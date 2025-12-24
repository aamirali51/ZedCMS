<?php

namespace PenciSoledadElementor\Modules\PenciAzTaxonomyListing\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use PenciSoledadElementor\Base\Base_Widget;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class PenciAzTaxonomyListing extends Base_Widget {

	public function get_name() {
		return 'penci-az-taxonomy-listing';
	}

	public function get_title() {
		return penci_get_theme_name( 'Penci' ) . ' ' . esc_html__( ' ABC Categories Listing', 'soledad' );
	}

	public function get_icon() {
		return 'eicon-gallery-grid';
	}

	public function get_categories() {
		return [ 'penci-elements' ];
	}

	public function get_keywords() {
		return array( 'category', 'post' );
	}

	public function get_script_depends() {
		return [ 'penci-abc-scroll' ];
	}

	protected function register_controls() {


		// Section layout
		$this->start_controls_section( 'section_general', array(
			'label' => esc_html__( 'General', 'soledad' ),
			'tab'   => Controls_Manager::TAB_CONTENT,
		) );

		$post_taxonomies = get_object_taxonomies( 'post' );
		$post_tax        = [];
		$post_tax['']    = 'Select';
		foreach ( $post_taxonomies as $tname ) {
			$labels             = get_taxonomy( $tname );
			$post_tax[ $tname ] = $labels->label;
		}

		foreach ( penci_get_published_posttypes() as $type ) {

			$type_data = get_object_taxonomies( $type );
			if ( is_array( $type_data ) ) {
				foreach ( $type_data as $type_name ) {
					$labels                 = get_taxonomy( $type_name );
					$post_tax[ $type_name ] = $labels->label;
				}
			}
		}

		$this->add_control( 'term_name', [
			'label'   => 'Taxonomies',
			'type'    => \Elementor\Controls_Manager::SELECT,
			'options' => $post_tax,
			'default' => 'category',
		] );

		$this->add_control( 'taxonomies_ex', [
			'label'       => esc_html__( 'Select the Excluded Taxonomies Terms.', 'soledad' ),
			'type'        => 'penci_el_autocomplete',
			'search'      => 'penci_get_taxonomies_by_query',
			'render'      => 'penci_get_taxonomies_title_by_id',
			'taxonomy'    => get_object_taxonomies( 'post' ),
			'multiple'    => true,
			'label_block' => true,
		] );

		$this->add_control( 'orderby', [
			'label'   => esc_html__( 'Order By', 'soledad' ),
			'type'    => Controls_Manager::SELECT,
			'default' => 'name',
			'options' => [
				'name'       => 'Name',
				'slug'       => 'Slug',
				'term_id'    => 'ID',
				'term_order' => 'Term Order',
				'count'      => 'Posts Count',
			],
		] );

		$this->add_control( 'order', [
			'label'   => esc_html__( 'Order', 'soledad' ),
			'type'    => Controls_Manager::SELECT,
			'default' => 'ASC',
			'options' => [
				'DESC' => 'DESC',
				'ASC'  => 'ASC',
			],
		] );

		$this->add_control( 'hide_empty', array(
			'label'        => __( 'Hide Empty Items', 'soledad' ),
			'type'         => Controls_Manager::SWITCHER,
			'label_on'     => __( 'Yes', 'soledad' ),
			'label_off'    => __( 'No', 'soledad' ),
			'return_value' => 'yes',
			'default'      => '',
		) );
		
		$this->add_control( 'show_head', array(
			'label'        => __( 'Show Heading Link', 'soledad' ),
			'type'         => Controls_Manager::SWITCHER,
			'label_on'     => __( 'Yes', 'soledad' ),
			'label_off'    => __( 'No', 'soledad' ),
			'return_value' => 'yes',
			'default'      => 'yes',
		) );
		
		$this->add_control( 'show_head_style', array(
			'label'   => __( 'Heading Link Style', 'soledad' ),
			'type'    => Controls_Manager::SELECT,
			'default' => 'style-1',
			'options' => array(
				'style-1'  		=> esc_html__( 'Style 1', 'soledad' ),
				'style-2' 		=> esc_html__( 'Style 2', 'soledad' ),
			),
			'condition' => [
				'show_head' => 'yes',
			],
		) );
		
		$this->add_control( 'show_count', array(
			'label'        => __( 'Show Post Count', 'soledad' ),
			'type'         => Controls_Manager::SWITCHER,
			'label_on'     => __( 'Yes', 'soledad' ),
			'label_off'    => __( 'No', 'soledad' ),
			'return_value' => 'yes',
			'default'      => '',
		) );

		$this->add_responsive_control( 'columns', array(
			'label'   => __( 'Columns', 'soledad' ),
			'type'    => Controls_Manager::SELECT,
			'default' => '3',
			'options' => array(
				'1' => esc_html__( '1 Column', 'soledad' ),
				'2' => esc_html__( '2 Columns', 'soledad' ),
				'3' => esc_html__( '3 Columns', 'soledad' ),
				'4' => esc_html__( '4 Columns', 'soledad' ),
				'5' => esc_html__( '5 Columns', 'soledad' ),
				'6' => esc_html__( '6 Columns', 'soledad' )
			),
			'selectors' => array( '{{WRAPPER}} .penci_az_taxonomy_listing' => '--col: {{VALUE}};' ),
		) );
		
		$this->add_responsive_control( 'gap', array(
			'label'   => __( 'Gap', 'soledad' ),
			'type'    => Controls_Manager::SLIDER,
			'size_units' => [ 'px' ],
			'range'      => array(
				'px' => array( 'max' => 200 ),
			),
			'selectors'  => array(
				'{{WRAPPER}} .penci_az_taxonomy_listing' => '--gap: {{SIZE}}px;'
			),
		) );

		$this->end_controls_section();

		$this->register_block_title_section_controls();

		// Heading Style
		$this->start_controls_section( 'section_heading_style', array(
			'label' => esc_html__( 'Heading Link', 'soledad' ),
			'tab'   => Controls_Manager::TAB_STYLE,
			'condition' => [
				'show_head' => 'yes',
			],
		) );

		$this->add_control( 'pcaz_heading_bg', array(
			'label'   => __( 'Background', 'soledad' ),
			'type'    => Controls_Manager::COLOR,
			'selectors'  => array(
				'{{WRAPPER}} .penci_az_taxonomy_listing_head, {{WRAPPER}} .penci_az_taxonomy_listing_head.style-2 a' => 'background: {{VALUE}};'
			),
		) );
		
		$this->add_control( 'pcaz_heading_bghv', array(
			'label'   => __( 'Hover Background', 'soledad' ),
			'type'    => Controls_Manager::COLOR,
			'condition' => [
				'show_head_style' => 'style-2',
			],
			'selectors'  => array(
				'{{WRAPPER}} .penci_az_taxonomy_listing_head.style-2 a:hover' => 'background: {{VALUE}};'
			),
		) );
		
		$this->add_responsive_control( 'pcaz_heading_padding', array(
			'label'   => __( 'Padding', 'soledad' ),
			'type'    => Controls_Manager::DIMENSIONS,
			'selectors'  => array(
				'{{WRAPPER}} .penci_az_taxonomy_listing_head' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
			),
		) );
		
		$this->add_responsive_control( 'pcaz_heading_margin', array(
			'label'   => __( 'Margin', 'soledad' ),
			'type'    => Controls_Manager::DIMENSIONS,
			'selectors'  => array(
				'{{WRAPPER}} .penci_az_taxonomy_listing_head' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
			),
		) );

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'pcaz_heading_border',
				'selector' => '{{WRAPPER}} .penci_az_taxonomy_listing_head',
			]
		);

		$this->add_control( 'pcaz_heading_txt_cl', array(
			'label'   => __( 'Link Color', 'soledad' ),
			'type'    => Controls_Manager::COLOR,
			'selectors'  => array(
				'{{WRAPPER}} .penci_az_taxonomy_listing_head a' => 'color: {{VALUE}};'
			),
		) );
		
		$this->add_control( 'pcaz_heading_txt_hcl', array(
			'label'   => __( 'Link Hover Color', 'soledad' ),
			'type'    => Controls_Manager::COLOR,
			'selectors'  => array(
				'{{WRAPPER}} .penci_az_taxonomy_listing_head a:hover' => 'color: {{VALUE}};'
			),
		) );

		$this->add_group_control( Group_Control_Typography::get_type(), array(
			'name'     => 'pcaz_heading_txt_typo',
			'label'    => __( 'Link Typography', 'soledad' ),
			'selector' => '{{WRAPPER}} .penci_az_taxonomy_listing_head a',
		) );

		$this->end_controls_section();

		// Heading Style
		$this->start_controls_section( 'section_headingitem_style', array(
			'label' => esc_html__( 'Character', 'soledad' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		) );

		$this->add_control( 'pcaz_item_heading_cl', array(
			'label'   => __( 'Heading Color', 'soledad' ),
			'type'    => Controls_Manager::COLOR,
			'selectors'  => array(
				'{{WRAPPER}} .penci_az_taxonomy_listing h3' => 'color: {{VALUE}};'
			),
		) );

		$this->add_group_control( Group_Control_Typography::get_type(), array(
			'name'     => 'pcaz_item_heading_typo',
			'label'    => __( 'Heading Typography', 'soledad' ),
			'selector' => '{{WRAPPER}} .penci_az_taxonomy_listing h3',
		) );

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'pcaz_item_heading_border',
				'selector' => '{{WRAPPER}} .penci_az_taxonomy_listing h3',
			]
		);

		$this->end_controls_section();

		// Item Style
		$this->start_controls_section( 'section_item_style', array(
			'label' => esc_html__( 'Links Style', 'soledad' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		) );

		$this->add_control( 'pcaz_link_cl', array(
			'label'   => __( 'Links Color', 'soledad' ),
			'type'    => Controls_Manager::COLOR,
			'selectors'  => array(
				'{{WRAPPER}} .penci_az_taxonomy_item a' => 'color: {{VALUE}};'
			),
		) );
		
		$this->add_control( 'pcaz_link_hcl', array(
			'label'   => __( 'Links Hover Color', 'soledad' ),
			'type'    => Controls_Manager::COLOR,
			'selectors'  => array(
				'{{WRAPPER}} .penci_az_taxonomy_item a:hover' => 'color: {{VALUE}};'
			),
		) );

		$this->add_group_control( Group_Control_Typography::get_type(), array(
			'name'     => 'pcaz_link_typo',
			'label'    => __( 'Links Typography', 'soledad' ),
			'selector' => '{{WRAPPER}} .penci_az_taxonomy_item a',
		) );

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'pcaz_link_border',
				'selector' => '{{WRAPPER}} .penci_az_taxonomy_item ul li:not(:last-child)',
			]
		);

		$this->add_responsive_control( 'pcaz_link_spacing', array(
			'label'   => __( 'Links Spacing', 'soledad' ),
			'type'    => Controls_Manager::SLIDER,
			'selectors'  => array(
				'{{WRAPPER}} .penci_az_taxonomy_item ul li:not(:last-child)' => 'padding-bottom: {{SIZE}}{{UNIT}};margin-bottom: {{SIZE}}{{UNIT}};'
			),
		) );

		$this->end_controls_section();

		$this->start_controls_section( 'section_count_style', array(
			'label' => esc_html__( 'Count Style', 'soledad' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		) );

		$this->add_control( 'pcaz_count_cl', array(
			'label'   => __( 'Number Color', 'soledad' ),
			'type'    => Controls_Manager::COLOR,
			'selectors'  => array(
				'{{WRAPPER}} .penci_az_taxonomy_item .pcaz_tcount' => 'color: {{VALUE}};'
			),
		) );
		
		$this->add_control( 'pcaz_count_hcl', array(
			'label'   => __( 'Number Hover Color', 'soledad' ),
			'type'    => Controls_Manager::COLOR,
			'selectors'  => array(
				'{{WRAPPER}} .penci_az_taxonomy_item a:hover .pcaz_tcount' => 'color: {{VALUE}};'
			),
		) );

		$this->add_group_control( Group_Control_Typography::get_type(), array(
			'name'     => 'pcaz_count_typo',
			'label'    => __( 'Number Typography', 'soledad' ),
			'selector' => '{{WRAPPER}} .penci_az_taxonomy_item .pcaz_tcount',
		) );

		$this->end_controls_section();

		$this->register_block_title_style_section_controls();
	}


	protected function render() {
		$settings = $this->get_settings();

		$ars_terms = array(
			'taxonomy'   => $settings['term_name'],
			'hide_empty' => $settings['hide_empty'] ? true : false,
			'orderby'    => $settings['orderby'],
			'order'      => $settings['order'],
		);

		$heading_style = $settings['show_head_style'];
		

		if ( $settings['taxonomies_ex'] ) {
			$ars_terms['exclude'] = $settings['taxonomies_ex'];
		}
		

		$paz_terms = get_terms( $ars_terms );

		
		$paz_grouped_terms = [];

		if ( ! empty( $paz_terms ) ) {

			foreach ( $paz_terms as $term_data ) {
				// Get the first letter (uppercase)
				$first_letter = strtoupper( mb_substr( $term_data->name, 0, 1 ) );

				// Check if the first letter key exists, if not, create it
				if ( ! isset( $paz_grouped_terms[ $first_letter ] ) ) {
					$paz_grouped_terms[ $first_letter ] = [];
				}

				// Add the term to the appropriate group
				$paz_grouped_terms[ $first_letter ][] = $term_data;
			}

			// Sort the groups alphabetically by first letter
			ksort( $paz_grouped_terms );

			echo '<div class="penci_az_taxonomy_listing_wrap">';

			$this->markup_block_title( $settings, $this );

			if ( $settings['show_head'] ) {

				echo '<div class="penci_az_taxonomy_listing_head '.$heading_style.'"><ul>';
				foreach ( $paz_grouped_terms as $paz_letter => $term_items ) {
					echo '<li><a href="#penci_az_'. esc_html( strtolower($paz_letter) ) .'">' . esc_html( $paz_letter ) . '</a></li>';
				}
				echo '</ul></div>';

			}


			echo '<div class="penci_az_taxonomy_listing">';

			foreach ( $paz_grouped_terms as $paz_letter => $term_items ) {
				if ( ! empty( $term_items) ) {
					echo '<div id="penci_az_'. esc_html( strtolower($paz_letter) ) .'" class="penci_az_taxonomy_item">';
					echo '<h3>' . esc_html( $paz_letter ) . '</h3><ul>';
					foreach ( $term_items as $term_sub_item ) {

						$term_name = $term_sub_item->name;

						if ( $term_name && $term_name !== '' ) {
							$count = $settings['show_count'] ? '<span class="pcaz_tcount">(' . $term_sub_item->count . ')</span>' : '';
							echo '<li><a href="' . get_term_link( $term_sub_item->term_id ) . '">' . $term_name .$count. '</a></li>';
						}
					}
					echo '</ul>';
					echo '</div>';
				}
			}
			echo '</div>';

			echo '</div>';
		} else {
			echo '<div class="penci_az_taxonomy_listing_wrap">';
			$this->markup_block_title( $settings, $this );
			echo '<div class="penci_az_taxonomy_listing">';
			echo '<p>' . esc_html__( 'No terms found.', 'soledad' ) . '</p>';
			echo '</div>';
			echo '</div>';
		}

	}
}