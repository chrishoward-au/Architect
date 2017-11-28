<?php

	/*
	  Plugin Name: Architect content display framework
	  Plugin URI: http://architect4wp.com
	  Description: Build the content layouts that your theme or page builder can't.
	  Version: 1.10.8
	  Author: Chris Howard
	  Author URI: http://pizazzwp.com
	  License: GNU GPL v2
	  Support: support@pizazzwp.com

	 * @fs_premium_only /extensions/
	 */

	if ( ! defined( 'ABSPATH' ) ) {
		echo '+++Divide By Cucumber Error. Please Reinstall Universe And Reboot +++. R.I.P Terry Pratchett 1948-2015';
		exit;
	} // Exit if accessed directly

	/**
	 * REMEMBER TO UPDATE VERSION IN arc-admin.scss
	 */
	define( 'PZARC_VERSION', '1.10.8' );
  if ( ! function_exists( 'arc_fs' ) ) {


    if ( ! function_exists( 'pzdb' ) ) {
      function pzdb( $pre = NULL, $var = 'dorkus' ) {
        if ( defined( 'PZARC_DEBUG' ) && PZARC_DEBUG ) {
          static $oldtime;
          $oldtime = empty( $oldtime ) ? microtime( TRUE ) : $oldtime;
          $btr     = debug_backtrace();
          $line    = $btr[0]['line'];
          $file    = basename( $btr[0]['file'] );
          global $pzstart_time;
          echo "\n<!-- " . strtoupper( $pre ) . ': ' . $file . ':' . $line . ': ' . round( ( microtime( TRUE ) - $pzstart_time ), 5 ) . 's. Time since last: ' . round( microtime( TRUE ) - $oldtime, 5 ) . "s -->\n";
          $oldtime = microtime( TRUE );
          if ( $var !== 'dorkus' ) {
            var_dump( $var );
          }
        }
      }

      if ( defined( 'PZARC_DEBUG' ) && PZARC_DEBUG ) {
        global $pzstart_time;
        $pzstart_time = microtime( TRUE );
        pzdb( 'start' );
      }

      include_once 'load-freemius.php';

      require_once( 'class_pzarchitect.php' );
      $pzarc = new pzArchitect();


      pzdb( 'bottom' );

    } else {
      die( 'Another copy of Architect,'. PZARC_VERSION .', is active. Please deactivate it first and then activate this new one, version 1.10.0 ');
    }
  }

  add_filter( 'plugin_action_links_'.plugin_basename(__FILE__), 'pzarc_plugin_action_links' );
  add_filter( 'network_admin_plugin_action_links_'.plugin_basename(__FILE__), 'pzarc_plugin_action_links' );

  function pzarc_plugin_action_links( $links ) {
    $links[] = '<a href="https://s3.amazonaws.com/341public/LATEST/Architect/architect-changelog.html" target="_blank">Changelog</a>';
    return $links;
  }