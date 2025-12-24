<?php

class penci_product_misc {

	protected static $instance;

	function __construct() {

		add_action( 'wp_ajax_penci_update_cart_item', array( $this, 'penci_update_cart_item' ) );
		add_action( 'wp_ajax_nopriv_penci_update_cart_item', array( $this, 'penci_update_cart_item' ) );

		add_action( 'wp_ajax_penci_ajax_load_products', array( $this, 'ajax_load_products' ) );
		add_action( 'wp_ajax_nopriv_penci_ajax_load_products', array( $this, 'ajax_load_products' ) );

		add_action( 'wp_ajax_penci_get_product_info', array( $this, 'get_product_info' ) );
		add_action( 'wp_ajax_nopriv_penci_get_product_info', array( $this, 'get_product_info' ) );

		add_filter( 'woocommerce_sale_flash', array( $this, 'product_label' ), 10 );
		add_action( 'woocommerce_before_single_product_summary', array( $this, 'penci_top_relate_products' ), 5 );

		add_action( 'penci_woocommerce_before_main_shop_content_right', function () {
			echo '<div class="penci-wofilter-inner">';
		}, 4 );
		add_action( 'penci_woocommerce_before_main_shop_content_right', array( $this, 'product_grid_label' ), 5 );
		add_action( 'penci_woocommerce_before_main_shop_content_right', array( $this, 'product_style_select' ), 5 );
		add_action( 'penci_woocommerce_before_main_shop_content_right', array(
			$this,
			'top_catalog_product_filter_trigger'
		), 9 );
		add_action( 'penci_woocommerce_before_main_shop_content_right', function () {
			echo '</div>';
		}, 9 );

		add_action( 'woocommerce_shop_loop_item_title', array( $this, 'product_categories_link' ), 15 );
		add_action( 'init', array( $this, 'login_to_see_price' ), 200 );


		add_action( 'wp_footer', array( $this, 'mobile_nav_menu' ) );
		add_filter( 'wp_nav_menu_items', array( $this, 'mobile_menu_filter' ), 10, 2 );


		if ( ! get_theme_mod( 'penci_woo_cat_enable_sidebar' ) ) {
			add_action( 'woocommerce_archive_description', array( $this, 'top_catalog_product_filter' ), 5 );
		}

		remove_action( 'woocommerce_cart_is_empty', 'wc_empty_cart_message', 10 );

		add_action( 'woocommerce_cart_is_empty', array( $this, 'empty_cart_message' ), 10 );

		add_action( 'penci_footer_nav_cart_item', array( $this, 'mobile_cart_totals' ) );
		add_action( 'woocommerce_add_to_cart_fragments', array( $this, 'mobile_cart_totals_fragment' ) );

		add_action( 'wp_ajax_penci_add_to_cart_variable', array(
			$this,
			'penci_add_to_cart_variable'
		) );
		add_action( 'wp_ajax_nopriv_penci_add_to_cart_variable', array(
			$this,
			'penci_add_to_cart_variable'
		) );

		add_action( 'woocommerce_after_shop_loop', array( $this, 'blog_search_result' ), 100 );
		add_action( 'penci_after_search_loop', array( $this, 'product_search_result' ), 100 );
	}

