<?php
$options   = [];
$options[] = array(
	'id'       => 'penci_toc_enabled_post_types',
	'default'  => '',
	'sanitize' => 'penci_sanitize_multiple_checkbox',
	'label'    => __( 'Enable Support in Post Types', 'soledad' ),
	'type'     => 'soledad-fw-select',
	'multiple' => 999,
	'choices'  => call_user_func( function () {
		$exclude    = array(
			'attachment',
			'revision',
			'nav_menu_item',
			'safecss',
			'penci-block',
			'penci_builder',
			'custom-post-template',
			'archive-template',
		);
		$registered = get_post_types( [ 'show_in_nav_menus' => true ], 'objects' );
		$types      = array();


		foreach ( $registered as $post ) {

			if ( in_array( $post->name, $exclude ) ) {

				continue;
			}

			$types[ $post->name ] = $post->label;
		}

		return $types;
	} )
);
$options[] = array(
	'id'       => 'penci_toc_position',
	'default'  => 'top',
	'sanitize' => 'penci_sanitize_choices_field',
	'label'    => __( 'Insert Table of Contents To', 'soledad' ),
	'type'     => 'soledad-fw-select',
	'choices'  => [
		'top'    => __( 'Before Post Content (Default)', 'soledad' ),
		'bottom' => __( 'After Post Content', 'soledad' ),
		'before' => __( 'Before First Heading', 'soledad' ),
		'after'  => __( 'After First Heading', 'soledad' ),
	]
);
$options[] = array(
	'default'  => 's1',
	'sanitize' => 'penci_sanitize_choices_field',
	'label'    => __( 'Table of Contents Style', 'soledad' ),
	'id'       => 'penci_toc_style',
	'type'     => 'soledad-fw-select',
	'choices'  => array(
		's1' => __( 'Default', 'soledad' ),
		's2' => __( 'Shadow', 'soledad' ),
		's3' => __( 'Background', 'soledad' ),
	)
);
$options[] = array(
	'default'  => 'left',
	'sanitize' => 'penci_sanitize_choices_field',
	'label'    => __( 'Sticky Position', 'soledad' ),
	'id'       => 'penci_toc_sticky',
	'type'     => 'soledad-fw-select',
	'choices'  => array(
		'left'                => __( 'Top Left', 'soledad' ),
		'right'               => __( 'Top Right', 'soledad' ),
		'bottom sticky-left'  => __( 'Bottom Left', 'soledad' ),
		'bottom sticky-right' => __( 'Bottom Right', 'soledad' ),
		'disable'             => __( 'Disable', 'soledad' ),
	)
);
$options[] = array(
	'sanitize'    => 'penci_sanitize_checkbox_field',
	'label'       => __( 'Always Sticky?', 'soledad' ),
	'description' => __( 'This option will hide the TOC in the main post content and display it in the sticky position you selected above.', 'soledad' ),
	'id'          => 'penci_toc_asticky',
	'type'        => 'soledad-fw-toggle',
);
$options[] = array(
	'default'     => '',
	'sanitize'    => 'absint',
	'type'        => 'soledad-fw-size',
	'label'       => __( 'Width', 'soledad' ),
	'description' => __( 'Enter the custom table of contents width.', 'soledad' ),
	'id'          => 'penci_toc_styles_width',
	'ids'         => array(
		'desktop' => 'penci_toc_styles_width',
	),
	'choices'     => array(
		'desktop' => array(
			'min'  => 1,
			'max'  => 9999,
			'step' => 1,
			'edit' => true,
			'unit' => 'px',
		),
	),
);
$options[] = array(
	'default'     => '',
	'sanitize'    => 'absint',
	'type'        => 'soledad-fw-size',
	'label'       => __( 'Sticky Width', 'soledad' ),
	'description' => __( 'Enter the custom table of contents width when sticky.', 'soledad' ),
	'id'          => 'penci_toc_styles_swidth',
	'ids'         => array(
		'desktop' => 'penci_toc_styles_swidth',
	),
	'choices'     => array(
		'desktop' => array(
			'min'  => 1,
			'max'  => 9999,
			'step' => 1,
			'edit' => true,
			'unit' => 'px',
		),
	),
);
$options[] = array(
	'default'  => 'none',
	'sanitize' => 'penci_sanitize_choices_field',
	'label'    => __( 'Table of Contents Float', 'soledad' ),
	'id'       => 'penci_toc_wrapping',
	'type'     => 'soledad-fw-select',
	'choices'  => array(
		'none'  => __( 'None', 'soledad' ),
		'left'  => __( 'Float Left', 'soledad' ),
		'right' => __( 'Float Right', 'soledad' ),
	)
);
$options[] = array(
	'id'          => 'penci_toc_start',
	'default'     => '3',
	'sanitize'    => 'penci_sanitize_number_field',
	'label'       => __( 'Show Table of Contents for Posts has How Many Minimum Heading Tags', 'soledad' ),
	'description' => __( 'By default, the table of contents does not show if the posts has lower than 3 heading tags', 'soledad' ),
	'type'        => 'soledad-fw-number',
);
$options[] = array(
	'id'       => 'penci_toc_heading_text',
	'default'  => 'Table of Contents',
	'sanitize' => 'penci_sanitize_textarea_field',
	'label'    => __( 'Header Label', 'soledad' ),
	'type'     => 'soledad-fw-text',
);
$options[] = array(
	'id'          => 'penci_toc_visibility',
	'default'     => '',
	'sanitize'    => 'penci_sanitize_checkbox_field',
	'label'       => __( 'Disable Toggle View', 'soledad' ),
	'description' => __( 'Disallow the user to toggle the visibility of the table of contents.', 'soledad' ),
	'type'        => 'soledad-fw-toggle',
);
$options[] = array(
	'id'          => 'penci_toc_visibility_hide_by_default',
	'default'     => '',
	'sanitize'    => 'penci_sanitize_checkbox_field',
	'label'       => __( 'Always hide the table of contents', 'soledad' ),
	'description' => __( 'Only display the table heading. Initially hide the table of contents.', 'soledad' ),
	'type'        => 'soledad-fw-toggle',
);
$options[] = array(
	'id'       => 'penci_toc_show_hierarchy',
	'default'  => true,
	'sanitize' => 'penci_sanitize_checkbox_field',
	'label'    => __( 'Show as Hierarchy', 'soledad' ),
	'type'     => 'soledad-fw-toggle',
);
$options[] = array(
	'id'       => 'penci_toc_counter',
	'default'  => 'decimal',
	'sanitize' => 'penci_sanitize_checkbox_field',
	'label'    => __( 'Counter', 'soledad' ),
	'type'     => 'soledad-fw-select',
	'choices'  => [
		'decimal' => __( 'Decimal (default)', 'soledad' ),
		'numeric' => __( 'Numeric', 'soledad' ),
		'roman'   => __( 'Roman', 'soledad' ),
		'none'    => __( 'None', 'soledad' ),
	]
);
$options[] = array(
	'id'       => 'penci_toc_smooth_scroll',
	'default'  => true,
	'sanitize' => 'penci_sanitize_checkbox_field',
	'label'    => __( 'Smooth Scroll', 'soledad' ),
	'type'     => 'soledad-fw-toggle',
);
$options[] = array(
	'label'    => esc_html__( 'Advanced Settings', 'soledad' ),
	'id'       => 'penci_toc_advanced_head_1',
	'type'     => 'soledad-fw-header',
	'sanitize' => 'sanitize_text_field'
);
$options[] = array(
	'id'       => 'penci_toc_levels',
	'default'  => 3,
	'sanitize' => 'penci_sanitize_checkbox_field',
	'label'    => __( 'Maximum Level of Table of Contents', 'soledad' ),
	'type'     => 'soledad-fw-select',
	'choices'  => [
		'1' => __( '1', 'soledad' ),
		'2' => __( '2', 'soledad' ),
		'3' => __( '3', 'soledad' ),
		'4' => __( '4', 'soledad' ),
		'5' => __( '5', 'soledad' ),
		'6' => __( '6', 'soledad' ),
	],
);
$options[] = array(
	'id'          => 'penci_toc_prefix',
	'default'     => 'penci',
	'sanitize'    => 'penci_sanitize_text_field',
	'label'       => __( 'Link Anchor Prefix', 'soledad' ),
	'description' => __( 'Please enter a value for this option field. All heading IDs will start with this text. If you leave this field blank, the TOC may not work correctly.', 'soledad' ),
	'type'        => 'soledad-fw-text',
);
$options[] = array(
	'id'       => 'penci_toc_heading_levels',
	'default'  => [ '1', '2', '3', '4', '5', '6' ],
	'sanitize' => 'penci_sanitize_checkbox_field',
	'label'    => __( 'Headings', 'soledad' ),
	'type'     => 'soledad-fw-select',
	'multiple' => 999,
	'choices'  => [
		'1' => __( 'Heading 1 (h1)', 'soledad' ),
		'2' => __( 'Heading 2 (h2)', 'soledad' ),
		'3' => __( 'Heading 3 (h3)', 'soledad' ),
		'4' => __( 'Heading 4 (h4)', 'soledad' ),
		'5' => __( 'Heading 5 (h5)', 'soledad' ),
		'6' => __( 'Heading 6 (h6)', 'soledad' ),
	],
);

