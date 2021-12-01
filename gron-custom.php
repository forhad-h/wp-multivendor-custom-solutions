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
use GRON\WCFM\Store_Manager;

/* TODO: Security Issue
  Single vendor management exposed to Delivery boy
  Example Link: http://localhost:8080/store-manager/vendors-manage/2/
*/

register_activation_hook( __FILE__, function() {
  new Activation();
} );

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

  // Load pusher service
  //require_once GRON_DIR_PATH . 'services/pusher.php';

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