	public static function get_instance() {
		if ( null == self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function penci_top_relate_products() {
		if ( wc_get_loop_prop( 'quickview', false ) || ! get_theme_mod( 'penci_single_product_top_related_product', true ) ) {
			return;
		}
		$current_id          = apply_filters( 'penci_top_relate_products_exclude', get_the_ID() );
		$top_relate_products = get_posts( array(
			'post_type'      => 'product',
			'post__not_in'   => array( $current_id ),
			'posts_per_page' => 2,
		) );
		if ( $top_relate_products ) {
			echo '<div class="penci-top-relate-post"><ul>';
			$count = 0;
			foreach ( $top_relate_products as $product_item ) {
				$id        = $product_item->ID;
				$product   = wc_get_product( $id );
				$classes   = 0 == $count ? 'left' : 'right';
				$link      = ( penci_is_shop_on_front() ) ? home_url() : get_post_type_archive_link( 'product' );
				$seperator = '<li class="item-shop-link"><a class="icon-link" href="' . esc_url( $link ) . '"><svg height="14px" viewBox="-19 -19 600 600" width="14px" xmlns="http://www.w3.org/2000/svg"><path d="m251.25 12.5c0-6.90625-5.59375-12.5-12.5-12.5h-226.25c-6.90625 0-12.5 5.59375-12.5 12.5v226.25c0 6.90625 5.59375 12.5 12.5 12.5h226.25c6.90625 0 12.5-5.59375 12.5-12.5zm-25 213.75h-201.25v-201.25h201.25zm0 0"/><path d="m562.5 12.5c0-6.90625-5.59375-12.5-12.5-12.5h-226.25c-6.90625 0-12.5 5.59375-12.5 12.5v226.25c0 6.90625 5.59375 12.5 12.5 12.5h226.25c6.90625 0 12.5-5.59375 12.5-12.5zm-25 213.75h-201.25v-201.25h201.25zm0 0"/><path d="m251.25 323.75c0-6.90625-5.59375-12.5-12.5-12.5h-226.25c-6.90625 0-12.5 5.59375-12.5 12.5v226.25c0 6.90625 5.59375 12.5 12.5 12.5h226.25c6.90625 0 12.5-5.59375 12.5-12.5zm-25 212.5h-201.25v-200h201.25zm0 0"/><path d="m562.5 323.75c0-6.90625-5.59375-12.5-12.5-12.5h-226.25c-6.90625 0-12.5 5.59375-12.5 12.5v226.25c0 6.90625 5.59375 12.5 12.5 12.5h226.25c6.90625 0 12.5-5.59375 12.5-12.5zm-25 212.5h-201.25v-200h201.25zm0 0"/></svg></a></li>';
				$seperator = 'left' == $classes ? $seperator : '';

				echo '<li class="top-ralate-item ' . $classes . '"><a href="' . get_permalink( $id ) . '"><span class="item-icon"><i class="penciicon-' . $classes . '-chevron"></i></span></a>';
				echo '<div class="inner-content"><div class="top-ralate-item__image"><a href="' . get_permalink( $id ) . '">' . get_the_post_thumbnail( $id, 'woocommerce_gallery_thumbnail' ) . '</a></div>';
				echo '<div class="top-ralate-item__summary"><h4><a href="' . get_permalink( $id ) . '">' . get_the_title( $id ) . '</a></h4><span class="price"><a href="' . get_permalink( $id ) . '">' . $product->get_price_html() . '</a></span></div></div>';
				echo '</li>' . $seperator;
				$count ++;
			}
			echo '</ul></div>';
		}
	}

	public function penci_product_quantity( $product ) {
		if ( ! $product->is_sold_individually() && 'variable' != $product->get_type() && $product->is_purchasable() && $product->is_in_stock() ) {
			woocommerce_quantity_input(
				array(
					'min_value' => 1,
					'max_value' => $product->backorders_allowed() ? '' : $product->get_stock_quantity(),
				)
			);
		}
	}

	public function penci_update_cart_item() {
		if ( ( isset( $_GET['item_id'] ) && $_GET['item_id'] ) && ( isset( $_GET['qty'] ) ) ) {
			global $woocommerce;
			if ( $_GET['qty'] ) {
				$woocommerce->cart->set_quantity( $_GET['item_id'], $_GET['qty'] );
			} else {
				$woocommerce->cart->remove_cart_item( $_GET['item_id'] );
			}
		}

		WC_AJAX::get_refreshed_fragments();
	}

	function product_label() {
		global $product;

		$output = array();

		$percentage_label = get_theme_mod( 'penci_woo_label_percentage', true );

		if ( $product->is_on_sale() ) {

			$percentage = '';

			if ( $product->get_type() == 'variable' && $percentage_label ) {

				$available_variations = $product->get_variation_prices();
				$max_percentage       = 0;

				foreach ( $available_variations['regular_price'] as $key => $regular_price ) {
					$sale_price = $available_variations['sale_price'][ $key ];

					if ( $sale_price < $regular_price ) {
						$percentage = round( ( ( $regular_price - $sale_price ) / $regular_price ) * 100 );

						if ( $percentage > $max_percentage ) {
							$max_percentage = $percentage;
						}
					}
				}

				$percentage = $max_percentage;
			} elseif ( ( $product->get_type() == 'simple' || $product->get_type() == 'external' || $product->get_type() == 'variation' ) && $percentage_label ) {
				$percentage = round( ( ( $product->get_regular_price() - $product->get_sale_price() ) / $product->get_regular_price() ) * 100 );
			}

			if ( $percentage ) {
				$output[] = '<span class="onsale product-label">-' . $percentage . '%' . '</span>';
			} else {
				$output[] = '<span class="onsale product-label">' . penci_woo_translate_text( 'penci_woo_trans_sale' ) . '</span>';
			}
		}

		if ( ! $product->is_in_stock() ) {
			$output[] = '<span class="out-of-stock product-label">' . penci_woo_translate_text( 'penci_woo_trans_sold_out' ) . '</span>';
		}

		if ( $product->is_featured() && get_theme_mod( 'penci_woo_label_hot_product', true ) ) {
			$output[] = '<span class="featured product-label">' . penci_woo_translate_text( 'penci_woo_trans_hot' ) . '</span>';
		}

		if ( $this->is_new_label_needed( $product->get_id() ) ) {
			$output[] = '<span class="new product-label">' . penci_woo_translate_text( 'penci_woo_trans_new' ) . '</span>';
		}

		if ( $output ) {
			echo '<div class="product-labels labels-' . get_theme_mod( 'penci_woo_label_style', 'square' ) . '">' . implode( '', $output ) . '</div>';
		}
	}

	public function is_new_label_needed( $product_id ) {
		$enable       = get_theme_mod( 'penci_woo_label_new_product', true );
		$new          = penci_get_single_product_meta( $product_id, 'product_extra_options', 'permanent_new_label' );
		$newness_days = get_theme_mod( 'penci_woo_label_new_product_period', 7 );
		$product      = wc_get_product( $product_id );
		$created      = strtotime( $product->get_date_created() );

		if ( $new ) {
			return true;
		}

		if ( $enable && $newness_days && ( time() - ( 60 * 60 * 24 * $newness_days ) ) < $created ) {
			return true;
		}

		return false;
	}

	public function product_grid_label() {
		if ( ! wc_get_loop_prop( 'is_paginated' ) || ! woocommerce_products_will_display() ) {
			return;
		}


		$default_posts_per_page    = array( penci_woo_get_products_per_page() );
		$per_page_options          = get_theme_mod( 'penci_woo_post_per_page_variations', '9,24,36,-1' );
		$products_per_page_options = ( ! empty( $per_page_options ) ) ? explode( ',', $per_page_options ) : array(
			'12' => 12,
			'24' => 24,
			'36' => 36,
		);
		$products_per_page_options = array_unique( array_merge( $default_posts_per_page, $products_per_page_options ) );
		asort( $products_per_page_options );
		?>

        <div class="penci-products-per-page">
			<span class="per-page-title">
				<?php echo penci_woo_translate_text( 'penci_woo_trans_show' ); ?>
			</span>

			<?php
			foreach ( $products_per_page_options as $key => $value ) :

				$classes = '';
				$args = array(
					'per_page' => $value,
				);

				$link = add_query_arg( $args, penci_shop_page_link( true ) );

				if ( (int) penci_woo_get_products_per_page() === (int) $value ) { // phpcs:ignore
					$classes .= ' current-variation';
				}
				?>
                <a rel="nofollow noopener" href="<?php echo esc_url( $link ); ?>"
                   class="per-page-variation<?php echo esc_attr( $classes ); ?>">
					<span>
						<?php
						$text = '%s';
						esc_html( printf( $text, $value == - 1 ? penci_woo_translate_text( 'penci_woo_trans_all' ) : $value ) );
						?>
					</span>
                </a>
			<?php endforeach; ?>
        </div>
		<?php
	}

	public function product_style_select( $is_element ) {
		if ( ! wc_get_loop_prop( 'is_paginated' ) || ! woocommerce_products_will_display() ) {
			return;
		}

		$shop_view        = get_theme_mod( 'penci_shop_product_view', 'grid_list' );
		$per_row_selector = get_theme_mod( 'penci_woo_per_row_columns_selector', true );
		$columns_settings = get_theme_mod( 'penci_shop_product_columns', 3 );

		$per_row_options = ( 4 >= $columns_settings ) ? array( 2, 3, 4 ) : array( 2, 3, 4, 5, 6 );

		$current_shop_view        = penci_get_shop_view();
		$current_per_row          = penci_get_products_columns_per_row();
		$products_per_row_options = ( ! empty( $per_row_options ) ) ? array_unique( $per_row_options ) : array(
			2,
			3,
			4
		);

		if ( 'list' === $shop_view || ( 'grid' === $shop_view && ! $per_row_selector ) || ( 'grid' === $shop_view && ! $per_row_options ) ) {
			return;
		}

		?>
        <div class="penci-products-shop-view <?php echo esc_attr( 'products-view-' . $shop_view ); ?>">
			<?php if ( 'grid' !== $shop_view ) : ?>
				<?php
				$classes = '';
				$args    = array(
					'shop_view' => 'list',
				);

				if ( $is_element ) {
					$args['shortcode'] = true;
				}

				if ( ( 'list' === $current_shop_view && ! $is_element ) || ( $is_element && isset( $_GET['shop_view'] ) && 'list' === $_GET['shop_view'] ) ) { // phpcs:ignore
					$classes .= ' current-variation';
				}

				$link = add_query_arg( $args, penci_shop_page_link( true ) );
				?>

                <a rel="nofollow noopener" href="<?php echo esc_url( $link ); ?>"
                   class="shop-view per-row-list<?php echo esc_attr( $classes ); ?>">
                    <svg class="icon">
                        <use xlink:href="#list"/>
                    </svg>
                </a>
			<?php endif ?>

			<?php if ( $per_row_selector && $per_row_options ) : ?>
				<?php foreach ( $products_per_row_options as $key => $value ) : ?>
					<?php
					if ( 0 === $value ) {
						continue;
					}

					$classes = '';
					$args    = array(
						'shop_view' => 'grid',
						'per_row'   => $value,
					);

					if ( $is_element ) {
						$args['shortcode'] = true;
					}

					$current_columns = null;

					if ( empty( $_GET['per_row'] ) ) {
						$current_columns = wc_get_loop_prop( 'columns' );
					}

					if ( 'list' !== $current_shop_view && ( (int) $value === (int) $current_per_row && ! $is_element ) || ( $is_element && isset( $_GET['per_row'] ) && $_GET['per_row'] === $value ) ) { // phpcs:ignore
						$classes .= ' current-variation';
					}

					$classes .= ' per-row-' . $value;

					$link = add_query_arg( $args, penci_shop_page_link( true ) );
					?>

                    <a title="<?php echo sprintf( esc_attr__( 'Show %s columns', 'soledad' ), $value ); ?>"
                       rel="nofollow noopener"
                       href="<?php echo esc_url( $link ); ?>"
                       class="shop-view<?php echo esc_attr( $classes ); ?>">
                        <svg class="icon">
                            <use xlink:href="#columns-<?php echo esc_attr( $value ); ?>"/>
                        </svg>
                    </a>
				<?php endforeach; ?>
			<?php endif ?>
            <div hidden>
                <svg xmlns="http://www.w3.org/2000/svg" style="display: none;">
                    <symbol id="columns-2" viewBox="0 0 444 444"><title>columns-2</title>
                        <path d="m7 0h181c3.867188 0 7 3.132812 7 7v181c0 3.867188-3.132812 7-7 7h-181c-3.867188 0-7-3.132812-7-7v-181c0-3.867188 3.132812-7 7-7zm0 0"/>
                        <path d="m256 0h181c3.867188 0 7 3.132812 7 7v181c0 3.867188-3.132812 7-7 7h-181c-3.867188 0-7-3.132812-7-7v-181c0-3.867188 3.132812-7 7-7zm0 0"/>
                        <path d="m7 249h181c3.867188 0 7 3.132812 7 7v181c0 3.867188-3.132812 7-7 7h-181c-3.867188 0-7-3.132812-7-7v-181c0-3.867188 3.132812-7 7-7zm0 0"/>
                        <path d="m256 249h181c3.867188 0 7 3.132812 7 7v181c0 3.867188-3.132812 7-7 7h-181c-3.867188 0-7-3.132812-7-7v-181c0-3.867188 3.132812-7 7-7zm0 0"/>
                    </symbol>
                    <symbol id="columns-3" viewBox="0 0 512 512"><title>columns-3</title>
                        <g>
                            <g>
                                <rect x="180.67" y="361.33" width="150.67" height="150.67"/>
                            </g>
                        </g>
                        <g>
                            <g>
                                <rect x="180.67" y="180.66" width="150.67" height="150.67"/>
                            </g>
                        </g>
                        <g>
                            <g>
                                <rect x="361.33" width="150.67" height="150.66"/>
                            </g>
                        </g>
                        <g>
                            <g>
                                <rect y="180.66" width="150.67" height="150.67"/>
                            </g>
                        </g>
                        <g>
                            <g>
                                <rect x="361.33" y="180.66" width="150.67" height="150.67"/>
                            </g>
                        </g>
                        <g>
                            <g>
                                <rect y="361.33" width="150.67" height="150.67"/>
                            </g>
                        </g>
                        <g>
                            <g>
                                <rect x="361.33" y="361.33" width="150.67" height="150.67"/>
                            </g>
                        </g>
                        <g>
                            <g>
                                <rect width="150.67" height="150.66"/>
                            </g>
                        </g>
                        <g>
                            <g>
                                <rect x="180.67" width="150.67" height="150.66"/>
                            </g>
                        </g>
                    </symbol>
                    <symbol id="columns-4" viewBox="0 0 397.061 397.061"><title>columns-4</title>
                        <rect x="104.49" y="208.98" width="83.592" height="83.592"/>
                        <rect x="104.49" y="0" width="83.592" height="83.592"/>
                        <rect x="104.49" y="313.469" width="83.592" height="83.592"/>
                        <rect x="104.49" y="104.49" width="83.592" height="83.592"/>
                        <rect x="5.224" y="208.98" width="78.367" height="83.592"/>
                        <rect x="5.224" y="313.469" width="78.367" height="83.592"/>
                        <rect x="5.224" y="0" width="78.367" height="83.592"/>
                        <rect x="5.224" y="104.49" width="78.367" height="83.592"/>
                        <rect x="208.98" y="208.98" width="83.592" height="83.592"/>
                        <rect x="313.469" y="104.49" width="78.367" height="83.592"/>
                        <rect x="313.469" y="208.98" width="78.367" height="83.592"/>
                        <rect x="313.469" y="0" width="78.367" height="83.592"/>
                        <rect x="208.98" y="313.469" width="83.592" height="83.592"/>
                        <rect x="208.98" y="104.49" width="83.592" height="83.592"/>
                        <rect x="208.98" y="0" width="83.592" height="83.592"/>
                        <rect x="313.469" y="313.469" width="78.367" height="83.592"/>
                    </symbol>
                    <symbol id="columns-5" viewBox="0 0 24 19">
                        <rect width="4" height="4"/>
                        <rect x="5" width="4" height="4"/>
                        <rect x="10" width="4" height="4"/>
                        <rect x="15" width="4" height="4"/>
                        <rect x="20" width="4" height="4"/>
                        <rect y="5" width="4" height="4"/>
                        <rect x="5" y="5" width="4" height="4"/>
                        <rect x="10" y="5" width="4" height="4"/>
                        <rect x="15" y="5" width="4" height="4"/>
                        <rect x="20" y="5" width="4" height="4"/>
                        <rect y="10" width="4" height="4"/>
                        <rect x="5" y="10" width="4" height="4"/>
                        <rect x="10" y="10" width="4" height="4"/>
                        <rect x="15" y="10" width="4" height="4"/>
                        <rect x="20" y="10" width="4" height="4"/>
                        <rect y="15" width="4" height="4"/>
                        <rect x="5" y="15" width="4" height="4"/>
                        <rect x="10" y="15" width="4" height="4"/>
                        <rect x="15" y="15" width="4" height="4"/>
                        <rect x="20" y="15" width="4" height="4"/>
                    </symbol>
                    <symbol id="columns-6" viewBox="0 0 23 19">
                        <rect width="3" height="4"/>
                        <rect x="4" width="3" height="4"/>
                        <rect x="8" width="3" height="4"/>
                        <rect x="12" width="3" height="4"/>
                        <rect x="16" width="3" height="4"/>
                        <rect x="20" width="3" height="4"/>
                        <rect y="5" width="3" height="4"/>
                        <rect x="4" y="5" width="3" height="4"/>
                        <rect x="8" y="5" width="3" height="4"/>
                        <rect x="12" y="5" width="3" height="4"/>
                        <rect x="16" y="5" width="3" height="4"/>
                        <rect x="20" y="5" width="3" height="4"/>
                        <rect y="10" width="3" height="4"/>
                        <rect x="4" y="10" width="3" height="4"/>
                        <rect x="8" y="10" width="3" height="4"/>
                        <rect x="12" y="10" width="3" height="4"/>
                        <rect x="16" y="10" width="3" height="4"/>
                        <rect x="20" y="10" width="3" height="4"/>
                        <rect y="15" width="3" height="4"/>
                        <rect x="4" y="15" width="3" height="4"/>
                        <rect x="8" y="15" width="3" height="4"/>
                        <rect x="12" y="15" width="3" height="4"/>
                        <rect x="16" y="15" width="3" height="4"/>
                        <rect x="20" y="15" width="3" height="4"/>
                    </symbol>
                    <symbol id="list" viewBox="0 0 512 512"><title>list</title>
                        <g>
                            <g>
                                <path d="M501.333,42.667H138.667c-5.888,0-10.667,4.779-10.667,10.667S132.779,64,138.667,64h362.667 C507.221,64,512,59.221,512,53.333S507.221,42.667,501.333,42.667z"/>
                            </g>
                        </g>
                        <g>
                            <g>
                                <path d="M352,106.667H138.667c-5.888,0-10.667,4.779-10.667,10.667S132.779,128,138.667,128H352 c5.888,0,10.667-4.779,10.667-10.667S357.888,106.667,352,106.667z"/>
                            </g>
                        </g>
                        <g>
                            <g>
                                <path d="M501.333,213.333H138.667c-5.888,0-10.667,4.779-10.667,10.667s4.779,10.667,10.667,10.667h362.667 c5.888,0,10.667-4.779,10.667-10.667S507.221,213.333,501.333,213.333z"/>
                            </g>
                        </g>
                        <g>
                            <g>
                                <path d="M352,277.333H138.667c-5.888,0-10.667,4.779-10.667,10.667s4.779,10.667,10.667,10.667H352 c5.888,0,10.667-4.779,10.667-10.667S357.888,277.333,352,277.333z"/>
                            </g>
                        </g>
                        <g>
                            <g>
                                <path d="M501.333,384H138.667c-5.888,0-10.667,4.779-10.667,10.667s4.779,10.667,10.667,10.667h362.667 c5.888,0,10.667-4.779,10.667-10.667S507.221,384,501.333,384z"/>
                            </g>
                        </g>
                        <g>
                            <g>
                                <path d="M352,448H138.667c-5.888,0-10.667,4.779-10.667,10.667s4.779,10.667,10.667,10.667H352 c5.888,0,10.667-4.779,10.667-10.667S357.888,448,352,448z"/>
                            </g>
                        </g>
                        <g>
                            <g>
                                <circle cx="42.667" cy="85.333" r="42.667"/>
                            </g>
                        </g>
                        <g>
                            <g>
                                <circle cx="42.667" cy="256" r="42.667"/>
                            </g>
                        </g>
                        <g>
                            <g>
                                <circle cx="42.667" cy="426.667" r="42.667"/>
                            </g>
                        </g>
                    </symbol>
                </svg>
            </div>
        </div>
		<?php
	}

	public function product_categories_link() {
		global $product;

		if ( ! wc_get_loop_prop( 'loop_categories', true ) ) {
			return;
		}

		$terms = get_the_terms( $product->get_id(), 'product_cat' );

		if ( ! $terms ) {
			return;
		}

		$terms_array = array();
		$parent      = array();
		$child       = array();
		$links       = array();

		foreach ( $terms as $term ) {
			$terms_array[ $term->term_id ] = $term;

			if ( ! $term->parent ) {
				$parent[ $term->term_id ] = $term->name;
			}
		}

		foreach ( $terms as $term ) {
			if ( $term->parent ) {
				unset( $parent[ $term->parent ] );

				if ( array_key_exists( $term->parent, $terms_array ) ) {
					$child[ $term->parent ] = get_term( $term->parent )->name;
				}

				$child[ $term->term_id ] = $term->name;
			}
		}

		$terms = $child + $parent;

		foreach ( $terms as $key => $value ) {
			$links[] = '<a href="' . esc_url( get_term_link( $key ) ) . '" rel="tag">' . esc_html( $value ) . '</a>';
		}

		?>
        <div class="penci-product-cats">
			<?php echo implode( ', ', $links ); // phpcs:ignore ?>
        </div>
		<?php
	}

	public function login_to_see_price() {
		$restrict_price = get_theme_mod( 'penci_woocommerce_restrict_cart_price', false );
		if ( ! is_user_logged_in() && $restrict_price ) {
			add_filter( 'woocommerce_get_price_html', array( $this, 'login_to_see_price_html' ) );
			add_filter( 'woocommerce_loop_add_to_cart_link', '__return_false' );

			// Add to cart btns
			remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );
			remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );
		}
	}

