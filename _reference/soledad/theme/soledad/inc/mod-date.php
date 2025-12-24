<?php

class PenciModDate {

	/**
	 * Register functions.
	 */
	public function __construct() {
		add_action( 'post_submitbox_misc_actions', [ $this, 'submitbox_edit' ], 5 );
		add_action( 'quick_edit_custom_box', [ $this, 'quick_edit' ], 10, 2 );
		add_action( 'bulk_edit_custom_box', [ $this, 'bulk_edit' ], 10, 2 );
		add_action( 'wp_ajax_process_bulk_edit', [ $this, 'bulk_save' ] );
		add_filter( 'wp_insert_post_data', [ $this, 'update_data' ], 9999, 2 );
		add_action( 'admin_init', function () {
			add_action( 'save_post', [ $this, 'save_post' ] );
			add_filter( 'post_updated_messages', [ $this, 'messages' ] );
		} );
		add_action( 'enqueue_block_editor_assets', [ $this, 'assets' ] );
		add_action( 'init', [ $this, 'register_meta' ] );
		if ( get_theme_mod( 'penci_lastmodified_pcol' ) ) {
			add_action( 'admin_init', [ $this, 'generate_column' ] );
		}
		add_action( 'admin_enqueue_scripts', [ $this, 'files' ] );

		$post_types = get_post_types( [ 'show_in_rest' => true ] );
		foreach ( $post_types as $post_type ) {
			add_filter( "rest_pre_insert_$post_type", [ $this, 'modified_params' ], 99, 2 );
		}
	}

	public function files( $hook ) {
		if ( 'edit.php' === $hook ) {

			wp_enqueue_script( 'pcmdate-edit', PENCI_SOLEDAD_URL . '/js/admin-edit.js', [ 'jquery' ], PENCI_SOLEDAD_VERSION, true );

			wp_localize_script( 'pcmdate-edit', 'pcmdate_edit_L10n', [
				'ajaxurl'  => admin_url( 'admin-ajax.php' ),
				'security' => wp_create_nonce( 'pcmdate_edit_nonce' ),
			] );
		}
	}

	public function generate_column() {
		// get all post types
		$post_types = get_post_types( $this->do_filter( 'admin_column_post_types', [] ) );
		if ( ! empty( $post_types ) ) {
			foreach ( $post_types as $post_type ) {
				add_filter( "manage_edit-{$post_type}_columns", [ $this, 'column_title' ] );
				add_action( "manage_edit-{$post_type}_sortable_columns", [ $this, 'column_sortable' ], 10, 2 );
				add_filter( "manage_{$post_type}_posts_columns", [ $this, 'column_title' ] );
				add_filter( "manage_{$post_type}_sortable_columns", [ $this, 'column_sortable' ], 10, 2 );
				add_action( "manage_{$post_type}_posts_custom_column", [ $this, 'column_data' ], 10, 2 );
			}
		}
	}

	public function column_sortable( $column ) {
		$column['lastmodified'] = 'modified';

		return $column;
	}

	public function column_data( $column, $post_id ) {
		if ( 'lastmodified' === $column ) {
			$post = get_post( $post_id );

			$get_df     = get_option( 'date_format' );
			$get_tf     = get_option( 'time_format' );
			$m_orig     = get_post_field( 'post_modified', $post->ID, 'raw' );
			$p_meta     = get_post_meta( $post->ID, '_lmt_disableupdate', true );
			$modified   = date_i18n( $this->do_filter( 'admin_column_datetime_format', $get_df . ' \a\t ' . $get_tf, $post->ID ), strtotime( $m_orig ) );
			$mod_format = date( 'M d, Y H:i:s', strtotime( $m_orig ) );
			$disabled   = ( ! empty( $p_meta ) ) ? 'yes' : 'no';

			$html = $modified;
			if ( get_the_modified_author() ) {
				$html .= '<br>' . __( 'by', 'soledad' ) . ' <strong>' . esc_html( get_the_modified_author() ) . '</strong>';
			}
			if ( ! in_array( $post->post_status, [ 'auto-draft', 'future' ] ) ) {
				if ( $p_meta == 'yes' ) {
					$html .= ' <span class="pcmdate-lock dashicons dashicons-lock" title="' . esc_attr__( 'Modified date time update is disabled.', 'soledad' ) . '" style="font-size:16px; padding-top: 3px;"></span>';
				}
				$html .= '<span class="pcmdate-hidden-date-format" style="display: none;">' . esc_html( $mod_format ) . '</span>';
				$html .= '<span class="pcmdate-hidden-post-modified" style="display: none;">' . esc_html( $post->post_modified ) . '</span>';
				$html .= '<span class="pcmdate-hidden-disabled" style="display: none;">' . esc_html( $disabled ) . '</span>';
				$html .= '<span class="pcmdate-hidden-post-type" style="display: none;">' . esc_html( $post->post_type ) . '</span>';
			}
			$html .= '<span class="pcmdate-hidden-status" style="display: none;">' . esc_html( $post->post_status ) . '</span>';

			echo $html;
		}
	}

