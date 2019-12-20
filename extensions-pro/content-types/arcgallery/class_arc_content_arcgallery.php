<?php

  /**
   * Project pizazzwp-architect.
   * File: class_arc_content_arcgallery.php
   * User: chrishoward
   * Date: 11/10/14
   * Time: 10:19 PM
   */
  // Add content info to the registry
  // This manages all the other files required

  class arc_content_arcgallery extends arc_set_data {

    protected function __construct() {

      add_filter( 'arc-show-content-types', array( $this, 'add_show_arcgallery' ) );
      // This has to be post_type
      $registry = arc_Registry::getInstance();
      $settings = $registry->get( 'post_types' );

      $prefix = '_content_galleries_';

      $settings[ $prefix ]['blueprint-content']['sections']['fields'][] = array(
        'title'    => __( 'Architect gallery', 'pzarchitect' ),
        'id'       => '_content_arcgallery_specific-arcgallery',
        'type'     => 'select',
        'select2'  => array( 'allowClear' => TRUE ),
        'data'     => 'callback',
        'args'     => array( 'pzarc_get_arcgalleries' ),
        'multi'    => FALSE,
        'required' => array( $prefix . 'gallery-source', 'equals', 'arcgallery' ),
      );

      $settings[ $prefix ]['blueprint-content']['sections']['fields'][0]['options']['arcgallery'] = __( 'Architect Galleries', 'pzarchitect' );

      $registry->set( 'post_types', $settings[ $prefix ], $prefix );
      add_action( "redux/metaboxes/_architect/boxes", array( $this, "add_arcgallery_metaboxes" ), 10, 1 );
    }


    private function __clone() {
    }

    private function __wakeup() {
    }


    function add_show_arcgallery( $arc_array ) {
      $arc_array['options']['pz_arcgallery'] = __( 'Architect Galleries', 'pzarchitect' );
      $arc_array['default']['pz_arcgallery'] = 0;

      return $arc_array;

    }

    function add_arcgallery_metaboxes( $metaboxes ) {
      // Declare your sections
      global $_architect_options;
      $boxSections   = array();
      $boxSections[] = array(
        //'title'         => __('General Settings', 'pzarchitect'),
        //'icon'          => 'el-icon-home', // Only used with metabox position normal or advanced
        'fields' => array(
          array(
            'id'    => 'pzarc_arcgallery-gallery',
            'title' => __( 'Gallery images', 'pzarchitect' ),
            'type'  => 'gallery',
          ),
        ),
      );

      // Declare your metaboxes
      $metaboxes[] = array(
        'id'         => 'pzarc_mb-arcgallery',
        'title'      => __( 'Add a Gallery', 'pzarchitect' ),
        'post_types' => array( 'pz_arcgallery' ),
        'position'   => 'normal', // normal, advanced, side
        'priority'   => 'high', // high, core, default, low - Priorities of placement
        'sections'   => $boxSections,
        'sidebar'    => FALSE,
      );

      return $metaboxes;
    }
  } // EOC

//  //todo:set this up as a proper singleton?
  $content_posts = arc_content_arcgallery::getInstance( 'arc_content_arcgallery' );


  /**
   * This is explicit as it's used in a callback which doesn't allow parameters
   *
   * @return array|null
   */
  function pzarc_get_arcgalleries() {
    $post_types = get_post_types();
    if ( ! isset( $post_types['pz_arcgallery'] ) ) {
      return NULL;
    }
    $args    = array(
      'post_type'   => 'pz_arcgallery',
      'numberposts' => - 1,
      'post_status' => NULL,
      'post_parent' => NULL,
    );
    $albums  = get_posts( $args );
    $results = array();
    if ( $albums ) {
      foreach ( $albums as $post ) {
        setup_postdata( $post );
        $results[ $post->ID ] = get_the_title( $post->ID );
      }
    }

    return $results;
  }

