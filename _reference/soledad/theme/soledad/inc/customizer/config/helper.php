<?php
if ( ! function_exists( 'penci_fw_customizer' ) ) {
	function penci_fw_customizer() {
		return SoledadFW\Customizer\Customizer::get_instance();
	}
}
