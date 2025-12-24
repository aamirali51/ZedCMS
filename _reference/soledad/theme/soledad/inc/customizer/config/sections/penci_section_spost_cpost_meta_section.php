<?php
$options   = [];
$options[] = array(
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field',
	'label'    => __( 'Showing Custom Meta Fields?', 'soledad' ),
	'id'       => 'penci_cpost_cmeta_enable',
	'type'     => 'soledad-fw-toggle',
);

$options[] = array(
	'default'     => '',
	'sanitize'    => 'penci_sanitize_choices_field',
	'label'       => __( 'Showing Custom Post Meta Fields', 'soledad' ),
	'description'=>__('Secperate with the commas','soledad'),
	'id'          => 'penci_cpost_cmeta_fields',
	'type'        => 'soledad-fw-text',
);

$options[] = array(
	'sanitize'    => 'penci_sanitize_choices_field',
	'label'       => __( 'Showing fields from Advanced Custom Fields plugin', 'soledad' ),
	'description'=>__('You can show your own custom fields easily by using the <a class="wp-customizer-link" href="https://wordpress.org/plugins/advanced-custom-fields/" target="_blank">Advanced Custom Fields</a> plugin.','soledad'),
	'id'          => 'penci_cpost_cmeta_acf',
	'type'        => 'soledad-fw-select',
	'multiple'    => 999,
	'choices'     => penci_get_afc_fields()
);

$options[] = array(
	'default'     => '',
	'sanitize'    => 'penci_sanitize_checkbox_field',
	'label'       => __( 'Showing Custom Post Meta Label', 'soledad' ),
	'description'=>__('This option just applies for the fields from Advanced Custom Fields only.','soledad'),
	'id'          => 'penci_cpost_cmeta_label',
	'type'        => 'soledad-fw-toggle',
);

$options[] = array(
	'default'  => ':',
	'sanitize' => 'penci_sanitize_choices_field',
	'label'    => __( 'Custom Divider Between Meta Label & Meta Value', 'soledad' ),
	'id'       => 'penci_cpost_divider_cmeta_label',
	'type'     => 'soledad-fw-text',
);

return $options;
