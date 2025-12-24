<?php

namespace PenciSoledadElementor\Modules\PenciStylistedArticlesCount;

use PenciSoledadElementor\Base\Module_Base;

class Module extends Module_Base {

	public function get_name() {
		return 'penci-stylisted-articles-count';
	}

	public function get_widgets() {
		return array( 'PenciStylistedArticlesCount' );
	}
}
