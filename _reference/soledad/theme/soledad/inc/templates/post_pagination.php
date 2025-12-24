<?php
/**
 * Post navigation in single post
 * Create next and prev button to next and prev posts
 *
 * @package Wordpress
 * @since 1.0
 */
$style          = get_theme_mod( 'penci_post_pagination_style', 'style-1' );
$thumbnail_size = in_array( $style, [ 'style-1', 'style-2' ] ) ? 'thumbnail' : 'penci-full-thumb';
$show_thumb     = true;
if ( 'style-1' == $style ) {
	$show_thumb = get_theme_mod( 'penci_post_nav_thumbnail' );
}
?>
<div class="post-pagination pcpagp-<?php echo esc_attr( $style ); ?>">
	<?php
	$prev_post = get_previous_post();
	$next_post = get_next_post();
	?>
	<?php if ( ! empty( $prev_post ) ) : ?>
        <div class="prev-post">
			<?php if ( has_post_thumbnail( $prev_post->ID ) && $show_thumb ): ?>

                <a <?php echo penci_layout_bg( penci_image_srcset( $prev_post->ID, $thumbnail_size ), ! get_theme_mod( 'penci_disable_lazyload_single' ) ); ?> class="<?php echo penci_layout_bg_class();?> penci-post-nav-thumb penci-holder-load penci-image-holder"
                   href="<?php echo esc_url( get_the_permalink( $prev_post->ID ) ); ?>">
					<?php echo penci_layout_img( penci_image_srcset( $prev_post->ID, $thumbnail_size ), '', ! get_theme_mod( 'penci_disable_lazyload_single' ) ); ?>
                </a>

			<?php elseif ( 'style-1' != $style ): ?>

                <a <?php echo penci_layout_bg( penci_get_default_thumbnail_url(), ! get_theme_mod( 'penci_disable_lazyload_single' ) ); ?> class="<?php echo penci_layout_bg_class();?> penci-post-nav-thumb penci-holder-load penci-image-holder"
                   href="<?php echo esc_url( get_the_permalink( $prev_post->ID ) ); ?>">
					<?php echo penci_layout_img( penci_get_default_thumbnail_url(), '', ! get_theme_mod( 'penci_disable_lazyload_single' ) ); ?>
                </a>

			<?php endif; ?>
            <div class="prev-post-inner">
				<?php if ( $style == 'style-3' ): ?>
                <div class="prev-post-inner-ct">
					<?php endif; ?>
                    <div class="prev-post-title">
                        <span><?php echo penci_get_setting( 'penci_trans_previous_post' ); ?></span>
                    </div>
                    <a href="<?php echo esc_url( get_the_permalink( $prev_post->ID ) ); ?>">
                        <div class="pagi-text">
                            <h5 class="prev-title"><?php echo get_the_title( $prev_post->ID ); ?></h5>
                        </div>
                    </a>
					<?php if ( $style == 'style-3' ): ?>
                </div>
			<?php endif; ?>
            </div>
        </div>
	<?php endif; ?>

	<?php if ( ! empty( $next_post ) ) : ?>
        <div class="next-post">
			<?php if ( has_post_thumbnail( $next_post->ID ) && $show_thumb ):
				?>

                <a <?php echo penci_layout_bg( penci_image_srcset( $next_post->ID, $thumbnail_size ), ! get_theme_mod( 'penci_disable_lazyload_single' ) ); ?> class="<?php echo penci_layout_bg_class();?> penci-post-nav-thumb penci-holder-load nav-thumb-next penci-image-holder"
                   href="<?php echo esc_url( get_the_permalink( $next_post->ID ) ); ?>">
					<?php echo penci_layout_img( penci_image_srcset( $next_post->ID, $thumbnail_size ), '', ! get_theme_mod( 'penci_disable_lazyload_single' ) ); ?>
                </a>

			<?php elseif ( 'style-1' != $style ): ?>

                <a <?php echo penci_layout_bg( penci_get_default_thumbnail_url(), ! get_theme_mod( 'penci_disable_lazyload_single' ) ); ?> class="<?php echo penci_layout_bg_class();?> penci-post-nav-thumb penci-holder-load nav-thumb-next penci-image-holder"
                   href="<?php echo esc_url( get_the_permalink( $next_post->ID ) ); ?>">
					<?php echo penci_layout_img( penci_get_default_thumbnail_url(), '', ! get_theme_mod( 'penci_disable_lazyload_single' ) ); ?>
                </a>

			<?php endif; ?>
            <div class="next-post-inner">
				<?php if ( $style == 'style-3' ): ?>
                <div class="next-post-inner-ct">
					<?php endif; ?>
                    <div class="prev-post-title next-post-title">
                        <span><?php echo penci_get_setting( 'penci_trans_next_post' ); ?></span>
                    </div>
                    <a href="<?php echo esc_url( get_the_permalink( $next_post->ID ) ); ?>">
                        <div class="pagi-text">
                            <h5 class="next-title"><?php echo get_the_title( $next_post->ID ); ?></h5>
                        </div>
                    </a>
					<?php if ( $style == 'style-3' ): ?>
                </div>
			<?php endif; ?>
            </div>
        </div>
	<?php endif; ?>
</div>
