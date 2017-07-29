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
 * Outputs a custom table template for manage coupon creation in plugin options panel
 *
 * @class   YWSFD_Custom_Coupon
 * @package Yithemes
 * @since   1.0.0
 * @author  Your Inspiration Themes
 *
 */
class YWSFD_Custom_Coupon {

    /**
     * Outputs a custom table template for manage coupon creation in plugin options panel
     *
     * @since   1.0.0
     *
     * @param   $option
     *
     * @return  void
     * @author  Alberto Ruggiero
     */
    public static function output( $option ) {

        $option_value = WC_Admin_Settings::get_option( $option['id'], $option['default'] );

        ?>
        <tr valign="top">
            <th scope="row" class="titledesc">
                <label for="<?php echo esc_attr( $option['id'] ); ?>"><?php echo esc_html( $option['title'] ); ?></label>
            </th>
            <td class="forminp" id="<?php echo esc_attr( $option['id'] ); ?>_settings">

                <div class="ywsfd-coupon">

                    <p class="form-row form-row-full">
                        <label for="<?php echo esc_attr( $option['id'] ); ?>[description]">
                            <?php _e( 'Description', 'woocommerce' ) ?>
                        </label>
                        <textarea

                            name="<?php echo esc_attr( $option['id'] ); ?>[description]"
                            id="<?php echo esc_attr( $option['id'] ); ?>[description]"
                            class="ywsfd-textarea-coupon"><?php echo esc_textarea( $option_value['description'] ); ?></textarea>
                    </p>

                    <p class="form-row form-row-first">
                        <label for="<?php echo esc_attr( $option['id'] ); ?>[discount_type]">
                            <?php _e( 'Discount type', 'woocommerce' ) ?>
                        </label>
                        <select
                            name="<?php echo esc_attr( $option['id'] ); ?>[discount_type]"
                            id="<?php echo esc_attr( $option['id'] ); ?>[discount_type]"
                            class="short ywsfd-discount-type">
                            <?php
                            $options = array(
                                'fixed_cart'      => __( 'Cart Discount', 'yith-woocommerce-share-for-discounts' ),
                                'percent'         => __( 'Cart % Discount', 'yith-woocommerce-share-for-discounts' ),
                                'fixed_product'   => __( 'Product Discount', 'yith-woocommerce-share-for-discounts' ),
                                'percent_product' => __( 'Product % Discount', 'yith-woocommerce-share-for-discounts' )
                            );

                            foreach ( $options as $key => $val ) {
                                ?>
                                <option value="<?php echo esc_attr( $key ); ?>" <?php

                                if ( is_array( $option_value['discount_type'] ) ) {
                                    selected( in_array( $key, $option_value['discount_type'] ), true );
                                }
                                else {
                                    selected( $option_value['discount_type'], $key );
                                }

                                ?>><?php echo $val ?></option>
                            <?php
                            }
                            ?>
                        </select>
                    </p>

                    <p class="form-row form-row-last">
                        <label for="<?php echo esc_attr( $option['id'] ); ?>[coupon_amount]">
                            <?php _e( 'Coupon amount', 'woocommerce' ) ?>
                        </label>
                        <input
                            type="text"
                            class="wc_input_price ywsfd-coupon-amount"
                            name="<?php echo esc_attr( $option['id'] ); ?>[coupon_amount]"
                            id="<?php echo esc_attr( $option['id'] ); ?>[coupon_amount]"
                            value="<?php echo $option_value['coupon_amount'] ?>"
                            placeholder="<?php echo wc_format_localized_price( 0 ); ?>"
                            />
                    </p>

                    <p class="form-row form-row-first">
                        <label for="<?php echo esc_attr( $option['id'] ); ?>[expiry_days]">
                            <?php _e( 'Number of days after which the coupon expires', 'yith-woocommerce-share-for-discounts' ) ?>
                        </label>
                        <input
                            type="number"
                            class="ywsfd-expiry-days"
                            name="<?php echo esc_attr( $option['id'] ); ?>[expiry_days]"
                            id="<?php echo esc_attr( $option['id'] ); ?>[expiry_days]"
                            value="<?php echo $option_value['expiry_days'] ?>"
                            placeholder="<?php _e( 'No expiration', 'woocommerce' ) ?>"
                            min="1"
                            />
                    </p>


                    <p class="form-row form-row-full checkboxes">
                        <label>
                            <input
                                type="checkbox"
                                class="checkbox ywsfd-free-shipping"
                                name="<?php echo esc_attr( $option['id'] ); ?>[free_shipping]"
                                id="<?php echo esc_attr( $option['id'] ); ?>[free_shipping]"
                                value="1"
                                <?php checked( ( isset( $option_value['free_shipping'] ) ? $option_value['free_shipping'] : '0' ), '1' ); ?>
                                />
                            <?php _e( 'Allow free shipping', 'woocommerce' ) ?>
                        </label>
                    </p>

                </div>
            </td>
        </tr>
    <?php
    }

}