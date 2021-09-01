<?php

defined('ABSPATH') or exit;

use GRON\WCFM\core\Store_Manager;

$store_manager = new Store_Manager();

// create GEO Routes menu in WCFM store-manager
$store_manager->add_new_component([
  'label' => __( 'GRON - GEO Routes','gron-custom' ),
  'icon' => 'street-view',
  'endpoint' => GRON_ENDPOINT_GEO_ROUTES,
  'slug' => GRON_ENDPOINT_GEO_ROUTES,
]);

// create Delivery menu in WCFM store-manager
$store_manager->add_new_component([
  'label' => __( 'GRON - Delivery','gron-custom' ),
  'icon' => 'truck-loading',
  'endpoint' => GRON_ENDPOINT_DELIVERY,
  'slug' => GRON_ENDPOINT_DELIVERY,
]);
