<?php

namespace PenciSoledadElementor\Base;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Widget_Base;
use PenciSoledadElementor\Modules\QueryControl\Controls\Penci_Group_Control_Posts;
use PenciSoledadElementor\Modules\QueryControl\Module;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

abstract class Base_Widget extends Widget_Base {
	public static function markup_block_title( $args, $self = null ) {
		$defaults = array(
			'heading_title_style'    => 'style-1',
			'heading_title_html_tag' => 'h3',
			'heading'                => '',
			'heading_title_link'     => '',
			'add_title_icon'         => '',
			'block_title_icon'       => '',
			'block_title_ialign'     => '',
			'block_title_align'      => '',
			'heading_icon_pos'       => '',
			'heading_icon'           => '',
			'block_title_marginbt'   => '',
			'blockid'                => '',
		);

		$r = wp_parse_args( $args, $defaults );

		if ( ! $r['heading'] ) {
			return;
		}

		if ( 'video_list' == $r['heading_title_style'] ) {
			return;
		}

		$heading_title = get_theme_mod( 'penci_sidebar_heading_style' ) ? get_theme_mod( 'penci_sidebar_heading_style' ) : 'style-1';
		$heading_align = get_theme_mod( 'penci_sidebar_heading_align' ) ? get_theme_mod( 'penci_sidebar_heading_align' ) : 'pcalign-center';


		if ( $r['heading_title_style'] ) {
			$heading_title = $r['heading_title_style'];
		}

		if ( $r['block_title_align'] ) {
			$heading_align = 'pcalign-' . $r['block_title_align'];
		}

		$heading_icon_pos    = get_theme_mod( 'penci_sidebar_icon_align' ) ? get_theme_mod( 'penci_sidebar_icon_align' ) : 'pciconp-right';
		$heading_icon_design = get_theme_mod( 'penci_sidebar_icon_design' ) ? get_theme_mod( 'penci_sidebar_icon_design' ) : 'pcicon-right';

		if ( $r['heading_icon_pos'] ) {
			$heading_icon_pos = $r['heading_icon_pos'];
		}

		if ( $r['heading_icon'] ) {
			$heading_icon_design = $r['heading_icon'];
		}

		$classes = 'penci-border-arrow penci-homepage-title penci-home-latest-posts';
		$classes .= ' ' . $heading_title;
		$classes .= ' ' . $heading_align;
		$classes .= ' ' . $heading_icon_pos;
		$classes .= ' ' . $heading_icon_design;
		$classes .= $r['block_title_ialign'] ? ' block-title-icon-' . $r['block_title_ialign'] : ' block-title-icon-left';
		$htag    = $r['heading_title_html_tag'];
		?>
    <div class="<?php echo esc_attr( $classes ); ?>">
        <<?php echo $htag; ?> class="inner-arrow">
        <span>
                    <?php
                    if ( $r['heading_title_link']['url'] ) {
	                    $self->add_render_attribute( 'link', 'href', $r['heading_title_link']['url'] );
	                    if ( $r['heading_title_link']['is_external'] ) {
		                    $self->add_render_attribute( 'link', 'target', '_blank' );
	                    }

	                    if ( $r['heading_title_link']['nofollow'] ) {
		                    $self->add_render_attribute( 'link', 'rel', 'nofollow' );
	                    }

	                    echo '<a ' . $self->get_render_attribute_string( 'link' ) . '>';
                    } else {
	                    echo '<span>';
                    }

                    if ( $r['add_title_icon'] && $r['block_title_icon'] && 'left' == $r['block_title_ialign'] ) {
	                    \Elementor\Icons_Manager::render_icon( $r['block_title_icon'] );
                    }
                    echo do_shortcode( $r['heading'] );
                    if ( $r['add_title_icon'] && $r['block_title_icon'] && 'right' == $r['block_title_ialign'] ) {
	                    \Elementor\Icons_Manager::render_icon( $r['block_title_icon'] );
                    }
                    if ( $r['heading_title_link'] ) {
	                    echo '</a>';
                    } else {
	                    echo '</span>';
                    }
                    ?>
                </span>
        </<?php echo $htag; ?>>
		<?php do_action( 'penci_block_title_extra_' . $r['blockid'] ); ?>
        </div>
		<?php
	}

	public function get_categories() {
		return array( 'basic' );
	}

	public function register_block_title_ajax_filter( $navigation = false, $condition = [] ) {
		$this->start_controls_section( 'biggrid_section_afilter', array(
			'label'     => __( 'Heading Ajax Filter', 'soledad' ),
			'condition' => $condition
		) );

		$this->add_control( 'group_more_link_text', array(
			'label'       => __( 'Add Text Before More Icon', 'soledad' ),
			'type'        => Controls_Manager::TEXT,
			'label_block' => true,
			'default'     => '',
		) );

		$this->add_control( 'group_more_nopost', array(
			'label'       => __( 'No Post Found Message', 'soledad' ),
			'type'        => Controls_Manager::TEXT,
			'label_block' => true,
			'default'     => penci_get_setting( 'penci_ajaxsearch_no_post' ),
		) );

		$this->add_control( 'group_more_defaultab_text', array(
			'label'       => __( 'Default Tab Text', 'soledad' ),
			'type'        => Controls_Manager::TEXT,
			'label_block' => true,
			'default'     => penci_get_setting( 'penci_trans_all' ),
		) );

		$this->add_control( 'biggrid_ajaxfilter_cat', array(
			'label'       => esc_html__( 'Category', 'soledad' ),
			'type'        => 'penci_el_autocomplete',
			'search'      => 'penci_get_taxonomies_by_query',
			'render'      => 'penci_get_taxonomies_title_by_id',
			'taxonomy'    => 'category',
			'multiple'    => true,
			'label_block' => true,
		) );

		$this->add_control( 'biggrid_ajaxfilter_tag', array(
			'label'       => esc_html__( 'Tags', 'soledad' ),
			'type'        => 'penci_el_autocomplete',
			'search'      => 'penci_get_taxonomies_by_query',
			'render'      => 'penci_get_taxonomies_title_by_id',
			'taxonomy'    => 'post_tag',
			'multiple'    => true,
			'label_block' => true,
		) );

		$this->add_control( 'biggrid_ajaxfilter_author', array(
			'label'       => esc_html__( 'Author', 'soledad' ),
			'label_block' => true,
			'multiple'    => true,
			'type'        => 'penci_el_autocomplete',
			'search'      => 'penci_get_user_by_query',
			'render'      => 'penci_get_user_title_by_id',
		) );

		$this->add_control( 'biggrid_ajax_loading_style', array(
			'type'    => Controls_Manager::SELECT,
			'label'   => esc_html__( 'Loading Icon Style', 'soledad' ),
			'default' => 'df',
			'options' => [
				'df' => __( 'Follow Customize', 'soledad' ),
				's9' => __( 'Style 1', 'soledad' ),
				's2' => __( 'Style 2', 'soledad' ),
				's3' => __( 'Style 3', 'soledad' ),
				's4' => __( 'Style 4', 'soledad' ),
				's5' => __( 'Style 5', 'soledad' ),
				's6' => __( 'Style 6', 'soledad' ),
				's1' => __( 'Style 7', 'soledad' ),
			],
			//'label_block' => true,
		) );

		if ( $navigation ) {
			$this->add_control( 'paging', array(
				'label' => __( 'Show Ajax Previous/Next Buttons', 'soledad' ),
				'type'  => Controls_Manager::SWITCHER,
			) );
		}

		$this->end_controls_section();
	}

