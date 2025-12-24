<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}


class PenciSingleCustomfield extends \Elementor\Widget_Base {

	public function get_title() {
		return esc_html__( 'Post - Custom Field', 'soledad' );
	}

	public function get_icon() {
		return 'eicon-post-title';
	}

	public function get_categories() {
		return [ 'penci-single-builder' ];
	}

	public function get_keywords() {
		return [ 'meta', 'field' ];
	}

	protected function get_html_wrapper_class() {
		return 'pcsb-csf elementor-widget-' . $this->get_name();
	}

	public function get_name() {
		return 'penci-single-custom-field';
	}

	protected function register_controls() {

		$this->start_controls_section( 'content_section', [
			'label' => esc_html__( 'General', 'soledad' ),
			'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
		] );

		$this->add_control( 'meta_source', [
			'label'   => esc_html__( 'Meta Source', 'soledad' ),
			'type'    => \Elementor\Controls_Manager::SELECT,
			'default' => 'custom',
			'options' => [
				'custom' => 'Custom Meta Field',
				'acf'    => 'Advanced Custom Field',
			]
		] );

		$this->add_control( 'meta', [
			'label'     => esc_html__( 'Meta Key', 'soledad' ),
			'type'      => \Elementor\Controls_Manager::TEXT,
			'condition' => [ 'meta_source' => 'custom' ]
		] );

		$this->add_control( 'acf', [
			'label'       => esc_html__( 'ACF Field', 'soledad' ),
			'description' => __( 'You can show your own custom fields easily by using the <a href="https://wordpress.org/plugins/advanced-custom-fields/" target="_blank">Advanced Custom Fields</a> plugin.', 'soledad' ),
			'type'        => \Elementor\Controls_Manager::SELECT2,
			'options'     => penci_get_afc_fields( true ),
			'condition'   => [ 'meta_source' => 'acf' ]
		] );

		$this->add_control( 'meta_label', [
			'label' => esc_html__( 'Meta Label', 'soledad' ),
			'type'  => \Elementor\Controls_Manager::TEXT,
		] );

		$this->add_control( 'meta_align', [
			'label'     => __( 'Meta Text Align', 'soledad' ),
			'type'      => \Elementor\Controls_Manager::CHOOSE,
			'default'   => 'left',
			'options'   => array(
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
			'toggle'    => true,
			'selectors' => [ '{{WRAPPER}} .post-box-meta-single' => 'text-align:{{VALUE}}' ],
		] );

		$this->add_control( 'meta_icon_check', [
			'label'     => esc_html__( 'Hide Meta Icon?', 'soledad' ),
			'type'      => \Elementor\Controls_Manager::SWITCHER,
			'label_on'  => __( 'Yes', 'soledad' ),
			'label_off' => __( 'No', 'soledad' ),
		] );

		$this->add_control( 'meta_img_preview', [
			'label'       => esc_html__( 'Display Field as Image', 'soledad' ),
			'description' => esc_html__( 'Use this option if your custom field are image field', 'soledad' ),
			'type'        => \Elementor\Controls_Manager::SWITCHER,
			'label_on'    => __( 'Yes', 'soledad' ),
			'label_off'   => __( 'No', 'soledad' ),
		] );

		$this->add_control( 'meta_icon', [
			'label'            => esc_html__( 'Meta Icon', 'soledad' ),
			'type'             => \Elementor\Controls_Manager::ICONS,
			'fa4compatibility' => 'icon',
			'default'          => [
				'value'   => 'far fa-user',
				'library' => 'fa-regular',
			],
			'condition'        => [ 'meta_icon_check!' => 'yes' ],
		] );

		$this->end_controls_section();


		$this->start_controls_section( 'color_style', [
			'label' => esc_html__( 'Meta Color & Styles', 'soledad' ),
			'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
		] );

		$this->add_group_control( \Elementor\Group_Control_Typography::get_type(), array(
			'name'     => 'heading_typo',
			'label'    => __( 'Typography for Post Meta', 'soledad' ),
			'selector' => '{{WRAPPER}} .post-box-meta-single',
		) );

		$this->add_control( 'meta-color', [
			'label'     => 'Meta Color',
			'type'      => \Elementor\Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .post-box-meta-single, {{WRAPPER}} .post-box-meta-single span' => 'color:{{VALUE}}' ],
		] );

		$this->add_control( 'meta-link-color', [
			'label'     => 'Meta Content Color',
			'type'      => \Elementor\Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .post-box-meta-single span.content' => 'color:{{VALUE}}' ],
		] );

		$this->add_control( 'meta-bg-color', [
			'label'     => 'Meta Background Color',
			'type'      => \Elementor\Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .post-box-meta-single' => 'background-color:{{VALUE}}' ],
		] );

		$this->add_responsive_control( 'meta-img-mw', [
			'label'     => 'Meta Image Max Width',
			'type'      => \Elementor\Controls_Manager::SLIDER,
			'range'     => [
				'px' => [
					'min' => 0,
					'max' => 1200,
				],
			],
			'condition' => [ 'meta_img_preview' => 'yes' ],
			'selectors' => [ '{{WRAPPER}} .post-box-meta-single img' => 'max-width:{{SIZE}}px;width:auto;' ],
		] );

		$this->add_responsive_control( 'meta-img-mh', [
			'label'     => 'Meta Image Max Height',
			'type'      => \Elementor\Controls_Manager::SLIDER,
			'range'     => [
				'px' => [
					'min' => 0,
					'max' => 1200,
				],
			],
			'condition' => [ 'meta_img_preview' => 'yes' ],
			'selectors' => [ '{{WRAPPER}} .post-box-meta-single img' => 'max-height:{{SIZE}}px;height:auto;' ],
		] );

		$this->add_responsive_control( 'meta-img-bd', [
			'label'     => 'Meta Image Border Radius',
			'type'      => \Elementor\Controls_Manager::SLIDER,
			'range'     => [
				'%' => [
					'min' => 0,
					'max' => 100,
				],
			],
			'condition' => [ 'meta_img_preview' => 'yes' ],
			'selectors' => [ '{{WRAPPER}} .post-box-meta-single img' => 'border-radius:{{SIZE}}%' ],
		] );

		$this->end_controls_section();

		$this->start_controls_section( 'icon_settings', [
			'label' => esc_html__( 'Icon Settings', 'soledad' ),
			'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
		] );

		$this->add_control( 'meta_icon_style', [
			'label'   => esc_html__( 'Icon Style', 'soledad' ),
			'type'    => \Elementor\Controls_Manager::SELECT,
			'default' => 'default',
			'options' => [
				'default' => 'Default',
				's1'      => 'Style 1',
				's2'      => 'Style 2',
				's3'      => 'Style 3',
				's4'      => 'Style 4',
			],
		] );

		$this->add_control( 'meta_icon_size', [
			'label'     => esc_html__( 'Icon Width', 'soledad' ),
			'type'      => \Elementor\Controls_Manager::SLIDER,
			'range'     => array( 'px' => array( 'min' => 0, 'max' => 100, ) ),
			'selectors' => array(
				'{{WRAPPER}} .pcmt-icon' => 'width: {{SIZE}}px;height: {{SIZE}}px;line-height: {{SIZE}}px;',
			),
		] );

		$this->add_control( 'meta_icon_fsize', [
			'label'     => esc_html__( 'Icon Font Size', 'soledad' ),
			'type'      => \Elementor\Controls_Manager::SLIDER,
			'range'     => array( 'px' => array( 'min' => 0, 'max' => 100, ) ),
			'selectors' => array(
				'{{WRAPPER}} .pcmt-icon' => 'font-size: {{SIZE}}px;',
			),
		] );

		$this->add_control( 'meta_icon_border', [
			'label'     => esc_html__( 'Icon Borders Radius', 'soledad' ),
			'type'      => \Elementor\Controls_Manager::SLIDER,
			'range'     => array( 'px' => array( 'min' => 0, 'max' => 100, ) ),
			'selectors' => array(
				'{{WRAPPER}} .pcmt-icon' => 'border-radius: {{SIZE}}px;',
			),
		] );

		$this->add_control( 'meta_icon_borderw', [
			'label'     => esc_html__( 'Icon Borders Width', 'soledad' ),
			'type'      => \Elementor\Controls_Manager::SLIDER,
			'range'     => array( 'px' => array( 'min' => 0, 'max' => 10, ) ),
			'selectors' => array(
				'{{WRAPPER}} .pcmt-icon' => 'border-width: {{SIZE}}px;',
			),
		] );

		$this->add_control( 'penci_single_meta_gnr_icon_color', [
			'label'     => esc_html__( 'Icon Color', 'soledad' ),
			'type'      => \Elementor\Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .pcmt-icon' => 'color:{{VALUE}} !important' ]
		] );

		$this->add_control( 'penci_single_meta_gnr_bg_color', [
			'label'     => esc_html__( 'Background Color', 'soledad' ),
			'type'      => \Elementor\Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .pcmt-icon'                                       => 'background-color:{{VALUE}}',
				'{{WRAPPER}} .post-box-meta-single.style-s3 .pcmt-icon:after'  => 'border-left-color:{{VALUE}} !important',
				'{{WRAPPER}} .post-box-meta-single.style-s4 .pcmt-icon:before' => 'border-left-color:{{VALUE}} !important',

			]
		] );

		$this->add_control( 'penci_single_meta_gnr_bd_color', [
			'label'     => esc_html__( 'Borders Color', 'soledad' ),
			'type'      => \Elementor\Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .pcmt-icon'                                      => 'border-color:{{VALUE}} !important',
				'{{WRAPPER}} .post-box-meta-single.style-s4 .pcmt-icon:after' => 'border-left-color:{{VALUE}} !important',
			]
		] );

