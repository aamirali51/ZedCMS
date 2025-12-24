<?php
/**
 * Weather Widget
 *
 * @package Soledad
 * @since 1.0
 */

add_action( 'widgets_init', 'penci_weather_widget' );

function penci_weather_widget() {
	register_widget( 'penci_weather_widget_class' );
}

if ( ! class_exists( 'penci_weather_widget_class' ) ) {
	class penci_weather_widget_class extends WP_Widget {

		/**
		 * Widget setup.
		 */
		function __construct() {
			/* Widget settings. */
			$widget_ops = array(
				'classname'   => 'penci_weather_widget_class',
				'description' => esc_html__( 'A widget that displays your recent posts from all categories or a category', 'soledad' )
			);

			/* Widget control settings. */
			$control_ops = array( 'id_base' => 'penci_weather_widget_class' );

			/* Create the widget. */
			global $wp_version;
			if ( 4.3 > $wp_version ) {
				$this->WP_Widget( 'penci_weather_widget_class', penci_get_theme_name( '.Soledad', true ) . esc_html__( 'Weather', 'soledad' ), $widget_ops, $control_ops );
			} else {
				parent::__construct( 'penci_weather_widget_class', penci_get_theme_name( '.Soledad', true ) . esc_html__( 'Weather', 'soledad' ), $widget_ops, $control_ops );
			}
		}

		/**
		 * How to display the widget on the screen.
		 */
		function widget( $args, $instance ) {
			extract( $args );

			/* Our variables from the widget settings. */
			$title      = isset( $instance['title'] ) ? $instance['title'] : '';
			$penci_location = isset( $instance['penci_location'] ) ? $instance['penci_location'] : '';
			$penci_location_show    = isset( $instance['penci_location_show'] ) ? $instance['penci_location_show'] : '';
			$penci_user_loc      = isset( $instance['penci_user_loc'] ) ? $instance['penci_user_loc'] : '';
			$penci_save_user_loc      = isset( $instance['penci_save_user_loc'] ) ? $instance['penci_save_user_loc'] : '';
			$units     = isset( $instance['units'] ) ? $instance['units'] : '';
			$penci_forcast      = isset( $instance['penci_forcast'] ) ? $instance['penci_forcast'] : '';

			

			/* Before widget (defined by themes). */
			echo ent2ncr( $before_widget );

			/* Display the widget title if one was input (before and after defined by themes). */
			if ( $title ) {
				echo ent2ncr( $before_title ) . $title . ent2ncr( $after_title );
			}


			$weather_data = \Penci_Weather::show_forecats( array(
				'location'      => $penci_location,
				'location_show' => $penci_location_show,
				'forecast_days' => $penci_forcast,
				'units'         => $units,
				'user_loc'		=> $penci_user_loc == 'enable',
				'cookie'		=> $penci_save_user_loc == 'enable',
			) );

			if( $weather_data ) {
				wp_enqueue_style( 'penci-font-iweather' );
				echo $weather_data;
			}else {
				echo '<div class="penci-block-error">';
				echo '<span>Weather widget</span>';
				echo ' You need to fill API key to Customize > General > Extra Options > Weather API Key to get this widget work.';
				echo '</div>';
			}
				

			/* After widget (defined by themes). */
			echo ent2ncr( $after_widget );
		}

		/**
		 * Update the widget settings.
		 */
		function update( $new_instance, $old_instance ) {
			$instance = $old_instance;

			$data_instance = $this->soledad_widget_defaults();

			foreach ( $data_instance as $data => $value ) {
				$instance[ $data ] = ! empty( $new_instance[ $data ] ) ? $new_instance[ $data ] : '';
			}

			if ( ! empty( $new_instance['cats_id'] ) ) {
				if ( is_array( $new_instance['cats_id'] ) ) {
					$instance['cats_id'] = implode( ',', $new_instance['cats_id'] );
				} else {
					$instance['cats_id'] = esc_sql( $new_instance['cats_id'] );
				}
			} else {
				$instance['cats_id'] = false;
			}

			if ( ! empty( $new_instance['tags_id'] ) ) {
				if ( is_array( $new_instance['tags_id'] ) ) {
					$instance['tags_id'] = implode( ',', $new_instance['tags_id'] );
				} else {
					$instance['tags_id'] = esc_sql( $new_instance['tags_id'] );
				}
			} else {
				$instance['tags_id'] = false;
			}

			return $instance;
		}

		public function soledad_widget_defaults() {
			$defaults = array(
				'title'          => esc_html__( 'Weather', 'soledad' ),
				'penci_location'     => '',
				'penci_location_show'     => '',
				'penci_user_loc'     => '',
				'penci_save_user_loc'     => '',
				'units'     => '',
				'penci_forcast'     => '',
			);

			return $defaults;
		}

		function form( $instance ) {

			/* Set up some default widget settings. */
			$defaults = $this->soledad_widget_defaults();

			$cats_id = array();
			$tags_id = array();

			if ( isset( $instance['cats_id'] ) && ! empty( $instance['cats_id'] ) ) {
				$cats_id = explode( ',', $instance['cats_id'] );
			}
			if ( isset( $instance['tags_id'] ) && ! empty( $instance['tags_id'] ) ) {
				$tags_id = explode( ',', $instance['tags_id'] );
			}

			$instance = wp_parse_args( (array) $instance, $defaults );

			$instance_title = $instance['title'] ? str_replace( '"', '&quot;', $instance['title'] ) : '';
			$instance_penci_location = $instance['penci_location'] ? str_replace( '"', '&quot;', $instance['penci_location'] ) : '';
			$instance_penci_location_show = $instance['penci_location_show'] ? str_replace( '"', '&quot;', $instance['penci_location_show'] ) : '';
			?>
            <style>span.description {
                    font-style: italic;
                    font-size: 13px;
                }</style>
            
            <!-- Widget Title: Text Input -->
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'soledad' ); ?></label>
                <input type="text" class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"
                       name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>"
                       value="<?php echo $instance_title; ?>"/>
            </p>

            <!-- Location -->
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'penci_location' ) ); ?>"><?php esc_html_e( 'Search your for location:', 'soledad' ); ?></label>
                <input type="text" class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'penci_location' ) ); ?>"
                       name="<?php echo esc_attr( $this->get_field_name( 'penci_location' ) ); ?>"
                       value="<?php echo $instance_penci_location; ?>"/>
                <span class="description"><a href="<?php echo esc_url( 'http://openweathermap.org/find' );?>"><?php echo sprintf( '%s - You can use "city name" (ex: New York) or "city name,country code" (ex: New York, US)',
					'<a href="' . esc_url( 'http://openweathermap.org/find' ) . '">' . esc_html__( 'Find your location', 'soledad' ) . '</a>' ); ?></a></span>
                       
            </p>

            <!-- Location Show -->
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'penci_location_show' ) ); ?>"><?php esc_html_e( 'Custom Location Name Display:', 'soledad' ); ?></label>
                <input type="text" class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'penci_location_show' ) ); ?>"
                       name="<?php echo esc_attr( $this->get_field_name( 'penci_location_show' ) ); ?>"
                       value="<?php echo $instance_penci_location_show; ?>"/><span class="description"><?php echo esc_html__( 'If the option is empty,will display results from ', 'soledad' ) . '<a href="' . esc_url( 'http://openweathermap.org/find' ) . '">openweathermap.org</a>';?></span>
            </p>

            <!-- User Locations -->
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'penci_user_loc' ) ); ?>"><?php esc_html_e( 'Enable User Location:', 'soledad' ); ?></label>
                <select id="<?php echo esc_attr( $this->get_field_id( 'penci_user_loc' ) ); ?>"
                        name="<?php echo esc_attr( $this->get_field_name( 'penci_user_loc' ) ); ?>" class="widefat penci_user_loc"
                        style="width:100%;">
                    <option value='disable' <?php if ( 'disable' == $instance['penci_user_loc'] ) { echo 'selected="selected"';} ?>><?php esc_html_e( 'Disable', 'soledad' ); ?></option>
                    <option value='enable' <?php if ( 'enable' == $instance['penci_user_loc'] ) { echo 'selected="selected"';} ?>><?php esc_html_e( 'Enable', 'soledad' ); ?></option>
                </select>
            </p>

            <!-- Save User Locations -->
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'penci_save_user_loc' ) ); ?>"><?php esc_html_e( 'Use Cookies to Save Weather Data When Users Access it Based on Their Location?', 'soledad' ); ?></label>
                <select id="<?php echo esc_attr( $this->get_field_id( 'penci_save_user_loc' ) ); ?>"
                        name="<?php echo esc_attr( $this->get_field_name( 'penci_save_user_loc' ) ); ?>" class="widefat penci_save_user_loc"
                        style="width:100%;">
                    <option value='disable' <?php if ( 'disable' == $instance['penci_save_user_loc'] ) { echo 'selected="selected"';} ?>><?php esc_html_e( 'Disable', 'soledad' ); ?></option>
                    <option value='enable' <?php if ( 'enable' == $instance['penci_save_user_loc'] ) { echo 'selected="selected"';} ?>><?php esc_html_e( 'Enable', 'soledad' ); ?></option>
                </select>
                <span class="description"><?php echo esc_html__( 'This option only applies when you enable the user\'s location above.', 'soledad' );?></span>
            </p>

            <!-- Units -->
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'units' ) ); ?>"><?php esc_html_e( 'Units:', 'soledad' ); ?></label>
                <select id="<?php echo esc_attr( $this->get_field_id( 'units' ) ); ?>"
                        name="<?php echo esc_attr( $this->get_field_name( 'units' ) ); ?>" class="widefat units"
                        style="width:100%;">
                    <option value='imperial' <?php if ( 'imperial' == $instance['units'] ) {
						echo 'selected="selected"';
					} ?>><?php esc_html_e( 'F', 'soledad' ); ?></option>
                    <option value='metric' <?php if ( 'metric' == $instance['units'] ) {
						echo 'selected="selected"';
					} ?>><?php esc_html_e( 'C', 'soledad' ); ?></option>
                </select>
            </p>

            <!-- Units -->
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'penci_forcast' ) ); ?>"><?php esc_html_e( 'Forcast:', 'soledad' ); ?></label>
                <select id="<?php echo esc_attr( $this->get_field_id( 'penci_forcast' ) ); ?>"
                        name="<?php echo esc_attr( $this->get_field_name( 'penci_forcast' ) ); ?>" class="widefat penci_forcast"
                        style="width:100%;">
                    <option value='1' <?php if ( '1' == $instance['penci_forcast'] ) { echo 'selected="selected"';} ?>><?php esc_html_e( '1 Day', 'soledad' ); ?></option>
                    <option value='2' <?php if ( '2' == $instance['penci_forcast'] ) { echo 'selected="selected"';} ?>><?php esc_html_e( '2 Days', 'soledad' ); ?></option>
                    <option value='3' <?php if ( '3' == $instance['penci_forcast'] ) { echo 'selected="selected"';} ?>><?php esc_html_e( '3 Days', 'soledad' ); ?></option>
                    <option value='4' <?php if ( '4' == $instance['penci_forcast'] ) { echo 'selected="selected"';} ?>><?php esc_html_e( '4 Days', 'soledad' ); ?></option>
                    <option value='5' <?php if ( '5' == $instance['penci_forcast'] ) { echo 'selected="selected"';} ?>><?php esc_html_e( '5 Days', 'soledad' ); ?></option>
                </select>
            </p>

			<?php
		}
	}
}
?>
