<?php

namespace PenciSoledadElementor\Modules\PenciBlockHeadingTitle\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use PenciSoledadElementor\Base\Base_Widget;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class PenciBlockHeadingTitle extends Base_Widget {

	public function get_name() {
		return 'penci-block-heading';
	}

	public function get_title() {
		return penci_get_theme_name( 'Penci' ) . ' ' . esc_html__( ' Block Heading', 'soledad' );
	}

	public function get_icon() {
		return 'eicon-url';
	}

	public function get_categories() {
		return array( 'penci-elements' );
	}

	public function get_keywords() {
		return array( 'heading', 'block heading', 'title' );
	}

	protected function register_controls() {

		$this->register_block_title_section_controls();

		$this->start_controls_section(
			'section_extra_link',
			array(
				'label' => __( 'Extra Button Link', 'soledad' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'btn_text',
			array(
				'label'   => __( 'Button Text', 'soledad' ),
				'type'    => Controls_Manager::TEXT,
				'default' => '',
			)
		);

		$this->add_control(
			'btn_url',
			array(
				'label'       => __( 'Button URL', 'soledad' ),
				'type'        => Controls_Manager::URL,
				'placeholder' => __( 'https://your-link.com', 'soledad' ),
			)
		);

		$this->add_control(
			'btn_style',
			array(
				'label'   => __( 'Button Style', 'soledad' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'none',
				'options' => array(
					'none'      => esc_html__( 'None', 'soledad' ),
					'underline' => esc_html__( 'Underline', 'soledad' ),
				),
			)
		);

		$this->add_control(
			'btn_pos',
			array(
				'label'   => __( 'Button Position with Title', 'soledad' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'right',
				'options' => array(
					'right' => esc_html__( 'Right', 'soledad' ),
					'left'  => esc_html__( 'Left', 'soledad' ),
				),
			)
		);
		
		$this->add_control(
			'btn_pos_lr',
			array(
				'label'   => __( 'Button Position', 'soledad' ),
				'type'    => Controls_Manager::SELECT,
				'default' => '',
				'options' => array(
					'' 		=> esc_html__( 'Default', 'soledad' ),
					'right' => esc_html__( 'Right', 'soledad' ),
					'left'  => esc_html__( 'Left', 'soledad' ),
				),
			)
		);

		$this->add_responsive_control(
			'btn_space',
			array(
				'label'     => __( 'Button Spacing', 'soledad' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 300,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .pcbh-btnp-left'  => 'margin-right: {{SIZE}}px;',
					'{{WRAPPER}} .pcbh-btnp-right' => 'margin-left: {{SIZE}}px;',
				),
			)
		);

		$this->add_control(
			'btn_hidemobile',
			array(
				'label' => __( 'Hide Extra Button Link on Mobile', 'soledad' ),
				'type'  => Controls_Manager::SWITCHER,
			)
		);

		$this->add_control(
			'btn_addicon',
			array(
				'label' => __( 'Add Icon to Button', 'soledad' ),
				'type'  => Controls_Manager::SWITCHER,
			)
		);

		$this->add_control(
			'btn_icon',
			array(
				'label'     => esc_html__( 'Icon', 'soledad' ),
				'type'      => Controls_Manager::ICONS,
				'condition' => array( 'btn_addicon' => 'yes' ),
			)
		);

		$this->add_control(
			'btn_iconpos',
			array(
				'label'     => esc_html__( 'Icon position', 'soledad' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => array(
					'right' => esc_html__( 'Right', 'soledad' ),
					'left'  => esc_html__( 'Left', 'soledad' ),
				),
				'default'   => 'right',
				'condition' => array( 'btn_addicon' => 'yes' ),
			)
		);

		$this->end_controls_section();

		// Link Group
		$this->start_controls_section(
			'section_group_links',
			array(
				'label' => __( 'Extra Links Group', 'soledad' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);
		$repeater = new \Elementor\Repeater();

		$this->add_control(
			'group_more_link_text',
			array(
				'label'   => __( 'More Text', 'soledad' ),
				'type'    => Controls_Manager::TEXT,
				'default' => 'More',
			)
		);

		$this->add_responsive_control(
			'group_more_link_items',
			array(
				'label'          => __( 'Maxium Items Display', 'soledad' ),
				'type'           => Controls_Manager::SLIDER,
				'default'        => array( 'size' => 5 ),
				'tablet_default' => array( 'size' => 4 ),
				'mobile_default' => array( 'size' => 2 ),
				'range'          => array(
					'px' => array(
						'min'  => 1,
						'max'  => 10,
						'step' => 1,
					),
				),
			)
		);

		$repeater->add_control(
			'group_link_title',
			array(
				'label' => __( 'Link Title', 'soledad' ),
				'type'  => Controls_Manager::TEXT,
			)
		);
		$repeater->add_control(
			'group_link_url',
			array(
				'label' => __( 'Link URL', 'soledad' ),
				'type'  => Controls_Manager::URL,
			)
		);
		$this->add_control(
			'link_items',
			array(
				'type'   => Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
			)
		);
		$this->end_controls_section();

		// Design
		$this->start_controls_section(
			'section_padding_general',
			array(
				'label'     => __( 'Spacing', 'soledad' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'heading_title_style!' => array(
						'style-7',
						'style-10',
						'style-12',
						'style-13',
						'style-16',
					),
				),
			)
		);

		$this->add_responsive_control(
			'bht_padding',
			array(
				'label'      => __( 'Block Heading Padding', 'soledad' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'condition'  => array(
					'heading_title_style!' => array(
						'style-7',
						'style-10',
						'style-12',
						'style-13',
						'style-16',
					),
				),
				'selectors'  => array( '{{WRAPPER}} .penci-homepage-title .inner-arrow' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ),
			)
		);

		$this->end_controls_section();

		$this->register_block_title_style_section_controls();

		$this->start_controls_section(
			'section_extrabtn_general',
			array(
				'label' => __( 'Extra Button Link', 'soledad' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'btn_text_color',
			array(
				'label'     => __( 'Text Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .penci-border-arrow .inner-arrow .pcbh-extrabtn' => 'color: {{VALUE}} !important;',
				),
			)
		);

		$this->add_control(
			'btn_text_hcolor',
			array(
				'label'     => __( 'Text Hover Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .penci-border-arrow .inner-arrow a.pcbh-extrabtn:hover' => 'color: {{VALUE}} !important;',
				),
			)
		);

		$this->add_control(
			'btn_line_color',
			array(
				'label'     => __( 'Line Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'condition' => array( 'btn_style' => 'underline' ),
				'selectors' => array(
					'{{WRAPPER}} .penci-border-arrow .inner-arrow .pcbh-btns-underline:after' => 'background-color: {{VALUE}} !important;',
				),
			)
		);

		$this->add_control(
			'btn_line_hcolor',
			array(
				'label'     => __( 'Line Hover Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'condition' => array( 'btn_style' => 'underline' ),
				'selectors' => array(
					'{{WRAPPER}} .penci-border-arrow .inner-arrow a.pcbh-btns-underline:hover:after' => 'background-color: {{VALUE}} !important;',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'btn_typo',
				'label'    => __( 'Block Title Typography', 'soledad' ),
				'selector' => '{{WRAPPER}} .penci-border-arrow .inner-arrow a.pcbh-extrabtn',
			)
		);

		$this->end_controls_section();

		$this->register_block_heading_link_section_style( 'Extra Links Group' );

	}

	protected function render() {
		$r = $this->get_settings();

		if ( ! $r['heading'] ) {
			return;
		}

		$link_group     = $r['link_items'];
		$link_group_out = '';

		if ( ! empty( $link_group ) ) {
			$link_group_out .= '<nav data-more="' . esc_attr( $r['group_more_link_text'] ) . '" class="pcnav-lgroup"><ul class="pcflx">';
			$count           = 0;
			foreach ( $link_group as $link ) {

				$element_id = 'link-wrapper-' . $count;

				if ( ! empty( $link['group_link_url']['url'] ) ) {
					$this->add_render_attribute( $element_id, 'href', $link['group_link_url']['url'] );
					if ( ! empty( $link['group_link_url']['is_external'] ) ) {
						$this->add_render_attribute( $element_id, 'target', '_blank' );
					}
					if ( $link['group_link_url']['nofollow'] ) {
						$this->add_render_attribute( $element_id, 'rel', 'nofollow' );
					}
				}

				$link_group_out .= '<li><a ' . $this->get_render_attribute_string( $element_id ) . '>' . $link['group_link_title'] . '</a></li>';

				$count ++;
			}
			$link_group_out .= '</ul></nav>';
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

		$classes  = 'penciel-bheading penci-border-arrow penci-homepage-title penci-home-latest-posts';
		$classes .= ' ' . $heading_title;
		$classes .= ' ' . $heading_align;
		$classes .= ' ' . $heading_icon_pos;
		$classes .= ' ' . $heading_icon_design;
		$classes .= $r['block_title_ialign'] ? ' block-title-icon-' . $r['block_title_ialign'] : ' block-title-icon-left';
		
		
		$btn_extra_class = $r['btn_text'] && $r['btn_pos_lr'] ? $r['btn_pos_lr'] : '';
		$btn_extra_class = $btn_extra_class == $r['block_title_align'] ? '' : $btn_extra_class;

		$classes .= $btn_extra_class ? ' block-tbtnpos-' . $btn_extra_class : '';
		$extra_btn_html    = $btn_classes = '';
		$btn_pos           = isset( $r['btn_pos'] ) ? $r['btn_pos'] : 'right';
		$btn_ipos          = isset( $r['btn_iconpos'] ) ? $r['btn_iconpos'] : 'right';
		$class_hide_mobile = 'yes' == $r['btn_hidemobile'] ? ' pcbtn-etrhm' : '';
		if ( $r['btn_text'] ) {
			ob_start();
			$btn_style   = isset( $r['btn_style'] ) ? $r['btn_style'] : '';
			$btn_classes = 'pcbh-extrabtn pcbh-btns-' . $btn_style . ' pcbh-btnp-' . $btn_pos . ' pcbh-btni-' . $btn_ipos . $class_hide_mobile;
			if ( $r['btn_url']['url'] ) {
				$btntarget   = $r['btn_url']['is_external'] ? ' target="_blank"' : '';
				$btnnofollow = $r['btn_url']['nofollow'] ? ' rel="nofollow"' : '';
				echo '<a class="' . $btn_classes . '"' . $btntarget . $btnnofollow . ' href="' . $r['btn_url']['url'] . '">';
			} else {
				echo '<span class="' . $btn_classes . '">';
			}

			if ( $r['btn_addicon'] && $r['btn_icon'] && 'left' == $r['btn_iconpos'] ) {
				\Elementor\Icons_Manager::render_icon( $r['btn_icon'] );
			}
			echo do_shortcode( $r['btn_text'] );
			if ( $r['btn_addicon'] && $r['btn_icon'] && 'right' == $r['btn_iconpos'] ) {
				\Elementor\Icons_Manager::render_icon( $r['btn_icon'] );
			}
			if ( $r['btn_url']['url'] ) {
				echo '</a>';
			} else {
				echo '</span>';
			}
			$extra_btn_html = ob_get_contents();
			ob_end_clean();
		}
		$htag = isset( $r['heading_title_html_tag'] ) && $r['heading_title_html_tag'] ? $r['heading_title_html_tag'] : 'h3';
		?>
		<div class="<?php echo esc_attr( $classes ); ?>">
			<<?php echo $htag;?> class="inner-arrow">
				<span>
				<?php
				if ( 'left' == $btn_pos && $extra_btn_html ) {
					echo $extra_btn_html;
				}

				if ( $r['heading_title_link']['url'] ) {
					$target   = $r['btn_url']['is_external'] ? ' target="_blank"' : '';
					$nofollow = $r['btn_url']['nofollow'] ? ' rel="nofollow"' : '';

					echo '<a href="' . $r['heading_title_link']['url'] . '"' . $target . $nofollow . '>';
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
				if ( $r['heading_title_link']['url'] ) {
					echo '</a>';
				} else {
					echo '</span>';
				}

				if ( 'right' == $btn_pos && $extra_btn_html ) {
					echo $extra_btn_html;
				}
				?>
				</span>
			</<?php echo $htag;?>>
			<?php
			if ( $link_group_out ) {
				echo $link_group_out;
			}
			?>
		</div>
		<?php
	}
}
