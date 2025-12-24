<?php
/**
 * Functions callback when more posts clicked for archive pages
 *
 * @since 6.0
 */
if (!function_exists('penci_archive_more_post_ajax_func')):

	add_action('wp_ajax_nopriv_penci_archive_more_post_ajax', 'penci_archive_more_post_ajax_func');
	add_action('wp_ajax_penci_archive_more_post_ajax', 'penci_archive_more_post_ajax_func');

	function penci_archive_more_post_ajax_func() {
		check_ajax_referer('archive-more-post', 'nonce');

		$ppp = (isset($_POST['ppp'])) ? (int) $_POST['ppp'] : 4;
		$order_get = (isset($_POST['order'])) ? sanitize_text_field( $_POST['order'] ) : '';
		$offset = (isset($_POST['offset'])) ? (int) $_POST['offset'] : 0;
		$layout = (isset($_POST['layout'])) ? sanitize_file_name( $_POST['layout'] ) : 'grid';
		$archivetype = isset($_POST['archivetype']) ? $_POST['archivetype'] : '';
		$archivevalue = isset($_POST['archivevalue']) ? $_POST['archivevalue'] : '';
		$from = (isset($_POST['from'])) ? $_POST['from'] : 'customize';
		$template = (isset($_POST['template'])) ? $_POST['template'] : 'sidebar';
		$md = ( isset( $_POST['md'] ) ) ? sanitize_text_field( $_POST['md'] ) : '';

		$orderby = get_theme_mod('penci_general_post_orderby');

		if (!$orderby):
			$orderby = 'date';
		endif;

		$order = get_theme_mod('penci_general_post_order');
		if (!$order):
			$order = 'DESC';
		endif;

		$order = $order_get ? $order_get : $order;

		$args = array(
			'post_type' => 'post',
			'posts_per_page' => $ppp,
			'post_status' => 'publish',
			'offset' => $offset,
			'orderby' => $orderby,
			'order' => $order,
		);

		if ( 'views' == $order ) {
			$args['orderby'] = 'meta_value_num';
			$args['meta_key'] = 'DESC';
			$args['meta_key'] = penci_get_postviews_key();
		}

		if ( $md ) {
			$md_filter = explode( '-', $md );
			if ( isset( $md_filter[0] ) && isset( $md_filter[1] ) ) {
				$args['year'] = $md_filter[1];
				$args['monthnum'] = $md_filter[0];
			}
		}

		if ( $order == 'view' ) {
			$args['meta_key'] = penci_get_postviews_key();
			$args['order'] = 'DESC';
			$args['orderby'] = 'meta_value_num';
		}
		
		if ( $order == 'comment' ) {
			$args['order'] = 'DESC';
			$args['orderby'] = 'comment_count';
		}

		if ('cat' == $archivetype) {
			$args['cat'] = $archivevalue;
		} elseif ('tag' == $archivetype) {
			$args['tag_id'] = $archivevalue;
		} elseif ('day' == $archivetype) {
			$date_arr = explode('|', $archivevalue);
			$args['date_query'] = array(
				array(
					'year' => isset($date_arr[2]) ? $date_arr[2] : '',
					'month' => isset($date_arr[0]) ? $date_arr[0] : '',
					'day' => isset($date_arr[1]) ? $date_arr[1] : '',
				),
			);
		} elseif ('month' == $archivetype) {
			$date_arr = explode('|', $archivevalue);
			$args['date_query'] = array(
				array(
					'year' => isset($date_arr[2]) ? $date_arr[2] : '',
					'month' => isset($date_arr[0]) ? $date_arr[0] : '',
				),
			);
		} elseif ('year' == $archivetype) {
			$date_arr = explode('|', $archivevalue);
			$args['date_query'] = array(
				array(
					'year' => isset($date_arr[2]) ? $date_arr[2] : '',
				),
			);
		} elseif ('search' == $archivetype) {
			$args['s'] = $archivevalue;

			$post_types = get_post_types(
				array(
					'public' => true,
					'show_in_nav_menus' => true,
				),
				'names'
			);
			$array_include = array();
			foreach ($post_types as $key => $val) {
				$array_include[] = $key;
			}
			$exclude = array(
				'web-story',
				'e-landing-page',
				'penci-block',
				'penci_builder',
				'archive-template',
				'custom-post-template',
			);

			if (!get_theme_mod('penci_include_search_page')) {
				$exclude[] = 'page';
			}

			$array_include = array_diff($array_include, $exclude);

			$args['post_type'] = $array_include;

			
		} elseif ('author' == $archivetype) {
			$args['author'] = $archivevalue;

			if (isset($args['post_type'])) {
				unset($args['post_type']);
			}
		} elseif ($archivetype && $archivevalue) {
			$args['tax_query'] = array(
				array(
					'taxonomy' => $archivetype,
					'field' => 'term_id',
					'terms' => array($archivevalue),
				),
			);
		}

		$loop = new WP_Query($args);

		if ($loop->have_posts()):
			$infeed_ads = get_theme_mod('penci_infeedads_archi_code') ? get_theme_mod('penci_infeedads_archi_code') : '';
			$infeed_num = get_theme_mod('penci_infeedads_archi_num') ? get_theme_mod('penci_infeedads_archi_num') : 3;
			$infeed_full = get_theme_mod('penci_infeedads_archi_layout') ? get_theme_mod('penci_infeedads_archi_layout') : '';
			while ($loop->have_posts()):
				$loop->the_post();
				include locate_template('content-' . sanitize_file_name( $layout ) . '.php');
			endwhile;
		endif;

		wp_reset_postdata();

		exit;
	}
endif;
/**
 * Functions callback when more posts clicked for homepage
 *
 * @since 2.5
 */
