<?php
/*
  Register stylesheet and JavaScript files
*/
namespace GRON;

defined('ABSPATH') or exit;

use GRON\Utils;

class Styles_And_Scripts {

  public function __construct() {

    // Register styles
    add_action( 'wp_enqueue_scripts', array( $this, 'register_view_styles' ) );

    // Resister scripts
    add_action( 'wp_enqueue_scripts', array( $this, 'register_view_scripts' ) );

    // Load styles
    add_action( 'wp_enqueue_scripts', array( $this, 'load_view_styles' ) );

    // Load scripts
    add_action( 'wp_enqueue_scripts', array( $this, 'load_view_scripts' ) );

  }

  /*
  * Register styles for client side
  */
  public function register_view_styles() {
    wp_register_style( 'gron-woocommerce-style', GRON_DIR_URI . 'assets/css/gron-woocommerce.css');
  }

  /**
  * Register scripts for client side
  */
  public function register_view_scripts() {
    wp_register_script( 'gron-woocommerce-script', GRON_DIR_URI . 'assets/js/gron-woocommerce.js', array( 'jquery' ), GRON_VERSION, true );
  }

  /**
  * Load styles for client side
  */
  public function load_view_styles() {
    wp_enqueue_style( 'jquery-toast-stylesheet', GRON_DIR_URI . 'assets/css/jquery.toast.min.css');
  }

  /**
  * Load scripts for client side
  */
  public function load_view_scripts() {

    // Load jQuery Toast JS
    wp_enqueue_script( 'jquery-toast-script', GRON_DIR_URI . 'assets/js/jquery.toast.min.js', array('jquery'), '1.0.0', true );

    // Load pusher JS
    wp_enqueue_script( 'pusher-js', 'https://js.pusher.com/7.0/pusher.min.js', array(), '7.0', true );

    // Load GRON pusher JS
    wp_enqueue_script( 'gron-pusher-js', GRON_DIR_URI . 'assets/js/services/pusher.js', array( 'jquery' ), GRON_VERSION, true );

    wp_localize_script( 'gron-pusher-js', 'pusherObj', array(
      'key'      => $_ENV[ 'PUSHER_KEY' ],
      'cluster'  => $_ENV[ 'PUSHER_CLUSTER'],
      'userInfo' => Utils::current_user_info(),
      'siteUrl'  => get_site_url()
    ) );

    wp_enqueue_script( 'gron-main-js', GRON_DIR_URI . 'assets/js/main.js', array( 'jquery' ), GRON_VERSION, true );

    wp_localize_script( 'gron-main-js', 'mainObj', array(
      'hasMapAPIKey' => Utils::has_map_api_key()
    ) );

  }

}
