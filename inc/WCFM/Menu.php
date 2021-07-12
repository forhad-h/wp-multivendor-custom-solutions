<?php
namespace GRONC\WCFM;
/**
 * class Menu
*/

class Menu {

  private $wcfm;
  private $label;
  private $icon;
  private $endpoint;
  private $endpoint_slug;

  public function __construct( $options_arr ) {

    global $WCFM;
    $this->wcfm = $WCFM;
    $this->label = $options_arr['label'];
    $this->icon = $options_arr['icon'];
    $this->endpoint = $options_arr['endpoint'];

    $enpoint_arr = explode( '-', $this->endpoint );
    array_shift( $enpoint_arr );
    $this->endpoint_slug = implode( '-', $enpoint_arr );

    add_action( 'init', [ $this, 'wcfm_init' ], 50 );

    add_filter( 'wcfm_menus', [ $this, 'create_menu' ], 20 );
    add_filter( 'wcfm_query_vars', [ $this, 'query_vars' ], 50 );
    add_filter( 'wcfm_endpoints_slug', [ $this, 'endpoints_slug' ] );

  }

  public function create_menu( $menus ) {
  	global $gronc_endpoints;

  	$geo_routes = array(
  		$gronc_endpoints[ 'geo_routes' ] => array(
  			'label'  => __( $this->label, 'gron-custom'),
  			'url'       => $this->get_menu_url( $this->endpoint ),
  			'icon'      => $this->icon,
  			'priority'  => 20
  		),
  	);

  	$menus = array_merge( $menus, $geo_routes );

  	return $menus;
  }

 	private function get_menu_url( $endpoint ) {

 		$wcfm_page = get_wcfm_page();
 		$wcfm_custom_menus_url = wcfm_get_endpoint_url( $endpoint, '', $wcfm_page );

 		return $wcfm_custom_menus_url;

 	}

  public function query_vars( $query_vars ) {

  	global $gronc_endpoints;

  	$wcfm_modified_endpoints = (array) get_option( 'wcfm_endpoints' );


  	$query_custom_menus_vars = array(
  		$this->endpoint => ! empty( $wcfm_modified_endpoints[ $this->endpoint ] ) ? $wcfm_modified_endpoints[ $this->endpoint ] : $this->endpoint_slug,
  	);

  	$query_vars = array_merge( $query_vars, $query_custom_menus_vars );

  	return $query_vars;
  }

  public function wcfm_init() {
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
  		$this->endpoint => $this->endpoint_slug
  	);

  	$endpoints = array_merge( $endpoints, $custom_menus_endpoints );

  	return $endpoints;
  }



}
