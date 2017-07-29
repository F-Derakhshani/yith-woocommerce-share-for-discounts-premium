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

return array(
    'coupon' => array(
        'ywsfd_coupon_title' => array(
            'name' => __( 'Coupon Settings', 'yith-woocommerce-share-for-discounts' ),
            'type' => 'title',
        ),
        'ywsfd_coupon'       => array(
            'name'    => __( 'Coupon settings', 'yith-woocommerce-share-for-discounts' ),
            'id'      => 'ywsfd_coupon',
            'default' => array(
                'description'   => __( '10% off for the shared product', 'yith-woocommerce-share-for-discounts' ),
                'discount_type' => 'percent_product',
                'coupon_amount' => 10,
                'expiry_days'   => 1,
                'free_shipping' => '',
            ),
            'type'    => 'ywsfd-coupon'
        ),
        'ywsfd_coupon_purge' => array(
            'name'    => __( 'Expired Coupon Clearing', 'yith-woocommerce-review-for-discounts' ),
            'type'    => 'ywsfd-coupon-purge',
            'desc'    => __( 'Automatically deletes expired coupons (Only those created by this plugin)', 'yith-woocommerce-review-for-discounts' ),
            'default' => 'no',
            'id'      => 'ywsfd_coupon_purge'
        ),

        'ywsfd_coupon_end' => array(
            'type' => 'sectionend',
        ),
    )
);