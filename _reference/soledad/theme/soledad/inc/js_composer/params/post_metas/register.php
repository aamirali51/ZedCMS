<?php
/**
 * Callback for new param 'penci_post_metas'.
 *
 * @param array $settings
 * @param string $value
 *
 * @return string
 */
function penci_vc_param_post_metas( $settings, $value ) {
	$output = '';

	$post_metas = array(
		'pauthor'    => __( 'Author', PENCI_SNORLAX_FW ),
		'pdate'      => __( 'Date', PENCI_SNORLAX_FW ),
		'minread'    => __( 'Min Read', PENCI_SNORLAX_FW ),
		'pcomment'   => __( 'Comments', PENCI_SNORLAX_FW ),
		'pcountview' => __( 'Post Count Views', PENCI_SNORLAX_FW ),
	);

	foreach ( $post_metas as $k => $v ) {
		$checked = '';

		$array_vaule = $value ? explode( ',', $value ) : array();
		if ( $array_vaule && in_array( $k, $array_vaule ) ) {
			$checked = ' checked="checked"';
		}

		$output .= ' <label class="vc_checkbox-label"><input id="'
		           . $settings['param_name'] . '-' . $k . '" value="'
		           . $k . '" class="wpb_vc_param_value '
		           . $settings['param_name']
		           . ' ' . $settings['type']
		           . '" type="checkbox" name="'
		           . $settings['param_name'] . '"' . $checked . '> '
		           . $v . '</label><br>';

	}

	return $output;
}

