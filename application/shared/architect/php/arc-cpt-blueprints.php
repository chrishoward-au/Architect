<?php
/**
 * Project pizazzwp-architect.
 * File: arc-cpt-blueprints.php
 * User: chrishoward
 * Date: 20/10/14
 * Time: 10:44 PM
 */

  if (!post_type_exists('arc-blueprints') && !function_exists('create_blueprints_post_type')) {
//    if (is_admin() && !(class_exists('ReduxFramework'))) {
//      // we don't want to load blueprints type if redux not around
//    } else {
      add_action('init', 'create_blueprints_post_type');
//    }


    function create_blueprints_post_type()
    {
      $labels = array(
          'name'               => _x('Blueprints', 'post type general name','pzarchitect'),
          'singular_name'      => _x('Blueprint', 'post type singular name','pzarchitect'),
          'add_new'            => __('Add new blank Blueprint','pzarchitect','pzarchitect'),
          'add_new_item'       => __('Add new blank Blueprint','pzarchitect'),
          'edit_item'          => __('Edit Blueprint','pzarchitect'),
          'new_item'           => __('New Blueprint','pzarchitect'),
          'view_item'          => __('View Blueprint','pzarchitect'),
          'search_items'       => __('Search Blueprints','pzarchitect'),
          'not_found'          => __('No Blueprints found','pzarchitect'),
          'not_found_in_trash' => __('No Blueprints found in Trash','pzarchitect'),
          'parent_item_colon'  => '',
          'menu_name'          => _x('<span class="dashicons dashicons-welcome-widgets-menus"></span>Blueprints', 'pzarc-blueprint-designer','pzarchitect'),
      );

      $args = array(
          'labels'              => $labels,
          'description'         => __('Architect Blueprints are used to create reusable layout Blueprints for use in your Architect blocks, widgets, shortcodes and WP template tags.','pzarchitect'),
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
          'menu_position'       => 30,
          'supports'            => array('title'),
          'exclude_from_search' => true,
      );

      register_post_type('arc-blueprints', $args);
    }
  }
