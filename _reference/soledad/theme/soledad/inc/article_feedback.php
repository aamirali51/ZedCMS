<?php

class Penci_Article_FeedBack {
    /**
     * @var array List of supported post types
     */
    private $post_types = [];

    /**
     * Constructor
     */
    public function __construct() {
        // Read selected post types
        $this->post_types = get_theme_mod( 'penci_ha_enabled_post_types', [] );

        if ( empty( $this->post_types ) || is_front_page() || is_home() || is_archive() || is_search() || is_404() ) {
            return;
        }

        add_action( 'init', [ $this, 'post_type_support' ] );
        add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_assets' ] );
        add_filter( 'the_content', [ $this, 'render_feedback' ] );
        add_action( 'rest_api_init', [ $this, 'register_routes' ] );
        add_action( 'add_meta_boxes', array( $this, 'add_feedback_meta_box' ) );
        add_action( 'save_post', array( $this, 'save_meta' ) );
    }

    public function add_feedback_meta_box( $post_type ) {

        $post_types = array_diff( $this->post_types, [ 'post', 'page' ] ); // Exclude 'post' and 'page' if present

		add_meta_box(
            'penci_helpful_meta',
            esc_html__( 'Article Feedback', 'soledad' ),
            array( $this, 'render_meta_box_content' ),
            $post_types,
            'advanced',
            'default'
        );
	}

