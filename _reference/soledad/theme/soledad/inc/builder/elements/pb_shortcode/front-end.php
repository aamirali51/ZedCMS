<?php 
$header_data    = $args['header_data'];
$shortcode_html  = penci_builder_validate_mod( $header_data, 'penci_header_builder_pb_shortcode_name', false );
$shortcode_class = penci_builder_validate_mod( $header_data, 'penci_header_builder_pb_shortcode_class' );
if ( empty( $shortcode_html ) ) {
	return false;
}
?>

<div class="penci-builder-element penci-shortcodes <?php echo esc_attr( $shortcode_class ); ?>">
	<?php echo do_shortcode( $shortcode_html ); ?>
</div>
