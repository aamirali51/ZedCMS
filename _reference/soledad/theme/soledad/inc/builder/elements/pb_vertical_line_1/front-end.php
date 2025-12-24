<?php 
$header_data    = $args['header_data'];
// verical line
$line1_width  = penci_builder_validate_mod( $header_data, 'penci_header_pb_vertical_line1_width' );
$line1_height = penci_builder_validate_mod( $header_data, 'penci_header_pb_vertical_line1_height' );
$line1_color  = penci_builder_validate_mod( $header_data, 'penci_header_pb_vertical_line1_color' );
$line1_class  = penci_builder_validate_mod( $header_data, 'penci_header_pb_vertical_line1_class' );
$data_attr    = [];
if ( ! empty( $line1_width ) ) {
	$data_attr[] = 'width:' . $line1_width . 'px;';
}
if ( ! empty( $line1_height ) ) {
	$data_attr[] = 'height:' . $line1_height . 'px;';
}
if ( ! empty( $line1_color ) ) {
	$data_attr[] = 'background-color:' . $line1_color . ';';
}
$data_attr = implode( ' ', $data_attr );
?>
<div style="<?php echo $data_attr; ?>"
     class="penci-builder-element penci-vertical-line vertical-line-1 <?php echo esc_attr( $line1_class ); ?>"></div>
