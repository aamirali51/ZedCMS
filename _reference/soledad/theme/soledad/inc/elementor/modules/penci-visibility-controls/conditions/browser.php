<?php

namespace PenciSoledadElementor\Modules\PenciVisibilityControls\Conditions;

use PenciSoledadElementor\Base\Condition;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Browser extends Condition {

	
	public function get_name() {
		return 'browser';
	}

	
	public function get_title() {
		return esc_html__( 'Browser', 'soledad' );
	}

	
	public function get_group() {
		return 'system';
	}

	
	public function get_control_value() {
		return [
			'type'        => Controls_Manager::SELECT,
			'default'     => array_keys( $this->get_browser_options() )[0],
			'label_block' => true,
			'options'     => $this->get_browser_options(),
		];
	}

	
	protected function get_browser_options() {
		return [
			'ie'      => 'Internet Explorer',
			'firefox' => 'Mozilla Firefox',
			'chrome'  => 'Google Chrome',
			'opera'   => 'Opera',
			'safari'  => 'Safari',
			'edge'    => 'Microsoft Edge',
		];
	}

	
	public function check( $relation, $val ) {
		$browsers = [
			'ie'      => [ 'MSIE', 'Trident', ],
			'firefox' => 'Firefox',
			'chrome'  => 'Chrome',
			'opera'   => 'OPR',
			'safari'  => 'Safari',
			'edge'    => [ 'Edg', 'Edge' ],
		];

		$show = false;

		if ( 'ie' === $val || 'edge' === $val ) {
			if ( false !== strpos( $_SERVER['HTTP_USER_AGENT'], $browsers[ $val ][0] ) || false !== strpos( $_SERVER['HTTP_USER_AGENT'], $browsers[ $val ][1] ) ) {
				$show = true;
			}
		} elseif ( 'opera' === $val ) {
			if ( false !== strpos( $_SERVER['HTTP_USER_AGENT'], 'OPR' ) ) {
				$show = true;
			}
		} elseif ( 'chrome' === $val ) {
			if ( false !== strpos( $_SERVER['HTTP_USER_AGENT'], 'Chrome' ) ) {
				$show = true;
				if ( false !== strpos( $_SERVER['HTTP_USER_AGENT'], 'OPR' ) ) {
					$show = false;
				}
				if ( false !== strpos( $_SERVER['HTTP_USER_AGENT'], 'Edg' ) ) {
					$show = false;
				}

			}
		} elseif ( 'firefox' === $val ) {
			if ( false !== strpos( $_SERVER['HTTP_USER_AGENT'], 'Firefox' ) ) {
				$show = true;
			}
		} elseif ( 'safari' === $val ) {
			if ( false !== strpos( $_SERVER['HTTP_USER_AGENT'], 'Safari' ) ) {
				$show = true;
				if ( false !== strpos( $_SERVER['HTTP_USER_AGENT'], 'Chrome' ) ) {
					$show = false;
				}
			}
		}

		return $this->compare( $show, true, $relation );
	}
}
