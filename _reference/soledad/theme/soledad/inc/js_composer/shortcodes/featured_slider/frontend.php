<?php
$responsive_spacing = $cspost_enable = $cspost_cpost_meta = $cspost_cpost_acf_meta = $cspost_cpost_meta_label = $cspost_cpost_meta_divider = '';

$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );

$block_id = Penci_Vc_Helper::get_unique_id_block( 'featured_pslider' );

$query_args = penci_build_args_query( $atts['build_query'] );
$feat_query = new \WP_Query( $query_args );

if ( ! $feat_query->have_posts() ) {
	if ( is_user_logged_in() ) {
		echo '<div class="penci-missing-settings">';
		echo '<span>Featured Slider</span>';
		echo penci_get_setting( 'penci_ajaxsearch_no_post' );
		echo '</div>';
	}
}
$slider_style = $atts['style'] ? $atts['style'] : 'style-1';

$slider_class = $slider_style;
if ( $slider_style == 'style-5' ) {
	$slider_class = 'style-4 style-5';
} elseif ( $slider_style == 'style-30' ) {
	$slider_class = 'style-29 style-30';
} elseif ( $slider_style == 'style-36' ) {
	$slider_class = 'style-35 style-36';
}

if ( $atts['enable_flat_overlay'] && in_array( $slider_style, array(
		'style-6',
		'style-7',
		'style-8',
		'style-9',
		'style-10',
		'style-11',
		'style-12',
		'style-13',
		'style-14',
		'style-15',
		'style-16',
		'style-17',
		'style-18',
		'style-19',
		'style-20',
		'style-21',
		'style-22',
		'style-23',
		'style-24',
		'style-25',
		'style-26',
		'style-27',
		'style-28'
	) ) ) {
	$slider_class .= ' penci-flat-overlay';
}

$disable_lazyload    = 'yes' == $atts['disable_lazyload_slider'];
$slider_title_length = $atts['title_length'] ? $atts['title_length'] : 12;
$center_box          = 'yes' == $atts['center_box'];
$meta_date_hide      = 'yes' == $atts['meta_date_hide'];
$hide_categories     = 'yes' == $atts['hide_categories'];
$show_cat            = 'yes' == $atts['show_cat'];
$hide_meta_comment   = 'yes' == $atts['hide_meta_comment'];
$show_meta_author    = 'yes' == $atts['show_meta_author'];
$hide_meta_excerpt   = 'yes' == $atts['hide_meta_excerpt'];
$hide_format_icons   = 'yes' == $atts['hide_format_icons'];

$post_thumb_size        = $atts['post_thumb_size'];
$bpost_thumb_size       = $atts['bpost_thumb_size'];
$post_thumb_size_mobile = $atts['post_thumb_size_mobile'];

$slider_data = 'data-style="' . $slider_style . '"';
$slider_data .= ' data-auto="' . ( 'yes' == $atts['autoplay'] ? 'true' : 'false' ) . '"';
$slider_data .= ' data-autotime="' . ( $atts['auto_time'] ? intval( $atts['auto_time'] ) : '4000' ) . '"';
$slider_data .= ' data-speed="' . ( $atts['speed'] ? intval( $atts['speed'] ) : '600' ) . '"';
$slider_data .= ' data-loop="' . ( 'yes' == $atts['loop'] ? 'true' : 'false' ) . '"';
$slider_data .= ' data-ceffect="' . $atts['carousel_slider_effect'] . '"';
$slider_data .= ' data-seffect="' . $atts['single_slider_effect'] . '"';

