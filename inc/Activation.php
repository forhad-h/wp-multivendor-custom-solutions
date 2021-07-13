<?php
namespace GRONC;
defined('ABSPATH') or exit;

use GRONC\DB;

class Activation {

  public function __construct() {

    // Create Database
    $db = new DB();
    $db->create_table();

  }

}
