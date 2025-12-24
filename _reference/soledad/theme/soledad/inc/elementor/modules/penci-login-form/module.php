<?php
namespace PenciSoledadElementor\Modules\PenciLoginForm;

use PenciSoledadElementor\Base\Module_Base;

class Module extends Module_Base {

	public function get_name() {
		return 'penci-login-form';
	}

	public function get_widgets() {
		return array( 'PenciLoginForm' );
	}
}
