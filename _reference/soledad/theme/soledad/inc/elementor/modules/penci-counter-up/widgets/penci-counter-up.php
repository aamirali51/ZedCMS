<?php
namespace PenciSoledadElementor\Modules\PenciCounterUp\Widgets;

use PenciSoledadElementor\Base\Base_Widget;
use Elementor\Controls_Manager;
use Elementor\Control_Media;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;
use Elementor\Utils;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class PenciCounterUp extends Base_Widget {

	public function get_name() {
		return 'penci-counter-up';
	}

	public function get_title() {
		return penci_get_theme_name('Penci').' '.esc_html__( ' Counter Up', 'soledad' );
	}

	public function get_icon() {
		return 'eicon-countdown';
	}
	
	public function get_categories() {
		return [ 'penci-elements' ];
	}

	public function get_keywords() {
		return array( 'counter' );
	}

	/**
	 * Retrieve the list of scripts the image carousel widget depended on.
	 */
	public function get_script_depends() {
		return array( 'waypoints','jquery-counterup' );
	}

	protected function register_controls() {
		

		$this->start_controls_section(
			'section_general', array(
				'label' => esc_html__( 'Counter Up', 'soledad' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);
		$this->add_control(
			'penci_block_width', array(
				'label'   => __( 'Element Columns', 'soledad' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 1,
				'options' => array(
					'1' => esc_html__( '1 Column ( Small Container Width)', 'soledad' ),
					'2' => esc_html__( '2 Columns ( Medium Container Width )', 'soledad' ),
					'3' => esc_html__( '3 Columns ( Large Container Width )', 'soledad' ),
				)
			)
		);
		$this->add_control(
			'cup_style', array(
				'label'   => __( 'Choose Style', 'soledad' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 's1',
				'options' => array(
					's1' => esc_html__( 'Style 1', 'soledad' ),
					's2' => esc_html__( 'Style 2', 'soledad' ),
				)
			)
		);
		$this->add_control(
			'cup_align', array(
				'label'   => __( 'Posttion', 'soledad' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'center',
				'options' => array(
					'left'   => esc_html__( 'Left', 'soledad' ),
					'center' => esc_html__( 'Center', 'soledad' ),
					'right'  => esc_html__( 'Right', 'soledad' ),
				),
				'condition' => array( 'cup_style' => 's1' ),
			)
		);
		$this->add_control(
			'cup_number', array(
				'label'     => __( 'Number', 'soledad' ),
				'type'      => Controls_Manager::TEXT,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'cup_prefix_number', array(
				'label'     => __( 'Prefix of number', 'soledad' ),
				'type'      => Controls_Manager::TEXT,
				'separator' => 'before',
			)
		);
		$this->add_control(
			'cup_suffix_number', array(
				'label'     => __( 'Suffix of number', 'soledad' ),
				'type'      => Controls_Manager::TEXT,
				'separator' => 'before',
			)
		);
		$this->add_control(
			'cup_title', array(
				'label'     => __( 'Title', 'soledad' ),
				'type'      => Controls_Manager::TEXT,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'cup_icon_type', array(
				'label'   => __( 'Icon type', 'soledad' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'icon'  => __( 'Icon', 'soledad' ),
					'image' => __( 'Image', 'soledad' ),
				),
				'default' => 'icon',
			)
		);
		$this->add_control(
			'cup_icon', array(
				'label'     => __( 'Icon', 'soledad' ),
				'type'      => Controls_Manager::ICON,
				'label_block' => true,
				'default'   => 'fas fa-star',
				'condition' => array( 'cup_icon_type' => 'icon' ),
			)
		);
		$this->add_control(
			'cup_image',
			array(
				'label'     => __( 'Choose Image', 'soledad' ),
				'type'      => Controls_Manager::MEDIA,
				'default'   => array( 'url' => Utils::get_placeholder_image_src() ),
				'condition' => array( 'cup_icon_type' => 'image' ),
			)
		);

		$this->add_control(
			'icon_border_style', array(
				'label'     => __( 'Icon border style', 'soledad' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => '',
				'options'   => array(
					''       => esc_html__( 'None', 'soledad' ),
					'solid'  => esc_html__( 'Solid', 'soledad' ),
					'dashed' => esc_html__( 'Dashed', 'soledad' ),
					'dotted' => esc_html__( 'Dotted', 'soledad' ),
				),
				'selectors' => array( '{{WRAPPER}} .penci-cup_icon--icon' => 'border-style: {{VALUE}}' ),
				'condition' => array( 'cup_icon_type' => 'icon' ),
			)
		);

		$this->add_control(
			'icon_border_width', array(
				'label'     => __( 'Border width for Icon', 'soledad' ),
				'type'      => Controls_Manager::NUMBER,
				'selectors' => array( '{{WRAPPER}} .penci-cup_icon--icon' => 'border-width: {{SIZE}}px' ),
				'condition' => array( 'icon_border_style' => array( 'solid', 'dashed', 'dotted', 'double' ) ),
			)
		);
		$this->add_control(
			'icon_border_radius', array(
				'label'     => __( 'Border radius for Icon', 'soledad' ),
				'type'      => Controls_Manager::NUMBER,
				'selectors' => array( '{{WRAPPER}} .penci-cup_icon--icon' => 'border-radius: {{SIZE}}px' ),
				'condition' => array( 'icon_border_style' => array( 'solid', 'dashed', 'dotted', 'double' ) ),
			)
		);
		$this->add_control(
			'icon_padding', array(
				'label'     => __( 'Padding for Icon', 'soledad' ),
				'type'      => Controls_Manager::NUMBER,
				'selectors' => array( '{{WRAPPER}} .penci-cup_icon--icon' => 'padding: {{SIZE}}px' ),
			)
		);
		$this->add_control(
			'_image_width_height', array(
				'label'     => __( 'Image With/Height', 'soledad' ),
				'type'      => Controls_Manager::NUMBER,
				'selectors' => array( '{{WRAPPER}} .penci-cup_icon--image' => 'width: {{SIZE}}px;height: {{SIZE}}px;' ),
				'condition' => array( 'cup_icon_type' => 'image' ),
			)
		);
		$this->add_control(
			'icon_margin_bottom', array(
				'label'     => __( 'Margin Bottom for Icon or Image', 'soledad' ),
				'type'      => Controls_Manager::NUMBER,
				'selectors' => array( '{{WRAPPER}} .penci-cup_icon' => 'margin-bottom: {{SIZE}}px' ),
			)
		);
		$this->add_control(
			'title_margin_top', array(
				'label'     => __( 'Margin Top for Title', 'soledad' ),
				'type'      => Controls_Manager::NUMBER,
				'selectors' => array( '{{WRAPPER}} .penci-cup-title' => 'margin-top: {{SIZE}}px' ),
			)
		);
		$this->add_control(
			'cup_delay', array(
				'label'     => __( 'Delay', 'soledad' ),
				'type'      => Controls_Manager::NUMBER,
				'default'   => '',
			)
		);
		$this->add_control(
			'cup_time', array(
				'label'     => __( 'Time', 'soledad' ),
				'type'      => Controls_Manager::NUMBER,
				'default'   => '',
			)
		);

		
		$this->end_controls_section();
		$this->start_controls_section(
			'section_style_content',
			array(
				'label' => __( 'Content', 'soledad' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'cup_icon_color', array(
				'label'     => __( 'Icon color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .penci-counter-up .penci-cup_icon' => 'color: {{VALUE}};' ),
			)
		);
		$this->add_responsive_control(
			'cup_icon_size', array(
				'label'     => __( 'Font size for Icon', 'soledad' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array( 'px' => array( 'min' => 0, 'max' => 100, ) ),
				'selectors' => array( '{{WRAPPER}} .penci-counter-up .penci-cup_icon' => 'font-size: {{SIZE}}px' ),
			)
		);

		$this->add_control(
			'cup_number_color', array(
				'label'     => __( 'Number Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .penci-counterup-number' => 'color: {{VALUE}};' ),
			)
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),array(
				'name' => 'cup_number_typo',
				'label'     => __( 'Typography for Number', 'soledad' ),
				'selector' => '{{WRAPPER}} .penci-counterup-number',
			)
		);
		$this->add_control(
			'cup_frefix_color', array(
				'label'     => __( 'Prefix and Suffix Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .penci-cup-postfix,{{WRAPPER}} .penci-cup-prefix' => 'color: {{VALUE}};' ),
			)
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),array(
				'name' => 'cup_frefix_typo',
				'label'     => __( 'Typography for Prefix and Suffix', 'soledad' ),
				'selector' => '{{WRAPPER}} .penci-cup-postfix,{{WRAPPER}} .penci-cup-prefix',
			)
		);

		$this->add_control(
			'cup_title_color', array(
				'label'     => __( 'Title color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .penci-cup-title' => 'color: {{VALUE}};' ),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),array(
				'name' => 'cup_title_typo',
				'label'     => __( 'Typography for Title', 'soledad' ),
				'selector' => '{{WRAPPER}} .penci-cup-title',
			)
		);

		$this->end_controls_section();
		$this->start_controls_section(
			'section_title_block',
			array(
				'label' => __( 'Heading Title', 'soledad' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);
		$this->add_control(
			'show_block_heading', array(
				'label'   => __( 'Show Heading Title', 'soledad' ),
				'type'    => Controls_Manager::SWITCHER,
			)
		);
		$this->add_control(
			'heading_title_style', array(
				'label'   => __( 'Choose Style', 'soledad' ),
				'type'    => Controls_Manager::SELECT,
				'default' => '',
				'options' => array(
					''                  => esc_html__( 'Default ( follow Customize )', 'soledad' ),
					'style-1'           => esc_html__( 'Style 1', 'soledad' ),
					'style-2'           => esc_html__( 'Style 2', 'soledad' ),
					'style-3'           => esc_html__( 'Style 3', 'soledad' ),
					'style-4'           => esc_html__( 'Style 4', 'soledad' ),
					'style-5'           => esc_html__( 'Style 5', 'soledad' ),
					'style-6'           => esc_html__( 'Style 6 - Only Text', 'soledad' ),
					'style-7'           => esc_html__( 'Style 7', 'soledad' ),
					'style-9'           => esc_html__( 'Style 8', 'soledad' ),
					'style-8'           => esc_html__( 'Style 9 - Custom Background Image', 'soledad' ),
					'style-10'          => esc_html__( 'Style 10', 'soledad' ),
					'style-11'          => esc_html__( 'Style 11', 'soledad' ),
					'style-12'          => esc_html__( 'Style 12', 'soledad' ),
					'style-13'          => esc_html__( 'Style 13', 'soledad' ),
					'style-14'          => esc_html__( 'Style 14', 'soledad' ),
					'style-15'          => esc_html__( 'Style 15', 'soledad' ),
					'style-16'          => esc_html__( 'Style 16', 'soledad' ),
					'style-2 style-17'  => esc_html__( 'Style 17', 'soledad' ),
					'style-18'          => esc_html__( 'Style 18', 'soledad' ),
					'style-18 style-19' => esc_html__( 'Style 19', 'soledad' ),
					'style-18 style-20' => esc_html__( 'Style 20', 'soledad' ),
					'style-21'          => esc_html__( 'Style 21', 'soledad' ),
					'style-22'          => esc_html__( 'Style 22', 'soledad' ),
					'style-23'          => esc_html__( 'Style 23', 'soledad' ),
					'style-24'          => esc_html__( 'Style 24', 'soledad' ),
					'style-25'          => __( 'Style 25', 'soledad' ),
					'style-26'          => __( 'Style 26', 'soledad' ),
					'style-27'          => __( 'Style 27', 'soledad' ),
					'style-28'          => __( 'Style 28', 'soledad' ),
					'style-29'          => __( 'Style 29', 'soledad' ),
					'style-30'          => __( 'Style 30', 'soledad' ),
				),
				'condition' => array(
					'show_block_heading' => 'yes'
				),
			)
		);
		$this->add_control(
			'heading', array(
				'label'   => __( 'Heading Title', 'soledad' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'Heading Title', 'soledad' ),
				'condition' => array(
					'show_block_heading' => 'yes'
				),
			)
		);
		$this->add_control(
			'heading_title_link',
			array(
				'label'       => __( 'Title url', 'soledad' ),
				'type'        => Controls_Manager::URL,
				'placeholder' => __( 'https://your-link.com', 'soledad' ),
				'separator'   => 'before',
				'condition' => array(
					'show_block_heading' => 'yes'
				),
			)
		);

		$this->add_control(
			'add_title_icon', array(
				'label'     => __( 'Add icon for title?', 'soledad' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_on'  => __( 'Show', 'soledad' ),
				'label_off' => __( 'Hide', 'soledad' ),
				'default'   => '',
				'separator' => 'before',
				'condition' => array(
					'show_block_heading' => 'yes'
				),
			)
		);

		$this->add_control(
			'block_title_icon', array(
				'label'     => __( 'Icon', 'soledad' ),
				'type'      => Controls_Manager::ICON,
				'default'   => 'fas fa-star',
				'label_block' => true,
				'condition' => array(
					'add_title_icon' => 'yes',
					'show_block_heading' => 'yes'
				),
			)
		);
		$this->add_control(
			'block_title_ialign', array(
				'label'     => __( 'Icon Alignment', 'soledad' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'left',
				'options'   => array(
					'left'  => esc_html__( 'Left', 'soledad' ),
					'right' => esc_html__( 'Right', 'soledad' ),
				),
				'condition' => array(
					'add_title_icon' => 'yes',
					'show_block_heading' => 'yes'
				),
			)
		);

		$this->add_control(
			'block_title_align', array(
				'label'   => __( 'Heading Align', 'soledad' ),
				'type'    => Controls_Manager::SELECT,
				'default' => '',
				'options' => array(
					''       => esc_html__( 'Default ( follow Customize )', 'soledad' ),
					'left'   => esc_html__( 'Left', 'soledad' ),
					'center' => esc_html__( 'Center', 'soledad' ),
					'right'  => esc_html__( 'Right', 'soledad' ),
				),
				'condition' => array(
					'show_block_heading' => 'yes'
				),
			)
		);

		$this->add_control(
			'heading_icon_pos', array(
				'label'     => __( 'Align Icon on Style 15', 'soledad' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => '',
				'options'   => array(
					''              => esc_html__( 'Default ( follow Customize )', 'soledad' ),
					'pciconp-right' => esc_html__( 'Right', 'soledad' ),
					'pciconp-left'  => esc_html__( 'Left', 'soledad' ),
				),
				'condition' => array( 'heading_title_style' => array( 'style-15' ) ),
			)
		);
		$this->add_control(
			'heading_icon', array(
				'label'     => __( 'Custom Icon on Style 15', 'soledad' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => '',
				'options'   => array(
					''             => esc_html__( 'Default ( follow Customize )', 'soledad' ),
					'pcicon-right' => esc_html__( 'Arrow Right', 'soledad' ),
					'pcicon-left'  => esc_html__( 'Arrow Left', 'soledad' ),
					'pcicon-down'  => esc_html__( 'Arrow Down', 'soledad' ),
					'pcicon-up'    => esc_html__( 'Arrow Up', 'soledad' ),
					'pcicon-star'  => esc_html__( 'Star', 'soledad' ),
					'pcicon-bars'  => esc_html__( 'Bars', 'soledad' ),
					'pcicon-file'  => esc_html__( 'File', 'soledad' ),
					'pcicon-fire'  => esc_html__( 'Fire', 'soledad' ),
					'pcicon-book'  => esc_html__( 'Book', 'soledad' ),
				),
				'condition' => array( 'heading_title_style' => array( 'style-15' ) ),
			)
		);

		$this->add_control(
			'block_title_marginbt', array(
				'label'     => __( 'Margin Bottom', 'soledad' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array( 'px' => array( 'min' => 0, 'max' => 100, ) ),
				'selectors' => array( '{{WRAPPER}} .penci-homepage-title' => 'margin-bottom: {{SIZE}}px' ),
				'condition' => array(
					'show_block_heading' => 'yes'
				),
			)
		);

		$this->end_controls_section();
		$this->register_block_title_style_section_controls();

	}

	protected function render() {
		$settings = $this->get_settings();

		$css_class = 'penci-counter-up';
		$css_class .= ' penci-style-' . $settings['cup_style'];
		$css_class .= ' penci-counterup-' . $settings['cup_align'];
		?>
		<div class="<?php echo esc_attr( $css_class ); ?>">
			<?php
			if( isset( $settings['show_block_heading'] ) && $settings['show_block_heading'] ) {
				$this->markup_block_title( $settings, $this );
			}
			?>
			<div class="penci-counter-up_inner">
				<?php
				if ( 'icon' == $settings['cup_icon_type'] ) {

					if( ! empty( $settings['cup_icon'] ) ) {
						$this->add_render_attribute( 'i', 'class', 'penci-cup_iconn--i ' . $settings['cup_icon'] );
						$this->add_render_attribute( 'i', 'aria-hidden', 'true' );

						echo '<div class="penci-cup_icon penci-cup_icon--icon">';
						echo '<i ' . $this->get_render_attribute_string( 'i' ) . '></i>';
						echo '</div>';
					}
				} elseif ( ! empty( $settings['cup_image']['url'] ) ) {
					$this->add_render_attribute( 'image', 'src', $settings['cup_image']['url'] );
					$this->add_render_attribute( 'image', 'alt', Control_Media::get_image_alt( $settings['cup_image'] ) );
					$this->add_render_attribute( 'image', 'title', Control_Media::get_image_title( $settings['cup_image'] ) );

					echo '<div class="penci-cup_icon penci-cup_icon--image">';
					echo  Group_Control_Image_Size::get_attachment_image_html( $settings, 'thumbnail', 'cup_image' );
					echo  '</div>';
				}
				$data_delay  = $settings['cup_delay'] ? $settings['cup_delay'] : 0;
				$data_time   = $settings['cup_time'] ? $settings['cup_time'] : 2000;
				$data_number = $settings['cup_number'] ? $settings['cup_number'] : 0;
				?>
				<div class="penci-cup-info">
					<div class="penci-cup-number-wrapper">
				<span class="penci-span-inner">
				<?php if ( $settings['cup_prefix_number'] ): ?><span class="penci-cup-label penci-cup-prefix"><?php echo do_shortcode( $settings['cup_prefix_number'] ); ?></span><?php endif; ?>
					<span class="penci-counterup-number" data-delay="<?php echo $data_delay; ?>" data-time="<?php echo $data_time; ?>" data-count="<?php echo $data_number; ?>">0</span>
					<?php if ( $settings['cup_suffix_number'] ): ?><span class="penci-cup-label penci-cup-postfix"><?php echo do_shortcode( $settings['cup_suffix_number'] ); ?></span><?php endif; ?>
				</span>
					</div>
					<?php if ( $settings['cup_title'] ): ?>
						<div class="penci-cup-title"><?php echo $settings['cup_title']; ?></div><?php endif; ?>
				</div>
			</div>
		</div>
		<?php
	}
}
