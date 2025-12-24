<?php
$output              = $penci_block_width = $el_class = $css_animation = $css = '';
$heading_title_style = $heading = $heading_title_link = $heading_title_align = '';
$build_query         = $penci_style = $penci_size = $penci_img_ratio = $responsive_spacing = '';
$hide_pdate          = $dis_lazyload = '';

$dis_lazyload = 'yes' == $dis_lazyload;

$ptitle_color  = $ptitle_hcolor = $ptitle_fsize = $use_ptitle_typo = $ptitle_typo = '';
$pmeta_color   = $pmeta_hcolor = $pmeta_fsize = $use_pmeta_typo = $pmeta_typo = '';
$_title_length = 10;

$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );

$class_to_filter = vc_shortcode_custom_css_class( $css, ' ' ) . $this->getExtraClass( $el_class ) . $this->getCSSAnimation( $css_animation );

$css_class = 'penci-block-vc penci_post-slider-sc';
$css_class .= ' ' . apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $class_to_filter, $this->settings['base'], $atts );
$block_id  = Penci_Vc_Helper::get_unique_id_block( 'post_slider' );

$thumbsize = penci_featured_images_size();
if ( 'horizontal' == $penci_size ) {
	$thumbsize = 'penci-thumb';
} else if ( 'square' == $penci_size ) {
	$thumbsize = 'penci-thumb-square';
} else if ( 'vertical' == $penci_size ) {
	$thumbsize = 'penci-thumb-vertical';
}

$query_args = penci_build_args_query( $build_query );
$loop       = new WP_Query( $query_args );

if ( ! $loop->have_posts() ) {
	return;
}

$rand = rand( 100, 10000 );

if ( get_theme_mod( 'penci_disable_lazyload_layout' ) ) {
	$dis_lazyload = false;
}

$style    = $penci_style ? $penci_style : 'style-1';
$dataauto = 'false';
if ( 'yes' == $autoplay ) {
	$dataauto = 'true';
}
?>
    <div id="<?php echo esc_attr( $block_id ); ?>" class="<?php echo esc_attr( $css_class ); ?>">
		<?php Penci_Vc_Helper::markup_block_title( $atts ); ?>
        <div class="penci-block_content">
            <div id="penci-postslidewg-<?php echo sanitize_text_field( $rand ); ?>"
                 class="penci-owl-carousel swiper penci-owl-carousel-slider penci-widget-slider penci-post-slider-<?php echo $style; ?>"
                 data-lazy="true" data-auto="<?php echo $dataauto; ?>">
                 <div class="swiper-wrapper">
				<?php while ( $loop->have_posts() ) : $loop->the_post(); ?>
                    <div class="penci-slide-widget swiper-slide">
                        <div class="penci-slide-content">
							<?php if ( $style != 'style-3' ) { ?>
								<?php if ( ! $dis_lazyload ) { ?>
                                    <span class="penci-image-holder <?php echo penci_classes_slider_lazy(); ?>"
                                          title="<?php echo wp_strip_all_tags( get_the_title() ); ?>"></span>
								<?php } else { ?>
                                    <span class="penci-image-holder"
                                          style="background-image: url('<?php echo penci_get_featured_image_size( get_the_ID(), $thumbsize ); ?>');"
                                          title="<?php echo wp_strip_all_tags( get_the_title() ); ?>"></span>
								<?php } ?>
                                <a href="<?php the_permalink() ?>" class="penci-widget-slider-overlay"
                                   title="<?php the_title(); ?>"></a>
							<?php } else { ?>

                                    <a <?php echo penci_layout_bg( penci_image_srcset( get_the_ID(), $thumbsize ), ! $dis_lazyload ); ?> href="<?php the_permalink() ?>" class="<?php echo penci_layout_bg_class(! $dis_lazyload);?> penci-image-holder"
                                       title="<?php echo wp_strip_all_tags( get_the_title() ); ?>">
	                                    <?php echo penci_layout_img( penci_image_srcset( get_the_ID(), $thumbsize ), get_the_title(), ! $dis_lazyload ); ?>
                                    </a>

							<?php } ?>
                            <div class="penci-widget-slide-detail">
                                <h4>
                                    <a href="<?php the_permalink() ?>" rel="bookmark"
                                       title="<?php the_title(); ?>"><?php penci_trim_post_title( get_the_ID(), $_title_length ); ?></a>
                                </h4>
								<?php if ( ! 'yes' == $hide_pdate ) : ?>
									<?php
									$date_format = get_option( 'date_format' );
									?>
									<?php if ( ! get_theme_mod( 'penci_show_modified_date' ) ) { ?>
                                        <span class="slide-item-date"><?php the_time( $date_format ); ?></span>
									<?php } else { ?>
                                        <span class="slide-item-date"><?php echo get_the_modified_date( $date_format ); ?></span>
									<?php } ?>
								<?php endif; ?>
                            </div>
                        </div>
                    </div>
				<?php endwhile; ?>
				</div>
            </div>
			<?php
			wp_reset_postdata();
			?>
        </div>
    </div>
