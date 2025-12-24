<?php
/**
 * Content in mega menu
 *
 * @return HTML inner mega menu
 * @since 1.0
 */

use Elementor\Plugin;

if ( ! function_exists( 'penci_html_mega_menu' ) ) {
	add_action( 'wp_ajax_penci_html_mega_menu', 'penci_html_mega_menu' );
	add_action( 'wp_ajax_nopriv_penci_html_mega_menu', 'penci_html_mega_menu' );
	function penci_html_mega_menu() {

		check_ajax_referer( 'penci-load-mega', 'id' );

		$menu     = $_REQUEST['menu'];
		$item     = $_REQUEST['item'];
		$catid    = $_REQUEST['catid'];
		$number   = $_REQUEST['number'];
		$style    = $_REQUEST['style'];
		$position = $_REQUEST['position'];

		$menu         = wp_get_nav_menu_items( $menu );
		$current_item = isset( $menu[ $item ] ) && is_object( $menu[ $item ] ) ? $menu[ $item ] : '';

		$menu_html = penci_return_html_mega_menu( $catid, $number, $style, $position, $menu, $current_item );
		wp_send_json_success( $menu_html );
	}

}

if ( ! function_exists( 'penci_return_html_mega_menu' ) ) {
	function penci_return_html_mega_menu( $catID, $row, $style, $position, $items, $current ) {
		$args               = [
			'parent'       => (int) $catID,
			'orderby'      => 'name',
			'order'        => 'ASC',
			'hide_empty'   => true,
			'hierarchical' => 1,
			'taxonomy'     => 'category',
		];
		$child_categories   = get_terms( $args );
		$all_text           = penci_get_setting( 'penci_trans_all' );
		$list_links         = $list_cats = [];
		$sub_menu_list_html = '';
		$menu_style         = get_theme_mod( 'penci_header_menu_style' );

		$child_items  = is_object( $current ) && isset( $current->ID ) ? penci_menu_childs( $items, $current->ID ) : [];
		$new_list_cat = [];

		if ( ! empty( $child_items ) ) {

			$term_all               = get_term_by( 'id', absint( $catID ), 'category' );
			$all_text               = $term_all->name;
			$list_cats[ $all_text ] = $catID;

			$count = 2;

			foreach ( $child_items as $id => $child_item ) {

				$parent_menu_id = $child_item->menu_item_parent;

				$child_class = $parent_menu_id != $current->ID ? 'pcmnl-ci' : 'pcmnl-ni';

				if ( $child_item->object == 'category' ) {
					$new_list_cat[ $child_item->title ] = $child_item->object_id;
					$sub_menu_list_html                 .= '<a class="' . $child_class . ' mega-cat-child';

					if ( ( $menu_style != 'menu-style-2' && $count == 1 ) || ( $menu_style == 'menu-style-2' && ( ( get_theme_mod( 'penci_topbar_mega_hide_alltab' ) && $count == 1 ) || ( ! get_theme_mod( 'penci_topbar_mega_hide_alltab' ) && $child_item->object_id == $catID ) ) ) ):
						$sub_menu_list_html .= ' cat-active';
					endif;

					if ( ! get_theme_mod( 'penci_topbar_mega_hide_alltab' ) && $child_item->object_id == $catID ):
						$sub_menu_list_html .= ' all-style';
					endif;
					$sub_menu_list_html .= '" href="' . esc_url( get_category_link( $child_item->object_id ) ) . '" data-id="penci-mega-' . esc_attr( $child_item->object_id ) . '"><span>' . sanitize_text_field( $child_item->title ) . '</span></a>';
				} else {
					$sub_menu_list_html .= '<a href="' . esc_url( $child_item->url ) . '" title="' . $child_item->title . '" class="' . $child_class . ' mega-normal-child"><span>' . $child_item->title . '</span></a>';
				}

				$count ++;
			}

			$child_categories = [];
		} else {
			$list_cats = [ $all_text => $catID ];
		}

		$mega_title_length = get_theme_mod( 'penci_megamenu_title_length' ) ? get_theme_mod( 'penci_megamenu_title_length' ) : 8;

		if ( ( ( get_theme_mod( 'penci_topbar_mega_hide_alltab' ) && $menu_style != 'menu-style-2' ) || $menu_style == 'menu-style-2' ) && ! empty( $child_categories ) ):
			$list_cats = [];
		endif;

		if ( ! empty( $child_categories ) ):

			foreach ( $child_categories as $child_cat ) {
				$list_cats[ $child_cat->name ] = $child_cat->term_id;
			}

		endif;

		if ( ! get_theme_mod( 'penci_topbar_mega_hide_alltab' ) && $menu_style == 'menu-style-2' && ! empty( $child_categories ) ):
			$list_cats[ $all_text ] = $catID;
		endif;

		$has_slide = ! empty( $child_categories ) || $sub_menu_list_html != '';

		$new_style = $style == 4 || $style == 5;

		/* Check rows to show number posts */
		if ( ! isset( $row ) || empty( $row ) ): $row = '1';endif;
		$col           = 'col-mn-5 mega-row-1';
		$numbers       = 5;
		$class_content = '';
		if ( $has_slide || $new_style ) {
			$col     = 'col-mn-4 mega-row-1';
			$numbers = 4;
		}

		if ( '2' == $row ) {
			$col     = 'col-mn-5 mega-row-2';
			$numbers = 10;
			if ( $has_slide || $new_style ) {
				$col     = 'col-mn-4 mega-row-2';
				$numbers = 8;
			}

		} elseif ( '3' == $row ) {
			$col     = 'col-mn-5 mega-row-3';
			$numbers = 15;
			if ( $has_slide || $new_style ) {
				$col     = 'col-mn-4 mega-row-3';
				$numbers = 12;
			}

		}

		$header_layout    = penci_soledad_get_header_layout();
		$header_container = penci_soledad_get_header_container_width();
		if ( ( in_array( $header_layout, [
					'header-7',
					'header-8',
					'header-9',
				] ) || 'fullwidth' == $header_container ) && ! get_theme_mod( 'penci_body_boxed_layout' ) ) {
			$class_content = ' penci-mega-fullct';
			$col           = 'col-mn-7 mega-row-1';
			$numbers       = 7;
			if ( $has_slide || $new_style ) {
				$col     = 'col-mn-6 mega-row-1';
				$numbers = 6;
			}

			if ( '2' == $row ) {
				$col     = 'col-mn-7 mega-row-2';
				$numbers = 14;
				if ( $has_slide || $new_style ) {
					$col     = 'col-mn-6 mega-row-2';
					$numbers = 12;
				}

			} elseif ( '3' == $row ) {
				$col     = 'col-mn-7 mega-row-3';
				$numbers = 21;
				if ( $has_slide || $new_style ) {
					$col     = 'col-mn-6 mega-row-3';
					$numbers = 18;
				}

			}

		}

		if ( $position == 'side' && $style == 4 && $has_slide ) {
			$numbers = $numbers - (int) $row;
		}

		if ( $style == 5 ) {
			$numbers = $numbers + (int) $row;
		}

		$class_content = ' pcmis-' . $style;

		ob_start();
		?>
		<?php if ( $has_slide ): ?>
            <div class="penci-mega-child-categories pcmit-<?php echo esc_attr( $position ); ?>">
				<?php
				$i = 1;
				foreach ( $list_cats as $child_name => $child_id ):
					echo '<a class="mega-cat-child';
					if ( ( $menu_style != 'menu-style-2' && $i == 1 ) || ( $menu_style == 'menu-style-2' && ( ( get_theme_mod( 'penci_topbar_mega_hide_alltab' ) && $i == 1 ) || ( ! get_theme_mod( 'penci_topbar_mega_hide_alltab' ) && $child_id == $catID ) ) ) ):
						echo ' cat-active';
					endif;
					if ( ! get_theme_mod( 'penci_topbar_mega_hide_alltab' ) && $child_id == $catID ):
						echo ' all-style';
					endif;
					echo '" href="' . esc_url( get_category_link( $child_id ) ) . '" data-id="penci-mega-' . esc_attr( $child_id ) . '"><span>' . sanitize_text_field( $child_name ) . '</span></a>';
					$i ++;
				endforeach;
				echo $sub_menu_list_html;
				?>
            </div>
		<?php endif; ?>

        <div class="penci-content-megamenu<?php echo $class_content; ?>">
            <div class="penci-mega-latest-posts                                                <?php echo esc_attr( $col ); ?>">
				<?php $j = 1;
				if ( ! empty( $new_list_cat ) ) {
					$list_cats = array_merge( $list_cats, $new_list_cat );
				}

				foreach ( $list_cats as $cat_name => $cat_id ): ?>
                    <div class="penci-mega-row penci-mega-<?php echo esc_attr( $cat_id ); ?><?php if ( ( $menu_style != 'menu-style-2' && $j == 1 ) || ( $menu_style == 'menu-style-2' && ( ( get_theme_mod( 'penci_topbar_mega_hide_alltab' ) && $j == 1 ) || ( ! get_theme_mod( 'penci_topbar_mega_hide_alltab' ) && $cat_id == $catID ) ) ) ): echo ' row-active';endif; ?>">
						<?php
						$thumbsize = 'penci-thumb-small';
						if ( '1400' == $header_container ) {
							$thumbsize = 'penci-thumb';
						}

						$customize_data = get_theme_mod( 'penci_mega_featured_image_size' );
						if ( 'square' == $customize_data ) {
							$thumbsize = 'penci-thumb-square';
						} elseif ( 'vertical' == $customize_data ) {
							$thumbsize = 'penci-thumb-vertical';
						}

						if ( $style == 5 ) {
							$thumbsize = 'penci-masonry-thumb';
						}

						if ( $style == 2 || $style == 4 ) {
							$thumbsize = 'penci-thumb-square';
						}

						$attr        = [
							'post_type'   => 'post',
							'showposts'   => $numbers,
							'post_status' => 'publish',
							'tax_query'   => [
								[
									'taxonomy' => 'category',
									'field'    => 'term_id',
									'terms'    => (int) $cat_id,
								],
							],
						];
						$latest_mega = new WP_Query( $attr );
						if ( $latest_mega->have_posts() ):
							while ( $latest_mega->have_posts() ): $latest_mega->the_post();

								$category = get_the_category( get_the_ID() );
								?>
                                <div class="penci-mega-post">
                                    <div class="penci-mega-post-inner">

                                        <div class="penci-mega-thumbnail">
											<?php
											do_action( 'penci_bookmark_post', get_the_ID(), 'small' );

											/* Display Review Piechart  */
											if ( function_exists( 'penci_display_piechart_review_html' ) ) {
												penci_display_piechart_review_html( get_the_ID(), 'small' );
											}

											?>
											<?php if ( ! get_theme_mod( 'penci_topbar_mega_category' ) ): ?>
                                                <span class="mega-cat-name">
												<?php if ( $numbers == 5 || $numbers == 10 || $numbers == 15 ) { ?>
                                                    <a href="<?php echo esc_url( get_category_link( $cat_id ) ); ?>">
														<?php echo sanitize_text_field( get_cat_name( $cat_id ) ); ?>
													</a>
												<?php } else { ?>
													<?php if ( $j == 1 && ! get_theme_mod( 'penci_topbar_mega_hide_alltab' ) ) {
														echo '<a href="' . esc_url( get_category_link( $category[0]->term_id ) ) . '">';
														echo sanitize_text_field( $category[0]->cat_name );
														echo '</a>';
													} else {
														echo '<a href="' . esc_url( get_category_link( $cat_id ) ) . '">';
														echo sanitize_text_field( get_cat_name( $cat_id ) );
														echo '</a>';
													}
													?>
												<?php }
												?>
											</span>
											<?php endif; ?>
                                            <a<?php echo penci_layout_bg( penci_image_srcset( get_the_ID(), $thumbsize ), ! get_theme_mod( 'penci_topbar_mega_disable_lazy' ) ); ?>
                                                    class="<?php echo penci_layout_bg_class( ! get_theme_mod( 'penci_topbar_mega_disable_lazy' ) ); ?> penci-image-holder"
                                                    href="<?php the_permalink(); ?>"
                                                    title="<?php echo esc_attr( wp_strip_all_tags( get_the_title() ) ); ?>">
												<?php echo penci_layout_img( penci_image_srcset( get_the_ID(), $thumbsize ), get_the_title(), ! get_theme_mod( 'penci_topbar_mega_disable_lazy' ) ); ?>
												<?php if ( has_post_thumbnail() && get_theme_mod( 'penci_topbar_mega_icons' ) ): ?>
													<?php if ( has_post_format( 'video' ) ): ?>
														<?php penci_fawesome_icon( 'fas fa-play' ); ?>
													<?php endif; ?>
													<?php if ( has_post_format( 'audio' ) ): ?>
														<?php penci_fawesome_icon( 'fas fa-music' ); ?>
													<?php endif; ?>
													<?php if ( has_post_format( 'link' ) ): ?>
														<?php penci_fawesome_icon( 'fas fa-link' ); ?>
													<?php endif; ?>
													<?php if ( has_post_format( 'quote' ) ): ?>
														<?php penci_fawesome_icon( 'fas fa-quote-left' ); ?>
													<?php endif; ?>
													<?php if ( has_post_format( 'gallery' ) ): ?>
														<?php penci_fawesome_icon( 'far fa-image' ); ?>
													<?php endif; ?>
												<?php endif; ?>
                                            </a>
                                        </div>
                                        <div class="penci-mega-meta">
                                            <div class="post-mega-title">
                                                <a href="<?php the_permalink(); ?>"
                                                   title="<?php echo esc_attr( wp_strip_all_tags( get_the_title() ) ); ?>"><?php echo sanitize_text_field( wp_trim_words( wp_strip_all_tags( get_the_title() ), $mega_title_length, '...' ) ); ?></a>
                                            </div>
											<?php if ( ! get_theme_mod( 'penci_topbar_mega_date' ) ): ?>
                                                <p class="penci-mega-date"><?php penci_soledad_time_link(); ?></p>
											<?php endif; ?>
                                        </div>
                                    </div>
                                </div>
							<?php endwhile;
							wp_reset_postdata();
						endif;
						?>
                    </div>
					<?php $j ++;endforeach; ?>
            </div>
        </div>

		<?php
		return ob_get_clean();
	}

}

