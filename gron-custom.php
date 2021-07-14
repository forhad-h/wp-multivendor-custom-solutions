<?php
/*
  Plugin Name: GRON Custom Solutions
  Description: Functionality Extention  on top of WCFM
  Author: Forhad Hosain
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
  new Activation();
} );


// Include wcfm integration
require_once GRONC_DIR_PATH . "wcfm/wcfm.php";
