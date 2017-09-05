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

    private static $instance = NULL;

    public static function getInstance() {
      if ( self::$instance === NULL ) {
        self::$instance = new arc_Registry();
      }

      return self::$instance;
    }

    private function __construct() {
    }

    private function __clone() {
    }

    public function set( $key, $value, $val2 = NULL ) {

      // For us, all keys are array parents, so like keys will extend the array, as opposed to throwing an error. This makes keys much more usable and extensible.
//      if (isset($this->registry[ $key ])) {
//        throw new Exception("There is already an entry for key " . $key);
//
//      }

      switch ( TRUE ) {

        case ( substr( $key, 0, 9 ) === 'metaboxes' ) :
          $this->registry[ $key ] = $value;
          break;

        case ( $key === 'content_source' ) :
          $this->registry[ $key ][ key( $value ) ] = current( $value );
          break;

        case ($key == 'post_types'):
          if (!empty($val2)) {
            $this->registry[ $key ][$val2] = $value;
          } else {
            $this->registry[ $key ][] = $value;
          }
          break;

        default:
          $this->registry[ $key ][] = $value;
          break;
      }


    }

    public function get( $key ) {
      if ( ! isset( $this->registry[ $key ] ) ) {
        return 'key not set';
      }

      return $this->registry[ $key ];
    }
  }


