<?php

namespace PenciSoledadElementor\Modules\PenciContactForm;

use PenciSoledadElementor\Base\Module_Base;
use Elementor\Plugin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}


class Module extends Module_Base {

	public function get_name() {
		return 'penci-contact-form';
	}

	public function get_widgets() {
		return array( 'PenciContactForm' );
	}

	public function is_valid_captcha( $validate ) {

		if ( isset( $_POST['g-recaptcha-response'] ) && ! empty( $validate['recaptcha_secret_key'] ) ) {
			$request  = wp_remote_get( 'https://www.google.com/recaptcha/api/siteverify?secret=' . $validate['recaptcha_secret_key'] . '&response=' . esc_textarea( $_POST["g-recaptcha-response"] ) );
			$response = wp_remote_retrieve_body( $request );

			$result = json_decode( $response, true );

			if ( isset( $result['success'] ) && $result['success'] == 1 ) {
				// Captcha ok
				return true;
			} else {
				// Captcha failed;
				return false;
			}
		}

		return false;
	}


	public function normalize_email( $email ) {
		/**
		 * Split the email into local part and domain
		 */
		list( $local, $domain ) = explode( '@', $email );

		/**
		 * Remove any text after the plus sign in the local part
		 */
		if ( ( $plusPos = strpos( $local, '+' ) ) !== false ) {
			$local = substr( $local, 0, $plusPos );
		}

		/**
		 * Return the normalized email
		 */
		return $local . '@' . $domain;
	}

	public function are_emails_same( $email1, $email2 ) {
		return $this->normalize_email( $email1 ) === $this->normalize_email( $email2 );
	}

	private function find_element_recursive( $elements, $form_id ) {

		foreach ( $elements as $element ) {
			if ( $form_id === $element['id'] ) {
				return $element;
			}

			if ( ! empty( $element['elements'] ) ) {
				$element = $this->find_element_recursive( $element['elements'], $form_id );

				if ( $element ) {
					return $element;
				}
			}
		}

		return false;
	}

	public function get_widget_settings( $post_id, $widget_id ) {
		if ( ! $post_id || ! $widget_id ) {
			return "Invalid request";
		}

		$elementor = Plugin::$instance;
		$pageMeta  = $elementor->documents->get( $post_id );

		if ( ! $pageMeta ) {
			return "Invalid Post or Page ID";
		}
		$metaData = $pageMeta->get_elements_data();
		if ( ! $metaData ) {
			return "Page page is not under elementor";
		}

		$widget_data = $this->find_element_recursive( $metaData, $widget_id );
		$settings    = [];


		if ( is_array( $widget_data ) ) {
			$widget   = $elementor->elements_manager->create_element_instance( $widget_data );
			$settings = $widget->get_settings();
		}

		return $settings;
	}

