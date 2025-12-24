<?php

$link = $title = $label = $label_text = $image = $image_size = $css_animation = $el_class = '';

$output = $class = $liclass = $label_out = '';

$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );

if ( penci_get_menu_label_tag( $label, $label_text ) ) {
	$liclass .= penci_get_menu_label_class( $label );
}

if ( $el_class ) {
	$class .= ' ' . $this->getExtraClass( $el_class );
}
$class .= ' mega-menu-list';
$class .= $this->getCSSAnimation( $css_animation );
$class .= ' sub-menu';

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

<ul class="penci-sub-menu<?php echo esc_attr( $class ); ?>">
    <li class="<?php echo esc_attr( $liclass ); ?>">
        <a <?php echo penci_get_link_attributes( vc_build_link($link) ); ?>>
			<?php if ( $image_output ) : ?>
				<?php echo $image_output; ?>
			<?php endif; ?>

            <span class="nav-link-text">
							<?php echo wp_kses( $title, penci_allow_html() ); ?>
						</span>
			<?php echo penci_get_menu_label_tag( $label, $label_text ); ?>
        </a>
        <ul class="sub-sub-menu">
			<?php echo do_shortcode( $content ); ?>
        </ul>
    </li>
</ul>


