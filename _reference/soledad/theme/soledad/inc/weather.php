<?php

class Penci_Weather {

	private static $caching_time = 14400;  // 3 hours

	private static $default_api_keys = '65a589922c250a25a65e5d934a2cc9e9';

	public static function get_appid() {

		$api_key = get_theme_mod( 'penci_api_weather_key' );

		if ( ! $api_key ) {
			$api_key = self::$default_api_keys;
		}

		return $api_key;
	}

	public static function weather_data( $atts ) {

		$atts = wp_parse_args( $atts, array(
			'w_type'        => 'today',
			'locale'        => 'en',
			'location'      => '',
			'location_show' => '',
			'forecast_days' => '5',
			'units'         => 'metric', // metric or imperial
		) );

		if ( empty( $atts['location'] ) ) {
			return self::mess_error();
		}

		$weather_data = array(
			'api_location'     => $atts['location'],
			'api_locale'       => $atts['locale'],
			'today_icon'       => '',
			'today_icon_text'  => '',
			'today_temp'       => array( 0, 0 ),
			'today_humidity'   => '',
			'today_wind_speed' => array( 0, 0 ),
			'today_min'        => array( 0, 0 ),
			'today_max'        => array( 0, 0 ),
			'today_clouds'     => 0,
			'current_unit'     => 'metric' == $atts['units'] ? '0' : '1',
			'temp_label'       => 'metric' == $atts['units'] ? 'C' : 'F',
			'speed_label'      => 'metric' == $atts['units'] ? 'km/h' : 'mp/h',
			'forecast'         => array()
		);

		$weather_data_status = self::get_weather_data( $atts, $weather_data );

		return $weather_data_status;

	}

	public static function get_weather_data( $atts, $weather_data ) {

		$sytem_locale      = get_locale();
		$available_locales = self::available_locales();

		// Check for locale
		if ( in_array( $sytem_locale, $available_locales ) ) {
			$atts['locale'] = $sytem_locale;
		}

		// Check for locale by first two digits
		if ( in_array( substr( $sytem_locale, 0, 2 ), $available_locales ) ) {
			$atts['locale'] = substr( $sytem_locale, 0, 2 );
		}

		// TRANSIENT NAME
		$weather_transient_name = 'penci_' . sanitize_title( $atts['location'] ) . strtolower( $atts['units'] ) . '_' . $atts['locale'];

		if ( isset( $_GET['penci_clear_weather_data'] ) ) {
			delete_transient( $weather_transient_name );
		}

		$api_key     = self::get_appid();
		$api_key_old = get_theme_mod( 'penci_api_weather_key_old' );

		if ( $api_key && $api_key != $api_key_old ) {
			set_theme_mod( 'penci_api_weather_key_old', $api_key );
		}
		delete_transient( $weather_transient_name );
		if ( get_transient( $weather_transient_name ) && $api_key == $api_key_old ) {
			$weather_data = get_transient( $weather_transient_name );
		} else {

			$weather_data = self::get_today_data( $atts, $weather_data );
			if ( 'forecast' == $atts['w_type'] ) {
				$weather_data = self::get_forecast_data( $atts, $weather_data );

			}
			set_transient( $weather_transient_name, $weather_data, self::$caching_time );
		}


		if ( 'forecast' == $atts['w_type'] && isset( $weather_data['forecast'] ) && ! $weather_data['forecast'] ) {

			delete_transient( $weather_transient_name );
			$weather_data = self::get_forecast_data( $atts, $weather_data );
			set_transient( $weather_transient_name, $weather_data, self::$caching_time );
		}

		return $weather_data;


	}

