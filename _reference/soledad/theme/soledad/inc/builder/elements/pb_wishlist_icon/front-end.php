<?php 
$header_data    = $args['header_data'];
$wishlist_page_id = get_theme_mod( 'penci_woocommerce_wishlist_page' );
if ( $wishlist_page_id ) :
	?>
    <div id="top-header-wishlist"
         class="pc-builder-element penci-builder-elements top-search-classes pcheader-icon wishlist-icon">
        <a class="wishlist-contents pc-button-define-<?php echo penci_builder_validate_mod( $header_data, 'penci_header_pb_wishlist_icon_section_btnstyle','customize' ); ?>" href="<?php echo esc_url( get_page_link( $wishlist_page_id ) ); ?>"
           title="<?php echo penci_woo_translate_text( 'penci_woo_trans_viewwishlist' ); ?>">

            <i class="penciicon-heart"></i>
            <span><?php do_action( 'penci_current_wishlist' ); ?></span>
        </a>
    </div>
<?php 
endif;
