<?php
$options   = [];
$options[] = [
	'id'          => 'penci_custom_css',
	'transport'   => 'refresh',
	'type'        => 'soledad-fw-code',
	'code_type'   => 'text/css',
	'label'       => esc_html__( 'Custom CSS', 'soledad' ),
	'input_attrs' => array(
		'aria-describedby' => 'editor-keyboard-trap-help-1 editor-keyboard-trap-help-2 editor-keyboard-trap-help-3 editor-keyboard-trap-help-4',
	),
];

return $options;