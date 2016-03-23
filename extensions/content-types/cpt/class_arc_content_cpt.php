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
      $registry->set( 'content_info',
                      array(
                        'cpt' => array(
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
  $content_cpt = arc_content_cpt::getInstance('arc_content_cpt');


