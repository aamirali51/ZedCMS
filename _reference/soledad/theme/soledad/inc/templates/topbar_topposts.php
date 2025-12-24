<div class="pctopbar-item penci-topbar-trending">
	<?php if ( penci_get_setting( 'penci_top_bar_custom_text' ) ) {
		$toptext_style = get_theme_mod( 'penci_top_bar_custom_style' ) ? get_theme_mod( 'penci_top_bar_custom_style' ) : 'nticker-style-1';
		?>
        <span class="headline-title <?php echo $toptext_style; ?>"><?php echo penci_get_setting( 'penci_top_bar_custom_text' ); ?></span>
	<?php } ?>
	<?php
	/**
	 * Display headline slider
	 */
	$number_posts = get_theme_mod( 'penci_top_bar_posts_per_page' ) ? get_theme_mod( 'penci_top_bar_posts_per_page' ) : 10;
	$topbar_cat   = get_theme_mod( 'penci_top_bar_category' );
	$topbar_sort  = get_theme_mod( 'penci_top_bar_display_by' );
	$title_length = get_theme_mod( 'penci_top_bar_title_length' ) ? get_theme_mod( 'penci_top_bar_title_length' ) : 8;

	$args = array(
		'post_type'      => 'post',
		'posts_per_page' => $number_posts
	);

	if ( ! get_theme_mod( 'penci_top_bar_tags' ) || get_theme_mod( 'penci_top_bar_filter_by' ) != 'tags' ) {
		if ( $topbar_cat ):
			$args['cat'] = $topbar_cat;
		endif;
	} elseif ( get_theme_mod( 'penci_top_bar_tags' ) && get_theme_mod( 'penci_top_bar_filter_by' ) == 'tags' ) {
		$list_tag          = get_theme_mod( 'penci_top_bar_tags' );
		$list_tag_trim     = str_replace( ' ', '', $list_tag );
		$list_tags         = explode( ',', $list_tag_trim );
		$args['tax_query'] = array(
			array(
				'taxonomy' => 'post_tag',
				'field'    => 'slug',
				'terms'    => $list_tags
			),
		);
	}

	if ( $topbar_sort == 'all' ) {
		$args['meta_key'] = penci_get_postviews_key();
		$args['orderby']  = 'meta_value_num';
		$args['order']    = 'DESC';
	} elseif ( $topbar_sort == 'week' ) {
		$args['meta_key'] = 'penci_post_week_views_count';
		$args['orderby']  = 'meta_value_num';
		$args['order']    = 'DESC';
	} elseif ( $topbar_sort == 'month' ) {
		$args['meta_key'] = 'penci_post_month_views_count';
		$args['orderby']  = 'meta_value_num';
		$args['order']    = 'DESC';
	} elseif ( $topbar_sort == 'month' ) {
		$args['meta_key'] = '_jetpack_post_view';
		$args['orderby']  = 'meta_value_num';
		$args['order']    = 'DESC';
	}

	$news = new WP_Query( $args );
	if ( $news->have_posts() ):
		$auto_play = 'true';
		if ( get_theme_mod( 'penci_top_bar_posts_autoplay' ) ): $auto_play = 'false'; endif;
		$auto_time  = get_theme_mod( 'penci_top_bar_auto_time' );
		$auto_speed = get_theme_mod( 'penci_top_bar_auto_speed' );
		$auto_time  = ( is_numeric( $auto_time ) && $auto_time > 0 ) ? $auto_time : '3000';
		$auto_speed = ( is_numeric( $auto_speed ) && $auto_speed > 0 ) ? $auto_speed : '200';
		$animation  = get_theme_mod( 'penci_top_bar_animation' ) ? get_theme_mod( 'penci_top_bar_animation' ) : 'slideInUp';
		$main_class = $animation == 'marquee' ? 'no-df pcmarquee-slider' : 'pcdfswiper';
		if ( $animation != 'marquee' ) :
			?>
            <span class="penci-trending-nav">
			<a class="penci-slider-prev" href="#"
               aria-label="Previous"><?php penci_fawesome_icon( 'fas fa-angle-left' ); ?></a>
			<a class="penci-slider-next" href="#"
               aria-label="Next"><?php penci_fawesome_icon( 'fas fa-angle-right' ); ?></a>
		</span>
		<?php
		else:
			wp_enqueue_script( 'penci-marquee' );
		endif; ?>
        <div class="<?php echo $main_class; ?> swiper penci-owl-carousel penci-owl-carousel-slider penci-headline-posts"
             data-auto="<?php echo $auto_play; ?>" data-nav="false" data-autotime="<?php echo $auto_time; ?>"
             data-speed="<?php echo $auto_speed; ?>" data-anim="<?php echo $animation; ?>">
            <div class="swiper-wrapper">
				<?php while ( $news->have_posts() ): $news->the_post();
					$title_full = get_the_title();
					?>
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