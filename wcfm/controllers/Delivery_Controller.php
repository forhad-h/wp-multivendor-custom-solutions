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
  public function update_shop_timings( $data_array ) {

		foreach( $data_array as $data ) {
			$this->db->update_shop_timing( $data );
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
