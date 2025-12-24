<?php
$settings         = vc_map_get_attributes( $this->getShortcode(), $atts );
$block_id         = Penci_Vc_Helper::get_unique_id_block( 'pc_single_share' );
$css_custom       = '';
$style_cscount    = $settings['penci_single_style_cscount'];
$wrapper_class    = array();
$wrapper_class[]  = 'tags-share-box';
$wrapper_class[]  = is_page() ? 'page-share hide-tags' : 'single-post-share';
$wrapper_class[]  = 'tags-share-box-' . $style_cscount;
$wrapper_class_c1 = 's1' == $style_cscount ? ' center-box' : ' tags-share-box-2_3';
$wrapper_class[]  = strpos( $style_cscount, 'n' ) !== false ? ' pcnew-share' : $wrapper_class_c1;

if ( in_array( $style_cscount, [ 'n1', 'n3', 'n5', 'n8', 'n9', 'n10', 'n11', 'n12', 'n13', 'n19', 'n20' ] ) ) {
	$wrapper_class[] = ' penci-social-colored';
}

if ( in_array( $style_cscount, [
	'n1',
	'n3',
	'n5',
	'n8',
	'n9',
	'n10',
	'n11',
	'n12',
	'n13',
	'n14',
	'n16',
	'n19',
	'n20'
] ) ) {
	$wrapper_class[] = ' penci-icon-full';
}

if ( in_array( $style_cscount, [ 'n2', 'n4', 'n6', 'n7', 'n9', 'n11', 'n13' ] ) ) {
	$wrapper_class[] = ' tags-share-box-s2';
}

if ( in_array( $style_cscount, [
	'n2',
	'n4',
	'n6',
	'n7',
	'n9',
	'n11',
	'n13',
	'n15',
	'n17',
	'n18',
	'n19',
	'n20'
] ) ) {
	$wrapper_class[] = ' show-txt';
}

if ( in_array( $style_cscount, [ 'n3', 'n4', 'n18' ] ) ) {
	$wrapper_class[] = ' rounder';
}

if ( in_array( $style_cscount, [ 'n5', 'n6', 'n10', 'n11' ] ) ) {
	$wrapper_class[] = ' show-shadow';
}

if ( in_array( $style_cscount, [ 'n7' ] ) ) {
	$wrapper_class[] = ' focus-icon';
}

if ( in_array( $style_cscount, [ 'n8', 'n9', 'n10', 'n11', 'n12', 'n13' ] ) ) {
	$wrapper_class[] = ' size-large';
}

if ( in_array( $style_cscount, [ 'n9', 'n11', 'n13' ] ) ) {
	$wrapper_class[] = ' txt-below';
}

if ( in_array( $style_cscount, [ 'n12', 'n13' ] ) ) {
	$wrapper_class[] = ' no-spacing';
}

if ( in_array( $style_cscount, [ 'n14', 'n15' ] ) ) {
	$wrapper_class[] = ' black-ver';
}

if ( in_array( $style_cscount, [ 'n16', 'n17', 'n18', 'n19', 'n20' ] ) ) {
	$wrapper_class[] = ' border-style';
}

if ( in_array( $style_cscount, [ 'n16', 'n17', 'n18' ] ) ) {
	$wrapper_class[] = ' penci-social-textcolored';
}

if ( in_array( $style_cscount, [ 'n19', 'n20' ] ) ) {
	$wrapper_class[] = ' full-border';
}
$css_class       = vc_shortcode_custom_css_class( $settings['css'] );
$wrapper_class[] = ' ' . $css_class;

