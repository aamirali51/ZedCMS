<?php

namespace PenciSoledadElementor\Modules\PenciVisibilityControls\Conditions;

use DateTime;
use PenciSoledadElementor\Base\Condition;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Day extends Condition {

	
	public function get_name() {
		return 'day';
	}

	
	public function get_title() {
		return esc_html__( 'Day of Week', 'soledad' );
	}

	
	public function get_group() {
		return 'date_time';
	}

	/**
	 * Get the browser
	 * @return array of different days of week
	 * @since  8.5.7
	 */
	public function get_control_value() {
		return [
			'label'       => esc_html__( 'Day(s)', 'soledad' ),
			'type'        => Controls_Manager::SELECT2,
			'options'     => [
				'0' => esc_html__( 'Sunday', 'soledad' ),
				'1' => esc_html__( 'Monday', 'soledad' ),
				'2' => esc_html__( 'Tuesday', 'soledad' ),
				'3' => esc_html__( 'Wednesday', 'soledad' ),
				'4' => esc_html__( 'Thursday', 'soledad' ),
				'5' => esc_html__( 'Friday', 'soledad' ),
				'6' => esc_html__( 'Saturday', 'soledad' ),
			],
			'multiple'    => true,
			'label_block' => true,
			'default'     => '1',
		];
	}

	
	public function check( $relation, $val ) {

		$day = date( 'w', current_time( 'timestamp' ) );

		$show = is_array( $val ) && ! empty( $val ) ? in_array( $day, $val ) : $val === $day;

		return self::compare( $show, true, $relation );
	}
}
