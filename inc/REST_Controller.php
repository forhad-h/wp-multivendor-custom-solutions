<?php
namespace GRON;
defined( 'ABSPATH' ) or exit;

use GRON\SQLite;
use GRON\Services;

use WP_REST_Controller;
use WP_REST_Server;
use WP_REST_Response;
use WP_Error;

class REST_Controller extends WP_REST_Controller {

  /** @var SQLite $sqlite instance of SQLite */
  private $sqlite;

  /** @var String $current_date_time curent date and time*/
  private $current_date_time;

  public function __construct() {

    $namespace = 'gron/v1';
    $this->sqlite = new SQLite();

    $this->current_date_time = date('Y-m-d H:i:s');

    register_rest_route(
      $namespace, '/delivery_notifications', array(
        array(
          'methods'             => WP_REST_Server::READABLE,
          'callback'            => array( $this, 'get_delivery_notifications' ),
          'permission_callback' => array( $this, 'permission_check' )
        ),
        array(
          'methods'             => WP_REST_Server::EDITABLE,
          'callback'            => array( $this, 'accept_delivery_notification' ),
          'permission_callback' => array( $this, 'permission_check' )
        ),
        array(
          'methods'             => WP_REST_Server::DELETABLE,
          'callback'            => array( $this, 'reject_delivery_notification' ),
          'permission_callback' => array( $this, 'permission_check' )
        )
      )
    );

  }

  /**
   * Get delivery notifications
   * @param WP_REST_Request $request
   * @return WP_Error|WP_REST_Response
  */
  public function get_delivery_notifications( $request ) {

    $user_id  = esc_sql( $request[ 'user_id' ] );
    $order_id = esc_sql( $request[ 'order_id' ] );
    $get_for  = esc_sql( $request[ 'get_for' ] );
    $status   = esc_sql( $request[ 'status' ] );

    $notifications = $this->sqlite->get_delivery_notifications( $user_id, $order_id, $get_for, $status );

    $data = $this->format_notifications( $notifications, $get_for );

    return new WP_REST_Response( $data, 200 );

    return new WP_Error( 'not-found', __( 'Delivery Notifications not found!', 'gron-custom' ), array( 'status' => 500 ) );

  }

  /**
   * Accept delivery notification
   * @param WP_REST_Request $request
   * @return WP_Error|WP_REST_Response
  */
  public function accept_delivery_notification( $request ) {

    $dn_id   = esc_sql( $request['dn_id'] );

    $is_accepted = $this->sqlite->is_accepted( $dn_id  );

    if( !$is_accepted ) {

      $update = $this->sqlite->update_delivery_notification( $dn_id );

      if( $update ) {

        $notification_info = $this->sqlite->get_delivery_notification( $dn_id );

        $associated_boy_ids =  $this->sqlite->get_boy_ids_with_order_id( $notification_info['order_id'] );

        // Get user information
        $user_info = Utils::user_info( $notification_info['boy_id'] );

        // Notify other associated delivery boy
        Services::pusher()->trigger( 'delivery-boy', 'delivery-accepted', array(
          'dn_id'              => $dn_id,
          'accepted_by_id'     => $notification_info['boy_id'],
          'accepted_by_name'   => $user_info['display_name'],
          'associated_boy_ids' => $associated_boy_ids
        ) );

        // Notify 'admin' or 'vendor
        Services::pusher()->trigger( $notification_info['manage_by'], 'delivery-accepted', array(
          'dn_id'            => $dn_id,
          'vendor_id'        => $notification_info['vendor_id'],
          'accepted_by_id'   => $notification_info['boy_id'],
          'accepted_by_name' => $user_info['display_name'],
        ) );

        return new WP_REST_Response( $notification_info, 200 );
      }

    }

    return new WP_Error( 'cant-update', __( 'Delivery Notification cannot be updated!', 'gron-custom' ), array( 'status' => 500 ) );

  }