if (!function_exists('penci_more_post_ajax_func')) {
	add_action('wp_ajax_nopriv_penci_more_post_ajax', 'penci_more_post_ajax_func');
	add_action('wp_ajax_penci_more_post_ajax', 'penci_more_post_ajax_func');
	function penci_more_post_ajax_func() {
		check_ajax_referer('penci_more_post_ajax', 'nonce');

		$ppp = (isset($_POST['ppp'])) ? $_POST['ppp'] : 4;
		$offset = (isset($_POST['offset'])) ? $_POST['offset'] : 0;
		$layout = (isset($_POST['layout'])) ? sanitize_file_name( $_POST['layout'] ) : 'grid';
		$exclude = (isset($_POST['exclude'])) ? $_POST['exclude'] : '';
		$from = (isset($_POST['from'])) ? $_POST['from'] : 'customize';
		$come_from = (isset($_POST['comefrom'])) ? $_POST['comefrom'] : '';
		$template = (isset($_POST['template'])) ? $_POST['template'] : 'sidebar';
		$penci_mixed_style = (isset($_POST['mixed'])) ? $_POST['mixed'] : 'mixed';
		$penci_vc_query = (isset($_POST['query'])) ? $_POST['query'] : 'query';
		$penci_infeedads = (isset($_POST['infeedads'])) ? $_POST['infeedads'] : array();
		$penci_vc_number = (isset($_POST['number'])) ? $_POST['number'] : '10';
		$query_type = (isset($_POST['query_type'])) ? $_POST['query_type'] : 'post';
		$archivetype = isset($_POST['archivetype']) ? $_POST['archivetype'] : '';
		$archivevalue = isset($_POST['archivevalue']) ? $_POST['archivevalue'] : '';
		$atts = isset($_POST['datafilter']) ? $_POST['datafilter'] : array();
		$tag = isset($_POST['tag']) ? $_POST['tag'] : array();
		$cat = isset($_POST['cat']) ? $_POST['cat'] : array();
		$author = isset($_POST['author']) ? $_POST['author'] : array();
		$pagednum = isset($_POST['pagednum']) ? $_POST['pagednum'] : 1;
		$qtype = isset($_POST['qtype']) ? $_POST['qtype'] : '';

		// Add more option enable or hide
		$standard_title_length = $grid_title_length = 10;

		if ($atts && is_array($atts)) {
			extract($atts);
		}

		$standard_title_length = $standard_title_length ? $standard_title_length : 10;
		$grid_title_length = $grid_title_length ? $grid_title_length : 10;

		// header( "Content-Type: text/html" );

		$orderby = get_theme_mod('penci_general_post_orderby');
		if (!$orderby):
			$orderby = 'date';
		endif;
		$order = get_theme_mod('penci_general_post_order');
		if (!$order):
			$order = 'DESC';
		endif;

		$args = array(
			'post_type' => 'post',
			'posts_per_page' => $ppp,
			'post_status' => 'publish',
			'offset' => $offset,
			'orderby' => $orderby,
			'order' => $order,
		);

		if ($query_type != 'current_query') {

			$exclude_cats = '';
			if ($from == 'vc' && $exclude) {
				$exclude_cats = $exclude;
			} elseif ($from == 'customize' && (get_theme_mod('penci_exclude_featured_cat') || get_theme_mod('penci_home_exclude_cat'))) {
				$feat_query = penci_global_query_featured_slider();

				if ($feat_query) {

					$list_post_ids = array();
					if ($feat_query->have_posts()) {
						while ($feat_query->have_posts()):
							$feat_query->the_post();
							$list_post_ids[] = get_the_ID();
						endwhile;
						wp_reset_postdata();
					}

					$args['post__not_in'] = $list_post_ids;
				}

				if (get_theme_mod('penci_home_exclude_cat')) {
					$exclude_cats = get_theme_mod('penci_home_exclude_cat');
				}
			}

			if ($exclude_cats) {
				$exclude_cats = str_replace(' ', '', $exclude_cats);
				$exclude_array = explode(',', $exclude_cats);

				$args['tax_query'] = array(
					array(
						'taxonomy' => 'category',
						'field' => 'slug',
						'terms' => $exclude_array,
						'operator' => 'NOT IN',
					),
				);
			}
			if ($from == 'vc' && $penci_vc_query) {
				$args = $penci_vc_query;

				$new_offset = (isset($args['offset']) && $args['offset']) ? intval($args['offset']) : 0;
				$args['offset'] = $new_offset + $offset;
				$args['posts_per_page'] = $penci_vc_number;
			}
		} else {
			if ('cat' == $archivetype) {
				$args['cat'] = $archivevalue;
			} elseif ('tag' == $archivetype) {
				$args['tag_id'] = $archivevalue;
			} elseif ('day' == $archivetype) {
				$date_arr = explode('|', $archivevalue);
				$args['date_query'] = array(
					array(
						'year' => isset($date_arr[2]) ? $date_arr[2] : '',
						'month' => isset($date_arr[0]) ? $date_arr[0] : '',
						'day' => isset($date_arr[1]) ? $date_arr[1] : '',
					),
				);
			} elseif ('month' == $archivetype) {
				$date_arr = explode('|', $archivevalue);
				$args['date_query'] = array(
					array(
						'year' => isset($date_arr[2]) ? $date_arr[2] : '',
						'month' => isset($date_arr[0]) ? $date_arr[0] : '',
					),
				);
			} elseif ('year' == $archivetype) {
				$date_arr = explode('|', $archivevalue);
				$args['date_query'] = array(
					array(
						'year' => isset($date_arr[2]) ? $date_arr[2] : '',
					),
				);
			} elseif ('search' == $archivetype) {
				$args['s'] = $archivevalue;

				if (!get_theme_mod('penci_include_search_page')) {
					$post_types = get_post_types(
						array(
							'public' => true,
							'show_in_nav_menus' => true,
						),
						'names'
					);
					$array_include = array();
					foreach ($post_types as $key => $val) {
						if ('page' != $key) {
							$array_include[] = $key;
						}
					}
					$args['post_type'] = $array_include;

				} elseif (isset($args['post_type'])) {
					unset($args['post_type']);
				}
			} elseif ('author' == $archivetype) {
				$args['author'] = $archivevalue;

				if (isset($args['post_type'])) {
					unset($args['post_type']);
				}
			} elseif ($archivetype && $archivevalue) {
				$args['tax_query'] = array(
					array(
						'taxonomy' => $archivetype,
						'field' => 'term_id',
						'terms' => array($archivevalue),
					),
				);
			}

			$new_offset = (isset($penci_vc_query['offset']) && $penci_vc_query['offset']) ? intval($penci_vc_query['offset']) : 0;
			$args['offset'] = $new_offset + $offset;
		}

		$args['post_status'] = 'publish';

		if ($cat || $tag || $author) {
			$args = array(
				'post_type' => 'post',
				'posts_per_page' => $ppp,
				'post_status' => 'publish',
				'orderby' => $orderby,
				'order' => $order,
			);
		}
		if ($qtype == 'ajaxtab' || (isset($_GET['query_type']) && $_GET['query_type'] == 'ajaxtab')) {
			if ($offset) {
				$args['offset'] = $offset;
			}
			if ($pagednum > 1) {
				$current_offset = isset($args['offset']) && $args['offset'] ? $args['offset'] : 0;
				$args['offset'] = ($pagednum - 1) * $ppp + $current_offset;
			}
		}
		if ($cat) {
			$args['cat'] = $cat;
		}
		if ($tag) {
			$args['tag_id'] = $tag;
		}
		if ($author) {
			$args['author'] = $author;
		}

		$args['nopaging'] = false;
		$args['no_found_rows'] = false;

		$loop = new WP_Query(array_filter($args));

		$qoffset = isset($args['offset']) && $args['offset'] ? $args['offset'] : 0;
		$qppp = isset($args['posts_per_page']) && $args['posts_per_page'] ? $args['posts_per_page'] : get_option('posts_per_page');
		$class_check = $qoffset + $qppp >= $loop->found_posts ? 'pc-nomorepost' : 'pc-hasmorepost';

		if ($loop->have_posts()):
			/* In-feed ads data */
			$infeed_ads = $infeed_num = $infeed_full = '';
			if ('vc-elementor' == $come_from) {
				$data_infeeds = $penci_infeedads;
				if (!empty($data_infeeds)) {
					$infeed_ads = (isset($data_infeeds['ads_code']) && $data_infeeds['ads_code']) ? rawurldecode(base64_decode($data_infeeds['ads_code'])) : '';
					$infeed_num = (isset($data_infeeds['ads_num']) && $data_infeeds['ads_num']) ? $data_infeeds['ads_num'] : 3;
					$infeed_full = (isset($data_infeeds['ads_full']) && $data_infeeds['ads_full']) ? $data_infeeds['ads_full'] : '';
				}
			} else {
				$infeed_ads = get_theme_mod('penci_infeedads_home_code') ? get_theme_mod('penci_infeedads_home_code') : '';
				$infeed_num = get_theme_mod('penci_infeedads_home_num') ? get_theme_mod('penci_infeedads_home_num') : 3;
				$infeed_full = get_theme_mod('penci_infeedads_home_layout') ? get_theme_mod('penci_infeedads_home_layout') : '';
			}
			if ( in_array( $layout, [ 'grid-boxed', 'grid-boxed-2', 'grid-boxed-3' ] ) ) {
				$layout = 'grid';
			} elseif ( in_array( $layout, [ 'list-boxed', 'list-boxed-2' ] ) ) {
				$layout = 'list';
			}
			//echo '<span class="' . $class_check . '"></span>';
			while ($loop->have_posts()):
				$loop->the_post();

				if ('vc-elementor' == $come_from) {
					include locate_template('template-parts/latest-posts-sc/content-' . sanitize_file_name( $layout ) . '.php');
				} else {
					include locate_template('content-' . sanitize_file_name( $layout ) . '.php');
				}

			endwhile;
		endif;

		wp_reset_postdata();

		exit;
	}
}

