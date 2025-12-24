<?php
/**
 * Template loop for masonry style
 */
if ( ! isset ( $j ) ) { $j = 1; } else { $j = $j; }
$post_id = get_the_ID();
$heading_title_tag = get_theme_mod( 'penci_grid_title_tag', 'h2' );
$heading_title_tag_class = 'h2' == $heading_title_tag ? 'penci_grid_title_df' : 'penci_grid_title';
?>
<article id="post-<?php the_ID(); ?>" class="item item-masonry grid-masonry hentry<?php if( get_theme_mod('penci_grid_meta_overlay') ): echo ' grid-overlay-meta'; endif; ?>">
	<?php if ( has_post_thumbnail() ) : ?>
		<div class="thumbnail">
			<?php
			do_action( 'penci_bookmark_post' );
			/* Display Review Piechart  */
			if( function_exists('penci_display_piechart_review_html') ) {
				penci_display_piechart_review_html( get_the_ID() );
			}
			?>
			<a href="<?php penci_permalink_fix() ?>" class="post-thumbnail<?php echo penci_class_lightbox_enable(); ?>">
				<?php 
				$src_full = penci_get_featured_image_size( get_the_ID(), 'full' );
				$src_check = substr( $src_full, -4 );
				if( $src_check == '.gif' ) {
					echo penci_get_featured_image_padding_markup( get_the_ID(), 'full' );
					the_post_thumbnail( 'full' );
				} else {
					echo penci_get_featured_image_padding_markup( get_the_ID(), 'penci-masonry-thumb' );
					the_post_thumbnail( 'penci-masonry-thumb' );
				}
				?>
			</a>
			<?php if( ! get_theme_mod('penci_grid_icon_format') ): ?>
				<?php if ( has_post_format( 'video' ) ) : ?>
					<a href="<?php the_permalink() ?>" class="icon-post-format" aria-label="Icon"><?php penci_fawesome_icon('fas fa-play'); ?></a>
				<?php endif; ?>
				<?php if ( has_post_format( 'gallery' ) ) : ?>
					<a href="<?php the_permalink() ?>" class="icon-post-format" aria-label="Icon"><?php penci_fawesome_icon('far fa-image'); ?></a>
				<?php endif; ?>
				<?php if ( has_post_format( 'audio' ) ) : ?>
					<a href="<?php the_permalink() ?>" class="icon-post-format" aria-label="Icon"><?php penci_fawesome_icon('fas fa-music'); ?></a>
				<?php endif; ?>
				<?php if ( has_post_format( 'link' ) ) : ?>
					<a href="<?php the_permalink() ?>" class="icon-post-format" aria-label="Icon"><?php penci_fawesome_icon('fas fa-link'); ?></a>
				<?php endif; ?>
				<?php if ( has_post_format( 'quote' ) ) : ?>
					<a href="<?php the_permalink() ?>" class="icon-post-format" aria-label="Icon"><?php penci_fawesome_icon('fas fa-quote-left'); ?></a>
				<?php endif; ?>
			<?php endif; ?>
		</div>
	<?php endif; ?>

	<div class="grid-header-box">
		<?php if ( ! get_theme_mod( 'penci_grid_cat' ) ) : ?>
			<span class="cat"><?php penci_category( '' ); ?></span>
		<?php endif; ?>
		<<?php echo $heading_title_tag;?> class="penci-entry-title entry-title grid-title <?php echo $heading_title_tag_class;?>"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></<?php echo $heading_title_tag;?>>
		<?php do_action( 'penci_after_post_title' ); ?>
		<?php penci_soledad_meta_schema(); ?>
		<?php $hide_readtime = get_theme_mod( 'penci_grid_readingtime' ); ?>
		<?php if ( ! get_theme_mod( 'penci_grid_date' ) || ! get_theme_mod( 'penci_grid_author' ) || get_theme_mod( 'penci_grid_countviews' ) || get_theme_mod( 'penci_grid_comment_other' ) || penci_isshow_reading_time( $hide_readtime ) ) : ?>
			<div class="grid-post-box-meta">
				<?php do_action( 'penci_grid_meta' ); ?>
				<?php if ( ! get_theme_mod( 'penci_grid_author' ) ) : ?>
					<span class="otherl-date-author author-italic author vcard"><?php echo penci_get_setting('penci_trans_by'); ?> <?php if ( function_exists( 'coauthors_posts_links' ) ) :
							penci_coauthors_posts_links();
						else: ?>
                            <a class="author-url url fn n"
                               href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>"><?php the_author(); ?></a>
						<?php endif; ?></span>
				<?php endif; ?>
				<?php if ( ! get_theme_mod( 'penci_grid_date' ) ) : ?>
					<span class="otherl-date"><?php penci_soledad_time_link(); ?></span>
				<?php endif; ?>
				<?php if ( get_theme_mod( 'penci_grid_comment_other' ) ) : ?>
					<span class="otherl-comment"><a href="<?php comments_link(); ?> "><?php comments_number( '0 ' . penci_get_setting( 'penci_trans_comments' ), '1 '. penci_get_setting( 'penci_trans_comment' ), '% ' . penci_get_setting( 'penci_trans_comments' ) ); ?></a></span>
				<?php endif; ?>
				<?php
				if ( get_theme_mod( 'penci_grid_countviews' ) ) {
					echo '<span>';
					echo penci_get_post_views( get_the_ID() );
					echo ' ' . penci_get_setting( 'penci_trans_countviews' );
					echo '</span>';
				}
				?>
				<?php if( penci_isshow_reading_time( $hide_readtime ) ): ?>
					<span class="otherl-readtime"><?php penci_reading_time(); ?></span>
				<?php endif; ?>
				<?php do_action( 'penci_extra_meta' ); ?>
			</div>
		<?php endif; ?>
	</div>

	<?php if( get_the_excerpt() && ! get_theme_mod( 'penci_grid_remove_excerpt' ) ): ?>
		<div <?php echo penci_excerpt_link(); ?>class="item-content entry-content">
			<?php 
			do_action( 'penci_before_post_excerpt' );
			if( ! get_theme_mod( 'penci_excerptcharac' ) ){
				the_excerpt();
			} else {
				$excerpt_length = get_theme_mod( 'penci_post_excerpt_length', 30 );
				penci_the_excerpt( $excerpt_length, $post_id );
			}
			?>
		</div>
	<?php endif; ?>

	<?php if( get_theme_mod( 'penci_grid_add_readmore' ) ): 
	$class_button = '';
	if( get_theme_mod( 'penci_grid_remove_arrow' ) ): $class_button .= ' penci-btn-remove-arrow'; endif;
	if( get_theme_mod( 'penci_grid_readmore_button' ) ): $class_button .= ' penci-btn-make-button'; endif;
	if( get_theme_mod( 'penci_grid_readmore_align' ) ): $class_button .= ' penci-btn-align-' . get_theme_mod( 'penci_grid_readmore_align' ); endif;
	?>
		<div class="penci-readmore-btn<?php echo $class_button; ?>">
			<a class="penci-btn-readmore" href="<?php the_permalink(); ?>"><?php echo penci_get_setting( 'penci_trans_read_more' ); ?><?php penci_fawesome_icon('fas fa-angle-double-right'); ?></a>
		</div>
	<?php endif; ?>

	<?php if ( ! get_theme_mod( 'penci_grid_share_box' ) ) : ?>
		<div class="penci-post-box-meta penci-post-box-grid">
			<div class="penci-post-share-box">
				<?php echo penci_getPostLikeLink( get_the_ID() ); ?>
				<?php penci_soledad_social_share( );  ?>
			</div>
		</div>
	<?php endif; ?>
</article>

<?php 
if( isset( $infeed_ads ) && $infeed_ads ){
	penci_get_markup_infeed_ad(
		array(
			'wrapper' => 'article',
			'classes'      => 'item item-masonry grid-masonry penci-infeed-data',
			'fullwidth'	 => $infeed_full,
			'order_ad'   => $infeed_num,
			'order_post' => $j,
			'code'       => $infeed_ads,
			'echo'       => true
		)
	); 
}
?>
<?php $j++; ?>
