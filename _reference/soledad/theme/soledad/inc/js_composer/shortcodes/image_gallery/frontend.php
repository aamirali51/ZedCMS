<?php

$el_class            = $css_animation = $css = '';
$heading_title_style = $heading = $heading_title_link = $heading_title_align = $caption_source = '';
$style_gallery       = $images = $auto_play = $disable_loop = $auto_time = $speed = $gallery_height = '';

$penci_img_size = $penci_img_size_bitem = $penci_img_type = $gallery_columns = $slider_autoplay = '';
$row_gap        = $col_gap = '';

$p_icon_color = $p_icon_size = $p_overlay_bgcolor = $responsive_spacing = '';

$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );


if ( ! $images ) {
	return;
}
$images = explode( ',', $images );

$class_to_filter = '';
$class_to_filter .= vc_shortcode_custom_css_class( $css, ' ' ) . $this->getExtraClass( $el_class ) . $this->getCSSAnimation( $css_animation );

$css_class = 'penci-block-vc penci-image-gallery';
$css_class .= ' penci-image-gallery-' . $style_gallery;

if ( 's1' == $style_gallery ) {
	$css_class .= ' pencisc-grid-' . $gallery_columns;
}

if ( 'img' == $caption_source ) {
	$caption_source = 'attachment';
} else {
	$css_class .= ' penci-image-not-caption';
}

$css_class .= ' ' . apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $class_to_filter, $this->settings['base'], $atts );