if (!function_exists('penci_more_slist_post_ajax_func')) {
	add_action('wp_ajax_nopriv_penci_more_slist_post_ajax', 'penci_more_slist_post_ajax_func');
	add_action('wp_ajax_penci_more_slist_post_ajax', 'penci_more_slist_post_ajax_func');
	function penci_more_slist_post_ajax_func() {
		check_ajax_referer('penci_ajax_filter_slist', 'nonce');
		$settings = isset($_POST['datafilter']) ? $_POST['datafilter'] : array();
		$cat = isset($_POST['cat']) ? $_POST['cat'] : array();
		$tag = isset($_POST['tag']) ? $_POST['tag'] : array();
		$author = isset($_POST['author']) ? $_POST['author'] : array();
		$elid = isset($_POST['id']) ? $_POST['id'] : '';
		$paged = isset($_POST['pagednum']) ? $_POST['pagednum'] : '';
		$archivetype = isset($_POST['archivetype']) ? $_POST['archivetype'] : '';
		$archivevalue = isset($_POST['archivevalue']) ? $_POST['archivevalue'] : '';
		$more = isset($_POST['checkmore']) ? $_POST['checkmore'] : false;
		if ($settings && is_array($settings)) {
			extract($settings);
		}

		$posts_per_page = isset($settings['query']['posts_per_page']) && $settings['query']['posts_per_page'] ? $settings['query']['posts_per_page'] : get_theme_mod('posts_per_page');
		$new_offset = (isset($settings['query']['offset']) && $settings['query']['offset']) ? intval($settings['query']['offset']) : 0;
		$smquery_args = array(
			'post_type' => isset($settings['query']['post_type']) ? $settings['query']['post_type'] : 'post',
			'posts_per_page' => $posts_per_page,
			'post_status' => 'publish',
			'offset' => $new_offset,
			'orderby' => isset($settings['query']['orderby']) ? $settings['query']['orderby'] : 'date',
			'order' => isset($settings['query']['order']) ? $settings['query']['order'] : 'DESC',
		);

		if (isset($settings['query']['tax_query'])) {
			$smquery_args['tax_query'] = $settings['query']['tax_query'];
		}

		if ($paged > 1) {
			$smquery_args['offset'] = $new_offset + ($posts_per_page * ($paged - 1));
		}

		if ($cat) {
			$smquery_args['cat'] = $cat;
		}
		if ($tag) {
			$smquery_args['tag_id'] = $tag;
		}
		if ($author) {
			$smquery_args['author'] = $tag;
		}
		if ($tag || $cat || $author) {
			unset($smquery_args['tax_query']);
			unset($smquery_args['offset']);
			if ($paged > 1) {
				$smquery_args['offset'] = ($paged - 1) * $posts_per_page;
			}
		} elseif ($archivetype && $archivevalue) {
			if ('cat' == $archivetype) {
				$smquery_args['cat'] = $archivevalue;
			} elseif ('tag' == $archivetype) {
				$smquery_args['tag_id'] = $archivevalue;
			} elseif ('day' == $archivetype) {
				$date_arr = explode('|', $archivevalue);
				$smquery_args['date_query'] = array(
					array(
						'year' => isset($date_arr[2]) ? $date_arr[2] : '',
						'month' => isset($date_arr[0]) ? $date_arr[0] : '',
						'day' => isset($date_arr[1]) ? $date_arr[1] : '',
					),
				);
			} elseif ('month' == $archivetype) {
				$date_arr = explode('|', $archivevalue);
				$smquery_args['date_query'] = array(
					array(
						'year' => isset($date_arr[2]) ? $date_arr[2] : '',
						'month' => isset($date_arr[0]) ? $date_arr[0] : '',
					),
				);
			} elseif ('year' == $archivetype) {
				$date_arr = explode('|', $archivevalue);
				$smquery_args['date_query'] = array(
					array(
						'year' => isset($date_arr[2]) ? $date_arr[2] : '',
					),
				);
			} elseif ('search' == $archivetype) {
				$smquery_args['s'] = $archivevalue;

				if (!get_theme_mod('penci_include_search_page')) {
					$post_types = get_post_types(
						array(
							'public' => true,
							'show_in_nav_menus' => true,
						),
						'names'
					);
					$array_include = array();
					foreach ($post_types as $key => $val) {
						if ('page' != $key) {
							$array_include[] = $key;
						}
					}
					$smquery_args['post_type'] = $array_include;

				} elseif (isset($smquery_args['post_type'])) {
					unset($smquery_args['post_type']);
				}
			} elseif ('author' == $archivetype) {
				$smquery_args['author'] = $archivevalue;

				if (isset($smquery_args['post_type'])) {
					unset($smquery_args['post_type']);
				}
			} else {
				$smquery_args['tax_query'] = array(
					array(
						'taxonomy' => $archivetype,
						'field' => 'term_id',
						'terms' => array($archivevalue),
					),
				);
			}
		}

		// begin
		$type = $settings['type'] ? $settings['type'] : '';
		$dformat = $settings['dformat'] ? $settings['dformat'] : '';
		$date_pos = $settings['date_pos'] ? $settings['date_pos'] : 'left';
		$column = $settings['column'] ? $settings['column'] : '3';
		$tab_column = $settings['tab_column'] ? $settings['tab_column'] : '2';
		$mb_column = $settings['mb_column'] ? $settings['mb_column'] : '1';
		$imgpos = $settings['imgpos'] ? $settings['imgpos'] : 'left';
		$thumb_size_imgtop = 'top' == $imgpos ? 'penci-thumb' : 'penci-thumb-small';
		if( get_theme_mod('penci_featured_image_size') == 'vertical' ){
			$thumb_size_imgtop = 'penci-thumb-vertical';
		} else if( get_theme_mod('penci_featured_image_size') == 'square' ){
			$thumb_size_imgtop = 'penci-thumb-square';
		}
		$thumb_size = $settings['thumb_size'] ? $settings['thumb_size'] : $thumb_size_imgtop;
		$mthumb_size = $settings['mthumb_size'] ? $settings['mthumb_size'] : $thumb_size_imgtop;
		$post_meta = $settings['post_meta'] ? $settings['post_meta'] : array();
		$primary_cat = $settings['primary_cat'] ? $settings['primary_cat'] : '';
		$title_length = $settings['title_length'] ? $settings['title_length'] : '';
		$excerpt_pos = $settings['excerpt_pos'] ? $settings['excerpt_pos'] : 'below';
		if ('top' == $imgpos) {
			$excerpt_pos = 'side';
		}
		$rmstyle = $settings['rmstyle'] ? $settings['rmstyle'] : 'filled';
		$excerpt_length = $settings['excerpt_length'] ? $settings['excerpt_length'] : 15;

		$thumbnail = $thumb_size;
		if (penci_is_mobile()) {
			$thumbnail = $mthumb_size;
		}

		if (!is_array($post_meta) && strpos($post_meta, ',') !== false) {
			$post_meta = explode(',', $post_meta);
		}

		$inner_wrapper_class = 'pcsl-inner penci-clearfix';
		$inner_wrapper_class .= ' pcsl-' . $type;
		if ('crs' == $type) {
			$inner_wrapper_class .= ' swiper penci-owl-carousel penci-owl-carousel-slider';
		}
		if ('nlist' == $type) {
			$column = '1';
			$tab_column = '1';
			$mb_column = '1';
			if (in_array('date', $post_meta)) {
				$inner_wrapper_class .= ' pcsl-hdate';
			}
		}
		$inner_wrapper_class .= ' pcsl-imgpos-' . $imgpos;
		$inner_wrapper_class .= ' pcsl-col-' . $column;
		$inner_wrapper_class .= ' pcsl-tabcol-' . $tab_column;
		$inner_wrapper_class .= ' pcsl-mobcol-' . $mb_column;
		if ('yes' == $settings['nocrop']) {
			$inner_wrapper_class .= ' pcsl-nocrop';
		}
		if ('yes' == $settings['hide_cat_mobile']) {
			$inner_wrapper_class .= ' pcsl-cat-mhide';
		}
		if ('yes' == $settings['hide_meta_mobile']) {
			$inner_wrapper_class .= ' pcsl-meta-mhide';
		}
		if ('yes' == $settings['hide_excerpt_mobile']) {
			$inner_wrapper_class .= ' pcsl-excerpt-mhide';
		}
		if ('yes' == $settings['hide_rm_mobile']) {
			$inner_wrapper_class .= ' pcsl-rm-mhide';
		}
		if ('yes' == $settings['imgtop_mobile'] && in_array($imgpos, array('left', 'right'))) {
			$inner_wrapper_class .= ' pcsl-imgtopmobile';
		}
		if ('yes' == $settings['ver_border']) {
			$inner_wrapper_class .= ' pcsl-verbd';
		}

		$data_slider = '';
		$item_class = ' normal-item';
		if ('crs' == $type) {
			$item_class = ' swiper-slide';
			$data_slider .= isset($settings['showdots']) && $settings['showdots'] ? ' data-dots="true"' : ' data-dots="false"';
			$data_slider .= isset($settings['shownav']) && $settings['shownav'] ? ' data-nav="false"' : ' data-nav="true"';
			$data_slider .= isset($settings['shownav']) && $settings['loop'] ? ' data-loop="false"' : ' data-loop="true"';
			$data_slider .= ' data-auto="' . ('yes' == $settings['autoplay'] ? 'true' : 'false') . '"';
			$data_slider .= isset($settings['auto_time']) && $settings['auto_time'] ? ' data-autotime="' . $settings['auto_time'] . '"' : ' data-autotime="4000"';
			$data_slider .= isset($settings['speed']) && $settings['speed'] ? ' data-speed="' . $settings['speed'] . '"' : ' data-speed="600"';

			$data_slider .= ' data-item="' . (isset($settings['column']) && $settings['column'] ? $settings['column'] : '3') . '"';
			$data_slider .= ' data-desktop="' . (isset($settings['column']) && $settings['column'] ? $settings['column'] : '3') . '" ';
			$data_slider .= ' data-tablet="' . (isset($settings['tab_column']) && $settings['tab_column'] ? $settings['tab_column'] : '2') . '"';
			$data_slider .= ' data-tabsmall="' . (isset($settings['tab_column']) && $settings['tab_column'] ? $settings['tab_column'] : '2') . '"';
			$data_slider .= ' data-mobile="' . (isset($settings['mb_column']) && $settings['mb_column'] ? $settings['mb_column'] : '1') . '"';
		}
		$query_smalllist = new WP_Query($smquery_args);
		$qoffset = isset($smquery_args['offset']) && $smquery_args['offset'] ? $smquery_args['offset'] : 0;
		$qppp = isset($smquery_args['posts_per_page']) && $smquery_args['posts_per_page'] ? $smquery_args['posts_per_page'] : get_option('posts_per_page');
		$class_check = $qoffset + $qppp >= $query_smalllist->found_posts ? 'pc-nomorepost' : 'pc-hasmorepost';
		
		if ($query_smalllist->have_posts()) {
			if (!$more):
				$wrapper_id = $elid ? $class_check . ' pwsl-id-' . $elid : 'pwsl-id-default';
				?>
				<div class="penci-smalllist pcsl-wrapper <?php echo $wrapper_id; ?>">
					<div class="<?php echo $inner_wrapper_class; ?>"<?php echo $data_slider; ?>>
						<?php if ( 'crs' == $type ) : ?>
                            <div class="swiper-wrapper">
								<?php endif; ?>
					<?php endif;?>
					<?php
					while ($query_smalllist->have_posts()):
						$query_smalllist->the_post();
						?>
						<?php if ( 'crs' == $type ) : ?>
                            <div class="swiper-slide">
						<?php endif; ?>
						<div class="pcsl-item
						<?php
						if ('yes' == $settings['hide_thumb'] || !has_post_thumbnail()) {
							echo ' pcsl-nothumb';
						}
						?>
						">
						<div class="pcsl-itemin">
							<div class="pcsl-iteminer">
								<?php if (in_array('date', $post_meta) && 'nlist' == $type) {?>
									<div class="pcsl-date pcsl-dpos-<?php echo $date_pos; ?>">
										<span class="sl-date"><?php penci_soledad_time_link(null, $dformat);?></span>
									</div>
								<?php }?>

								<?php if ('yes' != $settings['hide_thumb'] && has_post_thumbnail()) {
									?>
									<div class="pcsl-thumb">
										<?php
										do_action('penci_bookmark_post', get_the_ID(), 'small');
										/* Display Review Piechart  */
										if ('yes' == $settings['show_reviewpie'] && function_exists('penci_display_piechart_review_html')) {
											penci_display_piechart_review_html(get_the_ID(), 'small');
										}
										?>
										<?php if ('yes' == $settings['show_formaticon']): ?>
											<?php if (has_post_format('video')): ?>
												<a href="<?php the_permalink();?>" class="icon-post-format"
													aria-label="Icon"><?php penci_fawesome_icon('fas fa-play');?></a>
												<?php endif;?>
												<?php if (has_post_format('gallery')): ?>
													<a href="<?php the_permalink();?>" class="icon-post-format"
														aria-label="Icon"><?php penci_fawesome_icon('far fa-image');?></a>
													<?php endif;?>
													<?php if (has_post_format('audio')): ?>
														<a href="<?php the_permalink();?>" class="icon-post-format"
															aria-label="Icon"><?php penci_fawesome_icon('fas fa-music');?></a>
														<?php endif;?>
														<?php if (has_post_format('link')): ?>
															<a href="<?php the_permalink();?>" class="icon-post-format"
																aria-label="Icon"><?php penci_fawesome_icon('fas fa-link');?></a>
															<?php endif;?>
															<?php if (has_post_format('quote')): ?>
																<a href="<?php the_permalink();?>" class="icon-post-format"
																	aria-label="Icon"><?php penci_fawesome_icon('fas fa-quote-left');?></a>
																<?php endif;?>
															<?php endif;?>

																<a <?php echo penci_layout_bg( penci_image_srcset( get_the_ID(), $thumbnail ),'yes' != $settings['disable_lazy'] ); ?> href="<?php the_permalink();?>"
																	title="<?php echo wp_strip_all_tags(get_the_title()); ?>"
																	class="<?php echo penci_layout_bg_class('yes' != $settings['disable_lazy']);?> penci-image-holder"
																	<?php
																	if ('yes' == $settings['nocrop']) {
																		echo ' style="padding-bottom: ' . penci_get_featured_image_padding_markup(get_the_ID(), $thumbnail, true) . '%"';
																	}
																	?>>
																	<?php echo penci_layout_img( penci_image_srcset( get_the_ID(), $thumbnail ), get_the_title(),'yes' != $settings['disable_lazy'] ); ?>
																</a>

														</div>
													<?php }?>
													<div class="pcsl-content">
														<?php if (in_array('cat', $post_meta)): ?>
															<div class="cat pcsl-cat">
																<?php penci_category('', $primary_cat);?>
															</div>
														<?php endif;?>

														<?php if (in_array('title', $post_meta)): ?>
															<div class="pcsl-title">
																<a href="<?php the_permalink();?>"
																	<?php
																	if ($title_length):
																		echo ' title="' . wp_strip_all_tags(get_the_title()) . '"';
																	endif;
																	?>
																	>
																	<?php
																	if (!$title_length) {
																		the_title();
																	} else {
																		echo wp_trim_words(wp_strip_all_tags(get_the_title()), $title_length, '...');
																	}
																	?>
																</a>
															</div>
														<?php endif;?>

														<?php
														if ((count(
															array_intersect(
																array(
																	'author',
																	'date',
																	'comment',
																	'views',
																	'reading',
																),
																$post_meta
															)
														) > 0 && 'nlist' != $type) || (count(
															array_intersect(
																array(
																	'author',
																	'comment',
																	'views',
																	'reading',
																),
																$post_meta
															)
														) > 0 && 'nlist' == $type)) {
															?>
															<div class="grid-post-box-meta pcsl-meta">
																<?php if (in_array('author', $post_meta)): ?>
																	<span class="sl-date-author author-italic">
																		<?php echo penci_get_setting('penci_trans_by'); ?> <a
																		class="url fn n"
																		href="<?php echo get_author_posts_url(get_the_author_meta('ID')); ?>"><?php the_author();?></a>
																	</span>
																<?php endif;?>
																<?php if (in_array('date', $post_meta) && 'nlist' != $type): ?>
																	<span class="sl-date"><?php penci_soledad_time_link(null, $dformat);?></span>
																<?php endif;?>
																<?php if (in_array('comment', $post_meta)): ?>
																	<span class="sl-comment">
																		<a href="<?php comments_link();?> "><?php comments_number('0 ' . penci_get_setting('penci_trans_comments'), '1 ' . penci_get_setting('penci_trans_comment'), '% ' . penci_get_setting('penci_trans_comments'));?></a>
																	</span>
																<?php endif;?>
																<?php
																if (in_array('views', $post_meta)) {
																	echo '<span class="sl-views">';
																	echo penci_get_post_views(get_the_ID());
																	echo ' ' . penci_get_setting('penci_trans_countviews');
																	echo '</span>';
																}
																?>
																<?php
																$hide_readtime = in_array('reading', $post_meta) ? false : true;
																if (penci_isshow_reading_time($hide_readtime)):
																	?>
																	<span class="sl-readtime"><?php penci_reading_time();?></span>
																<?php endif;?>
																<?php do_action( 'penci_extra_meta' ); ?>
															</div>
														<?php }?>

														<?php if ('yes' == $settings['show_excerpt'] && 'side' == $excerpt_pos) {?>
															<div class="pcbg-pexcerpt pcsl-pexcerpt">
																<?php penci_the_excerpt($excerpt_length);?>
															</div>
														<?php }?>
														<?php if ('yes' == $settings['show_readmore'] && 'side' == $excerpt_pos) {?>
															<div class="pcsl-readmore">
																<a href="<?php the_permalink();?>"
																	class="pcsl-readmorebtn pcsl-btns-<?php echo $rmstyle; ?>">
																	<?php echo penci_get_setting('penci_trans_read_more'); ?>
																</a>
															</div>
														<?php }?>
													</div>

													<?php if (('yes' == $settings['show_excerpt'] || 'yes' == $settings['show_readmore']) && 'below' == $excerpt_pos) {?>
														<div class="pcsl-flex-full">
															<?php if ('yes' == $settings['show_excerpt']) {?>
																<div class="pcbg-pexcerpt pcsl-pexcerpt">
																	<?php penci_the_excerpt($excerpt_length);?>
																</div>
															<?php }?>
															<?php if ('yes' == $settings['show_readmore']) {?>
																<div class="pcsl-readmore">
																	<a href="<?php the_permalink();?>"
																		class="pcsl-readmorebtn pcsl-btns-<?php echo $rmstyle; ?>">
																		<?php echo penci_get_setting('penci_trans_read_more'); ?>
																	</a>
																</div>
															<?php }?>
														</div>
													<?php }?>
												</div>
											</div>
										</div>
										<?php
										if ( 'crs' == $type ) :
                            			echo '</div>';
										endif;
									endwhile;

									if ( 'crs' == $type ) :
                            			echo '</div>';
									endif;

									if (!$more):
										?>

									</div>
								</div>
								<?php
							endif;

						} /* End check if query exists posts */
						wp_reset_postdata();
						exit;
					}
				}

