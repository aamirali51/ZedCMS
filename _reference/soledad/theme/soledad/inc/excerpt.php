<?php
add_filter( 'penci_the_excerpt', 'wptexturize' );
add_filter( 'penci_the_excerpt', 'convert_smilies' );
add_filter( 'penci_the_excerpt', 'convert_chars' );
add_filter( 'penci_the_excerpt', 'wpautop' );
add_filter( 'penci_the_excerpt', 'shortcode_unautop' );

/**
 * Display the post excerpt.
 */
if ( ! function_exists( 'penci_the_excerpt' ) ):
	function penci_the_excerpt( $length = 25 ) {
		echo apply_filters( 'penci_the_excerpt', penci_get_the_excerpt( null, $length ) );
	}
endif;
/**
 * Retrieves the post excerpt.
 */
if ( ! function_exists( 'penci_get_the_excerpt' ) ):
	function penci_get_the_excerpt( $post = null, $length = 30 ) {
		$post = get_post( $post );
		if ( empty( $post ) ) {
			return '';
		}

		if ( post_password_required( $post ) ) {
			return __( 'There is no excerpt because this is a protected post.' );
		}

		return penci_trim_excerpt( $post->post_excerpt, $length );
	}
endif;

/**
 * Generates an excerpt from the content, if needed.
 *
 * The excerpt word amount will be 30 words and if the amount is greater than
 * that, then the string ' ...' will be appended to the excerpt. If the string
 * is less than 30 words, then the content will be returned as is.
 *
 * @param string $text Optional. The excerpt. If set to empty, an excerpt is generated.
 *
 * @return string The excerpt.
 */
if ( ! function_exists( 'penci_trim_excerpt' ) ):
	function penci_trim_excerpt( $text = '', $length = '' ) {
		$raw_excerpt = $text;

		if ( '' == $text ) {
			$text = get_the_content( '' );

			if ( class_exists( '\PenciDesign\SpeechCaster' ) ) {
				$SpeechCaster = \PenciDesign\SpeechCaster::get_instance();
				remove_filter( 'the_content', [ $SpeechCaster, 'add_player_to_content' ] );
			}

			$text = strip_shortcodes( $text );
			$text = function_exists( 'excerpt_remove_blocks' ) ? excerpt_remove_blocks( $text ) : $text;
			$text = apply_filters( 'the_content', $text );
			$text = str_replace( ']]>', ']]&gt;', $text );

			if ( ! $length || ! is_numeric( $length ) ) {
				$length = 30;
			}
		}

		if ( '' == $text ) {
			return '';
		}

		if ( $length ) {
			if ( get_theme_mod( 'penci_excerptcharac' ) ) {
				$text = strip_tags( $text );
				$text = html_entity_decode( $text );
				$text = mb_substr( $text, 0, $length, "utf-8" ) . '...';
			} else {
				$text = wp_trim_words( $text, $length, ' ...' );
			}
		}

		return $text;
	}
endif;

add_filter( 'strip_shortcodes_tagnames', 'penci_update_strip_shortcodes_tagnames' );
function penci_update_strip_shortcodes_tagnames( $tags ) {
	$tags_to_remove = array(
		'vc_gutenberg',
		'vc_row',
		'vc_row_inner',
		'vc_column',
		'vc_column_inner',
		'vc_column_text',
		'vc_section',
		'vc_icon',
		'vc_separator',
		'vc_zigzag',
		'vc_text_separator',
		'vc_message',
		'vc_hoverbox',
		'vc_facebook',
		'vc_tweetmeme',
		'vc_googleplus',
		'vc_pinterest',
		'vc_toggle',
		'vc_single_image',
		'vc_gallery',
		'vc_images_carousel',
		'vc_tta_tabs',
		'vc_tta_tour',
		'vc_tta_accordion',
		'vc_tta_pageable',
		'vc_tta_section',
		'vc_custom_heading',
		'vc_btn',
		'vc_cta',
		'vc_widget_sidebar',
		'vc_posts_slider',
		'vc_video',
		'vc_gmaps',
		'vc_raw_html',
		'vc_raw_js',
		'vc_flickr',
		'vc_progress_bar',
		'vc_pie',
		'vc_round_chart',
		'vc_line_chart',
		'vc_wp_search',
		'vc_wp_meta',
		'vc_wp_recentcomments',
		'vc_wp_calendar',
		'vc_wp_pages',
		'vc_wp_tagcloud',
		'vc_wp_custommenu',
		'vc_wp_text',
		'vc_wp_posts',
		'vc_wp_links',
		'vc_wp_categories',
		'vc_wp_archives',
		'vc_wp_rss',
		'vc_empty_space',
		'vc_basic_grid',
		'vc_media_grid',
		'vc_masonry_grid',
		'vc_masonry_media_grid',
		'vc_tabs',
		'vc_tour',
		'vc_tab',
		'vc_accordion',
		'vc_accordion_tab',
		'vc_button',
		'vc_button2',
		'vc_cta_button',
		'vc_cta_button2',
		'penci_about_me',
		'penci_column',
		'penci_column_inner',
		'penci_container',
		'penci_container_inner',
		'penci_count_down',
		'penci_counter_up',
		'penci_custom_sliders',
		'penci_facebook_page',
		'penci_fancy_heading',
		'penci_featured_slider',
		'penci_google_map',
		'penci_image_gallery',
		'penci_info_box',
		'penci_instagram',
		'penci_latest_tweets',
		'penci_login_form',
		'penci_mailchimp',
		'penci_open_hours',
		'penci_pintersest',
		'penci_popular_cat',
		'penci_posts_slider',
		'penci_pricing_table',
		'penci_progress_bar',
		'penci_recent_posts',
		'penci_social_counter',
		'penci_social_media',
		'penci_team_member',
		'penci_testimonails',
		'penci_text_block',
		'penci_video_playlist',
		'penci_weather',
	);

	return array_merge( $tags, $tags_to_remove );
}