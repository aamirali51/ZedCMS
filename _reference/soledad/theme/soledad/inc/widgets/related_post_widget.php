<?php
/**
 * Lastest post widget
 * Get recent posts and display in widget
 *
 * @package Wordpress
 * @since 1.0
 */

add_action( 'widgets_init', 'penci_related_news_load_widget' );

function penci_related_news_load_widget() {
	register_widget( 'penci_related_news_widget' );
}

if ( ! class_exists( 'penci_related_news_widget' ) ) {
	class penci_related_news_widget extends WP_Widget {

		/**
		 * Widget setup.
		 */
		function __construct() {
			/* Widget settings. */
			$widget_ops = array(
				'classname'   => 'penci_related_news_widget',
				'description' => esc_html__( 'A widget that displays your related posts based on currently viewing posts. Note that this widget just appears on single post pages only.', 'soledad' )
			);

			/* Widget control settings. */
			$control_ops = array( 'id_base' => 'penci_related_news_widget' );

			/* Create the widget. */
			global $wp_version;
			if ( 4.3 > $wp_version ) {
				$this->WP_Widget( 'penci_related_news_widget', penci_get_theme_name( '.Soledad', true ) . esc_html__( 'Related Posts', 'soledad' ), $widget_ops, $control_ops );
			} else {
				parent::__construct( 'penci_related_news_widget', penci_get_theme_name( '.Soledad', true ) . esc_html__( 'Related Posts', 'soledad' ), $widget_ops, $control_ops );
			}
		}

		/**
		 * How to display the widget on the screen.
		 */
		function widget( $args, $instance ) {
			extract( $args );

			/* Our variables from the widget settings. */
			$title        = isset( $instance['title'] ) ? $instance['title'] : '';
			$title        = apply_filters( 'widget_title', $title );
			$type         = isset( $instance['type'] ) ? $instance['type'] : 'categories';
			$orderby      = isset( $instance['orderby'] ) ? $instance['orderby'] : 'date';
			$order        = isset( $instance['order'] ) ? $instance['order'] : 'DESC';
			$metakey	  = isset( $instance['metakey'] ) ? $instance['metakey'] : '';
			$number       = isset( $instance['number'] ) ? $instance['number'] : 5;
			$title_length = isset( $instance['title_length'] ) ? $instance['title_length'] : '';
			$dotstyle     = isset( $instance['dotstyle'] ) ? $instance['dotstyle'] : '';
			$movemeta     = isset( $instance['movemeta'] ) ? $instance['movemeta'] : false;
			$featured     = isset( $instance['featured'] ) ? $instance['featured'] : false;
			$twocolumn    = isset( $instance['twocolumn'] ) ? $instance['twocolumn'] : false;
			$featured2    = isset( $instance['featured2'] ) ? $instance['featured2'] : false;
			$allfeatured  = isset( $instance['allfeatured'] ) ? $instance['allfeatured'] : false;
			$thumbright   = isset( $instance['thumbright'] ) ? $instance['thumbright'] : false;
			$postdate     = isset( $instance['postdate'] ) ? $instance['postdate'] : false;
			$icon         = isset( $instance['icon'] ) ? $instance['icon'] : false;
			$image_type   = isset( $instance['image_type'] ) ? $instance['image_type'] : 'default';
			$ptfsfe       = isset( $instance['ptfsfe'] ) ? absint( $instance['ptfsfe'] ) : '';
			$ptfs         = isset( $instance['ptfs'] ) ? absint( $instance['ptfs'] ) : '';
			$pmfs         = isset( $instance['pmfs'] ) ? absint( $instance['pmfs'] ) : '';

			$original_post_id = get_the_ID();

			$args = penci_get_query_related_posts( get_the_ID(), $type, $orderby, $order, $number );

			if ( $metakey && $orderby == 'metakey' ) {
				$args['meta_key'] = $metakey;
				$args['orderby']  = 'meta_value_num';
			}

			if ( is_singular( 'post' ) && ! empty( $args ) ) {

				$loop = new WP_Query( $args );
				if ( $loop->have_posts() ) :

					/* Before widget (defined by themes). */
					echo ent2ncr( $before_widget );

					/* Display the widget title if one was input (before and after defined by themes). */
					if ( $title ) {
						echo ent2ncr( $before_title ) . $title . ent2ncr( $after_title );
					}

					$rand = rand( 1000, 10000 );

					?>
                    <ul id="penci-relatedwg-<?php echo sanitize_text_field( $rand ); ?>"
                        data-settings='<?php echo json_encode( $instance ); ?>' data-paged="1"
                        data-id="<?php echo esc_attr( $original_post_id ); ?>"
                        data-action="penci_related_news_widget_ajax"
                        data-mes="<?php echo penci_get_setting( 'penci_trans_no_more_posts' ); ?>"
                        data-max="<?php echo esc_attr( $loop->max_num_pages ); ?>"
                        class="side-newsfeed<?php if ( $twocolumn && ! $allfeatured ): echo ' penci-feed-2columns';
						    if ( $featured ) {
							    echo ' penci-2columns-featured';
						    } else {
							    echo ' penci-2columns-feed';
						    } endif;
					    if ( $dotstyle ) {
						    echo ' pctlst pctl-' . $dotstyle;
					    } ?>">
						<?php $num = 1;
						while ( $loop->have_posts() ) : $loop->the_post(); ?>
                            <li class="penci-feed<?php if ( ( ( $num == 1 ) && $featured ) || $allfeatured ): echo ' featured-news';
								if ( $featured2 ): echo ' featured-news2'; endif; endif; ?><?php if ( $allfeatured ): echo ' all-featured-news'; endif; ?>">
                                <div class="side-item">

									<?php if ( ( function_exists( 'has_post_thumbnail' ) ) && ( has_post_thumbnail() ) ) : ?>
                                        <div class="side-image<?php if ( $thumbright ): echo ' thumbnail-right'; endif; ?>">
											<?php
											$size_pie = 'small';
											if ( ( ( $num == 1 ) && $featured ) || $allfeatured ): $size_pie = 'normal'; endif;
											do_action( 'penci_bookmark_post', get_the_ID(), $size_pie );
											/* Display Review Piechart  */
											if ( function_exists( 'penci_display_piechart_review_html' ) ) {
												penci_display_piechart_review_html( get_the_ID(), $size_pie );
											}
											$thumb = penci_featured_images_size( 'small' );
											if ( ( ( $num == 1 ) && $featured ) || $allfeatured ): $thumb = penci_featured_images_size(); endif;
											if ( $image_type == 'horizontal' ) {
												$thumb = 'penci-thumb-small';
												if ( ( ( $num == 1 ) && $featured ) || $allfeatured ): $thumb = 'penci-thumb'; endif;
											} elseif ( $image_type == 'square' ) {
												$thumb = 'penci-thumb-square';
											} elseif ( $image_type == 'vertical' ) {
												$thumb = 'penci-thumb-vertical';
											}
											?>

                                            <a <?php echo penci_layout_bg( penci_image_srcset( get_the_ID(), $thumb )); ?> class="<?php echo penci_layout_bg_class();?> penci-image-holder<?php if ( ( ( $num == 1 ) && $featured ) || $allfeatured ) {
												echo '';
											} else {
												echo ' small-fix-size';
											} ?>" rel="bookmark"
                                               href="<?php the_permalink(); ?>"
                                               title="<?php echo wp_strip_all_tags( get_the_title() ); ?>">
												<?php echo penci_layout_img( penci_image_srcset( get_the_ID(), $thumb ), get_the_title() ); ?>
                                            </a>


											<?php if ( $icon ): ?>
												<?php if ( has_post_format( 'video' ) ) : ?>
                                                    <a href="<?php the_permalink() ?>" class="icon-post-format"
                                                       aria-label="Icon"><?php penci_fawesome_icon( 'fas fa-play' ); ?></a>
												<?php endif; ?>
												<?php if ( has_post_format( 'audio' ) ) : ?>
                                                    <a href="<?php the_permalink() ?>" class="icon-post-format"
                                                       aria-label="Icon"><?php penci_fawesome_icon( 'fas fa-music' ); ?></a>
												<?php endif; ?>
												<?php if ( has_post_format( 'link' ) ) : ?>
                                                    <a href="<?php the_permalink() ?>" class="icon-post-format"
                                                       aria-label="Icon"><?php penci_fawesome_icon( 'fas fa-link' ); ?></a>
												<?php endif; ?>
												<?php if ( has_post_format( 'quote' ) ) : ?>
                                                    <a href="<?php the_permalink() ?>" class="icon-post-format"
                                                       aria-label="Icon"><?php penci_fawesome_icon( 'fas fa-quote-left' ); ?></a>
												<?php endif; ?>
												<?php if ( has_post_format( 'gallery' ) ) : ?>
                                                    <a href="<?php the_permalink() ?>" class="icon-post-format"
                                                       aria-label="Icon"><?php penci_fawesome_icon( 'far fa-image' ); ?></a>
												<?php endif; ?>
											<?php endif; ?>
                                        </div>
									<?php endif; ?>
                                    <div class="side-item-text">
										<?php if ( $movemeta && ! $postdate ): ?>
                                            <div class="grid-post-box-meta penci-side-item-meta pcsnmt-above">
                                                <span class="side-item-meta"><?php penci_soledad_time_link(); ?></span>
												<?php do_action( 'penci_extra_meta' ); ?>
                                            </div>
										<?php endif; ?>
                                        <h4 class="side-title-post">
                                            <a href="<?php the_permalink() ?>" rel="bookmark"
                                               title="<?php echo wp_strip_all_tags( get_the_title() ); ?>">
												<?php
												if ( ! $title_length || ! is_numeric( $title_length ) ) {
													if ( $featured2 && ( ( ( $num == 1 ) && $featured ) || $allfeatured ) ) {
														echo wp_trim_words( wp_strip_all_tags( get_the_title() ), 12, '...' );
													} else {
														the_title();
													}
												} else {
													echo wp_trim_words( wp_strip_all_tags( get_the_title() ), $title_length, '...' );
												}
												?>
                                            </a>
                                        </h4>
										<?php if ( ! $movemeta && ! $postdate ): ?>
                                            <div class="grid-post-box-meta penci-side-item-meta pcsnmt-below">
                                                <span class="side-item-meta"><?php penci_soledad_time_link(); ?></span>
												<?php do_action( 'penci_extra_meta' ); ?>
                                            </div>
										<?php endif; ?>
                                    </div>
                                </div>
                            </li>
							<?php $num ++; endwhile; ?>
                    </ul>

					<?php

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
                                        class="pcnav-title"><?php echo penci_get_setting( 'penci_trans_next' ) . '</span> ' . penci_icon_by_ver( 'penciicon-right-chevron' ); ?></span>
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
						$attrstyle .= '.widget ul#penci-relatedwg-' . $rand . ' li.featured-news .side-item .side-item-text h4 a{ font-size: ' . $ptfsfe . 'px; }';
					}
					if ( $ptfs ) {
						$attrstyle .= '.widget ul#penci-relatedwg-' . $rand . ' li:not(.featured-news) .side-item .side-item-text h4 a{ font-size: ' . $ptfs . 'px; }';
					}
					if ( $pmfs ) {
						$attrstyle .= '.widget ul#penci-relatedwg-' . $rand . ' li .side-item .side-item-text .side-item-meta{ font-size: ' . $pmfs . 'px; }';
					}
					if ( $image_type == 'horizontal' ) {
						$attrstyle .= '#penci-relatedwg-' . $rand . ' .penci-image-holder:before{ padding-top: 66.6667%; }';
					} elseif ( $image_type == 'square' ) {
						$attrstyle .= '#penci-relatedwg-' . $rand . ' .penci-image-holder:before{ padding-top: 100%; }';
					} elseif ( $image_type == 'vertical' ) {
						$attrstyle .= '#penci-relatedwg-' . $rand . ' .penci-image-holder:before{ padding-top: 135.4%; }';
					}

					if ( $attrstyle ) {
						echo '<style type="text/css">' . $attrstyle . '</style>';
					}

					/* After widget (defined by themes). */
					echo ent2ncr( $after_widget );

					wp_reset_postdata();
				endif;

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
			$defaults = array(
				'title'        => esc_html__( 'Related Posts', 'soledad' ),
				'ptfsfe'       => '',
				'ptfs'         => '',
				'pmfs'         => '',
				'image_type'   => 'default',
				'type'         => 'categories',
				'orderby'      => 'date',
				'order'        => 'DESC',
				'title_length' => '',
				'number'       => 5,
				'featured'     => false,
				'movemeta'     => false,
				'dotstyle'     => '',
				'ajaxnav'      => '',
				'metakey'      => '',
				'allfeatured'  => false,
				'thumbright'   => false,
				'twocolumn'    => false,
				'featured2'    => false,
				'postdate'     => false,
				'icon'         => false
			);

			return $defaults;
		}


		function form( $instance ) {

			/* Set up some default widget settings. */
			$defaults       = $this->soledad_widget_defaults();
			$instance       = wp_parse_args( (array) $instance, $defaults );
			$instance_title = $instance['title'] ? str_replace( '"', '&quot;', $instance['title'] ) : '';
			?>

            <br>
            <p><span style="color: #ff0000;">Note Important:</span> This widget just appears on single post pages only.
            </p>

            <!-- Widget Title: Text Input -->
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'soledad' ); ?></label>
                <input type="text" class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"
                       name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>"
                       value="<?php echo $instance_title; ?>"/>
            </p>

            <!-- Display related posts by -->
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'type' ) ); ?>"><?php esc_html_e( 'Display Related Posts By:', 'soledad' ); ?></label>
                <select id="<?php echo esc_attr( $this->get_field_id( 'type' ) ); ?>"
                        name="<?php echo esc_attr( $this->get_field_name( 'type' ) ); ?>" class="widefat type"
                        style="width:100%;">
                    <option value='categories' <?php if ( 'categories' == $instance['type'] ) {
						echo 'selected="selected"';
					} ?>><?php esc_html_e( 'Categories', 'soledad' ); ?></option>
                    <option value='tags' <?php if ( 'tags' == $instance['type'] ) {
						echo 'selected="selected"';
					} ?>><?php esc_html_e( 'Tags', 'soledad' ); ?></option>
                    <option value='primary_cat' <?php if ( 'primary_cat' == $instance['type'] ) {
						echo 'selected="selected"';
					} ?>><?php esc_html_e( 'Primary Category from Yoast SEO Plugin', 'soledad' ); ?></option>
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

            <!-- Custom trim post titles -->
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'title_length' ) ); ?>"><?php esc_html_e( 'Custom words length for post titles:', 'soledad' ); ?></label>
                <input type="text" class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title_length' ) ); ?>"
                       name="<?php echo esc_attr( $this->get_field_name( 'title_length' ) ); ?>"
                       value="<?php echo esc_attr( $instance['title_length'] ); ?>" size="3"/>
                <span class="description" style="display: block; padding: 0;font-size: 12px;">If your post titles is too long - You can use this option for trim it. Fill number value here.</span>
            </p>

            <!-- Timeline dots style -->
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'dotstyle' ) ); ?>"><?php esc_html_e( 'Show Timeline Dots?', 'soledad' ); ?></label>
                <select id="<?php echo esc_attr( $this->get_field_id( 'dotstyle' ) ); ?>"
                        name="<?php echo esc_attr( $this->get_field_name( 'dotstyle' ) ); ?>" class="widefat orderby"
                        style="width:100%;">
                    <option value='' <?php if ( '' == $instance['dotstyle'] ) {
						echo 'selected="selected"';
					} ?>><?php esc_html_e( "Don't Show", 'soledad' ); ?></option>
                    <option value='s1' <?php if ( 's1' == $instance['dotstyle'] ) {
						echo 'selected="selected"';
					} ?>><?php esc_html_e( 'Show with Color Style', 'soledad' ); ?></option>
                    <option value='s2' <?php if ( 's2' == $instance['dotstyle'] ) {
						echo 'selected="selected"';
					} ?>><?php esc_html_e( 'Show with Hover Style', 'soledad' ); ?></option>
                    <option value='s3' <?php if ( 's3' == $instance['dotstyle'] ) {
						echo 'selected="selected"';
					} ?>><?php esc_html_e( 'Show with Animation Style', 'soledad' ); ?></option>
                </select>
            </p>

            <!-- Move post meta -->
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'movemeta' ) ); ?>"><?php esc_html_e( 'Move post meta to display above post title?', 'soledad' ); ?></label>
                <input type="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'movemeta' ) ); ?>"
                       name="<?php echo esc_attr( $this->get_field_name( 'movemeta' ) ); ?>" <?php checked( (bool) $instance['movemeta'], true ); ?> />
            </p>

            <!-- Display thumbnail right -->
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'thumbright' ) ); ?>"><?php esc_html_e( 'Display thumbnail on right?:', 'soledad' ); ?></label>
                <input type="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'thumbright' ) ); ?>"
                       name="<?php echo esc_attr( $this->get_field_name( 'thumbright' ) ); ?>" <?php checked( (bool) $instance['thumbright'], true ); ?> />
            </p>

            <!-- 2 Columns -->
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'twocolumn' ) ); ?>"><?php esc_html_e( 'Display on 2 columns?:', 'soledad' ); ?></label>
                <input type="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'twocolumn' ) ); ?>"
                       name="<?php echo esc_attr( $this->get_field_name( 'twocolumn' ) ); ?>" <?php checked( (bool) $instance['twocolumn'], true ); ?> />
                <span class="description" style="display: block; padding: 0;font-size: 12px;">If you use 2 columns option, it will ignore option display thumbnail on right.</span>
            </p>

            <!-- Display latest post featured -->
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'featured' ) ); ?>"><?php esc_html_e( 'Display 1st post featured?:', 'soledad' ); ?></label>
                <input type="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'featured' ) ); ?>"
                       name="<?php echo esc_attr( $this->get_field_name( 'featured' ) ); ?>" <?php checked( (bool) $instance['featured'], true ); ?> />
            </p>

            <!-- Display latest post featured style 2 -->
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'featured2' ) ); ?>"><?php esc_html_e( 'Display featured post style 2?:', 'soledad' ); ?></label>
                <input type="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'featured2' ) ); ?>"
                       name="<?php echo esc_attr( $this->get_field_name( 'featured2' ) ); ?>" <?php checked( (bool) $instance['featured2'], true ); ?> />
            </p>

            <!-- Display big post -->
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'allfeatured' ) ); ?>"><?php esc_html_e( 'Display all post featured?:', 'soledad' ); ?></label>
                <input type="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'allfeatured' ) ); ?>"
                       name="<?php echo esc_attr( $this->get_field_name( 'allfeatured' ) ); ?>" <?php checked( (bool) $instance['allfeatured'], true ); ?> />
                <span class="description" style="display: block; padding: 0;font-size: 12px;">If you use all post featured option, it will ignore option display thumbnail on right & 2 columns.</span>
            </p>

            <!-- Hide post date -->
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'postdate' ) ); ?>"><?php esc_html_e( 'Hide post date?:', 'soledad' ); ?></label>
                <input type="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'postdate' ) ); ?>"
                       name="<?php echo esc_attr( $this->get_field_name( 'postdate' ) ); ?>" <?php checked( (bool) $instance['postdate'], true ); ?> />
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

            <!-- Enable post format icon -->
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'icon' ) ); ?>"><?php esc_html_e( 'Show icon post format?:', 'soledad' ); ?></label>
                <input type="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'icon' ) ); ?>"
                       name="<?php echo esc_attr( $this->get_field_name( 'icon' ) ); ?>" <?php checked( (bool) $instance['icon'], true ); ?> />
            </p>

            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'ptfsfe' ) ); ?>"><?php esc_html_e( 'Post Title Font Size on Featured Posts ( Default: 18 )', 'soledad' ); ?></label>
                <input type="number" class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'ptfsfe' ) ); ?>"
                       name="<?php echo esc_attr( $this->get_field_name( 'ptfsfe' ) ); ?>"
                       value="<?php echo esc_attr( $instance['ptfsfe'] ); ?>" size="3"/>
            </p>

            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'ptfs' ) ); ?>"><?php esc_html_e( 'Post Title Font Size ( Default: 16 )', 'soledad' ); ?></label>
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
