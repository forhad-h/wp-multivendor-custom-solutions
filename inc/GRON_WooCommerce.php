<?php

/**
 * Add date and time slot field in woocommerce
 * @version 2.0.0
*/
namespace GRON;
defined('ABSPATH') or exit;

class GRON_WooCommerce {

  public function __construct() {
    add_filter( 'woocommerce_billing_fields', array( $this, 'gron_billing_fileds' ) );
  }

  /**
   * Checkout field for date
   * @param Array $fields array of billing fields
   * @return Array modified array of billilng fields
  */
  public function gron_billing_fileds( $fields ) {

    // load custom styles
    wp_enqueue_style( 'gron-woocommerce-style' );
    // Load script
    wp_enqueue_script( 'gron-woocommerce-js' );

    $collection_type_field = array(
        'type'        => 'radio',
        'label'       => __( 'Collection Type', 'gron-custom' ),
        'required'    => true,
        'class'       => array( 'gron_collection_type' ),
        'clear'       => true,
        'options'     => array(
          'self_collection'     => 'Self Collection',
          'deliver_to_home'     => 'Deliver to Home'
        ),
        'priority' => 201
    );
    $fields['gron_collection_type'] = $collection_type_field;

    $date_filed = array(
        'type'        => 'select',
        'label'       => __( 'Deliver Date', 'gron-custom' ),
        'required'    => true,
        'class'       => array( 'select2', 'gron_deliver_date' ),
        'clear'       => true,
        'options'     => array(
          'option_1'     => 'Option 1',
          'option_2'     => 'Option 2'
        ),
        'priority' => 202
    );
    $fields['gron_deliver_date'] = $date_filed;

    $time_filed = array(
        'type'        => 'select',
        'label'       => __( 'Deliver Time', 'gron-custom' ),
        'required'    => true,
        'class'       => array( 'gron_deliver_time' ),
        'clear'       => true,
        'options'     => array(
          'option_1'     => 'Option 3',
          'option_2'     => 'Option 4'
        ),
        'priority' => 203
    );
    $fields['gron_deliver_time'] = $time_filed;

    return $fields;

  }

}
