<?php
$settings   = vc_map_get_attributes( $this->getShortcode(), $atts );
$term_style = $settings['term_style'];
$taxonomies = $settings['taxonomies'];
$queries    = get_queried_object();
$css_class  = vc_shortcode_custom_css_class( $settings['css'] );
$class      = isset( $settings['term_align'] ) && $settings['term_align'] ? 'pctax' . $settings['term_align'] : 'pctaxleft';
$count      = '';
if ( ! isset( $queries->taxonomy ) || ( $settings['tax_showall'] == 'custom' && empty( $taxonomies ) ) ) {
	return false;
}
$settings['tax_showcount'] = $settings['tax_showcount'] == 'yes';
$block_id                  = Penci_Vc_Helper::get_unique_id_block( 'pc_archive_taxonomy' );
$args                      = [
	'taxonomy' => $queries->taxonomy,
	'orderby'  => $settings['orderby'],
	'order'    => $settings['order'],
];

if ( $settings['taxonomies_ex'] ) {
	$args['exclude'] = $settings['taxonomies_ex'];
}

if ( $settings['number'] && 'rand' != $settings['orderby'] ) {
	$args['number'] = $settings['number'];
}

if ( $settings['tax_showall'] == 'child' ) {
	$args['child_of'] = $queries->term_id;
}

$term_data    = get_queried_object();
$current_term = ! empty( $term_data ) && isset( $term_data->term_id ) && $term_data->term_id ? $term_data->term_id : '';

$terms = get_terms( $args );
if ( $terms && $settings['tax_showall'] !== 'custom' ) {

	if ( 'rand' == $settings['orderby'] ) {
		shuffle( $terms );
	}

	?>
    <div id="<?php echo $block_id;?>" class="pcab-txnm pc-tax-lists <?php echo $css_class; ?>">
        <ul class="pctmp-term-list term-style-<?php echo esc_attr( $term_style . ' ' . $class ); ?>">
			<?php
			$t_count = 0;
			foreach ( $terms as $term ) {
				$class = '';
				if ( $t_count ++ == $settings['number'] && 'rand' == $settings['orderby'] ) {
					break;
				}
				if ( $settings['tax_showcount'] ) {
					$count = '<span class="count">(' . $term->count . ')</span>';
				}
				if ( $term->term_id == $current_term && 'yes' == $settings['tax_currentitem'] ) {
					$class = ' current-item';
				}
				echo '<li><a class="pctmp-term-item' . $class . '" href="' . get_term_link( $term->term_id ) . '">' . esc_html( $term->name ) . $count . '</a></li>';
			} ?>
        </ul>
    </div>
	<?php
} elseif ( $settings['tax_showall'] == 'custom' && ! empty( $taxonomies ) ) {
	?>
    <div class="pc-tax-lists <?php echo $css_class; ?>">
        <ul class="pctmp-term-list term-style-<?php echo esc_attr( $term_style . ' ' . $class ); ?>">
			<?php foreach ( $taxonomies as $term_id ) {
				$class = '';
				$term  = get_term( $term_id );
				if ( $settings['tax_showcount'] ) {
					$count = '<span class="count">(' . $term->count . ')</span>';
				}
				if ( $term->term_id == $current_term && 'yes' == $settings['tax_currentitem'] ) {
					$class = ' current-item';
				}
				echo '<li><a class="pctmp-term-item' . $class . '" href="' . get_term_link( $term->term_id ) . '">' . esc_html( $term->name ) . $count . '</a></li>';
			} ?>
        </ul>
    </div>
	<?php
}
$css_custom = '';
$block_id = '#'.$block_id;
if ( $settings['term_spacing'] ) {
	$css_custom .= $block_id . ' .pctmp-term-list.pctaxleft li:not(:last-child){margin-right:' . $settings['term_spacing'] . 'px}';
	$css_custom .= $block_id . ' .pctmp-term-list.pctaxright li:not(:first-child){margin-left:' . $settings['term_spacing'] . 'px}';
	$css_custom .= $block_id . ' .pctmp-term-list.pctaxcenter li(:first-child){margin-left:calc(' . $settings['term_spacing'] . 'px/2);margin-right:calc(' . $settings['term_spacing'] . 'px/2)}';
	$css_custom .= $block_id . ' .pctmp-term-list li .pctmp-term-item{margin-right:0;margin-left:0;--pctmp-term-list:' . $settings['term_spacing'] . 'px}';
}
if ( $settings['term_vspacing'] ) {
	$css_custom .= $block_id . ' .pctmp-term-list li .pctmp-term-item{margin-bottom:' . $settings['term_vspacing'] . '}';
}
if ( $settings['term_color'] ) {
	$css_custom .= $block_id . ' .pctmp-term-item{color:' . $settings['text_lcolor'] . '}';
}
if ( $settings['term_hcolor'] ) {
	$css_custom .= $block_id . ' a.pctmp-term-item:hover,' . $block_id . ' a.pctmp-term-item.current-item{color:' . $settings['term_hcolor'] . '}';
}
if ( $settings['term_bgcolor'] ) {
	$css_custom .= $block_id . ' .pctmp-term-item{background-color:' . $settings['term_bgcolor'] . '}';
}
if ( $settings['term_bghcolor'] ) {
	$css_custom .= $block_id . ' a.pctmp-term-item:hover,' . $block_id . ' a.pctmp-term-item.current-item{background-color:' . $settings['term_bghcolor'] . '}';
}
if ( $settings['term_bdcolor'] ) {
	$css_custom .= $block_id . ' .pctmp-term-item{border-color:' . $settings['term_bdcolor'] . '}';
}
if ( $settings['term_bdhcolor'] ) {
	$css_custom .= $block_id . ' a.pctmp-term-item:hover,' . $block_id . ' a.pctmp-term-item.current-item{border-color:' . $settings['term_bdhcolor'] . '}';
}
if ( $settings['term_border_style'] ) {
	$css_custom .= $block_id . ' .pctmp-term-item{border-style:' . $settings['term_border_style'] . '}';
}
if ( $settings['term_border_radius'] ) {
	$css_custom .= $block_id . ' .pctmp-term-item{border-radius:' . $settings['term_border_radius'] . 'px}';
}
if ( $settings['responsive_spacing'] ) {
	$css_custom .= penci_extract_spacing_style( $block_id, $settings['responsive_spacing'] );
}
if ( $css_custom ) {
	echo '<style>';
	echo $css_custom;
	echo '</style>';
}
