<?php
$output           = $penci_block_width = $el_class = $css_animation = $css = $bgquery_type = $bg_columns = $bg_columns_mobile = '';
$title_anivisi    = $apply_spe_bg_subtitle = $apply_spe_bg_title = $apply_spe_bg_meta = $hide_subtitle_mobile = $hide_desc_mobile = $hide_readmore_mobile = '';
$bg_postmeta      = $primary_cat = $show_readmore = $hide_cat_small = $hide_meta_small = $hide_rm_small = $show_formaticon = $show_reviewpie = $paging = $paging_align = $is_big_item = $post_meta = $hide_cat_small_flag = $hide_meta_small_flag = $hide_rm_small_flag = '';
$title_external   = $title_nofollow = $title_attr = $image_ratio = $item_id = $button_external = $button_nofollow = $button_attr = $button_text = $button_link = $sub_title = $desc = $biggrid_items = '';
$bg_gap           = $bg_othergap = $bg_height = $paging_matop = $content_horizontal_position = $content_vertical_position = $content_text_align = $content_padding = $content_margin = $overlay_opacity = $spe_bg_subtitle = $spe_bg_hsubtitle = $spe_bg_title = $spe_bg_htitle = $spe_bg_meta = $spe_bg_hmeta = $bgitem_bg = $bgitem_borders = $bgitem_borderwidth = $bgitem_padding = $bgsub_title = $bgsub_title_hover = $bgtitle_color = $bgtitle_color_hover = $bgdesc_color = $bgdesc_link_color = $bgdesc_link_hcolor = $readmore_color = $readmore_hcolor = $bgreadmore_color = $bgreadmore_hcolor = $bgreadmore_bgcolor = $bgreadmore_hbgcolor = $bgreadmore_borderwidth = $bgreadmore_borderradius = $bgreadmore_padding = $pagi_mwidth = $pagi_color = '';
$pagi_hcolor      = $bgtitle_typo_big = $bgpagi_color = $bgpagi_hcolor = $bgpagi_bgcolor = $bgpagi_hbgcolor = $bgpagi_borderwidth = $bgpagi_borderradius = $bgpagi_padding = '';
$bgsub_title_typo = $bgtitle_typo = $title_typo = $bgreadm_typo = $bgpagi_typo = $responsive_spacing = '';
$cspost_enable    = $cspost_cpost_meta = $cspost_cpost_acf_meta = $cspost_cpost_meta_label = $cspost_cpost_meta_divider = '';
$atts             = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );

// get data from settings
$biggid_type  = $bgquery_type ? $bgquery_type : 'post';
$biggid_style = $style ? $style : 'style-1';
if ( isset( $_GET['bgstyle'] ) ) {
	$biggid_style = esc_attr( $_GET['bgstyle'] );
}
$overlay_type      = $overlay_type ? $overlay_type : 'whole';
$bgcontent_pos     = $bgcontent_pos ? $bgcontent_pos : 'on';
$content_display   = $content_display ? $content_display : 'block';
$disable_lazy      = $disable_lazy ? $disable_lazy : '';
$image_hover       = $image_hover ? $image_hover : 'zoom-in';
$text_overlay      = $text_overlay ? $text_overlay : 'none';
$text_overlay_ani  = $text_overlay_ani ? $text_overlay_ani : 'movetop';
$onecol_mobile     = $onecol_mobile ? $onecol_mobile : '';
$sameh_mobile      = $sameh_mobile ? $sameh_mobile : '';
$thumb_size        = $thumb_size ? $thumb_size : 'penci-masonry-thumb';
$bthumb_size       = $bthumb_size ? $bthumb_size : 'penci-full-thumb';
$mthumb_size       = $mthumb_size ? $mthumb_size : 'penci-masonry-thumb';
$title_length      = $title_length ? $title_length : '';
$readmore_icon     = $readmore_icon ? $readmore_icon : '';
$readmore_icon_pos = $readmore_icon_pos ? $readmore_icon_pos : 'right';
$formaticon_pos    = $formaticon_pos ? $formaticon_pos : 'top-right';
$reviewpie_pos     = $reviewpie_pos ? $reviewpie_pos : 'top-left';

