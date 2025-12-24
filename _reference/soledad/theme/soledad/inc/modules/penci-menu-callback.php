<?php

/**
 * Class penci_nav_menu_edit_walker
 * Callback of penci_nav_menu_edit_walker function in penci-walker.php
 * Use to filter to wp_edit_nav_menu_walker
 *
 * @since 1.0
 */
class penci_nav_menu_edit_walker extends Walker_Nav_Menu_Edit {
	public function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
		$menu_return  = '';
		$menu_control = '';
		$style        = "font-family: 'Open Sans', sans-serif;";

		// Menu setting.
		$penci_cat_mega_menu      = get_post_meta( $item->ID, 'penci_cat_mega_menu', true );
		$penci_number_mega_menu   = get_post_meta( $item->ID, 'penci_number_mega_menu', true );
		$penci_style_mega_menu    = get_post_meta( $item->ID, 'penci_style_mega_menu', true );
		$penci_position_mega_menu = get_post_meta( $item->ID, 'penci_position_mega_menu', true );
		$penci_menu_pos           = get_post_meta( $item->ID, 'penci_menu_pos', true );
		$penci_menu_type          = get_post_meta( $item->ID, 'penci_menu_type', true );
		$penci_menu_load          = get_post_meta( $item->ID, 'penci_menu_load', true );
		$penci_menu_bgimg         = get_post_meta( $item->ID, 'penci_menu_bgimg', true );
		$penci_menu_block         = get_post_meta( $item->ID, 'penci_menu_block', true );
		$penci_menu_mw            = get_post_meta( $item->ID, 'penci_menu_mw', true );
		$penci_menu_mh            = get_post_meta( $item->ID, 'penci_menu_mh', true );
		$penci_menu_bgcl          = get_post_meta( $item->ID, 'penci_menu_bgcl', true );
		$penci_menu_lbtxt         = get_post_meta( $item->ID, 'penci_menu_lbtxt', true );
		$penci_menu_lbs           = get_post_meta( $item->ID, 'penci_menu_lbs', true );
		$penci_menu_lbbg          = get_post_meta( $item->ID, 'penci_menu_lbbg', true );
		$penci_menu_lbcl          = get_post_meta( $item->ID, 'penci_menu_lbcl', true );
		$penci_menu_anchor        = get_post_meta( $item->ID, 'penci_menu_anchor', true );
		$penci_menu_visible       = get_post_meta( $item->ID, 'penci_menu_visible', true );
		// Add option choose mega menu.
		$td_category_tree = array_merge(
			array(
				'- Not Mega Menu -' => '',
			),
			penci_list_categories()
		);

		$penci_block_list = penci_builder_block_list();

		$menu_control .= '<p class="description description-wide penci-soledad-description penci-hide-child">';
		$menu_control .= '<label>Mega Menu Type</label>';
		$menu_control .= '<select style="' . $style . '" name="penci_menu_type[' . $item->ID . ']" id="" class="widefat code edit-menu-item-mgstyle">';
		$menu_control .= '<option value=""' . selected( $penci_menu_type, '', false ) . '>Default</option>';
		$menu_control .= '<option value="mega-menu"' . selected( $penci_menu_type, 'mega-menu', false ) . '>Mega Menu Builder</option>';
		$menu_control .= ' </select>';
		$menu_control .= '</p>';

		$menu_control .= '<p class="description penci-mn-settings mega-menu-select description-wide penci-soledad-description penci-hide-child">';
		$menu_control .= '<label>Select Penci Block</label>';
		$menu_control .= '<select style="' . $style . '" name="penci_menu_block[' . $item->ID . ']" id="" class="pcblock-select widefat code edit-menu-item-url">';
		unset( $penci_block_list[''] );
		$menu_control .= '<option data-id="" value="">' . __( 'None', 'soledad' ) . '</option>';
		foreach ( $penci_block_list as $block_slug => $block_name ) {
			$block_id      = get_page_by_path( $block_slug, OBJECT, 'penci-block' );
			$block_id      = isset( $block_id->ID ) && $block_id->ID ? $block_id->ID : '';
			$menu_control .= '<option data-id="' . $block_id . '" value="' . $block_slug . '"' . selected( $block_slug, $penci_menu_block, false ) . '>' . $block_name . ' (ID: ' . $block_id . ')</option>';
		}
		$menu_control .= ' </select>';

