<?php
remove_action( 'admin_footer', 'vc_loop_include_templates', 1 );
add_action( 'wp_ajax_vc_edit_form', 'penci_remove_shortcode_param_loop', 1 );
if ( ! function_exists( 'penci_remove_shortcode_param_loop' ) ) {
	function penci_remove_shortcode_param_loop() {
		global $vc_params_list;

		$key = array_search( 'loop', $vc_params_list, true );
		if ( false !== $key ) {
			unset( $vc_params_list[ $key ] );
		}

	}
}

require PENCI_SOLEDAD_DIR . '/inc/js_composer/params/loop/register.php';
require PENCI_SOLEDAD_DIR . '/inc/js_composer/params/number/register.php';
require PENCI_SOLEDAD_DIR . '/inc/js_composer/params/only_number/register.php';
require PENCI_SOLEDAD_DIR . '/inc/js_composer/params/post_metas/register.php';
require PENCI_SOLEDAD_DIR . '/inc/js_composer/params/separator/register.php';
require PENCI_SOLEDAD_DIR . '/inc/js_composer/params/custom_markup/register.php';
require PENCI_SOLEDAD_DIR . '/inc/js_composer/params/heading_title/register.php';
require PENCI_SOLEDAD_DIR . '/inc/js_composer/params/buttons_set/register.php';
require PENCI_SOLEDAD_DIR . '/inc/js_composer/params/switch/register.php';
require PENCI_SOLEDAD_DIR . '/inc/js_composer/params/slider/register.php';
require PENCI_SOLEDAD_DIR . '/inc/js_composer/params/sizes/register.php';
require PENCI_SOLEDAD_DIR . '/inc/js_composer/params/spacing/register.php';

vc_add_shortcode_param( 'loop', 'penci_soledad_vc_param_loop' );
vc_add_shortcode_param( 'penci_number', 'penci_vc_param_number' );
vc_add_shortcode_param( 'penci_only_number', 'penci_vc_param_only_number' );
vc_add_shortcode_param( 'penci_post_metas', 'penci_vc_param_post_metas' );
vc_add_shortcode_param( 'penci_separator', 'penci_vc_param_separator' );
vc_add_shortcode_param( 'penci_custom_markup', 'penci_vc_param_custom_markup' );
vc_add_shortcode_param( 'penci_heading_title', 'penci_vc_param_heading_title' );
vc_add_shortcode_param( 'penci_buttons_sets', 'penci_get_button_set_param' );
vc_add_shortcode_param( 'penci_switch', 'penci_get_switch_param' );
vc_add_shortcode_param( 'penci_slider', 'penci_get_slider_param' );
vc_add_shortcode_param( 'penci_responsive_sizes', 'penci_get_responsive_size_param' );
vc_add_shortcode_param( 'penci_responsive_spacing', 'penci_get_responsive_spacing_param' );
