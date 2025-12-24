<?php

namespace PenciSoledadElementor\Modules\PenciAdvancedList\Widgets;


use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;
use Elementor\Repeater;
use PenciSoledadElementor\Base\Base_Widget;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Direct access not allowed.
}

/**
 * Elementor widget that inserts an embeddable content into the page, from any given URL.
 *
 * @since 1.0.0
 */
class PenciAdvancedList extends Base_Widget {
	/**
	 * Get widget name.
	 *
	 * @return string Widget name.
	 * @since 1.0.0
	 * @access public
	 *
	 */
	public function get_name() {
		return 'penci-advanced-list';
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
		return penci_get_theme_name( 'Penci' ) . ' ' . esc_html__( ' Advanced List', 'soledad' );
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
		return 'eicon-editor-list-ol';
	}

	/**
	 * Get widget categories.
	 *
	 * @return array Widget categories.
	 * @since 1.0.0
	 * @access public
	 *
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
		 * Content tab
		 */

		/**
		 * General style
		 */
		$this->start_controls_section(
			'general_style_section',
			[
				'label' => esc_html__( 'General', 'soledad' ),
			]
		);

		$this->add_control(
			'general_label_style', array(
				'label'   => __( 'General Label Style:', 'soledad' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'style-1',
				'options' => array(
					'style-1' => esc_html__( 'Style 1', 'soledad' ),
					'style-2' => esc_html__( 'Style 2', 'soledad' ),
					'style-3' => esc_html__( 'Style 3', 'soledad' ),
					'style-4' => esc_html__( 'Style 4', 'soledad' ),
				)
			)
		);

		$this->add_control(
			'general_heading_style', array(
				'label'   => __( 'Heading Title Style:', 'soledad' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'style-1',
				'options' => array(
					'style-1' => esc_html__( 'Style 1', 'soledad' ),
					'style-2' => esc_html__( 'Style 2', 'soledad' ),
					'style-3' => esc_html__( 'Style 3', 'soledad' ),
				)
			)
		);

		$this->add_control(
			'item_col', array(
				'label'   => __( 'Split list menu items in columns?', 'soledad' ),
				'type'    => Controls_Manager::SELECT,
				'default' => '1',
				'options' => array(
					'1' => esc_html__( 'No - Keep in 1 Column', 'soledad' ),
					'2' => esc_html__( '2 Columns', 'soledad' ),
					'3' => esc_html__( '3 Columns', 'soledad' ),
				)
			)
		);

		$this->add_control(
			'item_col_space',
			[
				'label'     => esc_html__( 'Spacing Between Columns', 'soledad' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array( 'px' => array( 'min' => 0, 'max' => 200, ) ),
				'condition' => [ 'item_col!' => '1' ],
				'selectors' => array(
					'{{WRAPPER}} .mega-menu-list' => '--pcmega-colspace: {{SIZE}}px',
				),
			]
		);

		$this->add_control(
			'menu_title_spacing',
			[
				'label'     => esc_html__( 'Spacing Between Title and List', 'soledad' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array( 'px' => array( 'min' => 0, 'max' => 200, ) ),
				'selectors' => array(
					'{{WRAPPER}} .mega-menu-list li ul' => 'margin-top: {{SIZE}}px',
				),
			]
		);

		$this->add_control(
			'menu_list_spacing',
			[
				'label'     => esc_html__( 'Spacing Between Item Lists', 'soledad' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array( 'px' => array( 'min' => 0, 'max' => 200, ) ),
				'selectors' => array(
					'{{WRAPPER}} .mega-menu-list > li > ul li' => 'margin-bottom: {{SIZE}}px',
				),
			]
		);

		$this->end_controls_section();

		/**
		 * General settings
		 */
		$this->start_controls_section(
			'general_section',
			[
				'label' => esc_html__( 'Custom List Items', 'soledad' ),
			]
		);

		$this->start_controls_tabs( 'extra_menu_tabs' );

		$this->start_controls_tab(
			'link_tab',
			[
				'label' => esc_html__( 'Link', 'soledad' ),
			]
		);

		$this->add_control(
			'title',
			[
				'label'   => esc_html__( 'Title', 'soledad' ),
				'type'    => Controls_Manager::TEXT,
				'default' => 'Menu parent item',
			]
		);

		$this->add_control(
			'link',
			[
				'label'   => esc_html__( 'Link', 'soledad' ),
				'type'    => Controls_Manager::URL,
				'default' => [
					'url'         => '#',
					'is_external' => false,
					'nofollow'    => false,
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'label_tab',
			[
				'label' => esc_html__( 'Label', 'soledad' ),
			]
		);

		$this->add_control(
			'label_style', array(
				'label'   => __( 'Label Style:', 'soledad' ),
				'type'    => Controls_Manager::SELECT,
				'default' => '',
				'options' => array(
					''        => esc_html__( 'Default From General', 'soledad' ),
					'style-1' => esc_html__( 'Style 1', 'soledad' ),
					'style-2' => esc_html__( 'Style 2', 'soledad' ),
					'style-3' => esc_html__( 'Style 3', 'soledad' ),
					'style-4' => esc_html__( 'Style 4', 'soledad' ),
				)
			)
		);

		$this->add_control(
			'label',
			[
				'label' => esc_html__( 'Label text (optional)', 'soledad' ),
				'type'  => Controls_Manager::TEXT,
			]
		);

		$this->add_control(
			'label_color',
			[
				'label'     => esc_html__( 'Label Background Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .mega-menu-list > li > a .menu-label'        => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .mega-menu-list > li > a .menu-label:before' => 'border-color: {{VALUE}}',
				),
			]
		);

		$this->add_control(
			'label_text_color',
			[
				'label'     => esc_html__( 'Label Text Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .mega-menu-list > li > a .menu-label' => 'color: {{VALUE}}',
				),
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'image_tab',
			[
				'label' => esc_html__( 'Image', 'soledad' ),
			]
		);

		$this->add_control(
			'image',
			[
				'label' => esc_html__( 'Choose image', 'soledad' ),
				'type'  => Controls_Manager::MEDIA,
			]
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name'      => 'image',
				'default'   => 'thumbnail',
				'separator' => 'none',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$repeater = new Repeater();

		$repeater->start_controls_tabs( 'extra_menu_tabs' );

		$repeater->start_controls_tab(
			'link_tab',
			[
				'label' => esc_html__( 'Link', 'soledad' ),
			]
		);

		$repeater->add_control(
			'title',
			[
				'label'   => esc_html__( 'Title', 'soledad' ),
				'type'    => Controls_Manager::TEXT,
				'default' => 'Menu child item',
			]
		);

		$repeater->add_control(
			'link',
			[
				'label'   => esc_html__( 'Link', 'soledad' ),
				'type'    => Controls_Manager::URL,
				'default' => [
					'url'         => '#',
					'is_external' => false,
					'nofollow'    => false,
				],
			]
		);

		$repeater->end_controls_tab();

		$repeater->start_controls_tab(
			'label_tab',
			[
				'label' => esc_html__( 'Label', 'soledad' ),
			]
		);

		$repeater->add_control(
			'label_style', array(
				'label'   => __( 'Label Style:', 'soledad' ),
				'type'    => Controls_Manager::SELECT,
				'default' => '',
				'options' => array(
					''        => esc_html__( 'Default From General', 'soledad' ),
					'style-1' => esc_html__( 'Style 1', 'soledad' ),
					'style-2' => esc_html__( 'Style 2', 'soledad' ),
					'style-3' => esc_html__( 'Style 3', 'soledad' ),
					'style-4' => esc_html__( 'Style 4', 'soledad' ),
				)
			)
		);

		$repeater->add_control(
			'label',
			[
				'label' => esc_html__( 'Label text (optional)', 'soledad' ),
				'type'  => Controls_Manager::TEXT,
			]
		);

		$repeater->add_control(
			'label_color',
			[
				'label'     => esc_html__( 'Label Background Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .mega-menu-list ul {{CURRENT_ITEM}} .menu-label'          => 'background-color: {{VALUE}}',
					'body.rtl {{WRAPPER}} .mega-menu-list ul {{CURRENT_ITEM}} .menu-label' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .mega-menu-list ul {{CURRENT_ITEM}} .menu-label:before'   => 'border-color: {{VALUE}}',
				),
			]
		);

		$repeater->add_control(
			'label_text_color',
			[
				'label'     => esc_html__( 'Label Text Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .mega-menu-list ul {{CURRENT_ITEM}} .menu-label' => 'color: {{VALUE}}',
				),
			]
		);

		$repeater->end_controls_tab();

		$repeater->start_controls_tab(
			'image_tab',
			[
				'label' => esc_html__( 'Image', 'soledad' ),
			]
		);

		$repeater->add_control(
			'image',
			[
				'label' => esc_html__( 'Choose image', 'soledad' ),
				'type'  => Controls_Manager::MEDIA,
			]
		);

		$repeater->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name'      => 'image',
				'default'   => 'thumbnail',
				'separator' => 'none',
			]
		);

		$repeater->end_controls_tab();

		$repeater->end_controls_tabs();

		$this->add_control(
			'menu_items_repeater',
			[
				'type'        => Controls_Manager::REPEATER,
				'label'       => esc_html__( 'List items', 'soledad' ),
				'separator'   => 'before',
				'title_field' => '{{{ title }}}',
				'fields'      => $repeater->get_controls(),
				'default'     => [
					[
						'title'       => __( 'Menu child item 1', 'soledad' ),
						'label'       => '',
						'label_color' => '',
						'link'        => [
							'url'         => '#',
							'is_external' => false,
							'nofollow'    => false,
						],
					],
					[
						'title'       => __( 'Menu child item  2', 'soledad' ),
						'label'       => 'New',
						'label_color' => '',
						'link'        => [
							'url'         => '#',
							'is_external' => false,
							'nofollow'    => false,
						],
					],
					[
						'title'       => __( 'Menu child item 3', 'soledad' ),
						'label'       => '',
						'label_color' => '',
						'link'        => [
							'url'         => '#',
							'is_external' => false,
							'nofollow'    => false,
						],
					],
					[
						'title'       => __( 'Menu child item 4', 'soledad' ),
						'label'       => '',
						'label_color' => '',
						'link'        => [
							'url'         => '#',
							'is_external' => false,
							'nofollow'    => false,
						],
					],
					[
						'title'       => __( 'Menu child item 5', 'soledad' ),
						'label'       => 'Hot',
						'label_color' => '',
						'link'        => [
							'url'         => '#',
							'is_external' => false,
							'nofollow'    => false,
						],
					],
					[
						'title'       => __( 'Menu child item 6', 'soledad' ),
						'label'       => '',
						'label_color' => '',
						'link'        => [
							'url'         => '#',
							'is_external' => false,
							'nofollow'    => false,
						],
					],
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_image',
			array(
				'label' => __( 'Style', 'soledad' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'header_line_color',
			[
				'label'     => esc_html__( 'Heading Title Line Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .mega-menu-list > li.heading-style-style-2 > a'       => 'border-bottom-color: {{VALUE}}',
					'{{WRAPPER}} .mega-menu-list > li.heading-style-style-3 > a:after' => 'border-bottom-color: {{VALUE}}',
				],
				'condition' => [
					'general_heading_style' => [ 'style-2', 'style-3' ]
				],
			]
		);

		$this->add_control(
			'header_line_active_color',
			[
				'label'     => esc_html__( 'Heading Title Small Line Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .mega-menu-list > li.heading-style-style-2 > a:after' => 'background-color: {{VALUE}}',
				],
				'condition' => [ 'general_heading_style' => 'style-2' ],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(), array(
				'name'     => 'menu_title_typo',
				'label'    => __( 'Title Typography', 'soledad' ),
				'selector' => 'html {{WRAPPER}} .mega-menu-list > li > a',
			)
		);

		$this->add_control(
			'menu_title_color',
			[
				'label'     => esc_html__( 'Menu Title color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'html {{WRAPPER}} .mega-menu-list > li > a' => 'color: {{VALUE}}',
				),
			]
		);

		$this->add_control(
			'menu_title_hover_color',
			[
				'label'     => esc_html__( 'Menu Title Hover color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'html {{WRAPPER}} .mega-menu-list > li > a:hover' => 'color: {{VALUE}}',
				),
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(), array(
				'name'     => 'menu_item_typo',
				'label'    => __( 'Menu Item Typography', 'soledad' ),
				'selector' => 'html {{WRAPPER}} .mega-menu-list ul li a',
			)
		);

		$this->add_control(
			'menu_item_color',
			[
				'label'     => esc_html__( 'Menu Item Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'html {{WRAPPER}} .mega-menu-list ul li a' => 'color: {{VALUE}}',
				),
			]
		);

		$this->add_control(
			'menu_item_color_hover',
			[
				'label'     => esc_html__( 'Menu Item Hover Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'html {{WRAPPER}} .mega-menu-list ul li a:hover' => 'color: {{VALUE}}',
				),
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(), array(
				'name'     => 'label_title_typo',
				'label'    => __( 'Label Typography', 'soledad' ),
				'selector' => 'html {{WRAPPER}} .mega-menu-list .menu-label',
			)
		);

		$this->add_control(
			'general_label_color',
			[
				'label'       => esc_html__( 'Label Text Color', 'soledad' ),
				'description' => 'This settings can be overwritten by item setting on Content Tab',
				'type'        => Controls_Manager::COLOR,
				'default'     => '',
				'selectors'   => array(
					'html {{WRAPPER}} .mega-menu-list .menu-label' => 'color: {{VALUE}}',
				),
			]
		);

		$this->add_control(
			'label_bg_color',
			[
				'label'       => esc_html__( 'Label Background Color', 'soledad' ),
				'description' => 'This settings can be overwritten by item setting on Content Tab',
				'type'        => Controls_Manager::COLOR,
				'default'     => '',
				'selectors'   => array(
					'html {{WRAPPER}} .mega-menu-list .menu-label'          => 'background-color: {{VALUE}}',
					'html body.rtl {{WRAPPER}} .mega-menu-list .menu-label' => 'border-color: {{VALUE}}',
					'html {{WRAPPER}} .mega-menu-list .menu-label:before'   => 'border-color: {{VALUE}}',
				),
			]
		);

		$this->end_controls_section();
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
		$default_settings = [
			'title'               => '',
			'label_style'         => '',
			'link'                => '',
			'image'               => '',
			'label'               => '',
			'menu_items_repeater' => [],
		];

		$settings = wp_parse_args( $this->get_settings_for_display(), $default_settings );

		$parent_label_style = ! empty( $settings['label_style'] ) ? $settings['label_style'] : $settings['general_label_style'];
		$col                = $settings['item_col'] ? $settings['item_col'] : '1';
		$item_col           = ' pcmg-subcol-' . $col;
		if ( '2' == $col || '3' == $col ) {
			$item_col .= ' penci-clearfix';
		}

		$this->add_render_attribute(
			[
				'parent_ul'    => [
					'class' => [
						'penci-sub-menu',
						'mega-menu-list',
					],
				],
				'parent_li'    => [
					'class' => [
						'item-with-label',
						'heading-style-' . $settings['general_heading_style']
					],
				],
				'parent_title' => [
					'class' => [
						'nav-link-text',
					],
				],
				'parent_label' => [
					'class' => [
						'menu-label',
						'label-' . $parent_label_style,
					],
				],
				'parent_label' => [
					'class' => [
						'menu-label',
						'label-' . $parent_label_style,
					],
				],
			]
		);

		$this->add_inline_editing_attributes( 'parent_title' );
		$this->add_inline_editing_attributes( 'parent_label' );

		$link_attrs = penci_el_get_link_attrs( $settings['link'] );

		?>
        <ul <?php echo $this->get_render_attribute_string( 'parent_ul' ); ?>>
            <li <?php echo $this->get_render_attribute_string( 'parent_li' ); ?>>
				<?php if ( $settings['title'] ) : ?>
                    <a <?php echo $link_attrs; ?>>
						<?php if ( $settings['image'] ) : ?>
							<?php echo penci_get_image_html( $settings, 'image' ); ?>
						<?php endif; ?>

						<?php if ( penci_elementor_is_edit_mode() ) : ?>
                        <span <?php echo $this->get_render_attribute_string( 'parent_title' ); ?>>
							<?php endif; ?>
							<?php echo wp_kses( $settings['title'], penci_allow_html() ); ?>
							<?php if ( penci_elementor_is_edit_mode() ) : ?>
								</span>
					<?php endif; ?>

						<?php if ( $settings['label'] ) : ?>
                            <span <?php echo $this->get_render_attribute_string( 'parent_label' ); ?>>
									<?php echo wp_kses( $settings['label'], penci_allow_html() ); ?>
								</span>
						<?php endif; ?>
                    </a>
				<?php endif; ?>

                <ul class="sub-sub-menu<?php echo $item_col; ?>">
					<?php foreach ( $settings['menu_items_repeater'] as $index => $item ) : ?>
						<?php
						$repeater_li_key    = $this->get_repeater_setting_key( 'li', 'menu_items_repeater', $index );
						$repeater_title_key = $this->get_repeater_setting_key( 'title', 'menu_items_repeater', $index );
						$repeater_label_key = $this->get_repeater_setting_key( 'label', 'menu_items_repeater', $index );

						$label_style = ! empty( $item['label_style'] ) ? $item['label_style'] : $settings['general_label_style'];

						$this->add_render_attribute(
							[
								$repeater_li_key    => [
									'class' => [
										'item-with-label',
										'item-label',
										'elementor-repeater-item-' . $item['_id'],
									],
								],
								$repeater_label_key => [
									'class' => [
										'menu-label',
										'label-' . $label_style
									],
								],
							]
						);

						$this->add_inline_editing_attributes( $repeater_title_key );
						$this->add_inline_editing_attributes( $repeater_label_key );

						$link_attrs = penci_el_get_link_attrs( $item['link'] );
						?>

                        <li <?php echo $this->get_render_attribute_string( $repeater_li_key ); ?>>
                            <a <?php echo $link_attrs; ?>>
								<?php if ( $item['image'] ) : ?>
									<?php echo penci_get_image_html( $item, 'image' ); ?>
								<?php endif; ?>

								<?php if ( penci_elementor_is_edit_mode() ) : ?>
                                <span <?php echo $this->get_render_attribute_string( $repeater_title_key ); ?>>
									<?php endif; ?>
									<?php echo wp_kses( $item['title'], penci_allow_html() ); ?>
									<?php if ( penci_elementor_is_edit_mode() ) : ?>
										</span>
							<?php endif; ?>

								<?php if ( $item['label'] ) : ?>
                                    <span <?php echo $this->get_render_attribute_string( $repeater_label_key ); ?>>
											<?php echo wp_kses( $item['label'], penci_allow_html() ); ?>
										</span>
								<?php endif; ?>
                            </a>
                        </li>
					<?php endforeach; ?>
                </ul>
            </li>
        </ul>
		<?php
	}
}
