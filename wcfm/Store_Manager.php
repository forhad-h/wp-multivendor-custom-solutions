<?php
namespace GRON\WCFM;
defined('ABSPATH') or exit;

use GRON\WCFM\core\Component;
use GRON\WCFM\Ajax_Controller;
use GRON\Notice;


class Store_Manager {

  private $notice;

  public function __construct() {

    // Ajax Controller
    new Ajax_Controller();

    $this->notice = new Notice();
    // Show notice before container of wcfm store manager
    add_action( 'wcfm_main_contentainer_before', array( $this, 'show_gron_delivery_notices' ) );

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
      'label' => __( 'GRON - Settings','gron-custom' ),
      'icon' => 'truck-loading',
      'endpoint' => GRON_ENDPOINT_SETTINGS,
      'slug' => GRON_ENDPOINT_SETTINGS,
    ));

  }

  /**
   * Create custom components
  */
  public function show_gron_delivery_notices() {

    // Show Delivery Notices
    echo $this->notice->shop_timing_notice();
    echo $this->notice->delivery_slot_notice();

  }


}
