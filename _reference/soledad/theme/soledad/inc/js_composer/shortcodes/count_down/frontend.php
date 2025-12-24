<?php
$penci_block_width = $el_class = $css_animation = $css = '';

$count_down_style     = $count_down_posttion = $count_year = $count_month = $count_day = $count_hour = $count_minus = $count_sec = '';
$countdown_opts       = $digit_border = $digit_border_width = $digit_border_radius = $digit_padding = $unit_margin_top = '';
$countdown_item_width = $countdown_item_height = $cdtitle_upper = '';

$str_days         = $str_days2 = $str_weeks = $str_weeks2 = $str_months = $str_months2 = $str_years = $str_years2 = '';
$str_hours        = $str_hours2 = $str_minutes = $str_minutes2 = $str_seconds = $str_seconds2 = '';
$time_digit_color = $time_digit_bordercolor = $time_digit_bgcolor = $time_digit_size = $time_weight = $unit_color = $unit_size = '';
$time_bgcolor     = $time_bordercolor = '';
$time_digit_size  = $time_digit_typo = $unit_size = $unit_typo = $responsive_spacing = '';

$output = '';
$atts   = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );

$class_to_filter = vc_shortcode_custom_css_class( $css, ' ' ) . $this->getExtraClass( $el_class ) . $this->getCSSAnimation( $css_animation );

$css_class = 'penci-countdown-bsc';
$css_class .= ' penci-countdown-' . $count_down_style;
$css_class .= ' penci-style-' . $count_down_posttion;
$css_class .= 'yes' == $cdtitle_upper ? ' penci-period-upper' : '';
$css_class .= ' ' . apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $class_to_filter, $this->settings['base'], $atts );

$block_id = Penci_Vc_Helper::get_unique_id_block( 'count_down' );

// Data Until
$data_time = '';
$data_time .= $count_year ? $count_year : 0;
$data_time .= ',';
$data_time .= $count_month ? intval( $count_month ) - 1 : 0;
$data_time .= ',';
$data_time .= $count_day ? $count_day : 0;
$data_time .= ',';
$data_time .= $count_hour ? $count_hour : 0;
$data_time .= ',';
$data_time .= $count_minus ? $count_minus : 0;
$data_time .= ',';
$data_time .= $count_sec ? $count_sec : 0;

$labels  = sprintf( "['%s', '%s', '%s', '%s', '%s', '%s', '%s']", $str_years2, $str_weeks2, $str_months2, $str_days2, $str_hours2, $str_minutes2, $str_seconds2 );
$labels1 = sprintf( "['%s', '%s', '%s', '%s', '%s', '%s', '%s']", $str_years, $str_weeks, $str_months, $str_days, $str_hours, $str_minutes, $str_seconds );

// Data format YOWDHMS
$data_format = 'DHMS';
if ( $countdown_opts ) {
	$data_format = str_replace( ',', '', $countdown_opts );
}
?>
    <div id="<?php echo esc_attr( $block_id ) ?>" class="<?php echo esc_attr( $css_class ); ?>"></div>
    <script type="text/javascript">
        jQuery(function ($) {
            if ($.fn.countdown) {
                var <?php echo esc_attr( $block_id ); ?>newDateTime = new Date(<?php echo $data_time; ?> );

                $('#<?php echo esc_attr( $block_id ); ?>').countdown({
                    until: <?php echo esc_attr( $block_id ); ?>newDateTime,
                    labels: <?php echo $labels; ?>,
                    labels1: <?php echo $labels1; ?>,
                    timezone: <?php echo get_option( 'gmt_offset' ); ?>,
                    format: '<?php echo $data_format; ?>',
					<?php echo( is_rtl() ? 'isRTL: true' : '' ); ?>
                });
            }
        });
    </script>
<?php
$id_countdown = '#' . $block_id;
$id_countdown2 = 'body:not(.pcdm-enable) #' . $block_id;
$css_custom   = '';

if ( 's1' == $count_down_style ) {
	if ( $digit_border ) {
		$css_custom .= $id_countdown . '.penci-countdown-s1 .penci-countdown-amount { border-style: ' . ( $digit_border ) . ' }';

		if ( ! $digit_border_width ) {
			$css_custom .= $id_countdown . '.penci-countdown-s1 .penci-countdown-amount { border-width: 1px; }';
		}
	}

	if ( $digit_border_width ) {
		$css_custom .= $id_countdown . '.penci-countdown-s1 .penci-countdown-amount { border-width: ' . ( $digit_border_width ) . ' }';
	}
	if ( $digit_border_radius ) {
		$css_custom .= $id_countdown . '.penci-countdown-s1 .penci-countdown-amount { border-radius: ' . ( $digit_border_radius ) . ' }';
	}

	if ( $time_digit_bordercolor ) {
		$css_custom .= $id_countdown2 . '.penci-countdown-s1 .penci-countdown-amount { border-color: ' . ( $time_digit_bordercolor ) . ' }';
	}

	if ( $time_digit_bgcolor ) {
		$css_custom .= $id_countdown2 . '.penci-countdown-s1 .penci-countdown-amount { background-color: ' . ( $time_digit_bgcolor ) . ' }';
	}
} else {
	if ( $time_bgcolor ) {
		$css_custom .= $id_countdown2 . ' .penci-span-inner { background-color: ' . ( $time_bgcolor ) . ' }';
	}
	if ( $time_bordercolor ) {
		$css_custom .= $id_countdown2 . ' .penci-span-inner{ border-color: ' . ( $time_bordercolor ) . ' }';
	}
}

if ( $digit_padding ) {
	$css_custom .= penci_extract_md_responsive_fsize( $id_countdown . ' .penci-countdown-amount { padding: {{VALUE}}px }', $digit_padding );
}
if ( $unit_margin_top ) {
	$css_custom .= penci_extract_md_responsive_fsize( $id_countdown . ' .penci-countdown-period { margin-top: {{VALUE}}px }', $digit_padding );
}

if ( $countdown_item_width ) {
	$css_custom .= penci_extract_md_responsive_fsize( $id_countdown . ' .penci-countdown-section { width: {{VALUE}}px }', $countdown_item_width );
}
if ( $countdown_item_height ) {
	$css_custom .= penci_extract_md_responsive_fsize( $id_countdown . ' .penci-countdown-section{ height: {{VALUE}}px }', $countdown_item_height );
}


if ( $time_digit_color ) {
	$css_custom .= $id_countdown2 . ' .penci-countdown-amount { color:' . ( $time_digit_color ) . ' }';
}
$css_custom .= Penci_Vc_Helper::vc_google_fonts_parse_attributes( array(
	'font_size'  => $time_digit_size,
	'font_style' => $time_digit_typo,
	'template'   => $id_countdown . ' .penci-countdown-amount{ %s }',
) );


if ( $unit_color ) {
	$css_custom .= $id_countdown2 . ' .penci-countdown-period{ color: ' . ( $unit_color ) . ' }';
}
$css_custom .= Penci_Vc_Helper::vc_google_fonts_parse_attributes( array(
	'font_size'  => $unit_size,
	'font_style' => $unit_typo,
	'template'   => $id_countdown . ' .penci-countdown-period{ %s }',
) );

if ( $responsive_spacing ) {
	$css_custom .= penci_extract_spacing_style( $id_countdown, $responsive_spacing );
}

if ( $css_custom ) {
	echo '<style>';
	echo $css_custom;
	echo '</style>';
}
