<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * @param $width
 *
 * @return bool|string
 * @since 4.2
 */
if ( ! function_exists( 'penci_wpb_translateColumnWidthToSpan' ) ) :
	function penci_wpb_translateColumnWidthToSpan( $width, $order ) {
		$output = array();
		preg_match( '/(\d+)\/(\d+)/', $width, $matches );

		$container_layout = Penci_Global_Data_Blocks::get_data_row();
		if ( in_array( $container_layout, array( '23_13', '13_23', '14_12_14', '12_14_14', '14_14_12' ) ) ) {
			if ( '1/4' == $width ) {
				$output[] = 'penci-vc-sidebar';
				if ( '12_14_14' == $container_layout ) {
					if ( 2 == $order ) {
						$output [] = 'penci-sidebar-left';
					} else {
						$output [] = 'penci-sidebar-right';
					}
				} else {
					if ( 1 == $order ) {
						$output [] = 'penci-sidebar-left';
					} else {
						$output [] = 'penci-sidebar-right';
					}
				}
			} elseif ( '1/3' == $width ) {
				$output[] = 'penci-vc-sidebar';

				if ( '23_13' == $container_layout ) {
					$output [] = 'penci-sidebar-right';
				} else {
					$output [] = 'penci-sidebar-left';
				}
			} elseif ( '2/3' == $width || '1/2' == $width ) {
				$output[] = 'penci-main-content';
			}
		} else {
			if ( ! empty( $matches ) ) {
				$part_x = (int) $matches[1];
				$part_y = (int) $matches[2];
				if ( $part_x > 0 && $part_y > 0 ) {
					$value = ceil( $part_x / $part_y * 12 );
					if ( $value > 0 && $value <= 12 ) {
						$output[] = 'penci-col-' . $value;
					}
				}
			}
			if ( preg_match( '/\d+\/5$/', $width ) ) {
				$output[] = 'penci-col-' . $width;
			}
		}

		if ( '11' == $width ) {
			$output[] = 'penci-col-12';
		}

		$output = implode( ' ', $output );

		if ( ! $output ) {
			$output = $width;
		}

		return apply_filters( 'penci_vc_translate_column_width_class', $output, $width );
	}
endif;

