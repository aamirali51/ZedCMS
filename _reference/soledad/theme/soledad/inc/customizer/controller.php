<?php
/**
 * Custom controller numbers type and categories type
 *
 * @package Wordpress
 * @since 1.0
 */
add_action( 'customize_register', 'pencidesign_customize_register' );
function pencidesign_customize_register( $wp_customize ) {
	class Customize_Number_Control extends WP_Customize_Control {
		public $type = 'number';

		public function render_content() {
			?>
            <label>
                <span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
				<?php if ( $this->description ) { ?>
                    <p class="description pheading-description"><?php echo $this->description; ?></p>
				<?php } ?>
                <input type="number" name="quantity" <?php $this->link(); ?>
                       value="<?php echo esc_textarea( $this->value() ); ?>" style="width:70px;">
            </label>
			<?php
		}
	}

	class Customize_CustomCss_Control extends WP_Customize_Control {
		public $type = 'custom_css';

		public function render_content() {
			?>
            <label>
                <span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
                <textarea
                        style="width:100%; height:150px;" <?php $this->link(); ?>><?php echo esc_textarea( $this->value() ); ?></textarea>
            </label>
			<?php
		}
	}

	class Penci_Customize_Heading_Control extends WP_Customize_Control {
		public $type = 'heading';

		public function render_content() {
			?>
            <label>
                <h2 class="customize-control-title"
                    style="text-transform: uppercase; text-align: center;"><?php echo esc_html( $this->label ); ?></h2>
				<?php if ( $this->description ) { ?>
                    <p class="description pheading-description"><?php echo $this->description; ?></p>
				<?php } ?>
                <hr style="border-top:1px solid #111;"/>
            </label>
			<?php
		}
	}

	class Penci_Customize_List_Menus_Control extends WP_Customize_Control {
		public $type = 'list_menus';

		public function render_content() {
			?>
            <label>
                <span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
                <select name="_customize-input-<?php echo $this->id; ?>"
                        id="_customize-input-<?php echo $this->id; ?>" <?php $this->link(); ?>>
                    <option value=""><?php esc_html_e( '— Select —', 'soledad' ); ?></option>
					<?php
					$menus = get_terms( 'nav_menu' );
					foreach ( $menus as $menu ) {
						$menu_name = $menu->name;
						?>
                        <option value="<?php echo $menu_name; ?>"<?php if ( $menu_name == $this->value() ): echo ' select="selected"'; endif; ?>><?php echo $menu_name; ?></option>
						<?php
					}
					?>
                </select>
            </label>
			<?php
		}
	}

	class Penci_Customize_Control_Checkbox_Multiple extends WP_Customize_Control {

		public $type = 'checkbox-multiple';

		public function enqueue() {
			wp_enqueue_script( 'penci-customize-controls-multi-checkbox', trailingslashit( PENCI_SOLEDAD_URL ) . 'inc/customizer/js/multi-select.js', array( 'jquery' ), '0.1', true );
		}

		public function render_content() {

			if ( empty( $this->choices ) ) {
				return;
			} ?>

			<?php if ( ! empty( $this->label ) ) : ?>
                <span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
			<?php endif; ?>

			<?php if ( ! empty( $this->description ) ) : ?>
                <span class="description customize-control-description"><?php echo $this->description; ?></span>
			<?php endif; ?>

			<?php $multi_values = ! is_array( $this->value() ) ? explode( ',', $this->value() ) : $this->value(); ?>

            <ul>
				<?php foreach ( $this->choices as $value => $label ) : ?>

                    <li>
                        <label>
                            <input type="checkbox"
                                   value="<?php echo esc_attr( $value ); ?>" <?php checked( in_array( $value, $multi_values ) ); ?> />
							<?php echo esc_html( $label['name'] ); ?>
                        </label>
                    </li>

				<?php endforeach; ?>
            </ul>

            <input id="_customize-input-<?php echo $this->id; ?>" name="_customize-input-<?php echo $this->id; ?>"
                   type="hidden" <?php $this->link(); ?>
                   value="<?php echo esc_attr( implode( ',', $multi_values ) ); ?>"/>
		<?php }
	}
}

if ( class_exists( 'WP_Customize_Control' ) ) {
	class WP_Customize_Category_Control extends WP_Customize_Control {
		public function render_content() {
			$dropdown = wp_dropdown_categories( array(
				'name'              => '_customize-dropdown-categories-' . $this->id,
				'echo'              => 0,
				'show_option_none'  => esc_html__( '- Select -', 'soledad' ),
				'option_none_value' => '0',
				'selected'          => $this->value(),
			) );

			// Hackily add in the data link parameter.
			$dropdown = str_replace( '<select', '<select ' . $this->get_link(), $dropdown );

			printf( '<label class="customize-control-select"><span class="customize-control-title">%s</span> %s</label>', $this->label, $dropdown );
		}
	}

}

/**
 * Add customize documentation for Soledad
 *
 * @since 4.0
 *
 */
add_action( 'customize_controls_enqueue_scripts', 'penci_customizer_documentation' );
/**
 * Enqueue script for customizer control
 */
if ( ! function_exists( 'penci_customizer_documentation' ) ) {
	function penci_customizer_documentation() {
		wp_enqueue_script( 'penci-customizer', PENCI_SOLEDAD_URL . '/js/customizer.js', array( 'jquery' ), PENCI_SOLEDAD_VERSION, true );
		wp_localize_script( 'penci-customizer', 'SoledadCustomizerDoc',
			array(
				'docs' => 'View Documentation'
			)
		);
	}
}
?>
