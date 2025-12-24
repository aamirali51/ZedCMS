<?php
// get settings
if ( ! function_exists( 'penci_featured_archive_posts_content' ) ) {
	add_action( 'penci_featured_archive_posts', 'penci_featured_archive_posts_content' );
	function penci_featured_archive_posts_content() {
		$penci_featured_layout     = '';
		$penci_cat_featured_layout = get_theme_mod( 'penci_cat_featured_layout', '' );
		$penci_tag_featured_layout = get_theme_mod( 'penci_tag_featured_layout', '' );

		if ( is_category() && $penci_cat_featured_layout ) {
			$penci_featured_layout = $penci_cat_featured_layout;
		}

		if ( is_tag() && $penci_tag_featured_layout ) {
			$penci_featured_layout = $penci_tag_featured_layout;
		}

		$grid_per_page = penci_featured_archive_ppl( $penci_featured_layout );

		if ( $penci_featured_layout ) {


			$biggid_style = $penci_featured_layout;
			// setup biggrid style
			$wrapper_class = $data_class = '';
			$flag_style    = false;
			if ( in_array( $penci_featured_layout, [
				'style-1 penci-grid-col-2',
				'style-1 penci-grid-col-3',
				'style-1 penci-grid-col-4',
				'style-1 penci-grid-col-5'
			] ) ) {
				$data_class   .= ' penci-dflex';
				$biggid_style = 'style-1';
			} else {
				$data_class .= ' penci-dblock';
				$flag_style = true;
				$data_class .= ' penci-fixh';
			}

			$two_sidebar_class = '';
			$sidebar_position  = penci_get_sidebar_position_archive();
			if ( 'two-sidebar' == $sidebar_position ): $two_sidebar_class = ' two-sidebar'; endif;

			$wrapper_class .= ' container biggrid-archive-wrapper';
			$wrapper_class .= $two_sidebar_class;

			$wrapper_class .= is_category() ? ' biggrid-cat-wrapper' : ' biggrid-tag-wrapper';

			$wrapper_class .= ' penci-bgrid-based-post penci-bgrid-' . $penci_featured_layout . ' pcbg-ficonpo-top-right pcbg-reiconpo-top-left penci-bgrid-content-on pencibg-imageh-zoom-in pencibg-texth-none pencibg-textani-movetop';

			if ( get_theme_mod( 'penci_arcf_mmeta' ) ) {
				$wrapper_class .= ' hide-mdesc';
			}

			if ( get_theme_mod( 'penci_arcf_mcat' ) ) {
				$wrapper_class .= ' hide-msubtitle';
			}

			$featured_posts = new WP_Query( penci_get_query_archive_first_posts_featured() );
			$big_items      = penci_big_grid_is_big_items( $biggid_style );

			//gettings category settings
			$post_meta      = [ 'title', 'cat', 'date' ];
			$disable_lazy   = get_theme_mod( 'penci_disable_lazyload_layout' );
			$show_reviewpie = true;

			if ( get_theme_mod( 'penci_review_hide_piechart' ) ) {
				$show_reviewpie = false;
			}
			$primary_cat = get_theme_mod( 'penci_show_pricat_yoast_only' );
			if ( get_theme_mod( 'penci_arcf_cat' ) ) {
				unset( $post_meta[1] );
			}
			if ( get_theme_mod( 'penci_arcf_date' ) ) {
				unset( $post_meta[2] );
			}
			if ( get_theme_mod( 'penci_arcf_author' ) ) {
				$post_meta[] = 'author';
			}
			if ( get_theme_mod( 'penci_arcf_cm' ) ) {
				$post_meta[] = 'comment';
			}
			if ( get_theme_mod( 'penci_arcf_view' ) ) {
				$post_meta[] = 'views';
			}
			if ( get_theme_mod( 'penci_arcf_reading' ) ) {
				$post_meta[] = 'reading';
			}
			$show_formaticon = get_theme_mod( 'penci_grid_icon_format' );
			if ( $featured_posts->have_posts() ) {
				$bg          = 1;
				$thumb_size  = $mthumb_size = 'penci-masonry-thumb';
				$bthumb_size = 'penci-full-thumb';
				$num_posts   = $featured_posts->post_count;
				?>
                <div class="penci-clearfix penci-biggrid-wrapper<?php echo $wrapper_class; ?>">
                    <div class="penci-clearfix penci-biggrid penci-bg<?php echo $biggid_style; ?> penci-bgel">
                        <div class="penci-biggrid-inner">
							<?php
							if ( $flag_style ) {
								echo '<div class="penci-big-grid-ajax-data">';
							}
							echo '<div class="penci-clearfix penci-biggrid-data' . $data_class . '">';
							while ( $featured_posts->have_posts() ) {
								$featured_posts->the_post();

								if ( $bg <= $grid_per_page ) {

									$is_big_item = '';
									$surplus     = penci_big_grid_count_classes( $bg, $biggid_style, true );
									$thumbnail   = $thumb_size;
									if ( ! empty( $big_items ) && in_array( $surplus, $big_items ) ) {
										$thumbnail   = $bthumb_size;
										$is_big_item = ' pcbg-big-item';
									}
									if ( penci_is_mobile() ) {
										$thumbnail = $mthumb_size;
									}
									?>
                                    <div class="penci-bgitem<?php echo $is_big_item . penci_big_grid_count_classes( $bg, $biggid_style ); ?>">
                                        <div class="penci-bgitin">
                                            <div class="penci-bgmain">
                                                <div class="pcbg-thumb">
													<?php
													/* Display Review Piechart  */
													if ( $show_reviewpie && function_exists( 'penci_display_piechart_review_html' ) ) {
														penci_display_piechart_review_html( get_the_ID() );
													}
													?>
													<?php if ( $show_formaticon ): ?>
														<?php if ( has_post_format( 'video' ) ) : ?>
                                                            <a href="<?php the_permalink() ?>" class="icon-post-format"
                                                               aria-label="Icon"><?php penci_fawesome_icon( 'fas fa-play' ); ?></a>
														<?php endif; ?>
														<?php if ( has_post_format( 'gallery' ) ) : ?>
                                                            <a href="<?php the_permalink() ?>" class="icon-post-format"
                                                               aria-label="Icon"><?php penci_fawesome_icon( 'far fa-image' ); ?></a>
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
													<?php endif; ?>
                                                    <div class="pcbg-thumbin">
                                                        <a class="pcbg-bgoverlay active-overlay"
                                                           href="<?php the_permalink(); ?>"
                                                           title="<?php echo wp_strip_all_tags( get_the_title() ); ?>"></a>

                                                            <div <?php echo penci_layout_bg( penci_image_srcset( get_the_ID(), $thumbnail ), ! $disable_lazy ); ?> class="<?php echo penci_layout_bg_class();?> penci-image-holder">
	                                                            <?php echo penci_layout_img( penci_image_srcset( get_the_ID(), $thumbnail ), get_the_title(),! $disable_lazy ); ?>
                                                            </div>

                                                    </div>
                                                </div>
                                                <div class="pcbg-content">
                                                    <div class="pcbg-content-flex">
                                                        <a class="pcbg-bgoverlay active-overlay"
                                                           href="<?php the_permalink(); ?>"
                                                           title="<?php echo wp_strip_all_tags( get_the_title() ); ?>"></a>
                                                        <div class="pcbg-content-inner bgcontent-block">
                                                            <a href="<?php the_permalink(); ?>"
                                                               title="<?php echo wp_strip_all_tags( get_the_title() ); ?>"
                                                               class="pcbg-bgoverlaytext item-hover"></a>

															<?php if ( in_array( 'cat', $post_meta ) ) : ?>
                                                                <div class="pcbg-above item-hover">
                                                                <span class="cat pcbg-sub-title">
                                                                    <?php penci_category( '', $primary_cat ); ?>
                                                                </span>
                                                                </div>
															<?php endif; ?>

															<?php if ( in_array( 'title', $post_meta ) ) :
																$title_length = get_theme_mod( 'penci_arf_titlength', '' );
																?>
                                                                <div class="pcbg-heading item-hover">
                                                                    <h3 class="pcbg-title">
                                                                        <a href="<?php the_permalink(); ?>">
																			<?php
																			if ( ! $title_length ) {
																				the_title();
																			} else {
																				echo wp_trim_words( wp_strip_all_tags( get_the_title() ), $title_length, '...' );
																			}
																			?>
                                                                        </a>
                                                                    </h3>
                                                                </div>
															<?php endif; ?>

															<?php if ( count( array_intersect( array(
																	'author',
																	'date',
																	'comment',
																	'views',
																	'reading',
																	'excerpt'
																), $post_meta ) ) > 0 ) { ?>
                                                                <div class="grid-post-box-meta pcbg-meta item-hover">
                                                                    <div class="pcbg-meta-desc">
																		<?php if ( in_array( 'author', $post_meta ) ) : ?>
                                                                            <span class="bg-date-author author-italic author vcard">
                                                                            <?php echo penci_get_setting( 'penci_trans_by' ); ?> <?php if ( function_exists( 'coauthors_posts_links' ) ) :
																					penci_coauthors_posts_links();
																				else: ?>
                                                                                    <a class="author-url url fn n"
                                                                                       href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>"><?php the_author(); ?></a>
																				<?php endif; ?>
                                                                        </span>
																		<?php endif; ?>
																		<?php if ( in_array( 'date', $post_meta ) ) : ?>
                                                                            <span class="bg-date"><?php penci_soledad_time_link(); ?></span>
																		<?php endif; ?>
																		<?php if ( in_array( 'comment', $post_meta ) ) : ?>
                                                                            <span class="bg-comment">
                                                                            <a href="<?php comments_link(); ?> "><?php comments_number( '0 ' . penci_get_setting( 'penci_trans_comments' ), '1 ' . penci_get_setting( 'penci_trans_comment' ), '% ' . penci_get_setting( 'penci_trans_comments' ) ); ?></a>
                                                                        </span>
																		<?php endif; ?>
																		<?php
																		if ( in_array( 'views', $post_meta ) ) {
																			echo '<span>';
																			echo penci_get_post_views( get_the_ID() );
																			echo ' ' . penci_get_setting( 'penci_trans_countviews' );
																			echo '</span>';
																		}
																		?>
																		<?php
																		$hide_readtime = in_array( 'reading', $post_meta ) ? false : true;
																		if ( penci_isshow_reading_time( $hide_readtime ) ): ?>
                                                                            <span class="bg-readtime"><?php penci_reading_time(); ?></span>
																		<?php endif; ?>
																		<?php do_action( 'penci_extra_meta' ); ?>
                                                                    </div>
                                                                </div>
															<?php } ?>

                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
									<?php
								}
								$bg ++;
								/* end loop content*/
							}
							?>
                        </div>
                    </div>
					<?php
					if ( $flag_style ) {
						echo '</div>';
					}
					?>
                </div>
                </div>

				<?php if ( get_theme_mod( 'penci_arcf_adbelow' ) ) { ?>
                    <div class="container container-arc-banner<?php echo $two_sidebar_class; ?>">
						<?php echo penci_render_google_adsense( 'penci_arcf_adbelow' ); ?>
                    </div>
				<?php } ?>

				<?php
				wp_reset_postdata();
			}
		}
	}
}

