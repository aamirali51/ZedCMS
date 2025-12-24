<?php
/**
 * Main sidebar of Soledad theme
 * Display all widgets on right sidebar
 *
 * @package Wordpress
 * @since   1.0
 */
$heading_title       = get_theme_mod( 'penci_sidebar_heading_style' ) ? get_theme_mod( 'penci_sidebar_heading_style' ) : 'style-1';
$heading_align       = get_theme_mod( 'penci_sidebar_heading_align' ) ? get_theme_mod( 'penci_sidebar_heading_align' ) : 'pcalign-center';
$sidebar_style       = get_theme_mod( 'penci_sidebar_style' ) ? get_theme_mod( 'penci_sidebar_style' ) : '';
$sidebar_icon_pos    = get_theme_mod( 'penci_sidebar_icon_align' ) ? get_theme_mod( 'penci_sidebar_icon_align' ) : 'pciconp-right';
$sidebar_icon_design = get_theme_mod( 'penci_sidebar_icon_design' ) ? get_theme_mod( 'penci_sidebar_icon_design' ) : 'pcicon-right';
?>

<div id="sidebar"
     class="penci-sidebar-content <?php echo esc_attr( $heading_title . ' ' . $heading_align . ' ' . $sidebar_style . ' ' . $sidebar_icon_pos . ' ' . $sidebar_icon_design ); ?><?php if ( get_theme_mod( 'penci_sidebar_sticky' ) ): echo ' penci-sticky-sidebar'; endif; ?>">
    <div class="theiaStickySidebar">
		<?php
		if ( is_active_sidebar( 'penci-buddypress' ) ) {
			dynamic_sidebar( 'penci-buddypress' );
		} elseif ( is_active_sidebar( 'main-sidebar' ) ) {
			dynamic_sidebar( 'main-sidebar' );
		}
		?>
    </div>
</div>
