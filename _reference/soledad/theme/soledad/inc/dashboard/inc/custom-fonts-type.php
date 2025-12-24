<?php

class Penci_Custom_Fonts {
	public static $fonts = false;
	public static $font_face_rules = '';

	public function __construct() {
		add_filter( 'init', [ $this, 'register_post_type' ] );
		add_action( 'admin_init', [ $this, 'migrate_old_options' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'admin_enqueue_scripts' ] );

		add_filter( 'post_row_actions', [ $this, 'post_row_actions' ], 10, 2 );

		add_filter( 'manage_penci_cfonts_posts_columns', [ $this, 'manage_columns' ] );
		add_action( 'manage_penci_cfonts_posts_custom_column', [ $this, 'render_columns' ], 10, 2 );

		add_action( 'add_meta_boxes_penci_cfonts', [ $this, 'add_meta_boxes' ] );
		add_filter( 'upload_mimes', [ $this, 'font_upload_mimes' ] );

		add_action( 'wp_ajax_soledad_save_font_faces', [ $this, 'save_font_faces' ], 10, 2 );

		add_action( 'admin_enqueue_scripts', [ $this, 'add_inline_style_font_face_rules' ], 11 );
	}

	public function migrate_old_options() {


		$penci_data = get_option( 'penci_soledad_options' );
		$has_change = false;

		for ( $x = 1; $x <= 10; $x ++ ) {
			$font_file = penci_get_option( 'soledad_custom_font' . $x );
			$font_name = penci_get_option( 'soledad_custom_fontfamily' . $x );
			if ( $font_file && $font_name ) {

				$file_id    = attachment_url_to_postid( $font_file );

				if ( $file_id ) {

					$post_id = wp_insert_post( [
						'post_title'  => $font_name,
						'post_status' => 'publish',
						'post_type'   => 'penci_cfonts',
						'post_name'   => sanitize_title( $font_name )
					] );

					$font_data = [
						'400' => [ 'woff' => $file_id ]
					];
					update_post_meta( $post_id, 'penci_cfonts_face', $font_data );
					unset( $penci_data[ 'soledad_custom_font' . $x ] );
					unset( $penci_data[ 'soledad_custom_fontfamily' . $x ] );
					$has_change = true;
				}
			}
		}

		if ( $has_change ) {
			update_option( 'penci_soledad_options', $penci_data );
		}
	}

	/**
	 * Generate custom font-face rules when viewing/editing "Custom fonts" in admin area
	 *
	 * @since 1.7.2
	 */
	public function generate_custom_font_face_rules() {

		$fonts = self::get_custom_fonts();

		$font_face_rules = self::$font_face_rules;

		if ( $font_face_rules ) {
			update_option( 'penci_cfonts_rules', $font_face_rules );
		} else {
			delete_option( 'penci_cfonts_rules' );
		}
	}

	public function add_inline_style_font_face_rules() {
		$font_face_rules = get_option( 'penci_cfonts_rules', false );

		// Generate custom font-face rules if not exist while in wp-admin
		if ( ! $font_face_rules && is_admin() ) {

			$fonts           = self::get_custom_fonts();
			$font_face_rules = self::$font_face_rules;

			if ( $font_face_rules ) {
				update_option( 'penci_cfonts_rules', $font_face_rules );
			}
		}

		// Add inline style for custom @font-face rules
		if ( $font_face_rules ) {
			wp_add_inline_style( 'admin-css', $font_face_rules );
		}
	}

	public static function get_custom_fonts() {
		// Return already generated fonts
		if ( self::$fonts ) {
			return self::$fonts;
		}

		$font_ids = get_posts(
			[
				'post_type'      => 'penci_cfonts',
				'posts_per_page' => - 1,
				'fields'         => 'ids',
				'no_found_rows'  => true, // Skip the 'found_posts' calculation
			]
		);

		$fonts = [];

		foreach ( $font_ids as $font_id ) {
			// Add 'custom_font_' prefix for correct font order in ControlTypography.vue & to build @font-face from font ID
			$fonts["custom_font_{$font_id}"] = [
				'id'        => "custom_font_{$font_id}",
				'family'    => get_the_title( $font_id ),
				'fontFaces' => self::generate_font_face_rules( $font_id ),
			];
		}

		self::$fonts = $fonts;

		return $fonts;
	}

