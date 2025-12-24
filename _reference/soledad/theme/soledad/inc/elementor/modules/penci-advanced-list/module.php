<?php

namespace PenciSoledadElementor\Modules\PenciAdvancedList;

use PenciSoledadElementor\Base\Module_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}


class Module extends Module_Base {

	public function get_name() {
		return 'penci-advanced-list';
	}

	public function get_widgets() {
		return array( 'PenciAdvancedList' );
	}
}