	public function register_block_title_section_controls() {
		$this->start_controls_section( 'section_title_block', array(
			'label' => __( 'Heading Title', 'soledad' ),
			'tab'   => Controls_Manager::TAB_CONTENT,
		) );

		$this->add_control( 'heading_title_style', array(
			'label'   => __( 'Choose Style', 'soledad' ),
			'type'    => Controls_Manager::SELECT,
			'default' => '',
			'options' => array(
				''                  => esc_html__( 'Default ( follow Customize )', 'soledad' ),
				'style-1'           => esc_html__( 'Style 1', 'soledad' ),
				'style-2'           => esc_html__( 'Style 2', 'soledad' ),
				'style-3'           => esc_html__( 'Style 3', 'soledad' ),
				'style-4'           => esc_html__( 'Style 4', 'soledad' ),
				'style-5'           => esc_html__( 'Style 5', 'soledad' ),
				'style-6'           => esc_html__( 'Style 6 - Only Text', 'soledad' ),
				'style-7'           => esc_html__( 'Style 7', 'soledad' ),
				'style-9'           => esc_html__( 'Style 8', 'soledad' ),
				'style-8'           => esc_html__( 'Style 9 - Custom Background Image', 'soledad' ),
				'style-10'          => esc_html__( 'Style 10', 'soledad' ),
				'style-11'          => esc_html__( 'Style 11', 'soledad' ),
				'style-12'          => esc_html__( 'Style 12', 'soledad' ),
				'style-13'          => esc_html__( 'Style 13', 'soledad' ),
				'style-14'          => esc_html__( 'Style 14', 'soledad' ),
				'style-15'          => esc_html__( 'Style 15', 'soledad' ),
				'style-16'          => esc_html__( 'Style 16', 'soledad' ),
				'style-2 style-17'  => esc_html__( 'Style 17', 'soledad' ),
				'style-18'          => esc_html__( 'Style 18', 'soledad' ),
				'style-18 style-19' => esc_html__( 'Style 19', 'soledad' ),
				'style-18 style-20' => esc_html__( 'Style 20', 'soledad' ),
				'style-21'          => esc_html__( 'Style 21', 'soledad' ),
				'style-22'          => esc_html__( 'Style 22', 'soledad' ),
				'style-23'          => esc_html__( 'Style 23', 'soledad' ),
				'style-24'          => esc_html__( 'Style 24', 'soledad' ),
				'style-25'          => __( 'Style 25', 'soledad' ),
				'style-26'          => __( 'Style 26', 'soledad' ),
				'style-27'          => __( 'Style 27', 'soledad' ),
				'style-28'          => __( 'Style 28', 'soledad' ),
				'style-29'          => __( 'Style 29', 'soledad' ),
				'style-30'          => __( 'Style 30', 'soledad' ),
			)
		) );
		$this->add_control( 'heading_title_html_tag', array(
			'label'   => __( 'HTML Tag', 'soledad' ),
			'type'    => Controls_Manager::SELECT,
			'default' => 'h3',
			'options' => array(
				'h1' => 'H1',
				'h2' => 'H2',
				'h3' => 'H3',
				'h4' => 'H4',
				'h5' => 'H5',
				'h6' => 'H6',
			)
		) );
		$this->add_control( 'heading', array(
			'label'   => __( 'Heading Title', 'soledad' ),
			'type'    => Controls_Manager::TEXT,
			'default' => __( 'Heading Title', 'soledad' ),
		) );
		$this->add_control( 'heading_title_link', array(
			'label'       => __( 'Title url', 'soledad' ),
			'type'        => Controls_Manager::URL,
			'placeholder' => __( 'https://your-link.com', 'soledad' ),
			'separator'   => 'before',
		) );

		$this->add_control( 'add_title_icon', array(
			'label'     => __( 'Add icon for title?', 'soledad' ),
			'type'      => Controls_Manager::SWITCHER,
			'label_on'  => __( 'Show', 'soledad' ),
			'label_off' => __( 'Hide', 'soledad' ),
			'default'   => '',
			'separator' => 'before',
		) );

		$this->add_control( 'block_title_icon', array(
			'label'     => __( 'Icon', 'soledad' ),
			'type'      => Controls_Manager::ICONS,
			'default'   => array( 'value' => 'fas fa-star', 'library' => 'solid' ),
			'condition' => array(
				'add_title_icon' => 'yes'
			),
		) );
		$this->add_control( 'block_title_ialign', array(
			'label'     => __( 'Icon Alignment', 'soledad' ),
			'type'      => Controls_Manager::SELECT,
			'default'   => 'left',
			'options'   => array(
				'left'  => esc_html__( 'Left', 'soledad' ),
				'right' => esc_html__( 'Right', 'soledad' ),
			),
			'condition' => array(
				'add_title_icon' => 'yes'
			),
		) );

		$this->add_responsive_control( 'block_title_ioffset', array(
			'label'       => __( 'Icon Offset', 'soledad' ),
			'description' => __( 'Adjust the vertical space', 'soledad' ),
			'type'        => Controls_Manager::NUMBER,
			'condition'   => array(
				'add_title_icon' => 'yes'
			),
			'selectors'   => array(
				'{{WRAPPER}} .inner-arrow i'   => 'transform: translateY({{SIZE}}px);',
				'{{WRAPPER}} .inner-arrow svg' => 'transform: translateY({{SIZE}}px);',
			)
		) );

		$this->add_responsive_control( 'block_title_isize', array(
			'label'     => __( 'Icon Size', 'soledad' ),
			'type'      => Controls_Manager::NUMBER,
			'condition' => array(
				'add_title_icon' => 'yes'
			),
			'selectors' => array(
				'{{WRAPPER}} .inner-arrow i'   => 'font-size: {{SIZE}}px;',
				'{{WRAPPER}} .inner-arrow svg' => 'width: {{SIZE}}px;height: auto',
			)
		) );

		$this->add_control( 'block_title_align', array(
			'label'   => __( 'Heading Align', 'soledad' ),
			'type'    => Controls_Manager::SELECT,
			'default' => '',
			'options' => array(
				''       => esc_html__( 'Default ( follow Customize )', 'soledad' ),
				'left'   => esc_html__( 'Left', 'soledad' ),
				'center' => esc_html__( 'Center', 'soledad' ),
				'right'  => esc_html__( 'Right', 'soledad' )
			)
		) );

		$this->add_control( 'heading_icon_pos', array(
			'label'     => __( 'Align Icon on Style 15', 'soledad' ),
			'type'      => Controls_Manager::SELECT,
			'default'   => '',
			'options'   => array(
				''              => esc_html__( 'Default ( follow Customize )', 'soledad' ),
				'pciconp-right' => esc_html__( 'Right', 'soledad' ),
				'pciconp-left'  => esc_html__( 'Left', 'soledad' ),
			),
			'condition' => array( 'heading_title_style' => array( 'style-15' ) ),
		) );
		$this->add_control( 'heading_icon', array(
			'label'     => __( 'Custom Icon on Style 15', 'soledad' ),
			'type'      => Controls_Manager::SELECT,
			'default'   => '',
			'options'   => array(
				''             => esc_html__( 'Default ( follow Customize )', 'soledad' ),
				'pcicon-right' => esc_html__( 'Arrow Right', 'soledad' ),
				'pcicon-left'  => esc_html__( 'Arrow Left', 'soledad' ),
				'pcicon-down'  => esc_html__( 'Arrow Down', 'soledad' ),
				'pcicon-up'    => esc_html__( 'Arrow Up', 'soledad' ),
				'pcicon-star'  => esc_html__( 'Star', 'soledad' ),
				'pcicon-bars'  => esc_html__( 'Bars', 'soledad' ),
				'pcicon-file'  => esc_html__( 'File', 'soledad' ),
				'pcicon-fire'  => esc_html__( 'Fire', 'soledad' ),
				'pcicon-book'  => esc_html__( 'Book', 'soledad' ),
			),
			'condition' => array( 'heading_title_style' => array( 'style-15' ) ),
		) );

		$this->add_responsive_control( 'block_title_marginbt', array(
			'label'     => __( 'Margin Bottom', 'soledad' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => array( 'px' => array( 'min' => 0, 'max' => 100, ) ),
			'selectors' => array( '{{WRAPPER}} .penci-homepage-title' => 'margin-bottom: {{SIZE}}px' ),
		) );

		$this->end_controls_section();
	}

	public function register_block_title_section_controls_post() {
		$this->start_controls_section( 'section_title_block', array(
			'label' => __( 'Heading Title', 'soledad' ),
			'tab'   => Controls_Manager::TAB_CONTENT,
		) );
		$this->add_control( 'hide_block_heading', array(
			'label' => __( 'Hide Heading Title', 'soledad' ),
			'type'  => Controls_Manager::SWITCHER,
		) );
		$this->add_control( 'heading_title_style', array(
			'label'   => __( 'Choose Style', 'soledad' ),
			'type'    => Controls_Manager::SELECT,
			'default' => '',
			'options' => array(
				''                  => esc_html__( 'Default ( follow Customize )', 'soledad' ),
				'style-1'           => esc_html__( 'Style 1', 'soledad' ),
				'style-2'           => esc_html__( 'Style 2', 'soledad' ),
				'style-3'           => esc_html__( 'Style 3', 'soledad' ),
				'style-4'           => esc_html__( 'Style 4', 'soledad' ),
				'style-5'           => esc_html__( 'Style 5', 'soledad' ),
				'style-6'           => esc_html__( 'Style 6 - Only Text', 'soledad' ),
				'style-7'           => esc_html__( 'Style 7', 'soledad' ),
				'style-9'           => esc_html__( 'Style 8', 'soledad' ),
				'style-8'           => esc_html__( 'Style 9 - Custom Background Image', 'soledad' ),
				'style-10'          => esc_html__( 'Style 10', 'soledad' ),
				'style-11'          => esc_html__( 'Style 11', 'soledad' ),
				'style-12'          => esc_html__( 'Style 12', 'soledad' ),
				'style-13'          => esc_html__( 'Style 13', 'soledad' ),
				'style-14'          => esc_html__( 'Style 14', 'soledad' ),
				'style-15'          => esc_html__( 'Style 15', 'soledad' ),
				'style-16'          => esc_html__( 'Style 16', 'soledad' ),
				'style-2 style-17'  => esc_html__( 'Style 17', 'soledad' ),
				'style-18'          => esc_html__( 'Style 18', 'soledad' ),
				'style-18 style-19' => esc_html__( 'Style 19', 'soledad' ),
				'style-18 style-20' => esc_html__( 'Style 20', 'soledad' ),
				'style-21'          => esc_html__( 'Style 21', 'soledad' ),
				'style-22'          => esc_html__( 'Style 22', 'soledad' ),
				'style-23'          => esc_html__( 'Style 23', 'soledad' ),
				'style-24'          => esc_html__( 'Style 24', 'soledad' ),
				'style-25'          => __( 'Style 25', 'soledad' ),
				'style-26'          => __( 'Style 26', 'soledad' ),
				'style-27'          => __( 'Style 27', 'soledad' ),
				'style-28'          => __( 'Style 28', 'soledad' ),
				'style-29'          => __( 'Style 29', 'soledad' ),
				'style-30'          => __( 'Style 30', 'soledad' ),
			)
		) );
		$this->add_control( 'heading_title_html_tag', array(
			'label'   => __( 'HTML Tag', 'soledad' ),
			'type'    => Controls_Manager::SELECT,
			'default' => 'h3',
			'options' => array(
				'h1' => 'H1',
				'h2' => 'H2',
				'h3' => 'H3',
				'h4' => 'H4',
				'h5' => 'H5',
				'h6' => 'H6',
			)
		) );
		$this->add_control( 'heading', array(
			'label'   => __( 'Heading Title', 'soledad' ),
			'type'    => Controls_Manager::TEXT,
			'default' => __( 'Heading Title', 'soledad' ),
		) );
		$this->add_control( 'heading_title_link', array(
			'label'       => __( 'Title url', 'soledad' ),
			'type'        => Controls_Manager::TEXT,
			'placeholder' => __( 'https://your-link.com', 'soledad' ),
		) );
		$this->add_control( 'block_title_align', array(
			'label'   => __( 'Heading Align', 'soledad' ),
			'type'    => Controls_Manager::SELECT,
			'default' => '',
			'options' => array(
				''               => esc_html__( 'Default ( follow Customize )', 'soledad' ),
				'pcalign-left'   => esc_html__( 'Left', 'soledad' ),
				'pcalign-center' => esc_html__( 'Center', 'soledad' ),
				'pcalign-right'  => esc_html__( 'Right', 'soledad' )
			)
		) );
		$this->add_control( 'heading_icon_pos', array(
			'label'     => __( 'Align Icon on Style 15', 'soledad' ),
			'type'      => Controls_Manager::SELECT,
			'default'   => '',
			'options'   => array(
				''              => esc_html__( 'Default ( follow Customize )', 'soledad' ),
				'pciconp-right' => esc_html__( 'Right', 'soledad' ),
				'pciconp-left'  => esc_html__( 'Left', 'soledad' ),
			),
			'condition' => array( 'heading_title_style' => array( 'style-15' ) ),
		) );
		$this->add_control( 'heading_icon', array(
			'label'     => __( 'Custom Icon on Style 15', 'soledad' ),
			'type'      => Controls_Manager::SELECT,
			'default'   => '',
			'options'   => array(
				''             => esc_html__( 'Default ( follow Customize )', 'soledad' ),
				'pcicon-right' => esc_html__( 'Arrow Right', 'soledad' ),
				'pcicon-left'  => esc_html__( 'Arrow Left', 'soledad' ),
				'pcicon-down'  => esc_html__( 'Arrow Down', 'soledad' ),
				'pcicon-up'    => esc_html__( 'Arrow Up', 'soledad' ),
				'pcicon-star'  => esc_html__( 'Star', 'soledad' ),
				'pcicon-bars'  => esc_html__( 'Bars', 'soledad' ),
				'pcicon-file'  => esc_html__( 'File', 'soledad' ),
				'pcicon-fire'  => esc_html__( 'Fire', 'soledad' ),
				'pcicon-book'  => esc_html__( 'Book', 'soledad' ),
			),
			'condition' => array( 'heading_title_style' => array( 'style-15' ) ),
		) );
		$this->add_responsive_control( 'block_title_marginbt', array(
			'label'     => __( 'Margin Bottom', 'soledad' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => array( 'px' => array( 'min' => 0, 'max' => 100, ) ),
			'selectors' => array( '{{WRAPPER}} .penci-homepage-title' => 'margin-bottom: {{SIZE}}px' ),
		) );

		$this->end_controls_section();
	}

	public function register_block_heading_link_section_style( $title = 'Heading Ajax Filter' ) {
		$this->start_controls_section( 'section_extrabtn_group', array(
			'label' => $title,
			'tab'   => Controls_Manager::TAB_STYLE,
		) );
		$this->add_responsive_control( 'btn_group_link_spacing', array(
			'label'     => __( 'Spacing', 'soledad' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => array( 'px' => array( 'min' => 0, 'max' => 50, ) ),
			'selectors' => array(
				'{{WRAPPER}} .pcnav-lgroup ul.pcflx li a'    => 'padding-left: calc({{SIZE}}px / 2);padding-right: calc({{SIZE}}px / 2);',
				'{{WRAPPER}} .pcnav-lgroup ul.pcflx ul li a' => 'padding-left: 0;padding-right: 0;',
				'{{WRAPPER}} .pcnav-lgroup ul.pcflx-nav'     => 'padding-left: calc({{SIZE}}px - 3px);',
			),
		) );
		$this->add_group_control( Group_Control_Typography::get_type(), array(
			'name'     => 'btn_group_link_typo',
			'label'    => __( 'Typography', 'soledad' ),
			'selector' => '{{WRAPPER}} .pcnav-lgroup ul li a',
		) );
		$this->add_responsive_control( 'btn_group_nav_size', array(
			'label'     => __( 'Next/Prev Buttons Font Size', 'soledad' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => array( 'px' => array( 'min' => 0, 'max' => 50, ) ),
			'selectors' => array(
				'{{WRAPPER}} .pcnav-lgroup ul > li > a.pcaj-nav-link' => 'font-size:{{SIZE}}px;',
			),
		) );
		$this->add_control( 'btn_group_link_color', array(
			'label'     => __( 'Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => array(
				'{{WRAPPER}} .pcnav-lgroup ul > li > a,{{WRAPPER}} .pcnav-lgroup ul > li' => 'color: {{VALUE}};opacity: 1;',
			),
		) );
		$this->add_control( 'btn_group_link_hcolor', array(
			'label'     => __( 'Hover & Active Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => array(
				'{{WRAPPER}} .pcnav-lgroup ul > li > a:hover,{{WRAPPER}} .pcnav-lgroup ul > li:hover,{{WRAPPER}} .pcnav-lgroup ul li > a.clactive' => 'color: {{VALUE}};opacity: 1;',
			),
		) );
		$this->add_control( 'filter_group_dbgcolor', array(
			'label'     => __( 'Filter Background Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => array(
				'body:not(.pcdm-enable) {{WRAPPER}} .penci-homepage-title .pcnav-lgroup > ul'         => 'background-color: {{VALUE}};',
				'body:not(.pcdm-enable) {{WRAPPER}} .penci-homepage-title.style-4 .pcnav-lgroup > ul' => 'background-color: {{VALUE}};',
			),
		) );
		$this->add_control( 'btn_group_dbgcolor', array(
			'label'     => __( 'Dropdown Background Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => array(
				'body:not(.pcdm-enable) {{WRAPPER}} .pcnav-lgroup ul ul' => 'background-color: {{VALUE}};',
			),
		) );
		$this->add_control( 'btn_group_dbdcolor', array(
			'label'     => __( 'Dropdown Borders Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => array(
				'{{WRAPPER}} .pcnav-lgroup ul ul li,{{WRAPPER}} .pcnav-lgroup ul ul' => 'border-color: {{VALUE}};',
			),
		) );
		$this->add_group_control( Group_Control_Typography::get_type(), array(
			'name'     => 'btn_group_sublink_typo',
			'label'    => __( 'Dropdown Typography', 'soledad' ),
			'selector' => '{{WRAPPER}}  .pcnav-lgroup ul ul li a',
		) );
		$this->add_control( 'btn_group_droplcolor', array(
			'label'     => __( 'Dropdown Link Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => array(
				'{{WRAPPER}} .pcnav-lgroup ul ul li,{{WRAPPER}} .pcnav-lgroup ul ul li a' => 'color: {{VALUE}};opacity: 1;',
			),
		) );
		$this->add_control( 'btn_group_droplhcolor', array(
			'label'     => __( 'Dropdown Hover & Active Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => array(
				'{{WRAPPER}} .pcnav-lgroup ul ul li a.clactive,{{WRAPPER}} .pcnav-lgroup ul ul li:hover,{{WRAPPER}} .pcnav-lgroup ul ul li a:hover' => 'color: {{VALUE}};opacity: 1;',
			),
		) );
		$this->add_control( 'btn_group_loading_icon', array(
			'label'     => __( 'Loading Icon Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'condition' => [ 'biggrid_ajax_loading_style!' => '' ],
			'selectors' => array(
				'{{WRAPPER}} .penci-loading-animation-1 .penci-loading-animation,{{WRAPPER}} .penci-loading-animation-1 .penci-loading-animation:before,{{WRAPPER}} .penci-loading-animation-1 .penci-loading-animation:after,{{WRAPPER}} .penci-loading-animation-5 .penci-loading-animation,{{WRAPPER}} .penci-loading-animation-6 .penci-loading-animation:before,{{WRAPPER}} .penci-loading-animation-7 .penci-loading-animation,{{WRAPPER}} .penci-loading-animation-8 .penci-loading-animation,{{WRAPPER}} .penci-loading-animation-9 .penci-loading-circle-inner:before,{{WRAPPER}} .penci-loading-animation-1>div,{{WRAPPER}} .penci-three-bounce .one,{{WRAPPER}} .penci-three-bounce .two,.penci-three-bounce .three' => 'background-color: {{VALUE}}',
				'{{WRAPPER}}'                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                   => '--pc-loader-2:{{VALUE}}'
			),
		) );
		$this->add_control( 'btn_group_loading_bgcolor', array(
			'label'     => __( 'Loading Overlay Background Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'condition' => [ 'biggrid_ajax_loading_style!' => '' ],
			'selectors' => array(
				'{{WRAPPER}} .pcftaj-ld:before' => 'background-color:{{VALUE}}'
			),
		) );
		$this->add_control( 'btn_group_loading_bgopacity', array(
			'label'     => __( 'Loading Overlay Background Opacity', 'soledad' ),
			'type'      => Controls_Manager::SLIDER,
			'condition' => [ 'biggrid_ajax_loading_style!' => '' ],
			'range'     => array( 'px' => array( 'min' => 0.1, 'max' => 1, 'step' => 0.01 ) ),
			'selectors' => array(
				'{{WRAPPER}} .pcftaj-ld:before' => 'opacity:{{SIZE}}'
			),
		) );
		$this->end_controls_section();
	}

	public function register_block_title_style_section_controls() {
		$this->start_controls_section( 'section_title_block_style', array(
			'label' => __( 'Block Heading Title', 'soledad' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		) );

		$this->add_control( 'block_title_color', array(
			'label'     => __( 'Title Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => array(
				'{{WRAPPER}} .penci-homepage-title.style-21 .inner-arrow span,{{WRAPPER}} .penci-homepage-title.style-22 .inner-arrow span,{{WRAPPER}} .penci-homepage-title.style-23 .inner-arrow span,{{WRAPPER}} .penci-homepage-title.style-24 .inner-arrow span,{{WRAPPER}} .penci-homepage-title.style-28' => '--pcheading-cl: {{VALUE}};',
				'{{WRAPPER}} .penci-border-arrow .inner-arrow'                                                                                                                                                                                                        => 'color: {{VALUE}};',
				'{{WRAPPER}} .penci-border-arrow .inner-arrow a'                                                                                                                                                                                                      => 'color: {{VALUE}};',
				'{{WRAPPER}} .home-pupular-posts-title, {{WRAPPER}} .home-pupular-posts-title a, .penci-homepage-title.style-25 .inner-arrow > span'                                                                                                                  => 'color: {{VALUE}};',
			),
		) );
		$this->add_control( 'block_title_hcolor', array(
			'label'     => __( 'Title Hover Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => array(
				'{{WRAPPER}} .penci-border-arrow .inner-arrow a:hover' => 'color: {{VALUE}} !important;',
				'{{WRAPPER}} .home-pupular-posts-title a:hover'        => 'color: {{VALUE}} !important;',
			),
		) );
		$this->add_control( 'block_title_bcolor', array(
			'label'     => __( 'Border Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => array(
				'{{WRAPPER}} .penci-homepage-title.style-21 .inner-arrow span'                                                                                                                                                                                                                                                                                                                                                                                                                               => '--pcaccent-cl: {{VALUE}}',
				'{{WRAPPER}} .penci-homepage-title.style-26 .inner-arrow,{{WRAPPER}} .penci-homepage-title.style-27 .inner-arrow'                                                                                                                                                                                                                                                                                                                                                                            => '--pcborder-cl: {{VALUE}}',
				'{{WRAPPER}} .penci-homepage-title.style-22 .inner-arrow:before'                                                                                                                                                                                                                                                                                                                                                                                                                             => 'background-color: {{VALUE}};',
				'{{WRAPPER}} .penci-homepage-title.style-23 .inner-arrow > span:before,{{WRAPPER}} .penci-homepage-title.style-23 .inner-arrow > span:before'                                                                                                                                                                                                                                                                                                                                                => 'background-color: {{VALUE}};',
				'{{WRAPPER}} .penci-homepage-title.style-24 .inner-arrow > span:before'                                                                                                                                                                                                                                                                                                                                                                                                                      => 'background-color: {{VALUE}};',
				'body:not(.pcdm-enable) {{WRAPPER}} .penci-border-arrow .inner-arrow,' . '{{WRAPPER}} .style-4.penci-border-arrow .inner-arrow > span:after,' . '{{WRAPPER}} .style-4.penci-border-arrow .inner-arrow > span:before,' . '{{WRAPPER}} .style-4.penci-border-arrow .inner-arrow:before,' . '{{WRAPPER}} .style-4.penci-border-arrow .inner-arrow:after,' . '{{WRAPPER}} .style-5.penci-border-arrow,' . '{{WRAPPER}} .style-7.penci-border-arrow,' . '{{WRAPPER}} .style-9.penci-border-arrow' => 'border-color: {{VALUE}}',
				'body:not(.pcdm-enable) {{WRAPPER}} .penci-border-arrow:before'                                                                                                                                                                                                                                                                                                                                                                                                                              => 'border-top-color: {{VALUE}}',
				'body:not(.pcdm-enable) {{WRAPPER}} .style-16.penci-border-arrow:after'                                                                                                                                                                                                                                                                                                                                                                                                                      => 'background-color: {{VALUE}}',
				'body:not(.pcdm-enable) {{WRAPPER}} .penci-home-popular-posts'                                                                                                                                                                                                                                                                                                                                                                                                                               => 'border-top-color: {{VALUE}}',
			),
			'condition' => array( 'heading_title_style!' => array( 'style-28' ) ),
		) );
		$this->add_control( 'btitle_outer_bcolor', array(
			'label'     => __( 'Border Outer Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => array(
				'{{WRAPPER}} .penci-border-arrow:after'                                => 'border-color: {{VALUE}};',
				'{{WRAPPER}} .penci-homepage-title.style-22 .inner-arrow:after'        => 'background-color: {{VALUE}};',
				'{{WRAPPER}} .penci-homepage-title.style-24 .inner-arrow > span:after' => 'background-color: {{VALUE}};',
				'{{WRAPPER}} .penci-homepage-title.style-23 .inner-arrow > span:after' => 'background-color: {{VALUE}};'
			),
			'condition' => array( 'heading_title_style!' => array( 'style-28' ) ),
		) );
		$this->add_control( 'btitle_style10_btopcolor', array(
			'label'     => __( 'Border Top', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => array(
				'{{WRAPPER}} .penci-homepage-title.style-10' => 'border-top-color: {{VALUE}};'
			),
			'condition' => array( 'heading_title_style' => 'style-10' ),
		) );

		$this->add_control( 'btitle_style5_bcolor', array(
			'label'     => __( 'Border Bottom', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => array(
				'{{WRAPPER}} .style-5.penci-border-arrow'              => 'border-color: {{VALUE}};',
				'{{WRAPPER}} .penci-homepage-title.style-10'           => 'border-bottom-color: {{VALUE}};',
				'{{WRAPPER}} .style-12.penci-border-arrow'             => 'border-bottom-color: {{VALUE}};',
				'{{WRAPPER}} .style-11.penci-border-arrow'             => 'border-bottom-color: {{VALUE}};',
				'{{WRAPPER}} .style-5.penci-border-arrow .inner-arrow' => 'border-bottom-color: {{VALUE}};',
			),
			'condition' => array( 'heading_title_style' => array( 'style-5', 'style-10', 'style-11', 'style-12' ) ),
		) );
		$this->add_control( 'btitle_style78_bcolor', array(
			'label'     => __( 'Small Border Bottom on Style 7 & 8', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => array(
				'body:not(.pcdm-enable) {{WRAPPER}} .style-7.penci-border-arrow .inner-arrow:before' => 'background-color: {{VALUE}};',
				'body:not(.pcdm-enable) {{WRAPPER}} .style-9.penci-border-arrow .inner-arrow:before' => 'background-color: {{VALUE}};'
			),
			'condition' => array( 'heading_title_style' => array( 'style-7', 'style-9' ) ),
		) );
		$this->add_control( 'btitle_shapes_color', array(
			'label'     => __( 'Background Color for Shapes', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => array(
				'{{WRAPPER}} .style-13.pcalign-center .inner-arrow:before,{{WRAPPER}} .style-13.pcalign-right .inner-arrow:before'                                         => 'border-left-color: {{VALUE}};',
				'{{WRAPPER}} .style-13.pcalign-center .inner-arrow:after,{{WRAPPER}} .style-13.pcalign-left .inner-arrow:after'                                            => ' border-right-color: {{VALUE}};',
				'{{WRAPPER}} .style-12 .inner-arrow:before,{{WRAPPER}} .style-12.pcalign-right .inner-arrow:after,{{WRAPPER}} .style-12.pcalign-center .inner-arrow:after' => ' border-bottom-color: {{VALUE}};',
				'{{WRAPPER}} .style-11 .inner-arrow:after,{{WRAPPER}} .style-11 .inner-arrow:before'                                                                       => ' border-top-color: {{VALUE}};',
				'{{WRAPPER}} .penci-border-arrow.style-28'                                                                       => '--pcaccent-cl: {{VALUE}};'
			),
			'condition' => array( 'heading_title_style' => array( 'style-13', 'style-11', 'style-12', 'style-28' ) ),
		) );

		$this->add_control( 'btitle_inshapes_color', array(
			'label'     => __( 'Background Color for Shapes Inside', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => array(
				'{{WRAPPER}} .style-11 .inner-arrow:after,{{WRAPPER}} .style-11 .inner-arrow:before' => ' border-right-color: {{VALUE}};'
			),
			'condition' => array( 'heading_title_style' => array( 'style-11' ) ),
		) );

		$this->add_control( 'bgstyle15_color', array(
			'label'       => __( 'Background Color for Icon', 'soledad' ),
			'type'        => Controls_Manager::COLOR,
			'default'     => '',
			'description' => __( 'For Icon on Style 15', 'soledad' ),
			'selectors'   => array(
				'body:not(.pcdm-enable) {{WRAPPER}} .style-15.penci-border-arrow:before' => 'background-color: {{VALUE}};',
			),
			'condition'   => array( 'heading_title_style' => array( 'style-15' ) ),
		) );
		$this->add_control( 'iconstyle15_color', array(
			'label'       => __( 'Icon Color', 'soledad' ),
			'type'        => Controls_Manager::COLOR,
			'default'     => '',
			'description' => __( 'For Icon on Style 15', 'soledad' ),
			'selectors'   => array(
				'{{WRAPPER}} .style-15.penci-border-arrow:after' => 'color: {{VALUE}};',
			),
			'condition'   => array( 'heading_title_style' => array( 'style-15' ) ),
		) );
		$this->add_responsive_control( 'iconstyle15_size', array(
			'label'       => __( 'Custom Font Size for Icon', 'soledad' ),
			'type'        => Controls_Manager::SLIDER,
			'description' => __( 'For Icon on Style 15', 'soledad' ),
			'range'       => array( 'px' => array( 'min' => 0, 'max' => 200, ) ),
			'selectors'   => array(
				'{{WRAPPER}} .style-15.penci-border-arrow:after' => 'font-size: {{SIZE}}px;',
			),
			'condition'   => array( 'heading_title_style' => array( 'style-15' ) ),
		) );
		$this->add_control( 'lines_color', array(
			'label'       => __( 'Color for Lines', 'soledad' ),
			'type'        => Controls_Manager::COLOR,
			'default'     => '',
			'description' => __( 'For Lines on Styles 18, 19, 20', 'soledad' ),
			'selectors'   => array(
				'{{WRAPPER}} .style-18.penci-border-arrow:after' => 'color: {{VALUE}}; background-image: linear-gradient( -45deg, transparent, transparent 30%, {{VALUE}} 30%, {{VALUE}} 50%, transparent 50%, transparent 80%, {{VALUE}} 80%);',
				'{{WRAPPER}} .style-19.penci-border-arrow:after' => 'background-image: linear-gradient( -90deg, transparent, transparent 30%, {{VALUE}} 30%, {{VALUE}} 50%, transparent 50%, transparent 80%, {{VALUE}} 80%);',
				'{{WRAPPER}} .style-20.penci-border-arrow:after' => 'background-image: linear-gradient( 0deg, transparent, transparent 30%, {{VALUE}} 30%, {{VALUE}} 50%, transparent 50%, transparent 80%, {{VALUE}} 80%);',
			),
			'condition'   => array(
				'heading_title_style' => array(
					'style-18',
					'style-18 style-19',
					'style-18 style-20'
				)
			),
		) );

		$this->add_control( 'btitle_bgcolor', array(
			'label'     => __( 'Background Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => array(
				'body:not(.pcdm-enable) {{WRAPPER}} .penci-homepage-title.style-27 .inner-arrow span span, body:not(.pcdm-enable) {{WRAPPER}} .penci-homepage-title.style-22 .inner-arrow > span, body:not(.pcdm-enable) {{WRAPPER}} .penci-homepage-title.style-26'                                                                           => '--pcbg-cl: {{VALUE}};',
				'body:not(.pcdm-enable) {{WRAPPER}} .style-2.penci-border-arrow:after'                                                                                                                                                                                                                                                         => 'border-color: transparent;border-top-color: {{VALUE}};',
				'body:not(.pcdm-enable) {{WRAPPER}} .style-14 .inner-arrow:before,{{WRAPPER}} .style-11 .inner-arrow,' . '{{WRAPPER}} .style-12 .inner-arrow,{{WRAPPER}} .style-13 .inner-arrow,{{WRAPPER}} .style-15 .inner-arrow,' . '{{WRAPPER}} .penci-border-arrow .inner-arrow, {{WRAPPER}} .penci-homepage-title.style-25 .inner-arrow' => 'background-color: {{VALUE}};',
			)
		) );
		$this->add_control( 'btitle_outer_bgcolor', array(
			'label'     => __( 'Background Outer Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => array(
				'body:not(.pcdm-enable) {{WRAPPER}} .penci-border-arrow:after'   => 'background-color: {{VALUE}};',
				'{{WRAPPER}} .penci-homepage-title.style-25 .inner-arrow > span' => '--pcheading-cl: {{VALUE}};',
			),
			'condition' => array( 'heading_title_style!' => array( 'style-28' ) ),
		) );

		$this->add_control( 'btitle_style9_bgimg', array(
			'label'     => __( 'Select Background Image for Style 9', 'soledad' ),
			'type'      => Controls_Manager::MEDIA,
			'default'   => array( 'id' => '', 'url' => '' ),
			'selectors' => array( '{{WRAPPER}} .style-8.penci-border-arrow .inner-arrow' => 'background-image: url("{{URL}}");' ),
			'condition' => array( 'heading_title_style' => 'style-8' ),
		) );

		$this->add_control( 'btitle_style9_repeat', array(
			'label'     => esc_html__( 'Background Image Repeat', 'soledad' ),
			'type'      => Controls_Manager::SELECT,
			'options'   => array(
				'no-repeat' => esc_html__( 'No Repeat', 'soledad' ),
				'repeat'    => esc_html__( 'Repeat', 'soledad' ),
				'repeat-x'  => esc_html__( 'Repeat X', 'soledad' ),
				'repeat-y'  => esc_html__( 'Repeat Y', 'soledad' ),
			),
			'condition' => array( 'heading_title_style' => 'style-8' ),
			'default'   => 'no-repeat',
			'selectors' => array( '{{WRAPPER}} .style-8.penci-border-arrow .inner-arrow' => 'background-repeat: {{VALUE}};' ),
		) );

		$this->add_control( 'btitle_style9_size', array(
			'label'     => esc_html__( 'Background Image Size', 'soledad' ),
			'type'      => Controls_Manager::SELECT,
			'options'   => array(
				'auto 100%' => esc_html__( 'With Auto - Height 100%', 'soledad' ),
				'100% auto' => esc_html__( 'Width 100% - Height Auto', 'soledad' ),
				'cover'     => esc_html__( 'Cover', 'soledad' ),
				'contain'   => esc_html__( 'Contain', 'soledad' ),
				'auto'      => esc_html__( 'Orininal Size', 'soledad' ),
			),
			'condition' => array( 'heading_title_style' => 'style-8' ),
			'default'   => 'auto 100%',
			'selectors' => array( '{{WRAPPER}} .style-8.penci-border-arrow .inner-arrow' => 'background-size: {{VALUE}};' ),
		) );

		$this->add_control( 'btitle_style9_pos', array(
			'label'     => esc_html__( 'Background Image Position', 'soledad' ),
			'type'      => Controls_Manager::SELECT,
			'options'   => array(
				'left top'      => esc_html__( 'Left Top', 'soledad' ),
				'left center'   => esc_html__( 'Left Center', 'soledad' ),
				'left bottom'   => esc_html__( 'Left Bottom', 'soledad' ),
				'right top'     => esc_html__( 'Right Top', 'soledad' ),
				'right center'  => esc_html__( 'Right Center', 'soledad' ),
				'right bottom'  => esc_html__( 'Right Bottom', 'soledad' ),
				'center top'    => esc_html__( 'Center Top', 'soledad' ),
				'center center' => esc_html__( 'Center', 'soledad' ),
				'center bottom' => esc_html__( 'Center Bottom', 'soledad' ),
			),
			'condition' => array( 'heading_title_style' => 'style-8' ),
			'default'   => 'left top',
			'selectors' => array( '{{WRAPPER}} .style-8.penci-border-arrow .inner-arrow' => 'background-position: {{VALUE}};' ),
		) );

		$this->add_group_control( Group_Control_Typography::get_type(), array(
			'name'     => 'btitle_typo',
			'label'    => __( 'Block Title Typography', 'soledad' ),
			'selector' => '{{WRAPPER}} .penci-border-arrow .inner-arrow',
		) );

		$this->add_control( 'block_title_icolor', array(
			'label'     => __( 'Icon Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => array(
				'{{WRAPPER}} .inner-arrow i'                                  => 'color: {{VALUE}};',
				'{{WRAPPER}} .inner-arrow svg, {{WRAPPER}} .inner-arrow path' => 'fill: {{VALUE}};',
			),
			'condition' => array(
				'add_title_icon' => 'yes'
			),
		) );

		$this->add_control( 'block_title_ihcolor', array(
			'label'     => __( 'Icon Hover Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => array(
				'{{WRAPPER}} .inner-arrow > span:hover i'                                               => 'color: {{VALUE}};',
				'{{WRAPPER}} .inner-arrow > span:hover svg, {{WRAPPER}} .inner-arrow > span:hover path' => 'fill: {{VALUE}};',
			),
			'condition' => array(
				'add_title_icon' => 'yes'
			),
		) );

		$this->end_controls_section();
	}

	public function register_block_icons() {
		$this->add_control( 'icon', array(
			'label' => esc_html__( 'Icon', 'soledad' ),
			'type'  => Controls_Manager::ICONS,
		) );

		$this->add_control( 'icon_position', array(
			'label'   => esc_html__( 'Icon position', 'soledad' ),
			'type'    => Controls_Manager::SELECT,
			'options' => array(
				'right' => esc_html__( 'Right', 'soledad' ),
				'left'  => esc_html__( 'Left', 'soledad' ),
			),
			'default' => 'right',
		) );
	}

	public function register_product_style() {
		/**
		 * Products design settings.
		 */
		$this->start_controls_section( 'products_design_style_product', [
			'label' => esc_html__( 'Products Style', 'soledad' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_control( 'product_title_color', array(
			'label'     => __( 'Product Title Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => array(
				'{{WRAPPER}} ul.products .penci-soledad-product .penci-product-loop-title h3'   => 'color: {{VALUE}};',
				'{{WRAPPER}} ul.products .penci-soledad-product .penci-product-loop-title h3 a' => 'color: {{VALUE}};',
			),
		) );

		$this->add_group_control( Group_Control_Typography::get_type(), array(
			'name'     => 'product_title_typo',
			'label'    => __( 'Product Title Font', 'soledad' ),
			'selector' => '{{WRAPPER}} ul.products .penci-soledad-product .penci-product-loop-title h3'
		) );

		$this->add_group_control( Group_Control_Typography::get_type(), array(
			'name'     => 'product_cat_typo',
			'label'    => __( 'Product Category Typo', 'soledad' ),
			'selector' => '{{WRAPPER}} ul.products li.product .penci-product-cats a'
		) );

		$this->add_control( 'product_cat_color', array(
			'label'     => __( 'Product Category Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => array(
				'{{WRAPPER}} ul.products li.product .penci-product-cats'   => 'color: {{VALUE}};',
				'{{WRAPPER}} ul.products li.product .penci-product-cats a' => 'color: {{VALUE}};',
			),
		) );

		$this->add_control( 'product_cat_hover_color', array(
			'label'     => __( 'Product Category Hover Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => array(
				'{{WRAPPER}} ul.products li.product .penci-product-cats a:hover' => 'color: {{VALUE}};',
			),
		) );

		$this->add_control( 'product_item_inner_color', array(
			'label'     => __( 'Product Inner Background Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => array(
				'body:not(.pcdm-enable) {{WRAPPER}} .products.product-style-6 .penci-product-loop-inner-content' => 'background-color: {{VALUE}};',
			),
		) );

		$this->add_control( 'product_item_button_bg_color', array(
			'label'     => __( 'Product Buttons Background Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => array(
				'body:not(.pcdm-enable) {{WRAPPER}} .products .penci-product-loop-button .button'                                                                             => 'background-color: {{VALUE}};',
				'body:not(.pcdm-enable) {{WRAPPER}} .products.icon-style-group:not(.product-style-7):not(.product-style-5) .penci-soledad-product .penci-product-loop-button' => 'background-color: {{VALUE}};',
			),
		) );

		$this->add_control( 'product_item_button_bg_hover_color', array(
			'label'     => __( 'Product Buttons Hover Background Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => array(
				'body:not(.pcdm-enable) {{WRAPPER}} .products .penci-product-loop-button .button:hover'                                                                  => 'background-color: {{VALUE}};',
				'body:not(.pcdm-enable) {{WRAPPER}} ul.products:not(.product-style-7):not(.product-style-5) .penci-soledad-product .penci-product-loop-button > a:hover' => 'background-color: {{VALUE}};',
			),
		) );

		$this->add_control( 'product_item_button_txt_color', array(
			'label'     => __( 'Product Buttons Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => array(
				'{{WRAPPER}} .products .penci-product-loop-button .button:before'                                                               => 'color: {{VALUE}};',
				'{{WRAPPER}} .products.product-style-7.icon-style-round .penci-product-loop-buttons .penci-product-loop-button a.button:before' => 'color: {{VALUE}};',
			),
		) );

		$this->add_control( 'product_item_button_txt_hover_color', array(
			'label'     => __( 'Product Buttons Hover Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => array(
				'{{WRAPPER}} .products .penci-product-loop-button .button:hover:before'                                                               => 'color: {{VALUE}};',
				'{{WRAPPER}} .products.product-style-7.icon-style-round .penci-product-loop-buttons .penci-product-loop-button a.button:hover:before' => 'color: {{VALUE}};',
			),
		) );

		$this->add_group_control( Group_Control_Typography::get_type(), array(
			'name'     => 'product_item_button_txt_typo',
			'label'    => __( 'Add to Cart Typo', 'soledad' ),
			'selector' => '{{WRAPPER}} .products.product-style-5 .penci-product-loop-top .penci-product-loop-extra-buttons .button,
					{{WRAPPER}} .products.product-style-3 .penci-soledad-product .penci-product-loop-image a.button,
					{{WRAPPER}} .products.product-style-3 .penci-soledad-product a.add_to_cart_button,
					{{WRAPPER}} .products.product-style-4 .penci-product-loop-title .button
					',
		) );

		$this->add_control( 'product_item_button_txt_color_5', array(
			'label'     => __( 'Add to cart button color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => array(
				'{{WRAPPER}} .products.product-style-5 .penci-product-loop-top .penci-product-loop-extra-buttons .button' => 'color: {{VALUE}};',
				'{{WRAPPER}} .products.product-style-3 .penci-soledad-product .penci-product-loop-image a.button'         => 'color: {{VALUE}};',
				'{{WRAPPER}} .products.product-style-3 .penci-soledad-product a.add_to_cart_button'                       => 'color: {{VALUE}};',
				'{{WRAPPER}} .products.product-style-4 .penci-product-loop-title .button'                                 => 'color: {{VALUE}};',
			),
			'condition' => [
				'product_style' => [ 'style-5', 'style-3', 'style-4' ],
			],
		) );

		$this->add_control( 'product_item_button_txt_hover_color_5', array(
			'label'     => __( 'Add to cart button hover color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => array(
				'{{WRAPPER}} .products.product-style-5 .penci-product-loop-extra-buttons .button:hover'                 => 'color: {{VALUE}};',
				'{{WRAPPER}} .products.product-style-3 .penci-soledad-product .penci-product-loop-image a.button:hover' => 'color: {{VALUE}};',
				'{{WRAPPER}} .products.product-style-3 .penci-soledad-product a.add_to_cart_button:hover'               => 'color: {{VALUE}};',
				'{{WRAPPER}} .products.product-style-4 .penci-product-loop-title .button:hover'                         => 'color: {{VALUE}};',
			),
			'condition' => [
				'product_style' => [ 'style-5', 'style-3', 'style-4' ],
			],
		) );

		$this->add_control( 'product_item_button_bg_color_5', array(
			'label'     => __( 'Add to cart button background color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => array(
				'body:not(.pcdm-enable) {{WRAPPER}} .products.product-style-5 .penci-product-loop-top .penci-product-loop-extra-buttons .button' => 'background-color: {{VALUE}};',
				'body:not(.pcdm-enable) {{WRAPPER}} .products.product-style-3 .penci-soledad-product .penci-product-loop-image a.button'         => 'background-color: {{VALUE}};',
				'body:not(.pcdm-enable) {{WRAPPER}} .products.product-style-3 .penci-soledad-product a.add_to_cart_button'                       => 'background-color: {{VALUE}};',
				'body:not(.pcdm-enable) {{WRAPPER}} .products.product-style-4 .penci-product-loop-title .button'                                 => 'background-color: {{VALUE}};',
			),
			'condition' => [
				'product_style' => [ 'style-5', 'style-3', 'style-4' ],
			],
		) );

		$this->add_control( 'product_item_button_bg_hover_color_5', array(
			'label'     => __( 'Add to cart button hover background color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => array(
				'body:not(.pcdm-enable) {{WRAPPER}} .products.product-style-5 .penci-product-loop-top .penci-product-loop-extra-buttons .button:hover' => 'background-color: {{VALUE}};',
				'body:not(.pcdm-enable) {{WRAPPER}} .products.product-style-3 .penci-soledad-product .penci-product-loop-image a.button:hover'         => 'background-color: {{VALUE}};',
				'body:not(.pcdm-enable) {{WRAPPER}} .products.product-style-3 .penci-soledad-product a.add_to_cart_button:hover'                       => 'background-color: {{VALUE}};',
				'body:not(.pcdm-enable) {{WRAPPER}} .products.product-style-4 .penci-product-loop-title .button:hover'                                 => 'background-color: {{VALUE}};',
			),
			'condition' => [
				'product_style' => [ 'style-5', 'style-3', 'style-4' ],
			],
		) );

		$this->add_control( 'product_item_button_border_color_5', array(
			'label'     => __( 'Add to cart button border color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => array(
				'{{WRAPPER}} .products.product-style-5 .penci-product-loop-top .penci-product-loop-extra-buttons .button' => 'border-color: {{VALUE}};',
			),
			'condition' => [
				'product_style' => 'style-5',
			],
		) );

		$this->add_control( 'product_item_button_border_hover_color_5', array(
			'label'     => __( 'Add to cart button hover border color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => array(
				'{{WRAPPER}} .products.product-style-5 .penci-product-loop-top .penci-product-loop-extra-buttons .button:hover' => 'border-color: {{VALUE}};',
			),
			'condition' => [
				'product_style' => 'style-5',
			],
		) );

		$this->add_control( 'product_item_inner_align', array(
			'label'     => __( 'Product Inner Text Align', 'soledad' ),
			'type'      => Controls_Manager::SELECT,
			'default'   => '',
			'options'   => array(
				'left'   => __( 'Left', 'soledad' ),
				'center' => __( 'Center', 'soledad' ),
				'right'  => __( 'Right', 'soledad' ),
			),
			'selectors' => array(
				'{{WRAPPER}} .products .penci-soledad-product .penci-product-loop-inner-content' => 'text-align: {{VALUE}};',
				'{{WRAPPER}} .products .penci-soledad-product .penci-product-loop-title'         => 'text-align: {{VALUE}};',
				'{{WRAPPER}} .products .penci-soledad-product .woocommerce-loop-product__title'  => 'text-align: {{VALUE}};',
				'{{WRAPPER}} .products .penci-soledad-product .penci-product-cats'               => 'text-align: {{VALUE}};',
				'{{WRAPPER}} .products .penci-soledad-product .price'                            => 'text-align: {{VALUE}};',
				'{{WRAPPER}} .products .penci-soledad-product .penci-swatches-list'              => 'justify-content: {{VALUE}};',
				'{{WRAPPER}} .products.product-style-4 .penci-product-loop-title .button'        => 'justify-content: {{VALUE}};',
			),
		) );

		$this->add_control( 'product_price_color', array(
			'label'     => __( 'Product Price Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => array(
				'{{WRAPPER}} ul.products li.product .price' => 'color: {{VALUE}};',
			),
		) );

		$this->add_group_control( Group_Control_Typography::get_type(), array(
			'name'     => 'product_price_typo',
			'label'    => __( 'Product Price Typo', 'soledad' ),
			'selector' => '{{WRAPPER}} ul.products li.product .price'
		) );

		$this->add_control( 'product_item_seperate_border_color', array(
			'label'     => __( 'Product Item List Separate Border Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => array(
				'{{WRAPPER}} .products.product-list .penci-soledad-product .penci-product-loop-inner-content' => 'border-bottom-color: {{VALUE}};',
			),
			'condition' => [
				'layout' => [ 'list' ],
			],
		) );

		$this->end_controls_section();

		/**
		 * Stock Progress Style
		 */

		$this->start_controls_section( 'products_design_stock_progress', [
			'label'     => esc_html__( 'Stock Progress Style', 'soledad' ),
			'tab'       => Controls_Manager::TAB_STYLE,
			'condition' => [ 'stock_progress_bar' => '1' ],
		] );

		$this->add_control( 'products_design_stock_progress_bg_color', array(
			'label'     => __( 'Stock Bar Background Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => array(
				'body:not(.pcdm-enable) {{WRAPPER}} .penci-stock-progress-bar .progress-area' => 'background-color: {{VALUE}};',
			),
		) );

		$this->add_control( 'products_design_stock_progress_bg_active_color', array(
			'label'     => __( 'Stock Bar Background Active Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => array(
				'body:not(.pcdm-enable) {{WRAPPER}} .penci-stock-progress-bar .progress-bar' => 'background-color: {{VALUE}};',
			),
		) );

		$this->add_control( 'products_design_stock_progress_height', array(
			'label'     => __( 'Stock Bar Height', 'soledad' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => array( 'px' => array( 'min' => 0, 'max' => 100, ) ),
			'selectors' => array(
				'{{WRAPPER}} .penci-stock-progress-bar .progress-area, {{WRAPPER}} .penci-stock-progress-bar .progress-bar' => 'height: {{SIZE}}px;',
			),
		) );

		$this->end_controls_section();

		/**
		 * Pagination Style
		 */

		$this->start_controls_section( 'products_design_style_pagination', [
			'label' => esc_html__( 'Pagination Style', 'soledad' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_control( 'pagination_color', array(
			'label'     => __( 'Pagination Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => array(
				'{{WRAPPER}} nav.woocommerce-pagination ul li a' => 'color: {{VALUE}};',
			),
		) );

		$this->add_control( 'pagination_bcolor', array(
			'label'     => __( 'Pagination Border Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => array(
				'{{WRAPPER}} nav.woocommerce-pagination ul li a'                                                                      => 'border-color: {{VALUE}};',
				'{{WRAPPER}} .penci-owl-carousel-slider .penci-owl-dot span, {{WRAPPER}} .penci-related-carousel .penci-owl-dot span' => 'border-color: {{VALUE}};',
				'{{WRAPPER}} .penci-woo-page-container .page-load-button .button'                                                     => 'border-color: {{VALUE}};',
			),
		) );

		$this->add_control( 'pagination_bgcolor', array(
			'label'     => __( 'Pagination Background Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => array(
				'body:not(.pcdm-enable) {{WRAPPER}} nav.woocommerce-pagination ul li a'                                                                      => 'background-color: {{VALUE}};',
				'body:not(.pcdm-enable) {{WRAPPER}} .penci-owl-carousel-slider .penci-owl-dot span, {{WRAPPER}} .penci-related-carousel .penci-owl-dot span' => 'background-color: {{VALUE}};'
			),
		) );

		$this->add_control( 'pagination_hcolor', array(
			'label'     => __( 'Pagination Hover Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => array(
				'{{WRAPPER}} nav.woocommerce-pagination ul li a:hover' => 'color: {{VALUE}};',
			),
		) );

		$this->add_control( 'pagination_hbcolor', array(
			'label'     => __( 'Pagination Border Hover Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => array(
				'{{WRAPPER}} nav.woocommerce-pagination ul li a:hover'                                                                            => 'border-color: {{VALUE}};',
				'{{WRAPPER}} .penci-owl-carousel-slider .penci-owl-dot span:hover, {{WRAPPER}} .penci-related-carousel .penci-owl-dot span:hover' => 'border-color: {{VALUE}};',
				'{{WRAPPER}} .penci-woo-page-container .page-load-button .button:hover'                                                           => 'border-color: {{VALUE}};',
			),
		) );

		$this->add_control( 'pagination_cicolor', array(
			'label'     => __( 'Pagination Current Item Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => array(
				'body:not(.pcdm-enable) {{WRAPPER}} nav.woocommerce-pagination ul li span.current'                                                                                                => 'color: {{VALUE}};',
				'body:not(.pcdm-enable) {{WRAPPER}} .penci-owl-carousel-slider .penci-owl-dot.active span, body:not(.pcdm-enable) {{WRAPPER}} .penci-related-carousel .penci-owl-dot.active span' => 'background-color: {{VALUE}};',
			),
		) );

		$this->add_control( 'pagination_bicolor', array(
			'label'     => __( 'Pagination Current Item Border Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => array(
				'{{WRAPPER}} nav.woocommerce-pagination ul li span.current'                                                                         => 'border-color: {{VALUE}};',
				'{{WRAPPER}} .penci-owl-carousel-slider .penci-owl-dot.active span, {{WRAPPER}} .penci-related-carousel .penci-owl-dot.active span' => 'border-color: {{VALUE}};',
			),
		) );

		$this->add_control( 'pagination_bgicolor', array(
			'label'     => __( 'Pagination Current Item Background Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => array(
				'body:not(.pcdm-enable) {{WRAPPER}} nav.woocommerce-pagination ul li span.current'                                                                         => 'background-color: {{VALUE}};',
				'body:not(.pcdm-enable) {{WRAPPER}} .penci-owl-carousel-slider .penci-owl-dot.active span, {{WRAPPER}} .penci-related-carousel .penci-owl-dot.active span' => 'background-color: {{VALUE}};',
			),
		) );

		$this->add_control( 'pagination_more_bgcolor', array(
			'label'     => __( 'Pagination View More Background Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => array(
				'body:not(.pcdm-enable) {{WRAPPER}} .penci-woo-page-container .page-load-button .button' => 'background-color: {{VALUE}};',
			),
		) );

		$this->add_control( 'pagination_more_bghcolor', array(
			'label'     => __( 'Pagination View More Background Hover Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => array(
				'body:not(.pcdm-enable) {{WRAPPER}} .penci-woo-page-container .page-load-button .button:hover' => 'background-color: {{VALUE}};',
			),
		) );

		$this->add_control( 'pagination_more_textcolor', array(
			'label'     => __( 'Pagination View More Text Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => array(
				'{{WRAPPER}} .page-load-button button.button'                     => 'color: {{VALUE}};',
				'{{WRAPPER}} .penci-woo-page-container .page-load-button .button' => 'color: {{VALUE}};',
			),
		) );

		$this->add_control( 'pagination_more_texthcolor', array(
			'label'     => __( 'Pagination View More Hover Text Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => array(
				'{{WRAPPER}} .page-load-button button.button:hover'                     => 'color: {{VALUE}};',
				'{{WRAPPER}} .penci-woo-page-container .page-load-button .button:hover' => 'color: {{VALUE}};',
			),
		) );

		$this->end_controls_section();
	}

	protected function register_custom_post_metas_groups() {
		$this->add_control( 'cspost_meta_heading', array(
			'label'     => __( 'Show Custom Post Metas', 'soledad' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
		) );
		$this->add_control( 'cspost_enable', array(
			'label' => __( 'Showing Custom Post Metas?', 'soledad' ),
			'type'  => Controls_Manager::SWITCHER,
		) );
		$this->add_control( 'cspost_cpost_meta', array(
			'label'     => __( 'Custom Post Meta Keys', 'soledad' ),
			'type'      => Controls_Manager::TEXT,
			'condition' => [ 'cspost_enable' => 'yes' ]
		) );
		$this->add_control( 'cspost_cpost_acf_meta', array(
			'label'       => __( 'Custom Post ACF Meta Keys', 'soledad' ),
			'type'        => Controls_Manager::SELECT2,
			'description' => __( 'You can show your own custom fields easily by using the <a href="https://wordpress.org/plugins/advanced-custom-fields/" target="_blank">Advanced Custom Fields</a> plugin.', 'soledad' ),
			'multiple'    => true,
			'options'     => penci_get_afc_fields(),
			'condition'   => [ 'cspost_enable' => 'yes' ]
		) );
		$this->add_control( 'cspost_cpost_meta_label', array(
			'label'     => __( 'Showing Custom Post Meta Label', 'soledad' ),
			'type'      => Controls_Manager::SWITCHER,
			'condition' => [ 'cspost_enable' => 'yes' ]
		) );
		$this->add_control( 'cspost_cpost_meta_divider', array(
			'label'       => __( 'Custom Divider Between Meta Label & Meta Value', 'soledad' ),
			'label_block' => true,
			'type'        => Controls_Manager::TEXT,
			'value'       => ':',
			'condition'   => [ 'cspost_cpost_meta_label' => 'yes' ]
		) );
	}

	protected function register_query_section_controls( $showmeta = false, $condition = [] ) {

		$section_args = array(
			'label' => __( 'Query', 'soledad' ),
			'tab'   => Controls_Manager::TAB_CONTENT
		);

		if ( !empty( $condition ) ) {
			$section_args['condition'] = $condition;
		}

		$this->start_controls_section( 'section_query', $section_args );

		$this->add_group_control( Penci_Group_Control_Posts::get_type(), array(
			'name' => 'posts'
		) );

		$this->add_control( 'posts_per_page', array(
			'label'     => __( 'Posts Per Page', 'soledad' ),
			'type'      => Controls_Manager::NUMBER,
			'default'   => 6,
			'condition' => array( 'posts_post_type!' => array( 'by_id' ) ),
		) );

		$this->add_control( 'arposts_new', array(
			'label'       => __( 'Always Show Newest Posts', 'soledad' ),
			'type'        => Controls_Manager::SWITCHER,
			'description' => 'This option will be avoid page navigation',
			'condition'   => array( 'posts_post_type' => array( 'current_query' ) ),
		) );

		$this->add_control( 'orderby', array(
			'label'     => __( 'Order By', 'soledad' ),
			'type'      => Controls_Manager::SELECT,
			'default'   => 'date',
			'options'   => array(
				'date'          => __( 'Published Date', 'soledad' ),
				'ID'            => __( 'Post ID', 'soledad' ),
				'modified'      => __( 'Modified Date', 'soledad' ),
				'title'         => __( 'Post Title', 'soledad' ),
				'rand'          => __( 'Random Posts', 'soledad' ),
				'comment_count' => __( 'Comment Count', 'soledad' ),
				'most_liked'    => __( 'Most Liked', 'soledad' ),
				'popular'       => __( 'Most Viewed Posts All Time', 'soledad' ),
				'popular_day'   => __( 'Most Viewed Posts Daily', 'soledad' ),
				'popular7'      => __( 'Most Viewed Posts Once Weekly', 'soledad' ),
				'popular_month' => __( 'Most Viewed Posts Once a Month', 'soledad' ),
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

		Module::add_exclude_controls( $this );

		if ( $showmeta ) {
			$this->register_custom_post_metas_groups();
		}

		$this->add_control( 'query_id', array(
			'label' => __( 'Query ID', 'soledad' ),
			'type'  => Controls_Manager::TEXT,
		) );

		$this->end_controls_section();
	}

	protected function register_penci_bookmark_style_groups() {
		if ( ! defined( 'PENCI_BL_VERSION' ) ) {
			return;
		}
		$this->start_controls_section( 'penci_bf_heading_01', [
			'label' => esc_html__( 'Bookmark Icon', 'soledad' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );
		$this->add_control( 'hide_bookmark_icon', [
			'label'     => esc_html__( 'Hide Bookmark Icon', 'soledad' ),
			'type'      => Controls_Manager::SWITCHER,
			'selectors' => array(
				'{{WRAPPER}} .penci-bf-follow-post-wrapper' => 'display: none !important;',
			),
		] );
		$this->add_responsive_control( 'penci_bf_icon_sizes', array(
			'label'      => __( 'Icon Size', 'soledad' ),
			'type'       => Controls_Manager::SLIDER,
			'range'      => array(
				'px' => array( 'min' => 0, 'max' => 2000 )
			),
			'condition'  => array( 'hide_bookmark_icon!' => 'yes' ),
			'size_units' => array( 'px' ),
			'selectors'  => array(
				'{{WRAPPER}} .penci-bf-follow-post-wrapper .pencibf-following-text:before'         => 'width: {{SIZE}}px;height: {{SIZE}}px;line-height: {{SIZE}}px;',
				'{{WRAPPER}} .penci-bf-follow-post-wrapper.loading .pencibf-following-text:before' => 'width: {{SIZE}}px;height: {{SIZE}}px;',
			),
		) );
		$this->add_responsive_control( 'penci_bf_icon_fsizes', array(
			'label'      => __( 'Icon Font Size', 'soledad' ),
			'type'       => Controls_Manager::SLIDER,
			'range'      => array(
				'px' => array( 'min' => 0, 'max' => 2000 )
			),
			'condition'  => array( 'hide_bookmark_icon!' => 'yes' ),
			'size_units' => array( 'px' ),
			'selectors'  => array(
				'{{WRAPPER}} .penci-bf-follow-post-wrapper .pencibf-following-text:before' => 'font-size: {{SIZE}}px;',
			),
		) );

		$this->add_responsive_control( 'penci_bf_icon_ssizes', array(
			'label'      => __( 'Icon Small Size', 'soledad' ),
			'type'       => Controls_Manager::SLIDER,
			'range'      => array(
				'px' => array( 'min' => 0, 'max' => 2000 )
			),
			'condition'  => array( 'hide_bookmark_icon!' => 'yes' ),
			'size_units' => array( 'px' ),
			'selectors'  => array(
				'{{WRAPPER}} .penci-bf-follow-post-wrapper.pcbf-size-small .pencibf-following-text:before'         => 'width: {{SIZE}}px;height: {{SIZE}}px;line-height: {{SIZE}}px;',
				'{{WRAPPER}} .penci-bf-follow-post-wrapper.pcbf-size-small.loading .pencibf-following-text:before' => 'width: {{SIZE}}px;height: {{SIZE}}px;',
			),
		) );
		$this->add_responsive_control( 'penci_bf_icon_sfsizes', array(
			'label'      => __( 'Icon Small Font Size', 'soledad' ),
			'type'       => Controls_Manager::SLIDER,
			'range'      => array(
				'px' => array( 'min' => 0, 'max' => 2000 )
			),
			'condition'  => array( 'hide_bookmark_icon!' => 'yes' ),
			'size_units' => array( 'px' ),
			'selectors'  => array(
				'{{WRAPPER}} .penci-bf-follow-post-wrapper.pcbf-size-small .pencibf-following-text:before' => 'font-size: {{SIZE}}px;',
			),
		) );

		$this->add_control( 'penci_bf_icon_border_color', array(
			'label'     => __( 'Border Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'condition' => array( 'hide_bookmark_icon!' => 'yes' ),
			'selectors' => array(
				'{{WRAPPER}} .penci-bf-follow-btn-wrapper .pencibf-following-text:before' => 'border-color: {{VALUE}};',
			),
		) );
		$this->add_control( 'penci_bf_icon_icon_color', array(
			'label'     => __( 'Icon Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'condition' => array( 'hide_bookmark_icon!' => 'yes' ),
			'selectors' => array(
				'{{WRAPPER}} .penci-bf-follow-btn-wrapper .pencibf-following-text:before' => 'color: {{VALUE}};',
			),
		) );
		$this->add_control( 'penci_bf_icon_bg_color', array(
			'label'     => __( 'Background Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'condition' => array( 'hide_bookmark_icon!' => 'yes' ),
			'selectors' => array(
				'{{WRAPPER}} .penci-bf-follow-btn-wrapper .pencibf-following-text:before' => 'background-color: {{VALUE}};',
			),
		) );
		// hover
		$this->add_control( 'penci_bf_icon_border_hvcolor', array(
			'label'     => __( 'Border Hover Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'condition' => array( 'hide_bookmark_icon!' => 'yes' ),
			'selectors' => array(
				'{{WRAPPER}} .penci-bf-follow-btn-wrapper .pencibf-following-text:hover:before' => 'border-color: {{VALUE}};',
			),
		) );
		$this->add_control( 'penci_bf_icon_icon_hvcolor', array(
			'label'     => __( 'Icon Hover Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'condition' => array( 'hide_bookmark_icon!' => 'yes' ),
			'selectors' => array(
				'{{WRAPPER}} .penci-bf-follow-btn-wrapper .pencibf-following-text:hover:before' => 'color: {{VALUE}};',
			),
		) );
		$this->add_control( 'penci_bf_icon_bg_hvcolor', array(
			'label'     => __( 'Background Hover Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'condition' => array( 'hide_bookmark_icon!' => 'yes' ),
			'selectors' => array(
				'{{WRAPPER}} .penci-bf-follow-btn-wrapper .pencibf-following-text:hover:before' => 'background-color: {{VALUE}};',
			),
		) );
		//selected
		$this->add_control( 'penci_bf_icon_border_svcolor', array(
			'label'     => __( 'Bookmarked Border Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'condition' => array( 'hide_bookmark_icon!' => 'yes' ),
			'selectors' => array(
				'{{WRAPPER}} .penci-bf-follow-btn-wrapper .penci-bf-following-button .pencibf-following-text:before' => 'border-color: {{VALUE}};',
			),
		) );
		$this->add_control( 'penci_bf_icon_icon_svcolor', array(
			'label'     => __( 'Bookmarked Icon Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'condition' => array( 'hide_bookmark_icon!' => 'yes' ),
			'selectors' => array(
				'{{WRAPPER}} .penci-bf-follow-btn-wrapper .penci-bf-following-button .pencibf-following-text:before' => 'color: {{VALUE}};',
			),
		) );
		$this->add_control( 'penci_bf_icon_bg_svcolor', array(
			'label'     => __( 'Bookmarked Background Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'condition' => array( 'hide_bookmark_icon!' => 'yes' ),
			'selectors' => array(
				'{{WRAPPER}} .penci-bf-follow-btn-wrapper .penci-bf-following-button .pencibf-following-text:before' => 'background-color: {{VALUE}};',
			),
		) );

		$this->end_controls_section();
	}

	protected function register_paywall_premium_heading_style_groups() {

		if ( ! defined( 'PENCI_PAYWALL' ) ) {
			return false;
		}

		$this->start_controls_section( 'paywall_heading_01', [
			'label' => esc_html__( 'Paywall Premium Post Title', 'soledad' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );
		$this->add_control( 'paywall_heading_text_style', array(
			'label'   => __( 'Style', 'soledad' ),
			'type'    => Controls_Manager::SELECT,
			'default' => get_theme_mod( 'pencipw_premium_heading_style', 'text' ),
			'options' => array(
				'text' => __( 'Text', 'soledad' ),
				'btn'  => __( 'Button', 'soledad' )
			),
		) );
		$this->add_group_control( Group_Control_Typography::get_type(), array(
			'name'     => 'paywall_heading_text_typo',
			'label'    => __( 'Heading Prefix Text Typo', 'soledad' ),
			'selector' => '{{WRAPPER}} .pc-premium-post'
		) );
		$this->add_control( 'paywall_heading_text', array(
			'label'     => __( 'Heading Prefix Text', 'soledad' ),
			'type'      => Controls_Manager::TEXT,
			'default'   => 'Premium: ',
			'selectors' => array(
				'{{WRAPPER}} span.pc-premium-post:before' => 'content: "{{VALUE}}";',
			),
		) );
		$this->add_control( 'paywall_heading_text_cl', array(
			'label'     => __( 'Heading Prefix Text Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => array(
				'{{WRAPPER}} span.pc-premium-post' => 'color: {{VALUE}};',
			),
		) );
		$this->add_control( 'paywall_heading_text_bgcl', array(
			'label'     => __( 'Heading Prefix Text Background Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => array(
				'{{WRAPPER}} span.pc-premium-post.btn' => 'background-color: {{VALUE}};',
			),
			'condition' => array( 'paywall_heading_text_style' => array( 'btn' ) ),
		) );
		$this->add_control( 'paywall_heading_text_pd', array(
			'label'     => __( 'Heading Prefix Text Padding', 'soledad' ),
			'type'      => Controls_Manager::DIMENSIONS,
			'selectors' => array(
				'{{WRAPPER}} span.pc-premium-post' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			),
		) );
		$this->add_control( 'paywall_heading_text_mg', array(
			'label'     => __( 'Heading Prefix Text Margin', 'soledad' ),
			'type'      => Controls_Manager::DIMENSIONS,
			'selectors' => array(
				'{{WRAPPER}} span.pc-premium-post' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			),
		) );
		$this->end_controls_section();
	}
}
