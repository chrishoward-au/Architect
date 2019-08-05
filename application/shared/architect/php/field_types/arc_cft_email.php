<?php
  /**
   * Name: class_arc_cft_email.php
   * Author: chrishoward
   * Date: 15/6/19
   * Purpose:
   */



    function arc_cft_email_get(&$i,&$section,&$post,&$postmeta,$data){

      if ( ! empty( $data['data']['value'] ) ) {
        $data['data']['value'] = do_shortcode( '[pzmailto ' . $data['data']['value'] . ']'. $data['data']['value'] .'[/pzmailto]' );
      }
      return $data;
    }
