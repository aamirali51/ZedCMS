<?php
/**
 * This template will display category page
 *
 * @package Wordpress
 * @since 1.0
 */

get_header();

/* Sidebar position */
$sidebar_position = penci_get_sidebar_position_archive();
$ccolor           = '';
$category_oj      = get_queried_object();
$fea_cat_id       = $category_oj->term_id;
$cat_meta         = get_option( "category_$fea_cat_id" );
$ccount 		  = (int) $category_oj->count;
$ccount_html 	  = $ccount && get_theme_mod( 'penci_archive_show_cat_count' ) ? '<span class="pccat-count">' . $ccount . '</span>' : '';
$magazine_layout  = isset( $cat_meta['cat_magazine'] ) && $cat_meta['cat_magazine'] ? $cat_meta['cat_magazine'] : '';
$categories_child = get_categories(
    array( 
		'parent' => $fea_cat_id
	)
);

$is_category_magazine = $magazine_layout && $categories_child;

if ( isset( $cat_meta['penci_archivepage_color'] ) && $cat_meta['penci_archivepage_color'] ) {
	$ccolor = 'color:' . $cat_meta['penci_archivepage_color'] . ';';
}
$sidebar_opts = isset( $cat_meta['cat_sidebar_display'] ) ? $cat_meta['cat_sidebar_display'] : '';

if ( $sidebar_opts == 'left' ) {
	$sidebar_position = 'left-sidebar';
} elseif ( $sidebar_opts == 'right' ) {
	$sidebar_position = 'right-sidebar';
} elseif ( $sidebar_opts == 'two' ) {
	$sidebar_position = 'two-sidebar';
}

$show_sidebar = false;
if ( ( penci_get_setting( 'penci_sidebar_archive' ) && $sidebar_opts != 'no' ) || $sidebar_opts == 'left' || $sidebar_opts == 'right' || $sidebar_opts == 'two' ) {
	$show_sidebar = true;
} else {
	/* Use $template to detect sidebar for category - use this value for load correct sidebar in content templates */
	$template = 'no-sidebar';
}

$archive_desc_align = get_theme_mod( 'penci_archive_descalign', '' );
if ( $archive_desc_align ) {
	$archive_desc_align = ' pcdesc-' . $archive_desc_align;
}
$title_acls = 'pcatitle-' . get_theme_mod( 'penci_archive_titlealign', 'default' );
/* Categories layout */
$layout_this = get_theme_mod( 'penci_archive_layout' );
$grid_col    = get_theme_mod( 'penci_archive_grid_col' );
$grid_mcol   = get_theme_mod( 'penci_archive_grid_mcol' );
if ( ! isset( $layout_this ) || empty( $layout_this ) ): $layout_this = 'standard'; endif;

$category_oj = get_queried_object();
$fea_cat_id  = $category_oj->term_id;
$cat_meta    = get_option( "category_$fea_cat_id" );
$cat_layout  = isset( $cat_meta['cat_layout'] ) ? $cat_meta['cat_layout'] : '';
if ( $cat_layout != '' ):
	$layout_this = $cat_layout;
endif;

$class_layout = '';
if ( $layout_this == 'classic' ): $class_layout = ' classic-layout'; endif;
$two_sidebar_class = '';
if ( 'two-sidebar' == $sidebar_position ): $two_sidebar_class = ' two-sidebar'; endif;
?>

