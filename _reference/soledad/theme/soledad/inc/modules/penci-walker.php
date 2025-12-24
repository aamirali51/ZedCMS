<?php

/**
 * Class penci_main_menu
 * Hook to create options mega menu on Appearance > Menu
 * Render content mega menu if mega menu is selected
 * We hook only for categories
 *
 * @since     1.0
 * @construct hook
 *            - action wp_update_nav_menu_item
 *            - filter wp_edit_nav_menu_walker
 *            - filter wp_nav_menu_objects
 */
class penci_main_menu {

	function __construct() {
		if ( is_admin() ) {
			add_action( 'wp_update_nav_menu_item', array( $this, 'penci_wp_update_nav_menu_item' ), 10, 3 );
			add_filter( 'wp_edit_nav_menu_walker', array( $this, 'penci_wp_edit_nav_menu_walker' ) );
		}
		add_filter( 'wp_nav_menu_objects', array( $this, 'hook_wp_nav_menu_objects' ), 10, 2 );
	}

	function penci_wp_edit_nav_menu_walker() {
		include_once trailingslashit( PENCI_SOLEDAD_DIR ) . 'inc/modules/penci-menu-callback.php';

		return 'penci_nav_menu_edit_walker';
	}

	function penci_wp_update_nav_menu_item( $menu_id, $menu_item_id, $args ) {

		$update_list = array(
			'penci_cat_mega_menu',
			'penci_number_mega_menu',
			'penci_style_mega_menu',
			'penci_position_mega_menu',
			'penci_menu_pos',
			'penci_menu_type',
			'penci_menu_bgimg',
			'penci_menu_block',
			'penci_menu_load',
			'penci_menu_mw',
			'penci_menu_mh',
			'penci_menu_bgcl',
			'penci_menu_lbtxt',
			'penci_menu_lbs',
			'penci_menu_lbbg',
			'penci_menu_lbcl',
			'penci_menu_anchor',
			'penci_menu_visible',
		);

		foreach ( $update_list as $list ) {
			if ( isset( $_POST[ $list ][ $menu_item_id ] ) ) {
				update_post_meta( $menu_item_id, $list, $_POST[ $list ][ $menu_item_id ] );
			}
		}
	}

