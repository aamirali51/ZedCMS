<?php
$output     = $penci_block_width = $el_class = $css_animation = $css = '';
$form_style = $dis_login_here = $lostpassword = $register = $redirect_url = '';

$form_text_color   = $form_input_color = $form_place_color = $form_inputborder_color = $ploginregis_link = '';
$form_link_color   = $form_link_hcolor = '';
$form_button_color = $form_button_bgcolor = $form_button_hcolor = $form_button_hbgcolor = $responsive_spacing= '';

$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );

$current_url = ( is_ssl() ? 'https://' : 'http://' ) . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

$class_to_filter = vc_shortcode_custom_css_class( $css, ' ' ) . $this->getExtraClass( $el_class ) . $this->getCSSAnimation( $css_animation );
$css_class       = 'penci-block-vc penci-login-register';
$css_class       .= ' ' . apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $class_to_filter, $this->settings['base'], $atts );
$block_id        = Penci_Vc_Helper::get_unique_id_block( 'login_register' );

if( ! is_user_logged_in() || ( $form_style != 'register' && is_user_logged_in() ) ){
?>
	<div id="<?php echo esc_attr( $block_id ); ?>" class="<?php echo esc_attr( $css_class ); ?>">
		<?php Penci_Vc_Helper::markup_block_title( $atts ); ?>
		<div class="penci-block_content">
			<div class="penci-login-wrap clearfix<?php echo( 'login' != $form_style ? ' hidden' : '' ); ?>">
				<?php
				if ( ! is_user_logged_in() ) {
					Penci_Vc_Helper::_login_form();
				} else {
					$current_user = wp_get_current_user();
					?>
					<div class="penci-user-logged-in">
						<div class="penci-login-header">
							<div class="penci-login-avatar">
								<?php echo get_avatar( $current_user->ID, 85 ); ?>
							</div>
							<p>
								<span class="penci-text-hello"><?php echo penci_get_setting( 'penci_trans_hello_text' ); ?></span>
								<span class="penci-display_name"><?php echo $current_user->display_name; ?></span>
							</p>
						</div>
						<div class="penci-user-action-links">
							<?php
							if ( class_exists( 'bbpress' ) ) {
								$profile_url = bbp_get_user_profile_url( bbp_get_current_user_id() );
							} else {
								$profile_url = get_edit_user_link();
							}
							?>
							<a class="penci-button penci-button-ptofile" href="<?php echo $profile_url; ?>"><?php penci_fawesome_icon('far fa-user-circle'); ?> <?php echo penci_get_setting( 'penci_trans_profile_text' ); ?></a>
							<a class="penci-button penci-button-logout" href="<?php echo wp_logout_url( $current_url ); ?>"><?php penci_fawesome_icon('fas fa-sign-out-alt'); ?> <?php echo penci_get_setting( 'penci_trans_logout_text' ); ?></a>
						</div>
					</div>
					<?php
				}
				?>
			</div>
			<?php if( ! is_user_logged_in() && get_option( 'users_can_register' ) ){ ?>
			<div class="penci-register-wrap clearfix<?php echo( 'register' != $form_style ? ' hidden' : '' ); ?>">
				<div class="penci-register-container">
					<form name="form" id="penci-registration-form" class="penci-registration-form" action="<?php echo esc_url( site_url( 'wp-login.php?action=register', 'login_post' ) ); ?>" method="post" novalidate="novalidate">
						<input type="hidden" name="_wpnonce" class="penci_form_nonce" value="<?php echo wp_create_nonce( 'register' ); ?>">
						<p class="register-input">
							<input class="penci_first_name penci-input" name="penci_first_name" type="text" placeholder="<?php echo penci_get_setting( 'penci_pregister_first_name' ); ?>"/>
						</p>
						<p class="register-input">
							<input class="penci_last_name penci-input" name="penci_last_name" type="text" placeholder="<?php echo penci_get_setting( 'penci_pregister_last_name' ); ?>"/>
						</p>
						<p class="register-input">
							<input class="penci_user_name penci-input" name="penci_user_name" type="text" placeholder="<?php echo penci_get_setting( 'penci_pregister_user_name' ); ?>"/>
						</p>
						<p class="register-input">
							<input class="penci_user_email penci-input" name="penci_user_email" type="email" placeholder="<?php echo penci_get_setting( 'penci_pregister_user_email' ); ?>"/>
						</p>
						<p class="register-input">
							<input class="penci_user_pass penci-input" name="penci_user_pass" type="password" placeholder="<?php echo penci_get_setting( 'penci_pregister_user_pass' ); ?>"/>
						</p>
						<p class="register-input">
							<input class="penci_user_pass_confirm penci-input" name="penci_user_pass_confirm" type="password" placeholder="<?php echo penci_get_setting( 'penci_pregister_pass_confirm' ); ?>"/>
						</p>
						<?php do_action( 'register_form' ); ?>
						<p class="register-input">
							<input type="submit" name="penci_submit" class="button" value="<?php echo penci_get_setting( 'penci_pregister_button_submit' ); ?>"/>
						</p>
					</form>
					<?php
					echo '<div class="penci-loginform-extra"><a class="penci-user-login-here" href="' . esc_url( wp_login_url() ) . '">' . penci_get_setting( 'penci_pregister_label_registration' ) . '</a></div>';
					?>
				</div>
			</div>
			<?php } ?>
			<div class="penci-loading-icon"><?php penci_fawesome_icon('fas fa-spinner fa-pulse fa-3x fa-fw'); ?></div>
		</div>
	</div>
<?php
}

