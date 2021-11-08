<?php
// prevent direct access
defined( 'ABSPATH' ) or exit;

$options = array(
  'cluster' => $_ENV['PUSHER_CLUSTER'],
  'useTLS' => true
);
$pusher = new Pusher\Pusher(
  $_ENV['PUSHER_KEY'],
  $_ENV['PUSHER_SECRET'],
  $_ENV['PUSHER_APP_ID'],
  $options
);

$data['message'] = 'hello world';
$pusher->trigger('my-channel', 'my-event', $data);
