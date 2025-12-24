<?php

/**
 * Class penci_menu_walker_nav_menu
 * This class will remove wrap </a> around mega menu
 * Callback on wp_nav_menu() in header.php file
 *
 * @since 1.0
 */
class penci_menu_builder_walker_nav_menu extends Walker_Nav_Menu {
	private $logo_break_point = null;
	private $count_item_parent = 0;
	private $curItem;

	/**
	 * Starts the list before the elements are added.
	 *
	 * @param string $output Passed by reference. Used to append additional content.
	 * @param int $depth Depth of menu item. Used for padding.
	 * @param mixed $args An array of arguments. @see wp_nav_menu().
	 *
	 * @since 3.0.0
	 *
	 * @see   Walker::start_lvl()
	 */
	public function start_lvl( &$output, $depth = 0, $args = array() ) {
		$menu_item       = $this->curItem;
		$penci_menu_type = get_post_meta( $menu_item->ID, 'penci_menu_type', true );
		$penci_menu_mw   = get_post_meta( $menu_item->ID, 'penci_menu_mw', true );

		$indent  = str_repeat( "\t", $depth );
		$classes = '';
		if ( 0 === $depth && 'mega-menu' == $penci_menu_type ) {

			$classes .= ' penci-dropdown-menu penci-dropdown';

			$classes .= $penci_menu_mw ? ' penci-mega-custom-width' : ' penci-mega-full-width';

			$output .= $indent . '<div class="' . trim( $classes ) . '">';
			$output .= $indent . '<div class="container">';
		}

		if ( 0 === $depth && 'mega-menu' == $penci_menu_type ) {
			$sub_menu_class = 'penci-megamenu-sub sub-sub-menu';
		} else {
			$sub_menu_class = 'sub-menu';
		}

		$output .= "\n$indent<ul class=\"$sub_menu_class\">\n";
	}

	/**
	 * Ends the list of after the elements are added.
	 *
	 * @param string $output Passed by reference. Used to append additional content.
	 * @param int $depth Depth of menu item. Used for padding.
	 * @param mixed $args An array of arguments. @see wp_nav_menu().
	 *
	 * @since 3.0.0
	 *
	 * @see   Walker::end_lvl()
	 */
	public function end_lvl( &$output, $depth = 0, $args = array() ) {
		$menu_item       = $this->curItem;
		$penci_menu_type = get_post_meta( $menu_item->ID, 'penci_menu_type', true );
		$indent          = str_repeat( "\t", $depth );
		$output          .= "$indent</ul>\n";

		if ( 0 === $depth && 'mega-menu' == $penci_menu_type ) {
			$output .= "$indent</div>\n";
			$output .= "$indent</div>\n";
		}
	}

	function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
		$this->curItem = $item;

		$indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';

		$class_names = $value = '';

		$classes   = empty( $item->classes ) ? array() : (array) $item->classes;
		$classes[] = 'menu-item-' . $item->ID;

		/**
		 * Filter the CSS classes applied to a menu items
		 *
		 * @param array $classes The CSS classes that are applied to the menu items
		 * @param object $item Current menu item
		 * @param array $args Array of arguments
		 *
		 * @since 1.0
		 *
		 */
		$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args, $depth ) );
		$class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

		/**
		 * Filter the ID applied to a menu items
		 *
		 * @param string       ID that is applied to the menu items
		 * @param object $item Current menu item.
		 * @param array $args Array of arguments
		 *
		 * @since 1.0
		 *
		 */
		$id = apply_filters( 'nav_menu_item_id', 'menu-item-' . $item->ID, $item, $args );
		$id = $id ? ' id="' . esc_attr( $id ) . '"' : '';

		$output .= $indent . '<li' . $id . $value . $class_names . '>';

		$atts           = array();
		$atts['title']  = ! empty( $item->attr_title ) ? $item->attr_title : '';
		$atts['target'] = ! empty( $item->target ) ? $item->target : '';
		$atts['rel']    = ! empty( $item->xfn ) ? $item->xfn : '';
		$atts['href']   = ! empty( $item->url ) ? $item->url : '';

		/**
		 * Filter the HTML attributes applied to a menu items
		 *
		 * @param array $atts
		 * @param object $item The current menu item.
		 * @param array $args An array of arguments
		 *
		 * @since 1.0
		 *
		 */
		$atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args, $depth );

		$attributes = '';
		foreach ( $atts as $attr => $value ) {
			if ( ! empty( $value ) ) {
				$value      = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
				$attributes .= ' ' . $attr . '="' . $value . '"';
			}
		}

		$item_output = $args->before;

		if ( $item->is_mega_menu == false ) {
			$item_output .= '<a' . $attributes . '>';
		}

		$item_title = $item->is_mega_menu ? $item->title : apply_filters( 'the_title', $item->title, $item->ID );

		$item_output .= $args->link_before . $item_title . $args->link_after;

		if ( $item->is_mega_menu == false ) {
			$item_output .= '</a>';
		}
		$item_output .= $args->after;

		/**
		 * Filter a menu item's starting output
		 *
		 * @param string $item_output The menu items starting HTML output
		 * @param object $item Menu item data object
		 * @param int $depth Depth of menu item
		 * @param array $args Array of arguments
		 *
		 * @since 1.0
		 *
		 */
		$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
	}

	function end_el( &$output, $item, $depth = 0, $args = array() ) {
		if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
			$t = '';
			$n = '';
		} else {
			$t = "\t";
			$n = "\n";
		}
		$output .= "</li>{$n}";

		$item_parent = $item->menu_item_parent ? $item->menu_item_parent : '0';

		if ( '0' === $item_parent ) {
			$this->count_item_parent ++;
		}
	}
}
