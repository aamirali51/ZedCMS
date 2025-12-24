<?php
$settings   = vc_map_get_attributes( $this->getShortcode(), $atts );
$block_id   = Penci_Vc_Helper::get_unique_id_block( 'pc_single_comments' );
$css_class  = vc_shortcode_custom_css_class( $settings['css'] );
$css_custom = '';
$mods       = [
	'penci_single_gdpr_text',
	'penci_single_gdpr',
	'penci_single_hide_save_fields',
	'penci_post_move_comment_box',
	'penci_single_comments_remove_name',
	'penci_single_comments_remove_email',
	'penci_single_comments_remove_website'
];
foreach ( $mods as $mod ) {
	$value = $settings[ $mod ];
	add_filter( 'theme_mod_' . $mod, function () use ( $value ) {
		return penci_switch_value( $value );
	} );
}
$class = $settings['penci_post_move_comment_box'] ? 'move-top' : 'normal';
echo '<div id="' . $block_id . '" class="pc-cscomments ' . $class . ' ' . $css_class . '">';
comments_template();
echo '</div>';
$block_id = '#' . $block_id;
if ( $settings['comment_rheading_lcolor'] ) {
	$css_custom .= $block_id . ' .post-box-title:before,' . $block_id . ' .post-box-title:after,' . $block_id . ' #respond h3.comment-reply-title span:before,' . $block_id . ' #respond h3.comment-reply-title span:after{display:none;}';
}
if ( $settings['submit_btn_align'] ) {
	$css_custom .= $block_id . ' #respond p.form-submit{text-align:' . $settings['submit_btn_align'] . ';}';
}
if ( $settings['comment_heading_color'] ) {
	$css_custom .= $block_id . ' .post-box-title{color:' . $settings['comment_heading_lcolor'] . ';}';
}
if ( $settings['author_ava_width'] ) {
	$css_custom .= $block_id . ' .thecomment .author-img{width:' . $settings['author_ava_width'] . 'px;}';
	$css_custom .= $block_id . ' .thecomment .comment-text{margin-left:' . ( $settings['author_ava_width'] + 20 ) . 'px;}';
}
if ( $settings['author_ava_boderradius'] ) {
	$css_custom .= $block_id . ' .thecomment .author-img{border-radius:' . $settings['author_ava_boderradius'] . ';}';
}
if ( $settings['comment_name_color'] ) {
	$css_custom .= $block_id . ' .thecomment .comment-text span.author, ' . $block_id . ' .thecomment .comment-text span.author a{color:' . $settings['comment_name_color'] . ';}';
}
if ( $settings['comment_name_hcolor'] ) {
	$css_custom .= $block_id . ' .thecomment .comment-text span.author a:hover{color:' . $settings['comment_name_hcolor'] . ';}';
}
if ( $settings['comment_date_color'] ) {
	$css_custom .= $block_id . ' .thecomment .comment-text span.date{color:' . $settings['comment_date_color'] . ';}';
}
if ( $settings['comment_date_icolor'] ) {
	$css_custom .= $block_id . ' .thecomment .comment-text span.date i{color:' . $settings['comment_date_icolor'] . ';}';
}
if ( $settings['comment_content_color'] ) {
	$css_custom .= $block_id . ' .thecomment .comment-content{color:' . $settings['comment_content_color'] . ';}';
}
if ( $settings['comment_reply_color'] ) {
	$css_custom .= $block_id . ' .post-comments span.reply a,' . $block_id . ' #respond h3 small a{color:' . $settings['comment_reply_color'] . ';}';
}
if ( $settings['comment_reply_hcolor'] ) {
	$css_custom .= $block_id . ' .post-comments span.reply a:hover,' . $block_id . ' #respond h3 small a:hover{color:' . $settings['comment_reply_hcolor'] . ';}';
}
if ( $settings['comment_item_bcolor'] ) {
	$css_custom .= $block_id . ' .comments .comment,' . $block_id . ' .pc-cscomments.move-top .post-title-box,' . $block_id . ' #respond h3{border-color:' . $settings['comment_item_bcolor'] . ';}';
}
if ( $settings['commentf_heading_color'] ) {
	$css_custom .= $block_id . ' #respond h3.comment-reply-title span{color:' . $settings['commentf_heading_color'] . ';}';
}
if ( $settings['commentf_input_text_color'] ) {
	$css_custom .= $block_id . ' #respond textarea, {{WRAPPER}} #respond input{color:' . $settings['commentf_input_text_color'] . ';}';
	$css_custom .= $block_id . ' #respond ::placeholder{color:' . $settings['commentf_input_text_color'] . ';}';
}
if ( $settings['commentf_input_border_color'] ) {
	$css_custom .= $block_id . ' #respond textarea, ' . $block_id . ' #respond input{border-color:' . $settings['commentf_input_border_color'] . ';}';
}
if ( $settings['commentf_submit_bg_color'] ) {
	$css_custom .= $block_id . ' #respond #submit{background-color:' . $settings['commentf_submit_bg_color'] . ';}';
}
if ( $settings['commentf_submit_bg_hcolor'] ) {
	$css_custom .= $block_id . ' #respond #submit:hover{background-color:' . $settings['commentf_submit_bg_color'] . ';}';
}
if ( $settings['commentf_submit_txt_color'] ) {
	$css_custom .= $block_id . ' #respond #submit{color:' . $settings['commentf_submit_txt_color'] . ';}';
}
if ( $settings['commentf_submit_txt_hcolor'] ) {
	$css_custom .= $block_id . ' #respond #submit:hover{color:' . $settings['commentf_submit_txt_hcolor'] . ';}';
}
if ( $settings['commentf_cookies_notice_color'] ) {
	$css_custom .= $block_id . ' #respond .comment-form-cookies-consent{color:' . $settings['commentf_cookies_notice_color'] . ';}';
}
if ( $settings['commentf_gdpr_text_color'] ) {
	$css_custom .= $block_id . ' #respond .penci-gdpr-message{color:' . $settings['commentf_gdpr_text_color'] . ';}';
}
if ( $settings['responsive_spacing'] ) {
	$css_custom .= penci_extract_spacing_style( $block_id, $settings['responsive_spacing'] );
}
if ( $css_custom ) {
	echo '<style>';
	echo $css_custom;
	echo '</style>';
}
