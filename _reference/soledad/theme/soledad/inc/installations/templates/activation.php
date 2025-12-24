<div class="soledad-wizard-content-inner">
    <?php
    $license_data = get_option( 'penci_soledad_purchased_data' );
    if ( ! empty( $license_data ) && penci_soledad_is_activated() ) {
    ?>
    <div class="success-icon">
        <i class="dashicons dashicons-yes-alt"></i>
    </div>
    <h1 class="page-title-wz"><?php esc_html_e( 'Your site has been successfully registered with a valid license.', 'soledad' ); ?></h1>
    <p class="penci-license-detail-desc" style="font-size: 15px;">
        <?php
        if ( isset( $license_data['buyer'] ) && $license_data['buyer'] ) {
            $buyer         = $license_data['buyer'];
            $buyer_len     = strlen( $buyer );
            $buyer_display = substr( $buyer, 0, 1 ) . str_repeat( '*', $buyer_len - 2 ) . substr( $buyer, $buyer_len - 1, 1 );
            ?>
            <strong style="display: inline-block; margin-bottom: 8px; min-width: 140px;"><?php esc_html_e( 'Buyer Username', 'soledad' ); ?></strong> : &nbsp;&nbsp;
            <strong><?php echo $buyer_display; ?></strong><br>
        <?php } ?>
        <?php if ( isset( $license_data['bount_time'] ) && $license_data['bount_time'] ) { ?>
            <strong style="display: inline-block; margin-bottom: 8px; min-width: 140px;"><?php esc_html_e( 'Purchase Date', 'soledad' ); ?></strong> : &nbsp;&nbsp;
            <strong><?php echo $license_data['bount_time']; ?></strong><br>
        <?php } ?>
        <?php
        if ( isset( $license_data['purchase_code'] ) && $license_data['purchase_code'] ) {
            $purchased_code = $license_data['purchase_code'];
            $code_len       = strlen( $purchased_code );
            $code_display   = substr( $purchased_code, 0, 8 ) . str_repeat( '*', $code_len - 16 ) . substr( $purchased_code, $code_len - 8, 8 );
            ?>
            <strong style="display: inline-block; margin-bottom: 8px; min-width: 140px;"><?php esc_html_e( 'Item Purchase Code', 'soledad' ); ?></strong> : &nbsp;&nbsp;
            <strong><?php echo $code_display; ?></strong><br>
        <?php } ?>
    </p>
    <?php } else { ?>
    <form id="penci-check-license"
          action="<?php echo admin_url( 'admin.php?page=soledad_active_theme' ); ?>">
        <div class="penci-activate-inputs">
            <input name="evato-code" class="penci-form-control evato-code" type="text"
                   placeholder="<?php esc_html_e( 'Your Purchase Code', 'soledad' ); ?>" value=""
                   autocomplete="off">
            <input type="hidden" name="server-id" class="server-id"
                   value="" readonly/>
            <span class="penci-form-control-bar"></span>
            <div class="penci-activate-err">
                <span class="penci-err-missing"><?php esc_html_e( 'Code is required', 'soledad' ); ?></span>
                <span class="penci-err-length"><?php esc_html_e( 'Code is too short', 'soledad' ); ?></span>
                <span class="penci-err-invalid"><?php esc_html_e( 'Invalid purchase code. Please check it again.', 'soledad' ); ?></span>
            </div>
        </div>
        <button class="soledad-activate-button"><?php esc_html_e( 'Activate theme', 'soledad' ); ?></button>
        <div class="spinner"></div>
        <div class="soledad-find-code">
            <a href="<?php echo esc_url( 'https://help.market.envato.com/hc/en-us/articles/202822600-Where-Is-My-Purchase-Code-' ); ?>"
               target="_blank">
				<?php esc_html_e( 'Find Your Purchase Code', 'soledad' ); ?>
            </a>
        </div>
    </form>
    <div class="penci-activate-extra-notes">
        <p class="penci-activate-desc" style="margin: 30px 0 10px;">
			<?php _e( 'To deliver you a better customer support service, access to features and to prevent piracy when the theme is activated the following data is sent to our servers:', 'soledad' ); ?>
        </p>
        <ul style="list-style: square; padding-left: 40px; font-size: 14px;">
            <li><?php _e( 'The Envato username', 'soledad' ); ?></li>
            <li><?php _e( 'The Envato purchase code for the item', 'soledad' ); ?></li>
            <li><?php _e( 'The website URL you\'ve activated the theme', 'soledad' ); ?></li>
            <li><?php _e( 'The server IP address that has the theme installed', 'soledad' ); ?></li>
        </ul>
        <p class="penci-activate-desc"
           style="margin-bottom: 0;"><?php _e( 'The data is stored in a server located in US, and we do not share any of this information with third-party partners.' ); ?></p>
        </p>
    </div>
    <?php } ?>
</div>

<div class="soledad-wizard-footer">
	<?php $this->get_prev_button( 'welcome' ); ?>
    <div>
        <?php $this->get_next_button( 'child-theme' ); ?>
    </div>
</div>