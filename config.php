<?php
defined('ABSPATH') or exit;


define( "GRON_DIR_PATH", plugin_dir_path( __FILE__ ) );
define( "GRON_DIR_URI", plugin_dir_url( __FILE__ ) );
define( "GRON_VERSION", '2.1.7');

define( "GRON_ENDPOINT_GEO_ROUTES", 'gron-geo-routes' );

define( "GRON_VENDOR_ENDPOINT_SETTINGS", 'gron-vendor-settings' );
define( "GRON_ADMIN_ENDPOINT_SETTINGS", 'gron-admin-settings' );

define( "GRON_ADMIN_ENDPOINT_DELIVERY_REQUEST", 'gron-admin-delivery-notifications' );
define( "GRON_VENDOR_ENDPOINT_DELIVERY_REQUEST", 'gron-vendor-delivery-notifications' );
define( "GRON_BOY_ENDPOINT_DELIVERY_REQUEST", 'gron-boy-delivery-requests' );

define( "SQLite_FILE_PATH", "sqlite:" . GRON_DIR_PATH . "db/delivery_notifications.db" );

define( "GRON_DELIVERY_ACCEPTED_STATUS_MSG", "Accepted by");