if ( ! class_exists( 'Penci_Vc_Helper' ) ) :
	class Penci_Vc_Helper {
		public static function get_unique_id_block( $block_id ) {
			return 'penci' . $block_id . '_' . rand( 1000, 100000 );
		}

		public static function get_http() {
			return is_ssl() ? 'https://' : 'http://';
		}

		/**
		 * Get typo of element
		 *
		 * @param $args
		 *
		 * @return string
		 */
		public static function vc_google_fonts_parse_attributes( $args ) {

			$args = wp_parse_args( $args, array(
				'template'   => '',
				'font_size'  => '',
				'font_style' => '',
				'media'      => '768',
			) );

			$output = $styles = $css_size = '';

			// Render google style
			if ( $args['font_style'] ) {
				$fonts_data = vc_parse_multi_attribute( $args['font_style'], array(
					'font_family' => '',
					'font_style'  => '',
				) );

				$fonts_family = explode( ':', $fonts_data['font_family'] );
				$fonts_styles = explode( ':', $fonts_data['font_style'] );

				if ( $fonts_family ) {

					$google_fonts_obj = new Vc_Google_Fonts();
					$font             = $google_fonts_obj->_vc_google_fonts_parse_attributes( array(), trim( $args['font_style'] ) );
					$font             = $font['values'];
					list( $font_family_load ) = explode( ':', $font['font_family'] . ':' );
					$penci_font_enqueue = array( 'Raleway', 'PT Serif' );

					$settings = get_option( 'wpb_js_google_fonts_subsets' );
					if ( is_array( $settings ) && ! empty( $settings ) ) {
						$subsets = '&subset=' . implode( ',', $settings );
					} else {
						$subsets = '';
					}

					if ( $font_family_load && ! in_array( $font_family_load, $penci_font_enqueue ) ) {
						wp_enqueue_style( 'vc_google_fonts_' . vc_build_safe_css_class( urlencode( $font_family_load ) ), '//fonts.googleapis.com/css?family=' . urlencode( $font_family_load ) . $subsets );
					}
				}

				if ( isset( $fonts_family[0] ) && $fonts_family[0] ) {
					$styles .= 'font-family:' . $fonts_family[0] . ';';
				}
				if ( isset( $fonts_styles[1] ) && $fonts_styles[1] ) {
					$styles .= 'font-weight:' . $fonts_styles[1] . ';';
				}
				if ( isset( $fonts_styles[2] ) && $fonts_styles[2] ) {
					$styles .= 'font-style:' . $fonts_styles[2] . ';';
				}
			}

			$multi_fonts = strlen( $args['font_size'] ) > 5;

			// Render font size
			$css_size = '';
			if ( $args['font_size'] && ! $multi_fonts ) {
				$css_size = 'font-size:' . $args['font_size'] . ';';
			}

			// Check Media screen
			if ( $css_size && $args['media'] && ! $multi_fonts ) {
				$output .= sprintf( '@media screen and (min-width: %spx ){' . $args['template'] . '}', $args['media'], $css_size );
			} elseif ( $multi_fonts ) {
				$font_size_arr = json_decode( base64_decode( $args['font_size'] ), true );
				if ( isset( $font_size_arr['data'] ) && $font_size_arr['data'] ) {
					foreach ( $font_size_arr['data'] as $devices => $size ) {

						if ( 'tablet' == $devices ) {
							$output .= sprintf( '@media only screen and (min-width:767px) and (max-width:1200px){' . $args['template'] . '}', $size );
						} elseif ( 'mobile' == $devices ) {
							$output .= sprintf( '@media only screen and (max-width:767px){' . $args['template'] . '}', $size );
						} else {
							$output .= sprintf( $args['template'], $size );
						}
					}
				}
			} else {
				$styles .= $css_size;
			}

			if ( $styles ) {
				$output .= sprintf( $args['template'], $styles );
			}

			return $output;
		}

		public static function markup_block_title( $args ) {
			$defaults = array(
				'heading_title_style'  => 'style-1',
				'heading'              => '',
				'heading_title_link'   => '',
				'add_title_icon'       => '',
				'block_title_icon'     => '',
				'block_title_ialign'   => '',
				'block_title_align'    => '',
				'block_title_offupper' => '',
				'heading_icon_pos'     => '',
				'heading_icon'         => '',
				'block_title_marginbt' => '',
				'blockid'              => '',
			);

			$r = wp_parse_args( $args, $defaults );

			if ( ! $r['heading'] ) {
				return;
			}
			if ( 'video_list' == $r['heading_title_style'] ) {
				return;
			}

			$heading_title = get_theme_mod( 'penci_sidebar_heading_style' ) ? get_theme_mod( 'penci_sidebar_heading_style' ) : 'style-1';
			$heading_align = get_theme_mod( 'penci_sidebar_heading_align' ) ? get_theme_mod( 'penci_sidebar_heading_align' ) : 'pcalign-center';

			if ( $r['heading_title_style'] ) {
				$heading_title = $r['heading_title_style'];
			}

			if ( $r['block_title_align'] ) {
				$heading_align = $r['block_title_align'];
			}

			$sb_icon_pos         = get_theme_mod( 'penci_sidebar_icon_align' ) ? get_theme_mod( 'penci_sidebar_icon_align' ) : 'pciconp-right';
			$heading_icon_pos    = get_theme_mod( 'penci_homep_icon_align' ) ? get_theme_mod( 'penci_homep_icon_align' ) : $sb_icon_pos;
			$sb_icon_design      = get_theme_mod( 'penci_sidebar_icon_design' ) ? get_theme_mod( 'penci_sidebar_icon_design' ) : 'pcicon-right';
			$heading_icon_design = get_theme_mod( 'penci_homep_icon_design' ) ? get_theme_mod( 'penci_homep_icon_design' ) : $sb_icon_design;

			if ( $r['heading_icon_pos'] ) {
				$heading_icon_pos = $r['heading_icon_pos'];
			}

			if ( $r['heading_icon'] ) {
				$heading_icon_design = $r['heading_icon'];
			}

			$classes = 'penci-border-arrow penci-homepage-title penci-home-latest-posts';
			$classes .= ' ' . $heading_title;
			$classes .= ' ' . $heading_align;
			$classes .= ' ' . $heading_icon_pos;
			$classes .= ' ' . $heading_icon_design;
			?>
            <div class="<?php echo esc_attr( $classes ); ?>">
                <h3 class="inner-arrow">
					<?php
					if ( $r['heading_title_link'] ) {
						echo '<a href="' . esc_url( $r['heading_title_link'] ) . '">';
					} else {
						echo '<span>';
					}

					if ( $r['add_title_icon'] && $r['block_title_icon'] && 'left' == $r['block_title_ialign'] ) {
						echo '<i class="' . esc_attr( $r['block_title_icon'] ) . '"></i>';
					}
					echo do_shortcode( $r['heading'] );
					if ( $r['add_title_icon'] && $r['block_title_icon'] && 'right' == $r['block_title_ialign'] ) {
						penci_icon_by_ver( 'fa-pos-right ' . esc_attr( $r['block_title_icon'] ) );
					}
					if ( $r['heading_title_link'] ) {
						echo '</a>';
					} else {
						echo '</span>';
					}
					?>
                </h3>
				<?php do_action( 'penci_block_title_extra_' . $r['blockid'] ); ?>
            </div>
			<?php
		}

		public static function get_heading_block_filter_css( $block_id_css, $args ) {
			$defaults = [
				'link_fsize'                      => '',
				'nexprev_fsize'                   => '',
				'btitle_typo'                     => '',
				'btitle_fsize'                    => '',
				'heading_filter_color'            => '',
				'heading_filter_hcolor'           => '',
				'heading_filter_dropdown_bgcolor' => '',
				'heading_filter_dropdown_bdcolor' => '',
				'dropdown_l_color'                => '',
				'dropdown_ha_color'               => '',
				'loading_icolor'                  => '',
				'loadingo_bg_color'               => '',
				'loading_opacity_color'           => '',
			];

			$r      = wp_parse_args( $args, $defaults );
			$output = '';

			if ( $r['link_fsize'] ) {
				$output .= $block_id_css . ' .pcnav-lgroup ul.pcflx li a{padding-left: calc(' . $r['link_fsize'] . 'px / 2);padding-right: calc(' . $r['link_fsize'] . 'px / 2);}';
				$output .= $block_id_css . ' .pcnav-lgroup ul.pcflx ul li a{padding-left: 0;padding-right: 0;}';
				$output .= $block_id_css . ' .pcnav-lgroup ul.pcflx-nav{padding-left: calc(' . $r['link_fsize'] . 'px - 3px);';
			}

			if ( $r['btitle_typo'] ) {
				$output .= self::vc_google_fonts_parse_attributes( array(
					'font_size'  => $r['btitle_fsize'],
					'font_style' => $r['btitle_typo'],
					'template'   => $block_id_css . ' .pcnav-lgroup ul li a{ %s }',
				) );
			}

			if ( $r['nexprev_fsize'] ) {
				$output .= penci_extract_responsive_fsize( $block_id_css . ' .pcnav-lgroup ul > li > a.pcaj-nav-link', 'font-size', $r['nexprev_fsize'] );
			}

			if ( $r['heading_filter_color'] ) {
				$output .= $block_id_css . ' .pcnav-lgroup ul > li > a,' . $block_id_css . '  .pcnav-lgroup ul > li{opacity: 1;color:' . $r['heading_filter_color'] . '}';
			}

			if ( $r['heading_filter_hcolor'] ) {
				$output .= $block_id_css . ' .pcnav-lgroup ul > li > a:hover,' . $block_id_css . ' .pcnav-lgroup ul > li:hover,' . $block_id_css . ' .pcnav-lgroup ul li > a.clactive{color:' . $r['heading_filter_hcolor'] . '}';
			}

			if ( $r['heading_filter_dropdown_bgcolor'] ) {
				$output .= $block_id_css . ' .pcnav-lgroup ul ul{background-color:' . $r['heading_filter_dropdown_bgcolor'] . '}';
			}

			if ( $r['heading_filter_dropdown_bdcolor'] ) {
				$output .= $block_id_css . ' .pcnav-lgroup ul ul li,' . $block_id_css . ' .pcnav-lgroup ul ul{border-color:' . $r['heading_filter_dropdown_bdcolor'] . '}';
			}

			if ( $r['dropdown_l_color'] ) {
				$output .= $block_id_css . ' .pcnav-lgroup ul ul li,' . $block_id_css . ' .pcnav-lgroup ul ul li a{opacity:1;color:' . $r['dropdown_l_color'] . '}';
			}

			if ( $r['dropdown_ha_color'] ) {
				$output .= $block_id_css . ' .pcnav-lgroup ul ul li a.clactive,{{WRAPPER}} .pcnav-lgroup ul ul li:hover,{{WRAPPER}} .pcnav-lgroup ul ul li a:hover{opacity:1;color:' . $r['dropdown_ha_color'] . '}';
			}

			if ( $r['loading_icolor'] ) {
				$output .= $block_id_css . ' .penci-loading-animation-1 .penci-loading-animation,' . $block_id_css . ' .penci-loading-animation-1 .penci-loading-animation:before,' . $block_id_css . ' .penci-loading-animation-1 .penci-loading-animation:after,' . $block_id_css . ' .penci-loading-animation-5 .penci-loading-animation,' . $block_id_css . ' .penci-loading-animation-6 .penci-loading-animation:before,' . $block_id_css . ' .penci-loading-animation-7 .penci-loading-animation,' . $block_id_css . ' .penci-loading-animation-8 .penci-loading-animation,' . $block_id_css . ' .penci-loading-animation-9 .penci-loading-circle-inner:before,' . $block_id_css . ' .penci-loading-animation-1>div,' . $block_id_css . ' .penci-three-bounce .one,' . $block_id_css . ' .penci-three-bounce .two,.penci-three-bounce .three{background-color:' . $r['loading_icolor'] . '}';
				$output .= $block_id_css . '{--pc-loader-2:' . $r['loading_icolor'] . '}';
			}

			if ( $r['loadingo_bg_color'] ) {
				$output .= $block_id_css . ' .pcftaj-ld:before{background-color:' . $r['loadingo_bg_color'] . '}';
			}

			if ( $r['loading_opacity_color'] ) {
				$output .= $block_id_css . ' .pcftaj-ld:before{opacity:' . $r['loading_opacity_color'] . '}';
			}

			return $output;
		}

		public static function get_bookmark_icon_css( $block_id_css, $args ) {
			if ( ! defined( 'PENCI_BL_VERSION' ) ) {
				return;
			}
			$defaults = array(
				'penci_bf_icon_sizes'          => '',
				'penci_bf_icon_fsizes'         => '',
				'penci_bf_icon_icon_color'     => '',
				'penci_bf_icon_icon_bcolor'    => '',
				'penci_bf_icon_icon_bgcolor'   => '',
				'penci_bf_icon_icon_hcolor'    => '',
				'penci_bf_icon_icon_hbcolor'   => '',
				'penci_bf_icon_icon_hbgcolor'  => '',
				'penci_bf_icon_icon_bmcolor'   => '',
				'penci_bf_icon_icon_bmbcolor'  => '',
				'penci_bf_icon_icon_bmbgcolor' => '',
			);
			$r        = wp_parse_args( $args, $defaults );
			$output   = '';

			if ( $r['penci_bf_icon_sizes'] ) {
				$output .= penci_extract_md_responsive_fsize( '{{WRAPPER}} .penci-bf-follow-post-wrapper .pencibf-following-text:before{ width:{{VALUE}};height:{{VALUE}};line-height:{{VALUE}};}', $r['penci_bf_icon_sizes'] );
			}

			if ( $r['penci_bf_icon_fsizes'] ) {
				$output .= penci_extract_md_responsive_fsize( '{{WRAPPER}} .penci-bf-follow-post-wrapper .pencibf-following-text:before{ font-size:{{VALUE}};}', $r['penci_bf_icon_fsizes'] );
			}

			if ( $r['penci_bf_icon_icon_color'] ) {
				$output .= '{{WRAPPER}} .penci-bf-follow-post-wrapper .pencibf-following-text:before{color:' . $r['penci_bf_icon_icon_color'] . '}';
			}
			if ( $r['penci_bf_icon_icon_bcolor'] ) {
				$output .= '{{WRAPPER}} .penci-bf-follow-post-wrapper .pencibf-following-text:before{border-color:' . $r['penci_bf_icon_icon_bcolor'] . '}';
			}
			if ( $r['penci_bf_icon_icon_bgcolor'] ) {
				$output .= '{{WRAPPER}} .penci-bf-follow-post-wrapper .pencibf-following-text:before{background-color:' . $r['penci_bf_icon_icon_bgcolor'] . '}';
			}

			//hover
			if ( $r['penci_bf_icon_icon_hcolor'] ) {
				$output .= '{{WRAPPER}} .penci-bf-follow-post-wrapper .pencibf-following-text:hover:before{color:' . $r['penci_bf_icon_icon_hcolor'] . '}';
			}
			if ( $r['penci_bf_icon_icon_hbcolor'] ) {
				$output .= '{{WRAPPER}} .penci-bf-follow-post-wrapper .pencibf-following-text:hover:before{border-color:' . $r['penci_bf_icon_icon_hbcolor'] . '}';
			}
			if ( $r['penci_bf_icon_icon_hbgcolor'] ) {
				$output .= '{{WRAPPER}} .penci-bf-follow-post-wrapper .pencibf-following-text:hover:before{background-color:' . $r['penci_bf_icon_icon_hbgcolor'] . '}';
			}

			//activated
			if ( $r['penci_bf_icon_icon_bmcolor'] ) {
				$output .= '{{WRAPPER}} .penci-bf-follow-post-wrapper .penci-bf-following-button .pencibf-following-text:before{color:' . $r['penci_bf_icon_icon_bmcolor'] . '!important}';
			}
			if ( $r['penci_bf_icon_icon_bmbcolor'] ) {
				$output .= '{{WRAPPER}} .penci-bf-follow-post-wrapper .penci-bf-following-button .pencibf-following-text:before{border-color:' . $r['penci_bf_icon_icon_bmbcolor'] . '!important}';
			}
			if ( $r['penci_bf_icon_icon_bmbgcolor'] ) {
				$output .= '{{WRAPPER}} .penci-bf-follow-post-wrapper .penci-bf-following-button .pencibf-following-text:before{background-color:' . $r['penci_bf_icon_icon_bmbgcolor'] . '!important}';
			}

			if ( $output ) {
				$output = str_replace( '{{WRAPPER}}', $block_id_css, $output );
			}

			return $output;

		}

		public static function get_heading_block_css( $block_id_css, $args ) {
			$defaults = array(
				'block_title_color'        => '',
				'block_title_hcolor'       => '',
				'btitle_bcolor'            => '',
				'btitle_outer_bcolor'      => '',
				'btitle_style5_bcolor'     => '',
				'btitle_style78_bcolor'    => '',
				'btitle_bgcolor'           => '',
				'btitle_outer_bgcolor'     => '',
				'btitle_style9_bgimg'      => '',
				'use_btitle_typo'          => '',
				'btitle_typo'              => '',
				'btitle_fsize'             => '',
				'block_title_offupper'     => '',
				'block_title_marginbt'     => '',
				'btitle_style10_btopcolor' => '',
				'btitle_shapes_color'      => '',
				'bgstyle15_color'          => '',
				'iconstyle15_color'        => '',
				'cl_lines'                 => '',
			);

			$r = wp_parse_args( $args, $defaults );

			$output = '';
			if ( $r['block_title_color'] ) {
				$output .= $block_id_css . ' .penci-border-arrow .inner-arrow a,';
				$output .= $block_id_css . ' .penci-border-arrow .inner-arrow{ color: ' . esc_attr( $r['block_title_color'] ) . '; }';
			}

			if ( $r['block_title_hcolor'] ) {
				$output .= $block_id_css . ' .penci-border-arrow .inner-arrow a:hover{ color: ' . esc_attr( $r['block_title_hcolor'] ) . '; }';
			}
			if ( $r['btitle_bcolor'] ) {
				$output .= $block_id_css . ' .penci-border-arrow .inner-arrow,';
				$output .= $block_id_css . ' .style-4.penci-border-arrow .inner-arrow:before,';
				$output .= $block_id_css . ' .style-4.penci-border-arrow .inner-arrow:after,';
				$output .= $block_id_css . ' .style-5.penci-border-arrow,';
				$output .= $block_id_css . ' .style-7.penci-border-arrow,';
				$output .= $block_id_css . ' .style-9.penci-border-arrow { border-color: ' . esc_attr( $r['btitle_bcolor'] ) . '; }';
				$output .= $block_id_css . ' .penci-border-arrow:before{ border-top-color: ' . esc_attr( $r['btitle_bcolor'] ) . '; }';
				$output .= $block_id_css . ' .style-16.penci-border-arrow:after{ background-color: ' . esc_attr( $r['btitle_bcolor'] ) . '; }';
			}

			if ( $r['btitle_style5_bcolor'] ) {
				$output .= $block_id_css . ' .style-5.penci-border-arrow{ border-color: ' . esc_attr( $r['btitle_style5_bcolor'] ) . '; }';

				$output .= $block_id_css . ' .style-11.penci-border-arrow,';
				$output .= $block_id_css . ' .penci-homepage-title.style-10,';
				$output .= $block_id_css . ' .style-12.penci-border-arrow,';
				$output .= $block_id_css . ' .style-5.penci-border-arrow .inner-arrow{ border-bottom-color: ' . esc_attr( $r['btitle_style5_bcolor'] ) . '; }';
			}
			if ( $r['btitle_style78_bcolor'] ) {
				$output .= $block_id_css . ' .style-7.penci-border-arrow .inner-arrow:before,';
				$output .= $block_id_css . ' .style-9.penci-border-arrow .inner-arrow:before{ background-color: ' . esc_attr( $r['btitle_style78_bcolor'] ) . '; }';
			}

			if ( $r['btitle_outer_bcolor'] ) {
				$output .= $block_id_css . ' .penci-border-arrow:after{ border-color: ' . esc_attr( $r['btitle_outer_bcolor'] ) . '; }';
			}
			if ( $r['btitle_style10_btopcolor'] ) {
				$output .= $block_id_css . ' .style-10.penci-border-arrow{ border-top-color: ' . esc_attr( $r['btitle_style10_btopcolor'] ) . '; }';
			}

			if ( $r['btitle_shapes_color'] ) {
				$output .= $block_id_css . ' .style-13.pcalign-center .inner-arrow:before,';
				$output .= $block_id_css . ' .style-13.pcalign-right .inner-arrow:before { border-left-color: ' . esc_attr( $r['btitle_shapes_color'] ) . ' !important; }';

				$output .= $block_id_css . ' .style-13.pcalign-center .inner-arrow:after,';
				$output .= $block_id_css . ' .style-13.pcalign-left .inner-arrow:after { border-right-color: ' . esc_attr( $r['btitle_shapes_color'] ) . ' !important; }';

				$output .= $block_id_css . ' .style-12 .inner-arrow:before,';
				$output .= $block_id_css . ' .style-12.pcalign-right .inner-arrow:after,';
				$output .= $block_id_css . ' .style-12.pcalign-center .inner-arrow:after{ border-bottom-color: ' . esc_attr( $r['btitle_shapes_color'] ) . ' !important; }';

				$output .= $block_id_css . ' .style-11 .inner-arrow:after,';
				$output .= $block_id_css . ' .style-11 .inner-arrow:before{ border-top-color: ' . esc_attr( $r['btitle_shapes_color'] ) . ' !important; }';
			}

			if ( $r['bgstyle15_color'] ) {
				$output .= $block_id_css . ' .style-15.penci-border-arrow:before{ background-color: ' . esc_attr( $r['bgstyle15_color'] ) . ' !important; }';
			}

			if ( $r['iconstyle15_color'] ) {
				$output .= $block_id_css . ' .style-15.penci-border-arrow:after{ color: ' . esc_attr( $r['iconstyle15_color'] ) . ' !important; }';
			}

			if ( $r['cl_lines'] ) {
				$output .= $block_id_css . ' .style-18.penci-border-arrow:after{ color: ' . esc_attr( $r['cl_lines'] ) . ' !important; }';
			}

			if ( $r['btitle_bgcolor'] ) {
				$output .= $block_id_css . ' .penci-homepage-title.style-14 .inner-arrow:before,';
				$output .= $block_id_css . ' .penci-homepage-title.style-11 .inner-arrow,';
				$output .= $block_id_css . ' .penci-homepage-title.style-12 .inner-arrow,';
				$output .= $block_id_css . ' .penci-homepage-title.style-13 .inner-arrow,';
				$output .= $block_id_css . ' .penci-homepage-title .inner-arrow,';
				$output .= $block_id_css . ' .penci-homepage-title.style-15 .inner-arrow{ background-color: ' . esc_attr( $r['btitle_bgcolor'] ) . '; }';
				$output .= $block_id_css . ' .penci-border-arrow.penci-homepage-title.style-2:after{ border-top-color: ' . esc_attr( $r['btitle_bgcolor'] ) . '; }';
			}

			if ( $r['btitle_outer_bgcolor'] ) {
				$output .= $block_id_css . ' .penci-border-arrow:after{ background-color: ' . esc_attr( $r['btitle_outer_bgcolor'] ) . '; }';
			}

			if ( $r['btitle_style9_bgimg'] ) {
				$output .= $block_id_css . ' .style-8.penci-border-arrow .inner-arrow{ background-image: url(' . esc_url( wp_get_attachment_url( $r['btitle_style9_bgimg'] ) ) . '); }';
			}

			if ( $r['use_btitle_typo'] ) {
				$output .= self::vc_google_fonts_parse_attributes( array(
					'font_size'  => $r['btitle_fsize'],
					'font_style' => $r['btitle_typo'],
					'template'   => $block_id_css . ' .penci-border-arrow .inner-arrow{ %s }',
				) );
			}

			if ( $r['block_title_offupper'] ) {
				$output .= $block_id_css . ' .penci-border-arrow .inner-arrow{ text-transform: none; }';
			}
			if ( $r['block_title_marginbt'] ) {
				$output .= $block_id_css . ' penci-border-arrow { margin-bottom:' . esc_attr( $r['block_title_marginbt'] ) . ';}';
			}

			return $output;
		}

		/**
		 * Get url image
		 *
		 * @param $attach_id
		 * @param string $size
		 *
		 * @return mixed|void
		 */
		public static function get_image_holder_gal( $attach_id, $size = 'full', $image_type = 'horizontal', $is_background = true, $count = '', $class = '', $caption_source = '' ) {
			$list_url  = self::penci_image_downsize( $attach_id, array( $size, 'penci-full-thumb' ) );
			$src_large = isset( $list_url['penci-full-thumb']['img_url'] ) ? $list_url['penci-full-thumb']['img_url'] : '';
			$src_thmb  = isset( $list_url[ $size ]['img_url'] ) ? $list_url[ $size ]['img_url'] : '';

			$class_lazy = '';


			if ( $image_type ) {
				$class_lazy .= ' penci-image-' . $image_type;
			}

			$caption_markup     = '';
			$gallery_title      = '';
			$attachment_caption = wp_get_attachment_caption( $attach_id );
			if ( $attachment_caption && 'attachment' == $caption_source ) {
				$caption_markup = '<span class="caption">' . wp_kses( $attachment_caption, array(
						'em'     => array(),
						'strong' => array(),
						'b'      => array(),
						'i'      => array(),
					) ) . '</span>';
				$gallery_title  = ' data-cap="' . esc_attr( $attachment_caption ) . '"';
			}

			if ( $is_background ) {
				ob_start();
				?>
                <div class="penci-gallery-item penci-galitem-<?php echo $count . ( $class ? ' ' . $class : '' ); ?>">
                    <a <?php echo penci_layout_bg( $src_thmb );?> class="<?php echo penci_layout_bg_class();?> penci-gallery-ite penci-image-holder<?php echo $class_lazy; ?>"
                       href="<?php echo $src_large; ?>" <?php echo $gallery_title; ?>>
						<?php echo $caption_markup; ?>
                        <?php echo penci_layout_img( $src_thmb );?>
                        <i class="penciicon-expand"></i>
                    </a>
                </div>
				<?php
				$output = ob_get_clean();

			} else {
				ob_start();
				?>
                <a class="penci-gallery-ite <?php echo $class_lazy . ( $class ? ' ' . $class : '' ); ?>"
                   href="<?php echo $src_large; ?>" <?php echo $gallery_title; ?>>
                    <img src="<?php echo $src_thmb; ?>" alt="<?php echo self::get_image_alt( $attach_id ); ?>"/>
					<?php echo $caption_markup; ?>
                    <i class="penciicon-expand"></i>
                </a>
				<?php
				$output = ob_get_clean();
			}

			return $output;
		}

		public static function penci_image_downsize( $id, $sizes = array( 'medium' ) ) {

			$img_url          = wp_get_attachment_url( $id );
			$img_url_basename = wp_basename( $img_url );

			$list_url = array();

			foreach ( $sizes as $size ) {
				$img_url_pre = $width = $height = '';
				if ( $intermediate = image_get_intermediate_size( $id, $size ) ) {
					$img_url_pre = isset( $intermediate['url'] ) ? $intermediate['url'] : $img_url;
					$width       = isset( $intermediate['width'] ) ? $intermediate['width'] : '';
					$height      = isset( $intermediate['height'] ) ? $intermediate['height'] : '';
				} elseif ( $size == 'thumbnail' ) {
					if ( ( $thumb_file = wp_get_attachment_thumb_file( $id ) ) && $info = getimagesize( $thumb_file ) ) {
						$img_url_pre = str_replace( $img_url_basename, wp_basename( $thumb_file ), $img_url );
						$width       = $info[0];
						$height      = $info[1];
					}
				} else {
					$img_url_pre = $img_url;
				}

				$list_url[ $size ] = array(
					'img_url' => $img_url_pre,
					'height'  => $height,
					'width'   => $width,
				);
			}

			return $list_url;
		}

		/**
		 * Get media control image alt.
		 * Retrieve the `alt` value of the image selected by the media control.
		 *
		 * @return string Image alt.
		 */
		public static function get_image_alt( $instance ) {
			if ( empty( $instance['id'] ) ) {
				return '';
			}

			$attachment_id = $instance['id'];
			if ( ! $attachment_id ) {
				return '';
			}

			$attachment = get_post( $attachment_id );
			if ( ! $attachment ) {
				return '';
			}

			$alt = get_post_meta( $attachment_id, '_wp_attachment_image_alt', true );
			if ( ! $alt ) {
				$alt = $attachment->post_excerpt;
				if ( ! $alt ) {
					$alt = $attachment->post_title;
				}
			}

			return trim( strip_tags( $alt ) );
		}

		public static function _login_form() {
			?>
            <form class="penci-loginform" name="penci-loginform" id="penci-loginform"
                  action="<?php echo esc_url( site_url( 'wp-login.php' ) ); ?>" method="post" novalidate="novalidate">
                <input type="hidden" name="_wpnonce" class="penci_form_nonce"
                       value="<?php echo wp_create_nonce( 'login' ); ?>">
                <p class="login-username">
                    <input type="text" name="log" id="penci-user-login" class="input penci-input"
                           placeholder="<?php echo penci_get_setting( 'penci_trans_usernameemail_text' ); ?>" size="20">
                </p>
                <p class="login-password">
                    <input type="password" name="pwd" id="penci-user-pass" class="input penci-input"
                           placeholder="<?php echo penci_get_setting( 'penci_trans_pass_text' ); ?>" size="20">
                </p>
				<?php do_action( 'login_form' ); ?>
				<?php penci_add_captcha_login_form(); ?>
                <p class="login-remember">
                    <input name="rememberme" type="checkbox" id="rememberme"
                           value="forever"> <?php echo penci_get_setting( 'penci_plogin_label_remember' ); ?>
                </p>
                <p class="login-submit">
                    <input type="submit" name="wp-submit" class="pcpop-button"
                           value="<?php echo penci_get_setting( 'penci_plogin_label_log_in' ); ?>">
                </p>
            </form>
            <div class="penci-loginform-extra">
                <a class="penci-lostpassword"
                   href="<?php echo esc_url( wp_lostpassword_url() ); ?>"><?php echo penci_get_setting( 'penci_plogin_label_lostpassword' ); ?></a>
				<?php if ( get_option( 'users_can_register' ) ) : ?>
                    <a class="penci-user-register"
                       href="<?php echo esc_url( wp_registration_url() ); ?>"><?php echo penci_get_setting( 'penci_plogin_label_registration' ); ?></a>
				<?php endif; ?>
            </div>
			<?php
		}

		/**
		 * Get image sizes.
		 *
		 * Retrieve available image sizes after filtering `include` and `exclude` arguments.
		 */
		/**
		 * Get image sizes.
		 *
		 * Retrieve available image sizes after filtering `include` and `exclude` arguments.
		 */
		public static function get_list_image_sizes( $default = false ) {
			$wp_image_sizes = self::get_all_image_sizes();

			$image_sizes = array();

			if ( $default ) {
				$image_sizes[ esc_html__( 'Default', 'soledad' ) ] = '';
			}

			foreach ( $wp_image_sizes as $size_key => $size_attributes ) {
				$control_title = ucwords( str_replace( '_', ' ', $size_key ) );
				if ( is_array( $size_attributes ) ) {
					$control_title .= sprintf( ' - %d x %d', $size_attributes['width'], $size_attributes['height'] );
				}

				$image_sizes[ $control_title ] = $size_key;
			}

			$image_sizes[ _x( 'Full', 'Image Size Control', 'soledad' ) ] = 'full';

			return $image_sizes;
		}

		public static function get_all_image_sizes() {
			global $_wp_additional_image_sizes;

			$default_image_sizes = array( 'thumbnail', 'medium', 'medium_large', 'large' );

			$image_sizes = array();

			foreach ( $default_image_sizes as $size ) {
				$image_sizes[ $size ] = array(
					'width'  => (int) get_option( $size . '_size_w' ),
					'height' => (int) get_option( $size . '_size_h' ),
					'crop'   => (bool) get_option( $size . '_crop' ),
				);
			}

			if ( $_wp_additional_image_sizes ) {
				$image_sizes = array_merge( $image_sizes, $_wp_additional_image_sizes );
			}

			return $image_sizes;
		}
	}
