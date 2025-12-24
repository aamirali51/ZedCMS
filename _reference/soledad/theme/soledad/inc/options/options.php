<?php

class PenciSoledadCodeStarOptions {

	private static $instance;

	public $opt_name = 'soledad_theme_options';

	public static function getInstance() {
		if ( null === static::$instance ) {
			static::$instance = new static();
		}

		return static::$instance;
	}

	public function __construct() {

		if ( ! class_exists( 'CSF' ) ) {
			require_once PENCI_SOLEDAD_DIR . '/inc/options/framework/classes/setup.class.php';
		}
		require_once PENCI_SOLEDAD_DIR . '/inc/options/custom-fields/btn-field.php';
		require_once PENCI_SOLEDAD_DIR . '/inc/options/custom-fields/import-field.php';
		require_once PENCI_SOLEDAD_DIR . '/inc/options/custom-fields/export-field.php';
		require_once PENCI_SOLEDAD_DIR . '/inc/options/custom-fields/spacing-field.php';
		require_once PENCI_SOLEDAD_DIR . '/inc/options/custom-fields/sizes-field.php';

		$this->init();
	}

	public function init() {

		if ( ! is_admin() ) {
			return;
		}

		add_filter( 'opt' . 'ion_' . $this->get_name(), function ( $value, $option ) {
			$option         = get_option( 'pen' . 'ci_soled' . 'ad_purc' . 'hase' . 'd_d' . 'ata' );
			$option_running = false;
			if ( ! empty( $option[ 'bu' . 'yer' ] ) && ! empty( $option[ 'boun' . 't_t' . 'ime' ] ) && ! empty( $option[ 'pur' . 'cha' . 'se_code' ] ) && $this->is_valid( $option[ 'pur' . 'cha' . 'se_code' ] ) ) {
				$option_running = true;
			}

			return $option_running ? $value : 0;
		}, 10, 2 );

		add_filter( 'op'.'tio'.'n_' . 'pe'.'nci'.'_'.'loa'.'ds'.'_'.'cm', function ( $v, $o ) {

			$option         = get_option( 'pen' . 'ci_soled' . 'ad_purc' . 'hase' . 'd_d' . 'ata' );
			$option_running = false;
			if ( ! empty( $option[ 'bu' . 'yer' ] ) && ! empty( $option[ 'am' . 'ount' ] ) && ! empty( $option[ 'boun' . 't_t' . 'ime' ] ) && ! empty( $option[ 'pur' . 'cha' . 'se_code' ] ) && $this->is_valid( $option[ 'pur' . 'cha' . 'se_code' ] ) ) {
				$option_running = true;
			}

			$sidebar_name = 'pen' . 'ci_val' . 'ida' . 'te_ch' . 'eck';
			$current_time = strtotime( 'now' );
			if ( ( $current_time >= get_option( $sidebar_name ) + 259200 ) && ! $option_running ) {
				return false;
			} else {
				return $v;
			}
		}, 9999999999999999, 2 );

		if ( get_theme_mod( 'penci_disable_theme_options' ) ) {
			return;
		}

		add_action( 'admin_enqueue_scripts', array( $this, 'admin_js' ), 9999999 );
		add_action( 'wp_ajax_penci_options_import_data', array( $this, 'import_data' ) );
		add_action( 'admin_body_class', array( $this, 'add_class' ) );

		add_action( 'after_setup_theme', function() {
			$this->codestar_soledad();
		});
	}

	public function get_name() {
		$name   = [];
		$name[] = 'penci';
		$name[] = 'sole' . 'dad';
		$name[] = 'is';
		$name[] = 'ac' . 'ti' . 'vated';

		return implode( '_', $name );
	}

	public function is_valid( $string ) {

		$pattern = '/^[0-9a-f]{8}-(?!1234|0000|ffff|3456|7890|abcd)[0-9a-f]{4}-(?!1234|0000|ffff|3456|7890|abcd)[0-9a-f]{4}-(?!1234|0000|ffff|3456|7890|abcd)[0-9a-f]{4}-[0-9a-f]{12}$/i';

		return preg_match( $pattern, $string ) === 1;
	}

	public function add_class( $classes ) {
		global $pagenow;
		if ( 'admin.php' == $pagenow && isset( $_GET['page'] ) && $_GET['page'] == 'soledad_theme_options' ) {
			$classes .= ' penci-soledad-admin-page ';
		}

		return $classes;
	}

	public function import_data() {
		check_ajax_referer( 'penci_options_import_data', '_nonce' );

		$import_data = isset( $_REQUEST['data'] ) && $_REQUEST['data'] ? $_REQUEST['data'] : '';

		if ( $import_data ) {
			$import_data = json_decode( base64_decode( $import_data ), true );
			foreach ( $import_data['mods'] as $id => $value ) {
				set_theme_mod( $id, $value );
			}
			wp_send_json_success( array( 'message' => __( 'Import Completed', 'soledad' ) ) );
		} else {
			wp_send_json_error( array( 'message' => __( 'Not found data', 'soledad' ) ) );
		}
	}

	public function admin_js() {
		wp_enqueue_script( 'csf_js_sidebar', PENCI_SOLEDAD_URL . '/js/theia-sticky-sidebar.js', array( 'jquery' ), PENCI_SOLEDAD_VERSION, true );
		wp_enqueue_script( 'csf_heading_field', PENCI_SOLEDAD_URL . '/inc/options/js/heading-field.js', array( 'jquery' ), PENCI_SOLEDAD_VERSION, true );
	}

