<?php
$output = $penci_block_width = $el_class = $css_animation = $css = $responsive_spacing = '';

$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );

$penci_slides = (array) vc_param_group_parse_atts( $atts['penci_slides'] );
if ( ! $penci_slides ) {
	return;
}

$class_to_filter = vc_shortcode_custom_css_class( $css, ' ' ) . $this->getExtraClass( $el_class ) . $this->getCSSAnimation( $css_animation );

$css_class = 'penci-block-vc penci-custom-slides';
$css_class .= ' ' . apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $class_to_filter, $this->settings['base'], $atts );
$block_id  = Penci_Vc_Helper::get_unique_id_block( 'custom_slider' );

$data_slider = 'yes' == $atts['showdots'] ? ' data-dots="true"' : '';
$data_slider .= ! 'yes' == $atts['shownav'] ? ' data-nav="true"' : '';
$data_slider .= ! 'yes' == $atts['loop'] ? ' data-loop="true"' : '';
$data_slider .= ' data-auto="' . ( ! 'yes' == $atts['autoplay'] ? 'false' : 'true' ) . '"';
?>
	<div id="<?php echo esc_attr( $block_id ); ?>" class="<?php echo esc_attr( $css_class ); ?>">
		<div class="penci-block_content penci-slides-wrap swiper penci-owl-carousel penci-owl-carousel-slider" <?php echo $data_slider; ?>>
			<div class="swiper-wrapper">
			<?php
			$slide_count = 0;
			foreach ( (array) $penci_slides as $slide ) {

				$slide_bg_color   = isset( $slide['background_color'] ) && $slide['background_color'] ? $slide['background_color'] : '#833ca3';
				$slide_text_align = isset( $slide['text_align'] ) && $slide['text_align'] ? $slide['text_align'] : 'center';

				$heading_overlay       = isset( $atts['heading_overlay'] ) && 'yes' == $atts['heading_overlay'] ? 'yes' : '';
				$description_overlay = isset( $atts['description_overlay'] ) && 'yes' == $atts['description_overlay'] ? 'yes' : '';

				if( isset( $slide['bg_item_overlay'] ) && $slide['bg_item_overlay'] ) {
					if( 'yes' == $slide['bg_item_overlay'] ){
						$heading_overlay = $description_overlay = 'yes';
					}elseif( 'no' == $slide['bg_item_overlay'] ){
						$heading_overlay = $description_overlay = '';
					}
				}

				echo '<div class="swiper-slide penci-repeater-item-' . $slide_count . ' penci-ctslide-wrap">';
				echo '<div class="penci-custom-slide">';

				echo '<div class="penci-ctslide-bg" style="background-color:' . esc_attr( $slide_bg_color ) . '"></div>';



				echo '<div class="penci-ctslide-inner" style="text-align:' . esc_attr( $slide_text_align ) . '">';

				$url_feat_img = isset( $slide['url_feat_img'] ) ? $slide['url_feat_img'] : '';
				if( $url_feat_img ){
					echo '<a class="penci-ctslider-featimg" href="' . esc_url( $url_feat_img ) . '"></a>';
				}

				echo '<div class="penci-ctslider-content penci-' . esc_attr( $atts['content_animation'] ) . '">';

				if ( isset( $slide['heading'] ) && $slide['heading'] ) {
					if( $heading_overlay ) {
						echo '<h2 class="pencislider-title pencislider-title-overlay"><span class="pslider-bgoverlay-inner"><span>' . $slide['heading'] . '</span></span></h2>';
					}else{
						echo '<h2 class="pencislider-title">' . $slide['heading'] . '</h2>';
					}
				}

				if ( isset( $slide['description'] ) && $slide['description'] ) {
					if( $description_overlay ) {
						echo '<div class="pencislider-caption pencislider-caption-overlay"><span class="pslider-bgoverlay-inner"><span>' . $slide['description'] . '</span></span></div>';
					}else{
						echo '<div class="pencislider-caption">' . $slide['description'] . '</div>';
					}
				}

				$html_button = '';
				if ( isset( $slide['button_text'] ) && $slide['button_text'] ) {
					$link_data = 'href="#"';
					if ( isset( $slide['button_link'] ) && $slide['button_link'] ) {
						$link_data = 'href="' . esc_url( $slide['button_link'] ) . '"';
					}

					$html_button .= '<a class="pencislider-btn pencislider-btn-1 penci-button" ' . $link_data . '><span>' . $slide['button_text'] . '</span></a>';
				}
				if ( isset( $slide['button_text2'] ) && $slide['button_text2'] ) {
					$link_data = 'href="#"';
					if ( isset( $slide['button_link2'] ) && $slide['button_link2'] ) {
						$link_data = 'href="' . esc_url( $slide['button_link2'] ) . '"';
					}

					$html_button .= '<a class="pencislider-btn pencislider-btn-2 penci-button" ' . $link_data . '><span>' . $slide['button_text2'] . '</span></a>';
				}

				if ( $html_button ) {
					echo '<div class="penci-slider_btnwrap">' . $html_button . '</div>';
				}

				echo '</div>'; // slider content

				echo '</div>'; // penci-ctslide-inner
				echo '</div>'; // penci-custom-slide
				echo '</div>'; // penci-ctslide-wrap

				$slide_count ++;
			}
			?>
			</div>
		</div>
	</div>