endif;

if ( ! function_exists( 'penci_get_link_attributes' ) ) {
	function penci_get_link_attributes( $link, $popup = false ) {
		// parse link
		$use_link = false;
		if ( isset( $link['url'] ) && strlen( $link['url'] ) > 0 ) {
			$use_link = true;
			$a_href   = apply_filters( 'penci_extra_menu_url', $link['url'] );
			if ( $popup ) {
				$a_href = $link['url'];
			}
			$a_title  = $link['title'];
			$a_target = $link['target'];
		}

		$attributes = array();

		if ( $use_link ) {
			$attributes[] = 'href="' . trim( $a_href ) . '"';
			$attributes[] = 'title="' . esc_attr( trim( $a_title ) ) . '"';
			if ( ! empty( $a_target ) ) {
				$attributes[] = 'target="' . esc_attr( trim( $a_target ) ) . '"';
			}
		}

		$attributes = implode( ' ', $attributes );

		return $attributes;
	}
}

if ( ! function_exists( 'penci_is_css_encode' ) ) {
	function penci_is_css_encode( $data ) {
		return strlen( $data ) > 50;
	}
}

if ( ! function_exists( 'penci_get_menu_label_tag' ) ) {
	function penci_get_menu_label_tag( $label, $label_text ) {
		if ( empty( $label_text ) ) {
			return '';
		}
		$label_out = '<span class="menu-label menu-label-' . $label . '">' . esc_attr( $label_text ) . '</span>';

		return $label_out;
	}
}