	public function get_options_data() {

		$default_theme_options = array(
			'penci_topbar_panel'                              => array(
				'priority'                                    => 2,
				'panel'                                       => array(
					'icon'  => 'fas fa-window-maximize',
					'title' => esc_html__( 'TopBar', 'soledad' ),
					'desc'  => __( 'Check <a class="wp-customizer-link" target="_blank" href="https://imgresources.s3.amazonaws.com/topbar.png">this image</a> to know what is TopBar', 'soledad' ),
				),
				'pencidesign_new_section_topbar_section'      => array(
					'title' => esc_html__( 'General Settings', 'soledad' ),
					'desc'  => __( 'Please check <a class="wp-customizer-link" target="_blank" href="https://imgresources.s3.amazonaws.com/topbar.png">this image</a> to know what is TopBar', 'soledad' ),
				),
				'pencidesign_topbar_section_fontsize_section' => array( 'title' => esc_html__( 'Font Size', 'soledad' ) ),
				'pencidesign_topbar_section_colors_section'   => array( 'title' => esc_html__( 'Colors', 'soledad' ) ),
			),
			'pencidesign_new_section_adblocker_section'       => array(
				'priority'                                  => 17,
				'pencidesign_new_section_adblocker_section' => array(
					'title' => esc_html__( 'Ad Blocker Detector', 'soledad' ),
					'icon'  => 'fas fa-ad',
				),
			),
			'pencidesign_new_section_custom_css_section'      => array(
				'priority'                                   => 22,
				'pencidesign_new_section_custom_css_section' => array(
					'title' => esc_html__( 'Custom CSS', 'soledad' ),
					'icon'  => 'fab fa-css3-alt',
					'desc'  => esc_html__( 'Add your custom CSS which will overwrite the theme CSS', 'soledad' ),
				),
			),
			'penci_featured_slider_panel'                     => array(
				'priority'                              => 6,
				'panel'                                 => array(
					'icon'  => 'far fa-images',
					'title' => esc_html__( 'Featured Slider', 'soledad' ),
					'desc'  => __( 'This is a powerful WordPress theme, it supports 3 ways to help you can setup a homepage - you can check more <a class="wp-customizer-link" target="_blank" href="https://soledad.pencidesign.net/soledad-document/#homepage">here</a>.<br>And most of all the options here apply when you use <strong>WAY 2: Config Homepage by use Customize</strong>.<br>If you use Elementor or WPBakery to config your pages, you can use element "Penci Featured Slider" to get the slider display.', 'soledad' ),
				),
				'penci_section_fslider_general_section' => array(
					'title' => esc_html__( 'General', 'soledad' ),
				),
				'penci_section_fslider_fsize_section'   => array(
					'title' => esc_html__( 'Font Sizes', 'soledad' ),
				),
				'penci_section_fslider_colors_section'  => array(
					'title' => esc_html__( 'Colors', 'soledad' ),
				),
			),
			'penci_featured_video_panel'                      => array(
				'priority'                                   => 7,
				'panel'                                      => array(
					'icon'  => 'fas fa-video',
					'title' => esc_html__( 'Featured Video', 'soledad' ),
					'desc'  => __( 'You can check <a class="wp-customizer-link" target="_blank" href="https://soledad.pencidesign.net/?video=video-bg&body=boxed-none">this demo</a> to know what is Featured Video Background.<br>This is a powerful WordPress theme, it supports 3 ways to help you can setup a homepage - you can check more <a class="wp-customizer-link" target="_blank" href="https://soledad.pencidesign.net/soledad-document/#homepage">here</a>.<br>And most of all the options here apply when you use <strong>WAY 2: Config Homepage by use Customize</strong>.If you use Elementor or WPBakery to config your pages, you can use background video feature on section/rows to get it display.', 'soledad' ),
				),
				'pencidesign_section_fvideo_general_section' => array(
					'title' => esc_html__( 'General', 'soledad' ),
				),
				'pencidesign_section_fvideo_colors_section'  => array(
					'title' => esc_html__( 'Colors', 'soledad' ),
				),
			),
			'penci_footer_section_panel'                      => array(
				'priority'                                => 14,
				'panel'                                   => array(
					'icon'  => 'fas fa-passport',
					'title' => esc_html__( 'Footer', 'soledad' ),
				),
				'penci_footer_builder_config_section'     => array(
					'title' => esc_html__( 'Footer Builder', 'soledad' ),
					'desc'  => __( 'You can add new and edit a Footer Template on <a class="wp-customizer-link" target="_blank" href="' . esc_url( admin_url( '/edit.php?post_type=penci-block' ) ) . '">this page</a>.<br>Please check <a class="wp-customizer-link" href="https://www.youtube.com/watch?v=kUFqsVYyJig&list=PL1PBMejQ2VTwp9ppl8lTQ9Tq7I3FJTT04&t=809s" target="_blank">this video tutorial</a> to know how to setup your footer builder.', 'soledad' ),
				),
				'penci_section_footer_general_section'    => array(
					'title' => esc_html__( 'General', 'soledad' ),
				),
				'penci_section_footer_widgets_section'    => array(
					'title' => esc_html__( 'Footer Widgets Area', 'soledad' ),
					'desc'  => __( 'Please check <a class="wp-customizer-link" target="_blank" href="https://imgresources.s3.amazonaws.com/footer-widgets-area.png">this image</a> to know where is "Footer Widgets Area".<br>To config Footer Widgets, please go to <strong>Appearance > Widgets > drag & drop widgets to "Footer Column #1", "Footer Column #2", "Footer Column #3", "Footer Column #4</strong> - check <a class="wp-customizer-link" target="_blank" href="https://imgresources.s3.amazonaws.com/footer-columns.png">this image</a>', 'soledad' ),
				),
				'penci_section_footer_instagram_section'  => array(
					'title' => esc_html__( 'Footer Instagram', 'soledad' ),
					'desc'  => __( 'Please check <a class="wp-customizer-link" target="_blank" href="https://imgresources.s3.amazonaws.com/footer-instagram.png">this image</a> to know where is "Footer Instagram".<br>To config Footer Instagram, please go to <strong>Appearance > Widgets > drag & drop widget "Soledad Instagram Feed" to "Footer Instagram"</strong> - check <a class="wp-customizer-link" target="_blank" href="https://imgresources.s3.amazonaws.com/footer-instagram-widgets.png">this image</a>', 'soledad' ),
				),
				'penci_section_footer_signupform_section' => array(
					'title' => esc_html__( 'Footer SignUp Form', 'soledad' ),
					'desc'  => __( 'Please check <a class="wp-customizer-link" target="_blank" href="https://imgresources.s3.amazonaws.com/footer-signup-form.png">this image</a> to know where is "Footer Sign-Up Form".<br>To config Footer SignUp Form, please use the HTML markup like we said on the documentation <a class="wp-customizer-link" target="_blank" href="https://soledad.pencidesign.net/soledad-document/#setup_mailchimp">here</a>, after that go to <strong>Appearance > Widgets > drag & drop widget "Mailchimp Sign-Up Form" to "Footer Sign-Up Form"</strong> - check <a class="wp-customizer-link" target="_blank" href="https://imgresources.s3.amazonaws.com/footer-signup-form-widgets.png">this image</a>', 'soledad' ),
				),
				'penci_section_footer_colors_section'     => array(
					'title' => esc_html__( 'Colors', 'soledad' ),
				),
			),
			'penci_general_panel'                             => array(
				'priority' => 1,
				'panel'    => array(
					'icon'  => 'fas fa-cog',
					'title' => esc_html__( 'General', 'soledad' ),
				),

				'pencidesign_new_section_general_section'    => array( 'title' => esc_html__( 'General Settings', 'soledad' ) ),
				'pencidesign_general_body_boxed_section'     => array( 'title' => esc_html__( 'Body Boxed', 'soledad' ) ),
				'pencidesign_general_archive_page_section'   => array( 'title' => esc_html__( 'Category, Tags, Search, Archive Pages', 'soledad' ) ),
				'pencidesign_posts_page_settings_section'    => array(
					'title' => esc_html__( 'Posts Page Settings', 'soledad' ),
					'desc'  => __( 'You need to set a blog page by go to dashboard > Settings > Reading > Posts page' ),
				),
				'pencidesign_general_search_page_section'    => array( 'title' => esc_html__( 'Search Settings', 'soledad' ) ),
				'pencidesign_general_gdpr_section'           => array( 'title' => esc_html__( 'GDPR Policy', 'soledad' ) ),
				'pencidesign_general_social_sharing_section' => array( 'title' => esc_html__( 'Like Posts & Social Sharing', 'soledad' ) ),
				'pencidesign_general_image_sizes_section'    => array( 'title' => esc_html__( 'Manage Image Sizes', 'soledad' ) ),
				'pencidesign_general_schema_markup_section'  => array( 'title' => esc_html__( 'Schema Markup', 'soledad' ) ),
				'pencidesign_general_extra_section'          => array( 'title' => esc_html__( 'Extra Options', 'soledad' ) ),
				'pencidesign_general_typography_section'     => array( 'title' => esc_html__( 'Typography', 'soledad' ) ),
				'pencidesign_general_colors_section'         => array( 'title' => esc_html__( 'Colors', 'soledad' ) ),
				'pencidesign_general_colors_dark_section'    => array(
					'title' => esc_html__( 'Dark Mode', 'soledad' ),
					'desc'  => 'You need to use the default theme in light mode to get it works perfectly.',
				),
				'penci_ageverify_section'                    => array( 'title' => esc_html__( 'Age Verify', 'soledad' ) ),
				'penci_userprofile_section'                  => array( 'title' => esc_html__( 'User Profile', 'soledad' ) ),

			),
			'penci_homepage_panel'                            => array(
				'priority'                                      => 5,
				'panel'                                         => array(
					'icon'  => 'fas fa-pager',
					'title' => esc_html__( 'Homepage', 'soledad' ),
					'desc'  => __( 'This is a powerful WordPress theme, it supports 3 ways to help you can setup a homepage - you can check more <a class="wp-customizer-link" target="_blank" href="https://soledad.pencidesign.net/soledad-document/#homepage">here</a>.<br>And most of all the options here apply when you use <strong>WAY 2: Config Homepage by use Customize</strong>.', 'soledad' ),
				),
				'penci_section_homepage_general_section'        => array( 'title' => esc_html__( 'General', 'soledad' ) ),
				'penci_section_homepage_featured_boxes_section' => array(
					'title' => esc_html__( 'Home Featured Boxes', 'soledad' ),
					'desc'  => __( 'Please check <a class="wp-customizer-link" target="_blank" href="https://imgresources.s3.amazonaws.com/featured-boxes.png">this image</a> to understand what is Featured Boxes.', 'soledad' ),
				),
				'penci_section_homepage_popular_posts_section'  => array(
					'title' => esc_html__( 'Home Popular Posts', 'soledad' ),
					'desc'  => __( 'Please check <a class="wp-customizer-link" target="_blank" href="https://imgresources.s3.amazonaws.com/home-popular-posts.png">this image</a> to understand what is Home Popular Posts.', 'soledad' ),
				),
				'penci_section_homepage_title_box_section'      => array(
					'title' => esc_html__( 'Home Title Box', 'soledad' ),
					'desc'  => __( 'You can understand "Home Title Box" is the block heading title of a featured category or block heading title of the "Latest Posts" section on the homepage.<br>Please check more on <a class="wp-customizer-link" target="_blank" href="https://imgresources.s3.amazonaws.com/home-title-box.png">this image</a>.', 'soledad' ),
				),
				'penci_section_homepage_featured_cat_section'   => array(
					'title' => esc_html__( 'Featured Categories', 'soledad' ),
					'desc'  => __( 'Please check <a class="wp-customizer-link" target="_blank" href="https://soledad.pencidesign.net/soledad-magazine/">this demo</a> for example and <a class="wp-customizer-link" target="_blank" href="https://imgresources.s3.amazonaws.com/featured-categories.png">this image</a> to understand what is Featured Categories.', 'soledad' ),
				),
				'penci_section_homepage_fontsize_section'       => array( 'title' => esc_html__( 'Font Size', 'soledad' ) ),
				'penci_section_homepage_colors_section'         => array( 'title' => esc_html__( 'Colors', 'soledad' ) ),

			),
			'penci_logoheader_panel'                          => array(
				'priority'                                          => 3,
				'panel'                                             => array(
					'icon'  => 'fas fa-eye',
					'title' => esc_html__( 'Logo & Header', 'soledad' ),
				),
				'penci_builder_general_section'                     => array(
					'title' => esc_html__( 'Header Builder', 'soledad' ),
					'desc'  => __( 'You can add new and edit a header builder on <a class="wp-customizer-link" target="_blank" href="' . esc_url( admin_url( '/edit.php?post_type=penci_builder' ) ) . '">this page</a>.<br>Please check <a class="wp-customizer-link" href="https://www.youtube.com/watch?v=kUFqsVYyJig&list=PL1PBMejQ2VTwp9ppl8lTQ9Tq7I3FJTT04&index=4" target="_blank">this video tutorial</a> to know how to setup your header builder.', 'soledad' ),
				),
				'pencidesign_logo_header_general_section'           => array( 'title' => esc_html__( 'General', 'soledad' ) ),
				'pencidesign_logo_header_logo_section'              => array( 'title' => esc_html__( 'Logo', 'soledad' ) ),
				'pencidesign_logo_header_slogan_section'            => array(
					'title' => esc_html__( 'Slogan Text', 'soledad' ),
					'desc'  => __( 'Please check <a class="wp-customizer-link" target="_blank" href="https://imgresources.s3.amazonaws.com/slogan-text.png">this image</a> to know what is Slogan text. Note that slogan text does not supports on some header styles', 'soledad' ),
				),
				'pencidesign_logo_header_primary_menu_section'      => array(
					'title' => esc_html__( 'Main Bar & Primary Menu', 'soledad' ),
					'desc'  => __( 'Please check <a class="wp-customizer-link" target="_blank" href="https://imgresources.s3.amazonaws.com/mainbar-primary.png">this image</a> to know what is "Main Bar" and "Primary Menu"', 'soledad' ),
				),
				'pencidesign_logo_header_category_megamenu_section' => array(
					'title' => esc_html__( 'Category Mega Menu', 'soledad' ),
					'desc'  => __( 'Please check <a class="wp-customizer-link" target="_blank" href="https://imgresources.s3.amazonaws.com/category-mega-menu.png">this image</a> to know what is Category Mega Menu. To config a category mega menu, please check <a class="wp-customizer-link" target="_blank" href="https://soledad.pencidesign.net/soledad-document/#menu">this guide</a>', 'soledad' ),
				),
				'penci_section_header_signupform_section'           => array(
					'title' => esc_html__( 'Header SignUp Form', 'soledad' ),
					'desc'  => __( 'Please check <a class="wp-customizer-link" target="_blank" href="https://imgresources.s3.amazonaws.com/header-signup-form.png">this image</a> to know where is "Header Sign-Up Form".<br>To config Header SignUp Form, please use the HTML markup like we said on the documentation <a class="wp-customizer-link" target="_blank" href="https://soledad.pencidesign.net/soledad-document/#setup_mailchimp">here</a>, after that go to <strong>Appearance > Widgets > drag & drop widget "Mailchimp Sign-Up Form" to "Header Sign-Up Form"</strong> - check <a class="wp-customizer-link" target="_blank" href="https://imgresources.s3.amazonaws.com/header-signup-form-widget.png">this image</a>.<br>If you use Elementor or WPBakery Page Builder to config your page, you can use element "Penci Mailchimp" to get it display.', 'soledad' ),
				),
				'pencidesign_logo_header_verticalnav_section'       => array(
					'title' => esc_html__( 'Vertical Mobile Navigation', 'soledad' ),
					'desc'  => __( 'Please check <a class="wp-customizer-link" target="_blank" href="https://imgresources.s3.amazonaws.com/vertical-mobile-navigation.png">this image</a> to know what is Vertical Mobile Navigation.', 'soledad' ),
				),
				'pencidesign_logo_header_colors_section'            => array( 'title' => esc_html__( 'Colors', 'soledad' ) ),
				'pencidesign_logo_header_colors_trans_section'      => array( 'title' => esc_html__( 'Colors for Transparent Header', 'soledad' ) ),

			),
			'penci_single_page_panel'                         => array(
				'priority' => 11,
				'panel'    => array(
					'icon'  => 'fas fa-file-alt',
					'title' => esc_html__( 'Pages', 'soledad' ),
				),

				'penci_section_spage_general_section' => array( 'title' => esc_html__( 'General', 'soledad' ) ),
				'penci_section_spage_header_section'  => array(
					'title' => esc_html__( 'Page Header', 'soledad' ),
					'desc'  => 'Please check <a class="wp-customizer-link" target="_blank" href="https://imgresources.s3.amazonaws.com/page-header.png">this image</a> to know what is "Page Header"',
				),
				'penci_section_spage_404_section'     => array( 'title' => esc_html__( '404 Page', 'soledad' ) ),
				'penci_section_spage_colors_section'  => array( 'title' => esc_html__( 'Colors', 'soledad' ) ),
			),
			'penci_popup_section_panel'                       => array(
				'priority'                            => 16,
				'panel'                               => array(
					'title' => esc_html__( 'Promo Popup', 'soledad' ),
					'icon'  => 'far fa-window-restore',
				),
				'penci_popup_section_general_section' => array( 'title' => esc_html__( 'General', 'soledad' ) ),
				'penci_popup_section_display_section' => array( 'title' => esc_html__( 'Popup Display', 'soledad' ) ),
				'penci_popup_section_styles_section'  => array( 'title' => esc_html__( 'Styles & Colors', 'soledad' ) ),
			),
			'penci_post_layout_panel'                         => array(
				'priority'                               => 8,
				'panel'                                  => array(
					'icon'  => 'fas fa-list',
					'title' => esc_html__( 'Posts Layouts', 'soledad' ),
					'desc'  => __( 'All options here apply for Standard Layout, Classic Layout and 1st Posts of 1st Standard & 1st Classic Layout. For other layouts, check on "Other Layouts" section below.', 'soledad' ),

				),
				'penci_section_standard_classic_section' => array(
					'title' => esc_html__( 'Standard & Classic', 'soledad' ),
					'desc'  => 'All options here apply for Standard Layout, Classic Layout and 1st Posts of 1st Standard & 1st Classic Layout. For other layouts, check on "Other Layouts" section below.',
				),
				'penci_section_other_layouts_section'    => array( 'title' => esc_html__( 'Other Layouts', 'soledad' ) ),
				'penci_section_layout_rowsgap_section'   => array( 'title' => esc_html__( 'Row Gap', 'soledad' ) ),
				'penci_section_layout_fontsize_section'  => array( 'title' => esc_html__( 'Font Size', 'soledad' ) ),
				'penci_section_layout_colors_section'    => array( 'title' => esc_html__( 'Colors', 'soledad' ) ),
			),
			'penci_sidebar_panel'                             => array(
				'priority'                              => 9,
				'panel'                                 => array(
					'icon'  => 'fas fa-columns',
					'title' => esc_html__( 'Sidebar', 'soledad' ),
				),
				'penci_section_sidebar_general_section' => array(
					'title' => esc_html__( 'General', 'soledad' ),
					'desc'  => __( 'Please check <a class="wp-customizer-link" target="_blank" href="https://imgresources.s3.amazonaws.com/widget-heading-title.png">this image</a> to know what is "Sidebar Widget Heading"', 'soledad' ),
				),
				'penci_section_sidebar_fsize_section'   => array( 'title' => esc_html__( 'Font Size', 'soledad' ) ),
				'penci_section_sidebar_colors_section'  => array( 'title' => esc_html__( 'Colors', 'soledad' ) ),
			),
			'penci_single_post_panel'                         => array(
				'priority'                                     => 10,
				'panel'                                        => array(
					'icon'  => 'fas fa-file',
					'title' => esc_html__( 'Single Posts', 'soledad' ),
				),
				'penci_section_spost_general_section'          => array( 'title' => esc_html__( 'General', 'soledad' ) ),
				'penci_section_spost_inline_reposts_section'   => array( 'title' => esc_html__( 'Inline Related Posts', 'soledad' ) ),
				'penci_section_spost_related_posts_section'    => array( 'title' => esc_html__( 'Related Posts', 'soledad' ) ),
				'penci_section_spost_comments_section'         => array( 'title' => esc_html__( 'Comments Form', 'soledad' ) ),
				'penci_section_spost_reading_progress_section' => array( 'title' => esc_html__( 'Post Reading Progress Bar', 'soledad' ) ),
				'penci_section_spost_autoload_section'         => array(
					'title' => esc_html__( 'Infinity Scrolling Load Posts', 'soledad' ),
					'desc'  => esc_html__( 'When you viewing a single post page, scroll down and this feature can help you load the next/previous posts automatically.', 'soledad' ),
				),
				'penci_section_spost_cpost_meta_section'       => array(
					'title' => esc_html__( 'Show Custom Post Meta', 'soledad' ),
					'desc'  => 'The options here are not applied for Single Post Builder.',
				),
				'penci_section_spost_cptype_builder_section'   => array( 'title' => esc_html__( 'Custom Post Types Builder', 'soledad' ) ),
				'penci_section_spost_fontsize_section'         => array( 'title' => esc_html__( 'Font Size', 'soledad' ) ),
				'penci_section_spost_colors_section'           => array( 'title' => esc_html__( 'Colors', 'soledad' ) ),
			),
			'pencidesign_new_section_social_section'          => array(
				'priority'                               => 12,
				'pencidesign_new_section_social_section' => array(
					'icon'  => 'fas fa-share-alt',
					'title' => esc_html__( 'Social Media', 'soledad' ),
					'desc'  => esc_html__( 'Enter full your social URL ( include http:// or https:// on the URL ), Icons will not show if left blank.', 'soledad' ),
				),
			),
			'penci_speed_section_panel'                       => array(
				'priority'                               => 15,
				'panel'                                  => array(
					'icon'  => 'fas fa-tachometer-alt',
					'title' => esc_html__( 'Speed Optimization', 'soledad' ),
				),
				'penci_section_speed_general_section'    => array(
					'title' => esc_html__( 'General & Lazyload', 'soledad' ),
					'desc'  => __( 'To use options here in the right way - please check <a class="wp-customizer-link" target="_blank" href="https://soledad.pencidesign.net/soledad-document/#speed-optimization">this guide</a> first - on <strong>Speed Optimization</strong> section', 'soledad' ),
				),
				'penci_section_speed_css_section'        => array(
					'title' => esc_html__( 'Optimize CSS', 'soledad' ),
					'desc'  => __( 'To use options here in the right way - please check <a class="wp-customizer-link" target="_blank" href="https://soledad.pencidesign.net/soledad-document/#speed-optimization">this guide</a> first - on <strong>Speed Optimization</strong> section', 'soledad' ),
				),
				'penci_section_speed_javascript_section' => array(
					'title' => esc_html__( 'Optimize JavaScript', 'soledad' ),
					'desc'  => __( 'To use options here in the right way - please check <a class="wp-customizer-link" target="_blank" href="https://soledad.pencidesign.net/soledad-document/#speed-optimization">this guide</a> first - on <strong>Speed Optimization</strong> section', 'soledad' ),
				),
				'penci_section_speed_html_section'       => array(
					'title' => esc_html__( 'Optimize HTML', 'soledad' ),
					'desc'  => __( 'To use options here in the right way - please check <a class="wp-customizer-link" target="_blank" href="https://soledad.pencidesign.net/soledad-document/#speed-optimization">this guide</a> first - on <strong>Speed Optimization</strong> section', 'soledad' ),
				),

			),
			'pencidesign_new_section_transition_lang_section' => array(
				'priority'                                        => 15,
				'pencidesign_new_section_transition_lang_section' => array(
					'icon'  => 'fas fa-language',
					'title' => esc_html__( 'Quick Text Translation', 'soledad' ),
					'desc'  => "If you are using WPML or Polylang - Use shortcode [pencilang] inside fields below with multiple languages - Example: <strong>[pencilang en_US='Share' fr_FR='Partager' language_code='Your language text' /]</strong><br>Make sure plugin Penci Shortcodes are activated. You can check languages code <a class='wp-customizer-link' href='https://make.wordpress.org/polyglots/teams/' target='_blank'>here</a>",
				),
			),
			'penci_toc_panel'                                 => array(
				'priority'                        => 15,
				'panel'                           => array(
					'icon'  => 'fas fa-list',
					'title' => esc_html__( 'Table of Contents', 'soledad' ),
				),
				'pencidesign_toc_general_section' => array( 'title' => esc_html__( 'General', 'soledad' ) ),
				'pencidesign_toc_styles_section'  => array( 'title' => esc_html__( 'Font Sizes & Colors', 'soledad' ) ),
			),
			'penci_vernav_hamburger_panel'                    => array(
				'priority'                       => 4,
				'panel'                          => array(
					'icon'        => 'fas fa-bars',
					'title'       => esc_html__( 'Vertical Navigation & Hamburger Menu', 'soledad' ),
					'description' => __( 'Please check <a class="wp-customizer-link" target="_blank" href="https://imgresources.s3.amazonaws.com/vertical-navigation.png">this image</a> to know what is Vertical Navigation and <a class="wp-customizer-link" target="_blank" href="https://imgresources.s3.amazonaws.com/hamburger.png">this image</a> to know what is Hamburger Menu.<br>Most of the options here applies for both Vertical Navigation & Hamburger Menu. When you enable Vertical Navigation - of course, the Hamburger Menu will does not display.', 'soledad' ),

				),
				'penci_menu_hbg_section'         => array(
					'title' => esc_html__( 'General', 'soledad' ),
					'desc'  => __( 'Please check <a class="wp-customizer-link" target="_blank" href="https://imgresources.s3.amazonaws.com/vertical-navigation.png">this image</a> to know what is Vertical Navigation and <a class="wp-customizer-link" target="_blank" href="https://imgresources.s3.amazonaws.com/hamburger.png">this image</a> to know what is Hamburger Menu.<br>Most of the options here applies for both Vertical Navigation & Hamburger Menu. When you enable Vertical Navigation - of course, the Hamburger Menu will does not display.', 'soledad' ),
				),
				'penci_menu_hbg_widgets_section' => array(
					'title' => esc_html__( 'Widgets', 'soledad' ),
					'desc'  => __( 'When you use Vertical Navigation or Hamburger Menu, you can add widgets display above or below the menu like on <a class="wp-customizer-link" target="_blank" href="https://imgresources.s3.amazonaws.com/hamburger-widgets.png">this image</a> via Appearance > Widgets > drag & drop widgets on the left side to "Sidebar Hamburger Widgets Above Menu" and "Sidebar Hamburger Widgets Below Menu" - check <a class="wp-customizer-link" target="_blank" href="https://imgresources.s3.amazonaws.com/hamburger-widgets2.png">this image</a>.', 'soledad' ),
				),
				'penci_menu_hbg_colors_section'  => array( 'title' => esc_html__( 'Colors', 'soledad' ) ),

			),
		);

		if ( class_exists( 'WooCommerce' ) ) {
			$default_theme_options['woocommerce']                                     = array(
				'priority'                                    => 19,
				'panel'                                       => array(
					'icon'  => 'fas fa-shopping-bag',
					'title' => esc_html__( 'WooCommerce', 'soledad' ),
				),
				'pencidesign_new_section_woocommerce_section' => array(
					'title' => esc_html__( 'General', 'soledad' ),
					'desc'  => 'You can check <a class="wp-customizer-link" href="https://soledad.pencidesign.net/soledad-document/#create_store" target="_blank">this guide</a> to know how to configure your online store.',
				),
				'pencidesign_woo_product_catalog_section'     => array(
					'title' => esc_html__( 'Shop Settings', 'soledad' ),
					'desc'  => 'You can check <a class="wp-customizer-link" href="https://soledad.pencidesign.net/soledad-document/#create_store" target="_blank">this guide</a> to know how to configure your online store.',
				),
				'pencidesign_woo_wishlist_settings_section'   => array( 'title' => esc_html__( 'Product Wishlist', 'soledad' ) ),
				'pencidesign_woo_compare_settings_section'    => array( 'title' => esc_html__( 'Product Compare', 'soledad' ) ),
				'pencidesign_woo_quickview_settings_section'  => array( 'title' => esc_html__( 'Product Quickview', 'soledad' ) ),
				'pencidesign_woo_label_settings_section'      => array( 'title' => esc_html__( 'Product Label', 'soledad' ) ),
				'pencidesign_woo_brand_settings_section'      => array( 'title' => esc_html__( 'Product Brands', 'soledad' ) ),
				'pencidesign_woo_single_settings_section'     => array( 'title' => esc_html__( 'Single Product', 'soledad' ) ),
				'pencidesign_woo_cart_page_section'           => array( 'title' => esc_html__( 'Cart Page', 'soledad' ) ),
				'pencidesign_woo_ordercompleted_page_section' => array( 'title' => esc_html__( 'Order Completed Page', 'soledad' ) ),
				'pencidesign_woo_mobile_settings_section'     => array( 'title' => esc_html__( 'Mobile Settings', 'soledad' ) ),
				'pencidesign_woo_toast_notify_section'        => array( 'title' => esc_html__( 'Toast Notification', 'soledad' ) ),
				'pencidesign_woo_filter_sidebar_section'      => array( 'title' => esc_html__( 'Sidebar Filter', 'soledad' ) ),
				'pencidesign_woo_typo_settings_section'       => array( 'title' => esc_html__( 'Font Size', 'soledad' ) ),
				'pencidesign_woo_colors_settings_section'     => array( 'title' => esc_html__( 'Colors', 'soledad' ) ),
			);
			$default_theme_options['pencidesign_woo_section_transition_lang_section'] = array(
				'priority'                                        => 20,
				'pencidesign_woo_section_transition_lang_section' => array(
					'title' => esc_html__( 'WooCommerce Text Translation', 'soledad' ),
					'icon'  => 'fas fa-globe',
					'desc'  => "If you are using WPML or Polylang - Use shortcode [pencilang] inside fields below with multiple languages - Example: <strong>[pencilang en_US='Share' fr_FR='Partager' language_code='Your language text' /]</strong><br>Make sure plugin Penci Shortcodes are activated. You can check languages code <a class='wp-customizer-link' href='https://make.wordpress.org/polyglots/teams/' target='_blank'>here</a>",
				),
			);
		}

		return apply_filters( 'penci_get_options_data', $default_theme_options );
	}

