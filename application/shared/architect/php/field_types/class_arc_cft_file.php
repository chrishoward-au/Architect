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
      if ( $this->data['field-source'] == 'acf' ) {
        switch ( TRUE ) {
          case( is_Array( $this->data['value'] ) ):
            $file_url = $this->data['value']['url'];
            break;
          case( is_numeric( $this->data['value'] ) ):
            $file_url = wp_get_attachment_url( $this->data['value']);
            break;
          case( is_string( $this->data['value'] ) ):
            $file_url = $this->data['value'];
            break;
          default:
            $file_url = '';
        }
      }

      $this->data['value']= !empty($file_url)?$file_url:$this->data['value'];
    }

    function render(){
      $content='';
      if ( ! empty( $this->data['value'] ) ) {
        $content = '<a href="' . $this->data['value'] . '" class="pzarc-acf-file" target="'.$this->data['link-behaviour'].'">' . ($this->data['link-text']!='<span class="pzarc-link-text"></span>'?$this->data['link-text']:'<span class="pzarc-link-text">'.$this->data['value'].'</span>') . '</a>';
      }

      return $content;
    }
  }
