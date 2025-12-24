<?php

use function Soledad\Table_Of_Contents\String\br2;

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class SoledadToc_Post {

	private $queriedObjectID;
	private $post;
	private $permalink;
	private $pages = array();
	private $headingLevels = array();
	private $excludedNodes = array();
	private $collision_collector = array();
	private $hasTOCItems = false;

	public function __construct( WP_Post $post, $apply_content_filter = true ) {

		$this->post            = $post;
		$this->permalink       = get_permalink( $post );
		$this->queriedObjectID = get_queried_object_id();

		$apply_content_filter = $this->apply_filter_status( $apply_content_filter );

		if ( $apply_content_filter ) {

			$this->applyContentFilter()->process();
		} else {

			$this->process();
		}
	}

	private function apply_filter_status( $apply_content_filter ) {
		$plugins = apply_filters(
			'penci_toc_apply_filter_status',
			array(
				'booster-extension/booster-extension.php',
				'divi-bodycommerce/divi-bodyshop-woocommerce.php',
				'social-pug/index.php',
				'modern-footnotes/modern-footnotes.php',
				'yet-another-stars-rating-premium/yet-another-stars-rating.php'
			)
		);

		foreach ( $plugins as $value ) {
			if ( in_array( $value, apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
				$apply_content_filter = false;
			}
		}

		$apply_content_filter = apply_filters( 'penci_toc_apply_filter_status_manually', $apply_content_filter );
		global $pencitoc_disable_the_content;
		if ( $pencitoc_disable_the_content ) {
			$apply_content_filter         = false;
			$pencitoc_disable_the_content = false;
		}

		return $apply_content_filter;
	}

	public static function get( $id ) {

		$post = get_post( $id );

		if ( ! $post instanceof WP_Post ) {

			return null;
		}

		return new static( $post );
	}

	private function process() {

		$this->processPages();

		return $this;
	}

	private function applyContentFilter() {

		/*
		 * Parses dynamic blocks out of post_content and re-renders them for gutenberg blocks.
		 */
		if ( function_exists( 'do_blocks' ) ) {
			$this->post->post_content = do_blocks( $this->post->post_content );
		} else {
			$this->post->post_content = $this->post->post_content;
		}

		add_filter( 'strip_shortcodes_tagnames', array( __CLASS__, 'stripShortcodes' ), 10, 2 );

		/*
		 * Ensure the PenciTOC content filter is not applied when running `the_content` filter.
		 */
		remove_filter( 'the_content', array( 'PenciTOC', 'the_content' ), 100 );

		$enable_memory_fix = get_option( 'penci_toc_enable_memory_fix' );
		if ( $enable_memory_fix ) {
			$this->post->post_content = $this->stripShortcodesButKeepContent( $this->post->post_content );
		}

		$this->post->post_content = apply_filters( 'the_content', strip_shortcodes( $this->post->post_content ) );

		add_filter( 'the_content', array(
			'PenciTOC',
			'the_content'
		), 100 );  // increased  priority to fix other plugin filter overwriting our changes

		remove_filter( 'strip_shortcodes_tagnames', array( __CLASS__, 'stripShortcodes' ) );

		return $this;
	}

	public static function stripShortcodes( $tags_to_remove, $content ) {

		/*
		 * Ensure the PenciTOC shortcodes are not processed when applying `the_content` filter
		 * otherwise an infinite loop may occur.
		 */
		$tags_to_remove = apply_filters(
			'penci_toc_strip_shortcodes_tagnames',
			array(
				'penci-toc',
				'penci-toc-widget-sticky',
				apply_filters( 'penci_toc_shortcode', 'toc' ),
			),
			$content
		);

		return $tags_to_remove;
	}

	protected function getCurrentPage() {

		global $wp_query;

		// Check to see if the global `$wp_query` var is an instance of WP_Query and that the get() method is callable.
		// If it is then when can simply use the get_query_var() function.
		if ( $wp_query instanceof WP_Query && is_callable( array( $wp_query, 'get' ) ) ) {

			$page = get_query_var( 'page', 1 );

			return 1 > $page ? 1 : $page;

			// If a theme or plugin broke the global `$wp_query` var, check to see if the $var was parsed and saved in $GLOBALS['wp_query']->query_vars.
		} elseif ( isset( $GLOBALS['wp_query']->query_vars['page'] ) ) {

			return $GLOBALS['wp_query']->query_vars['page'];

			// We should not reach this, but if we do, lets check the original parsed query vars in $GLOBALS['wp_the_query']->query_vars.
		} elseif ( isset( $GLOBALS['wp_the_query']->query_vars['page'] ) ) {

			return $GLOBALS['wp_the_query']->query_vars['page'];

			// Ok, if all else fails, check the $_REQUEST super global.
			//phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Reason : Nonce verification is not required here.
		} elseif ( isset( $_REQUEST['page'] ) ) {
			//phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Reason : Nonce verification is not required here.
			return $_REQUEST['page'];
		}

		// Finally, return the $default if it was supplied.
		return 1;
	}

	protected function getNumberOfPages() {

		return count( $this->pages );
	}

	protected function isMultipage() {

		return 1 < $this->getNumberOfPages();
	}

	private function processPages() {

		$content = apply_filters( 'penci_toc_modify_process_page_content', $this->post->post_content );

		// Fix for wordpress category pages showing wrong toc if they have description
		if ( is_category() ) {
			$cat_from_query = get_query_var( 'cat', null );
			if ( $cat_from_query ) {
				$category = get_category( $cat_from_query );
				if ( is_object( $category ) && property_exists( $category, 'description' ) && ! empty( $category->description ) ) {
					$content = $category->description;
				}
			}
		}

		if ( is_tax() || is_tag() ) {
			global $wp_query;
			$tax = $wp_query->get_queried_object();
			if ( is_object( $tax ) ) {
				$content = apply_filters( 'penci_toc_modify_taxonomy_content', $tax->description, $tax->term_id );
			}
		}

		if ( function_exists( 'is_product_category' ) && is_product_category() ) {
			$term_object = get_queried_object();
			if ( ! empty( $term_object->description ) ) {
				$content = $term_object->description;
			}
		}

		if ( in_array( 'js_composer_salient/js_composer.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {

			$pencitoc_post_id   = get_the_ID();
			$pencitoc_post_meta = get_option( 'penci-toc-post-meta-content', false );

			if ( ! empty( $pencitoc_post_meta ) && ! empty( $pencitoc_post_id ) && isset( $pencitoc_post_meta[ $pencitoc_post_id ] ) ) {

				if ( empty( $content ) ) {

					$content = $pencitoc_post_meta[ $pencitoc_post_id ];

				} else {

					$content .= $pencitoc_post_meta[ $pencitoc_post_id ];

				}
			}
		}

		$pages = array();

		$split = preg_split( '/<!--nextpage-->/msuU', $content );

		$page          = $first_page = 1;
		$totalHeadings = [];

		if ( is_array( $split ) ) {

			foreach ( $split as $content ) {

				$this->extractExcludedNodes( $page, $content );

				$totalHeadings[] = array(
					'headings' => $this->extractHeadings( $content, $page ),
					'content'  => $content,
				);

				$page ++;
			}

		}
		$pages[ $first_page ] = $totalHeadings;

		$this->pages = $pages;
	}

	public function getPages() {

		return $this->pages;
	}

	private function extractExcludedNodes( $page, $content ) {

		if ( ! class_exists( 'TagFilter' ) ) {
			require_once( PENCI_SOLEDAD_DIR . '/inc/toc/tag_filter.php' );
		}

		$tagFilterOptions = TagFilter::GetHTMLOptions();

		// Set custom TagFilter options.
		$tagFilterOptions['charset'] = get_option( 'blog_charset' );

		$html = TagFilter::Explode( $content, $tagFilterOptions );

		/**
		 * @param $selectors array  Array of classes/id selector to exclude from TOC.
		 * @param $content   string Post content.
		 *
		 * @since 2.0
		 *
		 */
		$selectors = apply_filters( 'penci_toc_exclude_by_selector', array( '.penci-toc-exclude-headings' ), $content );
		$selectors = ! is_array( $selectors ) ? [] : $selectors; // In case we get string instead of array
		$nodes     = $html->Find( implode( ',', $selectors ) );
		if ( isset( $nodes['ids'] ) ) {
			foreach ( $nodes['ids'] as $id ) {

				array_push( $this->excludedNodes, $html->Implode( $id, $tagFilterOptions ) );
			}
		}

		/**
		 * TagFilter::Implode() writes br tags as `<br>` while WP normalizes to `<br />`.
		 * Normalize `$eligibleContent` to match WP.
		 *
		 * @see wpautop()
		 */
	}

	private function extractHeadings( $content, $page = 1 ) {

		$matches = array();

		if ( in_array( 'elementor/elementor.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) || in_array( 'divi-machine/divi-machine.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) || 'Fortunato Pro' == apply_filters( 'current_theme', get_option( 'current_theme' ) ) || function_exists( 'koyfin_setup' ) ) {
			$content = apply_filters( 'penci_toc_extract_headings_content', $content );
		} else {
			$content = apply_filters( 'penci_toc_extract_headings_content', wptexturize( $content ) );
		}

		/**
		 * Lasso Product Compatibility
		 * @since 2.0.46
		 */
		$regEx = apply_filters( 'penci_toc_regex_filteration', '/(<h([1-6]{1})[^>]*>)(.*)<\/h\2>/msuU' );

		// get all headings
		// the html spec allows for a maximum of 6 heading depths
		if ( preg_match_all( $regEx, $content, $matches, PREG_SET_ORDER ) ) {

			$minimum = absint( get_theme_mod( 'penci_toc_start', 3 ) );

			$this->removeHeadingsFromExcludedNodes( $matches );
			$this->removeHeadings( $matches );
			$this->excludeHeadings( $matches );
			$this->removeEmptyHeadings( $matches );

			if ( count( $matches ) >= $minimum ) {

				$this->headingIDs( $matches );
				$this->addPage( $matches, $page );
				$this->hasTOCItems = true;

			} else {

				return array();
			}

		}

		return array_values( $matches ); // Rest the array index.
	}

	private function addPage( &$matches, $page ) {
		foreach ( $matches as $i => $match ) {
			$matches[ $i ]['page'] = $page;
		}

		return $matches;
	}

	private function inExcludedNode( $string ) {

		foreach ( $this->excludedNodes as $node ) {

			if ( empty( $node ) || empty( $string ) ) {

				return false;
			}

			if ( false !== strpos( $node, $string ) ) {

				return true;
			}
		}

		return false;
	}

	private function removeHeadingsFromExcludedNodes( &$matches ) {

		foreach ( $matches as $i => $match ) {

			$match[3] = apply_filters( 'penci_toc_filter_headings_from_exclude_nodes', $match[3] );

			if ( $this->inExcludedNode( "{$match[3]}</h$match[2]>" ) ) {

				unset( $matches[ $i ] );
			}
		}

		return $matches;
	}

	private function getHeadingLevels() {

		$levels = get_theme_mod( 'penci_toc_heading_levels', array( '2', '3', '4', '5', '6' ) );

		$this->headingLevels = $levels;

		return $this->headingLevels;
	}

	private function removeHeadings( &$matches ) {

		$levels = $this->getHeadingLevels();

		if ( count( $levels ) != 6 ) {

			$new_matches = array();

			foreach ( $matches as $i => $match ) {

				if ( in_array( $matches[ $i ][2], $levels ) ) {

					$new_matches[ $i ] = $matches[ $i ];
				}
			}

			$matches = $new_matches;
		}

		return $matches;
	}

	private function excludeHeadings( &$matches ) {

		$exclude = get_theme_mod( 'penci_toc_exclude', '' );

		if ( $exclude ) {

			$excluded_headings = explode( '|', $exclude );
			$excluded_count    = count( $excluded_headings );

			if ( $excluded_count > 0 ) {

				for ( $j = 0; $j < $excluded_count; $j ++ ) {

					$excluded_headings[ $j ] = preg_quote( $excluded_headings[ $j ] );

					// escape some regular expression characters
					// others: http://www.php.net/manual/en/regexp.reference.meta.php
					$excluded_headings[ $j ] = str_replace(
						array( '\*', '/', '%' ),
						array( '.*', '\/', '\%' ),
						trim( $excluded_headings[ $j ] )
					);
				}

				$new_matches = array();

				foreach ( $matches as $i => $match ) {

					$found = false;

					$against = html_entity_decode(
						( in_array( 'divi-machine/divi-machine.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) || 'Fortunato Pro' == apply_filters( 'current_theme', get_option( 'current_theme' ) ) ) ? wp_strip_all_tags( str_replace( array(
							"\r",
							"\n"
						), ' ', $matches[ $i ][0] ) ) : wptexturize( wp_strip_all_tags( str_replace( array(
							"\r",
							"\n"
						), ' ', $matches[ $i ][0] ) ) ),
						ENT_NOQUOTES,
						get_option( 'blog_charset' )
					);

					for ( $j = 0; $j < $excluded_count; $j ++ ) {

						// Since WP manipulates the post content it is required that the excluded header and
						// the actual header be manipulated similarly so a match can be made.
						$pattern = html_entity_decode(
							( in_array( 'divi-machine/divi-machine.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) || 'Fortunato Pro' == apply_filters( 'current_theme', get_option( 'current_theme' ) ) ) ? $excluded_headings[ $j ] : wptexturize( $excluded_headings[ $j ] ),
							ENT_NOQUOTES,
							get_option( 'blog_charset' )
						);
						$against = trim( $against );
						if ( preg_match( '/^' . $pattern . '$/imU', $against ) ) {

							$found = true;
							break;
						}
					}

					if ( ! $found ) {

						$new_matches[ $i ] = $matches[ $i ];
					}
				}

				$matches = $new_matches;
			}
		}

		return $matches;
	}

	private function headingIDs( &$matches ) {

		foreach ( $matches as $i => $match ) {

			$matches[ $i ]['id'] = $this->generateHeadingIDFromTitle( $matches[ $i ][0] );
		}

		return $matches;
	}

	private function generateHeadingIDFromTitle( $heading ) {

		$return = false;

		if ( $heading ) {
			$heading = apply_filters( 'penci_toc_url_anchor_target_before', $heading );
			// WP entity encodes the post content.
			$return = html_entity_decode( $heading, ENT_QUOTES, get_option( 'blog_charset' ) );
			$return = br2( $return, ' ' );
			$return = trim( wp_strip_all_tags( $return ) );

			// Convert accented characters to ASCII.
			$return = remove_accents( $return );

			// replace newlines with spaces (eg when headings are split over multiple lines)
			$return = str_replace( array( "\r", "\n", "\n\r", "\r\n" ), ' ', $return );

			// Remove `&amp;` and `&nbsp;` NOTE: in order to strip "hidden" `&nbsp;`,
			// title needs to be converted to HTML entities.
			// @link https://stackoverflow.com/a/21801444/5351316
			$return = htmlentities2( $return );
			$return = str_replace( array( '&amp;', '&nbsp;' ), ' ', $return );
			$return = str_replace( array( '&shy;' ), '', $return );                    // removed silent hypen
			$return = html_entity_decode( $return, ENT_QUOTES, get_option( 'blog_charset' ) );

			// remove non alphanumeric chars
			$return = preg_replace( '/[\x00-\x1F\x7F]*/u', '', $return );

			//for procesing shortcode in headings
			$return = apply_filters( 'penci_toc_table_heading_title_anchor', $return );
			// Reserved Characters.
			// * ' ( ) ; : @ & = + $ , / ? # [ ]
			$return = str_replace(
				array( '*', '\'', '(', ')', ';', '@', '&', '=', '+', '$', ',', '/', '?', '#', '[', ']' ),
				'',
				$return
			);

			// Unsafe Characters.
			// % { } | \ ^ ~ [ ] `
			$return = str_replace(
				array( '%', '{', '}', '|', '\\', '^', '~', '[', ']', '`' ),
				'',
				$return
			);

			// Special Characters.
			// $ - _ . + ! * ' ( ) ,
			// Special case for Apostrophes (’) which is causing TOC link to break in Block themes and CM Tooltip Glossary plugin #556
			$return = str_replace(
				array( '$', '.', '+', '!', '*', '\'', '(', ')', ',', '’' ),
				'',
				$return
			);

			// Dashes
			// Special Characters.
			// - (minus) - (dash) â€“ (en dash) â€” (em dash)
			$return = str_replace(
				array( '-', '-', 'â€“', 'â€”' ),
				'-',
				$return
			);

			// Curley quotes.
			// â€˜ (curly single open quote) â€™ (curly single close quote) â€œ (curly double open quote) â€ (curly double close quote)
			$return = str_replace(
				array( 'â€˜', 'â€™', 'â€œ', 'â€' ),
				'',
				$return
			);

			// AMP/Caching plugins seems to break URL with the following characters, so lets replace them.
			$return = str_replace( array( ':' ), '_', $return );

			// Convert space characters to an `_` (underscore).
			$return = preg_replace( '/\s+/', '_', $return );

			// Replace multiple `-` (hyphen) with a single `-` (hyphen).
			$return = preg_replace( '/-+/', '-', $return );

			// Replace multiple `_` (underscore) with a single `_` (underscore).
			$return = preg_replace( '/_+/', '_', $return );

			// Remove trailing `-` (hyphen) and `_` (underscore).
			$return = rtrim( $return, '-_' );

			/*
			 * Encode URI based on ECMA-262.
			 *
			 * Only required to support the jQuery smoothScroll library.
			 *
			 * @link https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/encodeURI#Description
			 * @link https://stackoverflow.com/a/19858404/5351316
			 */
			$return = preg_replace_callback(
				"{[^0-9a-z_.!~*'();,/?:@&=+$#-]}i",
				function ( $m ) {

					return sprintf( '%%%02X', ord( $m[0] ) );
				},
				$return
			);

			// lowercase everything?
			if ( get_theme_mod( 'penci_toc_lowercase' ) ) {

				$return = strtolower( $return );
			}

			// if blank, then prepend with the fragment prefix
			// blank anchors normally appear on sites that don't use the latin charset
			//@since  2.0.59
			if ( ! $return || true == get_theme_mod( 'penci_toc_all_fragment_prefix' ) ) {
				$return = ( get_theme_mod( 'penci_toc_fragment_prefix' ) ) ? get_theme_mod( 'penci_toc_fragment_prefix' ) : '_';
			}

			// hyphenate?
			if ( get_theme_mod( 'penci_toc_hyphenate' ) ) {

				$return = str_replace( '_', '-', $return );
				$return = preg_replace( '/-+/', '-', $return );
			}
		}

		if ( array_key_exists( $return, $this->collision_collector ) ) {

			$this->collision_collector[ $return ] ++;
			$return .= '-' . $this->collision_collector[ $return ];

		} else {

			$this->collision_collector[ $return ] = 1;
		}

		return apply_filters( 'penci_toc_url_anchor_target', $return, $heading );
	}

	private function removeEmptyHeadings( &$matches ) {

		$new_matches = array();
		foreach ( $matches as $i => $match ) {

			if ( trim( wp_strip_all_tags( $matches[ $i ][0] ) ) != false ) {

				$new_matches[ $i ] = $matches[ $i ];
			}
		}


		$matches = $new_matches;

		return $matches;
	}

	public function hasTOCItems() {

		return $this->hasTOCItems;
	}

	public function getHeadings( $page = null ) {

		$headings = array();

		if ( is_null( $page ) ) {

			$page = $this->getCurrentPage();
		}

		if ( ! empty( $this->pages ) || isset( $this->pages[ $page ] ) ) {

			$matches = $this->getHeadingsfromPageContents( $page );

			foreach ( $matches as $i => $match ) {

				$headings[] = str_replace(
					array(
						$matches[ $i ][1],                // start of heading
						'</h' . $matches[ $i ][2] . '>'   // end of heading
					),
					array(
						'>',
						'</h' . $matches[ $i ][2] . '>'
					),
					apply_filters( 'penci_toc_content_heading_title', $matches[ $i ][0] )
				);

			}
		}

		return $headings;
	}

	public function getTocTitleId( $page = null ) {
		$nav_data = array();
		if ( is_null( $page ) ) {
			$page = $this->getCurrentPage();
		}
		if ( ! empty( $this->pages ) || isset( $this->pages[ $page ] ) ) {
			$matches = $this->getHeadingsfromPageContents( $page );
			foreach ( $matches as $i => $match ) {
				$nav_data[ $i ]['title'] = wp_strip_all_tags( $matches[ $i ][0] );
				$nav_data[ $i ]['id']    = strtolower( str_replace( '_', '-', $matches[ $i ]['id'] ) );
			}
		}

		return $nav_data;
	}

	public function getHeadingsWithAnchors( $page = null ) {

		$headings = array();

		if ( is_null( $page ) ) {

			$page = $this->getCurrentPage();
		}

		if ( ! empty( $this->pages ) || isset( $this->pages[ $page ] ) ) {

			$matches = $this->getHeadingsfromPageContents( $page );
			$prefix  = get_theme_mod( 'penci_toc_prefix', 'penci' );
			$prefix  = $prefix ? $prefix . '-' : '';
			foreach ( $matches as $i => $match ) {

				$anchor = $matches[ $i ]['id'];

				$headings[] = str_replace(
					array(
						$matches[ $i ][1],                // start of heading
						'</h' . $matches[ $i ][2] . '>'   // end of heading
					),
					array(
						'><span class="penci-toc-section" id="' . $prefix . $anchor . '"></span>',
						'<span class="penci-toc-section-end"></span></h' . $matches[ $i ][2] . '>'
					),
					apply_filters( 'penci_toc_content_heading_title_anchor', $matches[ $i ][0] )
				);
			}
		}

		return $headings;
	}

	public function setContent( $content ) {

		$pages = array();
		$split = preg_split( '/<!--nextpage-->/msuU', $content );

		$page          = $first_page = 1;
		$totalHeadings = [];
		if ( is_array( $split ) ) {


			foreach ( $split as $content ) {

				$this->extractExcludedNodes( $page, $content );

				$totalHeadings[] = array(
					'headings' => $this->extractHeadings( $content, $page ),
					'content'  => $content,
				);

				$page ++;
			}

		}
		$pages[ $first_page ] = $totalHeadings;

		$this->pages = $pages;
	}

	private function getHeadingsfromPageContents( $page = 1 ) {
		$headings   = [];
		$first_page = 1;
		foreach ( $this->pages[ $first_page ] as $attributes ) {
			if ( isset( $attributes['headings'][0]['page'] ) && $page == $attributes['headings'][0]['page'] ) {
				foreach ( $attributes['headings'] as $heading ) {
					array_push( $headings, $heading );
				}
			}
		}

		return $headings;
	}

	private function createTOCParent( $prefix = "penci-toc", $toc_more = array() ) {
		$html       = '';
		$first_page = 1;
		$headings   = array();
		foreach ( $this->pages[ $first_page ] as $attribute ) {
			$headings = array_merge( $headings, $attribute['headings'] );
		}

		if ( ! empty( $headings ) ) {
			$html .= $this->createTOC( $first_page, $headings, $prefix, $toc_more );
		}

		return $html;
	}

	public function getTOCList( $prefix = "penci-toc", $options = [] ) {

		$html = '';

		$toc_more = isset( $options['view_more'] ) ? array( 'view_more' => $options['view_more'] ) : array();

		if ( isset( $options['hierarchy'] ) ) {
			$toc_more['hierarchy'] = true;
		} elseif ( isset( $options['no_hierarchy'] ) ) {
			$toc_more['no_hierarchy'] = true;
		}

		if ( isset( $options['collapse_hd'] ) ) {
			$toc_more['collapse_hd'] = true;
		} elseif ( isset( $options['no_collapse_hd'] ) ) {
			$toc_more['no_collapse_hd'] = true;
		}

		if ( $this->hasTOCItems ) {
			$html = $this->createTOCParent( $prefix, $toc_more );
			$html = apply_filters( 'penci_toc_add_custom_links', $html );
			$html = "<ul class='{$prefix}-list {$prefix}-list-level-1' >" . $html . "</ul>";
		}

		return $html;
	}

	public function getTOC( $options = [] ) {

		$class  = array( 'penci-toc-wrapper' );
		$max_lv = get_theme_mod( 'penci_toc_levels', 3 );
		$html   = '';

		if ( $this->hasTOCItems() ) {

			switch ( get_theme_mod( 'penci_toc_wrapping' ) ) {
				case 'left':
					$class[] = 'penci-toc-wrap-left';
					break;
				case 'right':
					$class[] = 'penci-toc-wrap-right';
					break;
				default:
					$class[] = 'penci-toc-default';
			}

			if ( get_theme_mod( 'penci_toc_show_hierarchy', true ) ) {
				$class[] = 'counter-hierarchy';
			} else {
				$class[] = 'counter-flat';
			}
			switch ( get_theme_mod( 'penci_toc_counter', 'decimal' ) ) {
				case 'numeric':
					$class[] = 'counter-numeric';
					break;
				case 'roman':
					$class[] = 'counter-roman';
					break;
				case 'decimal':
					$class[] = 'counter-decimal';
					break;
			}

			$class[] = 'penci-toc-container';
			$class[] = 'max-lv-' . $max_lv;
			$class[] = get_theme_mod( 'penci_toc_style' );
			$class[] = get_theme_mod( 'penci_toc_visibility' ) ? 'dis-toggle' : 'enable-toggle';

			$class        = array_filter( $class );
			$class        = array_map( 'trim', $class );
			$class        = array_map( 'sanitize_html_class', $class );
			$sticky_class = 'sticky-' . get_theme_mod( 'penci_toc_sticky', 'left' );

			$html .= '<div class="penci-toc-container-wrapper ' . $sticky_class . '"><div id="penci-toc-container" class="' . implode( ' ', $class ) . '">' . PHP_EOL;

			$html .= $this->get_css_based_toc_heading( $options );

			ob_start();
			do_action( 'penci_toc_before' );
			$html .= ob_get_clean();

			$html .= '<nav class="penci-toc">' . $this->getTOCList( 'penci-toc', $options ) . '</nav>';

			ob_start();
			do_action( 'penci_toc_after' );
			$html .= ob_get_clean();

			$html .= '</div></div>' . PHP_EOL;

		}

		return apply_filters( 'pencitoc_autoinsert_final_toc_html', $html );
	}

	//css based heaing function
	private function get_css_based_toc_heading( $options ) {

		$toc_title = penci_get_setting( 'penci_toc_heading_text' );
		if ( strpos( $toc_title, '%PAGE_TITLE%' ) !== false ) {
			$toc_title = str_replace( '%PAGE_TITLE%', get_the_title(), $toc_title );
		}
		if ( strpos( $toc_title, '%PAGE_NAME%' ) !== false ) {
			$toc_title = str_replace( '%PAGE_NAME%', get_the_title(), $toc_title );
		}

		$html = '<div class="penci-toc-head penci-toc-title-container">' . PHP_EOL;
		if ( penci_get_setting( 'penci_toc_heading_text' ) ) {
			$html .= '<p class="penci-toc-title">' . esc_html__( htmlentities( $toc_title, ENT_COMPAT, 'UTF-8' ), 'soledad' ) . '</p>' . PHP_EOL;
		}
		$html .= '<span class="penci-toc-title-toggle">';
		if ( ! get_theme_mod( 'penci_toc_visibility' ) ) {
			$html .= '<a class="penci-toc-toggle penci-toc-title-toggle" style="display: none;"></a>';
		}
		$html .= '</span>';
		$html .= '</div>' . PHP_EOL;

		return $html;
	}

	public function toc() {
		//phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Output is escaped in getTOC()
		echo $this->getTOC();
	}

	private function createTOC( $page, $matches, $prefix = "penci-toc", $toc_more = array() ) {

		// Whether or not the TOC should be built flat or hierarchical.
		$hierarchical = get_theme_mod( 'penci_toc_show_hierarchy', true );

		if ( isset( $toc_more['hierarchy'] ) ) {
			$hierarchical = true;
		} elseif ( isset( $toc_more['no_hierarchy'] ) ) {
			$hierarchical = false;
		}

		$html = $toc_type = $collapse_status = '';

		if ( isset( $toc_more['collapse_hd'] ) ) {
			$collapse_status = true;
		} elseif ( isset( $toc_more['no_collapse_hd'] ) ) {
			$collapse_status = false;
		}

		$count_matches = is_array( $matches ) ? count( $matches ) : '';

		$toc_type = get_theme_mod( 'penci_toc_toc_loading' );

		if ( $hierarchical ) {

			//To not show view more in Hierarchy
			unset( $toc_more['view_more'] );

			$current_depth      = 100;    // headings can't be larger than h6 but 100 as a default to be sure
			$numbered_items     = array();
			$numbered_items_min = null;

			// find the minimum heading to establish our baseline
			foreach ( $matches as $i => $match ) {
				if ( $current_depth > $matches[ $i ][2] ) {
					$current_depth = (int) $matches[ $i ][2];
				}
			}

			$numbered_items[ $current_depth ] = 0;
			$numbered_items_min               = $current_depth;

			foreach ( $matches as $i => $match ) {

				$level = $matches[ $i ][2];
				$count = $i + 1;

				if ( $current_depth == (int) $matches[ $i ][2] ) {

					$html .= "<li class='{$prefix}-page-" . $page . " {$prefix}-heading-level-" . $current_depth . "'>";
				}

				// start lists
				if ( $current_depth != (int) $matches[ $i ][2] ) {

					for ( $current_depth; $current_depth < (int) $matches[ $i ][2]; $current_depth ++ ) {

						$numbered_items[ $current_depth + 1 ] = 0;
						//Hide Level 4 Headings
						$sub_active = '';
						if ( $level > 3 ) {
							$sub_active = apply_filters( 'penci_toc_hierarchy_js_add_attr', $sub_active, $collapse_status );
						}
						$html .= "<ul class='{$prefix}-list-level-" . $level . "' " . $sub_active . "><li class='{$prefix}-heading-level-" . $level . "'>";
					}
				}

				$title = isset( $matches[ $i ]['alternate'] ) ? $matches[ $i ]['alternate'] : $matches[ $i ][0];
				//check for line break
				if ( ! get_theme_mod( 'penci_toc_prsrv_line_brk' ) ) {
					$title = br2( $title, ' ' );
				}
				$title = penci_toc_wp_strip_all_tags( apply_filters( 'penci_toc_title', $title ) );

				$html .= $this->createTOCItemAnchor( $matches[ $i ]['page'], $matches[ $i ]['id'], $title, $count );

				// end lists
				if ( $i != count( $matches ) - 1 ) {

					if ( $current_depth > (int) $matches[ $i + 1 ][2] ) {

						for ( $current_depth; $current_depth > (int) $matches[ $i + 1 ][2]; $current_depth -- ) {

							$html                             .= '</li></ul>';
							$numbered_items[ $current_depth ] = 0;
						}
					}

					if ( $current_depth == (int) $matches[ $i + 1 ][2] ) {

						$html .= '</li>';
					}

				} else {

					// this is the last item, make sure we close off all tags
					for ( $current_depth; $current_depth >= $numbered_items_min; $current_depth -- ) {

						$html .= '</li>';

						if ( $current_depth != $numbered_items_min ) {
							$html .= '</ul>';
						}
					}
				}
			}

		} else {
			if ( is_array( $matches ) ) {
				foreach ( $matches as $i => $match ) {
					$count = $i + 1;
					$title = isset( $matches[ $i ]['alternate'] ) ? $matches[ $i ]['alternate'] : $matches[ $i ][0];
					$title = penci_toc_wp_strip_all_tags( apply_filters( 'penci_toc_title', $title ) );
					$html  .= "<li class='{$prefix}-page-" . $page . "'>";
					$html  .= $this->createTOCItemAnchor( $matches[ $i ]['page'], $matches[ $i ]['id'], $title, $count );
					$html  .= '</li>';
				}
			}
		}

		$html = apply_filters( 'penci_toc_pro_html_modifier', $html, $toc_more, $count_matches, $toc_type );

		return do_shortcode( $html );
	}

	private function createTOCItemAnchor( $page, $id, $title, $count ) {
		if ( get_theme_mod( 'penci_toc_remove_special_chars_from_title' ) ) {
			$title = str_replace( ':', '', $title );
		}

		$anch_name = 'href';
		if ( get_theme_mod( 'penci_toc_loading' ) == 'js' && get_theme_mod( 'penci_toc_smooth_scroll' ) && get_theme_mod( 'penci_toc_avoid_anch_jump' ) ) {
			$anch_name = 'href="#" data-href';
		}

		$rel = get_theme_mod( 'penci_toc_nofollow_link' ) ? ' rel="nofollow" ' : ' ';

		return sprintf(
			'<a' . $rel . 'class="penci-toc-link penci-toc-heading-' . $count . '" ' . $anch_name . '="%1$s" title="%2$s">%3$s</a>',
			esc_url( $this->createTOCItemURL( $id, $page ) ),
			esc_attr( wp_strip_all_tags( $title ) ),
			$title
		);
	}

	private function createTOCItemURL( $id, $page ) {

		$prefix = get_theme_mod( 'penci_toc_prefix', 'penci' );
		$prefix = $prefix ? $prefix . '-' : '';

		$current_post = $this->post->ID === $this->queriedObjectID;
		$current_page = $this->getCurrentPage();

		$anch_url = $this->permalink;

		if ( get_theme_mod( 'penci_toc_ajax_load_more' ) && isset( $_SERVER['HTTP_X_REQUESTED_WITH'] ) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' ) {
			$anch_url = $_SERVER['HTTP_REFERER'];
		}

		if ( $page === $current_page && $current_post ) {

			return ( get_theme_mod( 'penci_toc_add_request_uri' ) ? $_SERVER['REQUEST_URI'] : '' ) . '#' . $prefix . $id;

		} elseif ( 1 === $page ) {
			// Fix for wrong links on TOC on Wordpress category page
			if ( is_category() || is_tax() || is_tag() || ( function_exists( 'is_product_category' ) && is_product_category() ) ) {
				return '#' . $id;
			}

			return trailingslashit( $anch_url ) . '#' . $prefix . $id;

		}

		return trailingslashit( $anch_url ) . $page . '/#' . $prefix . $id;
	}

	private function stripShortcodesButKeepContent( $content ) {
		// Regex pattern to match the specific shortcodes
		$shortcodes = apply_filters( 'penci_toc_strip_shortcodes_with_inner_content', [] );
		if ( ! empty( $shortcodes ) && is_array( $shortcodes ) ) {

			$pattern = '/\[(' . implode( '|', $shortcodes ) . ')(?:\s[^\]]*)?\](.*?)\[\/\1\]|\[(' . implode( '|', $shortcodes ) . ')(?:\s[^\]]*)?\/?\]/s';

			// Function to recursively strip shortcodes
			while ( preg_match( $pattern, $content ) ) {
				$content = preg_replace_callback( $pattern, function ( $matches ) {
					if ( isset( $matches[2] ) ) {
						return $matches[2]; // Keep content inside shortcode
					}

					return ''; // Remove self-closing shortcode
				}, $content );
			}

		}

		return $content;
	}
}