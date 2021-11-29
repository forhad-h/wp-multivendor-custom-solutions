<?php
/**
 * Vendor_Settings_Controller controllers
 */
namespace GRON\WCFM\controllers;

defined('ABSPATH') or exit;

use GRON\MySQL;
use GRON\Utils;



class Vendor_Settings_Controller {

	/** @var MySQL $mysql instance of MySQL */
	private $mysql;
	private $wpdb;

	public function __construct() {
		global $wpdb;
		$this->mysql = new MySQL();
		$this->wpdb = $wpdb;
	}

	/**
   * update shop timings
   * @param Array $data
  */
  public function update_shop_timings( $data_array ) {


		foreach( $data_array as $data ) {
			$update = $this->mysql->update_shop_timing( $data );
		}

		echo $this->mysql->count_shop_timings();

  }

	/**
   * insert delivery slot
   * @param Array $data
  */
	public function insert_delivery_slot( $data ) {

		$insert = $this->mysql->insert_delivery_slot( $data );

		if( $insert ) {

			$row = $this->mysql->get_delivery_slot_by_id( $this->wpdb->insert_id );

			$data = array(
				'count_slots' => $this->mysql->count_delivery_slots(),
				'info' => array(
					'slot_id' => $row->slot_id,
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

		$id = esc_sql( $data['slot_id'] );

		$update = $this->mysql->update_delivery_slot( $data );

		if( $update ) {
			$row = $this->mysql->get_delivery_slot_by_id( $id );

			$data = array(
				'slot_id' => $row->slot_id,
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

		$id = esc_sql( $data['slot_id'] );

    $delete = $this->mysql->delete_delivery_slot( $id );

		if( $delete ) {
			$data = array(
				'count_slots' => $this->mysql->count_delivery_slots(),
				'slot_id' => $id
			);

			echo $this->response(
				'success',
				'Slot deleted!',
				$data
			);
		}

	}

	/**
   * update general settings
	 *
   * @param Array $settings
	 *  ['name'] => ['Value']
  */
  public function update_general_settings( $settings ) {

		$saved_all = true;

		foreach( $settings as $key => $value ) {

			// Only accept 'yes' or 'no' for 'delivery_by_me' setting
			if( $key === 'delivery_by_me' ) {
				if( !in_array( $value, array( 'yes', 'no' ) ) ) return;
			}

			$name = $key . '_' . get_current_user_id();

			$save = update_option( esc_sql( $name ), esc_sql( $value ) );

			if( !$save ) {
				$saved_all = false;
			}

		}


    if( $saved_all ){
		  echo $this->response( 'success', 'General Settings Updated!', $saved_all );
		}else {
			echo $this->response( 'error', 'Error during General Settings Update!', '', 'One of the settings saving failed!' );
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
