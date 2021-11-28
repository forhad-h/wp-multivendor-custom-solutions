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

		foreach( $settings as $key => $value ) {

			$name = $key . '_' . get_current_user_id();

			$data[$name] = $value;

			$save = Utils::save_option( $name, $value );

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
