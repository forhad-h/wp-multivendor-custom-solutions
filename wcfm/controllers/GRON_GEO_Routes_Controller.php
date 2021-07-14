<?php
/**
 * WCFM plugin controllers
 *
 * Plugin Products Custom Menus Build Controller
 *
 * @author 		WC Lovers
 * @package 	wcfmcsm/controllers
 * @version   1.0.0
 */
namespace GRONC\WCFM\controllers;
use GRONC\DB;

defined('ABSPATH') or exit;

class GRON_GEO_Routes_Controller {

	private $wcfm;

	public function __construct() {
		global $WCFM, $WCFMu;

		$this->wcfm = $WCFM;

		$this->processing();
	}

	public function processing() {
		global $WCFM;
	  $db = new DB();

		if( $_POST['task'] === 'get-map-address' ) {

			$this->get_map_address();

		}elseif( $_POST['task'] === 'save-admin-settings' ) {

			$this->save_admin_settings();

		}
		else {
			echo $this->response( 'no_task', 'No task found!' );
		}

	  die;
	}


	private function response( $status, $message, $data = '' ) {

		$res = array(
			'status' => $status,
			'message' => $message,
			'data' => $data
		);

		return json_encode( $res );
	}

	private function get_map_address( $user_id ) {

		if( $user_id ) {
			$get_info = $db->get_user_info( $user_id );

			echo $this->response( 'found', 'Vendor data found!', $get_vendor );

		}else {
			echo $this->response( 'not_vendor', 'You are not a vendor!' );
		}

	}

	private function save_admin_settings() {

		$api_key = $_POST['google_map_api_key'];

		if( $api_key ) {
			$db->save_option('gron_google_map_api_key', $api_key );
		}

	}

}
