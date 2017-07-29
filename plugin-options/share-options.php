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

return array(
	'share' => array(

		'ywsfd_share_start'                 => array(
			'name' => __( 'Sharing Settings', 'yith-woocommerce-share-for-discounts' ),
			'type' => 'title',
		),
		'ywsfd_share_title'                 => array(
			'title'   => __( 'Box title before sharing', 'yith-woocommerce-share-for-discounts' ),
			'id'      => 'ywsfd_share_title',
			'default' => __( 'Share and get your discount!', 'yith-woocommerce-share-for-discounts' ),
			'type'    => 'text',
			'class'   => 'ywsfd-text',
			'desc'    => __( 'Title showed above the sharing buttons', 'yith-woocommerce-share-for-discounts' )
		),
		'ywsfd_share_title_after'           => array(
			'title'   => __( 'Box title after sharing', 'yith-woocommerce-share-for-discounts' ),
			'id'      => 'ywsfd_share_title_after',
			'default' => __( 'Thank you for sharing!', 'yith-woocommerce-share-for-discounts' ),
			'type'    => 'text',
			'class'   => 'ywsfd-text',
			'desc'    => __( 'Title showed after the sharing', 'yith-woocommerce-share-for-discounts' )
		),
		'ywsfd_share_text_after'            => array(
			'title'   => __( 'Message after sharing', 'yith-woocommerce-share-for-discounts' ),
			'id'      => 'ywsfd_share_text_after',
			'default' => __( 'Your discount has been activated and applied to your shopping cart.', 'yith-woocommerce-share-for-discounts' ),
			'type'    => 'yith-wc-textarea',
			'class'   => 'ywsfd-textarea',
			'desc'    => __( 'Text that replaces the buttons after the sharing', 'yith-woocommerce-share-for-discounts' )
		),
		'ywsfd_share_product_enable'        => array(
			'name'              => __( 'Show on product page', 'yith-woocommerce-share-for-discounts' ),
			'type'              => 'checkbox',
			'desc'              => '',
			'custom_attributes' => array(
				'class' => 'ywsfd-checkbox'
			),
			'id'                => 'ywsfd_share_product_enable',
			'default'           => 'yes',
		),
		'ywsfd_share_product_position'      => array(
			'name'    => __( 'Product page position', 'yith-woocommerce-share-for-discounts' ),
			'id'      => 'ywsfd_share_product_position',
			'default' => '2',
			'type'    => 'select',
			'class'   => 'ywsfd-select',
			'desc'    => __( 'The position where the sharing buttons are showed in product detail pages.', 'yith-woocommerce-share-for-discounts' ),
			'options' => array(
				'0' => __( 'Before title', 'yith-woocommerce-share-for-discounts' ),
				'1' => __( 'After price', 'yith-woocommerce-share-for-discounts' ),
				'2' => __( 'Before "Add to cart"', 'yith-woocommerce-share-for-discounts' ),
				'3' => __( 'Before tabs', 'yith-woocommerce-share-for-discounts' ),
				'4' => __( 'Between tabs and related products', 'yith-woocommerce-share-for-discounts' ),
				'5' => __( 'After related products', 'yith-woocommerce-share-for-discounts' )
			),
		),
		'ywsfd_share_product_onsale_enable' => array(
			'name'              => __( 'Enable on on-sale products', 'yith-woocommerce-share-for-discounts' ),
			'type'              => 'checkbox',
			'desc'              => '',
			'custom_attributes' => array(
				'class' => 'ywsfd-checkbox'
			),
			'id'                => 'ywsfd_share_product_onsale_enable',
			'default'           => 'yes',
		),
		'ywsfd_share_checkout_enable'       => array(
			'name'              => __( 'Show on checkout page', 'yith-woocommerce-share-for-discounts' ),
			'type'              => 'checkbox',
			'desc'              => '',
			'custom_attributes' => array(
				'class' => 'ywsfd-checkbox'
			),
			'id'                => 'ywsfd_share_checkout_enable',
			'default'           => 'no',
		),
		'ywsfd_share_checkout_position'     => array(
			'name'    => __( 'Checkout position', 'yith-woocommerce-share-for-discounts' ),
			'id'      => 'ywsfd_share_checkout_position',
			'default' => '0',
			'type'    => 'select',
			'class'   => 'ywsfd-select',
			'desc'    => __( 'The position where share buttons are showed in checkout page.', 'yith-woocommerce-share-for-discounts' ),
			'options' => array(
				'0' => __( 'Before customer details', 'yith-woocommerce-share-for-discounts' ),
				'1' => __( 'After customer details', 'yith-woocommerce-share-for-discounts' ),
			),
		),
		'ywsfd_share_cart_enable'           => array(
			'name'              => __( 'Show on cart page', 'yith-woocommerce-share-for-discounts' ),
			'type'              => 'checkbox',
			'desc'              => '',
			'custom_attributes' => array(
				'class' => 'ywsfd-checkbox'
			),
			'id'                => 'ywsfd_share_cart_enable',
			'default'           => 'no',
		),
		'ywsfd_share_cart_position'         => array(
			'name'    => __( 'Cart page position', 'yith-woocommerce-share-for-discounts' ),
			'id'      => 'ywsfd_share_cart_position',
			'default' => '0',
			'type'    => 'select',
			'class'   => 'ywsfd-select',
			'desc'    => __( 'The position where share buttons are showed in cart page.', 'yith-woocommerce-share-for-discounts' ),
			'options' => array(
				'0' => __( 'Before cart', 'yith-woocommerce-share-for-discounts' ),
				'1' => __( 'Cart collaterals', 'yith-woocommerce-share-for-discounts' ),
				'2' => __( 'After cart', 'yith-woocommerce-share-for-discounts' ),
			),
		),
		'ywsfd_share_end'                   => array(
			'type' => 'sectionend',
		),

		'ywsfd_custom_start'   => array(
			'name' => __( 'Custom Sharing Settings', 'yith-woocommerce-share-for-discounts' ),
			'type' => 'title',
		),
		'ywsfd_custom_url'     => array(
			'title'   => __( 'URL to share', 'yith-woocommerce-share-for-discounts' ),
			'id'      => 'ywsfd_custom_url',
			'default' => '',
			'type'    => 'text',
			'class'   => 'ywsfd-text',
			'desc'    => __( 'If not specified, the page URL will be used', 'yith-woocommerce-share-for-discounts' )
		),
		'ywsfd_custom_message' => array(
			'title'   => __( 'Custom message', 'yith-woocommerce-share-for-discounts' ),
			'id'      => 'ywsfd_custom_message',
			'default' => '',
			'type'    => 'yith-wc-textarea',
			'class'   => 'ywsfd-textarea',
			'desc'    => __( 'If not specified, the page name will be used (available only for Twitter & Email sharing)', 'yith-woocommerce-share-for-discounts' )
		),
		'ywsfd_custom_end'     => array(
			'type' => 'sectionend',
		),

		'ywsfd_fbmeta_start'       => array(
			'name' => __( 'Facebook OpenGraph Meta Defaults', 'yith-woocommerce-share-for-discounts' ),
			'type' => 'title',
		),
		'ywsfd_fbmeta_title'       => array(
			'title' => __( 'Default Title', 'yith-woocommerce-share-for-discounts' ),
			'id'    => 'ywsfd_fbmeta_title',
			'type'  => 'text',
			'class' => 'ywsfd-text',
		),
		'ywsfd_fbmeta_description' => array(
			'title' => __( 'Default Description', 'yith-woocommerce-share-for-discounts' ),
			'id'    => 'ywsfd_fbmeta_description',
			'type'  => 'yith-wc-textarea',
			'class' => 'ywsfd-textarea',
		),
		'ywsfd_fbmeta_image'       => array(
			'title' => __( 'Default Image', 'yith-woocommerce-share-for-discounts' ),
			'id'    => 'ywsfd_fbmeta_image',
			'type'  => 'ywsfd-image-upload',
			'desc'  => __( 'Image size must be at least 200x200px', 'yith-woocommerce-share-for-discounts' )
		),
		'ywsfd_fbmeta_end'         => array(
			'type' => 'sectionend',
		),

	)
);