$slider_id      = rand( 1000, 100000 );
$block_id       = 'penci-image_gallery_' . $slider_id;
$thumbnail_html = '';
?>
    <div data-sliderid="<?php echo esc_attr( $slider_id ); ?>" id="<?php echo esc_attr( $block_id ); ?>"
         class="<?php echo esc_attr( $css_class ); ?>">
		<?php Penci_Vc_Helper::markup_block_title( $atts ); ?>
        <div class="penci-block_content <?php echo esc_attr( 's1' == $style_gallery ? ' pencisc-grid' : '' ); ?>">
			<?php
			$total_img = is_array( $images ) ? count( (array) $images ) : 0;
			$gal_2_i   = $gal_count = 0;

			if ( 's2' == $style_gallery ) {
				foreach ( $images as $image_key => $image_item ) {
					$gal_count ++;
					$gal_2_i ++;

					if ( $image_item < 0 ) {
						continue;
					}

					$class_item         = 'penci-gallery-small-item';
					$penci_img_size_pre = $penci_img_size;
					if ( $gal_count == 1 ) {
						$penci_img_size_pre = $penci_img_size_bitem;
						$class_item         = 'penci-gallery-big-item';
					}

					echo Penci_Vc_Helper::get_image_holder_gal( $image_item, $penci_img_size_pre, $penci_img_type, true, $gal_count, $class_item, $caption_source );

					if ( $gal_count == 1 ) {
						echo '<div class="penci-post-smalls">';
					}

					if ( 5 == $gal_count || $gal_2_i == $total_img ) {
						$gal_count = 0;
						echo '</div>';
					}
				}
			} elseif ( 's3' == $style_gallery ) {
				foreach ( $images as $image_key => $image_item ) {
					$gal_count ++;
					$gal_2_i ++;

					if ( $image_item < 0 ) {
						continue;
					}

					$class_item = 'penci-gallery-small-item';
					if ( $gal_count == 1 || $gal_count == 2 ) {
						$penci_img_size = $penci_img_size_bitem;
						$class_item     = 'penci-gallery-big-item';
					}

					echo Penci_Vc_Helper::get_image_holder_gal( $image_item, $penci_img_size, $penci_img_type, true, $gal_count, $class_item, $caption_source );

					if ( 5 == $gal_count || $gal_2_i == $total_img ) {
						$gal_count = 0;
					}
				}
			} elseif ( 'justified' == $style_gallery || 'masonry' == $style_gallery || 'single-slider' == $style_gallery || 'thumbnail-slider' == $style_gallery ) {
				$data_height = '150';

				if ( is_numeric( $gallery_height ) && 60 < $gallery_height ) {
					$data_height = $gallery_height;
				}

				echo '<div class="penci-post-gallery-container ' . $style_gallery . ' column-' . $gallery_columns . '" data-height="' . $data_height . '" data-margin="3">';

				if ( 'masonry' == $style_gallery ) {
					echo '<div class="inner-gallery-masonry-container">';
				}

				if ( 'single-slider' == $style_gallery ) {
					echo '<div class="penci-owl-carousel swiper penci-owl-carousel-slider penci-nav-visible" data-auto="' . ( 'yes' == $slider_autoplay ? 'true' : 'false' ) . '" data-lazy="true"><div class="swiper-wrapper">';
				}

				$posts = get_posts( array( 'include' => $images, 'post_type' => 'attachment' ) );

				if ( $style_gallery == 'thumbnail-slider' ):
					echo '<div data-id="pcthumb-m-' . $slider_id . '" class="swiper penci-slick-carousel pcthumb-s-msl pcthumb-m-' . $slider_id . '"><div class="swiper-wrapper">';
				endif;


				if ( $posts ) {
					foreach ( $posts as $imagePost ) {
						$caption       = '';
						$gallery_title = '';
						if ( $imagePost->post_excerpt ):
							$caption = $imagePost->post_excerpt;
						endif;
						if ( $caption && 'attachment' == $caption_source ) {
							$gallery_title = ' data-cap="' . esc_attr( $caption ) . '"';
						}

						$get_full    = wp_get_attachment_image_src( $imagePost->ID, 'full' );
						$get_masonry = wp_get_attachment_image_src( $imagePost->ID, 'penci-masonry-thumb' );
						$thumbsize   = 'penci-masonry-thumb';

						$image_alt        = penci_get_image_alt( $imagePost->ID, get_the_ID() );
						$image_title_html = penci_get_image_title( $imagePost->ID );

						$class_a_item = 'penci-gallery-ite';
						if ( 'masonry' != $style_gallery ) {
							$class_a_item = 'penci-gallery-ite item-gallery-' . $style_gallery;
						}

						if ( $style_gallery == 'masonry' || $style_gallery == 'single-slider' ) {
							$class_a_item .= ' item-link-relative';
						}

						if ( 'single-slider' == $style_gallery || 'thumbnail-slider' == $style_gallery ) {
							echo '<figure class="swiper-slide">';
							$get_masonry = wp_get_attachment_image_src( $imagePost->ID, 'penci-full-thumb' );
							$thumbsize   = 'penci-full-thumb';
						}
						if ( 'masonry' == $style_gallery ) {
							echo '<div class="item-gallery-' . $style_gallery . '">';
						}

						echo '<a class="' . $class_a_item . ( 'attachment' != $caption_source ? ' added-caption' : '' ) . '" href="' . $get_full[0] . '"' . $gallery_title . ' data-rel="penci-gallery-image-content">';

						if ( 'masonry' == $style_gallery ) {
							echo '<div class="inner-item-masonry-gallery">';
						}

						if ( $style_gallery == 'masonry' || $style_gallery == 'single-slider' ) {
							echo penci_get_ratio_img_basedid( $imagePost->ID, $thumbsize );
						}
						echo '<img class="" src="' . $get_masonry[0] . '" alt="' . $image_alt . '"' . $image_title_html . '>';

						if ( $style_gallery == 'justified' && $caption && 'attachment' == $caption_source ) {
							echo '<div class="caption">' . wp_kses( $caption, array(
									'em'     => array(),
									'strong' => array(),
									'b'      => array(),
									'i'      => array()
								) ) . '</div>';
						}
						if ( 'masonry' == $style_gallery ) {
							echo '</div>';
						}

						echo '</a>';

						// Close item-gallery-' . $style_gallery . '-wrap
						if ( 'masonry' == $style_gallery ) {
							echo '</div>';
						}

						if ( 'single-slider' == $style_gallery || 'thumbnail-slider' == $style_gallery ) {
							if ( $caption ) {
								echo '<p class="penci-single-gallery-captions">' . $caption . '</p>';
							}
							echo '</figure>';
						}

						if ( $style_gallery == 'thumbnail-slider' ) {
							$get_thumbnail_slider_img = wp_get_attachment_image_src( $imagePost->ID, 'thumbnail' );
							$thumbnail_html           .= '<div class="pcgl-thumb-item swiper-slide"><div class="pcgl-thumb-item-img"><span class="penci-image-holder" style="background-image:url(' . $get_thumbnail_slider_img[0] . ')"></div></div>';
						}
					}
				}

				if ( 'masonry' == $style_gallery || 'single-slider' == $style_gallery || 'thumbnail-slider' == $style_gallery ) {
					echo '</div>';
				}

				if ( 'single-slider' == $style_gallery || 'thumbnail-slider' == $style_gallery ) {
					echo '</div>';
				}

				if ($style_gallery == 'thumbnail-slider') {
					$nav = '<div class="penci-slick-carousel-top-nav"><div class="pcslick-nav-area"><div class="pcslick-nav"><button type="button" class="slick-prev"><i class="penciicon-left-chevron"></i></button><button type="button" class="slick-next"><i class="penciicon-right-chevron"></i></button></div><div class="slider-num"><span class="current">1</span>' . __(' of ', 'soledad') . '<span class="total">' . count($posts) . '</span></div></div></div>';
					echo '<div data-cols="7" data-total="'.count($posts).'" data-id="pcthumb-c-' . $slider_id . '" class="swiper pcthumb-s-csl pcgl-thumb-slider penci-slick-carousel pcthumb-c-' . $slider_id . '"><div class="swiper-wrapper">' . $thumbnail_html . '</div>'.$nav.'</div>';
				}

				echo '</div>';
			} else {
				foreach ( $images as $image_key => $image_item ) {
					$gal_count ++;
					$gal_2_i ++;

					if ( $image_item < 0 ) {
						continue;
					}
					echo Penci_Vc_Helper::get_image_holder_gal( $image_item, $penci_img_size, $penci_img_type, true, $gal_count, '', $caption_source );
				}
			}

			?>
        </div>
    </div>