	public static function get_today_data( $atts, $weather_data ) {
		$http = is_ssl() ? 'https://' : 'http://';

		$now_ping = $http . "api.openweathermap.org/data/2.5/weather?q=" . urlencode( $atts['location'] ) . "&lang=" . $atts['locale'] . "&units=" . $atts['units'] . '&APPID=' . self::get_appid();

		$now_ping_get = wp_remote_get( $now_ping );

		if ( is_wp_error( $now_ping_get ) ) {
			return $weather_data;
		}

		// get api response
		$api_response = json_decode( $now_ping_get['body'], true );

		if ( isset( $api_response['cod'] ) && $api_response['cod'] == 404 ) {
			return $weather_data;
		} else {
			// Current location
			if ( isset( $api_response['name'] ) ) {
				$weather_data['api_location'] = $api_response['name'];
			}

			// min max current temperature
			if ( isset( $api_response['main']['temp'] ) ) {
				$weather_data['today_temp'][0] = round( $api_response['main']['temp'], 1 );
				$weather_data['today_temp'][1] = $api_response['main']['temp'];
			}
			if ( isset( $api_response['main']['temp_min'] ) ) {
				$weather_data['today_min'][0] = round( $api_response['main']['temp_min'], 1 );
				$weather_data['today_min'][1] = $api_response['main']['temp_min'];
			}
			if ( isset( $api_response['main']['temp_max'] ) ) {
				$weather_data['today_max'][0] = round( $api_response['main']['temp_max'], 1 );
				$weather_data['today_max'][1] = $api_response['main']['temp_max'];
			}

			// humidity
			if ( isset( $api_response['main']['humidity'] ) ) {
				$weather_data['today_humidity'] = round( $api_response['main']['humidity'] );
			}

			// wind speed and direction
			if ( isset( $api_response['wind']['speed'] ) ) {
				$weather_data['today_wind_speed'][0] = round( $api_response['wind']['speed'], 1 );
				$weather_data['today_wind_speed'][1] = self::convert_kmph_to_mph( $api_response['wind']['speed'] );
			}

			// forecast description
			if ( isset( $api_response['weather'][0]['description'] ) ) {
				$weather_data['today_icon_text'] = $api_response['weather'][0]['description'];
			}

			// clouds
			if ( isset( $api_response['clouds']['all'] ) ) {
				$weather_data['today_clouds'] = round( $api_response['clouds']['all'] );
			}

			// forecast description
			if ( isset( $api_response['weather'][0]['description'] ) ) {
				$weather_data['today_icon_text'] = $api_response['weather'][0]['description'];
			}

			// clouds
			if ( isset( $api_response['clouds']['all'] ) ) {
				$weather_data['today_clouds'] = round( $api_response['clouds']['all'] );
			}

			// icon
			if ( isset( $api_response['weather'][0]['icon'] ) ) {
				$icons = array(
					// day
					'01d' => 'wi-day-sunny',
					'02d' => 'wi-day-cloudy-high',
					'03d' => 'wi-cloudy',
					'04d' => 'wi-cloudy',
					'09d' => 'wi-day-showers',
					'10d' => 'wi-day-rain',
					'11d' => 'wi-day-thunderstorm',
					'13d' => 'wi-day-showers',
					'50d' => 'wi-day-cloudy-high',

					//night
					'01n' => 'wi-night-clear',
					'02n' => 'wi-night-alt-cloudy',
					'03n' => 'wi-night-alt-cloudy-high',
					'04n' => 'wi-night-cloudy',
					'09n' => 'wi-night-showers',
					'10n' => 'wi-night-rain',
					'11n' => 'wi-night-thunderstorm',
					'13n' => 'wi-night-snow-wind',
					'50n' => 'wi-night-alt-cloudy',
				);

				$weather_data['today_icon'] = 'wi-day-sunny';
				if ( isset( $icons[ $api_response['weather'][0]['icon'] ] ) ) {
					$weather_data['today_icon'] = $icons[ $api_response['weather'][0]['icon'] ];
				}
			}
		}

		return $weather_data;
	}

	public static function get_forecast_data( $atts, $weather_data ) {
		$http = is_ssl() ? 'https://' : 'http://';

		$type = isset( $atts['type'] ) && $atts['type'] ? $atts['type'] : 'forecast';

		$forecast_ping     = $http . "api.openweathermap.org/data/2.5/" . $type . "?q=" . urlencode( $atts['location'] ) . "&lang=" . $atts['locale'] . "&units=" . $atts['units'] . "&APPID=" . self::get_appid();
		$forecast_ping_get = wp_remote_get( $forecast_ping );

		if ( is_wp_error( $forecast_ping_get ) ) {
			return $forecast_ping_get->get_error_message();
		}

		$forecast_data = json_decode( $forecast_ping_get['body'], true );

		if ( isset( $forecast_data['cod'] ) && ( $forecast_data['cod'] == 404 || $forecast_data['cod'] == 401 ) ) {
			return array();
		} else {
			$weather_data['forecast'] = $forecast_data;
		}

		return $weather_data;
	}

