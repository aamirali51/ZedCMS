<?php
$output = $penci_block_width = $el_class = $css_animation = $css = $responsive_spacing = '';

$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );

if ( ( ! $atts['videos_list'] && $atts['video_type'] == 'custom' ) || ( $atts['video_type'] == 'youtube_playlist' && ! $atts['yplaylist_id'] ) ) {
	return;
}

$class_to_filter = vc_shortcode_custom_css_class( $css, ' ' ) . $this->getExtraClass( $el_class ) . $this->getCSSAnimation( $css_animation );

$css_class = 'penci-block-vc penci-video_playlist';
$css_class .= ' pencisc-column-' . $atts['penci_block_width'];
$css_class .= ' ' . apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $class_to_filter, $this->settings['base'], $atts );
$block_id  = Penci_Vc_Helper::get_unique_id_block( 'video_playlist' );
?>
    <div id="<?php echo esc_attr( $block_id ); ?>" class="<?php echo esc_attr( $css_class ); ?>">
		<?php Penci_Vc_Helper::markup_block_title( $atts ); ?>
        <div class="penci-block_content">
			<?php
			if ( ! get_theme_mod( 'penci_youtube_api_key' ) && preg_match( "#(?<=v=)[a-zA-Z0-9-]+(?=&)|(?<=v\/)[^&\n]+(?=\?)|(?<=v=)[^&\n]+|(?<=youtu.be/)[^&\n]+#", $atts['videos_list'], $matches ) ) {
				echo '<strong>Youtube Api key</strong> is empty. Please go to Customize > General > Extra Options > YouTube API Key and enter an api key :)';
			}

			$cache_name = 'penci-shortcode-playlist-' . $atts['video_type'] . '-' . $atts['block_id'];
			$cache_key  = 'penci-shortcode-playlist-key' . $atts['video_type'] . '-' . $atts['block_id'];

			$videos          = explode( ',', $atts['videos_list'] );
			$videos_list     = get_transient( $cache_name );
			$videos_list_key = get_transient( $cache_key );
			$rand_video_list = rand( 1000, 100000 );


			if ( $atts['video_type'] == 'custom' && ( empty( $videos_list ) || $atts['videos_list'] != $videos_list_key ) ) {
				$videos_list = \Penci_Video_List::get_video_infos( $videos );
				set_transient( $cache_name, $videos_list, 18000 );
				set_transient( $cache_key, $atts['videos_list'], 18000 );
			}

			if ( $atts['video_type'] == 'youtube_playlist' && ( empty( $videos_list ) || $atts['yplaylist_id'] != $videos_list_key ) ) {
				$videos_list = \Penci_Video_List::get_playlist_videos_ids( $atts['yplaylist_id'], $atts['yplaylist_limit'] );
				set_transient( $cache_name, $videos_list, 18000 );
				set_transient( $cache_key, $atts['videos_list'], 18000 );
			}
			$videos_count = is_array( $videos_list ) ? count( (array) $videos_list ) : 0;

			if ( ! empty( $videos_list ) ): ?>
                <div class="penci-video-play">
					<?php foreach ( (array) $videos_list as $key => $video ): ?>
						<?php
						if ( $key > 0 ) {
							continue;
						}
						?>
                        <div class="fluid-width-video-wrapper">
                            <iframe class="penci-video-frame" id="video-<?php echo esc_attr( $rand_video_list ) ?>-1"
                                    src="<?php echo esc_attr( $video['id'] ) ?>" width="770" height="434"></iframe>
                        </div>
					<?php endforeach; ?>
                </div>
                <div class="penci-video-nav">
					<?php if ( ! empty( $atts['heading'] ) && 'video_list' == $atts['heading_title_style'] ): ?>
                        <div class="penci-playlist-title">
                            <div class="playlist-title-icon"><?php penci_fawesome_icon( 'fas fa-play' ); ?></span></div>
                            <h2>
								<?php
								echo( ! empty( $atts['heading_title_link'] ) ? '<a href=" ' . esc_url( $atts['heading_title_link'] ) . ' ">' : '<span>' );
								echo esc_html( $atts['heading'] );
								echo( ! empty( $atts['heading_title_link'] ) ? '</a >' : '</span>' );
								?>
                            </h2>
                            <span class="penci-videos-number">
								<span class="penci-video-playing">1</span> /
								<span class="penci-video-total"><?php echo( $videos_count ) ?></span>
								<?php
								if ( function_exists( 'penci_get_tran_setting' ) ) {
									echo penci_get_tran_setting( 'penci_social_video_text' );
								} else {
									esc_html_e( 'Videos', 'soledad' );
								}
								?>
							</span>
                        </div>
					<?php endif; ?>
					<?php
					$class_nav = ( ! empty( $atts['title'] ) && 'video_list' == $atts['block_title_style'] ) ? ' playlist-has-title' : '';
					$class_nav .= $videos_count > 3 ? ' penci-custom-scroll' : '';

					$direction = is_rtl() ? ' dir="rtl"' : '';
					?>
                    <div class="penci-video-playlist-nav<?php echo esc_attr( $class_nav ); ?>"<?php echo( $direction ); ?>>
						<?php
						$video_number = 0;
						foreach ( $videos_list as $video ):
							$video_number ++;
							?>
                            <a data-name="video-<?php echo esc_attr( $rand_video_list . '-' . $video_number ) ?>"
                               data-bgset="<?php echo esc_attr( $video['id'] ) ?>"
                               class="penci-video-playlist-item penci-video-playlist-item-<?php echo esc_attr( $video_number ); ?>">
							<span class="penci-media-obj">
								<span class="penci-mobj-img">
									<?php if ( 'yes' != $atts['hide_order_number'] ): ?>
                                        <span class="playlist-panel-item penci-video-number"><?php echo esc_attr( $video_number ) ?></span>
                                        <span class="playlist-panel-item penci-video-play-icon"><?php penci_fawesome_icon( 'fas fa-play' ); ?></span>
                                        <span class="playlist-panel-item penci-video-paused-icon"><?php penci_fawesome_icon( 'fas fa-pause' ); ?></span>
									<?php
									endif;


									$class_lazy = $data_src = '';
									$dis_lazy   = get_theme_mod( 'penci_disable_lazyload_layout' );
									if ( $dis_lazy ) {
										$class_lazy = ' penci-disable-lazy';
										$data_src   = 'style="background-image: url(' . esc_url( $video['thumb'] ) . ');"';
									} else {
										$class_lazy = ' penci-lazy';
										$data_src   = 'data-bgset="' . esc_url( $video['thumb'] ) . '"';
									}

									printf( '<span class="penci-image-holder penci-video-thumbnail%s" %s><span class="screen-reader-text">%s</span></span>', $class_lazy, $data_src, esc_html__( 'Thumbnail youtube', 'soledad' ) );
									?>
								</span>
								<span class="penci-mobj-body">
									<span class="penci-video-title"
                                          title="<?php echo esc_attr( $video['title'] ); ?>"><?php echo wp_trim_words( $video['title'], $atts['video_title_length'], '...' ); ?></span>
									<?php if ( 'yes' != $atts['hide_duration'] ): ?>
                                        <span class="penci-video-duration"><?php echo esc_attr( $video['duration'] ) ?></span>
									<?php endif; ?>
								</span>
							</span>
                            </a>
						<?php endforeach;
						?>
                    </div>
                </div>
			<?php endif; ?>
        </div>
    </div>
