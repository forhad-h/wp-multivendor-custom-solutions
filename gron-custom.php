<?php
/*
  Plugin Name: GRON Custom Solutions
  Description: Functionality Extention  on top of WCFM
  Author: Forhad Hosain
  Version: 2.0.0
  Text Domain: gron-custom
  Domain Path: /languages
*/
defined('ABSPATH') or exit;

define( "GRON_DIR_PATH", plugin_dir_path( __FILE__ ) );
define( "GRON_DIR_URI", plugin_dir_url( __FILE__ ) );
define( "GRON_VERSION", '2.0.1');

define( "GRON_ENDPOINT_GEO_ROUTES", 'gron-geo-routes' );
define( "GRON_ENDPOINT_DELIVERY", 'gron-delivery' );

// include class autoloader
require_once GRON_DIR_PATH . "vendor/autoload.php";

use GRON\Activation;
use GRON\GRON_WooCommerce;
use GRON\Register_Scripts;
use GRON\REST_Controller;

register_activation_hook( __FILE__, function() {
  new Activation();
} );

// Register styles and scripts
new Register_Scripts();

function gron_init() {

    $is_vendor = false;
    $user = is_user_logged_in() ? wp_get_current_user() : '';

    if($user) {
       $is_vendor = $user->roles[0] == 'wcfm_vendor';
    }

    define( 'IS_GRON_VENDOR', $is_vendor );

    // Include wcfm integration
    require_once GRON_DIR_PATH . "wcfm/wcfm.php";




}

add_action('init', 'gron_init');

function gron_woocommerce_loaded() {
  // WooCommerce related implementation
  new GRON_WooCommerce();
}

add_action( 'woocommerce_loaded', 'gron_woocommerce_loaded');

add_action( 'rest_api_init', function() {
  // REST API
  new REST_Controller();
});
