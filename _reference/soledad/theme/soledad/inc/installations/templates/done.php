<?php
update_option( 'soledad_setup_status', 'done', false );
?>
<div class="soledad-wizard-content-inner soledad-wizard-done">
	<div class="soledad-wizard-logo">
		<img src="<?php echo esc_url( PENCI_SOLEDAD_URL . '/images/penci-logo-themeforest.png' ); ?>" alt="logo">
	</div>

	<h3>
		<?php esc_html_e( 'Everything is ready!', 'soledad' ); ?>
	</h3>

	<p>
		<?php
		esc_html_e(
			'Congratulations! The theme has been installed successfully, and you’re all set to begin creating your stunning website. Enjoy full control over design and layout, allowing you to build a site that truly reflects your unique vision.',
			'soledad'
		);
		?>
	</p>

	<div class="soledad-wizard-buttons">
		<a class="penci-install-btn penci-install-color-alt penci-install-i-view" href="<?php echo esc_url( admin_url() ); ?>">
			<?php esc_html_e( 'Return to Dashboard', 'soledad' ); ?>
		</a>
		
		<a class="penci-install-btn penci-install-color-primary penci-install-i-view" href="<?php echo esc_url( get_home_url() ); ?>">
			<?php esc_html_e( 'View Site', 'soledad' ); ?>
		</a>

		<a class="penci-install-inline-btn penci-install-i-theme-settings" href="<?php echo esc_url( admin_url( 'customize.php' ) ); ?>">
			<?php esc_html_e( 'Customizations', 'soledad' ); ?>
		</a>
	</div>
</div>