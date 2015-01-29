<?php

  /**
   * Project pizazzwp-architect.
   * File: class_arc_content_posts.php
   * User: chrishoward
   * Date: 11/10/14
   * Time: 10:19 PM
   */
  // Add content info to the registry
  class arc_content_cpt extends arc_set_data// Singleton
  {

    protected function __construct()
    {
      $registry = arc_Registry::getInstance();
      $prefix   = '_content_cpt_';

      $settings[ 'blueprint-content' ] = array(
          'type'        => 'cpt',
          'name'        => 'Custom Post Types',
          'panel_class' => 'arc_panel_CPT',
          'prefix'      => $prefix,
          // These are the sections to display on the admin metabox. We also use them to get default values.
          'sections'    => array(
              'title'      => 'Custom Post Types',
              'icon_class' => 'icon-large',
              'icon'       => 'el-icon-wrench',
              'fields'     => array(
                  array(
                      'title'   => __('Select custom post type', 'pzarchitect'),
                      'id'      => $prefix . 'custom-post-type',
                      'type'    => 'select',
                      'select2' => array('allowClear' => true),
                      //                'data' => 'post_types',
                      //'options' => $pzcustom_post_types
                      'data'    => 'callback',
                      //               'args'  => array('_builtin' => false,'public'=>true)
                      'args'    => array('pzarc_get_custom_post_types'),
                  ),
              )
          )
      );

      // This has to be post_type
      $registry->set('post_types', $settings);
      $registry->set('content_source',array('cpt'=>plugin_dir_path(__FILE__)));

    }
  }

//  //todo:set this up as a proper singleton?
  $content_cpt = arc_content_cpt::getInstance('arc_content_cpt');


