<?php

  /**
   * Created by PhpStorm.
   * User: chrishoward
   * Date: 6/05/2014
   * Time: 1:57 PM
   */
  class arc_Save_Process
  {

    function __construct()
    {
      add_action('save_post', array(&$this, 'save_arc_layouts'), 999, 3);

    }

    function save_arc_layouts($postid, $post, $update)
    {
      $screen = get_current_screen();
      if ($screen->id == 'arc-panels' || $screen->id == 'arc-blueprints') {

        $url = wp_nonce_url('post.php?post=' . $postid . '&action=edit', basename(__FILE__));
        if (false === ($creds = request_filesystem_credentials($url, '', false, false, null))) {
          return; // stop processing here
        }
        if (!WP_Filesystem($creds)) {
          request_filesystem_credentials($url, '', true, false, null);

          return;
        }

        $upload_dir = wp_upload_dir();
        $filename
                    = trailingslashit($upload_dir[ 'basedir' ]) . '/cache/pizazzwp/arc/pz' . $screen->id . '-layout-' . $postid . '.css';
        wp_mkdir_p(trailingslashit($upload_dir[ 'basedir' ]) . '/cache/pizazzwp/arc/');

        $contents = create_css($postid, $screen->id);

        global $wp_filesystem;
        if (!$wp_filesystem->put_contents(
            $filename,
            $contents,
            FS_CHMOD_FILE // predefined mode settings for WP files
        )
        ) {
          echo 'error saving file!';
        }
      }
    }

    /**
     * @function: create_css
     * @param      $postid
     * @param null $type
     * @return string
     *
     * @purpose: It is necessary to create the css files as they are imported separately. Redux can't handle that.
     *
     */
    function create_css($postid, $type = null)
    {

      global $pzarchitect;
      global $_architect_options;
      // var_dump($_architect_options);
      set_defaults();
      $defaults = $pzarchitect[ 'defaults' ];
      // Need to create the file contents
      // For each field in stylings, create css
      $contents = '';

      $nl = "\n";
      switch ($type) {
        case 'arc-blueprints':
          $blueprints = get_post_meta($postid, '_architect', true);
          $blueprints = merge_defaults($defaults[ '_blueprints' ], $blueprints);
          $contents .= '/* This is the css for blueprint ' . $postid . ' ' . $blueprints[ '_blueprints_short-name' ] . '*/' . $nl;
          $contents_css = '';

          // Process the setions
          for ($i = 0; $i < 3; $i++) {
            $classes = '.pzarchitect .pzarc-blueprint_' . $blueprints[ '_blueprints_short-name' ] . ' .pzarc-section_' . ($i + 1) . ' .pzarc-panel';

            if (!empty($blueprints[ '_blueprints_section-' . $i . '-panel-layout' ])) {

              $contents .= '@import url("' . CACHE_URL . '/pzarc-panels-layout-' . $blueprints[ '_blueprints_section-' . $i . '-panel-layout' ] . '.css");' . $nl;
              $hmargin = $blueprints[ '_blueprints_section-' . $i . '-panels-horiz-margin' ][ 'height' ];

              // Need to do this for each breakpoint
              $columns = intval($blueprints[ '_blueprints_section-' . $i . '-columns-breakpoint-1' ]);
              $contents_css .= '@media (max-width:99999px) {';
              $contents_css .= $classes . ' {width:' . (((100 - ($hmargin * ($columns - 1))) / $columns)) . '%;margin-right:' . ($hmargin) . '%;margin-bottom:' . $blueprints[ '_blueprints_section-' . $i . '-panels-vert-margin' ][ 'width' ] . '%;}';
              $contents_css .= $classes . ':nth-child(' . $columns . 'n) {margin-right: 0;}';
//            $contents_css .= $classes . ' {width:' . (((100 - ($hmargin * ($columns - 1))) / $columns)) . '%;margin-bottom:' . $blueprints[ '_blueprints_section-' . $i . '-panels-vert-margin' ][ 'width' ] . '%;}';
              $contents_css .= $classes . ' .grid-sizer { width:' . (100 / $columns) . '%;}';
              $contents_css .= '}' . $nl;

              for ($bp = 1; $bp <= 3; $bp++) {
                $columns = intval($blueprints[ '_blueprints_section-' . $i . '-columns-breakpoint-' . $bp ]);
                $contents_css .= '@media (max-width:' . $_architect_options[ 'architect_breakpoint_' . $bp ][ 'width' ] . ') {';
                $contents_css .= $classes . ' {width:' . (((100 - ($hmargin * ($columns - 1))) / $columns)) . '%;margin-right:' . ($hmargin) . '%;margin-bottom:' . $blueprints[ '_blueprints_section-' . $i . '-panels-vert-margin' ][ 'width' ] . '%;}';
                $contents_css .= $classes . ':nth-child(' . $columns . 'n) {margin-right: 0;}';
//              $contents_css .= $classes . ' {width:' . (((100 - ($hmargin * ($columns - 1))) / $columns)) . '%;margin-bottom:' . $blueprints[ '_blueprints_section-' . $i . '-panels-vert-margin' ][ 'width' ] . '%;}';
                $contents_css .= $classes . ' .grid-sizer { width:' . (100 / $columns) . '%;}';
                $contents_css .= '}' . $nl;
              }

            }

          }
          $contents .= $contents_css;
          $sectionsclasses = '.pzarchitect .pzarc-sections_' . $blueprints[ '_blueprints_short-name' ];
          $bpclasses       = '.pzarchitect.pzarc-blueprint_' . $blueprints[ '_blueprints_short-name' ];

          //   var_dump($blueprints);
          foreach ($blueprints as $key => $value) {

//          var_dump($key,$value);
            switch (true) {

              // Blueprints styling
              case ($key === '_blueprints_styling_blueprint-background'):
                if (!empty($value[ 'color' ])) {
                  $contents .= $bpclasses . ' {background-color:' . $value[ 'color' ] . ';}' . $nl;
                }
//              $contents .= $classes . ' {' . key($value) . ':' . $value[ key($value) ] . ';}' . $nl;
                break;

              case ($key === '_blueprints_styling_blueprint-padding' && !is_empty_vals($value, array('units'))):
                $padding = process_spacing($value);
                $contents .= $bpclasses . ' {' . $padding . ';}' . $nl;
                break;

              case ($key === '_blueprints_styling_blueprint-margins' && !is_empty_vals($value, array('units'))):
                $margins = process_spacing($value);
                $contents .= $bpclasses . ' {' . $margins . ';}' . $nl;
                break;

              case ($key === '_blueprints_styling_blueprint-borders' && ($value[ 'border-style' ] !== 'none')):
                $contents .= process_borders($bpclasses, $value) . $nl;
                break;

              case ($key === '_blueprints_styling_sections-borders' && ($value[ 'border-style' ] !== 'none')):
                $contents .= process_borders($sectionsclasses, $value) . $nl;
                break;

              case ($key === '_blueprints_styling_container-links'):
                $contents .= process_links($sectionsclasses, $value, $nl) . $nl;
                break;

              case ($key === '_blueprints_styling_container-custom-css'):
                $custom_css = trim($value);
                $contents .= (!empty($custom_css) ? $value . $nl : '');
                break;


              // Sections wrapper styling
              case ($key === '_blueprints_styling_sections-background'):
                if (!empty($value[ 'color' ])) {
                  $contents .= $sectionsclasses . ' {background-color:' . $value[ 'color' ] . ';}' . $nl;
                }
//              $contents .= $classes . ' {' . key($value) . ':' . $value[ key($value) ] . ';}' . $nl;
                break;

              case ($key === '_blueprints_styling_sections-padding' && !is_empty_vals($value, array('units'))):
                $padding = process_spacing($value);
                $contents .= $sectionsclasses . ' {' . $padding . ';}' . $nl;
                break;

              case ($key === '_blueprints_styling_sections-margins' && !is_empty_vals($value, array('units'))):
                $margins = process_spacing($value);
                $contents .= $sectionsclasses . ' {' . $margins . ';}' . $nl;
                break;


              default :
                //             var_Dump($key);
                break;
            }
          }

          break; // case blueprints

        /**
         * PANELS CSS
         */
        case 'arc-panels':
          $panels = get_post_meta($postid, '_architect', true);
          $panels = merge_defaults($defaults[ '_panels' ], $panels);

          global $pzarchitect;

          $contents .= '/* This is the css for panel $postid ' . $panels[ '_panels_settings_short-name' ] . '*/' . $nl;

          // step thru each field looking for ones to format
          $class_prefix = '.pzarchitect .pzarc-panel_' . $panels[ '_panels_settings_short-name' ];
//var_dump($panels);
          foreach ($panels as $key => $value) {
            switch (true) {

              case ($key == '_panels_settings_panel-height'):
                if ($panels[ '_panels_settings_panel-height-type' ] === 'fixed') {
                  $contents .= $class_prefix . ' {height:' . $value[ 'height' ] . ';}' . $nl;
                }
                break;

              case ($key == '_panels_design_responsive-hide-content' && $panels[ '_panels_design_responsive-hide-content' ] !== 'none'):
                $contents .= '@media (max-width: ' . $_architect_options[ 'architect_breakpoint_' . $panels[ '_panels_design_responsive-hide-content' ] ][ 'width' ] . ') { ' . $class_prefix . ' .entry-content, ' . $class_prefix . ' .entry-excerpt {display:none!important;}}' . $nl;
                break;


              case ($key == '_panels_design_preview'):
                $left_margin  = (!empty($panels[ '_panels_design_image-margin-left' ]) ? $panels[ '_panels_design_image-margin-left' ] : 0);
                $right_margin = (!empty($panels[ '_panels_design_image-margin-right' ]) ? $panels[ '_panels_design_image-margin-right' ] : 0);
                $layout       = json_decode($value, true);

                switch ($panels[ '_panels_design_title-prefix' ]) {

                  case 'none':
                  case 'thumb':
                    $titles_bullets = '';
                    break;

                  case 'disc':
                  case 'circle':
                  case 'square':
                    $titles_bullets = 'list-style:' . $panels[ '_panels_design_title-prefix' ] . ' outside none;display:list-item;';
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
                    $contents .= $class_prefix . ' .entry-title {counter-increment: arc-title-counter;}' . $nl;
                    $contents .= $class_prefix . ' .entry-title:before {content: counter(arc-title-counter, ' . $panels[ '_panels_design_title-prefix' ] . ')"' . $panels[ '_panels_design_title-bullet-separator' ] . '";}' . $nl;

                    break;
                }

                $contents .= $class_prefix . ' .entry-title {width:' . $layout[ 'title' ][ 'width' ] . '%;' . $titles_bullets . '}' . $nl;


                // Don't give thumbnail div a width if it's in the content
                //            var_dump($panels);
//              if ($panels[ '_layout-excerpt-thumb' ] == 'none') {
////                $contents .= $class_prefix . ' .entry-thumbnail {width:' . $layout[ 'image' ][ 'width' ] . '%;max-width:' . ($layout[ 'image' ][ 'width' ] - $left_margin - $right_margin) . '%;}' . "\n";
//              } else {
                $margins = process_spacing($panels[ '_panels_design_image-spacing' ]);
                $left    = $panels[ '_panels_design_image-spacing' ][ 'margin-left' ];
                $left    = preg_replace("/([\\%px])/uiUm", "", $left);
                $right   = $panels[ '_panels_design_image-spacing' ][ 'margin-right' ];
                $right   = preg_replace("/([\\%px])/uiUm", "", $right);
                $contents .= $class_prefix . ' .entry-thumbnail {width:' . ($layout[ 'image' ][ 'width' ] - $left - $right) . '%;max-width:' . $panels[ '_panels_design_image-max-width' ] . 'px;margin: ' . $margins . ';}' . $nl;
//              }

                $contents .= $class_prefix . ' .entry-content {width:' . $layout[ 'content' ][ 'width' ] . '%;}' . $nl;
                $contents .= $class_prefix . ' .entry-excerpt {width:' . $layout[ 'excerpt' ][ 'width' ] . '%;}' . $nl;
                $contents .= $class_prefix . ' .entry-meta1 {width:' . $layout[ 'meta1' ][ 'width' ] . '%;}' . $nl;
                $contents .= $class_prefix . ' .entry-meta2 {width:' . $layout[ 'meta2' ][ 'width' ] . '%;}' . $nl;
                $contents .= $class_prefix . ' .entry-meta3 {width:' . $layout[ 'meta3' ][ 'width' ] . '%;}' . $nl;
                break;

//            case ($key == '_panels_design_image-margin-left' && ($value === 0 || $value > 0)):
//              $contents .= $class_prefix . ' .entry-thumbnail {margin-left: ' . $value . '%;}' . "\n";
//              break;
//
//            case ($key == '_panels_design_image-margin-right' && ($value === 0 || $value > 0)):
//              $contents .= $class_prefix . ' .entry-thumbnail {margin-right: ' . $value . '%;}' . "\n";
//              break;
//
//            case ($key == '_panels_design_image-margin-top' && ($value === 0 || $value > 0)):
//              $contents .= $class_prefix . ' .entry-thumbnail {margin-top: ' . $value . '%;}' . "\n";
//              break;
//
//            case ($key == '_panels_design_image-margin-bottom' && ($value === 0 || $value > 0)):
//              $contents .= $class_prefix . ' .entry-thumbnail {margin-bottom: ' . $value . '%;}' . "\n";
//              break;

              case ($key == '_panels_design_thumb-position'):

                if ($value != 'none') {

                  $margins = process_spacing($panels[ '_panels_design_image-spacing' ]);
                  $twidth  = $panels[ '_panels_design_thumb-width' ] - ($panels[ '_panels_design_image-margin-left' ] + $panels[ '_panels_design_image-margin-right' ]);
                  $contents .= $class_prefix . ' .in-content-thumb {width:' . $twidth . '%;float:' . $value . '!important;' . $margins . '}' . $nl;

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

              /**
               *    STYLING
               */
              case (strpos($key, '_panels_styling') === 0 && !empty($value)):
                //           var_dump($key,$value);
                switch ($key) {

                  // Overall
                  // PANELS
                  case '_panels_styling_panels-background' :
                    $this_key = key($value);
                    if (!empty($value[ 'color' ])) {
                      $contents .= $class_prefix . '.pzarc-panel {background-color:' . $value[ 'color' ] . ';}' . $nl;
                    }
                    break;

                  case '_panels_styling_panels-padding' :
                    $filler = '';
                    foreach ($value as $k => $v) {
                      $filler .= (strpos($k, 'padding') === 0 && !empty($v) ? $k . ':' . $v . ';' : '');
                    }
                    $contents .= (!empty($filler) ? $class_prefix . '.pzarc-panel {' . $filler . '}' . $nl : '');
                    break;

                  case ('_panels_styling_panels-borders'):
                    if (($value[ 'border-style' ] !== 'none')) {
                      $contents .= process_borders($class_prefix . '.pzarc-panel', $value) . $nl;
                    }
                    break;

                  // COMPONENTS
                  case '_panels_styling_components-background' :
                    $this_key = key($value);
                    if (!empty($value[ 'color' ])) {
                      $contents .= $class_prefix . ' .pzarc-components {background-color:' . $value[ 'color' ] . ';}' . $nl;
                    }
                    break;

                  case '_panels_styling_components-padding' :
                    $padding = process_spacing($value);
                    if (!empty($padding)) {
                      $contents .= $class_prefix . ' .pzarc-components {' . $padding . '}' . $nl;
                    }
                    break;

                  case ('_panels_styling_components-borders'):
                    if (($value[ 'border-style' ] !== 'none')) {
                      $contents .= process_borders($class_prefix . ' .pzarc-components', $value) . $nl;
                    }
                    break;

                  // Titles
                  case '_panels_styling_entry-title-font' :
                    $contents .= process_fonts($class_prefix . ' .entry-title', $value) . $nl;
                    break;

                  case ($key === '_panels_styling_entry-title-font-links'):
                    $contents .= process_links($class_prefix . ' .entry-title ', $value, $nl) . $nl;
                    break;


                  // Meta
                  case '_panels_styling_entry-meta-font' :
                    $contents .= process_fonts($class_prefix . ' .entry-meta', $value) . $nl;
                    break;

                  case ($key === '_panels_styling_entry-meta-font-links'):
//                  var_dump($key,$value);
                    $contents .= process_links($class_prefix . ' .entry-meta ', $value, $nl) . $nl;
                    break;

                  //
                  // IMAGES
                  //

                  case '_panels_styling_entry-image-background' :
                    $contents .= process_background($class_prefix . ' figure.entry-thumbnail ', $value) . $nl;
                    break;

                  case '_panels_styling_entry-image-padding' :
                    //              var_dump($key);
                    $padding = process_spacing($value);
                    $contents .= $class_prefix . ' figure.entry-thumbnail  {' . $padding . ';}' . $nl;
                    break;

                  case ('_panels_styling_entry-image-borders'):
                    if (($value[ 'border-style' ] !== 'none')) {
                      $contents .= process_borders($class_prefix . ' figure.entry-thumbnail', $value) . $nl;
                    }
                    break;

                  // Captions
                  case '_panels_styling_entry-content-font' :
                    //              var_dump($key);
                    $contents .= process_fonts($class_prefix . ' .entry-content', $value) . $nl;
                    $contents .= process_fonts($class_prefix . ' .entry-excerpt', $value) . $nl;
                    break;

                  // Content
                  //entry-image-caption-font', array('figure.entry-thumbnail span.caption'
                  case '_panels_styling_entry-image-caption-font' :
                    //              var_dump($key);
                    $contents .= process_fonts($class_prefix . ' figure.entry-thumbnail span.caption ', $value) . $nl;
                    break;

                  case '_panels_styling_entry-image-caption-font-background' :
                    $contents .= process_background($class_prefix . ' figure.entry-thumbnail span.caption ', $value) . $nl;
//                  $contents .= process_bg($class_prefix . ' figure.entry-thumbnail span.caption {', $value) . $nl;
                    break;


                  case '_panels_styling_entry-image-caption-font-padding' :
                    //              var_dump($key);
                    $padding = process_spacing($value);
                    $contents .= $class_prefix . ' figure.entry-thumbnail span.caption {' . $padding . ';}' . $nl;
                    break;

                  case ($key === '_panels_styling_entry-content-font-links'):
                    $contents .= process_links($class_prefix . ' .entry-content ', $value, $nl) . $nl;
                    $contents .= process_links($class_prefix . ' .entry-excerpt ', $value, $nl) . $nl;
                    break;


                  //What is this lot?
                  case '_panels_styling_hentry-margin' :
                  case '_panels_styling_components-group-margin' :
                  case '_panels_styling_panels-margin' :
                    $this_key = key($value);
                    // architect_config_hentry-selectors
                    $options_key = str_replace('_panels_styling_', 'architect_config_', $key);
                    $options_key = str_replace('-margin', '-selectors', $options_key);
                    $classes     = (is_array($pzarchitect[ $options_key ]) ? $class_prefix . ' ' . implode(', ' . $class_prefix . ' ', $pzarchitect[ $options_key ]) : $class_prefix . ' ' . $pzarchitect[ $options_key ]);
                    $margins     = process_spacing($panels[ $key ]);
                    $contents .= (!empty($margins) ? $classes . ' {' . $margins . '}' . $nl : '');
                    //    var_dump($class_prefix,$classes . ' {' . $margins . '}');
                    break;
                  default :
                    //             var_Dump($key);
                    break;

                }
                break;
            }
//              $fieldname = str_replace('_layout-format-', '', $key);
////            var_dump($fieldname,$key,$prefix.$fieldname.'-class',$defaults[ $prefix.$fieldname.'-class'],$value);
//              $classes = $class_prefix . ' ' . str_replace(',', ', .pzarchitect .pzarc-' . $postid . ' ', $defaults[ $prefix . $fieldname . '-class' ]);
//              $contents .= $classes . ' {' . $value . '}' . "\n";
            //         break;
          }


          //     var_dump($key,strpos($key, '-format-') , !empty($value) , !empty($panels[ $key . '-classes' ]));
          $sections_postion = (!empty($panels[ '_panels_design_components-position' ]) ? $panels[ '_panels_design_components-position' ] : 'top');
          $sections_nudge_x = (!empty($panels[ '_panels_design_components-nudge-x' ]) ? $panels[ '_panels_design_components-nudge-x' ] : 0);
          $sections_nudge_y = (!empty($panels[ '_panels_design_components-nudge-y' ]) ? $panels[ '_panels_design_components-nudge-y' ] : 0);
          $sections_width   = (!empty($panels[ '_panels_design_components-widths' ]) ? $panels[ '_panels_design_components-widths' ] : 100);
          $tb               = ($sections_postion == 'left' || $sections_postion == 'right' ? 'top' : $sections_postion);
          $lr               = ($sections_postion == 'top' || $sections_postion == 'bottom' ? 'left' : $sections_postion);

          $contents .= $class_prefix . ' .has-bgimage {' . $tb . ':' . $sections_nudge_y . '%;' . $lr . ':' . $sections_nudge_x . '%;width:' . $sections_width . '%;}';
      }


//    break;
//  }

//die();
      return $contents;
    }

    /**
     * @param $classes
     * @param $properties
     * @return null|string
     */
    function process_fonts($classes, $properties)
    {
      $filler = '';
      foreach ($properties as $k => $v) {
        // Need to only process specific properties
        // This is to add quoties around fonts that don't have them
        switch ($k) {
          case (!empty($v) && $k == 'font-family'):
            $ff    = explode(', ', $v);
            $fonts = '';
            foreach ($ff as $key => $font) {
              if (strpos($font, ' ') > 0 && strpos($font, '\'') === false) {
                $fonts .= '"' . $font . '"';
              } else {
                $fonts .= $font;
              }

              if ($key != count($ff) - 1) {
                $fonts .= ', ';
              }
            }
            $filler .= $k . ':' . $fonts . ';';
            break;
          case (!empty($v) && $k == 'font-style'):
          case (!empty($v) && $v !== 'px' && $k == 'font-size'):
          case (!empty($v) && $k == 'font-variant'):
          case (!empty($v) && $k == 'text-align'):
          case (!empty($v) && $k == 'text-transform'):
          case (!empty($v) && $k == 'text-decoration'):
          case (!empty($v) && $v !== 'px' && $k == 'line-height'):
          case (!empty($v) && $v !== 'px' && $k == 'word-spacing'):
          case (!empty($v) && $v !== 'px' && $k == 'letter-spacing'):
          case (!empty($v) && $k == 'color'):
            $filler .= $k . ':' . $v . ';';
            break;

        }
      }

      return (!empty($filler) ? $classes . '{' . $filler . '}' : null);
    }

    /**
     * @param $properties
     * @return string
     */
    function process_spacing($properties)
    {
      $spacing_css = '';
//  $units = (!isset($property['units'])?'%':$property['units']);
      //   var_dump($property);
      foreach ($properties as $key => $value) {
        if ($key != 'units') {
          $iszero   = ($value === 0 || $value === '0');
          $isnotset = $value === '';
          $propval  = $key . ':' . $value;
          $propzero = $key . ':0;';
//      if (!isset($property['units'])) {
//        $spacing_css .= ($iszero ? $propzero :($isnotset ?null: $propval .$units.';'));
//      } else {
          $spacing_css .= ($iszero ? $propzero : ($isnotset ? null : $propval . ';'));
//      }
        }
      }

      return $spacing_css;
    }

    /**
     * @param $classes
     * @param $properties
     * @return string
     */
    function process_borders($classes, $properties)
    {
      $borders_css = '';

      $borders_css .= ($properties[ 'border-top' ] !== '' ? 'border-top:' . $properties[ 'border-top' ] : '');
      $borders_css .= ($properties[ 'border-top' ] !== '' && $properties[ 'border-top' ] !== '0' ? ' ' . $properties[ 'border-style' ] . ' ' . $properties[ 'border-color' ] . ';' : ';');
      $borders_css .= ($properties[ 'border-right' ] !== '' ? 'border-right:' . $properties[ 'border-right' ] : '');
      $borders_css .= ($properties[ 'border-right' ] !== '' && $properties[ 'border-right' ] !== '0' ? ' ' . $properties[ 'border-style' ] . ' ' . $properties[ 'border-color' ] . ';' : ';');
      $borders_css .= ($properties[ 'border-bottom' ] !== '' ? 'border-bottom:' . $properties[ 'border-bottom' ] : '');
      $borders_css .= ($properties[ 'border-bottom' ] !== '' && $properties[ 'border-bottom' ] !== '0' ? ' ' . $properties[ 'border-style' ] . ' ' . $properties[ 'border-color' ] . ';' : ';');
      $borders_css .= ($properties[ 'border-left' ] !== '' ? 'border-left:' . $properties[ 'border-left' ] : '');
      $borders_css .= ($properties[ 'border-left' ] !== '' && $properties[ 'border-left' ] !== '0' ? ' ' . $properties[ 'border-style' ] . ' ' . $properties[ 'border-color' ] . ';' : ';');

      return $classes . '{' . $borders_css . '}';
    }

    /**
     * @param $classes
     * @param $properties
     * @return string
     */
    function process_links($classes, $properties, $nl)
    {
      $links_css = '';
      if (!empty($properties[ 'regular' ]) || strtolower($properties[ 'regular-deco' ]) !== 'default') {
        $links_css .= $classes . ' a {';
        $links_css .= (!empty($properties[ 'regular' ]) ? 'color:' . $properties[ 'regular' ] . ';' : '');
        $links_css .= (strtolower($properties[ 'regular-deco' ]) !== 'default' ? 'text-decoration:' . strtolower($properties[ 'regular-deco' ]) . ';' : '');
        $links_css .= '}' . $nl;
      }

      if (!empty($properties[ 'hover' ]) || strtolower($properties[ 'hover-deco' ]) !== 'default') {
        $links_css .= $classes . ' a:hover {';
        $links_css .= (!empty($properties[ 'hover' ]) ? 'color:' . $properties[ 'hover' ] . ';' : '');
        $links_css .= (strtolower($properties[ 'hover-deco' ]) !== 'default' ? 'text-decoration:' . strtolower($properties[ 'hover-deco' ]) . ';' : '');
        $links_css .= '}' . $nl;
      }

      if (!empty($properties[ 'active' ]) || strtolower($properties[ 'active-deco' ]) !== 'default') {
        $links_css .= $classes . ' a:active {';
        $links_css .= (!empty($properties[ 'active' ]) ? 'color:' . $properties[ 'active' ] . ';' : '');
        $links_css .= (strtolower($properties[ 'active-deco' ]) !== 'default' ? 'text-decoration:' . strtolower($properties[ 'active-deco' ]) . ';' : '');
        $links_css .= '}' . $nl;
      }

      if (!empty($properties[ 'visited' ]) || strtolower($properties[ 'visited-deco' ]) !== 'default') {
        $links_css .= $classes . ' a:visited {';
        $links_css .= (!empty($properties[ 'visited' ]) ? 'color:' . $properties[ 'visited' ] . ';' : '');
        $links_css .= (strtolower($properties[ 'visited-deco' ]) !== 'default' ? 'text-decoration:' . strtolower($properties[ 'visited-deco' ]) . ';' : '');
        $links_css .= '}' . $nl;
      }

      return $links_css;
    }

    function process_background($classes, $properties)
    {
      $bg_css = '';
      // Could come from one of two methods
      if (!empty($properties[ 'color' ])) {
        $bg_css .= $classes . ' {background-color:' . $properties[ 'color' ] . ';}';
      }
      if (!empty($properties[ 'background-color' ])) {
        $bg_css .= $classes . ' {background-color:' . $properties[ 'background-color' ] . ';}';
      }

      return $bg_css;
    }

    /**
     * @param $properties
     * @param $exclude
     * @return bool
     */
    function is_empty_vals($properties, $exclude)
    {
      $is_empty = true;
      foreach ($properties as $key => $value) {
//    var_dump(!in_array($key,$exclude) , !empty($value));
        if (!in_array($key, $exclude) && !empty($value)) {
          $is_empty = false;
          break;
        }
      }

      return $is_empty;
    }
  }