	public function column_title( $column ) {
		$column['lastmodified'] = __( 'Last Modified', 'soledad' );

		return $column;
	}

	protected function filter( $tag, $function_to_add, $priority = 10, $accepted_args = 1 ) {
		return \add_filter( $tag, [ $this, $function_to_add ], $priority, $accepted_args );
	}

	public function register_meta() {
		register_meta(
			'post',
			'_lmt_disableupdate',
			[
				'show_in_rest'      => true,
				'type'              => 'string',
				'single'            => true,
				'sanitize_callback' => 'sanitize_text_field',
				'auth_callback'     => function () {
					return current_user_can( 'edit_posts' );
				},
			]
		);

		register_meta(
			'post',
			'_lmt_disable',
			[
				'show_in_rest'      => true,
				'type'              => 'string',
				'single'            => true,
				'sanitize_callback' => 'sanitize_text_field',
				'auth_callback'     => function () {
					return current_user_can( 'edit_posts' );
				},
			]
		);
	}

	public function modified_params( $prepared_post, $request ) {
		if ( 'PUT' !== $request->get_method() ) {
			return $prepared_post;
		}

		$params = $request->get_params();

		if ( isset( $params['modified'] ) ) {
			$prepared_post->pcmdate_modified_rest = $params['modified'];
		}

		if ( isset( $params['meta']['_lmt_disableupdate'] ) ) {
			$prepared_post->pcmdate_lockmodifiedupdate = $params['meta']['_lmt_disableupdate'];
		}

		return $prepared_post;
	}

	public function assets() {
		global $post;

		if ( ! $post instanceof \WP_Post ) {
			return; // load assets only in post edit screen.
		}

		$asset_file = include( PENCI_SOLEDAD_DIR . '/inc/block-editor/build/index.asset.php' );

		wp_enqueue_script( 'pcmdate-block-editor', PENCI_SOLEDAD_URL . '/inc/block-editor/build/index.js', $asset_file['dependencies'], $asset_file['version'], true );
		wp_localize_script( 'pcmdate-block-editor', 'pcmdateBlockEditor', [
			'postTypes' => [ 'post' ],
			'isEnabled' => true,
		] );
	}

