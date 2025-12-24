<?php
$options   = [];
$options[] = array(
	'sanitize' => 'sanitize_text_field',
	'label'    => esc_html__( 'Side Cart Style', 'soledad' ),
	'id'       => 'penci_woo_section_sidebarcart_color',
	'type'     => 'soledad-fw-header',
);
$options[] = array(
	'id'       => 'penci_woo_sidecart_bg_color',
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Background Color', 'soledad' ),
);
$options[] = array(
	'id'       => 'penci_woo_sidecart_heading_bg_color',
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Heading Background Color', 'soledad' ),
);
$options[] = array(
	'id'       => 'penci_woo_sidecart_heading_txt_color',
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Heading Color', 'soledad' ),
);
$options[] = array(
	'id'       => 'penci_woo_sidecart_product_title_color',
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Product Title Color', 'soledad' ),
);
$options[] = array(
	'id'       => 'penci_woo_sidecart_product_title_hover_color',
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Product Title Hover Color', 'soledad' ),
);
$options[] = array(
	'id'       => 'penci_woo_sidecart_border_color',
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Border Color', 'soledad' ),
);
$options[] = array(
	'id'       => 'penci_woo_sidecart_price_color',
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Price Color', 'soledad' ),
);
$options[] = array(
	'id'       => 'penci_woo_sidecart_pitem_bg_color',
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Product Item Background Color', 'soledad' ),
);
$options[] = array(
	'id'       => 'penci_woo_sidecart_pitem_bg_hover_color',
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Product Item Hover Background Color', 'soledad' ),
);
$options[] = array(
	'id'       => 'penci_woo_sidecart_pitem_bg_text_color',
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Product Item Hover Text Color', 'soledad' ),
);
$options[] = array(
	'id'       => 'penci_woo_sidecart_accent_color',
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Accent Color', 'soledad' ),
);
$options[] = array(
	'id'       => 'penci_woo_sidecart_heading_color',
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Heading Color', 'soledad' ),
);
$options[] = array(
	'id'       => 'penci_woo_sidecart_btn_color',
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Button Background Color', 'soledad' ),
);
$options[] = array(
	'id'       => 'penci_woo_sidecart_btn_text_color',
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Button Text Color', 'soledad' ),
);
$options[] = array(
	'id'       => 'penci_woo_sidecart_btn_hover_color',
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Button Background Hover Color', 'soledad' ),
);
$options[] = array(
	'id'       => 'penci_woo_sidecart_btn_text_hover_color',
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Button Text Hover Color', 'soledad' ),
);
$options[] = array(
	'id'       => 'penci_woo_sidecart_footer_bgcolor',
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Footer Background Color', 'soledad' ),
);
/* Product Item*/
$options[] = array(
	'sanitize' => 'sanitize_text_field',
	'label'    => esc_html__( 'Product Item Style', 'soledad' ),
	'id'       => 'penci_woo_section_product_color_loop',
	'type'     => 'soledad-fw-header',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Product Item Title', 'soledad' ),
	'id'       => 'penci_woo_product_loop_title_color',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Product Item Price Color', 'soledad' ),
	'id'       => 'penci_woo_product_loop_price_color',
);
$options[] = array(
	'id'       => 'penci_woo_product_loop_cat_color',
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Product Item Cat Color', 'soledad' ),
);
$options[] = array(
	'id'       => 'penci_woo_product_loop_cat_hover_color',
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Product Item Cat Hover Color', 'soledad' ),
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Product Item Button Group Background Color', 'soledad' ),
	'id'       => 'penci_woo_product_loop_button_groups_bgcolor',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Product Item Button Color', 'soledad' ),
	'id'       => 'penci_woo_product_loop_button_color',
);
$options[] = array(
	'id'       => 'penci_woo_product_loop_button_hover_color',
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Product Item Button Hover Color', 'soledad' ),
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Product Item Button Background Color', 'soledad' ),
	'id'       => 'penci_woo_product_loop_button_bg_color',
);
$options[] = array(
	'id'       => 'penci_woo_product_loop_button_bg_hover_color',
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Product Item Button Background Hover Color', 'soledad' ),
);
$options[] = array(
	'id'          => 'penci_woo_product_overlay_bg_color',
	'default'     => '',
	'sanitize'    => 'sanitize_hex_color',
	'type'        => 'soledad-fw-color',
	'label'       => __( 'Product Item Overlay Background Color', 'soledad' ),
	'description' => __( 'Apply to Product Item style 5 & style 7', 'soledad' ),
);
$options[] = array(
	'default'     => '0.5',
	'sanitize'    => 'absint',
	'label'       => __( 'Product Item Overlay Opacity', 'soledad' ),
	'description' => __( 'Apply to Product Item style 5 & style 7', 'soledad' ),
	'id'          => 'penci_woo_product_overlay_bg_opacity',
	'type'        => 'soledad-fw-size',
	'ids'         => array(
		'desktop' => 'penci_woo_product_overlay_bg_opacity',
	),
	'choices'     => array(
		'desktop' => array(
			'min'     => 1,
			'max'     => 2000,
			'step'    => 1,
			'edit'    => true,
			'unit'    => '',
			'default' => '0.5',
		),
	),
);
$options[] = array(
	'id'          => 'penci_woo_product_overlay_title_color',
	'default'     => '',
	'sanitize'    => 'sanitize_hex_color',
	'type'        => 'soledad-fw-color',
	'label'       => __( 'Product Item Overlay Title Color', 'soledad' ),
	'description' => __( 'Apply to Product Item style 5 & style 7', 'soledad' ),
);
$options[] = array(
	'id'          => 'penci_woo_product_overlay_link_color',
	'default'     => '',
	'sanitize'    => 'sanitize_hex_color',
	'type'        => 'soledad-fw-color',
	'label'       => __( 'Product Item Overlay Link Color', 'soledad' ),
	'description' => __( 'Apply to Product Item style 5 & style 7', 'soledad' ),
);
$options[] = array(
	'id'          => 'penci_woo_product_overlay_link_hover_color',
	'default'     => '',
	'sanitize'    => 'sanitize_hex_color',
	'type'        => 'soledad-fw-color',
	'label'       => __( 'Product Item Overlay Link Hover Color', 'soledad' ),
	'description' => __( 'Apply to Product Item style 5 & style 7', 'soledad' ),
);
$options[] = array(
	'id'          => 'penci_woo_product_overlay_button_color',
	'default'     => '',
	'sanitize'    => 'sanitize_hex_color',
	'type'        => 'soledad-fw-color',
	'label'       => __( 'Product Item Overlay Button Color', 'soledad' ),
	'description' => __( 'Apply to Product Item style 5 & style 7', 'soledad' ),
);
$options[] = array(
	'id'       => 'penci_woo_product_item_style6_bg',
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Product Item Style 6 Background Color', 'soledad' ),
);
$options[] = array(
	'id'       => 'penci_woo_product_item_style6_text_color',
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Product Item Style 6 Text Color', 'soledad' ),
);
$options[] = array(
	'id'       => 'penci_woo_product_item_style6_title_color',
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Product Item Style 6 Title Color', 'soledad' ),
);
$options[] = array(
	'id'       => 'penci_woo_product_item_style6_link_color',
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Product Item Style 6 Link Color', 'soledad' ),
);
$options[] = array(
	'id'       => 'penci_woo_product_item_style6_link_hover_color',
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Product Item Style 6 Link Hover Color', 'soledad' ),
);
$options[] = array(
	'id'       => 'penci_woo_product_item_style6_price_color',
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Product Item Style 6 Price Color', 'soledad' ),
);
$options[] = array(
	'id'       => 'penci_woo_product_item_style3_atc_bg_color',
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Product Item Style 3 Add To Cart Background Color', 'soledad' ),
);
$options[] = array(
	'id'       => 'penci_woo_product_item_style3_atc_bg_hover_color',
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Product Item Style 3 Add To Cart Hover Background Color', 'soledad' ),
);
$options[] = array(
	'id'       => 'penci_woo_product_item_style3_atc_text_color',
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Product Item Style 3 Add To Cart Text Color', 'soledad' ),
);
$options[] = array(
	'id'       => 'penci_woo_product_item_style3_atc_text_hover_color',
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Product Item Style 3 Add To Cart Hover Text Color', 'soledad' ),
);
$options[] = array(
	'id'       => 'penci_woo_product_item_style5_atc_text_color',
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Product Item Style 5 Add To Cart Text Color', 'soledad' ),
);
$options[] = array(
	'id'       => 'penci_woo_product_item_style5_atc_hv_text_color',
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Product Item Style 5 Add To Cart Hover Text Color', 'soledad' ),
);
$options[] = array(
	'id'       => 'penci_woo_product_item_style5_atc_border_color',
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Product Item Style 5 Add To Cart Border Color', 'soledad' ),
);
$options[] = array(
	'id'       => 'penci_woo_product_item_style5_atc_hv_border_color',
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Product Item Style 5 Add To Cart Hover Border Color', 'soledad' ),
);
$options[] = array(
	'id'       => 'penci_woo_product_item_style5_atc_bg_color',
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Product Item Style 5 Add To Cart Background Color', 'soledad' ),
);
$options[] = array(
	'id'       => 'penci_woo_product_item_style5_atc_hv_bg_color',
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Product Item Style 5 Add To Cart Hover Background Color', 'soledad' ),
);
$options[] = array(
	'id'       => 'penci_woo_product_item_style4_atc_txt_color',
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Product Item Style 4 Add To Cart Text Color', 'soledad' ),
);
$options[] = array(
	'id'       => 'penci_woo_product_item_style4_atc_hv_txt_color',
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Product Item Style 4 Add To Cart Hover Text Color', 'soledad' ),
);
$options[] = array(
	'id'       => 'penci_woo_product_item_style4_atc_bg_color',
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Product Item Style 4 Add To Cart Background Color', 'soledad' ),
);
$options[] = array(
	'id'       => 'penci_woo_product_item_style4_atc_hv_bg_color',
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Product Item Style 4 Add To Cart Hover Background Color', 'soledad' ),
);
/* Product Category Loop */
$options[] = array(
	'sanitize' => 'sanitize_text_field',
	'label'    => __( 'Product Stock Progress Style', 'soledad' ),
	'id'       => 'penci_woo_product_loop_progress_section',
	'type'     => 'soledad-fw-header',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Product Item Progress Background Color', 'soledad' ),
	'id'       => 'penci_woo_product_loop_progress_bg_color',
);
$options[] = array(
	'id'          => 'penci_woo_product_loop_progress_height',
	'default'     => '',
	'sanitize'    => 'absint',
	'label'       => __( 'Custom Progress Bar Height', 'soledad' ),
	'description' => __( 'Set a custom height of the progress bar.', 'soledad' ),
	'type'        => 'soledad-fw-size',
	'ids'         => array(
		'desktop' => 'penci_woo_product_loop_progress_height',
	),
	'choices'     => array(
		'desktop' => array(
			'min'  => 1,
			'max'  => 100,
			'step' => 1,
			'edit' => true,
			'unit' => 'px',
		),
	),
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Product Item Progress Active Background Color', 'soledad' ),
	'id'       => 'penci_woo_product_loop_progress_active_bg_color',
);
/* Product Category Loop */
$options[] = array(
	'sanitize' => 'sanitize_text_field',
	'label'    => __( 'Product Category Loop Style', 'soledad' ),
	'id'       => 'penci_woo_section_product_cat_loop_color',
	'type'     => 'soledad-fw-header',
);
$options[] = array(
	'id'       => 'penci_woo_section_product_cat_loop_title_color',
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Product Category Color', 'soledad' ),
);
$options[] = array(
	'id'       => 'penci_woo_section_product_cat_loop_meta_color',
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Product Meta Color', 'soledad' ),
);
$options[] = array(
	'id'       => 'penci_woo_section_product_cat_loop_overlay_color',
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Product Overlay Color', 'soledad' ),
);
/* Cross Sell & Related Products */
$options[] = array(
	'sanitize' => 'sanitize_text_field',
	'label'    => __( 'Product Item Style (Apply for Related & Upsell Products)', 'soledad' ),
	'id'       => 'penci_woo_section_product_related_upsell_color',
	'type'     => 'soledad-fw-header',
);
$options[] = array(
	'default'     => '',
	'sanitize'    => 'sanitize_hex_color',
	'label'       => __( 'Product Item Title', 'soledad' ),
	'type'        => 'soledad-fw-color',
	'description' => __( 'Apply for Upsell & Relate Products on Single Product Page', 'soledad' ),
	'id'          => 'penci_woo_product_loop_title_color_reup',
);
$options[] = array(
	'default'     => '',
	'sanitize'    => 'sanitize_hex_color',
	'label'       => __( 'Product Item Price Color', 'soledad' ),
	'type'        => 'soledad-fw-color',
	'description' => __( 'Apply for Upsell & Relate Products on Single Product Page', 'soledad' ),
	'id'          => 'penci_woo_product_loop_price_color_reup',
);
$options[] = array(
	'id'          => 'penci_woo_product_loop_cat_color_reup',
	'default'     => '',
	'sanitize'    => 'sanitize_hex_color',
	'type'        => 'soledad-fw-color',
	'label'       => __( 'Product Item Cat Color', 'soledad' ),
	'description' => __( 'Apply for Upsell & Relate Products on Single Product Page', 'soledad' ),
);
$options[] = array(
	'id'          => 'penci_woo_product_loop_cat_hover_color_reup',
	'default'     => '',
	'sanitize'    => 'sanitize_hex_color',
	'type'        => 'soledad-fw-color',
	'label'       => __( 'Product Item Cat Hover Color', 'soledad' ),
	'description' => __( 'Apply for Upsell & Relate Products on Single Product Page', 'soledad' ),
);
/* Cross Sell Products */
$options[] = array(
	'sanitize' => 'sanitize_text_field',
	'label'    => __( 'Product Item Style (Apply for Crossell Products)', 'soledad' ),
	'id'       => 'penci_woo_section_product_crossell_color',
	'type'     => 'soledad-fw-header',
);
$options[] = array(
	'default'     => '',
	'sanitize'    => 'sanitize_hex_color',
	'type'        => 'soledad-fw-color',
	'label'       => __( 'Product Item Title', 'soledad' ),
	'description' => __( 'Apply for Crossell Products on Cart Page', 'soledad' ),
	'id'          => 'penci_woo_product_loop_title_color_cross',
);
$options[] = array(
	'default'     => '',
	'sanitize'    => 'sanitize_hex_color',
	'type'        => 'soledad-fw-color',
	'label'       => __( 'Product Item Price Color', 'soledad' ),
	'description' => __( 'Apply for Crossell Products on Cart Page', 'soledad' ),
	'id'          => 'penci_woo_product_loop_price_color_cross',
);
$options[] = array(
	'id'          => 'penci_woo_product_loop_cat_color_cross',
	'default'     => '',
	'sanitize'    => 'sanitize_hex_color',
	'type'        => 'soledad-fw-color',
	'label'       => __( 'Product Item Cat Color', 'soledad' ),
	'description' => __( 'Apply for Crossell Products on Cart Page', 'soledad' ),
);
$options[] = array(
	'id'          => 'penci_woo_product_loop_cat_hover_color_cross',
	'default'     => '',
	'sanitize'    => 'sanitize_hex_color',
	'type'        => 'soledad-fw-color',
	'label'       => __( 'Product Item Cat Hover Color', 'soledad' ),
	'description' => __( 'Apply for Crossell Products on Cart Page', 'soledad' ),
);
/* Product Page */
$options[] = array(
	'sanitize' => 'sanitize_text_field',
	'label'    => esc_html__( 'Product Page', 'soledad' ),
	'id'       => 'penci_woo_section_product_page_color',
	'type'     => 'soledad-fw-header',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Product Page General Text Color', 'soledad' ),
	'id'       => 'penci_product_page_general_text_color',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Product Page Links Color', 'soledad' ),
	'id'       => 'penci_product_page_general_link_color',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Product Page Links Hover Color', 'soledad' ),
	'id'       => 'penci_product_page_general_link_hover_color',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Product Page General Border Color', 'soledad' ),
	'id'       => 'penci_product_page_general_border_color',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Swatches Button Border Color', 'soledad' ),
	'id'       => 'penci_product_page_button_swatches_border_color',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Swatches Button Border Hover Color', 'soledad' ),
	'id'       => 'penci_product_page_button_swatches_border_hover_color',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Swatches Button Background Color', 'soledad' ),
	'id'       => 'penci_product_page_button_swatches_bg_color',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Swatches Button Background Hover Color', 'soledad' ),
	'id'       => 'penci_product_page_button_swatches_bg_hover_color',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Add To Cart Background Color', 'soledad' ),
	'id'       => 'penci_product_page_button_atc_bg_color',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Add To Cart Background Hover Color', 'soledad' ),
	'id'       => 'penci_product_page_button_atc_bg_hover_color',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Product Meta Color', 'soledad' ),
	'id'       => 'penci_product_page_meta_color',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Product Meta Link Color', 'soledad' ),
	'id'       => 'penci_product_page_meta_link_color',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Product Meta Link Hover Color', 'soledad' ),
	'id'       => 'penci_product_page_meta_link_hover_color',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Product Tab Title Color', 'soledad' ),
	'id'       => 'penci_product_page_tab_title_color',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Product Tab Title Active Color', 'soledad' ),
	'id'       => 'penci_product_page_tab_title_active_color',
);
$options[] = array(
	'sanitize' => 'sanitize_text_field',
	'label'    => esc_html__( 'WooCommerce Additional Pages', 'soledad' ),
	'id'       => 'penci_woo_section_woo_page',
	'type'     => 'soledad-fw-header',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Title Color', 'soledad' ),
	'id'       => 'penci_woo_page_title_color',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Button Background Color', 'soledad' ),
	'id'       => 'penci_woo_page_button_bg_color',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Button Background HoverColor', 'soledad' ),
	'id'       => 'penci_woo_page_button_bg_hover_color',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Secondary Button Background Color', 'soledad' ),
	'id'       => 'penci_woo_page_button_alt_bg_color',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Secondary Button Background Color', 'soledad' ),
	'id'       => 'penci_woo_page_button_alt_bg_hover_color',
);
$options[] = array(
	'sanitize' => 'sanitize_text_field',
	'label'    => esc_html__( 'Loading Icon', 'soledad' ),
	'id'       => 'penci_woo_loading_ic_heading',
	'type'     => 'soledad-fw-header',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Circle Loading First Color', 'soledad' ),
	'id'       => 'penci_woo_loading_cl1',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Circle Loading Second Color', 'soledad' ),
	'id'       => 'penci_woo_loading_cl2',
);
// checkout pages
$options[] = array(
	'default' => '',
	'type'    => 'soledad-fw-header',
	'label'   => __( 'Checkout Page Color', 'soledad' ),
	'id'      => 'penci_woo_checkout_head',
);
$options[] = array(
	'id'        => 'penci_woo_checkout_breadcrumb_color',
	'default'   => '',
	'transport' => 'refresh',
	'sanitize'  => 'sanitize_hex_color',
	'type'      => 'soledad-fw-color',
	'label'     => __( 'Breadcrumb Color', 'soledad' ),
);
$options[] = array(
	'id'        => 'penci_woo_checkout_breadcrumb_active_color',
	'default'   => '',
	'transport' => 'refresh',
	'sanitize'  => 'sanitize_hex_color',
	'type'      => 'soledad-fw-color',
	'label'     => __( 'Breadcrumb Active Color', 'soledad' ),
);
$options[] = array(
	'id'        => 'penci_woo_checkout_form_label_color',
	'default'   => '',
	'transport' => 'refresh',
	'sanitize'  => 'sanitize_hex_color',
	'type'      => 'soledad-fw-color',
	'label'     => __( 'Label Color', 'soledad' ),
);
$options[] = array(
	'id'        => 'penci_woo_checkout_form_border_color',
	'default'   => '',
	'transport' => 'refresh',
	'sanitize'  => 'sanitize_hex_color',
	'type'      => 'soledad-fw-color',
	'label'     => __( 'Form Input Border Color', 'soledad' ),
);
$options[] = array(
	'id'        => 'penci_woo_checkout_form_border_focus_color',
	'default'   => '',
	'transport' => 'refresh',
	'sanitize'  => 'sanitize_hex_color',
	'type'      => 'soledad-fw-color',
	'label'     => __( 'Form Input Focus Border Color', 'soledad' ),
);
$options[] = array(
	'id'        => 'penci_woo_checkout_form_bg_color',
	'default'   => '',
	'transport' => 'refresh',
	'sanitize'  => 'sanitize_hex_color',
	'type'      => 'soledad-fw-color',
	'label'     => __( 'Form Input Background Color', 'soledad' ),
);
$options[] = array(
	'id'        => 'penci_woo_checkout_form_bg_focus_color',
	'default'   => '',
	'transport' => 'refresh',
	'sanitize'  => 'sanitize_hex_color',
	'type'      => 'soledad-fw-color',
	'label'     => __( 'Form Input Focus Background Color', 'soledad' ),
);
$options[] = array(
	'id'        => 'penci_woo_checkout_order_bg_color',
	'default'   => '',
	'transport' => 'refresh',
	'sanitize'  => 'sanitize_hex_color',
	'type'      => 'soledad-fw-color',
	'label'     => __( 'Order Form Background Color', 'soledad' ),
);
$options[] = array(
	'id'        => 'penci_woo_checkout_order_table_bg_color',
	'default'   => '',
	'transport' => 'refresh',
	'sanitize'  => 'sanitize_hex_color',
	'type'      => 'soledad-fw-color',
	'label'     => __( 'Order Form Table Background Color', 'soledad' ),
);
$options[] = array(
	'id'        => 'penci_woo_checkout_order_table_color',
	'default'   => '',
	'transport' => 'refresh',
	'sanitize'  => 'sanitize_hex_color',
	'type'      => 'soledad-fw-color',
	'label'     => __( 'Order Form Table Text Color', 'soledad' ),
);
$options[] = array(
	'id'        => 'penci_woo_checkout_order_head_color',
	'default'   => '',
	'transport' => 'refresh',
	'sanitize'  => 'sanitize_hex_color',
	'type'      => 'soledad-fw-color',
	'label'     => __( 'Order Form Table Head Color', 'soledad' ),
);
$options[] = array(
	'id'       => 'penci_woo_checkout_order_accent_color',
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Order Form Table Accent Color', 'soledad' ),
);
$options[] = array(
	'id'       => 'penci_woo_checkout_order_table_border_color',
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Order Form Table Border Color', 'soledad' ),
);
$options[] = array(
	'id'       => 'penci_woo_checkout_btn_bg_color',
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Button Background Color', 'soledad' ),
);
$options[] = array(
	'id'       => 'penci_woo_checkout_btn_txt_color',
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Button Text Color', 'soledad' ),
);
$options[] = array(
	'id'       => 'penci_woo_checkout_btn_hover_bg_color',
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Button Hover Background Color', 'soledad' ),
);
$options[] = array(
	'id'       => 'penci_woo_checkout_btn_hover_txt_color',
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Button Hover Text Color', 'soledad' ),
);
$options[] = array(
	'id'       => 'penci_woo_checkout_review_order_bg_color',
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Review Order Background Color', 'soledad' ),
);
$options[] = array(
	'id'       => 'penci_woo_checkout_before_content',
	'default'  => '',
	'sanitize' => 'penci_sanitize_textarea_field',
	'label'    => __( 'Custom Content Before Checkout Form', 'soledad' ),
	'type'     => 'soledad-fw-textarea',
);
$options[] = array(
	'id'       => 'penci_woo_checkout_after_content',
	'default'  => '',
	'sanitize' => 'penci_sanitize_textarea_field',
	'label'    => __( 'Custom Content After Checkout Form', 'soledad' ),
	'type'     => 'soledad-fw-textarea',
);
/* Notice Color*/
$options[] = array(
	'default' => '',
	'type'    => 'soledad-fw-header',
	'label'   => __( 'Notices Color', 'soledad' ),
	'id'      => 'penci_header_notice_color_head',
);
$options[] = array(
	'id'        => 'penci_woo_notice_bg_color',
	'default'   => '',
	'transport' => 'refresh',
	'sanitize'  => 'sanitize_hex_color',
	'type'      => 'soledad-fw-color',
	'label'     => __( 'Notice Background Color', 'soledad' ),
);
$options[] = array(
	'id'        => 'penci_woo_notice_txt_color',
	'default'   => '',
	'transport' => 'refresh',
	'sanitize'  => 'sanitize_hex_color',
	'type'      => 'soledad-fw-color',
	'label'     => __( 'Notice Text Color', 'soledad' ),
);

return $options;
