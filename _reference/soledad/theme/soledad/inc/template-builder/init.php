<?php

class PenciTemplateBuilder {
	private static $instance;

	private function __construct() {

		if ( function_exists( 'is_penci_amp' ) && is_penci_amp() ) {
			return false;
		}

		require_once PENCI_SOLEDAD_DIR . '/inc/template-builder/helper.php';

		add_action( 'init', array( $this, 'post_type' ), 10 );
		add_action( 'get_header', [ $this, 'load_header_block' ], 10 );
		add_action( 'elementor/widgets/register', [ $this, 'register_archive_widget' ] );
		add_action( 'elementor/widgets/register', [ $this, 'register_single_widget' ] );
		add_action( 'elementor/widgets/register', [ $this, 'register_header_widget' ] );
		add_action( 'elementor/elements/categories_registered', array( $this, 'archive_widget_categories' ), 50 );
		add_filter( 'template_include', array( $this, 'builder_content' ), 99 );
		add_filter( 'template_include', array( $this, 'fof_content' ), 99 );
		add_filter( 'display_post_states', array( $this, 'fof_add_post_state' ), 10, 2 );
		add_action( 'template_redirect', array( $this, 'disable_frontview' ) );
		add_action( 'admin_notices', array( $this, 'notice_message' ) );
		add_filter( 'post_limits', array( $this, 'filter_main_archive_query' ), 0, 2 );
	}

	public static function getInstance() {
		if ( null === static::$instance ) {
			static::$instance = new static();
		}

		return static::$instance;
	}

	function filter_main_archive_query( $limit, $query ) {

		if ( ! is_admin() && $query->is_main_query() && penci_should_render_archive_template() ) {
			return 'LIMIT 0, 1';
		}

		return $limit;
	}


	public function notice_message() {
		global $pagenow, $post_type;
		if ( $pagenow == 'edit.php' && 'custom-post-template' == $post_type ) {
			?>
            <div style="background: #e7ffe1;" class="notice notice-success">
                <p style="font-size: 15px;"><?php _e( 'You can check <a href="https://soledad.pencidesign.net/soledad-document/#single-builder" target="_blank">this guide</a> to know how to create your own custom post template.', 'soledad' ); ?></p>
            </div>
			<?php
		}
		if ( $pagenow == 'edit.php' && 'archive-template' == $post_type ) {
			?>
            <div style="background: #e7ffe1;" class="notice notice-success">
                <p style="font-size: 15px;"><?php _e( 'You can check <a href="https://soledad.pencidesign.net/soledad-document/#archive-method2" target="_blank">this guide</a> to know how to create your own custom category, tag, search, author, archive templates.', 'soledad' ); ?></p>
            </div>
			<?php
		}
	}

	public function register_single_widget( $widgets_manager ) {

		$post_elements = [
			'breadcrumb',
			'title',
			'subtitle',
			'featured',
			'featured-overlay',
			'meta',
			'custom-field',
			'taxonomy',
			'smartlists',
			'content',
			'share',
			'author',
			'postpagination',
			'relatedpost',
			'comments',
		];

		foreach ( $post_elements as $pelement ) {
			require_once( __DIR__ . "/single-elements/{$pelement}.php" );
			$class_name = str_replace( '-', ' ', $pelement );
			$class_name = str_replace( ' ', '', ucwords( $class_name ) );
			$classname  = '\\PenciSingle' . $class_name;
			$widgets_manager->register( new $classname() );
		}
	}

	public function register_archive_widget( $widgets_manager ) {

		$archive_elements = [
			'title',
			'description',
			'breadcrumb',
			'taxonomy',
		];

		foreach ( $archive_elements as $aelement ) {
			require_once( __DIR__ . "/archive-elements/{$aelement}.php" );
			$classname = '\\PenciArchive' . ucwords( $aelement );
			$widgets_manager->register( new $classname() );
		}

	}

