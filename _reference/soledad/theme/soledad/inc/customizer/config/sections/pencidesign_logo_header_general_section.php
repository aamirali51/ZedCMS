<?php
$options               = [];
$header_layout_options = array(
	'header-1'  => __( 'Header 1', 'soledad' ),
	'header-2'  => __( 'Header 2', 'soledad' ),
	'header-3'  => __( 'Header 3', 'soledad' ),
	'header-4'  => __( 'Header 4 ( Centered )', 'soledad' ),
	'header-5'  => __( 'Header 5 ( Centered )', 'soledad' ),
	'header-6'  => __( 'Header 6', 'soledad' ),
	'header-7'  => __( 'Header 7', 'soledad' ),
	'header-8'  => __( 'Header 8', 'soledad' ),
	'header-9'  => __( 'Header 9', 'soledad' ),
	'header-10' => __( 'Header 10', 'soledad' ),
	'header-11' => __( 'Header 11', 'soledad' ),
);
$options[]             = array(
	'default'  => 'header-1',
	'sanitize' => 'penci_sanitize_choices_field',
	'label'    => __( 'Header Layout', 'soledad' ),
	'id'       => 'penci_header_layout',
	'type'     => 'soledad-fw-select',
	'choices'  => $header_layout_options,
);
$options[]             = array(
	'default'  => '',
	'sanitize' => 'penci_sanitize_choices_field',
	'label'    => __( 'Custom Header Container Width', 'soledad' ),
	'id'       => 'penci_header_ctwidth',
	'type'     => 'soledad-fw-select',
	'choices'  => array(
		''          => esc_html__( 'Width: 1170px', 'soledad' ),
		'1400'      => esc_html__( 'Width: 1400px', 'soledad' ),
		'fullwidth' => esc_html__( 'FullWidth', 'soledad' ),
	)
);
$options[]             = array(
	'sanitize'    => 'esc_url_raw',
	'type'        => 'soledad-fw-image',
	'label'       => __( 'Banner Header Right For Header 3', 'soledad' ),
	'id'          => 'penci_header_3_banner',
	'description' => __( 'You should choose banner with 728px width and 90px - 100px height for the best result', 'soledad' ),
);
$options[]             = array(
	'default'     => '#',
	'sanitize'    => 'esc_url_raw',
	'label'       => __( 'Link To Go When Click Banner Header Right on Header 3', 'soledad' ),
	'id'          => 'penci_header_3_banner_url',
	'description' => '',
	'type'        => 'soledad-fw-text',
);
$options[]             = array(
	'default'     => '',
	'sanitize'    => 'penci_sanitize_textarea_field',
	'label'       => __( 'Google adsense/custom HTML code to display in header 3', 'soledad' ),
	'id'          => 'penci_header_3_adsense',
	'description' => __( 'If you want use google adsense/custom HTML code in header style 3, paste your google adsense/custom HTML code here', 'soledad' ),
	'type'        => 'soledad-fw-code',
	'code_type'   => 'text/html',
);
$options[]             = array(
	'default'     => false,
	'sanitize'    => 'penci_sanitize_checkbox_field',
	'label'       => __( 'Remove Border Bottom on The Header', 'soledad' ),
	'id'          => 'penci_remove_border_bottom_header',
	'description' => __( 'This option just apply for header styles 1, 4, 7', 'soledad' ),
	'type'        => 'soledad-fw-toggle',
);
$options[]             = array(
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field',
	'label'    => __( 'Disable Header Social Icons', 'soledad' ),
	'id'       => 'penci_header_social_check',
	'type'     => 'soledad-fw-toggle',
);
$options[]             = array(
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field',
	'label'    => __( 'Enable Use Brand Colors for Social Icons on Header', 'soledad' ),
	'id'       => 'penci_header_social_brand',
	'type'     => 'soledad-fw-toggle',
);
$options[]             = array(
	'default'  => '14',
	'sanitize' => 'absint',
	'label'    => __( 'Custom Font Size for Social Icons', 'soledad' ),
	'id'       => 'penci_size_header_social_check',
	'type'     => 'soledad-fw-size',
	'ids'      => array(
		'desktop' => 'penci_size_header_social_check',
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
$options[]             = array(
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field',
	'label'    => __( 'Display Top Instagram Widget Title Overlay Images', 'soledad' ),
	'id'       => 'penci_top_insta_overlay_image',
	'type'     => 'soledad-fw-toggle',
);
$options[]             = array(
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field',
	'label'    => __( 'Hide Instagram Icon on Top Instagram Widget', 'soledad' ),
	'id'       => 'penci_top_insta_hide_icon',
	'type'     => 'soledad-fw-toggle',
);
$options[]             = array(
	'default'   => '',
	'sanitize'  => 'penci_sanitize_textarea_field',
	'label'     => __( 'Add Custom Code Inside <head> tag', 'soledad' ),
	'id'        => 'penci_custom_code_inside_head_tag',
	'type'      => 'soledad-fw-code',
	'code_type' => 'text/html',
);
$options[]             = array(
	'default'   => '',
	'sanitize'  => 'penci_sanitize_textarea_field',
	'label'     => __( 'Add Custom Code After <body> tag', 'soledad' ),
	'id'        => 'penci_custom_code_after_body_tag',
	'type'      => 'soledad-fw-code',
	'code_type' => 'text/html',
);
$options[] = array(
	'label'       => __( 'Disable Essential Meta Tags For Social Media', 'soledad' ),
	'description' => __( 'This feature is automatically disabled when an SEO plugin is installed.', 'soledad' ),
	'id'          => 'penci_disable_og_tag',
	'type'        => 'soledad-fw-toggle',
	'default'     => false,
	'sanitize'    => 'penci_sanitize_checkbox_field'
);

$options[]   = array(
	'default'     => 'full',
	'sanitize'    => 'penci_sanitize_choices_field',
	'label'       => __( 'Custom Image Size for OG Tags', 'soledad' ),
	'id'          => 'penci_og_tag_image_size',
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
return $options;
