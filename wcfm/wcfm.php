<?php

defined('ABSPATH') or exit;

use GRON\WCFM\core\Menu;

// create GEO Routes menu in WCFM store-manager
new Menu([
  'label' => __( 'GRON - GEO Routes','gron-custom' ),
  'icon' => 'street-view',
  'endpoint' => GRON_ENDPOINT_GEO_ROUTES,
  'slug' => GRON_ENDPOINT_GEO_ROUTES,
]);

// create Delivery menu in WCFM store-manager
new Menu([
  'label' => __( 'GRON - Delivery','gron-custom' ),
  'icon' => 'truck-loading',
  'endpoint' => GRON_ENDPOINT_DELIVERY,
  'slug' => GRON_ENDPOINT_DELIVERY,
]);