<?php
$id_custom_slider = '#' . $block_id;
$id_custom_slider2 = 'body:not(.pcdm-enable) #' . $block_id;
$css_custom       = '';

$slide_count = 0;
foreach ( (array) $penci_slides as $slide ) {
	$horizontal_position = isset( $slide['horizontal_position'] ) ? $slide['horizontal_position'] : '';
	$vertical_position   = isset( $slide['vertical_position'] ) ? $slide['vertical_position'] : '';
	$text_align          = isset( $slide['text_align'] ) ? $slide['text_align'] : '';
	$content_color       = isset( $slide['content_color'] ) ? $slide['content_color'] : '';
	$background_image    = isset( $slide['background_image'] ) ? $slide['background_image'] : '';

	$heading_overlay       = isset( $atts['heading_overlay'] ) && $atts['heading_overlay'] ? 'yes' : '';
	$description_overlay = isset( $atts['description_overlay'] ) && $atts['description_overlay'] ? 'yes' : '';
	if( isset( $slide['bg_item_overlay'] ) && $slide['bg_item_overlay'] ) {
		if( 'yes' == $slide['bg_item_overlay'] ){
			$heading_overlay = $description_overlay = 'yes';
		}elseif( 'no' == $slide['bg_item_overlay'] ){
			$heading_overlay = $description_overlay = '';
		}
	}

	if ( $horizontal_position ) {
		$css_custom .= $id_custom_slider . ' .penci-repeater-item-' . $slide_count . ' .penci-ctslide-inner .penci-ctslider-content{';

		if ( 'left' == $horizontal_position ) {
			$css_custom .= 'margin-right: auto;';
		} elseif ( 'center' == $horizontal_position ) {
			$css_custom .= 'margin: 0 auto;';
		} elseif ( 'right' == $horizontal_position ) {
			$css_custom .= 'margin-left: auto;';

			if ( ! $atts['content_max_width'] ) {
				$css_custom .= 'max-width: 66%;';
			}
		}
		$css_custom .= '}';
	}
	if ( $vertical_position ) {
		$css_custom .= $id_custom_slider . ' .penci-repeater-item-' . $slide_count . ' .penci-ctslide-inner{';

		if ( 'top' == $vertical_position ) {
			$css_custom .= 'align-items: flex-start;';
		} elseif ( 'middle' == $vertical_position ) {
			$css_custom .= 'align-items: center;';
		} elseif ( 'bottom' == $vertical_position ) {
			$css_custom .= 'align-items: flex-end;';
		}
		$css_custom .= '}';
	}

	if ( $background_image ) {
		$url_bgimg_item = wp_get_attachment_url( $background_image );
		$css_custom     .= $id_custom_slider . ' .penci-repeater-item-' . $slide_count . ' .penci-ctslide-bg{ background-image: url(' . esc_url( $url_bgimg_item ) . '); }';
	}

	if ( $content_color ) {
		$css_custom .= $id_custom_slider . ' .penci-repeater-item-' . $slide_count . ' .pencislider-title,';
		$css_custom .= $id_custom_slider . ' .penci-repeater-item-' . $slide_count . ' .pencislider-caption{ color:' . esc_attr( $content_color ) . ' }';
	}

	$bgoverlay_color   = isset( $slide['bgoverlay_color'] ) ? $slide['bgoverlay_color'] : '';
	$bgoverlay_opacity = isset( $slide['bgoverlay_opacity'] ) ? $slide['bgoverlay_opacity'] : '';
	$bgoverlay_padding = isset( $slide['bgoverlay_padding'] ) ? $slide['bgoverlay_padding'] : '';

	if ( 'yes' == $heading_overlay ||  'yes' == $description_overlay ) {
		if ( $bgoverlay_color ) {
			$css_custom .= $id_custom_slider . ' .penci-repeater-item-' . $slide_count . ' .pencislider-title-overlay .pslider-bgoverlay-inner:before,';
			$css_custom .= $id_custom_slider . ' .penci-repeater-item-' . $slide_count . ' .pencislider-caption-overlay .pslider-bgoverlay-inner:before{ background-color:' . esc_attr( $bgoverlay_color ) . ' }';
		}

		if ( $bgoverlay_padding ) {
			$css_custom .= $id_custom_slider . ' .penci-repeater-item-' . $slide_count . ' .pencislider-title-overlay .pslider-bgoverlay-inner,';
			$css_custom .= $id_custom_slider . ' .penci-repeater-item-' . $slide_count . ' .pencislider-caption-overlay .pslider-bgoverlay-inner{ padding:' . esc_attr( $bgoverlay_padding ) . ' }';
		}

		if ( $bgoverlay_opacity ) {
			$css_custom .= $id_custom_slider . ' .penci-repeater-item-' . $slide_count . ' .pencislider-title-overlay .pslider-bgoverlay-inner:before,';
			$css_custom .= $id_custom_slider . ' .penci-repeater-item-' . $slide_count . ' .pencislider-caption-overlay .pslider-bgoverlay-inner:before{ opacity:' . esc_attr( $bgoverlay_opacity ) . ' }';
		}
	}

	$slide_count ++;
}

