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

if ( !class_exists( 'YWSFD_Share_Mail' ) ) {

    /**
     * Implements Share Mail for YWSFD plugin
     *
     * @class   YWSFD_Share_Mail
     * @package Yithemes
     * @since   1.0.0
     * @author  Your Inspiration Themes
     * @extends WC_Email
     *
     */
    class YWSFD_Share_Mail extends WC_Email {

        /**
         * @var string $customer_mail email of the customer that wants to unsubscribe
         */
        var $mail_body;

        /**
         * Constructor
         *
         * Initialize email type and set templates paths
         *
         * @since   1.0.0
         * @author  Alberto Ruggiero
         */
        public function __construct() {

            $this->id             = 'yith-share-for-discounts';
            $this->customer_email = true;
            $this->description    = __( 'YITH WooCommerce Share For Discounts gives you the perfect tool to reward your users when they share the products they are going to purchase.', 'yith-woocommerce-coupon-email-system' );
            $this->title          = __( 'Share For Discounts', 'yith-woocommerce-coupon-email-system' );
            $this->enabled        = 'yes';
            $this->template_html  = '/emails/share-email.php';
            $this->template_plain = '/emails/plain/share-email.php';
            $this->manual         = true;

            parent::__construct();

        }

        /**
         * Trigger email send
         *
         * @since   1.0.0
         *
         * @param   $friend_email
         * @param   $subject
         * @param   $message
         * @param   $sender_mail
         *
         * @return  bool
         * @author  Alberto Ruggiero
         */
        public function trigger( $friend_email, $subject, $message, $sender_mail ) {

            $this->heading    = $subject;
            $this->subject    = $subject;
            $this->mail_body  = $message;
            $this->recipient  = $friend_email;
            $this->email_type = 'html';

            $headers = $this->get_headers() . "Reply-To: " . $sender_mail . "\r\n";

            return $this->send( $this->get_recipient(), $this->get_subject(), $this->get_content(), $headers, "" );

        }

        /**
         * Get HTML content
         *
         * @since   1.0.0
         * @return  string
         * @author  Alberto Ruggiero
         */
        function get_content_html() {
            ob_start();
            wc_get_template( $this->template_html, array(
                'email_heading' => $this->get_heading(),
                'mail_body'     => $this->mail_body,
                'email'         => $this,
            ), YWSFD_TEMPLATE_PATH, YWSFD_TEMPLATE_PATH );
            return ob_get_clean();
        }

        /**
         * Get Plain content
         *
         * @since   1.0.0
         * @return  string
         * @author  Alberto Ruggiero
         */
        function get_content_plain() {
            ob_start();
            wc_get_template( $this->template_plain, array(
                'email_heading' => $this->get_heading(),
                'mail_body'     => $this->mail_body,
                'email'         => $this,
            ), YWSFD_TEMPLATE_PATH, YWSFD_TEMPLATE_PATH );
            return ob_get_clean();
        }

        /**
         * Get email content type.
         *
         * @since   1.1.3
         * @return  string
         * @author  Alberto Ruggiero
         */
        public function get_content_type() {

            return 'text/html';

        }

        /**
         * Checks if this email is enabled and will be sent.
         * @since   1.1.3
         * @return  bool
         * @author  Alberto Ruggiero
         */
        public function is_enabled() {
            return true;
        }

        /**
         * Admin Panel Options Processing - Saves the options to the DB
         *
         * @since   1.1.3
         * @return  boolean|null
         * @author  Alberto Ruggiero
         */
        public function process_admin_options() {

            woocommerce_update_options( $this->form_fields['general'] );

        }

        /**
         * Setup email settings screen.
         *
         * @since   1.1.3
         * @return  string
         * @author  Alberto Ruggiero
         */
        public function admin_options() {

            ?>
            <table class="form-table">
                <?php woocommerce_admin_fields( $this->form_fields['general'] ); ?>
            </table>
            <?php

        }

        /**
         * Initialise Settings Form Fields
         *
         * @since   1.1.3
         * @return  void
         * @author  Alberto Ruggiero
         */
        public function init_form_fields() {

            $this->form_fields = include( YWSFD_DIR . '/plugin-options/general-options.php' );

        }

    }

}

return new YWSFD_Share_Mail();