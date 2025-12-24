<?php
namespace PenciSoledadElementor\Modules\PenciCategoryListing;

use PenciSoledadElementor\Base\Module_Base;

class Module extends Module_Base {

	public function get_name() {
		return 'penci-category-listing';
	}

	public function get_widgets() {
		return array( 'PenciCategoryListing' );
	}
}
