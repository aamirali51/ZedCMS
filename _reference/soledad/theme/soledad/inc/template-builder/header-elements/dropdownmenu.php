<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}


class PenciHeaderDropdownmenu extends \Elementor\Widget_Base {

	public function get_title() {
		return esc_html__( 'Header - Dropdown Menu', 'soledad' );
	}

	public function get_icon() {
		return 'eicon-nav-menu';
	}

	public function get_categories() {
		return [ 'penci-header-builder' ];
	}

	public function get_keywords() {
		return [ 'menu', 'dropdown' ];
	}

	public function get_name() {
		return 'penci-header-dropdown-menu';
	}

	protected function register_controls() {

		$this->start_controls_section( 'content_section', [
			'label' => esc_html__( 'General', 'soledad' ),
			'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
		] );


		$menus = $this->get_available_menus();

		if ( ! empty( $menus ) ) {
			$this->add_control(
				'header_menu',
				[
					'label'        => __( 'Menu', 'soledad' ),
					'type'         => \Elementor\Controls_Manager::SELECT,
					'options'      => $menus,
					'default'      => array_keys( $menus )[0],
					'save_default' => true,
					'separator'    => 'after',
					'description'  => sprintf( __( 'Go to the <a href="%s" target="_blank">Menus screen</a> to manage your menus. Please note that: The Footer Nav Menu does not support showing sub-menu items. All sub-menus will be hidden.', 'soledad' ), admin_url( 'nav-menus.php' ) ),
				]
			);
		} else {
			$this->add_control(
				'header_menu',
				[
					'type'            => \Elementor\Controls_Manager::RAW_HTML,
					'raw'             => '<strong>' . __( 'There are no menus in your site.', 'soledad' ) . '</strong><br>' . sprintf( __( 'Go to the <a href="%s" target="_blank">Menus screen</a> to create one.', 'soledad' ), admin_url( 'nav-menus.php?action=edit&menu=0' ) ),
					'separator'       => 'after',
					'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
				]
			);
		}
		
		$this->add_control( 'vernav_click_parent', [
			'label'     => esc_html__( 'Enable click on Parent Menu Item to open Child Menu Items', 'soledad' ),
			'description' => __('By default, you need to click to the arrow on the right side to open child menu items - this option will help you click on the parent menu items to open child menu items','soledad' ),
			'type'      => \Elementor\Controls_Manager::SWITCHER,
		] );

		$this->end_controls_section();

		// style
		$this->start_controls_section( 'content_style', [
			'label' => esc_html__( 'Menu Colors & Typo', 'soledad' ),
			'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
		] );

		$this->add_group_control( \Elementor\Group_Control_Typography::get_type(), array(
			'name'     => 'fontsize_lv1',
			'label'    => __( 'Parent Menu Items', 'soledad' ),
			'selector' => '{{WRAPPER}} .pc-builder-menu.pc-dropdown-menu .menu li > a',
		) );
		
		$this->add_group_control( \Elementor\Group_Control_Typography::get_type(), array(
			'name'     => 'fontsize_dropdown',
			'label'    => __( 'Dropdown Menu Items', 'soledad' ),
			'selector' => '{{WRAPPER}} .pc-builder-menu.pc-dropdown-menu .menu li li a',
		) );
		
		$this->add_control(
			'menu_color',
			array(
				'label'      => 'Menu Item Color',
				'type'       => \Elementor\Controls_Manager::COLOR,
				'selectors'  => array(
					'{{WRAPPER}} .pc-builder-menu.pc-dropdown-menu .menu li a' => 'color: {{VALUE}};',
				),
			)
		);
		
		$this->add_control(
			'menu_hcolor',
			array(
				'label'      => 'Menu Item Hover Color',
				'type'       => \Elementor\Controls_Manager::COLOR,
				'selectors'  => array(
					'{{WRAPPER}} .pc-builder-menu.pc-dropdown-menu .menu li a:hover,{{WRAPPER}} .pc-builder-menu.pc-dropdown-menu .menu > li.current_page_item > a' => 'color: {{VALUE}};',
				),
			)
		);
		
		$this->add_control(
			'menu_rcolor',
			array(
				'label'      => 'Current Menu Item Color',
				'type'       => \Elementor\Controls_Manager::COLOR,
				'selectors'  => array(
					'{{WRAPPER}} .pc-builder-menu.pc-dropdown-menu .menu > li.current_page_item > a' => 'color: {{VALUE}};',
				),
			)
		);
		
		$this->add_control(
			'menu_bdcolor',
			array(
				'label'      => 'Menu Item Border Color',
				'type'       => \Elementor\Controls_Manager::COLOR,
				'selectors'  => array(
					'{{WRAPPER}} .pchgbel .menu li,{{WRAPPER}} .pchgbel .menu li ul.sub-menu' => 'border-color: {{VALUE}};',
				),
			)
		);
		
		$this->add_responsive_control(
			'menu_spacing',
			array(
				'label'      => 'Menu Item Spacing',
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'range'      => array( 'px' => array( 'min' => 0, 'max' => 100, ) ),
				'selectors'  => array(
					'{{WRAPPER}} .pchgbel .menu li a' => 'padding-top: {{SIZE}}px;padding-bottom: {{SIZE}}px;',
				),
			)
		);

		$this->end_controls_section();

	}

	private function get_available_menus() {
		$menus = wp_get_nav_menus();

		$options = [];

		foreach ( $menus as $menu ) {
			$options[ $menu->slug ] = $menu->name;
		}

		return $options;
	}

	protected function render() {

		$available_menus = $this->get_available_menus();

		if ( ! $available_menus ) {
			return;
		}

		$settings = $this->get_settings();

		$menu_id      = $settings['header_menu'] ? $settings['header_menu'] : '';
		$parent_click = $settings['vernav_click_parent'];
		$classes      = [];
		$classes[]    = penci_get_builder_mod( 'penci_header_pb_dropdown_menu_class', 'no-class' );
		$classes[]    = $parent_click ? 'penci-vernav-cparent pchb-cparent' : 'normal-click';
		add_filter( 'theme_mod_penci_vernav_click_parent', function ( $value ) use ($parent_click) {
			if ( $parent_click ) {
				$value = true;
			}

			return $value;
		} );
		?>
		<div class="penci-menu-hbg-ele pchgbel">
			<div class="pc-builder-element pc-builder-menu pc-dropdown-menu">
				<nav class="<?php echo implode( ' ', $classes ); ?>" role="navigation"
					<?php if ( ! penci_get_builder_mod( 'penci_schema_sitenav' ) ): ?>itemscope
					itemtype="https://schema.org/SiteNavigationElement"<?php endif; ?>>
					<?php
					$args = array(
						'container'   => false,
						'menu_class'  => 'menu menu-hgb-main',
						'fallback_cb' => 'penci_menu_fallback',
						'walker'      => new penci_menu_builder_walker_nav_menu()
					);
					if ( $menu_id ) {
						$args['menu'] = $menu_id;
					} else {
						$args['location'] = 'primary-menu';
					}
					wp_nav_menu( $args );
					?>
				</nav>
			</div>
		</div>
		<?php

	}
}