<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_filter( 'penci_meta_boxes', 'penci_page_meta_box' );
function penci_page_meta_box( $meta_boxes ) {

	$tabs = array(
		'page_general'    => array(
			'label' => esc_html__( 'General', 'soledad' ),
			'icon'  => 'dashicons dashicons-admin-site',
		),
		'page_header'     => array(
			'label' => esc_html__( 'Header', 'soledad' ),
			'icon'  => 'dashicons dashicons-media-text',
		),
		'page_footer'     => array(
			'label' => esc_html__( 'Footer', 'soledad' ),
			'icon'  => 'dashicons dashicons-media-text',
		),
		'page_title'      => array(
			'label' => esc_html__( 'Page Header', 'soledad' ),
			'icon'  => 'dashicons dashicons-media-text',
		),
		'page_background' => array(
			'label' => esc_html__( 'Background', 'soledad' ),
			'icon'  => 'dashicons dashicons-media-text',
		),
		'page_custom_css' => array(
			'label' => esc_html__( 'Custom CSS', 'soledad' ),
			'icon'  => 'dashicons dashicons-media-text',
		),
	);

	$header_layout = [];
	$footer_layout = [];

	$header_layout[''] = esc_attr__( 'Default Customizer Settings' );
	$footer_layout[''] = esc_attr__( 'Default Customizer Settings' );

	$header_layouts = get_posts( [
		'post_type'      => 'penci_builder',
		'posts_per_page' => - 1,
	] );
	foreach ( $header_layouts as $header ) {
		$header_layout[ $header->ID ] = $header->post_title;
	}

	$footer_layouts = get_posts( [
		'post_type'      => 'penci-block',
		'posts_per_page' => - 1,
	] );
	foreach ( $footer_layouts as $footer ) {
		$footer_layout[ $footer->ID ] = $footer->post_title;
	}

	$fields = array(
		array(
			'tab'  => 'page_general',
			'id'   => 'penci_page_style',
			'name' => esc_html__( 'Page Template', 'soledad' ),
			'type' => 'tab_general_options',
		),

		// Hide footer and header
		array(
			'tab'     => 'page_header',
			'id'      => 'header_builder_layout',
			'name'    => esc_html__( 'Header Builder Layout', 'soledad' ),
			'type'    => 'select',
			'std'     => '',
			'options' => $header_layout,
			'desc'    => esc_html__( 'Override header builder layout for this page.', 'soledad' ),
		),
		
		array(
			'tab'     => 'page_header',
			'id'      => 'header_block_layout',
			'name'    => esc_html__( 'Header Block', 'soledad' ),
			'type'    => 'select',
			'std'     => '',
			'options' => $footer_layout,
			'desc'    => esc_html__( 'Use the Penci Block as Header Template for this page.', 'soledad' ),
		),

		array(
			'tab'     => 'page_header',
			'id'      => 'header_style',
			'name'    => esc_html__( 'Header Style', 'soledad' ),
			'type'    => 'select',
			'std'     => '',
			'options' => array(
				''          => esc_html__( 'Default Value ( on Customize )', 'soledad' ),
				'header-1'  => esc_html__( 'Header 1', 'soledad' ),
				'header-2'  => esc_html__( 'Header 2', 'soledad' ),
				'header-3'  => esc_html__( 'Header 3', 'soledad' ),
				'header-4'  => esc_html__( 'Header 4 ( Centered )', 'soledad' ),
				'header-5'  => esc_html__( 'Header 5 ( Centered )', 'soledad' ),
				'header-6'  => esc_html__( 'Header 6', 'soledad' ),
				'header-7'  => esc_html__( 'Header 7', 'soledad' ),
				'header-8'  => esc_html__( 'Header 8', 'soledad' ),
				'header-9'  => esc_html__( 'Header 9', 'soledad' ),
				'header-10' => esc_html__( 'Header 10', 'soledad' ),
				'header-11' => esc_html__( 'Header 11', 'soledad' ),
			),
			'desc'    => esc_html__( 'Override header style for this page.', 'soledad' ),
		),
		array(
			'id'      => 'penci_header_width',
			'name'    => esc_html__( 'Custom Header Container Width', 'soledad' ),
			'type'    => 'select',
			'options' => array(
				''          => esc_html__( 'Default( follow Customize )', 'soledad' ),
				'1170'      => esc_html__( 'Width: 1170px', 'soledad' ),
				'1400'      => esc_html__( 'Width: 1400px', 'soledad' ),
				'fullwidth' => esc_html__( 'FullWidth', 'soledad' ),
			),
			'tab'     => 'page_header',
			'desc'    => esc_html__( 'Replace & change header with for this page.', 'soledad' ),
		),
		array(
			'id'   => 'penci_mainmenu_height',
			'type' => 'number',
			'name' => esc_html__( 'Custom Main Nav Height( min 30px )', 'soledad' ),
			'min'  => '1',
			'max'  => '500',
			'tab'  => 'page_header',
		),
		array(
			'id'   => 'penci_mainmenu_height_sticky',
			'type' => 'number',
			'name' => esc_html__( 'Custom Main Nav Height when Sticky Header( min 30px )', 'soledad' ),
			'min'  => '1',
			'max'  => '500',
			'tab'  => 'page_header',
		),
		array(
			'id'      => 'topbar_menu',
			'name'    => esc_html__( 'Custom TopBar Menu', 'soledad' ),
			'type'    => 'select',
			'options' => penci_get_option_menus(),
			'tab'     => 'page_header',
			'desc'    => esc_html__( 'Replace & change Topbar Menu for this page.', 'soledad' ),
		),
		array(
			'id'      => 'main_nav_menu',
			'name'    => esc_html__( 'Custom Primary Menu', 'soledad' ),
			'type'    => 'select',
			'options' => penci_get_option_menus(),
			'tab'     => 'page_header',
			'desc'    => esc_html__( 'Replace & change Primary Menu for this page.', 'soledad' ),
		),
		array(
			'id'   => 'custom_logo',
			'name' => esc_html__( 'Custom Logo Image', 'soledad' ),
			'type' => 'image',
			'desc' => esc_html__( 'You can override default site logo for this page.', 'soledad' ),
			'tab'  => 'page_header',
		),
		array(
			'id'   => 'header_bgcolor',
			'name' => esc_html__( 'Header Background Color', 'soledad' ),
			'desc' => esc_html__( 'You can change header background color with this option.', 'soledad' ),
			'type' => 'color',
			'tab'  => 'page_header',
		),
		array(
			'id'   => 'header_bgimg',
			'name' => esc_html__( 'Header Background Image', 'soledad' ),
			'type' => 'image',
			'desc' => esc_html__( 'You can change header background image color with this option. You should use image with minimum width 1920px and minimum height 300px', 'soledad' ),
			'tab'  => 'page_header',
		),

		array(
			'id'   => 'main_bar_bg',
			'name' => esc_html__( 'Main Bar Background Color', 'soledad' ),
			'desc' => esc_html__( 'You can change main nav background color with this option.', 'soledad' ),
			'type' => 'color',
			'tab'  => 'page_header',
		),
		array(
			'id'   => 'main_bar_bgimg',
			'name' => esc_html__( 'Main Bar Background Image', 'soledad' ),
			'type' => 'image',
			'desc' => esc_html__( 'You can change main bar background image color with this option.', 'soledad' ),
			'tab'  => 'page_header',
		),
		array(
			'id'      => 'penci_edeader_trans',
			'name'    => esc_html__( 'Enable Header Transparent', 'soledad' ),
			'type'    => 'select',
			'options' => array(
				''    => esc_html__( 'Default', 'soledad' ),
				'no'  => esc_html__( 'No', 'soledad' ),
				'yes' => esc_html__( 'Yes', 'soledad' )
			),
			'std'     => '',
			'tab'     => 'page_header',
		),
		array(
			'id'   => 'hlogo_trans',
			'name' => esc_html__( 'Upload Logo for Transparent Header style 6, 9, 10 & 11', 'soledad' ),
			'type' => 'image',
			'desc' => esc_html__( 'Important Note: This option apply when you use transparent header only', 'soledad' ),
			'tab'  => 'page_header',
		),
		array(
			'id'    => 'tran_slogan_color',
			'name'  => esc_html__( 'Header Slogan Text Color', 'soledad' ),
			'type'  => 'color',
			'tab'   => 'page_header',
			'style' => 'penci-col-6'
		),
		array(
			'id'    => 'tran_slogan_line_color',
			'name'  => esc_html__( 'Header Slogan Line Color', 'soledad' ),
			'type'  => 'color',
			'tab'   => 'page_header',
			'style' => 'penci-col-6'
		),
		array(
			'id'    => 'tran_social_color',
			'name'  => esc_html__( 'Header Social Icons Color', 'soledad' ),
			'type'  => 'color',
			'tab'   => 'page_header',
			'style' => 'penci-col-6'
		),
		array(
			'id'    => 'tran_social_color_hover',
			'name'  => esc_html__( 'Header Social Icons Color Hover', 'soledad' ),
			'type'  => 'color',
			'tab'   => 'page_header',
			'style' => 'penci-col-6'
		),
		array(
			'id'    => 'tran_main_bar_nav_color',
			'name'  => esc_html__( 'Main Bar Menu Text Color', 'soledad' ),
			'type'  => 'color',
			'tab'   => 'page_header',
			'style' => 'penci-col-6'
		),
		array(
			'id'    => 'tran_bar_color_active',
			'name'  => esc_html__( 'Main Bar Menu Text Hover & Active Color', 'soledad' ),
			'type'  => 'color',
			'tab'   => 'page_header',
			'style' => 'penci-col-6'
		),
		array(
			'id'    => 'tran_main_bar_padding_color',
			'name'  => esc_html__( 'Main Bar Padding Menu Items Background Color', 'soledad' ),
			'type'  => 'color',
			'tab'   => 'page_header',
			'style' => 'penci-col-6'
		),
		array(
			'id'    => 'tran_main_bar_search_magnify',
			'name'  => esc_html__( 'Main Bar Search Icon Color', 'soledad' ),
			'type'  => 'color',
			'tab'   => 'page_header',
			'style' => 'penci-col-6'
		),
		array(
			'id'    => 'tran_main_bar_close_color',
			'name'  => esc_html__( 'Main Bar Icon Close Search Color', 'soledad' ),
			'type'  => 'color',
			'tab'   => 'page_header',
			'style' => 'penci-col-6'
		),

		// Footer
		array(
			'tab'     => 'page_footer',
			'id'      => 'footer_builder_layout',
			'name'    => esc_html__( 'Footer Builder Layout', 'soledad' ),
			'type'    => 'select',
			'std'     => '',
			'options' => $footer_layout,
			'desc'    => esc_html__( 'Override footer builder layout for this page.', 'soledad' ),
		),
		array(
			'id'      => 'penci_hide_fwidget',
			'name'    => esc_html__( 'Disable Footer Widget Area', 'soledad' ),
			'type'    => 'select',
			'options' => array(
				''    => esc_html__( 'Default', 'soledad' ),
				'no'  => esc_html__( 'No', 'soledad' ),
				'yes' => esc_html__( 'Yes', 'soledad' )
			),
			'std'     => '',
			'tab'     => 'page_footer',
		),
		array(
			'id'      => 'penci_footer_width',
			'name'    => esc_html__( 'Footer Container Width', 'soledad' ),
			'type'    => 'select',
			'options' => array(
				''          => esc_html__( 'Default( follow Customize )', 'soledad' ),
				'1170'      => esc_html__( 'Width: 1170px', 'soledad' ),
				'1400'      => esc_html__( 'Width: 1400px', 'soledad' ),
				'fullwidth' => esc_html__( 'FullWidth', 'soledad' ),
			),
			'std'     => '',
			'tab'     => 'page_footer',
		),
		array(
			'id'   => 'penci_fw_padding_top_bottom',
			'type' => 'number',
			'name' => esc_html__( 'Footer Widget Area Padding Top & Bottom', 'soledad' ),
			'desc' => esc_html__( 'Numeric value only, unit is pixel', 'soledad' ),
			'min'  => 1,
			'step' => 1,
			'max'  => 200,
			'tab'  => 'page_footer',
		),
		array(
			'tab'     => 'page_footer',
			'id'      => 'penci_footer_style',
			'name'    => esc_html__( 'Footer Widget Area Columns Layout', 'soledad' ),
			'type'    => 'select',
			'std'     => '',
			'options' => array(
				''         => esc_html__( 'Default', 'soledad' ),
				'style-1'  => '1/3 + 1/3 + 1/3',
				'style-2'  => '1/3 + 2/3',
				'style-3'  => '2/3 + 1/3',
				'style-4'  => '1/4 + 1/4 + 1/4 + 1/4',
				'style-5'  => '2/4 + 1/4 + 1/4',
				'style-6'  => '1/4 + 2/4 + 1/4',
				'style-7'  => '1/4 + 1/4 + 2/4',
				'style-8'  => '1/4 + 3/4',
				'style-9'  => '3/4 + 1/4',
				'style-10' => '1/2 + 1/2',
			),
			'desc'    => esc_html__( 'Override footer layout for this page.', 'soledad' ),
		),
		// Page header
		array(
			'name'    => esc_html__( 'Enable/Disable Page Header', 'soledad' ),
			'id'      => "pheader_show",
			'type'    => 'select',
			'options' => array(
				''        => esc_html__( 'Default', 'soledad' ),
				'enable'  => esc_html__( 'Enable', 'soledad' ),
				'disable' => esc_html__( 'Disable', 'soledad' )
			),
			'tab'     => 'page_title',
		),
		array(
			'name'    => esc_html__( 'Hide/Show Line Below Title', 'soledad' ),
			'id'      => 'pheader_hideline',
			'type'    => 'select',
			'options' => array(
				''     => esc_html__( 'Default', 'soledad' ),
				'hide' => esc_html__( 'Hide', 'soledad' ),
				'show' => esc_html__( 'Show', 'soledad' ),
			),
			'tab'     => 'page_title',
			'style'   => 'penci-col-6'
		),
		array(
			'name'    => esc_html__( 'Hide/Show Breadcrumbs', 'soledad' ),
			'id'      => 'pheader_hidebead',
			'type'    => 'select',
			'options' => array(
				''     => esc_html__( 'Default', 'soledad' ),
				'hide' => esc_html__( 'Hide', 'soledad' ),
				'show' => esc_html__( 'Show', 'soledad' ),
			),
			'tab'     => 'page_title',
			'style'   => 'penci-col-6'
		),
		array(
			'name'    => esc_html__( 'Text Align', 'soledad' ),
			'id'      => 'pheader_align',
			'type'    => 'select',
			'options' => array(
				''       => esc_html__( 'Default', 'soledad' ),
				'left'   => esc_html__( 'Left', 'soledad' ),
				'center' => esc_html__( 'Center', 'soledad' ),
				'right'  => esc_html__( 'Right', 'soledad' )
			),
			'tab'     => 'page_title',
			'style'   => 'penci-col-6'
		),
		array(
			'id'    => 'pheader_width',
			'type'  => 'number',
			'name'  => esc_html__( 'Custom Container Width for Page Header', 'soledad' ),
			'desc'  => esc_html__( 'Numeric value only, unit is pixel', 'soledad' ),
			'min'   => '1',
			'max'   => '2000',
			'tab'   => 'page_title',
			'style' => 'penci-col-6'
		),
		array(
			'id'    => 'pheader_ptop',
			'type'  => 'number',
			'name'  => esc_html__( 'Padding top', 'soledad' ),
			'desc'  => esc_html__( 'Numeric value only, unit is pixel', 'soledad' ),
			'min'   => '1',
			'max'   => '100',
			'tab'   => 'page_title',
			'style' => 'penci-col-6'
		),

		array(
			'id'    => 'pheader_pbottom',
			'type'  => 'number',
			'name'  => esc_html__( 'Padding bottom', 'soledad' ),
			'desc'  => esc_html__( 'Numeric value only, unit is pixel', 'soledad' ),
			'min'   => '1',
			'max'   => '100',
			'tab'   => 'page_title',
			'style' => 'penci-col-6'
		),
		array(
			'name'    => esc_html__( 'On/Off Uppercase for Title', 'soledad' ),
			'id'      => 'pheader_turn_offup',
			'type'    => 'select',
			'options' => array(
				''    => esc_html__( 'Default', 'soledad' ),
				'on'  => esc_html__( 'On', 'soledad' ),
				'off' => esc_html__( 'Off', 'soledad' ),
			),
			'tab'     => 'page_title',
			'style'   => 'penci-col-6'
		),
		array(
			'name'    => esc_html__( 'Font Weight For Title', 'soledad' ),
			'id'      => 'pheader_fwtitle',
			'type'    => 'select',
			'options' => array(
				''        => esc_html__( 'Default', 'soledad' ),
				'normal'  => 'Normal',
				'bold'    => 'Bold',
				'bolder'  => 'Bolder',
				'lighter' => 'Lighter',
				'100'     => '100',
				'200'     => '200',
				'300'     => '300',
				'400'     => '400',
				'500'     => '500',
				'600'     => '600',
				'700'     => '700',
				'800'     => '800',
				'900'     => '900'
			),
			'tab'     => 'page_title',
			'style'   => 'penci-col-6'
		),
		array(
			'id'    => 'pheader_title_pbottom',
			'type'  => 'number',
			'name'  => esc_html__( 'Custom Padding Bottom for Title', 'soledad' ),
			'desc'  => esc_html__( 'Numeric value only, unit is pixel', 'soledad' ),
			'min'   => '1',
			'max'   => '100',
			'tab'   => 'page_title',
			'style' => 'penci-col-6'
		),
		array(
			'id'    => 'pheader_title_mbottom',
			'type'  => 'number',
			'name'  => esc_html__( 'Custom Margin Bottom for Title', 'soledad' ),
			'desc'  => esc_html__( 'Numeric value only, unit is pixel', 'soledad' ),
			'min'   => '1',
			'max'   => '100',
			'tab'   => 'page_title',
			'style' => 'penci-col-6'
		),
		array(
			'id'    => 'pheader_title_fsize',
			'type'  => 'number',
			'name'  => esc_html__( 'Custom size for Title', 'soledad' ),
			'desc'  => esc_html__( 'Numeric value only, unit is pixel', 'soledad' ),
			'min'   => '1',
			'max'   => '100',
			'tab'   => 'page_title',
			'style' => 'penci-col-6'
		),
		array(
			'id'   => 'pheader_bread_fsize',
			'type' => 'number',
			'name' => esc_html__( 'Custom size for Breadcrumb', 'soledad' ),
			'desc' => esc_html__( 'Numeric value only, unit is pixel', 'soledad' ),
			'min'  => '1',
			'max'  => '100',
			'tab'  => 'page_title',
		),
		array(
			'id'    => 'pheader_bgimg',
			'type'  => 'image',
			'name'  => esc_html__( 'Background Image', 'soledad' ),
			'tab'   => 'page_title',
			'style' => 'penci-col-6'

		),
		array(
			'id'    => 'pheader_bgcolor',
			'type'  => 'color',
			'name'  => esc_html__( 'Background Color', 'soledad' ),
			'tab'   => 'page_title',
			'style' => 'penci-col-6'
		),
		array(
			'id'    => 'pheader_title_color',
			'type'  => 'color',
			'name'  => esc_html__( 'Title Color', 'soledad' ),
			'tab'   => 'page_title',
			'style' => 'penci-col-6'
		),
		array(
			'id'    => 'pheader_line_color',
			'type'  => 'color',
			'name'  => esc_html__( 'Line Color', 'soledad' ),
			'tab'   => 'page_title',
			'style' => 'penci-col-6'
		),
		array(
			'id'    => 'pheader_bread_color',
			'type'  => 'color',
			'name'  => esc_html__( 'Breadcrumbs Text Color', 'soledad' ),
			'tab'   => 'page_title',
			'style' => 'penci-col-6'
		),
		array(
			'id'    => 'pheader_bread_hcolor',
			'type'  => 'color',
			'name'  => esc_html__( 'Breadcrumbs Hover Text Color', 'soledad' ),
			'tab'   => 'page_title',
			'style' => 'penci-col-6'
		),
		// Background
		array(
			'id'   => 'page_wrap_bgcolor',
			'type' => 'color',
			'name' => esc_html__( 'Background Color', 'soledad' ),
			'tab'  => 'page_background',
		),
		array(
			'id'   => 'page_wrap_bgimg',
			'type' => 'image',
			'name' => esc_html__( 'Background Image', 'soledad' ),
			'tab'  => 'page_background',
		),
		array(
			'name'    => esc_html__( 'Background Position', 'soledad' ),
			'id'      => 'page_wrap_bg_pos',
			'type'    => 'select',
			'options' => array(
				'center'        => esc_html__( 'Center', 'soledad' ),
				'left_top'      => esc_html__( 'Left Top', 'soledad' ),
				'left_center'   => esc_html__( 'Left Center', 'soledad' ),
				'left_bottom'   => esc_html__( 'Left Bottom', 'soledad' ),
				'right_top'     => esc_html__( 'Right Top', 'soledad' ),
				'right_center'  => esc_html__( 'Right Center', 'soledad' ),
				'right_bottom'  => esc_html__( 'Right Bottom', 'soledad' ),
				'center_top'    => esc_html__( 'Center Top', 'soledad' ),
				'center_bottom' => esc_html__( 'Center Bottom', 'soledad' ),
			),
			'std'     => 'center',
			'tab'     => 'page_background',
			'style'   => 'penci-col-6'
		),
		array(
			'name'    => esc_html__( 'Background Size', 'soledad' ),
			'id'      => 'page_wrap_bg_size',
			'type'    => 'select',
			'std'     => 'cover',
			'options' => array(
				'cover'   => esc_html__( 'Cover', 'soledad' ),
				'auto'    => esc_html__( 'Auto', 'soledad' ),
				'contain' => esc_html__( 'Contain', 'soledad' ),
			),
			'tab'     => 'page_background',
			'style'   => 'penci-col-6'
		),
		array(
			'name'    => esc_html__( 'Background Repeat', 'soledad' ),
			'id'      => 'page_wrap_bg_repeat',
			'type'    => 'select',
			'std'     => 'no-repeat',
			'options' => array(
				'repeat'    => esc_html__( 'Repeat', 'soledad' ),
				'no-repeat' => esc_html__( 'No repeat', 'soledad' ),
			),
			'tab'     => 'page_background',
			'style'   => 'penci-col-6'
		),

		// Custom css

		array(
			'name'        => esc_html__( 'Custom CSS Code', 'soledad' ),
			'id'          => 'page_custom_css',
			'type'        => 'textarea',
			'tab'         => 'page_custom_css',
			'placeholder' => '.class{ color: #fff; }',
			'desc'        => __( 'Enter your CSS code. In some case, the <code>!important</code> tag may be needed', 'soledad' ),
		),
	);

	$meta_boxes[] = array(
		'id'         => 'penci-metabox-page',
		'title'      => esc_html__( 'Page Options', 'soledad' ),
		'post_types' => array( 'page' ),
		'context'    => 'advanced',
		'priority'   => 'default',
		'autosave'   => 'false',
		'tabs'       => apply_filters( 'penci_page_meta_box_tabs', $tabs ),
		'fields'     => apply_filters( 'penci_page_meta_box_fields', $fields ),
	);

	return $meta_boxes;
}
