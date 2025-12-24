<?php
if ( ! function_exists( 'penci_getTweets' ) ) {
	return;
}
$output = $penci_block_width = $el_class = $css_animation = $css = '';
$date   = $auto = $reply = $retweet = $favorite = $align = $style = $responsive_spacing = '';

$tweets_text_color = $tweets_text_size = $tweets_date_color = $tweets_date_size = '';
$tweets_link_color = $tweets_link_size = $tweets_dot_color = $tweets_dot_hcolor = '';

$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );

$tweets = penci_getTweets( 5 );
if ( empty( $tweets ) ) {
	return;
}

$class_to_filter = vc_shortcode_custom_css_class( $css, ' ' ) . $this->getExtraClass( $el_class ) . $this->getCSSAnimation( $css_animation );

$css_class = 'penci-block-vc penci-latest-tweets-widget';
$css_class .= ' ' . apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $class_to_filter, $this->settings['base'], $atts );
$block_id  = Penci_Vc_Helper::get_unique_id_block( 'latest_tweets' );
$classes   = 'slider' == $style ? 'swiper penci-owl-carousel penci-owl-carousel-slider penci-tweets-slider' : 'penci-tweets-lists';
?>
    <div id="<?php echo esc_attr( $block_id ); ?>" class="<?php echo esc_attr( $css_class ); ?>">
		<?php Penci_Vc_Helper::markup_block_title( $atts ); ?>
        <div class="penci-block_content">
			<?php
			if ( isset( $tweets['error'] ) ) {
				echo 'Missing Twitter API Keys - please connect your Twitter Account by go to admin > Soledad > Connect Twitter';
			} else {
				$rtl_align = is_rtl() ? 'pc_alignright' : 'pc_alignleft';
				$align     = $style == 'slider' ? $align : $rtl_align;
				?>
                <div class="penci-tweets-widget-content <?php echo $align; ?>">
					<?php if ( $style == 'slider' ): ?>
                        <span class="icon-tweets"><?php penci_fawesome_icon( 'penciicon-x-twitter' ); ?></span>
					<?php endif; ?>
                    <div class="<?php echo esc_attr( $classes ); ?>" data-dots="true"
                         data-nav="false" data-auto="<?php if ( 'yes' == $auto ) {
						echo 'false';
					} else {
						echo 'true';
					} ?>">
						<?php 
						$item_class = 'normal-item';
						if ( 'slider' == $style ) {
							echo '<div class="swiper-wrapper">';
							$item_class = 'swiper-slide';
						}
						foreach ( $tweets as $tweet ):
							$date_array = explode( ' ', $tweet['created_at'] );
							$tweet_id = $tweet['id_str'];
							$tweet_text = $tweet['text'];
							$urls = $tweet['entities']['urls'];

							if ( isset( $urls ) ) {
								foreach ( $urls as $ul ) {
									$url = $ul['url'];
									if ( isset( $url ) ):
										$tweet_text = str_replace( $url, '<a href="' . $url . '" target="_blank">' . $url . '</a>', $tweet_text );
									endif;
								}
							}
							?>
                            <div class="penci-tweet <?php echo $item_class;?>">

								<?php if ( $style == 'list' ):
									$reply = '<i class="fa fa-reply" aria-hidden="true"></i>';
									$retweet = '<i class="fa fa-retweet" aria-hidden="true"></i>';
									$favorite = '<i class="fa fa-thumbs-up" aria-hidden="true"></i>';
									?>

                                    <div class="tweet-list-top">

										<?php if ( $date_array[1] && $date_array[2] && $date_array[5] && ! 'yes' == $date ): ?>
                                            <span class="tweet-date"><?php echo $date_array[2] . '-' . $date_array[1] . '-' . $date_array[5]; ?></span>
										<?php endif; ?>

                                    </div>

								<?php endif; ?>

                                <div class="tweet-text">
									<?php echo $tweet_text; ?>
                                </div>
								<?php if ( $style == 'slider' && $date_array[1] && $date_array[2] && $date_array[5] && ! 'yes' == $date ): ?>
                                    <p class="tweet-date"><?php echo $date_array[2] . '-' . $date_array[1] . '-' . $date_array[5]; ?></p>
								<?php endif; ?>
                                <div class="tweet-intents">
                                    <div class="tweet-intents-inner">
                                        <span><a target="_blank" class="reply"
                                                 href="https://x.com/intent/tweet?in_reply_to=<?php echo sanitize_text_field( $tweet_id ); ?>"><?php echo do_shortcode( $reply ); ?></a></span>
                                        <span><a target="_blank" class="retweet"
                                                 href="https://x.com/intent/retweet?tweet_id=<?php echo sanitize_text_field( $tweet_id ); ?>"><?php echo do_shortcode( $retweet ); ?></a></span>
                                        <span><a target="_blank" class="favorite"
                                                 href="https://x.com/intent/favorite?tweet_id=<?php echo sanitize_text_field( $tweet_id ); ?>"><?php echo do_shortcode( $favorite ); ?></a></span>
                                    </div>
                                </div>
                            </div>
						<?php endforeach;
						if ( 'slider' == $style ) {
							echo '</div>';
						}
						?>
                    </div>
                </div>

				<?php
			}
			?>
        </div>
    </div>
