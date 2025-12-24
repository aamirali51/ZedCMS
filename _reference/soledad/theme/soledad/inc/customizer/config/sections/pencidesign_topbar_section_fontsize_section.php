<?php
$options   = [];
$options[] = array(
	'default'  => '13',
	'sanitize' => 'absint',
	'label'    => __( 'Font Size for General Text on Topbar', 'soledad' ),
	'type'     => 'soledad-fw-size',
	'id'       => 'penci_topbar_ctsize',
	'ids'      => array(
		'desktop' => 'penci_topbar_ctsize',
	),
	'choices'  => array(
		'desktop' => array(
			'min'     => 1,
			'max'     => 100,
			'step'    => 1,
			'edit'    => true,
			'unit'    => 'px',
			'default' => '13',
		),
	),
);
$options[] = array(
	'default'  => '12',
	'sanitize' => 'absint',
	'type'     => 'soledad-fw-size',
	'label'    => __( 'Font Size for "Top Posts" text', 'soledad' ),
	'id'       => 'penci_top_bar_top_post_size',
	'ids'      => array(
		'desktop' => 'penci_top_bar_top_post_size',
	),
	'choices'  => array(
		'desktop' => array(
			'min'     => 1,
			'max'     => 100,
			'step'    => 1,
			'edit'    => true,
			'unit'    => 'px',
			'default' => '12',
		),
	),
);
$options[] = array(
	'default'  => '12',
	'sanitize' => 'absint',
	'type'     => 'soledad-fw-size',
	'label'    => __( 'Font Size for Post Titles on "Top Posts"', 'soledad' ),
	'id'       => 'penci_top_bar_top_post_size_title',
	'ids'      => array(
		'desktop' => 'penci_top_bar_top_post_size_title',
	),
	'choices'  => array(
		'desktop' => array(
			'min'     => 1,
			'max'     => 100,
			'step'    => 1,
			'edit'    => true,
			'unit'    => 'px',
			'default' => '12',
		),
	),
);
$options[] = array(
	'default'  => '11',
	'sanitize' => 'absint',
	'type'     => 'soledad-fw-size',
	'label'    => __( 'Font Size for Topbar Menu Level 1', 'soledad' ),
	'id'       => 'penci_top_bar_menu_level_one',
	'ids'      => array(
		'desktop' => 'penci_top_bar_menu_level_one',
	),
	'choices'  => array(
		'desktop' => array(
			'min'     => 1,
			'max'     => 100,
			'step'    => 1,
			'edit'    => true,
			'unit'    => 'px',
			'default' => '11',
		),
	),
);
$options[] = array(
	'default'  => '11',
	'sanitize' => 'absint',
	'type'     => 'soledad-fw-size',
	'label'    => __( 'Font Size for Sub-menu on Topbar Menu', 'soledad' ),
	'id'       => 'penci_top_bar_sub_menu_size',
	'ids'      => array(
		'desktop' => 'penci_top_bar_sub_menu_size',
	),
	'choices'  => array(
		'desktop' => array(
			'min'     => 1,
			'max'     => 100,
			'step'    => 1,
			'edit'    => true,
			'unit'    => 'px',
			'default' => '11',
		),
	),
);
$options[] = array(
	'default'  => '13',
	'sanitize' => 'absint',
	'type'     => 'soledad-fw-size',
	'label'    => __( 'Font Size for Social Icons on Topbar', 'soledad' ),
	'id'       => 'penci_top_bar_social_size',
	'ids'      => array(
		'desktop' => 'penci_top_bar_social_size',
	),
	'choices'  => array(
		'desktop' => array(
			'min'     => 1,
			'max'     => 100,
			'step'    => 1,
			'edit'    => true,
			'unit'    => 'px',
			'default' => '13',
		),
	),
);
$options[] = array(
	'sanitize' => 'sanitize_text_field',
	'label'    => esc_html__( 'Login/Register Popup Form', 'soledad' ),
	'id'       => 'penci_lgpop_form_heading',
	'type'     => 'soledad-fw-header',
);
$options[] = array(
	'label'    => '',
	'id'       => 'penci_tbpop_title_size_mobile',
	'type'     => 'soledad-fw-hidden',
	'sanitize' => 'absint',
	'default'  => '22',
);
$options[] = array(
	'label'    => __( 'Font Size for Titles', 'soledad' ),
	'id'       => 'penci_tbpop_title_size',
	'type'     => 'soledad-fw-size',
	'sanitize' => 'absint',
	'default'  => '24',
	'ids'      => array(
		'desktop' => 'penci_tbpop_title_size',
		'mobile'  => 'penci_tbpop_title_size_mobile',
	),
	'choices'  => array(
		'desktop' => array(
			'min'     => 1,
			'max'     => 100,
			'step'    => 1,
			'edit'    => true,
			'unit'    => 'px',
			'default' => '24',
		),
		'mobile'  => array(
			'min'     => 1,
			'max'     => 100,
			'step'    => 1,
			'edit'    => true,
			'unit'    => 'px',
			'default' => '22',
		),
	),
);
$options[] = array(
	'default'  => '14',
	'sanitize' => 'absint',
	'type'     => 'soledad-fw-size',
	'label'    => __( 'Font Size for Input Fields', 'soledad' ),
	'id'       => 'penci_tbpop_inputfs',
	'ids'      => array(
		'desktop' => 'penci_tbpop_inputfs',
	),
	'choices'  => array(
		'desktop' => array(
			'min'     => 1,
			'max'     => 100,
			'step'    => 1,
			'edit'    => true,
			'unit'    => 'px',
			'default' => '14',
		),
	),
);
$options[] = array(
	'default'  => '14',
	'sanitize' => 'absint',
	'type'     => 'soledad-fw-size',
	'label'    => __( 'Font Size for Submit Buttons', 'soledad' ),
	'id'       => 'penci_tbpop_submitfs',
	'ids'      => array(
		'desktop' => 'penci_tbpop_submitfs',
	),
	'choices'  => array(
		'desktop' => array(
			'min'     => 1,
			'max'     => 100,
			'step'    => 1,
			'edit'    => true,
			'unit'    => 'px',
			'default' => '14',
		),
	),
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'absint',
	'type'     => 'soledad-fw-size',
	'label'    => __( 'Font Size for Text/Links', 'soledad' ),
	'id'       => 'penci_tbpop_textfs',
	'ids'      => array(
		'desktop' => 'penci_tbpop_textfs',
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

return $options;
