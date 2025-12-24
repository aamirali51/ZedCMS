<?php
/**
 * Web Stories widget
 * Display webs stories posts on footer or sidebar
 *
 * @package Soledad
 * @since   8.2.2
 */

add_action( 'widgets_init', 'penci_webstories_load_widget' );

function penci_webstories_load_widget() {
	register_widget( 'penci_webstories_widget' );
}

if ( ! class_exists( 'penci_webstories_widget' ) ) {
	class penci_webstories_widget extends WP_Widget {

		/**
		 * Widget setup.
		 */
		function __construct() {
			/* Widget settings. */
			$widget_ops = array(
				'classname'   => 'penci_webstories_widget',
				'description' => esc_html__( 'A widget that displays an a Web Stories', 'soledad' )
			);

			/* Widget control settings. */
			$control_ops = array( 'id_base' => 'penci_webstories_widget' );

			/* Create the widget. */ global $wp_version;
			if ( 4.3 > $wp_version ) {
				$this->WP_Widget( 'penci_webstories_widget', penci_get_theme_name( '.Soledad', true ) . esc_html__( 'Web Stories', 'soledad' ), $widget_ops, $control_ops );
			} else {
				parent::__construct( 'penci_webstories_widget', penci_get_theme_name( '.Soledad', true ) . esc_html__( 'Web Stories', 'soledad' ), $widget_ops, $control_ops );
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

			/* Before widget (defined by themes). */
			echo ent2ncr( $before_widget );

			/* Display the widget title if one was input (before and after defined by themes). */
			if ( $title ) {
				echo $before_title . $title . $after_title;
			}

			$args_stories = [
				'order'       => isset( $instance['order'] ) ? $instance['order'] : '',
				'orderby'     => isset( $instance['orderby'] ) ? $instance['orderby'] : '',
				'number'      => isset( $instance['number'] ) ? $instance['number'] : 4,
				'offset'      => isset( $instance['offset'] ) ? $instance['offset'] : 0,
				'story_cat'   => isset( $instance['story_cat'] ) ? $instance['story_cat'] : '',
				'story_tag'   => isset( $instance['story_tag'] ) ? $instance['story_tag'] : '',
				'layout'      => isset( $instance['layout'] ) ? $instance['layout'] : 'grid',
				'align'       => isset( $instance['align'] ) ? $instance['align'] : '',
				'columns'     => isset( $instance['columns'] ) ? $instance['columns'] : 4,
				'iwidth'      => isset( $instance['iwidth'] ) ? $instance['iwidth'] : 100,
				'nextprev'    => isset( $instance['nextprev'] ) && $instance['nextprev'] ? 'yes' : '',
				'pos'         => isset( $instance['pos'] ) && $instance['pos'] ? 'yes' : '',
				'showtitle'   => isset( $instance['showtitle'] ) && $instance['showtitle'] ? 'yes' : '',
				'title_fsize' => isset( $instance['title_fsize'] ) && $instance['title_fsize'] ? $instance['title_fsize'] : '',
				'iboradius' => isset( $instance['iboradius'] ) && $instance['iboradius'] ? $instance['iboradius'] : '',
			];

			$id_base = '#' . $this->id;

			penci_webstories( $args_stories );

			echo '<style>';
			if ( $args_stories['columns'] ) {
				echo $id_base . ' .pc-wstories-wrapper .pc-wstories-list.grid .pc-webstory-item{width:calc(100% / ' . $args_stories['columns'] . ')}';
				echo $id_base . ' .pc-wstories-wrapper .pc-wstories-list.slider:not(.penci-owl-loaded) .pc-webstory-item{width:calc(100% / ' . $args_stories['columns'] . ')}';
				echo $id_base . ' .pc-wstories-wrapper .pc-wstories-list.one-row .pc-webstory-item{width:unset;flex: 0 0 calc(100% / ' . $args_stories['columns'] . ');min-width:calc(100% / ' . $args_stories['columns'] . ');}';
			}
			if ( $args_stories['iboradius'] ) {
				echo $id_base . ' .pc-wstories-wrapper .pc-webstory-thumb-wrapper, '. $id_base .' .pc-wstories-wrapper .pc-webstory-thumb{border-radius:' . $args_stories['iboradius'] . 'px;}';
			}
			if ( $args_stories['iwidth'] ) {
				echo $id_base . ' .pc-wstories-wrapper .pc-wstories-list.one-row .pc-webstory-item{flex: 0 0 ' . $args_stories['iwidth'] . 'px;min-width:' . $args_stories['iwidth'] . 'px;}';
			}
			if ( $args_stories['title_fsize'] ) {
				echo $id_base . ' .pc-webstory-item .pc-webstory-item-title h4{font-size:' . $args_stories['title_fsize'] . 'px}';
			}
			echo '</style>';

			wp_enqueue_script( 'penci_web_stories' );

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
				'title'       => 'Web Stories',
				'order'       => '',
				'orderby'     => '',
				'number'      => 4,
				'offset'      => '',
				'story_cat'   => '',
				'story_tag'   => '',
				'layout'      => 'grid',
				'align'       => '',
				'columns'     => 4,
				'iwidth'      => 100,
				'nextprev'    => true,
				'pos'         => true,
				'showtitle'   => true,
				'title_fsize' => '',
				'iboradius' => '',
			);
		}

		function form( $instance ) {

			/* Set up some default widget settings. */
			$defaults = $this->soledad_widget_defaults();
			$instance = wp_parse_args( (array) $instance, $defaults );

			$story_cat = array();
			$story_tag = array();

			if ( isset( $instance['cats_id'] ) && ! empty( $instance['story_cat'] ) ) {
				$story_cat = explode( ',', $instance['story_cat'] );
			}
			if ( isset( $instance['story_tag'] ) && ! empty( $instance['story_tag'] ) ) {
				$story_tag = explode( ',', $instance['story_tag'] );
			}

			$instance_title = $instance['title'] ? str_replace( '"', '&quot;', $instance['title'] ) : '';
			?>
            <!-- Widget Title: Text Input -->

            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'soledad' ); ?></label>
                <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"
                       name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>"
                       value="<?php echo $instance_title; ?>"/>
            </p>

            <!-- Category -->
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'cats_id' ) ); ?>"><?php esc_html_e( 'Include Categories:', 'soledad' ); ?></label>
                <select multiple="multiple" id="<?php echo esc_attr( $this->get_field_id( 'cats_id' ) ); ?>[]"
                        name="<?php echo esc_attr( $this->get_field_name( 'cats_id' ) ); ?>[]"
                        class="widefat categories" style="width:100%; height: 125px;">
                    <option value='all' <?php if ( in_array( 'all', $story_cat ) ) {
						echo 'selected="selected"';
					} ?>><?php esc_html_e( 'All categories', 'soledad' ); ?></option>
					<?php $categories = get_categories( 'taxonomy=web_story_category&hide_empty=0&depth=1&type=web-story' ); ?>
					<?php foreach ( $categories as $category ) { ?>
                        <option value='<?php echo esc_attr( $category->term_id ); ?>' <?php if ( in_array( $category->term_id, $story_cat ) ) {
							echo 'selected="selected"';
						} ?>><?php echo sanitize_text_field( $category->name ); ?></option>
					<?php } ?>
                </select>
                <span class="description"><?php _e( 'Hold the "Ctrl" on the keyboard and click to select/un-select multiple categories.', 'soledad' ); ?></span>
            </p>

            <!-- Tags -->
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'tags_id' ) ); ?>"><?php esc_html_e( 'Include Tags:', 'soledad' ); ?></label>
                <select multiple="multiple" id="<?php echo esc_attr( $this->get_field_id( 'tags_id' ) ); ?>[]"
                        name="<?php echo esc_attr( $this->get_field_name( 'tags_id' ) ); ?>[]"
                        class="widefat categories" style="width:100%; height: 125px;">
                    <option value='all' <?php if ( in_array( 'all', $story_tag ) ) {
						echo 'selected="selected"';
					} ?>><?php esc_html_e( 'All tags', 'soledad' ); ?></option>
					<?php $tags = get_tags( 'taxonomy=web_story_tag&hide_empty=0&depth=1&type=web-story' ); ?>
					<?php foreach ( $tags as $tag ) { ?>
                        <option value='<?php echo esc_attr( $tag->term_id ); ?>' <?php if ( in_array( $tag->term_id, $story_tag ) ) {
							echo 'selected="selected"';
						} ?>><?php echo sanitize_text_field( $tag->name ); ?></option>
					<?php } ?>
                </select>
                <span class="description"><?php _e( 'Hold the "Ctrl" on the keyboard and click to select/un-select multiple tags.', 'soledad' ); ?></span>
            </p>

            <!-- Order by -->
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'orderby' ) ); ?>"><?php esc_html_e( 'Order By:', 'soledad' ); ?></label>
                <select id="<?php echo esc_attr( $this->get_field_id( 'orderby' ) ); ?>"
                        name="<?php echo esc_attr( $this->get_field_name( 'orderby' ) ); ?>" class="widefat orderby"
                        style="width:100%;">
                    <option value='date' <?php if ( 'date' == $instance['orderby'] ) {
						echo 'selected="selected"';
					} ?>><?php esc_html_e( 'Published Date', 'soledad' ); ?></option>
                    <option value='ID' <?php if ( 'ID' == $instance['orderby'] ) {
						echo 'selected="selected"';
					} ?>><?php esc_html_e( 'Posts ID', 'soledad' ); ?></option>
                    <option value='title' <?php if ( 'title' == $instance['orderby'] ) {
						echo 'selected="selected"';
					} ?>><?php esc_html_e( 'Posts Titles', 'soledad' ); ?></option>
                    <option value='modified' <?php if ( 'modified' == $instance['orderby'] ) {
						echo 'selected="selected"';
					} ?>><?php esc_html_e( 'Modified Date', 'soledad' ); ?></option>
                    <option value='comment_count' <?php if ( 'comment_count' == $instance['orderby'] ) {
						echo 'selected="selected"';
					} ?>><?php esc_html_e( 'Comment Count', 'soledad' ); ?></option>
                    <option value='rand' <?php if ( 'rand' == $instance['orderby'] ) {
						echo 'selected="selected"';
					} ?>><?php esc_html_e( 'Random', 'soledad' ); ?></option>
                </select>
            </p>

            <!-- Order -->
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'order' ) ); ?>"><?php esc_html_e( 'Select Order Type:', 'soledad' ); ?></label>
                <select id="<?php echo esc_attr( $this->get_field_id( 'order' ) ); ?>"
                        name="<?php echo esc_attr( $this->get_field_name( 'order' ) ); ?>" class="widefat orderby"
                        style="width:100%;">
                    <option value='DESC' <?php if ( 'DESC' == $instance['order'] ) {
						echo 'selected="selected"';
					} ?>><?php esc_html_e( 'DESC ( Descending Order )', 'soledad' ); ?></option>
                    <option value='ASC' <?php if ( 'ASC' == $instance['order'] ) {
						echo 'selected="selected"';
					} ?>><?php esc_html_e( 'ASC ( Ascending Order )', 'soledad' ); ?></option>
                </select>
            </p>

            <!-- Number of posts -->
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>"><?php esc_html_e( 'Number of posts to show:', 'soledad' ); ?></label>
                <input type="text" class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>"
                       name="<?php echo esc_attr( $this->get_field_name( 'number' ) ); ?>"
                       value="<?php echo esc_attr( $instance['number'] ); ?>" size="3"/>
            </p>

            <!-- Offset of posts -->
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'offset' ) ); ?>"><?php esc_html_e( 'Number of offset Posts:', 'soledad' ); ?></label>
                <input type="text" class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'offset' ) ); ?>"
                       name="<?php echo esc_attr( $this->get_field_name( 'offset' ) ); ?>"
                       value="<?php echo esc_attr( $instance['offset'] ); ?>" size="3"/>
            </p>

            <!-- Layout -->
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'layout' ) ); ?>"><?php esc_html_e( 'Select Layout Type:', 'soledad' ); ?></label>
                <select id="<?php echo esc_attr( $this->get_field_id( 'layout' ) ); ?>"
                        name="<?php echo esc_attr( $this->get_field_name( 'layout' ) ); ?>" class="widefat orderby"
                        style="width:100%;">
                    <option value='grid' <?php if ( 'grid' == $instance['layout'] ) {
						echo 'selected="selected"';
					} ?>><?php esc_html_e( 'Grid', 'soledad' ); ?></option>
                    <option value='grid' <?php if ( 'slider' == $instance['layout'] ) {
						echo 'selected="selected"';
					} ?>><?php esc_html_e( 'Slider', 'soledad' ); ?></option>
                    <option value='onerow' <?php if ( 'onerow' == $instance['layout'] ) {
						echo 'selected="selected"';
					} ?>><?php esc_html_e( 'One Row', 'soledad' ); ?></option>
                </select>
            </p>

            <!-- Columns -->
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'columns' ) ); ?>"><?php esc_html_e( 'Number of columns to show:', 'soledad' ); ?></label>
                <input type="text" class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'columns' ) ); ?>"
                       name="<?php echo esc_attr( $this->get_field_name( 'columns' ) ); ?>"
                       value="<?php echo esc_attr( $instance['columns'] ); ?>" size="12"/>
            </p>

            <!-- Custom Item Width -->
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'iwidth' ) ); ?>"><?php esc_html_e( 'Custom Item Width (for One Row Layout):', 'soledad' ); ?></label>
                <input type="text" class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'iwidth' ) ); ?>"
                       name="<?php echo esc_attr( $this->get_field_name( 'iwidth' ) ); ?>"
                       value="<?php echo esc_attr( $instance['iwidth'] ); ?>" size="12"/>
            </p>

            <!-- Align -->
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'align' ) ); ?>">Align This Widget:</label>
                <select id="<?php echo esc_attr( $this->get_field_id( 'align' ) ); ?>"
                        name="<?php echo esc_attr( $this->get_field_name( 'align' ) ); ?>" class="widefat categories"
                        style="width:100%;">
                    <option value='pc_aligncenter' <?php if ( '' == $instance['align'] ) {
						echo 'selected="selected"';
					} ?>>Align Center
                    </option>
                    <option value='pc_alignleft' <?php if ( 'pc_alignleft' == $instance['align'] ) {
						echo 'selected="selected"';
					} ?>>Align Left
                    </option>
                    <option value='pc_alignright' <?php if ( 'pc_alignright' == $instance['align'] ) {
						echo 'selected="selected"';
					} ?>>Align Right
                    </option>
                </select>
            </p>
			
			<!-- Border radius -->
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'iboradius' ) ); ?>"><?php esc_html_e( 'Thumbnail Border Radius (px):', 'soledad' ); ?></label>
                <input type="number" class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'iboradius' ) ); ?>"
                       name="<?php echo esc_attr( $this->get_field_name( 'iboradius' ) ); ?>"
                       value="<?php echo esc_attr( $instance['iboradius'] ); ?>" size="3"/>
            </p>

            <!-- Show Title -->
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'showtitle' ) ); ?>"><?php esc_html_e( 'Display Story Title ?:', 'soledad' ); ?></label>
                <input type="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'showtitle' ) ); ?>"
                       name="<?php echo esc_attr( $this->get_field_name( 'showtitle' ) ); ?>" <?php checked( (bool) $instance['showtitle'], true ); ?> />
            </p>

            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'title_fsize' ) ); ?>"><?php esc_html_e( 'Font size for Story Title:', 'soledad' ); ?></label>
                <input type="text" class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title_fsize' ) ); ?>"
                       name="<?php echo esc_attr( $this->get_field_name( 'title_fsize' ) ); ?>"
                       value="<?php echo esc_attr( $instance['title_fsize'] ); ?>" size="12"/>
            </p>

            <!-- Show Next/Prev -->
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'nextprev' ) ); ?>"><?php esc_html_e( 'Display Next/Prev Buttons ?:', 'soledad' ); ?></label>
                <input type="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'nextprev' ) ); ?>"
                       name="<?php echo esc_attr( $this->get_field_name( 'nextprev' ) ); ?>" <?php checked( (bool) $instance['nextprev'], true ); ?> />
            </p>

            <!-- Show Position  -->
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'pos' ) ); ?>"><?php esc_html_e( 'Display Current Story Position ?:', 'soledad' ); ?></label>
                <input type="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'pos' ) ); ?>"
                       name="<?php echo esc_attr( $this->get_field_name( 'pos' ) ); ?>" <?php checked( (bool) $instance['pos'], true ); ?> />
            </p>
			<?php
		}
	}
}
?>
