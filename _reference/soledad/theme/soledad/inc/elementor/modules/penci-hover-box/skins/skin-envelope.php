<?php

namespace PenciSoledadElementor\Modules\PenciHoverBox\Skins;

use Elementor\Skin_Base as Elementor_Skin_Base;
use Elementor\Icons_Manager;
use PenciSoledadElementor\Modules\QueryControl\Module as Query_Control;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

class Skin_Envelope extends Elementor_Skin_Base {
	public function get_id() {
		return 'penci-hvb-envelope';
	}

	public function get_title() {
		return __( 'Style 1', 'soledad' );
	}

	public function render() {

		$settings = $this->parent->get_settings();
		
		$this->parent->markup_block_title( $settings, $this );

		if ( $settings['hover_box_event'] ) {
			$hoverBoxEvent = $settings['hover_box_event'];
		} else {
			$hoverBoxEvent = false;
		}

		if ( $settings['box_image_effect'] ) {
			$this->parent->add_render_attribute( 'hover_box', 'class', 'penci-hover-box-img-effect penci-hvb-' . $settings['box_image_effect_select'] );
		}

		$this->parent->add_render_attribute(
			[
				'hover_box' => [
					'id'            => 'penci-hover-box-' . $this->parent->get_id(),
					'class'         => 'penci-hover-box penci-hover-box-skin-envelope',
					'data-settings' => [
						wp_json_encode( array_filter( [
							'box_id'      => 'penci-hover-box-' . $this->parent->get_id(),
							'mouse_event' => $hoverBoxEvent,
						] ) )
					]
				]
			]
		);

		?>
        <div <?php $this->parent->print_render_attribute_string( 'hover_box' ); ?>>

			<?php
			if ( 'custom' == $settings['item_type'] ) {
				$this->parent->box_content();
				$this->box_items();
			} else {
				$this->parent->post_content();
				$this->post_items();
			}
			?>
        </div>

		<?php
	}

