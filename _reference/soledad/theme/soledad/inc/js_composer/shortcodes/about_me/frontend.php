<?php
$output = $penci_block_width = $el_class = $css_animation = $css = '';

$image              = $thumbnail_size = $link = $link_external = $link_nofollow = '';
$about_us_heading   = $align_block = $title_size = '';
$img_circle         = $dis_lazyload = $image_space = $image_width = $responsive_spacing = '';
$title_bottom_space = '';

$title_color = $content_color = '';

$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );

$class_to_filter = vc_shortcode_custom_css_class( $css, ' ' ) . $this->getExtraClass( $el_class ) . $this->getCSSAnimation( $css_animation );

$css_class = 'penci-block-vc penci-about-me';
$css_class .= ' ' . apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $class_to_filter, $this->settings['base'], $atts );
$block_id  = Penci_Vc_Helper::get_unique_id_block( 'about_us' );

$title_tag        = $title_tag ? $title_tag : 'h3';
$open_image       = '';
$close_image      = '';
$target_html      = '';
$image_width_data = $image_height_data = '';

$image_info = wp_get_attachment_image_src( $image, $thumbnail_size, false );
list( $image_src, $width, $height ) = $image_info;


if ( $link ):
	if ( $link_nofollow == 'yes' ) {
		$target_html .= ' rel="nofollow"';
	}

	if ( $link_external == 'yes' ) {
		$target_html .= ' target="_blank"';
	}

	$open_image  = '<a href="' . do_shortcode( $link ) . '"' . $target_html . '>';
	$close_image = '</a>';
endif;
?>
    <div id="<?php echo esc_attr( $block_id ); ?>" class="<?php echo esc_attr( $css_class ); ?>">
		<?php Penci_Vc_Helper::markup_block_title( $atts ); ?>
        <div class="penci-block_content about-widget<?php if ( $align_block ): echo ' pc_align' . $align_block; endif; ?>">
			<?php if ( $image_src ) :
				$image_width_data = penci_get_image_data_basedurl( $image_src, 'w' );
				$image_height_data = penci_get_image_data_basedurl( $image_src, 'h' );
				?>
				<?php echo $open_image; ?>
				<?php if ( ! $dis_lazyload == 'yes' ) { ?>
                <img class="penci-widget-about-image nopin penci-lazy" nopin="nopin"
                     width="<?php echo $image_width_data; ?>" height="<?php echo $image_height_data; ?>"
                     src="<?php echo penci_holder_image_base( $image_width_data, $image_height_data ); ?>"
                     data-src="<?php echo esc_url( $image_src ); ?>"
                     alt="<?php echo esc_attr( $about_us_heading ); ?>"/>
			<?php } else { ?>
                <img class="penci-widget-about-image nopin" nopin="nopin" width="<?php echo $image_width_data; ?>"
                     height="<?php echo $image_height_data; ?>" src="<?php echo esc_url( $image_src ); ?>"
                     alt="<?php echo esc_attr( $about_us_heading ); ?>"/>
			<?php } ?>
				<?php echo $close_image; ?>
			<?php endif; ?>

			<?php if ( $about_us_heading ) : ?>
            <<?php echo $title_tag; ?> class="about-me-heading"><?php echo do_shortcode( $about_us_heading ); ?>
        </<?php echo $title_tag; ?>>
	<?php endif; ?>

		<?php if ( $content ) : ?>
            <div class="penci-aboutme-content"><?php echo do_shortcode( $content ); ?></div>
		<?php endif; ?>
    </div>
    </div>
<?php
$id_about_me = 'body:not(.pcdm-enable) #' . $block_id;
$css_custom  = Penci_Vc_Helper::get_heading_block_css( $id_about_me, $atts );
if ( $align_block ) {
	$css_custom .= $id_about_me . ' .about-widget{ text-align: ' . $align_block . ' !important; }';
}
if ( $image_width ) {
	$css_custom .= penci_extract_md_responsive_fsize( $id_about_me . ' .about-widget img{ max-width: {{VALUE}} !important; width:100%; }', $image_width );
}
if ( $img_circle == 'yes' ) {
	$css_custom .= $id_about_me . ' .about-widget img{ border-radius: 50%; -webkit-border-radius: 50%;}';
}
if ( $image_space ) {
	$css_custom .= penci_extract_md_responsive_fsize( $id_about_me . ' .about-widget img{ margin-bottom:{{VALUE}}px;}', $image_space );
}
if ( $title_bottom_space ) {
	$css_custom .= penci_extract_md_responsive_fsize( $id_about_me . ' .about-me-heading{ margin-bottom:{{VALUE}}px;}', $title_bottom_space );
}
if ( $title_color ) {
	$css_custom .= $id_about_me . ' .about-me-heading{ color:' . esc_attr( $title_color ) . ';}';
}
if ( $content_color ) {
	$css_custom .= $id_about_me . ' .penci-aboutme-content{ color:' . esc_attr( $content_color ) . ';}';
}
if ( $responsive_spacing ) {
	$css_custom .= penci_extract_spacing_style( $id_about_me, $responsive_spacing );
}

if ( $css_custom ) {
	echo '<style>';
	echo $css_custom;
	echo '</style>';
}
