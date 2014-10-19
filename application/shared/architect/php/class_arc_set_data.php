<?php
/**
 * Project pizazzwp-architect.
 * File: class_arc_set_data.php
 * User: chrishoward
 * Date: 13/10/14
 * Time: 5:39 PM
 */

  // TODO This doesn't work in PHP 5.2. UGH!

abstract class arc_set_data {

  public static function getInstance()
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