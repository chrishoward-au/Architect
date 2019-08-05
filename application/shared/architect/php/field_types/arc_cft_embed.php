<?php
  /**
   * Name: class_arc_cft_embed.php
   * Author: chrishoward
   * Date: 15/6/19
   * Purpose:
   */

    function arc_cft_embed_get(&$i,&$section,&$post,&$postmeta,$data){

      $content = '';
      if ( ! empty( $data['data']['value'] ) ) {
        if ($data['data']['field-source']=='acf') {
          $content = $data['data']['value'];
        } else {
          $content = wp_oembed_get( $data['data']['value'] );
        }
      }
      $data['data']['value'] = $content;
      return $data;

  }
