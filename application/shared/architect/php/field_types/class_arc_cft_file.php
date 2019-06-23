<?php
  /**
   * Name: class_arc_cft_file.php
   * Author: chrishoward
   * Date: 15/6/19
   * Purpose:
   */

  class arc_cft_file extends arc_custom_fields {

    function __construct($i,$section, &$post, &$postmeta) {
      parent::__construct($i,$section,$post, $postmeta);
      self::get($i,$section,$post,$postmeta);
    }

    function get(&$i,&$section,&$post,&$postmeta){
      $file_url = '';
      if ( $this->data['field-source'] = 'acf' ) {
        if ( function_exists( 'get_field' ) ) {
          $file     = get_field( $this->data['name'] );
          $file_url = $file['url'];
        } elseif ( is_numeric( $this->data['value'] ) ) {
          $file     = wp_get_attachment_image_src( $this->data['value'] );
          $file_url = $file[0];
        }
      }
      $this->data['value']= !empty($file_url)?$file_url:$this->data['value'];
    }

    function render(){
      $content='';
      if ( ! empty( $this->data['value'] ) ) {
        $content = '<a href="' . $this->data['value'] . '" class="pzarc-acf-file" target="'.$this->data['link-behaviour'].'">' . $this->data['link-text'] . '</a>';
      }
      return $content;
    }
  }