if ( ! function_exists( 'penci_return_html_block_menu' ) ) {
	function penci_return_html_block_menu( $mega_block_id, $menuid ) {

		$css        = $css_out = $content = '';
		$css_prefix = '#navigation .menu li.penci-mega-menu.penci-block-wrap-mega-' . $menuid . ' .penci-dropdown-menu,
		.navigation .menu li.penci-mega-menu.penci-block-wrap-mega-' . $menuid . ' .penci-dropdown-menu';

		// mega menu style
		$penci_menu_bgimg = get_post_meta( $menuid, 'penci_menu_bgimg', true );
		$penci_menu_mw    = get_post_meta( $menuid, 'penci_menu_mw', true );
		$penci_menu_mh    = get_post_meta( $menuid, 'penci_menu_mh', true );
		$penci_menu_bgcl  = get_post_meta( $menuid, 'penci_menu_bgcl', true );
		$penci_menu_load  = get_post_meta( $menuid, 'penci_menu_load', true );

		if ( $penci_menu_bgimg || $penci_menu_bgcl || $penci_menu_mh || $penci_menu_mw ) {

			if ( $penci_menu_bgimg ) {
				$css .= 'background-image:url(' . esc_url( wp_get_attachment_image_url( $penci_menu_bgimg, 'full' ) ) . ');';
			}

			if ( $penci_menu_bgcl ) {
				$css .= 'background-color:' . $penci_menu_bgcl . ';';
			}

			$css_out .= '<style>';

			if ( $penci_menu_bgimg || $penci_menu_bgcl ) {
				$css_out .= $css_prefix . '{' . $css . '}';
			}

			if ( $penci_menu_mh ) {
				$penci_menu_mh = is_numeric( $penci_menu_mh ) ? $penci_menu_mh . 'px' : $penci_menu_mh;
				$css_out       .= '#navigation .menu li.penci-mega-menu.penci-block-wrap-mega-' . $menuid . ' .penci-dropdown-menu,
							.navigation .menu li.penci-mega-menu.penci-block-wrap-mega-' . $menuid . ' .penci-dropdown-menu{min-height:' . $penci_menu_mh . ';}';
			}

			if ( $penci_menu_mw && $penci_menu_mw != 'full' ) {
				$penci_menu_mw = is_numeric( $penci_menu_mw ) ? $penci_menu_mw . 'px' : $penci_menu_mw;

				if ( strpos( $penci_menu_mw, '%' ) !== false ) {
					$penci_menu_mw_percent = ( (int) str_replace( '%', '', $penci_menu_mw ) ) / 100;
					$penci_menu_mw         = 'calc(var(--pcctain) * ' . $penci_menu_mw_percent . ')';
				}

				$css_out .= '#navigation .menu li.penci-mega-menu.penci-block-wrap-mega-' . $menuid . ' .penci-mega-custom-width,.navigation .menu li.penci-mega-menu.penci-block-wrap-mega-' . $menuid . ' .penci-mega-custom-width{width:' . $penci_menu_mw . ';}';
			}

			$css_out .= '</style>';
		}

		$mega_block_id = get_page_by_path( $mega_block_id, OBJECT, 'penci-block' );
		$mega_block_id = isset( $mega_block_id->ID ) && $mega_block_id->ID ? $mega_block_id->ID : '';

		// mega menu content
		if ( $mega_block_id ) {
			$content = '<div data-blockid="' . $mega_block_id . '" class="penci-mega-content-container penci-mega-content-' . $menuid . '">';
			if ( defined( 'ELEMENTOR_VERSION' ) ) {
				wp_enqueue_style( 'elementor-frontend' );
			}

			if ( defined( 'WPB_VC_VERSION' ) ) {
				wp_enqueue_style( 'js_composer_front' );
			}

			do_action( 'soledad_megamenu_loaded' );
			wp_enqueue_script( 'penci_megamenu' );
			if ( 'yes' !== $penci_menu_load ) {

				$mega_block_content = get_post( $mega_block_id );

				if ( $mega_block_content ) {

					if ( did_action( 'elementor/loaded' ) && Plugin::$instance->documents->get( $mega_block_id )->is_built_with_elementor() ) {
						$content .= penci_get_elementor_content( $mega_block_id );
					} else {
						$content .= do_shortcode( $mega_block_content->post_content );

						$shortcodes_custom_css = get_post_meta( $mega_block_id, '_wpb_shortcodes_custom_css', true );

						$content .= '<style data-type="vc_shortcodes-custom-css">';
						if ( ! empty( $shortcodes_custom_css ) ) {
							$content .= $shortcodes_custom_css;
						}

						$content .= '</style>';
					}

				}

				$content .= '</div>';
			} else {
				if ( did_action( 'elementor/frontend/before_register_scripts' ) ) {
					wp_enqueue_script( 'jquery.plugin' );
					wp_enqueue_script( 'countdown' );
					wp_enqueue_script( 'waypoints' );
					wp_enqueue_script( 'jquery-counterup' );
				}

				if ( defined( 'WPB_VC_VERSION' ) ) {
					wp_enqueue_script( 'wpb_composer_front_js' );
				}

			}

		}

		return $css_out . $content;
	}

}
