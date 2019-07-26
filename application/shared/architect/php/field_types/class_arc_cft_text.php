<?php
  /**
   * Name: class_arc_cft_text.php
   * Author: chrishoward
   * Date: 15/6/19
   * Purpose:
   */

  class arc_cft_text extends arc_custom_fields {

    function __construct($i,$section, &$post, &$postmeta) {
      parent::__construct($i,$section,$post, $postmeta);
      self::get($i,$section,$post,$postmeta);
    }

    function get(&$i,&$section,&$post,&$postmeta){

      if (is_string($this->data['value'])){

      if ( empty( $section['_panels_design_process-custom-field-shortcodes'] ) || $section['_panels_design_process-custom-field-shortcodes'] === 'process' ) {
        $this->data['value'] = do_shortcode( $this->data['value'] );
      } else {
        $this->data['value'] = strip_shortcodes( $this->data['value'] );
      }
      }

    }

    function render() {
      $content = '';
      if ( ! empty( $this->data['value'] ) && is_string($this->data['value'])) {
        $content = $this->data['value'];
      }
      return $content;
    }


  }
