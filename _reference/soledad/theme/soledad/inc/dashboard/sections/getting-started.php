<?php
/**
 * Getting started section.
 *
 * @package soledad
 */
$theme_data   = wp_get_theme();
$is_child     = false;
$parent_theme = $theme_data->parent();
if ( ! empty( $parent_theme ) ) {
	$is_child = true;
}
$revokelicense = isset( $_GET['penci_revoke_license'] ) ? $_GET['penci_revoke_license'] : '';
$license_nonce = isset( $_GET['revoke_none'] ) ? $_GET['revoke_none'] : '';

if ( ( 'confirm' == $revokelicense ) && ( ( wp_create_nonce( 'revoke_license' ) == $license_nonce ) || 'pencidesign' == $license_nonce ) ) {
	$license_data = get_option( 'penci_soledad_purchased_data' );

	if ( ! empty( $license_data ) ) {
		if ( isset( $license_data['purchase_code'] ) ) {
			$purchased_code = $license_data['purchase_code'];

			$req = wp_remote_post( 'https://license.pencidesign.net/api/revoke', array(
				'headers'     => array(
					'Content-Type' => 'application/json',
				),
				'body'        => wp_json_encode( array(
					'code' => $purchased_code
				) ),
				'data_format' => 'body',
				'sslverify'   => false
			) );

			$body = wp_remote_retrieve_body( $req );
			$res  = json_decode( $body );
			if ( ! empty( $res ) && isset( $res->status ) && $res->status === 'success' ) {
				delete_option( 'penci_soledad_is_activated' );
				delete_option( 'penci_soledad_purchased_data' );
				echo '<span style="display: inline-block; margin-bottom:15px; padding: 3px 10px; border: 1px solid #65b70e; max-width: 330px; background: #67c700; color: #fff; font-size: 16px;">' . __( 'The license for this website has been revoked.', 'soledad' ) . '</span>';
			}
		}
	}
}

if ( isset( $_GET['penci_csync'] ) && $_GET['penci_csync'] == 'parent_to_child' ) {
	global $wp_customize;

	$nonce = isset( $_GET['nonce'] ) ? $_GET['nonce'] : '';

	if ( wp_verify_nonce( $nonce, 'penci_sync_customizer_data' ) ) {

		// Call the customize_save_before action.
		$charset = get_option( 'blog_charset' );
		$mods    = get_theme_mods();
		$data    = array(
			'mods' => $mods ? $mods : array(),
		);

		if ( function_exists( 'wp_get_custom_css_post' ) ) {
			$data['wp_css'] = wp_get_custom_css( 'soledad' );
		}

		// Move it to the current active stylesheet
		if ( function_exists( 'wp_update_custom_css_post' ) && isset( $data['wp_css'] ) && '' !== $data['wp_css'] ) {
			wp_update_custom_css_post( $data['wp_css'] );
		}

		// Call the customize_save action.
		do_action( 'customize_save', $wp_customize );

		// Loop through the mods.
		foreach ( $data['mods'] as $key => $val ) {

			// Call the customize_save_ dynamic action.
			do_action( 'customize_save_' . $key, $wp_customize );

			// Save the mod.
			set_theme_mod( $key, $val );
		}

		// Call the customize_save_after action.
		do_action( 'customize_save_after', $wp_customize );
		set_theme_mod( 'penci_sync_customizer_data', '1' );
	}
}
?>
<h2 class="nav-tab-wrapper">
    <a href="<?php echo admin_url( 'admin.php?page=soledad_dashboard_welcome' ) ?>"
       class="nav-tab nav-tab-active"><?php esc_html_e( 'Getting started', 'soledad' ); ?></a>
    <a href="<?php echo admin_url( 'customize.php' ) ?>"
       class="nav-tab"><?php esc_html_e( 'Customize Style', 'soledad' ); ?></a>
    <a href="<?php echo admin_url( 'admin.php?page=soledad_custom_fonts' ) ?>"
       class="nav-tab"><?php esc_html_e( 'Custom Fonts', 'soledad' ); ?></a>
	<?php if ( function_exists( 'penci_soledad_is_activated' ) && ! penci_soledad_is_activated() ) { ?>
        <a href="<?php echo admin_url( 'admin.php?page=soledad_active_theme' ) ?>"
           class="nav-tab"><?php esc_html_e( 'Active theme', 'soledad' ); ?></a>
	<?php } else if ( penci_soledad_is_activated() && penci_soledad_is_license() ) { ?>
        <a href="<?php echo admin_url( 'admin.php?page=soledad_theme_license' ) ?>"
           class="nav-tab"><?php esc_html_e( 'Theme License', 'soledad' ); ?></a>
	<?php } ?>
    <a href="https://pencidesign.ticksy.com/" target="_blank"
       class="nav-tab"><?php esc_html_e( 'Support Forum', 'soledad' ); ?></a>
</h2>

