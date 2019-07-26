<?php
  /**
   * Name: class_arc_cft_number.php
   * Author: chrishoward
   * Date: 15/6/19
   * Purpose:
   */

  class arc_cft_number extends arc_custom_fields {

    function __construct($i,$section, &$post, &$postmeta) {
      parent::__construct($i,$section,$post, $postmeta);
      self::get($i,$section,$post,$postmeta);
    }

    function get(&$i,&$section,&$post,&$postmeta){
      // TODO: Process ACF settings e.g. prepend text
      // Numeric settings
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
        $content = @number_format( $this->data['value'], $this->data['decimals'], $this->data['decimal-char'], $this->data['thousands-sep'] );
      }

      return $content;
    }
  }
