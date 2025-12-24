<?php
global $post;
$settings       = vc_map_get_attributes( $this->getShortcode(), $atts );
$avatar         = $settings['penci_single_author_avatar'];
$block_id       = Penci_Vc_Helper::get_unique_id_block( 'pc_single_featured_overlay' );
$css_custom     = '';
$css_class      = vc_shortcode_custom_css_class( $settings['css'] );
$avatarw        = isset( $settings['penci_avatar_w'] ) && $settings['penci_avatar_w'] ? $settings['penci_avatar_w'] : 32;
$icon_style     = $settings['meta_icon_style'];
$thumb_alt      = '';
$label_text     = $settings['hide_meta_label'];
$gradient_class = '';

if ( has_post_thumbnail() ) {
	$thumb_id  = get_post_thumbnail_id( get_the_ID() );
	$thumb_alt = penci_get_image_alt( $thumb_id, get_the_ID() );
}

$move_title_bellow   = false;
$enable_jarallax     = $settings['penci_enable_jarallax_single'];
$pmt_enable_jarallax = get_post_meta( get_the_ID(), 'penci_enable_jarallax_single', true );

if ( $pmt_enable_jarallax ) {
	$enable_jarallax = $pmt_enable_jarallax;
}

$simage_size = get_theme_mod( 'penci_single_custom_thumbnail_size' ) ? get_theme_mod( 'penci_single_custom_thumbnail_size' ) : 'penci-full-thumb';
$image_size  = $settings['img_size'] ? $settings['img_size'] : $simage_size;

if ( penci_is_mobile() ) {
	$image_size = $settings['img_msize'] ? $settings['img_msize'] : 'penci-masonry-thumb';
}


$div_special_wrapper = '';
if ( ! $move_title_bellow ) {
	$div_special_wrapper .= '<div class="';
	$div_special_wrapper .= 'standard-post-special_wrapper' . $gradient_class;
	$div_special_wrapper .= '">';
}

$image_html = penci_get_featured_single_image_size( get_the_ID(), $image_size, $enable_jarallax, $thumb_alt );

?>
<?php if ( penci_get_post_format( 'link' ) || penci_get_post_format( 'quote' ) ) : ?>
	<?php
	$class_pimage_linkquote = 'standard-post-special post-image';
	if ( penci_get_post_format( 'quote' ) ) {
		$class_pimage_linkquote .= ' penci-special-format-quote';
	}
	if ( ! has_post_thumbnail() || get_theme_mod( 'penci_post_thumb' ) ) {
		$class_pimage_linkquote .= ' no-thumbnail';
	}

	if ( ! $move_title_bellow ) {
		$class_pimage_linkquote .= ' penci-move-title-above';
	}

	if ( $enable_jarallax ) {
		$class_pimage_linkquote .= ' penci-jarallax';
	}
	?>
    <div id="<?php echo $block_id; ?>" class="<?php echo $css_class; ?>">
        <div class="<?php echo( $class_pimage_linkquote ); ?>">
			<?php
			if ( has_post_thumbnail() && ! get_theme_mod( 'penci_post_thumb' ) ) {
				echo $image_html;
			}
			?>
			<?php echo $div_special_wrapper; ?>
            <div class="standard-content-special">
                <div class="format-post-box<?php if ( penci_get_post_format( 'quote' ) ) {
					echo ' penci-format-quote';
				} else {
					echo ' penci-format-link';
				} ?>">
                    <span class="post-format-icon"><?php penci_fawesome_icon( 'fas fa-' . ( penci_get_post_format( 'quote' ) ? 'quote-left' : 'link' ) ); ?></span>
                    <p class="dt-special">
						<?php
						if ( penci_get_post_format( 'quote' ) ) {
							$dt_content = get_post_meta( $post->ID, '_format_quote_source_name', true );
							if ( ! empty( $dt_content ) ): echo sanitize_text_field( $dt_content ); endif;
						} else {
							$dt_content = get_post_meta( $post->ID, '_format_link_url', true );
							if ( ! empty( $dt_content ) ):
								echo '<a href="' . esc_url( $dt_content ) . '" target="_blank">' . sanitize_text_field( $dt_content ) . '</a>';
							endif;
						}
						?>
                    </p>
					<?php
					if ( penci_get_post_format( 'quote' ) ):
						$quote_author = get_post_meta( $post->ID, '_format_quote_source_url', true );
						if ( ! empty( $quote_author ) ):
							echo '<div class="author-quote"><span>' . sanitize_text_field( $quote_author ) . '</span></div>';
						endif;
					endif; ?>
                </div>
            </div>
            <div class="penci-fto-ct">
				<?php
				if ( ! $move_title_bellow ) {
					get_template_part( 'template-parts/single', 'breadcrumb' );
				}
				if ( ! $move_title_bellow && has_post_thumbnail() && ! get_theme_mod( 'penci_post_thumb' ) ) {
					get_template_part( 'inc/template-builder/single-elements/entry', 'header', $settings );
				}
				?>
            </div>
			<?php if ( ! $move_title_bellow ): ?></div><?php endif; ?>
    </div>

