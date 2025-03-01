<?php

  /**
   * Project pizazzwp-architect.
   * File: class_arc_content_posts.php
   * User: chrishoward
   * Date: 11/10/14
   * Time: 10:19 PM
   */

  // Add content info to the registry
  class arc_content_passed extends arc_set_data {


     function __construct() {
      $registry = arc_Registry::getInstance();
      $prefix   = '_content_galleries_';
      global $_architect_options;
      if ( empty( $_architect_options ) ) {
        $_architect_options = get_option( '_architect_options' );
      }
      $settings['blueprint-content'] = array(
        'type'        => 'gallery',
        'name'        => 'Galleries',
        'panel_class' => 'arc_panel_Galleries',
        'prefix'      => $prefix,
        // These are the sections to display on the admin metabox. We also use them to get default values.
        'sections'    => array(
          'title'      => 'Galleries',
          'icon_class' => 'icon-large',
          'icon'       => 'el-icon-picture',
          'desc'       => __( 'Did you know, you can set any Blueprint with Galleries as the content source to override the layout of the WordPress gallery shortcode? Look in Architect Options.', 'pzarchitect' ),
          'fields'     =>  array(
              array(
                'title'   => __( 'Gallery source', 'pzarchitect' ),
                'id'      => $prefix . 'gallery-source',
                'type'    => 'button_set',
                'default' => 'images',
                'hint'    => array( 'content' => __( 'Can be overriden by shortcode e.g:<br>[architect blueprint=&quot;mytemplate&quot; ids=&quot;1,2,3,4,5&quot;]', 'pzarchitect' ) ),
                'options' => array(
                  'images'      => __( 'Image Gallery', 'pzarchitect' ),
                  'ids'         => __( 'Media Library', 'pzarchitect' ),
                  'wpgallery'   => __( 'WP Galleries', 'pzarchitect' ),
                  // TODO: Get post images as gallery source working
                  'postimages'  => __( 'Attached images', 'pzarchitect' ),
                  'galleryplus' => __( 'GalleryPlus', 'pzarchitect' ),
                 ),
              ),
              array(
                'title'    => __( 'Image Gallery', 'pzarchitect' ),
                'id'       => $prefix . 'specific-images',
                'type'     => 'gallery',
                'required' => array( $prefix . 'gallery-source', 'equals', 'images' ),
              ),
              array(
                'title'    => __( 'Using Media Library', 'pzarchitect' ),
                'id'       => $prefix . 'media-about',
                'type'     => 'info',
                'style'    => 'success',
                'required' => array( $prefix . 'gallery-source', 'equals', 'ids' ),
                'subtitle' => __( 'If you select Media Library, you can specify <em>Specific IDs</em> of media library images below.', 'pzarchitect' ) . '<br><br>' . __( 'Alternatively, if you are using a plugin that adds categories to the media library. e.g.', 'pzarchitect' ) . '<a href="https://wordpress.org/plugins/enhanced-media-library/" target=_blank> Enhanced Media Library</a> ' . __( 'or any of the others in ', 'pzarchitect' ) . '<a href="https://wordpress.org/plugins/search.php?q=media+library+categories" target=_blank>Wordpress plugins</a>, ' . __( 'you can filter on those categories.', 'pzarchitect' ) . '<br><br>' . __( 'To do so, in the Blueprints Content > Filter tab, select the media category your chosen plugin uses in Custom Taxonomies, and enter the taxonomy to filter by.', 'pzarchitect' ) . '<br><br><strong>' . __( 'This method creates an extremely user friendly way to create image galleries. All your user has to do is upload the image, tag it in a category, and it will automatically display in the gallery you created with Architect.', 'pzarchitect' ) . '</strong>',
              ),
              array(
                'title'    => __( 'Specific IDs', 'pzarchitect' ),
                'id'       => $prefix . 'specific-ids',
                'type'     => 'text',
                'subtitle' => 'Enter a comma separated list of image ids',
                'required' => array( $prefix . 'gallery-source', 'equals', 'ids' ),
              ),
              ( empty( $_architect_options['architect_post-specific-id-dropdown'] ) ? array(
                'title'    => __( 'WP Gallery', 'pzarchitect' ),
                'id'       => $prefix . 'wp-post-gallery',
                'type'     => 'select',
                'data'     => 'callback',
                'args'     => array( 'pzarc_get_wp_galleries' ),
                'subtitle' => 'Select a post with a gallery',
                'required' => array( $prefix . 'gallery-source', 'equals', 'wpgallery' ),
              ) : array(
                'title'    => __( 'ID of post with the gallery', 'pzarchitect' ),
                'id'       => $prefix . 'wp-post-gallery',
                'type'     => 'text',
                'subtitle' => __( 'Enter the ID of the post with the gallery to display', 'pzarchitect' ),
                'required' => array( $prefix . 'gallery-source', 'equals', 'wpgallery' ),

              ) ),

              // TODO: Get post images as gallery source working. MAYBE... Probably not til ajaxed
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
                'title'    => __( 'Attached images', 'pzarchitect' ),
                'id'       => $prefix . 'wp-post-images-info',
                'type'     => 'info',
                'subtitle' => __( 'This will display all images attached to the primary post, page or content type this Blueprint is displayed on. It does not suppress the display of those images within the content. And it will display any image still attached to the particular post etc.<br><br>One way you might use it, is to attach several images to a page, and instead of displaying them within the content, use the Attached Images content source to display them in a separate area on the page, in a widget or block.', 'pzarchitect' ),
                'required' => array( $prefix . 'gallery-source', 'equals', 'postimages' ),
              ),
              array(
                'title'    => __( 'GalleryPlus', 'pzarchitect' ),
                'id'       => $prefix . 'galleryplus',
                'type'     => 'select',
                'data'     => 'callback',
                'args'     => array( 'pzarc_get_gp_galleries' ),
                'subtitle' => 'Select a GalleryPlus gallery',
                'required' => array( $prefix . 'gallery-source', 'equals', 'galleryplus' ),
              ),
            ) ,
        ),
      );

      // This has to be post_type
      $registry->set( 'post_types', $settings ,$prefix);
      $registry->set( 'content_source', array( 'gallery' => plugin_dir_path( __FILE__ ) ) );

    }
  }


//  //todo:set this up as a proper singleton?
  new arc_content_galleries( 'arc_content_galleries' );


