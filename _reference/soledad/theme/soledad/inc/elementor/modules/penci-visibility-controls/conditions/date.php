<?php

namespace PenciSoledadElementor\Modules\PenciVisibilityControls\Conditions;

use DateTime;
use PenciSoledadElementor\Base\Condition;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Date extends Condition {

	
	public function get_name() {
		return 'date';
	}

	
	public function get_title() {
		return esc_html__( 'Date Range', 'soledad' );
	}

	
	public function get_group() {
		return 'date_time';
	}

	
	public function get_control_value() {
		$default_date_start = date( 'Y-m-d', strtotime( '-3 day' ) + ( get_option( 'gmt_offset' ) * HOUR_IN_SECONDS ) );
		$default_date_end   = date( 'Y-m-d', strtotime( '+3 day' ) + ( get_option( 'gmt_offset' ) * HOUR_IN_SECONDS ) );
		$default_interval   = $default_date_start . ' to ' . $default_date_end;

		return [
			'label'          => esc_html__( 'In interval', 'soledad' ),
			'type'           => Controls_Manager::DATE_TIME,
			'dynamic'        => [ 'active' => true ],
			'picker_options' => [
				'enableTime' => false,
				'mode'       => 'range',
			],
			'label_block'    => true,
			'default'        => $default_interval,
		];
	}

	
	public function check( $relation, $val ) {

		if ( ! $val ) {
			return;
		}

		$range = explode( 'to', preg_replace( '/\s+/', '', $val ) );

		if ( ! is_array( $range ) || 2 !== count( $range ) ) {
			return;
		}

		$start_date = $range[0];
		$end_date   = $range[1];
		$today      = date( 'Y-m-d' );

		if ( DateTime::createFromFormat( 'Y-m-d', $start_date ) === false || // Make sure it's a date
		     DateTime::createFromFormat( 'Y-m-d', $end_date ) === false ) // Make sure it's a date
		{
			return;
		}

		$start_date = strtotime( $start_date ) + ( get_option( 'gmt_offset' ) * HOUR_IN_SECONDS );
		$end_date   = strtotime( $end_date ) + ( get_option( 'gmt_offset' ) * HOUR_IN_SECONDS );
		$today      = strtotime( $today ) + ( get_option( 'gmt_offset' ) * HOUR_IN_SECONDS );

		$show = ( ( $today >= $start_date ) && ( $today <= $end_date ) );

		return $this->compare( $show, true, $relation );
	}
}
