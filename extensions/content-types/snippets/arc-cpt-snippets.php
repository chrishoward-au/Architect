<?php
/**
 * Project pizazzwp-architect.
 * File: arc-cpt-snippets.php
 * User: chrishoward
 * Date: 20/10/14
 * Time: 10:40 PM
 */

  if (!post_type_exists('pz_snippets') && !function_exists('pz_create_snippets_post_type')) {
    add_action('init', 'pz_create_snippets_post_type');
    function pz_create_snippets_post_type()
    {
      $labels = array(
          'name'               => _x('Snippets', 'post type general name'),
          'singular_name'      => _x('Snippet', 'post type singular name'),
          'add_new'            => _x('Add New Snippet', 'gallery'),
          'add_new_item'       => __('Add New Snippet'),
          'edit_item'          => __('Edit Snippet'),
          'new_item'           => __('New Snippet'),
          'view_item'          => __('View Snippet'),
          'search_items'       => __('Search Snippets'),
          'not_found'          => __('No snippets found'),
          'not_found_in_trash' => __('No snippets found in Trash'),
          'parent_item_colon'  => '',
          'menu_name'          => _x('Snippets', 'pzarchitect'),
      );
      $args   = array(
          'labels'             => $labels,
          'public'             => true,
          'publicly_queryable' => true,
          'show_ui'            => true,
          //          'show_in_menu'       => 'pzarc',
          'menu_icon'          => 'dashicons-format-aside',
          'query_var'          => true,
          'rewrite'            => true,
          'capability_type'    => 'page',
          'has_archive'        => true,
          'hierarchical'       => true,
          'taxonomies'         => array('category', 'post_tag'),
          //          'menu_position'      => 999,
          'supports'           => array('title',
                                        'editor',
                                        'author',
                                        'thumbnail',
                                        'excerpt',
                                        'comments',
                                        'revisions',
                                        'custom-fields',
                                        'post-formats',
                                        'page-attributes')
      );


      register_post_type('pz_snippets', $args);

      // Create custom category taxonomy for Snippets
      $labels = array(
          'name' => _x( 'Snippet categories', 'taxonomy general name' ),
          'singular_name' => _x( 'Snippet category', 'taxonomy singular name' ),
          'search_items' =>  __( 'Search Snippet categories' ),
          'all_items' => __( 'All Snippet categories' ),
          'parent_item' => __( 'Parent Snippet category' ),
          'parent_item_colon' => __( 'Parent Snippet category:' ),
          'edit_item' => __( 'Edit Snippet category' ),
          'update_item' => __( 'Update Snippet category' ),
          'add_new_item' => __( 'Add New Snippet category' ),
          'new_item_name' => __( 'New Snippet category name' ),
          'menu_name' => __( 'Snippet Categories' ),
      );

      register_taxonomy('pz_snippet_cat',
                        array('pz_snippets'),
                        array(
                            'hierarchical' => true,
                            'labels' => $labels,
                            'show_ui' => true,
                            'query_var' => true,
                            'rewrite' => array( 'slug' => 'pzsnippetscat' ),
                        )
      );
      // Create custom category taxonomy for Snippets
      $labels = array(
          'name' => _x( 'Snippet tags', 'taxonomy general name' ),
          'singular_name' => _x( 'Snippet tag', 'taxonomy singular name' ),
          'search_items' =>  __( 'Search Snippet tags' ),
          'all_items' => __( 'All Snippet tags' ),
          'parent_item' => __( 'Parent Snippet tag' ),
          'parent_item_colon' => __( 'Parent Snippet tag:' ),
          'edit_item' => __( 'Edit Snippet tag' ),
          'update_item' => __( 'Update Snippet tag' ),
          'add_new_item' => __( 'Add New Snippet tag' ),
          'new_item_name' => __( 'New Snippet tag name' ),
          'menu_name' => __( 'Snippet Tags' ),
      );

      register_taxonomy('pz_snippet_tag',
                        array('pz_snippets'),
                        array(
                            'hierarchical' => false,
                            'labels' => $labels,
                            'show_ui' => true,
                            'query_var' => true,
                            'rewrite' => array( 'slug' => 'pzsnippetstag' ),
                        )
      );

    }
  }

// function add_custom_types_to_tax( $query ) {
//   if( is_category() || is_tag() && empty( $query->query_vars['suppress_filters'] ) ) {

//     // Get all your post types
//     $post_types = get_post_types();
    
//     $query->set( 'post_type', $post_types );
//     return $query;
//   }
// }
// add_filter( 'pre_get_posts', 'add_custom_types_to_tax' );