	public function box_items() {
		$settings = $this->parent->get_settings_for_display();
		$id       = $this->parent->get_id();

		$this->parent->add_render_attribute( 'box-settings', 'data-penci-hover-box-items', 'connect: #penci-hvb-box-content-' . esc_attr( $id ) . ';' );
		$this->parent->add_render_attribute( 'box-settings', 'class', 'penci-hover-box-item-wrap' );

		$this->parent->add_render_attribute( 'box-settings', 'data-penci-hover-box-grid', '' );
		$this->parent->add_render_attribute( 'box-settings', 'class', [ 'penci-hover-box-grid-collapse' ] );

		$this->parent->add_render_attribute( 'box-settings', 'class', 'penci-hvb-slider-items' );

		$this->parent->add_render_attribute(
			[
				'slider-settings' => [
					'class'        => 'swiper-carousel swiper penci-owl-carousel penci-owl-carousel-slider',
					'data-item'    => isset( $settings['ct_columns']['size'] ) ? $settings['ct_columns']['size'] : '4',
					'data-desktop' => isset( $settings['ct_columns']['size'] ) ? $settings['ct_columns']['size'] : '4',
					'data-tablet'  => isset( $settings['ct_columns_tablet']['size'] ) ? $settings['ct_columns_tablet']['size'] : '2',
					'data-mobile'  => isset( $settings['ct_columns_mobile']['size'] ) ? $settings['ct_columns_mobile']['size'] : '1',
				]
			]
		);

		?>
        <div <?php $this->parent->print_render_attribute_string( 'box-settings' ); ?>>
            <div <?php $this->parent->print_render_attribute_string( 'slider-settings' ); ?>>
                <div class="swiper-wrapper">
					<?php foreach ( $settings['hover_box'] as $index => $item ) :

					$tab_count   = $index + 1;
					$tab_id      = 'penci-hvb-box-' . $tab_count . esc_attr( $id );
					$active_item = $this->parent->activeItem( $settings['hover_box_active_item'], count( $settings['hover_box'] ) );
					if ( $tab_id == 'penci-hvb-box-' . $active_item . esc_attr( $id ) ) {
						$this->parent->add_render_attribute( 'box-item', 'class', 'penci-hover-box-item active', true );
					} else {
						$this->parent->add_render_attribute( 'box-item', 'class', 'penci-hover-box-item', true );
					}

					$this->parent->add_render_attribute( 'penci-hover-box-title', 'class', 'penci-hover-box-title', true );

					$title_key  = 'title_' . $index;
					$button_key = 'button_' . $index;
					$this->parent->add_render_attribute( $title_key, 'class', 'penci-hover-box-title-link', true );
					$this->parent->add_link_attributes( $title_key, isset( $item['title_link'] ) ? $item['title_link'] : [] );
					$this->parent->add_link_attributes( $button_key, isset( $item['button_link'] ) ? $item['button_link'] : [] );

					?>
                    <div class="swiper-slide">
                        <div <?php $this->parent->print_render_attribute_string( 'box-item' ); ?>
                                data-id="<?php echo esc_attr( $tab_id ); ?>">

                            <div class="penci-hover-box-description penci-hvb-position-small penci-hvb-position-<?php echo esc_attr( $settings['content_position'] ); ?>">
								<?php if ( 'yes' == $settings['show_icon'] ) : ?>
                                    <a class="penci-hover-box-icon-box" href="javascript:void(0);"
                                       data-tab-index="<?php echo esc_attr( $index ); ?>">
								<span class="penci-hover-box-icon-wrap">
									<?php Icons_Manager::render_icon( $item['selected_icon'], [ 'aria-hidden' => 'true' ] ); ?>
								</span>
                                    </a>
								<?php endif; ?>
								<?php if ( $item['hover_box_sub_title'] && ( 'yes' == $settings['show_sub_title'] ) ) : ?>
                                    <div class="penci-hover-box-sub-title">
										<?php echo wp_kses( $item['hover_box_sub_title'], wp_kses_allowed_html( 'post' ) ); ?>
                                    </div>
								<?php endif; ?>

								<?php if ( $item['hover_box_title'] && ( 'yes' == $settings['show_title'] ) ) : ?>
                                <<?php echo esc_attr( $settings['title_tags'] ); ?> <?php $this->parent->print_render_attribute_string( 'penci-hover-box-title' ); ?>
                                >

								<?php if ( '' !== $item['title_link']['url'] ) : ?>
                                <a <?php $this->parent->print_render_attribute_string( $title_key ); ?>>
									<?php endif; ?>
									<?php echo wp_kses( $item['hover_box_title'], wp_kses_allowed_html( 'post' ) ); ?>
									<?php if ( '' !== $item['title_link']['url'] ) : ?>
                                </a>
							<?php endif; ?>

                            </<?php echo esc_attr( $settings['title_tags'] ); ?>>
							<?php endif; ?>

							<?php if ( $item['hover_box_content'] && ( 'yes' == $settings['show_content'] ) ) : ?>
                                <div class="penci-hover-box-text">
									<?php echo wp_kses_post( $item['hover_box_content'] ); ?>
                                </div>
							<?php endif; ?>

							<?php if ( $item['hover_box_button'] && ( 'yes' == $settings['show_button'] ) ) : ?>
                                <div class="penci-hover-box-button">
                                    <a <?php $this->parent->print_render_attribute_string( $button_key ); ?>>
										<?php echo wp_kses_post( $item['hover_box_button'] ); ?>
                                    </a>
                                </div>
							<?php endif; ?>
                        </div>

                    </div>
                </div>
				<?php endforeach; ?>
            </div>
        </div>

        </div>

		<?php
	}

