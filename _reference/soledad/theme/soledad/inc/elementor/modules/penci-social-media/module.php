<?php
namespace PenciSoledadElementor\Modules\PenciSocialMedia;

use PenciSoledadElementor\Base\Module_Base;

class Module extends Module_Base {

	public function get_name() {
		return 'penci-social-media';
	}

	public function get_widgets() {
		return array( 'PenciSocialMedia' );
	}
}