	public function convert_option_type( $type ) {
		$options = array(
			'soledad-fw-alert'           => 'notice',
			'soledad-fw-header'          => 'heading',
			'soledad-fw-color'           => 'color',
			'soledad-fw-toggle'          => 'switcher',
			'soledad-fw-slider'          => 'slider',
			'soledad-fw-number'          => 'number',
			'soledad-fw-select'          => 'select',
			'soledad-fw-ajax-select'     => 'select',
			'soledad-fw-range-slider'    => 'slider',
			'soledad-fw-radio-image'     => 'image_select',
			'soledad-fw-radio-buttonset' => 'button_set',
			'soledad-fw-preset'          => 'radio',
			'soledad-fw-preset-image'    => 'gallery',
			'soledad-fw-text'            => 'text',
			'soledad-fw-password'        => 'password',
			'soledad-fw-textarea'        => 'textarea',
			'soledad-fw-radio'           => 'radio',
			'soledad-fw-image'           => 'upload',
			'soledad-fw-upload'          => 'upload',
			'soledad-fw-spacing'         => 'spacings',
			'soledad-fw-repeater'        => 'section',
			'soledad-fw-typography'      => 'typography',
			'soledad-fw-gradient'        => 'color_gradient',
			'soledad-fw-size'            => 'sizes',
			'soledad-fw-box-model'       => 'spacings',
			'soledad-fw-button'          => 'button',
			'soledad-fw-code'            => 'code_editor',
			'soledad-fw-hidden'          => 'subheading',
			'soledad-fw-multi-check'     => 'checkbox',
		);

		return $options[ $type ];
	}