$id_login_form = '#' . $block_id;
$id_login_form2 = 'body:not(.pcdm-enable) #' . $block_id;
$css_custom    = Penci_Vc_Helper::get_heading_block_css( $id_login_form, $atts );

if ( $form_text_color ) {
	$css_custom .= $id_login_form . ' form { color:' . esc_attr( $form_text_color ) . ' }';
}
if ( $form_input_color ) {
	$css_custom .= $id_login_form . ' form .penci-input{ color:' . esc_attr( $form_input_color ) . ' }';
}

if ( $form_place_color ) {
	$css_custom .= $id_login_form2 . ' form input::-webkit-input-placeholder{ color:' . esc_attr( $form_place_color ) . ' !important; }';
	$css_custom .= $id_login_form2 . ' form input:-moz-placeholder{ color:' . esc_attr( $form_place_color ) . ' !important; }';
	$css_custom .= $id_login_form2 . ' form input::-ms-input-placeholder{ color:' . esc_attr( $form_place_color ) . ' !important; }';
	$css_custom .= $id_login_form2 . ' form input::placeholder{ color:' . esc_attr( $form_place_color ) . ' !important; opacity: 1; }';
}

if ( $form_inputborder_color ) {
	$css_custom .= $id_login_form2 . ' form .penci-input{ border-color:' . esc_attr( $form_inputborder_color ) . ' }';
}
if ( $form_link_color ) {
	$css_custom .= $id_login_form2 . ' .penci-register-wrap a,';
	$css_custom .= $id_login_form2 . ' .penci-login-wrap a{ color:' . esc_attr( $form_link_color ) . ' }';
}
if ( $form_link_hcolor ) {
	$css_custom .= $id_login_form2 . ' .penci-register-wrap a:hover,';
	$css_custom .= $id_login_form2 . ' .penci-login-wrap a:hover{ color:' . esc_attr( $form_link_hcolor ) . ' }';
}

if ( $form_button_color ) {
	$css_custom .= $id_login_form2 . '.penci-login-register input[type="submit"], '. $id_login_form .' .penci-user-logged-in .penci-user-action-links a{ color:' . esc_attr( $form_link_color ) . ' }';
}
if ( $form_button_bgcolor ) {
	$css_custom .= $id_login_form2 . '.penci-login-register input[type="submit"], '. $id_login_form .' .penci-user-logged-in .penci-user-action-links a{ border-color:' . esc_attr( $form_button_bgcolor ) . ';background-color:' . esc_attr( $form_button_bgcolor ) . ' }';
}
if ( $form_button_hcolor ) {
	$css_custom .= $id_login_form2 . '.penci-login-register input[type="submit"]:hover, '. $id_login_form .' .penci-user-logged-in .penci-user-action-links a:hover{ color:' . esc_attr( $form_button_hcolor ) . ' }';
}
if ( $form_button_hbgcolor ) {
	$css_custom .= $id_login_form2 . '.penci-login-register input[type="submit"]:hover, '. $id_login_form .' .penci-user-logged-in .penci-user-action-links a:hover{ border-color:' . esc_attr( $form_button_hbgcolor ) . ';background-color:' . esc_attr( $form_button_hbgcolor ) . ' }';
}
if ( $ploginregis_link ) {
	$css_custom .= $id_login_form2 . ' .penci-loginform-extra a{ color:' . esc_attr( $ploginregis_link ) . '}';
}

if ( $responsive_spacing ) {
	$css_custom .= penci_extract_spacing_style( $id_login_form, $responsive_spacing );
}


if ( $css_custom ) {
	echo '<style>';
	echo $css_custom;
	echo '</style>';
}
