<?php
$responsive_spacing = '';
$atts               = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );

$block_id = Penci_Vc_Helper::get_unique_id_block( 'news-ticker' );

$query_args = penci_build_args_query( $atts['build_query'] );


$toptext = isset( $atts['tpost_text'] ) ? $atts['tpost_text'] : 'Top Posts';
?>
    <div class="penci-enews-ticker penci-topbar-trending" id="<?php echo esc_attr( $block_id ) ?>">
		<?php if ( $toptext ) {
			$toptext_style = $atts['headline_style'] ? $atts['headline_style'] : 'nticker-style-1';
			?>
            <span class="headline-title <?php echo $toptext_style; ?>"><?php echo $toptext; ?></span>
		<?php }

		$news             = new \WP_Query( $query_args );
		if ( $news->have_posts() ):
			$auto_play = $atts['autoplay'] ? 'yes' == $atts['autoplay'] : 'true';
			$auto_time    = $atts['autotime'] ? $atts['autotime'] : '3000';
			$auto_speed   = $atts['autospeed'] ? $atts['autospeed'] : '300';
			$title_length = $atts['title_length'] ? $atts['title_length'] : 8;
			$animation    = $atts['headline_anim'] ? $atts['headline_anim'] : 'slideInUp';
			$navs_buttons = isset( $atts['navs_buttons'] ) ? 'yes' == $atts['navs_buttons'] : false;
			?>
            <span class="penci-trending-nav<?php if ( $navs_buttons ): echo ' penci-navs-buttons'; endif; ?>">
				<a class="penci-slider-prev" href="#" aria-label="Previous"><?php penci_fawesome_icon( 'fas fa-angle-left' ); ?></a>
				<a class="penci-slider-next" href="#" aria-label="Next"><?php penci_fawesome_icon( 'fas fa-angle-right' ); ?></a>
			</span>
            <div class="penci-owl-carousel swiper penci-owl-carousel-slider penci-headline-posts"
                 data-auto="<?php echo $auto_play; ?>" data-nav="false" data-autotime="<?php echo $auto_time; ?>"
                 data-speed="<?php echo $auto_speed; ?>" data-anim="<?php echo $animation; ?>">
                 <div class="swiper-wrapper">
				<?php while ( $news->have_posts() ): $news->the_post(); ?>
                    <div class="swiper-slide">
                        <a class="penci-topbar-post-title"
                           href="<?php the_permalink(); ?>"><?php echo sanitize_text_field( wp_trim_words( get_the_title(), $title_length, '...' ) ); ?></a>
                    </div>
				<?php endwhile;
				wp_reset_postdata(); ?>
				</div>
            </div>
		<?php endif; /* End check if no posts */ ?>
    </div>
<?php
$block_id_css = '#' . $block_id;
$block_id_css2 = 'body:not(.pcdm-enable) #' . $block_id;
$css_custom   = '';

