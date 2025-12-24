<?php

namespace PenciSoledadElementor\Modules\PenciVisibilityControls\Conditions;

use PenciSoledadElementor\Base\Condition;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Role extends Condition {

	
	public function get_name() {
		return 'role';
	}

	
	public function get_title() {
		return esc_html__( 'User Role', 'soledad' );
	}

	
	public function get_group() {
		return 'user';
	}

	
	public function get_control_value() {
		global $wp_roles;

		return [
			'type'        => Controls_Manager::SELECT,
			'description' => esc_html__( 'Warning: This condition applies only to logged in visitors.', 'soledad' ),
			'default'     => 'subscriber',
			'label_block' => true,
			'options'     => $wp_roles->get_names(),
		];
	}

	
	public function check( $relation, $val ) {
		$user_role = wp_get_current_user()->roles;

		return $this->compare( is_user_logged_in() && in_array( $val, $user_role ), true, $relation );
	}
}
