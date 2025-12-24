<?php
/**
 * Customizer Control: password.
 *
 * Creates a password
 *
 * @author PenciDesign
 * @since 1.0.0
 * @package soledad
 */

namespace SoledadFW\Customizer\Control;

/**
 * Password control.
 */
class Password extends Text {

	/**
	 * The control type.
	 *
	 * @access public
	 * @var string
	 */
	public $type = 'soledad-fw-password';

	/**
	 * The input type.
	 *
	 * @access public
	 * @var string
	 */
	public $input_type = 'password';
}
