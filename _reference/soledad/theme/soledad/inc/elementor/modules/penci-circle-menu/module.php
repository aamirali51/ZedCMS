<?php
namespace PenciSoledadElementor\Modules\PenciCircleMenu;

use PenciSoledadElementor\Base\Module_Base;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Module extends Module_Base {

	public function get_name() {
		return 'penci-circle-menu';
	}

	public function get_widgets() {

		return ['PenciCircleMenu'];
	}
}
