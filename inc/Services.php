<?php
namespace GRON;
defined( 'ABSPATH' ) or exit;

use Pusher\Pusher;
/**
* Services class
* Entry point for all third party services
*/

class Services {

  /**
  * Pusher initialization
  *
  * @return Pusher $pusher the pusher instance
  */
  public static function pusher() {

    $options = array(
      'cluster' => $_ENV['PUSHER_CLUSTER'],
      'useTLS' => true
    );

    $pusher = new Pusher(
      $_ENV['PUSHER_KEY'],
      $_ENV['PUSHER_SECRET'],
      $_ENV['PUSHER_APP_ID'],
      $options
    );

    return $pusher;

  }

}
