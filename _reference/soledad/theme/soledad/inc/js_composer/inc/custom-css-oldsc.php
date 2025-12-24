<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if ( ! class_exists( 'Penci_Custom_CSS_Shortcode_Old' ) ):
	class Penci_Custom_CSS_Shortcode_Old {
		public static function latest_posts( $block_id, $atts ) {

			$block_id   = '#' . $block_id;
			$css_custom = Penci_Vc_Helper::get_heading_block_css( $block_id, $atts );
			$css_custom .= Penci_Vc_Helper::get_heading_block_filter_css( $block_id, $atts );

			if ( isset( $atts['penci_featimg_size'] ) && $atts['penci_featimg_size'] ) {
				if ( 'horizontal' == $atts['penci_featimg_size'] ) {
					$css_custom .= $block_id . '  .penci-image-holder:before{padding-top: 66.6667% !important;}';
				} elseif ( 'square' == $atts['penci_featimg_size'] ) {
					$css_custom .= $block_id . '  .penci-image-holder:before{ padding-top: 100% !important;}';
				} elseif ( 'vertical' == $atts['penci_featimg_size'] ) {
					$css_custom .= $block_id . '  .penci-image-holder:before{ padding-top: 135.4% !important;}';
				} elseif ( 'custom' == $atts['penci_featimg_size'] ) {
					if ( isset( $atts['penci_featimg_ratio'] ) && ! is_array( $atts['penci_featimg_ratio'] ) ) {
						$ratio      = $atts['penci_featimg_ratio'] ? $atts['penci_featimg_ratio'] : '';
						$css_custom .= $block_id . '  .penci-image-holder:before{padding-top: ' . esc_attr( $ratio ) . '% !important; }';
					}
				}
			}

			// Title
			if ( $atts['pborder_color'] ) {
				$css_custom .= $block_id . ' .grid-mixed,';
				$css_custom .= $block_id . ' .penci-grid .list-post.list-boxed-post,';
				$css_custom .= $block_id . ' .penci-grid li.list-boxed-post-2 .content-boxed-2,';
				$css_custom .= $block_id . ' .penci-grid li.list-post{ border-color: ' . esc_attr( $atts['pborder_color'] ) . ' !important;}';
			}

			$ptitle_typo = "{$block_id} .entry-title,{$block_id} .entry-title a,";
			$ptitle_typo .= "{$block_id} .header-standard .entry-title,{$block_id} .header-standard .entry-title a,";
			$ptitle_typo .= "{$block_id} .overlay-header-box .entry-title,{$block_id} .overlay-header-box .entry-title a,";
			$ptitle_typo .= "{$block_id} .header-standard h2, {$block_id} .header-standard h2 a,";
			$ptitle_typo .= "{$block_id} .penci-grid li .item h2 a, {$block_id} .penci-grid li .item h2 a,";
			$ptitle_typo .= "{$block_id} .penci-masonry .item-masonry h2 a,{$block_id} .penci-masonry .item-masonry h2 a";

			if ( $atts['ptitle_color'] ) {
				$css_custom .= $ptitle_typo . '{ color: ' . esc_attr( $atts['ptitle_color'] ) . ' !important;}';
			}
			if ( $atts['ptitle_hcolor'] ) {
				$css_custom .= $block_id . ' .header-standard h2 a:hover,' . $block_id . ' .entry-title a:hover,';
				$css_custom .= $block_id . '.penci-grid li .item h2 a:hover,' . $block_id . ' .penci-masonry .item-masonry h2 a:hover,';
				$css_custom .= $block_id . ' .overlay-header-box .overlay-title a:hover{ color: ' . esc_attr( $atts['ptitle_hcolor'] ) . ' !important;}';
			}

			$css_custom .= Penci_Vc_Helper::vc_google_fonts_parse_attributes( array(
				'font_size'  => $atts['ptitle_fsize'],
				'font_style' => $atts['use_ptitle_typo'] ? $atts['ptitle_typo'] : '',
				'template'   => $ptitle_typo . '{ %s }',
			) );

			// Post meta
			if ( $atts['pmeta_color'] ) {
				$css_custom .= $block_id . ' .header-standard .author-post span,';
				$css_custom .= $block_id . ' .penci-post-box-meta .penci-box-meta span,';
				$css_custom .= $block_id . ' .penci-post-box-meta .penci-box-meta a,';
				$css_custom .= $block_id . ' .overlay-author span,';
				$css_custom .= $block_id . ' .overlay-author a,';
				$css_custom .= $block_id . ' .overlay-post-box-meta .overlay-share span,';
				$css_custom .= $block_id . ' .overlay-post-box-meta .overlay-share a,';
				$css_custom .= $block_id . ' .overlay-post-box-meta,';
				$css_custom .= $block_id . ' .grid-post-box-meta span{ color: ' . esc_attr( $atts['pmeta_color'] ) . ';}';
			}
			if ( $atts['pmeta_hcolor'] ) {
				$css_custom .= $block_id . ' .penci-post-box-meta .penci-box-meta a:hover,';
				$css_custom .= $block_id . ' .overlay-author a:hover,';
				$css_custom .= $block_id . ' .grid-post-box-meta span a:hover{ color: ' . esc_attr( $atts['pmeta_hcolor'] ) . ';}';
			}

			if ( $atts['pauthor_color'] ) {
				$css_custom .= $block_id . ' .penci-meta-author a,';
				$css_custom .= $block_id . ' .penci-meta-author span,';
				$css_custom .= $block_id . ' .grid-post-box-meta span a,';
				$css_custom .= $block_id . ' .header-standard .author-post span a{ color: ' . esc_attr( $atts['pauthor_color'] ) . ' !important;}';
			}
			if ( $atts['pmeta_border_color'] ) {
				$css_custom .= $block_id . ' .header-standard:after,';
				$css_custom .= $block_id . ' .grid-header-box:after,';
				$css_custom .= $block_id . ' .penci-overlay-over .overlay-header-box:after,';
				$css_custom .= $block_id . ' .penci-post-box-meta{ background-color: ' . esc_attr( $atts['pmeta_border_color'] ) . ';}';
			}

			$pmeta_typo = $block_id . ' .header-standard .author-post,';
			$pmeta_typo .= $block_id . ' .penci-post-box-meta .penci-box-meta span,';
			$pmeta_typo .= $block_id . ' .penci-post-box-meta .penci-box-meta a,';
			$pmeta_typo .= $block_id . ' .overlay-author a,';
			$pmeta_typo .= $block_id . ' .overlay-header-box .overlay-author,';
			$pmeta_typo .= $block_id . ' .grid-post-box-meta';

			$css_custom .= Penci_Vc_Helper::vc_google_fonts_parse_attributes( array(
				'font_size'  => $atts['pmeta_fsize'],
				'font_style' => $atts['use_pmeta_typo'] ? $atts['pmeta_typo'] : '',
				'template'   => $pmeta_typo . '{ %s }',
			) );

			// Post excrept
			$markup_excrept = "{$block_id} .post-entry.standard-post-entry, {$block_id} .post-entry.standard-post-entry p,";
			$markup_excrept .= "{$block_id} .penci-grid .entry-content,{$block_id} .penci-grid .entry-content p,";
			$markup_excrept .= "{$block_id} .entry-content,{$block_id} .entry-content p";
			if ( $atts['pexcrept_color'] ) {
				$css_custom .= $markup_excrept . '{ color: ' . esc_attr( $atts['pexcrept_color'] ) . '}';
			}
			if ( $atts['use_pexcrept_typo'] ) {
				$css_custom .= Penci_Vc_Helper::vc_google_fonts_parse_attributes( array(
					'font_size'  => $atts['pexcrept_fsize'],
					'font_style' => $atts['pexcrept_typo'],
					'template'   => $markup_excrept . '{ %s }',
				) );
			}
			// Cat
			if ( $atts['pcat_color'] ) {
				$css_custom .= $block_id . ' .cat > a.penci-cat-name{ color: ' . esc_attr( $atts['pcat_color'] ) . '}';
				$css_custom .= $block_id . ' .typography-style .main-typography a.penci-cat-name:after,';
				$css_custom .= $block_id . ' .penci-grid .cat a.penci-cat-name:after,';
				$css_custom .= $block_id . ' .penci-masonry .cat a.penci-cat-name:after,';
				$css_custom .= $block_id . ' .overlay-header-box .cat > a.penci-cat-name:after{ border-color: ' . esc_attr( $atts['pcat_color'] ) . '}';
			}
			if ( $atts['pcat_hcolor'] ) {
				$css_custom .= $block_id . ' .cat > a.penci-cat-name:hover{ color: ' . esc_attr( $atts['pcat_hcolor'] ) . '}';
			}
			$css_custom .= Penci_Vc_Helper::vc_google_fonts_parse_attributes( array(
				'font_size'  => $atts['pcat_fsize'],
				'font_style' => $atts['use_pcat_typo'] ? $atts['pcat_typo'] : '',
				'template'   => $block_id . ' .cat > a.penci-cat-name{ %s }',
			) );

			// Read more btn
			if ( $atts['prmore_color'] ) {
				$css_custom .= $block_id . ' .penci-more-link a.more-link{ color: ' . esc_attr( $atts['prmore_color'] ) . '}';
				$css_custom .= $block_id . ' .penci-more-link a.more-link:before,';
				$css_custom .= $block_id . ' .penci-more-link a.more-link:after{ border-top-color: ' . esc_attr( $atts['prmore_color'] ) . '}';
			}
			if ( $atts['pcat_hcolor'] ) {
				$css_custom .= $block_id . ' .penci-more-link a.more-link:hover{ color: ' . esc_attr( $atts['pcat_hcolor'] ) . '}';
			}
			if ( $atts['pag_icon_fsize'] ) {
				$css_custom .= $block_id . ' .penci-more-link a.more-link:hover{ font-size: ' . esc_attr( $atts['pag_icon_fsize'] ) . '}';
			}
			$css_custom .= Penci_Vc_Helper::vc_google_fonts_parse_attributes( array(
				'font_size'  => $atts['prmore_fsize'],
				'font_style' => $atts['use_prmore_typo'] ? $atts['prmore_typo'] : '',
				'template'   => $block_id . ' .penci-more-link a.more-link{ %s }',
			) );

			// Share
			if ( $atts['pshare_color'] ) {
				$css_custom .= $block_id . ' .penci-post-box-meta .penci-post-share-box a{ color: ' . esc_attr( $atts['pshare_color'] ) . '}';
			}
			if ( $atts['pshare_hcolor'] ) {
				$css_custom .= $block_id . ' .penci-post-box-meta .penci-post-share-box a:hover{ color: ' . esc_attr( $atts['pshare_hcolor'] ) . '}';
			}
			if ( $atts['pshare_border_color'] ) {
				$css_custom .= $block_id . ' .penci-post-box-meta.penci-post-box-grid:before{ background-color: ' . esc_attr( $atts['pshare_border_color'] ) . '}';
				$css_custom .= $block_id . ' .penci-post-box-meta{ border-color: ' . esc_attr( $atts['pshare_border_color'] ) . '}';
			}

			// Pagination
			if ( $atts['pagination_icon'] ) {
				$css_custom .= $block_id . ' .penci-pagination a.penci-ajax-more-button i,' . $block_id . ' .penci-pagination .disable-url i{ font-size: ' . esc_attr( $atts['pagination_icon'] ) . '}';
			}
			if ( $atts['pagination_size'] ) {
				$css_custom .= $block_id . ' .penci-pagination ul.page-numbers li a i,';
				$css_custom .= $block_id . ' .penci-pagination ul.page-numbers li span,';
				$css_custom .= $block_id . ' .penci-pagination ul.page-numbers li a,';
				$css_custom .= $block_id . '  .penci-pagination.penci-ajax-more a.penci-ajax-more-button{ font-size: ' . esc_attr( $atts['pagination_size'] ) . '}';
			}

			$markup_pagination_color = "{$block_id} .penci-pagination ul.page-numbers li span,";
			$markup_pagination_color .= "{$block_id} .penci-pagination ul.page-numbers li a,";
			$markup_pagination_color .= "{$block_id} .penci-pagination.penci-ajax-more a.penci-ajax-more-button";
			if ( $atts['pagination_color'] ) {
				$css_custom .= $markup_pagination_color . '{ color: ' . esc_attr( $atts['pagination_color'] ) . ' !important; }';
			}
			if ( $atts['pagination_bordercolor'] ) {
				$css_custom .= $markup_pagination_color . '{ border-color: ' . esc_attr( $atts['pagination_bordercolor'] ) . ' !important; }';
			}
			if ( $atts['pagination_bgcolor'] ) {
				$css_custom .= $markup_pagination_color . '{ background-color: ' . esc_attr( $atts['pagination_bgcolor'] ) . ' !important; }';
			}

			$markup_pagination_hcolor = "{$block_id} .penci-pagination.penci-ajax-more a.penci-ajax-more-button:hover,";
			$markup_pagination_hcolor .= "{$block_id} .penci-pagination ul.page-numbers li a:hover,";
			$markup_pagination_hcolor .= "{$block_id} .penci-pagination ul.page-numbers li span.current";
			if ( $atts['pagination_hcolor'] ) {
				$css_custom .= $markup_pagination_hcolor . '{ color: ' . esc_attr( $atts['pagination_hcolor'] ) . ' !important; }';
			}
			if ( $atts['pagination_hbordercolor'] ) {
				$css_custom .= $markup_pagination_hcolor . '{ border-color: ' . esc_attr( $atts['pagination_hbordercolor'] ) . ' !important; }';
			}
			if ( $atts['pagination_hbgcolor'] ) {
				$css_custom .= $markup_pagination_hcolor . '{ background-color: ' . esc_attr( $atts['pagination_hbgcolor'] ) . ' !important; }';
			}

			// Big
			$style_big_post = array(
				'mixed',
				'mixed-4',
				'mixed-2',
				'standard-grid',
				'standard-grid-2',
				'standard-list',
				'standard-boxed-1',
				'classic-grid',
				'classic-grid-2',
				'classic-list',
				'classic-boxed-1',
				'overlay-grid',
				'overlay-grid-2',
				'overlay-list',
				'overlay-boxed-1'
			);

			if ( 'mixed-2' == $atts['style'] || 'overlay-grid' == $atts['style'] || 'overlay-grid-2' == $atts['style'] || 'overlay-list' == $atts['style'] || 'overlay-boxed' == $atts['style'] ) {
				if ( $atts['bptitle_color'] ) {
					$css_custom .= $block_id . ' .grid-overlay .penci-entry-title a{ color: ' . esc_attr( $atts['bptitle_color'] ) . ' !important; }';
				}
				if ( $atts['bptitle_hcolor'] ) {
					$css_custom .= $block_id . ' .grid-overlay .penci-entry-title a:hover{ color: ' . esc_attr( $atts['bptitle_hcolor'] ) . '!important;}';
				}

				if ( $atts['bpcat_color'] ) {
					$css_custom .= $block_id . ' .grid-overlay .cat > a.penci-cat-name{ color: ' . esc_attr( $atts['bpcat_color'] ) . '!important;}';
					$css_custom .= $block_id . ' .grid-overlay .overlay-header-box .cat > a.penci-cat-name:after{ border-color: ' . esc_attr( $atts['bpcat_color'] ) . '!important;}';
				}
				if ( $atts['bpcat_hcolor'] ) {
					$css_custom .= $block_id . ' .grid-overlay .cat > a.penci-cat-name:hover{ color: ' . esc_attr( $atts['bpcat_hcolor'] ) . '!important;}';
				}

				if ( $atts['bpauthor_color'] ) {
					$css_custom .= $block_id . ' .grid-overlay .overlay-author span,';
					$css_custom .= $block_id . ' .grid-overlay .overlay-author a{ color: ' . esc_attr( $atts['bpauthor_color'] ) . '!important;}';
				}
				if ( $atts['bpmeta_border_color'] ) {
					$css_custom .= $block_id . ' .grid-overlay .penci-overlay-over .overlay-header-box:after{ background-color: ' . esc_attr( $atts['bpmeta_border_color'] ) . '!important;}';
				}
			}

			if ( in_array( $atts['style'], $style_big_post ) ) {

				if ( $atts['bptitle_fsize'] ) {
					$css_custom .= '@media screen and (min-width: 768px){';
					$css_custom .= $block_id . '.penci-latest-posts-mixed-2 .item.overlay-layout .entry-title a,';
					$css_custom .= $block_id . '.penci-latest-posts-mixed .grid-mixed .entry-title a,';
					$css_custom .= $block_id . '.penci-latest-posts-standard-grid article.format-standard .entry-title a,';
					$css_custom .= $block_id . '.penci-latest-posts-standard-grid-2 article.format-standard .entry-title a,';
					$css_custom .= $block_id . '.penci-latest-posts-standard-list article.format-standard .entry-title a,';
					$css_custom .= $block_id . '.penci-latest-posts-standard-boxed-1 article.format-standard .entry-title a,';
					$css_custom .= $block_id . '.penci-latest-posts-classic-grid article.format-standard .entry-title a,';
					$css_custom .= $block_id . '.penci-latest-posts-classic-grid-2 article.format-standard .entry-title a,';
					$css_custom .= $block_id . '.penci-latest-posts-overlay-grid .overlay-header-box .entry-title a,';
					$css_custom .= $block_id . '.penci-latest-posts-overlay-grid-2 .overlay-header-box .entry-title a,';
					$css_custom .= $block_id . '.penci-latest-posts-overlay-list .overlay-header-box .entry-title a,';
					$css_custom .= $block_id . '.penci-latest-posts-overlay-boxed-1 .overlay-header-box .entry-title a,';
					$css_custom .= $block_id . '.penci-latest-posts-mixed-4 .penci-featured-infor .entry-title a,';
					$css_custom .= $block_id . '.penci-latest-posts-classic-list article.format-standard .entry-title a{ font-size: ' . esc_attr( $atts['bptitle_fsize'] ) . '!important;}';
					$css_custom .= '}';
				}
				if ( $atts['bpcat_fsize'] ) {

					$css_custom .= '@media screen and (min-width: 768px){';
					$css_custom .= $block_id . '.penci-latest-posts-mixed .grid-mixed .cat > a.penci-cat-name,';
					$css_custom .= $block_id . '.penci-latest-posts-mixed-2 .item.overlay-layout .cat > a.penci-cat-name,';
					$css_custom .= $block_id . '.penci-latest-posts-standard-grid article.format-standard .cat > a.penci-cat-name,';
					$css_custom .= $block_id . '.penci-latest-posts-standard-grid-2 article.format-standard .cat > a.penci-cat-name,';
					$css_custom .= $block_id . '.penci-latest-posts-standard-list article.format-standard .cat > a.penci-cat-name,';
					$css_custom .= $block_id . '.penci-latest-posts-standard-boxed-1 article.format-standard .cat > a.penci-cat-name,';
					$css_custom .= $block_id . '.penci-latest-posts-classic-grid article.format-standard .cat > a.penci-cat-name,';
					$css_custom .= $block_id . '.penci-latest-posts-classic-grid-2 article.format-standard .cat > a.penci-cat-name,';
					$css_custom .= $block_id . '.penci-latest-posts-classic-list article.format-standard .cat > a.penci-cat-name,';
					$css_custom .= $block_id . '.penci-latest-posts-overlay-grid .grid-overlay .cat > a.penci-cat-name,';
					$css_custom .= $block_id . '.penci-latest-posts-overlay-grid-2 .grid-overlay .cat > a.penci-cat-name,';
					$css_custom .= $block_id . '.penci-latest-posts-overlay-list .grid-overlay .cat > a.penci-cat-name,';
					$css_custom .= $block_id . '.penci-latest-posts-overlay-boxed-1 .grid-overlay .cat > a.penci-cat-name,';
					$css_custom .= $block_id . '.penci-latest-posts-mixed-4 .penci-featured-infor .cat > a.penci-cat-name{ font-size: ' . esc_attr( $atts['bpcat_fsize'] ) . '!important;}';
					$css_custom .= '}';
				}

				if ( $atts['bpmeta_fsize'] ) {
					$pmeta_typo_bpost = $block_id . '%1$s .header-standard .author-post,';
					$pmeta_typo_bpost .= $block_id . '%1$s .penci-post-box-meta .penci-box-meta span,';
					$pmeta_typo_bpost .= $block_id . '%1$s .penci-post-box-meta .penci-box-meta a,';
					$pmeta_typo_bpost .= $block_id . '%1$s .overlay-author a,';
					$pmeta_typo_bpost .= $block_id . '%1$s .overlay-header-box .overlay-author,';
					$pmeta_typo_bpost .= $block_id . '%1$s .grid-post-box-meta,';

					$css_custom .= '@media screen and (min-width: 768px){';
					$css_custom .= sprintf( $pmeta_typo_bpost, '.penci-latest-posts-mixed .grid-mixed' );
					$css_custom .= sprintf( $pmeta_typo_bpost, '.penci-latest-posts-mixed-2 .item.overlay-layout' );
					$css_custom .= sprintf( $pmeta_typo_bpost, '.penci-latest-posts-standard-grid article.format-standard' );
					$css_custom .= sprintf( $pmeta_typo_bpost, '.penci-latest-posts-standard-grid-2 article.format-standard' );
					$css_custom .= sprintf( $pmeta_typo_bpost, '.penci-latest-posts-standard-list article.format-standard' );
					$css_custom .= sprintf( $pmeta_typo_bpost, '.penci-latest-posts-standard-boxed-1 article.format-standard' );
					$css_custom .= sprintf( $pmeta_typo_bpost, '.penci-latest-posts-classic-grid article.format-standard' );
					$css_custom .= sprintf( $pmeta_typo_bpost, '.penci-latest-posts-classic-grid-2 article.format-standard' );
					$css_custom .= sprintf( $pmeta_typo_bpost, '.penci-latest-posts-classic-list article.format-standard' );
					$css_custom .= sprintf( $pmeta_typo_bpost, '.penci-latest-posts-overlay-grid .overlay-layout' );
					$css_custom .= sprintf( $pmeta_typo_bpost, '.penci-latest-posts-overlay-grid-2 .overlay-layout' );
					$css_custom .= sprintf( $pmeta_typo_bpost, '.penci-latest-posts-overlay-list .overlay-layout' );
					$css_custom .= sprintf( $pmeta_typo_bpost, '.penci-latest-posts-overlay-boxed-1 .overlay-layout' );
					$css_custom .= $block_id . '.penci-latest-posts-mixed-4 .penci-featured-infor .grid-post-box-meta, .penci-custom-x{ font-size: ' . esc_attr( $atts['bpmeta_fsize'] ) . '!important;}';
					$css_custom .= '}';
				}


				if ( $atts['bsocialshare_size'] ) {
					$css_custom .= '@media screen and (min-width: 768px){';
					$css_custom .= $block_id . '.penci-latest-posts-mixed-2 .item.overlay-layout .penci-post-box-meta .penci-post-share-box a,';
					$css_custom .= $block_id . '.penci-latest-posts-mixed .grid-mixed .penci-post-box-meta .penci-post-share-box a,';
					$css_custom .= $block_id . '.penci-latest-posts-standard-grid article.format-standard .penci-post-box-meta .penci-post-share-box a,';
					$css_custom .= $block_id . '.penci-latest-posts-standard-grid-2 article.format-standard .penci-post-box-meta .penci-post-share-box a,';
					$css_custom .= $block_id . '.penci-latest-posts-standard-list article.format-standard .penci-post-box-meta .penci-post-share-box a,';
					$css_custom .= $block_id . '.penci-latest-posts-standard-boxed-1 article.format-standard .penci-post-box-meta .penci-post-share-box a,';
					$css_custom .= $block_id . '.penci-latest-posts-classic-grid article.format-standard .penci-post-box-meta .penci-post-share-box a,';
					$css_custom .= $block_id . '.penci-latest-posts-classic-grid-2 article.format-standard .penci-post-box-meta .penci-post-share-box a,';
					$css_custom .= $block_id . '.penci-latest-posts-classic-list article.format-standard .penci-post-box-meta .penci-post-share-box a,';
					$css_custom .= $block_id . '.penci-latest-posts-overlay-grid .grid-overlay .penci-post-share-box a,';
					$css_custom .= $block_id . '.penci-latest-posts-overlay-grid-2 .grid-overlay .penci-post-share-box a,';
					$css_custom .= $block_id . '.penci-latest-posts-overlay-list .grid-overlay .penci-post-share-box a,';
					$css_custom .= $block_id . '.penci-latest-posts-overlay-boxed-1 .grid-overlay .penci-post-share-box a,';
					$css_custom .= $block_id . '.penci-latest-posts-mixed-4 .grid-featured .penci-post-share-box a';
					$css_custom .= '{ font-size: ' . esc_attr( $atts['bpcat_fsize'] ) . '!important;}';
					$css_custom .= '}';
				}
			}

			if ( in_array( $atts['style'], array(
				'mixed',
				'mixed-4',
				'standard-grid',
				'standard-grid-2',
				'standard-list',
				'standard-boxed-1',
				'classic-grid',
				'classic-grid-2',
				'classic-list'
			) ) ) {
				if ( $atts['bpexcerpt_size'] ) {
					$css_custom .= '@media screen and (min-width: 768px){';
					$css_custom .= $block_id . '.penci-latest-posts-mixed .grid-mixed .entry-content,';
					$css_custom .= $block_id . '.penci-latest-posts-mixed .grid-mixed .entry-content p,';
					$css_custom .= $block_id . '.penci-latest-posts-standard-grid article.format-standard .entry-content,';
					$css_custom .= $block_id . '.penci-latest-posts-standard-grid article.format-standard .entry-content p,';
					$css_custom .= $block_id . '.penci-latest-posts-standard-grid-2 article.format-standard .entry-content,';
					$css_custom .= $block_id . '.penci-latest-posts-standard-grid-2 article.format-standard .entry-content p,';
					$css_custom .= $block_id . '.penci-latest-posts-standard-list article.format-standard .entry-content,';
					$css_custom .= $block_id . '.penci-latest-posts-standard-list article.format-standard .entry-content p,';
					$css_custom .= $block_id . '.penci-latest-posts-standard-boxed-1 article.format-standard .entry-content,';
					$css_custom .= $block_id . '.penci-latest-posts-standard-boxed-1 article.format-standard .entry-content p,';
					$css_custom .= $block_id . '.penci-latest-posts-classic-grid article.format-standard .entry-content,';
					$css_custom .= $block_id . '.penci-latest-posts-classic-grid article.format-standard .entry-content p,';
					$css_custom .= $block_id . '.penci-latest-posts-classic-grid-2 article.format-standard .entry-content,';
					$css_custom .= $block_id . '.penci-latest-posts-classic-grid-2 article.format-standard .entry-content p,';
					$css_custom .= $block_id . '.penci-latest-posts-mixed-4 .penci-featured-infor entry-content,';
					$css_custom .= $block_id . '.penci-latest-posts-mixed-4 .penci-featured-infor entry-content p,';
					$css_custom .= $block_id . '.penci-latest-posts-classic-list article.format-standard .entry-content,';
					$css_custom .= $block_id . '.penci-latest-posts-classic-list article.format-standard .entry-content p';
					$css_custom .= '{ font-size: ' . esc_attr( $atts['bpcat_fsize'] ) . '!important;}';
					$css_custom .= '}';
				}
			}

			if ( isset( $atts['grid_uppercase_cat'] ) && $atts['grid_uppercase_cat'] ) {
				$css_custom .= $block_id . ' .penci-grid .cat a.penci-cat-name,';
				$css_custom .= $block_id . ' .penci-masonry .cat a.penci-cat-name,';
				$css_custom .= $block_id . ' .grid-mixed .cat a.penci-cat-name,';
				$css_custom .= $block_id . '  .overlay-header-box .cat a.penci-cat-name { text-transform: uppercase; }';
			}

			if ( isset( $atts['penci_paging_martop'] ) && $atts['penci_paging_martop'] ) {
				$css_custom .= $block_id . ' .penci-latest-posts-el .penci-pagination{ margin-top: ' . esc_attr( $atts['penci_paging_martop'] ) . '!important;}';
			}

			if ( 'grid' == $atts['style'] || 'masonry' == $atts['style'] ) {
				if ( isset( $atts['order_column_gap'] ) && $atts['order_column_gap'] && ! is_array( $atts['order_column_gap'] ) ) {
					$css_custom .= $block_id . '.penci-latest-posts-sc:not( .penci-lposts-ctcol ) .penci-grid > li{ margin-right: ' . esc_attr( $atts['order_column_gap'] ) . '!important;}';
					$css_custom .= $block_id . '.penci-lposts-ctcol .penci-grid{ grid-column-gap: ' . esc_attr( $atts['order_column_gap'] ) . '!important;}';
					$css_custom .= $block_id . ' .penci-wrap-masonry{margin-right: calc(-' . esc_attr( $atts['order_column_gap'] ) . '/2); margin-left: calc(-' . esc_attr( $atts['order_column_gap'] ) . '/2);}';
					$css_custom .= $block_id . ' .penci-masonry .item-masonry{ padding-right: calc(' . esc_attr( $atts['order_column_gap'] ) . '/2); padding-left: calc(' . esc_attr( $atts['order_column_gap'] ) . '/2);}';
				}
			}

			if ( in_array( $atts['style'], array( 'grid', 'grid-boxed', 'grid-boxed-2', 'grid-boxed-3', 'grid-boxed-4', 'masonry', 'list', 'boxed-1' ) ) ) {
				if ( isset( $atts['order_row_gap'] ) && $atts['order_row_gap'] && ! is_array( $atts['order_row_gap'] ) ) {
					$css_custom .= $block_id . '.penci-grid{ row-gap: 0}';
					$css_custom .= $block_id . '.penci-latest-posts-sc:not( .penci-lposts-ctcol ) .penci-grid > li:not( :last-child ){ margin-bottom: ' . esc_attr( $atts['order_row_gap'] ) . '!important;}';
					$css_custom .= $block_id . '.penci-lposts-ctcol .penci-grid{ grid-row-gap: ' . esc_attr( $atts['order_row_gap'] ) . '!important; }';
					$css_custom .= $block_id . ' .penci-masonry .item-masonry{margin-bottom:' . esc_attr( $atts['order_row_gap'] ) . '!important; }';
					$css_custom .= $block_id . '.penci-latest-posts-list .penci-grid li.list-post:not( :last-child ){ padding-bottom: calc(' . esc_attr( $atts['order_row_gap'] ) . '/2) !important; margin-bottom: calc(' . esc_attr( $atts['order_row_gap'] ) . '/2) !important; }';
					$css_custom .= $block_id . '.penci-latest-posts-boxed-1 .penci-grid .list-post.list-boxed-post:not( :last-child ){  margin-bottom: ' . esc_attr( $atts['order_row_gap'] ) . ' !important; };';
				}
			}

			if ( $css_custom ) {
				return '<style>' . $css_custom . '</style>';
			}
		}

		public static function featured_cat( $block_id, $atts ) {
			$block_id   = '#' . $block_id;
			$css_custom = Penci_Vc_Helper::get_heading_block_css( $block_id, $atts );
			$css_custom .= Penci_Vc_Helper::get_heading_block_filter_css( $block_id, $atts );

			if ( $atts['pborder_color'] ) {
				$css_custom .= $block_id . ' .penci-grid li.list-post,';
				$css_custom .= $block_id . ' .home-featured-cat-content .mag-post-box{ border-color: ' . esc_attr( $atts['pborder_color'] ) . ';}';
			}
			if ( $atts['ptitle_color'] ) {
				$css_custom .= $block_id . ' .penci-grid li .item h2 a,';
				$css_custom .= $block_id . ' .penci-masonry .item-masonry h2 a,';
				$css_custom .= $block_id . ' .home-featured-cat-content .magcat-detail h3 a{ color: ' . esc_attr( $atts['ptitle_color'] ) . ';}';
			}
			if ( $atts['ptitle_hcolor'] ) {
				$css_custom .= $block_id . ' .penci-grid li .item h2 a:hover,';
				$css_custom .= $block_id . ' .penci-masonry .item-masonry h2 a:hover,';
				$css_custom .= $block_id . ' .home-featured-cat-content .magcat-detail h3 a:hover{ color: ' . esc_attr( $atts['ptitle_hcolor'] ) . ';}';
			}

			if ( $atts['bptitle_color'] ) {
				$css_custom .= $block_id . ' .home-featured-cat-content .first-post .magcat-detail h3 a{ color: ' . esc_attr( $atts['bptitle_color'] ) . ';}';
			}
			if ( $atts['bptitle_hcolor'] ) {
				$css_custom .= $block_id . ' .home-featured-cat-content .first-post .magcat-detail h3 a:hover{ color: ' . esc_attr( $atts['bptitle_hcolor'] ) . ';}';
			}

			$template = $block_id . ' .penci-grid li .item h2 a,';
			$template .= $block_id . ' .penci-masonry .item-masonry h2 a,';
			$template .= $block_id . ' .home-featured-cat-content .magcat-detail h3 a{ %s }';

			$css_custom .= Penci_Vc_Helper::vc_google_fonts_parse_attributes( array(
				'font_size'  => $atts['ptitle_fsize'],
				'font_style' => $atts['use_ptitle_typo'] ? $atts['ptitle_typo'] : '',
				'template'   => $template,
			) );

			if ( $atts['bptitle_fsize'] ) {
				$css_custom .= '@media screen and (min-width: 768px){';
				$css_custom .= $block_id . ' .home-featured-cat-content .first-post .magcat-detail h3 a{ font-size: ' . esc_attr( $atts['bptitle_fsize'] ) . ';}';
				$css_custom .= '}';
			}
			if ( $atts['simgwidth'] ) {
				$css_custom .= $block_id . ' .home-featured-cat-content .penci-image-holder.small-fix-size{ width: ' . esc_attr( $atts['simgwidth'] ) . ';}';
			}
			if ( $atts['pmeta_color'] ) {
				$css_custom .= $block_id . ' .home-featured-cat-content .grid-post-box-meta span a,';
				$css_custom .= $block_id . ' .home-featured-cat-content .grid-post-box-meta span{ color: ' . esc_attr( $atts['pmeta_color'] ) . ';}';

				$css_custom .= $block_id . ' .home-featured-cat-content .mag-photo .grid-post-box-meta span:after{ color: ' . esc_attr( $atts['pmeta_color'] ) . ';}';
			}
			if ( $atts['pmeta_hcolor'] ) {
				$css_custom .= $block_id . ' .grid-post-box-meta span a.comment-link:hover,';
				$css_custom .= $block_id . ' .grid-post-box-meta span a:hover{ color: ' . esc_attr( $atts['pmeta_hcolor'] ) . ';}';
			}

			if ( $atts['bpmeta_color'] ) {
				$css_custom .= $block_id . ' .home-featured-cat-content .first-post .grid-post-box-meta span a,';
				$css_custom .= $block_id . ' .home-featured-cat-content .first-post .grid-post-box-meta span{ color: ' . esc_attr( $atts['bpmeta_color'] ) . ';}';
			}
			if ( $atts['bpmeta_hcolor'] ) {
				$css_custom .= $block_id . ' .home-featured-cat-content .first-post .grid-post-box-meta span a:hover{ color: ' . esc_attr( $atts['bpmeta_hcolor'] ) . ';}';
			}

			$css_custom .= Penci_Vc_Helper::vc_google_fonts_parse_attributes( array(
				'font_size'  => $atts['pmeta_fsize'],
				'font_style' => $atts['use_pmeta_typo'] ? $atts['pmeta_typo'] : '',
				'template'   => $block_id . ' .home-featured-cat-content .grid-post-box-meta{ %s }',
			) );

			if ( $atts['pexcerpt_color'] ) {
				$css_custom .= $block_id . ' .entry-content{ color: ' . esc_attr( $atts['pexcerpt_color'] ) . ';}';
			}

			$css_custom .= Penci_Vc_Helper::vc_google_fonts_parse_attributes( array(
				'font_size'  => $atts['pexcerpt_fsize'],
				'font_style' => $atts['use_pexcerpt_typo'] ? $atts['pexcerpt_typo'] : '',
				'template'   => $block_id . ' .entry-content,' . $block_id . ' .entry-content p{ %s }',
			) );

			if ( $atts['pcat_color'] ) {
				$css_custom .= $block_id . ' .cat > a.penci-cat-name,';
				$css_custom .= $block_id . ' .cat > a.penci-cat-name:after{ color: ' . esc_attr( $atts['pmeta_color'] ) . ';}';
			}
			if ( $atts['pcat_hcolor'] ) {
				$css_custom .= $block_id . ' .cat > a.penci-cat-name:hover{ color: ' . esc_attr( $atts['pcat_hcolor'] ) . ';}';
			}

			$css_custom .= Penci_Vc_Helper::vc_google_fonts_parse_attributes( array(
				'font_size'  => $atts['pcat_fsize'],
				'font_style' => $atts['pcat_typo'],
				'template'   => $block_id . ' .cat > a.penci-cat-name{ %s }',
			) );

			$att_style = isset( $atts['style'] ) ? $atts['style'] : '';


			if ( 'style-3' == $att_style || 'style-11' == $att_style ) {
				if ( isset( $atts['penci_column_gap'] ) && $atts['penci_column_gap'] ) {
					$css_custom .= $block_id . '.penci-featured-cat-sc:not( .penci-featured-cat-ctcol ) .home-featured-cat-content{ width: calc(100% + ' . esc_attr( $atts['penci_column_gap'] ) . ' );margin-left: calc(-' . esc_attr( $atts['penci_column_gap'] ) . '/2); margin-right: calc(-' . esc_attr( $atts['penci_column_gap'] ) . '/2); }';
					$css_custom .= $block_id . '.penci-featured-cat-sc:not( .penci-featured-cat-ctcol ) .home-featured-cat-content .mag-photo{ padding-left: calc(' . esc_attr( $atts['penci_column_gap'] ) . '/2); padding-right: calc(' . esc_attr( $atts['penci_column_gap'] ) . '/2); }';
					$css_custom .= $block_id . '.penci-featured-cat-ctcol .home-featured-cat-content{ grid-column-gap: ' . esc_attr( $atts['penci_column_gap'] ) . '; }';
				}
			}

			if ( in_array( $att_style, array( 'style-3', 'style-11', 'style-8' ) ) ) {
				if ( isset( $atts['penci_row_gap'] ) && $atts['penci_row_gap'] ) {

					$css_custom .= $block_id . '.penci-featured-cat-sc:not( .penci-featured-cat-ctcol ) .home-featured-cat-content .mag-photo{ margin-bottom: ' . esc_attr( $atts['penci_row_gap'] ) . ' !important; }';
					$css_custom .= $block_id . '.penci-featured-cat-ctcol .home-featured-cat-content{ grid-row-gap: ' . esc_attr( $atts['penci_row_gap'] ) . '; }';
					$css_custom .= $block_id . ' .mag-cat-style-8 .penci-grid li.list-post:not( :last-child ){ padding-bottom: calc( ' . esc_attr( $atts['penci_row_gap'] ) . '/2); margin-bottom: calc(' . esc_attr( $atts['penci_row_gap'] ) . '/2) }';
				}
			}

			if ( isset( $atts['penci_featimg_size'] ) && $atts['penci_featimg_size'] ) {
				if ( 'horizontal' == $atts['penci_featimg_size'] ) {
					$css_custom .= $block_id . '  .penci-image-holder:before{padding-top: 66.6667% !important;}';
				} elseif ( 'square' == $atts['penci_featimg_size'] ) {
					$css_custom .= $block_id . '  .penci-image-holder:before{ padding-top: 100% !important;}';
				} elseif ( 'vertical' == $atts['penci_featimg_size'] ) {
					$css_custom .= $block_id . '  .penci-image-holder:before{ padding-top: 135.4% !important;}';
				} elseif ( 'custom' == $atts['penci_featimg_size'] && isset( $atts['penci_featimg_ratio'] ) && $atts['penci_featimg_ratio'] && ! is_array( $atts['penci_featimg_ratio'] ) ) {
					$css_custom .= $block_id . '  .penci-image-holder:before{padding-top: ' . esc_attr( $atts['penci_featimg_ratio'] ) . '% !important; }';
				}
			}

			if ( $css_custom ) {
				return '<style>' . $css_custom . '</style>';
			}
		}

		public static function popular_posts( $block_id, $atts ) {
			$block_id   = '#' . $block_id;
			$css_custom = Penci_Vc_Helper::get_heading_block_css( $block_id, $atts );

			if ( isset( $atts['penci_featimg_size'] ) && $atts['penci_featimg_size'] ) {
				if ( 'horizontal' == $atts['penci_featimg_size'] ) {
					$css_custom .= $block_id . '  .penci-image-holder:before{padding-top: 66.6667% !important;}';
				} elseif ( 'square' == $atts['penci_featimg_size'] ) {
					$css_custom .= $block_id . '  .penci-image-holder:before{ padding-top: 100% !important;}';
				} elseif ( 'vertical' == $atts['penci_featimg_size'] ) {
					$css_custom .= $block_id . '  .penci-image-holder:before{ padding-top: 135.4% !important;}';
				} elseif ( 'custom' == $atts['penci_featimg_size'] && isset( $atts['penci_featimg_ratio'] ) && $atts['penci_featimg_ratio'] && ! is_array( $atts['penci_featimg_ratio'] ) ) {
					$css_custom .= $block_id . '  .penci-image-holder:before{padding-top: ' . esc_attr( $atts['penci_featimg_ratio'] ) . '% !important; }';
				}
			}

			// Header style default
			if ( isset( $atts['block_title_color'] ) && $atts['block_title_color'] ) {
				$css_custom .= $block_id . ' .use-heading-default .home-pupular-posts-title a,';
				$css_custom .= $block_id . ' .use-heading-default .home-pupular-posts-title span,';
				$css_custom .= $block_id . ' .use-heading-default .home-pupular-posts-title { color: ' . esc_attr( $atts['block_title_color'] ) . '; }';
			}

			if ( isset( $atts['block_title_hcolor'] ) && $atts['block_title_hcolor'] ) {
				$css_custom .= $block_id . '  .use-heading-default .home-pupular-posts-title a:hover{ color: ' . esc_attr( $atts['block_title_hcolor'] ) . '; }';
			}
			if ( isset( $atts['btitle_bcolor'] ) && $atts['btitle_bcolor'] ) {
				$css_custom .= $block_id . ' .penci-home-popular-posts.use-heading-default{ border-top-color: ' . esc_attr( $atts['btitle_bcolor'] ) . '; }';
			}

			if ( $atts['ptitle_color'] ) {
				$css_custom .= $block_id . ' .item-related h3 a{ color: ' . esc_attr( $atts['ptitle_color'] ) . ';}';
			}
			if ( $atts['ptitle_hcolor'] ) {
				$css_custom .= $block_id . ' .item-related h3 a:hover{ color: ' . esc_attr( $atts['ptitle_hcolor'] ) . ';}';
			}

			$css_custom .= Penci_Vc_Helper::vc_google_fonts_parse_attributes( array(
				'font_size'  => $atts['ptitle_fsize'],
				'font_style' => $atts['use_ptitle_typo'] ? $atts['ptitle_typo'] : '',
				'template'   => $block_id . ' .item-related h3 a{ %s }',
			) );

			if ( $atts['pmeta_color'] ) {
				$css_custom .= $block_id . ' .item-related span.date{ color: ' . esc_attr( $atts['pmeta_color'] ) . ';}';
			}
			$css_custom .= Penci_Vc_Helper::vc_google_fonts_parse_attributes( array(
				'font_size'  => $atts['pmeta_fsize'],
				'font_style' => $atts['use_pmeta_typo'] ? $atts['pmeta_typo'] : '',
				'template'   => $block_id . ' .item-related span.date{ %s }',
			) );

			if ( $atts['_dot_color'] ) {
				$css_custom .= $block_id . ' .penci-owl-dots .penci-owl-dot span{ border-color:' . esc_attr( $atts['_dot_color'] ) . ';background-color:' . esc_attr( $atts['_dot_color'] ) . ' }';
			}
			if ( $atts['dot_hcolor'] ) {
				$css_custom .= $block_id . ' .penci-owl-dots .penci-owl-dot:hover span,';
				$css_custom .= $block_id . ' .penci-owl-dots .penci-owl-dot.active span{ border-color:' . esc_attr( $atts['dot_hcolor'] ) . ';background-color:' . esc_attr( $atts['dot_hcolor'] ) . '}';
			}


			if ( $css_custom ) {
				return '<style>' . $css_custom . '</style>';
			}
		}

		public static function featured_boxes( $block_id, $atts ) {
			$block_id   = '#' . $block_id;
			$css_custom = '';

			$style = isset( $atts['style'] ) ? $atts['style'] : '';

			if ( $atts['img_box_border_color'] ) {

				if ( 'boxes-style-4' == $style ) {
					$css_custom .= $block_id . ' ul.homepage-featured-boxes .penci-fea-in h4 span span{ background-color: ' . esc_attr( $atts['img_box_border_color'] ) . ';}';
					$css_custom .= $block_id . ' ul.homepage-featured-boxes li .penci-fea-in:before,';
					$css_custom .= $block_id . ' ul.homepage-featured-boxes li .penci-fea-in:after{ border-color: ' . esc_attr( $atts['img_box_border_color'] ) . ';}';
				} else if ( 'boxes-style-3' == $style ) {
					$css_custom .= $block_id . ' ul.homepage-featured-boxes .penci-fea-in h4 span span{ background-color: ' . esc_attr( $atts['img_box_border_color'] ) . ';}';
					$css_custom .= $block_id . ' ul.homepage-featured-boxes li .penci-fea-in:before,';
					$css_custom .= $block_id . ' ul.homepage-featured-boxes li .penci-fea-in:after{ border-color: ' . esc_attr( $atts['img_box_border_color'] ) . ';}';
				} elseif ( 'boxes-style-2' == $style ) {
					$css_custom .= $block_id . ' ul.homepage-featured-boxes .penci-fea-in.boxes-style-2 h4{ background-color: ' . esc_attr( $atts['img_box_border_color'] ) . ';}';
					$css_custom .= $block_id . ' ul.homepage-featured-boxes .penci-fea-in.boxes-style-2 h4:before{ border-color: ' . esc_attr( $atts['img_box_border_color'] ) . ';}';
				} else {
					$css_custom .= $block_id . ' ul.homepage-featured-boxes .penci-fea-in h4 span span:before,';
					$css_custom .= $block_id . ' ul.homepage-featured-boxes li .penci-fea-in:after,';
					$css_custom .= $block_id . ' ul.homepage-featured-boxes li .penci-fea-in:before{ border-color: ' . esc_attr( $atts['img_box_border_color'] ) . ';}';
					$css_custom .= $block_id . ' ul.homepage-featured-boxes .penci-fea-in h4 span span{ background-color: ' . esc_attr( $atts['img_box_border_color'] ) . ';}';
				}
			}

			if ( $atts['img_box_text_color'] ) {
				$css_custom .= $block_id . ' ul.homepage-featured-boxes li .penci-fea-in h4 span span,';
				$css_custom .= $block_id . ' ul.homepage-featured-boxes .penci-fea-in h4 > span{ color: ' . esc_attr( $atts['img_box_text_color'] ) . ';}';
			}

			if ( $atts['img_box_text_hcolor'] ) {
				$css_custom .= $block_id . ' ul.homepage-featured-boxes li .penci-fea-in:hover h4 span span,';
				$css_custom .= $block_id . ' ul.homepage-featured-boxes li .penci-fea-in:hover h4 > span{ color: ' . esc_attr( $atts['img_box_text_hcolor'] ) . ';}';
			}

			$css_custom .= Penci_Vc_Helper::vc_google_fonts_parse_attributes( array(
				'font_size'  => $atts['img_box_fsize'],
				'font_style' => $atts['use_img_box_typo'] ? $atts['img_box_typo'] : '',
				'template'   => $block_id . ' ul.homepage-featured-boxes .penci-fea-in h4 > span,' . $block_id . ' ul.homepage-featured-boxes .penci-fea-in h4 span span{ %s }',
			) );

			if ( $css_custom ) {
				return '<style>' . $css_custom . '</style>';
			}
		}
	}
endif;
