<?php
/**
 * Top bar template
 * Options for it in Customize > Top Bar Options & Colors For Top Bar
 *
 * @since 1.0
 */
?>
<div class="penci-top-bar<?php if ( get_theme_mod( 'penci_top_bar_hide_social' ) ): echo ' no-social'; endif;
if ( get_theme_mod( 'penci_top_bar_enable_menu' ) ): echo ' topbar-menu'; endif;
if ( get_theme_mod( 'penci_top_bar_1400' ) ): echo ' topbar-1400px'; endif;
if ( get_theme_mod( 'penci_top_bar_full_width' ) ): echo ' topbar-fullwidth'; endif; ?>">
    <div class="container<?php if ( get_theme_mod( 'penci_top_bar_1400' ) ): echo ' container-1400'; endif; ?>">
        <div class="penci-headline" role="navigation"
		     <?php if ( ! get_theme_mod( 'penci_schema_sitenav' ) ): ?>itemscope
             itemtype="https://schema.org/SiteNavigationElement"<?php endif; ?>>
	        <?php do_action( 'penci_topbar_content' ); ?>
            <?php
			$tbreorder      = get_theme_mod( 'penci_topbar_ordersec' ) ? get_theme_mod( 'penci_topbar_ordersec' ) : 'toptext-topposts-topmenu-topsocial';
			$tbreorderarray = explode( '-', $tbreorder );
			if ( ! empty( $tbreorderarray ) ) {
				foreach ( $tbreorderarray as $tbsec ) {
					if ( $tbsec == 'toptext' && get_theme_mod( 'penci_tbtext_enable' ) ) :
						get_template_part( 'inc/templates/topbar_text' );
					endif;
					if ( $tbsec == 'topposts' && penci_get_setting( 'penci_toppost_enable' ) ) :
						get_template_part( 'inc/templates/topbar_topposts' );
					endif;
					if ( $tbsec == 'topmenu' && get_theme_mod( 'penci_top_bar_enable_menu' ) ) :
						get_template_part( 'inc/templates/topbar_menu' );
					endif;
					if ( $tbsec == 'topsocial' && ( ! get_theme_mod( 'penci_top_bar_hide_social' ) || get_theme_mod( 'penci_tblogin' ) ) ) :
						get_template_part( 'inc/templates/topbar_social' );
					endif;
				}
			}
			?>
        </div>
    </div>
</div>
