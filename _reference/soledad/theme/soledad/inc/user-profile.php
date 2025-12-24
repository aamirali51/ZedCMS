<?php

/**
 * Class Soledad Account Page
 */
class PenciUserProfile {
	private static $instance;

	private $endpoint;

	private $current_page;

	private $page_title;

	public static function getInstance() {
		if ( null === static::$instance ) {
			static::$instance = new static();
		}

		return static::$instance;
	}

	private function __construct() {

		if ( get_theme_mod( 'penci_disable_all_user_profiles' ) ) {
			return false;
		}

		add_action( 'after_setup_theme', function(){
			$this->setup_endpoint();
		});

		$this->setup_hook();

		add_action( 'init', function(){
			if ( !is_admin() ) {
				add_action( 'penci_account_main_content', array( $this, 'get_right_content' ) );
				add_action( 'penci_account_right_title', array( $this, 'get_right_title' ) );
			}
		});
	}

	protected function setup_hook() {
		if ( is_admin() ) {
			if ( get_theme_mod( 'limit_access_media', false ) ) {
				add_action( 'pre_get_posts', array( $this, 'users_own_attachments' ) );
				add_filter( 'ajax_query_attachments_args', array( $this, 'filter_user_media' ) );
				add_action( 'delete_attachment', array( $this, 'disable_delete_attachment' ) );
			}
		} else {
			add_action( 'wp_loaded', array( $this, 'form_handler' ), 20 );
			add_action( 'template_include', array( $this, 'add_page_template' ) );
			add_filter( 'document_title_parts', array( $this, 'account_title' ) );
		}
		add_action( 'init', array( $this, 'add_rewrite_rule' ) );
		add_action( 'after_switch_theme', array( $this, 'flush_rewrite_rules' ) );
		add_action( 'admin_init', array( $this, 'prevent_admin_access' ), 5 );
		add_filter( 'get_avatar', array( $this, 'user_avatar' ), 10, 6 );
		add_filter( 'get_avatar_url', array( $this, 'user_avatar_url' ), 10, 2 );
		add_filter( 'upload_mimes', array( $this, 'filter_mime_types' ) );
		add_action( 'admin_head-nav-menus.php', array( $this, 'add_nav_menu_meta_boxes' ) );


	}

	public function filter_mime_types( $mime_types ) {
		if ( $this->current_page === 'edit_account' ) {
			return array(
				'jpg|jpeg|jpe' => 'image/jpeg',
				'gif'          => 'image/gif',
				'png'          => 'image/png',
			);
		}

		return $mime_types;
	}

	public function load_script() {
		wp_enqueue_media();
	}

	protected function is_account_page( $wp ) {
		if ( is_user_logged_in() && ! is_admin() ) {
			if ( isset( $wp->query_vars[ $this->endpoint['account']['slug'] ] ) ) {
				add_action( 'wp_enqueue_scripts', array( $this, 'load_script' ) );

				return true;
			}
		}

		return false;
	}

	protected function is_login_page( $wp ) {
		if ( ! is_user_logged_in() && ! is_admin() ) {
			if ( isset( $wp->query_vars[ $this->endpoint['account']['slug'] ] ) ) {
				add_action( 'wp_enqueue_scripts', array( $this, 'load_script' ) );


				return true;
			}
		}

		return false;
	}

	protected function setup_endpoint() {
		$endpoint = array(
			'account'         => array(
				'slug'  => get_theme_mod( 'penci_frontend_submit_account_slug', 'account' ),
				'label' => 'my_account',
				'title' => penci_get_setting( 'penci_trans_my_account' ),
			),
			'edit_account'    => array(
				'slug'  => get_theme_mod( 'penci_frontend_submit_edit_account_slug', 'edit-account' ),
				'label' => 'edit_account',
				'title' => penci_get_setting( 'penci_trans_edit_account' ),
			),
			'change_password' => array(
				'slug'  => get_theme_mod( 'penci_frontend_submit_change_password_slug', 'change-password' ),
				'label' => 'change_password',
				'title' => penci_get_setting( 'penci_trans_change_password' ),
			),
			'like_posts'      => array(
				'slug'  => get_theme_mod( 'penci_frontend_submit_like_posts_slug', 'like-posts' ),
				'label' => 'change_password',
				'title' => penci_get_setting( 'penci_trans_like_posts' ),
			),
		);

		if ( get_theme_mod( 'penci_disable_user_profiles' ) ) {
			unset( $endpoint['edit_account'] );
			unset( $endpoint['change_password'] );
		}

		$this->endpoint = apply_filters( 'penci_account_page_endpoint', $endpoint );
	}

	protected function setup_current_page( $page ) {
		foreach ( $this->endpoint as $key => $value ) {
			if ( $page == $value['slug'] ) {
				$this->current_page = $key;
			}
		}
	}

