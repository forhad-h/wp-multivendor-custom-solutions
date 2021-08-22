<?php

/**
 * Add date and time slot field in woocommerce
 * @version 2.0.0
*/
namespace GRONC;
defined('ABSPATH') or exit;

class GRON_WooCommerce {

  public function __construct() {
    add_filter( 'woocommerce_checkout_fields', array( $this, 'gron_checkout_fileds' ) );
  }

  /**
   * Checkout field for date
   * @param Array fields array of woocommerce checkout page
   * @return Array modified array of fields
  */
  public function gron_checkout_fileds( $fields ) {

    $date_filed = array(
        'type'        => 'select',
        'label'       => __( 'Delivery Date', 'gron-custom' ),
        'required'    => true,
        'class'       => array( 'gron_shipping_date' ),
        'clear'       => true,
        'options'     => array(
          'option_1'     => 'Option 1 text',
          'option_2'     => 'Option 2 text'
        )
    );

    $fields['billing']['gron_shipping_date'] = $date_filed;

    return $fields;
    
  }

}
