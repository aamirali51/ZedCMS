<?php if ( ! defined( 'ABSPATH' ) ) {
	die; } // Cannot access directly.
/**
 *
 * Field: sizes
 *
 * @since 1.0.0
 * @version 1.0.0
 */
if ( ! class_exists( 'CSF_Field_sizes' ) ) {
	class CSF_Field_sizes extends CSF_Fields {

		public function __construct( $field, $value = '', $unique = '', $where = '', $parent = '' ) {
			parent::__construct( $field, $value, $unique, $where, $parent );
		}

		public function render() {

			$ids     = $this->field['ids'];
			$options = $this->field['options'];

			echo $this->field_before();

			echo '<div class="csf--inputs" data-depend-id="' . esc_attr( $this->field['id'] ) . '">';

			$icons = array(
				'mobile'  => '<i class="dashicons dashicons-smartphone"></i>',
				'desktop' => '<i class="dashicons dashicons-desktop"></i>',
			);

			foreach ( $ids as $device => $id ) {

				$item_value = get_theme_mod( $id );

				$icon = $icons[ $device ];

				if ( count( $ids ) == 1 ) {
					$icon = '<i class="dashicons dashicons-admin-site-alt3"></i>';
				}

				$default = isset( $options[ $device ]['default'] ) && $options[ $device ]['default'] ? $options[ $device ]['default'] : '';

				echo '<div class="csf--input">';
					echo '<span class="csf--label csf--icon">' . $icon . '</span>';
					echo '<input type="number" name="' . $id . '" placeholder="' . $default . '" value="' . $item_value . '" class="csf-input-number" step="any" />';
					echo '</div>';
			}

			echo '</div>';

			echo $this->field_after();
		}
	}
}
