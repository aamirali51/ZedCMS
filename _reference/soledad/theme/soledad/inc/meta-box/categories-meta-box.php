<?php
/**
 * Hook to create meta box in categories edit screen
 *
 * @since 1.0
 */

// Create markup
if ( ! function_exists( 'penci_category_fields_meta' ) ) {
	add_action( 'category_edit_form', 'penci_category_fields_meta', 0 );
	function penci_category_fields_meta( $tag ) {
		$t_id                     = $tag->term_id;
		$penci_categories         = get_option( "category_$t_id" );
		$mag_layout               = isset( $penci_categories['mag_layout'] ) ? $penci_categories['mag_layout'] : 'style-1';
		$mag_ads                  = isset( $penci_categories['mag_ads'] ) ? $penci_categories['mag_ads'] : '';
		$cat_layout               = isset( $penci_categories['cat_layout'] ) ? $penci_categories['cat_layout'] : '';
		$cat_sidebar              = isset( $penci_categories['cat_sidebar'] ) ? $penci_categories['cat_sidebar'] : '';
		$cat_sidebar_left         = isset( $penci_categories['cat_sidebar_left'] ) ? $penci_categories['cat_sidebar_left'] : '';
		$cat_sidebar_display      = isset( $penci_categories['cat_sidebar_display'] ) ? $penci_categories['cat_sidebar_display'] : '';
		$cat_header               = isset( $penci_categories['cat_header'] ) ? $penci_categories['cat_header'] : '';
		$cat_header_builder       = isset( $penci_categories['cat_header_builder'] ) ? $penci_categories['cat_header_builder'] : '';
		$cat_header_block         = isset( $penci_categories['cat_header_block'] ) ? $penci_categories['cat_header_block'] : '';
		$cat_header_single        = isset( $penci_categories['cat_header_single'] ) ? $penci_categories['cat_header_single'] : '';
		$cat_footer_builder       = isset( $penci_categories['cat_footer'] ) ? $penci_categories['cat_footer'] : '';
		$cat_footer_single        = isset( $penci_categories['cat_footer_single'] ) ? $penci_categories['cat_footer_single'] : '';
		$cat_sidebar_single       = isset( $penci_categories['cat_sidebar_single'] ) ? $penci_categories['cat_sidebar_single'] : '';
		$penci_critical_css       = isset( $penci_categories['penci_critical_css'] ) ? $penci_categories['penci_critical_css'] : '';
		$alayout_save_slug        = isset( $penci_categories['penci_archive_layout'] ) ? $penci_categories['penci_archive_layout'] : '';
		$archive_bgcolor          = isset( $penci_categories['penci_archive_bgcolor'] ) ? $penci_categories['penci_archive_bgcolor'] : '';
		$archive_acolor           = isset( $penci_categories['penci_archive_acolor'] ) ? $penci_categories['penci_archive_acolor'] : '';
		$penci_archive_gtextcolor = isset( $penci_categories['penci_archive_gtextcolor'] ) ? $penci_categories['penci_archive_gtextcolor'] : '';
		$penci_archive_cbgcolor   = isset( $penci_categories['penci_archive_cbgcolor'] ) ? $penci_categories['penci_archive_cbgcolor'] : '';
		$penci_archive_bdcolor    = isset( $penci_categories['penci_archive_bdcolor'] ) ? $penci_categories['penci_archive_bdcolor'] : '';
		$archive_color            = isset( $penci_categories['penci_archive_color'] ) ? $penci_categories['penci_archive_color'] : '';
		$archivepage_color        = isset( $penci_categories['penci_archivepage_color'] ) ? $penci_categories['penci_archivepage_color'] : '';
		$cat_magazine             = isset( $penci_categories['cat_magazine'] ) ? $penci_categories['cat_magazine'] : '';
		$penci_single_overide     = isset( $penci_categories['penci_single_overide'] ) ? $penci_categories['penci_single_overide'] : '';
		$penci_single_layout      = isset( $penci_categories['penci_single_layout'] ) ? $penci_categories['penci_single_layout'] : '';
		$penci_single_builder     = isset( $penci_categories['penci_single_builder'] ) ? $penci_categories['penci_single_builder'] : '';
		$default_thumb            = PENCI_SOLEDAD_URL . '/images/nothumb.jpg';
		$thumb_id                 = isset( $penci_categories['thumbnail_id'] ) ? $penci_categories['thumbnail_id'] : '';
		$thumb_url                = $thumb_id ? wp_get_attachment_image_url( $penci_categories['thumbnail_id'] ) : $default_thumb;
		?>
        <table class="form-table" role="presentation">
            <tbody>
            <tr class="form-field term-thumbnail-wrap" data-panel="colors">
                <th scope="row" valign="top">
                    <label><?php esc_html_e( 'Thumbnail', 'soledad' ); ?></label>
                </th>
                <td>

                    <div id="pc_term_thumbnail" style="margin-bottom:10px;"><img
                                src="<?php echo esc_url( $thumb_url ); ?>" width="60px" height="60px"/></div>

                    <div style="line-height: 60px;">
                        <input type="hidden" value="<?php echo $thumb_id; ?>" id="penci_categories_thumbnail_id"
                               name="penci_categories[thumbnail_id]"/>
                        <button type="button"
                                class="upload_image_button button"><?php esc_html_e( 'Upload/Add image', 'soledad' ); ?></button>
                        <button type="button"
                                class="remove_image_button button"><?php esc_html_e( 'Remove image', 'soledad' ); ?></button>
                    </div>
                <td>
                    <script type="text/javascript">

                      // Only show the "remove image" button when needed
                      if (!jQuery('#penci_categories_thumbnail_id').val()) {
                        jQuery('.remove_image_button').hide()
                      }

                      // Uploading files
                      var file_frame

                      jQuery(document).on('click', '.upload_image_button', function (event) {

                        event.preventDefault()

                        // If the media frame already exists, reopen it.
                        if (file_frame) {
                          file_frame.open()
                          return
                        }

                        // Create the media frame.
                        file_frame = wp.media.frames.downloadable_file = wp.media({
                          title: '<?php esc_html_e( 'Choose an image', 'soledad' ); ?>',
                          button: {
                            text: '<?php esc_html_e( 'Use image', 'soledad' ); ?>',
                          },
                          multiple: false,
                        })

                        // When an image is selected, run a callback.
                        file_frame.on('select', function () {
                          var attachment = file_frame.state().get('selection').first().toJSON()
                          var attachment_thumbnail = attachment.sizes.thumbnail || attachment.sizes.full

                          jQuery('#penci_categories_thumbnail_id').val(attachment.id)
                          jQuery('#pc_term_thumbnail').find('img').attr('src', attachment_thumbnail.url)
                          jQuery('.remove_image_button').show()
                        })

                        // Finally, open the modal.
                        file_frame.open()
                      })

                      jQuery(document).on('click', '.remove_image_button', function () {
                        jQuery('#pc_term_thumbnail').
                          find('img').
                          attr('src', '<?php echo esc_js( $default_thumb ); ?>')
                        jQuery('#penci_categories_thumbnail_id').val('')
                        jQuery('.remove_image_button').hide()
                        return false
                      })

                      jQuery(document).ajaxComplete(function (event, request, options) {
                        if (request && 4 === request.readyState && 200 === request.status
                          && options.data && 0 <= options.data.indexOf('action=add-tag')) {

                          var res = wpAjax.parseAjaxResponse(request.responseXML, 'ajax-response')
                          if (!res || res.errors) {
                            return
                          }
                          // Clear Thumbnail fields on submit
                          jQuery('#pc_term_thumbnail').
                            find('img').
                            attr('src', '<?php echo esc_js( $default_thumb ); ?>')
                          jQuery('#penci_categories_thumbnail_id').val('')
                          jQuery('.remove_image_button').hide()
                          // Clear Display type field on submit
                          jQuery('#display_type').val('')
                          return
                        }
                      })

                    </script>
            </tr>
            </tbody>
        </table>
        <div class="penci-cat-option-forms-ul">
            <ul>
                <li><a data-panel="general" class="active" href="#"><?php _e( 'General', 'soledad' ); ?></a></li>
                <li><a data-panel="header" href="#"><?php _e( 'Header', 'soledad' ); ?></a></li>
                <li><a data-panel="footer" href="#"><?php _e( 'Footer', 'soledad' ); ?></a></li>
                <li><a data-panel="single" href="#"><?php _e( 'Single Posts', 'soledad' ); ?></a></li>
                <li><a data-panel="sidebar" href="#"><?php _e( 'Sidebar', 'soledad' ); ?></a></li>
                <li><a data-panel="colors" href="#"><?php _e( 'Colors', 'soledad' ); ?></a></li>
            </ul>
        </div>
        <div class="penci-cat-option-forms">
        <table class="form-table" role="presentation">
        <tbody>
        <tr class="form-field" data-panel="general">
            <th scope="row" valign="top">
                <label for="penci_categories[cat_magazine]"><?php esc_html_e( 'Enable Magazine Layout for This Category', 'soledad' ); ?></label>
            </th>
            <td>
				<?php
				$yes_no_options      = array(
					''    => __( 'No', 'soledad' ),
					'yes' => __( 'Yes', 'soledad' ),
				);
				$customizer_edit_url = admin_url( 'customize.php?autofocus[section]=penci_section_homepage_featured_cat_section' );
				?>
                <select name="penci_categories[cat_magazine]" id="penci_categories[cat_magazine]">
					<?php
					foreach ( $yes_no_options as $slug => $name ) {
						echo '<option value="' . $slug . '" ' . selected( $cat_magazine, $slug ) . '>' . $name . '</option>';
					} ?>
                </select>
                <p class="description"><?php _e( 'It only works if the category contains sub-categories and displays them using the magazine layouts.', 'soledad' ); ?></p>
                <p class="description"><?php _e( 'All the settings are inherited from <strong>Customize → Homepage → Featured Categories</strong>. You can configure the options by clicking <a target="_blank" href=" ' . $customizer_edit_url . ' ">this link</a>.', 'soledad' ); ?></p>
            </td>
        </tr>
        <tr class="form-field" data-panel="general">
            <th scope="row" valign="top">
                <label for="penci_categories[cat_layout]"><?php esc_html_e( 'Select Layout For This Category', 'soledad' ); ?></label>
            </th>
            <td>
                <select name="penci_categories[cat_layout]" id="penci_categories[cat_layout]">
                    <option value="" <?php selected( $cat_layout, '' ); ?>><?php _e( 'None', 'soledad' ); ?></option>
                    <option value="standard" <?php selected( $cat_layout, 'standard' ); ?>><?php _e( 'Standard Posts', 'soledad' ); ?></option>
                    <option value="classic" <?php selected( $cat_layout, 'classic' ); ?>><?php _e( 'Classic Posts', 'soledad' ); ?></option>
                    <option value="overlay" <?php selected( $cat_layout, 'overlay' ); ?>><?php _e( 'Overlay Posts', 'soledad' ); ?></option>
                    <option value="featured" <?php selected( $cat_layout, 'featured' ); ?>><?php _e( 'Featured Posts', 'soledad' ); ?></option>
                    <option value="grid" <?php selected( $cat_layout, 'grid' ); ?>><?php _e( 'Grid Posts', 'soledad' ); ?></option>
                    <option value="grid-2" <?php selected( $cat_layout, 'grid-2' ); ?>><?php _e( 'Grid 2 Columns Posts', 'soledad' ); ?></option>
                    <option value="masonry" <?php selected( $cat_layout, 'masonry' ); ?>><?php _e( 'Grid Masonry Posts', 'soledad' ); ?></option>
                    <option value="masonry-2" <?php selected( $cat_layout, 'masonry-2' ); ?>><?php _e( 'Grid Masonry 2 Columns Posts', 'soledad' ); ?></option>
                    <option value="list" <?php selected( $cat_layout, 'list' ); ?>><?php _e( 'List Posts', 'soledad' ); ?></option>
                    <option value="small-list" <?php selected( $cat_layout, 'small-list' ); ?>><?php _e( 'Small List Posts', 'soledad' ); ?></option>
                    <option value="boxed-1" <?php selected( $cat_layout, 'boxed-1' ); ?>><?php _e( 'Boxed Posts Style 1', 'soledad' ); ?></option>
                    <option value="boxed-2" <?php selected( $cat_layout, 'boxed-2' ); ?>><?php _e( 'Boxed Posts Style 2', 'soledad' ); ?></option>
                    <option value="mixed" <?php selected( $cat_layout, 'mixed' ); ?>><?php _e( 'Mixed Posts', 'soledad' ); ?></option>
                    <option value="mixed-2" <?php selected( $cat_layout, 'mixed-2' ); ?>><?php _e( 'Mixed Posts Style 2', 'soledad' ); ?></option>
                    <option value="mixed-3" <?php selected( $cat_layout, 'mixed-3' ); ?>><?php _e( 'Mixed Posts Style 3', 'soledad' ); ?></option>
                    <option value="mixed-4" <?php selected( $cat_layout, 'mixed-4' ); ?>><?php _e( 'Mixed Posts Style 4', 'soledad' ); ?></option>
                    <option value="photography" <?php selected( $cat_layout, 'photography' ); ?>><?php _e( 'Photography Posts', 'soledad' ); ?></option>
                    <option value="standard-grid" <?php selected( $cat_layout, 'standard-grid' ); ?>><?php _e( '1st Standard Then Grid', 'soledad' ); ?></option>
                    <option value="standard-grid-2" <?php selected( $cat_layout, 'standard-grid-2' ); ?>><?php _e( '1st StandardThen Grid 2 Columns', 'soledad' ); ?></option>
                    <option value="standard-list" <?php selected( $cat_layout, 'standard-list' ); ?>><?php _e( '1st Standard Then List', 'soledad' ); ?></option>
                    <option value="standard-boxed-1" <?php selected( $cat_layout, 'standard-boxed-1' ); ?>><?php _e( '1st Standard Then Boxed', 'soledad' ); ?>
                    </option>
                    <option value="classic-grid" <?php selected( $cat_layout, 'classic-grid' ); ?>><?php _e( '1st Classic Then Grid', 'soledad' ); ?>
                    </option>
                    <option value="classic-grid-2" <?php selected( $cat_layout, 'classic-grid-2' ); ?>><?php _e( '1st Classic Then Grid 2 Columns', 'soledad' ); ?>
                    </option>
                    <option value="classic-list" <?php selected( $cat_layout, 'classic-list' ); ?>><?php _e( '1st Classic Then List', 'soledad' ); ?>
                    </option>
                    <option value="classic-boxed-1" <?php selected( $cat_layout, 'classic-boxed-1' ); ?>><?php _e( '1st Classic Then Boxed', 'soledad' ); ?>
                    </option>
                    <option value="overlay-grid" <?php selected( $cat_layout, 'overlay-grid' ); ?>><?php _e( '1st Overlay Then Grid', 'soledad' ); ?>
                    </option>
                    <option value="overlay-list" <?php selected( $cat_layout, 'overlay-list' ); ?>><?php _e( '1st Overlay Then List', 'soledad' ); ?>
                    </option>
                </select>
                <p class="description"><?php _e( 'This option will override with the general layout you selected on General > General Settings > Category, Tags, Search, Archive Pages > Category, Tag, Search, Archive Layout', 'soledad' ); ?></p>
            </td>
        </tr>
        <tr class="form-field" data-panel="header">
            <th scope="row" valign="top">
                <label for="penci_categories[cat_header_single]"><?php esc_html_e( 'Apply these settings to all posts in this category?', 'soledad' ); ?></label>
            </th>
            <td>
				<?php
				$yes_no_options = array(
					''    => __( 'No', 'soledad' ),
					'yes' => __( 'Yes', 'soledad' ),
				);
				?>
                <select name="penci_categories[cat_header_single]" id="penci_categories[cat_header_single]">
					<?php
					foreach ( $yes_no_options as $slug => $name ) {
						echo '<option value="' . $slug . '" ' . selected( $cat_header_single, $slug ) . '>' . $name . '</option>';
					} ?>
                </select>
            </td>
        </tr>
        <tr class="form-field" data-panel="header">
            <th scope="row" valign="top">
                <label for="penci_categories[cat_header]"><?php esc_html_e( 'Select Header Layout for this Category', 'soledad' ); ?></label>
            </th>
            <td>
				<?php
				$header_layout_options = array(
					'header-1'  => __( 'Header 1', 'soledad' ),
					'header-2'  => __( 'Header 2', 'soledad' ),
					'header-3'  => __( 'Header 3', 'soledad' ),
					'header-4'  => __( 'Header 4 ( Centered )', 'soledad' ),
					'header-5'  => __( 'Header 5 ( Centered )', 'soledad' ),
					'header-6'  => __( 'Header 6', 'soledad' ),
					'header-7'  => __( 'Header 7', 'soledad' ),
					'header-8'  => __( 'Header 8', 'soledad' ),
					'header-9'  => __( 'Header 9', 'soledad' ),
					'header-10' => __( 'Header 10', 'soledad' ),
					'header-11' => __( 'Header 11', 'soledad' ),
				);
				?>
                <select name="penci_categories[cat_header]" id="penci_categories[cat_header]">
                    <option value="">Default ( follow Customize )</option>
					<?php
					foreach ( $header_layout_options as $slug => $name ) {
						echo '<option value="' . $slug . '" ' . selected( $cat_header, $slug ) . '>' . $name . '</option>';
					} ?>
                </select>
            </td>
        </tr>
        <tr class="form-field" data-panel="header">
            <th scope="row" valign="top">
                <label for="penci_categories[cat_header_builder]"><?php esc_html_e( 'Select Header Builder Layout for this Category', 'soledad' ); ?></label>
            </th>
            <td>
				<?php
				$header_options     = [];
				$header_options[''] = __( 'Default ( follow Customize )', 'soledad' );
				$header_layouts     = get_posts( [
					'post_type'      => 'penci_builder',
					'posts_per_page' => - 1,
				] );
				foreach ( $header_layouts as $header_builder ) {
					$header_options[ $header_builder->post_name ] = $header_builder->post_title;
				}
				?>
                <select name="penci_categories[cat_header_builder]" id="penci_categories[cat_header_builder]">
					<?php
					foreach ( $header_options as $slug => $name ) {
						echo '<option value="' . $slug . '" ' . selected( $cat_header_builder, $slug ) . '>' . $name . '</option>';
					} ?>
                </select>
            </td>
        </tr>
        <tr class="form-field" data-panel="header">
            <th scope="row" valign="top">
                <label for="penci_categories[cat_header_block]"><?php esc_html_e( 'Select Penci Block use as Header Layout for this Category', 'soledad' ); ?></label>
            </th>
            <td>
				<?php
				$header_block_options     = [];
				$header_block_options[''] = __( 'Default ( follow Customize )', 'soledad' );
				$header_block_layouts     = get_posts( [
					'post_type'      => 'penci-block',
					'posts_per_page' => - 1,
				] );
				foreach ( $header_block_layouts as $header_block_name ) {
					$header_block_options[ $header_block_name->post_name ] = $header_block_name->post_title;
				}
				?>
                <select name="penci_categories[cat_header_block]" id="penci_categories[cat_header_block]">
					<?php
					foreach ( $header_block_options as $slug => $name ) {
						echo '<option value="' . $slug . '" ' . selected( $cat_header_block, $slug ) . '>' . $name . '</option>';
					} ?>
                </select>
            </td>
        </tr>
        <tr class="form-field" data-panel="footer">
            <th scope="row" valign="top">
                <label for="penci_categories[cat_footer_single]"><?php esc_html_e( 'Apply this setting to all posts in this category?', 'soledad' ); ?></label>
            </th>
            <td>
				<?php
				$yes_no_options = array(
					''    => __( 'No', 'soledad' ),
					'yes' => __( 'Yes', 'soledad' ),
				);
				?>
                <select name="penci_categories[cat_footer_single]" id="penci_categories[cat_footer_single]">
					<?php
					foreach ( $yes_no_options as $slug => $name ) {
						echo '<option value="' . $slug . '" ' . selected( $cat_footer_single, $slug ) . '>' . $name . '</option>';
					} ?>
                </select>
            </td>
        </tr>
        <tr class="form-field" data-panel="footer">
            <th scope="row" valign="top">
                <label for="penci_categories[cat_footer]"><?php esc_html_e( 'Select Footer Builder for this Category', 'soledad' ); ?></label>
            </th>
            <td>
                <select name="penci_categories[cat_footer]" id="penci_categories[cat_footer]">
                    <option value="">Default ( follow Customize )</option>
					<?php
					$footer_layouts = get_posts( [
						'post_type'      => 'penci-block',
						'posts_per_page' => - 1,
					] );
					foreach ( $footer_layouts as $footer ) {
						$footer_layout[ $footer->ID ] = $footer->post_title;
					}
					foreach ( $footer_layout as $slug => $name ) {
						echo '<option value="' . $slug . '" ' . selected( $cat_footer_builder, $slug ) . '>' . $name . '</option>';
					} ?>
                </select>
            </td>
        </tr>
        <tr class="form-field" data-panel="sidebar">
            <th scope="row" valign="top">
                <label for="penci_categories[cat_sidebar_single]"><?php esc_html_e( 'Apply these settings to all posts in this category?', 'soledad' ); ?></label>
            </th>
            <td>
				<?php
				$yes_no_options = array(
					''    => __( 'No', 'soledad' ),
					'yes' => __( 'Yes', 'soledad' ),
				);
				?>
                <select name="penci_categories[cat_sidebar_single]" id="penci_categories[cat_sidebar_single]">
					<?php
					foreach ( $yes_no_options as $slug => $name ) {
						echo '<option value="' . $slug . '" ' . selected( $cat_sidebar_single, $slug ) . '>' . $name . '</option>';
					} ?>
                </select>
            </td>
        </tr>
        <tr class="form-field" data-panel="sidebar">
            <th scope="row" valign="top">
                <label for="penci_categories[cat_sidebar_display]"><?php esc_html_e( 'Display Sidebar on this Category', 'soledad' ); ?></label>
            </th>
            <td>
                <select name="penci_categories[cat_sidebar_display]" id="penci_categories[cat_sidebar_display]">
                    <option value="">Default ( follow Customize )</option>
                    <option value="left" <?php selected( $cat_sidebar_display, 'left' ); ?>><?php _e( 'Left Sidebar', 'soledad' ); ?></option>
                    <option value="right" <?php selected( $cat_sidebar_display, 'right' ); ?>><?php _e( 'Right Sidebar', 'soledad' ); ?></option>
                    <option value="two" <?php selected( $cat_sidebar_display, 'two' ); ?>><?php _e( 'Two Sidebar', 'soledad' ); ?></option>
                    <option value="no" <?php selected( $cat_sidebar_display, 'no' ); ?>><?php _e( 'No Sidebar', 'soledad' ); ?></option>
                </select>
            </td>
        </tr>
        <tr class="form-field" data-panel="sidebar">
            <th scope="row" valign="top">
                <label for="penci_categories[cat_sidebar]"><?php esc_html_e( 'Select Custom Sidebar for This Category', 'soledad' ); ?></label>
            </th>
            <td>
                <select name="penci_categories[cat_sidebar]" id="penci_categories[cat_sidebar]">
                    <option value=""><?php _e( 'Default ( follow Customize )', 'soledad' ); ?></option>
                    <option value="main-sidebar" <?php selected( $cat_sidebar, 'main-sidebar' ); ?>><?php _e( 'Main Sidebar', 'soledad' ); ?>
                    </option>
                    <option value="custom-sidebar-1" <?php selected( $cat_sidebar, 'custom-sidebar-1' ); ?>><?php _e( 'Custom Sidebar 1', 'soledad' ); ?>
                    </option>
                    <option value="custom-sidebar-2" <?php selected( $cat_sidebar, 'custom-sidebar-2' ); ?>><?php _e( 'Custom Sidebar 2', 'soledad' ); ?>
                    </option>
                    <option value="custom-sidebar-3" <?php selected( $cat_sidebar, 'custom-sidebar-3' ); ?>><?php _e( 'Custom Sidebar 3', 'soledad' ); ?>
                    </option>
                    <option value="custom-sidebar-4" <?php selected( $cat_sidebar, 'custom-sidebar-4' ); ?>><?php _e( 'Custom Sidebar 4', 'soledad' ); ?>
                    </option>
                    <option value="custom-sidebar-5" <?php selected( $cat_sidebar, 'custom-sidebar-5' ); ?>><?php _e( 'Custom Sidebar 5', 'soledad' ); ?>
                    </option>
                    <option value="custom-sidebar-6" <?php selected( $cat_sidebar, 'custom-sidebar-6' ); ?>><?php _e( 'Custom Sidebar 6', 'soledad' ); ?>
                    </option>
                    <option value="custom-sidebar-7" <?php selected( $cat_sidebar, 'custom-sidebar-7' ); ?>><?php _e( 'Custom Sidebar 7', 'soledad' ); ?>
                    </option>
                    <option value="custom-sidebar-8" <?php selected( $cat_sidebar, 'custom-sidebar-8' ); ?>><?php _e( 'Custom Sidebar 8', 'soledad' ); ?>
                    </option>
                    <option value="custom-sidebar-9" <?php selected( $cat_sidebar, 'custom-sidebar-9' ); ?>><?php _e( 'Custom Sidebar 9', 'soledad' ); ?>
                    </option>
                    <option value="custom-sidebar-10" <?php selected( $cat_sidebar, 'custom-sidebar-10' ); ?>><?php _e( 'Custom Sidebar 10', 'soledad' ); ?>
                    </option>
					<?php Penci_Custom_Sidebar::get_list_sidebar( $cat_sidebar ); ?>
                </select>
            </td>
        </tr>
        <tr class="form-field" data-panel="sidebar">
            <th scope="row" valign="top">
                <label for="penci_categories[cat_sidebar_left]"><?php esc_html_e( 'Select Custom Sidebar Left for This Category', 'soledad' ); ?></label>
            </th>
            <td>
                <select name="penci_categories[cat_sidebar_left]" id="penci_categories[cat_sidebar_left]">
                    <option value=""><?php _e( 'Default ( follow Customize )', 'soledad' ); ?></option>
                    <option value="main-sidebar" <?php selected( $cat_sidebar_left, 'main-sidebar' ); ?>><?php _e( 'Main Sidebar', 'soledad' ); ?>
                    </option>
                    <option value="custom-sidebar-1" <?php selected( $cat_sidebar_left, 'custom-sidebar-1' ); ?>><?php _e( 'Custom                        Sidebar 1', 'soledad' ); ?>                    </option>
                    <option value="custom-sidebar-2" <?php selected( $cat_sidebar_left, 'custom-sidebar-2' ); ?>><?php _e( 'Custom                        Sidebar 2', 'soledad' ); ?>                    </option>
                    <option value="custom-sidebar-3" <?php selected( $cat_sidebar_left, 'custom-sidebar-3' ); ?>><?php _e( 'Custom                        Sidebar 3', 'soledad' ); ?>                    </option>
                    <option value="custom-sidebar-4" <?php selected( $cat_sidebar_left, 'custom-sidebar-4' ); ?>><?php _e( 'Custom                        Sidebar 4', 'soledad' ); ?>                    </option>
                    <option value="custom-sidebar-5" <?php selected( $cat_sidebar_left, 'custom-sidebar-5' ); ?>><?php _e( 'Custom                        Sidebar 5', 'soledad' ); ?>                    </option>
                    <option value="custom-sidebar-6" <?php selected( $cat_sidebar_left, 'custom-sidebar-6' ); ?>><?php _e( 'Custom                        Sidebar 6', 'soledad' ); ?>                    </option>
                    <option value="custom-sidebar-7" <?php selected( $cat_sidebar_left, 'custom-sidebar-7' ); ?>><?php _e( 'Custom                        Sidebar 7', 'soledad' ); ?>                    </option>
                    <option value="custom-sidebar-8" <?php selected( $cat_sidebar_left, 'custom-sidebar-8' ); ?>><?php _e( 'Custom                        Sidebar 8', 'soledad' ); ?>                    </option>
                    <option value="custom-sidebar-9" <?php selected( $cat_sidebar_left, 'custom-sidebar-9' ); ?>><?php _e( 'Custom                        Sidebar 9', 'soledad' ); ?>                    </option>
                    <option value="custom-sidebar-10" <?php selected( $cat_sidebar_left, 'custom-sidebar-10' ); ?>>                        <?php _e( 'Custom Sidebar 10', 'soledad' ); ?>                    </option>
					<?php Penci_Custom_Sidebar::get_list_sidebar( $cat_sidebar_left ); ?>
                </select>
            </td>
        </tr>
        <tr class="form-field" data-panel="general">
            <th scope="row" valign="top">
                <label for="penci_categories[mag_layout]"><?php esc_html_e( 'Select Featured Layout for Magazine Layout', 'soledad' ); ?></label>
            </th>
            <td>
                <select name="penci_categories[mag_layout]" id="penci_categories[mag_layout]">
                    <option value="style-1" <?php selected( $mag_layout, 'style-1' ); ?>><?php _e( 'Style 1 - 1st Post Grid                        Featured on Left', 'soledad' ); ?>                    </option>
                    <option value="style-2" <?php selected( $mag_layout, 'style-2' ); ?>><?php _e( 'Style 2 - 1st Post Grid                        Featured on Top', 'soledad' ); ?>                    </option>
                    <option value="style-3" <?php selected( $mag_layout, 'style-3' ); ?>><?php _e( 'Style 3 - Text Overlay', 'soledad' ); ?></option>
                    <option value="style-4" <?php selected( $mag_layout, 'style-4' ); ?>><?php _e( 'Style 4 - Single Slider', 'soledad' ); ?>                    </option>
                    <option value="style-5" <?php selected( $mag_layout, 'style-5' ); ?>><?php _e( 'Style 5 - Slider 2 Columns', 'soledad' ); ?>                    </option>
                    <option value="style-6" <?php selected( $mag_layout, 'style-6' ); ?>><?php _e( 'Style 6 - 1st Post List                        Featured on Top', 'soledad' ); ?>                    </option>
                    <option value="style-7" <?php selected( $mag_layout, 'style-7' ); ?>><?php _e( 'Style 7 - Grid 2 Columns', 'soledad' ); ?></option>
                    <option value="style-8" <?php selected( $mag_layout, 'style-8' ); ?>><?php _e( 'Style 8 - List Layout', 'soledad' ); ?></option>
                    <option value="style-9" <?php selected( $mag_layout, 'style-9' ); ?>><?php _e( 'Style 9 - Small List Layout', 'soledad' ); ?>                    </option>
                    <option value="style-10" <?php selected( $mag_layout, 'style-10' ); ?>><?php _e( 'Style 10 - 2 First Posts                        Featured and List', 'soledad' ); ?>                    </option>
                    <option value="style-11" <?php selected( $mag_layout, 'style-11' ); ?>><?php _e( 'Style 11 - Text Overlay                        Center', 'soledad' ); ?>                    </option>
                    <option value="style-12" <?php selected( $mag_layout, 'style-12' ); ?>><?php _e( 'Style 12 - Slider 3 Columns', 'soledad' ); ?>                    </option>
                    <option value="style-13" <?php selected( $mag_layout, 'style-13' ); ?>><?php _e( 'Style 13 - Grid 3 Columns', 'soledad' ); ?>                    </option>
                    <option value="style-14" <?php selected( $mag_layout, 'style-14' ); ?>><?php _e( 'Style 14 - 1st Post Overlay                        Featured on Top', 'soledad' ); ?>                    </option>
                    <option value="style-15" <?php selected( $mag_layout, 'style-15' ); ?>><?php _e( 'Style 15 - Overlay Left then                        List on Right', 'soledad' ); ?>                    </option>
                </select>
                <p class="description"><?php _e( 'Use it to change the featured layout for this category when you use this layout as a featured category. Check more on <a href="https://www.youtube.com/watch?v=ajTm4J34DF0&list=PL1PBMejQ2VTwp9ppl8lTQ9Tq7I3FJTT04&index=7" target="_blank">this video tutorial</a>', 'soledad' ); ?></p>
            </td>
        </tr>
        <tr class="form-field" data-panel="general">
            <th scope="row" valign="top">
                <label for="penci_categories[mag_ads]"><?php esc_html_e( 'Add Google Adsense/Custom HTML Code below this category', 'soledad' ); ?></label>
            </th>
            <td>
				<textarea name="penci_categories[mag_ads]" id="penci_categories[mag_ads]" rows="5"
                          cols="50"><?php echo stripslashes( $mag_ads ); ?></textarea>
            </td>
        </tr>
		<?php
		$archive_layout   = array();
		$archive_layout[] = __( 'Default Template', 'soledad' );
		$archive_layouts  = get_posts(
			array(
				'post_type'      => 'archive-template',
				'posts_per_page' => - 1,
				'meta_query'     => array(
					'relation' => 'OR',
					array(
						'key'     => 'penci_desktop_page_id',
						'compare' => 'NOT EXISTS',
					),
					array(
						'key'     => 'penci_desktop_page_id',
						'value'   => '',
						'compare' => '=',
					),
				),
			)
		);
		foreach ( $archive_layouts as $alayout ) {
			$archive_layout[ $alayout->post_name ] = $alayout->post_title;
		}
		?>
        <tr class="form-field" data-panel="general">
            <th scope="row" valign="top">
                <label for="penci_categories[penci_archive_layout]"><?php esc_html_e( 'Select Custom Archive Template for this Category', 'soledad' ); ?></label>
            </th>
            <td>
                <select name="penci_categories[penci_archive_layout]" id="penci_categories[penci_archive_layout]">
					<?php foreach ( $archive_layout as $alayout_slug => $alayout_name ) : ?>
                        <option value="<?php echo $alayout_slug; ?>" <?php selected( $alayout_slug, $alayout_save_slug ); ?>><?php echo $alayout_name; ?></option>
					<?php endforeach; ?>
                </select>
            </td>
        </tr>
        <tr class="form-field" data-panel="single">
            <th scope="row" valign="top">
                <label for="penci_categories[penci_single_overide]"><?php esc_html_e( 'Apply these settings to all posts in this category?', 'soledad' ); ?></label>
            </th>
            <td>
				<?php
				$yes_no_options = array(
					''    => __( 'No', 'soledad' ),
					'yes' => __( 'Yes', 'soledad' ),
				);
				?>
                <select name="penci_categories[penci_single_overide]" id="penci_categories[penci_single_overide]">
					<?php
					foreach ( $yes_no_options as $slug => $name ) {
						echo '<option value="' . $slug . '" ' . selected( $penci_single_overide, $slug ) . '>' . $name . '</option>';
					} ?>
                </select>
            </td>
        </tr>
        <tr class="form-field" data-panel="single">
            <th scope="row" valign="top">
                <label for="penci_categories[penci_single_layout]"><?php esc_html_e( 'Select Single Post Layout this Category', 'soledad' ); ?></label>
            </th>
            <td>
                <select name="penci_categories[penci_single_layout]" id="penci_categories[penci_single_layout]">
                    <option value=""><?php esc_html_e( "Default Style( on Customize )", "soledad" ); ?></option>
					<?php for ( $i = 1; $i <= 22; $i ++ ) : ?>
                        <option value="style-<?php echo $i; ?>" <?php selected( $penci_single_layout, 'style-' . $i ); ?>>
							<?php esc_html_e( "Style $i", "soledad" ); ?>
                        </option>
					<?php endfor; ?>
                </select>
            </td>
        </tr>
        <tr class="form-field" data-panel="single">
            <th scope="row" valign="top">
                <label for="penci_categories[penci_single_builder]"><?php esc_html_e( 'Custom Post Builder Template for This Category', 'soledad' ); ?></label>
            </th>
            <td>
                <select name="penci_categories[penci_single_builder]" id="penci_categories[penci_single_builder]">
                    <option value=""><?php esc_html_e( "Default Style( on Customize )", "soledad" ); ?></option>
					<?php
					$single_layout  = [];
					$single_layouts = get_posts( [
						'post_type'      => 'custom-post-template',
						'posts_per_page' => -1,
					] );
                    if ( $single_layouts ) {
                        foreach ( $single_layouts as $slayout ) {
                            $single_layout[ $slayout->post_name ] = $slayout->post_title;
                            echo '<option value="' . esc_attr( $slayout->post_name ) . '" ' . selected( $slayout->post_name, $penci_single_builder ) . '>' . esc_html( $slayout->post_title ) . '</option>';
                        } 
                    } 
                    ?>
                </select>
            </td>
        </tr>
        <tr class="form-field" data-panel="colors">
            <th scope="row" valign="top">
                <label for="penci_categories[cat_colors_single]"><?php esc_html_e( 'Apply these settings to all posts in this category?', 'soledad' ); ?></label>
            </th>
            <td>
				<?php
				$yes_no_options = array(
					''    => __( 'No', 'soledad' ),
					'yes' => __( 'Yes', 'soledad' ),
				);
				?>
                <select name="penci_categories[cat_colors_single]" id="penci_categories[cat_colors_single]">
					<?php
					foreach ( $yes_no_options as $slug => $name ) {
						echo '<option value="' . $slug . '" ' . selected( $cat_sidebar_single, $slug ) . '>' . $name . '</option>';
					} ?>
                </select>
            </td>
        </tr>
        <tr class="form-field" data-panel="colors">
            <th scope="row" valign="top">
                <label for="penci_categories[penci_archive_acolor]"><?php esc_html_e( 'Category Accent Color', 'soledad' ); ?></label>
            </th>
            <td>
                <input class="widefat pccat-color-picker color-picker"
                       id="penci_categories[penci_archive_acolor]"
                       name="penci_categories[penci_archive_acolor]" type="text"
                       value="<?php echo $archive_acolor; ?>"/>
            </td>
        </tr>
        <tr class="form-field" data-panel="colors">
            <th scope="row" valign="top">
                <label for="penci_categories[penci_archive_gtextcolor]"><?php esc_html_e( 'General Text Color', 'soledad' ); ?></label>
            </th>
            <td>
                <input class="widefat pccat-color-picker color-picker"
                       id="penci_categories[penci_archive_gtextcolor]"
                       name="penci_categories[penci_archive_gtextcolor]" type="text"
                       value="<?php echo $penci_archive_gtextcolor; ?>"/>
            </td>
        </tr>
        <tr class="form-field" data-panel="colors">
            <th scope="row" valign="top">
                <label for="penci_categories[penci_archive_cbgcolor]"><?php esc_html_e( 'Custom Background Color for Body', 'soledad' ); ?></label>
            </th>
            <td>
                <input class="widefat pccat-color-picker color-picker"
                       id="penci_categories[penci_archive_cbgcolor]"
                       name="penci_categories[penci_archive_cbgcolor]" type="text"
                       value="<?php echo $penci_archive_cbgcolor; ?>"/>
            </td>
        </tr>
        <tr class="form-field" data-panel="colors">
            <th scope="row" valign="top">
                <label for="penci_categories[penci_archive_bdcolor]"><?php esc_html_e( 'Custom General Borders Color', 'soledad' ); ?></label>
            </th>
            <td>
                <input class="widefat pccat-color-picker color-picker"
                       id="penci_categories[penci_archive_bdcolor]"
                       name="penci_categories[penci_archive_bdcolor]" type="text"
                       value="<?php echo $penci_archive_bdcolor; ?>"/>
            </td>
        </tr>
        <tr class="form-field" data-panel="colors">
            <th scope="row" valign="top">
                <label for="penci_categories[penci_archive_bgcolor]"><?php esc_html_e( 'Category Name Background Color', 'soledad' ); ?></label>
            </th>
            <td>
                <input class="widefat pccat-color-picker color-picker"
                       id="penci_categories[penci_archive_bgcolor]"
                       name="penci_categories[penci_archive_bgcolor]" type="text"
                       value="<?php echo $archive_bgcolor; ?>"/>
            </td>
        </tr>
        <tr class="form-field" data-panel="colors">
            <th scope="row" valign="top">
                <label for="penci_categories[penci_archive_color]"><?php esc_html_e( 'Category Name Text Color', 'soledad' ); ?></label>
            </th>
            <td>
                <input class="widefat pccat-color-picker color-picker"
                       id="penci_categories[penci_archive_color]"
                       name="penci_categories[penci_archive_color]" type="text"
                       value="<?php echo $archive_color; ?>"/>
            </td>
        </tr>
        <tr class="form-field" data-panel="colors">
            <th scope="row" valign="top">
                <label for="penci_categories[penci_archivepage_color]"><?php esc_html_e( 'Category Text Color on Category Page (Apply for Penci Cat Default Style)', 'soledad' ); ?></label>
            </th>
            <td>
                <input class="widefat pccat-color-picker color-picker"
                       id="penci_categories[penci_archivepage_color]"
                       name="penci_categories[penci_archivepage_color]" type="text"
                       value="<?php echo $archivepage_color; ?>"/>
            </td>
        </tr>

		<?php if ( get_theme_mod( 'penci_speed_remove_css' ) ) : ?>
            <tr class="form-field" data-panel="general">
                <th scope="row">
                    <label for="penci_categories[penci_critical_css]"><?php esc_html_e( 'Create a Separate Critical CSS cache for this Category?', 'soledad' ); ?></label>
                </th>
                <td>
                    <select name="penci_categories[penci_critical_css]" id="penci_categories[penci_critical_css]">
                        <option value=""><?php _e( 'No', 'soledad' ); ?></option>
                        <option value="yes" <?php selected( $penci_critical_css, 'yes' ); ?>><?php _e( 'Yes', 'soledad' ); ?></option>
                    </select>
                    <?php if ( $penci_critical_css == 'yes' ) : ?>
                    <div style="margin:10px 0 0 0;">
                        <a href="#" data-type="term" data-id="<?php echo esc_attr( $t_id ); ?>"
                        class="button button-primary penci-regenerate-css" target="_blank">
                            <?php _e( 'Generate Critical CSS Cache', 'penci-shortcodes' ); ?>
                        </a>
                    </div>
                    <?php endif; ?>
                </td>
            </tr>
		<?php
		endif;
		echo '</tbody></table></div>';
	}
}