<div id="getting-started" class="gt-tab-pane gt-is-active penci-dashboard-wapper">
    <div class="feature-section">
        <div class="soledad-install-steps">
            <div class="col">
                <div class="inner-content">
                    <div class="heading-span">Step <span>1</span></div>
                    <div class="heading-icon">
                        <i class="dashicons-before dashicons-admin-plugins"></i>
                    </div>
                    <h3><?php esc_html_e( 'Install & Activate Plugins', 'soledad' ); ?></h3>
                    <p>
						<?php
						/* translators: theme name. */
						echo penci_get_theme_name( 'Soledad' );
						esc_html_e( ' does not require any plugins. All plugins are additional features that you can choose to install and use. Simply install and activate the plugin of your choice. If you want to import a demo, please install and activate the "Penci Soledad Demo Importer" plugin', 'soledad' );
						echo '<br><br>';
						echo esc_html__( 'We recommend checking the theme\'s documentation first to learn how to configure it.', 'soledad' );
						?>
                    </p>
                    <a class="button button-primary"
                       href="https://soledad.pencidesign.net/soledad-document/"
                       target="_blank"><?php esc_html_e( 'View Documentation', 'soledad' ); ?></a>
                    <a class="button button-primary"
                       href="<?php echo esc_url( admin_url( 'themes.php?page=tgmpa-install-plugins' ) ); ?>"
                       target="_blank"><?php esc_html_e( 'Install Plugins', 'soledad' ); ?></a>
                </div>
            </div>
            <div class="col">
                <div class="inner-content">
                    <div class="heading-span">Step <span>2</span></div>
                    <div class="heading-icon">
                        <i class="dashicons-before dashicons-layout"></i>
                    </div>
                    <h3><?php esc_html_e( 'Import Demo Data (Optional)', 'soledad' ); ?></h3>
                    <p><?php _e( '<strong>Important Note:</strong> If your site is a new site with no old posts/pages, you can perform a full import as normal by using the "Import Demo Style" and "Import Demo Content" options. However, if your site has old data such as posts/pages, please only perform an import using the <strong>"Import Demo Style"</strong> option.<br><br>If you receive the message <strong>"Sorry, you are not allowed to access this page"</strong> when clicking to button "Import Demo Now" below, it means that you have not installed or activated the <strong>"Penci Soledad Demo Importer"</strong> plugin. Please go to ', 'soledad' ); ?>
                        <a href="<?php echo esc_url( admin_url( 'themes.php?page=tgmpa-install-plugins' ) ); ?>"
                           target="_blank"><?php _e( 'this page', 'soledad' ); ?></a> <?php _e( 'and do so.', 'soledad' ); ?>
                    </p>
                    <a class="button button-primary"
                       href="<?php echo esc_url( admin_url( 'themes.php?page=import-demo-content' ) ) ?>"
                       target="_blank"><?php esc_html_e( 'Import Demo Now', 'soledad' ); ?></a>
                </div>
            </div>
            <div class="col">
                <div class="inner-content">
                    <div class="heading-span">Step <span>3</span></div>
                    <div class="heading-icon">
                        <i class="dashicons-before dashicons-admin-customizer"></i>
                    </div>
                    <h3><?php esc_html_e( 'Customize The Theme', 'soledad' ); ?></h3>
                    <p><?php esc_html_e( 'By using the WordPress Customizer, you can easily customize every general aspect of the theme.', 'soledad' );
						echo '<br><br>';
						_e( 'If you wish to configure your homepage using Elementor or WPBakery Page Builder plugin, please refer to the <a target="_blank" href="https://soledad.pencidesign.net/soledad-document/#homepage">documentation guide</a> under the "Homepage" section.', 'soledad' ); ?></p>
                    <p>
                        <a href="<?php echo esc_url( admin_url( 'customize.php' ) ); ?>"
                           class="button button-primary"><?php esc_html_e( 'Start Customize', 'soledad' ); ?></a>
                    </p>
					<?php if ( $is_child && ! get_theme_mod( 'penci_sync_customizer_data' ) ):
						$sync_url = add_query_arg( array(
							'penci_csync'  => 'parent_to_child',
							'nonce' => wp_create_nonce( 'penci_sync_customizer_data' )
						), admin_url( 'admin.php?page=soledad_dashboard_welcome' ) );
						?>
                        <div class="penci-dash-customizer-convert">
                            <p style="font-size: 14px;color: #ae0000;font-weight: bold;"><?php _e( 'If you previously customized the parent theme, click the button below to sync those settings to the active child theme. This will resolve style, logo, menu, and other related issues you may encounter.', 'soledad' ); ?></p>
                            <a class="button button-primary"
                               href="<?php echo esc_url( $sync_url ); ?>"><?php _e( 'Sync Customizer Data', 'soledad' ); ?></a>
                        </div>
					<?php endif; ?>
                </div>
            </div>
        </div>
        <div class="soledad-helper-section soledad-install-steps">
            <div class="col">
                <div class="inner-content document">
                    <div class="heading-icon">
                        <i class="dashicons-before dashicons-media-document"></i>
                    </div>
                    <h3><?php esc_html_e( 'Read Full Documentation', 'soledad' ); ?></h3>
                    <p class="about"><?php esc_html_e( 'Need any help setting up and configuring the theme? Please check our full documentation for detailed information on how to use it first.', 'soledad' ); ?></p>
                    <p>
                        <a rel="nofollow" class="button button-primary"
                           href="<?php echo esc_url( 'https://soledad.pencidesign.net/soledad-document/' ); ?>"
                           target="_blank"
                           class="button button-secondary"><?php esc_html_e( 'Read Documentation', 'soledad' ); ?></a>
                    </p>
                </div>
            </div>
            <div class="col">
                <div class="inner-content video">
                    <div class="heading-icon">
                        <i class="dashicons-before dashicons-youtube"></i>
                    </div>
                    <h3><?php esc_html_e( 'Videos tutorial', 'soledad' ); ?></h3>
                    <p><?php echo wp_kses_post( 'We believe that the easiest way to learn is by watching video tutorials. We have a growing library of narrated video tutorials to help you do just that.' ); ?></p>
                    <a class="button button-primary" rel="nofollow"
                       href="https://www.youtube.com/playlist?list=PL1PBMejQ2VTwp9ppl8lTQ9Tq7I3FJTT04"
                       target="_blank"><?php esc_html_e( 'View videos tutorial', 'soledad' ); ?></a>
                </div>
            </div>
			<?php if ( get_option( 'penci_soledad_is_activated' ) ) { ?>
                <div class="col">
                    <div class="inner-content">
                        <div class="heading-icon">
                            <i class="dashicons-before dashicons-admin-network"></i>
                        </div>
                        <h3><?php esc_html_e( 'Revoke License', 'soledad' ); ?></h3>
                        <p>
                            <span style="color: #ff0000; font-weight: bold; font-size: 17px;"><?php _e( 'Note Important: ', 'soledad' ); ?></span><?php _e( 'If you want to use the license on another domain, please revoke the license for this website on ', 'soledad' ); ?>
                            <a href="<?php echo admin_url( 'admin.php?page=soledad_theme_license' ) ?>"><?php _e( 'this page', 'soledad' ); ?></a>
                        </p>
                        <a class="button button-primary"
                           href="<?php echo admin_url( 'admin.php?page=soledad_theme_license' ) ?>"><?php _e( 'Revoke License', 'soledad' ); ?></a>
                    </div>
                </div>
			<?php } ?>
        </div>
    </div>
	<?php if ( ! get_theme_mod( 'activate_white_label' ) ): ?>
        <hr>
        <h3 class="title-more-items"><?php esc_html_e( 'More items by PenciDesign', 'soledad' ) ?></h3>
        <div class="feature-section products three-col">
            <div class="col product">
                <a target="_blank" rel="nofollow" href="<?php echo esc_url( "https://pencidesign.net/" ) ?>"
                   title="<?php echo esc_attr( 'All Themes from PenciDesign' ) ?>">
                    <img class="product__image"
                         src="<?php echo esc_url( PENCI_SOLEDAD_URL . '/inc/dashboard/images/penci.jpg' ) ?>"
                         alt="" width="300" height="150">
                </a>
                <div class="product__body">
                    <h3 class="product__title">
                        <a target="_blank" rel="nofollow" href="<?php echo esc_url( "https://pencidesign.net/" ) ?>"
                           title="<?php echo esc_attr( 'All WordPress Themes from PenciDesign' ) ?>"><?php esc_html_e( 'All WordPress
                            Themes
                            from PenciDesign', 'soledad' ) ?></a>
                    </h3>
                </div>
            </div>
            <div class="col product">
                <a target="_blank" rel="nofollow"
                   href="<?php echo esc_url( "https://themeforest.net/item/pennew-multiconcept-newsmagazine-amp-wordpress-theme/20822517" ) ?>"
                   title="<?php echo esc_attr( 'PenNews' ) ?>">
                    <img class="product__image"
                         src="<?php echo esc_url( PENCI_SOLEDAD_URL . '/inc/dashboard/images/pennews.jpg' ) ?>"
                         alt="" width="300" height="150">
                </a>
                <div class="product__body">
                    <h3 class="product__title">
                        <a target="_blank" rel="nofollow"
                           href="<?php echo esc_url( "https://themeforest.net/item/pennew-multiconcept-newsmagazine-amp-wordpress-theme/20822517" ) ?>"
                           title="<?php echo esc_attr( 'PenNews' ) ?>">PenNews - News/ Magazine/ Business/ Portfolio/
                            Landing AMP WordPress Theme</a>
                    </h3>
                </div>
            </div>
        </div>
	<?php endif; ?>
</div>
