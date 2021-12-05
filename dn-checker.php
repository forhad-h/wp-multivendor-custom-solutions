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

    $dn_id     = $data['dn_id'];
    $vendor_id = $data['vendor_id'];
    $order_id  = $data['order_id'];
    $manage_by  = $data['manage_by'];
    $create_at = $data['created_at'];

    $availability_time = Utils::calculate_availability_time( $manage_by, $create_at, $vendor_id );

    // Terminate if the the notification is still available to accept/reject
    if( $availability_time > 0 ) return;

    if( $manage_by === 'admin' ) {

      // Check if the entry is duplicate based on order_id
      $is_duplicate = in_array( $order_id, $order_ids );

    }elseif( $manage_by === 'vendor' ) {

      // Check if the entry is duplicate based on vendor_id and order_id
      $is_duplicate = in_array( $vendor_id, $vendor_ids ) &&
      in_array( $order_id, $order_ids );

    }

    if( $is_duplicate ) {

      $sqlite->update_delivery_notification( array(
        'dn_id'        => $dn_id,
        'status'       => 'expired',
        'reset_boy_id' => true
      ) );

    }else {
      $vendor_ids[] = $vendor_id;
      $order_id[] = $order_id;

      $sqlite->update_delivery_notification( array(
        'dn_id'        => $dn_id,
        'status'       => 'expired',
        'status_msg'   => 'No one accepted!',
        'reset_boy_id' => true
      ) );

    }

  }

  // Remove all expired entry
  $sqlite->delete_delivery_notification( null, 'expired' );

}
