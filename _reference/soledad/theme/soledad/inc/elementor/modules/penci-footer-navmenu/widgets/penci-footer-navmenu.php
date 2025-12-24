<?php

namespace PenciSoledadElementor\Modules\PenciFooterNavmenu\Widgets;

use PenciSoledadElementor\Base\Base_Widget;
use PenciSoledadElementor\Base\Base_Color;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Image_Size;
use Elementor\Utils;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Direct access not allowed.
}

/**
 * Elementor widget that inserts an embeddable content into the page, from any given URL.
 *
 * @since 1.0.0
 */
class PenciFooterNavmenu extends Base_Widget {
	public function get_name() {
		return 'penci-footer-navmenu';
	}

	public function get_title() {
		return penci_get_theme_name('Penci').' '.esc_html__( ' Footer Nav Menu', 'soledad' );
	}

	public function get_icon() {
		return 'eicon-form-vertical';
	}
	
	public function get_categories() {
		return [ 'penci-elements' ];
	}

	public function get_keywords() {
		return array( 'list', 'menu', 'footer', 'builder', 'nav' );
	}
	
	private function get_available_menus() {
		$menus = wp_get_nav_menus();

		$options = [];

		foreach ( $menus as $menu ) {
			$options[ $menu->slug ] = $menu->name;
		}

		return $options;
	}

