<?php
$settings             = vc_map_get_attributes( $this->getShortcode(), $atts );
$type                 = $settings['type'] ? $settings['type'] : '';
$dformat              = $settings['dformat'] ? $settings['dformat'] : '';
$date_pos             = $settings['date_pos'] ? $settings['date_pos'] : 'left';
$column               = $settings['column'] ? $settings['column'] : '3';
$tab_column           = $settings['tab_column'] ? $settings['tab_column'] : '2';
$mb_column            = $settings['mb_column'] ? $settings['mb_column'] : '1';
$imgpos               = $settings['imgpos'] ? $settings['imgpos'] : 'left';
$thumb_size_imgtop    = 'top' == $imgpos ? 'penci-thumb' : 'penci-thumb-small';
$thumb_size           = $settings['thumb_size'] ? $settings['thumb_size'] : $thumb_size_imgtop;
$mthumb_size          = $settings['mthumb_size'] ? $settings['mthumb_size'] : $thumb_size_imgtop;
$post_meta            = $settings['post_meta'] ? explode( ',', $settings['post_meta'] ) : array();
$primary_cat          = $settings['primary_cat'] ? $settings['primary_cat'] : '';
$title_length         = $settings['title_length'] ? $settings['title_length'] : '';
$excerpt_pos          = $settings['excerpt_pos'] ? $settings['excerpt_pos'] : 'below';
$paging               = $settings['paging'] ? $settings['paging'] : 'none';
$paging_align         = $settings['paging_align'] ? $settings['paging_align'] : 'align-center';
$archive_buider_check = isset( $settings['posts_post_type'] ) ? $settings['posts_post_type'] : '';
if ( 'top' == $imgpos ) {
	$excerpt_pos = 'side';
}
$rmstyle        = $settings['rmstyle'] ? $settings['rmstyle'] : 'filled';
$excerpt_length = $settings['excerpt_length'] ? $settings['excerpt_length'] : 15;

$thumbnail = $thumb_size;
if ( penci_is_mobile() ) {
	$thumbnail = $mthumb_size;
}

$inner_wrapper_class = 'pcsl-inner penci-clearfix';
$inner_wrapper_class .= ' pcsl-' . $type;
if ( 'crs' == $type ) {
	$inner_wrapper_class .= ' penci-owl-carousel penci-owl-carousel-slider swiper';
}
if ( 'nlist' == $type ) {
	$column     = '1';
	$tab_column = '1';
	$mb_column  = '1';
	if ( in_array( 'date', $post_meta ) ) {
		$inner_wrapper_class .= ' pcsl-hdate';
	}
}
$inner_wrapper_class .= ' pcsl-imgpos-' . $imgpos;
$inner_wrapper_class .= ' pcsl-col-' . $column;
$inner_wrapper_class .= ' pcsl-tabcol-' . $tab_column;
$inner_wrapper_class .= ' pcsl-mobcol-' . $mb_column;
if ( 'yes' == $settings['nocrop'] ) {
	$inner_wrapper_class .= ' pcsl-nocrop';
}
if ( 'yes' == $settings['hide_cat_mobile'] ) {
	$inner_wrapper_class .= ' pcsl-cat-mhide';
}
if ( 'yes' == $settings['hide_meta_mobile'] ) {
	$inner_wrapper_class .= ' pcsl-meta-mhide';
}
if ( 'yes' == $settings['hide_excerpt_mobile'] ) {
	$inner_wrapper_class .= ' pcsl-excerpt-mhide';
}
if ( 'yes' == $settings['hide_rm_mobile'] ) {
	$inner_wrapper_class .= ' pcsl-rm-mhide';
}
if ( 'yes' == $settings['imgtop_mobile'] && in_array( $imgpos, array( 'left', 'right' ) ) ) {
	$inner_wrapper_class .= ' pcsl-imgtopmobile';
}
if ( 'yes' == $settings['ver_border'] ) {
	$inner_wrapper_class .= ' pcsl-verbd';
}

