<?php
/*
  Plugin Name: GRON Custom Solutions
  Description: Functionality Extention  on top of WCFM
  Author: Forhad Hosain
  Version: 2.1.2
  Text Domain: gron-custom
  Domain Path: /languages
*/
defined('ABSPATH') or exit;

// load config file
require_once plugin_dir_path( __FILE__ ) . "config.php";

// include class autoloader
require_once GRON_DIR_PATH . "vendor/autoload.php";

// Load environment variables
$dotenv = Dotenv\Dotenv::createImmutable( __DIR__ );
$dotenv->load();

use GRON\Activation;
use GRON\GRON_WooCommerce;
use GRON\Styles_And_Scripts;
use GRON\REST_Controller;
use GRON\Cron_Job;

use GRON\WCFM\Store_Manager;

// Load the Delivery Notifications schedule tasks file
require_once GRON_DIR_PATH . 'helpers/dn-scheduled-tasks.php';

/* TODO: Security Issue
  Single vendor management exposed to Delivery boy
  Example Link: http://localhost:8080/store-manager/vendors-manage/2/
*/

/*
* Activation hook run on plugin activation
*/
register_activation_hook( __FILE__, function() {
  new Activation();
} );

/*
* Deactivation hook run on plugin deactivation
*/
register_deactivation_hook( __FILE__, function(){

  // Reset Cron-job
  $timestamp = wp_next_scheduled('gron_dn_scheduled_tasks');
  wp_unschedule_event( $timestamp, 'gron_dn_scheduled_tasks' );

});


// Register styles and scripts
new Styles_And_Scripts();

function gron_init() {

  // required to init, to load WCFM funtionality

  require_once GRON_DIR_PATH . 'wcfm-config.php';

  // Include wcfm integration
  new Store_Manager();

  // TODO: prevent excecute delivery boy related code, if the plugin is not found
  // Delivery boy
  require_once GRON_DIR_PATH . 'wcfm/delivery-boy.php';


  /* Cron Job */
  $cron_job = new Cron_Job();
  gron_dn_scheduled_tasks_func();

  // On gron_dn_scheduled_tasks cron job
  add_action( 'gron_dn_scheduled_tasks', function() {
    // Run the Delivery Notifications Scheduled task
    // on gron_dn_scheduled_tasks cron job
    //gron_dn_scheduled_tasks_func();

  } );

}

add_action('init', 'gron_init');

function gron_woocommerce_loaded() {
  // WooCommerce related implementation
  new GRON_WooCommerce();
}

add_action( 'woocommerce_loaded', 'gron_woocommerce_loaded');

// REST API TODO: Remove if not needed
add_action( 'rest_api_init', function() {
  new REST_Controller();
});
