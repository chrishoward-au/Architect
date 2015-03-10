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
    pzdb('SAVE PROCESS TOP');
//   var_dump($postid,$post, $update);
    // keep an eye on this to be sure it doesn't prevent some saves
    if (!$update) {
      return;
    }
    $screen = ('all' === $postid ? 'refresh-cache' : get_current_screen());
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
    if (!empty($screen) && 'all' === $postid || $screen->id == 'arc-panels' || $screen->id == 'arc-blueprints' || $post->post_type === 'arc-panels' || $post->post_type === 'arc-blueprints') {

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

//      $pzarc_shortname = ($post->post_type === 'arc-panels' ? $pzarc_settings[ '_panels_settings_short-name' ] : $pzarc_settings[ '_blueprints_short-name' ]);


      $filename = PZARC_CACHE_PATH . '/pzarc_css_cache.css';

      wp_mkdir_p(trailingslashit(PZARC_CACHE_PATH));

      // Need to create the file contents

      if ('all' !== $postid) {
        pzdb('save process pre get settings');
        // First off clear the options css cache
        $pzarc_settings = get_post_meta($postid);
        $pzarc_settings = pzarc_flatten_wpinfo($pzarc_settings);
        pzarc_get_defaults();
        global  $_architect;
        $pzarc_bp_settings = array_replace_recursive($_architect[ 'defaults' ][ '_blueprints' ], $pzarc_settings);

        pzdb('save process pre create css');
        pzarc_create_css($postid, $post->post_type, $pzarc_bp_settings);
      } else {
        // get the blueprints and panels and step thru each recreating css
        delete_option('pzarc_css');
//        $pzarc_panels = get_posts(array('post_type' => 'arc-panels', 'post_status' => 'publish','numberposts'=>-1));
//        foreach ($pzarc_panels as $pzarc_panel) {
//          $postid         = $pzarc_panel->ID;
//          $pzarc_settings = get_post_meta($postid);
//          $pzarc_settings = pzarc_flatten_wpinfo($pzarc_settings);
//          pzarc_create_css($postid, $pzarc_panel->post_type, $pzarc_settings);
//        }
        $pzarc_blueprints = get_posts(array('post_type' => 'arc-blueprints', 'post_status' => 'publish','numberposts'=>-1));
        foreach ($pzarc_blueprints as $pzarc_blueprint) {
          $postid         = $pzarc_blueprint->ID;
          $pzarc_settings = get_post_meta($postid);
          $pzarc_settings = pzarc_flatten_wpinfo($pzarc_settings);
          pzarc_get_defaults();
          global  $_architect;
          $pzarc_bp_settings = array_replace_recursive($_architect[ 'defaults' ][ '_blueprints' ], $pzarc_settings);


          pzarc_create_css($postid, $pzarc_blueprint->post_type, $pzarc_bp_settings);
        }
      }

      $pzarc_css_cache = maybe_unserialize(get_option('pzarc_css'));
//      var_dump($pzarc_css_cache[ 'blueprints' ]);
//      var_dump($pzarc_css_cache[ 'panels' ]);

//      $pzarc_css       = "/* Blueprints */\n" . implode(" \n", $pzarc_css_cache[ 'blueprints' ]) . " \n/* Panels */\n" . implode(" \n", $pzarc_css_cache[ 'panels' ]);

      // by this point, the $wp_filesystem global should be working, so let's use it to create a file
// TODO: Mod this so it only updates the one in $postid

      global $wp_filesystem;
      if (isset($pzarc_css_cache[ 'blueprints' ])) {
        foreach ($pzarc_css_cache[ 'blueprints' ] as $k => $v) {
          $filename = PZARC_CACHE_PATH . '/pzarc_blueprint_' . $k . '.css';
          if (!empty($k) && !$wp_filesystem->put_contents(
                  $filename,
                  "/* Blueprint '.$k.'*/\n" . $v,
                  FS_CHMOD_FILE // predefined mode settings for WP files
              )
          ) {
            echo '<p class="error message">Error saving css cache file! Please check the permissions on the WP Uploads folder.</p>';
          }
        }
      }
//      if (isset($pzarc_css_cache[ 'panels' ])) {
//        foreach ($pzarc_css_cache[ 'panels' ] as $k => $v) {
//          $filename = PZARC_CACHE_PATH . '/pzarc_panel_' . $k . '.css';
//          if (!empty($k) && !$wp_filesystem->put_contents(
//                  $filename,
//                  "/* Panel '.$k.'*/\n" . $v,
//                  FS_CHMOD_FILE // predefined mode settings for WP files
//              )
//          ) {
//            echo '<p class="error message">Error saving css cache file! Please check the permissions on the WP Uploads folder.</p>';
//          }
//        }
//      }
      // And finally, let's flush the BFI image cache
      if ((isset($screen->id) && isset($post->post_type)) && ($screen->id == 'arc-panels' || $post->post_type === 'arc-panels') && function_exists('bfi_flush_image_cache')) {
        bfi_flush_image_cache();
      }
    }
    /// die();
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

    global $_architect;
    global $_architect_options;
    pzdb('pre get defaults');
    pzarc_get_defaults(array('blueprints', 'panels'));
    pzdb('post get defaults');
    $defaults = $_architect[ 'defaults' ];
    // Need to create the file contents
    // For each field in stylings, create css
    $pzarc_contents = '';

    switch ($type) {
      case 'arc-blueprints':
        require_once PZARC_PLUGIN_APP_PATH . '/admin/php/arc-save-process-blueprints.php';
//        pzdebug($defaults[ '_blueprints' ]);
        $pzarc_blueprints = pzarc_merge_defaults($defaults[ '_blueprints' ], $pzarc_settings);
        $pzarc_contents .= pzarc_create_blueprint_css($pzarc_blueprints, $pzarc_contents, $postid);

        /** Save css to options cache */
        $pzarc_css_cache = maybe_unserialize(get_option('pzarc_css'));
        // We have to delete it coz we want to use the 'no' otpion
        delete_option('pzarc_css');
        $pzarc_css_cache[ 'blueprints' ][ $pzarc_blueprints[ '_blueprints_short-name' ] ] = pzarc_compress($pzarc_contents);
        add_option('pzarc_css', maybe_serialize($pzarc_css_cache), null, 'no');
        break;

//      case 'arc-panels':
//        require_once PZARC_PLUGIN_APP_PATH . '/admin/php/arc-save-process-panels.php';
//        $pzarc_panels = pzarc_merge_defaults($defaults[ '_panels' ], $pzarc_settings);
//        $pzarc_contents .= pzarc_create_panels_css($pzarc_panels, $pzarc_contents, $postid);
//
//        /** Save css to options cache */
//        $pzarc_css_cache = maybe_unserialize(get_option('pzarc_css'));
//        // We have to delete it coz we want to use the 'no' option
//        delete_option('pzarc_css');
//        $pzarc_css_cache[ 'panels' ][ $pzarc_panels[ '_panels_settings_short-name' ] ] = pzarc_compress($pzarc_contents);
//        add_option('pzarc_css', maybe_serialize($pzarc_css_cache), null, 'no');
//        break;
    }

  }

  /**
   * @param $classes
   * @param $properties
   * @return null|string
   */
  function pzarc_process_fonts($classes, $properties)
  {
    // TODO: Build support for Google fonts and backup
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
        case (!empty($v) && $k == 'font-weight'):
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
    foreach ($properties as $key => $value) {
      // Only process values!
      if ($key != 'units') {
        $iszero   = ($value === 0 || $value === '0');
        $isnotset = $value === '';
        $propval  = $key . ':' . $value;
        $propzero = $key . ':0;';
        $spacing_css .= ($iszero ? $propzero : ($isnotset ? null : $propval . ';'));
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

  function pzarc_process_border_radius($classes, $properties)
  {
    $borders_css = '';

    $borders_css .= (!empty($properties[ 'border-top' ]) ? 'border-top-left-radius:' . $properties[ 'border-top' ] . ';' : '');
    $borders_css .= (!empty($properties[ 'border-right' ]) ? 'border-top-right-radius:' . $properties[ 'border-right' ] . ';' : '');
    $borders_css .= (!empty($properties[ 'border-bottom' ]) ? 'border-bottom-left-radius:' . $properties[ 'border-bottom' ] . ';' : '');
    $borders_css .= (!empty($properties[ 'border-left' ]) ? 'border-bottom-right-radius:' . $properties[ 'border-left' ] . ';' : '');

    return $classes . '{' . $borders_css . '}';
  }

  /**
   * @param $classes
   * @param $properties
   * @return string
   */
  function pzarc_process_links($classes, $properties, $nl)
  {
    // TODO: Should Default use inherit?
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

    if (!empty($properties[ 'visited' ]) || (isset($properties[ 'visited-deco' ]) && strtolower($properties[ 'visited-deco' ]) !== 'default')) {
      $links_css .= $classes . ' a:visited {';
      $links_css .= (!empty($properties[ 'visited' ]) ? 'color:' . $properties[ 'visited' ] . ';' : '');
      $links_css .= (strtolower($properties[ 'visited-deco' ]) !== 'default' ? 'text-decoration:' . strtolower($properties[ 'visited-deco' ]) . ';' : '');
      $links_css .= '}' . $nl;
    }

    return $links_css;
  }

  function pzarc_process_background($classes, $properties)
  {
    // Currently not used
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
   * @param $properties
   * @param $exclude
   * @return bool
   */
  function pzarc_is_empty_vals($properties, $exclude)
  {

    $is_empty = true;
    if (is_array($properties)) {
      foreach ($properties as $key => $value) {
//    var_dump(!in_array($key,$exclude) , !empty($value));
        if (!in_array($key, $exclude) && strlen($value) > 0) {
          $is_empty = false;
          break;
        }
      }
    }

    return $is_empty;
  }

  // TODO: If this was all a class it could be extensible!
  /**
   * @param $source
   * @param $keys
   * @param $value
   * @param $parentClass
   * @return mixed|string
   */
  function pzarc_get_styling($source, $keys, $value, $parentClass)
  {

    // generate correct whosit
    $pzarc_func = 'pzarc_style_' . $keys[ 'style' ];
    $pzarc_css  = '';
    foreach ($keys[ 'classes' ] as $class) {
      $pzarc_css .= (function_exists($pzarc_func) ? call_user_func($pzarc_func, $parentClass . ' ' . $class, $value) : '');
      if (!function_exists($pzarc_func)) {
        //print 'Missing function ' . $pzarc_func;
        pzdb($pzarc_func);
      }
    }

    return $pzarc_css;
  }

  function pzarc_style_background($class, $value)
  {

    return (!empty($value[ 'color' ]) ? $class . ' {background-color:' . $value[ 'color' ] . ';}' . "\n" : null);

  }

  function pzarc_style_padding($class, $value)
  {
//    var_Dump(pzarc_is_empty_vals($value, array('units')),$value);
    return (!pzarc_is_empty_vals($value, array('units')) ? $class . ' {' . pzarc_process_spacing($value) . ';}' . "\n" : null);
  }

  function pzarc_style_margin($class, $value)
  {
    return (!pzarc_is_empty_vals($value, array('units')) ? $class . ' {' . pzarc_process_spacing($value) . ';}' . "\n" : null);
  }

  // *cough!* Hack. TODO: Fix it!
  function pzarc_style_margins($class, $value)
  {
    return (!pzarc_is_empty_vals($value, array('units')) ? $class . ' {' . pzarc_process_spacing($value) . ';}' . "\n" : null);
  }

  function pzarc_style_borders($class, $value)
  {
    return pzarc_process_borders($class, $value) . "\n";
  }

  function pzarc_style_borderradius($class, $value)
  {
    return pzarc_process_border_radius($class, $value) . "\n";
  }

  function pzarc_style_links($class, $value)
  {
    return pzarc_process_links($class, $value, "\n") . "\n";
  }

  function pzarc_style_font($class, $value)
  {
    return pzarc_process_fonts($class, $value) . "\n";
  }

  function pzarc_style_css($class, $value)
  {
    // PHP doesn't like trim used inline
    $value = trim($value);

    return (!empty($value) ? $class . $value : null);
  }