if ( ! function_exists( 'penci_featured_archive_ppl' ) ) {
	function penci_featured_archive_ppl( $biggid_style ) {

		$count = 5;

		if ( in_array( $biggid_style, array(
			'style-3',
			'style-5',
			'style-13',
			'style-17',
			'style-1 penci-grid-col-3'
		) ) ) {
			$count = 3;
		} else if ( in_array( $biggid_style, array(
			'style-7',
			'style-8',
			'style-11',
			'style-12',
			'style-14',
			'style-16',
			'style-18',
			'style-1 penci-grid-col-4'
		) ) ) {
			$count = 4;
		} else if ( in_array( $biggid_style, array( 'style-15' ) ) ) {
			$count = 6;
		} else if ( in_array( $biggid_style, array( 'style-19', 'style-20', 'style-21', 'style-22' ) ) ) {
			$count = 7;
		} else if ( $biggid_style == 'style-1 penci-grid-col-2' ) {
			$count = 2;
		}

		return $count;
	}
}

if ( ! function_exists( 'penci_featured_exclude_posts' ) ) {
	function penci_featured_exclude_posts() {
		$penci_featured_layout     = '';
		$penci_cat_featured_layout = get_theme_mod( 'penci_cat_featured_layout', '' );
		$penci_tag_featured_layout = get_theme_mod( 'penci_tag_featured_layout', '' );

		if ( is_category() && $penci_cat_featured_layout ) {
			$penci_featured_layout = $penci_cat_featured_layout;
		}

		if ( is_tag() && $penci_tag_featured_layout ) {
			$penci_featured_layout = $penci_tag_featured_layout;
		}

		return (bool) $penci_featured_layout;
	}
}

