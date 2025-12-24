<?php
$upload_dir              = wp_upload_dir();
$penci_imex_temp         = $upload_dir['basedir'] . '/penci-data-imex-temp/';
$penci_imex_temp_uploads = $penci_imex_temp . '/uploads/';

define( 'PENCI_IMEX_TEMP', $penci_imex_temp );
define( 'PENCI_IMEX_TEMP_UPLOADS', $penci_imex_temp_uploads );

class Penci_Export_Init {

	protected $demo_lists = array();

	public function __construct() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		add_action( 'wp_ajax_penci_export_content', array( $this, 'export_content' ) );
		add_action( 'wp_ajax_penci_import_content', array( $this, 'import_content' ) );
		add_action( 'wp_ajax_penci_upload_import', array( $this, 'upload_import' ) );
		add_filter( 'post_row_actions', array( $this, 'add_export_link' ), 10, 2 );
		add_action( 'edit_form_advanced', array( $this, 'init_demo_import' ) );
		add_filter( 'advanced_import_demo_lists', array( $this, 'penci_demo_import_lists' ) );
		add_action( 'edit_form_after_title', array( $this, 'import_button' ), 10 );
		add_action( 'edit_form_after_title', array( $this, 'video_tutorial_button' ), 10 );
		add_action( 'in_admin_footer', array( $this, 'footer_upload' ) );
	}

	function footer_upload() {
		global $pagenow, $post;
		if ( isset( $post->post_type ) && 'penci_builder' == $post->post_type ) {
			add_thickbox();
			?>
			<div class="hidden" id="penci-imex-custom-upload">
				<?php $this->demo_import_form(); ?>
			</div>
			<?php
		}
	}

	public function demo_import_form( $total_demo = 0 ) {
		global $post;
		?>
		<div class="penci-imex-form <?php echo $total_demo > 0 ? 'hidden' : ''; ?>">
			<div id="penci-imex-upload-zip">
				<h3><?php esc_attr_e( 'Import Header Builder', 'soledad' ); ?></h3>
				<p><?php _e( "This is a feature to help you can export settings from a header you've made and import it to another header. It helps in case you want to move all settings from an existing header to another header or move a header from a website to another website.<br>Please check <a href='https://imgresources.s3.amazonaws.com/export-header.png' target='_blank'>this image</a> to know how to export settings for a header.", 'soledad' ); ?></p>
				<form id="penci_upload_form" enctype="multipart/form-data" novalidate>
					<div class="input-file">
						<input id="userupload" type="file" name="file" accept="application/json"/>
						<p class="notes"><?php esc_attr_e( 'Only accept .json export file of Penci Builder.', 'soledad' ); ?></p>
					</div>
					<input type="hidden" name="id" value="<?php echo esc_attr( $post->ID ); ?>">
					<input class="button button-primary button-large" type="submit" value="Import Header Builder"/>
					<?php wp_nonce_field( 'penci-imex-form' ); ?>
				</form>
				<p class="penci-import-result"></p>
			</div>
		</div>
		<?php
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		global $typenow;
		if ( 'penci_builder' != $typenow ) {
			return;
		}
		wp_enqueue_style(
			'penci-imex-export-style',
			PENCI_SOLEDAD_URL . '/inc/data-imex/assets/css/penci-imex-admin.css',
			array(
				'wp-admin',
				'dashicons',
			),
			'1.0'
		);
		wp_enqueue_script( 'penci-imex-export', PENCI_SOLEDAD_URL . '/inc/data-imex/assets/js/advanced-export-admin.js', array( 'jquery' ), '1.0', false );
		wp_localize_script(
			'penci-imex-export',
			'penci_imex_export_object',
			array(
				'ajaxurl' => admin_url( 'admin-ajax.php' ),
				'nonce'   => wp_create_nonce( 'penci-imex-export' ),
			)
		);
		wp_enqueue_media();
	}

	/**
	 * Export Content
	 *
	 * @since    1.0.0
	 */
	public function export_content() {
		check_ajax_referer( 'penci-imex-export', 'request_id' );
		if ( empty( $_REQUEST['type'] ) || empty( $_REQUEST['id'] ) ) {
			return;
		}

		if ( current_user_can('manage_options') ) {

			$content = get_post_meta( $_REQUEST['id'], 'settings_content', true );
			echo json_encode( $content );
		}
		die();
	}

	public function import_content() {
		check_ajax_referer( 'penci-imex-export', 'request_id' );
		if ( current_user_can('manage_options') ) {
			$demo_file = $_REQUEST['content'];
			$postID    = $_REQUEST['id'];
			$slug      = $_REQUEST['slug'];
			if ( $demo_file ) {
				$file = wp_remote_get( $demo_file );
				$data = json_decode( $file['body'], true );
				if ( isset( $data['penci_builder'][0]['meta']['settings_content'] ) ) {
					$data = $data['penci_builder'][0]['meta']['settings_content'];
				}

				$progress = wp_update_post(
					array(
						'ID'          => $postID,
						'post_status' => 'publish',
						'meta_input'  => array(
							'settings_content' => $data,
							'settings_demo'    => $slug,
						),
					)
				);
				wp_send_json_success( $progress );
			}
		}
		wp_die();
	}

	public function add_export_link( $actions, $post ) {

		$export_posttype = array(
			'penci_builder',
		);

		if ( in_array( $post->post_type, $export_posttype ) ) {
			$actions['penci-export'] = '<a rel="download" data-id="' . esc_attr( $post->ID ) . '" data-type="' . esc_attr( $post->post_type ) . '" class="penci-content-export" href="#">' . __( 'Export' ) . '</a>';
		}

		return $actions;
	}

	public function init_demo_import( $post ) {
		$total_demo = 0;
		if ( 'penci_builder' == $post->post_type ) {
			$this->demo_lists  = apply_filters( 'advanced_import_demo_lists', array() );
			$demo_lists_define = $this->demo_lists;

			$total_demo = count( $demo_lists_define );
			if ( $total_demo >= 1 ) {
				$this->demo_list( $demo_lists_define, $total_demo );
			}
		}
	}

	public function demo_list( $demo_lists, $total_demo ) {
		?>
		<div class="penci-imex-filter-content-heading">
			<h3><?php echo esc_attr( 'Import from Pre-built Headers' ); ?></h3>
			<p><?php echo 'Note that: Import a Pre-Built Header Will Replace All Settings You\'ve Setup for This Header.<br>After Importing A Header, You Need to Edit It with Penci Header Builder and Select The Menus You Want to Show on This Header.'; ?></p>
		</div>
		<div class="penci-imex-filter-content" id="penci-imex-filter-content">
			<div class="penci-imex-filter-content-wrapper">
				<?php
				global $post;
				foreach ( $demo_lists as $key => $demo_list ) {

					/*Check for required fields*/
					if ( ! isset( $demo_list['title'] ) || ! isset( $demo_list['screenshot_url'] ) || ! isset( $demo_list['demo_url'] ) ) {
						continue;
					}

					$data_template = '';
					$template_url  = isset( $demo_list['template_url'] ) && ! empty( $demo_list['template_url'] ) ? $demo_list['template_url'] : '';
					if ( $template_url ) {
						$data_template = 'data-template_url="' . esc_attr( $template_url ) . '"';
					}
					$item_slug = sanitize_title_with_dashes( $demo_list['title'] );
					$demo      = get_post_meta( $post->ID, 'settings_demo', true );
					$class     = $demo == $item_slug ? 'imported' : 'featured-import';
					?>
					<div <?php echo $data_template; ?>
							data-slug="<?php echo esc_attr( $item_slug ); ?>"
							data-id="<?php echo esc_attr( $post->ID ); ?>"
							aria-label="<?php echo esc_attr( $demo_list['title'] ); ?>"
							class="penci-imex-item <?php echo esc_attr( $class ); ?>">
						<div class="penci-imex-item-footer">
							<div class="penci-imex-item-footer_meta">
								<h3 class="theme-name"><?php echo esc_html( $demo_list['title'] ); ?></h3>
								<div class="penci-imex-item-footer-actions">
									<?php if ( ! empty( $demo_list['demo_url'] ) ) : ?>
										<a class="button penci-imex-item-demo-link"
											href="<?php echo esc_url( $demo_list['demo_url'] ); ?>" target="_blank">
											<span class="dashicons dashicons-visibility"></span><?php esc_html_e( 'Preview', 'soledad' ); ?>
										</a>
										<?php
									endif;
									?>
									<a class="button penci-import-button is-button is-default is-primary is-large button-primary"
										href="#"
										aria-label="<?php esc_attr_e( 'Import', 'soledad' ); ?>">
										<span class="dashicons dashicons-download"></span><?php esc_html_e( 'Import', 'soledad' ); ?>
									</a>
								</div>
							</div>
						</div>
						<div class="penci-imex-item-preview">
							<div class="penci-imex-item-screenshot">
								<img alt="" src="<?php echo esc_url( $demo_list['screenshot_url'] ); ?>">
							</div>
						</div>
					</div>
					<?php
				}
				?>
			</div>
		</div>
		<?php
	}

	public function penci_demo_import_lists() {
		$return = array();
		for ( $index = 1; $index <= 16; $index++ ) {
			$title = 'header' . $index;
			$args  = array(
				'title'          => __( 'Header ', 'soledad' ) . $index,
				'type'           => 'header',
				'author'         => __( 'PenciDesign', 'soledad' ),
				'template_url'   => 'https://imgresources.s3.amazonaws.com/header-builder/header-json/header-' . $index . '.json',
				'screenshot_url' => 'https://imgresources.s3.amazonaws.com/header-builder/header-' . $index . '.jpg',
				'demo_url'       => '',
			);
			if ( 9 == $index ) {
				$args['title'] = __( 'Header ', 'soledad' ) . $index . __( ' - Overlap Header', 'soledad' );
			}
			if ( 12 == $index ) {
				$args['title'] = __( 'Header ', 'soledad' ) . $index . __( ' - Overlap Header', 'soledad' );
			}
			$return[ $title ] = $args;
		}

		return $return;
	}

	public function import_button( $post ) {
		if ( 'penci_builder' === $post->post_type ) {
			?>
			<div class="penci-use-import-button penci-builder-button">
				<a data-id="<?php echo $post->ID; ?>"
					href="#TB_inline?&width=600&height=480&inlineId=penci-imex-custom-upload"
					class="thickbox button"><?php echo esc_attr__( 'Import Header Builder', 'soledad' ); ?></a>
			</div>
			<?php
		}
	}

	public function video_tutorial_button( $post ) {
		if ( 'penci_builder' === $post->post_type ) {
			?>
			<div class="penci-video-tutbtn penci-builder-button">
				<a target="_blank"
					href="https://www.youtube.com/watch?v=kUFqsVYyJig&list=PL1PBMejQ2VTwp9ppl8lTQ9Tq7I3FJTT04&index=4"
					class="button"><?php echo esc_attr__( 'Watch Video Tutorial', 'soledad' ); ?></a>
			</div>
			<?php
		}
	}

	public function upload_import() {
		check_ajax_referer( 'penci-imex-form' );
		if ( current_user_can('manage_options') ) {
			$content = '';
			if ( is_uploaded_file( $_FILES['file']['tmp_name'] ) && isset( $_POST['id'] ) ) {
				$id       = $_POST['id'];
				$data_out = '';
				$class    = '';

				$content  = file_get_contents( $_FILES['file']['tmp_name'] );
				$content  = json_decode( $content, true );
				$progress = wp_update_post(
					array(
						'ID'          => $id,
						'post_status' => 'publish',
						'meta_input'  => array(
							'settings_content' => $content,
							'settings_demo'    => 'custom_import',
						),
					)
				);
				if ( is_wp_error( $progress ) ) {
					$errors    = $progress->get_error_messages();
					$data_out .= implode( ',', $errors );
					$class     = 'error';
				} else {
					$data_out = esc_attr__( 'Import Header Builder Data Successfully', 'soledad' );
					$class    = 'success';
				}
			} else {
				$data_out = esc_attr__( 'Please Add Import Data File', 'soledad' );
				$class    = 'error';
			}
			wp_send_json_success(
				array(
					'text'  => $data_out,
					'class' => $class,
				),
				200
			);
		}
	}
}

new Penci_Export_Init();
