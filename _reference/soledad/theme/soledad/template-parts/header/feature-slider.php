<?php
if ( get_theme_mod( 'penci_enable_featured_video_bg' ) && get_theme_mod( 'penci_featured_video_url' ) ) {
	get_template_part( 'inc/featured_slider/featured_video' );
} else {
	if ( get_theme_mod( 'penci_featured_slider' ) ) {
		Penci_Featured_Slider::render_featured_slider();
	}
}