<?php
  /**
   * Name: class_arc_cft_example.php
   * Author: chrishoward
   * Date: 15/6/19
   * Purpose:
   */

  class arc_cft_example extends arc_custom_fields {

    function __construct($i,$section, &$post, &$postmeta) {
      parent::__construct($i,$section,$post, $postmeta);
      self::get($i,$section,$post,$postmeta);
    }

    function get(&$i,&$section,&$post,&$postmeta){

    }

    function render(){
      $content = '';
      if ( ! empty( $this->data['value'] ) ) {
        $content = $this->data['value'];

      }
      return $content;
    }


  }
