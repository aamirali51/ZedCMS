<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Plugin;
use SoledadFW\Customizer\CustomizerOptionAbstract;

define( 'PENCI_BUILDER_PATH', PENCI_SOLEDAD_DIR . '/inc/builder/' );
define( 'PENCI_BUILDER_URL', PENCI_SOLEDAD_URL . '/inc/builder/' );
require_once PENCI_SOLEDAD_DIR . '/inc/builder/class/template.php';
require_once PENCI_SOLEDAD_DIR . '/inc/builder/customizer/menu-callback.php';
require_once PENCI_SOLEDAD_DIR . '/inc/builder/customizer/builder_helper.php';
require_once PENCI_SOLEDAD_DIR . '/inc/builder/class/header_builder.php';
require_once PENCI_SOLEDAD_DIR . '/inc/builder/customizer/style.php';

HeaderBuilder::getInstance();

add_action(
	'init',
	function () {
		if ( class_exists( '\SoledadFW\Customizer\CustomizerOptionAbstract' ) ) {
			require_once PENCI_SOLEDAD_DIR . '/inc/builder/class/builder_panel.php';
			require_once PENCI_SOLEDAD_DIR . '/inc/builder/class/sections.php';
			\SoledadFW\HeaderBuilderCustomizer::getInstance();
		}
	}
);

if ( ! function_exists( 'penci_check_theme_mod' ) ) {
	function penci_check_theme_mod() {
		$val          = false;
		$page_data    = get_theme_mod( 'pchdbd_all' );
		$page_archive = get_theme_mod( 'pchdbd_archive' );
		$page_posts   = get_theme_mod( 'pchdbd_post' );
		$page_woo     = get_theme_mod( 'pchdbd_woocommerce' );
		$page_pages   = get_theme_mod( 'pchdbd_page' );
		$page_home    = get_theme_mod( 'pchdbd_homepage' );
		if ( ! empty( $page_data ) ) {
			$val = true;
		}
		if ( ! empty( $page_woo ) && function_exists( 'is_woocommerce' ) && ( is_woocommerce() ) ) {
			$val = true;
		}
		if ( ! empty( $page_home ) && ( is_home() || is_front_page() ) ) {
			$val = true;
		}
		if ( ! empty( $page_archive ) && is_archive() ) {
			$val = true;
		}
		if ( ! empty( $page_posts ) && is_single() ) {
			$val = true;
		}
		if ( ! empty( $page_pages ) && is_page() ) {
			$val = true;
		}
		if ( is_page() ) {

			$data_check = get_post_meta( get_the_ID(), 'penci_pmeta_page_header', true );

			if ( is_array( $data_check ) && ! empty( $data_check ) && isset( $data_check['header_builder_layout'] ) && $data_check['header_builder_layout'] ) {
				$val = true;
			}
		}
		if ( is_singular() && ! is_page() ) {
			$single_check = penci_get_single_key( get_the_ID(), 'penci_header_builder_layout' );
			if ( $single_check ) {
				$val = true;
			}
		}
		if ( is_singular( 'penci_builder' ) || is_singular( 'penci-block' ) ) {
			$val = true;
		}
		if ( isset( $_GET['view-header-layout'] ) ) {
			$val = true;
		}
		if ( is_customize_preview() && isset( $_GET['layout_id'] ) ) {
			$val = true;
		}

		if ( get_theme_mod( 'penci_vertical_nav_show' ) ) {
			$val = false;
		}

		return $val;
	}
}