	/**
	 * Post Submit Box HTML output.
	 *
	 * @param string $post WP Post
	 */
	public function submitbox_edit( $post ) {
		global $wp_locale;

		if ( in_array( $post->post_status, [ 'auto-draft', 'future' ] ) ) {
			return;
		}

		$datemodified = $post->post_modified;

		$jj = mysql2date( 'd', $datemodified, false );
		$mm = mysql2date( 'm', $datemodified, false );
		$aa = mysql2date( 'Y', $datemodified, false );
		$hh = mysql2date( 'H', $datemodified, false );
		$mn = mysql2date( 'i', $datemodified, false );
		$ss = mysql2date( 's', $datemodified, false );

		$stop_update = get_post_meta( $post->ID, '_lmt_disableupdate', true );
		$post_types  = get_post_type_object( $post->post_type );

		// get modified time with a particular format
		$orig_time = get_post_time( 'U', false, $post );
		$mod_time  = get_post_modified_time( 'U', false, $post ); ?>

        <div class="misc-pub-section curtime misc-pub-last-updated">
        <span id="pcmdate-timestamp"> <?php esc_html_e( 'Updated on:', 'soledad' ) ?> <strong><?php echo get_the_modified_time( 'M j, Y \a\t H:i' ); ?></strong></span>
        <a href="#edit_timestampmodified" class="edit-timestampmodified hide-if-no-js" role="button"><span
                    aria-hidden="true"><?php esc_html_e( 'Edit', 'soledad' ); ?></span> <span
                    class="screen-reader-text"><?php esc_html_e( 'Edit modified date and time', 'soledad' ); ?></span></a>
        <fieldset id="timestampmodifieddiv" class="hide-if-js"
                  data-prefix="<?php esc_attr_e( 'Updated on:', 'soledad' ); ?>"
                  data-separator="<?php esc_attr_e( 'at', 'soledad' ); ?>"
                  style="padding-top: 5px;line-height: 1.76923076;">
            <legend class="screen-reader-text"><?php esc_html_e( 'Last modified date and time', 'soledad' ); ?></legend>
            <div class="timestamp-wrap">
                <label>
                    <span class="screen-reader-text"><?php esc_html_e( 'Month', 'soledad' ); ?></span>
                    <select id="mmm" name="mmm" class="time-modified">
						<?php
						for ( $i = 1; $i < 13; $i ++ ) {
							$monthnum  = zeroise( $i, 2 );
							$monthtext = $wp_locale->get_month_abbrev( $wp_locale->get_month( $i ) );
							echo '<option value="' . $monthnum . '" data-text="' . $monthtext . '" ' . selected( $monthnum, $mm, false ) . '>' . sprintf( __( '%1$s-%2$s' ), $monthnum, $monthtext ) . '</option>';
						}
						?>
                    </select>
                </label>
                <label>
                    <span class="screen-reader-text"><?php esc_html_e( 'Day', 'soledad' ); ?></span>
                    <input type="text" id="jjm" class="time-modified jjm-edit" name="jjm" value="<?php echo $jj; ?>"
                           size="2" maxlength="2" autocomplete="off"/>
                </label>, <label>
                    <span class="screen-reader-text"><?php esc_html_e( 'Year', 'soledad' ); ?></span>
                    <input type="text" id="aam" class="time-modified aam-edit" name="aam" value="<?php echo $aa; ?>"
                           size="4" maxlength="4" autocomplete="off"/>
                </label> <?php esc_html_e( 'at', 'soledad' ); ?><label>
                    <span class="screen-reader-text"><?php esc_html_e( 'Hour', 'soledad' ); ?></span>
                    <input type="text" id="hhm" class="time-modified hhm-edit" name="hhm" value="<?php echo $hh; ?>"
                           size="2" maxlength="2" autocomplete="off"/>
                </label><?php esc_html_e( ':', 'soledad' ); ?><label>
                    <span class="screen-reader-text"><?php esc_html_e( 'Minute', 'soledad' ); ?></span>
                    <input type="text" id="mnm" class="time-modified mnm-edit" name="mnm" value="<?php echo $mn; ?>"
                           size="2" maxlength="2" autocomplete="off"/>
                </label>
            </div>
            <label for="pcmdate_disable" class="pcmdate-disable-update" style="display:block;margin: 5px 0;"
                   title="<?php esc_attr_e( 'Keep this checked, if you do not want to change modified date and time on this post.', 'soledad' ); ?>">
                <input type="checkbox" id="pcmdate_disable" name="disableupdate" <?php if ( $stop_update == 'yes' ) {
					echo 'checked';
				} ?>><span><?php esc_html_e( 'Lock the Modified Date', 'soledad' ); ?></span>
            </label>
			<?php

			$currentlocal = current_time( 'timestamp', 0 );
			$mm_current   = gmdate( 'm', $currentlocal );
			$jj_current   = gmdate( 'd', $currentlocal );
			$aa_current   = gmdate( 'Y', $currentlocal );
			$hh_current   = gmdate( 'H', $currentlocal );
			$mn_current   = gmdate( 'i', $currentlocal );

			$vals = [
				'mmm' => [ $mm, $mm_current ],
				'jjm' => [ $jj, $jj_current ],
				'aam' => [ $aa, $aa_current ],
				'hhm' => [ $hh, $hh_current ],
				'mnm' => [ $mn, $mn_current ],
			];

			foreach ( $vals as $key => $val ) {
				echo '<input type="hidden" id="hidden_' . $key . '" name="hidden_' . $key . '" value="' . $val[0] . '">';
				echo '<input type="hidden" id="cur_' . $key . '" name="cur_' . $key . '" value="' . $val[1] . '">';
			} ?>

            <input type="hidden" id="ssm" name="ssm" value="<?php echo $ss; ?>">
            <input type="hidden" id="pcmdate-change-modified" name="pcmdate_change" value="no">
            <input type="hidden" id="pcmdate-disable-hidden" name="pcmdate_disable"
                   value="<?php echo ( $stop_update ) ? (bool) $stop_update : 'no'; ?>">
            <input type="hidden" id="pcmdate-post-modified" name="pcmdate_modified"
                   value="<?php echo $datemodified; ?>">

            <p id="pcmdate-meta" class="pcmdate-meta-options">
                <a href="#edit_timestampmodified"
                   class="save-timestamp hide-if-no-js button"><?php esc_html_e( 'OK', 'soledad' ); ?></a>
                <a href="#edit_timestampmodified"
                   class="cancel-timestamp hide-if-no-js button-cancel"><?php esc_html_e( 'Cancel', 'soledad' ); ?></a>&nbsp;&nbsp;&nbsp;
            </p>
        </fieldset>
        </div><?php
	}

