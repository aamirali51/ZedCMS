<?php
$prev_post      = get_previous_post();
$next_post      = get_next_post();
$thumbnail_size = 'thumbnail';
wp_enqueue_script( 'penci-nav-scroll' );
$title_length = get_theme_mod( 'penci_post_sticky_rlposts_tlength', 10 );
?>
<div class="penci-post-sticky-nav">
    <div class="container">
        <div class="penci-post-sticky-nav-wrap">
			<?php if ( ! empty( $prev_post ) ) : ?>
                <div class="prev-post">
                    <a class="prev-post-wrap snav-pwrap" href="<?php echo esc_url( get_the_permalink( $prev_post->ID ) ); ?>">
                        <?php if ( has_post_thumbnail( $prev_post->ID ) ): ?>

                            <span <?php echo penci_layout_bg( penci_image_srcset( $prev_post->ID, $thumbnail_size ), ! get_theme_mod( 'penci_disable_lazyload_single' ) ); ?>
                                    class="<?php echo penci_layout_bg_class(); ?> penci-post-nav-thumb penci-holder-load penci-image-holder"
                                    href="<?php echo esc_url( get_the_permalink( $prev_post->ID ) ); ?>">
                                <?php echo penci_layout_img( penci_image_srcset( $prev_post->ID, $thumbnail_size ), '', ! get_theme_mod( 'penci_disable_lazyload_single' ) ); ?>
                            </span>

                        <?php else: ?>

                            <span <?php echo penci_layout_bg( penci_get_default_thumbnail_url(), ! get_theme_mod( 'penci_disable_lazyload_single' ) ); ?>
                                    class="<?php echo penci_layout_bg_class(); ?> penci-post-nav-thumb penci-holder-load penci-image-holder"
                                    href="<?php echo esc_url( get_the_permalink( $prev_post->ID ) ); ?>">
                                <?php echo penci_layout_img( penci_get_default_thumbnail_url(), '', ! get_theme_mod( 'penci_disable_lazyload_single' ) ); ?>
                            </span>

                        <?php endif; ?>
                        <div class="prev-post-inner pnavi-inner">
                            
                            
                            <div class="pagi-text">
                                <h5 class="prev-title">
                                    <?php 
                                        $title = get_the_title( $prev_post->ID );
                                        if ( $title_length ) {
                                            echo wp_trim_words( $title, $title_length, '...' );
                                        } else {
                                            echo $title;
                                        }
                                    ?>
                                </h5>
                            </div>
                            
                        </div>
                    </a>
                </div>
            <?php else: ?>
                <div class="prev-post"></div>
			<?php endif; ?>
            <div class="current-post">
                <h4>
                <?php 
                    $title = get_the_title( get_the_ID() );
                    if ( $title_length ) {
                        echo wp_trim_words( $title, $title_length, '...' );
                    } else {
                        echo $title;
                    }
                ?>
                </h4>
            </div>
			<?php if ( ! empty( $next_post ) ) : ?>
                <div class="next-post">
                    <a class="next-post-wrap snav-pwrap" href="<?php echo esc_url( get_the_permalink( $next_post->ID ) ); ?>">
                        <?php if ( has_post_thumbnail( $next_post->ID ) ):
                            ?>

                            <span <?php echo penci_layout_bg( penci_image_srcset( $next_post->ID, $thumbnail_size ), ! get_theme_mod( 'penci_disable_lazyload_single' ) ); ?>
                                    class="<?php echo penci_layout_bg_class(); ?> penci-post-nav-thumb penci-holder-load nav-thumb-next penci-image-holder"
                                    href="<?php echo esc_url( get_the_permalink( $next_post->ID ) ); ?>">
                                <?php echo penci_layout_img( penci_image_srcset( $next_post->ID, $thumbnail_size ), '', ! get_theme_mod( 'penci_disable_lazyload_single' ) ); ?>
                            </span>

                        <?php else: ?>

                            <span <?php echo penci_layout_bg( penci_get_default_thumbnail_url(), ! get_theme_mod( 'penci_disable_lazyload_single' ) ); ?>
                                    class="<?php echo penci_layout_bg_class(); ?> penci-post-nav-thumb penci-holder-load nav-thumb-next penci-image-holder"
                                    href="<?php echo esc_url( get_the_permalink( $next_post->ID ) ); ?>">
                                <?php echo penci_layout_img( penci_get_default_thumbnail_url(), '', ! get_theme_mod( 'penci_disable_lazyload_single' ) ); ?>
                            </span>

                        <?php endif; ?>
                        <div class="next-post-inner pnavi-inner">
                            <div class="pagi-text">
                                <h5 class="next-title">
                                    <?php 
                                        $title = get_the_title( $next_post->ID );
                                        if ( $title_length ) {
                                            echo wp_trim_words( $title, $title_length, '...' );
                                        } else {
                                            echo $title;
                                        }
                                    ?>
                                </h5>
                            </div>
                        </div>
                    </a>
                </div>
            <?php else: ?>
                <div class="next-post"></div>
			<?php endif; ?>
        </div>
    </div>
</div>