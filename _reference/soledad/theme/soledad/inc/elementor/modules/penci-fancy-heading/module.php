<?php
namespace PenciSoledadElementor\Modules\PenciFancyHeading;

use PenciSoledadElementor\Base\Module_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}


class Module extends Module_Base {

	public function get_name() {
		return 'penci-fancy-heading';
	}

	public function get_widgets() {
		return array( 'PenciFancyHeading' );
	}
}
