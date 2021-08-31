<?php
/**
 * Delivery_Controller controllers
 */
namespace GRON\WCFM\controllers;

defined('ABSPATH') or exit;

use GRON\DB;



class Delivery_Controller {

	private $db;

	public function __construct() {
		$this->db = new DB();
	}

	/**
   * update shop timings
   * @param Array $data
   * @return WP_Error|JSON
  */
  public function update_shop_timings( $data ) {

    try {

      $update = $this->db->update_shop_timings( $data );

      if( $update ) {
        $data = $this->db->get_shop_timings();
        return $this->response( 'success', 'Shop timing updated!', $data, null );
      }

    }catch( Exception $e ) {

      new WP_Error( 'update-failed', __( 'Shop Timings update failed!', 'gron-custom' ) );

    }

  }

	private function response( $status, $message, $data = '', $error = '' ) {

		$res = array(
			'status' => $status,
			'message' => $message,
			'data' => $data,
			'error' => $error
		);

		return json_encode( $res );
	}

}
