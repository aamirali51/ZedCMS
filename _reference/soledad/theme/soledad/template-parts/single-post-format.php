<?php
$thumb_alt = $thumb_title_html = '';

if ( has_post_thumbnail() ) {
	$thumb_id         = get_post_thumbnail_id( get_the_ID() );
	$thumb_alt        = penci_get_image_alt( $thumb_id, get_the_ID() );
	$thumb_title_html = penci_get_image_title( $thumb_id );
}

$sidebar_position = penci_get_posts_sidebar_class();

$single_style        = penci_get_single_style();
$move_title_bellow   = get_theme_mod( 'penci_move_title_bellow' );
$enable_jarallax     = get_theme_mod( 'penci_enable_jarallax_single' );
$pmt_enable_jarallax = get_post_meta( get_the_ID(), 'penci_enable_jarallax_single', true );

if ( $pmt_enable_jarallax ) {
	$enable_jarallax = $pmt_enable_jarallax;
}

$image_size = 'penci-single-full';

$author_html = '';

if ( 'style-22' == $single_style ) {
	$author_html = '<div class="penci-author-wrapper"><a href="'.get_author_posts_url( get_current_user_id() ).'">' . get_avatar( get_current_user_id(), 100 ) . '</a></div>';
}

if ( 'two-sidebar' != $sidebar_position && in_array( $single_style, array(
		'style-3',
		'style-6',
		'style-8',
		'style-9',
		'style-10',
		'style-11',
		'style-13',
	) ) ) {
	$image_size = get_theme_mod( 'penci_single_custom_thumbnail_size' ) ? get_theme_mod( 'penci_single_custom_thumbnail_size' ) : 'penci-full-thumb';
}

if ( penci_is_mobile() ) {
	$image_size = 'penci-masonry-thumb';
}

$before_special_wrapper = $after_special_wrapper = '';
if ( 'style-4' == $single_style || 'style-7' == $single_style || 'style-11' == $single_style ) {
	$before_special_wrapper = '<div class="standard-post-special_wrapper container">';
	$after_special_wrapper  = '</div>';
}

$image_html = penci_get_featured_single_image_size( get_the_ID(), $image_size, $enable_jarallax, $thumb_alt );

?>
<?php if ( penci_get_post_format( 'link' ) || penci_get_post_format( 'quote' ) ) : ?>
	<?php
	$class_pimage_linkquote = 'standard-post-special post-image';
	if ( penci_get_post_format( 'quote' ) ) {
		$class_pimage_linkquote .= ' penci-special-format-quote';
	}
	if ( ! has_post_thumbnail() || get_theme_mod( 'penci_standard_thumbnail' ) ) {
		$class_pimage_linkquote .= ' no-thumbnail';
	}

	if ( $enable_jarallax ) {
		$class_pimage_linkquote .= ' penci-jarallax';
	}

	?>
    <div class="<?php echo( $class_pimage_linkquote ); ?>">
		<?php
		echo $author_html;
		if ( has_post_thumbnail() && ! get_theme_mod( 'penci_standard_thumbnail' ) ) {
			echo $image_html;
		}
		?>
		<?php echo $before_special_wrapper; ?>
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
		<?php echo $after_special_wrapper; ?>
    </div>

<?php elseif ( penci_get_post_format( 'gallery' ) ) : ?>

	<?php $images = get_post_meta( $post->ID, '_format_gallery_images', true ); ?>

	<?php if ( $images ) :
		$autoplay = ! get_theme_mod( 'penci_disable_autoplay_single_slider' ) ? 'true' : 'false';
		?>
        <div class="post-image">
			<?php echo $author_html; ?>
            <div class="swiper penci-owl-carousel penci-owl-carousel-slider penci-nav-visible"
                 data-auto="<?php echo $autoplay; ?>" data-lazy="true">
                <div class="swiper-wrapper">
					<?php foreach ( $images as $image ) : ?>

						<?php $the_image = wp_get_attachment_image_src( $image, $image_size ); ?>
						<?php $the_caption = get_post_field( 'post_excerpt', $image );
						$image_alt         = penci_get_image_alt( $image, get_the_ID() );
						$image_title_html  = penci_get_image_title( $image );
						?>
                        <div class="swiper-slide swiper-mark-item">
                            <figure class="penci-swiper-mask item-link-relative">
								<?php if ( get_theme_mod( 'penci_speed_disable_first_screen' ) || ! get_theme_mod( 'penci_disable_lazyload_fsingle' ) ) { ?>
									<?php echo penci_get_ratio_img_format_gallery( $the_image ); ?>
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
	Penci_Sodedad_Video_Format::show_video_embed( array(
		'post_id'      => $post->ID,
		'parallax'     => $enable_jarallax,
		'args'         => array( 'width' => '1920', 'height' => '1080' ),
		'single_style' => $single_style
	) );
	?>
<?php elseif ( penci_get_post_format( 'audio' ) ) : ?>
	<?php
	if ( has_post_thumbnail() && ! get_theme_mod( 'penci_post_thumb' ) ) {
		$class_pimage_audio = 'standard-post-image post-image audio';

		if ( $enable_jarallax ) {
			$class_pimage_audio .= ' penci-jarallax';
		}

		?>
        <div class="<?php echo $class_pimage_audio; ?>">
			<?php echo $author_html; ?>
			<?php echo $image_html; ?>
			<?php echo $before_special_wrapper; ?>
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
			<?php echo $after_special_wrapper; ?>
        </div>
	<?php } ?>
<?php else : ?>

	<?php if ( has_post_thumbnail() && ! get_theme_mod( 'penci_post_thumb' ) ) : ?>
		<?php
		$class_stand_style = 'post-image';
		if ( in_array( $single_style, array( 'style-6', 'style-5', 'style-8', 'style-10', 'style-13' ) ) ) {
			$class_stand_style .= ' penci-header-text-white';
		}

		if ( $enable_jarallax ) {
			$class_stand_style .= ' penci-jarallax';
		}
		?>
        <div class="<?php echo $class_stand_style; ?>">
			<?php
			echo $author_html;
			if ( ! get_theme_mod( 'penci_disable_lightbox_single' ) && ! $enable_jarallax ) {
				$thumb_url = wp_get_attachment_url( get_post_thumbnail_id( $post->ID ) );
				echo '<a href="' . esc_url( $thumb_url ) . '" data-rel="penci-gallery-bground-content">';
				echo $image_html;
				echo '</a>';
			} else {
				echo $image_html;
			}

			if ( get_the_post_thumbnail_caption() && get_theme_mod( 'penci_post_thumb_caption' ) ) {
				echo '<div class="penci-featured-caption penci-fixed-caption">' . get_the_post_thumbnail_caption() . '</div>';
			}
			?>
        </div>
	<?php endif; ?>

<?php endif; ?>
