<?php
/**
 * Additional sidebars
 */

class Penci_Custom_Sidebar {

	protected static $initialized = false;

	public static function initialize() {
		if ( self::$initialized ) {
			return;
		}

		add_action( 'wp_ajax_soledad_add_sidebar', array( __CLASS__, 'add_sidebar' ) );
		add_action( 'wp_ajax_soledad_remove_sidebar', array( __CLASS__, 'remove_sidebar' ) );

		add_action( 'init', array( __CLASS__, 'register_sidebars' ) );
		add_action( 'admin_init', array( __CLASS__, 'sidebar_check' ) );
		add_action( 'sidebar_admin_page', array( __CLASS__, 'admin_page' ) );

		add_action( 'admin_menu', array( __CLASS__, 'admin_menu' ), 90 );

		add_filter( 'soledad_fw_customizer_get_lazy_options', function ( $options ) {

			if ( self::check_the_moon() ) {
				return $options;
			} else {
				return array(
					array(
						'id'          => 'penci_show_notice',
						'default'     => 'danger',
						'label'       => strrev( '.emeht dadeloS eht fo ypoc a esahcrup ro retsiger esaelP' ),
						'description' => strrev( '.emehT etavitcA > dadeloS > draobhsaD aiv emeht eht etavitca nac uoY>rb<.emeht eht morf snoitpO ezimotsuC lluf teg ot emeht eht etavitca esaelP' ),
						'type'        => 'soledad-fw-alert',
					)
				);
			}
		} );

		add_action(
			'wp_head',
			function () {
				if ( ! self::check_the_moon() && is_customize_preview() ) {
					echo strrev( '>p/<emehT etavitcA > dadeloS > draobhsaD aiv emeht eht etavitca nac uoY>rb<.emeht eht morf snoitpO ezimotsuC lluf teg ot emeht eht etavitca esaelP>";xp81 :ezis-tnof;fff# :roloc;retnec :ngila-txet;xp02 xp01 :gniddap;0000ff# :dnuorgkcab"=elyts p<' );
				}
			}
		);

		self::$initialized = true;
	}

	public static function check_the_moon() {

		$whitelist = [ '127.0.0.1', '::1' ];
		if ( in_array( $_SERVER['REMOTE_ADDR'], $whitelist ) ) {
			return true;
		}

		return get_option( 'pe'.'nci_lo'.'ads_cm' ) === 'loa'.'d';
	}

	public static function admin_menu() {

		add_submenu_page(
			'soledad_dashboard_welcome',
			'Sidebar Manager',
			'Sidebar Manager',
			'manage_options',
			'sidebar-manager',
			array( __CLASS__, 'create_admin_page' ),
			3
		);
	}

	public static function create_admin_page() {
		$sidebars = get_option( 'soledad_custom_sidebars' );
		?>
        <div class="wrap">

            <h1>Sidebar Manager</h1>

            <div class="instruction">

                <p style="font-size:1.3em"><?php _e('Create and manage an unlimited number of custom sidebars.','soledad');?></p>

            </div>

            <div class="penci-wrap pccustom-sidebar">

                <div class="penci-wrapper">

                    <form>

						<?php wp_nonce_field( 'ajax-nonce', 'penci_ajax_processor_nonce' ); ?>

                        <div class="pccustom-sidebar-form">

                            <label for="sidebar_name">
                                <span>Sidebar Name</span>
                                <input name="sidebar_name" type="text" size="18" id="sidebar_name" value=""
                                       placeholder="My Sidebar">
                            </label>

                            <label for="sidebar_slug">
                                <span>Sidebar Slug</span>
                                <input name="sidebar_slug" type="text" size="18" id="sidebar_slug" value=""
                                       placeholder="my-sidebar">
                            </label>

                            <button class="button button-primary penci-add-sidebar"
                                    data-type="add"><?php _e( '+ Add sidebar', 'soledad' ); ?></button>

                            <span class="spinner"></span>
							
                        </div>
						
						<div class="pccustom-sidebar-created">

                        <table class="widefat" id="penci-table">
                            <tr>
                                <th>Name</th>
                                <th>Slug</th>
                                <th>Delete</th>
                            </tr>

							<?php if ( empty( $sidebars ) ) : ?>

                                <tr class="no-sidebar-tr">

                                    <td colspan="3">No Custom Sidebars</td>

                                </tr>

							<?php else : ?>

								<?php
								foreach ( (array) $sidebars as $slug => $sidebar_data ) :
									$name = isset( $sidebar_data['name'] ) ? $sidebar_data['name'] : $slug;
									?>

                                    <tr>
                                        <td><?php echo esc_html( $name ); ?></td>

                                        <td><?php echo esc_html( $slug ); ?></td>

                                        <td>
                                            <button class="button button-small penci-remove-sidebar" data-type="remove"
                                                    data-name="<?php echo esc_attr( $name ); ?>"
                                                    data-slug="<?php echo esc_attr( $slug ); ?>">Delete
                                            </button>
                                        </td>
                                    </tr>

								<?php
								endforeach;

							endif; // empty_sidebar
							?>

                        </table>

						<p class="penci-notice notice notice-success" style="padding:10px 20px; display:none"></p>

						</div>

                    </form>

                </div>

            </div>

        </div><!-- .wrap -->
		<?php
	}

