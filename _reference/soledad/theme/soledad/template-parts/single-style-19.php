<?php
$sidebar_enable      = penci_single_sidebar_return();
$sidebar_position    = penci_get_posts_sidebar_class();
$sidebar_small_width = penci_single_smaller_content_enable();

// Check layout magazine
$single_magazine = 'container container-single penci-single-style-19 penci-single-smore container-single-fullwidth hentry';
if ( get_theme_mod( 'penci_home_layout' ) == 'magazine-1' || get_theme_mod( 'penci_home_layout' ) == 'magazine-2' ) {
	$single_magazine .= ' container-single-magazine';
}

// Check class main content
$class_container_single = 'container container-single penci-single-style-19 penci-single-smore';
if ( $sidebar_enable ) {
	$class_container_single .= ' penci_sidebar';
	$class_container_single .= ' ' . $sidebar_position;

	$single_magazine .= ' ' . $sidebar_position;
} else {
	$class_container_single .= ' penci_is_nosidebar';
	$single_magazine        .= ' penci_is_nosidebar';
}

if ( $sidebar_small_width ) {
	$class_container_single .= ' penci-single-smaller-width';
}

if ( ! get_theme_mod( 'penci_disable_lightbox_single' ) ) {
	$class_container_single .= ' penci-enable-lightbox';
}

if ( ! get_theme_mod( 'penci_disable_lightbox_single' ) ) {
	$class_container_single .= ' penci-enable-lightbox';
}
$post_format = get_post_format();

$post_format      = get_post_format();
$show_post_format = true;
if ( get_theme_mod( 'penci_post_thumb' ) && ! in_array( $post_format, array( 'link', 'quote', 'gallery', 'video' ) ) ) {
	$class_container_single .= ' penci-single-pheader-noimg';
	$show_post_format       = false;
}

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
		} ?>"<?php if ( get_theme_mod( 'penci_loadnp_posts' ) ): ?> data-prev-url="<?php echo esc_url( $prev_post_url ); ?>" data-current-url="<?php echo esc_url( $current_permalink ); ?>" data-post-title="<?php echo esc_attr( $current_title ); ?>" data-edit-post="<?php echo get_edit_post_link( $postID ); ?>" data-postid="<?php echo $postID; ?>"<?php endif; ?>>
            <div class="penci-single-pheader <?php echo( $single_magazine ); ?>">
				<?php
				if ( have_posts() ) :
					while ( have_posts() ) : the_post();
						if ( ! get_theme_mod( 'penci_move_title_bellow' ) ) {
							get_template_part( 'template-parts/single', 'breadcrumb' );
							get_template_part( 'template-parts/single', 'entry-header' );
						}
					endwhile;
				endif;
				?>
            </div>
            <div class="<?php echo $class_container_single; ?>">
                <div id="main"<?php if ( get_theme_mod( 'penci_sidebar_sticky' ) ): ?> class="penci-main-sticky-sidebar"<?php endif; ?>>
                    <div class="theiaStickySidebar">
						<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
							<?php get_template_part( 'template-parts/single', 'post-format' ); ?>
							<?php penci_set_post_views( $post->ID ); ?>
							<?php get_template_part( 'template-parts/single', 'content-main' ); ?>
						<?php endwhile; endif; ?>
                    </div>
                </div>
				<?php
				if ( get_theme_mod( 'penci_single_delayed_sidebar' ) && penci_is_mobile() ) {
					?>
                    <div data-id="section-sidebar-<?php the_ID(); ?>"
                         data-url="<?php echo esc_url( add_query_arg( [ 'penci_get_delayed_sections' => 'sidebar' ], get_permalink() ) ); ?>"
                         class="pcfb-single-sections-delayed pc-content-delayed"></div>
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