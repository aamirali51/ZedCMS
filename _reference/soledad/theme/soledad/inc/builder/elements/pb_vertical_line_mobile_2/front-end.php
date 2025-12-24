<?php 
$header_data    = $args['header_data'];
// verical line
$line5_width  = penci_builder_validate_mod( $header_data, 'penci_header_pb_vertical_line_mobile2_width' );
$line5_height = penci_builder_validate_mod( $header_data, 'penci_header_pb_vertical_line_mobile2_height' );
$line5_color  = penci_builder_validate_mod( $header_data, 'penci_header_pb_vertical_line_mobile2_color' );
$line5_class  = penci_builder_validate_mod( $header_data, 'penci_header_pb_vertical_line_mobile2_class' );
$data_attr    = [];
if ( ! empty( $line5_width ) ) {
	$data_attr[] = 'width:' . $line5_width . 'px;';
}
if ( ! empty( $line5_height ) ) {
	$data_attr[] = 'width:' . $line5_height . 'px;';
}
if ( ! empty( $line5_color ) ) {
	$data_attr[] = 'background-color:' . $line5_color . ';';
}
$data_attr = implode( ' ', $data_attr );
?>
<div style="<?php echo $data_attr; ?>"
     class="penci-builder-element penci-vertical-line vertical-line-mobile-2 <?php echo esc_attr( $line5_class ); ?>"></div>
