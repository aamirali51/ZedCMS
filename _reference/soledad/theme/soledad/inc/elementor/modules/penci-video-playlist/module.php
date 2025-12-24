<?php
namespace PenciSoledadElementor\Modules\PenciVideoPlaylist;

use PenciSoledadElementor\Base\Module_Base;

class Module extends Module_Base {

	public function get_name() {
		return 'penci-video-playlist';
	}

	public function get_widgets() {
		return array( 'PenciVideoPlaylist' );
	}
}
