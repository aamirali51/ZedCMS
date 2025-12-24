<?php
/**
 * Socials Widget
 * Get in touch with your clients
 *
 * @package WordPress
 * @since 1.0
 */

add_action( 'widgets_init', 'penci_social_load_widget' );

function penci_social_load_widget() {
	register_widget( 'penci_social_widget' );
}

if ( ! class_exists( 'penci_social_widget' ) ) {
	class penci_social_widget extends WP_Widget {

		/**
		 * Widget setup.
		 */
		function __construct() {
			/* Widget settings. */
			$widget_ops = array(
				'classname'   => 'penci_social_widget',
				'description' => esc_html__( 'A widget that displays your social icons', 'soledad' ),
			);

			/* Widget control settings. */
			$control_ops = array( 'id_base' => 'penci_social_widget' );

			/* Create the widget. */
			global $wp_version;
			if ( 4.3 > $wp_version ) {
				$this->WP_Widget( 'penci_social_widget', penci_get_theme_name( '.Soledad', true ) . esc_html__( 'Social Media', 'soledad' ), $widget_ops, $control_ops );
			} else {
				parent::__construct( 'penci_social_widget', penci_get_theme_name( '.Soledad', true ) . esc_html__( 'Social Media', 'soledad' ), $widget_ops, $control_ops );
			}
		}

		/**
		 * How to display the widget on the screen.
		 */
		function widget( $args, $instance ) {
			extract( $args );

			/* Our variables from the widget settings. */
			$title         = isset( $instance['title'] ) ? $instance['title'] : '';
			$title         = apply_filters( 'widget_title', $title );
			$align         = isset( $instance['align'] ) ? $instance['align'] : '';
			$text          = isset( $instance['text'] ) ? $instance['text'] : '';
			$circle        = isset( $instance['circle'] ) ? $instance['circle'] : false;
			$border_radius = isset( $instance['border_radius'] ) ? $instance['border_radius'] : false;
			$brand_color   = isset( $instance['brand_color'] ) ? $instance['brand_color'] : false;
			$size_icon     = isset( $instance['size_icon'] ) ? $instance['size_icon'] : '14';
			$size_upper    = isset( $instance['size_upper'] ) ? $instance['size_upper'] : false;
			$size_text     = isset( $instance['size_text'] ) ? $instance['size_text'] : '13';

			/* Before widget (defined by themes). */
			echo ent2ncr( $before_widget );

			/* Display the widget title if one was input (before and after defined by themes). */
			if ( $title ) {
				echo ent2ncr( $before_title ) . $title . ( $after_title );
			}

			$style_icon     = 'style="font-size: ' . $size_icon . 'px"';
			$style_icon_svg = 'style="width: ' . $size_icon . 'px;height: ' . $size_icon . 'px"';
			$style_text     = 'style="font-size: ' . $size_text . 'px"';
			?>

            <div class="widget-social
			<?php
			if ( $align ) :
				echo ' ' . $align;
			endif;
			?>
			<?php
			if ( $text ) :
				echo ' show-text';
			endif;
			?>

			<?php
			if ( $circle ) :
				echo ' remove-circle';
			endif;
			?>

			<?php
			if ( $size_upper ) :
				echo ' remove-uppercase-text';
			endif;
			?>

			<?php
			if ( $border_radius ) :
				echo ' remove-border-radius';
			endif;
			?>
			<?php
			if ( $brand_color && ! $circle ) {
				echo ' penci-social-colored';
			} elseif ( $brand_color && $circle ) {
				echo ' penci-social-textcolored';
			}
			?>
			">
				<?php
				$social_data         = penci_social_media_array();
				$custom_social_icons = get_option( 'penci_custom_socials', array() );
				foreach ( $social_data as $name => $sdata ) {
					$showing = isset( $instance[ $name ] ) ? $instance[ $name ] : '';
					if ( $showing ) {
						$icon_html = penci_icon_by_ver( $sdata[1], $style_icon );
						$name      = isset( $custom_social_icons[ $name ]['name'] ) ? $custom_social_icons[ $name ]['name'] : $name;
						?>
                        <a href="<?php echo esc_url( do_shortcode( $sdata[0] ) ); ?>"
                           aria-label="<?php echo ucwords( $name ); ?>" <?php echo penci_reltag_social_icons(); ?>
                           target="_blank"><?php echo $icon_html; ?>
                            <span <?php echo $style_text; ?>><?php echo ucwords( $name ); ?></span></a>
						<?php
					}
				}
				?>
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
			$defaults = array(
				'title'          => 'Keep in touch',
				'text'           => false,
				'align'          => '',
				'circle'         => false,
				'border_radius'  => false,
				'brand_color'    => false,
				'size_icon'      => '14',
				'size_upper'     => false,
				'size_text'      => '13',
				'facebook'       => 'on',
				'twitter'        => 'on',
				'instagram'      => 'on',
				'linkedin'       => '',
				'behance'        => '',
				'flickr'         => '',
				'youtube'        => '',
				'tumblr'         => '',
				'pinterest'      => 'on',
				'email'          => '',
				'vk'             => '',
				'bloglovin'      => '',
				'vine'           => '',
				'soundcloud'     => '',
				'snapchat'       => '',
				'spotify'        => '',
				'github'         => '',
				'stack-overflow' => '',
				'twitch'         => '',
				'vimeo'          => '',
				'steam'          => '',
				'xing'           => '',
				'whatsapp'       => '',
				'telegram'       => '',
				'reddit'         => '',
				'ok'             => '',
				'500px'          => '',
				'wechat'         => '',
				'weibo'          => '',
				'stumbleupon'    => '',
				'line'           => '',
				'viber'          => '',
				'discord'        => '',
				'rss'            => '',
				'slack'          => '',
				'mixcloud'       => '',
				'goodreads'      => '',
				'tripadvisor'    => '',
				'tiktok'         => '',
				'dailymotion'    => '',
				'blogger'        => '',
				'delicious'      => '',
				'deviantart'     => '',
				'digg'           => '',
				'evernote'       => '',
				'forrst'         => '',
				'grooveshark'    => '',
				'lastfm'         => '',
				'myspace'        => '',
				'paypal'         => '',
				'skype'          => '',
				'window'         => '',
				'wordPress'      => '',
				'yahoo'          => '',
				'yandex'         => '',
				'douban'         => '',
				'threads'        => '',
				'yelp'           => '',
				'qq'             => '',
			);

			$socials = get_option( 'penci_custom_socials', array() );

			if ( ! empty( $socials ) ) {
				foreach ( $socials as $slug => $attr ) {
					$defaults[ $slug ] = '';
				}
			}

			return $defaults;
		}

		function form( $instance ) {

			/* Set up some default widget settings. */
			$defaults       = $this->soledad_widget_defaults();
			$instance       = wp_parse_args( (array) $instance, $defaults );
			$instance_title = $instance['title'] ? str_replace( '"', '&quot;', $instance['title'] ) : '';
			?>

            <!-- Widget Title: Text Input -->
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'soledad' ); ?></label>
                <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"
                       name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>"
                       value="<?php echo $instance_title; ?>"/>
            </p>

            <!-- Align -->
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'align' ) ); ?>">Align This Widget:</label>
                <select id="<?php echo esc_attr( $this->get_field_id( 'align' ) ); ?>"
                        name="<?php echo esc_attr( $this->get_field_name( 'align' ) ); ?>" class="widefat categories"
                        style="width:100%;">
                    <option value='pc_aligncenter'
						<?php
						if ( '' == $instance['align'] ) {
							echo 'selected="selected"';
						}
						?>
                    >Align Center
                    </option>
                    <option value='pc_alignleft'
						<?php
						if ( 'pc_alignleft' == $instance['align'] ) {
							echo 'selected="selected"';
						}
						?>
                    >Align Left
                    </option>
                    <option value='pc_alignright'
						<?php
						if ( 'pc_alignright' == $instance['align'] ) {
							echo 'selected="selected"';
						}
						?>
                    >Align Right
                    </option>
                </select>
                <small><?php esc_html_e( 'This option only apply when you hide text on the right side of social icons.', 'soledad' ); ?></small>
            </p>

            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'text' ) ); ?>"><?php esc_html_e( 'Display Social Text on Right Icons?:', 'soledad' ); ?></label>
                <input type="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'text' ) ); ?>"
                       name="<?php echo esc_attr( $this->get_field_name( 'text' ) ); ?>" <?php checked( (bool) $instance['text'], true ); ?> />
            </p>

            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'circle' ) ); ?>"><?php esc_html_e( 'Remove Border Around Icons?:', 'soledad' ); ?></label>
                <input type="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'circle' ) ); ?>"
                       name="<?php echo esc_attr( $this->get_field_name( 'circle' ) ); ?>" <?php checked( (bool) $instance['circle'], true ); ?> />
            </p>

            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'border_radius' ) ); ?>"><?php esc_html_e( 'Remove Border Radius on Border of Icons?:', 'soledad' ); ?></label>
                <input type="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'border_radius' ) ); ?>"
                       name="<?php echo esc_attr( $this->get_field_name( 'border_radius' ) ); ?>" <?php checked( (bool) $instance['border_radius'], true ); ?> />
            </p>

            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'brand_color' ) ); ?>"><?php esc_html_e( 'Use Brand Colors for Social Icons?:', 'soledad' ); ?></label>
                <input type="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'brand_color' ) ); ?>"
                       name="<?php echo esc_attr( $this->get_field_name( 'brand_color' ) ); ?>" <?php checked( (bool) $instance['brand_color'], true ); ?> />
            </p>

            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'size_icon' ) ); ?>"><?php esc_html_e( 'Custom Font Size for Icons:', 'soledad' ); ?></label>
                <input type="number" style="width: 50px;"
                       id="<?php echo esc_attr( $this->get_field_id( 'size_icon' ) ); ?>"
                       name="<?php echo esc_attr( $this->get_field_name( 'size_icon' ) ); ?>"
                       value="<?php echo esc_attr( $instance['size_icon'] ); ?>" size="3"/>
            </p>

            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'size_upper' ) ); ?>"><?php esc_html_e( 'Disable Uppercase Text on Right Icons?:', 'soledad' ); ?></label>
                <input type="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'size_upper' ) ); ?>"
                       name="<?php echo esc_attr( $this->get_field_name( 'size_upper' ) ); ?>" <?php checked( (bool) $instance['size_upper'], true ); ?> />
            </p>

            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'size_text' ) ); ?>"><?php esc_html_e( 'Custom Font Size for Text on Right Icons:', 'soledad' ); ?></label>
                <input type="number" style="width: 50px;"
                       id="<?php echo esc_attr( $this->get_field_id( 'size_text' ) ); ?>"
                       name="<?php echo esc_attr( $this->get_field_name( 'size_text' ) ); ?>"
                       value="<?php echo esc_attr( $instance['size_text'] ); ?>" size="3"/>
            </p>

            <p class="description"
               style="padding: 0;"><?php _e( '<strong>Note:</strong> Setup your social URLs via <strong>Appearance > Customize > Social Media</strong>', 'soledad' ); ?></p>
            <style>.social-flex {
                    display: flex;
                    -ms-flex-wrap: wrap;
                    flex-wrap: wrap;
                }

                .social-flex > p {
                    flex: 0 0 50%;
                    display: block;
                    margin: 0.5em 0;
                }</style>
            <div class="social-flex">
				<?php
				$socials = get_option( 'penci_custom_socials', array() );

				if ( ! empty( $socials ) ) {
					foreach ( $socials as $slug => $attr ) {
						?>
                        <p>
                            <label for="<?php echo esc_attr( $this->get_field_id( $slug ) ); ?>"><?php esc_html_e( 'Show ' . $attr['name'] . ':', 'soledad' ); ?></label>
                            <input type="checkbox" id="<?php echo esc_attr( $this->get_field_id( $slug ) ); ?>"
                                   name="<?php echo esc_attr( $this->get_field_name( $slug ) ); ?>" <?php checked( (bool) $instance[ $slug ], true ); ?> />
                        </p>
						<?php
					}
				}
				?>
                <p>
                    <label for="<?php echo esc_attr( $this->get_field_id( 'facebook' ) ); ?>"><?php esc_html_e( 'Show Facebook:', 'soledad' ); ?></label>
                    <input type="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'facebook' ) ); ?>"
                           name="<?php echo esc_attr( $this->get_field_name( 'facebook' ) ); ?>" <?php checked( (bool) $instance['facebook'], true ); ?> />
                </p>

                <p>
                    <label for="<?php echo esc_attr( $this->get_field_id( 'twitter' ) ); ?>"><?php esc_html_e( 'Show Twitter:', 'soledad' ); ?></label>
                    <input type="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'twitter' ) ); ?>"
                           name="<?php echo esc_attr( $this->get_field_name( 'twitter' ) ); ?>" <?php checked( (bool) $instance['twitter'], true ); ?> />
                </p>

                <p>
                    <label for="<?php echo esc_attr( $this->get_field_id( 'instagram' ) ); ?>"><?php esc_html_e( 'Show Instagram:', 'soledad' ); ?></label>
                    <input type="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'instagram' ) ); ?>"
                           name="<?php echo esc_attr( $this->get_field_name( 'instagram' ) ); ?>" <?php checked( (bool) $instance['instagram'], true ); ?> />
                </p>

                <p>
                    <label for="<?php echo esc_attr( $this->get_field_id( 'pinterest' ) ); ?>"><?php esc_html_e( 'Show Pinterest:', 'soledad' ); ?></label>
                    <input type="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'pinterest' ) ); ?>"
                           name="<?php echo esc_attr( $this->get_field_name( 'pinterest' ) ); ?>" <?php checked( (bool) $instance['pinterest'], true ); ?> />
                </p>

                <p>
                    <label for="<?php echo esc_attr( $this->get_field_id( 'linkedin' ) ); ?>"><?php esc_html_e( 'Show Likedin:', 'soledad' ); ?></label>
                    <input type="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'linkedin' ) ); ?>"
                           name="<?php echo esc_attr( $this->get_field_name( 'linkedin' ) ); ?>" <?php checked( (bool) $instance['linkedin'], true ); ?> />
                </p>

                <p>
                    <label for="<?php echo esc_attr( $this->get_field_id( 'behance' ) ); ?>"><?php esc_html_e( 'Show Behance:', 'soledad' ); ?></label>
                    <input type="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'behance' ) ); ?>"
                           name="<?php echo esc_attr( $this->get_field_name( 'behance' ) ); ?>" <?php checked( (bool) $instance['behance'], true ); ?> />
                </p>

                <p>
                    <label for="<?php echo esc_attr( $this->get_field_id( 'flickr' ) ); ?>"><?php esc_html_e( 'Show Flickr:', 'soledad' ); ?></label>
                    <input type="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'flickr' ) ); ?>"
                           name="<?php echo esc_attr( $this->get_field_name( 'flickr' ) ); ?>" <?php checked( (bool) $instance['flickr'], true ); ?> />
                </p>

                <p>
                    <label for="<?php echo esc_attr( $this->get_field_id( 'tumblr' ) ); ?>"><?php esc_html_e( 'Show Tumblr:', 'soledad' ); ?></label>
                    <input type="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'tumblr' ) ); ?>"
                           name="<?php echo esc_attr( $this->get_field_name( 'tumblr' ) ); ?>" <?php checked( (bool) $instance['tumblr'], true ); ?> />
                </p>

                <p>
                    <label for="<?php echo esc_attr( $this->get_field_id( 'youtube' ) ); ?>"><?php esc_html_e( 'Show Youtube:', 'soledad' ); ?></label>
                    <input type="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'youtube' ) ); ?>"
                           name="<?php echo esc_attr( $this->get_field_name( 'youtube' ) ); ?>" <?php checked( (bool) $instance['youtube'], true ); ?> />
                </p>

                <p>
                    <label for="<?php echo esc_attr( $this->get_field_id( 'email' ) ); ?>"><?php esc_html_e( 'Show Email:', 'soledad' ); ?></label>
                    <input type="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'email' ) ); ?>"
                           name="<?php echo esc_attr( $this->get_field_name( 'email' ) ); ?>" <?php checked( (bool) $instance['email'], true ); ?> />
                </p>

                <p>
                    <label for="<?php echo esc_attr( $this->get_field_id( 'vk' ) ); ?>"><?php esc_html_e( 'Show Vk:', 'soledad' ); ?></label>
                    <input type="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'vk' ) ); ?>"
                           name="<?php echo esc_attr( $this->get_field_name( 'vk' ) ); ?>" <?php checked( (bool) $instance['vk'], true ); ?> />
                </p>

                <p>
                    <label for="<?php echo esc_attr( $this->get_field_id( 'bloglovin' ) ); ?>"><?php esc_html_e( 'Show Bloglovin:', 'soledad' ); ?></label>
                    <input type="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'bloglovin' ) ); ?>"
                           name="<?php echo esc_attr( $this->get_field_name( 'bloglovin' ) ); ?>" <?php checked( (bool) $instance['bloglovin'], true ); ?> />
                </p>

                <p>
                    <label for="<?php echo esc_attr( $this->get_field_id( 'vine' ) ); ?>"><?php esc_html_e( 'Show Vine:', 'soledad' ); ?></label>
                    <input type="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'vine' ) ); ?>"
                           name="<?php echo esc_attr( $this->get_field_name( 'vine' ) ); ?>" <?php checked( (bool) $instance['vine'], true ); ?> />
                </p>

                <p>
                    <label for="<?php echo esc_attr( $this->get_field_id( 'soundcloud' ) ); ?>"><?php esc_html_e( 'Show Soundcloud:', 'soledad' ); ?></label>
                    <input type="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'soundcloud' ) ); ?>"
                           name="<?php echo esc_attr( $this->get_field_name( 'soundcloud' ) ); ?>" <?php checked( (bool) $instance['soundcloud'], true ); ?> />
                </p>

                <p>
                    <label for="<?php echo esc_attr( $this->get_field_id( 'snapchat' ) ); ?>"><?php esc_html_e( 'Show Snapchat:', 'soledad' ); ?></label>
                    <input type="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'snapchat' ) ); ?>"
                           name="<?php echo esc_attr( $this->get_field_name( 'snapchat' ) ); ?>" <?php checked( (bool) $instance['snapchat'], true ); ?> />
                </p>

                <p>
                    <label for="<?php echo esc_attr( $this->get_field_id( 'spotify' ) ); ?>"><?php esc_html_e( 'Show Spotify:', 'soledad' ); ?></label>
                    <input type="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'spotify' ) ); ?>"
                           name="<?php echo esc_attr( $this->get_field_name( 'spotify' ) ); ?>" <?php checked( (bool) $instance['spotify'], true ); ?> />
                </p>

                <p>
                    <label for="<?php echo esc_attr( $this->get_field_id( 'github' ) ); ?>"><?php esc_html_e( 'Show Github:', 'soledad' ); ?></label>
                    <input type="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'github' ) ); ?>"
                           name="<?php echo esc_attr( $this->get_field_name( 'github' ) ); ?>" <?php checked( (bool) $instance['github'], true ); ?> />
                </p>

                <p>
                    <label for="<?php echo esc_attr( $this->get_field_id( 'stack-overflow' ) ); ?>"><?php esc_html_e( 'Show Stack Overflow:', 'soledad' ); ?></label>
                    <input type="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'stack-overflow' ) ); ?>"
                           name="<?php echo esc_attr( $this->get_field_name( 'stack-overflow' ) ); ?>" <?php checked( (bool) $instance['stack-overflow'], true ); ?> />
                </p>

                <p>
                    <label for="<?php echo esc_attr( $this->get_field_id( 'twitch' ) ); ?>"><?php esc_html_e( 'Show Twitch:', 'soledad' ); ?></label>
                    <input type="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'twitch' ) ); ?>"
                           name="<?php echo esc_attr( $this->get_field_name( 'twitch' ) ); ?>" <?php checked( (bool) $instance['twitch'], true ); ?> />
                </p>

                <p>
                    <label for="<?php echo esc_attr( $this->get_field_id( 'vimeo' ) ); ?>"><?php esc_html_e( 'Show Vimeo:', 'soledad' ); ?></label>
                    <input type="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'vimeo' ) ); ?>"
                           name="<?php echo esc_attr( $this->get_field_name( 'vimeo' ) ); ?>" <?php checked( (bool) $instance['vimeo'], true ); ?> />
                </p>

                <p>
                    <label for="<?php echo esc_attr( $this->get_field_id( 'steam' ) ); ?>"><?php esc_html_e( 'Show Steam:', 'soledad' ); ?></label>
                    <input type="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'steam' ) ); ?>"
                           name="<?php echo esc_attr( $this->get_field_name( 'steam' ) ); ?>" <?php checked( (bool) $instance['steam'], true ); ?> />
                </p>

                <p>
                    <label for="<?php echo esc_attr( $this->get_field_id( 'xing' ) ); ?>"><?php esc_html_e( 'Show Xing:', 'soledad' ); ?></label>
                    <input type="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'xing' ) ); ?>"
                           name="<?php echo esc_attr( $this->get_field_name( 'xing' ) ); ?>" <?php checked( (bool) $instance['xing'], true ); ?> />
                </p>

                <p>
                    <label for="<?php echo esc_attr( $this->get_field_id( 'whatsapp' ) ); ?>"><?php esc_html_e( 'Show Whatsapp:', 'soledad' ); ?></label>
                    <input type="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'whatsapp' ) ); ?>"
                           name="<?php echo esc_attr( $this->get_field_name( 'whatsapp' ) ); ?>" <?php checked( (bool) $instance['whatsapp'], true ); ?> />
                </p>

                <p>
                    <label for="<?php echo esc_attr( $this->get_field_id( 'telegram' ) ); ?>"><?php esc_html_e( 'Show Telegram:', 'soledad' ); ?></label>
                    <input type="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'telegram' ) ); ?>"
                           name="<?php echo esc_attr( $this->get_field_name( 'telegram' ) ); ?>" <?php checked( (bool) $instance['telegram'], true ); ?> />
                </p>

                <p>
                    <label for="<?php echo esc_attr( $this->get_field_id( 'reddit' ) ); ?>"><?php esc_html_e( 'Show Reddit:', 'soledad' ); ?></label>
                    <input type="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'reddit' ) ); ?>"
                           name="<?php echo esc_attr( $this->get_field_name( 'reddit' ) ); ?>" <?php checked( (bool) $instance['reddit'], true ); ?> />
                </p>

                <p>
                    <label for="<?php echo esc_attr( $this->get_field_id( 'ok' ) ); ?>"><?php esc_html_e( 'Show Ok:', 'soledad' ); ?></label>
                    <input type="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'ok' ) ); ?>"
                           name="<?php echo esc_attr( $this->get_field_name( 'ok' ) ); ?>" <?php checked( (bool) $instance['ok'], true ); ?> />
                </p>

                <p>
                    <label for="<?php echo esc_attr( $this->get_field_id( '500px' ) ); ?>"><?php esc_html_e( 'Show 500px:', 'soledad' ); ?></label>
                    <input type="checkbox" id="<?php echo esc_attr( $this->get_field_id( '500px' ) ); ?>"
                           name="<?php echo esc_attr( $this->get_field_name( '500px' ) ); ?>" <?php checked( (bool) $instance['500px'], true ); ?> />
                </p>

                <p>
                    <label for="<?php echo esc_attr( $this->get_field_id( 'stumbleupon' ) ); ?>"><?php esc_html_e( 'Show StumbleUpon:', 'soledad' ); ?></label>
                    <input type="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'stumbleupon' ) ); ?>"
                           name="<?php echo esc_attr( $this->get_field_name( 'stumbleupon' ) ); ?>" <?php checked( (bool) $instance['stumbleupon'], true ); ?> />
                </p>

                <p>
                    <label for="<?php echo esc_attr( $this->get_field_id( 'wechat' ) ); ?>"><?php esc_html_e( 'Show Wechat:', 'soledad' ); ?></label>
                    <input type="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'wechat' ) ); ?>"
                           name="<?php echo esc_attr( $this->get_field_name( 'wechat' ) ); ?>" <?php checked( (bool) $instance['wechat'], true ); ?> />
                </p>

                <p>
                    <label for="<?php echo esc_attr( $this->get_field_id( 'weibo' ) ); ?>"><?php esc_html_e( 'Show Weibo:', 'soledad' ); ?></label>
                    <input type="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'weibo' ) ); ?>"
                           name="<?php echo esc_attr( $this->get_field_name( 'weibo' ) ); ?>" <?php checked( (bool) $instance['weibo'], true ); ?> />
                </p>

                <p>
                    <label for="<?php echo esc_attr( $this->get_field_id( 'line' ) ); ?>"><?php esc_html_e( 'Show LINE:', 'soledad' ); ?></label>
                    <input type="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'line' ) ); ?>"
                           name="<?php echo esc_attr( $this->get_field_name( 'line' ) ); ?>" <?php checked( (bool) $instance['line'], true ); ?> />
                </p>

                <p>
                    <label for="<?php echo esc_attr( $this->get_field_id( 'viber' ) ); ?>"><?php esc_html_e( 'Show Viber:', 'soledad' ); ?></label>
                    <input type="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'viber' ) ); ?>"
                           name="<?php echo esc_attr( $this->get_field_name( 'viber' ) ); ?>" <?php checked( (bool) $instance['viber'], true ); ?> />
                </p>

                <p>
                    <label for="<?php echo esc_attr( $this->get_field_id( 'discord' ) ); ?>"><?php esc_html_e( 'Show Discord:', 'soledad' ); ?></label>
                    <input type="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'discord' ) ); ?>"
                           name="<?php echo esc_attr( $this->get_field_name( 'discord' ) ); ?>" <?php checked( (bool) $instance['discord'], true ); ?> />
                </p>

                <p>
                    <label for="<?php echo esc_attr( $this->get_field_id( 'rss' ) ); ?>"><?php esc_html_e( 'Show RSS:', 'soledad' ); ?></label>
                    <input type="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'rss' ) ); ?>"
                           name="<?php echo esc_attr( $this->get_field_name( 'rss' ) ); ?>" <?php checked( (bool) $instance['rss'], true ); ?> />
                </p>
                <p>
                    <label for="<?php echo esc_attr( $this->get_field_id( 'slack' ) ); ?>"><?php esc_html_e( 'Show slack:', 'soledad' ); ?></label>
                    <input type="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'slack' ) ); ?>"
                           name="<?php echo esc_attr( $this->get_field_name( 'slack' ) ); ?>" <?php checked( (bool) $instance['slack'], true ); ?> />
                </p>
                <p>
                    <label for="<?php echo esc_attr( $this->get_field_id( 'mixcloud' ) ); ?>"><?php esc_html_e( 'Show Mixcloud:', 'soledad' ); ?></label>
                    <input type="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'mixcloud' ) ); ?>"
                           name="<?php echo esc_attr( $this->get_field_name( 'mixcloud' ) ); ?>" <?php checked( (bool) $instance['mixcloud'], true ); ?> />
                </p>

                <p>
                    <label for="<?php echo esc_attr( $this->get_field_id( 'goodreads' ) ); ?>"><?php esc_html_e( 'Show Goodreads:', 'soledad' ); ?></label>
                    <input type="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'goodreads' ) ); ?>"
                           name="<?php echo esc_attr( $this->get_field_name( 'goodreads' ) ); ?>" <?php checked( (bool) $instance['goodreads'], true ); ?> />
                </p>
                <p>
                    <label for="<?php echo esc_attr( $this->get_field_id( 'tripadvisor' ) ); ?>"><?php esc_html_e( 'Show Tripadvisor:', 'soledad' ); ?></label>
                    <input type="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'tripadvisor' ) ); ?>"
                           name="<?php echo esc_attr( $this->get_field_name( 'tripadvisor' ) ); ?>" <?php checked( (bool) $instance['tripadvisor'], true ); ?> />
                </p>

                <p>
                    <label for="<?php echo esc_attr( $this->get_field_id( 'tiktok' ) ); ?>"><?php esc_html_e( 'Show Tiktok:', 'soledad' ); ?></label>
                    <input type="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'tiktok' ) ); ?>"
                           name="<?php echo esc_attr( $this->get_field_name( 'tiktok' ) ); ?>" <?php checked( (bool) $instance['tiktok'], true ); ?> />
                </p>
                <p>
                    <label for="<?php echo esc_attr( $this->get_field_id( 'dailymotion' ) ); ?>"><?php esc_html_e( 'Show Dailymotion:', 'soledad' ); ?></label>
                    <input type="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'dailymotion' ) ); ?>"
                           name="<?php echo esc_attr( $this->get_field_name( 'dailymotion' ) ); ?>" <?php checked( (bool) $instance['dailymotion'], true ); ?> />
                </p>
                <p>
                    <label for="<?php echo esc_attr( $this->get_field_id( 'blogger' ) ); ?>"><?php esc_html_e( 'Show Blogger:', 'soledad' ); ?></label>
                    <input type="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'blogger' ) ); ?>"
                           name="<?php echo esc_attr( $this->get_field_name( 'blogger' ) ); ?>" <?php checked( (bool) $instance['blogger'], true ); ?> />
                </p>
                <p>
                    <label for="<?php echo esc_attr( $this->get_field_id( 'delicious' ) ); ?>"><?php esc_html_e( 'Show Delicious:', 'soledad' ); ?></label>
                    <input type="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'delicious' ) ); ?>"
                           name="<?php echo esc_attr( $this->get_field_name( 'delicious' ) ); ?>" <?php checked( (bool) $instance['delicious'], true ); ?> />
                </p>
                <p>
                    <label for="<?php echo esc_attr( $this->get_field_id( 'deviantart' ) ); ?>"><?php esc_html_e( 'Show Deviantart:', 'soledad' ); ?></label>
                    <input type="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'deviantart' ) ); ?>"
                           name="<?php echo esc_attr( $this->get_field_name( 'deviantart' ) ); ?>" <?php checked( (bool) $instance['deviantart'], true ); ?> />
                </p>
                <p>
                    <label for="<?php echo esc_attr( $this->get_field_id( 'digg' ) ); ?>"><?php esc_html_e( 'Show Digg:', 'soledad' ); ?></label>
                    <input type="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'digg' ) ); ?>"
                           name="<?php echo esc_attr( $this->get_field_name( 'digg' ) ); ?>" <?php checked( (bool) $instance['digg'], true ); ?> />
                </p>
                <p>
                    <label for="<?php echo esc_attr( $this->get_field_id( 'evernote' ) ); ?>"><?php esc_html_e( 'Show Evernote:', 'soledad' ); ?></label>
                    <input type="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'evernote' ) ); ?>"
                           name="<?php echo esc_attr( $this->get_field_name( 'evernote' ) ); ?>" <?php checked( (bool) $instance['evernote'], true ); ?> />
                </p>
                <p>
                    <label for="<?php echo esc_attr( $this->get_field_id( 'forrst' ) ); ?>"><?php esc_html_e( 'Show Forrst:', 'soledad' ); ?></label>
                    <input type="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'forrst' ) ); ?>"
                           name="<?php echo esc_attr( $this->get_field_name( 'forrst' ) ); ?>" <?php checked( (bool) $instance['forrst'], true ); ?> />
                </p>
                <p>
                    <label for="<?php echo esc_attr( $this->get_field_id( 'grooveshark' ) ); ?>"><?php esc_html_e( 'Show Grooveshark:', 'soledad' ); ?></label>
                    <input type="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'grooveshark' ) ); ?>"
                           name="<?php echo esc_attr( $this->get_field_name( 'grooveshark' ) ); ?>" <?php checked( (bool) $instance['grooveshark'], true ); ?> />
                </p>
                <p>
                    <label for="<?php echo esc_attr( $this->get_field_id( 'lastfm' ) ); ?>"><?php esc_html_e( 'Show Lastfm:', 'soledad' ); ?></label>
                    <input type="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'lastfm' ) ); ?>"
                           name="<?php echo esc_attr( $this->get_field_name( 'lastfm' ) ); ?>" <?php checked( (bool) $instance['lastfm'], true ); ?> />
                </p>
                <p>
                    <label for="<?php echo esc_attr( $this->get_field_id( 'myspace' ) ); ?>"><?php esc_html_e( 'Show Myspace:', 'soledad' ); ?></label>
                    <input type="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'myspace' ) ); ?>"
                           name="<?php echo esc_attr( $this->get_field_name( 'myspace' ) ); ?>" <?php checked( (bool) $instance['myspace'], true ); ?> />
                </p>
                <p>
                    <label for="<?php echo esc_attr( $this->get_field_id( 'paypal' ) ); ?>"><?php esc_html_e( 'Show Paypal:', 'soledad' ); ?></label>
                    <input type="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'paypal' ) ); ?>"
                           name="<?php echo esc_attr( $this->get_field_name( 'paypal' ) ); ?>" <?php checked( (bool) $instance['paypal'], true ); ?> />
                </p>
                <p>
                    <label for="<?php echo esc_attr( $this->get_field_id( 'skype' ) ); ?>"><?php esc_html_e( 'Show Skype:', 'soledad' ); ?></label>
                    <input type="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'skype' ) ); ?>"
                           name="<?php echo esc_attr( $this->get_field_name( 'skype' ) ); ?>" <?php checked( (bool) $instance['skype'], true ); ?> />
                </p>
                <p>
                    <label for="<?php echo esc_attr( $this->get_field_id( 'window' ) ); ?>"><?php esc_html_e( 'Show Window:', 'soledad' ); ?></label>
                    <input type="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'window' ) ); ?>"
                           name="<?php echo esc_attr( $this->get_field_name( 'window' ) ); ?>" <?php checked( (bool) $instance['window'], true ); ?> />
                </p>
                <p>
                    <label for="<?php echo esc_attr( $this->get_field_id( 'WordPress' ) ); ?>"><?php esc_html_e( 'Show WordPress:', 'soledad' ); ?></label>
                    <input type="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'WordPress' ) ); ?>"
                           name="<?php echo esc_attr( $this->get_field_name( 'WordPress' ) ); ?>" <?php checked( (bool) $instance['wordPress'], true ); ?> />
                </p>
                <p>
                    <label for="<?php echo esc_attr( $this->get_field_id( 'yahoo' ) ); ?>"><?php esc_html_e( 'Show Yahoo:', 'soledad' ); ?></label>
                    <input type="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'yahoo' ) ); ?>"
                           name="<?php echo esc_attr( $this->get_field_name( 'yahoo' ) ); ?>" <?php checked( (bool) $instance['yahoo'], true ); ?> />
                </p>

                <p>
                    <label for="<?php echo esc_attr( $this->get_field_id( 'yandex' ) ); ?>"><?php esc_html_e( 'Show Yandex:', 'soledad' ); ?></label>
                    <input type="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'yandex' ) ); ?>"
                           name="<?php echo esc_attr( $this->get_field_name( 'yandex' ) ); ?>" <?php checked( (bool) $instance['yandex'], true ); ?> />
                </p>
                <p>
                    <label for="<?php echo esc_attr( $this->get_field_id( 'douban' ) ); ?>"><?php esc_html_e( 'Show Douban:', 'soledad' ); ?></label>
                    <input type="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'douban' ) ); ?>"
                           name="<?php echo esc_attr( $this->get_field_name( 'douban' ) ); ?>" <?php checked( (bool) $instance['douban'], true ); ?> />
                </p>
                <p>
                    <label for="<?php echo esc_attr( $this->get_field_id( 'threads' ) ); ?>"><?php esc_html_e( 'Show Threads:', 'soledad' ); ?></label>
                    <input type="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'threads' ) ); ?>"
                           name="<?php echo esc_attr( $this->get_field_name( 'threads' ) ); ?>" <?php checked( (bool) $instance['threads'], true ); ?> />
                </p>
                <p>
                    <label for="<?php echo esc_attr( $this->get_field_id( 'yelp' ) ); ?>"><?php esc_html_e( 'Show Yelp:', 'soledad' ); ?></label>
                    <input type="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'yelp' ) ); ?>"
                           name="<?php echo esc_attr( $this->get_field_name( 'yelp' ) ); ?>" <?php checked( (bool) $instance['yelp'], true ); ?> />
                </p>
                <p>
                    <label for="<?php echo esc_attr( $this->get_field_id( 'qq' ) ); ?>"><?php esc_html_e( 'Show QQ:', 'soledad' ); ?></label>
                    <input type="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'qq' ) ); ?>"
                           name="<?php echo esc_attr( $this->get_field_name( 'qq' ) ); ?>" <?php checked( (bool) $instance['qq'], true ); ?> />
                </p>
            </div>
			<?php
		}
	}
}
?>
