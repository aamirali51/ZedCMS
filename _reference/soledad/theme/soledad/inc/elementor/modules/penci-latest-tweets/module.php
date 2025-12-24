<?php
namespace PenciSoledadElementor\Modules\PenciLatestTweets;

use PenciSoledadElementor\Base\Module_Base;

class Module extends Module_Base {

	public function get_name() {
		return 'penci-latest-tweets';
	}

	public function get_widgets() {
		return array( 'PenciLatestTweets' );
	}
}
