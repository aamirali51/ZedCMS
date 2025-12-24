<?php
if ( isset( $_SERVER['HTTP_X_PJAX'] ) && $_SERVER['HTTP_X_PJAX'] === 'true' ) {
	return;
}
$render = true;

if ( is_customize_preview() && strpos( $_SERVER['HTTP_REFERER'], 'customize_amp=1' ) !== false ) {
	$render = false;
}

if ( defined( 'IFRAME_REQUEST' ) ) {
	$render = false;
}

if ( isset( $_REQUEST['customize_amp'] ) && $_REQUEST['customize_amp'] ) {
	$render = false;
}

if ( function_exists( 'elementor_location_exits' ) && elementor_location_exits( 'footer', true ) ) {
	$render = false;
}

if ( is_page_template( 'elementor_canvas' ) ) {
	$render = false;
}

if ( $render ) {
	get_template_part( 'template-parts/footer/footer' );
}
wp_footer(); ?>
</body>
</html>