<?php elseif ( penci_get_post_format( 'gallery' ) ) : ?>

	<?php $images = get_post_meta( $post->ID, '_format_gallery_images', true ); ?>

	<?php if ( ! $move_title_bellow && has_post_thumbnail() ) : ?>
		<?php if ( ! get_theme_mod( 'penci_post_thumb' ) ) : ?>
            <div class="post-image penci-move-title-above <?php echo( $enable_jarallax ? ' penci-jarallax' : '' ); ?>">
				<?php
				if ( ! get_theme_mod( 'penci_disable_lightbox_single' ) && ! $enable_jarallax ) {
					$thumb_url = wp_get_attachment_url( get_post_thumbnail_id( $post->ID ) );
					echo '<a href="' . esc_url( $thumb_url ) . '" data-rel="penci-gallery-image-content">';
					echo $image_html;
					echo '</a>';
				} else {
					echo $image_html;
				}

				echo $div_special_wrapper;

				echo '<div class="penci-fto-ct">';

				get_template_part( 'template-parts/single', 'breadcrumb' );
				if ( has_post_thumbnail() && ! get_theme_mod( 'penci_post_thumb' ) ) {
					get_template_part( 'inc/template-builder/single-elements/entry', 'header', $settings );
				}
				echo '</div>';
				echo '</div>';
				?>
            </div>
		<?php endif; ?>
	<?php elseif ( $images ) :
		$autoplay = ! get_theme_mod( 'penci_disable_autoplay_single_slider' ) ? 'true' : 'false';
		?>
        <div class="post-image">
            <div class="penci-owl-carousel swiper penci-owl-carousel-slider penci-nav-visible"
                 data-auto="<?php echo $autoplay; ?>" data-lazy="true">
                <div class="swiper-wrapper">
					<?php foreach ( $images as $image ) : ?>

						<?php $the_image = wp_get_attachment_image_src( $image, $image_size ); ?>
						<?php $the_caption = get_post_field( 'post_excerpt', $image );
						$image_alt         = penci_get_image_alt( $image, get_the_ID() );
						$image_title_html  = penci_get_image_title( $image );
						?>
                        <div class="swiper-slide swiper-mark-item">
                            <figure class="penci-swiper-mask">
								<?php if ( get_theme_mod( 'penci_speed_disable_first_screen' ) || ! get_theme_mod( 'penci_disable_lazyload_fsingle' ) ) { ?>
                                    <img class="penci-lazy" data-src="<?php echo esc_url( $the_image[0] ); ?>"
                                         alt="<?php echo $image_alt; ?>"<?php echo $image_title_html; ?> />
								<?php } else { ?>
                                    <img src="<?php echo esc_url( $the_image[0] ); ?>"
                                         alt="<?php echo $image_alt; ?>"<?php echo $image_title_html; ?> />
								<?php } ?>
								<?php if ( get_theme_mod( 'penci_post_gallery_caption' ) && $the_caption ): ?>
                                    <p class="penci-single-gallery-captions penci-single-gaformat-caption"><?php echo $the_caption; ?></p>
								<?php endif; ?>
                            </figure>
                        </div>

					<?php endforeach; ?>
                </div>
            </div>
        </div>
	<?php endif; ?>

