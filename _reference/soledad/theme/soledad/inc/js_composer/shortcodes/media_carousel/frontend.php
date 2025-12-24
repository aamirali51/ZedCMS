<?php
$output = $el_class = $css_animation = $css = '';
$atts   = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );

$class_to_filter = vc_shortcode_custom_css_class( $css, ' ' ) . $this->getExtraClass( $el_class ) . $this->getCSSAnimation( $css_animation );

$css_class = 'penci-block-vc penci-media-carousels';
$css_class .= ' ' . apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $class_to_filter, $this->settings['base'], $atts );
$block_id  = Penci_Vc_Helper::get_unique_id_block( 'media-carousels' );


$data_slider = 'yes' == $atts['showdots'] ? ' data-dots="true"' : '';
$data_slider .= ! 'yes' == $atts['shownav'] ? ' data-nav="true"' : '';
$data_slider .= ! 'yes' == $atts['loop'] ? ' data-loop="true"' : '';
$data_slider .= ' data-auto="' . ( 'yes' == $atts['autoplay'] ? 'true' : 'false' ) . '"';
$data_slider .= $atts['auto_time'] ? ' data-autotime="' . $atts['auto_time'] . '"' : '';
$data_slider .= $atts['speed'] ? ' data-speed="' . $atts['speed'] . '"' : '';

$data_slider .= ' data-margin="' . ( isset( $atts['slides_item_gap'] ) && $atts['slides_item_gap'] ? $atts['slides_item_gap'] : '10' ) . '"';
$data_slider .= ' data-item="' . ( isset( $atts['limit_desk'] ) && $atts['limit_desk'] ? $atts['limit_desk'] : '3' ) . '"';
$data_slider .= ' data-desktop="' . ( isset( $atts['limit_desk'] ) && $atts['limit_desk'] ? $atts['limit_desk'] : '3' ) . '" ';
$data_slider .= ' data-tablet="' . ( isset( $atts['limit_tab'] ) && $atts['limit_tab'] ? $atts['limit_tab'] : '2' ) . '"';
$data_slider .= ' data-tabsmall="' . ( isset( $atts['limit_tab'] ) && $atts['limit_tab'] ? $atts['limit_tab'] : '2' ) . '"';
$data_slider .= ' data-mobile="' . ( isset( $atts['limit_mobile'] ) && $atts['limit_mobile'] ? $atts['limit_mobile'] : '1' ) . '"';
?>
<div id="<?php echo esc_attr( $block_id ); ?>" class="<?php echo esc_attr( $css_class ); ?>">
	<div class="penci-block_content swiper penci-owl-carousel penci-owl-carousel-slider swiper" <?php echo( $data_slider ); ?>>
		<div class="swiper-wrapper">
		<?php echo do_shortcode( $content ); ?>
		</div>
	</div>
</div>
