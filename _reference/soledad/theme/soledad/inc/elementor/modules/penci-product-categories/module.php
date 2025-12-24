<?php

namespace PenciSoledadElementor\Modules\PenciProductCategories;

use PenciSoledadElementor\Base\Module_Base;

class Module extends Module_Base {

	public function get_name() {
		return 'penci_product_categories';
	}

	public function get_widgets() {
		return array( 'PenciProductCategories' );
	}
}
