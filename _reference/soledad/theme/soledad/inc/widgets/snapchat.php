<?php

add_action( 'widgets_init', 'penci_snapchat_load_widget' );

function penci_snapchat_load_widget() {
	register_widget( 'penci_snapchat_widget' );
}

if ( ! class_exists( 'penci_snapchat_widget' ) ) {
	class penci_snapchat_widget extends WP_Widget {

		/**
		 * Widget setup.
		 */
		function __construct() {
			/* Widget settings. */
			$widget_ops = array(
				'classname'   => 'penci_snapchat_widget',
				'description' => esc_html__( 'A widget that displays the Snapchat account information.', 'soledad' )
			);

			/* Widget control settings. */
			$control_ops = array( 'width' => 250, 'height' => 350, 'id_base' => 'penci_snapchat_widget' );

			/* Create the widget. */ global $wp_version;
			if ( 4.3 > $wp_version ) {
				$this->WP_Widget( 'penci_snapchat_widget', penci_get_theme_name( '.Soledad', true ) . esc_html__( 'Snapchat', 'soledad' ), $widget_ops, $control_ops );
			} else {
				parent::__construct( 'penci_snapchat_widget', penci_get_theme_name( '.Soledad', true ) . esc_html__( 'Snapchat', 'soledad' ), $widget_ops, $control_ops );
			}
		}

		/**
		 * How to display the widget on the screen.
		 */
		function widget( $args, $instance ) {
			extract( $args );

			/* Our variables from the widget settings. */
			$title = isset( $instance['title'] ) ? $instance['title'] : '';
			$title = apply_filters( 'widget_title', $title );
			$style = isset( $instance['style'] ) && $instance['style'] ? $instance['style'] : 'rounded';


			/* Before widget (defined by themes). */
			echo ent2ncr( $before_widget );

			/* Display the widget title if one was input (before and after defined by themes). */
			if ( $title ) {
				echo ent2ncr( $before_title ) . $title . ent2ncr( $after_title );
			}

			if ( ! empty( $instance['username'] ) && ! empty( $instance['userid'] ) && ! empty( $instance['avatar'] ) ) {
				$avatar_size  = ! empty( $instance['avatar_size'] ) ? 'background-size:' . $instance['avatar_size'] . 'px;width:' . $instance['avatar_size'] . 'px;height:' . $instance['avatar_size'] . 'px;' : 'background-size:60px;width:60px;height:60px;';
				$class        = ! empty( $instance['style'] ) ? 'is-' . $instance['style'] : 'is-rounded';
				$imgstyle     = ! empty( $instance['bgstyle'] ) ? $instance['bgstyle'] : 'flat';
				$userid       = str_replace( '@', '', $instance['userid'] );
				$bghtmlbg_src = 'data-bgset="' . PENCI_SOLEDAD_URL . '/images/snapchat_' . $imgstyle . '.png"';
				$bghtml_src   = 'data-bgset="' . $instance['avatar'] . '"';

				?>

                <div class="pc-snapchat-wrapper">
                    <a href="https://snapchat.com/add/<?php echo $userid ?>" rel="external noopener nofollow">
						<span class="pc-snapchat-badge <?php echo $class ?>">
                            <span class="pc-snapchat-badge-over penci-image-holder penci-lazy" <?php echo $bghtmlbg_src; ?>></span>
                            <span class="pc-snapchat-avatar penci-image-holder penci-lazy" <?php echo $bghtml_src; ?> style="<?php echo $avatar_size; ?>"></span>
						</span>
                        <span class="pc-snapchat-name"><?php echo $instance['username'] ?></span>
                        <span class="pc-snapchat-id">@<?php echo $userid ?></span>
                    </a>
                </div>
				<?php
			} else {
				echo __( 'Enter the required account info.', 'soledad' );
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
				'title'       => esc_html__( 'Follow Me', 'soledad' ),
				'username'    => '',
				'userid'      => '',
				'avatar'      => '',
				'avatar_size' => '',
				'style'       => '',
				'bgstyle'     => '',
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
                <label for="<?php echo esc_attr( $this->get_field_id( 'username' ) ); ?>"><?php esc_html_e( 'Account Name', 'soledad' ) ?></label>
                <input id="<?php echo esc_attr( $this->get_field_id( 'username' ) ); ?>"
                       name="<?php echo esc_attr( $this->get_field_name( 'username' ) ); ?>"
                       value="<?php echo esc_attr( $instance['username'] ); ?>" class="widefat" type="text"/>
            </p>
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'userid' ) ); ?>"><?php esc_html_e( 'ID', 'soledad' ) ?></label>
                <input id="<?php echo esc_attr( $this->get_field_id( 'userid' ) ); ?>"
                       name="<?php echo esc_attr( $this->get_field_name( 'userid' ) ); ?>"
                       value="<?php echo esc_attr( $instance['userid'] ); ?>" class="widefat" type="text"/>
            </p>
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'avatar' ) ); ?>"><?php esc_html_e( 'Avatar Image URL', 'soledad' ) ?></label>
                <input id="<?php echo esc_attr( $this->get_field_id( 'avatar' ) ); ?>"
                       name="<?php echo esc_attr( $this->get_field_name( 'avatar' ) ); ?>"
                       value="<?php echo esc_attr( $instance['avatar'] ); ?>" class="widefat" type="text"
                       placeholder="https://"/>
                <small>Insert your Avatar Image URL. To get good for load, let use image about 500px width.<br>You can
                    check <a
                            href="https://www.wpbeginner.com/beginners-guide/how-to-get-the-url-of-images-you-upload-in-wordpress/"
                            target="_blank" rel="noreferrer noopener">this guide</a> to know how to get URL of an image
                    you upload.</small>
            </p>
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'avatar_size' ) ); ?>"><?php esc_html_e( 'Avatar size (px)', 'soledad' ) ?></label>
                <input id="<?php echo esc_attr( $this->get_field_id( 'avatar_size' ) ); ?>"
                       name="<?php echo esc_attr( $this->get_field_name( 'avatar_size' ) ); ?>"
                       value="<?php echo esc_attr( $instance['avatar_size'] ); ?>" class="widefat" type="number"
                       placeholder=""/>
            </p>
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'style' ) ); ?>"><?php esc_html_e( 'Style', 'soledad' ) ?></label>
                <select id="<?php echo esc_attr( $this->get_field_id( 'style' ) ); ?>"
                        name="<?php echo esc_attr( $this->get_field_name( 'style' ) ); ?>" class="widefat">
                    <option value="rounded" <?php selected( $instance['style'], 'rounded' ); ?>><?php esc_html_e( 'Rounded', 'soledad' ) ?></option>
                    <option value="circle" <?php selected( $instance['style'], 'circle' ); ?>><?php esc_html_e( 'Circle', 'soledad' ) ?></option>
                </select>
            </p>
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'bgstyle' ) ); ?>"><?php esc_html_e( 'Background Style', 'soledad' ) ?></label>
                <select id="<?php echo esc_attr( $this->get_field_id( 'bgstyle' ) ); ?>"
                        name="<?php echo esc_attr( $this->get_field_name( 'bgstyle' ) ); ?>" class="widefat">
                    <option value="flat" <?php selected( $instance['bgstyle'], 'flat' ); ?>><?php esc_html_e( 'Flat', 'soledad' ) ?></option>
                    <option value="dots" <?php selected( $instance['bgstyle'], 'dots' ); ?>><?php esc_html_e( 'Dots', 'soledad' ) ?></option>
                </select>
            </p>

			<?php
		}
	}
}
?>