	public function login_to_see_price_html() {
		$restrict_price = get_theme_mod( 'penci_woocommerce_restrict_cart_price', false );
		if ( ! is_user_logged_in() && $restrict_price ) {
			return '<a href="' . esc_url( get_permalink( wc_get_page_id( 'myaccount' ) ) ) . '" class="restrict-price-tag">' . penci_woo_translate_text( 'penci_woo_trans_lgtspr' ) . '</a>';
		}
	}

	public function top_catalog_product_filter() {
		?>

        <div id="penci-top-product-filter" class="penci-top-product-filter">
			<?php dynamic_sidebar( 'penci-shop-sidebar' ); ?>
        </div>

		<?php
	}

	public function top_catalog_product_filter_trigger() {
		if ( ! get_theme_mod( 'pencidesign_woo_filter_widget_enable', true ) ) {
			return false;
		}
		?>
        <div class="penci-product-top-filter-button">
            <a class="penci-filter-button"
               title="<?php echo penci_woo_translate_text( 'penci_woo_trans_sidebar_filter' ); ?>"
               href="#penci-top-product-filter">
                <i>
                    <svg enable-background="new 0 0 512 512" height="20" viewBox="0 0 512 512" width="20"
                         xmlns="http://www.w3.org/2000/svg">
                        <g>
                            <path d="m90.5 0h-30v362h-30v90h30v60h30v-60h30v-90h-30zm0 422h-30v-30h30z"/>
                            <path d="m481.5 362h-30v-362h-30v362h-30v90h30v60h30v-60h30zm-30 60h-30v-30h30z"/>
                            <path d="m331.5 0h-30v222h-30v90h30v200h30v-200h30v-90h-30zm0 282h-30v-30h30z"/>
                            <path d="m210.5 0h-30v53.652h-30v90h30v368.348h30v-368.348h30v-90h-30zm0 113.652h-30v-30h30z"/>
                        </g>
                    </svg>
                </i>
                <span><?php echo penci_woo_translate_text( 'penci_woo_trans_sidebar_filter' ); ?></span>
            </a>
        </div>
		<?php
	}