$data_slider = '';
if ( 'crs' == $type ) {
	$data_slider .= $settings['showdots'] == 'yes' ? ' data-dots="true"' : '';
	$data_slider .= $settings['shownav'] == 'yes' ? ' data-nav="true"' : '';
	$data_slider .= $settings['loop'] == 'yes' ? ' data-loop="true"' : '';
	$data_slider .= ' data-auto="' . ( 'yes' == $settings['autoplay'] ? 'true' : 'false' ) . '"';
	$data_slider .= $settings['auto_time'] ? ' data-autotime="' . $settings['auto_time'] . '"' : ' data-autotime="4000"';
	$data_slider .= $settings['speed'] ? ' data-speed="' . $settings['speed'] . '"' : ' data-speed="600"';

	$data_slider .= ' data-item="' . ( isset( $settings['column'] ) && $settings['column'] ? $settings['column'] : '3' ) . '"';
	$data_slider .= ' data-desktop="' . ( isset( $settings['column'] ) && $settings['column'] ? $settings['column'] : '3' ) . '" ';
	$data_slider .= ' data-tablet="' . ( isset( $settings['tab_column'] ) && $settings['tab_column'] ? $settings['tab_column'] : '2' ) . '"';
	$data_slider .= ' data-tabsmall="' . ( isset( $settings['tab_column'] ) && $settings['tab_column'] ? $settings['tab_column'] : '2' ) . '"';
	$data_slider .= ' data-mobile="' . ( isset( $settings['mb_column'] ) && $settings['mb_column'] ? $settings['mb_column'] : '1' ) . '"';
}

$original_postype = isset( $settings['posts_post_type'] ) && $settings['posts_post_type'] ? $settings['posts_post_type'] : '';

if ( in_array( $original_postype, [ 'current_query', 'related_posts' ] ) && penci_is_builder_template() ) {
	$settings['posts_post_type'] = 'post';
}

$args          = penci_build_args_query( $settings['build_query'] );
$args['paged'] = max( get_query_var( 'paged' ), get_query_var( 'page' ), 1 );
if ( in_array( $original_postype, [ 'current_query', 'related_posts' ] ) ) {
	$paged  = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
	$ppp    = isset( $args['posts_per_page'] ) && $args['posts_per_page'] ? $args['posts_per_page'] : get_option( 'posts_per_page' );
	$ppp    = isset( $settings['arposts_per_page'] ) && $settings['arposts_per_page'] ? $settings['arposts_per_page'] : $ppp;
	$offset = 0;
	if ( $ppp ) {
		$args['posts_per_page'] = $ppp;
	}
	if ( $settings['arposts_new'] == 'yes' ) {
		$args['paged'] = 1;
	}
	if ( 0 < $settings['offset'] ) {
		$offset = $settings['offset'];
	}

	if ( ! empty( $settings['offset'] ) && $paged > 1 ) {
		$offset = $settings['offset'] + ( ( $paged - 1 ) * $ppp );
	}

	if ( $offset ) {
		$args['offset'] = $offset;
	}
}
$query_smalllist = new \WP_Query( $args );


$block_id = 'pcblock_' . rand( 0, 9999 );

$settings['blockid'] = $block_id;

