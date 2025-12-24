<?php

class penci_product_compare {

	private $save_compare = '';

	function __construct() {

		add_action( 'wp_ajax_nopriv_penci_add_to_compare', array( $this, 'add_to_compare' ) );
		add_action( 'wp_ajax_penci_add_to_compare', array( $this, 'add_to_compare' ) );

		if ( get_theme_mod( 'penci_woocommerce_compare_show', true ) ) {
			add_action( 'penci_loop_product_buttons', array( $this, 'compare_button' ) );
		}

		add_action( 'penci_single_product_extra_buttons', array( $this, 'compare_button' ), 10 );
		$this->save_compare = 'penci_compare_products';

		if ( is_multisite() ) {
			$this->save_compare .= '_' . get_current_blog_id();
		}

		add_shortcode( 'penci_compare_table', array( $this, 'compare_table_shortcode' ) );
		add_action( 'penci_current_compare', array( $this, 'compare_header_menu' ) );

		add_action( 'penci_header_extra_icons', array( $this, 'compare_header_item' ), 10 );

		add_action( 'admin_notices', array( $this, 'admin_notice' ), 85 );

		add_action( 'penci_footer_nav_compare_item', array( $this, 'compare_footer_nav' ) );

		add_filter( 'display_post_states', array( $this, 'admin_compare_post_state' ), 10, 2 );

		add_filter( 'the_content', array( $this, 'compare_page_content' ) );
	}

	public function compare_header_item() {
		get_template_part( 'woocommerce/header/compare-icon' );
	}

	public function compare_footer_nav() {
		echo '<span class="current-item">' . $this->compare_header_menu( true ) . '</span>';
	}

	public function compare_header_menu( $return = false ) {
		$compare_item  = $this->get_compare_item( $this->save_compare );
		$compare_count = 0;

		if ( $compare_item ) {
			$compare_check = get_posts(
				array(
					'post_type'      => 'product',
					'posts_per_page' => - 1,
					'post__in'       => $compare_item,
				)
			);
			$compare_count = count( $compare_check );
		}

		if ( $return ) {
			return $compare_count;
		} else {
			echo $compare_count;
		}
	}

	public function get_compare_item( $name ) {
		$items = $this->get_cookie( $name );
		$items = unserialize( base64_decode( $items ), array( 'allowed_classes' => false ) );
		if ( isset( $items ) && $items ) {
			return $items;
		} else {
			return false;
		}
	}

	public function get_cookie( $name ) {
		return isset( $_COOKIE[ $name ] ) ? sanitize_text_field( wp_unslash( $_COOKIE[ $name ] ) ) : false;
	}

	public function compare_button() {
		$product_id  = get_the_ID();
		$button_text = penci_woo_translate_text( 'penci_woo_trans_addtocompare' );
		$url         = '?add-to-compare=' . $product_id;
		$class       = '';

		$compare_item = $this->get_compare_item( $this->save_compare );
		if ( $compare_item && in_array( $product_id, $compare_item ) ) {
			$button_text = penci_woo_translate_text( 'penci_woo_trans_viewallcompare' );
			$url         = esc_url( get_page_link( get_theme_mod( 'penci_woocommerce_compare_page' ) ) );
			$class       = ' added';
		}

		echo '<a data-method="add" href="' . $url . '" class="button compare penci-compare' . $class . '" data-pID="' . $product_id . '">' . $button_text . '</a>';
	}

	function add_to_compare() {
		$nonce = isset( $_GET['nonce'] ) ? $_GET['nonce'] : '';
		if ( ! wp_verify_nonce( $nonce, 'ajax-nonce' ) ) {
			die( 'Nope!' );
		}

		$action          = isset( $_GET['method'] ) ? $_GET['method'] : '';
		$productID       = ( isset( $_GET['pid'] ) ) ? $_GET['pid'] : '';
		$compare_page_id = get_theme_mod( 'penci_woocommerce_compare_page' );

		$return = array(
			'message' => 'success',
			'url'     => get_home_url(),
		);

		if ( $compare_page_id ) {
			$return['url'] = esc_url( get_page_link( $compare_page_id ) );
		}

		$return['title']     = esc_attr( get_the_title( $productID ) );
		$return['img']       = esc_url( get_the_post_thumbnail_url( $productID, 'woocommerce_gallery_thumbnail' ) );
		$return['item_link'] = esc_url( get_permalink( $productID ) );

		if ( $productID && 'add' == $action ) {
			$this->add_item_to_compare( $this->save_compare, $productID );
			$return['notify'] = sprintf( esc_html__( 'The product %s successfully added to compare table.', 'soledad' ), get_the_title( $productID ) );
		} elseif ( $productID && 'remove' == $action ) {
			$this->remove_item_to_compare( $this->save_compare, $productID );
			$return['notify'] = sprintf( esc_html__( 'The product %s successfully removed to compare table.', 'soledad' ), get_the_title( $productID ) );
		} else {
			$return = array(
				'message' => 'error',
				'url'     => get_home_url(),
			);
		}

		$return['total'] = $this->compare_header_menu( true );

		wp_send_json_success( $return, 200 );
		wp_die();
	}