	public function ajax_load_products() {
		$preloader = isset( $_GET['preloader'] ) ? $_GET['preloader'] : false;
		$nonce     = isset( $_REQUEST['requestid'] ) ? $_REQUEST['requestid'] : false;
		if ( ! wp_verify_nonce( $nonce, 'ajax-nonce' ) ) {
			die( __( 'Ooppppp', 'soledad' ) );
		} elseif ( ! empty( $_GET['settings'] ) ) {
			penci_elementor_products_template( $_GET['settings'], $preloader );
			die();
		}
	}

	public function product_sale_countdown() {
		global $product;
		$sale_date_end   = get_post_meta( $product->get_id(), '_sale_price_dates_to', true );
		$sale_date_start = get_post_meta( $product->get_id(), '_sale_price_dates_from', true );

		if ( $product->get_type() == 'variable' ) {
			// Variations cache
			$cache                = apply_filters( 'pencicountdown_variable_cache', true );
			$transient_name       = 'pencicountdown_variable_cache_' . $product->get_id();
			$available_variations = array();

			if ( $cache ) {
				$available_variations = get_transient( $transient_name );
			}

			if ( ! $available_variations ) {
				$available_variations = $product->get_available_variations();
				if ( $cache ) {
					set_transient( $transient_name, $available_variations, apply_filters( 'pencicountdown_variable_cache_time', WEEK_IN_SECONDS ) );
				}
			}

			if ( $available_variations ) {
				$sale_date_end   = get_post_meta( $available_variations[0]['variation_id'], '_sale_price_dates_to', true );
				$sale_date_start = get_post_meta( $available_variations[0]['variation_id'], '_sale_price_dates_from', true );
			}
		}

		$curent_date = strtotime( date( 'Y-m-d H:i:s' ) );

		if ( $sale_date_end < $curent_date || $curent_date < $sale_date_start ) {
			return;
		}

		$timezone = 'GMT';

		if ( apply_filters( 'penciwp_timezone', false ) ) {
			$timezone = wc_timezone_string();
		}

		wp_enqueue_script( 'woo-countdown' );

		echo '<div class="penci-product-countdown penci-time-countdown" data-end-date="' . esc_attr( date( 'Y-m-d H:i:s', $sale_date_end ) ) . '" data-timezone="' . $timezone . '"></div>';
	}

