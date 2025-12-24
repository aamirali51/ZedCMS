<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Penci_Ad_Settings' ) ) :
	class Penci_Ad_Settings {

		static $list_panel = array(
			'header'         => 'Header',
			'homepage'       => 'Homepage',
			'featuredslider' => 'Featured Slider',
			'archive'        => 'Archive Pages',
			'single'         => 'Single Posts',
			'fltad'    		 => 'Float Banner Left/Right',
			'footer'         => 'Footer',
		);

		public function __construct() {
			add_filter( 'mb_settings_pages', array( $this, 'settings_pages' ) );
			add_filter( 'rwmb_meta_boxes', array( $this, 'header_option' ) );
			add_filter( 'rwmb_meta_boxes', array( $this, 'homepage_option' ) );
			add_filter( 'rwmb_meta_boxes', array( $this, 'featured_slider_option' ) );
			add_filter( 'rwmb_meta_boxes', array( $this, 'single_option' ) );
			add_filter( 'rwmb_meta_boxes', array( $this, 'archive_option' ) );
			add_filter( 'rwmb_meta_boxes', array( $this, 'footer_option' ) );
			add_filter( 'rwmb_meta_boxes', array( $this, 'floatads_option' ) );
			add_action( 'init', array( $this, 'mobile_ads' ) );
		}

		function settings_pages( $settings_pages ) {
			$list_social_media = self::$list_panel;
			$theme_slug        = get_option( 'stylesheet' );

			$settings_pages[] = array(
				'id'          => 'penci_ad_settings',
				'option_name' => 'theme_mods_' . $theme_slug,
				'menu_title'  => 'Ads Manager',
				'parent'      => 'soledad_dashboard_welcome',
				'style'       => 'no-boxes',
				'tab_style'   => 'left',
				'class'       => 'penci_ad_settings',
				'columns'     => 1,
				'tabs'        => $list_social_media,
			);

			return $settings_pages;
		}

		function header_option( $meta_boxes ) {
			$meta_boxes[] = array(
				'id'             => 'header',
				'title'          => 'Header Ads',
				'settings_pages' => 'penci_ad_settings',
				'tab'            => 'header',

				'fields' => array(
					array(
						'id'                => 'penci_header_3_adsense',
						'type'              => 'textarea',
						'name'              => esc_html__( 'Google adsense/custom HTML code to display in header 3', 'soledad' ),
						'std'               => '',
						'sanitize_callback' => 'none',
					),
					array(
						'id'   => 'penci_header_3_banner',
						'type' => 'file_input',
						'name' => esc_html__( 'Banner Header Right For Header 3', 'soledad' ),
						'std'  => '',
					),
					array(
						'id'   => 'penci_header_3_banner_url',
						'type' => 'url',
						'name' => esc_html__( 'Link To Go When Click on Banner of Header 3 ', 'soledad' ),
						'std'  => '',
					),
					array(
						'id'                => 'penci_custom_code_inside_head_tag',
						'type'              => 'textarea',
						'name'              => esc_html__( 'Add Custom Code Inside <head> tag', 'soledad' ),
						'std'               => '',
						'sanitize_callback' => 'none',
					),
					array(
						'id'                => 'penci_custom_code_after_body_tag',
						'type'              => 'textarea',
						'name'              => esc_html__( 'Add Custom Code After <body> tag', 'soledad' ),
						'std'               => '',
						'sanitize_callback' => 'none',
						'mobile'            => true,
					),
					array(
						'id'                => 'penci_custom_code_after_header_tag',
						'type'              => 'textarea',
						'name'              => esc_html__( 'Add Custom Code After Header Wrap', 'soledad' ),
						'std'               => '',
						'sanitize_callback' => 'none',
						'mobile'            => true,
					),
				),
			);

			return $meta_boxes;
		}

		function homepage_option( $meta_boxes ) {
			$meta_boxes[] = array(
				'id'             => 'homepage',
				'title'          => 'Homepage Ads',
				'settings_pages' => 'penci_ad_settings',
				'tab'            => 'homepage',

				'fields' => array(
					array(
						'id'   => 'penci_homepage_desc',
						'type' => 'custom_html',
						'std'  => __( 'The options here just apply when you use Customizer to set up the homepage - check more all ways to set up the homepage <a href="https://soledad.pencidesign.net/soledad-document/#homepage">here</a>', 'soledad' ),
					),
					array(
						'id'   => 'penci_infeedads_home_num',
						'type' => 'number',
						'name' => esc_html__( 'Insert In-feed Ads Code After Every How Many Posts?', 'soledad' ),
						'std'  => '3',
					),
					array(
						'id'                => 'penci_infeedads_home_code',
						'type'              => 'textarea',
						'sanitize_callback' => 'none',
						'mobile'            => true,
						'name'              => esc_html__( 'In-feed Ads Code/HTML', 'soledad' ),
						'desc'              => esc_html__( 'Please use normal responsive ads here to get the best results - the in-feed ads can\'t work with auto-ads because auto-ads will randomly place your ads on random places on the pages.', 'soledad' ),
					),
					array(
						'id'      => 'penci_infeedads_home_layout',
						'type'    => 'select',
						'name'    => esc_html__( 'In-feed Ads Layout Type', 'soledad' ),
						'options' => array(
							''     => esc_html__( 'Follow Current Layout', 'soledad' ),
							'full' => esc_html__( 'Full Width', 'soledad' ),
						),
					),
				),
			);

			return $meta_boxes;
		}

		function featured_slider_option( $meta_boxes ) {
			$meta_boxes[] = array(
				'id'             => 'featuredslider',
				'title'          => 'Featured Slider Ads',
				'settings_pages' => 'penci_ad_settings',
				'tab'            => 'featuredslider',

				'fields' => array(
					array(
						'id'   => 'penci_featureslider_desc',
						'type' => 'custom_html',
						'std'  => __( 'The options here just apply when you use Customizer to set up the homepage - check more all ways to set up the homepage <a href="https://soledad.pencidesign.net/soledad-document/#homepage">here</a>', 'soledad' ),
					),
					array(
						'id'                => 'penci_home_adsense_below_slider',
						'type'              => 'textarea',
						'sanitize_callback' => 'none',
						'mobile'            => true,
						'name'              => esc_html__( 'Add Google Adsense/Custom HTML Code Below Featured Slider ', 'soledad' ),
					),
				),
			);

			return $meta_boxes;
		}

		function single_option( $meta_boxes ) {
			$meta_boxes[] = array(
				'id'             => 'single',
				'title'          => 'Single Posts Ads',
				'settings_pages' => 'penci_ad_settings',
				'tab'            => 'single',
				'fields'         => array(
					array(
						'id'                => 'penci_ads_inside_content_html',
						'type'              => 'textarea',
						'sanitize_callback' => 'none',
						'mobile'            => true,
						'name'              => esc_html__( 'Add Ads/Custom HTML Code Inside Posts Content', 'soledad' ),
					),
					array(
						'id'      => 'penci_ads_inside_content_style',
						'type'    => 'select',
						'name'    => esc_html__( 'Add Ads/Custom HTML Code Inside Posts Content With:', 'soledad' ),
						'options' => array(
							'style-1' => __( 'After Each X Paragraphs - Repeat', 'soledad' ),
							'style-2' => __( 'After X Paragraphs - No Repeat', 'soledad' ),
						),
					),
					array(
						'id'   => 'penci_ads_inside_content_num',
						'type' => 'number',
						'name' => esc_html__( 'Add Ads/Custom HTML Code Inside Posts Content After How Many Paragraphs?', 'soledad' ),
						'std'  => 4,
					),
					array(
						'id'                => 'penci_post_adsense_single10',
						'type'              => 'textarea',
						'sanitize_callback' => 'none',
						'mobile'            => true,
						'name'              => esc_html__( 'Add Google Adsense/Custom HTML code For Post Template Style 10', 'soledad' ),
					),
					array(
						'id'                => 'penci_post_adsense_one',
						'type'              => 'textarea',
						'sanitize_callback' => 'none',
						'mobile'            => true,
						'name'              => esc_html__( 'Add Google Adsense/Custom HTML code below post description', 'soledad' ),
					),
					array(
						'id'                => 'penci_post_adsense_two',
						'type'              => 'textarea',
						'sanitize_callback' => 'none',
						'mobile'            => true,
						'name'              => esc_html__( 'Add Google Adsense/Custom HTML code at the end of content posts', 'soledad' ),
					),
					array(
						'desc'              => __( 'If you use Google Ads here, please use normal Google Ads here - don\'t use Google Auto Ads to get it appears in the correct place.', 'soledad' ),
						'id'                => 'penci_loadnp_ads',
						'type'              => 'textarea',
						'sanitize_callback' => 'none',
						'mobile'            => true,
						'name'              => esc_html__( 'Add Google Adsense/Custom HTML code Between Posts When Activate The Infinity Scrolling Load Posts', 'soledad' ),
					),
				),
			);

			return $meta_boxes;
		}

		function footer_option( $meta_boxes ) {
			$meta_boxes[] = array(
				'id'             => 'footer',
				'title'          => 'Footer Ads',
				'settings_pages' => 'penci_ad_settings',
				'tab'            => 'footer',

				'fields' => array(
					array(
						'id'                => 'penci_footer_adsense',
						'type'              => 'textarea',
						'sanitize_callback' => 'none',
						'mobile'            => true,
						'name'              => esc_html__( 'Add Google Adsense/Custom HTML Code Above Footer', 'soledad' ),
					),
					array(
						'id'                => 'penci_footer_analytics',
						'type'              => 'textarea',
						'sanitize_callback' => 'none',
						'mobile'            => true,
						'name'              => esc_html__( 'Add Custom HTML code before close </body> tag / Google Analytics Code', 'soledad' ),
					),
				),
			);

			return $meta_boxes;
		}

		function archive_option( $meta_boxes ) {
			$meta_boxes[] = array(
				'id'             => 'archive',
				'title'          => 'Archive Ads',
				'settings_pages' => 'penci_ad_settings',
				'tab'            => 'archive',
				'fields'         => array(
					array(
						'id'                => 'penci_arcf_adbelow',
						'type'              => 'textarea',
						'sanitize_callback' => 'none',
						'mobile'            => true,
						'name'              => esc_html__( 'Google Adsense/Custom HTML Code Display Below Featured Posts', 'soledad' ),
					),
					array(
						'id'                => 'penci_archive_ad_above',
						'desc'              => 'You can display google adsense/custom HTML code above posts on category, tags, search, archive page by use this option',
						'type'              => 'textarea',
						'mobile'            => true,
						'sanitize_callback' => 'none',
						'name'              => esc_html__( 'Google Adsense/Custom HTML Code to Display Above Posts Layout for Archive Pages ', 'soledad' ),
					),
					array(
						'id'                => 'penci_archive_ad_below',
						'desc'              => 'You can display google adsense/custom HTML code above posts on category, tags, search, archive page by use this option',
						'type'              => 'textarea',
						'sanitize_callback' => 'none',
						'mobile'            => true,
						'name'              => esc_html__( 'Google Adsense/Custom HTML Code to Display Below Posts Layout for Archive Pages', 'soledad' ),
					),
					array(
						'id'   => 'penci_infeedads_archi_num',
						'type' => 'number',
						'name' => esc_html__( 'Insert In-feed Ads Code After Every How Many Posts?', 'soledad' ),
					),
					array(
						'id'                => 'penci_infeedads_archi_code',
						'type'              => 'textarea',
						'sanitize_callback' => 'none',
						'mobile'            => true,
						'desc'              => __( 'Please use normal responsive ads here to get the best results - the in-feed ads can\'t work with auto-ads because auto-ads will randomly place your ads on random places on the pages.', 'soledad' ),
						'name'              => esc_html__( 'In-feed Ads Code/HTML', 'soledad' ),
					),
					array(
						'id'      => 'penci_infeedads_archi_layout',
						'type'    => 'select',
						'options' => array(
							''     => __( 'Follow Current Layout', 'soledad' ),
							'full' => __( 'Full Width', 'soledad' ),
						),
						'desc'    => __( 'Please use normal responsive ads here to get the best results - the in-feed ads can\'t work with auto-ads because auto-ads will randomly place your ads on random places on the pages.', 'soledad' ),
						'name'    => esc_html__( 'In-feed Ads Layout Type', 'soledad' ),
					),
				),
			);

			return $meta_boxes;
		}

		function floatads_option( $meta_boxes ) {
			$meta_boxes[] = array(
				'id'             => 'fltad',
				'title'          => 'Floating Ads',
				'settings_pages' => 'penci_ad_settings',
				'tab'            => 'fltad',
				'fields'         => array(
					array(
						'id'                => 'penci_floatads_enable',
						'type'              => 'switch',
						'sanitize_callback' => 'none',
						'name'              => esc_html__( 'Float Left Right Ads', 'soledad' ),
					),
					array(
						'id'                => 'penci_floatads_always_center',
						'type'              => 'switch',
						'sanitize_callback' => 'none',
						'name'              => esc_html__( 'Force Display Center in Vertical', 'soledad' ),
					),
					array(
						'id'                => 'penci_floatads_mtop',
						'type'              => 'number',
						'sanitize_callback' => 'none',
						'name'              => esc_html__( 'Margin Top', 'soledad' ),
					),
					array(
						'id'                => 'penci_floatads_scroll_mtop',
						'type'              => 'number',
						'sanitize_callback' => 'none',
						'name'              => esc_html__( 'Margin Top on Scroll', 'soledad' ),
					),
					array(
						'id'                => 'penci_floatads_banner_left',
						'type'              => 'textarea',
						'sanitize_callback' => 'none',
						'name'              => esc_html__( 'Banner Left', 'soledad' ),
					),
					array(
						'id'                => 'penci_floatads_banner_right',
						'type'              => 'textarea',
						'sanitize_callback' => 'none',
						'name'              => esc_html__( 'Banner Right', 'soledad' ),
					),
					array(
						'id'                => 'penci_floatads_width',
						'type'              => 'number',
						'default'           => '200',
						'sanitize_callback' => 'none',
						'name'              => esc_html__( 'Banner Width', 'soledad' ),
					),
					array(
						'id'                => 'penci_floatads_height',
						'type'              => 'number',
						'sanitize_callback' => 'none',
						'name'              => esc_html__( 'Banner Height', 'soledad' ),
					),
				),
			);

			return $meta_boxes;
		}

		function mobile_ads() {
			$list_mods = [
				'penci_custom_code_after_body_tag',
				'penci_custom_code_after_header_tag',
				'penci_infeedads_home_code',
				'penci_home_adsense_below_slider',
				'penci_ads_inside_content_html',
				'penci_post_adsense_single10',
				'penci_post_adsense_one',
				'penci_post_adsense_two',
				'penci_loadnp_ads',
				'penci_footer_adsense',
				'penci_footer_analytics',
				'penci_arcf_adbelow',
				'penci_archive_ad_above',
				'penci_archive_ad_below',
				'penci_infeedads_archi_code'
			];
			foreach ( $list_mods as $index => $mod ) {
				add_filter( 'theme_mod_' . $mod, function ( $value ) use ( $mod ) {

					if ( penci_is_mobile() ) {
						$mobile_value = get_theme_mod( $mod . '_mobile' );
						$value        = $mobile_value ? $mobile_value : $value;
					}

					if ( is_page() && 'disable' == get_post_meta( get_the_ID(), 'penci_page_dis_ads', true ) ) {
						$value = '';
					}

					return $value;
				} );
			}
		}
	}

	new Penci_Ad_Settings();
endif;
