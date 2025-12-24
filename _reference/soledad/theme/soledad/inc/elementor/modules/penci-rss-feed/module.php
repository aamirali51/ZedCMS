<?php
namespace PenciSoledadElementor\Modules\PenciRssFeed;

use PenciSoledadElementor\Base\Module_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}


class Module extends Module_Base {

	public function get_name() {
		return 'penci-rss-feed';
	}

	public function get_widgets() {
		return array( 'PenciRssFeed' );
	}
}
