<?php
defined('ABSPATH') or exit;

if( !function_exists('gron_time_format') ) {
  function gron_time_format( $time ) {
    $time_arr = explode( ":", $time );
    array_pop( $time_arr );
    $new_time = implode( ':', $time_arr);
    return $new_time;
  }
}

if( !function_exists('underscore_to_capitalize') ) {

  function underscore_to_capitalize( $string ) {

      $str = ucwords( str_replace( '_', ' ', $string ) );

      return $str;
  }

}
