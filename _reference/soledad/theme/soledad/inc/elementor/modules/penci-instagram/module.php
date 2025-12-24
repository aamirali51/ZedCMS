<?php
namespace PenciSoledadElementor\Modules\PenciInstagram;

use PenciSoledadElementor\Base\Module_Base;

class Module extends Module_Base {

	public function get_name() {
		return 'penci-instagram';
	}

	public function get_widgets() {
		return array( 'PenciInstagram' );
	}
}
