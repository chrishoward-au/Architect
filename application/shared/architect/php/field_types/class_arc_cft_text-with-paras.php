<?php
  /**
   * Name: class_arc_cft_text-with-paras.php
   * Author: chrishoward
   * Date: 15/6/19
   * Purpose:
   */

  class arc_cft_text_with_paras extends arc_cft {

    function __construct($i,$section, &$post, &$postmeta) {
      // parent::__construct($i,$section,$post, $postmeta);
      self::get($i,$section,$post,$postmeta);
    }

    function get(&$i,&$section,&$post,&$postmeta){
      $content = wpautop( $this->data['value'] );
      if ( empty( $section['_panels_design_process-custom-field-shortcodes'] ) || $section['_panels_design_process-custom-field-shortcodes'] === 'process' ) {
        $content = do_shortcode( $content );
      } else {
        $content = strip_shortcodes( $content );
      }
      $this->data['value']=$content;
    }

    function render(){
      $content = '';
      if ( ! empty( $this->data['value'] ) ) {
        $content = $this->data['value'];
      }
      return $content;
    }


  }