	public function load_header_block( $name ) {

		$header_block_id = $this->header_block_id();

		if ( ! $header_block_id ) {
			return;
		}

		require PENCI_SOLEDAD_DIR . '/inc/template-builder/header-elements/header.php';

		$templates = [];
		$name      = (string) $name;
		if ( '' !== $name ) {
			$templates[] = "header-{$name}.php";
		}

		$templates[] = 'header.php';

		// Avoid running wp_head hooks again
		remove_all_actions( 'wp_head' );
		ob_start();
		// It cause a `require_once` so, in the get_header it self it will not be required again.
		locate_template( $templates, true );
		ob_get_clean();
	}

	public function register_header_widget( $widgets_manager ) {

		$header_elements = [
			'logo',
			'menu',
			'search',
			'bookmark',
			'darkmode',
			'dropdownmenu',
			'hamburger',
		];

		if ( class_exists( 'WooCommerce' ) ) {
			$header_elements = array_merge( $header_elements, [
				'cart',
				'compare',
				'wishlist',
			] );
		}

		foreach ( $header_elements as $aelement ) {
			require_once( __DIR__ . "/header-elements/{$aelement}.php" );
			$classname = '\\PenciHeader' . ucwords( $aelement );
			$widgets_manager->register( new $classname() );
		}

	}

	public function archive_widget_categories( $elements_manager ) {
		// Add our categories
		$category_prefix = 'penci-';

		$elements_manager->add_category( $category_prefix . 'elements', [
			'title' => '[PenciDesign] Elements',
			'icon'  => 'fa fa-plug',
		] );

		$elements_manager->add_category( $category_prefix . 'custom-archive-builder', [
			'title' => '[PenciDesign] Archive Pages Builder',
			'icon'  => 'fa fa-plug',
		] );

		$elements_manager->add_category( $category_prefix . 'single-builder', [
			'title' => '[PenciDesign] Post Pages Builder',
			'icon'  => 'fa fa-plug',
		] );

		$elements_manager->add_category( $category_prefix . 'header-builder', [
			'title' => '[PenciDesign] Header Builder',
			'icon'  => 'fa fa-plug',
		] );

		// Hack into the private $categories member, and reorder it so our stuff is at the top
		$reorder_cats = function () use ( $category_prefix ) {
			uksort( $this->categories, function ( $keyOne, $keyTwo ) use ( $category_prefix ) {
				if ( substr( $keyOne, 0, 6 ) == $category_prefix ) {
					return - 1;
				}
				if ( substr( $keyTwo, 0, 6 ) == $category_prefix ) {
					return 1;
				}

				return 0;
			} );

		};

		$reorder_cats->call( $elements_manager );
	}

