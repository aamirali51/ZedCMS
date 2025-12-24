<?php
/**
 * The Header for our theme
 *
 * Displays all of the <head> section and everything up till <div id="main">
 *
 * @package    WordPress
 * @subpackage Soledad Theme
 * @since      1.0
 */
get_header(); ?>

<?php if ( get_theme_mod( 'penci_home_adsense_below_slider' ) ): ?>
    <div class="container penci-adsense-below-slider">
		<?php echo do_shortcode( get_theme_mod( 'penci_home_adsense_below_slider' ) ); ?>
    </div>
<?php endif; ?>

<?php
if ( ! get_theme_mod( 'penci_home_hide_boxes' ) && ( get_theme_mod( 'penci_home_box_img1' ) || get_theme_mod( 'penci_home_box_img2' ) || get_theme_mod( 'penci_home_box_img3' ) || get_theme_mod( 'penci_home_box_img4' ) ) ):
	get_template_part( 'inc/modules/home_boxes' );
endif;

/* Homepage Popular Post */
if ( get_theme_mod( 'penci_enable_home_popular_posts' ) ) {
	get_template_part( 'inc/modules/home_popular' );
}

/* Home layout */
$layout_this      = get_theme_mod( "penci_home_layout" );
$sidebar_position = 'right-sidebar';
if ( get_theme_mod( "penci_two_sidebar_home" ) ) {
	$sidebar_position = 'two-sidebar';
} elseif ( get_theme_mod( "penci_left_sidebar_home" ) ) {
	$sidebar_position = 'left-sidebar';
}

$grid_col = get_theme_mod( 'penci_home_grid_col' );
$grid_mcol = get_theme_mod( 'penci_home_grid_mcol' );
$cols_attrs = '';
$extra_class = '';
if ( in_array($layout_this,['grid-boxed', 'grid-boxed-2','grid-boxed-3', 'grid-boxed-4', 'masonry', 'grid'])){
	if ( $grid_col ) {
		$cols_attrs .= ' data-cols="'.$grid_col.'"';
		$extra_class .= 'has-d-cols ';
	}
	if ( $grid_mcol ) {
		$cols_attrs .= ' data-mcols="'.$grid_mcol.'"';
		$extra_class .= 'has-m-cols ';
	}
}

if ( ! isset( $layout_this ) || empty( $layout_this ) ): $layout_this = 'standard'; endif;
$class_layout = '';
if ( $layout_this == 'classic' ): $class_layout = ' classic-layout'; endif;
?>

