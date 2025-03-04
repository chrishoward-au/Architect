<?php

  /**
   * Project pizazzwp-architect.
   * File: class_arc_content_showcases.php
   * User: chrishoward
   * Date: 11/10/14
   * Time: 10:19 PM
   */
  // Add content info to the registry
  // This manages all the other files required

  class arc_content_showcases extends arc_set_data {

     function __construct() {

      $registry                      = arc_Registry::getInstance();
      $prefix                        = '_content_showcases_';
      $settings['blueprint-content'] = array(
        'type'        => 'showcases',
        'name'        => 'Showcases',
        'panel_class' => 'arc_panel_Showcases',
        'prefix'      => $prefix,
        // These are the sections to display on the admin metabox. We also use them to get default values.
        'sections'    => array(
          'title'      => 'Showcases',
          'icon_class' => 'icon-large',
          'icon'       => 'el-icon-file',
          'fields'     => array(
            array(
              'title'   => __( 'Specific sites', 'pzarchitect' ),
              'id'      => $prefix . 'specific-showcases',
              'type'    => 'select',
              'select2' => array( 'allowClear' => TRUE ),
              'options' => pzarc_get_posts_in_post_type( 'pz_showcases', 'id-slug' ),
              'multi'   => TRUE,
              'default' => array(),
            ),
          ),
        ),
      );

      // This has to be post_type
      $registry->set( 'post_types', $settings );
      $registry->set( 'content_source', array( 'showcases' => plugin_dir_path( __FILE__ ) ) );

      add_action( "redux/metaboxes/_architect/boxes", array( $this, "pzarc_add_showcases_metaboxes" ), 10, 1 );

    }

    private function __clone() {
    }

//    private function __wakeup() {
//    }


    function pzarc_add_showcases_metaboxes( $metaboxes ) {
      // Declare your sections
      global $_architect_options;
      $boxSections   = array();
      $boxSections[] = array(
        //'title'         => __('General Settings', 'pzarchitect'),
        //'icon'          => 'el-icon-home', // Only used with metabox position normal or advanced
        'fields' => array(
          array(
            'id'       => 'pzarc_showcase-url',
            'title'    => __( 'Web address', 'pzarchitect' ),
            'type'     => 'text',
            'subtitle' => __( 'Please include http:// or https://', 'pzarchitect' ),
            'validate' => 'url',
          ),
          array(
            'id'       => 'pzarc_showcase-year',
            'title'    => __( 'Year of work', 'pzarchitect' ),
            'subtitle' => __( 'Enter the year the work was created', 'pzarchitect' ),
            'type'     => 'text',
          ),
        ),
      );

      // Declare your metaboxes
      $metaboxes[] = array(
        'id'         => 'pzarc_mb-showcase',
        'title'      => __( 'Item Information', 'pzarchitect' ),
        'post_types' => array( 'pz_showcases' ),
        'position'   => 'side', // normal, advanced, side
        'priority'   => 'default', // high, core, default, low - Priorities of placement
        'sections'   => $boxSections,
        'sidebar'    => FALSE,
      );

      return $metaboxes;
    }
  }

//  //todo:set this up as a proper singleton?
  new arc_content_showcases( 'arc_content_showcases' );



