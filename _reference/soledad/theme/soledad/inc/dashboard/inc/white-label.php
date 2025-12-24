<?php
if ( ! class_exists( 'Penci_White_Lable' ) ) {
	class Penci_White_Lable {
		public function __construct() {

			if ( penci_is_show_white_label_panel() ) {
				add_filter( 'mb_settings_pages', array( $this, 'options_page' ), 999 );
				add_filter( 'rwmb_meta_boxes', array( $this, 'options_meta_boxes' ), 999 );
			}

			add_filter( 'admin_body_class', array( $this, 'add_admin_body_class' ) );
			add_action( 'login_enqueue_scripts', array( $this, 'login_scripts' ) );
		}

		public function add_admin_body_class( $class ) {
			$icon = get_theme_mod( 'admin_wel_page_icon' );

			if ( $icon ) {
				$icon  = str_replace( 'fa ', '', $icon );
				$class .= ' ' . $icon;
			}

			return $class;
		}


		public function login_scripts() {

			if ( ! get_theme_mod( 'activate_white_label' ) ) {
				return;
			}

			$custom_css = '';

			$logo = get_theme_mod( 'admin_login_logo' );
			$logo = isset( $logo[0] ) ? $logo[0] : '';

			$bgimage = get_theme_mod( 'admin_login_bgimage' );
			$bgimage = isset( $bgimage[0] ) ? $bgimage[0] : '';

			$bgcolor = get_theme_mod( 'admin_login_bgcolor' );

			if ( $logo ) {
				$logo_img = wp_get_attachment_image_src( $logo, 'full' );

				$logo_img_url = isset( $logo_img[0] ) ? $logo_img[0] : '';
				$logo_img_w   = $logo_img_h = '';

				$login_logow = get_theme_mod( 'admin_login_logow' );
				$login_logoh = get_theme_mod( 'admin_login_logoh' );

				if ( $login_logow ) {
					$logo_img_w = $login_logow;
				} elseif ( isset( $logo_img[1] ) ) {
					$logo_img_w = $logo_img[1];
				}

				if ( $login_logoh ) {
					$logo_img_h = $login_logoh;
				} elseif ( isset( $logo_img[2] ) ) {
					$logo_img_h = $logo_img[2];
				}

				$custom_css .= '#login h1 a, .login h1 a {';
				$custom_css .= 'background-image: url(' . esc_url( $logo_img_url ) . ');';
				$custom_css .= $logo_img_w ? 'width: ' . esc_attr( $logo_img_w ) . 'px;' : '';
				$custom_css .= $logo_img_h ? 'height: ' . esc_attr( $logo_img_h ) . 'px;' : '';
				$custom_css .= 'background-size: ' . esc_attr( $logo_img_w ) . 'px ' . esc_attr( $logo_img_h ) . 'px;';

				if ( $logo_img_w > 320 ) {
					$custom_css .= 'margin-left:-' . intval( ( $logo_img_w - 320 ) / 2 ) . 'px;';
				}

				$custom_css .= '}';
			}

			if ( $bgimage ) {
				$bgimage_img = wp_get_attachment_image_src( $bgimage, 'full' );
				if ( isset( $bgimage_img[0] ) && $bgimage_img[0] ) {
					$custom_css .= 'body{  background-image: url(' . esc_url( $bgimage_img[0] ) . ') !important;
                    background-size: cover !important;background-position: center center !important;background-repeat: no-repeat !important;background-attachment: fixed !important; }';
				}
			}

			if ( $bgcolor ) {
				$custom_css .= 'body{ background-color:' . $bgcolor . ' !important; }';
			}

			// From
			$flogin_bgimage        = get_theme_mod( 'penci_flogin_bgimage' );
			$flogin_bgcolor        = get_theme_mod( 'penci_flogin_bgcolor' );
			$flogin_padding        = get_theme_mod( 'penci_flogin_padding' );
			$flogin_shadow         = get_theme_mod( 'penci_flogin_shadow' );
			$flogin_field_bg       = get_theme_mod( 'penci_flogin_field_bg' );
			$flogin_field_borcolor = get_theme_mod( 'penci_flogin_field_borcolor' );
			$flogin_field_color    = get_theme_mod( 'penci_flogin_field_color' );
			$flogin_label_color    = get_theme_mod( 'penci_flogin_label_color' );

			$flogin_btn_bgcolor  = get_theme_mod( 'penci_flogin_btn_bgcolor' );
			$flogin_btn_color    = get_theme_mod( 'penci_flogin_btn_color' );
			$flogin_btn_bocolor  = get_theme_mod( 'penci_flogin_btn_bocolor' );
			$flogin_btn_hbgcolor = get_theme_mod( 'penci_flogin_btn_hbgcolor' );
			$flogin_btn_hcolor   = get_theme_mod( 'penci_flogin_btn_hcolor' );
			$flogin_btn_hbocolor = get_theme_mod( 'penci_flogin_btn_hbocolor' );

			$flogin_link_color  = get_theme_mod( 'penci_flogin_link_color' );
			$flogin_link_hcolor = get_theme_mod( 'penci_flogin_link_hcolor' );


			if ( $flogin_bgimage ) {

				$flogin_bgimage    = isset( $flogin_bgimage[0] ) ? $flogin_bgimage[0] : '';
				$flogin_bgimageimg = wp_get_attachment_image_src( $flogin_bgimage, 'full' );

				if ( isset( $flogin_bgimageimg[0] ) && $flogin_bgimageimg[0] ) {
					$custom_css .= '#loginform{  background-image: url(' . esc_url( $flogin_bgimageimg[0] ) . ') !important;
                    background-size: cover !important;background-position: center center !important;background-repeat: no-repeat !important;background-attachment: fixed !important; }';
				}
			}
			if ( $flogin_bgcolor ) {
				$custom_css .= '#loginform{ background-color:' . $flogin_bgcolor . '}';
			}
			if ( $flogin_padding ) {
				$custom_css .= '#loginform{ padding:' . $flogin_padding . '}';
			}
			if ( $flogin_shadow ) {
				$custom_css .= '#loginform{ box-shadow:' . $flogin_shadow . '}';
			}

			// Field
			$flogin_field_css = '';
			if ( $flogin_field_bg ) {
				$flogin_field_css .= 'background-color:' . $flogin_field_bg . ' !important;';
			}
			if ( $flogin_field_borcolor ) {
				$flogin_field_css .= 'border-color:' . $flogin_field_borcolor . ' !important;';
			}
			if ( $flogin_field_color ) {
				$flogin_field_css .= 'color:' . $flogin_field_color . ' !important;';
			}
			if ( $flogin_field_css ) {
				$custom_css .= '.login form .input, .login input[type="text"]{ ' . $flogin_field_css . '}';
			}


			if ( $flogin_label_color ) {
				$custom_css .= '.login #loginform label{ color:' . $flogin_label_color . '}';
			}

			// Button
			$flogin_btn_css = '';
			if ( $flogin_btn_bgcolor ) {
				$flogin_btn_css .= 'background-color:' . $flogin_btn_bgcolor . ';';
			}
			if ( $flogin_btn_color ) {
				$flogin_btn_css .= 'color:' . $flogin_btn_color . ';';
			}
			if ( $flogin_btn_bocolor ) {
				$flogin_btn_css .= 'border-color:' . $flogin_btn_bocolor . ';';
			}
			if ( $flogin_btn_css ) {
				$custom_css .= '.wp-core-ui #loginform .button-primary{ ' . $flogin_btn_css . 'text-shadow: none; box-shadow:none; }';
			}

			$flogin_btn_hover_css = '';
			if ( $flogin_btn_hbgcolor ) {
				$flogin_btn_hover_css .= 'background-color:' . $flogin_btn_hbgcolor . ';';
			}
			if ( $flogin_btn_hcolor ) {
				$flogin_btn_hover_css .= 'color:' . $flogin_btn_hcolor . ';';
			}
			if ( $flogin_btn_hbocolor ) {
				$flogin_btn_hover_css .= 'border-color:' . $flogin_btn_hbocolor . ';';
			}
			if ( $flogin_btn_hover_css ) {
				$custom_css .= '.wp-core-ui  #loginform  .button-primary.focus, .wp-core-ui  #loginform  .button-primary.hover, .wp-core-ui  #loginform  .button-primary:focus, .wp-core-ui  #loginform  .button-primary:hover{ ' . $flogin_btn_hover_css . '}';
			}

			if ( $flogin_link_color ) {
				$custom_css .= '.login #backtoblog a, .login #nav a{ color:' . $flogin_link_color . ' !important}';
			}
			if ( $flogin_link_hcolor ) {
				$custom_css .= '.login #backtoblog a:hover, .login #nav a:hover, .login h1 a:hover{ color:' . $flogin_link_hcolor . '  !important}';
			}

			if ( $custom_css ) {
				echo '<style type="text/css">' . $custom_css . '</style>';
			}
		}

		public function options_page( $settings_pages ) {
			$settings_pages[] = array(
				'id'          => 'soledad_white_label',
				'option_name' => 'theme_mods_' . get_stylesheet(),
				'menu_title'  => esc_html__( 'White Label ', 'soledad' ),
				'parent'      => 'soledad_dashboard_welcome',
				'columns'     => 1,
			);

			return $settings_pages;
		}

		public function options_meta_boxes( $meta_boxes ) {

			$white_label_filed = array(
				array(
					'id'   => 'activate_white_label',
					'name' => __( 'Activate White Label?', 'soledad' ),
					'type' => 'checkbox',
				),
				array(
					'name'             => esc_html__( 'WordPress Login Logo', 'soledad' ),
					'id'               => 'admin_login_logo',
					'type'             => 'image_advanced',
					'max_file_uploads' => 1,
					'max_status'       => 'false',
				),
				array(
					'name'   => esc_html__( 'Custom width for WordPress Login Logo', 'soledad' ),
					'desc'   => esc_html__( 'Numeric value only, unit is pixel', 'soledad' ),
					'id'     => 'admin_login_logow',
					'type'   => 'number',
					'std'    => '',
					'hidden' => array( 'button2_type', '=', 'simple' )
				),
				array(
					'name'   => esc_html__( 'Custom height for WordPress Login Logo', 'soledad' ),
					'desc'   => esc_html__( 'Numeric value only, unit is pixel', 'soledad' ),
					'id'     => 'admin_login_logoh',
					'type'   => 'number',
					'std'    => '',
					'hidden' => array( 'button2_type', '=', 'simple' )
				),
				array(
					'type' => 'heading',
					'name' => esc_html__( 'Background Options', 'soledad' ),
				),
				array(
					'name'             => esc_html__( 'Custom background image for page login', 'soledad' ),
					'id'               => 'admin_login_bgimage',
					'type'             => 'image_advanced',
					'max_file_uploads' => 1,
					'max_status'       => 'false',
				),
				array(
					'name' => esc_html__( 'Custom background color for page login', 'soledad' ),
					'id'   => 'admin_login_bgcolor',
					'type' => 'color',
				),

				array(
					'type' => 'heading',
					'name' => esc_html__( 'From options', 'soledad' ),
				),
				array(
					'name'             => esc_html__( 'Form background image', 'soledad' ),
					'id'               => 'penci_flogin_bgimage',
					'type'             => 'image_advanced',
					'max_file_uploads' => 1,
					'max_status'       => 'false',

					'desc' => __( 'This will change the background image property of login form.', 'soledad' ),

				),
				array(
					'name' => esc_html__( 'Form background color', 'soledad' ),
					'id'   => 'penci_flogin_bgcolor',
					'type' => 'color',
					'desc' => __( 'This will change the background color property.', 'soledad' ),
				),
				array(
					'id'   => 'penci_flogin_padding',
					'type' => 'text',
					'std'  => '26px 24px 46px;',
					'name' => esc_html__( 'Form padding', 'soledad' ),
					'desc' => __( 'This will change the padding property. Example: 26px 24px 46px 30px or none', 'soledad' ),
				),
				array(
					'id'   => 'penci_flogin_shadow',
					'type' => 'text',
					'std'  => '0 1px 3px rgba(0,0,0,.13)',
					'name' => esc_html__( 'Form shadow', 'soledad' ),
					'desc' => __( 'This will change the form\'s shadow property. Example: 0 1px 3px rgba(0,0,0,.13). Fill <strong>none</strong> if you want to remove shadow.', 'soledad' ),
				),
				array(
					'name' => esc_html__( 'Form field background', 'soledad' ),
					'id'   => 'penci_flogin_field_bg',
					'type' => 'color',
				),
				array(
					'name' => esc_html__( 'Form field border color', 'soledad' ),
					'id'   => 'penci_flogin_field_borcolor',
					'type' => 'color',
				),
				array(
					'name' => esc_html__( 'Form field color', 'soledad' ),
					'id'   => 'penci_flogin_field_color',
					'type' => 'color',
				),
				array(
					'name' => esc_html__( 'Form label color', 'soledad' ),
					'id'   => 'penci_flogin_label_color',
					'type' => 'color',
				),
				array(
					'name' => esc_html__( 'Submit button background color', 'soledad' ),
					'id'   => 'penci_flogin_btn_bgcolor',
					'type' => 'color',
				),
				array(
					'name' => esc_html__( 'Submit button text color', 'soledad' ),
					'id'   => 'penci_flogin_btn_color',
					'type' => 'color',
				),
				array(
					'name' => esc_html__( 'Submit button border color', 'soledad' ),
					'id'   => 'penci_flogin_btn_bocolor',
					'type' => 'color',
				),
				array(
					'name' => esc_html__( 'Submit button background  hover color', 'soledad' ),
					'id'   => 'penci_flogin_btn_hbgcolor',
					'type' => 'color',
				),
				array(
					'name' => esc_html__( 'Submit button text hover color', 'soledad' ),
					'id'   => 'penci_flogin_btn_hcolor',
					'type' => 'color',
				),
				array(
					'name' => esc_html__( 'Submit button border hover color', 'soledad' ),
					'id'   => 'penci_flogin_btn_hbocolor',
					'type' => 'color',
				),
				array(
					'name' => esc_html__( 'Link color ', 'soledad' ),
					'id'   => 'penci_flogin_link_color',
					'type' => 'color',
					'desc' => __( 'This will change the text color of links that are underneath the login form', 'soledad' ),
				),
				array(
					'name' => esc_html__( 'Link color hover', 'soledad' ),
					'id'   => 'penci_flogin_link_hcolor',
					'type' => 'color',
					'desc' => __( 'This will change the text color of links, that are underneath the login form, on hover', 'soledad' ),
				),
				array( 'type' => 'divider' ),
				array(
					'id'   => 'admin_wel_page_icon',
					'type' => 'text',
					'name' => esc_html__( 'Theme Icon', 'soledad' ),
					'desc' => __( 'Fill the icon class you want to display here. Check list icons <a rel="nofollow" href="https://fontawesome.com/v4.7.0/icons/" target="_blank">here</a>. Example fill: <strong>fa-book</strong>', 'soledad' ),
				),
				array(
					'id'   => 'admin_wel_page_title',
					'type' => 'text',
					'name' => esc_html__( 'Theme Name', 'soledad' ),
					'desc' => __( 'The theme name will display <a rel="nofollow" href="https://soledad.pencidesign.net/soledad-document/images/white-lable.png" target="_blank">here</a> - So, please do not fill it too long.', 'soledad' ),
				),
				array(
					'id'   => 'admin_wel_page_sname',
					'type' => 'text',
					'name' => esc_html__( 'Theme Short Name', 'soledad' ),
				),
				array(
					'id'   => 'admin_wel_page_author',
					'type' => 'text',
					'name' => esc_html__( 'Theme Author', 'soledad' ),
				),
				array(
					'name'    => esc_html__( 'Welcome Page Text', 'soledad' ),
					'id'      => 'admin_wel_page_text',
					'type'    => 'wysiwyg',
					'raw'     => false,
					'options' => array(
						'textarea_rows' => 4,
						'teeny'         => true,
					),
				)
			);

			$list_administrator = $this->get_list_users();

			if ( $list_administrator ) {
				$white_label_filed[] = array( 'type' => 'divider' );
				$white_label_filed[] = array(
					'name'    => esc_html__( 'Show this screen settings panel only to this user', 'soledad' ),
					'id'      => 'show_white_lable_user',
					'type'    => 'select',
					'options' => $list_administrator,
				);
			}

			$meta_boxes[] = array(
				'id'             => 'soledad_wp_banding',
				'title'          => esc_html__( 'WordPress Branding', 'soledad' ),
				'settings_pages' => 'soledad_white_label',
				'fields'         => $white_label_filed
			);

			return $meta_boxes;
		}

		public function get_list_users() {
			$current_user = wp_get_current_user();

			$user_query = new WP_User_Query( array(
				'number'  => 100,
				'orderby' => 'registered',
				'order'   => 'ASC',
				'role'    => 'Administrator',
			) );

			$output = array( '' => esc_html__( 'Administrator Users', 'soledad' ) );

			$get_results = $user_query->get_results();

			if ( ! empty( $get_results ) ) {
				foreach ( $get_results as $user ) {

					$display_name = isset( $user->data->display_name ) ? $user->data->display_name : '';
					$user_login   = isset( $user->data->user_login ) ? $user->data->user_login : '';

					if ( ! $display_name ) {
						continue;
					}

					$label = '';

					if ( $user->data->ID === $current_user->get( 'ID' ) ) {
						$label = esc_html__( 'Me: ', 'soledad' );
					}

					$label .= $display_name . '( ' . $user_login . ' )';


					$output[ $user->data->ID ] = $label;
				}
			}

			return $output;
		}
	}
}

if ( ! function_exists( 'penci_is_show_white_label_panel' ) ) {
	function penci_is_show_white_label_panel() {
		$output = true;

		$id_show = get_theme_mod( 'show_white_lable_user' );
		$user_id = get_current_user_id();

		if ( ! $id_show || ( $user_id == $id_show ) ) {
			$output = true;
		} elseif ( $id_show && ( $user_id != $id_show ) ) {
			$output = false;
		}

		return $output;
	}
}

new Penci_White_Lable();
