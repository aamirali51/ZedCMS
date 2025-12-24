<?php

namespace PenciSoledadElementor;

use PenciSoledadElementor\Base\Module_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

final class Manager {
	/**
	 * @var Module_Base
	 */
	private $modules = array();
	private $column_order = 1;

	public function __construct() {

		// Register controls
		$modules = array(
			'penci-sticky',
			'query-control',
			'penci-visibility-controls',
			'penci-collapsible-controls',
			'penci-sticky-container',
			'penci-big-grid',
			'penci-featured-sliders',
			'penci-latest-posts',
			'penci-featured-cat',
			'penci-small-list',
			'penci-custom-sliders',
			'penci-popular-posts',
			'penci-portfolio',
			'penci-featured-boxes',
			'penci-fullwidth-hero-overlay',
			'penci-news-ticker',
			'penci-category-listing',
			'penci-media-carousel',
			'penci-about-me',
			'penci-button',
			'penci-button-popup',
			'penci-posts-slider',
			'penci-recent-posts',
			'penci-stylisted-articles-count',
			'penci-instagram',
			'penci-pintersest',
			'penci-social-media',
			'penci-block-heading-title',
			'penci-animated-headline',
			'penci-multi-taxonomies-posts-filter',
			'penci-content-accordion',
			'penci-image-compare',
			'penci-table-of-contents',
			'penci-custom-carousel',
			'penci-az-taxonomy-listing',
			'penci-advanced-calculator',
			'penci-hover-box',
			'penci-circle-info',
			'penci-circle-menu',
			'penci-timeline',
			'penci-marquee',

			'penci-facebook-page',
			'penci-count-down',
			'penci-counter-up',
			'penci-fancy-heading',
			'penci-map',
			'penci-advanced-gmaps',
			'penci-info-box',
			'penci-image-gallery',
			'penci-latest-tweets',
			'penci-mail-chimp',
			'penci-open-hour',
			'penci-popular-cat',
			'penci-text-block',
			'penci-pricing-table',
			'penci-progress-bar',
			'penci-social-counter',
			'penci-team-member',
			'penci-video-playlist',
			'penci-weather',
			'penci-testimonials',
			'penci-login-form',
			'penci-sidebar',
			'penci-advanced-list',
			'penci-simple-list',
			'penci-footer-navmenu',
			'penci-tiktok-embed-feed',
			'penci-advanced-categories',
			'penci-author-list',
			'penci-search-form',
			'penci-posts-tabs',
			'penci-snapchat',
			'penci-comments-list',
			'penci-rss-feed',
			'penci-flickr',
			'penci-contact-form',
		);

		if ( class_exists( 'WooCommerce' ) ) {
			$woocommerce_modules = array(
				'penci-product',
				'penci-product-brand',
				'penci-product-filter',
				'penci-product-categories-grid',
				'penci-product-tabs',
				'penci-product-hotspot',
				'penci-product-list',
			);
			$modules = array_merge( $modules, $woocommerce_modules );
		}

		if ( defined( 'WEBSTORIES_VERSION' ) ) {
			$modules[] = 'penci-web-story';
		}

		foreach ( $modules as $module_name ) {
			$class_name = str_replace( '-', ' ', $module_name );
			$class_name = str_replace( ' ', '', ucwords( $class_name ) );
			$class_name = __NAMESPACE__ . '\\Modules\\' . $class_name . '\Module';


			/** @var Module_Base $class_name */
			$experimental_data = $class_name::get_experimental_data();

			if ( $experimental_data ) {


				\Elementor\Plugin::$instance->experiments->add_feature( $experimental_data );

				if ( ! \Elementor\Plugin::$instance->experiments->is_feature_active( $experimental_data['name'] ) ) {
					continue;
				}
			}

			if ( $class_name::is_active() ) {
				$this->modules[ $module_name ] = $class_name::instance();
			}
		}

		$this->add_actions();
	}

	/**
	 * Add sticky class for sticky content and sticky sidebar
	 */
	protected function add_actions() {
		add_action( 'elementor/frontend/column/before_render', array( $this, 'add_column_attribute' ) );
		add_action( 'elementor/frontend/section/before_render', array( $this, 'add_section_attribute' ) );

		add_action( 'elementor/frontend/column/after_add_attributes', array( $this, 'add_column_attribute' ) );
		add_action( 'elementor/frontend/section/after_add_attributes', array( $this, 'add_section_attribute' ) );

		add_action( 'elementor/frontend/container/before_render', array( $this, 'add_container_attribute' ), 0 );
		add_action( 'elementor/frontend/before_render', array( $this, 'add_attributes_to_ft_elements' ) );
		add_action( 'elementor/widget/render_content', array( $this, 'add_div_after_ft_elements' ), 10, 2 );

	}
	

