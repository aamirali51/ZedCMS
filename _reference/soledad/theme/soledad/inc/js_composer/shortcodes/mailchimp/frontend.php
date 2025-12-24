<?php
$output              = $penci_block_width = $el_class = $css_animation = $css = '';
$mailchimp_style     = $mc4wp_bg_color = '';
$mc4wp_des_color     = $mc4wp_bg_input_color = $mc4wp_border_input_color = $mc4wp_text_input = $mc4wp_placeh_input = '';
$mc4wp_submit_color  = $mc4wp_submit_bgcolor = $mc4wp_submit_border_color = '';
$mc4wp_submit_hcolor = $mc4wp_submit_hbgcolor = $mc4wp_submit_hborder_color = $responsive_spacing = '';

$mc4wp_des_width = $mc4wp_des_martop = $mc4wp_des_marbottom = '';

$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );

$class_to_filter = vc_shortcode_custom_css_class( $css, ' ' ) . $this->getExtraClass( $el_class ) . $this->getCSSAnimation( $css_animation );

$css_class = 'penci-block-vc penci-mailchimp-block';
$css_class .= ' penci-mailchimp-' . $mailchimp_style;
$css_class .= ' ' . apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $class_to_filter, $this->settings['base'], $atts );
$block_id  = Penci_Vc_Helper::get_unique_id_block( 'mailchimp' );

$class_signup_form = 'widget widget_mc4wp_form_widget';
if ( 's2' == $mailchimp_style ) {
	$class_signup_form .= ' penci-header-signup-form';
} elseif ( 's3' == $mailchimp_style ) {
	$class_signup_form .= ' footer-subscribe';
}
?>
<div id="<?php echo esc_attr( $block_id ); ?>" class="<?php echo esc_attr( $css_class ); ?>">
<?php Penci_Vc_Helper::markup_block_title( $atts ); ?>
    <div class="penci-block_content">
    <div class="<?php echo esc_attr( $class_signup_form ); ?>">
		<?php
		if ( function_exists( 'mc4wp_show_form' ) ) {
			mc4wp_show_form();
		}
		?>
        <div>
        </div>
    </div>
<?php

$id_mailchimp = '#' . $block_id;
$id_mailchimp2 = 'body:not(.pcdm-enable) #' . $block_id;
$css_custom   = Penci_Vc_Helper::get_heading_block_css( $id_mailchimp, $atts );

if ( $mc4wp_bg_color ) {
	$css_custom .= $id_mailchimp . ' .footer-subscribe,';
	$css_custom .= $id_mailchimp . ' .penci-header-signup-form{ background-color: ' . esc_attr( $mc4wp_bg_color ) . '; }';
}
if ( $mc4wp_bg_color ) {
	$css_custom .= $id_mailchimp . ' .footer-subscribe,';
	$css_custom .= $id_mailchimp . ' .penci-header-signup-form{ background-color: ' . esc_attr( $mc4wp_bg_color ) . '; }';
}

if ( $mc4wp_des_color ) {
	$css_custom .= $id_mailchimp . ' .penci-header-signup-form .mc4wp-form-fields > p,';
	$css_custom .= $id_mailchimp . ' .penci-header-signup-form form > p,';
	$css_custom .= $id_mailchimp . ' .footer-subscribe .mc4wp-form .mdes,';
	$css_custom .= $id_mailchimp . ' .mc4wp-form-fields{ color: ' . esc_attr( $mc4wp_des_color ) . '; }';
}

if ( $mc4wp_des_width ) {
	$css_custom .= penci_extract_md_responsive_fsize( $id_mailchimp . ' .mc4wp-form .mdes{ max-width: {{VALUE}}px;width: 100%;display: inline-block; }', $mc4wp_des_width );
}
if ( $mc4wp_des_martop ) {
	$css_custom .= penci_extract_md_responsive_fsize( $id_mailchimp . ' .mc4wp-form .mdes{ margin-top: {{VALUE}}px; }', $mc4wp_des_martop );
}
if ( $mc4wp_des_marbottom ) {
	$css_custom .= penci_extract_md_responsive_fsize( $id_mailchimp . ' .mc4wp-form .mdes{ margin-bottom: {{VALUE}}px; }', $mc4wp_des_marbottom );
}

