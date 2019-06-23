<?php
  /**
   * Name: class_arc_cft_link.php
   * Author: chrishoward
   * Date: 15/6/19
   * Purpose:
   */

  class arc_cft_link extends arc_custom_fields {

    function __construct($i,$section, &$post, &$postmeta) {
      parent::__construct($i,$section,$post, $postmeta);
      self::get($i,$section,$post,$postmeta);
    }

    function get(&$i,&$section,&$post,&$postmeta){

    }

    function render(){
      $content = '';
      if ( ! empty( $this->data['value'] ) ) {
        $content = '<a href="' . $this->data['value'] . '" class="pzarc-link">' . ( ! empty( $this->data['link-text'] ) ? $this->data['link-text'] : $this->data['value'] ) . '</a>';
      }
      return $content;
    }

  }
