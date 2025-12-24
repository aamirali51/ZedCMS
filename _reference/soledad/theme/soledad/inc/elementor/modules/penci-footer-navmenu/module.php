<?php

namespace PenciSoledadElementor\Modules\PenciFooterNavmenu;

use PenciSoledadElementor\Base\Module_Base;

class Module extends Module_Base {

	public function get_name() {
		return 'penci-footer-navmenu';
	}

	public function get_widgets() {
		return array( 'PenciFooterNavmenu' );
	}
}
