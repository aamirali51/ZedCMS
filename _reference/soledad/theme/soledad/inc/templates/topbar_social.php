<div class="pctopbar-item penci-topbar-social<?php if( get_theme_mod('penci_top_bar_brand_social') ): echo ' penci-social-textcolored'; endif; if( get_theme_mod('penci_tblogin') ): echo ' penci-lgdisplay-' . get_theme_mod('penci_tblogin'); endif; ?>">
	<?php
	if( get_theme_mod( 'penci_tblogin' ) == 'left' ){
		get_template_part( 'inc/templates/topbar_login' );
	}
	if( ! get_theme_mod( 'penci_top_bar_hide_social' ) ){
		get_template_part( 'inc/modules/socials' );
	}
	if( get_theme_mod( 'penci_tblogin' ) == 'right' ){
		get_template_part( 'inc/templates/topbar_login' );
	}
	?>
</div>