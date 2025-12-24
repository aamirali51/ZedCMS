<?php
namespace PenciSoledadElementor\Modules\PenciMediaCarousel;

use PenciSoledadElementor\Base\Module_Base;

class Module extends Module_Base {

	public function get_name() {
		return 'penci-media-carousel';
	}

	public function get_widgets() {
		return array( 'PenciMediaCarousel' );
	}
}