// Save data
if ( ! function_exists( 'penci_save_category_fileds_meta' ) ) {

	add_action( 'init', function () {
		$post_taxonomies = get_object_taxonomies( 'post' );

		foreach ( $post_taxonomies as $tname ) {
			add_action( 'edited_' . $tname, 'penci_save_category_fileds_meta' );
		}
	} );

	function penci_save_category_fileds_meta( $term_id ) {
		if ( isset( $_POST['penci_categories'] ) ) {
			$t_id             = $term_id;
			$penci_categories = get_option( "category_$t_id" );
			$cat_keys         = array_keys( $_POST['penci_categories'] );
			foreach ( $cat_keys as $key ) {
				if ( isset( $_POST['penci_categories'][ $key ] ) ) {
					$penci_categories[ $key ] = $_POST['penci_categories'][ $key ];
				}
			}
			// save the option array
			update_option( "category_$t_id", $penci_categories );
		} else if ( isset( $_POST['penci_term_data'] ) ) {
			$option_name = 'penci_tax_' . $term_id;

			$penci_term_data = get_option( $option_name );
			$cat_keys        = array_keys( $_POST['penci_term_data'] );
			foreach ( $cat_keys as $key ) {
				if ( isset( $_POST['penci_term_data'][ $key ] ) && $key ) {
					$penci_term_data[ $key ] = $_POST['penci_term_data'][ $key ];
				}
			}

			update_option( $option_name, $penci_term_data );

		}
	}
}


