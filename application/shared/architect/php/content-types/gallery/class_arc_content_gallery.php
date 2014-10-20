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
      $registry = Registry::getInstance();
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
                      'hint'    => array('content' => __('Can be overriden by shortcode e.g. [pzarc blueprint="mytemplate" ids="1,2,3,4,5"]', 'pzarchitect')),
                      'options' => array(
                          'images'      => 'Image Gallery',
                          'ids'         => 'Specific IDs',
                          'wpgallery'   => 'WP Galleries',
                          'postimages'  => 'Post images',
                          'galleryplus' => 'GalleryPlus',
                          'nggallery'   => 'NextGen',
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
                  array(
                      'title'    => __('Post iamges', 'pzarchitect'),
                      'id'       => $prefix . 'wp-post-images',
                      'type'     => 'select',
                      'data'     => 'callback',
                      'args'     => array('pzarc_get_wp_galleries'),
                      'subtitle' => 'Select a post with images',
                      'required' => array($prefix . 'gallery-source', 'equals', 'wpgallery')
                  ),
                  array(
                      'title'    => __('GalleryPlus', 'pzarchitect'),
                      'id'       => $prefix . 'galleryplus',
                      'type'     => 'select',
                      'data'     => 'callback',
                      'args'     => array('pzarc_get_gp_galleries'),
                      'subtitle' => 'Select a GalleryPlus gallery',
                      'required' => array($prefix . 'gallery-source', 'equals', 'galleryplus')
                  ),
                  array(
                      'title'    => __('NextGen Gallery', 'pzarchitect'),
                      'id'       => $prefix . 'nggallery',
                      'type'     => 'select',
                      'data'     => 'callback',
                      'args'     => array('pzarc_get_ng_galleries'),
                      'subtitle' => (class_exists('P_Photocrati_NextGen') ? 'Enter NGG gallery name to use'
                          : 'NextGen is not running on this site'),
                      'required' => array($prefix . 'gallery-source', 'equals', 'nggallery')
                  ),
                  array(
                      'title'    => __('Click behaviour', 'pzarchitect'),
                      'id'       => $prefix . 'click-behavioury',
                      'type'     => 'switch',
                      'default'  => true,
                      'subtitle' => __('Open image in lightbox when clicked', 'pzarchitect')
                  )
              )
          )
      );

      // This has to be post_type
      $registry->set('post_types', $settings);
    }
  }

//  //todo:set this up as a proper singleton?
  $content_posts = arc_content_galleries::getInstance();


