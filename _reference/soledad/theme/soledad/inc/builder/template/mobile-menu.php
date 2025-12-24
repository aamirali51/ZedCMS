<?php
$header_data = $args['header_data'];
$position    = penci_builder_validate_mod( $header_data, 'penci_header_mobile_sidebar_position', 'left' );
$style       = penci_builder_validate_mod( $header_data, 'penci_header_mobile_sidebar_style', '' );
$style_class = $style ? ' mstyle-' . $style : '';
?>
<a href="#" aria-label="Close" class="close-mobile-menu-builder mpos-<?php echo $position . $style_class; ?>"><i
            class="penci-faicon fa fa-close"></i></a>
<div id="penci_off_canvas"
     class="penci-builder-mobile-sidebar-nav penci-menu-hbg mpos-<?php echo $position . $style_class; ?>">
    <div class="penci_mobile_wrapper">
		<?php load_template( PENCI_BUILDER_PATH . 'template/mobile-menu-content.php', true, [ 'header_data' => $header_data ] ); ?>
    </div>
</div>