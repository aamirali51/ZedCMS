<?php

class Penci_Link_Options {

	private $content_placeholders = array();

	public function __construct() {

		if ( get_theme_mod( 'penci_linkmg_external_link_enable' ) || get_theme_mod( 'penci_linkmg_interal_link_enable' ) || get_theme_mod( 'penci_linkmg_excluded_link_enable' ) ) {
			require_once 'link_html.php';
			$this->init();
		}
	}

	protected function init() {
		$filter_hooks = [
			'the_title',
			'the_content',
			'the_excerpt',
			'get_the_excerpt',
			'comment_text',
			'comment_excerpt'
		];
		foreach ( $filter_hooks as $hook ) {
			add_filter( $hook, [ $this, 'scan' ], 10000000000 );
		}
	}


	public function scan( $content ) {
		/**
		 * Filter whether the plugin settings will be applied on links
		 *
		 * @param boolean $apply_settings
		 */
		$apply_settings = $this->filter_penci_apply_settings();

		if ( false === $apply_settings ) {
			return $content;
		}

		$content = $this->penci_before_filter( $content );

		$regexp_link = '/<a[^A-Za-z>](.*?)>(.*?)<\/a[\s+]*>/is';

		$content = preg_replace_callback( $regexp_link, [ $this, 'match_link' ], $content );

		/**
		 * Filters after scanning content (for internal use)
		 *
		 * @param string $content
		 */
		$content = $this->penci_after_filter( $content );

		return $content;
	}

	protected function match_link( $matches ) {
		$original_link = $matches[0];
		$atts          = $matches[1];
		$label         = $matches[2];

		if ( strpos( $atts, 'href' ) === false ) {
			return $original_link;
		}

		$created_link = $this->get_created_link( $label, $atts );

		if ( false === $created_link ) {
			return $original_link;
		}

		return $created_link;
	}

	protected function get_created_link( $label, $atts ) {
		$link = new Penci_Links_HTML( 'a', $label );
		$link->set_atts( $atts );

		/**
		 * Action triggered before link settings will be applied
		 *
		 * @param Penci_Links_HTML $link
		 *
		 * @return void
		 */
		do_action( 'penci_before_apply_link', $link );

		$this->penci_before_apply_link( $link );

		// has ignore flag
		if ( $link->is_ignore() ) {
			return false;
		}

		$this->set_link( $link );

		return $link->get_html( false );
	}

	protected function set_link( Penci_Links_HTML $link ) {
		$url = $link->get_attr( 'href' );

		$excludes_as_internal_links = false;

		// internal, external or excluded
		$is_excluded = $link->is_exclude() || $this->is_excluded_url( $url );
		$is_internal = $link->is_internal() || ( $this->is_internal_url( $url ) && ! $this->is_included_url( $url ) ) || ( $is_excluded && $excludes_as_internal_links );
		$is_external = $link->is_external() || ( ! $is_internal && ! $is_excluded );

		if ( strpos( $url, '#' ) === 0 ) {
			// skip anchors
		} else if ( $is_external ) {
			$link->set_external();
			$this->apply_link_settings( $link, 'penci_linkmg_external_link' );
		} else if ( $is_internal ) {
			$link->set_internal();
			$this->apply_link_settings( $link, 'penci_linkmg_interal_link' );
		} else if ( $is_excluded ) {
			$link->set_exclude();
			$this->apply_link_settings( $link, 'penci_linkmg_excluded_link' );
		}

		/**
		 * Action for changing link object
		 *
		 * @param Penci_Links_HTML $link
		 *
		 * @return void
		 */
		do_action( 'penci_link', $link );
	}

	protected function opt( $key, $type = null ) {
		if ( $type ) {
			return get_theme_mod( $type . '_' . $key );
		} else {
			return get_theme_mod( $key );
		}
	}

	protected function apply_link_settings( Penci_Links_HTML $link, $type ) {
		if ( ! $this->opt( 'enable', $type ) ) {
			return;
		}

		// set target
		$target     = $this->opt( 'target', $type );
		$has_target = $link->has_attr( 'target' );

		if ( $target && ! $has_target ) {
			$link->set_attr( 'target', $target );
		}

		// add "follow" / "nofollow"
		$follow     = $this->opt( 'rel_follow', $type );
		$has_follow = $link->has_attr_value( 'rel', 'follow' ) || $link->has_attr_value( 'rel', 'nofollow' );

		if ( $follow && ! $has_follow ) {

			$link->add_to_attr( 'rel', $follow );
		}

		$rel_options = $this->opt( 'rel_options', $type );
		$rel_options = explode( ',', $rel_options );

		// add "external"
		if ( 'penci_linkmg_external_link' === $type && in_array( 'rel_external', $rel_options ) ) {
			$link->add_to_attr( 'rel', 'external' );
		}

		// add "noopener"
		if ( in_array( 'rel_noopener', $rel_options ) ) {
			$link->add_to_attr( 'rel', 'noopener' );
		}

		// add "noreferrer"
		if ( in_array( 'rel_noreferrer', $rel_options ) ) {
			$link->add_to_attr( 'rel', 'noreferrer' );
		}

		// add "sponsored"
		if ( 'penci_linkmg_external_link' === $type && in_array( 'rel_sponsored', $rel_options ) ) {
			$link->add_to_attr( 'rel', 'sponsored' );
		}

		// add "ugc"
		if ( 'penci_linkmg_external_link' === $type && in_array( 'rel_ugc', $rel_options ) ) {
			$link->add_to_attr( 'rel', 'ugc' );
		}
	}

