<?php
$settings         = vc_map_get_attributes( $this->getShortcode(), $atts );
$default_settings = [
	'number'               => 20,
	'hover'                => 'default',
	'target'               => '_self',
	'link'                 => '',
	'ids'                  => '',
	'style'                => 'carousel',
	'brand_style'          => 'default',
	'slides_per_view'      => 3,
	'columns'              => 3,
	'orderby'              => '',
	'hide_empty'           => 0,
	'order'                => 'ASC',
	'scroll_carousel_init' => 'no',
	'custom_sizes'         => apply_filters( 'penci_brands_shortcode_custom_sizes', false ),
];

$atts['hide_empty']              = 'yes' == $atts['hide_empty'];
$atts['hide_pagination_control'] = 'yes' == $atts['hide_pagination_control'];
$atts['hide_prev_next_buttons']  = 'yes' == $atts['hide_prev_next_buttons'];
$atts['wrap']                    = 'yes' == $atts['wrap'];
$atts['autoplay']                = 'yes' == $atts['autoplay'];

$settings = wp_parse_args( $atts, $settings );

$carousel_id = 'brands_' . rand( 1000, 9999 );

$attribute = get_theme_mod( 'penci_woocommerce_brand_attr' );

if ( empty( $attribute ) || ! taxonomy_exists( $attribute ) ) {
	echo '<div class="penci-notice penci-info">' . esc_html__( 'You must select your brand attribute in Appearance -> Customize -> WooCommerce > Brands', 'soledad' ) . '</div>';

	return;
}

$settings['columns']         = isset( $settings['columns'] ) ? $settings['columns'] : 3;
$settings['slides_per_view'] = isset( $settings['slides_per_view'] ) ? $settings['slides_per_view'] : 3;

$owl_attributes = '';

$item_class          = [];
$wrapper_class       = [];
$wrapper_id          = $carousel_id;
$items_wrapper_class = [];
$items_wrapper_data  = [];
$wrapper_class[]     = 'brands-items-wrapper';
$wrapper_class[]     = 'brands-widget';
$wrapper_class[]     = 'slider-' . $carousel_id;
$wrapper_class[]     = 'brands-hover-' . $settings['hover'];
$wrapper_class[]     = 'brands-style-' . $settings['brand_style'];

if ( $settings['style'] ) {
	$element_class[] = 'brands-' . $settings['style'];
}

if ( 'carousel' === $settings['style'] ) {
	$settings['scroll_per_page'] = 'yes';
	$settings['carousel_id']     = $carousel_id;
	$owl_attributes              = '';

	$wrapper_class[]       = 'penci-carousel-container';
	$items_wrapper_class[] = 'penci-owl-carousel penci-owl-carousel-slider swiper';

	$item_class[]          = 'swiper-slide';


	$items_wrapper_data[] = 'data-item="' . ( isset( $settings['slides_per_view'] ) ? $settings['slides_per_view'] : 3 ) . '"';
	$items_wrapper_data[] = 'data-dots"' . $settings['hide_pagination_control'] . '"';
	$items_wrapper_data[] = 'data-nav"' . $settings['hide_prev_next_buttons'] . '"';
	$items_wrapper_data[] = 'data-loop"' . $settings['wrap'] . '"';
	$items_wrapper_data[] = 'data-auto"' . $settings['autoplay'] . '"';
	$items_wrapper_data[] = 'data-speed"' . $settings['speed'] . '"';
	$items_wrapper_data[] = 'data-lazy"' . $settings['scroll_carousel_init'] . '"';

	if ( 'yes' === $settings['scroll_carousel_init'] ) {
		$wrapper_class[] = 'scroll-init';
	}

	if ( get_theme_mod( 'penci_disable_owl_mobile_devices' ) ) {
		$wrapper_class[] = 'disable-owl-mobile';

	}
} else {
	$item_class[]          = 'column-item';
	$items_wrapper_class[] = 'penci-custom-row row-' . $settings['columns'];
}

$args = array(
	'taxonomy'   => $attribute,
	'hide_empty' => $settings['hide_empty'],
	'order'      => $settings['order'],
	'number'     => $settings['number'],
);

if ( $settings['orderby'] ) {
	$args['orderby'] = $settings['orderby'];
}

if ( 'random' === $settings['orderby'] ) {
	$args['orderby'] = 'id';
	$brand_count     = wp_count_terms( $attribute, array(
		'hide_empty' => $settings['hide_empty'],
	) );

	$offset = rand( 0, $brand_count - (int) $settings['number'] );
	if ( $offset <= 0 ) {
		$offset = '';
	}
	$args['offset'] = $offset;
}

if ( $settings['ids'] ) {
	$args['include'] = $settings['ids'];
}

$brands   = get_terms( $args );
$taxonomy = get_taxonomy( $attribute );

if ( 'random' === $settings['orderby'] ) {
	shuffle( $brands );
}

if ( penci_is_shop_on_front() ) {
	$link = home_url();
} else {
	$link = get_post_type_archive_link( 'product' );
}

?>
    <div <?php echo implode( ' ', $wrapper_class ); ?>>
		<?php Penci_Vc_Helper::markup_block_title( $settings ); ?>
        <div <?php echo implode( ' ', $items_wrapper_class ); ?> <?php echo implode( ' ', $items_wrapper_data ); ?>>
        	<div class="swiper-wrapper">
			<?php if ( ! is_wp_error( $brands ) && count( $brands ) > 0 ) : ?>
				<?php foreach ( $brands as $key => $brand ) : ?>
					<?php
					$image       = get_term_meta( $brand->term_id, 'image', true );
					$filter_name = 'filter_' . sanitize_title( str_replace( 'pa_', '', $attribute ) );

					if ( is_object( $taxonomy ) && $taxonomy->public ) {
						$attr_link = get_term_link( $brand->term_id, $brand->taxonomy );
					} else {
						$attr_link = add_query_arg( $filter_name, $brand->slug, $link );
					}

					?>

                    <div <?php echo implode( ' ', $item_class ); ?>>
                        <a title="Brand <?php echo esc_html( $brand->name ); ?>"
                           href="<?php echo esc_url( $attr_link ); ?>">
							<?php if ( 'list' === $settings['style'] || ! $image ) : ?>
                                <span class="brand-title-wrap">
										<?php echo esc_html( $brand->name ); ?>
									</span>
							<?php else : ?>
								<?php echo '<img src="' . wp_get_attachment_image_url( $image, 'full' ) . '" alt="' . $brand->name . '" title="' . $brand->name . '">'; ?>
							<?php endif; ?>
                        </a>
                    </div>
				<?php endforeach; ?>
			<?php endif; ?>
			</div>
        </div>
    </div>
<?php