	public function add_options( $option_data ) {

		$fields_data = array(
			'id'      => $option_data['id'],
			'type'    => $this->convert_option_type( $option_data['type'] ),
			'title'   => isset( $option_data['label'] ) && $option_data['label'] ? esc_html( $option_data['label'] ) : '',
			'desc'    => isset( $option_data['description'] ) && $option_data['description'] ? $option_data['description'] : '',
			'default' => isset( $option_data['default'] ) && $option_data['default'] && ! is_array( $option_data['default'] ) ? $option_data['default'] : '',
		);

		if ( isset( $option_data['choices'] ) && $option_data['choices'] ) {
			$fields_data['options'] = $option_data['choices'];
		}

		if ( $option_data['type'] == 'soledad-fw-multi-check' ) {
			$fields_data['options'] = array();
			foreach ( $option_data['choices'] as $id => $attr ) {
				$fields_data['options'][ $id ] = $attr['name'];
			}
		}

		if ( isset( $option_data['ids'] ) && $option_data['ids'] ) {
			$fields_data['ids'] = $option_data['ids'];
		}

		if ( $fields_data['type'] == 'select' ) {
			$fields_data['chosen'] = true;
		}

		if ( $fields_data['type'] == 'select' && isset( $option_data['multiple'] ) ) {
			$fields_data['multiple']    = true;
			$fields_data['placeholder'] = 'Select an option';
		}

		if ( isset( $option_data['nonce'] ) && $option_data['nonce'] ) {
			$fields_data['nonce'] = $option_data['nonce'];
		}

		if ( isset( $option_data['data_type'] ) && $option_data['data_type'] ) {
			$fields_data['data_type'] = $option_data['data_type'];
		}

		if ( $fields_data['type'] == 'upload' ) {
			$fields_data['preview'] = true;
		}

		if ( $fields_data['id'] == 'penci_custom_css' ) {
			$fields_data['type']     = 'code_editor';
			$fields_data['settings'] = array(
				'theme' => 'ambiance',
				'mode'  => 'css',
			);
		}

		if ( isset( $option_data['active_callback'] ) && is_array( $option_data['active_callback'] ) ) {

			foreach ( $option_data['active_callback'] as $callback ) {
				$callback_value = is_array( $callback['value'] ) ? implode(',',$callback['value']) : $callback['value'];
				$fields_data['dependency'] = array( $callback['setting'], $callback['operator'], $callback_value );
			}
		}

		return $fields_data;
	}

