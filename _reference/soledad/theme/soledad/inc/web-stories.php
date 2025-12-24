<?php
if ( ! function_exists( 'penci_webstories' ) ) {
	function penci_webstories( $settings = [] ) {

		$default = [
			'id'                 => '',
			'layout'             => 'grid',
			'mobile_one_row'     => 'yes',
			'shownav'            => '',
			'showdots'           => '',
			'autoplay'           => '',
			'auto_time'          => '',
			'speed'              => '',
			'loop'               => '',
			'query_type'         => 'all',
			'story_cat'          => '',
			'story_tag'          => '',
			'story_ids'          => '',
			'ex_story_cat'       => '',
			'ex_story_tag'       => '',
			'ex_story_ids'       => '',
			'showtitle'          => '',
			'nextprev'           => '',
			'pos'                => '',
			'imgsize'            => 'post-thumbnail',
			'lazyload'           => '',
			'columns'            => [ 'size' => 4 ],
			'columns_tablet'     => [ 'size' => 4 ],
			'columns_mobile'     => [ 'size' => 4 ],
			'ajax_loading_style' => 'df'
		];

		$settings = wp_parse_args( $settings, $default );

		$count     = 0;
		$class     = [];
		$data_attr = [];

		$class[] = $settings['layout'];

		if ( $settings['layout'] == 'onerow' ) {
			$class[] = 'one-row';
		}

		$lazyload = 'yes' == $settings['lazyload'] ? 'false' : 'true';

		if ( $settings['layout'] == 'slider' ) {
			$class[] = 'penci-owl-carousel penci-owl-carousel-slider swiper';

			$data_next_prev = 'yes' == $settings['shownav'] ? 'true' : 'false';
			$data_dots      = 'yes' == $settings['showdots'] ? 'true' : 'false';
			$data_attr[]    = 'data-height="false"';
			$data_attr[]    = 'data-dots="' . $data_dots . '" data-nav="' . $data_next_prev . '"';
			$data_attr[]    = 'data-auto="' . ( 'yes' == $settings['autoplay'] ? 'true' : 'false' ) . '"';
			$data_attr[]    = 'data-autotime="' . ( $settings['auto_time'] ? intval( $settings['auto_time'] ) : '4000' ) . '"';
			$data_attr[]    = 'data-speed="' . ( $settings['speed'] ? intval( $settings['speed'] ) : '600' ) . '"';
			$data_attr[]    = 'data-loop="' . ( 'yes' == $settings['loop'] ? 'true' : 'false' ) . '"';
			$data_attr[]    = 'data-item="' . ( isset( $settings['columns']['size'] ) ? $settings['columns']['size'] : 6 ) . '"';
			$data_attr[]    = 'data-desktop="' . ( isset( $settings['columns']['size'] ) ? $settings['columns']['size'] : 6 ) . '"';
			$data_attr[]    = 'data-tablet="' . ( isset( $settings['columns_tablet']['size'] ) ? $settings['columns_tablet']['size'] : 6 ) . '"';
			$data_attr[]    = 'data-mobile="' . ( isset( $settings['columns_mobile']['size'] ) ? $settings['columns_mobile']['size'] : 2 ) . '"';
		}

		$data_attr = ! empty( $data_attr ) ? ' ' . implode( ' ', $data_attr ) : '';

		$agrs = [
			'post_type'      => 'web-story',
			'orderby'        => $settings['orderby'],
			'order'          => $settings['order'],
			'posts_per_page' => $settings['number'],
			'offset'         => $settings['offset'],
		];

		if ( $settings['query_type'] == 'custom' ) {
			$agrs['post__in'] = is_array( $settings['story_ids'] ) ? $settings['story_ids'] : explode( ',', $settings['story_ids'] );
		} else {
			if ( $settings['story_cat'] ) {
				$agrs['tax_query'][] = [
					'taxonomy' => 'web_story_category',
					'field'    => 'term_id',
					'terms'    => is_array( $settings['story_cat'] ) ? $settings['story_cat'] : explode( ',', $settings['story_cat'] ),
				];
			}
			if ( $settings['story_tag'] ) {
				$agrs['tax_query'][] = [
					'taxonomy' => 'web_story_tag',
					'field'    => 'term_id',
					'terms'    => is_array( $settings['story_tag'] ) ? $settings['story_tag'] : explode( ',', $settings['story_tag'] ),
				];
			}
			if ( $settings['ex_story_cat'] ) {
				$agrs['tax_query'][] = [
					'taxonomy' => 'web_story_category',
					'field'    => 'term_id',
					'terms'    => is_array( $settings['ex_story_cat'] ) ? $settings['ex_story_cat'] : explode( ',', $settings['ex_story_cat'] ),
					'operator' => 'NOT IN',
				];
			}
			if ( $settings['ex_story_tag'] ) {
				$agrs['tax_query'][] = [
					'taxonomy' => 'ex_story_tag',
					'field'    => 'term_id',
					'terms'    => is_array( $settings['ex_story_tag'] ) ? $settings['ex_story_tag'] : explode( ',', $settings['ex_story_tag'] ),
					'operator' => 'NOT IN',
				];
			}
			if ( $settings['ex_story_ids'] ) {
				$agrs['post__not_in'] = is_array( $settings['ex_story_ids'] ) ? $settings['ex_story_ids'] : explode( ',', $settings['ex_story_ids'] );
			}
		}

		$web_query = new \WP_Query( $agrs );
		$total     = $web_query->found_posts;
		$seen      = isset( $_COOKIE['pc_webstories_seen'] ) && $_COOKIE['pc_webstories_seen'] ? explode( '|', $_COOKIE['pc_webstories_seen'] ) : [];
		$id_base   = $settings['id'] ? ' id="' . $settings['id'] . '"' : '';
		?>
        <div class="pc-wstories"<?php echo $id_base; ?>>
            <div class="pc-wstories-wrapper">
				<?php if ( $web_query->have_posts() ) : ?>
                    <div class="pc-wstories-list <?php echo implode( ' ', $class ); ?>"
						<?php echo $data_attr; ?>
                         data-total="<?php echo esc_attr( $total ); ?>">
						<?php
						$item_class = ' normal-item';
						if ( $settings['layout'] == 'slider' ) {
						$item_class = ' swiper-slide';
						?>
                        <div class="swiper-wrapper">
							<?php } ?>
							<?php while ( $web_query->have_posts() ) {
								$web_query->the_post();
								$seen_class = in_array( get_the_ID(), $seen ) ? ' seen' : ' new';
								$title      = get_the_title() ? get_the_title() : '';
								$count ++;
								?>
                                <div class="pc-webstory-item item-<?php echo esc_attr( $count ); ?> pc-story-link<?php echo $seen_class . $item_class; ?>"
                                     data-count="<?php echo esc_attr( $count ); ?>"
                                     data-id="<?php the_ID(); ?>"
                                     data-url="<?php echo get_the_permalink(); ?>">
                                    <div class="pc-webstory-item-wrapper">
                                        <div class="pc-webstory-thumb-wrapper">
											<?php if ( has_post_thumbnail() ): ?>

                                                <div <?php echo penci_layout_bg( get_the_post_thumbnail_url( get_the_ID(), $settings['imgsize'] ), $lazyload ); ?> title="<?php echo sanitize_text_field( $title ); ?>"
                                                     class="<?php echo penci_layout_bg_class( $lazyload );?> penci-image-holder pc-webstory-thumb">
													<?php echo penci_layout_img( get_the_post_thumbnail_url( get_the_ID(), $settings['imgsize'] ), $title, $lazyload ); ?>
                                                </div>

											<?php else: ?>
                                                <div title="<?php echo sanitize_text_field( $title ); ?>"
                                                     style="background-image:none;background-color:<?php echo sprintf( "#%06x", rand( 0, 16777215 ) ); ?>"
                                                     class="penci-image-holder pc-webstory-thumb"></div>
											<?php endif; ?>
                                        </div>
										<?php if ( 'yes' == $settings['showtitle'] ): ?>
                                            <div class="pc-webstory-item-title">
                                                <h4 title="<?php echo sanitize_text_field( $title ); ?>"><?php echo esc_html( $title ); ?></h4>
                                            </div>
										<?php endif; ?>
                                    </div>
                                </div>
							<?php } ?>
							<?php if ( $settings['layout'] == 'slider' ) { ?>
                        </div>
					<?php } ?>
                    </div>
				<?php endif;
				wp_reset_postdata(); ?>
            </div>
            <div class="pc-wstories-popup-wrapper">
                <div class="pc-wstories-popup-toolbar">
                    <span class="pc-ws-btn close">
                        <i class="penciicon-close-button"></i>
                    </span>
					<?php if ( 'yes' == $settings['nextprev'] ): ?>
                        <span class="pc-ws-btn pc-story-link disable previous">
                        <i class="penciicon-left-chevron"></i>
                    </span>
					<?php endif; ?>
					<?php if ( 'yes' == $settings['pos'] ): ?>
                        <span class="pc-ws-btn pc-story-info">
                            <span class="current"></span> / <span class="total"></span>
                        </span>
					<?php endif; ?>
					<?php if ( 'yes' == $settings['nextprev'] ): ?>
                        <span class="pc-ws-btn pc-story-link disable next">
                        <i class="penciicon-right-chevron"></i>
                    </span>
					<?php endif; ?>
                </div>
                <div class="pc-wstories-popup-content"></div>
                <div class="pc-loading-wrapper">
					<?php echo penci_get_html_animation_loading( $settings['ajax_loading_style'] ); ?>
                </div>
            </div>
        </div>
		<?php
	}
}
