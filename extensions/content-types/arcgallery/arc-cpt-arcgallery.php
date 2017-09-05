<?php
  /**
   * Project pizazzwp-architect.
   * File: arc-cpt-arcgallery.php
   * User: chrishoward
   * Date: 20/10/14
   * Time: 10:40 PM
   */

  if ( ! post_type_exists( 'pz_arcgallery' ) && ! function_exists( 'pz_create_arcgallery_post_type' ) ) {
    // add_action('init', 'pz_create_arcgallery_post_type');
    function pz_create_arcgallery_post_type() {
      $architect_options = get_option( '_architect_options' );
      $rewrite_slug      = ( array_key_exists( 'architect_rewrites-arcgallery', $architect_options ) ? esc_html( str_replace( ' ', '', $architect_options['architect_rewrites-arcgallery'] ) ) : 'pz_arcgallery' );
      $labels            = array(
        'name'               => _x( 'Galleries (Architect)', 'post type general name' ),
        'singular_name'      => _x( 'Gallery', 'post type singular name' ),
        'add_new'            => _x( 'Add Gallery item', 'gallery' ),
        'add_new_item'       => __( 'Add Gallery item' ),
        'edit_item'          => __( 'Edit Gallery item' ),
        'new_item'           => __( 'New Gallery item' ),
        'view_item'          => __( 'View Gallery item' ),
        'search_items'       => __( 'Search Galleries' ),
        'not_found'          => __( 'No Gallery found' ),
        'not_found_in_trash' => __( 'No Gallery found in Trash' ),
        'parent_item_colon'  => '',
        'menu_name'          => _x( 'Galleries', 'pzarchitect' ),
      );
      $args              = array(
        'labels'             => $labels,
        'public'             => TRUE,
        'publicly_queryable' => TRUE,
        'show_ui'            => TRUE,
        //          'show_in_menu'       => 'pzarc',
        'menu_icon'          => 'dashicons-format-gallery',
        'query_var'          => TRUE,
        'rewrite'            => array( 'slug' => $rewrite_slug ),
        'capability_type'    => 'post',
        'has_archive'        => TRUE,
        'hierarchical'       => TRUE,
        'taxonomies'         => array(),
        'menu_position'      => 999,
        'supports'           => array(
          'title',
          'author',
        ),
      );

//      <span class="dashicons dashicons-smiley"></span><span class="dashicons dashicons-format-quote"></span>
      register_post_type( 'pz_arcgallery', $args );

      // Create custom category taxonomy for arcgallery
      $labels = array(
        'name'              => _x( 'Gallery Categories (Architect)', 'taxonomy general name' ),
        'singular_name'     => _x( 'Gallery category', 'taxonomy singular name' ),
        'search_items'      => __( 'Search Gallery categories' ),
        'all_items'         => __( 'All Gallery categories' ),
        'parent_item'       => __( 'Parent Gallery category' ),
        'parent_item_colon' => __( 'Parent Gallery category:' ),
        'edit_item'         => __( 'Edit Gallery category' ),
        'update_item'       => __( 'Update Gallery category' ),
        'add_new_item'      => __( 'Add New Gallery category' ),
        'new_item_name'     => __( 'New Gallery category name' ),
        'menu_name'         => __( 'Gallery Categories' ),
      );

      register_taxonomy( 'pz_arcgallery_cat', array( 'pz_arcgallery' ), array(
        'hierarchical' => TRUE,
        'labels'       => $labels,
        'show_ui'      => TRUE,
        'query_var'    => TRUE,
        'rewrite'      => array( 'slug' => 'pzarcgallerycat' ),
      ) );

    }

    pz_create_arcgallery_post_type();


  }

  // GARGH!! How do we get all the vars in if it we do it this way! We need to extend the other code to load a additional query for galleries
  function pzarc_arcgallery_extend() {
    $gallery_post = get_post( $this->build->blueprint['_content_galleries_arcgallery'] );
    if ( ! empty( $gallery_post ) ) {
      preg_match_all( '/' . get_shortcode_regex() . '/s', $gallery_post->post_content, $matches );
      if ( isset( $matches[0][0] ) ) {
        preg_match( "/(?<=ids=\")([\\d,\\,])*/u", $matches[0][0], $ids );

        $this->query_options['post_type'] = 'attachment';
        if ( isset( $ids[0] ) ) {
          $this->query_options['post__in'] = explode( ',', $ids[0] );
        }
        $this->query_options['post_status']         = array( 'publish', 'inherit', 'private' );
        $this->query_options['ignore_sticky_posts'] = TRUE;
      }
    }
  }

