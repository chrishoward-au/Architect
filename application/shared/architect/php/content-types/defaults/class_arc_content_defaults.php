<?php

  /**
   * Project pizazzwp-architect.
   * File: class_arc_content_posts.php
   * User: chrishoward
   * Date: 11/10/14
   * Time: 10:19 PM
   */
  // Add content info to the registry
  class arc_content_defaults  extends arc_set_data// Singleton
  {


    protected function __construct()
    {
      $registry = arc_Registry::getInstance();

      $prefix = '_content_defaults_';

      $settings[ 'blueprint-content' ] = array(
          'type'        => 'defaults',
          'name'        => 'Defaults',
          'panel_class' => 'arc_panel_Who_Knows',
          'prefix'      => $prefix,
          // These are the sections to display on the admin metabox. We also use them to get default values.
          'sections'    => array(
              'title'      => 'Default content',
              'icon_class' => 'icon-large',
              'icon'       => 'el-icon-check',
              'fields'     => array(
                  array(
                      'title'    => __('Default Content', 'pzarchitect'),
                      'id'       => $prefix . 'defaults-heading',
                      'type'     => 'info',
                      'style'    => 'success',
                      'subtitle' => 'When Default is selected, Architect will use whatever the default content for the page. e.g. the home page, category archives, search results etc'
                  ),
                  array(
                      'title'    => __('Override WordPress default settings and filters', 'pzarchitect'),
                      'id'       => $prefix . 'defaults-override',
                      'type'     => 'switch',
                      'on'=>'Yes',
                      'off'=>'No',
                      'default'=>false,
                      'desc' => __('Defaults uses all WordPress default selection conditions. Enable overrides if you want the options in Settings and Filters to be applied.','pzarchitect')
                  )
              )
          )
      );


      // This has to be post_type
      $registry->set('post_types', $settings);
      $registry->set('content_source',array('defaults'=>plugin_dir_path(__FILE__)));
      $registry->set( 'content_info',
                      array(
                        'defaults' => array(
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
  $content_posts = arc_content_defaults::getInstance('arc_content_defaults');



