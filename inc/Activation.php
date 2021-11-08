<?php
namespace GRON;
defined('ABSPATH') or exit;

use GRON\CRUD_MySQL;

class Activation {

  public function __construct() {

    // Create Database
    $db = new CRUD_MySQL();
    $db->create_tables();

  }

}
