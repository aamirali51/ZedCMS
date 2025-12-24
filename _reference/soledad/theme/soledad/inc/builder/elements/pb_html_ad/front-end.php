<?php 
$header_data    = $args['header_data'];
$ads_html = penci_builder_validate_mod( $header_data, 'penci_header_builder_pb_html_name', false );
if ( empty( $ads_html ) ) {
	return false;
}
?>

<div class="penci-builder-element penci-html-ads penci-html-ads-1">
	<?php echo do_shortcode( $ads_html ); ?>
</div>
