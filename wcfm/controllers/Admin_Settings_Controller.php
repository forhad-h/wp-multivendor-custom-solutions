<?php
/**
 * Admin_Settings_Controller controllers
 */
namespace GRON\WCFM\controllers;

defined('ABSPATH') or exit;

use GRON\MySQL;
use GRON\Utils;



class Admin_Settings_Controller {

	/** @var MySQL $mysql instance of MySQL */
	private $mysql;

	/** @var wpdb $wpdb intance of wpdb */
	private $wpdb;

	public function __construct() {
		global $wpdb;
		$this->mysql = new MySQL();
		$this->wpdb = $wpdb;
	}

	/**
   * update general settings
	 *
   * @param Array $settings
	 *  ['name'] => ['Value']
  */
  public function update_general_settings( $settings ) {

		$saved_all = true;

		foreach( $settings as $name => $value ) {

			// Only accept 'yes' or 'no' for '_gron_delivery_by_seller' setting
			if( $name === '_gron_delivery_by_seller' ) {
				if( !in_array( $value, array( 'yes', 'no' ) ) ) return;
			}

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
