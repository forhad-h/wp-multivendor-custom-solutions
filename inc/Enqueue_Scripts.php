<?php
/*
  Register stylesheet and JavaScript files
*/
namespace GRON;

defined('ABSPATH') or exit;

class Enqueue_Scripts {

  public function __construct() {
    add_action( 'wp_enqueue_scripts', array( $this, 'register_view_styles' ) );
  }

  public function register_view_styles() {
    wp_register_style( 'gron-woocommerce-style', GRON_DIR_URI . 'assets/css/gron-woocommerce.css');
  }

}
