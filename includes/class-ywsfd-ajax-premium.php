<?php
/**
 * This file belongs to the YIT Plugin Framework.
 *
 * This source file is subject to the GNU GENERAL PUBLIC LICENSE (GPL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-3.0.txt
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

use LinkedIn\LinkedIn;

if ( ! class_exists( 'YWSFD_Ajax_Premium' ) ) {

	/**
	 * Implements AJAX for YWSFD plugin
	 *
	 * @class   YWSFD_Ajax_Premium
	 * @package Yithemes
	 * @since   1.0.0
	 * @author  Your Inspiration Themes
	 *
	 */
	class YWSFD_Ajax_Premium {

		/**
		 * Single instance of the class
		 *
		 * @var \YWSFD_Ajax_Premium
		 * @since 1.0.0
		 */
		protected static $instance;

		/**
		 * Returns single instance of the class
		 *
		 * @return \YWSFD_Ajax_Premium
		 * @since 1.0.0
		 */
		public static function get_instance() {

			if ( is_null( self::$instance ) ) {

				self::$instance = new self( $_REQUEST );

			}

			return self::$instance;
		}

		/**
		 * Constructor
		 *
		 * @since   1.0.0
		 * @return  mixed
		 * @author  Alberto Ruggiero
		 */
		public function __construct() {

			add_action( 'wp_ajax_ywsfd_send_friend_mail', array( $this, 'send_friend_mail' ) );
			add_action( 'wp_ajax_nopriv_ywsfd_send_friend_mail', array( $this, 'send_friend_mail' ) );
			add_action( 'wp_ajax_ywsfd_clear_expired_coupons', array( $this, 'clear_expired_coupons' ) );
			add_action( 'wp_ajax_ywsfd_get_linkedin_url', array( $this, 'get_linkedin_url' ) );
			add_action( 'wp_ajax_nopriv_ywsfd_get_linkedin_url', array( $this, 'get_linkedin_url' ) );
			add_action( 'wp_ajax_ywsfd_send_linkedin', array( $this, 'send_linkedin' ) );
			add_action( 'wp_ajax_nopriv_ywsfd_send_linkedin', array( $this, 'send_linkedin' ) );

		}

		/**
		 * Send mail to a friend
		 *
		 * @since   1.0.0
		 * @return  void
		 * @author  Alberto Ruggiero
		 */
		public function send_friend_mail() {

			try {

				if ( ! apply_filters( 'ywsfd_can_get_coupon', true, $_POST['ywsfd_post_id'] ) ) {
					throw new Exception( __( 'We were unable to process your request, please try again.', 'yith-woocommerce-share-for-discounts' ) );
				}

				if ( empty( $_POST['ywsfd_wpnonce'] ) || ! wp_verify_nonce( $_POST['ywsfd_wpnonce'], 'ywsfd-send_friend_mail' ) ) {
					throw new Exception( __( 'We were unable to process your request, please try again.', 'yith-woocommerce-share-for-discounts' ) );
				}

				$fields = array(
					'friend_email' => array(
						'value' => $_POST['ywsfd_friend_email'],
						'type'  => 'email',
						'label' => __( 'Your friend email', 'yith-woocommerce-share-for-discounts' ),
					),
					'user_email'   => array(
						'value' => $_POST['ywsfd_user_email'],
						'type'  => 'email',
						'label' => __( 'Your email', 'yith-woocommerce-share-for-discounts' ),
					),
					'message'      => array(
						'value' => $_POST['ywsfd_message'],
						'type'  => 'text',
						'label' => __( 'Your message', 'yith-woocommerce-share-for-discounts' ),
					),

				);

				foreach ( $fields as $key => $field ) {

					switch ( $field['type'] ) {
						case "textarea" :
							$fields[ $key ] = ! empty( $field['value'] ) ? wp_strip_all_tags( wp_check_invalid_utf8( stripslashes( $field['value'] ) ) ) : '';
							break;
						default :
							$fields[ $key ] = ! empty( $field['value'] ) ? ( is_array( $field['value'] ) ? array_map( 'wc_clean', $field['value'] ) : wc_clean( $field['value'] ) ) : '';
							break;
					}

					if ( empty( $fields[ $key ] ) ) {
						wc_add_notice( '<strong>' . $field['label'] . '</strong> ' . __( 'is a required field.', 'woocommerce' ), 'error' );
					} else {

						if ( $field['type'] == 'email' ) {

							if ( ! is_email( $fields[ $key ] ) ) {
								wc_add_notice( '<strong>' . $field['label'] . '</strong> ' . __( 'is not a valid email address.', 'woocommerce' ), 'error' );
							}

						}

					}

				}

				$response = array();

				if ( wc_notice_count( 'error' ) == 0 ) {

					$subject = sprintf( __( '%s wants to share something with you on %s site', 'yith-woocommerce-share-for-discounts' ), $fields['user_email'], str_replace( array( 'https://', 'http://' ), '', get_option( 'siteurl' ) ) );

					$message = $fields['message'] . '<br /><br /><a href="' . $_POST['ywsfd_sharing_url'] . '">' . $_POST['ywsfd_sharing_url'] . '</a>';

					$wc_email = WC_Emails::instance();
					$email    = $wc_email->emails['YWSFD_Share_Mail'];


					if ( ! $email->trigger( $fields['friend_email'], $subject, $message, $fields['user_email'] ) ) {
						throw new Exception( __( 'There was an error while sending the email, please try again.', 'yith-woocommerce-share-for-discounts' ) );
					}

					$user_data = YITH_WSFD()->get_user_data();

					$coupon = YITH_WSFD()->create_coupon( $user_data, $_POST['ywsfd_post_id'] );

					WC()->cart->add_discount( $coupon );

					$response['status']   = 'success';
					$response['redirect'] = get_permalink( $_POST['ywsfd_post_id'] );

					if ( is_ajax() ) {
						echo '<!--WC_START-->' . json_encode( $response ) . '<!--WC_END-->';
						exit;
					} else {
						wp_redirect( $response['redirect'] );
						exit;
					}

				}

			} catch ( Exception $e ) {

				if ( ! empty( $e ) ) {
					wc_add_notice( $e->getMessage(), 'error' );
				}

			}

			if ( is_ajax() ) {

				ob_start();
				wc_print_notices();
				$messages = ob_get_clean();

				echo '<!--WC_START-->' . json_encode(
						array(
							'result'   => 'failure',
							'messages' => isset( $messages ) ? $messages : ''
						)
					) . '<!--WC_END-->';

				exit;
			}

		}

		/**
		 * Get a Linkedin URL
		 *
		 * @since   1.3.3
		 * @return  string
		 * @author  Alberto Ruggiero
		 */
		public function get_linkedin_url() {

			try {

				$connection = new LinkedIn( array(
					                            'api_key'      => get_option( 'ywsfd_linkedin_app_id' ),
					                            'api_secret'   => get_option( 'ywsfd_linkedin_app_secret' ),
					                            'callback_url' => YWSFD_URL . 'templates/frontend/linkedin-login.php'
				                            ) );

				$url = $connection->getLoginUrl( array( LinkedIn::SCOPE_WRITE_SHARE ) );

				if ( ! WC()->session->has_session() ) {
					WC()->session->set_customer_session_cookie( true );
				}

				die( json_encode( array( 'success' => $url ) ) );

			} catch ( Exception $ex ) {

				die( json_encode( array( 'error' => $ex->getMessage() ) ) );

			}

		}

		/**
		 * Get send the tweet
		 *
		 * @since   1.3.0
		 * @return  string
		 * @author  Alberto Ruggiero
		 */
		public function send_linkedin() {

			try {

				$connection = new LinkedIn( array(
					                            'api_key'      => get_option( 'ywsfd_linkedin_app_id' ),
					                            'api_secret'   => get_option( 'ywsfd_linkedin_app_secret' ),
					                            'callback_url' => YWSFD_URL . 'templates/frontend/linkedin-login.php'
				                            ) );

				$token         = $connection->getAccessToken( $_POST['code'] );
				$token_expires = $connection->getAccessTokenExpiration();

				$response = $connection->post( '/people/~/shares', array(
					'content'    => array(
						'submitted-url' => $_POST['sharing_url'],
					),
					'comment'    => wc_clean( $_POST['message'] ),
					'visibility' => array( 'code' => 'anyone' )
				) );

				if ( isset( $response['updateKey'] ) ) {

					echo '<!--WC_START-->' . json_encode( array( 'status' => 'success' ) ) . '<!--WC_END-->';
					exit;

				} else {

					wc_add_notice( __( 'An error occurred', 'yith-woocommerce-share-for-discounts' ), 'error' );

				}

			} catch ( Exception $e ) {

				if ( ! empty( $e ) ) {
					wc_add_notice( $e->getMessage(), 'error' );
				}

			}

			ob_start();
			wc_print_notices();
			$messages = ob_get_clean();

			$result = array(
				'status'   => 'failure',
				'messages' => isset( $messages ) ? $messages : ''
			);

			echo '<!--WC_START-->' . json_encode( $result ) . '<!--WC_END-->';

			exit;

		}

		/**
		 * Clear expired coupons manually
		 *
		 * @since   1.0.8
		 * @return  void
		 * @author  Alberto Ruggiero
		 */
		public function clear_expired_coupons() {

			$result = array(
				'success' => true,
				'message' => ''
			);

			try {

				$count = YITH_WSFD()->trash_expired_coupons( true );

				$result['message'] = sprintf( _n( 'Cancellation completed. %d coupon deleted.', 'Cancellation completed. %d coupons deleted.', $count, 'yith-woocommerce-share-for-discounts' ), $count );

			} catch ( Exception $e ) {

				$result['success'] = false;
				$result['message'] = sprintf( __( 'An error has occurred: %s', 'yith-woocommerce-share-for-discounts' ), $e->getMessage() );

			}

			wp_send_json( $result );

		}

	}

	/**
	 * Unique access to instance of YWSFD_Ajax_Premium class
	 *
	 * @return \YWSFD_Ajax_Premium
	 */
	function YWSFD_Ajax_Premium() {

		return YWSFD_Ajax_Premium::get_instance();

	}

}