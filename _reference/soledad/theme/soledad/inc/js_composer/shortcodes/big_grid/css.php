<?php
$css = '';
$id  = '#' . esc_attr( $block_id );
$id2  = 'body:not(.pcdm-enable) #' . esc_attr( $block_id );

$css .= Penci_Vc_Helper::get_heading_block_css( $id, $atts );
$css .= Penci_Vc_Helper::get_heading_block_filter_css( $id, $atts );

if ( $bg_gap ) {
	$css .= $id . '.penci-bgstyle-1 .penci-dflex{margin-left: calc(-' . $bg_gap . 'px/2); margin-right: calc(-' . $bg_gap . 'px/2); width: calc(100% + ' . $bg_gap . 'px);}';
	$css .= $id . '.penci-bgstyle-2 .item-masonry, ' . $id . ' .penci-bgstyle-1 .penci-bgitem{padding-left: calc(' . $bg_gap . 'px/2); padding-right: calc(' . $bg_gap . 'px/2); margin-bottom: {' . $bg_gap . 'px}';
	$css .= $id . '.penci-bgstyle-2 .penci-biggrid-data{margin-left: calc(-' . $bg_gap . 'px/2); margin-right: calc(-' . $bg_gap . 'px/2);}';
}

if ( $bg_othergap ) {
	$css .= $id . ' .penci-biggrid{ --pcgap: ' . $bg_othergap . 'px; }';
}

if ( $penci_img_ratio ) {
	$css .= $id . ' .penci-bgitem .penci-image-holder:before{padding-top: ' . $penci_img_ratio . '%;}';
}

if ( $bg_height ) {
	$css .= $id . ' .penci-biggrid .penci-fixh{--bgh: ' . $bg_height . 'px;}';
}

if ( $paging_matop ) {
	$css .= $id . ' .penci-pagination{margin-top: ' . $paging_matop . 'px}';
}

if ( $content_horizontal_position ) {
	$content_horizontal_position_prop = array(
		'left'   => 'margin-right: auto',
		'center' => 'margin-left: auto; margin-right: auto;',
		'right'  => 'margin-left: auto',
	);
	$css                              .= $id . ' .pcbg-content-inner:{' . $content_horizontal_position_prop[ $content_horizontal_position ] . '}';
}

if ( $content_vertical_position ) {
	$content_vertical_position_prop = array(
		'top'    => 'flex-start',
		'middle' => 'center',
		'bottom' => 'flex-end',
	);
	$css                            .= $id . ' .pcbg-content-flex{' . $content_vertical_position_prop[ $content_vertical_position ] . '}';
}

if ( $content_text_align ) {
	$css .= $id . ' .pcbg-content-flex{text-align: ' . $content_text_align . '}';
}

if ( $content_width ) {
	$css .= $id . ' .pcbg-content-inner{max-width: ' . $content_width . '}';
}

if ( $content_padding ) {
	$css .= $id . ' .pcbg-content-inner{padding: ' . $content_padding . '}';
}

if ( $content_margin ) {
	$css .= $id . ' .pcbg-content-inner{margin: ' . $content_margin . '}';
}

if ( $overlay_opacity ) {
	$css .= $id . ' .penci-bgmain:hover .pcbg-bgoverlay.active-overlay{opacity: calc( ' . $overlay_opacity . '/100 )}';
	$css .= $id . ' .penci-bgmain:hover .pcbg-bgoverlaytext.active-overlay{opacity: calc( ' . $overlay_opacity . '/100 )}';
}

if ( $spe_bg_subtitle ) {
	$css .= $id2 . ' .penci-bgrid-based-post .cat > a.penci-cat-name{background-color: ' . $spe_bg_subtitle . ';}';
	$css .= $id2 . ' .penci-bgrid-based-custom .pcbg-sub-title{background-color: ' . $spe_bg_subtitle . '; border-color: ' . $spe_bg_subtitle . ';}';
}

