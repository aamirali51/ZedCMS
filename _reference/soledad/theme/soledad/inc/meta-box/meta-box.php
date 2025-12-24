<?php
/**
 * Add custom meta box for pages
 * Add custom sidebar for page go here
 * Use add_meta_box() function to hook it
 *
 * @package Wordpress
 * @since 1.0
 *
 */

require PENCI_SOLEDAD_DIR . '/inc/meta-box/meta-box-array.php';

/* Sub title */
add_action( 'admin_head', 'penci_add_subtitle_metaboxes' );
function penci_add_subtitle_metaboxes() {
	// Detect if editor is gutenberg or not - if not, add meta box after post title.
	if ( penci_is_using_gutenberg() ) {
		add_meta_box(
			'penci_post_secondry_title',
			esc_html__( 'Add Subtitle', 'soledad' ),
			'penci_render_subtitle_markup_gutenberg',
			penci_post_types_allow_meta_boxes(),
			'side',
			'high'
		);
	} else {
		add_action( 'edit_form_after_title', 'penci_render_subtitle_markup', 1 );
	}
}

function penci_render_subtitle_markup() {
	$post_id = get_the_ID();

	// Get currently viewing post type
	if ( ! empty( $post_id ) ) {
		$post_type = get_post_type( $post_id );
	}

	if ( empty( $post_type ) && get_current_screen()->post_type ) {
		$post_type = get_current_screen()->post_type;
	}

	// Don't render markup if it's not allowed
	if ( ! in_array( $post_type, penci_post_types_allow_meta_boxes() ) ) {
		return;
	}
	?>

    <div id="penci-subtitlediv">
        <div id="penci-subtitlewrap">
            <label class="screen-reader-text" id="penci-sub-title-label"
                   for="penci-sub-title"><?php esc_html_e( 'Add sub title here', 'soledad' ) ?></label>
            <input type="text" id="penci-sub-title" name="penci_post_sub_title" size="30"
                   style="padding: 3px 8px; font-size: 1.4em; line-height: 100%; height: 2em; width: 100%; outline: 0; margin: 0 0 3px; background-color: #fff;"
                   value="<?php echo esc_attr( get_post_meta( $post_id, 'penci_post_sub_title', true ) ); ?>"
                   placeholder="<?php esc_html_e( 'Add sub title here', 'soledad' ) ?>" spellcheck="true"
                   autocomplete="off">
        </div>
    </div>
	<?php
}

function penci_render_subtitle_markup_gutenberg() {
	$post_id = get_the_ID();

	// Get currently viewing post type
	if ( ! empty( $post_id ) ) {
		$post_type = get_post_type( $post_id );
	}

	if ( empty( $post_type ) && get_current_screen()->post_type ) {
		$post_type = get_current_screen()->post_type;
	}

	// Don't render markup if it's not allowed
	if ( ! in_array( $post_type, penci_post_types_allow_meta_boxes() ) ) {
		return;
	}
	?>

    <div id="penci-subtitlediv">
        <div id="penci-subtitlewrap">
            <label class="screen-reader-text" id="penci-sub-title-label"
                   for="penci-sub-title"><?php esc_html_e( 'Add sub title here', 'soledad' ) ?></label>
            <textarea name="penci_post_sub_title" rows="6" style="height: 80px; width: 100%; font-size: 1.3em;"
                      placeholder="<?php esc_html_e( 'Add sub title here', 'soledad' ) ?>" spellcheck="false"
                      autocomplete="off"><?php echo esc_attr( get_post_meta( $post_id, 'penci_post_sub_title', true ) ); ?></textarea>
        </div>
    </div>
	<?php
}


function Penci_Add_Custom_Metabox() {
	new Penci_Add_Custom_Metabox_Class();
}

if ( is_admin() ) {
	add_action( 'load-post.php', 'Penci_Add_Custom_Metabox' );
	add_action( 'load-post-new.php', 'Penci_Add_Custom_Metabox' );
}

/**
 * The Class.
 */
class Penci_Add_Custom_Metabox_Class {

