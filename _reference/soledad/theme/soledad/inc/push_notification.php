<?php

class Penci_Push_Notification {


	/**
	 * Constructor
	 */
	public function __construct() {

		if ( ! defined( 'ONESIGNAL_PLUGIN_URL' ) ) {
			return;
		}

		if ( ! get_theme_mod( 'penci_pushnt_enable' ) ) {
			return;
		}

		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_assets' ] );
		add_filter( 'the_content', [ $this, 'render_ui' ] );
		add_action( 'admin_init', array( $this, 'post_category_filter' ) );
		add_action( 'penci_archive_after_posts', array( $this, 'render_single_category_form' ) );
	}

	public function enqueue_assets() {
		wp_enqueue_script(
			'penci-push-notification',
			PENCI_SOLEDAD_URL . '/js/notification.js',
			[ 'jquery' ],
			PENCI_SOLEDAD_VERSION,
			true
		);
	}

	public function post_category_filter() {
		if ( current_user_can( 'edit_posts' ) ) {
			add_filter( 'onesignal_send_notification', array( $this, 'send_notification_filter' ), 10, 4 );
		}
	}

	function send_notification_filter( $fields, $new_status, $old_status, $post ) {
		if ( $post->post_type != 'post' ) {
			return false;
		}

		$filters    = array();
		$categories = $this->get_post_category( $post->ID );

		for ( $i = 0; $i < sizeof( $categories ); $i ++ ) {
			$filters[] = array(
				'field'    => 'tag',
				'key'      => $categories[ $i ],
				'relation' => '=',
				'value'    => $categories[ $i ],
			);

			if ( $i < ( sizeof( $categories ) - 1 ) ) {
				$filters[] = array(
					'operator' => 'OR',
				);
			}
		}

		if ( sizeof( $filters ) > 0 ) {
			$filters[] = array(
				'operator' => 'OR',
			);
		}

		$filters[] = array(
			'field'    => 'tag',
			'key'      => 'all',
			'relation' => '=',
			'value'    => 'all',
		);

		$fields['included_segments'] = array();
		$fields['filters']           = $filters;

		return $fields;
	}

	public function get_post_category( $post_id ) {
		$result = array();

		$categories = wp_get_post_categories( $post_id );

		foreach ( $categories as $category ) {
			$category = get_term_by( 'id', $category, 'category' );
			$result[] = $category->slug;
		}

		return $result;
	}

	public function render_ui( $content ) {

		if ( ! is_single() ) {
			return $content;
		}

		$push_form = '';

		$penci_pushnt_sdesc = get_theme_mod( 'penci_pushnt_sdesc', 'Get real time update about this post category directly on your device, subscribe now.' );
		$btn_subscribe      = get_theme_mod( 'penci_pushnt_btntext', 'Subscribe' );
		$btn_unsubscribe    = get_theme_mod( 'penci_pushnt_unbtntext', 'Unsubscribe' );
		$btn_processing     = get_theme_mod( 'penci_pushnt_probtntext', 'Processing ...' );
		$post_category      = $this->get_post_category( get_the_ID() );

		$push_form .= '
                        <div class="penci_push_notification">
                            <div class="penci_push_notification_content"><p>' . esc_html( $penci_pushnt_sdesc ) . '</p></div>
                            <div class="penci_push_notification_button">
                                <input type="hidden" name="post-category" value="' . implode( ',', $post_category ) . '">
                                    <input type="hidden" name="button-subscribe" value="' . $btn_subscribe . '">
                                    <input type="hidden" name="button-unsubscribe" value="' . $btn_unsubscribe . '">
                                    <input type="hidden" name="button-processing" value="' . $btn_processing . '">
                                    <a data-action="subscribe" class="button" data-type="general" href="#">
                                        <i class="fa fa-bell"></i>
                                       ' . esc_html( $btn_subscribe ) . '
                                    </a>
                                </div>
                        </div>';


		return $content . $push_form;
	}

	public function render_single_category_form() {

		$category = get_queried_object();

		$penci_pushnt_sdesc = get_theme_mod( 'penci_pushnt_sdesc', 'Get real time update about this post category directly on your device, subscribe now.' );
		$btn_subscribe      = get_theme_mod( 'penci_pushnt_btntext', 'Subscribe' );
		$btn_unsubscribe    = get_theme_mod( 'penci_pushnt_unbtntext', 'Unsubscribe' );
		$btn_processing     = get_theme_mod( 'penci_pushnt_probtntext', 'Processing ...' );

		$output = '<div class="penci_push_notification"><div class="penci_push_notification_content">
                        <p>' . esc_html( $penci_pushnt_sdesc ) . '</p>
                        <div class="penci_push_notification_button">
                            <input type="hidden" name="post-category" value="' . esc_attr( $category->slug ) . '">
                            <input type="hidden" name="button-subscribe" value="' . esc_attr( $btn_subscribe ) . '">
                            <input type="hidden" name="button-unsubscribe" value="' . esc_attr( $btn_unsubscribe ) . '">
                            <input type="hidden" name="button-processing" value="' . esc_attr( $btn_processing ) . '">
                            <a data-action="unsubscribe" data-type="category" class="button" href="#">
                                <i class="fa fa-bell"></i>
                                ' . esc_html( $btn_unsubscribe ) . '
                            </a>
                        </div>
                    </div></div>';

		echo $output;
	}
}

new Penci_Push_Notification();