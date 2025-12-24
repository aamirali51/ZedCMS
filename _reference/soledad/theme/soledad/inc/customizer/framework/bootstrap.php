<?php
defined( 'PENCI_FW' ) || define( 'PENCI_FW', 'soledad' );
defined( 'PENCI_FW_VERSION' ) || define( 'PENCI_FW_VERSION', '10.0.0' );
defined( 'PENCI_FW_URL' ) || define( 'PENCI_FW_URL', PENCI_SOLEDAD_URL );
defined( 'PENCI_FW_FILE' ) || define( 'PENCI_FW_FILE', __FILE__ );
defined( 'PENCI_FW_DIR' ) || define( 'PENCI_FW_DIR', PENCI_SOLEDAD_URL );
defined( 'PENCI_THEME_URL' ) || define( 'PENCI_THEME_URL', PENCI_FW_URL );

// Need to define PENCI_URL on plugin / Themes.
defined( 'PENCI_URL' ) || define( 'PENCI_URL', PENCI_THEME_URL . '/inc/customizer/framework' );
defined( 'PENCI_VERSION' ) || define( 'PENCI_VERSION', '1.2.6' );
defined( 'PENCI_DIR' ) || define( 'PENCI_DIR', dirname( __FILE__ ) );
defined( 'PENCI_CLASSPATH' ) || define( 'PENCI_CLASSPATH', PENCI_DIR );

require_once 'autoload.php';
require_once PENCI_SOLEDAD_DIR . '/inc/customizer/config/autoload.php';
require_once PENCI_SOLEDAD_DIR . '/inc/customizer/config/classes/class-customizer.php';
require_once PENCI_SOLEDAD_DIR . '/inc/customizer/config/options/init.php';
require_once PENCI_SOLEDAD_DIR . '/inc/customizer/config/settings.php';
\SoledadFW\Customizer::getInstance();

add_action( 'init', 'soledad_fw_initialize_customizer' );

/**
 * Initialize Customizer
 */
if ( ! function_exists( 'soledad_fw_initialize_customizer' ) ) {
	function soledad_fw_initialize_customizer() {
		// Instantiate Customizer.
		SoledadFW\Customizer\Customizer::get_instance();
	}
}
