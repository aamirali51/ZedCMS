<?php
namespace PenciSoledadElementor\Modules\PenciSidebar;

use PenciSoledadElementor\Base\Module_Base;

class Module extends Module_Base {

	public function get_name() {
		return 'penci-sidebar';
	}

	public function get_widgets() {
		return array( 'PenciSidebar' );
	}
}
