<?php
$output = $penci_block_width = $el_class = $css_animation = $css = $responsive_spacing = '';

$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );

$testimonails = (array) vc_param_group_parse_atts( $atts['testimonails'] );

if ( ! $testimonails ) {
	return;
}
$class_to_filter = vc_shortcode_custom_css_class( $css, ' ' ) . $this->getExtraClass( $el_class ) . $this->getCSSAnimation( $css_animation );

$css_class = 'penci-block-vc penci-testimonails';
$css_class .= ' penci-testi-' . $atts['style'];
if ( 's4' == $atts['style'] && $atts['imagepos'] ) {
	$css_class .= ' pcimgpos-' . $atts['imagepos'];
}

$data_slider         = '';
$inner_wrapper_class = 'penci-block_content pcsl-inner penci-clearfix';
$inner_wrapper_class .= ' pcsl-' . $atts['testitype'];
if ( 'crs' == $atts['testitype'] ) {
	$inner_wrapper_class .= ' penci-owl-carousel penci-owl-carousel-slider swiper';
}

$inner_wrapper_class .= ' pcsl-col-' . $atts['columns'];
$inner_wrapper_class .= ' pcsl-tabcol-' . $atts['tcolumns'];
$inner_wrapper_class .= ' pcsl-mobcol-' . $atts['mcolumns'];

$css_class .= ' ' . apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $class_to_filter, $this->settings['base'], $atts );
$block_id  = Penci_Vc_Helper::get_unique_id_block( 'testimonails' );
$item_class = 'normal-item';
if ( 'crs' == $atts['testitype'] ) {
	$data_slider = 'yes' == $atts['showdots'] ? ' data-dots="true"' : '';
	$data_slider .= 'yes' == $atts['shownav'] ? ' data-nav="true"' : '';
	$data_slider .= 'yes' == $atts['loop'] ? ' data-loop="true"' : '';
	$data_slider .= 'data-auto="' . ( 'yes' == $atts['autoplay'] ? 'true' : 'false' ) . '"';
	$data_slider .= 'data-autotime="' . ( $atts['auto_time'] ? intval( $atts['auto_time'] ) : '4000' ) . '"';
	$data_slider .= 'data-speed="' . ( $atts['speed'] ? intval( $atts['speed'] ) : '800' ) . '"';
	$data_slider .= ' data-desktop="' . ( $atts['slider_item'] ? $atts['slider_item'] : 1 ) . '"';
	$data_slider .= ' data-item="' . ( $atts['slider_item'] ? $atts['slider_item'] : 1 ) . '"';
}
?>
    <div id="<?php echo esc_attr( $block_id ); ?>" class="<?php echo esc_attr( $css_class ); ?>">
        <div class="<?php echo $inner_wrapper_class; ?>" <?php echo $data_slider; ?>>
			<?php
			if ( 'crs' == $atts['testitype'] ) {
				echo '<div class="swiper-wrapper">';
			}
			foreach ( (array) $testimonails as $_testi ) {
				$_testi_image   = isset( $_testi['testi_image'] ) ? $_testi['testi_image'] : '';
				$_testi_name    = isset( $_testi['testi_name'] ) ? $_testi['testi_name'] : '';
				$_testi_company = isset( $_testi['testi_company'] ) ? $_testi['testi_company'] : '';
				$_testi_desc    = isset( $_testi['testi_desc'] ) ? $_testi['testi_desc'] : '';
				$_testi_link    = isset( $_testi['testi_link'] ) ? $_testi['testi_link'] : '';
				$_testi_rating  = isset( $_testi['testi_rating'] ) ? $_testi['testi_rating'] : '';

				if ( $_testi_name || $_testi_company || $_testi_desc ) {
					if ( 'crs' == $atts['testitype'] ) {
						echo '<div class="swiper-slide">';
					}
					?>
                    <div class="pcsl-item penci-testimonail <?php echo $item_class;?>">
						<?php

						if ( 's2' == $atts['style'] ) {
							if ( $_testi_desc ) {
								echo '<div class="penci-testi-blockquote">';
								echo '<div class="penci-testi-bq-inner"><span class="penci-testi-bq-icon"></span><span>' . $_testi_desc . '</span></div>';

								if ( $_testi_rating ) {
									$rating_item = '';
									for ( $i = 1; $i <= $_testi_rating; $i ++ ) {
										$rating_item .= penci_icon_by_ver( 'fas fa-star' );
									}

									if ( $rating_item ) {
										echo '<div class="penci-testi-rating">' . $rating_item . '</div>';
									}
								}

								echo '</div>';
							}


						} else {
							if ( $_testi_desc ) {
								echo '<div class="penci-testi-blockquote"><div class="penci-testi-bq-inner"><span class="penci-testi-bq-icon"></span><span>' . $_testi_desc . '</span></div></div>';
							}

							if ( $_testi_rating ) {
								$rating_item = '';
								for ( $i = 1; $i <= $_testi_rating; $i ++ ) {
									$rating_item .= penci_icon_by_ver( 'fas fa-star' );
								}

								if ( $rating_item ) {
									echo '<div class="penci-testi-rating">' . $rating_item . '</div>';
								}
							}
						}


						$url_img_item = wp_get_attachment_url( $_testi_image );
						if ( $url_img_item ) {
							$thumbnailsize = penci_get_image_size_url( $url_img_item, 'thumbnail' );
							echo '<div class="penci-testi-avatar">';
							echo '<img src="' . esc_url( $thumbnailsize ) . '" alt="' . esc_attr( $_testi_name ) . '"/>';
							echo '</div>';
						}

						echo '<h3 class="penci-testi-name">' . $_testi_name . '</h3>';
						echo '<div class="penci-testi-company">' . $_testi_company . '</div>';


						?>
                    </div>
					<?php
				}

				if ( 'crs' == $atts['testitype'] ) {
					echo '</div>';
				}

			}
			if ( 'crs' == $atts['testitype'] ) {
				echo '</div>';
			}
			?>
        </div>
    </div>
