<?php
// prevent direct access
defined( 'ABSPATH' ) or exit;

use GRON\Utils;

/**
  * Delivery boy related logic
*/

add_action( 'wcfm_delivery_boys_manage' , function( $boy_id ) {

  Utils::log( get_user_meta( $boy_id, '_wcfm_vendor' ) );
});