	public static function available_locales() {
		return array(
			'ar',
			'bg',
			'ca',
			'cz',
			'de',
			'el',
			'en',
			'fa',
			'fi',
			'fr',
			'gl',
			'hr',
			'hu',
			'it',
			'ja',
			'kr',
			'la',
			'lt',
			'mk',
			'nl',
			'pl',
			'pt',
			'ro',
			'ru',
			'se',
			'sk',
			'sl',
			'es',
			'tr',
			'ua',
			'vi',
			'zh_cn',
			'zh_tw'
		);
	}

	public static function mess_error( $msg = false ) {
		$msg = $msg ? $msg : esc_html__( 'No weather information available', 'soledad' );

		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		return "<div class='penci-weather-error'>" . $msg . "</div>";
	}

	/**
	 * convert celsius to fahrenheit + rounding (no decimals if result > 100 or one decimal if result < 100)
	 *
	 * @param $celsius_degrees
	 *
	 * @return float
	 */
	private static function convert_c_to_f( $celsius_degrees ) {
		$f_degrees = $celsius_degrees * 9 / 5 + 32;

		$rounded_val = round( $f_degrees, 1 );
		if ( $rounded_val > 99.9 ) {
			return round( $f_degrees );
		}

		return $rounded_val;
	}

	/**
	 * Convert kmph to mph
	 *
	 * @param $kmph
	 *
	 * @return float
	 */
	private static function convert_kmph_to_mph( $kmph ) {
		return round( $kmph * 0.621371192, 1 );
	}

	public static function get_location_show( $atts, $weather_data ) {
		$api_location = '';
		if ( isset( $atts['location_show'] ) && $atts['location_show'] ) {
			$api_location = $atts['location_show'];
		} elseif ( isset( $weather_data['api_location'] ) ) {
			$api_location = $weather_data['api_location'];
		}

		return $api_location;
	}

	public static function get_top_bar_template( $atts ) {
		$weather_data = self::weather_data( $atts );

		if ( empty( $weather_data ) ) {
			return;
		}

		$api_location = self::get_location_show( $atts, $weather_data );

		$current_unit = isset( $weather_data['current_unit'] ) ? $weather_data['current_unit'] : '';
		$today_temp   = isset( $weather_data['today_temp'][ $current_unit ] ) ? $weather_data['today_temp'][ $current_unit ] : '';
		$temp_label   = isset( $weather_data['temp_label'] ) ? $weather_data['temp_label'] : '';
		$today_icon   = isset( $weather_data['today_icon'] ) ? $weather_data['today_icon'] : '';

		if ( '' === $today_temp ) {
			return;
		}

		?>
        <div class="topbar-item topbar-weather">
            <i class="penci-weather-icons wi <?php echo $today_icon; ?>"></i>
            <div class="penci-weather-now">
                <span class="penci-weather-degrees"><?php echo( $today_temp == '-0' ? 0 : $today_temp ); ?></span>
                <span class="penci-weather-unit"><?php echo $temp_label; ?></span>
            </div>
            <div class="penci-weather-location">
                <div class="penci-weather-city"><?php echo $api_location; ?></div>
            </div>
        </div>
		<?php
	}

