<?php
/**
 * Settings_Controller controllers
 */
namespace GRON\WCFM\controllers;

defined('ABSPATH') or exit;

use GRON\DB;
use GRON\Utils;



class Settings_Controller {

	private $db;
	private $wpdb;

	public function __construct() {
		global $wpdb;
		$this->db = new DB();
		$this->wpdb = $wpdb;
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

			$row = $this->db->get_delivery_slot_by_id( $this->wpdb->insert_id );

			$data = array(
				'count_slots' => $this->db->count_delivery_slots(),
				'info' => array(
					'id' => $row->id,
					'raw' => array(
						'time_from' => $row->time_from,
						'time_to' => $row->time_to
					),
					'formatted' => array(
						'time_from' => Utils::time_format( $row->time_from ),
						'time_to' => Utils::time_format( $row->time_to )
					)
				)
			);

			echo $this->response(
				'success',
				'Delivery slot inserted!',
				$data
			);

		}

	}

	/**
   * insert delivery slot
   * @param Array $data
  */
  public function update_delivery_slot( $data ) {

		$id = esc_sql( $data['id'] );

		$update = $this->db->update_delivery_slot( $data );

		if( $update ) {
			$row = $this->db->get_delivery_slot_by_id( $id );

			$data = array(
				'id' => $row->id,
				'raw' => array(
					'time_from' => $row->time_from,
					'time_to' => $row->time_to
				),
				'formatted' => array(
					'time_from' => Utils::time_format( $row->time_from ),
					'time_to' => Utils::time_format( $row->time_to )
				)
			);

			echo $this->response(
				'success',
				'Delivery slot inserted!',
				$data
			);

		}

	}

	/**
	 * insert delivery slot
	 * @param Array $data
	*/
  public function delete_delivery_slot( $data ) {

		$id = esc_sql( $data['id'] );

    $delete = $this->db->delete_delivery_slot( $id );

		if( $delete ) {
			$data = array(
				'count_slots' => $this->db->count_delivery_slots(),
				'id' => $id
			);

			echo $this->response(
				'success',
				'Slot deleted!',
				$data
			);
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