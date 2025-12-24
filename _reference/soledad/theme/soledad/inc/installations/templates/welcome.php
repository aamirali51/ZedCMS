<div class="soledad-wizard-content-inner soledad-wizard-welcome">

	<div class="soledad-wizard-logo">
		<img class="soledad-wizard-logo" src="<?php echo esc_url( PENCI_SOLEDAD_URL . '/images/penci-logo-themeforest.png' ); ?>" alt="logo">
		</div>

	<h3>
		<?php esc_html_e( 'Thank you for choosing our theme!', 'soledad' ); ?>
	</h3>

	<p>
		<?php
		esc_html_e(
			'The theme has been installed successfully, and you\'re ready to start building your awesome website. With full control over layout and design, you can create a site that truly reflects your vision.',
			'soledad'
		);
		?>
	</p>

	<p>
		<?php
		esc_html_e(
			'Take a look at the next steps to get started, and enjoy the process of launching your new project. If you have any questions, don’t hesitate to reach out—we’re always happy to help.',
			'soledad'
		);
		?>
	</p>

	<p class="soledad-wizard-signature">
		<span>
			<?php esc_html_e( 'Wishing you all the best with your new website!', 'soledad' ); ?>
		</span>
	</p>

	<div class="soledad-wizard-buttons">
		<a class="penci-install-inline-btn penci-install-style-underline" href="<?php echo esc_url( admin_url( 'admin.php?page=soledad_dashboard_welcome&skip_setup' ) ); ?>">
			<?php esc_html_e( 'Skip setup', 'soledad' ); ?>
		</a>

		<a class="penci-install-btn penci-install-color-primary penci-install-next" href="<?php echo esc_url( $this->get_page_url( 'activation' ) ); ?>">
			<?php esc_html_e( 'Let\'s start', 'soledad' ); ?>
		</a>
	</div>

</div>
