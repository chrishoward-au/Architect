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
      $settings[ 'blueprint-content' ] = array(
          'type'        => 'slide',
          'name'        => 'SliderPlus Slides',
          'panel_class' => 'arc_panel_Slides',
          'prefix'      => $prefix,
          // These are the sections to display on the admin metabox. We also use them to get default values.
          'sections'    => array(
              'title'      => 'SliderPlus Slides',
              'icon_class' => 'icon-large',
              'icon'       => 'el-icon-video',
              'fields'     => array(
                  array(
                      'title'   => __('Specific slides', 'pzarchitect'),
                      'id'      => $prefix . 'specific-slides',
                      'type'    => 'select',
                      'select2' => array('allowClear' => true),
                      'multi'   => true,
                      'data'=>'callback',
                      'args'=>array('pzarc_get_slides')

                  ),
              )
          )
      );

      // This has to be post_type
      $registry->set('post_types', $settings);
      $registry->set('content_source',array('slide'=>plugin_dir_path(__FILE__)));
      $registry->set( 'content_info',
                      array(
                        'slide' => array(
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
  $content_posts = arc_content_slide::getInstance('arc_content_slide');


function pzarc_get_slides(){
  $args                 = array(
      'posts_per_page'   => -1,
      'orderby'          => 'post_title',
      'order'            => 'ASC',
      'post_type'        => 'pzsp-slides',
      'post_status'      => 'publish',
      'suppress_filters' => true
  );
  $slides_obj = get_posts($args);
  //var_dump($slides_obj);
  $slides = array();
  foreach ($slides_obj as $key => $value) {
    $slides[ $value->ID ] = $value->post_title;
  }
  return $slides;
}
