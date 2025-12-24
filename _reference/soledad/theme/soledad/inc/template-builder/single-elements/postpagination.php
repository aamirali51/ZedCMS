<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}


class PenciSinglePostpagination extends \Elementor\Widget_Base {

	public function get_title() {
		return esc_html__( 'Post - Post Pagination', 'soledad' );
	}

	public function get_icon() {
		return 'eicon-post-navigation';
	}

	public function get_categories() {
		return [ 'penci-single-builder' ];
	}

	public function get_keywords() {
		return [ 'single', 'post', 'pagination' ];
	}

	protected function get_html_wrapper_class() {
		return 'pcsb-pnavi elementor-widget-' . $this->get_name();
	}

	public function get_name() {
		return 'penci-single-post-pagination';
	}

	protected function register_controls() {

		$this->start_controls_section( 'content_section', [
			'label' => esc_html__( 'General', 'soledad' ),
			'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
		] );

		$this->add_control( 'penci_post_nav_style', array(
			'label'     => __( 'Style', 'soledad' ),
			'type'      => \Elementor\Controls_Manager::SELECT,
			'default'   => '',
			'options'   => [
				'style-1'       => esc_html__( 'Style 1', 'soledad' ),
				'style-2'       => esc_html__( 'Style 2', 'soledad' ),
				'style-3'       => esc_html__( 'Style 3', 'soledad' ),
				'style-4'       => esc_html__( 'Style 4', 'soledad' ),
				'style-5'       => esc_html__( 'Style 5', 'soledad' ),
			],
		) );

		$this->add_control( 'penci_post_nav_thumbnail', [
			'label'   => esc_html__( 'Show Post Navigation Thumbnail?', 'soledad' ),
			'type'    => \Elementor\Controls_Manager::SWITCHER,
			'default' => '',
		] );

		$this->add_control( 'penci_trans_previous_post', [
			'label'   => esc_html__( 'Text: Previous Post', 'soledad' ),
			'type'    => \Elementor\Controls_Manager::TEXT,
			'default' => penci_get_setting( 'penci_trans_previous_post' ),
		] );

		$this->add_control( 'penci_trans_next_post', [
			'label'   => esc_html__( 'Text: Next Post', 'soledad' ),
			'type'    => \Elementor\Controls_Manager::TEXT,
			'default' => penci_get_setting( 'penci_trans_next_post' ),
		] );

		$this->end_controls_section();

		$this->start_controls_section( 'image_setup', [
			'label'     => esc_html__( 'Thumbnail Settings', 'soledad' ),
			'tab'       => \Elementor\Controls_Manager::TAB_CONTENT,
			'condition' => [ 'penci_post_nav_thumbnail' => 'yes' ],
		] );
		$this->add_control( 'thumb_size', array(
			'label'   => __( 'Custom Image Size', 'soledad' ),
			'type'    => \Elementor\Controls_Manager::SELECT,
			'default' => '',
			'options' => $this->get_list_image_sizes( true ),
		) );
		$this->add_responsive_control( 'thumb_ratio', array(
			'label'       => __( 'Thumbnail Ratio (%)', 'soledad' ),
			'type'        => \Elementor\Controls_Manager::SLIDER,
			'range'       => array( 'px' => array( 'min' => 0, 'max' => 300, ) ),
			'selectors'   => array(
				'{{WRAPPER}} .penci-post-nav-thumb:before' => 'padding-top: {{SIZE}}%; content: ""; display: block;',
				'{{WRAPPER}} .penci-post-nav-thumb'        => 'height: auto;',
			),
			'render_type' => 'template'
		) );
		$this->add_responsive_control( 'thumb_w', array(
			'label'     => __( 'Thumbnail Width', 'soledad' ),
			'type'      => \Elementor\Controls_Manager::SLIDER,
			'range'     => array( 'px' => array( 'min' => 0, 'max' => 300, ) ),
			'selectors' => array( '{{WRAPPER}} .penci-post-nav-thumb' => 'width: {{SIZE}}px;' ),
		) );
		$this->add_responsive_control( 'thumb_br', array(
			'label'     => __( 'Thumbnail Borders Radius', 'soledad' ),
			'type'      => \Elementor\Controls_Manager::SLIDER,
			'range'     => array( 'px' => array( 'min' => 0, 'max' => 300, ) ),
			'selectors' => array( '{{WRAPPER}} .penci-post-nav-thumb' => 'border-radius: {{SIZE}}px' ),
		) );
		$this->end_controls_section();

		$this->start_controls_section( 'color_style', [
			'label' => esc_html__( 'Color & Styles', 'soledad' ),
			'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
		] );

		$this->add_group_control( \Elementor\Group_Control_Typography::get_type(), array(
			'name'     => 'post_desc_typo',
			'label'    => __( 'Typography for Small Text Description', 'soledad' ),
			'selector' => '{{WRAPPER}} .post-pagination .prev-post-title span',
		) );

		$this->add_control( 'post_desc_color', [
			'label'     => 'Color for Small Text Description',
			'type'      => \Elementor\Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .post-pagination .prev-post-title span' => 'color: {{VALUE}}' ],
		] );

		$this->add_group_control( \Elementor\Group_Control_Typography::get_type(), array(
			'name'     => 'post_title_typo',
			'label'    => __( 'Typography for Post Title', 'soledad' ),
			'selector' => '{{WRAPPER}} .post-pagination .prev-title, {{WRAPPER}} .post-pagination .next-title, {{WRAPPER}} .post-pagination.pcpagp-style-2 .prev-post-title, {{WRAPPER}} .post-pagination.pcpagp-style-3 .prev-post-title, {{WRAPPER}} .post-pagination.pcpagp-style-4 .prev-post-title, {{WRAPPER}} .post-pagination.pcpagp-style-5 .prev-post-title',
		) );

		$this->add_control( 'post_title_color', [
			'label'     => 'Post Title Color',
			'type'      => \Elementor\Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .post-pagination a' => 'color: {{VALUE}}' ],
		] );

		$this->add_control( 'post_title_hcolor', [
			'label'     => 'Post Title Hover Color',
			'type'      => \Elementor\Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .post-pagination a:hover' => 'color: {{VALUE}}' ],
		] );
		$this->add_responsive_control( 'post_spacing', array(
			'label'     => __( 'Spacing Between Next & Previous Posts', 'soledad' ),
			'type'      => \Elementor\Controls_Manager::SLIDER,
			'range'     => array( 'px' => array( 'min' => 0, 'max' => 500, ) ),
			'selectors' => array( '{{WRAPPER}} .prev-post,{{WRAPPER}} .next-post' => 'width:calc(50% - {{SIZE}}px/2)' ),
		) );

		$this->add_control( 'previousn_post_bg', [
			'label'     => 'Background Color',
			'type'      => \Elementor\Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .post-pagination .prvn-item' => 'background-color: {{VALUE}}',
			],
		] );
		$this->add_control( 'previousn_post_bdradius', array(
			'label'     => __( 'Borders Radius', 'soledad' ),
			'type'      => \Elementor\Controls_Manager::SLIDER,
			'range'     => array( '%' => array( 'min' => 0, 'max' => 100, ) ),
			'selectors' => array(
				'{{WRAPPER}} .prvn-item' => 'border-radius: {{SIZE}}%;overflow:hidden;',
			),
		) );
		$this->add_control( 'nprev_post_pd', array(
			'label'      => __( 'Add Paddding', 'soledad' ),
			'type'       => \Elementor\Controls_Manager::DIMENSIONS,
			'size_units' => array( 'px', '%', 'em' ),
			'selectors'  => array( '{{WRAPPER}} .prvn-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ),
		) );
		$this->add_control( 'prev_post_bdcolor', array(
			'label'     => __( 'Borders Color', 'soledad' ),
			'type'      => \Elementor\Controls_Manager::COLOR,
			'selectors' => array( '{{WRAPPER}} .prvn-item' => 'border: 1px solid {{VALUE}}' ),
		) );
		$this->add_control( 'prev_post_bdw', array(
			'label'      => __( 'Borders Width', 'soledad' ),
			'type'       => \Elementor\Controls_Manager::DIMENSIONS,
			'size_units' => array( 'px', '%', 'em' ),
			'selectors'  => array( '{{WRAPPER}} .prvn-item' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ),
		) );

		$this->add_control( 'prev_post_bdstyle', array(
			'label'     => __( 'Borders Style', 'soledad' ),
			'type'      => \Elementor\Controls_Manager::SELECT,
			'default'   => '',
			'options'   => [
				''       => esc_html__( 'Default', 'soledad' ),
				'solid'  => esc_html__( 'Solid', 'soledad' ),
				'double' => esc_html__( 'Double', 'soledad' ),
				'dotted' => esc_html__( 'Dotted', 'soledad' ),
				'dashed' => esc_html__( 'Dashed', 'soledad' ),
			],
			'selectors' => array( '{{WRAPPER}} .prvn-item' => 'border-style: {{VALUE}}' ),
		) );
		$this->add_control( 'previous_post_bdcolor', array(
			'label'     => __( 'Borders Color', 'soledad' ),
			'type'      => \Elementor\Controls_Manager::COLOR,
			'selectors' => array( '{{WRAPPER}} .prvn-item' => 'border-color: {{VALUE}}' ),
		) );
		$this->end_controls_section();

	}

