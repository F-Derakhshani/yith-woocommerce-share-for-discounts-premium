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

if ( !class_exists( 'YWSFD_Custom_Image_Upload' ) ) {

    /**
     * Outputs a custom image upload template in plugin options panel
     *
     * @class   YWSFD_Custom_Image_Upload
     * @package Yithemes
     * @since   1.0.0
     * @author  Your Inspiration Themes
     *
     */
    class YWSFD_Custom_Image_Upload {

        /**
         * Single instance of the class
         *
         * @var \YWSFD_Custom_Image_Upload
         * @since 1.0.0
         */
        protected static $instance;

        /**
         * Returns single instance of the class
         *
         * @return \YWSFD_Custom_Image_Upload
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

            add_action( 'woocommerce_admin_field_ywsfd-image-upload', array( $this, 'output' ) );
            add_filter( 'woocommerce_admin_settings_sanitize_option', array( $this, 'save' ), 10, 3 );

        }

        /**
         * Outputs a custom textarea template in plugin options panel
         *
         * @since   1.0.0
         *
         * @param   $option
         *
         * @author  Alberto Ruggiero
         * @return  void
         */
        public function output( $option ) {

            $option_value = WC_Admin_Settings::get_option( $option['id'], $option['default'] );
            $img_url      = ( preg_match( '/(jpg|jpeg|png)$/', $option_value ) ) ? $option_value : '';
            ?>
            <tr valign="top">
                <th scope="row" class="titledesc">
                    <label for="<?php echo esc_attr( $option['id'] ); ?>"><?php echo esc_html( $option['title'] ); ?></label>
                </th>
                <td class="forminp forminp-custom-image">

                    <input
                        type="text"
                        name="<?php echo esc_attr( $option['id'] ); ?>"
                        id="<?php echo esc_attr( $option['id'] ); ?>"
                        value="<?php echo esc_attr( $option_value ); ?>"
                        class="ywsfd_upload_img_url" />

                    <input
                        type="button"
                        value="<?php _e( 'Upload', 'yith-woocommerce-share-for-discounts' ) ?>"
                        id="<?php echo $option['id']; ?>-button"
                        class="ywsfd_upload_button button" />

                    <span class="description"><?php echo $option['desc']; ?></span>
                    <br />
                    <div class="ywsfd_upload_preview">

                        <img class="ywsfd_upload_preview_img" src="<?php echo $img_url; ?>" />

                    </div>

                </td>
            </tr>
            <?php
        }

        /**
         * Saves custom textarea content
         *
         * @since   1.0.0
         *
         * @param   $value
         * @param   $option
         * @param   $raw_value
         *
         * @return  string
         * @author  Alberto ruggiero
         */
        public function save( $value, $option, $raw_value ) {

            if ( $option['type'] == 'yith-wc-textarea' ) {

                $value = wp_kses_post( trim( $raw_value ) );

            }

            return $value;

        }

    }

    /**
     * Unique access to instance of YWSFD_Custom_Image_Upload class
     *
     * @return \YWSFD_Custom_Image_Upload
     */
    function YWSFD_Custom_Image_Upload() {

        return YWSFD_Custom_Image_Upload::get_instance();

    }

    new YWSFD_Custom_Image_Upload();

}