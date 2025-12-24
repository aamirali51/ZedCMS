<?php
/**
 * The template for displaying single pages.
 *
 * @since 1.0
 */
$block_style = get_theme_mod('penci_blockquote_style') ? get_theme_mod('penci_blockquote_style') : 'style-1';
$heading_style = penci_get_heading_style();
?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<div class="post-entry <?php echo 'blockquote-'. $block_style; ?> <?php echo $heading_style; ?>">
		<div class="inner-post-entry entry-content">
			<?php the_content(); ?>
			
			<div class="penci-single-link-pages">
			<?php wp_link_pages(); ?>
			</div>
			
			<?php if ( ! get_theme_mod( 'penci_post_tags' ) && has_tag() ) : ?>
				<?php if ( is_single() ) : ?>
					<div class="post-tags">
						<?php the_tags( wp_kses( '', penci_allow_html() ), "", "" ); ?>
					</div>
				<?php endif; ?>
			<?php endif; ?>
		</div>
	</div>


</article>