if ( ! function_exists( 'penci_get_header_builder_id' ) ) {
	function penci_get_header_builder_id() {
		$val          = '';
		$page_data    = get_theme_mod( 'pchdbd_all' );
		$page_archive = get_theme_mod( 'pchdbd_archive' );
		$page_posts   = get_theme_mod( 'pchdbd_post' );
		$page_woo     = get_theme_mod( 'pchdbd_woocommerce' );
		$page_pages   = get_theme_mod( 'pchdbd_page' );
		$page_home    = get_theme_mod( 'pchdbd_homepage' );
		if ( ! empty( $page_data ) ) {
			$val = $page_data;
		}
		if ( ! empty( $page_woo ) && function_exists( 'is_woocommerce' ) && ( is_woocommerce() ) ) {
			$val = $page_woo;
		}
		if ( ! empty( $page_home ) && ( is_home() || is_front_page() ) ) {
			$val = $page_home;
		}
		if ( ! empty( $page_archive ) && is_archive() ) {
			$val = $page_archive;
		}
		if ( ! empty( $page_posts ) && is_single() ) {
			$val = $page_posts;
		}
		if ( ! empty( $page_pages ) && is_page() ) {
			$val = $page_pages;
		}
		if ( is_page() ) {

			$data_check = get_post_meta( get_the_ID(), 'penci_pmeta_page_header', true );

			if ( is_array( $data_check ) && ! empty( $data_check ) && isset( $data_check['header_builder_layout'] ) && $data_check['header_builder_layout'] ) {
				$val = $data_check['header_builder_layout'];
			}
		}
		if ( is_singular() && ! is_page() ) {

			$single_id = penci_get_single_key( get_the_ID(), 'penci_header_builder_layout' );

			if ( $single_id ) {
				$val = $single_id;
			}
		}

		return $val;
	}
}

if ( ! function_exists( 'penci_can_render_footer' ) ) {
	function penci_can_render_footer() {
		$render = false;

		$footer_site    = get_theme_mod( 'penci_footer_builder_layout' );
		$footer_home    = get_theme_mod( 'penci_footer_builder_layout_homepage' );
		$footer_archive = get_theme_mod( 'penci_footer_builder_layout_archive' );
		$footer_page    = get_theme_mod( 'penci_footer_builder_layout_page' );
		$footer_post    = get_theme_mod( 'penci_footer_builder_layout_post' );

		if ( ! empty( $footer_site ) ) {
			$render = true;
		}

		if ( ! empty( $footer_home ) && ( is_home() || is_front_page() ) ) {
			$render = true;
		}

		if ( is_archive() ) {

			if ( ! empty( $footer_archive ) ) {
				$render = true;
			}

			if ( is_category() ) {
				$current_cat_id   = get_query_var( 'cat' );
				$category_options = get_option( "category_$current_cat_id" );

				if ( isset( $category_options['cat_footer'] ) && is_numeric( $category_options['cat_footer'] ) ) {
					$render = true;
				} else if ( ! empty( $footer_archive ) ) {
					$render = true;
				} elseif ( ! empty( $footer_site ) ) {
					$render = true;
				}

			} else if ( ! empty( $footer_archive ) ) {
				$render = true;
			} elseif ( ! empty( $footer_site ) ) {
				$render = true;
			}

		}

		if ( ! empty( $footer_post ) && is_single() ) {
			$render = true;
		}

		if ( ! empty( $footer_page ) && is_page() ) {
			$render = true;
		}

		if ( is_singular() && ! is_page() ) {

			$single_check = penci_get_single_key( get_the_ID(), 'penci_footer_builder_layout' );

			if ( $single_check ) {
				$render = true;
			}

		}

		if ( is_page() ) {
			$data_check = get_post_meta( get_the_ID(), 'penci_pmeta_page_footer', true );
			if ( is_array( $data_check ) && ! empty( $data_check ) && isset( $data_check['footer_builder_layout'] ) && $data_check['footer_builder_layout'] ) {
				$render = true;
			}
		}

		if ( is_singular( 'penci-block' ) ) {
			$render = true;
		}

		if ( isset( $_GET['layout_id'] ) && is_customize_preview() ) {
			$render = true;
		}

		return $render;
	}
}

