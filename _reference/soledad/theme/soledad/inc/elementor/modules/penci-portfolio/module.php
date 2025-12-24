<?php
namespace PenciSoledadElementor\Modules\PenciPortfolio;

use PenciSoledadElementor\Base\Module_Base;

class Module extends Module_Base {

	public function get_name() {
		return 'penci-portfolio';
	}

	public function get_widgets() {
		return array( 'PenciPortfolio' );
	}

	public static function is_active() {
		return class_exists( 'Penci_Portfolio' );
	}
}
