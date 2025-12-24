<?php
/**
 * Main sidebar of Soledad theme
 * Display all widgets on left sidebar
 *
 * @package Wordpress
 * @since   1.0
 */
$heading_title       = get_theme_mod( 'penci_sidebar_heading_style' ) ? get_theme_mod( 'penci_sidebar_heading_style' ) : 'style-1';
$heading_align       = get_theme_mod( 'penci_sidebar_heading_align' ) ? get_theme_mod( 'penci_sidebar_heading_align' ) : 'pcalign-center';
$sidebar_style       = get_theme_mod( 'penci_sidebar_style' ) ? get_theme_mod( 'penci_sidebar_style' ) : '';
$sidebar_icon_pos    = get_theme_mod( 'penci_sidebar_icon_align' ) ? get_theme_mod( 'penci_sidebar_icon_align' ) : 'pciconp-right';
$sidebar_icon_design = get_theme_mod( 'penci_sidebar_icon_design' ) ? get_theme_mod( 'penci_sidebar_icon_design' ) : 'pcicon-right';

$sidebar_portfolio = get_theme_mod( 'penci_sidebar_left_single_portfolio' );
if ( ! isset( $sidebar_portfolio ) || empty( $sidebar_portfolio ) ): $sidebar_portfolio = 'main-sidebar-left'; endif;
$sidebar_default = 'main-sidebar-left';
if ( ! is_active_sidebar( $sidebar_default ) ) {
	$sidebar_default = 'main-sidebar';
}
$sidebar_home = get_theme_mod( 'penci_sidebar_left_name_home' );
if ( ! isset( $sidebar_home ) || empty( $sidebar_home ) ): $sidebar_home = $sidebar_default; endif;

$sidebar_category = get_theme_mod( 'penci_sidebar_left_name_category' );
if ( ! isset( $sidebar_category ) || empty( $sidebar_category ) ): $sidebar_category = $sidebar_default; endif;

$sidebar_single = get_theme_mod( 'penci_sidebar_left_name_single' );
if ( ! isset( $sidebar_single ) || empty( $sidebar_single ) ): $sidebar_single = $sidebar_default; endif;

$sidebar_pages = get_theme_mod( 'penci_sidebar_left_name_pages' );
if ( ! isset( $sidebar_pages ) || empty( $sidebar_pages ) ): $sidebar_pages = $sidebar_default; endif;

if ( is_home() || is_front_page() ) {
	$show_on_front = get_option( 'show_on_front' );
	if ( 'page' == $show_on_front ) {
		$custom_sidebar_pages = get_post_meta( get_the_ID(), 'penci_custom_sidebar_left_page_field', true );
		if ( $custom_sidebar_pages ): $sidebar_pages = $custom_sidebar_pages; endif;
	}
} elseif ( is_page() ) {
	$custom_sidebar_pages = get_post_meta( get_the_ID(), 'penci_custom_sidebar_left_page_field', true );
	if ( $custom_sidebar_pages ): $sidebar_pages = $custom_sidebar_pages; endif;
} elseif ( is_single() ) {
	$custom_sidebar_post = get_post_meta( get_the_ID(), 'penci_custom_sidebar_left_page_field', true );
	if ( $custom_sidebar_post ): $sidebar_single = $custom_sidebar_post; endif;

	$priid = penci_get_post_pri_cat();
	if ( $priid ) {
		$cat_options = get_option( "category_$priid" );
		$override = isset( $cat_options['cat_sidebar_single'] ) ? $cat_options['cat_sidebar_single'] : '';
		if ( $override ) {
			$cat_sidebar = isset( $cat_options['cat_sidebar_left'] ) ? $cat_options['cat_sidebar_left'] : '';
			if ( $cat_sidebar ): $sidebar_single = $cat_sidebar; endif;
		}
	}

} elseif ( is_category() ) {
	$category_oj = get_queried_object();
	$fea_cat_id  = $category_oj->term_id;
	$cat_meta    = get_option( "category_$fea_cat_id" );
	$cat_sidebar = isset( $cat_meta['cat_sidebar_left'] ) ? $cat_meta['cat_sidebar_left'] : '';
	if ( $cat_sidebar ): $sidebar_category = $cat_sidebar; endif;
}
$product_sidebar = 'penci-shop-single';
?>

<div id="sidebar"
     class="penci-sidebar-left penci-sidebar-content <?php echo esc_attr( $heading_title . ' ' . $heading_align . ' ' . $sidebar_style . ' ' . $sidebar_icon_pos . ' ' . $sidebar_icon_design ); ?><?php if ( get_theme_mod( 'penci_sidebar_sticky' ) ): echo ' penci-sticky-sidebar'; endif; ?>">
    <div class="theiaStickySidebar">
		<?php /* Display sidebar */
		if ( ! wp_is_mobile() && is_singular( 'portfolio' ) && function_exists( 'portfolio_meta_content' ) && isset( $GLOBALS['left-sidebar'] ) ) {
			portfolio_meta_content( $GLOBALS['share_style'], ! $GLOBALS['share_box'] );
		}
		if ( is_singular( 'portfolio' ) && is_active_sidebar( $sidebar_portfolio ) ) {
			dynamic_sidebar( $sidebar_portfolio );
		} else if ( function_exists( 'is_shop' ) && function_exists( 'is_product_category' ) && function_exists( 'is_product_tag' ) && function_exists( 'is_product' ) ) {
			if ( ( is_shop() || is_product_category() || is_product_tag() ) && is_active_sidebar( 'penci-shop-sidebar' ) ) {
				dynamic_sidebar( 'penci-shop-sidebar' );
			} else if ( is_product() && is_active_sidebar( $product_sidebar ) ) {
				dynamic_sidebar( $product_sidebar );
			} else {
				if ( ( is_home() || is_front_page() ) ) {
					$show_on_front = get_option( 'show_on_front' );
					if ( 'page' == $show_on_front && is_active_sidebar( $sidebar_pages ) ) {
						dynamic_sidebar( $sidebar_pages );
					} elseif ( is_active_sidebar( $sidebar_home ) ) {
						dynamic_sidebar( $sidebar_home );
					}
				} else if ( is_category() && is_active_sidebar( $sidebar_category ) ) {
					dynamic_sidebar( $sidebar_category );
				} else if ( is_single() && is_active_sidebar( $sidebar_single ) ) {
					dynamic_sidebar( $sidebar_single );
				} else if ( is_page() && is_active_sidebar( $sidebar_pages ) ) {
					dynamic_sidebar( $sidebar_pages );
				} else {
					dynamic_sidebar( $sidebar_default );
				}
			}
		} else if ( ( is_home() || is_front_page() ) ) {
			$show_on_front = get_option( 'show_on_front' );
			if ( 'page' == $show_on_front && is_active_sidebar( $sidebar_pages ) ) {
				dynamic_sidebar( $sidebar_pages );
			} elseif ( is_active_sidebar( $sidebar_home ) ) {
				dynamic_sidebar( $sidebar_home );
			}
		} else if ( is_category() && is_active_sidebar( $sidebar_category ) ) {
			dynamic_sidebar( $sidebar_category );
		} else if ( is_single() && is_active_sidebar( $sidebar_single ) ) {
			dynamic_sidebar( $sidebar_single );
		} else if ( is_page() && is_active_sidebar( $sidebar_pages ) ) {
			dynamic_sidebar( $sidebar_pages );
		} else {
			dynamic_sidebar( $sidebar_default );
		}
		?>
    </div>
</div>
