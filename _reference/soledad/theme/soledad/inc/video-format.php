<?php
if ( ! class_exists( 'Penci_Sodedad_Video_Format' ) ) {
	class Penci_Sodedad_Video_Format {

		public static function get_type_video( $video_embed ) {
			if ( ! $video_embed ) {
				return '';
			}

			$video_embed = strtolower( $video_embed );
			if ( false !== strpos( $video_embed, 'vimeo.com' ) ) {
				return 'vimeo';
			}

			if ( false !== strpos( $video_embed, 'youtu.be' ) ||
				false !== strpos( $video_embed, 'youtube-nocookie.com' ) ||
				false !== strpos( $video_embed, 'youtube.com' ) ) {
				return 'youtube';
			}

			preg_match( '#^(http|https)://.+\.(mp4|m4v|webm|ogv|wmv|flv)$#i', $video_embed, $matches );
			if ( ! empty( $matches[0] ) ) {
				return 'selfhosted';
			}

			return '';
		}

		public static function show_video_embed( $atts ) {

			$post_id             = '';
			$parallax            = '';
			$div_special_wrapper = '';
			$single_style        = '';
			$args                = array();
			$show_title_inner    = false;
			$move_title_bellow   = false;

			$atts = wp_parse_args( $atts, array(
				'post_id'             => '',
				'parallax'            => '',
				'args'                => '',
				'show_title_inner'    => '',
				'move_title_bellow'   => '',
				'div_special_wrapper' => '',
				'single_style'        => ''
			) );
			extract( $atts );

			remove_all_filters( 'oembed_result' );

			$author_html = '';

			if ( 'style-22' == $single_style ) {
				$author_html = '<div class="penci-author-wrapper"><a href="'.get_author_posts_url( get_current_user_id() ).'">' . get_avatar( get_current_user_id(), 100 ) . '</a></div>';
			}

			$video_embed = get_post_meta( $post_id, '_format_video_embed', true );
			$type_video  = self::get_type_video( $video_embed );

			$output = '';
			if ( 'youtube' == $type_video ) {
				$video = self::get_youtube_link( $video_embed );

				if ( $parallax ) {
					if ( false !== strpos( $video, 'iframe' ) ) {
						$output .= '<div class="penci-jarallax" data-video-src="' . self::get_url_video_embed_code( $video ) . '"></div>';
					} else {
						$output .= '<div class="penci-jarallax" data-video-src="' . esc_url( $video ) . '"></div>';
					}
				} else {
					if ( wp_oembed_get( $video ) ) {
						if ( 'style-7' == $single_style || ( ! get_theme_mod( 'penci_move_title_bellow' ) && in_array( $single_style, array(
									'style-5',
									'style-6',
									'style-8'
								) ) ) ) {
							$iframe_video = wp_oembed_get( $video, $args );

							$patterns[]     = '/src="(.*?)"/';
							$replacements[] = 'src="${1}&autoplay=1&mute=1"';
							$output         .= preg_replace( $patterns, $replacements, $iframe_video );

						} else {
							$output .= wp_oembed_get( $video, $args );
						}

					} else {
						$output .= do_shortcode( $video );
					}
				}
			} elseif ( 'vimeo' == $type_video ) {
				if ( $parallax ) {
					if ( false !== strpos( $video_embed, 'iframe' ) ) {
						$output .= $video_embed;
					} else {
						$output .= '<div class="penci-jarallax" data-video-src="' . esc_url( $video_embed ) . '"></div>';
					}
				} else {
					if ( wp_oembed_get( $video_embed ) ) {

						if ( 'style-7' == $single_style || ( ! get_theme_mod( 'penci_move_title_bellow' ) && in_array( $single_style, array(
									'style-5',
									'style-6',
									'style-8'
								) ) ) ) {


							$iframe_video = wp_oembed_get( $video_embed, $args );

							$patterns[]     = '/src="(.*?)"/';
							$replacements[] = 'src="${1}&autoplay=1&loop=1&mute=1"';

							$output .= preg_replace( $patterns, $replacements, $iframe_video );

						} else {
							$output .= wp_oembed_get( $video_embed, $args );
						}
					} else {
						$output .= do_shortcode( $video_embed );
					}
				}
			} elseif ( 'selfhosted' == $type_video ) {
				$output .= do_shortcode( '[video src="' . $video_embed . '"]' );
			} else {
				$output .= do_shortcode( $video_embed );
			}

			echo '<div class="post-image penci-video-format-' . $type_video . ( ! $move_title_bellow ? ' penci-move-title-above' : '' ) . '">';
			echo $output;

			if ( $show_title_inner && ! $move_title_bellow ) {
				echo $div_special_wrapper;
				get_template_part( 'template-parts/single', 'breadcrumb' );
				get_template_part( 'template-parts/single', 'entry-header' );
				echo '</div>';
			}
			echo $author_html;
			echo '</div>';

		}

		public static function show_builder_video_embed( $atts,$settings ) {

			$post_id             = '';
			$parallax            = '';
			$div_special_wrapper = '';
			$single_style        = '';
			$args                = array();
			$show_title_inner    = false;
			$move_title_bellow   = false;

			$atts = wp_parse_args( $atts, array(
				'post_id'             => '',
				'parallax'            => '',
				'args'                => '',
				'show_title_inner'    => '',
				'move_title_bellow'   => '',
				'div_special_wrapper' => '',
				'single_style'        => ''
			) );
			extract( $atts );

			remove_all_filters( 'oembed_result' );


			$video_embed = get_post_meta( $post_id, '_format_video_embed', true );
			$type_video  = self::get_type_video( $video_embed );

			$output = '';
			if ( 'youtube' == $type_video ) {
				$video = self::get_youtube_link( $video_embed );

				if ( $parallax ) {
					if ( false !== strpos( $video, 'iframe' ) ) {
						$output .= '<div class="penci-jarallax" data-video-src="' . self::get_url_video_embed_code( $video ) . '"></div>';
					} else {
						$output .= '<div class="penci-jarallax" data-video-src="' . esc_url( $video ) . '"></div>';
					}
				} else {
					if ( wp_oembed_get( $video ) ) {
						$iframe_video = wp_oembed_get( $video, $args );

						$patterns[]     = '/src="(.*?)"/';
						$replacements[] = 'src="${1}&autoplay=1&mute=1"';
						$output         .= preg_replace( $patterns, $replacements, $iframe_video );

					} else {
						$output .= do_shortcode( $video );
					}
				}
			} elseif ( 'vimeo' == $type_video ) {
				if ( $parallax ) {
					if ( false !== strpos( $video_embed, 'iframe' ) ) {
						$output .= $video_embed;
					} else {
						$output .= '<div class="penci-jarallax" data-video-src="' . esc_url( $video_embed ) . '"></div>';
					}
				} else {
					if ( wp_oembed_get( $video_embed ) ) {

						$iframe_video = wp_oembed_get( $video_embed, $args );

						$patterns[]     = '/src="(.*?)"/';
						$replacements[] = 'src="${1}&autoplay=1&loop=1&mute=1"';

						$output .= preg_replace( $patterns, $replacements, $iframe_video );
					} else {
						$output .= do_shortcode( $video_embed );
					}
				}
			} elseif ( 'selfhosted' == $type_video ) {
				$output .= do_shortcode( '[video src="' . $video_embed . '"]' );
			} else {
				$output .= do_shortcode( $video_embed );
			}

			echo '<div class="post-image penci-video-format-' . $type_video . ( ! $move_title_bellow ? ' penci-move-title-above' : '' ) . '">';
			echo $output;

			if ( $show_title_inner && ! $move_title_bellow ) {
				echo $div_special_wrapper;
				echo '<div class="penci-fto-ct">';
				get_template_part( 'template-parts/single', 'breadcrumb' );
				get_template_part( 'inc/template-builder/single-elements/entry', 'header',$settings );
				echo '</div>';
				echo '</div>';
			}
			echo '</div>';

		}

		public static function get_youtube_link( $link ) {
			$return = $link;
			$values = '';

			if ( preg_match( '/youtube\.com\/watch\?v=([^\&\?\/]+)/', $link, $id ) ) {
				$values = $id[1];
			} else if ( preg_match( '/youtube\.com\/embed\/([^"]+)\?/', $link, $id ) ) {
				$values = $id[1];
			} else if ( preg_match( '/youtube\.com\/embed\/([^"]+)"/', $link, $id ) ) {
				$values = $id[1];
			} else if ( preg_match( '/youtube\.com\/v\/([^\&\?\/]+)/', $link, $id ) ) {
				$values = $id[1];
			} else if ( preg_match( '/youtu\.be\/([^\&\?\/]+)/', $link, $id ) ) {
				$values = $id[1];
			} else if ( preg_match( '/youtube\.com\/verify_age\?next_url=\/watch%3Fv%3D([^\&\?\/]+)/', $link, $id ) ) {
				$values = $id[1];
			} elseif ( preg_match( '%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $link, $id ) ) {
				$values = $id[1];
			}

			if ( $values ) {
				$return = 'https://www.youtube.com/watch?v=' . $values;
			}

			return $return;
		}

		function get_url_video_embed_code( $video ) {
			return preg_replace( "/\s*[a-zA-Z\/\/:\.]*youtube.com\/watch\?v=([a-zA-Z0-9\-_]+)([a-zA-Z0-9\/\*\-\_\?\&\;\%\=\.]*)/i", "<i" . "frame width=\"560\" height=\"315\" src=\"//www.youtube.com/embed/$1\" frameborder=\"0\" allowfullscreen></i" . "frame>", $video );
		}
	}
}
