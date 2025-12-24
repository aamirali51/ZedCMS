<?php
add_action( 'wp_ajax_nopriv_penci_stylisted_articles_count_ajax', 'penci_stylisted_articles_count_ajax' );
add_action( 'wp_ajax_penci_stylisted_articles_count_ajax', 'penci_stylisted_articles_count_ajax' );
function penci_stylisted_articles_count_ajax() {
	check_ajax_referer( 'penci_widgets_ajax', 'nonce' );
	check_ajax_referer( 'penci_widgets_ajax', 'nonce' );
	/* Our variables from the widget settings. */
	$instance   = str_replace( 'u00a0', '', str_replace( '\\', '', $_POST['settings'] ) );
	$instance   = json_decode( $instance, true );
	$paged      = $_POST['paged'];
	$categories = isset( $instance['categories'] ) ? $instance['categories'] : '';
	$orderby    = isset( $instance['orderby'] ) ? $instance['orderby'] : 'date';
	$order      = isset( $instance['order'] ) ? $instance['order'] : 'DESC';
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
	$showauthor   = isset( $instance['show_author'] ) ? $instance['show_author'] : false;
	$showcomment  = isset( $instance['show_comment'] ) ? $instance['show_comment'] : false;
	$showviews    = isset( $instance['show_postviews'] ) ? $instance['show_postviews'] : false;
	$columns      = isset( $instance['columns'] ) ? $instance['columns'] : '';
	$cats_id      = ! empty( $instance['cats_id'] ) ? explode( ',', $instance['cats_id'] ) : array();
	$tags_id      = ! empty( $instance['tags_id'] ) ? explode( ',', $instance['tags_id'] ) : array();

	$thumb = 'penci-thumb-masonry';

	if ( $image_type == 'horizontal' ) {
		$thumb = 'penci-thumb-small';
	} elseif ( $image_type == 'square' ) {
		$thumb = 'penci-thumb-square';
	} elseif ( $image_type == 'vertical' ) {
		$thumb = 'penci-thumb-vertical';
	}

	if ( isset( $instance['custom_query'] ) && $instance['custom_query'] ) {
		$query  = $instance['custom_query'];
		$number = $query['posts_per_page'];
	} else {
		$query = array(
			'posts_per_page'      => $number,
			'post_type'           => $ptype,
			'ignore_sticky_posts' => $sticky_value
		);


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

		if ( $orderby == 'week' ) {
			$query['meta_key'] = 'penci_post_week_views_count';
			$query['orderby']  = 'meta_value_num';
		} elseif ( $orderby == 'month' ) {
			$query['meta_key'] = 'penci_post_month_views_count';
			$query['orderby']  = 'meta_value_num';
		} elseif ( $orderby == 'jetpack' ) {
			$query['meta_key'] = '_jetpack_post_view';
			$query['orderby']  = 'meta_value_num';
		} elseif ( $orderby == 'all' ) {
			$query['meta_key'] = penci_get_postviews_key();
			$query['orderby']  = 'meta_value_num';
		} elseif ( $orderby ) {
			$query['orderby'] = $orderby;
		}

		if ( $order ) {
			$query['order'] = $order;
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

	}

	if ( $paged ) {
		$query['paged'] = $paged;
	}

	if ( $offset ) {
		$query['offset'] = $offset;
	}


	$loop = new WP_Query( $query );
	if ( $loop->have_posts() ) :
		get_template_part( 'inc/templates/popular_posts', '', [
			'loop'         => $loop,
			'class'        => 'demo',
			'showauthor'   => $showauthor,
			'postdate'     => $postdate,
			'showviews'    => $showviews,
			'showcomment'  => $showcomment,
			'title_length' => $title_length,
			'thumb'        => $thumb,
			'columns'      => $columns,
			'id'           => '',
			'data_attr'    => '',
			'paged'        => $paged,
		] );
	endif;
	die();
}

add_action( 'wp_ajax_nopriv_penci_latest_news_widget_ajax', 'penci_latest_news_widget_ajax' );
add_action( 'wp_ajax_penci_latest_news_widget_ajax', 'penci_latest_news_widget_ajax' );
function penci_latest_news_widget_ajax() {
	check_ajax_referer( 'penci_widgets_ajax', 'nonce' );
	/* Our variables from the widget settings. */
	$instance   = str_replace( 'u00a0', '', str_replace( '\\', '', $_POST['settings'] ) );
	$instance   = json_decode( $instance, true );
	$paged      = $_POST['paged'];
	$categories = isset( $instance['categories'] ) ? $instance['categories'] : '';
	$orderby    = isset( $instance['orderby'] ) ? $instance['orderby'] : 'date';
	$order      = isset( $instance['order'] ) ? $instance['order'] : 'DESC';
	$number     = isset( $instance['number'] ) ? $instance['number'] : '';
	$offset     = isset( $instance['offset'] ) ? $instance['offset'] : '';
	$ptype      = isset( $instance['ptype'] ) ? $instance['ptype'] : '';
	if ( ! $ptype ): $ptype = 'post'; endif;
	$taxonomy     = isset( $instance['taxonomy'] ) ? $instance['taxonomy'] : '';
	$tax_ids      = isset( $instance['tax_ids'] ) ? $instance['tax_ids'] : 'tax_ids';
	$sticky       = isset( $instance['sticky'] ) ? $instance['sticky'] : true;
	$sticky_value = ( false == $sticky ) ? 0 : 1;
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
	$hide_thumb   = isset( $instance['hide_thumb'] ) ? $instance['hide_thumb'] : false;
	$showauthor   = isset( $instance['show_author'] ) ? $instance['show_author'] : false;
	$showcomment  = isset( $instance['show_comment'] ) ? $instance['show_comment'] : false;
	$showviews    = isset( $instance['show_postviews'] ) ? $instance['show_postviews'] : false;
	$ordernum     = isset( $instance['ordernum'] ) ? $instance['ordernum'] : false;
	$showborder   = isset( $instance['showborder'] ) ? $instance['showborder'] : false;
	$cats_id      = ! empty( $instance['cats_id'] ) ? explode( ',', $instance['cats_id'] ) : array();
	$tags_id      = ! empty( $instance['tags_id'] ) ? explode( ',', $instance['tags_id'] ) : array();
	$alt_style    = ! empty( $instance['altstyle'] ) ? $instance['altstyle'] : false;

	if ( isset( $instance['custom_query'] ) && $instance['custom_query'] ) {
		$query  = $instance['custom_query'];
		$number = $query['posts_per_page'];
	} else {
		$query = array(
			'posts_per_page'      => $number,
			'post_type'           => $ptype,
			'ignore_sticky_posts' => $sticky_value
		);


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

		if ( $orderby ) {
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

	}

	if ( $paged ) {
		$query['paged'] = $paged;
	}


	$loop = new WP_Query( $query );
	if ( $loop->have_posts() ) :

		?>
        <ul class="side-newsfeed<?php if ( $twocolumn && ! $allfeatured ): echo ' penci-feed-2columns';
			if ( $featured ) {
				echo ' penci-2columns-featured';
			} else {
				echo ' penci-2columns-feed';
			} endif;
		if ( $showborder ) {
			echo ' penci-rcpw-hborders';
		}
		if ( $dotstyle ) {
			echo ' pctlst pctl-' . $dotstyle;
		} ?>">
			<?php $num = 1;
			while ( $loop->have_posts() ) : $loop->the_post(); ?>
                <li class="penci-feed<?php if ( ( ( $num == 1 ) && $featured ) || $allfeatured ): echo ' featured-news';
					if ( $featured2 ): echo ' featured-news2'; endif; endif; ?><?php if ( $allfeatured ): echo ' all-featured-news'; endif; ?>">
					<?php if ( $ordernum && has_post_thumbnail() && ! $hide_thumb ): ?>
                        <span class="order-border-number<?php if ( $thumbright && ! $twocolumn ): echo ' right-side'; endif; ?>">
									<span class="number-post"><?php echo sanitize_text_field( $num + ( ( $paged - 1 ) * $number ) ); ?></span>
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

                                <a <?php echo penci_layout_bg( penci_image_srcset( get_the_ID(), $thumb ) ); ?> class="<?php echo penci_layout_bg_class();?> penci-image-holder<?php if ( ( ( $num == 1 ) && $featured ) || $allfeatured ) {
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
		wp_reset_postdata();
	endif;
	die();
}

add_action( 'wp_ajax_nopriv_penci_popular_news_ajax', 'penci_popular_news_ajax' );
add_action( 'wp_ajax_penci_popular_news_ajax', 'penci_popular_news_ajax' );
function penci_popular_news_ajax() {
	check_ajax_referer( 'penci_widgets_ajax', 'nonce' );
	/* Our variables from the widget settings. */
	$instance   = str_replace( 'u00a0', '', str_replace( '\\', '', $_POST['settings'] ) );
	$instance   = json_decode( $instance, true );
	$paged      = $_POST['paged'];
	$type       = isset( $instance['type'] ) ? $instance['type'] : '';
	$categories = isset( $instance['categories'] ) ? $instance['categories'] : '';
	$number     = isset( $instance['number'] ) ? $instance['number'] : '';
	$offset     = isset( $instance['offset'] ) ? $instance['offset'] : '';
	$ptype      = isset( $instance['ptype'] ) ? $instance['ptype'] : '';
	if ( ! $ptype ): $ptype = 'post'; endif;
	$taxonomy     = isset( $instance['taxonomy'] ) ? $instance['taxonomy'] : '';
	$tax_ids      = isset( $instance['tax_ids'] ) ? $instance['tax_ids'] : 'tax_ids';
	$sticky       = isset( $instance['sticky'] ) ? $instance['sticky'] : true;
	$sticky_value = ( false == $sticky ) ? 0 : 1;
	$title_length = isset( $instance['title_length'] ) ? $instance['title_length'] : '';
	$featured     = isset( $instance['featured'] ) ? $instance['featured'] : false;
	$dotstyle     = isset( $instance['dotstyle'] ) ? $instance['dotstyle'] : '';
	$movemeta     = isset( $instance['movemeta'] ) ? $instance['movemeta'] : false;
	$twocolumn    = isset( $instance['twocolumn'] ) ? $instance['twocolumn'] : false;
	$featured2    = isset( $instance['featured2'] ) ? $instance['featured2'] : false;
	$ordernum     = isset( $instance['ordernum'] ) ? $instance['ordernum'] : false;
	$allfeatured  = isset( $instance['allfeatured'] ) ? $instance['allfeatured'] : false;
	$thumbright   = isset( $instance['thumbright'] ) ? $instance['thumbright'] : false;
	$postdate     = isset( $instance['postdate'] ) ? $instance['postdate'] : false;
	$icon         = isset( $instance['icon'] ) ? $instance['icon'] : false;
	$image_type   = isset( $instance['image_type'] ) ? $instance['image_type'] : 'default';
	$hide_thumb   = isset( $instance['hide_thumb'] ) ? $instance['hide_thumb'] : false;
	$showauthor   = isset( $instance['show_author'] ) ? $instance['show_author'] : false;
	$showcomment  = isset( $instance['show_comment'] ) ? $instance['show_comment'] : false;
	$showviews    = isset( $instance['show_postviews'] ) ? $instance['show_postviews'] : false;
	$cats_id      = ! empty( $instance['cats_id'] ) ? explode( ',', $instance['cats_id'] ) : array();
	$tags_id      = ! empty( $instance['tags_id'] ) ? explode( ',', $instance['tags_id'] ) : array();
	$alt_style    = ! empty( $instance['altstyle'] ) ? $instance['altstyle'] : false;

	$query = array(
		'meta_key'            => penci_get_postviews_key(),
		'orderby'             => 'meta_value_num',
		'order'               => 'DESC',
		'posts_per_page'      => $number,
		'post_type'           => $ptype,
		'ignore_sticky_posts' => $sticky_value
	);

	if ( $paged ) {
		$query['paged'] = $paged;
	}

	if ( $type == 'week' ) {
		$query = array(
			'posts_per_page' => $number,
			'meta_key'       => 'penci_post_week_views_count',
			'orderby'        => 'meta_value_num',
			'order'          => 'DESC',
		);
	} elseif ( $type == 'month' ) {
		$query = array(
			'posts_per_page' => $number,
			'meta_key'       => 'penci_post_month_views_count',
			'orderby'        => 'meta_value_num',
			'order'          => 'DESC',
		);
	} elseif ( $type == 'jetpack' ) {
		$query = array(
			'posts_per_page' => $number,
			'meta_key'       => '_jetpack_post_view',
			'orderby'        => 'meta_value_num',
			'order'          => 'DESC',
		);
	}

	if ( 'post' == $ptype ) {
		if ( isset( $instance['cats_id'] ) ) {
			if ( ! empty( $cats_id ) && ! in_array( 'all', $cats_id ) ) {
				$query['tax_query'][] = [
					'taxonomy' => 'category',
					'field'    => 'term_id',
					'terms'    => $cats_id,
				];
			}
		} else {
			if ( $categories ) {
				$query['cat'] = $categories;
			}
		}

		if ( ! empty( $tags_id ) ) {
			if ( ! in_array( 'all', $tags_id ) ) {
				$query['tax_query'][] = [
					'taxonomy' => 'post_tag',
					'field'    => 'term_id',
					'terms'    => $tags_id,
				];
			}
		}
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
		$rand = rand( 1000, 10000 );


		?>
        <ul id="penci-popularwg-<?php echo sanitize_text_field( $rand ); ?>"
            class="side-newsfeed<?php if ( $twocolumn && ! $allfeatured ): echo ' penci-feed-2columns';
			    if ( $featured ) {
				    echo ' penci-2columns-featured';
			    } else {
				    echo ' penci-2columns-feed';
			    } endif; ?><?php if ( ! $ordernum ): echo ' display-order-numbers'; endif;
		    if ( $dotstyle ) {
			    echo ' pctlst pctl-' . $dotstyle;
		    } ?>">

			<?php $num = 1;
			while ( $loop->have_posts() ) : $loop->the_post(); ?>

                <li class="penci-feed<?php if ( ( ( $num == 1 ) && $featured ) || $allfeatured ): echo ' featured-news';
					if ( $featured2 ): echo ' featured-news2'; endif; endif; ?><?php if ( $allfeatured ): echo ' all-featured-news'; endif; ?>">
					<?php if ( ! $ordernum && has_post_thumbnail() && ! $hide_thumb ): ?>
                        <span class="order-border-number<?php if ( $thumbright && ! $twocolumn ): echo ' right-side'; endif; ?>">
									<span class="number-post"><?php echo sanitize_text_field( $num + ( ( $paged - 1 ) * $number ) ); ?></span>
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

                                <a <?php echo penci_layout_bg( penci_image_srcset( get_the_ID(), $thumb ) ); ?> class="<?php echo penci_layout_bg_class();?> penci-image-holder<?php if ( ( ( $num == 1 ) && $featured ) || $allfeatured ) {
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

		wp_reset_postdata();
	endif;
	die();
}

add_action( 'wp_ajax_nopriv_penci_related_news_widget_ajax', 'penci_related_news_widget_ajax' );
add_action( 'wp_ajax_penci_related_news_widget_ajax', 'penci_related_news_widget_ajax' );
function penci_related_news_widget_ajax() {
	check_ajax_referer( 'penci_widgets_ajax', 'nonce' );

	/* Our variables from the widget settings. */
	$instance     = str_replace( 'u00a0', '', str_replace( '\\', '', $_POST['settings'] ) );
	$instance     = json_decode( $instance, true );
	$paged        = $_POST['paged'];
	$post_id      = $_POST['id'];
	$type         = isset( $instance['type'] ) ? $instance['type'] : 'categories';
	$orderby      = isset( $instance['orderby'] ) ? $instance['orderby'] : 'date';
	$order        = isset( $instance['order'] ) ? $instance['order'] : 'DESC';
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

	$args = penci_get_query_related_posts( $post_id, $type, $orderby, $order, $number );

	if ( ! empty( $args ) ) {

		$args['paged'] = $paged;

		$loop = new WP_Query( $args );
		if ( $loop->have_posts() ) :
			?>
            <ul class="side-newsfeed<?php if ( $twocolumn && ! $allfeatured ): echo ' penci-feed-2columns';
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

                                    <a <?php echo penci_layout_bg( penci_image_srcset( get_the_ID(), $thumb ) ); ?> class="<?php echo penci_layout_bg_class();?> penci-image-holder<?php if ( ( ( $num == 1 ) && $featured ) || $allfeatured ) {
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
			wp_reset_postdata();
		endif;
	}
	die();
}

add_action( 'wp_ajax_nopriv_penci_posts_tabs_widget_ajax', 'penci_posts_tabs_widget_ajax' );
add_action( 'wp_ajax_penci_posts_tabs_widget_ajax', 'penci_posts_tabs_widget_ajax' );
function penci_posts_tabs_widget_ajax() {
	check_ajax_referer( 'penci_widgets_ajax', 'nonce' );
	$instance      = str_replace( 'u00a0', '', str_replace( '\\', '', $_POST['settings'] ) );
	$instance      = json_decode( $instance, true );
	$paged         = $_POST['paged'];
	$type          = $_POST['type'];
	$sticky        = isset( $instance['sticky'] ) ? $instance['sticky'] : true;
	$sticky_value  = ( false == $sticky ) ? 0 : 1;
	$popular_order = isset( $instance['popular_order'] ) ? $instance['popular_order'] : 'all';
	$number        = isset( $instance['number'] ) ? $instance['number'] : '5';
	$offset        = isset( $instance['offset'] ) ? $instance['offset'] : '';
	$title_length  = isset( $instance['title_length'] ) ? $instance['title_length'] : '';
	$featured      = isset( $instance['featured'] ) ? $instance['featured'] : false;
	$dotstyle      = isset( $instance['dotstyle'] ) ? $instance['dotstyle'] : '';
	$movemeta      = isset( $instance['movemeta'] ) ? $instance['movemeta'] : false;
	$twocolumn     = isset( $instance['twocolumn'] ) ? $instance['twocolumn'] : false;
	$featured2     = isset( $instance['featured2'] ) ? $instance['featured2'] : false;
	$ordernum      = isset( $instance['ordernum'] ) ? $instance['ordernum'] : false;
	$allfeatured   = isset( $instance['allfeatured'] ) ? $instance['allfeatured'] : false;
	$thumbright    = isset( $instance['thumbright'] ) ? $instance['thumbright'] : false;
	$postdate      = isset( $instance['postdate'] ) ? $instance['postdate'] : false;
	$icon          = isset( $instance['icon'] ) ? $instance['icon'] : false;
	$image_type    = isset( $instance['image_type'] ) ? $instance['image_type'] : 'default';
	$hide_thumb    = isset( $instance['hide_thumb'] ) ? $instance['hide_thumb'] : false;
	$showauthor    = isset( $instance['show_author'] ) ? $instance['show_author'] : false;
	$showcomment   = isset( $instance['show_comment'] ) ? $instance['show_comment'] : false;
	$showviews     = isset( $instance['show_postviews'] ) ? $instance['show_postviews'] : false;
	$showborder    = isset( $instance['showborder'] ) ? $instance['showborder'] : false;
	$query         = array(
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

	$query['paged'] = $paged;

	$loop = new WP_Query( $query );
	if ( $loop->have_posts() ) :
		?>
        <ul class="penci-wdtab-ct side-newsfeed<?php if ( $twocolumn && ! $allfeatured ): echo ' penci-feed-2columns';
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
		} ?>">

			<?php $num = 1;
			while ( $loop->have_posts() ) : $loop->the_post(); ?>

                <li class="penci-feed<?php if ( ( ( $num == 1 ) && $featured ) || $allfeatured ): echo ' featured-news';
					if ( $featured2 ): echo ' featured-news2'; endif; endif; ?><?php if ( $allfeatured ): echo ' all-featured-news'; endif; ?>">
					<?php if ( ! $ordernum && has_post_thumbnail() && ! $hide_thumb ): ?>
                        <span class="order-border-number<?php if ( $thumbright && ! $twocolumn ): echo ' right-side'; endif; ?>">
									<span class="number-post"><?php echo sanitize_text_field( $num + ( ( $paged - 1 ) * $number ) ); ?></span>
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
		wp_reset_postdata();
	endif;
	die();
}

add_action( 'wp_ajax_nopriv_penci_posts_tabs_widget_comments_ajax', 'penci_posts_tabs_widget_comments_ajax' );
add_action( 'wp_ajax_penci_posts_tabs_widget_comments_ajax', 'penci_posts_tabs_widget_comments_ajax' );
function penci_posts_tabs_widget_comments_ajax() {
	check_ajax_referer( 'penci_widgets_ajax', 'nonce' );
	$paged           = $_POST['paged'];
	$number_comments = isset( $_POST['number_comments'] ) ? $_POST['number_comments'] : 5;
	$comments        = get_comments( [
		'number' => $number_comments,
		'paged'  => $paged,
		'status' => 'approve',
	] );
	if ( ! empty( $comments ) ) {
		?>
        <ul>
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
	}
	die();
}