	protected function register_controls() {
		

		// Section general
		$this->start_controls_section(
			'section_type', array(
				'label' => esc_html__( 'General', 'soledad' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);
		
		$menus = $this->get_available_menus();

		if ( ! empty( $menus ) ) {
			$this->add_control(
				'menu',
				[
					'label' => __( 'Menu', 'soledad' ),
					'type' => Controls_Manager::SELECT,
					'options' => $menus,
					'default' => array_keys( $menus )[0],
					'save_default' => true,
					'separator' => 'after',
					'description' => sprintf( __( 'Go to the <a href="%s" target="_blank">Menus screen</a> to manage your menus. Please note that: The Footer Nav Menu does not support showing sub-menu items. All sub-menus will be hidden.', 'soledad' ), admin_url( 'nav-menus.php' ) ),
				]
			);
		} else {
			$this->add_control(
				'menu',
				[
					'type' => Controls_Manager::RAW_HTML,
					'raw' => '<strong>' . __( 'There are no menus in your site.', 'soledad' ) . '</strong><br>' . sprintf( __( 'Go to the <a href="%s" target="_blank">Menus screen</a> to create one.', 'soledad' ), admin_url( 'nav-menus.php?action=edit&menu=0' ) ),
					'separator' => 'after',
					'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
				]
			);
		}
		
		$this->add_responsive_control(
			'align_items',
			[
				'label' => __( 'Align', 'soledad' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => __( 'Left', 'soledad' ),
						'icon' => 'eicon-h-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'soledad' ),
						'icon' => 'eicon-h-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'soledad' ),
						'icon' => 'eicon-h-align-right',
					],
				],
				'selectors'   => array(
					'{{WRAPPER}} .pcfooter-navmenu .pcfoot-navmenu' => 'text-align: {{VALUE}}'
				)
			]
		);
		
		
		$this->add_control(
			'separator',
			array(
				'label'   => __( 'Separator Between Menu Items', 'soledad' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'none'   => 'None',
					'verline'   => 'Vertical Line',
					'slash'   => 'Slash Line',
					'dslash'   => 'Double Slash',
					'circle'   => 'Circle',
				),
				'default' => 'none',
			)
		);
		
		$this->add_responsive_control(
			'separator_height', array(
				'label'     => __( 'Separator Height', 'soledad' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array( 'px' => array( 'min' => 1, 'max' => 50 ) ),
				'selectors' => array(
					'{{WRAPPER}} .pcfooter-navmenu' => '--pcfnmn-spea-height: {{SIZE}}px;',
				),
				'condition' => array( 'separator' => array( 'verline', 'slash', 'dslash' ) ),
			)
		);
		
		$this->add_responsive_control(
			'separator_cheight', array(
				'label'     => __( 'CirCle Separator Width & Height', 'soledad' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array( 'px' => array( 'min' => 1, 'max' => 50 ) ),
				'selectors' => array(
					'{{WRAPPER}} .pcfooter-navmenu' => '--pcfnmn-cirspea-h: {{SIZE}}px;',
				),
				'condition' => array( 'separator' => array( 'circle' ) ),
			)
		);
		
		$this->add_responsive_control(
			'item_space', array(
				'label'     => __( 'Spacing Between Menu Items', 'soledad' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array( 'px' => array( 'min' => 1, 'max' => 300 ) ),
				'selectors' => array(
					'{{WRAPPER}} .pcfooter-navmenu' => '--pcfnmn-space: {{SIZE}}px;',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_content',
			array(
				'label' => __( 'Footer Nav Menu', 'soledad' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);
		
		$this->add_group_control(
			Group_Control_Typography::get_type(), array(
				'name'     => 'item_typo',
				'label'    => __( 'Typography', 'soledad' ),
				'selector' => '{{WRAPPER}} .pcfooter-navmenu li a',
			)
		);
		
		$this->add_control(
			'color',
			array(
				'label'     => __( 'Text Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array( '{{WRAPPER}} .pcfooter-navmenu li a' => 'color: {{VALUE}};' ),
			)
		);
		
		$this->add_control(
			'hcolor',
			array(
				'label'     => __( 'Hover & Active Text Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array( '{{WRAPPER}} .pcfooter-navmenu li a:hover, {{WRAPPER}} .pcfooter-navmenu li.current-menu-item a' => 'color: {{VALUE}};' ),
			)
		);
		
		$this->add_control(
			'sepacolor',
			array(
				'label'     => __( 'Separator Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array( 
					'{{WRAPPER}} .pcfoot-navmenu > li:after' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .pcfnm-sepa-circle .pcfoot-navmenu > li:after' => 'background-color: {{VALUE}};'
				),
				'condition' => array( 'separator!' => 'none' ),
			)
		);
		
		$this->add_control(
			'addbg', array(
				'label'        => __( 'Add Background for Menu Items?', 'soledad' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'soledad' ),
				'label_off'    => __( 'No', 'soledad' ),
				'return_value' => 'yes',
				'default'      => '',
				'separator' => 'before',
			)
		);
		
		$this->add_control(
			'bgcolor',
			array(
				'label'     => __( 'Background Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array( '{{WRAPPER}} .pcfnm-bgitems .pcfoot-navmenu li a' => 'background-color: {{VALUE}};' ),
				'condition' => array( 'addbg' => 'yes' ),
			)
		);
		
		$this->add_control(
			'hbgcolor',
			array(
				'label'     => __( 'Hover & Active Background Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'condition' => array( 'addbg' => 'yes' ),
				'selectors' => array( '{{WRAPPER}} .pcfnm-bgitems .pcfoot-navmenu li a:hover, {{WRAPPER}} .pcfnm-bgitems .pcfoot-navmenu li.current-menu-item a' => 'background-color: {{VALUE}};' ),
			)
		);
		
		$this->add_control(
			'addborders', array(
				'label'        => __( 'Add Borders for Menu Items?', 'soledad' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'soledad' ),
				'label_off'    => __( 'No', 'soledad' ),
				'return_value' => 'yes',
				'default'      => '',
				'separator' => 'before',
			)
		);
		
		$this->add_responsive_control(
			'items_bwidth', array(
				'label'      => __( 'Custom Borders Width', 'soledad' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .pcfnm-bditems .pcfoot-navmenu li a' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				),
				'condition' => array( 'addborders' => 'yes' ),
			)
		);
		
		$this->add_control(
			'borderscolor',
			array(
				'label'     => __( 'Borders Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array( '{{WRAPPER}} .pcfnm-bditems .pcfoot-navmenu li a' => 'border-color: {{VALUE}};' ),
				'condition' => array( 'addborders' => 'yes' ),
			)
		);
		
		$this->add_control(
			'hborderscolor',
			array(
				'label'     => __( 'Hover & Active Borders Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'condition' => array( 'addborders' => 'yes' ),
				'selectors' => array( '{{WRAPPER}} .pcfnm-bditems .pcfoot-navmenu li a:hover, {{WRAPPER}} .pcfnm-bditems .pcfoot-navmenu li.current-menu-item a' => 'border-color: {{VALUE}};' ),
			)
		);
		
		$this->add_responsive_control(
			'items_padding', array(
				'label'      => __( 'Menu Items Custom Padding', 'soledad' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .pcfnm-bditems .pcfoot-navmenu li a, {{WRAPPER}} .pcfnm-bgitems .pcfoot-navmenu li a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				),
				'separator' => 'before',
				'conditions' => [
					'relation' => 'or',
					'terms' => [
						[
							'name' => 'addbg',
							'operator' => '==',
							'value' => 'yes'
						],
						[
							'name' => 'addborders',
							'operator' => '==',
							'value' => 'yes'
						]
					]
				]
			)
		);
		
		$this->end_controls_section();

	}

	protected function render() {
		$available_menus = $this->get_available_menus();

		if ( ! $available_menus ) {
			return;
		}
		
		$settings = $this->get_settings();
		$wrapper_class = 'pcfooter-navmenu';
		$menu_id = $settings['menu'] ? $settings['menu'] : '';
		$wrapper_class .= $settings['separator'] ? ' pcfnm-sepa-' . $settings['separator'] : ' pcfnm-sepa-none';
		if( 'yes' == $settings['addbg'] ){
			$wrapper_class .= ' pcfnm-bgitems';
		}
		if( 'yes' == $settings['addborders'] ){
			$wrapper_class .= ' pcfnm-bditems';
		}
		?>
		<div class="<?php echo $wrapper_class; ?>">
			<?php
			wp_nav_menu( array(
				'menu'            => $menu_id,
				'container'      => false,
				'menu_class'     => 'pcfoot-navmenu',
				'fallback_cb'    => 'penci_menu_fallback',
			) );
			?>
		</div>
		<?php
	}
}