$wrapper_class   = $data_class = '';
$flag_style      = false;
$clear_fix_class = 'penci-clearfix ';
if ( in_array( $biggid_style, array( 'style-1' ) ) ) {
	$data_class .= ' penci-dflex';
} else {
	$data_class .= ' penci-dblock';
}

if ( ! in_array( $biggid_style, array( 'style-1', 'style-2' ) ) ) {
	$flag_style    = true;
	$data_class   .= ' penci-fixh';
	$bgcontent_pos = 'on';
}

if ( 'style-1' == $biggid_style || 'style-2' == $biggid_style ) {
	$bg_columns        = $bg_columns ? $bg_columns : '3';
	$bg_columns_tablet = $bg_columns_tablet ? $bg_columns_tablet : '';
	$bg_columns_mobile = $bg_columns_mobile ? $bg_columns_mobile : '1';
	$wrapper_class    .= ' penci-grid-col-' . $bg_columns;
	if ( $bg_columns_tablet ) {
		$wrapper_class .= ' penci-grid-tcol-' . $bg_columns_tablet;
	}
	$wrapper_class .= ' penci-grid-mcol-' . $bg_columns_mobile;
}

$wrapper_class .= ' penci-bgrid-based-' . $biggid_type . ' penci-bgrid-' . $biggid_style . ' pcbg-ficonpo-' . $formaticon_pos . ' pcbg-reiconpo-' . $reviewpie_pos . ' penci-bgrid-content-' . $bgcontent_pos . ' pencibg-imageh-' . $image_hover . ' pencibg-texth-' . $text_overlay . ' pencibg-textani-' . $text_overlay_ani;
if ( $flag_style && 'yes' == $onecol_mobile ) {
	$wrapper_class .= ' penci-bgrid-monecol';
}
if ( $flag_style && 'yes' == $sameh_mobile ) {
	$wrapper_class .= ' penci-bgrid-msameh';
}

if ( 'yes' == $title_anivisi ) {
	$wrapper_class .= ' pcbg-titles-visible';
}

if ( 'yes' == $apply_spe_bg_subtitle ) {
	$wrapper_class .= ' pcbg-mask-subtitle';
}

if ( 'yes' == $apply_spe_bg_title ) {
	$wrapper_class .= ' pcbg-mask-title';
}

if ( 'yes' == $apply_spe_bg_meta ) {
	$wrapper_class .= ' pcbg-mask-meta';
}

if ( in_array( $text_overlay_ani, array( 'movetop', 'movebottom', 'moveleft', 'moveright' ) ) ) {
	$wrapper_class .= ' textop';
} else {
	$wrapper_class .= ' notextop';
}

if ( 'yes' == $hide_subtitle_mobile ) {
	$wrapper_class .= ' hide-msubtitle';
}
if ( 'yes' == $hide_desc_mobile ) {
	$wrapper_class .= ' hide-mdesc';
}
if ( 'yes' == $hide_readmore_mobile ) {
	$wrapper_class .= ' hide-mreadmorebt';
}
$big_items = penci_big_grid_is_big_items( $biggid_style );

$class_to_filter = vc_shortcode_custom_css_class( $css, ' ' ) . $this->getExtraClass( $el_class ) . $this->getCSSAnimation( $css_animation );