if ( $spe_bg_hsubtitle ) {
	$css .= $id2 . ' .penci-bgrid-based-post .penci-bgitem:hover .cat > a.penci-cat-name{background-color: ' . $spe_bg_hsubtitle . ';}';
	$css .= $id2 . ' .penci-bgrid-based-custom .penci-bgitem:hover .pcbg-sub-title{background-color: ' . $spe_bg_hsubtitle . '; border-color: ' . $spe_bg_hsubtitle . ';}';
}

if ( $spe_bg_title ) {
	$css .= $id2 . ' .pcbg-title{background-color: ' . $spe_bg_title . '; border-color: ' . $spe_bg_title . ';}';
}

if ( $spe_bg_htitle ) {
	$css .= $id2 . ' .penci-bgitem:hover .pcbg-title{background-color: ' . $spe_bg_htitle . '; border-color: ' . $spe_bg_htitle . ';}';
}

if ( $spe_bg_meta ) {
	$css .= $id2 . ' .pcbg-meta-desc{background-color: ' . $spe_bg_meta . '; border-color: ' . $spe_bg_meta . ';}';
}

if ( $spe_bg_hmeta ) {
	$css .= $id2 . ' .penci-bgitem:hover .pcbg-meta-desc{background-color: ' . $spe_bg_hmeta . '; border-color: ' . $spe_bg_hmeta . ';}';
}

if ( $bgitem_bg ) {
	$css .= $id2 . ' .penci-biggrid .penci-bgitin{background-color: ' . $bgitem_bg . ';}';
}

if ( $bgitem_borders ) {
	$css .= $id2 . ' .penci-biggrid .penci-bgitin{border: 1px solid ' . $bgitem_borders . ';}';
}

if ( $bgitem_borderwidth ) {
	$css .= penci_extract_md_responsive_fsize( $id . ' .penci-biggrid .penci-bgitin{border-width:{{VALUE}}px;}', $bgitem_borderwidth );
}

if ( $bgitem_padding ) {
	$css .= $id . ' .penci-biggrid .penci-bgitin{padding: ' . $bgitem_padding . ';}';
}

if ( $bgsub_title ) {
	$css .= $id2 . ' .penci-bgrid-based-custom .pcbg-content-inner .pcbg-above{color: ' . $bgsub_title . ';}';
	$css .= $id2 . ' .pcbg-content-inner .pcbg-above span{color: ' . $bgsub_title . ';}';
	$css .= $id2 . ' .pcbg-content-inner .pcbg-above span a{color: ' . $bgsub_title . ';}';
}

if ( $bgsub_title_hover ) {
	$css .= $id2 . ' .pcbg-content-inner .pcbg-above a:hover{color: ' . $bgsub_title_hover . ';}';
}

if ( $bgtitle_color ) {
	$css .= $id2 . ' .pcbg-content-inner .pcbg-title a,' . $id . ' .pcbg-content-inner .pcbg-title{color: ' . $bgtitle_color . ';}';
}

if ( $bgtitle_color_hover ) {
	$css .= $id2 . ' .pcbg-content-inner .pcbg-title a:hover{color: ' . $bgtitle_color_hover . ';}';
}

if ( $bgdesc_color ) {
	$css .= $id2 . ' .pcbg-content-inner .pcbg-meta{color: ' . $bgdesc_color . ';}';
	$css .= $id2 . ' .pcbg-content-inner .pcbg-meta span{color: ' . $bgdesc_color . ';}';
}

if ( $bgdesc_link_color ) {
	$css .= $id2 . ' .pcbg-content-inner .pcbg-meta a{color: ' . $bgdesc_link_color . ';}';
	$css .= $id2 . ' .pcbg-content-inner .pcbg-meta span a{color: ' . $bgdesc_link_color . ';}';
}

if ( $bgdesc_link_hcolor ) {
	$css .= $id . ' .pcbg-content-inner .pcbg-meta a:hover{color: ' . $bgdesc_link_hcolor . ';}';
	$css .= $id . ' .pcbg-content-inner .pcbg-meta span a:hover{color: ' . $bgdesc_link_hcolor . ';}';
}

