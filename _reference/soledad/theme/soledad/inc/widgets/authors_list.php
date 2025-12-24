<?php

add_action( 'widgets_init', 'penci_authors_list_load_widget' );

function penci_authors_list_load_widget() {
	register_widget( 'penci_authors_list_widget' );
}

if ( ! class_exists( 'penci_authors_list_widget' ) ) {
	class penci_authors_list_widget extends WP_Widget {

		/**
		 * Widget setup.
		 */
		function __construct() {
			/* Widget settings. */
			$widget_ops = array(
				'classname'   => 'penci_authors_list_widget',
				'description' => esc_html__( 'A widget that displays a list of site authors', 'soledad' )
			);

			/* Widget control settings. */
			$control_ops = array( 'width' => 250, 'height' => 350, 'id_base' => 'penci_authors_list_widget' );

			/* Create the widget. */ global $wp_version;
			if ( 4.3 > $wp_version ) {
				$this->WP_Widget( 'penci_authors_list_widget', penci_get_theme_name( '.Soledad', true ) . esc_html__( 'Author List', 'soledad' ), $widget_ops, $control_ops );
			} else {
				parent::__construct( 'penci_authors_list_widget', penci_get_theme_name( '.Soledad', true ) . esc_html__( 'Author List', 'soledad' ), $widget_ops, $control_ops );
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
			$role__in    = isset( $instance['role__in'] ) && $instance['role__in'] ? $instance['role__in'] : 'administrator';
			$exclude     = isset( $instance['exclude'] ) && $instance['exclude'] ? $instance['exclude'] : '';
			$avatar      = isset( $instance['avatar'] ) && $instance['avatar'] ? $instance['avatar'] : '';
			$ava_bd      = isset( $instance['ava_bd'] ) && $instance['ava_bd'] ? $instance['ava_bd'] : '';
			$ava_size    = isset( $instance['ava_size'] ) && $instance['ava_size'] ? $instance['ava_size'] : 75;
			$total_posts = isset( $instance['total_posts'] ) && $instance['total_posts'] ? $instance['total_posts'] : '';


			/* Before widget (defined by themes). */
			echo ent2ncr( $before_widget );

			/* Display the widget title if one was input (before and after defined by themes). */
			if ( $title ) {
				echo ent2ncr( $before_title ) . $title . ent2ncr( $after_title );
			}
			$users_args = [];

			if ( $role__in !== 'all' ) {
				$users_args['role__in'] = explode( ',', $role__in );
			}
			if ( $exclude ) {
				$users_args['exclude'] = explode( ',', $exclude );
			}
			$users = get_users( $users_args );
			if ( $users ) {
				?>
                <div class="pc-widget-user-lists">
                    <ul>
						<?php foreach ( $users as $user ):
							$total_post = count_user_posts( $user->ID );
							$author_link = get_author_posts_url( $user->ID );
							$text = $total_post > 1 ? penci_get_setting( 'penci_trans_posts' ) : penci_get_setting( 'penci_trans_post' );
							?>
                            <li>
								<?php if ( $avatar ): ?>
                                    <a class="pc-uw-ava"
                                       href="<?php echo esc_url( $author_link ); ?>"><?php echo get_avatar( $user->ID, $ava_size ); ?></a>
								<?php endif; ?>
                                <div class="pc-uw-userinfo">
                                    <h4 class="name"><a
                                                href="<?php echo esc_url( $author_link ); ?>"><?php echo esc_attr( $user->display_name ); ?></a>
                                    </h4>
									<?php if ( $total_posts ): ?>
                                        <span class="count"><?php echo $total_post . ' ' . $text; ?></span>
									<?php endif; ?>
                                </div>
                            </li>
						<?php endforeach; ?>
                    </ul>
                </div>
				<?php
			}
			$styles = [
				'ava_bd'   => [
					'border-radius' => '#' . $this->id . ' .pc-widget-user-lists img'
				],
				'ava_size' => [
					'width' => '#' . $this->id . ' .pc-widget-user-lists .pc-uw-ava',
				]
			];

			$out = '';

			foreach ( $styles as $option => $selectors ) {
				$value = isset( $instance[ $option ] ) ? $instance[ $option ] : '';
				if ( $value ) {
					foreach ( $selectors as $prop => $selector ) {
						$prefix = in_array( $prop, [ 'width', 'height' ] ) ? 'px' : '';
						$out    .= $selector . '{' . $prop . ':' . $value . $prefix . '}';
					}
				}
			}

			if ( $out ) {
				echo '<style>' . $out . '</style>';
			}


			/* After widget (defined by themes). */
			echo ent2ncr( $after_widget );
		}

		/**
		 * Update the widget settings.
		 */
		function update( $new_instance, $old_instance ) {
			$instance      = $old_instance;
			$data_instance = $this->soledad_widget_defaults();
			foreach ( $data_instance as $data => $value ) {


				if ( 'role__in' == $data ) {

					if ( ! empty( $new_instance['role__in'] ) ) {
						if ( is_array( $new_instance['role__in'] ) ) {
							$instance['role__in'] = implode( ',', $new_instance['role__in'] );
						} else {
							$instance['role__in'] = esc_sql( $new_instance['role__in'] );
						}
					} else {
						$instance['role__in'] = false;
					}
				} else {
					$instance[ $data ] = ! empty( $new_instance[ $data ] ) ? $new_instance[ $data ] : '';
				}
			}

			return $instance;
		}

		public function soledad_widget_defaults() {
			return array(
				'title'       => esc_html__( 'Author Lists', 'soledad' ),
				'role__in'    => 'administrator',
				'exclude'     => '',
				'avatar'      => true,
				'total_posts' => true,
				'ava_bd'      => '',
				'ava_size'    => '',
			);
		}


		function form( $instance ) {

			/* Set up some default widget settings. */
			$defaults = $this->soledad_widget_defaults();
			$instance = wp_parse_args( (array) $instance, $defaults );

			$instance_title = $instance['title'] ? str_replace( '"', '&quot;', $instance['title'] ) : '';
			$role__in       = isset( $instance['role__in'] ) ? explode( ',', $instance['role__in'] ) : [];
			?>

            <!-- Widget Title: Text Input -->
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'soledad' ); ?></label>
                <input type="text" class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"
                       name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>"
                       value="<?php echo $instance_title; ?>"/>
            </p>

            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'role__in' ) ); ?>"><?php esc_html_e( 'Include User Roles:', 'soledad' ); ?></label>
                <select multiple="multiple" id="<?php echo esc_attr( $this->get_field_id( 'role__in' ) ); ?>[]"
                        name="<?php echo esc_attr( $this->get_field_name( 'role__in' ) ); ?>[]"
                        class="widefat categories" style="width:100%; height: 125px;">
                    <option value='all' <?php if ( in_array( 'all', $role__in ) ) {
						echo 'selected="selected"';
					} ?>><?php esc_html_e( 'All User Roles', 'soledad' ); ?></option>
					<?php
					$wp_roles = new WP_Roles();
					if ( ! empty( $wp_roles ) ) {
						foreach ( $wp_roles->roles as $role_name => $role_info ) {
							?>
                            <option value='<?php echo esc_attr( $role_name ); ?>' <?php if ( in_array( $role_name, $role__in ) ) {
								echo 'selected="selected"';
							} ?>><?php echo sanitize_text_field( $role_info['name'] ); ?></option>
						<?php }
					} ?>
                </select>
                <span class="description"
                      style="padding-left: 0;"><?php _e( 'Hold the "Ctrl" on the keyboard and click to select/un-select multiple user roles.', 'soledad' ); ?></span>
            </p>

            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'exclude' ) ); ?>"><?php esc_html_e( 'Exclude User IDs (Separate by comma):', 'soledad' ); ?></label>
                <input type="text" class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'exclude' ) ); ?>"
                       name="<?php echo esc_attr( $this->get_field_name( 'exclude' ) ); ?>"
                       value="<?php echo $instance['exclude']; ?>"/>
            </p>

            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'avatar' ) ); ?>"><?php esc_html_e( 'Show the user Avatar?:', 'soledad' ); ?></label>
                <input type="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'avatar' ) ); ?>"
                       name="<?php echo esc_attr( $this->get_field_name( 'avatar' ) ); ?>" <?php checked( (bool) $instance['avatar'], true ); ?> />
            </p>

            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'total_posts' ) ); ?>"><?php esc_html_e( 'Show the user posts count?:', 'soledad' ); ?></label>
                <input type="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'total_posts' ) ); ?>"
                       name="<?php echo esc_attr( $this->get_field_name( 'total_posts' ) ); ?>" <?php checked( (bool) $instance['total_posts'], true ); ?> />
            </p>

            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'ava_size' ) ); ?>">Avatar Size (Numberis
                    only)</label>
                <input type="number" class="widefat"
                       id="<?php echo esc_attr( $this->get_field_id( 'ava_size' ) ); ?>"
                       name="<?php echo esc_attr( $this->get_field_name( 'ava_size' ) ); ?>"
                       value="<?php echo $instance['ava_size']; ?>">
            </p>

            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'ava_bd' ) ); ?>">Avatar Border Radius (Example:
                    75px or 50%)</label>
                <input type="text" class="widefat"
                       id="<?php echo esc_attr( $this->get_field_id( 'ava_bd' ) ); ?>"
                       name="<?php echo esc_attr( $this->get_field_name( 'ava_bd' ) ); ?>"
                       value="<?php echo $instance['ava_bd']; ?>">
            </p>

			<?php
		}
	}
}
?>
