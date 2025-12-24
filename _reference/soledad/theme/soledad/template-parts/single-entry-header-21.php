<?php
$style_cscount     = get_theme_mod( 'penci_single_style_cscount' );
$style_cscount     = $style_cscount ? $style_cscount : 's1';
$single_style      = penci_get_single_style();
$move_title_bellow = get_theme_mod( 'penci_move_title_bellow' );
?>
    <div class="header-standard header-classic single-header">
        <div class="header-standard-wrapper">
			<?php do_action( 'penci_before_main_post_title' ); ?>
			<?php if ( ! get_theme_mod( 'penci_post_cat' ) ) : ?>
                <div class="penci-standard-cat penci-single-cat"><span class="cat"><?php penci_category( '' ); ?></span>
                </div>
			<?php endif; ?>

			<?php $hide_readtime = get_theme_mod( 'penci_single_hreadtime' ); ?>
			<?php if ( ! get_theme_mod( 'penci_single_meta_author' ) || ! get_theme_mod( 'penci_single_meta_date' ) || ! get_theme_mod( 'penci_single_meta_comment' ) || get_theme_mod( 'penci_single_show_cview' ) || penci_isshow_reading_time( $hide_readtime ) ) : ?>
                <div class="post-box-meta-single">
					<?php penci_author_update_name(); ?>
					<?php if ( ! get_theme_mod( 'penci_single_meta_date' ) ) : ?>
                        <span><?php penci_soledad_time_link( 'single' ); ?></span>
					<?php endif; ?>
					<?php if ( ! get_theme_mod( 'penci_single_meta_comment' ) && 's1' != $style_cscount ) : ?>
                        <span><?php comments_number( '0 ' . penci_get_setting( 'penci_trans_comments' ), '1 ' . penci_get_setting( 'penci_trans_comment' ), '% ' . penci_get_setting( 'penci_trans_comments' ) ); ?></span>
					<?php endif; ?>
					<?php if ( get_theme_mod( 'penci_single_show_cview' ) ) : ?>
                        <span><i class="penci-post-countview-number"><?php echo penci_get_post_views( get_the_ID() ); ?></i> <?php echo penci_get_setting( 'penci_trans_countviews' ); ?></span>
					<?php endif; ?>
					<?php if ( penci_isshow_reading_time( $hide_readtime ) ) : ?>
                        <span class="single-readtime"><?php penci_reading_time(); ?></span>
					<?php endif; ?>
					<?php do_action( 'penci_single_meta_content' ); ?>
					<?php
					if ( get_the_post_thumbnail_caption() && get_theme_mod( 'penci_post_thumb_caption' ) && in_array(
							$single_style,
							array(
								'style-5',
								'style-6',
								'style-8',
							)
						) && ! $move_title_bellow ) {
						echo '<span class="penci-featured-caption penci-fixed-caption penci-caption-relative">' . get_the_post_thumbnail_caption() . '</span>';
					}
					?>
                </div>
			<?php endif; ?>
        </div>

        <h1 class="post-title single-post-title entry-title"><?php the_title(); ?></h1>
		<?php penci_display_post_subtitle(); ?>
		<?php penci_soledad_meta_schema(); ?>

		<?php
		$recipe_title = get_post_meta( get_the_ID(), 'penci_recipe_title', true );
		if ( has_shortcode( get_the_content(), 'penci_recipe' ) || $recipe_title ) {
			do_action( 'penci_recipes_action_hook' );
		}
		?>

		<?php if ( ! get_theme_mod( 'penci_single_meta_author' ) ) : ?>
            <div class="penci-author-img-wrapper">
				<span
                        class="author vcard">
                       
						   <?php echo '<div class="penci-author-wrapper"><a href="'.get_author_posts_url( get_current_user_id() ).'">' . get_avatar( get_current_user_id(), 100 ) . '</a></div>';?>
						   <span class="author-name"><?php the_author(); ?></span>
						
				</span>
            </div>
		<?php endif; ?>

		<?php do_action( 'penci_after_main_post_title' ); ?>
    </div>
<?php
$single_sstyle     = get_theme_mod( 'penci_single_style' );
$move_title_bellow = get_theme_mod( 'penci_move_title_bellow' );
$show              = true;
if ( ! $move_title_bellow && in_array(
		$single_sstyle,
		array(
			'style-5',
			'style-6',
			'style-8',
			'style-10',
			'style-13',
			'style-22',
		)
	) ) {
	$show = false;
}
if ( $show && ( 'btitle-bcontent' == get_theme_mod( 'penci_single_poslcscount' ) || 'btitle' == get_theme_mod( 'penci_single_poslcscount' ) ) ) {
	get_template_part( 'template-parts/single-meta-comment-top' );
}
