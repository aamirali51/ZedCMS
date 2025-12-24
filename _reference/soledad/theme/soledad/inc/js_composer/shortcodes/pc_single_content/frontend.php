<?php
$settings    = vc_map_get_attributes( $this->getShortcode(), $atts );
$block_style = $settings['block_style'];
$block_id    = Penci_Vc_Helper::get_unique_id_block( 'pc_single_content' );
$css_class   = vc_shortcode_custom_css_class( $settings['css'] );
$css_custom  = '';
?>
    <article class="post">
        <div id="<?php echo $block_id; ?>" class="post-entry <?php echo $css_class . ' blockquote-' . $block_style; ?>">
            <div class="inner-post-entry entry-content" id="penci-post-entry-inner">

				<?php do_action( 'penci_action_before_the_content' ); ?>

				<?php the_content(); ?>

				<?php do_action( 'penci_action_after_the_content' ); ?>

                <div class="penci-single-link-pages">
					<?php wp_link_pages(); ?>
                </div>
            </div>
        </div>
    </article>
<?php
if ( $settings['main-text-color'] ) {
	$css_custom .= '#' . $block_id . ' .post-entry{color:' . $settings['main-text-color'] . ';}';
}
if ( $settings['main-link-color'] ) {
	$css_custom .= '#' . $block_id . ' .post-entry a{color:' . $settings['main-link-color'] . ';}';
}
if ( $settings['main-link-hcolor'] ) {
	$css_custom .= '#' . $block_id . ' .post-entry a:hover{color:' . $settings['main-link-hcolor'] . ';}';
}
if ( $settings['main-text-color'] ) {
	$css_custom .= '#' . $block_id . ' .post-entry{color:' . $settings['main-text-color'] . ';}';
}
if ( $settings['main-text-color'] ) {
	$css_custom .= '#' . $block_id . ' .post-entry{color:' . $settings['main-text-color'] . ';}';
}
if ( $settings['content-h1-color'] ) {
	$css_custom .= '#' . $block_id . ' .post-entry h1{color:' . $settings['content-h1-color'] . ';}';
}
if ( $settings['content-h2-color'] ) {
	$css_custom .= '#' . $block_id . ' .post-entry h1{color:' . $settings['content-h2-color'] . ';}';
}
if ( $settings['content-h3-color'] ) {
	$css_custom .= '#' . $block_id . ' .post-entry h1{color:' . $settings['content-h3-color'] . ';}';
}
if ( $settings['content-h4-color'] ) {
	$css_custom .= '#' . $block_id . ' .post-entry h1{color:' . $settings['content-h4-color'] . ';}';
}
if ( $settings['content-h5-color'] ) {
	$css_custom .= '#' . $block_id . ' .post-entry h1{color:' . $settings['content-h5-color'] . ';}';
}
if ( $settings['content-h6-color'] ) {
	$css_custom .= '#' . $block_id . ' .post-entry h1{color:' . $settings['content-h6-color'] . ';}';
}
if ( $settings['responsive_spacing'] ) {
	$css_custom .= penci_extract_spacing_style( '#' . $block_id, $settings['responsive_spacing'] );
}
if ( $css_custom ) {
	echo '<style>';
	echo $css_custom;
	echo '</style>';
}
