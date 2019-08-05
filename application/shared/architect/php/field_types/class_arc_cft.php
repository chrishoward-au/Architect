<?php
  /**
   * Name: class_arc_cft.php
   * Author: chrishoward
   * Date: 5/8/19
   * Purpose:
   */

  class Arc_CFT {
    public $data = array( 'value' => '' );
    public $meta = array();

    function get( &$i, &$section, &$post, &$postmeta ) {

    }

    function render() {

    }


    /****
     * functions
     */
    function arc_get_field_wp( $arc_field_name, &$postmeta ) {

      $this->data['value'] = ( ! empty( $postmeta[ $arc_field_name ] ) ? $postmeta[ $arc_field_name ][0] : NULL );
    }

    function arc_get_field_wooc( $arc_field_name, &$postmeta ) {
      $this->data['value'] = ( ! empty( $postmeta[ $arc_field_name ] ) ? $postmeta[ $arc_field_name ][0] : NULL );
    }

    function arc_get_field_acf( $arc_field_name, &$postmeta ) {
//        var_dump(get_field_object($arc_field_name));

      if ( ! empty( $arc_field_name ) && function_exists( 'get_field' ) ) {
        $this->data['value']        = get_field( $arc_field_name );
        $this->meta['acf_settings'] = get_field_object( $this->data['name'] );
        $this->meta['raw_value']    = get_field( $arc_field_name, FALSE, FALSE );
      }

    }

  }

