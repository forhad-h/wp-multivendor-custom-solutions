<?php

defined('ABSPATH') or exit;

use GRONC\WCFM\core\Menu;

// create GEO Routes menu in WCFM store-manager
new Menu([
  'label' => __( 'GRON - GEO Routes','gron-custom' ),
  'icon' => 'street-view',
  'endpoint' => 'gron-geo-routes',
  'slug' => 'gron-geo-routes',
]);

// create Delivery menu in WCFM store-manager
new Menu([
  'label' => __( 'GRON - Delivery','gron-custom' ),
  'icon' => 'truck-loading',
  'endpoint' => 'gron-delivery',
  'slug' => 'gron-delivery',
]);
