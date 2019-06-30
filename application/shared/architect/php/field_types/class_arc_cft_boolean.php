<?php
  /**
   * Name: class_arc_cft_example.php
   * Author: chrishoward
   * Date: 15/6/19
   * Purpose:
   */

  class arc_cft_boolean extends arc_custom_fields {

    function __construct($i,$section, &$post, &$postmeta) {
      parent::__construct($i,$section,$post, $postmeta);
      self::get($i,$section,$post,$postmeta);
    }

    function get(&$i,&$section,&$post,&$postmeta){
      // add fields for true value and false value
      $field_val = '';
      if ( $this->data['field-source'] = 'acf' ) {
        if ( function_exists( 'get_field' ) ) {
          $file     = get_field( $this->data['name'] );
          $file_url = $file['url'];
        } elseif ( is_numeric( $this->data['value'] ) ) {
          $file     = wp_get_attachment_image_src( $this->data['value'] );
          $file_url = $file[0];
        }
      }

      if($this->data['value']) {
        $this->data['value']=$section[ '_panels_design_cfield-' . $i . '-true-value' ];
      } else {
        $this->data['value']=$section[ '_panels_design_cfield-' . $i . '-false-value' ];
      }

    }

    function render(){
      $content = '';
      if ( ! empty( $this->data['value'] ) ) {
        $content = $this->data['value'];

      }
      return $content;
    }


  }