	public function get_right_title() {
		if ( isset( $this->current_page ) ) {
			echo __( $this->endpoint[ $this->current_page ]['title'], 'soledad' );
		}
	}

	public function get_right_content() {
		if ( $this->current_page == 'edit_account' ) {
			load_template( PENCI_SOLEDAD_DIR . '/inc/templates/edit_account.php', true, $this->get_user_data() );
		} elseif ( $this->current_page == 'change_password' ) {
			load_template( PENCI_SOLEDAD_DIR . '/inc/templates/account_password.php', true, $this->get_user_data() );
		} elseif ( $this->current_page == 'like_posts' ) {
			load_template( PENCI_SOLEDAD_DIR . '/inc/templates/like_posts.php', true, $this->get_user_data() );
		}
	}

	public function add_rewrite_rule() {
		add_rewrite_endpoint( $this->endpoint['account']['slug'], EP_ROOT | EP_PAGES );
		add_rewrite_rule( '^' . $this->endpoint['account']['slug'] . '/page/?([0-9]{1,})/?$', 'index.php?&paged=$matches[1]&' . $this->endpoint['account']['slug'], 'top' );
	}

	public function flush_rewrite_rules() {
		$this->add_rewrite_rule();

		global $wp_rewrite;
		$wp_rewrite->flush_rules();
	}

	public function add_page_template( $template ) {
		global $wp;

		if ( $this->is_account_page( $wp ) ) {
			$query_vars = explode( '/', $wp->query_vars[ $this->endpoint['account']['slug'] ] );

			if ( ! empty( $query_vars[0] ) ) {
				$this->setup_current_page( $query_vars[0] );
			} else {
				wp_safe_redirect( esc_url( penci_home_url_multilang( $this->endpoint['account']['slug'] . '/' . $this->endpoint['edit_account']['slug'] ) ) );
			}

			if ( isset( $this->endpoint[ $this->current_page ]['title'] ) && $this->endpoint[ $this->current_page ]['title'] ) {
				$this->page_title = $this->endpoint[ $this->current_page ]['title'];
			}

			$template = PENCI_SOLEDAD_DIR . '/inc/templates/account.php';
		} else if ( $this->is_login_page( $wp ) ) {
			$this->page_title = penci_get_setting( 'penci_plogin_label_log_in' );

			if ( class_exists( 'WooCommerce' ) ) {
				$myaccount_page = get_option( 'woocommerce_myaccount_page_id' );
				if ( $myaccount_page ) {
					wp_safe_redirect( get_permalink( $myaccount_page ) );
				}

			} else {
				wp_safe_redirect( esc_url( home_url( '/' ) ) );
			}
		}

		return $template;
	}

	public function account_title( $title ) {
		global $wp;
		$split      = $title;
		$additional = '';

		if ( isset( $this->page_title ) ) {
			$additional = $this->page_title;
		}

		$additional = apply_filters( 'penci_account_title', $additional, $wp, $this->endpoint );

		global $wp_query;
		$split['title'] = isset( $wp_query->queried_object->post_title );

		if ( ! empty( $additional ) ) {
			$title['title'] = $additional . ' ' . $split['title'];
		}

		return $title;
	}

	public function user_avatar_url( $url, $id_or_email ) {
		$profile_picture = get_the_author_meta( 'profile_picture', $id_or_email );

		if ( $profile_picture ) {
			$image = wp_get_attachment_image_src( $profile_picture, 'penci-thumb' );

			if ( ! empty( $image ) ) {
				$url = $image[0];
			}
		}

		return $url;
	}

	public function user_avatar( $avatar, $user_id, $size, $default, $alt, $args ) {
		$profile_picture = get_the_author_meta( 'profile_picture', $user_id );

		if ( $profile_picture ) {
			$image = wp_get_attachment_image_src( $profile_picture, 'penci-thumb' );

			$class = array( 'avatar', 'avatar-' . (int) $args['size'], 'photo' );

			if ( ! $args['found_avatar'] || $args['force_default'] ) {
				$class[] = 'avatar-default';
			}

			if ( $args['class'] ) {
				if ( is_array( $args['class'] ) ) {
					$class = array_merge( $class, $args['class'] );
				} else {
					$class[] = $args['class'];
				}
			}

			if ( ! empty( $image ) ) {

				$avatar = sprintf(
					"<img alt='%s' src='%s' srcset='%s' class='%s' height='%d' width='%d' %s/>",
					esc_attr( $args['alt'] ),
					esc_url( $image[0] ),
					esc_attr( "$image[0] 2x" ),
					esc_attr( join( ' ', $class ) ),
					(int) $args['height'],
					(int) $args['width'],
					$args['extra_attr']
				);
			}
		}

		return $avatar;
	}

