<?php
$output                 = $el_class = $css_animation = $css = $responsive_spacing = '';
$heading_title_style    = $heading = $heading_title_link = $heading_title_align = '';
$post_type              = $include = $exclude = $taxonomies = $orderby = $offset = $query_type = $order = $meta_key = $layout = $columns = $items_per_page = $pagination = $shop_tools = $scroll_per_page = '';
$hide_prev_next_buttons = $center_mode = $wrap = $autoplay = $speed = $product_style = '';
$img_size               = $img_custom_size = $sale_countdown = $stock_progress_bar = $highlighted_products = $product_quantity = '';
$item_vertical_spacing  = $item_horizontal_spacing = $hide_hot_label = $hide_new_label = $hide_sale_label = '';

$product_title_color = $product_title_font_size = $product_category_color = $product_category_font_size = $product_price_color = $product_price_font_size = $product_pagination_color = $product_pagination_boder_color = $product_pagination_hover_color = $product_pagination_border_hover_color = $product_pagination_current_item_color = $product_pagination_current_item_border_color = $product_pagination_current_bg_color = $product_pagination_viewmore_bg_color = $product_pagination_viewmore_hover_bg_color = $product_pagination_viewmore_text_color = $product_pagination_viewmore_hover_text_color = '';

$atts              = vc_map_get_attributes( $this->getShortcode(), $atts );
$atts['elementor'] = false;
extract( $atts );

$atts['hide_prev_next_buttons']  = 'yes' == $atts['hide_prev_next_buttons'];
$atts['hide_pagination_control'] = 'yes' == $atts['hide_pagination_control'];
$atts['wrap']                    = 'yes' == $atts['wrap'];
$atts['autoplay']                = 'yes' == $atts['autoplay'];
$atts['scroll_carousel_init']    = 'yes' == $atts['scroll_carousel_init'];
$atts['sale_countdown']          = 'yes' == $atts['sale_countdown'];
$atts['stock_progress_bar']      = 'yes' == $atts['stock_progress_bar'];
$atts['product_categories']      = 'yes' == $atts['product_categories'];
$atts['product_rating']          = 'yes' == $atts['product_rating'];

$css_class = 'penci-vc_product elementor-widget-container';

$class_to_filter = vc_shortcode_custom_css_class( $css, ' ' ) . $this->getExtraClass( $el_class ) . $this->getCSSAnimation( $css_animation );
$css_class       .= ' ' . apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $class_to_filter, $this->settings['base'], $atts );
$block_id        = Penci_Vc_Helper::get_unique_id_block( 'penci_product' );
?>
    <div id="<?php echo esc_attr( $block_id ); ?>" class="<?php echo esc_attr( $css_class ); ?>">
		<?php
		Penci_Vc_Helper::markup_block_title( $atts );
		penci_elementor_products_template( $atts, true );
		?>
    </div>

<?php
// CUSTOM CSS
$id_product = '#' . $block_id;
$id_product2 = 'body:not(.pcdm-enable) #' . $block_id;
$css_custom = Penci_Vc_Helper::get_heading_block_css( $id_product, $atts );

if ( $product_title_color ) {
	$css_custom .= $id_product2 . ' ul.products .penci-soledad-product .penci-product-loop-title h3{color:' . $product_title_color . '}';
	$css_custom .= $id_product2 . ' ul.products .penci-soledad-product .penci-product-loop-title h3 a{color:' . $product_title_color . '}';
}

if ( $product_title_font_size ) {
	$css_custom .= penci_extract_md_responsive_fsize( $id_product . ' ul.products .penci-soledad-product .penci-product-loop-title h3{font-size:{{VALUE}}px}', $product_title_font_size );
}

if ( $product_category_color ) {
	$css_custom .= $id_product2 . ' ul.products li.product .penci-product-cats a{color:' . $product_category_color . '}';
}

if ( $product_category_font_size ) {
	$css_custom .= penci_extract_md_responsive_fsize( $id_product . ' ul.products li.product .penci-product-cats a{font-size:{{VALUE}}px}', $product_category_font_size );
}

if ( $product_price_color ) {
	$css_custom .= $id_product2 . ' ul.products li.product .price{color:' . $product_price_color . '}';
}

if ( $product_price_font_size ) {
	$css_custom .= penci_extract_md_responsive_fsize( $id_product . ' ul.products li.product .price{font-size:{{VALUE}}px}', $product_price_font_size );
}