function penci_get_query_archive_first_posts_featured() {
	$featured_query            = [];
	$penci_cat_featured_layout = get_theme_mod( 'penci_cat_featured_layout', '' );
	$penci_tag_featured_layout = get_theme_mod( 'penci_tag_featured_layout', '' );


	if ( ( is_category() && $penci_cat_featured_layout ) || ( is_tag() && $penci_tag_featured_layout ) ) {
		$term                             = get_queried_object();
		$penci_featured_layout            = is_category() ? $penci_cat_featured_layout : $penci_tag_featured_layout;
		$featured_query['tax_query']      = array(
			array(
				'taxonomy' => $term->taxonomy,
				'terms'    => $term->term_id,
			),
		);
		$grid_per_page                    = penci_featured_archive_ppl( $penci_featured_layout );
		$featured_query['posts_per_page'] = $grid_per_page;

		$sortby   = get_theme_mod( 'penci_arf_sortby', 'desc' );
		$order_by = get_theme_mod( 'penci_arf_orderby', '' );

		$featured_query['orderby'] = $order_by;
		$featured_query['order']   = $sortby;

		if ( 'popular' == $order_by ) {
			$featured_query['meta_key'] = penci_get_postviews_key();
			$featured_query['orderby']  = 'meta_value_num';
		} elseif ( 'popular_day' == $order_by ) {
			$featured_query['meta_key'] = 'penci_post_day_views_count';
			$featured_query['orderby']  = 'meta_value_num';
		} elseif ( 'popular7' == $order_by ) {
			$featured_query['meta_key'] = 'penci_post_week_views_count';
			$featured_query['orderby']  = 'meta_value_num';
		} elseif ( 'popular_month' == $order_by ) {
			$featured_query['meta_key'] = 'penci_post_month_views_count';
			$featured_query['orderby']  = 'meta_value_num';
		} elseif ( 'jetpack' == $order_by ) {
			$featured_query['meta_key'] = '_jetpack_post_view';
			$featured_query['orderby']  = 'meta_value_num';
		}
	}

	return $featured_query;
}

