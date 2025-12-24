<?php
namespace PenciSoledadElementor\Modules\PenciOpenHour;

use PenciSoledadElementor\Base\Module_Base;

class Module extends Module_Base {

	public function get_name() {
		return 'penci-open-hour';
	}

	public function get_widgets() {
		return array( 'PenciOpenHour' );
	}
}
