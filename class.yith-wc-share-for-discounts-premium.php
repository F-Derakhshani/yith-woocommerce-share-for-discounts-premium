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
	exit;
} // Exit if accessed directly

/**
 * Main class
 *
 * @class   YITH_WC_Share_For_Discounts_Premium
 * @package Yithemes
 * @since   1.0.0
 * @author  Your Inspiration Themes
 */

if ( ! class_exists( 'YITH_WC_Share_For_Discounts_Premium' ) ) {

	class YITH_WC_Share_For_Discounts_Premium extends YITH_WC_Share_For_Discounts {

		/**
		 * @var array
		 */
		protected $_email_types = array();

		/**
		 * Returns single instance of the class
		 *
		 * @return \YITH_WC_Share_For_Discounts_Premium
		 * @since 1.0.0
		 */
		public static function get_instance() {

			if ( is_null( self::$instance ) ) {

				self::$instance = new self;

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

			parent::__construct();

			$this->_email_types = array(
				'share' => array(
					'class' => 'YWSFD_Share_Mail',
					'file'  => 'class-ywsfd-share-email.php',
					'hide'  => true,
				),
			);

			// register plugin to licence/update system
			add_action( 'wp_loaded', array( $this, 'register_plugin_for_activation' ), 99 );
			add_action( 'admin_init', array( $this, 'register_plugin_for_updates' ) );

			$this->includes_premium();

			if ( is_admin() ) {

				add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );
				add_action( 'woocommerce_admin_field_ywsfd-coupon', 'YWSFD_Custom_Coupon::output' );

			}

			if ( get_option( 'ywsfd_enable_plugin' ) == 'yes' ) {

				add_filter( 'ywsfd_coupon_options', array( $this, 'ywsfd_coupon_options' ) );
				add_shortcode( 'ywsfd_shortcode', array( $this, 'ywsfd_shortcode' ) );

				add_action( 'admin_init', array( $this, 'ywsfd_add_shortcodes_button' ) );
				add_action( 'admin_print_footer_scripts', array( &$this, 'ywsfd_add_quicktags' ) );

				if ( get_option( 'ywsfd_coupon_purge' ) == 'yes' ) {

					add_action( 'ywsfd_trash_coupon_cron', array( $this, 'trash_expired_coupons' ) );

				}

				YWSFD_Ajax_Premium();

				if ( is_admin() ) {

					add_filter( 'ywsfd_additional_notices', array( $this, 'ywsfd_additional_notices' ) );

				} else {

					if ( get_option( 'ywsfd_enable_facebook' ) == 'yes' ) {

						add_action( 'wp_head', array( $this, 'add_opengraph_meta' ), 5 );
						add_filter( 'language_attributes', array( $this, 'add_opengraph_doctype' ) );

					}

					add_action( 'wp_loaded', array( $this, 'send_email_action' ), 20 );
					add_action( 'wp_enqueue_scripts', array( $this, 'frontend_scripts_premium' ) );
					add_action( 'wp_head', array( $this, 'ywsfd_theme_compatibility' ) );

					add_filter( 'ywsfd_social_params', array( $this, 'ywsfd_social_params' ), 10, 1 );
					add_filter( 'ywsfd_available_socials', array( $this, 'ywsfd_available_socials' ) );
					add_filter( 'ywsfd_share_position', array( $this, 'ywsfd_share_position' ) );
					add_filter( 'ywsfd_scripts_filter', array( $this, 'ywsfd_scripts_filter' ), 10, 1 );
					add_filter( 'ywsfd_share_title', array( $this, 'ywsfd_share_title' ) );
					add_filter( 'ywsfd_share_title_after', array( $this, 'ywsfd_share_title_after' ) );
					add_filter( 'ywsfd_share_message', array( $this, 'ywsfd_share_message' ) );
					add_filter( 'ywsfd_post_url', array( $this, 'ywsfd_post_url' ), 10, 1 );
					add_filter( 'ywsfd_post_message', array( $this, 'ywsfd_post_message' ), 10, 1 );

					add_action( 'woocommerce_before_checkout_form', array( $this, 'show_ywsfd_checkout_page' ) );
					add_action( 'woocommerce_before_cart', array( $this, 'show_ywsfd_cart_page' ) );

				}

				add_filter( 'woocommerce_email_classes', array( $this, 'ywsfd_custom_email' ) );

				add_filter( 'ywsfd_can_get_coupon', array( $this, 'ywsfd_can_get_coupon' ), 10, 2 );
				add_filter( 'ywsfd_onsale_variations', array( $this, 'ywsfd_get_onsale_variation' ), 10, 2 );

			}

		}

		/**
		 * Files inclusion
		 *
		 * @since   1.0.0
		 * @return  void
		 * @author  Alberto Ruggiero
		 */
		private function includes_premium() {

			include_once( 'includes/class-ywsfd-ajax-premium.php' );

			if ( get_option( 'ywsfd_enable_linkedin' ) == 'yes' ) {

				require_once( 'includes/linkedinauth/linkedin.php' );

			}

			if ( is_admin() ) {

				include_once( 'templates/admin/custom-coupon.php' );
				include_once( 'templates/admin/class-ywsfd-custom-coupon-purge.php' );
				include_once( 'templates/admin/class-ywsfd-custom-image-upload.php' );
				include_once( 'templates/admin/class-yith-wc-custom-textarea.php' );

			}

		}

		/**
		 * Add styles for major themes compatibility
		 *
		 * @since   1.2.1
		 * @return  void
		 * @author  Alberto Ruggiero
		 */
		public function ywsfd_theme_compatibility() {

			//AVADA
			if ( class_exists( 'Avada' ) ) {

				?>

				<style type="text/css">

					.cart-collaterals .ywsfd-wrapper {
						float: left;
						border: 1px solid #e0dede;
						width: 48%;
						padding: 30px;
						margin: 0 0 10px 0;
					}

					.cart-collaterals .ywsfd-wrapper .ywsfd-social {

						padding: 0;
						margin: 0;
						border: 0 none;
					}

				</style>

				<?php

			}

			//NEIGHBORHOOD
			if ( function_exists( 'sf_is_neighborhood' ) ) {

				?>

				<style type="text/css">

					.checkout .ywsfd-wrapper {
						width: 380px;
						float: left;
						min-height: 1px;
						margin-left: 20px;
					}

					@media (min-width: 1200px) {

						.checkout .ywsfd-wrapper {
							width: 470px;
							margin-left: 30px;
						}

					}

					@media (max-width: 979px) and (min-width: 768px) {

						.checkout .ywsfd-wrapper {
							width: 290px;
							margin-left: 20px;
						}

					}

					@media (max-width: 767px) {

						.checkout .ywsfd-wrapper {
							display: block;
							float: none;
							width: 100%;
							max-width: 100%;
							margin-left: 0;
							-webkit-box-sizing: border-box;
							-moz-box-sizing: border-box;
							box-sizing: border-box;
						}

					}

				</style>

				<?php

			}

		}

		/**
		 * ADMIN FUNCTIONS
		 */

		/**
		 * Trash expired coupons
		 *
		 * @since   1.0.8
		 *
		 * @param   $return
		 *
		 * @return  mixed
		 * @author  Alberto Ruggiero
		 */
		public function trash_expired_coupons( $return = false ) {

			$args = array(
				'post_type'      => 'shop_coupon',
				'post_status'    => 'publish',
				'posts_per_page' => - 1,
				'meta_query'     => array(
					'relation' => 'AND',
					array(
						'key'   => 'generated_by',
						'value' => 'ywsfd',
					),
					array(
						'relation' => 'OR',
						array(
							'key'     => 'expiry_date',
							'value'   => date( 'Y-m-d', strtotime( "today" ) ),
							'compare' => '<',
							'type'    => 'DATE'
						),
						array(
							'key'     => 'usage_count',
							'value'   => 1,
							'compare' => '>='
						)
					)
				)
			);

			$query = new WP_Query( $args );
			$count = $query->post_count;

			if ( $query->have_posts() ) {

				while ( $query->have_posts() ) {

					$query->the_post();

					wp_trash_post( $query->post->ID );

				}

			}

			wp_reset_query();
			wp_reset_postdata();

			if ( $return ) {

				return $count;

			}

			return null;

		}

		/**
		 * Initializes CSS and javascript
		 *
		 * @since   1.0.0
		 * @return  void
		 * @author  Alberto Ruggiero
		 */
		public function admin_scripts() {

			$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

			wp_enqueue_style( 'ywsfd-admin', YWSFD_ASSETS_URL . '/css/ywsfd-admin' . $suffix . '.css' );

			wp_enqueue_script( 'ywsfd-admin-premium', YWSFD_ASSETS_URL . '/js/ywsfd-admin-premium' . $suffix . '.js', array( 'jquery' ) );

			$params = array(
				'ajax_url' => admin_url( 'admin-ajax.php' ),
			);

			wp_localize_script( 'ywsfd-admin-premium', 'ywsfd_admin', $params );

		}

		/**
		 * Add shortcode button to TinyMCE editor, adding filter on mce_external_plugins
		 *
		 * @since   1.0.0
		 * @return  void
		 * @author  Alberto Ruggiero
		 */
		public function ywsfd_add_shortcodes_button() {

			if ( ! current_user_can( 'edit_posts' ) && ! current_user_can( 'edit_pages' ) ) {

				return;

			}

			if ( get_user_option( 'rich_editing' ) == 'true' ) {

				add_filter( 'mce_external_plugins', array( &$this, 'ywsfd_add_shortcodes_tinymce_plugin' ) );
				add_filter( 'mce_buttons', array( &$this, 'ywsfd_register_shortcodes_button' ) );

			}

		}

		/**
		 * Add a script to TinyMCE script list
		 *
		 * @since   1.0.0
		 *
		 * @param   $plugin_array
		 *
		 * @return  array
		 * @author  Alberto Ruggiero
		 */
		public function ywsfd_add_shortcodes_tinymce_plugin( $plugin_array ) {

			$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

			$plugin_array['ywsfd_shortcode'] = YWSFD_ASSETS_URL . '/js/ywsfd-tinymce' . $suffix . '.js';

			return $plugin_array;

		}

		/**
		 * Make TinyMCE know a new button was included in its toolbar
		 *
		 * @since   1.0.0
		 *
		 * @param   $buttons
		 *
		 * @return  array
		 * @author  Alberto Ruggiero
		 */
		public function ywsfd_register_shortcodes_button( $buttons ) {

			array_push( $buttons, "|", "ywsfd_shortcode" );

			return $buttons;

		}

		/**
		 * Add quicktags to visual editor
		 *
		 * @since   1.0.0
		 * @return  void
		 * @author  Alberto Ruggiero
		 */
		public function ywsfd_add_quicktags() {
			?>
			<script type="text/javascript">

				if (window.QTags !== undefined) {
					QTags.addButton('ywsfd_shortcode', 'add ywsfd shortcode', function () {
						var str = '[ywsfd_shortcode]',
							win = window.dialogArguments || opener || parent || top;

						win.send_to_editor(str);
						var ed;
						if (typeof tinyMCE != 'undefined' && ( ed = tinyMCE.activeEditor ) && !ed.isHidden()) {
							ed.setContent(ed.getContent());
						}
					});
				}

			</script>
			<?php
		}

		/**
		 * Hides custom email settings from WooCommerce panel
		 *
		 * @since   1.0.0
		 *
		 * @param   $sections
		 *
		 * @return  array
		 * @author  Alberto Ruggiero
		 */
		public function ywsfd_hide_sections( $sections ) {
			foreach ( $this->_email_types as $type => $email_type ) {
				$class_name = strtolower( $email_type['class'] );
				if ( isset( $sections[ $class_name ] ) && $email_type['hide'] == true ) {
					unset( $sections[ $class_name ] );
				}
			}

			return $sections;
		}

		/**
		 * Add the YWSFD_Share_Mail class to WooCommerce mail classes
		 *
		 * @since   1.0.0
		 *
		 * @param   $email_classes
		 *
		 * @return  array
		 * @author  Alberto Ruggiero
		 */
		public function ywsfd_custom_email( $email_classes ) {

			foreach ( $this->_email_types as $type => $email_type ) {
				$email_classes[ $email_type['class'] ] = include( "includes/{$email_type['file']}" );
			}

			return $email_classes;
		}

		/**
		 * Manage additional admin notices
		 *
		 * @since   1.3.3
		 *
		 * @param   $errors
		 *
		 * @return  array
		 * @author  Alberto Ruggiero
		 */
		public function ywsfd_additional_notices( $errors ) {

			if ( isset( $_POST['ywsfd_enable_linkedin'] ) && '1' == $_POST['ywsfd_enable_linkedin'] && $_POST['ywsfd_linkedin_app_id'] == '' ) {

				$errors[] = __( 'You need to add a Linkedin Client ID', 'yith-woocommerce-share-for-discounts' );

			}

			if ( isset( $_POST['ywsfd_enable_linkedin'] ) && '1' == $_POST['ywsfd_enable_linkedin'] && $_POST['ywsfd_linkedin_app_secret'] == '' ) {

				$errors[] = __( 'You need to add a Linkedin Client Secret', 'yith-woocommerce-share-for-discounts' );

			}

			return $errors;

		}

		/**
		 * FRONTEND FUNCTIONS
		 */

		/**
		 * Initializes CSS and javascript
		 *
		 * @since   1.0.0
		 * @return  void
		 * @author  Alberto Ruggiero
		 */
		public function frontend_scripts_premium() {

			global $post;

			if ( ! apply_filters( 'ywsfd_can_get_coupon', true, ( isset( $post->ID ) ? $post->ID : '' ) ) || $this->coupon_already_assigned() || is_shop() || is_account_page() ) {
				return;
			}

			//$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
			wp_enqueue_script( 'ywsfd-frontend-premium', YWSFD_ASSETS_URL . '/js/ywsfd-frontend-premium.js', array( 'jquery', 'ywsfd-frontend' ) );

		}

		/**
		 * Get the position and show YWSFD in checkout page
		 *
		 * @since   1.0.0
		 * @return  void
		 * @author  Alberto Ruggiero
		 */
		public function show_ywsfd_checkout_page() {

			if ( get_option( 'ywsfd_share_checkout_enable' ) == 'yes' ) {

				$position = get_option( 'ywsfd_share_checkout_position' );

				switch ( $position ) {

					case '1':
						$args = array(
							'hook'     => 'after',
							'priority' => 10
						);
						break;

					default:
						$args = array(
							'hook'     => 'before',
							'priority' => 10
						);

				}

				add_action( 'woocommerce_checkout_' . $args['hook'] . '_customer_details', array( $this, 'add_ywsfd_template' ), $args['priority'] );

			}

		}

		/**
		 * Get the position and show YWSFD in cart page
		 *
		 * @since   1.0.0
		 * @return  void
		 * @author  Alberto Ruggiero
		 */
		public function show_ywsfd_cart_page() {

			if ( get_option( 'ywsfd_share_cart_enable' ) == 'yes' ) {

				$position = get_option( 'ywsfd_share_cart_position' );

				switch ( $position ) {

					case '1':
						$args = array(
							'hook'     => 'cart_collaterals',
							'priority' => 10
						);
						break;

					case '2':
						$args = array(
							'hook'     => 'after_cart',
							'priority' => 10
						);
						break;

					default:
						$args = array(
							'hook'     => 'before_cart',
							'priority' => 11
						);

				}

				add_action( 'woocommerce_' . $args['hook'], array( $this, 'add_ywsfd_template' ), $args['priority'] );

			}

		}

		/**
		 * Add parameters for premium social networks
		 *
		 * @since   1.0.0
		 *
		 * @param   $params
		 *
		 * @return  array
		 * @author  Alberto Ruggiero
		 */
		public function ywsfd_social_params( $params ) {

			global $post;

			$params['linkedin'] = get_option( 'ywsfd_enable_linkedin' );
			$params['email']    = get_option( 'ywsfd_enable_email' );

			$params['sharing']['form_action'] = isset( $post->ID ) ? get_permalink( $post->ID ) : '';
			$params['sharing']['post_id']     = isset( $post->ID ) ? $post->ID : '';

			return $params;

		}

		/**
		 * Get share bar position in product page
		 *
		 * @since   1.0.0
		 * @return  array
		 * @author  Alberto Ruggiero
		 */
		public function ywsfd_share_position() {

			$position = get_option( 'ywsfd_share_product_position' );

			switch ( $position ) {

				case '1':
					return array(
						'hook'     => 'single_product',
						'priority' => 15
					);
					break;

				case '2':
					return array(
						'hook'     => 'single_product',
						'priority' => 25
					);
					break;

				case '3':
					return array(
						'hook'     => 'after_single_product',
						'priority' => 5
					);
					break;

				case '4':
					return array(
						'hook'     => 'after_single_product',
						'priority' => 15
					);
					break;

				case '5':
					return array(
						'hook'     => 'after_single_product',
						'priority' => 25
					);
					break;

				default:
					return array(
						'hook'     => 'before_single_product',
						'priority' => 5
					);

			}

		}

		/**
		 * Get the list of available social networks
		 *
		 * @since   1.0.0
		 *
		 * @param   $socials
		 *
		 * @return  array
		 * @author  Alberto Ruggiero
		 */
		public function ywsfd_available_socials( $socials ) {

			$socials[] = 'linkedin';
			$socials[] = 'email';

			return $socials;

		}

		/**
		 * Add parameters for premium social networks
		 *
		 * @since   1.0.0
		 *
		 * @param   $params
		 *
		 * @return  array
		 * @author  Alberto Ruggiero
		 */
		public function ywsfd_scripts_filter( $params ) {

			global $post;

			$params['onsale_variations'] = apply_filters( 'ywsfd_onsale_variations', array(), isset( $post->ID ) ? $post->ID : '' );
			$params['email']             = 'no';
			$params['linkedin']          = 'no';

			if ( get_option( 'ywsfd_enable_linkedin' ) == 'yes' ) {

				$params['linkedin']           = 'yes';
				$params['linkedin_login']     = YWSFD_URL . 'templates/frontend/linkedin-login.php';
				$params['linkedin_close']     = __( 'You closed the parent window. The authorization process has been suspended.', 'yith-woocommerce-share-for-discounts' );
				$params['linkedin_fail']      = __( 'The authorization process has failed.', 'yith-woocommerce-share-for-discounts' );
				$params['linkedin_auth_ajax'] = add_query_arg( 'action', 'ywsfd_get_linkedin_url', str_replace( array( 'https:', 'http:' ), '', admin_url( 'admin-ajax.php' ) ) );
				$params['linkedin_send_ajax'] = add_query_arg( 'action', 'ywsfd_send_linkedin', str_replace( array( 'https:', 'http:' ), '', admin_url( 'admin-ajax.php' ) ) );

			}

			if ( get_option( 'ywsfd_enable_email' ) == 'yes' ) {

				$params['email']          = 'yes';
				$params['ajax_email_url'] = add_query_arg( 'action', 'ywsfd_send_friend_mail', str_replace( array( 'https:', 'http:' ), '', admin_url( 'admin-ajax.php' ) ) );

			}

			$params['custom_url'] = 'no';

			if ( get_option( 'ywsfd_custom_url' ) != '' ) {

				$params['custom_url'] = 'yes';

			}

			return $params;

		}

		/**
		 * Get title for share buttons
		 *
		 * @since   1.0.0
		 * @return  string
		 * @author  Alberto Ruggiero
		 */
		public function ywsfd_share_title() {

			return get_option( 'ywsfd_share_title' );

		}

		/**
		 * Get title after share
		 *
		 * @since   1.0.0
		 * @return  string
		 * @author  Alberto Ruggiero
		 */
		public function ywsfd_share_title_after() {

			return get_option( 'ywsfd_share_title_after' );

		}

		/**
		 * Get message after share
		 *
		 * @since   1.0.0
		 * @return  string
		 * @author  Alberto Ruggiero
		 */
		public function ywsfd_share_message() {

			return get_option( 'ywsfd_share_text_after' );

		}

		/**
		 * Get coupon options
		 *
		 * @since   1.0.0
		 * @return  array
		 * @author  Alberto Ruggiero
		 */
		public function ywsfd_coupon_options() {

			return get_option( 'ywsfd_coupon' );

		}

		/**
		 * Get url for share buttons
		 *
		 * @since   1.0.0
		 *
		 * @param   $url
		 *
		 * @return  string
		 * @author  Alberto Ruggiero
		 */
		public function ywsfd_post_url( $url ) {

			$custom_url = get_option( 'ywsfd_custom_url' );

			return ( $custom_url != '' ? $custom_url : $url );

		}

		/**
		 * Get custom share message
		 *
		 * @since   1.3.0
		 *
		 * @param   $message
		 *
		 * @return  string
		 * @author  Alberto Ruggiero
		 */
		public function ywsfd_post_message( $message ) {

			$custom_url = get_option( 'ywsfd_custom_message' );

			return ( $custom_url != '' ? $custom_url : $message );

		}

		/**
		 * Add opengraph doctype
		 *
		 * @since   1.0.0
		 *
		 * @param   $output
		 *
		 * @return  string
		 * @author  Alberto Ruggiero
		 */
		public function add_opengraph_doctype( $output ) {

			return $output . ' xmlns:og="http://opengraphprotocol.org/schema/" xmlns:fb="http://www.facebook.com/2008/fbml"';

		}

		/**
		 * Add opengraph meta
		 *
		 * @since   1.0.0
		 * @return  string
		 * @author  Alberto Ruggiero
		 */
		public function add_opengraph_meta() {

			if ( apply_filters( 'ywsfd_hide_og_meta', false ) ) {
				return;
			}

			global $post;

			$default_title       = get_option( 'ywsfd_fbmeta_title' );
			$default_description = get_option( 'ywsfd_fbmeta_description' );
			$default_image       = get_option( 'ywsfd_fbmeta_image' );

			if ( is_product() ) {

				if ( has_post_thumbnail( $post->ID ) ) {

					$image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'large' );
					$image = $image[0];

				} else {

					$image = $default_image;

				}

				$title       = get_the_title();
				$description = strip_tags( get_the_excerpt() );
				$permalink   = get_permalink();

			} else {

				$title       = $default_title ? $default_title : get_option( 'blogname' );
				$description = $default_description ? $default_description : get_option( 'blogdescription' );
				$image       = $default_image;

				global $wp;
				$permalink = home_url( add_query_arg( array(), $wp->request ) ) . '/';

			}

			?>

			<meta property="fb:app_id" content="<?php echo get_option( 'ywsfd_appid_facebook' ) ?>" />
			<meta property="og:type" content="website" />
			<meta property="og:site_name" content="<?php echo get_option( 'blogname' ) ?>" />
			<meta property="og:url" content="<?php echo $permalink ?>" />
			<meta property="og:title" content="<?php echo $title ?>" />
			<?php if ( $image ): ?>
				<meta property="og:image" content="<?php echo esc_attr( $image ) ?>" />
			<?php endif; ?>
			<meta property="og:description" content="<?php echo $description ?>" />

			<?php

		}

		/**
		 * Process the email form
		 *
		 * @since   1.0.0
		 * @return  string
		 * @author  Alberto Ruggiero
		 */
		public function send_email_action() {

			if ( isset( $_POST['ywsfd_email'] ) ) {

				YWSFD_Ajax_Premium()->send_friend_mail();
			}

		}

		/**
		 * Set shortcode
		 *
		 * @since   1.0.0
		 * @return  string
		 * @author  Alberto Ruggiero
		 */
		public function ywsfd_shortcode() {

			add_filter( 'widget_text', 'shortcode_unautop' );
			add_filter( 'widget_text', 'do_shortcode' );

			ob_start();

			$this->add_ywsfd_template();

			return ob_get_clean();

		}

		/**
		 * Current product can be shared?
		 *
		 * @since   1.3.1
		 *
		 * @param   $value
		 * @param   $product_id
		 *
		 * @return  bool
		 * @author  Alberto Ruggiero
		 */
		public function ywsfd_can_get_coupon( $value, $product_id ) {

			$product = wc_get_product( $product_id );

			if ( ! $product ) {
				return $value;
			}

			if ( get_option( 'ywsfd_share_product_onsale_enable', 'yes' ) == 'no' ) {

				if ( $product->is_type( 'variable' ) ) {

					$variations        = array_filter( $product->get_available_variations() );
					$onsale_variations = $this->ywsfd_get_onsale_variation( array(), $product_id );

					if ( count( $variations ) == count( $onsale_variations ) ) {

						$value = false;

					}

				} else {

					if ( $product->get_sale_price() ) {

						$value = false;

					}

				}

			}

			return $value;

		}

		/**
		 * Get onsale variations
		 *
		 * @since   1.3.1
		 *
		 * @param   $onsale_variations
		 * @param   $product_id
		 *
		 * @return  array
		 * @author  Alberto Ruggiero
		 */
		public function ywsfd_get_onsale_variation( $onsale_variations, $product_id ) {

			if ( ! $product_id ) {
				return $onsale_variations;
			}

			if ( get_option( 'ywsfd_share_product_onsale_enable', 'yes' ) == 'no' ) {

				$product = wc_get_product( $product_id );

				if ( ! $product ) {
					return $onsale_variations;
				}

				if ( $product->is_type( 'variable' ) ) {

					$variations = array_filter( $product->get_available_variations() );

					if ( count( $variations ) > 0 ) {

						foreach ( $variations as $variation ) {

							$product_variation = wc_get_product( $variation['variation_id'] );

							if ( $product_variation->get_sale_price() ) {

								$onsale_variations[] = $variation['variation_id'];

							}

						}

					}

				}

			}

			return $onsale_variations;

		}

		/**
		 * YITH FRAMEWORK
		 */

		/**
		 * Register plugins for activation tab
		 *
		 * @since   2.0.0
		 * @return  void
		 * @author  Andrea Grillo <andrea.grillo@yithemes.com>
		 */
		public function register_plugin_for_activation() {
			if ( ! class_exists( 'YIT_Plugin_Licence' ) ) {
				require_once 'plugin-fw/licence/lib/yit-licence.php';
				require_once 'plugin-fw/licence/lib/yit-plugin-licence.php';
			}
			YIT_Plugin_Licence()->register( YWSFD_INIT, YWSFD_SECRET_KEY, YWSFD_SLUG );
		}

		/**
		 * Register plugins for update tab
		 *
		 * @since   2.0.0
		 * @return  void
		 * @author  Andrea Grillo <andrea.grillo@yithemes.com>
		 */
		public function register_plugin_for_updates() {
			if ( ! class_exists( 'YIT_Upgrade' ) ) {
				require_once( 'plugin-fw/lib/yit-upgrade.php' );
			}
			YIT_Upgrade()->register( YWSFD_SLUG, YWSFD_INIT );
		}

	}

}