if ( ! function_exists( 'penci_get_builder_mod' ) ) {
	function penci_get_builder_mod( $setting, $default = '' ) {
		$header_data     = '';
		$data            = get_theme_mod( $setting, $default );
		$page_data       = get_theme_mod( 'pchdbd_all' );
		$page_archive    = get_theme_mod( 'pchdbd_archive' );
		$page_posts      = get_theme_mod( 'pchdbd_post' );
		$page_pages      = get_theme_mod( 'pchdbd_page' );
		$page_home       = get_theme_mod( 'pchdbd_homepage' );
		$page_woo        = get_theme_mod( 'pchdbd_woocommerce' );
		$customizer_save = isset( $_GET['layout_id'] ) ? (int) $_GET['layout_id'] : '';
		$header_id       = '';

		if ( ( is_home() || is_front_page() ) && $page_home ) {
			$page_home = get_page_by_path( $page_home, OBJECT, 'penci_builder' );
			$header_id = isset( $page_home->ID ) && $page_home->ID ? $page_home->ID : '';
		} elseif ( is_archive() ) {
			if ( is_category() ) {
				$current_cat_id   = get_query_var( 'cat' );
				$category_options = get_option( "category_$current_cat_id" );

				if ( isset( $category_options['cat_header_builder'] ) && $category_options['cat_header_builder'] ) {
					$page_archive = get_page_by_path( $category_options['cat_header_builder'], OBJECT, 'penci_builder' );
					$header_id    = isset( $page_archive->ID ) && $page_archive->ID != '' ? $page_archive->ID : '';
				} else if ( $page_archive ) {
					$page_archive = get_page_by_path( $page_archive, OBJECT, 'penci_builder' );
					$header_id    = isset( $page_archive->ID ) && $page_archive->ID ? $page_archive->ID : '';
				} elseif ( $page_data ) {
					$page_data = get_page_by_path( $page_data, OBJECT, 'penci_builder' );
					$header_id = isset( $page_data->ID ) && $page_data->ID ? $page_data->ID : '';
				}

			} else if ( $page_archive ) {
				$page_archive = get_page_by_path( $page_archive, OBJECT, 'penci_builder' );
				$header_id    = isset( $page_archive->ID ) && $page_archive->ID ? $page_archive->ID : '';
			} elseif ( $page_data ) {
				$page_data = get_page_by_path( $page_data, OBJECT, 'penci_builder' );
				$header_id = isset( $page_data->ID ) && $page_data->ID ? $page_data->ID : '';
			}

		} elseif ( function_exists( 'is_woocommerce' ) && is_woocommerce() && $page_woo ) {
			$page_woo  = get_page_by_path( $page_woo, OBJECT, 'penci_builder' );
			$header_id = isset( $page_woo->ID ) && $page_woo->ID ? $page_woo->ID : '';
		} elseif ( is_singular() && ! is_page() ) {
			$data_check = penci_get_single_key( get_the_ID(), 'penci_header_builder_layout' );
			if ( $data_check ) {

				if ( ! ctype_digit( $data_check ) ) {
					$page_archive = get_page_by_path( $data_check, OBJECT, 'penci_builder' );
					$data_check   = isset( $page_archive->ID ) && $page_archive->ID ? $page_archive->ID : '';
				}

				$header_id = $data_check;
			} elseif ( $page_posts ) {
				$page_posts = get_page_by_path( $page_posts, OBJECT, 'penci_builder' );
				$header_id  = isset( $page_posts->ID ) && $page_posts->ID ? $page_posts->ID : '';
			} elseif ( $page_data ) {
				$page_data = get_page_by_path( $page_data, OBJECT, 'penci_builder' );
				$header_id = isset( $page_data->ID ) && $page_data->ID ? $page_data->ID : '';
			}
		} elseif ( is_page() ) {
			$data_check = get_post_meta( get_the_ID(), 'penci_pmeta_page_header', true );
			if ( isset( $data_check['header_builder_layout'] ) && $data_check['header_builder_layout'] ) {
				$header_id = $data_check['header_builder_layout'];
			} elseif ( $page_pages ) {
				$page_pages = get_page_by_path( $page_pages, OBJECT, 'penci_builder' );
				$header_id  = isset( $page_pages->ID ) && $page_pages->ID ? $page_pages->ID : '';
			} elseif ( $page_data ) {
				$page_data = get_page_by_path( $page_data, OBJECT, 'penci_builder' );
				$header_id = isset( $page_data->ID ) && $page_data->ID ? $page_data->ID : '';
			}
		} elseif ( $page_data ) {
			$page_data = get_page_by_path( $page_data, OBJECT, 'penci_builder' );
			$header_id = isset( $page_data->ID ) && $page_data->ID ? $page_data->ID : '';
		}

		if ( $header_id ) {
			if ( 'all' == $setting ) {
				return get_post_meta( $header_id, 'settings_content', true );
			} else {
				$header_data = get_post_meta( $header_id, 'settings_content', true );
				$data        = isset( $header_data[ $setting ] ) && $header_data[ $setting ] !== '' ? $header_data[ $setting ] : $default;
			}
		}

		if ( is_singular( 'penci_builder' ) ) {
			if ( 'all' == $setting ) {
				return get_post_meta( get_the_ID(), 'settings_content', true );
			} else {
				$header_data = get_post_meta( get_the_ID(), 'settings_content', true );
				$data        = isset( $header_data[ $setting ] ) && $header_data[ $setting ] !== '' ? $header_data[ $setting ] : $default;
			}
		}

		if ( isset( $_GET['view-header-layout'] ) && $_GET['view-header-layout'] ) {
			if ( 'all' == $setting ) {
				return get_post_meta( $_GET['view-header-layout'], 'settings_content', true );
			} else {
				$header_data = get_post_meta( $_GET['view-header-layout'], 'settings_content', true );
				$data        = isset( $header_data[ $setting ] ) && $header_data[ $setting ] !== '' ? $header_data[ $setting ] : $default;
			}
		}

		if ( is_customize_preview() && ! empty( $customizer_save ) ) {
			$data = get_theme_mod( $setting, $default );
		}

		if ( is_array( $data ) ) {
			$data = implode( ',', $data );
		}

		if ( $data === null || $data === false || $data === '' ) {
			$data = $default;
		}

		return $data;
	}
}

