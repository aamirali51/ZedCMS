<?php
$options   = [];
$options[] = array(
	'sanitize' => 'sanitize_text_field',
	'label'    => esc_html__( 'Product Item Font Size', 'soledad' ),
	'id'       => 'penci_woo_section_product_loop_typo',
	'type'     => 'soledad-fw-header',
);
$options[] = array(
	'id'       => 'pencidesign_woo_product_loop_title_m_font_size',
	'type'     => 'soledad-fw-hidden',
	'sanitize' => 'absint',
);
$options[] = array(
	'label'    => __( 'Font Size for Product Item Title', 'soledad' ),
	'id'       => 'pencidesign_woo_product_loop_title_font_size',
	'type'     => 'soledad-fw-size',
	'sanitize' => 'absint',
	'ids'      => array(
		'desktop' => 'pencidesign_woo_product_loop_title_font_size',
		'mobile'  => 'pencidesign_woo_product_loop_title_m_font_size',
	),
	'choices'  => array(
		'desktop' => array(
			'min'  => 1,
			'max'  => 100,
			'step' => 1,
			'edit' => true,
			'unit' => 'px',
		),
		'mobile'  => array(
			'min'  => 1,
			'max'  => 100,
			'step' => 1,
			'edit' => true,
			'unit' => 'px',
		),
	),
);
$options[] = array(
	'label'    => '',
	'id'       => 'pencidesign_woo_product_loop_list_title_m_font_size',
	'type'     => 'soledad-fw-hidden',
	'sanitize' => 'absint',
);
$options[] = array(
	'label'    => __( 'Font Size for Product Item Title on Listing Layout', 'soledad' ),
	'id'       => 'pencidesign_woo_product_loop_list_title_font_size',
	'type'     => 'soledad-fw-size',
	'sanitize' => 'absint',
	'ids'      => array(
		'desktop' => 'pencidesign_woo_product_loop_list_title_font_size',
		'mobile'  => 'pencidesign_woo_product_loop_list_title_m_font_size',
	),
	'choices'  => array(
		'desktop' => array(
			'min'  => 1,
			'max'  => 100,
			'step' => 1,
			'edit' => true,
			'unit' => 'px',
		),
		'mobile'  => array(
			'min'  => 1,
			'max'  => 100,
			'step' => 1,
			'edit' => true,
			'unit' => 'px',
		),
	),
);
$options[] = array(
	'label'       => '',
	'description' => '',
	'id'          => 'pencidesign_woo_product_loop_meta_m_font_size',
	'type'        => 'soledad-fw-hidden',
	'sanitize'    => 'absint',
);
$options[] = array(
	'label'    => __( 'Font Size for Product Meta', 'soledad' ),
	'id'       => 'pencidesign_woo_product_loop_meta_font_size',
	'type'     => 'soledad-fw-size',
	'sanitize' => 'absint',
	'ids'      => array(
		'desktop' => 'pencidesign_woo_product_loop_meta_font_size',
		'mobile'  => 'pencidesign_woo_product_loop_meta_m_font_size',
	),
	'choices'  => array(
		'desktop' => array(
			'min'  => 1,
			'max'  => 100,
			'step' => 1,
			'edit' => true,
			'unit' => 'px',
		),
		'mobile'  => array(
			'min'  => 1,
			'max'  => 100,
			'step' => 1,
			'edit' => true,
			'unit' => 'px',
		),
	),
);
$options[] = array(
	'label'       => '',
	'description' => '',
	'id'          => 'pencidesign_woo_product_loop_price_m_font_size',
	'type'        => 'soledad-fw-hidden',
	'sanitize'    => 'absint',
);
$options[] = array(
	'label'    => __( 'Font Size for Product Price', 'soledad' ),
	'id'       => 'pencidesign_woo_product_loop_price_font_size',
	'type'     => 'soledad-fw-size',
	'sanitize' => 'absint',
	'ids'      => array(
		'desktop' => 'pencidesign_woo_product_loop_price_font_size',
		'mobile'  => 'pencidesign_woo_product_loop_price_m_font_size',
	),
	'choices'  => array(
		'desktop' => array(
			'min'  => 1,
			'max'  => 100,
			'step' => 1,
			'edit' => true,
			'unit' => 'px',
		),
		'mobile'  => array(
			'min'  => 1,
			'max'  => 100,
			'step' => 1,
			'edit' => true,
			'unit' => 'px',
		),
	),
);
$options[] = array(
	'label'       => '',
	'description' => '',
	'id'          => 'pencidesign_woo_product_loop_button_icon_m_size',
	'type'        => 'soledad-fw-hidden',
	'sanitize'    => 'absint',
);
$options[] = array(
	'label'    => __( 'Font Size for Button Icon', 'soledad' ),
	'id'       => 'pencidesign_woo_product_loop_button_icon_size',
	'type'     => 'soledad-fw-size',
	'sanitize' => 'absint',
	'ids'      => array(
		'desktop' => 'pencidesign_woo_product_loop_button_icon_size',
		'mobile'  => 'pencidesign_woo_product_loop_button_icon_m_size',
	),
	'choices'  => array(
		'desktop' => array(
			'min'  => 1,
			'max'  => 100,
			'step' => 1,
			'edit' => true,
			'unit' => 'px',
		),
		'mobile'  => array(
			'min'  => 1,
			'max'  => 100,
			'step' => 1,
			'edit' => true,
			'unit' => 'px',
		),
	),
);
$options[] = array(
	'label'       => '',
	'description' => '',
	'id'          => 'pencidesign_woo_product_loop_button_3_m_size',
	'type'        => 'soledad-fw-hidden',
	'sanitize'    => 'absint',
);
$options[] = array(
	'label'    => __( 'Font Size for Button - Product Style 3', 'soledad' ),
	'id'       => 'pencidesign_woo_product_loop_button_3_size',
	'type'     => 'soledad-fw-size',
	'sanitize' => 'absint',
	'ids'      => array(
		'desktop' => 'pencidesign_woo_product_loop_button_3_size',
		'mobile'  => 'pencidesign_woo_product_loop_button_3_m_size',
	),
	'choices'  => array(
		'desktop' => array(
			'min'  => 1,
			'max'  => 100,
			'step' => 1,
			'edit' => true,
			'unit' => 'px',
		),
		'mobile'  => array(
			'min'  => 1,
			'max'  => 100,
			'step' => 1,
			'edit' => true,
			'unit' => 'px',
		),
	),
);
$options[] = array(
	'label'       => '',
	'description' => '',
	'id'          => 'pencidesign_woo_product_loop_button_4_m_size',
	'type'        => 'soledad-fw-hidden',
	'sanitize'    => 'absint',
);
$options[] = array(
	'label'    => __( 'Font Size for Button - Product Style 4', 'soledad' ),
	'id'       => 'pencidesign_woo_product_loop_button_4_size',
	'type'     => 'soledad-fw-size',
	'sanitize' => 'absint',
	'ids'      => array(
		'desktop' => 'pencidesign_woo_product_loop_button_4_size',
		'mobile'  => 'pencidesign_woo_product_loop_button_4_m_size',
	),
	'choices'  => array(
		'desktop' => array(
			'min'  => 1,
			'max'  => 100,
			'step' => 1,
			'edit' => true,
			'unit' => 'px',
		),
		'mobile'  => array(
			'min'  => 1,
			'max'  => 100,
			'step' => 1,
			'edit' => true,
			'unit' => 'px',
		),
	),
);
$options[] = array(
	'label'       => '',
	'description' => '',
	'id'          => 'pencidesign_woo_product_loop_button_5_m_size',
	'type'        => 'soledad-fw-hidden',
	'sanitize'    => 'absint',
);
$options[] = array(
	'label'    => __( 'Font Size for Button - Product Style 5', 'soledad' ),
	'id'       => 'pencidesign_woo_product_loop_button_5_size',
	'type'     => 'soledad-fw-size',
	'sanitize' => 'absint',
	'ids'      => array(
		'desktop' => 'pencidesign_woo_product_loop_button_5_size',
		'mobile'  => 'pencidesign_woo_product_loop_button_5_m_size',
	),
	'choices'  => array(
		'desktop' => array(
			'min'  => 1,
			'max'  => 100,
			'step' => 1,
			'edit' => true,
			'unit' => 'px',
		),
		'mobile'  => array(
			'min'  => 1,
			'max'  => 100,
			'step' => 1,
			'edit' => true,
			'unit' => 'px',
		),
	),
);
/* Product Category Loop */
$options[] = array(
	'sanitize' => 'sanitize_text_field',
	'label'    => esc_html__( 'Product Category Loop Font Size', 'soledad' ),
	'id'       => 'penci_woo_section_product_cat_typo',
	'type'     => 'soledad-fw-header',
);
$options[] = array(
	'label'       => '',
	'description' => '',
	'id'          => 'penci_woo_loop_cat_font_size_m',
	'type'        => 'soledad-fw-hidden',
	'sanitize'    => 'absint',
);
$options[] = array(
	'label'    => __( 'Font Size for Product Category Title', 'soledad' ),
	'id'       => 'penci_woo_loop_cat_font_size',
	'type'     => 'soledad-fw-size',
	'sanitize' => 'absint',
	'ids'      => array(
		'desktop' => 'penci_woo_loop_cat_font_size',
		'mobile'  => 'penci_woo_loop_cat_font_size_m',
	),
	'choices'  => array(
		'desktop' => array(
			'min'  => 1,
			'max'  => 100,
			'step' => 1,
			'edit' => true,
			'unit' => 'px',
		),
		'mobile'  => array(
			'min'  => 1,
			'max'  => 100,
			'step' => 1,
			'edit' => true,
			'unit' => 'px',
		),
	),
);
$options[] = array(
	'label'       => '',
	'description' => '',
	'id'          => 'penci_woo_loop_meta_font_size_m',
	'type'        => 'soledad-fw-hidden',
	'sanitize'    => 'absint',
);
$options[] = array(
	'label'    => __( 'Font Size for Product Category Meta', 'soledad' ),
	'id'       => 'penci_woo_loop_meta_font_size',
	'type'     => 'soledad-fw-size',
	'sanitize' => 'absint',
	'ids'      => array(
		'desktop' => 'penci_woo_loop_meta_font_size',
		'mobile'  => 'penci_woo_loop_meta_font_size_m',
	),
	'choices'  => array(
		'desktop' => array(
			'min'  => 1,
			'max'  => 100,
			'step' => 1,
			'edit' => true,
			'unit' => 'px',
		),
		'mobile'  => array(
			'min'  => 1,
			'max'  => 100,
			'step' => 1,
			'edit' => true,
			'unit' => 'px',
		),
	),
);
/* Single Product */
$options[] = array(
	'sanitize' => 'sanitize_text_field',
	'label'    => esc_html__( 'Single Product Font Size', 'soledad' ),
	'id'       => 'penci_woo_section_product_single_typo',
	'type'     => 'soledad-fw-header',
);
$options[] = array(
	'label'       => '',
	'description' => '',
	'id'          => 'pencidesign_woo_fontsize_m_product_price',
	'type'        => 'soledad-fw-hidden',
	'sanitize'    => 'absint',
);
$options[] = array(
	'label'    => __( 'Font Size for Product Title', 'soledad' ),
	'id'       => 'pencidesign_woo_fontsize_product_single_title',
	'type'     => 'soledad-fw-size',
	'sanitize' => 'absint',
	'ids'      => array(
		'desktop' => 'pencidesign_woo_fontsize_product_single_title',
		'mobile'  => 'pencidesign_woo_fontsize_product_single_m_title',
	),
	'choices'  => array(
		'desktop' => array(
			'min'  => 1,
			'max'  => 100,
			'step' => 1,
			'edit' => true,
			'unit' => 'px',
		),
		'mobile'  => array(
			'min'  => 1,
			'max'  => 100,
			'step' => 1,
			'edit' => true,
			'unit' => 'px',
		),
	),
);
$options[] = array(
	'label'       => '',
	'description' => '',
	'id'          => 'pencidesign_woo_fontsize_m_product_price',
	'type'        => 'soledad-fw-hidden',
	'sanitize'    => 'absint',
);
$options[] = array(
	'label'    => __( 'Font Size for Product Price', 'soledad' ),
	'id'       => 'pencidesign_woo_fontsize_product_price',
	'type'     => 'soledad-fw-size',
	'sanitize' => 'absint',
	'ids'      => array(
		'desktop' => 'pencidesign_woo_fontsize_product_price',
		'mobile'  => 'pencidesign_woo_fontsize_m_product_price',
	),
	'choices'  => array(
		'desktop' => array(
			'min'  => 1,
			'max'  => 100,
			'step' => 1,
			'edit' => true,
			'unit' => 'px',
		),
		'mobile'  => array(
			'min'  => 1,
			'max'  => 100,
			'step' => 1,
			'edit' => true,
			'unit' => 'px',
		),
	),
);
$options[] = array(
	'label'       => '',
	'description' => '',
	'id'          => 'pencidesign_woo_fontsize_m_product_breadcrumb',
	'type'        => 'soledad-fw-hidden',
	'sanitize'    => 'absint',
);
$options[] = array(
	'label'    => __( 'Font Size for Product Breadcrumb', 'soledad' ),
	'id'       => 'pencidesign_woo_fontsize_product_meta',
	'type'     => 'soledad-fw-size',
	'sanitize' => 'absint',
	'ids'      => array(
		'desktop' => 'pencidesign_woo_fontsize_product_breadcrumb',
		'mobile'  => 'pencidesign_woo_fontsize_m_product_breadcrumb',
	),
	'choices'  => array(
		'desktop' => array(
			'min'  => 1,
			'max'  => 100,
			'step' => 1,
			'edit' => true,
			'unit' => 'px',
		),
		'mobile'  => array(
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
	'sanitize' => 'absint',
	'label'    => __( 'Font Size for Product General Text', 'soledad' ),
	'id'       => 'pencidesign_woo_fontsize_product_general',
	'type'     => 'soledad-fw-size',
	'ids'      => array(
		'desktop' => 'pencidesign_woo_fontsize_product_general',
	),
	'choices'  => array(
		'desktop' => array(
			'min'  => 1,
			'max'  => 300,
			'step' => 1,
			'edit' => true,
			'unit' => 'px',
		),
	),
);
$options[] = array(
	'label'       => '',
	'description' => '',
	'id'          => 'pencidesign_woo_fontsize_product_tab_m_title',
	'type'        => 'soledad-fw-hidden',
	'sanitize'    => 'absint',
);
$options[] = array(
	'label'    => __( 'Font Size for Product Tab Title', 'soledad' ),
	'id'       => 'pencidesign_woo_fontsize_product_meta',
	'type'     => 'soledad-fw-size',
	'sanitize' => 'absint',
	'ids'      => array(
		'desktop' => 'pencidesign_woo_fontsize_product_tab_title',
		'mobile'  => 'pencidesign_woo_fontsize_product_tab_m_title',
	),
	'choices'  => array(
		'desktop' => array(
			'min'  => 1,
			'max'  => 100,
			'step' => 1,
			'edit' => true,
			'unit' => 'px',
		),
		'mobile'  => array(
			'min'  => 1,
			'max'  => 100,
			'step' => 1,
			'edit' => true,
			'unit' => 'px',
		),
	),
);
$options[] = array(
	'label'       => '',
	'description' => '',
	'id'          => 'pencidesign_woo_fontsize_m_product_meta',
	'type'        => 'soledad-fw-hidden',
	'sanitize'    => 'absint',
);
$options[] = array(
	'label'    => __( 'Font Size for Product Meta', 'soledad' ),
	'id'       => 'pencidesign_woo_fontsize_product_meta',
	'type'     => 'soledad-fw-size',
	'sanitize' => 'absint',
	'ids'      => array(
		'desktop' => 'pencidesign_woo_fontsize_product_meta',
		'mobile'  => 'pencidesign_woo_fontsize_m_product_meta',
	),
	'choices'  => array(
		'desktop' => array(
			'min'  => 1,
			'max'  => 100,
			'step' => 1,
			'edit' => true,
			'unit' => 'px',
		),
		'mobile'  => array(
			'min'  => 1,
			'max'  => 100,
			'step' => 1,
			'edit' => true,
			'unit' => 'px',
		),
	),
);
/* Cart/Checkout/Thank you page */
$options[] = array(
	'sanitize' => 'sanitize_text_field',
	'label'    => esc_html__( 'Additional Pages Font Size', 'soledad' ),
	'id'       => 'penci_woo_section_product_additional_pages_typo',
	'type'     => 'soledad-fw-header',
);
$options[] = array(
	'label'       => '',
	'description' => '',
	'id'          => 'pencidesign_woo_fontsize_pages_nav_font_size_m',
	'type'        => 'soledad-fw-hidden',
	'sanitize'    => 'absint',
);
$options[] = array(
	'label'    => __( 'Font Size for Navigation Step', 'soledad' ),
	'id'       => 'pencidesign_woo_fontsize_pages_nav_font_size',
	'type'     => 'soledad-fw-size',
	'sanitize' => 'absint',
	'ids'      => array(
		'desktop' => 'pencidesign_woo_fontsize_pages_nav_font_size',
		'mobile'  => 'pencidesign_woo_fontsize_pages_nav_font_size_m',
	),
	'choices'  => array(
		'desktop' => array(
			'min'  => 1,
			'max'  => 100,
			'step' => 1,
			'edit' => true,
			'unit' => 'px',
		),
		'mobile'  => array(
			'min'  => 1,
			'max'  => 100,
			'step' => 1,
			'edit' => true,
			'unit' => 'px',
		),
	),
);
$options[] = array(
	'label'       => '',
	'description' => '',
	'id'          => 'pencidesign_woo_fontsize_pages_table_th_m',
	'type'        => 'soledad-fw-hidden',
	'sanitize'    => 'absint',
);
$options[] = array(
	'label'    => __( 'Font Size for Table Head', 'soledad' ),
	'id'       => 'pencidesign_woo_fontsize_pages_table_th',
	'type'     => 'soledad-fw-size',
	'sanitize' => 'absint',
	'ids'      => array(
		'desktop' => 'pencidesign_woo_fontsize_pages_table_th',
		'mobile'  => 'pencidesign_woo_fontsize_pages_table_th_m',
	),
	'choices'  => array(
		'desktop' => array(
			'min'  => 1,
			'max'  => 100,
			'step' => 1,
			'edit' => true,
			'unit' => 'px',
		),
		'mobile'  => array(
			'min'  => 1,
			'max'  => 100,
			'step' => 1,
			'edit' => true,
			'unit' => 'px',
		),
	),
);
$options[] = array(
	'label'       => '',
	'description' => '',
	'id'          => 'pencidesign_woo_fontsize_pages_table_product_title_m',
	'type'        => 'soledad-fw-hidden',
	'sanitize'    => 'absint',
);
$options[] = array(
	'label'    => __( 'Font Size for Table Product Title', 'soledad' ),
	'id'       => 'pencidesign_woo_fontsize_pages_table_product_title',
	'type'     => 'soledad-fw-size',
	'sanitize' => 'absint',
	'ids'      => array(
		'desktop' => 'pencidesign_woo_fontsize_pages_table_product_title',
		'mobile'  => 'pencidesign_woo_fontsize_pages_table_product_title_m',
	),
	'choices'  => array(
		'desktop' => array(
			'min'  => 1,
			'max'  => 100,
			'step' => 1,
			'edit' => true,
			'unit' => 'px',
		),
		'mobile'  => array(
			'min'  => 1,
			'max'  => 100,
			'step' => 1,
			'edit' => true,
			'unit' => 'px',
		),
	),
);
$options[] = array(
	'label'       => '',
	'description' => '',
	'id'          => 'pencidesign_woo_fontsize_pages_table_product_price_m',
	'type'        => 'soledad-fw-hidden',
	'sanitize'    => 'absint',
);
$options[] = array(
	'label'    => __( 'Font Size for Table Product Price', 'soledad' ),
	'id'       => 'pencidesign_woo_fontsize_pages_table_product_price',
	'type'     => 'soledad-fw-size',
	'sanitize' => 'absint',
	'ids'      => array(
		'desktop' => 'pencidesign_woo_fontsize_pages_table_product_price',
		'mobile'  => 'pencidesign_woo_fontsize_pages_table_product_price_m',
	),
	'choices'  => array(
		'desktop' => array(
			'min'  => 1,
			'max'  => 100,
			'step' => 1,
			'edit' => true,
			'unit' => 'px',
		),
		'mobile'  => array(
			'min'  => 1,
			'max'  => 100,
			'step' => 1,
			'edit' => true,
			'unit' => 'px',
		),
	),
);
$options[] = array(
	'label'       => '',
	'description' => '',
	'id'          => 'pencidesign_woo_fontsize_pages_table_product_subtotal_m',
	'type'        => 'soledad-fw-hidden',
	'sanitize'    => 'absint',
);
$options[] = array(
	'label'    => __( 'Font Size for Table Product Sub Total', 'soledad' ),
	'id'       => 'pencidesign_woo_fontsize_pages_table_product_subtotal',
	'type'     => 'soledad-fw-size',
	'sanitize' => 'absint',
	'ids'      => array(
		'desktop' => 'pencidesign_woo_fontsize_pages_table_product_subtotal',
		'mobile'  => 'pencidesign_woo_fontsize_pages_table_product_subtotal_m',
	),
	'choices'  => array(
		'desktop' => array(
			'min'  => 1,
			'max'  => 100,
			'step' => 1,
			'edit' => true,
			'unit' => 'px',
		),
		'mobile'  => array(
			'min'  => 1,
			'max'  => 100,
			'step' => 1,
			'edit' => true,
			'unit' => 'px',
		),
	),
);
$options[] = array(
	'label'       => '',
	'description' => '',
	'id'          => 'pencidesign_woo_fontsize_pages_table_product_quantity_m',
	'type'        => 'soledad-fw-hidden',
	'sanitize'    => 'absint',
);
$options[] = array(
	'label'    => __( 'Font Size for Table Product Quantity Input', 'soledad' ),
	'id'       => 'pencidesign_woo_fontsize_pages_table_product_quantity',
	'type'     => 'soledad-fw-size',
	'sanitize' => 'absint',
	'ids'      => array(
		'desktop' => 'pencidesign_woo_fontsize_pages_table_product_quantity',
		'mobile'  => 'pencidesign_woo_fontsize_pages_table_product_quantity_m',
	),
	'choices'  => array(
		'desktop' => array(
			'min'  => 1,
			'max'  => 100,
			'step' => 1,
			'edit' => true,
			'unit' => 'px',
		),
		'mobile'  => array(
			'min'  => 1,
			'max'  => 100,
			'step' => 1,
			'edit' => true,
			'unit' => 'px',
		),
	),
);
$options[] = array(
	'label'       => '',
	'description' => '',
	'id'          => 'pencidesign_woo_fontsize_pages_cart_total_h2_m',
	'type'        => 'soledad-fw-hidden',
	'sanitize'    => 'absint',
);
$options[] = array(
	'label'    => __( 'Font Size for Cart Total Heading', 'soledad' ),
	'id'       => 'pencidesign_woo_fontsize_pages_cart_total_h2',
	'type'     => 'soledad-fw-size',
	'sanitize' => 'absint',
	'ids'      => array(
		'desktop' => 'pencidesign_woo_fontsize_pages_cart_total_h2',
		'mobile'  => 'pencidesign_woo_fontsize_pages_cart_total_h2_m',
	),
	'choices'  => array(
		'desktop' => array(
			'min'  => 1,
			'max'  => 100,
			'step' => 1,
			'edit' => true,
			'unit' => 'px',
		),
		'mobile'  => array(
			'min'  => 1,
			'max'  => 100,
			'step' => 1,
			'edit' => true,
			'unit' => 'px',
		),
	),
);
$options[] = array(
	'label'       => '',
	'description' => '',
	'id'          => 'pencidesign_woo_fontsize_pages_button_m',
	'type'        => 'soledad-fw-hidden',
	'sanitize'    => 'absint',
);
$options[] = array(
	'label'    => __( 'Font Size for Button', 'soledad' ),
	'id'       => 'pencidesign_woo_fontsize_pages_button',
	'type'     => 'soledad-fw-size',
	'sanitize' => 'absint',
	'ids'      => array(
		'desktop' => 'pencidesign_woo_fontsize_pages_button',
		'mobile'  => 'pencidesign_woo_fontsize_pages_button_m',
	),
	'choices'  => array(
		'desktop' => array(
			'min'  => 1,
			'max'  => 100,
			'step' => 1,
			'edit' => true,
			'unit' => 'px',
		),
		'mobile'  => array(
			'min'  => 1,
			'max'  => 100,
			'step' => 1,
			'edit' => true,
			'unit' => 'px',
		),
	),
);
/* Checkout Form Font Size */
$options[] = array(
	'sanitize' => 'sanitize_text_field',
	'label'    => esc_html__( 'Check out Form Font Size', 'soledad' ),
	'id'       => 'penci_woo_section_checkout_font_size',
	'type'     => 'soledad-fw-header',
);
$options[] = array(
	'label'       => '',
	'description' => '',
	'id'          => 'pencidesign_woo_fontsize_checkout_form_label_m',
	'type'        => 'soledad-fw-hidden',
	'sanitize'    => 'absint',
);
$options[] = array(
	'label'    => __( 'Font Size for Form Label', 'soledad' ),
	'id'       => 'pencidesign_woo_fontsize_checkout_form_label',
	'type'     => 'soledad-fw-size',
	'sanitize' => 'absint',
	'ids'      => array(
		'desktop' => 'pencidesign_woo_fontsize_checkout_form_label_m',
		'mobile'  => 'pencidesign_woo_fontsize_checkout_form_label',
	),
	'choices'  => array(
		'desktop' => array(
			'min'  => 1,
			'max'  => 100,
			'step' => 1,
			'edit' => true,
			'unit' => 'px',
		),
		'mobile'  => array(
			'min'  => 1,
			'max'  => 100,
			'step' => 1,
			'edit' => true,
			'unit' => 'px',
		),
	),
);
$options[] = array(
	'label'       => '',
	'description' => '',
	'id'          => 'pencidesign_woo_fontsize_checkout_form_input_m',
	'type'        => 'soledad-fw-hidden',
	'sanitize'    => 'absint',
);
$options[] = array(
	'label'    => __( 'Font Size for Form Input', 'soledad' ),
	'id'       => 'pencidesign_woo_fontsize_checkout_form_input',
	'type'     => 'soledad-fw-size',
	'sanitize' => 'absint',
	'ids'      => array(
		'desktop' => 'pencidesign_woo_fontsize_checkout_form_input',
		'mobile'  => 'pencidesign_woo_fontsize_checkout_form_input_m',
	),
	'choices'  => array(
		'desktop' => array(
			'min'  => 1,
			'max'  => 100,
			'step' => 1,
			'edit' => true,
			'unit' => 'px',
		),
		'mobile'  => array(
			'min'  => 1,
			'max'  => 100,
			'step' => 1,
			'edit' => true,
			'unit' => 'px',
		),
	),
);
$options[] = array(
	'label'       => '',
	'description' => '',
	'id'          => 'pencidesign_woo_fontsize_checkout_order_heading_m',
	'type'        => 'soledad-fw-hidden',
	'sanitize'    => 'absint',
);
$options[] = array(
	'label'    => __( 'Font Size for Heading', 'soledad' ),
	'id'       => 'pencidesign_woo_fontsize_checkout_order_heading',
	'type'     => 'soledad-fw-size',
	'sanitize' => 'absint',
	'ids'      => array(
		'desktop' => 'pencidesign_woo_fontsize_checkout_order_heading',
		'mobile'  => 'pencidesign_woo_fontsize_checkout_order_heading_m',
	),
	'choices'  => array(
		'desktop' => array(
			'min'  => 1,
			'max'  => 100,
			'step' => 1,
			'edit' => true,
			'unit' => 'px',
		),
		'mobile'  => array(
			'min'  => 1,
			'max'  => 100,
			'step' => 1,
			'edit' => true,
			'unit' => 'px',
		),
	),
);
$options[] = array(
	'label'       => '',
	'description' => '',
	'id'          => 'pencidesign_woo_fontsize_checkout_order_button_m',
	'type'        => 'soledad-fw-hidden',
	'sanitize'    => 'absint',
);
$options[] = array(
	'label'    => __( 'Font Size for Order Button', 'soledad' ),
	'id'       => 'pencidesign_woo_fontsize_checkout_order_button',
	'type'     => 'soledad-fw-size',
	'sanitize' => 'absint',
	'ids'      => array(
		'desktop' => 'pencidesign_woo_fontsize_checkout_order_button',
		'mobile'  => 'pencidesign_woo_fontsize_checkout_order_button_m',
	),
	'choices'  => array(
		'desktop' => array(
			'min'  => 1,
			'max'  => 100,
			'step' => 1,
			'edit' => true,
			'unit' => 'px',
		),
		'mobile'  => array(
			'min'  => 1,
			'max'  => 100,
			'step' => 1,
			'edit' => true,
			'unit' => 'px',
		),
	),
);
/* Other Font Size */
$options[] = array(
	'sanitize' => 'sanitize_text_field',
	'label'    => esc_html__( 'Other Product Font Size', 'soledad' ),
	'id'       => 'penci_woo_section_product_other_typo',
	'type'     => 'soledad-fw-header',
);
$options[] = array(
	'label'       => '',
	'description' => '',
	'id'          => 'pencidesign_woo_fontsize_m_product_label',
	'type'        => 'soledad-fw-hidden',
	'sanitize'    => 'absint',
);
$options[] = array(
	'label'    => __( 'Font Size for Product Label', 'soledad' ),
	'id'       => 'pencidesign_woo_fontsize_product_label',
	'type'     => 'soledad-fw-size',
	'sanitize' => 'absint',
	'ids'      => array(
		'desktop' => 'pencidesign_woo_fontsize_product_label',
		'mobile'  => 'pencidesign_woo_fontsize_m_product_label',
	),
	'choices'  => array(
		'desktop' => array(
			'min'  => 1,
			'max'  => 100,
			'step' => 1,
			'edit' => true,
			'unit' => 'px',
		),
		'mobile'  => array(
			'min'  => 1,
			'max'  => 100,
			'step' => 1,
			'edit' => true,
			'unit' => 'px',
		),
	),
);

return $options;
