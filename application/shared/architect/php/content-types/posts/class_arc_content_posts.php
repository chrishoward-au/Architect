<?php

  /**
   * Project pizazzwp-architect.
   * File: class_arc_content_posts.php
   * User: chrishoward
   * Date: 11/10/14
   * Time: 10:19 PM
   */
  // Add content info to the registry
  class arc_content_posts extends arc_set_data// Singleton
  {

    protected function __construct()
    {
      $registry = Registry::getInstance();

      $prefix = '_content_posts_';

      $settings[ 'blueprint-content' ] = array(
          'type'        => 'post',
          'name'        => 'Posts',
          'panel_class' => 'arc_panel_Posts',
          'prefix'      => $prefix,
          // These are the sections to display on the admin metabox. We also use them to get default values.
          'sections'    => array(
              'title'      => 'Posts',
              'icon_class' => 'icon-large',
              'icon'       => 'el-icon-list',
              'fields'     => array(
                  array(
                      'title'   => __('Specific posts', 'pzarchitect'),
                      'id'      => $prefix . 'specific-posts',
                      'type'    => 'select',
                      'select2' => array('allowClear' => true),
                      'data'    => 'posts',
                      'multi'   => true
                  ),
              )
          )
      );

      // This has to be post_types
      $registry->set('post_types', $settings);
    }

  }

//  //todo:set this up as a proper singleton?
  $content_posts = arc_content_posts::getInstance();



