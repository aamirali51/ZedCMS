<?php
if ( get_theme_mod( 'penci_disable_breadcrumb' ) ) {
	return;
}
$yoast_breadcrumb = $rm_breadcrumb = '';
$extra_class      = '';
$single_style     = penci_get_single_style();
if ( get_theme_mod( 'penci_move_breadcrumbs' ) && in_array( $single_style, array( 'style-8', 'style-12' ) ) ) {
	$extra_class = ' penci-crumb-inside';
}
if ( function_exists( 'yoast_breadcrumb' ) ) {
	$yoast_breadcrumb = yoast_breadcrumb( '<div class="container penci-container-inside penci-breadcrumb single-breadcrumb' . $extra_class . '">', '</div>', false );
}

if ( function_exists( 'rank_math_get_breadcrumbs' ) ) {
	$rm_breadcrumb = rank_math_get_breadcrumbs( [
		'wrap_before' => '<div class="container penci-container-inside penci-breadcrumb single-breadcrumb' . $extra_class . '"><nav aria-label="breadcrumbs" class="rank-math-breadcrumb">',
		'wrap_after'  => '</nav></div>',
	] );
}

if ( $rm_breadcrumb ) {
	echo $rm_breadcrumb;
} elseif ( $yoast_breadcrumb ) {
	echo $yoast_breadcrumb;
} else { ?>
    <div class="container penci-breadcrumb single-breadcrumb<?php echo $extra_class; ?>">
        <span><a class="crumb"
                 href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php echo penci_get_setting( 'penci_trans_home' ); ?></a></span><?php penci_fawesome_icon( 'fas fa-angle-right' ); ?>
		<?php
		if ( get_theme_mod( 'enable_pri_cat_yoast_seo' ) ) {
			$primary_term = penci_get_wpseo_primary_term();

			if ( $primary_term ) {
				echo $primary_term;
			} else {
				$penci_cats = get_the_category( get_the_ID() );
				$penci_cat  = array_shift( $penci_cats );
				echo penci_get_category_parents( $penci_cat );
			}
		} else {
			$penci_cats = get_the_category( get_the_ID() );
			$penci_cat  = array_shift( $penci_cats );
			echo penci_get_category_parents( $penci_cat );
		}
		if ( ! get_theme_mod( 'penci_hide_post_title_breadcrumb' ) ) {
			echo '<span>' . get_the_title() . '</span>';
		}
		?>
    </div>
<?php } ?>
