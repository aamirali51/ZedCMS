<?php
/**
 * Display sign-up mailchimp form below the header
 * Check if 'header-signup-form' has widget, if true display it
 *
 * @since 2.0
 */
if ( is_active_sidebar( 'header-signup-form' ) && get_theme_mod( 'penci_move_signup_below' ) && ! is_page_template( array( 'page-fullwidth.php' ) ) ):
	if( ! get_theme_mod( 'penci_move_signup_full_width' ) ){
		?>
		<div class="container penci-header-signup-form penci-header-signup-form-below">
			<?php dynamic_sidebar( 'header-signup-form' ); ?>
		</div>
	<?php } else { ?>
		<div class="penci-header-signup-form penci-header-signup-form-below">
			<div class="container">
				<?php dynamic_sidebar( 'header-signup-form' ); ?>
			</div>
		</div>
	<?php } ?>
<?php endif; ?>