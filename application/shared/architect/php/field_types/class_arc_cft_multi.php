<?php
  /**
   * Name: class_arc_cft_multi.php
   * Author: chrishoward
   * Date: 15/6/19
   * Purpose:
   */

  class arc_cft_multi extends arc_custom_fields {

    function __construct($i,$section, &$post, &$postmeta) {
      parent::__construct($i,$section,$post, $postmeta);
      self::get($i,$section,$post,$postmeta);
    }

    function get(&$i,&$section,&$post,&$postmeta){

    }

    function render(){
      $content = '';
      if ( ! empty( $this->data['value'] ) ) {
        foreach ( $this->data['value'] as $k=>$v ) {
          // add fields for html tag, separator, prefix, and suffix
          $content .= '<span class="arc-multi-value">'.$v.'</span>';
        }

      }
      return $content;
    }


  }