?>
    <div id="<?php echo $block_id; ?>"
         class="pcsb-share <?php echo esc_attr( implode( ' ', $wrapper_class ) ); ?> post-share<?php if ( $settings['penci__hide_share_plike'] ): echo ' hide-like-count'; endif; ?>">
		<?php
		if ( 's1' != $style_cscount ) {
			echo '<span class="penci-social-share-text">';
			echo '<i class="penciicon-sharing"></i>';
			echo penci_get_setting( 'penci_trans_share' ) ? do_shortcode( penci_get_setting( 'penci_trans_share' ) ) : 'Share';
			echo '</span>';
		}
		?>
		<?php if ( ! $settings['penci_single_meta_comment'] && 's1' == $style_cscount && ! is_page() ) : ?>
            <span class="single-comment-o"><?php penci_fawesome_icon( 'far fa-comment' ); ?><?php comments_number( '0 ' . penci_get_setting( 'penci_trans_comments' ), '1 ' . penci_get_setting( 'penci_trans_comment' ), '% ' . penci_get_setting( 'penci_trans_comments' ) ); ?></span>
		<?php endif; ?>

		<?php if ( ! $settings['penci__hide_share_plike'] && ! is_page() ): ?>
            <span class="post-share-item post-share-plike">
		            <?php echo penci_single_getPostLikeLink( get_the_ID() ); ?>
                    </span>
		<?php endif; ?>
		<?php penci_soledad_social_share(); ?>
    </div>