		$this->end_controls_section();

	}

	protected function render() {

		if ( penci_elementor_is_edit_mode() ) {
			$this->preview_content();
		} else {
			$this->builder_content();
		}

	}

	protected function preview_content() {
		$settings = $this->get_settings();
		$label    = $settings['meta_label'] ? '<span class="label">' . $settings['meta_label'] . '</span>' : '';
		$meta     = 'meta value';
		if ( $settings['meta_img_preview'] ) {
			$meta = '<img src="' . PENCI_SOLEDAD_URL . '/inc/template-builder/placeholder.php?w=600&h=600' . '" alt=""/>';
		}

		?>
        <div class="post-box-meta-single style-<?php echo esc_attr( $settings['meta_icon_style'] ); ?>">
            <span>
	        <?php
	        if ( ! $settings['meta_icon_check'] && $settings['meta_icon'] ) {
		        echo '<span class="pcmt-icon meta-icon">';
		        \Elementor\Icons_Manager::render_icon( $settings['meta_icon'] );
		        echo '</span>';
	        }
	        ?>
	            <?php echo $label; ?>
            <span class="content"><?php echo $meta; ?></span></span>
        </div>
		<?php
	}

	protected function builder_content() {
		$settings = $this->get_settings();
		$label    = $settings['meta_label'] ? '<span class="label">' . $settings['meta_label'] . '</span>' : '';

		$meta = $meta_key = '';

		if ( 'custom' == $settings['meta_source'] && $settings['meta'] ) {
			$meta_key = $settings['meta'];
		}

		if ( 'acf' == $settings['meta_source'] && $settings['acf'] ) {
			$meta_key = $settings['acf'];
		}

		$meta = get_post_meta( get_the_ID(), $meta_key, true );

		$filed_type = penci_get_field_type( $meta_key );

		if ( 'image' == $filed_type && is_numeric( $meta ) ) {
			$meta = wp_get_attachment_image( $meta );
		}

		if ( 'url' == $filed_type || 'email' == $filed_type ) {
			$meta = '<a target="_blank" href="' . esc_url( $meta ) . '">' . esc_html( $meta ) . '</a>';
		}

		if ( $meta ) {
			?>
            <div class="post-box-meta-single style-<?php echo esc_attr( $settings['meta_icon_style'] ); ?>">
            <span>
	        <?php
	        if ( ! $settings['meta_icon_check'] && $settings['meta_icon'] ) {
		        echo '<span class="pcmt-icon meta-icon">';
		        \Elementor\Icons_Manager::render_icon( $settings['meta_icon'] );
		        echo '</span>';
	        }
	        ?><?php echo $label; ?><span class="content"><?php echo do_shortcode( wpautop( $meta ) ); ?></span></span>
            </div>
			<?php
		}
	}
}