if ( $product_pagination_color ) {
	$css_custom .= $id_product2 . ' nav.woocommerce-pagination ul li a{color:' . $product_pagination_color . '}';
}

if ( $product_pagination_boder_color ) {
	$css_custom .= $id_product2 . ' nav.woocommerce-pagination ul li a{border-color:' . $product_pagination_boder_color . '}';
}

if ( $product_pagination_hover_color ) {
	$css_custom .= $id_product2 . ' nav.woocommerce-pagination ul li a:hover{color:' . $product_pagination_hover_color . '}';
}

if ( $product_pagination_border_hover_color ) {
	$css_custom .= $id_product2 . ' nav.woocommerce-pagination ul li a:hover{color:' . $product_pagination_border_hover_color . '}';
}

if ( $product_pagination_current_item_color ) {
	$css_custom .= $id_product2 . ' nav.woocommerce-pagination ul li span.current{color:' . $product_pagination_border_hover_color . '}';
}

if ( $product_pagination_current_item_border_color ) {
	$css_custom .= $id_product2 . ' nav.woocommerce-pagination ul li span.current{border-color:' . $product_pagination_border_hover_color . '}';
}

if ( $product_pagination_current_bg_color ) {
	$css_custom .= $id_product . ' nav.woocommerce-pagination ul li span.current{background-color:' . $product_pagination_current_bg_color . '}';
}

if ( $product_pagination_viewmore_bg_color ) {
	$css_custom .= $id_product2 . '  .penci-woo-page-container .page-load-button .button{background-color:' . $product_pagination_viewmore_bg_color . '}';
}

if ( $product_pagination_viewmore_text_color ) {
	$css_custom .= $id_product2 . '  .penci-woo-page-container .page-load-button .button{color:' . $product_pagination_viewmore_text_color . '}';
}

if ( $product_pagination_viewmore_hover_bg_color ) {
	$css_custom .= $id_product2 . '  .penci-woo-page-container .page-load-button .button:hover{background-color:' . $product_pagination_viewmore_hover_bg_color . '}';
}

if ( $product_pagination_viewmore_hover_text_color ) {
	$css_custom .= $id_product2 . '  .penci-woo-page-container .page-load-button .button:hover{color:' . $product_pagination_viewmore_hover_text_color . '}';
}

if ( $item_horizontal_spacing ) {
	$css_custom .= penci_extract_md_responsive_fsize( $id_product . '  .product-layout-grid ul.products{margin-left:-{{VALUE}}px;margin-right:-{{VALUE}}px}', $item_horizontal_spacing );
	$css_custom .= penci_extract_md_responsive_fsize( $id_product . '  .product-layout-grid ul.products li.product{padding-left:-{{VALUE}}px;padding-right:-{{VALUE}}px}', $item_horizontal_spacing );
}

if ( $item_vertical_spacing ) {
	$css_custom .= penci_extract_md_responsive_fsize( $id_product . '  .product-layout-grid ul.products li.product{margin-bottom:-{{VALUE}}}', $item_vertical_spacing );
	$css_custom .= penci_extract_md_responsive_fsize( $id_product . '  .products.product-list .penci-soledad-product .penci-product-loop-inner-content{margin-bottom:-{{VALUE}}}', $item_vertical_spacing );
	$css_custom .= penci_extract_md_responsive_fsize( $id_product . '  .penci-woo-page-container.next_previous .woocommerce-pagination .page-numbers li a.prev.page-numbers,.penci-woo-page-container.next_previous .woocommerce-pagination .page-numbers li a.next.page-numbers{margin-top: calc( -25px -{{VALUE}}px)}', $item_vertical_spacing );
}

if ( $hide_hot_label ) {
	$css_custom .= $id_product . ' .penci-soledad-product .product-labels .product-label.featured{display:none}';
}

if ( $hide_new_label ) {
	$css_custom .= $id_product . ' .penci-soledad-product .product-labels .product-label.new{display:none}';
}

if ( $hide_sale_label ) {
	$css_custom .= $id_product . ' .penci-soledad-product .product-labels .product-label.onsale{display:none}';
}

if ( $responsive_spacing ) {
	$css_custom .= penci_extract_spacing_style( $id_product, $responsive_spacing );
}


if ( $css_custom ) {
	echo '<style>';
	echo $css_custom;
	echo '</style>';
}
