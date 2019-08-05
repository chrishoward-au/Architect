<?php
  /**
   * Name: class_arc_cft_group.php
   * Author: chrishoward
   * Date: 15/6/19
   * Purpose:
   */

  class arc_cft_group extends arc_cft {

    function __construct($i,$section, &$post, &$postmeta) {
      // parent::__construct($i,$section,$post, $postmeta);
      self::get($i,$section,$post,$postmeta);
    }

    function get(&$i,&$section,&$post,&$postmeta){
      // TODO: ACF
      $this->data['value']=$this->data['value']; // For consistency and probably got to change it anyways

    }

    function render(){
      $content = '';
      if ( ! empty( $this->data['value'] ) ) {
        if ( is_Array( maybe_unserialize( $this->data ['value'] ) )) {
          $this->data ['value'] = maybe_unserialize( $this->data ['value'] );

          $content = '<table class="arc-group-table">';
          $headers_done = FALSE;

          foreach ( $this->data ['value'] as $key => $value ) {
            $inner_array = maybe_unserialize( $value );
            if ( is_array( $inner_array ) ) {
              if ( ! $headers_done ) {
                foreach ( $inner_array as $k => $v ) {
                  $content .= '<th>' . ucwords( str_replace( '_', ' ', str_replace( 'ob_', '', $k ) ) ) . '</th>';
                  $headers_done = TRUE;
                }
              }
              $content .= '<tr>';
              foreach ( $inner_array as $k => $v ) {
                $content .= '<td>' . $v . '</td>';
              }
              $content .= '</tr>';
            } else {
              $content = '<td>' . $value . '</td>';
            }
          }
          $content         .= '</table>';
        }

      }
      return $content;
    }


  }