if ( $slider_style == 'style-7' || $slider_style == 'style-8' ) {
	$slider_data .= ' data-item="4" data-desktop="4" data-tablet="2" data-tabsmall="1"';
} elseif ( $slider_style == 'style-9' || $slider_style == 'style-10' ) {
	$slider_data .= ' data-item="3" data-desktop="3" data-tablet="2" data-tabsmall="1"';
} elseif ( $slider_style == 'style-11' || $slider_style == 'style-12' ) {
	$slider_data .= ' data-item="2" data-desktop="2" data-tablet="2" data-tabsmall="1"';
} elseif ( in_array( $slider_style, array( 'style-35', 'style-37' ) ) ) {
	$data_next_prev = 'yes' == $atts['shownav'] ? 'true' : 'false';
	$data_dots      = 'yes' == $atts['showdots'] ? 'true' : 'false';
	$slider_data    .= ' data-dots="' . $data_dots . '" data-nav="' . $data_next_prev . '"';
}
$slider_lib = 'penci-owl-featured-area';

$slider_lib .= ' elsl-' . $slider_style;

$swiper = true;

if ( $slider_style == 'style-40' ) {
	wp_enqueue_script( 'ff40' );
	wp_enqueue_script( 'gsap' );
	$slider_lib .= ' no-df-swiper';
	$swiper     = false;
}

if ( $slider_style == 'style-41' || $slider_style == 'style-42' || $slider_style == 'style-44' ) {
	$slider_lib .= ' no-df-swiper';
	$swiper     = false;
}

if ( $swiper ) {
	$slider_lib .= ' swiper penci-owl-carousel';
}
?>
    <div id="<?php echo esc_attr( $block_id ) ?>">
		<?php
		echo '<div class="penci-block-el featured-area featured-' . $slider_class . '">';
		if ( $slider_style == 'style-37' ):
			echo '<div class="penci-featured-items-left">';
		endif;
		echo '<div class="' . $slider_lib . ' penci-owl-featured-area elsl-' . $slider_class . '"' . $slider_data . '>';
		if ( $swiper ) {
			if ( 'yes' == $atts['shownav'] ) {
				echo '<div class="penci-owl-nav"><div class="owl-prev"><i class="penciicon-left-chevron"></i></div><div class="owl-next"><i class="penciicon-right-chevron"></i></div></div>';
			}
			echo '<div class="swiper-wrapper">';
		}
		include dirname( __FILE__ ) . "/{$slider_style}.php";
		if ( $swiper && $slider_style != 'style-37' ) {
			echo '</div>';
		}
		echo '</div>';
		echo '</div>';
		?>
    </div>
<?php
$block_id_css = '#' . $block_id;
$css_custom   = '';

if ( ! empty( $atts['img_border_radius'] ) ) {
	$css_custom .= $block_id_css . ' .penci-slider38-overlay,';
	$css_custom .= $block_id_css . ' .featured-style-29 .featured-slider-overlay,';
	$css_custom .= $block_id_css . ' .featured-area .penci-slide-overlay .overlay-link,';
	$css_custom .= $block_id_css . ' .featured-area .penci-image-holder';
	$css_custom .= $block_id_css . ' .featured-area .penci-image-holder{ border-radius: ' . esc_attr( $atts['img_border_radius'] ) . ';-webkit-border-radius: ' . esc_attr( $atts['img_border_radius'] ) . '; }';

	$css_custom .= $block_id_css . ' .penci-featured-content-right:before{border-top-right-radius: ' . esc_attr( $atts['img_border_radius'] ) . ';border-bottom-right-radius: ' . esc_attr( $atts['img_border_radius'] ) . '; }';
	$css_custom .= $block_id_css . ' .penci-flat-overlay .penci-slide-overlay .penci-mag-featured-content:before{ border-bottom-left-radius: ' . esc_attr( $atts['img_border_radius'] ) . ';border-bottom-right-radius: ' . esc_attr( $atts['img_border_radius'] ) . '; }';
}

