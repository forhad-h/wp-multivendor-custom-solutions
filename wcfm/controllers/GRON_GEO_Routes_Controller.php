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

	public function __construct() {
		global $WCFM, $WCFMu;

		$this->processing();
	}

	public function processing() {
		global $WCFM;
	  $db = new DB();

		if( $_POST['task'] === 'get-map-pointer' ) {

      $vendor_id = $WCFM->wcfm_marketplace->vendor_id;

			if( $vendor_id ) {
				$get_vendor = $db->get_vendor_info( $vendor_id );

				echo $this->response( 'found', 'Vendor data found!', $get_vendor );

			}else {
				echo $this->response( 'not_vendor', 'You are not a vendor!' );
			}


		}else {
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

}