/**
 * Functions callback featured posts
 *
 * @since 8.0.3
 */
if (!function_exists('penci_more_featured_post_ajax_func')) {
	add_action('wp_ajax_nopriv_penci_more_featured_post_ajax', 'penci_more_featured_post_ajax_func');
	add_action('wp_ajax_penci_more_featured_post_ajax', 'penci_more_featured_post_ajax_func');
	function penci_more_featured_post_ajax_func() {
		check_ajax_referer('penci_ajax_filter_fcat', 'nonce');
		$atts = isset($_POST['datafilter']) ? $_POST['datafilter'] : array();
		$cat = isset($_POST['cat']) ? $_POST['cat'] : array();
		$tag = isset($_POST['tag']) ? $_POST['tag'] : array();
		$author = isset($_POST['author']) ? $_POST['author'] : array();
		$id = isset($_POST['id']) ? $_POST['id'] : '';
		$paged = isset($_POST['pagednum']) ? $_POST['pagednum'] : '';
		$ppp = isset($_POST['ppp']) ? $_POST['ppp'] : get_option('posts_per_page');
		if ($atts && is_array($atts)) {
			extract($atts);
		}

		$query_args = array();

		if (is_array($atts['elementor_query'])) {
			$query_args = $atts['elementor_query'];
		} elseif ($atts['build_query'] && function_exists('penci_build_args_query')) {
			$query_args = penci_build_args_query($atts['build_query']);
		}

		$custom_query_tab = false;
		if ($cat) {
			$query_args['cat'] = $cat;
		}

		if ($tag) {
			$query_args['tag_id'] = $tag;
		}

		if ($author) {
			$query_args['author'] = $author;
		}

		if ($cat || $tag || $author) {
			unset($query_args['offset']);
			unset($query_args['tax_query']);
			$custom_query_tab = true;
		}
		
		if ( !isset($query_args['posts_per_page']) ) {
			$query_args['posts_per_page'] = $ppp;
		}

		$dppp = isset($query_args['posts_per_page']) && $query_args['posts_per_page'] ? $query_args['posts_per_page'] : get_option('posts_per_page');
		$num_check = 0;
		if ($paged) {
			if (isset($query_args['offset']) && $query_args['offset'] && !$custom_query_tab) {
				$query_args['offset'] = $paged * $query_args['offset'];
				$num_check = $query_args['offset'] + $dppp;
			} else {
				$query_args['paged'] = $paged;
				$num_check = $paged * $dppp;
			}
		}

		$fea_query = new WP_Query($query_args);
		$numers_results = $fea_query->post_count;
		$max_paged = $fea_query->max_num_pages;
		$doffset = isset($query_args['offset']) && $query_args['offset'] ? $query_args['offset'] : 0;
		$class_check = $num_check >= $fea_query->found_posts ? 'pc-nomorepost' : 'pc-hasmorepost';
		$slider_autoplay = 'true';
		if ($atts['cat_autoplay']) {
			$slider_autoplay = 'false';
		}
		?>
		<div class="home-featured-cat-content <?php echo esc_attr($class_check . ' pwf-id-' . $id . ' ' . $style); ?>">
			<?php if ($style == 'style-4'): ?>
			<div class="swiper penci-single-mag-slider penci-owl-carousel penci-owl-carousel-slider"
				data-auto="<?php echo $slider_autoplay; ?>" data-dots="true" data-nav="false">
				<div class="swiper-wrapper">
				<?php endif;?>
				<?php
if ($style == 'style-5' || $style == 'style-12'):
			$data_item = 2;
			if ($style == 'style-12'):
				$data_item = 3;
			endif;
			?>
								<div class="penci-magcat-carousel-wrapper">
									<div class="swiper penci-owl-carousel penci-owl-carousel-slider penci-magcat-carousel"
										data-speed="400" data-auto="<?php echo $slider_autoplay; ?>"
										data-item="<?php echo $data_item; ?>" data-desktop="<?php echo $data_item; ?>"
										data-tablet="2" data-tabsmall="1">
										<div class="swiper-wrapper">
										<?php endif;?>
						<?php if ($style == 'style-7' || $style == 'style-8' || $style == 'style-13'): ?>
						<ul class="penci-grid penci-grid-maglayout penci-fea-cat-<?php echo $style; ?>">
							<?php endif;?>
							<?php
$m = 1;
		while ($fea_query->have_posts()):
			$fea_query->the_post();
			include locate_template('template-parts/magazine-sc/magazine-' . sanitize_file_name( $style ) . '.php');
			++$m;
		endwhile;
		wp_reset_postdata();
		?>
							<?php if ($style == 'style-7' || $style == 'style-8' || $style == 'style-13'): ?>
						</ul>
					<?php endif;?>
						<?php if ($style == 'style-5' || $style == 'style-12'): ?>
						</div>
					</div>
				</div>
			<?php endif;?>
				<?php if ($style == 'style-4'): ?>
				</div>
			</div>
		<?php endif;?>
		</div>
		<?php
		exit;
	}
}

