<?php
$atts     = vc_map_get_attributes( $this->getShortcode(), $atts );
$block_id = Penci_Vc_Helper::get_unique_id_block( 'web_stories' );
?>
    <div id="<?php echo esc_attr( $block_id ); ?>">
		<?php
		Penci_Vc_Helper::markup_block_title( $atts );
		if ( function_exists( 'penci_webstories' ) ) {
			penci_webstories( $atts );
		} else {
			echo '<p>Please install the <a target="_blank" href="https://wordpress.org/plugins/web-stories/">Google Web Stories</a> plugin to continue using this widget.</p>';
		}
		?>
    </div>
<?php
$block_id_selector = '#' . $block_id;
$block_id_selector2 = 'body:not(.pcdm-enable) #' . $block_id;
echo '<style>';
if ( $atts['columns'] ) {
	echo $block_id_selector . ' .pc-wstories-wrapper .pc-wstories-list.grid .pc-webstory-item{width:calc(100% / ' . $atts['columns'] . ')}';
	echo $block_id_selector . ' .pc-wstories-wrapper .pc-wstories-list.slider:not(.penci-owl-loaded) .pc-webstory-item{width:calc(100% / ' . $atts['columns'] . ')}';
}
if ( $atts['iboradius'] ) {
	echo $block_id_selector . ' .pc-wstories-wrapper .pc-webstory-thumb-wrapper, '. $block_id_selector .' .pc-wstories-wrapper .pc-webstory-thumb{border-radius:' . $atts['iboradius'] . 'px;}';
}
if ( $atts['iwidth'] ) {
	echo $block_id_selector . ' .pc-wstories-wrapper .pc-wstories-list.one-row .pc-webstory-item{flex: 0 0 ' . $atts['iwidth'] . 'px;width:' . $atts['iwidth'] . 'px;}';
}
if ( $atts['title_fsize'] ) {
	echo $block_id_selector . ' .pc-webstory-item .pc-webstory-item-title h4{font-size:' . $atts['title_fsize'] . 'px}';
}
if ( $atts['responsive_spacing'] ) {
	echo penci_extract_spacing_style( $block_id, $atts['responsive_spacing'] );
}
if ( $atts['use_ptitle_typo'] ) {
	echo Penci_Vc_Helper::vc_google_fonts_parse_attributes( array(
		'font_size'  => $atts['title_fsize'],
		'font_style' => $atts['use_ptitle_typo'],
		'template'   => $block_id_selector . '  .pc-webstory-item .pc-webstory-item-title h4{ %s }',
	) );
}
if ( $atts['bd_color'] ) {
	echo $block_id_selector2 . ' .pc-wstories-wrapper .pc-webstory-thumb-wrapper{background-color:' . $atts['bd_color'] . '}';
}
if ( $atts['bd_scolor'] ) {
	echo $block_id_selector2 . ' .pc-wstories-wrapper .pc-webstory-item.seen .pc-webstory-thumb-wrapper{background-color:' . $atts['bd_scolor'] . '}';
}
if ( $atts['bd_w'] ) {
	echo $block_id_selector . ' .pc-wstories-wrapper .pc-webstory-thumb-wrapper{padding:' . $atts['bd_w'] . 'px}';
}
if ( $atts['bd_iw'] ) {
	echo $block_id_selector . ' .pc-wstories-wrapper .pc-webstory-thumb{border-width:' . $atts['bd_iw'] . 'px}';
}
if ( $atts['color_title'] ) {
	echo $block_id_selector2 . ' .pc-webstory-item .pc-webstory-item-title h4{color:' . $atts['color_title'] . '}';
}
if ( $atts['gap'] ) {
	echo $block_id_selector . ' .pc-wstories{--gap:' . $atts['gap'] . 'px}';
}
echo '</style>';

wp_enqueue_script( 'penci_web_stories' );
