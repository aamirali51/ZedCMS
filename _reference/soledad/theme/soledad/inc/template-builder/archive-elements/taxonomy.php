<?php

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Widget_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}


class PenciArchiveTaxonomy extends Widget_Base {

	public function get_title() {
		return esc_html__( 'Archive - Taxonomies', 'soledad' );
	}

	public function get_icon() {
		return 'eicon-text';
	}

	public function get_categories() {
		return array( 'penci-custom-archive-builder' );
	}

	public function get_keywords() {
		return array( 'archive', 'taxonomies' );
	}

	protected function get_html_wrapper_class() {
		return 'pcab-txnm elementor-widget-' . $this->get_name();
	}

	public function get_name() {
		return 'penci-archive-taxonomies';
	}

	protected function register_controls() {

		$this->start_controls_section(
			'content_section',
			array(
				'label' => esc_html__( 'General', 'soledad' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'tax_showall',
			array(
				'label'   => esc_html__( 'Term Listing', 'soledad' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'all',
				'options' => array(
					'all'    => 'All Items',
					'child'  => 'Child Items',
					'custom' => 'Custom Items',
				),
			)
		);

		$this->add_control(
			'taxonomies',
			array(
				'label'       => esc_html__( 'Select the Post Taxonomies Term.', 'soledad' ),
				'type'        => 'penci_el_autocomplete',
				'search'      => 'penci_get_taxonomies_by_query',
				'render'      => 'penci_get_taxonomies_title_by_id',
				'taxonomy'    => get_object_taxonomies( 'post' ),
				'multiple'    => true,
				'label_block' => true,
				'condition'   => array(
					'tax_showall' => 'custom',
				),
			)
		);

		$this->add_control(
			'taxonomies_ex',
			array(
				'label'       => esc_html__( 'Select the Excluded Post Taxonomies Term.', 'soledad' ),
				'type'        => 'penci_el_autocomplete',
				'search'      => 'penci_get_taxonomies_by_query',
				'render'      => 'penci_get_taxonomies_title_by_id',
				'taxonomy'    => get_object_taxonomies( 'post' ),
				'multiple'    => true,
				'label_block' => true,
			)
		);

		$this->add_control(
			'term_style',
			array(
				'label'   => 'Display Style',
				'type'    => Controls_Manager::SELECT,
				'default' => 's1',
				'options' => array(
					's1' => 'Style 1',
					's2' => 'Style 2',
					's3' => 'Style 3',
					's4' => 'Style 4',
				),
			)
		);

		$this->add_control(
			'term_align',
			array(
				'label'       => 'Align',
				'default'     => 'left',
				'type'        => Controls_Manager::CHOOSE,
				'label_block' => false,
				'options'     => array(
					'left'   => array(
						'title' => __( 'Left', 'soledad' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center' => array(
						'title' => __( 'Center', 'soledad' ),
						'icon'  => 'eicon-text-align-center',
					),
					'right'  => array(
						'title' => __( 'Right', 'soledad' ),
						'icon'  => 'eicon-text-align-right',
					),
				),
				'selectors'   => array(
					'{{WRAPPER}} .pctmp-term-list' => 'text-align: {{VALUE}};',
				),
				'render_type' => 'template',
			)
		);

		$this->add_control(
			'orderby',
			array(
				'label'     => esc_html__( 'Order By', 'soledad' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'name',
				'options'   => array(
					'name'       => 'Name',
					'slug'       => 'Slug',
					'term_id'    => 'ID',
					'term_order' => 'Term Order',
					'count'      => 'Term Count',
					'rand'       => 'Random',
				),
				'condition' => array(
					'tax_showall!' => 'custom',
				),
			)
		);

		$this->add_control(
			'order',
			array(
				'label'     => esc_html__( 'Order', 'soledad' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'ASC',
				'options'   => array(
					'DESC' => 'DESC',
					'ASC'  => 'ASC',
				),
				'condition' => array(
					'tax_showall!' => 'custom',
				),
			)
		);

		$this->add_control(
			'number',
			array(
				'label'       => esc_html__( 'Limit Terms to Show', 'soledad' ),
				'description' => esc_html__( 'This option is visible on frontend only.', 'soledad' ),
				'type'        => Controls_Manager::NUMBER,
				'condition'   => array(
					'tax_showall!' => 'custom',
				),
			)
		);

		$this->add_control(
			'tax_currentitem',
			array(
				'label' => esc_html__( 'Activate Current Viewing Term?', 'soledad' ),
				'type'  => Controls_Manager::SWITCHER,
			)
		);

		$this->add_control(
			'tax_showcount',
			array(
				'label' => esc_html__( 'Show Posts Count', 'soledad' ),
				'type'  => Controls_Manager::SWITCHER,
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'color_style',
			array(
				'label' => esc_html__( 'Color & Styles', 'soledad' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'term_spacing',
			array(
				'label'     => 'Horizontal Space Between Items',
				'type'      => Controls_Manager::SLIDER,
				'selectors' => array(
					'{{WRAPPER}} .pctmp-term-list.pctaxleft li:not(:last-child)'   => 'margin-right:{{SIZE}}px',
					'{{WRAPPER}} .pctmp-term-list.pctaxright li:not(:first-child)' => 'margin-left:{{SIZE}}px',
					'{{WRAPPER}} .pctmp-term-list.pctaxcenter li'                  => 'margin-left:calc({{SIZE}}px/2);margin-right:calc({{SIZE}}px/2)',
					'{{WRAPPER}} .pctmp-term-list li .pctmp-term-item'             => 'margin-right:0;margin-left:0;--pctmp-term-list:{{SIZE}}px',
				),
			)
		);

		$this->add_responsive_control(
			'term_vspacing',
			array(
				'label'     => 'Vertical Space Between Items',
				'type'      => Controls_Manager::SLIDER,
				'selectors' => array(
					'{{WRAPPER}} .pctmp-term-list li .pctmp-term-item' => 'margin-bottom:{{SIZE}}px;',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'term_typo',
				'label'    => __( 'Typography for Term', 'soledad' ),
				'selector' => '{{WRAPPER}} .pctmp-term-item',
			)
		);

		$this->add_control(
			'term_color',
			array(
				'label'     => 'Term Color',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .pctmp-term-item' => 'color:{{VALUE}}' ),
			)
		);

		$this->add_control(
			'term_hcolor',
			array(
				'label'     => 'Term Hover & Active Color',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} a.pctmp-term-item:hover,{{WRAPPER}} a.pctmp-term-item.current-item' => 'color:{{VALUE}}' ),
			)
		);

		$this->add_control(
			'term_bgcolor',
			array(
				'label'     => 'Term Background Color',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .pctmp-term-item' => 'background-color:{{VALUE}}' ),
			)
		);

		$this->add_control(
			'term_bghcolor',
			array(
				'label'     => 'Term Background Hover & Active Color',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} a.pctmp-term-item:hover,{{WRAPPER}} a.pctmp-term-item.current-item' => 'background-color:{{VALUE}}' ),
			)
		);

		$this->add_control(
			'term_bcolor',
			array(
				'label'     => 'Term Borders Color',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .pctmp-term-item' => 'border-color:{{VALUE}}' ),
			)
		);

		$this->add_control(
			'term_bhcolor',
			array(
				'label'     => 'Term Borders Hover & Active Color',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} a.pctmp-term-item:hover,{{WRAPPER}} a.pctmp-term-item.current-item' => 'border-color:{{VALUE}}' ),
			)
		);

		$this->add_control(
			'term_padding',
			array(
				'label'      => 'Term Item Padding',
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .pctmp-term-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'term_border',
			array(
				'label'      => 'Term Item Border',
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .pctmp-term-item' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'term_border_style',
			array(
				'label'     => 'Term Borders Style',
				'type'      => Controls_Manager::SELECT,
				'options'   => array(
					'dotted' => 'Dotted',
					'dashed' => 'Dashed',
					'solid'  => 'Solid',
					'double' => 'Double',
					'groove' => 'Groove',
					'ridge'  => 'Ridge',
					'inset'  => 'Inset',
					'outset' => 'Outset',
				),
				'selectors' => array( '{{WRAPPER}} .pctmp-term-item' => 'border-style:{{VALUE}}' ),
			)
		);

		$this->add_control(
			'term_border_radius',
			array(
				'label'      => 'Term Item Borders Radius',
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .pctmp-term-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();
	}

	protected function render() {

		if ( penci_elementor_is_edit_mode() ) {
			$this->preview_content();
		} else {
			$this->builder_content();
		}
	}

	protected function preview_content() {
		$settings   = $this->get_settings();
		$term_style = $settings['term_style'];
		$taxonomies = $settings['taxonomies'];
		$class      = isset( $settings['term_align'] ) && $settings['term_align'] ? 'pctax' . $settings['term_align'] : 'pctaxleft';
		$count      = '';
		$demo_lists = array(
			'Sport',
			'Football',
			'Tennis',
			'Volleyball',
			'Joke',
		);
		if ( $settings['tax_showall'] == 'custom' && ! empty( $taxonomies ) ) {
			?>
			<div class="pc-tax-lists">
				<ul class="pctmp-term-list term-style-<?php echo esc_attr( $term_style . ' ' . $class ); ?>">
					<?php
					foreach ( $taxonomies as $term_id ) {
						$term = get_term( $term_id );
						if ( $settings['tax_showcount'] ) {
							$count = '<span class="count">(' . $term->count . ')</span>';
						}
						echo '<li><a class="pctmp-term-item" href="' . get_term_link( $term->term_id ) . '">' . esc_html( $term->name ) . $count . '</a></li>';
					}
					?>
				</ul>
			</div>
			<?php
		} else {
			?>
			<div class="pc-tax-lists">
				<ul class="pctmp-term-list term-style-<?php echo esc_attr( $term_style . ' ' . $class ); ?>">
					<?php
					foreach ( $demo_lists as $list ) {
						if ( $settings['tax_showcount'] ) {
							$count = '<span class="count">(' . rand( 0, 99 ) . ')</span>';
						}
						echo '<li><a class="pctmp-term-item" href="#">' . $list . $count . '</a></li>';
					}
					?>
				</ul>
			</div>
			<?php
		}
	}

	protected function builder_content() {
		$settings   = $this->get_settings();
		$term_style = $settings['term_style'];
		$taxonomies = $settings['taxonomies'];
		$queries    = get_queried_object();
		$class      = isset( $settings['term_align'] ) && $settings['term_align'] ? 'pctax' . $settings['term_align'] : 'pctaxleft';
		$count      = '';
		if ( ! isset( $queries->taxonomy ) || ( $settings['tax_showall'] == 'custom' && empty( $taxonomies ) ) ) {
			return false;
		}

		$args = array(
			'taxonomy' => $queries->taxonomy,
			'orderby'  => $settings['orderby'],
			'order'    => $settings['order'],
		);

		if ( $settings['taxonomies_ex'] ) {
			$args['exclude'] = $settings['taxonomies_ex'];
		}

		if ( $settings['number'] && 'rand' != $settings['orderby'] ) {
			$args['number'] = $settings['number'];
		}

		if ( $settings['tax_showall'] == 'child' ) {
			$args['child_of'] = $queries->term_id;
		}

		$term_data    = get_queried_object();
		$current_term = ! empty( $term_data ) && isset( $term_data->term_id ) && $term_data->term_id ? $term_data->term_id : '';

		$terms = get_terms( $args );
		if ( $terms && $settings['tax_showall'] !== 'custom' ) {

			if ( 'rand' == $settings['orderby'] ) {
				shuffle( $terms );
			}

			?>
			<div class="pc-tax-lists">
				<ul class="pctmp-term-list term-style-<?php echo esc_attr( $term_style . ' ' . $class ); ?>">
					<?php
					$t_count = 0;
					foreach ( $terms as $term ) {
						$prefix_class = 'category' == $term->taxonomy ? 'cat' : esc_attr( $term->taxonomy );
						$class        = 'penci-' . $prefix_class . '-name penci-' . $prefix_class . '-' . $term->slug . ' penci-' . $prefix_class . '-' . $term->term_id;

						if ( $t_count++ == $settings['number'] && 'rand' == $settings['orderby'] ) {
							break;
						}
						if ( $settings['tax_showcount'] ) {
							$count = '<span class="count">(' . $term->count . ')</span>';
						}
						if ( $term->term_id == $current_term && 'yes' == $settings['tax_currentitem'] ) {
							$class .= ' current-item';
						}
						echo '<li><a class="pctmp-term-item ' . $class . '" href="' . get_term_link( $term->term_id ) . '">' . esc_html( $term->name ) . $count . '</a></li>';
					}
					?>
				</ul>
			</div>
			<?php
		} elseif ( $settings['tax_showall'] == 'custom' && ! empty( $taxonomies ) ) {
			?>
			<div class="pc-tax-lists">
				<ul class="pctmp-term-list term-style-<?php echo esc_attr( $term_style . ' ' . $class ); ?>">
					<?php
					foreach ( $taxonomies as $term_id ) {
						$term         = get_term( $term_id );
						$prefix_class = 'category' == $term->taxonomy ? 'cat' : esc_attr( $term->taxonomy );
						$class        = 'penci-' . $prefix_class . '-name penci-' . $prefix_class . '-' . $term->slug . ' penci-' . $prefix_class . '-' . $term->term_id;
						if ( $settings['tax_showcount'] ) {
							$count = '<span class="count">(' . $term->count . ')</span>';
						}
						if ( $term->term_id == $current_term && 'yes' == $settings['tax_currentitem'] ) {
							$class .= ' current-item';
						}
						echo '<li><a class="pctmp-term-item ' . $class . '" href="' . get_term_link( $term->term_id ) . '">' . esc_html( $term->name ) . $count . '</a></li>';
					}
					?>
				</ul>
			</div>
			<?php
		}
	}
}
