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
    exit; // Exit if accessed directly
}

/**
 * Implements session for YWSFD plugin
 *
 * @class   YWSFD_Session
 * @package Yithemes
 * @since   1.0.0
 * @author  Your Inspiration Themes
 *
 */
class YWSFD_Session {

    public $session = array();

    /**
     * Constructor
     *
     * @since   1.0.0
     * @return  mixed
     * @author  Alberto Ruggiero
     */
    public function __construct() {

        add_action( 'init', array( $this, 'init' ), - 1 );

    }

    /**
     * Set the instance of YWSFD_Session
     *
     * @since   1.0.0
     * @return  array
     * @author  Alberto Ruggiero
     */
    public function init() {

        $this->session = isset( $_SESSION['yith_wsfd'] ) && is_array( $_SESSION['yith_wsfd'] ) ? $_SESSION['yith_wsfd'] : array();

        return $this->session;

    }

    /**
     * Get a session variable
     *
     * @since   1.0.0
     *
     * @param   $key
     *
     * @return  string
     * @author  Alberto Ruggiero
     */
    public function get( $key ) {

        return isset( $this->session[$key] ) ? maybe_unserialize( $this->session[$key] ) : false;

    }

    /**
     * Set a session variable
     *
     * @since   1.0.0
     *
     * @param   $key
     * @param   $value
     *
     * @return  mixed
     * @author  Alberto Ruggiero
     */
    public function set( $key, $value ) {

        $this->session[$key]   = $value;
        $_SESSION['yith_wsfd'] = $this->session;

        return $this->session[$key];
    }

    /**
     * Destroy Session
     *
     * @since   1.0.0
     * @return  void
     * @author  Alberto Ruggiero
     */
    public function destroy_session() {

        if ( isset( $_SESSION['yith_wsfd'] ) ) {

            unset( $_SESSION['yith_wsfd'] );

        }

    }

}