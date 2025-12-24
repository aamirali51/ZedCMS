<?php
$output = $penci_block_width = $el_class = $css_animation = $css = '';

$text_right = $alignment = $dis_circle = $dis_border_radius = $brand_color = $responsive_spacing = '';
$size_icon = $size_upper = $size_text = $show_socials = '';

$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );

if( ! $show_socials ) {
	return;
}

$class_to_filter = vc_shortcode_custom_css_class( $css, ' ' ) . $this->getExtraClass( $el_class ) . $this->getCSSAnimation( $css_animation );

$css_class = 'penci-block-vc penci-social-media';
$css_class .= ' ' . apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $class_to_filter, $this->settings['base'], $atts );
$block_id = Penci_Vc_Helper::get_unique_id_block( 'social_media' );

$class_socials = ' widget-social';
$class_socials .= $alignment ? ' ' . $alignment : '';
$class_socials .= $text_right ? ' show-text' : '';
$class_socials .= 'yes' == $dis_circle ? ' remove-circle' : '';
$class_socials .= 'yes' == $size_upper ? ' remove-uppercase-text' : '';
$class_socials .= 'yes' == $dis_border_radius ? ' remove-border-radius' : '';

if ( 'yes' == $brand_color && ! 'yes' == $dis_circle ) {
	$class_socials .= ' penci-social-colored';
} elseif ( 'yes' == $brand_color && 'yes' == $dis_circle ) {
	$class_socials .= ' penci-social-textcolored';
}

$style_icon     = 'style="font-size: ' . $size_icon . '"';
$style_icon_svg = 'style="width: ' . $size_icon . 'px;height: auto;"';
$style_text     = 'style="font-size: ' . $size_text . '"';

?>
	<div id="<?php echo esc_attr( $block_id ); ?>" class="<?php echo esc_attr( $css_class ); ?>">
		<?php Penci_Vc_Helper::markup_block_title( $atts ); ?>
		<div class="penci-block_content<?php echo esc_attr( $class_socials ); ?>">
			<?php 
			$show_socials_array = explode(',', $show_socials );
			$social_data = penci_social_media_array();
			foreach( $social_data as $name => $sdata ){
				if( in_array( $name, $show_socials_array ) ){
					$icon_html = penci_icon_by_ver( $sdata[1], $style_icon );
					?>
					<a href="<?php echo esc_url( do_shortcode( $sdata[0] ) ); ?>" aria-label="<?php echo ucwords( $name ); ?>" <?php echo penci_reltag_social_icons(); ?> target="_blank"><?php echo $icon_html; ?><span <?php echo $style_text; ?>><?php echo ucwords( $name ); ?></span></a>
					<?php
				}
			}
			?>
		</div>
	</div>
<?php

$id_social_media = '#' . $block_id;
$id_social_media2 = 'body:not(.pcdm-enable) #' . $block_id;
$css_custom = Penci_Vc_Helper::get_heading_block_css( $id_social_media, $atts );

if ( $atts['size_icon'] ) {
	$css_custom .= $id_social_media . ' .widget-social i{ font-size:' . esc_attr( $atts['size_icon'] ) . ' !important; }';
}
if ( $atts['size_icon'] ) {
	$css_custom .= $id_social_media . ' .widget-social svg{ width:' . esc_attr( $atts['size_icon'] ) . ' !important; height: auto; }';
}

// Color
if ( $atts['social_text_color'] ) {
	$css_custom .= $id_social_media2 . ' .widget-social a i{ color: ' . esc_attr( $atts['social_text_color'] ) . '; }';
}
if ( $atts['social_text_hcolor'] ) {
	$css_custom .= $id_social_media2 . ' .widget-social a:hover i{ color: ' . esc_attr( $atts['social_text_hcolor'] ) . '; }';
}
if ( $atts['social_bodcolor'] ) {
	$css_custom .= $id_social_media2 . ' .widget-social a i{ border-color: ' . esc_attr( $atts['social_bodcolor'] ) . '; }';
}
if ( $atts['social_hbodcolor'] ) {
	$css_custom .= $id_social_media2 . ' .widget-social a:hover i{ border-color: ' . esc_attr( $atts['social_hbodcolor'] ) . '; }';
}
if ( $atts['social_bgcolor'] ) {
	$css_custom .= $id_social_media2 . ' .widget-social a i{ background-color: ' . esc_attr( $atts['social_bgcolor'] ) . '; }';
}
if ( $atts['social_bghcolor'] ) {
	$css_custom .= $id_social_media2 . ' .widget-social a:hover i{ background-color: ' . esc_attr( $atts['social_bghcolor'] ) . '; }';
}
if ( $atts['social_textcolor'] ) {
	$css_custom .= $id_social_media2 . ' .widget-social.show-text a span{ color: ' . esc_attr( $atts['social_textcolor'] ) . '; }';
}
if ( $atts['social_htextcolor'] ) {
	$css_custom .= $id_social_media2 . ' .widget-social.show-text a:hover span{ color: ' . esc_attr( $atts['social_htextcolor'] ) . '; }';
}

if ( $responsive_spacing ) {
	$css_custom .= penci_extract_spacing_style( $id_social_media, $responsive_spacing );
}

if ( $css_custom ) {
	echo '<style>';
	echo $css_custom;
	echo '</style>';
}
