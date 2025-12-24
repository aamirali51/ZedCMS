<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}


class PenciSingleSmartLists extends \Elementor\Widget_Base {

	public function get_title() {
		return esc_html__( 'Post - Smart Lists', 'soledad' );
	}

	public function get_icon() {
		return 'eicon-post-content';
	}

	public function get_categories() {
		return [ 'penci-single-builder' ];
	}

	public function get_keywords() {
		return [ 'single', 'content', 'smart', 'list' ];
	}

	protected function get_html_wrapper_class() {
		return 'pcsml-el elementor-widget-' . $this->get_name();
	}

	public function get_name() {
		return 'penci-single-smartlists';
	}

	protected function register_controls() {

		$this->start_controls_section( 'content_section', [
			'label' => esc_html__( 'General', 'soledad' ),
			'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
		] );

		$this->add_control( 'smartlists_heading', [
			'label' => esc_html__( 'Smart Lists', 'soledad' ),
			'type'  => \Elementor\Controls_Manager::HEADING,
		] );

		$this->add_control( 'smartlists_style', [
			'label'       => esc_html__( 'Smart Lists Style', 'soledad' ),
			'type'        => \Elementor\Controls_Manager::SELECT,
			'default'     => '1',
			'label_block' => true,
			'options'     => [
				'1' => 'Style 1',
				'2' => 'Style 2',
				'3' => 'Style 3',
				'4' => 'Style 4',
				'5' => 'Style 5',
				'6' => 'Style 6',
			]
		] );

		$this->add_control( 'smartlists_h', [
			'label'       => esc_html__( 'Smart Lists Content Break', 'soledad' ),
			'description' => esc_html__( 'Select the heading of the content listing', 'soledad' ),
			'type'        => \Elementor\Controls_Manager::SELECT,
			'default'     => 'h3',
			'label_block' => true,
			'options'     => [
				'h1' => 'H1 Heading',
				'h2' => 'H2 Heading',
				'h3' => 'H3 Heading',
				'h4' => 'H4 Heading',
				'h5' => 'H5 Heading',
				'h6' => 'H6 Heading',
			]
		] );

		$this->add_control( 'smartlists_heading_tag', [
			'label'       => esc_html__( 'Smart Lists Items Heading Tag', 'soledad' ),
			'type'        => \Elementor\Controls_Manager::SELECT,
			'default'     => '',
			'label_block' => true,
			'options'     => [
				''   => 'Inherit from the Smart Lists Content Break',
				'h1' => 'H1 Heading',
				'h2' => 'H2 Heading',
				'h3' => 'H3 Heading',
				'h4' => 'H4 Heading',
				'h5' => 'H5 Heading',
				'h6' => 'H6 Heading',
			]
		] );

		$this->add_control( 'smartlists_img_size', [
			'label'   => esc_html__( 'Image Size', 'soledad' ),
			'type'    => \Elementor\Controls_Manager::SELECT,
			'default' => 'penci-full-thumb',
			'options' => $this->get_list_image_sizes(),
		] );

		$this->add_control( 'smartlists_img_msize', [
			'label'   => esc_html__( 'Mobile Image Size', 'soledad' ),
			'type'    => \Elementor\Controls_Manager::SELECT,
			'default' => 'penci-masonry-thumb',
			'options' => $this->get_list_image_sizes(),
		] );

		$this->add_control( 'smartlists_order', [
			'label'     => esc_html__( 'Number Order', 'soledad' ),
			'type'      => \Elementor\Controls_Manager::SELECT,
			'default'   => 'asc',
			'condition' => array( 'smartlists_style!' => [ '5', '6' ] ),
			'options'   => [
				'asc'  => 'Ascending',
				'desc' => 'Descending',
			]
		] );

		$this->add_responsive_control( 'smartlists_spacing', [
			'label'     => esc_html__( 'Items Spacing', 'soledad' ),
			'type'      => \Elementor\Controls_Manager::SLIDER,
			'default'   => array( 'size' => '' ),
			'range'     => array( 'px' => array( 'min' => 0, 'max' => 500, ) ),
			'selectors' => array(
				'{{WRAPPER}} .pcsml-item:not(:last-child)' => 'margin-bottom: {{SIZE}}{{UNIT}};padding: 0 0 {{SIZE}}{{UNIT}};'
			)
		] );

		$this->add_responsive_control( 'smartlists2_img_width', [
			'label'     => esc_html__( 'Image Width', 'soledad' ),
			'type'      => \Elementor\Controls_Manager::SLIDER,
			'default'   => array( 'size' => '' ),
			'condition' => array( 'smartlists_style' => '2' ),
			'range'     => array( 'px' => array( 'min' => 0, 'max' => 1000, ) ),
			'selectors' => array(
				'{{WRAPPER}} .pcsml_style_2 .pcsml-figure'     => 'width: {{SIZE}}{{UNIT}};',
				'{{WRAPPER}} .pcsml_style_2 .pcsml-figure img' => 'width: 100%;height:auto;',
			)
		] );

		$this->add_control( 'smartlists2_jalign', [
			'label'     => esc_html__( 'Item Content Alignment', 'soledad' ),
			'type'      => \Elementor\Controls_Manager::SELECT,
			'default'   => 'start',
			'options'   => [
				'start'  => 'Top',
				'center' => 'Center',
				'end'    => 'Bottom',
			],
			'condition' => array( 'smartlists_style' => '2' ),
			'selectors' => array(
				'{{WRAPPER}} .pcsml_style_2 .pcsml-info' => 'align-self: {{VALUE}};',
			)
		] );

		$this->add_control( 'maincontent_heading', [
			'label' => esc_html__( 'Entry Content', 'soledad' ),
			'type'  => \Elementor\Controls_Manager::HEADING,
		] );

		$this->add_control( 'block_style', [
			'label'       => esc_html__( 'Blockquote Style', 'soledad' ),
			'description' => esc_html__( 'blockquote styles just applies when you use Classic Block or Classic Editor', 'soledad' ),
			'type'        => \Elementor\Controls_Manager::SELECT,
			'default'     => 'style-1',
			'options'     => [
				'style-1' => 'Style 1',
				'style-2' => 'Style 2'
			]
		] );

		$this->add_control( 'heading_style', [
			'label'       => esc_html__( 'Heading Style', 'soledad' ),
			'type'        => \Elementor\Controls_Manager::SELECT,
			'default'     => 'style-1',
			'options'     => [
				'style-1' => 'Style 1',
				'style-2' => 'Style 2',
				'style-3' => 'Style 3',
				'style-4' => 'Style 4',
				'style-5' => 'Style 5',
				'style-6' => 'Style 6',
			]
		] );

		$this->end_controls_section();

		$this->start_controls_section( 'smcolor_style', [
			'label' => esc_html__( 'Smart Lists Color & Styles', 'soledad' ),
			'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
		] );

		$this->add_control( 'sm_heading_title_01', [
			'label' => 'Heading Title',
			'type'  => \Elementor\Controls_Manager::HEADING,
		] );

		$this->add_group_control( \Elementor\Group_Control_Typography::get_type(), array(
			'name'     => 'sm_heading_typo',
			'label'    => __( 'Heading Typo', 'soledad' ),
			'selector' => '{{WRAPPER}} .pcsml-title-wrapper .pcsml-item-title',
		) );

		$this->add_control( 'sm_heading_color', [
			'label'     => 'Heading Color',
			'type'      => \Elementor\Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .pcsml-title-wrapper .pcsml-item-title' => 'color:{{VALUE}}' ],
		] );

		$this->add_control( 'sm_heading_title_02', [
			'label' => 'Item Number',
			'type'  => \Elementor\Controls_Manager::HEADING,
		] );

		$this->add_group_control( \Elementor\Group_Control_Typography::get_type(), array(
			'name'     => 'sm_inumber_typo',
			'label'    => __( 'Item Number Typo', 'soledad' ),
			'selector' => '{{WRAPPER}} .pcsml-item-number span',
		) );

		$this->add_control( 'sm_inumber_color', [
			'label'     => 'Item Number Color',
			'type'      => \Elementor\Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .pcsml-item-number span' => 'color:{{VALUE}}' ],
		] );

		$this->add_responsive_control( 'sm_inumber_bgrd', [
			'label'     => 'Item Number Border Radius',
			'type'      => \Elementor\Controls_Manager::SLIDER,
			'range'     => array( 'px' => array( 'min' => 0, 'max' => 100, ) ),
			'selectors' => [ '{{WRAPPER}} .pcsml-item-number span' => 'border-radius:{{SIZE}}px' ],
		] );

		$this->add_responsive_control( 'sm_inumber_bdw', [
			'label'     => 'Item Number Border Width',
			'type'      => \Elementor\Controls_Manager::SLIDER,
			'range'     => array( 'px' => array( 'min' => 0, 'max' => 100, ) ),
			'selectors' => [ '{{WRAPPER}} .pcsml-item-number span' => 'border:{{SIZE}}px solid var(--pcborder-cl)' ],
		] );

		$this->add_control( 'sm_inumber_bdcolor', [
			'label'     => 'Item Number Border Color',
			'type'      => \Elementor\Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .pcsml-item-number span' => 'border-color:{{VALUE}}' ],
		] );

		$this->add_control( 'sm_inumber_bgcolor', [
			'label'     => 'Item Number Background Color',
			'type'      => \Elementor\Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .pcsml-item-number span' => 'background-color:{{VALUE}}' ],
		] );

		$this->add_control( 'sm_heading_title_03', [
			'label' => 'Description Text',
			'type'  => \Elementor\Controls_Manager::HEADING,
		] );

		$this->add_group_control( \Elementor\Group_Control_Typography::get_type(), array(
			'name'     => 'sm_desc_typo',
			'label'    => __( 'Description Text Typo', 'soledad' ),
			'selector' => '{{WRAPPER}} .pcsml-desc, {{WRAPPER}} .pcsml-desc p',
		) );

		$this->add_control( 'sm_desc_color', [
			'label'     => 'Description Text Color',
			'type'      => \Elementor\Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .pcsml-desc' => 'color:{{VALUE}}' ],
		] );

		$this->add_control( 'sm_heading_title_04', [
			'label' => 'Navigation',
			'type'  => \Elementor\Controls_Manager::HEADING,
		] );

		$this->add_responsive_control( 'sn_nav_spacing', [
			'label'     => 'Navigation Spacing',
			'type'      => \Elementor\Controls_Manager::SLIDER,
			'range'     => [
				'px' => [
					'min' => 0,
					'max' => 1200,
				],
			],
			'condition' => array( 'smartlists_style' => '4' ),
			'selectors' => [ '{{WRAPPER}} .pcsml-pagination' => 'margin:{{SIZE}}px 0' ],
		] );

		$this->add_control( 'sn_nav_bdcolor', [
			'label'     => 'Navigation Border Color',
			'type'      => \Elementor\Controls_Manager::COLOR,
			'condition' => array( 'smartlists_style' => '6' ),
			'selectors' => [ '{{WRAPPER}} .pcsml_style_6' => '--pcborder-cl:{{VALUE}}' ],
		] );

		$this->add_group_control( \Elementor\Group_Control_Typography::get_type(), array(
			'name'      => 'sn_nav_dropdown_typo',
			'label'     => __( 'Navigation Dropdown Typo', 'soledad' ),
			'selector'  => '{{WRAPPER}} .pcsml-dropdown-wrap .pcsml-dropdown',
			'condition' => array( 'smartlists_style' => '6' ),
		) );

		$this->add_control( 'sn_nav_dropdown_color', [
			'label'     => 'Navigation Dropdown Color',
			'type'      => \Elementor\Controls_Manager::COLOR,
			'condition' => array( 'smartlists_style' => '6' ),
			'selectors' => [ '{{WRAPPER}} .pcsml-dropdown-wrap .pcsml-dropdown' => 'color:{{VALUE}}' ],
		] );

		$this->add_control( 'sn_nav_dropdowncolor', [
			'label'     => 'Navigation Dropdown Background Color',
			'type'      => \Elementor\Controls_Manager::COLOR,
			'condition' => array( 'smartlists_style' => '6' ),
			'selectors' => [ '{{WRAPPER}} .pcsml-dropdown-wrap .pcsml-dropdown' => 'background:{{VALUE}}' ],
		] );

		$this->add_control( 'sn_nav_bgcolor', [
			'label'     => 'Navigation Background Color',
			'type'      => \Elementor\Controls_Manager::COLOR,
			'condition' => array( 'smartlists_style' => '6' ),
			'selectors' => [ '{{WRAPPER}} .pcsml-dropdown-wrap' => 'background-color:{{VALUE}}' ],
		] );

		$this->add_responsive_control( 'sn_nav_bgradius', [
			'label'     => 'Navigation Border Radius',
			'type'      => \Elementor\Controls_Manager::SLIDER,
			'condition' => array( 'smartlists_style' => '6' ),
			'range'     => array( 'px' => array( 'min' => 0, 'max' => 100, ) ),
			'selectors' => [ '{{WRAPPER}} .pcsml-dropdown-wrap' => 'border-radius:{{SIZE}}px' ],
		] );

		$this->add_group_control( \Elementor\Group_Control_Typography::get_type(), array(
			'name'     => 'sn_btn_typo',
			'label'    => __( 'Button Text Typo', 'soledad' ),
			'selector' => '{{WRAPPER}} .pcsml-button',
		) );

		$this->add_control( 'sn_btn_color', [
			'label'     => 'Button Color',
			'type'      => \Elementor\Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .pcsml-button' => 'color:{{VALUE}}' ],
		] );

		$this->add_control( 'sn_btn_bgcolor', [
			'label'     => 'Button Background Color',
			'type'      => \Elementor\Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .pcsml-button' => 'background-color:{{VALUE}}' ],
		] );

		$this->add_control( 'sn_btn_hcolor', [
			'label'     => 'Button Hover Color',
			'type'      => \Elementor\Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .pcsml-button:hover' => 'color:{{VALUE}}' ],
		] );

		$this->add_control( 'sn_btn_hbgcolor', [
			'label'     => 'Button Hover Background Color',
			'type'      => \Elementor\Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .pcsml-button:hover' => 'background-color:{{VALUE}}' ],
		] );

		$this->add_responsive_control( 'sn_btn_bgrd', [
			'label'     => 'Button Border Radius',
			'type'      => \Elementor\Controls_Manager::SLIDER,
			'range'     => array( 'px' => array( 'min' => 0, 'max' => 100, ) ),
			'selectors' => [ '{{WRAPPER}} .pcsml-button' => 'border-radius:{{SIZE}}px' ],
		] );

		$this->add_responsive_control( 'sn_btn_bdw', [
			'label'     => 'Button Border Width',
			'type'      => \Elementor\Controls_Manager::SLIDER,
			'range'     => array( 'px' => array( 'min' => 0, 'max' => 100, ) ),
			'selectors' => [ '{{WRAPPER}} .pcsml-button' => 'border:{{SIZE}}px solid var(--pcborder-cl)' ],
		] );

		$this->add_control( 'sn_btn_bdcolor', [
			'label'     => 'Button Border Color',
			'type'      => \Elementor\Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .pcsml-button' => 'border-color:{{VALUE}}' ],
		] );

		$this->add_control( 'sn_btn_bdhcolor', [
			'label'     => 'Button Hover Border Color',
			'type'      => \Elementor\Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .pcsml-button:hover' => 'border-color:{{VALUE}}' ],
		] );

		$this->end_controls_section();

		$this->start_controls_section( 'color_style', [
			'label' => esc_html__( 'Standard Content Color & Styles', 'soledad' ),
			'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
		] );

		$this->add_group_control( \Elementor\Group_Control_Typography::get_type(), array(
			'name'     => 'heading_typo',
			'label'    => __( 'Typography for General Content', 'soledad' ),
			'selector' => '{{WRAPPER}} .post-entry',
		) );

		$this->add_control( 'main-text-color', [
			'label'     => 'Text Color',
			'type'      => \Elementor\Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .post-entry' => 'color:{{VALUE}}' ],
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
		$settings            = $this->get_settings();
		$block_style         = $settings['block_style'];
		$heading_style       = $settings['heading_style'];
		$placeholder1        = $this->random_image_url();
		$placeholder2        = $this->random_image_url();
		$placeholder3        = $this->random_image_url();
		$placeholder_content = '
                    <h3>The New Ways</h3>
                        <p><img class="alignnone wp-image-' . $placeholder1['id'] . ' size-full" src="' . $placeholder1['url'] . '" alt="" /></p>
                        <p>Focused on quality and innovation, our designers work closely with their clients to craft unique and highly unique fashion pieces.</p>
                    <h3>Chat with The Content</h3>
                         <p><img class="alignnone wp-image-' . $placeholder2['id'] . ' size-full" src="' . $placeholder2['url'] . '" alt="" /></p>
                        <p>Our extensive catalogue consists of the very latest fashion clothing and accessories from the most fashionable designers in the world. Focusing on quality and innovation, our designers work closely with their clients to craft unique and highly unique fashion pieces.</p>
                    <h3>#1 WordPress Theme</h3>
                    <p><img class="alignnone wp-image-' . $placeholder3['id'] . ' size-full" src="' . $placeholder3['url'] . '" alt="" /></p>
                    <p>We offer an excellent range of dresswear, accessories and footwear, in a wide variety of styles. </p>
                    [penci_end_smart_list]
                
                    <p>Sample Content after smart list end. Our goal is to provide you with an excellent, flexible and adaptable shopping experience. We believe that your shopping experience is one of the most vital aspects for your overall success and we are passionate about taking care of our clients and serving them with quality, dependable products. We have our website, Facebook page, Instagram account and various social media channels where we are continually improving our products and services. We also have an active online store of our own. </p>
                ';
		?>
        <div class="post-entry <?php echo 'blockquote-' . $block_style; ?> <?php echo 'heading-' . $heading_style; ?>">
            <div class="inner-post-entry entry-content" id="penci-post-entry-inner">

				<?php do_action( 'penci_action_before_the_content' ); ?>

				<?php
				$placeholder_content = str_replace( ']]>', ']]&gt;', $placeholder_content );
				$smart_list_content  = penci_smartlists( [
					'style'               => $settings['smartlists_style'],
					'content'             => $placeholder_content,
					'order'               => $settings['smartlists_order'],
					'h'                   => $settings['smartlists_h'],
					'extract_first_image' => true,
					'sm_title_tag'        => $settings['smartlists_heading_tag'] ? $settings['smartlists_heading_tag'] : $settings['smartlists_h'],
					'sm_ad'               => '',
					'first_image_size'    => $settings['smartlists_img_size'],
					'first_image_msize'   => $settings['smartlists_img_msize'],
					'disablelazy'         => false,
				] );
				echo $smart_list_content;
				?>

				<?php do_action( 'penci_action_after_the_content' ); ?>

                <div class="penci-single-link-pages">
					<?php wp_link_pages(); ?>
                </div>
            </div>
        </div>
		<?php
	}

	public function random_image_url() {
		$id          = 0;
		$url         = PENCI_SOLEDAD_URL . '/inc/template-builder/placeholder.php?w=1920&h=800';
		$attachments = get_posts( [ 'post_type' => 'attachment', 'posts_per_page' => 1, 'orderby' => 'rand' ] );
		if ( ! empty( $attachments ) && isset( $attachments[0] ) ) {
			$url = wp_get_attachment_url( $attachments[0]->ID );
			$id  = $attachments[0]->ID;
		}

		return [ 'url' => $url, 'id' => $id ];
	}

	protected function builder_content() {
		$settings      = $this->get_settings();
		$block_style   = $settings['block_style'];
		$heading_style = $settings['heading_style'];
		?>
        <div class="post-entry <?php echo 'blockquote-' . $block_style; ?> <?php echo 'heading-' . $heading_style; ?>">
            <div class="inner-post-entry entry-content" id="penci-post-entry-inner">

				<?php do_action( 'penci_action_before_the_content' ); ?>

				<?php
				$content = strip_shortcodes( get_the_content() );
				$content = apply_filters( 'the_content', $content );
				$content = str_replace( ']]>', ']]&gt;', $content );
				remove_filter( 'the_content', 'penci_insert_post_content_ads' );
				$smart_list_content = penci_smartlists( [
					'style'               => $settings['smartlists_style'],
					'content'             => $content,
					'order'               => $settings['smartlists_order'],
					'h'                   => $settings['smartlists_h'],
					'extract_first_image' => true,
					'sm_title_tag'        => $settings['smartlists_heading_tag'] ? $settings['smartlists_heading_tag'] : $settings['smartlists_h'],
					'first_image_size'    => $settings['smartlists_img_size'],
					'first_image_msize'   => $settings['smartlists_img_msize'],
					'disablelazy'         => false,
				] );
				echo $smart_list_content;
				?>

				<?php do_action( 'penci_action_after_the_content' ); ?>

                <div class="penci-single-link-pages">
					<?php wp_link_pages(); ?>
                </div>
            </div>
        </div>
		<?php
	}
}
