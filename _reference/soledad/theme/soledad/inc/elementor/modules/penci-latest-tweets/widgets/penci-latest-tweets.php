<?php

namespace PenciSoledadElementor\Modules\PenciLatestTweets\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use PenciSoledadElementor\Base\Base_Widget;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class PenciLatestTweets extends Base_Widget {

	public function get_name() {
		return 'penci-latest-tweets';
	}

	public function get_title() {
		return penci_get_theme_name( 'Penci' ) . ' ' . esc_html__( ' Latest Tweets', 'soledad' );
	}

	public function get_icon() {
		return 'eicon-gallery-grid';
	}

	public function get_categories() {
		return [ 'penci-elements' ];
	}

	public function get_keywords() {
		return array( 'tweets', 'social' );
	}

	protected function register_controls() {


		// Section layout
		$this->start_controls_section( 'section_page', array(
			'label' => esc_html__( 'Latest Tweets', 'soledad' ),
			'tab'   => Controls_Manager::TAB_CONTENT,
		) );
		$this->add_control( 'note_important', array(
			'type'            => Controls_Manager::RAW_HTML,
			'raw'             => sprintf( __( 'Note Important: To use this widget you need to connect your Twitter account <a target="_blank" href="%s">here</a>', 'soledad' ), admin_url( 'admin.php?page=penci_twitter_token' ) ),
			'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',

		) );
		$this->add_control( 'tweets_style', array(
			'label'   => __( 'Layout', 'soledad' ),
			'type'    => Controls_Manager::SELECT,
			'default' => 'slider',
			'options' => array(
				'slider' => esc_html__( 'Slider', 'soledad' ),
				'list'   => esc_html__( 'List', 'soledad' ),
			)
		) );
		$this->add_control( 'tweets_align', array(
			'label'     => __( 'Align This Widget', 'soledad' ),
			'type'      => Controls_Manager::SELECT,
			'default'   => 'pc_aligncenter',
			'condition' => [ 'tweets_style' => 'slider' ],
			'options'   => array(
				'pc_aligncenter' => esc_html__( 'Align Center', 'soledad' ),
				'pc_alignleft'   => esc_html__( 'Align Left', 'soledad' ),
				'pc_alignright'  => esc_html__( 'Align Right', 'soledad' ),
			)
		) );

		$this->add_control( 'tweets_hide_date', array(
			'label'     => __( 'Hide tweets date?', 'soledad' ),
			'type'      => Controls_Manager::SWITCHER,
			'label_on'  => __( 'Yes', 'soledad' ),
			'label_off' => __( 'No', 'soledad' ),
			'default'   => '',
		) );
		$this->add_control( 'tweets_dis_auto', array(
			'label'     => __( 'Disable Auto Play Tweets Slider?', 'soledad' ),
			'type'      => Controls_Manager::SWITCHER,
			'label_on'  => __( 'Yes', 'soledad' ),
			'label_off' => __( 'No', 'soledad' ),
			'condition' => [ 'tweets_style' => 'slider' ],
			'default'   => '',
		) );
		$this->add_control( 'tweets_reply', array(
			'label'       => __( 'Custom Reply text', 'soledad' ),
			'default'     => esc_html__( 'Reply', 'soledad' ),
			'condition'   => [ 'tweets_style' => 'slider' ],
			'label_block' => true,
		) );
		$this->add_control( 'tweets_retweet', array(
			'label'       => __( 'Custom Retweet text', 'soledad' ),
			'default'     => esc_html__( 'Retweet', 'soledad' ),
			'condition'   => [ 'tweets_style' => 'slider' ],
			'label_block' => true,
		) );
		$this->add_control( 'tweets_favorite', array(
			'label'       => __( 'Custom Favorite text', 'soledad' ),
			'default'     => esc_html__( 'Favorite', 'soledad' ),
			'condition'   => [ 'tweets_style' => 'slider' ],
			'label_block' => true,
		) );

		$this->end_controls_section();
		$this->register_block_title_section_controls();

		$this->start_controls_section( 'section_latest_tweets_spacing', array(
			'label' => __( 'Elements Spacing', 'soledad' ),
			'tab'   => Controls_Manager::TAB_CONTENT,
		) );
		$this->add_control( 'tweets_icon_spacing', array(
			'label'     => __( 'Tweet Icon Spacing', 'soledad' ),
			'type'      => Controls_Manager::SLIDER,
			'condition' => [ 'tweets_style' => 'slider' ],
			'range'     => array( 'px' => array( 'min' => 0, 'max' => 500, ) ),
			'selectors' => array( '{{WRAPPER}} .penci-tweets-widget-content .icon-tweets' => 'margin-bottom: {{SIZE}}px;' )
		) );
		$this->add_control( 'tweets_date_spacing', array(
			'label'     => __( 'Tweet Date Spacing', 'soledad' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => array( 'px' => array( 'min' => 0, 'max' => 500, ) ),
			'selectors' => array( '{{WRAPPER}} .penci-tweets-widget-content .tweet-date' => 'margin-bottom: {{SIZE}}px;' )
		) );
		$this->add_control( 'tweets_title_spacing', array(
			'label'     => __( 'Tweet Title Spacing', 'soledad' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => array( 'px' => array( 'min' => 0, 'max' => 500, ) ),
			'selectors' => array( '{{WRAPPER}} .penci-tweets-widget-content .tweet-text' => 'margin-bottom: {{SIZE}}px;' )
		) );
		$this->add_control( 'tweets_iconp_spacing', array(
			'label'     => __( 'Reply / Retweet/ Favorite Links & Icons', 'soledad' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => array( 'px' => array( 'min' => 0, 'max' => 500, ) ),
			'selectors' => array( '{{WRAPPER}} .penci-tweets-widget-content .tweet-intents' => 'margin-top: {{SIZE}}px!important;' )
		) );
		$this->add_control( 'tweets_nav_spacing', array(
			'label'     => __( 'Tweet Dots Spacing', 'soledad' ),
			'condition' => [ 'tweets_style' => 'slider' ],
			'type'      => Controls_Manager::SLIDER,
			'range'     => array( 'px' => array( 'min' => 0, 'max' => 500, ) ),
			'selectors' => array( '{{WRAPPER}} .penci-owl-carousel.penci-tweets-slider .penci-owl-dots' => 'margin-top: {{SIZE}}px!important;' )
		) );
		$this->add_control( 'tweets_items_spacing', array(
			'label'     => __( 'Spacing Between Tweets Items', 'soledad' ),
			'condition' => [ 'tweets_style!' => 'slider' ],
			'type'      => Controls_Manager::SLIDER,
			'range'     => array( 'px' => array( 'min' => 0, 'max' => 500, ) ),
			'selectors' => array( '{{WRAPPER}} .penci-tweets-lists .penci-tweet:not(:last-child)' => 'margin-bottom: calc({{SIZE}}px / 2);padding-bottom:calc({{SIZE}}px / 2)' )
		) );
		$this->end_controls_section();

		$this->start_controls_section( 'section_latest_tweets_style', array(
			'label' => __( 'Latest Tweets', 'soledad' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		) );
		$this->add_control( 'tweets_bd_color', array(
			'label'     => __( 'General Border Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => array( '{{WRAPPER}} .penci-tweets-lists .penci-tweet:not(:last-child)' => 'border-color: {{VALUE}};' )
		) );
		$this->add_control( 'tweets_text_headings', array(
			'label' => __( 'Text', 'soledad' ),
			'type'  => Controls_Manager::HEADING,
		) );
		$this->add_control( 'tweets_text_color', array(
			'label'     => __( 'Text color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => array( '{{WRAPPER}} .tweet-text' => 'color: {{VALUE}};' )
		) );
		$this->add_control( 'tweets_text_lcolor', array(
			'label'     => __( 'Text Links color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => array( '{{WRAPPER}} .tweet-text a' => 'color: {{VALUE}};' )
		) );
		$this->add_control( 'tweets_text_lhcolor', array(
			'label'     => __( 'Text Links Hover color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => array( '{{WRAPPER}} .tweet-text a:hover' => 'color: {{VALUE}};' )
		) );
		$this->add_group_control( Group_Control_Typography::get_type(), array(
			'name'     => 'tweets_text_typo',
			'label'    => __( 'Typography', 'soledad' ),
			'selector' => '{{WRAPPER}} .tweet-text',
		) );
		$this->add_control( 'tweets_date_headings', array(
			'label'     => __( 'Date', 'soledad' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
		) );
		$this->add_control( 'tweets_date_color', array(
			'label'     => __( 'Date color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => array( '{{WRAPPER}} .tweet-date' => 'color: {{VALUE}};' )
		) );
		$this->add_group_control( Group_Control_Typography::get_type(), array(
			'name'     => 'tweets_date_typo',
			'label'    => __( 'Typography', 'soledad' ),
			'selector' => '{{WRAPPER}} .tweet-date',
		) );
		$this->add_control( 'tweets_link_headings', array(
			'label'     => __( 'Reply / Retweet/ Favorite Links & Icons', 'soledad' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
		) );
		$this->add_control( 'tweets_link_color', array(
			'label'     => __( 'Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => array(
				'body:not(.pcdm-enable) {{WRAPPER}} .penci-tweets-widget-content .tweet-intents-inner:after'                                                                                                                                                         => 'background-color: {{VALUE}};',
				'body:not(.pcdm-enable) {{WRAPPER}} .penci-tweets-widget-content .tweet-intents-inner:before'                                                                                                                                                        => 'background-color: {{VALUE}};',
				'body:not(.pcdm-enable) {{WRAPPER}} .penci-tweets-widget-content .icon-tweets, {{WRAPPER}} .penci-tweets-widget-content .tweet-intents span:after, {{WRAPPER}} .penci-tweets-widget-content .tweet-intents a' => 'color: {{VALUE}};',
			)
		) );
		$this->add_responsive_control( 'tweets_link_size', array(
			'label'     => __( 'Font size', 'soledad' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => array( 'px' => array( 'min' => 0, 'max' => 100, ) ),
			'selectors' => array( '{{WRAPPER}} .penci-tweets-widget-content .tweet-intents a' => 'font-size: {{SIZE}}px' ),
		) );
		$this->add_control( 'tweets_dots_headings', array(
			'label'     => __( 'Dots', 'soledad' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
			'condition' => [ 'tweets_style' => 'slider' ],
		) );
		$this->add_control( 'tweets_dot_color', array(
			'condition' => [ 'tweets_style' => 'slider' ],
			'label'     => __( 'Background Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => array( 'body:not(.pcdm-enable) {{WRAPPER}} .penci-owl-carousel.penci-tweets-slider .penci-owl-dots .penci-owl-dot span' => 'border-color: {{VALUE}};background-color:{{VALUE}};' ),
		) );
		$this->add_control( 'tweets_dot_hcolor', array(
			'condition' => [ 'tweets_style' => 'slider' ],
			'label'     => __( 'Border and Background Active Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => array(
				'body:not(.pcdm-enable) {{WRAPPER}} .penci-owl-carousel.penci-tweets-slider .penci-owl-dots .penci-owl-dot:hover span,' . 'body:not(.pcdm-enable) {{WRAPPER}} .penci-owl-carousel.penci-tweets-slider .penci-owl-dots .penci-owl-dot.active span' => 'border-color: {{VALUE}};background-color:{{VALUE}};'
			),
		) );

		$this->end_controls_section();

		$this->register_block_title_style_section_controls();

	}

	protected function render() {
		$settings = $this->get_settings();
		if ( function_exists( 'penci_getTweets' ) ) {
			$tweets = penci_getTweets( 5 );
			if ( empty( $tweets ) ) {
				return;
			}

			$reply    = $settings['tweets_reply'];
			$retweet  = $settings['tweets_retweet'];
			$favorite = $settings['tweets_favorite'];

			$css_class = 'penci-latest-tweets-widget';
			$style     = isset( $settings['tweets_style'] ) && $settings['tweets_style'] ? $settings['tweets_style'] : 'slider';
			$classes   = 'slider' == $style ? 'penci-owl-carousel penci-owl-carousel-slider penci-tweets-slider swiper' : 'penci-tweets-lists';
			?>
            <div class="<?php echo esc_attr( $css_class ); ?>">
				<?php $this->markup_block_title( $settings, $this ); ?>
                <div class="penci-block_content">
					<?php
					if ( isset( $tweets['error'] ) ) {
						echo 'Missing Twitter API Keys - please connect your Twitter Account by go to admin > Soledad > Connect Twitter';
					} else {
						$rtl_align = is_rtl() ? 'pc_alignright' : 'pc_alignleft';
						$align     = $style == 'slider' ? $settings['tweets_align'] : $rtl_align;
						?>
                        <div class="penci-tweets-widget-content <?php echo esc_attr( $align ); ?>">
							<?php if ( $style == 'slider' ): ?>
                                <span class="icon-tweets"><?php penci_fawesome_icon( 'penciicon-x-twitter' ); ?></span>
							<?php endif; ?>
                            <div class="<?php echo esc_attr( $classes ); ?>" data-dots="true"
                                 data-nav="false" data-seffect="slide" data-auto="<?php if ( $settings['tweets_dis_auto'] ) {
								echo 'false';
							} else {
								echo 'true';
							} ?>">
								<?php
								$item_class = 'normal-item';
								if ( 'slider' == $style ) {
									echo '<div class="swiper-wrapper">';
									$item_class = 'swiper-slide';
								}
								foreach ( $tweets as $tweet ):
									$date_array = explode( ' ', $tweet['created_at'] );
									$tweet_id = $tweet['id_str'];
									$tweet_text = $tweet['text'];
									$urls = $tweet['entities']['urls'];

									if ( isset( $urls ) ) {
										foreach ( $urls as $ul ) {
											$url = $ul['url'];
											if ( isset( $url ) ):
												$tweet_text = str_replace( $url, '<a href="' . $url . '" target="_blank">' . $url . '</a>', $tweet_text );
											endif;
										}
									}
									?>
                                    <div class="penci-tweet <?php echo $item_class;?>">

										<?php if ( $style == 'list' ):
											$reply = '<i class="fa fa-reply" aria-hidden="true"></i>';
											$retweet = '<i class="fa fa-retweet" aria-hidden="true"></i>';
											$favorite = '<i class="fa fa-thumbs-up" aria-hidden="true"></i>';
											?>

                                            <div class="tweet-list-top">

												<?php if ( $date_array[1] && $date_array[2] && $date_array[5] && ! $settings['tweets_hide_date'] ): ?>
                                                    <span class="tweet-date"><?php echo $date_array[2] . '-' . $date_array[1] . '-' . $date_array[5]; ?></span>
												<?php endif; ?>

                                            </div>

										<?php endif; ?>

                                        <div class="tweet-text">
											<?php echo $tweet_text; ?>
                                        </div>
										<?php
										if ( $style == 'slider' && $date_array[1] && $date_array[2] && $date_array[5] && ! $settings['tweets_hide_date'] ): ?>
                                            <p class="tweet-date"><?php echo $date_array[2] . '-' . $date_array[1] . '-' . $date_array[5]; ?></p>
										<?php endif; ?>
                                        <div class="tweet-intents">
                                            <div class="tweet-intents-inner">
                                            <span><a target="_blank" class="reply"
                                                     href="https://x.com/intent/tweet?in_reply_to=<?php echo sanitize_text_field( $tweet_id ); ?>"><?php echo do_shortcode( $reply ); ?></a></span>
                                                <span><a target="_blank" class="retweet"
                                                         href="https://x.com/intent/retweet?tweet_id=<?php echo sanitize_text_field( $tweet_id ); ?>"><?php echo do_shortcode( $retweet ); ?></a></span>
                                                <span><a target="_blank" class="favorite"
                                                         href="https://x.com/intent/favorite?tweet_id=<?php echo sanitize_text_field( $tweet_id ); ?>"><?php echo do_shortcode( $favorite ); ?></a></span>
                                            </div>
                                        </div>
                                    </div>
								<?php endforeach; 
								if ( 'slider' == $style ) {
									echo '</div>';
								}
								?>

                            </div>
                        </div>

						<?php
					}
					?>
                </div>
            </div>
			<?php
		} else {
			_e( 'Please install the "Penci Social Feed" plugin via Apperance > Install Plugins to get this widget working.', 'soledad' );
		}
	}
}