$css_custom_input = '';
if ( $mc4wp_bg_input_color ) {
	$css_custom_input .= 'background-color: ' . esc_attr( $mc4wp_bg_input_color ) . ';';
}
if ( $mc4wp_border_input_color ) {
	$css_custom_input .= 'border-color: ' . esc_attr( $mc4wp_border_input_color ) . ';';
}
if ( $mc4wp_text_input ) {
	$css_custom_input .= 'color: ' . esc_attr( $mc4wp_text_input ) . ';';
}

if ( $css_custom_input ) {
	$css_custom .= $id_mailchimp . ' .widget input[type="text"],';
	$css_custom .= $id_mailchimp . ' .widget input[type="email"],';
	$css_custom .= $id_mailchimp . ' .widget input[type="date"],';
	$css_custom .= $id_mailchimp . ' .widget input[type="number"],';
	$css_custom .= $id_mailchimp . ' .widget input[type="search"],';
	$css_custom .= $id_mailchimp . ' .widget input[type="password"]{' . esc_attr( $css_custom_input ) . '}';
}

if ( $mc4wp_placeh_input ) {
	$css_custom .= $id_mailchimp . ' input::-webkit-input-placeholder{ color:' . esc_attr( $mc4wp_placeh_input ) . '; }';
	$css_custom .= $id_mailchimp . ' input::-moz-placeholder { color:' . esc_attr( $mc4wp_placeh_input ) . '; }';
	$css_custom .= $id_mailchimp . ' input:-ms-input-placeholder{ color:' . esc_attr( $mc4wp_placeh_input ) . '; }';
	$css_custom .= $id_mailchimp . ' input:-moz-placeholder{ color:' . esc_attr( $mc4wp_placeh_input ) . '; }';
}

$submit_color = $submit_hcolor = '';
if ( $mc4wp_submit_color ) {
	$submit_color .= 'color:' . esc_attr( $mc4wp_submit_color ) . ';';
}
if ( $mc4wp_submit_bgcolor ) {
	$submit_color .= 'background-color:' . esc_attr( $mc4wp_submit_bgcolor ) . ';';
}
if ( $mc4wp_submit_border_color ) {
	$submit_color .= 'border-color:' . esc_attr( $mc4wp_submit_border_color ) . ';';
}

if ( $submit_color ) {
	$css_custom .= $id_mailchimp2 . ' .mc4wp-form input[type="submit"]{ ' . $submit_color . ' }';
}

if ( $mc4wp_submit_hcolor ) {
	$submit_hcolor .= 'color:' . esc_attr( $mc4wp_submit_hcolor ) . ';';
}
if ( $mc4wp_submit_hbgcolor ) {
	$submit_hcolor .= 'background-color:' . esc_attr( $mc4wp_submit_hbgcolor ) . ';';
}
if ( $mc4wp_submit_hborder_color ) {
	$submit_hcolor .= 'border-color:' . esc_attr( $mc4wp_submit_hborder_color ) . ';';
}

if ( $submit_hcolor ) {
	$css_custom .= $id_mailchimp . ' .mc4wp-form input[type="submit"]:hover{' . $submit_hcolor . '}';
}

if ( $responsive_spacing ) {
	$css_custom .= penci_extract_spacing_style( $id_mailchimp, $responsive_spacing );
}

if ( $atts['content_w'] ) {
	$css_custom .= penci_extract_md_responsive_fsize( $id_mailchimp . ' .penci-mailchimp-block{max-width: {{VALUE}}px;width:100%;}', $atts['content_w'] );
}

if ( $atts['content_alignment'] ) {
	$css_custom .= $id_mailchimp . ' .penci-mailchimp-block{text-align: ' . $atts['content_alignment'] . ';}';
}

if ( $css_custom ) {
	echo '<style>';
	echo $css_custom;
	echo '</style>';
}