    public function save_meta( $post_id ) {
        if ( ! isset( $_POST['penci_article_feedback'] ) ) {
			return $post_id;
		}

		$nonce = $_POST['penci_article_feedback'];

		// Verify that the nonce is valid.
		if ( ! wp_verify_nonce( $nonce, 'penci_article_feedback' ) ) {
			return $post_id;
		}

        if ( isset( $_POST['penci_helpful_enable'] ) ) {
			update_post_meta( $post_id, 'penci_helpful_enable', $_POST['penci_helpful_enable'] );
		}

		// If this is an autosave, our form has not been submitted,
		//     so we don't want to do anything.
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return $post_id;
		}
    }

    public function render_meta_box_content( $post ) {
        wp_nonce_field( 'penci_article_feedback', 'penci_article_feedback' );
        $penci_helpful_enable = get_post_meta( $post->ID, 'penci_helpful_enable', true );
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
        <?php
    }

    /**
     * Register REST API routes
     */
    public function register_routes() {
        register_rest_route( 'article_feedback/v1', '/feedback/(?P<post_id>\d+)', [
            'methods'             => 'POST',
            'callback'            => [ $this, 'handle_feedback' ],
            'permission_callback' => '__return_true',
        ] );
    }

    /**
     * Enqueue assets
     */
    public function enqueue_assets() {
        if ( is_singular( $this->post_types ) ) {
            wp_enqueue_script(
                'penci-article-feedback',
                PENCI_SOLEDAD_URL . '/js/feedback.js',
                [ 'jquery' ],
                PENCI_SOLEDAD_VERSION,
                true
            );
        }
    }

    /**
     * Render feedback UI
     */
    public function render_feedback( $content ) {
        $feedback_content = '';

        $post_id       = get_the_ID();
        $meta_setting  = get_post_meta( $post_id, 'penci_helpful_enable', true );
        $show_feedback = $meta_setting === 'yes' ? true : ( $meta_setting === 'no' ? false : is_singular( $this->post_types ) );

        if ( $show_feedback ) {
            $post_id = get_the_ID();

            $class = 'penci-afb-disabled';

            $thank_text = $question_text = esc_attr( $this->get_text( 'thank' ) );
            $yes_text = esc_html( $this->get_text( 'yes' ) );
            $no_text = esc_html( $this->get_text( 'no' ) );

            // Don't show feedback if already voted
            if ( ! isset( $_COOKIE[ "penci_afb_" . $post_id ] ) ) {
                $class = 'penci-afb-enable';
                $question_text = esc_html( $this->get_text( 'question' ) );
            }

            $yes_count = '<i class="penci-afb-count-yes">' . (int) get_post_meta( $post_id, 'penci_feedback_yes', true ) . '</i>';
            $no_count = '<i class="penci-afb-count-no">' . (int) get_post_meta( $post_id, 'penci_feedback_no', true ) . '</i>';

            $yes_icon = '<svg width="800px" height="800px" viewBox="0 0 1920 1920" xmlns="http://www.w3.org/2000/svg"><path d="M1863.059 1016.47c0-124.574-101.308-225.882-225.883-225.882H1203.37c-19.651 0-37.044-9.374-47.66-25.863-10.391-16.15-11.86-35.577-3.84-53.196 54.776-121.073 94.87-247.115 119.378-374.513 15.925-83.576-5.873-169.072-60.085-234.578C1157.29 37.384 1078.005 0 993.751 0H846.588v56.47c0 254.457-155.068 473.224-285.063 612.029-72.734 77.477-176.98 122.09-285.967 122.09H56v734.117C56 1742.682 233.318 1920 451.294 1920h960c124.574 0 225.882-101.308 225.882-225.882 0-46.42-14.117-89.676-38.174-125.59 87.869-30.947 151.116-114.862 151.116-213.234 0-46.419-14.118-89.675-38.174-125.59 87.868-30.946 151.115-114.862 151.115-213.233" fill-rule="evenodd"/></svg>';

            $feedback_content .= '<div class="penci-article-feedback '.$class.'" id="penci-article-feedback" data-post-id="' . esc_attr( $post_id ) . '" data-thank-text="' . $thank_text . '">';
            $feedback_content .= '<div class="penci-afb-title">' . $question_text . '</div>';
            $feedback_content .= '<div class="penci-afb-yn">';
            $feedback_content .= '<span class="penci-afbl" data-value="1">' . $yes_icon . $yes_text . $yes_count . '</span>';
            $feedback_content .= '<span class="penci-afbdl" data-value="0">' . $no_text . $yes_icon . $no_count . '</span>';
            $feedback_content .= '</div></div>';
            
        }

        return $content . $feedback_content;
    }

    /**
     * Handle feedback submission via REST API
     *
     * @param WP_REST_Request $request
     * @return WP_REST_Response
     */
    public function handle_feedback( $request ) {
        $post_id = $request->get_param( 'post_id' );
        $value   = $request->get_param( 'value' );

        // Validate post ID
        if ( ! get_post( $post_id ) || ! in_array( get_post_type( $post_id ), $this->post_types, true ) ) {
            return new WP_REST_Response( [ 'error' => 'Invalid post ID' ], 400 );
        }

        // Validate value
        if ( ! in_array( $value, [ 0, 1 ], true ) ) {
            return new WP_REST_Response( [ 'error' => 'Invalid feedback value' ], 400 );
        }

        $meta_key   = $value ? 'penci_feedback_yes' : 'penci_feedback_no';
        $meta_value = (int) get_post_meta( $post_id, $meta_key, true ) + 1;

        update_post_meta( $post_id, $meta_key, $meta_value );

        return new WP_REST_Response( [
            'success' => true,
            'yes'     => $meta_key === 'penci_feedback_yes' ? $meta_value : (int) get_post_meta( $post_id, 'penci_feedback_yes', true ),
            'no'      => $meta_key === 'penci_feedback_no'  ? $meta_value : (int) get_post_meta( $post_id, 'penci_feedback_no', true ),
        ], 200 );
    }

    /**
     * Get localized text for feedback
     *
     * @param string $key
     * @return string
     */
    private function get_text( $key ) {
        $texts = [
            'thank'    => __( 'Thank you for your feedback!', 'soledad' ),
            'question' => __( 'Was this article helpful?', 'soledad' ),
            'yes'      => __( 'Yes', 'soledad' ),
            'no'       => __( 'No', 'soledad' ),
        ];

		return get_theme_mod( 'penci_ha_' . $key . '_text' ) ? get_theme_mod( 'penci_ha_' . $key . '_text' ) : $texts[ $key ];
    }

    public function penci_feedback_column( $column, $post_id ) {

        // Variables
        $positive_value = intval( get_post_meta( $post_id, 'penci_feedback_yes', true ) );
        $negative_value = intval( get_post_meta( $post_id, 'penci_feedback_no', true ) );

        // Total
        $total = $positive_value + $negative_value;

        if ( $total > 0 ) {
            $ratio = intval( $positive_value * 100 / $total );
        }

        // helpful ration
        if ( $column == 'penci_feedback' ) {

            if ( $total > 0 ) {
                echo "<strong style='display:block;'>%" . $ratio . "</strong>";
                echo "<em style='display:block;color:rgba(0,0,0,.55);'>" . $positive_value . " helpful" . " / " . $negative_value . " not helpful</em>";
                echo "<div style='margin-top: 5px;width:100%;max-width:100px;background:rgba(0,0,0,.12);line-height:0px;font-size:0px;border-radius:3px;'><span style='width:" . $ratio . "%;background:rgba(0,0,0,.55);height:4px;display:inline-block;border-radius:3px;'></span></div>";
            } else {
                echo "—";
            }

        }

    }

    public function column_heading( $columns ) {

        // Add column
        $columns['penci_feedback'] = __( 'Feedback', 'soledad' );

        return $columns;
    }

    // Adds post type support
    public function post_type_support() {

        // Get selected post types
        $selected_post_types = $this->post_types;

        // loop selected type
        if ( ! empty( $selected_post_types ) ) {

            foreach ( $selected_post_types as $selected_type ) {

                add_filter( 'manage_' . $selected_type . '_posts_columns', [ $this, 'column_heading' ] );
                add_action( 'manage_' . $selected_type . '_posts_custom_column', [
                    $this,
                    'penci_feedback_column'
                ], 10, 2 );

            }

        }

    }
}

new Penci_Article_FeedBack();