<?php

$id_testimonails = '#' . $block_id;
$id_testimonails2 = 'body:not(.pcdm-enable) #' . $block_id;
$css_custom      = Penci_Vc_Helper::get_heading_block_css( $id_testimonails, $atts );

if ( $atts['p_name_marbottom'] ) {
	$css_custom .= penci_extract_md_responsive_fsize( $id_testimonails . ' .penci-testi-name{ margin-bottom:{{VALUE}}px }', $atts['p_name_marbottom'] );
}
if ( $atts['p_company_marbottom'] ) {
	$css_custom .= penci_extract_md_responsive_fsize( $id_testimonails . ' .penci-testi-company{ margin-bottom:{{VALUE}}px }', $atts['p_company_marbottom'] );
}
if ( $atts['p_rating_marbottom'] ) {
	$css_custom .= penci_extract_md_responsive_fsize( $id_testimonails . ' .penci-testi-rating{ margin-bottom:{{VALUE}}px }', $atts['p_rating_marbottom'] );
}
if ( $atts['p_desc_marbottom'] ) {
	$css_custom .= penci_extract_md_responsive_fsize( $id_testimonails . ' .penci-testi-blockquote{ margin-bottom:{{VALUE}}px }', $atts['p_desc_marbottom'] );
}
if ( $atts['p_desc_padding'] ) {
	$css_custom .= penci_extract_md_responsive_fsize( $id_testimonails . ' .penci-testi-blockquote{ margin-bottom:{{VALUE}}px}', $atts['p_desc_padding'] );
}


// Icon
if ( $atts['icon_quote_color'] ) {
	$css_custom .= $id_testimonails2 . '.penci-testi-s1 .penci-testimonail .penci-testi-bq-icon:before{ color:' . esc_attr( $atts['icon_quote_color'] ) . ' }';
}
if ( $atts['icon_quote_bgcolor'] ) {
	$css_custom .= $id_testimonails2 . '.penci-testi-s1 .penci-testimonail .penci-testi-bq-icon:before{ background-color:' . esc_attr( $atts['icon_quote_bgcolor'] ) . ' }';
}

// Name
if ( $atts['name_color'] ) {
	$css_custom .= $id_testimonails2 . ' .penci-testi-name{ color:' . esc_attr( $atts['name_color'] ) . '; }';
}
if ( 'yes' == $atts['use_name_typo'] ) {
	$css_custom .= Penci_Vc_Helper::vc_google_fonts_parse_attributes( array(
		'font_size'  => $atts['name_size'],
		'font_style' => $atts['name_typo'],
		'template'   => $id_testimonails . ' .penci-testi-name{ %s }',
	) );
}

// Position
if ( $atts['company_color'] ) {
	$css_custom .= $id_testimonails2 . ' .penci-testi-company{ color:' . esc_attr( $atts['name_color'] ) . '; }';
}
if ( 'yes' == $atts['use_company_typo'] ) {
	$css_custom .= Penci_Vc_Helper::vc_google_fonts_parse_attributes( array(
		'font_size'  => $atts['company_size'],
		'font_style' => $atts['company_typo'],
		'template'   => $id_testimonails . ' .penci-testi-company{ %s }',
	) );
}

// Description
if ( $atts['desc_color'] ) {
	$css_custom .= $id_testimonails2 . '.penci-testi-s1 .penci-testimonail .penci-testi-blockquote{ color:' . esc_attr( $atts['desc_color'] ) . '; }';
}
if ( $atts['column_gap'] ) {
	$css_custom .= $id_testimonails . '.penci-testimonails{ --pcsl-hgap:' . esc_attr( $atts['column_gap'] ) . 'px; }';
}
if ( $atts['row_gap'] ) {
	$css_custom .= $id_testimonails . '.penci-testimonails{ --pcsl-bgap:' . esc_attr( $atts['row_gap'] ) . 'px; }';
}
if ( 'yes' == $atts['use_desc_typo'] ) {
	$css_custom .= Penci_Vc_Helper::vc_google_fonts_parse_attributes( array(
		'font_size'  => $atts['desc_size'],
		'font_style' => $atts['desc_typo'],
		'template'   => $id_testimonails . '.penci-testi-s1 .penci-testimonail .penci-testi-blockquote{ %s }',
	) );
}

if ( $responsive_spacing ) {
	$css_custom .= penci_extract_spacing_style( $id_testimonails, $responsive_spacing );
}

if ( $css_custom ) {
	echo '<style>';
	echo $css_custom;
	echo '</style>';
}
