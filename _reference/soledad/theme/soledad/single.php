<?php
/**
 * The Template for displaying all single posts
 *
 * @package Wordpress
 * @since   1.0
 */
get_header();

$single_style = penci_get_single_style();

if ( ! in_array( $single_style, array( 'style-1', 'style-2' ) ) ) {
	get_template_part( 'template-parts/single', $single_style );

	return;
}

$sidebar_enable      = penci_single_sidebar_return();
$sidebar_position    = penci_get_posts_sidebar_class();
$sidebar_small_width = penci_single_smaller_content_enable();

$postID            = get_the_ID();
$current_permalink = get_permalink( $postID );
$current_title     = get_the_title( $postID );
$infinite_load     = get_theme_mod( 'penci_loadnp_posts' ) ? get_theme_mod( 'penci_loadnp_posts' ) : false;
$prev_post_id      = $prev_post_url = $prev_post_title = $wrap_inficlass = $flag_infi = '';
$data_infiads      = get_theme_mod( 'penci_loadnp_ads' ) ? '<div class="penci-single-infiads">' . get_theme_mod( 'penci_loadnp_ads' ) . '</div>' : '';
if ( get_theme_mod( 'penci_loadnp_posts' ) ) {
	$prev_post = penci_get_next_prev_posts();
	$flag_infi = 'no_data';
	if ( ! empty( $prev_post ) && $prev_post != null && $prev_post != '' ) {
		$prev_post_id    = $prev_post->ID;
		$prev_post_url   = get_permalink( $prev_post_id );
		$prev_post_title = get_the_title( $prev_post_id );
		$wrap_inficlass  = ' penci-single-infiscroll';
		$flag_infi       = 'has_data';
	}
}
?>

