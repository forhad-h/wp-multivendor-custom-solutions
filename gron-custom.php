<?php
/*
  Plugin Name: GRON Custom Solutions
  Description: Functionality Extention  on top of WCFM
  Author: Forhad Hosain
  Text Domain: gron-custom
  Domain Path: /languages
*/
defined('ABSPATH') or exit;

define( "GRONC_DIR_PATH", plugin_dir_path( __FILE__ ) );
define( "GRONC_DIR_URI", plugin_dir_url( __FILE__ ) );
define( "GRONC_VERSION", '1.0.0');

// include class autoloader
require_once GRONC_DIR_PATH . "vendor/autoload.php";

use GRONC\Activation;
use GRONC\DB;

register_activation_hook( __FILE__, function() {
  //new Activation();
} );

function gron_init() {

    $is_vendor = false;
    $user = is_user_logged_in() ? wp_get_current_user() : '';

    if($user) {
       $is_vendor = $user->roles[0] == 'wcfm_vendor';
    }

    define( 'IS_GRON_VENDOR', $is_vendor );

    // Include wcfm integration
    require_once GRONC_DIR_PATH . "wcfm/wcfm.php";

}

add_action('init', 'gron_init');
