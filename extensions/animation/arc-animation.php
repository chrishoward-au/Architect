<?php
/**
 * Project pizazzwp-architect.
 * File: arc-animation.php
 * User: chrishoward
 * Date: 23/04/15
 * Time: 8:42 PM
 */

  global $_architect_options;
  if ( ! isset( $GLOBALS[ '_architect_options' ] ) ) {
    $GLOBALS[ '_architect_options' ] = get_option( '_architect_options', array() );
  }

  if ( is_admin() ) {
    require_once plugin_dir_path( __FILE__ ) . '/arc-animation-admin.php';
    new arcAnimationAdmin();
  } elseif ( !empty($_architect_options['architect_animation-enable']) ) {
    // We need to include the admin file for the defaults
    require_once plugin_dir_path( __FILE__ ) . '/arc-animation-admin.php';
    require_once plugin_dir_path( __FILE__ ) . '/arc-animation-public.php';
  }
