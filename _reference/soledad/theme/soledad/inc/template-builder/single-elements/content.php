<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}


class PenciSingleContent extends \Elementor\Widget_Base {

	public function get_title() {
		return esc_html__( 'Post - Content', 'soledad' );
	}

	public function get_icon() {
		return 'eicon-post-content';
	}

	public function get_categories() {
		return [ 'penci-single-builder' ];
	}

	public function get_keywords() {
		return [ 'single', 'content' ];
	}

	protected function get_html_wrapper_class() {
		return 'pcsb-mct elementor-widget-' . $this->get_name();
	}

	public function get_name() {
		return 'penci-single-content';
	}

	protected function register_controls() {

		$this->start_controls_section( 'content_section', [
			'label' => esc_html__( 'General', 'soledad' ),
			'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
		] );

		$this->add_control( 'block_style', [
			'label'       => esc_html__( 'Blockquote Style', 'soledad' ),
			'description' => esc_html__( 'blockquote styles just applies when you use Classic Block or Classic Editor', 'soledad' ),
			'type'        => \Elementor\Controls_Manager::SELECT,
			'default'     => 'style-1',
			'options'     => [
				'style-1' => 'Style 1',
				'style-2' => 'Style 2',
				'style-3' => 'Style 3',
				'style-4' => 'Style 4',
				'style-5' => 'Style 5',
			]
		] );

		$this->add_control( 'ct_headingh1_style', [
			'label'   => esc_html__( 'Style for Heading 1', 'soledad' ),
			'type'    => \Elementor\Controls_Manager::SELECT,
			'default' => '',
			'options' => [
				''        => 'Default (No Style)',
				'style-1' => 'Style 1',
				'style-2' => 'Style 2',
				'style-3' => 'Style 3',
				'style-4' => 'Style 4',
				'style-5' => 'Style 5',
				'style-6' => 'Style 6',
			]
		] );

		$this->add_control( 'ct_headingh2_style', [
			'label'   => esc_html__( 'Style for Heading 2', 'soledad' ),
			'type'    => \Elementor\Controls_Manager::SELECT,
			'default' => '',
			'options' => [
				''        => 'Default (No Style)',
				'style-1' => 'Style 1',
				'style-2' => 'Style 2',
				'style-3' => 'Style 3',
				'style-4' => 'Style 4',
				'style-5' => 'Style 5',
				'style-6' => 'Style 6',
			]
		] );

		$this->add_control( 'ct_headingh3_style', [
			'label'   => esc_html__( 'Style for Heading 3', 'soledad' ),
			'type'    => \Elementor\Controls_Manager::SELECT,
			'default' => '',
			'options' => [
				''        => 'Default (No Style)',
				'style-1' => 'Style 1',
				'style-2' => 'Style 2',
				'style-3' => 'Style 3',
				'style-4' => 'Style 4',
				'style-5' => 'Style 5',
				'style-6' => 'Style 6',
			]
		] );

		$this->add_control( 'ct_headingh4_style', [
			'label'   => esc_html__( 'Style for Heading 4', 'soledad' ),
			'type'    => \Elementor\Controls_Manager::SELECT,
			'default' => '',
			'options' => [
				''        => 'Default (No Style)',
				'style-1' => 'Style 1',
				'style-2' => 'Style 2',
				'style-3' => 'Style 3',
				'style-4' => 'Style 4',
				'style-5' => 'Style 5',
				'style-6' => 'Style 6',
			]
		] );

		$this->add_control( 'ct_headingh5_style', [
			'label'   => esc_html__( 'Style for Heading 5', 'soledad' ),
			'type'    => \Elementor\Controls_Manager::SELECT,
			'default' => '',
			'options' => [
				''        => 'Default (No Style)',
				'style-1' => 'Style 1',
				'style-2' => 'Style 2',
				'style-3' => 'Style 3',
				'style-4' => 'Style 4',
				'style-5' => 'Style 5',
				'style-6' => 'Style 6',
			]
		] );

		$this->end_controls_section();

		$this->start_controls_section( 'color_style', [
			'label' => esc_html__( 'Color & Styles', 'soledad' ),
			'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
		] );

		$this->add_group_control( \Elementor\Group_Control_Typography::get_type(), array(
			'name'     => 'heading_typo',
			'label'    => __( 'Typography for General Content', 'soledad' ),
			'selector' => '{{WRAPPER}} .post-entry, {{WRAPPER}} .post-entry p, {{WRAPPER}} .post-entry span',
		) );

		$this->add_control( 'main-text-color', [
			'label'     => 'Text Color',
			'type'      => \Elementor\Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .post-entry, {{WRAPPER}} .post-entry p, {{WRAPPER}} .post-entry span' => 'color:{{VALUE}}' ],
		] );

		$this->add_control( 'main-link-color', [
			'label'     => 'Link Color',
			'type'      => \Elementor\Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .post-entry a' => 'color:{{VALUE}}' ],
		] );

		$this->add_control( 'main-link-hcolor', [
			'label'     => 'Link Hover Color',
			'type'      => \Elementor\Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .post-entry a:hover' => 'color:{{VALUE}}' ],
		] );

		for ( $i = 1; $i <= 6; $i ++ ) {
			$this->add_group_control( \Elementor\Group_Control_Typography::get_type(), array(
				'name'     => 'heading_typo_h' . $i,
				'label'    => sprintf( __( 'Typography for H%s', 'soledad' ), $i ),
				'selector' => '{{WRAPPER}} .post-entry h' . $i,
			) );
			$this->add_control( "content_h{$i}_color", [
				'label'     => "H{$i} Color",
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [ "{{WRAPPER}} .post-entry h{$i}" => 'color:{{VALUE}}' ],
			] );
		}


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
		$settings         = $this->get_settings();
		$block_style      = $settings['block_style'];
		$ct_heading_style = $this->penci_get_heading_style();
		?>
        <div class="post-entry <?php echo 'blockquote-' . $block_style; ?> <?php echo $ct_heading_style; ?>">
            <div class="inner-post-entry entry-content" id="penci-post-entry-inner">

				<?php do_action( 'penci_action_before_the_content' ); ?>

                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Accusamus dolor expedita reiciendis veniam
                    voluptatum? Inventore labore quia quisquam repudiandae rerum unde. Accusantium consectetur
                    distinctio eius esse, necessitatibus quos sint tempore.</p>
                <h2>H2 Heading Tag</h2>
                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Accusamus dolor expedita reiciendis veniam
                    voluptatum? Inventore labore quia quisquam repudiandae rerum unde. Accusantium consectetur
                    distinctio eius esse, necessitatibus quos sint tempore.</p>
                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Accusamus dolor expedita reiciendis veniam
                    voluptatum? Inventore labore quia quisquam repudiandae rerum unde. Accusantium consectetur
                    distinctio eius esse, necessitatibus quos sint tempore.</p>
                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Accusamus dolor expedita reiciendis veniam
                    voluptatum? Inventore labore quia quisquam repudiandae rerum unde. Accusantium consectetur
                    distinctio eius esse, necessitatibus quos sint tempore.</p>

				<?php do_action( 'penci_action_after_the_content' ); ?>

                <div class="penci-single-link-pages">
					<?php wp_link_pages(); ?>
                </div>
            </div>
        </div>
		<?php
	}

