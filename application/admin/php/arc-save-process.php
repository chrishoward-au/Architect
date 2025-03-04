<?php
  /**
   * Created by PhpStorm.
   * User: chrishoward
   * Date: 6/05/2014
   * Time: 1:57 PM
   */
//add_action('pre_post_update','testx');
  function testx( $post ) {
    var_dump( $post );
  }

  // TODO: Dang it! It's doing that save twice problem again when i use this!!
  // that might because called when revision is saved too
  // See caution https://codex.wordpress.org/Function_Reference/wp_update_post

//  add_action('save_post_arc-panels', 'save_arc_layouts', 99,3);
//  add_action('save_post', 'save_arc_layouts', 999, 3);

  add_action( 'save_post_arc-blueprints', 'save_arc_layouts', 99, 3 ); // v11.1
  function save_arc_layouts( $post_id, $post, $update ) {
//    if ( $post->post_type !== 'arc-blueprints' ) {
//      return;
//    }
    pzdb( 'SAVE PROCESS TOP' );
    if ( ! wp_is_post_revision( $post_id ) ) {

      // unhook this function so it doesn't loop infinitely
      remove_action( 'save_post_arc-blueprints', 'save_arc_layouts', 99 );

      // This wil alwaysbe one update behind, but with it only needing shortname and layout, there should be subsequent updates.
      // This infor is only used by Gutenberg at the moment
      // But do need to find a way to get the new post_meta.
      $arc_default_raw = get_post_meta( $post_id );

      $pzarc_main = array(
          'shortname'   => $arc_default_raw['_blueprints_short-name'][0],
          'layout_type' =>  empty($arc_default_raw['_blueprints_section-0-layout-mode'][0]) || $arc_default_raw['_blueprints_section-0-layout-mode'][0]=='basic'?'grid':$arc_default_raw['_blueprints_section-0-layout-mode'][0] ,
          'source' => empty( $arc_default_raw['_blueprints_content-source'][0] )  ? 'default' : $arc_default_raw['_blueprints_content-source'][0],
      );
      // update the post, which calls save_post again
      wp_update_post( array( 'ID' => $post_id, 'post_excerpt' => maybe_serialize( $pzarc_main ) ) );

//    // re-hook this function
      add_action( 'save_post_arc-blueprints', 'save_arc_layouts', 99, 3 );
    }

    // keep an eye on this to be sure it doesn't prevent some saves
    if ( ! $update ) {
      return;
    }
    // If get_current_Screen doesn't exist we really are in the wrong place!
    if ( ! function_exists( 'get_current_screen' ) && $post_id !== 'all' ) {
      return;
    }
    $screen = ( 'all' === $post_id ? 'refresh-cache' : get_current_screen() );
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
     */ // save the CSS too
    // new wp_filesystem
    // create file named with id e.g. pzarc-cell-layout-123.css
    // Or should we connect this to the template? Potentially there'll be less panel layouts than templates tho
    if ( ! empty( $screen ) && ( 'all' === $post_id || $screen->id == 'arc-panels' || $screen->id == 'arc-blueprints' || $post->post_type === 'arc-panels' || $post->post_type === 'arc-blueprints' ) ) {

      $url = wp_nonce_url( 'post.php?post=' . $post_id . '&action=edit', basename( __FILE__ ) );

      // v1.1.17.0: Added function exists check as sometimes this is called way too early on archive pages. Possibly only in Blox.
      if ( ! function_exists( 'request_filesystem_credentials' ) || ( FALSE === ( $creds = request_filesystem_credentials( $url, '', FALSE, FALSE, NULL ) ) ) ) {
        return; // stop processing here
      }


      if ( ! WP_Filesystem( $creds ) ) {
        request_filesystem_credentials( $url, '', TRUE, FALSE, NULL );

        return;
      }

//      $pzarc_shortname = ($post->post_type === 'arc-panels' ? $pzarc_settings[ '_panels_settings_short-name' ] : $pzarc_settings[ '_blueprints_short-name' ]);


      $filename = PZARC_CACHE_PATH . '/pzarc_css_cache.css';
      wp_mkdir_p( trailingslashit( PZARC_CACHE_PATH ) );

      // Need to create the file contents

      $pzarc_architect_defaults = maybe_unserialize( get_option( '_architect_defaults' ) );
      if ( empty( $pzarc_architect_defaults ) ) {
        pzarc_set_defaults();
        $pzarc_architect_defaults = maybe_unserialize( get_option( '_architect_defaults' ) );
      }
      if ( 'all' !== $post_id ) {
        pzdb( 'save process pre get settings' );
        // First off clear the options css cache
        $pzarc_settings = get_post_meta( $post_id );
        $pzarc_settings = pzarc_flatten_wpinfo( $pzarc_settings );
//        pzarc_get_defaults();
        global $_architect;
        $pzarc_bp_settings = array_replace_recursive( $pzarc_architect_defaults, $pzarc_settings );

        pzdb( 'save process pre create css' );
        pzarc_create_css( $post_id, $post->post_type, $pzarc_bp_settings, $pzarc_architect_defaults );
      } else {
        // get the blueprints and panels and step thru each recreating css
        delete_option( 'pzarc_css' );

        $pzarc_blueprints = get_posts( array(
            'post_type'   => 'arc-blueprints',
            'post_status' => 'publish',
            'numberposts' => - 1,
        ) );

        foreach ( $pzarc_blueprints as $pzarc_blueprint ) {
          $nextid         = $pzarc_blueprint->ID;
          $pzarc_settings = get_post_meta( $nextid );
          $pzarc_settings = pzarc_flatten_wpinfo( $pzarc_settings );
          //pzarc_get_defaults();
          global $_architect;
          $pzarc_bp_settings = array_replace_recursive( $pzarc_architect_defaults, $pzarc_settings );
          pzarc_create_css( $nextid, $pzarc_blueprint->post_type, $pzarc_bp_settings, $pzarc_architect_defaults );
        }
      }

      $pzarc_css_cache = maybe_unserialize( get_option( 'pzarc_css' ) );

      // by this point, the $wp_filesystem global should be working, so let's use it to create a file

      global $wp_filesystem, $pzarc_css_success;
      $pzarc_css_success = TRUE;

      if ( isset( $pzarc_css_cache['blueprints'] ) ) {

        $blueprints = array();

        if ( 'all' !== $post_id && isset( $pzarc_settings['_blueprints_short-name'] ) ) {
          $blueprints[ $pzarc_settings['_blueprints_short-name'] ] = $pzarc_css_cache['blueprints'][ $pzarc_settings['_blueprints_short-name'] ];
        } else {
          $blueprints = $pzarc_css_cache['blueprints'];
        }
        foreach ( $blueprints as $k => $v ) {

          /* v.1.17.0: Let's try using WP options and writing css to the footer so we don't need to use the filesystem - which is only available in admin
          * At this rate, we could extend it to automatically grab css at runtime instead of using options even.
          */
          update_option( '_arc-css_' . $k, $v );
          /*  **** */ //          $filename = str_replace(' ','',PZARC_CACHE_PATH . 'pzarc_blueprint_' . $k . '.css');
//
//          if (!empty($k) && !$wp_filesystem->put_contents(
//                  $filename,
//                  $v,
//                  FS_CHMOD_FILE // predefined mode settings for WP files
//              )
//          ) {
//            echo '<p class="error message notice notice-error">Error saving css cache file for Blueprint '.$k.'! Please check the permissions on the WP Uploads folder.</p>';
//            $pzarc_css_success = false;
//          }
        }
      }
      // update Blueprints option
      update_option( 'arc_blueprints', pzarc_get_blueprints( FALSE ) );

      // And finally, let's flush the BFI image cache
      if ( ( isset( $screen->id ) && isset( $post->post_type ) ) && ( $screen->id == 'arc-blueprints' || $post->post_type === 'arc-blueprints' ) && function_exists( 'bfi_flush_image_cache' ) ) {
        bfi_flush_image_cache();
      }
    }
    // die( 'You have left the building' );

  }

  /**
   * @function: pzarc_create_css
   *
   * @param      $post_id
   * @param null $type
   *
   * @return string
   *
   * @purpose : It is necessary to create the css files as they are imported separately. Redux can't handle that.
   *
   */
  function pzarc_create_css( $post_id, $type = NULL, $pzarc_settings, $pzarc_architect_defaults ) {
    //  var_Dump($pzarc_settings);
    global $_architect;
    global $_architect_options;
    pzdb( 'pre get defaults' );
    //pzarc_get_defaults(false);
    pzdb( 'post get defaults' );
    //$defaults = $_architect[ 'defaults' ];
    // Need to create the file contents
    // For each field in stylings, create css
    $pzarc_contents = '';

    switch ( $type ) {
      case 'arc-blueprints':
        require_once PZARC_PLUGIN_APP_PATH . '/admin/php/arc-save-process-blueprints.php';

        $pzarc_blueprints = array_replace_recursive( $pzarc_architect_defaults, $pzarc_settings );

        $pzarc_contents .= pzarc_create_blueprint_css( $pzarc_blueprints, $pzarc_contents, $post_id );
//die();
        /** Save css to options cache */
        $pzarc_css_cache = maybe_unserialize( get_option( 'pzarc_css' ) );
        // We have to delete it coz we want to use the 'no' otpion
        delete_option( 'pzarc_css' );
        $pzarc_css_cache['blueprints'][ $pzarc_blueprints['_blueprints_short-name'] ] = pzarc_compress( $pzarc_contents );
        add_option( 'pzarc_css', maybe_serialize( $pzarc_css_cache ), NULL, 'no' );
        break;

    }
  }

