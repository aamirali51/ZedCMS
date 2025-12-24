<?php
/**
 * Customizer Control: text.
 *
 * Creates a text
 *
 * @author PenciDesign
 * @since 1.0.0
 * @package soledad
 */

namespace SoledadFW\Customizer\Control;

/**
 * Slider control (range).
 */
class Button extends Control_Abstract {

	/**
	 * The control type.
	 *
	 * @access public
	 * @var string
	 */
	public $type = 'soledad-fw-button';

	/**
	 * The input type
	 *
	 * @access public
	 * @var string
	 */
	public $data_type = '';
	public $description = '';
	public $nonce = '';

	/**
	 * Refresh the parameters passed to the JavaScript via JSON.
	 *
	 * @see WP_Customize_Control::to_json()
	 */
	public function to_json() {
		parent::to_json();

		$this->json['data_type']   = $this->data_type;
		$this->json['description'] = $this->description;
		$this->json['nonce']       = $this->nonce;
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
        <button class="button" data-ajaxurl="<?php echo admin_url( 'admin-ajax.php' ); ?>"
                data-type="{{{ data.data_type }}}" data-nonce="{{{ data.nonce }}}">{{{ data.label }}}
        </button>
        <# if ( data.description ) { #>
        <span class="description customize-control-description">
					<p>{{{ data.description }}}</p>
				</span>
        <# } #>
		<?php
	}
}
