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


ob_start();

?>

	<b><?php echo YWSFD_URL ?>templates/frontend/twitter-login.php</b>
	<br />
	<i><?php _e( 'or', 'yith-woocommerce-share-for-discounts' ) ?></i>
	<br />
	<b><?php echo get_option( 'siteurl' ) ?></b>
	<br />
	<br />

<?php

$tw_callback_content = ob_get_clean();

ob_start();

?>

	<b><?php echo YWSFD_URL ?>templates/frontend/linkedin-login.php</b>
	<br />
	<i><?php _e( 'or', 'yith-woocommerce-share-for-discounts' ) ?></i>
	<br />
	<b><?php echo get_option( 'siteurl' ) ?></b>
	<br />
	<br />

<?php

$lnk_callback_content = ob_get_clean();

return array(

	'premium-general' => array(

		'ywsfd_main_section_title' => array(
			'name' => __( 'Share For Discounts settings', 'yith-woocommerce-share-for-discounts' ),
			'type' => 'title',
		),
		'ywsfd_enable_plugin'      => array(
			'name'    => __( 'Enable YITH WooCommerce Share For Discounts', 'yith-woocommerce-share-for-discounts' ),
			'type'    => 'checkbox',
			'desc'    => '',
			'id'      => 'ywsfd_enable_plugin',
			'default' => 'yes',
		),
		'ywsfd_main_section_end'   => array(
			'type' => 'sectionend',
		),

		'ywsfd_section_facebook'     => array(
			'name' => __( 'Facebook', 'yith-woocommerce-share-for-discounts' ),
			'type' => 'title',
		),
		'ywsfd_enable_facebook'      => array(
			'name'    => __( 'Enable Facebook sharing', 'yith-woocommerce-share-for-discounts' ),
			'type'    => 'checkbox',
			'desc'    => '',
			'id'      => 'ywsfd_enable_facebook',
			'default' => 'no',
		),
		'ywsfd_appid_facebook'       => array(
			'name'    => __( 'Facebook App ID', 'yith-woocommerce-share-for-discounts' ),
			'type'    => 'text',
			'desc'    => '',
			'id'      => 'ywsfd_appid_facebook',
			'default' => '',
		),
		'ywsfd_button_type_facebook' => array(
			'name'    => __( 'Facebook Button Type', 'yith-woocommerce-share-for-discounts' ),
			'type'    => 'select',
			'desc'    => __( 'Select the type of button you want to show for Facebook', 'yith-woocommerce-share-for-discounts' ),
			'id'      => 'ywsfd_button_type_facebook',
			'options' => array(
				'both'  => __( 'Like and Share Buttons', 'yith-woocommerce-share-for-discounts' ),
				'like'  => __( 'Like Button Only', 'yith-woocommerce-share-for-discounts' ),
				'share' => __( 'Share Button Only', 'yith-woocommerce-share-for-discounts' )
			),
			'default' => 'like',
		),
		'ywsfd_section_end_facebook' => array(
			'type' => 'sectionend',
		),

		'ywsfd_section_twitter'      => array(
			'name' => __( 'Twitter', 'yith-woocommerce-share-for-discounts' ),
			'type' => 'title',
		),
		'ywsfd_enable_twitter'       => array(
			'name'    => __( 'Enable Twitter sharing', 'yith-woocommerce-share-for-discounts' ),
			'type'    => 'checkbox',
			'id'      => 'ywsfd_enable_twitter',
			'default' => 'no',
		),
		'ywsfd_user_twitter'         => array(
			'name'    => __( 'Twitter username', 'yith-woocommerce-share-for-discounts' ),
			'type'    => 'text',
			'desc'    => __( 'Set this option if you want to include "via @YourUsername" to your tweets', 'yith-woocommerce-share-for-discounts' ),
			'id'      => 'ywsfd_user_twitter',
			'default' => '',
		),
		'ywsfd_twitter_app_id'       => array(
			'name'    => __( 'Twitter Consumer Key (API Key)', 'yith-woocommerce-share-for-discounts' ),
			'type'    => 'text',
			'id'      => 'ywsfd_twitter_app_id',
			'default' => '',
		),
		'ywsfd_twitter_app_secret'   => array(
			'name'    => __( 'Twitter Consumer Secret (API Secret)', 'yith-woocommerce-share-for-discounts' ),
			'type'    => 'text',
			'id'      => 'ywsfd_twitter_app_secret',
			'default' => '',
		),
		'ywsfd_twitter_callback_url' => array(
			'name'    => __( 'Callback URL', 'yith-woocommerce-share-for-discounts' ),
			'type'    => 'yith-wc-label',
			'desc'    => __( 'Copy this text string into the "Callback URL" field of your Twitter App', 'yith-woocommerce-share-for-discounts' ),
			'content' => $tw_callback_content
		),
		'ywsfd_section_end_twitter'  => array(
			'type' => 'sectionend',
		),

		'ywsfd_section_google'     => array(
			'name' => __( 'Google+', 'yith-woocommerce-share-for-discounts' ),
			'type' => 'title',
		),
		'ywsfd_enable_google'      => array(
			'name'    => __( 'Enable Google+ sharing', 'yith-woocommerce-share-for-discounts' ),
			'type'    => 'checkbox',
			'desc'    => '',
			'id'      => 'ywsfd_enable_google',
			'default' => 'no',
		),
		'ywsfd_button_type_google' => array(
			'name'    => __( 'Google+ Button Type', 'yith-woocommerce-share-for-discounts' ),
			'type'    => 'select',
			'desc'    => __( 'Select the type of button you want to show for Google+.', 'yith-woocommerce-share-for-discounts' ),
			//'desc'    => __( 'Select the type of button you want to show for Google+. Note: because of a bug unresolved by Google, the "Share" button could not generate the coupon correctly. For more information about the bug, please click here:', 'yith-woocommerce-share-for-discounts' ) . ' <a href="https://code.google.com/p/google-plus-platform/issues/detail?id=232">https://code.google.com/p/google-plus-platform/issues/detail?id=232</a>',
			'id'      => 'ywsfd_button_type_google',
			'options' => array(
				'both'    => __( '+1 and Share Buttons', 'yith-woocommerce-share-for-discounts' ),
				'plusone' => __( '+1 Button Only', 'yith-woocommerce-share-for-discounts' ),
				'share'   => __( 'Share Button Only', 'yith-woocommerce-share-for-discounts' )
			),
			'default' => 'like',
		),
		'ywsfd_section_end_google' => array(
			'type' => 'sectionend',
		),

		'ywsfd_section_linkedin'      => array(
			'name' => __( 'Linkedin', 'yith-woocommerce-share-for-discounts' ),
			'type' => 'title',
		),
		'ywsfd_enable_linkedin'       => array(
			'name'    => __( 'Enable Linkedin sharing', 'yith-woocommerce-share-for-discounts' ), //@since 1.3.2
			'type'    => 'checkbox',
			'id'      => 'ywsfd_enable_linkedin',
			'default' => 'no',
		),
		'ywsfd_linkedin_app_id'       => array(
			'name'    => __( 'Linkedin Client ID', 'yith-woocommerce-share-for-discounts' ), //@since 1.3.2
			'type'    => 'text',
			'id'      => 'ywsfd_linkedin_app_id',
			'default' => '',
		),
		'ywsfd_linkedin_app_secret'   => array(
			'name'    => __( 'Linkedin Client Secret', 'yith-woocommerce-share-for-discounts' ), //@since 1.3.2
			'type'    => 'text',
			'id'      => 'ywsfd_linkedin_app_secret',
			'default' => '',
		),
		'ywsfd_linkedin_callback_url' => array(
			'name'    => __( 'Callback URL', 'yith-woocommerce-share-for-discounts' ), //@since 1.3.2
			'type'    => 'yith-wc-label',
			'desc'    => __( 'Copy this text string into the "Authorized Redirect URLs" field of your Linkedin App', 'yith-woocommerce-share-for-discounts' ), //@since 1.3.2,
			'content' => $lnk_callback_content
		),
		'ywsfd_section_end_linkedin'  => array(
			'type' => 'sectionend',
		),

		'ywsfd_section_email'     => array(
			'name' => __( 'Email to a friend', 'yith-woocommerce-share-for-discounts' ),
			'type' => 'title',
		),
		'ywsfd_enable_email'      => array(
			'name'    => __( 'Enable email sharing', 'yith-woocommerce-share-for-discounts' ),
			'type'    => 'checkbox',
			'desc'    => '',
			'id'      => 'ywsfd_enable_email',
			'default' => 'no',
		),
		'ywsfd_section_end_email' => array(
			'type' => 'sectionend',
		),

	)

);