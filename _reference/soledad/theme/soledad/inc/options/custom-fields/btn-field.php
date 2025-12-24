<?php
if ( ! class_exists( 'CSF_Field_button' ) ) {
	class CSF_Field_button extends CSF_Fields {

		public function __construct( $field, $value = '', $unique = '', $where = '', $parent = '' ) {
			parent::__construct( $field, $value, $unique, $where, $parent );
			wp_enqueue_script( 'csf_btn_field', PENCI_SOLEDAD_URL . '/inc/options/js/btn-field.js', array( 'jquery' ), PENCI_SOLEDAD_VERSION, true );
		}

		public function render() {

			echo $this->field_before();

			$type = '';
			$none = '';

			if ( isset( $this->field['data_type'] ) && $this->field['data_type'] ) {
				$type = ' data-type="' . $this->field['data_type'] . '" ';
			}

			if ( isset( $this->field['nonce'] ) && $this->field['nonce'] ) {
				$none = ' data-nonce="' . $this->field['nonce'] . '" ';
			}

			echo '<button data-adminjax="' . esc_url( admin_url( 'admin-ajax.php' ) ) . '"' . $type . $none . 'class="button" type="button" name="' . $this->field_name() . '" value="' . $this->value . '"' . $this->field_attributes() . '>' . $this->field['title'] . '</button>';

			echo $this->field_after();
		}
	}
}