<?php

$id_block_css = '#' . $block_id;
$id_block_css2 = 'body:not(.pcdm-enable) #' . $block_id;
$css_custom   = Penci_Vc_Helper::get_heading_block_css( $id_block_css, $atts );

if ( 'video_list' == $atts['heading_title_style'] ) {
	$css_custom .= Penci_Vc_Helper::vc_google_fonts_parse_attributes( array(
		'font_size'  => $atts['btitle_fsize'],
		'font_style' => $atts['use_btitle_typo'] ? $atts['btitle_typo'] : '',
		'template'   => $id_block_css . ' .penci-playlist-title h2{ %s }',
	) );

	if ( $atts['btitle_bgcolor'] ) {
		$css_custom .= $id_block_css2 . '.penci-video_playlist .penci-playlist-title{ background-color:' . esc_attr( $atts['btitle_bgcolor'] ) . ';}';
	}

	if ( $atts['block_title_color'] ) {
		$css_custom .= $id_block_css2 . '.penci-video_playlist .penci-playlist-title{ color:' . esc_attr( $atts['block_title_color'] ) . ';}';
	}

	if ( $atts['block_title_hcolor'] ) {
		$css_custom .= $id_block_css2 . '.penci-video_playlist .penci-playlist-title a:hover{ color:' . esc_attr( $atts['block_title_hcolor'] ) . ';}';
	}
}

if ( $atts['list_video_bgcolor'] ) {
	$css_custom .= $id_block_css2 . '.penci-video_playlist .penci-video-nav{ background-color:' . esc_attr( $atts['list_video_bgcolor'] ) . ';}';
}
if ( $atts['video_title_color'] ) {
	$css_custom .= $id_block_css2 . ' .penci-video-playlist-item .penci-video-title{ color:' . esc_attr( $atts['video_title_color'] ) . ';}';
}
if ( $atts['video_title_hover_color'] ) {
	$css_custom .= $id_block_css2 . ' .penci-video-playlist-item .penci-video-title:hover{ color:' . esc_attr( $atts['video_title_hover_color'] ) . ';}';
}
if ( $atts['duration_color'] ) {
	$css_custom .= $id_block_css2 . ' .penci-video-playlist-item .penci-video-duration{ color:' . esc_attr( $atts['duration_color'] ) . ';}';
}
if ( $atts['order_number_color'] ) {
	$css_custom .= $id_block_css2 . '.penci-video_playlist .penci-video-nav .playlist-panel-item{ color:' . esc_attr( $atts['order_number_color'] ) . ';}';
}
if ( $atts['order_number_bgcolor'] ) {
	$css_custom .= $id_block_css2 . '.penci-video_playlist .penci-video-nav .playlist-panel-item{ background-color:' . esc_attr( $atts['order_number_bgcolor'] ) . ';}';
}
if ( $atts['item_video_border_color'] ) {
	$css_custom .= $id_block_css2 . '.penci-video_playlist .penci-video-nav .penci-video-playlist-item{ border-color:' . esc_attr( $atts['item_video_border_color'] ) . ';}';
}
if ( $atts['item_video_bg_hcolor'] ) {
	$css_custom .= $id_block_css2 . '.penci-video_playlist .penci-video-nav .penci-video-playlist-item:hover{ background-color:' . esc_attr( $atts['item_video_bg_hcolor'] ) . ';}';
}
if ( $atts['scrollbar_bg_hcolor'] ) {
	$css_custom .= $id_block_css2 . '.penci-video_playlist .penci-custom-scroll::-webkit-scrollbar-thumb{ background-color:' . esc_attr( $atts['scrollbar_bg_hcolor'] ) . ';}';
}

if ( $responsive_spacing ) {
	$css_custom .= penci_extract_spacing_style( $id_block_css, $responsive_spacing );
}

if ( $css_custom ) {
	echo '<style>';
	echo $css_custom;
	echo '</style>';
}
