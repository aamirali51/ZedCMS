<?php
$output = $penci_block_width = $el_class = $css_animation = $css = '';

$cup_style          = $cup_align = $cup_number = $cup_prefix_number = $cup_suffix_number = $cup_title = '';
$cup_icon_type      = $cup_image = $img_size = $cup_icon = $icon_border_style = $icon_border_width = '';
$icon_border_radius = $icon_padding = $_image_width_height = $icon_margin_bottom = $number_margin_top = $number_padding_lr = '';
$title_margin_top   = $button_margin_top = $button_margin_bottom = $cup_delay = $cup_time = '';

$cup_icon_color   = $cup_icon_bgcolor = $cup_icon_size = '';
$cup_number_color = $cup_number_typo = $cup_number_size = $cup_number_weight = '';
$cup_frefix_color = $cup_frefix_size = $cup_frefix_typo = '';
$cup_title_color  = $cup_title_typo = $cup_title_size = $cup_title_weight = $responsive_spacing = '';

$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );

$class_to_filter = vc_shortcode_custom_css_class( $css, ' ' ) . $this->getExtraClass( $el_class ) . $this->getCSSAnimation( $css_animation );

$css_class = 'penci-counter-up';
$css_class .= ' penci-style-' . $cup_style;
$css_class .= ' penci-counterup-' . $cup_align;
$css_class .= ' ' . apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $class_to_filter, $this->settings['base'], $atts );

$block_id = Penci_Vc_Helper::get_unique_id_block( 'counter_up' );
?>
    <div id="<?php echo esc_attr( $block_id ); ?>" class="<?php echo esc_attr( $css_class ); ?>">
		<?php
		if ( isset( $atts['show_block_heading'] ) && 'yes' == $atts['show_block_heading'] ) {
			Penci_Vc_Helper::markup_block_title( $atts );
		}
		?>
        <div class="penci-counter-up_inner">
			<?php
			if ( 'icon' == $cup_icon_type ) {
				vc_icon_element_fonts_enqueue( 'fontawesome' );
				if ( $cup_icon ) {
					$icon = '<div class="penci-cup_icon penci-cup_icon--icon">';
					$icon .= '<i class="penci-cup_iconn--i ' . $cup_icon . '"></i>';
					$icon .= '</div>';
					echo $icon;
				}
			} elseif ( $cup_image ) {
				$icon = '<div class="penci-cup_icon penci-cup_icon--image">';
				$icon .= '<img alt="" src="' . esc_url( wp_get_attachment_url( $cup_image ) ) . '">';
				$icon .= '</div>';

				echo $icon;
			}
			$data_delay  = $cup_delay ? $cup_delay : 0;
			$data_time   = $cup_time ? $cup_time : 2000;
			$data_number = $cup_number ? $cup_number : 0;
			?>
            <div class="penci-cup-info">
                <div class="penci-cup-number-wrapper">
				<span class="penci-span-inner">
				<?php if ( $cup_prefix_number ): ?><span
                        class="penci-cup-label penci-cup-prefix"><?php echo $cup_prefix_number; ?></span><?php endif; ?>
					<span class="penci-counterup-number" data-delay="<?php echo $data_delay; ?>"
                          data-time="<?php echo $data_time; ?>" data-count="<?php echo $data_number; ?>">0</span>
					<?php if ( $cup_suffix_number ): ?><span
                            class="penci-cup-label penci-cup-postfix"><?php echo $cup_suffix_number; ?></span><?php endif; ?>
				</span>
                </div>
				<?php if ( $cup_title ): ?>
                    <div class="penci-cup-title"><?php echo $cup_title; ?></div><?php endif; ?>
            </div>
        </div>
    </div>
<?php
$id_counter_up = '#' . $block_id;
$id_counter_up2 = 'body:not(.pcdm-enable) #' . $block_id;

$css_custom = Penci_Vc_Helper::get_heading_block_css( $id_counter_up, $atts );

if ( 'icon' == $cup_icon_type ) {
	if ( $icon_border_style ) {
		$css_custom .= $id_counter_up . ' .penci-cup_icon{ border-style: ' . ( $icon_border_style ) . ' }';

		if ( $icon_border_radius ) {
			$css_custom .= $id_counter_up . ' .penci-cup_icon{ border-radius: ' . ( $icon_border_radius ) . ' }';
		}
		if ( $icon_border_width ) {
			$css_custom .= $id_counter_up . ' .penci-cup_icon{ border-width: ' . ( $icon_border_width ) . ' }';
		} else {
			$css_custom .= $id_counter_up . ' .penci-cup_icon{ border-width: 1px; }';
		}
	}
}

