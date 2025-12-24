<?php
/**
 * Main class for render pinterest pinboard
 * Render HTML in your sidebar on front-end
 *
 * @since 1.0
 */

if ( ! class_exists( 'Penci_Pinterest' ) ):

	class Penci_Pinterest {

		// Pinterest url
		var $pinterest_feed = 'https://pinterest.com/%s/feed.rss';

		var $start_time;

		function __construct() {
			$this->start_time = microtime( true );
		}

		// Render the pinboard and output
		function render_html( $username, $numbers, $cache_time = 1200, $follow = true ) {
			$user_display_html = $username;
			$pins = $this->get_board_name_pins( $username, $cache_time );
			$user_display = explode( "/", trim( $username ) );
			if( is_array( $user_display ) ) {
				$user_display_html = $user_display[0];
			}
			
			if( ! empty( $pins ) ) {
				echo '<div class="penci-images-pin-widget">';
				$i = 1;
				foreach ( $pins as $pin ) {
					if ( $numbers < $i ) {
						continue;
					}
					
					$image = isset( $pin['images']['orig']['url'] ) ? $pin['images']['orig']['url'] : '';
					if ( ! $image ) {
						continue;
					}

					$pin_id = isset( $pin['id'] ) ? $pin['id'] : '';
					$url    = 'https://www.pinterest.com/pin/' . $pin_id;
					
					$lazyhtml = '<span class="penci-image-holder rectangle-fix-size penci-lazy" data-bgset="' . esc_url( $image ) . '"></span>';
					if( get_theme_mod( 'penci_disable_lazyload_layout' ) ) {
						$lazyhtml = '<span class="penci-image-holder rectangle-fix-size" style="background-image: url(' . esc_url( $image ) . ');"></span>';
					}

					echo '<a href="' . esc_url( $url ) . '" aria-label="Pinterest URL" rel="noopener" target="_blank">'. $lazyhtml .'</a>';
					
					$i++;
				}

				echo '</div>';
			}else {
				$user_display = explode( "/", trim( $username ) );
				if( is_array( $user_display ) ) {
					$user_display_html = $user_display[0];
					if( isset($user_display[1]) && $user_display[1] ){
					    $user_board =  $user_display[1];
					}
				}

				 if( isset( $user_display_html ) && $user_display_html && isset( $user_board ) && $user_board ){
    			    $feedurl = 'http://pinterest.com/'.$user_display_html.'/'.$user_board.'.rss';
    			}
    			else{
    		        $feedurl = 'http://pinterest.com/'.$user_display_html.'/feed.rss';
    			}

				$cache_key = 'penci_pinterest_feed_' . strtolower( $username ) . esc_attr( $numbers );
				$item_foreach_cache = get_transient( $cache_key );
				
				$remove_item_foreach_cache = isset( $_GET['penci_cache_feed'] ) ? true : false;

				$item_foreach_pre = array();
				if ( ! $item_foreach_cache || $remove_item_foreach_cache ) {
					$rss = fetch_feed($feedurl);
					if ( is_wp_error( $rss ) ){
						if( current_user_can('manage_options') ){
							echo '<p style="text-align: center;">This message appears for Admin Users only:<br>Pinterest data return errors. Please check again your settings.</p>';
							return null;
						} else {
							return null;
						}
					}
					
                	$item_foreach = ! empty( $rss->get_items() ) ? $rss->get_items() : array();
					
					if( ! empty( $item_foreach ) ){
						$i = 1;
						foreach ( $item_foreach as $item ) {
							if ( $numbers < $i ) {
								continue;
							}
							$imagedata = $item->get_content();
							
							preg_match( '/src="([^\"]*)"/i', $imagedata, $matchessrc ) ;
							preg_match( '/href="([^\"]*)"/i', $imagedata, $matcheshref ) ;
							
							$item_foreach_pre[] = array(
								'image_url' => isset( $matchessrc[1] ) ? $matchessrc[1] : '',
								'url' => isset( $matcheshref[1] ) ? $matcheshref[1] : ''
							);

							$i++;
						}
					}
					if( ! empty( $item_foreach_pre ) ){  
						set_transient( $cache_key,$item_foreach_pre,  $cache_time );
					}
				}else {

					$item_foreach_pre = $item_foreach_cache;
				}

                if( ! empty( $item_foreach_pre ) ){    
				
    				echo '<div class="penci-images-pin-widget">';
    				$i = 1;
    				foreach ($item_foreach_pre as $item ) {
    					if ( $numbers < $i ) {
    						continue;
    					}
    				
    					$image_url = isset( $item['image_url'] ) ? $item['image_url'] : '';
    					$url =isset( $item['url'] ) ? $item['url'] : '';
						
						$lazyhtml = '<span class="penci-image-holder rectangle-fix-size penci-lazy" data-bgset="' . esc_url( $image_url ) . '"></span>';
						if( get_theme_mod( 'penci_disable_lazyload_layout' ) ) {
							$lazyhtml = '<span class="penci-image-holder rectangle-fix-size" style="background-image: url(' . esc_url( $image_url ) . ');"></span>';
						}

						echo '<a href="' . esc_url( $url ) . '" aria-label="Pinterest URL" rel="noopener" target="_blank">'. $lazyhtml .'</a>';
    					$i++;
    				}
					echo '</div>';
				}else{
					if( current_user_can('manage_options') ){
						echo '<p style="text-align: center;">This message appears for Admin Users only:<br>Render failed - no data is received, please check again the username</p>';
					} else {
						echo '';
					}
					return null;
				}			
			} 
			?>
			
			<?php if ( $follow ): ?>
				<div class="pin_link">
					<a href="http://pinterest.com/<?php echo sanitize_text_field( $user_display_html ); ?>/" aria-label="Follow on Pinterest" rel="noopener" target="_blank">@<?php echo sanitize_text_field( $user_display_html ); ?></a>
				</div>
			<?php endif; ?>
		<?php
		}

		/**
		 * Retrieve RSS feed for username, and parse the data needed.
		 * Returns null if error, otherwise a has of pins.
		 * Callback it on render_html functions
		 *
		 * @since 1.0
		 */
		function get_pins( $username, $numbers, $cache_time = 1200 ) {

			if( !is_numeric( $cache_time ) && $cache_time < 1 ): $cache_time = 1200; endif;
			// Set caching.
			add_filter( 'wp_feed_cache_transient_lifetime', create_function( '$a', 'return ' . $cache_time . ';' ) );

			// Get the RSS feed.
			$url = sprintf( $this->pinterest_feed, $username );
			$rss = fetch_feed( $url );
			if ( is_wp_error( $rss ) ) {
				return null;
			}

			$maxitems  = $rss->get_item_quantity( $numbers );
			$rss_items = $rss->get_items( 0, $maxitems );

			$pins = array();
			if ( is_null( $rss_items ) ) {
				$pins = null;
			}
			else {

				// Build patterns to search/replace in the image urls
				$search  = array( '_b.jpg' );
				$replace = array( '_t.jpg' );

				// Make url protocol relative
				array_push( $search, 'https://' );
				array_push( $replace, '//' );

				$pins = array();
				foreach ( $rss_items as $item ) {
					$title       = $item->get_title();
					$description = $item->get_description();
					$url         = $item->get_permalink();
					if ( preg_match_all( '/<img src="([^"]*)".*>/i', $description, $matches ) ) {
						$image = str_replace( $search, $replace, $matches[1][0] );
					}
					array_push( $pins, array(
						'title' => $title,
						'image' => $image,
						'url'   => $url
					) );
				}
			}

			return $pins;
		}
		
		public function get_board_name_pins( $username, $cache_time = 1200 ) {

			$output = array();

			$cache_key = 'penci_pinterest_' . strtolower( $username );

			$pinterest_cache = get_transient( $cache_key );

			if ( ! $pinterest_cache ) {

				$params = array(
					'timeout'    => 60,
					'sslverify'  => false,
					'headers'    => array( 'Accept-language' => 'en' ),
					'user-agent' => 'Mozilla/5.0 (Windows NT 6.3; WOW64; rv:35.0) Gecko/20100101 Firefox/35.0'
				);

				$response = wp_remote_get( 'https://www.pinterest.com/' . $username, $params );

				if ( ! is_wp_error( $response ) ) {
					$request_result = wp_remote_retrieve_body( $response );

					preg_match_all( '/jsInit1\'>(.*)<\/script>/', $request_result, $matches );

					if ( ! empty( $matches[1] ) && count( $matches[1] ) ) {
						$pinterest_json = json_decode( $matches[1][0], true );

						if ( ! isset( $pinterest_json['resourceDataCache'][1]['data']['board_feed'] ) ) {
							$output['error'] = esc_html__( 'The pinterest data is not set, please check the ID', 'soledad' );
						} elseif ( isset( $pinterest_json["resourceDataCache"][0]['data']['type'] ) && $pinterest_json["resourceDataCache"][0]['data']['type'] !== 'board' ) {
							$output['error'] = esc_html__( 'Invalid pinterest data for  <strong>' . $username . '</strong> please check the <em>user/board_id</em>', 'soledad' );
						} else {
							$output = (array) $pinterest_json['resourceDataCache'][1]['data']['board_feed'];
						}
					}
					
					set_transient( $cache_key, $output,  $cache_time );
				}
			} else {
				$output = $pinterest_cache;
			}
			
			

			return $output;
		}
		
	}

