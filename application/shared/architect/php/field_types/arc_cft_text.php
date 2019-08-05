<?php
  /**
   * Name: class_arc_cft_text.php
   * Author: chrishoward
   * Date: 15/6/19
   * Purpose:
   */


  function arc_cft_text_get( &$i, &$section, &$post, &$postmeta, $data ) {

    if ( is_string( $data['data']['value'] ) ) {

      if ( empty( $section['_panels_design_process-custom-field-shortcodes'] ) || $section['_panels_design_process-custom-field-shortcodes'] === 'process' ) {
        $data['data']['value'] = do_shortcode( $data['data']['value'] );
      } else {
        $data['data']['value'] = strip_shortcodes( $data['data']['value'] );
      }
    } else {
      // ???
      // TODO Find out what happens if a number or boolean
      $data['data']['value']='';
    }


    return $data;


  }
