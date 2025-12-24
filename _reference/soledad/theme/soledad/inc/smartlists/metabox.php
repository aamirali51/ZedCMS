<?php
function Penci_SmartLists_Custom_Metabox() {
	new Penci_SmartLists_Add_Custom_Metabox_Class();
}

if ( is_admin() ) {
	add_action( 'load-post.php', 'Penci_SmartLists_Custom_Metabox' );
	add_action( 'load-post-new.php', 'Penci_SmartLists_Custom_Metabox' );
}

/**
 * The Class.
 */
class Penci_SmartLists_Add_Custom_Metabox_Class {

	/**
	 * Hook into the appropriate actions when the class is constructed.
	 */
	public function __construct() {
		add_action( 'add_meta_boxes', array( $this, 'add_meta_box' ) );
		add_action( 'save_post', array( $this, 'save' ) );
	}

	/**
	 * Adds the meta box container.
	 */
	public function add_meta_box( $post_type ) {
		$post_types = [ 'post' ];
		if ( in_array( $post_type, $post_types ) ) {
			add_meta_box(
				'penci_smartlists_meta'
				, esc_html__( 'Smart Lists Settings For This Post', 'soledad' )
				, array( $this, 'render_meta_box_content' )
				, $post_type
				, 'advanced'
				, 'default'
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
		if ( ! isset( $_POST['penci_smartlists_custom_box_nonce'] ) ) {
			return $post_id;
		}

		$nonce = $_POST['penci_smartlists_custom_box_nonce'];

		// Verify that the nonce is valid.
		if ( ! wp_verify_nonce( $nonce, 'penci_smartlists_custom_box' ) ) {
			return $post_id;
		}

		// If this is an autosave, our form has not been submitted,
		//     so we don't want to do anything.
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return $post_id;
		}

		// Check the user's permissions.
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return $post_id;
		}

		// Update the meta field.
		if ( isset( $_POST['pcsml_smartlists_enable'] ) ) {
			update_post_meta( $post_id, 'pcsml_smartlists_enable', $_POST['pcsml_smartlists_enable'] );
		}
		if ( isset( $_POST['pcsml_smartlists_style'] ) ) {
			update_post_meta( $post_id, 'pcsml_smartlists_style', $_POST['pcsml_smartlists_style'] );
		}
		if ( isset( $_POST['pcsml_smartlists_h'] ) ) {
			update_post_meta( $post_id, 'pcsml_smartlists_h', $_POST['pcsml_smartlists_h'] );
		}
		if ( isset( $_POST['pcsml_smartlists_order'] ) ) {
			update_post_meta( $post_id, 'pcsml_smartlists_order', $_POST['pcsml_smartlists_order'] );
		}
		if ( isset( $_POST['pcsml_smartlists_spacing'] ) ) {
			update_post_meta( $post_id, 'pcsml_smartlists_spacing', $_POST['pcsml_smartlists_spacing'] );
		}
	}


