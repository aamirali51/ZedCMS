<?php
/**
 * Latest tweets slider widget
 * Display recent post or popular post for each category or all posts
 *
 * @package Wordpress
 * @since 1.2
 */

add_action( 'widgets_init', 'penci_latest_tweets_load_widget' );

function penci_latest_tweets_load_widget() {
	register_widget( 'penci_latest_tweets_widget' );
}

if ( ! class_exists( 'penci_latest_tweets_widget' ) ) {
	class penci_latest_tweets_widget extends WP_Widget {

		/**
		 * Widget setup.
		 */
		function __construct() {
			/* Widget settings. */
			$widget_ops = array(
				'classname'   => 'penci_latest_tweets_widget',
				'description' => esc_html__( 'A widget that displays your latest tweets with a slider', 'soledad' )
			);

			/* Widget control settings. */
			$control_ops = array( 'width' => 250, 'height' => 350, 'id_base' => 'penci_latest_tweets_widget' );

			/* Create the widget. */ global $wp_version;
			if ( 4.3 > $wp_version ) {
				$this->WP_Widget( 'penci_latest_tweets_widget', penci_get_theme_name( '.Soledad', true ) . esc_html__( 'Twitter Feed', 'soledad' ), $widget_ops, $control_ops );
			} else {
				parent::__construct( 'penci_latest_tweets_widget', penci_get_theme_name( '.Soledad', true ) . esc_html__( 'Twitter Feed', 'soledad' ), $widget_ops, $control_ops );
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
			$date     = isset( $instance['date'] ) ? $instance['date'] : false;
			$auto     = isset( $instance['auto'] ) ? $instance['auto'] : false;
			$reply    = isset( $instance['reply'] ) ? $instance['reply'] : esc_html__( 'Reply', 'soledad' );
			$retweet  = isset( $instance['retweet'] ) ? $instance['retweet'] : esc_html__( 'Retweet', 'soledad' );
			$favorite = isset( $instance['favorite'] ) ? $instance['favorite'] : esc_html__( 'Favorite', 'soledad' );
			$align    = isset( $instance['align'] ) ? $instance['align'] : '';
			$style    = isset( $instance['style'] ) ? $instance['style'] : 'slider';
			$number   = isset( $instance['number'] ) ? $instance['number'] : 5;
			$classes  = 'slider' == $style ? 'swiper penci-owl-carousel penci-owl-carousel-slider penci-tweets-slider' : 'penci-tweets-lists';
			if ( function_exists( 'penci_getTweets' ) ) {
				$tweets = penci_getTweets( $number );

				if ( ! empty( $tweets ) ):

					/* Before widget (defined by themes). */ echo ent2ncr( $before_widget );

					/* Display the widget title if one was input (before and after defined by themes). */
					if ( $title ) {
						echo ent2ncr( $before_title ) . $title . ent2ncr( $after_title );
					}

					if ( isset( $tweets['error'] ) ) {
						echo 'Missing consumer key - please check your settings in admin > Settings > Twitter Feed Auth';
					} else {
						$df_align = is_rtl() ? 'right' : 'left';
						?>
                        <div class="penci-tweets-widget-content<?php echo $style == 'slider' ? ' ' . $align : ' pc_align' . $df_align; ?>">
							<?php if ( $style == 'slider' ): ?>
                                <span class="icon-tweets"><?php penci_fawesome_icon( 'penciicon-x-twitter' ); ?></span>
							<?php endif; ?>
                            <div class="<?php echo esc_attr( $classes ); ?>" data-dots="true"
                                 data-nav="false" data-auto="<?php if ( $auto ) {
								echo 'false';
							} else {
								echo 'true';
							} ?>">
								<?php foreach ( $tweets as $tweet ):
									$date_array = explode( ' ', $tweet['created_at'] );
									$tweet_id = $tweet['id_str'];
									$tweet_text = $tweet['text'];
									$urls = $tweet['entities']['urls'];
									$user_id = $tweet['user']['id'];
									if ( isset( $urls ) ) {
										foreach ( $urls as $ul ) {
											$url = $ul['url'];
											if ( isset( $url ) ):
												$tweet_text = str_replace( $url, '<a href="' . $url . '" rel="noopener" target="_blank">' . $url . '</a>', $tweet_text );
											endif;
										}
									}
									?>
                                    <div class="penci-tweet">

										<?php if ( $style == 'list' ):
											$reply = '<i class="fa fa-reply" aria-hidden="true"></i>';
											$retweet = '<i class="fa fa-retweet" aria-hidden="true"></i>';
											$favorite = '<i class="fa fa-thumbs-up" aria-hidden="true"></i>';
											?>

                                            <div class="tweet-list-top">

												<?php if ( $date_array[1] && $date_array[2] && $date_array[5] && ! $date ): ?>
                                                    <span class="tweet-date"><?php echo $date_array[2] . '-' . $date_array[1] . '-' . $date_array[5]; ?></span>
												<?php endif; ?>

                                            </div>

										<?php endif; ?>

                                        <div class="tweet-text">
											<?php echo $tweet_text; ?>
                                        </div>

										<?php if ( $style == 'slider' ): ?>

											<?php if ( $date_array[1] && $date_array[2] && $date_array[5] && ! $date ): ?>
                                                <p class="tweet-date"><?php echo $date_array[2] . '-' . $date_array[1] . '-' . $date_array[5]; ?></p>
											<?php endif; ?>
										<?php endif; ?>
                                        <div class="tweet-intents">
                                            <div class="tweet-intents-inner">
                                            <span><a target="_blank" rel="noopener" class="reply"
                                                     href="https://x.com/intent/tweet?in_reply_to=<?php echo sanitize_text_field( $tweet_id ); ?>"><?php echo do_shortcode( $reply ); ?></a></span>
                                                <span><a target="_blank" rel="noopener" class="retweet"
                                                         href="https://x.com/intent/retweet?tweet_id=<?php echo sanitize_text_field( $tweet_id ); ?>"><?php echo do_shortcode( $retweet ); ?></a></span>
                                                <span><a target="_blank" rel="noopener" class="favorite"
                                                         href="https://x.com/intent/favorite?tweet_id=<?php echo sanitize_text_field( $tweet_id ); ?>"><?php echo do_shortcode( $favorite ); ?></a></span>
                                            </div>
                                        </div>


                                    </div>
								<?php endforeach; ?>
                            </div>
                        </div>

						<?php
					}
				endif; /* End check if array $tweets empty or null */


				/* After widget (defined by themes). */
				echo ent2ncr( $after_widget );
			} else {
				_e( 'Please install the "Penci Social Feed" plugin via Apperance > Install Plugins to get this widget working.', 'soledad' );
			}
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
				'title'    => esc_html__( 'Tweets', 'soledad' ),
				'date'     => false,
				'auto'     => false,
				'reply'    => esc_html__( 'Reply', 'soledad' ),
				'retweet'  => esc_html__( 'Retweet', 'soledad' ),
				'favorite' => esc_html__( 'Favorite', 'soledad' ),
				'align'    => '',
				'number'   => 5,
				'style'    => 'slider'
			);
		}


		function form( $instance ) {

			/* Set up some default widget settings. */
			$defaults = $this->soledad_widget_defaults();
			$instance = wp_parse_args( (array) $instance, $defaults );

			$instance_title = $instance['title'] ? str_replace( '"', '&quot;', $instance['title'] ) : '';
			?>

            <br>
            <p><span style="color: #ff0000;">Note Important:</span> To use this widget you need to connect your
                twitter account <a
                        href="<?php echo admin_url( 'admin.php?page=penci_twitter_token' ); ?>" target="_blank">here</a>.
            </p>

            <!-- Widget Title: Text Input -->
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'soledad' ); ?></label>
                <input type="text" class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"
                       name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>"
                       value="<?php echo $instance_title; ?>"/>
            </p>

            <!-- Style -->
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'style' ) ); ?>">Layout:</label>
                <select id="<?php echo esc_attr( $this->get_field_id( 'style' ) ); ?>"
                        name="<?php echo esc_attr( $this->get_field_name( 'style' ) ); ?>" class="widefat categories"
                        style="width:100%;">
                    <option value='slider' <?php selected( $instance['style'], 'slider' ); ?>>Slider
                    </option>
                    <option value='list' <?php selected( $instance['style'], 'list' ); ?>>List
                    </option>
                </select>
            </p>

            <!-- Align -->
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'align' ) ); ?>">Align This Widget ( for Slider
                    Layout ):</label>
                <select id="<?php echo esc_attr( $this->get_field_id( 'align' ) ); ?>"
                        name="<?php echo esc_attr( $this->get_field_name( 'align' ) ); ?>" class="widefat categories"
                        style="width:100%;">
                    <option value='pc_aligncenter'>Align Center
                    </option>
                    <option value='pc_alignleft' <?php selected( $instance['align'], 'pc_alignleft' ); ?>>Align Left
                    </option>
                    <option value='pc_alignright' <?php selected( $instance['align'], 'pc_alignright' ); ?>>Align Right
                    </option>
                </select>
            </p>

            <!-- Display tweets numner -->
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>"><?php esc_html_e( 'Number of Tweet to Display:', 'soledad' ); ?></label>
                <input type="number" class="widefat"
                       id="<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>"
                       name="<?php echo esc_attr( $this->get_field_name( 'number' ) ); ?>"
                       value="<?php echo $instance['number']; ?>">
            </p>

            <!-- Display tweets date -->
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'date' ) ); ?>"><?php esc_html_e( 'Hide tweets date?:', 'soledad' ); ?></label>
                <input type="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'date' ) ); ?>"
                       name="<?php echo esc_attr( $this->get_field_name( 'date' ) ); ?>" <?php checked( (bool) $instance['date'], true ); ?> />
            </p>

            <!-- Disable auto play -->
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'auto' ) ); ?>"><?php esc_html_e( 'Disable Auto Play Tweets Slider?:', 'soledad' ); ?></label>
                <input type="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'auto' ) ); ?>"
                       name="<?php echo esc_attr( $this->get_field_name( 'auto' ) ); ?>" <?php checked( (bool) $instance['auto'], true ); ?> />
            </p>

            <!-- Custom reply text -->
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'reply' ) ); ?>"><?php esc_html_e( 'Custom Reply text:', 'soledad' ); ?></label>
                <input type="text" class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'reply' ) ); ?>"
                       name="<?php echo esc_attr( $this->get_field_name( 'reply' ) ); ?>"
                       value="<?php echo esc_attr( $instance['reply'] ); ?>"/>
            </p>

            <!-- Custom retweet text -->
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'retweet' ) ); ?>"><?php esc_html_e( 'Custom Retweet text:', 'soledad' ); ?></label>
                <input type="text" class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'retweet' ) ); ?>"
                       name="<?php echo esc_attr( $this->get_field_name( 'retweet' ) ); ?>"
                       value="<?php echo esc_attr( $instance['retweet'] ); ?>"/>
            </p>

            <!-- Custom favorite text -->
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'favorite' ) ); ?>"><?php esc_html_e( 'Custom Favorite text:', 'soledad' ); ?></label>
                <input type="text" class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'favorite' ) ); ?>"
                       name="<?php echo esc_attr( $this->get_field_name( 'favorite' ) ); ?>"
                       value="<?php echo esc_attr( $instance['favorite'] ); ?>"/>
            </p>

			<?php
		}
	}
}
?>
