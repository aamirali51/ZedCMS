<?php
if ( ! function_exists( 'penci_delayed_div_condition' ) ) {
	function penci_delayed_div_condition() {

		$attr = [];

		$name = $id = '';

		if ( is_home() || is_front_page() ) {
			$name = 'home';
		} elseif ( is_page() ) {
			$name = 'page';
		} elseif ( is_singular() ) {
			$name = 'single';
		} elseif ( is_category() ) {
			$name = 'category';
		} elseif ( is_tag() ) {
			$name = 'tag';
		} elseif ( is_search() ) {
			$name = 'search';
		} elseif ( is_tax() ) {
			$name = 'tax';
		} elseif ( is_archive() ) {
			$name = 'archive';
		}

		if ( is_page() || is_single() || is_singular() ) {
			$id = get_the_ID();
		}

		if ( is_tag() || is_category() ) {
			$id = get_queried_object_id();
		}

		if ( $id ) {
			$attr[] = 'data-delayed-id="' . $id . '"';
		}

		if ( $name ) {
			$attr[] = 'data-delayed-con="' . $name . '"';
		}

		if ( $attr ) {
			return implode( ' ', $attr );
		}

	}
}

if ( ! function_exists( 'penci_delayed_div_content' ) ) {
	add_action( 'wp_ajax_penci_delayed_div_content', 'penci_delayed_div_content' );
	add_action( 'wp_ajax_nopriv_penci_delayed_div_content', 'penci_delayed_div_content' );
	function penci_delayed_div_content() {
		$type     = isset( $_REQUEST['type'] ) ? $_REQUEST['type'] : '';
		$id       = isset( $_REQUEST['id'] ) ? $_REQUEST['id'] : '';
		$settings = isset( $_REQUEST['settings'] ) ? wp_unslash( $_REQUEST['settings'] ) : [];
		$class    = isset( $_REQUEST['class'] ) ? $_REQUEST['class'] : 'pc-block-content';
		$blogid   = get_current_blog_id();

		$unqiue_name = 'pcdelayed_' . $blogid . strtolower( $type . $id . $class );

		$cache_data = get_transient( $unqiue_name );

		$cache_data = '';

		$html = '';

		if ( $cache_data ) {
			$html = $cache_data;
		} else {

			if ( $type == 'block' ) {
				$content = get_post( $id );

				if ( $content ) {
					if ( did_action( 'elementor/loaded' ) && \Elementor\Plugin::$instance->documents->get( $id )->is_built_with_elementor() ) {
						$html .= '<div class="' . esc_attr( $class ) . '">';
						$html .= penci_get_elementor_content( $id );
						$html .= '</div>';
					} else {
						$html .= '<div class="' . esc_attr( $class ) . ' js-composer-content">';
						$html .= do_shortcode( $content->post_content );

						$shortcodes_custom_css = get_post_meta( $id, '_wpb_shortcodes_custom_css', true );

						$html .= '<style data-type="vc_shortcodes-custom-css">';
						if ( ! empty( $shortcodes_custom_css ) ) {
							$html .= $shortcodes_custom_css;
						}
						$html .= '</style>';
						$html .= '</div>';
					}
				}
			} else if ( $type == 'template-footer' ) {
				ob_start();
				extract( json_decode( $settings, true ) );
				include PENCI_SOLEDAD_DIR . '/template-parts/footer/footer-main.php';
				$html = ob_get_clean();
			}

			set_transient( $unqiue_name, $html );

		}

		wp_send_json_success( [ 'html' => $html ] );
	}
}

add_action( 'save_post', 'penci_delayed_clear_cache' );
add_action( 'wp_update_post', 'penci_delayed_clear_cache' );
add_action( 'customize_save_after', 'penci_delayed_clear_cache' );
function penci_delayed_clear_cache() {
	global $wpdb;

	$blogid = get_current_blog_id();

	$transients = (array) $wpdb->get_results( "SELECT `option_name` FROM {$wpdb->options} WHERE `option_name` LIKE 'pcdelayed_{$blogid}_%'", ARRAY_A );

	foreach ( $transients as $transient ) {
		$transient = str_replace( '_transient_', '', $transient['option_name'] );
		\delete_transient( $transient );
	}
}

add_filter( 'template_include', function( $template ) {
	if ( is_singular() && isset($_REQUEST['penci_get_delayed_sections'])  ) {
		if ( $_REQUEST['penci_get_delayed_sections'] == 'sections' ) {
			$template = locate_template( array( 'template-parts/single-sections.php' ) );
		} else if ( $_REQUEST['penci_get_delayed_sections'] == 'sidebar' ) {
			$template = locate_template( array( 'template-parts/single-sidebar.php' ) );
		} else if ( $_REQUEST['penci_get_delayed_sections'] == 'mobile_nav' ) {
			$template = locate_template( array( 'template-parts/header/vertical-nav.php' ) );
		} else if ( $_REQUEST['penci_get_delayed_sections'] == 'content' ) {
			$template = locate_template( array( 'template-parts/single-content.php' ) );
		}
		
	}
	return $template;
}, 99 );