	public static function weather_icon( $icon ) {

		// Sunny
		if ( $icon == '01d' ) {
			$weather_icon = '
					<div class="weather-icon">
						<div class="icon-sun"></div>
					</div>
				';
		} // Moon
        elseif ( $icon == '01n' ) {
			$weather_icon = '
					<div class="weather-icon">
						<div class="icon-moon"></div>
					</div>
				';
		} // Cloudy Sunny
        elseif ( $icon == '02d' || $icon == '03d' || $icon == '04d' ) {
			$weather_icon = '
					<div class="weather-icon">
						<div class="icon-cloud"></div>
						<div class="icon-cloud-behind"></div>
						<div class="icon-sun-animi"></div>
					</div>
				';
		} // Cloudy Night
        elseif ( $icon == '02n' || $icon == '03n' || $icon == '04n' ) {
			$weather_icon = '
					<div class="weather-icon">
						<div class="icon-cloud"></div>
						<div class="icon-cloud-behind"></div>
						<div class="icon-moon-animi"></div>
					</div>
				';
		} // Showers
        elseif ( $icon == '09d' || $icon == '09n' ) {
			$weather_icon = '
					<div class="weather-icon drizzle-icons showers-icons">
						<div class="basecloud"></div>
						<div class="animi-icons-wrap">
							<div class="icon-rainy-animi"></div>
							<div class="icon-rainy-animi-2"></div>
							<div class="icon-rainy-animi-4"></div>
							<div class="icon-rainy-animi-5"></div>
						</div>
					</div>
				';
		} // Rainy Sunny
        elseif ( $icon == '10d' ) {
			$weather_icon = '
					<div class="weather-icon">
						<div class="basecloud"></div>
						<div class="animi-icons-wrap">
							<div class="icon-rainy-animi"></div>
							<div class="icon-rainy-animi-2"></div>
							<div class="icon-rainy-animi-4"></div>
							<div class="icon-rainy-animi-5"></div>
						</div>
						<div class="icon-sun-animi"></div>
					</div>
				';
		} // Rainy Night
        elseif ( $icon == '10n' ) {
			$weather_icon = '
					<div class="weather-icon">
						<div class="basecloud"></div>
						<div class="animi-icons-wrap">
							<div class="icon-rainy-animi"></div>
							<div class="icon-rainy-animi-2"></div>
							<div class="icon-rainy-animi-4"></div>
							<div class="icon-rainy-animi-5"></div>
						</div>
						<div class="icon-moon-animi"></div>
					</div>
				';
		} // Thunder
        elseif ( $icon == '11d' || $icon == '11n' ) {
			$weather_icon = '
					<div class="weather-icon">
						<div class="basecloud"></div>
						<div class="animi-icons-wrap">
							<div class="icon-thunder-animi"></div>
						</div>
					</div>
				';
		} // Snowing
        elseif ( $icon == '13d' || $icon == '13n' ) {
			$weather_icon = '
					<div class="weather-icon weather-snowing">
						<div class="basecloud"></div>
						<div class="animi-icons-wrap">
							<div class="icon-windysnow-animi"></div>
							<div class="icon-windysnow-animi-2"></div>
						</div>
					</div>
				';
		} // Mist
        elseif ( $icon == '50d' || $icon == '50n' ) {
			$weather_icon = '
					<div class="weather-icon">
						<div class="icon-mist"></div>
						<div class="icon-mist-animi"></div>
					</div>
				';
		} /// Default icon | Cloudy
		else {
			$weather_icon = '
					<div class="weather-icon">
						<div class="icon-cloud"></div>
						<div class="icon-cloud-behind"></div>
					</div>
				';
		}

		return apply_filters( 'soledad_weather_icon', $weather_icon, $icon );
	}


