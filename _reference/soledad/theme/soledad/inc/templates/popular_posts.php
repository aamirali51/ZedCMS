<?php
extract( $args );
$page_num      = isset( $paged ) && $paged ? $paged : 1;
$column_format = isset( $columns ) && $columns ? 'columns-format col-' . $columns : 'normal-format';
$class         .= isset( $mcolumns ) && $mcolumns ? ' m-col-' . $mcolumns : '';
$class         .= isset( $tcolumns ) && $tcolumns ? ' t-col-' . $tcolumns : '';
$class         .= ' ' . $column_format;
?>
<ul <?php echo $data_attr; ?> class="popularpost_list <?php echo esc_attr( $class ); ?>"
                              id="<?php echo esc_attr( $id ); ?>">
	<?php
	$count         = 1;
	$post_per_page = $loop->query['posts_per_page'];
	while ( $loop->have_posts() ) : $loop->the_post();
		$count_class  = $count == 1 && $column_format == 'normal-format' ? ' first' : '';
		$count_number = $page_num > 1 ? $count + ( $post_per_page * ( $page_num - 1 ) ) : $count;
		?>
        <li class="popularpost_item<?php echo esc_attr( $count_class ); ?>">
			<?php if ( $count == 1 && $column_format == 'normal-format' ): ?>
                <div class="pcpopular_new_thumb">
                    <div class="thumbnail-container">
                        <a <?php echo penci_layout_bg( penci_image_srcset( get_the_ID(), $thumb ) ); ?> class="<?php echo penci_layout_bg_class();?> penci-image-holder"
                           href="<?php the_permalink(); ?>"
                           title="<?php echo wp_strip_all_tags( get_the_title() ); ?>">
							<?php echo penci_layout_img( penci_image_srcset( get_the_ID(), $thumb ), get_the_title() ); ?>
                        </a>
                    </div>
                </div>
			<?php endif; ?>
            <h3 class="pcpopular_new_post_title"><a
                        href="<?php the_permalink(); ?>"
                        data-num="<?php echo $count_number; ?>">
					<?php
					if ( ! $title_length || ! is_numeric( $title_length ) ) {
						the_title();
					} else {
						echo wp_trim_words( wp_strip_all_tags( get_the_title() ), $title_length, '...' );
					}
					?>

                </a></h3>
            <div class="popularpost_meta">
				<?php if ( $showauthor ): ?>
                    <span class="side-item-meta side-wauthor"><?php echo penci_get_setting( 'penci_trans_by' ); ?> <a
                                class="url fn n"
                                href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>"><?php the_author(); ?></a></span>
				<?php endif; ?>
				<?php if ( ! $postdate ): ?>
                    <span class="side-item-meta side-wdate"><?php penci_soledad_time_link(); ?></span>
				<?php endif; ?>
				<?php if ( $showcomment ): ?>
                    <span class="side-item-meta side-wcomments"><a
                                href="<?php comments_link(); ?> "><?php comments_number( '0 ' . penci_get_setting( 'penci_trans_comments' ), '1 ' . penci_get_setting( 'penci_trans_comment' ), '% ' . penci_get_setting( 'penci_trans_comments' ) ); ?></a></span>
				<?php endif; ?>
				<?php if ( $showviews ): ?>
                    <span class="side-item-meta side-wviews"><?php echo penci_get_post_views( get_the_ID() ) . ' ' . penci_get_setting( 'penci_trans_countviews' ); ?></span>
				<?php endif; ?>
				<?php do_action( 'penci_extra_meta' ); ?>
            </div>
        </li>
		<?php
		$count ++;
	endwhile;
	wp_reset_postdata(); ?>
</ul>