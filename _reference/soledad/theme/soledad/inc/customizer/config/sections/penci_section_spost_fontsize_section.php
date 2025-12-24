<?php
$options                = [];
$options[]              = array(
	'default'  => '',
	'sanitize' => 'absint',
	'type'     => 'soledad-fw-size',
	'label'    => __( 'Font Size for Categories', 'soledad' ),
	'id'       => 'penci_single_cat_font_size',
	'ids'      => array(
		'desktop' => 'penci_single_cat_font_size',
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
$options[]              = array(
	'id'       => 'penci_single_title_font_msize',
	'type'     => 'soledad-fw-hidden',
	'sanitize' => 'absint',
);
$options[]              = array(
	'label'    => __( 'Font Size for Post Title', 'soledad' ),
	'id'       => 'penci_single_title_font_size',
	'type'     => 'soledad-fw-size',
	'sanitize' => 'absint',
	'default'  => '30',
	'ids'      => array(
		'desktop' => 'penci_single_title_font_size',
		'mobile'  => 'penci_single_title_font_msize',
	),
	'choices'  => array(
		'desktop' => array(
			'min'     => 1,
			'max'     => 100,
			'step'    => 1,
			'edit'    => true,
			'unit'    => 'px',
			'default' => '30',
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
$options[]              = array(
	'label'    => '',
	'id'       => 'penci_single_subtitle_font_msize',
	'type'     => 'soledad-fw-hidden',
	'sanitize' => 'absint',
);
$options[]              = array(
	'label'    => __( 'Font Size for SubTitle', 'soledad' ),
	'id'       => 'penci_single_subtitle_font_size',
	'type'     => 'soledad-fw-size',
	'sanitize' => 'absint',
	'ids'      => array(
		'desktop' => 'penci_single_subtitle_font_size',
		'mobile'  => 'penci_single_subtitle_font_msize',
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
$opt_single_title_fsize = array(
	'psingle_title_size_s3'  => esc_html__( 'Font Size for Post Title on Style 3', 'soledad' ),
	'psingle_title_size_s4'  => esc_html__( 'Font Size for Post Title on Style 4', 'soledad' ),
	'psingle_title_size_s5'  => esc_html__( 'Font Size for Post Title on Style 5', 'soledad' ),
	'psingle_title_size_s6'  => esc_html__( 'Font Size for Post Title on Style 6', 'soledad' ),
	'psingle_title_size_s7'  => esc_html__( 'Font Size for Post Title on Style 7', 'soledad' ),
	'psingle_title_size_s8'  => esc_html__( 'Font Size for Post Title on Style 8', 'soledad' ),
	'psingle_title_size_s9'  => esc_html__( 'Font Size for Post Title on Style 9', 'soledad' ),
	'psingle_title_size_s10' => esc_html__( 'Font Size for Post Title on Style 10', 'soledad' ),
);
foreach ( $opt_single_title_fsize as $opt_single_title_fsize_id => $opt_single_title_fsize_label ) {
	$options[] = array(
		'default'  => '',
		'sanitize' => 'absint',
		'label'    => '',
		'id'       => $opt_single_title_fsize_id . '_mobile',
		'type'     => 'soledad-fw-hidden',
	);
	$options[] = array(
		'default'  => '',
		'sanitize' => 'absint',
		'label'    => __( $opt_single_title_fsize_label, 'soledad' ),
		'id'       => $opt_single_title_fsize_id,
		'ids'      => [
			'desktop' => $opt_single_title_fsize_id,
			'mobile'  => $opt_single_title_fsize_id . '_mobile',
		],
		'type'     => 'soledad-fw-size',
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
}
$options[] = array(
	'default'  => '14',
	'sanitize' => 'absint',
	'label'    => __( 'Font Size for Post Meta', 'soledad' ),
	'id'       => 'penci_single_meta_font_size',
	'type'     => 'soledad-fw-size',
	'ids'      => array(
		'desktop' => 'penci_single_meta_font_size',
	),
	'choices'  => array(
		'desktop' => array(
			'min'     => 1,
			'max'     => 300,
			'step'    => 1,
			'edit'    => true,
			'unit'    => 'px',
			'default' => '14',
		),
	),
);
for ( $x = 1; $x < 7; $x ++ ) {
	$default   = 26 - ( $x * 2 );
	$options[] = array(
		'label'       => '',
		'description' => '',
		'id'          => 'penci_single_title_h' . $x . '_size_mobile',
		'type'        => 'soledad-fw-hidden',
		'sanitize'    => 'absint',
	);
	$options[] = array(
		'label'    => __( 'Font Size for H' . $x . ' inside Post Content', 'soledad' ),
		'id'       => 'penci_single_title_h' . $x . '_size',
		'type'     => 'soledad-fw-size',
		'sanitize' => 'absint',
		'default'  => $default,
		'ids'      => array(
			'desktop' => 'penci_single_title_h' . $x . '_size',
			'mobile'  => 'penci_single_title_h' . $x . '_size_mobile',
		),
		'choices'  => array(
			'desktop' => array(
				'min'     => 1,
				'max'     => 100,
				'step'    => 1,
				'edit'    => true,
				'unit'    => 'px',
				'default' => $default,
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
}
$options[] = array(
	'label'       => '',
	'description' => '',
	'id'          => 'penci_single_title_p_size_mobile',
	'type'        => 'soledad-fw-hidden',
	'sanitize'    => 'absint',
);
$options[] = array(
	'label'    => __( 'Font Size Text Inside Post Content', 'soledad' ),
	'id'       => 'penci_single_title_p_size',
	'type'     => 'soledad-fw-size',
	'sanitize' => 'absint',
	'ids'      => array(
		'desktop' => 'penci_single_title_p_size',
		'mobile'  => 'penci_single_title_p_size_mobile',
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
	'default'  => '',
	'sanitize' => 'absint',
	'label'    => __( 'Font Size for Blockquote', 'soledad' ),
	'id'       => 'penci_single_blockquote_fsize',
	'type'     => 'soledad-fw-size',
	'ids'      => array(
		'desktop' => 'penci_single_blockquote_fsize',
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
$options[] = array(
	'default'  => '',
	'sanitize' => 'absint',
	'label'    => __( 'Font Size for Blockquote Author', 'soledad' ),
	'id'       => 'penci_single_blockquoteauthor_fsize',
	'type'     => 'soledad-fw-size',
	'ids'      => array(
		'desktop' => 'penci_single_blockquoteauthor_fsize',
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
$options[] = array(
	'default'  => '11',
	'sanitize' => 'absint',
	'label'    => __( 'Font Size for Post Tags', 'soledad' ),
	'id'       => 'penci_single_tags_font_size',
	'type'     => 'soledad-fw-size',
	'ids'      => array(
		'desktop' => 'penci_single_tags_font_size',
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
	'default'  => '',
	'sanitize' => 'absint',
	'label'    => __( 'Font Size for Share Box', 'soledad' ),
	'id'       => 'penci_single_share_box_fsize',
	'type'     => 'soledad-fw-size',
	'ids'      => array(
		'desktop' => 'penci_single_share_box_fsize',
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
$options[] = array(
	'sanitize'    => 'sanitize_text_field',
	'label'       => esc_html__( 'Author Box', 'soledad' ),
	'id'          => 'penci_section_fauthor_box',
	'description' => __( 'Please check <a class="wp-customizer-link" target="_blank" href="https://soledad.pencidesign.net/soledad-document/#author-box">this guide</a> to know how to setup Author Box', 'soledad' ),
	'type'        => 'soledad-fw-header',
);
$options[] = array(
	'default'  => '14',
	'sanitize' => 'absint',
	'label'    => __( 'Font Size for Author Name', 'soledad' ),
	'id'       => 'penci_authorbio_name_fsize',
	'type'     => 'soledad-fw-size',
	'ids'      => array(
		'desktop' => 'penci_authorbio_name_fsize',
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
	'label'    => __( 'Font Size for Author Description', 'soledad' ),
	'id'       => 'penci_authorbio_desc_fsize',
	'type'     => 'soledad-fw-size',
	'ids'      => array(
		'desktop' => 'penci_authorbio_desc_fsize',
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
	'label'    => __( 'Font Size for Author Social Icons', 'soledad' ),
	'id'       => 'penci_authorbio_social_fsize',
	'type'     => 'soledad-fw-size',
	'ids'      => array(
		'desktop' => 'penci_authorbio_social_fsize',
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
	'sanitize' => 'sanitize_text_field',
	'label'    => esc_html__( 'Next/Previous Posts', 'soledad' ),
	'id'       => 'penci_authorbio_nextprev_fsize',
	'type'     => 'soledad-fw-header',
);
$options[] = array(
	'default'  => '14',
	'sanitize' => 'absint',
	'label'    => __( 'The "previous post", "next post" Text', 'soledad' ),
	'id'       => 'penci_prevnextpost_fsize',
	'type'     => 'soledad-fw-size',
	'ids'      => array(
		'desktop' => 'penci_prevnextpost_fsize',
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
	'label'    => __( 'Font Size for Post Titles on Prev/Next Posts', 'soledad' ),
	'id'       => 'penci_prevnextpost_title_fsize',
	'type'     => 'soledad-fw-size',
	'ids'      => array(
		'desktop' => 'penci_prevnextpost_title_fsize',
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
	'sanitize' => 'sanitize_text_field',
	'label'    => esc_html__( 'Related Posts & Comments', 'soledad' ),
	'id'       => 'penci_relatedpost_fsize',
	'type'     => 'soledad-fw-header',
);
$options[] = array(
	'default'  => '18',
	'sanitize' => 'absint',
	'label'    => __( 'Font Size for Sections Heading', 'soledad' ),
	'id'       => 'penci_rltcomment_heading_fsize',
	'type'     => 'soledad-fw-size',
	'ids'      => array(
		'desktop' => 'penci_rltcomment_heading_fsize',
	),
	'choices'  => array(
		'desktop' => array(
			'min'     => 1,
			'max'     => 100,
			'step'    => 1,
			'edit'    => true,
			'unit'    => 'px',
			'default' => '18',
		),
	),
);
$options[] = array(
	'default'  => '14',
	'sanitize' => 'absint',
	'label'    => __( 'Font Size for Post Titles on Related Posts', 'soledad' ),
	'id'       => 'penci_rltcomment_post_title_fsize',
	'type'     => 'soledad-fw-size',
	'ids'      => array(
		'desktop' => 'penci_rltcomment_post_title_fsize',
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
	'label'    => __( 'Font Size for Post Date on Related Posts', 'soledad' ),
	'id'       => 'penci_rltcomment_post_date_fsize',
	'type'     => 'soledad-fw-size',
	'ids'      => array(
		'desktop' => 'penci_rltcomment_post_date_fsize',
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
	'label'    => __( 'Font Size for Stars Rating', 'soledad' ),
	'id'       => 'penci_rltcomment_rating_fsize',
	'type'     => 'soledad-fw-size',
	'ids'      => array(
		'desktop' => 'penci_rltcomment_rating_fsize',
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
	),
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'absint',
	'label'    => __( 'Font Size for Rating Title', 'soledad' ),
	'id'       => 'penci_rltcomment_ratingt_fsize',
	'type'     => 'soledad-fw-size',
	'ids'      => array(
		'desktop' => 'penci_rltcomment_ratingt_fsize',
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
	),
);
$options[] = array(
	'default'  => '13',
	'sanitize' => 'absint',
	'label'    => __( 'Font Size for Comment Author Name', 'soledad' ),
	'id'       => 'penci_rltcomment_cmauthor_fsize',
	'type'     => 'soledad-fw-size',
	'ids'      => array(
		'desktop' => 'penci_rltcomment_cmauthor_fsize',
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
	'label'    => __( 'Font Size for Comment Date', 'soledad' ),
	'id'       => 'penci_rltcomment_cmdate_fsize',
	'type'     => 'soledad-fw-size',
	'ids'      => array(
		'desktop' => 'penci_rltcomment_cmdate_fsize',
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
	'default'  => '',
	'sanitize' => 'absint',
	'label'    => __( 'Font Size for Comment Content', 'soledad' ),
	'id'       => 'penci_rltcomment_cmcontent_fsize',
	'type'     => 'soledad-fw-size',
	'ids'      => array(
		'desktop' => 'penci_rltcomment_cmcontent_fsize',
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
$options[] = array(
	'default'  => '11',
	'sanitize' => 'absint',
	'label'    => __( 'Font Size for Reply/Edit Text', 'soledad' ),
	'id'       => 'penci_rltcomment_cmreplyedit_fsize',
	'type'     => 'soledad-fw-size',
	'ids'      => array(
		'desktop' => 'penci_rltcomment_cmreplyedit_fsize',
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
	'default'  => '14',
	'sanitize' => 'absint',
	'label'    => __( 'Font Size for Inputs on Comment Form', 'soledad' ),
	'id'       => 'penci_rltcomment_input_fsize',
	'type'     => 'soledad-fw-size',
	'ids'      => array(
		'desktop' => 'penci_rltcomment_input_fsize',
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
	'label'    => __( 'Font Size for Submit Button on Comment Form', 'soledad' ),
	'id'       => 'penci_rltcomment_submit_fsize',
	'type'     => 'soledad-fw-size',
	'ids'      => array(
		'desktop' => 'penci_rltcomment_submit_fsize',
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
	'label'    => __( 'Font Size for GDPR message & "Save my name, email.." Text', 'soledad' ),
	'id'       => 'penci_rltcomment_gdrp_fsize',
	'type'     => 'soledad-fw-size',
	'ids'      => array(
		'desktop' => 'penci_rltcomment_gdrp_fsize',
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
$options[] = array(
	'sanitize' => 'sanitize_text_field',
	'label'    => esc_html__( 'Related Posts Popup', 'soledad' ),
	'id'       => 'penci_relatedpostpopup_fsize',
	'type'     => 'soledad-fw-header',
);
$options[] = array(
	'default'  => '16',
	'sanitize' => 'absint',
	'label'    => __( 'Font Size for Heading', 'soledad' ),
	'id'       => 'penci_rltpopup_heading_fsize',
	'type'     => 'soledad-fw-size',
	'ids'      => array(
		'desktop' => 'penci_rltpopup_heading_fsize',
	),
	'choices'  => array(
		'desktop' => array(
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
	'default'  => '15',
	'sanitize' => 'absint',
	'label'    => __( 'Font Size for Post Titles', 'soledad' ),
	'id'       => 'penci_rltpopup_title_fsize',
	'type'     => 'soledad-fw-size',
	'ids'      => array(
		'desktop' => 'penci_rltpopup_title_fsize',
	),
	'choices'  => array(
		'desktop' => array(
			'min'     => 1,
			'max'     => 100,
			'step'    => 1,
			'edit'    => true,
			'unit'    => 'px',
			'default' => '15',
		),
	),
);
$options[] = array(
	'default'  => '13',
	'sanitize' => 'absint',
	'label'    => __( 'Font Size for Post Date', 'soledad' ),
	'id'       => 'penci_rltpopup_date_fsize',
	'type'     => 'soledad-fw-size',
	'ids'      => array(
		'desktop' => 'penci_rltpopup_date_fsize',
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
	'label'    => esc_html__( 'Smart Lists', 'soledad' ),
	'id'       => 'penci_smartlists_fsize',
	'type'     => 'soledad-fw-header',
);

$options[] = array(
	'default'  => '',
	'sanitize' => 'absint',
	'label'    => __( 'Font Size for Items Number', 'soledad' ),
	'id'       => 'penci_smartlists_inumber_fsize',
	'type'     => 'soledad-fw-size',
	'ids'      => array(
		'desktop' => 'penci_smartlists_inumber_fsize',
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
	),
);
$options[] = array(
	'label'    => '',
	'id'       => 'penci_smartlists_heading_mfsize',
	'type'     => 'soledad-fw-hidden',
	'sanitize' => 'absint',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'absint',
	'label'    => __( 'Font Size for Items Heading', 'soledad' ),
	'id'       => 'penci_smartlists_heading_fsize',
	'type'     => 'soledad-fw-size',
	'ids'      => array(
		'desktop' => 'penci_smartlists_heading_fsize',
		'mobile'  => 'penci_smartlists_heading_mfsize',
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
$options[] = array(
	'label'    => '',
	'id'       => 'penci_smartlists_text_mfsize',
	'type'     => 'soledad-fw-hidden',
	'sanitize' => 'absint',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'absint',
	'label'    => __( 'Font Size for Items Text', 'soledad' ),
	'id'       => 'penci_smartlists_text_fsize',
	'type'     => 'soledad-fw-size',
	'ids'      => array(
		'desktop' => 'penci_smartlists_text_fsize',
		'mobile'  => 'penci_smartlists_text_mfsize',
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
$options[] = array(
	'label'    => '',
	'id'       => 'penci_smartlists_btn_mfsize',
	'type'     => 'soledad-fw-hidden',
	'sanitize' => 'absint',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'absint',
	'label'    => __( 'Font Size for Buttons', 'soledad' ),
	'id'       => 'penci_smartlists_btn_fsize',
	'type'     => 'soledad-fw-size',
	'ids'      => array(
		'desktop' => 'penci_smartlists_btn_fsize',
		'mobile'  => 'penci_smartlists_btn_mfsize',
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

return $options;