	public function add_item_to_compare( $name, $value ) {

		$old_save = $this->get_cookie( $name );

		if ( $old_save ) {
			$old_save = unserialize( base64_decode( $old_save ), array( 'allowed_classes' => false ) );

			if ( ! isset( $old_save[ $value ] ) ) {
				$old_save[] = $value;
				$new_save   = base64_encode( serialize( $old_save ) );
				$this->set_cookie( $name, $new_save );
			}
		} else {
			$new_save   = array();
			$new_save[] = $value;
			$this->set_cookie( $name, base64_encode( serialize( $new_save ) ) );
		}
	}

	public function set_cookie( $name, $value ) {
		setcookie( $name, $value, time() + intval( 60 * 60 * 24 * 7 ), COOKIEPATH, COOKIE_DOMAIN, false, false );
		$_COOKIE[ $name ] = $value;
	}

	public function remove_item_to_compare( $name, $value ) {
		$current_compare_items = $this->get_cookie( $name );

		if ( $current_compare_items ) {
			$current_compare_items = unserialize( base64_decode( $current_compare_items ), array( 'allowed_classes' => false ) );

			if ( isset( $current_compare_items ) && $current_compare_items ) {

				if ( ( $key = array_search( $value, $current_compare_items ) ) !== false ) {
					unset( $current_compare_items[ $key ] );
				}

				$this->set_cookie( $name, base64_encode( serialize( $current_compare_items ) ) );
			}
		}
	}

	public function admin_notice() {
		$query['autofocus[section]'] = 'pencidesign_woo_compare_settings';
		$section_link                = add_query_arg( $query, admin_url( 'customize.php' ) );
		if ( get_theme_mod( 'penci_woocommerce_compare', false ) && ! get_theme_mod( 'penci_woocommerce_compare_page' ) ) {
			?>
			<div class="notice notice-warning is-dismissible">
				<p><?php _e( 'Penci Product Compare need to assign the compare page. Please create a new page with <strong>[penci_compare_table]</strong> content shortcode, then go to <a href="' . esc_url( $section_link ) . '">this page</a> to configure.', 'soledad' ); ?></p>
			</div>
			<?php
		}
		if ( get_theme_mod( 'penci_woocommerce_compare', false ) && class_exists( 'YITH_Woocompare' ) ) {
			?>
			<div class="notice notice-error is-dismissible">
				<p><?php _e( 'You\'ve activated the YITH WooCommerce Compare plugin, please go to <a href="' . esc_url( $section_link ) . '">this page</a> to disable default theme Compare.', 'soledad' ); ?></p>
			</div>
			<?php

		}
	}

	public function admin_compare_post_state( $post_states, $post ) {

		$compare_page_id = get_theme_mod( 'penci_woocommerce_compare_page', false );

		if ( $compare_page_id && $post->ID == $compare_page_id ) {
			$post_states[] = esc_attr__( 'Soledad Compare Product', 'soledad' );
		}

		return $post_states;
	}

	public function compare_page_content( $content ) {
		$compare_page_id = get_theme_mod( 'penci_woocommerce_compare_page', false );

		if ( is_page( $compare_page_id ) && empty( $content ) ) {
			$content = $this->compare_table_shortcode();
		}

		return $content;
	}

	public function compare_table_shortcode() {
		ob_start();
		$this->compare_html_table();

		return ob_get_clean();
	}

