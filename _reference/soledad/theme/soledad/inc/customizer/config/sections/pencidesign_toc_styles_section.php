<?php
$options = [];
/* Font Size */
$options[] = array(
	'default'  => 'none',
	'sanitize' => 'penci_sanitize_choices_field',
	'label'    => __( 'Font Sizes', 'soledad' ),
	'id'       => 'penci_toc_heading_fsize',
	'type'     => 'soledad-fw-header',
);
$options[] = array(
	'label'    => '',
	'id'       => 'penci_toc_heading_mfs',
	'type'     => 'soledad-fw-hidden',
	'sanitize' => 'absint',
);
$options[] = array(
	'label'    => __( 'Font Size for Heading Text', 'soledad' ),
	'id'       => 'penci_toc_heading_fs',
	'type'     => 'soledad-fw-size',
	'sanitize' => 'absint',
	'ids'      => array(
		'desktop' => 'penci_toc_heading_fs',
		'mobile'  => 'penci_toc_heading_mfs',
	),
	'choices'  => array(
		'desktop' => array(
			'min'  => 1,
			'max'  => 100,
			'step' => 1,
			'edit' => true,
			'unit' => 'px',
		),
		'mobile'  => array(
			'min'  => 1,
			'max'  => 100,
			'step' => 1,
			'edit' => true,
			'unit' => 'px',
		),
	),
);
$options[] = array(
	'label'    => '',
	'id'       => 'penci_toc_l1_mfs',
	'type'     => 'soledad-fw-hidden',
	'sanitize' => 'absint',
);
$options[] = array(
	'label'    => __( 'Font Size for Parent Items', 'soledad' ),
	'id'       => 'penci_toc_l1_fs',
	'type'     => 'soledad-fw-size',
	'sanitize' => 'absint',
	'ids'      => array(
		'desktop' => 'penci_toc_l1_fs',
		'mobile'  => 'penci_toc_l1_mfs',
	),
	'choices'  => array(
		'desktop' => array(
			'min'  => 1,
			'max'  => 100,
			'step' => 1,
			'edit' => true,
			'unit' => 'px',
		),
		'mobile'  => array(
			'min'  => 1,
			'max'  => 100,
			'step' => 1,
			'edit' => true,
			'unit' => 'px',
		),
	),
);
$options[] = array(
	'label'    => '',
	'id'       => 'penci_toc_l2_mfs',
	'type'     => 'soledad-fw-hidden',
	'sanitize' => 'absint',
);
$options[] = array(
	'label'    => __( 'Font Size for Child Items', 'soledad' ),
	'id'       => 'penci_toc_l2_fs',
	'type'     => 'soledad-fw-size',
	'sanitize' => 'absint',
	'ids'      => array(
		'desktop' => 'penci_toc_l2_fs',
		'mobile'  => 'penci_toc_l2_mfs',
	),
	'choices'  => array(
		'desktop' => array(
			'min'  => 1,
			'max'  => 100,
			'step' => 1,
			'edit' => true,
			'unit' => 'px',
		),
		'mobile'  => array(
			'min'  => 1,
			'max'  => 100,
			'step' => 1,
			'edit' => true,
			'unit' => 'px',
		),
	),
);
/* Sticky Color */
$options[] = array(
	'default'  => 'none',
	'sanitize' => 'penci_sanitize_choices_field',
	'label'    => __( 'Color', 'soledad' ),
	'id'       => 'penci_toc_color_style',
	'type'     => 'soledad-fw-header',
);
$options[] = array(
	'id'        => 'penci_toc_heading_color',
	'default'   => '',
	'transport' => 'refresh',
	'sanitize'  => 'sanitize_hex_color',
	'type'      => 'soledad-fw-color',
	'label'     => __( 'Table Heading Color', 'soledad' ),
);
$options[] = array(
	'id'        => 'penci_toc_l1_color',
	'default'   => '',
	'transport' => 'refresh',
	'sanitize'  => 'sanitize_hex_color',
	'type'      => 'soledad-fw-color',
	'label'     => __( 'Parent Items Color', 'soledad' ),
);
$options[] = array(
	'id'        => 'penci_toc_l1_hcolor',
	'default'   => '',
	'transport' => 'refresh',
	'sanitize'  => 'sanitize_hex_color',
	'type'      => 'soledad-fw-color',
	'label'     => __( 'Parent Items Hover Color', 'soledad' ),
);
$options[] = array(
	'id'        => 'penci_toc_l2_color',
	'default'   => '',
	'transport' => 'refresh',
	'sanitize'  => 'sanitize_hex_color',
	'type'      => 'soledad-fw-color',
	'label'     => __( 'Child Items Color', 'soledad' ),
);
$options[] = array(
	'id'        => 'penci_toc_l2_hcolor',
	'default'   => '',
	'transport' => 'refresh',
	'sanitize'  => 'sanitize_hex_color',
	'type'      => 'soledad-fw-color',
	'label'     => __( 'Child Items Hover Color', 'soledad' ),
);
$options[] = array(
	'id'        => 'penci_toc_bd_color',
	'default'   => '',
	'transport' => 'refresh',
	'sanitize'  => 'sanitize_hex_color',
	'type'      => 'soledad-fw-color',
	'label'     => __( 'Table Border Color', 'soledad' ),
);
$options[] = array(
	'id'        => 'penci_toc_bg_color',
	'default'   => '',
	'transport' => 'refresh',
	'sanitize'  => 'sanitize_hex_color',
	'type'      => 'soledad-fw-color',
	'label'     => __( 'Table Background Color', 'soledad' ),
);
$options[] = array(
	'id'        => 'penci_toc_tgbtn_color',
	'default'   => '',
	'transport' => 'refresh',
	'sanitize'  => 'sanitize_hex_color',
	'type'      => 'soledad-fw-color',
	'label'     => __( 'Toggle Button Color', 'soledad' ),
);
$options[] = array(
	'id'        => 'penci_toc_tgbtn_bgcolor',
	'default'   => '',
	'transport' => 'refresh',
	'sanitize'  => 'sanitize_hex_color',
	'type'      => 'soledad-fw-color',
	'label'     => __( 'Toggle Button Background Color', 'soledad' ),
);
$options[] = array(
	'id'        => 'penci_toc_tgbtn_hcolor',
	'default'   => '',
	'transport' => 'refresh',
	'sanitize'  => 'sanitize_hex_color',
	'type'      => 'soledad-fw-color',
	'label'     => __( 'Toggle Button Hover Color', 'soledad' ),
);
$options[] = array(
	'id'        => 'penci_toc_tgbtn_hbgcolor',
	'default'   => '',
	'transport' => 'refresh',
	'sanitize'  => 'sanitize_hex_color',
	'type'      => 'soledad-fw-color',
	'label'     => __( 'Toggle Button Background Hover Color', 'soledad' ),
);
/* Color */
$options[] = array(
	'default'  => 'none',
	'sanitize' => 'penci_sanitize_choices_field',
	'label'    => __( 'Sticky Color', 'soledad' ),
	'id'       => 'penci_toc_sticky_color_style',
	'type'     => 'soledad-fw-header',
);
$options[] = array(
	'id'        => 'penci_toc_sticky_heading_color',
	'default'   => '',
	'transport' => 'refresh',
	'sanitize'  => 'sanitize_hex_color',
	'type'      => 'soledad-fw-color',
	'label'     => __( 'Table Heading Color', 'soledad' ),
);
$options[] = array(
	'id'        => 'penci_toc_sticky_l1_color',
	'default'   => '',
	'transport' => 'refresh',
	'sanitize'  => 'sanitize_hex_color',
	'type'      => 'soledad-fw-color',
	'label'     => __( 'Parent Items Color', 'soledad' ),
);
$options[] = array(
	'id'        => 'penci_toc_sticky_l1_hcolor',
	'default'   => '',
	'transport' => 'refresh',
	'sanitize'  => 'sanitize_hex_color',
	'type'      => 'soledad-fw-color',
	'label'     => __( 'Parent Items Hover Color', 'soledad' ),
);
$options[] = array(
	'id'        => 'penci_toc_sticky_l2_color',
	'default'   => '',
	'transport' => 'refresh',
	'sanitize'  => 'sanitize_hex_color',
	'type'      => 'soledad-fw-color',
	'label'     => __( 'Child Items Color', 'soledad' ),
);
$options[] = array(
	'id'        => 'penci_toc_sticky_l2_hcolor',
	'default'   => '',
	'transport' => 'refresh',
	'sanitize'  => 'sanitize_hex_color',
	'type'      => 'soledad-fw-color',
	'label'     => __( 'Child Items Hover Color', 'soledad' ),
);
$options[] = array(
	'id'        => 'penci_toc_sticky_bd_color',
	'default'   => '',
	'transport' => 'refresh',
	'sanitize'  => 'sanitize_hex_color',
	'type'      => 'soledad-fw-color',
	'label'     => __( 'Table Border Color', 'soledad' ),
);
$options[] = array(
	'id'        => 'penci_toc_sticky_bg_color',
	'default'   => '',
	'transport' => 'refresh',
	'sanitize'  => 'sanitize_hex_color',
	'type'      => 'soledad-fw-color',
	'label'     => __( 'Table Background Color', 'soledad' ),
);
$options[] = array(
	'id'        => 'penci_toc_sticky_tgbtn_color',
	'default'   => '',
	'transport' => 'refresh',
	'sanitize'  => 'sanitize_hex_color',
	'type'      => 'soledad-fw-color',
	'label'     => __( 'Toggle Button Color', 'soledad' ),
);
$options[] = array(
	'id'        => 'penci_toc_sticky_tgbtn_bgcolor',
	'default'   => '',
	'transport' => 'refresh',
	'sanitize'  => 'sanitize_hex_color',
	'type'      => 'soledad-fw-color',
	'label'     => __( 'Toggle Button Background Color', 'soledad' ),
);
$options[] = array(
	'id'        => 'penci_toc_sticky_tgbtn_hcolor',
	'default'   => '',
	'transport' => 'refresh',
	'sanitize'  => 'sanitize_hex_color',
	'type'      => 'soledad-fw-color',
	'label'     => __( 'Toggle Button Hover Color', 'soledad' ),
);
$options[] = array(
	'id'        => 'penci_toc_sticky_tgbtn_hbgcolor',
	'default'   => '',
	'transport' => 'refresh',
	'sanitize'  => 'sanitize_hex_color',
	'type'      => 'soledad-fw-color',
	'label'     => __( 'Toggle Button Background Hover Color', 'soledad' ),
);

