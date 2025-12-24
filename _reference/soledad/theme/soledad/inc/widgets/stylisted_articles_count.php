<?php
/**
 * Stylisted Articles Count widget
 * Get recent posts and display in widget
 *
 * @package Soledad
 * @since 1.0
 */

add_action( 'widgets_init', 'penci_stylisted_articles_count_load_widget' );

function penci_stylisted_articles_count_load_widget() {
	register_widget( 'penci_stylisted_articles_widget' );
}

if ( ! class_exists( 'penci_stylisted_articles_widget' ) ) {
	class penci_stylisted_articles_widget extends WP_Widget {

		/**
		 * Widget setup.
		 */
		function __construct() {
			/* Widget settings. */
			$widget_ops = array(
				'classname'   => 'penci_stylisted_articles_widget',
				'description' => esc_html__( 'A widget that displays your recent posts from all categories or a category', 'soledad' )
			);

			/* Widget control settings. */
			$control_ops = array( 'id_base' => 'penci_stylisted_articles_widget' );

			/* Create the widget. */
			global $wp_version;
			if ( 4.3 > $wp_version ) {
				$this->WP_Widget( 'penci_stylisted_articles_widget', penci_get_theme_name( '.Soledad', true ) . esc_html__( 'Stylisted Articles Count', 'soledad' ), $widget_ops, $control_ops );
			} else {
				parent::__construct( 'penci_stylisted_articles_widget', penci_get_theme_name( '.Soledad', true ) . esc_html__( 'Stylisted Articles Count', 'soledad' ), $widget_ops, $control_ops );
			}
		}

		/**
		 * How to display the widget on the screen.
		 */
		function widget( $args, $instance ) {
			extract( $args );
			wp_enqueue_script( 'penci_widgets_ajax' );

			/* Our variables from the widget settings. */
			$title      = isset( $instance['title'] ) ? $instance['title'] : '';
			$title      = apply_filters( 'widget_title', $title );
			$categories = isset( $instance['categories'] ) ? $instance['categories'] : '';
			$orderby    = isset( $instance['orderby'] ) ? $instance['orderby'] : 'date';
			$order      = isset( $instance['order'] ) ? $instance['order'] : 'DESC';
			$metakey    = isset( $instance['metakey'] ) ? $instance['metakey'] : '';
			$number     = isset( $instance['number'] ) ? $instance['number'] : '';
			$offset     = isset( $instance['offset'] ) ? $instance['offset'] : '';
			$ptype      = isset( $instance['ptype'] ) ? $instance['ptype'] : '';
			if ( ! $ptype ): $ptype = 'post'; endif;
			$taxonomy     = isset( $instance['taxonomy'] ) ? $instance['taxonomy'] : '';
			$tax_ids      = isset( $instance['tax_ids'] ) ? $instance['tax_ids'] : 'tax_ids';
			$sticky       = isset( $instance['sticky'] ) ? $instance['sticky'] : true;
			$sticky_value = ( false == $sticky ) ? 0 : 1;
			$title_length = isset( $instance['title_length'] ) ? $instance['title_length'] : '';
			$postdate     = isset( $instance['postdate'] ) ? $instance['postdate'] : false;
			$image_type   = isset( $instance['image_type'] ) ? $instance['image_type'] : 'default';
			$ptfsfe       = isset( $instance['ptfsfe'] ) ? absint( $instance['ptfsfe'] ) : '';
			$ptfs         = isset( $instance['ptfs'] ) ? absint( $instance['ptfs'] ) : '';
			$pmfs         = isset( $instance['pmfs'] ) ? absint( $instance['pmfs'] ) : '';
			$row_gap      = isset( $instance['row_gap'] ) ? absint( $instance['row_gap'] ) : '';
			$imgwidth     = isset( $instance['imgwidth'] ) ? absint( $instance['imgwidth'] ) : '';
			$showauthor   = isset( $instance['show_author'] ) ? $instance['show_author'] : false;
			$showcomment  = isset( $instance['show_comment'] ) ? $instance['show_comment'] : false;
			$showviews    = isset( $instance['show_postviews'] ) ? $instance['show_postviews'] : false;
			$showborder   = isset( $instance['showborder'] ) ? $instance['showborder'] : false;
			$cats_id      = ! empty( $instance['cats_id'] ) ? explode( ',', $instance['cats_id'] ) : array();
			$tags_id      = ! empty( $instance['tags_id'] ) ? explode( ',', $instance['tags_id'] ) : array();
			$nbfs_big     = ! empty( $instance['nbfs_big'] ) ? $instance['nbfs_big'] : '';
			$nbfs         = ! empty( $instance['nbfs'] ) ? $instance['nbfs'] : '';

			$query = array(
				'posts_per_page'      => $number,
				'post_type'           => $ptype,
				'ignore_sticky_posts' => $sticky_value
			);

			if ( 'post' == $ptype ) {
				if ( isset( $instance['cats_id'] ) ) {
					if ( ! empty( $cats_id ) && ! in_array( 'all', $cats_id ) ) {
						$query['category__in'] = $cats_id;
					}
				} else {
					$term_name = get_cat_name( $categories );
					$term      = term_exists( $term_name, 'category' );

					if ( $term !== 0 && $term !== null ) {
						$query['cat'] = $categories;
					}
				}

				if ( ! empty( $tags_id ) ) {
					if ( ! in_array( 'all', $tags_id ) ) {
						$query['tag__in'] = $tags_id;
					}
				}
			}

			if ( $orderby == 'week' ) {
				$query['meta_key']     = 'penci_post_week_views_count';
				$query['orderby']       = 'meta_value_num';
			} elseif ( $orderby == 'month' ) {
				$query['meta_key']     = 'penci_post_month_views_count';
				$query['orderby']       = 'meta_value_num';
			} elseif ( $orderby == 'jetpack' ) {
				$query['meta_key']     = '_jetpack_post_view';
				$query['orderby']       = 'meta_value_num';
			} elseif ( $orderby == 'all' ) {
				$query['meta_key']     = penci_get_postviews_key();
				$query['orderby']       = 'meta_value_num';
			} elseif ( $orderby == 'metakey' && $metakey ) {
				$query['meta_key']     = $metakey;
				$query['orderby']      = 'meta_value_num';
			} elseif ( $orderby ) {
				$query['orderby'] = $orderby;
			}


			if ( $order ) {
				$query['order'] = $order;
			}
			if ( $offset ) {
				$query['offset'] = $offset;
			}

			if ( $taxonomy && ( 'post' != $ptype ) ) {
				$taxonomy  = str_replace( ' ', '', $taxonomy );
				$tax_array = explode( ',', $taxonomy );

				foreach ( $tax_array as $tax ) {
					$tax_ids_array = array();
					if ( $tax_ids ) {
						$tax_ids       = str_replace( ' ', '', $tax_ids );
						$tax_ids_array = explode( ',', $tax_ids );
					} else {
						$get_all_terms = get_terms( $tax );
						if ( ! empty( $get_all_terms ) ) {
							foreach ( $get_all_terms as $term ) {
								$tax_ids_array[] = $term->term_id;
							}
						}
					}

					if ( ! empty( $tax_ids_array ) ) {
						$query['tax_query'][] = array(
							'taxonomy' => $tax,
							'field'    => 'term_id',
							'terms'    => $tax_ids_array
						);
					}
				}
			}

			$loop = new WP_Query( $query );
			if ( $loop->have_posts() ) :

				/* Before widget (defined by themes). */
				echo ent2ncr( $before_widget );

				/* Display the widget title if one was input (before and after defined by themes). */
				if ( $title ) {
					echo ent2ncr( $before_title ) . $title . ent2ncr( $after_title );
				}

				$rand          = rand( 1000, 10000 );
				$data_settings = $instance;
				unset( $data_settings['title'] );

				$thumb = 'penci-thumb-masonry';

				if ( $image_type == 'horizontal' ) {
					$thumb = 'penci-thumb-small';
				} elseif ( $image_type == 'square' ) {
					$thumb = 'penci-thumb-square';
				} elseif ( $image_type == 'vertical' ) {
					$thumb = 'penci-thumb-vertical';
				}

				get_template_part( 'inc/templates/popular_posts', '', [
					'loop'         => $loop,
					'class'        => 'demo',
					'showauthor'   => $showauthor,
					'postdate'     => $postdate,
					'showviews'    => $showviews,
					'showcomment'  => $showcomment,
					'title_length' => $title_length,
					'thumb'        => $thumb,
					'id'           => 'pcstylisted-acount-' . sanitize_text_field( $rand ),
					'data_attr'    => 'data-mes="' . penci_get_setting( 'penci_trans_no_more_posts' ) . '" data-max="' . esc_attr( $loop->max_num_pages ) . '" data-settings=\'' . json_encode( $data_settings ) . '\' data-paged="1" data-action="penci_stylisted_articles_count_ajax"',
				] );

				if ( isset( $instance['ajaxnav'] ) && $instance['ajaxnav'] == 'btn' ) {
					?>
                    <div class="penci-pagination penci-ajax-more pcwg-lposts">
                        <a class="penci-ajax-more-button penci-wgajx-btn"
                           href="#" aria-label="More Posts">
                            <span class="ajax-more-text"><?php echo penci_get_setting( 'penci_trans_load_more_posts' ); ?></span><span
                                    class="ajaxdot"></span><i
                                    class="penci-faicon fa fa-refresh"></i> </a>
                    </div>
					<?php
				} else if ( isset( $instance['ajaxnav'] ) && $instance['ajaxnav'] == 'nav' ) { ?>
                    <div class="penci-pagination penci-ajax-nav pcwg-lposts">
                        <span class="pcajx-btn penci-wgajx-btn prev disable"><?php echo penci_icon_by_ver( 'penciicon-left-chevron' ) . ' <span class="pcnav-title">' . penci_get_setting( 'penci_trans_back' ); ?></span></span>
                        <span class="pcajx-btn penci-wgajx-btn next"><span
                                    class="pcnav-title"><?php echo penci_get_setting( 'penci_trans_next' ) . '</span>' . penci_icon_by_ver( 'penciicon-right-chevron' ); ?></span>
                    </div>
					<?php
				}
				if ( isset( $instance['ajaxnav'] ) && $instance['ajaxnav'] ) {
					?>
                    <div class="pcwgajx-ld-wrapper">
						<?php echo penci_get_html_animation_loading( 'df' ); ?>
                    </div>
					<?php
				}
				$attrstyle = '';
				if ( $ptfsfe ) {
					$attrstyle .= '.widget ul#pcstylisted-acount-' . $rand . ' .popularpost_item.first .pcpopular_new_post_title{ font-size: ' . $ptfsfe . 'px; }';
				}
				if ( $ptfs ) {
					$attrstyle .= '.widget ul#pcstylisted-acount-' . $rand . ' .popularpost_item .pcpopular_new_post_title{ font-size: ' . $ptfs . 'px; }';
				}
				if ( $pmfs ) {
					$attrstyle .= '.widget ul#pcstylisted-acount-' . $rand . ' .popularpost_meta{ font-size: ' . $pmfs . 'px; }';
				}
				if ( $row_gap ) {
					$attrstyle .= '.widget ul#pcstylisted-acount-' . $rand . ' ul li{ margin-bottom: ' . $row_gap . 'px; padding-bottom: ' . $row_gap . 'px; }';
				}
				if ( $nbfs ) {
					$attrstyle .= '.widget ul#pcstylisted-acount-' . $rand . ' .popularpost_item .pcpopular_new_post_title a:before{ font-size: ' . $nbfs . 'px; }';
				}
				if ( $nbfs_big ) {
					$attrstyle .= '.widget ul#pcstylisted-acount-' . $rand . ' .popularpost_item.first .pcpopular_new_post_title a::before, .widget ul#pcstylisted-acount-' . $rand . ' .popularpost_item.first:hover .pcpopular_new_post_title a::before{ font-size: ' . $nbfs_big . 'px; }';
				}
				if ( $image_type == 'horizontal' ) {
					$attrstyle .= '#pcstylisted-acount-' . $rand . ' .penci-image-holder:before{ padding-top: 66.6667%; }';
				} elseif ( $image_type == 'square' ) {
					$attrstyle .= '#pcstylisted-acount-' . $rand . ' .penci-image-holder:before{ padding-top: 100%; }';
				} elseif ( $image_type == 'vertical' ) {
					$attrstyle .= '#pcstylisted-acount-' . $rand . ' .penci-image-holder:before{ padding-top: 135.4%; }';
				}

				if ( $attrstyle ) {
					echo '<style>' . $attrstyle . '</style>';
				}

				/* After widget (defined by themes). */
				echo ent2ncr( $after_widget );

				wp_reset_postdata();
			endif;
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

			if ( ! empty( $new_instance['cats_id'] ) ) {
				if ( is_array( $new_instance['cats_id'] ) ) {
					$instance['cats_id'] = implode( ',', $new_instance['cats_id'] );
				} else {
					$instance['cats_id'] = esc_sql( $new_instance['cats_id'] );
				}
			} else {
				$instance['cats_id'] = false;
			}

			if ( ! empty( $new_instance['tags_id'] ) ) {
				if ( is_array( $new_instance['tags_id'] ) ) {
					$instance['tags_id'] = implode( ',', $new_instance['tags_id'] );
				} else {
					$instance['tags_id'] = esc_sql( $new_instance['tags_id'] );
				}
			} else {
				$instance['tags_id'] = false;
			}

			return $instance;
		}

		public function soledad_widget_defaults() {
			$defaults = array(
				'title'          => esc_html__( 'Stylisted Articles Count', 'soledad' ),
				'hide_thumb'     => false,
				'movemeta'       => false,
				'ptype'          => '',
				'taxonomy'       => '',
				'tax_ids'        => '',
				'dotstyle'       => '',
				'sticky'         => true,
				'show_author'    => false,
				'show_comment'   => false,
				'show_postviews' => false,
				'showborder'     => false,
				'row_gap'        => '',
				'ptfsfe'         => '',
				'ptfs'           => '',
				'orderby'        => 'date',
				'order'          => 'DESC',
				'pmfs'           => '',
				'image_type'     => 'default',
				'title_length'   => '',
				'number'         => 5,
				'offset'         => '',
				'categories'     => '',
				'ajaxnav'        => '',
				'metakey'        => '',
				'postdate'       => false,
				'nbfs_big'       => '',
				'nbfs'           => '',
			);

			return $defaults;
		}

		function form( $instance ) {

			/* Set up some default widget settings. */
			$defaults = $this->soledad_widget_defaults();

			$cats_id = array();
			$tags_id = array();

			if ( isset( $instance['cats_id'] ) && ! empty( $instance['cats_id'] ) ) {
				$cats_id = explode( ',', $instance['cats_id'] );
			}
			if ( isset( $instance['tags_id'] ) && ! empty( $instance['tags_id'] ) ) {
				$tags_id = explode( ',', $instance['tags_id'] );
			}

			$instance = wp_parse_args( (array) $instance, $defaults );

			$instance_title = $instance['title'] ? str_replace( '"', '&quot;', $instance['title'] ) : '';
			?>
            <style>span.description {
                    font-style: italic;
                    font-size: 13px;
                }</style>
            <!-- Widget Title: Text Input -->
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'soledad' ); ?></label>
                <input type="text" class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"
                       name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>"
                       value="<?php echo $instance_title; ?>"/>
            </p>

            <!-- Category -->
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'cats_id' ) ); ?>"><?php esc_html_e( 'Include Categories:', 'soledad' ); ?></label>
                <select multiple="multiple" id="<?php echo esc_attr( $this->get_field_id( 'cats_id' ) ); ?>[]"
                        name="<?php echo esc_attr( $this->get_field_name( 'cats_id' ) ); ?>[]"
                        class="widefat categories" style="width:100%; height: 125px;">
                    <option value='all' <?php if ( in_array( 'all', $cats_id ) ) {
						echo 'selected="selected"';
					} ?>><?php esc_html_e( 'All categories', 'soledad' ); ?></option>
					<?php $categories = get_categories( 'hide_empty=0&depth=1&type=post' ); ?>
					<?php foreach ( $categories as $category ) { ?>
                        <option value='<?php echo esc_attr( $category->term_id ); ?>' <?php if ( in_array( $category->term_id, $cats_id ) ) {
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
                    <option value='all' <?php if ( in_array( 'all', $tags_id ) ) {
						echo 'selected="selected"';
					} ?>><?php esc_html_e( 'All tags', 'soledad' ); ?></option>
					<?php $tags = get_tags( 'hide_empty=0&depth=1&type=post' ); ?>
					<?php foreach ( $tags as $tag ) { ?>
                        <option value='<?php echo esc_attr( $tag->term_id ); ?>' <?php if ( in_array( $tag->term_id, $tags_id ) ) {
							echo 'selected="selected"';
						} ?>><?php echo sanitize_text_field( $tag->name ); ?></option>
					<?php } ?>
                </select>
                <span class="description"><?php _e( 'Hold the "Ctrl" on the keyboard and click to select/un-select multiple tags.', 'soledad' ); ?></span>
            </p>

            <!-- Custom Post Type -->
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'ptype' ) ); ?>"><?php esc_html_e( 'Query for Custom Post Type:', 'soledad' ); ?></label>
                <select id="<?php echo esc_attr( $this->get_field_id( 'ptype' ) ); ?>"
                        name="<?php echo esc_attr( $this->get_field_name( 'ptype' ) ); ?>" class="widefat categories"
                        style="width:100%;">
                    <option value='' <?php if ( '' == $instance['ptype'] ) {
						echo 'selected="selected"';
					} ?>><?php esc_html_e( '- Default( Posts ) -', 'soledad' ); ?></option>
					<?php $all_post_types = get_post_types( array(
						'public'            => true,
						'show_in_nav_menus' => true
					), 'objects' );
					unset( $all_post_types['post'] );
					if ( ! empty( $all_post_types ) ) {
						foreach ( $all_post_types as $p_type => $ptobject ) {
							?>
                            <option value='<?php echo esc_attr( $p_type ); ?>' <?php if ( $p_type == $instance['ptype'] ) {
								echo 'selected="selected"';
							} ?>><?php echo $ptobject->label; ?></option>
						<?php }
					}
					?>
                </select>
            </p>

            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'taxonomy' ) ); ?>"><?php esc_html_e( 'Custom taxonomies to query:', 'soledad' ); ?></label>
                <input type="text" class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'taxonomy' ) ); ?>"
                       name="<?php echo esc_attr( $this->get_field_name( 'taxonomy' ) ); ?>"
                       value="<?php echo esc_attr( $instance['taxonomy'] ); ?>" size="3"/>
                <span class="description"><?php _e( 'Fill taxonomy slug(s) here. Separate by commas to query multiple taxonomies. Check <a href="https://imgresources.s3.amazonaws.com/custom-taxonomy.png" style="color: #007cba;" target="_blank">this image</a> for understand more.', 'soledad' ); ?></span>
            </p>

            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'tax_ids' ) ); ?>"><?php esc_html_e( 'Taxonomy IDs to query:', 'soledad' ); ?></label>
                <input type="text" class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'tax_ids' ) ); ?>"
                       name="<?php echo esc_attr( $this->get_field_name( 'tax_ids' ) ); ?>"
                       value="<?php echo esc_attr( $instance['tax_ids'] ); ?>" size="3"/>
                <span class="description"><?php _e( 'Fill taxonomy ID(s) here. Separate by commas to query multiple taxonomies. Check <a href="https://imgresources.s3.amazonaws.com/custom-taxonomy.png" style="color: #007cba;" target="_blank">this image</a> for understand more.', 'soledad' ); ?></span>
            </p>

            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'sticky' ) ); ?>"><?php esc_html_e( 'Ignore Sticky Posts?:', 'soledad' ); ?></label>
                <input type="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'sticky' ) ); ?>"
                       name="<?php echo esc_attr( $this->get_field_name( 'sticky' ) ); ?>" <?php checked( (bool) $instance['sticky'], true ); ?> /><br>
                <span class="description"><?php _e( 'Note that: Ignore sticky posts doesn\'t work if you filter your posts by a taxonomy or multiple taxonomies (categories, tags... ) - because it doesn\'t support by WordPress itself.', 'soledad' ); ?></span>
            </p>

            <!-- Image Size -->
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'image_type' ) ); ?>"><?php esc_html_e( 'Featured Images Type:', 'soledad' ); ?></label>
                <select id="<?php echo esc_attr( $this->get_field_id( 'image_type' ) ); ?>"
                        name="<?php echo esc_attr( $this->get_field_name( 'image_type' ) ); ?>"
                        class="widefat image_type" style="width:100%;">
                    <option value='default' <?php if ( 'default' == $instance['image_type'] ) {
						echo 'selected="selected"';
					} ?>><?php esc_html_e( 'Default ( follow on Customize )', 'soledad' ); ?></option>
                    <option value='horizontal' <?php if ( 'horizontal' == $instance['image_type'] ) {
						echo 'selected="selected"';
					} ?>><?php esc_html_e( 'Horizontal Size', 'soledad' ); ?></option>
                    <option value='square' <?php if ( 'square' == $instance['image_type'] ) {
						echo 'selected="selected"';
					} ?>><?php esc_html_e( 'Square Size', 'soledad' ); ?></option>
                    <option value='vertical' <?php if ( 'vertical' == $instance['image_type'] ) {
						echo 'selected="selected"';
					} ?>><?php esc_html_e( 'Vertical Size', 'soledad' ); ?></option>
                </select>
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
					<option value='all' <?php if ( 'all' == $instance['orderby'] ) {
						echo 'selected="selected"';
					} ?>><?php esc_html_e( 'Most Viewed Posts All Time', 'soledad' ); ?></option>
					<option value='week' <?php if ( 'week' == $instance['orderby'] ) {
						echo 'selected="selected"';
					} ?>><?php esc_html_e( 'Most Viewed Posts Weekly', 'soledad' ); ?></option>
					<option value='month' <?php if ( 'month' == $instance['orderby'] ) {
						echo 'selected="selected"';
					} ?>><?php esc_html_e( 'Most Viewed Posts Monthly', 'soledad' ); ?></option>
					<option value='metakey' <?php if ( 'metakey' == $instance['orderby'] ) {
						echo 'selected="selected"';
					} ?>><?php esc_html_e( 'Custom Meta Key', 'soledad' ); ?></option>
					<?php if ( ! empty( penci_jetpack_option() ) ): ?>
                        <option value='jetpack' <?php if ( 'jetpack' == $instance['orderby'] ) {
							echo 'selected="selected"';
						} ?>>Jetpack Post Views
                        </option>
					<?php endif; ?>
                </select>
            </p>

			<!-- Custom Meta Key -->
			<p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'metakey' ) ); ?>"><?php esc_html_e( 'Custom Meta Key:', 'soledad' ); ?></label>
                <input type="text" class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'metakey' ) ); ?>"
                       name="<?php echo esc_attr( $this->get_field_name( 'metakey' ) ); ?>"
                       value="<?php echo esc_attr( $instance['metakey'] ); ?>"/>
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

            <!-- Custom trim post titles -->
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'title_length' ) ); ?>"><?php esc_html_e( 'Custom words length for post titles:', 'soledad' ); ?></label>
                <input type="text" class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title_length' ) ); ?>"
                       name="<?php echo esc_attr( $this->get_field_name( 'title_length' ) ); ?>"
                       value="<?php echo esc_attr( $instance['title_length'] ); ?>" size="3"/>
                <span class="description" style="display: block; padding: 0;font-size: 12px;">If your post titles is too long - You can use this option for trim it. Fill number value here.</span>
            </p>

            <!-- Ajax Post Navigation -->
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'ajaxnav' ) ); ?>"><?php esc_html_e( 'Ajax Posts Navigation?', 'soledad' ); ?></label>
                <select id="<?php echo esc_attr( $this->get_field_id( 'ajaxnav' ) ); ?>"
                        name="<?php echo esc_attr( $this->get_field_name( 'ajaxnav' ) ); ?>" class="widefat orderby"
                        style="width:100%;">
                    <option value='' <?php if ( '' == $instance['ajaxnav'] ) {
						echo 'selected="selected"';
					} ?>><?php esc_html_e( "Don't Show", 'soledad' ); ?></option>
                    <option value='nav' <?php if ( 'nav' == $instance['ajaxnav'] ) {
						echo 'selected="selected"';
					} ?>><?php esc_html_e( 'Next/Previous Button', 'soledad' ); ?></option>
                    <option value='btn' <?php if ( 'btn' == $instance['ajaxnav'] ) {
						echo 'selected="selected"';
					} ?>><?php esc_html_e( 'Load More Button', 'soledad' ); ?></option>
                </select>
            </p>

            <!-- Post Meta -->
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'show_author' ) ); ?>"><?php esc_html_e( 'Show Author Name?:', 'soledad' ); ?></label>
                <input type="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'show_author' ) ); ?>"
                       name="<?php echo esc_attr( $this->get_field_name( 'show_author' ) ); ?>" <?php checked( (bool) $instance['show_author'], true ); ?> />
            </p>

            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'postdate' ) ); ?>"><?php esc_html_e( 'Hide post date?:', 'soledad' ); ?></label>
                <input type="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'postdate' ) ); ?>"
                       name="<?php echo esc_attr( $this->get_field_name( 'postdate' ) ); ?>" <?php checked( (bool) $instance['postdate'], true ); ?> />
            </p>

            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'show_comment' ) ); ?>"><?php esc_html_e( 'Show Comment Count?:', 'soledad' ); ?></label>
                <input type="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'show_comment' ) ); ?>"
                       name="<?php echo esc_attr( $this->get_field_name( 'show_comment' ) ); ?>" <?php checked( (bool) $instance['show_comment'], true ); ?> />
            </p>

            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'show_postviews' ) ); ?>"><?php esc_html_e( 'Show Post Views?:', 'soledad' ); ?></label>
                <input type="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'show_postviews' ) ); ?>"
                       name="<?php echo esc_attr( $this->get_field_name( 'show_postviews' ) ); ?>" <?php checked( (bool) $instance['show_postviews'], true ); ?> />
            </p>

            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'showborder' ) ); ?>"><?php esc_html_e( 'Remove Border at The Bottom?:', 'soledad' ); ?></label>
                <input type="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'showborder' ) ); ?>"
                       name="<?php echo esc_attr( $this->get_field_name( 'showborder' ) ); ?>" <?php checked( (bool) $instance['showborder'], true ); ?> />
            </p>

            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'row_gap' ) ); ?>"><?php esc_html_e( 'Rows Gap Between Post Items ( Default: 20 )', 'soledad' ); ?></label>
                <input type="number" class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'row_gap' ) ); ?>"
                       name="<?php echo esc_attr( $this->get_field_name( 'row_gap' ) ); ?>"
                       value="<?php echo esc_attr( $instance['row_gap'] ); ?>" size="3"/>
            </p>

            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'ptfsfe' ) ); ?>"><?php esc_html_e( 'Post Title Font Size on Featured Posts ( Default: 18 )', 'soledad' ); ?></label>
                <input type="number" class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'ptfsfe' ) ); ?>"
                       name="<?php echo esc_attr( $this->get_field_name( 'ptfsfe' ) ); ?>"
                       value="<?php echo esc_attr( $instance['ptfsfe'] ); ?>" size="3"/>
            </p>

            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'ptfs' ) ); ?>"><?php esc_html_e( 'Post Title Font Size ( Default: 14 )', 'soledad' ); ?></label>
                <input type="number" class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'ptfs' ) ); ?>"
                       name="<?php echo esc_attr( $this->get_field_name( 'ptfs' ) ); ?>"
                       value="<?php echo esc_attr( $instance['ptfs'] ); ?>" size="3"/>
            </p>

            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'pmfs' ) ); ?>"><?php esc_html_e( 'Post Meta Font Size ( Default: 11 )', 'soledad' ); ?></label>
                <input type="number" class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'pmfs' ) ); ?>"
                       name="<?php echo esc_attr( $this->get_field_name( 'pmfs' ) ); ?>"
                       value="<?php echo esc_attr( $instance['pmfs'] ); ?>" size="3"/>
            </p>

            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'nbfs_big' ) ); ?>"><?php esc_html_e( 'Order Number Font Size for Featured Post ( Default: 36 )', 'soledad' ); ?></label>
                <input type="number" class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'nbfs_big' ) ); ?>"
                       name="<?php echo esc_attr( $this->get_field_name( 'nbfs_big' ) ); ?>"
                       value="<?php echo esc_attr( $instance['nbfs_big'] ); ?>" size="3"/>
            </p>

            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'nbfs' ) ); ?>"><?php esc_html_e( 'Order Number Font Size ( Default: 18 )', 'soledad' ); ?></label>
                <input type="number" class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'nbfs' ) ); ?>"
                       name="<?php echo esc_attr( $this->get_field_name( 'nbfs' ) ); ?>"
                       value="<?php echo esc_attr( $instance['nbfs'] ); ?>" size="3"/>
            </p>

			<?php
		}
	}
}
?>
