<?php if ( is_active_sidebar( 'top-instagram' ) ): ?>
	<div class="footer-instagram penci-top-instagram<?php if( get_theme_mod('penci_top_insta_overlay_image') ): echo ' penci-insta-title-overlay'; endif; ?>">
		<?php dynamic_sidebar( 'top-instagram' ); ?>
	</div>
<?php endif; ?>