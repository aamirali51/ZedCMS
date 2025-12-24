<?php

namespace PenciSoledadElementor\Modules\PenciCategoryListing\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Typography;
use PenciSoledadElementor\Base\Base_Widget;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class PenciCategoryListing extends Base_Widget {

	public function get_name() {
		return 'penci-category-listing';
	}

	public function get_title() {
		return penci_get_theme_name( 'Penci' ) . ' ' . esc_html__( ' Taxonomy Listing', 'soledad' );
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
		return [];
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

		$this->add_control( 'number', [
			'label'   => esc_html__( 'Limit Terms to Show', 'soledad' ),
			'type'    => Controls_Manager::NUMBER,
			'default' => get_option( 'posts_per_page' ),
		] );

		$this->add_control( 'hide_empty', array(
			'label'        => __( 'Hide Empty Items', 'soledad' ),
			'type'         => Controls_Manager::SWITCHER,
			'label_on'     => __( 'Yes', 'soledad' ),
			'label_off'    => __( 'No', 'soledad' ),
			'return_value' => 'yes',
			'default'      => '',
		) );

		$this->add_control( 'hierarchical', array(
			'label'        => __( 'Hierarchical', 'soledad' ),
			'description'  => __( 'Whether to include terms that have non-empty descendants (even if "Hide Empty Items" is set to "Yes").', 'soledad' ),
			'type'         => Controls_Manager::SWITCHER,
			'label_on'     => __( 'Yes', 'soledad' ),
			'label_off'    => __( 'No', 'soledad' ),
			'return_value' => 'yes',
			'default'      => '',
		) );

		$this->add_control( 'style', array(
			'label'   => __( 'Display Style', 'soledad' ),
			'type'    => Controls_Manager::SELECT,
			'default' => 'style-1',
			'options' => array(
				'style-1'  => esc_html__( 'Grid ( Default )', 'soledad' ),
				'style-2'  => esc_html__( 'Masonry', 'soledad' ),
				'style-3'  => esc_html__( 'Style 3', 'soledad' ),
				'style-4'  => esc_html__( 'Style 4', 'soledad' ),
				'style-5'  => esc_html__( 'Style 5', 'soledad' ),
				'style-6'  => esc_html__( 'Style 6', 'soledad' ),
				'style-7'  => esc_html__( 'Style 7', 'soledad' ),
				'style-8'  => esc_html__( 'Style 8', 'soledad' ),
				'style-9'  => esc_html__( 'Style 9', 'soledad' ),
				'style-10' => esc_html__( 'Style 10', 'soledad' ),
				'style-11' => esc_html__( 'Style 11', 'soledad' ),
				'style-12' => esc_html__( 'Style 12', 'soledad' ),
				'style-13' => esc_html__( 'Style 13', 'soledad' ),
				'style-14' => esc_html__( 'Style 14', 'soledad' ),
				'style-15' => esc_html__( 'Style 15', 'soledad' ),
				'style-16' => esc_html__( 'Style 16', 'soledad' ),
				'style-17' => esc_html__( 'Style 17', 'soledad' ),
				'style-18' => esc_html__( 'Style 18', 'soledad' ),
				'style-19' => esc_html__( 'Style 19', 'soledad' ),
				'style-20' => esc_html__( 'Style 20', 'soledad' ),
				'style-21' => esc_html__( 'Style 21', 'soledad' ),
				'style-22' => esc_html__( 'Style 22', 'soledad' ),
			)
		) );

		$this->add_control( 'bg_columns', array(
			'label'     => __( 'Grid/Masonry Style Columns', 'soledad' ),
			'type'      => Controls_Manager::SELECT,
			'default'   => '3',
			'options'   => array(
				'1' => esc_html__( '1 Column', 'soledad' ),
				'2' => esc_html__( '2 Columns', 'soledad' ),
				'3' => esc_html__( '3 Columns', 'soledad' ),
				'4' => esc_html__( '4 Columns', 'soledad' ),
				'5' => esc_html__( '5 Columns', 'soledad' ),
				'6' => esc_html__( '6 Columns', 'soledad' )
			),
			'condition' => array( 'style' => array( 'style-1', 'style-2' ) ),
		) );

		$this->add_control( 'bg_columns_tablet', array(
			'label'     => __( 'Grid/Masonry Style Columns on Tablet', 'soledad' ),
			'type'      => Controls_Manager::SELECT,
			'default'   => '',
			'options'   => array(
				''  => esc_html__( 'Default', 'soledad' ),
				'1' => esc_html__( '1 Column', 'soledad' ),
				'2' => esc_html__( '2 Columns', 'soledad' ),
				'3' => esc_html__( '3 Columns', 'soledad' ),
				'4' => esc_html__( '4 Columns', 'soledad' ),
			),
			'condition' => array( 'style' => array( 'style-1', 'style-2' ) ),
		) );

		$this->add_control( 'bg_columns_mobile', array(
			'label'     => __( 'Grid/Masonry Style Columns on Mobile', 'soledad' ),
			'type'      => Controls_Manager::SELECT,
			'default'   => '1',
			'options'   => array(
				'1' => esc_html__( '1 Column', 'soledad' ),
				'2' => esc_html__( '2 Columns', 'soledad' ),
				'3' => esc_html__( '3 Columns', 'soledad' ),
			),
			'condition' => array( 'style' => array( 'style-1', 'style-2' ) ),
		) );

		$this->add_control( 'bg_postmeta', array(
			'label'    => __( 'Showing Term Data', 'soledad' ),
			'type'     => Controls_Manager::SELECT2,
			'default'  => array( 'name', 'count' ),
			'multiple' => true,
			'options'  => array(
				'name'  => esc_html__( 'Name', 'soledad' ),
				'desc'  => esc_html__( 'Description', 'soledad' ),
				'count' => esc_html__( 'Posts Count', 'soledad' ),
			),
		) );

		$this->add_control( 'hide_meta_small', array(
			'label'        => __( 'Hide Term Meta on Small Grid Items', 'soledad' ),
			'type'         => Controls_Manager::SWITCHER,
			'label_on'     => __( 'Yes', 'soledad' ),
			'label_off'    => __( 'No', 'soledad' ),
			'return_value' => 'yes',
			'default'      => '',
			'condition'    => array(
				'style!'       => array( 'style-1', 'style-2' ),
				'bgquery_type' => 'post'
			),
		) );

		$this->add_control( 'hide_excerpt_small', array(
			'label'        => __( 'Hide Only Post Excerpt on Small Grid Items', 'soledad' ),
			'type'         => Controls_Manager::SWITCHER,
			'label_on'     => __( 'Yes', 'soledad' ),
			'label_off'    => __( 'No', 'soledad' ),
			'return_value' => 'yes',
			'default'      => '',
			'condition'    => array(
				'style!' => array( 'style-1', 'style-2' ),
			),
		) );

		$this->add_control( 'title_length', array(
			'label'   => __( 'Custom Words Length for Term Name', 'soledad' ),
			'type'    => Controls_Manager::NUMBER,
			'min'     => 1,
			'max'     => 100,
			'step'    => 1,
			'default' => '',
		) );

		$this->add_control( 'hide_subtitle_mobile', array(
			'label'        => __( 'Hide Terms Description on Mobile', 'soledad' ),
			'type'         => Controls_Manager::SWITCHER,
			'label_on'     => __( 'Yes', 'soledad' ),
			'label_off'    => __( 'No', 'soledad' ),
			'return_value' => 'yes',
			'default'      => '',
		) );

		$this->add_control( 'excerpt_length', array(
			'label'   => __( 'Custom Words Length for Terms Description', 'soledad' ),
			'type'    => Controls_Manager::NUMBER,
			'min'     => 1,
			'max'     => 500,
			'step'    => 1,
			'default' => 10,
		) );

		$this->add_control( 'show_readmore', array(
			'label'        => __( 'Show View All Posts Button', 'soledad' ),
			'type'         => Controls_Manager::SWITCHER,
			'label_on'     => __( 'Show', 'soledad' ),
			'label_off'    => __( 'Hide', 'soledad' ),
			'return_value' => 'show',
			'default'      => '',
		) );

		$this->add_control( 'hide_rm_small', array(
			'label'        => __( 'Hide View All Posts on Small Grid Items', 'soledad' ),
			'type'         => Controls_Manager::SWITCHER,
			'label_on'     => __( 'Yes', 'soledad' ),
			'label_off'    => __( 'No', 'soledad' ),
			'return_value' => 'yes',
			'default'      => '',
			'condition'    => array(
				'style!'        => array( 'style-1', 'style-2' ),
				'show_readmore' => 'show',
			),
		) );

		$this->add_control( 'hide_readmore_mobile', array(
			'label'        => __( 'Hide View All Posts on Mobile', 'soledad' ),
			'type'         => Controls_Manager::SWITCHER,
			'label_on'     => __( 'Yes', 'soledad' ),
			'label_off'    => __( 'No', 'soledad' ),
			'return_value' => 'yes',
			'default'      => '',
			'conditions'   => array(
				'relation' => 'or',
				'terms'    => array(
					array(
						'terms' => array(
							array( 'name' => 'show_readmore', 'operator' => '===', 'value' => 'show' )
						)
					),
				)
			),
		) );

		$this->add_control( 'show_formaticon', array(
			'label'        => __( 'Hide Bookmark Icon', 'soledad' ),
			'type'         => Controls_Manager::SWITCHER,
			'label_on'     => __( 'Yes', 'soledad' ),
			'label_off'    => __( 'No', 'soledad' ),
			'return_value' => 'yes',
			'default'      => '',
			'selectors'    => array(
				'{{WRAPPER}} .penci-bf-follow-term-wrapper' => 'display: none !important;',
			),
		) );

		$this->add_control( 'onecol_mobile', array(
			'label'        => __( 'Display One Column on Mobile?', 'soledad' ),
			'type'         => Controls_Manager::SWITCHER,
			'label_on'     => __( 'Yes', 'soledad' ),
			'label_off'    => __( 'No', 'soledad' ),
			'return_value' => 'yes',
			'default'      => '',
			'condition'    => array(
				'style!' => array( 'style-1', 'style-2' ),
			),
		) );

		$this->add_control( 'sameh_mobile', array(
			'label'        => __( 'Display Grid Items Same Height on Mobile?', 'soledad' ),
			'type'         => Controls_Manager::SWITCHER,
			'label_on'     => __( 'Yes', 'soledad' ),
			'label_off'    => __( 'No', 'soledad' ),
			'return_value' => 'yes',
			'default'      => '',
			'condition'    => array(
				'onecol_mobile' => 'yes',
				'style!'        => array( 'style-1', 'style-2' ),
			),
		) );

		$this->add_control( 'bgcontent_pos', array(
			'label'     => __( 'Content Position', 'soledad' ),
			'type'      => Controls_Manager::SELECT,
			'default'   => 'on',
			'options'   => array(
				'on'    => esc_html__( 'On Image', 'soledad' ),
				'below' => esc_html__( 'Below Image', 'soledad' ),
				'above' => esc_html__( 'Above Image', 'soledad' ),
			),
			'condition' => array( 'style' => array( 'style-1', 'style-2' ) ),
		) );

		$this->add_responsive_control( 'bg_gap', array(
			'label'     => __( 'Gap Between Grid & Mansonry Items', 'soledad' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => array( 'px' => array( 'min' => 0, 'max' => 200, ) ),
			'selectors' => array(
				'{{WRAPPER}} .penci-bgstyle-1 .penci-dflex'                                              => 'margin-left: calc(-{{SIZE}}px/2); margin-right: calc(-{{SIZE}}px/2); width: calc(100% + {{SIZE}}px);',
				'{{WRAPPER}} .penci-bgstyle-2 .item-masonry, {{WRAPPER}} .penci-bgstyle-1 .penci-bgitem' => 'padding-left: calc({{SIZE}}px/2); padding-right: calc({{SIZE}}px/2); margin-bottom: {{SIZE}}px',
				'{{WRAPPER}} .penci-bgstyle-2 .penci-biggrid-data'                                       => 'margin-left: calc(-{{SIZE}}px/2); margin-right: calc(-{{SIZE}}px/2);',
			),
			'condition' => array( 'style' => array( 'style-1', 'style-2' ) ),
		) );

		$this->add_responsive_control( 'bg_othergap', array(
			'label'     => __( 'Gap Between Items', 'soledad' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => array( 'px' => array( 'min' => 0, 'max' => 200, ) ),
			'selectors' => array(
				'{{WRAPPER}} .penci-biggrid' => '--pcgap: {{SIZE}}px;',
			),
			'condition' => array( 'style!' => array( 'style-1', 'style-2' ) ),
		) );

		$this->add_responsive_control( 'penci_img_ratio', array(
			'label'     => __( 'Adjust Ratio of Images( Unit % )', 'soledad' ),
			'type'      => Controls_Manager::SLIDER,
			'default'   => array( 'size' => 66 ),
			'range'     => array( 'px' => array( 'min' => 1, 'max' => 300, 'step' => 0.5 ) ),
			'selectors' => array(
				'{{WRAPPER}} .penci-bgitem .penci-image-holder:before' => 'padding-top: {{SIZE}}%;',
			),
			'condition' => array( 'style' => array( 'style-1' ) ),
		) );

		$this->add_responsive_control( 'imgradius', array(
			'label'     => __( 'Custom Border Radius for Images', 'soledad' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => array( 'px' => array( 'min' => 0, 'max' => 300, 'step' => 1 ) ),
			'selectors' => array(
				'{{WRAPPER}} .pcbg-thumb, {{WRAPPER}} .pcbg-bgoverlay, {{WRAPPER}} .penci-image-holder' => 'border-radius: {{SIZE}}px; -webkit-border-radius: {{SIZE}}px;',
			)
		) );

		$this->add_responsive_control( 'bg_height', array(
			'label'     => __( 'Custom Item Height (Unit is px)', 'soledad' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => array( 'px' => array( 'min' => 0, 'max' => 2000, ) ),
			'condition' => array( 'style!' => array( 'style-1', 'style-2' ) ),
			'selectors' => array(
				'{{WRAPPER}} .penci-biggrid .penci-fixh' => '--bgh: {{SIZE}}px;',
			),
		) );

		$this->add_control( 'disable_lazy', array(
			'label'        => __( 'Disable Lazyload Images?', 'soledad' ),
			'type'         => Controls_Manager::SWITCHER,
			'label_on'     => __( 'Yes', 'soledad' ),
			'label_off'    => __( 'No', 'soledad' ),
			'return_value' => 'yes',
			'default'      => '',
		) );

		$this->add_control( 'bg_pagination', array(
			'label'     => esc_html__( 'Page Navigation', 'soledad' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
		) );

		$this->add_control( 'paging', array(
			'label'       => __( 'Page Navigation Style', 'soledad' ),
			'type'        => Controls_Manager::SELECT,
			'default'     => 'none',
			'options'     => array(
				'none'     => esc_html__( 'None', 'soledad' ),
				'nextprev' => esc_html__( 'Ajax Next/Previous', 'soledad' ),
				'loadmore' => esc_html__( 'Load More Terms Button', 'soledad' ),
				'scroll'   => esc_html__( 'Infinite Scroll', 'soledad' ),
			),
			'description' => __( 'Load More Terms Button & Infinite Scroll just works on frontend only.', 'soledad' ),
		) );

		$this->add_control( 'paging_align', array(
			'label'     => __( 'Page Navigation Align', 'soledad' ),
			'type'      => Controls_Manager::SELECT,
			'default'   => 'align-center',
			'options'   => array(
				'align-center' => esc_html__( 'Center', 'soledad' ),
				'align-left'   => esc_html__( 'Left', 'soledad' ),
				'align-right'  => esc_html__( 'Right', 'soledad' ),
			),
			'condition' => array( 'paging!' => 'none' ),
		) );

		$this->add_responsive_control( 'paging_matop', array(
			'label'     => __( 'Margin Top for Page Navigation', 'soledad' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => array( '' => array( 'min' => 0, 'max' => 200, ) ),
			'selectors' => array( '{{WRAPPER}} .penci-pagination' => 'margin-top: {{SIZE}}px' ),
		) );

		$this->end_controls_section();

		$this->start_controls_section( 'biggrid_section_imgsize', array(
			'label' => __( 'Custom Image Sizes', 'soledad' ),
		) );

		$this->add_control( 'thumb_size', array(
			'label'   => __( 'Custom Image Size', 'soledad' ),
			'type'    => Controls_Manager::SELECT,
			'default' => '',
			'options' => $this->get_list_image_sizes( true ),
		) );

		$this->add_control( 'bthumb_size', array(
			'label'     => __( 'Image Size for Big Items', 'soledad' ),
			'type'      => Controls_Manager::SELECT,
			'default'   => '',
			'options'   => $this->get_list_image_sizes( true ),
			'condition' => array( 'style!' => array( 'style-1', 'style-2' ) ),
		) );

		$this->add_control( 'mthumb_size', array(
			'label'   => __( 'Custom Image Size for Mobile', 'soledad' ),
			'type'    => Controls_Manager::SELECT,
			'default' => '',
			'options' => $this->get_list_image_sizes( true ),
		) );

		$this->end_controls_section();

		$this->register_block_title_section_controls();

		$this->start_controls_section( 'section_biggrid_design', array(
			'label' => __( 'Item Style', 'soledad' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		) );

		$this->add_control( 'bg_pos_display', array(
			'label'     => esc_html__( 'Content Text Position and Display', 'soledad' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
		) );

		$this->add_control( 'content_horizontal_position', array(
			'label'                => __( 'Content Text Horizontal Position', 'soledad' ),
			'type'                 => Controls_Manager::CHOOSE,
			'label_block'          => false,
			'options'              => array(
				'left'   => array(
					'title' => __( 'Left', 'soledad' ),
					'icon'  => 'eicon-h-align-left',
				),
				'center' => array(
					'title' => __( 'Center', 'soledad' ),
					'icon'  => 'eicon-h-align-center',
				),
				'right'  => array(
					'title' => __( 'Right', 'soledad' ),
					'icon'  => 'eicon-h-align-right',
				),
			),
			'selectors'            => array(
				'{{WRAPPER}} .pcbg-content-inner' => '{{VALUE}}',
			),
			'selectors_dictionary' => array(
				'left'   => 'margin-right: auto',
				'center' => 'margin-left: auto; margin-right: auto;',
				'right'  => 'margin-left: auto',
			),
		) );

		$this->add_control( 'content_vertical_position', array(
			'label'                => __( 'Content Text Vertical Position', 'soledad' ),
			'type'                 => Controls_Manager::CHOOSE,
			'label_block'          => false,
			'options'              => array(
				'top'    => array(
					'title' => __( 'Top', 'soledad' ),
					'icon'  => 'eicon-v-align-top',
				),
				'middle' => array(
					'title' => __( 'Middle', 'soledad' ),
					'icon'  => 'eicon-v-align-middle',
				),
				'bottom' => array(
					'title' => __( 'Bottom', 'soledad' ),
					'icon'  => 'eicon-v-align-bottom',
				),
			),
			'selectors'            => array(
				'{{WRAPPER}} .pcbg-content-flex' => 'align-items: {{VALUE}}',
			),
			'selectors_dictionary' => array(
				'top'    => 'flex-start',
				'middle' => 'center',
				'bottom' => 'flex-end',
			),
		) );

		$this->add_control( 'content_text_align', array(
			'label'       => __( 'Content Text Align', 'soledad' ),
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
				'{{WRAPPER}} .pcbg-content-flex' => 'text-align: {{VALUE}}'
			)
		) );

		$this->add_control( 'content_display', array(
			'label'   => __( 'Content Text Display', 'soledad' ),
			'type'    => Controls_Manager::SELECT,
			'default' => 'block',
			'options' => array(
				'block'        => esc_html__( 'Block', 'soledad' ),
				'inline-block' => esc_html__( 'Inline Block', 'soledad' ),
			),
		) );

		$this->add_responsive_control( 'content_width', array(
			'label'      => __( 'Content Text Max-Width', 'soledad' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => array( '%', 'px' ),
			'range'      => array(
				'%'  => array( 'min' => 0, 'max' => 100, ),
				'px' => array( 'min' => 0, 'max' => 2000, 'step' => 1 ),
			),
			'selectors'  => array( '{{WRAPPER}} .pcbg-content-inner' => 'max-width: {{SIZE}}{{UNIT}}' ),
		) );

		$this->add_control( 'bg_padding_margin', array(
			'label'     => esc_html__( 'Content Text Padding and Margin', 'soledad' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
		) );

		$this->add_responsive_control( 'content_padding', array(
			'label'      => __( 'Content Text Padding', 'soledad' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => array( 'px', '%', 'em' ),
			'selectors'  => array(
				'{{WRAPPER}} .pcbg-content-inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
			),
		) );

		$this->add_responsive_control( 'content_margin', array(
			'label'      => __( 'Content Text Margin', 'soledad' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => array( 'px', '%', 'em' ),
			'selectors'  => array(
				'{{WRAPPER}} .pcbg-content-inner' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
			),
		) );

		$this->end_controls_section();

		$this->start_controls_section( 'section_biggrid_overlay', array(
			'label' => __( 'Overlay Style', 'soledad' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		) );

		$this->add_control( 'overlay_type', array(
			'label'   => __( 'Apply Overlay On:', 'soledad' ),
			'type'    => Controls_Manager::SELECT,
			'default' => 'whole',
			'options' => array(
				'whole' => 'Whole Image',
				'text'  => 'Whole Content Text',
				'none'  => 'None',
			)
		) );

		$this->add_group_control( Group_Control_Background::get_type(), array(
			'name'      => 'overlay_bg',
			'label'     => __( 'Overlay Background', 'soledad' ),
			'types'     => array( 'classic', 'gradient', 'video' ),
			'selector'  => '{{WRAPPER}} .pcbg-bgoverlay.active-overlay, {{WRAPPER}} .pcbg-bgoverlaytext.active-overlay',
			'condition' => array( 'overlay_type!' => array( 'none' ) ),
		) );

		$this->add_responsive_control( 'overlay_opacity', array(
			'label'     => __( 'Overlay Opacity(%)', 'soledad' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => array( '%' => array( 'min' => 0, 'max' => 100, 'step' => 1 ) ),
			'selectors' => array(
				'{{WRAPPER}} .pcbg-bgoverlay.active-overlay'     => 'opacity: calc( {{SIZE}}/100 )',
				'{{WRAPPER}} .pcbg-bgoverlaytext.active-overlay' => 'opacity: calc( {{SIZE}}/100 )',
			),
		) );

		$this->add_responsive_control( 'overlay_hopacity', array(
			'label'     => __( 'Overlay Hover Opacity(%)', 'soledad' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => array( '%' => array( 'min' => 0, 'max' => 100, 'step' => 1 ) ),
			'selectors' => array(
				'{{WRAPPER}} .penci-bgmain:hover .pcbg-bgoverlay.active-overlay'     => 'opacity: calc( {{SIZE}}/100 )',
				'{{WRAPPER}} .penci-bgmain:hover .pcbg-bgoverlaytext.active-overlay' => 'opacity: calc( {{SIZE}}/100 )',
			),
		) );

		$this->add_control( 'apply_spe_bg_title', array(
			'label' => __( 'Apply Separate Background for Title', 'soledad' ),
			'type'  => Controls_Manager::SWITCHER,
		) );

		$this->add_control( 'spe_bg_title', array(
			'label'     => __( 'Background for Title', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => array(
				'{{WRAPPER}} .pcbg-title' => 'background-color: {{VALUE}}; border-color: {{VALUE}};',
			),
			'condition' => array( 'apply_spe_bg_title' => 'yes' ),
		) );

		$this->add_control( 'spe_bg_htitle', array(
			'label'     => __( 'Background for Title on Hover', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => array(
				'{{WRAPPER}} .penci-bgitem:hover .pcbg-title' => 'background-color: {{VALUE}}; border-color: {{VALUE}};',
			),
			'condition' => array( 'apply_spe_bg_title' => 'yes' ),
		) );

		$this->add_control( 'apply_spe_bg_meta', array(
			'label' => __( 'Apply Separate Background for Term Meta', 'soledad' ),
			'type'  => Controls_Manager::SWITCHER,
		) );

		$this->add_control( 'spe_bg_meta', array(
			'label'     => __( 'Background for Term Meta', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => array(
				'{{WRAPPER}} .pcbg-meta-desc' => 'background-color: {{VALUE}}; border-color: {{VALUE}};',
			),
			'condition' => array( 'apply_spe_bg_meta' => 'yes' ),
		) );

		$this->add_control( 'spe_bg_hmeta', array(
			'label'     => __( 'Background for Term Meta on Hover', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => array(
				'{{WRAPPER}} .penci-bgitem:hover .pcbg-meta-desc' => 'background-color: {{VALUE}}; border-color: {{VALUE}};',
			),
			'condition' => array( 'apply_spe_bg_meta' => 'yes' ),
		) );

		$this->end_controls_section();

		$this->start_controls_section( 'section_bg_hover_effect', array(
			'label' => __( 'Hover Effect', 'soledad' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		) );

		$this->add_control( 'image_hover', array(
			'label'   => __( 'Image Hover Effect', 'soledad' ),
			'type'    => Controls_Manager::SELECT,
			'default' => 'zoom-in',
			'options' => array(
				'zoom-in'     => 'Zoom-In',
				'zoom-out'    => 'Zoom-out',
				'move-left'   => 'Move to Left',
				'move-right'  => 'Move to Right',
				'move-bottom' => 'Move to Bottom',
				'move-top'    => 'Move to Top',
				'none'        => 'None',
			)
		) );

		$this->add_control( 'text_overlay', array(
			'label'   => __( 'Content Text Hover Type', 'soledad' ),
			'type'    => Controls_Manager::SELECT,
			'default' => 'none',
			'options' => array(
				'none'    => 'None',
				'show-in' => 'Show on Hover',
				'hide-in' => 'Hide on Hover',
			)
		) );

		$this->add_control( 'text_overlay_ani', array(
			'label'     => __( 'Content Text Hover Animation', 'soledad' ),
			'type'      => Controls_Manager::SELECT,
			'default'   => 'movetop',
			'options'   => array(
				'movetop'    => 'Move to Top',
				'movebottom' => 'Move to Bottom',
				'moveleft'   => 'Move to Left',
				'moveright'  => 'Move to Right',
				'zoomin'     => 'Zoom In',
				'zoomout'    => 'Zoom Out',
				'fade'       => 'Fade',
			),
			'condition' => array( 'text_overlay' => array( 'show-in', 'hide-in' ) ),
		) );

		$this->add_control( 'title_anivisi', array(
			'label'     => __( 'Makes Titles Always Visible?', 'soledad' ),
			'type'      => Controls_Manager::SWITCHER,
			'condition' => array( 'text_overlay' => array( 'show-in', 'hide-in' ) ),
		) );

		$this->end_controls_section();

		$this->start_controls_section( 'section_biggrid_typo', array(
			'label' => __( 'Typography & Colors', 'soledad' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		) );

		$this->add_control( 'bgitem_design', array(
			'label'     => esc_html__( 'Terms Items', 'soledad' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
		) );

		$this->add_control( 'ver_border', array(
			'label'        => __( 'Add Vertical Border Between Terms Items', 'soledad' ),
			'type'         => Controls_Manager::SWITCHER,
			'label_on'     => __( 'Yes', 'soledad' ),
			'label_off'    => __( 'No', 'soledad' ),
			'return_value' => 'yes',
			'condition'    => array( 'style' => 'style-1' ),
		) );

		$this->add_control( 'ver_bordercl', array(
			'label'     => __( 'Custom Vertical Border Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => array(
				'{{WRAPPER}} .pcbg-verbd .penci-dflex .penci-bgitem' => 'border-right-color: {{VALUE}};',
			),
			'condition' => array( 'ver_border' => 'yes', 'style' => 'style-1' ),
		) );

		$this->add_responsive_control( 'ver_borderw', array(
			'label'     => __( 'Custom Vertical Border Width', 'soledad' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => array( 'px' => array( 'min' => 0, 'max' => 200, ) ),
			'selectors' => array(
				'{{WRAPPER}} .pcbg-verbd .penci-dflex .penci-bgitem' => 'border-right-width: {{SIZE}}px;',
			),
			'condition' => array( 'ver_border' => 'yes', 'style' => 'style-1' ),
		) );

		$this->add_control( 'bgitem_bg', array(
			'label'     => __( 'Background Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => array(
				'body:not(.pcdm-enable) {{WRAPPER}} .penci-biggrid .penci-bgitin' => 'background-color: {{VALUE}};',
				'body.pcdark-mode {{WRAPPER}} .penci-biggrid .penci-bgitin'       => 'background-color: rgba(55,55,55,0.2);',
			),
		) );

		$this->add_control( 'bgitem_borders', array(
			'label'     => __( 'Borders Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => array(
				'{{WRAPPER}} .penci-biggrid .penci-bgitin' => 'border: 1px solid {{VALUE}};',
			),
		) );

		$this->add_responsive_control( 'bgitem_borderwidth', array(
			'label'      => __( 'Borders Width', 'soledad' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => array( 'px', 'em' ),
			'selectors'  => array(
				'{{WRAPPER}} .penci-biggrid .penci-bgitin' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
			),
		) );

		$this->add_responsive_control( 'bgitem_padding', array(
			'label'      => __( 'Padding', 'soledad' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => array( 'px', '%', 'em' ),
			'selectors'  => array(
				'{{WRAPPER}} .penci-biggrid .penci-bgitin' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
			),
		) );

		// Box Shadow
		$this->add_control( 'heading_featured_image_shadow', array(
			'label'     => __( 'Featured Image Shadow', 'soledad' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
		) );

		$this->add_control( 'featured_image_shadow_enable', array(
			'label' => __( 'Enable Shadow?', 'soledad' ),
			'type'  => Controls_Manager::SWITCHER,
		) );

		$this->add_responsive_control( 'featured_image_shadow', array(
			'label'     => __( 'Image Shadow', 'soledad' ),
			'type'      => Controls_Manager::BOX_SHADOW,
			'selectors' => [
				'{{WRAPPER}} .penci-bgitin, {{WRAPPER}} .pcbg-thumb' => 'box-shadow: {{HORIZONTAL}}px {{VERTICAL}}px {{BLUR}}px {{SPREAD}}px {{COLOR}} {{box_shadow_position.VALUE}};',
			],
			'condition' => [ 'featured_image_shadow_enable' => 'yes' ]
		) );

		$this->add_control( 'title_design', array(
			'label'     => esc_html__( 'Term Title', 'soledad' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
		) );

		$this->add_control( 'bgtitle_color', array(
			'label'     => __( 'Title Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => array( '{{WRAPPER}} .pcbg-content-inner .pcbg-title a,{{WRAPPER}} .pcbg-content-inner .pcbg-title' => 'color: {{VALUE}};' ),
		) );

		$this->add_control( 'bgtitle_color_hover', array(
			'label'     => __( 'Title Hover Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => array( '{{WRAPPER}} .penci-bgmain:hover .pcbg-content-inner .pcbg-title a' => 'color: {{VALUE}};' ),
		) );

		$this->add_group_control( Group_Control_Typography::get_type(), array(
			'name'      => 'bgtitle_typo_big',
			'label'     => __( 'Title Typography for Big Items', 'soledad' ),
			'selector'  => '{{WRAPPER}} .pcbg-big-item .pcbg-content-inner .pcbg-title,{{WRAPPER}} .pcbg-big-item .pcbg-content-inner .pcbg-title a',
			'condition' => array( 'style!' => array( 'style-1', 'style-2' ) ),
		) );

		$this->add_group_control( Group_Control_Typography::get_type(), array(
			'name'     => 'bgtitle_typo',
			'label'    => __( 'Title Typography', 'soledad' ),
			'selector' => '{{WRAPPER}} .pcbg-content-inner .pcbg-title,{{WRAPPER}} .pcbg-content-inner .pcbg-title a',
		) );

		$this->add_control( 'desc_design', array(
			'label'     => esc_html__( 'Term Meta Text', 'soledad' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
		) );

		$this->add_control( 'bgdesc_color', array(
			'label'     => __( 'Text Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => array(
				'{{WRAPPER}} .pcbg-content-inner .pcbg-meta'      => 'color: {{VALUE}};',
				'{{WRAPPER}} .pcbg-content-inner .pcbg-meta span' => 'color: {{VALUE}};',
			),
		) );

		$this->add_control( 'bgdesc_link_color', array(
			'label'     => __( 'Links Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => array(
				'{{WRAPPER}} .pcbg-content-inner .pcbg-meta a'      => 'color: {{VALUE}};',
				'{{WRAPPER}} .pcbg-content-inner .pcbg-meta span a' => 'color: {{VALUE}};',
			),
		) );

		$this->add_control( 'bgdesc_link_hcolor', array(
			'label'     => __( 'Links Hover Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => array(
				'{{WRAPPER}} .pcbg-content-inner .pcbg-meta a:hover'      => 'color: {{VALUE}};',
				'{{WRAPPER}} .pcbg-content-inner .pcbg-meta span a:hover' => 'color: {{VALUE}};',
			),
		) );

		$this->add_group_control( Group_Control_Typography::get_type(), array(
			'name'     => 'title_typo',
			'label'    => __( 'Typography', 'soledad' ),
			'selector' => '{{WRAPPER}} .pcbg-content-inner .pcbg-meta,{{WRAPPER}} .pcbg-content-inner .pcbg-meta span, {{WRAPPER}} .pcbg-content-inner .pcbg-meta a',
		) );

		$this->add_control( 'excerpt_design', array(
			'label'     => esc_html__( 'Terms Description', 'soledad' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
		) );

		$this->add_control( 'excerpt_tcolor', array(
			'label'     => __( 'Text Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => array(
				'{{WRAPPER}} .pcbg-pexcerpt, {{WRAPPER}} .pcbg-pexcerpt a, {{WRAPPER}} .pcbg-pexcerpt p' => 'color: {{VALUE}};',
			),
		) );

		$this->add_group_control( Group_Control_Typography::get_type(), array(
			'name'     => 'excerpt_typo',
			'label'    => __( 'Typography', 'soledad' ),
			'selector' => '{{WRAPPER}} .pcbg-pexcerpt, {{WRAPPER}} .pcbg-pexcerpt a, {{WRAPPER}} .pcbg-pexcerpt p',
		) );

		$this->add_control( 'readmore_design', array(
			'label'     => esc_html__( 'View All Button', 'soledad' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
		) );

		$this->add_control( 'readmore_color', array(
			'label'     => __( 'Text Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => array(
				'{{WRAPPER}} .pcbg-readmore-sec .pcbg-readmorebtn' => 'color: {{VALUE}};',
			),
		) );

		$this->add_control( 'readmore_hcolor', array(
			'label'     => __( 'Text Hover Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => array(
				'{{WRAPPER}} .pcbg-readmore-sec .pcbg-readmorebtn:hover, {{WRAPPER}} .pcbg-overlap-hover:hover .pcbg-readmore-sec .pcbg-readmorebtn' => 'color: {{VALUE}};',
			),
		) );

		$this->add_control( 'bgreadmore_color', array(
			'label'     => __( 'Borders Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => array(
				'{{WRAPPER}} .pcbg-readmore-sec .pcbg-readmorebtn' => 'border: 1px solid {{VALUE}};',
			),
		) );

		$this->add_control( 'bgreadmore_hcolor', array(
			'label'     => __( 'Borders Hover Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => array(
				'{{WRAPPER}} .pcbg-readmore-sec .pcbg-readmorebtn:hover, {{WRAPPER}} .pcbg-overlap-hover:hover .pcbg-readmore-sec .pcbg-readmorebtn' => 'border-color: {{VALUE}};',
			),
		) );

		$this->add_control( 'bgreadmore_bgcolor', array(
			'label'     => __( 'Background Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => array(
				'{{WRAPPER}} .pcbg-readmore-sec .pcbg-readmorebtn' => 'background-color: {{VALUE}};',
			),
		) );

		$this->add_control( 'bgreadmore_hbgcolor', array(
			'label'     => __( 'Hover Background Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => array(
				'{{WRAPPER}} .pcbg-readmore-sec .pcbg-readmorebtn:hover, {{WRAPPER}} .pcbg-overlap-hover:hover .pcbg-readmore-sec .pcbg-readmorebtn' => 'background-color: {{VALUE}};',
			),
		) );

		$this->add_group_control( Group_Control_Typography::get_type(), array(
			'name'     => 'bgreadm_typo',
			'label'    => __( 'Typography', 'soledad' ),
			'selector' => '{{WRAPPER}} .pcbg-readmore-sec .pcbg-readmorebtn',
		) );

		$this->add_responsive_control( 'bgreadmore_borderwidth', array(
			'label'      => __( 'Borders Width', 'soledad' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => array( 'px', 'em' ),
			'selectors'  => array(
				'{{WRAPPER}} .pcbg-readmore-sec .pcbg-readmorebtn' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
			),
		) );

		$this->add_responsive_control( 'bgreadmore_borderradius', array(
			'label'      => __( 'Borders Radius', 'soledad' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => array( 'px', '%', 'em' ),
			'selectors'  => array(
				'{{WRAPPER}} .pcbg-readmore-sec .pcbg-readmorebtn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
			),
		) );

		$this->add_responsive_control( 'bgreadmore_padding', array(
			'label'      => __( 'Padding', 'soledad' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => array( 'px', '%', 'em' ),
			'selectors'  => array(
				'{{WRAPPER}} .pcbg-readmore-sec .pcbg-readmorebtn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
			),
		) );

		$this->add_control( 'add_icon_readmore', array(
			'label' => __( 'Add Icon to "View All" Button', 'soledad' ),
			'type'  => Controls_Manager::SWITCHER,
		) );

		$this->add_control( 'readmore_icon', array(
			'label'     => esc_html__( 'Icon', 'soledad' ),
			'type'      => Controls_Manager::ICONS,
			'condition' => array( 'add_icon_readmore' => 'yes' ),
		) );

		$this->add_control( 'readmore_icon_pos', array(
			'label'     => esc_html__( 'Icon position', 'soledad' ),
			'type'      => Controls_Manager::SELECT,
			'options'   => array(
				'right' => esc_html__( 'Right', 'soledad' ),
				'left'  => esc_html__( 'Left', 'soledad' ),
			),
			'default'   => 'right',
			'condition' => array( 'add_icon_readmore' => 'yes' ),
		) );

		$this->add_control( 'pagi_design', array(
			'label'     => esc_html__( 'Page Navigation', 'soledad' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
			'condition' => array(
				'paging!' => 'none',
			),
		) );

		$this->add_responsive_control( 'pagi_mwidth', array(
			'label'      => __( 'Load More Button Max Width', 'soledad' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => array( '%', 'px' ),
			'range'      => array(
				'%'  => array( 'min' => 0, 'max' => 100, ),
				'px' => array( 'min' => 0, 'max' => 2000, 'step' => 1 ),
			),
			'condition'  => array(
				'paging' => array( 'loadmore', 'scroll' ),
			),
			'selectors'  => array(
				'{{WRAPPER}} .penci-pagination.penci-ajax-more a.penci-ajax-more-button' => 'max-width: {{SIZE}}{{UNIT}};',
			),
		) );

		$this->add_control( 'pagi_color', array(
			'label'     => __( 'Text Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => array(
				'{{WRAPPER}} .penci-pagination a'                    => 'color: {{VALUE}};',
				'{{WRAPPER}} .penci-pagination ul.page-numbers li a' => 'color: {{VALUE}};',
			),
			'condition' => array(
				'paging!' => 'none',
			),
		) );

		$this->add_control( 'pagi_hcolor', array(
			'label'     => __( 'Text Hover & Active Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => array(
				'{{WRAPPER}} .penci-pagination a:hover'                         => 'color: {{VALUE}};',
				'{{WRAPPER}} .penci-pagination ul.page-numbers li a:hover'      => 'color: {{VALUE}};',
				'{{WRAPPER}} .penci-pagination ul.page-numbers li span.current' => 'color: {{VALUE}};',
			),
			'condition' => array(
				'paging!' => 'none',
			),
		) );

		$this->add_control( 'bgpagi_color', array(
			'label'     => __( 'Borders Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => array(
				'{{WRAPPER}} .penci-pagination a'                    => 'border-color: {{VALUE}};',
				'{{WRAPPER}} .penci-pagination ul.page-numbers li a' => 'border-color: {{VALUE}};',
			),
			'condition' => array(
				'paging!' => 'none',
			),
		) );

		$this->add_control( 'bgpagi_hcolor', array(
			'label'     => __( 'Borders Hover & Active Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => array(
				'{{WRAPPER}} .penci-pagination a:hover'                         => 'border-color: {{VALUE}};',
				'{{WRAPPER}} .penci-pagination ul.page-numbers li a:hover'      => 'border-color: {{VALUE}};',
				'{{WRAPPER}} .penci-pagination ul.page-numbers li span.current' => 'border-color: {{VALUE}};',
			),
			'condition' => array(
				'paging!' => 'none',
			),
		) );

		$this->add_control( 'bgpagi_bgcolor', array(
			'label'     => __( 'Background Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => array(
				'{{WRAPPER}} .penci-pagination a'                    => 'background-color: {{VALUE}};',
				'{{WRAPPER}} .penci-pagination ul.page-numbers li a' => 'background-color: {{VALUE}};',
			),
			'condition' => array(
				'paging!' => 'none',
			),
		) );

		$this->add_control( 'bgpagi_hbgcolor', array(
			'label'     => __( 'Hover & Active Background Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => array(
				'{{WRAPPER}} .penci-pagination a:hover'                         => 'background-color: {{VALUE}};',
				'{{WRAPPER}} .penci-pagination ul.page-numbers li a:hover'      => 'background-color: {{VALUE}};',
				'{{WRAPPER}} .penci-pagination ul.page-numbers li span.current' => 'background-color: {{VALUE}};',
			),
			'condition' => array(
				'paging!' => 'none',
			),
		) );

		$this->add_group_control( Group_Control_Typography::get_type(), array(
			'name'      => 'bgpagi_typo',
			'label'     => __( 'Typography', 'soledad' ),
			'selector'  => '{{WRAPPER}} .penci-pagination a, {{WRAPPER}} .penci-pagination span.current',
			'condition' => array(
				'paging!' => 'none',
			),
		) );

		$this->add_responsive_control( 'bgpagi_borderwidth', array(
			'label'      => __( 'Borders Width', 'soledad' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => array( 'px', 'em' ),
			'selectors'  => array(
				'{{WRAPPER}} ul.page-numbers li a'         => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				'{{WRAPPER}} ul.page-numbers span.current' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				'{{WRAPPER}} .penci-pagination a'          => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			),
			'condition'  => array(
				'paging!' => 'none',
			),
		) );

		$this->add_responsive_control( 'bgpagi_borderradius', array(
			'label'      => __( 'Borders Radius', 'soledad' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => array( 'px', '%', 'em' ),
			'selectors'  => array(
				'{{WRAPPER}} ul.page-numbers li a'         => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				'{{WRAPPER}} ul.page-numbers span.current' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				'{{WRAPPER}} .penci-pagination a'          => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			),
			'condition'  => array(
				'paging!' => 'none',
			),
		) );

		$this->add_responsive_control( 'bgpagi_padding', array(
			'label'      => __( 'Padding', 'soledad' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => array( 'px', '%', 'em' ),
			'selectors'  => array(
				'{{WRAPPER}} ul.page-numbers li a'         => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				'{{WRAPPER}} ul.page-numbers span.current' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				'{{WRAPPER}} .penci-pagination a'          => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			),
			'condition'  => array(
				'paging!' => 'none',
			),
		) );

		$this->end_controls_section();

		$this->register_block_title_style_section_controls();
	}

	/**
	 * Get image sizes.
	 *
	 * Retrieve available image sizes after filtering `include` and `exclude` arguments.
	 */
	public function get_list_image_sizes( $default = false ) {
		$wp_image_sizes = $this->get_all_image_sizes();

		$image_sizes = array();

		if ( $default ) {
			$image_sizes[''] = esc_html__( 'Default', 'soledad' );
		}

		foreach ( $wp_image_sizes as $size_key => $size_attributes ) {
			$control_title = ucwords( str_replace( '_', ' ', $size_key ) );
			if ( is_array( $size_attributes ) ) {
				$control_title .= sprintf( ' - %d x %d', $size_attributes['width'], $size_attributes['height'] );
			}

			$image_sizes[ $size_key ] = $control_title;
		}

		$image_sizes['full'] = esc_html__( 'Full', 'soledad' );

		return $image_sizes;
	}

	public function get_all_image_sizes() {
		global $_wp_additional_image_sizes;

		$default_image_sizes = [ 'thumbnail', 'medium', 'medium_large', 'large' ];

		$image_sizes = [];

		foreach ( $default_image_sizes as $size ) {
			$image_sizes[ $size ] = [
				'width'  => (int) get_option( $size . '_size_w' ),
				'height' => (int) get_option( $size . '_size_h' ),
				'crop'   => (bool) get_option( $size . '_crop' ),
			];
		}

		if ( $_wp_additional_image_sizes ) {
			$image_sizes = array_merge( $image_sizes, $_wp_additional_image_sizes );
		}

		return $image_sizes;
	}


	protected function render() {
		$settings     = $this->get_settings();
		$biggid_style = $settings['style'] ? $settings['style'] : 'style-1';
		if ( isset( $_GET['bgstyle'] ) ) {
			$biggid_style = esc_attr( $_GET['bgstyle'] );
		}
		$overlay_type      = $settings['overlay_type'] ? $settings['overlay_type'] : 'whole';
		$bgcontent_pos     = $settings['bgcontent_pos'] ? $settings['bgcontent_pos'] : 'on';
		$content_display   = $settings['content_display'] ? $settings['content_display'] : 'block';
		$disable_lazy      = $settings['disable_lazy'] ? $settings['disable_lazy'] : '';
		$disable_lazy 	   = 'yes' == $disable_lazy ? 'false' : 'true';
		$image_hover       = $settings['image_hover'] ? $settings['image_hover'] : 'zoom-in';
		$text_overlay      = $settings['text_overlay'] ? $settings['text_overlay'] : 'none';
		$text_overlay_ani  = $settings['text_overlay_ani'] ? $settings['text_overlay_ani'] : 'movetop';
		$onecol_mobile     = $settings['onecol_mobile'] ? $settings['onecol_mobile'] : '';
		$sameh_mobile      = $settings['sameh_mobile'] ? $settings['sameh_mobile'] : '';
		$thumb_size        = $settings['thumb_size'] ? $settings['thumb_size'] : 'penci-masonry-thumb';
		$bthumb_size       = $settings['bthumb_size'] ? $settings['bthumb_size'] : 'penci-full-thumb';
		$mthumb_size       = $settings['mthumb_size'] ? $settings['mthumb_size'] : 'penci-masonry-thumb';
		$title_length      = $settings['title_length'] ? $settings['title_length'] : 10;
		$excerpt_length    = $settings['excerpt_length'] ? $settings['excerpt_length'] : 10;
		$readmore_icon     = $settings['readmore_icon'] ? $settings['readmore_icon'] : '';
		$readmore_icon_pos = $settings['readmore_icon_pos'] ? $settings['readmore_icon_pos'] : 'right';

		$wrapper_class   = $data_class = '';
		$flag_style      = false;
		$clear_fix_class = 'penci-clearfix ';
		if ( $biggid_style == 'style-1' ) {
			$data_class .= ' penci-dflex';
		} else {
			$data_class .= ' penci-dblock';
		}

		if ( ! in_array( $biggid_style, array( 'style-1', 'style-2' ) ) ) {
			$flag_style    = true;
			$data_class    .= ' penci-fixh';
			$bgcontent_pos = 'on';
		}

		if ( 'style-1' == $biggid_style || 'style-2' == $biggid_style ) {
			$bg_columns        = $settings['bg_columns'] ? $settings['bg_columns'] : '3';
			$bg_columns_tablet = $settings['bg_columns_tablet'] ? $settings['bg_columns_tablet'] : '';
			$bg_columns_mobile = $settings['bg_columns_mobile'] ? $settings['bg_columns_mobile'] : '1';
			$wrapper_class     .= ' penci-grid-col-' . $bg_columns;
			if ( $bg_columns_tablet ) {
				$wrapper_class .= ' penci-grid-tcol-' . $bg_columns_tablet;
			}
			$wrapper_class .= ' penci-grid-mcol-' . $bg_columns_mobile;
		}

		$wrapper_class .= ' penci-bgrid-based-custom penci-bgrid-custom penci-bgrid-content-' . $bgcontent_pos . ' pencibg-imageh-' . $image_hover . ' pencibg-texth-' . $text_overlay . ' pencibg-textani-' . $text_overlay_ani;
		if ( $flag_style && 'yes' == $onecol_mobile ) {
			$wrapper_class .= ' penci-bgrid-monecol';
		}
		if ( $flag_style && 'yes' == $sameh_mobile ) {
			$wrapper_class .= ' penci-bgrid-msameh';
		}

		if ( 'yes' == $settings['title_anivisi'] ) {
			$wrapper_class .= ' pcbg-titles-visible';
		}

		if ( 'yes' == $settings['apply_spe_bg_title'] ) {
			$wrapper_class .= ' pcbg-mask-title';
		}

		if ( 'yes' == $settings['apply_spe_bg_meta'] ) {
			$wrapper_class .= ' pcbg-mask-meta';
		}

		if ( in_array( $text_overlay_ani, array( 'movetop', 'movebottom', 'moveleft', 'moveright' ) ) ) {
			$wrapper_class .= ' textop';
		} else {
			$wrapper_class .= ' notextop';
		}

		if ( 'yes' == $settings['hide_subtitle_mobile'] ) {
			$wrapper_class .= ' hide-msubtitle';
		}
		if ( 'yes' == $settings['hide_readmore_mobile'] ) {
			$wrapper_class .= ' hide-mreadmorebt';
		}
		if ( 'yes' == $settings['ver_border'] && 'style-1' == $biggid_style ) {
			$wrapper_class .= ' pcbg-verbd';
		}


		$ars_terms = array(
			'taxonomy'     => $settings['term_name'],
			'hide_empty'   => $settings['hide_empty'] ? true : false,
			'orderby'      => $settings['orderby'],
			'order'        => $settings['order'],
			'hierarchical' => $settings['hierarchical'] ? true : false,
		);

		/*if ( $settings['taxonomies_inc'] ) {
			$ars_terms['include'] = $settings['taxonomies_inc'];
		}*/

		if ( $settings['taxonomies_ex'] ) {
			$ars_terms['exclude'] = $settings['taxonomies_ex'];
		}

		if ( $settings['number'] ) {
			$ars_terms['number'] = $settings['number'];
		}

		$settings['block_query'] = $ars_terms;

		$block_id            = 'pcblock-' . rand( 0, 9999 );
		$settings['blockid'] = $block_id;
		add_action( 'penci_block_title_extra_' . $block_id, function () use ( $settings ) {

			$link_group_out = $link_group_out_before = $link_group_out_after = '';

			$data_settings_tabs                       = array();
			$data_settings_tabs['query']              = $settings['block_query'];
			$data_settings_tabs['style']              = $settings['style'];
			$data_settings_tabs['overlay_type']       = $settings['overlay_type'];
			$data_settings_tabs['bgcontent_pos']      = $settings['bgcontent_pos'];
			$data_settings_tabs['content_display']    = $settings['content_display'];
			$data_settings_tabs['disable_lazy']       = $settings['disable_lazy'];
			$data_settings_tabs['image_hover']        = $settings['image_hover'];
			$data_settings_tabs['text_overlay']       = $settings['text_overlay'];
			$data_settings_tabs['text_overlay_ani']   = $settings['text_overlay_ani'];
			$data_settings_tabs['thumb_size']         = $settings['thumb_size'];
			$data_settings_tabs['bthumb_size']        = $settings['bthumb_size'];
			$data_settings_tabs['mthumb_size']        = $settings['mthumb_size'];
			$data_settings_tabs['bg_postmeta']        = $settings['bg_postmeta'];
			$data_settings_tabs['show_readmore']      = $settings['show_readmore'];
			$data_settings_tabs['excerpt_length']     = $settings['excerpt_length'];
			$data_settings_tabs['hide_meta_small']    = $settings['hide_meta_small'];
			$data_settings_tabs['hide_excerpt_small'] = $settings['hide_excerpt_small'];
			$data_settings_tabs['hide_rm_small']      = $settings['hide_rm_small'];
			$data_settings_tabs['title_length']       = $settings['title_length'];
			$data_settings_tabs['excerpt_length']     = $settings['excerpt_length'];
			$data_settings_tabs['readmore_icon']      = $settings['readmore_icon'];
			$data_settings_tabs['show_formaticon']    = isset( $settings['show_formaticon'] ) ? $settings['show_formaticon'] : '';
			$data_settings_tabs['readmore_icon_pos']  = $settings['readmore_icon_pos'];

			$ppp = isset( $data_settings_tabs['query']['number'] ) ? $data_settings_tabs['query']['number'] : get_option( 'posts_per_page' );

			$link_group_out_before .= '<nav data-paged="1" data-number="' . $ppp . '" data-query_type="ajaxtab" data-layout="' . esc_attr( $settings['style'] ) . '" data-settings="' . htmlentities( json_encode( $data_settings_tabs ), ENT_QUOTES, "UTF-8" ) . '" class="pcnav-lgroup"><ul class="pcflx">';
			$link_group_out_after  = '</ul></nav>';


			if ( 'nextprev' == $settings['paging'] ) {
				$link_group_out .= '<li class="pcaj-nav-item pcaj-prev"><a class="disable pc-ajaxfil-link pcaj-nav-link prev" data-id="" href="#" aria-label="Previous"><i class="penciicon-left-chevron"></i></a></li>';
				$link_group_out .= '<li class="pcaj-nav-item pcaj-next"><a class="pc-ajaxfil-link pcaj-nav-link next" data-id="" href="#" aria-label="Next"><i class="penciicon-right-chevron"></i></a></li>';
			}

			if ( $link_group_out ) {
				wp_enqueue_script( 'penci_ajax_filter_terms_bg' );
				echo $link_group_out_before . $link_group_out . $link_group_out_after;
			}
		} );

		$big_items    = penci_big_grid_is_big_items( $biggid_style );
		$paging_align = $settings['paging_align'];
		?>
        <div class="penci-clearfix penci-biggrid-terms-wrapper penci-biggrid-wrapper<?php echo $wrapper_class; ?>">
			<?php $this->markup_block_title( $settings, $this ); ?>
            <div class="penci-clearfix penci-biggrid penci-bg<?php echo $biggid_style; ?> penci-bgel">
                <div class="penci-biggrid-inner default">
					<?php
					$bg = 1;

					$biggrid_items      = get_terms( $ars_terms );
					$num_posts          = count( $biggrid_items );
					$post_meta          = $settings['bg_postmeta'];
					$hide_meta_small    = $settings['hide_meta_small'] ? $settings['hide_meta_small'] : '';
					$hide_excerpt_small = $settings['hide_excerpt_small'] ? $settings['hide_excerpt_small'] : '';
					$show_readmore      = $settings['show_readmore'] ? $settings['show_readmore'] : '';
					if ( ! empty( $biggrid_items ) ) {
						echo '<div class="penci-clearfix penci-biggrid-data' . $data_class . '">';
						foreach ( $biggrid_items as $setting ) {
							$is_big_item         = '';
							$hide_cat_small_flag = $hide_meta_small_flag = $hide_rm_small_flag = $hide_excerpt_small_flag = false;
							$surplus             = penci_big_grid_count_classes( $bg, $biggid_style, true );
							$thumbnail           = $thumb_size;
							$post_count          = $setting->count;
							if ( ! empty( $big_items ) && in_array( $surplus, $big_items ) ) {
								$thumbnail   = $bthumb_size;
								$is_big_item = ' pcbg-big-item';
							}
							if ( penci_is_mobile() ) {
								$thumbnail = $mthumb_size;
							}

							if ( ! $is_big_item ) {
								if ( 'yes' == $hide_meta_small ) {
									$hide_meta_small_flag = true;
								}
								if ( 'yes' == $hide_excerpt_small ) {
									$hide_excerpt_small_flag = true;
								}
							}

							$image_url = get_default_term_thumb_url( $setting->term_id, $thumbnail );

							/* Get Custom Items Data */
							$item_id     = ' elementor-repeater-item-' . $setting->term_id;
							$image_ratio = penci_get_ratio_size_based_url( $image_url );

							$title      = $setting->name;
							$title_link = get_term_link( $setting->term_id );
							$title_attr = '';

							$desc = $setting->description;

							include dirname( __FILE__ ) . "/category.php";

							if ( $flag_style && $surplus == 0 && $bg < $num_posts ) {
								echo '</div><div class="penci-clearfix penci-biggrid-data' . $data_class . '">';
							}

							$bg ++;
						}
						echo '</div>';


					}
					?>
                </div>

				<?php
				$paging = $settings['paging'];


				if ( 'loadmore' == $paging || 'scroll' == $paging ) {
					$data_settings                       = array();
					$data_settings['query']              = $ars_terms;
					$data_settings['style']              = $biggid_style;
					$data_settings['overlay_type']       = $overlay_type;
					$data_settings['bgcontent_pos']      = $bgcontent_pos;
					$data_settings['content_display']    = $content_display;
					$data_settings['disable_lazy']       = $disable_lazy;
					$data_settings['image_hover']        = $image_hover;
					$data_settings['text_overlay']       = $text_overlay;
					$data_settings['text_overlay_ani']   = $text_overlay_ani;
					$data_settings['thumb_size']         = $thumb_size;
					$data_settings['bthumb_size']        = $bthumb_size;
					$data_settings['mthumb_size']        = $mthumb_size;
					$data_settings['bg_postmeta']        = $post_meta;
					$data_settings['show_readmore']      = $show_readmore;
					$data_settings['excerpt_length']     = $settings['excerpt_length'];
					$data_settings['hide_excerpt_small'] = $hide_excerpt_small;
					$data_settings['hide_rm_small']      = $settings['hide_rm_small'];
					$data_settings['title_length']       = $title_length;
					$data_settings['readmore_icon']      = $readmore_icon;
					$data_settings['show_formaticon']    = $settings['show_formaticon'];
					$data_settings['readmore_icon_pos']  = $settings['readmore_icon_pos'];
					$data_paged                          = max( get_query_var( 'paged' ), get_query_var( 'page' ), 1 );

					$data_settings_ajax = htmlentities( json_encode( $data_settings ), ENT_QUOTES, "UTF-8" );

					$button_class = ' penci-ajax-more penci-bgajax-more-terms-click';
					if ( 'loadmore' == $paging ):
						wp_enqueue_script( 'penci_bgajax_more_terms' );
					endif;
					if ( 'scroll' == $paging ):
						$button_class = ' penci-ajax-more penci-bgajax-more-terms-scroll';
						wp_enqueue_script( 'penci_bgajax_more_terms_scroll' );
					endif;
					?>
                    <div class="pcbg-paging penci-pagination <?php echo 'pcbg-paging-' . $paging_align . $button_class; ?>">
                        <a class="penci-ajax-more-button" aria-label="More Items" href="#"
                           data-layout="<?php echo $biggid_style; ?>"
                           data-settings="<?php echo $data_settings_ajax; ?>"
                           data-pagednum="<?php echo( (int) $data_paged + 1 ); ?>"
                           data-mes="<?php echo penci_get_setting( 'penci_trans_no_more_items' ); ?>">
                            <span class="ajax-more-text"><?php echo penci_get_setting( 'penci_trans_load_more_items' ); ?></span><span
                                    class="ajaxdot"></span><?php penci_fawesome_icon( 'fas fa-sync' ); ?>
                        </a>
                    </div>
					<?php
				}
				?>
            </div>
        </div>
		<?php
	}
}
