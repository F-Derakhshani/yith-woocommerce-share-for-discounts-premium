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
 * Outputs a custom checkbox template with button for immediate action plugin options panel
 *
 * @class   YWSFD_Custom_Coupon_Purge
 * @package Yithemes
 * @since   1.0.8
 * @author  Your Inspiration Themes
 *
 */
class YWSFD_Custom_Coupon_Purge {

    /**
     * Single instance of the class
     *
     * @var \YWSFD_Custom_Coupon_Purge
     * @since 1.0.8
     */
    protected static $instance;

    /**
     * Returns single instance of the class
     *
     * @return \YWSFD_Custom_Coupon_Purge
     * @since 1.0.8
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
     * @since   1.0.8
     * @return  mixed
     * @author  Alberto Ruggiero
     */
    public function __construct() {

        add_action( 'woocommerce_admin_field_ywsfd-coupon-purge', array( $this, 'output' ) );
        add_filter( 'woocommerce_admin_settings_sanitize_option', array( $this, 'save' ), 10, 3 );

    }

    /**
     * Outputs a custom checkbox template with button for immediate action plugin options panel
     *
     * @since   1.0.8
     *
     * @param   $option
     *
     * @return  void
     * @author  Alberto Ruggiero
     */
    public function output( $option ) {

        $option_value = WC_Admin_Settings::get_option( $option['id'], $option['default'] );

        $custom_attributes = array();

        if ( !empty( $option['custom_attributes'] ) && is_array( $option['custom_attributes'] ) ) {
            foreach ( $option['custom_attributes'] as $attribute => $attribute_value ) {
                $custom_attributes[] = esc_attr( $attribute ) . '="' . esc_attr( $attribute_value ) . '"';
            }
        }

        ?>
        <tr valign="top">
            <th scope="row" class="titledesc">
                <?php echo esc_html( $option['title'] ); ?>
            </th>
            <td class="forminp forminp-checkbox">
                <fieldset>
                    <?php if ( !empty( $option['title'] ) ) : ?>
                        <legend class="screen-reader-text"><span><?php echo esc_html( $option['title'] ) ?></span>
                        </legend>
                    <?php endif; ?>
                    <label for="<?php echo $option['id'] ?>">
                        <input
                            name="<?php echo esc_attr( $option['id'] ); ?>"
                            id="<?php echo esc_attr( $option['id'] ); ?>"
                            type="checkbox"
                            class="<?php echo esc_attr( isset( $option['class'] ) ? $option['class'] : '' ); ?>"
                            value="1"
                            <?php checked( $option_value, 'yes' ); ?>
                            <?php echo implode( ' ', $custom_attributes ); ?>
                        /> <?php echo $option['desc'] ?>
                    </label>
                </fieldset>
                <button type="button" class="button-secondary ywsfd-purge-coupon"><?php _e( 'Remove expired coupons', 'yith-woocommerce-share-for-discounts' ); ?></button>
                <div class="ywsfd-clear-result clear-progress"><?php _e( 'Removal of expired coupons in progress...', 'yith-woocommerce-share-for-discounts' ); ?></div>
            </td>
        </tr>
        <?php
    }

    /**
     * Saves custom checkbox content
     *
     * @since   1.0.9
     *
     * @param   $value
     * @param   $option
     * @param   $raw_value
     *
     * @return  string
     * @author  Alberto Ruggiero
     */
    public function save( $value, $option, $raw_value ) {

        if ( $option['type'] == 'ywsfd-coupon-purge' ) {

            $value = is_null( $raw_value ) ? 'no' : 'yes';

        }

        return $value;

    }

}

/**
 * Unique access to instance of YWSFD_Custom_Coupon_Purge class
 *
 * @return \YWSFD_Custom_Coupon_Purge
 */
function YWSFD_Custom_Coupon_Purge() {

    return YWSFD_Custom_Coupon_Purge::get_instance();

}

new YWSFD_Custom_Coupon_Purge();