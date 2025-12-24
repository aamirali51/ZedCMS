<?php
if ( ! function_exists( 'penci_header_default' ) ) {
	function penci_header_default( $option ) {
		$default = '';

		switch ( $option ) {

			/** DISPLAY */
			case 'desktop_display_top_left':
			case 'desktop_display_mid_right':
			case 'desktop_display_bottom_left':
			case 'sticky_display_mid_left':
			case 'mobile_display_mid_center':
				$default = 'grow';
				break;
			case 'desktop_display_top_center':
			case 'desktop_display_top_right':
			case 'desktop_display_mid_left':
			case 'desktop_display_mid_center':
			case 'desktop_display_bottom_center':
			case 'desktop_display_bottom_right':
			case 'sticky_display_mid_center':
			case 'sticky_display_mid_right':
			case 'mobile_display_mid_left':
			case 'mobile_display_mid_right':
				$default = 'normal';
				break;
		}

		return $default;
	}
}


function penci_builder_menu_list() {
	$menu_list = [ '' => '' ];
	$menus     = wp_get_nav_menus();
	if ( ! empty( $menus ) ) {
		foreach ( $menus as $menu ) {
			$menu_list[ $menu->slug ] = $menu->name;
		}
	}

	return $menu_list;
}

if ( ! function_exists( 'penci_can_render_header' ) ) {
	function penci_can_render_header( $device, $row ) {
		$columns    = array();
		$can_render = false;

		if ( $device === 'desktop' || $device === 'desktop_sticky' ) {
			$columns = array( 'left', 'center', 'right' );
		}

		if ( $device === 'mobile' ) {
			$columns = array( 'left', 'center', 'right' );
		}

		foreach ( $columns as $column ) {


			$setting_element = "penci_hb_element_{$device}_{$row}_{$column}";
			$default_element = penci_get_builder_mod( $setting_element );
			$default_element = ! empty( $default_element ) ? explode( ',', $default_element ) : $default_element;


			if ( ! empty( $default_element ) && is_array( $default_element ) ) {
				$can_render = true;
				break;
			}
		}

		return $can_render;
	}
}

function penci_hb_element_desktop_top_preview_render() {
	load_template( PENCI_BUILDER_PATH . '/template/desktop-top.php' );
}

function penci_hb_element_desktop_topblock_preview_render() {
	load_template( PENCI_BUILDER_PATH . '/template/desktop-topblock.php' );
}

function penci_hb_element_desktop_mid_preview_render() {
	load_template( PENCI_BUILDER_PATH . '/template/desktop-mid.php' );
}

function penci_hb_element_desktop_bottom_preview_render() {
	load_template( PENCI_BUILDER_PATH . '/template/desktop-bottom.php' );
}

function penci_hb_element_desktop_bottomblock_preview_render() {
	load_template( PENCI_BUILDER_PATH . '/template/desktop-bottomblock.php' );
}

function penci_hb_element_mobile_top_preview_render() {
	load_template( PENCI_BUILDER_PATH . '/template/mobile-top.php' );
}

function penci_hb_element_mobile_mid_preview_render() {
	load_template( PENCI_BUILDER_PATH . '/template/mobile-mid.php' );
}

function penci_hb_element_mobile_bottom_preview_render() {
	load_template( PENCI_BUILDER_PATH . '/template/mobile-bottom.php' );
}

function penci_hb_element_desktop_sticky_top_preview_render() {
	load_template( PENCI_BUILDER_PATH . '/template/desktop-sticky-top.php' );
}

function penci_hb_element_desktop_sticky_mid_preview_render() {
	load_template( PENCI_BUILDER_PATH . '/template/desktop-sticky-mid.php' );
}

function penci_hb_element_desktop_sticky_bottom_preview_render() {
	load_template( PENCI_BUILDER_PATH . '/template/desktop-sticky-bottom.php' );
}