<?php
$css = [
	'penci_single_share_label' => [ '{{WRAPPER}} .penci-social-share-text' => 'display:none !important' ],
	'meta_align'               => [ '{{WRAPPER}} .tags-share-box' => 'text-align:{{VALUE}}' ],
	'comment_text_color'       => [
		'{{WRAPPER}} .single-comment-o'                       => 'color:{{VALUE}}',
		'{{WRAPPER}} .tags-share-box .single-comment-o:after' => 'background-color:{{VALUE}};opacity: 0.5;'
	],
	'label_color'              => [ '{{WRAPPER}} .pcnew-share .penci-social-share-text,{{WRAPPER}} .tags-share-box.tags-share-box-2_3 .penci-social-share-text' => 'color:{{VALUE}}' ],
	'label_icolor'             => [ '{{WRAPPER}} .pcnew-share .penci-social-share-text i,{{WRAPPER}} .tags-share-box.tags-share-box-2_3 .penci-social-share-text i' => 'color:{{VALUE}}' ],
	'label_bdcolor'            => [
		'{{WRAPPER}} .pcnew-share .penci-social-share-text'                 => 'border-color:{{VALUE}}',
		'{{WRAPPER}} .pcnew-share .penci-social-share-text:before'          => 'border-left-color:{{VALUE}}',
		'body.rtl {{WRAPPER}} .pcnew-share .penci-social-share-text:before' => 'border-left-color:transparent;border-right-color:{{VALUE}}',
	],
	'label_bgcolor'            => [
		'{{WRAPPER}} .pcnew-share .penci-social-share-text'                => 'background-color:{{VALUE}}',
		'{{WRAPPER}} .pcnew-share .penci-social-share-text:after'          => 'border-left-color:{{VALUE}};',
		'body.rtl {{WRAPPER}} .pcnew-share .penci-social-share-text:after' => 'border-right-color:{{VALUE}};',
	],
	'likebtn_bgcolor'          => [ '{{WRAPPER}} .tags-share-box.tags-share-box-2_3 .post-share-plike,{{WRAPPER}} .tags-share-box-n19.post-share a.penci-post-like,{{WRAPPER}} .tags-share-box-n20.post-share a.penci-post-like,{{WRAPPER}} .tags-share-box.tags-share-box-s2 .post-share-plike,{{WRAPPER}} .pcnew-share .post-share-item.post-share-plike' => 'background-color:{{VALUE}}' ],
	'likebtn_color'            => [ '{{WRAPPER}} .black-ver .post-share-plike i,{{WRAPPER}} .post-share.tags-share-box-2_3.post-share .count-number-like,{{WRAPPER}} .tags-share-box.tags-share-box-2_3.post-share .post-share-item .penci-post-like,{{WRAPPER}} .pcnew-share.penci-icon-full .post-share-item.post-share-plike i,{{WRAPPER}} .tags-share-box-n19.pcnew-share.border-style .post-share-item.post-share-plike i, {{WRAPPER}} .tags-share-box-n20.pcnew-share.border-style .post-share-item.post-share-plike i, {{WRAPPER}} .tags-share-box-n20 .post-share-item.post-share-plike i,{{WRAPPER}} .penci-social-textcolored .post-share-plike i.fa-heart-o, {{WRAPPER}} .tags-share-box.tags-share-box-s2 .post-share-plike .count-number-like,{{WRAPPER}} .tags-share-box.tags-share-box-s2 .post-share-plike .penci-post-like,{{WRAPPER}} .pcnew-share .post-share-item.post-share-plike .count-number-like,{{WRAPPER}} .pcnew-share .post-share-item.post-share-plike .penci-post-like' => 'color:{{VALUE}} !important' ],
	'likebtn_bcolor'           => [ '{{WRAPPER}} .tags-share-box.tags-share-box-s2 .post-share-plike,{{WRAPPER}} .pcnew-share .post-share-item.post-share-plike' => 'border-color:{{VALUE}}' ],
	'social_bgcolor'           => [ '{{WRAPPER}} a.post-share-item,{{WRAPPER}} .black-ver .post-share-item,{{WRAPPER}} .black-ver .post-share-item i' => 'background-color:{{VALUE}}' ],
	'social_bghcolor'          => [ '{{WRAPPER}} a.post-share-item:hover,{{WRAPPER}} .black-ver .post-share-item:hover,{{WRAPPER}} .black-ver .post-share-item:hover i' => 'background-color:{{VALUE}}' ],
	'social_color'             => [ '{{WRAPPER}} a.post-share-item,{{WRAPPER}} .black-ver .post-share-item i,{{WRAPPER}} .show-txt.post-share a .dt-share' => 'color:{{VALUE}}' ],
	'social_hcolor'            => [ '{{WRAPPER}} a.post-share-item:hover,{{WRAPPER}} .black-ver .post-share-item:hover i,{{WRAPPER}} .show-txt.post-share a:hover .dt-share' => 'color:{{VALUE}}' ],
	'social_bcolor'            => [ '{{WRAPPER}} a.post-share-item,{{WRAPPER}} .pcnew-share.penci-icon-full.border-style .post-share-item i' => 'border-color:{{VALUE}}' ],
	'social_bhcolor'           => [ '{{WRAPPER}} a.post-share-item:hover,{{WRAPPER}} .pcnew-share.penci-icon-full.border-style .post-share-item:hover i' => 'border-color:{{VALUE}}' ],
	'plus_btn_color'           => [ '{{WRAPPER}} a.post-share-expand,{{WRAPPER}} .black-ver .post-share-expand i,{{WRAPPER}} .tags-share-box.tags-share-box-2_3 .post-share-expand,{{WRAPPER}} .penci-social-colored .post-share-item.post-share-expand i, {{WRAPPER}} .tags-share-box.tags-share-box-s2 .post-share-item.post-share-expand i' => 'color:{{VALUE}} !important' ],
	'plus_btn_hcolor'          => [ '{{WRAPPER}} a.post-share-expand:hover,{{WRAPPER}} .black-ver .post-share-expand:hover i,{{WRAPPER}} .tags-share-box.tags-share-box-2_3 .post-share-expand:hover,{{WRAPPER}} .penci-social-colored .post-share-item.post-share-expand:hover i, {{WRAPPER}} .tags-share-box.tags-share-box-s2 .post-share-item.post-share-expand:hover i' => 'color:{{VALUE}} !important' ],
	'plus_btn_bgcolor'         => [ '{{WRAPPER}} a.post-share-expand,{{WRAPPER}} .black-ver .post-share-expand,{{WRAPPER}} .black-ver .post-share-expand i,{{WRAPPER}} .tags-share-box.tags-share-box-2_3 .post-share-expand,{{WRAPPER}} .penci-social-colored .post-share-item.post-share-expand i, {{WRAPPER}} .tags-share-box.tags-share-box-s2 .post-share-item.post-share-expand' => 'background-color:{{VALUE}} !important' ],
	'plus_btn_bghcolor'        => [ '{{WRAPPER}} a.post-share-expand:hover,{{WRAPPER}} .black-ver .post-share-expand:hover,{{WRAPPER}} .black-ver .post-share-expand:hover i,{{WRAPPER}} .tags-share-box.tags-share-box-2_3 .post-share-expand:hover,{{WRAPPER}} .penci-social-colored .post-share-item.post-share-expand:hover i, {{WRAPPER}} .tags-share-box.tags-share-box-s2 .post-share-item.post-share-expand:hover' => 'background-color:{{VALUE}} !important' ],
];
penci_wpbakery_el_extract_css( $css, $settings, '#' . $block_id );
