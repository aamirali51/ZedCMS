<?php
if ( ! isset( $_GET['layout_id'] ) ) {
	return false;
}
$title       = get_the_title( $_GET['layout_id'] );
$preview_url = add_query_arg( [ 'view-header-layout' => $_GET['layout_id'] ], home_url() );
?>
<div class="header-builder">

    <div class="header-builder-top-header flex-row">
        <div class="device-mode flex-col flex-left">
            <div class="device-mode-desktop" data-mode="desktop">
                <span><?php esc_html_e( 'Desktop', 'soledad' ); ?></span>

            </div>
            <div class="device-mode-mobile" data-mode="mobile">
                <span><?php esc_html_e( 'Tablet / Phone', 'soledad' ); ?></span>
            </div>
        </div>
        <div class="header-builder-top-menu notice-edit flex-col flex-none">
            <div class="top-edit-screen">
                <span><?php esc_attr_e( 'You are currently editing the template:' ); ?> <strong><?php echo esc_attr( $title ); ?></strong></span>
                <a class="preview-header" target="_blank"
                   href="<?php echo esc_url( $preview_url ); ?>"><?php esc_attr_e( 'Preview' ); ?></a>
            </div>
        </div>
        <div class="header-builder-top-menu flex-col flex-right">
            <div class="nav-right">
                <div class="top-menu close">
                    <i class="fa fa-close"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="desktop-mode header-tab">
        <ul>
            <li class="normal" data-desktop-mode="normal" data-section="penci_header_desktop_option">
                <i class="fa fa-cog"></i> <?php esc_html_e( 'Normal Header', 'soledad' ); ?>
            </li>
            <li class="sticky" data-desktop-mode="sticky" data-section="penci_header_desktop_sticky">
                <i class="fa fa-cog"></i> <?php esc_html_e( 'Sticky Header', 'soledad' ); ?>
            </li>
        </ul>
    </div>


    <div class="mobile-mode header-tab">
        <ul>
            <li class="menu" data-mobile-mode="mobile_menu" data-section="penci_header_mobile_option">
                <i class="fa fa-cog"></i> <?php esc_html_e( 'Mobile Header', 'soledad' ); ?>
            </li>
            <li class="drawer" data-mobile-mode="drawer" data-section="penci_header_drawer_container">
                <i class="fa fa-cog"></i> <?php esc_html_e( 'Mobile Sidebar', 'soledad' ); ?>
            </li>
        </ul>
    </div>


    <div class="header-builder-body">

        <!-- Desktop -->
        <div class="header-builder-device header-builder-desktop" data-device="desktop">
            <div class="header-builder-wrapper">
				<?php

				$default_rows = 'topblock,top,bottom,mid, bottomblock';
				$rows         = penci_get_builder_mod( 'penci_hb_arrange_bar', $default_rows );
				$rows         = explode( ',', $rows );
				if ( is_array( $rows ) ) {
					foreach ( $rows as $row ) {
						$template->render( 'header-row', array(
							'row'      => $row,
							'template' => $template
						), true );
					}
				}
				?>
            </div>
            <div class="header-builder-list header-builder-drop-zone">
				<?php
				$elements = \HeaderBuilder::remain_desktop_header_element();
				if ( is_array( $elements ) ) {
					foreach ( $elements as $key => $value ) {
						$template->render( 'header-element', array(
							'key'   => $key,
							'value' => $value,
						), true );
					}
				}
				?>
            </div>
        </div><!-- Desktop -->

        <!-- Desktop Sticky -->
        <div class="header-builder-device header-builder-desktop-sticky" data-device="desktop_sticky">
            <div class="header-builder-wrapper">
				<?php
				$sticky_row = [ 'top', 'mid', 'bottom' ];
				foreach ( $sticky_row as $row ) {
					$template->render( 'header-sticky', array(
						'row'      => $row,
						'template' => $template
					), true );
				}
				?>
            </div>
            <div class="header-builder-list header-builder-drop-zone">
				<?php
				$elements = \HeaderBuilder::remain_sticky_header_element();
				if ( is_array( $elements ) ) {
					foreach ( $elements as $key => $value ) {
						$template->render( 'header-element', array(
							'key'   => $key,
							'value' => isset( $value['title'] ) ? $value['title'] : $value,
						), true );
					}
				}
				?>
            </div>
        </div><!-- Desktop Sticky -->

        <!-- Mobile -->
        <div class="header-builder-device header-builder-mobile" data-device="mobile">
            <div class="header-builder-wrapper">
				<?php
				$rows = array( 'top', 'mid', 'bottom' );
				foreach ( $rows as $row ) {
					$template->render( 'header-mobile-row', array(
						'row'      => $row,
						'template' => $template
					), true );
				}
				?>
            </div>
            <div class="header-builder-list header-builder-drop-zone">
				<?php
				$elements = \HeaderBuilder::remain_mobile_header_element();
				if ( is_array( $elements ) ) {
					foreach ( $elements as $key => $value ) {
						$template->render( 'header-element', array(
							'key'   => $key,
							'value' => $value
						), true );
					}
				}
				?>
            </div>
        </div><!-- Mobile -->

        <!-- Mobile Drawer -->
        <div class="header-builder-device header-builder-mobile-drawer" data-device="mobile_drawer">
            <div class="header-builder-wrapper">
				<?php
				$rows = array( 'top' );
				foreach ( $rows as $row ) {
					$template->render( 'header-mobile-drawer', array(
						'row'      => $row,
						'template' => $template
					), true );
				}
				?>
            </div>
            <div class="header-builder-list header-builder-drop-zone">
				<?php
				$elements = \HeaderBuilder::remain_mobile_drawer_element();
				if ( is_array( $elements ) ) {
					foreach ( $elements as $key => $value ) {
						$template->render( 'header-element', array(
							'key'   => $key,
							'value' => $value,
						), true );
					}
				}
				?>
            </div>
        </div><!-- Drawer -->

    </div>
</div>
