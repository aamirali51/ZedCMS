<?php if ( ! defined( 'ABSPATH' ) ) {
	die; } // Cannot access directly.
/**
 *
 * Field: export
 *
 * @since 1.0.0
 * @version 1.0.0
 */
if ( ! class_exists( 'CSF_Field_export' ) ) {
	class CSF_Field_export extends CSF_Fields {

		public function __construct( $field, $value = '', $unique = '', $where = '', $parent = '' ) {
			parent::__construct( $field, $value, $unique, $where, $parent );
		}

		public function render() {

			$template = get_template();
			$mods     = get_theme_mods();
			$data     = array(
				'template' => $template,
				'mods'     => $mods ? $mods : array(),
				'options'  => array(),
			);

			$export_content = base64_encode( wp_json_encode( $data ) );

			echo $this->field_before();
			echo '<textarea readonly>' . $export_content . '</textarea>';
			echo $this->field_after();
		}
	}
}
