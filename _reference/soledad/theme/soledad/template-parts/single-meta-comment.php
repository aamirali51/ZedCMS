<?php
$style_cscount = get_theme_mod( 'penci_single_style_cscount', 's1' );
$style_bottom_cscount = get_theme_mod( 'penci_single_style_bottom_cscount' );
$style_cscount = $style_bottom_cscount && is_single() ? $style_bottom_cscount : $style_cscount;
$align_cscount = is_page() ? 'penci_page_align_cscount' : 'penci_post_align_cscount';
if ( is_page() ) {
	$page_cscount  = get_theme_mod( 'penci_page_style_cscount', $style_cscount );
	$style_cscount = ! empty( $page_cscount ) ? $page_cscount : $style_cscount;
}
$bio_style = get_theme_mod( 'penci_authorbio_style' ) ? get_theme_mod( 'penci_authorbio_style' ) : 'style-1';

$wrapper_class    = array();
$wrapper_class[]  = 'tags-share-box';
$wrapper_class[]  = is_page() ? 'page-share hide-tags' : 'single-post-share';
$wrapper_class[]  = 'tags-share-box-' . $style_cscount;
$wrapper_class_c1 = 's1' == $style_cscount ? ' center-box' : ' tags-share-box-2_3';
$wrapper_class[]  = strpos( $style_cscount, 'n' ) !== false ? ' pcnew-share' : $wrapper_class_c1;
$wrapper_class[]  = $bio_style == 'style-4' ? 'share-box-border-bot' : '';
$wrapper_class[]  = 'social-align-' . get_theme_mod( strval( $align_cscount ), 'default' );

if ( is_single() && get_theme_mod( 'penci_post_share_disbtnplus', true ) ) {
	$wrapper_class[] = 'disable-btnplus';
}

if ( is_page() && get_theme_mod( 'penci_page_share_disbtnplus', true ) ) {
	$wrapper_class[] = 'disable-btnplus';
}

if ( in_array( $style_cscount, [ 'n1', 'n3', 'n5', 'n8', 'n9', 'n10', 'n11', 'n12', 'n13', 'n19', 'n20' ] ) ) {
	$wrapper_class[] = ' penci-social-colored';
}

if ( in_array( $style_cscount, [
	'n1',
	'n3',
	'n5',
	'n8',
	'n9',
	'n10',
	'n11',
	'n12',
	'n13',
	'n14',
	'n16',
	'n19',
	'n20'
] ) ) {
	$wrapper_class[] = ' penci-icon-full';
}

if ( in_array( $style_cscount, [ 'n2', 'n4', 'n6', 'n7', 'n9', 'n11', 'n13' ] ) ) {
	$wrapper_class[] = ' tags-share-box-s2';
}

if ( in_array( $style_cscount, [ 'n2', 'n4', 'n6', 'n7', 'n9', 'n11', 'n13', 'n15', 'n17', 'n18', 'n19', 'n20' ] ) ) {
	$wrapper_class[] = ' show-txt';
}

if ( in_array( $style_cscount, [ 'n3', 'n4', 'n18' ] ) ) {
	$wrapper_class[] = ' rounder';
}

if ( in_array( $style_cscount, [ 'n5', 'n6', 'n10', 'n11' ] ) ) {
	$wrapper_class[] = ' show-shadow';
}

if ( in_array( $style_cscount, [ 'n7' ] ) ) {
	$wrapper_class[] = ' focus-icon';
}

if ( in_array( $style_cscount, [ 'n8', 'n9', 'n10', 'n11', 'n12', 'n13' ] ) ) {
	$wrapper_class[] = ' size-large';
}

if ( in_array( $style_cscount, [ 'n9', 'n11', 'n13' ] ) ) {
	$wrapper_class[] = ' txt-below';
}

if ( in_array( $style_cscount, [ 'n12', 'n13' ] ) ) {
	$wrapper_class[] = ' no-spacing';
}

if ( in_array( $style_cscount, [ 'n14', 'n15' ] ) ) {
	$wrapper_class[] = ' black-ver';
}

if ( in_array( $style_cscount, [ 'n16', 'n17', 'n18', 'n19', 'n20' ] ) ) {
	$wrapper_class[] = ' border-style';
}

if ( in_array( $style_cscount, [ 'n16', 'n17', 'n18' ] ) ) {
	$wrapper_class[] = ' penci-social-textcolored';
}

if ( in_array( $style_cscount, [ 'n19', 'n20' ] ) ) {
	$wrapper_class[] = ' full-border';
}

$show_comments = $show_socials = false;

if ( penci_soledad_social_share( '', false ) ) {
	$show_socials = true;
} else {
	$wrapper_class[] = ' no-social-enabled';
}

$show_comments_option = ! get_theme_mod( 'penci_single_meta_comment' );

if ( $show_comments_option && 's1' == $style_cscount && is_singular( 'post' ) ) {
	$show_comments = true;
	$show_socials  = true;
} else if ( get_theme_mod( 'penci_post_share' ) ) {
	$show_socials = false;
}

?>
<?php if ( ( $show_comments_option || ! get_theme_mod( 'penci_post_share' ) ) && $show_socials ): ?>
    <div class="<?php echo esc_attr( implode( ' ', $wrapper_class ) ); ?> post-share<?php if ( get_theme_mod( 'penci__hide_share_plike' ) ): echo ' hide-like-count'; endif; ?>">
		<?php
		if ( 's1' != $style_cscount || is_page() ) {
			echo '<span class="penci-social-share-text">';
			if ( get_theme_mod( 'penci_trans_share' ) ) {
				echo do_shortcode( get_theme_mod( 'penci_trans_share' ) );
			} else {
				echo '<i class="penciicon-sharing"></i>';
				esc_html_e( 'Share', 'soledad' );
			}
			echo '</span>';
		}
		?>
		<?php if ( $show_comments ) : ?>
            <span class="single-comment-o<?php if ( get_theme_mod( 'penci_post_share' ) ) : echo ' hide-comments-o'; endif; ?>"><?php penci_fawesome_icon( 'far fa-comment' ); ?><?php comments_number( '0 ' . penci_get_setting( 'penci_trans_comments' ), '1 ' . penci_get_setting( 'penci_trans_comment' ), '% ' . penci_get_setting( 'penci_trans_comments' ) ); ?></span>
		<?php endif; ?>

		<?php if ( ! get_theme_mod( 'penci_post_share' ) ) : ?>
			<?php if ( ! get_theme_mod( 'penci__hide_share_plike' ) && ! is_page() ): ?>
                <span class="post-share-item post-share-plike">
					<?php echo penci_single_getPostLikeLink( get_the_ID() ); ?>
					</span>
			<?php endif; ?>
			<?php penci_soledad_social_share(); ?>
		<?php endif; ?>
    </div>
<?php endif; ?>