function penci_builder_validate_mod( $header_data, $setting, $default = '' ) {

	$customizer_save = isset( $_GET['layout_id'] ) ? (int) $_GET['layout_id'] : '';

	if ( is_customize_preview() && ! empty( $customizer_save ) ) {
		$data = get_theme_mod( $setting, $default );
	} else {
		$data        = isset( $header_data[ $setting ] ) && $header_data[ $setting ] !== '' ? $header_data[ $setting ] : $default;
	}

	if ( is_array( $data ) ) {
		$data = implode( ',', $data );
	}

	if ( $data === null || $data === false || $data === '' ) {
		$data = $default;
	}

	return $data;
}

add_action(
	'wp_enqueue_scripts',
	function () {
		if ( penci_check_theme_mod() ) {
			if ( penci_builder_fonts_url() ) {
				wp_register_style( 'penci-header-builder-fonts', penci_builder_fonts_url(), array(), PENCI_SOLEDAD_VERSION );
			}

			// Enqueue style
			if ( ! get_theme_mod( 'penci_disable_default_fonts' ) ) {
				if ( penci_builder_fonts_url() ) {
					wp_enqueue_style( 'penci-header-builder-fonts' );
				}
				$data_fonts = penci_builder_fonts_url( 'earlyaccess' );
				if ( is_array( $data_fonts ) && ! empty( $data_fonts ) ) {
					foreach ( $data_fonts as $fontname ) {
						wp_enqueue_style( 'penci-font-' . $fontname, '//fonts.googleapis.com/earlyaccess/' . esc_attr( $fontname ) . '.css', array(), PENCI_SOLEDAD_VERSION );
					}
				}
			}
			wp_enqueue_script( 'penci-header-builder', PENCI_SOLEDAD_URL . '/inc/builder/assets/js/penci-header-builder.js', array(), PENCI_SOLEDAD_VERSION, true );
		}
	}
);