	/**
	 * Get image sizes.
	 *
	 * Retrieve available image sizes after filtering `include` and `exclude` arguments.
	 */
	public function get_list_image_sizes( $default = false ) {
		$wp_image_sizes = $this->get_all_image_sizes();

		$image_sizes = array();

		if ( $default ) {
			$image_sizes[''] = esc_html__( 'Default', 'soledad' );
		}

		foreach ( $wp_image_sizes as $size_key => $size_attributes ) {
			$control_title = ucwords( str_replace( '_', ' ', $size_key ) );
			if ( is_array( $size_attributes ) ) {
				$control_title .= sprintf( ' - %d x %d', $size_attributes['width'], $size_attributes['height'] );
			}

			$image_sizes[ $size_key ] = $control_title;
		}

		$image_sizes['full'] = esc_html__( 'Full', 'soledad' );

		return $image_sizes;
	}

	public function get_all_image_sizes() {
		global $_wp_additional_image_sizes;

		$default_image_sizes = [ 'thumbnail', 'medium', 'medium_large', 'large' ];

		$image_sizes = [];

		foreach ( $default_image_sizes as $size ) {
			$image_sizes[ $size ] = [
				'width'  => (int) get_option( $size . '_size_w' ),
				'height' => (int) get_option( $size . '_size_h' ),
				'crop'   => (bool) get_option( $size . '_crop' ),
			];
		}

		if ( $_wp_additional_image_sizes ) {
			$image_sizes = array_merge( $image_sizes, $_wp_additional_image_sizes );
		}

		return $image_sizes;
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
		$width    = 300;
		$height   = 300;
		$class    = isset( $settings['thumb_ratio']['size'] ) && $settings['thumb_ratio']['size'] ? 'has-custom-ratio' : 'default-ratio';
		$style    = $settings['penci_post_nav_style'];
		$class    .= ' pcpagp-'.$style;
		?>
        <div class="post-pagination <?php echo $class; ?>">

            <div class="prev-post prvn-item">
				<?php if ( $settings['penci_post_nav_thumbnail'] ): ?>
					<?php if ( ! get_theme_mod( 'penci_disable_lazyload_single' ) ) { ?>
                        <a class="penci-post-nav-thumb penci-image-holder penci-holder-load penci-lazy"
                           href="#"
                           data-bgset="<?php echo PENCI_SOLEDAD_URL . '/inc/template-builder/placeholder.php?w=' . $width . '&h=' . $height; ?>">
                        </a>
					<?php } else { ?>
                        <a class="penci-post-nav-thumb penci-image-holder"
                           href="#"
                           style="background-image: url('<?php echo PENCI_SOLEDAD_URL . '/inc/template-builder/placeholder.php?w=' . $width . '&h=' . $height; ?>');">
                        </a>
					<?php } ?>
				<?php endif; ?>
                <div class="prev-post-inner">
                	<?php if ( $style == 'style-3'): ?>
	                <div class="prev-post-inner-ct">
	                <?php endif; ?>
                    <div class="prev-post-title">
                        <span><?php echo $settings['penci_trans_previous_post']; ?></span>
                    </div>
                    <a href="#">
                        <div class="pagi-text">
                            <h5 class="prev-title">Previous Post Title</h5>
                        </div>
                    </a>
                    <?php if ( $style == 'style-3'): ?>
	                </div>
	                <?php endif; ?>
                </div>
            </div>

            <div class="next-post prvn-item">
				<?php if ( $settings['penci_post_nav_thumbnail'] ): ?>
					<?php if ( ! get_theme_mod( 'penci_disable_lazyload_single' ) ) { ?>
                        <a class="penci-post-nav-thumb nav-thumb-next penci-image-holder penci-holder-load penci-lazy"
                           href="#"
                           data-bgset="<?php echo PENCI_SOLEDAD_URL . '/inc/template-builder/placeholder.php?w=' . $width . '&h=' . $height; ?>">
                        </a>
					<?php } else { ?>
                        <a class="penci-post-nav-thumb nav-thumb-next"
                           href="#"
                           style="background-image: url('<?php echo PENCI_SOLEDAD_URL . '/inc/template-builder/placeholder.php?w=' . $width . '&h=' . $height; ?>');">
                        </a>
					<?php } ?>
				<?php endif; ?>
                <div class="next-post-inner">
                	<?php if ( $style == 'style-3'): ?>
	                <div class="next-post-inner-ct">
	                <?php endif; ?>
                    <div class="prev-post-title next-post-title">
                        <span><?php echo $settings['penci_trans_next_post']; ?></span>
                    </div>
                    <a href="#">
                        <div class="pagi-text">
                            <h5 class="next-title">Next Post Title</h5>
                        </div>
                    </a>
                    <?php if ( $style == 'style-3'): ?>
	                </div>
	                <?php endif; ?>
                </div>
            </div>
        </div>
		<?php
	}

