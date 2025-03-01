<?php
  /**
   * Name: class_arc_cft_link.php
   * Author: chrishoward
   * Date: 15/6/19
   * Purpose:
   */


    function arc_cft_link_get(&$i,&$section,&$post,&$postmeta,$data){
      if ( ! empty( $data['data']['value'] ) ) {
        $data['data']['value'] = '<a href="' . $data['data']['value'] . '" class="pzarc-link">' . ( ! empty( $data['data']['link-text'] ) ? '<span class="pzarc-link-text">' . $data['data']['link-text'] . '</span>' : '<span class="pzarc-link-text">' . $data['data']['value'] . '</span>' ) . '</a>';
      }
      return $data;

  }

