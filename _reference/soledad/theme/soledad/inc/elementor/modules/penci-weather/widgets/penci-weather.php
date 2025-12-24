<?php
namespace PenciSoledadElementor\Modules\PenciWeather\Widgets;

use PenciSoledadElementor\Base\Base_Widget;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class PenciWeather extends Base_Widget {

	public function get_name() {
		return 'penci-weather';
	}

	public function get_title() {
		return penci_get_theme_name('Penci').' '.esc_html__( ' Weather', 'soledad' );
	}

	public function get_icon() {
		return 'eicon-image';
	}
	
	public function get_categories() {
		return [ 'penci-elements' ];
	}

	public function get_keywords() {
		return array( 'weather' );
	}

    public function get_style_depends(){
	    return ['penci-font-iweather'];
    }

	protected function register_controls() {
		

		$this->start_controls_section(
			'section_layout', array(
				'label' => esc_html__( 'Layout', 'soledad' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'penci_user_loc', array(
				'label'   => __( 'Enable User Location', 'soledad' ),
				'type'    => Controls_Manager::SWITCHER,
				'label_on'  => __( 'Enable', 'soledad' ),
				'label_off' => __( 'Disable', 'soledad' ),
			)
		);

		$this->add_control(
			'penci_save_user_loc', array(
				'label'   => __( 'Use Cookies to Save Weather Data When Users Access it Based on Their Location?', 'soledad' ),
				'type'    => Controls_Manager::SWITCHER,
				'label_on'  => __( 'Enable', 'soledad' ),
				'label_off' => __( 'Disable', 'soledad' ),
				'condition' => ['penci_user_loc'=>'yes']
			)
		);

		$this->add_control(
			'penci_location', array(
				'label'       => __( 'Search your for location:', 'soledad' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => 'London',
				'description' => sprintf( '%s - You can use "city name" (ex: New York) or "city name,country code" (ex: New York, US)',
					'<a href="' . esc_url( 'http://openweathermap.org/find' ) . '">' . esc_html__( 'Find your location', 'soledad' ) . '</a>' ),
				'label_block' => true,
			)
		);

		$this->add_control(
			'penci_location_show', array(
				'label'       => __( 'Location display', 'soledad' ),
				'type'        => Controls_Manager::TEXT,
				'description' => esc_html__( 'If the option is empty,will display results from ', 'soledad' ) . '<a href="' . esc_url( 'http://openweathermap.org/find' ) . '">openweathermap.org</a>',
				'label_block' => true,
			)
		);

		$this->add_control(
			'penci_units', array(
				'label'   => __( 'Units', 'soledad' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'metric',
				'options' => array(
					'imperial' => esc_html__( 'F', 'soledad' ),
					'metric'   => esc_html__( 'C', 'soledad' ),
				),
			)
		);

		$this->add_control(
			'penci_forcast', array(
				'label'   => __( 'Forcast', 'soledad' ),
				'type'    => Controls_Manager::SELECT,
				'default' => '5',
				'options' => array(
					'1' => esc_html__( '1 Day', 'soledad' ),
					'2' => esc_html__( '2 Days', 'soledad' ),
					'3' => esc_html__( '3 Days', 'soledad' ),
					'4' => esc_html__( '4 Days', 'soledad' ),
					'5' => esc_html__( '5 Days', 'soledad' ),
				),
			)
		);
		
		$this->end_controls_section();
		$this->register_block_title_section_controls();

		// Design
		$this->start_controls_section(
			'section_design_content',
			array(
				'label' => __( 'Content', 'soledad' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'w_genneral_color',
			array(
				'label'     => __( 'General color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .penci-weather-condition,' .
					'{{WRAPPER}} .penci-weather-information,' .
					'{{WRAPPER}} .penci-weather-lo-hi__content .fa,' .
					'{{WRAPPER}} .penci-circle,' .
					'{{WRAPPER}} .penci-weather-animated-icon i,' .
					'{{WRAPPER}} .penci-weather-unit' => 'color: {{VALUE}};  opacity: 1;',
				),
			)
		);

		$this->add_control(
			'w_localtion_color',
			array(
				'label'     => __( 'Localtion color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array( '{{WRAPPER}} .penci-weather-city' => 'color: {{VALUE}};' ),
			)
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(), array(
				'name'     => 'w_location_typo',
				'label'    => __( 'Typography for Location', 'soledad' ),
				'selector' => '{{WRAPPER}} .penci-weather-city',
			)
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(), array(
				'name'     => 'w_condition_typo',
				'label'    => __( 'Typography for Cloudiness', 'soledad' ),
				'selector' => '{{WRAPPER}} .penci-weather-condition',
			)
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(), array(
				'name'     => 'w_whc_info_typo',
				'label'    => __( 'Typography for Wind,Humidity, Clouds', 'soledad' ),
				'selector' => '{{WRAPPER}} .penci-weather-information',
			)
		);

		$this->add_control(
			'w_border_color',
			array(
				'label'     => __( 'Border color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array( '{{WRAPPER}} .penci-weather-information' => 'border-color: {{VALUE}};' ),
			)
		);

		$this->add_control(
			'w_degrees_color',
			array(
				'label'     => __( 'Degrees color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array( '{{WRAPPER}} .penci-big-degrees,{{WRAPPER}} .penci-small-degrees' => 'color: {{VALUE}};' ),
			)
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(), array(
				'name'     => 'w_temp_typo',
				'label'    => __( 'Typography for Temperature', 'soledad' ),
				'selector' => '{{WRAPPER}} .penci-weather-now .penci-big-degrees',
			)
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(), array(
				'name'     => 'w_tempsmall_typo',
				'label'    => __( 'Typography for Min/Max Temperature', 'soledad' ),
				'selector' => '{{WRAPPER}} .penci-weather-degrees-wrap .penci-small-degrees',
			)
		);

		$this->add_control(
			'w_forecast_text_color',
			array(
				'label'     => __( 'Custom color for forecast weather in next days', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array( '{{WRAPPER}} .penci-weather-week' => 'color: {{VALUE}};' ),
			)
		);

		$this->add_control(
			'w_forecast_bg_color',
			array(
				'label'     => __( 'Custom background for forecast weather in next days', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
                        'body:not(.pcdm-enable) {{WRAPPER}} .penci-weather-week:before' => 'background-color: {{VALUE}}; opacity: 1;',
                ),
			)
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(), array(
				'name'     => 'w_forecast_typo',
				'label'    => __( 'Typography for Weather Forecast', 'soledad' ),
				'selector' => '{{WRAPPER}} .penci-weather-days .penci-day-degrees',
			)
		);


		$this->end_controls_section();
		
		$this->register_block_title_style_section_controls();

	}

	protected function render() {
		$settings = $this->get_settings();

		$css_class = 'penci-block-vc penci_block_weather penci-weather';
		?>
		<div class="<?php echo esc_attr( $css_class ); ?>">
			<?php $this->markup_block_title( $settings, $this ); ?>
			<div class="penci-block_content">
				<?php
				$weather_data = \Penci_Weather::show_forecats( array(
					'location'      => $settings['penci_location'],
					'location_show' => $settings['penci_location_show'],
					'forecast_days' => $settings['penci_forcast'],
					'units'         => $settings['penci_units'],
					'user_loc'		=> $settings['penci_user_loc'],
					'cookie'		=> $settings['penci_save_user_loc'],
				) );

				if( $weather_data ) {
					echo $weather_data;
				}else {
					echo '<div class="penci-block-error">';
					echo '<span>Weather widget</span>';
					echo ' You need to fill API key to Customize > General > Extra Options > Weather API Key to get this widget work.';
					echo '</div>';
				}
				?>
			</div>
		</div>
		<?php
	}
}
