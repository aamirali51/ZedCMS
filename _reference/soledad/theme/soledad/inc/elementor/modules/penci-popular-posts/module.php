<?php
namespace PenciSoledadElementor\Modules\PenciPopularPosts;

use PenciSoledadElementor\Base\Module_Base;

class Module extends Module_Base {

	public function get_name() {
		return 'penci-popular-posts';
	}

	public function get_widgets() {
		return array( 'PenciPopularPosts' );
	}
}