	public function post_type() {

		$show_archive = get_theme_mod( 'penci_hide_archive_builder' ) ? false : true;
		register_post_type( 'archive-template', array(
			'labels'              => array(
				'name'               => esc_html__( 'Archive Template', 'soledad' ),
				'singular_name'      => esc_html__( 'Archive Template', 'soledad' ),
				'menu_name'          => esc_html__( 'Archive Template', 'soledad' ),
				'add_new'            => esc_html__( 'New Archive Template', 'soledad' ),
				'add_new_item'       => esc_html__( 'Build Archive Template', 'soledad' ),
				'edit_item'          => esc_html__( 'Edit Archive Template', 'soledad' ),
				'new_item'           => esc_html__( 'New Archive Template Entry', 'soledad' ),
				'view_item'          => esc_html__( 'View Archive Template', 'soledad' ),
				'search_items'       => esc_html__( 'Search Archive Template', 'soledad' ),
				'not_found'          => esc_html__( 'No entry found', 'soledad' ),
				'not_found_in_trash' => esc_html__( 'No Archive Template in Trash', 'soledad' ),
				'parent_item_colon'  => ''
			),
			'description'         => esc_html__( 'Single Archive Template', 'soledad' ),
			'public'              => true,
			'show_ui'             => $show_archive,
			'show_in_nav_menus'   => $show_archive,
			'menu_position'       => 8,
			'menu_icon'           => 'dashicons-align-full-width',
			'capability_type'     => 'post',
			'hierarchical'        => false,
			'supports'            => array( 'title', 'editor' ),
			'map_meta_cap'        => true,
			'exclude_from_search' => true,
			'rewrite'             => array(
				'slug' => 'archive-template'
			)
		) );

		$show_post = get_theme_mod( 'penci_hide_post_builder' ) ? false : true;

		register_post_type( 'custom-post-template', array(
			'labels'              => array(
				'name'               => esc_html__( 'Post Template', 'soledad' ),
				'singular_name'      => esc_html__( 'Post Template', 'soledad' ),
				'menu_name'          => esc_html__( 'Post Template', 'soledad' ),
				'add_new'            => esc_html__( 'New Post Template', 'soledad' ),
				'add_new_item'       => esc_html__( 'Build Custom Post Template', 'soledad' ),
				'edit_item'          => esc_html__( 'Edit Post Template', 'soledad' ),
				'new_item'           => esc_html__( 'New Custom Post Template Entry', 'soledad' ),
				'view_item'          => esc_html__( 'View Custom Post Template', 'soledad' ),
				'search_items'       => esc_html__( 'Search Custom Post Template', 'soledad' ),
				'not_found'          => esc_html__( 'No entry found', 'soledad' ),
				'not_found_in_trash' => esc_html__( 'No Custom Post Template in Trash', 'soledad' ),
				'parent_item_colon'  => ''
			),
			'description'         => esc_html__( 'Custom Single Post Template', 'soledad' ),
			'public'              => true,
			'menu_icon'           => 'dashicons-media-document',
			'show_ui'             => $show_post,
			'show_in_nav_menus'   => $show_post,
			'menu_position'       => 9,
			'capability_type'     => 'post',
			'hierarchical'        => false,
			'supports'            => array( 'title', 'editor' ),
			'map_meta_cap'        => true,
			'exclude_from_search' => true,
			'rewrite'             => array(
				'slug' => 'post-template'
			)
		) );

	}

	public function header_block_id() {
		$header_site    = get_theme_mod( 'pchdbd_block_all' );
		$header_home    = get_theme_mod( 'pchdbd_block_homepage' );
		$header_archive = get_theme_mod( 'pchdbd_block_archive' );
		$header_page    = get_theme_mod( 'pchdbd_block_page' );
		$header_post    = get_theme_mod( 'pchdbd_block_post' );

		if ( is_home() || is_front_page() ) {
			$home_id = $header_home ? $header_home : $header_site;
			return $home_id ? $this->get_header_block_id( $home_id ) : '';
		} elseif ( is_archive() ) {
			
			if ( is_category() ) {
				
				$current_cat_id   = get_query_var( 'cat' );
				$category_options = get_option( "category_$current_cat_id" );

				if ( isset( $category_options['cat_header_block'] ) && $category_options['cat_header_block'] ) {
					return $this->get_header_block_id( $category_options['cat_header_block'] ) ;
				} else if ( $header_archive ) {
					return $this->get_header_block_id( $header_archive ) ;
				} elseif ( $header_site ) {
					return $this->get_header_block_id( $header_site ) ;
				}

			} else if ( $header_archive ) {
				return $this->get_header_block_id( $header_archive ) ;
			} elseif ( $header_site ) {
				return $this->get_header_block_id( $header_site ) ;
			}

		} elseif ( is_singular() && ! is_page() ) {
			$post_config = penci_get_single_key( get_the_ID(), 'penci_header_block_layout' );
			if ( $post_config ) {
				if ( is_numeric( $post_config ) ) {
					return $post_config;
				} else {
					return $this->get_header_block_id( $post_config ) ;
				}
			} elseif ( $header_post ) {
				return $this->get_header_block_id( $header_post ) ;
			} elseif ( $header_site ) {
				return $this->get_header_block_id( $header_site ) ;
			}
		} elseif ( is_page() ) {
			$page_config = get_post_meta( get_the_ID(), 'penci_pmeta_page_header', true );
			if ( isset( $page_config['header_block_layout'] ) && $page_config['header_block_layout'] ) {
				return $page_config['header_block_layout'];
			} elseif ( $header_page ) {
				return $this->get_header_block_id( $header_page ) ;
			} elseif ( $header_site ) {
				return $this->get_header_block_id( $header_page ) ;
			}
		}

		return false;
	}

