<?php
  /**
   * Name: class_arc_cft_example.php
   * Author: chrishoward
   * Date: 15/6/19
   * Purpose:
   */


    function arc_cft_boolean_get(&$i,&$section,&$post,&$postmeta,$data){
      // add fields for true value and false value
      $field_val = '';

      // WTF is fiel stuff in hertefor??? TODO
      if ( $data['data']['field-source'] = 'acf' ) {
        if ( function_exists( 'get_field' ) ) {
          $file     = get_field( $data['data']['name'] );
          $file_url = $file['url'];
        } elseif ( is_numeric( $data['data']['value'] ) ) {
          $file     = wp_get_attachment_image_src( $data['data']['value'] );
          $file_url = $file[0];
        }
      }

      if($data['data']['value']) {
        $data['data']['value']=$section[ '_panels_design_cfield-' . $i . '-true-value' ];
      } else {
        $data['data']['value']=$section[ '_panels_design_cfield-' . $i . '-false-value' ];
      }

      return $data;



  }