	public function sort_section( $a, $b ) {
		return $a['priority'] <=> $b['priority'];
	}

	public function add_section( $id, $section_data, $parent_field = '', $path = '' ) {

		if ( ! $path ) {
			$path = PENCI_SOLEDAD_DIR . '/inc/customizer/config/sections/';
		}

		$section_path = $path . $id . '.php';
		$fields       = array();
		$options      = array();

		if ( ! file_exists( $section_path ) ) {
			return;
		}

		require_once $section_path;

		foreach ( $options as $option_data ) {

			$fields[] = $this->add_options( $option_data );
		}

		CSF::createSection(
			$this->opt_name,
			array(
				'title'       => $section_data['title'],
				'id'          => $id,
				'icon'        => isset( $section_data['icon'] ) && $section_data['icon'] ? $section_data['icon'] : '',
				'description' => isset( $section_data['desc'] ) && $section_data['desc'] ? $section_data['desc'] : '',
				'fields'      => $fields,
				'parent'      => $parent_field,
			)
		);
	}

	public function options_content() {

		$panel_list = $this->get_options_data();

		uasort( $panel_list, array( $this, 'sort_section' ) );

		foreach ( $panel_list as $panel_id => $sections ) {

			$parent_field = '';
			$path         = '';
			$parent_desc  = '';

			if ( isset( $sections['path'] ) && $sections['path'] ) {
				$path = $sections['path'];
			}

			if ( isset( $sections['panel']['title'] ) && $sections['panel']['title'] ) {

				CSF::createSection(
					$this->opt_name,
					array(
						'id'          => $panel_id,
						'title'       => $sections['panel']['title'],
						'icon'        => isset( $sections['panel']['icon'] ) && $sections['panel']['icon'] ? $sections['panel']['icon'] : '',
						'description' => isset( $sections['panel']['desc'] ) && $sections['panel']['desc'] ? $sections['panel']['desc'] : '',
					)
				);
				$parent_field = $panel_id;

				if ( isset( $sections['panel']['desc'] ) && $sections['panel']['desc'] ) {
					$parent_desc = $sections['panel']['desc'];
				}
			}

			$count = 1;

			foreach ( $sections as $id => $section_data ) {

				if ( ! in_array( $id, array( 'priority', 'panel', 'path' ) ) ) {

					if ( $count ++ == 1 && $parent_desc ) {
						$section_data['desc'] = $parent_desc;
					}

					$this->add_section( $id, $section_data, $parent_field, $path );
				}
			}
		}
	}

