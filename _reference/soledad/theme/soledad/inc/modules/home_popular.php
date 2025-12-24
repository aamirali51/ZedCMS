<?php
/**
 * Popular Posts in Homepage
 */

$number_posts         = get_theme_mod( 'penci_home_popular_post_numberposts' ) ? get_theme_mod( 'penci_home_popular_post_numberposts' ) : 10;
$popular_title_length = get_theme_mod( 'penci_home_polular_title_length' ) ? get_theme_mod( 'penci_home_polular_title_length' ) : 8;
$query                = array(
	'posts_per_page' => $number_posts,
	'post_type'      => 'post',
	'meta_key'       => penci_get_postviews_key(),
	'orderby'        => 'meta_value_num',
	'order'          => 'DESC'
);

if ( get_theme_mod( 'penci_home_popular_type' ) == 'week' ) {
	$query = array(
		'posts_per_page' => $number_posts,
		'post_type'      => 'post',
		'meta_key'       => 'penci_post_week_views_count',
		'orderby'        => 'meta_value_num',
		'order'          => 'DESC'
	);
} elseif ( get_theme_mod( 'penci_home_popular_type' ) == 'month' ) {
	$query = array(
		'posts_per_page' => $number_posts,
		'post_type'      => 'post',
		'meta_key'       => 'penci_post_month_views_count',
		'orderby'        => 'meta_value_num',
		'order'          => 'DESC'
	);
} elseif ( get_theme_mod( 'penci_home_popular_type' ) == 'jetpack' ) {
	$query = array(
		'posts_per_page' => $number_posts,
		'post_type'      => 'post',
		'meta_key'       => '_jetpack_post_view',
		'orderby'        => 'meta_value_num',
		'order'          => 'DESC'
	);
}

$popular_cat = get_theme_mod( 'penci_home_popular_cat' );
if ( $popular_cat && '0' != $popular_cat ):
	$query['cat'] = $popular_cat;
endif;

$popular = new WP_Query( $query );

if ( $popular->have_posts() ) {
	$data_loop            = '';
	$data_nav             = get_theme_mod( 'penci_home_popular_shownav' ) ? 'true' : 'false';
	$data_dots            = get_theme_mod( 'penci_home_popular_hidedots' ) ? 'false' : 'true';
	$number_posts_display = $popular->post_count;
	if ( $number_posts_display < 5 ):
		$data_loop = ' data-loop="false"';
	endif;
	$popular_title = get_theme_mod( 'penci_home_popular_title' );
	?>
    <div class="container penci-home-popular-posts">
        <h2 class="home-pupular-posts-title">
		<span>
			<?php echo penci_get_setting( 'penci_home_popular_title' ); ?>
		</span>
        </h2>
        <div class="swiper penci-owl-carousel penci-owl-carousel-slider penci-related-carousel penci-home-popular-post"<?php echo $data_loop; ?>
             data-lazy="true" data-item="4" data-desktop="4" data-tablet="3" data-tabsmall="2" data-auto="false"
             data-speed="300" data-dots="<?php echo $data_dots; ?>" data-nav="<?php echo $data_nav; ?>">
            <div class="swiper-wrapper">
				<?php while ( $popular->have_posts() ) : $popular->the_post(); ?>
                    <div class="item-related swiper-slide">
                        <div class="item-related-inner">
							<?php if ( ( function_exists( 'has_post_thumbnail' ) ) && ( has_post_thumbnail() ) ) : ?>
								<?php do_action( 'penci_bookmark_post', get_the_ID() ); ?>

                                <a <?php echo penci_layout_bg( penci_get_featured_image_size( get_the_ID(), penci_featured_images_size() ) ); ?> class="<?php echo penci_layout_bg_class();?> related-thumb penci-image-holder <?php echo penci_classes_slider_lazy(); ?>"
                                   href="<?php the_permalink() ?>"
                                   title="<?php echo wp_strip_all_tags( get_the_title() ); ?>">
									<?php echo penci_layout_img( penci_get_featured_image_size( get_the_ID(), penci_featured_images_size() ), get_the_title() ); ?>


									<?php if ( has_post_thumbnail() && get_theme_mod( 'penci_enable_home_popular_icons' ) ): ?>
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

                            <h3 class="entry-title"><a title="<?php echo wp_strip_all_tags( get_the_title() ); ?>"
                                                       href="<?php the_permalink(); ?>"><?php echo wp_trim_words( wp_strip_all_tags( get_the_title() ), $popular_title_length, '...' ); ?></a>
                            </h3>
							<?php if ( ! get_theme_mod( 'penci_hide_date_home_popular' ) ) : ?>
                                <span class="date"><?php penci_soledad_time_link(); ?></span>
							<?php endif; ?>
                        </div>
                    </div>
				<?php
				endwhile;
				?>
            </div>
        </div>
    </div>
	<?php
}
wp_reset_postdata();
?>