	public function compare_html_table() {
		$user_fields        = get_theme_mod( 'penci_woocommerce_compare_fields' );
		$user_fields		= ! is_array( $user_fields ) ? explode( ',', $user_fields ) : $user_fields;
		$products           = $this->get_compared_products_data();
		$fields             = $this->get_compare_fields( $user_fields );
		$empty_compare_text = penci_woo_translate_text( 'penci_woocommerce_compare_empty_text' );
		$check_class        = '';
		$total_producs      = count( $products );
		if ( 3 < $total_producs ) {
			$check_class = ' penci-multicompare';
		}
		?>

		<div class="penci-products-compare-table woocommerce<?php echo esc_attr( $check_class ); ?>">
			<?php
			if ( ! empty( $products ) ) {
				array_unshift( $products, array() );
				foreach ( $fields as $field_id => $field ) {
					if ( ! $this->is_products_have_field( $field_id, $products ) ) {
						continue;
					}
					?>
					<div class="penci-compare-row compare-<?php echo esc_attr( $field_id ); ?>">
						<?php
						foreach ( $products as $product_id => $product ) :
							$idtag = isset( $product['id'] ) ? ' data-productid="' . esc_attr( $product['id'] ) . '"' : '';
							?>
							<?php if ( ! empty( $product ) ) : ?>
							<div<?php echo $idtag; ?> class="penci-compare-col compare-value"
														data-title="<?php echo esc_attr( $field ); ?>">
								<?php $this->compare_display_field( $field_id, $product ); ?>
							</div>
						<?php else : ?>
							<div<?php echo $idtag; ?> class="penci-compare-col compare-field">
								<?php echo esc_html( $field ); ?>
							</div>
						<?php endif; ?>

						<?php endforeach ?>
					</div>
					<?php
				}
			} else {
				?>
				<div class="penci-empty-compare penci-empty-page penci-empty-page-text">
					<h3 class="penci-compare-empty-title"><?php echo penci_woo_translate_text( 'penci_woo_trans_compare_empty_title' ); ?></h3>
					<p><?php echo $empty_compare_text; ?></p>
				</div>
				<p class="return-to-shop">
					<a class="button"
						href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>">
						<?php echo penci_woo_translate_text( 'penci_woo_trans_returnshop' ); ?>
					</a>
				</p>
				<?php
			}

			?>
		</div>

		<?php
	}

	public function get_compared_products_data() {
		$ids = $this->get_compare_item( $this->save_compare );

		if ( empty( $ids ) ) {
			return array();
		}

		$args = array(
			'include' => $ids,
			'limit'   => 100,
		);

		$products = wc_get_products( $args );

		$products_data = array();

		$user_fields = get_theme_mod( 'penci_woocommerce_compare_fields' );
		$fields      = $this->get_compare_fields( $user_fields );

		$fields = array_filter(
			$fields,
			function ( $field ) {
				return 'pa_' === substr( $field, 0, 3 );
			},
			ARRAY_FILTER_USE_KEY
		);

		$divider = '-';

		foreach ( $products as $product ) {
			$rating_count = $product->get_rating_count();
			$average      = $product->get_average_rating();

			$products_data[ $product->get_id() ] = array(
				'basic'        => array(
					'title'       => $product->get_title() ? $product->get_title() : $divider,
					'image'       => $product->get_image() ? $product->get_image() : $divider,
					'rating'      => wc_get_rating_html( $average, $rating_count ),
					'price'       => $product->get_price_html() ? $product->get_price_html() : $divider,
					'add_to_cart' => $this->compare_add_to_cart_html( $product ) ? $this->compare_add_to_cart_html( $product ) : $divider,
				),
				'id'           => $product->get_id(),
				'image_id'     => $product->get_image_id(),
				'permalink'    => $product->get_permalink(),
				'dimensions'   => wc_format_dimensions( $product->get_dimensions( false ) ),
				'description'  => $product->get_short_description() ? $product->get_short_description() : $divider,
				'weight'       => $product->get_weight() ? $product->get_weight() : $divider,
				'sku'          => $product->get_sku() ? $product->get_sku() : $divider,
				'availability' => $this->compare_availability_html( $product ),
			);

			foreach ( $fields as $field_id => $field_name ) {
				if ( taxonomy_exists( $field_id ) ) {
					$products_data[ $product->get_id() ][ $field_id ] = array();
					$orderby = wc_attribute_orderby( $field_id ) ? wc_attribute_orderby( $field_id ) : 'name';
					$terms   = wp_get_post_terms(
						$product->get_id(),
						$field_id,
						array(
							'orderby' => $orderby,
						)
					);
					if ( ! empty( $terms ) ) {
						foreach ( $terms as $term ) {
							$term = sanitize_term( $term, $field_id );
							$products_data[ $product->get_id() ][ $field_id ][] = $term->name;
						}
					} else {
						$products_data[ $product->get_id() ][ $field_id ][] = '-';
					}
					$products_data[ $product->get_id() ][ $field_id ] = implode( ', ', $products_data[ $product->get_id() ][ $field_id ] );
				}
			}
		}

		return $products_data;
	}

