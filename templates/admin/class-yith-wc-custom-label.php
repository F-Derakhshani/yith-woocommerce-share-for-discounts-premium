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

if ( ! class_exists( 'YITH_WC_Custom_Label' ) ) {

	/**
	 * Outputs a custom label template in plugin options panel
	 *
	 * @class   YITH_WC_Custom_Label
	 * @package Yithemes
	 * @since   1.0.0
	 * @author  Your Inspiration Themes
	 *
	 */
	class YITH_WC_Custom_Label {

		/**
		 * Single instance of the class
		 *
		 * @var \YITH_WC_Custom_Label
		 * @since 1.0.0
		 */
		protected static $instance;

		/**
		 * Returns single instance of the class
		 *
		 * @return \YITH_WC_Custom_Label
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

			add_action( 'woocommerce_admin_field_yith-wc-label', array( $this, 'output' ) );

		}

		/**
		 * Outputs a custom label template in plugin options panel
		 *
		 * @since   1.0.0
		 *
		 * @param   $option
		 *
		 * @author  Alberto Ruggiero
		 * @return  void
		 */
		public function output( $option ) {

			$has_title = ( isset( $option['title'] ) && $option['title'] != '' );

			?>
			<tr valign="top">

				<?php if ( $has_title ): ?>

					<th scope="row" class="titledesc">
						<?php echo $option['title']; ?>
					</th>

				<?php endif; ?>

				<td class="forminp" <?php echo( $has_title ? '' : 'colspan="2"' ) ?>>

					<?php echo( isset( $option['content'] ) ? $option['content'] : '' ); ?>
					<span <?php echo( isset( $option['label_id'] ) ? 'id="' . $option['label_id'] . '"' : '' ); ?> class="description"><?php echo $option['desc']; ?></span>

				</td>

			</tr>
			<?php
		}

	}

	/**
	 * Unique access to instance of YITH_WC_Custom_Label class
	 *
	 * @return \YITH_WC_Custom_Label
	 */
	function YITH_WC_Custom_Label() {

		return YITH_WC_Custom_Label::get_instance();

	}

	new YITH_WC_Custom_Label();

}