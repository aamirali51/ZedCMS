<?php
/**
 * Related post popup template
 * Render list related posts
 *
 * @since 6.3
 */

$orig_post = $post;
global $post;
$numbers_related = get_theme_mod( 'penci_rltpopup_numpost' );
if ( ! isset( $numbers_related ) || $numbers_related < 1 ): $numbers_related = 3; endif;


$orderby_post         = get_theme_mod( 'penci_rltpopup_orderby' ) ? get_theme_mod( 'penci_rltpopup_orderby' ) : 'date';
$related_position     = get_theme_mod( 'penci_rltpopup_position' ) ? get_theme_mod( 'penci_rltpopup_position' ) : 'left';
$related_order_post   = get_theme_mod( 'penci_rltpopup_sort_order' ) ? get_theme_mod( 'penci_rltpopup_sort_order' ) : 'DESC';
$penci_related_by     = get_theme_mod( 'penci_rltpopup_by' );
$related_title_length = get_theme_mod( 'penci_rltpopup_title_length' ) ? get_theme_mod( 'penci_rltpopup_title_length' ) : 6;

$args = penci_get_query_related_posts( get_the_ID(), $penci_related_by, $orderby_post, $related_order_post, $numbers_related );

if ( ! empty( $args ) ) {

$my_query = new wp_query( $args );
if ( $my_query->have_posts() ) {
?>
<aside class="penci-rlt-popup penci-rltpopup-<?php echo $related_position; ?><?php if ( get_theme_mod( 'penci_rltpopup_hide_mobile' ) ): echo ' rltpopup-hide-mobile'; endif; ?>">
    <h3 class="rtlpopup-heading"><?php echo penci_get_setting( 'penci_rltpopup_heading_text' ); ?><a
                class="penci-close-rltpopup">x<span></span><span></span></a></h3>

    <div class="penci-rtlpopup-content">
		<?php $i = 0;
		while ( $my_query->have_posts() ) : $my_query->the_post();
			$trans = 400 + ( 80 * $i ); ?>
            <div class="rltpopup-item"
                 style="transition-delay: <?php echo $trans; ?>ms; -webkit-transition-delay: <?php echo $trans; ?>ms;">
                <div class="rltpopup-item-inner">
					<?php if ( ( function_exists( 'has_post_thumbnail' ) ) && ( has_post_thumbnail() ) ) : ?>

                        <figure class="rltpopup-thumbnail">
                            <a <?php echo penci_layout_bg( penci_image_srcset( get_the_ID(), penci_featured_images_size( 'small' ) ), ! get_theme_mod( 'penci_disable_lazyload_single' ) ); ?> class="<?php echo penci_layout_bg_class();?> rltpopup-thumb penci-image-holder"
                               href="<?php the_permalink(); ?>"
                               title="<?php echo wp_strip_all_tags( get_the_title() ); ?>">
								<?php echo penci_layout_img( penci_image_srcset( get_the_ID(), penci_featured_images_size( 'small' ) ), get_the_title(), ! get_theme_mod( 'penci_disable_lazyload_single' ) ); ?>
                            </a>
                        </figure>

					<?php endif; ?>
                    <div class="rltpopup-meta">
                        <h4><a class="rltpopup-title"
                               href="<?php the_permalink(); ?>"><?php echo wp_trim_words( get_the_title(), $related_title_length, '...' ); ?></a>
                        </h4>
						<?php if ( ! get_theme_mod( 'penci_rltpopup_hide_date' ) ): ?>
                            <span class="date"><?php penci_soledad_time_link(); ?></span>
						<?php endif; ?>
                    </div>
                </div>
            </div>
			<?php
			$i ++; endwhile;
		echo '</div></aside>';
		}
		}
		$post = $orig_post;
		wp_reset_postdata();
		?>
