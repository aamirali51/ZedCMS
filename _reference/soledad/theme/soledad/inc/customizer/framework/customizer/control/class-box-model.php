<?php
/**
 * Customizer Control: Alert
 *
 * @author PenciDesign
 * @since 1.0.0
 * @package soledad
 */

namespace SoledadFW\Customizer\Control;

use WP_Customize_Setting;

/**
 * Create a simple number control
 */
class Box_Model extends Control_Abstract {
	/**
	 * Control type
	 *
	 * @var string
	 */
	public $type = 'soledad-fw-box-model';

	public function to_json() {
		parent::to_json();
		$label                      = [
			0  => 'Top',
			1  => 'Right',
			2  => 'Bottom',
			3  => 'Left',
			4  => 'Top',
			5  => 'Right',
			6  => 'Bottom',
			7  => 'Left',
			8  => 'Top',
			9  => 'Right',
			10 => 'Bottom',
			11 => 'Left',
			12 => 'Top',
			13 => 'Right',
			14 => 'Bottom',
			15 => 'Left',
		];
		$saved_values               = ( ! is_array( $this->value() ) && ! empty( $this->value() ) ) ? explode( ', ', $this->value() ) : explode( ', ', '\'\', \'\', \'\', \'\', \'\', \'\', \'\', \'\', \'\', \'\', \'\', \'\', \'\', \'\', \'\', \'\'' );
		$this->json['saved']        = $saved_values;
		$this->json['choices']      = $this->choices;
		$this->json['control_link'] = $this->get_control_link();
		$this->json['item_label']   = $label;
		$this->json['value']        = implode( '-', $saved_values );
	}

	public function get_control_link( $setting_key = 'default' ) {
		if ( isset( $this->settings[ $setting_key ] ) && $this->settings[ $setting_key ] instanceof WP_Customize_Setting ) {
			return 'data-customize-setting-link=' . esc_attr( $this->settings[ $setting_key ]->id );
		} else {
			return 'data-customize-setting-key-link=' . esc_attr( $setting_key );
		}
	}

	/**
	 * Control method
	 *
	 * @since 1.0.0
	 */
	public function content_template() {
		?>
        <# if ( data.label ) { #>
        <span class="customize-control-title">{{{ data.label }}}</span>
        <# } #>
        <# if ( data.description ) { #>
        <p class="description customize-control-description">{{{ data.description }}}</p>
        <# } #>
        <div class="box-model-wrapper">
            <# _.each(data.choices,function(eachItem,index){ #>
            <# if ( 'margin' === index ) { #>
            <div class="box-model box-model-margin">
                <h4 class="box-head">Margin</h4>
                <# margin_count = 0; #>
                <# _.each(eachItem,function(m_value,m_key){ #>
                <div class="box-spacing-item">
                    <span class="box-title">{{data.item_label[margin_count]}}</span>
                    <input type="number" placeholder="-" value="{{data.saved[margin_count]}}"
                           class="box-model-field {{m_key}}">
                </div>
                <# margin_count++; #>
                <# }) #>
            </div>
            <# } #>
            <# if ( 'padding' === index ) { #>
            <div class="box-model box-model-padding">
                <h4 class="box-head">Padding</h4>
                <# padding_count = 4; #>
                <# _.each(eachItem,function(p_value,p_key){ #>
                <div class="box-spacing-item">
                    <span class="box-title">{{data.item_label[padding_count]}}</span>
                    <input type="number" placeholder="-" value="{{data.saved[padding_count]}}"
                           class="box-model-field {{p_key}}">
                </div>
                <# padding_count++; #>
                <# }) #>
            </div>
            <# } #>
            <# if ( 'border' === index ) { #>
            <div class="box-model box-model-border">
                <h4 class="box-head">Borders Width</h4>
                <# border_count = 8; #>
                <# _.each(eachItem,function(b_value,b_key){ #>
                <div class="box-spacing-item">
                    <span class="box-title">{{data.item_label[border_count]}}</span>
                    <input type="number" placeholder="-" value="{{data.saved[border_count]}}"
                           class="box-model-field {{b_key}}">
                </div>
                <# border_count++; #>
                <# }) #>
            </div>
            <# } #>
            <# if ( 'border-radius' === index ) { #>
            <div class="box-model box-model-border-radius">
                <h4 class="box-head">Borders Radius</h4>
                <# r_count = 12; #>
                <# _.each(eachItem,function(r_value,r_key){ #>
                <div class="box-spacing-item">
                    <span class="box-title">{{data.item_label[r_count]}}</span>
                    <input type="number" placeholder="-" value="{{data.saved[r_count]}}"
                           class="box-model-field {{r_key}}">
                </div>
                <# r_count++; #>
                <# }) #>
            </div>
            <# } #>
            <# }) #>
            <input type="hidden" class="box-model-saved" {{data.control_link}} value="{{data.value}}"/>
        </div>
		<?php
	}
}
