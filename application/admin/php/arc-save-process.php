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
    // If get_current_Screen doesn't exist we really are in the wrong place!
    if (!function_exists('get_current_screen') && $postid !== 'all') {
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

    if (!empty($screen) && ('all' === $postid || $screen->id == 'arc-panels' || $screen->id == 'arc-blueprints' || $post->post_type === 'arc-panels' || $post->post_type === 'arc-blueprints')) {

      $url = wp_nonce_url('post.php?post=' . $postid . '&action=edit', basename(__FILE__));

      if (false === ($creds = request_filesystem_credentials($url, '', false, false, null))) {
        return; // stop processing here
      }


      if (!WP_Filesystem($creds)) {
        request_filesystem_credentials($url, '', true, false, null);

        return;
      }

//      $pzarc_shortname = ($post->post_type === 'arc-panels' ? $pzarc_settings[ '_panels_settings_short-name' ] : $pzarc_settings[ '_blueprints_short-name' ]);


      $filename = PZARC_CACHE_PATH . '/pzarc_css_cache.css';

      wp_mkdir_p(trailingslashit(PZARC_CACHE_PATH));

      // Need to create the file contents

      $pzarc_architect_defaults = maybe_unserialize( get_option( '_architect_defaults' ) );
      if (empty($pzarc_architect_defaults)) {
        pzarc_set_defaults();
        $pzarc_architect_defaults = maybe_unserialize( get_option( '_architect_defaults' ) );
      }
      if ( 'all' !== $postid) {
        pzdb('save process pre get settings');
        // First off clear the options css cache
        $pzarc_settings = get_post_meta($postid);
        $pzarc_settings = pzarc_flatten_wpinfo($pzarc_settings);
//        pzarc_get_defaults();
        global $_architect;
        $pzarc_bp_settings = array_replace_recursive( $pzarc_architect_defaults, $pzarc_settings);

        pzdb('save process pre create css');
        pzarc_create_css($postid, $post->post_type, $pzarc_bp_settings,$pzarc_architect_defaults);
      } else {
        // get the blueprints and panels and step thru each recreating css
        delete_option('pzarc_css');

        $pzarc_blueprints = get_posts(array('post_type'   => 'arc-blueprints',
                                            'post_status' => 'publish',
                                            'numberposts' => -1));

        foreach ($pzarc_blueprints as $pzarc_blueprint) {
          $nextid         = $pzarc_blueprint->ID;
          $pzarc_settings = get_post_meta($nextid);
          $pzarc_settings = pzarc_flatten_wpinfo($pzarc_settings);
          //pzarc_get_defaults();
          global $_architect;
          $pzarc_bp_settings = array_replace_recursive( $pzarc_architect_defaults, $pzarc_settings);
          pzarc_create_css($nextid, $pzarc_blueprint->post_type, $pzarc_bp_settings,$pzarc_architect_defaults);
        }
      }

      $pzarc_css_cache = maybe_unserialize(get_option('pzarc_css'));

      // by this point, the $wp_filesystem global should be working, so let's use it to create a file

      global $wp_filesystem;

      if (isset($pzarc_css_cache[ 'blueprints' ])) {

        $blueprints = array();

        if ('all' !== $postid) {
          $blueprints[ $pzarc_settings[ '_blueprints_short-name' ] ] = $pzarc_css_cache[ 'blueprints' ][ $pzarc_settings[ '_blueprints_short-name' ] ];
        } else {
          $blueprints = $pzarc_css_cache[ 'blueprints' ];
        }
        foreach ($blueprints as $k => $v) {
          $filename = str_replace(' ','',PZARC_CACHE_PATH . 'pzarc_blueprint_' . $k . '.css');
          if (!empty($k) && !$wp_filesystem->put_contents(
                  $filename,
                  $v,
                  FS_CHMOD_FILE // predefined mode settings for WP files
              )
          ) {
            echo '<p class="error message">Error saving css cache file for Blueprint '.$k.'! Please check the permissions on the WP Uploads folder.</p>';
          }
        }
      }
      // And finally, let's flush the BFI image cache
      if ((isset($screen->id) && isset($post->post_type)) && ($screen->id == 'arc-blueprints' || $post->post_type === 'arc-blueprints') && function_exists('bfi_flush_image_cache')) {
        bfi_flush_image_cache();
      }
    }
   // die( 'You have left the building' );
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
  function pzarc_create_css($postid, $type = null, $pzarc_settings,$pzarc_architect_defaults)
  {
  //  var_Dump($pzarc_settings);
    global $_architect;
    global $_architect_options;
    pzdb('pre get defaults');
    //pzarc_get_defaults(false);
    pzdb('post get defaults');
    //$defaults = $_architect[ 'defaults' ];
    // Need to create the file contents
    // For each field in stylings, create css
    $pzarc_contents = '';

    switch ($type) {
      case 'arc-blueprints':
        require_once PZARC_PLUGIN_APP_PATH . '/admin/php/arc-save-process-blueprints.php';

        $pzarc_blueprints = array_replace_recursive($pzarc_architect_defaults, $pzarc_settings);

        $pzarc_contents .= pzarc_create_blueprint_css($pzarc_blueprints, $pzarc_contents, $postid);

        /** Save css to options cache */
        $pzarc_css_cache = maybe_unserialize(get_option('pzarc_css'));
        // We have to delete it coz we want to use the 'no' otpion
        delete_option('pzarc_css');
        $pzarc_css_cache[ 'blueprints' ][ $pzarc_blueprints[ '_blueprints_short-name' ] ] = pzarc_compress($pzarc_contents);
        add_option('pzarc_css', maybe_serialize($pzarc_css_cache), null, 'no');
        break;

    }
  }

