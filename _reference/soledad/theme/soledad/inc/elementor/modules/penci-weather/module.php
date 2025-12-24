<?php
namespace PenciSoledadElementor\Modules\PenciWeather;

use PenciSoledadElementor\Base\Module_Base;

class Module extends Module_Base {

	public function get_name() {
		return 'penci-weather';
	}

	public function get_widgets() {
		return array( 'PenciWeather' );
	}
}
