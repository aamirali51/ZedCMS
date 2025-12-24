<?php
/**
 * Related post template
 * Render list related posts
 *
 * @since 1.0
 */
$data_auto = 'true';
$auto      = get_theme_mod( 'penci_post_related_autoplay' );
if ( $auto == false ): $data_auto = 'false'; endif;

$sidebar_opts = penci_get_single_key( get_the_ID(), 'penci_post_sidebar_display' );

$orig_post = $post;
global $post;
$numbers_related = get_theme_mod( 'penci_numbers_related_post' );
if ( ! isset( $numbers_related ) || $numbers_related < 1 ): $numbers_related = 10; endif;

$orderby_post = 'date';
if ( get_theme_mod( 'penci_related_orderby' ) && get_theme_mod( 'penci_related_orderby' ) != 'date' ):
	$orderby_post = get_theme_mod( 'penci_related_orderby' );
endif;
$related_style        = get_theme_mod( 'penci_related_style', 'style-1' );
$related_order_post   = get_theme_mod( 'penci_related_sort_order' );
$related_order_post   = $related_order_post ? $related_order_post : 'DESC';
$related_title_length = get_theme_mod( 'penci_related_posts_title_length' ) ? get_theme_mod( 'penci_related_posts_title_length' ) : 8;
$penci_related_by     = get_theme_mod( 'penci_related_by' );

$args = penci_get_query_related_posts( get_the_ID(), $penci_related_by, $orderby_post, $related_order_post, $numbers_related );

if ( ! empty( $args ) ) {

$my_query = new wp_query( $args );
if ( $my_query->have_posts() ) {
$data_loop            = '';
$number_posts_display = $my_query->post_count;
if ( $number_posts_display < 4 ):
	$data_loop = ' data-loop="false"';
endif;
?>
<div class="pcrlt-<?php echo $related_style; ?> post-related<?php if ( get_theme_mod( 'penci_post_related_grid' ) ): echo ' penci-posts-related-grid'; endif; ?>">
	<?php if ( penci_get_setting( 'penci_post_related_text' ) ): ?>
        <div class="post-title-box"><h4
                    class="post-box-title"><?php echo penci_get_setting( 'penci_post_related_text' ); ?></h4></div>
	<?php endif; ?>
	<?php if ( ! get_theme_mod( 'penci_post_related_grid' ) ) {
	$dcol_slides = $related_style == 'style-4' ? 2 : 3;
	$mcol_slides = $related_style == 'style-4' ? 1 : 2;
	$lazy_class  = 'penci-lazy'; ?>
    <div class="swiper penci-owl-carousel penci-owl-carousel-slider penci-related-carousel"
         data-lazy="true"<?php echo $data_loop; ?> data-item="<?php echo $dcol_slides; ?>"
         data-desktop="<?php echo $dcol_slides; ?>" data-tablet="<?php echo $mcol_slides; ?>"
         data-tabsmall="<?php echo $mcol_slides; ?>"
         data-auto="<?php echo $data_auto; ?>"
         data-speed="300"<?php if ( ! get_theme_mod( 'penci_post_related_dots' ) ) {
		echo ' data-dots="true"';
	}
	if ( ! get_theme_mod( 'penci_post_related_arrows', true ) ) {
		echo ' data-nav="false"';
	} ?>>
        <div class="swiper-wrapper">
			<?php } else {
			$lazy_class = 'penci-lazy'; ?>
            <div class="penci-related-carousel penci-related-grid-display">
				<?php } ?>
				<?php while ( $my_query->have_posts() ) : $my_query->the_post(); ?>
                    <div class="item-related swiper-slide">
                        <div class="item-related-inner">


							<?php if ( ( function_exists( 'has_post_thumbnail' ) ) && ( has_post_thumbnail() ) ) : ?>
								<?php do_action( 'penci_bookmark_post', get_the_ID() ); ?>

                                <a <?php echo penci_layout_bg( penci_image_srcset( get_the_ID(), penci_featured_images_size() ), get_theme_mod( 'penci_disable_lazyload_single' ) ); ?> class="<?php echo penci_layout_bg_class();?> related-thumb penci-image-holder"
                                   href="<?php the_permalink(); ?>"
                                   title="<?php echo wp_strip_all_tags( get_the_title() ); ?>">
									<?php echo penci_layout_img( penci_image_srcset( get_the_ID(), penci_featured_images_size() ), get_the_title(), get_theme_mod( 'penci_disable_lazyload_single' ) ); ?>

									<?php if ( has_post_thumbnail() && get_theme_mod( 'penci_post_related_icons' ) ): ?>
										<?php if ( has_post_format( 'video' ) ) : ?>
											<?php penci_fawesome_icon( 'fas fa-play' ); ?>
										<?php endif; ?>
										<?php if ( has_post_format( 'audio' ) ) : ?>
											<?php penci_fawesome_icon( 'fas fa-music' ); ?>
										<?php endif; ?>
										<?php if ( has_post_format( 'link' ) ) : ?>
											<?php penci_fawesome_icon( 'fas fa-link' ); ?>
										<?php endif; ?>
										<?php if ( has_post_format( 'quote' ) ) : ?>
											<?php penci_fawesome_icon( 'fas fa-quote-left' ); ?>
										<?php endif; ?>
										<?php if ( has_post_format( 'gallery' ) ) : ?>
											<?php penci_fawesome_icon( 'far fa-image' ); ?>
										<?php endif; ?>
									<?php endif; ?>
                                </a>
							<?php endif; ?>
                            <div class="related-content">

                                <h3>
                                    <a href="<?php the_permalink(); ?>"><?php do_action( 'penci_before_title' );
										echo wp_trim_words( get_the_title(), $related_title_length, '...' ); ?></a>
                                </h3>
								<?php if ( ! get_theme_mod( 'penci_hide_date_related' ) ): ?>
                                    <span class="date"><?php penci_soledad_time_link(); ?></span>
								<?php endif; ?>
                            </div>
                        </div>
                    </div>
				<?php
				endwhile;
				echo '</div>';
				if ( ! get_theme_mod( 'penci_post_related_grid' ) ) {
					echo '<div class="penci-owl-dots"></div></div>';
				}
				echo '</div>';
				}
				}
				$post = $orig_post;
				wp_reset_postdata();
				?>
