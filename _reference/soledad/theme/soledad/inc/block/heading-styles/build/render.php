<?php
/**
 * PHP file to use when rendering the block type on the server to show on the front end.
 *
 * The following variables are exposed to the file:
 *     $attributes (array): The block attributes.
 *     $content (string): The block default content.
 *     $block (WP_Block): The block instance.
 *
 * @see https://github.com/WordPress/gutenberg/blob/trunk/docs/reference-guides/block-api/block-metadata.md#render
 */
// Determine which content to display.
if ( isset( $attributes['headingText'] ) ) {

	$headingNewStyle = isset( $attributes['headingStyle'] ) ? $attributes['headingStyle'] : 'style-1';
	$heading_tag     = isset( $attributes['headingTag'] ) ? $attributes['headingTag'] : '1';
	$headingText     = $attributes['headingText'];
	$props           = get_block_wrapper_attributes();

	$style   = '';
	$accents = isset( $attributes['accentColor'] ) ? $attributes['accentColor'] : '';
	$border  = isset( $attributes['borderColor'] ) ? $attributes['borderColor'] : '';

	if ( $border ) {
		$style .= '--pcborder-cl:' . $border . ';';
	}

	if ( $accents ) {
		$style .= '--pcaccent-cl:' . $accents . ';';
	}

	echo "<div style='{$style}' class='pchead-e-block pchead-e-${headingNewStyle} heading${heading_tag}-${headingNewStyle}'><h${heading_tag} ${props}>${headingText}</h${heading_tag}></div>";
}