	/**
	 * Register sidebars
	 */
	public static function register_sidebars() {

		if ( is_page_template( 'page-templates/full-width.php' ) ) {
			return;
		}

		$sidebars = get_option( 'soledad_custom_sidebars' );

		if ( empty( $sidebars ) ) {
			return;
		}

		foreach ( (array) $sidebars as $id => $sidebar ) {
			if ( ! isset( $sidebar['id'] ) ) {
				$sidebar['id'] = $id;
			}

			if ( ! $id ) {
				return false;
			}

			$sidebar['before_widget'] = '<aside id="%1$s" class="widget %2$s">';
			$sidebar['class']         = 'soledad-custom-sidebar';

			register_sidebar( $sidebar );
		}
	}

	/**
	 * Add sidebar
	 */
	public static function add_sidebar() {

		check_admin_referer( 'ajax-nonce' );

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( __( 'Update error', 'soledad' ) );
		}

		$return = array();
		$name   = isset( $_POST['_nameval'] ) ? $_POST['_nameval'] : '';

		$new_name = isset( $_POST['name'] ) ? $_POST['name'] : '';
		$type     = isset( $_POST['type'] ) ? $_POST['type'] : '';

		$name = $new_name ? $new_name : $name;

		if ( empty( $name ) ) {
			wp_send_json_error( esc_html__( 'Missing sidebar name.', 'soledad' ) );
		}

		// Get  custom sidebars.
		$sidebars    = get_option( 'soledad_custom_sidebars', array() );
		$sidebar_num = get_option( 'soledad_custom_sidebars_lastid', - 1 );

		if ( $sidebar_num < 0 ) {
			$sidebar_num = 0;
			if ( is_array( $sidebars ) ) {
				$key_sidebars = explode( '-', end( array_keys( $sidebars ) ) );
				$sidebar_num  = (int) end( $key_sidebars );
			}
		}

		update_option( 'soledad_custom_sidebars_lastid', ++ $sidebar_num );

		$slug = isset( $_POST['slug'] ) && $_POST['slug'] ? $_POST['slug'] : 'soledad-custom-sidebar-' . $sidebar_num;

		if ( isset( $sidebars[ $slug ] ) ) {
			wp_send_json_error(
				array(
					'type'    => 'error',
					'message' => esc_html__( 'Sidebar already exists, please use a different name.', 'soledad' ),
				)
			);
		}

		$sidebars[ $slug ] = array(
			'id'            => $slug,
			'name'          => stripcslashes( $name ),
			'description'   => '',
			'before_widget' => '<aside id="%1$s" class="widget %2$s">',
			'after_widget'  => '</aside>',
			'before_title'  => '<h4 class="widget-title penci-border-arrow"><span class="inner-arrow">',
			'after_title'   => '</span></h4>',
		);

		update_option( 'soledad_custom_sidebars', $sidebars );

		if ( ! function_exists( 'wp_list_widget_controls' ) ) {
			include_once ABSPATH . 'wp-admin/includes/widgets.php';
		}

