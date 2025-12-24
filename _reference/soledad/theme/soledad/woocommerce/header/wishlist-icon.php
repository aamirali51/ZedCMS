<?php
$wishlist_page_id = get_theme_mod( 'penci_woocommerce_wishlist_page' );
if ( $wishlist_page_id && ! get_theme_mod( 'penci_woo_shop_hide_wishlist_icon', false ) ) :
	?>
    <div id="top-header-wishlist"
         class="top-search-classes pcheader-icon wishlist-icon<?php if ( get_theme_mod( 'penci_topbar_search_check' ) ): echo ' clear-right'; endif; ?>">
        <a class="wishlist-contents" href="<?php echo esc_url( get_page_link( $wishlist_page_id ) ); ?>"
           title="<?php echo penci_woo_translate_text( 'penci_woo_trans_viewwishlist' ); ?>">

            <i class="penciicon-heart"></i>
            <span><?php do_action( 'penci_current_wishlist' ); ?></span>
        </a>
    </div>
<?php endif;
