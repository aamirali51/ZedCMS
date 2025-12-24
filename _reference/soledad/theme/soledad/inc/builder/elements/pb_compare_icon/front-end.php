<?php 
$header_data    = $args['header_data'];
$compare_page_id = get_theme_mod( 'penci_woocommerce_compare_page' );
if ( $compare_page_id ):
	?>
    <div id="top-header-compare"
         class="top-search-classes penci-builder-elements pcheader-icon compare-icon">
        <a href="<?php echo esc_url( get_page_link( $compare_page_id ) ); ?>"
           class="pc-button-define-<?php echo penci_builder_validate_mod( $header_data, 'penci_header_pb_compare_icon_section_btnstyle','customize' ); ?> compare-contents"
           title="<?php echo penci_woo_translate_text( 'penci_woo_trans_viewcompare' ); ?>">

            <i class="penciicon-exchange-2"></i>
            <span><?php do_action( 'penci_current_compare' ); ?></span>
        </a>
    </div>
<?php 
endif;
