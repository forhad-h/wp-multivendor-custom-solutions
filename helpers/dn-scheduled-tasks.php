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
use GRON\Utils;
use GRON\Services;

function gron_dn_scheduled_tasks_func() {

  $sqlite = new SQLite();

  $notifications = $sqlite->get_all_delivery_notifications();

  $vendor_ids = array();
  $order_ids  = array();
  $boy_and_vendor_ids = array();

  foreach( $notifications as $data ) {

    $dn_id       = $data['dn_id'];
    $vendor_id   = $data['vendor_id'];
    $order_id    = $data['order_id'];
    $boy_id      = $data['boy_id'];
    $manage_by   = $data['manage_by'];
    $create_at   = $data['created_at'];
    $is_accepted = $data['is_accepted'];
    $status      = $data['status'];

    $availability_time = Utils::calculate_availability_time( $manage_by, $create_at, $vendor_id );

    // Terminate if the the notification is still available to accept/reject
    if( $availability_time > 0 ) continue;

    // Terminate if the boy_id reseted
    if( $boy_id == 0 ) continue;

    // do nothing for accepted entries, for now
    if( $is_accepted && $status === 'accepted' ) {
      continue;
    }

    /* If the order was accepted then update the status */
    if( $is_accepted && $status === 'pending' ) {

      // Update the order notifications as accepted
      $update = $sqlite->update_delivery_notification( array(
        'dn_id'        => $dn_id,
        'status'       => 'accepted'
      ) );

      // Update others notification with same vendor_id and order_id
      $sqlite->update_delivery_notification( array(
        'vendor_id'  => $vendor_id,
        'order_id'   => $order_id,
        'status'     => 'expired',
        'status_msg' => 'Expired!',
      ) );

      if( $update ) {

        // Notify other associated delivery boy
        Services::pusher()->trigger(
          'delivery-boy',
          'lock-accepted-delivery',
          array( 'boy_id' => $boy_id )
        );

        // Notify to admin or vendor
        Services::pusher()->trigger(
          $manage_by,
          'lock-accepted-delivery',
          array( 'vendor_id' => $vendor_id )
        );

      }

    }elseif( !$is_accepted && $status === 'pending' ) {

      /* If the order was not accepted by anyone,
        and expire the broadcast time limit */
      $update_option = array(
        'dn_id'        => $dn_id,
        'status'       => 'expired',
        'reset_boy_id' => true
      );

      if( $manage_by === 'admin' ) {

        // Check if the entry is duplicate based on order_id
        $is_duplicate = in_array( $order_id, $order_ids );

      }elseif( $manage_by === 'vendor' ) {

        // Check if the entry is duplicate based on vendor_id and order_id
        $is_duplicate = in_array( $vendor_id, $vendor_ids ) &&
        in_array( $order_id, $order_ids );

      }

      if( $is_duplicate ) {

        // Update the status to expired
        // then we easily consider it as redundant and clean all entries
        $update_option['status'] = 'expired';

        // Simply change the message for duplicate based on order
        $update_option['status_msg'] = 'Expired!';

      }else {
        // Store vendor ID and order ID to track duplication based on order
        $vendor_ids[] = $vendor_id;
        $order_ids[] = $order_id;

        // Fill $boy_and_vendor_ids array with boy_id and vendor_id
        // to refresh the appropriate UI via pusher
        $boy_and_vendor_ids[] = (Int) $boy_id;
        $boy_and_vendor_ids[] = (Int) $vendor_id;

        // Keep the status pending to show admin or vendor
        $update_option['status'] = 'pending';

        // Change the message that no one accepted
        $update_option['status_msg'] = 'No one accepted!';

      }

      // Finally update info in database
      $sqlite->update_delivery_notification( $update_option );

    }

  }

  if( !empty( $boy_and_vendor_ids ) ) {

    // Notify other associated delivery boy
    Services::pusher()->trigger(
      'delivery-boy',
      'no-one-accepted',
      array( 'boy_and_vendor_ids' => array_unique( $boy_and_vendor_ids ) )
    );

    // Notify to admin or vendor
    Services::pusher()->trigger(
      $manage_by,
      'no-one-accepted',
      array( 'boy_and_vendor_ids' => array_unique( $boy_and_vendor_ids ) )
    );

  }

  // Remove all expired entry
  $sqlite->delete_delivery_notification( null, 'expired' );

}
