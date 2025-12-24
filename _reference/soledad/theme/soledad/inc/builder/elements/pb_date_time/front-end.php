<?php 
$header_data    = $args['header_data'];
$date_time_format = penci_builder_validate_mod( $header_data, 'penci_header_pb_data_time_format' );
$class            = penci_builder_validate_mod( $header_data, 'penci_header_pb_data_time_css_class' );
if ( empty( $date_time_format ) ) {
	return false;
}
?>

<div class="penci-builder-element penci-data-time-format <?php echo esc_attr( $class ); ?>">
	<?php
	if ( function_exists( 'wp_date' ) ) {
		$return = wp_date( $date_time_format );
	} else {
		$return = current_time( $date_time_format );
	}
	$time_class = get_theme_mod( 'penci_time_sync' ) ? 'penci-dtf-real' : 'penci-dtf-normal';
	?>
    <span data-format="<?php echo $date_time_format; ?>"
          class="<?php echo $time_class; ?>"><?php echo $return; ?></span>
</div>
