<?php

  /**
   * Project pizazzwp-architect.
   * File: class_arc_content_posts.php
   * User: chrishoward
   * Date: 11/10/14
   * Time: 10:19 PM
   */
  // Add content info to the registry
  class arc_content_posts extends arc_set_data// Singleton
  {

     function __construct()
    {
      $registry = arc_Registry::getInstance();

      $prefix = '_content_posts_';

      global $_architect_options;
      if (empty($_architect_options)) {
        $_architect_options = get_option('_architect_options');
      }

      if (empty($_architect_options['architect_post-specific-id-dropdown'])) {
        $settings[ 'blueprint-content' ] = array(
            'type'        => 'post',
            'name'        => 'Posts',
            'panel_class' => 'arc_panel_Posts',
            'prefix'      => $prefix,
            // These are the sections to display on the admin metabox. We also use them to get default values.
            'sections'    => array(
                'title'      => 'Posts',
                'icon_class' => 'icon-large',
                'icon'       => 'el-icon-list',
                'fields'     => array(
                    array(
                        'title'    => __('Specific posts', 'pzarchitect'),
                        'id'       => $prefix . 'specific-posts',
                        'type'     => 'select',
                        'select2'  => array('allowClear' => true),
                        'args'     => array('posts_per_page' => '-1'),
                        'data'     => 'posts',
                        'multi'    => true,
                        'sortable' => true,
                        'subtitle' => __('Select then drag and drop to order. Be sure to set Order By in Settings to Specified.', 'pzarchitect'),
                    ),
                    array(
                        'title'   => __('Exclude posts', 'pzarchitect'),
                        'id'      => $prefix . 'exclude-posts',
                        'type'    => 'select',
                        'select2' => array('allowClear' => true),
                        'args'    => array('posts_per_page' => '-1'),
                        'data'    => 'posts',
                        'multi'   => true
                    ),
                    array(
                        'title'    => __('Exclude current post', 'pzarchitect'),
                        'id'       => $prefix . 'exclude-current-post',
                        'type'     => 'switch',
                        'on'       => __('Yes', 'pzarchitect'),
                        'off'      => __('No', 'pzarchitect'),
                        'default'  => false,
                        'subtitle' => __('If this Blueprint is displayed on a post page, exclude that post from Blueprint', 'pzarchitect')
                    ),
                )
            )
        );
      } else {
        $settings[ 'blueprint-content' ] = array(
            'type'        => 'post',
            'name'        => 'Posts',
            'panel_class' => 'arc_panel_Posts',
            'prefix'      => $prefix,
            // These are the sections to display on the admin metabox. We also use them to get default values.
            'sections'    => array(
                'title'      => 'Posts',
                'icon_class' => 'icon-large',
                'icon'       => 'el-icon-list',
                'fields'     => array(
                    array(
                        'title'    => __('Specific posts', 'pzarchitect'),
                        'id'       => $prefix . 'specific-posts',
                        'type'     => 'text',
                        'subtitle' => __('Enter a comma separated list of post IDs. Be sure to set Order By in Settings to Specified.', 'pzarchitect'),
                        'desc'=> __('If you wish to display a dropdown of posts here, you can enable that in Options. However, be aware if there are thousands of posts, the Blueprint editor may not load due to out of memory.','pzarchitect')
                    ),
                    array(
                        'title'   => __('Exclude posts', 'pzarchitect'),
                        'id'      => $prefix . 'exclude-posts',
                        'type'    => 'text',
                    ),
                    array(
                        'title'    => __('Exclude current post', 'pzarchitect'),
                        'id'       => $prefix . 'exclude-current-post',
                        'type'     => 'switch',
                        'on'       => __('Yes', 'pzarchitect'),
                        'off'      => __('No', 'pzarchitect'),
                        'default'  => false,
                        'subtitle' => __('If this Blueprint is displayed on a post page, exclude that post from Blueprint', 'pzarchitect')
                    ),
                )
            )
        );

      }
      // This has to be post_types
      $registry->set('post_types', $settings);
      $registry->set('content_source',array('post'=>plugin_dir_path(__FILE__)));
    }

  }

//  //todo:set this up as a proper singleton?
  new arc_content_posts('arc_content_posts');



