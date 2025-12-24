<?php
/**
 * Content classic homepage, archive page
 *
 * @since 1.0
 */
$post_id = get_the_ID();
if ( ! isset ( $j ) ) {
	$j = 1;
} else {
	$j = $j;
}
$block_style   = get_theme_mod( 'penci_blockquote_style' ) ? get_theme_mod( 'penci_blockquote_style' ) : 'style-1';
$heading_style = penci_get_heading_style();
$thumb_alt     = $thumb_title_html = '';

$post_class     = get_post_class( '', get_the_ID() );
$standard_class = 'standard-article classic-pitem ' . esc_attr( implode( ' ', $post_class ) );

$heading_title_tag = get_theme_mod( 'penci_standard_title_tag', 'h2' );
$heading_title_tag_class = 'h2' == $heading_title_tag ? 'pc_titlebig_standard_df' : 'pc_titlebig_standard';

if ( has_post_thumbnail() ) {
	$thumb_id         = get_post_thumbnail_id( get_the_ID() );
	$thumb_alt        = penci_get_image_alt( $thumb_id, get_the_ID() );
	$thumb_title_html = penci_get_image_title( $thumb_id );
}
?>
<article id="post-<?php the_ID(); ?>" class="<?php echo $standard_class; ?>">

    <div class="header-standard header-classic">
		<?php if ( ! get_theme_mod( 'penci_standard_cat' ) ) : ?>
            <div class="penci-standard-cat"><span class="cat"><?php penci_category( '' ); ?></span></div>
		<?php endif; ?>

        <<?php echo $heading_title_tag;?> class="penci-entry-title entry-title entry-title <?php echo $heading_title_tag_class;?>"><a
                    href="<?php the_permalink(); ?>"><?php the_title(); ?></a></<?php echo $heading_title_tag;?>>
		<?php do_action( 'penci_after_classic_post_title' ); ?>
		<?php penci_soledad_meta_schema(); ?>

		<?php $hide_readtime = get_theme_mod( 'penci_standard_readingtime' );
		if ( ! get_theme_mod( 'penci_standard_author' ) || penci_isshow_reading_time( $hide_readtime ) ) : ?>
            <div class="standard-top-meta author-post byline">
				<?php do_action( 'penci_before_classic_post_meta' ); ?>
				<?php if ( ! get_theme_mod( 'penci_standard_author' ) ) : ?>
                    <span class="author vcard"><?php echo penci_get_setting( 'penci_trans_written_by' ); ?> <?php if ( function_exists( 'coauthors_posts_links' ) ) :
							penci_coauthors_posts_links();
						else: ?>
                            <a class="author-url url fn n"
                               href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>"><?php the_author(); ?></a>
						<?php endif; ?></span>
				<?php endif; ?>
				<?php if ( penci_isshow_reading_time( $hide_readtime ) ) : ?>
                    <span class="penci-readtime"><?php penci_reading_time(); ?></span>
				<?php endif; ?>
            </div>
		<?php endif; ?>
    </div>

	<?php if ( ! get_theme_mod( 'penci_standard_thumbnail' ) ): ?>

		<?php if ( penci_get_post_format( 'link' ) || penci_get_post_format( 'quote' ) ) : ?>
            <div class="standard-post-special<?php if ( penci_get_post_format( 'quote' ) ): ?> penci-special-format-quote<?php endif; ?><?php if ( ! has_post_thumbnail() || get_theme_mod( 'penci_standard_thumbnail' ) ) : echo ' no-thumbnail'; endif; ?>">
				<?php if ( has_post_thumbnail() && ! get_theme_mod( 'penci_standard_thumbnail' ) ) : ?>
					<?php if ( ! get_theme_mod( 'penci_disable_lazyload_layout' ) ) { ?>
                        <a <?php if ( get_theme_mod( 'penci_standard_thumb_crop' ) ): ?> class="penci-image-holder penci-lazy" data-bgset="<?php echo penci_get_featured_image_size( get_the_ID(), 'penci-full-thumb' ); ?>"<?php endif; ?>
                                href="<?php the_permalink() ?>">
							<?php if ( ! get_theme_mod( 'penci_standard_thumb_crop' ) ): ?><img
                                class="attachment-penci-full-thumb size-penci-full-thumb penci-lazy wp-post-image"
                                width="<?php penci_get_image_data_based_post_id( get_the_ID(), 'penci-full-thumb', 'w' ); ?>"
                                height="<?php penci_get_image_data_based_post_id( get_the_ID(), 'penci-full-thumb', 'h' ); ?>"
                                src="<?php echo penci_holder_image_base( penci_get_image_data_based_post_id( get_the_ID(), 'penci-full-thumb', 'w', false ), penci_get_image_data_based_post_id( get_the_ID(), 'penci-full-thumb', 'h', false ) ); ?>" alt="<?php echo $thumb_alt; ?>"<?php echo $thumb_title_html; ?>
                                data-src="<?php echo penci_get_featured_image_size( get_the_ID(), 'penci-full-thumb' ); ?>"><?php endif; ?>
                        </a>
					<?php } else { ?>
                        <a <?php if ( get_theme_mod( 'penci_standard_thumb_crop' ) ): ?> class="penci-image-holder" style="background-image: url('<?php echo penci_get_featured_image_size( get_the_ID(), 'penci-full-thumb' ); ?>');"<?php endif; ?>
                                href="<?php the_permalink() ?>">
							<?php if ( ! get_theme_mod( 'penci_standard_thumb_crop' ) ): ?><?php the_post_thumbnail( 'penci-full-thumb' ); ?><?php endif; ?>
                        </a>
					<?php } ?>
				<?php endif; ?>
                <div class="standard-content-special">
					<?php if ( penci_get_post_format( 'quote' ) ): ?><a href="<?php the_permalink(); ?>"><?php endif; ?>
                        <div class="format-post-box<?php if ( penci_get_post_format( 'quote' ) ) {
							echo ' penci-format-quote';
						} else {
							echo ' penci-format-link';
						} ?>">
                            <span class="post-format-icon"><?php penci_fawesome_icon( 'fas fa-' . ( penci_get_post_format( 'quote' ) ? 'quote-left' : 'link' ) ); ?></span>
                            <p class="dt-special">
								<?php
								if ( penci_get_post_format( 'quote' ) ) {
									$dt_content = get_post_meta( get_the_ID(), '_format_quote_source_name', true );
									if ( ! empty( $dt_content ) ): echo sanitize_text_field( $dt_content ); endif;
								} else {
									$dt_content = get_post_meta( get_the_ID(), '_format_link_url', true );
									if ( ! empty( $dt_content ) ):
										echo '<a href="' . esc_url( get_permalink() ) . '">' . sanitize_text_field( $dt_content ) . '</a>';
									endif;
								}
								?>
                            </p>
							<?php
							if ( penci_get_post_format( 'quote' ) ):
								$quote_author = get_post_meta( get_the_ID(), '_format_quote_source_url', true );
								if ( ! empty( $quote_author ) ):
									echo '<div class="author-quote"><span>' . sanitize_text_field( $quote_author ) . '</span></div>';
								endif;
							endif; ?>
                        </div>
						<?php if ( penci_get_post_format( 'quote' ) ): ?></a><?php endif; ?>
                </div>
            </div>

		<?php elseif ( penci_get_post_format( 'gallery' ) ) : ?>

			<?php $images = get_post_meta( get_the_ID(), '_format_gallery_images', true ); ?>

			<?php if ( $images ) :
				$autoplay = ! get_theme_mod( 'penci_standard_disable_autoplay_gallery' ) ? 'true' : 'false';
				?>
                <div class="standard-post-image classic-post-image">
                    <div class="swiper penci-owl-carousel penci-owl-carousel-slider penci-nav-visible"
                         data-auto="<?php echo $autoplay; ?>">
                        <div class="swiper-wrapper">
							<?php foreach ( $images as $image ) : ?>
                                <div class="swiper-slide swiper-mark-item">
									<?php $the_image = wp_get_attachment_image_src( $image, 'penci-full-thumb' ); ?>
									<?php $the_caption = get_post_field( 'post_excerpt', $image );
									$image_alt         = penci_get_image_alt( $image, get_the_ID() );
									$image_title_html  = penci_get_image_title( $image );
									?>
                                    <figure class="penci-swiper-mask item-link-relative penci-gallery-images"
                                            alt="<?php the_title(); ?>"<?php if ( $the_caption ) : ?> title="<?php echo esc_attr( $the_caption ); ?>"<?php endif; ?>>
										<?php echo penci_get_ratio_img_format_gallery( $the_image ); ?>
                                        <img src="<?php echo esc_url( $the_image[0] ); ?>"
                                             width="<?php echo $the_image[1]; ?>"
                                             height="<?php echo $the_image[2]; ?>"
                                             alt="<?php echo $image_alt; ?>"<?php echo $image_title_html; ?>>
                                    </figure>
                                </div>

							<?php endforeach; ?>
                        </div>
                    </div>
                </div>
			<?php endif; ?>

		<?php elseif ( penci_get_post_format( 'video' ) ) : ?>

            <div class="standard-post-image classic-post-image video-post">
				<?php $penci_video = get_post_meta( get_the_ID(), '_format_video_embed', true ); ?>
				<?php if ( wp_oembed_get( $penci_video ) ) : ?>
					<?php echo wp_oembed_get( $penci_video ); ?>
				<?php else : ?>
					<?php echo $penci_video; ?>
				<?php endif; ?>
            </div>

		<?php elseif ( penci_get_post_format( 'audio' ) ) : ?>

            <div class="standard-post-image classic-post-image audio<?php if ( ! has_post_thumbnail() ) : echo ' no-thumbnail'; endif; ?>">
				<?php if ( has_post_thumbnail() ) : ?>
					<?php if ( ! get_theme_mod( 'penci_disable_lazyload_layout' ) ) { ?>
                        <a <?php if ( get_theme_mod( 'penci_standard_thumb_crop' ) ): ?> class="penci-image-holder penci-lazy" data-bgset="<?php echo penci_get_featured_image_size( get_the_ID(), 'penci-full-thumb' ); ?>"<?php endif; ?>
                                href="<?php the_permalink() ?>">
							<?php if ( ! get_theme_mod( 'penci_standard_thumb_crop' ) ): ?><img
                                class="attachment-penci-full-thumb size-penci-full-thumb penci-lazy wp-post-image"
                                width="<?php penci_get_image_data_based_post_id( get_the_ID(), 'penci-full-thumb', 'w' ); ?>"
                                height="<?php penci_get_image_data_based_post_id( get_the_ID(), 'penci-full-thumb', 'h' ); ?>"
                                src="<?php echo penci_holder_image_base( penci_get_image_data_based_post_id( get_the_ID(), 'penci-full-thumb', 'w', false ), penci_get_image_data_based_post_id( get_the_ID(), 'penci-full-thumb', 'h', false ) ); ?>" alt="<?php echo $thumb_alt; ?>"<?php echo $thumb_title_html; ?>
                                data-src="<?php echo penci_get_featured_image_size( get_the_ID(), 'penci-full-thumb' ); ?>"><?php endif; ?>
                        </a>
					<?php } else { ?>
                        <a <?php if ( get_theme_mod( 'penci_standard_thumb_crop' ) ): ?> class="penci-image-holder" style="background-image: url('<?php echo penci_get_featured_image_size( get_the_ID(), 'penci-full-thumb' ); ?>');"<?php endif; ?>
                                href="<?php the_permalink() ?>">
							<?php if ( ! get_theme_mod( 'penci_standard_thumb_crop' ) ): ?><?php the_post_thumbnail( 'penci-full-thumb' ); ?><?php endif; ?>
                        </a>
					<?php } ?>
				<?php endif; ?>
                <div class="audio-iframe">
					<?php $penci_audio = get_post_meta( get_the_ID(), '_format_audio_embed', true );
					$penci_audio_str   = substr( $penci_audio, - 4 );
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
                <div class="standard-post-image classic-post-image">
					<?php if ( ! get_theme_mod( 'penci_disable_lazyload_layout' ) ) { ?>
                        <a <?php if ( get_theme_mod( 'penci_standard_thumb_crop' ) ): ?> class="penci-image-holder penci-lazy" data-bgset="<?php echo penci_get_featured_image_size( get_the_ID(), 'penci-full-thumb' ); ?>"<?php endif; ?>
                                href="<?php the_permalink() ?>">
							<?php if ( ! get_theme_mod( 'penci_standard_thumb_crop' ) ): ?><img
                                class="attachment-penci-full-thumb size-penci-full-thumb penci-lazy wp-post-image"
                                width="<?php penci_get_image_data_based_post_id( get_the_ID(), 'penci-full-thumb', 'w' ); ?>"
                                height="<?php penci_get_image_data_based_post_id( get_the_ID(), 'penci-full-thumb', 'h' ); ?>"
                                src="<?php echo penci_holder_image_base( penci_get_image_data_based_post_id( get_the_ID(), 'penci-full-thumb', 'w', false ), penci_get_image_data_based_post_id( get_the_ID(), 'penci-full-thumb', 'h', false ) ); ?>" alt="<?php echo $thumb_alt; ?>"<?php echo $thumb_title_html; ?>
                                data-src="<?php echo penci_get_featured_image_size( get_the_ID(), 'penci-full-thumb' ); ?>"><?php endif; ?>
                        </a>
					<?php } else { ?>
                        <a <?php if ( get_theme_mod( 'penci_standard_thumb_crop' ) ): ?> class="penci-image-holder" style="background-image: url('<?php echo penci_get_featured_image_size( get_the_ID(), 'penci-full-thumb' ); ?>');"<?php endif; ?>
                                href="<?php the_permalink() ?>">
							<?php if ( ! get_theme_mod( 'penci_standard_thumb_crop' ) ): ?><?php the_post_thumbnail( 'penci-full-thumb' ); ?><?php endif; ?>
                        </a>
					<?php } ?>
                </div>
			<?php endif; ?>

		<?php endif; /* End Thumbnail */ ?>
	<?php endif; ?>

    <div class="standard-content">
        <div class="standard-main-content entry-content">
            <div class="post-entry standard-post-entry classic-post-entry <?php echo 'blockquote-' . $block_style; ?> <?php echo $heading_style; ?>">
				<?php 
				do_action( 'penci_before_classic_excerpt' );
				if ( get_theme_mod( 'penci_standard_auto_excerpt' ) || isset( $atts['wpblock'] ) ) { ?>
					<?php
					$default_excerpt         = get_theme_mod( 'penci_post_excerpt_length', 30 );
					$standard_excerpt_length = get_theme_mod( 'penci_standard_excerpt_length', $default_excerpt );
					penci_the_excerpt( $standard_excerpt_length, $post_id );
					?>
                    <div class="penci-more-link<?php if ( get_theme_mod( 'penci_standard_continue_reading_button' ) ): echo ' penci-more-link-button'; endif; ?>">
                        <a href="<?php the_permalink(); ?>"
                           class="more-link"><?php echo penci_get_setting( 'penci_trans_continue_reading' ); ?></a>
                    </div>
				<?php } else { ?>
					<?php the_content( penci_get_setting( 'penci_trans_continue_reading' ) ); ?>
					<?php wp_link_pages(); ?>
				<?php } ?>
            </div>
        </div>

		<?php if ( ! get_theme_mod( 'penci_standard_share_box' ) || ! get_theme_mod( 'penci_standard_date' ) || ! get_theme_mod( 'penci_standard_comment' ) || get_theme_mod( 'penci_standard_viewcount' ) ) : ?>
            <div class="penci-post-box-meta<?php if ( get_theme_mod( 'penci_standard_share_box' ) || ( get_theme_mod( 'penci_standard_date' ) && get_theme_mod( 'penci_standard_comment' ) && ! get_theme_mod( 'penci_standard_viewcount' ) ) ): echo ' center-inner'; endif; ?>">
				<?php if ( ! get_theme_mod( 'penci_standard_date' ) || ! get_theme_mod( 'penci_standard_comment' ) || ! get_theme_mod( 'penci_standard_viewcount' ) ) : ?>
                    <div class="penci-box-meta">
						<?php if ( ! get_theme_mod( 'penci_standard_date' ) ) : ?>
                            <span><?php penci_fawesome_icon( 'far fa-clock' ); ?><?php penci_soledad_time_link(); ?></span>
						<?php endif; ?>
						<?php if ( ! get_theme_mod( 'penci_standard_comment' ) ) : ?>
                            <span><a href="<?php comments_link(); ?> "><?php penci_fawesome_icon( 'far fa-comment' ); ?><?php comments_number( '0 ' . penci_get_setting( 'penci_trans_comments' ), '1 ' . penci_get_setting( 'penci_trans_comment' ), '% ' . penci_get_setting( 'penci_trans_comments' ) ); ?></a></span>
						<?php endif; ?>
						<?php
						if ( get_theme_mod( 'penci_standard_viewcount' ) ) {
							penci_get_post_countview( get_the_ID() );
						}
						?>
                    </div>
				<?php endif; ?>
				<?php if ( ! get_theme_mod( 'penci_standard_share_box' ) ) : ?>
                    <div class="penci-post-share-box">
						<?php echo penci_getPostLikeLink( get_the_ID() ); ?>
						<?php penci_soledad_social_share(); ?>
                    </div>
				<?php endif; ?>
            </div>
		<?php endif; ?>
    </div>

</article>

<?php
if ( isset( $infeed_ads ) && $infeed_ads ) {
	penci_get_markup_infeed_ad(
		array(
			'wrapper'    => 'article',
			'classes'    => 'standard-article classic-pitem post penci-infeed-data',
			'fullwidth'  => $infeed_full,
			'order_ad'   => $infeed_num,
			'order_post' => $j,
			'code'       => $infeed_ads,
			'echo'       => true
		)
	);
}
?>

<?php $j ++; ?>
