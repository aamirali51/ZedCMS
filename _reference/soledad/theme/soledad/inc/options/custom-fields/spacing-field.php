<?php if ( ! defined( 'ABSPATH' ) ) {
	die; } // Cannot access directly.
/**
 *
 * Field: spacings
 *
 * @since 1.0.0
 * @version 1.0.0
 */
if ( ! class_exists( 'CSF_Field_spacings' ) ) {
	class CSF_Field_spacings extends CSF_Fields {

		public function __construct( $field, $value = '', $unique = '', $where = '', $parent = '' ) {
			parent::__construct( $field, $value, $unique, $where, $parent );
			wp_enqueue_script( 'csf_spacings_field', PENCI_SOLEDAD_URL . '/inc/options/js/spacing-field.js', array( 'jquery' ), PENCI_SOLEDAD_VERSION, true );

		}

		public function render() {

			$options      = $this->field['options'];
			$saved_values = ( ! is_array( $this->value ) && ! empty( $this->value ) ) ? explode( ', ', $this->value ) : explode( ', ', '\'\', \'\', \'\', \'\', \'\', \'\', \'\', \'\', \'\', \'\', \'\', \'\', \'\', \'\', \'\', \'\'' );

			echo $this->field_before();

			echo '<div class="csf--inputs" data-depend-id="' . esc_attr( $this->field['id'] ) . '">';

			$labels = array(
				'margin'        => __( 'Margin', 'soledad' ),
				'padding'       => __( 'Padding', 'soledad' ),
				'border'        => __( 'Border Width', 'soledad' ),
				'border-radius' => __( 'Border Radius', 'soledad' ),
			);

			$icons = array(
				'0' => '<i class="fas fa-long-arrow-alt-up"></i>',
				'1' => '<i class="fas fa-long-arrow-alt-right"></i>',
				'2' => '<i class="fas fa-long-arrow-alt-down"></i>',
				'3' => '<i class="fas fa-long-arrow-alt-left"></i>',
			);

			foreach ( $options as $area => $props ) {

				echo '<div class="csf-desc-text"><h4>' . $labels[ $area ] . '</h4></div>';

				$icon_count = 0;

				switch ( $area ) {
					case 'padding':
						$count = 4;
						break;
					case 'border':
						$count = 8;
						break;
					case 'border-radius':
						$count = 12;
						break;
					default:
						$count = 0;
				}

				foreach ( $props as $prop => $value ) {

					$value_check = isset( $saved_values[ $count ] ) && $saved_values[ $count ] ? $saved_values[ $count ] : '';

					echo '<div class="csf--input">';
					echo '<span class="csf--label csf--icon">' . $icons[ $icon_count ] . '</span>';
					echo '<input type="number" placeholder="' . $value . '" value="' . $value_check . '" class="csf-input-number cfs-spacings-field ' . $prop . '" step="any" />';
					echo '</div>';

					++$count;
					++$icon_count;

				}
			}

			echo '</div>';

			echo '<input type="hidden" class="hidden cfs-spacings-saved-field" name="' . esc_attr( $this->field_name() ) . '" value="' . $this->value . '"/>';

			echo $this->field_after();
		}
	}
}
