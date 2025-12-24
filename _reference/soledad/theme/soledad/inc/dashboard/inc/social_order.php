<?php
if ( ! class_exists( 'Penci_Social_Order' ) ) {
	class Penci_Social_Order {
		public function __construct() {
			if ( current_user_can( 'manage_options' ) ) {
				add_action( 'admin_menu', array( $this, 'add_menu' ), 90 );
				add_action( 'admin_enqueue_scripts', array( $this, 'admin_lib' ), 90 );
				add_action( 'wp_ajax_penci_social_save_order', array( $this, 'save_order' ) );
				add_action( 'wp_ajax_soledad_add_social', array( $this, 'soledad_add_social' ) );
				add_action( 'wp_ajax_soledad_remove_social', array( $this, 'soledad_remove_social' ) );
				add_action( 'admin_enqueue_scripts', array( $this, 'admin_src' ) );
			}
		}

		public function admin_src() {
			wp_enqueue_style( 'wp-color-picker' );
			wp_enqueue_script( 'wp-color-picker' );
		}

		public function soledad_remove_social() {
			check_ajax_referer( 'ajax-nonce', '_wpnonce' );
			if ( ! current_user_can( 'manage_options' ) ) {
				return;
			}
			$name = isset( $_POST['name'] ) ? $_POST['name'] : '';

			$custom_socials = get_option( 'penci_custom_socials' );

			if ( $name && isset( $custom_socials[ $name ] ) ) {

				unset( $custom_socials[ $name ] );

				if ( update_option( 'penci_custom_socials', $custom_socials ) ) {
					wp_send_json_success(
						array(
							'type'    => 'remove',
							'message' => esc_html__( 'Social name add successfully.', 'soledad' ),
						)
					);
				}
			}
		}

		public function soledad_add_social() {

			check_ajax_referer( 'ajax-nonce', '_wpnonce' );

			if ( ! current_user_can( 'manage_options' ) ) {
				return;
			}

			$name     = isset( $_POST['name'] ) ? $_POST['name'] : '';
			$url      = isset( $_POST['url'] ) ? esc_url( $_POST['url'] ) : '';
			$icon     = isset( $_POST['icon'] ) ? $_POST['icon'] : '';
			$color    = isset( $_POST['color'] ) ? $_POST['color'] : '';
			$field_id = '_pccs_' . strtolower( sanitize_text_field( $name ) );

			$custom_socials = get_option( 'penci_custom_socials', array() );

			if ( isset( $sidebars[ $field_id ] ) ) {
				wp_send_json_error(
					array(
						'type'    => 'error',
						'message' => esc_html__( 'Social name already exists, please use a different name.', 'soledad' ),
					)
				);
			}

			$custom_socials[ $field_id ] = array(
				'name'  => $name,
				'url'   => $url,
				'icon'  => $icon,
				'color' => $color,
			);

			if ( update_option( 'penci_custom_socials', $custom_socials ) ) {
				wp_send_json_success(
					array(
						'type'    => 'add',
						'name'    => $name,
						'url'     => $url,
						'id'      => $field_id,
						'color'   => $color,
						'img'     => wp_get_attachment_image( $icon ),
						'message' => esc_html__( 'Social name add successfully.', 'soledad' ),
					)
				);
			}
		}

		public function admin_lib() {

			if ( isset( $_GET['page'] ) && $_GET['page'] == 'penci_social_order' ) {
				wp_enqueue_script( 'sortable-lib', PENCI_SOLEDAD_URL . '/inc/dashboard/js/Sortable.min.js', array(), PENCI_SOLEDAD_VERSION );
				wp_enqueue_script( 'sortable-main', PENCI_SOLEDAD_URL . '/inc/dashboard/js/social_order.js', array(), PENCI_SOLEDAD_VERSION );
			}
		}

		public function add_menu() {
			add_submenu_page(
				'soledad_dashboard_welcome',
				esc_html__( 'Add & Order of Social Icons', 'soledad' ),
				esc_html__( 'Add & Order of Social Icons', 'soledad' ),
				'manage_options',
				'penci_social_order',
				array(
					$this,
					'soledad_social_order',
				),
				3
			);
		}

		public function save_order() {
			check_ajax_referer( 'ajax-order-nonce', '_wpnonce' );
			if ( ! current_user_can( 'manage_options' ) ) {
				return;
			}
			$social_update = isset( $_REQUEST['penci_soledad_social_order_list'] ) && $_REQUEST['penci_soledad_social_order_list'] ? $_REQUEST['penci_soledad_social_order_list'] : '';
			if ( $social_update ) {
				update_option( 'penci_social_orders', $social_update );
			}
			wp_send_json_success( array( 'success' ) );
		}

		public function convert_array( $options ) {
			$return = array();
			foreach ( $options as $key => $option ) {
				$return[ $option['id'] ] = $option['label'];
			}

			return $return;
		}

		public function soledad_social_order() {
			$socials = get_option( 'penci_custom_socials', array() );
			$data    = get_option( 'penci_social_orders' );
			$options = array();
			include PENCI_SOLEDAD_DIR . '/inc/customizer/config/sections/pencidesign_new_section_social_section.php';
			$options = $this->convert_array( $options );

			if ( ! empty( $socials ) ) {
				foreach ( $socials as $slug => $attr ) {
					$options[ $slug ] = $attr['name'];
				}
			}

			if ( ! empty( $data ) ) {
				$options = penci_sortArrayByArray( $options, $data );
			}

			?>
			<div class="wrap">
				<h1><?php esc_html_e( 'Order of Social Icons', 'soledad' ); ?></h1>
				<div class="penci-soledad-social-order">
					<ul id="penci_soledad_social_order_list">
						<?php
						if ( ! empty( $options ) ) {
							foreach ( $options as $index => $option ) {

								if ( in_array( $index, array( 'penci_facebook', 'penci_twitter' ) ) ) {
									$class = penci_get_setting( $index ) ? 'has-data' : 'no-data';
								} else {
									$class = get_theme_mod( $index ) ? 'has-data' : 'no-data';
								}

								if ( isset( $socials[ $index ] ) ) {
									$class = 'has-data';
								}

								echo '<li class="' . $class . '" data-id="' . $index . '"><span>' . $option . '</span></li>';
							}
						}
						?>
					</ul>
					<?php wp_nonce_field( 'ajax-order-nonce', 'penci_ajax_processor_order_nonce' ); ?>
				</div>
				<div class="penci-new-social-wrapper">
					<h2><?php _e( 'Add new social item', 'soledad' ); ?></h2>
					<form>

						<?php wp_nonce_field( 'ajax-nonce', 'penci_ajax_processor_nonce' ); ?>

						<div style="display: flex;flex-wrap: wrap;flex-direction: revert;justify-content: space-between;">
							<div class="penci-form-field" style="flex-basis:20%">

								<input class="social_name" name="social_name" type="text" id="social_name" value=""
										placeholder="Social Name">
							</div>
							<div class="penci-form-field" style="flex-basis:20%">


								<input class="social_url" name="social_url" type="url" id="social_url" value=""
										placeholder="Social URL">
							</div>

							<div class="penci-form-field" style="flex-basis:30%">

								<input class="social_color" name="social_color" type="text" id="social_color" value=""
										placeholder="">
								<script>
									jQuery(document).ready(function ($) {

									$('.penci-form-field #social_color').wpColorPicker()

									})
								</script>
							</div>

							<div class="penci-form-field">

								<?php
								$default_image = PENCI_SOLEDAD_URL . '/images/nothumb.jpg';
								?>

								<img data-placeholder="<?php echo $default_image; ?>"
									src="<?php echo $default_image; ?>" alt="" class="penci-placeholder-img"/>


								<button class="button button-alt penci-add-icon"
										data-type="add"><?php _e( 'Upoad Icon', 'soledad' ); ?></button>
								<input type="hidden" class="penci-icon-id" name="penci-icon-id">
							</div>


							<div class="penci-form-field">

								<button class="button button-primary penci-add-social"
										data-type="add"><?php _e( '+ Add Social Item', 'soledad' ); ?></button>


							</div>
						</div>

						<div class="notes" style="margin-top: 6px;">
							<?php _e( 'You should use an <strong>SVG image</strong> for your icon to achieve the best results. You can find <strong>SVG file</strong> types for your icons by conducting a <a target="_blank" href="https://www.google.com/search?q=q=svg+icon&tbm=isch"><strong>search on Google.</strong></a>', 'soledad' ); ?>
						</div>

						<table style="margin-top:20px" class="widefat" id="penci-table">
							<tr>
								<th>Name</th>
								<th>URL</th>
								<th>Icon</th>
								<th>Color</th>
								<th>Delete</th>
							</tr>

							<?php if ( empty( $socials ) ) : ?>

								<tr class="no-social-tr">

									<td colspan="3">No Custom Social Media</td>

								</tr>

							<?php else : ?>

								<?php
								foreach ( (array) $socials as $slug => $social_data ) :

									$name  = isset( $social_data['name'] ) ? $social_data['name'] : $slug;
									$img   = isset( $social_data['icon'] ) ? $social_data['icon'] : '';
									$url   = isset( $social_data['url'] ) ? $social_data['url'] : '';
									$color = isset( $social_data['color'] ) ? $social_data['color'] : '';
									?>

									<tr>
										<td><?php echo esc_html( $name ); ?></td>

										<td><?php echo esc_html( $url ); ?></td>


										<td><?php echo wp_get_attachment_image( $img ); ?></td>

										<td><span aria-label="<?php echo esc_attr( $color ); ?>" class="social-colors-init" style="background-color: <?php echo esc_attr( $color ); ?>;"></span></td>

										<td>
											<button class="button button-small penci-remove-social" data-type="remove"
													data-slug="<?php echo esc_attr( $slug ); ?>">Delete
											</button>
										</td>
									</tr>

									<?php
								endforeach;

							endif; // empty_sidebar
							?>

						</table>

						<p class="penci-notice notice notice-success" style="padding:10px 20px; display:none"></p>

					</form>
				</div>
			</div>
			<div class="penci_soledad_social_order_update">
				<span><?php _e( 'Updated successfully', 'soledad' ); ?></span>
			</div>
			<script>
				Sortable.create(penci_soledad_social_order_list, {
				store: {
					set: function (sortable) {
					var order = sortable.toArray(),
						nonce = jQuery('[name="penci_ajax_processor_order_nonce"]').val()
					jQuery.ajax({
						method: 'POST',
						url: ajaxurl,
						data: {
						_wpnonce: nonce,
						action: 'penci_social_save_order',
						penci_soledad_social_order_list: order,
						},
					}).done(function (msg) {
						jQuery('.penci_soledad_social_order_update').fadeIn()
						setTimeout(function () {
						jQuery('.penci_soledad_social_order_update').fadeOut()
						}, 1200)
					})
					},
				},
				})
			</script>
			<?php
		}
	}
}
new Penci_Social_Order();
