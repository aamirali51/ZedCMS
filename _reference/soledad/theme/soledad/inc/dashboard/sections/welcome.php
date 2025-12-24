<?php
/**
 * Welcome section.
 *
 * @package soledad
 */

$theme_data   = wp_get_theme();
$is_child     = false;
$parent_theme = $theme_data->parent();
if ( ! empty( $parent_theme ) ) {
	$is_child = true;
}

$parent_version = '';
if ( $is_child ) {
	$parent_version = $theme_data->parent()->Version;
}
?>
<h1>
	<?php
	if ( $parent_version ) {
		echo sprintf( __( 'Welcome to %s Child Theme', 'soledad' ), penci_get_theme_name( 'Soledad' ) ) . '<br>';
		echo '<span style="display: block; font-size: 22px; font-weight: 500; margin-top: 10px;">' . esc_html__( sprintf( __( 'Inherit from the %1$s Parent Theme - Version %2$s', 'soledad' ), penci_get_theme_name( 'Soledad' ), $parent_version ) ) . '</span>';
	} else {
		// Translators: %1$s - Theme name, %2$s - Theme version.
		echo esc_html__( sprintf( __( 'Welcome to %1$s - Version %2$s', 'soledad' ), penci_get_theme_name( 'Soledad' ), $theme_data->version ) );
	}
	?>
</h1>
<div class="about-text"><?php echo sprintf( __( "Thank you for purchasing our theme! We're thrilled to have you as a valued customer.<br>You can join other users who love using Soledad to build their websites in <a target='_blank' href='https://www.facebook.com/groups/soledad/'>Soledad Users Group Here</a>. This platform is for sharing, showcasing your work, providing assistance, engaging in discussions, and staying updated.", 'soledad' ), penci_get_theme_author() ); ?>

	<?php
	$admin_wel_page_text = get_theme_mod( 'admin_wel_page_text' );
	if ( $admin_wel_page_text ) {
		echo do_shortcode( wpautop( $admin_wel_page_text ) );
	}
	?>
</div>
<?php if( get_theme_mod('activate_white_label') && ! get_theme_mod('admin_wel_page_author')): ?>
<a rel="nofollow" target="_blank" href="<?php echo esc_url( 'https://pencidesign.net/' ); ?>" class="wp-badge">PenciDesign</a>
<?php endif; ?>
