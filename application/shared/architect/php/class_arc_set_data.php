<?php
/**
 * Project pizazzwp-architect.
 * File: class_arc_set_data.php
 * PHPO 5.3+ version
 * User: chrishoward
 * Date: 13/10/14
 * Time: 5:39 PM
 */


abstract class arc_set_data {

  // $caller is not used in 5.3+ version. Is jsut here for compatibility
  public static function getInstance($caller=null)
  {
    static $instance = null;
    if (null === $instance) {
      $instance = new static();
    }

    return $instance;
  }

  protected function __construct() {

}
  private function __clone()
  {
  }

  private function __wakeup()
  {
  }

} 