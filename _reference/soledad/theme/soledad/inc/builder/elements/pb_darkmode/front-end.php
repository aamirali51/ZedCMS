<?php 
$header_data    = $args['header_data'];
$style = penci_builder_validate_mod( $header_data, 'penci_header_pb_darkmode_style', '3' );
?>
<div class="pb-header-builder pc-dmswitcher-element">
    <div class="pc_dm_mode style_<?php echo esc_attr( $style ); ?>">
        <label class="pc_dm_switch">
            <input type="checkbox" class="pc_dark_mode_toggle" aria-label="Darkmode Switcher">
            <span class="slider round"></span>
        </label>
    </div>
</div>
