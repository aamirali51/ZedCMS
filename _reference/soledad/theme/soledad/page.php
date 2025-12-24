<?php
/**
 * The template for displaying pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages and that
 * other "pages" on your WordPress site will use a different template.
 *
 * @package Wordpress
 * @since   1.0
 */
get_header();
$breadcrumb      = get_post_meta( get_the_ID(), 'penci_page_breadcrumb', true );
$featured_boxes  = get_post_meta( get_the_ID(), 'penci_page_display_featured_boxes', true );
$page_meta       = get_post_meta( get_the_ID(), 'penci_page_slider', true );
$rev_shortcode   = get_post_meta( get_the_ID(), 'penci_page_rev_shortcode', true );
$sidebar_default = get_theme_mod( 'penci_page_default_template_layout' );

$sidebar_position = get_post_meta( get_the_ID(), 'penci_sidebar_page_pos', true );
if ( $sidebar_position ) {
	$sidebar_default = $sidebar_position;
}

$class_small_width = '';
if ( $sidebar_default == 'small-width' ):
	$class_small_width = ' penci-page-container-smaller';
endif;

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
			$yoast_breadcrumb = yoast_breadcrumb( '<div class="container container-single-page penci-breadcrumb ' . $class_small_width . '">', '</div>', false );
		}
		if ( function_exists( 'rank_math_get_breadcrumbs' ) ) {
			$rm_breadcrumb = rank_math_get_breadcrumbs( [
				'wrap_before' => '<div class="container container-single-page penci-breadcrumb ' . $class_small_width . '"><nav aria-label="breadcrumbs" class="rank-math-breadcrumb">',
				'wrap_after'  => '</nav></div>',
			] );
		}

		if ( $rm_breadcrumb ) {
			echo $rm_breadcrumb;
		} elseif ( $yoast_breadcrumb ) {
			echo $yoast_breadcrumb;
		} else { ?>
            <div class="container container-single-page penci-breadcrumb<?php echo $class_small_width; ?>">
                <span><a class="crumb"
                         href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php echo penci_get_setting( 'penci_trans_home' ); ?></a></span><?php penci_fawesome_icon( 'fas fa-angle-right' ); ?>
				<?php
				$page_parent = get_post_ancestors( get_the_ID() );
				if ( ! empty( $page_parent ) ):
					$page_parent = array_reverse( $page_parent );
					foreach ( $page_parent as $pages ) {
						?>
                        <span><a class="crumb"
                                 href="<?php echo get_permalink( $pages ); ?>"><?php echo get_the_title( $pages ); ?></a>
                        </span><?php penci_fawesome_icon( 'fas fa-angle-right' ); ?>
					<?php }
				endif; ?>
                <span><?php the_title(); ?></span>
            </div>
		<?php } ?>
	<?php endif; ?>
<?php endif; ?>

<div class="container container-single-page container-default-page<?php echo $class_small_width;
if ( in_array( $sidebar_default, array( 'left-sidebar', 'right-sidebar', 'two-sidebar' ) ) ) {
	echo ' penci_sidebar ' . $sidebar_default;
} else {
	echo ' penci_is_nosidebar';
} ?>">
    <div id="main"
         class="penci-main-single-page-default <?php if ( get_theme_mod( 'penci_sidebar_sticky' ) ): ?> penci-main-sticky-sidebar<?php endif; ?>">
        <div class="theiaStickySidebar">

			<?php if ( ! $show_page_title ): ?>
				<?php if ( ! get_theme_mod( 'penci_disable_breadcrumb' ) && ( 'no' != $breadcrumb ) && get_theme_mod( 'penci_move_breadcrumbs' ) ): ?>
					<?php
					$yoast_breadcrumb = $rm_breadcrumb = '';
					if ( function_exists( 'yoast_breadcrumb' ) ) {
						$yoast_breadcrumb = yoast_breadcrumb( '<div class="container container-single-page penci-breadcrumb penci-crumb-inside ' . $class_small_width . '">', '</div>', false );
					}

					if ( function_exists( 'rank_math_get_breadcrumbs' ) ) {
						$rm_breadcrumb = rank_math_get_breadcrumbs( [
							'wrap_before' => '<div class="container container-single-page penci-breadcrumb penci-crumb-inside ' . $class_small_width . '"><nav aria-label="breadcrumbs" class="rank-math-breadcrumb">',
							'wrap_after'  => '</nav></div>',
						] );
					}

					if ( $rm_breadcrumb ) {
						echo $rm_breadcrumb;
					} elseif ( $yoast_breadcrumb ) {
						echo $yoast_breadcrumb;
					} else { ?>
                        <div class="container container-single-page penci-breadcrumb penci-crumb-inside<?php echo $class_small_width; ?>">
                                <span><a class="crumb"
                                         href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php echo penci_get_setting( 'penci_trans_home' ); ?></a></span><?php penci_fawesome_icon( 'fas fa-angle-right' ); ?>
							<?php
							$page_parent = get_post_ancestors( get_the_ID() );
							if ( ! empty( $page_parent ) ):
								$page_parent = array_reverse( $page_parent );
								foreach ( $page_parent as $pages ) {
									?>
                                    <span><a class="crumb"
                                             href="<?php echo get_permalink( $pages ); ?>"><?php echo get_the_title( $pages ); ?></a>
                                    </span><?php penci_fawesome_icon( 'fas fa-angle-right' ); ?>
								<?php }
							endif; ?>
                            <span><?php the_title(); ?></span>
                        </div>
					<?php } ?>
				<?php endif; ?>
			<?php endif; ?>

			<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
				<?php get_template_part( 'content', 'page' ); ?>
			<?php endwhile; endif; ?>
        </div>
    </div>

	<?php if ( in_array( $sidebar_default, array( 'left-sidebar', 'right-sidebar', 'two-sidebar' ) ) ) : ?>
		<?php get_sidebar(); ?>
	<?php endif; ?>
	<?php if ( $sidebar_default == 'two-sidebar' ) : get_sidebar( 'left' ); endif; ?>
</div>
<?php get_footer(); ?>
