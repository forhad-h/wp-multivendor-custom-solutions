<?php
defined('ABSPATH') or exit;

$gronc  = [
  'version' => '1.0.0',
];

define( "GRONC_DIR_PATH", get_stylesheet_directory() . '/gron-custom' );
define( "GRONC_DIR_URI", get_stylesheet_directory_uri() . '/gron-custom' );

// include class autoloader
require_once GRONC_DIR_PATH . "/vendor/autoload.php";

// Include wcfm integration
require_once GRONC_DIR_PATH . "/wcfm/wcfm.php";
