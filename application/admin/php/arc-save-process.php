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
      $pzarc_settings = get_post_meta($postid);
//      var_dump($pzarc_settings,$post->post_type,$pzarc_settings['_blueprints_short-name']);

      $pzarc_settings = pzarc_flatten_wpinfo($pzarc_settings);

      $pzarc_shortname = ($post->post_type === 'arc-panels' ? $pzarc_settings[ '_panels_settings_short-name' ] : $pzarc_settings[ '_blueprints_short-name' ]);

      $upload_dir = wp_upload_dir();

      $filename = trailingslashit($upload_dir[ 'basedir' ]) . '/cache/pizazzwp/arc/pz' . $post->post_type . '-layout-' . $postid . '-' . $pzarc_shortname . '.css';

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

      // And finally, let's flush the BFI image cache
      if ($screen->id == 'arc-panels' || $post->post_type === 'arc-panels' && function_exists('bfi_flush_image_cache')) {
        bfi_flush_image_cache();
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

    switch ($type) {
      case 'arc-blueprints':
        require_once PZARC_PLUGIN_APP_PATH . '/admin/php/arc-save-process-blueprints-new.php';
        $pzarc_blueprints = pzarc_merge_defaults($defaults[ '_blueprints' ], $pzarc_settings);
        $pzarc_contents .= pzarc_create_blueprint_css($pzarc_blueprints, $pzarc_contents, $postid);
        break;

      case 'arc-panels':
        require_once PZARC_PLUGIN_APP_PATH . '/admin/php/arc-save-process-panels.php';
        $pzarc_panels = pzarc_merge_defaults($defaults[ '_panels' ], $pzarc_settings);
        $pzarc_contents .= pzarc_create_panels_css($pzarc_panels, $pzarc_contents, $postid);
        break;
    }

    return $pzarc_contents;
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

  // TODO: If this was all a class it could be extensible!
  function pzarc_get_styling($source, $keys, $value, $classes)
  {
    // TODO: We need to makes it so only one declaration per case. i.e. class {padding;margins;border;etc}
    // which would require smarterness by the caller.
    if ('blueprint' === $source) {
      switch ($keys[ 'id' ]) {
        case 'blueprint':
          // Note no space for this class as it's in the same declaration
          $keys[ 'class' ] = $classes . '.pzarc-blueprint';
          break;
        case  'blueprint-custom':
          $keys[ 'class' ] = '';
          break;
        case  'sections':
          $keys[ 'class' ] = $classes . ' .pzarc-section';
          break;
        case  'pzarc-section_1':
          $keys[ 'class' ] = $classes . ' .pzarc-section_1';
          break;
        case  'pzarc-section_2':
          $keys[ 'class' ] = $classes . ' .pzarc-section_2';
          break;
        case  'pzarc-section_3':
          $keys[ 'class' ] = $classes . ' .pzarc-section_3';
          break;
        case  'pzarc-navigator':
          $keys[ 'class' ] = $classes . ' .pzarc-navigator';
          break;
        case  'pzarc-navigator-items':
          $keys[ 'class' ] = $classes . ' .arc-slider-slide-nav-item'; // not always!! ugh!
          break;

      }

    }
    if ('panel' === $source) {
      switch (true) {
        case 'panels' === $keys['id'] :
          $keys[ 'class' ] = $classes . '.pzarc-panel';
          break;
        case 'components' === $keys['id'] :
          $keys[ 'class' ] = $classes . ' .pzarc-components';
          break;
        case 'entry-title'  === $keys['id']:
          $keys[ 'class' ] = $classes . ' .entry-title';
          break;
        case 'entry-meta'  === $keys['id']:
          $keys[ 'class' ] = $classes . ' .entry-meta';
          break;
        case 'entry-content'  === $keys['id']:
          $keys[ 'class' ] = $classes . ' .entry-content';
          break;
        case strpos( $keys['id'],'entry-customfield-')===0 :
          $keys[ 'class' ] = $classes . ' .'.$keys['id'];
          break;
        case 'custom'  === $keys['id']:
          $keys[ 'class' ] = $classes . '';
          break;
        case 'entry-readmore'  === $keys['id']:
          $keys[ 'class' ] = $classes . ' a.readmore';
          break;
        case 'entry-image'  === $keys['id']:
          $keys[ 'class' ] = $classes . ' figure.entry-thumbnail';
          break;
        case 'entry-image-caption'  === $keys['id']:
          $keys[ 'class' ] = $classes . ' figure.entry-thumbnail caption';
          break;
        case 'hentry'  === $keys['id']:
          $keys[ 'class' ] = $classes . ' .hentry';
          break;
      }

    }


    //Need to do the above switch for Panels
    // generate correct whosit
    $pzarc_func = 'pzarc_style_' . $keys[ 'style' ];

    $pzarc_css  = (function_exists($pzarc_func)?call_user_func($pzarc_func, $keys[ 'class' ], $value):'');

    return $pzarc_css;
  }

  function pzarc_style_background($class, $value)
  {
    return (!empty($value[ 'color' ]) ? $class . ' {background-color:' . $value[ 'color' ] . ';}' . "\n" : null);

  }

  function pzarc_style_padding($class, $value)
  {
    return (!pzarc_is_empty_vals($value, array('units')) ? $class . ' {' . pzarc_process_spacing($value) . ';}' . "\n" : null);
  }

  function pzarc_style_margins($class, $value)
  {
    return (!pzarc_is_empty_vals($value, array('units')) ? $class . ' {' . pzarc_process_spacing($value) . ';}' . "\n" : null);
  }

  function pzarc_style_borders($class, $value)
  {
    return pzarc_process_borders($class, $value) . "\n";
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
