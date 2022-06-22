<?php
namespace GRON\WCFM;
defined('ABSPATH') or exit;

use GRON\WCFM\Page;
use GRON\WCFM\Ajax_Controller;
use GRON\Notice;
use GRON\MySQL;
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

    if( Utils::is_admin() ) {

      /* Create Store Manager pages for admin only */

      // create Settings page
      new Page(array(
        'label' => __( 'GRON - Settings','gron-custom' ),
        'icon' => 'cog',
        'endpoint' => GRON_ADMIN_ENDPOINT_SETTINGS,
        'slug' => GRON_ADMIN_ENDPOINT_SETTINGS,
      ));

      // create Delivery Requests page
      new Page(array(
        'label' => __( 'GRON - Deliveries','gron-custom' ),
        'icon' => 'inbox',
        'endpoint' => GRON_ADMIN_ENDPOINT_DELIVERY_REQUEST,
        'slug' => GRON_ADMIN_ENDPOINT_DELIVERY_REQUEST,
      ));

    }elseif( Utils::is_vendor() ) {

      /* Create Store Manager pages for vendor only */

      // create GEO Routes page
      new Page(array(
        'label' => __( 'GRON - GEO Routes','gron-custom' ),
        'icon' => 'street-view',
        'endpoint' => GRON_ENDPOINT_GEO_ROUTES,
        'slug' => GRON_ENDPOINT_GEO_ROUTES,
      ));

      // create Delivery page
      new Page(array(
        'label' => __( 'GRON - Settings','gron-custom' ),
        'icon' => 'cog',
        'endpoint' => GRON_VENDOR_ENDPOINT_SETTINGS,
        'slug' => GRON_VENDOR_ENDPOINT_SETTINGS,
      ));

      // create page for Deliveries
      new Page(array(
        'label' => __( 'GRON - Deliveries','gron-custom' ),
        'icon' => 'inbox',
        'endpoint' => GRON_VENDOR_ENDPOINT_DELIVERY_REQUEST,
        'slug' => GRON_VENDOR_ENDPOINT_DELIVERY_REQUEST
      ));

      // create page for Deliveries
      new Page(array(
        'label' => __( 'GRON - Orders','gron-custom' ),
        'icon' => 'inbox',
        'endpoint' => GRON_VENDOR_ENDPOINT_GRON_ORDERS,
        'slug' => GRON_VENDOR_ENDPOINT_GRON_ORDERS
      ));

    }elseif( wcfm_is_delivery_boy() ) {

      /* Create Store Manager pages for delivery boy only */

      // create page for Delivery Requests
      new Page(array(
        'label' => __( 'GRON - Requests','gron-custom' ),
        'icon' => 'inbox',
        'endpoint' => GRON_BOY_ENDPOINT_DELIVERY_REQUEST,
        'slug' => GRON_BOY_ENDPOINT_DELIVERY_REQUEST,
        'priority' => 305 // this priority allow make page for delivery boy
      ));

    }

  }

  /**
   * Create custom components
  */
  public function show_gron_notices() {

    $mysql = new MySQL();

    $necessary_value = '<input type="hidden" id="gron-count-shop-timings" value="' . $mysql->count_shop_timings(). '" />';

    $necessary_value .= '<input type="hidden" id="gron-count-delivery-slots" value="' . $mysql->count_delivery_slots() . '" />';

    echo $necessary_value;

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
