<?php
/**
 * The template for displaying single pages.
 *
 * @since 1.0
 */
$block_style   = get_theme_mod( 'penci_blockquote_style' ) ? get_theme_mod( 'penci_blockquote_style' ) : 'style-1';
$heading_style = penci_get_heading_style();
$thumb_alt     = $thumb_title_html = '';
$simage_size   = get_theme_mod( 'penci_single_custom_thumbnail_size' ) ? get_theme_mod( 'penci_single_custom_thumbnail_size' ) : 'penci-full-thumb';
if ( penci_is_mobile() ) {
	$simage_size = 'penci-masonry-thumb';
}
if ( has_post_thumbnail() ) {
	$thumb_id         = get_post_thumbnail_id( get_the_ID() );
	$thumb_alt        = penci_get_image_alt( $thumb_id, get_the_ID() );
	$thumb_title_html = penci_get_image_title( $thumb_id );

	$image_width  = penci_get_image_data_based_post_id( get_the_ID(), $simage_size, 'w', false );
	$image_height = penci_get_image_data_based_post_id( get_the_ID(), $simage_size, 'h', false );
}
$single_style     = penci_get_single_style();
$hide_featuimg    = get_post_meta( get_the_ID(), 'penci_post_hide_featuimg', true );
$show_featuredimg = true;
if ( get_theme_mod( 'penci_post_thumb' ) ) {
	$show_featuredimg = false;
}
if ( $hide_featuimg == 'yes' ) {
	$show_featuredimg = false;
}
if ( $hide_featuimg == 'no' ) {
	$show_featuredimg = true;
}

$style_cscount = get_theme_mod( 'penci_single_style_cscount' );
$style_cscount = $style_cscount ? $style_cscount : 's1';
$hide_readtime = get_theme_mod( 'penci_single_hreadtime' );
?>
<article id="post-<?php the_ID(); ?>" class="post type-post status-publish<?php do_action( 'penci_post_class' ); ?>">

	<?php if ( 'style-2' != $single_style ) : ?>

		<?php if ( ! get_theme_mod( 'penci_move_title_bellow' ) ) : ?>

            <div class="header-standard header-classic single-header">
				<?php do_action( 'penci_before_main_post_title' ); ?>
				<?php if ( ! get_theme_mod( 'penci_post_cat' ) ) : ?>
                    <div class="penci-standard-cat"><span class="cat"><?php penci_category( '' ); ?></span></div>
				<?php endif; ?>


                <h1 class="post-title single-post-title entry-title"><?php the_title(); ?></h1>

				<?php penci_display_post_subtitle(); ?>
				<?php penci_soledad_meta_schema(); ?>
				<?php if ( ! get_theme_mod( 'penci_single_meta_author' ) || ! get_theme_mod( 'penci_single_meta_date' ) || ! get_theme_mod( 'penci_single_meta_comment' ) || get_theme_mod( 'penci_single_show_cview' ) || penci_isshow_reading_time( $hide_readtime ) ) : ?>
                    <div class="post-box-meta-single">
						<?php if ( ! get_theme_mod( 'penci_single_meta_author' ) ) : ?>
                            <span class="author-post byline"><span
                                        class="author vcard"><?php echo penci_get_setting( 'penci_trans_written_by' ); ?> <?php
									if ( function_exists( 'coauthors_posts_links' ) ) :
										penci_coauthors_posts_links();
									else :
										?>
                                        <a class="author-url url fn n"
                                           href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>"><?php the_author(); ?></a>
									<?php endif; ?></span>
                            </span>
						<?php endif; ?>
						<?php penci_author_update_name(); ?>
						<?php if ( ! get_theme_mod( 'penci_single_meta_date' ) ) : ?>
                            <span><?php penci_soledad_time_link( 'single' ); ?></span>
						<?php endif; ?>
						<?php if ( ! get_theme_mod( 'penci_single_meta_comment' ) && 's1' != $style_cscount ) : ?>
                            <span><?php comments_number( '0 ' . penci_get_setting( 'penci_trans_comments' ), '1 ' . penci_get_setting( 'penci_trans_comment' ), '% ' . penci_get_setting( 'penci_trans_comments' ) ); ?></span>
						<?php endif; ?>
						<?php if ( get_theme_mod( 'penci_single_show_cview' ) ) : ?>
                            <span><i class="penci-post-countview-number"><?php echo penci_get_post_views( get_the_ID() ); ?></i> <?php echo penci_get_setting( 'penci_trans_countviews' ); ?></span>
						<?php endif; ?>
						<?php if ( penci_isshow_reading_time( $hide_readtime ) ) : ?>
                            <span class="single-readtime"><?php penci_reading_time(); ?></span>
						<?php endif; ?>
						<?php do_action( 'penci_single_meta_content' ); ?>
                    </div>
				<?php endif; ?>
				<?php do_action( 'penci_after_main_post_title' ); ?>
            </div>

			<?php
			if ( 'btitle-bcontent' == get_theme_mod( 'penci_single_poslcscount' ) || 'btitle' == get_theme_mod( 'penci_single_poslcscount' ) ) {
				get_template_part( 'template-parts/single-meta-comment-top' );
			}
			?>

			<?php if ( get_theme_mod( 'penci_post_adsense_one' ) ) : ?>
                <div class="penci-google-adsense-1">
					<?php echo do_shortcode( get_theme_mod( 'penci_post_adsense_one' ) ); ?>
                </div>
			<?php endif; ?>

			<?php
			$recipe_title = get_post_meta( get_the_ID(), 'penci_recipe_title', true );
			if ( has_shortcode( get_the_content(), 'penci_recipe' ) || $recipe_title ) {
				do_action( 'penci_recipes_action_hook' );
			}
			?>

		<?php endif; /* End check if not move title bellow featured image */ ?>

		<?php if ( penci_get_post_format( 'link' ) || penci_get_post_format( 'quote' ) ) : ?>
            <div class="standard-post-special post-image
			<?php
			if ( penci_get_post_format( 'quote' ) ) :
				?>
				penci-special-format-quote<?php endif; ?>
											<?php
			if ( ! has_post_thumbnail() || get_theme_mod( 'penci_standard_thumbnail' ) ) :
				echo ' no-thumbnail';
			endif;
			?>