<div class="penci-single-wrapper<?php echo $wrap_inficlass; ?>"<?php if ( get_theme_mod( 'penci_loadnp_posts' ) && $data_infiads ) {
	echo ' data-infiads="' . htmlentities( $data_infiads ) . '"';
} ?>>
    <div class="penci-single-block<?php if ( $flag_infi == 'no_data' ) {
		echo ' penci-single-infiblock-end';
	} ?>"<?php if ( get_theme_mod( 'penci_loadnp_posts' ) ): ?>
         data-prev-url="<?php echo esc_url( $prev_post_url ); ?>"
         data-current-url="<?php echo esc_url( $current_permalink ); ?>"
         data-post-title="<?php echo esc_attr( $current_title ); ?>"
         data-edit-post="<?php echo get_edit_post_link( $postID ); ?>"
         data-postid="<?php echo $postID; ?>"<?php endif; ?>>
		<?php if ( ! get_theme_mod( 'penci_disable_breadcrumb' ) && ( ! get_theme_mod( 'penci_move_breadcrumbs' ) || 'style-2' == $single_style ) ): ?>
			<?php
			$yoast_breadcrumb = $rm_breadcrumb = '';
			if ( function_exists( 'yoast_breadcrumb' ) ) {
				$yoast_breadcrumb = yoast_breadcrumb( '<div class="container penci-breadcrumb single-breadcrumb">', '</div>', false );
			}

			if ( function_exists( 'rank_math_get_breadcrumbs' ) ) {
				$rm_breadcrumb = rank_math_get_breadcrumbs( [
					'wrap_before' => '<div class="container penci-breadcrumb single-breadcrumb"><nav aria-label="breadcrumbs" class="rank-math-breadcrumb">',
					'wrap_after'  => '</nav></div>',
				] );
			}

			if ( $rm_breadcrumb ) {
				echo $rm_breadcrumb;
			} elseif ( $yoast_breadcrumb ) {
				echo $yoast_breadcrumb;
			} else {
				?>
                <div class="container penci-breadcrumb single-breadcrumb">
                    <span><a class="crumb"
                             href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php echo penci_get_setting( 'penci_trans_home' ); ?></a></span><?php penci_fawesome_icon( 'fas fa-angle-right' ); ?>
					<?php
					if ( get_theme_mod( 'enable_pri_cat_yoast_seo' ) ) {
						$primary_term = penci_get_wpseo_primary_term();

						if ( $primary_term ) {
							echo $primary_term;
						} else {
							$penci_cats = get_the_category( get_the_ID() );
							$penci_cat  = array_shift( $penci_cats );
							echo penci_get_category_parents( $penci_cat );
						}
					} else {
						$penci_cats = get_the_category( get_the_ID() );
						$penci_cat  = array_shift( $penci_cats );
						echo penci_get_category_parents( $penci_cat );
					}
					if ( ! get_theme_mod( 'penci_hide_post_title_breadcrumb' ) ) {
						echo '<span>' . get_the_title() . '</span>';
					}
					?>
                </div>
			<?php } ?>
		<?php endif; ?>

		<?php if ( 'style-2' == $single_style ) : ?>
            <div class="penci-single-pheader container container-single<?php if ( get_theme_mod( 'penci_home_layout' ) == 'magazine-1' || get_theme_mod( 'penci_home_layout' ) == 'magazine-2' ): ?> container-single-magazine<?php endif; ?><?php if ( $sidebar_enable ) {
				echo ' ' . esc_attr( $sidebar_position );
			} else {
				echo ' penci_is_nosidebar';
			} ?> container-single-fullwidth hentry">
				<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
					<?php get_template_part( 'content', 'single-full' ); ?>
				<?php endwhile; endif; ?>
            </div>
		<?php endif; ?>

        <div class="container container-single<?php if ( get_theme_mod( 'penci_home_layout' ) == 'magazine-1' || get_theme_mod( 'penci_home_layout' ) == 'magazine-2' ): ?> container-single-magazine<?php endif; ?><?php if ( $sidebar_enable ) { ?> penci_sidebar <?php echo esc_attr( $sidebar_position ); ?><?php } else {
			echo ' penci_is_nosidebar';
		} ?><?php if ( $sidebar_small_width ): ?> penci-single-smaller-width<?php endif; ?><?php if ( ! get_theme_mod( 'penci_disable_lightbox_single' ) ): ?> penci-enable-lightbox<?php endif; ?>">
            <div id="main"<?php if ( get_theme_mod( 'penci_sidebar_sticky' ) ): ?> class="penci-main-sticky-sidebar"<?php endif; ?>>
                <div class="theiaStickySidebar">

					<?php if ( ! get_theme_mod( 'penci_disable_breadcrumb' ) && get_theme_mod( 'penci_move_breadcrumbs' ) && 'style-1' == $single_style ): ?>
						<?php
						$yoast_breadcrumb = $rm_breadcrumb = '';
						if ( function_exists( 'yoast_breadcrumb' ) ) {
							$yoast_breadcrumb = yoast_breadcrumb( '<div class="container penci-breadcrumb penci-crumb-inside single-breadcrumb">', '</div>', false );
						}

						if ( function_exists( 'rank_math_get_breadcrumbs' ) ) {
							$rm_breadcrumb = rank_math_get_breadcrumbs( [
								'wrap_before' => '<div class="container penci-breadcrumb penci-crumb-inside single-breadcrumb"><nav aria-label="breadcrumbs" class="rank-math-breadcrumb">',
								'wrap_after'  => '</nav></div>',
							] );
						}

						if ( $rm_breadcrumb ) {
							echo $rm_breadcrumb;
						} elseif ( $yoast_breadcrumb ) {
							echo $yoast_breadcrumb;
						} else { ?>
                            <div class="container penci-breadcrumb penci-crumb-inside single-breadcrumb">
                                        <span><a class="crumb"
                                                 href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php echo penci_get_setting( 'penci_trans_home' ); ?></a></span><?php penci_fawesome_icon( 'fas fa-angle-right' ); ?>
								<?php
								if ( get_theme_mod( 'enable_pri_cat_yoast_seo' ) ) {
									$primary_term = penci_get_wpseo_primary_term();

									if ( $primary_term ) {
										echo $primary_term;
									} else {
										$penci_cats = get_the_category( get_the_ID() );
										$penci_cat  = array_shift( $penci_cats );
										echo penci_get_category_parents( $penci_cat );
									}
								} else {
									$penci_cats = get_the_category( get_the_ID() );
									$penci_cat  = array_shift( $penci_cats );
									echo penci_get_category_parents( $penci_cat );
								}
								if ( ! get_theme_mod( 'penci_hide_post_title_breadcrumb' ) ) {
									echo '<span>' . get_the_title() . '</span>';
								}
								?>
                            </div>
						<?php } ?>
					<?php endif; ?>

					<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
						<?php /* Count viewed posts */
						penci_set_post_views( $post->ID ); ?>
						<?php get_template_part( 'content', 'single' ); ?>
					<?php endwhile; endif; ?>
                </div>
            </div>
			<?php 
			if ( get_theme_mod( 'penci_single_delayed_sidebar' ) && penci_is_mobile() ) {
				?>
				<div data-id="section-sidebar-<?php the_ID();?>" data-url="<?php echo esc_url( add_query_arg(['penci_get_delayed_sections' => 'sidebar'],get_permalink()) );?>" class="pcfb-single-sections-delayed pc-content-delayed"></div>
				<?php
			} else {
				get_template_part( 'template-parts/single', 'sidebar' );
			}
			?>
        </div>

		<?php do_action( 'penci_action_after_post_content' ); ?>
    </div>
</div>
<?php if ( get_theme_mod( 'penci_loadnp_posts' ) && $flag_infi != 'no_data' ) { ?>
    <div class="penci-ldsingle">
        <div class="penci-ldspinner">
            <div class="bounce1"></div>
            <div class="bounce2"></div>
            <div class="bounce3"></div>
        </div>
    </div>
<?php } ?>
<?php get_footer(); ?>
