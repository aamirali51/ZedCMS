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
class MultiCheck extends Control_Abstract {

	/**
	 * The control type.
	 *
	 * @access public
	 * @var string
	 */
	public $type = 'soledad-fw-multi-check';

	public function to_json() {
		parent::to_json();
		$multi_values               = ! is_array( $this->value() ) ? json_encode( explode( ',', $this->value() ) ) : $this->value();
		$this->json['multi_values'] = $multi_values;
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
	protected function content_template() { ?>

        <# if ( data.label ) { #>
        <span class="customize-control-title">{{{ data.label }}}</span>
        <# } #>
        <# if ( data.description ) { #>
        <p class="description customize-control-description">{{{ data.description }}}</p>
        <# } #>
        <ul>
            <# _.each(data.choices,function(value,key){
            if (typeof data.value.split === 'function') {
                value_arr = data.value.split(",");
            } else {
                value_arr = '';
            }
            #>
            <li>
                <label>
                    <input type="checkbox" value="{{ key }}" <# if ( _.contains(_.toArray(value_arr),key) ) { #>checked<# } #>/>
                    {{ value.name}}
                </label>
            </li>
            <# }) #>
        </ul>

        <input id="_customize-input-{{data.id}}" name="_customize-input-{{data.id}}"
               type="hidden" {{{ data.link }}}
               value="{{data.value}}"/>
		<?php
	}
}