/**
 * Functions callback when using ajax load more posts on Big Grid
 *
 * @since 7.9
 */
if (!function_exists('penci_big_grid_more_post_ajax_func')) {
	add_action('wp_ajax_nopriv_penci_bgmore_post_ajax', 'penci_big_grid_more_post_ajax_func');
	add_action('wp_ajax_penci_bgmore_post_ajax', 'penci_big_grid_more_post_ajax_func');
	function penci_big_grid_more_post_ajax_func() {
		check_ajax_referer('penci_ajax_filter_bg', 'nonce');

		$settings = (isset($_POST['settings'])) ? $_POST['settings'] : array();
		$pagednum = (isset($_POST['pagednum'])) ? (int) $_POST['pagednum'] : 1;
		$query_type = (isset($_POST['query_type'])) ? $_POST['query_type'] : 'post';
		$archivetype = isset($_POST['archivetype']) ? $_POST['archivetype'] : '';
		$archivevalue = isset($_POST['archivevalue']) ? $_POST['archivevalue'] : '';
		$arppp = isset($_POST['arppp']) ? (int) $_POST['arppp'] : '';
		$cat = isset($_POST['cat']) ? $_POST['cat'] : '';
		$tag = isset($_POST['tag']) ? $_POST['tag'] : '';
		$author = isset($_POST['author']) ? $_POST['author'] : '';
		$scroll = isset($_POST['scroll']) ? $_POST['scroll'] : '';

		/* Get settings data */
		$flag_style = false;
		$biggid_style = $settings['style'] ? $settings['style'] : 'style-1';
		$query_id = isset( $settings['query_id'] ) ? $settings['query_id'] : 'all';
		$overlay_type = $settings['overlay_type'] ? $settings['overlay_type'] : 'whole';
		$bgcontent_pos = $settings['bgcontent_pos'] ? $settings['bgcontent_pos'] : 'on';
		$content_display = $settings['content_display'] ? $settings['content_display'] : 'block';
		$disable_lazy = $settings['disable_lazy'] == 'false' ? false : $settings['disable_lazy'];
		$image_hover = $settings['image_hover'] ? $settings['image_hover'] : 'zoom-in';
		$text_overlay = $settings['text_overlay'] ? $settings['text_overlay'] : 'none';
		$text_overlay_ani = $settings['text_overlay_ani'] ? $settings['text_overlay_ani'] : 'movetop';
		$thumb_size = $settings['thumb_size'] ? $settings['thumb_size'] : 'penci-masonry-thumb';
		$bthumb_size = $settings['bthumb_size'] ? $settings['bthumb_size'] : 'penci-full-thumb';
		$mthumb_size = $settings['mthumb_size'] ? $settings['mthumb_size'] : 'penci-masonry-thumb';
		$readmore_icon = $settings['readmore_icon'] ? $settings['readmore_icon'] : '';
		$hide_cat_small = $settings['hide_cat_small'] == 'false' ? false : $settings['hide_cat_small'];
		$hide_meta_small = $settings['hide_meta_small'] == 'false' ? false : $settings['hide_meta_small'];
		$hide_excerpt_small = $settings['hide_excerpt_small'] == 'false' ? false : $settings['hide_excerpt_small'];
		$hide_rm_small = $settings['hide_rm_small'] == 'false' ? false : $settings['hide_rm_small'];
		$show_formaticon = $settings['show_formaticon'] == 'false' ? false : $settings['show_formaticon'];
		$show_reviewpie = $settings['show_reviewpie'] == 'false' ? false : $settings['show_reviewpie'];
		$readmore_icon_pos = $settings['readmore_icon_pos'] ? $settings['readmore_icon_pos'] : 'right';

		$post_meta = $settings['bg_postmeta'] ? $settings['bg_postmeta'] : array();
		$primary_cat = $settings['primary_cat'] == 'false' ? false : $settings['primary_cat'];
		$title_length = $settings['title_length'] ? $settings['title_length'] : '';
		$show_readmore = $settings['show_readmore'] == 'false' ? false : $settings['show_readmore'];
		$excerpt_length = $settings['excerpt_length'] ? $settings['excerpt_length'] : 10;
		if (!in_array($biggid_style, array('style-1', 'style-2'))) {
			$flag_style = true;
		}

		if (isset($settings['jscomposer']) && $settings['jscomposer'] == 'yes' && $settings['query']) {
			$settings['query'] = penci_build_args_query($settings['query']);
			$post_meta = explode(',', $post_meta);
		}

		$args = array(
			'post_type' => 'post',
			'post_status' => 'publish',
			'orderby' => isset($settings['query']['orderby']) ? $settings['query']['orderby'] : 'date',
			'order' => isset($settings['query']['order']) ? $settings['query']['order'] : 'DESC',
		);

		$pper_page = isset($settings['query']['posts_per_page']) ? $settings['query']['posts_per_page'] : 10;
		$per_page = $arppp ? $arppp : $pper_page;
		$args['posts_per_page'] = $per_page;

		if (isset($settings['query']['tax_query'])) {
			$args['tax_query'] = $settings['query']['tax_query'];
		}

		if ($query_type != 'current_query') {
			$args = $settings['query'] ? $settings['query'] : array();
			$args['paged'] = $pagednum;
			$flag_offset = false;
			if (isset($args['offset']) && (int) $args['offset'] > 0) {
				$flag_offset = true;
				$data_offset = $args['offset'];
				$data_paged = isset($args['paged']) ? $args['paged'] : 1;
				unset($args['paged']);
				$args['offset'] = $per_page * ($data_paged - 1) + $data_offset;
			}
		} elseif ($archivetype && $archivevalue) {

			$orderby = get_theme_mod('penci_general_post_orderby');
			if (!$orderby):
				$orderby = 'date';
			endif;

			$order = get_theme_mod('penci_general_post_order');
			if (!$order):
				$order = 'DESC';
			endif;

			$args = array(
				'post_type' => 'post',
				'post_status' => 'publish',
				'posts_per_page' => $arppp ? $arppp : 10,
				'offset' => $arppp * ($pagednum - 1),
				'orderby' => $orderby,
				'order' => $order,
			);

			$new_offset = (isset($settings['query']['offset']) && $settings['query']['offset']) ? intval($settings['query']['offset']) : 0;
			$args['offset'] = $new_offset + ($arppp * ($pagednum - 1));

			if ('cat' == $archivetype) {
				$args['cat'] = $archivevalue;
			} elseif ('tag' == $archivetype) {
				$args['tag_id'] = $archivevalue;
			} elseif ('day' == $archivetype) {
				$date_arr = explode('|', $archivevalue);
				$args['date_query'] = array(
					array(
						'year' => isset($date_arr[2]) ? $date_arr[2] : '',
						'month' => isset($date_arr[0]) ? $date_arr[0] : '',
						'day' => isset($date_arr[1]) ? $date_arr[1] : '',
					),
				);
			} elseif ('month' == $archivetype) {
				$date_arr = explode('|', $archivevalue);
				$args['date_query'] = array(
					array(
						'year' => isset($date_arr[2]) ? $date_arr[2] : '',
						'month' => isset($date_arr[0]) ? $date_arr[0] : '',
					),
				);
			} elseif ('year' == $archivetype) {
				$date_arr = explode('|', $archivevalue);
				$args['date_query'] = array(
					array(
						'year' => isset($date_arr[2]) ? $date_arr[2] : '',
					),
				);
			} elseif ('search' == $archivetype) {
				$args['s'] = $archivevalue;

				if (!get_theme_mod('penci_include_search_page')) {
					$post_types = get_post_types(
						array(
							'public' => true,
							'show_in_nav_menus' => true,
						),
						'names'
					);
					$array_include = array();
					foreach ($post_types as $key => $val) {
						if ('page' != $key) {
							$array_include[] = $key;
						}
					}
					$args['post_type'] = $array_include;

				} elseif (isset($args['post_type'])) {
					unset($args['post_type']);
				}
			} elseif ('author' == $archivetype) {
				$args['author'] = $archivevalue;

				if (isset($args['post_type'])) {
					unset($args['post_type']);
				}
			} else {
				$args['tax_query'] = array(
					array(
						'taxonomy' => $archivetype,
						'field' => 'term_id',
						'terms' => array($archivevalue),
					),
				);
			}
		}

		if ($cat || $tag || $author) {
			$orderby = get_theme_mod('penci_general_post_orderby');
			if (!$orderby):
				$orderby = 'date';
			endif;

			$order = get_theme_mod('penci_general_post_order');
			if (!$order):
				$order = 'DESC';
			endif;
			$args = array(
				'post_type' => isset($settings['query']['post_type']) ? $settings['query']['post_type'] : 'post',
				'post_status' => 'publish',
				'posts_per_page' => $per_page,
				'orderby' => isset($settings['query']['orderby']) ? $settings['query']['orderby'] : $orderby,
				'order' => isset($settings['query']['order']) ? $settings['query']['order'] : $order,
			);
			if ($pagednum > 1) {
				$args['offset'] = $per_page * ($pagednum - 1);
			}
		}

		if ($cat) {
			$args['cat'] = $cat;
		}

		if ($tag) {
			$args['tag_id'] = $tag;
		}

		if ($author) {
			$args['author'] = $tag;
		}

		if ($pagednum) {
			$args['paged'] = $pagednum;
		}
		$args = apply_filters( 'penci_elementor_query_' . $query_id, $args );
		$loop = new WP_Query($args);

		$class_check = $args['paged'] >= $loop->max_num_pages ? 'pc-nomorepost' : 'pc-hasmorepost';
		if ($loop->have_posts()):
			$num_posts = $loop->post_count;
			$big_items = penci_big_grid_is_big_items($biggid_style);
			$bg = 1;
			if ($flag_style) {
				echo '<div class="penci-clearfix penci-biggrid-data penci-dblock penci-fixh">';
			}
			while ($loop->have_posts()):
				$loop->the_post();
				$hide_cat_small_flag = $hide_meta_small_flag = $hide_rm_small_flag = $hide_excerpt_small_flag = false;
				$is_big_item = '';
				$surplus = penci_big_grid_count_classes($bg, $biggid_style, true);
				$thumbnail = $thumb_size;
				if (!empty($big_items) && in_array($surplus, $big_items)) {
					$thumbnail = $bthumb_size;
					$is_big_item = ' pcbg-big-item';
				}
				if (penci_is_mobile()) {
					$thumbnail = $mthumb_size;
				}
				if (!$is_big_item) {
					if ('yes' == $hide_cat_small) {
						$hide_cat_small_flag = true;
					}
					if ('yes' == $hide_meta_small) {
						$hide_meta_small_flag = true;
					}
					if ('yes' == $hide_excerpt_small) {
						$hide_excerpt_small_flag = true;
					}
					if ('yes' == $hide_rm_small) {
						$hide_rm_small_flag = true;
					}
				}

				if ('style-1' == $biggid_style || 'style-2' == $biggid_style) {
					$hide_cat_small_flag = $hide_meta_small_flag = $hide_rm_small_flag = $hide_excerpt_small_flag = false;
				}

				if (isset($settings['jscomposer']) && $settings['jscomposer'] == 'yes') {
					include locate_template('inc/js_composer/shortcodes/big_grid/based-post.php');
				} else {
					include locate_template('inc/elementor/modules/penci-big-grid/widgets/based-post.php');
				}

				if ($flag_style && $surplus == 0 && $bg < $num_posts) {
					echo '</div><div class="penci-clearfix penci-biggrid-data penci-dblock penci-fixh">';
				}

				++$bg;
			endwhile;

			if ($flag_style) {
				echo '</div>';
			}

			echo '<span class="' . $class_check . '"></span>';

		endif; /* End check if no posts */

		wp_reset_postdata();

		exit;
	}
}

