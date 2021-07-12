<?php

defined('ABSPATH') or exit;

if(!class_exists('WCFM')) return; // Exit if WCFM not installed

use GRONC\Controllers\GRON_GEO_Routes;
use GRONC\WCFM\Menu;


/**
 * Create Menu - GEO Routes
 */
global $gronc_endpoints;
$options = [
	'label' => 'GEO Routes',
	'icon' => 'street-view',
	'endpoint' => $gronc_endpoints[ 'geo_routes' ],
];

new Menu( $options );

/**
 * WCFM - Custom Menus Query Var
 */
function gronc_query_vars( $query_vars ) {

	global $gronc_endpoints;

	$wcfm_modified_endpoints = (array) get_option( 'wcfm_endpoints' );


	$query_custom_menus_vars = array(
		$gronc_endpoints['geo_routes'] => ! empty( $wcfm_modified_endpoints[ $gronc_endpoints['geo_routes'] ] ) ? $wcfm_modified_endpoints[ $gronc_endpoints['geo_routes'] ] : 'geo-routes'
	);

	$query_vars = array_merge( $query_vars, $query_custom_menus_vars );

	return $query_vars;
}
add_filter( 'wcfm_query_vars', 'gronc_query_vars', 50 );

/**
 *  WCFM - Custom Menus Views
 */
function wcfm_csm_load_views( $end_point ) {
	global $WCFM, $WCFMu, $gronc_endpoints;
	$plugin_path = trailingslashit( dirname( __FILE__  ) );

	switch( $end_point ) {
		case $gronc_endpoints[ 'geo_routes' ]:
			require_once( $plugin_path . 'views/wcfm-views-geo-routes.php' );
		break;
		default:
		  break;
	}
}
add_action( 'wcfm_load_views', 'wcfm_csm_load_views', 50 );
add_action( 'before_wcfm_load_views', 'wcfm_csm_load_views', 50 );


/**
 *  WCFM - Custom Menus Ajax Controllers
 */
function wcfm_csm_ajax_controller() {
	global $WCFM, $WCFMu, $gronc_endpoints;

	$plugin_path = trailingslashit( dirname( __FILE__  ) );

	$controller = '';
	if( isset( $_POST['controller'] ) ) {
		$controller = $_POST['controller'];

		switch( $controller ) {
			case $gronc_endpoints[ 'geo_routes' ]:
				new GRON_GEO_Routes();
			break;
		}
	}
}
add_action( 'after_wcfm_ajax_controller', 'wcfm_csm_ajax_controller' );