	public static function generate_font_face_rules( $font_id = 0 ) {
		$font_faces = get_post_meta( $font_id, 'penci_cfonts_face', true );

		if ( ! $font_faces ) {
			return;
		}

		$font_family     = get_the_title( $font_id );
		$font_face_rules = '';

		// $key: font-weight + variant (e.g.: 700italic)
		foreach ( $font_faces as $key => $font_face ) {
			$font_weight = filter_var( $key, FILTER_SANITIZE_NUMBER_INT );
			$font_style  = str_replace( $font_weight, '', $key );
			$src         = [];

			foreach ( $font_face as $format => $value ) {
				$font_variant_url = wp_get_attachment_url( $font_face[ $format ] );

				if ( $font_variant_url ) {
					if ( $format === 'ttf' ) {
						$format = 'truetype';
					} elseif ( $format === 'otf' ) {
						$format = 'opentype';
					} elseif ( $format === 'eot' ) {
						$format = 'embedded-opentype';
					}

					// Load woff2 first @since 1.4 (smaller file size, almost same support as 'woff')
					if ( $format === 'woff2' ) {
						array_unshift( $src, "url($font_variant_url) format(\"$format\")" );
					} else {
						array_push( $src, "url($font_variant_url) format(\"$format\")" );
					}
				}
			}

			if ( ! count( $src ) ) {
				return;
			}

			$src = implode( ',', $src );

			if ( $font_family && $src ) {
				$font_face_rules .= '@font-face{';
				$font_face_rules .= "font-family:\"$font_family\";";

				if ( $font_weight ) {
					$font_face_rules .= "font-weight:$font_weight;";
				}

				if ( $font_style ) {
					$font_face_rules .= "font-style:$font_style;";
				}

				$font_face_rules .= 'font-display:swap;';
				$font_face_rules .= "src:$src;";
				$font_face_rules .= '}';
			}
		}

		self::$font_face_rules .= "$font_face_rules\n";

		return $font_face_rules;
	}

	public function admin_enqueue_scripts() {
		$current_screen = get_current_screen();

		if ( is_object( $current_screen ) && $current_screen->post_type === 'penci_cfonts' ) {
			// Generate custom font-face rules on custom font edit page
			$this->generate_custom_font_face_rules();

			wp_enqueue_media();

			wp_enqueue_script( 'soledad-custom-fonts', get_template_directory_uri() . '/js/custom-fonts.min.js', [], PENCI_SOLEDAD_VERSION, true );

			$localize_script = array(
				'nonce'   => wp_create_nonce( 'pcfonts-ajax-nonce' ),
			);
			wp_localize_script( 'soledad-custom-fonts', 'PENCIFONTS', $localize_script );
		}
	}

	public function add_meta_boxes() {
		add_meta_box(
			'soledad-font-metabox',
			esc_html__( 'Manage your custom font files', 'soledad' ),
			[ $this, 'render_meta_boxes' ],
			'penci_cfonts',
			'normal',
			'default'
		);
	}


	public function font_upload_mimes( $mime_types ) {
		if ( isset( $_POST['soledadCustomFontsUpload'] ) ) {
			foreach ( $this->get_custom_fonts_mime_types() as $type => $mime ) {
				if ( ! isset( $mime_types[ $type ] ) ) {
					$mime_types[ $type ] = $mime;
				}
			}
		}

		return $mime_types;
	}

	private static function get_custom_fonts_mime_types() {
		$font_mime_types = [
			'woff' => 'application/x-font-woff',
		];

		// NOTE: Undocumented
		return apply_filters( 'soledad/custom_fonts/mime_types', $font_mime_types );
	}

