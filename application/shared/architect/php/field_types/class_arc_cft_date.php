<?php
  /**
   * Name: class_arc_cft_date.php
   * Author: chrishoward
   * Date: 15/6/19
   * Purpose:
   */

  class arc_cft_date extends arc_custom_fields {

    function __construct($i,$section, &$post, &$postmeta) {
      parent::__construct($i,$section,$post, $postmeta);
      self::get($i,$section,$post,$postmeta);
    }

    function get(&$i,&$section,&$post,&$postmeta){
        // Date settings
      $this->data['date-format'] = $section[ '_panels_design_cfield-' . $i . '-date-format' ];
      if ( $section[ '_panels_design_cfield-' . $i . '-field-type' ] === 'date' ) {
        $cfdate              = is_numeric( $this->data ['value'] ) ? $this->data ['value'] : str_replace( ',', ' ', strtotime( $this->data ['value'] ) ); //convert field value to date
        $cfdate              = empty( $cfdate ) ? '000000' : $cfdate;
        $this->data ['data'] = "data-sort-date='{$cfdate}'";
      }
      if ( is_numeric( $this->data['value'] ) ) {
        $this->data['value'] = date( $this->data['date-format'], $this->data['value'] );
      }

    }

    function render(){
      $content ='';
      if (!empty($this->data['value'])) {
        $content = '<time datetime="' . $this->data['value'] . '">' . $this->data['value'] . '</time>';
      }
      return $content;
    }
  }
