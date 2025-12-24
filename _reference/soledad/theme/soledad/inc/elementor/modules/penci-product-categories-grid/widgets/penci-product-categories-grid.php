<?php

namespace PenciSoledadElementor\Modules\PenciProductCategoriesGrid\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Typography;
use PenciSoledadElementor\Base\Base_Widget;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Direct access not allowed.
}

/**
 * Elementor widget that inserts an embeddable content into the page, from any given URL.
 *
 * @since 1.0.0
 */
class PenciProductCategoriesGrid extends Base_Widget {
	/**
	 * Get widget name.
	 *
	 * @return string Widget name.
	 * @since 1.0.0
	 * @access public
	 *
	 */
	public function get_name() {
		return 'penci_product_categories_grid';
	}

	/**
	 * Get widget title.
	 *
	 * @return string Widget title.
	 * @since 1.0.0
	 * @access public
	 *
	 */
	public function get_title() {
		return penci_get_theme_name('Penci').' '.esc_html__( ' Categories Grid', 'soledad' );
	}

	/**
	 * Get widget icon.
	 *
	 * @return string Widget icon.
	 * @since 1.0.0
	 * @access public
	 *
	 */
	public function get_icon() {
		return 'eicon-gallery-grid';
	}

	/**
	 * Get widget categories.
	 *
	 * @return array Widget categories.
	 * @since 1.0.0
	 * @access public
	 *Hide Category Count on Mobile
	 */
	public function get_categories() {
		return [ 'penci-elements' ];
	}

	/**
	 * Register the widget controls.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function register_controls() {
		/**
		 * Content tab.
		 */

		/**
		 * General settings.
		 */
		$this->start_controls_section(
			'general_content_section',
			[
				'label' => esc_html__( 'Categories Query', 'soledad' ),
			]
		);

