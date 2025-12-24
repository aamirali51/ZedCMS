<?php
$options      = [];
$single_style = array();
for ( $i = 1; $i <= 22; $i ++ ) {
	$single_style[ 'style-' . $i ] = esc_html__( 'Style ' . $i, 'soledad' );
}

$options[]         = array(
	'default'  => 'style-1',
	'sanitize' => 'penci_sanitize_choices_field',
	'label'    => __( 'Single Posts Template', 'soledad' ),
	'id'       => 'penci_single_style',
	'type'     => 'soledad-fw-select',
	'choices'  => $single_style,
);
$single_layout     = [];
$single_layout[''] = esc_attr__( 'None' );
$single_layouts    = get_posts( [
	'post_type'      => 'custom-post-template',
	'posts_per_page' => - 1,
	'meta_query'     => array(
		'relation' => 'OR',
		array(
			'key'     => 'penci_desktop_page_id',
			'compare' => 'NOT EXISTS',
		),
		array(
			'key'     => 'penci_desktop_page_id',
			'value'   => '',
			'compare' => '=',
		),
	),
] );
foreach ( $single_layouts as $slayout ) {
	$single_layout[ $slayout->post_name ] = $slayout->post_title;
}
$options[]                     = array(
	'default'     => '',
	'description' => __( 'Will override the pre-build single posts template above. You can add new and edit a single post template on <a class="wp-customizer-link" target="_blank" href="' . esc_url( admin_url( '/edit.php?post_type=custom-post-template' ) ) . '">this page</a>', 'soledad' ),
	'sanitize'    => 'penci_sanitize_choices_field',
	'label'       => __( 'Custom Post Builder Template', 'soledad' ),
	'id'          => 'penci_single_custom_template',
	'type'        => 'soledad-fw-select',
	'choices'     => $single_layout
);
$penci_move_title_bellow_style = [
	'style-1',
	'style-2',
	'style-3',
	'style-4',
	'style-5',
	'style-6',
	'style-7',
	'style-8',
	'style-9',
	'style-14',
	'style-16',
	'style-17',
	'style-18',
	'style-19',
	'style-22',
];
$options[]                     = array(
	'default'         => false,
	'sanitize'        => 'penci_sanitize_checkbox_field',
	'label'           => __( 'Move Categories, Post Title, Post Meta To Bellow Featured Image', 'soledad' ),
	'id'              => 'penci_move_title_bellow',
	'type'            => 'soledad-fw-toggle',
	'active_callback' => [
		[
			'setting'  => 'penci_single_style',
			'operator' => 'in',
			'value'    => $penci_move_title_bellow_style,
		]
	],
);
$options[]                     = array(
	'default'     => false,
	'sanitize'    => 'penci_sanitize_checkbox_field',
	'label'       => __( 'Enable Focus Mode', 'soledad' ),
	'description' => __( 'This option will display a button in the footer. Users can click it to enable Focus Mode, which highlights only the main content of the page. It hides unnecessary elements such as the header, footer, sidebar, comments, related posts, and more.', 'soledad' ),
	'id'          => 'penci_single_focus_mode',
	'type'        => 'soledad-fw-toggle',
);
$options[]                     = array(
	'default'  => 'right',
	'sanitize' => 'penci_sanitize_choices_field',
	'label'    => __( 'Focus Mode Button Position', 'soledad' ),
	'id'       => 'penci_single_focus_mode_pos',
	'type'     => 'soledad-fw-select',
	'choices'  => array(
		'right'       => __( 'Bottom Right', 'soledad' ),
		'left'        => __( 'Bottom Left', 'soledad' ),
	),
	'active_callback' => [
		[
			'setting'  => 'penci_single_focus_mode',
			'operator' => '==',
			'value'    => true,
		]
	],
);
$options[]                     = array(
	'default'  => 'right',
	'sanitize' => 'penci_sanitize_choices_field',
	'label'    => __( 'Single Posts Sidebar Layout', 'soledad' ),
	'id'       => 'penci_single_layout',
	'type'     => 'soledad-fw-select',
	'choices'  => array(
		'right'       => __( 'Right Sidebar', 'soledad' ),
		'left'        => __( 'Left Sidebar', 'soledad' ),
		'two'         => __( 'Two Sidebars', 'soledad' ),
		'no'          => __( 'No Sidebar', 'soledad' ),
		'small_width' => __( 'No Sidebar with Container Width Smaller', 'soledad' ),
	)
);
$options[]                     = array(
	'default'  => '',
	'sanitize' => 'penci_sanitize_choices_field',
	'label'    => __( 'Single Posts Sidebar Layout on Mobile', 'soledad' ),
	'id'       => 'penci_single_layout_mobile',
	'type'     => 'soledad-fw-select',
	'choices'  => array(
		''            => __( 'Follow Desktop', 'soledad' ),
		'right'       => __( 'Right Sidebar', 'soledad' ),
		'left'        => __( 'Left Sidebar', 'soledad' ),
		'two'         => __( 'Two Sidebars', 'soledad' ),
		'no'          => __( 'No Sidebar', 'soledad' ),
		'small_width' => __( 'No Sidebar with Container Width Smaller', 'soledad' ),
	)
);
$options[]                     = array(
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field',
	'label'    => __( 'Delayed Load Sidebar Content on Mobile', 'soledad' ),
	'id'       => 'penci_single_delayed_sidebar',
	'type'     => 'soledad-fw-toggle',
);
$options[]                     = array(
	'default'  => '1',
	'sanitize' => 'penci_sanitize_choices_field',
	'label'    => __( 'Default Smart Lists Style', 'soledad' ),
	'id'       => 'penci_single_smartlists_style',
	'type'     => 'soledad-fw-select',
	'choices'  => array(
		'1' => 'Style 1',
		'2' => 'Style 2',
		'3' => 'Style 3',
		'4' => 'Style 4',
		'5' => 'Style 5',
		'6' => 'Style 6',
	)
);
$options[]                     = array(
	'default'         => '780',
	'sanitize'        => 'absint',
	'label'           => __( 'Custom Width for "No Sidebar with Container Width Smaller" Layout You Selected Above', 'soledad' ),
	'id'              => 'penci_single_smaller_width',
	'type'            => 'soledad-fw-size',
	'ids'             => array(
		'desktop' => 'penci_single_smaller_width',
	),
	'choices'         => array(
		'desktop' => array(
			'min'     => 1,
			'max'     => 100,
			'step'    => 1,
			'edit'    => true,
			'unit'    => 'px',
			'default' => '780',
		),
	),
	'active_callback' => [
		[
			'setting'  => 'penci_single_layout',
			'operator' => '==',
			'value'    => 'small_width',
		]
	],
);
$options[]                     = array(
	'default'     => '',
	'sanitize'    => 'absint',
	'label'       => __( 'Custom Container Width on Single Posts Page', 'soledad' ),
	'description' => __( 'Minimum is 600px', 'soledad' ),
	'id'          => 'penci_single_container_w',
	'type'        => 'soledad-fw-size',
	'ids'         => array(
		'desktop' => 'penci_single_container_w',
	),
	'choices'     => array(
		'desktop' => array(
			'min'  => 1,
			'max'  => 2000,
			'step' => 1,
			'edit' => true,
			'unit' => 'px',
		),
	),
);
$options[]                     = array(
	'default'     => '',
	'sanitize'    => 'absint',
	'label'       => __( 'Custom Container Width for Two Sidebars on Single Posts Page', 'soledad' ),
	'description' => __( 'Minimum is 800px', 'soledad' ),
	'id'          => 'penci_single_container2_w',
	'type'        => 'soledad-fw-size',
	'ids'         => array(
		'desktop' => 'penci_single_container2_w',
	),
	'choices'     => array(
		'desktop' => array(
			'min'  => 1,
			'max'  => 2000,
			'step' => 1,
			'edit' => true,
			'unit' => 'px',
		),
	),
);
$options[]                     = array(
	'default'     => '',
	'sanitize'    => 'penci_sanitize_choices_field',
	'label'       => __( 'Custom Image Size for Featured Image', 'soledad' ),
	'description' => __( 'This option doesn\'t apply for two sidebars layout.', 'soledad' ),
	'id'          => 'penci_single_custom_thumbnail_size',
	'type'        => 'soledad-fw-ajax-select',
	'choices'     => call_user_func( function () {
		global $_wp_additional_image_sizes;

		$image_sizes = [];

		$default_image_sizes = get_intermediate_image_sizes();

		foreach ( $default_image_sizes as $size ) {
			$image_sizes[ $size ]['width']  = intval( get_option( "{$size}_size_w" ) );
			$image_sizes[ $size ]['height'] = intval( get_option( "{$size}_size_h" ) );
			$image_sizes[ $size ]['crop']   = get_option( "{$size}_crop" ) ? get_option( "{$size}_crop" ) : false;
		}

		if ( isset( $_wp_additional_image_sizes ) && count( $_wp_additional_image_sizes ) ) {
			$image_sizes = array_merge( $image_sizes, $_wp_additional_image_sizes );
		}

		$image_sizes_data = array( '' => 'Default' );
		if ( ! empty( $image_sizes ) ) {
			foreach ( $image_sizes as $key => $val ) {
				$new_val = $key;
				if ( isset( $val['width'] ) && isset( $val['height'] ) ) {
					$heightname = $val['height'];
					if ( '0' == $val['height'] || '99999' == $val['height'] ) {
						$heightname = 'auto';
					}
					$new_val = $key . ' - ' . $val['width'] . ' x ' . $heightname;
				}
				$image_sizes_data[ $key ] = $new_val;
			}
		}
		$image_sizes_data['full'] = 'Full Size';

		return $image_sizes_data;
	} ),
);
$options[]                     = array(
	'default'     => false,
	'sanitize'    => 'penci_sanitize_checkbox_field',
	'label'       => __( 'Enable Parallax on Featured Image', 'soledad' ),
	'id'          => 'penci_enable_jarallax_single',
	'type'        => 'soledad-fw-toggle',
	'description' => __( 'This feature does not apply for Single Style 1 & 2', 'soledad' ),
);
$options[]                     = array(
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field',
	'label'    => __( 'Enable Font Sizes Changer', 'soledad' ),
	'id'       => 'penci_single_font_changer',
	'type'     => 'soledad-fw-toggle',
);
$options[]                     = array(
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field',
	'label'    => __( 'Disable Parallax on Featured Image on Mobile', 'soledad' ),
	'id'       => 'penci_dis_jarallax_single_mb',
	'type'     => 'soledad-fw-toggle',
);
$options[]                     = array(
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field',
	'label'    => __( 'Disable Auto Play for Single Slider Gallery & Posts Format Gallery', 'soledad' ),
	'id'       => 'penci_disable_autoplay_single_slider',
	'type'     => 'soledad-fw-toggle',
);
$options[]                     = array(
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field',
	'label'    => __( 'Hide Images Title on Galleries from The Theme', 'soledad' ),
	'id'       => 'penci_disable_image_titles_galleries',
	'type'     => 'soledad-fw-toggle',
);
$options[]                     = array(
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field',
	'label'    => __( 'Disable Lightbox on Single Posts', 'soledad' ),
	'id'       => 'penci_disable_lightbox_single',
	'type'     => 'soledad-fw-toggle',
);
$options[]                     = array(
	'default'     => false,
	'sanitize'    => 'penci_sanitize_checkbox_field',
	'label'       => __( 'Hide Featured Image on Top', 'soledad' ),
	'id'          => 'penci_post_thumb',
	'description' => __( 'Hide Featured images auto appears on single posts page - This option not apply for Video format, Gallery format', 'soledad' ),
	'type'        => 'soledad-fw-toggle',
);
$options[]                     = array(
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field',
	'label'    => __( 'Hide Category', 'soledad' ),
	'id'       => 'penci_post_cat',
	'type'     => 'soledad-fw-toggle',
);
$options[]                     = array(
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field',
	'label'    => __( 'Enable Uppercase on Post Categories', 'soledad' ),
	'id'       => 'penci_on_uppercase_post_cat',
	'type'     => 'soledad-fw-toggle',
);
$options[]                     = array(
	'default'     => '',
	'sanitize'    => 'sanitize_text_field',
	'label'       => __( 'Custom Border Radius for Featured Image', 'soledad' ),
	'id'          => 'penci_post_featured_image_radius',
	'type'        => 'soledad-fw-text',
	'description' => __( 'You can use pixel or percent. E.g:  <strong>10px</strong>  or  <strong>10%</strong>. If you want to disable border radius - fill 0', 'soledad' ),
);
$options[]                     = array(
	'default'     => '',
	'sanitize'    => 'sanitize_text_field',
	'label'       => __( 'Custom Aspect Ratio for Featured Image', 'soledad' ),
	'id'          => 'penci_post_featured_image_ratio',
	'type'        => 'soledad-fw-text',
	'description' => __( 'The aspect ratio of an element describes the proportional relationship between its width and its height. E.g: <strong>3:2</strong>. Default is 3:2 . This option not apply when enable parallax images. This feature does not apply for Single Style 1 & 2', 'soledad' ),
);
$options[]                     = array(
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field',
	'label'    => __( 'Align Left Post Categories, Post Title, Post Meta', 'soledad' ),
	'id'       => 'penci_align_left_post_title',
	'type'     => 'soledad-fw-toggle',
);
$options[]                     = array(
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field',
	'label'    => __( 'Remove Letter Spacing on Post Title', 'soledad' ),
	'id'       => 'penci_off_letter_space_post_title',
	'type'     => 'soledad-fw-toggle',
);
$options[]                     = array(
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field',
	'label'    => __( 'Turn Off Uppercase on Post Title', 'soledad' ),
	'id'       => 'penci_off_uppercase_post_title',
	'type'     => 'soledad-fw-toggle',
);
$options[]                     = array(
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field',
	'label'    => __( 'Hide Post Author', 'soledad' ),
	'id'       => 'penci_single_meta_author',
	'type'     => 'soledad-fw-toggle',
);
$options[]                     = array(
	'default'     => false,
	'sanitize'    => 'penci_sanitize_checkbox_field',
	'label'       => __( 'Show Updated Author', 'soledad' ),
	'description' => __( 'If a post is created by one author and then edited and updated by another author, this option will allow you to display both authors in the post\'s meta data.', 'soledad' ),
	'id'          => 'penci_single_meta_update_author',
	'type'        => 'soledad-fw-toggle',
);
$options[]                     = array(
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field',
	'label'    => __( 'Hide Post Date', 'soledad' ),
	'id'       => 'penci_single_meta_date',
	'type'     => 'soledad-fw-toggle',
);
$options[]                     = array(
	'default'     => false,
	'sanitize'    => 'penci_sanitize_checkbox_field',
	'label'       => __( 'Display Published Date & Modified Date', 'soledad' ),
	'description' => esc_html__( 'Note that: If Published Date and Modified Date is the same - will be display Published date only. And if you want to display Modified date only - check option for it via Customize > General > General Settings > Display Modified Date Replace with Published Date', 'soledad' ),
	'id'          => 'penci_single_publishmodified',
	'type'        => 'soledad-fw-toggle',
);
$options[]                     = array(
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field',
	'label'    => __( 'Hide Comment Count', 'soledad' ),
	'id'       => 'penci_single_meta_comment',
	'type'     => 'soledad-fw-toggle',
);
$options[]                     = array(
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field',
	'label'    => __( 'Show Views Count', 'soledad' ),
	'id'       => 'penci_single_show_cview',
	'type'     => 'soledad-fw-toggle',
);
$options[]                     = array(
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field',
	'label'    => __( 'Hide Reading Time', 'soledad' ),
	'id'       => 'penci_single_hreadtime',
	'type'     => 'soledad-fw-toggle',
);
$options[]                     = array(
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field',
	'label'    => __( 'Show Dropdown Share in Post Meta', 'soledad' ),
	'id'       => 'penci_single_dropdown_share',
	'type'     => 'soledad-fw-toggle',
);
$options[]                     = array(
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field',
	'label'    => __( 'Enable Font Size Adjustment', 'soledad' ),
	'id'       => 'penci_single_fontsize_adj',
	'type'     => 'soledad-fw-toggle',
);
$options[]                     = array(
	'default'     => false,
	'sanitize'    => 'penci_sanitize_checkbox_field',
	'label'       => __( 'Enable Ajax Post View Count', 'soledad' ),
	'id'          => 'penci_enable_ajax_view',
	'description' => __( 'Use to count posts viewed when you using cache plugin.', 'soledad' ),
	'type'        => 'soledad-fw-toggle',
);
$options[]                     = array(
	'default'     => false,
	'sanitize'    => 'penci_sanitize_checkbox_field',
	'label'       => __( 'Enable Caption on Featured Image', 'soledad' ),
	'id'          => 'penci_post_thumb_caption',
	'description' => __( 'If your featured image has a caption, it will display on featured image', 'soledad' ),
	'type'        => 'soledad-fw-toggle',
);
$options[]                     = array(
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field',
	'label'    => __( 'Enable Caption on Slider of Gallery Post Format', 'soledad' ),
	'id'       => 'penci_post_gallery_caption',
	'type'     => 'soledad-fw-toggle',
);
$options[]                     = array(
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field',
	'label'    => __( 'Move Caption of Images to Below The Images', 'soledad' ),
	'id'       => 'penci_post_caption_below',
	'type'     => 'soledad-fw-toggle',
);
$options[]                     = array(
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field',
	'label'    => __( 'Disable Italic on Caption of Images', 'soledad' ),
	'id'       => 'penci_post_caption_disable_italic',
	'type'     => 'soledad-fw-toggle',
);
$options[]                     = array(
	'default'  => 'style-1',
	'sanitize' => 'penci_sanitize_choices_field',
	'label'    => __( 'Blockquote Style:', 'soledad' ),
	'id'       => 'penci_blockquote_style',
	'type'     => 'soledad-fw-select',
	'choices'  => array(
		'style-1' => __( 'Style 1', 'soledad' ),
		'style-2' => __( 'Style 2', 'soledad' ),
		'style-3' => __( 'Style 3', 'soledad' ),
		'style-4' => __( 'Style 4', 'soledad' ),
		'style-5' => __( 'Style 5', 'soledad' ),
		'style-6' => __( 'Style 6', 'soledad' ),
		'style-7' => __( 'Style 7', 'soledad' ),
		'style-8' => __( 'Style 8', 'soledad' ),
	)
);
$options[]                     = array(
	'default'  => '',
	'sanitize' => 'penci_sanitize_choices_field',
	'label'    => __( 'Custom Style for Heading 1 Inside Post Content', 'soledad' ),
	'id'       => 'penci_heading_h1_style',
	'type'     => 'soledad-fw-select',
	'choices'  => array(
		''        => __( 'Default (No Style)', 'soledad' ),
		'style-1' => __( 'Style 1', 'soledad' ),
		'style-2' => __( 'Style 2', 'soledad' ),
		'style-3' => __( 'Style 3', 'soledad' ),
		'style-4' => __( 'Style 4', 'soledad' ),
		'style-5' => __( 'Style 5', 'soledad' ),
		'style-6' => __( 'Style 6', 'soledad' ),
	)
);
$options[]                     = array(
	'default'  => '',
	'sanitize' => 'penci_sanitize_choices_field',
	'label'    => __( 'Custom Style for Heading 2 Inside Post Content', 'soledad' ),
	'id'       => 'penci_heading_h2_style',
	'type'     => 'soledad-fw-select',
	'choices'  => array(
		''        => __( 'Default (No Style)', 'soledad' ),
		'style-1' => __( 'Style 1', 'soledad' ),
		'style-2' => __( 'Style 2', 'soledad' ),
		'style-3' => __( 'Style 3', 'soledad' ),
		'style-4' => __( 'Style 4', 'soledad' ),
		'style-5' => __( 'Style 5', 'soledad' ),
		'style-6' => __( 'Style 6', 'soledad' ),
	)
);
$options[]                     = array(
	'default'  => '',
	'sanitize' => 'penci_sanitize_choices_field',
	'label'    => __( 'Custom Style for Heading 3 Inside Post Content', 'soledad' ),
	'id'       => 'penci_heading_h3_style',
	'type'     => 'soledad-fw-select',
	'choices'  => array(
		''        => __( 'Default (No Style)', 'soledad' ),
		'style-1' => __( 'Style 1', 'soledad' ),
		'style-2' => __( 'Style 2', 'soledad' ),
		'style-3' => __( 'Style 3', 'soledad' ),
		'style-4' => __( 'Style 4', 'soledad' ),
		'style-5' => __( 'Style 5', 'soledad' ),
		'style-6' => __( 'Style 6', 'soledad' ),
	)
);
$options[]                     = array(
	'default'  => '',
	'sanitize' => 'penci_sanitize_choices_field',
	'label'    => __( 'Custom Style for Heading 4 Inside Post Content', 'soledad' ),
	'id'       => 'penci_heading_h4_style',
	'type'     => 'soledad-fw-select',
	'choices'  => array(
		''        => __( 'Default (No Style)', 'soledad' ),
		'style-1' => __( 'Style 1', 'soledad' ),
		'style-2' => __( 'Style 2', 'soledad' ),
		'style-3' => __( 'Style 3', 'soledad' ),
		'style-4' => __( 'Style 4', 'soledad' ),
		'style-5' => __( 'Style 5', 'soledad' ),
		'style-6' => __( 'Style 6', 'soledad' ),
	)
);
$options[]                     = array(
	'default'  => '',
	'sanitize' => 'penci_sanitize_choices_field',
	'label'    => __( 'Custom Style for Heading 5 Inside Post Content', 'soledad' ),
	'id'       => 'penci_heading_h5_style',
	'type'     => 'soledad-fw-select',
	'choices'  => array(
		''        => __( 'Default (No Style)', 'soledad' ),
		'style-1' => __( 'Style 1', 'soledad' ),
		'style-2' => __( 'Style 2', 'soledad' ),
		'style-3' => __( 'Style 3', 'soledad' ),
		'style-4' => __( 'Style 4', 'soledad' ),
		'style-5' => __( 'Style 5', 'soledad' ),
		'style-6' => __( 'Style 6', 'soledad' ),
	)
);
$options[]                     = array(
	'default'  => '',
	'sanitize' => 'penci_sanitize_choices_field',
	'label'    => __( 'Tags Style', 'soledad' ),
	'id'       => 'penci_tags_style',
	'type'     => 'soledad-fw-select',
	'choices'  => array(
		''        => __( 'Default (No Style)', 'soledad' ),
		'style-1' => __( 'Style 1', 'soledad' ),
		'style-2' => __( 'Style 2', 'soledad' ),
		'style-3' => __( 'Style 3', 'soledad' ),
		'style-4' => __( 'Style 4', 'soledad' ),
		'style-5' => __( 'Style 5', 'soledad' ),
	)
);
$options[]                     = array(
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field',
	'label'    => __( 'Hide Tags', 'soledad' ),
	'id'       => 'penci_post_tags',
	'type'     => 'soledad-fw-toggle',
);
$options[]                     = array(
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field',
	'label'    => __( 'Hide Like Count & Social Share', 'soledad' ),
	'id'       => 'penci_post_share',
	'type'     => 'soledad-fw-toggle',
);
$options[]                     = array(
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field',
	'label'    => __( 'Turn on the Sticky Share', 'soledad' ),
	'id'       => 'penci_post_stickyshare',
	'type'     => 'soledad-fw-toggle',
);
$options[]                     = array(
	'default'         => 'style-1',
	'sanitize'        => 'penci_sanitize_choices_field',
	'label'           => __( 'Sticky Share Style:', 'soledad' ),
	'id'              => 'penci_post_stickyshare_style',
	'type'            => 'soledad-fw-select',
	'choices'         => array(
		'style-1' => __( 'Style 1', 'soledad' ),
		'style-2' => __( 'Style 2', 'soledad' ),
		'style-3' => __( 'Style 3', 'soledad' ),
	),
	'active_callback' => [
		[
			'setting'  => 'penci_post_stickyshare',
			'operator' => '==',
			'value'    => true,
		]
	],
);
$options[]                     = array(
	'default'         => 'left',
	'sanitize'        => 'penci_sanitize_choices_field',
	'label'           => __( 'Sticky Share Position:', 'soledad' ),
	'id'              => 'penci_post_stickyshare_pos',
	'type'            => 'soledad-fw-select',
	'choices'         => array(
		'left'  => 'Left',
		'right' => 'Right',
	),
	'active_callback' => [
		[
			'setting'  => 'penci_post_stickyshare',
			'operator' => '==',
			'value'    => true,
		]
	],
);
$options[]                     = array(
	'default'         => 'below-content',
	'sanitize'        => 'penci_sanitize_choices_field',
	'label'           => __( 'Share Box Position', 'soledad' ),
	'id'              => 'penci_single_poslcscount',
	'description'     => '',
	'type'            => 'soledad-fw-select',
	'choices'         => array(
		'btitle'             => __( 'Bellow Post Meta', 'soledad' ),
		'above-content'      => __( 'Above Content', 'soledad' ),
		'below-content'      => __( 'Below Content', 'soledad' ),
		'abovebelow-content' => __( 'Above & Below Content', 'soledad' ),
		'btitle-bcontent'    => __( 'Bellow Post Meta & Below Content', 'soledad' ),
		'sticky-left'        => __( 'Sticky of the Post Content', 'soledad' ),
	),
	'active_callback' => [
		[
			'setting'  => 'penci_post_share',
			'operator' => '!=',
			'value'    => true,
		]
	],
);
$share_style                   = [];
for ( $i = 1; $i <= 23; $i ++ ) {
	$v                      = $i < 4 ? 's' : 'n';
	$n                      = $i < 4 ? $i : $i - 3;
	$share_style[ $v . $n ] = 'Style ' . $i;
}

