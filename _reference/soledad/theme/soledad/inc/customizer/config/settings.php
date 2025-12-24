<?php
/**
 * @author : PenciDesign
 */

namespace SoledadFW;

/**
 * Class Theme Soledad Customizer
 */
class Customizer {
	/**
	 * @var Customizer
	 */
	private static $instance;

	/**
	 * Package name
	 *
	 * @var string
	 */
	private static $package = 'Soledad';

	/**
	 * @var Customizer
	 */
	private $customizer = null;

	/**
	 * Section of Customizer
	 */
	private $list_section = array(
		'penci_ageverify_section',
		'penci_menu_hbg_section',
		'penci_menu_hbg_widgets_section',
		'penci_popup_section_display_section',
		'penci_popup_section_general_section',
		'penci_popup_section_styles_section',
		'penci_section_footer_colors_section',
		'penci_section_footer_general_section',
		'penci_section_footer_instagram_section',
		'penci_section_footer_signupform_section',
		'penci_section_footer_widgets_section',
		'penci_section_fslider_colors_section',
		'penci_section_fslider_fsize_section',
		'penci_section_fslider_general_section',
		'penci_section_header_signupform_section',
		'penci_section_homepage_colors_section',
		'penci_section_homepage_featured_boxes_section',
		'penci_section_homepage_featured_cat_section',
		'penci_section_homepage_fontsize_section',
		'penci_section_homepage_general_section',
		'penci_section_homepage_popular_posts_section',
		'penci_section_homepage_title_box_section',
		'penci_section_layout_colors_section',
		'penci_section_layout_fontsize_section',
		'penci_section_layout_rowsgap_section',
		'penci_section_other_layouts_section',
		'penci_section_sidebar_colors_section',
		'penci_section_sidebar_fsize_section',
		'penci_section_sidebar_general_section',
		'penci_section_spage_404_section',
		'penci_section_spage_colors_section',
		'penci_section_spage_general_section',
		'penci_section_spage_header_section',
		'penci_section_speed_css_section',
		'penci_section_speed_general_section',
		'penci_section_speed_html_section',
		'penci_section_speed_javascript_section',
		'penci_section_spost_colors_section',
		'penci_section_spost_fontsize_section',
		'penci_section_spost_general_section',
		'penci_section_spost_inline_reposts_section',
		'penci_section_select_share_section',
		'penci_section_spost_related_posts_section',
		'penci_section_standard_classic_section',
		'pencidesign_general_archive_page_section',
		'pencidesign_general_body_boxed_section',
		'pencidesign_general_colors_section',
		'pencidesign_general_colors_dark_section',
		'pencidesign_general_cursor_section',
		'pencidesign_general_lvn_section',
		'pencidesign_general_extra_section',
		'pencidesign_general_accessibility_section',
		'pencidesign_general_gdpr_section',
		'pencidesign_general_image_sizes_section',
		'pencidesign_general_schema_markup_section',
		'pencidesign_general_search_page_section',
		'pencidesign_general_social_sharing_section',
		'pencidesign_general_typography_section',
		'pencidesign_logo_header_category_megamenu_section',
		'pencidesign_logo_header_colors',
		'pencidesign_logo_header_colors_section',
		'pencidesign_logo_header_colors_trans',
		'pencidesign_logo_header_general_section',
		'pencidesign_logo_header_logo_section',
		'pencidesign_logo_header_primary_menu_section',
		'pencidesign_logo_header_verticalnav_section',
		'pencidesign_new_section_custom_css_section',
		'pencidesign_new_section_general_section',
		'pencidesign_new_section_social_section',
		'pencidesign_new_section_topbar_section',
		'pencidesign_new_section_transition_lang_section',
		'pencidesign_new_section_woocommerce_section',
		'pencidesign_section_fvideo_colors_section',
		'pencidesign_section_fvideo_general_section',
		'pencidesign_topbar_section_colors_section',
		'pencidesign_topbar_section_fontsize_section',
		'penci_section_spost_comments_section',
		'penci_section_spost_autoload_section',
		'penci_section_spost_cpost_meta_section',
		'penci_section_spost_cptype_builder_section',
		'penci_section_spost_pformats_builder_section',
		'penci_menu_hbg_colors_section',
		'pencidesign_logo_header_slogan_section',
		'pencidesign_logo_header_colors_trans_section',
		'penci_builder_general_section',
		'penci_footer_builder_config_section',
		'pencidesign_toc_general_section',
		'pencidesign_toc_styles_section',
		'penci_userprofile_section',
		'penci_maintenance_mode_section',
		'penci_linkmanager_section',
		'pencidesign_new_section_adblocker_section',
		'pencidesign_new_section_helpful_articles_section',
		'pencidesign_new_section_push_notification_section',
		'pencidesign_posts_page_settings_section',
		'penci_section_spost_reading_progress_section',
		'penci_epopup_section_general_section',
		'penci_epopup_section_styles_section',
	);