	public static function show_forecats( $atts ) {

		if ( ( isset($atts['cookie'] ) && $atts['cookie'] ) && $atts['user_loc'] && isset( $_COOKIE['PenciUserCity'] ) && $_COOKIE['PenciUserCity'] ) {
			$atts['location'] = $_COOKIE['PenciUserCity'];
		}


		$weather_data = self::weather_data( array(
			'w_type'        => 'forecast',
			'locale'        => 'en',
			'location'      => $atts['location'],
			'forecast_days' => $atts['forecast_days'],
			'units'         => $atts['units']
		) );

		$forecast      = isset( $weather_data['forecast'] ) && is_array( $weather_data['forecast'] ) ? $weather_data['forecast'] : array();
		$forecast_list = isset( $forecast['list'] ) && is_array( $weather_data['forecast'] ) ? $forecast['list'] : array();

		if ( ! $forecast_list ) {
			$weather_transient_name = 'penci_' . sanitize_title( $atts['location'] ) . strtolower( $atts['units'] ) . '_' . ( isset( $atts['locale'] ) && $atts['locale'] ? $atts['locale'] : 'en' );
			delete_transient( $weather_transient_name );

			return;
		}

		$current_unit = $weather_data['current_unit'];
		$days_to_show = (int) $atts['forecast_days'];
		$dt_today     = date( 'Ymd', current_time( 'timestamp', 0 ) );

		if ( function_exists( 'penci_get_tran_setting' ) ) {
			$days_of_week = array(
				penci_get_tran_setting( 'penci_weather_sun_text' ),
				penci_get_tran_setting( 'penci_weather_mon_text' ),
				penci_get_tran_setting( 'penci_weather_tue_text' ),
				penci_get_tran_setting( 'penci_weather_wed_text' ),
				penci_get_tran_setting( 'penci_weather_thu_text' ),
				penci_get_tran_setting( 'penci_weather_fri_text' ),
				penci_get_tran_setting( 'penci_weather_sat_text' ),
			);
		} else {
			$days_of_week = array(
				__( 'Sun', 'soledad' ),
				__( 'Mon', 'soledad' ),
				__( 'Tue', 'soledad' ),
				__( 'Wed', 'soledad' ),
				__( 'Thu', 'soledad' ),
				__( 'Fri', 'soledad' ),
				__( 'Sat', 'soledad' )
			);
		}


		$wind_speed       = isset( $weather_data['today_wind_speed'][ $current_unit ] ) ? $weather_data['today_wind_speed'][ $current_unit ] : '';
		$wind_speed_text  = isset( $weather_data['speed_label'] ) ? $weather_data['speed_label'] : '';
		$today_high       = isset( $weather_data['today_max'][ $current_unit ] ) ? round( $weather_data['today_max'][ $current_unit ] ) : '';
		$today_low        = isset( $weather_data['today_min'][ $current_unit ] ) ? round( $weather_data['today_min'][ $current_unit ] ) : '';
		$today_temp       = isset( $weather_data['today_temp'][ $current_unit ] ) ? round( $weather_data['today_temp'][ $current_unit ] ) : '';
		$today_temp_label = isset( $weather_data['temp_label'] ) ? $weather_data['temp_label'] : '';
		$today_clouds     = isset( $weather_data['today_clouds'] ) ? $weather_data['today_clouds'] . '%' : '';

		$units_display_symbol = '&deg;';

		$nonce = wp_create_nonce( 'penci-weather' );

		$output = '<div class="penci-weather-widget" data-id="' . $nonce . '" data-forecast_days="' . $atts['forecast_days'] . '" data-units="' . $atts['units'] . '">';

		if ( $atts['user_loc'] ) {
			$output .= '<span aria-label="' . esc_attr__( 'Click to get current location weather', 'soledad' ) . '" class="penci-wuser-gps"><svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M12 19.5C16.1421 19.5 19.5 16.1421 19.5 12C19.5 7.85786 16.1421 4.5 12 4.5C7.85786 4.5 4.5 7.85786 4.5 12C4.5 16.1421 7.85786 19.5 12 19.5Z" stroke="#292D32" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
<path d="M12 15C13.6569 15 15 13.6569 15 12C15 10.3431 13.6569 9 12 9C10.3431 9 9 10.3431 9 12C9 13.6569 10.3431 15 12 15Z" stroke="#292D32" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
<path d="M12 4V2" stroke="#292D32" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
<path d="M4 12H2" stroke="#292D32" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
<path d="M12 20V22" stroke="#292D32" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
<path d="M20 12H22" stroke="#292D32" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
</svg></span>';
		}

		$api_location = self::get_location_show( $atts, $weather_data );


		if ( $api_location ) {
			$output .= '<div class="penci-weather-header">';
			$output .= '<div class="penci-weather-city">' . $api_location . '</div>';

			if ( isset( $weather_data['today_icon_text'] ) ) {
				$output .= '<div class="penci-weather-condition">' . $weather_data['today_icon_text'] . '</div>';
			}
			$output .= '</div>';
		}

		$output .= '<div class="penci-weather-information">';


		if ( isset( $weather_data['today_humidity'] ) ) {
			$output .= '<div class="penci-weather-section penci_humidty"><i class="wi wi-humidity"></i>' . $weather_data['today_humidity'] . '%</div>';
		}
		if ( $wind_speed && $wind_speed_text ) {
			$output .= '<div class="penci-weather-section penci_wind"><i class="wi wi-windy"></i>' . $wind_speed . $wind_speed_text . '</div>';
		}

		if ( $today_clouds ) {
			$output .= '<div class="penci-weather-section penci_clouds">' . penci_icon_by_ver( 'fas fa-cloud' ) . $today_clouds . '</div>';
		}


		$output .= '</div>';


		$output .= '<div class="penci-weather-degrees-wrap">';

		$today_icon = array_values( $weather_data['forecast']['list'][0]['weather'][0] );

		if ( isset( $today_icon[3] ) ) {
			$output .= '<div class="penci-weather-animated-icon is-animated">' . self::weather_icon( $today_icon[3] ) . '</div>';
		}

		$output .= '<div class="penci-weather-now">';
		$output .= '<span class="penci-big-degrees">' . $today_temp . '</span>';
		$output .= '<span class="penci-circle">' . $units_display_symbol . '</span>';
		$output .= '<span class="penci-weather-unit">' . $today_temp_label . '</span>';
		$output .= '</div>';

		$output .= '<div class="penci-weather-lo-hi">';
		$output .= '<div class="penci-weather-lo-hi__content">';
		$output .= penci_icon_by_ver( 'fas fa-angle-double-up' );
		$output .= '<span class="penci-small-degrees penci-w-high-temp">' . $today_high . '</span>';
		$output .= '<span class="penci-circle">' . $units_display_symbol . '</span>';
		$output .= '</div>';
		$output .= '<div class="penci-weather-lo-hi__content">';
		$output .= penci_icon_by_ver( 'fas fa-angle-double-down' );
		$output .= '<span class="penci-small-degrees penci-w-low-temp">' . $today_low . '</span>';
		$output .= '<span class="penci-circle">' . $units_display_symbol . '</span>';
		$output .= '</div>';
		$output .= '</div>';
		$output .= '</div>';


		$output .= "<div class=\"penci-weather-week penci_days_{$days_to_show} \">";
		$day    = 1;

		$term_arr = array();
		if ( $forecast_list ) {
			foreach ( (array) $forecast_list as $forecast ) {
				$forecast_dt        = isset( $forecast['dt'] ) ? $forecast['dt'] : '';
				$forecast_main_temp = isset( $forecast['main']['temp'] ) ? (int) $forecast['main']['temp'] : '';

				$forecast_icon = array_values( $forecast['weather'][0] );
				$forecast_icon = $forecast_icon[3];


				if ( empty( $forecast_dt ) || empty( $forecast_main_temp ) ) {
					continue;
				}

				$current_day = date( 'j', $forecast_dt );

				if ( ! in_array( $current_day, $term_arr ) ) {
					$term_arr[] = $current_day;
				} else {
					continue;
				}

				$day_of_week = isset( $days_of_week[ date( 'w', $forecast_dt ) ] ) ? $days_of_week[ date( 'w', $forecast_dt ) ] : date_i18n( 'D', $forecast_dt );

				$output .= "
						<div class='penci-weather-days is-animated'>
							" . self::weather_icon( $forecast_icon ) . "
							<div class='penci-day-degrees'><span class='penci-degrees'>{$forecast_main_temp}</span><span class='circle'>{$units_display_symbol}</span></div>
							<div class='penci-day'>{$day_of_week}</div>
						</div>";

				if ( $day == $days_to_show ) {
					break;
				}

				$day ++;

			}
		}

		$output .= '</div>';

		$output .= '</div>';

		return $output;
	}
}

