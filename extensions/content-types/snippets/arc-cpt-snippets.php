<?php
/**
 * Project pizazzwp-architect.
 * File: arc-cpt-snippets.php
 * User: chrishoward
 * Date: 20/10/14
 * Time: 10:40 PM
 */

  if (!post_type_exists('pz_snippets') && !function_exists('pz_create_snippets_post_type')) {
    //add_action('init', 'pz_create_snippets_post_type');
    function pz_create_snippets_post_type()
    {
      $architect_options = get_option( '_architect_options' );
      $rewrite_slug=(array_key_exists('architect_rewrites-snippets',$architect_options)?esc_html(str_replace( ' ','' ,  $architect_options['architect_rewrites-snippets'])):'pz_snippets');
      $labels = array(
          'name'               => _x('Snippets (Architect)', 'post type general name'),
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
          'show_in_rest'=> false,  // Prevents using Gutenberg editor
          'exclude_from_search' => !empty($architect_options['architect_exclude-snippets-search']),
          //          'show_in_menu'       => 'pzarc',
          'menu_icon'          => 'dashicons-format-aside',
          'query_var'          => true,
          'rewrite'            => array('slug'=>$rewrite_slug),
          'capability_type'    => 'page',
          'has_archive'        => true,
          'hierarchical'       => true,
          'taxonomies'         => array('category', 'post_tag'),
                   'menu_position'      => 999,
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

    add_filter('manage_pz_snippets_posts_columns', 'add_snippets_columns');
    add_action('manage_pz_snippets_posts_custom_column', 'add_snippets_column_content', 10, 2);

    function add_snippets_columns($columns)
    {
      $pzarc_front = array_slice($columns, 0, 2);
      $pzarc_back  = array_slice($columns, 2);
      $pzarc_insert = array(
          'pz_snippet_cat' => __('Snippet Categories', 'pzarchitect'),
          'pz_snippet_tag' => __('Snippet Tags', 'pzarchitect'),
      );

      return array_merge($pzarc_front, $pzarc_insert, $pzarc_back);
    }

    /**
     *
     * @param [type] $column  [description]
     * @param [type] $post_id [description]
     */
    function add_snippets_column_content($column, $post_id)
    {

      switch ($column) {
        case 'pz_snippet_cat':
        case 'pz_snippet_tag':
          $post_terms = get_the_term_list($post_id,$column,null,', ',null);
          echo $post_terms;
          break;

      }
    }

    pz_create_snippets_post_type();

  }

// function add_custom_types_to_tax( $query ) {
//   if( is_category() || is_tag() && empty( $query->query_vars['suppress_filters'] ) ) {

//     // Get all your post field_types
//     $post_types = get_post_types();
    
//     $query->set( 'post_type', $post_types );
//     return $query;
//   }
// }
// add_filter( 'pre_get_posts', 'add_custom_types_to_tax' );