// CSS
if ( 'yes' == $atts['navs_buttons'] ) {
	$css_custom .= $block_id_css . ' .penci-trending-nav a{ height: 24px; line-height: 24px; top: 4px; background: #313131; color: #fff; float: left; }';
	$css_custom .= $block_id_css . ' .penci-trending-nav a:first-child{ margin-right: 4px; }';
}
if ( 'yes' == $atts['move_navs'] ) {
	$css_custom .= $block_id_css . '.penci-topbar-trending .penci-trending-nav{ float: right; }';
	$css_custom .= $block_id_css . ' .headline-title{ margin-right: 10px; }';
	$css_custom .= $block_id_css . ' .headline-title.nticker-style-2{ margin-right: 18px; }';
	$css_custom .= $block_id_css . ' .headline-title.nticker-style-4{ margin-right: 19px; }';
	$css_custom .= 'body.rtl ' . $block_id_css . '.penci-topbar-trending .penci-trending-nav{ float: left; }';
	$css_custom .= 'body.rtl ' . $block_id_css . ' .headline-title{ margin-right: 0; margin-left: 10px; }';
	$css_custom .= 'body.rtl ' . $block_id_css . ' .headline-title.nticker-style-2{ margin-left: 18px; margin-right:0; }';
	$css_custom .= 'body.rtl ' . $block_id_css . ' .headline-title.nticker-style-4{ margin-left: 19px; margin-right:0; }';
}
if ( 'yes' == $atts['headline_upper'] ) {
	$css_custom .= $block_id_css . ' .headline-title{ text-transform: none; }';
}
if ( 'yes' == $atts['titles_upper'] ) {
	$css_custom .= $block_id_css . ' a.penci-topbar-post-title{ text-transform: none; }';
}
if ( ! empty( $atts['tpost_size'] ) ) {
	$css_custom .= penci_extract_md_responsive_fsize( $block_id_css . ' .headline-title{ font-size:{{VALUE}}px; }', $atts['tpost_size'] );
}
if ( ! empty( $atts['navs_size'] ) ) {
	$css_custom .= penci_extract_md_responsive_fsize( $block_id_css . ' .penci-trending-nav a{ font-size:{{VALUE}}px; }', $atts['navs_size'] );
}
if ( ! empty( $atts['ptitles_size'] ) ) {
	$css_custom .= penci_extract_md_responsive_fsize( $block_id_css . ' a.penci-topbar-post-title{ font-size:{{VALUE}}px; }', $atts['ptitles_size'] );
}
if ( ! empty( $atts['border_color'] ) ) {
	$css_custom .= $block_id_css . '.penci-topbar-trending{ border: 1px solid ' . esc_attr( $atts['border_color'] ) . '; }';
}
if ( ! empty( $atts['bg_color'] ) ) {
	$css_custom .= $block_id_css . '.penci-topbar-trending,' . $block_id_css . ' .penci-owl-carousel .owl-item{ background-color: ' . esc_attr( $atts['bg_color'] ) . '; }';
	$css_custom .= $block_id_css . ' .headline-title.nticker-style-3:after{ border-color: ' . esc_attr( $atts['bg_color'] ) . '; }';
}
if ( ! empty( $atts['tpost_bg'] ) ) {
	$css_custom .= $block_id_css . ' .headline-title{ background-color: ' . esc_attr( $atts['tpost_bg'] ) . '; }';
	$css_custom .= $block_id_css . ' .headline-title.nticker-style-2:after,' . $block_id_css . ' .headline-title.nticker-style-4:after{ border-color: ' . esc_attr( $atts['tpost_bg'] ) . '; }';
}
if ( ! empty( $atts['tpost_color'] ) ) {
	$css_custom .= $block_id_css2 . ' .headline-title{ color: ' . esc_attr( $atts['tpost_color'] ) . '; }';
}
if ( ! empty( $atts['navs_color'] ) ) {
	$css_custom .= $block_id_css2 . ' .penci-trending-nav a{ color: ' . esc_attr( $atts['navs_color'] ) . '; }';
}
if ( ! empty( $atts['navs_hcolor'] ) ) {
	$css_custom .= $block_id_css2 . ' .penci-trending-nav a:hover{ color: ' . esc_attr( $atts['navs_hcolor'] ) . '; }';
}
if ( ! empty( $atts['navs_bg'] ) ) {
	$css_custom .= $block_id_css2 . ' .penci-trending-nav a{ background-color: ' . esc_attr( $atts['navs_bg'] ) . '; }';
}
if ( ! empty( $atts['navs_hbg'] ) ) {
	$css_custom .= $block_id_css2 . ' .penci-trending-nav a:hover{ background-color: ' . esc_attr( $atts['navs_hbg'] ) . '; }';
}
if ( ! empty( $atts['ptitle_color'] ) ) {
	$css_custom .= $block_id_css2 . ' a.penci-topbar-post-title{ color: ' . esc_attr( $atts['ptitle_color'] ) . '; }';
}
if ( ! empty( $atts['ptitle_hcolor'] ) ) {
	$css_custom .= $block_id_css2 . ' a.penci-topbar-post-title:hover{ color: ' . esc_attr( $atts['ptitle_hcolor'] ) . '; }';
}

if ( $auto_speed && '300' != $auto_speed ) {
	$auto_speed_num = (int) $auto_speed / 1000;
	$css_custom     .= $block_id_css . '.penci-topbar-trending .animated.slideOutUp,' . $block_id_css . '.penci-topbar-trending .animated.slideInUp,' . $block_id_css . '.penci-topbar-trending .animated.TickerslideOutRight,' . $block_id_css . '.penci-topbar-trending .animated.TickerslideInRight,' . $block_id_css . '.penci-topbar-trending .animated.fadeOut,' . $block_id_css . '.penci-topbar-trending .animated.fadeIn{ -webkit-animation-duration : ' . $auto_speed_num . 's; animation-duration : ' . $auto_speed_num . 's; }';
}

if ( $responsive_spacing ) {
	$css_custom .= penci_extract_spacing_style( $block_id_css, $responsive_spacing );
}

if ( $css_custom ) {
	echo '<style>';
	echo $css_custom;
	echo '</style>';
}
