<?php
  /**
   * Created by PhpStorm.
   * User: chrishoward
   * Date: 6/05/2014
   * Time: 1:57 PM
   */

  //TODO: Dang it! It's doing that save twice problem again!!
//  add_action('save_post_arc-panels', 'save_arc_layouts', 99,3);
//  add_action('save_post_arc-blueprints', 'save_arc_layouts', 99,3);
  add_action('save_post', 'save_arc_layouts', 999, 3);
  function save_arc_layouts($postid, $post, $update)
  {
//    var_dump($postid,$post, $update);
    $screen = get_current_screen();
    /*
     * $screen:
     * Array
      (
          [action] =>
          [base] => post
          [WP_Screencolumns] => 0
          [id] => arc-panels
          [*in_admin] => site
          [is_network] =>
          [is_user] =>
          [parent_base] =>
          [parent_file] =>
          [post_type] => arc-panels
          [taxonomy] =>
          [WP_Screen_help_tabs] => Array()
          [WP_Screen_help_sidebar] =>
          [WP_Screen_options] => Array()
          [WP_Screen_show_screen_options] =>
          [WP_Screen_screen_settings] =>
      )
     */
    // save the CSS too
    // new wp_filesystem
    // create file named with id e.g. pzarc-cell-layout-123.css
    // Or should we connect this to the template? Potentially there'll be less panel layouts than templates tho
    if ($screen->id == 'arc-panels' || $screen->id == 'arc-blueprints') {

      $url = wp_nonce_url('post.php?post=' . $postid . '&action=edit', basename(__FILE__));
      if (false === ($creds = request_filesystem_credentials($url, '', false, false, null))) {
        return; // stop processing here
      }
      if (!WP_Filesystem($creds)) {
        request_filesystem_credentials($url, '', true, false, null);

        return;
      }

//    WP_Filesystem(true);
// get the upload directory and make a test.txt file
      $upload_dir = wp_upload_dir();
      $filename
                  = trailingslashit($upload_dir[ 'basedir' ]) . '/cache/pizazzwp/arc/pz' . $screen->id . '-layout-' . $postid . '.css';
      wp_mkdir_p(trailingslashit($upload_dir[ 'basedir' ]) . '/cache/pizazzwp/arc/');

      // Need to create the file contents

///pzdebug($filename);
      $pzarc_contents = pzarc_create_css($postid, $screen->id);

// by this point, the $wp_filesystem global should be working, so let's use it to create a file
      global $wp_filesystem;
      if (!$wp_filesystem->put_contents(
          $filename,
          $pzarc_contents,
          FS_CHMOD_FILE // predefined mode settings for WP files
      )
      ) {
        echo 'error saving file!';
      }
    }
  }

  /**
   * @function: pzarc_create_css
   * @param      $postid
   * @param null $type
   * @return string
   *
   * @purpose: It is necessary to create the css files as they are imported separately. Redux can't handle that.
   *
   */
  function pzarc_create_css($postid, $type = null)
  {

    global $pzarchitect;
    pzarc_set_defaults();
    $defaults = $pzarchitect[ 'defaults' ];
    // Need to create the file contents
    // For each field in stylings, create css
    $pzarc_contents = '';

    $nl = "\n";
    switch ($type) {
      case 'arc-blueprints':
        $pzarc_blueprints   = get_post_meta($postid, '_architect', true);
        $pzarc_blueprints   = pzarc_merge_defaults($defaults[ '_blueprints' ], $pzarc_blueprints);
        $pzarc_contents     = '/* This is the css for blueprint ' . $postid . ' ' . $pzarc_blueprints[ '_blueprints_short-name' ] . '*/' . $nl;
        $pzarc_contents_css = '';
        for ($i = 0; $i < 3; $i++) {
          $classes = '.pzarc-blueprint_' . $pzarc_blueprints[ '_blueprints_short-name' ] . ' .pzarc-section_' . ($i + 1) . ' .pzarc-panel';
          if (!empty($pzarc_blueprints[ '_blueprints_section-' . $i . '-panel-layout' ])) {

            $pzarc_contents .= '@import url("' . PZARC_CACHE_URL . '/pzarc-panels-layout-' . $pzarc_blueprints[ '_blueprints_section-' . $i . '-panel-layout' ] . '.css");' . $nl;
            $columns = intval($pzarc_blueprints[ '_blueprints_section-' . $i . '-columns' ]);
            $hmargin = $pzarc_blueprints[ '_blueprints_section-' . $i . '-panels-horiz-margin' ];

            //TODO: Use media queries and nth child trick too
            $pzarc_contents_css .= $classes . ' {width:' . (((100 - ($hmargin * ($columns - 1))) / $columns)) . '%;margin-right:' . ($hmargin) . '%;margin-bottom:' . $pzarc_blueprints[ '_blueprints_section-' . $i . '-panels-vert-margin' ] . '%;}' . $nl;
            $pzarc_contents_css .= $classes . ':nth-child(' . $columns . 'n) {margin-right: 0;}' . $nl;

          }
        }
        $pzarc_contents .= $pzarc_contents_css;
        $classes = '.pzarc-blueprint_' . $pzarc_blueprints[ '_blueprints_short-name' ];

        foreach ($pzarc_blueprints as $key => $value) {

          switch (true) {

            case ($key === '_blueprints_styling_blueprint-background'):
              $pzarc_contents .= '.pzarc-blueprint_' . $pzarc_blueprints[ '_blueprints_short-name' ] . ' {' . key($value) . ':' . $value[ key($value) ] . ';}' . $nl;
              break;

            case ($key === '_blueprints_styling_blueprint-padding' && !pzarc_is_empty_vals($value, array('units'))):
              $padding = pzarc_process_spacing($value);
              $pzarc_contents .= '.pzarc-blueprint_' . $pzarc_blueprints[ '_blueprints_short-name' ] . ' {' . $padding . ';}' . $nl;
              break;

            case ($key === '_blueprints_styling_blueprint-margins' && !pzarc_is_empty_vals($value, array('units'))):
              $margins = pzarc_process_spacing($value);
              $pzarc_contents .= '.pzarc-blueprint_' . $pzarc_blueprints[ '_blueprints_short-name' ] . ' {' . $margins . ';}' . $nl;
              break;

            case ($key === '_blueprints_styling_blueprint-borders' && ($value[ 'border-style' ] !== 'none')):
              $pzarc_contents .= pzarc_process_borders($classes, $value) . $nl;
              break;

            case ($key === '_blueprints_styling_blueprint-links'):
              $pzarc_contents .= pzarc_process_links($classes, $value) . $nl;
              break;
            case ($key === '_blueprints_styling_blueprint-links-dec'):
              $pzarc_contents .= pzarc_process_links_deco($classes, $value) . $nl;
              break;
            case ($key === '_blueprints_styling_blueprint-custom-css'):
              $custom_css = trim($value);
              $pzarc_contents .= (!empty($custom_css) ? $value . $nl : '');
              break;
          }
        }

        break;

      /**
       * PANELS CSS
       */
      case 'arc-panels':
        $pzarc_panels = get_post_meta($postid, '_architect', true);
        $pzarc_panels = pzarc_merge_defaults($defaults[ '_panels' ], $pzarc_panels);

        global $pzarchitect;

        $pzarc_contents = '/* This is the css for panel $postid ' . $pzarc_panels[ '_panels_settings_short-name' ] . '*/' . $nl;

        // step thru each field looking for ones to format
        $class_prefix = '.pzarchitect .pzarc-panel_' . $pzarc_panels[ '_panels_settings_short-name' ];

        foreach ($pzarc_panels as $key => $value) {

          switch (true) {

            case ($key == '_panels_design_responsive-hide-content' && !empty($pzarc_panels[ '_panels_design_responsive-hide-content' ])):
              $pzarc_contents .= '@media (max-width: ' . $pzarc_panels[ '_panels_design_responsive-hide-content' ] . 'px) { .pzarchitect .pzarc-' . $postid . ' entry-content, .pzarchitect .pzarc-' . $postid . ' .entry-excerpt {display:none;}}' . $nl;
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
                  $pzarc_contents .= $class_prefix . ' .entry-title {counter-increment: arc-title-counter;}' . $nl;
                  $pzarc_contents .= $class_prefix . ' .entry-title:before {content: counter(arc-title-counter, ' . $pzarc_panels[ '_panels_design_title-prefix' ] . ')"' . $pzarc_panels[ '_panels_design_title-bullet-separator' ] . '";}' . $nl;

                  break;
              }

              $pzarc_contents .= $class_prefix . ' .entry-title {width:' . $pzarc_layout[ 'title' ][ 'width' ] . '%;' . $titles_bullets . '}' . $nl;

              // Don't give thumbnail div a width if it's in the content
              if ($pzarc_panels[ '_pzarc_layout-excerpt-thumb' ] == 'none') {
//                $pzarc_contents .= $class_prefix . ' .entry-thumbnail {width:' . $pzarc_layout[ 'image' ][ 'width' ] . '%;max-width:' . ($pzarc_layout[ 'image' ][ 'width' ] - $pzarc_left_margin - $pzarc_right_margin) . '%;}' . "\n";
              } else {
                $margins = pzarc_process_spacing($pzarc_panels[ '_panels_design_image-spacing' ]);
                $pzarc_contents .= $class_prefix . ' .entry-thumbnail {width:' . $pzarc_layout[ 'image' ][ 'width' ] . '%;max-width:' . $pzarc_panels[ '_panels_design_image-max-width' ] . 'px;margin: ' . $margins . ';}' . $nl;
              }

              $pzarc_contents .= $class_prefix . ' .entry-content {width:' . $pzarc_layout[ 'content' ][ 'width' ] . '%;}' . $nl;
              $pzarc_contents .= $class_prefix . ' .entry-excerpt {width:' . $pzarc_layout[ 'excerpt' ][ 'width' ] . '%;}' . $nl;
              $pzarc_contents .= $class_prefix . ' .entry-meta1 {width:' . $pzarc_layout[ 'meta1' ][ 'width' ] . '%;}' . $nl;
              $pzarc_contents .= $class_prefix . ' .entry-meta2 {width:' . $pzarc_layout[ 'meta2' ][ 'width' ] . '%;}' . $nl;
              $pzarc_contents .= $class_prefix . ' .entry-meta3 {width:' . $pzarc_layout[ 'meta3' ][ 'width' ] . '%;}' . $nl;
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
                $pzarc_contents .= $class_prefix . ' .in-content-thumb {width:' . $twidth . '%;float:' . $value . ';' . $margins . '}' . $nl;

              }
              break;

            /**
             *    STYLING
             */
            case (strpos($key, '_panels_styling') === 0 && !empty($value)):

              switch ($key) {

                // Overall
                case '_panels_styling_panels-bg' :
                  $this_key = key($value);
                  $pzarc_contents .= $class_prefix . '.pzarc-panel {' . $this_key . ':' . $value[ $this_key ] . ';}' . $nl;
                  break;

                case '_panels_styling_panels-padding' :
                  $filler = '';
                  foreach ($value as $k => $v) {
                    $filler .= (strpos($k, 'padding') === 0 && !empty($v) ? $k . ':' . $v . ';' : '');
                  }
                  $pzarc_contents .= (!empty($filler) ? $class_prefix . '.pzarc-panel {' . $filler . '}' . $nl : '');
                  break;

                // Titles
                case '_panels_styling_entry-title-font' :
                  $pzarc_contents = pzarc_process_fonts($class_prefix . ' .entry-title', $value) . $nl;
                  break;

                case ($key === '_panels_styling_entry-title-font-links'):
                  $pzarc_contents .= pzarc_process_links($class_prefix . ' .entry-title', $value) . $nl;
                  break;

                case ($key === '_panels_styling_entry-title-font-links-dec'):
                  $pzarc_contents .= pzarc_process_links_deco($class_prefix . ' .entry-title', $value) . $nl;
                  break;


                // Meta
                // Images
                // Content


                //What is this lot?
                case '_panels_styling_hentry-margin' :
                case '_panels_styling_components-group-margin' :
                case '_panels_styling_panels-margin' :
                  $this_key = key($value);
                  // architect_config_hentry-selectors
                  $options_key = str_replace('_panels_styling_', 'architect_config_', $key);
                  $options_key = str_replace('-margin', '-selectors', $options_key);
                  $classes     = (is_array($pzarchitect[ $options_key ]) ? $class_prefix . ' ' . implode(', ' . $class_prefix . ' ', $pzarchitect[ $options_key ]) : $class_prefix . ' ' . $pzarchitect[ $options_key ]);
                  $margins     = pzarc_process_spacing($pzarc_panels[ $key ]);
                  $pzarc_contents .= (!empty($margins) ? $classes . ' {' . $margins . '}' . $nl : '');
                  //    var_dump($class_prefix,$classes . ' {' . $margins . '}');
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
        $pzarc_sections_postion = (!empty($pzarc_panels[ '_panels_design_components-position' ]) ? $pzarc_panels[ '_panels_design_components-position' ] : 'top');
        $pzarc_sections_nudge_x = (!empty($pzarc_panels[ '_panels_design_components-nudge-x' ]) ? $pzarc_panels[ '_panels_design_components-nudge-x' ] : 0);
        $pzarc_sections_nudge_y = (!empty($pzarc_panels[ '_panels_design_components-nudge-y' ]) ? $pzarc_panels[ '_panels_design_components-nudge-y' ] : 0);
        $pzarc_sections_width   = (!empty($pzarc_panels[ '_panels_design_components-widths' ]) ? $pzarc_panels[ '_panels_design_components-widths' ] : 100);
        $pzarc_tb               = ($pzarc_sections_postion == 'left' || $pzarc_sections_postion == 'right' ? 'top' : $pzarc_sections_postion);
        $pzarc_lr               = ($pzarc_sections_postion == 'top' || $pzarc_sections_postion == 'bottom' ? 'left' : $pzarc_sections_postion);

        $pzarc_contents .= $class_prefix . ' .abs-content {' . $pzarc_tb . ':' . $pzarc_sections_nudge_y . '%;' . $pzarc_lr . ':' . $pzarc_sections_nudge_x . '%;width:' . $pzarc_sections_width . '%;}';
    }


//    break;
//  }

//die();
    return $pzarc_contents;
  }

  /**
   * @param $classes
   * @param $properties
   * @return null|string
   */
  function pzarc_process_fonts($classes, $properties)
  {
    $filler = '';
    foreach ($properties as $k => $v) {
      // Need to only process specific properties
      switch ($k) {
        case (!empty($v) && $k == 'font-family'):
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
  function pzarc_process_spacing($properties)
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
  function pzarc_process_borders($classes, $properties)
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
  function pzarc_process_links($classes, $properties)
  {
    $links_css = '';

    $links_css .= (!empty($properties[ 'regular' ]) ? $classes . ' a {color:' . $properties[ 'regular' ] . '}' : '');
    $links_css .= (!empty($properties[ 'active' ]) ? $classes . ' a:active {color:' . $properties[ 'active' ] . '}' : '');
    $links_css .= (!empty($properties[ 'hover' ]) ? $classes . ' a:hover {color:' . $properties[ 'hover' ] . '}' : '');
    $links_css .= (!empty($properties[ 'visited' ]) ? $classes . ' a:visited {color:' . $properties[ 'visited' ] . '}' : '');

    return str_replace(';;',';',$links_css);
  }

  /**
   * @param $classes
   * @param $properties
   * @return string
   */
  function pzarc_process_links_deco($classes, $properties)
  {
    $links_css = '';
    // This doesn't deal with a "none" situation. Need custom css for that
    $links_css .= ($properties[ 0 ] == 'regular' ? $classes . ' a {text-decoration:underline}' : '');
    $links_css .= ($properties[ 0 ] == 'active' ? $classes . ' a:active {text-decoration:underline}' : '');
    $links_css .= ($properties[ 0 ] == 'hover' ? $classes . ' a:hover {text-decoration:underline}' : '');
    $links_css .= ($properties[ 0 ] == 'visited' ? $classes . ' a:visited {text-decoration:underline}' : '');

    return str_replace(';;',';',$links_css);
  }

  /**
   * @param $properties
   * @param $exclude
   * @return bool
   */
  function pzarc_is_empty_vals($properties, $exclude)
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
