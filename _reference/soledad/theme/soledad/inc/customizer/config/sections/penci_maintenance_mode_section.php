<?php
$options           = [];
$options[]         = array(
	'id'          => 'penci_maintenance_mode',
	'default'     => '',
	'sanitize'    => 'penci_sanitize_choices_field',
	'type'        => 'soledad-fw-select',
	'label'       => esc_html__( 'Mode', 'soledad' ),
	'description' => esc_html__( 'Choose between Coming Soon mode (returning HTTP 200 code) or Maintenance Mode (returning HTTP 503 code).', 'soledad' ),
	'choices'     => [
		''             => __( 'Disable', 'soledad' ),
		'comming_soon' => __( 'Coming Soon', 'soledad' ),
		'maintenance'  => __( 'Maintenance', 'soledad' ),
	],
);
$block_options     = [];
$block_options[''] = __( '- Select -', 'soledad' );
$builder_layouts   = get_posts( [
	'post_type'      => 'penci-block',
	'posts_per_page' => - 1,
] );
foreach ( $builder_layouts as $builder_builder ) {
	$block_options[ $builder_builder->ID ] = $builder_builder->post_title;
}
/* Saved Layout */
$options[] = array(
	'id'          => 'penci_maintenance_mode_template',
	'default'     => '',
	'sanitize'    => 'penci_sanitize_choices_field',
	'type'        => 'soledad-fw-select',
	'label'       => esc_html__( 'Choose Penci Block', 'soledad' ),
	'description' => esc_html__( 'To enable maintenance/coming soon mode you have to set a Penci Block for the maintenance mode page. Select one or go ahead and create one now. ', 'soledad' ),
	'choices'     => $block_options,
);
$options[] = array(
	'id'       => 'penci_maintenance_mode_access',
	'default'  => 'logged_in',
	'sanitize' => 'penci_sanitize_choices_field',
	'type'     => 'soledad-fw-select',
	'label'    => esc_html__( 'Who Can Access', 'soledad' ),
	'choices'  => [
		'logged_in' => __( 'Logged In', 'soledad' ),
		'custom'    => __( 'Custom', 'soledad' ),
	],
);
$options[] = array(
	'default'  => [],
	'sanitize' => 'penci_sanitize_choices_field',
	'label'    => __( 'Custom User Group Can Access', 'soledad' ),
	'id'       => 'penci_maintenance_mode_custom_roles',
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

return $options;