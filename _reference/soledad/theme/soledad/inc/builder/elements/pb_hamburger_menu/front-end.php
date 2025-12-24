<?php
$header_data = $args['header_data'];
?>
    <div class="pc-builder-element penci-menuhbg-wapper penci-menu-toggle-wapper">
        <a href="#" aria-label="Open Menu"
           class="penci-menuhbg-toggle builder pc-button-define-<?php echo penci_builder_validate_mod( $header_data, 'penci_header_pb_hamburger_menu_btn_style', 'customize' ); ?>">
		<span class="penci-menuhbg-inner">
			<i class="lines-button lines-button-double">
				<i class="penci-lines"></i>
			</i>
			<i class="lines-button lines-button-double penci-hover-effect">
				<i class="penci-lines"></i>
			</i>
		</span>
        </a>
    </div>
<?php
add_filter( 'theme_mod_penci_menu_hbg_show', function () {
	return true;
} );
