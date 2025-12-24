<?php
/**
 * This is content page will display in loop of page.php file
 * Don't edit this file, let create child theme and override it
 *
 * @since 1.0
 */
$pagetitle   = get_post_meta( $post->ID, 'penci_page_display_title', true );
$sharebox    = get_post_meta( $post->ID, 'penci_page_sharebox', true );
$block_style = get_theme_mod( 'penci_blockquote_style' ) ? get_theme_mod( 'penci_blockquote_style' ) : 'style-1';
$heading_style = penci_get_heading_style();

$show_page_title = penci_is_pageheader();
$flag_ptitle     = true;
if ( get_theme_mod( 'penci_page_hide_ptitle' ) ) {
	$flag_ptitle = false;
}
if ( $pagetitle == 'yes' ) {
	$flag_ptitle = true;
} else if ( $pagetitle == 'no' ) {
	$flag_ptitle = false;
}
$simage_size = 'penci-full-thumb';
if ( penci_is_mobile() ) {
	$simage_size = 'penci-masonry-thumb';
}
?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php if ( ! $show_page_title ): ?>
		<?php if ( get_the_title() && $flag_ptitle ): ?>
            <div class="penci-page-header">
                <h1 class="entry-title"><?php the_title(); ?></h1>
            </div>
		<?php endif; ?>
	<?php endif; ?>
	<?php do_action( 'penci_after_post_title' ); ?>
	<?php penci_soledad_meta_schema(); ?>

	<?php if ( function_exists( 'has_post_thumbnail' ) && has_post_thumbnail() && ! get_theme_mod( 'penci_page_hide_featured_image' ) ) :
		$thumb_alt = $thumb_title_html = '';
		$thumb_id = get_post_thumbnail_id( get_the_ID() );
		$thumb_alt = penci_get_image_alt( $thumb_id, get_the_ID() );
		$thumb_title_html = penci_get_image_title( $thumb_id );
		?>
        <div class="post-image">
			<?php if ( get_theme_mod( 'penci_speed_disable_first_screen' ) || ! get_theme_mod('penci_disable_lazyload_fsingle') ) {
				$img_w = penci_get_image_data_based_post_id( get_the_ID(), $simage_size, 'w', false );
				$img_h = penci_get_image_data_based_post_id( get_the_ID(), $simage_size, 'h', false );
				?>
                <img class="attachment-penci-full-thumb size-penci-full-thumb penci-lazy wp-post-image"
                     src="<?php echo penci_holder_image_base( $img_w, $img_h ); ?>"
                     width="<?php echo $img_w; ?>"
                     height="<?php echo $img_h; ?>"
                     alt="<?php echo $thumb_alt; ?>"<?php echo $thumb_title_html; ?>
                     data-sizes="<?php echo penci_image_datasize( $simage_size , 'penci-masonry-thumb' ); ?>"
                     data-srcset="<?php echo penci_image_img_srcset( get_the_ID(), $simage_size ,'penci-masonry-thumb' ); ?>"
                     data-src="<?php echo penci_get_featured_image_size( get_the_ID(), $simage_size ); ?>">
			<?php } else { ?>
				<?php the_post_thumbnail( $simage_size ); ?>
			<?php } ?>
        </div>
	<?php endif; ?>

    <div class="post-entry <?php echo $heading_style; ?> <?php echo 'blockquote-' . $block_style; ?><?php if ( get_theme_mod( 'penci_page_comments' ) && get_theme_mod( 'penci_page_share' ) ): echo ' page-has-margin'; endif; ?>">
        <div class="inner-post-entry entry-content">
			<?php the_content(); ?>
			<?php wp_link_pages(); ?>
        </div>
    </div>

	<?php if ( ! get_theme_mod( 'penci_page_share' ) && ( 'no' != $sharebox ) ) : ?>
		<?php get_template_part( 'template-parts/single-meta-comment' ); ?>
	<?php endif; ?>

	<?php if ( ! get_theme_mod( 'penci_page_comments' ) ) : ?>
		<?php comments_template( '', true ); ?>
	<?php endif; ?>

</article>
