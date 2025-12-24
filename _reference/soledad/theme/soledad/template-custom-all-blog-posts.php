<?php
/**
 * Template Name: Custom Blog Page
 *
 * @package Wordpress
 * @since   2.3
 */

get_header();

/* Sidebar position */
$sidebar_position = penci_get_sidebar_position_archive();

$featured_boxes = get_post_meta( get_the_ID(), 'penci_page_display_featured_boxes', true );
$pagetitle      = get_post_meta( $post->ID, 'penci_page_display_title', true );
$breadcrumb     = get_post_meta( get_the_ID(), 'penci_page_breadcrumb', true );

$two_sidebar_class = '';
if ( 'two-sidebar' == $sidebar_position ): $two_sidebar_class = ' two-sidebar'; endif;

/* Categories layout */
$layout_this = get_theme_mod( 'penci_archive_layout' );
if ( ! isset( $layout_this ) || empty( $layout_this ) ): $layout_this = 'standard'; endif;
$class_layout = '';
if ( $layout_this == 'classic' ): $class_layout = ' classic-layout'; endif;

$page_meta     = get_post_meta( get_the_ID(), 'penci_page_slider', true );
$rev_shortcode = get_post_meta( get_the_ID(), 'penci_page_rev_shortcode', true );
$title_acls = 'pcatitle-' . get_theme_mod('penci_archive_titlealign', 'default');

if ( Penci_Featured_Slider::is_slider_style( $page_meta ) ) {
	if ( $page_meta == 'video' && get_theme_mod( 'penci_featured_video_url' ) ) {
		get_template_part( 'inc/featured_slider/featured_video' );
	} else {
		Penci_Featured_Slider::render_featured_slider( [
			'style'         => $page_meta,
			'rev_shortcode' => $rev_shortcode,
		] );
	}
}

/* Display Featured Boxes */
if ( $featured_boxes == 'yes' && ! get_theme_mod( 'penci_home_hide_boxes' ) && ( get_theme_mod( 'penci_home_box_img1' ) || get_theme_mod( 'penci_home_box_img2' ) || get_theme_mod( 'penci_home_box_img3' ) || get_theme_mod( 'penci_home_box_img4' ) ) ):
	get_template_part( 'inc/modules/home_boxes' );
endif;
?>
<?php $show_page_title = penci_is_pageheader(); ?>
<?php if ( ! $show_page_title ): ?>
	<?php if ( ! get_theme_mod( 'penci_disable_breadcrumb' ) && ( 'no' != $breadcrumb ) && ! get_theme_mod( 'penci_move_breadcrumbs' ) ): ?>
		<?php
		$yoast_breadcrumb = $rm_breadcrumb = '';
		if ( function_exists( 'yoast_breadcrumb' ) ) {
			$yoast_breadcrumb = yoast_breadcrumb( '<div class="container penci-breadcrumb' . $two_sidebar_class . '">', '</div>', false );
		}

		if ( function_exists( 'rank_math_get_breadcrumbs' ) ) {
			$rm_breadcrumb = rank_math_get_breadcrumbs( [
				'wrap_before' => '<div class="container penci-breadcrumb' . $two_sidebar_class . '"><nav aria-label="breadcrumbs" class="rank-math-breadcrumb">',
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
                <span><?php the_title(); ?></span>
            </div>
		<?php } ?>
	<?php endif; ?>
<?php endif; ?>

<div class="container<?php echo esc_attr( $class_layout );
if ( penci_get_setting( 'penci_sidebar_archive' ) ) : ?> penci_sidebar <?php echo esc_attr( $sidebar_position ); ?><?php endif; ?>">
    <div id="main"
         class="penci-layout-<?php echo esc_attr( $layout_this ); ?><?php if ( get_theme_mod( 'penci_sidebar_sticky' ) ): ?> penci-main-sticky-sidebar<?php endif; ?>">
        <div class="theiaStickySidebar">

			<?php if ( ! $show_page_title ): ?>
				<?php if ( ! get_theme_mod( 'penci_disable_breadcrumb' ) && ( 'no' != $breadcrumb ) && get_theme_mod( 'penci_move_breadcrumbs' ) ): ?>
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
                            <span><?php the_title(); ?></span>
                        </div>
					<?php } ?>
				<?php endif; ?>
			<?php endif; ?>

			<?php if ( ! $show_page_title ): ?>
				<?php if ( get_the_title() && ( 'no' != $pagetitle ) ): ?>
                    <div class="archive-box">
                        <div class="title-bar <?php echo $title_acls; ?>">
                            <h1 class="page-title"><?php the_title(); ?></h1>
                        </div>
                    </div>
				<?php endif; ?>
			<?php endif; ?>

			<?php
			$paged = max( get_query_var( 'paged' ), get_query_var( 'page' ), 1 );

			$args = array( 'post_type' => 'post', 'post_status' => 'publish', 'paged' => $paged );

			$query_custom = new WP_Query( $args );

			if ( $query_custom->have_posts() ) :
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
				?>

				<?php /* The loop */
				$infeed_ads  = get_theme_mod( 'penci_infeedads_archi_code' ) ? get_theme_mod( 'penci_infeedads_archi_code' ) : '';
				$infeed_num  = get_theme_mod( 'penci_infeedads_archi_num' ) ? get_theme_mod( 'penci_infeedads_archi_num' ) : 3;
				$infeed_full = get_theme_mod( 'penci_infeedads_archi_layout' ) ? get_theme_mod( 'penci_infeedads_archi_layout' ) : '';

				while ( $query_custom->have_posts() ) : $query_custom->the_post();
					include( locate_template( 'content-' . $layout_this . '.php' ) );
				endwhile;
				?>

				<?php
				if ( in_array( $layout_this, $class_grid_arr ) ) {
					echo '</ul>';
				} elseif ( in_array( $layout_this, array( 'masonry', 'masonry-2' ) ) ) {
					echo '</div></div>';
				} elseif ( get_theme_mod( 'penci_archive_nav_ajax' ) || get_theme_mod( 'penci_archive_nav_scroll' ) ) {
					echo '</div>';
				}
				?>

				<?php
				if ( ! get_theme_mod( 'penci_archive_nav_ajax' ) && ! get_theme_mod( 'penci_archive_nav_scroll' ) ) {
					echo penci_pagination_numbers( $query_custom );
				} else {
					penci_soledad_archive_pag_style( $layout_this, $query_custom );
				}
				?>

			<?php endif;
			wp_reset_postdata(); /* End if of the loop */ ?>
        </div>
    </div>

	<?php if ( penci_get_setting( 'penci_sidebar_archive' ) ) : ?>
		<?php get_sidebar(); ?>
		<?php if ( get_theme_mod( 'penci_two_sidebar_archive' ) ) : get_sidebar( 'left' ); endif; ?>
	<?php endif; ?>
</div>
<?php get_footer(); ?>
