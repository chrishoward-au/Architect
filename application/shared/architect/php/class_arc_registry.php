<?php

  /**
   * Project pizazzwp-architect.
   * File: arc_Registry.php
   * User: chrishoward
   * Date: 20/10/14
   * Time: 10:52 PM
   */
  class arc_Registry {
    private $registry = array();

    private static $instance = null;

    public static function getInstance() {
      if ( self::$instance === null ) {
        self::$instance = new arc_Registry();
      }

      return self::$instance;
    }

    private function __construct() { }

    private function __clone() { }

    public function set( $key, $value ) {

      // For us, all keys are array parents, so like keys will extend the array, as opposed to throwing an error. This makes keys much more usable and extensible.
//      if (isset($this->registry[ $key ])) {
//        throw new Exception("There is already an entry for key " . $key);
//
//      }

      if ( substr( $key, 0, 9 ) === 'metaboxes' ) {
        $this->registry[ $key ] = $value;
      } elseif ( $key === 'content_source'
        || $key==='content_info'
      ) {
        $this->registry[ $key ][ key( $value ) ] = current( $value );
      } else {
        $this->registry[ $key ][] = $value;
      }


    }

    public function get( $key ) {
      if ( ! isset( $this->registry[ $key ] ) ) {
        return 'key not set';
      }

      return $this->registry[ $key ];
    }
  }


