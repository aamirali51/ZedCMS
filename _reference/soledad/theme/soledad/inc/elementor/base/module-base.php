<?php
namespace PenciSoledadElementor\Base;

use Elementor\Core\Base\Module;
use PenciSoledadElementor\Loader;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

abstract class Module_Base extends Module {

	public function get_widgets() {
		return array();
	}

	public function __construct() {
		add_action( 'elementor/widgets/register', array( $this, 'init_widgets' ),0 );
	}

	public function init_widgets() {
		$widget_manager = Loader::elementor()->widgets_manager;

		foreach ( $this->get_widgets() as $widget ) {
			$class_name = $this->get_reflection()->getNamespaceName() . '\Widgets\\' . $widget;



			if ( class_exists( $class_name ) ) {
				$widget_manager->register( new $class_name() );
			}
		}
	}
}
