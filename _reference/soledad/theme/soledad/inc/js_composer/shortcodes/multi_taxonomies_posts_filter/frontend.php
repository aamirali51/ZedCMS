<?php
$settings             = vc_map_get_attributes( $this->getShortcode(), $atts );
$dformat              = $settings['dformat'] ? $settings['dformat'] : '';
$date_pos             = $settings['date_pos'] ? $settings['date_pos'] : 'left';
$column               = $settings['column'] ? $settings['column'] : '1';
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
$inner_wrapper_class .= ' pcsl-grid';
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

$tax      = isset( $settings['tax'] ) ? $settings['tax'] : 'category';
$order    = isset( $settings['order'] ) ? $settings['order'] : 'term_id';
$orderby  = isset( $settings['orderby'] ) ? $settings['orderby'] : 'DESC';
$maxitems = isset( $settings['maxitems'] ) ? $settings['maxitems'] : 10;

$term_args = [
	'taxonomy' => $tax,
	'order'    => $order,
	'orderby'  => $orderby,
	'number'   => $maxitems,
];

if ( isset( $settings['taxonomies_ex'] ) && $settings['taxonomies_ex'] ) {
	$term_args['exclude'] = explode(',',$settings['taxonomies_ex']);
}

if ( isset( $settings['taxonomies_in'] ) && $settings['taxonomies_in'] ) {
	$term_args['include'] = explode(',',$settings['taxonomies_in']);
}

// posts args

$post_args = [
	'orderby'        => isset( $settings['post_orderby'] ) ? $settings['post_orderby'] : '',
	'order'          => $settings['post_order'],
	'posts_per_page' => $settings['posts_per_page'],
];

if ( isset( $settings['taxonomies_ex'] ) && $settings['taxonomies_ex'] ) {
	$post_args['tax_query'][] = [
		'operator' => 'NOT IN',
		'taxonomy' => $tax,
		'terms'    => explode(',',$settings['taxonomies_ex']),
	];
}

if ( isset( $settings['taxonomies_in'] ) && $settings['taxonomies_in'] ) {
	$post_args['tax_query'][] = [
		'operator' => 'IN',
		'taxonomy' => $tax,
		'terms'    => explode(',',$settings['taxonomies_in']),
	];
}

if ( $settings['taxonomies_ex'] || $settings['taxonomies_in'] ) {
	$post_args['tax_query']['relation'] = 'AND';
}

$query_smalllist = new WP_Query( $post_args );

$data_settings = [
	'hide_thumb'      => $settings['hide_thumb'],
	'show_reviewpie'  => $settings['show_reviewpie'],
	'show_formaticon' => $settings['show_formaticon'],
	'disable_lazy'    => $settings['disable_lazy'],
	'nocrop'          => $settings['nocrop'],
	'post_meta'       => $post_meta,
	'excerpt_length'  => $excerpt_length,
	'title_length'    => $title_length,
	'rmstyle'         => $rmstyle,
	'dformat'         => $dformat,
	'excerpt_pos'     => $excerpt_pos,
	'primary_cat'     => $primary_cat,
	'thumbnail'       => $thumbnail,
	'column'          => $column,
	'show_excerpt'    => $settings['show_excerpt'],
	'show_readmore'   => $settings['show_readmore'],
];

$terms_list   = get_terms( $term_args );
$count_html   = '';
$sticky_class = $settings['tax_sticky'] ? 'penci-mtp-sticky' : 'penci-mtp-non-sticky';
wp_enqueue_script( 'mtp-filters' );

