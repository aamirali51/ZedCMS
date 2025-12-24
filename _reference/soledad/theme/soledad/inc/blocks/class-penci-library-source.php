<?php

class Penci_Library_Source extends Elementor\TemplateLibrary\Source_Base {
	public function get_id() {
		return 'penci';
	}

	public function get_title() {
		return esc_html__( 'Penci', 'soledad' );
	}

	public function register_data() {
	}

	public function get_items( $args = [] ) {
		return [];
	}

	public function get_item( $template_id ) {
		$templates = $this->get_items();

		return $templates[ $template_id ];
	}


	private function get_template_content( $template_id ) {
		$response = wp_remote_get( 'https://library.pencidesign.net/wp-json/penci-blocks/v1/templates?id=' . $template_id );

		if ( is_wp_error( $response ) || ! is_array( $response ) ) {
			return $response;
		}

		$data = json_decode( $response['body'], true );

		if ( is_object( $data ) && isset( $data->error ) ) {
			die( __( 'Error whilst getting template', 'soledad' ) );
		}

		return $data;
	}

	public function get_data( array $args, $context = 'display' ) {
		if ( 'update' === $context ) {
			$data = $args['data'];
		} else {
			$data = $this->get_template_content( $args['template_id'] );
			self::penci_templ_remove_placeholer( $data['content'] );
		}

		if ( empty( $data ) || empty( $data['content'] ) ) {
			throw new \Exception( 'Template does not have any content' );
		}

		$data['content'] = $this->replace_elements_ids( $data['content'] );
		$data['content'] = $this->process_export_import_content( $data['content'], 'on_import' );


		// After the upload complete, set the elementor upload state back to false
		//Plugin::$instance->uploads_manager->set_elementor_upload_state( false );

		return $data;
	}

	public static function penci_templ_remove_placeholer( &$array ) {
		foreach ( $array as $key => &$value ) {
			if ( is_array( $value ) ) {
				if ( isset( $value['image'] ) && strpos( $value['image']['url'], 'placeholder' ) !== false ) {
					$value['image'] = [
						'url' => ''
					];
				}
				self::penci_templ_remove_placeholer( $value );
			}
		}
	}

	public function save_item( $template_data ) {
		return new WP_Error( 'invalid_request', 'Cannot save template to a remote source' );
	}

	public function update_item( $new_data ) {
		return new WP_Error( 'invalid_request', 'Cannot update template to a remote source' );
	}

	public function delete_template( $template_id ) {
		return new WP_Error( 'invalid_request', 'Cannot delete template from a remote source' );
	}


	public function export_template( $template_id ) {
		return new WP_Error( 'invalid_request', 'Cannot export template from a remote source' );
	}
}