endif; /* End check if class exists */


/**
 * Create a pinterest widget on sidebar
 *
 * @since 1.0
 */

add_action( 'widgets_init', 'penci_register_pinterest_widget' );

function penci_register_pinterest_widget() {
	register_widget( 'Penci_Pinterest_Widget' );
}

if( ! class_exists( 'Penci_Pinterest_Widget' ) ) {
	class Penci_Pinterest_Widget extends WP_Widget {

		function __construct() {
			/* Widget settings. */
			$widget_ops = array(
				'classname'   => 'penci_pinterest_widget',
				'description' => esc_html__( 'A widget that display pinterest widget.', 'soledad' )
			);

			/* Widget control settings. */
			$control_ops = array( 'id_base' => 'penci_pinterest_widget' );

			/* Create the widget. */
			global $wp_version;
			if ( 4.3 > $wp_version ) {
				$this->WP_Widget( 'penci_pinterest_widget', penci_get_theme_name('.Soledad',true).esc_html__( 'Pinterest Widget', 'soledad' ), $widget_ops, $control_ops );
			}
			else {
				parent::__construct( 'penci_pinterest_widget', penci_get_theme_name('.Soledad',true).esc_html__( 'Pinterest Widget', 'soledad' ), $widget_ops, $control_ops );
			}
		}

		function widget( $args, $instance ) {
			extract( $args );

			$title    = isset( $instance['title'] ) ? $instance['title'] : '';
			$title    = apply_filters( 'widget_title', $title );
			$username = isset( $instance['username'] ) ? $instance['username'] : '';
			$numbers  = isset( $instance['numbers'] ) ? $instance['numbers'] : '';
			$follow   = isset( $instance['follow'] ) ? $instance['follow'] : '';
			$cache    = isset( $instance['cache'] ) ? $instance['cache'] : '';

			echo ent2ncr( $before_widget );
			if ( $title ) {
				echo( $before_title . $title . $after_title );
			}
			echo '<div class="penci-pinterest-widget-container">';

			// Render the pinboard from the widget settings.
			$pinboard = new Penci_Pinterest();
			$pinboard->render_html( $username, $numbers, $cache, $follow );

			echo '</div>';

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
			$defaults = array( 'title'    => 'On Pinterest',
			                   'username' => 'username',
			                   'numbers'  => '9',
			                   'cache'    => '1200',
			                   'follow'   => true
			);

			return $defaults;
		}

		function form( $instance ) {
			// load current values or set to default.
			$defaults       = $this->soledad_widget_defaults();
			$instance       = wp_parse_args( (array) $instance, $defaults );
			$instance_title = $instance['title'] ? str_replace( '"', '&quot;', $instance['title'] ) : '';
			?>

            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'soledad' ); ?></label>
                <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"
                       name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text"
                       value="<?php echo $instance_title; ?>"/>
            </p>
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'username' ) ); ?>">Enter the <strong
                            style="color: #ff0000;">username</strong> or <strong style="color: #ff0000;">username/board_name</strong>
                    for load images:</label>
				<p class="description" style="padding: 0; margin-bottom: 13px;">Example if you want to load a board has url <strong style="color: #ff0000;"><a href="https://www.pinterest.com/thefirstmess/animals-cuteness" target="_blank">https://www.pinterest.com/thefirstmess/animals-cuteness</a></strong> You need to fill <strong style="color: #ff0000;">thefirstmess/animals-cuteness</strong></p>
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'username' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'username' ) ); ?>" type="text" value="<?php echo sanitize_text_field( $instance['username'] ); ?>" />
			</p>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'numbers' ) ); ?>"><?php esc_html_e( 'Numbers image to show:', 'soledad' ); ?></label>
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'numbers' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'numbers' ) ); ?>" type="number" value="<?php echo absint( $instance['numbers'] ); ?>" />
			</p>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'cache' ) ); ?>"><?php esc_html_e( 'Cache life time ( unit is seconds ):', 'soledad' ); ?></label>
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'cache' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'cache' ) ); ?>" type="number" value="<?php echo absint( $instance['cache'] ); ?>" />
			</p>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'follow' ) ); ?>"><?php esc_html_e( 'Display more link with username text?:', 'soledad' ); ?></label>
				<input type="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'follow' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'follow' ) ); ?>" <?php checked( (bool) $instance['follow'], true ); ?> />
			</p>
		<?php
		}
	}
}
