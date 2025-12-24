<?php
/**
 * This template will display google search result page
 *
 * @package Wordpress
 * @since 1.0
 */

get_header();

/* Sidebar position */
$sidebar_position = penci_get_sidebar_position_archive();

$show_sidebar = false;
if ( ( penci_get_setting( 'penci_sidebar_archive' ) ) ) {
	$show_sidebar = true;
} else {
	/* Use $template to detect sidebar for category - use this value for load correct sidebar in content templates */
	$template = 'no-sidebar';
}

$title_acls = 'pcatitle-' . get_theme_mod('penci_archive_titlealign', 'default');

$archive_desc_align = get_theme_mod( 'penci_archive_descalign', '' );
if ( $archive_desc_align ) {
	$archive_desc_align = ' pcdesc-' . $archive_desc_align;
}

/* Categories layout */
$layout_this = get_theme_mod( 'penci_archive_layout' );
if ( ! isset( $layout_this ) || empty( $layout_this ) ): $layout_this = 'standard'; endif;

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
            <span><?php echo penci_get_setting( 'penci_trans_search' ); ?></span>
        </div>
	<?php } ?>
<?php endif; ?>

<div class="container<?php echo esc_attr( $class_layout );
if ( $show_sidebar ) : ?> penci_sidebar <?php echo esc_attr( $sidebar_position ); ?><?php endif; ?>">
    <div id="main"
         class="penci-layout-<?php echo esc_attr( $layout_this ); ?><?php if ( get_theme_mod( 'penci_sidebar_sticky' ) ): ?> penci-main-sticky-sidebar<?php endif; ?>">
        <div class="theiaStickySidebar">

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
                        <span><?php echo penci_get_setting( 'penci_trans_search' ); ?></span>
                    </div>
				<?php } ?>
			<?php endif; ?>

            <div class="archive-box">
                <div class="title-bar <?php echo $title_acls;?>">
                    <span><?php echo penci_get_setting( 'penci_trans_search_results_for' ); ?></span>
                    <h1><?php printf( esc_html__( '"%s"', 'soledad' ), get_search_query() ); ?></h1>
                </div>
            </div>

			<?php echo penci_render_google_adsense( 'penci_archive_ad_above' ); ?>

			<?php
			if ( get_theme_mod( 'penci_gsr_searchform' ) ) {
				$gcse_code = 'search';
			} else {
				$gcse_code = 'searchresults-only';
			}

			$content = '<div class="penci_gsr_wrapper" id="penci_gsr_wrapper_id">';

			$content .= '<div class="gcse-' . $gcse_code . '" data-linktarget="' . get_theme_mod( 'penci_gsr_target', '_self' ) . '"></div>';

			$content .= '</div>';

			echo $content;
			?>

			<?php echo penci_render_google_adsense( 'penci_archive_ad_below' ); ?>

        </div>
    </div>

	<?php
	if ( $show_sidebar ) {
		get_sidebar();

		$category_layout_sidebar = get_theme_mod( 'penci_two_sidebar_archive' );

		if ( 'two' == $category_layout_sidebar ) {
			get_sidebar( 'left' );
		}
	}
	?>
</div>
<?php get_footer(); ?>