	/**
	 * Quick edit HTML output.
	 *
	 * @param string $column_name Current column name
	 * @param string $post_type Post type
	 */
	public function quick_edit( $column_name, $post_type ) {
		global $wp_locale;

		if ( 'lastmodified' !== $column_name ) {
			return;
		} ?>

        <fieldset id="inline-edit-col-modified-date" class="inline-edit-date">
            <legend><span class="title"><?php esc_html_e( 'Modified', 'soledad' ); ?></span></legend>
            <div class="timestamp-wrap">
                <label class="inline-edit-group">
                    <span class="screen-reader-text"><?php esc_html_e( 'Month', 'soledad' ); ?></span>
                    <select id="mmm" class="time-modified" name="mmm">
						<?php
						for ( $i = 1; $i < 13; $i ++ ) {
							$monthnum  = zeroise( $i, 2 );
							$monthtext = $wp_locale->get_month_abbrev( $wp_locale->get_month( $i ) );
							echo '<option value="' . $monthnum . '" data-text="' . $monthtext . '">' . sprintf( __( '%1$s-%2$s' ), $monthnum, $monthtext ) . '</option>';
						}
						?>
                    </select>
                </label>
                <label class="inline-edit-group">
                    <span class="screen-reader-text"><?php esc_html_e( 'Day', 'soledad' ); ?></span>
                    <input type="text" id="jjm" class="time-modified tm-jjm" name="jjm" value="" size="2" maxlength="2"
                           autocomplete="off"/>
                </label>, <label>
                    <span class="screen-reader-text"><?php esc_html_e( 'Year', 'soledad' ); ?></span>
                    <input type="text" id="aam" class="time-modified tm-aam" name="aam" value="" size="4" maxlength="4"
                           autocomplete="off"/>
                </label> <?php esc_html_e( 'at', 'soledad' ); ?> <label>
                    <span class="screen-reader-text"><?php esc_html_e( 'Hour', 'soledad' ); ?></span>
                    <input type="text" id="hhm" class="time-modified tm-hhm" name="hhm" value="" size="2" maxlength="2"
                           autocomplete="off"/>
                </label>:<label>
                    <span class="screen-reader-text"><?php esc_html_e( 'Minute', 'soledad' ); ?></span>
                    <input type="text" id="mnm" class="time-modified tm-mnm" name="mnm" value="" size="2" maxlength="2"
                           autocomplete="off"/>
                </label>&nbsp;&nbsp;<label for="pcmdate_disable">
                    <input type="checkbox" id="pcmdate_disable" name="disableupdate"/>
                    <span class="checkbox-title"><?php esc_html_e( 'Lock the Modified Date', 'soledad' ); ?></span>
                </label>
            </div>
            <input type="hidden" id="ssm" name="ssm" value="">
            <input type="hidden" id="pcmdate-change-modified" name="pcmdate_change" value="no">
            <input type="hidden" id="pcmdate-disable-hidden" name="pcmdate_disable" value="">
            <input type="hidden" id="pcmdate-post-modified" name="pcmdate_modified" value="">
        </fieldset>
		<?php
	}

