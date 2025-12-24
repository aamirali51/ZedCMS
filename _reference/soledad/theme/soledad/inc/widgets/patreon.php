<?php

add_action( 'widgets_init', 'penci_patreon_load_widget' );

function penci_patreon_load_widget() {
	register_widget( 'penci_patreon_widget' );
}

if ( ! class_exists( 'penci_patreon_widget' ) ) {
	class penci_patreon_widget extends WP_Widget {

		/**
		 * Widget setup.
		 */
		function __construct() {
			/* Widget settings. */
			$widget_ops = array(
				'classname'   => 'penci_patreon_widget',
				'description' => esc_html__( 'A widget that displays the Patreon account information.', 'soledad' )
			);

			/* Widget control settings. */
			$control_ops = array( 'width' => 250, 'height' => 350, 'id_base' => 'penci_patreon_widget' );

			/* Create the widget. */ global $wp_version;
			if ( 4.3 > $wp_version ) {
				$this->WP_Widget( 'penci_patreon_widget', penci_get_theme_name( '.Soledad', true ) . esc_html__( 'Paetron', 'soledad' ), $widget_ops, $control_ops );
			} else {
				parent::__construct( 'penci_patreon_widget', penci_get_theme_name( '.Soledad', true ) . esc_html__( 'Paetron', 'soledad' ), $widget_ops, $control_ops );
			}
		}

		/**
		 * How to display the widget on the screen.
		 */
		function widget( $args, $instance ) {
			extract( $args );

			/* Our variables from the widget settings. */
			$title       = isset( $instance['title'] ) ? $instance['title'] : '';
			$title       = apply_filters( 'widget_title', $title );
			$button_text = ! empty( $instance['button_text'] ) ? $instance['button_text'] : esc_html__( 'Paetron', 'soledad' );
			$username    = ! empty( $instance['username'] ) ? $instance['username'] : '';

			/* Before widget (defined by themes). */
			echo ent2ncr( $before_widget );

			/* Display the widget title if one was input (before and after defined by themes). */
			if ( $title ) {
				echo ent2ncr( $before_title ) . $title . ent2ncr( $after_title );
			}
			?>
            <div class="penci-patreon-badge-wrap">

                <a href="https://www.patreon.com/<?php echo $username ?>" rel="external noopener nofollow"
                   target="_blank">
                    <svg width="569px" height="546px" viewBox="0 0 569 546"
                         xmlns="http://www.w3.org/2000/svg"><title><?php echo esc_html( $button_text ) ?></title>
                        <g>
                            <circle data-color="1" id="Oval" cx="362.589996" cy="204.589996" r="204.589996"></circle>
                            <rect data-color="2" id="Rectangle" x="0" y="0" width="100" height="545.799988"></rect>
                        </g>
                    </svg>
                </a>

				<?php
				if ( ! empty( $instance['secondary_text'] ) ) {
					echo '<h4>' . $instance['secondary_text'] . '</h4>';
				}
				?>

                <a href="https://www.patreon.com/<?php echo $username ?>" rel="external noopener nofollow"
                   target="_blank" class="button">
                    <span><?php echo esc_html( $button_text ) ?></span>
                </a>
            </div>
			<?php

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

			return $instance;
		}

		public function soledad_widget_defaults() {
			return array(
				'title'          => esc_html__( 'Buy Me a Coffee', 'soledad' ),
				'button_text'    => '',
				'secondary_text' => '',
				'username'       => '',
			);
		}


		function form( $instance ) {

			/* Set up some default widget settings. */
			$defaults       = $this->soledad_widget_defaults();
			$instance       = wp_parse_args( (array) $instance, $defaults );
			$title          = $instance['title'] ? str_replace( '"', '&quot;', $instance['title'] ) : '';
			$username       = isset( $instance['username'] ) ? $instance['username'] : '';
			$button_text    = isset( $instance['button_text'] ) ? $instance['button_text'] : '';
			$secondary_text = isset( $instance['secondary_text'] ) ? $instance['secondary_text'] : '';
			?>

            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title', 'soledad' ) ?></label>
                <input id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"
                       name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>"
                       value="<?php echo esc_attr( $title ); ?>" class="widefat" type="text"/>
            </p>
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'username' ) ); ?>"><?php esc_html_e( 'Username', 'soledad' ) ?></label>
                <input id="<?php echo esc_attr( $this->get_field_id( 'username' ) ); ?>"
                       name="<?php echo esc_attr( $this->get_field_name( 'username' ) ); ?>"
                       value="<?php echo esc_attr( $username ); ?>" class="widefat" type="text"/>
            </p>
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'button_text' ) ); ?>"><?php esc_html_e( 'Button Text', 'soledad' ) ?></label>
                <input id="<?php echo esc_attr( $this->get_field_id( 'button_text' ) ); ?>"
                       name="<?php echo esc_attr( $this->get_field_name( 'button_text' ) ); ?>"
                       value="<?php echo esc_attr( $button_text ); ?>" class="widefat" type="text"/>
            </p>
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'secondary_text' ) ); ?>"><?php esc_html_e( 'Secondary Text', 'soledad' ) ?></label>
                <input id="<?php echo esc_attr( $this->get_field_id( 'secondary_text' ) ); ?>"
                       name="<?php echo esc_attr( $this->get_field_name( 'secondary_text' ) ); ?>"
                       value="<?php echo esc_attr( $secondary_text ); ?>" class="widefat" type="text"/>
            </p>

			<?php
		}
	}
}
?>
