<?php
namespace PenciSoledadElementor\Modules\PenciFeaturedCat;

use PenciSoledadElementor\Base\Module_Base;

class Module extends Module_Base {

	public function get_name() {
		return 'penci-featured-cat';
	}

	public function get_widgets() {
		return array( 'PenciFeaturedCat' );
	}
}
