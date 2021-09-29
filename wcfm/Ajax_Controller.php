<?php
/**
 * WCFM ajax request
 * Run on every request
*/
namespace GRON\WCFM;
defined('ABSPATH') or exit;

use GRON\WCFM\controllers\GEO_Routes_Controller;
use GRON\WCFM\controllers\Settings_Controller;

class Ajax_Controller {

  private $task = [];

  public function __construct() {

    // GEO Location
    $this->task['get_map_location'] = 'get-map-locations';

    // Shop Timings
    $this->task['update_shop_timings'] = 'update-shop-timings';

    // Delivery Slots
    $this->task['insert_delivery_slot'] = 'insert-delivery-slot';
    $this->task['update_delivery_slot'] = 'update-delivery-slot';
    $this->task['delete_delivery_slot'] = 'delete-delivery-slot';

    add_action( 'after_wcfm_ajax_controller', array( $this, 'controllers' ) );

  }

  /**
  * Ajax controller
  */
  public function controllers() {

    $controller = $_POST['controller'];
    $task = $_POST['task'];
    $data = $_POST['data'];

    if(
      $controller === GRON_ENDPOINT_GEO_ROUTES &&
      $task === $this->task['get_map_location']
    ) {

      new GEO_Routes_Controller();

    }

    // Controller for Delivery options
    if( $controller === GRON_ENDPOINT_SETTINGS ) {

      $delivery_controller = new Settings_Controller();


      if( $task == $this->task['update_shop_timings'] && $data ) {

        // update shop timings
        $delivery_controller->update_shop_timings( $data );

      }elseif( $task == $this->task['insert_delivery_slot'] && $data ) {

        // insert delivery slot
        $delivery_controller->insert_delivery_slot( $data );

      }elseif( $task == $this->task['update_delivery_slot'] && $data ) {

        // insert delivery slot
        $delivery_controller->update_delivery_slot( $data );

      }elseif( $task == $this->task['delete_delivery_slot'] && $data ) {

        // insert delivery slot
        $delivery_controller->delete_delivery_slot( $data );

      }

    }

  }

}