if( $atts['slides_img_ratio'] ){
	$css_custom .= $id_custom_slider . ' .penci-ctslide-wrap{ height: auto !important; }';
	$css_custom .= $id_custom_slider . ' .penci-ctslide-wrap:before{ content:"";padding-top:' . esc_attr( $atts['slides_img_ratio'] ) . '% !important; }';
} elseif ( $atts['slides_height'] ) {
	$css_custom .= $id_custom_slider . ' .penci-ctslide-wrap{ height: ' . esc_attr( $atts['slides_height'] ) . 'px !important; }';
}
if ( $atts['content_max_width'] ) {
	$css_custom .= $id_custom_slider . ' .penci-ctslider-content{ max-width: ' . esc_attr( $atts['content_max_width'] ) . '; }';
}
$content_padding_ctcss = '';
if ( $atts['slides_paddingl'] ) {
	$content_padding_ctcss .= 'padding-left:' . esc_attr( $atts['slides_paddingl'] ) . ';';
}
if ( $atts['slides_paddingr'] ) {
	$content_padding_ctcss .= 'padding-right:' . esc_attr( $atts['slides_paddingr'] ) . ';';
}
if ( $atts['slides_paddingt'] ) {
	$content_padding_ctcss .= 'padding-top:' . esc_attr( $atts['slides_paddingt'] ) . ';';
}
if ( $atts['slides_paddingb'] ) {
	$content_padding_ctcss .= 'padding-bottom:' . esc_attr( $atts['slides_paddingb'] ) . ';';
}
if ( $content_padding_ctcss ) {
	$css_custom .= $id_custom_slider . ' .penci-ctslide-inner{' . esc_attr( $content_padding_ctcss ) . '}';
}

// Heading
$heading_spacing_ctcss = '';
if ( $atts['heading_spacing'] ) {
	$heading_spacing_ctcss .= 'margin-bottom:' . esc_attr( esc_attr( $atts['heading_spacing'] ) ) . ';';
}
if ( $atts['heading_color'] ) {
	$heading_spacing_ctcss .= 'color:' . esc_attr( esc_attr( $atts['heading_color'] ) ) . ';';
}
if ( $heading_spacing_ctcss ) {
	$css_custom .= $id_custom_slider . ' .pencislider-title{' . esc_attr( $heading_spacing_ctcss ) . '}';
}
$css_custom .= Penci_Vc_Helper::vc_google_fonts_parse_attributes( array(
	'font_size'  => $atts['heading_size'],
	'font_style' => $atts['heading_typography'],
	'template'   => $id_custom_slider . ' .pencislider-title{ %s }',
) );

