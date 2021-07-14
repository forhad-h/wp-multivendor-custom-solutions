<?php
defined('ABSPATH') or exit;

define( "GRONC_DIR_PATH", plugin_dir_path( __FILE__ ) );
define( "GRONC_DIR_URI", plugin_dir_url( __FILE__ ) );
define( "GRONC_VERSION", '1.0.0');

$user = wp_get_current_user();
$is_vendor =$user->roles[0] == 'wcfm_vendor';
define( 'IS_GRON_VENDOR', $is_vendor );
