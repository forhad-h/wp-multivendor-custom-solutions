<?php
namespace GRON\WCFM;
defined('ABSPATH') or exit;

use GRON\WCFM\core\Component;
use GRON\WCFM\Ajax_Controller;
use GRON\Notice;
use GRON\Utils;


class Store_Manager {

  /** @var Notice $notice Notice Builder instance */
  private $notice;


  public function __construct() {

    // Ajax Controller
    new Ajax_Controller();

    $this->notice = new Notice();
    // Show notice before container of wcfm store manager
    add_action( 'wcfm_main_contentainer_before', array( $this, 'show_gron_notices' ) );

    // create components
    $this->create_components();
  }

  /**
   * Create custom components
  */
  public function create_components() {


    if( Utils::is_vendor() ) {
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
        'icon' => 'cog',
        'endpoint' => GRON_VENDOR_ENDPOINT_SETTINGS,
        'slug' => GRON_VENDOR_ENDPOINT_SETTINGS,
      ));

    }


    if( Utils::is_admin() ) {

      // create Delivery menu in WCFM store-manager
      new Component(array(
        'label' => __( 'GRON - Settings','gron-custom' ),
        'icon' => 'cog',
        'endpoint' => GRON_ADMIN_ENDPOINT_SETTINGS,
        'slug' => GRON_ADMIN_ENDPOINT_SETTINGS,
      ));

    }

    if( wcfm_is_delivery_boy() ) {
      // create Delivery menu in WCFM store-manager
      new Component(array(
        'label' => __( 'GRON - Requests','gron-custom' ),
        'icon' => 'inbox',
        'endpoint' => GRON_ENDPOINT_DELIVERY_REQUEST,
        'slug' => GRON_ENDPOINT_DELIVERY_REQUEST,
        'priority' => 305
      ));
    }

  }

  /**
   * Create custom components
  */
  public function show_gron_notices() {


    if( Utils::is_admin() ) {

      // Show notice for google map api key
      echo $this->notice->admin_google_map_api();

    }elseif( Utils::is_vendor() ) {

      // Show Settings Notices
      echo $this->notice->shop_timing_notice();
      echo $this->notice->delivery_slot_notice();

    }

  }


}