if ( ! function_exists( 'penci_get_user_location' ) ) {
	function penci_get_user_location() {
		$city = '';
		$ip   = file_get_contents( 'https://api.ipify.org' );

		if ( $ip !== false ) {

			$request = wp_remote_get( 'http://ip-api.com/json/' . $ip );

			if ( ! is_wp_error( $request ) ) {

				$request = wp_remote_retrieve_body( $request );
				$request = json_decode( $request, true );

				if ( $request && ! empty( $request['status'] ) && $request['status'] == 'success' ) {
					$city = $request['city'];
					setcookie( 'PenciUserCity', $city, time() + ( DAY_IN_SECONDS * 30 ), "/" );
				}
			}
		}

		return $city;
	}
}

if ( ! function_exists( 'penci_get_weather_loc' ) ) {
	add_action( 'wp_ajax_nopriv_penci_get_weather_loc', 'penci_get_weather_loc' );
	add_action( 'wp_ajax_penci_get_weather_loc', 'penci_get_weather_loc' );
	function penci_get_weather_loc() {

		check_ajax_referer( 'penci-weather', 'id' );

		$forecast_days = $_REQUEST['forecast_days'];
		$units         = $_REQUEST['units'];

		$city = penci_get_user_location();

		if ( empty( $city ) ) {
			wp_send_json_error( [ 'message' => esc_html__( 'Failed to fetch your current loction! try again later!', 'soledad' ) ] );
		}

		$weather_data = '';

		if ( ! empty( $city ) ) {

			$weather_data = \Penci_Weather::show_forecats( array(
				'location'      => $city,
				'forecast_days' => $forecast_days,
				'units'         => $units,
				'user_loc'      => true,
			) );
		}


		wp_send_json_success( [ 'html' => $weather_data ] );

	}
}