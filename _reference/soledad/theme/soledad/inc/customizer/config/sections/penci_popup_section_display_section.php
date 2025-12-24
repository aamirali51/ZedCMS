<?php
$options   = [];
$options[] = array(
	'id'       => 'penci_popup_show_after',
	'default'  => 'all_pages',
	'sanitize' => 'penci_sanitize_choices_field',
	'label'    => __( 'Popup Event', 'soledad' ),
	'type'     => 'soledad-fw-select',
	'choices'  => [
		'all_pages' => __( 'Show on all pages, no using cookies', 'soledad' ),
		'time'      => __( 'One Time - close forever when users close popup', 'soledad' ),
		'section'   => __( 'Section', 'soledad' ),
		'fixtime'   => __( 'After Fixed Time', 'soledad' ),
	],
);
$options[] = array(
	'id'        => 'penci_popup_html_content',
	'default'   => '',
	'sanitize'  => 'penci_sanitize_textarea_field',
	'label'     => __( 'Load Popup Content Using Shortcode/HTML', 'soledad' ),
	'type'      => 'soledad-fw-code',
	'code_type' => 'text/html',
);
$options[] = array(
	'id'          => 'penci_popup_block',
	'default'     => '',
	'sanitize'    => 'penci_sanitize_choices_field',
	'label'       => __( 'Load Popup Content Using Penci Block', 'soledad' ),
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
	'id'          => 'penci_popup_show_after_time',
	'default'     => '2000',
	'sanitize'    => 'penci_sanitize_text_field',
	'label'       => __( 'Popup Delay', 'soledad' ),
	'description' => __( 'Show popup after some time (in milliseconds). Apply for "Some Time" Setting.', 'soledad' ),
	'type'        => 'soledad-fw-text'
);
$options[] = array(
	'id'          => 'penci_popup_show_after_time',
	'default'     => '7',
	'sanitize'    => 'penci_sanitize_text_field',
	'label'       => __( 'Show After Fixed Time', 'soledad' ),
	'description' => __( 'Set the number of days expire the popup cookie. Apply for "After Fixed Time" Setting.', 'soledad' ),
	'type'        => 'soledad-fw-text'
);
$options[] = array(
	'id'          => 'penci_popup_version',
	'default'     => '1',
	'sanitize'    => 'penci_sanitize_text_field',
	'label'       => __( 'Popup Version', 'soledad' ),
	'description' => __( 'If you apply any changes to your popup settings or content you might want to force the popup to all visitors who already closed it again. In this case, you just need to increase the banner version.', 'soledad' ),
	'type'        => 'soledad-fw-text'
);
$options[] = array(
	'id'          => 'penci_popup_show_after_pages',
	'default'     => '0',
	'sanitize'    => 'penci_sanitize_text_field',
	'label'       => __( 'Show After Number of Pages Visited', 'soledad' ),
	'description' => __( 'You can choose how many pages the user should visit before the popup will be shown.', 'soledad' ),
	'type'        => 'soledad-fw-text'
);
$options[] = array(
	'id'       => 'penci_popup_animation',
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
