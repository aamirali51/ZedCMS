<?php
namespace PenciSoledadElementor\Modules\PenciBlockHeadingTitle;

use PenciSoledadElementor\Base\Module_Base;

class Module extends Module_Base {

	public function get_name() {
		return 'penci-block-heading-title';
	}

	public function get_widgets() {
		return array( 'PenciBlockHeadingTitle' );
	}
}
