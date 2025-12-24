<?php
$options   = [];
$options[] = array(
	'id'          => 'penci_speed_optimize_css',
	'default'     => '',
	'sanitize'    => 'penci_sanitize_checkbox_field',
	'label'       => __( 'Enable Optimize CSS', 'soledad' ),
	'description' => __( "You need to check to this option to make all optimize CSS options below works", "soledad" ),
	'type'        => 'soledad-fw-toggle',
);
$options[] = array(
	'id'          => 'penci_speed_remove_css',
	'default'     => false,
	'sanitize'    => 'penci_sanitize_checkbox_field',
	'label'       => __( 'Create Critical CSS?', 'soledad' ),
	'description' => __( 'Remove Unused CSS to reduce the loading time, all other CSS will be delayed to loads until user interaction.', 'soledad' ),
	'type'        => 'soledad-fw-toggle',
);
$options[] = array(
	'id'          => 'penci_speed_optimize_css_minify',
	'default'     => '',
	'sanitize'    => 'penci_sanitize_checkbox_field',
	'label'       => __( 'Minify All CSS', 'soledad' ),
	'description' => __( "Minify CSS to reduced the CSS size.", "soledad" ),
	'type'        => 'soledad-fw-toggle',
);
$options[] = array(
	'id'          => 'penci_speed_optimize_css_to_inline',
	'default'     => '',
	'sanitize'    => 'penci_sanitize_checkbox_field',
	'label'       => __( 'Inline Optimized CSS', 'soledad' ),
	'description' => __( "Inline the CSS to prevent flash of unstyled content. Highly recommended.", "soledad" ),
	'type'        => 'soledad-fw-toggle',
);
$options[] = array(
	'id'          => 'penci_speed_optimize_gfonts',
	'default'     => '',
	'sanitize'    => 'penci_sanitize_checkbox_field',
	'label'       => __( 'Optimize Google Fonts', 'soledad' ),
	'description' => __( "Add preconnect hints and add display swap for Google Fonts.", "soledad" ),
	'type'        => 'soledad-fw-toggle',
);
$options[] = array(
	'id'          => 'penci_speed_optimize_gfonts_inline',
	'default'     => '',
	'sanitize'    => 'penci_sanitize_checkbox_field',
	'label'       => __( 'Inline Google Fonts CSS', 'soledad' ),
	'description' => __( "Inline the Google Fonts CSS for a big boost on FCP and slight on LCP on mobile. Highly recommended.", "soledad" ),
	'type'        => 'soledad-fw-toggle',
);
$options[] = array(
	'id'          => 'penci_speed_optimize_gfonts_delay',
	'default'     => '',
	'sanitize'    => 'penci_sanitize_checkbox_field',
	'label'       => __( 'Delay Loading Google Fonts', 'soledad' ),
	'description' => __( "It can helps you reduce the FCP & LCP for Core Web Vitals.", "soledad" ),
	'type'        => 'soledad-fw-toggle',
);
$options[] = array(
	'id'       => 'penci_speed_optimize_disable_icon_delay',
	'default'  => '',
	'sanitize' => 'penci_sanitize_checkbox_field',
	'label'    => __( 'Disable Delay Loading Icon Fonts', 'soledad' ),
	'type'     => 'soledad-fw-toggle',
);
$options[] = array(
	'default'     => '',
	'label'       => __( 'Remove Gutenberg Styles', 'soledad' ),
	'description' => __( 'Use with caution. This will remove styles for Gutenberg editor from the <head> - only activate it if your website users using the Classic Editor', 'soledad' ),
	'id'          => 'penci_speed_remove_gutenbergcss',
	'type'        => 'soledad-fw-toggle',
);
$options[] = array(
	'id'       => 'penci_heading_inex_opts',
	'sanitize' => 'sanitize_text_field',
	'label'    => esc_html__( 'File Rules Options', 'soledad' ),
	'type'     => 'soledad-fw-header',
);
$options[] = array(
	'id'          => 'penci_speed_optimize_css_excludes',
	'default'     => '',
	'sanitize'    => 'penci_sanitize_textarea_field',
	'label'       => __( 'Exclude Stylesheets from Optimize CSS', 'soledad' ),
	'description' => __( "Enter one per line to exclude certain CSS files from this optimizations. Examples: <strong>id:my-css-id</strong> OR <strong>a-part-from-file-url</strong>", "soledad" ),
	'type'        => 'soledad-fw-textarea',
);
$options[] = array(
	'id'          => 'penci_speed_remove_css_excludes',
	'default'     => '',
	'sanitize'    => 'penci_sanitize_textarea_field',
	'label'       => __( 'Exclude Stylesheets from Remove Unused CSS', 'soledad' ),
	'description' => __( "Enter one per line to exclude certain CSS files from this optimizations. It won't remove the Unused CSS from those stylesheets. Examples: <strong>id:my-css-id</strong> OR <strong>a-part-from-file-url</strong>", "soledad" ),
	'type'        => 'soledad-fw-textarea',
);
$options[] = array(
	'id'          => 'penci_speed_allow_css_selectors',
	'default'     => '',
	'sanitize'    => 'penci_sanitize_textarea_field',
	'label'       => __( 'Always Keep Selectors', 'soledad' ),
	'description' => __( "Enter one per line. Partial or full matches for selectors (if any of these keywords found, the selector will be kept). Examples: .myclass", "soledad" ),
	'type'        => 'soledad-fw-textarea',
);
$options[] = array(
	'id'       => 'penci_heading_critical_css_head',
	'sanitize' => 'sanitize_text_field',
	'label'    => esc_html__( 'CSS File Options', 'soledad' ),
	'type'     => 'soledad-fw-header',
);
$options[] = array(
	'default'     => 'inline',
	'sanitize'    => 'penci_sanitize_choices_field',
	'label'       => __( 'Render Customizer CSS Method', 'soledad' ),
	'id'          => 'penci_spcss_render',
	'description' => __( 'Render Customizer CSS in a separate file can help you to improve performance dramatically.', 'soledad' ),
	'type'        => 'soledad-fw-select',
	'choices'     => array(
		'inline'        => esc_html__( 'Inline CSS', 'soledad' ),
		'separate_file' => esc_html__( 'Separate CSS File', 'soledad' ),
	)
);
$options[] = array(
	'data_type'       => 'render_separate_css',
	'nonce'           => esc_html( wp_create_nonce( 'penci_render_separate_css_file' ) ),
	'label'           => __( 'Regenerate CSS File', 'soledad' ),
	'id'              => 'penci_render_separate_css',
	'type'            => 'soledad-fw-button',
	'callback'        => 'penci_activate_separate_css_file_callback',
	'active_callback' => [
		[
			'setting'  => 'penci_spcss_render',
			'operator' => '==',
			'value'    => 'separate_file',
		],
	],
);
if ( function_exists( 'penci_speed_optimizer_get_stat' ) ) {
	$soledad_pages_speed_stat = penci_speed_optimizer_get_stat();
	$css_cache                = $soledad_pages_speed_stat['css'];
	$options[]                = array(
		'type'        => 'soledad-fw-button',
		'data_type'   => 'penci_speed_delete_cache',
		'nonce'       => esc_html( wp_create_nonce( 'penci_speed_delete_cache' ) ),
		'label'       => __( 'Clear Critical CSS Cache', 'soledad' ),
		'description' => sprintf( __( '<strong>Critical CSS Cache Files:</strong> <span class="count">%1$d</span>', 'soledad' ), $css_cache ),
		'id'          => 'penci_speed_delete_cache_button',
	);
}

return $options;
