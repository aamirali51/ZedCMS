<?php
/**
 * Related post template
 * Render list related posts
 *
 * @since 8.3.9
 */
$author_id = isset( $args['id'] ) && $args['id'] ? $args['id'] : null;

if ( ! $author_id ) {
	return;
}
$data_auto = 'true';
$auto      = get_theme_mod( 'penci_post_related_autoplay' );
if ( $auto == false ): $data_auto = 'false'; endif;

$sidebar_opts = penci_get_single_key( get_the_ID(), 'penci_post_sidebar_display' );

$orig_post = $post;
global $post;
$numbers_related = get_theme_mod( 'penci_numbers_related_post' );
if ( ! isset( $numbers_related ) || $numbers_related < 1 ): $numbers_related = 6; endif;

$orderby_post = 'date';
if ( get_theme_mod( 'penci_related_orderby' ) && get_theme_mod( 'penci_related_orderby' ) != 'date' ):
	$orderby_post = get_theme_mod( 'penci_related_orderby' );
endif;

$related_order_post   = get_theme_mod( 'penci_related_sort_order' );
$related_order_post   = $related_order_post ? $related_order_post : 'DESC';
$related_title_length = get_theme_mod( 'penci_related_posts_title_length' ) ? get_theme_mod( 'penci_related_posts_title_length' ) : 8;
$penci_related_by     = get_theme_mod( 'penci_related_by' );

$query_args = [
	'author'         => $author_id,
	'posts_per_page' => $numbers_related,
	'orderby'        => $orderby_post,
	'order'          => $related_order_post
];

if ( 'most_liked' == $orderby_post ) {
	$query_args['meta_key'] = '_post_like_count';
	$query_args['orderby']  = 'meta_value_num';
}

if ( ! empty( $query_args ) ) {
	$query = new wp_query( $query_args );
	if ( $query->have_posts() ) {
		$align        = 'none';
		$title_align  = 'left';
		$grid_columns = 2;

		$wrapper_class = 'penci-ilrelated-posts';
		$style         = 'grid';
		$wrapper_class .= ' pcilrt-' . $style . ' pcilrt-' . $align . ' pcilrt-col-' . $grid_columns;


		?>
        <div class="<?php echo $wrapper_class; ?>">
            <ul class="pcilrp-content">
				<?php while ( $query->have_posts() ) : $query->the_post(); ?>
                    <li class="pcilrp-item pcilrp-item-<?php echo $style; ?>">
                        <div class="pcilrp-flex">

                            <div class="pcilrp-thumb">

                                <a <?php echo penci_layout_bg( penci_image_srcset( get_the_ID(), penci_featured_images_size( 'small' ) )); ?> class="<?php echo penci_layout_bg_class();?> penci-image-holder"
                                   href="<?php the_permalink(); ?>"
                                   title="<?php echo wp_strip_all_tags( get_the_title() ); ?>">
									<?php echo penci_layout_img( penci_image_srcset( get_the_ID(), penci_featured_images_size( 'small' ) ), get_the_title() ); ?>
                                </a>

                            </div>

                            <div class="pcilrp-body">
                                <div class="pcilrp-title">
                                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                </div>

                            </div>
                        </div>
                    </li>
				<?php endwhile; ?>
            </ul>
        </div>

		<?php


	}
	wp_reset_postdata();
}