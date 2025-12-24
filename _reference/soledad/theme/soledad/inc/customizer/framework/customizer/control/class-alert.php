<?php
/**
 * Customizer Control: Alert
 *
 * @author PenciDesign
 * @since 1.0.0
 * @package soledad
 */

namespace SoledadFW\Customizer\Control;

/**
 * Create a simple number control
 */
class Alert extends Control_Abstract {

	/**
	 * The control type.
	 *
	 * @access public
	 * @var string
	 */
	public $type = 'soledad-fw-alert';

	public function to_json() {
		parent::to_json();

		if ( isset( $this->default ) ) {
			$this->json['default'] = $this->default;
		} else {
			$this->json['default'] = $this->setting->default;
		}
	}


	/**
	 * An Underscore (JS) template for this control's content (but not its container).
	 *
	 * Class variables for this control class are available in the `data` JS object;
	 * export custom variables by overriding
	 *
	 * @see WP_Customize_Control::print_template()
	 *
	 * @access protected
	 */
	protected function content_template() {
		?>
        <div class="customize-alert customize-alert-{{{ data.default }}}">
            <label>
                <# if ( data.label ) { #>
                <strong class="customize-control-title">{{{ data.label }}}</strong>
                <# } #>
                <# if ( data.description ) { #>
                <div class="description customize-control-description">{{{ data.description }}}</div>
                <# } #>
            </label>
        </div>
		<?php
	}
}
