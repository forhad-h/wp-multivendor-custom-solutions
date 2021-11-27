<?php
namespace GRON;
defined('ABSPATH') or exit;

use GRON\MySQL;
use GRON\Utils;

class Notice {

  /** @var MySQL $mysql instance of MySQL */
  private $mysql;

  public function __construct() {

    $this->mysql = new MySQL();

    add_action( 'wp_enqueue_scripts', function() {
      wp_enqueue_style( 'gron-wcfm-notice-css', GRON_DIR_URI . 'wcfm/assets/css/notice.css' );
    } );


  }

  /**
  * Notice for Shop Timings
  */
  public function shop_timing_notice() {

    $options = array(
      'title' => 'Shop Timing is Required!',
      'description' => 'Please set up at least one Shop Timing: &nbsp; ',
      'path' => array(
        array( 'icon' => 'truck-loading', 'name' => 'GRON - Settings' ),
        array( 'icon' => 'business-time', 'name' => 'Shop Timings' ),
      ),
      'id' => 'gron-shop-timing-notice',
      'type_class' => 'gron_notice_error'
    );

    return $this->notice_markup( $options );

  }

  /**
  * Notice for Delivery Slots
  */
  public function delivery_slot_notice() {

    $options = array(
      'title' => 'Delivery Slot is Required!',
      'description' => 'Please set up at least one Delivery Slot: &nbsp; ',
      'path' => array(
        array( 'icon' => 'truck-loading', 'name' => 'GRON - Settings' ),
        array( 'icon' => 'clock', 'name' => 'Delivery Slots' ),
      ),
      'id' => 'gron-delivery-slot-notice',
      'type_class' => 'gron_notice_error'
    );

    return $this->notice_markup( $options );

  }

  /**
  * Notice - Map API Settings for Admin
  */
  public function admin_google_map_api() {

    $options = array();

    if( !Utils::has_map_api_key() ) {
      $options['title'] = 'Google Map API Key is Required!';
      $options['description'] = 'Make sure you have set your Google Map API Key: &nbsp;';
      $options['path'] = array(
        array( 'icon' => 'cogs', 'name' => 'Settings' ),
        array( 'icon' => 'street-view', 'name' => 'GEO Location > Google Map API Key (Change the Map Library to "Google Map")' )
      );
    }/*else {
      $options['title'] = 'Map Default Location is Required!';
      $options['description'] = 'Make sure you have set your Map Default Location: &nbsp;';
      $options['path'] = array(
        array( 'icon' => 'cogs', 'name' => 'Settings' ),
        array( 'icon' => 'street-view', 'name' => 'GEO Location > Map Default Location' )
      );
    }*/

    $options['id'] = 'gron-map-settings-notice';
    $options['type_class'] = 'gron_warning_info';

    return $this->notice_markup( $options );
  }

  /**
  * Notice - Map API Settings for Vendor
  */
  public function vendor_geo_routes() {

    if( !Utils::has_map_api_key() ) {

      $options['title']       = 'Google Map API Key is Required!';
      $options['description'] = 'Please ask the admin to set the Google Map API Key: &nbsp;';

    }else {

      $options['title']       = 'Map Default Location is Required!';
      $options['description'] = 'Make sure you have set your Map Default Location in: &nbsp; ';
      $options['path']        = array(
        array( 'icon' => 'cogs', 'name' => 'Settings' ),
        array( 'icon' => 'globe', 'name' => 'Location > Store Location' ),
      );

    }

    $options['id'] = 'gron-map-settings-notice';
    $options['type_class'] = 'gron_warning_info';

    return $this->notice_markup( $options );
    
  }

  /**
  * Notice Markup
  */
  private function notice_markup( $options ) {

    $html = "<div id='{$options['id']}' class='gron_wcfm_notice {$options['type_class']}'>";
    $html .= "<h3 class='gron_title'> {$options['title']} </h3>";
    $html .= '<div class="gron_desc">';
    $html .= $options['description'];
    $html .= '<span class="gron_setting_path">';

    foreach( $options['path'] as $index => $path ) {

      $seperator = '';
      if( $index < (count( $options['path'] ) - 1) ) {
        $seperator = "&nbsp;>&nbsp;";
      }

      $html .= "<span class='wcfmfa fa-{$path['icon']}'></span> {$path['name']} {$seperator} ";
    }

    $html .= '</span>';
    $html .= '</div>';
    $html .= '</div>';

    return $html;

  }

}
