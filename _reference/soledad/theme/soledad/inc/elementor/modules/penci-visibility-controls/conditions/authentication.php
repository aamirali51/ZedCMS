<?php

namespace PenciSoledadElementor\Modules\PenciVisibilityControls\Conditions;

use PenciSoledadElementor\Base\Condition;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Authentication extends Condition {

	
	public function get_name() {
		return 'authentication';
	}

	
	public function get_title() {
		return esc_html__( 'Login Status', 'soledad' );
	}

	
	public function get_group() {
		return 'user';
	}

	
	public function get_control_value() {
		return [
			'type'        => Controls_Manager::SELECT,
			'default'     => 'authenticated',
			'label_block' => true,
			'options'     => [
				'authenticated' => esc_html__( 'Logged in', 'soledad' ),
			],
		];
	}

	
	public function check( $relation, $val ) {
		return $this->compare( is_user_logged_in(), true, $relation );
	}
}
