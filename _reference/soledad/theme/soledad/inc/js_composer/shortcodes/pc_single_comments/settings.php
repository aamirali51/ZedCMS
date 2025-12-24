<?php
$group_icon  = 'Icon';
$group_color = 'Typo & Color';

vc_map( array(
	'base'          => "pc_single_comments",
	'icon'          => PENCI_SOLEDAD_URL . '/images/vc-icon.png',
	'category'      => penci_get_theme_name( 'Post Builder' ),
	'html_template' => PENCI_SOLEDAD_DIR . '/inc/js_composer/shortcodes/pc_single_comments/frontend.php',
	'weight'        => 910,
	'name'          => penci_get_theme_name( 'Penci' ) . ' ' . esc_html__( 'Post Builder - Comments', 'soledad' ),
	'description'   => 'Post Builder - Comments',
	'controls'      => 'full',
	'params'        => array_merge( array(
		array(
			'type'             => 'penci_switch',
			'heading'          => esc_html__( 'Move Comment Form to Above the List Comments', 'soledad' ),
			'param_name'       => 'penci_post_move_comment_box',
			'value'            => 'no',
			'edit_field_class' => 'vc_col-sm-6'
		),
		array(
			'type'             => 'penci_switch',
			'heading'          => esc_html__( 'Hide Comment Date', 'soledad' ),
			'param_name'       => 'hcomment_date',
			'value'            => 'no',
			'edit_field_class' => 'vc_col-sm-6'
		),
		array(
			'type'             => 'penci_switch',
			'heading'          => esc_html__( 'Remove Lines on The Block Heading', 'soledad' ),
			'param_name'       => 'comment_rheading_lcolor',
			'value'            => 'no',
			'edit_field_class' => 'vc_col-sm-6'
		),
		array(
			'type'             => 'penci_switch',
			'heading'          => esc_html__( 'Hide "Name" field on Comment Form', 'soledad' ),
			'param_name'       => 'penci_single_comments_remove_name',
			'value'            => 'no',
			'edit_field_class' => 'vc_col-sm-6'
		),
		array(
			'type'             => 'penci_switch',
			'heading'          => esc_html__( 'Hide "Email" field on Comment Form', 'soledad' ),
			'param_name'       => 'penci_single_comments_remove_email',
			'value'            => 'no',
			'edit_field_class' => 'vc_col-sm-6'
		),
		array(
			'type'             => 'penci_switch',
			'heading'          => esc_html__( 'Hide "Website" field on Comment Form', 'soledad' ),
			'param_name'       => 'penci_single_comments_remove_website',
			'value'            => 'no',
			'edit_field_class' => 'vc_col-sm-6'
		),
		array(
			'type'             => 'penci_switch',
			'heading'          => esc_html__( 'Remove checkbox "Save my name, email, and website in this browser for the next time I comment."', 'soledad' ),
			'param_name'       => 'penci_single_hide_save_fields',
			'value'            => 'no',
			'edit_field_class' => 'vc_col-sm-6'
		),
		array(
			'type'             => 'penci_switch',
			'heading'          => esc_html__( 'Enable GDPR message on Comment Form', 'soledad' ),
			'param_name'       => 'penci_single_gdpr',
			'value'            => 'no',
			'edit_field_class' => 'vc_col-sm-6'
		),
		array(
			'type'             => 'textfield',
			'heading'          => esc_html__( 'Custom GDPR Message on Comment Form', 'soledad' ),
			'param_name'       => 'penci_single_gdpr_text',
			'edit_field_class' => 'vc_col-sm-6'
		),
		array(
			'type'             => 'dropdown',
			'heading'          => esc_html__( 'Submit Button Align', 'soledad' ),
			'param_name'       => 'submit_btn_align',
			'value'            => array(
				'Left'   => 'left',
				'Center' => 'center',
				'Right'  => 'right',
			),
			'edit_field_class' => 'vc_col-sm-6'
		),
	), array(
		array(
			'type'             => 'textfield',
			'param_name'       => 'heading_cmlists',
			'heading'          => esc_html__( 'Comment Lists', 'soledad' ),
			'value'            => '',
			'group'            => $group_color,
			'edit_field_class' => 'penci-param-heading-wrapper no-top-margin vc_column vc_col-sm-12',
		),
		array(
			'type'             => 'colorpicker',
			'heading'          => esc_html__( 'Heading Title Color', 'soledad' ),
			'param_name'       => 'comment_heading_color',
			'group'            => $group_color,
			'edit_field_class' => 'vc_col-sm-6',
		),
		array(
			'type'             => 'colorpicker',
			'heading'          => esc_html__( 'Heading Title Lines Color', 'soledad' ),
			'param_name'       => 'comment_heading_lcolor',
			'group'            => $group_color,
			'edit_field_class' => 'vc_col-sm-6',
		),
		array(
			'type'             => 'penci_only_number',
			'heading'          => esc_html__( 'Author Avatar Width', 'soledad' ),
			'param_name'       => 'author_ava_width',
			'group'            => $group_color,
			'edit_field_class' => 'vc_col-sm-6',
		),
		array(
			'type'             => 'penci_only_number',
			'heading'          => esc_html__( 'Author Avatar Borders Radius', 'soledad' ),
			'param_name'       => 'author_ava_boderradius',
			'group'            => $group_color,
			'edit_field_class' => 'vc_col-sm-6',
		),
		array(
			'type'             => 'colorpicker',
			'heading'          => esc_html__( 'Author Name Color', 'soledad' ),
			'param_name'       => 'comment_name_color',
			'group'            => $group_color,
			'edit_field_class' => 'vc_col-sm-6',
		),
		array(
			'type'             => 'colorpicker',
			'heading'          => esc_html__( 'Author Name Hover Color', 'soledad' ),
			'param_name'       => 'comment_name_hcolor',
			'group'            => $group_color,
			'edit_field_class' => 'vc_col-sm-6',
		),
		array(
			'type'             => 'colorpicker',
			'heading'          => esc_html__( 'Comment Date Color', 'soledad' ),
			'param_name'       => 'comment_date_color',
			'group'            => $group_color,
			'edit_field_class' => 'vc_col-sm-6',
		),
		array(
			'type'             => 'colorpicker',
			'heading'          => esc_html__( 'Comment Date Icon Color', 'soledad' ),
			'param_name'       => 'comment_date_icolor',
			'group'            => $group_color,
			'edit_field_class' => 'vc_col-sm-6',
		),
		array(
			'type'             => 'colorpicker',
			'heading'          => esc_html__( 'Color for Comment Content', 'soledad' ),
			'param_name'       => 'comment_content_color',
			'group'            => $group_color,
			'edit_field_class' => 'vc_col-sm-6',
		),
		array(
			'type'             => 'colorpicker',
			'heading'          => esc_html__( 'Reply Button Color', 'soledad' ),
			'param_name'       => 'comment_reply_color',
			'group'            => $group_color,
			'edit_field_class' => 'vc_col-sm-6',
		),
		array(
			'type'             => 'colorpicker',
			'heading'          => esc_html__( 'Reply Button Hover Color', 'soledad' ),
			'param_name'       => 'comment_reply_hcolor',
			'group'            => $group_color,
			'edit_field_class' => 'vc_col-sm-6',
		),
		array(
			'type'             => 'colorpicker',
			'heading'          => esc_html__( 'General Borders Color', 'soledad' ),
			'param_name'       => 'comment_item_bcolor',
			'group'            => $group_color,
			'edit_field_class' => 'vc_col-sm-6',
		),
		array(
			'type'             => 'penci_only_number',
			'heading'          => esc_html__( 'Comments List Spacing Top', 'soledad' ),
			'param_name'       => 'comment_list_spacingt',
			'group'            => $group_color,
			'edit_field_class' => 'vc_col-sm-6',
		),
		array(
			'type'             => 'textfield',
			'param_name'       => 'heading_cmform',
			'heading'          => esc_html__( 'Comment Form', 'soledad' ),
			'value'            => '',
			'group'            => $group_color,
			'edit_field_class' => 'penci-param-heading-wrapper no-top-margin vc_column vc_col-sm-12',
		),
		array(
			'type'             => 'colorpicker',
			'heading'          => esc_html__( 'Heading Title Color', 'soledad' ),
			'param_name'       => 'commentf_heading_color',
			'group'            => $group_color,
			'edit_field_class' => 'vc_col-sm-6',
		),
		array(
			'type'             => 'colorpicker',
			'heading'          => esc_html__( 'Heading Title Line Color', 'soledad' ),
			'param_name'       => 'commentf_heading_lcolor',
			'group'            => $group_color,
			'edit_field_class' => 'vc_col-sm-6',
		),
		array(
			'type'             => 'colorpicker',
			'heading'          => esc_html__( 'Input Text Color', 'soledad' ),
			'param_name'       => 'commentf_input_text_color',
			'group'            => $group_color,
			'edit_field_class' => 'vc_col-sm-6',
		),
		array(
			'type'             => 'colorpicker',
			'heading'          => esc_html__( 'Input Border Color', 'soledad' ),
			'param_name'       => 'commentf_input_border_color',
			'group'            => $group_color,
			'edit_field_class' => 'vc_col-sm-6',
		),
		array(
			'type'             => 'colorpicker',
			'heading'          => esc_html__( 'Submit Background Color', 'soledad' ),
			'param_name'       => 'commentf_submit_bg_color',
			'group'            => $group_color,
			'edit_field_class' => 'vc_col-sm-6',
		),
		array(
			'type'             => 'colorpicker',
			'heading'          => esc_html__( 'Submit Background Hover Color', 'soledad' ),
			'param_name'       => 'commentf_submit_bg_hcolor',
			'group'            => $group_color,
			'edit_field_class' => 'vc_col-sm-6',
		),
		array(
			'type'             => 'colorpicker',
			'heading'          => esc_html__( 'Submit Text Color', 'soledad' ),
			'param_name'       => 'commentf_submit_txt_color',
			'group'            => $group_color,
			'edit_field_class' => 'vc_col-sm-6',
		),
		array(
			'type'             => 'colorpicker',
			'heading'          => esc_html__( 'Submit Text Hover Color', 'soledad' ),
			'param_name'       => 'commentf_submit_txt_hcolor',
			'group'            => $group_color,
			'edit_field_class' => 'vc_col-sm-6',
		),
		array(
			'type'             => 'colorpicker',
			'heading'          => esc_html__( 'Cookie Save Notice Color', 'soledad' ),
			'param_name'       => 'commentf_cookies_notice_color',
			'group'            => $group_color,
			'edit_field_class' => 'vc_col-sm-6',
		),
		array(
			'type'             => 'colorpicker',
			'heading'          => esc_html__( 'GDPR Message Color', 'soledad' ),
			'param_name'       => 'commentf_gdpr_text_color',
			'group'            => $group_color,
			'edit_field_class' => 'vc_col-sm-6',
		),
	), Penci_Vc_Params_Helper::extra_params() )
) );
