<?php
namespace PenciSoledadElementor\Modules\PenciCustomCarousel;

use PenciSoledadElementor\Base\Module_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}


class Module extends Module_Base {

	public function get_name() {
		return 'penci-custom-carousel';
	}

	public function get_widgets() {
		return array( 'PenciCustomCarousel' );
	}
}