if ( $icon_padding ) {
	$css_custom .= penci_extract_md_responsive_fsize( $id_counter_up . ' .penci-cup_icon{ padding: {{VALUE}}px }', $icon_padding );
}

if ( $_image_width_height ) {
	$css_custom .= penci_extract_md_responsive_fsize( $id_counter_up . ' .penci-cup_icon{ width: {{VALUE}}px;height: {{VALUE}}px }', $_image_width_height );
}
if ( $icon_margin_bottom ) {
	$css_custom .= penci_extract_md_responsive_fsize( $id_counter_up . ' .penci-cup_icon{ margin-bottom: {{VALUE}}px }', $icon_margin_bottom );
}

if ( $number_margin_top ) {
	$css_custom .= penci_extract_md_responsive_fsize( $id_counter_up . ' .penci-cup-number-wrapper{ margin-top: {{VALUE}}px }', $number_margin_top );
}

if ( $number_padding_lr ) {
	$css_custom .= penci_extract_md_responsive_fsize( $id_counter_up . ' .penci-counterup-number{ margin-left: {{VALUE}}px; margin-right: {{VALUE}}px; }', $number_padding_lr );
}

if ( $title_margin_top ) {
	$css_custom .= penci_extract_md_responsive_fsize( $id_counter_up . ' .penci-cup-title{ margin-top: {{VALUE}}px }', $title_margin_top );
}

$icon_custom_css_color = '';
if ( $cup_icon_color ) {
	$icon_custom_css_color .= 'color: ' . ( $cup_icon_color ) . ';';
}
if ( $cup_icon_bgcolor ) {
	$icon_custom_css_color .= 'background-color: ' . ( $cup_icon_bgcolor ) . ';';
}
if ( $cup_icon_size ) {
	$icon_custom_css_color .= 'font-size: ' . ( $cup_icon_size ) . ';';
}
if ( $icon_custom_css_color ) {
	$css_custom .= $id_counter_up . ' .penci-cup_icon{' . ( $icon_custom_css_color ) . '}';
}

$number_custom_css_color = '';
if ( $cup_number_color ) {
	$number_custom_css_color .= 'color: ' . ( $cup_number_color ) . ';';
}
if ( $cup_number_weight ) {
	$number_custom_css_color .= 'font-weight: ' . ( $cup_number_weight ) . ';';
}
if ( $number_custom_css_color ) {
	$css_custom .= $id_counter_up . ' .penci-counterup-number{' . ( $number_custom_css_color ) . '}';
}
$css_custom .= Penci_Vc_Helper::vc_google_fonts_parse_attributes( array(
	'font_size'  => $cup_number_size,
	'font_style' => $cup_number_typo,
	'template'   => $id_counter_up . ' .penci-counterup-number{ %s }',
) );

if ( $cup_frefix_color ) {
	$css_custom .= $id_counter_up . ' .penci-cup-label { color: ' . ( $cup_frefix_color ) . ';}';
}
$css_custom .= Penci_Vc_Helper::vc_google_fonts_parse_attributes( array(
	'font_size'  => $cup_frefix_size,
	'font_style' => $cup_frefix_typo,
	'template'   => $id_counter_up . ' .penci-cup-label{ %s }',
) );

$title_custom_css_color = '';
if ( $cup_title_color ) {
	$title_custom_css_color .= 'color: ' . ( $cup_title_color ) . ';';
}
if ( $cup_title_weight ) {
	$title_custom_css_color .= 'font-weight: ' . ( $cup_title_weight ) . ';';
}
if ( $title_custom_css_color ) {
	$css_custom .= $id_counter_up . ' .penci-cup-title{' . ( $title_custom_css_color ) . '}';
}
$css_custom .= Penci_Vc_Helper::vc_google_fonts_parse_attributes( array(
	'font_size'  => $cup_title_size,
	'font_style' => $cup_title_typo,
	'template'   => $id_counter_up . ' .penci-cup-title{ %s }',
) );

if ( $responsive_spacing ) {
	$css_custom .= penci_extract_spacing_style( $id_counter_up, $responsive_spacing );
}


if ( $css_custom ) {
	echo '<style>';
	echo $css_custom;
	echo '</style>';
}