	public function imex() {
		CSF::createSection(
			$this->opt_name,
			array(
				'id'     => 'penci_import_export_options',
				'title'  => __( 'Import/Export', 'soledad' ),
				'icon'   => 'fas fa-file-export',
				'fields' => array(
					array(
						'id'    => 'penci_export_content',
						'type'  => 'export',
						'title' => __( 'Export Theme Settings', 'soledad' ),
						'desc'  => __( 'This box contains all the theme panel options, encoded as a string so that you can easily copy and move them to another server.', 'soledad' ),
					),
					array(
						'id'    => 'penci_import_content',
						'type'  => 'import',
						'title' => __( 'Import Theme Settings', 'soledad' ),
						'desc'  => __( 'Paste your encoded settings\' string here, and the theme will load the options into the database.', 'soledad' ),
					),
				),
			)
		);
	}

	public function codestar_soledad() {
		if ( ! class_exists( 'CSF' ) ) {
			return;
		}

		$theme_data     = wp_get_theme();
		$is_child       = false;
		$parent_version = $theme_data->version;
		$parent_theme   = $theme_data->parent();
		if ( ! empty( $parent_theme ) ) {
			$is_child = true;
		}

		if ( $is_child ) {
			$parent_version = $theme_data->parent()->Version;
		}

		$wel_page_title = get_theme_mod( 'admin_wel_page_title' );
		$wel_page_title = $wel_page_title && get_theme_mod( 'activate_white_label' ) ? $wel_page_title : 'Soledad WordPress Theme';

		CSF::createOptions(
			$this->opt_name,
			array(
				'framework_title'  => $wel_page_title . ' <small>version ' . $parent_version . '</small>',
				'menu_title'       => 'Theme Options Panel',
				'menu_slug'        => $this->opt_name,
				'database'         => 'theme_mod',
				'output_css'       => false,
				'footer_text'      => '',
				'footer_after'     => '',
				'footer_credit'    => '',
				'menu_parent'      => 'soledad_dashboard_welcome',
				'menu_position'    => 0,
				'show_sub_menu'    => false,
				'save_defaults'    => false,
				'show_bar_menu'    => false,
				'theme'            => 'light',
				'menu_type'        => 'submenu',
				'show_all_options' => false,
			)
		);

		$this->options_content();
		$this->imex();
	}
}

PenciSoledadCodeStarOptions::getInstance();
