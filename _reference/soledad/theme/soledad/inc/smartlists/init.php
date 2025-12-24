<?php
require_once( PENCI_SOLEDAD_DIR . '/inc/smartlists/class_penci_splitter.php' );
require_once( PENCI_SOLEDAD_DIR . '/inc/smartlists/class_penci_smartlists.php' );
require_once( PENCI_SOLEDAD_DIR . '/inc/smartlists/metabox.php' );

if ( ! function_exists( 'penci_smartlists' ) ) {
	function penci_smartlists( $args ) {
		$default = [
			'style'               => 1,
			'content'             => '',
			'order'               => '',
			'h'                   => 'h3',
			'extract_first_image' => true,
			'sm_title_tag'        => 'h3',
			'sm_ad'               => get_theme_mod( 'penci_ads_inside_content_html' ),
			'first_image_size'    => '',
			'first_image_msize'   => '',
			'disablelazy'         => get_theme_mod( 'penci_disable_lazyload_single' )
		];

		$content  = '';
		$settings = wp_parse_args( $args, $default );

		if ( empty( $settings['style'] ) ) {
			$settings['style'] = get_theme_mod( 'penci_single_smartlists_style', 1 );
		}

		// Define allowed styles (example list, adjust to match your theme's templates)
		$allowed_styles = array( '1', '2', '3', '4', '5', '6' );

		// Validate and sanitize
		$style = ( isset( $settings['style'] ) && in_array( $settings['style'], $allowed_styles, true ) )
			? $settings['style']
			: '1';

		// Build safe path
		$smart_lists_template = PENCI_SOLEDAD_DIR . '/inc/smartlists/templates/penci_smartlists_' . $style . '.php';

		if ( file_exists( $smart_lists_template ) ) {
			require_once $smart_lists_template;
		}
		$smart_list_class = 'Penci_SmartLists_Style_' . esc_attr( $settings['style'] );

		if ( class_exists( $smart_list_class ) ) {

			$smart_list_obj      = new $smart_list_class( $settings );
			$smart_list_settings = [
				'post_content'        => $settings['content'],
				'counting_order_asc'  => $settings['order'] == 'asc',
				'penci_smart_list_h'  => $settings['h'],
				'extract_first_image' => $settings['extract_first_image'],
			];

			$content = $smart_list_obj->get_content( $smart_list_settings );
		}

		return $content;
	}
}

if ( ! function_exists( 'penci_smartlists_add_query' ) ) {
	function penci_smartlists_add_query( $vars ) {
		$vars[] = 'list';

		return $vars;
	}

	add_filter( 'query_vars', 'penci_smartlists_add_query' );
}