if ( $readmore_color ) {
	$css .= $id . ' .pcbg-readmore-sec .pcbg-readmorebtn{color: ' . $readmore_color . ';}';
}

if ( $readmore_hcolor ) {
	$css .= $id . ' .pcbg-readmore-sec .pcbg-readmorebtn:hover{color: ' . $readmore_hcolor . ';}';
}

if ( $bgreadmore_color ) {
	$css .= $id . ' .pcbg-readmore-sec .pcbg-readmorebtn{border: 1px solid ' . $bgreadmore_color . ';}';
}

if ( $bgreadmore_hcolor ) {
	$css .= $id2 . ' .pcbg-readmore-sec .pcbg-readmorebtn:hover{border-color: ' . $bgreadmore_hcolor . ';}';
}

if ( $bgreadmore_bgcolor ) {
	$css .= $id2 . ' .pcbg-readmore-sec .pcbg-readmorebtn{background-color: ' . $bgreadmore_bgcolor . ';}';
}

if ( $bgreadmore_hbgcolor ) {
	$css .= $id2 . ' .pcbg-readmore-sec .pcbg-readmorebtn:hover{background-color: ' . $bgreadmore_hbgcolor . ';}';
}

if ( $bgreadmore_borderwidth ) {
	$css .= $id . ' .pcbg-readmore-sec .pcbg-readmorebtn{border-width: ' . $bgreadmore_borderwidth . ';}';
}

if ( $bgreadmore_borderradius ) {
	$css .= $id . ' .pcbg-readmore-sec .pcbg-readmorebtn{border-radius: ' . $bgreadmore_borderwidth . ';}';
}

if ( $bgreadmore_padding ) {
	$css .= $id . ' .pcbg-readmore-sec .pcbg-readmorebtn{padding: ' . $bgreadmore_padding . ';}';
}

if ( $pagi_mwidth ) {
	$css .= $id . ' .penci-pagination.penci-ajax-more a.penci-ajax-more-button{max-width: ' . $pagi_mwidth . ';}';
}

if ( $pagi_color ) {
	$css .= $id2 . ' .penci-pagination a{color: ' . $pagi_color . ';}';
	$css .= $id2 . ' .penci-pagination ul.page-numbers li a{color: ' . $pagi_color . ';}';
}

if ( $pagi_hcolor ) {
	$css .= $id2 . ' .penci-pagination a:hover{color: ' . $pagi_hcolor . ';}';
	$css .= $id2 . ' .penci-pagination ul.page-numbers li a:hover{color: ' . $pagi_hcolor . ';}';
	$css .= $id2 . ' .penci-pagination ul.page-numbers li span.current{color: ' . $pagi_hcolor . ';}';
}

if ( $bgpagi_color ) {
	$css .= $id2 . ' .penci-pagination a{border-color: ' . $bgpagi_color . ';}';
	$css .= $id2 . ' .penci-pagination ul.page-numbers li a{border-color: ' . $bgpagi_color . ';}';
}

if ( $bgpagi_hcolor ) {
	$css .= $id2 . ' .penci-pagination a:hover{border-color: ' . $bgpagi_hcolor . ';}';
	$css .= $id2 . ' .penci-pagination ul.page-numbers li a:hover{border-color: ' . $bgpagi_hcolor . ';}';
	$css .= $id2 . ' .penci-pagination ul.page-numbers li span.current{border-color: ' . $bgpagi_hcolor . ';}';
}

if ( $bgpagi_bgcolor ) {
	$css .= $id2 . ' .penci-pagination a{background-color: ' . $bgpagi_bgcolor . ';}';
	$css .= $id2 . ' .penci-pagination ul.page-numbers li a{background-color: ' . $bgpagi_bgcolor . ';}';
}

