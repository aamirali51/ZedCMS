<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}


class PenciSingleFeatured extends \Elementor\Widget_Base {

	public function get_title() {
		return esc_html__( 'Post - Featured Image', 'soledad' );
	}

	public function get_icon() {
		return 'eicon-image-rollover';
	}

	public function get_categories() {
		return [ 'penci-single-builder' ];
	}

	public function get_keywords() {
		return [ 'single', 'featured' ];
	}

	protected function get_html_wrapper_class() {
		return 'pcsb-ft elementor-widget-' . $this->get_name();
	}

	public function get_name() {
		return 'penci-single-featured';
	}

	protected function register_controls() {

		$this->start_controls_section( 'content_section', [
			'label' => esc_html__( 'General', 'soledad' ),
			'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
		] );

		$this->add_control( 'penci_single_custom_thumbnail_size', array(
			'label'   => __( 'Image Size', 'soledad' ),
			'type'    => \Elementor\Controls_Manager::SELECT,
			'default' => '',
			'options' => $this->get_list_image_sizes( true ),
		) );

		$this->add_control( 'penci_single_custom_thumbnail_msize', array(
			'label'   => __( 'Image Size for Mobile', 'soledad' ),
			'type'    => \Elementor\Controls_Manager::SELECT,
			'default' => '',
			'options' => $this->get_list_image_sizes( true ),
		) );

		$this->add_control( 'penci_disable_autoplay_single_slider', array(
			'label'       => __( 'Disable Autoplay on Slider', 'soledad' ),
			'description' => __( 'For gallery post format', 'soledad' ),
			'type'        => \Elementor\Controls_Manager::SWITCHER,
			'default'     => '',
		) );

		$this->add_control( 'penci_post_gallery_caption', array(
			'label'   => __( 'Show Image Caption', 'soledad' ),
			'type'    => \Elementor\Controls_Manager::SWITCHER,
			'default' => '',
		) );

		$this->add_control( 'penci_disable_lightbox_single', array(
			'label'   => __( 'Disable Image Lightbox', 'soledad' ),
			'type'    => \Elementor\Controls_Manager::SWITCHER,
			'default' => '',
		) );

		$this->end_controls_section();

		$this->start_controls_section( 'style_section', [
			'label'     => esc_html__( 'Caption Style', 'soledad' ),
			'tab'       => \Elementor\Controls_Manager::TAB_CONTENT,
			'condition' => [ 'penci_post_gallery_caption' => 'yes' ]
		] );

		$this->add_group_control( \Elementor\Group_Control_Typography::get_type(), array(
			'name'     => 'caption_typo',
			'label'    => __( 'Typography for Caption Text', 'soledad' ),
			'selector' => '{{WRAPPER}} .penci-single-gallery-captions',
		) );

		$this->add_control( 'caption_color', array(
			'label'     => __( 'Caption Color', 'soledad' ),
			'type'      => \Elementor\Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .penci-single-gallery-captions' => 'color:{{VALUE}}' ]
		) );

		$this->add_control( 'caption_bgcolor', array(
			'label'     => __( 'Background Color', 'soledad' ),
			'type'      => \Elementor\Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .penci-single-gallery-captions' => 'background-color:{{VALUE}}' ]
		) );

		$this->end_controls_section();

	}

	/**
	 * Get image sizes.
	 *
	 * Retrieve available image sizes after filtering `include` and `exclude` arguments.
	 */
	public function get_list_image_sizes( $default = false ) {
		$wp_image_sizes = $this->get_all_image_sizes();

		$image_sizes = array();

		if ( $default ) {
			$image_sizes[''] = esc_html__( 'Default', 'soledad' );
		}

		foreach ( $wp_image_sizes as $size_key => $size_attributes ) {
			$control_title = ucwords( str_replace( '_', ' ', $size_key ) );
			if ( is_array( $size_attributes ) ) {
				$control_title .= sprintf( ' - %d x %d', $size_attributes['width'], $size_attributes['height'] );
			}

			$image_sizes[ $size_key ] = $control_title;
		}

		$image_sizes['full'] = esc_html__( 'Full', 'soledad' );

		return $image_sizes;
	}

