<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}


class PenciHeaderMenu extends \Elementor\Widget_Base {

	public function get_title() {
		return esc_html__( 'Header - Menu', 'soledad' );
	}

	public function get_icon() {
		return 'eicon-nav-menu';
	}

	public function get_categories() {
		return [ 'penci-header-builder' ];
	}

	public function get_keywords() {
		return [ 'menu' ];
	}

	public function get_name() {
		return 'penci-header-menu';
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

		$this->add_control( 'header_padding', [
			'label' => esc_html__( 'Disable Padding', 'soledad' ),
			'type'  => \Elementor\Controls_Manager::SWITCHER,
		] );
		
		$this->add_control( 'header_vertical_mode', [
			'label' => esc_html__( 'Vertical Mode', 'soledad' ),
			'type'  => \Elementor\Controls_Manager::SWITCHER,
		] );

		$this->add_control( 'penci_header_menu_style', [
			'label'   => esc_html__( 'Sub Menu Style', 'soledad' ),
			'type'    => \Elementor\Controls_Manager::SELECT,
			'default' => 'style-1',
			'options' => [
				'style-1'   => __( 'Style 1', 'soledad' ),
				'style-3'   => __( 'Style 2', 'soledad' ),
				'style-2'   => __( 'Style 3', 'soledad' ),
			]
		] );

		$this->add_control( 'header_remove_line_hover', [
			'label'       => esc_html__( 'Hide Line When Hover on Menu Items Level 1', 'soledad' ),
			'type'        => \Elementor\Controls_Manager::SWITCHER,
			'condition'   => [ 'penci_header_menu_style' => 'style-3' ],
		] );

		$this->end_controls_section();

		// style
		$this->start_controls_section( 'content_style', [
			'label' => esc_html__( 'Style', 'soledad' ),
			'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
		] );

		$this->add_control( 'menu_nav_main_head', [
			'label' => esc_html__( 'Main Menu Items', 'soledad' ),
			'type'  => \Elementor\Controls_Manager::HEADING,
		] );

		$this->add_group_control( \Elementor\Group_Control_Typography::get_type(), array(
			'name'     => 'menu_item_typo',
			'label'    => __( 'Typography', 'soledad' ),
			'selector' => '{{WRAPPER}} .navigation .menu > li > a',
		) );

		$this->add_control( 'menu_item_height', [
			'label'     => esc_html__( 'Menu Item Height', 'soledad' ),
			'type'      => \Elementor\Controls_Manager::SLIDER,
			'selectors' => [ '{{WRAPPER}} .navigation .menu > li > a' => 'line-height: {{SIZE}}px' ]
		] );
		
		$this->add_control( 'menu_item_color', [
			'label'     => esc_html__( 'Menu Color', 'soledad' ),
			'type'      => \Elementor\Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .navigation .menu > li > a, {{WRAPPER}} .navigation ul.menu ul.sub-menu a' => 'color: {{VALUE}}' ]
		] );

		$this->add_control( 'menu_item_hvcolor', [
			'label'     => esc_html__( 'Menu Hover Color', 'soledad' ),
			'type'      => \Elementor\Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .navigation.menu-item-padding .menu > li.current-menu-ancestor > a, {{WRAPPER}} .navigation.menu-item-padding .menu > li.current-menu-item > a, {{WRAPPER}} .navigation.menu-item-padding .menu > li.current_page_item > a, {{WRAPPER}} .navigation.menu-item-padding .menu > li:hover > a, {{WRAPPER}} .navigation.menu-item-padding .menu > li > a:hover' => 'color: {{VALUE}}' ]
		] );
		
		$this->add_control( 'menu_item_crbgcolor', [
			'label'     => esc_html__( 'Menu Current Background Color', 'soledad' ),
			'type'      => \Elementor\Controls_Manager::COLOR,
			'condition' => ['header_padding!'=>'yes'],
			'selectors' => [ '{{WRAPPER}} .navigation.menu-item-padding .menu > li.current-menu-ancestor > a, {{WRAPPER}} .navigation.menu-item-padding .menu > li.current-menu-item > a, {{WRAPPER}} .navigation.menu-item-padding .menu > li.current_page_item > a, {{WRAPPER}} .navigation.menu-item-padding .menu > li:hover > a, {{WRAPPER}} .navigation.menu-item-padding .menu > li > a:hover' => 'background: {{VALUE}}' ]
		] );
		
		$this->add_control( 'menu_item_crtxtcolor', [
			'label'     => esc_html__( 'Menu Current Text Color', 'soledad' ),
			'type'      => \Elementor\Controls_Manager::COLOR,
			'condition' => ['header_padding!'=>'yes'],
			'selectors' => [ '{{WRAPPER}} .navigation.menu-item-padding .menu > li.current-menu-ancestor > a, {{WRAPPER}} .navigation.menu-item-padding .menu > li.current-menu-item > a, {{WRAPPER}} .navigation.menu-item-padding .menu > li.current_page_item > a, {{WRAPPER}} .navigation.menu-item-padding .menu > li:hover > a, {{WRAPPER}} .navigation.menu-item-padding .menu > li > a:hover' => 'color: {{VALUE}}' ]
		] );

		$this->add_responsive_control( 'menu_item_cpadding', [
			'label'     => esc_html__( 'Custom Padding', 'soledad' ),
			'type'      => \Elementor\Controls_Manager::SLIDER,
			'selectors' => [ '{{WRAPPER}} .navigation.menu-item-padding .menu > ul > li > a, {{WRAPPER}} .navigation.menu-item-padding ul.menu > li > a' => 'padding-left: {{SIZE}}px;padding-right: {{SIZE}}px' ]
		] );
		
		$this->add_responsive_control( 'menu_item_bdcolor', [
			'label'     => esc_html__( 'Border Color', 'soledad' ),
			'type'      => \Elementor\Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .navigation ul.menu > li' => 'border-color: {{VALUE}}px;' ]
		] );
		
		$this->add_responsive_control( 'menu_item_bdwidth', [
			'label'     => esc_html__( 'Border Width', 'soledad' ),
			'type'      => \Elementor\Controls_Manager::DIMENSIONS,
			'selectors' => [ '{{WRAPPER}} .navigation ul.menu > li' => 'border-width: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;' ],
		] );

		$this->add_control( 'menu_nav_sub_head', [
			'label' => esc_html__( 'Sub Menu Items', 'soledad' ),
			'type'  => \Elementor\Controls_Manager::HEADING,
		] );

		$this->add_group_control( \Elementor\Group_Control_Typography::get_type(), array(
			'name'     => 'sub_menu_item_typo',
			'label'    => __( 'Sub Menu Item Typography', 'soledad' ),
			'selector' => '{{WRAPPER}} .navigation ul.menu ul.sub-menu li a',
		) );

		$this->add_control( 'menu_item_subbg', [
			'label'     => esc_html__( 'Sub Menu Background Color', 'soledad' ),
			'type'      => \Elementor\Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .navigation .menu .children, {{WRAPPER}} .navigation .menu .sub-menu' => 'background: {{VALUE}}' ]
		] );

		$this->add_control( 'menu_item_style_btcolor', [
			'label'     => esc_html__( 'Menu Style 2 Border Top Color', 'soledad' ),
			'type'      => \Elementor\Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .navigation.menu-style-2 ul.menu ul.sub-menu:before' => 'background: {{VALUE}}' ]
		] );
		
		$this->add_control( 'menu_item_subcls', [
			'label'     => esc_html__( 'Sub Menu Item Color', 'soledad' ),
			'type'      => \Elementor\Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .navigation ul.menu ul.sub-menu > li > a' => 'color: {{VALUE}}' ]
		] );
		
		$this->add_control( 'menu_item_subhvcls', [
			'label'     => esc_html__( 'Sub Menu Item Hover Color', 'soledad' ),
			'type'      => \Elementor\Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .navigation ul.menu ul.sub-menu > li > a:hover' => 'color: {{VALUE}}' ]
		] );

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

		$menu_id = $settings['header_menu'] ? $settings['header_menu'] : '';
		if ( is_page() ) {
			$pmeta_page_header = get_post_meta( get_the_ID(), 'penci_pmeta_page_header', true );
			if ( isset( $pmeta_page_header['main_nav_menu'] ) && $pmeta_page_header['main_nav_menu'] ) {
				$menu_id = $pmeta_page_header['main_nav_menu'];
			}
		}
		$classes = [];

		if ( $settings['header_vertical_mode'] ) {
			$classes[] = 'pchdvertical-enabled';
		}

		$classes[] = 'navigation pc-elehd-menu';
		$classes[] = 'menu-' . $settings['penci_header_menu_style'];
		$classes[] = $settings['header_padding'] ? 'menu-item-normal' : 'menu-item-padding';
		$classes[] = $settings['header_remove_line_hover'] ? 'pcremove-lineh' : '';
		?>
        <nav class="<?php echo implode( ' ', $classes ); ?>" role="navigation"
             itemtype="https://schema.org/SiteNavigationElement">
			<?php
			wp_nav_menu( array(
				'menu'        => $menu_id,
				'container'   => false,
				'menu_class'  => 'menu',
				'fallback_cb' => 'penci_menu_fallback',
				'walker'      => new penci_menu_builder_walker_nav_menu()
			) );
			?>
        </nav>
		<?php
	}
}