	public function mobile_nav_menu() {
		if ( penci_is_shop_archive() ) {
			wc_get_template_part( 'global/mobile-bottom-nav' );
		}
	}

	public function mobile_menu_filter( $items, $args ) {
		$menu_id = get_theme_mod( 'penci_woo_mobile_nav_menu' );
		if ( penci_is_shop_catelog() && $args->menu == $menu_id ) {
			$items .= '<li class="cart-filter"><a href="#sidebar-product-filter"><i class="fa fa-filter" aria-hidden="true"></i>' . penci_woo_translate_text( 'penci_woo_trans_fasort' ) . '</a></li>';
		}

		return $items;
	}

	public function get_product_info() {
		$data = array();
		if ( isset( $_REQUEST['requestid'] ) && isset( $_REQUEST['id'] ) && wp_verify_nonce( $_REQUEST['requestid'], 'ajax-nonce' ) ) {

			$id = $_REQUEST['id'];

			$data = array(
				'title'     => get_the_title( $id ),
				'item_link' => get_the_permalink( $id ),
				'img'       => get_the_post_thumbnail_url( $id, 'woocommerce_gallery_thumbnail' ),
			);
		}
		wp_send_json_success(
			$data,
			200
		);
	}

	public function empty_cart_message() {
		?>
        <div class="woocommerce-content-wrapper">
            <div class="penci-empty-cart penci-empty-page penci-empty-page-text">
                <h3 class="penci-compare-empty-title"><?php echo penci_woo_translate_text( 'penci_woo_cart_empty_title' ) ?></h3>
				<?php echo penci_woo_translate_text( 'penci_woo_cart_empty_textarea' ) ?>
            </div>
        </div>
		<?php
	}

