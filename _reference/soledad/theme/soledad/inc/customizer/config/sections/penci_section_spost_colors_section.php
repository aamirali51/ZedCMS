<?php
$options         = [];
$options[]       = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Single Categories Accent Color', 'soledad' ),
	'id'       => 'penci_single_cat_color',
);
$options[]       = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Post Title Color', 'soledad' ),
	'id'       => 'penci_single_title_color',
);
$options[]       = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Post SubTitle Color', 'soledad' ),
	'id'       => 'penci_single_subtitle_color',
);
$options[]       = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Post Meta Color', 'soledad' ),
	'id'       => 'penci_single_meta_color',
);
$more_opt_single = array(
	'penci_single_color_title_s568'    => esc_html__( 'Post Title Color for Template Style 5, 6, 8, 13, 21', 'soledad' ),
	'penci_single_color_subtitle_s568' => esc_html__( 'Post SubTitle Color for Template Style 5, 6, 8, 13, 21', 'soledad' ),
	'penci_single_color_cat_s568'      => esc_html__( 'Categories Color for Template Style 5, 6, 8, 13, 21', 'soledad' ),
	'penci_single_color_meta_s568'     => esc_html__( 'Color for Posts Meta on Template Style 5, 6, 8, 13, 21', 'soledad' ),
	'penci_single_bgcolor_header'      => esc_html__( 'Header Background for Template Style 9 & 10', 'soledad' ),
	'penci_single_color_title_s10'     => esc_html__( 'Post Title Color for Template Style 10', 'soledad' ),
	'penci_single_color_subtitle_s10'  => esc_html__( 'Post SubTitle Color for Template Style 10', 'soledad' ),
	'penci_single_color_cat_s10'       => esc_html__( 'Categories Color for Template Style 10', 'soledad' ),
	'penci_single_color_meta_s10'      => esc_html__( 'Color for Posts Meta on Template Style 10', 'soledad' ),
	'penci_single_color_bread_s10'     => esc_html__( 'Color for Breadcrumb on Template Style 10', 'soledad' ),
	'penci_single_13_bgcolor'     	   => esc_html__( 'Background Color on Template Style 13', 'soledad' ),
	'penci_single_15_bgcolor'     	   => esc_html__( 'Background Color on Template Style 15', 'soledad' ),
	'penci_single_18_bgcolor'     	   => esc_html__( 'Background Color on Template Style 18', 'soledad' ),
	'penci_single_21_obgcolor'     	   => esc_html__( 'Background Overlay Color on Template Style 21', 'soledad' ),
);
foreach ( $more_opt_single as $opt_single_color_id => $opt_single_color_label ) {
	$desc = '';
	if ( 'penci_single_color_title_s568' == $opt_single_color_id || 'penci_single_color_subtitle_s568' == $opt_single_color_id || 'penci_single_color_cat_s568' == $opt_single_color_id || 'penci_single_color_meta_s568' == $opt_single_color_id ) {
		$desc = esc_html__( 'This option doesn\'t apply for move post title & meta below featured image', 'soledad' );
	}
	$options[] = array(
		'default'     => '',
		'sanitize'    => 'sanitize_hex_color',
		'type'        => 'soledad-fw-color',
		'label'       => $opt_single_color_label,
		'id'          => $opt_single_color_id,
		'description' => $desc
	);
}
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Tags Text Color', 'soledad' ),
	'id'       => 'penci_single_tag_color',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Tags Borders Color', 'soledad' ),
	'id'       => 'penci_single_tag_border',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Tags Background Color', 'soledad' ),
	'id'       => 'penci_single_tag_bg',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Tags Hover Text Color', 'soledad' ),
	'id'       => 'penci_single_tag_hcolor',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Tags Hover Borders Color', 'soledad' ),
	'id'       => 'penci_single_tag_hborder',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Tags Hover Background Color', 'soledad' ),
	'id'       => 'penci_single_tag_hbg',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Share Text Color', 'soledad' ),
	'id'       => 'penci_single_share_tcolor',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Share Text Background Color', 'soledad' ),
	'id'       => 'penci_single_share_bgcolor',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Share Text Borders Color', 'soledad' ),
	'id'       => 'penci_single_share_bdcolor',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Share Box Icon Color', 'soledad' ),
	'id'       => 'penci_single_share_icon_color',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Share Box Icon Hover Color', 'soledad' ),
	'id'       => 'penci_single_share_icon_hover_color',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Numbers Like Of Post Color', 'soledad' ),
	'id'       => 'penci_single_number_like_color',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Icon Background Color for Share Box Style 3', 'soledad' ),
	'id'       => 'penci_single_share_icon_style3_bgcolor',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Icon Background Hover Color for Share Box Style 3', 'soledad' ),
	'id'       => 'penci_single_share_icon_style3_hbgcolor',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Custom Text Color Inside Post Content', 'soledad' ),
	'id'       => 'penci_single_color_text',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Color for Links', 'soledad' ),
	'id'       => 'penci_single_color_links',
);
for ( $pheading = 1; $pheading < 7; $pheading ++ ) {
	$options[] = array(
		'default'  => '',
		'sanitize' => 'sanitize_hex_color',
		'type'     => 'soledad-fw-color',
		'label'    => __( 'Custom Color for H' . $pheading . ' Tag Inside Post Content', 'soledad' ),
		'id'       => 'penci_single_color_h' . $pheading,
	);
}
/* social new style*/
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Social Background Color', 'soledad' ),
	'id'       => 'penci_single_newshare_bgcolor',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Social HoverBackground Color', 'soledad' ),
	'id'       => 'penci_single_newshare_hbgcolor',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Social Color', 'soledad' ),
	'id'       => 'penci_single_newshare_color',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Social Hover Color', 'soledad' ),
	'id'       => 'penci_single_newshare_hcolor',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Social Borders Color', 'soledad' ),
	'id'       => 'penci_single_newshare_bcolor',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Social Hover Boders Color', 'soledad' ),
	'id'       => 'penci_single_newshare_hbcolor',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Plus Button Color', 'soledad' ),
	'id'       => 'penci_single_splus_color',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Plus Button Hover Color', 'soledad' ),
	'id'       => 'penci_single_splus_hcolor',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Plus Button Background Color', 'soledad' ),
	'id'       => 'penci_single_splus_bgcolor',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Plus Button Hover Background Color', 'soledad' ),
	'id'       => 'penci_single_splus_hbgcolor',
);
/*end social new style*/
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Blockquote Text Color', 'soledad' ),
	'id'       => 'penci_bquote_text_color',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Blockquote Author Text Color', 'soledad' ),
	'id'       => 'penci_bquote_author_color',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Blockquote Background Color', 'soledad' ),
	'id'       => 'penci_bquote_bgcolor',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Borders & Icon Colors on Blockquote', 'soledad' ),
	'id'       => 'penci_bquote_border_color',
);
$options[] = array(
	'sanitize'    => 'sanitize_text_field',
	'label'       => esc_html__( 'Author Box', 'soledad' ),
	'id'          => 'penci_section_cauthor_box',
	'description' => __( 'Please check <a class="wp-customizer-link" target="_blank" href="https://soledad.pencidesign.net/soledad-document/#author-box">this guide</a> to know how to setup Author Box', 'soledad' ),
	'type'        => 'soledad-fw-header',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Author Box Background Color', 'soledad' ),
	'id'       => 'penci_authorbio_bg',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Author Box Borders Color', 'soledad' ),
	'id'       => 'penci_authorbio_bordercl',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Author Name Color', 'soledad' ),
	'id'       => 'penci_authorbio_name_color',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Author Name Hover Color', 'soledad' ),
	'id'       => 'penci_authorbio_name_hcolor',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Author Description Color', 'soledad' ),
	'id'       => 'penci_authorbio_desc_color',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Author Social Icons Color', 'soledad' ),
	'id'       => 'penci_authorbio_social_color',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Author Social Icons Hover Color', 'soledad' ),
	'id'       => 'penci_authorbio_social_hcolor',
);
$options[] = array(
	'sanitize' => 'sanitize_text_field',
	'label'    => esc_html__( 'Next/Previous Posts', 'soledad' ),
	'id'       => 'penci_section_cpost_nav',
	'type'     => 'soledad-fw-header',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Color for "previous post", "next post" Text', 'soledad' ),
	'id'       => 'penci_prevnext_colors',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Color for Post Titles', 'soledad' ),
	'id'       => 'penci_prevnext_ctitle',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Color for Post Titles on Hover', 'soledad' ),
	'id'       => 'penci_prevnext_hctitle',
);
$options[] = array(
	'sanitize' => 'sanitize_text_field',
	'label'    => esc_html__( 'Related Posts & Comments', 'soledad' ),
	'id'       => 'penci_section_crelatedp',
	'type'     => 'soledad-fw-header',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Color for Sections Heading', 'soledad' ),
	'id'       => 'penci_relatedcm_heading',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Color for Lines Before & After Sections Heading', 'soledad' ),
	'id'       => 'penci_relatedcm_lineheading',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Post Titles Color on Related Posts', 'soledad' ),
	'id'       => 'penci_relatedcm_ctitle',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Post Titles Hover Color on Related Posts', 'soledad' ),
	'id'       => 'penci_relatedcm_hctitle',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Post Date Color on Related Posts', 'soledad' ),
	'id'       => 'penci_relatedcm_cdate',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Comment Author Color', 'soledad' ),
	'id'       => 'penci_relatedcm_author',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Comment Author Hover Color', 'soledad' ),
	'id'       => 'penci_relatedcm_hauthor',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Comment Date Color', 'soledad' ),
	'id'       => 'penci_relatedcm_cmdate',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Reply/Edit Text Color', 'soledad' ),
	'id'       => 'penci_relatedcm_replyedit',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Comment Content Color', 'soledad' ),
	'id'       => 'penci_relatedcm_cmcontent',
);
$options[] = array(
	'default'     => '',
	'sanitize'    => 'sanitize_hex_color',
	'type'        => 'soledad-fw-color',
	'label'       => __( 'Comment Form Inputs & Textarea Color', 'soledad' ),
	'description' => __( 'For change color on "Submit" button color, check options on General > Colors > General Buttons', 'soledad' ),
	'id'          => 'penci_relatedcm_cminput',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'GDPR message & "Save my name, email.." Color', 'soledad' ),
	'id'       => 'penci_relatedcm_gdpr',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Accent Color', 'soledad' ),
	'id'       => 'penci_single_accent_color',
);
$options[] = array(
	'sanitize'    => 'sanitize_text_field',
	'label'       => esc_html__( 'Related Posts Popup', 'soledad' ),
	'id'          => 'penci_section_crelated_post_popup',
	'description' => __( 'Please check <a class="wp-customizer-link" target="_blank" href="https://imgresources.s3.amazonaws.com/related-posts-popup.png">this image</a> to know what is "Related Posts Popup"', 'soledad' ),
	'type'        => 'soledad-fw-header',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Custom Heading Background on Related Posts Popup', 'soledad' ),
	'id'       => 'penci_rltpop_heading_bg',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Custom Heading Text Color on Related Posts Popup', 'soledad' ),
	'id'       => 'penci_rltpop_heading_color',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Close Button Color on Related Posts Popup', 'soledad' ),
	'id'       => 'penci_rltpop_close_color',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Background Color for Related Posts Popup', 'soledad' ),
	'id'       => 'penci_rltpop_bg_color',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Custom Color for Post Titles on Related Posts Popup', 'soledad' ),
	'id'       => 'penci_rltpop_title_color',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Custom Color for Post Titles Hover on Related Posts Popup', 'soledad' ),
	'id'       => 'penci_rltpop_title_hover',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Custom Color for Date on Related Posts Popup', 'soledad' ),
	'id'       => 'penci_rltpop_date_color',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Custom Color for Borders on Related Posts Popup', 'soledad' ),
	'id'       => 'penci_rltpop_border_color',
);

