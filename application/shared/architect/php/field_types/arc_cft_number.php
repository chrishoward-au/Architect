<?php
  /**
   * Name: class_arc_cft_number.php
   * Author: chrishoward
   * Date: 15/6/19
   * Purpose:
   */


    function arc_cft_number_get(&$i,&$section,&$post,&$postmeta,$data){
      // TODO: Process ACF settings e.g. prepend text
      // Numeric settings
      $prefix='';
      $suffix='';
    //  var_dump($data);

      if ( $data['data']['field-source'] == 'acf' ) {
        $field_object=get_field_object($data['data']['name']);
        $prefix=$field_object['prepend'];
        $suffix=$field_object['append'];
      }
      $data['data'] ['decimals']      = $section[ '_panels_design_cfield-' . $i . '-number-decimals' ];
      $data ['data']['decimal-char']  = $section[ '_panels_design_cfield-' . $i . '-number-decimal-char' ];
      $data ['data']['thousands-sep'] = $section[ '_panels_design_cfield-' . $i . '-number-thousands-separator' ];

      if ( $section[ '_panels_design_cfield-' . $i . '-field-type' ] === 'number' ) {
    //    $cfnumeric           = @number_format( $data ['value'], $data ['decimals'], '', '' );
        $cfnumeric           = @number_format( $data['data'] ['value'], $data ['data']['decimals'], '', '' );
        $cfnumeric           = empty( $cfnumeric ) ? '0000' : $cfnumeric;
        $data['data'] ['data'] = "data-sort-numeric='{$cfnumeric}'";
      }


      if ( ! empty( $data['data']['value'] ) ) {
        $data['data']['value'] = $prefix.@number_format( $data['data']['value'], $data['data']['decimals'], $data['data']['decimal-char'], $data['data']['thousands-sep'] ).$suffix;
      }

      return $data;
  }
