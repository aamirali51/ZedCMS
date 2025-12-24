<?php

namespace PenciSoledadElementor\Modules\PenciSimpleList;

use PenciSoledadElementor\Base\Module_Base;

class Module extends Module_Base {

	public function get_name() {
		return 'penci-simple-list';
	}

	public function get_widgets() {
		return array( 'PenciSimpleList' );
	}
}