	/**
	 * Hook into the appropriate actions when the class is constructed.
	 */
	public function __construct() {
		add_action( 'add_meta_boxes', array( $this, 'add_meta_box' ) );
		add_action( 'save_post', array( $this, 'save' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_scr' ) );
	}

	public function admin_scr() {
		wp_enqueue_style( 'penci-adm-select2', PENCI_SOLEDAD_URL . '/inc/customizer/css/select2.min.css', array(), PENCI_SOLEDAD_VERSION );
		wp_enqueue_script( 'penci-adm-select2', PENCI_SOLEDAD_URL . '/inc/customizer/js/select2.full.min.js', array( 'jquery' ), PENCI_SOLEDAD_VERSION, true );
		wp_enqueue_script( 'penci-adm-users', PENCI_SOLEDAD_URL . '/js/get_user.js', array(
			'jquery',
			'penci-adm-select2'
		), PENCI_SOLEDAD_VERSION, true );
		wp_localize_script(
			'penci-adm-users',
			'penci_adm_users',
			array(
				'ajax'  => esc_url( admin_url( 'admin-ajax.php' ) ),
				'nonce' => wp_create_nonce( 'penci-adm-users' ),
			)
		);
	}

	/**
	 * Adds the meta box container.
	 */
	public function add_meta_box( $post_type ) {
		$post_types = penci_post_types_allow_meta_boxes();     //limit meta box to certain post types
		if ( in_array( $post_type, $post_types ) ) {
			add_meta_box(
				'penci_custom_sidebar_page'
				, esc_html__( 'Options for This Post/Page', 'soledad' )
				, array( $this, 'render_meta_box_content' )
				, $post_type
				, 'advanced'
				, 'high'
			);

			add_meta_box(
				'penci_view_count_custom'
				, esc_html__( 'Post Views', 'soledad' )
				, array( $this, 'render_meta_box_view_count' )
				, $post_type
				, 'side'
				, 'high'
			);
		}
	}

	/**
	 * Save the meta when the post is saved.
	 *
	 * @param int $post_id The ID of the post being saved.
	 */
	public function save( $post_id ) {


		/*
		 * We need to verify this came from the our screen and with proper authorization,
		 * because save_post can be triggered at other times.
		 */

		// Check if our nonce is set.
		if ( ! isset( $_POST['penci_inner_custom_box_nonce'] ) ) {
			return $post_id;
		}

		$nonce = $_POST['penci_inner_custom_box_nonce'];

		// Verify that the nonce is valid.
		if ( ! wp_verify_nonce( $nonce, 'penci_inner_custom_box' ) ) {
			return $post_id;
		}

		// If this is an autosave, our form has not been submitted,
		//     so we don't want to do anything.
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return $post_id;
		}

		$post_type_allow = penci_post_types_allow_meta_boxes();
		$post_type       = $_POST['post_type'];

		// Check the user's permissions.
		if ( 'page' == $_POST['post_type'] ) {

			if ( ! current_user_can( 'edit_page', $post_id ) ) {
				return $post_id;
			}

		} else {

			if ( ! current_user_can( 'edit_post', $post_id ) ) {
				return $post_id;
			}
		}

		// Sanitize the user input.
		$mydata            = sanitize_text_field( $_POST['penci_custom_sidebar_page_field'] );
		$sidebar_left_page = sanitize_text_field( $_POST['penci_custom_sidebar_left_page_field'] );

		if ( in_array( $post_type, $post_type_allow ) ) {
			$sidebar = sanitize_text_field( $_POST['penci_post_sidebar_display'] );
		}

		$hide_header = $hide_footer = '';

		if ( 'page' == $_POST['post_type'] ) {
			$slider         = sanitize_text_field( $_POST['penci_page_slider_field'] );
			$featured_boxes = sanitize_text_field( $_POST['penci_page_display_featured_boxes'] );
			$pagetitle      = sanitize_text_field( $_POST['penci_page_display_title_field'] );
			$breadcrumb     = sanitize_text_field( $_POST['penci_page_breadcrumb_field'] );
			$sharebox       = sanitize_text_field( $_POST['penci_page_sharebox_field'] );
			$rev_shortcode  = sanitize_text_field( $_POST['penci_page_rev_shortcode'] );
			$hide_header    = sanitize_text_field( $_POST['penci_page_hide_header_field'] );
			$hide_footer    = sanitize_text_field( $_POST['penci_page_hide_footer_field'] );
			$page_sidebar   = sanitize_text_field( $_POST['penci_sidebar_page_pos'] );
		}


		// Update the meta field.
		update_post_meta( $post_id, 'penci_custom_sidebar_page_display', $mydata );
		update_post_meta( $post_id, 'penci_custom_sidebar_left_page_field', $sidebar_left_page );

		if ( isset( $_POST['penci_post_critical_css'] ) ) {
			update_post_meta( $post_id, 'penci_post_critical_css', $_POST['penci_post_critical_css'] );
		}

		if ( isset( $_POST['penci_desktop_page_id'] ) && $_POST['penci_desktop_page_id'] ) {
			update_post_meta( $post_id, 'penci_desktop_page_id', $_POST['penci_desktop_page_id'] );
			update_post_meta( $_POST['penci_desktop_page_id'], 'penci_mobile_page_id', $post_id );
		}

		if ( isset( $_POST['penci_cpost_title'] ) ) {
			update_post_meta( $post_id, 'penci_cpost_title', $_POST['penci_cpost_title'] );
		}

		if ( isset( $_POST['penci_page_dis_ads'] ) ) {
			update_post_meta( $post_id, 'penci_page_dis_ads', $_POST['penci_page_dis_ads'] );
		}
        
        if ( isset( $_POST['penci_helpful_enable'] ) ) {
			update_post_meta( $post_id, 'penci_helpful_enable', $_POST['penci_helpful_enable'] );
		}

		if ( in_array( $post_type, $post_type_allow ) ) {
			update_post_meta( $post_id, 'penci_post_sidebar_display', $sidebar );

			if ( isset( $_POST['penci_post_sub_title'] ) ) {
				update_post_meta( $post_id, 'penci_post_sub_title', $_POST['penci_post_sub_title'] );
			}

			if ( isset( $_POST['penci_single_style'] ) ) {
				update_post_meta( $post_id, 'penci_single_style', $_POST['penci_single_style'] );
			}

			if ( isset( $_POST['penci_pfeatured_image_ratio'] ) ) {
				update_post_meta( $post_id, 'penci_pfeatured_image_ratio', $_POST['penci_pfeatured_image_ratio'] );
			}

			if ( isset( $_POST['penci_enable_jarallax_single'] ) ) {
				update_post_meta( $post_id, 'penci_enable_jarallax_single', $_POST['penci_enable_jarallax_single'] );
			}

			if ( isset( $_POST['penci_toc_enable'] ) ) {
				update_post_meta( $post_id, 'penci_toc_enable', $_POST['penci_toc_enable'] );
			}
            
            if ( isset( $_POST['penci_helpful_enable'] ) ) {
				update_post_meta( $post_id, 'penci_helpful_enable', $_POST['penci_helpful_enable'] );
			}

			if ( isset( $_POST['penci_extra_author'] ) ) {
				update_post_meta( $post_id, 'penci_extra_author', $_POST['penci_extra_author'] );
			}

			if ( isset( $_POST['penci_extra_author_id'] ) ) {
				update_post_meta( $post_id, 'penci_extra_author_id', $_POST['penci_extra_author_id'] );
			}

			if ( isset( $_POST['penci_post_hide_featuimg'] ) ) {
				update_post_meta( $post_id, 'penci_post_hide_featuimg', $_POST['penci_post_hide_featuimg'] );
			}

			if ( isset( $_POST['penci_reading_time'] ) ) {
				update_post_meta( $post_id, 'penci_reading_time', $_POST['penci_reading_time'] );
			}

			if ( isset( $_POST['penci_header_builder_layout'] ) ) {
				update_post_meta( $post_id, 'penci_header_builder_layout', $_POST['penci_header_builder_layout'] );
			}

			if ( isset( $_POST['penci_header_block_layout'] ) ) {
				update_post_meta( $post_id, 'penci_header_block_layout', $_POST['penci_header_block_layout'] );
			}

			if ( isset( $_POST['penci_header_layout'] ) ) {
				update_post_meta( $post_id, 'penci_header_layout', $_POST['penci_header_layout'] );
			}

			if ( isset( $_POST['penci_footer_builder_layout'] ) ) {
				update_post_meta( $post_id, 'penci_footer_builder_layout', $_POST['penci_footer_builder_layout'] );
			}

			if ( isset( $_POST['penci_single_builder_layout'] ) ) {
				update_post_meta( $post_id, 'penci_single_builder_layout', $_POST['penci_single_builder_layout'] );
			}

			if ( isset( $_POST['penci_sponsored_post'] ) ) {
				update_post_meta( $post_id, 'penci_sponsored_post', $_POST['penci_sponsored_post'] );
			}

			if ( isset( $_POST['penci_sponsored_logo'] ) ) {
				update_post_meta( $post_id, 'penci_sponsored_logo', $_POST['penci_sponsored_logo'] );
			}

			if ( isset( $_POST['penci_sponsored_url'] ) ) {
				update_post_meta( $post_id, 'penci_sponsored_url', $_POST['penci_sponsored_url'] );
			}

			if ( isset( $_POST['penci_sponsored_redirect'] ) ) {
				update_post_meta( $post_id, 'penci_sponsored_redirect', $_POST['penci_sponsored_redirect'] );
			}

			if ( isset( $_POST['penci_video_preview_url'] ) ) {
				update_post_meta( $post_id, 'penci_video_preview_url', $_POST['penci_video_preview_url'] );
			}

			if ( isset( $_POST['penci_video_preview'] ) ) {
				update_post_meta( $post_id, 'penci_video_preview', $_POST['penci_video_preview'] );
			}

			if ( isset( $_POST['penci_post_sources'] ) ) {
				update_post_meta( $post_id, 'penci_post_sources', $_POST['penci_post_sources'] );
			}

			$count_key  = penci_get_postviews_key();
			$count_wkey = 'penci_post_week_views_count';
			$count_mkey = 'penci_post_month_views_count';

			if ( isset( $_POST[ $count_key ] ) ) {
				update_post_meta( $post_id, $count_key, $_POST[ $count_key ] );
			}

			if ( isset( $_POST[ $count_wkey ] ) ) {
				update_post_meta( $post_id, $count_wkey, $_POST[ $count_wkey ] );
			}

			if ( isset( $_POST[ $count_mkey ] ) ) {
				update_post_meta( $post_id, $count_mkey, $_POST[ $count_mkey ] );
			}
		}

		if ( 'page' == $_POST['post_type'] ) {
			update_post_meta( $post_id, 'penci_page_slider', $slider );
			update_post_meta( $post_id, 'penci_page_display_featured_boxes', $featured_boxes );
			update_post_meta( $post_id, 'penci_page_display_title', $pagetitle );
			update_post_meta( $post_id, 'penci_page_breadcrumb', $breadcrumb );
			update_post_meta( $post_id, 'penci_page_sharebox', $sharebox );
			update_post_meta( $post_id, 'penci_page_rev_shortcode', $rev_shortcode );
			update_post_meta( $post_id, 'penci_page_hide_header', $hide_header );
			update_post_meta( $post_id, 'penci_page_hide_footer', $hide_footer );
			update_post_meta( $post_id, 'penci_sidebar_page_pos', $page_sidebar );
		}
	}

	/**
	 * Render Meta Box content.
	 *
	 * @param WP_Post $post The post object.
	 */
	public function render_meta_box_view_count( $post ) {

		// Add an nonce field so we can check for it later.
		wp_nonce_field( 'penci_inner_custom_box', 'penci_inner_custom_box_nonce' );

		// Use get_post_meta to retrieve an existing value from the database.
		$count_key  = penci_get_postviews_key();
		$count_wkey = 'penci_post_week_views_count';
		$count_mkey = 'penci_post_month_views_count';
		$count_dkey = 'penci_post_day_views_count';
		$count      = get_post_meta( $post->ID, $count_key, true ) ? get_post_meta( $post->ID, $count_key, true ) : 0;
		$count_w    = get_post_meta( $post->ID, $count_wkey, true ) ? get_post_meta( $post->ID, $count_wkey, true ) : 0;
		$count_m    = get_post_meta( $post->ID, $count_mkey, true ) ? get_post_meta( $post->ID, $count_mkey, true ) : 0;
		$count_d    = get_post_meta( $post->ID, $count_dkey, true ) ? get_post_meta( $post->ID, $count_dkey, true ) : 0;


		// Display the form, using the current value.
		?>
        <p><?php esc_html_e( 'You can change the view count for this post by change values below.', 'soledad' ); ?></p>
        <h2 style="font-weight: 600; font-size: 13px; padding-left: 0;"><?php esc_html_e( 'All Time Views', 'soledad' ); ?></h2>
        <p><input id="<?php echo $count_key . '_id'; ?>" name="<?php echo $count_key; ?>" type="number"
                  value="<?php echo absint( $count ); ?>"></p>

        <h2 style="font-weight: 600; font-size: 13px; padding-left: 0;"><?php esc_html_e( 'Today Views', 'soledad' ); ?></h2>
        <p><input id="<?php echo $count_dkey . '_id'; ?>" name="<?php echo $count_wkey; ?>" type="number"
                  value="<?php echo absint( $count_d ); ?>"></p>
                  
        <h2 style="font-weight: 600; font-size: 13px; padding-left: 0;"><?php esc_html_e( 'This Week Views', 'soledad' ); ?></h2>
        <p><input id="<?php echo $count_wkey . '_id'; ?>" name="<?php echo $count_wkey; ?>" type="number"
                  value="<?php echo absint( $count_w ); ?>"></p>

        <h2 style="font-weight: 600; font-size: 13px; padding-left: 0;"><?php esc_html_e( 'This Month Views', 'soledad' ); ?></h2>
        <p><input id="<?php echo $count_mkey . '_id'; ?>" name="<?php echo $count_mkey; ?>" type="number"
                  value="<?php echo absint( $count_m ); ?>"></p>
        <p style="font-size: 12px;"><?php _e( '<strong>Note that:</strong> If you don\'t see the weekly view count reset after installing the theme in some weeks, that means your hosting doesn\'t support <a href="https://developer.wordpress.org/reference/functions/wp_schedule_event/" target="_blank">wp_schedule_event</a> function from WordPress. So, if you want to get it to work, please contact your hosting provider and requirement them allows it.', 'soledad' ); ?></p>
		<?php
	}


	/**
	 * Render Meta Box content.
	 *
	 * @param WP_Post $post The post object.
	 */
	public function render_meta_box_content( $post ) {

		// Add an nonce field so we can check for it later.
		wp_nonce_field( 'penci_inner_custom_box', 'penci_inner_custom_box_nonce' );

		// Use get_post_meta to retrieve an existing value from the database.
		$value      = get_post_meta( $post->ID, 'penci_custom_sidebar_page_display', true );
		$value_left = get_post_meta( $post->ID, 'penci_custom_sidebar_left_page_field', true );


		$sidebar        = get_post_meta( $post->ID, 'penci_post_sidebar_display', true );
		$slider         = get_post_meta( $post->ID, 'penci_page_slider', true );
		$featured_boxes = get_post_meta( $post->ID, 'penci_page_display_featured_boxes', true );
		$pagetitle      = get_post_meta( $post->ID, 'penci_page_display_title', true );
		$breadcrumb     = get_post_meta( $post->ID, 'penci_page_breadcrumb', true );
		$sharebox       = get_post_meta( $post->ID, 'penci_page_sharebox', true );
		$rev_shortcode  = get_post_meta( $post->ID, 'penci_page_rev_shortcode', true );
		$single_style   = get_post_meta( $post->ID, 'penci_single_style', true );
		$hide_featuimg  = get_post_meta( $post->ID, 'penci_post_hide_featuimg', true );

		$hide_header               = get_post_meta( $post->ID, 'penci_page_hide_header', true );
		$hide_footer               = get_post_meta( $post->ID, 'penci_page_hide_footer', true );
		$pfeatured_image_ratio     = get_post_meta( $post->ID, 'penci_pfeatured_image_ratio', true );
		$penci_reading_time        = get_post_meta( $post->ID, 'penci_reading_time', true );
		$enable_parallax           = get_post_meta( $post->ID, 'penci_enable_jarallax_single', true );
		$penci_toc_enable          = get_post_meta( $post->ID, 'penci_toc_enable', true );
		$penci_helpful_enable      = get_post_meta( $post->ID, 'penci_helpful_enable', true );
		$penci_extra_author        = get_post_meta( $post->ID, 'penci_extra_author', true );
		$penci_extra_author_id     = get_post_meta( $post->ID, 'penci_extra_author_id', true );
		$page_sidebar              = get_post_meta( $post->ID, 'penci_sidebar_page_pos', true );
		$headerdf_layout           = get_post_meta( $post->ID, 'penci_header_layout', true );
		$headerbd_layout           = get_post_meta( $post->ID, 'penci_header_builder_layout', true );
		$footerbd_layout           = get_post_meta( $post->ID, 'penci_footer_builder_layout', true );
		$penci_header_block_layout = get_post_meta( $post->ID, 'penci_header_block_layout', true );
		$singlebd_layout           = get_post_meta( $post->ID, 'penci_single_builder_layout', true );
		$penci_post_critical_css   = get_post_meta( $post->ID, 'penci_post_critical_css', true );
		$penci_cpost_title         = get_post_meta( $post->ID, 'penci_cpost_title', true );
		$penci_sponsored_url       = get_post_meta( $post->ID, 'penci_sponsored_url', true );
		$penci_sponsored_post      = get_post_meta( $post->ID, 'penci_sponsored_post', true );
		$penci_sponsored_redirect  = get_post_meta( $post->ID, 'penci_sponsored_redirect', true );
		$penci_sponsored_logo      = get_post_meta( $post->ID, 'penci_sponsored_logo', true );
		$penci_video_preview       = get_post_meta( $post->ID, 'penci_video_preview', true );
		$penci_video_preview_url   = get_post_meta( $post->ID, 'penci_video_preview_url', true );
		$penci_page_dis_ads        = get_post_meta( $post->ID, 'penci_page_dis_ads', true );
		$penci_post_sources        = get_post_meta( $post->ID, 'penci_post_sources', true );


		$post_type_allow = penci_post_types_allow_meta_boxes();
		$post_type       = get_post_type( $post->ID );
		// Display the form, using the current value.

		$header_layout = [];
		$footer_layout = [];
		$single_layout = [];

		$header_layout[''] = esc_attr__( 'Default Settings' );
		$footer_layout[''] = esc_attr__( 'Default Settings' );
		$single_layout[''] = esc_attr__( 'Default Settings' );

		$header_layouts = get_posts( [
			'post_type'      => 'penci_builder',
			'posts_per_page' => - 1,
		] );
		foreach ( $header_layouts as $header ) {
			$header_layout[ $header->ID ] = $header->post_title;
		}

		$footer_layouts = get_posts( [
			'post_type'      => 'penci-block',
			'posts_per_page' => - 1,
		] );
		foreach ( $footer_layouts as $footer ) {
			$footer_layout[ $footer->ID ] = $footer->post_title;
		}

		$single_layouts = get_posts( [
			'post_type'      => 'custom-post-template',
			'posts_per_page' => - 1,
		] );
		foreach ( $single_layouts as $slayout ) {
			$single_layout[ $slayout->post_name ] = $slayout->post_title;
		}
		?>

		<?php if ( 'page' == get_post_type( $post->ID ) ) {
			do_action( 'penci_page_metabox' );
			?>
            <div class="pcmt-control-wrapper">
                <div class="pcmt-title">
                    <h2 style="font-weight: 600; font-size: 14px; padding-left: 0;"><?php _e( 'Select Featured Slider/Featured
                        Video to
                        Display on Top of This Page?', 'soledad' ); ?></h2>
                </div>
                <div class="pcmt-control">
                    <p>
                        <select id="penci_page_slider_field" name="penci_page_slider_field">
                            <option value="">None</option>
                            <option value="style-1" <?php selected( $slider, 'style-1' ); ?>><?php _e( 'Posts Featured Slider Style
                                1', 'soledad' ); ?>
                            </option>
                            <option value="style-2" <?php selected( $slider, 'style-2' ); ?>><?php _e( 'Posts Featured Slider Style
                                2', 'soledad' ); ?>
                            </option>
                            <option value="style-3" <?php selected( $slider, 'style-3' ); ?>><?php _e( 'Posts Featured Slider Style
                                3', 'soledad' ); ?>
                            </option>
                            <option value="style-4" <?php selected( $slider, 'style-4' ); ?>><?php _e( 'Posts Featured Slider Style
                                4', 'soledad' ); ?>
                            </option>
                            <option value="style-5" <?php selected( $slider, 'style-5' ); ?>><?php _e( 'Posts Featured Slider Style
                                5', 'soledad' ); ?>
                            </option>
                            <option value="style-6" <?php selected( $slider, 'style-6' ); ?>><?php _e( 'Posts Featured Slider Style
                                6', 'soledad' ); ?>
                            </option>
                            <option value="style-7" <?php selected( $slider, 'style-7' ); ?>><?php _e( 'Posts Featured Slider Style
                                7', 'soledad' ); ?>
                            </option>
                            <option value="style-8" <?php selected( $slider, 'style-8' ); ?>><?php _e( 'Posts Featured Slider Style
                                8', 'soledad' ); ?>
                            </option>
                            <option value="style-9" <?php selected( $slider, 'style-9' ); ?>><?php _e( 'Posts Featured Slider Style
                                9', 'soledad' ); ?>
                            </option>
                            <option value="style-10" <?php selected( $slider, 'style-10' ); ?>><?php _e( 'Posts Featured Slider
                                Style 10', 'soledad' ); ?>
                            </option>
                            <option value="style-11" <?php selected( $slider, 'style-11' ); ?>><?php _e( 'Posts Featured Slider
                                Style 11', 'soledad' ); ?>
                            </option>
                            <option value="style-12" <?php selected( $slider, 'style-12' ); ?>><?php _e( 'Posts Featured Slider
                                Style 12', 'soledad' ); ?>
                            </option>
                            <option value="style-13" <?php selected( $slider, 'style-13' ); ?>><?php _e( 'Posts Featured Slider
                                Style 13', 'soledad' ); ?>
                            </option>
                            <option value="style-14" <?php selected( $slider, 'style-14' ); ?>><?php _e( 'Posts Featured Slider
                                Style 14', 'soledad' ); ?>
                            </option>
                            <option value="style-15" <?php selected( $slider, 'style-15' ); ?>><?php _e( 'Posts Featured Slider
                                Style 15', 'soledad' ); ?>
                            </option>
                            <option value="style-16" <?php selected( $slider, 'style-16' ); ?>><?php _e( 'Posts Featured Slider
                                Style 16', 'soledad' ); ?>
                            </option>
                            <option value="style-17" <?php selected( $slider, 'style-17' ); ?>><?php _e( 'Posts Featured Slider
                                Style 17', 'soledad' ); ?>
                            </option>
                            <option value="style-18" <?php selected( $slider, 'style-18' ); ?>><?php _e( 'Posts Featured Slider
                                Style 18', 'soledad' ); ?>
                            </option>
                            <option value="style-19" <?php selected( $slider, 'style-19' ); ?>><?php _e( 'Posts Featured Slider
                                Style 19', 'soledad' ); ?>
                            </option>
                            <option value="style-20" <?php selected( $slider, 'style-20' ); ?>><?php _e( 'Posts Featured Slider
                                Style 20', 'soledad' ); ?>
                            </option>
                            <option value="style-21" <?php selected( $slider, 'style-21' ); ?>><?php _e( 'Posts Featured Slider
                                Style 21', 'soledad' ); ?>
                            </option>
                            <option value="style-22" <?php selected( $slider, 'style-22' ); ?>><?php _e( 'Posts Featured Slider
                                Style 22', 'soledad' ); ?>
                            </option>
                            <option value="style-23" <?php selected( $slider, 'style-23' ); ?>><?php _e( 'Posts Featured Slider
                                Style 23', 'soledad' ); ?>
                            </option>
                            <option value="style-24" <?php selected( $slider, 'style-24' ); ?>><?php _e( 'Posts Featured Slider
                                Style 24', 'soledad' ); ?>
                            </option>
                            <option value="style-25" <?php selected( $slider, 'style-25' ); ?>><?php _e( 'Posts Featured Slider
                                Style 25', 'soledad' ); ?>
                            </option>
                            <option value="style-26" <?php selected( $slider, 'style-26' ); ?>><?php _e( 'Posts Featured Slider
                                Style 26', 'soledad' ); ?>
                            </option>
                            <option value="style-27" <?php selected( $slider, 'style-27' ); ?>><?php _e( 'Posts Featured Slider
                                Style 27', 'soledad' ); ?>
                            </option>
                            <option value="style-28" <?php selected( $slider, 'style-28' ); ?>><?php _e( 'Posts Featured Slider
                                Style 28', 'soledad' ); ?>
                            </option>
                            <option value="style-29" <?php selected( $slider, 'style-29' ); ?>><?php _e( 'Posts Featured Slider
                                Style 29', 'soledad' ); ?>
                            </option>
                            <option value="style-30" <?php selected( $slider, 'style-30' ); ?>><?php _e( 'Posts Featured Slider
                                Style 30', 'soledad' ); ?>
                            </option>
                            <option value="style-31" <?php selected( $slider, 'style-31' ); ?>><?php _e( 'Penci Slider Style 1', 'soledad' ); ?>
                            </option>
                            <option value="style-32" <?php selected( $slider, 'style-32' ); ?>><?php _e( 'Penci Slider Style 2', 'soledad' ); ?>
                            </option>
                            <option value="style-33" <?php selected( $slider, 'style-33' ); ?>><?php _e( 'Revolution Slider Full
                                Width', 'soledad' ); ?>
                            </option>
                            <option value="style-34" <?php selected( $slider, 'style-34' ); ?>><?php _e( 'Revolution Slider In
                                Container', 'soledad' ); ?>
                            </option>
                            <option value="style-35" <?php selected( $slider, 'style-35' ); ?>><?php _e( 'Posts Featured Slider
                                Style 35', 'soledad' ); ?>
                            </option>
                            <option value="style-36" <?php selected( $slider, 'style-36' ); ?>><?php _e( 'Posts Featured Slider
                                Style 36', 'soledad' ); ?>
                            </option>
                            <option value="style-37" <?php selected( $slider, 'style-37' ); ?>><?php _e( 'Posts Featured Slider
                                Style 37', 'soledad' ); ?>
                            </option>
                            <option value="style-38" <?php selected( $slider, 'style-38' ); ?>><?php _e( 'Posts Featured Slider
                                Style 38', 'soledad' ); ?>
                            </option>
                            <option value="style-40" <?php selected( $slider, 'style-40' ); ?>><?php _e( 'Posts Featured Slider
                                Style 39', 'soledad' ); ?>
                            <option value="style-41" <?php selected( $slider, 'style-41' ); ?>><?php _e( 'Posts Featured Slider
                                Style 40', 'soledad' ); ?>
                            <option value="style-42" <?php selected( $slider, 'style-42' ); ?>><?php _e( 'Posts Featured Slider
                                Style 41', 'soledad' ); ?>
                            <option value="style-44" <?php selected( $slider, 'style-44' ); ?>><?php _e( 'Posts Featured Slider
                                Style 42', 'soledad' ); ?>
                            </option>
                            <option value="video" <?php selected( $slider, 'video' ); ?>><?php _e( 'Featured Video Background', 'soledad' ); ?>
                            </option>
                        </select>
                    </p>
                </div>
            </div>
            <div class="pcmt-control-wrapper">
                <div class="pcmt-title">
                    <h2 style="font-weight: 600; font-size: 14px; padding-left: 0;"><?php _e( 'Revolution Slider Shortcode', 'soledad' ); ?></h2>
                    <p class="description"><?php _e( 'If you select Revolution Slider above, please fill Revolution Slider
                        Shortcode here', 'soledad' ); ?></p>
                </div>
                <div class="pcmt-control">
                    <textarea style="width: 100%; height: 50px;"
                              name="penci_page_rev_shortcode"><?php if ( $rev_shortcode ): echo $rev_shortcode; endif; ?></textarea>
                </div>
            </div>
            <div class="pcmt-control-wrapper">
                <div class="pcmt-title">
                    <h2 style="font-weight: 600; font-size: 14px; padding-left: 0;"><?php _e( 'Display Featured Boxes?', 'soledad' ); ?></h2>
                </div>
                <div class="pcmt-control select-button-type">
                    <p>
                        <select id="penci_page_display_featured_boxes" name="penci_page_display_featured_boxes">
                            <option value=""><?php _e( 'No', 'soledad' ); ?></option>
                            <option value="yes" <?php selected( $featured_boxes, 'yes' ); ?>><?php _e( 'Yes', 'soledad' ); ?></option>
                        </select>
                    </p>
                </div>
            </div>
            <div class="pcmt-control-wrapper">
                <div class="pcmt-title">
                    <h2 style="font-weight: 600; font-size: 14px; padding-left: 0;"><?php _e( 'Display Page Title?', 'soledad' ); ?></h2>
                    <p class="descriptions"><?php _e( 'By default, this option will follow on Customizer settings', 'soledad' ); ?></p>
                </div>
                <div class="pcmt-control select-button-type">
                    <p>
                        <select id="penci_page_display_title_field" name="penci_page_display_title_field">
                            <option value=""><?php _e( 'Default', 'soledad' ); ?></option>
                            <option value="yes" <?php selected( $pagetitle, 'no' ); ?>><?php _e( 'Yes', 'soledad' ); ?></option>
                            <option value="no" <?php selected( $pagetitle, 'no' ); ?>><?php _e( 'No', 'soledad' ); ?></option>
                        </select>
                    </p>
                </div>
            </div>
            <div class="pcmt-control-wrapper">
                <div class="pcmt-title">

                    <h2 style="font-weight: 600; font-size: 14px; padding-left: 0;"><?php _e( 'Display Breadcrumb on This
                        Page?', 'soledad' ); ?></h2>
                </div>
                <div class="pcmt-control select-button-type">
                    <p>
                        <select id="penci_page_breadcrumb_field" name="penci_page_breadcrumb_field">
                            <option value=""><?php _e( 'Yes', 'soledad' ); ?></option>
                            <option value="no" <?php selected( $breadcrumb, 'no' ); ?>><?php _e( 'No', 'soledad' ); ?></option>
                        </select>
                    </p>
                </div>
            </div>
            <div class="pcmt-control-wrapper">
                <div class="pcmt-title">

                    <h2 style="font-weight: 600; font-size: 14px; padding-left: 0;"><?php _e( 'Display Share Box on This Page?', 'soledad' ); ?></h2>
                </div>
                <div class="pcmt-control select-button-type">
                    <p>
                        <select id="penci_page_sharebox_field" name="penci_page_sharebox_field">
                            <option value=""><?php _e( 'Yes', 'soledad' ); ?></option>
                            <option value="no" <?php selected( $sharebox, 'no' ); ?>><?php _e( 'No', 'soledad' ); ?></option>
                        </select>
                    </p>
                </div>
            </div>
            <div class="pcmt-control-wrapper">
                <div class="pcmt-title">


                    <h2 style="font-weight: 600; font-size: 14px; padding-left: 0;"><?php _e( 'Hide Header on This Page?', 'soledad' ); ?></h2>
                </div>
                <div class="pcmt-control select-button-type">
                    <p>
                        <select id="penci_page_hide_header_field" name="penci_page_hide_header_field">
                            <option value=""><?php _e( 'No', 'soledad' ); ?></option>
                            <option value="yes" <?php selected( $hide_header, 'yes' ); ?>><?php _e( 'Yes', 'soledad' ); ?></option>
                        </select>
                    </p>
                </div>
            </div>
            <div class="pcmt-control-wrapper">
                <div class="pcmt-title">

                    <h2 style="font-weight: 600; font-size: 14px; padding-left: 0;"><?php _e( 'Hide Footer on This Page?', 'soledad' ); ?></h2>
                </div>
                <div class="pcmt-control select-button-type">
                    <p>
                        <select id="penci_page_hide_footer_field" name="penci_page_hide_footer_field">
                            <option value=""><?php _e( 'No', 'soledad' ); ?></option>
                            <option value="yes" <?php selected( $hide_footer, 'yes' ); ?>><?php _e( 'Yes', 'soledad' ); ?></option>
                        </select>
                    </p>
                </div>
            </div>
            <div class="pcmt-control-wrapper">
                <div class="pcmt-title">
                    <h2 style="font-weight: 600; font-size: 14px; padding-left: 0;"><?php _e( 'Select Sidebar Position for This
                        Page', 'soledad' ); ?></h2>
                    <p class="description"><?php esc_html_e( 'This option just apply for Page Template "Page with Sidebar" and "Page VC Builder with Sidebar"', 'soledad' ); ?></p>
                </div>
                <div class="pcmt-control">
                    <p>
                        <select id="penci_sidebar_page_pos" name="penci_sidebar_page_pos">
                            <option value=""><?php esc_html_e( "Default", "soledad" ); ?></option>
                            <option value="left-sidebar" <?php selected( $page_sidebar, 'left-sidebar' ); ?>><?php esc_html_e( "Left Sidebar", "soledad" ); ?></option>
                            <option value="right-sidebar" <?php selected( $page_sidebar, 'right-sidebar' ); ?>><?php esc_html_e( "Right Sidebar", "soledad" ); ?></option>
                            <option value="two-sidebar" <?php selected( $page_sidebar, 'two-sidebar' ); ?>><?php esc_html_e( "Two Sidebar", "soledad" ); ?></option>
                        </select>
                    </p>
                </div>
            </div>
		<?php } ?>

		<?php if ( in_array( $post_type, $post_type_allow ) ) { ?>
            <div class="pcmt-control-wrapper">
                <div class="pcmt-title">
                    <h2 style="font-weight: 600; font-size: 14px; padding-left: 0;"><?php esc_html_e( 'Hide Featured Image Auto Appears on
                        This
                        Post?', 'soledad' ); ?></h2>
                    <p class="description">
						<?php esc_html_e( 'This option only applies to Single Post Template Styles 1 & 2.', 'soledad' ); ?>
                        <br>
						<?php esc_html_e( 'if you wish to hide Featured Images from automatically appearing on all posts, you can select the option to do so by going to Customize > Single Posts > General > Hide Featured Image on Top.', 'soledad' ); ?>
                    </p>
                </div>
                <div class="pcmt-control">
                    <p>
                        <select id="penci_post_hide_featuimg" name="penci_post_hide_featuimg">
                            <option value=""><?php esc_html_e( 'Default ( follow Customize )', 'soledad' ); ?></option>
                            <option value="no" <?php selected( $hide_featuimg, 'no' ); ?>><?php esc_html_e( 'No, Show Featured Image', 'soledad' ); ?>
                            </option>
                            <option value="yes" <?php selected( $hide_featuimg, 'yes' ); ?>><?php esc_html_e( 'Yes, Hide Featured Image', 'soledad' ); ?>
                            </option>
                        </select>
                    </p>
                </div>
            </div>
            <div class="pcmt-control-wrapper">
                <div class="pcmt-title">

                    <h2 style="font-weight: 600; font-size: 14px; padding-left: 0;"><?php esc_html_e( 'Custom Post Title on Single Post
                        Page', 'soledad' ); ?></h2>
                    <p class="description"><?php esc_html_e( 'You can enter a custom post title to be displayed on the single post page
                        that is different from the post title shown on archive pages.', 'soledad' ); ?></p>
                </div>
                <div class="pcmt-control">
                    <textarea style="width: 100%; height: 50px;"
                              placeholder="<?php esc_html_e( 'Enter the custom single post title', 'soledad' ); ?>"
                              id="penci_cpost_title"
                              name="penci_cpost_title"><?php echo $penci_cpost_title; ?></textarea>
                </div>
            </div>
            <div class="pcmt-control-wrapper">
                <div class="pcmt-title">

                    <h2 style="font-weight: 600; font-size: 14px; padding-left: 0;"><?php esc_html_e( 'Reading Time', 'soledad' ); ?></h2>
                    <p class="description"><?php _e( 'Please fill in the estimated reading time for this post. For example: 3
                        mins<br>If you want to set a default reading time value for all posts, you can do so by going to
                        <strong>Customize > General >
                            General Settings > Set A Default Reading Time Value</strong>', 'soledad' ); ?></p>
                </div>
                <div class="pcmt-control">
                    <p><input placeholder="<?php esc_html_e( 'Enter the custom post reading time', 'soledad' ); ?>"
                              id="penci_reading_time"
                              name="penci_reading_time" type="text"
                              value="<?php echo esc_attr( $penci_reading_time ); ?>"></p>
                </div>
            </div>
            <div class="pcmt-control-wrapper">
                <div class="pcmt-title">
                    <h2 style="font-weight: 600; font-size: 14px; padding-left: 0;"><?php _e( 'Sidebar Layout for this post?', 'soledad' ); ?></h2>
                </div>
                <div class="pcmt-control">
                    <p>
                        <select id="penci_post_sidebar_display" name="penci_post_sidebar_display">
                            <option value=""><?php _e( 'Default Value ( on Customize )', 'soledad' ); ?></option>
                            <option value="left" <?php selected( $sidebar, 'left' ); ?>><?php _e( 'Left Sidebar', 'soledad' ); ?></option>
                            <option value="right" <?php selected( $sidebar, 'right' ); ?>><?php _e( 'Right Sidebar', 'soledad' ); ?></option>
                            <option value="two" <?php selected( $sidebar, 'two' ); ?>><?php _e( 'Two Sidebar', 'soledad' ); ?></option>
                            <option value="no" <?php selected( $sidebar, 'no' ); ?>><?php _e( 'No Sidebar', 'soledad' ); ?></option>
                            <option value="small_width" <?php selected( $sidebar, 'small_width' ); ?>><?php _e( 'No Sidebar with
                                Container Width Smaller', 'soledad' ); ?>
                            </option>
                        </select>
                    </p>
                </div>
            </div>
		<?php } ?>
        <div class="pcmt-control-wrapper">
            <div class="pcmt-title">
                <h2 style="font-weight: 600; font-size: 14px; padding-left: 0;"><?php esc_html_e( 'Custom Sidebar for This Posts/Page', 'soledad' ); ?></h2>
                <p class="description"><?php esc_html_e( 'Note: for pages, you can choose whether or not to display a sidebar in the "Page with Sidebar" template and customize the sidebar there. if the sidebar you choose here is empty, the sidebar you chose for the page in Customize will be displayed.', 'soledad' ); ?></p>
            </div>
            <div class="pcmt-control">
                <p>
                    <select id="penci_custom_sidebar_page_field" name="penci_custom_sidebar_page_field">
                        <option value=""><?php esc_html_e( "Default Sidebar( on Customize )", "soledad" ); ?></option>
                        <option value="main-sidebar" <?php selected( $value, 'main-sidebar' ); ?>><?php esc_html_e( "Main Sidebar", "soledad" ); ?></option>
                        <option value="main-sidebar-left" <?php selected( $value, 'main-sidebar-left' ); ?>><?php esc_html_e( "Main Sidebar Left", "soledad" ); ?></option>
                        <option value="custom-sidebar-1" <?php selected( $value, 'custom-sidebar-1' ); ?>><?php esc_html_e( "Custom Sidebar 1", "soledad" ); ?></option>
                        <option value="custom-sidebar-2" <?php selected( $value, 'custom-sidebar-2' ); ?>><?php esc_html_e( "Custom Sidebar 2", "soledad" ); ?></option>
                        <option value="custom-sidebar-3" <?php selected( $value, 'custom-sidebar-3' ); ?>><?php esc_html_e( "Custom Sidebar 3", "soledad" ); ?></option>
                        <option value="custom-sidebar-4" <?php selected( $value, 'custom-sidebar-4' ); ?>><?php esc_html_e( "Custom Sidebar 4", "soledad" ); ?></option>
                        <option value="custom-sidebar-5" <?php selected( $value, 'custom-sidebar-5' ); ?>><?php esc_html_e( "Custom Sidebar 5", "soledad" ); ?></option>
                        <option value="custom-sidebar-6" <?php selected( $value, 'custom-sidebar-6' ); ?>><?php esc_html_e( "Custom Sidebar 6", "soledad" ); ?></option>
                        <option value="custom-sidebar-7" <?php selected( $value, 'custom-sidebar-7' ); ?>><?php esc_html_e( "Custom Sidebar 7", "soledad" ); ?></option>
                        <option value="custom-sidebar-8" <?php selected( $value, 'custom-sidebar-8' ); ?>><?php esc_html_e( "Custom Sidebar 8", "soledad" ); ?></option>
                        <option value="custom-sidebar-9" <?php selected( $value, 'custom-sidebar-9' ); ?>><?php esc_html_e( "Custom Sidebar 9", "soledad" ); ?></option>
                        <option value="custom-sidebar-10" <?php selected( $value, 'custom-sidebar-10' ); ?>><?php esc_html_e( "Custom Sidebar 10", "soledad" ); ?></option>
						<?php Penci_Custom_Sidebar::get_list_sidebar( $value ); ?>
                    </select>
                </p>
            </div>
        </div>
        <div class="pcmt-control-wrapper">
            <div class="pcmt-title">
                <h2 style="font-weight: 600; font-size: 14px; padding-left: 0;"><?php esc_html_e( 'Custom Sidebar Left for This
                    Posts/Page', 'soledad' ); ?></h2>
                <p class="description"><?php esc_html_e( 'Note: for pages, you can choose to display or hide the sidebar using the "Page with Sidebar" template and customize the sidebar here. if the sidebar you choose here is empty, the sidebar you chose for the page in Customize will be displayed.', 'soledad' ); ?></p>
            </div>
            <div class="pcmt-control">
                <p>
                    <select id="penci_custom_sidebar_left_page_field" name="penci_custom_sidebar_left_page_field">
                        <option value=""><?php esc_html_e( "Default Sidebar( on Customize )", "soledad" ); ?></option>
                        <option value="main-sidebar" <?php selected( $value_left, 'main-sidebar' ); ?>><?php esc_html_e( "Main Sidebar", "soledad" ); ?></option>
                        <option value="main-sidebar-left" <?php selected( $value_left, 'main-sidebar-left' ); ?>><?php esc_html_e( "Main Sidebar Left", "soledad" ); ?></option>
                        <option value="custom-sidebar-1" <?php selected( $value_left, 'custom-sidebar-1' ); ?>><?php esc_html_e( "Custom Sidebar 1", "soledad" ); ?></option>
                        <option value="custom-sidebar-2" <?php selected( $value_left, 'custom-sidebar-2' ); ?>><?php esc_html_e( "Custom Sidebar 2", "soledad" ); ?></option>
                        <option value="custom-sidebar-3" <?php selected( $value_left, 'custom-sidebar-3' ); ?>><?php esc_html_e( "Custom Sidebar 3", "soledad" ); ?></option>
                        <option value="custom-sidebar-4" <?php selected( $value_left, 'custom-sidebar-4' ); ?>><?php esc_html_e( "Custom Sidebar 4", "soledad" ); ?></option>
                        <option value="custom-sidebar-5" <?php selected( $value_left, 'custom-sidebar-5' ); ?>><?php esc_html_e( "Custom Sidebar 5", "soledad" ); ?></option>
                        <option value="custom-sidebar-6" <?php selected( $value_left, 'custom-sidebar-6' ); ?>><?php esc_html_e( "Custom Sidebar 6", "soledad" ); ?></option>
                        <option value="custom-sidebar-7" <?php selected( $value_left, 'custom-sidebar-7' ); ?>><?php esc_html_e( "Custom Sidebar 7", "soledad" ); ?></option>
                        <option value="custom-sidebar-8" <?php selected( $value_left, 'custom-sidebar-8' ); ?>><?php esc_html_e( "Custom Sidebar 8", "soledad" ); ?></option>
                        <option value="custom-sidebar-9" <?php selected( $value_left, 'custom-sidebar-9' ); ?>><?php esc_html_e( "Custom Sidebar 9", "soledad" ); ?></option>
                        <option value="custom-sidebar-10" <?php selected( $value_left, 'custom-sidebar-10' ); ?>><?php esc_html_e( "Custom Sidebar 10", "soledad" ); ?></option>
						<?php Penci_Custom_Sidebar::get_list_sidebar( $value_left ); ?>
                    </select>
                </p>
            </div>
        </div>

		<?php if ( in_array( $post_type, $post_type_allow ) ) { ?>
            <div class="pcmt-control-wrapper">
                <div class="pcmt-title">
                    <h2 style="font-weight: 600; font-size: 14px; padding-left: 0;"><?php esc_html_e( 'Select Single Style for This
                        Post?', 'soledad' ); ?></h2>
                </div>
                <div class="pcmt-control">
                    <p>
                        <select id="penci_single_style" name="penci_single_style">
                            <option value=""><?php esc_html_e( "Default Style( on Customize )", "soledad" ); ?></option>
							<?php for ( $i = 1; $i <= 22; $i ++ ) : ?>
                                <option value="style-<?php echo $i; ?>" <?php selected( $single_style, 'style-' . $i ); ?>>
									<?php esc_html_e( "Style $i", "soledad" ); ?>
                                </option>
							<?php endfor; ?>
                        </select>
                    </p>
                </div>
            </div>
            <div class="pcmt-control-wrapper">
                <div class="pcmt-title">
                    <h2 style="font-weight: 600; font-size: 14px; padding-left: 0;"><?php esc_html_e( 'Custom Aspect Ratio for Featured
                        Image of This Post?', 'soledad' ); ?></h2>
                    <p class="description"><?php esc_html_e( 'The aspect ratio of an element describes the proportional relationship
                        between its width and height, for example, 3:2 (width:height). The default aspect ratio is
                        3:2<br>Please note that this option does not apply when parallax images are enabled or for
                        Single Post Styles 1 & 2.', 'soledad' ); ?></p>
                </div>
                <div class="pcmt-control">
                    <p><input placeholder="<?php esc_html_e( 'Enter the custom aspect ratio here', 'soledad' ); ?>"
                              id="_customize-input-penci_pfeatured_image_ratio" name="penci_pfeatured_image_ratio"
                              type="text" value="<?php echo esc_attr( $pfeatured_image_ratio ); ?>"></p>
                </div>
            </div>
            <div class="pcmt-control-wrapper">
                <div class="pcmt-title">
                    <h2 style="font-weight: 600; font-size: 14px; padding-left: 0;"><?php esc_html_e( 'Enable Parallax Images for This
                        Post?', 'soledad' ); ?></h2>
                    <p class="description"><?php esc_html_e( 'This feature does not apply for Single Style 1 & 2', 'soledad' ); ?></p>
                </div>
                <div class="pcmt-control">
                    <p>
                        <select id="penci_enable_jarallax_single" name="penci_enable_jarallax_single">
                            <option value=""><?php esc_html_e( 'No', 'soledad' ); ?></option>
                            <option value="yes" <?php selected( $enable_parallax, 'yes' ); ?>><?php esc_html_e( 'Yes', 'soledad' ); ?></option>
                        </select>
                    </p>
                </div>
            </div>
            <div class="pcmt-control-wrapper">
                <div class="pcmt-title">
                    <h2 style="font-weight: 600; font-size: 14px; padding-left: 0;"><?php esc_html_e( 'Custom Post Builder Template for
                        This Post', 'soledad' ); ?></h2>
                </div>
                <div class="pcmt-control">
                    <p>
                        <select id="penci_single_builder_layout" name="penci_single_builder_layout">
							<?php foreach ( $single_layout as $single_slug => $single_name ) { ?>
                                <option value="<?php echo $single_slug; ?>" <?php selected( $single_slug, $singlebd_layout ); ?>><?php echo $single_name; ?></option>
							<?php } ?>
                        </select>
                    </p>
                </div>
            </div>
            <div class="pcmt-control-wrapper">
                <div class="pcmt-title">
                    <h2 style="font-weight: 600; font-size: 14px; padding-left: 0;"><?php esc_html_e( 'Custom Header Layout for
                        This Post', 'soledad' ); ?></h2>
                </div>
                <div class="pcmt-control">
                    <p>
                        <select id="penci_header_layout" name="penci_header_layout">
							<?php
							$header_layout_options = array(
								''          => __( 'Default Setting', 'soledad' ),
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
							foreach ( $header_layout_options as $header_slug => $header_name ) { ?>
                                <option value="<?php echo $header_slug; ?>" <?php selected( $header_slug, $headerdf_layout ); ?>><?php echo $header_name; ?></option>
							<?php } ?>
                        </select>
                    </p>
                </div>
            </div>
            <div class="pcmt-control-wrapper">
                <div class="pcmt-title">
                    <h2 style="font-weight: 600; font-size: 14px; padding-left: 0;"><?php esc_html_e( 'Custom Header Builder Template for
                        This Post', 'soledad' ); ?></h2>
                </div>
                <div class="pcmt-control">
                    <p>
                        <select id="penci_header_builder_layout" name="penci_header_builder_layout">
							<?php foreach ( $header_layout as $header_slug => $header_name ) { ?>
                                <option value="<?php echo $header_slug; ?>" <?php selected( $header_slug, $headerbd_layout ); ?>><?php echo $header_name; ?></option>
							<?php } ?>
                        </select>
                    </p>
                </div>
            </div>
            <div class="pcmt-control-wrapper">
                <div class="pcmt-title">
                    <h2 style="font-weight: 600; font-size: 14px; padding-left: 0;"><?php esc_html_e( 'Custom Header Block for
                        This Post', 'soledad' ); ?></h2>
                </div>
                <div class="pcmt-control">
                    <p>
                        <select id="penci_header_block_layout" name="penci_header_block_layout">
							<?php foreach ( $footer_layout as $footer_slug => $footer_name ) { ?>
                                <option value="<?php echo $footer_slug; ?>" <?php selected( $footer_slug, $penci_header_block_layout ); ?>><?php echo $footer_name; ?></option>
							<?php } ?>
                        </select>
                    </p>
                </div>
            </div>
            <div class="pcmt-control-wrapper">
                <div class="pcmt-title">
                    <h2 style="font-weight: 600; font-size: 14px; padding-left: 0;"><?php esc_html_e( 'Custom Footer Builder Template for
                        This Post', 'soledad' ); ?></h2>
                </div>
                <div class="pcmt-control">
                    <p>
                        <select id="penci_footer_builder_layout" name="penci_footer_builder_layout">
							<?php foreach ( $footer_layout as $footer_slug => $footer_name ) { ?>
                                <option value="<?php echo $footer_slug; ?>" <?php selected( $footer_slug, $footerbd_layout ); ?>><?php echo $footer_name; ?></option>
							<?php } ?>
                        </select>
                    </p>
                </div>
            </div>
            <div class="pcmt-control-wrapper">
                <div class="pcmt-title">
                    <h2 style="font-weight: 600; font-size: 14px; padding-left: 0;"><?php esc_html_e( 'Enable Table of Content for This
                        Post', 'soledad' ); ?></h2>
                </div>
                <div class="pcmt-control">
                    <p>
                        <select id="penci_toc_enable" name="penci_toc_enable">
                            <option value=""><?php esc_html_e( 'Default Settings', 'soledad' ); ?></option>
                            <option value="yes" <?php selected( $penci_toc_enable, 'yes' ); ?>><?php esc_html_e( 'Yes', 'soledad' ); ?></option>
                            <option value="no" <?php selected( $penci_toc_enable, 'no' ); ?>><?php esc_html_e( 'No', 'soledad' ); ?></option>
                        </select>
                    </p>
                </div>
            </div>
            <?php
                $post_type = get_theme_mod( 'penci_ha_enabled_post_types', [] );
                if ( in_array( 'post', $post_type ) ) {
            ?>
            <div class="pcmt-control-wrapper">
                <div class="pcmt-title">
                    <h2 style="font-weight: 600; font-size: 14px; padding-left: 0;"><?php esc_html_e( 'Enable Article Feedback for This
                        Post', 'soledad' ); ?></h2>
                </div>
                <div class="pcmt-control">
                    <p>
                        <select id="penci_helpful_enable" name="penci_helpful_enable">
                            <option value=""><?php esc_html_e( 'Default Settings', 'soledad' ); ?></option>
                            <option value="yes" <?php selected( $penci_helpful_enable, 'yes' ); ?>><?php esc_html_e( 'Yes', 'soledad' ); ?></option>
                            <option value="no" <?php selected( $penci_helpful_enable, 'no' ); ?>><?php esc_html_e( 'No', 'soledad' ); ?></option>
                        </select>
                    </p>
                </div>
            </div>
            <?php } ?>

			<?php if ( ! get_theme_mod( 'penci_disable_extra_author' ) ) : ?>

                <div class="pcmt-control-wrapper">
                    <div class="pcmt-title">
                        <h2 style="font-weight: 600; font-size: 14px; padding-left: 0;"><?php esc_html_e( 'Extra Author Action for This
                        Post', 'soledad' ); ?></h2>
                    </div>
                    <div class="pcmt-control" style="flex-direction: column;">
                        <div class="pcmt-child-control" style="width: 100%;">
                            <label for="penci_extra_author"
                                   style="font-weightl:bold;display:block;margin-bottom:10px;"><?php esc_html_e( 'Select
                            Action', 'soledad' ); ?></label>
                            <p class="select-button-type">
                                <select id="penci_extra_author" name="penci_extra_author">
                                    <option <?php selected( $penci_extra_author, 'updated_by' ); ?>
                                            value="updated_by"><?php _e( 'Updated By', 'soledad' ); ?></option>
                                    <option <?php selected( $penci_extra_author, 'reviewed_by' ); ?>
                                            value="reviewed_by"><?php _e( 'Reviewed By', 'soledad' ); ?></option>
                                    <option <?php selected( $penci_extra_author, 'edited_by' ); ?>
                                            value="edited_by"><?php _e( 'Edited By', 'soledad' ); ?></option>
                                    <option <?php selected( $penci_extra_author, 'revised_by' ); ?>
                                            value="revised_by"><?php _e( 'Revised By', 'soledad' ); ?></option>
                                </select>
                            </p>
                        </div>
						<?php
						$user_lists = get_users( [ 'role__not_in' => [ 'subscriber', 'customer' ] ] );
						$total_user = count( $user_lists );
						?>
                        <div class="pcmt-child-control" style="width: 100%;">
                            <label for="penci_extra_author_id"
                                   style="font-weightl:bold;display:block;margin-bottom:10px;"><?php esc_html_e( 'Action
                            By', 'soledad' ); ?></label>
							<?php if ( $total_user < 10 ): ?>
                                <select data-value="<?php echo $penci_extra_author_id; ?>" id="penci_extra_author_id"
                                        name="penci_extra_author_id">
                                    <option value=""><?php _e( '-- Select User --', 'soledad' ); ?></option>
									<?php foreach ( $user_lists as $id => $user ): ?>
                                        <option <?php selected( $penci_extra_author_id, $user->ID ); ?>
                                                value="<?php echo $user->ID; ?>"><?php echo $user->display_name; ?></option>
									<?php endforeach; ?>
                                </select>
							<?php else: ?>
                                <div style="min-width:270px;">
                                    <select class="penci_extra_author_ajax_id" id="penci_extra_author_id"
                                            name="penci_extra_author_id">
                                        <option value=""></option>
										<?php if ( $penci_extra_author_id ): ?>
                                            <option selected="selected"
                                                    value="<?php echo $penci_extra_author_id; ?>"><?php echo get_the_author_meta( 'display_name', $penci_extra_author_id ); ?></option>
										<?php endif; ?>
                                    </select>
                                </div>
							<?php endif; ?>
                        </div>
                    </div>
                </div>

			<?php endif; ?>

            <div class="pcmt-control-wrapper">
                <div class="pcmt-title">
                    <h2 style="font-weight: 600; font-size: 14px; padding-left: 0;"><?php _e( 'Sponsored Post', 'soledad' ); ?></h2>
                </div>
                <div class="pcmt-control" style="flex-direction: column;">
                    <div class="pcmt-child-control" style="width: 100%;">
                        <label for="penci_sponsored_post"
                               style="font-weightl:bold;display:block;margin-bottom:10px;"><?php _e( 'Sponsored
                            Post', 'soledad' ); ?></label>
                        <p class="select-button-type">
                            <select id="penci_sponsored_post" name="penci_sponsored_post">
                                <option <?php selected( $penci_sponsored_post, '' ); ?>
                                        value=""><?php _e( 'Disable', 'soledad' ); ?></option>
                                <option <?php selected( $penci_sponsored_post, 'enable' ); ?>
                                        value="enable"><?php _e( 'Enable', 'soledad' ); ?></option>
                            </select>
                        </p>
                    </div>
                    <div class="pcmt-child-control" style="width: 100%;">
                        <label for="penci_sponsored_logo"
                               style="font-weightl:bold;display:block;margin-bottom:10px;"><?php _e( 'Sponsor
                            Logo', 'soledad' ); ?></label>
                        <div class="form-input-wrapper">
							<?php
							get_template_part( 'inc/templates/upload_form', '', array(
								'id'      => 'penci_sponsored_logo',
								'class'   => '',
								'name'    => 'penci_sponsored_logo',
								'source'  => isset( $penci_sponsored_logo ) ? $penci_sponsored_logo : '',
								'button'  => 'btn-single-image',
								'multi'   => false,
								'maxsize' => apply_filters( 'penci_maxsize_upload_profile_picture', '2mb' )
							) );
							?>
                        </div>
                    </div>
                    <div class="pcmt-child-control" style="width: 100%;">
                        <label for="penci_sponsored_post"
                               style="font-weightl:bold;display:block;margin-bottom:10px;"><?php _e( 'Sponsor
                            URL:', 'soledad' ); ?></label>
                        <input value="<?php echo $penci_sponsored_url; ?>" type="url" name="penci_sponsored_url"
                               id="penci_sponsored_url">
                    </div>
                    <div class="pcmt-child-control" style="width: 100%;">
                        <label for="penci_sponsored_redirect"
                               style="font-weightl:bold;display:block;margin-bottom:10px;"><?php _e( 'Directly Redirect', 'soledad' ); ?></label>
                        <p class="select-button-type">
                            <select id="penci_sponsored_redirect" name="penci_sponsored_redirect">
                                <option <?php selected( $penci_sponsored_redirect, '' ); ?>
                                        value=""><?php _e( 'Disable', 'soledad' ); ?></option>
                                <option <?php selected( $penci_sponsored_redirect, 'enable' ); ?>
                                        value="enable"><?php _e( 'Enable', 'soledad' ); ?></option>
                            </select>
                        </p>
                    </div>
                </div>
            </div>

            <div class="pcmt-control-wrapper">
                <div class="pcmt-title">
                    <h2 style="font-weight: 600; font-size: 14px; padding-left: 0;">Video Preview on Hover
                        Thumbnail</h2>
                </div>
                <div class="pcmt-control" style="flex-direction: column;">
                    <div class="pcmt-child-control" style="width: 100%;">
                        <label for="penci_video_preview"
                               style="font-weightl:bold;display:block;margin-bottom:10px;"><?php _e( 'Enable Video Preview on Hover
                            Thumbnail', 'soledad' ); ?></label>
                        <p class="select-button-type">
                            <select id="penci_video_preview" name="penci_video_preview">
                                <option <?php selected( $penci_video_preview, '' ); ?>
                                        value=""><?php _e( 'Disable', 'soledad' ); ?></option>
                                <option <?php selected( $penci_video_preview, 'enable' ); ?>
                                        value="enable"><?php _e( 'Enable', 'soledad' ); ?></option>
                            </select>
                        </p>
                    </div>
                    <div class="pcmt-child-control" style="width: 100%;">
                        <label for="penci_video_preview_url"
                               style="font-weightl:bold;display:block;margin-bottom:10px;"><?php _e( 'Video URL:', 'soledad' ); ?></label>
                        <input value="<?php echo $penci_video_preview_url; ?>" type="url" name="penci_video_preview_url"
                               id="penci_video_preview_url">
                        <div style="margin-top: 10px;" class="description">
                            <p><?php _e( 'Please enter the full video URL, ensuring it ends with one of the following extensions:
                                .mp4, .mov, .mkv, .webm, and so on. You may also input URLs from YouTube, Vimeo, or
                                Facebook videos.', 'soledad' ); ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="pcmt-control-wrapper">
                <div class="pcmt-title">
                    <h2 style="font-weight: 600; font-size: 14px; padding-left: 0;"><?php _e( 'Disable All Adsense Code on This Post', 'soledad' ); ?></h2>
                </div>
                <div class="pcmt-control" style="flex-direction: column;">
                    <div class="pcmt-child-control" style="width: 100%;">
                        <label for="penci_page_dis_ads"
                               style="font-weightl:bold;display:block;margin-bottom:10px;"><?php _e( 'This option will remove all the ad code you\'ve added to this post.', 'soledad' ); ?></label>
                        <p class="select-button-type">
                            <select id="penci_page_dis_ads" name="penci_page_dis_ads">
                                <option <?php selected( $penci_page_dis_ads, '' ); ?>
                                        value=""><?php _e( 'No, Keep all Ads', 'soledad' ); ?></option>
                                <option <?php selected( $penci_page_dis_ads, 'enable' ); ?>
                                        value="disable"><?php _e( 'Yes, Disable all Ads', 'soledad' ); ?></option>
                            </select>
                        </p>
                    </div>
                </div>
            </div>

            <div class="pcmt-control-wrapper">
                <div class="pcmt-title">
                    <h2 style="font-weight: 600; font-size: 14px; padding-left: 0;"><?php _e( 'Post Sources', 'soledad' ); ?></h2>
                </div>
                <div class="pcmt-control" style="flex-direction: column;">
                    <div class="pcmt-child-control" style="width: 100%;">
                        <label for="penci_post_sources"
                               style="font-weightl:bold;display:block;margin-bottom:10px;"><?php _e( 'Add sources for this post.', 'soledad' ); ?></label>


                        <div class="pc-meta-repeater" id="penci-post-sources-wrapper">
                            <!-- Repeater Item Template (Hidden) -->
                            <div class="pc-meta-repeater-item-content pc-repeater-template" style="display: none;">
                                <div class="pc-meta-repeater-input">
                                    <label><?php _e( 'Name', 'soledad' ); ?></label>
                                    <input type="text" name="" class="source-name" value="">
                                </div>
                                <div class="pc-meta-repeater-input">
                                    <label><?php _e( 'URL', 'soledad' ); ?></label>
                                    <input type="text" name="" class="source-url" value="">
                                </div>
                                <a href="#" class="pc-meta-repeater-item-remove"><?php _e( 'Remove', 'soledad' ); ?></a>
                            </div>

                            <!-- Repeater Items Container -->
                            <div class="pc-meta-repeater-items">
								<?php if ( $penci_post_sources ):
									foreach ( $penci_post_sources as $index => $source ): ?>
                                        <div class="pc-meta-repeater-item-content">
                                            <div class="pc-meta-repeater-input">
                                                <label><?php _e( 'Name', 'soledad' ); ?></label>
                                                <input type="text"
                                                       name="penci_post_sources[<?php echo $index; ?>][name]"
                                                       class="source-name" value="<?php echo $source['name']; ?>">
                                            </div>
                                            <div class="pc-meta-repeater-input">
                                                <label><?php _e( 'URL', 'soledad' ); ?></label>
                                                <input type="text" name="penci_post_sources[<?php echo $index; ?>][url]"
                                                       class="source-url" value="<?php echo $source['url']; ?>">
                                            </div>
                                            <a href="#"
                                               class="pc-meta-repeater-item-remove"><?php _e( 'Remove', 'soledad' ); ?></a>
                                        </div>
									<?php endforeach;
								endif;
								?>
                            </div>

                            <!-- Add Button -->
                            <div class="pc-meta-repeater-item-actions">
                                <button type="button"
                                        class="button pc-meta-repeater-item-add"><?php _e( 'Add new source', 'soledad' ); ?></button>
                            </div>
                        </div>

                        <!-- jQuery Script -->
                        <script>
                          jQuery(document).ready(function ($) {
                            function updateRepeaterInputNames () {
                              $('#penci-post-sources-wrapper .pc-meta-repeater-items .pc-meta-repeater-item-content').
                                each(function (index) {
                                  $(this).find('.source-name').attr('name', 'penci_post_sources[' + index + '][name]')
                                  $(this).find('.source-url').attr('name', 'penci_post_sources[' + index + '][url]')
                                })
                            }

                            // Add new item
                            $('.pc-meta-repeater-item-add').on('click', function (e) {
                              e.preventDefault()
                              var $template = $('.pc-repeater-template').
                                clone().
                                removeClass('pc-repeater-template').
                                show()
                              $('.pc-meta-repeater-items').append($template)
                              updateRepeaterInputNames()
                            })

                            // Remove item
                            $('#penci-post-sources-wrapper').on('click', '.pc-meta-repeater-item-remove', function (e) {
                              e.preventDefault()
                              $(this).closest('.pc-meta-repeater-item-content').remove()
                              updateRepeaterInputNames()
                            })
                          })
                        </script>

                    </div>
                </div>
            </div>

			<?php if ( get_theme_mod( 'penci_speed_remove_css' ) ): ?>
                <div class="pcmt-control-wrapper">
                    <div class="pcmt-title">
                        <h2 style="font-weight: 600; font-size: 14px; padding-left: 0;"><?php _e( 'Create a Separate Critical CSS
                            cache for this Post?', 'soledad' ); ?></h2>
                    </div>
                    <div class="pcmt-control">
                        <p>
                            <select id="penci_post_critical_css" name="penci_post_critical_css">
                                <option value=""><?php _e( 'No', 'soledad' ); ?></option>
                                <option value="yes" <?php selected( $penci_post_critical_css, 'yes' ); ?>><?php _e( 'Yes', 'soledad' ); ?></option>
                            </select>
                        </p>
                    </div>
                </div>
			<?php endif; ?>
		<?php }
	}
}