<?php
$id_image_gallery  = '#' . $block_id;
$id_image_gallery2 = 'body:not(.pcdm-enable) #' . $block_id;
$css_custom        = Penci_Vc_Helper::get_heading_block_css( $id_image_gallery, $atts );

$row_col_gap = '';
if ( $row_gap ) {
	$row_col_gap .= 'grid-row-gap: ' . esc_attr( $row_gap ) . ';';
}
if ( $col_gap ) {
	$row_col_gap .= 'grid-column-gap: ' . esc_attr( $col_gap ) . ';';
}
if ( $row_col_gap ) {
	$css_custom .= $id_image_gallery . ' .pencisc-grid{' . esc_attr( $row_col_gap ) . '}';
}
if ( $p_icon_color ) {
	$css_custom .= $id_image_gallery2 . ' .penci-gallery-item i{ color:' . esc_attr( $p_icon_color ) . '; }';
}
if ( $p_icon_size ) {
	$css_custom .= $id_image_gallery . ' .penci-gallery-item i{ font-size:' . esc_attr( $p_icon_size ) . '; }';
}
if ( $p_overlay_bgcolor ) {
	$css_custom .= $id_image_gallery2 . ' .penci-gallery-item a::after{ background-color:' . esc_attr( $p_overlay_bgcolor ) . '; }';
}
if ( $penci_img_type ) {
	if ( 'square' == $penci_img_type ) {
		$css_custom .= $id_image_gallery . ' .penci-image-holder:before{ padding-top: 100%; }';
	} elseif ( 'vertical' == $penci_img_type ) {
		$css_custom .= $id_image_gallery . ' .penci-image-holder:before{ padding-top: 135.4%; }';
	} else {
		$css_custom .= $id_image_gallery . ' .penci-image-holder:before{ padding-top: 66.6667%; }';
	}
}
if ( $responsive_spacing ) {
	$css_custom .= penci_extract_spacing_style( $id_image_gallery, $responsive_spacing );
}

if ( $css_custom ) {
	echo '<style>';
	echo $css_custom;
	echo '</style>';
}
