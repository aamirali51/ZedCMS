<?php

namespace PenciSoledadElementor\Controls;

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Base_Data_Control;

class Select2 extends Base_Data_Control {
	public function get_type() {
		return 'penci-select2';
	}

	public function enqueue() {
		wp_register_script( 'penci-select2', PENCI_ELEMENTOR_URL . 'assets/js/penci-select2.js', [ 'jquery-elementor-select2' ], '1.0.0', true );
		wp_localize_script( 'penci-select2', 'penci_select2_localize', [
				'ajaxurl'     => admin_url( 'admin-ajax.php' ),
				'search_text' => esc_html__( 'Search', 'soledad' ),
			] );
		wp_enqueue_script( 'penci-select2' );
	}

	public function content_template() {
		$control_uid = $this->get_control_uid();
		?>
        <# var controlUID = '<?php echo $control_uid; ?>'; #>
        <# var currentID = elementor.panel.currentView.currentPageView.model.attributes.settings.attributes[data.name]; #>
        <div class="elementor-control-field">
            <# if ( data.label ) { #>
            <label for="<?php echo $control_uid; ?>" class="elementor-control-title">{{{data.label }}}</label>
            <# } #>
            <div class="elementor-control-input-wrapper elementor-control-unit-5">
                <# var multiple = ( data.multiple ) ? 'multiple' : ''; #>
                <select id="<?php echo $control_uid; ?>" {{ multiple }} class="penci-select2"
                        data-setting="{{ data.name }}"></select>
            </div>
        </div>
        <#
        ( function( $ ) {
        $( document.body ).trigger( 'penci_select2_init',{currentID:currentID,data:data,controlUID:controlUID,multiple:data.multiple} );
        }( jQuery ) );
        #>
		<?php
	}

	protected function get_default_settings() {
		return [
			'multiple'    => true,
			'source_name' => 'post_type',
			'source_type' => 'post',
		];
	}
}
