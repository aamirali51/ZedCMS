<?php

namespace PenciSoledadElementor\Modules\PenciProductBrand;

use PenciSoledadElementor\Base\Module_Base;

class Module extends Module_Base {

	public function get_name() {
		return 'penci_product_brand';
	}

	public function get_widgets() {
		return array( 'PenciProductBrand' );
	}
}
