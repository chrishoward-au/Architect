<?php
  /**
   * Created by PhpStorm.
   * User: chrishoward
   * Date: 6/05/2014
   * Time: 1:57 PM
   */
//add_action('pre_post_update','testx');
  function testx($post)
  {
    var_dump($post);
  }

  //TODO: Dang it! It's doing that save twice problem again when i use this!!
//  add_action('save_post_arc-panels', 'save_arc_layouts', 99,3);
//  add_action('save_post_arc-blueprints', 'save_arc_layouts', 99,3);
  add_action('save_post', 'save_arc_layouts', 999, 3);
  function save_arc_layouts($postid, $post, $update)
  {
//   var_dump($postid,$post, $update);
    // keep an eye on this to be sure it doesn't prevent some saves
    if (!$update) {
      return;
    }
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
    if ($screen->id == 'arc-panels' || $screen->id == 'arc-blueprints' || $post->post_type === 'arc-panels' || $post->post_type === 'arc-blueprints') {


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
      $pzarc_settings = get_post_meta($postid, '_architect', true);
//      var_dump($pzarc_settings,$post->post_type,$pzarc_settings['_blueprints_short-name']);

      $pzarc_shortname = ($post->post_type === 'arc-panels' ? $pzarc_settings[ '_panels_settings_short-name' ] : $pzarc_settings[ '_blueprints_short-name' ]);
      $upload_dir      = wp_upload_dir();
      $filename
                       = trailingslashit($upload_dir[ 'basedir' ]) . '/cache/pizazzwp/arc/pz' . $post->post_type . '-layout-' . $postid . '-' . $pzarc_shortname . '.css';
      wp_mkdir_p(trailingslashit($upload_dir[ 'basedir' ]) . '/cache/pizazzwp/arc/');

      // Need to create the file contents

///pzdebug($filename);
      $pzarc_contents = pzarc_create_css($postid, $post->post_type, $pzarc_settings);


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
  function pzarc_create_css($postid, $type = null, $pzarc_settings)
  {

    global $pzarchitect;
    global $_architect_options;
    // var_dump($_architect_options);
    pzarc_set_defaults();
    $defaults = $pzarchitect[ 'defaults' ];
    // Need to create the file contents
    // For each field in stylings, create css
    $pzarc_contents = '';

    $nl = "\n";
    switch ($type) {
      case 'arc-blueprints':
        $pzarc_blueprints = pzarc_merge_defaults($defaults[ '_blueprints' ], $pzarc_settings);

        $pzarc_contents .= '/* This is the css for blueprint ' . $postid . ' ' . $pzarc_blueprints[ '_blueprints_short-name' ] . '*/' . $nl;
        $pzarc_bp_css = array();

        $keyss = array_keys($pzarc_blueprints);
        foreach ($keyss as $k => $v) {
          $keyz[ $k ] = explode('_', $v);
          if (empty($keyz[ $k ][ 0 ])) {
            unset($keyz[ $k ][ 0 ]);
          }
        }
        //    var_dump($keyz);

        // Process the setions
        for ($i = 0; $i < 3; $i++) {
          $pzarc_bp_css[ $i ] = pzarc_process_bp_sections($pzarc_blueprints, $i, $nl, $_architect_options);

        }
        $pzarc_contents .= $pzarc_bp_css[ 0 ][ 0 ] . $pzarc_bp_css[ 1 ][ 0 ] . $pzarc_bp_css[ 2 ][ 0 ] . $pzarc_bp_css[ 0 ][ 1 ] . $pzarc_bp_css[ 1 ][ 1 ] . $pzarc_bp_css[ 2 ][ 1 ];
        $sectionsclasses = '.pzarchitect .pzarc-sections_' . $pzarc_blueprints[ '_blueprints_short-name' ];
        $bpclasses       = '.pzarchitect.pzarc-blueprint_' . $pzarc_blueprints[ '_blueprints_short-name' ];

        //   var_dump($pzarc_blueprints);
        foreach ($pzarc_blueprints as $key => $value) {

//          var_dump($key,$value);
          switch (true) {

            // Blueprints styling
            case ($key === '_blueprints_styling_blueprint-background'):
              if (!empty($value[ 'color' ])) {
                $pzarc_contents .= $bpclasses . ' {background-color:' . $value[ 'color' ] . ';}' . $nl;
              }
//              $pzarc_contents .= $classes . ' {' . key($value) . ':' . $value[ key($value) ] . ';}' . $nl;
              break;

            case ($key === '_blueprints_styling_blueprint-padding' && !pzarc_is_empty_vals($value, array('units'))):
              $padding = pzarc_process_spacing($value);
              $pzarc_contents .= $bpclasses . ' {' . $padding . ';}' . $nl;
              break;

            case ($key === '_blueprints_styling_blueprint-margins' && !pzarc_is_empty_vals($value, array('units'))):
              $margins = pzarc_process_spacing($value);
              $pzarc_contents .= $bpclasses . ' {' . $margins . ';}' . $nl;
              break;

            case ($key === '_blueprints_styling_blueprint-borders' && ($value[ 'border-style' ] !== 'none')):
              $pzarc_contents .= pzarc_process_borders($bpclasses, $value) . $nl;
              break;

            case ($key === '_blueprints_styling_sections-borders' && ($value[ 'border-style' ] !== 'none')):
              $pzarc_contents .= pzarc_process_borders($sectionsclasses, $value) . $nl;
              break;

            case ($key === '_blueprints_styling_container-links'):
              $pzarc_contents .= pzarc_process_links($sectionsclasses, $value, $nl) . $nl;
              break;


            case ($key === '_blueprints_styling_blueprint-custom-css'):
              $custom_css = trim($value);
              $pzarc_contents .= (!empty($custom_css) ? $value . $nl : '');
              break;


            // Sections wrapper styling
            case ($key === '_blueprints_styling_sections-background'):
              if (!empty($value[ 'color' ])) {
                $pzarc_contents .= $sectionsclasses . ' {background-color:' . $value[ 'color' ] . ';}' . $nl;
              }
//              $pzarc_contents .= $classes . ' {' . key($value) . ':' . $value[ key($value) ] . ';}' . $nl;
              break;

            case ($key === '_blueprints_styling_sections-padding' && !pzarc_is_empty_vals($value, array('units'))):
              $padding = pzarc_process_spacing($value);
              $pzarc_contents .= $sectionsclasses . ' {' . $padding . ';}' . $nl;
              break;

            case ($key === '_blueprints_styling_sections-margins' && !pzarc_is_empty_vals($value, array('units'))):
              $margins = pzarc_process_spacing($value);
              $pzarc_contents .= $sectionsclasses . ' {' . $margins . ';}' . $nl;
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
        $pzarc_panels = pzarc_merge_defaults($defaults[ '_panels' ], $pzarc_settings);


        $pzarc_contents .= '/* This is the css for panel $postid ' . $pzarc_panels[ '_panels_settings_short-name' ] . '*/' . $nl;

        // step thru each field looking for ones to format
        $class_prefix = '.pzarchitect .pzarc-panel_' . $pzarc_panels[ '_panels_settings_short-name' ];
//var_dump($pzarc_panels);
        foreach ($pzarc_panels as $key => $value) {
          switch (true) {

//            case ($key == '_panels_settings_panel-height'):
//              if ($pzarc_panels[ '_panels_settings_panel-height-type' ] === 'fixed') {
//                $pzarc_contents .= $class_prefix . ' {height:' . $value[ 'height' ] . ';}' . $nl;
//              }
//              break;

            case ($key == '_panels_design_responsive-hide-content' && $pzarc_panels[ '_panels_design_responsive-hide-content' ] !== 'none'):
              $pzarc_contents .= '@media (max-width: ' . $_architect_options[ 'architect_breakpoint_' . $pzarc_panels[ '_panels_design_responsive-hide-content' ] ][ 'width' ] . ') { ' . $class_prefix . ' .entry-content, ' . $class_prefix . ' .entry-excerpt {display:none!important;}}' . $nl;
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
                    $pzarc_contents .= $class_prefix . '.pzarc-panel {background-color:' . $value[ 'color' ] . ';}' . $nl;
                  }
                  break;

                case '_panels_styling_panels-padding' :
                  $filler = '';
                  foreach ($value as $k => $v) {
                    $filler .= (strpos($k, 'padding') === 0 && !empty($v) ? $k . ':' . $v . ';' : '');
                  }
                  $pzarc_contents .= (!empty($filler) ? $class_prefix . '.pzarc-panel {' . $filler . '}' . $nl : '');
                  break;

                case ('_panels_styling_panels-borders'):
                  if (($value[ 'border-style' ] !== 'none')) {
                    $pzarc_contents .= pzarc_process_borders($class_prefix . '.pzarc-panel', $value) . $nl;
                  }
                  break;

                // COMPONENTS
                case '_panels_styling_components-background' :
                  $this_key = key($value);
                  if (!empty($value[ 'color' ])) {
                    $pzarc_contents .= $class_prefix . ' .pzarc-components {background-color:' . $value[ 'color' ] . ';}' . $nl;
                  }
                  break;

                case '_panels_styling_components-padding' :
                  $padding = pzarc_process_spacing($value);
                  if (!empty($padding)) {
                    $pzarc_contents .= $class_prefix . ' .pzarc-components {' . $padding . '}' . $nl;
                  }
                  break;

                case ('_panels_styling_components-borders'):
                  if (($value[ 'border-style' ] !== 'none')) {
                    $pzarc_contents .= pzarc_process_borders($class_prefix . ' .pzarc-components', $value) . $nl;
                  }
                  break;

                //TODO: Really got to make this dumb!
                // Titles
                ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                case '_panels_styling_entry-title-font' :
                  $pzarc_contents .= pzarc_process_fonts($class_prefix . ' .entry-title', $value) . $nl;
                  break;

                case ($key === '_panels_styling_entry-title-font-links'):
                  $pzarc_contents .= pzarc_process_links($class_prefix . ' .entry-title ', $value, $nl) . $nl;
                  break;

                case ('_panels_styling_entry-title-borders'):
                  if (($value[ 'border-style' ] !== 'none')) {
                    $pzarc_contents .= pzarc_process_borders($class_prefix . ' .entry-title', $value) . $nl;
                  }
                  break;

                case '_panels_styling_entry-title-font-padding' :
                  $padding = pzarc_process_spacing($value);
                  $pzarc_contents .= $class_prefix . ' .entry-title {' . $padding . ';}' . $nl;
                  break;

                case '_panels_styling_entry-title-font-margin' :
                  $margins = pzarc_process_spacing($value);
                  $pzarc_contents .= $class_prefix . ' .entry-title {' . $margins . ';}' . $nl;
                  break;


                // Meta
                ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                case '_panels_styling_entry-meta-font' :
                  $pzarc_contents .= pzarc_process_fonts($class_prefix . ' .entry-meta', $value) . $nl;
                  break;

                case ($key === '_panels_styling_entry-meta-font-links'):
//                  var_dump($key,$value);
                  $pzarc_contents .= pzarc_process_links($class_prefix . ' .entry-meta ', $value, $nl) . $nl;
                  break;

                //
                // IMAGES
                ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

                case '_panels_styling_entry-image-background' :
                  $pzarc_contents .= pzarc_process_background($class_prefix . ' figure.entry-thumbnail ', $value) . $nl;
                  break;

                case '_panels_styling_entry-image-padding' :
                  //              var_dump($key);
                  $padding = pzarc_process_spacing($value);
                  $pzarc_contents .= $class_prefix . ' figure.entry-thumbnail  {' . $padding . ';}' . $nl;
                  break;

                case ('_panels_styling_entry-image-borders'):
                  if (($value[ 'border-style' ] !== 'none')) {
                    $pzarc_contents .= pzarc_process_borders($class_prefix . ' figure.entry-thumbnail', $value) . $nl;
                  }
                  break;

                // Content
                ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                case '_panels_styling_entry-content-font' :
                  //              var_dump($key);
                  $pzarc_contents .= pzarc_process_fonts($class_prefix . ' .entry-content', $value) . $nl;
                  $pzarc_contents .= pzarc_process_fonts($class_prefix . ' .entry-excerpt', $value) . $nl;
                  break;

                case ($key === '_panels_styling_entry-content-font-links'):
                  $pzarc_contents .= pzarc_process_links($class_prefix . ' .entry-content ', $value, $nl) . $nl;
                  $pzarc_contents .= pzarc_process_links($class_prefix . ' .entry-excerpt ', $value, $nl) . $nl;
                  break;

                // Captions
                ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                //entry-image-caption-font', array('figure.entry-thumbnail span.caption'
                case '_panels_styling_entry-image-caption-font' :
                  //              var_dump($key);
                  $pzarc_contents .= pzarc_process_fonts($class_prefix . ' figure.entry-thumbnail span.caption ', $value) . $nl;
                  break;

                case '_panels_styling_entry-image-caption-font-background' :
                  $pzarc_contents .= pzarc_process_background($class_prefix . ' figure.entry-thumbnail span.caption ', $value) . $nl;
//                  $pzarc_contents .= pzarc_process_bg($class_prefix . ' figure.entry-thumbnail span.caption {', $value) . $nl;
                  break;


                case '_panels_styling_entry-image-caption-font-padding' :
                  //              var_dump($key);
                  $padding = pzarc_process_spacing($value);
                  $pzarc_contents .= $class_prefix . ' figure.entry-thumbnail span.caption {' . $padding . ';}' . $nl;
                  break;


                // Custom
                ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                //
                case '_panels_styling_entry-customfield-1-font' :
                  //              var_dump($key);
                  $pzarc_contents .= pzarc_process_fonts($class_prefix . ' .entry-customfield-1 ', $value) . $nl;
                  break;

                case '_panels_styling_entry-customfield-1-font-background' :
                  $pzarc_contents .= pzarc_process_background($class_prefix . ' .entry-customfield-1 ', $value) . $nl;
//                  $pzarc_contents .= pzarc_process_bg($class_prefix . ' figure.entry-thumbnail span.caption {', $value) . $nl;
                  break;


                case '_panels_styling_entry-customfield-1-font-padding' :
                  //              var_dump($key);
                  $padding = pzarc_process_spacing($value);
                  $pzarc_contents .= $class_prefix . ' .entry-customfield-1 {' . $padding . ';}' . $nl;
                  break;

                case ($key === '_panels_styling_entry-customfield-1-font-links'):
                  $pzarc_contents .= pzarc_process_links($class_prefix . ' .entry-customfield-1 ', $value, $nl) . $nl;
                  break;

                case ('_panels_styling_entry-customfield-1-borders'):
                  if (($value[ 'border-style' ] !== 'none')) {
                    $pzarc_contents .= pzarc_process_borders($class_prefix . ' .entry-customfield-1', $value) . $nl;
                  }
                  break;


                //What is this lot?
                ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
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

                // Custom CSS
                case '_panels_styling_custom-css':
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
        $pzarc_sections_postion = (!empty($pzarc_panels[ '_panels_design_components-position' ]) ? $pzarc_panels[ '_panels_design_components-position' ] : 'top');
        $pzarc_sections_nudge_x = (!empty($pzarc_panels[ '_panels_design_components-nudge-x' ]) ? $pzarc_panels[ '_panels_design_components-nudge-x' ] : 0);
        $pzarc_sections_nudge_y = (!empty($pzarc_panels[ '_panels_design_components-nudge-y' ]) ? $pzarc_panels[ '_panels_design_components-nudge-y' ] : 0);
        $pzarc_sections_width   = (!empty($pzarc_panels[ '_panels_design_components-widths' ]) ? $pzarc_panels[ '_panels_design_components-widths' ] : 100);
        $pzarc_tb               = ($pzarc_sections_postion == 'left' || $pzarc_sections_postion == 'right' ? 'top' : $pzarc_sections_postion);
        $pzarc_lr               = ($pzarc_sections_postion == 'top' || $pzarc_sections_postion == 'bottom' ? 'left' : $pzarc_sections_postion);

        $pzarc_contents .= $class_prefix . '.using-bgimages .pzarc-components{' . $pzarc_tb . ':' . $pzarc_sections_nudge_y . '%;' . $pzarc_lr . ':' . $pzarc_sections_nudge_x . '%;width:' . $pzarc_sections_width . '%;}';
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
  function pzarc_process_links($classes, $properties, $nl)
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

  function pzarc_process_background($classes, $properties)
  {
    $pzarc_bg_css = '';
    // Could come from one of two methods
    if (!empty($properties[ 'color' ])) {
      $pzarc_bg_css .= $classes . ' {background-color:' . $properties[ 'color' ] . ';}';
    }
    if (!empty($properties[ 'background-color' ])) {
      $pzarc_bg_css .= $classes . ' {background-color:' . $properties[ 'background-color' ] . ';}';
    }

    return $pzarc_bg_css;
  }


  /**
   * @param $pzarc_blueprints
   * @param $i
   * @param $nl
   * @param $_architect_options
   * @return string
   *
   * Sets up import of panels CSS
   */
  function pzarc_process_bp_sections(&$pzarc_blueprints, $i, $nl, &$_architect_options)
  {
    $panel_id         = $pzarc_blueprints[ '_blueprints_section-' . $i . '-panel-layout' ];
    $pzarc_panels     = get_post_meta($panel_id, '_architect', true);
    $classes          = '.pzarchitect .pzarc-blueprint_' . $pzarc_blueprints[ '_blueprints_short-name' ] . ' .pzarc-section_' . ($i + 1) . '.pzarc-section-using-panel_' . $pzarc_panels[ '_panels_settings_short-name' ] . ' .pzarc-panel';
    $pzarc_import_css = '';
    $pzarc_mediaq_css = '';

    if (!empty($pzarc_blueprints[ '_blueprints_section-' . $i . '-panel-layout' ])) {
      // var_dump($pzarc_blueprints[ '_blueprints_section-' . $i . '-panel-layout' ]);
      $pzarc_import_css .= '@import url("' . PZARC_CACHE_URL . '/pzarc-panels-layout-' . $panel_id . '-' . $pzarc_panels[ '_panels_settings_short-name' ] . '.css");' . $nl;
      $hmargin = $pzarc_blueprints[ '_blueprints_section-' . $i . '-panels-horiz-margin' ][ 'height' ];

      // Need to do this for each breakpoint
      $columns = intval($pzarc_blueprints[ '_blueprints_section-' . $i . '-columns-breakpoint-1' ]);
      $pzarc_mediaq_css .= '@media (max-width:99999px) {';
      $pzarc_mediaq_css .= $classes . ' {width:' . (((100 - ($hmargin * ($columns - 1))) / $columns)) . '%;margin-bottom:' . $pzarc_blueprints[ '_blueprints_section-' . $i . '-panels-vert-margin' ][ 'width' ] . '%;}';
      $pzarc_mediaq_css .= $classes . ':nth-child(n) {margin-right: ' . ($hmargin) . '%;}';
      $pzarc_mediaq_css .= $classes . ':nth-child(' . $columns . 'n) {margin-right: 0;}';
//            $pzarc_contents_css .= $classes . ' {width:' . (((100 - ($hmargin * ($columns - 1))) / $columns)) . '%;margin-bottom:' . $pzarc_blueprints[ '_blueprints_section-' . $i . '-panels-vert-margin' ][ 'width' ] . '%;}';
      $pzarc_mediaq_css .= $classes . ' .grid-sizer { width:' . (100 / $columns) . '%;}';
      $pzarc_mediaq_css .= '}' . $nl;

      for ($bp = 1; $bp <= 3; $bp++) {
        $columns = intval($pzarc_blueprints[ '_blueprints_section-' . $i . '-columns-breakpoint-' . $bp ]);
        $pzarc_mediaq_css .= '@media (max-width:' . $_architect_options[ 'architect_breakpoint_' . $bp ][ 'width' ] . ') {';
        $pzarc_mediaq_css .= $classes . ' {width:' . (((100 - ($hmargin * ($columns - 1))) / $columns)) . '%;margin-bottom:' . $pzarc_blueprints[ '_blueprints_section-' . $i . '-panels-vert-margin' ][ 'width' ] . '%;}';
        $pzarc_mediaq_css .= $classes . ':nth-child(n) {margin-right: ' . ($hmargin) . '%;}';
        $pzarc_mediaq_css .= $classes . ':nth-child(' . $columns . 'n) {margin-right: 0;}';
//              $pzarc_contents_css .= $classes . ' {width:' . (((100 - ($hmargin * ($columns - 1))) / $columns)) . '%;margin-bottom:' . $pzarc_blueprints[ '_blueprints_section-' . $i . '-panels-vert-margin' ][ 'width' ] . '%;}';
        $pzarc_mediaq_css .= $classes . ' .grid-sizer { width:' . (100 / $columns) . '%;}';
        $pzarc_mediaq_css .= '}' . $nl;
      }

//        // TODO: do we have to use the bg image height instead if it is set??
        $pzarc_panel_height = $pzarc_panels[ '_panels_settings_panel-height' ][ 'height' ];
        $pzarc_mediaq_css .= '.pzarchitect .swiper-container.swiper-container-' . $pzarc_blueprints[ '_blueprints_short-name' ] . ' {'.$pzarc_panels[ '_panels_settings_panel-height-type' ].':' . $pzarc_panel_height . ';}' . $nl;


    }

    return array($pzarc_import_css, $pzarc_mediaq_css);
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