	function hook_wp_nav_menu_objects( $items, $args ) {
		$menu_items = array();
		$remove_ids = [];

		$main_menu_name = isset( $args->menu ) ? $args->menu : '';
		$main_menu_name = is_object( $main_menu_name ) ? $main_menu_name->slug : $main_menu_name;

		$item_position = 0;
		$ajax_support  = get_theme_mod( 'penci_megamenu_ajax_cat', true );

		foreach ( $items as $item ) {
			// Get post meta value for the menu item
			$penci_menu_visible = get_post_meta( $item->ID, 'penci_menu_visible', true );

			if ( $penci_menu_visible == 'loggedin' && ! is_user_logged_in() ) {
				$remove_ids[] = $item->ID;
			} elseif ( $penci_menu_visible == 'visitor' && is_user_logged_in() ) {
				$remove_ids[] = $item->ID;
			}
		}

		foreach ( $items as $key => &$item ) {
			if ( in_array( $item->ID, $remove_ids ) || in_array( $item->menu_item_parent, $remove_ids ) ) {
				unset( $items[ $key ] ); // Remove item if it's in the list or its parent is in the list
			}
		}

		foreach ( $items as $key => &$item ) {
			$item->is_mega_menu = false;

			// if menu is mega menu, render mega menu.
			$penci_menu_type          = get_post_meta( $item->ID, 'penci_menu_type', true );
			$penci_menu_pos           = get_post_meta( $item->ID, 'penci_menu_pos', true );
			$penci_menu_load          = get_post_meta( $item->ID, 'penci_menu_load', true );
			$penci_menu_block         = get_post_meta( $item->ID, 'penci_menu_block', true );
			$penci_catid_mega_menu    = get_post_meta( $item->ID, 'penci_cat_mega_menu', true );
			$penci_number_mega_menu   = get_post_meta( $item->ID, 'penci_number_mega_menu', true );
			$penci_style_mega_menu    = get_post_meta( $item->ID, 'penci_style_mega_menu', true );
			$penci_position_mega_menu = get_post_meta( $item->ID, 'penci_position_mega_menu', true );
			$penci_full_mega_menu     = get_post_meta( $item->ID, 'penci_menu_mw', true );

			$penci_catid_validate  = get_term( $penci_catid_mega_menu, 'category' );
			$penci_catid_mega_menu = $penci_catid_validate && ! is_wp_error( $penci_catid_validate ) ? $penci_catid_mega_menu : '';

			// label text.
			$penci_menu_lbtxt  = get_post_meta( $item->ID, 'penci_menu_lbtxt', true );
			$penci_menu_lbs    = get_post_meta( $item->ID, 'penci_menu_lbs', true );
			$penci_menu_lbbg   = get_post_meta( $item->ID, 'penci_menu_lbbg', true );
			$penci_menu_lbcl   = get_post_meta( $item->ID, 'penci_menu_lbcl', true );
			$penci_menu_anchor = get_post_meta( $item->ID, 'penci_menu_anchor', true );

			if ( $penci_menu_anchor ) {
				$item->classes[] = 'menu-item-has-anchor';
			}

			if ( $penci_menu_lbtxt ) {
				$item->classes[] = 'menu-item-has-label';
				$penci_menu_lbs  = $penci_menu_lbs ? $penci_menu_lbs : 1;
				$label_css       = '';
				if ( $penci_menu_lbbg ) {
					$label_css .= '.menu-item-' . $item->ID . '.menu-item-has-label > a > .menu-label{background-color:' . $penci_menu_lbbg . ';}';
					$label_css .= '.menu-item-' . $item->ID . '.menu-item-has-label > a > .menu-label:before{border-color:' . $penci_menu_lbbg . ';}';
				}
				if ( $penci_menu_lbcl ) {
					$label_css .= '.menu-item-' . $item->ID . '.menu-item-has-label > a > .menu-label{color:' . $penci_menu_lbcl . ';}';
				}
				if ( $label_css ) {
					$label_css = '<style>' . $label_css . '</style>';
				}
				$label       = '<span class="menu-label label-style-' . $penci_menu_lbs . '">' . esc_attr( $penci_menu_lbtxt ) . '</span>';
				$item->title = $label_css . $item->title . $label;
			}

			if ( 'yes' === $penci_menu_load ) {
				$item->classes[] = 'ajax-mega-menu';
			}

			if ( empty( $penci_number_mega_menu ) ) :
				$penci_number_mega_menu = '1';
			endif;
			if ( empty( $penci_style_mega_menu ) ) :
				$penci_style_mega_menu = '1';
			endif;
			if ( empty( $penci_position_mega_menu ) ) :
				$penci_position_mega_menu = 'side';
			endif;


			$parent_ID = penci_menu_parent_id( $items, $item->menu_item_parent );

			if ( ! $parent_ID && ( ( $penci_menu_type != 'mega-menu' && $penci_catid_mega_menu ) || ( $penci_menu_type == 'mega-menu' && $penci_menu_block ) ) ) {
				$penci_menu_pos  = $penci_menu_pos ? $penci_menu_pos : 'flexible';
				$item->classes[] = 'penci-megapos-' . $penci_menu_pos;
				$item->classes[] = 'penci-mega-menu';

				if ( ! $ajax_support && ( $penci_menu_type != 'mega-menu' && $penci_catid_mega_menu ) ) {
					$item->classes[] = 'pcmn-ajxd';
				}

				if ( $penci_full_mega_menu == 'full' && $penci_menu_type == 'mega-menu' ) {
					$item->classes[] = 'penci-mg-fullwidth';
				}

				if ( ! empty( $penci_menu_block ) && $penci_menu_type == 'mega-menu' ) {
					$item->classes[] = 'penci-block-mega penci-block-wrap-mega-' . $item->ID;
				}

				if ( ! empty( $penci_catid_mega_menu ) ) {
					$child_categories = get_categories(
						array(
							'parent'       => $penci_catid_mega_menu,
							'orderby'      => 'name',
							'order'        => 'ASC',
							'hide_empty'   => true,
							'hierarchical' => 1,
							'taxonomy'     => 'category',
						)
					);

					if ( ! empty( $child_categories ) ) {
						$item->classes[] = 'menu-item-has-children';
					}
				}

				$menu_items[] = $item;

				// create mega menu item
				$post                 = new stdClass();
				$post->ID             = 0;
				$post->post_author    = '';
				$post->post_date      = '';
				$post->post_date_gmt  = '';
				$post->post_password  = '';
				$post->post_type      = 'menu_penci';
				$post->post_status    = 'draft';
				$post->to_ping        = '';
				$post->pinged         = '';
				$post->comment_status = get_option( 'default_comment_status' );
				$post->ping_status    = get_option( 'default_ping_status' );
				$post->post_pingback  = get_option( 'default_pingback_flag' );
				$post->post_category  = get_option( 'default_category' );
				$post->page_template  = 'default';
				$post->post_parent    = 0;
				$post->menu_order     = 0;
				$new_item             = new WP_Post( $post );

				/*
				 * if this is mega menu
				 * set the is_mega_menu flag
				 * render content of this mega menu
				 */
				$new_item->is_mega_menu = true; // sent to the menu walkers

				$new_item->menu_item_parent = $item->ID;

				$extra_class = $penci_menu_type == 'mega-menu' ? 'penci-block-mega' : 'penci-megamenu normal-cat-menu';

				$menu_attr = $penci_menu_type !== 'mega-menu' && $penci_catid_mega_menu && $ajax_support ? ' data-id="' . wp_create_nonce( 'penci-load-mega' ) . '" data-menu="' . $main_menu_name . '" data-item="' . $item_position . '" data-catid="' . $penci_catid_mega_menu . '" data-number="' . $penci_number_mega_menu . '" data-style="' . $penci_style_mega_menu . '" data-position="' . $penci_position_mega_menu . '"' : '';

				$new_item->url   = '';
				$new_item->title = '';
				$new_item->title .= '<div class="' . $extra_class . ' penc-menu-' . $item->ID . '"' . $menu_attr . '>';

				if ( $penci_menu_type !== 'mega-menu' && $penci_catid_mega_menu && ! $ajax_support ) {
					$new_item->title .= penci_return_html_mega_menu( $penci_catid_mega_menu, $penci_number_mega_menu, $penci_style_mega_menu, $penci_position_mega_menu, $items, $item );
				}

				if ( $penci_menu_type == 'mega-menu' && ! penci_is_mobile() ) {
					$new_item->title .= penci_return_html_block_menu( $penci_menu_block, $item->ID );
				}

				$new_item->title .= '</div>';

				$menu_items[] = $new_item;

			} elseif ( $parent_ID && get_post_meta( $parent_ID, 'penci_cat_mega_menu', true ) ) {

				$menu_items[] = '';
			} elseif ( $parent_ID && get_post_meta( $parent_ID, 'penci_menu_type', true ) == 'mega-menu' ) {
				$menu_items[] = '';
			} else {
				$menu_items[] = $item;
			}
			++ $item_position;
		} //end foreach

		return $menu_items;
	}
}

