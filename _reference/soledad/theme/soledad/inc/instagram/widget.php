<?php
add_action( 'widgets_init', 'penci_instagram_feed_widget' );

if ( ! function_exists( 'penci_instagram_feed_widget' ) ) {
	function penci_instagram_feed_widget() {
		register_widget( 'Penci_Instagram_Feed_Widget' );
	}
}

if ( ! class_exists( 'Penci_Instagram_Feed_Widget' ) ) {
	class Penci_Instagram_Feed_Widget extends WP_Widget {

		protected $default;

		function __construct() {
			$this->default = array(
				'title'            => __( 'Instagram', 'soledad' ),
				'template'         => 'thumbs-no-border',
				'images_link'      => 'image_url',
				'custom_url'       => '',
				'orderby'          => 'rand',
				'images_number'    => 9,
				'columns'          => 3,
				'refresh_hour'     => 12,
				'image_size'       => 'jr_insta_square',
				'image_link_rel'   => '',
				'image_link_class' => '',
				'controls'         => 'prev_next',
				'caption_words'    => 20,
				'slidespeed'       => 7000,
				'description'      => array( 'username', 'time', 'caption' ),
			);

			$widget_ops  = array(
				'classname'   => 'penci-insta-slider',
				'description' => esc_html__( 'A widget that displays thumbnails or a slider with instagram images', 'soledad' )
			);
			$control_ops = array( 'id_base' => 'penci-insta-slider' );

			global $wp_version;
			if ( 4.3 > $wp_version ) {
				$this->WP_Widget( 'penci-insta-slider', penci_get_theme_name( '.Soledad', true ) . ' ' . esc_html__( 'Instagram Feed', 'soledad' ), $widget_ops, $control_ops );
			} else {
				parent::__construct( 'penci-insta-slider', penci_get_theme_name( '.Soledad', true ) . ' ' . esc_html__( 'Instagram Feed', 'soledad' ), $widget_ops, $control_ops );
			}
		}

		function widget( $args, $instance ) {
			$instance = wp_parse_args( $instance, $this->default );
			extract( $args );

			/* Before widget (defined by themes). */
			echo ent2ncr( $before_widget );

			/* Display the widget title if one was input (before and after defined by themes). */
			$title = apply_filters( 'widget_title', $instance['title'] );
			if ( $title ) {
				echo ent2ncr( $before_title ) . $title . ent2ncr( $after_title );
			}

			Penci_Instagram_Feed::display_images( $instance );

			/* After widget (defined by themes). */
			echo ent2ncr( $after_widget );
		}

		function update( $new_instance, $old_instance ) {
			$instance = $old_instance;

			$instance['title']            = isset( $new_instance['title'] ) && $new_instance['title'] ? $new_instance['title'] : '';
			$instance['template']         = isset( $new_instance['template'] ) && $new_instance['template'] ? $new_instance['template'] : '';
			$instance['images_link']      = isset( $new_instance['images_link'] ) && $new_instance['images_link'] ? $new_instance['images_link'] : '';
			$instance['custom_url']       = isset( $new_instance['custom_url'] ) && $new_instance['custom_url'] ? $new_instance['custom_url'] : '';
			$instance['orderby']          = isset( $new_instance['orderby'] ) && $new_instance['orderby'] ? $new_instance['orderby'] : '';
			$instance['images_number']    = isset( $new_instance['images_number'] ) && $new_instance['images_number'] ? $new_instance['images_number'] : '';
			$instance['columns']          = isset( $new_instance['columns'] ) && $new_instance['columns'] ? $new_instance['columns'] : '';
			$instance['refresh_hour']     = isset( $new_instance['refresh_hour'] ) && $new_instance['refresh_hour'] ? $new_instance['refresh_hour'] : '';
			$instance['image_size']       = isset( $new_instance['image_size'] ) && $new_instance['image_size'] ? $new_instance['image_size'] : '';
			$instance['image_link_rel']   = isset( $new_instance['image_link_rel'] ) && $new_instance['image_link_rel'] ? $new_instance['image_link_rel'] : '';
			$instance['image_link_class'] = isset( $new_instance['image_link_class'] ) && $new_instance['image_link_class'] ? $new_instance['image_link_class'] : '';
			$instance['controls']         = isset( $new_instance['controls'] ) && $new_instance['controls'] ? $new_instance['controls'] : '';
			$instance['caption_words']    = isset( $new_instance['caption_words'] ) && $new_instance['caption_words'] ? $new_instance['caption_words'] : '';
			$instance['slidespeed']       = isset( $new_instance['slidespeed'] ) && $new_instance['slidespeed'] ? $new_instance['slidespeed'] : '';
			$instance['description']      = isset( $new_instance['description'] ) && $new_instance['description'] ? $new_instance['description'] : '';

			return $instance;
		}

		function form( $instance ) {
			$instance = wp_parse_args( $instance, $this->default );

			$instance_title = $instance['title'] ? str_replace( '"', '&quot;', $instance['title'] ) : '';
			?>
            <div class="penci-instagram-container">
                <p><span style="color: #ff0000;">Note Important: </span>Please connect to your Instagram accout on <a
                            href="<?php echo esc_url( admin_url( 'admin.php?page=penci_instgram_token' ) ); ?>"
                            target="_blank">this page</a> first.
                </p>
                <p>
                    <label for="<?php echo $this->get_field_id( 'title' ); ?>"><strong><?php _e( 'Title:', 'soledad' ); ?></strong></label>
                    <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>"
                           name="<?php echo $this->get_field_name( 'title' ); ?>"
                           value="<?php echo $instance_title; ?>"/>
                </p>
                <p>
                    <label for="<?php echo $this->get_field_id( 'images_number' ); ?>"><strong><?php _e( 'Number of images to show:', 'soledad' ); ?></strong>
                        <input class="small-text" id="<?php echo $this->get_field_id( 'images_number' ); ?>"
                               name="<?php echo $this->get_field_name( 'images_number' ); ?>"
                               value="<?php echo $instance['images_number']; ?>"/>
                    </label>
                </p>
                <p>
                    <label for="<?php echo $this->get_field_id( 'refresh_hour' ); ?>"><strong><?php _e( 'Check for new images every:', 'soledad' ); ?></strong>
                        <input class="small-text" id="<?php echo $this->get_field_id( 'refresh_hour' ); ?>"
                               name="<?php echo $this->get_field_name( 'refresh_hour' ); ?>"
                               value="<?php echo $instance['refresh_hour']; ?>"/>
                        <span><?php _e( 'hours', 'soledad' ); ?></span>
                    </label>
                </p>
                <p>
                    <label for="<?php echo $this->get_field_id( 'template' ); ?>"><strong><?php _e( 'Template', 'soledad' ); ?></strong>
                        <select class="widefat" name="<?php echo $this->get_field_name( 'template' ); ?>"
                                id="<?php echo $this->get_field_id( 'template' ); ?>">
                            <option value="thumbs-no-border" <?php echo ( $instance['template'] == 'thumbs-no-border' ) ? ' selected="selected"' : ''; ?>><?php _e( 'Thumbnails - Without Border', 'soledad' ); ?></option>
                            <option value="thumbs" <?php echo ( $instance['template'] == 'thumbs' ) ? ' selected="selected"' : ''; ?>><?php _e( 'Thumbnails', 'soledad' ); ?></option>
                            <option value="slider" <?php echo ( $instance['template'] == 'slider' ) ? ' selected="selected"' : ''; ?>><?php _e( 'Slider - Normal', 'soledad' ); ?></option>
                            <option value="slider-overlay" <?php echo ( $instance['template'] == 'slider-overlay' ) ? ' selected="selected"' : ''; ?>><?php _e( 'Slider - Overlay Text', 'soledad' ); ?></option>
                        </select>
                    </label>
                </p>
                <p class="<?php if ( 'thumbs' != $instance['template'] && 'thumbs-no-border' != $instance['template'] ) {
					echo 'hidden';
				} ?>">
                    <label for="<?php echo $this->get_field_id( 'columns' ); ?>"><strong><?php _e( 'Number of Columns:', 'soledad' ); ?></strong>
                        <input class="small-text" id="<?php echo $this->get_field_id( 'columns' ); ?>"
                               name="<?php echo $this->get_field_name( 'columns' ); ?>"
                               value="<?php echo $instance['columns']; ?>"/>
                        <span class='penci-description'><?php _e( 'max is 10 ( only for thumbnails template )', 'soledad' ); ?></span>
                    </label>
                </p>
                <p>
                    <label for="<?php echo $this->get_field_id( 'orderby' ); ?>"><strong><?php _e( 'Order by', 'soledad' ); ?></strong>
                        <select class="widefat" name="<?php echo $this->get_field_name( 'orderby' ); ?>"
                                id="<?php echo $this->get_field_id( 'orderby' ); ?>">
                            <option value="date-ASC" <?php selected( $instance['orderby'], 'date-ASC', true ); ?>><?php _e( 'Date - Ascending', 'soledad' ); ?></option>
                            <option value="date-DESC" <?php selected( $instance['orderby'], 'date-DESC', true ); ?>><?php _e( 'Date - Descending', 'soledad' ); ?></option>
                            <option value="popular-ASC" <?php selected( $instance['orderby'], 'popular-ASC', true ); ?>><?php _e( 'Popularity - Ascending', 'soledad' ); ?></option>
                            <option value="popular-DESC" <?php selected( $instance['orderby'], 'popular-DESC', true ); ?>><?php _e( 'Popularity - Descending', 'soledad' ); ?></option>
                            <option value="rand" <?php selected( $instance['orderby'], 'rand', true ); ?>><?php _e( 'Random', 'soledad' ); ?></option>
                        </select>
                    </label>
                </p>
                <p>
                    <label for="<?php echo $this->get_field_id( 'images_link' ); ?>"><strong><?php _e( 'Link to', 'soledad' ); ?></strong>
                        <select class="widefat" name="<?php echo $this->get_field_name( 'images_link' ); ?>"
                                id="<?php echo $this->get_field_id( 'images_link' ); ?>">
                            <option value="image_url" <?php selected( $instance['images_link'], 'image_url', true ); ?>><?php _e( 'Instagram Image', 'soledad' ); ?></option>
                            <option value="user_url" <?php selected( $instance['images_link'], 'user_url', true ); ?>><?php _e( 'Instagram Profile', 'soledad' ); ?></option>
                            <option value="attachment" <?php selected( $instance['images_link'], 'attachment', true ); ?>><?php _e( 'Attachment Page', 'soledad' ); ?></option>
                            <option value="none" <?php selected( $instance['images_link'], 'none', true ); ?>><?php _e( 'None', 'soledad' ); ?></option>
                        </select>
                    </label>
                </p>
                <p class="<?php if ( 'custom_url' != $instance['images_link'] ) {
					echo 'hidden';
				} ?>">
                    <label for="<?php echo $this->get_field_id( 'custom_url' ); ?>"><?php _e( 'Custom link:', 'soledad' ); ?></label>
                    <input class="widefat" id="<?php echo $this->get_field_id( 'custom_url' ); ?>"
                           name="<?php echo $this->get_field_name( 'custom_url' ); ?>"
                           value="<?php echo $instance['custom_url']; ?>"/>
                    <span><?php _e( '* use this field only if the above option is set to <strong>Custom Link</strong>', 'soledad' ); ?></span>
                </p>
                <div class="penci-advanced-input">
                    <div class="penci-slider-options <?php if ( 'thumbs' == $instance['template'] || 'thumbs-no-border' == $instance['template'] ) {
						echo 'hidden';
					} ?>">
                        <h4 class="penci-advanced-title"><?php _e( 'Advanced Slider Options', 'soledad' ); ?></h4>
                        <p>
							<?php _e( 'Slider Navigation Controls:', 'soledad' ); ?><br>
                            <label class="penci-radio"><input type="radio"
                                                              id="<?php echo $this->get_field_id( 'controls' ); ?>"
                                                              name="<?php echo $this->get_field_name( 'controls' ); ?>"
                                                              value="prev_next" <?php checked( 'prev_next', $instance['controls'] ); ?> /> <?php _e( 'Prev & Next', 'soledad' ); ?>
                            </label>
                            <label class="penci-radio"><input type="radio"
                                                              id="<?php echo $this->get_field_id( 'controls' ); ?>"
                                                              name="<?php echo $this->get_field_name( 'controls' ); ?>"
                                                              value="numberless" <?php checked( 'numberless', $instance['controls'] ); ?> /> <?php _e( 'Dotted', 'soledad' ); ?>
                            </label>
                            <label class="penci-radio"><input type="radio"
                                                              id="<?php echo $this->get_field_id( 'controls' ); ?>"
                                                              name="<?php echo $this->get_field_name( 'controls' ); ?>"
                                                              value="none" <?php checked( 'none', $instance['controls'] ); ?> /> <?php _e( 'No Navigation', 'soledad' ); ?>
                            </label>
                        </p>
                        <p>
                            <label for="<?php echo $this->get_field_id( 'caption_words' ); ?>"><?php _e( 'Number of words in caption:', 'soledad' ); ?>
                                <input class="small-text" id="<?php echo $this->get_field_id( 'caption_words' ); ?>"
                                       name="<?php echo $this->get_field_name( 'caption_words' ); ?>"
                                       value="<?php echo $instance['caption_words']; ?>"/>
                            </label>
                        </p>
                        <p>
                            <label for="<?php echo $this->get_field_id( 'slidespeed' ); ?>"><?php _e( 'Slide Speed:', 'soledad' ); ?>
                                <input class="small-text" id="<?php echo $this->get_field_id( 'slidespeed' ); ?>"
                                       name="<?php echo $this->get_field_name( 'slidespeed' ); ?>"
                                       value="<?php echo $instance['slidespeed']; ?>"/>
                                <span><?php _e( 'milliseconds', 'soledad' ); ?></span>
                                <span class='penci-description'><?php _e( '1000 milliseconds = 1 second', 'soledad' ); ?></span>
                            </label>
                        </p>
                        <p>
                            <label for="<?php echo $this->get_field_id( 'description' ); ?>"><?php _e( 'Slider Text Description:', 'soledad' ); ?></label>
                            <select size=3 class='widefat' id="<?php echo $this->get_field_id( 'description' ); ?>"
                                    name="<?php echo $this->get_field_name( 'description' ); ?>[]" multiple="multiple">
                                <option value='username' <?php $this->selected( $instance['description'], 'username' ); ?>><?php _e( 'Username', 'soledad' ); ?></option>
                                <option value='time'<?php $this->selected( $instance['description'], 'time' ); ?>><?php _e( 'Time', 'soledad' ); ?></option>
                                <option value='caption'<?php $this->selected( $instance['description'], 'caption' ); ?>><?php _e( 'Caption', 'soledad' ); ?></option>
                            </select>
                            <span class="jr-description"><?php _e( 'Hold ctrl and click the fields you want to show/hide on your slider. Leave all unselected to hide them all. Default all selected.', 'soledad' ) ?></span>
                        </p>
                    </div>
                </div>
            </div>
			<?php
		}

		public function selected( $haystack, $current ) {

			if ( is_array( $haystack ) && in_array( $current, $haystack ) ) {
				selected( 1, 1, true );
			}
		}
	}
}
?>
