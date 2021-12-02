<?php
namespace GRON;
defined('ABSPATH') or exit;

class Utils {

  /**
  * Remove seconds from time
  * @param Time $time 00:00:00
  * @return Time $new_time 00:00
  */
  public static function time_format( $time ) {
    $new_time = date('h:i A', strtotime( $time ) );
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
  * Default is 5 minutes
  * @param Int $vendor_id [Optional] ID of the current vendor
  * @return Int the time limit minutes
  */
  public static function get_dn_boradcast_time_limit( $vendor_id = null ) {

    $default_time_limit = 5; // Default time limit is 5 minute

    if( !$vendor_id ) {

      // for admin

      $time_limit = get_option( '_gron_dn_broadcast_time_limit' );

      // if the time limit is not set up then default time limit will return
      if( !$time_limit ) return $default_time_limit;
      else return $time_limit;

    }elseif( $vendor_id > 0 ) {

      // for vendor
      $time_limit = get_user_meta( $vendor_id, '_gron_dn_broadcast_time_limit' );

      // if the time limit is not set up then default time limit will return
      if( empty( $time_limit ) ) return $default_time_limit;
      else return $time_limit[0];

    }

  }

  /**
  * Get current user role
  * @return String role of the user
  */
  public static function current_user_role() {

    $current_user = wp_get_current_user();

    if( !empty( $current_user->roles ) ) {
      return $current_user->roles[0];
    }

  }


}
