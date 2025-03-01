<?php
  /**
   * Name: class_arc_cft_date.php
   * Author: chrishoward
   * Date: 15/6/19
   * Purpose:
   */


    function arc_cft_date_get( &$i, &$section, &$post, &$postmeta,$data ) {
      // Date settings
      $format = !empty($data['meta']['acf_settings']['return_format'])?$data['meta']['acf_settings']['return_format']:get_option('date_format');
      if (  ( $data['data']['field-source'] == 'acf' && $data['data']['use-acf-date-format'] ) ) {
        // this is for readability and simpler if statement
        // keeps same ACF value
      } else {
        $cfdate              = is_numeric( $data ['data']['value'] ) ? $data ['data']['value'] : strtotime( ArcFun::fix_date( $data ['data']['value'],$format) ); //convert field value to date
        $cfdate              = empty( $cfdate ) ? '000000' : $cfdate;
        $data['data']['data'] = "data-sort-date='{$cfdate}'";
       // $data['data']['value'] = date_i18n( $data['data']['date-format'], $cfdate );
        $data['data']['value'] = wp_date( $data['data']['date-format'], $cfdate ); //v11.3
      }
      if ( ! empty( $data['data']['value'] ) ) {
        // Might need this for masonary sorting...
        // $content = '<time datetime="' . $data['value'] . '" '.$data['data'].'>' . $data['value'] . '</time>';
        $data['data']['value'] = '<time datetime="' . $data['data']['value'] . '">' . $data['data']['value'] . '</time>';
      }

      return $data;

  }
