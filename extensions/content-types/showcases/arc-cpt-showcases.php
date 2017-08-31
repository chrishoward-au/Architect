<?php
  /**
   * Project pizazzwp-architect.
   * File: arc-cpt-showcases.php
   * User: chrishoward
   * Date: 20/10/14
   * Time: 10:40 PM
   */

  if ( ! post_type_exists( 'pz_showcases' ) && ! function_exists( 'pz_create_showcases_post_type' ) ) {
    // add_action('init', 'pz_create_showcases_post_type');
    function pz_create_showcases_post_type() {
      $architect_options = get_option( '_architect_options' );
      $rewrite_slug      = ( array_key_exists( 'architect_rewrites-showcases', $architect_options ) ? esc_html( str_replace( ' ', '', $architect_options[ 'architect_rewrites-showcases' ] ) ) : 'pz_showcases' );
      $labels            = array(
        'name'               => _x( 'Showcases (Architect)', 'post type general name' ),
        'singular_name'      => _x( 'Showcase', 'post type singular name' ),
        'add_new'            => _x( 'Add Showcase item', 'gallery' ),
        'add_new_item'       => __( 'Add Showcase item' ),
        'edit_item'          => __( 'Edit Showcase item' ),
        'new_item'           => __( 'New Showcase item' ),
        'view_item'          => __( 'View Showcase item' ),
        'search_items'       => __( 'Search Showcases' ),
        'not_found'          => __( 'No showcases found' ),
        'not_found_in_trash' => __( 'No showcases found in Trash' ),
        'parent_item_colon'  => '',
        'menu_name'          => _x( 'Showcases', 'pzarchitect' ),
      );
      $args              = array(
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        //          'show_in_menu'       => 'pzarc',
        'menu_icon'          => 'dashicons-images-alt',
        'query_var'          => true,
        'rewrite'            => array( 'slug' => $rewrite_slug ),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => true,
        'taxonomies'         => array(),
                 'menu_position'      => 999,
        'supports'           => array(
          'title',
          'editor',
          'author',
          'thumbnail',
          'excerpt',
          'custom-fields',
          'revisions',
        )
      );

//      <span class="dashicons dashicons-smiley"></span><span class="dashicons dashicons-format-quote"></span>
      register_post_type( 'pz_showcases', $args );

      // Create custom category taxonomy for Showcases
      $labels = array(
        'name'              => _x( 'Showcase categories', 'taxonomy general name' ),
        'singular_name'     => _x( 'Showcase category', 'taxonomy singular name' ),
        'search_items'      => __( 'Search Showcase categories' ),
        'all_items'         => __( 'All Showcase categories' ),
        'parent_item'       => __( 'Parent Showcase category' ),
        'parent_item_colon' => __( 'Parent Showcase category:' ),
        'edit_item'         => __( 'Edit Showcase category' ),
        'update_item'       => __( 'Update Showcase category' ),
        'add_new_item'      => __( 'Add New Showcase category' ),
        'new_item_name'     => __( 'New Showcase category name' ),
        'menu_name'         => __( 'Showcase Categories' ),
      );

      register_taxonomy( 'pz_showcase_cat',
                         array( 'pz_showcases' ),
                         array(
                           'hierarchical' => true,
                           'labels'       => $labels,
                           'show_ui'      => true,
                           'query_var'    => true,
                           'rewrite'      => array( 'slug' => 'pzshowcasescat' ),
                         )
      );

      // Create custom category taxonomy for Showcases
      $labels = array(
        'name'              => _x( 'Showcase tags', 'taxonomy general name' ),
        'singular_name'     => _x( 'Showcase tag', 'taxonomy singular name' ),
        'search_items'      => __( 'Search Showcase tags' ),
        'all_items'         => __( 'All Showcase tags' ),
        'parent_item'       => __( 'Parent Showcase tag' ),
        'parent_item_colon' => __( 'Parent Showcase tag:' ),
        'edit_item'         => __( 'Edit Showcase tag' ),
        'update_item'       => __( 'Update Showcase tag' ),
        'add_new_item'      => __( 'Add New Showcase tag' ),
        'new_item_name'     => __( 'New Showcase tag name' ),
        'menu_name'         => __( 'Showcase Tags' ),
      );

      register_taxonomy( 'pz_showcase_tag',
                         array( 'pz_showcases' ),
                         array(
                           'hierarchical' => false,
                           'labels'       => $labels,
                           'show_ui'      => true,
                           'query_var'    => true,
                           'rewrite'      => array( 'slug' => 'pzshowcasestag' ),
                         )
      );

    }

    add_filter( 'manage_pz_showcases_posts_columns', 'add_showcases_columns' );
    add_action( 'manage_pz_showcases_posts_custom_column', 'add_showcases_column_content', 10, 2 );

    function add_showcases_columns( $columns ) {
      $pzarc_front  = array_slice( $columns, 0, 2 );
      $pzarc_back   = array_slice( $columns, 2 );
      $pzarc_insert = array(
        'pz_showcase_cat'     => __( 'Categories', 'pzarchitect' ),
        'pz_showcase_tag'     => __( 'Tags', 'pzarchitect' ),
        'pzarc_showcase-year' => __( 'Year of Work', 'pzarchitect' ),
      );

      return array_merge( $pzarc_front, $pzarc_insert, $pzarc_back );
    }

    /**
     *
     * @param [type] $column  [description]
     * @param [type] $post_id [description]
     */
    function add_showcases_column_content( $column, $post_id ) {

      switch ( $column ) {
        case 'pzarc_showcase-year':
          $post_meta = get_post_meta( $post_id );
          if ( isset( $post_meta[ $column ] ) ) {
            echo $post_meta[ $column ][ 0 ];
          }
          break;
        case 'pz_showcase_cat':
        case 'pz_showcase_tag':
          $post_terms = get_the_term_list( $post_id, $column, null, ', ', null );
          echo $post_terms;
          break;
      }
    }

    pz_create_showcases_post_type();
  }