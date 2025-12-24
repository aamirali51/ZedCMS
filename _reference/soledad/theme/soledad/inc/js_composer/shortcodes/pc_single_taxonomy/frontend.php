<?php
$settings    = vc_map_get_attributes( $this->getShortcode(), $atts );
$block_id    = Penci_Vc_Helper::get_unique_id_block( 'pc_single_taxonomy' );
$css_custom  = '';
$term_name   = $settings['term_name'];
$term_text   = $settings['term_text'];
$label_text  = $settings['label_text'];
$term_link   = $settings['term_link'];
$terms       = wp_get_post_terms( get_the_ID(), $term_name );
$term_style  = $settings['term_style'];
$label_style = $settings['label_style'];
$css_class   = vc_shortcode_custom_css_class( $settings['css'] );
if ( $terms ) {
	?>
    <div id="<?php echo $block_id; ?>"
         class="pctmp-term-list term-style-<?php echo esc_attr( $term_style . ' ' . $css_class ); ?> label-style-<?php echo esc_attr( $label_style ); ?>">
		<?php if ( $term_text && $label_text ) : ?>
            <span class="term-labels">
                <?php echo $label_text; ?>
            </span>
		<?php endif;
		$terms       = wp_get_post_terms( get_the_ID(), $term_name );
		$i           = 0;
		$total_items = count( $terms );
		foreach ( $terms as $term ) {
			if ( $term_link ) {
				echo '<span class="pctmp-term-item">' . $term->name . '</span>';
			} else {
				echo '<a href="' . get_term_link( $term ) . '" class="pctmp-term-item">' . $term->name . '</a>';
			}
		}

		?>
    </div>
	<?php
}
$css = [
	'tax_align'          => [ '{{WRAPPER}} .pctmp-term-list' => 'text-align:{{VALUE}}' ],
	'term_color'         => [ '{{WRAPPER}} .pctmp-term-item' => 'color:{{VALUE}}' ],
	'term_hcolor'        => [ '{{WRAPPER}} a.pctmp-term-item:hover' => 'color:{{VALUE}}' ],
	'term_bgcolor'       => [ '{{WRAPPER}} .pctmp-term-item' => 'background-color:{{VALUE}}' ],
	'term_bghcolor'      => [ '{{WRAPPER}} a.pctmp-term-item:hover' => 'background-color:{{VALUE}}' ],
	'term_bcolor'        => [ '{{WRAPPER}} .pctmp-term-item' => 'border-color:{{VALUE}}' ],
	'term_bhcolor'       => [ '{{WRAPPER}} a.pctmp-term-item:hover' => 'border-color:{{VALUE}}' ],
	'term_border_radius' => [ '{{WRAPPER}} .pctmp-term-item' => 'border-radius: {{VALUE}}px;' ],
];
penci_wpbakery_el_extract_css( $css, $settings, '#' . $block_id );