$options[] = array(
	'id'          => 'penci_toc_exclude',
	'sanitize'    => 'penci_sanitize_text_field',
	'label'       => __( 'Exclude Headings', 'soledad' ),
	'description' => __( 'Specify headings to be excluded from appearing in the table of contents. Separate multiple headings with a pipe |. Use an asterisk * as a wildcard to match other text.', 'soledad' ),
	'type'        => 'soledad-fw-text',
);
$options[] = array(
	'id'          => 'penci_toc_smooth_scroll_offset',
	'sanitize'    => 'absint',
	'default'     => '120',
	'label'       => __( 'Smooth Scroll Offset', 'soledad' ),
	'description' => __( 'If you have a consistent menu across the top of your site, you can adjust the top offset to stop the headings from appearing underneath the top menu. A setting of 30 accommodates the WordPress admin bar. This setting only has an effect after you have enabled Smooth Scroll option.', 'soledad' ),
	'type'        => 'soledad-fw-number',
);
$options[] = array(
	'id'          => 'penci_toc_mobile_smooth_scroll_offset',
	'sanitize'    => 'absint',
	'default'     => '90',
	'label'       => __( 'Mobile Smooth Scroll Offset', 'soledad' ),
	'description' => __( 'This provides the same function as the Smooth Scroll Offset option above but applied when the user is visiting your site on a mobile device.', 'soledad' ),
	'type'        => 'soledad-fw-number',
);
$options[] = array(
	'id'       => 'penci_toc_nofollow_link',
	'sanitize' => 'absint',
	'label'    => __( 'Add "no-follow" to table of contents link.', 'soledad' ),
	'type'     => 'soledad-fw-toggle',
);

return $options;
