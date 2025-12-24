<?php
namespace PenciSoledadElementor\Modules\PenciTableOfContents;

use PenciSoledadElementor\Base\Module_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}


class Module extends Module_Base {

	public function get_name() {
		return 'penci-table-of-contents';
	}

	public function get_widgets() {
		return array( 'PenciTableOfContents' );
	}
}