add_action( 'penci_block_title_extra_' . $block_id, function () use ( $settings, $args, $query_smalllist ) {
	$link_group_cats            = $settings['biggrid_ajaxfilter_cat'];
	$link_group_tags            = $settings['biggrid_ajaxfilter_tag'];
	$link_group_author          = $settings['biggrid_ajaxfilter_author'];
	$link_group_out             = $link_group_out_before = $link_group_out_after = '';
	$settings['posts_per_page'] = isset( $settings['posts_per_page'] ) && $settings['posts_per_page'] ? $settings['posts_per_page'] : get_option( 'posts_per_page' );
	$link_group_out_before      .= '<nav data-ppp="' . $settings['posts_per_page'] . '" data-blockid="' . $settings['blockid'] . '" data-query_type="ajaxtab" data-more="' . esc_attr( $settings['group_more_link_text'] ) . '" class="pcnav-lgroup"><ul class="pcflx">';
	$link_group_out_after       = '</ul></nav>';
	$has_link                   = false;

	if ( $link_group_cats ) {
		$has_link        = true;
		$link_group_cats = explode( ',', $link_group_cats );
		foreach ( $link_group_cats as $link_cat ) {
			$link_group_out .= '<li><a class="pc-ajaxfil-link" href="#" data-paged="1" data-id="' . md5( 'cat-link-' . $link_cat ) . '" data-cat="' . $link_cat . '">' . get_term_field( 'name', $link_cat ) . '</a></li>';
		}
	}

	if ( $link_group_tags ) {
		$has_link        = true;
		$link_group_tags = explode( ',', $link_group_tags );
		foreach ( $link_group_tags as $link_tag ) {
			$link_group_out .= '<li><a class="pc-ajaxfil-link" href="#" data-paged="1" data-id="' . md5( 'tag-link-' . $link_tag ) . '" data-tag="' . $link_tag . '">' . get_term_field( 'name', $link_tag ) . '</a></li>';
		}
	}

	if ( $link_group_author ) {
		$has_link          = true;
		$link_group_author = explode( ',', $link_group_author );
		foreach ( $link_group_author as $author ) {
			$link_group_out .= '<li><a class="pc-ajaxfil-link" href="#" data-paged="1" data-id="' . md5( 'author-link-' . $author ) . '" data-cat="' . $author . '">' . get_the_author_meta( 'nicename', $author ) . '</a></li>';
		}
	}

	if ( 'nextprev' == $settings['paging'] ) {
		$link_group_out .= '</ul><ul class="pcflx-nav">';
		$link_group_out .= '<li class="pcaj-nav-item pcaj-prev"><a class="disable pc-ajaxfil-link pcaj-nav-link prev" data-id="" href="#"><i class="penciicon-left-chevron"></i></a></li>';
		$link_group_out .= '<li class="pcaj-nav-item pcaj-next"><a class="pc-ajaxfil-link pcaj-nav-link next" data-id="" href="#"><i class="penciicon-right-chevron"></i></a></li>';
	}

	if ( $link_group_out ) {
		$first_class = $has_link ? 'visible' : 'hidden-item';
		$df_datamax  = '';
		if ( 'nextprev' == $settings['paging'] ) {
			$df_datamax = 'data-maxp="' . $query_smalllist->max_num_pages . '" ';
		}
		$link_group_out_before .= '<li class="all ' . $first_class . '"><a ' . $df_datamax . 'data-paged="1" class="pc-ajaxfil-link current-item" data-id="default" href="#">' . $settings['group_more_defaultab_text'] . '</a></li>';

		wp_enqueue_script( 'penci_ajax_filter_slist' );
		echo $link_group_out_before . $link_group_out . $link_group_out_after;
	}
} );

