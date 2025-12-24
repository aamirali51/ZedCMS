<?php
/**
 * Widget sort by
 *
 */

if ( ! class_exists( 'Penci_Widget_Sorting' ) ) {
	class Penci_Widget_Sorting extends WPH_Widget {

		function __construct() {
			if ( ! function_exists( 'wc_get_attribute_taxonomies' ) ) {
				return;
			}

			// Configure widget array
			$args = array(
				// Widget Backend label
				'label'       => penci_get_theme_name('.Soledad',true).esc_html__( 'WooCommerce Sort by', 'soledad' ),
				// Widget Backend Description
				'description' => esc_html__( 'Sort products by name, price, popularity etc.', 'soledad' ),
				'slug'        => 'soledad-woocommerce-sort-by',
			);

			// Configure the widget fields

			// fields array
			$args['fields'] = array(
				array(
					'id'   => 'title',
					'type' => 'text',
					'std'  => esc_html__( 'Sort by', 'soledad' ),
					'name' => esc_html__( 'Title', 'soledad' )
				),
			);

			// create widget
			$this->create_widget( $args );
		}

		// Output function
		// Based on woo widget @version  2.3.0
		function widget( $args, $instance ) {
			global $wp_query;

			if ( ! woocommerce_products_will_display() ) {
				return;
			}

			$orderby                 = isset( $_GET['orderby'] ) ? wc_clean( $_GET['orderby'] ) : apply_filters( 'woocommerce_default_catalog_orderby', get_option( 'woocommerce_default_catalog_orderby' ) );
			$show_default_orderby    = 'menu_order' === apply_filters( 'woocommerce_default_catalog_orderby', get_option( 'woocommerce_default_catalog_orderby' ) );
			$catalog_orderby_options = apply_filters( 'woocommerce_catalog_orderby', array(
				'menu_order' => esc_html__( 'Default', 'soledad' ),
				'popularity' => esc_html__( 'Popularity', 'soledad' ),
				'rating'     => esc_html__( 'Average rating', 'soledad' ),
				'date'       => esc_html__( 'Newness', 'soledad' ),
				'price'      => esc_html__( 'Price: low to high', 'soledad' ),
				'price-desc' => esc_html__( 'Price: high to low', 'soledad' )
			) );

			if ( ! $show_default_orderby ) {
				unset( $catalog_orderby_options['menu_order'] );
			}

			if ( get_option( 'woocommerce_enable_review_rating' ) === 'no' ) {
				unset( $catalog_orderby_options['rating'] );
			}

			echo wp_kses_post( $args['before_widget'] );

			if ( $title = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance ) ) {
				echo wp_kses_post( $args['before_title'] ) . $title . wp_kses_post( $args['after_title'] );
			}

			wc_get_template( 'loop/orderby.php', array(
				'catalog_orderby_options' => $catalog_orderby_options,
				'orderby'                 => $orderby,
				'show_default_orderby'    => $show_default_orderby,
				'list'                    => true
			) );

			echo wp_kses_post( $args['after_widget'] );
		}

		function form( $instance ) {
			parent::form( $instance );
		}

	} // class
	add_action( 'widgets_init', function () {
		register_widget( 'Penci_Widget_Sorting' );
	} );
}
