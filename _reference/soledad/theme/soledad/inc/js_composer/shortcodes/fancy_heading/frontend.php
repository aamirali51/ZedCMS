<?php
/**
 * Shortcode attributes
 * @var $atts
 * @var $_text_align
 * @var $p_subtitle
 * @var $subtitle_tag
 * @var $subtitle_pos
 * @var $subtitle_margin_top
 * @var $subtitle_margin_bottom
 * @var $subtitle_width
 * @var $p_title
 * @var $title_tag
 * @var $turn_on_title
 * @var $_use_separator
 * @var $separator_style
 * @var $add_separator_icon
 * @var $separator_border_width
 * @var $separator_width
 * @var $separator_margin_top
 * @var $content_margin_top
 * @var $content_width
 * @var $content
 * @var $p_icon
 * @var $icon_size
 * @var $p_icon_martop
 * @var $p_icon_marlr
 * @var $title_color
 * @var $_title_typo
 * @var $_title_fsize
 * @var $title_md_fsize
 * @var $title_sm_fsize
 * @var $title_xs_fsize
 * @var $subtitle_color
 * @var $_subtitle_typo
 * @var $_subtitle_fsize
 * @var $_separator_icon_color
 * @var $_separator_border_color
 * @var $_content_color
 * @var $_desc_typo
 * @var $_desc_fsize
 * @var $css_animation
 * @var $el_class
 * @var $css
 */

$_text_align            = $p_subtitle = $subtitle_tag = $subtitle_pos = '';
$subtitle_margin_top    = $subtitle_margin_bottom = $subtitle_width = $p_title = '';
$title_tag              = $turn_on_title = $_use_separator = $separator_style = $add_separator_icon = '';
$separator_border_width = $separator_width = $separator_margin_top = $content_margin_top = '';
$content_width          = $p_icon = $icon_size = $p_icon_martop = $p_icon_marlr = '';
$title_color            = $_title_typo = $_title_fsize = $title_md_fsize = $title_sm_fsize = $title_xs_fsize = '';
$subtitle_color         = $_subtitle_typo = $_subtitle_fsize = $_separator_icon_color = $_separator_border_color = '';
$_content_color         = $_desc_typo = $_desc_fsize = $el_class = $css_animation = $css = '';
$title_link             = $responsive_spacing = '';

$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );

$class_to_filter = '';
$class_to_filter .= vc_shortcode_custom_css_class( $css, ' ' ) . $this->getExtraClass( $el_class ) . $this->getCSSAnimation( $css_animation );

$css_class = 'penci-fancy-heading penci-heading-text-' . $_text_align;
$css_class .= ' ' . apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $class_to_filter, $this->settings['base'], $atts );


