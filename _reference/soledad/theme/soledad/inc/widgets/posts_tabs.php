<?php

add_action( 'widgets_init', 'penci_posts_tabs_load_widget' );

function penci_posts_tabs_load_widget() {
	register_widget( 'penci_posts_tabs_widget' );
}

if ( ! class_exists( 'penci_posts_tabs_widget' ) ) {
	class penci_posts_tabs_widget extends WP_Widget {

		/**
		 * Widget setup.
		 */
		function __construct() {
			/* Widget settings. */
			$widget_ops = array(
				'classname'   => 'penci_posts_tabs_widget',
				'description' => esc_html__( 'A widget that displays a tabs with listing posts.', 'soledad' )
			);

			/* Widget control settings. */
			$control_ops = array( 'width' => 250, 'height' => 350, 'id_base' => 'penci_posts_tabs_widget' );

			/* Create the widget. */ global $wp_version;
			if ( 4.3 > $wp_version ) {
				$this->WP_Widget( 'penci_posts_tabs_widget', penci_get_theme_name( '.Soledad', true ) . esc_html__( 'Tabs', 'soledad' ), $widget_ops, $control_ops );
			} else {
				parent::__construct( 'penci_posts_tabs_widget', penci_get_theme_name( '.Soledad', true ) . esc_html__( 'Tabs', 'soledad' ), $widget_ops, $control_ops );
			}
		}

		/**
		 * How to display the widget on the screen.
		 */
		function widget( $args, $instance ) {
			extract( $args );

			/* Our variables from the widget settings. */
			$title           = isset( $instance['title'] ) ? $instance['title'] : '';
			$title           = apply_filters( 'widget_title', $title );
			$sticky          = isset( $instance['sticky'] ) ? $instance['sticky'] : true;
			$tabs_order      = isset( $instance['tabs_order'] ) ? $instance['tabs_order'] : 'recent_popular_comments';
			$tabs_style      = isset( $instance['tabs_style'] ) ? $instance['tabs_style'] : 'default';
			$number          = isset( $instance['number'] ) ? $instance['number'] : 5;
			$number_comments = isset( $instance['number_comments'] ) ? $instance['number_comments'] : 5;
			$sticky_value    = ( false == $sticky ) ? 0 : 1;
			$title_length    = isset( $instance['title_length'] ) ? $instance['title_length'] : '';
			$featured        = isset( $instance['featured'] ) ? $instance['featured'] : false;
			$dotstyle        = isset( $instance['dotstyle'] ) ? $instance['dotstyle'] : '';
			$movemeta        = isset( $instance['movemeta'] ) ? $instance['movemeta'] : false;
			$twocolumn       = isset( $instance['twocolumn'] ) ? $instance['twocolumn'] : false;
			$featured2       = isset( $instance['featured2'] ) ? $instance['featured2'] : false;
			$ordernum        = isset( $instance['ordernum'] ) ? $instance['ordernum'] : false;
			$allfeatured     = isset( $instance['allfeatured'] ) ? $instance['allfeatured'] : false;
			$thumbright      = isset( $instance['thumbright'] ) ? $instance['thumbright'] : false;
			$postdate        = isset( $instance['postdate'] ) ? $instance['postdate'] : false;
			$icon            = isset( $instance['icon'] ) ? $instance['icon'] : false;
			$image_type      = isset( $instance['image_type'] ) ? $instance['image_type'] : 'default';
			$ptfsfe          = isset( $instance['ptfsfe'] ) ? absint( $instance['ptfsfe'] ) : '';
			$ptfs            = isset( $instance['ptfs'] ) ? absint( $instance['ptfs'] ) : '';
			$pmfs            = isset( $instance['pmfs'] ) ? absint( $instance['pmfs'] ) : '';
			$ptorn           = isset( $instance['ptorn'] ) ? absint( $instance['ptorn'] ) : '';
			$oboxsize        = isset( $instance['oboxsize'] ) ? absint( $instance['oboxsize'] ) : '';
			$row_gap         = isset( $instance['row_gap'] ) ? absint( $instance['row_gap'] ) : '';
			$imgwidth        = isset( $instance['imgwidth'] ) ? absint( $instance['imgwidth'] ) : '';
			$hide_thumb      = isset( $instance['hide_thumb'] ) ? $instance['hide_thumb'] : false;
			$tabs_icon       = isset( $instance['tabs_icon'] ) ? $instance['tabs_icon'] : false;
			$showauthor      = isset( $instance['show_author'] ) ? $instance['show_author'] : false;
			$showcomment     = isset( $instance['show_comment'] ) ? $instance['show_comment'] : false;
			$showviews       = isset( $instance['show_postviews'] ) ? $instance['show_postviews'] : false;
			$showborder      = isset( $instance['showborder'] ) ? $instance['showborder'] : false;


			/* Before widget (defined by themes). */
			echo ent2ncr( $before_widget );

			/* Display the widget title if one was input (before and after defined by themes). */
			if ( $title ) {
				echo ent2ncr( $before_title ) . $title . ent2ncr( $after_title );
			}
			wp_enqueue_script( 'penci_widget_tabs' );
			$tabs_order = explode( '_', $tabs_order );
			$rand       = rand( 1000, 10000 );
			$class      = $tabs_icon ? 'show-icon' : 'hide-icon';
			$class      .= $tabs_style ? ' box-tabs' : ' default-tabs';
			?>
            <div class="penc-posts-tabs <?php echo esc_attr( $class ); ?>" id="pc-wpt-<?php echo $rand; ?>">
                <div class="tabs">
                    <ul>
						<?php $count = 1;
						foreach ( $tabs_order as $tab ) {
							if ( isset( $instance[ 'disable_' . $tab ] ) && ! $instance[ 'disable_' . $tab ] ) {
								$class = $count == 1 ? ' active' : '';
								echo '<li class="li-tab-' . $tab . $class . '" data-tab="tab-' . $tab . '"><a href="#" aria-label="Tab">' . penci_get_setting( 'penci_trans_' . $tab ) . '</a></li>';
								$count ++;
							}
						} ?>
                    </ul>
                </div>
                <div class="tabs-content">
					<?php $tcount = 1;
					foreach ( $tabs_order as $tab ) {
						$check = $tcount == 1;
						if ( isset( $instance[ 'disable_' . $tab ] ) && ! $instance[ 'disable_' . $tab ] ) {
							if ( $tab == 'popular' || $tab == 'recent' ) {
								$this->show_tabs_posts( $instance, $tab, $check );
							} else {
								$this->show_comment_posts( $instance, $number_comments, $check );
							}
						}
						$tcount ++;
					} ?>
                </div>
            </div>

			<?php

			$attrstyle = '';
			if ( $ptfsfe ) {
				$attrstyle .= '.widget #pc-wpt-' . $rand . ' ul.penci-wdtab-ct li.featured-news .side-item .side-item-text h4 a{ font-size: ' . $ptfsfe . 'px; }';
			}
			if ( $ptfs ) {
				$attrstyle .= '.widget #pc-wpt-' . $rand . ' ul.penci-wdtab-ct li:not(.featured-news) .side-item .side-item-text h4 a{ font-size: ' . $ptfs . 'px; }';
			}
			if ( $pmfs ) {
				$attrstyle .= '.widget #pc-wpt-' . $rand . ' ul.penci-wdtab-ct li .side-item .side-item-text .side-item-meta{ font-size: ' . $pmfs . 'px; }';
			}
			if ( $imgwidth ) {
				$attrstyle .= '.widget #pc-wpt-' . $rand . ' ul.penci-wdtab-ct li .penci-image-holder.small-fix-size{ width: ' . $imgwidth . 'px; }';
			}
			if ( $row_gap ) {
				$attrstyle .= '.widget #pc-wpt-' . $rand . ' ul.penci-wdtab-ct.side-newsfeed:not(.penci-feed-2columns) li{ margin-bottom: ' . $row_gap . 'px; padding-bottom: ' . $row_gap . 'px; }';
				$attrstyle .= '.widget #pc-wpt-' . $rand . ' ul.penci-wdtab-ct.penci-feed-2columns li{ margin-bottom: ' . $row_gap . 'px; }';
			}
			if ( $image_type == 'horizontal' ) {
				$attrstyle .= '#pc-wpt-' . $rand . ' .penci-wdtab-ct .penci-image-holder:before{ padding-top: 66.6667%; }';
			} elseif ( $image_type == 'square' ) {
				$attrstyle .= '#pc-wpt-' . $rand . ' .penci-wdtab-ct .penci-image-holder:before{ padding-top: 100%; }';
			} elseif ( $image_type == 'vertical' ) {
				$attrstyle .= '#pc-wpt-' . $rand . ' .penci-wdtab-ct .penci-image-holder:before{ padding-top: 135.4%; }';
			}

			if ( $ptorn ) {
				$attrstyle .= '.widget #pc-wpt-' . $rand . ' ul.order-numbers-4.display-order-numbers li .side-image:before{ font-size: ' . $ptorn . 'px; }';
				$attrstyle .= '.widget #pc-wpt-' . $rand . ' li .number-post{ font-size: ' . $ptorn . 'px; }';
				$attrstyle .= '.widget #pc-wpt-' . $rand . ' ul.order-numbers-3 li .number-post{ font-size: ' . $ptorn . 'px; }';
				$attrstyle .= '.widget #pc-wpt-' . $rand . ' ul.order-numbers-2.display-order-numbers li .side-item-text:before{ font-size: ' . $ptorn . 'px; }';
			}

			if ( $oboxsize ) {
				$attrstyle .= '.widget #pc-wpt-' . $rand . ' ul.side-newsfeed{ --oboxsize: ' . $oboxsize . 'px; }';
				$attrstyle .= '.widget #pc-wpt-' . $rand . ' ul.side-newsfeed.order-numbers-1 li .order-border-number{ width: ' . $oboxsize . 'px;height: ' . $oboxsize . 'px;line-height: ' . ( $oboxsize - 2 ) . 'px; }';
				$attrstyle .= '.widget #pc-wpt-' . $rand . ' ul.side-newsfeed.order-numbers-1 li .number-post{ width: ' . $oboxsize . 'px;height: ' . $oboxsize . 'px;line-height: ' . ( $oboxsize - 2 ) . 'px; }';
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
			$instance      = $old_instance;
			$data_instance = $this->soledad_widget_defaults();
			foreach ( $data_instance as $data => $value ) {
				$instance[ $data ] = ! empty( $new_instance[ $data ] ) ? $new_instance[ $data ] : '';
			}

			return $instance;
		}

		public function show_comment_posts( $instance, $number = 5, $is_active = false ) {
			$comments_query     = new WP_Comment_Query( [
				'number' => $number,
				'status' => 'approve',
			] );
			$comments           = $comments_query->comments;
			$class              = $is_active ? 'active' : 'inactive';
			$comments_count     = wp_count_comments();
			$comments_max_pages = $comments_count->approved > $number ? ceil( $comments_count->approved / $number ) : 1;
			if ( $comments ) :
				?>
                <div class="tab-content-wrapper tab-comments recent-comments <?php echo $class; ?>">
                    <ul data-max="<?php echo $comments_max_pages; ?>" data-paged="1"
                        data-mes="<?php echo penci_get_setting( 'penci_trans_no_more_comments' ); ?>"
                        data-action="penci_posts_tabs_widget_comments_ajax">
						<?php foreach ( $comments as $comment ) {
							if ( isset( $comment->comment_author_email ) && $comment->comment_author_email ) {
								$usergravatar = 'http://www.gravatar.com/avatar/' . md5( $comment->comment_author_email ) . '?s=70';
							} else {
								$usergravatar = get_avatar_url( $comment->user_id );
							}
							echo '<li>
						        <a href="' . get_author_posts_url( $comment->user_id ) . '" class="avatar"><img src="' . $usergravatar . '" alt=""></a>
						        <div class="author-info"><a href="' . get_author_posts_url( $comment->user_id ) . '">' . $comment->comment_author . '</a> on <a href="' . get_permalink( $comment->comment_post_ID ) . '">' . get_the_title( $comment->comment_post_ID ) . '</a></div>
						     </li>';
						} ?>
                    </ul>
					<?php
					if ( isset( $instance['ajaxnav'] ) && $instance['ajaxnav'] == 'btn' ) {
						?>
                        <div class="penci-pagination penci-ajax-more pcwg-lposts">
                            <a class="penci-ajax-more-button pc-tabsajax-btn penci-wgajx-btn"
                               href="#" aria-label="More Posts">
                                <span class="ajax-more-text"><?php echo penci_get_setting( 'penci_trans_load_more_comments' ); ?></span><span
                                        class="ajaxdot"></span><i
                                        class="penci-faicon fa fa-refresh"></i> </a>
                        </div>
						<?php
					} else if ( isset( $instance['ajaxnav'] ) && $instance['ajaxnav'] == 'nav' ) { ?>
                        <div class="penci-pagination penci-ajax-nav pcwg-lposts">
                            <span class="pcajx-btn penci-wgajx-btn pc-tabsajax-btn prev disable"><?php echo penci_icon_by_ver( 'penciicon-left-chevron' ) . ' <span class="pcnav-title">' . penci_get_setting( 'penci_trans_back' ); ?></span></span>
                            <span class="pcajx-btn penci-wgajx-btn pc-tabsajax-btn next"><?php echo '<span class="pcnav-title">' . penci_get_setting( 'penci_trans_next' ) . '</span>' . penci_icon_by_ver( 'penciicon-right-chevron' ); ?></span>
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
					?>
                </div>
			<?php
			else:
				echo '<div class="tab-content-wrapper tab-comments recent-comments ' . $class . '">';
				echo '<p>' . penci_get_setting( 'penci_trans_no_comments' ) . '</p>';
				echo '</div>';
			endif;
		}

		public function show_tabs_posts( $instance, $type, $first = false ) {
			$sticky         = isset( $instance['sticky'] ) ? $instance['sticky'] : true;
			$sticky_value   = ( false == $sticky ) ? 0 : 1;
			$popular_order  = isset( $instance['popular_order'] ) ? $instance['popular_order'] : 'all';
			$number         = isset( $instance['number'] ) ? $instance['number'] : '5';
			$offset         = isset( $instance['offset'] ) ? $instance['offset'] : '';
			$title_length   = isset( $instance['title_length'] ) ? $instance['title_length'] : '';
			$featured       = isset( $instance['featured'] ) ? $instance['featured'] : false;
			$dotstyle       = isset( $instance['dotstyle'] ) ? $instance['dotstyle'] : '';
			$movemeta       = isset( $instance['movemeta'] ) ? $instance['movemeta'] : false;
			$twocolumn      = isset( $instance['twocolumn'] ) ? $instance['twocolumn'] : false;
			$featured2      = isset( $instance['featured2'] ) ? $instance['featured2'] : false;
			$ordernum       = isset( $instance['ordernum'] ) ? $instance['ordernum'] : false;
			$allfeatured    = isset( $instance['allfeatured'] ) ? $instance['allfeatured'] : false;
			$thumbright     = isset( $instance['thumbright'] ) ? $instance['thumbright'] : false;
			$postdate       = isset( $instance['postdate'] ) ? $instance['postdate'] : false;
			$icon           = isset( $instance['icon'] ) ? $instance['icon'] : false;
			$image_type     = isset( $instance['image_type'] ) ? $instance['image_type'] : 'default';
			$hide_thumb     = isset( $instance['hide_thumb'] ) ? $instance['hide_thumb'] : false;
			$showauthor     = isset( $instance['show_author'] ) ? $instance['show_author'] : false;
			$showcomment    = isset( $instance['show_comment'] ) ? $instance['show_comment'] : false;
			$showviews      = isset( $instance['show_postviews'] ) ? $instance['show_postviews'] : false;
			$showborder     = isset( $instance['showborder'] ) ? $instance['showborder'] : false;
			$ordernum_style = isset( $instance['ordernum_style'] ) ? $instance['ordernum_style'] : '1';
			$rand           = rand( 1000, 10000 );
			$query          = array(
				'meta_key'            => penci_get_postviews_key(),
				'orderby'             => 'meta_value_num',
				'order'               => 'DESC',
				'posts_per_page'      => $number,
				'post_type'           => 'post',
				'ignore_sticky_posts' => $sticky_value
			);

			if ( $popular_order == 'week' ) {
				$query = array(
					'posts_per_page' => $number,
					'meta_key'       => 'penci_post_week_views_count',
					'orderby'        => 'meta_value_num',
					'order'          => 'DESC',
				);
			} elseif ( $popular_order == 'month' ) {
				$query = array(
					'posts_per_page' => $number,
					'meta_key'       => 'penci_post_month_views_count',
					'orderby'        => 'meta_value_num',
					'order'          => 'DESC',
				);
			} elseif ( $popular_order == 'jetpack' ) {
				$query = array(
					'posts_per_page' => $number,
					'meta_key'       => '_jetpack_post_view',
					'orderby'        => 'meta_value_num',
					'order'          => 'DESC',
				);
			}
			if ( $offset ) {
				$query['offset'] = $offset;
			}

			if ( $type == 'recent' ) {
				$query    = array(
					'order'               => 'DESC',
					'posts_per_page'      => $number,
					'post_type'           => 'post',
					'ignore_sticky_posts' => $sticky_value
				);
				$ordernum = isset( $instance['ordernum_recent'] ) ? $instance['ordernum_recent'] : true;
			}

			$loop  = new WP_Query( $query );
			$class = $first ? 'active' : 'inactive';
			?>
            <div class="tab-content-wrapper tab-<?php echo esc_attr( $type . ' ' . $class ); ?>">
                <ul data-settings='<?php echo json_encode( $instance ); ?>'
                    data-paged="1"
                    data-type="<?php echo esc_attr( $type ); ?>"
                    data-action="penci_posts_tabs_widget_ajax"
                    data-mes="<?php echo penci_get_setting( 'penci_trans_no_more_posts' ); ?>"
                    data-max="<?php echo esc_attr( $loop->max_num_pages ); ?>"
                    class="penci-wdtab-ct side-newsfeed<?php if ( $twocolumn && ! $allfeatured ): echo ' penci-feed-2columns';
					    if ( $featured ) {
						    echo ' penci-2columns-featured';
					    } else {
						    echo ' penci-2columns-feed';
					    } endif; ?><?php if ( ! $ordernum ): echo ' display-order-numbers'; endif;
				    if ( $dotstyle ) {
					    echo ' pctlst pctl-' . $dotstyle;
				    }
				    if ( $showborder ) {
					    echo ' penci-rcpw-hborders';
				    } 
					echo ' order-numbers-' . $ordernum_style;
					?>">

					<?php $num = 1;
					while ( $loop->have_posts() ) : $loop->the_post(); ?>

                        <li class="penci-feed<?php if ( ( ( $num == 1 ) && $featured ) || $allfeatured ): echo ' featured-news';
							if ( $featured2 ): echo ' featured-news2'; endif; endif; ?><?php if ( $allfeatured ): echo ' all-featured-news'; endif; ?>">
							<?php if ( ! $ordernum && has_post_thumbnail() && ! $hide_thumb ): ?>
                                <span class="order-border-number<?php if ( $thumbright && ! $twocolumn ): echo ' right-side'; endif; ?>">
									<span class="number-post"><?php echo sanitize_text_field( $num ); ?></span>
								</span>
							<?php endif; ?>
                            <div class="side-item">
								<?php if ( ( function_exists( 'has_post_thumbnail' ) ) && ( has_post_thumbnail() ) && ! $hide_thumb ) : ?>
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

                                        <a <?php echo penci_layout_bg( penci_image_srcset( get_the_ID(), $thumb ) ); ?>
                                                class="<?php echo penci_layout_bg_class(); ?> penci-image-holder<?php if ( ( ( $num == 1 ) && $featured ) || $allfeatured ) {
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
									<?php if ( $movemeta && ( ! $postdate || $showauthor || $showcomment || $showviews ) ): ?>
                                        <div class="grid-post-box-meta penci-side-item-meta pcsnmt-above">
											<?php if ( $showauthor ): ?>
                                                <span class="side-item-meta side-wauthor"><?php echo penci_get_setting( 'penci_trans_by' ); ?> <a
                                                            class="url fn n"
                                                            href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>"><?php the_author(); ?></a></span>
											<?php endif; ?>
											<?php if ( ! $postdate ): ?>
                                                <span class="side-item-meta side-wdate"><?php penci_soledad_time_link(); ?></span>
											<?php endif; ?>
											<?php if ( $showcomment ): ?>
                                                <span class="side-item-meta side-wcomments"><a
                                                            href="<?php comments_link(); ?> "><?php comments_number( '0 ' . penci_get_setting( 'penci_trans_comments' ), '1 ' . penci_get_setting( 'penci_trans_comment' ), '% ' . penci_get_setting( 'penci_trans_comments' ) ); ?></a></span>
											<?php endif; ?>
											<?php if ( $showviews ): ?>
                                                <span class="side-item-meta side-wviews"><?php echo penci_get_post_views( get_the_ID() ) . ' ' . penci_get_setting( 'penci_trans_countviews' ); ?></span>
											<?php endif; ?>
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
									<?php if ( ! $movemeta && ( ! $postdate || $showauthor || $showcomment || $showviews ) ): ?>
                                        <div class="grid-post-box-meta penci-side-item-meta pcsnmt-below">
											<?php if ( $showauthor ): ?>
                                                <span class="side-item-meta side-wauthor"><?php echo penci_get_setting( 'penci_trans_by' ); ?> <a
                                                            class="url fn n"
                                                            href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>"><?php the_author(); ?></a></span>
											<?php endif; ?>
											<?php if ( ! $postdate ): ?>
                                                <span class="side-item-meta side-wdate"><?php penci_soledad_time_link(); ?></span>
											<?php endif; ?>
											<?php if ( $showcomment ): ?>
                                                <span class="side-item-meta side-wcomments"><a
                                                            href="<?php comments_link(); ?> "><?php comments_number( '0 ' . penci_get_setting( 'penci_trans_comments' ), '1 ' . penci_get_setting( 'penci_trans_comment' ), '% ' . penci_get_setting( 'penci_trans_comments' ) ); ?></a></span>
											<?php endif; ?>
											<?php if ( $showviews ): ?>
                                                <span class="side-item-meta side-wviews"><?php echo penci_get_post_views( get_the_ID() ) . ' ' . penci_get_setting( 'penci_trans_countviews' ); ?></span>
											<?php endif; ?>
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
                        <a class="penci-ajax-more-button penci-wgajx-btn pc-tabsajax-btn"
                           href="#" aria-label="More Posts">
                            <span class="ajax-more-text"><?php echo penci_get_setting( 'penci_trans_load_more_posts' ); ?></span><span
                                    class="ajaxdot"></span><i
                                    class="penci-faicon fa fa-refresh"></i> </a>
                    </div>
					<?php
				} else if ( isset( $instance['ajaxnav'] ) && $instance['ajaxnav'] == 'nav' ) { ?>
                    <div class="penci-pagination penci-ajax-nav pcwg-lposts">
                        <span class="pcajx-btn pc-tabsajax-btn penci-wgajx-btn prev disable"><?php echo penci_icon_by_ver( 'penciicon-left-chevron' ) . ' <span class="pcnav-title">' . penci_get_setting( 'penci_trans_back' ); ?></span></span>
                        <span class="pcajx-btn pc-tabsajax-btn penci-wgajx-btn next"><span
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
				?>
            </div>
			<?php
		}

		public function soledad_widget_defaults() {
			return array(
				'title'            => esc_html__( 'Tabs', 'soledad' ),
				'tabs_order'       => 'recent_popular_comments',
				'tabs_style'       => 'default',
				'popular_order'    => 'all',
				'number'           => 5,
				'number_comments'  => 5,
				'tabs_icon'        => '',
				'disable_popular'  => '',
				'disable_recent'   => '',
				'disable_comments' => '',
				'sticky'           => true,
				'show_author'      => false,
				'show_comment'     => false,
				'show_postviews'   => false,
				'showborder'       => false,
				'row_gap'          => '',
				'imgwidth'         => '',
				'ptfsfe'           => '',
				'ptfs'             => '',
				'ptorn'            => '',
				'oboxsize'         => '',
				'pmfs'             => '',
				'image_type'       => 'default',
				'type'             => '',
				'title_length'     => '',
				'offset'           => '',
				'categories'       => '',
				'dotstyle'         => '',
				'movemeta'         => '',
				'hide_thumb'       => '',
				'ajaxnav'          => '',
				'ordernum_style'   => '',
				'featured'         => false,
				'allfeatured'      => false,
				'thumbright'       => false,
				'twocolumn'        => false,
				'featured2'        => false,
				'ordernum'         => false,
				'ordernum_recent'  => true,
				'postdate'         => false,
				'icon'             => false
			);
		}


		function form( $instance ) {

			/* Set up some default widget settings. */
			$defaults = $this->soledad_widget_defaults();
			$instance = wp_parse_args( (array) $instance, $defaults );

			$instance_title = $instance['title'] ? str_replace( '"', '&quot;', $instance['title'] ) : '';
			$tabs_order     = $instance['tabs_order'] ? $instance['tabs_order'] : 'recent_popular_comments';
			$tabs_style     = $instance['tabs_style'] ? $instance['tabs_style'] : 'default';
			$popular_order  = $instance['popular_order'] ? $instance['popular_order'] : 'all';
			?>

            <!-- Widget Title: Text Input -->
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'soledad' ); ?></label>
                <input type="text" class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"
                       name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>"
                       value="<?php echo $instance_title; ?>"/>
            </p>

            <!--<p>
                <label for="<?php /*echo esc_attr( $this->get_field_id( 'tabs_icon' ) ); */ ?>"><?php /*esc_html_e( 'Use icons:', 'soledad' ); */ ?></label>
                <input type="checkbox" id="<?php /*echo esc_attr( $this->get_field_id( 'tabs_icon' ) ); */ ?>"
                       name="<?php /*echo esc_attr( $this->get_field_name( 'tabs_icon' ) ); */ ?>" <?php /*checked( (bool) $instance['tabs_icon'], true ); */ ?> />
            </p>-->

			<?php
			$list_oders = [
				'recent_popular_comments' => 'Recent / Popular / Comments',
				'recent_comments_popular' => 'Recent / Comments / Popular',
				'popular_recent_comments' => 'Popular / Recent / Comments',
				'popular_comments_recent' => 'Popular / Comments / Recent ',
				'comments_popular_recent' => 'Comments / Popular / Recent ',
				'comments_recent_popular' => 'Comments / Recent / Popular ',
			];
			$list_style = [
				'default' => 'Default Theme Style',
				'box'     => 'Box Tabs',
			]
			?>

            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'tabs_order' ) ); ?>"><?php esc_html_e( 'Tabs order:', 'soledad' ); ?></label>
                <select id="<?php echo esc_attr( $this->get_field_id( 'tabs_order' ) ); ?>"
                        name="<?php echo esc_attr( $this->get_field_name( 'tabs_order' ) ); ?>">
					<?php
					foreach ( $list_oders as $value => $name ) {
						echo '<option value="' . $value . '" ' . selected( $value, $tabs_order ) . '>' . $name . '</option>';
					}
					?>
                </select>
            </p>

            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'tabs_style' ) ); ?>"><?php esc_html_e( 'Tabs style:', 'soledad' ); ?></label>
                <select id="<?php echo esc_attr( $this->get_field_id( 'tabs_style' ) ); ?>"
                        name="<?php echo esc_attr( $this->get_field_name( 'tabs_style' ) ); ?>">
					<?php
					foreach ( $list_style as $value => $name ) {
						echo '<option value="' . $value . '" ' . selected( $value, $tabs_style ) . '>' . $name . '</option>';
					}
					?>
                </select>
            </p>

            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'popular_order' ) ); ?>"><?php esc_html_e( 'Popular tab order:', 'soledad' ); ?></label>
                <select id="<?php echo esc_attr( $this->get_field_id( 'popular_order' ) ); ?>"
                        name="<?php echo esc_attr( $this->get_field_name( 'popular_order' ) ); ?>">
                    <option value="all" <?php if ( 'all' == $popular_order ) {
						echo 'selected="selected"';
					} ?>>All Time
                    </option>
                    <option value="week" <?php if ( 'week' == $popular_order ) {
						echo 'selected="selected"';
					} ?>>Once Weekly
                    </option>
                    <option value="month" <?php if ( 'month' == $popular_order ) {
						echo 'selected="selected"';
					} ?>>Once a Month
                    </option>
                    <option value="comments" <?php if ( 'comments' == $popular_order ) {
						echo 'selected="selected"';
					} ?>>Comments
                    </option>
					<?php if ( ! empty( penci_jetpack_option() ) ): ?>
                        <option value='jetpack' <?php if ( 'jetpack' == $instance['type'] ) {
							echo 'selected="selected"';
						} ?>>Once a Month
                        </option>
					<?php endif; ?>
                </select>
            </p>

            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'disable_popular' ) ); ?>"><?php esc_html_e( 'Disable Popular Posts Tab ?', 'soledad' ); ?></label>
                <input type="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'disable_popular' ) ); ?>"
                       name="<?php echo esc_attr( $this->get_field_name( 'disable_popular' ) ); ?>" <?php checked( (bool) $instance['disable_popular'], true ); ?> />
            </p>

            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'disable_recent' ) ); ?>"><?php esc_html_e( 'Disable Recent Posts Tab ?', 'soledad' ); ?></label>
                <input type="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'disable_recent' ) ); ?>"
                       name="<?php echo esc_attr( $this->get_field_name( 'disable_recent' ) ); ?>" <?php checked( (bool) $instance['disable_recent'], true ); ?> />
            </p>

            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'disable_comments' ) ); ?>"><?php esc_html_e( 'Disable Comments Tab ?', 'soledad' ); ?></label>
                <input type="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'disable_comments' ) ); ?>"
                       name="<?php echo esc_attr( $this->get_field_name( 'disable_comments' ) ); ?>" <?php checked( (bool) $instance['disable_comments'], true ); ?> />
            </p>

            <p>
                <strong>Post Listing Options</strong>
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

            <!-- Number of posts -->
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>"><?php esc_html_e( 'Number of posts to show:', 'soledad' ); ?></label>
                <input type="text" class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>"
                       name="<?php echo esc_attr( $this->get_field_name( 'number' ) ); ?>"
                       value="<?php echo esc_attr( $instance['number'] ); ?>" size="3"/>
            </p>

            <!-- Number of comments -->
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'number_comments' ) ); ?>"><?php esc_html_e( 'Number of comments to show:', 'soledad' ); ?></label>
                <input type="text" class="widefat"
                       id="<?php echo esc_attr( $this->get_field_id( 'number_comments' ) ); ?>"
                       name="<?php echo esc_attr( $this->get_field_name( 'number_comments' ) ); ?>"
                       value="<?php echo esc_attr( $instance['number_comments'] ); ?>" size="3"/>
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
                <label for="<?php echo esc_attr( $this->get_field_id( 'hide_thumb' ) ); ?>"><?php esc_html_e( 'Hide thumbnail(Featured Image)?', 'soledad' ); ?></label>
                <input type="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'hide_thumb' ) ); ?>"
                       name="<?php echo esc_attr( $this->get_field_name( 'hide_thumb' ) ); ?>" <?php checked( (bool) $instance['hide_thumb'], true ); ?> />
            </p>

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

            <!-- Hide Order Numbers -->
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'ordernum' ) ); ?>"><?php esc_html_e( 'Hide Order Numbers?:', 'soledad' ); ?></label>
                <input type="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'ordernum' ) ); ?>"
                       name="<?php echo esc_attr( $this->get_field_name( 'ordernum' ) ); ?>" <?php checked( (bool) $instance['ordernum'], true ); ?> />
            </p>

            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'ordernum_style' ) ); ?>"><?php esc_html_e( 'Order Numbers Style', 'soledad' ); ?></label>
                <select id="<?php echo esc_attr( $this->get_field_id( 'ordernum_style' ) ); ?>"
                        name="<?php echo esc_attr( $this->get_field_name( 'ordernum_style' ) ); ?>"
                        class="widefat orderby"
                        style="width:100%;">
                    <option value='' <?php if ( '' == $instance['ordernum_style'] ) {
						echo 'selected="selected"';
					} ?>><?php esc_html_e( "Style 1", 'soledad' ); ?></option>
                    <option value='2' <?php if ( '2' == $instance['ordernum_style'] ) {
						echo 'selected="selected"';
					} ?>><?php esc_html_e( 'Style 2', 'soledad' ); ?></option>
                    <option value='3' <?php if ( '3' == $instance['ordernum_style'] ) {
						echo 'selected="selected"';
					} ?>><?php esc_html_e( 'Style 3', 'soledad' ); ?></option>
                    <option value='4' <?php if ( '4' == $instance['ordernum_style'] ) {
						echo 'selected="selected"';
					} ?>><?php esc_html_e( 'Style 4', 'soledad' ); ?></option>
                </select>
            </p>

            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'ordernum_recent' ) ); ?>"><?php esc_html_e( 'Hide Order Numbers on Recent Tab Only?:', 'soledad' ); ?></label>
                <input type="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'ordernum_recent' ) ); ?>"
                       name="<?php echo esc_attr( $this->get_field_name( 'ordernum_recent' ) ); ?>" <?php checked( (bool) $instance['ordernum_recent'], true ); ?> />
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

            <!-- Hide post format icon -->
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'icon' ) ); ?>"><?php esc_html_e( 'Show icon post format?:', 'soledad' ); ?></label>
                <input type="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'icon' ) ); ?>"
                       name="<?php echo esc_attr( $this->get_field_name( 'icon' ) ); ?>" <?php checked( (bool) $instance['icon'], true ); ?> />
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
                <label for="<?php echo esc_attr( $this->get_field_id( 'imgwidth' ) ); ?>"><?php esc_html_e( 'Custom Image Width ( Default: 120 )', 'soledad' ); ?></label>
                <input type="number" class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'imgwidth' ) ); ?>"
                       name="<?php echo esc_attr( $this->get_field_name( 'imgwidth' ) ); ?>"
                       value="<?php echo esc_attr( $instance['imgwidth'] ); ?>" size="3"/>
                <span class="description"
                      style="display: block; padding: 0;font-size: 12px;"><?php esc_html_e( 'This option doesn\'t apply for featured posts. It should be between 80 to 200.', 'soledad' ); ?></span>
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
                <label for="<?php echo esc_attr( $this->get_field_id( 'ptorn' ) ); ?>"><?php esc_html_e( 'Font Size for Order Number', 'soledad' ); ?></label>
                <input type="number" class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'ptorn' ) ); ?>"
                       name="<?php echo esc_attr( $this->get_field_name( 'ptorn' ) ); ?>"
                       value="<?php echo esc_attr( $instance['ptorn'] ); ?>" size="3"/>
            </p>

			<p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'oboxsize' ) ); ?>"><?php esc_html_e( 'Box Size for Order Number', 'soledad' ); ?></label>
                <input type="number" class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'oboxsize' ) ); ?>"
                       name="<?php echo esc_attr( $this->get_field_name( 'oboxsize' ) ); ?>"
                       value="<?php echo esc_attr( $instance['oboxsize'] ); ?>" size="3"/>
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
