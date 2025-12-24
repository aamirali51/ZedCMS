<?php
/**
 * Facebook page box widget
 * Display facebook page box on your website
 *
 * @package Wordpress
 * @since 1.0
 */

add_action( 'widgets_init', 'penci_facebook_load_widget' );

function penci_facebook_load_widget() {
	register_widget( 'penci_facebook_widget' );
}

if ( ! class_exists( 'penci_facebook_widget' ) ) {
	class penci_facebook_widget extends WP_Widget {

		/**
		 * Widget setup.
		 */
		function __construct() {
			/* Widget settings. */
			$widget_ops = array(
				'classname'   => 'penci_facebook_widget',
				'description' => esc_html__( 'A widget will displays a Facebook Page', 'soledad' )
			);

			/* Widget control settings. */
			$control_ops = array( 'id_base' => 'penci_facebook_widget' );

			/* Create the widget. */
			global $wp_version;
			if ( 4.3 > $wp_version ) {
				$this->WP_Widget( 'penci_facebook_widget', penci_get_theme_name('.Soledad',true).esc_html__( 'Facebook Page Box', 'soledad' ), $widget_ops, $control_ops );
			} else {
				parent::__construct( 'penci_facebook_widget', penci_get_theme_name('.Soledad',true).esc_html__( 'Facebook Page Box', 'soledad' ), $widget_ops, $control_ops );
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
			$page_url = isset( $instance['page_url'] ) ? $instance['page_url'] : '';
			$height   = isset( $instance['height'] ) ? $instance['height'] : '';
			$faces    = isset( $instance['faces'] ) ? $instance['faces'] : '';
			$stream   = isset( $instance['stream'] ) ? $instance['stream'] : '';
			$cover    = isset( $instance['cover'] ) ? $instance['cover'] : '';
			$language = isset( $instance['language'] ) ? $instance['language'] : '';


			/* Before widget (defined by themes). */
			echo ent2ncr( $before_widget );

			/* Display the widget title if one was input (before and after defined by themes). */
			if ( $title ) {
				echo ent2ncr( $before_title ) . $title . ent2ncr( $after_title );
			}
			$lang = $language ? $language : get_locale();
			if ( $page_url ) {
				?>
                <div id="fb-root"></div>
                <script data-cfasync="false">(function (d, s, id) {
                        var js, fjs = d.getElementsByTagName(s)[0];
                        if (d.getElementById(id)) return;
                        js = d.createElement(s);
                        js.id = id;
                        js.src = "//connect.facebook.net/<?php echo esc_attr( $lang )?>/sdk.js#xfbml=1&version=v9.0";
                        fjs.parentNode.insertBefore(js, fjs);
                    }(document, 'script', 'facebook-jssdk'));</script>
                <div class="fb-page"
                     data-href="<?php echo esc_url( $page_url ); ?>"<?php if ( $stream && is_numeric( $height ) && $height > 69 ): ?> data-height="<?php echo absint( $height ); ?>"<?php endif; ?>
                     data-small-header="false" data-hide-cover="<?php if ( $cover ) {
					echo 'true';
				} else {
					echo 'false';
				} ?>" data-show-facepile="<?php if ( $faces ) {
					echo 'true';
				} else {
					echo 'false';
				} ?>" data-show-posts="<?php if ( $stream ) {
					echo 'true';
				} else {
					echo 'false';
				} ?>" data-adapt-container-width="true">
                    <div class="fb-xfbml-parse-ignore"><a
                                href="<?php echo esc_attr( $page_url ); ?>"><?php if ( $title ) {
								echo sanitize_text_field( $title );
							} else {
								echo 'Facebook';
							} ?></a></div>
                </div>
				<?php
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
			$defaults = array( 'title'    => 'Follow Me',
			                   'cover'    => '',
			                   'language' => '',
			                   'faces'    => 'on',
			                   'page_url' => '',
			                   'height'   => '290',
			                   'stream'   => true
			);

			return $defaults;
		}


		function form( $instance ) {

			/* Set up some default widget settings. */
			$defaults = $this->soledad_widget_defaults();
			$instance = wp_parse_args( (array) $instance, $defaults );

			$instance_title = $instance['title'] ? str_replace( '"', '&quot;', $instance['title'] ) : '';
			?>

            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'soledad' ); ?></label>
                <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"
                       name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>"
                       value="<?php echo $instance_title; ?>"/>
            </p>

            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'page_url' ) ); ?>"><?php esc_html_e( 'Facebook Page URL:', 'soledad' ); ?></label>
                <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'page_url' ) ); ?>"
                       name="<?php echo esc_attr( $this->get_field_name( 'page_url' ) ); ?>"
                       placeholder="EG. http://www.facebook.com/demo"
                       value="<?php echo esc_attr( $instance['page_url'] ); ?>"/>
            </p>

            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'height' ) ); ?>"><?php esc_html_e( 'Facebook Page Height:', 'soledad' ); ?></label>
                <small style="display: block; clear: both; padding-left: 0;"
                       class="description"><?php esc_html_e( 'This option is only applied when "Show Stream" option is checked', 'soledad' ); ?></small>
                <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'height' ) ); ?>"
                       name="<?php echo esc_attr( $this->get_field_name( 'height' ) ); ?>"
                       placeholder="min is 70. EG: 400" value="<?php echo absint( $instance['height'] ); ?>"/>
            </p>

            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'cover' ) ); ?>"><?php esc_html_e( 'Hide Cover Photo?', 'soledad' ); ?></label>
                <input type="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'cover' ) ); ?>"
                       name="<?php echo esc_attr( $this->get_field_name( 'cover' ) ); ?>" <?php checked( (bool) $instance['cover'], true ); ?> />
            </p>

            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'faces' ) ); ?>"><?php esc_html_e( 'Show Faces:', 'soledad' ); ?></label>
                <input type="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'faces' ) ); ?>"
                       name="<?php echo esc_attr( $this->get_field_name( 'faces' ) ); ?>" <?php checked( (bool) $instance['faces'], true ); ?> />
            </p>

            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'stream' ) ); ?>"><?php esc_html_e( 'Show Stream:', 'soledad' ); ?></label>
                <input type="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'stream' ) ); ?>"
                       name="<?php echo esc_attr( $this->get_field_name( 'stream' ) ); ?>" <?php checked( (bool) $instance['stream'], true ); ?> />
            </p>

            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'language' ) ); ?>"><?php esc_html_e( 'Custom Language:', 'soledad' ); ?></label>
                <small style="display: block; clear: both; padding-left: 0;"
                       class="description"><?php _e( 'Fill the language code to use on Facebook Page Box here. By default, the language will follow the site language. See more <a href="https://developers.facebook.com/docs/internationalization/" target="_blank">here</a>', 'soledad' ); ?></small>
                <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'language' ) ); ?>"
                       name="<?php echo esc_attr( $this->get_field_name( 'language' ) ); ?>" placeholder="EG. de_DE"
                       value="<?php echo esc_attr( $instance['language'] ); ?>"/>
            </p>

			<?php
		}
	}
}

?>
