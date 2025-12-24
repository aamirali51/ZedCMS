<?php

add_action( 'widgets_init', 'penci_tiktok_embed_load_widget' );

function penci_tiktok_embed_load_widget() {
	register_widget( 'penci_tiktok_embed_widget' );
}

if ( ! class_exists( 'penci_tiktok_embed_widget' ) ) {
	class penci_tiktok_embed_widget extends WP_Widget {

		/**
		 * Widget setup.
		 */
		function __construct() {
			/* Widget settings. */
			$widget_ops = array(
				'classname'   => 'penci_tiktok_embed_widget',
				'description' => esc_html__( 'A widget that displays the user TikTok embed iframe.', 'soledad' )
			);

			/* Widget control settings. */
			$control_ops = array( 'width' => 250, 'height' => 350, 'id_base' => 'penci_tiktok_embed_widget' );

			/* Create the widget. */ global $wp_version;
			if ( 4.3 > $wp_version ) {
				$this->WP_Widget( 'penci_tiktok_embed_widget', penci_get_theme_name( '.Soledad', true ) . esc_html__( 'Penci TikTok Feed', 'soledad' ), $widget_ops, $control_ops );
			} else {
				parent::__construct( 'penci_tiktok_embed_widget', penci_get_theme_name( '.Soledad', true ) . esc_html__( 'Penci TikTok Feed', 'soledad' ), $widget_ops, $control_ops );
			}
		}

		/**
		 * How to display the widget on the screen.
		 */
		function widget( $args, $instance ) {
			extract( $args );

			/* Our variables from the widget settings. */
			$title    = isset( $instance['title'] ) ? $instance['title'] : '';
			$title    = apply_filters( 'widget_title', $title );
			$username = isset( $instance['username'] ) ? $instance['username'] : '';
			$width    = isset( $instance['width'] ) ? $instance['width'] : 280;


			/* Before widget (defined by themes). */
			echo ent2ncr( $before_widget );

			/* Display the widget title if one was input (before and after defined by themes). */
			if ( $title ) {
				echo ent2ncr( $before_title ) . $title . ent2ncr( $after_title );
			}

			if ( $username ) {

				?>

                <blockquote class="tiktok-embed" cite="https://www.tiktok.com/@<?php echo esc_attr( $username ); ?>"
                            data-unique-id="<?php echo esc_attr( $username ); ?>"
                            data-embed-type="creator"
                            style="width: 100%; max-width: <?php echo esc_attr( $width ); ?>px;">
                    <section><a target="_blank"
                                href="https://www.tiktok.com/@<?php echo esc_attr( $username ); ?>">@<?php echo esc_attr( $username ); ?></a>
                    </section>
                </blockquote>

				<?php

				wp_enqueue_script( 'penci_tiktok_embed' );
			} else {
				_e( 'Please enter Tiktok username', 'soledad' );
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

			return $instance;
		}

		public function soledad_widget_defaults() {
			return array(
				'title'    => esc_html__( 'TikTok Feed', 'soledad' ),
				'username' => '',
				'width'    => 280,
			);
		}


		function form( $instance ) {

			/* Set up some default widget settings. */
			$defaults       = $this->soledad_widget_defaults();
			$instance       = wp_parse_args( (array) $instance, $defaults );
			$instance_title = $instance['title'] ? str_replace( '"', '&quot;', $instance['title'] ) : '';
			?>

            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title', 'soledad' ) ?></label>
                <input id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"
                       name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>"
                       value="<?php echo esc_attr( $instance_title ); ?>" class="widefat" type="text"/>
            </p>

            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'username' ) ); ?>"><?php esc_html_e( 'Tiktok Username ( Without @ )', 'soledad' ) ?></label>
                <input id="<?php echo esc_attr( $this->get_field_id( 'username' ) ); ?>"
                       name="<?php echo esc_attr( $this->get_field_name( 'username' ) ); ?>"
                       value="<?php echo esc_attr( $instance['username'] ); ?>" class="widefat" type="text"/>
            </p>


            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'width' ) ); ?>"><?php esc_html_e( 'Custom Width (px)', 'soledad' ) ?></label>
                <input id="<?php echo esc_attr( $this->get_field_id( 'width' ) ); ?>"
                       name="<?php echo esc_attr( $this->get_field_name( 'width' ) ); ?>"
                       value="<?php echo esc_attr( $instance['width'] ); ?>" class="widefat" type="number"/>
            </p>

			<?php
		}
	}
}
?>