		$penci_menu_block_id = 'blockid';
		if ( $penci_menu_block ) {
			$penci_menu_block_id = get_page_by_path( $penci_menu_block, OBJECT, 'penci-block' );
			$penci_menu_block_id = isset( $penci_menu_block_id->ID ) && $penci_menu_block_id->ID ? $penci_menu_block_id->ID : '';
		}
		$menu_control .= '<span class="description pcajax-change"><a target="_blank" class="penciblock-edit-link" data-url="' . esc_url( admin_url( 'post.php?action=edit&post=' ) ) . '" href="' . esc_url( admin_url( 'post.php?action=edit&post=' . $penci_menu_block_id . '' ) ) . '">Edit This Block</a> | <a target="_blank" href="' . esc_url( admin_url( 'edit.php?post_type=penci-block' ) ) . '">' . __( 'Add New Block', 'soledad' ) . '</a></span>';
		$menu_control .= '<span class="description pcajax-none">' . __( 'You can add new a Penci Block on <a target="_blank" href="' . esc_url( admin_url( 'edit.php?post_type=penci-block' ) ) . '">this page</a>', 'soledad' ) . '</span>';

		$menu_control .= '</p>';

		$menu_control .= '<p class="description penci-mn-settings mega-menu-select description-wide penci-soledad-description penci-hide-child">';
		$menu_control .= '<label>Mega Menu Position</label>';
		$menu_control .= '<select style="' . $style . '" name="penci_menu_pos[' . $item->ID . ']" id="" class="widefat code edit-menu-item-url">';
		$menu_control .= '<option value="flexible"' . selected( $penci_menu_pos, 'flexible', false ) . '>Flexible</option>';
		$menu_control .= '<option value="center"' . selected( $penci_menu_pos, 'center', false ) . '>Center</option>';
		$menu_control .= ' </select>';
		$menu_control .= '</p>';

		$menu_control .= '<p class="description penci-mn-settings mega-menu-select description-wide penci-soledad-description penci-hide-child">';
		$menu_control .= '<label>Maxium Width: (Eg: 100% or 100px)<br>If you want this mega menu to display with full 100% width to the edge of the browser - fill: &nbsp;&nbsp;<strong>full</strong></label>';
		$menu_control .= '<input value="' . $penci_menu_mw . '" type="text" style="' . $style . '" name="penci_menu_mw[' . $item->ID . ']" id="" class="widefat code edit-menu-item-url">';
		$menu_control .= '</p>';

		$menu_control .= '<p class="description penci-mn-settings mega-menu-select description-wide penci-soledad-description penci-hide-child">';
		$menu_control .= '<label>Min Height: (Eg: 100% or 300px)</label>';
		$menu_control .= '<input value="' . $penci_menu_mh . '" type="text" style="' . $style . '" name="penci_menu_mh[' . $item->ID . ']" id="" class="widefat code edit-menu-item-url">';
		$menu_control .= '</p>';

		$menu_control .= '<p class="description penci-mn-settings mega-menu-select description-wide penci-soledad-description penci-hide-child">';
		$menu_control .= '<label>Load Penci Block Dropdown with AJAX?</label>';
		$menu_control .= '<select style="' . $style . '" name="penci_menu_load[' . $item->ID . ']" id="" class="widefat code edit-menu-item-url">';
		$menu_control .= '<option value="yes"' . selected( $penci_menu_load, 'yes', false ) . '>Yes</option>';
		$menu_control .= '<option value="no"' . selected( $penci_menu_load, 'no', false ) . '>No</option>';
		$menu_control .= ' </select>';
		$menu_control .= '</p>';

		$menu_control .= '<p class="description penci-mn-settings mega-menu-select description-wide penci-soledad-description penci-hide-child">';
		$menu_control .= '<label style="display:block">Mega Menu Background Color</label>';
		$menu_control .= '<input value="' . $penci_menu_bgcl . '" type="text" style="' . $style . '" name="penci_menu_bgcl[' . $item->ID . ']" id="penci_menu_bgcl_' . $item->ID . '" class="widefat code color-picker edit-menu-item-url">';
		$menu_control .= '</p>';

		$img_wrap_class = $data_img = '';
		$img_label      = esc_attr__( 'Upload Background Image', 'soledad' );
		if ( $penci_menu_bgimg ) {
			$data_img       = wp_get_attachment_image_src( $penci_menu_bgimg, 'thumbnail' );
			$data_img       = $data_img[0] ?? '';
			$img_wrap_class = ' active';
			$img_label      = esc_attr__( 'Change Background Image', 'soledad' );
		}

