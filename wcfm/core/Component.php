<?php
/**
 * class Component
*/
namespace GRON\WCFM\core;

defined('ABSPATH') or exit;

class Component {

  private $wcfm;
  private $label;
  private $icon;
  private $endpoint;
  private $slug;
  private $priority;

  private $endpoint_geo_routes = GRON_ENDPOINT_GEO_ROUTES;
  private $endpoint_settings = GRON_VENDOR_ENDPOINT_SETTINGS;

  private $get_map_locations = 'get_map_locations';

  public function __construct( $options ) {

    global $WCFM;
    $this->wcfm = $WCFM;
    $this->label = $options['label'];
    $this->icon = $options['icon'];
    $this->endpoint = $options['endpoint'];
    $this->slug = $options['slug'];
    $this->priority = array_key_exists( 'priority', $options ) && $options['priority'] ? $options['priority'] : 51;

    add_action( 'init', array( $this, 'init' ), 50 );
    add_action( 'wcfm_load_scripts', array( $this, 'load_scripts' ) );
    add_action( 'wcfm_load_styles', array( $this, 'load_styles' ) );
    add_action( 'wcfm_load_views', array( $this, 'load_views' ), 50 );

    add_filter( 'wcfm_menus', array( $this, 'add_menu' ), $this->priority );
    add_filter( 'wcfm_query_vars', array( $this, 'query_vars' ), 50 );
    add_filter( 'wcfm_endpoint_title', array( $this, 'endpoint_title' ), 50, 2 );
    add_filter( 'wcfm_endpoints_slug', array( $this, 'endpoints_slug' ) );

  }

 	private function get_menu_url( $endpoint ) {

 		$wcfm_page = get_wcfm_page();
 		$wcfm_custom_menus_url = wcfm_get_endpoint_url( $endpoint, '', $wcfm_page );

 		return $wcfm_custom_menus_url;

 	}

  public function add_menu( $menus ) {

  	$geo_routes = array(
  		$this->endpoint => array(
  			'label'  => __( $this->label, 'gron-custom'),
  			'url'       => $this->get_menu_url( 'wcfm-' . $this->slug ),
  			'icon'      => $this->icon,
  			'priority'  => 101
  		),
  	);

  	$menus = array_merge( $menus, $geo_routes );

  	return $menus;
  }

  public function endpoint_title( $title, $endpoint ) {

  	if( $endpoint === $this->endpoint ) {
  		$title = __( $this->label, 'gron-custom' );
  	}

  	return $title;

  }

  public function query_vars( $query_vars ) {

  	$modified_endpoints = (array) get_option( 'wcfm_endpoints' );


  	$query_custom_menus_vars = array(
  		$this->endpoint => ! empty( $modified_endpoints[ $this->endpoint ] ) ? $modified_endpoints[ $this->endpoint ] : $this->slug,
  	);

  	$query_vars = array_merge( $query_vars, $query_custom_menus_vars );

  	return $query_vars;
  }

  public function init() {
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

  public function endpoints_slug( $endpoints ) {

  	$custom_menus_endpoints = array(
  		$this->endpoint => $this->slug
  	);

  	$endpoints = array_merge( $endpoints, $custom_menus_endpoints );

  	return $endpoints;
  }

  public function load_views( $end_point ) {

    if( $end_point === $this->endpoint ) {
      require_once( GRON_DIR_PATH . 'wcfm/views/' . $this->slug . '.php' );
    }

  }

  public function load_scripts( $end_point ) {

    if( $end_point === 'gron-geo-routes' ) {
      global $WCFMmp;

      $wcfm_marketplace_options = $WCFMmp->wcfmmp_marketplace_options;
      $wcfm_google_map_api = isset( $wcfm_marketplace_options['wcfm_google_map_api'] ) ? $wcfm_marketplace_options['wcfm_google_map_api'] : '';

      $args = array(
        'key' => $wcfm_google_map_api
      );

      $googl_map_src = add_query_arg( $args, 'https://maps.googleapis.com/maps/api/js' );

      wp_enqueue_script( 'gron_google_map', $googl_map_src, array(), GRON_VERSION, true );

      wp_enqueue_script( 'gron_map_implementation', GRON_DIR_URI . 'wcfm/assets/js/google-map-implementation.js' , array( 'gron_google_map' ), GRON_VERSION, true );

      wp_localize_script('gron_map_implementation', 'wcfm', array( 'gronDirUri' => GRON_DIR_URI ) );

    }

    if( $end_point === $this->endpoint ) {

      $deps = array( 'jquery' );

      if( $end_point === 'gron-geo-routes' ) {
        $deps = array('jquery', 'gron_map_implementation' );
      }

  		wp_enqueue_script( 'gron_' . $this->slug . '_script', GRON_DIR_URI . 'wcfm/assets/js/' . $this->slug . '.js', $deps, GRON_VERSION, true );

    }


  }

  function load_styles( $end_point ) {

    if( $end_point === $this->endpoint ) {
      wp_enqueue_style( 'gron_' . $this->slug . '_style', GRON_DIR_URI . 'wcfm/assets/css/' . $this->slug . '.css', array(), GRON_VERSION );
    }

  }


}
