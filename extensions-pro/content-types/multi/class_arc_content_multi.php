<?php

  /**
   * Project pizazzwp-architect.
   * File: class_arc_content_posts.php
   * User: chrishoward
   * Date: 11/10/14
   * Time: 10:19 PM
   */
  // Add content info to the registry
  class arc_content_multi extends arc_set_data {

     function __construct() {
      $registry = arc_Registry::getInstance();
      $prefix   = '_content_multi_';

      global $_architect_options;
      if ( empty( $_architect_options ) ) {
        $_architect_options = get_option( '_architect_options' );
      }

      $settings[ 'blueprint-content' ] = array(
        'type'        => 'multi',
        'name'        => 'Multiple',
        'panel_class' => 'arc_panel_multi',
        'prefix'      => $prefix,
        // These are the sections to display on the admin metabox. We also use them to get default values.
        'sections'    => array(
          'title'      => 'Multiple post type settings',
          'icon_class' => 'icon-large',
          'icon'       => 'el-icon-list',
          'fields'     => array(
            array(
              'title'   => __( 'Select the post field_types to display', 'pzarchitect' ),
              'id'      => $prefix . 'post-field_types',
              'type'    => 'select',
              'data'    => 'callback',
              'multi'   => true,
              'default' => array(),
              'args'    => array( 'pzarc_get_post_types' ),
            ),
            array(
              'title'    => __( 'Specific posts', 'pzarchitect' ),
              'id'       => $prefix . 'specific-IDs',
              'type'     => 'text',
              'subtitle' => __( 'Comma separated list of IDs', 'pzarchitect' ),
            ),
          )
        )
      );
      // This has to be post_types
      $registry->set( 'post_types', $settings );
      $registry->set( 'content_source', array( 'multi' => plugin_dir_path( __FILE__ ) ) );

    }

  }

//  //todo:set this up as a proper singleton?
  new arc_content_multi( );


