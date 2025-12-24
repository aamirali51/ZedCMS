<?php
namespace PenciSoledadElementor\Modules\PenciPopularCat;

use PenciSoledadElementor\Base\Module_Base;

class Module extends Module_Base {

	public function get_name() {
		return 'penci-popular-cat';
	}

	public function get_widgets() {
		return array( 'PenciPopularCat' );
	}
}