// Smart Lists
$options[] = array(
	'sanitize' => 'sanitize_text_field',
	'label'    => esc_html__( 'Smart Lists', 'soledad' ),
	'id'       => 'penci_section_smart_lists',
	'type'     => 'soledad-fw-header',
);

$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Heading Color', 'soledad' ),
	'id'       => 'penci_sml_heading_cl',
);

$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Items Number Text Color', 'soledad' ),
	'id'       => 'penci_sml_number_cl',
);

$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Items Number Border Color', 'soledad' ),
	'id'       => 'penci_sml_number_bcl',
);

$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Items Number Background Color', 'soledad' ),
	'id'       => 'penci_sml_number_bgcl',
);

$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Description Text Color', 'soledad' ),
	'id'       => 'penci_sml_desc_cl',
);

$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Buttons Text Color', 'soledad' ),
	'id'       => 'penci_sml_btn_cl',
);

$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Buttons Background Color', 'soledad' ),
	'id'       => 'penci_sml_btn_bgcl',
);

$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Buttons Border Color', 'soledad' ),
	'id'       => 'penci_sml_btn_bdcl',
);

$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Buttons Text Hover Color', 'soledad' ),
	'id'       => 'penci_sml_btn_hcl',
);

$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Buttons Background Hover Color', 'soledad' ),
	'id'       => 'penci_sml_btn_bghcl',
);

