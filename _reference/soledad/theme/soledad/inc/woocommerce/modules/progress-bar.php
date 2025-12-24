<?php

class penci_product_progress_bar {

	function __construct() {
		add_action( 'woocommerce_process_product_meta_simple', array( $this, 'save_total_stock_quantity' ) );
		add_action( 'woocommerce_process_product_meta_variable', array( $this, 'save_total_stock_quantity' ) );
		add_action( 'woocommerce_process_product_meta_grouped', array( $this, 'save_total_stock_quantity' ) );
		add_action( 'woocommerce_process_product_meta_external', array( $this, 'save_total_stock_quantity' ) );
		add_action( 'woocommerce_product_options_inventory_product_data', array(
			$this,
			'total_stock_quantity_input'
		) );

		add_action( 'penci_after_shop_loop', array( $this, 'stock_progress_bar' ) );
		add_action( 'woocommerce_single_product_summary', array( $this, 'stock_progress_bar' ), 15 );
	}

	public function stock_progress_bar() {
		$stock_press = wc_get_loop_prop( 'stock_progress_bar', false );
		if ( ! $stock_press ) {
			return;
		}
		$product_id    = get_the_ID();
		$total_stock   = (int) get_post_meta( $product_id, 'penci_total_stock_quantity', true );
		$current_stock = (int) get_post_meta( $product_id, '_stock', true );
		$total_sales   = (int) get_post_meta( $product_id, 'total_sales', true );
		$total_stock   = ! empty( $total_stock ) && $total_stock > 0 ? $total_stock : $total_sales + $current_stock;

		if ( ! $total_stock ) {
			return;
		}

		$total_sold = $total_stock > $current_stock ? $total_stock - $current_stock : 0;
		$percentage = $total_sold > 0 ? round( $total_sold / $total_stock * 100 ) : 0;

		if ( $current_stock > 0 ) {
			get_template_part(
				'woocommerce/product-loop/progress',
				'bar',
				array(
					'total_sold'    => $total_sold,
					'current_stock' => $current_stock,
					'percentage'    => $percentage,
				)
			);
		}
	}

	public function total_stock_quantity_input() {
		echo '<div class="options_group">';
		woocommerce_wp_text_input(
			array(
				'id'          => 'penci_total_stock_quantity',
				'label'       => penci_woo_translate_text( 'penci_woo_trans_innumbeistock' ),
				'desc_tip'    => 'true',
				'description' => penci_woo_translate_text( 'penci_woo_trans_rqinnumbeistock' ),
				'type'        => 'text',
			)
		);
		echo '</div>';
	}

	public function save_total_stock_quantity( $post_id ) { // phpcs:ignore
		$stock_quantity = isset( $_POST['penci_total_stock_quantity'] ) && $_POST['penci_total_stock_quantity'] ? wc_clean( $_POST['penci_total_stock_quantity'] ) : ''; // phpcs:ignore

		update_post_meta( $post_id, 'penci_total_stock_quantity', $stock_quantity );
	}
}

$penci_product_progress_bar = new penci_product_progress_bar();
