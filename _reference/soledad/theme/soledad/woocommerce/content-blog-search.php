<?php
/* Get Archive layout */
if ( ! isset( $args['search_query'] ) && ! $args['search_query'] ) {
	return false;
}

$layout_this = get_theme_mod( 'penci_archive_layout' );
if ( ! isset( $layout_this ) || empty( $layout_this ) ): $layout_this = 'standard'; endif;
$class_layout = '';
if ( $layout_this == 'classic' ): $class_layout = ' classic-layout'; endif;

$search_args = array(
	's'              => $args['search_query'],
	'posts_per_page' => get_theme_mod( 'penci_woocommerce_search_included_total', 5 ),
	'post_type'      => 'post',
);

$product_search_post_query = new WP_Query( $search_args );
if ( $product_search_post_query->have_posts() ):?>
    <div class="penci-border-arrow penci-homepage-title penci-magazine-title style-7 pcalign-left pciconp-right pcicon-right">
        <h3 class="inner-arrow">
			<span>
				<a href="<?php echo esc_url( get_search_link( $args['search_query'] ) ); ?>">
					<?php echo penci_woo_translate_text( 'penci_woo_trans_blog_search_result' ); ?>
				</a>
			</span>
        </h3>
    </div>
	<?php
	$class_grid_arr = array(
		'mixed',
		'mixed-2',
		'mixed-3',
		'mixed-4',
		'small-list',
		'overlay-grid',
		'overlay-list',
		'photography',
		'grid',
		'grid-2',
		'list',
		'boxed-1',
		'boxed-2',
		'boxed-3',
		'standard-grid',
		'standard-grid-2',
		'standard-list',
		'standard-boxed-1',
		'classic-grid',
		'classic-grid-2',
		'classic-list',
		'classic-boxed-1',
		'magazine-1',
		'magazine-2'
	);
	if ( in_array( $layout_this, $class_grid_arr ) ) {
		echo '<ul class="penci-wrapper-data penci-grid">';
	} elseif ( in_array( $layout_this, array( 'masonry', 'masonry-2' ) ) ) {
		echo '<div class="penci-wrap-masonry"><div class="penci-wrapper-data masonry penci-masonry">';
	} elseif ( get_theme_mod( 'penci_archive_nav_ajax' ) || get_theme_mod( 'penci_archive_nav_scroll' ) ) {
		echo '<div class="penci-wrapper-data">';
	}

	$infeed_ads  = get_theme_mod( 'penci_infeedads_archi_code' ) ? get_theme_mod( 'penci_infeedads_archi_code' ) : '';
	$infeed_num  = get_theme_mod( 'penci_infeedads_archi_num' ) ? get_theme_mod( 'penci_infeedads_archi_num' ) : 3;
	$infeed_full = get_theme_mod( 'penci_infeedads_archi_layout' ) ? get_theme_mod( 'penci_infeedads_archi_layout' ) : '';
	while ( $product_search_post_query->have_posts() ) : $product_search_post_query->the_post();
		include( locate_template( 'content-' . $layout_this . '.php' ) );
	endwhile;

	if ( in_array( $layout_this, $class_grid_arr ) ) {
		echo '</ul>';
	} elseif ( in_array( $layout_this, array( 'masonry', 'masonry-2' ) ) ) {
		echo '</div></div>';
	} elseif ( get_theme_mod( 'penci_archive_nav_ajax' ) || get_theme_mod( 'penci_archive_nav_scroll' ) ) {
		echo '</div>';
	}
	?>
    <div class="pcbg-paging penci-pagination pcbg-paging-align-center penci-ajax-more">
        <a class="penci-ajax-more-button" href="<?php echo esc_url( get_search_link( $args['search_query'] ) ); ?>">
            <span class="ajax-more-text"><?php echo penci_woo_translate_text( 'penci_woo_trans_showmore_result' ); ?></span>
        </a>
    </div>
<?php endif;
wp_reset_postdata(); /* End if of the loop */ ?>