if ( ! function_exists( 'penci_get_menu_label_class' ) ) {
	function penci_get_menu_label_class( $label ) {
		$class = '';
		$class .= ' item-with-label';
		$class .= ' item-label-' . $label;

		return $class;
	}
}

// **********************************************************************//
// Get explode size
// **********************************************************************//
if ( ! function_exists( 'penci_get_explode_size' ) ) {
	function penci_get_explode_size( $img_size, $default_size ) {
		$sizes = explode( 'x', $img_size );
		if ( count( $sizes ) < 2 ) {
			$sizes[0] = $sizes[1] = $default_size;
		}

		return $sizes;
	}
}

if ( ! function_exists( 'penci_display_icon' ) ) {
	function penci_display_icon( $img_id, $img_size, $default_size ) {
		$icon     = wpb_getImageBySize( array(
			'attach_id'  => $img_id,
			'thumb_size' => $img_size,
		) );
		$icon_src = $icon['p_img_large'][0];
		$icon_id  = rand( 999, 9999 );

		$sizes = penci_get_explode_size( $img_size, $default_size );

		if ( substr( $icon_src, - 3, 3 ) == 'svg' ) {
			return '<div class="img-wrapper"><span class="svg-icon" style="width: ' . $sizes[0] . 'px;height: ' . $sizes[1] . 'px;">' . penci_get_any_svg( $icon_src, $icon_id ) . '</span></div>';
		} else {
			return '<div class="img-wrapper">' . $icon['thumbnail'] . '</div>';
		}
	}
}

