<?php 
$header_data    = $args['header_data'];
// verical line
$line2_width = penci_builder_validate_mod( $header_data, 'penci_header_pb_vertical_line2_width' );
$line2_height = penci_builder_validate_mod( $header_data, 'penci_header_pb_vertical_line2_height' );
$line2_color = penci_builder_validate_mod( $header_data, 'penci_header_pb_vertical_line2_color' );
$line2_class = penci_builder_validate_mod( $header_data, 'penci_header_pb_vertical_line2_class' );
$data_attr   = [];
if ( ! empty( $line2_width ) ) {
	$data_attr[] = 'width:' . $line2_width . 'px;';
}
if ( ! empty( $line2_height ) ) {
	$data_attr[] = 'height:' . $line2_height . 'px;';
}
if ( ! empty( $line2_color ) ) {
	$data_attr[] = 'background-color:' . $line2_color . ';';
}
$data_attr = implode( ' ', $data_attr );
?>
<div style="<?php echo $data_attr; ?>"
     class="penci-builder-element penci-vertical-line vertical-line-2 <?php echo esc_attr( $line2_class ); ?>"></div>
