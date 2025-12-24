<?php
$all_points   = \PenciUserProfile::getInstance();
$endpoints    = $all_points->get_endpoint();
$account_slug = $endpoints['account']['slug'];
$posts_slug   = $endpoints['like_posts']['slug'];
$post_like    = get_user_option( '_liked_posts', get_current_user_id() );
$post_like    = is_array( $post_like ) ? array_values( $post_like ) : [];
$order        = isset( $_GET['order'] ) ? sanitize_text_field( $_GET['order'] ) : 'desc';
$args         = array(
	'post_type'           => 'post',
	'post__in'            => $post_like,
	'orderby'             => 'date',
	'order'               => $order,
	'posts_per_page'      => - 1,
	'post_status'         => 'any',
	'ignore_sticky_posts' => 1,
);
$posts        = new WP_Query( $args );

if ( $posts->have_posts() && ! empty( $post_like ) ) {

	$total_post = $lpost = $posts->found_posts;

	?>

    <div class="penci_account_posts">
        <div class="penci_post_list_meta">
            <div class="penci_post_list_count">
                <span><?php echo sprintf( str_replace( '{{value}}', '%s', penci_get_setting( 'penci_trans_showing_result' ) ), 1, $lpost, $total_post ) ?></span>
            </div>
            <div class="penci_post_list_filter">
                <input type="hidden" name="current-page-url"
                       value="<?php echo esc_url( penci_home_url_multilang( '/' . $account_slug . '/' . $posts_slug ) ); ?>">
                <select name="post-list-filter">
                    <option <?php echo ( $order === 'desc' ) ? esc_attr( 'selected' ) : ''; ?>
                            value="desc"><?php echo penci_get_setting( 'penci_trans_sort_latest' ); ?></option>
                    <option <?php echo ( $order === 'asc' ) ? esc_attr( 'selected' ) : ''; ?>
                            value="asc"><?php echo penci_get_setting( 'penci_trans_sort_older' ); ?></option>
                </select>
            </div>
        </div>
        <div class="penci-smalllist">
            <div class="pcsl-inner penci-clearfix pcsl-grid pcsl-imgpos-left pcsl-col-1 pcsl-tabcol-2 pcsl-mobcol-1">
				<?php

				while ( $posts->have_posts() ) :
					$posts->the_post();

					$post_id     = get_the_ID();
					$post_status = get_post_status_object( get_post_status( $post_id ) )->label;

					?>
                    <article <?php post_class( 'pcsl-item' ); ?>>
                        <div class="pcsl-itemin">
                            <div class="pcsl-iteminer">
                                <div class="pcsl-thumb">
                                    <a href="<?php the_permalink(); ?>"
                                       title="<?php echo wp_strip_all_tags( get_the_title() ); ?>"
                                       class="penci-image-holder">
										<?php echo penci_layout_img( penci_get_featured_image_size( get_the_ID(), 'penci-thumb' ), get_the_title() ); ?>
                                    </a>
                                </div>
                                <div class="pcsl-content">
                                    <div class="cat pcsl-cat">
										<?php penci_category(); ?>
                                    </div>
                                    <div class="pcsl-title">
                                        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                    </div>
                                    <div class="grid-post-box-meta pcsl-meta">
                                         <span class="sl-date-author author-italic">
													<?php echo penci_get_setting( 'penci_trans_by' ); ?> <?php if ( function_exists( 'coauthors_posts_links' ) ) :
		                                         penci_coauthors_posts_links();
	                                         else: ?>
                                                 <a class="author-url url fn n"
                                                    href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>"><?php the_author(); ?></a>
	                                         <?php endif; ?>
													</span>
                                        <span class="sl-date"><?php penci_soledad_time_link(); ?></span>
                                        <?php do_action( 'penci_extra_meta' ); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </article>
				<?php
				endwhile;

				?>
            </div>
        </div>
    </div>

	<?php


} else {
	echo "<div class='penci_empty_module'>" . penci_get_setting( 'penci_trans_no_content' ) . "</div>";
}

wp_reset_postdata();
?>