$block_id = Penci_Vc_Helper::get_unique_id_block( 'fancy_heading' );
?>
<div id="<?php echo esc_attr( $block_id ); ?>" class="<?php echo esc_attr( $css_class ); ?>">
    <div class="penci-fancy-heading-inner">
		<?php
		$markup_subtitle = '';
		if ( $p_subtitle ) {
			$markup_subtitle = '<' . esc_attr( $subtitle_tag ) . ' class="penci-heading-subtitle">' . $p_subtitle . '</' . esc_attr( $subtitle_tag ) . '>';
		}

		if ( $markup_subtitle && 'above' == $subtitle_pos ) {
			echo $markup_subtitle;
		}

		if ( $p_title ) {

			$p_title_link = $p_title;
			if ( ! empty( $title_link ) ) {
				$_title_link  = vc_build_link( $title_link );
				$p_title_link = '<a href="' . esc_url( $_title_link['url'] ) . '"' . ( $_title_link['target'] ? ' target="' . esc_attr( $_title_link['target'] ) . '"' : '' ) . ( $_title_link['rel'] ? ' rel="' . esc_attr( $_title_link['rel'] ) . '"' : '' ) . '>' . $p_title . '</a>';
			}

			echo '<' . esc_attr( $title_tag ) . ' class="penci-heading-title">' . $p_title_link . '</' . esc_attr( $title_tag ) . '>';
		}
		if ( $markup_subtitle && 'below' == $subtitle_pos ) {
			echo $markup_subtitle;
		}

		if ( 'yes' == $_use_separator ) {
			echo '<div class="penci-separator penci-separator-' . esc_attr( $separator_style ) . ' penci-separator-' . $_text_align . '">';
			echo '<span class="penci-sep_holder penci-sep_holder_l"><span class="penci-sep_line"></span></span>';

			if ( 'yes' == $add_separator_icon ) {
				echo '<span class="penci-heading-icon ' . esc_attr( $p_icon ? $p_icon : 'fa fa-adjust' ) . '"></span>';
			}

			echo '<span class="penci-sep_holder penci-sep_holder_r"><span class="penci-sep_line"></span></span>';
			echo '</div>';
		}

		if ( $markup_subtitle && 'belowline' == $subtitle_pos ) {
			echo $markup_subtitle;
		}

		if ( $content ) {
			$content = wpautop( preg_replace( '/<\/?p\>/', "\n", $content ) . "\n" );
			$content = do_shortcode( shortcode_unautop( $content ) );

			echo '<div class="penci-heading-content entry-content">' . $content . '</div>';
		}
		?>
    </div>
	<?php
	$id_fancy_heading = '#' . $block_id;
	$id_fancy_heading2 = 'body:not(.pcdm-enable) #' . $block_id;
	$css_custom       = '';

	// Margin
	if ( $subtitle_margin_top ) {
		$css_custom .= $id_fancy_heading . '.penci-fancy-heading .penci-heading-subtitle{ margin-top: ' . esc_attr( $subtitle_margin_top ) . '; }';
	}
	if ( $subtitle_margin_bottom ) {
		$css_custom .= $id_fancy_heading . '.penci-fancy-heading .penci-heading-subtitle{ margin-bottom: ' . esc_attr( $subtitle_margin_bottom ) . '; }';
	}

	if ( $subtitle_width ) {
		$css_custom .= $id_fancy_heading . '.penci-fancy-heading .penci-heading-subtitle{ max-width: ' . esc_attr( $subtitle_width ) . '; }';
	}

	if ( $separator_margin_top ) {
		$css_custom .= $id_fancy_heading . ' .penci-separator{ margin-top: ' . esc_attr( $separator_margin_top ) . '; }';
	}

	if ( $content_margin_top ) {
		$css_custom .= $id_fancy_heading . '.penci-fancy-heading .penci-heading-content{ margin-top: ' . esc_attr( $content_margin_top ) . '; }';
	}
	$css_custom .= $id_fancy_heading . '.penci-fancy-heading .penci-heading-content{ margin-bottom: 0; }';

	if ( $content_width ) {
		$css_custom .= $id_fancy_heading . '.penci-fancy-heading .penci-heading-content{ max-width: ' . esc_attr( $content_width ) . ';width: 100%; }';
	}

	if ( $separator_width ) {
		$css_custom .= $id_fancy_heading . ' .penci-separator{ width: ' . esc_attr( $separator_width ) . '; }';
	}

	if ( $atts['separator_border_width'] && $atts['separator_border_width'] > 1 ) {
		$css_custom .= $id_fancy_heading . ' .penci-separator .penci-sep_line{ border-width: ' . esc_attr( $atts['separator_border_width'] ) . 'px; top: -' . ( intval( $atts['separator_border_width'] ) / 2 ) . 'px; }';

		if ( 'double' == $atts['separator_style'] ) {
			$height_separator_pre = ( intval( $atts['separator_border_width'] ) * 2 ) + 4;
			$css_custom           .= $id_fancy_heading . ' .penci-separator.penci-separator-double{ height: ' . $height_separator_pre . 'px;}';
			$css_custom           .= $id_fancy_heading . ' .penci-separator.penci-separator-double:before,' . $id_fancy_heading . ' .penci-separator.penci-separator-double:after{ border-top-width: ' . esc_attr( $atts['separator_border_width'] ) . 'px;}';
		}
	}

	if ( $atts['separator_style'] ) {
		$css_custom .= $id_fancy_heading . ' .penci-separator .penci-sep_line{ border-top-style: ' . esc_attr( $atts['separator_style'] ) . '; }';
	}

	// Color
	if ( 'yes' == $atts['turn_on_title'] ) {
		$css_custom .= $id_fancy_heading . '.penci-fancy-heading .penci-heading-title{ text-transform: uppercase; }';
	}

	if ( $atts['title_color'] ) {
		$css_custom .= $id_fancy_heading2 . '.penci-fancy-heading .penci-heading-title a,';
		$css_custom .= $id_fancy_heading2 . '.penci-fancy-heading .penci-heading-title{ color: ' . esc_attr( $atts['title_color'] ) . '; }';
	}
	if ( $atts['title_hcolor'] ) {
		$css_custom .= $id_fancy_heading2 . '.penci-fancy-heading .penci-heading-title a:hover{ color: ' . esc_attr( $atts['title_hcolor'] ) . '; }';
	}

	if ( $atts['subtitle_color'] ) {
		$css_custom .= $id_fancy_heading2 . '.penci-fancy-heading .penci-heading-subtitle{ color: ' . esc_attr( $atts['subtitle_color'] ) . '; }';
	}
	if ( $atts['_content_color'] ) {
		$css_custom .= $id_fancy_heading2 . '.penci-fancy-heading .penci-heading-content{ color: ' . esc_attr( $atts['_content_color'] ) . '; }';
	}
	if ( $atts['_separator_border_color'] ) {
		$css_custom .= $id_fancy_heading2 . ' .penci-separator .penci-sep_line{ border-color: ' . esc_attr( $atts['_separator_border_color'] ) . '; }';
		$css_custom .= $id_fancy_heading2 . ' .penci-separator.penci-separator-double:before,' . $id_fancy_heading . ' .penci-separator.penci-separator-double:after{ border-color: ' . esc_attr( $atts['_separator_border_color'] ) . '; }';
	}

	// Icon
	$css_custom_icon = '';
	if ( $atts['icon_size'] ) {
		$css_custom_icon .= 'font-size: ' . esc_attr( $atts['icon_size'] ) . ';';
	}

	if ( $atts['p_icon_martop'] ) {
		$css_custom_icon .= 'margin-top: ' . esc_attr( $atts['p_icon_martop'] ) . '; margin-bottom: ' . esc_attr( $atts['p_icon_martop'] ) . ';';
	}

	if ( $atts['p_icon_marlr'] ) {
		$css_custom_icon .= 'margin-left: ' . esc_attr( $atts['p_icon_marlr'] ) . '; margin-right: ' . esc_attr( $atts['p_icon_marlr'] ) . ';';
	}

	if ( $atts['_separator_icon_color'] ) {
		$css_custom_icon .= 'color: ' . esc_attr( $atts['_separator_icon_color'] ) . ';';
	}

	if ( $css_custom_icon ) {
		$css_custom .= $id_fancy_heading . ' .penci-separator .penci-heading-icon{ ' . ( $css_custom_icon ) . ' }';
	}

	if ( $atts['title_md_fsize'] ) {
		$css_custom .= '@media screen and (min-width: 769px) and (max-width: 1024px){';
		$css_custom .= $id_fancy_heading . '.penci-fancy-heading .penci-heading-title{ font-size: ' . esc_attr( $atts['title_md_fsize'] ) . '; } }';
	}

	if ( $atts['title_sm_fsize'] ) {
		$css_custom .= '@media screen and (min-width: 481px) and (max-width: 768px){';
		$css_custom .= $id_fancy_heading . '.penci-fancy-heading .penci-heading-title{ font-size: ' . esc_attr( $atts['title_sm_fsize'] ) . '; } }';
	}

	if ( $atts['title_xs_fsize'] ) {
		$css_custom .= '@media screen and (max-width: 480px){';
		$css_custom .= $id_fancy_heading . '.penci-fancy-heading .penci-heading-title{ font-size: ' . esc_attr( $atts['title_xs_fsize'] ) . '; } }';
	}

	$css_custom .= Penci_Vc_Helper::vc_google_fonts_parse_attributes( array(
		'font_size'  => $atts['_title_fsize'],
		'font_style' => $atts['_title_typo'],
		'template'   => $id_fancy_heading . ' .penci-heading-title{ %s }',
	) );

	$css_custom .= Penci_Vc_Helper::vc_google_fonts_parse_attributes( array(
		'font_size'  => $atts['_subtitle_fsize'],
		'font_style' => $atts['_subtitle_typo'],
		'template'   => $id_fancy_heading . ' .penci-heading-subtitle{ %s }',
	) );

	$css_custom .= Penci_Vc_Helper::vc_google_fonts_parse_attributes( array(
		'font_size'  => $atts['_desc_fsize'],
		'font_style' => $atts['_desc_typo'],
		'template'   => $id_fancy_heading . ' .penci-heading-content{ %s }',
	) );

	if ( $add_line == 'yes' ) {
		if ( $lines_height ) {
			$css_custom .= $id_fancy_heading . ' .penci-fancy-heading.pc-fctline .inner-tit:before,' . $id_fancy_heading . ' .penci-fancy-heading.pc-fctline .inner-tit:after{height: ' . $lines_height . 'px; margin-top: calc( ' . $lines_height . 'px * -1 / 2 );}';
		}
		if ( $lines_width ) {
			$css_custom .= $id_fancy_heading . ' .penci-fancy-heading.pc-fctline .inner-tit:before,' . $id_fancy_heading . ' .penci-fancy-heading.pc-fctline .inner-tit:after{width: ' . $lines_height . 'px;}';
		}
		if ( $lines_spacing ) {
			$css_custom .= $id_fancy_heading . ' .penci-fancy-heading.pc-fctline .inner-tit{padding-left: ' . $lines_spacing . 'px; padding-right: ' . $lines_spacing . 'px;}';
		}
	}

	if ( $responsive_spacing ) {
		$css_custom .= penci_extract_spacing_style( $id_fancy_heading, $responsive_spacing );
	}

	if ( $css_custom ) {
		echo '<style>';
		echo $css_custom;
		echo '</style>';
	}
	?>
</div>
