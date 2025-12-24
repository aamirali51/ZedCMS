<?php
header( "Content-type: image/png" );

$height = 100;
$width  = 50;

$start = str_pad( dechex( mt_rand( 0, 0xFFFFFF ) ), 6, '0', STR_PAD_LEFT );
$end   = str_pad( dechex( mt_rand( 0, 0xFFFFFF ) ), 6, '0', STR_PAD_LEFT );
if ( isset( $_GET["start"] ) && $_GET["start"] ) {
	$start = $_GET["start"];
}
if ( isset( $_GET["end"] ) && $_GET["end"] ) {
	$end = $_GET["end"];
}
if ( isset( $_GET["w"] ) && $_GET["w"] ) {
	$width = $_GET["w"];
}
if ( isset( $_GET["h"] ) && $_GET["h"] ) {
	$height = $_GET["h"];
}

$start_r = hexdec( substr( $start, 0, 2 ) );
$start_g = hexdec( substr( $start, 2, 2 ) );
$start_b = hexdec( substr( $start, 4, 2 ) );
$end_r   = hexdec( substr( $end, 0, 2 ) );
$end_g   = hexdec( substr( $end, 2, 2 ) );
$end_b   = hexdec( substr( $end, 4, 2 ) );
$image   = @imagecreate( $width, $height );

for ( $y = 0; $y < $height; $y ++ ) {
	for ( $x = 0; $x < $width; $x ++ ) {
		if ( $start_r == $end_r ) {
			$new_r = $start_r;
		}
		$difference = $start_r - $end_r;
		$new_r      = $start_r - intval( ( $difference / $height ) * $y );
		if ( $start_g == $end_g ) {
			$new_g = $start_g;
		}
		$difference = $start_g - $end_g;
		$new_g      = $start_g - intval( ( $difference / $height ) * $y );
		if ( $start_b == $end_b ) {
			$new_b = $start_b;
		}
		$difference = $start_b - $end_b;
		$new_b      = $start_b - intval( ( $difference / $height ) * $y );
		$row_color  = imagecolorresolve( $image, $new_r, $new_g, $new_b );
		imagesetpixel( $image, $x, $y, $row_color );
	}
}

imagepng( $image );
imagedestroy( $image );