	public function get_compare_fields( $user_field = array() ) {
		$fields = array(
			'basic' => penci_woo_translate_text( 'penci_woo_trans_compare_product' ),
		);

		$fields_settings = is_array( $user_field ) && $user_field ? $user_field : '';

		if ( is_array( $fields_settings ) && count( $fields_settings ) > 1 ) {
			$fields_labels = $this->compare_available_fields( true );

			foreach ( $fields_settings as $field ) {
				if ( isset( $fields_labels [ $field ] ) ) {
					$fields[ $field ] = $fields_labels [ $field ]['name'];
				}
			}

			return $fields;
		}

		if ( isset( $fields_settings['enabled'] ) && count( $fields_settings['enabled'] ) > 1 ) {
			$fields = $fields + $fields_settings['enabled'];
		}

		return $fields;
	}

	public static function compare_available_fields( $new = false ) {
		$product_attributes = array();

		if ( function_exists( 'wc_get_attribute_taxonomies' ) ) {
			$product_attributes = wc_get_attribute_taxonomies();
		}

		if ( $new ) {
			$options = array(
				'description'  => array(
					'name'  => penci_woo_translate_text( 'penci_woo_trans_desc' ),
					'value' => 'description',
				),
				'dimensions'   => array(
					'name'  => penci_woo_translate_text( 'penci_woo_trans_demensions' ),
					'value' => 'dimensions',
				),
				'weight'       => array(
					'name'  => penci_woo_translate_text( 'penci_woo_trans_weight' ),
					'value' => 'weight',
				),
				'availability' => array(
					'name'  => penci_woo_translate_text( 'penci_woo_trans_availability' ),
					'value' => 'availability',
				),
				'sku'          => array(
					'name'  => penci_woo_translate_text( 'penci_woo_trans_sku' ),
					'value' => 'sku',
				),

			);

			if ( count( $product_attributes ) > 0 ) {
				foreach ( $product_attributes as $attribute ) {
					$options[ 'pa_' . $attribute->attribute_name ] = array(
						'name'  => wc_attribute_label( $attribute->attribute_label ),
						'value' => 'pa_' . $attribute->attribute_name,
					);
				}
			}

			return $options;
		}

		$fields = array(
			'enabled'  => array(
				'description'  => penci_woo_translate_text( 'penci_woo_trans_desc' ),
				'sku'          => penci_woo_translate_text( 'penci_woo_trans_sku' ),
				'availability' => penci_woo_translate_text( 'penci_woo_trans_availability' ),
			),
			'disabled' => array(
				'weight'     => penci_woo_translate_text( 'penci_woo_trans_weight' ),
				'dimensions' => penci_woo_translate_text( 'penci_woo_trans_demensions' ),
			),
		);

		if ( count( $product_attributes ) > 0 ) {
			foreach ( $product_attributes as $attribute ) {
				$fields['disabled'][ 'pa_' . $attribute->attribute_name ] = $attribute->attribute_label;
			}
		}

		return $fields;
	}

	public function compare_add_to_cart_html( $product ) {
		if ( ! $product ) {
			return;
		}

		$defaults = array(
			'quantity'   => 1,
			'class'      => implode(
				' ',
				array_filter(
					array(
						'button',
						'product_type_' . $product->get_type(),
						$product->is_purchasable() && $product->is_in_stock() ? 'add_to_cart_button' : '',
						$product->supports( 'ajax_add_to_cart' ) && $product->is_purchasable() && $product->is_in_stock() ? 'ajax_add_to_cart' : '',
					)
				)
			),
			'attributes' => array(
				'data-product_id'  => $product->get_id(),
				'data-product_sku' => $product->get_sku(),
				'aria-label'       => $product->add_to_cart_description(),
				'rel'              => 'nofollow',
			),
		);

		$args = apply_filters( 'woocommerce_loop_add_to_cart_args', $defaults, $product );

		if ( isset( $args['attributes']['aria-label'] ) ) {
			$args['attributes']['aria-label'] = strip_tags( $args['attributes']['aria-label'] );
		}

		return apply_filters(
			'woocommerce_loop_add_to_cart_link',
			sprintf(
				'<a href="%s" data-quantity="%s" class="%s add-to-cart-loop" %s><span>%s</span></a>',
				esc_url( $product->add_to_cart_url() ),
				esc_attr( isset( $args['quantity'] ) ? $args['quantity'] : 1 ),
				esc_attr( isset( $args['class'] ) ? $args['class'] : 'button' ),
				isset( $args['attributes'] ) ? wc_implode_html_attributes( $args['attributes'] ) : '',
				esc_html( $product->add_to_cart_text() )
			),
			$product,
			$args
		);
	}