if ( 'none' !== 'paging' ) {
	$ajax_data          = $settings;
	$ajax_data['query'] = $args;
	\Soledad_VC_Shortcodes::get_block_script( $settings['blockid'], $ajax_data );
}
$block_id = Penci_Vc_Helper::get_unique_id_block( 'small_list' );
?>
    <div id="<?php echo $block_id; ?>" class="penci-wrapper-smalllist">
		<?php Penci_Vc_Helper::markup_block_title( $settings ); ?>
		<?php
		if ( ! $query_smalllist->have_posts() ) {
			echo '<p>' . penci_get_setting( 'penci_ajaxsearch_no_post' ) . '</p>';
		}

		?>
        <div class="penci-smalllist-wrapper">
			<?php
			if ( $query_smalllist->have_posts() ) {
				?>
                <div class="penci-smalllist pcsl-wrapper pwsl-id-default">
                    <div class="<?php echo $inner_wrapper_class; ?>"<?php echo $data_slider; ?>>

						<?php
						$item_class = ' normal-item';
						if ( 'crs' == $type ) {
							echo '<div class="swiper-wrapper">';
							$item_class = ' swiper-slide';
						}
						while ( $query_smalllist->have_posts() ) : $query_smalllist->the_post(); ?>
                            <div class="pcsl-item<?php if ( 'yes' == $settings['hide_thumb'] || ! has_post_thumbnail() ) {
								echo ' pcsl-nothumb';
							}
							echo $item_class; ?>">
                                <div class="pcsl-itemin">
                                    <div class="pcsl-iteminer">
										<?php if ( in_array( 'date', $post_meta ) && 'nlist' == $type ) { ?>
                                            <div class="pcsl-date pcsl-dpos-<?php echo $date_pos; ?>">
                                                <span class="sl-date"><?php penci_soledad_time_link( null, $dformat ); ?></span>
                                            </div>
										<?php } ?>

										<?php if ( 'yes' != $settings['hide_thumb'] && has_post_thumbnail() ) { ?>
                                            <div class="pcsl-thumb">
												<?php
												do_action( 'penci_bookmark_post', get_the_ID(), 'small' );
												/* Display Review Piechart  */
												if ( 'yes' == $settings['show_reviewpie'] && function_exists( 'penci_display_piechart_review_html' ) ) {
													penci_display_piechart_review_html( get_the_ID(), 'small' );
												}
												?>
												<?php if ( 'yes' == $settings['show_formaticon'] ): ?>
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
												<?php if ( 'yes' != $settings['disable_lazy'] ) { ?>
                                                    <a href="<?php the_permalink(); ?>"
                                                       title="<?php echo wp_strip_all_tags( get_the_title() ); ?>"
                                                       class="penci-image-holder penci-lazy"<?php if ( 'yes' == $settings['nocrop'] ) {
														echo ' style="padding-bottom: ' . penci_get_featured_image_padding_markup( get_the_ID(), $thumbnail, true ) . '%"';
													} ?>
                                                       data-bgset="<?php echo penci_get_featured_image_size( get_the_ID(), $thumbnail ); ?>">
                                                    </a>
												<?php } else { ?>
                                                    <a title="<?php echo wp_strip_all_tags( get_the_title() ); ?>"
                                                       href="<?php the_permalink(); ?>" class="penci-image-holder"
                                                       style="background-image: url('<?php echo penci_get_featured_image_size( get_the_ID(), $thumbnail ); ?>');<?php if ( 'yes' == $settings['nocrop'] ) {
														   echo 'padding-bottom: ' . penci_get_featured_image_padding_markup( get_the_ID(), $thumbnail, true ) . '%';
													   } ?>">
                                                    </a>
												<?php } ?>
                                            </div>
										<?php } ?>
                                        <div class="pcsl-content">
											<?php if ( in_array( 'cat', $post_meta ) ) : ?>
                                                <div class="cat pcsl-cat">
													<?php penci_category( '', $primary_cat ); ?>
                                                </div>
											<?php endif; ?>

											<?php if ( in_array( 'title', $post_meta ) ) : ?>
                                                <div class="pcsl-title">
                                                    <a href="<?php the_permalink(); ?>"<?php if ( $title_length ): echo ' title="' . wp_strip_all_tags( get_the_title() ) . '"'; endif; ?>><?php if ( ! $title_length ) {
															the_title();
														} else {
															echo wp_trim_words( wp_strip_all_tags( get_the_title() ), $title_length, '...' );
														} ?></a>
                                                </div>
											<?php endif; ?>

											<?php if ( $settings['cspost_enable'] || ( count( array_intersect( array(
														'author',
														'date',
														'comment',
														'views',
														'reading'
													), $post_meta ) ) > 0 && 'nlist' != $type ) || ( count( array_intersect( array(
														'author',
														'comment',
														'views',
														'reading'
													), $post_meta ) ) > 0 && 'nlist' == $type ) ) { ?>
                                                <div class="grid-post-box-meta pcsl-meta">
													<?php if ( in_array( 'author', $post_meta ) ) : ?>
                                                        <span class="sl-date-author author-italic">
													<?php echo penci_get_setting( 'penci_trans_by' ); ?> <a
                                                                    class="url fn n"
                                                                    href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>"><?php the_author(); ?></a>
													</span>
													<?php endif; ?>
													<?php if ( in_array( 'date', $post_meta ) && 'nlist' != $type ) : ?>
                                                        <span class="sl-date"><?php penci_soledad_time_link( null, $dformat ); ?></span>
													<?php endif; ?>
													<?php if ( in_array( 'comment', $post_meta ) ) : ?>
                                                        <span class="sl-comment">
												<a href="<?php comments_link(); ?> "><?php comments_number( '0 ' . penci_get_setting( 'penci_trans_comments' ), '1 ' . penci_get_setting( 'penci_trans_comment' ), '% ' . penci_get_setting( 'penci_trans_comments' ) ); ?></a>
											</span>
													<?php endif; ?>
													<?php
													if ( in_array( 'views', $post_meta ) ) {
														echo '<span class="sl-views">';
														echo penci_get_post_views( get_the_ID() );
														echo ' ' . penci_get_setting( 'penci_trans_countviews' );
														echo '</span>';
													}
													?>
													<?php
													$hide_readtime = in_array( 'reading', $post_meta ) ? false : true;
													if ( penci_isshow_reading_time( $hide_readtime ) ): ?>
                                                        <span class="sl-readtime"><?php penci_reading_time(); ?></span>
													<?php endif; ?>
													<?php echo penci_show_custom_meta_fields( [
														'validator' => $settings['cspost_enable'],
														'keys'      => $settings['cspost_cpost_meta'],
														'acf'       => $settings['cspost_cpost_acf_meta'],
														'label'     => $settings['cspost_cpost_meta_label'],
														'divider'   => $settings['cspost_cpost_meta_divider'],
													] ); ?>
													<?php do_action( 'penci_extra_meta' ); ?>
                                                </div>
											<?php } ?>

											<?php if ( 'yes' == $settings['show_excerpt'] && 'side' == $excerpt_pos ) { ?>
                                                <div class="pcbg-pexcerpt pcsl-pexcerpt">
													<?php penci_the_excerpt( $excerpt_length ); ?>
                                                </div>
											<?php } ?>
											<?php if ( 'yes' == $settings['show_readmore'] && 'side' == $excerpt_pos ) { ?>
                                                <div class="pcsl-readmore">
                                                    <a href="<?php the_permalink(); ?>"
                                                       class="pcsl-readmorebtn pcsl-btns-<?php echo $rmstyle; ?>">
														<?php echo penci_get_setting( 'penci_trans_read_more' ); ?>
                                                    </a>
                                                </div>
											<?php } ?>
                                        </div>

										<?php if ( ( 'yes' == $settings['show_excerpt'] || 'yes' == $settings['show_readmore'] ) && 'below' == $excerpt_pos ) { ?>
                                            <div class="pcsl-flex-full">
												<?php if ( 'yes' == $settings['show_excerpt'] ) { ?>
                                                    <div class="pcbg-pexcerpt pcsl-pexcerpt">
														<?php penci_the_excerpt( $excerpt_length ); ?>
                                                    </div>
												<?php } ?>
												<?php if ( 'yes' == $settings['show_readmore'] ) { ?>
                                                    <div class="pcsl-readmore">
                                                        <a href="<?php the_permalink(); ?>"
                                                           class="pcsl-readmorebtn pcsl-btns-<?php echo $rmstyle; ?>">
															<?php echo penci_get_setting( 'penci_trans_read_more' ); ?>
                                                        </a>
                                                    </div>
												<?php } ?>
                                            </div>
										<?php } ?>
                                    </div>
                                </div>
                            </div>
						<?php endwhile;
						if ( 'crs' == $type ) {
							echo '</div>';
						}
						?>
                    </div>

					<?php
					if ( 'loadmore' == $paging || 'scroll' == $paging ) {
						$data_settings          = array();
						$data_settings['query'] = $args;
						$data_paged             = max( get_query_var( 'paged' ), get_query_var( 'page' ), 1 );

						$data_settings_ajax = htmlentities( json_encode( $data_settings ), ENT_QUOTES, "UTF-8" );

						$button_class = ' penci-ajax-more penci-slajax-more-click';
						if ( 'loadmore' == $paging ):
							wp_enqueue_script( 'penci_slajax_more_posts' );
						endif;
						if ( 'scroll' == $paging ):
							$button_class = ' penci-ajax-more penci-slajax-more-scroll';
							wp_enqueue_script( 'penci_slajax_more_scroll' );
						endif;
						$data_archive_type  = '';
						$data_archive_value = '';
						if ( is_category() ) :
							$category           = get_category( get_query_var( 'cat' ) );
							$cat_id             = isset( $category->cat_ID ) ? $category->cat_ID : '';
							$data_archive_type  = 'cat';
							$data_archive_value = $cat_id;
							$opt_cat            = 'category_' . $cat_id;
							$cat_meta           = get_option( $opt_cat );
							$sidebar_opts       = isset( $cat_meta['cat_sidebar_display'] ) ? $cat_meta['cat_sidebar_display'] : '';
							if ( $sidebar_opts == 'no' ):
								$data_template = 'no-sidebar';
                            elseif ( $sidebar_opts == 'left' || $sidebar_opts == 'right' ):
								$data_template = 'sidebar';
							endif;

                        elseif ( is_tag() ) :
							$tag                = get_queried_object();
							$tag_id             = isset( $tag->term_id ) ? $tag->term_id : '';
							$data_archive_type  = 'tag';
							$data_archive_value = $tag_id;
                        elseif ( is_day() ) :
							$data_archive_type  = 'day';
							$data_archive_value = get_the_date( 'm|d|Y' );
                        elseif ( is_month() ) :
							$data_archive_type  = 'month';
							$data_archive_value = get_the_date( 'm|d|Y' );
                        elseif ( is_year() ) :
							$data_archive_type  = 'year';
							$data_archive_value = get_the_date( 'm|d|Y' );
                        elseif ( is_search() ) :
							$data_archive_type  = 'search';
							$data_archive_value = get_search_query();
                        elseif ( is_author() ) :

							global $authordata;
							$user_id = isset( $authordata->ID ) ? $authordata->ID : 0;

							$data_archive_type  = 'author';
							$data_archive_value = $user_id;
                        elseif ( is_archive() ) :
							$queried_object = get_queried_object();
							$term_id        = isset( $queried_object->term_id ) ? $queried_object->term_id : '';
							$tax            = get_taxonomy( get_queried_object()->taxonomy );
							$tax_name       = isset( $tax->name ) ? $tax->name : '';

							if ( $term_id && $tax_name ) {
								$data_archive_type  = $tax_name;
								$data_archive_value = $term_id;
							}
						endif;
						?>
                        <div class="pcbg-paging penci-pagination <?php echo 'pcbg-paging-' . $paging_align . $button_class; ?>">
                            <a class="penci-ajax-more-button" href="#"
								<?php if ( $data_archive_type && $data_archive_value ): ?>
                                    data-archivetype="<?php echo $data_archive_type; ?>"
                                    data-archivevalue="<?php echo $data_archive_value; ?>"
                                    data-arppp="<?php echo $ppp; ?>"
								<?php endif; ?>
                               data-blockid="<?php echo $settings['blockid']; ?>"
                               data-query_type="<?php echo $archive_buider_check; ?>"
                               data-settings="<?php echo $data_settings_ajax; ?>"
                               data-pagednum="<?php echo( (int) $data_paged + 1 ); ?>"
                               data-mes="<?php echo penci_get_setting( 'penci_trans_no_more_posts' ); ?>">
                                <span class="ajax-more-text"><?php echo penci_get_setting( 'penci_trans_load_more_posts' ); ?></span><span
                                        class="ajaxdot"></span><?php penci_fawesome_icon( 'fas fa-sync' ); ?>
                            </a>
                        </div>
						<?php
					} elseif ( 'numbers' == $paging ) {
						echo penci_pagination_numbers( $query_smalllist, $paging_align );
					}
					?>

                </div>
				<?php
			} /* End check if query exists posts */
			if ( $settings['biggrid_ajaxfilter_cat'] || $settings['biggrid_ajaxfilter_tag'] || $settings['biggrid_ajaxfilter_author'] || 'nextprev' == $settings['paging'] ) {
				echo penci_get_html_animation_loading( $settings['biggrid_ajax_loading_style'] );
			}
			?>
        </div>
    </div>
