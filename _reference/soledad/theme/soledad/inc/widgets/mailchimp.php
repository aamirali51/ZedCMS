<?php
add_action( 'mc4wp_form_widget_form', function ( $settings, $widget ) {
	$penci_mailchimp_style = isset( $settings['penci_mailchimp_style'] ) ? $settings['penci_mailchimp_style'] : 's1';
	?>
    <p>
        <label for="<?php echo esc_attr( $widget->get_field_id( 'penci_mailchimp_style' ) ); ?>"><?php esc_html_e( 'Mailchimp Style:', 'soledad' ); ?></label>
        <select id="<?php echo esc_attr( $widget->get_field_id( 'penci_mailchimp_style' ) ); ?>"
                name="<?php echo esc_attr( $widget->get_field_name( 'penci_mailchimp_style' ) ); ?>"
                class="widefat penci_mailchimp_style" style="width:100%;">
			<?php
			$options = [
				's1'  => __( 'Default', 'soledad' ),
				's5'  => __( 'Style 1', 'soledad' ),
				's6'  => __( 'Style 2', 'soledad' ),
				's7'  => __( 'Style 3', 'soledad' ),
				's8'  => __( 'Style 4', 'soledad' ),
				's9'  => __( 'Style 5', 'soledad' ),
				's10' => __( 'Style 6', 'soledad' ),
				's11' => __( 'Style 7', 'soledad' ),
				's12' => __( 'Style 8', 'soledad' ),
				's13' => __( 'Style 9', 'soledad' ),
				's14' => __( 'Style 10', 'soledad' ),
				's15' => __( 'Style 11', 'soledad' ),
			];

			foreach ( $options as $value => $label ) {
				printf(
					'<option value="%s" %s>%s</option>',
					esc_attr( $value ),
					selected( $penci_mailchimp_style, $value, false ),
					esc_html__( $label, 'soledad' )
				);
			}
			?>
        </select>
        <span style="display: block;margin-top: 10px;"><?php _e( 'This style does not apply to the Header/Footer Signup Form.', 'soledad' ); ?></span>
    </p>
	<?php
}, 10, 2 );

add_filter( 'mc4wp_form_widget_update_settings', function ( $new_settings, $old_settings, $widget ) {
	if ( ! empty( $new_settings['penci_mailchimp_style'] ) ) {
		$new_settings['penci_mailchimp_style'] = sanitize_text_field( $new_settings['penci_mailchimp_style'] );
	}

	return $new_settings;

}, 10, 3 );

add_filter( 'widget_display_callback', function ( $instance, $widget, $args ) {
	// Disable Mailchimp widget in specific sidebars
	if ( isset( $args['id'] ) && in_array( $args['id'], [ 'footer-signup-form', 'header-signup-form' ], true ) ) {
		return $instance; // Prevents the widget from being displayed
	}

	if ( isset( $args['widget_id'] ) && strpos( $args['widget_id'], 'mc4wp_form_widget' ) !== false ) {
		// Ensure the instance contains the correct setting
		$penci_form_style = isset( $instance['penci_mailchimp_style'] ) ? $instance['penci_mailchimp_style'] : 's1';

		// Modify the widget output directly using output buffering
		ob_start();
		$widget->widget( $args, $instance );
		$output = ob_get_clean();

		// Inject the correct class into the widget wrapper
		if ( preg_match( '/class="([^"]*)"/', $output ) ) {
			$output = preg_replace(
				'/class="([^"]*)"/',
				'class="penci-mc4wp-widget penci-mailchimp-' . esc_attr( $penci_form_style ) . ' $1"',
				$output,
				1
			);
		} else {
			// If class attribute doesn't exist, add it
			$output = preg_replace(
				'/<div/',
				'<div class="penci-mc4wp-widget penci-mailchimp-' . esc_attr( $penci_form_style ) . '"',
				$output,
				1
			);
		}

		echo $output;

		return false; // Prevents the default widget output from being displayed
	}

	return $instance;
}, 10, 3 );