	/**
	 * Render Meta Box content.
	 *
	 * @param WP_Post $post The post object.
	 */
	public function render_meta_box_content( $post ) {

		// Add an nonce field so we can check for it later.
		wp_nonce_field( 'penci_smartlists_custom_box', 'penci_smartlists_custom_box_nonce' );

		// Use get_post_meta to retrieve an existing value from the database.
		$smartlists_enable  = get_post_meta( $post->ID, 'pcsml_smartlists_enable', true );
		$smartlists_style   = get_post_meta( $post->ID, 'pcsml_smartlists_style', true );
		$smartlists_h       = get_post_meta( $post->ID, 'pcsml_smartlists_h', true );
		$smartlists_order   = get_post_meta( $post->ID, 'pcsml_smartlists_order', true );
		$smartlists_spacing = get_post_meta( $post->ID, 'pcsml_smartlists_spacing', true );

		if ( empty( $smartlists_h ) ) {
			$smartlists_h = 'h3';
		}

		// Display the form, using the current value.
		?>

        <div class="penci-table-meta">
            <h3>Transform your post into a Smart List format.</h3>
            <p>1. You can add custom content after the Smart Lists by using the following shortcode at the end of the last item:
                <strong
                        class="penci-smartlists-shortcode">[penci_end_smartlists]</strong></p>
            <p>2. Go to <strong>Appearance > Customize > Single Posts > Colors</strong> and <strong>Appearance >
                    Customize > Single Posts > Font Sizes</strong> to customize the Smart Lists Colors & Styles.</p>
            <div class="pcmt-control-wrapper">
                <div class="pcmt-title">
                    <label for="pcsml_smartlists_enable" class="penci-format-row">Enable Smart Lists
                        for
                        This Post:</label>
                </div>
                <div class="pcmt-control">
                    <select id="pcsml_smartlists_enable" name="pcsml_smartlists_enable">
                        <option value="" <?php selected( '', $smartlists_enable ); ?>>Disable</option>
                        <option value="yes" <?php selected( 'yes', $smartlists_enable ); ?>>Enable</option>
                    </select>
                </div>
            </div>
            <div class="pcmt-control-wrapper">
                <div class="pcmt-title">
                    <label for="pcsml_smartlists_style" class="penci-format-row">Smart Lists
                        Style:</label>
                    <p>Change the Default Style at <strong>Appearance > Customize > Single Posts > Default Smart Lists
                            Style.</strong></p>
                </div>
                <div class="pcmt-control">
                    <select id="pcsml_smartlists_style" name="pcsml_smartlists_style">
                        <option value="" <?php selected( '', $smartlists_style ); ?>>Default Customizer Settings
                        </option>
                        <option value="1" <?php selected( '1', $smartlists_style ); ?>>Style 1</option>
                        <option value="2" <?php selected( '2', $smartlists_style ); ?>>Style 2</option>
                        <option value="3" <?php selected( '3', $smartlists_style ); ?>>Style 3</option>
                        <option value="4" <?php selected( '4', $smartlists_style ); ?>>Style 4</option>
                        <option value="5" <?php selected( '5', $smartlists_style ); ?>>Style 5</option>
                        <option value="6" <?php selected( '6', $smartlists_style ); ?>>Style 6</option>
                    </select>
                </div>
            </div>
            <div class="pcmt-control-wrapper">
                <div class="pcmt-title">
                    <label for="pcsml_smartlists_h" class="penci-format-row">Smart List Content
                        Break from:</label>
                </div>
                <div class="pcmt-control">
                    <select id="pcsml_smartlists_h" name="pcsml_smartlists_h">
                        <option value="h1" <?php selected( 'h1', $smartlists_h ); ?>>Heading 1</option>
                        <option value="h2" <?php selected( 'h2', $smartlists_h ); ?>>Heading 2</option>
                        <option value="h3" <?php selected( 'h3', $smartlists_h ); ?>>Heading 3</option>
                        <option value="h4" <?php selected( 'h4', $smartlists_h ); ?>>Heading 4</option>
                        <option value="h5" <?php selected( 'h5', $smartlists_h ); ?>>Heading 5</option>
                        <option value="h6" <?php selected( 'h6', $smartlists_h ); ?>>Heading 6</option>
                    </select>
                </div>
            </div>
            <div class="pcmt-control-wrapper">
                <div class="pcmt-title">
                    <label for="pcsml_smartlists_order" class="penci-format-row">Smart List Number
                        Ordered:</label>
                    <p>Apply to <strong>Style 1, Style 2</strong> and <strong>Style 3</strong></p>
                </div>
                <div class="pcmt-control">
                    <select id="pcsml_smartlists_order" name="pcsml_smartlists_order">
                        <option value="desc" <?php selected( 'desc', $smartlists_order ); ?>>Descending</option>
                        <option value="asc" <?php selected( 'asc', $smartlists_order ); ?>>Ascending</option>
                    </select>
                </div>
            </div>
            <div class="pcmt-control-wrapper">
                <div class="pcmt-title">
                    <label for="pcsml_smartlists_spacing" class="penci-format-row">Smart List Spacing
                        Between Items:</label>
                </div>
                <div class="pcmt-control">
                    <input placeholder="For example: 10px" type="text" id="pcsml_smartlists_spacing"
                           name="pcsml_smartlists_spacing" value="<?php echo esc_attr( $smartlists_spacing ); ?>">
                </div>
            </div>
        </div>
		<?php
	}
}
