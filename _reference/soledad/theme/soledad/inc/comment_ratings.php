<?php

class Penci_Comment_Ratings {

	/**
	 * Instance of this class.
	 * @since    1.0.0
	 * @var      object
	 */
	protected static $instance = null;

	protected $plugin_baseurl = null;

	protected static $config;
	protected static $localized = array();

	/**
	 * Initialize the plugin by setting localization, filters, and administration functions.
	 * @since     1.0.0
	 */
	protected function __construct() {

		if ( ! get_theme_mod( 'penci_post_comments_ratings' ) ) {
			return false;
		}

		// Load public-facing style sheet and JavaScript.
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

		add_action( 'comment_form_top', array( $this, 'output_review_fields' ) );

		add_action( 'comment_form_defaults', array( $this, 'filter_submit_comment_button' ) );


		add_action( 'comment_post', array( $this, 'save_comment' ) );

		// now the admin part
		add_filter( 'comment_edit_redirect', array( $this, 'save_comment_backend' ), 10, 2 );
		add_action( 'add_meta_boxes', array( $this, 'add_custom_backend_box' ) );

		// add admin columns
		add_filter( 'manage_edit-comments_columns', array( $this, 'admin_col' ), 10, 100 );
		add_action( 'manage_comments_custom_column', array( $this, 'admin_col_content' ), 10, 2 );

		add_filter( 'comment_class', array( $this, 'review_comment_class' ), 10, 6 );

	}

