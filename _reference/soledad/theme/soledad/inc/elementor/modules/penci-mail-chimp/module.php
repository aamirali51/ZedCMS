<?php
namespace PenciSoledadElementor\Modules\PenciMailChimp;

use PenciSoledadElementor\Base\Module_Base;

class Module extends Module_Base {

	public function get_name() {
		return 'penci-mail-chimp';
	}

	public function get_widgets() {
		return array( 'PenciMailChimp' );
	}
}
