<?php
/**
 * The template for displaying author pages
 *
 * @package Wordpress
 * @since 1.0
 */
get_header();

/* Sidebar position */
$sidebar_position = penci_get_sidebar_position_archive();
$title_acls = 'pcatitle-' . get_theme_mod('penci_archive_titlealign', 'default');
/* Archive layout */
$layout_this = get_theme_mod( 'penci_archive_layout' );
$grid_col = get_theme_mod( 'penci_archive_grid_col' );
$grid_mcol = get_theme_mod( 'penci_archive_grid_mcol' );
if ( ! isset( $layout_this ) || empty( $layout_this ) ): $layout_this = 'standard'; endif;
$class_layout = '';
if ( $layout_this == 'classic' ): $class_layout = ' classic-layout'; endif;
?>

<?php if ( ! get_theme_mod( 'penci_disable_breadcrumb' ) && ! get_theme_mod( 'penci_move_breadcrumbs' ) ): ?>
	<?php
	$yoast_breadcrumb = $rm_breadcrumb = '';
	if ( function_exists( 'yoast_breadcrumb' ) ) {
		$yoast_breadcrumb = yoast_breadcrumb( '<div class="container penci-breadcrumb">', '</div>', false );
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
        <div class="container penci-breadcrumb">
            <span><a class="crumb"
                     href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php echo penci_get_setting( 'penci_trans_home' ); ?></a></span><?php penci_fawesome_icon( 'fas fa-angle-right' ); ?>
			<?php
			echo '<span>';
			echo penci_get_setting( 'penci_trans_author' );
			echo '</span>';
			?>
        </div>
	<?php } ?>
<?php endif; ?>

