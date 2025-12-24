<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}


class PenciHeaderSearch extends \Elementor\Widget_Base {

	public function get_title() {
		return esc_html__( 'Header - Search Icon', 'soledad' );
	}

	public function get_icon() {
		return 'eicon-search';
	}

	public function get_categories() {
		return [ 'penci-header-builder' ];
	}

	public function get_keywords() {
		return [ 'search' ];
	}

	public function get_name() {
		return 'penci-header-search';
	}

	public function get_script_depends() {
		return [ 'penci-header-search' ];
	}

	protected function register_controls() {

		$this->start_controls_section( 'content_section', [
			'label' => esc_html__( 'General', 'soledad' ),
			'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
		] );

		$this->add_control( 'search_style', [
			'label'   => esc_html__( 'Search Style', 'soledad' ),
			'type'    => \Elementor\Controls_Manager::SELECT,
			'default' => 'showup',
			'options' => [
				'showup'  => 'Default (Slide Up)',
				'overlay' => 'Overlay',
				'popup'   => 'Popup',
			]
		] );

		$this->add_control( 'btn_style', [
			'label'   => esc_html__( 'Button Style', 'soledad' ),
			'type'    => \Elementor\Controls_Manager::SELECT,
			'default' => 'customize',
			'options' => [
				'customize' => __( 'Default', 'soledad' ),
				'style-4'   => __( 'Filled', 'soledad' ),
				'style-1'   => __( 'Bordered', 'soledad' ),
				'style-2'   => __( 'Link', 'soledad' ),
			]
		] );

		$this->add_responsive_control( 'btn_icon_size', [
			'label'     => esc_html__( 'Icon Size', 'soledad' ),
			'type'      => \Elementor\Controls_Manager::SLIDER,
			'selectors' => [ '{{WRAPPER}} .top-search-classes > a > i' => 'font-size:{{SIZE}}px' ]
		] );

		$this->add_responsive_control( 'btn_iconb_size', [
			'label'     => esc_html__( 'Box Size', 'soledad' ),
			'type'      => \Elementor\Controls_Manager::SLIDER,
			'selectors' => [ '{{WRAPPER}} .top-search-classes > a' => 'width:{{SIZE}}px;height:{{SIZE}}px;display:flex;align-items: center;justify-content: center;' ]
		] );

		$this->end_controls_section();

		// style
		$this->start_controls_section( 'content_style', [
			'label' => esc_html__( 'Style', 'soledad' ),
			'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
		] );

		$this->add_control( 'search_btn_heading', [
			'label' => esc_html__( 'Search Button', 'soledad' ),
			'type'  => \Elementor\Controls_Manager::HEADING,
		] );

		$this->add_control( 'search_bd_color', [
			'label'     => esc_html__( 'Border Color', 'soledad' ),
			'type'      => \Elementor\Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .top-search-classes > a' => 'border-color:{{VALUE}}',
			]
		] );

