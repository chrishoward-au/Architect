<?php
  /**
   * Created by PhpStorm.
   * User: chrishoward
   * Date: 9/7/17
   * Time: 11:53 PM
   */

  function pzarc_generate_beaver_css($field_styling) {
    $css_styling = '';
    foreach ($field_styling as $k => $v) {
      switch ($k) {

        case 'font':
          $css_styling .= !empty($v->family) && strtolower($v->family) != 'default' ? 'font-family:"' . $v->family . '";' : '';
          $css_styling .= !empty($v->weight) && strtolower($v->weight) != 'default' ? 'font-weight:' . pz_process_font_weight($v->weight) . ';' : '';
          break;

        case  'color':
        case  'background-color':
          $css_styling .= !empty($v) ? $k.': #' . $v . ';' : '';
          break;

        case 'font-size':
        case 'letter-spacing':
        case 'word-spacing':
        case 'line-height':
        case 'margin-top':
        case 'margin-bottom':
        case 'margin-left':
        case 'margin-right':
        case 'padding-top':
        case 'padding-bottom':
        case 'padding-left':
        case 'padding-right':
          if (!empty($v->number)) {
            $css_styling .= $k.':' . $v->number;
            $css_styling .= $v->type . ';';
          }
          break;

        case 'border-top':
        case 'border-bottom':
        case 'border-left':
        case 'border-right':
          if (!empty($v->number) && !empty($v->border_style) && $v->border_style != 'none') {
            $css_styling .= $k.':' . $v->number;
            $css_styling .= $v->type . ' ';
            $css_styling .= $v->colour . ' ';
            $css_styling .= $v->border_style . ';';
          }
          break;


        case  'background-image':
          $css_styling .= !empty($v) ? $k.': url("' . wp_get_attachment_image_url($v) . '");' : '';
          break;

        case 'text-align':
        case 'text-decoration':
        case 'text-transform':
        case 'font-style':
        case 'font-variant':
        case 'white-space':
        case 'word-break':
        case 'background-attachment':
        case 'background-position-x':
        case 'background-position-y':
        case 'background-repeat':
        case 'background-size':
        case 'background-origin':
        case 'background-clip':
          $css_styling .= !empty($v) ? $k.':' . $v . ';' : '';
          break;

      }

    }
    return $css_styling;
  }
