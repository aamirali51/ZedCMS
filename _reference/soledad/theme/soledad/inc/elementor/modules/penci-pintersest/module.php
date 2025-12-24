<?php
namespace PenciSoledadElementor\Modules\PenciPintersest;

use PenciSoledadElementor\Base\Module_Base;

class Module extends Module_Base {

	public function get_name() {
		return 'penci-pintersest';
	}

	public function get_widgets() {
		return array( 'PenciPintersest' );
	}
}
