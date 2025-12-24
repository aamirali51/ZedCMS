<?php
add_filter( 'body_class', function ( $class ) {
	$class[] = 'pccustom-template-enable';

	return $class;
} );
get_header();
penci_set_post_views(get_the_ID());
// load next posts
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
<div class="container-single-page penci-single-wrapper<?php echo $wrap_inficlass; ?>"<?php if ( get_theme_mod( 'penci_loadnp_posts' ) && $data_infiads ) {
	echo ' data-infiads="' . htmlentities( $data_infiads ) . '"';
} ?>>
    <div id="main" class="penci-custom-single-template penci-single-block<?php if ( $flag_infi == 'no_data' ) {
		echo ' penci-single-infiblock-end';
	} ?>"<?php if ( get_theme_mod( 'penci_loadnp_posts' ) ): ?>
        data-prev-url="<?php echo esc_url( $prev_post_url ); ?>"
        data-current-url="<?php echo esc_url( $current_permalink ); ?>"
        data-post-title="<?php echo esc_attr( $current_title ); ?>"
        data-edit-post="<?php echo get_edit_post_link( $postID ); ?>"
        data-postid="<?php echo $postID; ?>"<?php endif; ?>>
		<?php
		$html      = '';
		$post_template_id = penci_should_render_single_template();
		if ( $post_template_id ) {

			if ( did_action( 'elementor/loaded' ) && \Elementor\Plugin::$instance->documents->get( $post_template_id )->is_built_with_elementor() ) {


				if ( have_posts() ) : while ( have_posts() ) : the_post();
					$html = penci_get_elementor_content( $post_template_id );
				endwhile;endif;

				echo $html;
			} else {
				$builder_content = get_post( $post_template_id );
				echo '<div class="js-composer-content">';
				echo do_shortcode( $builder_content->post_content );

				$shortcodes_custom_css = get_post_meta( $post_template_id, '_wpb_shortcodes_custom_css', true );

				echo '<style data-type="vc_shortcodes-custom-css">';
				if ( ! empty( $shortcodes_custom_css ) ) {
					echo $shortcodes_custom_css;
				}
				echo '</style>';
				echo '</div>';
			}
		}
		?>
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
