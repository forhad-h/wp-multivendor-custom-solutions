<?php
namespace GRON;
defined('ABSPATH') or exit;

use GRON\DB;

class Activation {

  public function __construct() {

    // Create Database
    $db = new DB();
    $db->create_tables();

  }

}