	public function mobile_cart_totals() {
		echo '<span class="penci-mobile-cart-item current-item">' . WC()->cart->get_cart_contents_count() . '</span>';
	}

	public function mobile_cart_totals_fragment( $fragments ) {
		global $woocommerce;

		ob_start();

		?>
        <span class="penci-mobile-cart-item current-item"><?php echo esc_attr( $woocommerce->cart->cart_contents_count ); ?></span>
		<?php
		$fragments['span.penci-mobile-cart-item.current-item'] = ob_get_clean();

		return $fragments;
	}

	public function penci_add_to_cart_variable() {
		ob_start();

		$product_id   = apply_filters( 'woocommerce_add_to_cart_product_id', absint( $_POST['product_id'] ) );
		$quantity     = empty( $_POST['quantity'] ) ? 1 : apply_filters( 'woocommerce_stock_amount', $_POST['quantity'] );
		$variation_id = $_POST['variation_id'];

		$cart_item_data = $_POST;
		unset( $cart_item_data['quantity'] );

		$variation = array();

		foreach ( $cart_item_data as $key => $value ) {
			if ( preg_match( "/^attribute*/", $key ) ) {
				$variation[ $key ] = $value;
			}
		}

		foreach ( $variation as $key => $value ) {
			$variation[ $key ] = stripslashes( $value );
		}
		$passed_validation = apply_filters( 'woocommerce_add_to_cart_validation', true, $product_id, $quantity );

		if ( $passed_validation && WC()->cart->add_to_cart( $product_id, $quantity, $variation_id, $variation, $cart_item_data ) ) {
			do_action( 'woocommerce_ajax_added_to_cart', $product_id );
			if ( get_option( 'woocommerce_cart_redirect_after_add' ) == 'yes' ) {
				wc_add_to_cart_message( $product_id );
			}
			global $woocommerce;
			$items = $woocommerce->cart->get_cart();
			wc_setcookie( 'woocommerce_items_in_cart', count( $items ) );
			wc_setcookie( 'woocommerce_cart_hash', md5( json_encode( $items ) ) );
			do_action( 'woocommerce_set_cart_cookies', true );
			// Return fragments
			WC_AJAX::get_refreshed_fragments();

		} else {

			// If there was an error adding to the cart, redirect to the product page to show any errors
			$data = array(
				'error'       => true,
				'product_url' => apply_filters( 'woocommerce_cart_redirect_after_error', get_permalink( $product_id ), $product_id )
			);
			wp_send_json_error( $data );
		}
	}

	public function blog_search_result() {
		if ( ! is_search() || ! get_theme_mod( 'penci_woocommerce_search_included_posts', true ) ) {
			return;
		}

		$search_query = get_search_query();
		$args         = [ 'search_query' => $search_query ];
		get_template_part( 'woocommerce/content', 'blog-search', $args );
	}

	public function product_search_result() {
		if ( ! is_search() || ! apply_filters( 'penci_enable_product_post_search', false ) ) {
			return;
		}
		$search_query = get_search_query();
		penci_elementor_products_template( [ 'search_query' => $search_query ] );
	}
}

$penci_product_misc = new penci_product_misc;
