<?php

  function pzarc_create_panels_css($pzarc_panels, $pzarc_contents, $postid)
  {

    global $_architect;
    global $_architect_options;

    $nl = "\n";

    $pzarc_contents .= '/* This is the css for panel ' . $pzarc_panels[ '_panels_settings_short-name' ] . '*/' . $nl;

    // Step thru each field looking for ones to format
    $class_prefix = 'body.pzarchitect .pzarc-panel_' . $pzarc_panels[ '_panels_settings_short-name' ];

    $toshow = json_decode($pzarc_panels[ '_panels_design_preview' ], true);

    $pzarc_components_position = (!empty($pzarc_panels[ '_panels_design_components-position' ]) ? $pzarc_panels[ '_panels_design_components-position' ] : 'top');
    $pzarc_components_nudge_x  = (!empty($pzarc_panels[ '_panels_design_components-nudge-x' ]) ? $pzarc_panels[ '_panels_design_components-nudge-x' ] : 0);
    $pzarc_components_nudge_y  = (!empty($pzarc_panels[ '_panels_design_components-nudge-y' ]) ? $pzarc_panels[ '_panels_design_components-nudge-y' ] : 0);
    $pzarc_components_width    = (!empty($pzarc_panels[ '_panels_design_components-widths' ]) ? $pzarc_panels[ '_panels_design_components-widths' ] : 100);
    $pzarc_tb                  = ($pzarc_components_position == 'left' || $pzarc_components_position == 'right' ? 'top' : $pzarc_components_position);
    $pzarc_lr                  = ($pzarc_components_position == 'top' || $pzarc_components_position == 'bottom' ? 'left' : $pzarc_components_position);

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

        case ($key == '_panels_design_responsive-hide-content' && !empty($pzarc_panels[ '_panels_design_responsive-hide-content' ]) && $pzarc_panels[ '_panels_design_responsive-hide-content' ] !== 'none'):
          $em_width = (int)str_replace('px', '', $_architect_options[ 'architect_breakpoint_' . $pzarc_panels[ '_panels_design_responsive-hide-content' ] ][ 'width' ]) / 16;
          $pzarc_contents .= '@media (max-width: ' . $em_width . 'em) { ' . $class_prefix . ' .entry-content, ' . $class_prefix . ' .entry-excerpt {display:none!important;}}' . $nl;

          break;

        // TODO: Lazy ass! Work outhow to do this in case statement!
        case ($key == '_panels_design_content-font-size-bp1' && !empty($pzarc_panels[ '_panels_design_content-font-size-bp1' ]) && !empty($pzarc_panels[ '_panels_design_use-responsive-font-size' ])):

          $em_width = (int)str_replace('px', '', $_architect_options[ 'architect_breakpoint_1' ][ 'width' ]) / 16;

          $pzarc_contents .= '@media (min-width: ' . $em_width . 'em) { ' . $class_prefix . ' .entry-content, ' . $class_prefix . ' .entry-excerpt {font-size:' . $pzarc_panels[ '_panels_design_content-font-size-bp1' ][ 'font-size' ] . '!important;line-height:' . $pzarc_panels[ '_panels_design_content-font-size-bp1' ][ 'line-height' ] . ';!important}}' . $nl;

          break;

        case ($key == '_panels_design_content-font-size-bp2' && !empty($pzarc_panels[ '_panels_design_content-font-size-bp2' ]) && !empty($pzarc_panels[ '_panels_design_use-responsive-font-size' ])):

          $em_widthU = (int)str_replace('px', '', $_architect_options[ 'architect_breakpoint_1' ][ 'width' ]) / 16;
          $em_widthL = (int)str_replace('px', '', $_architect_options[ 'architect_breakpoint_2' ][ 'width' ]) / 16;

          $pzarc_contents .= '@media (min-width: ' . $em_widthL . 'em and max-width: ' . $em_widthU . 'em) { ' . $class_prefix . ' .entry-content, ' . $class_prefix . ' .entry-excerpt {font-size:' . $pzarc_panels[ '_panels_design_content-font-size-bp2' ][ 'font-size' ] . '!important;line-height:' . $pzarc_panels[ '_panels_design_content-font-size-bp2' ][ 'line-height' ] . '!important;}}' . $nl;

          break;

        case ($key == '_panels_design_content-font-size-bp3' && !empty($pzarc_panels[ '_panels_design_content-font-size-bp3' ]) && !empty($pzarc_panels[ '_panels_design_use-responsive-font-size' ])):

          $em_width = (int)str_replace('px', '', $_architect_options[ 'architect_breakpoint_2' ][ 'width' ]) / 16;

          $pzarc_contents .= '@media (max-width: ' . $em_width . 'em) { ' . $class_prefix . ' .entry-content, ' . $class_prefix . ' .entry-excerpt {font-size:' . $pzarc_panels[ '_panels_design_content-font-size-bp3' ][ 'font-size' ] . '!important;line-height:' . $pzarc_panels[ '_panels_design_content-font-size-bp3' ][ 'line-height' ] . '!important;}}' . $nl;

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
          $pzarc_contents .= $class_prefix . ' .td-entry-title {width:' . $pzarc_layout[ 'title' ][ 'width' ] . '%;' . $titles_bullets . '}' . $nl;


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

          if ('float' === $pzarc_panels[ '_panels_design_feature-location' ]) {
            if ('top'===$pzarc_components_position || 'bottom'===$pzarc_components_position) {
              $pzarc_contents .= $class_prefix . ' .entry-thumbnail {width:' . (100  - $left - $right) . '%;max-width:' . $pzarc_panels[ '_panels_design_image-max-dimensions' ][ 'width' ] . ';' . $margins . ';}' . $nl;
            }else {
              $pzarc_contents .= $class_prefix . ' .entry-thumbnail {width:' . max((100 - $pzarc_components_width - $left - $right), 0) . '%;max-width:' . $pzarc_panels[ '_panels_design_image-max-dimensions' ][ 'width' ] . ';' . $margins . ';float:'.('left'===$pzarc_components_position?'right':'left').'}' . $nl;

            }
          } elseif ('fill' === $pzarc_panels[ '_panels_design_feature-location' ] && 'image' === $pzarc_panels[ '_panels_settings_feature-type' ]) {
            $pzarc_contents .= $class_prefix . ' .entry-thumbnail {width:100%;max-width:' . $pzarc_panels[ '_panels_design_image-max-dimensions' ][ 'width' ] . ';}' . $nl;
          } elseif ('video' === $pzarc_panels[ '_panels_settings_feature-type' ]) {
            $pzarc_contents .= $class_prefix . ' .entry-thumbnail {width:' . ($pzarc_layout[ 'image' ][ 'width' ] - $left - $right) . '%;}' . $nl;
          } else {
            $pzarc_contents .= $class_prefix . ' .entry-thumbnail {width:' . ($pzarc_layout[ 'image' ][ 'width' ] - $left - $right) . '%;max-width:' . $pzarc_panels[ '_panels_design_image-max-dimensions' ][ 'width' ] . ';' . $margins . ';}' . $nl;
          }
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


        // This is in content
        case ($key == '_panels_design_feature-location'):

          if ($value === 'content-left' || $value === 'content-right') {

            $margins = pzarc_process_spacing($pzarc_panels[ '_panels_design_image-spacing' ]);
            $twidth  = $pzarc_panels[ '_panels_design_thumb-width' ] - (str_replace('%', '', $pzarc_panels[ '_panels_design_image-spacing' ][ 'margin-left' ]) + str_replace('%', '', $pzarc_panels[ '_panels_design_image-spacing' ][ 'margin-right' ]));
            $float   = $value === 'content-left' ? 'left' : 'right';
            $pzarc_contents .= $class_prefix . ' .in-content-thumb {width:' . $twidth . '%;float:' . $float . '!important;' . $margins . '}' . $nl;
          }

          if ($value === 'fill') {

          }

          if ($value === 'float') {

          }
          break;

        case ($key == '_panels_design_components-position'):

          if ($value != 'none') {

          }
          break;

        /********************************************************
         *    PANELS STYLING
         *********************************************************/

        case (strpos($key, '_panels_styling') === 0 && !empty($value) && !empty($_architect_options[ 'architect_enable_styling' ])):

          $pkeys    = array();
          $pkey     = str_replace('_panels_styling_', '', $key);
          $pkey     = str_replace('-font-', '-', $pkey);
          $splitter = (substr_count($pkey, '-font-') === 1 ? strrpos($pkey, '-font-') : strrpos($pkey, '-'));

          $pkeys[ 'style' ] = str_replace('-', '', substr($pkey, $splitter + 1));
          $pkeys[ 'id' ]    = substr($pkey, 0, $splitter);

//          if (strpos($pkeys['id'],'content')) {
//            die(var_dump($pkeys, $value, $key));
//          }
          if (!in_array($pkeys['id'],array('custom','panels-load'))) {
            // Filter out old selector names hanging arouind in existing panels
            if (isset($_architect[ 'architect_config_' . $pkeys[ 'id' ] . '-selectors' ])) {

              $pkeys[ 'classes' ] = (is_array($_architect[ 'architect_config_' . $pkeys[ 'id' ] . '-selectors' ]) ? $_architect[ 'architect_config_' . $pkeys[ 'id' ] . '-selectors' ] : array('0' => $_architect[ 'architect_config_' . $pkeys[ 'id' ] . '-selectors' ]));
              $pzarc_contents .= pzarc_get_styling('panel', $pkeys, $value, $class_prefix, $pkeys[ 'classes' ]);
            }
          } elseif ($pkeys['id']==='custom'){
            $pzarc_contents .= $value;
          }
          break;
      }
    }


    // TODO: Why is using bgimages here??
    // TODO: Extend this for multiple breakpoints
    // Put this in an if since it's only when we are using bg images.
    // NOTE: It can affect wide screen content if you set the image smaller than the panel width
