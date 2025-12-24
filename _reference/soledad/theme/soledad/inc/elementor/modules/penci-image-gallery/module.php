<?php
namespace PenciSoledadElementor\Modules\PenciImageGallery;

use PenciSoledadElementor\Base\Module_Base;

class Module extends Module_Base {

	public function get_name() {
		return 'penci-image-gallery';
	}

	public function get_widgets() {
		return array( 'PenciImageGallery' );
	}
}
