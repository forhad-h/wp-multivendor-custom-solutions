<?php
/**
 * GEO_Routes_Controller controllers
 */
namespace GRON\WCFM\controllers;
use GRON\MySQL;
use GRON\Utils;

defined('ABSPATH') or exit;

class GEO_Routes_Controller {

	private $wcfm;

	/** @var MySQL $mysql instance of MySQL */
	private $mysql;

	public function __construct() {
		global $WCFM, $WCFMu;

		$this->wcfm = $WCFM;
		$this->mysql = new MySQL();

		$this->processing();
	}

	public function processing() {
		global $WCFM;

		if( $_POST['task'] === 'get-map-locations' ) {

			$this->get_locations();

		}elseif( $_POST['task'] === 'save-admin-settings' ) {

			$this->save_admin_settings();

		}
		else {
			echo $this->response( 'no_task', 'No task found!' );
		}

	  die;
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

	private function get_locations() {

		try {

			$data = array(
				'store' => $this->get_store_locations(),
				'order' => $this->get_order_locations(),
				'has_api_key' => Utils::has_map_api_key()
			);
			echo $this->response( 'data_found', 'Location found!', $data );

		}catch( Exception $e ) {
		    echo $this->response( 'not_found', 'Location not found!', '', $e );
		}

	}

	private function get_store_locations() {
		$default_lat = apply_filters( 'gron_map_default_lat',  0 );
		$default_lng = apply_filters( 'gron_map_default_lng', 0 );

		if( !IS_GRON_VENDOR ) {
			try {
	      global $WCFMmp;

				$wcfm_marketplace_options = $WCFMmp->wcfmmp_marketplace_options;

				$default_geolocation = isset( $wcfm_marketplace_options['default_geolocation'] ) ? $wcfm_marketplace_options['default_geolocation'] : array();
				$store_location = isset( $default_geolocation['location'] ) ? esc_attr( $default_geolocation['location'] ) : '';

				$store_lat = isset( $default_geolocation['lat'] ) ? esc_attr( $default_geolocation['lat'] ) : $default_lat;
				$store_lng = isset( $default_geolocation['lng'] ) ? esc_attr( $default_geolocation['lng'] ) : $default_lng;

				$data = array(
					'address' => $store_location,
					'lat' => $store_lat,
					'lng' => $store_lng
				);
				return $data;

			}catch(Exception $e) {

				throw new WP_Error( 'not_found', 'Error: during get store location of admin user', $e );

			}

		}else {

			try {
				$user_id = apply_filters( 'gron_current_user_id', get_current_user_id() );

				$vendor_data = get_user_meta( $user_id, 'wcfmmp_profile_settings', true );

	      $store_location   = isset( $vendor_data['store_location'] ) ? esc_attr( $vendor_data['store_location'] ) : '';
				$store_lat = isset( $vendor_data['store_lat'] ) ? esc_attr( $vendor_data['store_lat'] ) : $default_lat;
				$store_lng = isset( $vendor_data['store_lng'] ) ? esc_attr( $vendor_data['store_lng'] ) : $default_lng;

				$data = array(
					'address' => $store_location,
					'lat' => $store_lat,
					'lng' => $store_lng
				);

				return $data;
			}catch( Exception $e ) {
         throw new WP_Error( 'not_found', 'Error: during get store location of vendor', $e );
			}

		}
	}

	private function get_order_locations() {

		if( !IS_GRON_VENDOR ) {

			$args = array(
				'posts_per_page'   => -1,
				'orderby'          => 'date',
				'order'            => 'DESC',
				'post_type'        => 'shop_order',
				'post_status'      => 'published'
			);
			$allData = [];

		  try {

				$order_array = get_posts( $args );

				foreach($order_array as $order_single) {
					$the_order = wc_get_order( $order_single );

					$order_id = $order_single->ID;

					$address = get_post_meta( $order_id, '_wcfmmp_user_location', true );
					$lat     = get_post_meta( $order_id, '_wcfmmp_user_location_lat', true );
					$lng     = get_post_meta( $order_id, '_wcfmmp_user_location_lng', true );

					if( !$lat || !$lng ) continue;

					$data = array(
						'address' => $address,
						'lat' => $lat,
						'lng' => $lng
					);

					array_push( $allData, $data );

				}
				return $allData;
			}catch( Exception $e ) {
		    throw new WP_Error( 'not_found', 'Error: during get order location of admin user', $e );
		  }
	  }else{

			global $wpdb, $WCFMmp;
			$vendor_id = $WCFMmp->vendor_id;
			$table_name = $wpdb->prefix . 'wcfm_marketplace_orders';
			$sql = "SELECT * FROM {$table_name} WHERE `vendor_id` = {$vendor_id} AND `is_trashed` = 0 ORDER BY 'order_id' DESC";

			$allData = [];

			try {

				$order_array = $wpdb->get_results( $sql );

		    foreach( $order_array as $order_single ) {

		      $order_id = $order_single->order_id;

		      $address = get_post_meta( $order_id, '_wcfmmp_user_location', true );
		      $lat = get_post_meta( $order_id, '_wcfmmp_user_location_lat', true );
		      $lng = get_post_meta( $order_id, '_wcfmmp_user_location_lng', true );

		      if( !$lat || !$lng ) continue;

		      $data = array(
		        'address' => $address,
		        'lat' => $lat,
		        'lng' => $lng
		      );

					array_push( $allData, $data );

		    }

				return $allData;

			}catch( Exception $e ){
				throw new WP_Error( 'not_found', 'Error: during get order location of admin user', $e );
			}

		}

	}

	private function save_admin_settings() {

		$api_key = $_POST['google_map_api_key'];

		if( $api_key ) {
			$this->mysql->save_option('gron_google_map_api_key', $api_key );
		}

	}

}