// add thumbnail meta
// Create markup
if ( ! function_exists( 'penci_term_fields_meta' ) ) {

	add_action(
		'init',
		function () {
			$post_taxonomies = get_object_taxonomies( 'post' );

			foreach ( $post_taxonomies as $tname ) {
				if ( $tname != 'category' ) {
					add_action( $tname . '_edit_form', 'penci_term_fields_meta' );
				}
			}
		}
	);

	function penci_term_fields_meta( $tag ) {
		$t_id                     = $tag->term_id;
		$tax_data                 = get_taxonomy( $tag->taxonomy );
		$option_name              = 'penci_tax_' . $t_id;
		$penci_categories         = get_option( $option_name );
		$default_thumb            = PENCI_SOLEDAD_URL . '/images/nothumb.jpg';
		$thumb_id                 = isset( $penci_categories['thumbnail_id'] ) ? $penci_categories['thumbnail_id'] : '';
		$archive_acolor           = isset( $penci_categories['penci_archive_acolor'] ) ? $penci_categories['penci_archive_acolor'] : '';
		$penci_archive_gtextcolor = isset( $penci_categories['penci_archive_gtextcolor'] ) ? $penci_categories['penci_archive_gtextcolor'] : '';
		$penci_archive_cbgcolor   = isset( $penci_categories['penci_archive_cbgcolor'] ) ? $penci_categories['penci_archive_cbgcolor'] : '';
		$penci_archive_bdcolor    = isset( $penci_categories['penci_archive_bdcolor'] ) ? $penci_categories['penci_archive_bdcolor'] : '';
		$cat_header               = isset( $penci_categories['cat_header'] ) ? $penci_categories['cat_header'] : '';
		$cat_header_builder       = isset( $penci_categories['cat_header_builder'] ) ? $penci_categories['cat_header_builder'] : '';
		$cat_header_block         = isset( $penci_categories['cat_header_block'] ) ? $penci_categories['cat_header_block'] : '';
		$thumb_url                = $thumb_id ? wp_get_attachment_image_url( $penci_categories['thumbnail_id'] ) : $default_thumb;
		?>
        <div class="penci-cat-option-forms-ul">
            <ul>
                <li><a data-panel="general" class="active" href="#"><?php _e( 'Colors', 'soledad' ); ?></a></li>
                <li><a data-panel="header" href="#"><?php _e( 'Header', 'soledad' ); ?></a></li>
            </ul>
        </div>
        <div class="penci-cat-option-forms">
            <table class="form-table" role="presentation">
                <tbody>
                <tr class="form-field" data-panel="general">
                    <th scope="row" valign="top">
                        <label for="penci_term_data[penci_archive_acolor]"><?php echo sprintf( esc_html__( '%s Accent Color', 'soledad' ), $tax_data->labels->singular_name ); ?></label>
                    </th>
                    <td>
                        <input class="widefat pccat-color-picker color-picker"
                               id="penci_term_data[penci_archive_acolor]"
                               name="penci_term_data[penci_archive_acolor]" type="text"
                               value="<?php echo $archive_acolor; ?>"/>
                    </td>
                </tr>
                <tr class="form-field" data-panel="general">
                    <th scope="row" valign="top">
                        <label for="penci_term_data[penci_archive_gtextcolor]"><?php esc_html_e( 'General Text Color', 'soledad' ); ?></label>
                    </th>
                    <td>
                        <input class="widefat pccat-color-picker color-picker"
                               id="penci_term_data[penci_archive_gtextcolor]"
                               name="penci_term_data[penci_archive_gtextcolor]" type="text"
                               value="<?php echo $penci_archive_gtextcolor; ?>"/>
                    </td>
                </tr>
                <tr class="form-field" data-panel="general">
                    <th scope="row" valign="top">
                        <label for="penci_term_data[penci_archive_cbgcolor]"><?php esc_html_e( 'Custom Background Color for Body', 'soledad' ); ?></label>
                    </th>
                    <td>
                        <input class="widefat pccat-color-picker color-picker"
                               id="penci_term_data[penci_archive_cbgcolor]"
                               name="penci_term_data[penci_archive_cbgcolor]" type="text"
                               value="<?php echo $penci_archive_cbgcolor; ?>"/>
                    </td>
                </tr>
                <tr class="form-field" data-panel="general">
                    <th scope="row" valign="top">
                        <label for="penci_term_data[penci_archive_bdcolor]"><?php esc_html_e( 'Custom General Borders Color', 'soledad' ); ?></label>
                    </th>
                    <td>
                        <input class="widefat pccat-color-picker color-picker"
                               id="penci_term_data[penci_archive_bdcolor]"
                               name="penci_term_data[penci_archive_bdcolor]" type="text"
                               value="<?php echo $penci_archive_bdcolor; ?>"/>
                    </td>
                </tr>
                <tr class="form-field" data-panel="header">
                    <th scope="row" valign="top">
                        <label for="cat_header"><?php echo sprintf( esc_html__( 'Select Header Layout for this %s', 'soledad' ), $tax_data->labels->singular_name ); ?></label>
                    </th>
                    <td>
						<?php
						$header_layout_options = array(
							''          => __( 'Default ( follow Customize )', 'soledad' ),
							'header-1'  => __( 'Header 1', 'soledad' ),
							'header-2'  => __( 'Header 2', 'soledad' ),
							'header-3'  => __( 'Header 3', 'soledad' ),
							'header-4'  => __( 'Header 4 ( Centered )', 'soledad' ),
							'header-5'  => __( 'Header 5 ( Centered )', 'soledad' ),
							'header-6'  => __( 'Header 6', 'soledad' ),
							'header-7'  => __( 'Header 7', 'soledad' ),
							'header-8'  => __( 'Header 8', 'soledad' ),
							'header-9'  => __( 'Header 9', 'soledad' ),
							'header-10' => __( 'Header 10', 'soledad' ),
							'header-11' => __( 'Header 11', 'soledad' ),
						);
						?>
                        <select name="penci_term_data[cat_header]" id="penci_term_data[cat_header]">
							<?php
							foreach ( $header_layout_options as $slug => $name ) {
								echo '<option value="' . $slug . '" ' . selected( $cat_header, $slug ) . '>' . $name . '</option>';
							} ?>
                        </select>
                    </td>
                </tr>
                <tr class="form-field" data-panel="header">
                    <th scope="row" valign="top">
                        <label for="cat_header_builder"><?php echo sprintf( esc_html__( 'Select Header Builder Layout for this %s', 'soledad' ), $tax_data->labels->singular_name ); ?></label>
                    </th>
                    <td>
						<?php
						$header_options     = [];
						$header_options[''] = __( 'Default ( follow Customize )', 'soledad' );
						$header_layouts     = get_posts( [
							'post_type'      => 'penci_builder',
							'posts_per_page' => - 1,
						] );
						foreach ( $header_layouts as $header_builder ) {
							$header_options[ $header_builder->post_name ] = $header_builder->post_title;
						}
						?>
                        <select name="penci_term_data[cat_header_builder]" id="penci_term_data[cat_header_builder]">
							<?php
							foreach ( $header_options as $slug => $name ) {
								echo '<option value="' . $slug . '" ' . selected( $cat_header_builder, $slug ) . '>' . $name . '</option>';
							} ?>
                        </select>
                    </td>
                </tr>
                <tr class="form-field" data-panel="header">
                    <th scope="row" valign="top">
                        <label for="penci_term_data[cat_header_block]"><?php echo sprintf( esc_html__( 'Select Penci Block use as Header for this %s', 'soledad' ), $tax_data->labels->singular_name ); ?></label>
                    </th>
                    <td>
						<?php
						$header_block_options     = [];
						$header_block_options[''] = __( 'Default ( follow Customize )', 'soledad' );
						$header_block_layouts     = get_posts( [
							'post_type'      => 'penci-block',
							'posts_per_page' => - 1,
						] );
						foreach ( $header_block_layouts as $header_block_name ) {
							$header_block_options[ $header_block_name->post_name ] = $header_block_name->post_title;
						}
						?>
                        <select name="penci_term_data[cat_header_block]" id="penci_term_data[cat_header_block]">
							<?php
							foreach ( $header_block_options as $slug => $name ) {
								echo '<option value="' . $slug . '" ' . selected( $cat_header_block, $slug ) . '>' . $name . '</option>';
							} ?>
                        </select>
                    </td>
                </tr>
                <tr class="form-field term-thumbnail-wrap" data-panel="general">
                    <th scope="row" valign="top">
                        <label><?php esc_html_e( 'Thumbnail', 'soledad' ); ?></label>
                    </th>
                    <td>

                        <div id="pc_term_thumbnail" style="margin-bottom:10px;"><img
                                    src="<?php echo esc_url( $thumb_url ); ?>" width="60px" height="60px"/></div>

                        <div style="line-height: 60px;">
                            <input type="hidden" value="<?php echo $thumb_id; ?>" id="penci_categories_thumbnail_id"
                                   name="penci_term_data[thumbnail_id]"/>
                            <button type="button"
                                    class="upload_image_button button"><?php esc_html_e( 'Upload/Add image', 'soledad' ); ?></button>
                            <button type="button"
                                    class="remove_image_button button"><?php esc_html_e( 'Remove image', 'soledad' ); ?></button>
                        </div>
                    <td>
                        <script type="text/javascript">

                          // Only show the "remove image" button when needed
                          if (!jQuery('#penci_categories_thumbnail_id').val()) {
                            jQuery('.remove_image_button').hide()
                          }

                          // Uploading files
                          var file_frame

                          jQuery(document).on('click', '.upload_image_button', function (event) {

                            event.preventDefault()

                            // If the media frame already exists, reopen it.
                            if (file_frame) {
                              file_frame.open()
                              return
                            }

                            // Create the media frame.
                            file_frame = wp.media.frames.downloadable_file = wp.media({
                              title: '<?php esc_html_e( 'Choose an image', 'soledad' ); ?>',
                              button: {
                                text: '<?php esc_html_e( 'Use image', 'soledad' ); ?>',
                              },
                              multiple: false,
                            })

                            // When an image is selected, run a callback.
                            file_frame.on('select', function () {
                              var attachment = file_frame.state().get('selection').first().toJSON()
                              var attachment_thumbnail = attachment.sizes.thumbnail || attachment.sizes.full

                              jQuery('#penci_categories_thumbnail_id').val(attachment.id)
                              jQuery('#pc_term_thumbnail').find('img').attr('src', attachment_thumbnail.url)
                              jQuery('.remove_image_button').show()
                            })

                            // Finally, open the modal.
                            file_frame.open()
                          })

                          jQuery(document).on('click', '.remove_image_button', function () {
                            jQuery('#pc_term_thumbnail').
                              find('img').
                              attr('src', '<?php echo esc_js( $default_thumb ); ?>')
                            jQuery('#penci_categories_thumbnail_id').val('')
                            jQuery('.remove_image_button').hide()
                            return false
                          })

                          jQuery(document).ajaxComplete(function (event, request, options) {
                            if (request && 4 === request.readyState && 200 === request.status
                              && options.data && 0 <= options.data.indexOf('action=add-tag')) {

                              var res = wpAjax.parseAjaxResponse(request.responseXML, 'ajax-response')
                              if (!res || res.errors) {
                                return
                              }
                              // Clear Thumbnail fields on submit
                              jQuery('#pc_term_thumbnail').
                                find('img').
                                attr('src', '<?php echo esc_js( $default_thumb ); ?>')
                              jQuery('#penci_categories_thumbnail_id').val('')
                              jQuery('.remove_image_button').hide()
                              // Clear Display type field on submit
                              jQuery('#display_type').val('')
                              return
                            }
                          })

                        </script>
                </tr>

                </tbody>
            </table>
        </div>

		<?php
	}
}
