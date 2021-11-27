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

    $allowed_roles = array( 'administrator' );

    $is_allowed = array_intersect( $current_user->roles, $allowed_roles);

    return !$is_allowed ? false : true;

  }

  /**
  * Check if the user is a vendor
  * With provided funtion from WCFM
  * @return Boolean
  */
  public static function is_vendor() {

    return wcfm_is_vendor();

  }

}