if ( ! function_exists( 'penci_get_vc_responsive_spacing_map' ) ) {
	/**
	 * Get responsive spacing option map.
	 *
	 * @return array map.
	 */
	function penci_get_vc_responsive_spacing_map() {
		return array(
			'type'       => 'penci_responsive_spacing',
			'param_name' => 'responsive_spacing',
			'group'      => esc_html__( 'Design Options', 'js_composer' ),
		);
	}
}

if ( ! function_exists( 'penci_extract_spacing_style' ) ) {
	function penci_extract_spacing_style( $selector, $value ) {
		$out      = '';
		$data_css = json_decode( base64_decode( $value ), true );
		if ( isset( $data_css['data'] ) && is_array( $data_css['data'] ) ) {
			foreach ( $data_css['data'] as $devices => $d_value ) {
				if ( 'tablet' == $devices ) {
					$out .= '@media only screen and (min-width:768px) and (max-width:1169px){';
				}
				if ( 'mobile' == $devices ) {
					$out .= '@media only screen and (max-width:767px){';
				}
				foreach ( $d_value as $prop => $pvalue ) {
					$out .= $selector . '{' . $prop . ':' . $pvalue . 'px;}';
				}
				$out .= '}';
			}
		}

		return $out;
	}
}

if ( ! function_exists( 'penci_extract_responsive_fsize' ) ) {
	function penci_extract_responsive_fsize( $selector, $props, $value ) {
		$out = '';

		if ( strlen( $value ) > 5 ) {

			$data_css = json_decode( base64_decode( $value ), true );
			if ( isset( $data_css['data'] ) && is_array( $data_css['data'] ) ) {
				foreach ( $data_css['data'] as $devices => $d_value ) {
					if ( 'tablet' == $devices ) {
						$out .= '@media only screen and (min-width:768px) and (max-width:1169px){';
					}
					if ( 'mobile' == $devices ) {
						$out .= '@media only screen and (max-width:767px){';
					}

					if ( is_array( $props ) ) {
						foreach ( $props as $prop ) {
							$out .= $selector . '{' . $prop . ':' . $d_value . '}';
						}
					} else {
						$out .= $selector . '{' . $props . ':' . $d_value . '}';
					}


					$out .= '}';
				}
			}
		} else {
			$out .= $selector . '{' . $props . ':' . $value . '}';
		}

		return $out;
	}
}

