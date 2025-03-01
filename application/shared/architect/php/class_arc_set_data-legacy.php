<?php

  /**
   * Class arc_set_data
   * This is the PHP 5.2 version
   * User: chrishoward
   */
  abstract class arc_set_data
  {
    private static $instance = array();

    public static function getInstance($c = 'you_must_pass_the_class_name_for_php_52_compatibility')
    {

      // This is hacky and probably wrong! But seems to work at the moment........
      // need to double check the uniqueness of the var
      if (!isset(self::$instance[$c])) {
        self::$instance[$c] = new $c;
      }
      return self::$instance[$c];


    }

    protected function __construct()
    {

    }

    private function __clone()
    {
    }

//    private function __wakeup()
//    {
//    }

  }