<?php if ( ! get_theme_mod( 'penci_disable_breadcrumb' ) && ! get_theme_mod( 'penci_move_breadcrumbs' ) ): ?>
	<?php
	$yoast_breadcrumb = $rm_breadcrumb = '';
	if ( function_exists( 'yoast_breadcrumb' ) ) {
		$yoast_breadcrumb = yoast_breadcrumb( '<div class="container penci-breadcrumb' . $two_sidebar_class . '">', '</div>', false );
	}

	if ( function_exists( 'rank_math_get_breadcrumbs' ) ) {
		$rm_breadcrumb = rank_math_get_breadcrumbs( [
			'wrap_before' => '<div class="container penci-breadcrumb"><nav aria-label="breadcrumbs" class="rank-math-breadcrumb">',
			'wrap_after'  => '</nav></div>',
		] );
	}

	if ( $rm_breadcrumb ) {
		echo $rm_breadcrumb;
	} elseif ( $yoast_breadcrumb ) {
		echo $yoast_breadcrumb;
	} else { ?>
        <div class="container penci-breadcrumb<?php echo $two_sidebar_class; ?>">
            <span><a class="crumb"
                     href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php echo penci_get_setting( 'penci_trans_home' ); ?></a></span><?php penci_fawesome_icon( 'fas fa-angle-right' ); ?>
			<?php
			$parent_ID = penci_get_category_parent_id( $fea_cat_id );
			if ( $parent_ID ):
				echo penci_get_category_parents( $parent_ID );
			endif;
			?>
            <span><?php single_cat_title( '', true ); ?></span>
        </div>
	<?php } ?>
<?php endif; ?>

<?php if ( penci_featured_exclude_posts() ): ?>

    <div class="archive-box container<?php if ( penci_is_show_archive_filter() ): ?> pc-has-sorter<?php endif; ?>">
        <div class="title-bar <?php echo $title_acls; ?>">
			<?php if ( ! get_theme_mod( 'penci_remove_cat_words' ) ): ?>
                <span><?php echo penci_get_setting( 'penci_trans_category' ); ?></span>
			<?php endif; ?>
            	<h1 style="<?php echo $ccolor; ?>"><?php printf( esc_html__( '%s', 'soledad' ), single_cat_title( '', false ) ); ?></h1>
				<?php echo $ccount_html; ?>
			<?php do_action( 'penci_archive_follow_button' ); ?>
        </div>
		<?php get_template_part( 'template-parts/archive-sorter' ); ?>
    </div>


	<?php if ( category_description() && ! get_theme_mod( 'penci_archive_move_desc' ) && ! get_theme_mod( 'penci_archive_hide_desc' ) ) : // Show an optional category description ?>
        <div class="container <?php echo $two_sidebar_class; ?>">
            <div class="post-entry penci-category-description<?php echo $archive_desc_align; ?>">
                <div class="penci-category-description-inner">
					<?php echo do_shortcode( category_description() ); ?>
                </div>
				<?php if ( ! get_theme_mod( 'penci_archive_disable_desc_collapse', true ) ): ?>
                    <div class="penci-category-description-button">
                        <a href="#" title="<?php echo penci_get_setting( 'penci_trans_read_more' ); ?>"
                           aria-label="<?php echo penci_get_setting( 'penci_trans_read_more' ); ?>"><?php echo penci_get_setting( 'penci_trans_read_more' ); ?></a>
                    </div>
				<?php endif; ?>
            </div>
        </div>
	<?php endif; ?>

<?php endif; ?>

<?php do_action( 'penci_featured_archive_posts' ); ?>