<div class="container<?php echo esc_attr( $class_layout );
if ( penci_get_setting( 'penci_sidebar_archive' ) ) : ?> penci_sidebar <?php echo esc_attr( $sidebar_position ); ?><?php endif; ?>">
    <div id="main"
         class="penci-layout-<?php echo esc_attr( $layout_this ); ?><?php if ( get_theme_mod( 'penci_sidebar_sticky' ) ): ?> penci-main-sticky-sidebar<?php endif; ?>">
        <div class="theiaStickySidebar">

			<?php do_action( 'penci_archive_before_posts' ); ?>

			<?php if ( ! get_theme_mod( 'penci_disable_breadcrumb' ) && get_theme_mod( 'penci_move_breadcrumbs' ) ): ?>
				<?php
				$yoast_breadcrumb = $rm_breadcrumb = '';
				if ( function_exists( 'yoast_breadcrumb' ) ) {
					$yoast_breadcrumb = yoast_breadcrumb( '<div class="container penci-breadcrumb penci-crumb-inside">', '</div>', false );
				}

				if ( function_exists( 'rank_math_get_breadcrumbs' ) ) {
					$rm_breadcrumb = rank_math_get_breadcrumbs( [
						'wrap_before' => '<div class="container penci-breadcrumb penci-crumb-inside"><nav aria-label="breadcrumbs" class="rank-math-breadcrumb">',
						'wrap_after'  => '</nav></div>',
					] );
				}

				if ( $rm_breadcrumb ) {
					echo $rm_breadcrumb;
				} elseif ( $yoast_breadcrumb ) {
					echo $yoast_breadcrumb;
				} else { ?>
                    <div class="container penci-breadcrumb penci-crumb-inside">
                            <span><a class="crumb"
                                     href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php echo penci_get_setting( 'penci_trans_home' ); ?></a></span><?php penci_fawesome_icon( 'fas fa-angle-right' ); ?>
						<?php
						echo '<span>';
						echo penci_get_setting( 'penci_trans_author' );
						echo '</span>';
						?>
                    </div>
				<?php } ?>
			<?php endif; ?>

            <div class="archive-box">
                <div class="title-bar <?php echo $title_acls; ?>">
					<?php
					$author_name = get_the_author();
					$author      = get_user_by( 'slug', get_query_var( 'author_name' ) );
					if ( $author ) {
						$author_id   = $author->ID;
						$author_name = get_the_author_meta( 'display_name', $author_id );
					}
					echo '<span>';
					echo penci_get_setting( 'penci_trans_author' );
					echo ' </span>';
					printf( __( '<h1 class="page-title">%s</h1>', 'soledad' ), $author_name );
					?>
                </div>
            </div>

			<?php get_template_part( 'inc/templates/about_author' ); ?>

			<?php echo penci_render_google_adsense( 'penci_archive_ad_above' ); ?>

			<?php if ( have_posts() ) : ?>
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
					'magazine-2',
					'grid-boxed',
					'grid-boxed-2',
					'grid-boxed-3',
					'grid-boxed-4',
					'grid-boxed-5',
					'list-boxed',
					'list-boxed-2',
					'list-boxed-3'
				);
				
				$cols_attrs = '';
				$extra_class = '';
				if ( in_array($layout_this,['grid-boxed', 'grid-boxed-2', 'grid-boxed-3', 'grid-boxed-4', 'grid-boxed-5', 'grid', 'masonry'])){
					if ( $grid_col ) {
						$cols_attrs .= ' data-cols="'.$grid_col.'"';
						$extra_class .= 'has-d-cols ';
					}
					if ( $grid_mcol ) {
						$cols_attrs .= ' data-mcols="'.$grid_mcol.'"';
						$extra_class .= 'has-m-cols ';
					}
				}
				if ( in_array( $layout_this, $class_grid_arr ) ) {
					echo '<ul'.$cols_attrs.' data-layout="'.esc_attr( $layout_this ).'" class="'.$extra_class.'penci-wrapper-data penci-grid">';
				} elseif ( in_array( $layout_this, array( 'masonry', 'masonry-2' ) ) ) {
					echo '<div class="penci-wrap-masonry"><div'.$cols_attrs.' class="penci-wrapper-data masonry penci-masonry">';
				} elseif ( get_theme_mod( 'penci_archive_nav_ajax' ) || get_theme_mod( 'penci_archive_nav_scroll' ) ) {
					echo '<div class="penci-wrapper-data">';
				}

				$cimg_size = 'normal';

				if ( in_array($layout_this,['grid-boxed','grid-boxed-2','grid-boxed-3','grid-boxed-5'])) {
					$layout_this = 'grid';
				} elseif ( in_array($layout_this,['list-boxed','list-boxed-2'])) {
					$layout_this = 'list';
					$cimg_size = 'masonry';
				}

				$infeed_ads  = get_theme_mod( 'penci_infeedads_archi_code' ) ? get_theme_mod( 'penci_infeedads_archi_code' ) : '';
				$infeed_num  = get_theme_mod( 'penci_infeedads_archi_num' ) ? get_theme_mod( 'penci_infeedads_archi_num' ) : 3;
				$infeed_full = get_theme_mod( 'penci_infeedads_archi_layout' ) ? get_theme_mod( 'penci_infeedads_archi_layout' ) : '';
				while ( have_posts() ) : the_post();
					include( locate_template( 'content-' . $layout_this . '.php' ) );
				endwhile;

				if ( in_array( $layout_this, $class_grid_arr ) ) {
					echo '</ul>';
				} elseif ( in_array( $layout_this, array( 'masonry', 'masonry-2' ) ) ) {
					echo '</div></div>';
				} elseif ( get_theme_mod( 'penci_archive_nav_ajax' ) || get_theme_mod( 'penci_archive_nav_scroll' ) ) {
					echo '</div>';
				}

				penci_soledad_archive_pag_style( $layout_this );
				?>
			<?php endif;
			wp_reset_postdata(); /* End if of the loop */ ?>

			<?php echo penci_render_google_adsense( 'penci_archive_ad_below' ); ?>

			<?php do_action( 'penci_archive_after_posts' ); ?>

        </div>
    </div>

	<?php if ( penci_get_setting( 'penci_sidebar_archive' ) ) : ?>
		<?php get_sidebar(); ?>
		<?php if ( get_theme_mod( 'penci_two_sidebar_archive' ) ) : get_sidebar( 'left' ); endif; ?>
	<?php endif; ?>
</div>
<?php get_footer(); ?>
