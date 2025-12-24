<?php

namespace PenciSoledadElementor\Modules\PenciAdvancedGmaps\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Css_Filter;
use Elementor\Group_Control_Typography;
use Elementor\Repeater;
use Elementor\Utils;
use PenciSoledadElementor\Base\Base_Widget;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Exit if accessed directly

class PenciAdvancedGmaps extends Base_Widget {

	public function get_name() {
		return 'penci-advanced-gmap';
	}

	public function get_title() {
		return penci_get_theme_name( 'Penci' ) . ' ' . esc_html__( ' Advanced Google Maps', 'soledad' );
	}

	public function get_icon() {
		return 'eicon-google-maps';
	}

	public function get_categories() {
		return [ 'penci-elements' ];
	}

	public function get_keywords() {
		return [ 'advanced', 'gmap', 'location' ];
	}

	public function get_script_depends() {
		return [ 'google-map', 'penci-amap' ];
	}

	protected function is_dynamic_content(): bool {
		return true;
	}

	protected function register_controls() {
		$this->start_controls_section(
			'section_content_gmap',
			[
				'label' => esc_html__( 'Google Map', 'soledad' ),
			]
		);

		$this->add_control(
			'avd_google_map_default_zoom',
			[
				'label'   => esc_html__( 'Default Zoom', 'soledad' ),
				'type'    => Controls_Manager::SLIDER,
				'default' => [
					'size' => 15,
				],
				'range'   => [
					'px' => [
						'min' => 1,
						'max' => 24,
					],
				],
			]
		);

		$this->add_responsive_control(
			'avd_google_map_height',
			[
				'label'     => esc_html__( 'Map Height', 'soledad' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'max' => 1000,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .penci-advanced-map' => '--mheight: {{SIZE}}{{UNIT}}',
				],
			]
		);
		$this->add_control(
			'avd_google_map_show_list',
			[
				'label'     => esc_html__( 'Show List', 'soledad' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => 'no',
				'separator' => 'before',
			]
		);
		$this->add_control(
			'avd_google_map_list_position',
			[
				'label'     => esc_html__( 'List Position', 'soledad' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'right',
				'options'   => [
					'left'  => esc_html__( 'Left', 'soledad' ),
					'right' => esc_html__( 'Right', 'soledad' ),
				],
				'condition' => [
					'avd_google_map_show_list' => 'yes',
				],
			]
		);

		$this->add_control(
			'gmap_geocode',
			[
				'label'     => esc_html__( 'Search Address', 'soledad' ),
				'type'      => Controls_Manager::SWITCHER,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'search_placeholder_text',
			[
				'label'     => esc_html__( 'Placeholder Text', 'soledad' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => esc_html__( 'Search...', 'soledad' ),
				'condition' => [
					'gmap_geocode' => 'yes'
				]
			]
		);

		$this->add_responsive_control(
			'search_align',
			[
				'label'     => esc_html__( 'Alignment', 'soledad' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => [
					'left'   => [
						'title' => esc_html__( 'Left', 'soledad' ),
						'icon'  => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'soledad' ),
						'icon'  => 'eicon-text-align-center',
					],
					'right'  => [
						'title' => esc_html__( 'Right', 'soledad' ),
						'icon'  => 'eicon-text-align-right',
					],
				],
				'selectors' => [
					'{{WRAPPER}} .penci-gmap-search-wrapper' => 'text-align: {{VALUE}};',
				],
				'condition' => [
					'gmap_geocode' => 'yes',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_content_marker',
			[
				'label' => esc_html__( 'Locations', 'soledad' ),
			]
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'marker_title',
			[
				'label'       => esc_html__( 'Title', 'soledad' ),
				'label_block' => true,
				'type'        => Controls_Manager::TEXT,
				'dynamic'     => [ 'active' => true ],
			]
		);
		$repeater->add_control(
			'marker_place',
			[
				'label'       => esc_html__( 'Place', 'soledad' ),
				'type'        => Controls_Manager::TEXTAREA,
				'label_block' => true,
				'dynamic'     => [ 'active' => true ],
			]
		);
		$repeater->add_control(
			'marker_phone',
			[
				'label'       => esc_html__( 'Phone', 'soledad' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'dynamic'     => [ 'active' => true ],
			]
		);

		$this->add_control(
			'marker',
			[
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'default'     => [
					[
						'marker_title'   => esc_html__( 'PenciDesign', 'soledad' ),
						'marker_place'   => esc_html__( '551 Swanston St Carlton VIC 3053, Australia ', 'soledad' ),
						'marker_phone'   => esc_html__( '+12345678', 'soledad' ),
						'marker_website' => esc_html__( 'https://soledad.pencidesign.net/', 'soledad' ),
					],
				],
				'title_field' => '{{{ marker_title }}}',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_search',
			[
				'label'     => esc_html__( 'Search', 'soledad' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'gmap_geocode' => 'yes',
				],
			]
		);

		$this->add_control(
			'search_background',
			[
				'label'     => esc_html__( 'Background', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .penci-search.penci-search-default .penci-search-input' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'search_color',
			[
				'label'     => esc_html__( 'Text Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .penci-search.penci-search-default .penci-search-input' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'search_placeholder_color',
			[
				'label'     => esc_html__( 'Placeholder Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .penci-search.penci-search-default .penci-search-input::placeholder' => 'color: {{VALUE}};',
					'{{WRAPPER}} .penci-search.penci-search-default span'                             => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'search_shadow',
				'selector' => '{{WRAPPER}} .penci-search.penci-search-default .penci-search-input',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'        => 'search_border',
				'label'       => esc_html__( 'Border', 'soledad' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} .penci-search.penci-search-default .penci-search-input',
			]
		);

		$this->add_responsive_control(
			'search_border_radius',
			[
				'label'      => esc_html__( 'Radius', 'soledad' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .penci-search.penci-search-default .penci-search-input' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; overflow: hidden;',
				],
			]
		);

		$this->add_responsive_control(
			'search_padding',
			[
				'label'      => esc_html__( 'Padding', 'soledad' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .penci-search.penci-search-default .penci-search-input' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'search_margin',
			[
				'label'      => esc_html__( 'Margin', 'soledad' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .penci-search.penci-search-default .penci-search-input' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition'  => [
					'avd_google_map_show_list!' => 'yes',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'searh_typography',
				'label'    => esc_html__( 'Typography', 'soledad' ),
				'selector' => '{{WRAPPER}} .penci-search.penci-search-default .penci-search-input',
			]
		);

		$this->end_controls_section();
		$this->start_controls_section(
			'section_content_list',
			[
				'label'     => esc_html__( 'Map List', 'soledad' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'avd_google_map_show_list' => 'yes',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'map_list_background',
				'label'    => esc_html__( 'Backgrund', 'soledad' ),
				'types'    => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .penci-gmap-lists-wrapper',
			]
		);
		$this->add_control(
			'list_item_bd_color',
			[
				'label'     => esc_html__( 'Border Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .penci-gmap-list-item, {{WRAPPER}} .penci-advanced-map .penci-gmap-lists-wrapper, {{WRAPPER}} .penci-gmap-lists-wrapper .penci-gmap-search-wrapper' => 'border-color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'list_item_separator_color',
			[
				'label'     => esc_html__( 'Separator Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .penci-advanced-map .penci-gmap-list-item' => 'border-color: {{VALUE}}',
				],
			]
		);
		$this->add_responsive_control(
			'list_item_separator_width',
			[
				'label'     => esc_html__( 'Separator Height', 'soledad' ),
				'type'      => Controls_Manager::SLIDER,
				'selectors' => [
					'{{WRAPPER}} .penci-advanced-map .penci-gmap-list-item' => 'border-width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->start_controls_tabs(
			'list_item_tabs'
		);
		$this->start_controls_tab(
			'list_item_tab_title',
			[
				'label' => esc_html__( 'Title', 'soledad' ),
			]
		);
		$this->add_control(
			'list_item_title_color',
			[
				'label'     => esc_html__( 'Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .penci-advanced-map .penci-gmap-list-content .penci-title' => 'color: {{VALUE}}',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'list_item_title_typography',
				'label'    => esc_html__( 'Typography', 'soledad' ),
				'selector' => '{{WRAPPER}} .penci-advanced-map .penci-gmap-list-content .penci-title',
			]
		);

		$this->end_controls_tab();
		$this->start_controls_tab(
			'list_item_tab_place',
			[
				'label' => esc_html__( 'Place', 'soledad' ),
			]
		);
		$this->add_control(
			'list_item_place_color',
			[
				'label'     => esc_html__( 'Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .penci-advanced-map .penci-gmap-list-content .penci-place' => 'color: {{VALUE}}',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'list_item_place_typography',
				'label'    => esc_html__( 'Typography', 'soledad' ),
				'selector' => '{{WRAPPER}} .penci-advanced-map .penci-gmap-list-content .penci-place',
			]
		);
		$this->end_controls_tab();
		$this->start_controls_tab(
			'list_item_tab_phone',
			[
				'label' => esc_html__( 'Phone', 'soledad' ),
			]
		);
		$this->add_control(
			'list_item_phone_color',
			[
				'label'     => esc_html__( 'Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .penci-advanced-map .penci-gmap-list-content .penci-phone' => 'color: {{VALUE}}',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'list_item_phone_typography',
				'label'    => esc_html__( 'Typography', 'soledad' ),
				'selector' => '{{WRAPPER}} .penci-advanced-map .penci-gmap-list-content .penci-phone',
			]
		);
		$this->end_controls_tab();
		$this->end_controls_tabs();
		$this->end_controls_section();
	}

	public function render() {
		$settings = $this->get_settings_for_display();
		$id       = 'penci-advanced-gmap-' . $this->get_id() . '-' . rand( 10, 100 );

		if ( $settings['gmap_geocode'] === 'yes' && $settings['avd_google_map_show_list'] !== 'yes' ): ?>

            <div class="penci-gmap-search-wrapper penci-margin">
                <form method="post" id="<?php echo esc_attr( $id ); ?>form" class="penci-search penci-search-default">
                    <span data-penci-search-icon></span>
                    <input id="<?php echo esc_attr( $id ); ?>address" name="address" class="penci-search-input"
                           type="search"
                           placeholder="<?php echo ! empty( $settings['search_placeholder_text'] ) ? esc_html( $settings['search_placeholder_text'] ) : 'Search...'; ?>">
                </form>
            </div>

		<?php endif;
		$this->add_render_attribute( 'penci-advanced-map', 'class', 'penci-advanced-map' );
		if ( ( $settings['avd_google_map_show_list'] == 'yes' ) ):
			$this->add_render_attribute( 'penci-advanced-map', 'class', [ 'penci-direction-' . $settings['avd_google_map_list_position'] . '' ] );
		endif;
		if ( ( $settings['avd_google_map_show_list'] == 'yes' ) && ( $settings['gmap_geocode'] == 'yes' ) ):
			$this->add_render_attribute( 'penci-advanced-map', 'class', [ 'penci-has-lists-search-' . $settings['avd_google_map_show_list'] . '' ] );
		endif;
		?>
        <div <?php $this->print_render_attribute_string( 'penci-advanced-map' ); ?>>
            <div class="penci-grid-wrap">
                <div class="penci-advanced-map-wrapper">
					<?php
					$map_zoom = isset( $settings['avd_google_map_default_zoom']['size'] ) ? (int) $settings['avd_google_map_default_zoom']['size'] : 15;
					$map_type = 'road';
					foreach ( $settings['marker'] as $map_list => $marker_item ) {
						$mapclass    = 0 == $map_list ? 'active' : 'inactive';
						$block_id    = 'penci_map_a_' . $map_list;
						$map_address = isset( $marker_item['marker_place'] ) ? $marker_item['marker_place'] : '';
						$params      = [
							rawurlencode( $map_address ),
							absint( $map_zoom ),
							esc_attr( $map_type ),
						];
						$map_url     = 'https://maps.google.com/maps?q=%1$s&amp;t=m&amp;z=%2$d&amp;output=embed&amp;iwloc=near&amp;maptype=%3$s';
						$map_url     = esc_url( vsprintf( $map_url, $params ) );
						$css_class   = 'penci-block-vc penci-lazy no-api';
						printf( '<div class="' . $mapclass . ' penci_admap_item penci_admap_' . $map_list . '"><iframe id="%s" class="%s" data-src="%s"></iframe></div>', $block_id, $css_class, $map_url );
					}
					?>
                </div>
				<?php if ( $settings['avd_google_map_show_list'] === 'yes' ): ?>
                    <div class="penci-gmap-lists-wrapper">
						<?php if ( $settings['gmap_geocode'] === 'yes' ): ?>
                            <div class="penci-gmap-search-wrapper">
                                <form class="penci-search penci-search-default">
                                    <div class="search-box">
                                        <input type="text"
                                               placeholder="<?php echo ! empty( $settings['search_placeholder_text'] ) ? esc_html( $settings['search_placeholder_text'] ) : 'Search Places'; ?>"
                                               class="penci-search-input"/>
                                    </div>
                                </form>
                            </div>
						<?php endif; ?>
                        <ul class="penci-gmap-lists">
							<?php
							foreach ( $settings['marker'] as $index => $marker_item ) {
								?>
                                <div class="penci-gmap-list-item" data-index="penci_admap_<?php echo $index; ?>">
                                    <div class="penci-gmap-list-content">
                                        <h5 class="penci-title"><?php echo esc_html__( $marker_item['marker_title'], 'soledad' ); ?></h5>
                                        <span class="penci-place"><?php echo esc_html__( $marker_item['marker_place'], 'soledad' ); ?></span>
										<?php if ( isset($marker_item['marker_phone']) && $marker_item['marker_phone'] ) : ?>
                                        <span class="penci-phone"><?php echo esc_html__( $marker_item['marker_phone'], 'soledad' ); ?></span>
										<?php endif; ?>
                                    </div>
                                </div>
								<?php
							};
							?>
                        </ul>
                    </div>
				<?php endif; ?>
            </div>
        </div>
		<?php
	}
}