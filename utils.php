<?php
defined('ABSPATH') or exit;

if( !function_exists('gron_time_format') ) {
  function gron_time_format( $time ) {
    $new_time = date('h:i A', strtotime( $time ) );
    return $new_time;
  }
}

if( !function_exists('underscore_to_capitalize') ) {

  function underscore_to_capitalize( $string ) {

      $str = ucwords( str_replace( '_', ' ', $string ) );

      return $str;
  }

}
