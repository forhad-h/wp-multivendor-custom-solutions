<?php
namespace GRON;
defined( 'ABSPATH' ) or exit;

/**
 * NOTE: NOT USED
*/

use GRON\DB;
use WP_REST_Controller;
use WP_REST_Server;

class REST_Controller extends WP_REST_Controller {

  private $db;

  public function __construct() {

    $namespace = 'gron/v1';
    $base = 'api';
    $this->db = new DB();

    register_rest_route(
      $namespace,
      '/' . $base . '/shop_timings',
      array(
        'methods'             => WP_REST_Server::READABLE,
        'callback'            => array( $this, 'get_shop_timings' ),
        'permission_callback' => array( $this, 'permission_check' )
      )
    );

  }

  /**
   * get shop timings
   * @param WP_REST_Request $request
   * @return WP_Error|WP_REST_Response
  */
  public function get_shop_timings( $request ) {

    return $this->db->get_shop_timings();

  }

  /**
   * update shop timings
   * @return NULL|Boolean
  */
  // TODO: need to implement
  public function permission_check() {
    return true;
  }

}
