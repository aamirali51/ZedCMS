<?php
/**
 * Post slider widget
 * Display recent post or popular post for each category or all posts
 *
 * @package Wordpress
 * @since 1.0
 */

add_action( 'widgets_init', 'penci_slider_posts_news_load_widget' );

function penci_slider_posts_news_load_widget() {
	register_widget( 'penci_slider_posts_news_widget' );
}

if ( ! class_exists( 'penci_slider_posts_news_widget' ) ) {
	class penci_slider_posts_news_widget extends WP_Widget {

		/**
		 * Widget setup.
		 */
		function __construct() {
			/* Widget settings. */
			$widget_ops = array(
				'classname'   => 'penci_slider_posts_news_widget',
				'description' => esc_html__( 'A widget that displays your latest/popular posts from all categories or a category with a slider', 'soledad' )
			);

			/* Widget control settings. */
			$control_ops = array( 'width' => 250, 'height' => 350, 'id_base' => 'penci_slider_posts_news_widget' );

			/* Create the widget. */ global $wp_version;
			if ( 4.3 > $wp_version ) {
				$this->WP_Widget( 'penci_slider_posts_news_widget', penci_get_theme_name( '.Soledad', true ) . esc_html__( 'Posts Slider', 'soledad' ), $widget_ops, $control_ops );
			} else {
				parent::__construct( 'penci_slider_posts_news_widget', penci_get_theme_name( '.Soledad', true ) . esc_html__( 'Posts Slider', 'soledad' ), $widget_ops, $control_ops );
			}
		}

		/**
		 * How to display the widget on the screen.
		 */
		function widget( $args, $instance ) {
			extract( $args );

			/* Our variables from the widget settings. */
			$title      = isset( $instance['title'] ) ? $instance['title'] : '';
			$title      = apply_filters( 'widget_title', $title );
			$categories = isset( $instance['categories'] ) ? $instance['categories'] : '';
			$number     = isset( $instance['number'] ) ? $instance['number'] : 5;
			$orderby    = isset( $instance['orderby'] ) ? $instance['orderby'] : 'date';
			$order      = isset( $instance['order'] ) ? $instance['order'] : 'DESC';
			$metakey    = isset( $instance['metakey'] ) ? $instance['metakey'] : '';
			$offset     = isset( $instance['offset'] ) ? $instance['offset'] : '';
			$query_cat  = isset( $instance['query_cat'] ) ? $instance['query_cat'] : '';
			$style      = isset( $instance['style'] ) ? $instance['style'] : 'style-1';
			$date       = isset( $instance['date'] ) ? $instance['date'] : false;
			$image_type = isset( $instance['image_type'] ) ? $instance['image_type'] : 'default';
			$ptype      = isset( $instance['ptype'] ) ? $instance['ptype'] : '';
			if ( ! $ptype ): $ptype = 'post'; endif;
			$taxonomy     = isset( $instance['taxonomy'] ) ? $instance['taxonomy'] : '';
			$tax_ids      = isset( $instance['tax_ids'] ) ? $instance['tax_ids'] : 'tax_ids';
			$sticky       = isset( $instance['sticky'] ) ? $instance['sticky'] : true;
			$sticky_value = ( false == $sticky ) ? 0 : 1;
			$autoplay     = isset( $instance['autoplay'] ) ? $instance['autoplay'] : '';
			$autoplaydata = ( $autoplay ) ? 'true' : 'false';
			$ptfs         = isset( $instance['ptfs'] ) ? $instance['ptfs'] : '';
			$pmfs         = isset( $instance['pmfs'] ) ? $instance['pmfs'] : '';
			$cats_id      = ! empty( $instance['cats_id'] ) ? explode( ',', $instance['cats_id'] ) : array();
			$tags_id      = ! empty( $instance['tags_id'] ) ? explode( ',', $instance['tags_id'] ) : array();

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
					if ( $categories ) {
						$query['cat'] = $categories;
					}
				}

				if ( ! empty( $tags_id ) ) {
					if ( ! in_array( 'all', $tags_id ) ) {
						$query['tag__in'] = $cats_id;
					}
				}
			}

			if ( $query_cat && ( is_category() || is_tag() ) ) {
				$query['post_type'] = 'post';
				$queried_object = get_queried_object();
			
				if ( is_category() ) {
					if ( ! empty( $queried_object->term_id ) ) {
						$query['cat'] = $queried_object->term_id;
					} elseif ( ! empty( $queried_object->slug ) ) {
						$query['category_name'] = $queried_object->slug;
					}
				} elseif ( is_tag() ) {
					if ( ! empty( $queried_object->term_id ) ) {
						$query['tag_id'] = $queried_object->term_id;
					} elseif ( ! empty( $queried_object->slug ) ) {
						$query['tag'] = $queried_object->slug;
					}
				}
			}

			if ( 'popular' == $orderby ) {
				$query['meta_key'] = penci_get_postviews_key();
				$query['orderby']  = 'meta_value_num';
			} elseif ( 'popular_day' == $orderby ) {
				$query['meta_key'] = 'penci_post_day_views_count';
				$query['orderby']  = 'meta_value_num';
			} elseif ( 'popular7' == $orderby ) {
				$query['meta_key'] = 'penci_post_week_views_count';
				$query['orderby']  = 'meta_value_num';
			} elseif ( 'popular_month' == $orderby ) {
				$query['meta_key'] = 'penci_post_month_views_count';
				$query['orderby']  = 'meta_value_num';
			} elseif ( 'jetpack' == $orderby ) {
				$query['meta_key'] = '_jetpack_post_view';
				$query['orderby']  = 'meta_value_num';
			} elseif ( 'metakey' == $orderby && $metakey ) {
				$query['meta_key'] = $metakey;
				$query['orderby']  = 'meta_value_num';
			} else {
				if ( $orderby ) {
					$query['orderby'] = $orderby;
				}
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
			$rand = rand( 100, 10000 );
			if ( $loop->have_posts() ) :

				/* Before widget (defined by themes). */ echo ent2ncr( $before_widget );

				/* Display the widget title if one was input (before and after defined by themes). */
				if ( $title ) {
					echo ent2ncr( $before_title ) . $title . ent2ncr( $after_title );
				}
				?>
                <div id="penci-postslidewg-<?php echo sanitize_text_field( $rand ); ?>"
                     class="swiper penci-owl-carousel penci-owl-carousel-slider penci-widget-slider penci-post-slider-<?php echo $style; ?>"
                     data-lazy="true" data-auto="<?php echo $autoplaydata; ?>">
                    <div class="swiper-wrapper">
						<?php while ( $loop->have_posts() ) : $loop->the_post(); ?>
                            <div class="swiper-slide penci-slide-widget">
                                <div class="penci-slide-content">
									<?php if ( $style != 'style-3' ) { ?>

                                        <span <?php echo penci_layout_bg( penci_image_srcset( get_the_ID(), penci_featured_images_size() ) ); ?> class="<?php echo penci_layout_bg_class();?> penci-image-holder <?php echo penci_classes_slider_lazy(); ?>"
                                              title="<?php echo wp_strip_all_tags( get_the_title() ); ?>">
                                                <?php echo penci_layout_img( penci_image_srcset( get_the_ID(), penci_featured_images_size() ), get_the_title() ); ?>
                                            </span>

                                        <a href="<?php the_permalink() ?>" class="penci-widget-slider-overlay"
                                           title="<?php the_title(); ?>"></a>
									<?php } else { ?>

                                        <a <?php echo penci_layout_bg( penci_image_srcset( get_the_ID(), penci_featured_images_size() ) ); ?> href="<?php the_permalink() ?>" class="<?php echo penci_layout_bg_class();?> penci-image-holder"
                                           title="<?php echo wp_strip_all_tags( get_the_title() ); ?>">
											<?php echo penci_layout_img( penci_image_srcset( get_the_ID(), penci_featured_images_size() ), get_the_title() ); ?>
                                        </a>

									<?php } ?>
                                    <div class="penci-widget-slide-detail">
                                        <h4>
                                            <a href="<?php the_permalink() ?>" rel="bookmark"
                                               title="<?php the_title(); ?>"><?php echo sanitize_text_field( wp_trim_words( get_the_title(), 8, '...' ) ); ?></a>
                                        </h4>
										<?php if ( ! $date ): ?>
                                            <span class="slide-item-date"><?php penci_soledad_time_link(); ?></span>
										<?php endif; ?>
                                    </div>
                                </div>
                            </div>
						<?php endwhile; ?>
                    </div>
                </div>

			<?php
			endif;
			wp_reset_postdata();

			$attrstyle = '';
			if ( $ptfs ) {
				$attrstyle .= '#penci-postslidewg-' . $rand . ' .penci-widget-slide-detail h4 a{ font-size: ' . $ptfs . 'px; }';
			}
			if ( $pmfs ) {
				$attrstyle .= '#penci-postslidewg-' . $rand . ' .penci-widget-slide-detail .slide-item-date{ font-size: ' . $pmfs . 'px; }';
			}

			if ( $image_type == 'horizontal' ) {
				$attrstyle .= '#penci-postslidewg-' . sanitize_text_field( $rand ) . ' .penci-image-holder:before{ padding-top: 66.6667%; }';
			} elseif ( $image_type == 'square' ) {
				$attrstyle .= '#penci-postslidewg-' . sanitize_text_field( $rand ) . ' .penci-image-holder:before{ padding-top: 100%; }';
			} elseif ( $image_type == 'vertical' ) {
				$attrstyle .= '#penci-postslidewg-' . sanitize_text_field( $rand ) . ' .penci-image-holder:before{ padding-top: 135.4%; }';
			}

			if ( $attrstyle ) {
				echo '<style type="text/css">' . $attrstyle . '</style>';
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
				'title'      => esc_html__( 'Posts Slider', 'soledad' ),
				'autoplay'   => false,
				'ptype'      => '',
				'taxonomy'   => '',
				'tax_ids'    => '',
				'sticky'     => true,
				'orderby'    => 'date',
				'order'      => 'DESC',
				'ptfs'       => '',
				'pmfs'       => '',
				'image_type' => 'default',
				'number'     => 5,
				'offset'     => '',
				'query_cat'  => '',
				'metakey' 	 => '',
				'categories' => '',
				'date'       => false,
				'style'      => 'style-1'
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

			$instance       = wp_parse_args( (array) $instance, $defaults );
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
                       value="<?php echo sanitize_text_field( $instance['title'] ); ?>"/>
            </p>

            <!-- Style -->
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'style' ) ); ?>">Select Style for This
                    Slider</label>
                <select id="<?php echo esc_attr( $this->get_field_id( 'style' ) ); ?>"
                        name="<?php echo esc_attr( $this->get_field_name( 'style' ) ); ?>" class="widefat categories"
                        style="width:100%;">
                    <option value='style-1' <?php if ( 'style-1' == $instance['style'] ): echo 'selected="selected"'; endif; ?>>
                        Style 1
                    </option>
                    <option value='style-2' <?php if ( 'style-2' == $instance['style'] ): echo 'selected="selected"'; endif; ?>>
                        Style 2
                    </option>
                    <option value='style-3' <?php if ( 'style-3' == $instance['style'] ): echo 'selected="selected"'; endif; ?>>
                        Style 3
                    </option>
                </select>
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
                <label for="<?php echo esc_attr( $this->get_field_id( 'query_cat' ) ); ?>"><?php esc_html_e( 'Query Posts Within Current Category?:', 'soledad' ); ?></label>
                <input type="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'query_cat' ) ); ?>"
                       name="<?php echo esc_attr( $this->get_field_name( 'query_cat' ) ); ?>" <?php checked( (bool) $instance['query_cat'], true ); ?> /><br>
                <span class="description"><?php _e( 'When this widget is viewed on a category page, the post query will automatically use the current category\'s slug or ID', 'soledad' ); ?></span>
            </p>

            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'sticky' ) ); ?>"><?php esc_html_e( 'Ignore Sticky Posts?:', 'soledad' ); ?></label>
                <input type="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'sticky' ) ); ?>"
                       name="<?php echo esc_attr( $this->get_field_name( 'sticky' ) ); ?>" <?php checked( (bool) $instance['sticky'], true ); ?> /><br>
                <span class="description"><?php _e( 'Note that: Ignore sticky posts doesn\'t work if you filter your posts by a taxonomy or multiple taxonomies (categories, tags... ) - because it doesn\'t support by WordPress itself.', 'soledad' ); ?></span>
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
                    <option value='popular' <?php if ( 'popular' == $instance['orderby'] ) {
						echo 'selected="selected"';
					} ?>><?php esc_html_e( 'Popular Posts All Time', 'soledad' ); ?></option>
                    <option value='popular7' <?php if ( 'popular7' == $instance['orderby'] ) {
						echo 'selected="selected"';
					} ?>><?php esc_html_e( 'Popular Posts Once Weekly', 'soledad' ); ?></option>
                    <option value='popular_month' <?php if ( 'popular_month' == $instance['orderby'] ) {
						echo 'selected="selected"';
					} ?>><?php esc_html_e( 'Popular Posts Once A Month', 'soledad' ); ?></option>
                    <option value='rand' <?php if ( 'rand' == $instance['orderby'] ) {
						echo 'selected="selected"';
					} ?>><?php esc_html_e( 'Random', 'soledad' ); ?></option>
					<option value='metakey' <?php if ( 'metakey' == $instance['orderby'] ) {
						echo 'selected="selected"';
					} ?>><?php esc_html_e( 'Custom Meta Key', 'soledad' ); ?></option>
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

            <!-- Display post date -->
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'date' ) ); ?>"><?php esc_html_e( 'Hide post date?:', 'soledad' ); ?></label>
                <input type="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'date' ) ); ?>"
                       name="<?php echo esc_attr( $this->get_field_name( 'date' ) ); ?>" <?php checked( (bool) $instance['date'], true ); ?> />
            </p>

            <!-- Disable autoplay -->
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'autoplay' ) ); ?>"><?php esc_html_e( 'Enable slider autoplay?:', 'soledad' ); ?></label>
                <input type="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'autoplay' ) ); ?>"
                       name="<?php echo esc_attr( $this->get_field_name( 'autoplay' ) ); ?>" <?php checked( (bool) $instance['autoplay'], true ); ?> />
            </p>

            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'ptfs' ) ); ?>"><?php esc_html_e( 'Post Title Font Size ( Default: 18 )', 'soledad' ); ?></label>
                <input type="number" class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'ptfs' ) ); ?>"
                       name="<?php echo esc_attr( $this->get_field_name( 'ptfs' ) ); ?>"
                       value="<?php echo esc_attr( $instance['ptfs'] ); ?>" size="3"/>
            </p>

            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'pmfs' ) ); ?>"><?php esc_html_e( 'Post Meta Font Size ( Default: 13 )', 'soledad' ); ?></label>
                <input type="number" class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'pmfs' ) ); ?>"
                       name="<?php echo esc_attr( $this->get_field_name( 'pmfs' ) ); ?>"
                       value="<?php echo esc_attr( $instance['pmfs'] ); ?>" size="3"/>
            </p>

			<?php
		}
	}
}
?>