<?php elseif ( penci_get_post_format( 'video' ) ) : ?>
	<?php
	Penci_Sodedad_Video_Format::show_builder_video_embed( array(
		'post_id'             => $post->ID,
		'parallax'            => $enable_jarallax,
		'args'                => array( 'width' => '1920', 'height' => '1080' ),
		'show_title_inner'    => true,
		'move_title_bellow'   => $move_title_bellow,
		'div_special_wrapper' => $div_special_wrapper,
		'single_style'        => 'style-1'
	), $settings );
	?>
<?php elseif ( penci_get_post_format( 'audio' ) ) : ?>
	<?php
	$class_pimage_audio = 'standard-post-image post-image audio';

	if ( ! has_post_thumbnail() || get_theme_mod( 'penci_post_thumb' ) ) {
		$class_pimage_audio .= ' no-thumbnail';
	}

	if ( $enable_jarallax ) {
		$class_pimage_audio .= ' penci-jarallax';
	}

	if ( ! $move_title_bellow ) {
		$class_pimage_audio .= ' penci-move-title-above';
	}

	?>
<div class="<?php echo $class_pimage_audio; ?>">
	<?php
	if ( has_post_thumbnail() && ! get_theme_mod( 'penci_post_thumb' ) ) {
		echo $image_html;
	}
	?>
	<?php echo $div_special_wrapper; ?>
    <div class="audio-iframe">
		<?php $penci_audio = get_post_meta( $post->ID, '_format_audio_embed', true );
		$penci_audio_str   = substr( $penci_audio, - 4 ); ?>
		<?php if ( wp_oembed_get( $penci_audio ) ) : ?>
			<?php echo wp_oembed_get( $penci_audio ); ?>
		<?php elseif ( $penci_audio_str == '.mp3' ) : ?>
			<?php echo do_shortcode( '[audio src="' . esc_url( $penci_audio ) . '"]' ); ?>
		<?php else : ?>
			<?php echo $penci_audio; ?>
		<?php endif; ?>
    </div>
    <div class="penci-fto-ct">
		<?php
		if ( ! $move_title_bellow ) {
			get_template_part( 'template-parts/single', 'breadcrumb' );
		}
		if ( ! $move_title_bellow && has_post_thumbnail() && ! get_theme_mod( 'penci_post_thumb' ) ) {
			get_template_part( 'inc/template-builder/single-elements/entry', 'header', $settings );
		}
		?>
    </div>
	<?php if ( ! $move_title_bellow ): ?></div><?php endif; ?>
    </div>

