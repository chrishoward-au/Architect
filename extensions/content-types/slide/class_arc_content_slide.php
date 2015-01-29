<?php

  /**
   * Project pizazzwp-architect.
   * File: class_arc_content_posts.php
   * User: chrishoward
   * Date: 11/10/14
   * Time: 10:19 PM
   */
  // Add content info to the registry
  class arc_content_slide  extends arc_set_data// Singleton
  {


    protected function __construct()
    {
      $registry = arc_Registry::getInstance();

      $prefix = '_content_slides_';

      /** Slides */
      $slides_obj = get_posts(array('post_type' => 'pzsp-slides'));
      //var_dump($slides_obj);
      $slides = array();
      foreach ($slides_obj as $key => $value) {
        $slides[ $value->ID ] = $value->post_title;
      }

      $settings[ 'blueprint-content' ] = array(
          'type'        => 'slide',
          'name'        => 'Slides',
          'panel_class' => 'arc_panel_Slides',
          'prefix'      => $prefix,
          // These are the sections to display on the admin metabox. We also use them to get default values.
          'sections'    => array(
              'title'      => 'Slides',
              'icon_class' => 'icon-large',
              'icon'       => 'el-icon-video',
              'fields'     => array(
                  array(
                      'title'   => __('Specific slides', 'pzarchitect'),
                      'id'      => $prefix . 'specific-slides',
                      'type'    => 'select',
                      'select2' => array('allowClear' => true),
                      'multi'   => true,
                      'options' => $slides
                  ),
              )
          )
      );

      // This has to be post_type
      $registry->set('post_types', $settings);
      $registry->set('content_source',array('slide'=>plugin_dir_path(__FILE__)));
    }
  }

//  //todo:set this up as a proper singleton?
  $content_posts = arc_content_slide::getInstance('arc_content_slide');



