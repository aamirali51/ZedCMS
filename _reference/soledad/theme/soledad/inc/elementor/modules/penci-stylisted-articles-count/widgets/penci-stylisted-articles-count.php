<?php

namespace PenciSoledadElementor\Modules\PenciStylistedArticlesCount\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use PenciSoledadElementor\Base\Base_Widget;
use PenciSoledadElementor\Modules\QueryControl\Module as Query_Control;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class PenciStylistedArticlesCount extends Base_Widget {

	public function get_name() {
		return 'penci-stylisted-articles-count';
	}

	public function get_title() {
		return penci_get_theme_name( 'Penci' ) . ' ' . esc_html__( ' Stylisted Articles Count', 'soledad' );
	}

	public function get_icon() {
		return 'eicon-post-list';
	}

	public function get_categories() {
		return [ 'penci-elements' ];
	}

	public function get_keywords() {
		return array( 'posts' );
	}

	public function get_script_depends() {
		return [ 'penci_widgets_ajax' ];
	}

	protected function register_controls() {


		// Section layout
		$this->start_controls_section(
			'section_page_layout', array(
				'label' => esc_html__( 'Layout', 'soledad' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control( 'columns', array(
			'label'   => __( 'Columns', 'soledad' ),
			'type'    => Controls_Manager::SELECT,
			'default' => '',
			'options' => [
				'' => 'Default with Featured Post',
				2  => '2 Columns',
				3  => '3 Columns',
				4  => '4 Columns',
				5  => '5 Columns',
			],
		) );

		$this->add_control( 'tcolumns', array(
			'label'   => __( 'Columns for Tablet', 'soledad' ),
			'type'    => Controls_Manager::SELECT,
			'default' => 2,
			'options' => [
				1  => '1 Column',
				2  => '2 Columns',
				3  => '3 Columns',
			],
		) );

		$this->add_control( 'mcolumns', array(
			'label'   => __( 'Columns for Mobile', 'soledad' ),
			'type'    => Controls_Manager::SELECT,
			'default' => 1,
			'options' => [
				1  => '1 Column',
				2  => '2 Columns',
			],
		) );

		$this->add_responsive_control(
			'penci_img_ratio', array(
				'label'          => __( 'Image Ratio', 'soledad' ),
				'type'           => Controls_Manager::SLIDER,
				'default'        => array( 'size' => 0.66 ),
				'tablet_default' => array( 'size' => '' ),
				'mobile_default' => array( 'size' => 0.5 ),
				'condition'      => array( 'columns' => '' ),
				'range'          => array( 'px' => array( 'min' => 0.1, 'max' => 2, 'step' => 0.01 ) ),
				'selectors'      => array(
					'{{WRAPPER}} .penci-image-holder:before' => 'padding-top: calc( {{SIZE}} * 100% );',
				),
			)
		);
		$this->add_control(
			'thumb_size', array(
				'label'     => __( 'Custom Image size', 'soledad' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'penci-masonry-thumb',
				'condition' => array( 'columns' => '' ),
				'options'   => $this->get_list_image_sizes( true ),
			)
		);

		$this->add_control(
			'title_length', array(
				'label'       => __( 'Custom words length for post titles', 'soledad' ),
				'type'        => Controls_Manager::NUMBER,
				'description' => __( 'If your post titles is too long - You can use this option for trim it. Fill number value here.', 'soledad' ),
			)
		);

		$this->add_control(
			'show_author', array(
				'label' => __( 'Show Author Name?', 'soledad' ),
				'type'  => Controls_Manager::SWITCHER,
			)
		);
		$this->add_control(
			'hide_postdate', array(
				'label' => __( 'Hide post date?', 'soledad' ),
				'type'  => Controls_Manager::SWITCHER,
			)
		);
		$this->add_control(
			'show_comment', array(
				'label' => __( 'Show Comment Count?', 'soledad' ),
				'type'  => Controls_Manager::SWITCHER,
			)
		);
		$this->add_control(
			'show_postviews', array(
				'label' => __( 'Show Post Views?', 'soledad' ),
				'type'  => Controls_Manager::SWITCHER,
			)
		);

		$this->add_control(
			'showborder', array(
				'label'     => __( 'Remove Border at The Bottom?', 'soledad' ),
				'type'      => Controls_Manager::SWITCHER,
				'selectors' => array(
					'{{WRAPPER}} ul li' => 'border-bottom: none !important'
				)
			)
		);

		$this->add_control(
			'rp_rows_gap', array(
				'label'              => __( 'Rows Gap', 'soledad' ),
				'type'               => Controls_Manager::SLIDER,
				'range'              => array( 'px' => array( 'min' => 0, 'max' => 200 ) ),
				'frontend_available' => true,
				'selectors'          => array(
					'{{WRAPPER}} ul li' => 'margin-bottom: calc({{SIZE}}{{UNIT}}/2); padding-bottom: calc({{SIZE}}{{UNIT}}/2)',
				),
			)
		);

		$this->add_control( 'ajaxnav', array(
			'label'   => __( 'Ajax Posts Navigation?', 'soledad' ),
			'type'    => Controls_Manager::SELECT,
			'default' => '',
			'options' => [
				''    => 'Disable',
				'nav' => 'Next/Previous Buttons',
				'btn' => 'Load More Posts Button',
			]
		) );

		$this->end_controls_section();
		$this->register_query_section_controls();
		$this->register_block_title_section_controls();
		$this->start_controls_section(
			'section_post_style',
			array(
				'label' => __( 'Post', 'soledad' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);
		$this->add_control(
			'pborder_color', array(
				'label'     => __( 'Borders Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ul li' => 'border-color: {{VALUE}};',
				)
			)
		);

		$this->add_control(
			'heading_ptitle_style', array(
				'label' => __( 'Post Title', 'soledad' ),
				'type'  => Controls_Manager::HEADING
			)
		);

		$this->add_control(
			'ptitle_color', array(
				'label'     => __( 'Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .popularpost_item .pcpopular_new_post_title'   => 'color: {{VALUE}};',
					'{{WRAPPER}} .popularpost_item .pcpopular_new_post_title a' => 'color: {{VALUE}};',
				)
			)
		);
		$this->add_control(
			'ptitle_hcolor', array(
				'label'     => __( 'Hover Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .popularpost_item .pcpopular_new_post_title a:hover' => 'color: {{VALUE}};',
				)
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(), array(
				'name'     => 'ptitle_typo',
				'selector' => '{{WRAPPER}} .popularpost_item .pcpopular_new_post_title'
			)
		);

		$this->add_responsive_control(
			'featitle_size', array(
				'label'     => __( 'Font size for Featured/Big Post', 'soledad' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array( 'px' => array( 'min' => 0, 'max' => 100, ) ),
				'selectors' => array(
					'{{WRAPPER}} .popularpost_item.first .pcpopular_new_post_title' => 'font-size: {{SIZE}}px'
				),
			)
		);

		$this->add_control(
			'heading_pmeta_style', array(
				'label' => __( 'Post Meta', 'soledad' ),
				'type'  => Controls_Manager::HEADING
			)
		);

		$this->add_control(
			'pmeta_color', array(
				'label'     => __( 'Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .popularpost_meta' => 'color: {{VALUE}};',
				)
			)
		);
		$this->add_control(
			'pmeta_acolor', array(
				'label'     => __( 'Color for Author Name & Comment Count', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .popularpost_meta a' => 'color: {{VALUE}};',
				)
			)
		);
		$this->add_control(
			'pmeta_ahcolor', array(
				'label'     => __( 'Hover Color for Author Name & Comment Count', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .popularpost_meta a:hover' => 'color: {{VALUE}};',
				)
			)
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(), array(
				'name'     => 'pmeta_typo',
				'selector' => '{{WRAPPER}} .popularpost_meta',
			)
		);

		$this->add_control(
			'heading_number_style', array(
				'label' => __( 'Number Style', 'soledad' ),
				'type'  => Controls_Manager::HEADING,
			)
		);

		$this->add_control(
			'number_color', array(
				'label'     => __( 'Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .popularpost_item .pcpopular_new_post_title a:before' => 'color: {{VALUE}};',
				)
			)
		);
		$this->add_control(
			'number_bgcolor', array(
				'label'     => __( 'Background Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .popularpost_item .pcpopular_new_post_title a:before' => 'background-color: {{VALUE}};',
				)
			)
		);
		$this->add_control(
			'number_bdcolor', array(
				'label'     => __( 'Border Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .popularpost_item .pcpopular_new_post_title a:before' => 'border: 1px solid {{VALUE}};',
				)
			)
		);

		$this->add_control(
			'number_hcolor', array(
				'label'     => __( 'Hover Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .popularpost_item:hover .pcpopular_new_post_title a:before' => 'color: {{VALUE}};',
				)
			)
		);
		$this->add_control(
			'number_bghcolor', array(
				'label'     => __( 'Hover Background Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .popularpost_item:hover .pcpopular_new_post_title a:before' => 'background-color: {{VALUE}};',
				)
			)
		);
		$this->add_control(
			'number_bdhcolor', array(
				'label'     => __( 'Hover Border Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .popularpost_item:hover .pcpopular_new_post_title a:before' => 'border: 1px solid {{VALUE}};',
				)
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(), array(
				'name'     => 'number_typo_small',
				'label'    => __( 'Number Typography on Small Posts', 'soledad' ),
				'selector' => '{{WRAPPER}} .popularpost_item:not(.first) .pcpopular_new_post_title a:before',
			)
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(), array(
				'name'     => 'number_typo_big',
				'label'    => __( 'Number Typography on Big Post', 'soledad' ),
				'selector' => '{{WRAPPER}} .popularpost_item.first .pcpopular_new_post_title a:before, {{WRAPPER}} .popularpost_item.first:hover .pcpopular_new_post_title a:before',
			)
		);
		$this->add_responsive_control(
			'number_size_small', array(
				'label'     => __( 'Number Size on Small Posts', 'soledad' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array( 'px' => array( 'min' => 10, 'max' => 200, 'step' => 1 ) ),
				'selectors' => array(
					'{{WRAPPER}} .popularpost_item:not(.first) .pcpopular_new_post_title a:before' => 'width: {{SIZE}}px;height: {{SIZE}}px;line-height: {{SIZE}}px;',
					'{{WRAPPER}} .popularpost_item:not(.first)'                                    => 'padding-left: calc({{SIZE}}px + 35px);',
				)
			)
		);
		$this->add_responsive_control(
			'number_size_big', array(
				'label'     => __( 'Number Size on Big Post', 'soledad' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array( 'px' => array( 'min' => 10, 'max' => 200, 'step' => 1 ) ),
				'selectors' => array(
					'{{WRAPPER}} .popularpost_item.first .pcpopular_new_post_title a::before, {{WRAPPER}} .popularpost_item.first:hover .pcpopular_new_post_title a:before' => 'width: {{SIZE}}px;height: {{SIZE}}px;line-height: {{SIZE}}px;',
					'{{WRAPPER}} .popularpost_item.first .pcpopular_new_post_title,{{WRAPPER}} .popularpost_item.first .popularpost_meta'                                   => 'padding-left: calc({{SIZE}}px + 35px);',
				)
			)
		);

		$this->end_controls_section();

		$this->start_controls_section( 'post_navigation_style', array(
			'label'     => __( 'Post Navigation', 'soledad' ),
			'tab'       => Controls_Manager::TAB_STYLE,
			'condition' => [ 'ajaxnav!' => '' ]
		) );

		$this->add_group_control( Group_Control_Typography::get_type(), array(
			'name'     => 'post_nav_btn_typo',
			'label'    => __( 'Button Typography', 'soledad' ),
			'selector' => '{{WRAPPER}} .penci-pagination.penci-ajax-nav .pcajx-btn,{{WRAPPER}} .penci-pagination.penci-ajax-nav .pcajx-btn .pcnav-title, {{WRAPPER}} .penci-pagination.penci-ajax-more a.penci-ajax-more-button',
		) );

		$this->add_control( 'post_nav_btn_color', array(
			'label'     => __( 'Button Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => array(
				'{{WRAPPER}} .penci-pagination.penci-ajax-nav .pcajx-btn,{{WRAPPER}} .penci-pagination.penci-ajax-nav .pcajx-btn .pcnav-title' => 'color: {{VALUE}};',
				'{{WRAPPER}} .penci-pagination.penci-ajax-more a.penci-ajax-more-button'                                                       => 'color: {{VALUE}};',
			),
		) );

		$this->add_control( 'post_nav_btn_bdcolor', array(
			'label'     => __( 'Button Border Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => array(
				'{{WRAPPER}} .penci-pagination.penci-ajax-nav .pcajx-btn,{{WRAPPER}} .penci-pagination.penci-ajax-nav .pcajx-btn .pcnav-title' => 'border-color: {{VALUE}};',
				'{{WRAPPER}} .penci-pagination.penci-ajax-more a.penci-ajax-more-button'                                                       => 'border-color: {{VALUE}};',
			),
		) );

		$this->add_control( 'post_nav_btn_bgcolor', array(
			'label'     => __( 'Button Background Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => array(
				'{{WRAPPER}} .penci-pagination.penci-ajax-nav .pcajx-btn,{{WRAPPER}} .penci-pagination.penci-ajax-nav .pcajx-btn .pcnav-title' => 'background-color: {{VALUE}};',
				'{{WRAPPER}} .penci-pagination.penci-ajax-more a.penci-ajax-more-button'                                                       => 'background-color: {{VALUE}};',
			),
		) );

		$this->add_control( 'post_nav_btn_hcolor', array(
			'label'     => __( 'Button Hover Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => array(
				'{{WRAPPER}} .penci-pagination.penci-ajax-nav .pcajx-btn:hover,{{WRAPPER}} .penci-pagination.penci-ajax-nav .pcajx-btn:hover .pcnav-title' => 'color: {{VALUE}};',
				'{{WRAPPER}} .penci-pagination.penci-ajax-more a.penci-ajax-more-button:hover'                                                             => 'color: {{VALUE}};',
			),
		) );

		$this->add_control( 'post_nav_btn_bdhcolor', array(
			'label'     => __( 'Button Border Hover Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => array(
				'{{WRAPPER}} .penci-pagination.penci-ajax-nav .pcajx-btn:hover,{{WRAPPER}} .penci-pagination.penci-ajax-nav .pcajx-btn:hover .pcnav-title' => 'border-color: {{VALUE}};',
				'{{WRAPPER}} .penci-pagination.penci-ajax-more a.penci-ajax-more-button:hover'                                                             => 'border-color: {{VALUE}};',
			),
		) );

		$this->add_control( 'post_nav_btn_bghcolor', array(
			'label'     => __( 'Button Background Hover Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => array(
				'{{WRAPPER}} .penci-pagination.penci-ajax-nav .pcajx-btn:hover,{{WRAPPER}} .penci-pagination.penci-ajax-nav .pcajx-btn:hover .pcnav-title' => 'background-color: {{VALUE}};',
				'{{WRAPPER}} .penci-pagination.penci-ajax-more a.penci-ajax-more-button:hover'                                                             => 'background-color: {{VALUE}};',
			),
		) );

		$this->end_controls_section();

		$this->register_block_title_style_section_controls();

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
		$settings         = $this->get_settings();
		$thumb_size       = $settings['thumb_size'] ? $settings['thumb_size'] : '';
		$original_postype = $settings['posts_post_type'];

		if ( in_array( $original_postype, [
				'current_query',
				'related_posts'
			] ) && penci_elementor_is_edit_mode() && penci_is_builder_template() ) {
			$settings['posts_post_type'] = 'post';
		}

		$query_args = Query_Control::get_query_args( 'posts', $settings );
		if ( in_array( $original_postype, [ 'current_query', 'related_posts' ] ) ) {
			$paged  = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
			$ppp    = $settings['posts_per_page'] ? $settings['posts_per_page'] : get_option( 'posts_per_page' );
			$ppp    = isset( $settings['arposts_per_page'] ) && $settings['arposts_per_page'] ? $settings['arposts_per_page'] : $ppp;
			$offset = 0;
			if ( $ppp ) {
				$query_args['posts_per_page'] = $ppp;
			}
			if ( $settings['arposts_new'] == 'yes' ) {
				$query_args['paged'] = 1;
			}
			if ( 0 < $settings['offset'] ) {
				$offset = $settings['offset'];
			}

			if ( ! empty( $settings['offset'] ) && $paged > 1 ) {
				$offset = $settings['offset'] + ( ( $paged - 1 ) * $ppp );
			}

			if ( $offset ) {
				$query_args['offset'] = $offset;
			}
		}
		$loop = new \WP_Query( $query_args );

		if ( ! $loop->have_posts() ) {
			return;
		}

		$title_length = intval( $settings['title_length'] );
		$postdate     = 'yes' == $settings['hide_postdate'] ? true : false;
		$showauthor   = 'yes' == $settings['show_author'] ? true : false;
		$showcomment  = 'yes' == $settings['show_comment'] ? true : false;
		$showviews    = 'yes' == $settings['show_postviews'] ? true : false;

		$ajax_tab_var = [
			'title_length'   => intval( $settings['title_length'] ),
			'hide_postdate'  => $settings['hide_postdate'],
			'show_author'    => $settings['show_author'],
			'show_comment'   => $settings['show_comment'],
			'show_postviews' => $settings['show_postviews'],
			'custom_query'   => $query_args,
			'columns'        => $settings['columns'],
			'tcolumns'       => $settings['tcolumns'],
			'mcolumns'       => $settings['mcolumns'],
		];

		$css_class = 'penci-block-vc penci_recent-posts-sc widget';
		$rand      = rand( 1000, 10000 );
		?>
        <div class="<?php echo esc_attr( $css_class ); ?>">
			<?php $this->markup_block_title( $settings, $this ); ?>
            <div class="penci-block_content">
				<?php
				get_template_part( 'inc/templates/popular_posts', '', [
					'loop'         => $loop,
					'class'        => 'demo',
					'showauthor'   => $showauthor,
					'postdate'     => $postdate,
					'showviews'    => $showviews,
					'showcomment'  => $showcomment,
					'title_length' => $title_length,
					'thumb'        => $thumb_size,
					'columns'      => $settings['columns'],
					'tcolumns'     => $settings['tcolumns'],
					'mcolumns'     => $settings['mcolumns'],
					'id'           => 'pcstylisted-acount-' . sanitize_text_field( $rand ),
					'data_attr'    => 'data-mes="' . penci_get_setting( 'penci_trans_no_more_posts' ) . '" data-max="' . esc_attr( $loop->max_num_pages ) . '" data-settings=\'' . json_encode( $ajax_tab_var ) . '\' data-paged="1" data-action="penci_stylisted_articles_count_ajax"',
				] );

				if ( isset( $settings['ajaxnav'] ) && $settings['ajaxnav'] == 'btn' ) {
					?>
                    <div class="penci-pagination penci-ajax-more pcwg-lposts">
                        <a class="penci-ajax-more-button penci-wgajx-btn"
                           href="#" aria-label="More Posts">
                            <span class="ajax-more-text"><?php echo penci_get_setting( 'penci_trans_load_more_posts' ); ?></span><span
                                    class="ajaxdot"></span><i
                                    class="penci-faicon fa fa-refresh"></i> </a>
                    </div>
					<?php
				} else if ( isset( $settings['ajaxnav'] ) && $settings['ajaxnav'] == 'nav' ) { ?>
                    <div class="penci-pagination penci-ajax-nav pcwg-lposts">
                        <span class="pcajx-btn penci-wgajx-btn prev disable"><?php echo penci_icon_by_ver( 'penciicon-left-chevron' ) . ' <span class="pcnav-title">' . penci_get_setting( 'penci_trans_back' ); ?></span></span>
                        <span class="pcajx-btn penci-wgajx-btn next"><?php echo '<span class="pcnav-title">' . penci_get_setting( 'penci_trans_next' ) . '</span>' . penci_icon_by_ver( 'penciicon-right-chevron' ); ?></span>
                    </div>
					<?php
				}
				if ( isset( $settings['ajaxnav'] ) && $settings['ajaxnav'] ) {
					?>
                    <div class="pcwgajx-ld-wrapper">
						<?php echo penci_get_html_animation_loading( 'df' ); ?>
                    </div>
					<?php
				}
				wp_reset_postdata();
				?>
            </div>
        </div>
		<?php
	}
}
