<?php

namespace PenciSoledadElementor\Modules\PenciProductHotspot;

use PenciSoledadElementor\Base\Module_Base;

class Module extends Module_Base {

	public function get_name() {
		return 'penci_product_hotspot';
	}

	public function get_widgets() {
		return array( 'PenciProductHotspot' );
	}
}
