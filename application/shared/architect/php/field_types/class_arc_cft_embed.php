<?php
  /**
   * Name: class_arc_cft_embed.php
   * Author: chrishoward
   * Date: 15/6/19
   * Purpose:
   */

  class arc_cft_embed extends arc_cft {

    function __construct($i,$section, &$post, &$postmeta) {
      // parent::__construct($i,$section,$post, $postmeta);
      self::get($i,$section,$post,$postmeta);
    }

    function get(&$i,&$section,&$post,&$postmeta){

    }

    function render(){
      $content = '';
      if ( ! empty( $this->data['value'] ) ) {
        if ($this->data['field-source']=='acf') {
          $content = $this->data['value'];
        } else {
          $content = wp_oembed_get( $this->data['value'] );
        }
      }
      return $content;
    }

  }