	public function get_all_image_sizes() {
		global $_wp_additional_image_sizes;

		$default_image_sizes = [ 'thumbnail', 'medium', 'medium_large', 'large' ];

		$image_sizes = [];

		foreach ( $default_image_sizes as $size ) {
			$image_sizes[ $size ] = [
				'width'  => (int) get_option( $size . '_size_w' ),
				'height' => (int) get_option( $size . '_size_h' ),
				'crop'   => (bool) get_option( $size . '_crop' ),
			];
		}

		if ( $_wp_additional_image_sizes ) {
			$image_sizes = array_merge( $image_sizes, $_wp_additional_image_sizes );
		}

		return $image_sizes;
	}

	protected function render() {

		if ( penci_elementor_is_edit_mode() ) {
			$this->preview_content();
		} else {
			$this->builder_content();
		}

	}

	protected function preview_content() {
		?>
        <div class="standard-post-special post-image">
            <img src="<?php echo PENCI_SOLEDAD_URL . '/inc/template-builder/placeholder.php?w=1200&h=800'; ?>"
                 alt="">
        </div>
		<?php
	}

	protected function builder_content() {
		$settings       = $this->get_settings();
		$simage_df_size = get_theme_mod( 'penci_single_custom_thumbnail_size' ) ? get_theme_mod( 'penci_single_custom_thumbnail_size' ) : 'penci-full-thumb';
		$simage_size    = $settings['penci_single_custom_thumbnail_size'] ? $settings['penci_single_custom_thumbnail_size'] : $simage_df_size;
		if ( penci_is_mobile() ) {
			$simage_size = $settings['penci_single_custom_thumbnail_msize'] ? $settings['penci_single_custom_thumbnail_msize'] : 'penci-masonry-thumb';
		}
		if ( has_post_thumbnail() ) {
			$thumb_id         = get_post_thumbnail_id( get_the_ID() );
			$thumb_alt        = penci_get_image_alt( $thumb_id, get_the_ID() );
			$thumb_title_html = penci_get_image_title( $thumb_id );

			$image_width  = penci_get_image_data_based_post_id( get_the_ID(), $simage_size, 'w', false );
			$image_height = penci_get_image_data_based_post_id( get_the_ID(), $simage_size, 'h', false );
		}
		if ( penci_get_post_format( 'link' ) || penci_get_post_format( 'quote' ) ) : ?>
            <div class="standard-post-special post-image<?php if ( penci_get_post_format( 'quote' ) ): ?> penci-special-format-quote<?php endif; ?><?php if ( ! has_post_thumbnail() ) : echo ' no-thumbnail'; endif; ?>">
				<?php if ( has_post_thumbnail() ) : ?>
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
                    <div class="format-post-box<?php if ( penci_get_post_format( 'quote' ) ) {
						echo ' penci-format-quote';
					} else {
						echo ' penci-format-link';
					} ?>">
                        <span class="post-format-icon"><?php penci_fawesome_icon( 'fas fa-' . ( penci_get_post_format( 'quote' ) ? 'quote-left' : 'link' ) ); ?></span>
                        <p class="dt-special">
							<?php
							if ( penci_get_post_format( 'quote' ) ) {
								$dt_content = get_post_meta( get_the_id(), '_format_quote_source_name', true );
								if ( ! empty( $dt_content ) ): echo sanitize_text_field( $dt_content ); endif;
							} else {
								$dt_content = get_post_meta( get_the_id(), '_format_link_url', true );
								if ( ! empty( $dt_content ) ):
									echo '<a href="' . esc_url( $dt_content ) . '" target="_blank">' . sanitize_text_field( $dt_content ) . '</a>';
								endif;
							}
							?>
                        </p>
						<?php
						if ( penci_get_post_format( 'quote' ) ):
							$quote_author = get_post_meta( get_the_id(), '_format_quote_source_url', true );
							if ( ! empty( $quote_author ) ):
								echo '<div class="author-quote"><span>' . sanitize_text_field( $quote_author ) . '</span></div>';
							endif;
						endif; ?>
                    </div>
                </div>
            </div>

		<?php elseif ( penci_get_post_format( 'gallery' ) ) : ?>

			<?php $images = get_post_meta( get_the_id(), '_format_gallery_images', true ); ?>

			<?php if ( $images ) :
				$autoplay = ! $settings['penci_disable_autoplay_single_slider'] ? 'true' : 'false';
				?>
                <div class="post-image">
                    <div class="swiper penci-owl-carousel penci-owl-carousel-slider penci-nav-visible"
                         data-auto="<?php echo $autoplay; ?>" data-lazy="true">
                        <div class="swiper-wrapper">
							<?php foreach ( $images as $image ) : ?>

