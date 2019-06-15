<?php
  /**
   * Name: class_arc_cft_date.php
   * Author: chrishoward
   * Date: 15/6/19
   * Purpose:
   */

  class arc_cft_date extends arc_custom_fields {

    function __construct($i,$section) {
        parent::__construct($i,$section,$post);
        $this->get();
    }
    function get( ) {
      // Date settings
      $this->data['date-format'] = $this->section[ '_panels_design_cfield-' . $this->i . '-date-format' ];
      if ( $this->section[ '_panels_design_cfield-' . $this->i . '-field-type' ] === 'date' ) {
        $cfdate              = is_numeric( $this->data ['value'] ) ? $this->data ['value'] : str_replace( ',', ' ', strtotime( $this->data ['value'] ) ); //convert field value to date
        $cfdate              = empty( $cfdate ) ? '000000' : $cfdate;
        $this->data ['data'] = "data-sort-date='{$cfdate}'";
      }

    }

    function render(){
    }
  }
