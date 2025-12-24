<?php
if ( isset( $_GET['soledad_builder'] ) ) {
	$builder = esc_html( $_GET['soledad_builder'] );
} else {
	$builder = 'elementor';
}

$installations = Soledad_Theme_Installations::get_instance();
$plugins_list = $installations->get_plugins();

if ( $installations->is_setup() ) {
	$button_item_class = 'penci-install-inline-btn penci-install-style-underline';
} else {
	$button_item_class = 'penci-install-btn';
}
$active_list = 0;
?>
<div class="penci-install-plugins<?php echo $installations->is_all_activated() ? ' penci-install-all-active' : ''; ?>">
	<div class="penci-install-plugin-response"></div>

	<?php if ( $installations->is_setup() ) : ?>
		<h3>
			<?php esc_html_e( 'Plugins activation', 'soledad' ); ?>
		</h3>

		<p>
			<?php esc_html_e( 'Install and activate plugins for your website.', 'soledad' ); ?>
		</p>
	<?php endif; ?>

	<ul>
		<?php foreach ( $plugins_list as $slug => $plugin_data ) : 
			if ( $plugin_data['name'] == '' ){
				continue; // Skip plugins without a name
			}

			if ( 'deactivate' == $plugin_data['status'] ) {
				$active_list++;
			}

			?>
			
			<li class="soledad-plugin-wrapper<?php echo isset( $plugin_data['description'] ) ? ' penci-install-large' : ''; ?>">
				<div class="penci-install-plugin-heading">
				
					<span class="penci-install-plugin-name">
						<?php echo esc_html( $plugin_data['name'] ); ?>
					</span>
				</div>

					<span class="penci-install-plugin-required">
						<?php if ( ! empty( $plugin_data['required'] ) || 'elementor' === $slug || 'js_composer' === $slug ) : ?>
							
							<span class="penci-install-plugin-required-text">
								<?php esc_html_e( 'Required', 'soledad' ); ?>
							</span>
						<?php endif; ?>
					</span>

				<?php if ( ! empty( $plugin_data['description'] ) ) : ?>
					<div class="penci-install-plugin-description">
						<?php echo esc_html( $plugin_data['description'] ); ?>
					</div>
				<?php endif; ?>

				<span class="penci-install-plugin-version">
					<?php if ( ! empty( $plugin_data['version'] ) ) : ?>
						<span>
							<?php echo esc_html( $plugin_data['version'] ); ?>
						</span>
					<?php endif; ?>
				</span>

				<div class="penci-install-plugin-btn-wrapper">
					<?php if ( is_multisite() && is_plugin_active_for_network( $plugin_data['file_path'] ) ) : ?>
						<span class="penci-install-plugin-btn-text">
							<?php esc_html_e( 'Plugin activated globally.', 'soledad' ); ?>
						</span>
					<?php elseif ( isset( $plugin_data['status'] ) && 'require_update' !== $plugin_data['status'] ) : ?>
						<a class="<?php echo esc_attr( $button_item_class ); ?> soledad-ajax-plugin soledad-<?php echo esc_html( $plugin_data['status'] ); ?>"
							href="<?php echo esc_url( $installations->get_action_url( $slug, $plugin_data['status'] ) ); ?>"
							data-plugin="<?php echo esc_attr( $slug ); ?>"
							data-action="<?php echo esc_attr( $plugin_data['status'] ); ?>">
							<span><?php echo esc_html( $installations->get_action_text( $plugin_data['status'] ) ); ?></span>
						</a>
					<?php elseif ( $installations->is_setup() ) : ?>
						<span class="penci-install-plugin-btn-text">
							<?php esc_html_e( 'Required update not available', 'soledad' ); ?>
						</span>
					<?php endif; ?>

					<?php if ( isset( $plugin_data['buttons'] ) ) : ?>
						<?php foreach ( $plugin_data['buttons'] as $button ) : ?>
							<a href="<?php echo esc_url( $button['url'] ); ?>" class="penci-install-btn<?php echo isset( $button['extra-class'] ) ? ' ' . esc_attr( $button['extra-class'] ) : ''; ?>">
								<?php echo esc_html( $button['name'] ); ?>
							</a>
						<?php endforeach; ?>
					<?php endif; ?>
				</div>
			</li>
		<?php endforeach; ?>
	</ul>

	<?php if ( $plugins_list && ( ! isset( $args['show_plugins'] ) || 'compatible' !== $args['show_plugins'] ) ) : ?>
		<script>
			var penci_plugin_data = <?php echo wp_json_encode( $plugins_list ); ?>
		</script>
	<?php endif; ?>
</div>

<?php if ( $installations->is_setup() ) : ?>
	<div class="soledad-wizard-footer">
		<?php $installations->get_prev_button( 'child-theme' ); ?>
		<div>
			<?php 
			if ( $active_list < 5 ) : 
			?>
			<a class="penci-install-inline-btn penci-install-style-underline soledad-wizard-all-plugins" href="#">
				<?php esc_html_e( 'Install & activate all', 'soledad' ); ?>
			</a>
			<?php endif; ?>
			<?php $installations->get_next_button( 'demo', '', count( $installations->get_required_plugins_to_activate() ) > 0 ); ?>
		</div>
	</div>
<?php endif; ?>