if ( ! empty( $atts['img_ratio'] ) ) {
	$css_custom .= $block_id_css . ' .penci-owl-carousel:not(.elsl-style-19):not(.elsl-style-27) .penci-image-holder{ height: auto !important; }';
	$css_custom .= $block_id_css . ' .penci-owl-carousel:not(.elsl-style-19):not(.elsl-style-27) .penci-image-holder:before{ content:"";padding-top:' . esc_attr( $atts['img_ratio'] ) . ';height: auto; }';

	$css_custom .= $block_id_css . ' .featured-style-13 .penci-owl-carousel .penci-item-1 .penci-image-holder:before{ padding-top:calc( ' . esc_attr( $atts['img_ratio'] ) . ' / 2 ); }';
	$css_custom .= $block_id_css . ' .featured-style-15 .penci-owl-carousel .penci-item-2 .penci-image-holder:before{ padding-top:calc( ' . esc_attr( $atts['img_ratio'] ) . ' / 2 ); }';
	$css_custom .= $block_id_css . ' .featured-style-25 .penci-owl-carousel .penci-item-1 .penci-image-holder:before{ padding-top:calc( ' . esc_attr( $atts['img_ratio'] ) . ' * 3/2 ); }';
}

// Title
if ( ! empty( $atts['title_color'] ) ) {
	$css_custom .= $block_id_css . ' .feat-text-right h3,';
	$css_custom .= $block_id_css . ' .feat-text-right h3 a,';
	$css_custom .= $block_id_css . ' .feat-text h3 a,';
	$css_custom .= $block_id_css . ' .feat-text h3{ color:' . esc_attr( $atts['title_color'] ) . ' !important; }';
}

if ( ! empty( $atts['title_hcolor'] ) ) {
	$css_custom .= $block_id_css . ' .feat-text h3 a:hover,';
	$css_custom .= $block_id_css . ' .feat-text-right h3 a:hover{ color:' . esc_attr( $atts['title_hcolor'] ) . ' !important; }';
}

$css_custom .= Penci_Vc_Helper::vc_google_fonts_parse_attributes( array(
	'font_size'  => $atts['ptitle_fsize'],
	'font_style' => 'yes' == $atts['use_ptitle_typo'] ? $atts['ptitle_typo'] : '',
	'template'   => "{$block_id_css} .feat-text h3, {$block_id_css} .feat-text h3 a,{$block_id_css} .feat-text-right h3, {$block_id_css} .feat-text-right h3 a{ %s }",
) );
if ( ! empty( $atts['bptitle_fsize'] ) ) {
	$css_custom .= $block_id_css . ' .featured-area .penci-pitem-big .feat-text h3,';
	$css_custom .= $block_id_css . ' .featured-area .penci-pitem-big .feat-text h3 a{ color:' . esc_attr( $atts['bptitle_fsize'] ) . ' !important; }';
}

// Category
if ( ! empty( $atts['pcat_color'] ) ) {
	$css_custom .= $block_id_css . ' .feat-text .featured-cat a,';
	$css_custom .= $block_id_css . ' .featured-style-35 .featured-cat a{ color:' . esc_attr( $atts['pcat_color'] ) . ' !important; }';
}

if ( ! empty( $atts['pcat_hcolor'] ) ) {
	$css_custom .= $block_id_css . ' .feat-text .featured-cat a:hover,';
	$css_custom .= $block_id_css . ' .featured-style-35 .featured-cat a:hover{ color:' . esc_attr( $atts['pcat_color'] ) . ' !important; }';
}

$css_custom .= Penci_Vc_Helper::vc_google_fonts_parse_attributes( array(
	'font_size'  => $atts['pcat_fsize'],
	'font_style' => 'yes' == $atts['use_pcat_typo'] ? $atts['pcat_typo'] : '',
	'template'   => "{$block_id_css} .feat-text .featured-cat a, {$block_id_css} .featured-style-35 .featured-cat a{ %s }",
) );

// Meta
if ( ! empty( $atts['pmeta_color'] ) ) {
	$css_custom .= $block_id_css . ' .feat-text .feat-meta span,';
	$css_custom .= $block_id_css . ' .feat-text .feat-meta a,';
	$css_custom .= $block_id_css . ' .featured-content-excerpt .feat-meta span,';
	$css_custom .= $block_id_css . ' .featured-content-excerpt .feat-meta span a{ color:' . esc_attr( $atts['pmeta_color'] ) . ' !important; }';
}