if ( ! function_exists( 'penci_extract_md_responsive_fsize' ) ) {
	function penci_extract_md_responsive_fsize( $props, $value ) {
		$out = '';

		if ( strlen( $value ) > 5 ) {

			$data_css = json_decode( base64_decode( $value ), true );

			if ( isset( $data_css['data'] ) && is_array( $data_css['data'] ) ) {

				foreach ( $data_css['data'] as $devices => $d_value ) {
					if ( 'tablet' == $devices ) {
						$out .= '@media only screen and (min-width:768px) and (max-width:1169px){';
					}
					if ( 'mobile' == $devices ) {
						$out .= '@media only screen and (max-width:767px){';
					}

					if ( is_array( $props ) ) {
						foreach ( $props as $prop ) {
							$out .= str_replace( '{{VALUE}}', $d_value, $prop );
						}
					} else {
						$out .= str_replace( '{{VALUE}}', $d_value, $props );
					}

					if ( 'tablet' == $devices || 'mobile' == $devices ) {
						$out .= '}';
					}
				}
			}
		} else {
			$out .= str_replace( '{{VALUE}}', $value, $props );
		}

		return str_replace( 'pxpx', 'px', $out );
	}
}

if ( ! function_exists( 'penci_wpbakery_el_extract_css' ) ) {
	function penci_wpbakery_el_extract_css( $properties, $settings, $wrapper_id, $extra_css = '' ) {
		$out = '';
		foreach ( $properties as $id => $selectors ) {
			if ( isset( $settings[ $id ] ) && $settings[ $id ] ) {
				foreach ( $selectors as $selector => $value ) {
					$value    = str_replace( '{{VALUE}}', $settings[ $id ], $value );
					$selector = str_replace( '{{WRAPPER}}', $wrapper_id, $selector );
					$out      .= $selector . '{' . $value . '}';
				}
			}
		}
		if ( $settings['responsive_spacing'] ) {
			$out .= penci_extract_spacing_style( $wrapper_id, $settings['responsive_spacing'] );
		}
		if ( $extra_css ) {
			$out .= $extra_css;
		}
		if ( $out ) {
			echo '<style>' . $out . '</style>';
		}
	}
}
if ( ! function_exists( 'penci_wpbakery_taxonomies_list' ) ) {
	function penci_wpbakery_taxonomies_list() {
		$return = [];
		$datas  = get_object_taxonomies( 'post', 'object' );
		if ( ! empty( $datas ) ) {
			foreach ( $datas as $data ) {
				$return[] = [
					'value' => $data->name,
					'label' => $data->label,
				];
			}
		}

		return $return;
	}
}
if ( ! function_exists( 'penci_wpbakery_taxonomies_list_arr' ) ) {
	function penci_wpbakery_taxonomies_list_arr() {
		$return = [];
		$datas  = get_object_taxonomies( 'post', 'object' );
		if ( ! empty( $datas ) ) {
			foreach ( $datas as $data ) {
				$return[$data->label] = $data->name;
			}
		}

		return $return;
	}
}
if ( ! function_exists( 'penci_wpbakery_taxonomies_posts_arr' ) ) {
	function penci_wpbakery_taxonomies_posts_arr() {
		$return = [];
		$datas  = get_object_taxonomies( 'post' );
		if ( ! empty( $datas ) ) {
			$args = array(
				'taxonomy'   => $datas,
				'hide_empty' => false,
			);
	
			$terms = get_terms( $args );
	
			if ( is_array( $terms ) && $terms ) {
				foreach ( $terms as $term ) {
					if ( is_object( $term ) ) {
						$return[] = array(
							'value'   => $term->term_id,
							'label' => $term->name . ' (' . $term->taxonomy . ')',
						);
					}
				}
			}
		}

		return $return;
	}
}
