<?php

namespace PenciSoledadElementor\Modules\PenciVisibilityControls\Conditions;

use PenciSoledadElementor\Base\Condition;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Date_Time_Before extends Condition {

	
	public function get_name() {
		return 'date_time_before';
	}

	
	public function get_title() {
		return esc_html__( 'Till Date (Server Time)', 'soledad' );
	}

	
	public function get_group() {
		return 'date_time';
	}

	
	public function get_control_value() {
		$default_date = date( 'Y-m-d', strtotime( '+3 day' ) + ( get_option( 'gmt_offset' ) * HOUR_IN_SECONDS ) );

		return [
			'label'          => esc_html__( 'Before', 'soledad' ),
			'type'           => Controls_Manager::DATE_TIME,
			'picker_options' => [
				'enableTime' => false,
			],
			'label_block'    => true,
			'default'        => $default_date,
		];
	}

	
	public function check( $relation, $val ) {
		// Check vars
		if ( ! $val ) { // Make sure it's a date
			return;
		}

		// Get timestamps for comparison
		$date_ts = strtotime( $val );
		$today   = current_time( 'timestamp' );

		// Check that today is before specified date
		$show = $today <= $date_ts;

		return $this->compare( $show, true, $relation );
	}
}
