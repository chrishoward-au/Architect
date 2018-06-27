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

  /**
   * Designer constants
   */

  define( '_amb_layouts_help', 999 );
  define( '_amb_tabular', 1000 );
  define( '_amb_styling_tabular', 1050 );
  define( '_amb_accordion', 1100 );
  define( '_amb_styling_accordion', 1150 );
  define( '_amb_slidertabbed', 1200 );
  define( '_amb_styling_slidertabbed', 1250 );
  define( '_amb_masonry', 1300 );
  define( '_amb_styling_masonry', 1350 );
  define( '_amb_pagination', 1400 );
  define( '_amb_section', 1500 );
  define( '_amb_section1', 1501 );
  define( '_amb_section2', 1502 );
  define( '_amb_section3', 1503 );

  // Where's section styling?

  define( '_amb_bp_custom_css', 1600 ); // 0s are mains
  define( '_amb_blueprint_help', 1799 ); // 99s are help
  define( '_amb_styling_sections_wrapper', 1850 ); // 50s are styling
  define( '_amb_styling_page', 1950 );
  define( '_amb_styling_general', 2050 );

  define( '_amb_titles', 2100 );
  define( '_amb_titles_help', 2199 );
  define( '_amb_styling_titles', 2150 );
  define( '_amb_titles_responsive', 2130 );

  define( '_amb_body_excerpt', 2200 );
  define( '_amb_body_excerpt_help', 2299 );
  define( '_amb_styling_body', 2250 );
  define( '_amb_styling_excerpt', 2350 );
  define( '_amb_body_excerpt_responsive', 2400 );

  define( '_amb_customfields', 2500 );
  define( '_amb_customfields_help', 2599 );
  define( '_amb_styling_customfields', 2550 );
  for ( $i = 1; $i <= 40; $i ++ ) {
    define( '_amb_customfields' . $i, 2500 + $i );
    define( '_amb_styling_customfields' . $i, 2550 + $i );
  }

  define( '_amb_features', 2600 );
  define( '_amb_features_help', 2699 );
  define( '_amb_styling_features', 2650 );

  define( '_amb_general', 2700 );

  define( '_amb_meta', 2800 );
  define( '_amb_meta_help', 2899 );
  define( '_amb_styling_meta', 2850 );

  define( '_amb_panels', 2900 );
  define( '_amb_styling_panels', 2950 );
  define( '_amb_panels_help', 2999 );
  define( '_amb_panels_css', 3000 );


  define( '_amb_sources', 3100 );
  define( '_amb_sources_settings', 3110 );
  define( '_amb_sources_filters', 3120 );
  define( '_amb_sources_custom_sorting', 3130 );
  define( '_amb_sources_help', 3199 );

  define( '_amb_tabs', 3200 );
