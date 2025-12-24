<?php if ( ! defined( 'ABSPATH' ) ) {
	die; } // Cannot access directly.
/**
 *
 * Field: import
 *
 * @since 1.0.0
 * @version 1.0.0
 */
if ( ! class_exists( 'CSF_Field_import' ) ) {
	class CSF_Field_import extends CSF_Fields {

		public function __construct( $field, $value = '', $unique = '', $where = '', $parent = '' ) {
			parent::__construct( $field, $value, $unique, $where, $parent );
		}

		public function render() {

			$nonce = wp_create_nonce( 'penci_options_import_data' );

			echo $this->field_before();
			echo '<textarea></textarea>';
			echo $this->field_after();
			echo '<button data-nonce="' . $nonce . '" data-ajaxurl="' . esc_url( admin_url( 'admin-ajax.php' ) ) . '" class="button">' . __( 'Import Theme Settings', 'soledad' ) . '</button>';
		}
	}
}
