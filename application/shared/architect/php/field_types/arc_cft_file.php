<?php
  /**
   * Name: class_arc_cft_file.php
   * Author: chrishoward
   * Date: 15/6/19
   * Purpose:
   */


  function arc_cft_file_get( &$i, &$section, &$post, &$postmeta, $data ) {
    $file_url = '';
    if ( $data['data']['field-source'] == 'acf' ) {
      switch ( TRUE ) {
        case( is_Array( $data['data']['value'] ) ):
          $file_url = $data['data']['value']['url'];
          break;
        case( is_numeric( $data['data']['value'] ) ):
          $file_url = wp_get_attachment_url( $data['data']['value'] );
          break;
        case( is_string( $data['data']['value'] ) ):
          $file_url = $data['data']['value'];
          break;
        default:
          $file_url = '';
      }
    }

    $data['data']['value'] = ! empty( $file_url ) ? $file_url : $data['data']['value'];

    $content = '';
    if ( ! empty( $data['data']['value'] ) ) {
      $content = '<a href="' . $data['data']['value'] . '" class="pzarc-acf-file" target="' . $data['data']['link-behaviour'] . '">' . ( !empty($data['data']['link-text']) && $data['data']['link-text'] != '<span class="pzarc-link-text"></span>' ? $data['data']['link-text'] : '<span class="pzarc-link-text">' . basename($data['data']['value']) . '</span>' ) . '</a>';
    }
    $data['data']['value'] = $content;

    return $data;

  }
