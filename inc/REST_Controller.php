<?php
namespace GRON;
defined( 'ABSPATH' ) or exit;

use GRON\SQLite;
use WP_REST_Controller;
use WP_REST_Server;
use WP_REST_Response;

class REST_Controller extends WP_REST_Controller {

  /** @var SQLite $sqlite instance of SQLite */
  private $sqlite;

  public function __construct() {

    $namespace = 'gron/v1';
    $this->sqlite = new SQLite();

    register_rest_route(
      $namespace, '/delivery_notifications',
      array(
        'methods'             => WP_REST_Server::READABLE,
        'callback'            => array( $this, 'delivery_notifications' ),
        'permission_callback' => array( $this, 'permission_check' )
      )
    );

  }

  /**
   * Get delivery notifications
   * @param WP_REST_Request $request
   * @return WP_Error|WP_REST_Response
  */
  public function delivery_notifications( $request ) {

    $user_id  = esc_sql( $request[ 'user_id' ] );
    $order_id = esc_sql( $request[ 'order_id' ] );
    $get_for  = esc_sql( $request[ 'get_for' ] );
    $status   = esc_sql( $request[ 'status' ] );
    $data = array();

    $notifications = $this->sqlite->get_delivery_notifications( $user_id, $order_id, $get_for, $status );

    foreach( $notifications as $notification ) {

      $site_url = get_site_url();
      $vendor_id = $notification['vendor_id'];
      $order_id = $notification['order_id'];
      $delivery_boy_id = $notification['boy_id'];

      $data_item['store_name'] = get_user_meta( $vendor_id, 'store_name', true );

      if( $get_for !== 'admin' ) {
        // do not expose vendor manage URL for delivery boy and vendor
        $data_item['store_link'] = "{$site_url}/store-manager/vendors-manage/{$vendor_id}/";
      }

      if( $get_for === 'admin' || $get_for === 'vendor' ) {
        $data_item['status'] = $notification['status'];
        $data_item['status_msg'] = $notification['status_msg'];

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

      array_push( $data, $data_item );
    }

    return new WP_REST_Response( $data, 200 );

    return new WP_Error( 'not-found', __( 'Delivery Notifications not found!', 'gron-custom' ), array( 'status' => 500 ) );

  }

  /**
   * Check if the user is valid
   * @param WP_REST_Request $request
   * @return Boolean
  */
  public function permission_check( $request ) {

    $current_user = wp_get_current_user();

    if( !$current_user->ID ) return false;

    $get_for = $request['get_for'];

    $admin_role = 'administrator';
    $vendor_role = 'wcfm_vendor';
    $delivery_boy_role = 'wcfm_delivery_boy';

    if( $get_for === 'admin' ) {

      if( in_array( $admin_role, $current_user->roles ) ) return true;

    }elseif( $get_for === 'vendor' ) {

      if( in_array( $vendor_role, $current_user->roles ) ) return true;

    }elseif( $get_for === 'delivery_boy' ) {

      if( in_array( $delivery_boy_role, $current_user->roles ) ) return true;

    }

    return false;

  }

}
