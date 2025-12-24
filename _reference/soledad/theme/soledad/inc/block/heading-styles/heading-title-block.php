<?php
/**
 * Plugin Name:       Heading Title Blocks
 * Description:       Display heading title
 * Version:           0.1.0
 * Requires at least: 6.2
 * Requires PHP:      7.0
 * Author:            PenciDesign
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       soledad
 *
 * @package           soledad
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Registers the block using the metadata loaded from the `block.json` file.
 * Behind the scenes, it registers also all assets so they can be enqueued
 * through the block editor in the corresponding context.
 *
 * @see https://developer.wordpress.org/reference/functions/register_block_type/
 */
function penci_heading_block_styles() {
	register_block_type( __DIR__ . '/build' );
}
add_action( 'init', 'penci_heading_block_styles' );
