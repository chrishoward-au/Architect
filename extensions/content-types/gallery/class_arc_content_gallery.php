<?php

  /**
   * Project pizazzwp-architect.
   * File: class_arc_content_posts.php
   * User: chrishoward
   * Date: 11/10/14
   * Time: 10:19 PM
   */
  // Add content info to the registry
  class arc_content_galleries  extends arc_set_data// Singleton
  {


    protected function __construct()
    {
      $registry = arc_Registry::getInstance();
      $prefix   = '_content_galleries_';

      $settings[ 'blueprint-content' ] = array(
          'type'        => 'gallery',
          'name'        => 'Galleries',
          'panel_class' => 'arc_panel_Galleries',
          'prefix'      => $prefix,
          // These are the sections to display on the admin metabox. We also use them to get default values.
          'sections'    => array(
              'title'      => 'Galleries',
              'icon_class' => 'icon-large',
              'icon'       => 'el-icon-picture',
              'desc'       => __('Did you know, you can set any Blueprint with Galleries as the content source to override the layout of the WordPress gallery shortcode? Look in Architect Options.', 'pzarchitect'),
              'fields'     => array(
                  array(
                      'title'   => __('Gallery source', 'pzarchitect'),
                      'id'      => $prefix . 'gallery-source',
                      'type'    => 'button_set',
                      'default' => 'images',
                      'hint'    => array('content' => __('Can be overriden by shortcode e.g:<br>[architect blueprint=&quot;mytemplate&quot; ids=&quot;1,2,3,4,5&quot;]', 'pzarchitect')),
                      'options' => array(
                          'images'      => 'Image Gallery',
                          'ids'         => 'Specific IDs',
                          'wpgallery'   => 'WP Galleries',
                          // TODO: Get post images as gallery source working
//                          'postimages'  => 'Post images',
                          'galleryplus' => 'GalleryPlus',
                      )
                  ),
                  array(
                      'title'    => __('Image Gallery', 'pzarchitect'),
                      'id'       => $prefix . 'specific-images',
                      'type'     => 'gallery',
                      'required' => array($prefix . 'gallery-source', 'equals', 'images'),
                  ),
                  array(
                      'title'    => __('Specific IDs', 'pzarchitect'),
                      'id'       => $prefix . 'specific-ids',
                      'type'     => 'text',
                      'subtitle' => 'Enter a comma separated list of image ids',
                      'required' => array($prefix . 'gallery-source', 'equals', 'ids')
                  ),
                  array(
                      'title'    => __('WP Gallery', 'pzarchitect'),
                      'id'       => $prefix . 'wp-post-gallery',
                      'type'     => 'select',
                      'data'     => 'callback',
                      'args'     => array('pzarc_get_wp_galleries'),
                      'subtitle' => 'Select a post with a gallery',
                      'required' => array($prefix . 'gallery-source', 'equals', 'wpgallery')
                  ),
                  // TODO: Get post images as gallery source working
//                  array(
//                      'title'    => __('Post images', 'pzarchitect'),
//                      'id'       => $prefix . 'wp-post-images',
//                      'type'     => 'select',
//                      'data'     => 'callback',
//                      'args'     => array('pzarc_get_wp_post_images'),
//                      'subtitle' => 'Select a post with images',
//                      'required' => array($prefix . 'gallery-source', 'equals', 'postimages')
//                  ),
                  array(
                      'title'    => __('GalleryPlus', 'pzarchitect'),
                      'id'       => $prefix . 'galleryplus',
                      'type'     => 'select',
                      'data'     => 'callback',
                      'args'     => array('pzarc_get_gp_galleries'),
                      'subtitle' => 'Select a GalleryPlus gallery',
                      'required' => array($prefix . 'gallery-source', 'equals', 'galleryplus')
                  ),
              )
          )
      );

      // This has to be post_type
      $registry->set('post_types', $settings);
      $registry->set('content_source',array('gallery'=>plugin_dir_path(__FILE__)));
    }
  }

//  //todo:set this up as a proper singleton?
  $content_posts = arc_content_galleries::getInstance();


