<?php

  /**
   * Project pizazzwp-architect.
   * File: class_arc_content_posts.php
   * User: chrishoward
   * Date: 11/10/14
   * Time: 10:19 PM
   */
  // Add content info to the registry
  class arc_content_dummy extends arc_set_data {

    protected function __construct() {
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
              'title'    => __( 'Dummy Content', 'pzarchitect' ),
              'id'       => $prefix . 'dummy-heading',
              'type'     => 'info',
              'style'    => 'normal',
              'subtitle' => __( 'The dummy content is automagically generated for you to help plan and test design when the site has no content.', 'pzarchitect' )
            ),
            array(
              'title'    => __( 'Number of dummy records', 'pzarchitect' ),
              'id'       => $prefix . 'dummy-record-count',
              'type'     => 'spinner',
              'default'  => 12,
              'step'     => 1,
              'min'      => 1,
              'max'      => 99,
              'subtitle' => __( 'Number of dummy records to simulate', 'pzarchitect' ),
            ),
            array(
              'title'   => __( 'Dummy image type', 'pzarchitect' ),
              'id'      => $prefix . 'image-source',
              'type'    => 'select',
              'default' => 'lorempixel',
              //'subtitle'=>__('Only LoremPixel and PlaceImg give random images per post.','pzarchitect'),
              'options' => array(
                'lorempixel' => __('Random Picture','pzarchitect'),
                'dummyimage' => __('Place holder','pzarchitect'),
                'specific'=>__('Custom specific image','pzarchitect'),
                'abstract'   => ucfirst( 'abstract' ),
                'animals'    => ucfirst( 'animals' ),
                'business'   => ucfirst( 'business' ),
                'cats'           => ucfirst( 'cats' ),
                'city'           => ucfirst( 'city' ),
                'food'           => ucfirst( 'food' ),
                'nightlife'           => ucfirst( 'nightlife' ),
                'fashion'           => ucfirst( 'fashion' ),
                'people'           => ucfirst( 'people' ),
                'nature'           => ucfirst( 'nature' ),
                'sports'           => ucfirst( 'sports' ),
                'technics'           => ucfirst( 'transport' ),
              )
            ),
            array(
                'id'             => $prefix . 'use-dummy-image-source-specific',
                'type'           => 'gallery',
                'title'          => __('Specific filler images', 'pzarchitect'),
                'subtitle'       => __('Selected images will be used for dummy posts in this Blueprint.', 'pzarchitect'),
                'required'       => array($prefix . 'image-source', '=', 'specific'),
                'library_filter' => array('jpg', 'jpeg', 'png')
            ),
            array(
              'title'   => __( 'Greyscale', 'pzarchitect' ),
              'id'      => $prefix . 'image-grey',
              'type'    => 'switch',
              'default' => false,
              'required'    => array( $prefix . 'image-source', '!=', 'dummyimage' ),
            ),
            array(
              'id'          => $prefix . 'text-colour',
              'type'        => 'color',
              'title'       => __( 'Text colour', 'pzarchitect' ),
              'default'     => '#fff',
              'transparent' => false,
              'validate'    => 'color',
              'required'    => array( $prefix . 'image-source', 'equals', 'dummyimage' ),
            ),
            array(
              'id'          => $prefix . 'bg-colour',
              'type'        => 'color',
              'title'       => __( 'Background Colour', 'pzarchitect' ),
              'default'     => '#bbb',
              'transparent' => false,
              'validate'    => 'color',
              'required'    => array( $prefix . 'image-source', 'equals', 'dummyimage' ),
            ),
          )
        )
      );

      // This has to be post_types
      $registry->set( 'post_types', $settings );
      $registry->set( 'content_source', array( 'dummy' => plugin_dir_path( __FILE__ ) ) );
      $registry->set( 'content_info',
                      array(
                        'dummy' => array(
                          'options' => array(
                            'titles'       => '<span class="pzarc-tab-title">' . __( 'Title', 'pzarchitect' ) . '</span>',
                            'meta'         => '<span class="pzarc-tab-title">' . __( 'Meta', 'pzarchitect' ) . '</span>',
                            'features'     => '<span class="pzarc-tab-title">' . __( 'Feature', 'pzarchitect' ) . '</span>',
                            'body'         => '<span class="pzarc-tab-title">' . __( 'Body/Excerpt', 'pzarchitect' ) . '</span>',
                            'customfields' => '<span class="pzarc-tab-title">' . __( 'Custom Fields', 'pzarchitect' ) . '</span>',
                          ),
                          'targets' =>
                            array(
                              'titles'       => array( 'titles-settings' ),
                              'meta'         => array( 'meta-settings' ),
                              'features'     => array( 'features-settings' ),
                              'body'         => array( 'body-settings' ),
                              'customfields' => array( 'customfields-settings' ),
                            )
                        )
                      )
      );
      // Load appropriate components
      require_once PZARC_PLUGIN_APP_PATH.'/shared/architect/php/components/arc_component_titles.php';
      require_once PZARC_PLUGIN_APP_PATH.'/shared/architect/php/components/arc_component_body.php';
      require_once PZARC_PLUGIN_APP_PATH.'/shared/architect/php/components/arc_component_meta.php';
      require_once PZARC_PLUGIN_APP_PATH.'/shared/architect/php/components/arc_component_feature.php';
      require_once PZARC_PLUGIN_APP_PATH.'/shared/architect/php/components/arc_component_customfields.php';
    }

  }

//  //todo:set this up as a proper singleton?
  $content_posts = arc_content_dummy::getInstance( 'arc_content_dummy' );



