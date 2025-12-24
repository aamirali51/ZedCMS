<?php

class penci_product_swatches {

	protected static $instance = null;

	public $types = array();

	public function __construct() {
		add_action( 'init', [ $this, 'init'] );
	}

	public function init() {
		$this->types = array(
			'color' => esc_html__( 'Color', 'soledad' ),
			'image' => esc_html__( 'Image', 'soledad' ),
			'label' => esc_html__( 'Label', 'soledad' ),
		);

		if ( is_admin() ) {
			add_action( 'admin_init', array( $this, 'init_attribute_hooks' ) );
			add_action( 'admin_notices', array( $this, 'restore_attributes_notice' ) );
			add_action( 'admin_init', array( $this, 'restore_attribute_types' ) );
			add_action( 'admin_print_scripts', array( $this, 'admin_enqueue_scripts' ) );
			add_action( 'woocommerce_product_option_terms', array( $this, 'product_option_terms' ), 10, 2 );
			add_action( 'wp_ajax_penci_add_new_attribute', array( $this, 'add_new_attribute_ajax' ) );
			add_action( 'admin_footer', array( $this, 'add_attribute_term_template' ) );
			add_action( 'edit_post', array( $this, 'deleted_transitent' ) );
		}

		add_filter( 'product_attributes_type_selector', array( $this, 'add_attribute_types' ) );
		add_action( 'penci_product_attribute_field', array( $this, 'attribute_fields' ), 10, 3 );
		add_filter( 'woocommerce_dropdown_variation_attribute_options_html', array(
			$this,
			'get_swatch_html'
		), 100, 2 );
		add_filter( 'penci_swatch_html', array( $this, 'swatch_html' ), 5, 4 );

		add_action( 'penci_swatches_loop', array( $this, 'product_swatches_list' ) );
		add_action( 'penci_loop_product_image', array( $this, 'preload_icon' ) );
	}

