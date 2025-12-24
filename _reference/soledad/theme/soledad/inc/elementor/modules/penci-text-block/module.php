<?php
namespace PenciSoledadElementor\Modules\PenciTextBlock;

use PenciSoledadElementor\Base\Module_Base;

class Module extends Module_Base {

	public function get_name() {
		return 'penci-text-block';
	}

	public function get_widgets() {
		return array( 'PenciTextBlock' );
	}
}
