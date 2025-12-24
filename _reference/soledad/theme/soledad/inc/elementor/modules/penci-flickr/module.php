<?php
namespace PenciSoledadElementor\Modules\PenciFlickr;

use PenciSoledadElementor\Base\Module_Base;

class Module extends Module_Base {

	public function get_name() {
		return 'penci-flickr';
	}

	public function get_widgets() {
		return array( 'PenciFlickr' );
	}
}