	protected function user_social_info() {
		$socials = array(
			"facebook"   => __( 'Facebook', 'soledad' ),
			"twitter"    => __( 'Twitter', 'soledad' ),
			"linkedin"   => __( 'Linkedin', 'soledad' ),
			"pinterest"  => __( 'Pinterest', 'soledad' ),
			"behance"    => __( 'Behance', 'soledad' ),
			"github"     => __( 'Github', 'soledad' ),
			"flickr"     => __( 'Flickr', 'soledad' ),
			"tumblr"     => __( 'Tumblr', 'soledad' ),
			"dribbble"   => __( 'Dribbble', 'soledad' ),
			"soundcloud" => __( 'Soundcloud', 'soledad' ),
			"instagram"  => __( 'Instagram', 'soledad' ),
			"vimeo"      => __( 'Vimeo', 'soledad' ),
			"youtube"    => __( 'Youtube', 'soledad' ),
			"reddit"     => __( 'Reddit', 'soledad' ),
			"vk"         => __( 'Vk', 'soledad' ),
			"weibo"      => __( 'Weibo', 'soledad' ),
			"rss"        => __( 'Rss', 'soledad' ),
			"twitch"     => __( 'Twitch', 'soledad' ),
			"url"        => __( 'Website', 'soledad' ),
		);

		if ( defined( 'PENCI_PAY_WRITER' ) ) {
			$socials['paypal_account'] = __( 'Paypal Email', 'soledad' );
		}

		return $socials;
	}

	protected function get_user_data() {
		$user_id = get_current_user_id();

		$user = array(
			'user_firstname' => trim( get_the_author_meta( 'user_firstname', $user_id ) ),
			'user_lastname'  => trim( get_the_author_meta( 'user_lastname', $user_id ) ),
			'description'    => get_the_author_meta( 'description', $user_id ),
			'photo'          => array( get_avatar_url( $user_id ) )
		);

		foreach ( $this->user_social_info() as $key => $value ) {
			$user['socials'][ $key ] = array(
				'label' => $value,
				'value' => trim( get_the_author_meta( $key, $user_id ) )
			);
		}

		return $user;
	}

	public function form_handler() {
		if ( isset( $_POST['penci-action'] ) && ! empty( $_POST['penci-account-nonce'] ) && wp_verify_nonce( $_POST['penci-account-nonce'], 'penci-account-nonce' ) ) {
			$action = sanitize_key( $_POST['penci-action'] );

			switch ( $action ) {
				case 'edit-account':
					$this->edit_account_handler();
					break;
				case 'change-password':
					$this->edit_password_handler();
					break;
			}
		}
	}

	protected function edit_account_handler() {
		$user_id      = get_current_user_id();
		$first_name   = '';
		$last_name    = '';
		$display_name = '';

		try {

			if ( ! empty( $_POST['fname'] ) ) {
				$first_name = sanitize_text_field( $_POST['fname'] );
			} else {
				throw new \Exception( __( 'First name should not be empty', 'soledad' ) );
			}

			if ( ! empty( $_POST['lname'] ) ) {
				$last_name = sanitize_text_field( $_POST['lname'] );
			}

			if ( ! empty( $_POST['dname'] ) ) {
				$display_name = sanitize_text_field( $_POST['dname'] );
			}

			do_action( 'penci_account_page_on_save' );

			$url         = sanitize_text_field( $_POST['url'] );
			$description = wp_kses_post( $_POST['description'] );

			wp_update_user( array(
				'ID'           => $user_id,
				'first_name'   => $first_name,
				'last_name'    => $last_name,
				'display_name' => $display_name,
				'description'  => $description,
				'user_url'     => $url,
			) );

			foreach ( $this->user_social_info() as $key => $value ) {
				update_user_meta( $user_id, $key, sanitize_text_field( $_POST[ $key ] ) );
			}

			if ( isset( $_POST['photo'][0] ) && '' != $_POST['photo'][0] ) {
				update_user_meta( $user_id, 'profile_picture', sanitize_text_field( $_POST['photo'][0] ) );
			} else {
				delete_user_meta( $user_id, 'profile_picture' );
			}

			$_POST['success-message'] = penci_get_setting( 'penci_trans_update_notice' );

		} catch ( \Exception $e ) {
			$_POST['error-message'] = $e->getMessage();
		}
	}

