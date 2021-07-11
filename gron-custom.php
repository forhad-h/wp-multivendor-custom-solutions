<?php
defined('ABSPATH') or exit;

define( "GRONC_MAIN_PATH", get_stylesheet_directory() . '/gron-custom' );

// include class autoloader
require_once GRONC_MAIN_PATH . "/vendor/autoload.php";

// include WCFM menus integration
require_once GRONC_MAIN_PATH . "/wcfm-menus/wcfm-menus.php";