	protected function builder_content() {
		$settings         = $this->get_settings();
		$block_style      = $settings['block_style'];
		$ct_heading_style = $this->penci_get_heading_style();
		?>
        <article class="post">
            <div class="post-entry <?php echo 'blockquote-' . $block_style; ?> <?php echo $ct_heading_style; ?>">
                <div class="inner-post-entry entry-content" id="penci-post-entry-inner">

					<?php do_action( 'penci_action_before_the_content' ); ?>

					<?php the_content(); ?>

					<?php do_action( 'penci_action_after_the_content' ); ?>

                    <div class="penci-single-link-pages">
						<?php wp_link_pages(); ?>
                    </div>
                </div>
            </div>
        </article>

		<?php if ( get_theme_mod( 'penci_post_adsense_two' ) ) : ?>
            <div class="penci-google-adsense-2">
				<?php echo do_shortcode( get_theme_mod( 'penci_post_adsense_two' ) ); ?>
            </div>
		<?php endif;
	}

	protected function penci_get_heading_style() {

		$settings = $this->get_settings();

		$css_class = '';

		$h1_style = $settings['ct_headingh1_style'];
		$h2_style = $settings['ct_headingh2_style'];
		$h3_style = $settings['ct_headingh3_style'];
		$h4_style = $settings['ct_headingh4_style'];
		$h5_style = $settings['ct_headingh5_style'];

		if ( $h1_style ) {
			$css_class .= ' heading1-' . $h1_style;
		}

		if ( $h2_style ) {
			$css_class .= ' heading2-' . $h2_style;
		}

		if ( $h3_style ) {
			$css_class .= ' heading3-' . $h3_style;
		}

		if ( $h4_style ) {
			$css_class .= ' heading4-' . $h4_style;
		}

		if ( $h5_style ) {
			$css_class .= ' heading5-' . $h5_style;
		}

		return $css_class;

	}
}
