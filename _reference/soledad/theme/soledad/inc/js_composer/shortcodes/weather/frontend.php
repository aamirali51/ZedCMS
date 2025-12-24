<?php
$output = $penci_block_width = $el_class = $css_animation = $css = $responsive_spacing = '';

$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );

$class_to_filter = vc_shortcode_custom_css_class( $css, ' ' ) . $this->getExtraClass( $el_class ) . $this->getCSSAnimation( $css_animation );

$css_class = 'penci-block-vc penci_block_weather penci-weather';
$css_class .= ' ' . apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $class_to_filter, $this->settings['base'], $atts );
$block_id  = Penci_Vc_Helper::get_unique_id_block( 'weather' );
wp_enqueue_style( 'penci-font-iweather' );
?>
    <div id="<?php echo esc_attr( $block_id ); ?>" class="<?php echo esc_attr( $css_class ); ?>">
		<?php Penci_Vc_Helper::markup_block_title( $atts ); ?>
        <div class="penci-block_content">
			<?php
			$weather_data = Penci_Weather::show_forecats( array(
				'location'      => $atts['penci_location'],
				'location_show' => $atts['penci_location_show'],
				'forecast_days' => $atts['penci_forcast'],
				'units'         => $atts['penci_units'],
				'user_loc'		=> $atts['penci_user_loc'] == 'enable',
				'cookie'		=> $atts['penci_save_user_loc'] == 'enable',
			) );

			if ( $weather_data ) {
				echo $weather_data;
			} else {
				echo '<div class="penci-block-error">';
				echo '<span>Weather widget</span>';
				echo ' You need to fill API key to Customize > General > Extra Options > Weather API Key to get this widget work.';
				echo '</div>';
			}
			?>
        </div>
    </div>
<?php

$id_weather = 'body:not(.pcdm-enable) #' . $block_id;
$css_custom = Penci_Vc_Helper::get_heading_block_css( $id_weather, $atts );

if ( $atts['w_genneral_color'] ) {
	$css_custom .= sprintf( '%s .penci-weather-condition,
					 %s .penci-weather-information,
					 %s .penci-weather-lo-hi__content .fa,
					 %s .penci-circle,
					 %s .penci-weather-animated-icon i,
					 %s .penci-weather-unit { color : %s; opacity: 1; }', $id_weather, $id_weather, $id_weather, $id_weather, $id_weather, $id_weather, $atts['w_genneral_color'] );
}

if ( $atts['w_localtion_color'] ) {
	$css_custom .= sprintf( '%s .penci-weather-city { color : %s; }', $id_weather, $atts['w_localtion_color'] );
}

if ( $atts['w_border_color'] ) {
	$css_custom .= sprintf( '%s .penci-weather-information { border-color : %s; }', $id_weather, $atts['w_border_color'] );
}

if ( $atts['w_degrees_color'] ) {
	$css_custom .= sprintf( '%s .penci-big-degrees,%s .penci-small-degrees { color : %s; }', $id_weather, $id_weather, $atts['w_degrees_color'] );
}

if ( $atts['w_forecast_text_color'] ) {
	$css_custom .= sprintf( '%s .penci-weather-week{ color : %s; }', $id_weather, $atts['w_forecast_text_color'] );
}

if ( $atts['w_forecast_bg_color'] ) {
	$css_custom .= sprintf( '%s .penci-weather-week:before { background-color : %s;opacity: 1; }', $id_weather, $atts['w_forecast_bg_color'] );
}

if ( $atts['w_location_size'] ) {
	$css_custom .= sprintf( '%s .penci-weather-city{ font-size: %s; }', $id_weather, $atts['w_location_size'] );
}
if ( $atts['w_condition_size'] ) {
	$css_custom .= sprintf( '%s .penci-weather-condition{ font-size: %s; }', $id_weather, $atts['w_condition_size'] );
}
if ( $atts['w_whc_info_size'] ) {
	$css_custom .= sprintf( '%s .penci-weather-information{ font-size: %s; }', $id_weather, $atts['w_whc_info_size'] );
}
if ( $atts['w_temp_size'] ) {
	$css_custom .= sprintf( '%s .penci-weather-now .penci-big-degrees{ font-size: %s; }', $id_weather, $atts['w_temp_size'] );
}
if ( $atts['w_tempsmall_size'] ) {
	$css_custom .= sprintf( '%s .penci-weather-degrees-wrap .penci-small-degrees{ font-size: %s; }', $id_weather, $atts['w_tempsmall_size'] );
}
if ( $atts['w_forecast_size'] ) {
	$css_custom .= sprintf( '%s .penci-weather-days .penci-day-degrees { font-size: %s; }', $id_weather, $atts['w_forecast_size'] );
}

if ( $responsive_spacing ) {
	$css_custom .= penci_extract_spacing_style( $id_weather, $responsive_spacing );
}

if ( $css_custom ) {
	echo '<style>';
	echo $css_custom;
	echo '</style>';
}
