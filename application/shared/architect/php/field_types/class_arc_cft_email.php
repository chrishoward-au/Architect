<?php
  /**
   * Name: class_arc_cft_email.php
   * Author: chrishoward
   * Date: 15/6/19
   * Purpose:
   */

  class arc_cft_email extends arc_custom_fields {

    function __construct($i,$section, &$post) {
        parent::__construct($i,$section,$post);
        $this->get();
    }
    function get( ) {

    }

    function render(){
    }
  }
