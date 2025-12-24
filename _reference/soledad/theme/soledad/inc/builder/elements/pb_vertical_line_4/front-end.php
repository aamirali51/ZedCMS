<?php 
$header_data    = $args['header_data'];
// verical line
$line4_width = penci_builder_validate_mod( $header_data, 'penci_header_pb_vertical_line4_width' );
$line4_height = penci_builder_validate_mod( $header_data, 'penci_header_pb_vertical_line4_height' );
$line4_color = penci_builder_validate_mod( $header_data, 'penci_header_pb_vertical_line4_color' );
$line4_class = penci_builder_validate_mod( $header_data, 'penci_header_pb_vertical_line4_class' );
$data_attr   = [];
if ( ! empty( $line4_width ) ) {
	$data_attr[] = 'width:' . $line4_width . 'px;';
}
if ( ! empty( $line4_height ) ) {
	$data_attr[] = 'height:' . $line4_height . 'px;';
}
if ( ! empty( $line4_color ) ) {
	$data_attr[] = 'background-color:' . $line4_color . ';';
}
$data_attr = implode( ' ', $data_attr );
?>
<div style="<?php echo $data_attr; ?>"
     class="penci-builder-element penci-vertical-line vertical-line-4 <?php echo esc_attr( $line4_class ); ?>"></div>