function penci_footer_builder_content_id() {
	$footer_id      = '';
	$footer_site    = get_theme_mod( 'penci_footer_builder_layout' );
	$footer_home    = get_theme_mod( 'penci_footer_builder_layout_homepage' );
	$footer_archive = get_theme_mod( 'penci_footer_builder_layout_archive' );
	$footer_page    = get_theme_mod( 'penci_footer_builder_layout_page' );
	$footer_post    = get_theme_mod( 'penci_footer_builder_layout_post' );

	if ( ( is_home() || is_front_page() ) && $footer_home ) {
		$footer_home = get_page_by_path( $footer_home, OBJECT, 'penci-block' );
		if ( isset( $footer_home->ID ) && $footer_home->ID ) {
			$footer_id = $footer_home->ID;
		}
	} elseif ( is_archive() ) {

		if ( is_category() ) {
			$current_cat_id   = get_query_var( 'cat' );
			$category_options = get_option( "category_$current_cat_id" );

			if ( isset( $category_options['cat_footer'] ) && is_numeric( $category_options['cat_footer'] ) ) {
				$footer_id = $category_options['cat_footer'];
			} else if ( $footer_archive ) {
				$footer_archive = get_page_by_path( $footer_archive, OBJECT, 'penci-block' );
				if ( isset( $footer_archive->ID ) && $footer_archive->ID ) {
					$footer_id = $footer_archive->ID;
				}
			} elseif ( $footer_site ) {
				$footer_site = get_page_by_path( $footer_site, OBJECT, 'penci-block' );
				if ( isset( $footer_site->ID ) && $footer_site->ID ) {
					$footer_id = $footer_site->ID;
				}
			}

		} else if ( $footer_archive ) {
			$footer_archive = get_page_by_path( $footer_archive, OBJECT, 'penci-block' );
			if ( isset( $footer_archive->ID ) && $footer_archive->ID ) {
				$footer_id = $footer_archive->ID;
			}
		} elseif ( $footer_site ) {
			$footer_site = get_page_by_path( $footer_site, OBJECT, 'penci-block' );
			if ( isset( $footer_site->ID ) && $footer_site->ID ) {
				$footer_id = $footer_site->ID;
			}
		}

	} elseif ( is_singular() && ! is_page() ) {
		$post_config = penci_get_single_key( get_the_ID(), 'penci_footer_builder_layout' );
		if ( $post_config ) {
			$footer_id = $post_config;
		} elseif ( $footer_post ) {
			$footer_post = get_page_by_path( $footer_post, OBJECT, 'penci-block' );
			if ( isset( $footer_post->ID ) && $footer_post->ID ) {
				$footer_id = $footer_post->ID;
			}
		} elseif ( $footer_site ) {
			$footer_site = get_page_by_path( $footer_site, OBJECT, 'penci-block' );
			if ( isset( $footer_site->ID ) && $footer_site->ID ) {
				$footer_id = $footer_site->ID;
			}
		}
	} elseif ( is_page() ) {
		$page_config = get_post_meta( get_the_ID(), 'penci_pmeta_page_footer', true );
		if ( isset( $page_config['footer_builder_layout'] ) && $page_config['footer_builder_layout'] ) {
			$footer_id = $page_config['footer_builder_layout'];
		} elseif ( $footer_page ) {
			$footer_page = get_page_by_path( $footer_page, OBJECT, 'penci-block' );
			if ( isset( $footer_page->ID ) && $footer_page->ID ) {
				$footer_id = $footer_page->ID;
			}
		} elseif ( $footer_site ) {
			$footer_site = get_page_by_path( $footer_site, OBJECT, 'penci-block' );
			if ( isset( $footer_site->ID ) && $footer_site->ID ) {
				$footer_id = $footer_site->ID;
			}
		}
	} elseif ( $footer_site ) {
		$footer_site = get_page_by_path( $footer_site, OBJECT, 'penci-block' );
		if ( isset( $footer_site->ID ) && $footer_site->ID ) {
			$footer_id = $footer_site->ID;
		}
	}

	return $footer_id;
}