                            <div class="swiper-slide swiper-mark-item">

								<?php $the_image = wp_get_attachment_image_src( $image, $simage_size ); ?>
								<?php $the_caption = get_post_field( 'post_excerpt', $image );
								$image_alt         = penci_get_image_alt( $image, get_the_ID() );
								$image_title_html  = penci_get_image_title( $image );
								?>
                                <figure class="item-link-relative penci-swiper-mask">
									<?php if ( get_theme_mod( 'penci_speed_disable_first_screen' ) || ! get_theme_mod( 'penci_disable_lazyload_fsingle' ) ) { ?>
										<?php echo penci_get_ratio_img_format_gallery( $the_image ); ?>
                                        <img class="penci-lazy"
                                             src="<?php echo penci_holder_image_base( $the_image[1], $the_image[2] ); ?>"
                                             width="<?php echo $the_image[1]; ?>" height="<?php echo $the_image[2]; ?>"
                                             data-src="<?php echo esc_url( $the_image[0] ); ?>"
                                             alt="<?php echo $image_alt; ?>"<?php echo $image_title_html; ?> />
									<?php } else { ?>
                                        <img src="<?php echo esc_url( $the_image[0] ); ?>"
                                             width="<?php echo $the_image[1]; ?>" height="<?php echo $the_image[2]; ?>"
                                             alt="<?php echo $image_alt; ?>"<?php echo $image_title_html; ?> />
									<?php } ?>
									<?php if ( $settings['penci_post_gallery_caption'] && $the_caption ): ?>
                                        <p class="penci-single-gallery-captions penci-single-gaformat-caption"><?php echo $the_caption; ?></p>
									<?php endif; ?>
                                </figure>
                            </div>

							<?php endforeach; ?>
                        </div>
                    </div>
                </div>
			<?php endif; ?>

		<?php elseif ( penci_get_post_format( 'video' ) ) :
			$penci_video = get_post_meta( get_the_id(), '_format_video_embed', true );
			$type_video = self::get_type_video( $penci_video );
			?>

            <div class="post-image penci-video-format-<?php echo esc_attr( $type_video ); ?>">
				<?php if ( wp_oembed_get( $penci_video ) ) : ?>
					<?php echo wp_oembed_get( $penci_video ); ?>
				<?php else : ?>
					<?php echo $penci_video; ?>
				<?php endif; ?>
            </div>

		<?php elseif ( penci_get_post_format( 'audio' ) ) : ?>

            <div class="standard-post-image post-image audio<?php if ( ! has_post_thumbnail() || get_theme_mod( 'penci_post_thumb' ) ) : echo ' no-thumbnail'; endif; ?>">
				<?php if ( has_post_thumbnail() ) : ?>
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
					<?php $penci_audio = get_post_meta( get_the_id(), '_format_audio_embed', true );
					$penci_audio_str   = substr( $penci_audio, - 4 ); ?>
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

                <div class="post-image">
					<?php
					if ( ! get_theme_mod( 'penci_disable_lightbox_single' ) ) {
						$thumb_url = wp_get_attachment_url( get_post_thumbnail_id( get_the_id() ) );
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
					if ( get_the_post_thumbnail_caption() && $settings['penci_post_gallery_caption'] ) {
						echo '<div class="penci-featured-caption">' . get_the_post_thumbnail_caption() . '</div>';
					}
					?>
                </div>

			<?php endif; ?>

		<?php endif;
	}

	public static function get_type_video( $video_embed ) {
		if ( ! $video_embed ) {
			return '';
		}

		$video_embed = strtolower( $video_embed );
		if ( false !== strpos( $video_embed, 'vimeo.com' ) ) {
			return 'vimeo';
		}

		if ( false !== strpos( $video_embed, 'youtu.be' ) ||
		     false !== strpos( $video_embed, 'youtube-nocookie.com' ) ||
		     false !== strpos( $video_embed, 'youtube.com' ) ) {
			return 'youtube';
		}

		preg_match( '#^(http|https)://.+\.(mp4|m4v|webm|ogv|wmv|flv)$#i', $video_embed, $matches );
		if ( ! empty( $matches[0] ) ) {
			return 'selfhosted';
		}

		return '';
	}
}