/* Sticky Button Color */
$options[] = array(
	'default'  => 'none',
	'sanitize' => 'penci_sanitize_choices_field',
	'label'    => __( 'Sitcky Button on Mobile', 'soledad' ),
	'id'       => 'penci_toc_btnsticky_style',
	'type'     => 'soledad-fw-header',
);
$options[] = array(
	'id'        => 'penci_toc_msticky_w_bgcolor',
	'default'   => '',
	'transport' => 'refresh',
	'sanitize'  => 'sanitize_hex_color',
	'type'      => 'soledad-fw-color',
	'label'     => __( 'Background Color', 'soledad' ),
);
$options[] = array(
	'id'        => 'penci_toc_msticky_w_bdcolor',
	'default'   => '',
	'transport' => 'refresh',
	'sanitize'  => 'sanitize_hex_color',
	'type'      => 'soledad-fw-color',
	'label'     => __( 'Border Color', 'soledad' ),
);
$options[] = array(
	'id'        => 'penci_toc_msticky_btn_bgcolor',
	'default'   => '',
	'transport' => 'refresh',
	'sanitize'  => 'sanitize_hex_color',
	'type'      => 'soledad-fw-color',
	'label'     => __( 'Button Background Color', 'soledad' ),
);
/*$options[] = array(
	'id'        => 'penci_toc_msticky_btn_bghcolor',
	'default'   => '',
	'transport' => 'refresh',
	'sanitize'  => 'sanitize_hex_color',
	'type'      => 'soledad-fw-color',
	'label'     => __( 'Button Hover Background Color', 'soledad' ),
);*/
$options[] = array(
	'id'        => 'penci_toc_msticky_btn_color',
	'default'   => '',
	'transport' => 'refresh',
	'sanitize'  => 'sanitize_hex_color',
	'type'      => 'soledad-fw-color',
	'label'     => __( 'Button Color', 'soledad' ),
);

/*$options[] = array(
	'id'        => 'penci_toc_msticky_btn_hcolor',
	'default'   => '',
	'transport' => 'refresh',
	'sanitize'  => 'sanitize_hex_color',
	'type'      => 'soledad-fw-color',
	'label'     => __( 'Button Hover Color', 'soledad' ),
);*/

return $options;