	public function render_meta_boxes( $post ) {
		echo '<h2 class="title">';
		esc_html_e( 'Manage your custom font files', 'soledad' );
		echo '</h2>';

		$font_faces = get_post_meta( $post->ID, 'penci_cfonts_face', true );

		if ( is_array( $font_faces ) && count( $font_faces ) ) {
			foreach ( $font_faces as $font_variant => $font_face ) {
				echo self::render_font_faces_meta_box( $font_face, $font_variant );
			}
		} else {
			echo self::render_font_faces_meta_box( [], 400 );
		}

		echo '<button id="soledad-custom-fonts-add-font-variant" class="button button-primary">' . esc_html__( 'Add a font variant', 'soledad' ) . '</button>';
	}

	public static function generate_hash( $string, $length = 6 ) {
		// Generate SHA1 hexadecimal string (40-characters)
		$sha1        = sha1( $string );
		$sha1_length = strlen( $sha1 );
		$hash        = '';

		// Generate random site hash based on SHA1 string
		for ( $i = 0; $i < $length; $i ++ ) {
			$hash .= $sha1[ rand( 0, $sha1_length - 1 ) ];
		}

		// Convert site path to lowercase
		$hash = strtolower( $hash );

		return $hash;
	}

	public static function generate_random_id( $echo = true ) {
		$hash = self::generate_hash( md5( uniqid( rand(), true ) ) );

		if ( $echo ) {
			echo $hash;
		}

		return $hash;
	}

