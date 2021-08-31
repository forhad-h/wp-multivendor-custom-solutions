<?php
/*
  Register stylesheet and JavaScript files
*/
namespace GRON;

defined('ABSPATH') or exit;

class Register_Scripts {

  public function __construct() {
    add_action( 'wp_enqueue_scripts', array( $this, 'register_view_styles' ) );
    add_action( 'wp_enqueue_scripts', array( $this, 'register_view_scripts' ) );
  }

  public function register_view_styles() {
    wp_register_style( 'gron-woocommerce-style', GRON_DIR_URI . 'assets/css/gron-woocommerce.css');
  }

  public function register_view_scripts() {
    wp_register_script( 'gron-woocommerce-js', GRON_DIR_URI . 'assets/js/gron-woocommerce.js', array( 'jquery' ), GRON_VERSION, true );
  }

}
