<?php

class Penci_Featured_Slider {

	public static function is_slider_style( $style ) {
		return in_array( $style, [
			'style-1',
			'style-2',
			'style-3',
			'style-4',
			'style-5',
			'style-6',
			'style-7',
			'style-8',
			'style-9',
			'style-10',
			'style-11',
			'style-12',
			'style-13',
			'style-14',
			'style-15',
			'style-16',
			'style-17',
			'style-18',
			'style-19',
			'style-20',
			'style-21',
			'style-22',
			'style-23',
			'style-24',
			'style-25',
			'style-26',
			'style-27',
			'style-28',
			'style-29',
			'style-30',
			'style-31',
			'style-32',
			'style-33',
			'style-34',
			'style-35',
			'style-36',
			'style-37',
			'style-38',
			'style-40',
			'video'
		] );
	}

	public static function render_featured_slider( $attrs = array() ) {

		$default_attrs = array(
			'style'                  => get_theme_mod( 'penci_featured_slider_style', 'style-1' ),
			'rev_shortcode'          => get_theme_mod( 'penci_feature_rev_sc' ),
			'auto_play'              => get_theme_mod( 'penci_featured_autoplay' ),
			'auto_time'              => get_theme_mod( 'penci_featured_slider_auto_time' ),
			'auto_speed'             => get_theme_mod( 'penci_featured_slider_auto_speed' ),
			'loop'                   => get_theme_mod( 'penci_featured_loop' ),
			'animation_in'           => get_theme_mod( 'penci_featured_slider_ain' ),
			'animation_out'          => get_theme_mod( 'penci_featured_slider_aout' ),
			'dots'                   => get_theme_mod( 'penci_disable_dots_penci_slider' ),
			'next_prev'              => get_theme_mod( 'penci_enable_next_prev_penci_slider' ),
			'slidespg'               => get_theme_mod( 'penci_featured_penci_slider_slidespg' ),
			'slideTo'                => get_theme_mod( 'penci_featured_penci_slider_slideTo' ),
			'flat_overlay'           => get_theme_mod( 'penci_enable_flat_overlay' ),
			'query_args'             => array(),
			'is_builder'             => false,
			'post_thumb_size'        => 'penci-slider-thumb',
			'post_thumb_size_mobile' => 'penci-masonry-thumb',
			'center_box'             => get_theme_mod( 'penci_featured_center_box' ),
			'hide_categories'        => get_theme_mod( 'penci_featured_hide_categories' ),
			'meta_date_hide'         => get_theme_mod( 'penci_featured_meta_date' ),
			'hide_meta_comment'      => get_theme_mod( 'penci_featured_meta_comment' ),
			'show_viewscount'        => get_theme_mod( 'penci_featured_viewscount' ),
			'show_meta_author'       => get_theme_mod( 'penci_featured_meta_author' ),
			'slider_title_length'    => get_theme_mod( 'penci_slider_title_length' ),
			'cspost_enable'          => get_theme_mod( 'penci_cspost_enable' ),
			'carousel_slider_effect' => get_theme_mod( 'penci_carousel_slider_effect' ),
			'single_slider_effect'   => get_theme_mod( 'penci_single_slider_effect' ),
		);

		$attrs = wp_parse_args( $attrs, $default_attrs );

		$slider_style = $attrs['style'];

		if ( in_array( $slider_style, [ 'style-33', 'style-34' ] ) && $attrs['rev_shortcode'] ) {
			$rev_shortcode = $attrs['rev_shortcode'];
			echo '<div class="featured-area featured-' . $slider_style . '">';
			if ( $slider_style == 'style-34' ) {
				echo '<div class="container">';
			}
			echo do_shortcode( $rev_shortcode );
			if ( $slider_style == 'style-34' ) {
				echo '</div>';
			}
			echo '</div>';
		} else {
			$style_mappings = [
				'style-3'  => 'style-1',
				'style-5'  => 'style-4',
				'style-7'  => 'style-8',
				'style-9'  => 'style-10',
				'style-11' => 'style-12',
				'style-13' => 'style-14',
				'style-15' => 'style-16',
				'style-17' => 'style-18',
				'style-29' => 'style-30',
				'style-35' => 'style-36'
			];

			if ( get_theme_mod( 'penci_body_boxed_layout' ) && ! get_theme_mod( 'penci_vertical_nav_show' ) && ! $attrs['is_builder'] ) {
				$slider_style = $style_mappings[ $slider_style ] ?? $slider_style;
			}

			$combined_classes = [
				'style-5'  => 'style-4 style-5',
				'style-30' => 'style-29 style-30',
				'style-36' => 'style-35 style-36'
			];

			$slider_class = $combined_classes[ $slider_style ] ?? $slider_style;
			$data_auto    = $attrs['auto_play'] ? 'true' : 'false';
			$data_loop    = $attrs['loop'] ? 'false' : 'true';
			$auto_time    = is_numeric( $attrs['auto_time'] ) ? $attrs['auto_time'] : '4000';
			$auto_speed   = is_numeric( $attrs['auto_speed'] ) ? $attrs['auto_speed'] : '600';

			$data_res = '';
			switch ( $slider_style ) {
				case 'style-7':
				case 'style-8':
					$data_res = ' data-item="4" data-desktop="4" data-tablet="2" data-tabsmall="1"';
					break;
				case 'style-9':
				case 'style-10':
				case 'style-43':
					$data_res = ' data-item="3" data-desktop="3" data-tablet="2" data-tabsmall="1"';
					break;
				case 'style-11':
				case 'style-12':
					$data_res = ' data-item="2" data-desktop="2" data-tablet="2" data-tabsmall="1"';
					break;
				case 'style-31':
				case 'style-32':
				case 'style-35':
				case 'style-36':
				case 'style-37':
					$data_next_prev = $attrs['next_prev'] ? 'true' : 'false';
					$data_dots      = $attrs['dots'] ? 'false' : 'true';
					$data_res       = ' data-dots="' . $data_dots . '" data-nav="' . $data_next_prev . '"';
					break;
			}

			$container_styles = [
				'style-1',
				'style-4',
				'style-6',
				'style-8',
				'style-10',
				'style-12',
				'style-14',
				'style-16',
				'style-18',
				'style-19',
				'style-20',
				'style-21',
				'style-22',
				'style-23',
				'style-24',
				'style-25',
				'style-26',
				'style-27',
				'style-30',
				'style-32',
				'style-36',
				'style-37',
				'style-41',
				'style-42',
				'style-43',
				'style-44',
				'style-45',
			];

			$open_container = $close_container = '';
			if ( in_array( $slider_style, $container_styles ) ) {
				$open_container  = '<div class="container">';
				$close_container = '</div>';
			}

			$output_anim = '';
			if ( $attrs['carousel_slider_effect'] ) {
				$output_anim .= ' data-ceffect="' . $attrs['carousel_slider_effect'] . '"';
			}
			if ( $attrs['single_slider_effect'] ) {
				$output_anim .= ' data-seffect="' . $attrs['single_slider_effect'] . '"';
			}

			$slider_lib = 'penci-owl-featured-area';
			if ( $attrs['flat_overlay'] && in_array( $slider_style, [
					'style-6',
					'style-7',
					'style-8',
					'style-9',
					'style-10',
					'style-11',
					'style-12',
					'style-13',
					'style-14',
					'style-15',
					'style-16',
					'style-17',
					'style-18',
					'style-19',
					'style-20',
					'style-21',
					'style-22',
					'style-23',
					'style-24',
					'style-25',
					'style-26',
					'style-27',
					'style-28'
				] ) ) {
				$slider_class .= ' penci-flat-overlay';
			}

			$slider_lib .= ' elsl-' . $slider_style;
			$swiper     = true;
			if ( $slider_style == 'style-40' ) {
				wp_enqueue_script( 'ff40' );
				wp_enqueue_script( 'gsap' );
				$slider_lib .= ' no-df-swiper';
				$swiper     = false;
			}

			if ( $slider_style == 'style-41' || $slider_style == 'style-42' || $slider_style == 'style-44' ) {
				$slider_lib .= ' no-df-swiper';
				$swiper     = false;
			}

			$new_attr = '';
			if ( $attrs['slidespg'] ) {
				$new_attr = ' data-slidespg="' . $attrs['slidespg'] . '"';
			}
			if ( $attrs['slideTo'] ) {
				$new_attr = ' data-slideTo="' . $attrs['slideTo'] . '"';
			}

			if ( $swiper ) {
				$slider_lib .= ' swiper penci-owl-carousel';
			}

			echo '<div class="featuredsl-customizer featured-area featured-' . $slider_class . '">' . $open_container;
			if ( $slider_style == 'style-37' ) {
				echo '<div class="penci-featured-items-left">';
			}
			echo '<div class="' . $slider_lib . '"' . $data_res . $new_attr . ' data-style="' . $slider_style . '" data-auto="' . $data_auto . '" data-autotime="' . $auto_time . '" data-speed="' . $auto_speed . '" data-loop="' . $data_loop . '"' . $output_anim . '>';
			if ( $swiper ) {
				echo '<div class="penci-owl-nav"><div class="owl-prev"><i class="penciicon-left-chevron"></i></div><div class="owl-next"><i class="penciicon-right-chevron"></i></div></div>';
				echo '<div class="swiper-wrapper">';
			}

			get_template_part( 'inc/featured_slider/' . $slider_style );

			if ( $swiper && $slider_style != 'style-37' ) {
				echo '</div>';
			}

			echo '</div>';
			echo $close_container . '</div>';
		}
	}

}