<?php
wp_reset_postdata();

$block_id_css  = '#' . $block_id;
$block_id_css2 = '#' . $block_id;
$css_custom    = Penci_Vc_Helper::get_heading_block_css( $block_id_css, $settings );

if ( $settings['title_color'] ) {
	$css_custom .= $block_id_css2 . ' .pcsl-content .pcsl-title a{ color: ' . esc_attr( $settings['title_color'] ) . '; }';
}
if ( $settings['title_hcolor'] ) {
	$css_custom .= $block_id_css2 . ' .pcsl-content .pcsl-title a:hover{ color: ' . esc_attr( $settings['title_hcolor'] ) . '; }';
}
if ( $settings['date_color'] ) {
	$css_custom .= $block_id_css2 . ' .pcsl-hdate .pcsl-date span{ color: ' . esc_attr( $settings['date_color'] ) . '; }';
}
if ( $settings['meta_color'] ) {
	$css_custom .= $block_id_css2 . ' .grid-post-box-meta span{ color: ' . esc_attr( $settings['ppcount_color'] ) . '; }';
}
if ( $settings['link_color'] ) {
	$css_custom .= $block_id_css2 . ' .grid-post-box-meta span a{ color: ' . esc_attr( $settings['links_color'] ) . '; }';
}
if ( $settings['link_hcolor'] ) {
	$css_custom .= $block_id_css2 . ' .grid-post-box-meta span a:hover{ color: ' . esc_attr( $settings['links_hcolor'] ) . '; }';
}
if ( $settings['excerpt_color'] ) {
	$css_custom .= $block_id_css2 . ' .pcbg-pexcerpt, ' . $block_id_css . ' .pcbg-pexcerpt p{ color: ' . esc_attr( $settings['excerpt_color'] ) . '; }';
}
if ( $settings['title_fize'] ) {
	$css_custom .= penci_extract_responsive_fsize( $block_id_css . ' .pcbg-pexcerpt, ' . $block_id_css . ' .pcbg-pexcerpt p', 'font-size', $settings['title_fize'] );
}
if ( $settings['meta_fize'] ) {
	$css_custom .= penci_extract_responsive_fsize( $block_id_css . ' .grid-post-box-meta span', 'font-size', $settings['meta_fize'] );
}
if ( $settings['date_fize'] ) {
	$css_custom .= penci_extract_responsive_fsize( $block_id_css . ' .pcsl-hdate .pcsl-date span', 'font-size', $settings['meta_fize'] );
}

if ( $settings['responsive_spacing'] ) {
	$css_custom .= penci_extract_spacing_style( $block_id_css, $settings['responsive_spacing'] );
}

$css_custom .= Penci_Vc_Helper::get_bookmark_icon_css( $block_id_css, $atts );

if ( $css_custom ) {
	echo '<style>';
	echo $css_custom;
	echo '</style>';
}
