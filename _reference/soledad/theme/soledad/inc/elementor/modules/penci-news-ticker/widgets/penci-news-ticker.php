<?php

namespace PenciSoledadElementor\Modules\PenciNewsTicker\Widgets;

use PenciSoledadElementor\Base\Base_Widget;
use PenciSoledadElementor\Modules\QueryControl\Module;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class PenciNewsTicker extends Base_Widget {

	public function get_name() {
		return 'penci-news-ticker';
	}

	public function get_title() {
		return penci_get_theme_name( 'Penci' ) . ' ' . esc_html__( ' News Ticker', 'soledad' );
	}

	public function get_icon() {
		return 'eicon-anchor';
	}

	public function get_categories() {
		return [ 'penci-elements' ];
	}

	public function get_script_depends() {
		return [ 'penci-marquee' ];
	}

	public function get_keywords() {
		return array( 'facebook', 'social', 'embed', 'page' );
	}

	protected function register_controls() {


		$this->register_query_section_controls();
		$this->start_controls_section(
			'section_layout', array(
				'label' => esc_html__( 'Display', 'soledad' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'tpost_text', array(
				'label'   => __( 'Custom "Top Posts" Text', 'soledad' ),
				'type'    => Controls_Manager::TEXT,
				'default' => 'Top Posts',
			)
		);

		$this->add_control(
			'headline_style', array(
				'label'   => __( 'Style for "Top Posts" Text', 'soledad' ),
				'type'    => Controls_Manager::SELECT,
				'default' => '',
				'options' => array(
					''                => 'Default Style',
					'nticker-style-2' => 'Style 2',
					'nticker-style-3' => 'Style 3',
					'nticker-style-4' => 'Style 4'
				)
			)
		);

		$this->add_control(
			'navs_buttons', array(
				'label'     => __( 'Display Next/Prev Icons as Buttons', 'soledad' ),
				'type'      => Controls_Manager::SWITCHER,
				'selectors' => array(
					'{{WRAPPER}} .penci-trending-nav a'             => 'height: 24px; line-height: 24px; top: 4px; background: #313131; color: #fff; float: left;',
					'{{WRAPPER}} .penci-trending-nav a:first-child' => 'margin-right: 4px;',
				),
			)
		);

		$this->add_control(
			'move_navs', array(
				'label'     => __( 'Move Next/Prev Icons/Buttons To The Right Side', 'soledad' ),
				'type'      => Controls_Manager::SWITCHER,
				'selectors' => array(
					'{{WRAPPER}} .penci-topbar-trending .penci-trending-nav'          => 'float: right;',
					'{{WRAPPER}} .headline-title'                                     => 'margin-right: 10px;',
					'{{WRAPPER}} .headline-title.nticker-style-2'                     => 'margin-right: 18px;',
					'{{WRAPPER}} .headline-title.nticker-style-4'                     => 'margin-right: 19px;',
					'body.rtl {{WRAPPER}} .penci-topbar-trending .penci-trending-nav' => 'float: left;',
					'body.rtl {{WRAPPER}} .headline-title'                            => 'margin-right: 0; margin-left: 10px;',
					'body.rtl {{WRAPPER}} .headline-title.nticker-style-2'            => 'margin-left: 18px; margin-right:0;',
					'body.rtl {{WRAPPER}} .headline-title.nticker-style-4'            => 'margin-left: 19px; margin-right:0;',
				),
			)
		);

		$this->add_control(
			'headline_upper', array(
				'label'     => __( 'Turn off Uppercase for "Top Posts" text', 'soledad' ),
				'type'      => Controls_Manager::SWITCHER,
				'selectors' => array( '{{WRAPPER}} .headline-title' => 'text-transform: none;' ),
			)
		);

		$this->add_control(
			'title_length', array(
				'label'       => __( 'Custom Words Length for Post Titles', 'soledad' ),
				'type'        => Controls_Manager::NUMBER,
				'label_block' => true,
				'default'     => '',
			)
		);

		$this->add_control(
			'titles_upper', array(
				'label'     => __( 'Turn off Uppercase for Post Titles', 'soledad' ),
				'type'      => Controls_Manager::SWITCHER,
				'selectors' => array( '{{WRAPPER}} a.penci-topbar-post-title' => 'text-transform: none;' ),
			)
		);

		$this->add_control(
			'autoplay', array(
				'label' => __( 'Disable Auto Play', 'soledad' ),
				'type'  => Controls_Manager::SWITCHER
			)
		);

		$this->add_control(
			'autotime', array(
				'label'       => __( 'Autoplay Timeout', 'soledad' ),
				'type'        => Controls_Manager::NUMBER,
				'description' => __( '1000 = 1 second', 'soledad' ),
				'label_block' => true,
				'default'     => '3000',
			)
		);

		$this->add_control(
			'autospeed', array(
				'label'       => __( 'Autoplay Speed', 'soledad' ),
				'type'        => Controls_Manager::NUMBER,
				'description' => __( '1000 = 1 second', 'soledad' ),
				'label_block' => true,
				'default'     => '300'
			)
		);

		$this->add_control(
			'headline_anim', array(
				'label'   => __( 'Transition Animation', 'soledad' ),
				'type'    => Controls_Manager::SELECT,
				'default' => '',
				'options' => array(
					''             => 'Slide In Up',
					'slideInRight' => 'Fade In Right',
					'fadeIn'       => 'Fade In',
					'marquee'      => 'Marquee',
				)
			)
		);

		$this->end_controls_section();

		$this->register_block_title_section_controls();

		// Design
		$this->start_controls_section(
			'section_design_general',
			array(
				'label' => __( 'News Ticker', 'soledad' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'border_color',
			array(
				'label'     => __( 'Set Borders Around The News Ticker', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .penci-enews-ticker.penci-topbar-trending' => 'border: 1px solid {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'bg_color',
			array(
				'label'     => __( 'Background Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'body:not(.pcdm-enable) {{WRAPPER}} .penci-topbar-trending'                => 'background-color: {{VALUE}};',
					'body:not(.pcdm-enable) {{WRAPPER}} .penci-owl-carousel .owl-item'         => 'background-color: {{VALUE}};',
					'body:not(.pcdm-enable) {{WRAPPER}} .headline-title.nticker-style-3:after' => 'border-color: {{VALUE}};'
				),
			)
		);

		$this->add_control(
			'heading_tpostsec',
			array(
				'label'     => __( '"Top Posts" Section', 'soledad' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(), array(
				'name'     => 'headline_typo',
				'selector' => '{{WRAPPER}} .headline-title',
			)
		);

		$this->add_control(
			'tpost_bg',
			array(
				'label'     => __( '"Top Posts" Background Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'body:not(.pcdm-enable) {{WRAPPER}} .headline-title'                       => 'background-color: {{VALUE}};',
					'body:not(.pcdm-enable) {{WRAPPER}} .headline-title.nticker-style-2:after' => 'border-color: {{VALUE}};',
					'body:not(.pcdm-enable) {{WRAPPER}} .headline-title.nticker-style-4:after' => 'border-color: {{VALUE}};'
				),
			)
		);

		$this->add_control(
			'tpost_color',
			array(
				'label'     => __( '"Top Posts" Text Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .headline-title' => 'color: {{VALUE}};'
				),
			)
		);

		$this->add_responsive_control(
			'navs_size', array(
				'label'     => __( 'Font size for Next/Prev Icons', 'soledad' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array( 'px' => array( 'min' => 0, 'max' => 100, ) ),
				'selectors' => array( '{{WRAPPER}} .penci-trending-nav a' => 'font-size: {{SIZE}}px' ),
			)
		);

		$this->add_control(
			'navs_color',
			array(
				'label'     => __( 'Next/Prev Icons Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .penci-trending-nav a' => 'color: {{VALUE}};'
				),
			)
		);

		$this->add_control(
			'navs_hcolor',
			array(
				'label'     => __( 'Next/Prev Icons Hover Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .penci-trending-nav a:hover' => 'color: {{VALUE}};'
				),
			)
		);

		$this->add_control(
			'navs_bg',
			array(
				'label'     => __( 'Next/Prev Buttons Background Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => array( 'navs_buttons' => 'yes' ),
				'selectors' => array(
					'body:not(.pcdm-enable) {{WRAPPER}} .penci-trending-nav a' => 'background-color: {{VALUE}};'
				),
			)
		);

		$this->add_control(
			'navs_hbg',
			array(
				'label'     => __( 'Next/Prev Buttons Hover Background Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => array( 'navs_buttons' => 'yes' ),
				'selectors' => array(
					'body:not(.pcdm-enable) {{WRAPPER}} .penci-trending-nav a:hover' => 'background-color: {{VALUE}};'
				),
			)
		);

		$this->add_control(
			'heading_tposttitle',
			array(
				'label'     => __( 'Post Titles', 'soledad' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(), array(
				'name'     => 'ptitle_typo',
				'selector' => '{{WRAPPER}} a.penci-topbar-post-title',
			)
		);

		$this->add_control(
			'ptitle_color',
			array(
				'label'     => __( 'Post Title Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} a.penci-topbar-post-title' => 'color: {{VALUE}};'
				),
			)
		);

		$this->add_control(
			'ptitle_hcolor',
			array(
				'label'     => __( 'Post Title Hover Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} a.penci-topbar-post-title:hover' => 'color: {{VALUE}};'
				),
			)
		);

		$this->end_controls_section();

		$this->register_block_title_style_section_controls();

	}

	protected function render() {
		$settings = $this->get_settings();

		$query_args = Module::get_query_args( 'posts', $settings );

		$toptext = isset( $settings['tpost_text'] ) ? $settings['tpost_text'] : 'Top Posts';
		$rand    = rand( 100, 5000 );
		?>
		<?php $this->markup_block_title( $settings, $this ); ?>
        <div class="penci-enews-ticker penci-topbar-trending penci-enews-ticker-<?php echo $rand; ?>">

			<?php if ( $toptext ) {
				$toptext_style = $settings['headline_style'] ? $settings['headline_style'] : 'nticker-style-1';
				?>
                <span class="headline-title <?php echo $toptext_style; ?>"><?php echo $toptext; ?></span>
			<?php }

			$news             = new \WP_Query( $query_args );
			if ( $news->have_posts() ):
				$auto_play = $settings['autoplay'] ? $settings['autoplay'] : 'true';
				$auto_time    = $settings['autotime'] ? $settings['autotime'] : '3000';
				$auto_speed   = $settings['autospeed'] ? $settings['autospeed'] : '300';
				$title_length = $settings['title_length'] ? $settings['title_length'] : 8;
				$animation    = $settings['headline_anim'] ? $settings['headline_anim'] : 'slideInUp';
				$navs_buttons = isset( $settings['navs_buttons'] ) ? $settings['navs_buttons'] : false;
				$main_class   = $animation == 'marquee' ? 'no-df pcmarquee-slider' : 'pcdfswiper';
				if ( $animation != 'marquee' ) :
					?>
                    <span class="penci-trending-nav<?php if ( $navs_buttons ): echo ' penci-navs-buttons'; endif; ?>">
					<a class="penci-slider-prev" href="#"
                       aria-label="Previous"><?php penci_fawesome_icon( 'fas fa-angle-left' ); ?></a>
					<a class="penci-slider-next" href="#"
                       aria-label="Next"><?php penci_fawesome_icon( 'fas fa-angle-right' ); ?></a>
				</span>
				<?php endif; ?>
                <div class="<?php echo $main_class; ?> penci-owl-carousel swiper penci-owl-carousel-slider penci-headline-posts"
                     data-auto="<?php echo $auto_play; ?>" data-nav="false" data-autotime="<?php echo $auto_time; ?>"
                     data-speed="<?php echo $auto_speed; ?>" data-anim="<?php echo $animation; ?>">
                    <div class="swiper-wrapper">
						<?php while ( $news->have_posts() ): $news->the_post(); ?>
                            <div class="swiper-slide">
                                <a class="penci-topbar-post-title"
                                   href="<?php the_permalink(); ?>"><?php echo sanitize_text_field( wp_trim_words( get_the_title(), $title_length, '...' ) ); ?></a>
                            </div>
						<?php endwhile;
						wp_reset_postdata(); ?>
                    </div>
                </div>
			<?php endif; /* End check if no posts */ ?>
        </div>
		<?php
		if ( isset( $auto_speed ) && $auto_speed && '300' != $auto_speed ) {
			$auto_speed_num = (int) $auto_speed / 1000;
			echo '<style>.penci-topbar-trending.penci-enews-ticker-' . $rand . ' .animated.slideOutUp, .penci-topbar-trending.penci-enews-ticker-' . $rand . ' .animated.slideInUp, .penci-topbar-trending.penci-enews-ticker-' . $rand . ' .animated.TickerslideOutRight, .penci-topbar-trending.penci-enews-ticker-' . $rand . ' .animated.TickerslideInRight, .penci-topbar-trending.penci-enews-ticker-' . $rand . ' .animated.fadeOut, .penci-topbar-trending.penci-enews-ticker-' . $rand . ' .animated.fadeIn{ -webkit-animation-duration : ' . $auto_speed_num . 's; animation-duration : ' . $auto_speed_num . 's; }</style>';
		}
	}
}