$options[] = array(
	'default'         => 's1',
	'sanitize'        => 'penci_sanitize_choices_field',
	'label'           => __( 'Share Box Style', 'soledad' ),
	'id'              => 'penci_single_style_cscount',
	'description'     => '',
	'type'            => 'soledad-fw-select',
	'choices'         => $share_style,
	'active_callback' => [
		[
			'setting'  => 'penci_post_share',
			'operator' => '!=',
			'value'    => true,
		],
		[
			'setting'  => 'penci_single_poslcscount',
			'operator' => '!=',
			'value'    => 'sticky-left',
		],
	],
);
$options[] = array(
	'default'         => 's1',
	'sanitize'        => 'penci_sanitize_choices_field',
	'label'           => __( 'Sticky Share Box Style', 'soledad' ),
	'id'              => 'penci_single_style_sticky_cscount',
	'description'     => '',
	'type'            => 'soledad-fw-select',
	'choices'         => [
		's1' 	=> 'Style 1',
		'n1' 	=> 'Style 2',
		'n3' 	=> 'Style 3',
		'n16' 	=> 'Style 4',
	],
	'active_callback' => [
		[
			'setting'  => 'penci_single_poslcscount',
			'operator' => '==',
			'value'    => 'sticky-left',
		]
	],
);
$options[] = array(
	'default'         => '',
	'sanitize'        => 'penci_sanitize_choices_field',
	'label'           => __( 'Share Box Bottom Style', 'soledad' ),
	'description'     => __( 'Apply for Above & Below Content and Bellow Post Meta & Below Content Share Position', 'soledad' ),
	'id'              => 'penci_single_style_bottom_cscount',
	'description'     => '',
	'type'            => 'soledad-fw-select',
	'choices'         => array_merge( [ '' => __( 'Follow Default Style', 'soledad' ) ], $share_style ),
	'active_callback' => [
		[
			'setting'  => 'penci_post_share',
			'operator' => '!=',
			'value'    => true,
		],
		[
			'setting'  => 'penci_single_poslcscount',
			'operator' => 'in',
			'value'    => ['abovebelow-content', 'btitle-bcontent'],
		]
	],
);
$options[] = array(
	'default'         => true,
	'sanitize'        => 'penci_sanitize_checkbox_field',
	'label'           => __( 'Disable Social Share Plus Button', 'soledad' ),
	'id'              => 'penci_post_share_disbtnplus',
	'type'            => 'soledad-fw-toggle',
	'active_callback' => [
		[
			'setting'  => 'penci_post_share',
			'operator' => '!=',
			'value'    => true,
		]
	],
);
$options[] = array(
	'id'              => 'penci_post_align_cscount',
	'default'         => 'default',
	'sanitize'        => 'penci_sanitize_choices_field',
	'label'           => __( 'Share Box Alignment', 'soledad' ),
	'type'            => 'soledad-fw-select',
	'choices'         => array(
		'default' => __( 'Default Theme Style', 'soledad' ),
		'left'    => __( 'Left', 'soledad' ),
		'right'   => __( 'Right', 'soledad' ),
		'center'  => __( 'Center', 'soledad' ),
	),
	'active_callback' => [
		[
			'setting'  => 'penci_post_share',
			'operator' => '!=',
			'value'    => true,
		]
	],
);
$options[] = array(
	'default'     => 'author-postnav-related-comments',
	'sanitize'    => 'penci_sanitize_choices_field',
	'label'       => __( 'Re-order "Author Box" - "Post Navigation" - "Related Posts" - "Comments" Sections', 'soledad' ),
	'id'          => 'penci_single_ordersec',
	'description' => '',
	'type'        => 'soledad-fw-select',
	'choices'     => array(
		'author-postnav-related-comments' => __( 'Author Box - Post Navigation - Related Posts - Comments', 'soledad' ),
		'author-postnav-comments-related' => __( 'Author Box - Post Navigation - Comments - Related Posts', 'soledad' ),
		'author-comments-postnav-related' => __( 'Author Box - Comments - Post Navigation - Related Posts', 'soledad' ),
		'author-comments-related-postnav' => __( 'Author Box - Comments - Related Posts - Post Navigation', 'soledad' ),
		'author-related-comments-postnav' => __( 'Author Box - Related Posts - Comments - Post Navigation', 'soledad' ),
		'author-related-postnav-comments' => __( 'Author Box - Related Posts - Post Navigation - Comments', 'soledad' ),
		'postnav-author-related-comments' => __( 'Post Navigation - Author Box - Related Posts - Comments', 'soledad' ),
		'postnav-author-comments-related' => __( 'Post Navigation - Author Box - Comments - Related Posts', 'soledad' ),
		'postnav-comments-author-related' => __( 'Post Navigation - Comments - Author Box - Related Posts', 'soledad' ),
		'postnav-comments-related-author' => __( 'Post Navigation - Comments - Related Posts - Author Box', 'soledad' ),
		'postnav-related-comments-author' => __( 'Post Navigation - Related Posts - Comments - Author Box', 'soledad' ),
		'postnav-related-author-comments' => __( 'Post Navigation - Related Posts - Author Box - Comments', 'soledad' ),
		'related-author-comments-postnav' => __( 'Related Posts - Author Box - Comments - Post Navigation', 'soledad' ),
		'related-author-postnav-comments' => __( 'Related Posts - Author Box - Post Navigation - Comments', 'soledad' ),
		'related-comments-author-postnav' => __( 'Related Posts - Comments - Author Box - Post Navigation', 'soledad' ),
		'related-comments-postnav-author' => __( 'Related Posts - Comments - Post Navigation - Author Box', 'soledad' ),
		'related-postnav-author-comments' => __( 'Related Posts - Post Navigation - Author Box - Comments', 'soledad' ),
		'related-postnav-comments-author' => __( 'Related Posts - Post Navigation - Comments - Author Box', 'soledad' ),
		'comments-author-postnav-related' => __( 'Comments - Author Box - Post Navigation - Related Posts', 'soledad' ),
		'comments-author-related-postnav' => __( 'Comments - Author Box - Related Posts - Post Navigation', 'soledad' ),
		'comments-postnav-related-author' => __( 'Comments - Post Navigation - Related Posts - Author Box', 'soledad' ),
		'comments-postnav-author-related' => __( 'Comments - Post Navigation - Author Box - Related Posts', 'soledad' ),
		'comments-related-author-postnav' => __( 'Comments - Related Posts - Author Box - Post Navigation', 'soledad' ),
		'comments-related-postnav-author' => __( 'Comments - Related Posts - Post Navigation - Author Box', 'soledad' ),
	)
);
$options[] = array(
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field',
	'label'    => __( 'Delayed Load "Author Box" - "Post Navigation" - "Related Posts" - "Comments" Sections', 'soledad' ),
	'id'       => 'penci_single_sec_delayed',
	'type'     => 'soledad-fw-toggle',
);
$options[] = array(
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field',
	'label'    => __( 'Hide Author Box', 'soledad' ),
	'id'       => 'penci_post_author',
	'type'     => 'soledad-fw-toggle',
);
$options[] = array(
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field',
	'label'    => __( 'Remove Border Top of the Author Box', 'soledad' ),
	'id'       => 'penci_post_author_rm_bt',
	'type'     => 'soledad-fw-toggle',
);
$options[] = array(
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field',
	'label'    => __( 'Show Email Icon of Author on Author Box', 'soledad' ),
	'id'       => 'penci_post_author_email',
	'type'     => 'soledad-fw-toggle',
);
$options[] = array(
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field',
	'label'    => __( 'Disable Uppercase for Author Name on Author Box', 'soledad' ),
	'id'       => 'penci_bio_upper_name',
	'type'     => 'soledad-fw-toggle',
);
$options[] = array(
	'default'     => 'style-1',
	'sanitize'    => 'penci_sanitize_choices_field',
	'label'       => __( 'Author Box Style', 'soledad' ),
	'id'          => 'penci_authorbio_style',
	'description' => '',
	'type'        => 'soledad-fw-select',
	'choices'     => array(
		'style-1' => __( 'Default', 'soledad' ),
		'style-2' => __( 'Style 2', 'soledad' ),
		'style-3' => __( 'Style 3', 'soledad' ),
		'style-4' => __( 'Style 4', 'soledad' ),
		'style-5' => __( 'Style 5', 'soledad' ),
	)
);
$options[] = array(
	'default'     => 'round',
	'sanitize'    => 'penci_sanitize_choices_field',
	'label'       => __( 'Author Box Image Type', 'soledad' ),
	'id'          => 'penci_bioimg_style',
	'description' => '',
	'type'        => 'soledad-fw-select',
	'choices'     => array(
		'round'  => 'Round',
		'square' => 'Square',
		'sround' => 'Round Borders',
	)
);
$options[] = array(
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field',
	'label'    => __( 'Enable Sticky Next/Prev Posts', 'soledad' ),
	'id'       => 'penci_post_sticky_rlposts',
	'type'     => 'soledad-fw-toggle',
);
$options[] = array(
	'default'  => '10',
	'sanitize' => 'penci_sanitize_number_field',
	'label'    => __( 'Sticky Next/Prev Posts: Limit Title Length', 'soledad' ),
	'id'       => 'penci_post_sticky_rlposts_tlength',
	'type'     => 'soledad-fw-number',
	'active_callback' => array(
		array(
			'setting'  => 'penci_post_sticky_rlposts',
			'operator' => '==',
			'value'    => true,
		),
	),
);
$options[] = array(
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field',
	'label'    => __( 'Hide Next/Prev Posts Navigation', 'soledad' ),
	'id'       => 'penci_post_nav',
	'type'     => 'soledad-fw-toggle',
);
$options[] = array(
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field',
	'label'    => __( 'Remove Border Top of Next/Prev Post Navigation', 'soledad' ),
	'id'       => 'penci_post_nav_rm_bt',
	'type'     => 'soledad-fw-toggle',
);
$options[] = array(
	'default'  => 'style-1',
	'sanitize' => 'penci_sanitize_choices_field',
	'label'    => __( 'Next/Prev Post Navigation Style', 'soledad' ),
	'id'       => 'penci_post_pagination_style',
	'type'     => 'soledad-fw-select',
	'choices'  => array(
		'style-1' => esc_html__( 'Style 1', 'soledad' ),
		'style-2' => esc_html__( 'Style 2', 'soledad' ),
		'style-3' => esc_html__( 'Style 3', 'soledad' ),
		'style-4' => esc_html__( 'Style 4', 'soledad' ),
		'style-5' => esc_html__( 'Style 5', 'soledad' ),
	)
);
$options[] = array(
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field',
	'label'    => __( 'Turn Off Uppercase in Post Title Next/Prev Post Navigation', 'soledad' ),
	'id'       => 'penci_off_uppercase_post_title_nav',
	'type'     => 'soledad-fw-toggle',
);
$options[] = array(
	'default'     => false,
	'sanitize'    => 'penci_sanitize_checkbox_field',
	'label'       => __( 'Show Post Thumbnail on Next/Prev Post Navigation', 'soledad' ),
	'description' => __( 'This option applies to Post Navigation Style 1', 'soledad' ),
	'id'          => 'penci_post_nav_thumbnail',
	'type'        => 'soledad-fw-toggle',
);
$options[] = array(
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field',
	'label'    => __( 'Remove Lines Before & After of Heading Title on Related & Comments', 'soledad' ),
	'id'       => 'penci_post_remove_lines_related',
	'type'     => 'soledad-fw-toggle',
);
$options[] = array(
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field',
	'label'    => __( 'Disable Gallery Feature from This Theme', 'soledad' ),
	'id'       => 'penci_post_disable_gallery',
	'type'     => 'soledad-fw-toggle',
);
$options[] = array(
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field',
	'label'    => __( 'Delayed Load Main Post Content on Mobile', 'soledad' ),
	'id'       => 'penci_single_delayed_content',
	'type'     => 'soledad-fw-toggle',
);
$options[] = array(
	'default'     => false,
	'sanitize'    => 'penci_sanitize_checkbox_field',
	'label'       => __( 'Enable Video Floating', 'soledad' ),
	'description' => __( 'This option apply for the post format Video. The video will float in the corner as you scroll down outside the main video at the top.', 'soledad' ),
	'id'          => 'penci_video_float',
	'type'        => 'soledad-fw-toggle',
);
$options[] = array(
	'default'  => 'bottom-right',
	'sanitize' => 'penci_sanitize_choices_field',
	'label'    => __( 'Video Floating Position', 'soledad' ),
	'id'       => 'penci_video_float_position',
	'type'     => 'soledad-fw-select',
	'choices'  => array(
		'top-left'     => esc_html__( 'Top Left', 'soledad' ),
		'bottom-left'  => esc_html__( 'Bottom Left', 'soledad' ),
		'top-right'    => esc_html__( 'Top Right', 'soledad' ),
		'bottom-right' => esc_html__( 'Bottom Right', 'soledad' ),
	)
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'absint',
	'label'    => __( 'Custom Floating Video Mobile Width', 'soledad' ),
	'id'       => 'penci_video_float_mw',
	'type'     => 'soledad-fw-hidden',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'absint',
	'label'    => __( 'Custom Floating Video Width', 'soledad' ),
	'id'       => 'penci_video_float_w',
	'type'     => 'soledad-fw-size',
	'ids'      => array(
		'desktop' => 'penci_video_float_w',
		'mobile'  => 'penci_video_float_mw',
	),
	'choices'  => array(
		'desktop' => array(
			'min'  => 1,
			'max'  => 2000,
			'step' => 1,
			'edit' => true,
			'unit' => 'px',
		),
		'mobile'  => array(
			'min'  => 1,
			'max'  => 2000,
			'step' => 1,
			'edit' => true,
			'unit' => 'px',
		),
	),
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'absint',
	'label'    => __( 'Maximum Content Height on Mobile', 'soledad' ),
	'id'       => 'penci_single_content_hm',
	'type'     => 'soledad-fw-hidden',
);
$options[] = array(
	'default'     => '',
	'sanitize'    => 'absint',
	'label'       => __( 'Maximum Content Height', 'soledad' ),
	'description' => __( 'This option will limit the main post content and display a "Read More" button.', 'soledad' ),
	'id'          => 'penci_single_content_h',
	'type'        => 'soledad-fw-size',
	'ids'         => array(
		'desktop' => 'penci_single_content_h',
		'mobile'  => 'penci_single_content_hm',
	),
	'choices'     => array(
		'desktop' => array(
			'min'  => 1,
			'max'  => 2000,
			'step' => 1,
			'edit' => true,
			'unit' => 'px',
		),
		'mobile'  => array(
			'min'  => 1,
			'max'  => 2000,
			'step' => 1,
			'edit' => true,
			'unit' => 'px',
		),
	),
);
$options[] = array(
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field',
	'label'    => __( 'Disable Extra Author Option', 'soledad' ),
	'id'       => 'penci_disable_extra_author',
	'type'     => 'soledad-fw-toggle',
);
$options[] = array(
	'default'  => 'justified',
	'sanitize' => 'penci_sanitize_choices_field',
	'label'    => __( 'Default Gallery Style from The Theme', 'soledad' ),
	'id'       => 'penci_gallery_dstyle',
	'type'     => 'soledad-fw-select',
	'choices'  => array(
		'justified'        => __( 'Justified Style', 'soledad' ),
		'masonry'          => __( 'Masonry Style', 'soledad' ),
		'grid'             => __( 'Grid Style', 'soledad' ),
		'single-slider'    => __( 'Single Slider', 'soledad' ),
		'thumbnail-slider' => __( 'Single Slider with Thumbnail', 'soledad' ),
		'none'             => __( 'None', 'soledad' ),
	)
);
$options[] = array(
	'default'  => '150',
	'sanitize' => 'absint',
	'label'    => __( 'Custom the height of images on Justified Gallery style', 'soledad' ),
	'id'       => 'penci_image_height_gallery',
	'type'     => 'soledad-fw-size',
	'ids'      => array(
		'desktop' => 'penci_image_height_gallery',
	),
	'choices'  => array(
		'desktop' => array(
			'min'  => 1,
			'max'  => 100,
			'step' => 1,
			'edit' => true,
			'unit' => 'px',
		),
	),
);
$options[] = array(
	'default'     => 'main-sidebar',
	'sanitize'    => 'penci_sanitize_choices_field',
	'label'       => __( 'Custom Sidebar for Single', 'soledad' ),
	'id'          => 'penci_sidebar_name_single',
	'description' => __( 'If sidebar your choice is empty, will display Main Sidebar', 'soledad' ),
	'type'        => 'soledad-fw-select',
	'choices'     => get_list_custom_sidebar_option()
);
$options[] = array(
	'default'     => 'main-sidebar-left',
	'sanitize'    => 'penci_sanitize_choices_field',
	'label'       => __( 'Custom Sidebar Left for Single', 'soledad' ),
	'id'          => 'penci_sidebar_left_name_single',
	'description' => __( 'If sidebar your choice is empty, will display Main Sidebar. This option just apply when you use 2 sidebars for Single', 'soledad' ),
	'type'        => 'soledad-fw-select',
	'choices'     => get_list_custom_sidebar_option()
);
$options[] = array(
	'sanitize' => 'sanitize_text_field',
	'label'    => esc_html__( 'Ads on Single Posts', 'soledad' ),
	'id'       => 'penci_singleads_bheading',
	'type'     => 'soledad-fw-header',
);
$options[] = array(
	'default'     => '',
	'sanitize'    => 'penci_sanitize_textarea_field',
	'label'       => __( 'Add Ads/Custom HTML Code Inside Posts Content', 'soledad' ),
	'id'          => 'penci_ads_inside_content_html',
	'description' => '',
	'type'        => 'soledad-fw-code',
	'code_type'   => 'text/html',
);
$options[] = array(
	'default'  => 'style-1',
	'sanitize' => 'penci_sanitize_choices_field',
	'label'    => __( 'Add Ads/Custom HTML Code Inside Posts Content:', 'soledad' ),
	'id'       => 'penci_ads_inside_content_style',
	'type'     => 'soledad-fw-select',
	'choices'  => array(
		'style-1' => 'After Each X Paragraphs - Repeat',
		'style-2' => 'After X Paragraphs - No Repeat'
	)
);
$options[] = array(
	'default'  => '4',
	'sanitize' => 'absint',
	'label'    => __( 'Add Ads/Custom HTML Code Inside Posts Content After How Many Paragraphs?', 'soledad' ),
	'id'       => 'penci_ads_inside_content_num',
	'type'     => 'soledad-fw-size',
	'ids'      => array(
		'desktop' => 'penci_ads_inside_content_num',
	),
	'choices'  => array(
		'desktop' => array(
			'min'  => 1,
			'max'  => 2000,
			'step' => 1,
			'edit' => true,
			'unit' => '',
		),
	),
);
$options[] = array(
	'default'   => '',
	'sanitize'  => 'penci_sanitize_textarea_field',
	'label'     => __( 'Add Google Adsense/Custom HTML code For Post Template Style 10', 'soledad' ),
	'id'        => 'penci_post_adsense_single10',
	'type'      => 'soledad-fw-code',
	'code_type' => 'text/html',
);
$options[] = array(
	'default'     => '',
	'sanitize'    => 'penci_sanitize_textarea_field',
	'label'       => __( 'Add Google Adsense/Custom HTML code below post description', 'soledad' ),
	'id'          => 'penci_post_adsense_one',
	'description' => '',
	'type'        => 'soledad-fw-code',
	'code_type'   => 'text/html',
);
$options[] = array(
	'default'     => '',
	'sanitize'    => 'penci_sanitize_textarea_field',
	'label'       => __( 'Add Google Adsense/Custom HTML code at the end of content posts', 'soledad' ),
	'id'          => 'penci_post_adsense_two',
	'description' => '',
	'type'        => 'soledad-fw-code',
	'code_type'   => 'text/html',
);

return $options;
