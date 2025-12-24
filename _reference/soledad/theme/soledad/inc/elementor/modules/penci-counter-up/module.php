<?php
namespace PenciSoledadElementor\Modules\PenciCounterUp;

use PenciSoledadElementor\Base\Module_Base;

class Module extends Module_Base {

	public function get_name() {
		return 'penci-counter-up';
	}

	public function get_widgets() {
		return array( 'PenciCounterUp' );
	}
}