$css_class  = 'penci-clearfix penci-biggrid-wrapper';
$css_class .= $wrapper_class;
$css_class .= ' ' . apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $class_to_filter, $this->settings['base'], $atts );
$block_id   = Penci_Vc_Helper::get_unique_id_block( 'biggrid' );
?>
	<div id="<?php echo esc_attr( $block_id ); ?>" class="<?php echo esc_attr( $css_class ); ?>">
		<?php
		$atts['blockid'] = $block_id;
		add_action(
			'penci_block_title_extra_' . $block_id,
			function () use ( $atts ) {
				$link_group_cats   = $atts['biggrid_ajaxfilter_cat'];
				$link_group_tags   = $atts['biggrid_ajaxfilter_tag'];
				$link_group_author = $atts['biggrid_ajaxfilter_author'];
				$link_group_out    = $link_group_out_before = $link_group_out_after = '';

				$data_settings_tabs                       = array();
				$data_settings_tabs['query']              = $atts['build_query'];
				$data_settings_tabs['style']              = $atts['style'];
				$data_settings_tabs['overlay_type']       = $atts['overlay_type'];
				$data_settings_tabs['bgcontent_pos']      = $atts['bgcontent_pos'];
				$data_settings_tabs['content_display']    = $atts['content_display'];
				$data_settings_tabs['disable_lazy']       = $atts['disable_lazy'] == 'yes';
				$data_settings_tabs['image_hover']        = $atts['image_hover'];
				$data_settings_tabs['text_overlay']       = $atts['text_overlay'];
				$data_settings_tabs['text_overlay_ani']   = $atts['text_overlay_ani'];
				$data_settings_tabs['thumb_size']         = $atts['thumb_size'];
				$data_settings_tabs['bthumb_size']        = $atts['bthumb_size'];
				$data_settings_tabs['mthumb_size']        = $atts['mthumb_size'];
				$data_settings_tabs['bg_postmeta']        = $atts['bg_postmeta'];
				$data_settings_tabs['primary_cat']        = $atts['primary_cat'] == 'yes';
				$data_settings_tabs['show_readmore']      = $atts['show_readmore'] == 'yes';
				$data_settings_tabs['hide_cat_small']     = $atts['hide_cat_small'] == 'yes';
				$data_settings_tabs['hide_meta_small']    = $atts['hide_meta_small'] == 'yes';
				$data_settings_tabs['hide_rm_small']      = $atts['hide_rm_small'] == 'yes';
				$data_settings_tabs['title_length']       = $atts['title_length'];
				$data_settings_tabs['readmore_icon']      = $atts['readmore_icon'] == 'yes';
				$data_settings_tabs['show_formaticon']    = $atts['show_formaticon'] == 'yes';
				$data_settings_tabs['show_reviewpie']     = $atts['show_reviewpie'] == 'yes';
				$data_settings_tabs['readmore_icon_pos']  = $atts['readmore_icon_pos'];
				$data_settings_tabs['hide_excerpt_small'] = $atts['hide_excerpt_small'];
				$data_settings_tabs['excerpt_length']     = $atts['excerpt_length'];
				$data_settings_tabs['jscomposer']         = 'yes';

				$query = penci_build_args_query( $atts['build_query'] );

				$ppp = isset( $query['posts_per_page'] ) && $query['posts_per_page'] ? $query['posts_per_page'] : get_option( 'posts_per_page' );

				$link_group_out_before .= '<nav data-number="' . $ppp . '" data-query_type="ajaxtab" data-layout="' . esc_attr( $atts['style'] ) . '" data-settings="' . htmlentities( json_encode( $data_settings_tabs ), ENT_QUOTES, 'UTF-8' ) . '" data-more="' . esc_attr( $atts['group_more_link_text'] ) . '" class="pcnav-lgroup"><ul class="pcflx">';
				$link_group_out_after   = '</ul></nav>';

				$has_link = false;

				if ( $link_group_cats ) {
					$has_link        = true;
					$link_group_cats = explode( ',', $link_group_cats );
					foreach ( $link_group_cats as $link_cat ) {
						$link_group_out .= '<li><a class="pc-ajaxfil-link" href="#" aria-label="Category" data-paged="1" data-id="' . md5( 'cat-link-' . $link_cat ) . '" data-cat="' . $link_cat . '">' . get_term_field( 'name', $link_cat ) . '</a></li>';
					}
				}

				if ( $link_group_tags ) {
					$has_link        = true;
					$link_group_tags = explode( ',', $link_group_tags );
					foreach ( $link_group_tags as $link_tag ) {
						$link_group_out .= '<li><a class="pc-ajaxfil-link" href="#" aria-label="Paged" data-paged="1" data-id="' . md5( 'tag-link-' . $link_tag ) . '" data-tag="' . $link_tag . '">' . get_term_field( 'name', $link_tag ) . '</a></li>';
					}
				}

				if ( $link_group_author ) {
					$has_link          = true;
					$link_group_author = explode( ',', $link_group_author );
					foreach ( $link_group_author as $author ) {
						$link_group_out .= '<li><a class="pc-ajaxfil-link" href="#" aria-label="Paged" data-paged="1" data-id="' . md5( 'author-link-' . $author ) . '" data-cat="' . $author . '">' . get_the_author_meta( 'nicename', $author ) . '</a></li>';
					}
				}

				if ( 'nextprev' == $atts['paging'] ) {
					$link_group_out .= '</ul><ul class="pcflx-nav">';
					$link_group_out .= '<li class="pcaj-nav-item pcaj-prev"><a class="disable pc-ajaxfil-link pcaj-nav-link prev" data-id="" href="#" aria-label="Previous"><i class="penciicon-left-chevron"></i></a></li>';
					$link_group_out .= '<li class="pcaj-nav-item pcaj-next"><a class="pc-ajaxfil-link pcaj-nav-link next" data-id="" href="#" aria-label="Next"><i class="penciicon-right-chevron"></i></a></li>';
				}

				if ( $link_group_out ) {
					$first_class            = $has_link ? 'visible' : 'hidden-item';
					$link_group_out_before .= '<li class="all ' . $first_class . '"><a data-paged="1" class="pc-ajaxfil-link current-item" data-id="default" href="#" aria-label="Paged">' . $atts['group_more_defaultab_text'] . '</a></li>';
					wp_enqueue_script( 'penci_ajax_filter_bg' );
					echo $link_group_out_before . $link_group_out . $link_group_out_after;
				}
			}
		);
		Penci_Vc_Helper::markup_block_title( $atts );
		?>
		<div class="penci-clearfix penci-biggrid penci-bg<?php echo $biggid_style; ?> penci-bgel">
			<div class="penci-biggrid-inner">
				<?php
				$bg = 1;
				if ( 'post' == $biggid_type ) {
					$post_meta       = $bg_postmeta ? explode( ',', $bg_postmeta ) : array(
						'cat',
						'title',
						'author',
						'date',
					);
					$primary_cat     = $primary_cat ? $primary_cat == 'yes' : '';
					$show_readmore   = $show_readmore ? $show_readmore == 'yes' : '';
					$hide_cat_small  = $hide_cat_small ? $hide_cat_small == 'yes' : '';
					$hide_meta_small = $hide_meta_small ? $hide_meta_small == 'yes' : '';
					$hide_rm_small   = $hide_rm_small ? $hide_rm_small == 'yes' : '';
					$show_formaticon = $show_formaticon ? $show_formaticon == 'yes' : '';
					$show_reviewpie  = $show_reviewpie ? $show_reviewpie == 'yes' : '';
					$paging          = $paging ? $paging : 'none';
					$paging_align    = $paging_align ? $paging_align : 'align-center';


					$args          = penci_build_args_query( $build_query );
					$args['paged'] = max( get_query_var( 'paged' ), get_query_var( 'page' ), 1 );

					$query_custom = new \WP_Query( $args );
					if ( ! $query_custom->have_posts() ) {
						$output = '';
						if ( current_user_can( 'manage_options' ) ) {
							$output .= '<div class="penci-missing-settings">';
							$output .= '<p style="margin-bottom: 4px;">This message appears for administrator users only</p>';
							$output .= '<span>Big Grid</span>';
							$output .= penci_get_setting( 'penci_ajaxsearch_no_post' );
							$output .= '</div>';
						}

						echo $output;
					}

					if ( $query_custom->have_posts() ) {
						$num_posts = $query_custom->post_count;
						if ( $flag_style ) {
							echo '<div class="penci-big-grid-ajax-data">';
						}

						echo '<div class="penci-clearfix penci-biggrid-data' . $data_class . '">';
						while ( $query_custom->have_posts() ) :
							$query_custom->the_post();
							$hide_cat_small_flag = $hide_meta_small_flag = $hide_rm_small_flag = false;
							$is_big_item         = '';
							$surplus             = penci_big_grid_count_classes( $bg, $biggid_style, true );
							$thumbnail           = $thumb_size;
							if ( ! empty( $big_items ) && in_array( $surplus, $big_items ) ) {
								$thumbnail   = $bthumb_size;
								$is_big_item = ' pcbg-big-item';
							}
							if ( penci_is_mobile() ) {
								$thumbnail = $mthumb_size;
							}
							if ( ! $is_big_item ) {
								if ( 'yes' == $hide_cat_small ) {
									$hide_cat_small_flag = true;
								}
								if ( 'yes' == $hide_meta_small ) {
									$hide_meta_small_flag = true;
								}
								if ( 'yes' == $hide_rm_small ) {
									$hide_rm_small_flag = true;
								}
							}

							include __DIR__ . '/based-post.php';

							if ( $flag_style && $surplus == 0 && $bg < $num_posts ) {
								echo '</div><div class="penci-clearfix penci-biggrid-data' . $data_class . '">';
							}

							++$bg;
						endwhile;
						echo '</div>';

						if ( $flag_style ) {
							echo '</div>';
						}

						if ( 'loadmore' == $paging || 'scroll' == $paging ) {
							$data_settings                      = array();
							$data_settings['query']             = $args;
							$data_settings['style']             = $biggid_style;
							$data_settings['overlay_type']      = $overlay_type;
							$data_settings['bgcontent_pos']     = $bgcontent_pos;
							$data_settings['content_display']   = $content_display;
							$data_settings['disable_lazy']      = $disable_lazy;
							$data_settings['image_hover']       = $image_hover;
							$data_settings['text_overlay']      = $text_overlay;
							$data_settings['text_overlay_ani']  = $text_overlay_ani;
							$data_settings['thumb_size']        = $thumb_size;
							$data_settings['bthumb_size']       = $bthumb_size;
							$data_settings['mthumb_size']       = $mthumb_size;
							$data_settings['bg_postmeta']       = $post_meta;
							$data_settings['primary_cat']       = $primary_cat == 'yes';
							$data_settings['show_readmore']     = $show_readmore == 'yes';
							$data_settings['hide_cat_small']    = $hide_cat_small == 'yes';
							$data_settings['hide_meta_small']   = $hide_meta_small == 'yes';
							$data_settings['hide_rm_small']     = $hide_rm_small == 'yes';
							$data_settings['title_length']      = $title_length;
							$data_settings['readmore_icon']     = $readmore_icon;
							$data_settings['show_formaticon']   = $show_formaticon == 'yes';
							$data_settings['show_reviewpie']    = $show_reviewpie == 'yes';
							$data_settings['readmore_icon_pos'] = $readmore_icon_pos;
							$data_paged                         = max( get_query_var( 'paged' ), get_query_var( 'page' ), 1 );

							$data_settings_ajax = htmlentities( json_encode( $data_settings ), ENT_QUOTES, 'UTF-8' );

							$button_class = ' penci-ajax-more penci-bgajax-more-click';
							if ( 'loadmore' == $paging ) :
								wp_enqueue_script( 'penci_bgajax_more_posts' );
							endif;
							if ( 'scroll' == $paging ) :
								$button_class = ' penci-ajax-more penci-bgajax-more-scroll';
								wp_enqueue_script( 'penci_bgajax_more_scroll' );
							endif;
							?>
							<div class="pcbg-paging penci-pagination <?php echo 'pcbg-paging-' . $paging_align . $button_class; ?>">
								<a class="penci-ajax-more-button" href="#" aria-label="More Posts" data-layout="<?php echo $biggid_style; ?>"
									data-settings="<?php echo $data_settings_ajax; ?>"
									data-pagednum="<?php echo( (int) $data_paged + 1 ); ?>"
									data-mes="<?php echo penci_get_setting( 'penci_trans_no_more_posts' ); ?>">
									<span class="ajax-more-text"><?php echo penci_get_setting( 'penci_trans_load_more_posts' ); ?></span><span
											class="ajaxdot"></span><?php penci_fawesome_icon( 'fas fa-sync' ); ?>
								</a>
							</div>
							<?php
						} elseif ( 'none' != $paging ) {
							echo penci_pagination_numbers( $query_custom, $paging_align );
						}
					}
					wp_reset_postdata();

				} else {
					$biggrid_items = $biggrid_items ? (array) vc_param_group_parse_atts( $biggrid_items ) : array();
					$num_posts     = count( $biggrid_items );
					if ( ! empty( $biggrid_items ) ) {
						echo '<div class="penci-clearfix penci-biggrid-data' . $data_class . '">';
						foreach ( $biggrid_items as $setting ) {
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

							/* Get Custom Items Data */
							$item_id     = ' vc-repeater-item-' . $bg;
							$image_data  = $setting['image'] ? wp_get_attachment_url( $setting['image'] ) : '';
							$image_url   = penci_get_image_size_url( $image_data, $thumbnail );
							$image_ratio = penci_get_ratio_size_based_url( $image_data );
							$sub_title   = $setting['sub_title'] ? $setting['sub_title'] : '';

							$title           = $setting['title'] ? $setting['title'] : '';
							$title_link      = '';
							$title_link_prop = isset( $setting['title_link'] ) ? vc_build_link( $setting['title_link'] ) : '';
							if ( $title_link_prop ) {
								$title_link       = $title_link_prop['url'] ? $title_link_prop['url'] : '';
								$title_external   = $title_link_prop['target'] ? ' target="' . esc_attr( $title_link_prop['target'] ) . '"' : '';
								$title_link_title = $title_link_prop['title'] ? ' title="' . esc_attr( $title_link_prop['title'] ) . '"' : '';
							}


							$desc = $setting['desc'] ? $setting['desc'] : '';

							$button_text      = $setting['button_text'] ? $setting['button_text'] : '';
							$button_link      = '';
							$button_link_prop = isset( $setting['button_link'] ) ? vc_build_link( $setting['button_link'] ) : '';
							if ( $button_link_prop ) {
								$button_link       = $button_link_prop ? $button_link_prop['url'] : '';
								$button_external   = $button_link_prop['target'] ? ' target="' . esc_attr( $button_link_prop['target'] ) . '"' : '';
								$button_link_title = $button_link_prop['title'] ? ' target="' . esc_attr( $button_link_prop['title'] ) . '"' : '';
							}

							include __DIR__ . '/custom.php';

							if ( $flag_style && $surplus == 0 && $bg < $num_posts ) {
								echo '</div><div class="penci-clearfix penci-biggrid-data' . $data_class . '">';
							}

							++$bg;
						}
						echo '</div>';
					}
				}
				?>
			</div>
			<?php
			if ( $atts['biggrid_ajaxfilter_cat'] || $atts['biggrid_ajaxfilter_tag'] || $atts['biggrid_ajaxfilter_author'] || 'nextprev' == $atts['paging'] ) {
				echo penci_get_html_animation_loading( $atts['biggrid_ajax_loading_style'] );
			}
			?>
		</div>
	</div>
<?php
require __DIR__ . '/css.php';
