<?php

  /**
   * Project pizazzwp-architect.
   * File: class_arc_query_generic.php
   * User: chrishoward
   * Date: 20/10/14
   * Time: 2:12 AM
   */
  class arc_query_generic {

    public $query_options = array();
    public $build = array();
    public $criteria = array();

    function __construct( $build, $criteria ) {
      $this->build    = $build;
      $this->criteria = $criteria;
    }

    public function build_custom_query_options( $overrides ) {
      // Make sure we're using the original post type for the page
//      var_dump($this->build->blueprint[ '_blueprints_content-source' ],$this->build->blueprint[ '_content_defaults_defaults-override' ],get_the_ID());
      if ( $this->build->blueprint[ '_blueprints_content-source' ] === 'defaults' ) {
        $this->query_options = $this->build->blueprint[ 'original_query_vars' ];
        // Don't want the default page names as it prevents cats etc...
        switch ( true ) {
          case ! empty( $this->criteria[ 'category__in' ] ):
          case ! empty( $this->criteria[ 'tag__in' ] ):
          case ! empty( $this->criteria[ 'category__not__in' ] ):
          case ! empty( $this->criteria[ 'tag__not__in' ] ):
          case ! empty( $this->criteria[ '_content_general_other-tax-tag' ] ):
            unset( $this->query_options[ 'pagename' ] );
            unset( $this->query_options[ 'name' ] );
            break;
        }

      }

      // TODO: need to scrape this down to just a generic one for built in post types
      // Probably should make this extensible
      //build the new query
      $source = $this->build->blueprint[ '_blueprints_content-source' ];
      global $paged;

      //Paging parameters
      if ( ! empty( $this->build->blueprint[ '_blueprints_pagination' ] ) ) {

        // This is meant ot be the magic tonic to make pagination work on static front page. Bah!! Didnt' for me - ever
        // TODO: Ah! It only doesn't work with Headway!
        // TODO: Ah! It only doesn't work with Headway!
        if ( get_query_var( 'paged' ) ) {

          $paged = get_query_var( 'paged' );

        } elseif ( get_query_var( 'page' ) ) {

          $paged = get_query_var( 'page' );

        } else {

          $paged = 1;

        }

//        $this->query_options[ 'nopaging' ]       = false;
        $this->query_options[ 'posts_per_page' ] = $this->criteria[ 'per_page' ];
        $this->query_options[ 'pagination' ]     = true;
        $this->query_options[ 'paged' ]          = $paged;

      } else {

        $query[ 'nopaging' ]                     = $this->criteria[ 'nopaging' ];
        $this->query_options[ 'posts_per_page' ] = $this->criteria[ 'panels_to_show' ];

        if ( ! empty( $this->criteria[ 'offset' ] ) ) {
          $this->query_options[ 'offset' ] = $this->criteria[ 'offset' ];
        }
      }

      // these two break paging yes?
      $this->query_options[ 'ignore_sticky_posts' ] = $this->criteria[ 'ignore_sticky_posts' ];

      //Lot of work!
      //     pzdebug($criteria);

      // TODO: We're going to have to make this pluggable too! :P Probably with a loop?

      /** General content filters */
      $cat_ids = $this->criteria[ 'category__in' ];

      // TODO: This doesn't work right yet
//      if ($this->build->blueprint[ '_content_general_sub-cats' ]  && is_category()) {
//        $current_cat = get_the_category();
//        $archive_cat = $current_cat->cat_ID;
//        $cat_kids = get_categories(array('child_of' => $archive_cat));
//
//        foreach ($cat_kids as $kid) {
//          $cat_ids[] = $kid->cat_ID;
//        }
//
//      }

      $this->query_options[ 'category__in' ]     = ( ! empty( $this->criteria[ 'category__in' ] ) ? $cat_ids : null );
      $this->query_options[ 'category__not_in' ] = ( ! empty( $this->criteria[ 'category__in' ] ) ? $this->criteria[ 'category__not_in' ] : null );

      $this->query_options[ 'tag__in' ]     = ( ! empty( $this->criteria[ 'tag__in' ] ) ? $this->criteria[ 'tag__in' ] : null );
      $this->query_options[ 'tag__not_in' ] = ( ! empty( $this->criteria[ 'tag__in' ] ) ? $this->criteria[ 'tag__not_in' ] : null );


      /** Custom taxonomies  */
      if ( ! empty( $this->build->blueprint[ '_content_general_other-tax-tags' ] ) ) {
        $this->query_options[ 'tax_query' ] = array(
          array(
            'taxonomy' => $this->build->blueprint[ '_content_general_other-tax' ],
            'field'    => 'slug',
            'terms'    => $this->build->blueprint[ '_content_general_other-tax-tags' ],
            'operator' => $this->build->blueprint[ '_content_general_tax-op' ]
          ),
        );
      }

      /** Specific content filters **********************************************************/
      $this->content_filters( $source, $overrides );

      if ( ! empty( $this->criteria[ '_content_posts_specific-posts' ] ) ) {
        $this->query_options[ 'post__in' ] = $this->criteria[ '_content_posts_specific-posts' ];
      }

      /** Order. This is always set **********************************************************/
      $this->query_options[ 'orderby' ] = $this->criteria[ 'orderby' ];
      $this->query_options[ 'order' ]   = $this->criteria[ 'order' ];
      if ( $this->criteria[ 'orderby' ] === 'custom' && ! empty( $this->build->blueprint[ '_content_general_custom-sort-key' ] ) && ! empty( $this->build->blueprint[ '_content_general_custom-sort-key-type' ] ) ) {
        switch ( $this->build->blueprint[ '_content_general_custom-sort-key-type' ] ) {
          case 'NUMERIC':
          case 'DECIMAL':
          case 'NUMERICDATE':
            $mk_type= 'NUMERIC';
            break;
          default:
            $mk_type = $this->build->blueprint[ '_content_general_custom-sort-key-type' ] ;
        }
        $this->query_options[ 'orderby' ]                         = 'arc-custom-sort';
        $this->query_options[ 'meta_query' ][ 'arc-custom-sort' ] = array(
          'key'  => $this->build->blueprint[ '_content_general_custom-sort-key' ],
          'type' => $mk_type
        );
      }
      /** Custom fields filters **********************************************************/
      for ( $cfi = 1; $cfi <= 3; $cfi ++ ) {

        if ( ! empty( $this->build->blueprint[ '_blueprints_content-fields-filter-key' . $cfi ] )  ) {
          $cf_value = $this->build->blueprint[ '_blueprints_content-fields-filter-value' . $cfi ];
          switch ( empty( $this->build->blueprint[ '_blueprints_content-fields-filter-value-type' . $cfi ] ) ? 'string' : $this->build->blueprint[ '_blueprints_content-fields-filter-value-type' . $cfi ] ) {
            case 'numeric' :
              $cf_value = (float) $cf_value;
              break;
            case 'binary':
              $cf_value = (int) $cf_value;
              break;
            case 'date':
              $cf_value = date( 'Y-m-d', strtotime( $cf_value ) );
              break;
            case 'datetime':
              $cf_value = strtotime( $cf_value );
              break;
            case 'time':
              $cf_value = strtotime( $cf_value );
              break;
            case 'timestamp':
              $cf_value = strtotime( $cf_value );
              break;
            default:
            case 'string':
              break;
          }

          if ( $this->build->blueprint[ '_blueprints_content-fields-filter-key' . $cfi ] === 'title' || $this->build->blueprint[ '_blueprints_content-fields-filter-key' . $cfi ] === 'date' ) {
            //TODO For some reason, this seems more complicated!
          } else {
            $this->query_options[ 'meta_query' ][] =
              array(
                'key'     => $this->build->blueprint[ '_blueprints_content-fields-filter-key' . $cfi ],
                'type'    => ( empty( $this->build->blueprint[ '_blueprints_content-fields-filter-type' . $cfi ] ) ? 'CHAR' : $this->build->blueprint[ '_blueprints_content-fields-filter-type' . $cfi ] ),
                'value'   => $cf_value,
                'compare' => ( empty( $this->build->blueprint[ '_blueprints_content-fields-filter-compare' . $cfi ] ) ? '=' : $this->build->blueprint[ '_blueprints_content-fields-filter-compare' . $cfi ] ),
              );
          }
          //  var_dump($this->query_options['meta_query']);
        }
      }

      if ( isset( $this->query_options[ 'meta_query' ][1] )) {
        $this->query_options[ 'meta_query' ][ 'relation' ] = ( empty( $this->build->blueprint[ '_blueprints_content-fields-filter-jointype' ] ) ? 'AND' : $this->build->blueprint[ '_blueprints_content-fields-filter-jointype' ] );
      }

      /** OVERRIDES
       * currently this is the only bit that really does anything
       **********************************************************/
      if ( ! empty( $overrides[ 'ids' ] ) ) {

        $this->query_options[ 'post__in' ]       = explode( ',', $overrides[ 'ids' ] );
        $this->query_options[ 'posts_per_page' ] = count( $this->query_options[ 'post__in' ] );
      }
      if ( ! empty( $overrides[ 'tax' ] ) && ! empty( $overrides[ 'terms' ] ) ) {
        $this->query_options[ 'tax_query' ] = array(
          array(
            'taxonomy' => $overrides[ 'tax' ],
            'field'    => 'slug',
            'terms'    => explode( ',', $overrides[ 'terms' ] ),
            'operator' => $this->build->blueprint[ '_content_general_tax-op' ]
          ),
        );
      }
      if ( isset( $_GET[ 'debug' ] ) ) {
        d( $this->query_options );
      }
    } //EOF

    /**
     *
     * Replace this in your own extensions if necessary
     *
     * @return WP_Query
     *
     */
    public function get_custom_query( $overrides ) {
// Get any existing copy of our transient data
      global $_architect_options;

      // Otto says don't use transients for this type of scenario
      //http://webdevstudios.com/2014/12/04/using-transients-with-wordpress-to-cache-all-the-things/
//      $transient_id = 'pzarc_custom_query_' . $this->build->blueprint[ '_blueprints_short-name' ].'_'.(!empty($overrides['terms'])?$overrides['terms']:'' );
//      if (!empty($_architect_options[ 'architect_enable_query_cache' ]) && false == ($custom_query = get_transient($transient_id)) && (!current_user_can('manage_options') || !current_user_can('edit_others_pages'))) {
//        // It wasn't there, so regenerate the data and save the transient
//
//        $custom_query = new WP_Query($this->query_options);
//
//        set_transient($transient_id, $custom_query, PZARC_TRANSIENTS_KEEP);
//
//      } elseif (current_user_can('edit_others_pages') || empty($_architect_options[ 'architect_enable_query_cache' ])) {
//        // if is admin
      $custom_query = new WP_Query( $this->query_options );
//      } else {
//        // Will use transient value from first check
//      }

      global $wp_query;
//      /* Changed to this approach coz the other broke WPML */
      $wp_query = ( isset( $custom_query ) ? $custom_query : $wp_query );
////      // 0.9.0.2 TODO: This scares me! Test thoroughly that it's okay to use $wp_query. The big problem is if something else changes it midstream. And then resets to the main query!
////      // Previously all these were $custom_query
////      // If transient not set and not admin
//      if ( false == ( $wp_query = get_transient( 'pzarc_custom_query_'.$this->build->blueprint['_blueprints_short-name'] ) )  && !current_user_can( 'manage_options' ) ) {
//        // It wasn't there, so regenerate the data and save the transient
//        $wp_query = new WP_Query($this->query_options);
//
//        set_transient( 'pzarc_custom_query_'.$this->build->blueprint['_blueprints_short-name'], $wp_query, PZARC_TRANSIENTS_KEEP );
//
//      } elseif (current_user_can( 'manage_options' )) {
//        // if is admin
//        $wp_query = new WP_Query($this->query_options);
//      } else {
//        // Will use transient value from first check
//      }
      return $wp_query;
    }

    protected function content_filters( $source, $overrides ) {
//TODO: this function isn't called by $this->content_filters above. Only child ones are. Need to resolve!
      switch ( $source ) {

        case 'cpt':
          $this->query_options[ 'post_type' ] = $this->build->blueprint[ '_content_cpt_custom-post-type' ];
          break;


      }


    }

  }