  /**
   * Delete delivery notification
   * @param WP_REST_Request $request
   * @return WP_Error|WP_REST_Response
  */
  public function reject_delivery_notification( $request ) {

    $dn_id   = esc_sql( $request['dn_id'] );

    $delete = $this->sqlite->delete_delivery_notification( $dn_id );

    if( $delete ) {
      return new WP_REST_Response( $delete, 200 );
    }

    return new WP_Error( 'cant-delete', __( 'Delivery Notifications cannot be deleted!', 'gron-custom' ), array( 'status' => 500 ) );

  }

  /**
   * Check if the user is valid
   * @param WP_REST_Request $request
   * @return Boolean
  */
  public function permission_check( $request ) {

    $current_user = wp_get_current_user();

    if( !$current_user->ID ) return false;

    $accepted_roles = array( 'administrator', 'wcfm_vendor', 'wcfm_delivery_boy' );

    if( !empty( array_intersect( $accepted_roles, $accepted_roles ) ) ) return true;

    return false;

  }


  /**
  * Calculate Availability Time or Remaining Broadcast time
  * @param String $manage_by Delivery manage by - 'admin' or 'vendor'
  * @param String $created_at DateTime when the the entry is created
  * @return Int $time Availability in seconds
  */
  private function calculate_availability_time( $manage_by, $created_at ) {

    $broadcast_time_limit = 0;

    if( $manage_by === 'admin' ) {
      $broadcast_time_limit = Utils::get_dn_boradcast_time_limit();
    }elseif( $manage_by === 'vendor' ) {
      $broadcast_time_limit = Utils::get_dn_boradcast_time_limit( $vendor_id );
    }

    // Created before in seconds
    $created_before = strtotime( $this->current_date_time ) - strtotime( $created_at );

    // Time in seconds
    $time = ( $broadcast_time_limit * 60 ) - $created_before;

    return $time > 0 ? (int) $time : 0;

  }

  /**
  * Format data to return notifications
  * @param Array $data_raw Array of all raw data
  * @param Array $get_for Data get form 'admin', 'vendor' or 'delivery_boy'
  * @return Array $data_formatted Array of formatted data
  */
  private function format_notifications( $data_raw, $get_for ) {

    $data_formatted = array();

    foreach( $data_raw as $data ) {

      $site_url        = get_site_url();
      $vendor_id       = $data['vendor_id'];
      $order_id        = $data['order_id'];
      $delivery_boy_id = $data['boy_id'];
      $manage_by       = $data['manage_by'];
      $create_at       = $data['created_at'];

      $data_item['dn_id'] = $data['dn_id'];

      $data_item['store_name'] = get_user_meta( $vendor_id, 'store_name', true );

      if( $get_for !== 'admin' ) {
        // do not expose vendor manage URL for delivery boy and vendor
        $data_item['store_link'] = "{$site_url}/store-manager/vendors-manage/{$vendor_id}/";
      }

      if( $get_for === 'admin' || $get_for === 'vendor' ) {

        if( $delivery_boy_id ) {
          $user_data = get_userdata( $delivery_boy_id );

          $data_item['accepted_by_name'] = $user_data->display_name;
          $data_item['accepted_by_link'] = "{$site_url}/store-manager/delivery-boys-stats/{$delivery_boy_id}/";
        }

      }

      $data_item['order_id'] = $order_id;
      $data_item['order_link'] = "{$site_url}/store-manager/orders-details/{$order_id}/";

      $data_item['delivery_day'] = get_post_meta( $order_id, 'gron_deliver_day_' . $vendor_id, true );
      $data_item['delivery_time'] = get_post_meta( $order_id, 'gron_deliver_time_' . $vendor_id, true );

      $data_item['status'] = $data['status'];
      $data_item['status_msg'] = $data['status_msg'];

      $availability_time = $this->calculate_availability_time( $manage_by, $create_at );

      $data_item['availability_time'] = $availability_time;

      // Provide information about the delivery boy who accepted
      if( $data['accepted_by'] ) {
        // Get user information
        $user_info = Utils::user_info( $data['boy_id'] );
        
        $data_item['accepted_by_id'] = $data['boy_id'];
        $data_item['accepted_by_name'] = $user_info['display_name'];

      }

      array_push( $data_formatted, $data_item );
    }

    return $data_formatted;

  }

}