$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Buttons Border Hover Color', 'soledad' ),
	'id'       => 'penci_sml_btn_bdhcl',
);

$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Navigation Background Color', 'soledad' ),
	'id'       => 'penci_sml_nav_bgcl',
);

$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Navigation Border Color', 'soledad' ),
	'id'       => 'penci_sml_nav_bdcl',
);

// Sticky Posts
$options[] = array(
	'sanitize' => 'sanitize_text_field',
	'label'    => esc_html__( 'Sticky Posts Navigations', 'soledad' ),
	'id'       => 'penci_section_sticky_posts_nav',
	'type'     => 'soledad-fw-header',
);

$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Background Color', 'soledad' ),
	'id'       => 'penci_sticky_posts_nav_bgcolor',
);

$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Border Color', 'soledad' ),
	'id'       => 'penci_sticky_posts_nav_bdcolor',
);

$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Post Title Color', 'soledad' ),
	'id'       => 'penci_sticky_posts_nav_tcolor',
);

$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Post Title Hover Color', 'soledad' ),
	'id'       => 'penci_sticky_posts_nav_thcolor',
);

$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Main Post Title Color', 'soledad' ),
	'id'       => 'penci_sticky_posts_nav_mpcolor',
);

$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Navigation Color', 'soledad' ),
	'id'       => 'penci_sticky_posts_nav_color',
);

// Focus Mode Button
$options[] = array(
	'sanitize' => 'sanitize_text_field',
	'label'    => esc_html__( 'Focus Mode', 'soledad' ),
	'id'       => 'penci_section_focus_mode_style',
	'type'     => 'soledad-fw-header',
);

$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Background Color', 'soledad' ),
	'id'       => 'penci_fcusmode_bgcolor',
);

$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Text Color', 'soledad' ),
	'id'       => 'penci_fcusmode_txtcolor',
);

$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Active Background Color', 'soledad' ),
	'id'       => 'penci_fcusmode_bghcolor',
);

$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Active Text Color', 'soledad' ),
	'id'       => 'penci_fcusmode_txthcolor',
);

return $options;