new penci_main_menu();

/**
 * Class penci_menu_walker_nav_menu
 * This class will remove wrap </a> around mega menu
 * Callback on wp_nav_menu() in header.php file
 *
 * @since 1.0
 */
class penci_menu_walker_nav_menu extends Walker_Nav_Menu {
	private $logo_break_point = null;
	private $count_item_parent = 0;
	private $curItem;
	private $child_menu_items = array();

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

			$classes .= $penci_menu_mw && $penci_menu_mw != 'full' ? ' penci-mega-custom-width' : ' penci-mega-full-width';
			$classes .= empty( $penci_menu_mw ) ? ' penci-mega-default-width' : ' penci-mega-has-width';

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

		$header_style = penci_soledad_get_header_layout();
		if ( in_array( $header_style, array( 'header-10', 'header-11' ) ) && ! isset( $this->logo_break_point ) ) {
			$penci_nav_menu_items = wp_get_nav_menu_items( $args->menu );
			$middle_menu_elements = 0;

			if ( is_array( $penci_nav_menu_items ) || is_object( $penci_nav_menu_items ) ) {
				foreach ( $penci_nav_menu_items as $menu_element ) {
					$menu_item_parent = isset( $menu_element->menu_item_parent ) && $menu_element->menu_item_parent ? $menu_element->menu_item_parent : '0';
					if ( '0' === $menu_item_parent ) {
						++ $middle_menu_elements;
					}
				}
			}
			$top_level_menu_items_count = $middle_menu_elements;
			if ( 0 === $top_level_menu_items_count ) {
				$this->logo_break_point = $middle_menu_elements / 2;
			} else {
				$this->logo_break_point = ceil( $middle_menu_elements / 2 );
			}
		}

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

		$item_output .= $args->link_before . apply_filters( 'the_title', $item->title, $item->ID ) . $args->link_after;

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
			++ $this->count_item_parent;
		}

		$header_style = penci_soledad_get_header_layout();
		$break_point  = $this->logo_break_point;

		if ( in_array(
			     $header_style,
			     array(
				     'header-10',
				     'header-11',
			     )
		     ) && '0' === $item_parent && $break_point == $this->count_item_parent && ! penci_check_theme_mod() ) {

			if ( isset( $args->menu_id ) && $args->menu_id ) {
				$wrap_id = $args->menu_id;
			} elseif ( isset( $args->menu ) && $args->menu ) {
				$menu    = $args->menu;
				$wrap_id = 'menu-' . ( isset( $menu->slug ) ? $menu->slug : '' ) . '-second';
			} else {
				$wrap_id = 'menu-second';
			}

			$wrap_class = isset( $args->menu_class ) ? str_replace( 'pencimn-left', 'pencimn-right', $args->menu_class ) : 'pencimn-right';

			$output .= '</ul>';

			ob_start();
			get_template_part( 'template-parts/header/logo-has-trans' );
			$output .= ob_get_clean();

			$output .= sprintf( '<ul id="%s" class="%s">', esc_attr( $wrap_id ), esc_attr( $wrap_class ) );

		}
	}
}
