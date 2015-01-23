<?php
  /**
   * Project pizazzwp-architect.
   * File: arc-cpt-panels.php
   * User: chrishoward
   * Date: 20/10/14
   * Time: 10:45 PM
   */

  if (!post_type_exists('arc-panels') && !function_exists('create_panels_post_type')) {
//    if (is_admin() && !(class_exists('ReduxFramework'))) {
//      // we don't want to load blueprints type if redux not around
//    } else {
      add_action('init', 'create_panels_post_type');
//    }
    function create_panels_post_type()
    {
      $labels = array(
          'name'               => _x('Panel designs', 'post type general name'),
          'singular_name'      => _x('Panel design', 'post type singular name'),
          'add_new'            => __('Add New Panel design'),
          'add_new_item'       => __('Add New Panel design'),
          'edit_item'          => __('Edit Panel design'),
          'new_item'           => __('New Panel design'),
          'view_item'          => __('View Panel design'),
          'search_items'       => __('Search Panel designs'),
          'not_found'          => __('No Panel designs found'),
          'not_found_in_trash' => __('No Panel designs found in Trash'),
          'parent_item_colon'  => '',
          'menu_name'          => _x('<span class="dashicons dashicons-id-alt"></span>Panels', 'pzarc-cell-designer'),
      );

      $args = array(
          'labels'              => $labels,
          'description'         => __('Architect Panels are used to create reusable panel designs for use in your Architect blocks, widgets, shortcodes and WP template tags'),
          'public'              => false,
          'publicly_queryable'  => false,
          'show_ui'             => true,
          'show_in_menu'        => 'pzarc',
          'show_in_nav_menus'   => false,
          'query_var'           => true,
          'rewrite'             => true,
          'capability_type'     => 'post',
          'has_archive'         => false,
          'hierarchical'        => false,
          'menu_position'       => 10,
          'supports'            => array('title'),
          'exclude_from_search' => true,
      );

      register_post_type('arc-panels', $args);
    }
  }