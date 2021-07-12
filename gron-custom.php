<?php
defined('ABSPATH') or exit;

$gronc_info  = [
  'version' => '1.0.0',
];

$gronc_endpoints = [
  'geo_routes' => 'gronc-geo-routes'
];

define( "GRONC_DIR_PATH", get_stylesheet_directory() . '/gron-custom' );
define( "GRONC_DIR_URI", get_stylesheet_directory_uri() . '/gron-custom' );

// include class autoloader
require_once GRONC_DIR_PATH . "/vendor/autoload.php";

// Load assets - css, js
require_once GRONC_DIR_PATH . "/inc/load-assets.php";

// include WCFM menus integration
require_once GRONC_DIR_PATH . "/google-map.php";
