<?php
/*
  Plugin Name: Cron Test
*/

function clog( $content ) {
  $log_path = plugin_dir_path( __FILE__ ) . 'dev_log.txt';
  file_put_contents( $log_path, $content );
}

function interval_per_minute( $schedules ) {

  $schedules['gron_1m'] = array(
    'interval' => 10,
    'display' => 'Each Minute'
  );

  return $schedules;

}

$timestamp = wp_next_scheduled( 'gron_dn_cron_hook' );
//wp_unschedule_event($timestamp, 'gron_dn_cron_hook');

if(!wp_next_scheduled('gron_dn_cron_hook')) {
  wp_schedule_event(time(), 'gron_1m', 'gron_dn_cron_hook');
}

//var_dump( wp_next_scheduled( 'gron_dn_cron_hook' ) );
//echo "<br>";
//var_dump( wp_get_ready_cron_jobs('sals_check_event') );


add_action('gron_dn_cron_hook', 'sals_handle_video');

function sals_handle_video() {
   clog(date("H:i:s"));
}
