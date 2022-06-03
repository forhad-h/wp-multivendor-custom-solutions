<?php
namespace GRON;
// Prevent direct access
defined('ABSPATH') or exit;

use GRON\MySQL;

class Utils {

  /**
  * Remove seconds from time
  * @param String $time 00:00:00
  * @return String $new_time 00:00
  */
  public static function time_format( $time ) {
    $new_time = date_i18n('h:i A', strtotime( $time ) );
    return $new_time;
  }

  /**
  * Make Strings with Underscore to Capitalize
  * @param String $string 'something_like_this'
  * @return String $str 'Something LIke This'
  */
  public static function underscore_to_capitalize( $string ) {
    $str = ucwords( str_replace( '_', ' ', $string ) );
    return $str;
  }

  /**
  * Check if Google Map API key exists
  * @return Boolean $str 'Something LIke This'
  */
  public static function has_map_api_key() {
    global $WCFMmp;

    $marketplace_options = $WCFMmp->wcfmmp_marketplace_options;
    $has_api = !empty( $marketplace_options['wcfm_google_map_api'] ) ? true : false;

    return $has_api;
  }

  /**
  * Write log in a file
  * When normal output with var_dump/echo/print is not possible
  * Use it
  * @param $content Anything to write
  * @return void
  */
  public static function log( $content ) {
    $filename = GRON_DIR_PATH . 'dev_log.txt';
    file_put_contents($filename, $content);
  }

  /**
  * Check if the user is business owner or admin of GRON
  * @return Boolean
  */
  public static function is_admin() {

    $current_user = wp_get_current_user();

    $is_admin = in_array( 'administrator', $current_user->roles );

    return !$is_admin ? false : true;

  }

  /**
  * Check if the user is a vendor
  * With provided funtion from WCFM
  * @return Boolean
  */
  public static function is_vendor() {

    return wcfm_is_vendor();

  }

  /**
  * Get delivery by sellter setting
  * If the setting are note set up
  * Default will be yes
  *
  * @return Boolean
  */
  public static function is_delivery_by_seller() {

    $setting = get_option( '_gron_delivery_by_seller' );

    // If the setting is not set up, then default is true
    if( !$setting ) return true;
    elseif( $setting === 'yes' ) return true;
    elseif( $setting === 'no' ) return false;

  }

  /**
  * Get delivery by me (Vendor) setting
  * If the setting are note set up
  * Default will be yes
  *
  * @param Int $vendor_id ID of the current vendor
  * @return Boolean
  */
  public static function is_delivery_by_me( $vendor_id ) {

    $setting = get_user_meta( $vendor_id, '_gron_delivery_by_me' );

    // If the setting is not set up, then default is true
    if( empty( $setting ) ) return true;
    elseif( $setting[0] === 'yes' ) return true;
    elseif( $setting[0] === 'no' ) return false;

  }

  /**
  * Get boradcast time limit
  *
  * Default is 10 minutes
  * @param Int $vendor_id [Optional] ID of the current vendor
  * @return Int the time limit minutes
  */
  public static function get_dn_boradcast_time_limit( $vendor_id = null ) {

    $default_time_limit = 10; // Default time limit is 10 minute

    // For admin
    if( !$vendor_id ) $time_limit = get_option( '_gron_dn_broadcast_time_limit' );

    // For vendor
    elseif( $vendor_id > 0 ) $time_limit = get_user_meta( $vendor_id, '_gron_dn_broadcast_time_limit', true );

    // if the time limit is not set up then default time limit will return
    return !$time_limit ? $default_time_limit : $time_limit;

  }

  /**
  * Get current user role
  * @param $user_id [Options] ID of the user, if not provided then current user information will return
  * @return Array $info User information
  */
  public static function user_info( $user_id = null ) {

    $user = null;

    if( !$user_id ) {
      $user = wp_get_current_user();
    }else {
      $user = get_userdata( $user_id );
    }

    if( $user && $user->ID ) {

      $info = array(
        'id'           => $user->ID,
        'role'         => $user->roles[0],
        'display_name' => $user->display_name
      );

      return $info;

    }

  }