	/**
	 * Quick ecit HTML output.
	 *
	 * @param string $column_name Current column name
	 * @param string $post_type Post type
	 */
	public function bulk_edit( $column_name, $post_type ) {
		global $wp_locale;

		if ( 'lastmodified' !== $column_name ) {
			return;
		} ?>

        <div class="inline-edit-col pcmdate-bulkedit">
            <fieldset class="inline-edit-date">
                <legend><span class="title"><?php esc_html_e( 'Modified', 'soledad' ); ?></span></legend>
                <div class="timestamp-wrap">
                    <label class="inline-edit-group">
                        <span class="screen-reader-text"><?php esc_html_e( 'Month', 'soledad' ); ?></span>
                        <select id="mmm" class="time-modified" name="mmm">
                            <option value="none">— <?php esc_html_e( 'No Change', 'soledad' ); ?>—
                            </option>
							<?php
							for ( $i = 1; $i < 13; $i ++ ) {
								$monthnum  = zeroise( $i, 2 );
								$monthtext = $wp_locale->get_month_abbrev( $wp_locale->get_month( $i ) );
								echo '<option value="' . $monthnum . '" data-text="' . $monthtext . '">' . sprintf( __( '%1$s-%2$s' ), $monthnum, $monthtext ) . '</option>';
							}
							?>
                        </select>
                    </label>
                    <label class="inline-edit-group">
                        <span class="screen-reader-text"><?php esc_html_e( 'Day', 'soledad' ); ?></span>
                        <input type="text" id="jjm" class="time-modified tm-jjm" name="jjm" value="" size="2"
                               maxlength="2" placeholder="<?php esc_attr_e( 'Day', 'soledad' ); ?>"
                               autocomplete="off"/>
                    </label>, <label>
                        <span class="screen-reader-text"><?php esc_html_e( 'Year', 'soledad' ); ?></span>
                        <input type="text" id="aam" class="time-modified tm-aam" name="aam" value="" size="4"
                               maxlength="4" placeholder="<?php esc_attr_e( 'Year', 'soledad' ); ?>"
                               autocomplete="off"/>
                    </label> <?php esc_html_e( 'at', 'soledad' ); ?> <label>
                        <span class="screen-reader-text"><?php esc_html_e( 'Hour', 'soledad' ); ?></span>
                        <input type="text" id="hhm" class="time-modified tm-hhm" name="hhm" value="" size="2"
                               maxlength="2" placeholder="<?php esc_attr_e( 'Hour', 'soledad' ); ?>"
                               autocomplete="off"/>
                    </label>:<label>
                        <span class="screen-reader-text"><?php esc_html_e( 'Minute', 'soledad' ); ?></span>
                        <input type="text" id="mnm" class="time-modified tm-mnm" name="mnm" value="" size="2"
                               maxlength="2" placeholder="<?php esc_attr_e( 'Min', 'soledad' ); ?>"
                               autocomplete="off"/>
                    </label>&nbsp;&nbsp;<label for="pcmdate_disable">
                        <span class="select-title"><?php esc_html_e( 'Update Status', 'soledad' ); ?></span>
                        <select id="pcmdate-disable-update" class="disable-update" name="disable_update">
                            <option value="none">— <?php esc_html_e( 'No Change', 'soledad' ); ?>—
                            </option>
                            <option value="yes"><?php esc_html_e( 'Lock the Modified Date', 'soledad' ); ?></option>
                            <option value="no"><?php esc_html_e( 'Un-Lock the Modified Date', 'soledad' ); ?></option>
                        </select>
                    </label>
                </div>
            </fieldset>
        </div>
		<?php
	}

	public function save_post( $post_id ) {
		// get WordPress date time format
		$get_df = get_option( 'date_format' );
		$get_tf = get_option( 'time_format' );
		// get post meta data
		$m_orig   = get_post_field( 'post_modified', $post_id, 'raw' );
		$m_stamp  = strtotime( $m_orig );
		$modified = date_i18n( $this->do_filter( 'custom_field_date_time_format', $get_df . ' @ ' . $get_tf ), $m_stamp );


		// update post meta
		update_post_meta( $post_id, 'wp_last_modified_info', $modified );
	}

	protected function do_filter( ...$args ) {
		if ( ! isset( $args[0] ) || empty( $args[0] ) ) {
			return;
		}

		$action = 'pcmdate/' . $args[0];
		unset( $args[0] );

		return apply_filters_ref_array( $action, array_merge( [], $args ) );
	}