	public function contact_form() {

		$email               = get_bloginfo( 'admin_email' );
		$error_empty         = penci_get_setting( 'penci_trans_error_empty' );
		$error_noemail       = penci_get_setting( 'penci_trans_error_noemail' );
		$error_same_as_admin = penci_get_setting( 'penci_trans_error_same_as_admin' );
		$error_spam_email    = penci_get_setting( 'penci_trans_error_spam_email' );
		$result              = penci_get_setting( 'penci_trans_result' );

		if ( $_SERVER['REQUEST_METHOD'] == 'POST' ) {

			if ( ! wp_verify_nonce( $_REQUEST['_wpnonce'], 'simpleContactForm' ) ) {
				$result = esc_html__( 'Security check failed!', 'soledad' );
				echo '<span class="pencif-text-warning">' . esc_html( $result ) . '</span>';
				wp_die();
			}

			$post_id         = sanitize_text_field( $_REQUEST['page_id'] );
			$widget_id       = sanitize_text_field( $_REQUEST['widget_id'] );
			$widget_settings = $this->get_widget_settings( $post_id, $widget_id );

			if ( ! empty( $widget_settings['custom_email_to'] ) ) {
				$email = $widget_settings['custom_email_to'];
			}

			$error = false;

			// this part fetches everything that has been POSTed, sanitizes them and lets us use them as $form_data['subject']
			foreach ( $_POST as $field => $value ) {
				if ( is_email( $value ) ) {
					$value = sanitize_email( $value );
				} else {
					$value = sanitize_textarea_field( $value );
				}

				$form_data[ $field ] = strip_tags( $value );
			}


			foreach ( $form_data as $key => $value ) {
				$value = trim( $value );
				if ( empty( $value ) ) {
					$error  = true;
					$result = $error_empty;
				}
			}

			$success = sprintf( penci_get_setting( 'penci_trans_email_success' ), $form_data['name'] );

			// and if the e-mail is not valid, switch $error to TRUE and set the result text to the shortcode attribute named 'error_noemail'
			if ( ! is_email( $form_data['email'] ) ) {
				$error  = true;
				$result = $error_noemail;
			}

			/**
			 * Stop spamming
			 */
			if ( ! $error ) {
				$admin_email = get_option( 'admin_email' );
				if ( $this->are_emails_same( wp_kses_post( trim( $form_data['email'] ) ), $admin_email ) || $admin_email == wp_kses_post( trim( $form_data['email'] ) ) || $email == wp_kses_post( trim( $form_data['email'] ) ) ) {
					$error  = true;
					$result = $error_same_as_admin;
				} else {
					if ( isset( $widget_settings['contact_form_spam_email'] ) ) {
						$spam_email_list = $widget_settings['contact_form_spam_email'];
						$final_spam_list = explode( ',', $spam_email_list );
						foreach ( $final_spam_list as $spam_email ) {
							if ( trim( $form_data['email'] ) == trim( $spam_email ) ) {
								$error  = true;
								$result = $error_spam_email;
								break;
							}
						}
					}
				}
			}

			/** Recaptcha*/

			if ( isset( $widget_settings['show_recaptcha'] ) && $widget_settings['show_recaptcha'] == 'yes' ) {
				if ( ! empty( $widget_settings['recaptcha_site_key'] ) && ! empty( $widget_settings['recaptcha_secret_key'] ) ) {
					if ( ! $this->is_valid_captcha( [
						'recaptcha_site_key'   => $widget_settings['recaptcha_site_key'],
						'recaptcha_secret_key' => $widget_settings['recaptcha_secret_key'],
					] ) ) {
						$error  = true;
						$result = esc_html__( "reCAPTCHA is invalid!", "soledad" );
					}
				}
			}


			$contact_number  = isset( $form_data['contact'] ) ? esc_attr( $form_data['contact'] ) : '';
			$contact_subject = isset( $form_data['subject'] ) ? esc_attr( $form_data['subject'] ) : '';

			// but if $error is still FALSE, put together the POSTed variables and send the e-mail!
			if ( $error == false ) {
				// get the website's name and puts it in front of the subject
				$email_subject = "[" . get_bloginfo( 'name' ) . "] " . $contact_subject;
				// get the message from the form and add the IP address of the user below it
				$email_message = $this->message_html( $form_data['message'], $form_data['name'], $form_data['email'], $contact_number );
				// set the e-mail headers with the user's name, e-mail address and character encoding
				$headers = "Reply-To: " . $form_data['name'] . " <" . $form_data['email'] . ">\n";
				$headers .= "Content-Type: text/html; charset=UTF-8\n";
				$headers .= "Content-Transfer-Encoding: 8bit\n";
				// send the e-mail with the shortcode attribute named 'email' and the POSTed data
				wp_mail( $email, html_entity_decode( $email_subject ), $email_message, $headers );
				// and set the result text to the shortcode attribute named 'success'
				$result = $success;
				// ...and switch the $sent variable to TRUE
				$sent = true;
			}


			$redirect_url = ( isset( $form_data['redirect-url'] ) && ! empty( $form_data['redirect-url'] ) ) ? esc_url( $form_data['redirect-url'] ) : 'no';
			$is_external  = ( isset( $form_data['is-external'] ) && ! empty( $form_data['is-external'] ) ) ? esc_attr( $form_data['is-external'] ) : 'no';

			$reset_status = ( isset( $form_data['reset-after-submit'] ) && ( $form_data['reset-after-submit'] == 'yes' ) ) ? 'yes' : 'no';

			if ( $error == false ) {
				echo '<div class="pencif-text-notice pencif-text-success" data-resetstatus="' . esc_html( $reset_status ) . '"  data-redirect="' . wp_kses_post( $redirect_url ) . '" data-external="' . esc_attr( $is_external ) . '">' . esc_html( $result ) . '</div>';
				// wp_redirect( $form_data['redirect_url'] );
			} else {
				echo '<div class="pencif-text-notice pencif-text-warning">' . esc_html( $result ) . '</div>';
			}
		}

		die;
	}

	public static function get_client_ip() {
		$server_ip_keys = [
			'HTTP_CLIENT_IP',
			'HTTP_X_FORWARDED_FOR',
			'HTTP_X_FORWARDED',
			'HTTP_X_CLUSTER_CLIENT_IP',
			'HTTP_FORWARDED_FOR',
			'HTTP_FORWARDED',
			'REMOTE_ADDR',
		];

		foreach ( $server_ip_keys as $key ) {
			if ( isset( $_SERVER[ $key ] ) && filter_var( $_SERVER[ $key ], FILTER_VALIDATE_IP ) ) {
				return $_SERVER[ $key ];
			}
		}

		// Fallback local ip.
		return '127.0.0.1';
	}

	public function message_html( $message, $name, $email, $number = '' ) {

		$fullmsg = "<html lang='en-US'><body style='background-color: #f5f5f5; padding: 35px;'>";
		$fullmsg .= "<div style='max-width: 768px; margin: 0 auto; background-color: #fff; padding: 50px 35px;'>";
		$fullmsg .= nl2br( $message );
		$fullmsg .= "<br><br>";
		$fullmsg .= "<b>" . esc_html( $name ) . "<b><br>";
		$fullmsg .= esc_html( $email ) . "<br>";
		$fullmsg .= ( $number ) ? esc_html( $number ) . "<br>" : "";
		$fullmsg .= "<em>IP: " . self::get_client_ip() . "</em>";
		$fullmsg .= "</div>";
		$fullmsg .= "</body></html>";

		return $fullmsg;
	}

	public function __construct() {
		parent::__construct();

		add_action( 'wp_ajax_penci_contact_form', [ $this, 'contact_form' ] );
		add_action( 'wp_ajax_nopriv_penci_contact_form', [ $this, 'contact_form' ] );
	}
}
