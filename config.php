<?php
defined('ABSPATH') or exit;


define( "GRON_DIR_PATH", plugin_dir_path( __FILE__ ) );
define( "GRON_DIR_URI", plugin_dir_url( __FILE__ ) );
define( "GRON_VERSION", '2.1.0');

define( "GRON_ENDPOINT_GEO_ROUTES", 'gron-geo-routes' );
define( "GRON_ENDPOINT_SETTINGS", 'gron-settings' );
define( "GRON_ENDPOINT_DELIVERY_REQUEST", 'gron-delivery-request' );

define( "SQLite_FILE_PATH", "sqlite:" . GRON_DIR_PATH . "db/delivery_notifications.db" );