	public static function instance() {
		if ( null == self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function admin_enqueue_scripts() {
		$screen = get_current_screen();
		if ( strpos( $screen->id, 'edit-pa_' ) === false && strpos( $screen->id, 'product' ) === false ) {
			return;
		}

		wp_enqueue_media();

		wp_enqueue_style( 'penci-admin', PENCI_SOLEDAD_URL . '/inc/woocommerce/css/penci-admin.css', array( 'wp-color-picker' ), '1.0' );
		wp_enqueue_script( 'penci-admin', PENCI_SOLEDAD_URL . '/inc/woocommerce/js/penci-admin.js', array(
			'jquery',
			'wp-color-picker',
			'wp-util'
		), '1.0', true );

		wp_localize_script(
			'penci-admin',
			'penci',
			array(
				'i18n'        => array(
					'mediaTitle'  => esc_html__( 'Choose an image', 'soledad' ),
					'mediaButton' => esc_html__( 'Use image', 'soledad' ),
				),
				'placeholder' => WC()->plugin_url() . '/assets/images/placeholder.png'
			)
		);
	}

	public function add_attribute_types( $types ) {
		$types = array_merge( $types, $this->types );

		return $types;
	}

	public function init_attribute_hooks() {
		$attribute_taxonomies = wc_get_attribute_taxonomies();

		if ( empty( $attribute_taxonomies ) ) {
			return;
		}

		foreach ( $attribute_taxonomies as $tax ) {
			add_action( 'pa_' . $tax->attribute_name . '_add_form_fields', array( $this, 'add_attribute_fields' ) );
			add_action( 'pa_' . $tax->attribute_name . '_edit_form_fields', array(
				$this,
				'edit_attribute_fields'
			), 10, 2 );

			add_filter( 'manage_edit-pa_' . $tax->attribute_name . '_columns', array(
				$this,
				'add_attribute_columns'
			) );
			add_filter( 'manage_pa_' . $tax->attribute_name . '_custom_column', array(
				$this,
				'add_attribute_column_content'
			), 10, 3 );
		}

		add_action( 'created_term', array( $this, 'save_term_meta' ), 10, 2 );
		add_action( 'edit_term', array( $this, 'save_term_meta' ), 10, 2 );
	}

	public function restore_attributes_notice() {
		if ( get_transient( 'penci_attribute_taxonomies' ) && ! get_option( 'penci_restore_attributes_time' ) ) {
			?>
            <div class="notice-warning notice is-dismissible">
                <p>
					<?php
					esc_html_e( 'Found a backup of product attributes types. This backup was generated at', 'soledad' );
					echo ' ' . date( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ), get_option( 'penci_backup_attributes_time' ) ) . '.';
					?>
                </p>
                <p>
                    <a href="<?php echo esc_url( add_query_arg( array(
						'penci_action' => 'restore_attributes_types',
						'penci_nonce'  => wp_create_nonce( 'restore_attributes_types' )
					) ) ); ?>">
                        <strong><?php esc_html_e( 'Restore product attributes types', 'soledad' ); ?></strong>
                    </a>
                    |
                    <a href="<?php echo esc_url( add_query_arg( array(
						'penci_action' => 'dismiss_restore_notice',
						'penci_nonce'  => wp_create_nonce( 'dismiss_restore_notice' )
					) ) ); ?>">
                        <strong><?php esc_html_e( 'Dismiss this notice', 'soledad' ); ?></strong>
                    </a>
                </p>
            </div>
			<?php
		} elseif ( isset( $_GET['penci_message'] ) && 'restored' == $_GET['penci_message'] ) {
			?>
            <div class="notice-warning settings-error notice is-dismissible">
                <p><?php esc_html_e( 'All attributes types have been restored.', 'soledad' ) ?></p>
            </div>
			<?php
		}
	}

	public function restore_attribute_types() {
		if ( ! isset( $_GET['penci_action'] ) || ! isset( $_GET['penci_nonce'] ) ) {
			return;
		}

		if ( ! wp_verify_nonce( $_GET['penci_nonce'], $_GET['penci_action'] ) ) {
			return;
		}

		if ( 'restore_attributes_types' == $_GET['penci_action'] ) {
			global $wpdb;

			$attribute_taxnomies = get_transient( 'penci_attribute_taxonomies' );

			foreach ( $attribute_taxnomies as $id => $attribute ) {
				$wpdb->update(
					$wpdb->prefix . 'woocommerce_attribute_taxonomies',
					array( 'attribute_type' => $attribute->attribute_type ),
					array( 'attribute_id' => $id ),
					array( '%s' ),
					array( '%d' )
				);
			}

			update_option( 'penci_restore_attributes_time', time() );
			delete_transient( 'penci_attribute_taxonomies' );
			delete_transient( 'wc_attribute_taxonomies' );

			$url = remove_query_arg( array( 'penci_action', 'penci_nonce' ) );
			$url = add_query_arg( array( 'penci_message' => 'restored' ), $url );
		} elseif ( 'dismiss_restore_notice' == $_GET['penci_action'] ) {
			update_option( 'penci_restore_attributes_time', 'ignored' );
			$url = remove_query_arg( array( 'penci_action', 'penci_nonce' ) );
		}

		if ( isset( $url ) ) {
			wp_redirect( $url );
			exit;
		}
	}

	public function add_attribute_fields( $taxonomy ) {
		$attr = $this->get_tax_attribute( $taxonomy );

		do_action( 'penci_product_attribute_field', $attr->attribute_type, '', 'add' );
	}

	public function get_tax_attribute( $taxonomy ) {
		global $wpdb;

		$attr = substr( $taxonomy, 3 );
		$attr = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM " . $wpdb->prefix . "woocommerce_attribute_taxonomies WHERE attribute_name = %s", $attr ) );

		return $attr;
	}

	public function edit_attribute_fields( $term, $taxonomy ) {
		$attr  = $this->get_tax_attribute( $taxonomy );
		$value = get_term_meta( $term->term_id, $attr->attribute_type, true );

		do_action( 'penci_product_attribute_field', $attr->attribute_type, $value, 'edit' );
	}

	public function attribute_fields( $type, $value, $form ) {
		if ( in_array( $type, array( 'select', 'text' ) ) ) {
			return;
		}

		printf(
			'<%s class="form-field">%s<label for="term-%s">%s</label>%s',
			'edit' == $form ? 'tr' : 'div',
			'edit' == $form ? '<th>' : '',
			esc_attr( $type ),
			$this->types[ $type ],
			'edit' == $form ? '</th><td>' : ''
		);

		switch ( $type ) {
			case 'image':
				$image = $value ? wp_get_attachment_image_src( $value ) : '';
				$image = $image ? $image[0] : WC()->plugin_url() . '/assets/images/placeholder.png';
				?>
                <div class="penci-term-image-thumbnail" style="float:left;margin-right:10px;">
                    <img alt="" src="<?php echo esc_url( $image ) ?>" width="60px" height="60px"/>
                </div>
                <div style="line-height:60px;">
                    <input type="hidden" class="penci-term-image" name="image"
                           value="<?php echo esc_attr( $value ) ?>"/>
                    <button type="button"
                            class="penci-upload-image-button button"><?php esc_html_e( 'Upload/Add image', 'soledad' ); ?></button>
                    <button type="button"
                            class="penci-remove-image-button button <?php echo $value ? '' : 'hidden' ?>"><?php esc_html_e( 'Remove image', 'soledad' ); ?></button>
                </div>
				<?php
				break;

			default:
				?>
                <input type="text" id="term-<?php echo esc_attr( $type ) ?>" name="<?php echo esc_attr( $type ) ?>"
                       value="<?php echo esc_attr( $value ) ?>"/>
				<?php
				break;
		}

		echo 'edit' == $form ? '</td></tr>' : '</div>';
	}

	public function save_term_meta( $term_id, $tt_id ) {
		foreach ( $this->types as $type => $label ) {
			if ( isset( $_POST[ $type ] ) ) {
				update_term_meta( $term_id, $type, sanitize_text_field( $_POST[ $type ] ) );
			}
		}
	}

	public function add_attribute_columns( $columns ) {
		$new_columns          = array();
		$new_columns['cb']    = isset( $columns['cb'] ) && $columns['cb'] ? $columns['cb'] : 2;
		$new_columns['thumb'] = '';
		unset( $columns['cb'] );

		return array_merge( $new_columns, $columns );
	}

	public function add_attribute_column_content( $columns, $column, $term_id ) {
		if ( 'thumb' !== $column ) {
			return $columns;
		}

		$attr  = $this->get_tax_attribute( $_REQUEST['taxonomy'] );
		$value = get_term_meta( $term_id, $attr->attribute_type, true );

		switch ( $attr->attribute_type ) {
			case 'color':
				printf( '<div class="swatch-preview swatch-color" style="background-color:%s;"></div>', esc_attr( $value ) );
				break;

			case 'image':
				$image = $value ? wp_get_attachment_image_src( $value ) : '';
				$image = $image ? $image[0] : WC()->plugin_url() . '/assets/images/placeholder.png';
				printf( '<img class="swatch-preview swatch-image" src="%s" width="44px" height="44px">', esc_url( $image ) );
				break;

			case 'label':
				printf( '<div class="swatch-preview swatch-label">%s</div>', esc_html( $value ) );
				break;
		}
	}

	public function get_swatch_html( $html, $args ) {
		$swatch_types = $this->types;
		$attr         = $this->get_tax_attribute( $args['attribute'] );

		// Return if this is normal attribute
		if ( empty( $attr ) ) {
			return $html;
		}

		if ( ! array_key_exists( $attr->attribute_type, $swatch_types ) ) {
			return $html;
		}

		$options   = $args['options'];
		$product   = $args['product'];
		$attribute = $args['attribute'];
		$class     = "variation-selector variation-select-{$attr->attribute_type}";
		$swatches  = '';

		// Add new option for tooltip to $args variable.
		$args['tooltip'] = wc_string_to_bool( get_option( 'penci_enable_tooltip', 'yes' ) );

		if ( empty( $options ) && ! empty( $product ) && ! empty( $attribute ) ) {
			$attributes = $product->get_variation_attributes();
			$options    = $attributes[ $attribute ];
		}

		if ( array_key_exists( $attr->attribute_type, $swatch_types ) ) {
			if ( ! empty( $options ) && $product && taxonomy_exists( $attribute ) ) {
				// Get terms if this is a taxonomy - ordered. We need the names too.
				$terms = wc_get_product_terms( $product->get_id(), $attribute, array( 'fields' => 'all' ) );

				foreach ( $terms as $term ) {
					if ( in_array( $term->slug, $options ) ) {
						$swatches .= apply_filters( 'penci_swatch_html', '', $term, $attr->attribute_type, $args );
					}
				}
			}

			if ( ! empty( $swatches ) ) {
				$class    .= ' hidden';
				$swatches = '<div class="penci-swatches" data-attribute_name="attribute_' . esc_attr( $attribute ) . '">' . $swatches . '</div>';
				$html     = '<div class="' . esc_attr( $class ) . '">' . $html . '</div>' . $swatches;
			}
		}

		return $html;
	}

	public function swatch_html( $html, $term, $type, $args ) {
		$selected = sanitize_title( $args['selected'] ) == $term->slug ? 'selected' : '';
		$name     = esc_html( apply_filters( 'woocommerce_variation_option_name', $term->name ) );
		$tooltip  = '';

		if ( ! empty( $args['tooltip'] ) ) {
			$tooltip = '<span class="swatch__tooltip">' . ( $term->description ? $term->description : $name ) . '</span>';
		}

		switch ( $type ) {
			case 'color':
				$color = get_term_meta( $term->term_id, 'color', true );
				list( $r, $g, $b ) = sscanf( $color, "#%02x%02x%02x" );
				$html = sprintf(
					'<span class="swatch swatch-color swatch-%s %s" style="background-color:%s;color:%s;" data-value="%s">%s%s</span>',
					esc_attr( $term->slug ),
					$selected,
					esc_attr( $color ),
					"rgba($r,$g,$b,0.5)",
					esc_attr( $term->slug ),
					$name,
					$tooltip
				);
				break;

			case 'image':
				$image = get_term_meta( $term->term_id, 'image', true );
				$image = $image ? wp_get_attachment_image_src( $image ) : '';
				$image = $image ? $image[0] : WC()->plugin_url() . '/assets/images/placeholder.png';
				$html  = sprintf(
					'<span class="swatch swatch-image swatch-%s %s" data-value="%s"><img src="%s" alt="%s">%s%s</span>',
					esc_attr( $term->slug ),
					$selected,
					esc_attr( $term->slug ),
					esc_url( $image ),
					esc_attr( $name ),
					$name,
					$tooltip
				);
				break;

			case 'label':
				$label = get_term_meta( $term->term_id, 'label', true );
				$label = $label ? $label : $name;
				$html  = sprintf(
					'<span class="swatch swatch-label swatch-%s %s" data-value="%s">%s%s</span>',
					esc_attr( $term->slug ),
					$selected,
					esc_attr( $term->slug ),
					esc_html( $label ),
					$tooltip
				);
				break;
		}

		return $html;
	}

	public function product_option_terms( $taxonomy, $index ) {
		if ( ! array_key_exists( $taxonomy->attribute_type, $this->types ) ) {
			return;
		}

		$taxonomy_name = wc_attribute_taxonomy_name( $taxonomy->attribute_name );
		global $thepostid;

		$product_id = isset( $_POST['post_id'] ) ? absint( $_POST['post_id'] ) : $thepostid;
		?>

        <select multiple="multiple" data-placeholder="<?php esc_attr_e( 'Select terms', 'soledad' ); ?>"
                class="multiselect attribute_values wc-enhanced-select"
                name="attribute_values[<?php echo $index; ?>][]">
			<?php

			$all_terms = get_terms( $taxonomy_name, apply_filters( 'woocommerce_product_attribute_terms', array(
				'orderby'    => 'name',
				'hide_empty' => false
			) ) );
			if ( $all_terms ) {
				foreach ( $all_terms as $term ) {
					echo '<option value="' . esc_attr( $term->term_id ) . '" ' . selected( has_term( absint( $term->term_id ), $taxonomy_name, $product_id ), true, false ) . '>' . esc_attr( apply_filters( 'woocommerce_product_attribute_term_name', $term->name, $term ) ) . '</option>';
				}
			}
			?>
        </select>
        <button class="button plus select_all_attributes"><?php esc_html_e( 'Select all', 'soledad' ); ?></button>
        <button class="button minus select_no_attributes"><?php esc_html_e( 'Select none', 'soledad' ); ?></button>
        <button class="button fr plus penci_add_new_attribute"
                data-type="<?php echo $taxonomy->attribute_type ?>"><?php esc_html_e( 'Add new', 'soledad' ); ?></button>

		<?php
	}

	public function add_new_attribute_ajax() {
		$nonce  = isset( $_POST['nonce'] ) ? $_POST['nonce'] : '';
		$tax    = isset( $_POST['taxonomy'] ) ? sanitize_text_field( $_POST['taxonomy'] ) : '';
		$type   = isset( $_POST['type'] ) ? sanitize_text_field( $_POST['type'] ) : '';
		$name   = isset( $_POST['name'] ) ? sanitize_text_field( $_POST['name'] ) : '';
		$slug   = isset( $_POST['slug'] ) ? sanitize_text_field( $_POST['slug'] ) : '';
		$swatch = isset( $_POST['swatch'] ) ? sanitize_text_field( $_POST['swatch'] ) : '';

		if ( ! wp_verify_nonce( $nonce, '_penci_create_attribute' ) ) {
			wp_send_json_error( esc_html__( 'Wrong request', 'soledad' ) );
		}

		if ( empty( $name ) || empty( $swatch ) || empty( $tax ) || empty( $type ) ) {
			wp_send_json_error( esc_html__( 'Not enough data', 'soledad' ) );
		}

		if ( ! taxonomy_exists( $tax ) ) {
			wp_send_json_error( esc_html__( 'Taxonomy is not exists', 'soledad' ) );
		}

		if ( term_exists( $_POST['name'], $_POST['tax'] ) ) {
			wp_send_json_error( esc_html__( 'This term is exists', 'soledad' ) );
		}

		$term = wp_insert_term( $name, $tax, array( 'slug' => $slug ) );

		if ( is_wp_error( $term ) ) {
			wp_send_json_error( $term->get_error_message() );
		} else {
			$term = get_term_by( 'id', $term['term_id'], $tax );
			update_term_meta( $term->term_id, $type, $swatch );
		}

		wp_send_json_success(
			array(
				'msg'  => esc_html__( 'Added successfully', 'soledad' ),
				'id'   => $term->term_id,
				'slug' => $term->slug,
				'name' => $term->name,
			)
		);
	}

	public function add_attribute_term_template() {
		global $pagenow, $post;

		if ( $pagenow != 'post.php' || ( isset( $post ) && get_post_type( $post->ID ) != 'product' ) ) {
			return;
		}
		?>

        <div id="penci-modal-container" class="penci-modal-container">
            <div class="penci-modal">
                <button type="button" class="button-link media-modal-close penci-modal-close">
                    <span class="media-modal-icon"></span></button>
                <div class="penci-modal-header"><h2><?php esc_html_e( 'Add new term', 'soledad' ) ?></h2></div>
                <div class="penci-modal-content">
                    <p class="penci-term-name">
                        <label>
							<?php esc_html_e( 'Name', 'soledad' ) ?>
                            <input type="text" class="widefat penci-input" name="name">
                        </label>
                    </p>
                    <p class="penci-term-slug">
                        <label>
							<?php esc_html_e( 'Slug', 'soledad' ) ?>
                            <input type="text" class="widefat penci-input" name="slug">
                        </label>
                    </p>
                    <div class="penci-term-swatch">

                    </div>
                    <div class="hidden penci-term-tax"></div>

                    <input type="hidden" class="penci-input" name="nonce"
                           value="<?php echo wp_create_nonce( '_penci_create_attribute' ) ?>">
                </div>
                <div class="penci-modal-footer">
                    <button class="button button-secondary penci-modal-close"><?php esc_html_e( 'Cancel', 'soledad' ) ?></button>
                    <button class="button button-primary penci-new-attribute-submit"><?php esc_html_e( 'Add New', 'soledad' ) ?></button>
                    <span class="message"></span>
                    <span class="spinner"></span>
                </div>
            </div>
            <div class="penci-modal-backdrop media-modal-backdrop"></div>
        </div>

        <script type="text/template" id="tmpl-penci-input-color">

            <label><?php echo penci_woo_translate_text( 'penci_woo_trans_color' ); ?></label><br>
            <input type="text" class="penci-input penci-input-color" name="swatch">

        </script>

        <script type="text/template" id="tmpl-penci-input-image">

            <label><?php penci_woo_translate_text( 'penci_woo_trans_image' ); ?></label><br>
            <div class="penci-term-image-thumbnail" style="float:left;margin-right:10px;">
                <img alt="" src="<?php echo esc_url( WC()->plugin_url() . '/assets/images/placeholder.png' ) ?>" width="60px"
                     height="60px"/>
            </div>
            <div style="line-height:60px;">
                <input type="hidden" class="penci-input penci-input-image penci-term-image" name="swatch" value=""/>
                <button type="button"
                        class="penci-upload-image-button button"><?php esc_html_e( 'Upload/Add image', 'soledad' ); ?></button>
                <button type="button"
                        class="penci-remove-image-button button hidden"><?php esc_html_e( 'Remove image', 'soledad' ); ?></button>
            </div>

        </script>

        <script type="text/template" id="tmpl-penci-input-label">

            <label>
				<?php esc_html_e( 'Label', 'soledad' ) ?>
                <input type="text" class="widefat penci-input penci-input-label" name="swatch">
            </label>

        </script>

        <script type="text/template" id="tmpl-penci-input-tax">

            <input type="hidden" class="penci-input" name="taxonomy" value="{{data.tax}}">
            <input type="hidden" class="penci-input" name="type" value="{{data.type}}">

        </script>
		<?php
	}

	public function product_swatches_list() {
		global $product;


		$id             = $product->get_id();
		$attribute_name = penci_get_single_product_meta( $id, 'product_extra_options', 'grid_swatch', get_theme_mod( 'penci_woocommerce_grid_swatch' ) );
		if ( empty( $id ) || ! $product->is_type( 'variable' ) ) {
			return false;
		}

		if ( ! $attribute_name ) {
			$attribute_name = 'pa_color';
		}

		if ( empty( $attribute_name ) ) {
			return false;
		}

		// Swatches cache
		$cache          = get_theme_mod( 'penci_woocommerce_grid_swatch_cache', true );
		$transient_name = 'penci_loop_swatches_cache_' . $id;

		if ( $cache ) {
			$available_variations = get_transient( $transient_name );
		} else {
			$available_variations = array();
		}

		if ( ! $available_variations ) {
			$available_variations = $product->get_available_variations();
			if ( $cache ) {
				set_transient( $transient_name, $available_variations, apply_filters( 'penci_swatches_cache_time', WEEK_IN_SECONDS ) );
			}
		}

		if ( empty( $available_variations ) ) {
			return false;
		}

		$swatches_to_show = $this->get_option_variations( $attribute_name, $available_variations, false, $id );

		if ( empty( $swatches_to_show ) ) {
			return false;
		}
		$out = '';

		$out .= '<div class="penci-swatches-list swatches-select">';

		if ( apply_filters( 'penci_swatches_on_grid_right_order', true ) ) {
			$terms = wc_get_product_terms( $product->get_id(), $attribute_name, array( 'fields' => 'slugs' ) );

			$swatches_to_show_tmp = $swatches_to_show;

			$swatches_to_show = array();

			foreach ( $terms as $id => $slug ) {
				if ( ! isset( $swatches_to_show_tmp[ $slug ] ) ) {
					continue;
				}
				$swatches_to_show[ $slug ] = $swatches_to_show_tmp[ $slug ];
			}
		}

		$index    = 0;
		$img_size = wc_get_loop_prop( 'img_size', 'woocommerce_thumbnail' );

		foreach ( $swatches_to_show as $key => $swatch ) {
			$style = $class = '';
			$term  = get_term_by( 'slug', $key, $attribute_name );

			$swatch_limit                  = get_theme_mod( 'penci_woocommerce_grid_swatch_limit', 3 );
			$swatches_limit                = true;
			$swatches_use_variation_images = false;
			if ( $swatches_limit && count( $swatches_to_show ) > (int) $swatch_limit ) {
				if ( $index >= $swatch_limit ) {
					$class .= ' hidden';
				}
				if ( $index === (int) $swatch_limit ) {
					$out .= '<div class="penci-swatches-divider">+' . ( count( $swatches_to_show ) - (int) $swatch_limit ) . '</div>';
				}
			}

			$index ++;

			if ( ! empty( $swatch['color'] ) ) {
				$style = 'background-color:' . $swatch['color'];
				$class .= ' swatch-with-bg';
			} elseif ( $swatches_use_variation_images && isset( $swatch['image_src'] ) ) {
				$thumb = wp_get_attachment_image_src( get_post_thumbnail_id( $swatch['variation_id'] ), $img_size );
				if ( ! empty( $thumb ) ) {
					$style = 'background-image: url(' . $thumb[0] . ')';
					$class .= ' swatch-with-bg bg-image penci-tooltip';
				}
			} elseif ( ! empty( $swatch['image'] ) ) {
				$style = 'background-image: url(' . wp_get_attachment_image_url( $swatch['image'], 'thumbnail' ) . ')';
				$class .= ' swatch-with-bg bg-image';
			} elseif ( ! empty( $swatch['not_dropdown'] ) ) {
				$class .= ' text-only ';
			} elseif ( ! empty( $swatch['label'] ) ) {
				$class .= ' label ';
			} else {
				$class      .= ' no-user-swatch ';
				$label_text = strtolower( $term->slug );
				if ( penci_product_is_color_name( $label_text ) ) {
					$class .= ' has-define-bg-color swatch-with-bg ';
					$style = 'background-color:' . $label_text;
				}
			}

			$style .= ';';

			$data = '';

			if ( isset( $swatch['image_src'] ) ) {
				$data .= 'data-image-src="' . $swatch['image_src'] . '"';
				$data .= ' data-image-srcset="' . $swatch['image_srcset'] . '"';
				$data .= ' data-image-sizes="' . $swatch['image_sizes'] . '"';

				if ( ! $swatch['is_in_stock'] ) {
					$class .= ' variation-out-of-stock';
				}
			}


			$out .= '<div data-tippy-content="' . $term->name . '" class="penci-swatch-item penci-swatch penci-tooltip' . esc_attr( $class ) . '" style="' . esc_attr( $style ) . '" ' . $data . '>' . $term->name . '</div>';
		}

		$out .= '</div>';

		echo $out;

	}

	public function get_option_variations( $attribute_name, $available_variations, $option = false, $product_id = false ) {
		$swatches_to_show = array();
		$img_size         = wc_get_loop_prop( 'img_size', 'woocommerce_thumbnail' );
		foreach ( $available_variations as $key => $variation ) {
			$option_variation = array();
			$attr_key         = 'attribute_' . $attribute_name;
			if ( ! isset( $variation['attributes'][ $attr_key ] ) ) {
				return;
			}


			$val = $variation['attributes'][ $attr_key ];

			if ( ! empty( $variation['image_id'] ) ) {
				$option_variation = array(
					'variation_id' => $variation['variation_id'],
					'is_in_stock'  => $variation['is_in_stock'],
					'image_src'    => wp_get_attachment_image_url( $variation['image_id'], $img_size ),
					'image_srcset' => wp_get_attachment_image_srcset( $variation['image_id'], $img_size ),
					'image_sizes'  => $variation['image']['sizes'],
				);
			}

			// Get only one variation by attribute option value
			if ( $option ) {
				if ( $val != $option ) {
					continue;
				} else {
					return $option_variation;
				}
			} else {
				// Or get all variations with swatches to show by attribute name
				$swatch                   = $this->has_swatch( $product_id, $attribute_name, $val );
				$swatches_to_show[ $val ] = array_merge( $swatch, $option_variation );

			}
		}

		return $swatches_to_show;

	}

	public function has_swatch( $id, $attr_name, $value ) {
		$swatches = array();

		$color = $image = $label = '';

		$term = get_term_by( 'slug', $value, $attr_name );
		if ( is_object( $term ) ) {
			$color = get_term_meta( $term->term_id, 'color', true );
			$image = get_term_meta( $term->term_id, 'image', true );
			$label = get_term_meta( $term->term_id, 'label', true );
		}

		if ( $color != '' ) {
			$swatches['color'] = $color;
		}

		if ( $image != '' ) {
			$swatches['image'] = $image;
		}

		if ( $label != '' ) {
			$swatches['label'] = $label;
		}

		return $swatches;
	}

	public function deleted_transitent( $post ) {
		$transient_name = 'penci_loop_swatches_cache_' . $post;
		delete_transient( $transient_name );
	}

	public function preload_icon() {
		echo '<div class="penci-image-loader"></div>';
	}

}

$penci_product_swatches = new penci_product_swatches();
