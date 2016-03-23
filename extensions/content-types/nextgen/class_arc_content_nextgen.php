<?php

  /**
   * Project pizazzwp-architect.
   * File: class_arc_content_nextgen.php
   * User: chrishoward
   * Date: 11/10/14
   * Time: 10:19 PM
   */
  // Add content info to the registry
  class arc_content_nextgen  extends arc_set_data// Singleton
  {


    protected function __construct()
    {
      $registry = arc_Registry::getInstance();
      $prefix   = '_content_nextgen_';

      $settings[ 'blueprint-content' ] = array(
          'type'        => 'nextgen',
          'name'        => 'NextGen Galleries',
          'panel_class' => 'arc_panel_Nextgen',
          'prefix'      => $prefix,
          // These are the sections to display on the admin metabox. We also use them to get default values.
          'sections'    => array(
              'title'      => 'NextGen Galleries',
              'icon_class' => 'icon-large',
              'icon'       => 'el-icon-picture',
              'fields'     => array(
                  array(
                      'title'    => __('NextGen Gallery', 'pzarchitect'),
                      'id'       => $prefix . 'nggallery',
                      'type'     => 'select',
                      'data'     => 'callback',
                      'args'     => array('pzarc_get_ngg_galleries'),
                      'subtitle' => (class_exists('P_Photocrati_NextGen') ? 'Enter NGG gallery name to use'
                          : 'NextGen is not running on this site'),
                  ),
              )
          )
      );

      // This has to be post_type
      $registry->set('post_types', $settings);
      $registry->set('content_source',array('nextgen'=>plugin_dir_path(__FILE__)));
      $registry->set( 'content_info',
                      array(
                        'nextgen' => array(
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
  $content_posts = arc_content_nextgen::getInstance('arc_content_nextgen');


  function pzarc_get_ngg_galleries()
  {
    if (!class_exists('P_Photocrati_NextGen')) {
      return null;
    }
    global $ngg, $nggdb;
    $results = array();


    $ng_galleries = $nggdb->find_all_galleries('gid', 'asc', true, 0, 0, false);

    if ($ng_galleries) {
      foreach ($ng_galleries as $gallery) {
        $results[ $gallery->gid ] = $gallery->title;
      }
    }

    return $results;
  }

  function pzarc_get_ngg_gallery($gid,$block) {
    
//var_dump($gid,$block);
    if (method_exists('HeadwayBlocksData', 'get_legacy_id')) {
      $block[ 'id' ] = HeadwayBlocksData::get_legacy_id($block);
    }
    $settings = GalleryPBlockOptions::get_settings($block);

    $results = array();
    $inc = 0;
    $images  = nggdb::get_gallery( $gid);


    foreach ($images as $image) {

      $results[$inc] = array('post_id' =>$image->gid,
                             'title'=>stripslashes($image->alttext),
                             'caption'=>stripslashes($image->description),
                             'thumb_url'=>$image->thumbURL,
                             'image_url'=>$image->imageURL,
                             'link_url'=>'');
      $inc++;


    };

    $results = pzgp_limit_images($results,$settings['gp-limit-images']);


    return $results;

  }
