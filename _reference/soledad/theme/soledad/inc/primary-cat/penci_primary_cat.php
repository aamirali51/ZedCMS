<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Penci_Primary_Category {

	/**
	 * Constructor.
	 */
	public function __construct() {

		if ( function_exists('yoast_get_primary_term_id') || class_exists( 'RankMath' ) ) {
			return;
		}
		

		$this->register_hooks();
	}

	/**
	 * Register Hooks.
	 */
	public function register_hooks() {
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_script' ), 10, 1 );
		add_action( 'admin_footer', array( $this, 'include_selection_template' ) );
		add_action( 'save_post', array( $this, 'save_primary_terms' ), 10, 2 );

		$post_types = get_post_types( [ 'show_in_rest' => true ] );
		add_filter( 'wp_insert_post_data', [ $this, 'update_data' ], 9999, 2 );
		foreach ( $post_types as $post_type ) {
			add_filter( "rest_pre_insert_$post_type", [ $this, 'modified_params' ], 99, 2 );
		}
	}

	/**
	 * Enqueue Script.
	 *
	 * @param string $hook_suffix - Page hook suffix.
	 */
	public function enqueue_script( $hook_suffix ) {

		if ( ! $this->is_post_edit( $hook_suffix ) ) {
			return;
		}

		$post_taxonomies = $this->get_post_taxonomies();

		if ( empty( $post_taxonomies ) ) {
			return;
		}

		// Get current screen to determine the script name to be enqueued.
		$current_screen = get_current_screen();
		$script_name    = 'classic-editor.js';
		$asset_file     = 'classic-editor.asset.php';

		if (
			isset( $current_screen->is_block_editor )
			&& $current_screen->is_block_editor
		) {
			$script_name = 'gutenberg.js';
			$asset_file  = 'gutenberg.asset.php';
		}

		$assets = require_once PENCI_SOLEDAD_DIR . '/inc/primary-cat/dist/' . $asset_file;

		wp_register_script(
			'pcpricat-taxonomy',
			PENCI_SOLEDAD_URL . '/inc/primary-cat/dist/' . $script_name,
			array_merge(
				$assets['dependencies'],
				array( 'jquery' )
			),
			$assets['version'],
			true
		);
		wp_enqueue_script( 'pcpricat-taxonomy' );

		wp_localize_script(
			'pcpricat-taxonomy',
			'pcpricatData',
			array(
				'taxonomies' => array_map(
					array( $this, 'get_taxonomies_for_js' ),
					$post_taxonomies
				),
			)
		);
	}

	/**
	 * Include Primary Term Selection Template.
	 */
	public function include_selection_template() {
		if ( ! $this->is_post_edit() ) {
			return;
		}

		?>
        <style>
            .pcpricat-primary-term select { width: 100% }
        </style>

        <script type="text/html" id="tmpl-pcpricat-select-primary-term">
            <div class="pcpricat-primary-term">
                <h4 class="pcpricat-primary-term-heading">
                    <?php
                    /* translators: %s expands to taxonomy title. */
                    echo sprintf( esc_html__( 'Primary %s', 'soledad' ), '{{data.taxonomy.title}}' );
                    ?>
                </h4>
                <select id="pcpricat-primary-term-{{data.taxonomy.name}}" name="pcpricat_primary_term_{{data.taxonomy.name}}">
                    <option value="-1">
                        <?php
                        /* translators: %s expands to taxonomy title. */
                        echo sprintf( esc_html__( '— Select Primary %s —', 'soledad' ), '{{data.taxonomy.title}}' );
                        ?>
                    </option>
                    <# _( data.taxonomy.terms ).each( function( term ) { #>
                        <option value="{{term.id}}"
                        <# if ( data.taxonomy.primary === term.id ) { #>
                            selected
                        <# } #>
                        >{{term.name}}</option>
                    <# }); #>
                </select>
                <?php wp_nonce_field( 'pcpricat-save-primary-term', 'pcpricat_save_primary_{{data.taxonomy.name}}_nonce' ); ?>
            </div>
        </script>
        <?php
	}

	/**
	 * Save primary terms on post submit.
	 *
	 * @param integer $post_id - Post id.
	 * @param WP_Post $post    - Post object.
	 */
	public function save_primary_terms( $post_id, $post ) {
		$post_taxonomies = $this->get_post_taxonomies( $post_id );

		foreach ( $post_taxonomies as $post_taxonomy ) {
			$this->save_primary_term( $post_id, $post_taxonomy );
		}
	}

	/**
	 * Returns Primary Term of the editing post.
	 *
	 * @param string $taxonomy - Taxonomy name.
	 * @return integer
	 */
	public function get_primary_term( $taxonomy ) {

		$primary_term = (int) get_post_meta(
			$this->get_current_post_id(),
			'pcpricat_primary_' . $taxonomy,
			true
		);


		$post_terms = get_the_terms( $this->get_current_post_id(), $taxonomy );

		if ( ! $post_terms || is_wp_error( $post_terms ) ) {
			$post_terms = array();
		}

		if ( ! in_array( $primary_term, wp_list_pluck( $post_terms, 'term_id' ), true ) ) {
			$primary_term = false;
		}

		return $primary_term;
	}

	/**
	 * Save Primary Term of a post.
	 *
	 * @param integer     $post_id  - Post id.
	 * @param WP_Taxonomy $taxonomy - Taxonomy object.
	 */
	public function save_primary_term( $post_id, $taxonomy ) {
		
		$primary_term = false;
		if ( isset( $_POST[ 'pcpricat_primary_term_' . $taxonomy->name ] ) ) {
			$primary_term = (int) sanitize_text_field(
				wp_unslash( $_POST[ 'pcpricat_primary_term_' . $taxonomy->name ] )
			);
		}

		if ( ! $primary_term ) {
			return;
		}

		check_admin_referer(
			'pcpricat-save-primary-term',
			'pcpricat_save_primary_' . $taxonomy->name . '_nonce'
		);

		update_post_meta(
			$post_id,
			'pcpricat_primary_' . $taxonomy->name,
			$primary_term
		);
	}

	/**
	 * Returns true if the current page is post edit page.
	 *
	 * @param string $hook_suffix - Page hook suffix.
	 * @return boolean
	 */
	public function is_post_edit( $hook_suffix = '' ) {
		if ( '' === $hook_suffix ) {
			global $pagenow;
			$hook_suffix = $pagenow;
		}

		return 'post-new.php' === $hook_suffix || 'post.php' === $hook_suffix;
	}

	/**
	 * Get Post Type Taxonomies.
	 *
	 * @param string $post_id - Post id.
	 * @return array
	 */
	public function get_post_taxonomies( $post_id = 0 ) {

		if ( ! $post_id ) {
			$post_id = $this->get_current_post_id();
		}

		$taxonomies = wp_cache_get( 'pcpricat_post_taxonomies_' . $post_id, 'pcpricat' );

		if ( false !== $taxonomies ) {
			return $taxonomies;
		}

		$post_type  = get_post_type( $post_id );
		$taxonomies = get_object_taxonomies( $post_type, 'objects' );

		foreach ( $taxonomies as $taxonomy_name => $taxonomy ) {
			if ( ! $taxonomy->hierarchical ) {
				unset( $taxonomies[ $taxonomy_name ] );
			}
		}

		wp_cache_set( 'pcpricat_post_taxonomies_' . $post_id, $taxonomies, 'pcpricat' );

		return $taxonomies;
	}

	/**
	 * Return Current Post ID.
	 *
	 * @return integer
	 */
	public function get_current_post_id() {
		return (int) filter_input( INPUT_GET, 'post', FILTER_SANITIZE_NUMBER_INT );
	}

	/**
	 * Get taxonomy data for JS in array.
	 *
	 * @param WP_Taxonomy $taxonomy - WP Taxonomy object.
	 * @return array
	 */
	public function get_taxonomies_for_js( $taxonomy ) {
		return array(
			'name'     => $taxonomy->name,
			'title'    => $taxonomy->labels->singular_name,
			'primary'  => $this->get_primary_term( $taxonomy->name ),
			'restBase' => $taxonomy->rest_base,
			'terms'    => array_map(
				array( $this, 'get_terms_for_js' ),
				get_terms( $taxonomy->name )
			),
		);
	}

	/**
	 * Get term data for JS in array.
	 *
	 * @param WP_Term $term - WP term object.
	 * @return array
	 */
	public function get_terms_for_js( $term ) {
		return array(
			'id'   => $term->term_id,
			'name' => $term->name,
		);
	}

	public function modified_params( $prepared_post, $request ) {
		if ( 'PUT' !== $request->get_method() ) {
			return $prepared_post;
		}

		$params = $request->get_params();

		if ( isset( $params['meta']['pcpricat_primary_term_category'] ) ) {
			$prepared_post->pcpricat_primary_term_category = $params['meta']['pcpricat_primary_term_category'];
		}

		$post_taxonomies = $this->get_post_taxonomies();

		foreach ( $post_taxonomies as $post_taxonomy ) {
			$post_taxonomy = $post_taxonomy->name;
			if ( isset( $params['meta']['pcpricat_primary_term_'.$post_taxonomy ] ) ) {
				$prepared_post->{'pcpricat_primary_term_'.$post_taxonomy} = $params['meta']['pcpricat_primary_term_'.$post_taxonomy];
			}
		}

		return $prepared_post;
	}

	public function update_data( $data, $postarr ) {
		if ( ! isset( $postarr['ID'] ) ) {
			return $data;
		}

		$post_id = $postarr['ID'];

		$post_taxonomies = $this->get_post_taxonomies( $post_id );

		foreach ( $post_taxonomies as $post_taxonomy ) {

			$post_taxonomy = $post_taxonomy->name;
			
			if ( isset( $postarr['pcpricat_primary_term_'.$post_taxonomy] ) ) {
				update_post_meta(
					$post_id,
					'pcpricat_primary_' . $post_taxonomy,
					$postarr['pcpricat_primary_term_'.$post_taxonomy]
				);
			}
		}

		return $data;
	}
}
new Penci_Primary_Category();