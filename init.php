<?php
/**
 * Plugin Name: YITH WooCommerce Share For Discounts Premium
 * Plugin URI: http://yithemes.com/themes/plugins/yith-woocommerce-share-for-discounts/
 * Description: YITH WooCommerce Share For Discounts gives you the perfect tool to reward your users when they share the products they are going to purchase.
 * Author: YITHEMES
 * Text Domain: yith-woocommerce-share-for-discounts
 * Version: 1.4.2
 * Author URI: http://yithemes.com/
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

if ( ! function_exists( 'is_plugin_active' ) ) {
	require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
}

function ywsfd_install_woocommerce_premium_admin_notice() {
	?>
	<div class="error">
		<p><?php _e( 'YITH WooCommerce Share For Discounts is enabled but not effective. It requires WooCommerce in order to work.', 'yith-woocommerce-share-for-discounts' ); ?></p>
	</div>
	<?php
}

if ( ! function_exists( 'yit_deactive_free_version' ) ) {
	require_once 'plugin-fw/yit-deactive-plugin.php';
}

yit_deactive_free_version( 'YWSFD_FREE_INIT', plugin_basename( __FILE__ ) );

if ( ! defined( 'YWSFD_VERSION' ) ) {
	define( 'YWSFD_VERSION', '1.4.2' );
}

if ( ! defined( 'YWSFD_INIT' ) ) {
	define( 'YWSFD_INIT', plugin_basename( __FILE__ ) );
}

if ( ! defined( 'YWSFD_SLUG' ) ) {
	define( 'YWSFD_SLUG', 'yith-woocommerce-share-for-discounts' );
}

if ( ! defined( 'YWSFD_SECRET_KEY' ) ) {
	define( 'YWSFD_SECRET_KEY', 'XcGRIqJg5Fzlbh9lpEND' );
}

if ( ! defined( 'YWSFD_PREMIUM' ) ) {
	define( 'YWSFD_PREMIUM', '1' );
}

if ( ! defined( 'YWSFD_FILE' ) ) {
	define( 'YWSFD_FILE', __FILE__ );
}

if ( ! defined( 'YWSFD_DIR' ) ) {
	define( 'YWSFD_DIR', plugin_dir_path( __FILE__ ) );
}

if ( ! defined( 'YWSFD_URL' ) ) {
	define( 'YWSFD_URL', plugins_url( '/', __FILE__ ) );
}

if ( ! defined( 'YWSFD_ASSETS_URL' ) ) {
	define( 'YWSFD_ASSETS_URL', YWSFD_URL . 'assets' );
}

if ( ! defined( 'YWSFD_TEMPLATE_PATH' ) ) {
	define( 'YWSFD_TEMPLATE_PATH', YWSFD_DIR . 'templates' );
}

/* Plugin Framework Version Check */
if ( ! function_exists( 'yit_maybe_plugin_fw_loader' ) && file_exists( YWSFD_DIR . 'plugin-fw/init.php' ) ) {
	require_once( YWSFD_DIR . 'plugin-fw/init.php' );
}
yit_maybe_plugin_fw_loader( YWSFD_DIR );

function ywsfd_init() {

	/* Load text domain */
	load_plugin_textdomain( 'yith-woocommerce-share-for-discounts', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );

	if ( ! session_id() ) {
		session_start();
	}

	/* === Global YITH WooCommerce Share For Discounts  === */
	YITH_WSFD();

}

add_action( 'ywsfd_init', 'ywsfd_init' );

function ywsfd_install() {

	if ( ! function_exists( 'WC' ) ) {
		add_action( 'admin_notices', 'ywsfd_install_woocommerce_premium_admin_notice' );
	} else {
		do_action( 'ywsfd_init' );
	}

}

add_action( 'plugins_loaded', 'ywsfd_install', 11 );

/**
 * Init default plugin settings
 */
if ( ! function_exists( 'yith_plugin_registration_hook' ) ) {
	require_once 'plugin-fw/yit-plugin-registration-hook.php';
}

register_activation_hook( __FILE__, 'yith_plugin_registration_hook' );

if ( ! function_exists( 'YITH_WSFD' ) ) {

	/**
	 * Unique access to instance of YITH_WC_Share_For_Discounts
	 *
	 * @since   1.0.0
	 * @return  YITH_WC_Share_For_Discounts|YITH_WC_Share_For_Discounts_Premium
	 * @author  Alberto Ruggiero
	 */
	function YITH_WSFD() {

		// Load required classes and functions
		require_once( YWSFD_DIR . 'class.yith-wc-share-for-discounts.php' );

		if ( defined( 'YWSFD_PREMIUM' ) && file_exists( YWSFD_DIR . 'class.yith-wc-share-for-discounts-premium.php' ) ) {


			require_once( YWSFD_DIR . 'class.yith-wc-share-for-discounts-premium.php' );

			return YITH_WC_Share_For_Discounts_Premium::get_instance();
		}

		return YITH_WC_Share_For_Discounts::get_instance();

	}

}

if ( ! function_exists( 'ywsfd_trash_coupon_schedule' ) ) {

	/**
	 * Creates a cron job to handle daily expired coupon trash
	 *
	 * @since   1.0.0
	 * @return  void
	 * @author  Alberto Ruggiero
	 */
	function ywsfd_trash_coupon_schedule() {
		wp_schedule_event( time(), 'daily', 'ywsfd_trash_coupon_cron' );
	}

}
register_activation_hook( __FILE__, 'ywsfd_trash_coupon_schedule' );