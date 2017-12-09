<?php
  /**
   * Project pizazzwp-architect.
   * File: arc-constants.php
   * User: chrishoward
   * Date: 18/04/2016
   * Time: 4:18 PM
   */

  if ( ! defined( 'PZARC_TESTER' ) ) {
    $arc_options = get_option( '_architect_options', array() );
    define( 'PZARC_TESTER', ( isset( $arc_options['architect_enable_beta'] ) ? $arc_options['architect_enable_beta'] : FALSE ) );
  }

  define( 'PZARC_NAME', 'pzarchitect' ); // This is also same as the locale
  define( 'PZARC_FOLDER', '/pizazzwp-architect' );
  define( 'PZARC_CODEX', 'http://architect4wp.com/codex-listings' );

  define( 'PZARC_BETA', FALSE );

  define( 'PZARC_PLUGIN_APP_URL', PZARC_PLUGIN_URL . 'application/' );
  define( 'PZARC_PLUGIN_APP_PATH', PZARC_PLUGIN_PATH . 'application/' );
  define( 'PZARC_DOCUMENTATION_URL', PZARC_PLUGIN_URL . 'documentation/' );
  define( 'PZARC_DOCUMENTATION_PATH', PZARC_PLUGIN_PATH . 'documentation/' );
  define( 'PZARC_PLUGIN_ASSETS_URL', PZARC_PLUGIN_APP_URL . 'shared/assets/' );
  define( 'PZARC_PLUGIN_PRESETS_URL', PZARC_PLUGIN_URL . 'presets/' );

  $upload_dir = wp_upload_dir();
  if ( substr( home_url(), 0, 5 ) === 'https' && substr( $upload_dir['baseurl'], 0, 5 ) === 'http:' ) {
    $upload_dir['baseurl'] = str_replace( 'http:', 'https:', $upload_dir['baseurl'] );
  }
  define( 'PZARC_UPLOADS_BASEPATH', $upload_dir['basedir'] );
  define( 'PZARC_UPLOADS_BASEURL', $upload_dir['baseurl'] );

  define( 'PZARC_PRESETS_PATH', PZARC_UPLOADS_BASEPATH . '/pizazzwp/architect/presets/' );
  define( 'PZARC_PRESETS_URL', PZARC_UPLOADS_BASEURL . '/pizazzwp/architect/presets/' );

  // TODO: Need to add support for WP Offload S3

  $pzarc_use_s3 = FALSE;

  if ( $pzarc_use_s3 ) {
    // Insert code

  } else {
    define( 'PZARC_CACHE', '/arc/' );
    define( 'PZARC_CACHE_URL', PZARC_UPLOADS_BASEURL . '/cache/pizazzwp/arc/' );
    define( 'PZARC_CACHE_PATH', PZARC_UPLOADS_BASEPATH . '/cache/pizazzwp/arc/' );
  }

  // TODO: Setup an option for changing the language
  $language = substr( get_locale(), 0, 2 );

  define( 'PZARC_LANGUAGE', 'en' );

  define( 'PZARC_TRANSIENTS_KEEP', 12 * HOUR_IN_SECONDS );