if( isset( $atts['heading_bgoverlay_opacity'] ) && $atts['heading_bgoverlay_opacity'] ) {
	$css_custom .= $id_custom_slider . ' .pencislider-title-overlay .pslider-bgoverlay-inner:before{ opacity:' . esc_attr( $atts['heading_bgoverlay_opacity'] ) . '; }';
}
if( isset( $atts['heading_bgoverlay_color'] ) && $atts['heading_bgoverlay_color'] ) {
	$css_custom .= $id_custom_slider . ' .pencislider-title-overlay .pslider-bgoverlay-inner:before{ background-color:' . esc_attr( $atts['heading_bgoverlay_color'] ) . '; }';
}
if( isset( $atts['heading_bgoverlay_padding'] ) && $atts['heading_bgoverlay_padding'] ) {
	$css_custom .= $id_custom_slider . ' .pencislider-title-overlay .pslider-bgoverlay-inner{ padding:' . esc_attr( $atts['heading_bgoverlay_padding'] ) . '; }';
}

// Description
$desc_ctcss = '';
if ( $atts['desc_spacing'] ) {
	$desc_ctcss .= 'margin-bottom:' . esc_attr( esc_attr( $atts['desc_spacing'] ) ) . ';';
}
if ( $atts['desc_color'] ) {
	$desc_ctcss .= 'color:' . esc_attr( $atts['desc_color'] ) . ';';
}
if ( $desc_ctcss ) {
	$css_custom .= $id_custom_slider . ' .pencislider-caption{' . esc_attr( $desc_ctcss ) . '}';
}
$css_custom .= Penci_Vc_Helper::vc_google_fonts_parse_attributes( array(
	'font_size'  => $atts['desc_size'],
	'font_style' => $atts['desc_typo'],
	'template'   => $id_custom_slider . ' .pencislider-caption{ %s }',
) );

if( isset( $atts['desc_bgoverlay_opacity'] ) && $atts['desc_bgoverlay_opacity'] ) {
	$css_custom .= $id_custom_slider . ' .pencislider-caption .pslider-bgoverlay-inner:before{ opacity:' . esc_attr( $atts['desc_bgoverlay_opacity'] ) . '; }';
}
if( isset( $atts['desc_bgoverlay_color'] ) && $atts['desc_bgoverlay_color'] ) {
	$css_custom .= $id_custom_slider . ' .pencislider-caption .pslider-bgoverlay-inner:before{ background-color:' . esc_attr( $atts['desc_bgoverlay_color'] ) . '; }';
}
if( isset( $atts['desc_bgoverlay_padding'] ) && $atts['desc_bgoverlay_padding'] ) {
	$css_custom .= $id_custom_slider . ' .pencislider-caption .pslider-bgoverlay-inner{ padding:' . esc_attr( $atts['desc_bgoverlay_padding'] ) . '; }';
}

// Button
$css_btn_ct = '';
if ( $atts['button1_width'] ) {
	$css_btn_ct .= 'width:' . esc_attr( esc_attr( $atts['button1_width'] ) ) . ';';
}
if ( $atts['button1_height'] ) {
	$css_btn_ct .= 'height:' . esc_attr( esc_attr( $atts['button1_height'] ) ) . ';';
}
if ( $atts['button1_border_width'] ) {
	$css_btn_ct .= 'border-width:' . esc_attr( esc_attr( $atts['button1_border_width'] ) ) . ';';
}
if ( $atts['button1_border_radius'] ) {
	$css_btn_ct .= 'border-radius:' . esc_attr( esc_attr( $atts['button1_border_radius'] ) ) . ';';
}
if ( $atts['button1_color'] ) {
	$css_btn_ct .= 'color:' . esc_attr( esc_attr( $atts['button1_color'] ) ) . ';';
}
if ( $atts['button1_bgcolor'] ) {
	$css_btn_ct .= 'background-color:' . esc_attr( esc_attr( $atts['button1_bgcolor'] ) ) . ';';
}
if ( $atts['button1_border_color'] ) {
	$css_btn_ct .= 'border-color:' . esc_attr( esc_attr( $atts['button1_border_color'] ) ) . ';';
}
if ( $css_btn_ct ) {
	$css_custom .= $id_custom_slider . ' .penci-slider_btnwrap .pencislider-btn-1{' . esc_attr( $css_btn_ct ) . '}';
}
$css_btn_ct_hover = '';
if ( $atts['button1_hcolor'] ) {
	$css_btn_ct_hover .= 'color:' . esc_attr( esc_attr( $atts['button1_hcolor'] ) ) . ';';
}
if ( $atts['button1_hbgcolor'] ) {
	$css_btn_ct_hover .= 'background-color:' . esc_attr( esc_attr( $atts['button1_hbgcolor'] ) ) . ';';
}
if ( $atts['button1_hbordercolor'] ) {
	$css_btn_ct_hover .= 'border-color:' . esc_attr( esc_attr( $atts['button1_hbordercolor'] ) ) . ';';
}