		$this->add_control(
			'taxonomy',
			[
				'label'   => esc_html__( 'Taxonomy', 'soledad' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'category',
				'options' => penci_get_all_taxonomies(),
			]
		);

		$this->add_control(
			'product_cat_number',
			[
				'label'       => esc_html__( 'Number', 'soledad' ),
				'description' => esc_html__( 'Enter the number of categories to display for this element.', 'soledad' ),
				'type'        => Controls_Manager::NUMBER,
			]
		);

		$this->add_control(
			'taxonomy_image',
			[
				'label'   => esc_html__( 'Get image of taxonomy from', 'soledad' ),
				'type'    => Controls_Manager::SELECT,
				'default' => '',
				'options' => array(
					'latest' => esc_html__( 'Latest Post', 'soledad' ),
					'random' => esc_html__( 'Random Post', 'soledad' ),
					'view'   => esc_html__( 'Most View Post', 'soledad' ),
					'field'  => esc_html__( 'Custom Term Field', 'soledad' ),
				),
			]
		);

		$this->add_control(
			'taxonomy_image_view_key',
			[
				'label'     => esc_html__( 'Taxonomy view meta key', 'soledad' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => '',
				'condition' => array( 'taxonomy_image' => array( 'view' ) ),
			]
		);

		$this->add_control(
			'taxonomy_image_field',
			[
				'label'     => esc_html__( 'Taxonomy image field name', 'soledad' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => '',
				'condition' => array( 'taxonomy_image' => array( 'field' ) ),
			]
		);

		$this->add_control(
			'product_cat_orderby',
			[
				'label'   => esc_html__( 'Order by', 'soledad' ),
				'type'    => Controls_Manager::SELECT,
				'default' => '',
				'options' => array(
					''           => '',
					'id'         => esc_html__( 'ID', 'soledad' ),
					'date'       => esc_html__( 'Date', 'soledad' ),
					'title'      => esc_html__( 'Title', 'soledad' ),
					'menu_order' => esc_html__( 'Menu order', 'soledad' ),
					'modified'   => esc_html__( 'Last modified date', 'soledad' ),
				),
			]
		);

		$this->add_control(
			'product_cat_order',
			[
				'label'   => esc_html__( 'Sort order', 'soledad' ),
				'type'    => Controls_Manager::SELECT,
				'default' => '',
				'options' => array(
					''     => esc_html__( 'Inherit', 'soledad' ),
					'DESC' => esc_html__( 'Descending', 'soledad' ),
					'ASC'  => esc_html__( 'Ascending', 'soledad' ),
				),
			]
		);

		// Taxonomy Include

		$this->add_control(
			'product_cat_ids',
			[
				'label'       => esc_html__( 'Categories', 'soledad' ),
				'description' => esc_html__( 'List of product categories.', 'soledad' ),
				'type'        => 'penci_el_autocomplete',
				'search'      => 'penci_get_taxonomies_by_query',
				'render'      => 'penci_get_taxonomies_title_by_id',
				'taxonomy'    => 'product_cat',
				'multiple'    => true,
				'label_block' => true,
				'condition'   => array( 'taxonomy' => array( 'product_cat' ) ),
			]
		);

		$this->add_control(
			'category_ids',
			[
				'label'       => esc_html__( 'Categories', 'soledad' ),
				'description' => esc_html__( 'List of post categories.', 'soledad' ),
				'type'        => 'penci_el_autocomplete',
				'search'      => 'penci_get_taxonomies_by_query',
				'render'      => 'penci_get_taxonomies_title_by_id',
				'taxonomy'    => 'category',
				'multiple'    => true,
				'label_block' => true,
				'condition'   => array( 'taxonomy' => array( 'category' ) ),
			]
		);

		$this->add_control(
			'post_tag_ids',
			[
				'label'       => esc_html__( 'Post Tags', 'soledad' ),
				'description' => esc_html__( 'List of post tags.', 'soledad' ),
				'type'        => 'penci_el_autocomplete',
				'search'      => 'penci_get_taxonomies_by_query',
				'render'      => 'penci_get_taxonomies_title_by_id',
				'taxonomy'    => 'post_tag',
				'multiple'    => true,
				'label_block' => true,
				'condition'   => array( 'taxonomy' => array( 'post_tag' ) ),
			]
		);

		$this->add_control(
			'product_tag_ids',
			[
				'label'       => esc_html__( 'Product Tags', 'soledad' ),
				'description' => esc_html__( 'List of product tags.', 'soledad' ),
				'type'        => 'penci_el_autocomplete',
				'search'      => 'penci_get_taxonomies_by_query',
				'render'      => 'penci_get_taxonomies_title_by_id',
				'taxonomy'    => 'product_tag',
				'multiple'    => true,
				'label_block' => true,
				'condition'   => array( 'taxonomy' => array( 'product_tag' ) ),
			]
		);

		$this->add_control(
			'portfolio-category_ids',
			[
				'label'       => esc_html__( 'Portfolio Categories', 'soledad' ),
				'description' => esc_html__( 'List of porfolio categories.', 'soledad' ),
				'type'        => 'penci_el_autocomplete',
				'search'      => 'penci_get_taxonomies_by_query',
				'render'      => 'penci_get_taxonomies_title_by_id',
				'taxonomy'    => 'portfolio-category',
				'multiple'    => true,
				'label_block' => true,
				'condition'   => array( 'taxonomy' => array( 'portfolio-category' ) ),
			]
		);

		// Taxonomy Exclude

		$this->add_control(
			'product_cat_ids_ex',
			[
				'label'       => esc_html__( 'Exclude Product Categories', 'soledad' ),
				'description' => esc_html__( 'List of product categories you want to exclude.', 'soledad' ),
				'type'        => 'penci_el_autocomplete',
				'search'      => 'penci_get_taxonomies_by_query',
				'render'      => 'penci_get_taxonomies_title_by_id',
				'taxonomy'    => 'product_cat',
				'multiple'    => true,
				'label_block' => true,
				'condition'   => array( 'taxonomy' => array( 'product_cat' ) ),
			]
		);

		$this->add_control(
			'category_ids_ex',
			[
				'label'       => esc_html__( 'Exclude Categories', 'soledad' ),
				'description' => esc_html__( 'List of post categories you want to exclude.', 'soledad' ),
				'type'        => 'penci_el_autocomplete',
				'search'      => 'penci_get_taxonomies_by_query',
				'render'      => 'penci_get_taxonomies_title_by_id',
				'taxonomy'    => 'category',
				'multiple'    => true,
				'label_block' => true,
				'condition'   => array( 'taxonomy' => array( 'category' ) ),
			]
		);

		$this->add_control(
			'post_tag_ids_ex',
			[
				'label'       => esc_html__( 'Exclude Post Tags', 'soledad' ),
				'description' => esc_html__( 'List of post tags you want to exclude.', 'soledad' ),
				'type'        => 'penci_el_autocomplete',
				'search'      => 'penci_get_taxonomies_by_query',
				'render'      => 'penci_get_taxonomies_title_by_id',
				'taxonomy'    => 'post_tag',
				'multiple'    => true,
				'label_block' => true,
				'condition'   => array( 'taxonomy' => array( 'post_tag' ) ),
			]
		);

		$this->add_control(
			'product_tag_ids_ex',
			[
				'label'       => esc_html__( 'Exclude Product Tags', 'soledad' ),
				'description' => esc_html__( 'List of product tags you want to exclude.', 'soledad' ),
				'type'        => 'penci_el_autocomplete',
				'search'      => 'penci_get_taxonomies_by_query',
				'render'      => 'penci_get_taxonomies_title_by_id',
				'taxonomy'    => 'product_tag',
				'multiple'    => true,
				'label_block' => true,
				'condition'   => array( 'taxonomy' => array( 'product_tag' ) ),
			]
		);

		$this->add_control(
			'portfolio-category_ids_ex',
			[
				'label'       => esc_html__( 'Exclude Portfolio Categories', 'soledad' ),
				'description' => esc_html__( 'List of porfolio categories you want to exclude.', 'soledad' ),
				'type'        => 'penci_el_autocomplete',
				'search'      => 'penci_get_taxonomies_by_query',
				'render'      => 'penci_get_taxonomies_title_by_id',
				'taxonomy'    => 'portfolio-category',
				'multiple'    => true,
				'label_block' => true,
				'condition'   => array( 'taxonomy' => array( 'portfolio-category' ) ),
			]
		);

		// End Taxonomy Settings

		$this->add_control(
			'product_cat_hide_empty',
			[
				'label'        => esc_html__( 'Hide empty', 'soledad' ),
				'description'  => esc_html__( 'Don’t display categories that don’t have any products assigned.', 'soledad' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'label_on'     => esc_html__( 'Yes', 'soledad' ),
				'label_off'    => esc_html__( 'No', 'soledad' ),
				'return_value' => 'yes',
			]
		);

		$this->end_controls_section();

		/**
		 * Title settings.
		 */


		// Section layout
		$this->start_controls_section(
			'section_general', array(
				'label' => esc_html__( 'General', 'soledad' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'style', array(
				'label'   => __( 'Categories Grid Style', 'soledad' ),
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
				)
			)
		);

		$this->add_control(
			'bg_columns', array(
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
			)
		);

		$this->add_control(
			'bg_columns_mobile', array(
				'label'     => __( 'Grid/Masonry Style Columns on Mobile', 'soledad' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => '1',
				'options'   => array(
					'1' => esc_html__( '1 Column', 'soledad' ),
					'2' => esc_html__( '2 Columns', 'soledad' ),
				),
				'condition' => array( 'style' => array( 'style-1', 'style-2' ) ),
			)
		);

		$this->add_control(
			'hide_subtitle', array(
				'label'        => __( 'Hide Category Count', 'soledad' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'soledad' ),
				'label_off'    => __( 'No', 'soledad' ),
				'return_value' => 'yes',
				'default'      => '',
			)
		);

		$this->add_control(
			'hide_subtitle_mobile', array(
				'label'        => __( 'Hide Category Count on Mobile', 'soledad' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'soledad' ),
				'label_off'    => __( 'No', 'soledad' ),
				'return_value' => 'yes',
				'default'      => '',
				'condition'    => array( 'hide_subtitle!' => array( 'yes' ) ),
			)
		);

		$this->add_control(
			'onecol_mobile', array(
				'label'        => __( 'Display One Column on Mobile?', 'soledad' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'soledad' ),
				'label_off'    => __( 'No', 'soledad' ),
				'return_value' => 'yes',
				'default'      => '',
				'condition'    => array(
					'style!' => array( 'style-1', 'style-2' ),
				),
			)
		);

		$this->add_control(
			'sameh_mobile', array(
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
			)
		);

		$this->add_control(
			'bgcontent_pos', array(
				'label'     => __( 'Categories Grid Content Position', 'soledad' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'on',
				'options'   => array(
					'on'    => esc_html__( 'On Image', 'soledad' ),
					'below' => esc_html__( 'Below Image', 'soledad' ),
					'above' => esc_html__( 'Above Image', 'soledad' ),
				),
				'condition' => array( 'style' => array( 'style-1', 'style-2' ) ),
			)
		);

		$this->add_responsive_control(
			'bg_gap', array(
				'label'     => __( 'Gap Between Items', 'soledad' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array( 'px' => array( 'min' => 0, 'max' => 200, ) ),
				'selectors' => array(
					'{{WRAPPER}} .penci-bgstyle-1 .penci-dflex'                                              => 'margin-left: calc(-{{SIZE}}px/2); margin-right: calc(-{{SIZE}}px/2); width: calc(100% + {{SIZE}}px);',
					'{{WRAPPER}} .penci-bgstyle-2 .item-masonry, {{WRAPPER}} .penci-bgstyle-1 .penci-bgitem' => 'padding-left: calc({{SIZE}}px/2); padding-right: calc({{SIZE}}px/2); margin-bottom: {{SIZE}}px',
					'{{WRAPPER}} .penci-bgstyle-2 .penci-biggrid-data'                                       => 'margin-left: calc(-{{SIZE}}px/2); margin-right: calc(-{{SIZE}}px/2);',
				),
				'condition' => array( 'style' => array( 'style-1', 'style-2' ) ),
			)
		);

		$this->add_responsive_control(
			'penci_img_ratio', array(
				'label'     => __( 'Adjust Ratio of Images( Unit % )', 'soledad' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => array( 'size' => 66 ),
				'range'     => array( 'px' => array( 'min' => 1, 'max' => 300, 'step' => 0.5 ) ),
				'selectors' => array(
					'{{WRAPPER}} .penci-bgitem .penci-image-holder:before' => 'padding-top: {{SIZE}}%;',
				),
				'condition' => array( 'style' => array( 'style-1' ) ),
			)
		);

		$this->add_responsive_control(
			'bg_height', array(
				'label'     => __( 'Custom Categories Grid Height (Unit is px)', 'soledad' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array( 'px' => array( 'min' => 0, 'max' => 2000, ) ),
				'condition' => array( 'style!' => array( 'style-1', 'style-2' ) ),
				'selectors' => array(
					'{{WRAPPER}} .penci-biggrid .penci-fixh' => '--bgh: {{SIZE}}px;',
				),
			)
		);

		$this->add_control(
			'disable_lazy', array(
				'label'        => __( 'Disable Lazyload Images?', 'soledad' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'soledad' ),
				'label_off'    => __( 'No', 'soledad' ),
				'return_value' => 'yes',
				'default'      => '',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'biggrid_section_imgsize', array(
				'label' => __( 'Custom Image Sizes', 'soledad' ),
			)
		);

		$this->add_control(
			'thumb_size', array(
				'label'   => __( 'Custom Image Size', 'soledad' ),
				'type'    => Controls_Manager::SELECT,
				'default' => '',
				'options' => $this->get_list_image_sizes( true ),
			)
		);

		$this->add_control(
			'bthumb_size', array(
				'label'     => __( 'Image Size for Big Items', 'soledad' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => '',
				'options'   => $this->get_list_image_sizes( true ),
				'condition' => array( 'style!' => array( 'style-1', 'style-2' ) ),
			)
		);

		$this->add_control(
			'mthumb_size', array(
				'label'   => __( 'Custom Image Size for Mobile', 'soledad' ),
				'type'    => Controls_Manager::SELECT,
				'default' => '',
				'options' => $this->get_list_image_sizes( true ),
			)
		);

		$this->end_controls_section();

		$this->register_block_title_section_controls();

		/**
		 * Translate settings.
		 */

		$this->start_controls_section(
			'section_biggrid_translate',
			array(
				'label' => __( 'Quick Text Translate', 'soledad' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'text_item',
			array(
				'label'   => esc_html__( 'Text: % Item', 'soledad' ),
				'type'    => Controls_Manager::TEXT,
				'default' => '% item',
			)
		);

		$this->add_control(
			'text_items',
			array(
				'label'   => esc_html__( 'Text: % Items', 'soledad' ),
				'type'    => Controls_Manager::TEXT,
				'default' => '% items',
			)
		);

		$this->end_controls_section();

		/**
		 * Design settings.
		 */

		$this->start_controls_section(
			'section_biggrid_design',
			array(
				'label' => __( 'Categories Grid Style', 'soledad' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'bg_pos_display',
			array(
				'label'     => esc_html__( 'Content Text Position and Display', 'soledad' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'content_horizontal_position', array(
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
			)
		);

		$this->add_control(
			'content_vertical_position',
			array(
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
			)
		);

		$this->add_control(
			'content_text_align', array(
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
			)
		);

		$this->add_control(
			'content_display', array(
				'label'   => __( 'Content Text Display', 'soledad' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'block',
				'options' => array(
					'block'        => esc_html__( 'Block', 'soledad' ),
					'inline-block' => esc_html__( 'Inline Block', 'soledad' ),
				),
			)
		);

		$this->add_responsive_control(
			'content_width', array(
				'label'      => __( 'Content Text Max-Width', 'soledad' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( '%', 'px' ),
				'range'      => array(
					'%'  => array( 'min' => 0, 'max' => 100, ),
					'px' => array( 'min' => 0, 'max' => 2000, 'step' => 1 ),
				),
				'selectors'  => array( '{{WRAPPER}} .pcbg-content-inner' => 'max-width: {{SIZE}}{{UNIT}}' ),
			)
		);

		$this->add_control(
			'bg_padding_margin',
			array(
				'label'     => esc_html__( 'Content Text Padding and Margin', 'soledad' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_responsive_control(
			'content_padding', array(
				'label'      => __( 'Content Text Padding', 'soledad' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .pcbg-content-inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				),
			)
		);

		$this->add_responsive_control(
			'content_margin', array(
				'label'      => __( 'Content Text Margin', 'soledad' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .pcbg-content-inner' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_biggrid_overlay',
			array(
				'label' => __( 'Categories Grid Overlay', 'soledad' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'overlay_type', array(
				'label'   => __( 'Apply Overlay On:', 'soledad' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'whole',
				'options' => array(
					'whole' => 'Whole Image',
					'text'  => 'Whole Content Text',
					'none'  => 'None',
				)
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'      => 'overlay_bg',
				'label'     => __( 'Overlay Background', 'soledad' ),
				'types'     => array( 'classic', 'gradient', 'video' ),
				'selector'  => '{{WRAPPER}} .pcbg-bgoverlay.active-overlay, {{WRAPPER}} .pcbg-bgoverlaytext.active-overlay',
				'condition' => array( 'overlay_type!' => array( 'none' ) ),
			)
		);

		$this->add_responsive_control(
			'overlay_opacity', array(
				'label'     => __( 'Overlay Opacity(%)', 'soledad' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array( '%' => array( 'min' => 0, 'max' => 100, 'step' => 1 ) ),
				'selectors' => array(
					'{{WRAPPER}} .pcbg-bgoverlay.active-overlay'     => 'opacity: calc( {{SIZE}}/100 )',
					'{{WRAPPER}} .pcbg-bgoverlaytext.active-overlay' => 'opacity: calc( {{SIZE}}/100 )',
				),
			)
		);

		$this->add_responsive_control(
			'overlay_hopacity', array(
				'label'     => __( 'Overlay Hover Opacity(%)', 'soledad' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array( '%' => array( 'min' => 0, 'max' => 100, 'step' => 1 ) ),
				'selectors' => array(
					'{{WRAPPER}} .penci-bgmain:hover .pcbg-bgoverlay.active-overlay'     => 'opacity: calc( {{SIZE}}/100 )',
					'{{WRAPPER}} .penci-bgmain:hover .pcbg-bgoverlaytext.active-overlay' => 'opacity: calc( {{SIZE}}/100 )',
				),
			)
		);

		$this->add_control(
			'apply_spe_bg_title', array(
				'label' => __( 'Apply Separate Background for Title', 'soledad' ),
				'type'  => Controls_Manager::SWITCHER,
			)
		);

		$this->add_control(
			'spe_bg_title', array(
				'label'     => __( 'Background for Title', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .pcbg-title' => 'background-color: {{VALUE}}; border-color: {{VALUE}};',
				),
				'condition' => array( 'apply_spe_bg_title' => 'yes' ),
			)
		);

		$this->add_control(
			'spe_bg_htitle', array(
				'label'     => __( 'Background for Title on Hover', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .penci-bgitem:hover .pcbg-title' => 'background-color: {{VALUE}}; border-color: {{VALUE}};',
				),
				'condition' => array( 'apply_spe_bg_title' => 'yes' ),
			)
		);

		$this->add_control(
			'apply_spe_bg_meta', array(
				'label' => __( 'Apply Separate Background for Category Count', 'soledad' ),
				'type'  => Controls_Manager::SWITCHER,
			)
		);

		$this->add_control(
			'spe_bg_meta', array(
				'label'     => __( 'Background for Category Count', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .pcbg-content-inner .pcbg-sub-title span' => 'background-color: {{VALUE}}; border-color: {{VALUE}};padding-left: 10px;padding-right:10px;',
				),
				'condition' => array( 'apply_spe_bg_meta' => 'yes' ),
			)
		);

		$this->add_control(
			'spe_bg_hmeta', array(
				'label'     => __( 'Background for Category Count on Hover', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .pcbg-content-inner .pcbg-sub-title span:hover' => 'background-color: {{VALUE}}; border-color: {{VALUE}};padding-left: 10px;padding-right:10px;',
				),
				'condition' => array( 'apply_spe_bg_meta' => 'yes' ),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_bg_hover_effect',
			array(
				'label' => __( 'Hover Effect', 'soledad' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'image_hover', array(
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
			)
		);

		$this->add_control(
			'text_overlay', array(
				'label'   => __( 'Content Text Hover Type', 'soledad' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'none',
				'options' => array(
					'none'    => 'None',
					'show-in' => 'Show on Hover',
					'hide-in' => 'Hide on Hover',
				)
			)
		);

		$this->add_control(
			'text_overlay_ani', array(
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
				'condition' => array( 'text_overlay!' => 'none' ),
			)
		);

		$this->add_control(
			'title_anivisi', array(
				'label'     => __( 'Makes Titles Always Visible?', 'soledad' ),
				'type'      => Controls_Manager::SWITCHER,
				'condition' => array( 'text_overlay!' => 'none' ),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_biggrid_typo',
			array(
				'label' => __( 'Categories Grid Typography & Colors', 'soledad' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'bgitem_design',
			array(
				'label'     => esc_html__( 'Categories Grid Items', 'soledad' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'bgitem_bg', array(
				'label'     => __( 'Background Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .penci-biggrid .penci-bgitin' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'bgitem_borders', array(
				'label'     => __( 'Borders Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .penci-biggrid .penci-bgitin' => 'border: 1px solid {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'bgitem_borderwidth', array(
				'label'      => __( 'Borders Width', 'soledad' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .penci-biggrid .penci-bgitin' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				),
			)
		);

		$this->add_responsive_control(
			'bgitem_padding', array(
				'label'      => __( 'Padding', 'soledad' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .penci-biggrid .penci-bgitin' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				),
			)
		);

		$this->add_control(
			'title_design',
			array(
				'label'     => esc_html__( 'Category Title', 'soledad' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'bgtitle_color', array(
				'label'     => __( 'Title Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array( '{{WRAPPER}} .pcbg-content-inner .pcbg-title a,{{WRAPPER}} .pcbg-content-inner .pcbg-title' => 'color: {{VALUE}};' ),
			)
		);

		$this->add_control(
			'bgtitle_color_hover', array(
				'label'     => __( 'Title Hover Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array( '{{WRAPPER}} .pcbg-content-inner .pcbg-title a:hover' => 'color: {{VALUE}};' ),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(), array(
				'name'      => 'bgtitle_typo_big',
				'label'     => __( 'Title Typography for Big Items', 'soledad' ),
				'selector'  => '{{WRAPPER}} .pcbg-big-item .pcbg-content-inner .pcbg-title,{{WRAPPER}} .pcbg-big-item .pcbg-content-inner .pcbg-title a',
				'condition' => array( 'style!' => array( 'style-1', 'style-2' ) ),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(), array(
				'name'     => 'bgtitle_typo',
				'label'    => __( 'Title Typography', 'soledad' ),
				'selector' => '{{WRAPPER}} .pcbg-content-inner .pcbg-title,{{WRAPPER}} .pcbg-content-inner .pcbg-title a',
			)
		);

		$this->add_control(
			'desc_design',
			array(
				'label'     => esc_html__( 'Category Count', 'soledad' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'bgdesc_color', array(
				'label'     => __( 'Text Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .pcbg-content-inner .pcbg-sub-title' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(), array(
				'name'     => 'title_typo',
				'label'    => __( 'Typography', 'soledad' ),
				'selector' => '{{WRAPPER}} .pcbg-content-inner .pcbg-sub-title',
			)
		);

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

	/**
	 * Render the widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 */
	protected function render() {

		$settings     = $this->get_settings();
		$biggid_style = $settings['style'] ? $settings['style'] : 'style-1';
		if ( isset( $_GET['bgstyle'] ) ) {
			$biggid_style = esc_attr( $_GET['bgstyle'] );
		}
		$overlay_type     = $settings['overlay_type'] ? $settings['overlay_type'] : 'whole';
		$bgcontent_pos    = $settings['bgcontent_pos'] ? $settings['bgcontent_pos'] : 'on';
		$content_display  = $settings['content_display'] ? $settings['content_display'] : 'block';
		$disable_lazy     = $settings['disable_lazy'] ? $settings['disable_lazy'] : '';
		$disable_lazy 	  = 'yes' == $disable_lazy ? 'false' : 'true';
		$image_hover      = $settings['image_hover'] ? $settings['image_hover'] : 'zoom-in';
		$text_overlay     = $settings['text_overlay'] ? $settings['text_overlay'] : 'none';
		$text_overlay_ani = $settings['text_overlay_ani'] ? $settings['text_overlay_ani'] : 'movetop';
		$onecol_mobile    = $settings['onecol_mobile'] ? $settings['onecol_mobile'] : '';
		$sameh_mobile     = $settings['sameh_mobile'] ? $settings['sameh_mobile'] : '';
		$thumb_size       = $settings['thumb_size'] ? $settings['thumb_size'] : 'penci-masonry-thumb';
		$bthumb_size      = $settings['bthumb_size'] ? $settings['bthumb_size'] : 'penci-full-thumb';
		$mthumb_size      = $settings['mthumb_size'] ? $settings['mthumb_size'] : 'penci-masonry-thumb';

		$wrapper_class   = $data_class = '';
		$flag_style      = false;
		$clear_fix_class = 'penci-clearfix ';
		if ( in_array( $biggid_style, array( 'style-1' ) ) ) {
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
			$bg_columns_mobile = $settings['bg_columns_mobile'] ? $settings['bg_columns_mobile'] : '1';
			$wrapper_class     .= ' penci-grid-col-' . $bg_columns;
			$wrapper_class     .= ' penci-grid-mcol-' . $bg_columns_mobile;
		}

		$wrapper_class .= ' penci-bgrid-based-product-category' . ' penci-bgrid-' . $biggid_style . ' penci-bgrid-content-' . $bgcontent_pos . ' pencibg-imageh-' . $image_hover . ' pencibg-texth-' . $text_overlay . ' pencibg-textani-' . $text_overlay_ani;
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

		$big_items = penci_big_grid_is_big_items( $biggid_style );

		$product_cat_default_settings = [
			// Query.
			'product_cat_number'  => null,
			'product_cat_orderby' => '',
			'product_cat_order'   => 'ASC',
			'product_cat_ids'     => '',
		];

		$product_cat_settings = wp_parse_args( $this->get_settings_for_display(), $product_cat_default_settings );

		$tax_name = $product_cat_settings['taxonomy'];
		// Query.
		$product_cat_query_args = array(
			'taxonomy'   => $tax_name,
			'order'      => $product_cat_settings['product_cat_order'],
			'hide_empty' => 'yes' === $product_cat_settings['product_cat_hide_empty'],
			'include'    => $product_cat_settings[ $tax_name . '_ids' ],
			'exclude'    => $product_cat_settings[ $tax_name . '_ids_ex' ],
			'pad_counts' => true,
			'number'     => $product_cat_settings['product_cat_number'],
		);

		if ( $settings['product_cat_orderby'] ) {
			$product_cat_query_args['orderby'] = $settings['product_cat_orderby'];
		}

		$product_categories = get_terms( $product_cat_query_args );
		$bg                 = 1;
		$text_item          = $settings['text_item'];
		$text_items         = $settings['text_items'];
		?>
        <div class="penci-clearfix penci-biggrid-wrapper<?php echo $wrapper_class; ?>">
			<?php $this->markup_block_title( $settings, $this ); ?>
            <div class="penci-clearfix penci-biggrid penci-bg<?php echo $biggid_style; ?> penci-bgel">
                <div class="penci-biggrid-inner">
					<?php

					echo '<div class="penci-clearfix penci-biggrid-data' . $data_class . '">';

					$num_cat = count( $product_categories );

					foreach ( $product_categories as $category ) :
						$item_id     = ' elementor-repeater-item-' . $category->term_id;
						$desc        = $category->description;
						$surplus     = penci_big_grid_count_classes( $bg, $biggid_style, true );
						$thumbnail   = $thumb_size;
						$is_big_item = '';
						if ( ! empty( $big_items ) && in_array( $surplus, $big_items ) ) {
							$thumbnail   = $bthumb_size;
							$is_big_item = ' pcbg-big-item';
						}
						if ( penci_is_mobile() ) {
							$thumbnail = $mthumb_size;
						}

						$term_options = array(
							'sort' => isset( $settings['taxonomy_image'] ) && $settings['taxonomy_image'] ? $settings['taxonomy_image'] : 'latest',
							'key'  => isset( $settings['taxonomy_image_view_key'] ) && $settings['taxonomy_image_view_key'] ? $settings['taxonomy_image_view_key'] : '',
						);

						include dirname( __FILE__ ) . "/product-category.php";
						if ( $flag_style && $surplus == 0 && $bg < $num_cat ) {
							echo '</div><div class="penci-clearfix penci-biggrid-data' . $data_class . '">';
						}

						$bg ++;
					endforeach;

					echo '</div>';

					if ( $flag_style ) {
						echo '</div>';
					}
					?>
                </div>
            </div>
        </div>
		<?php
	}
}