	public function get_header_block_id( $page_slug ) {
		$page_data = get_page_by_path( $page_slug, OBJECT, 'penci-block' );
		if ( isset( $page_data->ID ) && $page_data->ID ) {
			return $page_data->ID;
		}

		return false;
	}

	public function builder_content( $template ) {

		global $post;

		if ( isset( $post->post_type ) && ( $post->post_type == 'archive-template' || $post->post_type == 'custom-post-template' ) && $this->is_preview() ) {
			$template = locate_template( array( 'inc/template-builder/page-edit.php' ) );
		}

		if ( penci_should_render_archive_template() && ! $this->is_preview() && ! is_embed() ) {
			$template = locate_template( array( 'inc/template-builder/archive-elements/render.php' ) );
		}

		if ( penci_should_render_single_template() && ! $this->is_preview() && ! is_embed() ) {
			$template = locate_template( array( 'inc/template-builder/single-elements/render.php' ) );
		}

		return $template;
	}

	public function is_preview() {
		$check = false;
		if ( class_exists( '\Elementor\Plugin' ) ) {
			$check = \Elementor\Plugin::$instance->preview->is_preview_mode() || \Elementor\Plugin::$instance->editor->is_edit_mode();
		}

		return $check;
	}

	public function disable_frontview() {

		if ( ( is_singular( 'archive-template' ) || is_singular( 'custom-post-template' ) ) && ! $this->is_preview() ) {
			wp_redirect( home_url( '/' ), 301 );
			die;
		}
	}

	public function fof_content( $template ) {
		if ( is_404() && get_theme_mod( 'fof_page' ) ) {
			$template = locate_template( array( 'inc/template-builder/other/404.php' ) );
		}

		return $template;
	}

	public function fof_add_post_state( $post_states, $post ) {

		$post_name = get_theme_mod( 'fof_page' );

		if ( $post_name && $post->post_name == $post_name && $post->post_type == 'page' ) {
			$post_states[] = __( '404 Page', 'solead' );
		}

		return $post_states;
	}

	public function preview_image( $url, $postid ) {
		if ( penci_elementor_is_edit_mode() && ( is_singular( 'custom-post-template' ) || is_singular( 'archive-template' ) ) ) {
			$url = PENCI_SOLEDAD_URL . '/inc/template-builder/placeholder.php?w=1200&h=800';
		}

		return $url;
	}

	public function preview_image_html( $html, $postid, $post_thumbnail_id, $size, $attr ) {
		if ( penci_elementor_is_edit_mode() && ( is_singular( 'custom-post-template' ) || is_singular( 'archive-template' ) ) ) {
			global $_wp_additional_image_sizes;
			$w = 1200;
			$h = 800;

			if ( isset( $_wp_additional_image_sizes[ $size ] ) ) {
				$w = $_wp_additional_image_sizes[ $size ]['width'];
				$h = $_wp_additional_image_sizes[ $size ]['height'];
			}

			$html = '<img src="' . PENCI_SOLEDAD_URL . '/inc/template-builder/placeholder.php?w=' . $w . '&h=' . $h . '" alt="" />';
		}

		return $html;
	}

}

PenciTemplateBuilder::getInstance();