">
				<?php if ( has_post_thumbnail() && ! get_theme_mod( 'penci_standard_thumbnail' ) ) : ?>
					<?php if ( get_theme_mod( 'penci_speed_disable_first_screen' ) || ! get_theme_mod( 'penci_disable_lazyload_fsingle' ) ) { ?>
                        <img class="attachment-penci-full-thumb size-penci-full-thumb penci-lazy wp-post-image pc-singlep-img"
                             src="<?php echo penci_holder_image_base( $image_width, $image_height ); ?>"
                             alt="<?php echo $thumb_alt; ?>"<?php echo $thumb_title_html; ?>
                             width="<?php echo $image_width; ?>" height="<?php echo $image_height; ?>"
                             data-src="<?php echo penci_get_featured_image_size( get_the_ID(), $simage_size ); ?>">
					<?php } else { ?>
						<?php the_post_thumbnail( $simage_size, array( 'class' => 'pc-singlep-img' ) ); ?>
					<?php } ?>
				<?php endif; ?>
                <div class="standard-content-special">
                    <div class="format-post-box
					<?php
					if ( penci_get_post_format( 'quote' ) ) {
						echo ' penci-format-quote';
					} else {
						echo ' penci-format-link';
					}
					?>
					">
                        <span class="post-format-icon"><?php penci_fawesome_icon( 'fas fa-' . ( penci_get_post_format( 'quote' ) ? 'quote-left' : 'link' ) ); ?></span>
                        <p class="dt-special">
							<?php
							if ( penci_get_post_format( 'quote' ) ) {
								$dt_content = get_post_meta( $post->ID, '_format_quote_source_name', true );
								if ( ! empty( $dt_content ) ) :
									echo sanitize_text_field( $dt_content );
								endif;
							} else {
								$dt_content = get_post_meta( $post->ID, '_format_link_url', true );
								if ( ! empty( $dt_content ) ) :
									echo '<a href="' . esc_url( $dt_content ) . '" target="_blank">' . sanitize_text_field( $dt_content ) . '</a>';
								endif;
							}
							?>
                        </p>
						<?php
						if ( penci_get_post_format( 'quote' ) ) :
							$quote_author = get_post_meta( $post->ID, '_format_quote_source_url', true );
							if ( ! empty( $quote_author ) ) :
								echo '<div class="author-quote"><span>' . sanitize_text_field( $quote_author ) . '</span></div>';
							endif;
						endif;
						?>
                    </div>
                </div>
            </div>

		<?php elseif ( penci_get_post_format( 'gallery' ) ) : ?>

			<?php $images = get_post_meta( $post->ID, '_format_gallery_images', true ); ?>

			<?php
			if ( $images ) :
				$autoplay = ! get_theme_mod( 'penci_disable_autoplay_single_slider' ) ? 'true' : 'false';
				?>
                <div class="post-image">
                    <div class="swiper penci-owl-carousel penci-owl-carousel-slider penci-nav-visible"
                         data-auto="<?php echo $autoplay; ?>" data-lazy="true">
                        <div class="swiper-wrapper">
							<?php foreach (
								$images

								as $image
							) : ?>

								<?php $the_image = wp_get_attachment_image_src( $image, $simage_size ); ?>
								<?php
								$the_caption      = get_post_field( 'post_excerpt', $image );
								$image_alt        = penci_get_image_alt( $image, get_the_ID() );
								$image_title_html = penci_get_image_title( $image );
								?>
                                <div class="swiper-slide swiper-mark-item">
                                    <figure class="penci-swiper-mask item-link-relative">
										<?php if ( get_theme_mod( 'penci_speed_disable_first_screen' ) || ! get_theme_mod( 'penci_disable_lazyload_fsingle' ) ) { ?>
											<?php echo penci_get_ratio_img_format_gallery( $the_image ); ?>
                                            <img class="penci-lazy"
                                                 src="<?php echo penci_holder_image_base( $the_image[1], $the_image[2] ); ?>"
                                                 width="<?php echo $the_image[1]; ?>"
                                                 height="<?php echo $the_image[2]; ?>"
                                                 data-src="<?php echo esc_url( $the_image[0] ); ?>"
                                                 alt="<?php echo $image_alt; ?>"<?php echo $image_title_html; ?> />
										<?php } else { ?>
                                            <img src="<?php echo esc_url( $the_image[0] ); ?>"
                                                 width="<?php echo $the_image[1]; ?>"
                                                 height="<?php echo $the_image[2]; ?>"
                                                 alt="<?php echo $image_alt; ?>"<?php echo $image_title_html; ?> />
										<?php } ?>
										<?php if ( get_theme_mod( 'penci_post_gallery_caption' ) && $the_caption ) : ?>
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

            <div class="post-image">
				<?php $penci_video = get_post_meta( $post->ID, '_format_video_embed', true ); ?>
				<?php if ( wp_oembed_get( $penci_video ) ) : ?>
					<?php echo wp_oembed_get( $penci_video ); ?>
				<?php else : ?>
					<?php echo $penci_video; ?>
				<?php endif; ?>
            </div>

		<?php elseif ( penci_get_post_format( 'audio' ) ) : ?>

            <div class="standard-post-image post-image audio
			<?php
			if ( ! has_post_thumbnail() || get_theme_mod( 'penci_post_thumb' ) ) :
				echo ' no-thumbnail';
			endif;
			?>
			">
				<?php if ( has_post_thumbnail() && ! get_theme_mod( 'penci_post_thumb' ) ) : ?>
					<?php if ( get_theme_mod( 'penci_speed_disable_first_screen' ) || ! get_theme_mod( 'penci_disable_lazyload_fsingle' ) ) { ?>
                        <img class="attachment-penci-full-thumb size-penci-full-thumb penci-lazy wp-post-image pc-singlep-img"
                             width="<?php echo $image_width; ?>" height="<?php echo $image_height; ?>"
                             src="<?php echo penci_holder_image_base( $image_width, $image_height ); ?>"
                             alt="<?php echo $thumb_alt; ?>"<?php echo $thumb_title_html; ?>
                             data-src="<?php echo penci_get_featured_image_size( get_the_ID(), $simage_size ); ?>">
					<?php } else { ?>
						<?php the_post_thumbnail( $simage_size, array( 'class' => 'pc-singlep-img' ) ); ?>
					<?php } ?>
				<?php endif; ?>
                <div class="audio-iframe">
					<?php
					$penci_audio     = get_post_meta( $post->ID, '_format_audio_embed', true );
					$penci_audio_str = substr( $penci_audio, - 4 );
					?>
					<?php if ( wp_oembed_get( $penci_audio ) ) : ?>
						<?php echo wp_oembed_get( $penci_audio ); ?>
					<?php elseif ( $penci_audio_str == '.mp3' ) : ?>
						<?php echo do_shortcode( '[audio src="' . esc_url( $penci_audio ) . '"]' ); ?>
					<?php else : ?>
						<?php echo $penci_audio; ?>
					<?php endif; ?>
                </div>
            </div>

		<?php else : ?>

			<?php if ( has_post_thumbnail() ) : ?>
				<?php if ( $show_featuredimg == true ) : ?>
                    <div class="post-image">
						<?php
						if ( ! get_theme_mod( 'penci_disable_lightbox_single' ) ) {
							$thumb_url = wp_get_attachment_url( get_post_thumbnail_id( $post->ID ) );
							echo '<a href="' . esc_url( $thumb_url ) . '" data-rel="penci-gallery-image-content">';
							?>
							<?php if ( get_theme_mod( 'penci_speed_disable_first_screen' ) || ! get_theme_mod( 'penci_disable_lazyload_fsingle' ) ) { ?>
                                <img class="attachment-penci-full-thumb size-penci-full-thumb penci-lazy wp-post-image pc-singlep-img"
                                     width="<?php echo $image_width; ?>" height="<?php echo $image_height; ?>"
                                     src="<?php echo penci_holder_image_base( $image_width, $image_height ); ?>"
                                     alt="<?php echo $thumb_alt; ?>"<?php echo $thumb_title_html; ?>
                                     data-sizes="<?php echo penci_image_datasize( $simage_size, 'penci-masonry-thumb' ); ?>"
                                     data-srcset="<?php echo penci_image_img_srcset( get_the_ID(), $simage_size, 'penci-masonry-thumb' ); ?>"
                                     data-src="<?php echo penci_get_featured_image_size( get_the_ID(), $simage_size ); ?>">
							<?php } else { ?>
								<?php the_post_thumbnail( $simage_size, array( 'class' => 'pc-singlep-img' ) ); ?>
							<?php } ?>
							<?php
							echo '</a>';
						} else {
							?>
							<?php if ( get_theme_mod( 'penci_speed_disable_first_screen' ) || ! get_theme_mod( 'penci_disable_lazyload_fsingle' ) ) { ?>
                                <img class="attachment-penci-full-thumb size-penci-full-thumb penci-lazy wp-post-image pc-singlep-img"
                                     width="<?php echo $image_width; ?>" height="<?php echo $image_height; ?>"
                                     src="<?php echo penci_holder_image_base( $image_width, $image_height ); ?>"
                                     alt="<?php echo $thumb_alt; ?>"<?php echo $thumb_title_html; ?>
                                     data-src="<?php echo penci_get_featured_image_size( get_the_ID(), $simage_size ); ?>">
							<?php } else { ?>
								<?php the_post_thumbnail( $simage_size, array( 'class' => 'pc-singlep-img' ) ); ?>
							<?php } ?>
							<?php
						}
						if ( get_the_post_thumbnail_caption() && get_theme_mod( 'penci_post_thumb_caption' ) ) {
							echo '<div class="penci-featured-caption">' . get_the_post_thumbnail_caption() . '</div>';
						}
						?>
                    </div>
				<?php endif; ?>
			<?php endif; ?>

		<?php endif; ?>

	<?php endif; /* End check if single style 2 is enable */ ?>

	<?php if ( 'style-2' == $single_style ) : ?>
		<?php if ( get_theme_mod( 'penci_post_adsense_one' ) ) : ?>
            <div class="penci-google-adsense-1">
				<?php echo do_shortcode( get_theme_mod( 'penci_post_adsense_one' ) ); ?>
            </div>
		<?php endif; ?>
	<?php endif; ?>

	<?php if ( get_theme_mod( 'penci_move_title_bellow' ) && 'style-2' != $single_style ) : ?>

        <div class="header-standard header-classic single-header">
			<?php do_action( 'penci_before_main_post_title' ); ?>
			<?php if ( ! get_theme_mod( 'penci_post_cat' ) ) : ?>
                <div class="penci-standard-cat"><span class="cat"><?php penci_category( '' ); ?></span></div>
			<?php endif; ?>


            <h1 class="post-title single-post-title entry-title"><?php the_title(); ?></h1>

			<?php penci_display_post_subtitle(); ?>
			<?php penci_soledad_meta_schema(); ?>

			<?php if ( ! get_theme_mod( 'penci_single_meta_author' ) || ! get_theme_mod( 'penci_single_meta_date' ) || ! get_theme_mod( 'penci_single_meta_comment' ) || get_theme_mod( 'penci_single_show_cview' ) ) : ?>
                <div class="post-box-meta-single">
					<?php if ( ! get_theme_mod( 'penci_single_meta_author' ) ) : ?>
                        <span class="author-post byline"><span
                                    class="author vcard"><?php echo penci_get_setting( 'penci_trans_written_by' ); ?> <?php
								if ( function_exists( 'coauthors_posts_links' ) ) :
									penci_coauthors_posts_links();
								else :
									?>
                                    <a class="author-url url fn n"
                                       href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>"><?php the_author(); ?></a>
								<?php endif; ?></span></span>
					<?php endif; ?>
					<?php penci_author_update_name(); ?>
					<?php if ( ! get_theme_mod( 'penci_single_meta_date' ) ) : ?>
                        <span><?php penci_soledad_time_link( 'single' ); ?></span>
					<?php endif; ?>
					<?php if ( ! get_theme_mod( 'penci_single_meta_comment' ) && 's1' != $style_cscount ) : ?>
                        <span><?php comments_number( '0 ' . penci_get_setting( 'penci_trans_comments' ), '1 ' . penci_get_setting( 'penci_trans_comment' ), '% ' . penci_get_setting( 'penci_trans_comments' ) ); ?></span>
					<?php endif; ?>
					<?php if ( get_theme_mod( 'penci_single_show_cview' ) ) : ?>
                        <span><i class="penci-post-countview-number"><?php echo penci_get_post_views( get_the_ID() ); ?></i> <?php echo penci_get_setting( 'penci_trans_countviews' ); ?></span>
					<?php endif; ?>
					<?php if ( penci_isshow_reading_time( $hide_readtime ) ) : ?>
						<span class="single-readtime"><?php penci_reading_time(); ?></span>
					<?php endif; ?>
					<?php do_action( 'penci_single_meta_content' ); ?>
                </div>
			<?php endif; ?>
			<?php do_action( 'penci_after_main_post_title' ); ?>
        </div>

		<?php if ( get_theme_mod( 'penci_post_adsense_one' ) ) : ?>
            <div class="penci-google-adsense-1">
				<?php echo do_shortcode( get_theme_mod( 'penci_post_adsense_one' ) ); ?>
            </div>
		<?php endif; ?>

		<?php
		$recipe_title = get_post_meta( get_the_ID(), 'penci_recipe_title', true );
		if ( has_shortcode( get_the_content(), 'penci_recipe' ) || $recipe_title ) {
			do_action( 'penci_recipes_action_hook' );
		}
		?>

	<?php endif; /* End check if not move title bellow featured image */ ?>

	<?php
	$single_poslcscount = penci_get_setting( 'penci_single_poslcscount' );
	if ( 'above-content' == $single_poslcscount || 'abovebelow-content' == $single_poslcscount ) {
		get_template_part( 'template-parts/single', 'meta-comment-top' );
	}
	if ( 'sticky-left' == $single_poslcscount ) {
		get_template_part( 'template-parts/single', 'meta-sticky' );
	}
	?>
    <div class="post-entry <?php echo 'blockquote-' . $block_style; ?> <?php echo $heading_style; ?>">
        <div class="inner-post-entry entry-content" id="penci-post-entry-inner">

			<?php do_action( 'penci_action_before_the_content' ); ?>

			<?php
			if ( get_theme_mod( 'penci_single_delayed_content' ) && penci_is_mobile() ) {
				?>
                <div data-id="section-p-content-<?php echo get_the_ID(); ?>"
                     data-url="<?php echo esc_url( add_query_arg( [ 'penci_get_delayed_sections' => 'content' ], get_permalink() ) ); ?>"
                     class="pcfb-single-content-delayed pc-content-delayed"></div>
				<?php
			} else {

				$smartlists_enable = get_post_meta( get_the_ID(), 'pcsml_smartlists_enable', true );
				$smartlists_style  = get_post_meta( get_the_ID(), 'pcsml_smartlists_style', true );
				$smartlists_h      = get_post_meta( get_the_ID(), 'pcsml_smartlists_h', true );
				$smartlists_order  = get_post_meta( get_the_ID(), 'pcsml_smartlists_order', true );

				if ( 'yes' == $smartlists_enable ) {
					$content = get_the_content();
					$content = apply_filters( 'the_content', $content );
					$content = str_replace( ']]>', ']]&gt;', $content );
					remove_filter( 'the_content', 'penci_insert_post_content_ads' );
					add_filter( 'penci_toc_maybe_apply_the_content_filter', function () {
						return false;
					}, 10 );
					$smartlists_content = penci_smartlists( [
						'style'        => $smartlists_style,
						'content'      => $content,
						'order'        => $smartlists_order,
						'h'            => $smartlists_h,
						'sm_title_tag' => $smartlists_h,
					] );
					if ( $smartlists_content ) {
						echo '<div class="pcsml-el pcsml-customized-ver scmchck">' . $smartlists_content . '</div>';
					}
				} else {
					the_content();
				}
			}
			?>

			<?php do_action( 'penci_action_after_the_content' ); ?>

            <div class="penci-single-link-pages">
				<?php wp_link_pages(); ?>
            </div>

			<?php if ( ! get_theme_mod( 'penci_post_tags' ) && has_tag() ) : ?>
				<?php if ( is_single() ) : ?>
                    <div class="post-tags">
						<?php the_tags( wp_kses( '', penci_allow_html() ), '', '' ); ?>
                    </div>
				<?php endif; ?>
			<?php endif; ?>

			<?php do_action( 'penci_end_single_content' ); ?>
        </div>
    </div>

	<?php if ( get_theme_mod( 'penci_post_adsense_two' ) ) : ?>
        <div class="penci-google-adsense-2">
			<?php echo do_shortcode( get_theme_mod( 'penci_post_adsense_two' ) ); ?>
        </div>
	<?php endif; ?>

	<?php
	if ( 'below-content' == $single_poslcscount || 'abovebelow-content' == $single_poslcscount || 'btitle-bcontent' == $single_poslcscount ) {
		get_template_part( 'template-parts/single', 'meta-comment' );
	}
	?>

	<?php
	if ( get_theme_mod( 'penci_single_sec_delayed' ) ) {
		$is_log = is_user_logged_in() ? 'log' : 'no-log';
		?>
        <div data-id="section-p-<?php echo get_the_ID() . '-' . $is_log; ?>"
             data-url="<?php echo esc_url( add_query_arg( [ 'penci_get_delayed_sections' => 'sections' ], get_permalink() ) ); ?>"
             class="pcfb-single-sections-delayed pc-content-delayed"></div>
		<?php
	} else {
		get_template_part( 'template-parts/single-sections' );
	}
	?>

	<?php if ( get_theme_mod( 'penci_related_post_popup' ) ) : ?>
        <div class="penci-flag-rlt-popup"></div><?php endif; ?>

</article>
