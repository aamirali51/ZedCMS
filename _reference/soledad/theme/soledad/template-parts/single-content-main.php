<?php
/**
 * The template for displaying single pages.
 *
 * @since 1.0
 */
$block_style = get_theme_mod( 'penci_blockquote_style' ) ? get_theme_mod( 'penci_blockquote_style' ) : 'style-1';
$heading_style = penci_get_heading_style();
$thumb_alt   = $thumb_title_html = '';

if ( has_post_thumbnail() ) {
	$thumb_id         = get_post_thumbnail_id( get_the_ID() );
	$thumb_alt        = penci_get_image_alt( $thumb_id, get_the_ID() );
	$thumb_title_html = penci_get_image_title( $thumb_id );
}

$single_style = penci_get_single_style();
?>
<article id="post-<?php the_ID(); ?>" class="post type-post status-publish<?php do_action( 'penci_post_class' ); ?>">
	<?php if ( 'style-8' == $single_style ): ?>
		<?php
		$single_magazine = 'container-single penci-single-style-8  penci-header-text-white';
		if ( get_theme_mod( 'penci_home_layout' ) == 'magazine-1' || get_theme_mod( 'penci_home_layout' ) == 'magazine-2' ) {
			$single_magazine .= ' container-single-magazine';
		}
		?>
        <div class="<?php echo( $single_magazine ); ?>">
			<?php
			$post_format = get_post_format();
			if ( ! get_theme_mod( 'penci_move_title_bellow' ) && get_theme_mod( 'penci_post_thumb' ) && ! in_array( $post_format, array(
					'link',
					'quote',
					'gallery',
					'video'
				) ) ) {
				get_template_part( 'template-parts/single', 'entry-header' );
			} else {
				get_template_part( 'template-parts/single', 'post-format2' );
			}
			?>
        </div>
		<?php if ( get_theme_mod( 'penci_post_adsense_one' ) ): ?>
            <div class="penci-google-adsense-1">
				<?php echo do_shortcode( get_theme_mod( 'penci_post_adsense_one' ) ); ?>
            </div>
		<?php endif; ?>
	<?php endif; ?>
	<?php if ( get_theme_mod( 'penci_move_title_bellow' ) && ! in_array( $single_style, ['style-10','style-11','style-12','style-13','style-15','style-20','style-21','style-22'] ) ): ?>
		<?php
		if ( 'style-8' != $single_style ) {
			get_template_part( 'template-parts/single', 'breadcrumb-inner' );
		}

		get_template_part( 'template-parts/single', 'entry-header' );
		?>

		<?php if ( get_theme_mod( 'penci_post_adsense_one' ) ): ?>
            <div class="penci-google-adsense-1">
				<?php echo do_shortcode( get_theme_mod( 'penci_post_adsense_one' ) ); ?>
            </div>
		<?php endif; ?>

	<?php endif; /* End check if not move title bellow featured image */ ?>

	<?php
	$share_show         = false;
	$single_style       = get_theme_mod( 'penci_single_style' );
	$single_poslcscount = penci_get_setting( 'penci_single_poslcscount' );
	if ( 'above-content' == $single_poslcscount || 'abovebelow-content' == $single_poslcscount ) {
		$share_show = true;
	}
	if ( $share_show ) {
		get_template_part( 'template-parts/single-meta-comment-top' );
	}
	if ( 'sticky-left' == $single_poslcscount ) {
		get_template_part( 'template-parts/single', 'meta-sticky' );
	}
	?>

    <div class="post-entry <?php echo $heading_style; ?> <?php echo 'blockquote-' . $block_style; ?>">
        <div class="inner-post-entry entry-content" id="penci-post-entry-inner">

			<?php do_action( 'penci_action_before_the_content' ); ?>

			<?php
			if ( get_theme_mod( 'penci_single_delayed_content' ) && penci_is_mobile() ) {
				?>
				<div data-id="section-p-content-<?php echo get_the_ID();?>" data-url="<?php echo esc_url( add_query_arg(['penci_get_delayed_sections' => 'content'],get_permalink()) );?>" class="pcfb-single-content-delayed pc-content-delayed"></div>
				<?php
			} else {

				$smartlists_enable = get_post_meta( get_the_ID(), 'pcsml_smartlists_enable', true );
				$smartlists_style  = get_post_meta( get_the_ID(), 'pcsml_smartlists_style', true );
				$smartlists_h      = get_post_meta( get_the_ID(), 'pcsml_smartlists_h', true );
				$smartlists_order  = get_post_meta( get_the_ID(), 'pcsml_smartlists_order', true );

				if ( 'yes' == $smartlists_enable ) {
					$content            = get_the_content();
					$content            = apply_filters( 'the_content', $content );
					$content            = str_replace( ']]>', ']]&gt;', $content );
					remove_filter( 'the_content', 'penci_insert_post_content_ads' );
					add_filter('penci_toc_maybe_apply_the_content_filter',function(){
						return false;
					},10);
					$smartlists_content = penci_smartlists( [
						'style'        => $smartlists_style,
						'content'      => $content,
						'order'        => $smartlists_order,
						'h'            => $smartlists_h,
						'sm_title_tag' => $smartlists_h,
					] );
					if ( $smartlists_content ) {
						echo '<div class="pcsml-el pcsml-customized-ver scmchck">' . $smartlists_content . '</div>';
					}
				} else {
					the_content();
				}
			}
			?>

			<?php do_action( 'penci_action_after_the_content' ); ?>

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

			<?php do_action( 'penci_end_single_content' ); ?>
        </div>
    </div>

	<?php if ( get_theme_mod( 'penci_post_adsense_two' ) ): ?>
        <div class="penci-google-adsense-2">
			<?php echo do_shortcode( get_theme_mod( 'penci_post_adsense_two' ) ); ?>
        </div>
	<?php endif; ?>

	<?php
	if ( 'below-content' == $single_poslcscount || 'abovebelow-content' == $single_poslcscount || 'btitle-bcontent' == $single_poslcscount ) {
		get_template_part( 'template-parts/single', 'meta-comment' );
	}
	?>

	<?php if ( get_theme_mod( 'penci_related_post_popup' ) ) : ?>
        <div class="penci-flag-rlt-popup"></div><?php endif; ?>

	<?php
	if ( get_theme_mod( 'penci_single_sec_delayed' ) ) {
		$is_log = is_user_logged_in() ? 'log' : 'no-log';
		?>
		<div data-id="section-p-<?php echo get_the_ID().'-'.$is_log;?>" data-url="<?php echo esc_url( add_query_arg(['penci_get_delayed_sections' => 'sections'],get_permalink()) );?>" class="pcfb-single-sections-delayed pc-content-delayed"></div>
		<?php
	} else {
		get_template_part( 'template-parts/single-sections' );
	}
	?>

</article>
