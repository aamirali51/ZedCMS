<?php
namespace PenciSoledadElementor\Modules\PenciMultiTaxonomiesPostsFilter;

use PenciSoledadElementor\Base\Module_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}


class Module extends Module_Base {

	public function get_name() {
		return 'penci-multi-taxonomies-posts-filter';
	}

	public function get_widgets() {
		return array( 'PenciMultiTaxonomiesPostsFilter' );
	}
}