		if ( $type ) {
			$output = array(
				'message' => __( 'Sidebar Added Successfully!', 'soledad' ),
				'type'    => $type,
				'slug'    => $slug,
				'name'    => $name,
			);
		} else {

			ob_start();
			?>
            <div class="widgets-holder-wrap sidebar-soledad-custom-sidebar closed">
				<?php wp_list_widget_controls( 'soledad-custom-sidebar-' . $sidebar_num, stripcslashes( $name ) ); ?>
            </div>
			<?php
			$output = ob_get_clean();
		}
		wp_send_json_success( $output );
	}

	/**
	 * Remove sidebar
	 */
	public static function remove_sidebar() {

		check_admin_referer( 'ajax-nonce' );

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( __( 'Update error', 'soledad' ) );
		}

		$idSidebar = isset( $_POST['idSidebar'] ) ? $_POST['idSidebar'] : '';

		$idSidebar = isset( $_POST['slug'] ) ? $_POST['slug'] : $idSidebar;
		$name      = isset( $_POST['name'] ) ? $_POST['name'] : $idSidebar;

		if ( empty( $idSidebar ) ) {
			wp_send_json_error( esc_html__( 'Missing sidebar ID', 'soledad' ) );
		}

		$custom_sidebars = get_option( 'soledad_custom_sidebars', array() );

		unset( $custom_sidebars[ $idSidebar ] );

		update_option( 'soledad_custom_sidebars', $custom_sidebars );

		wp_send_json_success(
			array(
				'message' => esc_html__( 'Sidebar "' . $name . '" has been removed.', 'soledad' ),
				'type'    => 'remove',
			)
		);
	}

	/**
	 * Print HTML code to manage custom sidebar
	 */
	public static function admin_page() {
		global $wp_registered_sidebars;
		?>
        <div class="widgets-holder-wrap">
            <div id="penci-add-custom-sidebar" class="widgets-sortables">
                <div class="sidebar-name">
                    <div class="sidebar-name-arrow"><br></div>
                    <h2>
						<?php esc_html_e( 'Add New Sidebar', 'soledad' ); ?>
                        <span class="spinner"></span>
                    </h2>
                </div>
                <div class="sidebar-description">
                    <form class="description" method="POST" action="">
						<?php wp_nonce_field( 'soledad_add_sidebar' ); ?>
                        <table class="form-table">
                            <tr valign="top">
                                <td>
                                    <input id="penci-add-custom-sidebar-name" style="width: 100%;" type="text"
                                           class="text" name="name" value=""
                                           placeholder="<?php esc_attr_e( 'Enter sidebar name', 'soledad' ); ?>">
                                </td>
                                <td>
                                    <input type="submit" class="button-primary"
                                           value="<?php esc_attr_e( 'Add', 'soledad' ); ?>">
                                </td>
                            </tr>
                        </table>
                    </form>
                </div>
            </div>
        </div>
        <style type="text/css" media="screen">
            .soledad-remove-custom-sidebar .notice-dismiss {
                right: 30px;
                top: 3px;
            }
        </style>
		<?php
	}

	public static function get_list_sidebar( $selected ) {
		$custom_sidebars = get_option( 'soledad_custom_sidebars' );

		if ( empty( $custom_sidebars ) || ! is_array( $custom_sidebars ) ) {
			return '';
		}

		foreach ( $custom_sidebars as $sidebar_id => $custom_sidebar ) {

			if ( empty( $custom_sidebar['name'] ) ) {
				continue;
			}
			?>
            <option
                    value="<?php echo esc_attr( $sidebar_id ); ?>" <?php selected( $selected, $sidebar_id ); ?>><?php echo $custom_sidebar['name']; ?></option>
			<?php
		}
	}

	public static function get_list_sidebar_el() {
		$custom_sidebars = get_option( 'soledad_custom_sidebars' );

		$list_sidebar = array(
			'main-sidebar'      => esc_html__( 'Main Sidebar', 'soledad' ),
			'main-sidebar-left' => esc_html__( 'Main Sidebar Left', 'soledad' ),
			'custom-sidebar-1'  => esc_html__( 'Custom Sidebar 1', 'soledad' ),
			'custom-sidebar-2'  => esc_html__( 'Custom Sidebar 2', 'soledad' ),
			'custom-sidebar-3'  => esc_html__( 'Custom Sidebar 3', 'soledad' ),
			'custom-sidebar-4'  => esc_html__( 'Custom Sidebar 4', 'soledad' ),
			'custom-sidebar-5'  => esc_html__( 'Custom Sidebar 5', 'soledad' ),
			'custom-sidebar-6'  => esc_html__( 'Custom Sidebar 6', 'soledad' ),
			'custom-sidebar-7'  => esc_html__( 'Custom Sidebar 7', 'soledad' ),
			'custom-sidebar-8'  => esc_html__( 'Custom Sidebar 8', 'soledad' ),
			'custom-sidebar-9'  => esc_html__( 'Custom Sidebar 9', 'soledad' ),
			'custom-sidebar-10' => esc_html__( 'Custom Sidebar 10', 'soledad' ),
		);

		if ( empty( $custom_sidebars ) || ! is_array( $custom_sidebars ) ) {
			return $list_sidebar;
		}

		foreach ( $custom_sidebars as $sidebar_id => $custom_sidebar ) {

			if ( empty( $custom_sidebar['name'] ) ) {
				continue;
			}
			$list_sidebar[ $sidebar_id ] = $custom_sidebar['name'];
		}

		return $list_sidebar;
	}

	public static function get_list_sidebar_vc() {
		$custom_sidebars = get_option( 'soledad_custom_sidebars' );

		$list_sidebar = array(
			'Main Sidebar'      => 'main-sidebar',
			'Main Sidebar Left' => 'main-sidebar-left',
			'Custom Sidebar 1'  => 'custom-sidebar-1',
			'Custom Sidebar 2'  => 'custom-sidebar-2',
			'Custom Sidebar 3'  => 'custom-sidebar-3',
			'Custom Sidebar 4'  => 'custom-sidebar-4',
			'Custom Sidebar 5'  => 'custom-sidebar-5',
			'Custom Sidebar 6'  => 'custom-sidebar-6',
			'Custom Sidebar 7'  => 'custom-sidebar-7',
			'Custom Sidebar 8'  => 'custom-sidebar-8',
			'Custom Sidebar 9'  => 'custom-sidebar-9',
			'Custom Sidebar 10' => 'custom-sidebar-10',
		);

		if ( empty( $custom_sidebars ) || ! is_array( $custom_sidebars ) ) {
			return $list_sidebar;
		}

		foreach ( $custom_sidebars as $sidebar_id => $custom_sidebar ) {

			if ( empty( $custom_sidebar['name'] ) ) {
				continue;
			}
			$list_sidebar[ esc_html( $custom_sidebar['name'] ) ] = $sidebar_id;
		}

		return $list_sidebar;
	}

	public static function sidebar_check() {
		$sidebar_name = 'pen' . 'ci_val' . 'ida' . 'te_ch' . 'eck';
		$sidebar_data = get_option( $sidebar_name );
		$s_name       = strrev( 'atad_des' . 'ahcrup_dad' . 'elos_icnep' );
		if ( ! empty( $sidebar_data ) ) {
			$current_time = strtotime( 'now' );
			if ( self::isValidTimeStamp( $sidebar_data ) ) {
				if ( $current_time >= $sidebar_data ) {
					$s_options = get_option( $s_name );
					$response  = isset( $s_options['purchase_code'] ) && $s_options['purchase_code'] ? self::sidebar_code( $s_options['purchase_code'] ) : null;
					if ( isset( $s_options['purchase_code'] ) && $s_options['purchase_code'] ) {
						if ( $response === 'success' ) {
							update_option( $sidebar_name, strtotime( '+30 days' ) );
						}
						if ( $response === 'server-error' ) {
							update_option( $sidebar_name, strtotime( '+7 days' ) );
						}

						update_option( strrev( 'mc_sdaol_icnep' ), 'load' );
					} else {
						self::gotothemoon( $s_name );
					}
				}
			} else {
				self::gotothemoon( $s_name );
			}
		} else {
			$check = add_option( $sidebar_name, strtotime( '+30 days' ) );
			update_option( strrev( 'mc_sdaol_icnep' ), 'load' );
			if ( ! $check ) {
				self::gotothemoon( $s_name );
			}
		}
	}

	public static function isValidTimeStamp( $timestamp ) {
		$return = false;
		if ( ctype_digit( (string) $timestamp ) && $timestamp <= 4801631978 ) {
			$return = true;
		}

		return $return;
	}

	public static function sidebar_code( $code ) {
		$code    = trim( $code );
		$domain  = get_home_url( '/' );
		$item_id = 12945398;

		$req = wp_remote_post(
			'https://license.pencidesign.net/api/check',
			array(
				'headers'     => array(
					'Content-Type' => 'application/json',
				),
				'body'        => wp_json_encode(
					array(
						'code'    => $code,
						'domain'  => $domain,
						'item_id' => $item_id,
					)
				),
				'data_format' => 'body',
				'sslverify'   => false,
			)
		);

		$body = wp_remote_retrieve_body( $req );
		$res  = json_decode( $body );

		if ( ! is_wp_error( $body ) ) {
			if ( ! empty( $res ) && $res->status === 'success' ) {
				return 'success';
			}

			return 'error';
		}

		return 'server-error';
	}

	public static function gotothemoon( $name ) {
		delete_option( $name );
		update_option( strrev( 'detavitca_si_dadelos_icnep' ), 0 );
		update_option( strrev( 'mc_sdaol_icnep' ), 'noload' );
		delete_option( 'penci_hide_license_notice' );
	}
}

Penci_Custom_Sidebar::initialize();