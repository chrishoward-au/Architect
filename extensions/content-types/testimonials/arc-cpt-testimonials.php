<?php
/**
 * Project pizazzwp-architect.
 * File: arc-cpt-testimonials.php
 * User: chrishoward
 * Date: 20/10/14
 * Time: 10:40 PM
 */

  if (!post_type_exists('pz_testimonials') && !function_exists('pz_create_testimonials_post_type')) {
    add_action('init', 'pz_create_testimonials_post_type');
    function pz_create_testimonials_post_type()
    {
      $labels = array(
          'name'               => _x('Testimonials', 'post type general name'),
          'singular_name'      => _x('Testimonial', 'post type singular name'),
          'add_new'            => _x('Add New Testimonial', 'gallery'),
          'add_new_item'       => __('Add New Testimonial'),
          'edit_item'          => __('Edit Testimonial'),
          'new_item'           => __('New Testimonial'),
          'view_item'          => __('View Testimonial'),
          'search_items'       => __('Search Testimonials'),
          'not_found'          => __('No testimonials found'),
          'not_found_in_trash' => __('No testimonials found in Trash'),
          'parent_item_colon'  => '',
          'menu_name'          => _x('Testimonials', 'pzarchitect'),
      );
      $args   = array(
          'labels'             => $labels,
          'public'             => true,
          'publicly_queryable' => true,
          'show_ui'            => true,
          //          'show_in_menu'       => 'pzarc',
          'menu_icon'          => 'dashicons-format-chat',
          'query_var'          => true,
          'rewrite'            => true,
          'capability_type'    => 'post',
          'has_archive'        => true,
          'hierarchical'       => true,
          'taxonomies'         => array(),
          //          'menu_position'      => 999,
          'supports'           => array('title',
                                        'editor',
                                        'author',
                                        'thumbnail',
                                        'excerpt',
                                        'custom-fields',
                                        'revisions',
          )
      );

//      <span class="dashicons dashicons-smiley"></span><span class="dashicons dashicons-format-quote"></span>
      register_post_type('pz_testimonials', $args);

      // Create custom category taxonomy for Testimonials
      $labels = array(
          'name' => _x( 'Testimonial categories', 'taxonomy general name' ),
          'singular_name' => _x( 'Testimonial category', 'taxonomy singular name' ),
          'search_items' =>  __( 'Search Testimonial categories' ),
          'all_items' => __( 'All Testimonial categories' ),
          'parent_item' => __( 'Parent Testimonial category' ),
          'parent_item_colon' => __( 'Parent Testimonial category:' ),
          'edit_item' => __( 'Edit Testimonial category' ),
          'update_item' => __( 'Update Testimonial category' ),
          'add_new_item' => __( 'Add New Testimonial category' ),
          'new_item_name' => __( 'New Testimonial category name' ),
          'menu_name' => __( 'Testimonial Categories' ),
      );

      register_taxonomy('pz_testimonial_cat',
                        array('pz_testimonials'),
                        array(
                            'hierarchical' => true,
                            'labels' => $labels,
                            'show_ui' => true,
                            'query_var' => true,
                            'rewrite' => array( 'slug' => 'pztestimonialscat' ),
                        )
      );

      // Create custom category taxonomy for Testimonials
      $labels = array(
          'name' => _x( 'Testimonial tags', 'taxonomy general name' ),
          'singular_name' => _x( 'Testimonial tag', 'taxonomy singular name' ),
          'search_items' =>  __( 'Search Testimonial tags' ),
          'all_items' => __( 'All Testimonial tags' ),
          'parent_item' => __( 'Parent Testimonial tag' ),
          'parent_item_colon' => __( 'Parent Testimonial tag:' ),
          'edit_item' => __( 'Edit Testimonial tag' ),
          'update_item' => __( 'Update Testimonial tag' ),
          'add_new_item' => __( 'Add New Testimonial tag' ),
          'new_item_name' => __( 'New Testimonial tag name' ),
          'menu_name' => __( 'Testimonial Tags' ),
      );

      register_taxonomy('pz_testimonial_tag',
                        array('pz_testimonials'),
                        array(
                            'hierarchical' => false,
                            'labels' => $labels,
                            'show_ui' => true,
                            'query_var' => true,
                            'rewrite' => array( 'slug' => 'pztestimonialstag' ),
                        )
      );

    }
    add_filter('manage_pz_testimonials_posts_columns', 'add_testimonials_columns');
    add_action('manage_pz_testimonials_posts_custom_column', 'add_testimonials_column_content', 10, 2);

    function add_testimonials_columns($columns)
    {
      $pzarc_front = array_slice($columns, 0, 2);
      $pzarc_back  = array_slice($columns, 2);
      $pzarc_insert = array(
          'pz_testimonial_cat' => __('Categories', 'pzarchitect'),
          'pz_testimonial_tag' => __('Tags', 'pzarchitect'),
      );

      return array_merge($pzarc_front, $pzarc_insert, $pzarc_back);
    }

    /**
     *
     * @param [type] $column  [description]
     * @param [type] $post_id [description]
     */
    function add_testimonials_column_content($column, $post_id)
    {

      switch ($column) {
        case 'pz_testimonial_cat':
        case 'pz_testimonial_tag':
          $post_terms = get_the_term_list($post_id,$column,null,', ',null);
          echo $post_terms;
          break;

      }
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