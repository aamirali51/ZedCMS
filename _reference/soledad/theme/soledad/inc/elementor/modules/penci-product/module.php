<?php

namespace PenciSoledadElementor\Modules\PenciProduct;

use PenciSoledadElementor\Base\Module_Base;

class Module extends Module_Base {

	public function get_name() {
		return 'penci_product';
	}

	public function get_widgets() {
		return array( 'PenciProduct' );
	}
}
