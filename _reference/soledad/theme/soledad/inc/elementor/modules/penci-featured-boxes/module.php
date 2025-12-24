<?php
namespace PenciSoledadElementor\Modules\PenciFeaturedBoxes;

use PenciSoledadElementor\Base\Module_Base;

class Module extends Module_Base {

	public function get_name() {
		return 'penci-featured-boxes';
	}

	public function get_widgets() {
		return array( 'PenciFeaturedBoxes' );
	}
}
