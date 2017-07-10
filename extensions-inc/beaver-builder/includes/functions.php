<?php
  /**
   * Created by PhpStorm.
   * User: chrishoward
   * Date: 10/6/17
   * Time: 6:27 PM
   */

  function pz_process_font_weight($pz_input){

    switch ($pz_input) {
      case 'regular':
        $pz_input ='400';
    }
    return $pz_input;
  }