if ( $css_btn_ct_hover ) {
	$css_custom .= $id_custom_slider . ' .penci-slider_btnwrap .pencislider-btn-1:hover{' . esc_attr( $css_btn_ct_hover ) . '}';
}

$css_custom .= Penci_Vc_Helper::vc_google_fonts_parse_attributes( array(
	'font_size'  => $atts['button1_size'],
	'font_style' => $atts['button1_typo'],
	'template'   => $id_custom_slider . ' .pencislider-btn-1{ %s }',
) );

// Button 2
$css_btn_ct2 = '';
if ( $atts['button2_width'] ) {
	$css_btn_ct2 .= 'width:' . esc_attr( esc_attr( $atts['button2_width'] ) ) . ';';
}
if ( $atts['button2_height'] ) {
	$css_btn_ct2 .= 'height:' . esc_attr( esc_attr( $atts['button2_height'] ) ) . ';';
}
if ( $atts['button2_border_width'] ) {
	$css_btn_ct2 .= 'border-width:' . esc_attr( esc_attr( $atts['button2_border_width'] ) ) . ';';
}
if ( $atts['button2_border_radius'] ) {
	$css_btn_ct2 .= 'border-radius:' . esc_attr( esc_attr( $atts['button2_border_radius'] ) ) . ';';
}
if ( $atts['button2_color'] ) {
	$css_btn_ct2 .= 'color:' . esc_attr( esc_attr( $atts['button2_color'] ) ) . ';';
}
if ( $atts['button2_bgcolor'] ) {
	$css_btn_ct2 .= 'background-color:' . esc_attr( esc_attr( $atts['button2_bgcolor'] ) ) . ';';
}
if ( $atts['button2_border_color'] ) {
	$css_btn_ct2 .= 'border-color:' . esc_attr( esc_attr( $atts['button2_border_color'] ) ) . ';';
}
if ( $css_btn_ct2 ) {
	$css_custom .= $id_custom_slider . ' .penci-slider_btnwrap .pencislider-btn-2{' . esc_attr( $css_btn_ct2 ) . '}';
}
$css_btn_ct2_hover = '';
if ( $atts['button2_hcolor'] ) {
	$css_btn_ct2_hover .= 'color:' . esc_attr( esc_attr( $atts['button2_hcolor'] ) ) . ';';
}
if ( $atts['button2_hbgcolor'] ) {
	$css_btn_ct2_hover .= 'background-color:' . esc_attr( esc_attr( $atts['button2_hbgcolor'] ) ) . ';';
}
if ( $atts['button2_hbordercolor'] ) {
	$css_btn_ct2_hover .= 'border-color:' . esc_attr( esc_attr( $atts['button2_hbordercolor'] ) ) . ';';
}
if ( $css_btn_ct2_hover ) {
	$css_custom .= $id_custom_slider . ' .penci-slider_btnwrap .pencislider-btn-2:hover{' . esc_attr( $css_btn_ct2_hover ) . '}';
}

$css_custom .= Penci_Vc_Helper::vc_google_fonts_parse_attributes( array(
	'font_size'  => $atts['button2_size'],
	'font_style' => $atts['button2_typo'],
	'template'   => $id_custom_slider . ' .pencislider-btn-2{ %s }',
) );

if ( $responsive_spacing ) {
	$css_custom .= penci_extract_spacing_style( $id_custom_slider, $responsive_spacing );
}


if ( $css_custom ) {
	echo '<style>';
	echo $css_custom;
	echo '</style>';
}
