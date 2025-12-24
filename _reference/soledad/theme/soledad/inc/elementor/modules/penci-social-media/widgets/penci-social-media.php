<?php
namespace PenciSoledadElementor\Modules\PenciSocialMedia\Widgets;

use PenciSoledadElementor\Base\Base_Widget;
use Elementor\Group_Control_Typography;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class PenciSocialMedia extends Base_Widget {

	public function get_name() {
		return 'penci-social-media';
	}

	public function get_title() {
		return penci_get_theme_name( 'Penci' ) . ' ' . esc_html__( ' Widget Social Media', 'soledad' );
	}

	public function get_icon() {
		return 'eicon-share';
	}

	public function get_categories() {
		return array( 'penci-elements' );
	}

	public function get_keywords() {
		return array( 'social media' );
	}

	protected function register_controls() {

		$this->start_controls_section(
			'section_general',
			array(
				'label' => esc_html__( 'General', 'soledad' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);
		$this->add_control(
			'social_desc',
			array(
				'type'            => Controls_Manager::RAW_HTML,
				'raw'             => 'Note: You can setup the URL for each social media icon via <strongAppearance > Customize > Social Media</strong>',
				'content_classes' => 'elementor-descriptor',
			)
		);
		$this->add_control(
			'text_right',
			array(
				'label'     => __( 'Display Social Text on Right Icons', 'soledad' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_on'  => __( 'Yes', 'soledad' ),
				'label_off' => __( 'No', 'soledad' ),
			)
		);
		$this->add_control(
			'alignment',
			array(
				'label'   => __( 'Alignment', 'soledad' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'pc_alignleft',
				'options' => array(
					'pc_aligncenter' => esc_html__( 'Center', 'soledad' ),
					'pc_alignleft'   => esc_html__( 'Left', 'soledad' ),
					'pc_alignright'  => esc_html__( 'Right', 'soledad' ),
				),
			)
		);
		$this->add_control(
			'dis_circle',
			array(
				'label'     => __( 'Remove Border Around Icons ?', 'soledad' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_on'  => __( 'Yes', 'soledad' ),
				'label_off' => __( 'No', 'soledad' ),
			)
		);
		$this->add_control(
			'dis_border_radius',
			array(
				'label'     => __( 'Remove Border Radius on Border of Icons ?', 'soledad' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_on'  => __( 'Yes', 'soledad' ),
				'label_off' => __( 'No', 'soledad' ),
			)
		);
		$this->add_control(
			'brand_color',
			array(
				'label'     => __( 'Use Brand Colors for Social Icons ?', 'soledad' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_on'  => __( 'Yes', 'soledad' ),
				'label_off' => __( 'No', 'soledad' ),
			)
		);
		$this->add_responsive_control(
			'size_icon',
			array(
				'label'     => __( 'Custom Font Size for Icons', 'soledad' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .widget-social i'   => 'font-size: {{SIZE}}px;',
					'{{WRAPPER}} .widget-social svg' => 'width: {{SIZE}}px; height: auto;',
				),
			)
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'size_text',
				'label'    => __( 'Typography for Text', 'soledad' ),
				'selector' => '{{WRAPPER}} .widget-social span',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_show_socials',
			array(
				'label' => __( 'Socials', 'soledad' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);
		$show_socials = $this->get_show_socials();
		$socials      = get_option( 'penci_custom_socials', array() );

		if ( ! empty( $socials ) ) {
			foreach ( $socials as $slug => $attr ) {
				$show_socials[ 'Show ' . $attr['name'] ] = $slug;
			}
		}

		foreach ( $show_socials as $social_label => $social_key ) {
			$default = '';
			if ( in_array( $social_key, array( 'facebook', 'twitter', 'instagram' ) ) ) {
				$default = 'yes';
			}

			$this->add_control(
				$social_key,
				array(
					'label'     => $social_label,
					'type'      => Controls_Manager::SWITCHER,
					'label_on'  => __( 'Yes', 'soledad' ),
					'label_off' => __( 'No', 'soledad' ),
					'default'   => $default,
				)
			);
		}
		$this->end_controls_section();

		$this->register_block_title_section_controls();
		$this->start_controls_section(
			'section_style_content',
			array(
				'label' => __( 'Content', 'soledad' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'social_text_color',
			array(
				'label'     => __( 'Icons Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .widget-social a i' => 'color: {{VALUE}};',
					'{{WRAPPER}} .widget-social a svg path' => 'fill: {{VALUE}};',
				),
			)
		);
		$this->add_control(
			'social_text_hcolor',
			array(
				'label'     => __( 'Icons Hover Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .widget-social a:hover i' => 'color: {{VALUE}};',
					'{{WRAPPER}} .widget-social a:hover svg path' => 'fill: {{VALUE}};',
				),
			)
		);
		$this->add_control(
			'social_bodcolor',
			array(
				'label'     => __( 'Icons Border Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array( '{{WRAPPER}} .widget-social a i' => 'border-color: {{VALUE}};' ),
			)
		);
		$this->add_control(
			'social_hbodcolor',
			array(
				'label'     => __( 'Icons Border Hover Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array( '{{WRAPPER}} .widget-social a:hover i' => 'border-color: {{VALUE}};' ),
			)
		);
		$this->add_control(
			'social_bgcolor',
			array(
				'label'     => __( 'Icons Background Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array( '{{WRAPPER}} .widget-social a i' => 'background-color: {{VALUE}};' ),
			)
		);
		$this->add_control(
			'social_bghcolor',
			array(
				'label'     => __( 'Icons Hover Background Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array( '{{WRAPPER}} .widget-social a:hover i' => 'background-color: {{VALUE}};' ),
			)
		);
		$this->add_control(
			'social_textcolor',
			array(
				'label'     => __( 'Text Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array( '{{WRAPPER}} .widget-social.show-text a span' => 'color: {{VALUE}};' ),
			)
		);
		$this->add_control(
			'social_htextcolor',
			array(
				'label'     => __( 'Text Hover Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array( '{{WRAPPER}} .widget-social.show-text a:hover span' => 'color: {{VALUE}};' ),
			)
		);

		$this->end_controls_section();
		$this->register_block_title_style_section_controls();
	}

	protected function render() {
		$settings = $this->get_settings();

		$css_class = 'penci-block-vc penci-social-media';

		$class_socials  = ' widget-social';
		$class_socials .= $settings['alignment'] ? ' ' . $settings['alignment'] : '';
		$class_socials .= $settings['text_right'] ? ' show-text' : '';
		$class_socials .= $settings['dis_circle'] ? ' remove-circle' : '';
		$class_socials .= $settings['dis_border_radius'] ? ' remove-border-radius' : '';

		if ( $settings['brand_color'] && ! $settings['dis_circle'] ) {
			$class_socials .= ' penci-social-colored';
		} elseif ( $settings['brand_color'] && $settings['dis_circle'] ) {
			$class_socials .= ' penci-social-textcolored';
		}
		?>
		<div class="<?php echo esc_attr( $css_class ); ?>">
			<?php $this->markup_block_title( $settings, $this ); ?>
			<div class="penci-block_content<?php echo esc_attr( $class_socials ); ?>">
				<?php
				$social_data = penci_social_media_array();
				foreach ( $social_data as $name => $sdata ) {
					if ( isset( $settings[ $name ] ) && $settings[ $name ] ) {
						$icon_html = penci_icon_by_ver( $sdata[1] );
						?>
						<a href="<?php echo esc_url( do_shortcode( $sdata[0] ) ); ?>" aria-label="<?php echo ucwords( $name ); ?>" <?php echo penci_reltag_social_icons(); ?> target="_blank"><?php echo $icon_html; ?><span><?php echo ucwords( $name ); ?></span></a>
						<?php
					}
				}
				?>
			</div>
		</div>
		<?php
	}

	public function get_show_socials() {
		return array(
			'Show facebook'       => 'facebook',
			'Show twitter'        => 'twitter',
			'Show instagram'      => 'instagram',
			'Show pinterest'      => 'pinterest',
			'Show linkedin'       => 'linkedin',
			'Show behance'        => 'behance',
			'Show flickr'         => 'flickr',
			'Show tumblr'         => 'tumblr',
			'Show youtube'        => 'youtube',
			'Show email'          => 'email',
			'Show vk'             => 'vk',
			'Show bloglovin'      => 'bloglovin',
			'Show vine'           => 'vine',
			'Show soundcloud'     => 'soundcloud',
			'Show snapchat'       => 'snapchat',
			'Show spotify'        => 'spotify',
			'Show github'         => 'github',
			'Show Stack-overflow' => 'stack-overflow',
			'Show twitch'         => 'twitch',
			'Show vimeo'          => 'vimeo',
			'Show steam'          => 'steam',
			'Show xing'           => 'xing',
			'Show whatsapp'       => 'whatsapp',
			'Show telegram'       => 'telegram',
			'Show reddit'         => 'reddit',
			'Show ok'             => 'ok',
			'Show 500px'          => '500px',
			'Show stumbleupon'    => 'stumbleupon',
			'Show wechat'         => 'wechat',
			'Show weibo'          => 'weibo',
			'Show line'           => 'line',
			'Show viber'          => 'viber',
			'Show discord'        => 'discord',
			'Show rss'            => 'rss',
			'Show slack'          => 'slack',
			'Show mixcloud'       => 'mixcloud',
			'Show goodreads'      => 'goodreads',
			'Show tripadvisor'    => 'tripadvisor',
			'Show tiktok'         => 'tiktok',
			'Show dailymotion'    => 'dailymotion',
			'Show blogger'        => 'blogger',
			'Show delicious'      => 'delicious',
			'Show deviantart'     => 'deviantart',
			'Show digg'           => 'digg',
			'Show evernote'       => 'evernote',
			'Show forrst'         => 'forrst',
			'Show grooveshark'    => 'grooveshark',
			'Show lastfm'         => 'lastfm',
			'Show myspace'        => 'myspace',
			'Show paypal'         => 'paypal',
			'Show skype'          => 'skype',
			'Show window'         => 'window',
			'Show wordPress'      => 'wordPress',
			'Show yahoo'          => 'yahoo',
			'Show yandex'         => 'yandex',
			'Show douban'         => 'douban',
			'Show Threads'        => 'threads',
			'Show Yelp'        	  => 'yelp',
			'Show QQ'             => 'qq',
		);
	}
}