	/**
	 * Construct
	 */
	private function __construct() {
		// Need to load Customizer early for this kind of request.
		if ( isset( $_REQUEST['action'] ) && 'penci_fw_customizer_disable_panel' === $_REQUEST['action'] ) {
			$this->customizer = penci_fw_customizer();
		}

		if ( class_exists( 'woocommerce' ) ) {
			$woocommerce_section = [
				'pencidesign_woo_brand_settings_section',
				'pencidesign_woo_cart_page_section',
				'pencidesign_woo_colors_settings_section',
				'pencidesign_woo_compare_settings_section',
				'pencidesign_woo_filter_sidebar_section',
				'pencidesign_woo_label_settings_section',
				'pencidesign_woo_mobile_settings_section',
				'pencidesign_woo_ordercompleted_page_section',
				'pencidesign_woo_quickview_settings_section',
				'pencidesign_woo_section_transition_lang_section',
				'pencidesign_woo_single_settings_section',
				'pencidesign_woo_toast_notify_section',
				'pencidesign_woo_typo_settings_section',
				'pencidesign_woo_wishlist_settings_section',
				'pencidesign_woo_product_catalog_section',
				'woocommerce_checkout_section',
				'woocommerce_product_catalog_section',
			];
			$this->list_section  = array_merge( $this->list_section, $woocommerce_section );
		}

		if ( is_customize_preview() || ! is_admin() ) {
			add_filter( 'soledad_fw_customizer_get_lazy_options', array( $this, 'prepare_lazy_option' ) );
			add_filter( 'soledad_fw_register_lazy_section', array( $this, 'register_lazy_section' ) );
			add_action( 'soledad_fw_register_customizer_option', array( $this, 'load_customizer' ), 91 );
			add_filter( 'soledad_fw_customizer_add_field', array( $this, 'prepare_add_section' ) );
		}
	}

	/**
	 * @return Customizer
	 */
	public static function getInstance() {
		if ( null === static::$instance ) {
			static::$instance = new static();
		}

		return static::$instance;
	}

	public function prepare_lazy_option( $options ) {
		if ( is_array( $options ) ) {
			foreach ( $options as &$option ) {
				if ( isset( $option['type'] ) ) {
					//$option['type'] = str_replace( 'soledad-fw-', 'soledad-', $option['type'] );
				}
			}
		}

		return $options;
	}

	public function register_lazy_section( $result ) {
		$array = $this->list_section;

		$path = PENCI_SOLEDAD_DIR . '/inc/customizer/config/sections/';

		foreach ( $array as $id ) {
			$result[ $id ][] = "{$path}{$id}.php";
		}

		return $result;
	}

	public function load_customizer() {
		$this->customizer = Customizer\Customizer::get_instance();
		new Customizer\GeneralOption( $this->customizer, 1 );
		new Customizer\TopbarOption( $this->customizer, 2 );
		new Customizer\LogoHeaderOption( $this->customizer, 3 );
		new Customizer\VerticalNavHamburgeOption( $this->customizer, 4 );
		new Customizer\HomepageOption( $this->customizer, 5 );
		new Customizer\FeaturedSliderOption( $this->customizer, 6 );
		new Customizer\FeaturedVideoOption( $this->customizer, 7 );
		new Customizer\PostLayoutOption( $this->customizer, 8 );
		new Customizer\SidebarOption( $this->customizer, 9 );
		new Customizer\SinglePostOption( $this->customizer, 10 );
		new Customizer\PagesOption( $this->customizer, 11 );
		new Customizer\SocialMediaOption( $this->customizer, 11 );
		new Customizer\FooterOption( $this->customizer, 12 );
		new Customizer\SpeedOptimizationOption( $this->customizer, 12 );		
		new Customizer\TextTranslationOption( $this->customizer, 12 );
		new Customizer\TocOptions( $this->customizer, 13 );
		new Customizer\PopupOption( $this->customizer, 14 );
		new Customizer\PopupEOption( $this->customizer, 14 );
		new Customizer\AdBlockerOption( $this->customizer, 15 );
		new Customizer\ArticleFeedbackOption( $this->customizer, 15 );
		new Customizer\PushNotificationOption( $this->customizer, 15 );
		new Customizer\CustomCSSOption( $this->customizer, 220 );



		if ( class_exists( 'WooCommerce' ) ) {
			new Customizer\WooCommerceOption( $this->customizer, 17 );
			new Customizer\WooTranslationOption( $this->customizer, 200 );
		}

	}
}
