<?php
namespace GRON;

// Prevent direct access
defined('ABSPATH') or exit;

/*
 * class Cron_Job
 * Handle scheduling tasks here
*/

class Cron_Job {

  public function __construct() {

    // add custom interval
    add_filter( 'cron_schedules', array( $this, 'add_interval' ) );

    // Cron job to check Delivery Notifications
    if( ! wp_next_scheduled('gron_dn_scheduled_tasks') ) {
      wp_schedule_event( time(), 'gron_1m', 'gron_dn_scheduled_tasks' );
    }

  }

  /**
  * Add interval
  * @param Array $schedules
  * @return Array $schedules
  */
  public function add_interval( $schedules ) {

    $schedules['gron_1m'] = array(
      'interval' => 60,
      'display' => esc_html__('Every Minute'),
    );

    return $schedules;

  }

}
