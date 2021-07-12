<?php

if(!defined('ABSPATH')) exit; // Exit if accessed directly

if(!class_exists('WCFM')) return; // Exit if WCFM not installed

use GRONC\Controllers\GRON_GEO_Routes;

/**
 * WCFM - Custom Menus Query Var
 */
function gronc_query_vars( $query_vars ) {

	$wcfm_modified_endpoints = (array) get_option( 'wcfm_endpoints' );

	$query_custom_menus_vars = array(
		'wcfm-geo-routes' => ! empty( $wcfm_modified_endpoints['wcfm-geo-routes'] ) ? $wcfm_modified_endpoints['wcfm-geo-routes'] : 'geo-routes'
	);

	$query_vars = array_merge( $query_vars, $query_custom_menus_vars );

	return $query_vars;
}
add_filter( 'wcfm_query_vars', 'gronc_query_vars', 50 );

/**
 * WCFM - Custom Menus End Point Title
 */
function gronc_endpoint_title( $title, $endpoint ) {

	if( $endpoint === 'wcfm-geo-routes' ) {
		$title = __( 'GEO Routes', 'gron-custom' );
	}

	return $title;

}
add_filter( 'wcfm_endpoint_title', 'gronc_endpoint_title', 50, 2 );

/**
 * WCFM - Custom Menus Endpoint Intialize
 */
function gronc_init() {
	global $WCFM_Query;

	// Intialize WCFM End points
	$WCFM_Query->init_query_vars();
	$WCFM_Query->add_endpoints();

	if( !get_option( 'wcfm_updated_end_point_cms' ) ) {
		// Flush rules after endpoint update
		flush_rewrite_rules();
		update_option( 'wcfm_updated_end_point_cms', 1 );
	}
}
add_action( 'init', 'gronc_init', 50 );

/**
 * WCFM - Custom Menus Endpoiint Edit
 */
function wcfm_custom_menus_endpoints_slug( $endpoints ) {

	$custom_menus_endpoints = array(
		'wcfm-geo-routes' => 'geo-routes'
	);

	$endpoints = array_merge( $endpoints, $custom_menus_endpoints );

	return $endpoints;
}

add_filter( 'wcfm_endpoints_slug', 'wcfm_custom_menus_endpoints_slug' );

if(!function_exists('get_wcfm_custom_menus_url')) {
	function get_wcfm_custom_menus_url( $endpoint ) {
		global $WCFM;
		$wcfm_page = get_wcfm_page();
		$wcfm_custom_menus_url = wcfm_get_endpoint_url( $endpoint, '', $wcfm_page );
		return $wcfm_custom_menus_url;
	}
}

/**
 * WCFM - Custom Menus
 */
function gronc_wcfm_menus( $menus ) {
	global $WCFM;

	$custom_menus = array(
		'wcfm-geo-routes' => array(
			'label'  => __( 'GEO Routes', 'gron-custom'),
			'url'       => get_wcfm_custom_menus_url( 'wcfm-geo-routes' ),
			'icon'      => 'street-view',
			'priority'  => 17
		),
	);

	$menus = array_merge( $menus, $custom_menus );

	return $menus;
}
add_filter( 'wcfm_menus', 'gronc_wcfm_menus', 20 );

/**
 *  WCFM - Custom Menus Views
 */
function wcfm_csm_load_views( $end_point ) {
	global $WCFM, $WCFMu;
	$plugin_path = trailingslashit( dirname( __FILE__  ) );

	switch( $end_point ) {
		case 'wcfm-geo-routes':
			require_once( $plugin_path . 'views/wcfm-views-geo-routes.php' );
		break;
		default:
		  break;
	}
}
add_action( 'wcfm_load_views', 'wcfm_csm_load_views', 50 );
add_action( 'before_wcfm_load_views', 'wcfm_csm_load_views', 50 );

// Custom Load WCFM Scripts
function wcfm_csm_load_scripts( $end_point ) {
	global $WCFM;
	$plugin_url = trailingslashit( plugins_url( '', __FILE__ ) );

	switch( $end_point ) {
		case 'wcfm-geo-routes':
			wp_enqueue_script( 'wcfm_build_js', $plugin_url . 'js/wcfm-script-geo-routes.js', array( 'jquery' ), $WCFM->version, true );
		break;
	}
}

add_action( 'wcfm_load_scripts', 'wcfm_csm_load_scripts' );
add_action( 'after_wcfm_load_scripts', 'wcfm_csm_load_scripts' );

// Custom Load WCFM Styles
function wcfm_csm_load_styles( $end_point ) {
	global $WCFM, $WCFMu;
	$plugin_url = trailingslashit( plugins_url( '', __FILE__ ) );

	switch( $end_point ) {
		case 'wcfm-geo-routes':
			wp_enqueue_style( 'wcfmu_build_css', $plugin_url . 'css/wcfm-style-geo-routes.css', array(), $WCFM->version );
		break;
	}
}
add_action( 'wcfm_load_styles', 'wcfm_csm_load_styles' );
add_action( 'after_wcfm_load_styles', 'wcfm_csm_load_styles' );

/**
 *  WCFM - Custom Menus Ajax Controllers
 */
function wcfm_csm_ajax_controller() {
	global $WCFM, $WCFMu;

	$plugin_path = trailingslashit( dirname( __FILE__  ) );

	$controller = '';
	if( isset( $_POST['controller'] ) ) {
		$controller = $_POST['controller'];

		switch( $controller ) {
			case 'wcfm-geo-routes':
				new GRON_GEO_Routes();
			break;
		}
	}
}
add_action( 'after_wcfm_ajax_controller', 'wcfm_csm_ajax_controller' );
