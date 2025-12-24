<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if ( ! class_exists( 'PENCI_FW_MetaBox_Fields' ) ):
	class PENCI_FW_MetaBox_Fields {

		public static $post_id = 0;
		public static $type = 'post';
		public static $tab = '';

		public static function html_field( $field, $post_id, $type = 'post', $tab = array() ) {
			$defaults = array(
				'id'          => '',
				'type'        => '',
				'name'        => '',
				'desc'        => '',
				'std'         => '',
				'placeholder' => '',
				'min'         => '',
				'max'         => '',
				'style'       => '',
				'options'     => array(),
			);

			self::$post_id = $post_id;
			self::$type    = $type;
			self::$tab     = $tab;

			$field = wp_parse_args( $field, $defaults );

			switch ( $field['type'] ) {
				case 'text':
					self::html_field_text( $field );
					break;
				case 'number':
					self::html_field_number( $field );
					break;
				case 'textarea':
					self::html_field_textarea( $field );
					break;
				case 'wysiwyg':
					self::html_field_wysiwyg( $field );
					break;
				case 'checkbox':
					self::html_field_checkbox( $field );
					break;
				case 'select':
					self::html_field_select( $field );
					break;
				case 'image_select':
					self::html_field_image_select( $field );
					break;
				case 'color':
					self::html_field_color( $field );
					break;
				case 'image':
					self::html_field_image( $field );
					break;
				case 'custom_html':
					self::html_field_custom_html( $field );
					break;
				case 'srart_accordion':
					self::html_field_srart_accordion( $field );
					break;
				case 'end_accordion':
					self::html_field_end_accordion( $field );
					break;
				case 'tab_general_options':
					self::get_meta_box_general();
					break;
			}
		}

		public static function get_meta_box_general() {

			// Add an nonce field so we can check for it later.
			wp_nonce_field( 'penci_inner_custom_box', 'penci_inner_custom_box_nonce' );

			// Use get_post_meta to retrieve an existing value from the database.
			$value                   = get_post_meta( self::$post_id, 'penci_custom_sidebar_page_display', true );
			$value_left              = get_post_meta( self::$post_id, 'penci_custom_sidebar_left_page_field', true );
			$slider                  = get_post_meta( self::$post_id, 'penci_page_slider', true );
			$featured_boxes          = get_post_meta( self::$post_id, 'penci_page_display_featured_boxes', true );
			$pagetitle               = get_post_meta( self::$post_id, 'penci_page_display_title', true );
			$breadcrumb              = get_post_meta( self::$post_id, 'penci_page_breadcrumb', true );
			$sharebox                = get_post_meta( self::$post_id, 'penci_page_sharebox', true );
			$rev_shortcode           = get_post_meta( self::$post_id, 'penci_page_rev_shortcode', true );
			$hide_header             = get_post_meta( self::$post_id, 'penci_page_hide_header', true );
			$hide_footer             = get_post_meta( self::$post_id, 'penci_page_hide_footer', true );
			$page_sidebar            = get_post_meta( self::$post_id, 'penci_sidebar_page_pos', true );
			$penci_helpful_enable    = get_post_meta( self::$post_id, 'penci_helpful_enable', true );
			$penci_page_dis_ads      = get_post_meta( self::$post_id, 'penci_page_dis_ads', true );
			$penci_post_critical_css = get_post_meta( self::$post_id, 'penci_post_critical_css', true );
			$penci_desktop_page_id   = get_post_meta( self::$post_id, 'penci_desktop_page_id', true );
			$penci_desktop_page_id   = ( isset( $_REQUEST['desktop_id'] ) && $_REQUEST['desktop_id'] ) ? $_REQUEST['desktop_id'] : $penci_desktop_page_id;
			?>
			<?php if ( $penci_desktop_page_id ) : ?>

			<input id="penci_desktop_page_id" name="penci_desktop_page_id" type="hidden" value="<?php echo esc_attr($penci_desktop_page_id);?>" />

			<?php endif; ?>
            <div class="pcmt-control-wrapper">
                <div class="pcmt-title">
                    <h2 style="font-weight: 600; font-size: 14px; padding-left: 0;min-width: 200px;">Select Featured
                        Slider/Featured Video to Display on Top of This Page?</h2>
                    <p class="description">This option not apply for Page Template Full Width</p>
                </div>
                <div class="pcmt-control">
                    <p>
                        <select id="penci_page_slider_field" name="penci_page_slider_field">
                            <option value=""><?php _e( 'None', 'soledad' ); ?></option>
                            <option value="style-1" <?php selected( $slider, 'style-1' ); ?>><?php _e( 'Posts Featured Slider Style 1', 'soledad' ); ?></option>
                            <option value="style-2" <?php selected( $slider, 'style-2' ); ?>><?php _e( 'Posts Featured Slider Style 2', 'soledad' ); ?></option>
                            <option value="style-3" <?php selected( $slider, 'style-3' ); ?>><?php _e( 'Posts Featured Slider Style 3', 'soledad' ); ?></option>
                            <option value="style-4" <?php selected( $slider, 'style-4' ); ?>><?php _e( 'Posts Featured Slider Style 4', 'soledad' ); ?></option>
                            <option value="style-5" <?php selected( $slider, 'style-5' ); ?>><?php _e( 'Posts Featured Slider Style 5', 'soledad' ); ?></option>
                            <option value="style-6" <?php selected( $slider, 'style-6' ); ?>><?php _e( 'Posts Featured Slider Style 6', 'soledad' ); ?></option>
                            <option value="style-7" <?php selected( $slider, 'style-7' ); ?>><?php _e( 'Posts Featured Slider Style 7', 'soledad' ); ?></option>
                            <option value="style-8" <?php selected( $slider, 'style-8' ); ?>><?php _e( 'Posts Featured Slider Style 8', 'soledad' ); ?></option>
                            <option value="style-9" <?php selected( $slider, 'style-9' ); ?>><?php _e( 'Posts Featured Slider Style 9', 'soledad' ); ?></option>
                            <option value="style-10" <?php selected( $slider, 'style-10' ); ?>><?php _e( 'Posts Featured Slider Style 10', 'soledad' ); ?></option>
                            <option value="style-11" <?php selected( $slider, 'style-11' ); ?>><?php _e( 'Posts Featured Slider Style 11', 'soledad' ); ?></option>
                            <option value="style-12" <?php selected( $slider, 'style-12' ); ?>><?php _e( 'Posts Featured Slider Style 12', 'soledad' ); ?></option>
                            <option value="style-13" <?php selected( $slider, 'style-13' ); ?>><?php _e( 'Posts Featured Slider Style 13', 'soledad' ); ?></option>
                            <option value="style-14" <?php selected( $slider, 'style-14' ); ?>><?php _e( 'Posts Featured Slider Style 14', 'soledad' ); ?></option>
                            <option value="style-15" <?php selected( $slider, 'style-15' ); ?>><?php _e( 'Posts Featured Slider Style 15', 'soledad' ); ?></option>
                            <option value="style-16" <?php selected( $slider, 'style-16' ); ?>><?php _e( 'Posts Featured Slider Style 16', 'soledad' ); ?></option>
                            <option value="style-17" <?php selected( $slider, 'style-17' ); ?>><?php _e( 'Posts Featured Slider Style 17', 'soledad' ); ?></option>
                            <option value="style-18" <?php selected( $slider, 'style-18' ); ?>><?php _e( 'Posts Featured Slider Style 18', 'soledad' ); ?></option>
                            <option value="style-19" <?php selected( $slider, 'style-19' ); ?>><?php _e( 'Posts Featured Slider Style 19', 'soledad' ); ?></option>
                            <option value="style-20" <?php selected( $slider, 'style-20' ); ?>><?php _e( 'Posts Featured Slider Style 20', 'soledad' ); ?></option>
                            <option value="style-21" <?php selected( $slider, 'style-21' ); ?>><?php _e( 'Posts Featured Slider Style 21', 'soledad' ); ?></option>
                            <option value="style-22" <?php selected( $slider, 'style-22' ); ?>><?php _e( 'Posts Featured Slider Style 22', 'soledad' ); ?></option>
                            <option value="style-23" <?php selected( $slider, 'style-23' ); ?>><?php _e( 'Posts Featured Slider Style 23', 'soledad' ); ?></option>
                            <option value="style-24" <?php selected( $slider, 'style-24' ); ?>><?php _e( 'Posts Featured Slider Style 24', 'soledad' ); ?></option>
                            <option value="style-25" <?php selected( $slider, 'style-25' ); ?>><?php _e( 'Posts Featured Slider Style 25', 'soledad' ); ?></option>
                            <option value="style-26" <?php selected( $slider, 'style-26' ); ?>><?php _e( 'Posts Featured Slider Style 26', 'soledad' ); ?></option>
                            <option value="style-27" <?php selected( $slider, 'style-27' ); ?>><?php _e( 'Posts Featured Slider Style 27', 'soledad' ); ?></option>
                            <option value="style-28" <?php selected( $slider, 'style-28' ); ?>><?php _e( 'Posts Featured Slider Style 28', 'soledad' ); ?></option>
                            <option value="style-29" <?php selected( $slider, 'style-29' ); ?>><?php _e( 'Posts Featured Slider Style 29', 'soledad' ); ?></option>
                            <option value="style-30" <?php selected( $slider, 'style-30' ); ?>><?php _e( 'Posts Featured Slider Style 30', 'soledad' ); ?></option>
                            <option value="style-31" <?php selected( $slider, 'style-31' ); ?>><?php _e( 'Penci Slider Style 1', 'soledad' ); ?></option>
                            <option value="style-32" <?php selected( $slider, 'style-32' ); ?>><?php _e( 'Penci Slider Style 2', 'soledad' ); ?></option>
                            <option value="style-33" <?php selected( $slider, 'style-33' ); ?>><?php _e( 'Revolution Slider Full Width', 'soledad' ); ?></option>
                            <option value="style-34" <?php selected( $slider, 'style-34' ); ?>><?php _e( 'Revolution Slider In Container', 'soledad' ); ?></option>
                            <option value="style-35" <?php selected( $slider, 'style-35' ); ?>><?php _e( 'Posts Featured Slider Style 35', 'soledad' ); ?></option>
                            <option value="style-36" <?php selected( $slider, 'style-36' ); ?>><?php _e( 'Posts Featured Slider Style 36', 'soledad' ); ?></option>
                            <option value="style-37" <?php selected( $slider, 'style-37' ); ?>><?php _e( 'Posts Featured Slider Style 37', 'soledad' ); ?></option>
                            <option value="style-38" <?php selected( $slider, 'style-38' ); ?>><?php _e( 'Posts Featured Slider Style 38', 'soledad' ); ?></option>
                            <option value="style-40" <?php selected( $slider, 'style-40' ); ?>><?php _e( 'Posts Featured Slider Style 39', 'soledad' ); ?></option>
                            <option value="style-41" <?php selected( $slider, 'style-40' ); ?>><?php _e( 'Posts Featured Slider Style 40', 'soledad' ); ?></option>
                            <option value="style-42" <?php selected( $slider, 'style-40' ); ?>><?php _e( 'Posts Featured Slider Style 41', 'soledad' ); ?></option>
                            <option value="style-44" <?php selected( $slider, 'style-40' ); ?>><?php _e( 'Posts Featured Slider Style 42', 'soledad' ); ?></option>
                            <option value="video" <?php selected( $slider, 'video' ); ?>><?php _e( 'Featured Video Background', 'soledad' ); ?></option>
                        </select>
                    </p>
                </div>
            </div>

            <h2 style="font-weight: 600; font-size: 14px; padding-left: 0;min-width: 200px;"><?php _e( 'Revolution Slider Shortcode', 'soledad' ); ?></h2>
            <p class="description"><?php _e( 'If you select Revolution Slider above, please fill Revolution Slider Shortcode here. This option not apply for Page Template Full Width', 'soledad' ); ?></p>
            <textarea style="width: 100%; height: 50px;"
                      name="penci_page_rev_shortcode"><?php if ( $rev_shortcode ): echo $rev_shortcode; endif; ?></textarea>

            <div class="penci-metabox-row penci-col-6">
                <h2 style="font-weight: 600; font-size: 14px; padding-left: 0;min-width: 200px;"><?php _e( 'Display Featured Boxes?', 'soledad' ); ?> </h2>
                <p class="select-button-type">
                    <select id="penci_page_display_featured_boxes" name="penci_page_display_featured_boxes">
                        <option value=""><?php _e( 'No', 'soledad' ); ?></option>
                        <option value="yes" <?php selected( $featured_boxes, 'yes' ); ?>><?php _e( 'Yes', 'soledad' ); ?></option>
                    </select>
                </p>
            </div>
            <div class="penci-metabox-row penci-col-6">
                <h2 style="font-weight: 600; font-size: 14px; padding-left: 0;min-width: 200px;"><?php _e( 'Display Page Title?', 'soledad' ); ?> </h2>
                <p class="select-button-type">
                    <select id="penci_page_display_title_field" name="penci_page_display_title_field">
                        <option value=""><?php _e( 'Default', 'soledad' ); ?></option>
                        <option value="yes" <?php selected( $pagetitle, 'yes' ); ?>><?php _e( 'Yes', 'soledad' ); ?></option>
                        <option value="no" <?php selected( $pagetitle, 'no' ); ?>><?php _e( 'No', 'soledad' ); ?></option>
                    </select>
                </p>
            </div>
            <div class="penci-metabox-row penci-col-6">
                <h2 style="font-weight: 600; font-size: 14px; padding-left: 0;min-width: 200px;"><?php _e( 'Display Breadcrumb on This Page?', 'soledad' ); ?></h2>
                <p class="select-button-type">
                    <select id="penci_page_breadcrumb_field" name="penci_page_breadcrumb_field">
                        <option value=""><?php _e( 'Yes', 'soledad' ); ?></option>
                        <option value="no" <?php selected( $breadcrumb, 'no' ); ?>><?php _e( 'No', 'soledad' ); ?></option>
                    </select>
                </p>
            </div>
            <div class="penci-metabox-row penci-col-6">
                <h2 style="font-weight: 600; font-size: 14px; padding-left: 0;min-width: 200px;"><?php esc_html_e( 'Display Share Box on This Page?', 'soledad' ); ?></h2>
                <p class="select-button-type">
                    <select id="penci_page_sharebox_field" name="penci_page_sharebox_field">
                        <option value=""><?php esc_html_e( 'Yes', 'soledad' ); ?></option>
                        <option value="no" <?php selected( $sharebox, 'no' ); ?>><?php esc_html_e( 'No', 'soledad' ); ?></option>
                    </select>
                </p>
            </div>
            <div class="penci-metabox-row penci-col-6">

                <h2 style="font-weight: 600; font-size: 14px; padding-left: 0;min-width: 200px;"><?php esc_html_e( 'Hide Header on This Page?', 'soledad' ); ?></h2>
                <p class="select-button-type">
                    <select id="penci_page_hide_header_field" name="penci_page_hide_header_field">
                        <option value=""><?php esc_html_e( 'No', 'soledad' ); ?></option>
                        <option value="yes" <?php selected( $hide_header, 'yes' ); ?>><?php esc_html_e( 'Yes', 'soledad' ); ?></option>
                    </select>
                </p>
            </div>
            <div class="penci-metabox-row penci-col-6">
                <h2 style="font-weight: 600; font-size: 14px; padding-left: 0;min-width: 200px;">Hide Footer on This
                    Page?</h2>
                <p class="select-button-type">
                    <select id="penci_page_hide_footer_field" name="penci_page_hide_footer_field">
                        <option value=""><?php esc_html_e( 'No', 'soledad' ); ?></option>
                        <option value="yes" <?php selected( $hide_footer, 'yes' ); ?>><?php esc_html_e( 'Yes', 'soledad' ); ?></option>
                    </select>
                </p>
            </div>
            <div class="penci-col-12 pcmt-control-wrapper">
                <div class="pcmt-title">
                    <h2 style="font-weight: 600; font-size: 14px; padding-left: 0;min-width: 200px;"><?php esc_html_e( 'Select Sidebar Position for This Page', 'soledad' ); ?></h2>
                    <p style="clear: both"
                       class="description"><?php esc_html_e( 'This option just apply for Page Template "Default Template", "Page with Sidebar" and "Page VC Builder with Sidebar"', 'soledad' ); ?></p>
                </div>
                <div class="pcmt-control select-button-type">
                    <p>
                        <select id="penci_sidebar_page_pos" name="penci_sidebar_page_pos">
                            <option value=""><?php esc_html_e( "Default", "soledad" ); ?></option>
                            <option value="left-sidebar" <?php selected( $page_sidebar, 'left-sidebar' ); ?>><?php esc_html_e( "Left Sidebar", "soledad" ); ?></option>
                            <option value="right-sidebar" <?php selected( $page_sidebar, 'right-sidebar' ); ?>><?php esc_html_e( "Right Sidebar", "soledad" ); ?></option>
                            <option value="two-sidebar" <?php selected( $page_sidebar, 'two-sidebar' ); ?>><?php esc_html_e( "Two Sidebar", "soledad" ); ?></option>
                        </select>
                    </p>
                </div>
            </div>
			<div class="penci-col-12 pcmt-control-wrapper">
                <div class="pcmt-title">
                    <h2 style="font-weight: 600; font-size: 14px; padding-left: 0;min-width: 200px;"><?php esc_html_e( 'Disable All Adsense Code on This Page', 'soledad' ); ?></h2>
                </div>
                <div class="pcmt-control select-button-type">
                    <p>
                        <select id="penci_page_dis_ads" name="penci_page_dis_ads">
                            <option value=""><?php esc_html_e( "No, Keep all Ads", "soledad" ); ?></option>
                            <option value="disable" <?php selected( $penci_page_dis_ads, 'disable' ); ?>><?php esc_html_e( "Yes, Disable all Ads", "soledad" ); ?></option>
                        </select>
                    </p>
                </div>
            </div>
			
			<?php
                $post_type = get_theme_mod( 'penci_ha_enabled_post_types', [] );
                if ( in_array( 'page', $post_type ) ) {
            ?>
			<div class="penci-col-12 pcmt-control-wrapper">
                <div class="pcmt-title">
                    <h2 style="font-weight: 600; font-size: 14px; padding-left: 0;min-width: 200px;"><?php esc_html_e( 'Enable Article Feedback for This
                        Page', 'soledad' ); ?></h2>
                </div>
                <div class="pcmt-control select-button-type">
                    <p>
						<select id="penci_helpful_enable" name="penci_helpful_enable">
                            <option value=""><?php esc_html_e( 'Default Settings', 'soledad' ); ?></option>
                            <option value="yes" <?php selected( $penci_helpful_enable, 'yes' ); ?>><?php esc_html_e( 'Yes', 'soledad' ); ?></option>
                            <option value="no" <?php selected( $penci_helpful_enable, 'no' ); ?>><?php esc_html_e( 'No', 'soledad' ); ?></option>
                        </select>
                    </p>
                </div>
            </div>
			<?php } ?>

            <div class="penci-col-12 pcmt-control-wrapper">
                <div class="pcmt-title">
                    <h2 style="font-weight: 600; font-size: 14px; padding-left: 0;min-width: 200px;"><?php esc_html_e( 'Custom Sidebar for This Posts/Page', 'soledad' ); ?></h2>
                    <p class="description"><?php esc_html_e( 'Note: for page, you can choose display sidebar or no in Template "Page with Sidebar" and custom sidebar here, if sidebar you choice here is empty, will display sidebar you choice for page in customize. This option not apply for Page Template Full Width', 'soledad' ); ?></p>
                </div>
                <div class="pcmt-control">
                    <p>
                        <select id="penci_custom_sidebar_page_field" name="penci_custom_sidebar_page_field">
                            <option value=""><?php esc_html_e( "Default Sidebar( on Customize )", "soledad" ); ?></option>
                            <option value="main-sidebar" <?php selected( $value, 'main-sidebar' ); ?>><?php esc_html_e( "Main Sidebar", "soledad" ); ?></option>
                            <option value="main-sidebar-left" <?php selected( $value, 'main-sidebar-left' ); ?>><?php esc_html_e( "Main Sidebar Left", "soledad" ); ?></option>
                            <option value="custom-sidebar-1" <?php selected( $value, 'custom-sidebar-1' ); ?>><?php esc_html_e( "Custom Sidebar 1", "soledad" ); ?></option>
                            <option value="custom-sidebar-2" <?php selected( $value, 'custom-sidebar-2' ); ?>><?php esc_html_e( "Custom Sidebar 2", "soledad" ); ?></option>
                            <option value="custom-sidebar-3" <?php selected( $value, 'custom-sidebar-3' ); ?>><?php esc_html_e( "Custom Sidebar 3", "soledad" ); ?></option>
                            <option value="custom-sidebar-4" <?php selected( $value, 'custom-sidebar-4' ); ?>><?php esc_html_e( "Custom Sidebar 4", "soledad" ); ?></option>
                            <option value="custom-sidebar-5" <?php selected( $value, 'custom-sidebar-5' ); ?>><?php esc_html_e( "Custom Sidebar 5", "soledad" ); ?></option>
                            <option value="custom-sidebar-6" <?php selected( $value, 'custom-sidebar-6' ); ?>><?php esc_html_e( "Custom Sidebar 6", "soledad" ); ?></option>
                            <option value="custom-sidebar-7" <?php selected( $value, 'custom-sidebar-7' ); ?>><?php esc_html_e( "Custom Sidebar 7", "soledad" ); ?></option>
                            <option value="custom-sidebar-8" <?php selected( $value, 'custom-sidebar-8' ); ?>><?php esc_html_e( "Custom Sidebar 8", "soledad" ); ?></option>
                            <option value="custom-sidebar-9" <?php selected( $value, 'custom-sidebar-9' ); ?>><?php esc_html_e( "Custom Sidebar 9", "soledad" ); ?></option>
                            <option value="custom-sidebar-10" <?php selected( $value, 'custom-sidebar-10' ); ?>><?php esc_html_e( "Custom Sidebar 10", "soledad" ); ?></option>
							<?php Penci_Custom_Sidebar::get_list_sidebar( $value ); ?>
                        </select>
                    </p>
                </div>
            </div>
            <div class="penci-col-12 pcmt-control-wrapper">
                <div class="pcmt-title">
                    <h2 style="font-weight: 600; font-size: 14px; padding-left: 0;min-width: 200px;"><?php esc_html_e( 'Custom Sidebar Left for This Posts/Page', 'soledad' ); ?></h2>
                    <p class="description"><?php esc_html_e( 'Note: for page, you can choose display sidebar or no in Template "Page with Sidebar" and custom sidebar here, if sidebar you choice here is empty, will display sidebar you choice for page in customize. This option not apply for Page Template Full Width', 'soledad' ); ?></p>
                </div>
                <div class="pcmt-control">
                    <p>
                        <select id="penci_custom_sidebar_left_page_field" name="penci_custom_sidebar_left_page_field">
                            <option value=""><?php esc_html_e( "Default Sidebar( on Customize )", "soledad" ); ?></option>
                            <option value="main-sidebar" <?php selected( $value_left, 'main-sidebar' ); ?>><?php esc_html_e( "Main Sidebar", "soledad" ); ?></option>
                            <option value="main-sidebar-left" <?php selected( $value_left, 'main-sidebar-left' ); ?>><?php esc_html_e( "Main Sidebar Left", "soledad" ); ?></option>
                            <option value="custom-sidebar-1" <?php selected( $value_left, 'custom-sidebar-1' ); ?>><?php esc_html_e( "Custom Sidebar 1", "soledad" ); ?></option>
                            <option value="custom-sidebar-2" <?php selected( $value_left, 'custom-sidebar-2' ); ?>><?php esc_html_e( "Custom Sidebar 2", "soledad" ); ?></option>
                            <option value="custom-sidebar-3" <?php selected( $value_left, 'custom-sidebar-3' ); ?>><?php esc_html_e( "Custom Sidebar 3", "soledad" ); ?></option>
                            <option value="custom-sidebar-4" <?php selected( $value_left, 'custom-sidebar-4' ); ?>><?php esc_html_e( "Custom Sidebar 4", "soledad" ); ?></option>
                            <option value="custom-sidebar-5" <?php selected( $value_left, 'custom-sidebar-5' ); ?>><?php esc_html_e( "Custom Sidebar 5", "soledad" ); ?></option>
                            <option value="custom-sidebar-6" <?php selected( $value_left, 'custom-sidebar-6' ); ?>><?php esc_html_e( "Custom Sidebar 6", "soledad" ); ?></option>
                            <option value="custom-sidebar-7" <?php selected( $value_left, 'custom-sidebar-7' ); ?>><?php esc_html_e( "Custom Sidebar 7", "soledad" ); ?></option>
                            <option value="custom-sidebar-8" <?php selected( $value_left, 'custom-sidebar-8' ); ?>><?php esc_html_e( "Custom Sidebar 8", "soledad" ); ?></option>
                            <option value="custom-sidebar-9" <?php selected( $value_left, 'custom-sidebar-9' ); ?>><?php esc_html_e( "Custom Sidebar 9", "soledad" ); ?></option>
                            <option value="custom-sidebar-10" <?php selected( $value_left, 'custom-sidebar-10' ); ?>><?php esc_html_e( "Custom Sidebar 10", "soledad" ); ?></option>
							<?php Penci_Custom_Sidebar::get_list_sidebar( $value_left ); ?>
                        </select>
                    </p>
                </div>
            </div>
			<?php if ( get_theme_mod( 'penci_speed_remove_css' ) ): ?>
                <div class="penci-metabox-row penci-col-12">

                    <h2 style="font-weight: 600; font-size: 14px; padding-left: 0;min-width: 200px;"><?php esc_html_e( 'Create a Separate Critical CSS cache for this Page?', 'soledad' ); ?></h2>
                    <p class="select-button-type">
                        <select id="penci_post_critical_css" name="penci_post_critical_css">
                            <option value=""><?php esc_html_e( 'No', 'soledad' ); ?></option>
                            <option value="yes" <?php selected( $penci_post_critical_css, 'yes' ); ?>><?php esc_html_e( 'Yes', 'soledad' ); ?></option>
                        </select>
                    </p>
                </div>
			<?php endif;
		}

		private static function get_value_input( $field_id ) {
			$tab = self::$tab;

			$output = '';

			if ( $tab ) {
				if ( 'term' == self::$type ) {
					$penci_pmeta = get_term_meta( self::$post_id, 'penci_pmeta_' . $tab, true );
				} else {
					$penci_pmeta = get_post_meta( self::$post_id, 'penci_pmeta_' . $tab, true );
				}

				if ( isset( $penci_pmeta[ $field_id ] ) ) {
					$output = $penci_pmeta[ $field_id ];
				}
			} else {
				if ( 'term' == self::$type ) {
					$output = get_term_meta( self::$post_id, $field_id, true );
				} else {
					$output = get_post_meta( self::$post_id, $field_id, true );
				}
			}


			return $output;
		}

		private static function html_field_text( $field ) {
			self::html_field_div_before( $field['id'], $field['style'] );

			echo '<div class="penci-mb-labeldesc">';
			self::html_field_label( $field );
			echo '</div>';
			echo '<div class="penci-mb-input">';

			$placeholder = '';
			if ( isset( $field['placeholder'] ) && $field['placeholder'] ) {
				$placeholder = 'placeholder="' . $field['placeholder'] . '"';
			}


			printf( '<input %s type="text" name="%s" id="%s" %s value="%s">',
				self::html_attr_input( $field ),
				$field['id'],
				$field['id'],
				$placeholder,
				self::get_value_input( $field['id'] )
			);

			self::html_field_desc( $field['desc'] );
			echo '</div>';

			self::html_field_div_after();
		}

		private static function html_field_number( $field ) {
			self::html_field_div_before( $field['id'], $field['style'] );

			echo '<div class="penci-mb-labeldesc">';
			self::html_field_label( $field );
			echo '</div>';
			echo '<div class="penci-mb-input">';
			printf( '<input %s type="number" name="%s" id="%s" value="%s">',
				self::html_attr_input( $field ),
				$field['id'],
				$field['id'],
				self::get_value_input( $field['id'] )
			);

			self::html_field_desc( $field['desc'] );
			echo '</div>';

			self::html_field_div_after();
		}

		private static function html_field_textarea( $field ) {
			self::html_field_div_before( $field['id'], $field['style'] );

			echo '<div class="penci-mb-labeldesc">';
			self::html_field_label( $field );
			self::html_field_desc( $field['desc'] );
			echo '</div>';
			echo '<div class="penci-mb-input">';

			printf( '<textarea %s name="%s" id="%s">%s</textarea>',
				self::html_attr_input( $field ),
				$field['id'],
				$field['id'],
				self::get_value_input( $field['id'] )
			);
			echo '</div>';

			self::html_field_div_after();
		}

		private static function html_field_wysiwyg( $field ) {
			echo '<div class="penci-metabox-row ' . esc_attr( $field['id'] ) . ( $field['style'] ? ' ' . $field['style'] : '' ) . '">';

			if ( isset( $field['name'] ) && $field['name'] ) {
				echo '<div class="penci-mb-labeldesc">';
				self::html_field_label( $field );
				self::html_field_desc( $field['desc'] );
				echo '</div>';
			}

			echo '<div class="penci-mb-input">';

			$content = self::get_value_input( $field['id'] );

			$field['options']['textarea_name'] = $field['id'];
			$field['options']['dfw']           = isset( $field['1'] ) ? $field['1'] : '';

			if ( ! isset( $field['options']['editor_height'] ) ) {
				$field['options']['editor_height'] = '250';
			}

			if ( isset( $field['options']['textarea_rows'] ) && $field['options']['textarea_rows'] ) {
				$field['options']['textarea_rows'] = '10';
			}

			wp_editor( $content, $field['id'], $field['options'] );

			echo '</div>';

			self::html_field_div_after();
		}

		private static function html_field_checkbox( $field ) {
			self::html_field_div_before( $field['id'], $field['style'] );

			echo '<div class="penci-mb-labeldesc">';
			self::html_field_label( $field );
			echo '</div>';
			echo '<div class="penci-mb-input">';

			$selected = self::get_value_input( $field['id'] );

			printf( '<input %s name="%s" id="%s"  type="checkbox" %s>',
				self::html_attr_input( $field ),
				$field['id'],
				$field['id'],
				checked( $selected, 1, false )
			);
			self::html_field_desc( $field['desc'] );
			echo '</div>';

			self::html_field_div_after();
		}

		private static function html_field_select( $field ) {
			self::html_field_div_before( $field['id'], $field['style'] );

			echo '<div class="penci-mb-labeldesc">';
			self::html_field_label( $field );

			echo '</div>';
			echo '<div class="penci-mb-input">';
			printf( '<select %s name="%s" id="%s">%s',
				self::html_attr_input( $field ),
				$field['id'],
				$field['id'],
				''
			);
			$options  = $field['options'];
			$selected = self::get_value_input( $field['id'] );

			foreach ( (array) $options as $param_name => $param_value ) {
				?>
                <option
                value="<?php echo $param_name; ?>" <?php selected( $selected, $param_name, true ); ?>><?php echo $param_value; ?></option><?php
			}
			echo '</select>';
			self::html_field_desc( $field['desc'] );

			echo '</div>';

			self::html_field_div_after();
		}

		private static function html_field_image_select( $field ) {
			self::html_field_div_before( $field['id'], $field['style'] );

			echo '<div class="penci-mb-labeldesc">';
			self::html_field_label( $field );
			self::html_field_desc( $field['desc'] );
			echo '</div>';
			echo '<div class="penci-mb-input">';

			$value_current = self::get_value_input( $field['id'] );

			if ( ! $value_current && $field['std'] ) {
				$value_current = $field['std'];
			}

			echo '<div class="penci-image-select-wrap">';
			$options = $field['options'];
			$tpl     = '<label class="penci-image-select"><img src="%s"><input type="%s" %s class="penci-image_select" name="%s" value="%s"%s></label>';

			$value_current = (array) $value_current;
			foreach ( (array) $options as $value => $image ) {
				printf(
					$tpl,
					$image,
					'radio',
					self::html_attr_input( $field ),
					$field['id'],
					$value,
					checked( in_array( $value, $value_current ), true, false )
				);
			}
			echo '</div>';
			echo '</div>';

			self::html_field_div_after();
		}

		private static function html_field_color( $field ) {
			self::html_field_div_before( $field['id'], $field['style'] );

			echo '<div class="penci-mb-labeldesc">';
			self::html_field_label( $field );
			echo '</div>';
			echo '<div class="penci-mb-input">';

			printf( '<input class="penci-color-picker rwmb-color" type="text" %s name="%s" id="%s" value="%s">',
				self::html_attr_input( $field ),
				$field['id'],
				$field['id'],
				self::get_value_input( $field['id'] )
			);
			self::html_field_desc( $field['desc'] );
			echo '</div>';

			self::html_field_div_after();

			wp_enqueue_script( 'rwmb', RWMB_JS_URL . 'script.js', array( 'jquery' ), RWMB_VER, true );
			RWMB_Color_Field::admin_enqueue_scripts();
		}

		private static function html_field_image( $field ) {
			self::html_field_div_before( $field['id'], $field['style'] );

			echo '<div class="penci-mb-labeldesc">';
			self::html_field_label( $field );
			self::html_field_desc( $field['desc'] );
			echo '</div>';

			$img_id = self::get_value_input( $field['id'] );
			$url    = wp_get_attachment_thumb_url( $img_id );
			?>
            <div class="penci-mb-input penci-widget-image media-widget-control">
                <input name="<?php echo $field['id']; ?>" type="hidden" class="penci-widget-image__input"
                       value="<?php echo esc_attr( $img_id ); ?>">
                <img src="<?php echo esc_url( $url ); ?>"
                     class="penci-widget-image__image<?php echo $img_id ? '' : ' hidden'; ?>">
                <div class="placeholder <?php echo( $url ? 'hidden' : '' ); ?>"><?php _e( 'No image selected' ); ?></div>
                <button class="button penci-widget-image__select"><?php esc_html_e( 'Select' ); ?></button>
                <button class="button penci-widget-image__remove"><?php esc_html_e( 'Remove' ); ?></button>
            </div>
			<?php
			self::html_field_div_after();
		}

		private static function html_field_custom_html( $field ) {
			echo '<div class="penci-metabox-row">';
			echo $field['std'];
			self::html_field_div_after();
		}

		private static function html_field_srart_accordion( $field ) {
			echo '<div class="penci-accordion-name ' . $field['std'] . '"><h3>' . $field['name'] . '</h3><span class="handle-repeater"></span></div>';
			echo '<div class="penci-panel-accordion">';
		}

		private static function html_field_end_accordion( $field ) {
			echo '</div>';
		}

		private static function html_field_div_before( $field_id, $style ) {
			echo '<div id="' . esc_attr( $field_id ) . '" class="penci-metabox-row ' . esc_attr( $field_id ) . ( $style ? ' ' . $style : $style ) . '">';
		}

		private static function html_field_div_after() {
			echo '</div>';
		}

		private static function html_field_label( $field ) {
			echo '<label for="' . esc_attr( $field['id'] ) . '" class="penci-metabox-label ' . esc_attr( $field['id'] ) . 'label">' . esc_attr( $field['name'] ) . '</label>';
		}

		private static function html_field_desc( $desc ) {
			echo '<span class="penci-metabox-desc">' . $desc . '</span>';
		}

		private static function html_attr_input( $field ) {
			$html  = '';
			$attrs = isset( $field['attrs'] ) ? $field['attrs'] : array();
			foreach ( (array) $attrs as $attr_key => $attr_value ) {
				$html .= ' ' . $attr_key . '="' . $attr_value . '"';
			}

			return $html;
		}


	}
endif;