  /**
  * Calculate Availability Time or Remaining Broadcast time
  * @param String $manage_by Delivery manage by - 'admin' or 'vendor'
  * @param String $created_at DateTime when the the entry is created
  * @param String $vendor_id ID of the vendor
  * @return Int $time Availability in seconds
  */
  public static function calculate_availability_time( $manage_by, $created_at, $vendor_id ) {

    $current_date_time = current_time('Y-m-d H:i:s');

    $broadcast_time_limit = 0;

    if( $manage_by === 'admin' ) {
      $broadcast_time_limit = self::get_dn_boradcast_time_limit();
    }elseif( $manage_by === 'vendor' ) {
      $broadcast_time_limit = self::get_dn_boradcast_time_limit( $vendor_id );
    }

    // Created before in seconds
    $created_before = strtotime( $current_date_time ) - strtotime( $created_at );

    // Time in seconds
    $time = ( $broadcast_time_limit * 60 ) - $created_before;

    return $time > 0 ? (int) $time : 0;

  }

  /**
  * Check if the specific vendor is allowed to manage delivery
  * @param Int $vendor_id ID of the Vendor
  *
  * @return Boolean
  */
  public static function is_allowed_the_vendor_for_dm( $vendor_id ) {

    $allowed_vendors = get_option( '_gron_delivery_allowed_vendors' );
    $is_checked = false;

    if( !$allowed_vendors ) {
      // If the settings not set yet
      // Then make all chcked
      $is_checked = true;
    }else {
      if( in_array( $vendor_id, $allowed_vendors ) ) {
        $is_checked = true;
      }
    }

    return $is_checked;

  }

  /**
  * Get all vendors Location Latitude and Longitude
  *
  * @return Boolean
  */
  public static function get_all_vendors_info() {

    $vendors = array();

    $vendor_role = 'wcfm_vendor';

    $args = array(
      'role__in'     => array( $vendor_role ),
      'orderby'      => 'ID',
      'order'        => 'ASC',
      'fields'       => "ID"
     );

    $vendor_ids = get_users( $args );

    foreach( $vendor_ids as $vendor_id ) {

      $store_name = get_user_meta( $vendor_id, 'store_name', true );

      $vendor_data = get_user_meta( $vendor_id, 'wcfmmp_profile_settings', true );

      $store_user  = wcfmmp_get_store( absint( $vendor_id ) );
      $store_address     = $store_user->get_address_string();

      $store_location   = isset( $vendor_data['store_location'] ) ? esc_attr( $vendor_data['store_location'] ) : '';
      $store_lat = isset( $vendor_data['store_lat'] ) ? esc_attr( $vendor_data['store_lat'] ) : null;
      $store_lng = isset( $vendor_data['store_lng'] ) ? esc_attr( $vendor_data['store_lng'] ) : null;

      $vendors[] = array(
        'vendor_id' => $vendor_id,
        'store_name' => $store_name,
        'store_address' => $store_address,
        'store_location' => $store_location,
        'store_latitude' => $store_lat,
        'store_longitude' => $store_lng
      );

    }

    return $vendors;

  }


  /**
   * Get Delivery Days
   * @param Int $vendor_id ID of the vendor
   * @return Array options of Delivery Day field
   */
  public static function get_delivery_days($vendor_id, $format = 'raw')
  {

    $mysql = new MySQL();

    $shop_timings = $mysql->get_shop_timings(true, $vendor_id);

    foreach ($shop_timings as $timing) {

      $key = $timing->day_name;
      $value = ucfirst($timing->day_name);

      $options[$key] = $value;
    }

    return $options;
  }

  /**
   * Get delivery times
   * @param Int $vendor_id ID of the vendor
   * @return Array options of delivery time field
   */
  public static function get_delivery_times($vendor_id)
  {

    $mysql = new MySQL();

    $slots = $mysql->get_delivery_slots($vendor_id);

    foreach ($slots as $slot) {
      $time_from = Utils::time_format($slot->time_from);
      $time_to = Utils::time_format($slot->time_to);
      $key = $value = $time_from . '-' . $time_to;

      $options[$key] = $value;
    }

    return $options;
  }



}
