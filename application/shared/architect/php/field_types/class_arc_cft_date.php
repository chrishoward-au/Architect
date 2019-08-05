<?php
  /**
   * Name: class_arc_cft_date.php
   * Author: chrishoward
   * Date: 15/6/19
   * Purpose:
   */

  class arc_cft_date extends arc_cft {

    function __construct( $i, $section, &$post, &$postmeta ) {
      // parent::__construct( $i, $section, $post, $postmeta );
      self::get( $i, $section, $post, $postmeta );
    }

    function get( &$i, &$section, &$post, &$postmeta ) {
      // Date settings
      $format = !empty($this->meta['acf_settings']['return_format'])?$this->meta['acf_settings']['return_format']:get_option('date_format');
      if (  ( $this->data['field-source'] == 'acf' && $this->data['use-acf-date-format'] ) ) {
        // this is for readability and simpler if statement
        // keeps same ACF value
      } else {
        $cfdate              = is_numeric( $this->data ['value'] ) ? $this->data ['value'] : strtotime( ArcFun::fix_date( $this->data ['value'],$format) ); //convert field value to date
        $cfdate              = empty( $cfdate ) ? '000000' : $cfdate;
        $this->data ['data'] = "data-sort-date='{$cfdate}'";
        $this->data['value'] = date_i18n( $this->data['date-format'], $cfdate );
      }
    }

    function render() {
      $content = '';
      if ( ! empty( $this->data['value'] ) ) {
        // Might need this for masonary sorting...
        // $content = '<time datetime="' . $this->data['value'] . '" '.$this->data['data'].'>' . $this->data['value'] . '</time>';
        $content = '<time datetime="' . $this->data['value'] . '">' . $this->data['value'] . '</time>';
      }

      return $content;
    }
  }
