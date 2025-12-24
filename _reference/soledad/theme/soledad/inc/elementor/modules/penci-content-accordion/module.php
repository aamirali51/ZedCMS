<?php
namespace PenciSoledadElementor\Modules\PenciContentAccordion;

use PenciSoledadElementor\Base\Module_Base;

class Module extends Module_Base {

	public function get_name() {
		return 'penci-content-accordion';
	}

	public function get_widgets() {
		return array( 'PenciContentAccordion' );
	}
}
