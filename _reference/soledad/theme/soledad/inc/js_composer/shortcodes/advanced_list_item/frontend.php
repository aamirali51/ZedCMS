<?php


if ( ! function_exists( 'wpb_getImageBySize' ) ) {
	return;
}
$link   = $title = $image = $image_size = $label = $label_text = $el_class = '';
$output = $class = $label_out = '';
$atts   = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );

if ( penci_get_menu_label_tag( $label, $label_text ) ) {
	$class .= penci_get_menu_label_class( $label );
}

if ( $el_class ) {
	$class .= ' ' . $el_class;
}

// Image settings.
$image_output = '';
if ( function_exists( 'wpb_getImageBySize' ) && $image ) {
	$img          = wpb_getImageBySize( array(
		'attach_id'  => $image,
		'thumb_size' => $image_size,
		'class'      => 'penci-nav-img'
	) );
	$image_output = isset( $img['thumbnail'] ) ? $img['thumbnail'] : '';
}

?>

<li class="<?php echo esc_attr( $class ); ?>">
    <a <?php echo penci_get_link_attributes( vc_build_link( $link ) ); ?>>
		<?php if ( $image_output ) : ?>
			<?php echo $image_output; ?>
		<?php endif; ?>

		<?php echo wp_kses( $title, penci_allow_html() ); ?>
		<?php echo penci_get_menu_label_tag( $label, $label_text ); ?>
    </a>
</li>

		

