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
 * FACEBOOK
 */
if ( $social_params['facebook'] == 'yes' ) {

	?>
	<div id="fb-root"></div>
	<?php

	if ( $social_params['facebook_type'] != 'share' ) {

		include_once( 'fb-like-btn.php' );

	}

	if ( $social_params['facebook_type'] != 'like' ) {

		include_once( 'fb-share-btn.php' );

	}


}

/**
 * TWITTER
 */
if ( $social_params['twitter'] == 'yes' ) {

	include_once( 'tw-tweet-btn.php' );

}

/**
 * GOOGLE PLUS
 */
if ( $social_params['google'] == 'yes' ) {

	if ( $social_params['google_type'] != 'share' ) {

		include_once( 'gp-plusone-btn.php' );

	}

	if ( $social_params['google_type'] != 'plusone' ) {

		include_once( 'gp-share-btn.php' );

	}


}

/**
 * LINKEDIN
 */
if ( $social_params['linkedin'] == 'yes' && file_exists( YWSFD_TEMPLATE_PATH . '/frontend/lnk-share-btn-premium.php' ) ) {

	include_once( 'lnk-share-btn-premium.php' );

}

/**
 * EMAIL
 */
if ( $social_params['email'] == 'yes' && file_exists( YWSFD_TEMPLATE_PATH . '/frontend/mail-btn-premium.php' ) ) {

	include_once( 'mail-btn-premium.php' );

}

/**
 * TWITTER FORM
 */
if ( $social_params['twitter'] == 'yes' ) {

	include_once( 'tw-tweet-form.php' );

}

/**
 * LINKEDIN FORM
 */
if ( $social_params['linkedin'] == 'yes' && file_exists( YWSFD_TEMPLATE_PATH . '/frontend/lnk-share-form-premium.php' ) ) {

	include_once( 'lnk-share-form-premium.php' );

}

/**
 * EMAIL FORM
 */
if ( $social_params['email'] == 'yes' && file_exists( YWSFD_TEMPLATE_PATH . '/frontend/mail-form-premium.php' ) ) {

	include_once( 'mail-form-premium.php' );

}