if ( $bgpagi_hbgcolor ) {
	$css .= $id2 . ' .penci-pagination a:hover{background-color: ' . $bgpagi_hbgcolor . ';}';
	$css .= $id2 . ' .penci-pagination ul.page-numbers li a:hover{background-color: ' . $bgpagi_hbgcolor . ';}';
	$css .= $id2 . ' .penci-pagination ul.page-numbers li span.current{background-color: ' . $bgpagi_hbgcolor . ';}';
}

if ( $bgpagi_borderwidth ) {
	$css .= $id . ' ul.page-numbers li a{border-width: ' . $bgpagi_borderwidth . ';}';
	$css .= $id . ' ul.page-numbers span.current{border-width: ' . $bgpagi_borderwidth . ';}';
	$css .= $id . ' .penci-pagination a{border-width: ' . $bgpagi_borderwidth . ';}';
}

if ( $bgpagi_borderradius ) {
	$css .= $id . ' ul.page-numbers li a{border-radius: ' . $bgpagi_borderradius . ';}';
	$css .= $id . ' ul.page-numbers span.current{border-radius: ' . $bgpagi_borderradius . ';}';
	$css .= $id . ' .penci-pagination a{border-radius: ' . $bgpagi_borderradius . ';}';
}

if ( $bgpagi_padding ) {
	$css .= $id . ' ul.page-numbers li a{padding: ' . $bgpagi_padding . ';}';
	$css .= $id . ' ul.page-numbers span.current{padding: ' . $bgpagi_padding . ';}';
	$css .= $id . ' .penci-pagination a{padding: ' . $bgpagi_padding . ';}';
}


if ( $bgsub_title_typo ) {
	$css .= $id . ' .penci-bgrid-based-custom .pcbg-content-inner .pcbg-above, ' . $id . ' .pcbg-content-inner .pcbg-above span,' . $id . ' .pcbg-content-inner .pcbg-above span a{' . penci_soledad_vc_extract_font_prop( $bgsub_title_typo ) . '}';
}

if ( $bgtitle_typo_big ) {
	$css .= $id . ' .pcbg-big-item .pcbg-content-inner .pcbg-title,' . $id . ' .pcbg-big-item .pcbg-content-inner .pcbg-title a{' . penci_soledad_vc_extract_font_prop( $bgtitle_typo_big ) . '}';
}

if ( $bgtitle_typo ) {
	$css .= $id . ' .pcbg-content-inner .pcbg-title,' . $id . ' .pcbg-content-inner .pcbg-title a{' . penci_soledad_vc_extract_font_prop( $bgtitle_typo ) . '}';
}

if ( $title_typo ) {
	$css .= $id . ' .pcbg-content-inner .pcbg-meta,' . $id . ' .pcbg-content-inner .pcbg-meta span, ' . $id . ' .pcbg-content-inner .pcbg-meta a{' . penci_soledad_vc_extract_font_prop( $title_typo ) . '}';
}

if ( $bgreadm_typo ) {
	$css .= $id . ' .pcbg-readmore-sec .pcbg-readmorebtn{' . penci_soledad_vc_extract_font_prop( $bgreadm_typo ) . '}';
}

if ( $bgreadm_typo ) {
	$css .= $id . ' .pcbg-readmore-sec .pcbg-readmorebtn{' . penci_soledad_vc_extract_font_prop( $bgreadm_typo ) . '}';
}

if ( $bgpagi_typo ) {
	$css .= $id . ' .penci-pagination a, ' . $id . ' .penci-pagination span.current{' . penci_soledad_vc_extract_font_prop( $bgpagi_typo ) . '}';
}
if ( $responsive_spacing ) {
	$css .= penci_extract_spacing_style( $id, $responsive_spacing );
}

$css .= Penci_Vc_Helper::get_bookmark_icon_css( $id, $atts );

if ( $css ) {
	echo '<style id="penci-vc_big_grid_custom_css">';
	echo $css;
	echo '</style>';
}