<div class="container<?php echo esc_attr( $class_layout );
if ( penci_get_setting( 'penci_sidebar_home' ) ) : ?> penci_sidebar <?php echo esc_attr( $sidebar_position ); ?><?php endif; ?>">
    <div id="main"
         class="penci-layout-<?php echo esc_attr( $layout_this ); ?><?php if ( get_theme_mod( 'penci_sidebar_sticky' ) ): ?> penci-main-sticky-sidebar<?php endif; ?>">
        <div class="theiaStickySidebar">

			<?php
			/**
			 * Featured categories for magazine layouts
			 *
			 * @since 1.0
			 */
			if ( ! get_theme_mod( 'penci_move_latest_posts_above' ) && ( ( get_theme_mod( 'penci_home_featured_cat' ) && ( $layout_this == 'magazine-1' || $layout_this == 'magazine-2' ) ) || get_theme_mod( 'penci_enable_featured_cat_all_layouts' ) ) ):
				get_template_part( 'inc/modules/featured-categories' );
			endif;
			?>

			<?php if ( ! get_theme_mod( 'penci_hide_latest_post_homepage' ) ): ?>

				<?php
				$heading_widget_title = get_theme_mod( 'penci_sidebar_heading_style' ) ? get_theme_mod( 'penci_sidebar_heading_style' ) : 'style-1';
				$heading_widget_align = get_theme_mod( 'penci_sidebar_heading_align' ) ? get_theme_mod( 'penci_sidebar_heading_align' ) : 'pcalign-center';
				$heading_title        = get_theme_mod( 'penci_featured_cat_style' ) ? get_theme_mod( 'penci_featured_cat_style' ) : $heading_widget_title;
				$heading_align        = get_theme_mod( 'penci_heading_latest_align' ) ? get_theme_mod( 'penci_heading_latest_align' ) : $heading_widget_align;
				$sb_icon_pos          = get_theme_mod( 'penci_sidebar_icon_align' ) ? get_theme_mod( 'penci_sidebar_icon_align' ) : 'pciconp-right';
				$sidebar_icon_pos     = get_theme_mod( 'penci_homep_icon_align' ) ? get_theme_mod( 'penci_homep_icon_align' ) : $sb_icon_pos;
				$sb_icon_design       = get_theme_mod( 'penci_sidebar_icon_design' ) ? get_theme_mod( 'penci_sidebar_icon_design' ) : 'pcicon-right';
				$sidebar_icon_design  = get_theme_mod( 'penci_homep_icon_design' ) ? get_theme_mod( 'penci_homep_icon_design' ) : $sb_icon_design;
				?>

				<?php if ( get_theme_mod( 'penci_home_title' ) ) : ?>
                    <div class="penci-border-arrow penci-homepage-title penci-home-latest-posts <?php echo esc_attr( $heading_title . ' ' . $heading_align . ' ' . $sidebar_icon_pos . ' ' . $sidebar_icon_design ); ?>">
                        <h3 class="inner-arrow">
							<span><span><?php echo penci_get_setting( 'penci_home_title' ); ?></span></span>
						</h3>
                    </div>
				<?php endif; ?>

                <div class="penci-wrapper-posts-content">

					<?php if ( have_posts() ) : 
						$classic_grid_arr = array(
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
						);
						?>
						<?php if ( in_array( $layout_this, array(
							'standard',
							'classic',
							'overlay',
							'featured'
						) ) ): ?><div class="penci-wrapper-data"><?php endif; ?>
						<?php if ( in_array( $layout_this, $classic_grid_arr ) ) : ?>
                        <ul<?php echo $cols_attrs;?> data-layout="<?php echo esc_attr( $layout_this );?>" class="penci-wrapper-data penci-grid"><?php endif; ?>
						<?php if ( in_array( $layout_this, array( 'masonry', 'masonry-2' ) ) ) : ?>
                        <div class="penci-wrap-masonry">
                        <div<?php echo $cols_attrs;?> class="penci-wrapper-data masonry penci-masonry"><?php endif; ?>
						<?php if ( 'timeline' == $layout_this ) : 
							wp_enqueue_style( 'penci-timeline' );
							?>
						<div class="penci-tiline penci-tiline-posts penci-tiline-archive penci-tiline-center"><div class="penci-wrapper-data penci-tiline-grid penci-tiline-align-center">
						<?php endif; ?>

						<?php
						$cimg_size = 'normal';
						if ( in_array($layout_this,['grid-boxed','grid-boxed-2','grid-boxed-3','grid-boxed-5'])) {
							$layout_this = 'grid';
						} elseif ( in_array($layout_this,['list-boxed','list-boxed-2'])) {
							$layout_this = 'list';
							$cimg_size = 'masonry';
						}
						
						$infeed_ads  = get_theme_mod( 'penci_infeedads_home_code' ) ? get_theme_mod( 'penci_infeedads_home_code' ) : '';
						$infeed_num  = get_theme_mod( 'penci_infeedads_home_num' ) ? get_theme_mod( 'penci_infeedads_home_num' ) : 3;
						$infeed_full = get_theme_mod( 'penci_infeedads_home_layout' ) ? get_theme_mod( 'penci_infeedads_home_layout' ) : '';
						while ( have_posts() ) : the_post();
							include( locate_template( 'content-' . $layout_this . '.php' ) );
						endwhile;
						?>

						<?php if ( in_array( $layout_this, array( 'masonry', 'masonry-2', 'timeline' ) ) ) : ?></div>
                        </div><?php endif; ?>
						<?php if ( in_array( $layout_this, $classic_grid_arr ) ) : ?></ul><?php endif; ?>
						<?php if ( in_array( $layout_this, array(
							'standard',
							'classic',
							'overlay',
							'featured'
						) ) ): ?></div><?php endif; ?>

						<?php if ( get_theme_mod( 'penci_page_navigation_ajax' ) || get_theme_mod( 'penci_page_navigation_scroll' ) ) { ?>
							<?php
							$button_class = 'penci-ajax-more penci-ajax-home penci-ajax-more-click';
							if ( get_theme_mod( 'penci_page_navigation_scroll' ) ):
								$button_class = 'penci-ajax-more penci-ajax-home penci-ajax-more-scroll';
							endif;
							/* Get data template */
							$data_layout = $layout_this;
							if ( in_array( $layout_this, array(
								'standard-grid',
								'classic-grid',
								'overlay-grid'
							) ) ) {
								$data_layout = 'grid';
							} elseif ( in_array( $layout_this, array( 'standard-grid-2', 'classic-grid-2' ) ) ) {
								$data_layout = 'grid-2';
							} elseif ( in_array( $layout_this, array(
								'standard-list',
								'classic-list',
								'overlay-list'
							) ) ) {
								$data_layout = 'list';
							} elseif ( in_array( $layout_this, array( 'standard-boxed-1', 'classic-boxed-1' ) ) ) {
								$data_layout = 'boxed-1';
							} elseif ( in_array( $layout_this, array( 'mixed-3', 'mixed-4' ) ) ) {
								$data_layout = 'small-list';
							}

							$data_template = 'sidebar';

							if ( ! penci_get_setting( 'penci_sidebar_home' ) ):
								$data_template = 'no-sidebar';
							endif;

							/* Get data offset */
							$offset_number = get_option( 'posts_per_page' );
							if ( get_theme_mod( 'penci_home_lastest_posts_numbers' ) && 0 != get_theme_mod( 'penci_home_lastest_posts_numbers' ) ):
								$offset_number = get_theme_mod( 'penci_home_lastest_posts_numbers' );
							endif;
							$num_load = 6;
							if ( get_theme_mod( 'penci_number_load_more' ) && 0 != get_theme_mod( 'penci_number_load_more' ) ):
								$num_load = get_theme_mod( 'penci_number_load_more' );
							endif;
							?>
                            <div class="penci-pagination <?php echo $button_class; ?>">
                                <a class="penci-ajax-more-button" aria-label="More Posts" href="#"
                                   data-mes="<?php echo penci_get_setting( 'penci_trans_no_more_posts' ); ?>"
                                   data-layout="<?php echo esc_attr( $data_layout ); ?>"
                                   data-number="<?php echo absint( $num_load ); ?>"
                                   data-offset="<?php echo absint( $offset_number ); ?>"
                                   data-from="customize" data-template="<?php echo $data_template; ?>"
                                   data-order="<?php echo get_query_var( 'pc_archive_sort', 'desc' ); ?>">
                                    <span class="ajax-more-text"><?php echo penci_get_setting( 'penci_trans_load_more_posts' ); ?></span><span
                                            class="ajaxdot"></span><?php penci_fawesome_icon( 'fas fa-sync' ); ?>
                                </a>
                            </div>
						<?php } else { ?>
							<?php penci_soledad_pagination(); ?>
						<?php } ?>
					<?php endif;
					wp_reset_postdata(); /* End if of the loop */ ?>
                </div>
			<?php endif; /* End check if not hide latest on homepage */ ?>

			<?php
			/**
			 * Featured categories for magazine layouts
			 *
			 * @since 1.0
			 */
			if ( get_theme_mod( 'penci_move_latest_posts_above' ) && ( ( get_theme_mod( 'penci_home_featured_cat' ) && ( $layout_this == 'magazine-1' || $layout_this == 'magazine-2' ) ) || get_theme_mod( 'penci_enable_featured_cat_all_layouts' ) ) ):
				get_template_part( 'inc/modules/featured-categories' );
			endif;
			?>

        </div>
    </div>

	<?php if ( penci_get_setting( 'penci_sidebar_home' ) ) : ?>
		<?php get_sidebar(); ?>
		<?php if ( get_theme_mod( "penci_two_sidebar_home" ) ) : get_sidebar( 'left' ); endif; ?>
	<?php endif; ?>
</div>
<?php get_footer(); ?>
