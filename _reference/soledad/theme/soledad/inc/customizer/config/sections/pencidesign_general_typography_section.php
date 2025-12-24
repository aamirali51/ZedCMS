<?php
$options   = [];
$options[] = array(
	'label' => __( 'General Typography Settings', 'soledad' ),
	'id'    => 'penci_font_heading_titles',
	'type'  => 'soledad-fw-header',
);

$options[] = array(
	'label'       => __( 'Font For Heading Titles', 'soledad' ),
	'id'          => 'penci_font_for_title',
	'description' => __( 'Default font is "Raleway"', 'soledad' ),
	'type'        => 'soledad-fw-select',
	'choices'     => penci_all_fonts(),
	'default'     => '"Raleway", "100:200:300:regular:500:600:700:800:900:100italic:200italic:300italic:italic:500italic:600italic:700italic:800italic:900italic", sans-serif',
	'sanitize'    => 'penci_sanitize_choices_field'
);
$options[] = array(
	'label'    => __( 'Font For Heading Titles on Mobile', 'soledad' ),
	'id'       => 'penci_font_for_title_mobile',
	'type'     => 'soledad-fw-select',
	'choices'  => penci_all_fonts(),
	'default'  => '',
	'sanitize' => 'penci_sanitize_choices_field'
);
$options[] = array(
	'label'    => __( 'Font Weight For Heading Titles', 'soledad' ),
	'id'       => 'penci_font_weight_title',
	'type'     => 'soledad-fw-select',
	'default'  => '',
	'sanitize' => 'penci_sanitize_choices_field',
	'choices'  => array(
		''        => __( '- Select -', 'soledad' ),
		'normal'  => __( 'Normal', 'soledad' ),
		'bold'    => __( 'Bold', 'soledad' ),
		'bolder'  => __( 'Bolder', 'soledad' ),
		'lighter' => __( 'Lighter', 'soledad' ),
		'100'     => __( '100', 'soledad' ),
		'200'     => __( '200', 'soledad' ),
		'300'     => __( '300', 'soledad' ),
		'400'     => __( '400', 'soledad' ),
		'500'     => __( '500', 'soledad' ),
		'600'     => __( '600', 'soledad' ),
		'700'     => __( '700', 'soledad' ),
		'800'     => __( '800', 'soledad' ),
		'900'     => __( '900', 'soledad' ),
	)
);
$options[] = array(
	'label'       => __( 'Font For Body Text', 'soledad' ),
	'id'          => 'penci_font_for_body',
	'description' => __( 'Default font is "PT Serif"', 'soledad' ),
	'type'        => 'soledad-fw-select',
	'choices'     => penci_all_fonts(),
	'default'     => '"PT Serif", "regular:italic:700:700italic", serif',
	'sanitize'    => 'penci_sanitize_choices_field'
);
$options[] = array(
	'label'    => __( 'Font For Body Text on Mobile', 'soledad' ),
	'id'       => 'penci_font_for_body_mobile',
	'type'     => 'soledad-fw-select',
	'choices'  => penci_all_fonts(),
	'default'  => '',
	'sanitize' => 'penci_sanitize_choices_field'
);
$options[] = array(
	'label'    => __( 'Font Weight For Body Text', 'soledad' ),
	'id'       => 'penci_font_weight_bodytext',
	'type'     => 'soledad-fw-select',
	'default'  => '',
	'sanitize' => 'penci_sanitize_choices_field',
	'choices'  => array(
		''        => __( '- Select -', 'soledad' ),
		'normal'  => __( 'Normal', 'soledad' ),
		'bold'    => __( 'Bold', 'soledad' ),
		'bolder'  => __( 'Bolder', 'soledad' ),
		'lighter' => __( 'Lighter', 'soledad' ),
		'100'     => __( '100', 'soledad' ),
		'200'     => __( '200', 'soledad' ),
		'300'     => __( '300', 'soledad' ),
		'400'     => __( '400', 'soledad' ),
		'500'     => __( '500', 'soledad' ),
		'600'     => __( '600', 'soledad' ),
		'700'     => __( '700', 'soledad' ),
		'800'     => __( '800', 'soledad' ),
		'900'     => __( '900', 'soledad' ),
	)
);
$options[] = array(
	'label'    => '',
	'id'       => 'penci_font_mfor_size_body',
	'type'     => 'soledad-fw-hidden',
	'sanitize' => 'absint',
	'default'  => '14',
);
$options[] = array(
	'label'    => __( 'General Font Size for Text', 'soledad' ),
	'id'       => 'penci_font_for_size_body',
	'type'     => 'soledad-fw-size',
	'default'  => '14',
	'sanitize' => 'absint',
	'ids'      => array(
		'desktop' => 'penci_font_for_size_body',
		'mobile'  => 'penci_font_mfor_size_body',
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
		'mobile'  => array(
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
	'label'    => __( 'General Line Height for Text', 'soledad' ),
	'type'     => 'soledad-fw-size',
	'id'       => 'penci_body_line_height',
	'default'  => '1.8',
	'sanitize' => 'penci_sanitize_decimal_empty_field',
	'ids'      => array(
		'desktop' => 'penci_body_line_height',
	),
	'choices'  => array(
		'desktop' => array(
			'min'     => 1,
			'max'     => 100,
			'step'    => 1,
			'edit'    => true,
			'unit'    => 'px',
			'default' => '1.8',
		),
	),
);
$options[] = array(
	'label'    => '',
	'id'       => 'penci_archive_mobile_fpagetitle',
	'type'     => 'soledad-fw-hidden',
	'sanitize' => 'absint',
	'default'  => '16',
);
$options[] = array(
	'label'       => __( 'Font Size for Archive Page Title', 'soledad' ),
	'description' => __( 'Apply for Category Page Title, Tag Page Title, Search Page Title, Archive Page Title - check more on <a class="wp-customizer-link" href="https://imgresources.s3.amazonaws.com/archive-page-title.png" target="_blank">this image</a>', 'soledad' ),
	'id'          => 'penci_archive_fpagetitle',
	'type'        => 'soledad-fw-size',
	'sanitize'    => 'absint',
	'default'     => '24',
	'ids'         => array(
		'desktop' => 'penci_archive_fpagetitle',
		'mobile'  => 'penci_archive_mobile_fpagetitle',
	),
	'choices'     => array(
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
			'default' => '16',
		),
	),
);
$options[] = array(
	'label'    => __( 'Disable Uppercase on Archive Page Title', 'soledad' ),
	'id'       => 'penci_archive_uppagetitle',
	'type'     => 'soledad-fw-toggle',
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field'
);
$options[] = array(
	'id'       => 'penci_body_breadcrumbs',
	'type'     => 'soledad-fw-size',
	'label'    => __( 'Font Size for Breadcrumbs', 'soledad' ),
	'default'  => '13',
	'sanitize' => 'absint',
	'ids'      => array(
		'desktop' => 'penci_body_breadcrumbs',
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
	'label'    => __( 'Font Size for "Load More Posts" & Pagination Button', 'soledad' ),
	'id'       => 'penci_home_loadmore_size',
	'type'     => 'soledad-fw-size',
	'default'  => '12',
	'sanitize' => 'absint',
	'ids'      => array(
		'desktop' => 'penci_home_loadmore_size',
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
	'label' => __( 'Advanced Typography Settings', 'soledad' ),
	'id'    => 'penci_font_advanced_typography',
	'type'  => 'soledad-fw-header',
);

for ( $i = 1; $i <= 6; $i ++ ) {
	$options[] = array(
		'label'    => '',
		'id'       => 'penci_font_gh' . $i . '_mfont_size',
		'type'     => 'soledad-fw-hidden',
		'sanitize' => 'absint',
		'default'  => '',
	);
	$options[] = array(
		'label'    => __( 'H' . $i . ' Font Size', 'soledad' ),
		'id'       => 'penci_font_gh' . $i . '_font_size',
		'type'     => 'soledad-fw-size',
		'sanitize' => 'absint',
		'default'  => '',
		'ids'      => array(
			'desktop' => 'penci_font_gh' . $i . '_font_size',
			'mobile'  => 'penci_font_gh' . $i . '_mfont_size',
		),
		'choices'  => array(
			'desktop' => array(
				'min'     => 1,
				'max'     => 100,
				'step'    => 1,
				'edit'    => true,
				'unit'    => 'px',
				'default' => '',
			),
			'mobile'  => array(
				'min'     => 1,
				'max'     => 100,
				'step'    => 1,
				'edit'    => true,
				'unit'    => 'px',
				'default' => '',
			),
		),
	);
}

return $options;
