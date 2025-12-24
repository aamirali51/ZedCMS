<?php
$options   = [];
$options[] = array(
	'label'    => __( 'Font Size for Categories on Slider', 'soledad' ),
	'id'       => 'penci_fslider_cat_fsize',
	'ids'      => [
		'desktop' => 'penci_fslider_cat_fsize'
	],
	'default'  => '',
	'sanitize' => 'absint',
	'type'     => 'soledad-fw-size',
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
	'label'    => __( 'Font Size for Post Meta', 'soledad' ),
	'id'       => 'penci_fslider_meta_fsize',
	'default'  => '13',
	'type'     => 'soledad-fw-size',
	'ids'      => [
		'desktop' => 'penci_fslider_meta_fsize'
	],
	'sanitize' => 'absint',
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
	'label'    => __( 'Font Size for Post Titles', 'soledad' ),
	'id'       => 'penci_fslider_title_fsize',
	'default'  => '',
	'sanitize' => 'absint',
	'type'     => 'soledad-fw-size',
	'ids'      => [
		'desktop' => 'penci_fslider_title_fsize'
	],
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
	'label'    => __( 'Font Size for Post Titles on Small Posts', 'soledad' ),
	'id'       => 'penci_fslider_smalltitle_fsize',
	'default'  => '',
	'sanitize' => 'absint',
	'type'     => 'soledad-fw-size',
	'ids'      => [
		'desktop' => 'penci_fslider_smalltitle_fsize'
	],
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
	'label'       => __( 'Font Size for Post Titles on Tiny Posts', 'soledad' ),
	'description' => __( 'You can see Tiny Posts on Style 22, 23, 24', 'soledad' ),
	'id'          => 'penci_fslider_tinytitle_fsize',
	'default'     => '',
	'sanitize'    => 'absint',
	'type'        => 'soledad-fw-size',
	'ids'         => [
		'desktop' => 'penci_fslider_tinytitle_fsize',
	],
	'choices'     => array(
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
	'label'    => __( 'Font Size for Post Titles on Mobile', 'soledad' ),
	'id'       => 'penci_fslider_title_fsize_mobile',
	'default'  => '',
	'sanitize' => 'absint',
	'type'     => 'soledad-fw-size',
	'ids'      => [
		'desktop' => 'penci_fslider_title_fsize_mobile',
	],
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
	'label'    => __( 'Font Size for Post Titles on Small Posts on Mobile', 'soledad' ),
	'id'       => 'penci_fslider_small_fsize_mobile',
	'default'  => '',
	'sanitize' => 'absint',
	'type'     => 'soledad-fw-size',
	'ids'      => [
		'desktop' => 'penci_fslider_small_fsize_mobile',
	],
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
	'label'    => __( 'Font Size for Excerpt on Style 35, 36, 38, 39', 'soledad' ),
	'id'       => 'penci_fslider_excerpt_fsize',
	'default'  => '',
	'sanitize' => 'absint',
	'type'     => 'soledad-fw-size',
	'ids'      => [
		'desktop' => 'penci_fslider_excerpt_fsize',
	],
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
	'label'    => __( 'Font Size for Button on Style 29, 30, 35, 36, 38, 39', 'soledad' ),
	'id'       => 'penci_fslider_button_fsize',
	'default'  => '',
	'sanitize' => 'absint',
	'type'     => 'soledad-fw-size',
	'ids'      => [
		'desktop' => 'penci_fslider_button_fsize',
	],
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
	'label'       => esc_html__( 'Penci Slider Style 1 & 2', 'soledad' ),
	'description' => __( 'Penci Slider is a Custom Slider - it does not based on Posts. So, you can pick any image & text & URL on each slide.<br>Check a demo for Penci Slider <a class="wp-customizer-link" target="_blank" href="https://soledad.pencidesign.net/soledad-hipster/?slider=style-31">here</a>', 'soledad' ),
	'id'          => 'penci_pslider_fsize_heading',
	'type'        => 'soledad-fw-header',
	'sanitize'    => 'sanitize_text_field'
);
$options[] = array(
	'label'       => '',
	'description'=>'',
	'id'          => 'penci_pslider_title_fsize',
	'type'        => 'soledad-fw-hidden',
	'sanitize'    => 'absint',
);
$options[] = array(
	'label'    => __( 'Font Size for Titles on Penci Slider', 'soledad' ),
	'id'       => 'penci_pslider_title_fsize',
	'type'     => 'soledad-fw-size',
	'sanitize' => 'absint',
	'ids'      => array(
		'desktop' => 'penci_pslider_title_fsize',
		'mobile'  => 'penci_pslider_title_fsize_mobile',
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
	'label'       => '',
	'description'=>'',
	'id'          => 'penci_pslider_caption_fsize',
	'type'        => 'soledad-fw-hidden',
	'sanitize'    => 'absint',
);
$options[] = array(
	'label'    => __( 'Font Size for Caption on Penci Slider', 'soledad' ),
	'id'       => 'penci_pslider_caption_fsize',
	'type'     => 'soledad-fw-size',
	'sanitize' => 'absint',
	'ids'      => array(
		'desktop' => 'penci_pslider_caption_fsize',
		'mobile'  => 'penci_pslider_caption_fsize_mobile',
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
	'label'       => '',
	'description'=>'',
	'id'          => 'penci_pslider_button_fsize_mobile',
	'type'        => 'soledad-fw-hidden',
	'sanitize'    => 'absint',
);
$options[] = array(
	'label'    => __( 'Font Size for Button on Penci Slider', 'soledad' ),
	'id'       => 'penci_pslider_button_fsize',
	'type'     => 'soledad-fw-size',
	'sanitize' => 'absint',
	'ids'      => array(
		'desktop' => 'penci_pslider_button_fsize',
		'mobile'  => 'penci_pslider_button_fsize_mobile',
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

return $options;
