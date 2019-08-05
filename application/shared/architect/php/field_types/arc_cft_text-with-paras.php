<?php
  /**
   * Name: class_arc_cft_text-with-paras.php
   * Author: chrishoward
   * Date: 15/6/19
   * Purpose:
   */

    function arc_cft_text_with_paras_get(&$i,&$section,&$post,&$postmeta,$data){
      $content = wpautop( $data['data']['value'] );
      if ( empty( $section['_panels_design_process-custom-field-shortcodes'] ) || $section['_panels_design_process-custom-field-shortcodes'] === 'process' ) {
        $content = do_shortcode( $content );
      } else {
        $content = strip_shortcodes( $content );
      }
      $data['data']['value']=$content;

      return $data;


  }
