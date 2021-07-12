<?php
// Custom Load WCFM Scripts
function gronc_load_scripts( $end_point ) {
  global $gronc_info;
  global $gronc_endpoints;

	switch( $end_point ) {
		case $gronc_endpoints['geo_routes']:
			wp_enqueue_script( 'gronc_main_js', GRONC_DIR_URI . '/assets/js/main.js', array( 'jquery' ), $gronc_info['version'], true );
		  break;
    default:
      break;
	}

}

add_action( 'after_wcfm_load_scripts', 'gronc_load_scripts' );

// Custom Load WCFM Styles
function gronc_load_styles( $end_point ) {
  global $gronc_info;
  global $gronc_endpoints;

	switch( $end_point ) {
		case $gronc_endpoints['geo_routes']:
			wp_enqueue_style( 'gronc_main_css', GRONC_DIR_URI . '/assets/css/main.css', array(), $gronc_info['version'] );
		  break;
    default:
      break;
	}

}

add_action( 'after_wcfm_load_styles', 'gronc_load_styles' );
