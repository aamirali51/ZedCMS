<?php
namespace PenciSoledadElementor\Modules\PenciPostsSlider;

use PenciSoledadElementor\Base\Module_Base;

class Module extends Module_Base {

	public function get_name() {
		return 'penci-posts-slider';
	}

	public function get_widgets() {
		return array( 'PenciPostsSlider' );
	}
}