	public function add_attributes_to_ft_elements( $element ) {

		if ( ! $element->get_settings( 'query_id' ) ) {
			return;
		}
	
		$element->add_render_attribute(
			'_wrapper',
			[
				'class' => 'pcft-elers-wrapper pcft-ele-' . $element->get_settings( 'query_id' ),
			]
		);
	
	}

	public function add_div_after_ft_elements( $widget_content, $widget ) {
		if ( ! $widget->get_settings( 'query_id' ) ) {
			return $widget_content;
		}
		return $widget_content . penci_get_html_animation_loading( 'df' );
	}

	/**
	 * @param string $module_name
	 *
	 * @return Module_Base|Module_Base[]
	 */
	public function get_modules( $module_name ) {
		if ( $module_name ) {
			if ( isset( $this->modules[ $module_name ] ) ) {
				return $this->modules[ $module_name ];
			}

			return null;
		}

		return $this->modules;
	}

	public function add_column_attribute( $element ) {
		$settings = $element->get_settings();

		$current_column_order = $this->column_order;
		$element->add_render_attribute( array(
			'_inner_wrapper' => array( 'class' => 'theiaStickySidebar' ),
			'_wrapper'       => array(
				'class' => array(
					'penci-ercol-' . $settings['_column_size'],
					'penci-ercol-order-' . $current_column_order,
					in_array( $settings['_column_size'], array( 33, 25 ) ) ? 'penci-sticky-sb' : 'penci-sticky-ct',
					in_array( $settings['_column_size'], array( 33, 25 ) ) ? 'penci-sidebarSC' : '',
					$settings['background_background'] ? 'penci-dmcheck penci-elbg-activate' : '',
					isset( $settings['background_image']['url'] ) && $settings['background_image']['url'] ? 'penci-elbg-img' : ''
				)
			)
		) );

		$this->column_order = $current_column_order + 1;
	}

	public function add_container_attribute( $element ) {
		$settings      = $element->get_settings();
		$enable_sticky = isset( $settings['penci_enable_sticky'] ) ? $settings['penci_enable_sticky'] : false;

		$wrap = $element->get_data( 'isInner' ) ? 'e-child' : 'e-parent';

		if ( 'e-parent' == $wrap && $enable_sticky ) {
			$this->column_order = 1;

			$class = 'penci-section';

			$class .= $settings['background_background'] ? ' penci-dmcheck penci-elbg-activate' : '';
			$class .= isset( $settings['background_image']['url'] ) && $settings['background_image']['url'] ? ' penci-elbg-img' : '';

			if ( ! $enable_sticky ) {
				$class .= ' penci-disSticky';
			} else {
				$class .= ' penci-enSticky';
			}

			$element->add_render_attribute( '_wrapper', array(
				'class' => $class,
			) );
		}

		if ( 'e-child' == $wrap && $enable_sticky ) {
			$current_column_order = $this->column_order;
			$element->add_render_attribute( array(
				'_wrapper' => array(
					'class' => array(
						'pc-container-sticky',
						$current_column_order + 1 > 2 ? 'penci-sticky-sb penci-sidebarSC' : 'penci-sticky-ct',
						$settings['background_background'] ? 'penci-dmcheck penci-elbg-activate' : '',
						isset( $settings['background_image']['url'] ) && $settings['background_image']['url'] ? 'penci-elbg-img' : ''
					)
				)
			) );

			$this->column_order = $current_column_order + 1;
		}

	}

	public function add_section_attribute( $element ) {
		$settings = $element->get_settings();

		$enable_sticky       = isset( $settings['penci_enable_sticky'] ) ? $settings['penci_enable_sticky'] : false;
		$enable_repons_twosb = isset( $settings['penci_enable_repons_section'] ) ? $settings['penci_enable_repons_section'] : false;
		$ctsidebar_mb        = isset( $settings['penci_ctsidebar_mb'] ) ? $settings['penci_ctsidebar_mb'] : 'con_sb2_sb1';
		$structure           = isset( $settings['structure'] ) ? $settings['structure'] : '';

		$this->column_order = 1;

		$class = 'penci-section';

		$class .= $settings['background_background'] ? ' penci-dmcheck penci-elbg-activate' : '';
		$class .= isset( $settings['background_image']['url'] ) && $settings['background_image']['url'] ? ' penci-elbg-img' : '';

		if ( ! $enable_sticky ) {
			$class .= ' penci-disSticky';
		} else {
			$class .= ' penci-enSticky';
		}

		if ( $enable_repons_twosb ) {
			$class .= ' penci-repons-elsection';

			$class .= ' penci-' . $ctsidebar_mb;
		}

		if ( $structure ) {
			$class .= ' penci-structure-' . $structure;
		}

		$element->add_render_attribute( '_wrapper', array(
			'class' => $class,
		) );
	}

}
