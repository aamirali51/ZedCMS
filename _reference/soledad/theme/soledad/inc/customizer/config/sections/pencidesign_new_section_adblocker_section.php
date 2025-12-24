<?php
$options   = [];
$options[] = array(
	'default'     => '',
	'sanitize'    => 'penci_sanitize_checkbox_field',
	'label'       => __( 'Ad Blocker Detector Pop-up', 'soledad' ),
	'id'          => 'penci_adblocker_popup',
	'description' => __( 'Block the adblockers from browsing the site, till they turn off the Ad Blocker. ', 'soledad' ),
	'type'        => 'soledad-fw-toggle',
);
$options[] = array(
	'default'  => __( 'Adblock Detected', 'soledad' ),
	'sanitize' => 'sanitize_text_field',
	'label'    => __( 'Pop-up Title', 'soledad' ),
	'id'       => 'penci_adblocker_popup_title',
	'type'     => 'soledad-fw-text',
);
$options[] = array(
	'sanitize' => 'penci_sanitize_textarea_field',
	'label'    => __( 'Pop-up Message', 'soledad' ),
	'id'       => 'penci_adblocker_popup_message',
	'default'  => __( 'Please support us by disabling your AdBlocker extension from your browsers for our website.', 'soledad' ),
	'type'     => 'soledad-fw-textarea',
);
$options[] = array(
	'sanitize' => 'penci_sanitize_textarea_field',
	'label'    => __( 'Button Text', 'soledad' ),
	'id'       => 'penci_adblocker_dissmiss',
	'default'  => __( 'Dismiss this message', 'soledad' ),
	'type'     => 'soledad-fw-text',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'penci_sanitize_number_field',
	'label'    => __( 'Number of seconds before displaying the message', 'soledad' ),
	'id'       => 'penci_adblocker_popup_time',
	'type'     => 'soledad-fw-number',
);
$options[] = array(
	'default'     => '',
	'sanitize'    => 'penci_sanitize_checkbox_field',
	'label'       => __( 'Is dismissable?', 'soledad' ),
	'id'          => 'penci_adblocker_popup_dismissable',
	'description' => __( 'Allow visitors to dismiss the message.', 'soledad' ),
	'type'        => 'soledad-fw-toggle',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'penci_sanitize_checkbox_field',
	'label'    => __( 'Don\'t show again after dismissing', 'soledad' ),
	'id'       => 'penci_adblocker_popup_onetime',
	'type'     => 'soledad-fw-toggle',
);
$options[] = array(
	'default'  => [],
	'sanitize' => 'penci_sanitize_choices_field',
	'label'    => __( 'Don\'t show for user group', 'soledad' ),
	'id'       => 'penci_adblocker_user_group',
	'type'     => 'soledad-fw-select',
	'multiple' => 999,
	'choices'  => call_user_func( function () {
		$roles = [];

		$wp_roles = new \WP_Roles();
		if ( ! empty( $wp_roles ) ) {
			foreach ( $wp_roles->roles as $role_name => $role_info ) {
				$roles[ $role_name ] = $role_info['name'];
			}
		}

		return $roles;
	} ),
);
$options[] = array(
	'label' => __( 'Popup Colors', 'soledad' ),
	'id'    => 'penci_adblocker_popup_heading',
	'type'  => 'soledad-fw-header',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'label'    => __( 'Popup Background Color', 'soledad' ),
	'id'       => 'penci_adblocker_popup_bgcolor',
	'type'     => 'soledad-fw-color',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'label'    => __( 'Popup Icon Color', 'soledad' ),
	'id'       => 'penci_adblocker_popup_icolor',
	'type'     => 'soledad-fw-color',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'label'    => __( 'Popup Heading Color', 'soledad' ),
	'id'       => 'penci_adblocker_popup_headingcolor',
	'type'     => 'soledad-fw-color',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'label'    => __( 'Popup Text Color', 'soledad' ),
	'id'       => 'penci_adblocker_popup_textcolor',
	'type'     => 'soledad-fw-color',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'label'    => __( 'Popup Button Color', 'soledad' ),
	'id'       => 'penci_adblocker_popup_btncolor',
	'type'     => 'soledad-fw-color',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'label'    => __( 'Popup Button Hover Color', 'soledad' ),
	'id'       => 'penci_adblocker_popup_btnhcolor',
	'type'     => 'soledad-fw-color',
);

return $options;