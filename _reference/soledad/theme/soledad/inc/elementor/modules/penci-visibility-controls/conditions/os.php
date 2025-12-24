<?php

namespace PenciSoledadElementor\Modules\PenciVisibilityControls\Conditions;

use PenciSoledadElementor\Base\Condition;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Os extends Condition {

	
	public function get_name() {
		return 'os';
	}

	
	public function get_title() {
		return esc_html__( 'Operating System', 'soledad' );
	}

	
	public function get_group() {
		return 'system';
	}

	
	public function get_control_value() {
		return [
			'type'        => Controls_Manager::SELECT,
			'default'     => array_keys( $this->get_os_options() )[0],
			'label_block' => true,
			'options'     => $this->get_os_options(),
		];
	}

	/**
	 * Get the operating system
	 * @return array of different types of operating system
	 * @since  8.5.7
	 */
	protected function get_os_options() {
		return [
			'iphone'   => 'iPhone',
			'android'  => 'Android',
			'windows'  => 'Windows',
			'open_bsd' => 'OpenBSD',
			'sun_os'   => 'SunOS',
			'linux'    => 'Linux',
			'mac_os'   => 'Mac OS',
		];
	}

	
	public function check( $relation, $val ) {
		$oses = [
			'iphone'   => '(iPhone)',
			'android'  => '(Android)',
			'windows'  => 'Win16|(Windows 95)|(Win95)|(Windows_95)|(Windows 98)|(Win98)|(Windows NT 5.0)|(Windows 2000)|(Windows NT 5.1)|(Windows XP)|(Windows NT 5.2)|(Windows NT 6.0)|(Windows Vista)|(Windows NT 6.1)|(Windows 7)|(Windows NT 4.0)|(WinNT4.0)|(WinNT)|(Windows NT)|Windows ME',
			'open_bsd' => 'OpenBSD',
			'sun_os'   => 'SunOS',
			'linux'    => '(Linux)|(X11)',
			'mac_os'   => '(Mac_PowerPC)|(Macintosh)',
		];

		return $this->compare( preg_match( '@' . $oses[ $val ] . '@', $_SERVER['HTTP_USER_AGENT'] ), true, $relation );
	}
}