if ( ! function_exists( 'penci_featured_exclude_ids' ) ) {
	function penci_featured_exclude_ids(): array {
		$ids  = [];
		$args = penci_get_query_archive_first_posts_featured();

		if ( ! empty( $args ) ) {

			$checkquery_posts = get_posts( $args );
			foreach ( $checkquery_posts as $post ) {
				$ids[] = $post->ID;
			}

		}

		return $ids;
	}
}


if ( ! function_exists( 'penci_featured_exclude_query_posts' ) ) {
	add_action( 'pre_get_posts', 'penci_featured_exclude_query_posts' );
	function penci_featured_exclude_query_posts( $query ) {
		if ( penci_featured_exclude_posts() && ! is_admin() && $query->is_main_query() ) {
			$featured_ids = penci_featured_exclude_ids();
			$query->set( 'post__not_in', $featured_ids );
		}
	}
}

if ( ! function_exists( 'penci_featured_custom_css' ) ) {
	add_action( 'soledad_theme/custom_css', 'penci_featured_custom_css' );
	function penci_featured_custom_css() {

		$custom_colors = [
			'penci_arf_t_cl'                    => [
				'color' => '.biggrid-archive-wrapper .pcbg-content-inner .pcbg-title, .biggrid-archive-wrapper .pcbg-content-inner .pcbg-title a',
			],
			'penci_arf_t_hcl'                   => [
				'color' => '.biggrid-archive-wrapper .pcbg-content-inner .pcbg-title:hover, .biggrid-archive-wrapper .pcbg-content-inner .pcbg-title a:hover',
			],
			'penci_arf_meta_cl'                 => [
				'color' => '.biggrid-archive-wrapper .pcbg-meta, .biggrid-archive-wrapper .pcbg-meta span, .biggrid-archive-wrapper .pcbg-meta span a',
			],
			'penci_arf_meta_lcl'                => [
				'color' => '.biggrid-archive-wrapper .pcbg-meta span a',
			],
			'penci_arf_meta_hcl'                => [
				'color' => '.biggrid-archive-wrapper .pcbg-meta span a:hover',
			],
			'penci_arf_cat_cl'                  => [
				'color' => '.biggrid-archive-wrapper .pcbg-content-inner .cat > a.penci-cat-name',
			],
			'penci_arf_cat_hcl'                 => [
				'color' => '.biggrid-archive-wrapper .pcbg-content-inner .cat > a.penci-cat-name:hover',
			],
			'penci_arf_catfs'                   => [
				'font-size' => '.biggrid-archive-wrapper .cat > a.penci-cat-name',
			],
			'penci_arf_t_fs'                    => [
				'font-size' => '.biggrid-archive-wrapper .pcbg-content-inner .pcbg-title, .biggrid-archive-wrapper .pcbg-content-inner .pcbg-title a',
			],
			'penci_arf_t_bfs'                   => [
				'font-size' => '.biggrid-archive-wrapper .penci-biggrid-data.penci-fixh .pcbg-big-item .pcbg-content-inner .pcbg-title, .biggrid-archive-wrapper .penci-biggrid-data.penci-fixh .pcbg-big-item .pcbg-content-inner .pcbg-title a',
			],
			'penci_arf_meta_fs'                 => [
				'font-size' => '.biggrid-archive-wrapper .pcbg-meta, .biggrid-archive-wrapper .pcbg-meta span, .biggrid-archive-wrapper .pcbg-meta span a',
			],
			'penci_arf_gap'                     => [
				'--pcgap' => '.biggrid-archive-wrapper .penci-biggrid',
			],
			'penci_arf_img_ratio'               => [
				'padding-top' => '.biggrid-archive-wrapper .penci-bgitem .penci-image-holder:before',
			],
			'penci_category_featured_img_ratio' => [
				'padding-top' => '.biggrid-category-wrapper .penci-bgitem .penci-image-holder:before',
			],
			'penci_tag_featured_img_ratio'      => [
				'padding-top' => '.biggrid-tag-wrapper .penci-bgitem .penci-image-holder:before',
			],
			'penci_arf_exp_cl'                  => [
				'color' => '.biggrid-archive-wrapper .pcbg-content-inner .pcbg-meta .pcbg-pexcerpt',
			],
			'penci_arf_height'                  => [
				'--bgh' => '.biggrid-archive-wrapper .penci-biggrid .penci-fixh',
			],
			'penci_category_featured_height'    => [
				'--bgh' => '.biggrid-cat-wrapper .penci-biggrid .penci-fixh',
			],
			'penci_tag_featured_height'         => [
				'--bgh' => '.biggrid-tag-wrapper .penci-biggrid .penci-fixh',
			],
		];

		$mobile_css = [
			'penci_arf_mheight'               => [
				'--bgh' => '.biggrid-archive-wrapper .penci-biggrid .penci-fixh',
			],
			'penci_category_featured_mheight' => [
				'--bgh' => '.biggrid-cat-wrapper .penci-biggrid .penci-fixh',
			],
			'penci_tag_featured_mheight'      => [
				'--bgh' => '.biggrid-tag-wrapper .penci-biggrid .penci-fixh',
			],
			'penci_arf_t_mfs'                 => [
				'font-size' => '.biggrid-archive-wrapper .penci-bgrid-content-on .pcbg-content-inner .pcbg-title, .biggrid-archive-wrapper .penci-bgrid-content-on .pcbg-content-inner .pcbg-title a',
			],
			'penci_arf_t_bmfs'                => [
				'font-size' => '.biggrid-archive-wrapper .penci-biggrid-data.penci-fixh .pcbg-big-item .pcbg-content-inner .pcbg-title, .biggrid-archive-wrapper .penci-biggrid-data.penci-fixh .pcbg-big-item .pcbg-content-inner .pcbg-title a',
			],
		];

		$tablet_css = [
			'penci_arf_theight'          => [
				'--bgh' => '.biggrid-archive-wrapper .penci-biggrid .penci-fixh',
			],
			'penci_cat_featured_theight' => [
				'--bgh' => '.biggrid-cat-wrapper .penci-biggrid .penci-fixh',
			],
			'penci_tag_featured_theight' => [
				'--bgh' => '.biggrid-tag-wrapper .penci-biggrid .penci-fixh',
			],
		];

		penci_featured_render_css( $custom_colors );
		penci_featured_render_css( $mobile_css, 'mobile' );
		penci_featured_render_css( $tablet_css, 'tablet' );

		$gap_size = get_theme_mod( 'penci_arf_gap' );
		if ( $gap_size ) {
			echo '.biggrid-archive-wrapper .penci-bgstyle-1 .penci-dflex{';
			echo 'margin-left: calc(-' . $gap_size . 'px/2); margin-right: calc(-' . $gap_size . 'px/2); width: calc(100% + ' . $gap_size . 'px);';
			echo '}';
			echo '.biggrid-archive-wrapper .penci-bgstyle-1 .penci-bgitem{';
			echo 'padding-left: calc(' . $gap_size . 'px/2); padding-right: calc(' . $gap_size . 'px/2); margin-bottom: ' . $gap_size . 'px';
			echo '}';
		}
	}
}

if ( ! function_exists( 'penci_featured_render_css' ) ) {
	function penci_featured_render_css( $custom_colors, $mobile = false ) {

		$before = $after = '';

		if ( 'mobile' == $mobile ) {
			$before = '@media only screen and (max-width: 767px){';
			$after  = '}';
		}

		if ( 'tablet' == $mobile ) {
			$before = '@media only screen and (min-width: 768px) and (max-width: 1169px){';
			$after  = '}';
		}

		foreach ( $custom_colors as $option => $rules ) {
			$value  = get_theme_mod( $option );
			$prefix = is_numeric( $value ) ? 'px' : '';
			$prefix = strpos( $option, 'img_ratio' ) !== false ? '%' : $prefix;
			if ( $value ) {
				foreach ( $rules as $prop => $selector ) {
					echo $before . $selector . '{' . $prop . ':' . $value . $prefix . ';}' . $after;
				}
			}
		}
	}
}

if ( ! function_exists( 'penci_featured_title_check' ) ) {
	function penci_featured_title_check() {
		$category     = get_queried_object();
		$featured_ids = count( penci_featured_exclude_ids() );

		return $featured_ids == $category->count;
	}
}
