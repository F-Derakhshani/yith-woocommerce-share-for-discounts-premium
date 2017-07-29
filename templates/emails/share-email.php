<?php
/**
 * This file belongs to the YIT Plugin Framework.
 *
 * This source file is subject to the GNU GENERAL PUBLIC LICENSE (GPL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-3.0.txt
 */

if ( !defined( 'ABSPATH' ) ) {
    exit;
} // Exit if accessed directly

/**
 * Implements Share Mail for YWSFD plugin (HTML)
 *
 * @class   YWSFD_Share_Mail
 * @package Yithemes
 * @since   1.0.0
 * @author  Your Inspiration Themes
 */

do_action( 'woocommerce_email_header', $email_heading, $email );
?>

    <p><?php echo $mail_body ?></p>

<?php

do_action( 'woocommerce_email_footer', $email );