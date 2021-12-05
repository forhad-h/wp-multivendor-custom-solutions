<?php
// Prevent Direct notification
defined( 'ABSPATH' ) or exit;

/**
*
* Checker of delivery notifications
* from SQLite Database - delivery_notifications.db
*
* Clean up the unaccepted delivery notifications
* Change the status and status message of accepted and unaccepted notifications
*/

use GRON\SQLite;


function gron_dn_checker() {

  $sqlite = new SQLite();

  $notifications = $sqlite->get_all_delivery_notifications();

  $vendor_ids = array();
  $order_ids  = array();

  foreach( $notifications as $data ) {

    $vendor_id = $data['vendor_id'];
    $order_id  = $data['order_id'];

    if(
      in_array( $vendor_id, $vendor_ids ) &&
      in_array( $order_id, $order_ids )
    ) {

    }

  }

}