	public function post_items() {
		$settings = $this->parent->get_settings();
		$id       = $this->parent->get_id();

		$this->parent->add_render_attribute( 'box-settings', 'data-penci-hover-box-items', 'connect: #penci-hvb-box-content-' . esc_attr( $id ) . ';' );
		$this->parent->add_render_attribute( 'box-settings', 'class', 'penci-hover-box-item-wrap' );

		$this->parent->add_render_attribute( 'box-settings', 'data-penci-hover-box-grid', '' );
		$this->parent->add_render_attribute( 'box-settings', 'class', [ 'penci-hover-box-grid-collapse' ] );

		$this->parent->add_render_attribute( 'box-settings', 'class', 'penci-hvb-slider-items' );

		$this->parent->add_render_attribute(
			[
				'slider-settings' => [
					'class'        => 'swiper-carousel swiper penci-owl-carousel penci-owl-carousel-slider',
					'data-item'    => isset( $settings['ct_columns']['size'] ) ? $settings['ct_columns']['size'] : '4',
					'data-desktop' => isset( $settings['ct_columns']['size'] ) ? $settings['ct_columns']['size'] : '4',
					'data-tablet'  => isset( $settings['ct_columns_tablet']['size'] ) ? $settings['ct_columns_tablet']['size'] : '2',
					'data-mobile'  => isset( $settings['ct_columns_mobile']['size'] ) ? $settings['ct_columns_mobile']['size'] : '1',
				]
			]
		);

		$args = Query_Control::get_query_args( 'posts', $settings );;
		$query_custom = new \WP_Query( $args );
		$post_meta    = $settings['postmeta'] ? $settings['postmeta'] : [];
		$primary_cat  = $settings['primary_cat'];

		?>
        <div <?php $this->parent->print_render_attribute_string( 'box-settings' ); ?>>
            <div <?php $this->parent->print_render_attribute_string( 'slider-settings' ); ?>>
                <div class="swiper-wrapper">
					<?php
					if ( $query_custom->have_posts() ):
						$post_count = 0;
					while ( $query_custom->have_posts() ) :
					$query_custom->the_post();
					$post_count ++;
					
					$tab_id      = 'penci-hvb-box-' . $post_count . esc_attr( $id );
					$active_item = $this->parent->activeItem( $settings['hover_box_active_item'], $query_custom->found_posts );
					if ( $tab_id == 'penci-hvb-box-' . $active_item . esc_attr( $id ) ) {
						$this->parent->add_render_attribute( 'box-item', 'class', 'penci-hover-box-item active', true );
					} else {
						$this->parent->add_render_attribute( 'box-item', 'class', 'penci-hover-box-item', true );
					}

					$this->parent->add_render_attribute( 'penci-hover-box-title', 'class', 'penci-hover-box-title', true );

					$title_key  = 'title_' . get_the_ID();
					$button_key = 'button_' . get_the_ID();
					$this->parent->add_render_attribute( $title_key, 'class', 'penci-hover-box-title-link', true );
					$this->parent->add_link_attributes( $title_key, isset( $item['title_link'] ) ? $item['title_link'] : [] );
					$this->parent->add_link_attributes( $button_key, isset( $item['button_link'] ) ? $item['button_link'] : [] );

					?>
                    <div class="swiper-slide">
                        <div <?php $this->parent->print_render_attribute_string( 'box-item' ); ?>
                                data-id="<?php echo esc_attr( $tab_id ); ?>">

                            <div class="penci-hover-box-description penci-hvb-position-small penci-hvb-position-<?php echo esc_attr( $settings['content_position'] ); ?>">
								<?php if ( 'yes' == $settings['show_icon'] ) : ?>
                                    <a class="penci-hover-box-icon-box" href="javascript:void(0);"
                                       data-tab-index="<?php echo esc_attr( get_the_ID() ); ?>">
								<span class="penci-hover-box-icon-wrap">
								<?php if ( has_post_format( 'video' ) ) : ?>
									<?php penci_fawesome_icon( 'fas fa-play' ); ?>
								<?php endif; ?>
								<?php if ( has_post_format( 'gallery' ) ) : ?>
									<?php penci_fawesome_icon( 'far fa-image' ); ?>
								<?php endif; ?>
								<?php if ( has_post_format( 'audio' ) ) : ?>
									<?php penci_fawesome_icon( 'fas fa-music' ); ?>
								<?php endif; ?>
								<?php if ( has_post_format( 'link' ) ) : ?>
									<?php penci_fawesome_icon( 'fas fa-link' ); ?>
								<?php endif; ?>
								<?php if ( has_post_format( 'quote' ) ) : ?>
									<?php penci_fawesome_icon( 'fas fa-quote-left' ); ?>
								<?php endif; ?>
								</span>
                                    </a>
								<?php endif; ?>
								<?php if ( 'yes' == $settings['show_post_meta'] ) : ?>
									<?php if ( ( isset( $settings['cspost_enable'] ) && $settings['cspost_enable'] ) || ( count( array_intersect( array(
												'author',
												'date',
												'comment',
												'views',
												'reading',
												'excerpt'
											), $post_meta ) ) > 0 ) ) { ?>
                                        <div class="pchvb-meta">
                                            <div class="pcbg-meta-desc">
											<?php if ( in_array( 'cat', $post_meta ) ) : ?>
												<span class="bg-cat-meta">
													<?php penci_category( '', $primary_cat ); ?>
												</span>
											<?php endif; ?>
												<?php if ( in_array( 'author', $post_meta ) ) : ?>
                                                    <span class="bg-date-author author-italic author vcard">
												<?php echo penci_get_setting( 'penci_trans_by' ); ?> <?php if ( function_exists( 'coauthors_posts_links' ) ) :
															penci_coauthors_posts_links();
														else: ?>
                                                            <a class="author-url url fn n"
                                                               href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>"><?php the_author(); ?></a>
														<?php endif; ?>
											</span>
												<?php endif; ?>
												<?php if ( in_array( 'date', $post_meta ) ) : ?>
                                                    <span class="bg-date"><?php penci_soledad_time_link(); ?></span>
												<?php endif; ?>
												<?php if ( in_array( 'comment', $post_meta ) ) : ?>
                                                    <span class="bg-comment">
												<a href="<?php comments_link(); ?> "><?php comments_number( '0 ' . penci_get_setting( 'penci_trans_comments' ), '1 ' . penci_get_setting( 'penci_trans_comment' ), '% ' . penci_get_setting( 'penci_trans_comments' ) ); ?></a>
											</span>
												<?php endif; ?>
												<?php
												if ( in_array( 'views', $post_meta ) ) {
													echo '<span>';
													echo penci_get_post_views( get_the_ID() );
													echo ' ' . penci_get_setting( 'penci_trans_countviews' );
													echo '</span>';
												}
												?>
												<?php
												$hide_readtime = in_array( 'reading', $post_meta ) ? false : true;
												if ( penci_isshow_reading_time( $hide_readtime ) ): ?>
                                                    <span class="bg-readtime"><?php penci_reading_time(); ?></span>
												<?php endif; ?>
												<?php echo penci_show_custom_meta_fields( [
													'validator' => isset( $settings['cspost_enable'] ) ? $settings['cspost_enable'] : '',
													'keys'      => isset( $settings['cspost_cpost_meta'] ) ? $settings['cspost_cpost_meta'] : '',
													'acf'       => isset( $settings['cspost_cpost_acf_meta'] ) ? $settings['cspost_cpost_acf_meta'] : '',
													'label'     => isset( $settings['cspost_cpost_meta_label'] ) ? $settings['cspost_cpost_meta_label'] : '',
													'divider'   => isset( $settings['cspost_cpost_meta_divider'] ) ? $settings['cspost_cpost_meta_divider'] : '',
												] ); ?>
												<?php do_action( 'penci_extra_meta' ); ?>
                                            </div>
                                        </div>
									<?php } ?>
								<?php endif; ?>

								<?php if ( 'yes' == $settings['show_title'] ) : ?>
                                <<?php echo esc_attr( $settings['title_tags'] ); ?>
								<?php $this->parent->print_render_attribute_string( 'penci-hover-box-title' ); ?>>


                                <a href="<?php the_permalink(); ?>">
									<?php the_title(); ?>
                                </a>


                            </<?php echo esc_attr( $settings['title_tags'] ); ?>>
							<?php endif; ?>

							<?php if ( 'yes' == $settings['show_content'] ) : ?>
                                <div class="penci-hover-box-text">
									<?php penci_the_excerpt( $settings['post_content_length'] ); ?>
                                </div>
							<?php endif; ?>

							<?php if ( 'yes' == $settings['show_button'] ) : ?>
                                <div class="penci-hover-box-button">
                                    <a href="<?php the_permalink(); ?>">
										<?php echo penci_get_setting( 'penci_trans_read_more' ); ?>
                                    </a>
                                </div>
							<?php endif; ?>
                        </div>

                    </div>
                </div>
				<?php endwhile;
				endif; ?>
            </div>
        </div>

        </div>

		<?php
	}
}