//    $pzarc_contents .= $class_prefix . '.using-bgimages .pzarc-components{max-width:' . $pzarc_panels[ '_panels_design_background-image-max' ][ 'width' ] . ';' . $pzarc_tb . ':' . $pzarc_components_nudge_y . '%;' . $pzarc_lr . ':' . $pzarc_components_nudge_x . '%;width:' . $pzarc_components_width . '%;}';

    if (!empty($toshow[ 'image' ][ 'show' ])) {
      if ('fill' === $pzarc_panels[ '_panels_design_feature-location' ]) {
        $pzarc_contents .= $class_prefix . '.using-bgimages .pzarc-components{' . $pzarc_tb . ':' . $pzarc_components_nudge_y . '%;' . $pzarc_lr . ':' . $pzarc_components_nudge_x . '%;width:' . $pzarc_components_width . '%;}';
      } else {
        $pzarc_contents .= $class_prefix . ' .pzarc-components{' . $pzarc_tb . ':' . $pzarc_components_nudge_y . '%;' . $pzarc_lr . ':' . $pzarc_components_nudge_x . '%;width:' . $pzarc_components_width . '%;}';
      }
    }
//    if ('align' === $pzarc_panels[ '_panels_design_background-position' ]) {
//
//
//      switch ($pzarc_components_position) {
//        case 'left':
//          $pzarc_contents .= $class_prefix . '.using-aligned-bgimages figure {left:' . ($pzarc_components_width + $pzarc_components_nudge_x) . '%;}';
//          $pzarc_contents .= $class_prefix . '.using-aligned-bgimages .pzarc-components {' . $pzarc_tb . ':' . $pzarc_components_nudge_y . '%;' . $pzarc_lr . ':' . $pzarc_components_nudge_x . '%;width:' . $pzarc_components_width . '%;}';
//          break;
//        case 'right':
//          $pzarc_contents .= $class_prefix . '.using-aligned-bgimages figure {right:' . ($pzarc_components_width + $pzarc_components_nudge_x) . '%;}';
//          $pzarc_contents .= $class_prefix . '.using-aligned-bgimages .pzarc-components {' . $pzarc_tb . ':' . $pzarc_components_nudge_y . '%;' . 'left:' . (100 - $pzarc_components_nudge_x - $pzarc_components_width) . '%;width:' . $pzarc_components_width . '%;}';
//          break;
//      }
//    }

//    $pzarc_contents .= $class_prefix . ' .pzarc-components{max-width:'.$pzarc_panels['_panels_design_background-image-max']['width'].';' . $pzarc_tb . ':' . $pzarc_components_nudge_y . '%;' . $pzarc_lr . ':' . $pzarc_components_nudge_x . '%;width:' . $pzarc_components_width . '%;}';

    return $pzarc_contents;
  }
