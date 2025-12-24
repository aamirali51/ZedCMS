<?php
$options   = array();
$options[] = array(
	'id'          => 'penci_epopup_enable',
	'default'     => false,
	'sanitize'    => 'penci_sanitize_checkbox_field',
	'label'       => __( 'Enable Exit Intent Popup', 'soledad' ),
	'description' => __( 'Show popup when user leave the site.', 'soledad' ),
	'type'        => 'soledad-fw-toggle',
);
$options[] = array(
	'id'        => 'penci_epopup_html_content',
	'default'   => '',
	'sanitize'  => 'penci_sanitize_textarea_field',
	'label'     => __( 'Load Popup Content Using Shortcode/HTML', 'soledad' ),
	'type'      => 'soledad-fw-code',
	'code_type' => 'text/html',
);
$options[] = array(
	'id'          => 'penci_epopup_block',
	'default'     => '',
	'sanitize'    => 'penci_sanitize_choices_field',
	'label'       => __( 'Popup Content', 'soledad' ),
	'description' => __( 'You can add new or edit a Penci Block on <a class="wp-customizer-link" target="_blank" href="' . esc_url( admin_url( '/edit.php?post_type=penci-block' ) ) . '">this page</a>', 'soledad' ),
	'type'        => 'soledad-fw-ajax-select',
	'choices'     => call_user_func( function () {
		$builder_layout  = [ '' => __( '- Select -', 'soledad' ) ];
		$builder_layouts = get_posts( [
			'post_type'      => 'penci-block',
			'posts_per_page' => - 1,
		] );

		foreach ( $builder_layouts as $builder_builder ) {
			$builder_layout[ $builder_builder->post_name ] = $builder_builder->post_title;
		}

		return $builder_layout;
	} ),
);
$options[] = array(
	'id'       => 'penci_epopup_animation',
	'default'  => 'move-to-top',
	'sanitize' => 'penci_sanitize_choices_field',
	'label'    => __( 'Popup Open Animation', 'soledad' ),
	'type'     => 'soledad-fw-select',
	'choices'  => [
		'move-to-left'   => __( 'Move To Left', 'soledad' ),
		'move-to-right'  => __( 'Move To Right', 'soledad' ),
		'move-to-top'    => __( 'Move To Top', 'soledad' ),
		'move-to-bottom' => __( 'Move To Bottom', 'soledad' ),
		'fadein'         => __( 'Fade In', 'soledad' ),
	]
);
return $options;