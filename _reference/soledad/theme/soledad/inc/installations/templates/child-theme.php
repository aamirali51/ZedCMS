<div class="soledad-wizard-content-inner soledad-wizard-child-theme<?php echo is_child_theme() ? ' soledad-installed' : ''; ?>">

    <div class="soledad-child-theme-response"></div>

    <h3>
		<?php esc_html_e( 'Setup Soledad Child Theme', 'soledad' ); ?>
    </h3>

    <p>
		<?php
    if ( is_child_theme() ) {
        esc_html_e( 'You have already installed the child theme. You can now customize it as you like.', 'soledad' );
    } else {
        esc_html_e( 'The Soledad Child Theme is a WordPress theme that inherits the functionality and styling of the Soledad Parent Theme, allowing you to make customizations without affecting the parent theme.', 'soledad' );
    }
    ?>
    </p>

    <div class="soledad-theme-images">
        <div class="soledad-main-image">
            <img src="<?php echo PENCI_SOLEDAD_URL . '/screenshot.png'; ?>" alt="parent">
        </div>
        <div class="soledad-child-image">
          <img src="<?php echo PENCI_SOLEDAD_URL . '/screenshot.png'; ?>" alt="child">
        </div>
        <span class="soledad-child-checkmark"></span>
    </div>

    <p>
		<?php
		esc_html_e(
			'If you are going to make changes to the theme source code please use the Child Theme rather than modifying the main theme HTML/CSS/PHP code. This allows the parent theme to receive updates without overwriting your source code changes. Use the button below to create and activate the Child Theme.',
			'soledad'
		);
		?>
    </p>

    <a href="#" class="soledad-btn soledad-color-primary soledad-install-child-theme">
		  <?php esc_html_e( 'Install child theme', 'soledad' ); ?>
    </a>
</div>

<div class="soledad-wizard-footer">
	<?php $this->get_prev_button( 'activation' ); ?>
    <div>
		<?php $this->get_next_button( 'plugins' ); ?>
		<?php $this->get_skip_button( 'plugins' ); ?>
    </div>
</div>