		$menu_control .= '<p data-imgid="' . esc_attr( $item->ID ) . '" class="description penci-mn-settings mega-menu-select description-wide penci-soledad-description penci-hide-child">';
		$menu_control .= '<label style="display:block">Mega Menu Background Image</label>';
		$menu_control .= '<span class="menu-mn-g">';
		$menu_control .= '<span class="penci_menu_bgimg_btn">' . $img_label . '</span>';
		$menu_control .= '<span class="penci_menu_bgimg_delete_btn' . $img_wrap_class . '">Delete</span>';
		$menu_control .= '</span>';
		$menu_control .= '<input type="hidden" class="custom_media_id widefat code edit-menu-item-url" id="penci_menu_bgimg[' . $item->ID . ']" name="penci_menu_bgimg[' . $item->ID . ']" value="' . $penci_menu_bgimg . '" />';
		$menu_control .= '<span class="pc-mn-image-wrapper' . $img_wrap_class . '"><img style="max-width:70px;height:auto;" class="custom_media_image" src="' . esc_url( $data_img ) . '" alt="" /></span>';
		$menu_control .= '</p>';

		$menu_control .= '<p class="description penci-mn-settings cat-menu-select description-wide penci-soledad-description penci-hide-child">';
		$menu_control .= '<label>';
		$menu_control .= 'Ensure that it is a category mega menu. Make sure the selected category has posts, the menu item you choose is a top-level item, and it does not have any child menu items.';
		$menu_control .= '</label>';

		$menu_control .= '<select style="' . $style . '" name="penci_cat_mega_menu[' . $item->ID . ']" id="" class="widefat code edit-menu-item-url">';
		foreach ( $td_category_tree as $category => $category_id ) {
			$menu_control .= '<option value="' . $category_id . '"' . selected( $penci_cat_mega_menu, $category_id, false ) . '>' . $category . '</option>';
		}
		$menu_control .= ' </select>';
		$menu_control .= '</p>';

		$menu_control .= '<p class="description penci-mn-settings cat-menu-select description-wide penci-soledad-description penci-hide-child">';
		$menu_control .= '<label>';
		$menu_control .= 'Choose the item style.';
		$menu_control .= '</label>';

		$menu_control .= '<select style="' . $style . '" name="penci_style_mega_menu[' . $item->ID . ']" id="" class="widefat code edit-menu-item-url">';
		$menu_control .= '<option value="1"' . selected( $penci_style_mega_menu, '1', false ) . '>' . __( 'Style 1', 'soledad' ) . '</option>';
		$menu_control .= '<option value="2"' . selected( $penci_style_mega_menu, '2', false ) . '>' . __( 'Style 2', 'soledad' ) . '</option>';
		$menu_control .= '<option value="3"' . selected( $penci_style_mega_menu, '3', false ) . '>' . __( 'Style 3', 'soledad' ) . '</option>';
		$menu_control .= '<option value="4"' . selected( $penci_style_mega_menu, '4', false ) . '>' . __( 'Style 4', 'soledad' ) . '</option>';
		$menu_control .= '<option value="5"' . selected( $penci_style_mega_menu, '5', false ) . '>' . __( 'Style 5', 'soledad' ) . '</option>';
		$menu_control .= ' </select>';
		$menu_control .= '</p>';

		$menu_control .= '<p class="description penci-mn-settings cat-menu-select description-wide penci-soledad-description penci-hide-child">';
		$menu_control .= '<label>';
		$menu_control .= 'Choose the position for the tabs';
		$menu_control .= '</label>';

		$menu_control .= '<select style="' . $style . '" name="penci_position_mega_menu[' . $item->ID . ']" id="" class="widefat code edit-menu-item-url">';
		$menu_control .= '<option value="side"' . selected( $penci_position_mega_menu, 'side', false ) . '>Side</option>';
		$menu_control .= '<option value="top"' . selected( $penci_position_mega_menu, 'top', false ) . '>Top</option>';
		$menu_control .= '<option value="bottom"' . selected( $penci_position_mega_menu, 'bottom', false ) . '>Bottom</option>';
		$menu_control .= ' </select>';
		$menu_control .= '</p>';

		$menu_control .= '<p class="description penci-mn-settings cat-menu-select description-wide penci-soledad-description penci-hide-child">';
		$menu_control .= '<label>';
		$menu_control .= 'Select the number of rows to display posts in this category mega menu.';
		$menu_control .= '</label>';

