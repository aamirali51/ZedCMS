<?php
/**
 * Shortcode attributes
 * @var $atts
 * @var $css_animation
 * @var $el_class
 * @var $css
 */

$el_class = $css_animation = $css = $responsive_spacing = '';

$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );

$class_to_filter = '';
$class_to_filter .= vc_shortcode_custom_css_class( $css, ' ' ) . $this->getExtraClass( $el_class ) . $this->getCSSAnimation( $css_animation );

$css_class = 'penci-block-vc penci-block-popular-cat widget_categories widget widget_categories';
$css_class .= ' ' . apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $class_to_filter, $this->settings['base'], $atts );

$block_id = Penci_Vc_Helper::get_unique_id_block( 'popular_cat' );

$c          = 'yes' == $atts['pcount'] ? '1' : '0';
$h          = 'yes' == $atts['phierarchical'] ? '1' : '0';
$limit      = $atts['plimit'] ? $atts['plimit'] : 6;
$exclude    = 'yes' == $atts['phide_uncat'] ? '1' : '';
$hide_empty = 'yes' != $atts['pcount'];

$cat_args = array(
	'show_count'   => $c,
	'hierarchical' => $h,
	'hide_empty'   => $hide_empty,
	'number'       => $limit,
	'title_li'     => '',
	'exclude'      => $exclude
);
?>
<div id="<?php echo esc_attr( $block_id ) ?>" class="<?php echo esc_attr( $css_class ); ?>">
	<?php Penci_Vc_Helper::markup_block_title( $atts ); ?>
    <div class="penci-div-inner">
        <ul>
			<?php
			wp_list_categories( $cat_args );
			?>
        </ul>
    </div>
</div>
<?php
$block_id_css = '#' . $block_id;
$block_id_css2 = 'body:not(.pcdm-enable) #' . $block_id;
$css_custom   = '';

$css_custom .= Penci_Vc_Helper::get_heading_block_css( $block_id_css, $atts );

if ( $atts['plink_color'] ) {
	$css_custom .= $block_id_css2 . ' ul li a{ color: ' . esc_attr( $atts['plink_color'] ) . '; }';
}
if ( $atts['plink_hcolor'] ) {
	$css_custom .= $block_id_css2 . ' ul li a:hover{ color: ' . esc_attr( $atts['plink_hcolor'] ) . '; }';
}

if ( $atts['pcat_item_size'] ) {
	$css_custom .= penci_extract_md_responsive_fsize( $block_id_css . ' ul li a{ font-size: {{VALUE}}px; }', $atts['pcat_item_size'] );
}
if ( $atts['ppcount_color'] ) {
	$css_custom .= $block_id_css2 . ' ul li a .category-item-count{ color: ' . esc_attr( $atts['ppcount_color'] ) . '; }';
}

if ( $responsive_spacing ) {
	$css_custom .= penci_extract_spacing_style( $block_id_css, $responsive_spacing );
}

if ( $css_custom ) {
	echo '<style>';
	echo $css_custom;
	echo '</style>';
}
?>
