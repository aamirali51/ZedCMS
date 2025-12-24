<?php

namespace PenciSoledadElementor\Modules\PenciCountDown\Widgets;

use PenciSoledadElementor\Base\Base_Widget;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class PenciCountDown extends Base_Widget {

	public function get_name() {
		return 'penci-count-down';
	}

	public function get_title() {
		return penci_get_theme_name('Penci').' '.esc_html__( ' Countdown', 'soledad' );
	}

	public function get_icon() {
		return 'eicon-countdown';
	}
	
	public function get_categories() {
		return [ 'penci-elements' ];
	}

	public function get_keywords() {
		return array( 'count', 'count_down' );
	}

	public function get_script_depends() {
		return array( 'jquery.plugin', 'countdown' );
	}

	protected function register_controls() {


		$this->start_controls_section(
			'section_general', array(
				'label' => esc_html__( 'Count Down', 'soledad' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);
		$this->add_control(
			'count_down_style', array(
				'label'   => __( 'Choose Style', 'soledad' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 's1',
				'options' => array(
					's1' => esc_html__( 'Style 1', 'soledad' ),
					's5' => esc_html__( 'Style 2', 'soledad' ),
					's3' => esc_html__( 'Style 3', 'soledad' ),
					's4' => esc_html__( 'Style 4', 'soledad' ),
				)
			)
		);
		$this->add_control(
			'count_down_posttion', array(
				'label'   => __( 'Posttion', 'soledad' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'center',
				'options' => array(
					'left'   => esc_html__( 'Left', 'soledad' ),
					'center' => esc_html__( 'Center', 'soledad' ),
					'right'  => esc_html__( 'Right', 'soledad' ),
				)
			)
		);

		$this->add_control(
			'count_year', array(
				'label'   => __( 'Year', 'soledad' ),
				'type'    => Controls_Manager::TEXT,
				'separator'   => 'before',
			)
		);
		$this->add_control(
			'count_month', array(
				'label'   => __( 'Month', 'soledad' ),
				'type'    => Controls_Manager::TEXT,
			)
		);

		$this->add_control(
			'count_day', array(
				'label'   => __( 'Day', 'soledad' ),
				'type'    => Controls_Manager::TEXT,
			)
		);
		$this->add_control(
			'count_hour', array(
				'label'   => __( 'Hour', 'soledad' ),
				'type'    => Controls_Manager::TEXT,
			)
		);
		$this->add_control(
			'count_minus', array(
				'label'   => __( 'Minus', 'soledad' ),
				'type'    => Controls_Manager::TEXT,
			)
		);
		$this->add_control(
			'count_sec', array(
				'label'   => __( 'Sec', 'soledad' ),
				'type'    => Controls_Manager::TEXT,
				'separator'   => 'after',
			)
		);

		$this->add_control(
			'countdown_opts',
			array(
				'label' => __( 'Select time units to display in countdown timer', 'soledad' ),
				'type'  => Controls_Manager::HEADING,
			)
		);

		$countdown_opts = array(
			esc_html__( "Years", "penci-framework" )   => "Y",
			esc_html__( "Months", "penci-framework" )  => "O",
			esc_html__( "Weeks", "penci-framework" )   => "W",
			esc_html__( "Days", "penci-framework" )    => "D",
			esc_html__( "Hours", "penci-framework" )   => "H",
			esc_html__( "Minutes", "penci-framework" ) => "M",
			esc_html__( "Seconds", "penci-framework" ) => "S",
		);

		foreach ( $countdown_opts as $countdown_opt_lab => $countdown_opt ) {

			$default = '';
			if( in_array( $countdown_opt, array( 'D','H','M','S' ) ) ){
				$default = 'yes';
			}

			$this->add_control(
				'countdown_'. $countdown_opt , array(
					'label'     => $countdown_opt_lab,
					'type'      => Controls_Manager::SWITCHER,
					'label_on'  => __( 'Show', 'soledad' ),
					'label_off' => __( 'Hide', 'soledad' ),
					'default'   => $default,
				)
			);
		}

		$this->add_control(
			'digit_border', array(
				'label'   => __( 'Timer digit border style', 'soledad' ),
				'type'    => Controls_Manager::SELECT,
				'default' => '',
				'options' => array(
					''       => esc_html__( 'None', 'soledad' ),
					'solid'  => esc_html__( 'Solid', 'soledad' ),
					'dashed' => esc_html__( 'Dashed', 'soledad' ),
					'dotted' => esc_html__( 'Dotted', 'soledad' ),
				),
				'separator'   => 'before',
				'condition' => array( 'count_down_style' => array( 's1','s2' ) ),
				'selectors' => array(
					'{{WRAPPER}} .penci-countdown-s1 .penci-countdown-amount' => 'border-style: {{VALUE}};',
					'{{WRAPPER}} .penci-countdown-s2 .penci-countdown-amount' => 'border-style: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'digit_border_width', array(
				'label'     => __( 'Timer digit border width', 'soledad' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array( 'px' => array( 'min' => 0, 'max' => 100, ) ),
				'selectors' => array(
					'{{WRAPPER}} .penci-countdown-s1 .penci-countdown-amount' => 'border-width: {{SIZE}}px',
					'{{WRAPPER}} .penci-countdown-s2 .penci-countdown-amount' => 'border-width: {{SIZE}}px',
				),

				'condition' => array( 'digit_border' => array( 'solid', 'dashed', 'dotted', 'double' ),'count_down_style' => array( 's1','s2') ),
			)
		);
		$this->add_control(
			'digit_border_radius', array(
				'label'     => __( 'Timer digit border radius', 'soledad' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array( 'px' => array( 'min' => 0, 'max' => 500, ) ),
				'selectors' => array(
                      '{{WRAPPER}} .penci-countdown-s1 .penci-countdown-amount' => 'border-radius: {{SIZE}}px',
                      '{{WRAPPER}} .penci-countdown-s2 .penci-countdown-amount' => 'border-radius: {{SIZE}}px',
					),
				'condition' => array( 'digit_border' => array( 'solid', 'dashed', 'dotted', 'double' ),'count_down_style' => array( 's1','s2') ),
			)
		);
		$this->add_control(
			'digit_padding', array(
				'label'     => __( 'Timer digit padding', 'soledad' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array( 'px' => array( 'min' => 0, 'max' => 200, ) ),
				'selectors' => array( '{{WRAPPER}} .penci-countdown-amount' => 'padding: {{SIZE}}px' ),
			)
		);
		$this->add_control(
			'digit_margin_top', array(
				'label'     => __( 'Timer digit margin top', 'soledad' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array( 'px' => array( 'min' => -200, 'max' => 200, ) ),
				'selectors' => array( '{{WRAPPER}} .penci-countdown-amount' => 'margin-top: {{SIZE}}px' ),
			)
		);
		$this->add_control(
			'unit_margin_top', array(
				'label'     => __( 'Timer unit margin top', 'soledad' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array( 'px' => array( 'min' => 0, 'max' => 100, ) ),
				'selectors' => array( '{{WRAPPER}} .penci-countdown-period' => 'margin-top: {{SIZE}}px' ),
			)
		);
		$this->add_responsive_control(
			'countdown_item_width', array(
				'label'     => __( 'Width of Countdown Section', 'soledad' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array( 'px' => array( 'min' => 0, 'max' => 500, ) ),
				'condition' => array( 'count_down_style' => array( 's3','s4','s5' ) ),
				'selectors' => array( '{{WRAPPER}} .penci-countdown-section' => 'width: {{SIZE}}px;' ),
			)
		);
		$this->add_responsive_control(
			'countdown_item_height', array(
				'label'     => __( 'Height of Countdown Section', 'soledad' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array( 'px' => array( 'min' => 0, 'max' => 500, ) ),
				'condition' => array( 'count_down_style' => array( 's3','s4','s5' ) ),
				'selectors' => array( '{{WRAPPER}} .penci-countdown-section' => 'height: {{SIZE}}px;' ),
			)
		);
		$this->end_controls_section();

		$this->start_controls_section(
			'section_translation', array(
				'label' => esc_html__( 'Strings Translation', 'soledad' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'str_days', array(
				'label'   => __( 'Day (Singular)', 'soledad' ),
				'type'    => Controls_Manager::TEXT,
				'default'   => esc_html__( 'Day', 'soledad' ),
			)
		);
		$this->add_control(
			'str_days2', array(
				'label'   => __( 'Days (Plural)', 'soledad' ),
				'type'    => Controls_Manager::TEXT,
				'default'   => esc_html__( 'Days', 'soledad' ),
			)
		);
		$this->add_control(
			'str_weeks', array(
				'label'   => __( 'Week (Singular)', 'soledad' ),
				'type'    => Controls_Manager::TEXT,
				'default'   => esc_html__( 'Week', 'soledad' ),
			)
		);
		$this->add_control(
			'str_weeks2', array(
				'label'   => __( 'Weeks (Singular)', 'soledad' ),
				'type'    => Controls_Manager::TEXT,
				'default'   => esc_html__( 'Weeks', 'soledad' ),
			)
		);

		$this->add_control(
			'str_months', array(
				'label'   => __( 'Month (Singular)', 'soledad' ),
				'type'    => Controls_Manager::TEXT,
				'default'   => esc_html__( 'Month', 'soledad' ),
			)
		);
		$this->add_control(
			'str_months2', array(
				'label'   => __( 'Months (Singular)', 'soledad' ),
				'type'    => Controls_Manager::TEXT,
				'default'   => esc_html__( 'Months', 'soledad' ),
			)
		);

		$this->add_control(
			'str_years', array(
				'label'   => __( 'Year (Singular)', 'soledad' ),
				'type'    => Controls_Manager::TEXT,
				'default'   => esc_html__( 'Year', 'soledad' ),
			)
		);
		$this->add_control(
			'str_years2', array(
				'label'   => __( 'Years (Singular)', 'soledad' ),
				'type'    => Controls_Manager::TEXT,
				'default'   => esc_html__( 'Years', 'soledad' ),
			)
		);

		$this->add_control(
			'str_hours', array(
				'label'   => __( 'Hour (Singular)', 'soledad' ),
				'type'    => Controls_Manager::TEXT,
				'default'   => esc_html__( 'Hour', 'soledad' ),
			)
		);
		$this->add_control(
			'str_hours2', array(
				'label'   => __( 'Hours (Singular)', 'soledad' ),
				'type'    => Controls_Manager::TEXT,
				'default'   => esc_html__( 'Hours', 'soledad' ),
			)
		);

		$this->add_control(
			'str_minutes', array(
				'label'   => __( 'Minute (Singular)', 'soledad' ),
				'type'    => Controls_Manager::TEXT,
				'default'   => esc_html__( 'Minute', 'soledad' ),
			)
		);
		$this->add_control(
			'str_minutes2', array(
				'label'   => __( 'Minutes (Singular)', 'soledad' ),
				'type'    => Controls_Manager::TEXT,
				'default'   => esc_html__( 'Minutes', 'soledad' ),
			)
		);

		$this->add_control(
			'str_seconds', array(
				'label'   => __( 'Second (Singular)', 'soledad' ),
				'type'    => Controls_Manager::TEXT,
				'default'   => esc_html__( 'Second', 'soledad' ),
			)
		);
		$this->add_control(
			'str_seconds2', array(
				'label'   => __( 'Seconds (Singular)', 'soledad' ),
				'type'    => Controls_Manager::TEXT,
				'default'   => esc_html__( 'Seconds', 'soledad' ),
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
			'time_digit_color',
			array(
				'label'     => __( 'Timer Digit Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array( '{{WRAPPER}} .penci-countdown-amount' => 'color: {{VALUE}} !important;' ),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(), array(
				'name'     => 'time_digit_typo',
				'label'     => __( 'Timer Digit Typography', 'soledad' ),
				'selector' => '{{WRAPPER}} .penci-countdown-amount',
			)
		);

		$this->add_control(
			'time_digit_bordercolor',
			array(
				'label'     => __( 'Timer Digit Border Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .penci-countdown-s1 .penci-countdown-amount' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .penci-countdown-s2 .penci-countdown-amount' => 'border-color: {{VALUE}};'
				),
				'condition' => array( 'count_down_style' => array( 's1','s2' ) ),
			)
		);

		$this->add_control(
			'time_digit_bgcolor',
			array(
				'label'     => __( 'Timer Digit Background Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array( 'body:not(.pcdm-enable) {{WRAPPER}} .penci-countdown-s1 .penci-countdown-amount' => 'background-color: {{VALUE}} !important;' ),
				'condition' => array( 'count_down_style' => array( 's1' ) ),
			)
		);
		$this->add_control(
			'unit_color',
			array(
				'label'     => __( 'Timer Unit Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array( '{{WRAPPER}} .penci-countdown-period' => 'color: {{VALUE}} !important;' ),
			)
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(), array(
				'name'     => 'unit_typo',
				'label'     => __( 'Timer Unit Typography', 'soledad' ),
				'selector' => '{{WRAPPER}} .penci-countdown-period',
			)
		);
		$this->add_control(
			'countdown_item_bg', array(
				'label'     => __( 'Countdown Section Background Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'condition' => array( 'count_down_style' => array( 's3','s4' ) ),
				'selectors' => array(
					'body:not(.pcdm-enable) {{WRAPPER}} .penci-countdown-s3 .penci-span-inner' => 'background-color: {{VALUE}};',
					'body:not(.pcdm-enable) {{WRAPPER}} .penci-countdown-s4 .penci-span-inner' => 'background-color: {{VALUE}};',
				),
			)
		);
		$this->add_control(
			'countdown_item_border', array(
				'label'     => __( 'Countdown Section Border Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'condition' => array( 'count_down_style' => array( 's3' ) ),
				'selectors' => array( '{{WRAPPER}} .penci-countdown-s3 .penci-span-inner' => 'border-color: {{VALUE}};' ),
			)
		);
		$this->end_controls_section();
	}

	public function get_time_format( $settings ) {
		$data_format = '';

		$opts = array(
			'countdown_Y' => 'Y',
			'countdown_O' => 'O',
			'countdown_W' => 'W',
			'countdown_D' => 'D',
			'countdown_H' => 'H',
			'countdown_M' => 'M',
			'countdown_S' => 'S',
		);

		foreach ( $opts as $k => $v ) {
			if ( isset( $settings[ $k ] ) && $settings[ $k ] ) {
				$data_format .= $v;
			}
		}

		if ( ! $data_format ) {
			$data_format = 'DHMS';
		}

		return $data_format;
	}

	protected function render() {
		$settings              = $this->get_settings();

		$block_id = 'penci_countdown_' . rand( 1000, 100000 );

		$css_class = 'penci-countdown-bsc';
		$css_class .= ' penci-countdown-' . $settings['count_down_style'];
		$css_class .= ' penci-style-' . $settings['count_down_posttion'];

		$labels  = sprintf( "['%s', '%s', '%s', '%s', '%s', '%s', '%s']",
			$settings['str_years2'], $settings['str_weeks2'],
			$settings['str_months2'], $settings['str_days2'],
			$settings['str_hours2'], $settings['str_minutes2'],
			$settings['str_seconds2']
		);
		$labels1 = sprintf( "['%s', '%s', '%s', '%s', '%s', '%s', '%s']",
			$settings['str_years'], $settings['str_weeks'],
			$settings['str_months'], $settings['str_days'],
			$settings['str_hours'], $settings['str_minutes'],
			$settings['str_seconds']
		);

		$data_format = $this->get_time_format( $settings );

		// Data Until
		$data_time = '';
		$data_time .= $settings['count_year'] ? $settings['count_year'] : 0;
		$data_time .= ',';
		$data_time .= $settings['count_month'] ? intval( $settings['count_month'] ) - 1 : 0;
		$data_time .= ',';
		$data_time .= $settings['count_day'] ? $settings['count_day'] : 0;
		$data_time .= ',';
		$data_time .= $settings['count_hour'] ? $settings['count_hour'] : 0;
		$data_time .= ',';
		$data_time .= $settings['count_minus'] ? $settings['count_minus'] : 0;
		$data_time .= ',';
		$data_time .= $settings['count_sec'] ? $settings['count_sec'] : 0;

		$data_time = apply_filters( 'penci_countdown_time_filter', $data_time );
		?>
		<div id="<?php echo esc_attr( $block_id ); ?>" class="<?php echo esc_attr( $css_class ); ?>"></div>
		<script type="text/javascript">
			jQuery( function ( $ ) {
				if ( $.fn.countdown ) {
					var <?php echo esc_attr( $block_id ); ?>newDateTime = new Date(<?php echo $data_time; ?> );

					$( '#<?php echo esc_attr( $block_id ); ?>' ).countdown( {
						until: <?php echo esc_attr( $block_id ); ?>newDateTime,
						labels: <?php echo $labels; ?>,
						labels1: <?php echo $labels1; ?>,
						timezone: <?php echo get_option( 'gmt_offset' ); ?>,
						format: '<?php echo $data_format; ?>',
						<?php echo( is_rtl() ? 'isRTL: true' : '' ); ?>
					} );
				}
			} );
		</script>
		<?php
	}
}
