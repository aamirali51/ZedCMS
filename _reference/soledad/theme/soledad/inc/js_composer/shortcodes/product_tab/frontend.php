<?php
if ( ! function_exists( 'wpb_getImageBySize' ) ) {
	return;
}
$output                = $class = $autoplay = $header_classes = $design = $responsive_spacing = '';
$item_vertical_spacing = $item_horizontal_spacing = $hide_hot_label = $hide_new_label = $hide_sale_label = '';
$atts                  = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );
$block_id          = Penci_Vc_Helper::get_unique_id_block( 'penci_product_tabs' );
$atts['elementor'] = false;
$id_product        = '#' . $block_id;

$img_id  = preg_replace( '/[^\d]/', '', $image );
$tabs_id = 'penci-tab-' . uniqid();

// Extract tab titles
preg_match_all( '/penci_product_tab([^\]]+)/i', $content, $matches, PREG_OFFSET_CAPTURE );
$tab_titles = array();

if ( isset( $matches[1] ) ) {
	$tab_titles = $matches[1];
}

$tabs_nav        = '';
$first_tab_title = '';
$tabs_nav        .= '<ul class="products-tabs-title">';
$_i              = 0;
foreach ( $tab_titles as $tab ) {
	$_i ++;
	$tab_atts                       = shortcode_parse_atts( $tab[0] );
	$tab_atts['carousel_js_inline'] = 'yes';
	$encoded_atts                   = wp_json_encode( array_intersect_key( array_merge( $atts, $tab_atts ), penci_custom_product_query_default_args() ) );
	$icon_output                    = '';

	if ( empty( $tab_atts['icon_size'] ) ) {
		$tab_atts['icon_size'] = '25x25';
	}

	// Tab icon
	if ( isset( $tab_atts['icon'] ) ) {
		$icon_output = penci_display_icon( $tab_atts['icon'], $tab_atts['icon_size'], 25 );
	}

	if ( $_i == 1 && isset( $tab_atts['title'] ) ) {
		$first_tab_title = $tab_atts['title'];
	}
	$class = ( $_i == 1 ) ? ' active-tab-title' : '';
	if ( isset( $tab_atts['title'] ) ) {
		$query_id = strtolower( sanitize_title_with_dashes( $tab_atts['title'] ) );
		$tabs_nav .= '<li data-queryid="' . esc_attr( $query_id ) . '" data-layout="' . esc_attr( $atts['layout'] ) . '" data-atts="' . esc_attr( $encoded_atts ) . '" class="' . esc_attr( $class ) . '">' . $icon_output . '<span class="tab-label">' . $tab_atts['title'] . '</span></li>';
	}
}

$tabs_nav .= '</ul>';

$class .= ' tabs-' . $tabs_id;

$class .= ' tabs-design-' . $design;

$class .= ' ' . $el_class;

$class .= 'penci-products-tabs';

$header_classes .= ' text-' . $alignment;

?>
    <div class="elementor-element elementor-widget-container penci-product-tabs-wrapper woocommerce">
        <div id="<?php echo esc_attr( $tabs_id ); ?>"
             class="penci-products-tabs<?php echo esc_attr( $class ); ?>">
            <div class="penci-tabs-header<?php echo esc_attr( $header_classes ); ?>">
				<?php if ( ! empty( $title ) ) : ?>
                    <h4 class="tabs-name title">
						<?php
						if ( $img_id ) {
							echo penci_display_icon( $img_id, $img_size, 30 );
						}
						?>
                        <span class="tabs-text"><?php echo wp_kses( $title, penci_allow_html() ); ?></span>
                    </h4>
				<?php endif; ?>
                <div class="tabs-navigation-wrapper">
                    <span class="open-title-menu"><?php echo wp_kses( $first_tab_title, penci_allow_html() ); ?></span>
					<?php
					echo ! empty( $tabs_nav ) ? $tabs_nav : '';
					?>
                </div>
            </div>
            <div class="penci-tab-content-container">
                <div class="penci-products-preloader">
                <span class="penci-loading-icon"><span class="bubble"></span><span class="bubble"></span><span
                            class="bubble"></span></span>
                </div>
				<?php
				if ( isset( $tab_titles[0][0] ) ) {
					$first_tab_atts          = array_intersect_key( array_merge( $atts, shortcode_parse_atts( $tab_titles[0][0] ) ), penci_custom_product_query_default_args() );
					$first_tab_atts['class'] = 'active';
					penci_elementor_products_template( $first_tab_atts );
				}
				?>
            </div>

			<?php
			if ( $color ) {
				$css = '.tabs-' . esc_attr( $tabs_id ) . '.tabs-design-simple .tabs-name {';
				$css .= 'border-color: ' . esc_attr( $color ) . ';';
				$css .= '}';

				$css .= '.tabs-' . esc_attr( $tabs_id ) . '.tabs-design-default .products-tabs-title .tab-label:after,';
				$css .= '.tabs-' . esc_attr( $tabs_id ) . '.tabs-design-alt .products-tabs-title .tab-label:after {';
				$css .= 'background-color: ' . esc_attr( $color ) . ';';
				$css .= '}';

				$css .= '.tabs-' . esc_attr( $tabs_id ) . '.tabs-design-simple .products-tabs-title li.active-tab-title,';
				$css .= '.tabs-' . esc_attr( $tabs_id ) . '.tabs-design-simple .penci-owl-nav > div:hover,';
				$css .= '.tabs-' . esc_attr( $tabs_id ) . '.tabs-design-simple .wrap-loading-arrow > div:not(.disabled):hover {';
				$css .= 'color: ' . esc_attr( $color ) . ';';
				$css .= '}';

				wp_add_inline_style( 'penci-woocommerce', $css );
			}
			?>
        </div>
    </div>

<?php
$css_custom = '';
if ( $item_horizontal_spacing ) {
	$css_custom .= penci_extract_md_responsive_fsize( $id_product . '  .product-layout-grid ul.products{margin-left:-{{VALUE}}px;margin-right:-{{VALUE}}px}', $item_horizontal_spacing );
	$css_custom .= penci_extract_md_responsive_fsize( $id_product . '  .product-layout-grid ul.products li.product{padding-left:-{{VALUE}}px;padding-right:-{{VALUE}}px}', $item_horizontal_spacing );
}

if ( $item_vertical_spacing ) {
	$css_custom .= penci_extract_md_responsive_fsize( $id_product . '  .product-layout-grid ul.products li.product{margin-bottom:-{{VALUE}}px}', $item_vertical_spacing );
	$css_custom .= penci_extract_md_responsive_fsize( $id_product . '  .products.product-list .penci-soledad-product .penci-product-loop-inner-content{margin-bottom:-{{VALUE}}px}', $item_vertical_spacing );
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
