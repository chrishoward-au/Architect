<?php
  /**
   * Created by PhpStorm.
   * User: chrishoward
   * Date: 10/6/17
   * Time: 6:27 PM
   */

  /**
   * @param $pz_input
   *
   * @return string
   */
  function pz_process_font_weight($pz_input){

    switch ($pz_input) {
      case 'regular':
        $pz_input ='400';
    }
    return $pz_input;
  }

  /**
   * @param $blueprint_size
   * @param $pz_css
   * @param $id
   */
  function pz_render_module_css($blueprint_size,$pz_css,$id,$pz_css_header) {
    echo $pz_css_header;
    foreach ($pz_css['defaults'] as $k => $v) {
      switch ($k) {

        case 'breakpoint':
          break;

        case 'custom_css':
          if (!empty($v) && trim($v) != '.pzarc-blueprint {}') {
            echo '.fl-node-'.$id .' #pzarc-blueprint_'. $blueprint_size .' '.$v;
          }
          break;

        case '.pzarc-panel':
          $pz_generated_css = pzarc_generate_beaver_css($v);
          if (!empty($pz_generated_css)) {
            echo '.fl-node-'.$id .' #pzarc-blueprint_'. $blueprint_size .' .pzarc-panel_'. $blueprint_size .$k .'{';
            echo $pz_generated_css;
            echo '}';
          }
          break;

        case '.pzarc-blueprint':
          $pz_generated_css = pzarc_generate_beaver_css($v);
          if (!empty($pz_generated_css)) {
            echo '.fl-node-'.$id .' #pzarc-blueprint_'. $blueprint_size .$k.' {';
            echo $pz_generated_css;
            echo '}';
          }
          break;

        default:
          $pz_generated_css = pzarc_generate_beaver_css($v);
          if (!empty($pz_generated_css)) {
            echo '.fl-node-'.$id .' #pzarc-blueprint_'. $blueprint_size .' .pzarc-panel_'. $blueprint_size .' '.$k .'{';
            echo $pz_generated_css;
            echo '}';
          }
          break;
      }
    }

  }