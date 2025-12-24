<?php
namespace PenciSoledadElementor\Modules\PenciButton\Widgets;

use PenciSoledadElementor\Base\Base_Widget;
use Elementor\Controls_Manager;
use Elementor\Utils;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Background;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class PenciButton extends Base_Widget {

	public function get_name() {
		return 'penci-button';
	}

	public function get_title() {
		return penci_get_theme_name( 'Penci' ) . ' ' . esc_html__( ' Button', 'soledad' );
	}

	public function get_icon() {
		return 'eicon-button';
	}

	public function get_categories() {
		return array( 'penci-elements' );
	}

	public function get_keywords() {
		return array( 'button', 'click', 'penci', 'soledad' );
	}

	protected function register_controls() {

		$this->start_controls_section(
			'section_general',
			array(
				'label' => penci_get_theme_name( 'Penci' ) . ' ' . esc_html__( ' Button', 'soledad' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'text',
			array(
				'label'       => __( 'Button Text', 'soledad' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => __( 'Click Here', 'soledad' ),
				'placeholder' => __( 'Add Button Text Here', 'soledad' ),
			)
		);

		$this->add_control(
			'link',
			array(
				'label'       => __( 'Link', 'elementor' ),
				'type'        => Controls_Manager::URL,
				'placeholder' => __( 'https://your-link.com', 'soledad' ),
				'default'     => array(
					'url' => 'https://example.com/',
				),
			)
		);

		$this->add_responsive_control(
			'align',
			array(
				'label'   => __( 'Alignment', 'elementor' ),
				'type'    => Controls_Manager::CHOOSE,
				'options' => array(
					'left'    => array(
						'title' => __( 'Left', 'elementor' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center'  => array(
						'title' => __( 'Center', 'elementor' ),
						'icon'  => 'eicon-text-align-center',
					),
					'right'   => array(
						'title' => __( 'Right', 'elementor' ),
						'icon'  => 'eicon-text-align-right',
					),
					'justify' => array(
						'title' => __( 'Full', 'elementor' ),
						'icon'  => 'eicon-text-align-justify',
					),
				),
				'default' => '',
			)
		);

		$this->add_responsive_control(
			'button_padding',
			array(
				'label'      => __( 'Button Padding', 'soledad' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .pcbtn-wrapperin' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'button_radius',
			array(
				'label'      => __( 'Button Border Radius', 'soledad' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .pcbtn-wrapperin, {{WRAPPER}} .pcbtn-wrapper a.pcbtn, {{WRAPPER}} .pcbtn-wrapper a.pcbtn:before' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition'  => array( 'border_style!' => 'gradient' ),
			)
		);

		$this->add_control(
			'add_borders',
			array(
				'label'     => __( 'Add Borders to Button', 'soledad' ),
				'type'      => Controls_Manager::SWITCHER,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'border_style',
			array(
				'label'     => __( 'Borders Style', 'soledad' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => array(
					''         => 'Solid',
					'dotted'   => 'Dotted',
					'dashed'   => 'Dashed',
					'double'   => 'Double',
					'groove'   => 'Groove',
					'ridge'    => 'Ridge',
					'inset'    => 'Inset',
					'outset'   => 'Outset',
					'gradient' => 'Gradient',
				),
				'default'   => '',
				'condition' => array( 'add_borders' => 'yes' ),
			)
		);

		$this->add_responsive_control(
			'border_width',
			array(
				'label'      => __( 'Custom Button Borders Width', 'soledad' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .pcbtn-bordered .pcbtn-wrapperin, {{WRAPPER}} .pcbtn-wrapper a.pcbtn:before' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition'  => array( 'add_borders' => 'yes' ),
			)
		);

		$this->add_control(
			'add_icon',
			array(
				'label'     => __( 'Add Icon to Button', 'soledad' ),
				'type'      => Controls_Manager::SWITCHER,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'button_icon',
			array(
				'label'     => esc_html__( 'Icon', 'soledad' ),
				'type'      => Controls_Manager::ICONS,
				'default'   => array(
					'value'   => 'fas fa-star',
					'library' => 'solid',
				),
				'condition' => array( 'add_icon' => 'yes' ),
			)
		);

		$this->add_control(
			'icon_pos',
			array(
				'label'     => __( 'Icon Position', 'soledad' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => array(
					'left'  => __('Before','soledad' ),
					'right' => __('After','soledad' ),
				),
				'default'   => 'left',
				'condition' => array( 'add_icon' => 'yes' ),
			)
		);

		$this->add_responsive_control(
			'icon_space',
			array(
				'label'     => __( 'Icon Spacing', 'soledad' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 300,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .pcbtn-icon-left span span i, {{WRAPPER}} .pcbtn-icon-left span span svg' => 'margin-right: {{SIZE}}px;',
					'{{WRAPPER}} .pcbtn-icon-right span span i, {{WRAPPER}} .pcbtn-icon-right span span svg' => 'margin-left: {{SIZE}}px;',
				),
				'condition' => array( 'add_icon' => 'yes' ),
			)
		);

		// Subtext
		$this->add_control(
			'add_subtext',
			array(
				'label'     => __( 'Add Subtext?', 'soledad' ),
				'type'      => Controls_Manager::SWITCHER,
				'separator' => 'before',
			)
		);
		$this->add_control(
			'subtext',
			array(
				'label'       => __( 'SubText', 'soledad' ),
				'type'        => Controls_Manager::TEXTAREA,
				'default'     => __( 'Example Subtext', 'soledad' ),
				'placeholder' => __( 'Add Your SubText Here', 'soledad' ),
				'label_block' => true,
				'condition'   => array( 'add_subtext' => 'yes' ),
			)
		);

		$this->add_responsive_control(
			'subtext_align',
			array(
				'label'     => __( 'Text & Subtext Alignment', 'elementor' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'left'   => array(
						'title' => __( 'Left', 'elementor' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center' => array(
						'title' => __( 'Center', 'elementor' ),
						'icon'  => 'eicon-text-align-center',
					),
					'right'  => array(
						'title' => __( 'Right', 'elementor' ),
						'icon'  => 'eicon-text-align-right',
					),
				),
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .pcbtn-wrapperin' => 'text-align: {{VALUE}};',
				),
				'condition' => array( 'add_subtext' => 'yes' ),
			)
		);

		$this->add_responsive_control(
			'subtext_margin',
			array(
				'label'      => __( 'Subtext Margin', 'soledad' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'default'    => array(
					'top'      => '',
					'right'    => '',
					'bottom'   => '',
					'left'     => '',
					'unit'     => 'px',
					'isLinked' => false,
				),
				'selectors'  => array(
					'{{WRAPPER}} span.pcbtn-subtext' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition'  => array( 'add_subtext' => 'yes' ),
			)
		);

		$this->add_control(
			'button_id',
			array(
				'label'       => __( 'Button ID', 'soledad' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'title'       => __( 'Add your custom id WITHOUT the Pound key. e.g: my-id', 'soledad' ),
				'description' => __( 'Please make sure the ID is unique and not used elsewhere on the page this form is displayed. This field allows <code>A-z 0-9</code> & underscore chars without spaces.', 'soledad' ),
				'separator'   => 'before',
			)
		);

		$this->end_controls_section();

		// Design
		$this->start_controls_section(
			'section_design_content',
			array(
				'label' => penci_get_theme_name( 'Penci' ) . ' ' . esc_html__( 'Button', 'soledad' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'button_typo',
				'label'    => __( 'Typography', 'soledad' ),
				'selector' => '{{WRAPPER}} span.pcbtn-content',
			)
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			array(
				'name'     => 'button_tshadow',
				'label'    => __( 'Text Shadow', 'soledad' ),
				'selector' => '{{WRAPPER}} span.pcbtn-content',
			)
		);

		$this->add_responsive_control(
			'icon_size',
			array(
				'label'     => __( 'Icon Font Size', 'soledad' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 500,
					),
				),
				'selectors' => array(
					'{{WRAPPER}}  span.pcbtn-content i'   => 'font-size: {{SIZE}}px;',
					'{{WRAPPER}}  span.pcbtn-content svg' => 'width: {{SIZE}}px;',
				),
				'condition' => array( 'add_icon' => 'yes' ),
			)
		);

		$this->add_control(
			'subtext_heading',
			array(
				'label'     => __( 'Subtext', 'soledad' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array( 'add_subtext' => 'yes' ),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'subtext_typo',
				'label'     => __( 'SubText Typography', 'soledad' ),
				'selector'  => '{{WRAPPER}} span.pcbtn-subtext',
				'condition' => array( 'add_subtext' => 'yes' ),
			)
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			array(
				'name'      => 'button_subtshadow',
				'label'     => __( 'Text Shadow', 'soledad' ),
				'selector'  => '{{WRAPPER}} span.pcbtn-subtext',
				'condition' => array( 'add_subtext' => 'yes' ),
			)
		);

		$this->start_controls_tabs(
			'style_tabs'
		);

		$this->start_controls_tab(
			'style_normal_tab',
			array(
				'label' => __( 'Normal', 'soledad' ),
			)
		);

		$this->add_control(
			'btcolor',
			array(
				'label'     => __( 'Button Text Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} span.pcbtn-content' => 'color: {{VALUE}};' ),
			)
		);

		$this->add_control(
			'iconcolor',
			array(
				'label'     => __( 'Icon Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} span.pcbtn-content i'   => 'color: {{VALUE}};',
					'{{WRAPPER}} span.pcbtn-content svg' => 'fill: {{VALUE}};',
				),
				'condition' => array( 'add_icon' => 'yes' ),
			)
		);

		$this->add_control(
			'subcolor',
			array(
				'label'     => __( 'Subtext Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} span.pcbtn-subtext' => 'color: {{VALUE}};' ),
				'condition' => array( 'add_subtext' => 'yes' ),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'        => 'btbg',
				'label'       => __( 'Button Background', 'soledad' ),
				'types'       => array( 'classic', 'gradient' ),
				'selector'    => '{{WRAPPER}} .pcbtn-wrapperin, {{WRAPPER}} .pcbtn-wrapper a.pcbtn',
				'label_block' => true,
				'separator'   => 'before',
			)
		);

		$this->add_control(
			'border_cl',
			array(
				'label'     => __( 'Borders Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .pcbtn-bordered .pcbtn-wrapperin' => 'border-color: {{VALUE}};' ),
				'condition' => array( 'add_borders' => 'yes' ),
				'separator' => 'before',
			)
		);

		$this->add_control(
			'sborder_cl',
			array(
				'label'     => __( 'Second Borders Color( Gradient Borders )', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .pcbtn-bordered .pcbtn-wrapperin' => 'border-image: linear-gradient( 360deg , {{border_cl.VALUE}}, {{VALUE}} ); border-image: -webkit-linear-gradient( 360deg , {{border_cl.VALUE}}, {{VALUE}} ); border-image-slice: 1;' ),
				'condition' => array(
					'add_borders'  => 'yes',
					'border_style' => 'gradient',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'      => 'box_shadow',
				'label'     => __( 'Box Shadow', 'soledad' ),
				'selector'  => '{{WRAPPER}} a.pcbtn',
				'separator' => 'before',
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'style_hover_tab',
			array(
				'label' => __( 'Hover', 'soledad' ),
			)
		);

		$this->add_control(
			'bthcolor',
			array(
				'label'     => __( 'Button Text Hover Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} a.pcbtn:hover span.pcbtn-content' => 'color: {{VALUE}};' ),
			)
		);

		$this->add_control(
			'iconhcolor',
			array(
				'label'     => __( 'Icon Hover Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} a.pcbtn:hover span.pcbtn-content i' => 'color: {{VALUE}};',
					'{{WRAPPER}} a.pcbtn:hover span.pcbtn-content svg' => 'fill: {{VALUE}};',
				),
				'condition' => array( 'add_icon' => 'yes' ),
			)
		);

		$this->add_control(
			'subhcolor',
			array(
				'label'     => __( 'Subtext Hover Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} a.pcbtn:hover span.pcbtn-subtext' => 'color: {{VALUE}};' ),
				'condition' => array( 'add_subtext' => 'yes' ),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'        => 'bthbg',
				'label'       => __( 'Button Hover Background', 'soledad' ),
				'types'       => array( 'classic', 'gradient' ),
				'selector'    => '{{WRAPPER}} .pcbtn-wrapper a.pcbtn:before',
				'label_block' => true,
				'separator'   => 'before',
			)
		);

		$this->add_control(
			'border_hcl',
			array(
				'label'     => __( 'Borders Hover Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .pcbtn-wrapper a.pcbtn:before' => 'border-color: {{VALUE}};' ),
				'condition' => array( 'add_borders' => 'yes' ),
				'separator' => 'before',
			)
		);

		$this->add_control(
			'sborder_hcl',
			array(
				'label'     => __( 'Second Borders Hover Color( Gradient Borders )', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .pcbtn-wrapper a.pcbtn:before' => 'border-image: linear-gradient( 360deg , {{border_hcl.VALUE}}, {{VALUE}} ); border-image: -webkit-linear-gradient( 360deg , {{border_hcl.VALUE}}, {{VALUE}} ); border-image-slice: 1;' ),
				'condition' => array(
					'add_borders'  => 'yes',
					'border_style' => 'gradient',
				),
			)
		);

		$this->add_control(
			'hover_animation',
			array(
				'label' => esc_html__( 'Hover Animation', 'soledad' ),
				'type'  => Controls_Manager::HOVER_ANIMATION,
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'      => 'hbox_shadow',
				'label'     => __( 'Box Shadow on Hover', 'soledad' ),
				'selector'  => '{{WRAPPER}} a.pcbtn:hover',
				'separator' => 'before',
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	protected function render() {
		$settings       = $this->get_settings_for_display();
		$text           = $settings['text'] ? $settings['text'] : '';
		$link           = $settings['link']['url'] ? $settings['link']['url'] : '';
		$link_external  = $settings['link']['is_external'] ? ' target="_blank"' : '';
		$link_nofollow  = $settings['link']['nofollow'] ? ' rel="nofollow"' : '';
		$link_attr      = '';
		$link_attr_data = $settings['link']['custom_attributes'] ? $settings['link']['custom_attributes'] : '';
		if ( $link_attr_data ) {
			$link_attr = $this->get_data_link( $link_attr_data );
		}
		$button_id_attr = '';
		$button_id      = $settings['button_id'] ? $settings['button_id'] : '';
		if ( $button_id ) {
			$button_id      = str_replace( ' ', '', $button_id );
			$button_id_attr = ' id="' . $button_id . '"';
		}
		$add_icon     = $settings['add_icon'] ? $settings['add_icon'] : '';
		$add_borders  = $settings['add_borders'] ? $settings['add_borders'] : '';
		$border_style = $settings['border_style'] ? $settings['border_style'] : 'solid';
		$icon_pos     = $settings['icon_pos'] ? $settings['icon_pos'] : 'left';
		$align        = $settings['align'] ? $settings['align'] : 'none';
		$align_class  = 'pcbtn-align-' . $align;
		if ( isset( $settings['align_tablet'] ) && $settings['align_tablet'] ) {
			$align_class .= ' pcbtn-talign-' . $settings['align_tablet'];
		}
		if ( isset( $settings['align_mobile'] ) && $settings['align_mobile'] ) {
			$align_class .= ' pcbtn-malign-' . $settings['align_mobile'];
		}
		$add_subtext    = $settings['add_subtext'] ? $settings['add_subtext'] : '';
		$subtext        = $settings['subtext'] ? $settings['subtext'] : '';
		$button_icon    = $settings['button_icon'] ? $settings['button_icon'] : array();
		$icon_html      = '';
		$button_classes = 'pcbtn pcbtn-icon-' . $icon_pos;
		if ( $add_borders ) {
			$button_classes .= ' pcbtn-bordered pcbtn-bstyle-' . $border_style;
		}
		if ( 'yes' == $add_icon && ! empty( $button_icon ) ) {
			ob_start();
			\Elementor\Icons_Manager::render_icon( $button_icon );
			$icon_html = ob_get_clean();
		}
		if ( $settings['hover_animation'] ) {
			$button_classes .= ' elementor-animation-' . $settings['hover_animation'];
		}
		?>
		<div class="pcbtn-wrapper <?php echo $align_class; ?>">
			<a class="<?php echo $button_classes; ?>" 
								<?php
								if ( $link ) {
									?>
				href="<?php echo esc_url( $link ); ?>"<?php } echo $link_external . $link_nofollow . $link_attr . $button_id_attr; ?>>
				<span class="pcbtn-wrapperin">
					<span class="pcbtn-content">
					<?php
					if ( 'left' == $icon_pos ) :
						echo $icon_html;
endif;
					?>
					<?php echo do_shortcode( $text ); ?>
			<?php
			if ( 'right' == $icon_pos ) :
				echo $icon_html;
endif;
			?>
</span>
					<?php if ( 'yes' == $add_subtext && $subtext ) { ?>
					<span class="pcbtn-subtext"><?php echo do_shortcode( $subtext ); ?></span>
					<?php } ?>
				</span>
			</a>
		</div>
		<?php
	}
}
