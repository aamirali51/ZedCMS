<?php
/**
 * @author : PenciDesign
 */

namespace SoledadFW\Customizer;

/**
 * Class Theme Soledad Customizer
 */
class WooCommerceOption extends CustomizerOptionAbstract {

	public $panelID = 'woocommerce';

	public function set_option() {
		$this->set_section();
	}

	public function set_section() {
		$this->add_lazy_section( 'pencidesign_new_section_woocommerce_section', esc_html__( 'General', 'soledad' ), $this->panelID, __('You can check <a class="wp-customizer-link" href="https://soledad.pencidesign.net/soledad-document/#create_store" target="_blank">this guide</a> to know how to configure your online store.','soledad') );
		$this->add_lazy_section( 'pencidesign_woo_product_catalog_section', esc_html__( 'Shop Settings', 'soledad' ), $this->panelID, __('You can check <a class="wp-customizer-link" href="https://soledad.pencidesign.net/soledad-document/#create_store" target="_blank">this guide</a> to know how to configure your online store.','soledad') );
		$this->add_lazy_section( 'pencidesign_woo_wishlist_settings_section', esc_html__( 'Product Wishlist', 'soledad' ), $this->panelID );
		$this->add_lazy_section( 'pencidesign_woo_compare_settings_section', esc_html__( 'Product Compare', 'soledad' ), $this->panelID );
		$this->add_lazy_section( 'pencidesign_woo_quickview_settings_section', esc_html__( 'Product Quickview', 'soledad' ), $this->panelID );
		$this->add_lazy_section( 'pencidesign_woo_label_settings_section', esc_html__( 'Product Label', 'soledad' ), $this->panelID );
		$this->add_lazy_section( 'pencidesign_woo_brand_settings_section', esc_html__( 'Product Brands', 'soledad' ), $this->panelID );
		$this->add_lazy_section( 'pencidesign_woo_single_settings_section', esc_html__( 'Single Product', 'soledad' ), $this->panelID );
		$this->add_lazy_section( 'pencidesign_woo_cart_page_section', esc_html__( 'Cart Page', 'soledad' ), $this->panelID );
		$this->add_lazy_section( 'pencidesign_woo_ordercompleted_page_section', esc_html__( 'Order Completed Page', 'soledad' ), $this->panelID );
		$this->add_lazy_section( 'pencidesign_woo_mobile_settings_section', esc_html__( 'Mobile Settings', 'soledad' ), $this->panelID );
		$this->add_lazy_section( 'pencidesign_woo_toast_notify_section', esc_html__( 'Toast Notification', 'soledad' ), $this->panelID );
		$this->add_lazy_section( 'pencidesign_woo_filter_sidebar_section', esc_html__( 'Sidebar Filter', 'soledad' ), $this->panelID );
		$this->add_lazy_section( 'pencidesign_woo_typo_settings_section', esc_html__( 'Font Size', 'soledad' ), $this->panelID );
		$this->add_lazy_section( 'pencidesign_woo_colors_settings_section', esc_html__( 'Colors', 'soledad' ), $this->panelID );
	}
}