	public static function render_font_faces_meta_box( $font_face = [], $font_variant = 400 ) {
		$mime_types  = self::get_custom_fonts_mime_types();
		$font_weight = substr( $font_variant, 0, 3 );
		$font_style  = substr( $font_variant, 3, strlen( $font_variant ) );

		ob_start();
		?>
        <div class="soledad-font-variant">
            <div class="font-header">
                <div
                        class="soledad-font-weight-wrapper"
                        data-balloon="<?php esc_html_e( 'Font weight', 'soledad' ); ?>"
                        data-balloon-pos="top">
                    <select name="font_weight">
                        <option value="100" <?php selected( $font_weight, 100, true ); ?>><?php echo '100 (' . esc_html__( 'Thin', 'soledad' ); ?>
                            )
                        </option>
                        <option value="200" <?php selected( $font_weight, 200, true ); ?>><?php echo '200 (' . esc_html__( 'Extra Light', 'soledad' ); ?>
                            )
                        </option>
                        <option value="300" <?php selected( $font_weight, 300, true ); ?>><?php echo '300 (' . esc_html__( 'Light', 'soledad' ); ?>
                            )
                        </option>
                        <option value="400" <?php selected( $font_weight, 400, true ); ?>><?php echo '400 (' . esc_html__( 'Normal', 'soledad' ); ?>
                            )
                        </option>
                        <option value="500" <?php selected( $font_weight, 500, true ); ?>><?php echo '500 (' . esc_html__( 'Medium', 'soledad' ); ?>
                            )
                        </option>
                        <option value="600" <?php selected( $font_weight, 600, true ); ?>><?php echo '600 (' . esc_html__( 'Semi Bold', 'soledad' ); ?>
                            )
                        </option>
                        <option value="700" <?php selected( $font_weight, 700, true ); ?>><?php echo '700 (' . esc_html__( 'Bold', 'soledad' ); ?>
                            )
                        </option>
                        <option value="800" <?php selected( $font_weight, 800, true ); ?>><?php echo '800 (' . esc_html__( 'Extra Bold', 'soledad' ); ?>
                            )
                        </option>
                        <option value="900" <?php selected( $font_weight, 900, true ); ?>><?php echo '900 (' . esc_html__( 'Black', 'soledad' ); ?>
                            )
                        </option>
                    </select>
                </div>

                <div
                        class="soledad-font-style-wrapper"
                        data-balloon="<?php esc_html_e( 'Font style', 'soledad' ); ?>"
                        data-balloon-pos="top">
                    <select name="font_style">
                        <option value="" <?php selected( $font_style, '', true ); ?>><?php esc_html_e( 'Normal', 'soledad' ); ?></option>
                        <option value="italic" <?php selected( $font_style, 'italic', true ); ?>><?php esc_html_e( 'Italic', 'soledad' ); ?></option>
                        <option value="oblique" <?php selected( $font_style, 'oblique', true ); ?>><?php esc_html_e( 'Oblique', 'soledad' ); ?></option>
                    </select>
                </div>

                <div
                        class="soledad-font-preview"
                        data-balloon="<?php esc_html_e( 'Font preview', 'soledad' ); ?>"
                        data-balloon-pos="top">
					<?php
					$font_id     = get_the_ID();
					$font_family = get_the_title();
					$style       = [
						'font-family: "' . $font_family . '"',
						'font-weight: ' . $font_weight,
					];

					if ( ! empty( $font_style ) ) {
						$style[] = "font-style: $font_style";
					}
					?>
                    <div class="pangram"
                         style='<?php echo implode( ';', $style ); ?>'><?php esc_html_e( 'The quick brown fox jumps over the lazy dog.', 'soledad ' ); ?></div>
                </div>

                <div class="actions">
                    <button class="button edit"
                            data-label="<?php esc_html_e( 'Close', 'soledad' ); ?>"><?php esc_html_e( 'Edit', 'soledad' ); ?></button>
                    <button class="button delete"><?php esc_html_e( 'Delete', 'soledad' ); ?></button>
                </div>
            </div>

            <ul class="font-faces hide">
				<?php
				foreach ( $mime_types as $extension => $mime_type ) {
					$font_id     = isset( $font_face[ $extension ] ) ? $font_face[ $extension ] : '';
					$font_url    = wp_get_attachment_url( $font_id );
					$file_size   = $font_id ? ceil( filesize( get_attached_file( $font_id ) ) / 1024 ) . ' KB' : false;
					$placeholder = '';

					switch ( $extension ) {
						case 'ttf':
							$placeholder = esc_html__( 'TrueType Font: Uncompressed font data, but partial IE9+ support.', 'soledad' );
							break;

						case 'woff':
							$placeholder = esc_html__( 'Web Open Font Format: Compressed TrueType/OpenType font with information about font source and full IE9+ support (recommended).', 'soledad' );
							break;

						case 'woff2':
							$placeholder = esc_html__( 'Web Open Font Format 2.0: TrueType/OpenType font with even better compression than WOFF 1.0, but no IE browser support.', 'soledad' );
							break;
					}
					?>
                    <li class="font-face">
                        <label>
                            <div
                                    class="font-name"
                                    data-balloon="<?php echo $file_size; ?>"
                                    data-balloon-pos="top">
								<?php
								// translators: %s: Font file extension (e.g.: TTF, WOFF, WOFF2)
								printf( esc_html__( '%s file', 'soledad' ), strtoupper( $extension ) );
								?>
                            </div>
                        </label>

                        <input type="url" name="font_url" value="<?php echo $font_url; ?>"
                               placeholder="<?php echo $placeholder; ?>">
                        <input type="number" name="font_id" value="<?php echo $font_id; ?>">

                        <button
                                id="<?php echo self::generate_random_id(); ?>"
                                class="button upload<?php echo $font_id ? ' hide' : ''; ?>"
                                data-mime-type="<?php echo esc_attr( $mime_type ); ?>"
                                data-extension="<?php echo esc_attr( $extension ); ?>"
							<?php // translators: %s: Font file extension (e.g.: TTF, WOFF, WOFF2) ?>
                                data-title="<?php echo esc_attr( sprintf( esc_html__( 'Upload .%s file', 'soledad' ), $extension ) ); ?>"><?php esc_html_e( 'Upload', 'soledad' ); ?></button>
                        <button class="button remove<?php echo $font_id ? '' : ' hide'; ?>"><?php esc_html_e( 'Remove', 'soledad' ); ?></button>
                    </li>
				<?php } ?>
            </ul>
        </div>

		<?php
		return ob_get_clean();
	}

	public function save_font_faces() {

		check_ajax_referer( 'pcfonts-ajax-nonce', 'nonce' );

		$post_id    = isset( $_POST['post_id'] ) ? intval( $_POST['post_id'] ) : 0;
		$font_faces = isset( $_POST['font_faces'] ) ? json_decode( stripslashes( $_POST['font_faces'] ), true ) : false;

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( [ 'message' => esc_html__( 'Not allowed', 'soledad' ) ] );
		}

		if ( count( $font_faces ) ) {
			$updated = update_post_meta( $post_id, 'penci_cfonts_face', $font_faces );
		} else {
			$updated = delete_post_meta( $post_id, 'penci_cfonts_face' );
		}

		// Update font face rules in options table (@since 1.7.2)
		if ( $updated ) {
			$fonts = self::get_custom_fonts();

			if ( is_string( self::$font_face_rules ) ) {
				update_option( 'penci_cfonts_rules', self::$font_face_rules );
			}
		}

		wp_send_json_success(
			[
				'post_id'    => $post_id,
				'font_faces' => $font_faces,
				'updated'    => $updated,
			]
		);
	}

	public function manage_columns( $columns ) {
		$columns = [
			'cb'           => '<input type="checkbox" />',
			'title'        => esc_html__( 'Font Family', 'soledad' ),
			'font_preview' => esc_html__( 'Font Preview', 'soledad' ),
		];

		$mime_types = self::get_custom_fonts_mime_types();

		foreach ( $mime_types as $extension => $label ) {
			// translators: %s: Font file extension (e.g.: TTF, WOFF, WOFF2)
			$columns[ $extension ] = sprintf( esc_html__( '%s file', 'soledad' ), strtoupper( $extension ) );
		}

		return $columns;
	}

	public function render_columns( $column, $post_id ) {
		if ( $column === 'font_preview' ) {
			echo '<div class="pangram" style="font-family: \'' . get_the_title( $post_id ) . '\'; font-size: 18px">';

			esc_html_e( 'The quick brown fox jumps over the lazy dog.', 'soledad ' );

			echo '</div>';
		}

		$extensions = array_keys( self::get_custom_fonts_mime_types() );
		$font_faces = get_post_meta( $post_id, 'penci_cfonts_face', true );

		if ( in_array( $column, $extensions ) && $font_faces ) {
			$has_font_file = false;

			foreach ( $font_faces as $font_variant => $font_face ) {
				if ( isset( $font_face[ $column ] ) ) {
					$has_font_file = true;
				}
			}

			echo $has_font_file ? '<i class="dashicons dashicons-yes-alt"></i>' : '<i class="dashicons dashicons-minus"></i>';
		}
	}

	public function post_row_actions( $actions, $post ) {
		// Remove 'Quick Edit'
		if ( $post->post_type === 'penci_cfonts' ) {
			// unset( $actions['inline hide-if-no-js'] );
			unset( $actions['view'] );
		}

		return $actions;
	}

	public function register_post_type() {
		$args = [
			'labels'              => [
				'name'               => esc_html__( 'Custom Fonts', 'soledad' ),
				'singular_name'      => esc_html__( 'Custom Font', 'soledad' ),
				'add_new'            => esc_html__( 'Add New', 'soledad' ),
				'add_new_item'       => esc_html__( 'Add New Custom Font', 'soledad' ),
				'edit_item'          => esc_html__( 'Edit Custom Font', 'soledad' ),
				'new_item'           => esc_html__( 'New Custom Font', 'soledad' ),
				'view_item'          => esc_html__( 'View Custom Font', 'soledad' ),
				'view_items'         => esc_html__( 'View Custom Fonts', 'soledad' ),
				'search_items'       => esc_html__( 'Search Custom Fonts', 'soledad' ),
				'not_found'          => esc_html__( 'No Custom Fonts found', 'soledad' ),
				'not_found_in_trash' => esc_html__( 'No Custom Font found in Trash', 'soledad' ),
				'all_items'          => esc_html__( 'All Custom Fonts', 'soledad' ),
				'menu_name'          => esc_html__( 'Custom Fonts', 'soledad' ),
			],
			'public'              => false,
			'publicly_queryable'  => false,
			'show_ui'             => true,
			'show_in_menu'        => false,
			'show_in_nav_menus'   => false,
			'exclude_from_search' => true,
			'hierarchical'        => false,
			'rewrite'             => false,
			'supports'            => [ 'title' ],
		];

		register_post_type( 'penci_cfonts', $args );
	}
}

new Penci_Custom_Fonts();