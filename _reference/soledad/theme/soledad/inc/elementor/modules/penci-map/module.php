<?php
namespace PenciSoledadElementor\Modules\PenciMap;

use PenciSoledadElementor\Base\Module_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}


class Module extends Module_Base {

	public function get_name() {
		return 'penci-map';
	}

	public function get_widgets() {
		return array( 'PenciMap' );
	}
}