	protected function edit_password_handler() {
		$user_id = get_current_user_id();
		$user    = get_userdata( $user_id );

		try {

			if ( ! empty( $_POST['old_password'] ) ) {
				if ( ! wp_check_password( $_POST['old_password'], $user->data->user_pass, $user_id ) ) {
					throw new \Exception( penci_get_setting( 'penci_trans_password_not_valid' ) );
				}

				if ( empty( $_POST['new_password'] ) || empty( $_POST['confirm_password'] ) ) {
					throw new \Exception( penci_get_setting( 'penci_trans_password_new' ) );
				}

				if ( $_POST['new_password'] !== $_POST['confirm_password'] ) {
					throw new \Exception( penci_get_setting( 'penci_trans_password_match' ) );
				}

				$this->do_reset_password( $user, $_POST['new_password'] );

				$_POST['success-message'] = penci_get_setting( 'penci_trans_password_success' );
			} else {
				throw new \Exception( penci_get_setting( 'penci_trans_password_e' ) );
			}
		} catch ( \Exception $e ) {
			$_POST['error-message'] = $e->getMessage();
		}
	}

	protected function do_reset_password( $user, $new_pass ) {
		do_action( 'password_reset', $user, $new_pass );

		wp_set_password( $new_pass, $user->ID );

		wp_password_change_notification( $user );
	}

	public function users_own_attachments( $wp_query ) {
		if ( $wp_query->is_main_query() ) {
			global $pagenow;

			if ( 'upload.php' === $pagenow || 'media-upload.php' === $pagenow ) {
				if ( ! current_user_can( 'manage_options' ) ) {
					$wp_query->set( 'author', get_current_user_id() );
				}
			}
		}
	}

	public function filter_user_media( $query ) {
		if ( ! ( current_user_can( 'manage_options' ) ) ) {
			$query['author'] = get_current_user_id();
		}

		return $query;
	}

	public function disable_delete_attachment() {
		if ( ! current_user_can( 'manage_options' ) ) {
			exit();
		}
	}

	public function prevent_admin_access() {
		$prevent_access = ! ( defined( 'DOING_AJAX' ) && DOING_AJAX ) && ! current_user_can( 'edit_posts' ) && ! current_user_can( 'edit_pages' ) && $this->admin_post_action() ? true : false;

		if ( $prevent_access ) {
			wp_safe_redirect( esc_url( penci_home_url_multilang( '/' ) ) );
			exit;
		}
	}

	public function admin_post_action() {
		if ( strpos( $_SERVER['REQUEST_URI'], 'admin-post.php' ) !== false ) {
			return false;
		}
	}

	public function add_nav_menu_meta_boxes() {
		add_meta_box( 'penci_endpoints_nav_link', __( 'Penci Account Pages', 'soledad' ), array(
			$this,
			'nav_menu_links'
		), 'nav-menus', 'side', 'low' );
	}

	/**
	 * Output menu links.
	 */
	public function nav_menu_links() {
		// Get items from account menu.
		$endpoints = $this->endpoint;
		?>
        <div id="posttype-pcft-endpoints" class="posttypediv">
            <div id="tabs-panel-pcft-endpoints" class="tabs-panel tabs-panel-active">
                <ul id="pcft-endpoints-checklist" class="categorychecklist form-no-clear">
					<?php
					$loop_index = 999999;
					$items      = [];
					foreach ( array_slice( $endpoints, 1 ) as $key => $value ) :

						$item_url = 'editor' == $value['slug'] ? esc_url( home_url( '/' ) . $value['slug'] ) : esc_url( home_url( '/' ) . $endpoints['account']['slug'] . '/' . $value['slug'] );

						$item = new stdClass();
						$loop_index ++;
						$item->object_id        = $loop_index;
						$item->db_id            = 0;
						$item->object           = 'post_type_link';
						$item->menu_item_parent = 0;
						$item->type             = 'custom';
						$item->title            = $value['title'];
						$item->url              = $item_url;
						$item->target           = '';
						$item->attr_title       = '';
						$item->classes          = array();
						$item->xfn              = '';
						$items[]                = $item;
					endforeach;
					$walker = new Walker_Nav_Menu_Checklist( array() );
					echo walk_nav_menu_tree( array_map( 'wp_setup_nav_menu_item', $items ), 0, (object) array( 'walker' => $walker ) );
					?>
                </ul>
            </div>
            <p class="button-controls">
				<span class="list-controls">
					<a href="<?php echo esc_url( admin_url( 'nav-menus.php?page-tab=all&selectall=1#posttype-pcft-endpoints' ) ); ?>"
                       class="select-all"><?php esc_html_e( 'Select all', 'soledad' ); ?></a>
				</span>
                <span class="add-to-menu">
					<button type="submit" class="button-secondary submit-add-to-menu right"
                            value="<?php esc_attr_e( 'Add to menu', 'soledad' ); ?>"
                            name="add-post-type-menu-item"
                            id="submit-posttype-pcft-endpoints"><?php esc_html_e( 'Add to menu', 'soledad' ); ?></button>
					<span class="spinner"></span>
				</span>
            </p>
        </div>
		<?php
	}

	public function get_endpoint() {
		return $this->endpoint;
	}

	public function get_current_page() {
		return $this->endpoint[ $this->current_page ]['slug'];
	}
}

PenciUserProfile::getInstance();