<?php else : ?>

	<?php if ( has_post_thumbnail() && ! $settings['penci_post_thumb'] ) : ?>
        <div class="post-image <?php echo( ! $move_title_bellow ? ' penci-move-title-above' : '' ); ?>">
			<?php
			if ( ! get_theme_mod( 'penci_disable_lightbox_single' ) && ! $enable_jarallax ) {
				$thumb_url = wp_get_attachment_url( get_post_thumbnail_id( $post->ID ) );
				echo '<a href="' . esc_url( $thumb_url ) . '" data-rel="penci-gallery-bground-content">';
				echo $image_html;
				echo '</a>';
			} else {

				echo '<div class="' . ( $enable_jarallax ? 'penci-jarallax' : '' ) . '">';
				echo $image_html;
				echo '</div>';
			}

			echo $div_special_wrapper;
			echo '<div class="penci-fto-ct">';
			if ( ! $move_title_bellow ) {
				get_template_part( 'template-parts/single', 'breadcrumb' );
			}

			if ( ! $move_title_bellow && has_post_thumbnail() ) {
				?>
                <div class="header-standard header-classic single-header">
				<?php if ( ! $settings['penci_post_cat'] ) : ?>
                    <div class="penci-standard-cat penci-single-cat <?php echo $settings['cat_pre_style']; ?>"><span
                                class="cat">
                                    <?php penci_category( '' ); ?>
                                </span></div>
				<?php endif; ?>

				<?php if ( ! $settings['hide_title'] ): ?>
                    <h1 class="post-title single-post-title entry-title"><span><?php the_title(); ?></span></h1>
				<?php endif; ?>
				<?php
				if ( ! $settings['hide_subtitle'] ) {
					penci_display_post_subtitle();
				}
				?>
				<?php penci_soledad_meta_schema(); ?>
				<?php $hide_readtime = get_theme_mod( 'penci_single_hreadtime' ); ?>
				<?php if ( ! $settings['penci_single_meta_author'] || ! $settings['penci_single_meta_date'] || ! $settings['penci_single_meta_comment'] || $settings['penci_single_show_cview'] || $settings[ $hide_readtime ] ) : ?>

                    <div class="post-box-meta-single style-<?php echo esc_attr( $icon_style ); ?>">
						<?php if ( ! $settings['penci_single_meta_author'] ) :
							global $post;
							?>
                            <span class="author-post byline">
                                        <span class="author vcard">
	                                        <?php if ( ! $label_text ) {
		                                        echo penci_get_setting( 'penci_trans_written_by' );
	                                        } ?>
	                                        <?php
	                                        $author_ids = [];
	                                        $author     = get_post_field( 'post_author', get_the_ID() );
	                                        if ( $author ) {
		                                        $author_ids[] = $author;
	                                        }
	                                        if ( function_exists( 'coauthors__echo' ) ) {
		                                        $author_list = coauthors__echo( 'ID', 'field', array(
			                                        'between'     => ',',
			                                        'betweenLast' => ',',
			                                        'before'      => '',
			                                        'after'       => '',
		                                        ), null, false );
		                                        if ( $author_list ) {
			                                        $author_ids = explode( ',', $author_list );
		                                        }
	                                        }
	                                        $total   = count( $author_ids );
	                                        $current = 0;
	                                        foreach ( $author_ids as $author_id ) {
		                                        $current ++;
		                                        ?>

                                                <a class="author-url url fn n"
                                                   href="<?php echo get_author_posts_url( $author_id ); ?>">
                                                <?php
                                                if ( ! $avatar ) {
	                                                echo get_avatar( $author_id, $avatarw );
                                                } else {
	                                                echo $current == 2 ? ', ' : ( ( $current == $total ) ? ' & ' : '' );
                                                }
                                                echo get_the_author_meta( 'display_name', $author_id );
                                                ?>
                                            </a>
	                                        <?php } ?>
                                        </span>
                                    </span>
						<?php endif; ?>
						<?php penci_author_update_name(); ?>
						<?php if ( ! $settings['penci_single_meta_date'] ) : ?>
                            <span class="pctmp-date-post">
                                <?php penci_soledad_time_link( 'single' ); ?></span>
						<?php endif; ?>
						<?php if ( ! $settings['penci_single_meta_comment'] ) :
							?>
                            <span class="pctmp-comment-post">
                                    <?php
                                    $comment_text  = ! $label_text ? ' ' . penci_get_setting( 'penci_trans_comment' ) : '';
                                    $comments_text = ! $label_text ? ' ' . penci_get_setting( 'penci_trans_comments' ) : '';
                                    ?>
                                    <?php comments_number( '0' . $comments_text, '1' . $comment_text, '%' . $comments_text ); ?></span>
						<?php endif; ?>
						<?php if ( ! $settings['penci_single_show_cview'] ) : ?>
                            <span class="pctmp-view-post">

                                        <i class="penci-post-countview-number"><?php echo penci_get_post_views( get_the_ID() ); ?></i><?php if ( ! $label_text ) {
									echo ' ' . penci_get_setting( 'penci_trans_countviews' );
								} ?></span>
						<?php endif; ?>
						<?php if ( penci_isshow_reading_time( $hide_readtime ) ):
							?>
                            <span class="single-readtime">

                                    <?php penci_reading_time(); ?></span>
						<?php endif; ?>
						<?php
						if ( get_the_post_thumbnail_caption() && get_theme_mod( 'penci_post_thumb_caption' ) && ! $move_title_bellow ) {
							echo '<span class="penci-featured-caption penci-fixed-caption penci-caption-relative">' . get_the_post_thumbnail_caption() . '</span>';
						}
						?>
                    </div>
				<?php endif; ?>
				<?php
				$recipe_title = get_post_meta( get_the_ID(), 'penci_recipe_title', true );
				if ( has_shortcode( get_the_content(), 'penci_recipe' ) || $recipe_title ) {
					do_action( 'penci_recipes_action_hook' );
				} ?>
                </div><?php
			}
			echo '</div>';
			if ( ! $move_title_bellow ) {
				echo '</div>';
			}

			if ( get_the_post_thumbnail_caption() && get_theme_mod( 'penci_post_thumb_caption' ) && $move_title_bellow ) {
				echo '<span class="penci-featured-caption penci-fixed-caption">' . get_the_post_thumbnail_caption() . '</span>';
			}
			?>
        </div>
	<?php endif; ?>

