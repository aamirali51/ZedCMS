<?php

use function Soledad\Table_Of_Contents\String\mb_find_replace;

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if ( ! class_exists( 'PenciTOC' ) ) {
	/**
	 * Class PenciTOC
	 */
	final class PenciTOC {
		private static $instance;
		private static $store = array();

		public function __construct() {
			/* Do nothing here */
		}

		public static function instance() {
			global $post;
			if ( ! self::is_eligible( $post ) || ! is_singular() ) {
				return false;
			}
			if ( ! isset( self::$instance ) && ! ( self::$instance instanceof self ) ) {
				self::$instance = new self();
				self::includes();
				self::hooks();
			}

			return self::$instance;
		}

		private static function includes() {
			require_once PENCI_SOLEDAD_DIR . '/inc/toc/helper.php';
			require_once PENCI_SOLEDAD_DIR . '/inc/toc/helper_new.php';
			require_once PENCI_SOLEDAD_DIR . '/inc/toc/progress.php';
		}

		private static function hooks() {
			add_action( 'wp_enqueue_scripts', array( __CLASS__, 'enqueueScripts' ) );
			// Run after shortcodes are interpreted (priority 10).
			add_filter( 'the_content', array( __CLASS__, 'the_content' ), 100 );
			add_shortcode( 'penci-toc', array( __CLASS__, 'shortcode' ) );
			add_shortcode( apply_filters( 'penci_toc_shortcode', 'toc' ), array( __CLASS__, 'shortcode' ) );
			add_action( 'soledad_theme/custom_css', array( __CLASS__, 'penci_toc_style' ) );
		}

		public static function enqueueScripts() {
			$js_vars = array();
			wp_enqueue_script( 'js-cookies' );
			wp_register_script( 'penci-smoothscroll2', PENCI_SOLEDAD_URL . '/js/smooth-scroll.min.js', array( 'jquery' ), PENCI_SOLEDAD_VERSION, true );
			wp_register_script(
				'penci-toc-lib',
				PENCI_SOLEDAD_URL . '/inc/toc/penci-toc.js',
				array(
					'jquery',
					'js-cookies',
					'penci-smoothscroll2',
				),
				PENCI_SOLEDAD_VERSION,
				true
			);
			if ( get_theme_mod( 'penci_toc_smooth_scroll', true ) ) {
				$js_vars['smooth_scroll'] = true;
			}
			if ( penci_get_setting( 'penci_toc_heading_text' ) && ! get_theme_mod( 'penci_toc_visibility' ) ) {
				$width                                 = get_theme_mod( 'penci_toc_styles_width', '320' ) . 'px';
				$js_vars['visibility_hide_by_default'] = get_theme_mod( 'penci_toc_visibility_hide_by_default' );
				$js_vars['width']                      = esc_js( $width );
			}
			$prefix                   = get_theme_mod( 'penci_toc_prefix', 'penci' );
			$js_vars['prefix']        = (string) $prefix ? $prefix : '';
			$offset                   = wp_is_mobile() ? get_theme_mod( 'penci_toc_mobile_smooth_scroll_offset', 90 ) : get_theme_mod( 'penci_toc_smooth_scroll_offset', 120 );
			$js_vars['scroll_offset'] = esc_js( $offset );
			if ( ! empty( $js_vars ) ) {
				wp_enqueue_script( 'penci-toc-lib' );
				wp_localize_script( 'penci-toc-lib', 'PenciTOC', $js_vars );
			}
		}

		public static function array_search_deep( $search, array $array, $mode = 'value' ) {
			foreach ( new RecursiveIteratorIterator( new RecursiveArrayIterator( $array ) ) as $key => $value ) {
				if ( $search === ${${'mode'}} ) {
					return true;
				}
			}

			return false;
		}

		public static function is_eligible( $post ) {

			if ( empty( $post ) || ! $post instanceof WP_Post ) {
				return false;
			}

			$return = false;

			if ( has_shortcode( $post->post_content, apply_filters( 'penci_toc_shortcode', 'toc' ) ) || has_shortcode( $post->post_content, 'penci-toc' ) ) {
				$return = true;
			}
			
			if ( is_front_page() ) {
				$return = false;
			}

			if ( is_singular() ) {

				$type    = get_post_type( $post->ID );
				$enabled = get_theme_mod( 'penci_toc_enabled_post_types' ) && is_array( get_theme_mod( 'penci_toc_enabled_post_types' ) ) && in_array( $type, get_theme_mod( 'penci_toc_enabled_post_types', array() ), true );

				if ( $enabled && is_singular( $type ) ) {
					$return = true;
				}

				if ( 'yes' == get_post_meta( $post->ID, 'penci_toc_enable', true ) ) {
					$return = true;
				}

				if ( 'no' == get_post_meta( $post->ID, 'penci_toc_enable', true ) ) {
					$return = false;
				}

				
				$elementor_data = get_post_meta( $post->ID, '_elementor_data', 'true' );
				if ( strpos($elementor_data, '[penci-toc') !== false ) {
					$return = true;
				}
			
			}

			return $return;
		}

		public static function get( $id ) {
			$post = null;
			if ( isset( self::$store[ $id ] ) && self::$store[ $id ] instanceof SoledadToc_Post ) {
				$post = self::$store[ $id ];
			} else {
				$post = SoledadToc_Post::get( get_the_ID() );
				if ( $post instanceof SoledadToc_Post ) {
					self::$store[ $id ] = $post;
				}
			}

			return $post;
		}

		public static function shortcode( $atts, $content, $tag ) {
			static $run = true;
			$html = '';
			if ( $run ) {
				$post = self::get( get_the_ID() );
				if ( ! $post instanceof SoledadToc_Post ) {
					return $content;
				}
				$html = $post->getTOC();
				$run  = false;
			}

			return $html;
		}

		private static function maybe_apply_the_content_filter() {

			$apply = true;

			global $wp_current_filter;

			// Do not execute if root current filter is one of those in the array.
			if ( isset( $wp_current_filter[0] ) && in_array( $wp_current_filter[0], array(
					'get_the_excerpt',
					'init',
					'wp_head'
				), true ) ) {

				$apply = false;
			}

			// bail if feed, search or archive
			if ( is_feed() || is_search() || is_archive() ) {

				if ( ( true == get_theme_mod( 'penci_toc_include_category', false ) && is_category() ) || ( true == get_theme_mod( 'penci_toc_include_tag', false ) && is_tag() ) || ( true == get_theme_mod( 'penci_toc_include_product_category', false ) && ( function_exists( 'is_product_category' ) && is_product_category() ) ) || ( true == get_theme_mod( 'penci_toc_include_custom_tax', false ) && is_tax() ) ) {

					$apply = true;
				} else {
					$apply = false;
				}
			}

			if ( function_exists( 'get_current_screen' ) ) {
				$my_current_screen = get_current_screen();
				if ( isset( $my_current_screen->id ) ) {

					if ( $my_current_screen->id == 'edit-post' ) {
						$apply = false;
					}
				}

				if ( is_object( $my_current_screen ) && method_exists( $my_current_screen, 'is_block_editor' ) && $my_current_screen->is_block_editor() ) {
					$apply = false;
				}
			}

			if ( ! empty( array_intersect( $wp_current_filter, array( 'get_the_excerpt', 'init', 'wp_head' ) ) ) ) {
				$apply = false;
			}

			return apply_filters( 'penci_toc_maybe_apply_the_content_filter', $apply );
		}

		public static function the_content( $content ) {

			if ( function_exists( 'post_password_required' ) ) {
				if ( post_password_required() ) {
					return $content;
				}
			}

			$maybeApplyFilter = self::maybe_apply_the_content_filter();
			$content          = apply_filters( 'pencitoc_modify_the_content', $content );


			if ( ! $maybeApplyFilter ) {

				return $content;
			}
			// Fix for getting current page id when sub-queries are used on the page
			$penci_toc_current_post_id = function_exists( 'get_queried_object_id' ) ? get_queried_object_id() : get_the_ID();

			// Bail if post not eligible and widget is not active.
			if ( apply_filters( 'current_theme', get_option( 'current_theme' ) ) == 'MicrojobEngine Child' || class_exists( 'Timber' ) ) {
				$isEligible = self::is_eligible( get_post( $penci_toc_current_post_id ) );
			} else {
				$isEligible = self::is_eligible( get_post() );
			}

			$isEligible = apply_filters( 'pencitoc_do_shortcode', $isEligible );

			if ( $isEligible ) {
				if ( ! penci_toc_auto_device_target_status() ) {
					$isEligible = false;
				}
			}

			if ( ! $isEligible ) {
				return $content;
			}

			if ( apply_filters( 'current_theme', get_option( 'current_theme' ) ) == 'MicrojobEngine Child' || class_exists( 'Timber' ) ) {
				$post = self::get( $penci_toc_current_post_id );
			} else {
				$post = self::get( get_the_ID() );
			}


			if ( ! $post instanceof SoledadToc_Post ) {

				return $content;
			}
			//Bail if no headings found.
			if ( ! $post->hasTOCItems() && get_theme_mod( 'penci_toc_no_heading_text' ) != 1 ) {

				return $content;
			}

			$find     = $post->getHeadings();
			$replace  = $post->getHeadingsWithAnchors();
			$toc      = $post->getTOC();
			$headings = implode( PHP_EOL, $find );
			$anchors  = implode( PHP_EOL, $replace );

			$headingRows = count( $find ) + 1;
			$anchorRows  = count( $replace ) + 1;

			$style = "background-image: linear-gradient(#F1F1F1 50%, #F9F9F9 50%); background-size: 100% 4em; border: 1px solid #CCC; font-family: monospace; font-size: 1em; line-height: 2em; margin: 0 auto; overflow: auto; padding: 0 8px 4px; white-space: nowrap; width: 100%;";

			// If shortcode used or post not eligible, return content with anchored headings.
			if ( strpos( $content, 'penci-toc-container' ) || ! $isEligible ) {


				return mb_find_replace( $find, $replace, $content );
			}

			$position = get_theme_mod( 'penci_toc_position', 'top' );


			switch ( $position ) {

				case 'top':
					$content = $toc . mb_find_replace( $find, $replace, $content );
					break;

				case 'bottom':
					$content = mb_find_replace( $find, $replace, $content ) . $toc;
					break;

				case 'after':
					$replace[0] = $replace[0] . $toc;
					$content    = mb_find_replace( $find, $replace, $content );
					break;
				case 'afterpara':
					$exc_blkqt = get_theme_mod( 'penci_toc_blockqoute_checkbox' );
					//blockqoute
					$blockquotes = array();
					if ( $exc_blkqt == true ) {
						preg_match_all( "/<blockquote(.*?)>(.*?)<\/blockquote>/s", $content, $blockquotes );
						if ( ! empty( $blockquotes ) ) {
							$content = penci_toc_para_blockquote_replace( $blockquotes, $content, 1 );
						}
					}
					$content = insertElementByPTag( mb_find_replace( $find, $replace, $content ), $toc );
					//add blockqoute back
					if ( $exc_blkqt == true && ! empty( $blockquotes ) ) {
						$content = penci_toc_para_blockquote_replace( $blockquotes, $content, 2 );
					}
					break;
				case 'aftercustompara':
					$exc_blkqt = get_theme_mod( 'penci_toc_blockqoute_checkbox' );
					//blockqoute
					$blockquotes = array();
					if ( $exc_blkqt == true ) {
						preg_match_all( "/<blockquote(.*?)>(.*?)<\/blockquote>/s", $content, $blockquotes );
						if ( ! empty( $blockquotes ) ) {
							$content = penci_toc_para_blockquote_replace( $blockquotes, $content, 1 );
						}
					}
					$paragraph_index = get_theme_mod( 'penci_toc_custom_para_number' );
					if ( $paragraph_index == 1 ) {
						$content = insertElementByPTag( mb_find_replace( $find, $replace, $content ), $toc );
					} else if ( $paragraph_index > 1 ) {
						$closing_p  = '</p>';
						$paragraphs = explode( $closing_p, $content );
						if ( ! empty( $paragraphs ) && is_array( $paragraphs ) && $paragraph_index <= count( $paragraphs ) ) {
							$paragraph_id = $paragraph_index;
							foreach ( $paragraphs as $index => $paragraph ) {
								if ( trim( $paragraph ) ) {
									$paragraphs[ $index ] .= $closing_p;
								}
								$pos = strpos( $paragraph, '<p' );
								if ( $paragraph_id == $index + 1 && $pos !== false ) {
									$paragraphs[ $index ] .= $toc;
								}
							}
							$content = implode( '', $paragraphs );
							$content = mb_find_replace( $find, $replace, $content );
						} else {
							$content = insertElementByPTag( mb_find_replace( $find, $replace, $content ), $toc );
						}
					} else {
						$content = insertElementByPTag( mb_find_replace( $find, $replace, $content ), $toc );
					}
					//add blockqoute back
					if ( $exc_blkqt == true && ! empty( $blockquotes ) ) {
						$content = penci_toc_para_blockquote_replace( $blockquotes, $content, 2 );
					}
					break;
				case 'aftercustomimg':
					$img_index = get_theme_mod( 'penci_toc_custom_img_number' );
					if ( $img_index == 1 ) {
						$content = insertElementByImgTag( mb_find_replace( $find, $replace, $content ), $toc );
					} else if ( $img_index > 1 ) {
						$closing_img = '</figure>';
						$imgs        = explode( $closing_img, $content );
						if ( ! empty( $imgs ) && is_array( $imgs ) && $img_index <= count( $imgs ) ) {
							$img_id = $img_index;
							foreach ( $imgs as $index => $img ) {
								if ( trim( $img ) ) {
									$imgs[ $index ] .= $closing_img;
								}
								$pos = strpos( $img, '<figure' );
								if ( $img_id == $index + 1 && $pos !== false ) {
									$imgs[ $index ] .= $toc;
								}
							}
							$content = implode( '', $imgs );
							$content = mb_find_replace( $find, $replace, $content );
						} else {
							$content = insertElementByImgTag( mb_find_replace( $find, $replace, $content ), $toc );
						}
					} else {
						$content = insertElementByImgTag( mb_find_replace( $find, $replace, $content ), $toc );
					}
					break;
				case 'before':
				default:
					$content = mb_find_replace( $find, $replace, $content );

					/**
					 * @link https://wordpress.org/support/topic/php-notice-undefined-offset-8/
					 */
					if ( ! array_key_exists( 0, $replace ) ) {
						break;
					}

					$pattern = '`<h[1-6]{1}[^>]*' . preg_quote( $replace[0], '`' ) . '`msuU';
					$result  = preg_match( $pattern, $content, $matches );

					/*
					 * Try to place TOC before the first heading found in eligible heading, failing that,
					 * insert TOC at top of content.
					 */
					if ( 1 === $result ) {


						$start   = strpos( $content, $matches[0] );
						$content = substr_replace( $content, $toc, $start, 0 );

					}
			}

			return $content;

		}

		public static function penci_toc_style() {
			$css       = '';
			$toc_style = array(
				'penci_toc_styles_width'          => array( 'max-width' => '.penci-toc-wrapper,.penci-toc-wrapper.penci-toc-default' ),
				'penci_toc_styles_swidth'         => array( 'max-width' => '.penci-sticky-toc' ),
				'penci_toc_heading_mfs'           => array( 'font-size' => '.penci-toc-wrapper .penci-toc-title' ),
				'penci_toc_heading_fs'            => array( 'font-size' => '.penci-toc-wrapper .penci-toc-title' ),
				'penci_toc_l1_mfs'                => array( 'font-size' => '.post-entry .penci-toc ul a,.penci-toc ul a' ),
				'penci_toc_l1_fs'                 => array( 'font-size' => '.post-entry .penci-toc ul a,.penci-toc ul a' ),
				'penci_toc_l2_mfs'                => array( 'font-size' => '.post-entry .penci-toc ul ul a,.penci-toc ul ul a' ),
				'penci_toc_l2_fs'                 => array( 'font-size' => '.post-entry .penci-toc ul ul a,.penci-toc ul ul a' ),
				'penci_toc_heading_color'         => array( 'color' => '.post-entry .penci-toc-wrapper .penci-toc-title,.penci-toc-wrapper .penci-toc-title' ),
				'penci_toc_l1_color'              => array( 'color' => '.post-entry .penci-toc ul a,.penci-toc ul a' ),
				'penci_toc_l1_hcolor'             => array( 'color' => '.post-entry .penci-toc ul a:hover,.penci-toc ul a:hover' ),
				'penci_toc_l2_color'              => array( 'color' => '.post-entry .penci-toc ul ul a,.penci-toc ul ul a' ),
				'penci_toc_l2_hcolor'             => array( 'color' => '.post-entry .penci-toc ul ul a:hover,.penci-toc ul ul a:hover' ),
				'penci_toc_bd_color'              => array( 'border-color' => '.penci-toc-wrapper,.penci-toc-wrapper .penci-toc > ul,.penci-toc ul li a' ),
				'penci_toc_bg_color'              => array( 'background-color' => '.penci-toc-wrapper' ),
				'penci_toc_tgbtn_color'           => array( 'color' => '.post-entry .penci-toc-wrapper .penci-toc-title-toggle' ),
				'penci_toc_tgbtn_hcolor'          => array( 'color' => '.post-entry .penci-toc-wrapper .penci-toc-title-toggle:hover' ),
				'penci_toc_tgbtn_bgcolor'         => array( 'background-color' => '.penci-toc-wrapper .penci-toc-title-toggle' ),
				'penci_toc_tgbtn_hbgcolor'        => array( 'background-color' => '.penci-toc-wrapper .penci-toc-title-toggle:hover' ),

				// sticky
				'penci_toc_sticky_heading_color'  => array( 'color' => '.penci-toc-wrapper.penci-sticky-toc .penci-toc-title' ),
				'penci_toc_sticky_l1_color'       => array( 'color' => '.penci-sticky-toc .penci-toc ul a' ),
				'penci_toc_sticky_l1_hcolor'      => array( 'color' => '.penci-sticky-toc .penci-toc ul a:hover' ),
				'penci_toc_sticky_l2_color'       => array( 'color' => '.penci-sticky-toc .penci-toc ul ul a' ),
				'penci_toc_sticky_l2_hcolor'      => array( 'color' => '.penci-sticky-toc .penci-toc ul ul a:hover' ),
				'penci_toc_sticky_bd_color'       => array( 'border-color' => '.penci-sticky-toc .penci-toc-wrapper,.penci-toc-wrapper .penci-toc > ul,.penci-toc ul li a' ),
				'penci_toc_sticky_bg_color'       => array( 'background-color' => '.penci-sticky-toc .penci-toc-wrapper' ),
				'penci_toc_sticky_tgbtn_color'    => array( 'color' => '.penci-sticky-toc .penci-toc-wrapper .penci-toc-title-toggle' ),
				'penci_toc_sticky_tgbtn_hcolor'   => array( 'color' => '.penci-sticky-toc .penci-toc-wrapper .penci-toc-title-toggle:hover' ),
				'penci_toc_sticky_tgbtn_bgcolor'  => array( 'background-color' => '.penci-sticky-toc .penci-toc-wrapper .penci-toc-title-toggle' ),
				'penci_toc_sticky_tgbtn_hbgcolor' => array( 'background-color' => '.penci-sticky-toc .penci-toc-wrapper .penci-toc-title-toggle:hover' ),

				// Mobile Sticky Button
				'penci_toc_msticky_w_bgcolor'     => array( 'background-color' => '.penci-toc-wrapper.hide-table' ),
				'penci_toc_msticky_w_bdcolor'     => array( 'border-color' => '.penci-toc-wrapper.hide-table' ),
				'penci_toc_msticky_btn_bgcolor'   => array( 'background-color' => '.penci-toc-wrapper.hide-table .sticky-toggle' ),
				'penci_toc_msticky_btn_bghcolor'  => array( 'background-color' => '.penci-toc-wrapper.hide-table .sticky-toggle:hover' ),
				'penci_toc_msticky_btn_color'     => array( 'color' => '.penci-toc-wrapper.hide-table .sticky-toggle' ),
				'penci_toc_msticky_btn_hcolor'    => array( 'color' => '.penci-toc-wrapper.hide-table .sticky-toggle:hover' ),

			);
			foreach ( $toc_style as $value => $props ) {
				$before = $after = '';
				if ( strpos( $value, 'm' ) !== false ) {
					$before = '@media only screen and (max-width: 767px){';
					$after  = '}';
				}
				$val = get_theme_mod( $value );
				if ( $val ) {
					foreach ( $props as $prop => $selector ) {
						$prefix = 'font-size' == $prop || 'max-width' == $prop ? 'px' : '';
						$css    .= $before . $selector . '{' . $prop . ':' . $val . $prefix . '}' . $after;
					}
				}
			}

			echo $css;
		}
	}

	function penciTOC() {
		return penciTOC::instance();
	}

	add_action( 'wp', 'penciTOC' );
}
