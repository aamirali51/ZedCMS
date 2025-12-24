<?php
$options   = array();
$options[] = array(
	'id'       => 'penci_favicon',
	'sanitize' => 'esc_url_raw',
	'type'     => 'soledad-fw-image',
	'label'    => __( 'Upload Favicon', 'soledad' ),
);
$options[] = array(
	'default'     => 'horizontal',
	'label'       => __( 'Featured Images Type:', 'soledad' ),
	'id'          => 'penci_featured_image_size',
	'description' => __( 'This feature does not apply to Featured Sliders and certain special areas. For featured images on category mega menu items, please select the option for it via <strong>Customize > Logo & Header</strong>.', 'soledad' ),
	'type'        => 'soledad-fw-radio',
	'sanitize'    => 'penci_sanitize_choices_field',
	'choices'     => array(
		'horizontal' => esc_html__( 'Horizontal Size', 'soledad' ),
		'square'     => esc_html__( 'Square Size', 'soledad' ),
		'vertical'   => esc_html__( 'Vertical Size', 'soledad' ),
		'custom'     => esc_html__( 'Custom', 'soledad' ),
	),
);
$options[] = array(
	'default'     => false,
	'sanitize'    => 'penci_sanitize_checkbox_field',
	'label'       => __( 'Auto Get Featured Image from Post Content', 'soledad' ),
	'id'          => 'penci_enable_auto_featured_image',
	'type'        => 'soledad-fw-toggle',
	'description' => __( 'If you haven\'t uploaded featured images for your posts, this option will automatically set the first image from the post content or the YouTube/Vimeo thumbnail as the featured image.', 'soledad' ),
);
$options[] = array(
	'id'       => 'penci_default_thumbnail',
	'sanitize' => 'esc_url_raw',
	'type'     => 'soledad-fw-image',
	'label'    => __( 'Default Thumbnail Image', 'soledad' ),
);
$options[] = array(
	'id'       => 'penci_thumbnail_zoom_effect',
	'sanitize' => 'penci_sanitize_checkbox_field',
	'type'     => 'soledad-fw-toggle',
	'label'    => __( 'Enable Thumbnail Zoom Effect', 'soledad' ),
);
$options[] = array(
	'default'     => '',
	'label'       => __( 'Custom Container Width', 'soledad' ),
	'id'          => 'penci_custom_container_w',
	'description' => __( 'Default is 1170px. Minimum is 800px', 'soledad' ),
	'type'        => 'soledad-fw-size',
	'sanitize'    => 'absint',
	'ids'         => array(
		'desktop' => 'penci_custom_container_w',
	),
	'choices'     => array(
		'desktop' => array(
			'min'  => 800,
			'max'  => 10000,
			'step' => 1,
			'edit' => true,
			'unit' => 'px',
		),
	),
);
$options[] = array(
	'default'     => '',
	'label'       => __( 'Custom Container Width for Two Sidebars', 'soledad' ),
	'id'          => 'penci_custom_container2_w',
	'description' => __( 'Default is 1400px. Minimum is 800px', 'soledad' ),
	'type'        => 'soledad-fw-size',
	'sanitize'    => 'absint',
	'ids'         => array(
		'desktop' => 'penci_custom_container2_w',
	),
	'choices'     => array(
		'desktop' => array(
			'min'  => 800,
			'max'  => 10000,
			'step' => 1,
			'edit' => true,
			'unit' => 'px',
		),
	),
);
$options[] = array(
	'type'        => 'soledad-fw-text',
	'label'       => __( 'Custom Aspect Ratio for Featured Image', 'soledad' ),
	'id'          => 'penci_general_featured_image_ratio',
	'sanitize'    => 'sanitize_text_field',
	'description' => __( 'The aspect ratio of an element describes the proportional relationship between its width and height, for example, 3:2. The default aspect ratio for featured images type "Custom" is 3:2. This option applies to the "Custom" featured image type.', 'soledad' ),
);
$options[] = array(
	'type'        => 'soledad-fw-text',
	'label'       => __( 'Custom Border Radius for Featured Images', 'soledad' ),
	'id'          => 'penci_image_border_radius',
	'sanitize'    => 'sanitize_text_field',
	'description' => __( 'Enter the value you want for the border radius here. You can use either pixels or percentage. For example, 10px or 10%.', 'soledad' ),
);

