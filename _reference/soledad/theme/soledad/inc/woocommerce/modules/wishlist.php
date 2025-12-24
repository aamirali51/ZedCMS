<?php

class penci_product_wishlist {

	protected static $instance = null;

	private $save_me = '';

	function __construct() {

		if ( get_theme_mod( 'penci_woocommerce_wishlist_only_logged_in', false ) && ! is_user_logged_in() ) {
			return false;
		}

		if ( get_theme_mod( 'penci_woocommerce_wishlist_show', true ) ) {
			add_action( 'penci_loop_product_buttons', array( $this, 'wishlist_button' ) );
		}

		add_action( 'penci_single_product_extra_buttons', array( $this, 'wishlist_button' ), 5 );

		add_action( 'wp_ajax_nopriv_penci_add_to_wishlist', array( $this, 'add_to_wishlist' ) );
		add_action( 'wp_ajax_penci_add_to_wishlist', array( $this, 'add_to_wishlist' ) );

		add_action( 'wp_ajax_nopriv_penci_remove_wishlist_item', array( $this, 'remove_wishlist_item' ) );
		add_action( 'wp_ajax_penci_remove_wishlist_item', array( $this, 'remove_wishlist_item' ) );

		add_action( 'penci_header_extra_icons', array( $this, 'header_icon' ), 15 );

		add_action( 'penci_current_wishlist', array( $this, 'current_wishlist' ) );

		add_action( 'penci_before_product_loop', array( $this, 'wistlist_remove_icon' ), 10 );

		add_shortcode( 'penci_wishlist', array( $this, 'wishlist_content_shortcode' ) );

		$this->save_me = 'penci_soledad_wishlist_products';
		if ( is_multisite() ) {
			$this->save_me .= '_' . get_current_blog_id();
		}

		add_action( 'admin_notices', array( $this, 'wishlist_admin_notice' ) );

		add_action( 'penci_footer_nav_wishlist_item', array( $this, 'wishlist_footer_nav' ) );

		add_action( 'display_post_states', array( $this, 'admin_wishlist_post_state' ), 10, 2 );

		add_filter( 'the_content', array( $this, 'wishlist_page_content' ) );

		add_filter(
			'body_class',
			function ( $class ) {
				$wishlist_page = get_theme_mod( 'penci_woocommerce_wishlist_page' );

				if ( $wishlist_page && is_page( $wishlist_page ) ) {
					$class[] = 'elementor-page penci-wishlist-page';
				}

				return $class;
			},
			10,
			1
		);
	}

