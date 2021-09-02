<?php
namespace GRON\WCFM;
defined('ABSPATH') or exit;

use GRON\WCFM\core\Component;
use GRON\WCFM\Ajax_Controller;
use GRON\WCFM\Notice;



class Store_Manager {

  public function __construct() {

    // Ajax Controller
    new Ajax_Controller();

    // Notice
    new Notice();

    // create components
    $this->create_components();
  }

  /**
   * Create custom components
  */
  public function create_components() {

    // create GEO Routes menu in WCFM store-manager
    new Component(array(
      'label' => __( 'GRON - GEO Routes','gron-custom' ),
      'icon' => 'street-view',
      'endpoint' => GRON_ENDPOINT_GEO_ROUTES,
      'slug' => GRON_ENDPOINT_GEO_ROUTES,
    ));

    // create Delivery menu in WCFM store-manager
    new Component(array(
      'label' => __( 'GRON - Delivery','gron-custom' ),
      'icon' => 'truck-loading',
      'endpoint' => GRON_ENDPOINT_DELIVERY,
      'slug' => GRON_ENDPOINT_DELIVERY,
    ));

  }



}