<?php

$id_latest_tweets = '#' . $block_id;
$id_latest_tweets2 = 'body:not(.pcdm-enable) #' . $block_id;
$css_custom       = Penci_Vc_Helper::get_heading_block_css( $id_latest_tweets, $atts );

if ( $tweets_text_color ) {
	$css_custom .= $id_latest_tweets . ' .tweet-text{ color:' . esc_attr( $tweets_text_color ) . ' }';
}
if ( $tweets_text_size ) {
	$css_custom .= $id_latest_tweets . ' .tweet-text{ font-size:' . esc_attr( $tweets_text_size ) . ' }';
}
if ( $tweets_date_color ) {
	$css_custom .= $id_latest_tweets2 . ' .tweet-date{ color:' . esc_attr( $tweets_date_color ) . ' }';
}
if ( $tweets_text_size ) {
	$css_custom .= $id_latest_tweets . ' .tweet-date{ font-size:' . esc_attr( $tweets_text_size ) . ' }';
}
if ( $tweets_link_color ) {
	$css_custom .= $id_latest_tweets2 . ' .penci-tweets-widget-content .tweet-intents-inner:after,';
	$css_custom .= $id_latest_tweets2 . ' .penci-tweets-widget-content .tweet-intents-inner:before{background-color:' . esc_attr( $tweets_link_color ) . '}';

	$css_custom .= $id_latest_tweets2 . ' .tweet-text a,';
	$css_custom .= $id_latest_tweets2 . ' .penci-tweets-widget-content .icon-tweets,';
	$css_custom .= $id_latest_tweets2 . ' .penci-tweets-widget-content .tweet-intents span:after,';
	$css_custom .= $id_latest_tweets2 . ' .penci-tweets-widget-content .tweet-intents a{ color:' . esc_attr( $tweets_link_color ) . ' }';
}
if ( $tweets_link_size ) {
	$css_custom .= penci_extract_md_responsive_fsize( $id_latest_tweets . ' .penci-tweets-widget-content .tweet-intents a{ font-size:{{VALUE}}px }', $tweets_text_size );
}
if ( $tweets_dot_color ) {
	$css_custom .= $id_latest_tweets2 . ' .penci-owl-carousel.penci-tweets-slider .penci-owl-dots .penci-owl-dot span{ border-color:' . esc_attr( $tweets_dot_color ) . ';background-color:' . esc_attr( $tweets_dot_color ) . ' }';
}
if ( $tweets_dot_hcolor ) {
	$css_custom .= $id_latest_tweets2 . ' .penci-owl-carousel.penci-tweets-slider .penci-owl-dots .penci-owl-dot:hover span,';
	$css_custom .= $id_latest_tweets2 . ' .penci-owl-carousel.penci-tweets-slider .penci-owl-dots .penci-owl-dot.active span{ border-color:' . esc_attr( $tweets_dot_hcolor ) . ';background-color:' . esc_attr( $tweets_dot_hcolor ) . '}';
}
if ( $responsive_spacing ) {
	$css_custom .= penci_extract_spacing_style( $id_latest_tweets, $responsive_spacing );
}

if ( $css_custom ) {
	echo '<style>';
	echo $css_custom;
	echo '</style>';
}
