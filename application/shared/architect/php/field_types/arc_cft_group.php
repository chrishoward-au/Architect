<?php
  /**
   * Name: class_arc_cft_group.php
   * Author: chrishoward
   * Date: 15/6/19
   * Purpose:
   */


    function arc_cft_group_get(&$i,&$section,&$post,&$postmeta,$data){
      // TODO: ACF
//      $data['data']['value']=$data['data']['value']; // For consistency and probably got to change it anyways

      $content = '';
      if ( ! empty( $data['data']['value'] ) ) {
        if ( is_Array( maybe_unserialize( $data ['value'] ) )) {
          $data ['value'] = maybe_unserialize( $data ['value'] );

          $content = '<table class="arc-group-table">';
          $headers_done = FALSE;

          foreach ( $data ['value'] as $key => $value ) {
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
      $data['data']['value'] = $content;
      return $data;
    }



