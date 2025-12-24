<?php 
$header_data    = $args['header_data'];
$btn_link_target = penci_builder_validate_mod( $header_data, 'penci_header_pb_bookmark_link_target', '_self' );
$btn_title       = penci_builder_validate_mod( $header_data, 'penci_header_pb_bookmark_text_setting', false );

$classes   = [];
$classes[] = penci_builder_validate_mod( $header_data, 'penci_header_pb_bookmark_txt_css_class', 'default' );
$btn_title = ! empty( $btn_title ) ? $btn_title : '';

$pages = get_option( 'penci_bl_set_pages' );
if ( isset( $pages['subscribe_manage_page'] ) && $pages['subscribe_manage_page'] ) {
	echo '<div id="penci-header-bookmark" class="penci-header-bookmark-element penci-builder-element top-search-classes"><a title="' . penci_get_setting( 'penci_trans_bookmark' ) . '" target="' . $btn_link_target . '" href="' . esc_url( get_page_link( $pages['subscribe_manage_page'] ) ) . '">' . penci_icon_by_ver( 'fa fa-bookmark-o' ) . $btn_title . '</a></div>';
}
