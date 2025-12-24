<?php

namespace PenciSoledadElementor\Modules\PenciPortfolio\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use PenciSoledadElementor\Base\Base_Widget;
use PenciSoledadElementor\Modules\QueryControl\Module;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class PenciPortfolio extends Base_Widget {

	public function get_name() {
		return 'penci-portfolio';
	}

	public function get_title() {
		return penci_get_theme_name('Penci').' '.esc_html__( ' Portfolio', 'soledad' );
	}

	public function get_icon() {
		return 'eicon-gallery-grid';
	}

	public function get_categories() {
		return [ 'penci-elements' ];
	}

	public function get_keywords() {
		return array( 'portfolio' );
	}

	protected function register_controls() {
		

		$this->register_query_section_controls();

		// Section layout
		$this->start_controls_section(
			'section_page', array(
				'label' => esc_html__( 'Portfolio', 'soledad' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);
		$this->add_control(
			'penci_ajax', array(
				'label'     => __( 'Filter with AJAX?', 'soledad' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_on'  => __( 'Enable', 'soledad' ),
				'label_off' => __( 'Disable', 'soledad' ),
			)
		);
		$this->add_control(
			'penci_style', array(
				'label'   => __( 'Choose Style', 'soledad' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'masonry',
				'options' => array(
					'masonry' => esc_html__( 'Masonry', 'soledad' ),
					'grid'    => esc_html__( 'Grid', 'soledad' ),
				)
			)
		);

		$this->add_control(
			'penci_item_style', array(
				'label'   => __( 'Choose Item Style', 'soledad' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'text_overlay',
				'options' => array(
					'text_overlay' => esc_html__( 'Text Overlay', 'soledad' ),
					'below_img'    => esc_html__( 'Text Below Image', 'soledad' ),
				)
			)
		);

		$this->add_control(
			'penci_column', array(
				'label'   => __( 'Number Columns', 'soledad' ),
				'type'    => Controls_Manager::SELECT,
				'default' => '3',
				'options' => array(
					'3' => esc_html__( '3 Columns', 'soledad' ),
					'2' => esc_html__( '2 Columns', 'soledad' ),
				)
			)
		);
		$this->add_control(
			'penci_image_type', array(
				'label'       => __( 'Image Type', 'soledad' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => 'landscape',
				'options'     => array(
					'square'    => esc_html__( 'Square', 'soledad' ),
					'vertical'  => esc_html__( 'Vertical', 'soledad' ),
					'landscape' => esc_html__( 'Landscape', 'soledad' ),
				),
				'description' => esc_html__( 'This option does not apply for "Masonry" Style', 'soledad' ),
			)
		);

		$this->add_control(
			'penci_filter', array(
				'label'     => __( 'Display Filter?', 'soledad' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_on'  => __( 'Show', 'soledad' ),
				'label_off' => __( 'Hide', 'soledad' ),
				'default'   => 'yes',
			)
		);
		$this->add_control(
			'penci_all_text', array(
				'label' => __( 'All Portfolio Text', 'soledad' ),
				'type'  => Controls_Manager::TEXT,
			)
		);

		$this->add_control(
			'penci_pagination', array(
				'label'   => __( 'Pagination', 'soledad' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'number',
				'options' => array(
					'number'        => esc_html__( 'Numeric Pagination', 'soledad' ),
					'load_more'     => esc_html__( 'Load More Button', 'soledad' ),
					'infinite'      => esc_html__( 'Infinite Load', 'soledad' ),
					'next_previous' => esc_html__( 'Next/Previous', 'soledad' ),
					'none'          => esc_html__( 'None', 'soledad' ),
				)
			)
		);
		$this->add_control(
			'penci_numbermore', array(
				'label'     => __( 'Number of Posts Each Time Load More Posts', 'elementor' ),
				'type'      => Controls_Manager::NUMBER,
				'default'   => 6,
				'condition' => array( 'penci_pagination' => array( 'load_more', 'infinite' ) )
			)
		);
		$this->add_control(
			'penci_lightbox', array(
				'label'     => __( 'Enable Click on Thumbnails to Open Lightbox?', 'soledad' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_on'  => __( 'Yes', 'soledad' ),
				'label_off' => __( 'No', 'soledad' ),
				'default'   => '',
			)
		);
		$this->end_controls_section();
		$this->start_controls_section(
			'section_style_portfolio',
			array(
				'label' => __( 'Portfolio', 'soledad' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);
		$this->add_control(
			'penci_filter_heading',
			array(
				'label' => __( 'Filter', 'soledad' ),
				'type'  => Controls_Manager::HEADING,
			)
		);
		$this->add_control(
			'pfilter_color',
			array(
				'label'     => __( 'Link Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} post-entry .penci-portfolio-filter ul li a,{{WRAPPER}} .penci-portfolio-filter ul li a' => 'color: {{VALUE}};'
				),
			)
		);
		$this->add_control(
			'pfilter_hcolor',
			array(
				'label'     => __( 'Link Hover Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} post-entry .penci-portfolio-filter ul li a:hover'  => 'color: {{VALUE}};',
					'{{WRAPPER}} post-entry .penci-portfolio-filter ul li.active a' => 'color: {{VALUE}};',
					'{{WRAPPER}} .penci-portfolio-filter ul li a:hover'             => 'color: {{VALUE}};',
					'{{WRAPPER}} .penci-portfolio-filter ul li.active a'            => 'color: {{VALUE}};'
				),
			)
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(), array(
				'name'     => 'pfilter_typo',
				'selector' => '{{WRAPPER}} post-entry .penci-portfolio-filter ul li a,{{WRAPPER}} .penci-portfolio-filter ul li a',
			)
		);

		$this->add_control(
			'pbgoverlay_color',
			array(
				'label'     => __( 'Portfolio Background Overlay Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .penci-portfolio-thumbnail a:after' => 'background-color: {{VALUE}};' ),
			)
		);
		// Title
		$this->add_control(
			'ptitle_heading',
			array(
				'label' => __( 'Title', 'soledad' ),
				'type'  => Controls_Manager::HEADING,
			)
		);
		$this->add_control(
			'ptitle_color',
			array(
				'label'     => __( 'Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .inner-item-portfolio .portfolio-desc h3' => 'color: {{VALUE}};' ),
			)
		);
		$this->add_control(
			'ptitle_hcolor',
			array(
				'label'     => __( 'Hover Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .inner-item-portfolio .portfolio-desc h3:hover' => 'color: {{VALUE}};' ),
			)
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(), array(
				'name'     => 'ptitle_typo',
				'selector' => '{{WRAPPER}} .inner-item-portfolio .portfolio-desc h3',
			)
		);

		// Cat
		$this->add_control(
			'pcat_heading',
			array(
				'label' => __( 'Category', 'soledad' ),
				'type'  => Controls_Manager::HEADING,
			)
		);
		$this->add_control(
			'pcat_color',
			array(
				'label'     => __( 'Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .inner-item-portfolio .portfolio-desc span' => 'color: {{VALUE}};' ),
			)
		);
		$this->add_control(
			'pcat_hcolor',
			array(
				'label'     => __( 'Hover Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .inner-item-portfolio .portfolio-desc span:hover' => 'color: {{VALUE}};' ),
			)
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(), array(
				'name'     => 'pcat_typo',
				'selector' => '{{WRAPPER}} .inner-item-portfolio .portfolio-desc span',
			)
		);

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings();

		$atts = array(
			'style',
			'image_type',
			'item_style',
			'number',
			'column',
			'cat',
			'all_text',
			'pagination',
			'numbermore',
			'loop',
			'elementor_query'
		);

		$atts_shortcode = array();

		foreach ( $atts as $att ) {
			if ( empty( $settings[ 'penci_' . $att ] ) ) {
				continue;
			}

			$atts_shortcode[ $att ] = $settings[ 'penci_' . $att ];
		}

		if ( 'yes' == $settings['penci_filter'] ) {
			$atts_shortcode['filter'] = 'true';
		} else {
			$atts_shortcode['filter'] = 'false';
		}

		if ( 'yes' == $settings['penci_lightbox'] ) {
			$atts_shortcode['lightbox'] = 'true';
		} else {
			$atts_shortcode['lightbox'] = 'false';
		}

		if ( 'yes' == $settings['penci_ajax'] ) {
			$atts_shortcode['penci_ajax'] = 'true';
		}

		$query_args = Module::get_query_args( 'posts', $settings );
		$post_type  = $settings['posts_post_type'];

		$tax_query = array();
		if ( 'by_id' != $post_type ) {
			if ( 'post' == $post_type ) {
				$tax_query = isset( $settings['posts_category_ids'] ) ? $settings['posts_category_ids'] : array();
			} elseif ( 'portfolio' == $post_type ) {
				$tax_query = isset( $settings['posts_portfolio-category_ids'] ) ? $settings['posts_portfolio-category_ids'] : array();
			} elseif ( 'product' == $post_type ) {
				$tax_query = isset( $settings['posts_product_cat_ids'] ) ? $settings['posts_product_cat_ids'] : array();
			} elseif ( $post_type ) {
				$taxonomy_objects = get_object_taxonomies( $post_type );

				if ( isset( $taxonomy_objects[0] ) ) {
					$tax_first = $taxonomy_objects[0];

					$setting_key = 'posts_' . $tax_first . '_ids';
					$tax_query   = isset( $settings[ $setting_key ] ) ? $settings[ $setting_key ] : array();
				}
			}
		}

		if ( $tax_query ) {
			$query_args['filter_bar_ids'] = implode( ',', $tax_query );
		}

		$atts_shortcode['elementor_query'] = $query_args;

		if ( class_exists( 'Penci_Soledad_Portfolio_Shortcode' ) ) {
			$portfolio = new \Penci_Soledad_Portfolio_Shortcode;
			echo $portfolio->portfolio_shortcode( $atts_shortcode );
		}
	}
}
