<?php
namespace PenciSoledadElementor\Modules\PenciTestimonials;

use PenciSoledadElementor\Base\Module_Base;

class Module extends Module_Base {

	public function get_name() {
		return 'penci-testimonials';
	}

	public function get_widgets() {
		return array( 'PenciTestimonials' );
	}
}