if ( ! function_exists( 'penci_smartlists_custom_css' ) ) {
	add_action( 'soledad_theme/custom_css', 'penci_smartlists_custom_css' );
	function penci_smartlists_custom_css() {
		$should_render = false;

		if ( is_singular() && 'yes' == get_post_meta( get_the_ID(), 'pcsml_smartlists_enable', true ) ) {
			$should_render = true;
		}

		if ( ! $should_render ) {
			return;
		}

		$custom_css = '';

		$general_style = [
			'penci_sml_heading_cl'           => [
				'.pcsml-el.pcsml-customized-ver .pcsml-title-wrapper .pcsml-item-title,.pcsml-el.pcsml-customized-ver .pcsml-item-title' => 'color:{{VALUE}};'
			],
			'penci_sml_number_cl'            => [
				'.pcsml-el.pcsml-customized-ver .pcsml-item-number span' => 'background-color:{{VALUE}};'
			],
			'penci_sml_number_bcl'           => [
				'.pcsml-el.pcsml-customized-ver .pcsml-item-number span' => 'border:1px solid {{VALUE}};'
			],
			'penci_sml_desc_cl'              => [
				'.pcsml-el.pcsml-customized-ver .pcsml-desc' => 'color:{{VALUE}};'
			],
			'penci_sml_btn_cl'               => [
				'.pcsml-el.pcsml-customized-ver .pcsml-button' => 'color:{{VALUE}};'
			],
			'penci_sml_btn_bgcl'             => [
				'.pcsml-el.pcsml-customized-ver .pcsml-button' => 'background-color:{{VALUE}};'
			],
			'penci_sml_btn_bdcl'             => [
				'.pcsml-el.pcsml-customized-ver .pcsml-button' => 'border:1px solid {{VALUE}};'
			],
			'penci_sml_btn_hcl'              => [
				'.pcsml-el.pcsml-customized-ver .pcsml-button:hover' => 'color:{{VALUE}};'
			],
			'penci_sml_btn_bghcl'            => [
				'.pcsml-el.pcsml-customized-ver .pcsml-button:hover' => 'background-color:{{VALUE}};'
			],
			'penci_sml_btn_bdhcl'            => [
				'.pcsml-el.pcsml-customized-ver .pcsml-button:hover' => 'border:1px solid {{VALUE}};'
			],
			'penci_sml_nav_bgcl'             => [
				'.pcsml-el.pcsml-customized-ver .pcsml-dropdown-wrap, .pcsml-el.pcsml-customized-ver .pcsml-dropdown-wrap .pcsml-dropdown' => 'background-color:{{VALUE}};'
			],
			'penci_sml_nav_bdcl'             => [
				'.pcsml-el.pcsml-customized-ver .pcsml_style_6' => '--pcborder-cl:{{VALUE}};'
			],
			'penci_smartlists_inumber_fsize' => [
				'.pcsml-el.pcsml-customized-ver .pcsml-item-number span' => 'font-size:{{VALUE}}px',
			],
			'penci_smartlists_heading_fsize' => [
				'.pcsml-el.pcsml-customized-ver .pcsml-title-wrapper .pcsml-item-title' => 'font-size:{{VALUE}}px',
			],
			'penci_smartlists_text_fsize'    => [
				'.pcsml-el.pcsml-customized-ver .pcsml-desc,.pcsml-el.pcsml-customized-ver .pcsml-desc p' => 'font-size:{{VALUE}}px',
			],
			'penci_smartlists_btn_fsize'     => [
				'.pcsml-el.pcsml-customized-ver .pcsml-button' => 'font-size:{{VALUE}}px',
			]
		];

		$mobile_style = [
			'penci_smartlists_heading_mfsize' => [
				'.pcsml-el.pcsml-customized-ver .pcsml-title-wrapper .pcsml-item-title' => 'font-size:{{VALUE}}px',
			],
			'penci_smartlists_text_mfsize'    => [
				'.pcsml-el.pcsml-customized-ver .pcsml-desc, .pcsml-el.pcsml-customized-ver .pcsml-desc p' => 'font-size:{{VALUE}}px',
			],
			'penci_smartlists_btn_mfsize'     => [
				'.pcsml-el.pcsml-customized-ver .pcsml-button' => 'font-size:{{VALUE}}px',
			]
		];

		foreach ( $general_style as $mod => $props ) {
			$value = get_theme_mod( $mod );

			if ( $value ) {
				foreach ( $props as $selector => $val ) {
					$custom_css .= $selector . '{' . str_replace( '{{VALUE}}', $value, $val ) . '}';
				}
			}

		}

		foreach ( $mobile_style as $mod => $props ) {
			$value = get_theme_mod( $mod );

			if ( $value ) {
				foreach ( $props as $selector => $val ) {
					$custom_css .= '@media only screen and (max-width: 767px) {' . $selector . '{' . str_replace( '{{VALUE}}', $value, $val ) . '}}';
				}
			}

		}

		$spacing = get_post_meta( get_the_ID(), 'pcsml_smartlists_spacing', true );

		if ( ! empty( $spacing ) ) {
			$custom_css .= '.pcsml-el.pcsml-customized-ver .pcsml-item:not(:last-child){ margin-bottom: ' . $spacing . ';padding: 0 0 ' . $spacing . '; }';
		}

		echo $custom_css;

	}
}
if ( ! function_exists( 'penci_remove_shortcode_trigger_on_specific_pages' ) ) {
	function penci_remove_shortcode_trigger_on_specific_pages( $output, $tag, $attr ) {

		// page ids where we want to remove shortcodes
		$smartlists_enable = get_post_meta( get_the_ID(), 'pcsml_smartlists_enable', true );

		if ( $smartlists_enable ) {

			// array of shortcode tags to be removed.
			$shortcodes_to_remove = array( 'inline_related_posts', 'penci_index', 'penci_recipe', 'portfolio' );
			foreach ( $shortcodes_to_remove as $shortcode_tag ) {
				if ( $shortcode_tag == $tag ) {
					$output = '';
				}
			}
		}

		return $output;

	}

	add_filter( 'do_shortcode_tag', 'penci_remove_shortcode_trigger_on_specific_pages', 20, 3 );
}

if ( ! function_exists( 'penci_remove_smartlist_shortcode' ) ) {
	add_filter( 'the_content', 'penci_remove_smartlist_shortcode', 100, 1 );
	function penci_remove_smartlist_shortcode( $content ) {

		// page ids where we want to remove shortcodes
		$smartlists_enable = get_post_meta( get_the_ID(), 'pcsml_smartlists_enable', true );

		if ( ! $smartlists_enable ) {
			$content = str_replace( '[penci_end_smartlists]', '', $content );
		}

		return $content;

	}
}