	/**
	 * Process bulk post meta data update
	 */
	public function bulk_save() {
		// security check
		wp_verify_nonce( 'pcmdate_edit_nonce' );

		// we need the post IDs
		$post_ids = ( ! empty( $_POST['post_ids'] ) ) ? wp_parse_id_list( $_POST['post_ids'] ) : [];

		// if we have post IDs
		if ( ! empty( $post_ids ) && is_array( $post_ids ) ) {
			$mmm     = sanitize_text_field( $_POST['modified_month'] );
			$jj      = sanitize_text_field( $_POST['modified_day'] );
			$aa      = sanitize_text_field( $_POST['modified_year'] );
			$hh      = sanitize_text_field( $_POST['modified_hour'] );
			$mn      = sanitize_text_field( $_POST['modified_minute'] );
			$disable = sanitize_text_field( $_POST['modified_disable'] );

			$mm = ( is_numeric( $mmm ) && $mmm <= 12 ) ? $mmm : '01'; // months
			$jj = ( is_numeric( $jj ) && $jj <= 31 ) ? $jj : '01'; // days
			$aa = ( is_numeric( $aa ) && $aa >= 0 ) ? $aa : '1970'; // years
			$hh = ( is_numeric( $hh ) && $hh <= 24 ) ? $hh : '12'; // hours
			$mn = ( is_numeric( $mn ) && $mn <= 60 ) ? $mn : '00'; // minutes
			$ss = '00'; // seconds

			$newdate = sprintf( "%04d-%02d-%02d %02d:%02d:%02d", $aa, $mm, $jj, $hh, $mn, $ss );

			foreach ( $post_ids as $post_id ) {
				if ( ! in_array( get_post_status( $post_id ), [ 'auto-draft', 'future' ] ) ) {
					if ( 'none' !== $mmm ) {
						update_post_meta( $post_id, '_pcmdate_last_modified', $newdate );
						update_post_meta( $post_id, 'pcmdate_bulk_update_datetime', $newdate );
					}

					if ( 'none' !== $disable ) {
						update_post_meta( $post_id, '_lmt_disableupdate', $disable );
					}
				}
			}
		}

		wp_send_json_success();
	}

