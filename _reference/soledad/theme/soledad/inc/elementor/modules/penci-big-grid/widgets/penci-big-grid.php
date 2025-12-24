<?php

namespace PenciSoledadElementor\Modules\PenciBigGrid\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Typography;
use Elementor\Repeater;
use Elementor\Utils;
use PenciSoledadElementor\Base\Base_Widget;
use PenciSoledadElementor\Modules\QueryControl\Controls\Penci_Group_Control_Posts;
use PenciSoledadElementor\Modules\QueryControl\Module as Query_Control;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class PenciBigGrid extends Base_Widget {

	public function get_name() {
		return 'penci-big-grid';
	}

	public function get_title() {
		return penci_get_theme_name( 'Penci' ) . ' ' . esc_html__( ' Big Grid', 'soledad' );
	}

	public function get_icon() {
		return 'eicon-gallery-grid';
	}

	public function get_categories() {
		return [ 'penci-elements' ];
	}

	public function get_keywords() {
		return array( 'big grid', 'post' );
	}

	public function get_script_depends() {
		return [ 'penci_ajax_filter_bg' ];
	}

	protected function register_controls() {


		// Section layout
		$this->start_controls_section( 'section_general', array(
			'label' => esc_html__( 'General', 'soledad' ),
			'tab'   => Controls_Manager::TAB_CONTENT,
		) );

		$this->add_control( 'bgquery_type', array(
			'label'   => __( 'Query Type:', 'soledad' ),
			'type'    => Controls_Manager::SELECT,
			'default' => 'post',
			'options' => array(
				'post'   => esc_html__( 'Based Posts', 'soledad' ),
				'custom' => esc_html__( 'Custom', 'soledad' ),
			)
		) );

		$this->add_control( 'style', array(
			'label'   => __( 'Big Grid Style', 'soledad' ),
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
			'label'     => __( 'Showing Post Data', 'soledad' ),
			'type'      => Controls_Manager::SELECT2,
			'default'   => array( 'cat', 'title', 'author', 'date' ),
			'multiple'  => true,
			'options'   => array(
				'cat'     => esc_html__( 'Categories', 'soledad' ),
				'title'   => esc_html__( 'Title', 'soledad' ),
				'author'  => esc_html__( 'Author', 'soledad' ),
				'date'    => esc_html__( 'Date', 'soledad' ),
				'comment' => esc_html__( 'Comments', 'soledad' ),
				'views'   => esc_html__( 'Views', 'soledad' ),
				'reading' => esc_html__( 'Reading Time', 'soledad' ),
				'excerpt' => esc_html__( 'Post Excerpt', 'soledad' ),
			),
			'condition' => array( 'bgquery_type' => 'post' ),
		) );

		$this->add_control( 'title_length', array(
			'label'     => __( 'Custom Title Words Length', 'soledad' ),
			'type'      => Controls_Manager::NUMBER,
			'min'       => 1,
			'max'       => 100,
			'step'      => 1,
			'default'   => '',
			'condition' => array( 'bgquery_type' => 'post' ),
		) );

		$this->add_control( 'primary_cat', array(
			'label'        => __( 'Show Primary Category Only', 'soledad' ),
			'type'         => Controls_Manager::SWITCHER,
			'description'  => __( 'If you using Yoast SEO or Rank Math plugin, this option will show only the primary category from those plugins. If you don\'t use those plugins, it will show the first category in the list categories of the posts.', 'soledad' ),
			'label_on'     => __( 'On', 'soledad' ),
			'label_off'    => __( 'Off', 'soledad' ),
			'return_value' => 'on',
			'default'      => '',
			'condition'    => array( 'bgquery_type' => 'post' ),
		) );

		$this->add_control( 'hide_cat_small', array(
			'label'        => __( 'Hide Post Categories on Small Grid Items', 'soledad' ),
			'type'         => Controls_Manager::SWITCHER,
			'label_on'     => __( 'Yes', 'soledad' ),
			'label_off'    => __( 'No', 'soledad' ),
			'return_value' => 'yes',
			'default'      => 'yes',
			'condition'    => array(
				'style!'       => array( 'style-1', 'style-2' ),
				'bgquery_type' => 'post'
			),
		) );

		$this->add_control( 'hide_meta_small', array(
			'label'        => __( 'Hide Post Meta( Author, Date.. ) on Small Grid Items', 'soledad' ),
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
				'style!'       => array( 'style-1', 'style-2' ),
				'bgquery_type' => 'post'
			),
		) );

		$this->add_control( 'hide_subtitle_mobile', array(
			'label'        => __( 'Hide Post Categories/Sub Title on Mobile', 'soledad' ),
			'type'         => Controls_Manager::SWITCHER,
			'label_on'     => __( 'Yes', 'soledad' ),
			'label_off'    => __( 'No', 'soledad' ),
			'return_value' => 'yes',
			'default'      => '',
		) );

		$this->add_control( 'hide_desc_mobile', array(
			'label'        => __( 'Hide All Post Meta/Description on Mobile', 'soledad' ),
			'type'         => Controls_Manager::SWITCHER,
			'label_on'     => __( 'Yes', 'soledad' ),
			'label_off'    => __( 'No', 'soledad' ),
			'return_value' => 'yes',
			'default'      => '',
		) );

		$this->add_control( 'hide_excerpt_mobile', array(
			'label'        => __( 'Hide Post Excerpt on Mobile', 'soledad' ),
			'type'         => Controls_Manager::SWITCHER,
			'label_on'     => __( 'Yes', 'soledad' ),
			'label_off'    => __( 'No', 'soledad' ),
			'return_value' => 'yes',
			'default'      => '',
			'condition'    => array( 'bgquery_type' => 'post' ),
		) );

		$this->add_control( 'excerpt_length', array(
			'label'     => __( 'Custom Excerpt Length', 'soledad' ),
			'type'      => Controls_Manager::NUMBER,
			'min'       => 1,
			'max'       => 500,
			'step'      => 1,
			'default'   => 10,
			'condition' => array( 'bgquery_type' => 'post' ),
		) );

		$this->add_control( 'show_readmore', array(
			'label'        => __( 'Show Read More Button', 'soledad' ),
			'type'         => Controls_Manager::SWITCHER,
			'label_on'     => __( 'Show', 'soledad' ),
			'label_off'    => __( 'Hide', 'soledad' ),
			'return_value' => 'show',
			'default'      => '',
			'condition'    => array( 'bgquery_type' => 'post' ),
		) );

		$this->add_control( 'hide_rm_small', array(
			'label'        => __( 'Hide Read More Button on Small Grid Items', 'soledad' ),
			'type'         => Controls_Manager::SWITCHER,
			'label_on'     => __( 'Yes', 'soledad' ),
			'label_off'    => __( 'No', 'soledad' ),
			'return_value' => 'yes',
			'default'      => '',
			'condition'    => array(
				'style!'        => array( 'style-1', 'style-2' ),
				'show_readmore' => 'show',
				'bgquery_type'  => 'post'
			),
		) );

		$this->add_control( 'hide_readmore_mobile', array(
			'label'        => __( 'Hide Read More Button on Mobile', 'soledad' ),
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
							array( 'name' => 'bgquery_type', 'operator' => '===', 'value' => 'post' ),
							array( 'name' => 'show_readmore', 'operator' => '===', 'value' => 'show' )
						)
					),
					array(
						'terms' => array(
							array( 'name' => 'bgquery_type', 'operator' => '===', 'value' => 'custom' )
						)
					),
				)
			),
		) );

		$this->add_control( 'show_formaticon', array(
			'label'        => __( 'Show Post Format Icons', 'soledad' ),
			'type'         => Controls_Manager::SWITCHER,
			'label_on'     => __( 'Yes', 'soledad' ),
			'label_off'    => __( 'No', 'soledad' ),
			'return_value' => 'yes',
			'default'      => '',
			'condition'    => array(
				'bgquery_type' => 'post'
			),
		) );

		$this->add_control( 'formaticon_pos', array(
			'label'     => __( 'Post Format Icon Position', 'soledad' ),
			'type'      => Controls_Manager::SELECT,
			'default'   => 'top-right',
			'options'   => array(
				'top-right'    => esc_html__( 'Top Right', 'soledad' ),
				'top-left'     => esc_html__( 'Top Left', 'soledad' ),
				'bottom-right' => esc_html__( 'Bottom Right', 'soledad' ),
				'bottom-left'  => esc_html__( 'Bottom Left', 'soledad' ),
				'center'       => esc_html__( 'Center', 'soledad' ),
			),
			'condition' => array( 'bgquery_type' => 'post', 'show_formaticon' => 'yes' ),
		) );

		$this->add_control( 'show_reviewpie', array(
			'label'        => __( 'Show Review Scores from Penci Review plugin', 'soledad' ),
			'type'         => Controls_Manager::SWITCHER,
			'label_on'     => __( 'Yes', 'soledad' ),
			'label_off'    => __( 'No', 'soledad' ),
			'return_value' => 'yes',
			'default'      => '',
			'condition'    => array(
				'bgquery_type' => 'post'
			),
		) );

		$this->add_control( 'reviewpie_pos', array(
			'label'     => __( 'Review Scores Position', 'soledad' ),
			'type'      => Controls_Manager::SELECT,
			'default'   => 'top-left',
			'options'   => array(
				'top-right'    => esc_html__( 'Top Right', 'soledad' ),
				'top-left'     => esc_html__( 'Top Left', 'soledad' ),
				'bottom-right' => esc_html__( 'Bottom Right', 'soledad' ),
				'bottom-left'  => esc_html__( 'Bottom Left', 'soledad' ),
				'center'       => esc_html__( 'Center', 'soledad' ),
			),
			'condition' => array( 'bgquery_type' => 'post', 'show_reviewpie' => 'yes' ),
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
			'label'     => __( 'Big Grid Content Position', 'soledad' ),
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
			'label'     => __( 'Custom Big Grid Height (Unit is px)', 'soledad' ),
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
			'condition' => array( 'bgquery_type' => 'post' ),
		) );

		$this->add_control( 'paging', array(
			'label'       => __( 'Page Navigation Style', 'soledad' ),
			'type'        => Controls_Manager::SELECT,
			'default'     => 'none',
			'options'     => array(
				'none'     => esc_html__( 'None', 'soledad' ),
				'nextprev' => esc_html__( 'Ajax Next/Previous', 'soledad' ),
				'numbers'  => esc_html__( 'Page Navigation Numbers', 'soledad' ),
				'loadmore' => esc_html__( 'Load More Posts Button', 'soledad' ),
				'scroll'   => esc_html__( 'Infinite Scroll', 'soledad' ),
			),
			'description' => __( 'Load More Posts Button & Infinite Scroll just works on frontend only.', 'soledad' ),
			'condition'   => array( 'bgquery_type' => 'post' ),
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
			'condition' => array( 'bgquery_type' => 'post', 'paging!' => 'none' ),
		) );

		$this->add_responsive_control( 'paging_matop', array(
			'label'     => __( 'Margin Top for Page Navigation', 'soledad' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => array( '' => array( 'min' => 0, 'max' => 200, ) ),
			'selectors' => array( '{{WRAPPER}} .penci-pagination' => 'margin-top: {{SIZE}}px' ),
			'condition' => array( 'bgquery_type' => 'post' ),
		) );

		$this->end_controls_section();

		$this->register_query_section_controls();

		$this->start_controls_section( 'bg_custom', array(
			'label'     => __( 'Custom Grid Items', 'soledad' ),
			'condition' => array( 'bgquery_type' => 'custom' ),
		) );

		$repeater = new Repeater();
		$repeater->start_controls_tabs( 'biggrid_repeater' );

		$repeater->start_controls_tab( 'content', array( 'label' => __( 'Content', 'soledad' ) ) );

		$repeater->add_control( 'image', array(
			'label'   => _x( 'Select Image', 'soledad' ),
			'type'    => Controls_Manager::MEDIA,
			'default' => array( 'url' => Utils::get_placeholder_image_src() ),
		) );

		$repeater->add_control( 'sub_title', array(
			'label'       => __( 'Sub title', 'soledad' ),
			'type'        => Controls_Manager::TEXT,
			'default'     => __( 'Sub title', 'soledad' ),
			'label_block' => true,
		) );

		$repeater->add_control( 'title', array(
			'label'       => __( 'Title', 'soledad' ),
			'type'        => Controls_Manager::TEXT,
			'default'     => __( 'Heading Title', 'soledad' ),
			'label_block' => true,
		) );

		$repeater->add_control( 'title_link', array(
			'label'       => __( 'Add Link for Title & Image', 'soledad' ),
			'type'        => Controls_Manager::URL,
			'placeholder' => __( 'https://your-link.com', 'soledad' ),
		) );

		$repeater->add_control( 'desc', array(
			'label'      => __( 'Description', 'soledad' ),
			'type'       => Controls_Manager::TEXTAREA,
			'default'    => __( 'I am demo text - click edit button to change me.', 'soledad' ),
			'show_label' => true,
		) );

		$repeater->add_control( 'button_text', array(
			'label'   => __( 'Button Text', 'soledad' ),
			'type'    => Controls_Manager::TEXT,
			'default' => __( 'Read More', 'soledad' ),
		) );

		$repeater->add_control( 'button_link', array(
			'label'       => __( 'Button Link', 'soledad' ),
			'type'        => Controls_Manager::URL,
			'placeholder' => __( 'https://your-link.com', 'soledad' ),
		) );

		$repeater->end_controls_tab();

		$repeater->start_controls_tab( 'background', array( 'label' => __( 'Background', 'soledad' ) ) );

		$repeater->add_control( 'item_overlaybg', array(
			'label'     => esc_html__( 'Custom Overlay Background for This Item. It will only affect this item only. To adjust the style for all items, check options on the Style tab at the top.', 'soledad' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
		) );

		$repeater->add_group_control( Group_Control_Background::get_type(), array(
			'name'     => 'item_bg',
			'label'    => __( 'Overlay Background', 'soledad' ),
			'types'    => array( 'classic', 'gradient', 'video' ),
			'selector' => '{{WRAPPER}} {{CURRENT_ITEM}} .pcbg-bgoverlay.active-overlay, {{WRAPPER}} {{CURRENT_ITEM}} .pcbg-bgoverlaytext.active-overlay',
		) );

		$repeater->end_controls_tab();

		$repeater->start_controls_tab( 'style', array( 'label' => __( 'Style', 'soledad' ) ) );

		$repeater->add_control( 'custom_style', array(
			'label'       => __( 'Custom', 'soledad' ),
			'type'        => Controls_Manager::SWITCHER,
			'description' => __( 'Set custom style that will only affect this item only. To adjust the style for all items, check options on the "Style" tab at the top.', 'soledad' ),
		) );

		$repeater->add_control( 'horizontal_position', array(
			'label'                => __( 'Horizontal Position', 'soledad' ),
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
				'{{WRAPPER}} {{CURRENT_ITEM}} .pcbg-content-inner' => '{{VALUE}}',
			),
			'selectors_dictionary' => array(
				'left'   => 'margin-right: auto !important',
				'center' => 'margin-left: auto !important; margin-right: auto !important;',
				'right'  => 'margin-left: auto !important',
			),
			'conditions'           => array(
				'terms' => array(
					array(
						'name'  => 'custom_style',
						'value' => 'yes',
					)
				),
			),
		) );

		$repeater->add_control( 'vertical_position', array(
			'label'                => __( 'Vertical Position', 'soledad' ),
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
				'{{WRAPPER}} {{CURRENT_ITEM}} .pcbg-content-flex' => 'align-items: {{VALUE}}',
			),
			'selectors_dictionary' => array(
				'top'    => 'flex-start',
				'middle' => 'center',
				'bottom' => 'flex-end',
			),
			'conditions'           => array(
				'terms' => array(
					array(
						'name'  => 'custom_style',
						'value' => 'yes',
					)
				)
			),
		) );

		$repeater->add_control( 'text_align', array(
			'label'       => __( 'Text Align', 'soledad' ),
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
				'{{WRAPPER}} {{CURRENT_ITEM}} .pcbg-content-flex' => 'text-align: {{VALUE}}'
			),
			'conditions'  => array(
				'terms' => array(
					array(
						'name'  => 'custom_style',
						'value' => 'yes',
					)
				)
			),
		) );

		$repeater->add_control( 'subtitle_color', array(
			'label'      => __( 'Sub Title Color', 'soledad' ),
			'type'       => Controls_Manager::COLOR,
			'selectors'  => array(
				'{{WRAPPER}} .penci-bgrid-based-custom {{CURRENT_ITEM}} .pcbg-content-inner .pcbg-above'      => 'color: {{VALUE}}',
				'{{WRAPPER}} .penci-bgrid-based-custom {{CURRENT_ITEM}} .pcbg-content-inner .pcbg-above span' => 'color: {{VALUE}}',
				'{{WRAPPER}} .penci-bgrid-based-custom {{CURRENT_ITEM}} .pcbg-content-inner .pcbg-above a'    => 'color: {{VALUE}}',
				'{{WRAPPER}} .penci-bgrid-based-custom {{CURRENT_ITEM}} .pcbg-content-inner .pcbg-sub-title'  => 'color: {{VALUE}}',
			),
			'conditions' => array(
				'terms' => array(
					array(
						'name'  => 'custom_style',
						'value' => 'yes',
					)
				)
			)
		) );

		$repeater->add_control( 'title_color', array(
			'label'      => __( 'Title Color', 'soledad' ),
			'type'       => Controls_Manager::COLOR,
			'selectors'  => array(
				'{{WRAPPER}} {{CURRENT_ITEM}} .pcbg-content-inner .pcbg-title'   => 'color: {{VALUE}}',
				'{{WRAPPER}} {{CURRENT_ITEM}} .pcbg-content-inner .pcbg-title a' => 'color: {{VALUE}}',
			),
			'conditions' => array(
				'terms' => array(
					array(
						'name'  => 'custom_style',
						'value' => 'yes',
					)
				)
			)
		) );

		$repeater->add_control( 'title_hcolor', array(
			'label'      => __( 'Title Hover Color', 'soledad' ),
			'type'       => Controls_Manager::COLOR,
			'selectors'  => array(
				'{{WRAPPER}} {{CURRENT_ITEM}} .pcbg-content-inner .pcbg-title a:hover' => 'color: {{VALUE}}',
			),
			'conditions' => array(
				'terms' => array(
					array(
						'name'  => 'custom_style',
						'value' => 'yes',
					)
				)
			)
		) );

		$repeater->add_control( 'desc_color', array(
			'label'      => __( 'Description Color', 'soledad' ),
			'type'       => Controls_Manager::COLOR,
			'selectors'  => array(
				'{{WRAPPER}} {{CURRENT_ITEM}} .pcbg-content-inner .pcbg-meta'   => 'color: {{VALUE}}',
				'{{WRAPPER}} {{CURRENT_ITEM}} .pcbg-content-inner .pcbg-meta a' => 'color: {{VALUE}}',
			),
			'conditions' => array(
				'terms' => array(
					array(
						'name'  => 'custom_style',
						'value' => 'yes',
					)
				)
			)
		) );

		$repeater->add_control( 'button_color', array(
			'label'      => __( 'Button Text Color', 'soledad' ),
			'type'       => Controls_Manager::COLOR,
			'selectors'  => array(
				'{{WRAPPER}} {{CURRENT_ITEM}} .pcbg-readmore-sec .pcbg-readmorebtn' => 'color: {{VALUE}}'
			),
			'conditions' => array(
				'terms' => array(
					array(
						'name'  => 'custom_style',
						'value' => 'yes',
					)
				)
			)
		) );

		$repeater->add_control( 'button_hcolor', array(
			'label'      => __( 'Button Text Hover Color', 'soledad' ),
			'type'       => Controls_Manager::COLOR,
			'selectors'  => array(
				'{{WRAPPER}} {{CURRENT_ITEM}} .pcbg-readmore-sec .pcbg-readmorebtn:hover, {{WRAPPER}} {{CURRENT_ITEM}} .pcbg-overlap-hover:hover .pcbg-readmore-sec .pcbg-readmorebtn' => 'color: {{VALUE}}',
			),
			'conditions' => array(
				'terms' => array(
					array(
						'name'  => 'custom_style',
						'value' => 'yes',
					)
				)
			)
		) );

		$repeater->add_control( 'button_border_color', array(
			'label'      => __( 'Button Border Color', 'soledad' ),
			'type'       => Controls_Manager::COLOR,
			'selectors'  => array(
				'{{WRAPPER}} {{CURRENT_ITEM}} .pcbg-readmore-sec .pcbg-readmorebtn' => 'border-color: {{VALUE}}',
			),
			'conditions' => array(
				'terms' => array(
					array(
						'name'  => 'custom_style',
						'value' => 'yes',
					)
				)
			)
		) );

		$repeater->add_control( 'button_border_hcolor', array(
			'label'      => __( 'Button Border Hover Color', 'soledad' ),
			'type'       => Controls_Manager::COLOR,
			'selectors'  => array(
				'{{WRAPPER}} {{CURRENT_ITEM}} .pcbg-readmore-sec .pcbg-readmorebtn:hover, {{WRAPPER}} {{CURRENT_ITEM}} .pcbg-overlap-hover:hover .pcbg-readmore-sec .pcbg-readmorebtn' => 'border-color: {{VALUE}}',
			),
			'conditions' => array(
				'terms' => array(
					array(
						'name'  => 'custom_style',
						'value' => 'yes',
					)
				)
			)
		) );

		$repeater->add_control( 'button_bg_color', array(
			'label'      => __( 'Button Background Color', 'soledad' ),
			'type'       => Controls_Manager::COLOR,
			'selectors'  => array(
				'{{WRAPPER}} {{CURRENT_ITEM}} .pcbg-readmore-sec .pcbg-readmorebtn' => 'background-color: {{VALUE}}',
			),
			'conditions' => array(
				'terms' => array(
					array(
						'name'  => 'custom_style',
						'value' => 'yes',
					)
				)
			)
		) );

		$repeater->add_control( 'button_bg_hcolor', array(
			'label'      => __( 'Button Background Hover Color', 'soledad' ),
			'type'       => Controls_Manager::COLOR,
			'selectors'  => array(
				'{{WRAPPER}} {{CURRENT_ITEM}} .pcbg-readmore-sec .pcbg-readmorebtn:hover, {{WRAPPER}} {{CURRENT_ITEM}} .pcbg-overlap-hover:hover .pcbg-readmore-sec .pcbg-readmorebtn' => 'background-color: {{VALUE}}',
			),
			'conditions' => array(
				'terms' => array(
					array(
						'name'  => 'custom_style',
						'value' => 'yes',
					)
				)
			)
		) );

		$repeater->add_responsive_control( 'bgoverlay_padding', array(
			'label'      => __( 'Content Text Padding', 'soledad' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => array( 'px', '%', 'em' ),
			'selectors'  => array(
				'{{WRAPPER}} {{CURRENT_ITEM}} .pcbg-content-inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			),
			'conditions' => array(
				'terms' => array(
					array(
						'name'  => 'custom_style',
						'value' => 'yes',
					)
				)
			)
		) );

		$repeater->add_responsive_control( 'bgoverlay_margin', array(
			'label'      => __( 'Content Text Margin', 'soledad' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => array( 'px', '%', 'em' ),
			'selectors'  => array(
				'{{WRAPPER}} {{CURRENT_ITEM}} .pcbg-content-inner' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			),
			'conditions' => array(
				'terms' => array(
					array(
						'name'  => 'custom_style',
						'value' => 'yes',
					)
				)
			)
		) );

		$repeater->end_controls_tab();

		$repeater->end_controls_tabs();

		$this->add_control( 'biggrid_items', array(
			'label'       => __( 'Big Grid Custom Items', 'soledad' ),
			'type'        => Controls_Manager::REPEATER,
			'show_label'  => true,
			'fields'      => $repeater->get_controls(),
			'default'     => array(
				array(
					'image'       => array( 'url' => Utils::get_placeholder_image_src() ),
					'sub_title'   => __( 'Sub Title', 'soledad' ),
					'title'       => __( 'Big Grid Item #1', 'soledad' ),
					'desc'        => __( 'I am demo text. Edit grid items to edit me', 'soledad' ),
					'button_text' => __( 'Click Here', 'soledad' ),
				),
				array(
					'image'       => array( 'url' => Utils::get_placeholder_image_src() ),
					'sub_title'   => __( 'Sub Title', 'soledad' ),
					'title'       => __( 'Big Grid Item #2', 'soledad' ),
					'desc'        => __( 'I am demo text. Edit grid items to edit me', 'soledad' ),
					'button_text' => __( 'Click Here', 'soledad' ),
				),
				array(
					'image'       => array( 'url' => Utils::get_placeholder_image_src() ),
					'sub_title'   => __( 'Sub Title', 'soledad' ),
					'title'       => __( 'Big Grid Item #3', 'soledad' ),
					'desc'        => __( 'I am demo text. Edit grid items to edit me', 'soledad' ),
					'button_text' => __( 'Click Here', 'soledad' ),
				),
				array(
					'image'       => array( 'url' => Utils::get_placeholder_image_src() ),
					'sub_title'   => __( 'Sub Title', 'soledad' ),
					'title'       => __( 'Big Grid Item #4', 'soledad' ),
					'desc'        => __( 'I am demo text. Edit grid items to edit me', 'soledad' ),
					'button_text' => __( 'Click Here', 'soledad' ),
				),
				array(
					'image'       => array( 'url' => Utils::get_placeholder_image_src() ),
					'sub_title'   => __( 'Sub Title', 'soledad' ),
					'title'       => __( 'Big Grid Item #5', 'soledad' ),
					'desc'        => __( 'I am demo text. Edit grid items to edit me', 'soledad' ),
					'button_text' => __( 'Click Here', 'soledad' ),
				),
				array(
					'image'       => array( 'url' => Utils::get_placeholder_image_src() ),
					'sub_title'   => __( 'Sub Title', 'soledad' ),
					'title'       => __( 'Big Grid Item #6', 'soledad' ),
					'desc'        => __( 'I am demo text. Edit grid items to edit me', 'soledad' ),
					'button_text' => __( 'Click Here', 'soledad' ),
				),
			),
			'title_field' => '{{{ title }}}',
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

		$this->register_block_title_ajax_filter( false, [ 'bgquery_type' => 'post' ] );

		$this->register_block_title_section_controls();

		$this->start_controls_section( 'section_biggrid_design', array(
			'label' => __( 'Big Grid Style', 'soledad' ),
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
			'label' => __( 'Big Grid Overlay', 'soledad' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		) );

		$this->add_control( 'overlay_type', array(
			'label'   => __( 'Apply Overlay On:', 'soledad' ),
			'type'    => Controls_Manager::SELECT,
			'default' => 'whole',
			'options' => array(
				'whole' => __('Whole Image', 'soledad' ),
				'text'  => __('Whole Content Text', 'soledad' ),
				'none'  => __('None', 'soledad' ),
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

		$this->add_control( 'apply_spe_bg_subtitle', array(
			'label' => __( 'Apply Separate Background for Categories/Sub Title', 'soledad' ),
			'type'  => Controls_Manager::SWITCHER,
		) );

		$this->add_control( 'spe_bg_subtitle', array(
			'label'     => __( 'Background for Categories/Sub Title', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => array(
				'{{WRAPPER}} .penci-bgrid-based-post .cat > a.penci-cat-name' => 'background-color: {{VALUE}};',
				'{{WRAPPER}} .penci-bgrid-based-custom .pcbg-sub-title'       => 'background-color: {{VALUE}}; border-color: {{VALUE}};',
			),
			'condition' => array( 'apply_spe_bg_subtitle' => 'yes' ),
		) );

		$this->add_control( 'spe_bg_hsubtitle', array(
			'label'     => __( 'Background for Categories/Sub Title on Hover', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => array(
				'{{WRAPPER}} .penci-bgrid-based-post .penci-bgitem:hover .cat > a.penci-cat-name' => 'background-color: {{VALUE}};',
				'{{WRAPPER}} .penci-bgrid-based-custom .penci-bgitem:hover .pcbg-sub-title'       => 'background-color: {{VALUE}}; border-color: {{VALUE}};',
			),
			'condition' => array( 'apply_spe_bg_subtitle' => 'yes' ),
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
			'label' => __( 'Apply Separate Background for Post Meta/Description', 'soledad' ),
			'type'  => Controls_Manager::SWITCHER,
		) );

		$this->add_control( 'spe_bg_meta', array(
			'label'     => __( 'Background for Post Meta/Description', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => array(
				'{{WRAPPER}} .pcbg-meta-desc' => 'background-color: {{VALUE}}; border-color: {{VALUE}};',
			),
			'condition' => array( 'apply_spe_bg_meta' => 'yes' ),
		) );

		$this->add_control( 'spe_bg_hmeta', array(
			'label'     => __( 'Background for Post Meta/Description on Hover', 'soledad' ),
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
				'zoom-in'     => __('Zoom-In', 'soledad' ),
				'zoom-out'    => __('Zoom-out', 'soledad' ),
				'move-left'   => __('Move to Left', 'soledad' ),
				'move-right'  => __('Move to Right', 'soledad' ),
				'move-bottom' => __('Move to Bottom', 'soledad' ),
				'move-top'    => __('Move to Top', 'soledad' ),
				'none'        => __('None', 'soledad' ),
			)
		) );

		$this->add_control( 'text_overlay', array(
			'label'   => __( 'Content Text Hover Type', 'soledad' ),
			'type'    => Controls_Manager::SELECT,
			'default' => 'none',
			'options' => array(
				'none'    => __('None', 'soledad' ),
				'show-in' => __('Show on Hover', 'soledad' ),
				'hide-in' => __('Hide on Hover', 'soledad' ),
			)
		) );

		$this->add_control( 'text_overlay_ani', array(
			'label'     => __( 'Content Text Hover Animation', 'soledad' ),
			'type'      => Controls_Manager::SELECT,
			'default'   => 'movetop',
			'options'   => array(
				'movetop'    => __('Move to Top', 'soledad' ),
				'movebottom' => __('Move to Bottom', 'soledad' ),
				'moveleft'   => __('Move to Left', 'soledad' ),
				'moveright'  => __('Move to Right', 'soledad' ),
				'zoomin'     => __('Zoom In', 'soledad' ),
				'zoomout'    => __('Zoom Out', 'soledad' ),
				'fade'       => __('Fade', 'soledad' ),
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
			'label' => __( 'Big Grid Typography & Colors', 'soledad' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		) );

		$this->add_control( 'bgitem_design', array(
			'label'     => esc_html__( 'Big Grid Items', 'soledad' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
		) );

		$this->add_control( 'ver_border', array(
			'label'        => __( 'Add Vertical Border Between Post Items', 'soledad' ),
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

		$this->add_control( 'subtitle_design', array(
			'label'     => esc_html__( 'Post Categories/ Sub Title', 'soledad' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
		) );

		$this->add_control( 'bgsub_title', array(
			'label'     => __( 'Text & Links Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => array(
				'{{WRAPPER}} .penci-bgrid-based-custom .pcbg-content-inner .pcbg-above' => 'color: {{VALUE}};',
				'{{WRAPPER}} .pcbg-content-inner .pcbg-above span'                      => 'color: {{VALUE}};',
				'{{WRAPPER}} .pcbg-content-inner .pcbg-above span a'                    => 'color: {{VALUE}};',
				'{{WRAPPER}} .pcbg-content-inner .pcbg-sub-title'                       => 'color: {{VALUE}};',
			),
		) );

		$this->add_control( 'bgsub_title_hover', array(
			'label'     => __( 'Links Hover Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => array(
				'{{WRAPPER}} .pcbg-content-inner .pcbg-above a:hover' => 'color: {{VALUE}};',
			),
		) );

		$this->add_group_control( Group_Control_Typography::get_type(), array(
			'name'     => 'bgsub_title_typo',
			'label'    => __( 'Typography', 'soledad' ),
			'selector' => '{{WRAPPER}} .penci-bgrid-based-custom .pcbg-content-inner .pcbg-above, {{WRAPPER}} .pcbg-content-inner .pcbg-above span,{{WRAPPER}} .pcbg-content-inner .pcbg-above span a, {{WRAPPER}} .pcbg-content-inner .pcbg-sub-title',
		) );

		$this->add_control( 'title_design', array(
			'label'     => esc_html__( 'Title', 'soledad' ),
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
			'selectors' => array( '{{WRAPPER}} .pcbg-content-inner .pcbg-title a:hover' => 'color: {{VALUE}};' ),
		) );

		$this->add_control( 'title_hcolor_effect', array(
			'label'     => __( 'Enable Hover Border Color', 'soledad' ),
			'type'      => Controls_Manager::SWITCHER,
			'selectors' => array(
				'{{WRAPPER}} .pcbg-content-inner .pcbg-title a' 		=> 'background-image: linear-gradient(var(--pcaccent-cl) 0%, var(--pcaccent-cl) 98%);background-size: 0 1px;background-repeat: no-repeat;background-position: left 100%;word-break: break-word;transition: 0.3s all ease-in-out;',
				'{{WRAPPER}} .pcbg-content-inner .pcbg-title a:hover' => 'background-size: 100% 1px;',
			),
		) );

		$this->add_control( 'title_hcolor_effect_color', array(
			'label'     => __( 'Hover Border Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => array( '{{WRAPPER}} .pcbg-content-inner .pcbg-title a' => 'background-image: linear-gradient({{VALUE}} 0%, {{VALUE}} 98%)' ),
			'condition' => array( 'title_hcolor_effect' => 'yes' ),
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
			'label'     => esc_html__( 'Post Meta/ Description Text', 'soledad' ),
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
			'label'     => esc_html__( 'Post Excerpt', 'soledad' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
			'condition' => array( 'bgquery_type' => 'post' ),
		) );

		$this->add_control( 'excerpt_tcolor', array(
			'label'     => __( 'Text Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'condition' => array( 'bgquery_type' => 'post' ),
			'selectors' => array(
				'{{WRAPPER}} .pcbg-pexcerpt, {{WRAPPER}} .pcbg-pexcerpt a, {{WRAPPER}} .pcbg-pexcerpt p' => 'color: {{VALUE}};',
			),
		) );

		$this->add_group_control( Group_Control_Typography::get_type(), array(
			'name'      => 'excerpt_typo',
			'label'     => __( 'Typography', 'soledad' ),
			'condition' => array( 'bgquery_type' => 'post' ),
			'selector'  => '{{WRAPPER}} .pcbg-pexcerpt, {{WRAPPER}} .pcbg-pexcerpt a, {{WRAPPER}} .pcbg-pexcerpt p',
		) );

		$this->add_control( 'readmore_design', array(
			'label'     => esc_html__( 'Read More Button', 'soledad' ),
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
			'label' => __( 'Add Icon to "Read More" Button', 'soledad' ),
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
			// 'selectors_dictionary' => array(
			// 'right' => 'row',
			// 'left' => 'row-reverse',
			// ),
			// 'selectors' => array(
			// '{{WRAPPER}} .pcbg-readmore-sec .pcbg-readmorebtn' => 'flex-direction: {{VALUE}}; -webkit-flex-direction: {{VALUE}}',
			// ),
		) );

		$this->add_control( 'pagi_design', array(
			'label'     => esc_html__( 'Page Navigation', 'soledad' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
			'condition' => array(
				'paging!'      => 'none',
				'bgquery_type' => 'post'
			),
		) );

		$this->add_responsive_control( 'pagi_mwidth', array(
			'label'      => __( 'Load More Posts Button Max Width', 'soledad' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => array( '%', 'px' ),
			'range'      => array(
				'%'  => array( 'min' => 0, 'max' => 100, ),
				'px' => array( 'min' => 0, 'max' => 2000, 'step' => 1 ),
			),
			'condition'  => array(
				'paging'       => array( 'loadmore', 'scroll' ),
				'bgquery_type' => 'post'
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
				'paging!'      => 'none',
				'bgquery_type' => 'post'
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
				'paging!'      => 'none',
				'bgquery_type' => 'post'
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
				'paging!'      => 'none',
				'bgquery_type' => 'post'
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
				'paging!'      => 'none',
				'bgquery_type' => 'post'
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
				'paging!'      => 'none',
				'bgquery_type' => 'post'
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
				'paging!'      => 'none',
				'bgquery_type' => 'post'
			),
		) );

		$this->add_group_control( Group_Control_Typography::get_type(), array(
			'name'      => 'bgpagi_typo',
			'label'     => __( 'Typography', 'soledad' ),
			'selector'  => '{{WRAPPER}} .penci-pagination a, {{WRAPPER}} .penci-pagination span.current',
			'condition' => array(
				'paging!'      => 'none',
				'bgquery_type' => 'post'
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
				'paging!'      => 'none',
				'bgquery_type' => 'post'
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
				'paging!'      => 'none',
				'bgquery_type' => 'post'
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
				'paging!'      => 'none',
				'bgquery_type' => 'post'
			),
		) );

		$this->end_controls_section();


		$this->register_penci_bookmark_style_groups();
		$this->register_paywall_premium_heading_style_groups();
		$this->register_block_heading_link_section_style();
		$this->register_block_title_style_section_controls();
	}

	protected function register_query_section_controls( $showmeta = false, $condition = [] ) {

		$this->start_controls_section( 'section_query', array(
			'label'     => __( 'Query Based Posts', 'soledad' ),
			'condition' => array( 'bgquery_type' => 'post' ),
			'tab'       => Controls_Manager::TAB_CONTENT
		) );

		$this->add_group_control( Penci_Group_Control_Posts::get_type(), array(
			'name' => 'posts'
		) );

		$this->add_control( 'posts_per_page', array(
			'label'     => __( 'Posts Per Page', 'soledad' ),
			'type'      => Controls_Manager::NUMBER,
			'default'   => 10,
			'condition' => array( 'posts_post_type!' => array( 'by_id', 'current_query' ) ),
		) );

		$this->add_control( 'arposts_per_page', array(
			'label'       => __( 'Posts Per Page', 'soledad' ),
			'description' => __( 'This option only work for Archive Builder', 'soledad' ),
			'type'        => Controls_Manager::NUMBER,
			'default'     => 10,
			'condition'   => array( 'posts_post_type' => array( 'current_query' ) ),
		) );

		$this->add_control( 'arposts_new', array(
			'label'       => __( 'Always Show Newest Posts', 'soledad' ),
			'description' => __( 'This option will sticky newest posts when your page go to a new pagination', 'soledad' ),
			'type'        => Controls_Manager::SWITCHER,
			'condition'   => array( 'posts_post_type' => array( 'current_query' ) ),
		) );

		$this->add_control( 'orderby', array(
			'label'     => __( 'Order By', 'soledad' ),
			'type'      => Controls_Manager::SELECT,
			'default'   => 'date',
			'options'   => array(
				'date'          => __( 'Published Date', 'soledad' ),
				'ID'            => __('Post ID', 'soledad' ),
				'modified'      => __('Modified Date', 'soledad' ),
				'title'         => __('Post Title', 'soledad' ),
				'rand'          => __('Random Posts', 'soledad' ),
				'comment_count' => __('Comment Count', 'soledad' ),
				'most_liked' 	=> __( 'Most Liked', 'soledad' ),
				'popular'       => __('Most Viewed Posts All Time', 'soledad' ),
				'popular_day'   => __('Most Viewed Posts Daily', 'soledad' ),
				'popular7'      => __('Most Viewed Posts Once Weekly', 'soledad' ),
				'popular_month' => __('Most Viewed Posts Once a Month', 'soledad' ),
				'metakey' 		=> __( 'Custom Meta Key', 'soledad' ),
			),
			'condition' => array( 'posts_post_type!' => array( 'current_query' ) ),
		) );

		$this->add_control( 'orderby_meta_key', array(
			'label'       => __( 'Custom Meta Key', 'soledad' ),
			'type'        => Controls_Manager::TEXT,
			'default'     => '',
			'description' => __( 'Enter the custom meta key to order by.', 'soledad' ),
			'condition'   => array( 'orderby' => 'metakey', 'posts_post_type!' => array( 'current_query' ) ),
		) );

		$this->add_control( 'order', array(
			'label'     => __( 'Order', 'soledad' ),
			'type'      => Controls_Manager::SELECT,
			'default'   => 'desc',
			'options'   => array(
				'asc'  => __( 'ASC', 'soledad' ),
				'desc' => __( 'DESC', 'soledad' )
			),
			'condition' => array( 'posts_post_type!' => array( 'current_query' ) ),
		) );

		$this->add_control( 'offset', array(
			'label'       => __( 'Offset', 'soledad' ),
			'type'        => Controls_Manager::NUMBER,
			'default'     => 0,
			'condition'   => array( 'posts_post_type!' => array( 'by_id' ) ),
			'description' => __( 'Use this setting to skip over posts (e.g. \'2\' to skip over 2 posts).', 'soledad' ),
		) );

		$this->add_control( 'avoid_duplicates', [
			'label'       => esc_html__( 'Avoid Duplicates', 'soledad' ),
			'type'        => Controls_Manager::SWITCHER,
			'default'     => '',
			'description' => esc_html__( 'Set to Yes to avoid duplicate posts from showing up. This only effects the frontend.', 'soledad' ),
			'condition'   => [
				'posts_post_type!' => [
					'by_id',
					'current_query',
				],
			],
		] );

		Query_Control::add_exclude_controls( $this );

		$this->register_custom_post_metas_groups();

		$this->add_control( 'query_id', array(
			'label'     => __( 'Query ID', 'soledad' ),
			'type'      => Controls_Manager::TEXT,
		) );

		$this->end_controls_section();
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
		$biggid_type  = $settings['bgquery_type'] ? $settings['bgquery_type'] : 'post';
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
		$title_length      = $settings['title_length'] ? $settings['title_length'] : '';
		$readmore_icon     = $settings['readmore_icon'] ? $settings['readmore_icon'] : '';
		$readmore_icon_pos = $settings['readmore_icon_pos'] ? $settings['readmore_icon_pos'] : 'right';
		$formaticon_pos    = $settings['formaticon_pos'] ? $settings['formaticon_pos'] : 'top-right';
		$reviewpie_pos     = $settings['reviewpie_pos'] ? $settings['reviewpie_pos'] : 'top-left';

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

		$wrapper_class .= ' penci-bgrid-based-' . $biggid_type . ' penci-bgrid-' . $biggid_style . ' pcbg-ficonpo-' . $formaticon_pos . ' pcbg-reiconpo-' . $reviewpie_pos . ' penci-bgrid-content-' . $bgcontent_pos . ' pencibg-imageh-' . $image_hover . ' pencibg-texth-' . $text_overlay . ' pencibg-textani-' . $text_overlay_ani;
		if ( $flag_style && 'yes' == $onecol_mobile ) {
			$wrapper_class .= ' penci-bgrid-monecol';
		}
		if ( $flag_style && 'yes' == $sameh_mobile ) {
			$wrapper_class .= ' penci-bgrid-msameh';
		}

		if ( 'yes' == $settings['title_anivisi'] ) {
			$wrapper_class .= ' pcbg-titles-visible';
		}

		if ( 'yes' == $settings['apply_spe_bg_subtitle'] ) {
			$wrapper_class .= ' pcbg-mask-subtitle';
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
		if ( 'yes' == $settings['hide_desc_mobile'] ) {
			$wrapper_class .= ' hide-mdesc';
		}
		if ( 'yes' == $settings['hide_excerpt_mobile'] ) {
			$wrapper_class .= ' hide-mexcerpt';
		}
		if ( 'yes' == $settings['hide_readmore_mobile'] ) {
			$wrapper_class .= ' hide-mreadmorebt';
		}
		if ( 'yes' == $settings['ver_border'] && 'style-1' == $biggid_style ) {
			$wrapper_class .= ' pcbg-verbd';
		}

		if ( isset( $settings['paywall_heading_text_style'] ) ) {
			$wrapper_class .= ' pencipw-hd-' . $settings['paywall_heading_text_style'];
		}

		$big_items           = penci_big_grid_is_big_items( $biggid_style );
		$block_id            = 'pcblock-' . rand( 0, 9999 );
		$settings['blockid'] = $block_id;
		add_action( 'penci_block_title_extra_' . $block_id, function () use ( $settings ) {
			$link_group_cats   = $settings['biggrid_ajaxfilter_cat'];
			$link_group_tags   = $settings['biggrid_ajaxfilter_tag'];
			$link_group_author = $settings['biggrid_ajaxfilter_author'];
			$link_group_out    = $link_group_out_before = $link_group_out_after = '';

			$data_settings_tabs          = array();
			$data_settings_tabs['query'] = Query_Control::get_query_args( 'posts', $settings );;
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
			$data_settings_tabs['primary_cat']        = $settings['primary_cat'];
			$data_settings_tabs['show_readmore']      = $settings['show_readmore'];
			$data_settings_tabs['excerpt_length']     = $settings['excerpt_length'];
			$data_settings_tabs['hide_cat_small']     = $settings['hide_cat_small'];
			$data_settings_tabs['hide_meta_small']    = $settings['hide_meta_small'];
			$data_settings_tabs['hide_excerpt_small'] = $settings['hide_excerpt_small'];
			$data_settings_tabs['hide_rm_small']      = $settings['hide_rm_small'];
			$data_settings_tabs['title_length']       = $settings['title_length'];
			$data_settings_tabs['readmore_icon']      = $settings['readmore_icon'];
			$data_settings_tabs['show_formaticon']    = $settings['show_formaticon'];
			$data_settings_tabs['show_reviewpie']     = $settings['show_reviewpie'];
			$data_settings_tabs['readmore_icon_pos']  = $settings['readmore_icon_pos'];

			$ppp = $settings['posts_per_page'];

			if ( 'current_query' == $settings['posts_post_type'] ) {
				$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
				$ppp   = isset( $settings['arposts_per_page'] ) && $settings['arposts_per_page'] ? $settings['arposts_per_page'] : $ppp;
			}

			$link_group_out_before .= '<nav data-number="' . $ppp . '" data-query_type="ajaxtab" data-layout="' . esc_attr( $settings['style'] ) . '" data-settings="' . htmlentities( json_encode( $data_settings_tabs ), ENT_QUOTES, "UTF-8" ) . '" data-more="' . esc_attr( $settings['group_more_link_text'] ) . '" class="pcnav-lgroup"><ul class="pcflx">';
			$link_group_out_after  = '</ul></nav>';

			$has_link = false;

			if ( is_array( $link_group_cats ) ) {
				$has_link = true;
				foreach ( $link_group_cats as $link_cat ) {
					$element_id = 'link-wrapper-cat-' . $link_cat;
					$this->add_render_attribute( $element_id, 'href', '#' );
					if ( $link_cat ) {
						$this->add_render_attribute( $element_id, 'data-cat', $link_cat );
					}
					$this->add_render_attribute( $element_id, 'data-id', md5( 'cat-link-' . $link_cat ) );
					$this->add_render_attribute( $element_id, 'data-paged', 1 );
					$this->add_render_attribute( $element_id, 'class', 'pc-ajaxfil-link' );
					$link_group_out .= '<li><a ' . $this->get_render_attribute_string( $element_id ) . '>' . get_term_field( 'name', $link_cat ) . '</a></li>';
				}
			}

			if ( is_array( $link_group_tags ) ) {
				$has_link = true;
				foreach ( $link_group_tags as $link_tag ) {
					$element_id = 'link-wrapper-tag-' . $link_tag;
					$this->add_render_attribute( $element_id, 'href', '#' );
					if ( $link_tag ) {
						$this->add_render_attribute( $element_id, 'data-tag', $link_tag );
					}
					$this->add_render_attribute( $element_id, 'data-id', md5( 'tag-link-' . $link_tag ) );
					$this->add_render_attribute( $element_id, 'data-paged', 1 );
					$this->add_render_attribute( $element_id, 'class', 'pc-ajaxfil-link' );
					$link_group_out .= '<li><a ' . $this->get_render_attribute_string( $element_id ) . '>' . get_term_field( 'name', $link_tag ) . '</a></li>';
				}
			}

			if ( is_array( $link_group_author ) ) {
				$has_link = true;
				foreach ( $link_group_author as $author ) {
					$element_id = 'link-wrapper-author-' . $author;
					$this->add_render_attribute( $element_id, 'href', get_the_author_meta( 'url', $author ) );
					if ( $author ) {
						$this->add_render_attribute( $element_id, 'data-author', $author );
					}
					$this->add_render_attribute( $element_id, 'data-id', md5( 'author-link-' . $author ) );
					$this->add_render_attribute( $element_id, 'data-paged', 1 );
					$this->add_render_attribute( $element_id, 'class', 'pc-ajaxfil-link' );
					$link_group_out .= '<li><a ' . $this->get_render_attribute_string( $element_id ) . '>' . get_the_author_meta( 'nicename', $author ) . '</a></li>';
				}
			}

			if ( 'nextprev' == $settings['paging'] ) {
				$link_group_out .= '</ul><ul class="pcflx-nav">';
				$link_group_out .= '<li class="pcaj-nav-item pcaj-prev"><a class="disable pc-ajaxfil-link pcaj-nav-link prev" data-id="" href="#" aria-label="Previous"><i class="penciicon-left-chevron"></i></a></li>';
				$link_group_out .= '<li class="pcaj-nav-item pcaj-next"><a class="pc-ajaxfil-link pcaj-nav-link next" data-id="" href="#" aria-label="Next"><i class="penciicon-right-chevron"></i></a></li>';
			}

			if ( $link_group_out ) {
				$first_class           = $has_link ? 'visible' : 'hidden-item';
				$link_group_out_before .= '<li class="all ' . $first_class . '"><a data-paged="1" class="pc-ajaxfil-link current-item" data-id="default" href="#" aria-label="Paged">' . $settings['group_more_defaultab_text'] . '</a></li>';
				wp_enqueue_script( 'penci_ajax_filter_bg' );
				echo $link_group_out_before . $link_group_out . $link_group_out_after;
			}
		} );
		?>
        <div class="penci-clearfix penci-biggrid-wrapper<?php echo $wrapper_class; ?>">
			<?php $this->markup_block_title( $settings, $this ); ?>
            <div class="penci-clearfix penci-biggrid penci-bg<?php echo $biggid_style; ?> penci-bgel">
                <div class="penci-biggrid-inner default">
					<?php
					$bg = 1;
					if ( 'post' == $biggid_type ) {
						$post_meta          = $settings['bg_postmeta'] ? $settings['bg_postmeta'] : array();
						$primary_cat        = $settings['primary_cat'] ? $settings['primary_cat'] : '';
						$show_readmore      = $settings['show_readmore'] ? $settings['show_readmore'] : '';
						$hide_cat_small     = $settings['hide_cat_small'] ? $settings['hide_cat_small'] : '';
						$hide_meta_small    = $settings['hide_meta_small'] ? $settings['hide_meta_small'] : '';
						$hide_excerpt_small = $settings['hide_excerpt_small'] ? $settings['hide_excerpt_small'] : '';
						$hide_rm_small      = $settings['hide_rm_small'] ? $settings['hide_rm_small'] : '';
						$show_formaticon    = $settings['show_formaticon'] ? $settings['show_formaticon'] : '';
						$show_reviewpie     = $settings['show_reviewpie'] ? $settings['show_reviewpie'] : '';
						$excerpt_length     = $settings['excerpt_length'] ? $settings['excerpt_length'] : 10;
						$paging             = $settings['paging'] ? $settings['paging'] : 'none';
						$paging_align       = $settings['paging_align'] ? $settings['paging_align'] : 'align-center';
						$original_postype   = $settings['posts_post_type'];

						if ( in_array( $original_postype, [
								'current_query',
								'related_posts'
							] ) && penci_elementor_is_edit_mode() && penci_is_builder_template() ) {
							$settings['posts_post_type'] = 'post';
						}
						$archive_buider_check = $settings['posts_post_type'];

						$args          = Query_Control::get_query_args( 'posts', $settings );

						if ( 'numbers' == $paging ) {
							$args['paged'] = max( get_query_var( 'paged' ), get_query_var( 'page' ), 1 );
						}
						
						$ppp           = $settings['posts_per_page'] ? $settings['posts_per_page'] : get_option( 'posts_per_page' );
						if ( in_array( $original_postype, [ 'current_query', 'related_posts' ] ) ) {
							$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
							if ( 'current_query' == $original_postype ) {
								$ppp = isset( $settings['arposts_per_page'] ) && $settings['arposts_per_page'] ? $settings['arposts_per_page'] : $ppp;
							}
							$offset = 0;

							if ( $ppp ) {
								$args['posts_per_page'] = $ppp;
							}
							if ( isset( $settings['arposts_new'] ) && $settings['arposts_new'] == 'yes' ) {
								$args['paged'] = 1;
							}
							if ( 0 < $settings['offset'] ) {
								$offset = $settings['offset'];
							}

							if ( ! empty( $settings['offset'] ) && $paged > 1 ) {
								$offset = $settings['offset'] + ( ( $paged - 1 ) * $ppp );
							}

							if ( $offset ) {
								$args['offset'] = $offset;
							}
						}

						$query_custom = new \WP_Query( $args );

						if ( ! $query_custom->have_posts() ) {
							echo $this->show_missing_settings( 'Big Grid', penci_get_setting( 'penci_ajaxsearch_no_post' ) );
						}

						if ( $query_custom->have_posts() ) {
							$num_posts = $query_custom->post_count;
							//$number_each = penci_big_grid_count_item( $biggid_style );
							if ( $flag_style ) {
								echo '<div class="penci-big-grid-ajax-data">';
							}

							$maxp_out = '';

							if ( 'nextprev' == $settings['paging'] ) {
								$maxp_out = 'data-maxp="' . $query_custom->max_num_pages . '" ';
							}

							echo '<div ' . $maxp_out . 'class="penci-clearfix penci-biggrid-data' . $data_class . '">';
							while ( $query_custom->have_posts() ) : $query_custom->the_post();
								$hide_cat_small_flag = $hide_meta_small_flag = $hide_rm_small_flag = $hide_excerpt_small_flag = false;
								$is_big_item         = '';
								$surplus             = penci_big_grid_count_classes( $bg, $biggid_style, true );
								$thumbnail           = $thumb_size;
								if ( ! empty( $big_items ) && in_array( $surplus, $big_items ) ) {
									$thumbnail   = $bthumb_size;
									$is_big_item = ' pcbg-big-item';
								}
								if ( penci_is_mobile() ) {
									$thumbnail = $mthumb_size;
								}

								if ( ! $is_big_item ) {
									if ( 'yes' == $hide_cat_small ) {
										$hide_cat_small_flag = true;
									}
									if ( 'yes' == $hide_meta_small ) {
										$hide_meta_small_flag = true;
									}
									if ( 'yes' == $hide_excerpt_small ) {
										$hide_excerpt_small_flag = true;
									}
									if ( 'yes' == $hide_rm_small ) {
										$hide_rm_small_flag = true;
									}
								}

								if ( 'style-1' == $biggid_style || 'style-2' == $biggid_style ) {
									$hide_cat_small_flag = $hide_meta_small_flag = $hide_rm_small_flag = $hide_excerpt_small_flag = false;
								}
								include dirname( __FILE__ ) . "/based-post.php";

								if ( $flag_style && $surplus == 0 && $bg < $num_posts ) {
									echo '</div><div class="penci-clearfix penci-biggrid-data' . $data_class . '">';
								}

								$bg ++;
							endwhile;
							echo '</div>';

							if ( $flag_style ) {
								echo '</div>';
							}

							if ( 'loadmore' == $paging || 'scroll' == $paging ) {
								$data_settings                       = array();
								$data_settings['query']              = $args;
								$data_settings['query_id']           = isset($settings['query_id']) ? $settings['query_id'] : 'all';
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
								$data_settings['primary_cat']        = $primary_cat;
								$data_settings['show_readmore']      = $show_readmore;
								$data_settings['excerpt_length']     = $excerpt_length;
								$data_settings['hide_cat_small']     = $hide_cat_small;
								$data_settings['hide_meta_small']    = $hide_meta_small;
								$data_settings['hide_excerpt_small'] = $hide_excerpt_small;
								$data_settings['hide_rm_small']      = $hide_rm_small;
								$data_settings['title_length']       = $title_length;
								$data_settings['readmore_icon']      = $readmore_icon;
								$data_settings['show_formaticon']    = $show_formaticon;
								$data_settings['show_reviewpie']     = $show_reviewpie;
								$data_settings['readmore_icon_pos']  = $readmore_icon_pos;
								$data_paged                          = max( get_query_var( 'paged' ), get_query_var( 'page' ), 1 );

								$data_settings_ajax = htmlentities( json_encode( $data_settings ), ENT_QUOTES, "UTF-8" );

								$button_class = ' penci-ajax-more penci-bgajax-more-click';
								if ( 'loadmore' == $paging ):
									wp_enqueue_script( 'penci_bgajax_more_posts' );
								endif;
								if ( 'scroll' == $paging ):
									$button_class = ' penci-ajax-more penci-bgajax-more-scroll';
									wp_enqueue_script( 'penci_bgajax_more_scroll' );
								endif;
								$data_archive_type  = '';
								$data_archive_value = '';
								if ( is_category() ) :
									$category           = get_category( get_query_var( 'cat' ) );
									$cat_id             = isset( $category->cat_ID ) ? $category->cat_ID : '';
									$data_archive_type  = 'cat';
									$data_archive_value = $cat_id;
									$opt_cat            = 'category_' . $cat_id;
									$cat_meta           = get_option( $opt_cat );
									$sidebar_opts       = isset( $cat_meta['cat_sidebar_display'] ) ? $cat_meta['cat_sidebar_display'] : '';
									if ( $sidebar_opts == 'no' ):
										$data_template = 'no-sidebar';
                                    elseif ( $sidebar_opts == 'left' || $sidebar_opts == 'right' ):
										$data_template = 'sidebar';
									endif;

                                elseif ( is_tag() ) :
									$tag                = get_queried_object();
									$tag_id             = isset( $tag->term_id ) ? $tag->term_id : '';
									$data_archive_type  = 'tag';
									$data_archive_value = $tag_id;
                                elseif ( is_day() ) :
									$data_archive_type  = 'day';
									$data_archive_value = get_the_date( 'm|d|Y' );
                                elseif ( is_month() ) :
									$data_archive_type  = 'month';
									$data_archive_value = get_the_date( 'm|d|Y' );
                                elseif ( is_year() ) :
									$data_archive_type  = 'year';
									$data_archive_value = get_the_date( 'm|d|Y' );
                                elseif ( is_search() ) :
									$data_archive_type  = 'search';
									$data_archive_value = get_search_query();
                                elseif ( is_author() ) :

									global $authordata;
									$user_id = isset( $authordata->ID ) ? $authordata->ID : 0;

									$data_archive_type  = 'author';
									$data_archive_value = $user_id;
                                elseif ( is_archive() ) :
									$queried_object = get_queried_object();
									$term_id        = isset( $queried_object->term_id ) ? $queried_object->term_id : '';
									$tax            = get_taxonomy( get_queried_object()->taxonomy );
									$tax_name       = isset( $tax->name ) ? $tax->name : '';

									if ( $term_id && $tax_name ) {
										$data_archive_type  = $tax_name;
										$data_archive_value = $term_id;
									}
								endif;
								?>
                                <div class="pcbg-paging penci-pagination <?php echo 'pcbg-paging-' . $paging_align . $button_class; ?>">
                                    <a class="penci-ajax-more-button" aria-label="More Posts" href="#"
										<?php if ( $data_archive_type && $data_archive_value ): ?>
                                            data-archivetype="<?php echo $data_archive_type; ?>"
                                            data-archivevalue="<?php echo $data_archive_value; ?>"
                                            data-arppp="<?php echo $ppp; ?>"
										<?php endif; ?>
                                       data-query_type="<?php echo $archive_buider_check; ?>"
                                       data-layout="<?php echo $biggid_style; ?>"
                                       data-settings="<?php echo $data_settings_ajax; ?>"
                                       data-pagednum="<?php echo( (int) $data_paged + 1 ); ?>"
                                       data-mes="<?php echo penci_get_setting( 'penci_trans_no_more_posts' ); ?>">
                                        <span class="ajax-more-text"><?php echo penci_get_setting( 'penci_trans_load_more_posts' ); ?></span><span
                                                class="ajaxdot"></span><?php penci_fawesome_icon( 'fas fa-sync' ); ?>
                                    </a>
                                </div>
								<?php
							} elseif ( 'numbers' == $paging ) {
								echo penci_pagination_numbers( $query_custom, $paging_align );
							}
						}
						wp_reset_postdata();

					} else {
						$biggrid_items = $settings['biggrid_items'] ? (array) $settings['biggrid_items'] : array();
						// echo '<pre>';
						// print_r( $biggrid_items );
						// echo '</pre>';
						$num_posts = count( $biggrid_items );
						if ( ! empty( $biggrid_items ) ) {
							echo '<div class="penci-clearfix penci-biggrid-data' . $data_class . '">';
							foreach ( $biggrid_items as $setting ) {
								$is_big_item = '';
								$surplus     = penci_big_grid_count_classes( $bg, $biggid_style, true );
								$thumbnail   = $thumb_size;
								if ( ! empty( $big_items ) && in_array( $surplus, $big_items ) ) {
									$thumbnail   = $bthumb_size;
									$is_big_item = ' pcbg-big-item';
								}
								if ( penci_is_mobile() ) {
									$thumbnail = $mthumb_size;
								}

								/* Get Custom Items Data */
								$item_id     = $setting['_id'] ? ' elementor-repeater-item-' . $setting['_id'] : '';
								$image_data  = $setting['image']['url'] ? $setting['image']['url'] : '';
								$image_url   = penci_get_image_size_url( $image_data, $thumbnail );
								$image_ratio = penci_get_ratio_size_based_url( $image_data );
								$sub_title   = $setting['sub_title'] ? $setting['sub_title'] : '';

								$title           = $setting['title'] ? $setting['title'] : '';
								$title_link      = $setting['title_link']['url'] ? $setting['title_link']['url'] : '';
								$title_external  = $setting['title_link']['is_external'] ? ' target="_blank"' : '';
								$title_nofollow  = $setting['title_link']['nofollow'] ? ' rel="nofollow"' : '';
								$title_attr      = '';
								$title_attr_data = $setting['title_link']['custom_attributes'] ? $setting['title_link']['custom_attributes'] : '';
								if ( $title_attr_data ) {
									$title_attr = $this->get_data_link( $title_attr_data );
								}

								$desc = $setting['desc'] ? $setting['desc'] : '';

								$button_text      = $setting['button_text'] ? $setting['button_text'] : '';
								$button_link      = $setting['button_link']['url'] ? $setting['button_link']['url'] : '';
								$button_external  = $setting['button_link']['is_external'] ? ' target="_blank"' : '';
								$button_nofollow  = $setting['button_link']['nofollow'] ? ' rel="nofollow"' : '';
								$button_attr      = '';
								$button_attr_data = $setting['button_link']['custom_attributes'] ? $setting['button_link']['custom_attributes'] : '';
								if ( $button_attr_data ) {
									$button_attr = $this->get_data_link( $button_attr_data );
								}

								include dirname( __FILE__ ) . "/custom.php";

								if ( $flag_style && $surplus == 0 && $bg < $num_posts ) {
									echo '</div><div class="penci-clearfix penci-biggrid-data' . $data_class . '">';
								}

								$bg ++;
							}
							echo '</div>';
						}
					}
					?>
                </div>
				<?php
				if ( $settings['biggrid_ajaxfilter_cat'] || $settings['biggrid_ajaxfilter_tag'] || $settings['biggrid_ajaxfilter_author'] || 'nextprev' == $settings['paging'] ) {
					echo penci_get_html_animation_loading( $settings['biggrid_ajax_loading_style'] );
				} ?>
            </div>
        </div>
		<?php
	}

	public static function show_missing_settings( $label, $mess ) {
		$output = '';
		if ( current_user_can( 'manage_options' ) ) {
			$output .= '<div class="penci-missing-settings">';
			$output .= '<p style="margin-bottom: 4px;">This message appears for administrator users only</p>';
			$output .= '<span>' . $label . '</span>';
			$output .= $mess;
			$output .= '</div>';
		}

		return $output;
	}

	public function get_data_link( $attr ) {
		$output = '';
		if ( $attr ) {
			$attr     = str_replace( ' ', '', $attr );
			$attr_all = explode( ',', $attr );
			foreach ( $attr_all as $data ) {
				$attr_each = explode( '|', $data );
				if ( $attr_each[0] && $attr_each[1] ) {
					$output .= ' ' . $attr_each[0] . '="' . $attr_each[1] . '"';
				}
			}
		}

		return $output;
	}
}
