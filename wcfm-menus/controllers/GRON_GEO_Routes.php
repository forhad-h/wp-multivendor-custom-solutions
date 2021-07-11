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

 namespace GRONC\WCFM\Controllers;



class GRON_GEO_Routes {

	public function __construct() {
		global $WCFM, $WCFMu;

		$this->processing();
	}

	public function processing() {
		global $WCFM, $WCFMu, $wpdb, $_POST;

	  echo '{ "status": true, "message": "' . __( 'Build updated.', 'wc-frontend-manager' ) . '" }';

	  die;
	}
}
