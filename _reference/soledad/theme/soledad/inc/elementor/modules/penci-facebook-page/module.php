<?php
namespace PenciSoledadElementor\Modules\PenciFacebookPage;

use PenciSoledadElementor\Base\Module_Base;

class Module extends Module_Base {

	public function get_name() {
		return 'penci-facebook-page';
	}

	public function get_widgets() {
		return array( 'PenciFacebookPage' );
	}
}
