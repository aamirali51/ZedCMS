<?php
$settings   = vc_map_get_attributes( $this->getShortcode(), $atts );
$css_class  = vc_shortcode_custom_css_class( $settings['css'] );
$thumbsize  = $settings['thumb_size'];
$block_id   = Penci_Vc_Helper::get_unique_id_block( 'pc_single_postpagination' );
$css_custom = '';
?>
    <div id="<?php echo $block_id; ?>" class="post-pagination <?php echo $css_class; ?>">
		<?php
		$prev_post = get_previous_post();
		$next_post = get_next_post();
		?>
		<?php if ( ! empty( $prev_post ) ) : ?>
            <div class="prev-post prvn-item">
				<?php if ( has_post_thumbnail( $prev_post->ID ) && 'yes' == $settings['penci_post_nav_thumbnail'] ): ?>
					<?php if ( ! get_theme_mod( 'penci_disable_lazyload_single' ) ) { ?>
                        <a class="penci-post-nav-thumb penci-holder-load penci-lazy"
                           href="<?php echo esc_url( get_the_permalink( $prev_post->ID ) ); ?>"
                           data-bgset="<?php echo penci_image_srcset( $prev_post->ID, $thumbsize ); ?>">
                        </a>
					<?php } else { ?>
                        <a class="penci-post-nav-thumb"
                           href="<?php echo esc_url( get_the_permalink( $prev_post->ID ) ); ?>"
                           style="background-image: url('<?php echo penci_get_featured_image_size( $prev_post->ID, $thumbsize ); ?>');">
                        </a>
					<?php } ?>
				<?php endif; ?>
                <div class="prev-post-inner">
                    <div class="prev-post-title">
                        <span><?php echo penci_get_setting( 'penci_trans_previous_post' ); ?></span>
                    </div>
                    <a href="<?php echo esc_url( get_the_permalink( $prev_post->ID ) ); ?>">
                        <div class="pagi-text">
                            <h5 class="prev-title"><?php echo get_the_title( $prev_post->ID ); ?></h5>
                        </div>
                    </a>
                </div>
            </div>
		<?php endif; ?>

		<?php if ( ! empty( $next_post ) ) : ?>
            <div class="next-post prvn-item">
				<?php if ( has_post_thumbnail( $next_post->ID ) && 'yes' == $settings['penci_post_nav_thumbnail'] ): ?>
					<?php if ( ! get_theme_mod( 'penci_disable_lazyload_single' ) ) { ?>
                        <a class="penci-post-nav-thumb penci-holder-load penci-lazy nav-thumb-next"
                           href="<?php echo esc_url( get_the_permalink( $next_post->ID ) ); ?>"
                           data-bgset="<?php echo penci_image_srcset( $next_post->ID, $thumbsize ); ?>">
                        </a>
					<?php } else { ?>
                        <a class="penci-post-nav-thumb nav-thumb-next"
                           href="<?php echo esc_url( get_the_permalink( $next_post->ID ) ); ?>"
                           style="background-image: url('<?php echo penci_get_featured_image_size( $next_post->ID, $thumbsize ); ?>');">
                        </a>
					<?php } ?>
				<?php endif; ?>
                <div class="next-post-inner">
                    <div class="prev-post-title next-post-title">
                        <span><?php echo penci_get_setting( 'penci_trans_next_post' ); ?></span>
                    </div>
                    <a href="<?php echo esc_url( get_the_permalink( $next_post->ID ) ); ?>">
                        <div class="pagi-text">
                            <h5 class="next-title"><?php echo get_the_title( $next_post->ID ); ?></h5>
                        </div>
                    </a>
                </div>
            </div>
		<?php endif; ?>
    </div>
<?php
$css = [
	'post_desc_color'   => [ '{{WRAPPER}} .post-pagination .prev-post-title span' => 'color: {{VALUE}}' ],
	'post_title_color'  => [ '{{WRAPPER}} .post-pagination a' => 'color: {{VALUE}}' ],
	'post_title_hcolor' => [ '{{WRAPPER}} .post-pagination a:hover' => 'color: {{VALUE}}' ],
];
penci_wpbakery_el_extract_css( $css, $settings, '#' . $block_id );
