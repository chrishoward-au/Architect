<?php

  function pzarc_create_panels_css($pzarc_panels, $pzarc_contents, $postid)
  {

    global $pzarchitect;
    global $_architect_options;

    $nl = "\n";

    $pzarc_contents .= '/* This is the css for panel $postid ' . $pzarc_panels[ '_panels_settings_short-name' ] . '*/' . $nl;

    // Step thru each field looking for ones to format
    $class_prefix = '.pzarchitect .pzarc-panel_' . $pzarc_panels[ '_panels_settings_short-name' ];

    foreach ($pzarc_panels as $key => $value) {

      /**
       * Panels settings and design
       */
      switch (true) {

        case ($key == '_panels_settings_panel-height'):

          if ($pzarc_panels[ '_panels_settings_panel-height-type' ] !== 'none') {

            $pzarc_contents .= $class_prefix . ' {' . $pzarc_panels[ '_panels_settings_panel-height-type' ] . ':' . $value[ 'height' ] . ';}' . $nl;

          }
          break;

        case ($key == '_panels_design_responsive-hide-content' && $pzarc_panels[ '_panels_design_responsive-hide-content' ] !== 'none'):

          $em_width = (int)str_replace('px', '', $_architect_options[ 'architect_breakpoint_' . $pzarc_panels[ '_panels_design_responsive-hide-content' ] ][ 'width' ]) / 16;
          $pzarc_contents .= '@media (max-width: ' . $em_width . 'em;) { ' . $class_prefix . ' .entry-content, ' . $class_prefix . ' .entry-excerpt {display:none!important;}}' . $nl;

          break;


        case ($key == '_panels_design_preview'):
          $pzarc_left_margin  = (!empty($pzarc_panels[ '_panels_design_image-margin-left' ]) ? $pzarc_panels[ '_panels_design_image-margin-left' ] : 0);
          $pzarc_right_margin = (!empty($pzarc_panels[ '_panels_design_image-margin-right' ]) ? $pzarc_panels[ '_panels_design_image-margin-right' ] : 0);
          $pzarc_layout       = json_decode($value, true);

          switch ($pzarc_panels[ '_panels_design_title-prefix' ]) {

            case 'none':
            case 'thumb':
              $titles_bullets = '';
              break;

            case 'disc':
            case 'circle':
            case 'square':
              $titles_bullets = 'list-style:' . $pzarc_panels[ '_panels_design_title-prefix' ] . ' outside none;display:list-item;';
              break;

            case 'decimal':
            case 'decimal-leading-zero':
            case 'upper-roman':
            case 'lower-roman':
            case 'upper-greek':
            case 'lower-greek':
            case 'lower-latin':
            case 'upper-latin':
            case 'armenian':
            case 'georgian':
            case 'lower-alpha':
            case 'upper-alpha':
              // TODO: Is it possible to make this CSS based?
              $pzarc_contents .= $class_prefix . ' .entry-title {counter-increment: arc-title-counter;}' . $nl;
              $pzarc_contents .= $class_prefix . ' .entry-title:before {content: counter(arc-title-counter, ' . $pzarc_panels[ '_panels_design_title-prefix' ] . ')"' . $pzarc_panels[ '_panels_design_title-bullet-separator' ] . '";}' . $nl;

              break;
          }

          $pzarc_contents .= $class_prefix . ' .entry-title {width:' . $pzarc_layout[ 'title' ][ 'width' ] . '%;' . $titles_bullets . '}' . $nl;


          // Don't give thumbnail div a width if it's in the content
          //            var_dump($pzarc_panels);
//              if ($pzarc_panels[ '_pzarc_layout-excerpt-thumb' ] == 'none') {
////                $pzarc_contents .= $class_prefix . ' .entry-thumbnail {width:' . $pzarc_layout[ 'image' ][ 'width' ] . '%;max-width:' . ($pzarc_layout[ 'image' ][ 'width' ] - $pzarc_left_margin - $pzarc_right_margin) . '%;}' . "\n";
//              } else {
          $margins = pzarc_process_spacing($pzarc_panels[ '_panels_design_image-spacing' ]);
          $left    = $pzarc_panels[ '_panels_design_image-spacing' ][ 'margin-left' ];
          $left    = preg_replace("/([\\%px])/uiUm", "", $left);
          $right   = $pzarc_panels[ '_panels_design_image-spacing' ][ 'margin-right' ];
          $right   = preg_replace("/([\\%px])/uiUm", "", $right);
          $pzarc_contents .= $class_prefix . ' .entry-thumbnail {width:' . ($pzarc_layout[ 'image' ][ 'width' ] - $left - $right) . '%;max-width:' . $pzarc_panels[ '_panels_design_image-max-width' ] . 'px;margin: ' . $margins . ';}' . $nl;
//              }

          $pzarc_contents .= $class_prefix . ' .entry-content {width:' . $pzarc_layout[ 'content' ][ 'width' ] . '%;}' . $nl;
          $pzarc_contents .= $class_prefix . ' .entry-excerpt {width:' . $pzarc_layout[ 'excerpt' ][ 'width' ] . '%;}' . $nl;
          $pzarc_contents .= $class_prefix . ' .entry-meta1 {width:' . $pzarc_layout[ 'meta1' ][ 'width' ] . '%;}' . $nl;
          $pzarc_contents .= $class_prefix . ' .entry-meta2 {width:' . $pzarc_layout[ 'meta2' ][ 'width' ] . '%;}' . $nl;
          $pzarc_contents .= $class_prefix . ' .entry-meta3 {width:' . $pzarc_layout[ 'meta3' ][ 'width' ] . '%;}' . $nl;
          $pzarc_contents .= $class_prefix . ' .entry-customfieldgroup-1 {width:' . $pzarc_layout[ 'custom1' ][ 'width' ] . '%;}' . $nl;
          $pzarc_contents .= $class_prefix . ' .entry-customfieldgroup-2 {width:' . $pzarc_layout[ 'custom2' ][ 'width' ] . '%;}' . $nl;
          $pzarc_contents .= $class_prefix . ' .entry-customfieldgroup-3 {width:' . $pzarc_layout[ 'custom3' ][ 'width' ] . '%;}' . $nl;
          break;

//            case ($key == '_panels_design_image-margin-left' && ($value === 0 || $value > 0)):
//              $pzarc_contents .= $class_prefix . ' .entry-thumbnail {margin-left: ' . $value . '%;}' . "\n";
//              break;
//
//            case ($key == '_panels_design_image-margin-right' && ($value === 0 || $value > 0)):
//              $pzarc_contents .= $class_prefix . ' .entry-thumbnail {margin-right: ' . $value . '%;}' . "\n";
//              break;
//
//            case ($key == '_panels_design_image-margin-top' && ($value === 0 || $value > 0)):
//              $pzarc_contents .= $class_prefix . ' .entry-thumbnail {margin-top: ' . $value . '%;}' . "\n";
//              break;
//
//            case ($key == '_panels_design_image-margin-bottom' && ($value === 0 || $value > 0)):
//              $pzarc_contents .= $class_prefix . ' .entry-thumbnail {margin-bottom: ' . $value . '%;}' . "\n";
//              break;

        case ($key == '_panels_design_thumb-position'):

          if ($value != 'none') {

            $margins = pzarc_process_spacing($pzarc_panels[ '_panels_design_image-spacing' ]);
            $twidth  = $pzarc_panels[ '_panels_design_thumb-width' ] - ($pzarc_panels[ '_panels_design_image-margin-left' ] + $pzarc_panels[ '_panels_design_image-margin-right' ]);
            $pzarc_contents .= $class_prefix . ' .in-content-thumb {width:' . $twidth . '%;float:' . $value . '!important;' . $margins . '}' . $nl;

          }
          break;
        case ($key == '_panels_design_background-position'):

          if ($value != 'none') {

          }
          break;

        case ($key == '_panels_design_components-position'):

          if ($value != 'none') {

          }
          break;

        /********************************************************
         *    PANELS STYLING
         *********************************************************/

        case (strpos($key, '_panels_styling') === 0 && !empty($value)):
          //           var_dump($key,$value);
          switch (true) {

            ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            /** Overall */
            ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            case $key === '_panels_styling_panels-background' :
              $this_key = key($value);
              if (!empty($value[ 'color' ])) {
                $pzarc_contents .= $class_prefix . '.pzarc-panel {background-color:' . $value[ 'color' ] . ';}' . $nl;
              }
              break;

            case $key === '_panels_styling_panels-padding' :
              $filler = '';
              foreach ($value as $k => $v) {
                $filler .= (strpos($k, 'padding') === 0 && !empty($v) ? $k . ':' . $v . ';' : '');
              }
              $pzarc_contents .= (!empty($filler) ? $class_prefix . '.pzarc-panel {' . $filler . '}' . $nl : '');
              break;

            case ($key === '_panels_styling_panels-borders'):
              if (($value[ 'border-style' ] !== 'none')) {
                $pzarc_contents .= pzarc_process_borders($class_prefix . '.pzarc-panel', $value) . $nl;
              }
              break;

            ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            /** COMPONENTS */
            ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            case $key === '_panels_styling_components-background' :
              $this_key = key($value);
              if (!empty($value[ 'color' ])) {
                $pzarc_contents .= $class_prefix . ' .pzarc-components {background-color:' . $value[ 'color' ] . ';}' . $nl;
              }
              break;

            case $key === '_panels_styling_components-padding' :
              $padding = pzarc_process_spacing($value);
              if (!empty($padding)) {
                $pzarc_contents .= $class_prefix . ' .pzarc-components {' . $padding . '}' . $nl;
              }
              break;

            case ($key === '_panels_styling_components-borders'):
              if (($value[ 'border-style' ] !== 'none')) {
                $pzarc_contents .= pzarc_process_borders($class_prefix . ' .pzarc-components', $value) . $nl;
              }
              break;

            //TODO: Really got to make this dumb!
            ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            /** Titles */
            ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            case $key === '_panels_styling_entry-title-font' :
              $pzarc_contents .= pzarc_process_fonts($class_prefix . ' .entry-title', $value) . $nl;
              break;

            case ($key === '_panels_styling_entry-title-font-links'):
              $pzarc_contents .= pzarc_process_links($class_prefix . ' .entry-title ', $value, $nl) . $nl;
              break;

            case ($key === '_panels_styling_entry-title-borders'):
              if (($value[ 'border-style' ] !== 'none')) {
                $pzarc_contents .= pzarc_process_borders($class_prefix . ' .entry-title', $value) . $nl;
              }
              break;

            case $key === '_panels_styling_entry-title-font-padding' :
              $padding = pzarc_process_spacing($value);
              $pzarc_contents .= $class_prefix . ' .entry-title {' . $padding . ';}' . $nl;
              break;

            case $key === '_panels_styling_entry-title-font-margin' :
              $margins = pzarc_process_spacing($value);
              $pzarc_contents .= $class_prefix . ' .entry-title {' . $margins . ';}' . $nl;
              break;


            ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            /** Meta */
            ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            case $key === '_panels_styling_entry-meta-font' :
              $pzarc_contents .= pzarc_process_fonts($class_prefix . ' .entry-meta', $value) . $nl;
              break;

            case ($key === '_panels_styling_entry-meta-font-links'):
//                  var_dump($key,$value);
              $pzarc_contents .= pzarc_process_links($class_prefix . ' .entry-meta ', $value, $nl) . $nl;
              break;

            ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            /** IMAGES */
            ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

            case $key === '_panels_styling_entry-image-background' :
              $pzarc_contents .= pzarc_process_background($class_prefix . ' figure.entry-thumbnail ', $value) . $nl;
              break;

            case $key === '_panels_styling_entry-image-padding' :
              //              var_dump($key);
              $padding = pzarc_process_spacing($value);
              $pzarc_contents .= $class_prefix . ' figure.entry-thumbnail  {' . $padding . ';}' . $nl;
              break;

            case ($key === '_panels_styling_entry-image-borders'):
              if (($value[ 'border-style' ] !== 'none')) {
                $pzarc_contents .= pzarc_process_borders($class_prefix . ' figure.entry-thumbnail', $value) . $nl;
              }
              break;


            ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            /** Content */
            ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            case $key === '_panels_styling_entry-content-font' :

              //var_dump($key);
              $pzarc_contents .= pzarc_process_fonts($class_prefix . ' .entry-content', $value) . $nl;
              $pzarc_contents .= pzarc_process_fonts($class_prefix . ' .entry-excerpt', $value) . $nl;
              break;

            case ($key === '_panels_styling_entry-content-font-links'):
              $pzarc_contents .= pzarc_process_links($class_prefix . ' .entry-content ', $value, $nl) . $nl;
              $pzarc_contents .= pzarc_process_links($class_prefix . ' .entry-excerpt ', $value, $nl) . $nl;
              break;

            ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            /** Captions */
            ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            //entry-image-caption-font', array('figure.entry-thumbnail span.caption'
            case $key === '_panels_styling_entry-image-caption-font' :
              //              var_dump($key);
              $pzarc_contents .= pzarc_process_fonts($class_prefix . ' figure.entry-thumbnail span.caption ', $value) . $nl;
              break;

            case $key === '_panels_styling_entry-image-caption-font-background' :
              $pzarc_contents .= pzarc_process_background($class_prefix . ' figure.entry-thumbnail span.caption ', $value) . $nl;
//                  $pzarc_contents .= pzarc_process_bg($class_prefix . ' figure.entry-thumbnail span.caption {', $value) . $nl;
              break;


            case $key === '_panels_styling_entry-image-caption-font-padding' :
              //              var_dump($key);
              $padding = pzarc_process_spacing($value);
              $pzarc_contents .= $class_prefix . ' figure.entry-thumbnail span.caption {' . $padding . ';}' . $nl;
              break;


            ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            /** Custom */
            ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            case $key === '_panels_styling_entry-customfield-1-font' :
              //              var_dump($key);
              $pzarc_contents .= pzarc_process_fonts($class_prefix . ' .entry-customfield-1 ', $value) . $nl;
              break;

            case $key === '_panels_styling_entry-customfield-1-font-background' :
              $pzarc_contents .= pzarc_process_background($class_prefix . ' .entry-customfield-1 ', $value) . $nl;
//                  $pzarc_contents .= pzarc_process_bg($class_prefix . ' figure.entry-thumbnail span.caption {', $value) . $nl;
              break;


            case $key === '_panels_styling_entry-customfield-1-font-padding' :
              //              var_dump($key);
              $padding = pzarc_process_spacing($value);
              $pzarc_contents .= $class_prefix . ' .entry-customfield-1 {' . $padding . ';}' . $nl;
              break;

            case ($key === '_panels_styling_entry-customfield-1-font-links'):
              $pzarc_contents .= pzarc_process_links($class_prefix . ' .entry-customfield-1 ', $value, $nl) . $nl;
              break;

            case ($key === '_panels_styling_entry-customfield-1-borders'):
              if (($value[ 'border-style' ] !== 'none')) {
                $pzarc_contents .= pzarc_process_borders($class_prefix . ' .entry-customfield-1', $value) . $nl;
              }
              break;


            // TODO:What is this lot and why here?
            ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            case $key === '_panels_styling_hentry-margin' :
            case $key === '_panels_styling_components-group-margin' :
            case $key === '_panels_styling_panels-margin' :
              $this_key = key($value);
              // architect_config_hentry-selectors
              $options_key = str_replace('_panels_styling_', 'architect_config_', $key);
              $options_key = str_replace('-margin', '-selectors', $options_key);
              $classes     = (is_array($pzarchitect[ $options_key ]) ? $class_prefix . ' ' . implode(', ' . $class_prefix . ' ', $pzarchitect[ $options_key ]) : $class_prefix . ' ' . $pzarchitect[ $options_key ]);
              $margins     = pzarc_process_spacing($pzarc_panels[ $key ]);
              $pzarc_contents .= (!empty($margins) ? $classes . ' {' . $margins . '}' . $nl : '');
              //    var_dump($class_prefix,$classes . ' {' . $margins . '}');
              break;

            // Custom CSS
            case $key === '_panels_styling_custom-css':
              $pzarc_contents .= $value;
              break;
            default :
              //             var_Dump($key);
              break;

          }
          break;
      }
//              $fieldname = str_replace('_pzarc_layout-format-', '', $key);
////            var_dump($fieldname,$key,$prefix.$fieldname.'-class',$defaults[ $prefix.$fieldname.'-class'],$value);
//              $pzarc_classes = $class_prefix . ' ' . str_replace(',', ', .pzarchitect .pzarc-' . $postid . ' ', $defaults[ $prefix . $fieldname . '-class' ]);
//              $pzarc_contents .= $pzarc_classes . ' {' . $value . '}' . "\n";
      //         break;
    }


    //     var_dump($key,strpos($key, '-format-') , !empty($value) , !empty($pzarc_panels[ $key . '-classes' ]));
    $pzarc_components_position = (!empty($pzarc_panels[ '_panels_design_components-position' ]) ? $pzarc_panels[ '_panels_design_components-position' ] : 'top');
    $pzarc_components_nudge_x  = (!empty($pzarc_panels[ '_panels_design_components-nudge-x' ]) ? $pzarc_panels[ '_panels_design_components-nudge-x' ] : 0);
    $pzarc_components_nudge_y  = (!empty($pzarc_panels[ '_panels_design_components-nudge-y' ]) ? $pzarc_panels[ '_panels_design_components-nudge-y' ] : 0);
    $pzarc_components_width    = (!empty($pzarc_panels[ '_panels_design_components-widths' ]) ? $pzarc_panels[ '_panels_design_components-widths' ] : 100);
    $pzarc_tb                  = ($pzarc_components_position == 'left' || $pzarc_components_position == 'right' ? 'top' : $pzarc_components_position);
    $pzarc_lr                  = ($pzarc_components_position == 'top' || $pzarc_components_position == 'bottom' ? 'left' : $pzarc_components_position);

    // TODO: Why is using bgimages here??
    // TODO: Extend this for multiple breakpoints
    // Put this in an if since it's only when we are using bg images.
    // NOTE: It can affect wide screen content if you set the image smaller than the panel width
//    $pzarc_contents .= $class_prefix . '.using-bgimages .pzarc-components{max-width:' . $pzarc_panels[ '_panels_design_background-image-max' ][ 'width' ] . ';' . $pzarc_tb . ':' . $pzarc_components_nudge_y . '%;' . $pzarc_lr . ':' . $pzarc_components_nudge_x . '%;width:' . $pzarc_components_width . '%;}';
    $pzarc_contents .= $class_prefix . '.using-bgimages .pzarc-components{' . $pzarc_tb . ':' . $pzarc_components_nudge_y . '%;' . $pzarc_lr . ':' . $pzarc_components_nudge_x . '%;width:' . $pzarc_components_width . '%;}';

//    $pzarc_contents .= $class_prefix . ' .pzarc-components{max-width:'.$pzarc_panels['_panels_design_background-image-max']['width'].';' . $pzarc_tb . ':' . $pzarc_components_nudge_y . '%;' . $pzarc_lr . ':' . $pzarc_components_nudge_x . '%;width:' . $pzarc_components_width . '%;}';

    return $pzarc_contents;
  }
