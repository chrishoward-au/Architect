<?php
  /**
   * Name: class_arc_cft_number.php
   * Author: chrishoward
   * Date: 15/6/19
   * Purpose:
   */

  class arc_cft_number extends arc_cft {

    function __construct($i,$section, &$post, &$postmeta) {
      // parent::__construct($i,$section,$post, $postmeta);
      self::get($i,$section,$post,$postmeta);
    }

    function get(&$i,&$section,&$post,&$postmeta){
      // TODO: Process ACF settings e.g. prepend text
      // Numeric settings
      $this->meta['prefix']='';
      $this->meta['suffix']='';
      if ( $this->data['field-source'] == 'acf' ) {
        $field_object=get_field_object($this->data['name']);
        $this->meta['prefix']=$field_object['prepend'];
        $this->meta['suffix']=$field_object['append'];
      }
      $this->data ['decimals']      = $section[ '_panels_design_cfield-' . $i . '-number-decimals' ];
      $this->data ['decimal-char']  = $section[ '_panels_design_cfield-' . $i . '-number-decimal-char' ];
      $this->data ['thousands-sep'] = $section[ '_panels_design_cfield-' . $i . '-number-thousands-separator' ];

      if ( $section[ '_panels_design_cfield-' . $i . '-field-type' ] === 'number' ) {
    //    $cfnumeric           = @number_format( $this->data ['value'], $this->data ['decimals'], '', '' );
        $cfnumeric           = @number_format( $this->data ['value'], $this->data ['decimals'], '', '' );
        $cfnumeric           = empty( $cfnumeric ) ? '0000' : $cfnumeric;
        $this->data ['data'] = "data-sort-numeric='{$cfnumeric}'";
      }

    }

    function render() {
      $content = '';
      if ( ! empty( $this->data['value'] ) ) {
        $content = $this->meta['prefix'].@number_format( $this->data['value'], $this->data['decimals'], $this->data['decimal-char'], $this->data['thousands-sep'] ).$this->meta['suffix'];
      }

      return $content;
    }
  }

  function arc_construct_image(){

  }
  function arc_get_number(&$i,&$section,&$post,&$postmeta,$arc_data){
    // TODO: Process ACF settings e.g. prepend text
    // Numeric settings
    $arc_meta['prefix']='';
    $arc_meta['suffix']='';
    if ( $arc_data['field-source'] == 'acf' ) {
      $field_object=get_field_object($arc_data['name']);
      $arc_meta['prefix']=$field_object['prepend'];
      $arc_meta['suffix']=$field_object['append'];
    }
    $arc_data ['decimals']      = $section[ '_panels_design_cfield-' . $i . '-number-decimals' ];
    $arc_data ['decimal-char']  = $section[ '_panels_design_cfield-' . $i . '-number-decimal-char' ];
    $arc_data ['thousands-sep'] = $section[ '_panels_design_cfield-' . $i . '-number-thousands-separator' ];

    if ( $section[ '_panels_design_cfield-' . $i . '-field-type' ] === 'number' ) {
      //    $cfnumeric           = @number_format( $this->data ['value'], $this->data ['decimals'], '', '' );
      $cfnumeric           = @number_format( $arc_data ['value'], $arc_data ['decimals'], '', '' );
      $cfnumeric           = empty( $cfnumeric ) ? '0000' : $cfnumeric;
      $arc_data ['data'] = "data-sort-numeric='{$cfnumeric}'";
    }
    return array('data'=>$arc_data,'meta'=>$arc_meta);
  }
  function arc_render_number($arc_data,$arc_meta){
    $arc_content = '';
    if ( ! empty( $arc_data['value'] ) ) {
      $arc_content = $arc_meta['prefix'].@number_format( $arc_data['value'], $arc_data['decimals'], $arc_data['decimal-char'], $arc_data['thousands-sep'] ).$arc_meta['suffix'];
    }

    return $arc_content;

  }