		$this->add_control( 'search_bg_color', [
			'label'     => esc_html__( 'Background Color', 'soledad' ),
			'type'      => \Elementor\Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .top-search-classes > a' => 'background-color:{{VALUE}}',
			]
		] );

		$this->add_control( 'search_icon_color', [
			'label'     => esc_html__( 'Icon Color', 'soledad' ),
			'type'      => \Elementor\Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .top-search-classes > a, {{WRAPPER}} .top-search-classes > a i' => 'color:{{VALUE}}',
			]
		] );

		$this->add_control( 'search_btn_bdradius', [
			'label'     => esc_html__( 'Border Radius', 'soledad' ),
			'type'      => \Elementor\Controls_Manager::DIMENSIONS,
			'selectors' => [
				'{{WRAPPER}} .top-search-classes > a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			]
		] );


		$this->add_control( 'search_form_heading', [
			'label' => esc_html__( 'Search Form', 'soledad' ),
			'type'  => \Elementor\Controls_Manager::HEADING,
		] );

		$this->add_control( 'search_btn_bd_color', [
			'label'     => esc_html__( 'Border Color', 'soledad' ),
			'type'      => \Elementor\Controls_Manager::COLOR,
			'condition' => [ 'search_style' => 'showup' ],
			'selectors' => [
				'.header-search-style-showup {{WRAPPER}} .show-search:before' => 'border-bottom-color:{{VALUE}}',
				'.header-search-style-showup {{WRAPPER}} .show-search'        => 'border-top-color:{{VALUE}}',
			]
		] );

		$this->add_control( 'search_btn_bg_color', [
			'label'     => esc_html__( 'Background Color', 'soledad' ),
			'type'      => \Elementor\Controls_Manager::COLOR,
			'condition' => [ 'search_style' => 'showup' ],
			'selectors' => [
				'.header-search-style-showup {{WRAPPER}} .show-search' => 'background-color:{{VALUE}}',
			]
		] );

		$this->add_group_control( \Elementor\Group_Control_Typography::get_type(), array(
			'name'     => 'input_txt_typo',
			'label'    => __( 'Input Typography', 'soledad' ),
			'selector' => '.header-search-style-showup {{WRAPPER}} .show-search form.pc-searchform input.search-input,.header-search-style-overlay .pc-wrapbuilder-header .show-search form.pc-searchform input.search-input',
		) );

		$this->add_control( 'search_inputboder_color', [
			'label'     => esc_html__( 'Input Border Color', 'soledad' ),
			'type'      => \Elementor\Controls_Manager::COLOR,
			'condition' => [ 'search_style' => 'showup' ],
			'selectors' => [
				'.header-search-style-showup {{WRAPPER}} .show-search form.pc-searchform input.search-input' => 'border-color:{{VALUE}}',
			]
		] );

		$this->add_control( 'search_inputbg_color', [
			'label'     => esc_html__( 'Input Background Color', 'soledad' ),
			'type'      => \Elementor\Controls_Manager::COLOR,
			'condition' => [ 'search_style' => 'showup' ],
			'selectors' => [
				'.header-search-style-showup {{WRAPPER}} .show-search form.pc-searchform input.search-input' => 'background-color:{{VALUE}}',
			]
		] );

		$this->add_responsive_control( 'search_form_wi', [
			'label'     => esc_html__( 'Search Form Width', 'soledad' ),
			'type'      => \Elementor\Controls_Manager::SLIDER,
			'range'     => array( 'px' => array( 'min' => 300, 'max' => 1170, ) ),
			'condition' => [ 'search_style' => 'showup' ],
			'selectors' => [
				'.header-search-style-showup {{WRAPPER}} .show-search' => 'width:{{SIZE}}px',
			]
		] );

		$this->add_responsive_control( 'search_form_pd', [
			'label'     => esc_html__( 'Search Form Padding', 'soledad' ),
			'type'      => \Elementor\Controls_Manager::DIMENSIONS,
			'condition' => [ 'search_style' => 'showup' ],
			'selectors' => [
				'.header-search-style-showup {{WRAPPER}} .show-search' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			]
		] );

		$this->add_responsive_control( 'search_form_boxshaodw', [
			'label'     => esc_html__( 'Search Form Box Shadow', 'soledad' ),
			'type'      => \Elementor\Controls_Manager::BOX_SHADOW,
			'condition' => [ 'search_style' => 'showup' ],
			'selectors' => [
				'.header-search-style-showup {{WRAPPER}} .show-search' => 'box-shadow: {{HORIZONTAL}}px {{VERTICAL}}px {{BLUR}}px {{SPREAD}}px {{COLOR}} {{box_shadow_position.VALUE}};',
			]
		] );

		$this->add_control( 'search_inputtxt_color', [
			'label'     => esc_html__( 'Input Text Color', 'soledad' ),
			'type'      => \Elementor\Controls_Manager::COLOR,
			'condition' => [ 'search_style' => 'overlay' ],
			'selectors' => [
				'{{WRAPPER}}'                                                                                 => '--pchd-sinput-txt:{{VALUE}}',
				'.header-search-style-overlay {{WRAPPER}} .show-search form.pc-searchform input.search-input' => 'color:{{VALUE}}',
			]
		] );

		$this->add_control( 'search_overlay_bd_color', [
			'label'     => esc_html__( 'Overlay Border Color', 'soledad' ),
			'type'      => \Elementor\Controls_Manager::COLOR,
			'condition' => [ 'search_style' => 'overlay' ],
			'selectors' => [
				'.header-search-style-overlay {{WRAPPER}} .show-search form.pc-searchform .pc-searchform-inner' => 'border-color:{{VALUE}}',
			]
		] );

		$this->add_control( 'search_overlay_bg_color', [
			'label'     => esc_html__( 'Overlay Background Color', 'soledad' ),
			'type'      => \Elementor\Controls_Manager::COLOR,
			'condition' => [ 'search_style' => 'overlay' ],
			'selectors' => [
				'.header-search-style-overlay {{wrapper}} .penci-header-builder .show-search' => 'background:{{VALUE}}',
			]
		] );

		$this->add_control( 'search_overlay_clx_color', [
			'label'     => esc_html__( 'Overlay Close Color', 'soledad' ),
			'type'      => \Elementor\Controls_Manager::COLOR,
			'condition' => [ 'search_style' => 'overlay' ],
			'selectors' => [
				'.header-search-style-overlay {{wrapper}} .penci-header-builder .show-search a.close-search' => 'color:{{VALUE}}',
			]
		] );

		$this->add_group_control( \Elementor\Group_Control_Typography::get_type(), array(
			'name'     => 'search_btn_typo',
			'label'    => __( 'Typography', 'soledad' ),
			'selector' => '.header-search-style-showup {{WRAPPER}} .show-search form.pc-searchform .searchsubmit',
		) );

		$this->add_control( 'search_btn_bgcolor', [
			'label'     => esc_html__( 'Button Background Color', 'soledad' ),
			'type'      => \Elementor\Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .penci_header_search_button_bg_color' => 'background-color:{{VALUE}}',
			]
		] );

		$this->add_control( 'search_btn_bghcolor', [
			'label'     => esc_html__( 'Button Hover Background Color', 'soledad' ),
			'type'      => \Elementor\Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .penci_header_search_button_bg_color:hover' => 'background-color:{{VALUE}}',
			]
		] );

		$this->add_control( 'search_btn_txtcolor', [
			'label'     => esc_html__( 'Button Text Color', 'soledad' ),
			'type'      => \Elementor\Controls_Manager::COLOR,
			'selectors' => [
				'.header-search-style-showup {{WRAPPER}} .show-search form.pc-searchform .searchsubmit' => 'background-color:{{VALUE}}',
			]
		] );

		$this->add_control( 'search_btn_txthcolor', [
			'label'     => esc_html__( 'Button Hover Text Color', 'soledad' ),
			'type'      => \Elementor\Controls_Manager::COLOR,
			'selectors' => [
				'.header-search-style-showup {{WRAPPER}} .show-search form.pc-searchform .searchsubmit:hover' => 'background-color:{{VALUE}}',
			]
		] );

		$this->end_controls_section();

	}

	protected function render() {
		$settings     = $this->get_settings();
		$search_style = $settings['search_style'];
		$btn_style    = $settings['btn_style'];
		add_filter( 'penci_topbar_search_style', function ( $val ) use ( $search_style ) {
			return $search_style;
		} );
		?>
        <div class="pc-elehdbd-search pc-builder-element penci-top-search top-search-classes">
            <a href="#" aria-label="Search"
               class="search-click pc-button-define-<?php echo $btn_style; ?>">
                <i class="penciicon-magnifiying-glass"></i>
            </a>
            <div class="show-search pcbds-<?php echo $search_style; ?>">
			<?php if ( 'popup' == $search_style ) : ?>
				<h3 class="pc-search-top-title">
					<?php echo penci_get_setting( 'penci_trans_search_title' ); ?>
				</h3>
			<?php endif; ?>
				<?php penci_search_form( [
					'innerclass'     => true,
					'innerclass_css' => 'pc-searchform-inner pc-eajxsearch'
				] ); ?>
				<?php if ( 'popup' == $search_style ) : ?>
                    <div class="pc-search-suggest-term post-tags">
						<?php wp_list_categories( array(
								'title_li'   => '',
								'style'      => '',
								'separator'  => '',
								'orderby'    => 'name',
								'show_count' => false,
								'taxonomy'   => 'category',
								'number'     => 10,
								'depth'      => 1,
						) ); ?>
					</div>
					<?php
					$recent_posts = get_posts( array(
						'numberposts' => 4,
						'post_status' => 'publish',
					) );
					if ( $recent_posts ) : ?>
                        <div class="pc-search-recent-posts">
                            <h3 class="pc-search-recent-posts-title">
								<?php echo penci_get_setting( 'penci_trans_recent' ); ?>
                            </h3>
                            <div class="penci-smalllist pcsl-wrapper pwsl-id-default">
                                <div class="pcsl-inner penci-clearfix pcsl-grid pencipw-hd-text pcsl-imgpos-top pcsl-col-4 pcsl-tabcol-2 pcsl-mobcol-1">
									<?php foreach ( $recent_posts as $post ) : setup_postdata( $post ); ?>
                                        <div class="pcsl-item">
                                            <div class="pcsl-itemin">
                                                <div class="pcsl-iteminer">

                                                    <div class="pcsl-thumb">

                                                        <a <?php echo penci_layout_bg( penci_get_featured_image_size( get_the_ID(), 'penci-thumb' ), false ); ?>
                                                                href="<?php the_permalink(); ?>"
                                                                title="<?php echo wp_strip_all_tags( get_the_title() ); ?>"
                                                                class="<?php echo penci_layout_bg_class( false ); ?> penci-image-holder">
															<?php echo penci_layout_img( penci_get_featured_image_size( get_the_ID(), 'penci-thumb' ), get_the_title(), false ); ?>
                                                        </a>

                                                    </div>
                                                    <div class="pcsl-content">

                                                        <div class="pcsl-title">
                                                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                                        </div>

                                                        <div class="grid-post-box-meta pcsl-meta">
                                                            <span class="sl-date"><?php penci_soledad_time_link(); ?></span>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
									<?php endforeach;
									wp_reset_postdata(); ?>
                                </div>
                            </div>
                        </div>
					<?php
					endif;
					wp_reset_postdata();
				endif;
				?>
                <a href="#" aria-label="Close" class="search-click close-search"><i class="penciicon-close-button"></i></a>
            </div>
        </div>
		<?php if ( 'popup' == $search_style ) : ?>
			<div class="pc-search-popup-overlay"></div>
		<?php endif; ?>
		<?php
	}
}