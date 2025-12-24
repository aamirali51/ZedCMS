<?php
namespace PenciSoledadElementor\Modules\PenciNewsTicker;

use PenciSoledadElementor\Base\Module_Base;

class Module extends Module_Base {

	public function get_name() {
		return 'penci-news-ticker';
	}

	public function get_widgets() {
		return array( 'PenciNewsTicker' );
	}
}
