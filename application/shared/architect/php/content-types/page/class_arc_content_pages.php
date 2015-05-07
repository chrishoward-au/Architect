<?php

  /**
   * Project pizazzwp-architect.
   * File: class_arc_content_posts.php
   * User: chrishoward
   * Date: 11/10/14
   * Time: 10:19 PM
   */
  // Add content info to the registry
  class arc_content_pages extends arc_set_data// Singleton
  {

    protected function __construct()
    {
      $registry = arc_Registry::getInstance();
      $prefix   = '_content_pages_';

      $settings[ 'blueprint-content' ] = array(
          'type'        => 'page',
          'name'        => 'Pages',
          'panel_class' => 'arc_panel_Pages',
          'prefix'      => $prefix,
          // These are the sections to display on the admin metabox. We also use them to get default values.
          'sections'    => array(
              'title'      => 'Pages',
              'icon_class' => 'icon-large',
              'icon'       => 'el-icon-align-justify',
              'fields'     => array(
                  array(
                      'title'   => __('Specific pages', 'pzarchitect'),
                      'id'      => $prefix . 'specific-pages',
                      'type'    => 'select',
                      'args' => array('posts_per_page'=>'-1'),
                      'select2' => array('allowClear' => true),
                      'data'    => 'pages',
                      'multi'   => true
                  ),
                  array(
                    'title'   => __('Exclude pages', 'pzarchitect'),
                    'id'      => $prefix . 'exclude-pages',
                    'type'    => 'select',
                    'select2' => array('allowClear' => true),
                    'args' => array('posts_per_page'=>'-1'),
                    'data'    => 'pages',
                    'multi'   => true
                  ),
                  array(
                    'title'    => __( 'Exclude current page', 'pzarchitect' ),
                    'id'       => $prefix . 'exclude-current-page',
                    'type'     => 'switch',
                    'on'       => __( 'Yes', 'pzarchitect' ),
                    'off'      => __( 'No', 'pzarchitect' ),
                    'default'  => false,
                    'subtitle' => __('If this Blueprint is displayed on a page, exclude that page from the Blueprint display','pzarchitect')
                  ),
              )
          )
      );

      // This has to be post_type
      $registry->set('post_types', $settings);
      $registry->set('content_source',array('page'=>plugin_dir_path(__FILE__)));
    }
  }

//  //todo:set this up as a proper singleton?
  $content_posts = arc_content_pages::getInstance('arc_content_pages');



