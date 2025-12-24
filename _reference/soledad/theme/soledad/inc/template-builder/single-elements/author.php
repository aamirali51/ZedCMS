<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}


class PenciSingleAuthor extends \Elementor\Widget_Base {

	public function get_title() {
		return esc_html__( 'Post - Author', 'soledad' );
	}

	public function get_icon() {
		return 'eicon-user-circle-o';
	}

	public function get_categories() {
		return [ 'penci-single-builder' ];
	}

	public function get_keywords() {
		return [ 'single', 'author' ];
	}

	protected function get_html_wrapper_class() {
		return 'pcsb-athor elementor-widget-' . $this->get_name();
	}

	public function get_name() {
		return 'penci-single-author';
	}

	protected function register_controls() {

		$this->start_controls_section( 'content_section', [
			'label' => esc_html__( 'General', 'soledad' ),
			'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
		] );

		$this->add_control( 'penci_authorbio_style', [
			'label'   => esc_html__( 'Author Box Style', 'soledad' ),
			'type'    => \Elementor\Controls_Manager::SELECT,
			'default' => 'style-1',
			'options' => [
				'style-1' => 'Default',
				'style-2' => 'Style 2',
				'style-3' => 'Style 3',
				'style-4' => 'Style 4',
				'style-5' => 'Style 5',
			]
		] );

		$this->add_control( 'penci_bioimg_style', [
			'label'   => esc_html__( 'Author Box Image Style', 'soledad' ),
			'type'    => \Elementor\Controls_Manager::SELECT,
			'default' => 'round',
			'options' => [
				'round'  => 'Round',
				'square' => 'Square',
				'sround' => 'Round Borders',
			]
		] );

		$this->end_controls_section();

		$this->start_controls_section( 'avata_img', [
			'label' => esc_html__( 'Avatar Image', 'soledad' ),
			'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
		] );
		$this->add_responsive_control( 'penci_author_ava_size', [
			'label'     => 'Author Image Size',
			'default'   => [
				'unit' => 'px',
				'size' => 100,
			],
			'type'      => \Elementor\Controls_Manager::SLIDER,
			'range'     => array( 'px' => array( 'min' => 0, 'max' => 300, ) ),
			'selectors' => [
				'{{WRAPPER}} .post-author.abio-style-1 .author-content,{{WRAPPER}} .post-author.abio-style-2 .author-content' => 'margin-left:calc({{SIZE}}px + 20px)',
				'body.rtl {{WRAPPER}} .post-author.abio-style-1 .author-content,body.rtl {{WRAPPER}} .post-author.abio-style-2 .author-content' => 'margin-left:0;margin-right:calc({{SIZE}}px + 20px)',
				'{{WRAPPER}} .post-author .author-img'                                                                        => 'width:{{SIZE}}px;max-width:100%;',
				'{{WRAPPER}} .post-author.abio-style-3 .author-img,{{WRAPPER}} .post-author.abio-style-4 .author-img'         => 'margin-right:auto;margin-left:auto;width:{{SIZE}}px;',
				'{{WRAPPER}} .post-author .author-img img'                                                                    => 'width:100%;height:auto;',
				'{{WRAPPER}} .abio-style-4'                                                                                   => 'margin-top:calc({{SIZE}}px/2);',
				'{{WRAPPER}} .abio-style-4 .author-img'                                                                       => 'margin-top:calc({{SIZE}}px/2 * -1 - 20px);',
			],
		] );
		$this->end_controls_section();

		$this->start_controls_section( 'color_style', [
			'label' => esc_html__( 'Color & Styles', 'soledad' ),
			'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
		] );

		$this->add_control( 'author_bg_color', [
			'label'     => 'Author Box Background Color',
			'type'      => \Elementor\Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .post-author' => 'background-color:{{VALUE}}' ],
		] );

		$this->add_control( 'author_bd_color', [
			'label'     => 'Author Box Borders Color',
			'type'      => \Elementor\Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .post-author' => 'border:1px solid {{VALUE}}' ],
		] );

		$this->add_control( 'author_abd_color', [
			'label'     => 'Author Image Borders Color',
			'type'      => \Elementor\Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .abio-style-3 .author-img img, {{WRAPPER}} .abio-style-4 .author-img img' => 'border-color: {{VALUE}};box-shadow:0px 1px 2px 0px {{VALUE}}' ],
			'condition' => [ 'penci_authorbio_style' => [ 'style-3', 'style-4' ] ]
		] );

		$this->add_control( 'author_bw_color', [
			'label'      => 'Author Box Borders Width',
			'type'       => \Elementor\Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', 'em', '%' ],
			'selectors'  => [
				'{{WRAPPER}} .post-author' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
			],
		] );

		$this->add_control( 'author_bd_style', [
			'label'     => 'Author Box Borders Style',
			'type'      => \Elementor\Controls_Manager::SELECT,
			'options'   => [
				''       => esc_html__( 'Default', 'soledad' ),
				'solid'  => esc_html__( 'Solid', 'soledad' ),
				'dashed' => esc_html__( 'Dashed', 'soledad' ),
				'dotted' => esc_html__( 'Dotted', 'soledad' ),
			],
			'selectors' => [
				'{{WRAPPER}} .post-author' => 'border-style: {{VALUE}}',
			],
		] );

		$this->add_control( 'author_pd', [
			'label'      => 'Author Box Padding',
			'type'       => \Elementor\Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', 'em', '%' ],
			'selectors'  => [
				'{{WRAPPER}} .post-author' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
			],
		] );

		$this->add_group_control( \Elementor\Group_Control_Typography::get_type(), array(
			'name'     => 'author_name_typo',
			'label'    => __( 'Typography for Author Name', 'soledad' ),
			'selector' => '{{WRAPPER}} .author-content > h5',
		) );

		$this->add_control( 'author_name_color', [
			'label'     => 'Author Name Color',
			'type'      => \Elementor\Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .author-content > h5, {{WRAPPER}} .author-content > h5 a' => 'color:{{VALUE}}' ],
		] );

		$this->add_control( 'author_name_hcolor', [
			'label'     => 'Author Name Hover Color',
			'type'      => \Elementor\Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .author-content > h5 a:hover' => 'color:{{VALUE}}' ],
		] );

		$this->add_group_control( \Elementor\Group_Control_Typography::get_type(), array(
			'name'     => 'author_desc_typo',
			'label'    => __( 'Typography for Author Description', 'soledad' ),
			'selector' => '{{WRAPPER}} .author-content > p',
		) );

		$this->add_control( 'author_desc_color', [
			'label'     => 'Author Description Color',
			'type'      => \Elementor\Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .author-content > p' => 'color:{{VALUE}}' ],
		] );

		$this->add_control( 'author_desc_lcolor', [
			'label'     => 'Author Description Link Color',
			'type'      => \Elementor\Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .author-content > p a' => 'color:{{VALUE}}' ],
		] );

		$this->add_control( 'author_desc_hcolor', [
			'label'     => 'Author Description Link Hover Color',
			'type'      => \Elementor\Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .author-content > p a:hover' => 'color:{{VALUE}}' ],
		] );

		$this->add_control( 'author_social_lcolor', [
			'label'     => 'Author Social Icons Color',
			'type'      => \Elementor\Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .bio-social a' => 'color:{{VALUE}}' ],
		] );

		$this->add_control( 'author_social_hcolor', [
			'label'     => 'Author Social Icons Hover Color',
			'type'      => \Elementor\Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .bio-social a:hover' => 'color:{{VALUE}}' ],
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
		$settings  = $this->get_settings();
		$classes   = 'post-author';
		$bio_style = $settings['penci_authorbio_style'] ? $settings['penci_authorbio_style'] : 'style-1';
		$bio_img   = $settings['penci_bioimg_style'] ? $settings['penci_bioimg_style'] : 'round';
		$classes   .= ' abio-' . $bio_style;
		$classes   .= ' bioimg-' . $bio_img;
		$ava_size  = isset( $settings['penci_author_ava_size']['size'] ) ? $settings['penci_author_ava_size']['size'] : 100;
		?>
        <div class="<?php echo $classes; ?>">
            <div class="author-img">
				<?php echo get_avatar( get_the_author_meta( 'ID' ), $ava_size ); ?>
            </div>
            <div class="author-content">
                <h5>The Author Name</h5>
                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Cupiditate dolores et ex fugiat, laboriosam
                    magni, nam nihil officiis perferendis quam quidem saepe voluptatem. Debitis dignissimos fugit
                    inventore neque, repudiandae soluta.</p>
                <div class="bio-social">
                    <a <?php echo penci_reltag_social_icons(); ?> target="_blank" class="author-social"
                                                                  href="#"><?php penci_fawesome_icon( 'fas fa-globe' ); ?></a>
                    <a <?php echo penci_reltag_social_icons(); ?> target="_blank" class="author-social"
                                                                  href="#"><?php penci_fawesome_icon( 'fab fa-facebook-f' ); ?></a>
                    <a <?php echo penci_reltag_social_icons(); ?> target="_blank" class="author-social"
                                                                  href="#"><?php penci_fawesome_icon( 'penciicon-x-twitter' ); ?></a>
                    <a <?php echo penci_reltag_social_icons(); ?> target="_blank" class="author-social"
                                                                  href="#"><?php penci_fawesome_icon( 'fab fa-instagram' ); ?></a>
                    <a <?php echo penci_reltag_social_icons(); ?> target="_blank" class="author-social"
                                                                  href="#"><?php penci_fawesome_icon( 'fab fa-pinterest' ); ?></a>
                    <a <?php echo penci_reltag_social_icons(); ?> target="_blank" class="author-social"
                                                                  href="#"><?php penci_fawesome_icon( 'fab fa-tumblr' ); ?></a>
                    <a <?php echo penci_reltag_social_icons(); ?> target="_blank" class="author-social"
                                                                  href="#"><?php penci_fawesome_icon( 'fab fa-linkedin-in' ); ?></a>
                    <a <?php echo penci_reltag_social_icons(); ?> target="_blank" class="author-social"
                                                                  href="#"><?php penci_fawesome_icon( 'fab fa-soundcloud' ); ?></a>
                    <a <?php echo penci_reltag_social_icons(); ?> target="_blank" class="author-social"
                                                                  href="#"><?php penci_fawesome_icon( 'fab fa-youtube' ); ?></a>
                    <a class="author-social" href="#"><?php penci_fawesome_icon( 'fas fa-envelope' ); ?></a>
                </div>
            </div>
        </div>
		<?php
	}

	protected function builder_content() {
		$this->overwrite_mods();
		get_template_part( 'inc/templates/about_author' );
	}

	protected function overwrite_mods() {
		$settings = $this->get_settings();
		$mods     = [
			'penci_authorbio_style',
			'penci_author_ava_size',
			'penci_bioimg_style'
		];
		foreach ( $mods as $mod ) {
			if ( $mod == 'penci_author_ava_size' ) {
				$value = isset( $settings[ $mod ]['size'] ) && $settings[ $mod ]['size'] ? $settings[ $mod ]['size'] : 100;
			} else {
				$value = $settings[ $mod ];
			}
			add_filter( 'theme_mod_' . $mod, function () use ( $value ) {
				return $value;
			} );
		}
	}
}
