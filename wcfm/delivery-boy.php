<?php
// prevent direct access
defined( 'ABSPATH' ) or exit;

use GRON\Utils;

/**
  * Delivery boy related logic
*/

add_action( 'wcfm_delivery_boys_manage' , function( $boy_id ) {

  if( !get_user_meta( $boy_id, '_wcfm_vendor' ) ) {
    // Link delivery boy with admin, if no vendor found
    update_user_meta( $boy_id, '_gron_admin', 'yes' );
  }else {
    // Remove delivery boy from admin, as it has a vendor already
    delete_user_meta( $boy_id, '_gron_admin' );
  }

});
