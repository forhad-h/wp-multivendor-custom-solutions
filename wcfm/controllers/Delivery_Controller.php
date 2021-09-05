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
  */
  public function update_shop_timings( $data_array ) {


		foreach( $data_array as $data ) {
			$update = $this->db->update_shop_timing( $data );
		}

		echo $this->db->count_shop_timings();

  }

	/**
   * insert delivery slot
   * @param Array $data
  */
	public function insert_delivery_slot( $data ) {

		$insert = $this->db->insert_delivery_slot( $data );

		if( $insert ) {
			echo $this->db->count_delivery_slots();
		}

	}

	/**
   * insert delivery slot
   * @param Array $data
  */
  public function update_delivery_slot( $data ) {

		$update = $this->db->update_delivery_slot( $data );
		echo $update;

	}

	/**
	 * insert delivery slot
	 * @param Array $data
	*/
  public function delete_delivery_slot( $data ) {

    $delete = $this->db->delete_delivery_slot( $data );
		
		if( $delete ) {
			echo $this->db->count_delivery_slots();
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