<div class="container<?php echo esc_attr( $class_layout );
if ( $show_sidebar ) : ?> penci_sidebar <?php echo esc_attr( $sidebar_position ); ?><?php endif; ?>">
    <div id="main"
         class="penci-layout-<?php echo esc_attr( $layout_this ); ?><?php if ( get_theme_mod( 'penci_sidebar_sticky' ) ): ?> penci-main-sticky-sidebar<?php endif; ?>">
        <div class="theiaStickySidebar">

			<?php do_action( 'penci_archive_before_posts' ); ?>

			<?php if ( ! get_theme_mod( 'penci_disable_breadcrumb' ) && get_theme_mod( 'penci_move_breadcrumbs' ) ): ?>
				<?php
				$yoast_breadcrumb = $rm_breadcrumb = '';
				if ( function_exists( 'yoast_breadcrumb' ) ) {
					$yoast_breadcrumb = yoast_breadcrumb( '<div class="container penci-breadcrumb penci-crumb-inside' . $two_sidebar_class . '">', '</div>', false );
				}

				if ( function_exists( 'rank_math_get_breadcrumbs' ) ) {
					$rm_breadcrumb = rank_math_get_breadcrumbs( [
						'wrap_before' => '<div class="container penci-breadcrumb penci-crumb-inside' . $two_sidebar_class . '"><nav aria-label="breadcrumbs" class="rank-math-breadcrumb">',
						'wrap_after'  => '</nav></div>',
					] );
				}

				if ( $rm_breadcrumb ) {
					echo $rm_breadcrumb;
				} elseif ( $yoast_breadcrumb ) {
					echo $yoast_breadcrumb;
				} else { ?>
                    <div class="container penci-breadcrumb penci-crumb-inside<?php echo $two_sidebar_class; ?>">
                            <span><a class="crumb"
                                     href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php echo penci_get_setting( 'penci_trans_home' ); ?></a></span><?php penci_fawesome_icon( 'fas fa-angle-right' ); ?>
						<?php
						$parent_ID = penci_get_category_parent_id( $fea_cat_id );
						if ( $parent_ID ):
							echo penci_get_category_parents( $parent_ID );
						endif;
						?>
                        <span><?php single_cat_title( '', true ); ?></span>
                    </div>
				<?php } ?>
			<?php endif; ?>

			<?php if ( ! penci_featured_exclude_posts() ) {
				?>

                <div class="archive-box<?php if ( penci_is_show_archive_filter() ): ?> pc-has-sorter container<?php endif; ?>">
                    <div class="title-bar <?php echo $title_acls; ?>">
						<?php if ( ! get_theme_mod( 'penci_remove_cat_words' ) ): ?>
                            <span><?php echo penci_get_setting( 'penci_trans_category' ); ?></span> <?php endif; ?>
                        <h1 style="<?php echo $ccolor; ?>"><?php printf( esc_html__( '%s', 'soledad' ), single_cat_title( '', false ) ); ?></h1>
						<?php echo $ccount_html; ?>
						<?php do_action( 'penci_archive_follow_button' ); ?>
                    </div>
					<?php if ( ! $is_category_magazine ):
						get_template_part( 'template-parts/archive-sorter' );
					endif; ?>
                </div>

				<?php if ( category_description() && ! get_theme_mod( 'penci_archive_move_desc' ) && ! get_theme_mod( 'penci_archive_hide_desc' ) ) : // Show an optional category description ?>
                    <div class="post-entry penci-category-description<?php echo $archive_desc_align; ?>">
                        <div class="penci-category-description-inner">
							<?php echo do_shortcode( category_description() ); ?>
                        </div>
						<?php if ( ! get_theme_mod( 'penci_archive_disable_desc_collapse', true ) ): ?>
                            <div class="penci-category-description-button">
                                <a href="#" title="<?php echo penci_get_setting( 'penci_trans_read_more' ); ?>"
                                   aria-label="<?php echo penci_get_setting( 'penci_trans_read_more' ); ?>"><?php echo penci_get_setting( 'penci_trans_read_more' ); ?></a>
                            </div>
						<?php endif; ?>
                    </div>
				<?php endif; ?>

			<?php } else {

				$format_title = penci_get_setting( 'penci_arf_title' );
				if ( $format_title && ! penci_featured_title_check() ) {
					$heading_widget_title = get_theme_mod( 'penci_sidebar_heading_style' ) ? get_theme_mod( 'penci_sidebar_heading_style' ) : 'style-1';
					$heading_widget_align = get_theme_mod( 'penci_sidebar_heading_align' ) ? get_theme_mod( 'penci_sidebar_heading_align' ) : 'pcalign-center';
					$heading_title        = get_theme_mod( 'penci_featured_cat_style' ) ? get_theme_mod( 'penci_featured_cat_style' ) : $heading_widget_title;
					$heading_align        = get_theme_mod( 'penci_featured_cat_align' ) ? get_theme_mod( 'penci_featured_cat_align' ) : $heading_widget_align;
					$sb_icon_pos          = get_theme_mod( 'penci_sidebar_icon_align' ) ? get_theme_mod( 'penci_sidebar_icon_align' ) : 'pciconp-right';
					$sidebar_icon_pos     = get_theme_mod( 'penci_homep_icon_align' ) ? get_theme_mod( 'penci_homep_icon_align' ) : $sb_icon_pos;
					$sb_icon_design       = get_theme_mod( 'penci_sidebar_icon_design' ) ? get_theme_mod( 'penci_sidebar_icon_design' ) : 'pcicon-right';
					$sidebar_icon_design  = get_theme_mod( 'penci_homep_icon_design' ) ? get_theme_mod( 'penci_homep_icon_design' ) : $sb_icon_design;
					$heading_title        = get_theme_mod( 'penci_arf_title_style', $heading_title );
					?>
                    <div class="featured-list-title penci-border-arrow penci-homepage-title penci-magazine-title <?php echo esc_attr( $heading_title . ' ' . $heading_align . ' ' . $sidebar_icon_pos . ' ' . $sidebar_icon_design ); ?>">
                        <h2 class="inner-arrow">
							<span><span>
							<?php
							echo str_replace( '{name}', single_cat_title( '', false ), $format_title );
							?>
							</span></span>
                        </h2>
                    </div>

				<?php }
			}

			do_action( 'penci_sub_cat_list' );
			?>

			<?php echo penci_render_google_adsense( 'penci_archive_ad_above' ); ?>

			<?php 
			
			if ( $is_category_magazine ):
				get_template_part( 'inc/modules/featured', 'categories', [ 'featured_cat_child' => $categories_child ] );

				$heading_widget_title = get_theme_mod( 'penci_sidebar_heading_style' ) ? get_theme_mod( 'penci_sidebar_heading_style' ) : 'style-1';
				$heading_widget_align = get_theme_mod( 'penci_sidebar_heading_align' ) ? get_theme_mod( 'penci_sidebar_heading_align' ) : 'pcalign-center';
				$heading_title        = get_theme_mod( 'penci_featured_cat_style' ) ? get_theme_mod( 'penci_featured_cat_style' ) : $heading_widget_title;
				$heading_align        = get_theme_mod( 'penci_heading_latest_align' ) ? get_theme_mod( 'penci_heading_latest_align' ) : $heading_widget_align;
				$sb_icon_pos          = get_theme_mod( 'penci_sidebar_icon_align' ) ? get_theme_mod( 'penci_sidebar_icon_align' ) : 'pciconp-right';
				$sidebar_icon_pos     = get_theme_mod( 'penci_homep_icon_align' ) ? get_theme_mod( 'penci_homep_icon_align' ) : $sb_icon_pos;
				$sb_icon_design       = get_theme_mod( 'penci_sidebar_icon_design' ) ? get_theme_mod( 'penci_sidebar_icon_design' ) : 'pcicon-right';
				$sidebar_icon_design  = get_theme_mod( 'penci_homep_icon_design' ) ? get_theme_mod( 'penci_homep_icon_design' ) : $sb_icon_design;
				?>
                    <div class="penci-border-arrow penci-homepage-title penci-home-latest-posts <?php echo esc_attr( $heading_title . ' ' . $heading_align . ' ' . $sidebar_icon_pos . ' ' . $sidebar_icon_design ); ?>">
                        <h3 class="inner-arrow">
							<span><span><?php echo penci_get_setting( 'penci_trans_recent' ); ?></span></span>
						</h3>
                    </div>
				<?php
			
			endif;

			if ( have_posts() ) :
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
					'magazine-2',
					'grid-boxed',
					'grid-boxed-2',
					'grid-boxed-3',
					'grid-boxed-4',
					'grid-boxed-5',
					'list-boxed',
					'list-boxed-2',
					'list-boxed-3',
				);
				$cols_attrs     = '';
				$extra_class    = '';
				if ( in_array( $layout_this, [ 'grid-boxed', 'grid-boxed-2', 'grid-boxed-3', 'grid-boxed-4', 'grid-boxed-5', 'grid', 'masonry' ] ) ) {
					if ( $grid_col ) {
						$cols_attrs  .= ' data-cols="' . $grid_col . '"';
						$extra_class .= 'has-d-cols ';
					}
					if ( $grid_mcol ) {
						$cols_attrs  .= ' data-mcols="' . $grid_mcol . '"';
						$extra_class .= 'has-m-cols ';
					}
				}
				if ( in_array( $layout_this, $class_grid_arr ) ) {
					echo '<ul' . $cols_attrs . ' data-layout="' . esc_attr( $layout_this ) . '" class="' . $extra_class . 'penci-wrapper-data penci-grid">';
				} elseif ( in_array( $layout_this, array( 'masonry', 'masonry-2' ) ) ) {
					echo '<div class="penci-wrap-masonry"><div' . $cols_attrs . ' class="penci-wrapper-data masonry penci-masonry">';
				} elseif ( 'timeline' == $layout_this ) {
					wp_enqueue_style( 'penci-timeline' );
					echo '<div class="penci-tiline penci-tiline-posts penci-tiline-archive penci-tiline-center"><div class="penci-wrapper-data penci-tiline-grid penci-tiline-align-center">';
				} elseif ( get_theme_mod( 'penci_archive_nav_ajax' ) || get_theme_mod( 'penci_archive_nav_scroll' ) ) {
					echo '<div class="penci-wrapper-data">';
				}

				$cimg_size = 'normal';

				if ( in_array( $layout_this, [ 'grid-boxed', 'grid-boxed-2', 'grid-boxed-3', 'grid-boxed-5' ] ) ) {
					$layout_this = 'grid';
				} elseif ( in_array( $layout_this, [ 'list-boxed', 'list-boxed-2' ] ) ) {
					$layout_this = 'list';
					$cimg_size   = 'masonry';
				}

				/* The loop */
				$infeed_ads  = get_theme_mod( 'penci_infeedads_archi_code' ) ? get_theme_mod( 'penci_infeedads_archi_code' ) : '';
				$infeed_num  = get_theme_mod( 'penci_infeedads_archi_num' ) ? get_theme_mod( 'penci_infeedads_archi_num' ) : 3;
				$infeed_full = get_theme_mod( 'penci_infeedads_archi_layout' ) ? get_theme_mod( 'penci_infeedads_archi_layout' ) : '';
				while ( have_posts() ) : the_post();
					include( locate_template( 'content-' . $layout_this . '.php' ) );
				endwhile;

				if ( in_array( $layout_this, $class_grid_arr ) ) {
					echo '</ul>';
				} elseif ( in_array( $layout_this, array( 'masonry', 'masonry-2', 'timeline' ) ) ) {
					echo '</div></div>';
				} elseif ( get_theme_mod( 'penci_archive_nav_ajax' ) || get_theme_mod( 'penci_archive_nav_scroll' ) ) {
					echo '</div>';
				}
				penci_soledad_archive_pag_style( $layout_this );
				?>
			<?php endif;
			wp_reset_postdata(); 
			/* End if of the loop */ ?>

			<?php if ( category_description() && get_theme_mod( 'penci_archive_move_desc' ) && ! get_theme_mod( 'penci_archive_hide_desc' ) ) : // Show an optional category description ?>
                <div class="post-entry penci-category-description penci-acdes-below<?php echo $archive_desc_align; ?>"><?php echo do_shortcode( category_description() ); ?></div>
			<?php endif; ?>

			<?php echo penci_render_google_adsense( 'penci_archive_ad_below' ); ?>

			<?php do_action( 'penci_archive_after_posts' ); ?>

        </div>
    </div>

	<?php
	if ( $show_sidebar ) {
		get_sidebar();

		$category_layout_sidebar = get_theme_mod( 'penci_two_sidebar_archive' );
		if ( $sidebar_opts ) {
			$category_layout_sidebar = $sidebar_opts;
		}

		if ( 'two' == $category_layout_sidebar ) {
			get_sidebar( 'left' );
		}
	}
	?>
</div>
<?php get_footer(); ?>
