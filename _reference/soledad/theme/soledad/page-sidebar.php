<?php
/*
	Template Name: Page with Sidebar
*/

/**
 * Template using to display sidebar on page
 *
 * @since 1.0
 */

get_header();
$sidebar_position = 'right-sidebar';
if ( get_theme_mod( 'penci_page_default_template_layout' ) == 'two-sidebar' ) {
	$sidebar_position = 'two-sidebar';
} elseif ( get_theme_mod( 'penci_page_default_template_layout' ) == 'left-sidebar' ) {
	$sidebar_position = 'left-sidebar';
}

$page_sidebar = get_post_meta( get_the_ID(), 'penci_sidebar_page_pos', true );
if ( $page_sidebar ) {
	$sidebar_position = $page_sidebar;
}
$breadcrumb     = get_post_meta( get_the_ID(), 'penci_page_breadcrumb', true );
$featured_boxes = get_post_meta( get_the_ID(), 'penci_page_display_featured_boxes', true );
$page_meta      = get_post_meta( get_the_ID(), 'penci_page_slider', true );
$rev_shortcode  = get_post_meta( get_the_ID(), 'penci_page_rev_shortcode', true );

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
		$yoast_breadcrumb = '';
		if ( function_exists( 'yoast_breadcrumb' ) ) {
			$yoast_breadcrumb = yoast_breadcrumb( '<div class="container container-single-page penci-breadcrumb">', '</div>', false );
		}

		if ( $yoast_breadcrumb ) {
			echo $yoast_breadcrumb;
		} else { ?>
            <div class="container container-single-page penci-breadcrumb">
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

<div class="container penci_sidebar <?php echo esc_attr( $sidebar_position ); ?>">

    <div id="main"<?php if ( get_theme_mod( 'penci_sidebar_sticky' ) ): ?> class="penci-main-sticky-sidebar"<?php endif; ?>>
        <div class="theiaStickySidebar">

			<?php if ( ! $show_page_title ): ?>
				<?php if ( ! get_theme_mod( 'penci_disable_breadcrumb' ) && ( 'no' != $breadcrumb ) && get_theme_mod( 'penci_move_breadcrumbs' ) ): ?>
					<?php
					$yoast_breadcrumb = '';
					if ( function_exists( 'yoast_breadcrumb' ) ) {
						$yoast_breadcrumb = yoast_breadcrumb( '<div class="container container-single-page penci-breadcrumb penci-crumb-inside">', '</div>', false );
					}

					if ( $yoast_breadcrumb ) {
						echo $yoast_breadcrumb;
					} else { ?>
                        <div class="container container-single-page penci-breadcrumb penci-crumb-inside">
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

	<?php get_sidebar(); ?>
	<?php if ( $sidebar_position == 'two-sidebar' ) : get_sidebar( 'left' ); endif; ?>
</div>

<?php get_footer(); ?>
