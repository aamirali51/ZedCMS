<?php
$options   = [];
$options[] = array(
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field',
	'label'    => __( 'Disable Filter Trigger Auto Scroll', 'soledad' ),
	'id'       => 'penci_woo_mobile_autoscroll',
	'type'     => 'soledad-fw-toggle',
);
$options[] = array(
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field',
	'label'    => __( 'Enable Bottom Navigation', 'soledad' ),
	'id'       => 'penci_woo_mobile_bottom_nav',
	'type'     => 'soledad-fw-toggle',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'penci_sanitize_multiple_checkbox',
	'label'    => __( 'Select navigation menu item', 'soledad' ),
	'id'       => 'penci_woo_mobile_nav_items',
	'multiple' => 999,
	'type'     => 'soledad-fw-select',
	'choices'  => array(
		'home'     => __('Home Page','soledad' ),
		'shop'     => __('Shop Page','soledad' ),
		'cart'     => __('Cart Page','soledad' ),
		'account'  => __('Account Page','soledad' ),
		'wishlist' => __('Wishlist Page','soledad' ),
		'compare'  => __('Compare Page','soledad' ),
		'filter'   => __('Category Filter Panel','soledad' ),
	),
);
$options[] = array(
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field',
	'label'    => __( 'Show custom Navigation Menus', 'soledad' ),
	'id'       => 'penci_woo_mobile_show_custom_nav',
	'type'     => 'soledad-fw-toggle',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'penci_sanitize_choices_field',
	'label'    => __( 'Select Custom Menu for Mobile Bottom Navigation', 'soledad' ),
	'id'       => 'penci_woo_mobile_nav_menu',
	'type'     => 'soledad-fw-ajax-select',
	'choices'  => call_user_func( function () {
		$menu_list = [ '' => '' ];
		$menus     = wp_get_nav_menus();
		if ( ! empty( $menus ) ) {
			foreach ( $menus as $menu ) {
				$menu_list[ $menu->slug ] = $menu->name;
			}
		}

		return $menu_list;
	} ),
);

return $options;
