<?php
$output = $penci_block_width = $el_class = $css_animation = $css = '';

$title_page          = $page_url = $page_height = $hide_faces = $hide_stream = $hide_cover = $language = $responsive_spacing = '';
$heading_title_style = $heading = $heading_title_link = $heading_title_align = '';

$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );
if ( ! $language ) {
	$language = get_locale();
}
$class_to_filter = vc_shortcode_custom_css_class( $css, ' ' ) . $this->getExtraClass( $el_class ) . $this->getCSSAnimation( $css_animation );

$css_class = 'penci-block-vc penci_facebook_widget';
$css_class .= ' ' . apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $class_to_filter, $this->settings['base'], $atts );
$block_id  = Penci_Vc_Helper::get_unique_id_block( 'counter_up' );
?>
    <div id="<?php echo esc_attr( $block_id ); ?>" class="<?php echo esc_attr( $css_class ); ?>">
		<?php Penci_Vc_Helper::markup_block_title( $atts ); ?>
        <div class="penci-block_content">
            <div id="fb-root"></div>
            <script data-cfasync="false">(function (d, s, id) {
                    var js, fjs = d.getElementsByTagName(s)[0];
                    if (d.getElementById(id)) return;
                    js = d.createElement(s);
                    js.id = id;
                    js.src = "//connect.facebook.net/<?php echo esc_attr( $language )?>/sdk.js#xfbml=1&version=v9.0";
                    fjs.parentNode.insertBefore(js, fjs);
                }(document, 'script', 'facebook-jssdk'));</script>
            <div class="fb-page"
                 data-href="<?php echo esc_url( $page_url ); ?>"<?php if ( (! 'yes' == $hide_stream || ! 'true' == $hide_stream) && is_numeric( $page_height ) && $page_height > 69 ): ?> data-height="<?php echo absint( $page_height ); ?>"<?php endif; ?>
                 data-small-header="false" data-hide-cover="<?php if ( 'yes' == $hide_cover || 'true' == $hide_cover ) {
				echo 'true';
			} else {
				echo 'false';
			} ?>" data-show-facepile="<?php if ( ! 'yes' == $hide_faces || ! 'true' == $hide_faces ) {
				echo 'true';
			} else {
				echo 'false';
			} ?>" data-show-posts="<?php if ( 'yes' == $hide_stream || 'true' == $hide_stream ) {
				echo 'false';
			} else {
				echo 'true';
			} ?>" data-adapt-container-width="true">
                <div class="fb-xfbml-parse-ignore"><a
                            href="<?php echo esc_attr( $page_url ); ?>"><?php if ( $heading ) {
							echo sanitize_text_field( $heading );
						} else {
							echo 'Facebook';
						} ?></a></div>
            </div>
        </div>
    </div>
<?php

$id_facebook_page = '#' . $block_id;
$css_custom       = Penci_Vc_Helper::get_heading_block_css( $id_facebook_page, $atts );

if ( $responsive_spacing ) {
	$css_custom .= penci_extract_spacing_style( $id_facebook_page, $responsive_spacing );
}


if ( $css_custom ) {
	echo '<style>';
	echo $css_custom;
	echo '</style>';
}
