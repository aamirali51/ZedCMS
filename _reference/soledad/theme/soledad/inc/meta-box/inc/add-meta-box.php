<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * Register a meta box using a class.
 */
class Penci_Add_Meta_Box {

	/**
	 * Meta box parameters.
	 *
	 * @var array
	 */
	public $meta_box;

	/**
	 * Constructor.
	 */
	public function __construct( $meta_box ) {
		if( ! $meta_box  ) {
			return;
		}

		$this->meta_box = $meta_box;

		if ( is_admin() ) {
			add_action( 'load-post.php',     array( $this, 'init_metabox' ) );
			add_action( 'load-post-new.php', array( $this, 'init_metabox' ) );
		}
	}

	/**
	 * Meta box initialization.
	 */
	public function init_metabox() {
		add_action( 'add_meta_boxes', array( $this, 'add_metabox'  )        );
		add_action( 'save_post',      array( $this, 'save_metabox' ), 10, 2 );
	}

	/**
	 * Adds the meta box.
	 */
	public function add_metabox() {

		$metabox = $this->meta_box;

		if ( isset( $metabox['fields'] ) ) {
			unset( $metabox['fields'] );
		}

		add_meta_box(
			$metabox['id'],
			$metabox['title'],
			array( $this, 'render_metabox' ),
			$metabox['post_types'],
			$metabox['context'],
			$metabox['priority']
		);

	}

	/**
	 * Renders the meta box.
	 */
	public function render_metabox( $post ) {

		$metabox = $this->meta_box;
		$tabs = isset( $metabox['tabs'] ) ? $metabox['tabs'] : array();

		$fields = isset( $metabox['fields'] ) ? $metabox['fields'] : array();
		if( ! $fields ) {
			return;
		}

		echo '<div class="penci-metabox-wrap">';

		if( $tabs ){
			echo '<ul class="penci-metabox-tabs">';
			$i = 0;
			foreach ( $tabs as $key => $tab_data ) {
				$class = "tab-$key";
				if ( ! $i ) {
					$class .= ' tab-active';
				}
				printf(
					'<li class="%s" data-panel="%s"><a href="#">%s%s</a></li>',
					esc_attr( $class ),
					esc_attr( $key ),
					$tab_data['icon'] ? '<i class="' . esc_attr( $tab_data['icon'] ) . '"></i>' : '',
					$tab_data['label'] ? $tab_data['label'] : ''
				);

				$i ++;
			} // End foreach().
			echo '</ul>';

			$group_tabs = array();
			foreach ( (array)$fields as  $field ){
				if ( ! isset( $field['tab'] ) ) {
					continue;
				}

				$tab_key = $field['tab'];

				$group_tabs[ $tab_key ][] = $field;
			}

			echo '<div class="penci-metabox-fields">';
			foreach ( (array)$group_tabs as $tab =>  $fields ){

				echo '<div class="penci-tab-panel penci-tab-panel-' . esc_attr( $tab ) . '">';
				foreach ( (array)$fields as  $field ){
					PENCI_FW_MetaBox_Fields::html_field( $field, $post->ID, 'post', $tab );
				}
				echo '</div>';
			}
			echo '</div>';
		}else{
			echo '<div class="penci-metabox-fields">';
			foreach ( (array)$fields as  $field ){
				PENCI_FW_MetaBox_Fields::html_field( $field, $post->ID );
			}
			echo '</div>';
		}


		echo '</div>';
	}


	/**
	 * Save the meta when the post is saved.
	 *
	 * @param $post_id
	 *
	 * @return mixed
	 */
	public function save_metabox( $post_id ) {



		$metabox = $this->meta_box;

		$metabox_post_type = isset( $metabox['post_types'] ) ? $metabox['post_types'] : array();
		$current_post_type = isset( $_POST['post_type'] ) ? $_POST['post_type'] : '';

		if( ! in_array( $current_post_type, $metabox_post_type ) ) {
			return;
		}


		$tabs = isset( $metabox['tabs'] ) ? $metabox['tabs'] : array();

		$fields = isset( $metabox['fields'] ) ? $metabox['fields'] : array();
		if( ! $fields ) {
			return;
		}

		if( $tabs ){
			$group_tabs = array();
			foreach ( (array)$fields as  $field ){
				if ( ! isset( $field['tab'] ) ) {
					continue;
				}

				$tab_key  = isset( $field['tab'] ) ? $field['tab'] : '';
				$field_id = isset( $field['id'] ) ? $field['id'] : '';

				if( ! $tab_key || ! $field_id ) {
					continue;
				}

				$value_field = isset( $_POST[ $field_id ] ) ? $_POST[ $field_id ] : '';
				if ( 'checkbox' == $field['type'] ) {
					$value_field =  $value_field ? '1' : '';
				}

				$group_tabs[ $tab_key ][$field_id] = $value_field;
			}

			foreach ( (array) $group_tabs as $tab => $fields ) {
				update_post_meta( $post_id, 'penci_pmeta_' . $tab, $fields );
			}
		}else{
			foreach ( (array)$fields as  $field ){
				$this->save_field( $field, $post_id );
			}
		}
	}

	/**
	 * Save field
	 *
	 * @param $field
	 * @param $post_id
	 */
	public function save_field( $field, $post_id ) {
		$defaults = array(
			'id'   => '',
			'type' => ''
		);
		$field    = wp_parse_args( $field, $defaults );

		$field_id    = $field['id'];
		$value_field = isset( $_POST[ $field_id ] ) ? $_POST[ $field_id ] : '';
		$value_field = sanitize_text_field( $value_field );

		if ( 'checkbox' == $field['type'] ) {
			update_post_meta( $post_id, $field_id, ( $value_field ? '1' : '' ) );
		} else {
			update_post_meta( $post_id, $field_id, $value_field );
		}


	}

}