		$menu_control .= '<select style="' . $style . '" name="penci_number_mega_menu[' . $item->ID . ']" id="" class="widefat code edit-menu-item-url">';
		$menu_control .= '<option value="1"' . selected( $penci_number_mega_menu, '1', false ) . '>1 row</option>';
		$menu_control .= '<option value="2"' . selected( $penci_number_mega_menu, '2', false ) . '>2 rows</option>';
		$menu_control .= '<option value="3"' . selected( $penci_number_mega_menu, '3', false ) . '>3 rows</option>';
		$menu_control .= ' </select>';
		$menu_control .= '</p>';

		$menu_control .= '<p class="description all-menu-select description-wide penci-soledad-description penci-hide-child">';
		$menu_control .= '<label>Label Text</label>';
		$menu_control .= '<input value="' . $penci_menu_lbtxt . '" type="text" style="' . $style . '" name="penci_menu_lbtxt[' . $item->ID . ']" id="" class="widefat code edit-menu-item-url">';
		$menu_control .= '</p>';

		$menu_control .= '<p class="description all-menu-select description-wide penci-soledad-description penci-hide-child">';
		$menu_control .= '<label>Label Style</label>';
		$menu_control .= '<select style="' . $style . '" name="penci_menu_lbs[' . $item->ID . ']" id="" class="widefat code edit-menu-item-url">';
		$menu_control .= '<option value="1"' . selected( $penci_menu_lbs, '1', false ) . '>' . __( 'Style 1', 'soledad' ) . '</option>';
		$menu_control .= '<option value="2"' . selected( $penci_menu_lbs, '2', false ) . '>' . __( 'Style 2', 'soledad' ) . '</option>';
		$menu_control .= '<option value="4"' . selected( $penci_menu_lbs, '4', false ) . '>' . __( 'Style 3', 'soledad' ) . '</option>';
		$menu_control .= ' </select>';
		$menu_control .= '</p>';

		$menu_control .= '<p class="description all-menu-select description-wide penci-soledad-description penci-hide-child">';
		$menu_control .= '<label style="display:block">Label Text Color</label>';
		$menu_control .= '<input value="' . $penci_menu_lbcl . '" type="text" style="' . $style . '" name="penci_menu_lbcl[' . $item->ID . ']" id="penci_menu_lbcl_' . $item->ID . '" class="widefat code color-picker edit-menu-item-url">';
		$menu_control .= '</p>';

		$menu_control .= '<p class="description all-menu-select description-wide penci-soledad-description penci-hide-child">';
		$menu_control .= '<label style="display:block">Label Background Color</label>';
		$menu_control .= '<input value="' . $penci_menu_lbbg . '" type="text" style="' . $style . '" name="penci_menu_lbbg[' . $item->ID . ']" id="penci_menu_lbbg_' . $item->ID . '" class="widefat code color-picker edit-menu-item-url">';
		$menu_control .= '</p>';

		$menu_control .= '<p class="description all-menu-select description-wide penci-soledad-description penci-hide-child">';
		$menu_control .= '<label>Page Anchor (for one-page menu)</label>';
		$menu_control .= '<select style="' . $style . '" name="penci_menu_anchor[' . $item->ID . ']" id="" class="widefat code edit-menu-item-url">';
		$menu_control .= '<option value=""' . selected( $penci_menu_anchor, '', false ) . '>Disable</option>';
		$menu_control .= '<option value="enable"' . selected( $penci_menu_anchor, 'enable', false ) . '>Enable</option>';
		$menu_control .= ' </select>';
		$menu_control .= '</p>';
		
		$menu_control .= '<p class="description all-menu-select description-wide penci-soledad-description">';
		$menu_control .= '<label>Show this menu for</label>';
		$menu_control .= '<select style="' . $style . '" name="penci_menu_visible[' . $item->ID . ']" id="" class="widefat code edit-menu-item-url">';
		$menu_control .= '<option value=""' . selected( $penci_menu_visible, '', false ) . '>All Users</option>';
		$menu_control .= '<option value="loggedin"' . selected( $penci_menu_visible, 'loggedin', false ) . '>Logged In Users</option>';
		$menu_control .= '<option value="visitor"' . selected( $penci_menu_visible, 'visitor', false ) . '>Non-Logged In Users</option>';
		$menu_control .= ' </select>';
		$menu_control .= '</p>';

		parent::start_el( $menu_return, $item, $depth, $args, $id );

		$menu_return = preg_replace( '/(?=<div.*submitbox)/', $menu_control, $menu_return );

		$output .= $menu_return;
	}
}
