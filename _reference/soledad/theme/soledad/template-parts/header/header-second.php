<?php $header_class_main = penci_soledad_wpheader_classes(); ?>
<header id="header" class="penci-header-second <?php echo esc_attr( $header_class_main ); ?>" <?php if( ! get_theme_mod('penci_schema_wphead') ): ?>itemscope="itemscope" itemtype="https://schema.org/WPHeader"<?php endif;?>>
	<?php if ( ! get_theme_mod( 'penci_vertical_nav_remove_header' ) ): ?>
		<div class="inner-header">
			<div class="<?php penci_soledad_get_header_width(); ?>">
				<div id="logo">
					<?php get_template_part( 'template-parts/header/logo' ); ?>
					<?php if ( ( is_home() || is_front_page() ) && get_theme_mod( 'penci_home_h1content' ) ): echo '<h1 class="penci-hide-tagupdated">' . get_theme_mod( 'penci_home_h1content' ) . '</h1>'; endif; ?>
				</div>

				<?php if ( penci_get_setting( 'penci_header_slogan_text' ) ) : ?>
					<div class="header-slogan">
						<div class="header-slogan-text"><?php echo penci_get_setting( 'penci_header_slogan_text' ); ?></div>
					</div>
				<?php endif; ?>

				<?php if ( ! get_theme_mod( 'penci_header_social_check' ) ) : ?>
					<div class="header-social<?php if ( get_theme_mod( 'penci_header_social_brand' ) ): echo ' penci-social-textcolored'; endif; ?>">
						<?php get_template_part( 'inc/modules/socials' ); ?>
					</div>
				<?php endif; ?>
			</div>
		</div>
	<?php endif; ?>
</header>
<!-- end #header -->