	/**
	 * Return an instance of this class.
	 * @return    object    A single instance of this class.
	 * @since     1.0.0
	 */
	public static function get_instance() {
		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	public function review_comment_class( $classes, $css_class, $comment_id, $comment, $post ) {
		$rating            = get_comment_meta( $comment_id, 'pccm_rating', true );
		$pccm_rating_title = get_comment_meta( $comment_id, 'pccm_rating_title', true );

		if ( $rating && $pccm_rating_title ) {
			$classes[] = 'penci-rating-comment';
		}

		return $classes;
	}

	public function moveElement( &$array, $a, $b ) {
		$out = array_splice( $array, $a, 1 );
		array_splice( $array, $b, 0, $out );
	}

	public function admin_col( $columns ) {
		$columns['penci_comment_ratings'] = __( 'Comment Ratings', 'soledad' );

		$n_columns = array();
		$move      = 'penci_comment_ratings'; // what to move
		$before    = 'response'; // move before this
		foreach ( $columns as $key => $value ) {
			if ( $key == $before ) {
				$n_columns[ $move ] = $move;
			}
			$n_columns[ $key ] = $value;
		}

		return $n_columns;

	}

	function admin_col_content( $column, $comment_ID ) {
		//get the rating
		$rating_content = '';
		$rating         = get_comment_meta( $comment_ID, 'pccm_rating', true );
		//get the rating title
		$pccm_rating_title = get_comment_meta( $comment_ID, 'pccm_rating_title', true );

		//add the rating stars to the comment
		if ( ! empty( $rating ) ) {
			$rating_content .= '<div class="review_rated" style="color:#ffb900">';
			for ( $x = 1; $x <= 5; $x ++ ) {
				$show           = $x <= $rating ? 'filled' : 'empty';
				$rating_content .= '<span class="dashicons dashicons-star-' . $show . '"></span>';
			}
			$rating_content .= '</div>';
		}

		//add the rating title
		if ( ! empty( $pccm_rating_title ) ) {
			$rating_content .= '<span class="pccm_rating_title">' . $pccm_rating_title . '</span>';
		}
		switch ( $column ) :
			case 'penci_comment_ratings' :
			{
				echo $rating_content; // or echo $comment->comment_ID;
				break;
			}
		endswitch;
	}

	protected function set_rating_values() {
		$ratings = array(
			penci_get_setting( 'penci_review_terrible' ),
			penci_get_setting( 'penci_review_poor' ),
			penci_get_setting( 'penci_review_average' ),
			penci_get_setting( 'penci_review_verygood' ),
			penci_get_setting( 'penci_review_exceptional' ),
		);

		return $ratings;
	}

	/**
	 * Register and enqueues public-facing JavaScript files.
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		// add assets here
		wp_enqueue_script( 'jquery-raty', PENCI_SOLEDAD_URL . '/js/jquery.raty.js', array( 'jquery' ), PENCI_SOLEDAD_VERSION, true );
		wp_enqueue_script( 'reviews-scripts', PENCI_SOLEDAD_URL . '/js/reviews.js', array( 'jquery-raty' ), PENCI_SOLEDAD_VERSION, true );

		wp_localize_script( 'reviews-scripts', 'penci_reviews', array(
				'hints' => $this->set_rating_values(),
			)
		);
	}

	function save_comment( $commentID ) {
		//some sanity check
		if ( ! is_numeric( $commentID ) ) {
			return;
		}
		// Save the rating
		if ( isset( $_POST['pccm_rating'] ) && is_numeric( $_POST['pccm_rating'] ) ) {
			update_comment_meta( $commentID, 'pccm_rating', $_POST['pccm_rating'], true );
		}

		//Save the rating title
		if ( isset( $_POST['pccm_rating_title'] ) && is_string( $_POST['pccm_rating_title'] ) ) {
			update_comment_meta( $commentID, 'pccm_rating_title', sanitize_text_field( $_POST['pccm_rating_title'] ), true );
		}
	}

	function output_review_fields() {
		?>

        <div id="add_comment_rating_wrap">
            <label for="add_post_rating"><?php echo penci_get_setting( 'penci_overall_rating' ); ?></label>

            <div id="add_post_rating"
                 data-pccm_rating="<?php echo get_theme_mod( 'penci_post_comments_ratings', 4 ); ?>"
                 data-assets_path="<?php echo esc_attr( PENCI_SOLEDAD_URL . '/images' ); ?>"></div>
        </div>
        <div class="review-title-form">
            <label for="pccm_rating_title"><?php penci_get_setting( 'penci_summarize' ); ?></label>
            <input type='text' id='pccm_rating_title' name='pccm_rating_title' value=""
                   placeholder="<?php echo penci_get_setting( 'penci_summarize' ); ?>"
                   size='25'/>
        </div>

		<?php
	}


	function filter_submit_comment_button( $args ) {
		$label                = penci_get_setting( 'penci_review_submit' );
		$args['label_submit'] = $label;

		return $args;
	}

	/**
	 * Save Custom Comment Field
	 * This hook deals with the redirect after saving, we are only taking advantage of it
	 */
	function save_comment_backend( $location, $comment_id ) {
		// Not allowed, return regular value without updating meta
		if ( ! wp_verify_nonce( $_POST['noncename_wpse_82317'], plugin_basename( __FILE__ ) )
		     && ! isset( $_POST['pccm_rating_title'] ) && ! isset( $_POST['pccm_rating'] ) ) {
			return $location;
		}

		// Update meta
		update_comment_meta(
			$comment_id,
			'pccm_rating_title',
			sanitize_text_field( $_POST['pccm_rating_title'] )
		);

		// Save rating to the comment if we actually have a numeric value here - just to stay safe
		if ( intval( $_POST['pccm_rating'] ) ) {
			update_comment_meta( $comment_id, 'pccm_rating', intval( $_POST['pccm_rating'] ) );
		}

		// Return regular value after updating
		return $location;
	}

	/**
	 * Add Comment meta box
	 */
	function add_custom_backend_box() {
		add_meta_box(
			'section_id_wpse_82317',
			esc_html__( 'Review Fields', 'soledad' ),
			array( $this, 'inner_custom_backend_box' ),
			'comment',
			'normal'
		);
	}

	/**
	 * Render meta box with Custom Field
	 */
	function inner_custom_backend_box( $comment ) {
		//some sanity check
		if ( empty( $comment ) ) {
			return;
		}

		// Use nonce for verification
		wp_nonce_field( plugin_basename( __FILE__ ), 'noncename_wpse_82317' );

		$pccm_rating_title = get_comment_meta( $comment->comment_ID, 'pccm_rating_title', true );
		$current_rating    = get_comment_meta( $comment->comment_ID, 'pccm_rating', true ); ?>

        <fieldset>
            <label for="pccm_rating_title"><?php echo penci_get_setting( 'penci_review_title' ); ?></label>
            <input type='text' id='pccm_rating_title' name='pccm_rating_title'
                   value="<?php echo esc_attr( $pccm_rating_title ) ?>" size='25'/>
        </fieldset>

		<?php // if there is a value, display it
		$data = '';
		if ( ! empty( $current_rating ) ) {
			$data .= 'data-pccm_rating="' . $current_rating . '"';
		} ?>

        <fieldset id="add_comment_rating_wrap">
			<?php ?>
            <label for="add_post_rating"><?php echo penci_get_setting( 'penci_review_ratings' ); ?></label>

            <div id="add_post_rating" <?php echo $data; ?>
                 data-assets_path="<?php echo $this->plugin_baseurl . '/images'; ?>"></div>
        </fieldset>

	<?php }

	function get_average_rating( $post_id = null, $decimals = 2 ) {

		if ( empty( $post_id ) ) {
			$post_id = get_the_ID();
		}

		$comments = get_comments( array(
			'post_id'  => $post_id,
			'meta_key' => 'pccm_rating',
			'status'   => 'approve'
		) );

		if ( empty( $comments ) ) {
			return false;
		}

		$total = 0;
		foreach ( $comments as $comment ) {
			$current_rating = get_comment_meta( $comment->comment_ID, 'pccm_rating', true );
			$total          = $total + (double) $current_rating;
		}

		$average = $total / count( $comments );

		return number_format( $average, $decimals );
	}
}

Penci_Comment_Ratings::get_instance();