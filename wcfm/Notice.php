<?php
namespace GRON\WCFM;
defined('ABSPATH') or exit;

use GRON\DB;

// TODO: implement GEO Route notice like this
class Notice {

  private $db;

  public function __construct() {

    $this->db = new DB();

    add_action( 'wp_enqueue_scripts', function() {
      wp_enqueue_style( 'gron-wcfm-notice-css', GRON_DIR_URI . 'wcfm/assets/css/notice.css' );
    } );

    // Show notice before container of wcfm store manager
    add_action( 'wcfm_main_contentainer_before', array( $this, 'show_notice' ) );
  }

  /**
  * Show notice to store manager
  */
  public function show_notice() {

    $has_shop_timing = $this->db->has_shop_timings_data( true );
    $has_delivery_slot = $this->db->has_delivery_slots_data( true );

    if( !$has_shop_timing ) {
      echo $this->shop_timing_notice();
    }

    if( !$has_delivery_slot ) {
      echo $this->delivery_slot_notice();
    }

  }

  /**
  * Notice for Shop Timings
  */
  private function shop_timing_notice() {

    $options = array(
      'title' => 'Shop Timing is Required!',
      'description' => 'Please set up at least one Shop Timing: &nbsp; ',
      'path' => array(
        array( 'icon' => 'truck-loading', 'name' => 'GRON - Delivery' ),
        array( 'icon' => 'business-time', 'name' => 'Shop Timings' ),
      )
    );

    return $this->notice_markup( $options );

  }

  /**
  * Notice for Delivery Slots
  */
  private function delivery_slot_notice() {

    $options = array(
      'title' => 'Delivery Slot is Required!',
      'description' => 'Please set up at least one Delivery Slot: &nbsp; ',
      'path' => array(
        array( 'icon' => 'truck-loading', 'name' => 'GRON - Delivery' ),
        array( 'icon' => 'clock', 'name' => 'Delivery Slots' ),
      )
    );

    return $this->notice_markup( $options );

  }

  /**
  * Notice Markup
  */
  private function notice_markup( $options ) {

    $html = '<div class="gron_wcfm_notice gron_notice_error">';
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
