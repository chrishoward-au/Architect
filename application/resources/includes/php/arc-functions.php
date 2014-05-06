<?php

  if (!function_exists('pzdebug')) {

    //---------------------------------------------------------------------------------------------------
    // Debug
    //---------------------------------------------------------------------------------------------------
    /**
     * [pzdebug description]
     * @param  string $value ='' [description]
     * @return [type]           [description]
     */
    function pzdebug($value = '')
    {
      $btr  = debug_backtrace();
      $line = $btr[ 0 ][ 'line' ];
      $file = basename($btr[ 0 ][ 'file' ]);
      print"<pre>$file:$line</pre>\n";
      if (is_array($value)) {
        print"<pre>";
        print_r($value);
        print"</pre>\n";
      } elseif (is_object($value)) {
        var_dump($value);
      } else {
        print("<p>&gt;${value}&lt;</p>");
      }
    }

  }

  function arc_tax_string_list($tax, $prefix, $suffix, $separator)
  {
    $list  = '';
    $count = count($tax);
    $i     = 1;
    if (is_array($tax)) {
      foreach ($tax as $key => $value) {
        $list .= $prefix . $value->slug . $suffix . ($i++ == $count ? '' : $separator);
      }
    }

    return $list;
  }

  function pzarc_squish($array)
  {
    $return_array = array();
    foreach ($array as $key => $value) {
      $return_array[ $key ] = $array[ $key ][ 0 ];
    }

    return $return_array;
  }

  function pzarc_create_css($postid, $type = null)
  {


    global $pzarchitect;
    pzarc_set_defaults();
    $defaults = $pzarchitect[ 'defaults' ];
    // Need to create the file contents
    // For each field in stylings, create css
    $pzarc_contents = '';

    switch ($type) {
      case 'arc-blueprints':
        $pzarc_blueprints = get_post_meta($postid, '_architect', true);
        $pzarc_blueprints = pzarc_merge_defaults($defaults[ '_blueprints' ], $pzarc_blueprints);
        $pzarc_contents   = "/* This is the css for blueprint $postid " . $pzarc_blueprints[ '_blueprints_short-name' ] . "*/\n";

        for ($i = 0; $i < 3; $i++) {
          if (!empty($pzarc_blueprints[ '_blueprints_section-' . $i . '-panel-layout' ])) {

            $pzarc_contents .= '@import url("' . PZARC_CACHE_URL . '/pzarc-panels-layout-' . $pzarc_blueprints[ '_blueprints_section-' . $i . '-panel-layout' ] . '.css");' . "\n";

            $columns = intval($pzarc_blueprints[ '_blueprints_section-' . $i . '-columns' ]);
            $hmargin = $pzarc_blueprints[ '_blueprints_section-' . $i . '-panels-horiz-margin' ];
            $classes = '.pzarc-blueprint_' . $pzarc_blueprints[ '_blueprints_short-name' ] . ' .pzarc-panel';
            $pzarc_contents .= $classes . ' {width:' . (((100 - $hmargin * ($columns)) / $columns)) . '%;margin-left:' . ($hmargin / 2) . '%;margin-right:' . ($hmargin / 2) . '%;margin-bottom:' . $pzarc_blueprints[ '_blueprints_section-' . $i . '-panels-vert-margin' ] . '%;}';

          }

        }
//        var_dump($pzarc_blueprints);
        break;
      case 'arc-panels':
        $pzarc_panels   = get_post_meta($postid, '_architect', true);
        $pzarc_panels   = pzarc_merge_defaults($defaults[ '_panels' ], $pzarc_panels);
        $pzarc_contents = '/* This is the css for panel $postid ' . $pzarc_panels[ '_panels_settings_short-name' ] . '*/' . "\n";
        // step thru each field looking for ones to format
        $class_prefix = '.pzarchitect .pzarc-panel_' . $pzarc_panels[ '_panels_settings_short-name' ];
        //_panels_settings_short-name
        foreach ($pzarc_panels as $key => $value) {
          switch (true) {

            case ($key == '_panels_design_responsive-hide-content' && !empty($pzarc_panels[ '_panels_design_responsive-hide-content' ])):
              $pzarc_contents .= '@media (max-width: ' . $pzarc_panels[ '_panels_design_responsive-hide-content' ] . 'px) { .pzarchitect .pzarc-' . $postid . ' entry-content, .pzarchitect .pzarc-' . $postid . ' .entry-excerpt {display:none;}}' . "\n";
              break;


            case ($key == '_panels_design_preview'):
              $pzarc_left_margin  = (!empty($pzarc_panels[ '_panels_design_image-margin-left' ]) ? $pzarc_panels[ '_panels_design_image-margin-left' ] : 0);
              $pzarc_right_margin = (!empty($pzarc_panels[ '_panels_design_image-margin-right' ]) ? $pzarc_panels[ '_panels_design_image-margin-right' ] : 0);
              $pzarc_layout       = json_decode($value, true);
              $pzarc_contents .= $class_prefix . ' .entry-title {width:' . $pzarc_layout[ 'title' ][ 'width' ] . '%;}' . "\n";
              // Don't give thumbnail div a width if it's in the content
              if ($pzarc_panels[ '_pzarc_layout-excerpt-thumb' ] == 'none') {
                $pzarc_contents .= $class_prefix . ' .entry-thumbnail {width:' . ($pzarc_layout[ 'image' ][ 'width' ] - $pzarc_left_margin - $pzarc_right_margin) . '%;}' . "\n";
              } else {
                $pzarc_contents .= $class_prefix . ' .entry-thumbnail {width:' . $pzarc_panels[ '_panels_design_image-max-width' ] . 'px;}' . "\n";
              }
              $pzarc_contents .= $class_prefix . ' .entry-content {width:' . $pzarc_layout[ 'content' ][ 'width' ] . '%;}' . "\n";
              $pzarc_contents .= $class_prefix . ' .entry-excerpt {width:' . $pzarc_layout[ 'excerpt' ][ 'width' ] . '%;}' . "\n";
              $pzarc_contents .= $class_prefix . ' .entry-meta1 {width:' . $pzarc_layout[ 'meta1' ][ 'width' ] . '%;}' . "\n";
              $pzarc_contents .= $class_prefix . ' .entry-meta2 {width:' . $pzarc_layout[ 'meta2' ][ 'width' ] . '%;}' . "\n";
              $pzarc_contents .= $class_prefix . ' .entry-meta3 {width:' . $pzarc_layout[ 'meta3' ][ 'width' ] . '%;}' . "\n";
              break;

            case ($key == '_panels_design_image-margin-left' && ($value === 0 || $value > 0)):
              $pzarc_contents .= $class_prefix . ' .entry-thumbnail {margin-left: ' . $value . '%;}' . "\n";
              break;

            case ($key == '_panels_design_image-margin-right' && ($value === 0 || $value > 0)):
              $pzarc_contents .= $class_prefix . ' .entry-thumbnail {margin-right: ' . $value . '%;}' . "\n";
              break;

            case ($key == '_panels_design_image-margin-top' && ($value === 0 || $value > 0)):
              $pzarc_contents .= $class_prefix . ' .entry-thumbnail {margin-top: ' . $value . '%;}' . "\n";
              break;

            case ($key == '_panels_design_image-margin-bottom' && ($value === 0 || $value > 0)):
              $pzarc_contents .= $class_prefix . ' .entry-thumbnail {margin-bottom: ' . $value . '%;}' . "\n";
              break;

            // Setup all the custom CSS
            // I think this is all managed by Redux...
            case (strpos($key, '_panels_styling') === 0 && !empty($value)):
              //section-panel-setting

              switch ($key) {
                case '_panels_styling_panels-bg' :
                  $this_key = key($value);
                  $pzarc_contents .= $class_prefix . '.pzarc-panel {' . $this_key . ':' . $value[ $this_key ] . ';}' . "\n";
                  break;
                case '_panels_styling_panels-padding' :
                  $filler = '';
                  foreach ($value as $k => $v) {
                    $filler .= (strpos($k, 'padding') === 0 && !empty($v) ? $k . ':' . $v . ';' : '');
                  }
                  $pzarc_contents .= (!empty($filler) ? $class_prefix . '.pzarc-panel {' . $filler . '}' . "\n" : '');
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
    }


    $pzarc_sections_postion = (!empty($pzarc_panels[ '_panels_design_components-position' ]) ? $pzarc_panels[ '_panels_design_components-position' ] : 'top');
    $pzarc_sections_nudge_x = (!empty($pzarc_panels[ '_panels_design_components-nudge-x' ]) ? $pzarc_panels[ '_panels_design_components-nudge-x' ] : 0);
    $pzarc_sections_nudge_y = (!empty($pzarc_panels[ '_panels_design_components-nudge-y' ]) ? $pzarc_panels[ '_panels_design_components-nudge-y' ] : 0);
    $pzarc_sections_width   = (!empty($pzarc_panels[ '_panels_design_components-widths' ]) ? $pzarc_panels[ '_panels_design_components-widths' ] : 100);
    $pzarc_tb               = ($pzarc_sections_postion == 'left' || $pzarc_sections_postion == 'right' ? 'top' : $pzarc_sections_postion);
    $pzarc_lr               = ($pzarc_sections_postion == 'top' || $pzarc_sections_postion == 'bottom' ? 'left' : $pzarc_sections_postion);

    $pzarc_contents .= $class_prefix . ' .abs-content {' . $pzarc_tb . ':' . $pzarc_sections_nudge_y . '%;' . $pzarc_lr . ':' . $pzarc_sections_nudge_x . '%;width:' . $pzarc_sections_width . '%;}';
//    break;
//  }

//die();
  return $pzarc_contents;
  }

  function pzarc_get_string($start, $end, $source)
  {

    preg_match("/(?<=" . $start . ").+?(?=" . $end . ")/uim", $source, $result);

    return $result[ 0 ];

  }

  function pzarc_redux_font($id, $selectors, $defaults = null)
  {

    return array(
        'title'           => __('Font', 'pzarc'),
        'id'              => $id,
        'output'          => $selectors,
        'type'            => 'typography',
        'text-decoration' => true,
        'default'         => $defaults,
    );
  }

  function pzarc_redux_bg($id, $selectors, $defaults = null)
  {
    return array(
        'title'                 => __('Background', 'pzarc'),
        'id'                    => $id,
        'output'                => $selectors,
        'compiler'              => $selectors,
        'type'                  => 'background',
        'background-image'      => false,
        'background-repeat'     => false,
        'background-size'       => false,
        'background-attachment' => false,
        'background-position'   => false,
        'preview'               => false,
        'default'               => $defaults,
    );
  }

  function pzarc_redux_padding($id, $selectors, $defaults = null)
  {
    return array(
        'title'   => __('Padding', 'pzarc'),
        'id'      => $id,
        'output'  => $selectors,
        'mode'    => 'padding',
        'type'    => 'spacing',
        'units'   => array('px', '%'),
        'default' => $defaults,
    );

  }

  function pzarc_redux_links($id, $selectors, $defaults = null)
  {
    return array(
        'title'   => __('Links', 'pzarc'),
        'id'      => $id,
        'type'    => 'link_color',
        'output'  => $selectors,
        'default' => $defaults
    );

  }

  function pzarc_redux_borders($id, $selectors, $defaults = null)
  {

    return array(
        'title'   => __('Border', 'pzarc'),
        'id'      => $id,
        'type'    => 'border',
        'all'     => false,
        'output'  => $selectors,
        'default' => $defaults
    );
  }

  function pzarc_set_defaults()
  {
    // TODO: Remove this once Dovy fixes MB defaults... or maybe not...
    require_once PZARC_PLUGIN_PATH . '/admin/php/class_arc_Panels_Layouts.php';
    require_once PZARC_PLUGIN_PATH . '/admin/php/class_arc_Blueprints_Layouts.php';
    global $pzarchitect;
    $pzarchitect[ 'defaults' ][ 'blueprints' ]                                 = array();
    $blueprint_layout_general                                                  = pzarc_blueprint_layout_general($pzarchitect[ 'defaults' ][ 'blueprints' ]);
    $pzarc_blueprint_content_general                                           = pzarc_blueprint_content_general($pzarchitect[ 'defaults' ][ 'blueprints' ]);
    $pzarc_blueprint_layout                                                    = pzarc_blueprint_layout($pzarchitect[ 'defaults' ][ 'blueprints' ]);
    $pzarc_contents_metabox                                                    = pzarc_contents_metabox($pzarchitect[ 'defaults' ][ 'blueprints' ]);
    $pzarchitect[ 'defaults' ][ 'blueprints' ][ '_blueprint_layout_general' ]  = $blueprint_layout_general[ 0 ][ 'sections' ];
    $pzarchitect[ 'defaults' ][ 'blueprints' ][ '_blueprint_content_general' ] = $pzarc_blueprint_content_general[ 0 ][ 'sections' ];
    $pzarchitect[ 'defaults' ][ 'blueprints' ][ '_blueprint_layout' ]          = $pzarc_blueprint_layout[ 0 ][ 'sections' ];
    $pzarchitect[ 'defaults' ][ 'blueprints' ][ '_contents_metabox' ]          = $pzarc_contents_metabox[ 0 ][ 'sections' ];

    $pzarc_panel_general_settings                                       = pzarc_panel_general_settings($pzarchitect[ 'defaults' ][ 'panels' ]);
    $pzarc_panels_design                                                = pzarc_panels_design($pzarchitect[ 'defaults' ][ 'panels' ]);
    $pzarc_panels_styling                                               = pzarc_panels_styling($pzarchitect[ 'defaults' ][ 'panels' ]);
    $pzarchitect[ 'defaults' ][ 'panels' ][ '_panel_general_settings' ] = $pzarc_panel_general_settings[ 0 ][ 'sections' ];
    $pzarchitect[ 'defaults' ][ 'panels' ][ '_panels_design' ]          = $pzarc_panels_design[ 0 ][ 'sections' ];
    $pzarchitect[ 'defaults' ][ 'panels' ][ '_panels_styling' ]         = $pzarc_panels_styling[ 0 ][ 'sections' ];

    foreach ($pzarchitect[ 'defaults' ][ 'blueprints' ] as $key1 => $value1) {
      foreach ($value1 as $key2 => $value2) {
        foreach ($value2 as $key3 => $fields) {
          if (is_array($fields)) {
            foreach ($fields as $key4 => $field) {
              if (isset($field[ 'id' ])) {
                $pzarchitect[ 'defaults' ][ '_blueprints' ][ $field[ 'id' ] ] = $field[ 'default' ];
              }
            }
          }
        }
      }
    }

    unset($pzarchitect[ 'defaults' ][ 'blueprints' ]);

    foreach ($pzarchitect[ 'defaults' ][ 'panels' ] as $key1 => $value1) {
      foreach ($value1 as $key2 => $value2) {
        foreach ($value2 as $key3 => $fields) {
          if (is_array($fields)) {
            foreach ($fields as $key4 => $field) {
              if (isset($field[ 'id' ])) {
                $pzarchitect[ 'defaults' ][ '_panels' ][ $field[ 'id' ] ] = $field[ 'default' ];
              }
            }
          }
        }
      }
    }
    unset($pzarchitect[ 'defaults' ][ 'panels' ]);

  }

  function pzarc_merge_defaults($defaultvs, $setvals)
  {

    foreach ($defaultvs as $key => $value) {
      if (!isset($setvals[ $key ])) {
        $setvals[ $key ] = $value;
      }
    }

    return $setvals;
  }

  add_action('save_post', 'save_arc_layouts', 99);
  function save_arc_layouts($postid)
  {
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
    if ($screen->id == 'arc-panels' || $screen->id == 'arc-blueprints') {
      // save the CSS too
      // new wp_filesystem
      // create file named with id e.g. pzarc-cell-layout-123.css
      // Or should we connect this to the template? Potentially there'll be less panel layouts than templates tho

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
