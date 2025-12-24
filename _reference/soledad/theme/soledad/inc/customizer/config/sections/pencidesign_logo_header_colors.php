<?php
$options   = [];
$options[] = array(
	'sanitize' => 'sanitize_text_field',
	'label'    => esc_html__( 'Slide Up Search Form', 'soledad' ),
	'id'       => 'penci_section_searchform_color',
	'type'     => 'soledad-fw-header',
);
$options[] = array(
	'id'       => 'penci_section_searchform_form_bg',
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Search Form Background Color', 'soledad' ),
);
$options[] = array(
	'id'       => 'penci_section_searchform_top_border_cl',
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Search Form Border Top Color', 'soledad' ),
);
$options[] = array(
	'id'       => 'penci_section_searchform_text_bg',
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Search Field Background Color', 'soledad' ),
);
$options[] = array(
	'id'       => 'penci_section_searchform_text_cl',
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Search Field Text Color', 'soledad' ),
);
$options[] = array(
	'id'       => 'penci_section_searchform_bd_cl',
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Search Field Border Color', 'soledad' ),
);
$options[] = array(
	'id'       => 'penci_section_searchform_btn_bg',
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Submit Button Background Color', 'soledad' ),
);
$options[] = array(
	'id'       => 'penci_section_searchform_btn_hv_bg',
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Submit Button Hover Background Color', 'soledad' ),
);
$options[] = array(
	'id'       => 'penci_section_searchform_btn_cl',
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Submit Button Text Color', 'soledad' ),
);
$options[] = array(
	'id'       => 'penci_section_searchform_btn_hv_cl',
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Submit Button Hover Text Color', 'soledad' ),
);

return $options;