<?php endif;
$css = [
	'img_ratio'              => [ '{{WRAPPER}} .penci-single-featured-img,{{WRAPPER}} .penci-jarallax' => 'padding-top: {{VALUE}}% !important;' ],
	'content_overlay_align'  => [ '{{WRAPPER}} .standard-post-special_wrapper,{{WRAPPER}} .standard-post-special_wrapper .penci-fto-ct,{{WRAPPER}} .standard-post-special_wrapper *,{{WRAPPER}} .post-box-meta-single' => 'text-align:{{VALUE}}' ],
	'content_overlay_calign' => [ '{{WRAPPER}} .standard-post-special_wrapper' => 'bottom:30px;top:30px;display:flex;flex-wrap: wrap;flex-direction: column;justify-content:{{VALUE}}' ],
	'hide_breadcrumb'        => [ '{{WRAPPER}} .penci-breadcrumb' => 'display:none' ],
	'hide_title'             => [ '{{WRAPPER}} .single-post-title' => 'display:none' ],
	'hide_info'              => [ '{{WRAPPER}} .post-box-meta-single' => 'display:none' ],
	'meta_divider'           => [ '{{WRAPPER}} .post-box-meta-single > span:before' => 'display:none !important;' ],
	'meta-item-spacing'      => [ '{{WRAPPER}} .post-box-meta-single > span:not(:last-child)' => 'margin-right:{{VALUE}}px;' ],
	'overlay_bgcolor'        => [ '{{WRAPPER}} .penci-move-title-above:after' => 'background:{{VALUE}}' ],
	'overlay_ctbgcolor'      => [ '{{WRAPPER}} .penci-fto-ct' => 'background-color:{{VALUE}}' ],
	'overlay_ctw'            => [ '{{WRAPPER}} .penci-fto-ct' => 'max-width:{{VALUE}}px' ],
	'overlay_align'          => [ '{{WRAPPER}} .penci-fto-ct' => '{{VALUE}}' ],
	'overlay_ctpd'           => [ '{{WRAPPER}} .penci-fto-ct' => 'padding: {{VALUE}}px;' ],
	'overlay_ctbdr'          => [ '{{WRAPPER}} .penci-fto-ct' => 'border-radius:{{VALUE}}px' ],
	'breadcrumb_color'       => [ '{{WRAPPER}} .penci-breadcrumb.single-breadcrumb span, {{WRAPPER}} .penci-breadcrumb.single-breadcrumb i,{{WRAPPER}} .penci-breadcrumb.single-breadcrumb a' => 'color:{{VALUE}} !important' ],
	'breadcrumb_lhcolor'     => [ '{{WRAPPER}} .penci-breadcrumb.single-breadcrumb a:hover' => 'color:{{VALUE}}' ],
	'cat_color'              => [ '{{WRAPPER}} .cat' => 'color:{{VALUE}}' ],
	'cat_lcolor'             => [ '{{WRAPPER}} .cat > a.penci-cat-name' => 'color:{{VALUE}}' ],
	'cat_lhcolor'            => [ '{{WRAPPER}} .cat > a.penci-cat-name:hover' => 'color:{{VALUE}}' ],
	'cat_bgcolor'            => [ '{{WRAPPER}} .cat > a.penci-cat-name' => 'background-color:{{VALUE}}' ],
	'cat_bghcolor'           => [ '{{WRAPPER}} .cat > a.penci-cat-name:hover' => 'background-color:{{VALUE}}' ],
	'cat_bcolor'             => [ '{{WRAPPER}} .cat > a.penci-cat-name' => 'border-color:{{VALUE}}' ],
	'cat_bhcolor'            => [ '{{WRAPPER}} .cat > a.penci-cat-name:hover' => 'border-color:{{VALUE}}' ],
	'cat_border'             => [ '{{WRAPPER}} .cat > a.penci-cat-name' => 'border-width: {{VALUE}}px;' ],
	'cat_border_style'       => [ '{{WRAPPER}} .cat > a.penci-cat-name' => 'border-style:{{VALUE}}' ],
	'cat_border_radius'      => [ '{{WRAPPER}} .cat > a.penci-cat-name' => 'border-radius: {{VALUE}}px;' ],
	'cat_divider'            => [ '{{WRAPPER}} .cat > a.penci-cat-name:after' => 'display:none !important;' ],
	'meta-color'             => [ '{{WRAPPER}} .post-box-meta-single, {{WRAPPER}} .post-box-meta-single span' => 'color:{{VALUE}}' ],
	'meta-link-color'        => [ '{{WRAPPER}} .post-box-meta-single a' => 'color:{{VALUE}}' ],
	'meta-link-hcolor'       => [ '{{WRAPPER}} .post-box-meta-single a:hover' => 'color:{{VALUE}}' ],
	'remove-divider'         => [ '{{WRAPPER}} .post-box-meta-single > span:before' => 'display:none' ],
	'meta-author-color'      => [ '{{WRAPPER}} .author-post,{{WRAPPER}} .author-post .author' => 'color:{{VALUE}}' ],
	'meta-author-lcolor'     => [ '{{WRAPPER}} .author-post a' => 'color:{{VALUE}}' ],
	'meta-author-hcolor'     => [ '{{WRAPPER}} .author-post a:hover' => 'color:{{VALUE}}' ],
	'meta-pdate-color'       => [ '{{WRAPPER}} .pctmp-date-post' => 'color:{{VALUE}} !important' ],
	'meta-pcomment-color'    => [ '{{WRAPPER}} .pctmp-comment-post' => 'color:{{VALUE}} !important' ],
	'meta-pview-color'       => [ '{{WRAPPER}} .pctmp-view-post' => 'color:{{VALUE}} !important' ],
	'meta-preading-color'    => [ '{{WRAPPER}} .single-readtime' => 'color:{{VALUE}} !important' ],
	'title_color'            => [ '{{WRAPPER}} .header-standard .post-title' => 'color:{{VALUE}}' ],
	'subtitle_color'         => [ '{{WRAPPER}} .penci-psub-title' => 'color:{{VALUE}}' ],
	'postinfo_color'         => [ '{{WRAPPER}} .header-standard .post-box-meta-single span' => 'color:{{VALUE}}' ],
	'postinfo_lcolor'        => [ '{{WRAPPER}} .header-standard .post-box-meta-single span a' => 'color:{{VALUE}}' ],
	'postinfo_lhcolor'       => [ '{{WRAPPER}} .header-standard .post-box-meta-single span a:hover' => 'color:{{VALUE}}' ],
	'postinfo_bgcolor'       => [ '{{WRAPPER}} .post-box-meta-single > span' => 'background-color:{{VALUE}}' ],
	'postinfo_bcolor'        => [ '{{WRAPPER}} .post-box-meta-single > span' => 'border-color:{{VALUE}}' ],
];
penci_wpbakery_el_extract_css( $css, $settings, '#' . $block_id );
