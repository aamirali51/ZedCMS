<?php
$demo_data_list     = apply_filters( 'penci_soledad_demo_packages', array() );
$plugins_required   = apply_filters( 'penci_soledad_plugins_required', array() );
$data_import_option = get_option( 'penci_import_demo_data' );
$installed_demo     = isset( $data_import_option['installed_demo'] ) ? $data_import_option['installed_demo'] : 'empty';
$class              = 'empty' !== $installed_demo ? 'has-imported' : '';
?>

<div class="soledad-wizard-content-inner soledad-wizard-dummy<?php echo esc_attr( $wrapper_classes ); ?>">

    <h1 class="page-title-wz"><?php _e( 'Pick the demo that you want to import', 'soledad' ); ?> </h1>

    <div class="penci-demo-search-form">
        <form>
            <input id="penci-demo-search" name="penci-demo-search" type="text"
                    placeholder="<?php _e( 'Type to search the demo', 'soledad' ); ?>">
        </form>
    </div>

    <div class="demos-container-wrap">
        <div class="demos-container <?php echo $class; ?>">
			<?php
			foreach ( $demo_data_list as $demo => $data ) :
				$item_class = array();
				$item_class[] = 'demo-selector';
				if ( isset( $data['plugins'] ) && is_array( $data['plugins'] ) ) {
					foreach ( $data['plugins'] as $plugin ) {
						if ( ! is_plugin_active( 'elementor/elementor.php' ) && 'elementor' == $plugin ) {
							$item_class[] = 'req-elementor';
						}

						if ( ! is_plugin_active( 'woocommerce/woocommerce.php' ) && 'woocommerce' == $plugin ) {
							$item_class[] = 'req-woocommerce';
						}

						if ( ! is_plugin_active( 'penci-finance/penci-finance.php' ) && 'penci-finance' == $plugin ) {
							$item_class[] = 'req-penci-finance';
						}

						if ( ! is_plugin_active( 'penci-sport/penci-sport.php' ) && 'penci-sport' == $plugin ) {
							$item_class[] = 'req-penci-sport';
						}
					}
				}
				$install_title = __( 'Install', 'soledad' );
				$install_class = 'button button-primary penci-winstall-demo';
				if ( $installed_demo == $demo ) {
					$item_class[]  = 'demo-selector-installed';
					$install_title = __( 'Uninstall', 'soledad' );
					$install_class = 'button button-primary penci-wuninstall-demo';
				}
				?>
                <form action="<?php echo esc_url( add_query_arg( array( 'step' => 2 ) ) ); ?>" method="post"
                      class="<?php echo esc_attr( implode( ' ', array_filter( $item_class ) ) ); ?>">
					<?php wp_nonce_field( 'penci_soledad_import_demo' ); ?>
                    <input type="hidden" name="demo" value="<?php echo esc_attr( $demo ); ?>">


                    <div class="demo-image">
                        <img src="<?php echo esc_url( $data['preview'] ); ?>">

                        <div class="penci-demo-hover">
                            <div class="penci-div-inner">
                                <div class="penci-demo-include">
                                    <input class="input include-content" name="include-style" checked="checked"
                                           type="checkbox">
                                    <span>Import Demo Style</span>
                                </div>
                                <div class="penci-demo-include">
                                    <input class="input include-content" name="include-pages" checked="checked"
                                           type="checkbox">
                                    <span>Import Pages</span>
                                </div>
                                <div class="penci-demo-include">
                                    <input class="input include-content" name="include-posts" checked="checked"
                                           type="checkbox">
                                    <span>Import Posts</span>
                                </div>
								<?php if ( isset( $data['plugins'] ) && is_array( $data['plugins'] ) && in_array( 'woocommerce', $data['plugins'] ) ) : ?>
                                    <div class="penci-demo-include">
                                        <input class="input include-content" name="include-products"
                                               checked="checked"
                                               type="checkbox">
                                        <span>Import Products</span>
                                        <input type="hidden" name="woocommerce_demo" value="1">
                                    </div>
								<?php endif; ?>
                                <div class="penci-demo-include">
                                    <input class="input include-content" name="generate-image" checked="checked"
                                           type="checkbox">
                                    <span>Generate Images</span>
                                </div>
								<?php $id_demo = isset( $data['id_demo'] ) ? $data['id_demo'] : ''; ?>

                            </div>
                        </div>
                    </div>

                    <div class="demo-selector-tools">
                        <div class="demo-install-progress"></div>
                        <div class="demo-install-actions">

                            <input data-installing-text="<?php echo __( 'Installing ...', 'soledad' );?>" data-uninstall-text="<?php echo __( 'Uninstall', 'soledad' );?>" type="submit" class="<?php echo esc_attr( $install_class ); ?>"
                                   value="<?php echo $install_title; ?>">

                            <h2 class="demo-title"><?php echo $data['name']; ?></h2>
                        
                        </div>
                    </div>
                </form>

			<?php endforeach; ?>

        </div>
    </div>
	<div class="penci-soledad-demo-import-log"></div>
</div>



<div class="penci-install-note-wrapper">
    <p class="penci-install-note">
		<?php
		echo esc_html(
			'You can import any of the prebuilt websites that will include a home page, a few products, posts, projects, images and menus. You will be able to switch to any website at any time or just skip this step for now.',
			'soledad'
		);
		?>
    </p>
</div>

<div class="soledad-wizard-footer">
	<?php $this->get_prev_button( 'plugins' ); ?>
    <div>
		<?php $this->get_next_button( 'done' ); ?>
		<?php $this->get_skip_button( 'done' ); ?>
    </div>
</div>