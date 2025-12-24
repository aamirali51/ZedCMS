<?php
$settings   = vc_map_get_attributes( $this->getShortcode(), $atts );
$css_class  = vc_shortcode_custom_css_class( $settings['css'] );
$block_id   = Penci_Vc_Helper::get_unique_id_block( 'pc_single_meta' );
$css_custom = '';
global $post;
$label = $settings['meta_label'] ? '<span class="label">' . $settings['meta_label'] . '</span>' : '';

$meta = $meta_key = '';

if ( 'custom' == $settings['meta_source'] && $settings['meta'] ) {
	$meta_key = $settings['meta'];
}

if ( 'acf' == $settings['meta_source'] && $settings['acf'] ) {
	$meta_key = $settings['acf'];
}

$meta = get_post_meta( get_the_ID(), $meta_key, true );

$filed_type = penci_get_field_type( $meta_key );

if ( 'image' == $filed_type && is_numeric( $meta ) ) {
	$meta = wp_get_attachment_image( $meta );
}

if ( $meta ) {
	?>
    <div class="post-box-meta-single style-<?php echo esc_attr( $settings['meta_icon_style'] ); ?>">
            <span>
	            <?php echo $label; ?>
            <span class="content"><?php echo do_shortcode( $meta ); ?></span></span>
    </div>
	<?php
}
$css = [
	'penci_single_meta_gnr_color'      => [
		'{{WRAPPER}} .post-box-meta-single, {{WRAPPER}} .post-box-meta-single span' => 'color:{{VALUE}}'
	],
	'penci_single_meta_gnr_icon_color' => [ '{{WRAPPER}} .pcmt-icon' => 'color:{{VALUE}}' ],
	'penci_single_meta_gnr_bg_color'   => [
		'{{WRAPPER}} .pcmt-icon'                                       => 'background-color:{{VALUE}}',
		'{{WRAPPER}} .post-box-meta-single.style-s3 .pcmt-icon:after'  => 'border-left-color:{{VALUE}} !important',
		'{{WRAPPER}} .post-box-meta-single.style-s4 .pcmt-icon:before' => 'border-left-color:{{VALUE}} !important',

	],
	'meta-link-color'                  => [
		'{{WRAPPER}} .post-box-meta-single a' => 'color:{{VALUE}}'
	],
	'meta-link-hcolor'                 => [
		'{{WRAPPER}} .post-box-meta-single a:hover' => 'color:{{VALUE}}'
	],
	'penci_single_meta_mt_bg_color'                 => [
		'{{WRAPPER}} .post-box-meta-single' => 'background-color:{{VALUE}}'
	],
];
penci_wpbakery_el_extract_css( $css, $settings, '#' . $block_id );
