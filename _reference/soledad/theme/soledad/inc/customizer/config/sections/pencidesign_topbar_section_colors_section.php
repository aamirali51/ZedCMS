<?php
$options   = [];
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Top Bar Background Color', 'soledad' ),
	'id'       => 'penci_top_bar_bg',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( '"Current Date/Custom Text" Color', 'soledad' ),
	'id'       => 'penci_topbar_ct_color',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( '"Top Posts" Background Color', 'soledad' ),
	'id'       => 'penci_top_bar_top_posts_bg',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( '"Top Posts" Text Color', 'soledad' ),
	'id'       => 'penci_top_bar_top_posts_color',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Next/Prev Posts Top Bar Button Color', 'soledad' ),
	'id'       => 'penci_top_bar_button_color',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Next/Prev Posts Top Bar Button Hover Color', 'soledad' ),
	'id'       => 'penci_top_bar_button_hover_color',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Top Bar Posts Title Color', 'soledad' ),
	'id'       => 'penci_top_bar_title_color',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Top Bar Post Titles Hover Color', 'soledad' ),
	'id'       => 'penci_top_bar_title_hover_color',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Top Bar Menu Text Color', 'soledad' ),
	'id'       => 'penci_top_bar_menu_color',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Top Bar Menu Text Hover Color', 'soledad' ),
	'id'       => 'penci_top_bar_menu_hover_color',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Top Bar Menu Border Color', 'soledad' ),
	'id'       => 'penci_top_bar_menu_border',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Top Bar Menu Dropdown Background Color', 'soledad' ),
	'id'       => 'penci_top_bar_menu_dropdown_bg',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Top Bar Social Icons Color', 'soledad' ),
	'id'       => 'penci_top_bar_social_color',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Top Bar Social Icons Hover Color', 'soledad' ),
	'id'       => 'penci_top_bar_social_hover_color',
);
$options[] = array(
	'sanitize' => 'sanitize_text_field',
	'label'    => esc_html__( 'Login/Register Popup', 'soledad' ),
	'id'       => 'penci_lgpop_form_cheading',
	'type'     => 'soledad-fw-header',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Top Bar Login Icon & Text Color', 'soledad' ),
	'id'       => 'penci_tblgc_icon_text',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Top Bar Login Icon & Text Hover Color', 'soledad' ),
	'id'       => 'penci_tblgc_icon_htext',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Color for Popup Loading Icon', 'soledad' ),
	'id'       => 'penci_tblgpop_cloading',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Background Color for Popup Form', 'soledad' ),
	'id'       => 'penci_tblgpop_bg',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Second Background Color for Popup Form ( for Gradient Background )', 'soledad' ),
	'id'       => 'penci_tblgpop_sbg',
);
$options[] = array(
	'default'  => '0.75',
	'sanitize' => 'penci_sanitize_choices_field',
	'label'    => __( 'Background Color Opacity for Popup Form', 'soledad' ),
	'id'       => 'penci_tblgpop_bg_opacity',
	'type'     => 'soledad-fw-select',
	'choices'  => array(
		'0'    => '0',
		'0.05' => '0.05',
		'0.1'  => '0.1',
		'0.15' => '0.15',
		'0.2'  => '0.2',
		'0.25' => '0.25',
		'0.3'  => '0.3',
		'0.35' => '0.35',
		'0.4'  => '0.4',
		'0.45' => '0.45',
		'0.5'  => '0.5',
		'0.55' => '0.55',
		'0.6'  => '0.6',
		'0.65' => '0.65',
		'0.7'  => '0.7',
		'0.75' => '0.75',
		'0.8'  => '0.8',
		'0.85' => '0.85',
		'0.9'  => '0.9',
		'0.95' => '0.95',
		'1'    => '1',
	)
);
$options[] = array(
	'sanitize' => 'esc_url_raw',
	'label'    => __( 'Background Image for Popup Form', 'soledad' ),
	'type'     => 'soledad-fw-image',
	'id'       => 'penci_tblgpop_bgimgage',
);
$options[] = array(
	'default'  => 'no-repeat',
	'sanitize' => 'penci_sanitize_choices_field',
	'label'    => __( 'Background Image Repeat for Popup Form', 'soledad' ),
	'id'       => 'penci_tblgpop_bg_repeat',
	'type'     => 'soledad-fw-select',
	'choices'  => array(
		'no-repeat' => __('No Repeat','soledad' ),
		'repeat'    => __('Repeat','soledad' ),
	)
);
$options[] = array(
	'default'  => 'fixed',
	'sanitize' => 'penci_sanitize_choices_field',
	'label'    => __( 'Background Image Attachment for Popup Form', 'soledad' ),
	'id'       => 'penci_tblgpop_bg_attachment',
	'type'     => 'soledad-fw-select',
	'choices'  => array(
		'fixed'  => __('Fixed','soledad' ),
		'scroll' => __('Scroll','soledad' ),
		'local'  => __('Local','soledad' ),
	)
);
$options[] = array(
	'default'  => 'auto',
	'sanitize' => 'penci_sanitize_choices_field',
	'label'    => __( 'Background Image Size for Popup Form', 'soledad' ),
	'id'       => 'penci_tblgpop_bg_size',
	'type'     => 'soledad-fw-select',
	'choices'  => array(
		'auto'    => __('Auto','soledad' ),
		'cover'   => __('Cover','soledad' ),
		'contain' => __('Contain','soledad' ),
	)
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Color for Close Button on Popup Form', 'soledad' ),
	'id'       => 'penci_tblgc_close',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Color for Titles on Popup Form', 'soledad' ),
	'id'       => 'penci_tblgc_titles',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Text Color for Input Fields on Popup Form', 'soledad' ),
	'id'       => 'penci_tblgc_inputs',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Borders Color for Input Fields on Popup Form', 'soledad' ),
	'id'       => 'penci_tblgc_inputs_borders',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Color for Submit Buttons on Popup Form', 'soledad' ),
	'id'       => 'penci_tblgc_submit',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Background Color for Submit Buttons on Popup Form', 'soledad' ),
	'id'       => 'penci_tblgc_submit_bg',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Hover Color for Submit Buttons on Popup Form', 'soledad' ),
	'id'       => 'penci_tblgc_hsubmit',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Hover Background Color for Submit Buttons on Popup Form', 'soledad' ),
	'id'       => 'penci_tblgc_submit_hbg',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Text Color on Popup Form', 'soledad' ),
	'id'       => 'penci_tblgc_text',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Links Color on Popup Form', 'soledad' ),
	'id'       => 'penci_tblgc_links',
);

return $options;
