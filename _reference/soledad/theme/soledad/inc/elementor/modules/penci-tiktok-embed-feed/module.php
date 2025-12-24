<?php

namespace PenciSoledadElementor\Modules\PenciTiktokEmbedFeed;

use PenciSoledadElementor\Base\Module_Base;

class Module extends Module_Base {

	public function get_name() {
		return 'penci-tiktok-embed-feed';
	}

	public function get_widgets() {
		return array( 'PenciTiktokEmbedFeed' );
	}
}
