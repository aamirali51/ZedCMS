<?php

namespace PenciSoledadElementor\Modules\PenciProductFilter;

use PenciSoledadElementor\Base\Module_Base;

class Module extends Module_Base {

	public function get_name() {
		return 'penci_product_filters';
	}

	public function get_widgets() {
		return array( 'PenciProductFilter' );
	}
}
