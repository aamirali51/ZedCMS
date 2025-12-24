<?php
$output = $penci_block_width = $el_class = $css_animation = $css = $responsive_spacing = '';

$working_hours         = $row_gap = $subtitle_martop = '';
$ophour_icon_color     = $ophour_icon_size = '';
$ophour_title_color    = $ophour_title_size = $ophour_title_typo = '';
$ophour_subtitle_color = $ophour_subtitle_size = $ophour_subtitle_typo = '';
$ophour_hour_color     = $ophour_hour_size = $ophour_hour_weight = $ophour_hour_typo = '';

$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );

$working_hours = (array) vc_param_group_parse_atts( $working_hours );

if ( ! $working_hours ) {
	return;
}

$class_to_filter = vc_shortcode_custom_css_class( $css, ' ' ) . $this->getExtraClass( $el_class ) . $this->getCSSAnimation( $css_animation );

$css_class = 'penci-block-vc penci-working-hours';
$css_class .= ' ' . apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $class_to_filter, $this->settings['base'], $atts );
$block_id  = Penci_Vc_Helper::get_unique_id_block( 'open_hours' );
?>
    <div id="<?php echo esc_attr( $block_id ); ?>" class="<?php echo esc_attr( $css_class ); ?>">
		<?php Penci_Vc_Helper::markup_block_title( $atts ); ?>
        <div class="penci-block_content">
            <div class="penci-workingh-lists">
                <ul>
					<?php foreach ( (array) $working_hours as $hour ):
						$icon = isset( $hour['icon'] ) ? $hour['icon'] : '';
						$title = isset( $hour['title'] ) ? $hour['title'] : '';
						$hours = isset( $hour['hours'] ) ? $hour['hours'] : '';
						$subtitle = isset( $hour['subtitle'] ) ? $hour['subtitle'] : '';

						?>
                        <li class="penci-workingh-item">
                            <div class="penci-workingh-item-inner">
                                <div class="penci-workingh-line1">
                                    <div class="penci-icontitle">
										<?php
										if ( $icon ) {
											echo '<i class="penci-listitem-icon ' . $icon . '"></i>';
										}
										if ( $title ) {
											echo '<span class="penci-listitem-title">' . $title . '</span>';
										}
										?>
                                    </div>
									<?php
									if ( $hours ) {
										echo '<span class="penci-listitem-hours">' . $hours . '</span>';
									}
									?>
                                </div>
								<?php
								if ( $subtitle ) {
									echo '<span class="penci-listitem-subtitle">' . $subtitle . '</span>';
								}
								?>
                            </div>
                        </li>
					<?php endforeach; ?>

                </ul>
            </div>
        </div>
    </div>
<?php

$id_open_hours = '#' . $block_id;
$id_open_hours2 = 'body:not(.pcdm-enable) #' . $block_id;
$css_custom    = Penci_Vc_Helper::get_heading_block_css( $id_open_hours, $atts );

if ( $row_gap ) {
	$css_custom .= penci_extract_md_responsive_fsize( $id_open_hours . ' .penci-workingh-item{ margin-bottom: {{VALUE}}px;}', $row_gap );
}
if ( $subtitle_martop ) {
	$css_custom .= penci_extract_md_responsive_fsize( $id_open_hours . ' .penci-listitem-subtitle{ margin-top: {{VALUE}}px;}', $subtitle_martop );
}
if ( $ophour_icon_color ) {
	$css_custom .= $id_open_hours2 . ' .penci-workingh-lists .penci-listitem-icon{ color: ' . esc_attr( $ophour_icon_color ) . ';}';
}
if ( $ophour_icon_size ) {
	$css_custom .= penci_extract_md_responsive_fsize( $id_open_hours . ' .penci-workingh-lists .penci-listitem-icon{ font-size: {{VALUE}}px;}', $ophour_icon_size );
}
if ( $ophour_title_color ) {
	$css_custom .= $id_open_hours2 . ' .penci-workingh-lists .penci-listitem-title{ color: ' . esc_attr( $ophour_title_color ) . ';}';
}
if ( $ophour_title_size ) {
	$css_custom .= penci_extract_md_responsive_fsize( $id_open_hours . ' .penci-workingh-lists .penci-listitem-title{ font-size: {{VALUE}}px;}', $ophour_title_size );
}
if ( $ophour_subtitle_color ) {
	$css_custom .= $id_open_hours2 . ' .penci-workingh-lists .penci-listitem-subtitle{ color: ' . esc_attr( $ophour_subtitle_color ) . ';}';
}
if ( $ophour_subtitle_size ) {
	$css_custom .= penci_extract_md_responsive_fsize( $id_open_hours . ' .penci-workingh-lists .penci-listitem-subtitle{ font-size: {{VALUE}}px;}', $ophour_subtitle_size );
}
if ( $ophour_hour_color ) {
	$css_custom .= $id_open_hours2 . ' .penci-workingh-lists .penci-listitem-hours{ color: ' . esc_attr( $ophour_hour_color ) . ';}';
}
if ( $ophour_hour_size ) {
	$css_custom .= penci_extract_md_responsive_fsize( $id_open_hours . ' .penci-workingh-lists .penci-listitem-hours{ font-size: {{VALUE}}px;}', $ophour_hour_size );
}
if ( $ophour_hour_weight ) {
	$css_custom .= $id_open_hours . ' .penci-workingh-lists .penci-listitem-hours{ font-weight: ' . esc_attr( $ophour_hour_weight ) . ';}';
}

if ( $responsive_spacing ) {
	$css_custom .= penci_extract_spacing_style( $id_open_hours, $responsive_spacing );
}

if ( $css_custom ) {
	echo '<style>';
	echo $css_custom;
	echo '</style>';
}
