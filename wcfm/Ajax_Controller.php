<?php
/**
 * WCFM ajax request
 * Run on every request
*/
namespace GRON\WCFM;
defined('ABSPATH') or exit;

use GRON\WCFM\controllers\GEO_Routes_Controller;
use GRON\WCFM\controllers\Vendor_Settings_Controller;
use GRON\WCFM\controllers\Admin_Settings_Controller;

class Ajax_Controller {

  private $task = [];

  public function __construct() {

    // GEO Location
    $this->task['get_map_location'] = 'get-map-locations';

    // Shop Timings
    $this->task['update_shop_timings'] = 'update-shop-timings';

    // Delivery Slots
    $this->task['insert_delivery_slot']   = 'insert-delivery-slot';
    $this->task['update_delivery_slot']   = 'update-delivery-slot';
    $this->task['delete_delivery_slot']   = 'delete-delivery-slot';

    // Admin settings
    $this->task['update_general_settings'] = 'update-general-settings';

    add_action( 'after_wcfm_ajax_controller', array( $this, 'controllers' ) );

  }

  /**
  * Ajax controller
  */
  public function controllers() {

    $controller = $_POST['controller'];
    $task = $_POST['task'];
    $data = $_POST['data'];

    if( $controller === GRON_ENDPOINT_GEO_ROUTES ) {

      if( $task === $this->task['get_map_location'] ) {
        new GEO_Routes_Controller();
      }

    }elseif( $controller === GRON_VENDOR_ENDPOINT_SETTINGS ) {
      // Controller for Delivery options

      // Terminate if $data is empty
      if( empty( $data ) ) return;

      $vendor_controller = new Vendor_Settings_Controller();


      if( $task == $this->task['update_shop_timings'] ) {

        // update shop timings
        $vendor_controller->update_shop_timings( $data );

      }elseif( $task == $this->task['insert_delivery_slot'] ) {

        // insert delivery slot
        $vendor_controller->insert_delivery_slot( $data );

      }elseif( $task == $this->task['update_delivery_slot'] ) {

        // insert delivery slot
        $vendor_controller->update_delivery_slot( $data );

      }elseif( $task == $this->task['delete_delivery_slot'] ) {

        // insert delivery slot
        $vendor_controller->delete_delivery_slot( $data );

      }elseif( $task == $this->task['update_general_settings'] ) {

        $vendor_controller->update_general_settings( $data );

      }

    }elseif( $controller === GRON_ADMIN_ENDPOINT_SETTINGS ) {

      // Terminate if $data is empty
      if( empty( $data ) ) return;

      $admin_controller = new Admin_Settings_Controller();

      if( $task === $this->task['update_general_settings'] && $data ) {

        $admin_controller->update_general_settings( $data );

      }

    }

  }

}