<?php

$id_post_slider = '#' . $block_id;
$id_post_slider2 = '#' . $block_id;
$css_custom     = Penci_Vc_Helper::get_heading_block_css( $id_post_slider, $atts );

if ( 'horizontal' == $penci_size ) {
	$css_custom .= $id_post_slider . ' .penci-widget-slider .penci-image-holder:before{ padding-top: 66.6667%; }';
}
if ( 'square' == $penci_size ) {
	$css_custom .= $id_post_slider . ' .penci-widget-slider .penci-image-holder:before{ padding-top: 100%; }';
}
if ( 'vertical' == $penci_size ) {
	$css_custom .= $id_post_slider . ' .penci-widget-slider .penci-image-holder:before{ padding-top: 135.4%; }';
}
if ( 'custom' == $penci_size && $penci_img_ratio ) {
	$css_custom .= $id_post_slider . ' .penci-widget-slider .penci-image-holder:before{ padding-top: ' . esc_attr( $penci_img_ratio ) . '%; }';
}
if ( $ptitle_color ) {
	$css_custom .= $id_post_slider2 . ' .penci-widget-slider .penci-widget-slide-detail h4,';
	$css_custom .= $id_post_slider2 . ' .penci-widget-slider .penci-widget-slide-detail h4 a{ color: ' . esc_attr( $ptitle_color ) . ' !important;}';
}
if ( $ptitle_hcolor ) {
	$css_custom .= $id_post_slider2 . ' .penci-widget-slider .penci-widget-slide-detail h4 a:hover{ color: ' . esc_attr( $ptitle_hcolor ) . ' !important;}';
}
$css_custom .= Penci_Vc_Helper::vc_google_fonts_parse_attributes( array(
	'font_size'  => $ptitle_fsize,
	'font_style' => 'yes' == $use_ptitle_typo ? $ptitle_typo : '',
	'template'   => "{$id_post_slider} .penci-widget-slider .penci-widget-slide-detail h4 a,{$id_post_slider} .penci-widget-slider .penci-widget-slide-detail h4" . '{ %s }',
) );

if ( $pmeta_color ) {
	$css_custom .= $id_post_slider2 . ' .penci-widget-slide-detail .slide-item-date { color: ' . esc_attr( $pmeta_color ) . ' !important;}';
}
$css_custom .= Penci_Vc_Helper::vc_google_fonts_parse_attributes( array(
	'font_size'  => $pmeta_fsize,
	'font_style' => 'yes' == $use_pmeta_typo ? $pmeta_typo : '',
	'template'   => "{$id_post_slider} .penci-widget-slide-detail .slide-item-date" . '{ %s }',
) );

if ( $responsive_spacing ) {
	$css_custom .= penci_extract_spacing_style( $id_post_slider, $responsive_spacing );
}

$css_custom .= Penci_Vc_Helper::get_bookmark_icon_css( $id_post_slider, $atts );

if ( $css_custom ) {
	echo '<style>';
	echo $css_custom;
	echo '</style>';
}
