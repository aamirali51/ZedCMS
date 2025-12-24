<?php
namespace PenciSoledadElementor\Modules\PenciProgressBar;

use PenciSoledadElementor\Base\Module_Base;

class Module extends Module_Base {

	public function get_name() {
		return 'penci-progress-bar';
	}

	public function get_widgets() {
		return array( 'PenciProgressBar' );
	}
}
