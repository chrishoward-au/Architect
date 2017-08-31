<?php

	/*
	  Plugin Name: Architect content display framework
	  Plugin URI: http://architect4wp.com
	  Description: Architect is a framework for creating and managing custom content layouts. <strong>Build your own slider, grid, tabbed, gallery, masonry, accordion or tabular layouts with ANY content source</strong>. Display using shortcodes, widgets, Blox blocks, Beaver Builder modules, WP action hooks and template tags, or override the WP Gallery shortcode layout with your own.
	  Version: 1.10.0 RC2
	  Author: Chris Howard
	  Author URI: http://pizazzwp.com
	  License: GNU GPL v2
	  Support: support@pizazzwp.com
	 */

	if ( ! defined( 'ABSPATH' ) ) {
		echo '+++Divide By Cucumber Error. Please Reinstall Universe And Reboot +++. R.I.P Terry Pratchett 1948-2015';
		exit;
	} // Exit if accessed directly

	/**
	 * REMEMBER TO UPDATE VERSION IN arc-admin.scss
	 */
	define( 'PZARC_VERSION', '1.9.9' );

	if ( defined( 'PZARC_DEBUG' ) && PZARC_DEBUG ) {
		global $pzstart_time;
		$pzstart_time = microtime( TRUE );
		pzdb( 'start' );
	}

	// include_once 'load-freemius.php';

	require_once('class_pzarchitect.php');
	$pzarc = new pzArchitect();


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


	pzdb( 'bottom' );

