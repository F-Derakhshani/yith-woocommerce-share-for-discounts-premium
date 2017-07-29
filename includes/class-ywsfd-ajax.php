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

use Abraham\TwitterOAuth\TwitterOAuth;

if ( ! class_exists( 'YWSFD_Ajax' ) ) {

	/**
	 * Implements AJAX for YWSFD plugin
	 *
	 * @class   YWSFD_Ajax
	 * @package Yithemes
	 * @since   1.0.0
	 * @author  Your Inspiration Themes
	 *
	 */
	class YWSFD_Ajax {

		/**
		 * Single instance of the class
		 *
		 * @var \YWSFD_Ajax
		 * @since 1.0.0
		 */
		protected static $instance;

		/**
		 * Returns single instance of the class
		 *
		 * @return \YWSFD_Ajax
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

			add_action( 'wp_ajax_ywsfd_get_coupon', array( $this, 'get_coupon' ) );
			add_action( 'wp_ajax_nopriv_ywsfd_get_coupon', array( $this, 'get_coupon' ) );
			add_action( 'wp_ajax_ywsfd_get_twitter_url', array( $this, 'get_twitter_url' ) );
			add_action( 'wp_ajax_nopriv_ywsfd_get_twitter_url', array( $this, 'get_twitter_url' ) );
			add_action( 'wp_ajax_ywsfd_send_tweet', array( $this, 'send_tweet' ) );
			add_action( 'wp_ajax_nopriv_ywsfd_send_tweet', array( $this, 'send_tweet' ) );

		}

		/**
		 * Get a coupon
		 *
		 * @since   1.0.0
		 * @return  void
		 * @author  Alberto Ruggiero
		 */
		public function get_coupon() {

			if ( ! WC()->session->has_session() ) {
				WC()->session->set_customer_session_cookie( true );
			}

			try {

				if ( ! apply_filters( 'ywsfd_can_get_coupon', true, $_POST['post_id'] ) ) {
					throw new Exception( __( 'We were unable to process your request, please try again.', 'yith-woocommerce-share-for-discounts' ) );
				}

				$response  = array();
				$user_data = YITH_WSFD()->get_user_data();

				$coupon = YITH_WSFD()->create_coupon( $user_data, $_POST['post_id'] );

				WC()->cart->add_discount( $coupon );

				$response['status']   = 'success';
				$response['redirect'] = get_permalink( $_POST['post_id'] );

				if ( is_ajax() ) {

					echo '<!--WC_START-->' . json_encode( $response ) . '<!--WC_END-->';
					exit;

				} else {

					wp_redirect( $response['redirect'] );
					exit;

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
							'status'   => 'failure',
							'messages' => isset( $messages ) ? $messages : ''
						)
					) . '<!--WC_END-->';

				exit;

			}

		}

		/**
		 * Get a Twitter URL
		 *
		 * @since   1.3.0
		 * @return  string
		 * @author  Alberto Ruggiero
		 */
		public function get_twitter_url() {

			try {

				$connection = new TwitterOAuth( get_option( 'ywsfd_twitter_app_id' ), get_option( 'ywsfd_twitter_app_secret' ) );

				$request_token = $connection->oauth( 'oauth/request_token', array( 'oauth_callback' => YWSFD_URL . 'templates/frontend/twitter-login.php' ) );

				if ( ! WC()->session->has_session() ) {
					WC()->session->set_customer_session_cookie( true );
				}

				WC()->session->set( 'oauth_token', $request_token['oauth_token'] );
				WC()->session->set( 'oauth_token_secret', $request_token['oauth_token_secret'] );

				$url = $connection->url( 'oauth/authenticate', array( 'oauth_token' => $request_token['oauth_token'] ) );

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
		public function send_tweet() {

			try {

				$request_token = array(
					'oauth_token'        => WC()->session->get( 'oauth_token' ),
					'oauth_token_secret' => WC()->session->get( 'oauth_token_secret' )
				);

				if ( isset( $_POST['oauth_token'] ) && $request_token['oauth_token'] !== $_REQUEST['oauth_token'] ) {

					wc_add_notice( __( 'An error occurred', 'yith-woocommerce-share-for-discounts' ), 'error' );

				}

				$connection = new TwitterOAuth( get_option( 'ywsfd_twitter_app_id' ), get_option( 'ywsfd_twitter_app_secret' ), $request_token['oauth_token'], $request_token['oauth_token_secret'] );

				$access_token = $connection->oauth( "oauth/access_token", array( "oauth_verifier" => $_POST['oauth_verifier'] ) );

				WC()->session->set( 'access_token', $access_token );

				$connection = new TwitterOAuth( get_option( 'ywsfd_twitter_app_id' ), get_option( 'ywsfd_twitter_app_secret' ), $access_token['oauth_token'], $access_token['oauth_token_secret'] );

				$tweet = wc_clean( $_POST['tweet'] );

				if ( empty( $tweet ) ) {

					wc_add_notice( __( 'The message field cannot be empty', 'yith-woocommerce-share-for-discounts' ), 'error' );

				} else {

					if ( strpos( $tweet, 'http' ) !== true ) {

						$url   = ' - ' . $_POST['sharing_url'];
						$tweet = substr( $tweet, 0, ( 140 - strlen( $url ) ) ) . $url;

					}

					$parameters = array(
						'status' => substr( $tweet, 0, 140 ),
					);

					$statuses = $connection->post( "statuses/update", $parameters );

					if ( $connection->getLastHttpCode() == 200 ) {

						echo '<!--WC_START-->' . json_encode( array( 'status' => 'success' ) ) . '<!--WC_END-->';
						exit;

					} else {

						wc_add_notice( __( 'An error occurred', 'yith-woocommerce-share-for-discounts' ), 'error' );

					}

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

	}

	/**
	 * Unique access to instance of YWSFD_Ajax class
	 *
	 * @return \YWSFD_Ajax
	 */
	function YWSFD_Ajax() {

		return YWSFD_Ajax::get_instance();

	}

}