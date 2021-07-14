<?php
/**
 * class Menu
*/
namespace GRONC\WCFM\core;
use GRONC\WCFM\controllers\GRON_GEO_Routes_Controller;

defined('ABSPATH') or exit;

class Menu {

  private $wcfm;
  private $label;
  private $icon;
  private $endpoint;
  private $slug;

  public function __construct( $component ) {

    global $WCFM;
    $this->wcfm = $WCFM;
    $this->label = $component['label'];
    $this->icon = $component['icon'];
    $this->endpoint = $component['endpoint'];
    $this->slug = $component['slug'];

    add_action( 'init', [ $this, 'init' ], 50 );
    add_action( 'wcfm_load_scripts', [ $this, 'load_scripts' ] );
    add_action( 'wcfm_load_styles', [ $this, 'load_styles' ] );
    add_action( 'wcfm_load_views', [ $this, 'load_views' ], 50 );
    add_action( 'after_wcfm_ajax_controller', [ $this, 'ajax_controller' ] );

    add_filter( 'wcfm_menus', [ $this, 'add_menu' ], 20 );
    add_filter( 'wcfm_query_vars', [ $this, 'query_vars' ], 50 );
    add_filter( 'wcfm_endpoint_title', [ $this, 'endpoint_title' ], 50, 2 );
    add_filter( 'wcfm_endpoints_slug', [ $this, 'endpoints_slug' ] );

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
  			'priority'  => 100
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

    if( $this->slug ) {
      require_once( GRONC_DIR_PATH . 'wcfm/views/gronc-views-' . $this->slug . '.php' );
    }

  }

  public function ajax_controller() {

  	if( isset( $_POST['controller'] ) ) {


      new GRON_GEO_Routes_Controller();

  	}

  }

  public function load_scripts( $end_point ) {

      if( $end_point === 'gronc-geo-routes' ) {
        $args = array(
          'key' => 'AIzaSyB41DRUbKWJHPxaFjMAwdrzWzbVKartNGg'
        );

        $googl_map_src = add_query_arg( $args, 'https://maps.googleapis.com/maps/api/js' );

        wp_enqueue_script( 'gronc_google_map', $googl_map_src, array(), GRONC_VERSION, true );

        wp_enqueue_script( 'gronc_map_implementation', GRONC_DIR_URI . 'wcfm/assets/js/gron-map.js' , array( 'gronc_google_map' ), GRONC_VERSION, true );
      }

    if( $this->slug ) {
  			wp_enqueue_script( 'gronc_main_js', GRONC_DIR_URI . 'wcfm/assets/js/gronc-script-' . $this->slug . '.js', array('jquery', 'gronc_map_implementation' ), GRONC_VERSION, true );
    }


  }

  function load_styles( $end_point ) {

    if( $this->slug ) {
      wp_enqueue_style( 'gronc_main_css', GRONC_DIR_URI . 'wcfm/assets/css/gronc-style-' . $this->slug . '.css', array(), GRONC_VERSION );
    }

  }


}