	/**
	 * Save post modified info to db.
	 *
	 * @param object $data Old Data
	 * @param object $postarr Current Data
	 *
	 * @return object  $data
	 */
	public function update_data( $data, $postarr ) {
		if ( ! isset( $postarr['ID'] ) || in_array( $postarr['post_status'], [ 'auto-draft', 'future' ] ) ) {
			return $data;
		}

		if ( $this->do_filter( 'force_update_author_id', false ) ) {
			update_post_meta( $postarr['ID'], '_edit_last', get_current_user_id() );
		}

		// Check bulk edit state
		$bulk_datetime = get_post_meta( $postarr['ID'], 'pcmdate_bulk_update_datetime', true );
		if ( ! empty( $bulk_datetime ) ) {
			$data['post_modified']     = $bulk_datetime;
			$data['post_modified_gmt'] = get_gmt_from_date( $bulk_datetime );

			delete_post_meta( $postarr['ID'], 'pcmdate_bulk_update_datetime' );

			return $data;
		}

		// Get disable state.
		$disabled = get_post_meta( $postarr['ID'], '_lmt_disableupdate', true );

		/**
		 * Handle post editor save
		 */
		if ( ! isset( $postarr['pcmdate_modified'], $postarr['pcmdate_change'], $postarr['pcmdate_disable'] ) ) {
			/**
			 * Handle Block editor save
			 */
			if ( ! empty( $postarr['pcmdate_lockmodifiedupdate'] ) ) {
				$disabled = $postarr['pcmdate_lockmodifiedupdate'];
			}

			if ( 'yes' === $disabled ) {
				$data['post_modified']     = $postarr['post_modified'];
				$data['post_modified_gmt'] = $postarr['post_modified_gmt'];
			} else {
				$data['post_modified']     = current_time( 'mysql' );
				$data['post_modified_gmt'] = current_time( 'mysql', 1 );
			}

			// Check the duplicate request.
			$temp_date = get_post_meta( $postarr['ID'], 'pcmdate_temp_date', true );
			if ( ! empty( $postarr['pcmdate_modified_rest'] ) ) {
				$published_timestamp = get_post_time( 'U', false, $postarr['ID'] );
				$modified_timestamp  = strtotime( $postarr['pcmdate_modified_rest'] );
				$modified_date       = date( 'Y-m-d H:i:s', $modified_timestamp );

				if ( $modified_timestamp >= $published_timestamp ) {
					$data['post_modified']     = $modified_date;
					$data['post_modified_gmt'] = get_gmt_from_date( $modified_date );

					update_post_meta( $postarr['ID'], 'pcmdate_temp_date', $modified_date );
				}
			} elseif ( ! empty( $temp_date ) ) {
				$data['post_modified']     = $temp_date;
				$data['post_modified_gmt'] = get_gmt_from_date( $temp_date );

				delete_post_meta( $postarr['ID'], 'pcmdate_temp_date' );
			}
		} else {
			/**
			 * Handle Classic editor save
			 */
			$modified = ! empty( $postarr['pcmdate_modified'] ) ? sanitize_text_field( $postarr['pcmdate_modified'] ) : $postarr['post_modified'];
			$change   = ! empty( $postarr['pcmdate_change'] ) ? sanitize_text_field( $postarr['pcmdate_change'] ) : 'no';
			$disabled = ! empty( $postarr['pcmdate_disable'] ) ? sanitize_text_field( $postarr['pcmdate_disable'] ) : $disabled;

			// Update meta
			update_post_meta( $postarr['ID'], '_lmt_disableupdate', $disabled );

			// Check if disable is set to 'yes'
			if ( 'yes' === $disabled ) {
				$data['post_modified']     = $modified;
				$data['post_modified_gmt'] = get_gmt_from_date( $modified );
			} else {
				$data['post_modified']     = current_time( 'mysql' );
				$data['post_modified_gmt'] = current_time( 'mysql', 1 );
			}

			// check is current state is changed
			if ( 'yes' === $change ) {
				$mm = sanitize_text_field( $postarr['mmm'] );
				$jj = sanitize_text_field( $postarr['jjm'] );
				$aa = sanitize_text_field( $postarr['aam'] );
				$hh = sanitize_text_field( $postarr['hhm'] );
				$mn = sanitize_text_field( $postarr['mnm'] );
				$ss = sanitize_text_field( $postarr['ssm'] );

				$mm = ( is_numeric( $mm ) && $mm <= 12 ) ? $mm : '01'; // months
				$jj = ( is_numeric( $jj ) && $jj <= 31 ) ? $jj : '01'; // days
				$aa = ( is_numeric( $aa ) && $aa >= 0 ) ? $aa : '1970'; // years
				$hh = ( is_numeric( $hh ) && $hh <= 24 ) ? $hh : '12'; // hours
				$mn = ( is_numeric( $mn ) && $mn <= 60 ) ? $mn : '00'; // minutes
				$ss = ( is_numeric( $ss ) && $ss <= 60 ) ? $ss : '00'; // seconds

				$newdate             = sprintf( "%04d-%02d-%02d %02d:%02d:%02d", $aa, $mm, $jj, $hh, $mn, $ss );
				$published_timestamp = get_post_time( 'U', false, $postarr['ID'] );

				if ( strtotime( $newdate ) >= $published_timestamp ) {
					$data['post_modified']     = $newdate;
					$data['post_modified_gmt'] = get_gmt_from_date( $newdate );
				}
			}
		}

		// Update post meta for future reference
		update_post_meta( $postarr['ID'], '_pcmdate_last_modified', $data['post_modified'] );

		return $data;
	}

	public function messages( $messages ) {
		global $post;

		if ( ! $post instanceof WP_Post ) {
			if ( empty( $_REQUEST['post'] ) ) {
				return $messages;
			}
			$post_id = absint( $_REQUEST['post'] );
			$post    = get_post( $post_id );
		}

		// get WordPress date time format
		$get_df = get_option( 'date_format' );
		$get_tf = get_option( 'time_format' );

		$m_orig        = get_post_field( 'post_modified', $post->ID, 'raw' );
		$post_modified = '&nbsp;<strong>' . date_i18n( $this->do_filter( 'post_updated_datetime_format', $get_df . ' @ ' . $get_tf, $post->ID ), strtotime( $m_orig ) ) . '</strong>.&nbsp;';

		// get post types returns object
		$post_types = get_post_type_object( $post->post_type );
		if ( is_post_type_viewable( $post->post_type ) ) {
			$post_modified .= sprintf( __( '<a href="%1$s" target="_blank">%2$s %3$s</a>' ), esc_url( get_permalink( $post->ID ) ), __( 'View', 'soledad' ), $post_types->name );
		}

		if ( isset( $messages[ $post->post_type ] ) ) {
			$messages[ $post->post_type ][1] = esc_html( $post_types->labels->singular_name ) . '&nbsp;' . sprintf( __( '%1$s%2$s' ), __( 'updated on', 'soledad' ), $post_modified );
		}

		return $messages;
	}
}

new PenciModDate();