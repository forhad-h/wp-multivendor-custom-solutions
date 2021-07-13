<?php

defined('ABSPATH') or exit;

use GRONC\WCFM\core\Menu;

// create GEO Routes menu in WCFM store-manager
new Menu([
  'label' => 'GEO Routes',
  'icon' => 'street-view',
  'endpoint' => 'gronc-geo-routes',
  'slug' => 'geo-routes',
]);
