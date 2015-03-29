<?php

  /**
   * Project pizazzwp-architect.
   * File: class_arc_content_posts.php
   * User: chrishoward
   * Date: 11/10/14
   * Time: 10:19 PM
   */
  // Add content info to the registry
  class arc_content_dummy extends arc_set_data// Singleton
  {

    protected function __construct()
    {
      $registry = arc_Registry::getInstance();

      $prefix = '_content_dummy_';

      $settings[ 'blueprint-content' ] = array(
          'type'        => 'dummy',
          'name'        => 'Dummy',
          'panel_class' => 'arc_panel_Dummy',
          'prefix'      => $prefix,
          // These are the sections to display on the admin metabox. We also use them to get default values.
          'sections'    => array(
              'title'      => 'Dummy content',
              'icon_class' => 'icon-large',
              'icon'       => 'el-icon-asterisk',
              'fields'     => array(
                  array(
                      'title'    => __('Dummy Content', 'pzarchitect'),
                      'id'       => $prefix . 'dummy-heading',
                      'type'     => 'info',
                      'style'    => 'normal',
                      'subtitle' => __('The dummy content is automagically generated for you to help plan and test design when the site has no content.', 'pzarchitect')
                  ),
                  array(
                      'title'    => __('Number of dummy records', 'pzarchitect'),
                      'id'       => $prefix . 'dummy-record-count',
                      'type'     => 'spinner',
                      'default'  => 12,
                      'step'     => 1,
                      'min'      => 1,
                      'max'      => 99,
                      'subtitle' => __('Number of dummy records to simulate', 'pzarchitect'),
                  ),
                  array(
                      'title'   => __('Dummy image type', 'pzarchitect'),
                      'id'      => $prefix . 'image-source',
                      'type'    => 'button_set',
                      'default' => 'lorempixel',
                      //                      'subtitle'=>__('Only LoremPixel and PlaceImg give random images per post.','pzarchitect'),
                      'options' => array(
                          'lorempixel'            => 'LoremPixel',
                          'dummyimage'            => 'Place holder',
//                          'placeimg'              => 'Random',
//                          'placeimg-animals'      => 'Animals',
//                          'placeimg-architecture' => 'Architecture',
//                          'placeimg-nature'       => 'Nature',
//                          'placeimg-people'       => 'People',
//                          'placeimg-tech'         => 'Tech',
                      )
                  ),
                  array(
                      'id'       => $prefix.'text-colour',
                      'type'     => 'color',
                      'title'    => __('Text colour', 'pzarchitect'),
                      'default'  => '#fff',
                      'transparent'=>false,
                      'validate' => 'color',
                      'required'=>array($prefix . 'image-source','equals','dummyimage'),
                  ),
                  array(
                      'id'       => $prefix.'bg-colour',
                      'type'     => 'color',
                      'title'    => __('Background Colour', 'pzarchitect'),
                      'default'  => '#bbb',
                      'transparent'=>false,
                      'validate' => 'color',
                      'required'=>array($prefix . 'image-source','equals','dummyimage'),
                  ),
              )
          )
      );

      // This has to be post_types
      $registry->set('post_types', $settings);
      $registry->set('content_source', array('dummy' => plugin_dir_path(__FILE__)));
    }

  }

//  //todo:set this up as a proper singleton?
  $content_posts = arc_content_dummy::getInstance('arc_content_dummy');



