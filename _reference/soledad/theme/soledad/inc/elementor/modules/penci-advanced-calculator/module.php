<?php
namespace PenciSoledadElementor\Modules\PenciAdvancedCalculator;

use PenciSoledadElementor\Base\Module_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}


class Module extends Module_Base {

	public function get_name() {
		return 'penci-advanced-calculator';
	}

	public function get_widgets() {
		return array( 'PenciAdvancedCalculator' );
	}
}
