<?php

  /**
   * Project pizazzwp-architect.
   * File: class_arc_content_posts.php
   * User: chrishoward
   * Date: 11/10/14
   * Time: 10:19 PM
   */
  // Add content info to the registry
  class arc_content_snippets  extends arc_set_data// Singleton
  {

    protected function __construct()
    {

      $registry = arc_Registry::getInstance();
      $prefix   = '_content_snippets_';
      $settings[ 'blueprint-content' ] = array(
          'type'        => 'snippets',
          'name'        => 'Snippets',
          'panel_class' => 'arc_panel_Snippets',
          'prefix'      => $prefix,
          // These are the sections to display on the admin metabox. We also use them to get default values.
          'sections'    => array(
              'title'      => 'Snippets',
              'icon_class' => 'icon-large',
              'icon'       => 'el-icon-file',
              'fields'     => array(
                  array(
                      'title'   => __('Specific snippets', 'pzarchitect'),
                      'id'      => $prefix . 'specific-snippets',
                      'type'    => 'select',
                      'select2' => array('allowClear' => true),
                      'options' => pzarc_get_posts_in_post_type('pz_snippets','id'),
                      'multi'   => true,
                      'default' => array()
                  ),
              )
          )
      );

      // This has to be post_type
      $registry->set('post_types', $settings);
      $registry->set('content_source',array('snippets'=>plugin_dir_path(__FILE__)));
    }

    private function __clone()
    {
    }

    private function __wakeup()
    {
    }
  }

//  //todo:set this up as a proper singleton?
  $content_posts = arc_content_snippets::getInstance('arc_content_snippets');



