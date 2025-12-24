<?php if ( isset( $_POST['success-message'] ) ) : ?>
	<div class="alert-success alert alert-dismissible fade in"
		role="alert"><?php echo esc_html( $_POST['success-message'] ); ?></div>
<?php endif ?>

<?php if ( isset( $_POST['error-message'] ) ) : ?>
	<div class="alert-error alert alert-dismissible fade in"
		role="alert"><?php esc_html_e( $_POST['error-message'] ); ?></div>
<?php endif ?>

<form method="post" action="">
	<div class="row clearfix">
		<!-- old password -->
		<div class="pccol_2 old_password-field">
			<div class="form-group">
				<label for="old_password"><?php echo penci_get_setting( 'penci_trans_oldpassword' ); ?> <span
							class="required">*</span></label>
				<input id="old_password" name="old_password" type="password" class="form-control" value="">
			</div>
		</div>
	</div>
	<div class="row clearfix">
		<!-- new password -->
		<div class="pccol_2 new_password-field">
			<div class="form-group">
				<label for="new_password"><?php echo penci_get_setting( 'penci_trans_newpassword' ); ?></label> <span
						class="required">*</span></label>
				<input id="new_password" name="new_password" type="password" class="form-control" value="">
			</div>
		</div>
	</div>
	<div class="row clearfix">
		<!-- confirm password -->
		<div class="pccol_2 confirm_password-field">
			<div class="form-group">
				<label for="confirm_password"><?php echo penci_get_setting( 'penci_trans_cpassword' ); ?></label>
				<span
						class="required">*</span></label>
				<input id="confirm_password" name="confirm_password" type="password" class="form-control" value="">
			</div>
		</div>
	</div>
	<div class="row clearfix">
		<!-- submit -->
		<div class="pccol_1">
			<div class="form-group">
				<input type="hidden" name="penci-action" value="change-password"/>
				<input type="hidden" name="user_id" value="<?php echo esc_attr( get_current_user_id() ); ?>"/>
				<input type="hidden" name="penci-account-nonce"
						value="<?php echo esc_attr( wp_create_nonce( 'penci-account-nonce' ) ); ?>"/>
				<input type="submit" value="<?php echo penci_get_setting( 'penci_trans_change_password' ); ?>"/>
			</div>
		</div>
	</div>
</form>
