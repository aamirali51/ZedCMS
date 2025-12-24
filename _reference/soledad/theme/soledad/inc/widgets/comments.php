<?php

add_action( 'widgets_init', 'penci_comments_load_widget' );

function penci_comments_load_widget() {
	register_widget( 'penci_comments_widget' );
}

if ( ! class_exists( 'penci_comments_widget' ) ) {
	class penci_comments_widget extends WP_Widget {

		/**
		 * Widget setup.
		 */
		function __construct() {
			/* Widget settings. */
			$widget_ops = array(
				'classname'   => 'penci_comments_widget',
				'description' => esc_html__( 'A widget that displays the recent comments with avatar.', 'soledad' )
			);

			/* Widget control settings. */
			$control_ops = array( 'width' => 250, 'height' => 350, 'id_base' => 'penci_comments_widget' );

			/* Create the widget. */ global $wp_version;
			if ( 4.3 > $wp_version ) {
				$this->WP_Widget( 'penci_comments_widget', penci_get_theme_name( '.Soledad', true ) . esc_html__( 'Comments', 'soledad' ), $widget_ops, $control_ops );
			} else {
				parent::__construct( 'penci_comments_widget', penci_get_theme_name( '.Soledad', true ) . esc_html__( 'Comments', 'soledad' ), $widget_ops, $control_ops );
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
			$length      = isset( $instance['length'] ) ? $instance['length'] : 12;
			$avatar_size = isset( $instance['avatar_size'] ) ? $instance['avatar_size'] : 70;

			/* Before widget (defined by themes). */
			echo ent2ncr( $before_widget );

			/* Display the widget title if one was input (before and after defined by themes). */
			if ( $title ) {
				echo ent2ncr( $before_title ) . $title . ent2ncr( $after_title );
			}

			echo '<ul>';

			$comments = get_comments( 'status=approve&number=' . $instance['number'] );

			foreach ( $comments as $comment ) { ?>
                <li>
					<?php

					$no_thumb = 'no-small-thumbs';

					// Show the avatar if it is active only
					if ( get_option( 'show_avatars' ) ) {
						if ( isset( $comment->comment_author_email ) && $comment->comment_author_email ) {
							$usergravatar = 'http://www.gravatar.com/avatar/' . md5( $comment->comment_author_email ) . '?s=' . $instance['avatar_size'];
						} else {
							$usergravatar = get_avatar_url( $comment->user_id );
						}
						$no_thumb = ''; ?>
                        <div class="post-widget-thumbnail"
                             style="flex: 0 0 <?php echo esc_attr( $avatar_size ) ?>px">
                            <a class="author-avatar"
                               href="<?php echo get_permalink( $comment->comment_post_ID ); ?>#comment-<?php echo esc_attr( $comment->comment_ID ); ?>">
                                <img src="<?php echo esc_url( $usergravatar ); ?>"
                                     alt="<?php echo esc_url( $comment->comment_author ); ?>">
                            </a>
                        </div>
						<?php
					}

					?>

                    <div class="comment-body <?php echo esc_attr( $no_thumb ) ?>">
                        <a class="comment-author"
                           href="<?php echo get_permalink( $comment->comment_post_ID ); ?>#comment-<?php echo esc_attr( $comment->comment_ID ); ?>">
							<?php echo strip_tags( $comment->comment_author ); ?>
                        </a>
                        <p><?php
							$comment_content = wp_strip_all_tags( $comment->comment_content );
							echo wp_trim_words( $comment_content, $length ); ?></p>
                    </div>

                </li>
				<?php
			}

			echo '</ul>';

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
				'title'       => esc_html__( 'Recent Comments', 'soledad' ),
				'number'      => 5,
				'avatar_size' => 70,
				'length'      => 12,
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
                       value="<?php echo esc_attr( $instance_title ) ?>" class="widefat" type="text"/>
            </p>

            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>"><?php esc_html_e( 'Number of comments to show:', 'soledad' ) ?></label>
                <input id="<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>"
                       name="<?php echo esc_attr( $this->get_field_name( 'number' ) ); ?>"
                       value="<?php echo esc_attr( $instance['number'] ) ?>" type="number"
                       class="tiny-text"/>
            </p>

            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'length' ) ); ?>"><?php esc_html_e( 'Custom Comment Content Words Length:', 'soledad' ) ?></label>
                <input id="<?php echo esc_attr( $this->get_field_id( 'length' ) ); ?>"
                       name="<?php echo esc_attr( $this->get_field_name( 'length' ) ); ?>"
                       value="<?php echo esc_attr( $instance['length'] ) ?>" type="number"
                       class="tiny-text"/>
            </p>

            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'avatar_size' ) ); ?>"><?php esc_html_e( 'Avatar size (px)', 'soledad' ) ?></label>
                <input id="<?php echo esc_attr( $this->get_field_id( 'avatar_size' ) ); ?>"
                       name="<?php echo esc_attr( $this->get_field_name( 'avatar_size' ) ); ?>"
                       value="<?php echo esc_attr( $instance['avatar_size'] ); ?>" class="widefat" type="number"
                       placeholder=""/>
            </p>
			<?php

		}
	}
}