	protected function is_included_url( $url ) {
		// should be using private property, but static is more practical
		static $include_urls_arr = null;

		if ( null === $include_urls_arr ) {
			$include_urls = $this->opt( 'penci_linkmg_exceptions_ieurl' );
			$include_urls = str_replace( "\n", ',', $include_urls );

			if ( '' === trim( $include_urls ) ) {
				$include_urls_arr = array();
			} else {
				$include_urls_arr = explode( ',', $include_urls );
			}

			$include_urls_arr = array_filter( $include_urls_arr, function ( $url ) {
				return '' !== trim( $url );
			} );
		}

		foreach ( $include_urls_arr as $include_url ) {
			if ( false !== strpos( $url, $include_url ) ) {
				return true;
			}
		}

		return false;
	}

	protected function is_excluded_url( $url ) {
		// should be using private property, but static is more practical
		static $exclude_urls_arr = null;

		if ( null === $exclude_urls_arr ) {
			$exclude_urls = $this->opt( 'penci_linkmg_exceptions_eeurl' );
			$exclude_urls = str_replace( "\n", ',', $exclude_urls );

			if ( '' === trim( $exclude_urls ) ) {
				$exclude_urls_arr = array();
			} else {
				$exclude_urls_arr = explode( ',', $exclude_urls );
			}

			$exclude_urls_arr = array_filter( $exclude_urls_arr, function ( $url ) {
				return '' !== trim( $url );
			} );
		}

		foreach ( $exclude_urls_arr as $exclude_url ) {
			if ( false !== strpos( $url, $exclude_url ) ) {
				return true;
			}
		}

		return false;
	}

	protected function is_internal_url( $url ) {
		// all relative url's are internal
		if ( substr( $url, 0, 7 ) !== 'http://'
		     && substr( $url, 0, 8 ) !== 'https://'
		     && substr( $url, 0, 6 ) !== 'ftp://'
		     && substr( $url, 0, 2 ) !== '//' ) {
			return true;
		}

		// is internal
		$url_without_protocol = preg_replace( '#^http(s)?://#', '', home_url( '' ) );
		$clean_home_url       = preg_replace( '/^www\./', '', $url_without_protocol );

		if ( 0 === strpos( $url, 'http://' . $clean_home_url )
		     || 0 === strpos( $url, 'https://' . $clean_home_url )
		     || 0 === strpos( $url, 'http://www.' . $clean_home_url )
		     || 0 === strpos( $url, 'https://www.' . $clean_home_url )
		) {
			return true;
		}

		// check subdomains
		if ( $this->opt( 'penci_linkmg_exceptions_subdomain' ) && false !== strpos( $url, $this->get_domain() ) ) {
			return true;
		}

		return false;
	}

	protected function get_domain() {
		// should be using private property, but static is more practical
		static $domain_name = null;

		if ( null === $domain_name ) {
			preg_match(
				'/[a-z0-9\-]{1,63}\.[a-z\.]{2,6}$/'
				, parse_url( home_url(), PHP_URL_HOST )
				, $domain_tld
			);

			if ( count( $domain_tld ) > 0 ) {
				$domain_name = $domain_tld[0];
			} else {
				$domain_name = sanitize_text_field( $_SERVER['SERVER_NAME'] );
			}
		}

		return $domain_name;
	}

	protected function filter_penci_apply_settings() {
		if ( ! is_single() && ! is_page() ) {
			return true;
		}

		$current_post_id   = get_queried_object_id();
		$skip_post_ids     = $this->opt( 'penci_linkmg_exceptions_ids' );
		$skip_post_ids_arr = explode( ',', $skip_post_ids );

		foreach ( $skip_post_ids_arr as $post_id ) {
			if ( intval( $post_id ) === $current_post_id ) {
				return false;
			}
		}

		return true;
	}

	protected function penci_before_apply_link( Penci_Links_HTML $link ) {
		// ignore mailto links
		if ( $link->is_mailto() ) {
			$link->set_ignore();
		}

		// ignore WP Admin Bar Links
		if ( $link->has_attr_value( 'class', 'ab-item' ) ) {
			$link->set_ignore();
		}

		// ignore links containing ignored classes
		if ( $this->has_ignore_class( $link ) ) {
			$link->set_ignore();
		}
	}

	private function has_ignore_class( Penci_Links_HTML $link ) {
		$ignore_classes     = $this->opt( 'penci_linkmg_exceptions_class' );
		$ignore_classes_arr = explode( ',', $ignore_classes );

		foreach ( $ignore_classes_arr as $ignore_class ) {
			if ( $link->has_attr_value( 'class', trim( $ignore_class ) ) ) {
				return true;
			}
		}

		return false;
	}

	protected function penci_before_filter( $content ) {
		$ignore_tags = array( 'head', 'script' );


		foreach ( $ignore_tags as $tag_name ) {
			$content = preg_replace_callback(
				$this->get_tag_regexp( $tag_name )
				, [ $this, 'skip_tag' ]
				, $content
			);
		}

		return $content;
	}

	protected function penci_after_filter( $content ) {
		return $this->restore_content_placeholders( $content );
	}

	protected function get_tag_regexp( $tag_name ) {
		return '/<' . $tag_name . '[\s.*>|>](.*?)<\/' . $tag_name . '[\s+]*>/is';
	}

	protected function skip_tag( $matches ) {
		$skip_content = $matches[0];

		return $this->get_placeholder( $skip_content );
	}

	protected function get_placeholder( $placeholding_content ) {
		$placeholder                                = '<!--- PENCI PLACEHOLDER ' . count( $this->content_placeholders ) . ' --->';
		$this->content_placeholders[ $placeholder ] = $placeholding_content;

		return $placeholder;
	}

	protected function restore_content_placeholders( $content ) {
		foreach ( $this->content_placeholders as $placeholder => $placeholding_content ) {
			$content = str_replace( $placeholder, $placeholding_content, $content );
		}

		return $content;
	}
}

new Penci_Link_Options();