/**
 * Functions callback when using ajax load more posts on Big Grid
 *
 * @since 7.9
 */
if (!function_exists('penci_big_grid_more_terms_ajax_func')) {
	add_action('wp_ajax_nopriv_penci_bgmore_terms_ajax', 'penci_big_grid_more_terms_ajax_func');
	add_action('wp_ajax_penci_bgmore_terms_ajax', 'penci_big_grid_more_terms_ajax_func');
	function penci_big_grid_more_terms_ajax_func() {
		check_ajax_referer('penci_ajax_terms_filter_bg', 'nonce');

		$settings = (isset($_POST['settings'])) ? $_POST['settings'] : array();
		$pagednum = (isset($_POST['pagednum'])) ? (int) $_POST['pagednum'] : 1;
		$query_type = (isset($_POST['query_type'])) ? $_POST['query_type'] : 'post';

		/* Get settings data */
		$flag_style = false;
		$biggid_style = $settings['style'] ? $settings['style'] : 'style-1';
		$overlay_type = $settings['overlay_type'] ? $settings['overlay_type'] : 'whole';
		$bgcontent_pos = $settings['bgcontent_pos'] ? $settings['bgcontent_pos'] : 'on';
		$content_display = $settings['content_display'] ? $settings['content_display'] : 'block';
		$disable_lazy = $settings['disable_lazy'] == 'false' ? false : $settings['disable_lazy'];
		$image_hover = $settings['image_hover'] ? $settings['image_hover'] : 'zoom-in';
		$text_overlay = $settings['text_overlay'] ? $settings['text_overlay'] : 'none';
		$text_overlay_ani = $settings['text_overlay_ani'] ? $settings['text_overlay_ani'] : 'movetop';
		$thumb_size = $settings['thumb_size'] ? $settings['thumb_size'] : 'penci-masonry-thumb';
		$bthumb_size = $settings['bthumb_size'] ? $settings['bthumb_size'] : 'penci-full-thumb';
		$mthumb_size = $settings['mthumb_size'] ? $settings['mthumb_size'] : 'penci-masonry-thumb';
		$readmore_icon = $settings['readmore_icon'] ? $settings['readmore_icon'] : '';
		$hide_excerpt_small = $settings['hide_excerpt_small'] == 'false' ? false : $settings['hide_excerpt_small'];
		$hide_rm_small = $settings['hide_rm_small'] == 'false' ? false : $settings['hide_rm_small'];
		$show_formaticon = $settings['show_formaticon'] == 'false' ? false : $settings['show_formaticon'];
		$readmore_icon_pos = $settings['readmore_icon_pos'] ? $settings['readmore_icon_pos'] : 'right';

		$post_meta = $settings['bg_postmeta'] ? $settings['bg_postmeta'] : array();
		$title_length = $settings['title_length'] ? $settings['title_length'] : '';
		$show_readmore = $settings['show_readmore'] == 'false' ? false : $settings['show_readmore'];
		$excerpt_length = $settings['excerpt_length'] ? $settings['excerpt_length'] : 10;
		if (!in_array($biggid_style, array('style-1', 'style-2'))) {
			$flag_style = true;
		}

		$args = $settings['query'];

		if ($pagednum) {
			$args['offset'] = ($pagednum - 1) * ($args['number']);
		}

		$loop_temrs = get_terms($args);

		$max_terms = $args;
		unset($max_terms['number']);
		unset($max_terms['offset']);

		$max_terms_count = count(get_terms($max_terms));
		$settings = array();
		$settings['term_name'] = $args['taxonomy'];

		$class_check = $pagednum >= ceil($max_terms_count / $args['number']) ? 'pc-nomorepost' : 'pc-hasmorepost';
		if ($loop_temrs):
			$num_posts = count($loop_temrs);
			$big_items = penci_big_grid_is_big_items($biggid_style);
			$bg = 1;
			if ($flag_style) {
				echo '<div class="penci-clearfix penci-biggrid-data penci-dblock penci-fixh">';
			}
			foreach ($loop_temrs as $setting):
				$hide_cat_small_flag = $hide_meta_small_flag = $hide_rm_small_flag = $hide_excerpt_small_flag = false;
				$is_big_item = '';
				$surplus = penci_big_grid_count_classes($bg, $biggid_style, true);
				$thumbnail = $thumb_size;
				if (!empty($big_items) && in_array($surplus, $big_items)) {
					$thumbnail = $bthumb_size;
					$is_big_item = ' pcbg-big-item';
				}
				if (penci_is_mobile()) {
					$thumbnail = $mthumb_size;
				}
				if (!$is_big_item) {
					if ('yes' == $hide_excerpt_small) {
						$hide_excerpt_small_flag = true;
					}
					if ('yes' == $hide_rm_small) {
						$hide_rm_small_flag = true;
					}
				}

				if ('style-1' == $biggid_style || 'style-2' == $biggid_style) {
					$hide_cat_small_flag = $hide_meta_small_flag = $hide_rm_small_flag = $hide_excerpt_small_flag = false;
				}

				$post_count = $setting->count;
				$image_url = get_default_term_thumb_url($setting->term_id, $thumbnail);

				/* Get Custom Items Data */
				$item_id = ' elementor-repeater-item-' . $setting->term_id;
				$image_ratio = penci_get_ratio_size_based_url($image_url);

				$title = $setting->name;
				$title_link = get_term_link($setting->term_id);
				$title_attr = '';

				$desc = $setting->description;

				include locate_template('inc/elementor/modules/penci-category-listing/widgets/category.php');

				if ($flag_style && $surplus == 0 && $bg < $num_posts) {
					echo '</div><div class="penci-clearfix penci-biggrid-data penci-dblock penci-fixh">';
				}

				++$bg;
			endforeach;

			if ($flag_style) {
				echo '</div>';
			}

			echo '<span class="' . $class_check . '"></span>';

		endif; /* End check if no posts */

		wp_reset_postdata();

		exit;
	}
}

add_action( 'wp_ajax_penci_fix_polylang_translation', 'penci_fix_polylang_translation' );
function penci_fix_polylang_translation() {
	
	check_ajax_referer( 'penci_fix_polylang_translation', '_nonce' );

	$theme_path = get_template_directory();
	$wpml_config_path = $theme_path . '/wpml-config.xml';
	$wpml_config_bk_path = $theme_path . '/wpml-config-bk.xml';

	if (file_exists($wpml_config_path)) {
		rename($wpml_config_path, $wpml_config_bk_path);
	}

	wp_send_json_success();	
}