function penci_footer_builder_content() {
	if ( is_singular( 'penci-block' ) || ( isset( $_GET['layout_id'] ) && is_customize_preview() ) ) {
		return;
	}
	$content   = '';
	$footer_id = penci_footer_builder_content_id();
	if ( $footer_id ) {

		if ( get_theme_mod( 'penci_footer_delayed' ) ) {
			$content .= '<div class="pcfb-footer-delayed pc-content-delayed" data-class="pcfb-wrapper" data-type="block" data-id="' . $footer_id . '"></div>';
		} else {

			$footer_content = get_post( $footer_id );

			if ( $footer_content ) {
				if ( did_action( 'elementor/loaded' ) && Plugin::$instance->documents->get( $footer_id )->is_built_with_elementor() ) {

					$edit_block_link = add_query_arg( [
						'post'   => $footer_id,
						'action' => 'elementor'
					], site_url( 'wp-admin/edit.php' ) );

					if ( is_customize_preview() ) {
						$content .= '<a href="#" data-href="' . $edit_block_link . '" class="soledad-customizer-edit-link custom-link footer-custom-link"><button><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M13.89 3.39l2.71 2.72c.46.46.42 1.24.03 1.64l-8.01 8.02-5.56 1.16 1.16-5.58s7.6-7.63 7.99-8.03c.39-.39 1.22-.39 1.68.07zm-2.73 2.79l-5.59 5.61 1.11 1.11 5.54-5.65zm-2.97 8.23l5.58-5.6-1.07-1.08-5.59 5.6z"/></svg></button></a>';
					}
					$content .= '<div id="footer-section-container" class="pcfb-wrapper">';
					$content .= penci_get_elementor_content( $footer_id );
					$content .= '</div>';
				} else {
					$content .= '<div id="footer-section-container" class="pcfb-wrapper js-composer-content">';
					$content .= do_shortcode( $footer_content->post_content );

					$shortcodes_custom_css = get_post_meta( $footer_id, '_wpb_shortcodes_custom_css', true );

					$content .= '<style data-type="vc_shortcodes-custom-css">';
					if ( ! empty( $shortcodes_custom_css ) ) {
						$content .= $shortcodes_custom_css;
					}
					$content .= '</style>';
					$content .= '</div>';
				}
			}
		}
	}

	echo $content;
}

if ( ! function_exists( 'penci_builder_header_list' ) ) {
	function penci_builder_header_list() {
		$header_layout  = array( '' => '- Select -' );
		$header_layouts = get_posts(
			array(
				'post_type'      => 'penci_builder',
				'posts_per_page' => - 1,
			)
		);
		foreach ( $header_layouts as $header_builder ) {
			$header_layout[ $header_builder->post_name ] = $header_builder->post_title;
		}

		return $header_layout;
	}
}

if ( ! function_exists( 'penci_builder_block_list' ) ) {
	function penci_builder_block_list( $r = false ) {

		if ( $r ) {
			$builder_layout = array( '- Select -' => '' );
		} else {
			$builder_layout = array( '' => '- Select -' );
		}

		$builder_layouts = get_posts(
			array(
				'post_type'      => 'penci-block',
				'posts_per_page' => - 1,
			)
		);

		foreach ( $builder_layouts as $builder_builder ) {
			if ( $r ) {
				$builder_layout[ $builder_builder->post_title ] = $builder_builder->post_name;
			} else {
				$builder_layout[ $builder_builder->post_name ] = $builder_builder->post_title;
			}
		}

		return $builder_layout;
	}
}

function penci_is_active_header_builder() {
	return function_exists( 'penci_check_theme_mod' ) && penci_check_theme_mod() ? penci_check_theme_mod() : '';
}