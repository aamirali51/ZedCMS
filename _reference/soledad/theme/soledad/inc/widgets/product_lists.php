<?php
defined( 'ABSPATH' ) || exit;

/**
 * Widget products.
 */
if ( ! class_exists( 'Penci_Product_Lists' ) ) {
	class Penci_Product_Lists extends WP_Widget {
		private $widget_id;

		/**
		 * Constructor.
		 */
		public function __construct() {
			/* Widget settings. */
			$widget_ops = array(
				'classname'   => 'woocommerce widget_products penci-product-list',
				'description' => esc_html__( 'A widget that displays your recent products from all categories or a category', 'soledad' )
			);

			/* Widget control settings. */
			$control_ops     = array( 'id_base' => 'penci_products_list' );
			$this->widget_id = 'penci_products_list';

			/* Create the widget. */

			parent::__construct( 'penci_products_list', penci_get_theme_name('.Soledad',true).esc_html__( 'Products List', 'soledad' ), $widget_ops, $control_ops );

		}

		/**
		 * Output widget.
		 *
		 * @param array $args Arguments.
		 * @param array $instance Widget instance.
		 *
		 * @see WP_Widget
		 */
		public function widget( $args, $instance ) {
			ob_start();

			wc_set_loop_prop( 'name', 'widget' );

			$products = $this->get_products( $args, $instance );
			if ( $products && $products->have_posts() ) {
				$this->widget_start( $args, $instance );

				echo wp_kses_post( apply_filters( 'woocommerce_before_widget_product_list', '<ul class="product_list_widget">' ) );

				$template_args = array(
					'widget_id'   => isset( $args['widget_id'] ) && $args['widget_id'] ? $args['widget_id'] : $this->widget_id,
					'show_rating' => true,
				);

				while ( $products->have_posts() ) {
					$products->the_post();
					wc_get_template( 'content-widget-product.php', $template_args );
				}

				echo wp_kses_post( apply_filters( 'woocommerce_after_widget_product_list', '</ul>' ) );

				$this->widget_end( $args );
			}

			wp_reset_postdata();

			echo $this->cache_widget( $args, ob_get_clean() ); // WPCS: XSS ok.
		}

		/**
		 * Cache the widget.
		 *
		 * @param array $args Arguments.
		 * @param string $content Content.
		 *
		 * @return string the content that was cached
		 */
		public function cache_widget( $args, $content ) {
			// Don't set any cache if widget_id doesn't exist.
			if ( empty( $args['widget_id'] ) ) {
				return $content;
			}

			$cache = wp_cache_get( $this->get_widget_id_for_cache( $this->widget_id ), 'widget' );

			if ( ! is_array( $cache ) ) {
				$cache = array();
			}

			$cache[ $this->get_widget_id_for_cache( $args['widget_id'] ) ] = $content;

			wp_cache_set( $this->get_widget_id_for_cache( $this->widget_id ), $cache, 'widget' );

			return $content;
		}

		protected function get_widget_id_for_cache( $widget_id, $scheme = '' ) {
			if ( $scheme ) {
				$widget_id_for_cache = $widget_id . '-' . $scheme;
			} else {
				$widget_id_for_cache = $widget_id . '-' . ( is_ssl() ? 'https' : 'http' );
			}

			return apply_filters( 'woocommerce_cached_widget_id', $widget_id_for_cache );
		}

		/**
		 * Flush the cache.
		 */
		public function flush_widget_cache() {
			foreach ( array( 'https', 'http' ) as $scheme ) {
				wp_cache_delete( $this->get_widget_id_for_cache( $this->widget_id, $scheme ), 'widget' );
			}
		}

		public function widget_start( $args, $instance ) {
			echo $args['before_widget']; // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped

			$title = apply_filters( 'widget_title', $this->get_instance_title( $instance ), $instance, $this->id_base );

			if ( $title ) {
				echo $args['before_title'] . $title . $args['after_title']; // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped
			}
		}

		protected function get_instance_title( $instance ) {
			if ( isset( $instance['title'] ) ) {
				return $instance['title'];
			}

			if ( isset( $this->settings, $this->settings['title'], $this->settings['title']['std'] ) ) {
				return $this->settings['title']['std'];
			}

			return '';
		}

		public function widget_end( $args ) {
			echo $args['after_widget']; // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped
		}

		/**
		 * Query the products and return them.
		 *
		 * @param array $args Arguments.
		 * @param array $instance Widget instance.
		 *
		 * @return WP_Query
		 */
		public function get_products( $args, $instance ) {
			$number                      = ! empty( $instance['number'] ) ? absint( $instance['number'] ) : 5;
			$show                        = ! empty( $instance['show'] ) ? sanitize_title( $instance['show'] ) : '';
			$orderby                     = ! empty( $instance['orderby'] ) ? sanitize_title( $instance['orderby'] ) : 'date';
			$order                       = ! empty( $instance['order'] ) ? sanitize_title( $instance['order'] ) : 'desc';
			$product_visibility_term_ids = wc_get_product_visibility_term_ids();

			$query_args = array(
				'posts_per_page' => $number,
				'post_status'    => 'publish',
				'post_type'      => 'product',
				'no_found_rows'  => 1,
				'order'          => $order,
				'meta_query'     => array(),
				'tax_query'      => array(
					'relation' => 'AND',
				),
			); // WPCS: slow query ok.

			if ( empty( $instance['show_hidden'] ) ) {
				$query_args['tax_query'][] = array(
					'taxonomy' => 'product_visibility',
					'field'    => 'term_taxonomy_id',
					'terms'    => is_search() ? $product_visibility_term_ids['exclude-from-search'] : $product_visibility_term_ids['exclude-from-catalog'],
					'operator' => 'NOT IN',
				);
				$query_args['post_parent'] = 0;
			}

			if ( ! empty( $instance['cats_id'] ) ) {
				$query_args['tax_query'][] = array(
					'taxonomy' => 'product_cat',
					'terms'    => explode( ',', $instance['cats_id'] ),
				);
			}

			if ( ! empty( $instance['tags_id'] ) ) {
				$query_args['tax_query'][] = array(
					'taxonomy' => 'product_tag',
					'terms'    => explode( ',', $instance['tags_id'] ),
				);
			}

			if ( ! empty( $instance['hide_free'] ) ) {
				$query_args['meta_query'][] = array(
					'key'     => '_price',
					'value'   => 0,
					'compare' => '>',
					'type'    => 'DECIMAL',
				);
			}

			if ( 'yes' === get_option( 'woocommerce_hide_out_of_stock_items' ) ) {
				$query_args['tax_query'][] = array(
					array(
						'taxonomy' => 'product_visibility',
						'field'    => 'term_taxonomy_id',
						'terms'    => $product_visibility_term_ids['outofstock'],
						'operator' => 'NOT IN',
					),
				); // WPCS: slow query ok.
			}

			switch ( $show ) {
				case 'featured':
					$query_args['tax_query'][] = array(
						'taxonomy' => 'product_visibility',
						'field'    => 'term_taxonomy_id',
						'terms'    => $product_visibility_term_ids['featured'],
					);
					break;
				case 'onsale':
					$product_ids_on_sale    = penci_get_product_ids_on_sale();
					$product_ids_on_sale[]  = 0;
					$query_args['post__in'] = $product_ids_on_sale;
					break;
			}

			switch ( $orderby ) {
				case 'price':
					$query_args['meta_key'] = '_price'; // WPCS: slow query ok.
					$query_args['orderby']  = 'meta_value_num';
					break;
				case 'rand':
					$query_args['orderby'] = 'rand';
					break;
				case 'sales':
					$query_args['meta_key'] = 'total_sales'; // WPCS: slow query ok.
					$query_args['orderby']  = 'meta_value_num';
					break;
				default:
					$query_args['orderby'] = 'date';
			}

			return new WP_Query( apply_filters( 'woocommerce_products_widget_query_args', $query_args ) );
		}

		function soledad_widget_defaults() {
			return [
				'title'       => 'Penci Products List',
				'cats_id'     => '',
				'tags_id'     => '',
				'orderby'     => 'date',
				'order'       => 'desc',
				'show'        => '',
				'hide_free'   => '',
				'show_hidden' => '',
				'number'      => 5,
				'offset'      => '',
			];
		}

		function form( $instance ) {

			/* Set up some default widget settings. */
			$defaults = $this->soledad_widget_defaults();

			$cats_id = array();
			$tags_id = array();

			if ( isset( $instance['cats_id'] ) && ! empty( $instance['cats_id'] ) ) {
				$cats_id = explode( ',', $instance['cats_id'] );
			}
			if ( isset( $instance['tags_id'] ) && ! empty( $instance['tags_id'] ) ) {
				$tags_id = explode( ',', $instance['tags_id'] );
			}

			$instance = wp_parse_args( (array) $instance, $defaults );

			$instance_title = $instance['title'] ? str_replace( '"', '&quot;', $instance['title'] ) : '';
			?>
            <style>span.description {
					font-style: italic;
					font-size: 13px;
				}</style>
            <!-- Widget Title: Text Input -->
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'soledad' ); ?></label>
                <input type="text" class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"
                       name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>"
                       value="<?php echo $instance_title; ?>"/>
            </p>

            <!-- Category -->
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'cats_id' ) ); ?>"><?php esc_html_e( 'Include Categories:', 'soledad' ); ?></label>
                <select multiple="multiple" id="<?php echo esc_attr( $this->get_field_id( 'cats_id' ) ); ?>[]"
                        name="<?php echo esc_attr( $this->get_field_name( 'cats_id' ) ); ?>[]"
                        class="widefat categories" style="width:100%; height: 125px;">
                    <option value='' <?php if ( in_array( 'all', $cats_id ) ) {
						echo 'selected="selected"';
					} ?>><?php esc_html_e( 'All categories', 'soledad' ); ?></option>
					<?php $categories = get_terms( array(
						'taxonomy'   => 'product_cat',
						'hide_empty' => false,
					) ); ?>
					<?php foreach ( $categories as $category ) { ?>
                        <option value='<?php echo esc_attr( $category->term_id ); ?>' <?php if ( in_array( $category->term_id, $cats_id ) ) {
							echo 'selected="selected"';
						} ?>><?php echo sanitize_text_field( $category->name ); ?></option>
					<?php } ?>
                </select>
                <span class="description"><?php _e( 'Hold the "Ctrl" on the keyboard and click to select/un-select multiple categories.', 'soledad' ); ?></span>
            </p>

            <!-- Tags -->
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'tags_id' ) ); ?>"><?php esc_html_e( 'Include Tags:', 'soledad' ); ?></label>
                <select multiple="multiple" id="<?php echo esc_attr( $this->get_field_id( 'tags_id' ) ); ?>[]"
                        name="<?php echo esc_attr( $this->get_field_name( 'tags_id' ) ); ?>[]"
                        class="widefat categories" style="width:100%; height: 125px;">
                    <option value='' <?php if ( in_array( 'all', $tags_id ) ) {
						echo 'selected="selected"';
					} ?>><?php esc_html_e( 'All tags', 'soledad' ); ?></option>
					<?php $tags = get_terms( array(
						'taxonomy'   => 'product_tag',
						'hide_empty' => false,
					) ); ?>
					<?php foreach ( $tags as $tag ) { ?>
                        <option value='<?php echo esc_attr( $tag->term_id ); ?>' <?php if ( in_array( $tag->term_id, $tags_id ) ) {
							echo 'selected="selected"';
						} ?>><?php echo sanitize_text_field( $tag->name ); ?></option>
					<?php } ?>
                </select>
                <span class="description"><?php _e( 'Hold the "Ctrl" on the keyboard and click to select/un-select multiple tags.', 'soledad' ); ?></span>
            </p>

            <!-- Show -->
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'show' ) ); ?>"><?php esc_html_e( 'Show:', 'soledad' ); ?></label>
                <select id="<?php echo esc_attr( $this->get_field_id( 'show' ) ); ?>"
                        name="<?php echo esc_attr( $this->get_field_name( 'show' ) ); ?>" class="widefat show"
                        style="width:100%;">
                    <option value='' <?php if ( '' == $instance['show'] ) {
						echo 'selected="selected"';
					} ?>><?php esc_html_e( 'All products', 'soledad' ); ?></option>
                    <option value='featured' <?php if ( 'featured' == $instance['show'] ) {
						echo 'selected="selected"';
					} ?>><?php esc_html_e( 'Featured products', 'soledad' ); ?></option>
                    <option value='onsale' <?php if ( 'onsale' == $instance['show'] ) {
						echo 'selected="selected"';
					} ?>><?php esc_html_e( 'On-sale products', 'soledad' ); ?></option>
                </select>
            </p>

            <!-- Order by -->
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'orderby' ) ); ?>"><?php esc_html_e( 'Order By:', 'soledad' ); ?></label>
                <select id="<?php echo esc_attr( $this->get_field_id( 'orderby' ) ); ?>"
                        name="<?php echo esc_attr( $this->get_field_name( 'orderby' ) ); ?>" class="widefat orderby"
                        style="width:100%;">
                    <option value='date' <?php if ( 'date' == $instance['orderby'] ) {
						echo 'selected="selected"';
					} ?>><?php esc_html_e( 'Published Date', 'soledad' ); ?></option>
                    <option value='price' <?php if ( 'price' == $instance['orderby'] ) {
						echo 'selected="selected"';
					} ?>><?php esc_html_e( 'Price', 'soledad' ); ?></option>
                    <option value='rand' <?php if ( 'rand' == $instance['orderby'] ) {
						echo 'selected="selected"';
					} ?>><?php esc_html_e( 'Random', 'soledad' ); ?></option>
                    <option value='sales' <?php if ( 'sales' == $instance['orderby'] ) {
						echo 'selected="selected"';
					} ?>><?php esc_html_e( 'Sales', 'soledad' ); ?></option>
                </select>
            </p>

            <!-- Order -->
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'order' ) ); ?>"><?php esc_html_e( 'Select Order Type:', 'soledad' ); ?></label>
                <select id="<?php echo esc_attr( $this->get_field_id( 'order' ) ); ?>"
                        name="<?php echo esc_attr( $this->get_field_name( 'order' ) ); ?>" class="widefat orderby"
                        style="width:100%;">
                    <option value='DESC' <?php if ( 'DESC' == $instance['order'] ) {
						echo 'selected="selected"';
					} ?>><?php esc_html_e( 'DESC ( Descending Order )', 'soledad' ); ?></option>
                    <option value='ASC' <?php if ( 'ASC' == $instance['order'] ) {
						echo 'selected="selected"';
					} ?>><?php esc_html_e( 'ASC ( Ascending Order )', 'soledad' ); ?></option>
                </select>
            </p>

            <!-- Number of posts -->
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>"><?php esc_html_e( 'Number of posts to show:', 'soledad' ); ?></label>
                <input type="text" class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>"
                       name="<?php echo esc_attr( $this->get_field_name( 'number' ) ); ?>"
                       value="<?php echo esc_attr( $instance['number'] ); ?>" size="3"/>
            </p>

            <!-- Offset of products -->
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'offset' ) ); ?>"><?php esc_html_e( 'Number of offset Posts:', 'soledad' ); ?></label>
                <input type="text" class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'offset' ) ); ?>"
                       name="<?php echo esc_attr( $this->get_field_name( 'offset' ) ); ?>"
                       value="<?php echo esc_attr( $instance['offset'] ); ?>" size="3"/>
            </p>

            <!-- Products Misc -->
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'hide_free' ) ); ?>"><?php esc_html_e( 'Hide Free Products?:', 'soledad' ); ?></label>
                <input type="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'hide_free' ) ); ?>"
                       name="<?php echo esc_attr( $this->get_field_name( 'hide_free' ) ); ?>" <?php checked( (bool) $instance['hide_free'], true ); ?> />
            </p>

            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'show_hidden' ) ); ?>"><?php esc_html_e( 'Show Hidden Products?:', 'soledad' ); ?></label>
                <input type="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'show_hidden' ) ); ?>"
                       name="<?php echo esc_attr( $this->get_field_name( 'show_hidden' ) ); ?>" <?php checked( (bool) $instance['show_hidden'], true ); ?> />
            </p>

			<?php
		}

		function update( $new_instance, $old_instance ) {
			$instance = $old_instance;

			$data_instance = $this->soledad_widget_defaults();

			foreach ( $data_instance as $data => $value ) {
				$instance[ $data ] = ! empty( $new_instance[ $data ] ) ? $new_instance[ $data ] : '';
			}

			if ( ! empty( $new_instance['cats_id'] ) ) {
				if ( is_array( $new_instance['cats_id'] ) ) {
					$instance['cats_id'] = implode( ',', $new_instance['cats_id'] );
				} else {
					$instance['cats_id'] = esc_sql( $new_instance['cats_id'] );
				}
			} else {
				$instance['cats_id'] = false;
			}

			if ( ! empty( $new_instance['tags_id'] ) ) {
				if ( is_array( $new_instance['tags_id'] ) ) {
					$instance['tags_id'] = implode( ',', $new_instance['tags_id'] );
				} else {
					$instance['tags_id'] = esc_sql( $new_instance['tags_id'] );
				}
			} else {
				$instance['tags_id'] = false;
			}

			$this->flush_widget_cache();

			return $instance;
		}
	}

	add_action( 'widgets_init', function () {
		register_widget( 'Penci_Product_Lists' );
	} );
}
