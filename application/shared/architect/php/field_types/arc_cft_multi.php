<?php
  /**
   * Name: class_arc_cft_multi.php
   * Author: chrishoward
   * Date: 15/6/19
   * Purpose:
   */


    function arc_cft_multi_get(&$i,&$section,&$post,&$postmeta,$data){

      $content = '';
      if ( ! empty( $data['data']['value'] ) ) {
        foreach ( $data['data']['value'] as $k=>$v ) {
          // add fields for html tag, separator, prefix, and suffix
          $content .= '<span class="arc-multi-value">'.$v.'</span>';
        }

      }
      $data['data']['value']=$content;
      return $data;


  }
