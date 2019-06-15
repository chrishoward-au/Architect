<?php
  /**
   * Name: class_arc_cft_text.php
   * Author: chrishoward
   * Date: 15/6/19
   * Purpose:
   */

  class arc_cft_text extends arc_custom_fields {

    function __construct( $i, $section, &$post,&$postmeta ) {
      parent::__construct( $i, $section, $post,$postmeta );
      self::get();
    }

    function get() {

    }

    function render() {
    }
  }