	public function compare_availability_html( $product ) {
		$html         = '';
		$availability = $product->get_availability();

		if ( empty( $availability['availability'] ) ) {
			$availability['availability'] = __( 'In stock', 'woocommerce' );
		}

		if ( ! empty( $availability['availability'] ) ) {
			ob_start();

			wc_get_template(
				'single-product/stock.php',
				array(
					'product'      => $product,
					'class'        => $availability['class'],
					'availability' => $availability['availability'],
				)
			);

			$html = ob_get_clean();
		}

		return apply_filters( 'woocommerce_get_stock_html', $html, $product );
	}

	public function is_products_have_field( $field_id, $products ) {
		foreach ( $products as $product_id => $product ) {
			if ( isset( $product[ $field_id ] ) && ( ! empty( $product[ $field_id ] ) && '-' !== $product[ $field_id ] && 'N/A' !== $product[ $field_id ] ) ) {
				return true;
			}
		}

		return false;
	}

	public function compare_display_field( $field_id, $product ) {

		$type = $field_id;

		if ( 'pa_' === substr( $field_id, 0, 3 ) ) {
			$type = 'attribute';
		}

		switch ( $type ) {
			case 'basic':
				echo '<div class="compare-basic-content">';
				echo '<div class="penci-top-button"><a title="' . penci_woo_translate_text( 'penci_woo_trans_remove_product' ) . '" href="#" rel="nofollow" data-method="remove" class="button penci-compare" data-pid="' . esc_attr( $product['id'] ) . '">' . penci_woo_translate_text( 'penci_woo_trans_remove_product' ) . '</a></div>';
				echo '<a class="product-image" href="' . get_permalink( $product['id'] ) . '">' . $product['basic']['image'] . '</a>';
				echo '<h4 class="penci-product-title"><a href="' . get_permalink( $product['id'] ) . '">' . $product['basic']['title'] . '</a></h4>';
				echo wp_kses_post( $product['basic']['rating'] );
				echo '<div class="price">';
				echo wp_kses_post( $product['basic']['price'] );
				echo '</div>';
				if ( ! get_theme_mod( 'penci_woo_catalog_mode' ) ) {
					echo $product['basic']['add_to_cart'];
				}
				echo '</div>';
				break;

			case 'attribute':
				if ( $field_id === get_theme_mod( 'penci_woocommerce_brand_attr' ) ) {
					$brands = wc_get_product_terms( $product['id'], $field_id, array( 'fields' => 'all' ) );

					if ( empty( $brands ) ) {
						echo '-';

						return;
					}

					foreach ( $brands as $brand ) {
						$image = get_term_meta( $brand->term_id, 'image', true );

						if ( ! empty( $image ) ) {
							echo '<div class="penci-brand-compare">';
							echo '<img src="' . esc_url( wp_get_attachment_image_url( $image, 'full' ) ) . '" title="' . esc_attr( $brand->name ) . '" alt="' . esc_attr( $brand->name ) . '" />';
							echo '</div>';
						} else {
							echo wp_kses_post( $product[ $field_id ] );
						}
					}
				} else {
					echo wp_kses_post( $product[ $field_id ] );
				}
				break;

			case 'weight':
				if ( $product[ $field_id ] ) {
					$unit = $product[ $field_id ] !== '-' ? get_option( 'woocommerce_weight_unit' ) : '';
					echo wc_format_localized_decimal( $product[ $field_id ] ) . ' ' . esc_attr( $unit );
				}
				break;

			case 'description':
				echo apply_filters( 'woocommerce_short_description', $product[ $field_id ] );
				break;

			default:
				echo wp_kses_post( $product[ $field_id ] );
				break;
		}
	}
}

$product_compare = new penci_product_compare();
