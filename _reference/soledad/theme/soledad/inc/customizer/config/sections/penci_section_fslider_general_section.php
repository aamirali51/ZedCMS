<?php
$options   = [];
$options[] = array(
	'label'    => __( 'Enable Featured Slider', 'soledad' ),
	'id'       => 'penci_featured_slider',
	'type'     => 'soledad-fw-toggle',
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field'
);
$options[] = array(
	'label'    => __( 'Enable Featured Slider on All Page', 'soledad' ),
	'id'       => 'penci_featured_slider_all_page',
	'type'     => 'soledad-fw-toggle',
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field'
);
$options[] = array(
	'label'       => __( 'Enable Flat Overlay Replace with Gradient Overlay', 'soledad' ),
	'id'          => 'penci_enable_flat_overlay',
	'description' => __( 'This option does not apply for Slider Styles 1, 2, 3, 4, 5, 29, 30, 35, 36, 37, 38 & Penci Sliders', 'soledad' ),
	'type'        => 'soledad-fw-toggle',
	'default'     => false,
	'sanitize'    => 'penci_sanitize_checkbox_field'
);
$options[] = array(
	'label'       => __( 'Border Radius for Images on Featured Slider', 'soledad' ),
	'id'          => 'penci_slider_border_radius',
	'default'     => '',
	'sanitize'    => 'sanitize_text_field',
	'type'        => 'soledad-fw-text',
	'description' => __( 'You can use pixel or percent. E.g:  <strong>10px</strong>  or  <strong>10%</strong>. If you want to disable border radius for slider. Fill 0', 'soledad' ),
);
$options[] = array(
	'label'       => __( 'Featured Slider Style', 'soledad' ),
	'id'          => 'penci_featured_slider_style',
	'default'     => 'style-1',
	'sanitize'    => 'penci_sanitize_choices_field',
	'description' => __( 'If you choose Penci Slider, you need have posts in "Penci Slider" post type', 'soledad' ),
	'type'        => 'soledad-fw-select',
	'choices'     => array(
		'style-1'  => 'Style 1',
		'style-2'  => 'Style 2',
		'style-3'  => 'Style 3',
		'style-4'  => 'Style 4',
		'style-5'  => 'Style 5',
		'style-6'  => 'Style 6',
		'style-7'  => 'Style 7',
		'style-8'  => 'Style 8',
		'style-9'  => 'Style 9',
		'style-10' => 'Style 10',
		'style-11' => 'Style 11',
		'style-12' => 'Style 12',
		'style-13' => 'Style 13',
		'style-14' => 'Style 14',
		'style-15' => 'Style 15',
		'style-16' => 'Style 16',
		'style-17' => 'Style 17',
		'style-18' => 'Style 18',
		'style-19' => 'Style 19',
		'style-20' => 'Style 20',
		'style-21' => 'Style 21',
		'style-22' => 'Style 22',
		'style-23' => 'Style 23',
		'style-24' => 'Style 24',
		'style-25' => 'Style 25',
		'style-26' => 'Style 26',
		'style-27' => 'Style 27',
		'style-28' => 'Style 28',
		'style-29' => 'Style 29',
		'style-30' => 'Style 30',
		'style-31' => 'Penci Slider Style 1',
		'style-32' => 'Penci Slider Style 2',
		'style-33' => 'Revolution Slider Full Width',
		'style-34' => 'Revolution Slider In Container',
		'style-35' => 'Style 35',
		'style-36' => 'Style 36',
		'style-37' => 'Style 37',
		'style-38' => 'Style 38',
		'style-40' => 'Style 39',
		'style-41' => 'Style 40',
		'style-42' => 'Style 41',
		'style-44' => 'Style 42',
	)
);
$options[] = array(
	'label'       => __( 'Carousel Sliders Effect', 'soledad' ),
	'description' => __( 'This option apply for slider styles 7,8,9,10 and 38. The "Swing" effect does not support the loop option.', 'soledad' ),
	'id'          => 'penci_fslider_carousel_slider_effect',
	'type'        => 'soledad-fw-select',
	'choices'     => array(
		'default' => 'Default',
		'swing'   => 'Swing',
	),
	'default'     => 'swing',
	'sanitize'    => 'penci_sanitize_choices_field',
);
$options[] = array(
	'label'       => __( 'General Slider Effect', 'soledad' ),
	'description' => __( 'Some sliders do not support all effects listed below.', 'soledad' ),
	'id'          => 'penci_fslider_single_slider_effect',
	'type'        => 'soledad-fw-select',
	'choices'     => array(
		'slide'     => 'Slide',
		'fade'      => 'Fade',
		'coverflow' => 'Coverflow',
		'flip'      => 'Flip',
		'cards'     => 'Cards',
		'creative'  => 'Creative',
	),
	'default'     => 'creative',
	'sanitize'    => 'penci_sanitize_choices_field',
);
$options[] = array(
	'label'       => __( 'Revolution Slider Shortcode', 'soledad' ),
	'id'          => 'penci_feature_rev_sc',
	'type'        => 'soledad-fw-textarea',
	'default'     => '',
	'sanitize'    => 'sanitize_text_field',
	'description' => __( 'If you choose Featured Slider Style is Revolution Slider, you need to fill Revolution Slider shortcode here', 'soledad' ),
);
$options[] = array(
	'label'    => __( 'Enable Next/Prev Button for Penci Slider & Slider Styles 35, 36, 37', 'soledad' ),
	'section'  => 'penci_section_fslider_general',
	'id'       => 'penci_enable_next_prev_penci_slider',
	'type'     => 'soledad-fw-toggle',
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field'
);
$options[] = array(
	'label'    => __( 'Disable Dots for Penci Slider & Slider Styles 35, 36, 37', 'soledad' ),
	'section'  => 'penci_section_fslider_general',
	'id'       => 'penci_disable_dots_penci_slider',
	'type'     => 'soledad-fw-toggle',
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field'
);
$options[] = array(
	'label'    => __( 'Custom Image Size on the Slider', 'soledad' ),
	'id'       => 'featured_slider_imgsize',
	'type'     => 'soledad-fw-ajax-select',
	'choices'  => call_user_func( function () {
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
	'default'  => '',
	'sanitize' => 'penci_sanitize_choices_field'
);
$options[] = array(
	'label'    => __( 'Custom Image Size for Big Posts on the Slider', 'soledad' ),
	'section'  => 'penci_section_fslider_general',
	'id'       => 'featured_slider_imgbig',
	'type'     => 'soledad-fw-ajax-select',
	'choices'  => call_user_func( function () {
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
	'default'  => '',
	'sanitize' => 'penci_sanitize_choices_field'
);
$options[] = array(
	'label'    => __( 'Custom Image Size on Mobile', 'soledad' ),
	'id'       => 'featured_slider_imgsize_mobile',
	'type'     => 'soledad-fw-ajax-select',
	'choices'  => call_user_func( function () {
		global $_wp_additional_image_sizes;

		$default_image_sizes = get_intermediate_image_sizes();
		$image_sizes         = [];

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
	'default'  => '',
	'sanitize' => 'penci_sanitize_choices_field'
);
$options[] = array(
	'label'    => __( 'Custom Words Length for Post Titles', 'soledad' ),
	'id'       => 'penci_slider_title_length',
	'type'     => 'soledad-fw-size',
	'default'  => '12',
	'sanitize' => 'absint',
	'ids'      => array(
		'desktop' => 'penci_slider_title_length',
	),
	'choices'  => array(
		'desktop' => array(
			'min'     => 1,
			'max'     => 2000,
			'step'    => 1,
			'edit'    => true,
			'unit'    => '',
			'default' => '12',
		),
	),
);
$options[] = array(
	'label'    => __( 'Order By on Featured Slider', 'soledad' ),
	'id'       => 'featured_slider_orderby',
	'type'     => 'soledad-fw-select',
	'choices'  => array(
		'date'          => __( 'Post Date', 'soledad' ),
		'modified'      => __( 'Modified Date', 'soledad' ),
		'title'         => __( 'Post Title', 'soledad' ),
		'rand'          => __( 'Random Posts', 'soledad' ),
		'ID'            => __( 'Post ID', 'soledad' ),
		'comment_count' => __( 'Comment Count', 'soledad' ),
		'most_liked'    => __( 'Most Liked', 'soledad' ),
	),
	'default'  => 'date',
	'sanitize' => 'penci_sanitize_choices_field'
);
$options[] = array(
	'label'    => __( 'Sort Order on Featured Slider', 'soledad' ),
	'id'       => 'featured_slider_order',
	'type'     => 'soledad-fw-select',
	'choices'  => array(
		'DESC' => __( 'Descending', 'soledad' ),
		'ASC'  => __( 'Ascending ', 'soledad' ),
	),
	'default'  => 'DESC',
	'sanitize' => 'penci_sanitize_choices_field'
);
$options[] = array(
	'label'    => __( 'Amount of Slides', 'soledad' ),
	'id'       => 'penci_featured_slider_slides',
	'type'     => 'soledad-fw-size',
	'default'  => '6',
	'sanitize' => 'absint',
	'ids'      => array(
		'desktop' => 'penci_featured_slider_slides',
	),
	'choices'  => array(
		'desktop' => array(
			'min'     => 1,
			'max'     => 2000,
			'step'    => 1,
			'edit'    => true,
			'unit'    => '',
			'default' => '6',
		),
	),
);
$options[] = array(
	'label'    => __( 'Enable Auto Play Slider', 'soledad' ),
	'id'       => 'penci_featured_autoplay',
	'type'     => 'soledad-fw-toggle',
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field'
);
$options[] = array(
	'label'       => __( 'Featured Slider Auto Time', 'soledad' ),
	'description' => __( '1000 = 1 second', 'soledad' ),
	'id'          => 'penci_featured_slider_auto_time',
	'type'        => 'soledad-fw-size',
	'default'     => '4000',
	'sanitize'    => 'absint',
	'ids'         => array(
		'desktop' => 'penci_featured_slider_auto_time',
	),
	'choices'     => array(
		'desktop' => array(
			'min'     => 1,
			'max'     => 2000,
			'step'    => 1,
			'edit'    => true,
			'unit'    => '',
			'default' => '4000',
		),
	),
);
$options[] = array(
	'label'       => __( 'Featured Slider Auto Speed', 'soledad' ),
	'description' => __( '1000 = 1 second', 'soledad' ),
	'id'          => 'penci_featured_slider_auto_speed',
	'default'     => '600',
	'sanitize'    => 'absint',
	'type'        => 'soledad-fw-size',
	'ids'         => array(
		'desktop' => 'penci_featured_slider_auto_speed',
	),
	'choices'     => array(
		'desktop' => array(
			'min'     => 1,
			'max'     => 2000,
			'step'    => 1,
			'edit'    => true,
			'unit'    => '',
			'default' => '600',
		),
	),
);
$options[] = array(
	'label'    => __( 'Featured Penci Slider Height', 'soledad' ),
	'id'       => 'penci_featured_penci_slider_height',
	'default'  => '',
	'sanitize' => 'absint',
	'type'     => 'soledad-fw-size',
	'ids'      => array(
		'desktop' => 'penci_featured_penci_slider_height',
	),
	'choices'  => array(
		'desktop' => array(
			'min'  => 1,
			'max'  => 2000,
			'step' => 1,
			'edit' => true,
			'unit' => 'px',
		),
	),
);
$options[] = array(
	'label'    => __( 'Slides Per Group', 'soledad' ),
	'id'       => 'penci_featured_penci_slider_slidespg',
	'default'  => '',
	'sanitize' => 'absint',
	'type'     => 'soledad-fw-size',
	'ids'      => array(
		'desktop' => 'penci_featured_penci_slider_slidespg',
	),
	'choices'  => array(
		'desktop' => array(
			'min'     => 1,
			'max'     => 2000,
			'step'    => 1,
			'edit'    => true,
			'unit'    => '',
			'default' => '',
		),
	),
);
$options[] = array(
	'label'    => __( 'Start the slider from slide number', 'soledad' ),
	'id'       => 'penci_featured_penci_slider_slideTo',
	'default'  => '',
	'sanitize' => 'absint',
	'type'     => 'soledad-fw-size',
	'ids'      => array(
		'desktop' => 'penci_featured_penci_slider_slideTo',
	),
	'choices'  => array(
		'desktop' => array(
			'min'     => 1,
			'max'     => 2000,
			'step'    => 1,
			'edit'    => true,
			'unit'    => '',
			'default' => '',
		),
	),
);
$options[] = array(
	'label'       => __( 'Custom Fixed Ratio Height/Width of Images on Penci Slider', 'soledad' ),
	'description' => __( 'Example: height/width = 0.45 = 45% - let fill 45', 'soledad' ),
	'id'          => 'penci_featured_penci_slider_ratio',
	'type'        => 'soledad-fw-size',
	'ids'         => array(
		'desktop' => 'penci_featured_penci_slider_ratio',
	),
	'choices'     => array(
		'desktop' => array(
			'min'  => 1,
			'max'  => 2000,
			'step' => 1,
			'edit' => true,
			'unit' => '%',
		),
	),
);
$options[] = array(
	'label'    => __( 'Remove Black Overlay Background on Heading & Captions of Penci Slider', 'soledad' ),
	'section'  => 'penci_section_fslider_general',
	'id'       => 'penci_penci_slider_remove_overlay',
	'type'     => 'soledad-fw-toggle',
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field'
);
$options[] = array(
	'label'    => __( 'Filter Featured Slider By', 'soledad' ),
	'section'  => 'penci_section_fslider_general',
	'id'       => 'penci_featured_slider_filter_type',
	'type'     => 'soledad-fw-select',
	'choices'  => array(
		'category' => __( 'Featured Category', 'soledad' ),
		'tags'     => __( 'Featured Tags', 'soledad' ),
	),
	'default'  => 'category',
	'sanitize' => 'penci_sanitize_choices_field'
);
$options[] = array(
	'label'       => __( 'Select Featured Category', 'soledad' ),
	'id'          => 'penci_featured_cat',
	'description' => __( 'Just apply when you select filter by Featured Category above', 'soledad' ),
	'default'     => '0',
	'type'        => 'soledad-fw-ajax-select',
	'choices'     => call_user_func( function () {
		$category   = [ '' ];
		$categories = get_categories( [
			'hide_empty'   => false,
			'hierarchical' => true,
		] );
		foreach ( $categories as $value ) {
			$category[ $value->term_id ] = $value->name;
		}

		return $category;
	} ),
	'sanitize'    => 'penci_sanitize_choices_field'
);
$options[] = array(
	'label'       => __( 'Fill List Featured Tags for Featured Slider', 'soledad' ),
	'id'          => 'penci_featured_tags',
	'description' => __( 'Just apply when you select filter by Featured Tags above. And please fill list featured tags slug here, check <a class="wp-customizer-link" rel="nofollow" href="https://soledad.pencidesign.net/soledad-document/images/tags.png" target="_blank">this image</a> to know what is tags slug. Example for multiple tags slug, fill:  tag-1, tag-2, tag-3', 'soledad' ),
	'type'        => 'soledad-fw-textarea',
	'default'     => '',
	'sanitize'    => 'penci_sanitize_textarea_field'
);
$options[] = array(
	'label'       => __( 'Hide Featured Category', 'soledad' ),
	'id'          => 'penci_featured_cat_hide',
	'description' => __( 'Check this if you want the featured category to be hide on all pages.', 'soledad' ),
	'type'        => 'soledad-fw-toggle',
	'default'     => false,
	'sanitize'    => 'penci_sanitize_checkbox_field'
);
$options[] = array(
	'label'       => __( 'Exclude All Posts on the Featured Slider in Latest Posts on Homepage', 'soledad' ),
	'description' => __( 'Let view your site outside customize page to see the changes.', 'soledad' ),
	'id'          => 'penci_exclude_featured_cat',
	'type'        => 'soledad-fw-toggle',
	'default'     => false,
	'sanitize'    => 'penci_sanitize_checkbox_field'
);
$options[] = array(
	'label'    => __( 'Hide Center Box on Featured Slider', 'soledad' ),
	'id'       => 'penci_featured_center_box',
	'type'     => 'soledad-fw-toggle',
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field'
);
$options[] = array(
	'label'    => __( 'Turn off Uppercase of Heading/Post Titles on Slider', 'soledad' ),
	'id'       => 'penci_featured_off_uppercase_title',
	'type'     => 'soledad-fw-toggle',
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field'
);
$options[] = array(
	'label'    => __( 'Hide Post Date', 'soledad' ),
	'id'       => 'penci_featured_meta_date',
	'type'     => 'soledad-fw-toggle',
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field'
);
$options[] = array(
	'label'       => __( 'Hide Categories Of Post', 'soledad' ),
	'id'          => 'penci_featured_hide_categories',
	'description' => '',
	'type'        => 'soledad-fw-toggle',
	'default'     => false,
	'sanitize'    => 'penci_sanitize_checkbox_field'
);
$options[] = array(
	'label'       => __( 'Display Categories for all Posts on Slider', 'soledad' ),
	'id'          => 'penci_featured_show_categories',
	'description' => __( 'By default, we disabled categories for some slider styles & some small posts - this option will help you show it if you want.', 'soledad' ),
	'type'        => 'soledad-fw-toggle',
	'default'     => false,
	'sanitize'    => 'penci_sanitize_checkbox_field'
);
$options[] = array(
	'label'    => __( 'Hide Comment Count', 'soledad' ),
	'id'       => 'penci_featured_meta_comment',
	'type'     => 'soledad-fw-toggle',
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field'
);
$options[] = array(
	'label'    => __( 'Show Post Author', 'soledad' ),
	'id'       => 'penci_featured_meta_author',
	'type'     => 'soledad-fw-toggle',
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field'
);
$options[] = array(
	'label'       => __( 'Hide Post Excerpt', 'soledad' ),
	'section'     => 'penci_section_fslider_general',
	'description' => __( 'Apply for some slider styles has post excerpt.', 'soledad' ),
	'id'          => 'penci_featured_meta_excerpt',
	'type'        => 'soledad-fw-toggle',
	'default'     => false,
	'sanitize'    => 'penci_sanitize_checkbox_field'
);
$options[] = array(
	'label'    => __( 'Hide Post Format Icons', 'soledad' ),
	'id'       => 'penci_featured_slider_icons',
	'type'     => 'soledad-fw-toggle',
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field'
);
$options[] = array(
	'label'       => __( 'Add Google Adsense/Custom HTML Code Below Featured Slider', 'soledad' ),
	'id'          => 'penci_home_adsense_below_slider',
	'description' => '',
	'type'        => 'soledad-fw-code',
	'code_type'   => 'text/html',
	'default'     => '',
	'sanitize'    => 'penci_sanitize_textarea_field'
);

return $options;
