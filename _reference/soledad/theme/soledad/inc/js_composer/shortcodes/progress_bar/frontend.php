<?php
$output = $penci_block_width = $el_class = $css_animation = $css = $responsive_spacing = '';

$bar_height    = $bar_mar_top = $bar_mar_bottom = '';
$bar_textcolor = $bar_run_bgcolor = $bar_bgcolor = '';

$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );

$values = (array) vc_param_group_parse_atts( $atts['values'] );

if ( empty( $values ) ) {
	return;
}

wp_enqueue_script( 'waypoints' );

$class_to_filter = vc_shortcode_custom_css_class( $css, ' ' ) . $this->getExtraClass( $el_class ) . $this->getCSSAnimation( $css_animation );

$css_class = 'penci-block-vc penci-progress-bar';
$css_class .= ' ' . apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $class_to_filter, $this->settings['base'], $atts );
$block_id  = Penci_Vc_Helper::get_unique_id_block( 'progress_bar' );

$bar_options = array();
$options     = explode( ',', $atts['options'] );
if ( in_array( 'animated', $options ) ) {
	$bar_options[] = 'animated penci-probar-animated';
}
if ( in_array( 'striped', $options ) ) {
	$bar_options[] = 'penci-probar-striped';
}
?>
    <div id="<?php echo esc_attr( $block_id ); ?>" class="<?php echo esc_attr( $css_class ); ?>">
        <div class="penci-block_content">
            <ul class="penci-probar-items">
				<?php
				$line_output = '';

				$max_value        = 0.0;
				$graph_lines_data = array();
				foreach ( $values as $data ) {
					$new_line             = $data;
					$new_line['value']    = isset( $data['value'] ) ? $data['value'] : 0;
					$new_line['label']    = isset( $data['label'] ) ? $data['label'] : '';
					$new_line['bgcolor']  = isset( $data['bgcolor'] ) ? ' style="background-color: ' . esc_attr( $data['bgcolor'] ) . ';"' : '';
					$new_line['txtcolor'] = isset( $data['textcolor'] ) ? ' style="color: ' . esc_attr( $data['textcolor'] ) . ';"' : '';

					if ( $max_value < (float) $new_line['value'] ) {
						$max_value = $new_line['value'];
					}
					$graph_lines_data[] = $new_line;
				}

				foreach ( $graph_lines_data as $line ) {

					if ( $max_value > 100.00 ) {
						$percentage_value = (float) $line['value'] > 0 && $max_value > 100.00 ? round( (float) $line['value'] / $max_value * 100, 4 ) : 0;
					} else {
						$percentage_value = $line['value'];
					}
					$percentage_value = number_format( intval( $percentage_value / 10 ), 1, '.', '' );

					$line_output .= '<li class="penci-probar-item">';
					$line_output .= '<div class="penci-probar-text"' . $line['txtcolor'] . '>';
					$line_output .= '<span class="penci-probar-point">' . do_shortcode( $line['label'] ) . '</span>';
					$line_output .= '<span class="penci-probar-score">' . $line['value'] . ( isset( $atts['units'] ) ? $atts['units'] : '' ) . '</span>';
					$line_output .= '</div>';
					$line_output .= '<div class="penci-review-process">';
					$line_output .= '<span class="penci-probar-run ' . esc_attr( implode( ' ', $bar_options ) ) . '" data-width="' . $percentage_value . '"' . $line['bgcolor'] . '></span>';
					$line_output .= '</div>';
					$line_output .= '</li>';
				}

				echo $line_output;
				?>
            </ul>
        </div>
    </div>
<?php

$id_progress_bar = '#' . $block_id;
$id_progress_bar2 = '#' . $block_id;
$css_custom      = Penci_Vc_Helper::get_heading_block_css( $id_progress_bar, $atts );

if ( $bar_height ) {
	$css_custom .= penci_extract_md_responsive_fsize( $id_progress_bar . ' .penci-review-process{ height: {{VALUE}}px; }', $bar_height );
}
if ( $bar_mar_top ) {
	$css_custom .= penci_extract_md_responsive_fsize( $id_progress_bar . ' .penci-probar-item{ margin-top: {{VALUE}}px; }', $bar_mar_top );
}
if ( $bar_mar_bottom ) {
	$css_custom .= penci_extract_md_responsive_fsize( $id_progress_bar . ' .penci-probar-item{ margin-bottom:{{VALUE}}px; }', $bar_mar_bottom );
}

// color
if ( $bar_run_bgcolor ) {
	$css_custom .= $id_progress_bar2 . ' .penci-probar-run{ background-color: ' . esc_attr( $bar_run_bgcolor ) . '; }';
}
if ( $bar_bgcolor ) {
	$css_custom .= $id_progress_bar2 . ' .penci-review-process{ background-color: ' . esc_attr( $bar_bgcolor ) . '; }';
}
if ( $bar_textcolor ) {
	$css_custom .= $id_progress_bar2 . ' .penci-probar-text{ color: ' . esc_attr( $bar_textcolor ) . '; }';
}
if ( $responsive_spacing ) {
	$css_custom .= penci_extract_spacing_style( $id_progress_bar, $responsive_spacing );
}

if ( $css_custom ) {
	echo '<style>';
	echo $css_custom;
	echo '</style>';
}
