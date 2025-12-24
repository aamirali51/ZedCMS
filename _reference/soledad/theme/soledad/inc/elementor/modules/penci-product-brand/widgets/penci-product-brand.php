<?php

namespace PenciSoledadElementor\Modules\PenciProductBrand\Widgets;

use Elementor\Controls_Manager;
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
class PenciProductBrand extends Base_Widget {
	/**
	 * Get widget name.
	 *
	 * @return string Widget name.
	 * @since 1.0.0
	 * @access public
	 *
	 */
	public function get_name() {
		return 'penci_products_brands';
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
		return penci_get_theme_name( 'Penci' ) . ' ' . esc_html__( ' Product Brands', 'soledad' );
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
		return 'eicon-logo';
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
		 * Content tab.
		 */

		/**
		 * General settings.
		 */
		$this->start_controls_section( 'general_content_section', [
			'label' => esc_html__( 'General', 'soledad' ),
		] );

		$this->add_control( 'number', [
			'label'       => esc_html__( 'Number', 'soledad' ),
			'description' => esc_html__( 'Enter the number of categories to display for this element.', 'soledad' ),
			'type'        => Controls_Manager::NUMBER,
		] );

		$this->add_control( 'orderby', [
			'label'   => esc_html__( 'Order by', 'soledad' ),
			'type'    => Controls_Manager::SELECT,
			'default' => '',
			'options' => array(
				''        => '',
				'name'    => esc_html__( 'Name', 'soledad' ),
				'term_id' => esc_html__( 'ID', 'soledad' ),
				'slug'    => esc_html__( 'Slug', 'soledad' ),
				'random'  => esc_html__( 'Random order', 'soledad' ),
			),
		] );

		$this->add_control( 'order', [
			'label'       => esc_html__( 'Sort order', 'soledad' ),
			'description' => 'Designates the ascending or descending order. More at <a href="http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters" target="_blank">WordPress codex page</a>.',
			'type'        => Controls_Manager::SELECT,
			'default'     => '',
			'options'     => array(
				''     => esc_html__( 'Inherit', 'soledad' ),
				'DESC' => esc_html__( 'Descending', 'soledad' ),
				'ASC'  => esc_html__( 'Ascending', 'soledad' ),
			),
		] );

		$this->add_control( 'hide_empty', [
			'label'        => esc_html__( 'Hide empty', 'soledad' ),
			'type'         => Controls_Manager::SWITCHER,
			'default'      => '0',
			'label_on'     => esc_html__( 'Yes', 'soledad' ),
			'label_off'    => esc_html__( 'No', 'soledad' ),
			'return_value' => '1',
		] );

		$this->add_control( 'ids', [
			'label'       => esc_html__( 'Brands', 'soledad' ),
			'description' => esc_html__( 'List of product brands to show. Leave empty to show all.', 'soledad' ),
			'type'        => 'penci_el_autocomplete',
			'search'      => 'penci_get_taxonomies_by_query',
			'render'      => 'penci_get_taxonomies_title_by_id',
			'taxonomy'    => 'pa_brand',
			'multiple'    => true,
			'label_block' => true,
		] );

		$this->end_controls_section();

		/**
		 * Title settings.
		 */

		$this->register_block_title_section_controls();

		/**
		 * Style tab.
		 */

		/**
		 * General settings.
		 */
		$this->start_controls_section( 'general_style_section', [
			'label' => esc_html__( 'General', 'soledad' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_control( 'hover', [
			'label'   => esc_html__( 'Brand images hover', 'soledad' ),
			'type'    => Controls_Manager::SELECT,
			'default' => 'default',
			'options' => array(
				'default' => esc_html__( 'Style 1', 'soledad' ),
				'simple'  => esc_html__( 'Style 2', 'soledad' ),
				'alt'     => esc_html__( 'Style 3', 'soledad' ),
			),
		] );

		$this->add_control( 'brand_style', [
			'label'   => esc_html__( 'Border Style ?', 'soledad' ),
			'type'    => Controls_Manager::SELECT,
			'default' => 'default',
			'options' => array(
				'default'  => esc_html__( 'No', 'soledad' ),
				'bordered' => esc_html__( 'Yes', 'soledad' ),
			),
		] );

		$this->add_control( 'style', [
			'label'   => esc_html__( 'Layout', 'soledad' ),
			'type'    => Controls_Manager::SELECT,
			'default' => 'carousel',
			'options' => array(
				'carousel' => esc_html__( 'Carousel', 'soledad' ),
				'grid'     => esc_html__( 'Grid', 'soledad' ),
				'list'     => esc_html__( 'Links List', 'soledad' ),
			),
		] );

		$this->add_control( 'columns', [
			'label'       => esc_html__( 'Columns', 'soledad' ),
			'description' => esc_html__( 'Number of columns in the grid.', 'soledad' ),
			'type'        => Controls_Manager::SLIDER,
			'default'     => [
				'size' => 3,
			],
			'size_units'  => '',
			'range'       => [
				'px' => [
					'min'  => 1,
					'max'  => 6,
					'step' => 1,
				],
			],
			'condition'   => [
				'style' => array( 'grid', 'list' ),
			],
		] );

		$this->end_controls_section();

		/**
		 * Carousel settings.
		 */
		$this->start_controls_section( 'carousel_style_section', [
			'label'     => esc_html__( 'Carousel', 'soledad' ),
			'tab'       => Controls_Manager::TAB_STYLE,
			'condition' => [
				'style' => 'carousel',
			],
		] );

		$this->add_control( 'slides_per_view', [
			'label'       => esc_html__( 'Slides per view', 'soledad' ),
			'description' => esc_html__( 'Set numbers of slides you want to display at the same time on slider\'s container for carousel mode.', 'soledad' ),
			'type'        => Controls_Manager::SLIDER,
			'default'     => [
				'size' => 3,
			],
			'size_units'  => '',
			'range'       => [
				'px' => [
					'min'  => 1,
					'max'  => 8,
					'step' => 1,
				],
			],
		] );

		$this->add_control( 'hide_pagination_control', [
			'label'        => esc_html__( 'Hide pagination control', 'soledad' ),
			'description'  => esc_html__( 'If "YES" pagination control will be removed.', 'soledad' ),
			'type'         => Controls_Manager::SWITCHER,
			'default'      => 'true',
			'label_on'     => esc_html__( 'Yes', 'soledad' ),
			'label_off'    => esc_html__( 'No', 'soledad' ),
			'return_value' => 'false',
			'condition'    => [
				'style' => 'carousel',
			],
		] );

		$this->add_control( 'hide_prev_next_buttons', [
			'label'        => esc_html__( 'Hide prev/next buttons', 'soledad' ),
			'description'  => esc_html__( 'If "YES" prev/next control will be removed', 'soledad' ),
			'type'         => Controls_Manager::SWITCHER,
			'default'      => 'true',
			'label_on'     => esc_html__( 'Yes', 'soledad' ),
			'label_off'    => esc_html__( 'No', 'soledad' ),
			'return_value' => 'false',
			'condition'    => [
				'style' => 'carousel',
			],
		] );

		$this->add_control( 'carousel_slider_effect', array(
			'label'       => __( 'Carousel Slider Effect', 'soledad' ),
			'description' => __( 'The "Swing" effect does not support the loop option.', 'soledad' ),
			'type'        => Controls_Manager::SELECT,
			'default'     => get_theme_mod( 'penci_carousel_slider_effect', 'swing' ),
			'options'     => array(
				'default' => 'Default',
				'swing'   => 'Swing',
			),
		) );

		$this->add_control( 'wrap', [
			'label'        => esc_html__( 'Slider loop', 'soledad' ),
			'type'         => Controls_Manager::SWITCHER,
			'default'      => 'false',
			'label_on'     => esc_html__( 'Yes', 'soledad' ),
			'label_off'    => esc_html__( 'No', 'soledad' ),
			'return_value' => 'true',
			'condition'    => [
				'style' => 'carousel',
			],
		] );

		$this->add_control( 'autoplay', [
			'label'        => esc_html__( 'Slider autoplay', 'soledad' ),
			'type'         => Controls_Manager::SWITCHER,
			'default'      => 'false',
			'label_on'     => esc_html__( 'Yes', 'soledad' ),
			'label_off'    => esc_html__( 'No', 'soledad' ),
			'return_value' => 'true',
		] );

		$this->add_control( 'speed', [
			'label'       => esc_html__( 'Slider speed', 'soledad' ),
			'description' => esc_html__( 'Duration of animation between slides (in ms)', 'soledad' ),
			'default'     => '300',
			'type'        => Controls_Manager::NUMBER,
			'condition'   => [
				'autoplay' => 'yes',
			],
		] );

		$this->add_control( 'scroll_carousel_init', [
			'label'        => esc_html__( 'Init carousel on scroll', 'soledad' ),
			'description'  => esc_html__( 'This option allows you to init carousel script only when visitor scroll the page to the slider. Useful for performance optimization.', 'soledad' ),
			'type'         => Controls_Manager::SWITCHER,
			'default'      => 'false',
			'label_on'     => esc_html__( 'Yes', 'soledad' ),
			'label_off'    => esc_html__( 'No', 'soledad' ),
			'return_value' => 'true',
		] );

		$this->end_controls_section();

		/**
		 * Carousel settings.
		 */
		$this->start_controls_section( 'brand_style_section', [
			'label' => esc_html__( 'Brand Style', 'soledad' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_control( 'brand_text_color', array(
			'label'     => __( 'Brand Text Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => array(
				'{{WRAPPER}} .brands-style-bordered.brands-list .brand-item > a' => 'color: {{VALUE}};',
			),
			'condition' => [
				'style' => 'list',
			],
		) );

		$this->add_group_control( Group_Control_Typography::get_type(), array(
			'name'      => 'brand_text_typo',
			'label'     => __( 'Brand Text Typography', 'soledad' ),
			'selector'  => '{{WRAPPER}} .brands-style-bordered.brands-list .brand-item > a',
			'condition' => [
				'style' => 'list',
			],
		) );

		$this->add_control( 'brand_border_color', array(
			'label'     => __( 'Border Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => array(
				'{{WRAPPER}} .brands-style-bordered .brand-item'                      => 'border-color: {{VALUE}};',
				'{{WRAPPER}} .brands-style-bordered.brands-carousel .owl-stage-outer' => 'border-color: {{VALUE}};',
				'{{WRAPPER}} .brands-style-bordered'                                  => 'border-color: {{VALUE}};',
				'{{WRAPPER}} .brands-style-bordered.brands-list .brand-item > a'      => 'border-bottom-color: {{VALUE}};',
			),
			'condition' => [
				'brand_style' => 'bordered',
			],
		) );

		$this->add_control( 'carousel_border_color', array(
			'label'     => __( 'Carousel Navigation <br> Border Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => array(
				'{{WRAPPER}} .penci-owl-carousel-slider .penci-owl-dot span' => 'border-color: {{VALUE}};',
			),
			'condition' => [
				'style' => 'carousel',
			],
		) );

		$this->add_control( 'carousel_bg_color', array(
			'label'     => __( 'Carousel Navigation <br> Background Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => array(
				'{{WRAPPER}} .penci-owl-carousel-slider .penci-owl-dot span' => 'background-color: {{VALUE}};',
			),
			'condition' => [
				'style' => 'carousel',
			],
		) );

		$this->add_control( 'carousel_border_active_color', array(
			'label'     => __( 'Carousel Navigation <br> Active Border Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => array(
				'{{WRAPPER}} .penci-owl-carousel-slider .penci-owl-dot.active span' => 'border-color: {{VALUE}};',
			),
			'condition' => [
				'style' => 'carousel',
			],
		) );

		$this->add_control( 'carousel_bg_active_color', array(
			'label'     => __( 'Carousel Navigation <br> Active Background Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => array(
				'{{WRAPPER}} .penci-owl-carousel-slider .penci-owl-dot.active span' => 'background-color: {{VALUE}};',
			),
			'condition' => [
				'style' => 'carousel',
			],
		) );

		$this->end_controls_section();

		$this->register_block_title_style_section_controls();
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
			'number'               => 20,
			'hover'                => 'default',
			'target'               => '_self',
			'link'                 => '',
			'ids'                  => '',
			'style'                => 'carousel',
			'brand_style'          => 'default',
			'slides_per_view'      => 3,
			'columns'              => 3,
			'orderby'              => '',
			'hide_empty'           => 0,
			'order'                => 'ASC',
			'scroll_carousel_init' => 'no',
			'custom_sizes'         => apply_filters( 'penci_brands_shortcode_custom_sizes', false ),
		];

		$settings = wp_parse_args( $this->get_settings_for_display(), $default_settings );

		$carousel_id = 'brands_' . rand( 1000, 9999 );

		$attribute = get_theme_mod( 'penci_woocommerce_brand_attr' );

		if ( empty( $attribute ) || ! taxonomy_exists( $attribute ) ) {
			echo '<div class="penci-notice penci-info">' . esc_html__( 'You must select your brand attribute in Appearance -> Customize -> WooCommerce > Brands', 'soledad' ) . '</div>';

			return;
		}

		$settings['columns']         = isset( $settings['columns']['size'] ) ? $settings['columns']['size'] : 3;
		$settings['slides_per_view'] = isset( $settings['slides_per_view']['size'] ) ? $settings['slides_per_view']['size'] : 3;

		$this->add_render_attribute( [
			'wrapper' => [
				'class' => [
					'brands-items-wrapper',
					'brands-widget',
					'slider-' . $carousel_id,
					'brands-hover-' . $settings['hover'],
					'brands-style-' . $settings['brand_style'],
				],
				'id'    => [
					$carousel_id,
				],
			],
			'items'   => [
				'class' => [
					'brand-item',
				],
			],
		] );

		if ( $settings['style'] ) {
			$this->add_render_attribute( 'wrapper', 'class', 'brands-' . $settings['style'] );
		}

		if ( 'carousel' === $settings['style'] ) {
			$settings['scroll_per_page'] = 'yes';
			$settings['carousel_id']     = $carousel_id;

			$this->add_render_attribute( 'items_wrapper', 'class', 'swiper penci-owl-carousel penci-owl-carousel-slider ' );
			$this->add_render_attribute( 'wrapper', 'class', 'penci-carousel-container' );

			$this->add_render_attribute( 'items_wrapper', 'data-item', isset( $settings['slides_per_view'] ) ? $settings['slides_per_view'] : 3 );
			$this->add_render_attribute( 'items_wrapper', 'data-desktop', isset( $settings['slides_per_view'] ) ? $settings['slides_per_view'] : 3 );
			$this->add_render_attribute( 'items_wrapper', 'data-dots', $settings['hide_pagination_control'] );
			$this->add_render_attribute( 'items_wrapper', 'data-nav', $settings['hide_prev_next_buttons'] );
			$this->add_render_attribute( 'items_wrapper', 'data-loop', $settings['wrap'] );
			$this->add_render_attribute( 'items_wrapper', 'data-ceffect', $settings['carousel_slider_effect'] );
			$this->add_render_attribute( 'items_wrapper', 'data-auto', $settings['autoplay'] );
			$this->add_render_attribute( 'items_wrapper', 'data-speed', $settings['speed'] );
			$this->add_render_attribute( 'items_wrapper', 'data-lazy', $settings['scroll_carousel_init'] );
			$this->add_render_attribute( 'items', 'class', 'swiper-slide' );

			if ( 'yes' === $settings['scroll_carousel_init'] ) {
				$this->add_render_attribute( 'wrapper', 'class', 'scroll-init' );
			}

			if ( get_theme_mod( 'penci_disable_owl_mobile_devices' ) ) {
				$this->add_render_attribute( 'wrapper', 'class', 'disable-owl-mobile' );
			}
		} else {
			$this->add_render_attribute( 'items_wrapper', 'class', 'penci-custom-row row-' . $settings['columns'] );
			$this->add_render_attribute( 'items', 'class', 'column-item' );
		}

		$args = array(
			'taxonomy'   => $attribute,
			'hide_empty' => $settings['hide_empty'],
			'order'      => $settings['order'],
			'number'     => $settings['number'],
		);

		if ( $settings['orderby'] ) {
			$args['orderby'] = $settings['orderby'];
		}

		if ( 'random' === $settings['orderby'] ) {
			$args['orderby'] = 'id';
			$brand_count     = wp_count_terms( $attribute, array(
				'hide_empty' => $settings['hide_empty'],
			) );

			$offset = rand( 0, $brand_count - (int) $settings['number'] );
			if ( $offset <= 0 ) {
				$offset = '';
			}
			$args['offset'] = $offset;
		}

		if ( $settings['ids'] ) {
			$args['include'] = $settings['ids'];
		}

		$brands   = get_terms( $args );
		$taxonomy = get_taxonomy( $attribute );

		if ( 'random' === $settings['orderby'] ) {
			shuffle( $brands );
		}

		if ( penci_is_shop_on_front() ) {
			$link = home_url();
		} else {
			$link = get_post_type_archive_link( 'product' );
		}

		?>
        <div class="penci-product-brand-wrapper">
			<?php $this->markup_block_title( $this->get_settings(), $this ); ?>

            <div <?php echo $this->get_render_attribute_string( 'wrapper' ); ?>>
                <div <?php echo $this->get_render_attribute_string( 'items_wrapper' ); ?>>
					<?php if ( 'carousel' === $settings['style'] ) {
						echo '<div class="swiper-wrapper">';
					} ?>
					<?php if ( ! is_wp_error( $brands ) && count( $brands ) > 0 ) : ?>
						<?php foreach ( $brands as $key => $brand ) : ?>
							<?php
							$image       = get_term_meta( $brand->term_id, 'image', true );
							$filter_name = 'filter_' . sanitize_title( str_replace( 'pa_', '', $attribute ) );

							if ( is_object( $taxonomy ) && $taxonomy->public ) {
								$attr_link = get_term_link( $brand->term_id, $brand->taxonomy );
							} else {
								$attr_link = add_query_arg( $filter_name, $brand->slug, $link );
							}
							?>

                            <div <?php echo $this->get_render_attribute_string( 'items' ); ?>>
                                <a title="Brand <?php echo esc_html( $brand->name ); ?>"
                                   href="<?php echo esc_url( $attr_link ); ?>">
									<?php if ( 'list' === $settings['style'] || ! $image ) : ?>
                                        <span class="brand-title-wrap">
										<?php echo esc_html( $brand->name ); ?>
									</span>
									<?php else : ?>
										<?php echo '<img src="' . wp_get_attachment_image_url( $image, 'full' ) . '" alt="' . $brand->name . '" title="' . $brand->name . '">'; ?>
									<?php endif; ?>
                                </a>
                            </div>
						<?php endforeach; ?>
					<?php endif; ?>
					<?php if ( 'carousel' === $settings['style'] ) {
						echo '</div>';
					} ?>
                </div>
            </div>
        </div>
		<?php
	}
}
