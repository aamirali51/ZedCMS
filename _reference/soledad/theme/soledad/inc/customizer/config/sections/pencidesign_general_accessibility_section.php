<?php
$options = [];

$options[] = array(
	'label'           => __( 'General', 'soledad' ),
	'id'              => 'penci_accessibility_heading_03',
	'type'            => 'soledad-fw-header',
);

$options[] = array(
	'default'         => false,
	'sanitize'        => 'penci_sanitize_checkbox_field',
	'label'           => __( 'Enable Commons Accessibility Options', 'soledad' ),
	'description'     => __( 'Enable this option to access the settings below.', 'soledad' ),
	'id'              => 'penci_accessibility_enable',
	'type'            => 'soledad-fw-toggle',
);

$options[] = array(
	'label'           => __( 'Standard Accessibility', 'soledad' ),
	'id'              => 'penci_accessibility_heading_01',
	'type'            => 'soledad-fw-header',
);

$options[] = array(
	'default'         => false,
	'sanitize'        => 'penci_sanitize_checkbox_field',
	'label'           => __( 'Enable Skiplinks', 'soledad' ),
	'id'              => 'penci_accessibility_skiplinks',
	'type'            => 'soledad-fw-toggle',
);

$options[] = array(
	'default'         => false,
	'sanitize'        => 'penci_sanitize_checkbox_field',
	'label'           => __( 'Skiplinks always visible', 'soledad' ),
	'id'              => 'penci_accessibility_skiplinks_visible',
	'type'            => 'soledad-fw-toggle',
);

$options[] = array(
	'default'         => false,
	'sanitize'        => 'penci_sanitize_checkbox_field',
	'label'           => __( 'Remove tabindex from focusable elements', 'soledad' ),
	'id'              => 'penci_accessibility_rm_tabindex',
	'type'            => 'soledad-fw-toggle',
);

$options[] = array(
	'default'         => false,
	'sanitize'        => 'penci_sanitize_checkbox_field',
	'label'           => __( 'Force underline on links', 'soledad' ),
	'id'              => 'penci_accessibility_underline',
	'type'            => 'soledad-fw-toggle',
);

$options[] = array(
	'default'         => false,
	'sanitize'        => 'penci_sanitize_checkbox_field',
	'label'           => __( 'Add outline to elements on keyboard focus', 'soledad' ),
	'id'              => 'penci_accessibility_focus',
	'type'            => 'soledad-fw-toggle',
);

$options[] = array(
	'default'         => false,
	'sanitize'        => 'penci_sanitize_checkbox_field',
	'label'           => __( 'Use Keyboard to Navigate Next/Previous Posts', 'soledad' ),
	'id'              => 'penci_accessibility_key_prext',
	'type'            => 'soledad-fw-toggle',
);

$options[] = array(
	'default'         => false,
	'sanitize'        => 'penci_sanitize_checkbox_field',
	'label'           => __( 'Add Missing IMG Alt Tag', 'soledad' ),
	'id'              => 'penci_accessibility_img_alt',
	'type'            => 'soledad-fw-toggle',
);

$options[] = array(
	'default'         => false,
	'sanitize'        => 'penci_sanitize_checkbox_field',
	'label'           => __( 'Force Add a Title Attribute to an HTML Tag', 'soledad' ),
	'id'              => 'penci_accessibility_title_attr',
	'type'            => 'soledad-fw-toggle',
);

$options[] = array(
	'default'         => false,
	'sanitize'        => 'penci_sanitize_checkbox_field',
	'label'           => __( 'Disable All Theme Animations', 'soledad' ),
	'id'              => 'penci_accessibility_animate',
	'type'            => 'soledad-fw-toggle',
);

$options[] = array(
	'label'           => __( 'Enhanced Accessibility', 'soledad' ),
	'id'              => 'penci_accessibility_heading_02',
	'type'            => 'soledad-fw-header',
);

$options[] = array(
	'default'         => false,
	'sanitize'        => 'penci_sanitize_checkbox_field',
	'label'           => __( 'Enable Enhanced Accessibility Features', 'soledad' ),
	'description' 	  => __( 'Enables comprehensive WCAG compliance features including improved navigation, form handling, and screen reader support.', 'soledad' ),
	'id'              => 'penci_accessibility_enhanced',
	'type'            => 'soledad-fw-toggle',
);

$options[] = array(
	'default'         => false,
	'sanitize'        => 'penci_sanitize_checkbox_field',
	'label'           => __( 'Enhanced Keyboard Navigation', 'soledad' ),
	'id'              => 'penci_accessibility_enhanced_nav',
	'type'            => 'soledad-fw-toggle',
);

$options[] = array(
	'default'         => false,
	'sanitize'        => 'penci_sanitize_checkbox_field',
	'label'           => __( 'ARIA Live Regions', 'soledad' ),
	'id'              => 'penci_accessibility_aria_live',
	'type'            => 'soledad-fw-toggle',
);

$options[] = array(
	'default'         => false,
	'sanitize'        => 'penci_sanitize_checkbox_field',
	'label'           => __( 'Enhanced Form Labels', 'soledad' ),
	'id'              => 'penci_accessibility_form_labels',
	'type'            => 'soledad-fw-toggle',
);

return $options;