$block_id = Penci_Vc_Helper::get_unique_id_block( 'small_list' );
?>
    <div id="<?php echo $block_id; ?>" class="penci-wrapper-tax-posts-filters">
		<?php Penci_Vc_Helper::markup_block_title( $settings ); ?>
		<?php
		if ( ! $query_smalllist->have_posts() ) {
			echo '<p>' . penci_get_setting( 'penci_ajaxsearch_no_post' ) . '</p>';
		}

		?>

        <div class="penci-mtp-filters-wrapper <?php echo $sticky_class; ?> pctp-<?php echo $settings['tax_position']; ?>"
             data-settings='<?php echo json_encode( $data_settings ); ?>'
             data-tax="<?php echo $tax; ?>" data-request-id="<?php echo wp_create_nonce( 'penci-mtp-filters' ); ?>"
             data-paged="1" data-filter-terms=""
             data-query='<?php echo json_encode( $post_args ); ?>'>
            <div class="penci-mtp-filters-terms">
                <div class="theiaStickySidebar">
                    <a href="#" aria-label="<?php echo penci_get_setting( 'penci_trans_all' ); ?>"
                       class="penci-mtp-filters-mobile">
                        <span><?php echo penci_get_setting( 'penci_trans_all' ); ?></span>
                    </a>
                    <a href="#" aria-label="<?php echo penci_get_setting( 'penci_trans_close' ); ?>"
                       class="penci-mtp-filters-close">
                        <span><?php echo penci_get_setting( 'penci_trans_close' ); ?></span>
                    </a>
                    <ul class="penci-mtp-filters-main">
                        <li>
                            <a class="pcmtp-f-term" data-id="all" href="#">
								<?php
								echo penci_get_setting( 'penci_trans_all' );
								if ( $settings['count'] == 'yes' ) {
									echo '<span class="count">' . $query_smalllist->found_posts . '</span>';
								}
								?>
                            </a>
                        </li>
						<?php
						foreach ( $terms_list as $order => $term_data ) {
							if ( $settings['count'] == 'yes' ) {
								$count_html = '<span class="count">' . $term_data->count . '</span>';
							}
							echo '<li><a class="pcmtp-f-term" data-id="' . $term_data->term_id . '" href="#">' . $term_data->name . $count_html . '</a></li>';
						}
						?>
                    </ul>
                </div>
            </div>
            <div class="penci-mtp-filters-posts">
                <div class="theiaStickySidebar">

                    <div class="penci-smalllist-wrapper">
						<?php
						if ( $query_smalllist->have_posts() ) {
							?>
                            <div class="penci-smalllist pcsl-wrapper pwsl-id-default">
                                <div class="<?php echo $inner_wrapper_class; ?>">

									<?php
									$item_class = ' normal-item';
									while ( $query_smalllist->have_posts() ) : $query_smalllist->the_post(); ?>
                                        <div class="pcsl-item<?php if ( 'yes' == $settings['hide_thumb'] || ! has_post_thumbnail() ) {
											echo ' pcsl-nothumb';
										}
										echo $item_class; ?>">
                                            <div class="pcsl-itemin">
                                                <div class="pcsl-iteminer">


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
                                                                    <a href="<?php the_permalink() ?>"
                                                                       class="icon-post-format"
                                                                       aria-label="Icon"><?php penci_fawesome_icon( 'fas fa-play' ); ?></a>
																<?php endif; ?>
																<?php if ( has_post_format( 'gallery' ) ) : ?>
                                                                    <a href="<?php the_permalink() ?>"
                                                                       class="icon-post-format"
                                                                       aria-label="Icon"><?php penci_fawesome_icon( 'far fa-image' ); ?></a>
																<?php endif; ?>
																<?php if ( has_post_format( 'audio' ) ) : ?>
                                                                    <a href="<?php the_permalink() ?>"
                                                                       class="icon-post-format"
                                                                       aria-label="Icon"><?php penci_fawesome_icon( 'fas fa-music' ); ?></a>
																<?php endif; ?>
																<?php if ( has_post_format( 'link' ) ) : ?>
                                                                    <a href="<?php the_permalink() ?>"
                                                                       class="icon-post-format"
                                                                       aria-label="Icon"><?php penci_fawesome_icon( 'fas fa-link' ); ?></a>
																<?php endif; ?>
																<?php if ( has_post_format( 'quote' ) ) : ?>
                                                                    <a href="<?php the_permalink() ?>"
                                                                       class="icon-post-format"
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
                                                                   href="<?php the_permalink(); ?>"
                                                                   class="penci-image-holder"
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
																), $post_meta ) ) > 0 ) || ( count( array_intersect( array(
																	'author',
																	'comment',
																	'views',
																	'reading'
																), $post_meta ) ) > 0 ) ) { ?>

															<?php if ( $column == 1 ) { ?>
                                                                <div class="grid-post-box-meta pcsl-meta pcmtf-mt-alt">
																	<?php if ( in_array( 'date', $post_meta ) ) : ?>
                                                                        <span class="sl-date"><?php penci_soledad_time_link( null, $dformat ); ?></span>
																	<?php endif; ?>
                                                                </div>
															<?php } ?>
                                                            <div class="grid-post-box-meta pcsl-meta">
																<?php if ( in_array( 'author', $post_meta ) ) : ?>
                                                                    <span class="sl-date-author author-italic">
																<?php echo penci_get_setting( 'penci_trans_by' ); ?> <a
                                                                                class="url fn n"
                                                                                href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>"><?php the_author(); ?></a>
																</span>
																<?php endif; ?>
																<?php if ( in_array( 'date', $post_meta ) && $column > 1 ) : ?>
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
									wp_reset_postdata();
									echo penci_get_html_animation_loading( $settings['biggrid_ajax_loading_style'] );
									?>
                                </div>

								<?php
								if ( 'loadmore' == $paging || 'scroll' == $paging ) {
									$button_class = ' penci-ajax-more penci-mtpf-more-click';
									if ( 'scroll' == $paging ):
										$button_class = ' penci-ajax-more penci-mtpf-more-scroll';
									endif;
									?>
                                    <div class="pcbg-paging penci-pagination <?php echo 'pcbg-paging-' . $paging_align . $button_class; ?>">
                                        <a class="penci-ajax-more-button" href="#" aria-label="More Posts"
                                           data-more="<?php echo penci_get_setting( 'penci_trans_load_more_posts' ); ?>"
                                           data-mes="<?php echo penci_get_setting( 'penci_trans_no_more_posts' ); ?>">
                                            <span class="ajax-more-text"><?php echo penci_get_setting( 'penci_trans_load_more_posts' ); ?></span>
                                            <span class="ajaxdot"></span>
											<?php penci_fawesome_icon( 'fas fa-sync' ); ?>
                                        </a>
                                    </div>
								<?php } ?>


                            </div>
							<?php
						} /* End check if query exists posts */
						?>
                    </div>
                </div>
            </div>
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