	public static function instance() {
		if ( null == self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function wishlist_footer_nav() {
		echo '<span class="current-item">' . $this->current_wishlist( true ) . '</span>';
	}

	public function current_wishlist( $return = false ) {
		$number        = $this->get_user_wishlist( $this->save_me );
		$product_check = array();

		if ( $number && is_array( $number ) ) {
			$product_check = get_posts(
				array(
					'post_type'      => 'product',
					'post__in'       => $number,
					'posts_per_page' => - 1,
				)
			);
		}

		$number = count( $product_check );

		if ( $return ) {
			return $number;
		} else {
			echo $number;
		}
	}

	public function get_user_wishlist( $name ) {

		$save_wishlist = $this->get_data( $name );
		$save_wishlist = unserialize( base64_decode( $save_wishlist ), array( 'allowed_classes' => false ) );

		return $save_wishlist;
	}

	public function get_data( $name ) {

		if ( is_user_logged_in() ) {
			$data = get_user_meta( get_current_user_id(), $name, true );
		} else {
			$data = $this->get_cookie( $name );
		}

		return $data;
	}

	public function get_cookie( $name ) {
		return isset( $_COOKIE[ $name ] ) ? sanitize_text_field( wp_unslash( $_COOKIE[ $name ] ) ) : false;
	}

	public function wishlist_button() {

		$class = '';
		$href  = '?add-to-wishlist=' . get_the_id();
		$text  = penci_woo_translate_text( 'penci_woo_trans_adtwilsh' );

		$save_wisthlist = $this->get_user_wishlist( $this->save_me );

		if ( isset( $save_wisthlist ) && $save_wisthlist && in_array( get_the_ID(), $save_wisthlist ) ) {
			$class = ' added';
			$text  = penci_woo_translate_text( 'penci_woo_trans_brtwilsh' );
			$href  = get_page_link( get_theme_mod( 'penci_woocommerce_wishlist_page' ) );
		}

		echo '<a title="' . $text . '" href="' . $href . '" data-pid="' . get_the_ID() . '" class="penci-addtowishlist button ' . $class . '">' . $text . '</a>';
	}

	public function add_to_wishlist() {

		$nonce = isset( $_GET['nonce'] ) ? $_GET['nonce'] : '';
		if ( ! wp_verify_nonce( $nonce, 'ajax-nonce' ) ) {
			die( 'Nope!' );
		}

		$productID        = ( isset( $_GET['pid'] ) ) ? $_GET['pid'] : '';
		$productTitle     = get_the_title( $productID );
		$wishlist_page_id = get_theme_mod( 'penci_woocommerce_wishlist_page' );

		if ( ! $productID ) {
			return false;
		}

		$this->set_wishlist_data( $this->save_me, $productID );

		$wish_list_page_url = $wishlist_page_id ? esc_url( get_page_link( $wishlist_page_id ) ) : get_home_url();

		wp_send_json_success(
			array(
				'title'     => esc_attr( get_the_title( $productID ) ),
				'item_link' => esc_url( get_permalink( $productID ) ),
				'img'       => esc_url( get_the_post_thumbnail_url( $productID, 'woocommerce_gallery_thumbnail' ) ),
				'total'     => (int) $this->current_wishlist( true ),
				'url'       => $wish_list_page_url,
			),
			200
		);

		wp_die();
	}

	public function set_wishlist_data( $name, $value ) {

		$current_wishlist = $this->get_data( $name );

		if ( $current_wishlist ) {
			$current_wishlist = unserialize( base64_decode( $current_wishlist ), array( 'allowed_classes' => false ) );

			if ( ! isset( $current_wishlist[ $value ] ) ) {
				$current_wishlist[] = $value;
				$new_wishlist       = base64_encode( serialize( $current_wishlist ) );
				$this->set_data( $name, $new_wishlist );
			}
		} else {
			$current_wishlist   = array();
			$current_wishlist[] = $value;
			$this->set_data( $name, base64_encode( serialize( $current_wishlist ) ) );
		}
	}

	public function set_data( $name, $value ) {
		if ( is_user_logged_in() ) {
			update_user_meta( get_current_user_id(), $name, $value );
		} else {
			$this->set_cookie( $name, $value );
		}
	}

	public function set_cookie( $name, $value ) {
		setcookie( $name, $value, time() + intval( 60 * 60 * 24 * 7 ), COOKIEPATH, COOKIE_DOMAIN, false, false );
		$_COOKIE[ $name ] = $value;
	}

	public function remove_wishlist_item() {

		$nonce = isset( $_GET['nonce'] ) ? $_GET['nonce'] : '';
		if ( ! wp_verify_nonce( $nonce, 'ajax-nonce' ) ) {
			die( 'Nope!' );
		}

		$productID = ( isset( $_GET['pid'] ) ) ? $_GET['pid'] : '';

		if ( ! $productID ) {
			return false;
		}

		$this->delete_wishlist_item( $this->save_me, $productID );

		$wishlist_page_id  = get_theme_mod( 'penci_woocommerce_wishlist_page' );
		$wishlist_page_url = $wishlist_page_id ? esc_url( get_page_link( $wishlist_page_id ) ) : get_home_url();

		wp_send_json_success(
			array(
				'title'     => esc_attr( get_the_title( $productID ) ),
				'item_link' => esc_url( get_permalink( $productID ) ),
				'img'       => esc_url( get_the_post_thumbnail_url( $productID, 'woocommerce_gallery_thumbnail' ) ),
				'total'     => (int) $this->current_wishlist( true ),
				'url'       => $wishlist_page_url,
			),
			200
		);
	}

	public function delete_wishlist_item( $name, $value ) {

		$current_wishlist = $this->get_data( $name );

		if ( $current_wishlist ) {
			$current_wishlist = unserialize( base64_decode( $current_wishlist ), array( 'allowed_classes' => false ) );

			if ( isset( $current_wishlist ) && $current_wishlist ) {

				if ( ( $key = array_search( $value, $current_wishlist ) ) !== false ) {
					unset( $current_wishlist[ $key ] );
				}

				$new_wishlist = base64_encode( serialize( $current_wishlist ) );
				$this->set_data( $name, $new_wishlist );
			}
		}
	}

	public function header_icon() {
		get_template_part( 'woocommerce/header/wishlist-icon' );
	}

	public function wistlist_remove_icon() {

		$wishlist_page = get_theme_mod( 'penci_woocommerce_wishlist_page' );

		if ( $wishlist_page && is_page( $wishlist_page ) ) {

			$class = '';
			$text  = penci_woo_translate_text( 'penci_woo_trans_rmproduct' );
			$href  = '?remove-from-wishlist=' . get_the_id();

			$save_wisthlist = $this->get_user_wishlist( $this->save_me );

			if ( isset( $save_wisthlist ) && $save_wisthlist && in_array( get_the_ID(), $save_wisthlist ) ) {
				echo '<a title="' . $text . '" href="' . $href . '" data-pID="' . get_the_ID() . '" class="penci-removewishlist button ' . $class . '">' . $text . '</a>'; //phpcs:ignore
			}
		}
	}

	public function wishlist_admin_notice() {
		$query['autofocus[section]'] = 'pencidesign_woo_wishlist_settings';
		$section_link                = add_query_arg( $query, admin_url( 'customize.php' ) );
		if ( get_theme_mod( 'penci_woocommerce_wishlist', false ) && ! get_theme_mod( 'penci_woocommerce_wishlist_page' ) ) {
			?>
			<div class="notice notice-warning is-dismissible">
				<p><?php _e( 'Penci Wishlist need to assign the wishlist page. Please create a new page with <strong>[penci_wishlist]</strong> content shortcode, then go to <a href="' . esc_url( $section_link ) . '">this page</a> to configure.', 'soledad' ); ?></p>
			</div>
			<?php
		}
		if ( get_theme_mod( 'penci_woocommerce_wishlist', false ) && class_exists( 'YITH_WCWL_Frontend' ) ) {
			?>
			<div class="notice notice-error is-dismissible">
				<p><?php _e( 'You\'ve activated the YITH WooCommerce Wishlist plugin, please go to <a href="' . esc_url( $section_link ) . '">this page</a> to disable default theme Wishlist.', 'soledad' ); ?></p>
			</div>
			<?php

		}
	}

	public function admin_wishlist_post_state( $post_states, $post ) {

		$wishlist_page_id = get_theme_mod( 'penci_woocommerce_wishlist_page', false );

		if ( $wishlist_page_id && $post->ID == $wishlist_page_id ) {
			$post_states[] = esc_attr__( 'Soledad Wishlist Products', 'soledad' );
		}

		return $post_states;
	}

	public function wishlist_page_content( $content ) {
		$wishlist_page_id = get_theme_mod( 'penci_woocommerce_wishlist_page', false );

		if ( is_page( $wishlist_page_id ) && empty( $content ) ) {
			$content = $this->wishlist_content_shortcode();
		}

		return $content;
	}

	public function wishlist_content_shortcode() {
		ob_start();
		$this->get_wishlist_products();

		return ob_get_clean();
	}

	public function get_wishlist_products() {

		$classes   = array();
		$classes[] = 'penci-wishlist-products';

		$post_ids = $this->get_user_wishlist( $this->save_me );

		$icon_position = get_theme_mod( 'penci_woocommerce_product_icon_hover_position', 'top-left' );
		$icon_style    = get_theme_mod( 'penci_woocommerce_product_icon_hover_style', 'round' );
		$pagination    = get_theme_mod( 'penci_woocommerce_wishlist_pagination' );

		$settings = array(
			'class'               => 'penci-wishlists-products-container',
			'pagination'          => $pagination ? $pagination : 'pagination',
			'post_type'           => 'ids',
			'items_per_page'      => get_theme_mod( 'penci_woocommerce_wishlist_ppp', 24 ),
			'include'             => $post_ids,
			'loop_name'           => 'wishlist',
			'icon_style'          => get_theme_mod( 'penci_woocommerce_product_icon_hover_style', 'round' ),
			'icon_position'       => $icon_position,
			'icon_animation'      => get_theme_mod( 'penci_woocommerce_product_icon_hover_animation', 'move-right' ),
			'product_round_style' => penci_shop_product_round_style( $icon_style, $icon_position ),
			'product_style'       => get_theme_mod( 'penci_woocommerce_product_style', 'style-1' ),
		);

		$check_products = get_posts(
			array(
				'post_type' => 'product',
				'post__in'  => $post_ids,
			)
		);

		if ( ! empty( $check_products ) && $post_ids ) {
			penci_elementor_products_template( $settings, true );
		} else {
			?>

			<div class="woocommerce">

				<div class="penci-wishlist-products-empty-text">
					<h3 class="penci-wishlist-empty-title"><?php echo penci_woo_translate_text( 'penci_woo_trans_wishlist_empty_title' ); ?></h3>
					<?php echo penci_woo_translate_text( 'penci_woocommerce_wishlist_empty_text' ); ?>
				</div>

				<p class="return-to-shop">
					<a class="button"
						href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>">
						<?php echo penci_woo_translate_text( 'penci_woo_trans_returnshop' ); ?>
					</a>
				</p>
			</div>

			<?php
		}
	}
}

$penci_product_wishlist = new penci_product_wishlist();