$options[] = array(
	'label'    => __( 'Get Post Views Data From?', 'soledad' ),
	'id'       => 'penci_general_views_meta',
	'type'     => 'soledad-fw-select',
	'default'  => '',
	'sanitize' => 'penci_sanitize_choices_field',
	'choices'  => array(
		''       => __('Default - from The Theme','soledad' ),
		'custom' => __('Custom Post Meta Field','soledad' ),
	),
);
$options[] = array(
	'type'        => 'soledad-fw-text',
	'label'       => __( 'Post Views Meta Key', 'soledad' ),
	'id'          => 'penci_general_views_key',
	'sanitize'    => 'penci_sanitize_choices_field',
	'description' => __( 'Please enter the post views meta key you wish to use in order to display the number of post views. This option only applies when you have selected "Custom Post Meta Field" as the "Get Post Views Data From" option.', 'soledad' ),
	'active_callback' => [
		[
			'setting'  => 'penci_general_views_meta',
			'operator' => '==',
			'value'    => 'custom',
		]
	],
);
$options[] = array(
	'type'        => 'soledad-fw-text',
	'label'       => __( 'Set A Default Reading Time Value', 'soledad' ),
	'id'          => 'penci_readtime_default',
	'description' => __( 'If you want to set a default value for the reading time, please enter it here. For example, 3 mins.', 'soledad' ),
);
$options[] = array(
	'type'        => 'soledad-fw-toggle',
	'label'       => __( 'Estimate Reading Time base Post Content?', 'soledad' ),
	'id'          => 'penci_readtime_auto',
	'description' => __( 'Please note that if you enable this option, any value set in "Set A Default Reading Time Value" will be ignored.', 'soledad' ),
);
$options[] = array(
	'type'        => 'soledad-fw-number',
	'label'       => __( 'Reading Time: Words Per Minute', 'soledad' ),
	'id'          => 'penci_readtime_wpm',
	'default'     => 200,
	'sanitize'    => 'absint',
	'description' => __( 'Please note that this option only applies when you have enabled "Estimate Reading Time based on Post Content".', 'soledad' ),
);
$options[] = array(
	'sanitize' => 'penci_sanitize_choices_field',
	'type'     => 'soledad-fw-select',
	'default'  => 'date',
	'label'    => __( 'Sort Posts By', 'soledad' ),
	'id'       => 'penci_general_post_orderby',
	'choices'  => array(
		'date'          => __('Published Date','soledad' ),
		'ID'            => __('Post ID','soledad' ),
		'modified'      => __('Modified Date','soledad' ),
		'title'         => __('Post Title','soledad' ),
		'rand'          => __('Random Posts','soledad' ),
		'comment_count' => __('Comment Count','soledad' ),
	),
);
$options[] = array(
	'sanitize' => 'penci_sanitize_choices_field',
	'default'  => 'DESC',
	'label'    => __( 'Select Posts Order', 'soledad' ),
	'id'       => 'penci_general_post_order',
	'type'     => 'soledad-fw-select',
	'choices'  => array(
		'DESC' => __('Descending','soledad' ),
		'ASC'  => __('Ascending ','soledad' ),
	),
);
$options[] = array(
	'label'       => __( 'Use Outline Social Icons Instead of Filled Social Icons for Your Social Media?', 'soledad' ),
	'description' => __( 'You can refer to <a class="wp-customizer-link" href="https://imgresources.s3.amazonaws.com/outline-social.png" target="_blank">this image</a> to understand the difference between outline social icons and filled social icons. Please note that some icons may not be available in the outline style.', 'soledad' ),
	'id'          => 'penci_outline_social_icon',
	'type'        => 'soledad-fw-toggle',
);
$options[] = array(
	'label' => __( 'Use Outline Social Icons for Social Sharing?', 'soledad' ),
	'id'    => 'penci_outline_social_share',
	'type'  => 'soledad-fw-toggle',
);
$options[] = array(
	'sanitize'    => 'penci_sanitize_choices_field',
	'label'       => __( 'Select Separator Icon Between Posts Datas', 'soledad' ),
	'description' => __( 'You can refer to <a class="wp-customizer-link" href="https://imgresources.s3.amazonaws.com/separator-pmeta.png" target="_blank">this image</a> to understand what the "Separator Icon Between Posts Data" is."', 'soledad' ),
	'id'          => 'penci_separator_post_meta',
	'type'        => 'soledad-fw-select',
	'choices'     => array(
		''         => __('Default ( Vertical Line )','soledad' ),
		'horiline' => __('Horizontal Line','soledad' ),
		'circle'   => __('Circle Filled','soledad' ),
		'bcricle'  => __('Circle Bordered','soledad' ),
		'square'   => __('Square Filled','soledad' ),
		'bsquare'  => __('Square Bordered','soledad' ),
		'diamond'  => __('Diamond Square Filled','soledad' ),
		'bdiamond' => __('Diamond Square Bordered','soledad' ),
	),
);
$options[] = array(
	'sanitize'    => 'penci_sanitize_choices_field',
	'label'       => __( 'Style for Post Categories Listing', 'soledad' ),
	'description' => __( 'You can refer to <a class="wp-customizer-link" href="https://imgresources.s3.amazonaws.com/cat_design.png" target="_blank">this image</a> to understand the styles for post categories listing. You can change the general color for it via General > Colors > Filled Categories Styles.', 'soledad' ),
	'id'          => 'penci_catdesign',
	'type'        => 'soledad-fw-select',
	'choices'     => array(
		''      => __('Default','soledad' ),
		'fill'  => __('Filled Rectangle','soledad' ),
		'fillr' => __('Filled Round','soledad' ),
		'fillc' => __('Filled Circle','soledad' ),
	),
);
$options[] = array(
	'sanitize'    => 'penci_sanitize_choices_field',
	'label'       => __( 'Select Separator Icon Between Categories', 'soledad' ),
	'description' => __( 'You can refer to <a class="wp-customizer-link" href="https://imgresources.s3.amazonaws.com/separator-cat.png" target="_blank">this image</a>  to understand what the "Separator Icon Between Categories" is. Please note that this does not apply to the filled styles shown above.', 'soledad' ),
	'id'          => 'penci_separator_cat',
	'type'        => 'soledad-fw-select',
	'choices'     => array(
		''         => __('Default ( Diamond Square Bordered )','soledad' ),
		'verline'  => __('Vertical Line','soledad' ),
		'horiline' => __('Horizontal Line','soledad' ),
		'circle'   => __('Circle Filled','soledad' ),
		'bcricle'  => __('Circle Bordered','soledad' ),
		'square'   => __('Square Filled','soledad' ),
		'bsquare'  => __('Square Bordered','soledad' ),
		'diamond'  => __('Diamond Square Filled','soledad' ),
	),
);
$options[] = array(
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field',
	'label'    => __( 'Display Modified Date Replace with Published Date', 'soledad' ),
	'id'       => 'penci_show_modified_date',
	'type'     => 'soledad-fw-toggle',
);
$options[] = array(
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field',
	'label'    => __( 'Add "Published", "Updated" text before Post Date', 'soledad' ),
	'id'       => 'penci_addpre_date_text',
	'type'     => 'soledad-fw-toggle',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'penci_sanitize_choices_field',
	'label'    => __( 'Select Date Format', 'soledad' ),
	'id'       => 'penci_date_format',
	'type'     => 'soledad-fw-select',
	'choices'  => array(
		''        => __('Default ( By Day, Month & Year )','soledad' ),
		'timeago' => __('Time Ago Format','soledad' ),
	),
);
$options[] = array(
	'default'  => 's9',
	'sanitize' => 'penci_sanitize_choices_field',
	'label'    => __( 'Style for Blocks Loading Ajax', 'soledad' ),
	'id'       => 'penci_block_lajax',
	'type'     => 'soledad-fw-select',
	'choices'  => array(
		's9' => __( 'Style 1','soledad' ),
		's2' => __( 'Style 2','soledad' ),
		's3' => __( 'Style 3','soledad' ),
		's4' => __( 'Style 4','soledad' ),
		's5' => __( 'Style 5','soledad' ),
		's6' => __( 'Style 6','soledad' ),
		's1' => __( 'Style 7','soledad' ),
	),
);
$options[] = array(
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field',
	'label'    => __( 'Enable Smooth Scroll', 'soledad' ),
	'id'       => 'penci_enable_smooth_scroll',
	'type'     => 'soledad-fw-toggle',
);
$options[] = array(
	'label'    => __( 'Enable Page Navigation Numbers', 'soledad' ),
	'id'       => 'penci_page_navigation_numbers',
	'type'     => 'soledad-fw-toggle',
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field',
);
$options[] = array(
	'label'    => __( 'Page Navigation Numbers Alignment', 'soledad' ),
	'id'       => 'penci_page_navigation_align',
	'type'     => 'soledad-fw-select',
	'choices'  => array(
		'align-left'   => __('Left','soledad' ),
		'align-right'  => __('Right','soledad' ),
		'align-center' => __('Center','soledad' ),
	),
	'default'  => 'align-left',
	'sanitize' => 'penci_sanitize_choices_field',
	'active_callback' => [
		[
			'setting'  => 'penci_page_navigation_numbers',
			'operator' => '==',
			'value'    => true,
		]
	],
);
$options[] = array(
	'label'       => __( 'Show Primary Category Only', 'soledad' ),
	'description' => __( 'If you are not select the Primary Category OR using the "Yoast SEO""Rank Math" plugin, the first category listed in the post categories will be displayed.', 'soledad' ),
	'id'          => 'penci_show_pricat_yoast_only',
	'type'        => 'soledad-fw-toggle',
	'default'     => false,
	'sanitize'    => 'penci_sanitize_checkbox_field',
);
$options[] = array(
	'label'    => __( 'Show Primary Category at First ( When you display full categories )', 'soledad' ),
	'id'       => 'penci_show_pricat_first_yoast',
	'type'     => 'soledad-fw-toggle',
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field',
);
$options[] = array(
	'label'       => __( 'Get the Post Excerpt Length based on Number of Letters', 'soledad' ),
	'description' => __( 'This option can help fix problems with excerpt length in Chinese or Japanese languages.', 'soledad' ),
	'id'          => 'penci_excerptcharac',
	'type'        => 'soledad-fw-toggle',
	'default'     => false,
	'sanitize'    => 'penci_sanitize_checkbox_field',
);
$options[] = array(
	'label'       => __( 'Limit User Access Media Library', 'soledad' ),
	'description' => __( 'Users can only access the media files that they have uploaded.', 'soledad' ),
	'id'          => 'limit_access_media',
	'type'        => 'soledad-fw-toggle',
	'default'     => false,
	'sanitize'    => 'penci_sanitize_checkbox_field',
);
$options[] = array(
	'label'       => __( 'Utilize the Image Tag Instead of a Background image for Post Layouts', 'soledad' ),
	'id'          => 'penci_img_layouttag',
	'type'        => 'soledad-fw-toggle',
	'default'     => false,
	'sanitize'    => 'penci_sanitize_checkbox_field',
);
$options[] = array(
	'label'       => __( 'Synchronize user time for the penci_date shortcode and the Date/Time Header Builder element.', 'soledad' ),
	'id'          => 'penci_time_sync',
	'type'        => 'soledad-fw-toggle',
	'default'     => false,
	'sanitize'    => 'penci_sanitize_checkbox_field',
);
$options[] = array(
	'label' => __( 'Breadcrumb Settings', 'soledad' ),
	'id'    => 'author_breadcrumb_heading_01',
	'type'  => 'soledad-fw-header',
);
$options[] = array(
	'default'  => false,
	'label'    => __( 'Disable Entire Site Breadcrumb', 'soledad' ),
	'id'       => 'penci_disable_breadcrumb',
	'type'     => 'soledad-fw-toggle',
	'sanitize' => 'penci_sanitize_checkbox_field',
);
$options[] = array(
	'default'  => false,
	'label'    => __( 'Disable Breadcrumbs on Single Post Pages.', 'soledad' ),
	'id'       => 'penci_disable_posts_breadcrumb',
	'type'     => 'soledad-fw-toggle',
	'sanitize' => 'penci_sanitize_checkbox_field',
	'active_callback' => [
		[
			'setting'  => 'penci_disable_breadcrumb',
			'operator' => '!=',
			'value'    => true,
		]
	],
);
$options[] = array(
	'default'  => false,
	'label'    => __( 'Hidden Post Title on Breadcrumbs ', 'soledad' ),
	'id'       => 'penci_hide_post_title_breadcrumb',
	'type'     => 'soledad-fw-toggle',
	'sanitize' => 'penci_sanitize_checkbox_field',
	'active_callback' => [
		[
			'setting'  => 'penci_disable_breadcrumb',
			'operator' => '!=',
			'value'    => true,
		]
	],
);
$options[] = array(
	'default'  => false,
	'label'    => __( 'Disable Breadcrumbs on Pages.', 'soledad' ),
	'id'       => 'penci_disable_pages_breadcrumb',
	'type'     => 'soledad-fw-toggle',
	'sanitize' => 'penci_sanitize_checkbox_field',
	'active_callback' => [
		[
			'setting'  => 'penci_disable_breadcrumb',
			'operator' => '!=',
			'value'    => true,
		]
	],
);
$options[] = array(
	'default'  => false,
	'label'    => __( 'Disable Breadcrumbs on Archive Pages.', 'soledad' ),
	'id'       => 'penci_disable_archive_breadcrumb',
	'type'     => 'soledad-fw-toggle',
	'sanitize' => 'penci_sanitize_checkbox_field',
	'active_callback' => [
		[
			'setting'  => 'penci_disable_breadcrumb',
			'operator' => '!=',
			'value'    => true,
		]
	],
);
$options[] = array(
	'default'  => false,
	'label'    => __( 'Disable Breadcrumbs on Home Page.', 'soledad' ),
	'id'       => 'penci_disable_home_breadcrumb',
	'type'     => 'soledad-fw-toggle',
	'sanitize' => 'penci_sanitize_checkbox_field',
	'active_callback' => [
		[
			'setting'  => 'penci_disable_breadcrumb',
			'operator' => '!=',
			'value'    => true,
		]
	],
);
$options[] = array(
	'label'    => __( 'Show Only Primary Category from "Yoast SEO" or "Rank Math" plugin for Breadcrumb', 'soledad' ),
	'id'       => 'enable_pri_cat_yoast_seo',
	'type'     => 'soledad-fw-toggle',
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field',
	'active_callback' => [
		[
			'setting'  => 'penci_disable_breadcrumb',
			'operator' => '!=',
			'value'    => true,
		]
	],
);
$options[] = array(
	'label' => __( 'Author Avatar Settings', 'soledad' ),
	'id'    => 'author_avatar_heading_01',
	'type'  => 'soledad-fw-header',
);
$options[] = array(
	'label'    => __( 'Meta Author Display', 'soledad' ),
	'id'       => 'penci_meta_author_display',
	'type'     => 'soledad-fw-select',
	'choices'  => array(
		'author-name'        => __('Name','soledad' ),
		'author-avatar'      => __('Avatar','soledad' ),
		'author-name-avatar' => __('Name & Avatar','soledad' ),
	),
	'default'  => 'author-name',
	'sanitize' => 'penci_sanitize_choices_field',
);
$options[] = array(
	'type'     => 'soledad-fw-number',
	'label'    => __( 'Avatar Size', 'soledad' ),
	'id'       => 'penci_meta_author_aw',
	'default'  => 22,
	'sanitize' => 'absint',
);
$options[] = array(
	'type'     => 'soledad-fw-text',
	'label'    => __( 'Avatar Border Radius', 'soledad' ),
	'id'       => 'penci_meta_author_bd',
	'default'  => '10px',
	'sanitize' => 'sanitize_text_field',
);
$options[] = array(
	'label' => __( 'General Slider Options', 'soledad' ),
	'id'    => 'slider_option_heading_01',
	'type'  => 'soledad-fw-header',
);
$options[] = array(
	'label'       => __( 'Carousel Slider Effect', 'soledad' ),
	'description' => __( 'The "Swing" effect does not support the loop option.', 'soledad' ),
	'id'          => 'penci_carousel_slider_effect',
	'type'        => 'soledad-fw-select',
	'choices'     => array(
		'default' => __('Default','soledad' ),
		'swing'   => __('Swing','soledad' ),
	),
	'default'     => 'swing',
	'sanitize'    => 'penci_sanitize_choices_field',
);
$options[] = array(
	'label'       => __( 'General Slider Effect', 'soledad' ),
	'id'          => 'penci_single_slider_effect',
	'description' => __( 'Some sliders do not support all effects listed below.', 'soledad' ),
	'type'        => 'soledad-fw-select',
	'choices'     => array(
		'fade'      => __('Fade','soledad' ),
		'slide'     => __('Slide','soledad' ),
		'coverflow' => __('Coverflow','soledad' ),
		'flip'      => __('Flip','soledad' ),
		'cards'     => __('Cards','soledad' ),
		'creative'  => __('Creative','soledad' ),
	),
	'default'     => 'creative',
	'sanitize'    => 'penci_sanitize_choices_field',
);
$options[] = array(
	'label' => __( 'Sponsored Posts Options', 'soledad' ),
	'id'    => 'slider_option_heading_09',
	'type'  => 'soledad-fw-header',
);
$options[] = array(
	'label'    => __( 'Show Prefix Text Before Post Title', 'soledad' ),
	'id'       => 'penci_sponsored_prefix',
	'type'     => 'soledad-fw-toggle',
	'default'  => true,
	'sanitize' => 'penci_sanitize_checkbox_field',
);
return $options;
