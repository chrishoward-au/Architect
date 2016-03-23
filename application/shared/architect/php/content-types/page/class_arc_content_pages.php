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
                      'multi'   => true,
                      'sortable'=>true,
                      'subtitle'=>__('Select then drag and drop to order. Be sure to set Order By in Settings to Specified.','pzarchitect'),
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
      $registry->set( 'content_info',
                      array(
                        'pages' => array(
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
  $content_posts = arc_content_pages::getInstance('arc_content_pages');