if ( ! empty( $atts['pmeta_hcolor'] ) ) {
	$css_custom .= $block_id_css . ' .feat-text .feat-meta a:hover,';
	$css_custom .= $block_id_css . ' .featured-content-excerpt .feat-meta span a:hover{ color:' . esc_attr( $atts['pmeta_hcolor'] ) . ' !important; }';
}

$pmeta_custom_markup = $block_id_css . ' .feat-text .feat-meta span,';
$pmeta_custom_markup .= $block_id_css . ' .feat-text .feat-meta a,';
$pmeta_custom_markup .= $block_id_css . ' .featured-content-excerpt .feat-meta span,';
$pmeta_custom_markup .= $block_id_css . ' .featured-content-excerpt .feat-meta span a';

$css_custom .= Penci_Vc_Helper::vc_google_fonts_parse_attributes( array(
	'font_size'  => $atts['pmeta_fsize'],
	'font_style' => 'yes' == $atts['use_pmeta_typo'] ? $atts['pmeta_typo'] : '',
	'template'   => $pmeta_custom_markup . "{ %s }",
) );

// Excerpt
if ( ! empty( $atts['pexcerpt_color'] ) ) {
	$css_custom .= $block_id_css . ' .featured-content-excerpt p';
	$css_custom .= $block_id_css . ' .featured-slider-excerpt p{ color:' . esc_attr( $atts['pexcerpt_color'] ) . ' !important; }';
}

$css_custom .= Penci_Vc_Helper::vc_google_fonts_parse_attributes( array(
	'font_size'  => $atts['pexcerpt_fsize'],
	'font_style' => $atts['use_pexcerpt_typo'] ? $atts['pexcerpt_typo'] : '',
	'template'   => "{$block_id_css} .featured-content-excerpt p, {$block_id_css} .featured-slider-excerpt p{ %s }",
) );

//  Read More
if ( ! empty( $atts['readmore_color'] ) ) {
	$css_custom .= $block_id_css . ' .penci-featured-slider-button a{ color:' . esc_attr( $atts['readmore_color'] ) . ' !important;border-color:' . esc_attr( $atts['readmore_color'] ) . ' !important; }';
}

if ( ! empty( $atts['readmore_hcolor'] ) ) {
	$css_custom .= $block_id_css . ' .penci-featured-slider-button a:hover{ color:' . esc_attr( $atts['readmore_hcolor'] ) . ' !important; }';
}
if ( ! empty( $atts['readmore_hbgcolor'] ) ) {
	$css_custom .= $block_id_css . ' .penci-featured-slider-button a:hover{ border-color:' . esc_attr( $atts['readmore_hbgcolor'] ) . ' !important;background-color:' . esc_attr( $atts['readmore_hbgcolor'] ) . ' !important; }';
}

$css_custom .= Penci_Vc_Helper::vc_google_fonts_parse_attributes( array(
	'font_size'  => $atts['readmore_fsize'],
	'font_style' => $atts['use_readmore_typo'] ? $atts['readmore_typo'] : '',
	'template'   => "{$block_id_css} .penci-featured-slider-button a{ %s }",
) );

if ( isset( $atts['speed'] ) && $atts['speed'] ) {
	$delay_time = $atts['speed'] / 1000 - 0.2;
	$css_custom .= $block_id_css . ' .penci-owl-carousel{--pcfs-delay:' . $delay_time . 's;}';
}

if ( $responsive_spacing ) {
	$css_custom .= penci_extract_spacing_style( $block_id_css, $responsive_spacing );
}

$css_custom .= Penci_Vc_Helper::get_bookmark_icon_css( $block_id_css, $atts );


if ( $css_custom ) {
	echo '<style>';
	echo $css_custom;
	echo '</style>';
}