	protected function builder_content() {
		$settings  = $this->get_settings();
		$thumbsize = $settings['thumb_size'];
		$style    = $settings['penci_post_nav_style'];
		$class    = isset( $settings['thumb_ratio']['size'] ) && $settings['thumb_ratio']['size'] ? 'has-custom-ratio' : 'default-ratio';
		$class    .= ' pcpagp-'.$style;
		?>
        <div class="post-pagination <?php echo $class;?>">
			<?php
			$prev_post = get_previous_post();
			$next_post = get_next_post();
			?>
			<?php if ( ! empty( $prev_post ) ) : ?>
                <div class="prev-post prvn-item">
					<?php if ( has_post_thumbnail( $prev_post->ID ) && 'yes' == $settings['penci_post_nav_thumbnail'] ): ?>
						<?php if ( ! get_theme_mod( 'penci_disable_lazyload_single' ) ) { ?>
                            <a class="penci-post-nav-thumb penci-holder-load penci-lazy"
                               href="<?php echo esc_url( get_the_permalink( $prev_post->ID ) ); ?>"
                               data-bgset="<?php echo penci_image_srcset( $prev_post->ID, $thumbsize ); ?>">
                            </a>
						<?php } else { ?>
                            <a class="penci-post-nav-thumb"
                               href="<?php echo esc_url( get_the_permalink( $prev_post->ID ) ); ?>"
                               style="background-image: url('<?php echo penci_get_featured_image_size( $prev_post->ID, $thumbsize ); ?>');">
                            </a>
						<?php } ?>
					<?php endif; ?>
                    <div class="prev-post-inner">
                    	<?php if ( $style == 'style-3'): ?>
		                <div class="prev-post-inner-ct">
		                <?php endif; ?>
                        <div class="prev-post-title">
                            <span><?php echo penci_get_setting( 'penci_trans_previous_post' ); ?></span>
                        </div>
                        <a href="<?php echo esc_url( get_the_permalink( $prev_post->ID ) ); ?>">
                            <div class="pagi-text">
                                <h5 class="prev-title"><?php echo get_the_title( $prev_post->ID ); ?></h5>
                            </div>
                        </a>
                        <?php if ( $style == 'style-3'): ?>
		                </div>
		                <?php endif; ?>
                    </div>
                </div>
			<?php endif; ?>

			<?php if ( ! empty( $next_post ) ) : ?>
                <div class="next-post prvn-item">
					<?php if ( has_post_thumbnail( $next_post->ID ) && 'yes' == $settings['penci_post_nav_thumbnail'] ): ?>
						<?php if ( ! get_theme_mod( 'penci_disable_lazyload_single' ) ) { ?>
                            <a class="penci-post-nav-thumb penci-holder-load penci-lazy nav-thumb-next"
                               href="<?php echo esc_url( get_the_permalink( $next_post->ID ) ); ?>"
                               data-bgset="<?php echo penci_image_srcset( $next_post->ID, $thumbsize ); ?>">
                            </a>
						<?php } else { ?>
                            <a class="penci-post-nav-thumb nav-thumb-next"
                               href="<?php echo esc_url( get_the_permalink( $next_post->ID ) ); ?>"
                               style="background-image: url('<?php echo penci_get_featured_image_size( $next_post->ID, $thumbsize ); ?>');">
                            </a>
						<?php } ?>
					<?php endif; ?>
                    <div class="next-post-inner">
                    	<?php if ( $style == 'style-3'): ?>
		                <div class="next-post-inner-ct">
		                <?php endif; ?>
                        <div class="prev-post-title next-post-title">
                            <span><?php echo penci_get_setting( 'penci_trans_next_post' ); ?></span>
                        </div>
                        <a href="<?php echo esc_url( get_the_permalink( $next_post->ID ) ); ?>">
                            <div class="pagi-text">
                                <h5 class="next-title"><?php echo get_the_title( $next_post->ID ); ?></h5>
                            </div>
                        </a>
                        <?php if ( $style == 'style-3'): ?>
		                </div>
		                <?php endif; ?>
                    </div>
                </div>
			<?php endif; ?>
        </div>
		<?php
	}
}
