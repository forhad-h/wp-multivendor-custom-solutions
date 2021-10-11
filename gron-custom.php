<?php
/*
  Plugin Name: GRON Custom Solutions
  Description: Functionality Extention  on top of WCFM
  Author: Forhad Hosain
  Version: 2.1.0
  Text Domain: gron-custom
  Domain Path: /languages
*/
defined('ABSPATH') or exit;

// load config file
require_once plugin_dir_path( __FILE__ ) . "config.php";

// include class autoloader
require_once GRON_DIR_PATH . "vendor/autoload.php";

use GRON\Activation;
use GRON\GRON_WooCommerce;
use GRON\Register_Scripts;
use GRON\REST_Controller;
use GRON\WCFM\Store_Manager;

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
    new Store_Manager();
    add_filter( 'wcfm_menus', function( $menus ){

      //wcfm_is_delivery_boy()
      
        $requests = array(
          'wcfm-requests' => array(
            'label'      => __( 'Requests', 'wc-frontend-manager-delivery'),
            'url'        => 'http://localhost:8080/store-manager/requests/',
            'icon'       => 'shipping-fast',
            'priority'   => 53
           ) );


      return array_merge( $menus, $requests );
    }, 301 );

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
