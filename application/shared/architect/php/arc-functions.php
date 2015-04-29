<?php

  if ( ! function_exists( 'pzdebug' ) ) {

    //---------------------------------------------------------------------------------------------------
    // Debug
    //---------------------------------------------------------------------------------------------------
    /**
     * [pzdebug description]
     *
     * @param  string $value ='' [description]
     *
     * @return [type]           [description]
     */
    function pzdebug( $value = '' ) {
      $btr  = debug_backtrace();
      $line = $btr[ 0 ][ 'line' ];
      $file = basename( $btr[ 0 ][ 'file' ] );
      print"<pre>$file:$line</pre>\n";
      if ( is_array( $value ) ) {
        print"<pre>";
        print_r( $value );
        print"</pre>\n";
      } elseif ( is_object( $value ) ) {
        var_dump( $value );
      } else {
        print( "<p>&gt;${value}&lt;</p>" );
      }
    }

  }

  /**
   * @param $tax
   * @param $prefix
   * @param $suffix
   * @param $separator
   *
   * @return string
   */
  function pzarc_tax_string_list( $tax, $prefix, $suffix, $separator ) {
    $list  = '';
    $count = count( $tax );
    $i     = 1;
    if ( is_array( $tax ) ) {
      foreach ( $tax as $key => $value ) {
        $list .= $prefix . $value->slug . $suffix . ( $i ++ == $count ? '' : $separator );
      }
    }

    return $list;
  }

  /**
   * @param $array
   *
   * @return array
   */
  function pzarc_squish( $array ) {
    $return_array = array();
    foreach ( $array as $key => $value ) {
      $return_array[ $key ] = $array[ $key ][ 0 ];
    }

    return $return_array;
  }

  /**
   * @param $start
   * @param $end
   * @param $source
   *
   * @return mixed
   */
  function pzarc_get_string( $start, $end, $source ) {

    preg_match( "/(?<=" . $start . ").+?(?=" . $end . ")/uim", $source, $result );

    return $result[ 0 ];

  }

  /**
   * @param        $id
   * @param        $selectors
   * @param string $defaults
   * @param array  $exclude
   *
   * @return array
   */
  function pzarc_redux_font( $id, $selectors, $defaults = '', $exclude = array() ) {
//var_dump($exclude,in_array('font-family',$exclude),in_array('color',$exclude),!array_search('font-family',$exclude));
    // TODO: Change font size to a range and use flowtype.js
//'architect_typography_units'
    global $_architect_options;
    $units        = isset( $_architect_options[ 'architect_typography_units' ] ) ? $_architect_options[ 'architect_typography_units' ] : 'px';
    $return_array = array(
      'title'           => __( 'Typography', 'pzarchitect' ),
      'id'              => $id,
      'subtitle'        => __( 'You can change the typography units to px,em or rem in Architect > Options', 'pzarchitect' ),
      'desc'            => __( 'Tip: If you set the typography line height to less than 3, Architect will use it as a multiplier of the font size.e.g. line-height:1.5.', 'pzarchitect' ),
      //       'output'          => $selectors,
      'type'            => 'typography',
      'text-decoration' => true,
      'font-variant'    => true,
      'text-transform'  => true,
      'font-family'     => true,
      'font-size'       => true,
      'font-weight'     => true,
      'font-style'      => true,
      'font-backup'     => true,
      'google'          => true,
      'subsets'         => false,
      'custom_fonts'    => false,
      'text-align'      => true,
      //'text-shadow'       => false, // false
      'color'           => true,
      'preview'         => true,
      'line-height'     => true,
      'word-spacing'    => true,
      'letter-spacing'  => true,
      'units'           => $units,
      'default'         => $defaults,
    );
    foreach ( $return_array as $k => $v ) {
      if ( in_array( $k, $exclude ) ) {
        $return_array[ $k ] = false;
      }
    }

    return $return_array;
  }


  /**
   * @param       $id
   * @param null  $selectors
   * @param array $defaults
   *
   * @return array
   */
  function pzarc_redux_bg( $id, $selectors = null, $defaults = array( 'color' => '' ) ) {
    return array(
      'title'                 => __( 'Background', 'pzarchitect' ),
      'id'                    => $id,
      //        'output'                => $selectors,
      //        'compiler'              => $selectors,
      'type'                  => 'spectrum',
      'mode'                  => 'background-color',
      'background-image'      => false,
      'background-repeat'     => false,
      'background-size'       => false,
      'background-attachment' => false,
      'background-position'   => false,
      'preview'               => false,
      'default'               => $defaults,
    );
  }

  /**
   * @param       $id
   * @param       $selectors
   * @param array $defaults
   *
   * @return array
   */
  function pzarc_redux_padding( $id, $selectors, $defaults = array( 'units' => '%' ) ) {
    return array(
      'title'   => __( 'Padding', 'pzarchitect' ),
      'id'      => $id,
      //      'output'  => $selectors,
      'mode'    => 'padding',
      'type'    => 'spacing',
      'units'   => array( '%', 'px', 'em' ),
      'default' => $defaults,
    );

  }

  /**
   * @param        $id
   * @param        $selectors
   * @param array  $defaults
   * @param string $limits
   *
   * @return array
   */
  function pzarc_redux_margin( $id, $selectors, $defaults = array( 'units' => '%' ), $limits = 'tblr' ) {
    return array(
      'title'   => __( 'Margins', 'pzarchitect' ),
      'id'      => $id,
      //        'output'  => $selectors,
      'mode'    => 'margin',
      'type'    => 'spacing',
      'units'   => array( '%', 'px', 'em' ),
      'default' => $defaults,
      'top'     => ( strpos( $limits, 't' ) !== false ),
      'bottom'  => ( strpos( $limits, 'b' ) !== false ),
      'left'    => ( strpos( $limits, 'l' ) !== false ),
      'right'   => ( strpos( $limits, 'r' ) !== false )
    );

  }

  /**
   * @param       $id
   * @param       $selectors
   * @param array $defaults
   *
   * @return array
   */
  function pzarc_redux_links( $id, $selectors, $defaults = array() ) {
    return
      array(
        'title'   => __( 'Links', 'pzarchitect' ),
        'id'      => $id,
        'type'    => 'links',
        //            'output'  => $selectors,
        'default' => $defaults,
      );

  }

//  function pzarc_redux_links_dec($id, $selectors, $defaults = '')
//  {
//    return array(
//        'title'   => __('Links underline', 'pzarchitect'),
//        'id'      => $id,
//        'type'    => 'button_set',
//        'multi'   => true,
//        'output'  => $selectors,
//        'default' => $defaults,
//        'options' => array(
//            'regular' => 'Regular',
//            'hover'   => 'Hover',
//            'active'  => 'Active',
//            'visited' => 'Visited'
//        )
//    );
//
//  }

  /**
   * @param        $id
   * @param        $selectors
   * @param string $defaults
   *
   * @return array
   */
  function pzarc_redux_borders( $id, $selectors, $defaults = '' ) {

    return array(
      'title'   => __( 'Border', 'pzarchitect' ),
      'id'      => $id,
      'type'    => 'border',
      'all'     => false,
      //      'output'  => $selectors,
      'default' => $defaults
    );
  }

  /**
   * @param        $id
   * @param        $selectors
   * @param string $defaults
   *
   * @return array
   */
  function pzarc_redux_border_radius( $id, $selectors, $defaults = '' ) {

    return array(
      'title'    => __( 'Border Radius', 'pzarchitect' ),
      'subtitle' => __( 'TopLeft, TopRight, BottomLeft, BottomRight', 'pzarchitect' ),
      'id'       => $id,
      'type'     => 'border',
      'all'      => false,
      'style'    => false,
      'color'    => false,
      //       'output'  => $selectors,
      'default'  => $defaults
    );
  }

  /**
   * pzarc_get_defaults
   *
   * This doesn't need to return anything becuase it is populating the global variable
   *
   */
  function pzarc_get_defaults( $exclude_styling = false ) {
    pzdb( 'top get defaults' );
    // TODO: Do we really need to call this on the front end??!!

    // TODO: Remove this once Dovy fixes MB defaults... or maybe not...
    // Actually, $_architect doesn't populate if it's not here
//    require_once PZARC_PLUGIN_APP_PATH . '/admin/php/class_arc_panels_layouts.php';
    require_once PZARC_PLUGIN_APP_PATH . '/admin/php/class_arc_blueprints_designer.php';
    $blueprints = new arc_Blueprints_Designer( 'defaults' );
//    $panels     = new arc_Panels_Layouts('defaults');

    global $_architect;
    global $_architect_options;
    if ( ! isset( $_architect[ 'defaults' ] ) ) {
      /**
       * BLUEPRINTS
       *
       */
      pzdb( 'pre get blueprints defaults' );
//    if ($use_cache) {
//
//    } else {

      $_architect[ 'defaults' ][ 'blueprints' ] = ( ! isset( $_architect[ 'defaults' ][ 'blueprints' ] ) ? array() : $_architect[ 'defaults' ][ 'blueprints' ] );

      $blueprint_layout_general = $blueprints->pzarc_mb_blueprint_general_settings( $_architect[ 'defaults' ][ 'blueprints' ], true );
      $blueprint_styling        = empty( $_architect_options[ 'architect_enable_styling' ] ) || $exclude_styling ? '' : $blueprints->pzarc_mb_blueprint_styling( $_architect[ 'defaults' ][ 'blueprints' ], true );
      $pzarc_blueprint_layout   = $blueprints->pzarc_mb_blueprint_design( $_architect[ 'defaults' ][ 'blueprints' ], true );
      $pzarc_contents_metabox   = $blueprints->pzarc_mb_blueprint_content_selection( $_architect[ 'defaults' ][ 'blueprints' ], true );

      $_architect[ 'defaults' ][ 'blueprints' ][ '_blueprint_layout_general' ] = $blueprint_layout_general[ 0 ][ 'sections' ];
      $_architect[ 'defaults' ][ 'blueprints' ][ '_blueprint_stylings' ]       = empty( $blueprint_styling ) ? '' : $blueprint_styling[ 0 ][ 'sections' ];
      $_architect[ 'defaults' ][ 'blueprints' ][ '_blueprint_layout' ]         = $pzarc_blueprint_layout[ 0 ][ 'sections' ];
      $_architect[ 'defaults' ][ 'blueprints' ][ '_contents_metabox' ]         = $pzarc_contents_metabox[ 0 ][ 'sections' ];

      // Apply the defaults
      foreach ( $_architect[ 'defaults' ][ 'blueprints' ] as $key1 => $value1 ) {
        if ( ! empty( $value1 ) ) {
          foreach ( $value1 as $key2 => $value2 ) {
            foreach ( $value2 as $key3 => $fields ) {
              if ( is_array( $fields ) ) {
                foreach ( $fields as $key4 => $field ) {
                  if ( isset( $field[ 'id' ] ) ) {
                    $_architect[ 'defaults' ][ '_blueprints' ][ $field[ 'id' ] ] = ( empty( $field[ 'default' ] ) ? '' : $field[ 'default' ] );
                  }
                }
              }
            }
          }
        }
      }

//    }

      /**
       * PANELS
       *
       */
      pzdb( 'pre get panels defaults' );
      $_architect[ 'defaults' ][ 'panels' ] = ( ! isset( $_architect[ 'defaults' ][ 'panels' ] ) ? array() : $_architect[ 'defaults' ][ 'panels' ] );
//var_dump($_architect);
//      $pzarc_panel_general_settings = $blueprints->pzarc_panel_general_settings($_architect[ 'defaults' ][ 'panels' ], true);
      $pzarc_panels_design  = $blueprints->pzarc_mb_panels_layout( $_architect[ 'defaults' ][ 'panels' ], true );
      $pzarc_panels_styling = empty( $_architect_options[ 'architect_enable_styling' ] ) || $exclude_styling ? '' : $blueprints->pzarc_mb_panels_styling( $_architect[ 'defaults' ][ 'panels' ], true );

      //     $_architect[ 'defaults' ][ 'panels' ][ '_panel_general_settings' ] = $pzarc_panel_general_settings[ 0 ][ 'sections' ];
      $_architect[ 'defaults' ][ 'panels' ][ '_panels_design' ] = $pzarc_panels_design[ 0 ][ 'sections' ];

      if ( ! empty( $_architect_options[ 'architect_enable_styling' ] ) && ! $exclude_styling ) {
        $_architect[ 'defaults' ][ 'panels' ][ '_panels_styling' ] = $pzarc_panels_styling[ 0 ][ 'sections' ];
      }
      foreach ( $_architect[ 'defaults' ][ 'panels' ] as $key1 => $value1 ) {
        if ( ! empty( $value1 ) ) {
          foreach ( $value1 as $key2 => $value2 ) {
            foreach ( $value2 as $key3 => $fields ) {
              if ( is_array( $fields ) ) {
                foreach ( $fields as $key4 => $field ) {
                  if ( isset( $field[ 'id' ] ) ) {
                    $_architect[ 'defaults' ][ '_blueprints' ][ $field[ 'id' ] ] = ( empty( $field[ 'default' ] ) ? '' : $field[ 'default' ] );
                  }
                }
              }
            }
          }
        }
      }
      pzdb( 'bottom get defaults' );

      //  Unset the temporary blueprints field
      unset( $_architect[ 'defaults' ][ 'blueprints' ] );
      //  Unset the temporary panels field
      unset( $_architect[ 'defaults' ][ 'panels' ] );
    }
  }

  /**
   * @param $defaultvs
   * @param $setvals
   *
   * @return mixed
   */
  function pzarc_merge_defaults( $defaultvs, $setvals ) {

    foreach ( $defaultvs as $key => $value ) {
      if ( ! isset( $setvals[ $key ] ) ) {
        $setvals[ $key ] = $value;
      }
    }

    return $setvals;
  }

  // For testing actions
//add_action('arc_after_title','pzarc_action_test',10,3);
  /**
   * @param $component
   * @param $panelno
   * @param $postid
   */
  function pzarc_action_test( $component, $panelno, $postid ) {
    echo '<h2>Action run:' . current_action() . '</h2>';
    var_dump( $component, $panelno, $postid );
  }

  // For testing filters
//add_filter('arc_filter_excerpt','pzarc_filter_test',10,2);
  /**
   * @param $stuff
   * @param $postid
   *
   * @return string
   */
  function pzarc_filter_test( $stuff, $postid ) {
    return $stuff . '--more stuff added by filter--' . $postid;
  }

  //add_filter('arc_filter_shortcode', 'pzarc_scf_test', 10, 3);
  /**
   * @param $content
   * @param $blueprint
   * @param $overrides
   *
   * @return string
   */
  function pzarc_scf_test( $content, $blueprint, $overrides ) {
    return '<div class="pzarc-shortcode-debug" style="background:#fff4f4;border:solid 1px #c99;box-sizing: border-box;"><h3>Start shortcode blueprint ' . $blueprint . ' with ' . count( $overrides ) . ' overrides</h3>' . $content . '<h3>End blueprint ' . $blueprint . '</h3>';
  }

  /**
   * Convert any variable to an array
   *
   * @param $delimiter
   * @param $var
   *
   * @return array
   */
  function pzarc_to_array( $delimiter, $var ) {
    return ( is_array( $var ) ? $var : explode( $delimiter, (string) $var ) );
  }

  /**
   * @return array
   */
  function pzarc_fields() {
    $arg_list = func_get_args();
    $returna  = array();
    foreach ( $arg_list as $k => $v ) {
      if ( isset( $v[ 0 ] ) ) {
        foreach ( $v as $k2 => $v2 ) {
          $returna[ ] = $v2;
        }
      } else {
        $returna[ ] = $v;
      }
    }

    return $returna;
  }

  add_action( 'xloop_start', 'pzarc_top_of_loop', 10, 1 );
  /**
   * @param $the_query
   */
  function pzarc_top_of_loop( &$the_query ) {

    if ( is_main_query() ) {
      echo '<h1 style="font-size:24px;font-weight:bold;color:red;">Loop starts here</h1>';
//      var_dump($the_query);
    }
  }

  add_action( 'xloop_end', 'pzarc_bottom_of_loop' );
  /**
   *
   */
  function pzarc_bottom_of_loop() {
    if ( is_main_query() ) {
      echo '<h1 style="font-size:24px;font-weight:bold;color:red;">Loop ends here</h1>';
    }
  }

  /**
   * @return array|null
   */
  function pzarc_get_gp_galleries() {
    $post_types = get_post_types();
    if ( ! isset( $post_types[ 'gp_gallery' ] ) ) {
      return null;
    }
    // Don't need to check for GPlus class coz we add the post type
    // Get GalleryPlus galleries
    $args    = array( 'post_type' => 'gp_gallery', 'numberposts' => - 1, 'post_status' => null, 'post_parent' => null );
    $albums  = get_posts( $args );
    $results = array();
    if ( $albums ) {
      foreach ( $albums as $post ) {
        setup_postdata( $post );
        $results[ $post->ID ] = get_the_title( $post->ID );
      }
    }

    return $results;
  }

  /**
   * @return array
   */
  function pzarc_get_wp_galleries() {

    // Get galleries in posts and pages
    $args    = array(
      'post_type'   => array( 'post', 'page' ),
      'numberposts' => - 1,
      'post_status' => null,
      'post_parent' => null
    );
    $albums  = get_posts( $args );
    $results = array();
    if ( $albums ) {
      foreach ( $albums as $post ) {
        setup_postdata( $post );
        if ( get_post_gallery( $post->ID ) ) {
          $results[ $post->ID ] = substr( get_the_title( $post->ID ), 0, 60 );
        }
      }
    }

    return $results;


  }

  /**
   * @return array
   */
  function pzarc_get_wp_post_images() {
    $results = array( 'todo' => 'TODO!!!' );

    return $results;
  }

  /**
   * @param bool $inc_all
   * @param int  $min_level
   *
   * @return array
   */
  function pzarc_get_authors( $inc_all = true, $min_level = 1 ) {
    // user_level 1 = contributor
// Get authors
    $userslist = get_users();
    $authors   = array();
    if ( $inc_all ) {
      $authors[ 0 ] = 'All';
    }
    foreach ( $userslist as $author ) {
      if ( get_the_author_meta( 'user_level', $author->ID ) >= $min_level ) {
        $authors[ $author->ID ] = $author->display_name;
      }
    }

    return $authors;
  }

  function pzarc_get_custom_post_types() {
    $pzarc_cpts = ( get_post_types( array( '_builtin' => false, 'public' => true ), 'objects' ) );
    $return     = array();
    foreach ( $pzarc_cpts as $key => $value ) {
      $return[ $key ] = $value->labels->name;
    }

    return $return;
  }

  /**
   * @param string $pzarc_post_type
   * @param bool   $use_shortname
   *
   * @return array
   */
  function pzarc_get_posts_in_post_type( $pzarc_post_type = 'arc-blueprints', $use_shortname = false ) {
//    // No point doing this if not on a screen that can use it.
// Except it didn't work!
//    if (!function_exists('get_current_screen')) {
//      return array();
//    }
//    // No point doing this if not on a screen that can use it.
    if ( ! is_admin() ) {
      return array();
    }
    $args                 = array(
      'posts_per_page'   => - 1,
      'orderby'          => 'post_title',
      'order'            => 'ASC',
      'post_type'        => $pzarc_post_type,
      'post_status'      => 'publish',
      'suppress_filters' => true
    );
    $pzarc_post_types_obj = get_posts( $args );
    $pzarc_post_type_list = array();

    foreach ( $pzarc_post_types_obj as $pzarc_post_type_obj ) {

      if ( $use_shortname === true ) {

        if ( $pzarc_post_type === 'arc-blueprints' ) {
          $use_key = get_post_meta( $pzarc_post_type_obj->ID, '_blueprints_short-name', true );
        } elseif ( $pzarc_post_type === 'arc-panels' ) {
          $use_key = get_post_meta( $pzarc_post_type_obj->ID, '_panels_settings_short-name', true );
        } else {
          $use_key = $pzarc_post_type_obj->post_name;
        }

      } elseif ( $use_shortname === 'id-slug' ) {
        $use_key = $pzarc_post_type_obj->ID . ':' . $pzarc_post_type_obj->post_name;
      } else {
        $use_key = $pzarc_post_type_obj->post_name;
      }

      $pzarc_post_type_list[ $use_key ] = $pzarc_post_type_obj->post_title;
    }

    return $pzarc_post_type_list;
  }

  // TODO: This is a sorta duplicate of pzarc_get_posts_in_type. Fix it one day.
  /**
   * @param bool $inc_post_id
   *
   * @return array
   */
  function pzarc_get_blueprints( $inc_post_id = false ) {
    $query_options    = array(
      'post_type'      => 'arc-blueprints',
      'meta_key'       => '_blueprints_short-name',
      'posts_per_page' => '-1'
    );
    $blueprints_query = new WP_Query( $query_options );
    $pzarc_return     = array();
    while ( $blueprints_query->have_posts() ) {
      $blueprints_query->next_post();
      $the_panel_meta = get_post_meta( $blueprints_query->post->ID );
      $bpid           = $the_panel_meta[ '_blueprints_short-name' ][ 0 ] . ( $inc_post_id ? '##' . $blueprints_query->post->ID : '' );
      // This caused an error with the WooCommerce 2.3
      //     $pzarc_return[ $bpid ] = get_the_title($blueprints_query->post->ID);
      $pzarc_return[ $bpid ] = $blueprints_query->post->post_title;
    };
    asort( $pzarc_return );
    wp_reset_postdata();

    return $pzarc_return;
  }

  /**
   * @param $source_arr
   * @param $selected
   */
  function pzarc_array_to_options_list( $source_arr, $selected ) {
    foreach ( $source_arr as $key => $value ) {
      echo '<option value="' . esc_attr( $key ) . '" ' . ( $selected == $key ? 'selected' : null ) . '>' . esc_attr( $value ) . '</option>';
    }
  }

  /**
   * @return array
   */
  function pzarc_get_custom_fields() {
    global $wpdb;

    global $_architect_options;

    // WARNING: This may cause problems with missing custom fields
    if ( false === ( $pzarc_cf_list = get_transient( 'pzarc_cf_list' ) ) ) {
      // It wasn't there, so regenerate the data and save the transient

      if ( ! empty( $_architect_options[ 'architect_exclude_hidden_custom' ] ) ) {
        $pzarc_cf_list = $wpdb->get_results(
          "SELECT DISTINCT meta_key FROM $wpdb->postmeta HAVING meta_key NOT LIKE '\_%' ORDER BY meta_key"
        );

      } else {
        //Get custom fields
        // This is only able to get custom fields that have been used! ugh!
//    if ( false === ( $pzep_cf_list = get_transient( 'pzarc_custom_fields' ) ) ) {
        // It wasn't there, so regenerate the data and save the transient
        $pzarc_cf_list = $wpdb->get_results(
          "SELECT DISTINCT meta_key FROM $wpdb->postmeta ORDER BY meta_key"
        );
      }
      set_transient( 'pzarc_cf_list', $pzarc_cf_list );
    }
    //    set_transient( 'pzarc_custom_fields', $pzep_cf_list, 0*PZARC_TRANSIENTS_KEEP );
    // }

//    $pzep_cf_list = $wpdb->get_results(
//        "SELECT meta_key,post_id,wp_posts.post_type FROM wp_postmeta,wp_posts GROUP BY meta_key HAVING ((meta_key NOT LIKE '\_%' AND meta_key NOT LIKE 'pz%' AND meta_key NOT LIKE 'enclosure%') AND (wp_posts.post_type NOT LIKE 'attachment' AND wp_posts.post_type NOT LIKE 'revision' AND wp_posts.post_type NOT LIKE 'acf' AND wp_posts.post_type NOT LIKE 'arc-%' AND wp_posts.post_type NOT LIKE 'nav_menu_item' AND wp_posts.post_type NOT LIKE 'wp-types%')) ORDER BY meta_key"
//    );
    //   var_dump($pzep_cf_list);
    $exclude_fields   = array(
      'ID',
      'post_id',
      'post_author',
      'post_date',
      'post_date_gmt',
      'post_content',
      'post_title',
      'post_excerpt',
      'post_status',
      'comment_status',
      'ping_status',
      'post_password',
      'post_name',
      'to_ping',
      'pinged',
      'post_modified',
      'post_modified_gmt',
      'post_content_filtered',
      'post_parent',
      'guid',
      'menu_order',
      'post_type',
      'post_mime_type',
      'comment_count',
      'meta_id',
      'meta_key',
      'meta_value',
      'enclosure',
      'hide_on_screen',
      'original_post_id',
      'pre_import_post_id',
      'pre_import_post_parent',
      'panels_data',
      'position',
      'rule',
      'layout',
      'standard_link_url_field',
      'standard_seo_post_level_layout',
      'standard_seo_post_meta_description',
      'sharing_disabled'
    );
    $exclude_prefixes = array(
      '_blueprints',
      '_panels',
      '_animation',
      '_pzarc_pagebuilder',
      '_hw',
      '_wp_',
      '_format',
      '_edit',
      '_content',
      '_attachment',
      '_menu',
      '_oembed',
      '_publicize',
      '_thumbnail',
      'pzgp',
      'field_'


    );

    $pzarc_custom_fields = array();
    foreach ( $pzarc_cf_list as $pzarc_cf ) {
      if ( in_array( $pzarc_cf->meta_key, $exclude_fields ) === false && ! pzarc_starts_with( $exclude_prefixes, $pzarc_cf->meta_key ) ) {

        $pzarc_custom_fields[ $pzarc_cf->meta_key ] = $pzarc_cf->meta_key;
      }
    }

    return $pzarc_custom_fields;
  }

  function pzarc_starts_with( $needles = array(), $haystack = '', $position = 0 ) {
    $success = false;
    $needles = ! is_array( $needles ) ? array( $needles ) : $needles;
    foreach ( $needles as $needle ) {
      if ( strpos( $haystack, $needle ) === $position ) {
        $success = true;
        break;
      }

    }

    return $success;
  }

  /**
   * @param $text
   * @param $type
   */
  function pzarc_msg( $text, $type ) {
    echo '<div class="message-' . $type . '">' . $text . '</div>';
  }


  /***********************
   *
   * Flatten wp arrays if necessary
   *
   ***********************/

  function pzarc_flatten_wpinfo( $array_in, $strip = null ) {
    $array_out = array();
    if ( ! empty( $array_in ) ) {
      foreach ( $array_in as $key => $value ) {
        if ( $key == '_edit_lock' || $key == '_edit_last' || strpos( $key, $strip ) !== false ) {
          continue;
        }
        if ( is_array( $value ) ) {
          $array_out[ $key ] = $value;
        }
        $array_out[ $key ] = maybe_unserialize( $value[ 0 ] );
      }
    }

    return $array_out;
  }

  /**
   * @param $post_id
   * @param $meta_string
   *
   * @return array
   */
  function pzarc_get_post_terms( $post_id, $meta_string ) {
    $post_tax_terms = array();
    $meta_custom    = substr_count( $meta_string, '%ct:' );
    preg_match_all( "/(?<=\\%)(ct\\:)(.*)(?=\\%)/uiUmx", $meta_string, $matches );
    for ( $i = 1; $i <= $meta_custom; $i ++ ) {
      if ( taxonomy_exists( $matches[ 2 ][ $i - 1 ] ) ) {
        $post_tax_terms = array( $matches[ 2 ][ $i - 1 ] => get_the_term_list( $post_id, $matches[ 2 ][ $i - 1 ] ) );
      }
    }

    return $post_tax_terms;
  }

  /**
   * @param $post_name
   *
   * @return mixed
   *
   * Convert slug name to post ID
   *
   * We need this because we can't save post IDs else things aren't transportable
   */
  function pzarc_convert_name_to_id( $post_name ) {
    global $wpdb, $_architect_options;
    // We don't want transients used for admins since they may be testing new settings - which won't take!
    if ( ! empty( $_architect_options[ 'architect_enable_query_cache' ] ) && ( ! current_user_can( 'manage_options' ) || ! current_user_can( 'edit_others_pages' ) ) && false === ( $post_id = get_transient( 'pzarc_post_name_to_id_' . $post_name ) ) ) {
      // It wasn't there, so regenerate the data and save the transient
      $post_id = $wpdb->get_var( "SELECT ID FROM $wpdb->posts WHERE post_name = '" . $post_name . "'" );
      set_transient( 'pzarc_post_name_to_id_' . $post_name, $post_id, PZARC_TRANSIENTS_KEEP );
    } elseif ( current_user_can( 'edit_others_pages' ) || empty( $_architect_options[ 'architect_enable_query_cache' ] ) ) {
      $post_id = $wpdb->get_var( "SELECT ID FROM $wpdb->posts WHERE post_name = '" . $post_name . "'" );
    }


    return $post_id;
  }

  /**
   * @param $vcode
   *
   * @return false|string
   */
  function pzarc_process_video( $vcode ) {

    $vcode_type      = 'unknown';
    $vcode_processed = '';

    switch ( true ) {
      case ( strpos( strtolower( $vcode ), 'http' ) === 0 ):
        if ( strpos( $vcode, home_url() ) === 0 ) {
          $vcode      = '[video src="' . $vcode . '"]';
          $vcode_type = 'shortcode';
        } else {
          $vcode_type = 'url';
        }
        break;
      case ( strpos( strtolower( $vcode ), '<iframe ' ) === 0 ):
        $vcode_type = 'embed';
        break;
      case ( strpos( strtolower( $vcode ), '[' ) === 0 ):
        $vcode_type = 'shortcode';
        break;
    }

    switch ( $vcode_type ) {
      case 'url':
        // This also throws securing the url back to wp!
        $vcode_processed = wp_oembed_get( $vcode );
        break;
      case 'embed':
        $vcode_processed = $vcode;
        break;
      case 'shortcode':
        $vcode_processed = do_shortcode( $vcode );
        break;
    }

    return $vcode_processed;
  }

  /**
   * A simple minifier for CSS from https://ikreativ.com/combine-minify-css-with-php/
   *
   * @param  [type] $minify [description]
   *
   * @return [type]         [description]
   */
  function pzarc_compress( $minify ) {
    /* remove comments */
    $minify = preg_replace( '!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $minify );

    /* remove tabs, spaces, newlines, etc. */
    $minify = str_replace( array( "\r\n", "\r", "\n", "\t" ), '', $minify );
    $minify = str_replace( array( '  ', '    ', '    ' ), ' ', $minify );

    return $minify;
  }

  add_shortcode( 'pztestsc', 'pzarc_test_shortcode' );
  /**
   * @param $atts
   *
   * @return string
   */
  function pzarc_test_shortcode( $atts ) {
    return 'Shortcode test';
  }

  /**
   * @param $values
   *
   * @return array
   */
  function pzarc_maths_sum( $values ) {
    $result = 0;
    $vtype  = '';
    switch ( true ) {
      case strpos( $values[ 1 ], 'px' ):
        $vtype = 'px';
        break;
      case strpos( $values[ 1 ], 'rem' ):
        $vtype = 'rem';
        break;
      case strpos( $values[ 1 ], '%' ):
        $vtype = '%';
        break;
      case strpos( $values[ 1 ], 'em' ):
        $vtype = 'em';
        break;
    }
    foreach ( $values as $v ) {
      $vclean = str_replace( array( '%', 'px', 'em', 'rem' ), '', $v );
      $result += $vclean;
    }

    return array( 'result' => $result, 'type' => $vtype );
  }

  /**
   * @param $atts
   * @param $rawemail
   * @param $tag
   *
   * @return string
   */
  function pzarc_mail_encode( $atts, $rawemail, $tag ) {
    $s_email     = sanitize_email( $rawemail );
    $encodedmail = '';
    for ( $i = 0; $i < strlen( $s_email ); $i ++ ) {
      $encodedmail .= "&#" . ord( $s_email[ $i ] ) . ';';
    }
    if ( isset( $atts[ 0 ] ) ) {
      return '<a href="mailto:' . $encodedmail . '">' . $encodedmail . '</a>';
    } else {
      return $encodedmail;
    }
  }

  if ( ! shortcode_exists( 'mailto' ) ) {
    add_shortcode( 'mailto', 'pzarc_mail_encode' );
  }
  add_shortcode( 'pzmailto', 'pzarc_mail_encode' );

  // Testing function.
  // NOTE: If defaults chosen, then will be main query!!
  //    add_filter( 'the_content', 'cv_display_random_imgs_home' );
  //    function cv_display_random_imgs_home( $content ) {
  //      $custom_content = '1WTF'.$content;
  //      // Theoretically, this shouldn't run in most instances coz Architect is not the main loop. Template tags or a specific action could be the exception.
  //      if ( is_main_query() ) {
  //        $custom_content .= '2WTF';
  //      }
  //      var_dump(is_main_query());
  //      return $custom_content;
  //    }

  // Just incase
  if ( ! function_exists( 'd' ) ) {
    function d( $var ) {
      // do nothing incase a d() left behind
    }
  }

  /**
   * @param $classes
   * @param $properties
   *
   * @return null|string
   */
  function pzarc_process_fonts( $classes, $properties ) {
    $filler = '';
    if ( ! empty( $properties ) && is_array( $properties ) ) {
      foreach ( $properties as $k => $v ) {
        // Need to only process specific properties
        // This is to add quoties around fonts that don't have them
        switch ( $k ) {
          case ( ! empty( $v ) && $k == 'font-family' ):
            $ff    = explode( ', ', $v );
            $fonts = '';
            foreach ( $ff as $key => $font ) {
              if ( strpos( $font, ' ' ) > 0 && strpos( $font, '\'' ) === false ) {
                $fonts .= '"' . $font . '"';
              } else {
                $fonts .= $font;
              }

              if ( $key != count( $ff ) - 1 ) {
                $fonts .= ', ';
              }
            }
            $font_backup = ( ! empty( $properties[ 'font-backup' ] ) ? ', ' . str_replace( "'", '"', $properties[ 'font-backup' ] ) : '' );
            $filler .= $k . ':' . $fonts . $font_backup . ';';
            break;

          case ( ! empty( $v ) && $k == 'font-style' ):
          case ( ! empty( $v ) && $v !== 'px' && $k == 'font-size' ):
          case ( ! empty( $v ) && $k == 'font-variant' ):
          case ( ! empty( $v ) && $k == 'text-align' ):
          case ( ! empty( $v ) && $k == 'font-weight' ):
          case ( ! empty( $v ) && $k == 'text-transform' ):
          case ( ! empty( $v ) && $k == 'text-decoration' ):
          case ( ! empty( $v ) && $v !== 'px' && $k == 'word-spacing' ):
          case ( ! empty( $v ) && $v !== 'px' && $k == 'letter-spacing' ):
          case ( ! empty( $v ) && $k == 'color' ):
            $filler .= $k . ':' . $v . ';';
            break;

          case ( ! empty( $v ) && $v !== 'px' && $k == 'line-height' && (float) $v < 3 ):
            $filler .= $k . ':' . ( (float) $v ) . ';';
            break;
          case ( ! empty( $v ) && $v !== 'px' && $k == 'line-height' ):
            $filler .= $k . ':' . $v . ';';
            break;

        }
      }
    }

    return ( ! empty( $filler ) ? $classes . '{' . $filler . '}' : null );
  }

  /**
   * @param $properties
   *
   * @return string
   */
  function pzarc_process_spacing( $properties ) {
    $spacing_css = '';
    if ( ! empty( $properties ) && is_array( $properties ) ) {
      //    var_dump($properties);
      foreach ( $properties as $key => $value ) {
        // Only process values!
        if ( $key != 'units' ) {
          $iszero   = ( $value === 0 || $value === '0' );
          $isnotset = $value === '';
          $propval  = $key . ':' . $value;
          $propzero = $key . ':0;';
          $spacing_css .= ( $iszero ? $propzero : ( $isnotset ? null : $propval . ';' ) );
        }
      }
    }

    return $spacing_css;
  }

  /**
   * @param $classes
   * @param $properties
   *
   * @return string
   */
  function pzarc_process_borders( $classes, $properties ) {
    $borders_css = '';
    // This is to fix Redux making borders zero all the time
    $dodgy_values = array(
      'border-top'    => '0',
      'border-right'  => '0',
      'border-bottom' => '0',
      'border-left'   => '0',
      'border-style'  => 'solid',
      'border-color'  => ''
    );

    if ( ! empty( $properties ) && $properties != $dodgy_values ) {

      $borders_css .= ( ! empty( $properties[ 'border-top' ] ) ? 'border-top:' . $properties[ 'border-top' ] : '' );
      $borders_css .= ( ! empty( $properties[ 'border-top' ] ) && $properties[ 'border-top' ] !== '0' ? ' ' . $properties[ 'border-style' ] . ' ' . $properties[ 'border-color' ] . ';' : ';' );
      $borders_css .= ( ! empty( $properties[ 'border-right' ] ) ? 'border-right:' . $properties[ 'border-right' ] : '' );
      $borders_css .= ( ! empty( $properties[ 'border-right' ] ) && $properties[ 'border-right' ] !== '0' ? ' ' . $properties[ 'border-style' ] . ' ' . $properties[ 'border-color' ] . ';' : ';' );
      $borders_css .= ( ! empty( $properties[ 'border-bottom' ] ) ? 'border-bottom:' . $properties[ 'border-bottom' ] : '' );
      $borders_css .= ( ! empty( $properties[ 'border-bottom' ] ) && $properties[ 'border-bottom' ] !== '0' ? ' ' . $properties[ 'border-style' ] . ' ' . $properties[ 'border-color' ] . ';' : ';' );
      $borders_css .= ( ! empty( $properties[ 'border-left' ] ) ? 'border-left:' . $properties[ 'border-left' ] : '' );
      $borders_css .= ( ! empty( $properties[ 'border-left' ] ) && $properties[ 'border-left' ] !== '0' ? ' ' . $properties[ 'border-style' ] . ' ' . $properties[ 'border-color' ] . ';' : ';' );

      return $classes . '{' . $borders_css . '}';
    } else {
      return null;
    }
  }

  function pzarc_process_border_radius( $classes, $properties ) {
    $borders_css = '';
    if ( ! empty( $properties ) ) {

      $borders_css .= ( ! empty( $properties[ 'border-top' ] ) ? 'border-top-left-radius:' . $properties[ 'border-top' ] . ';' : '' );
      $borders_css .= ( ! empty( $properties[ 'border-right' ] ) ? 'border-top-right-radius:' . $properties[ 'border-right' ] . ';' : '' );
      $borders_css .= ( ! empty( $properties[ 'border-bottom' ] ) ? 'border-bottom-left-radius:' . $properties[ 'border-bottom' ] . ';' : '' );
      $borders_css .= ( ! empty( $properties[ 'border-left' ] ) ? 'border-bottom-right-radius:' . $properties[ 'border-left' ] . ';' : '' );

      return $classes . '{' . $borders_css . '}';
    } else {
      return null;
    }
  }

  /**
   * @param $classes
   * @param $properties
   *
   * @return string
   */
  function pzarc_process_links( $classes, $properties, $nl ) {
    // TODO: Should Default use inherit?
    $links_css = '';
    if ( ! empty( $properties ) ) {
      if ( ! empty( $properties[ 'regular' ] ) || ( ! empty( $properties[ 'regular-deco' ] ) && strtolower( $properties[ 'regular-deco' ] ) !== 'default' ) ) {
        $links_css .= $classes . ' a {';
        $links_css .= ( ! empty( $properties[ 'regular' ] ) ? 'color:' . $properties[ 'regular' ] . ';' : '' );
        $links_css .= ( strtolower( $properties[ 'regular-deco' ] ) !== 'default' ? 'text-decoration:' . strtolower( $properties[ 'regular-deco' ] ) . ';' : '' );
        $links_css .= '}' . $nl;
      }

      if ( ! empty( $properties[ 'hover' ] ) || ( ! empty( $properties[ 'hover-deco' ] ) && strtolower( $properties[ 'hover-deco' ] ) !== 'default' ) ) {
        $links_css .= $classes . ' a:hover {';
        $links_css .= ( ! empty( $properties[ 'hover' ] ) ? 'color:' . $properties[ 'hover' ] . ';' : '' );
        $links_css .= ( strtolower( $properties[ 'hover-deco' ] ) !== 'default' ? 'text-decoration:' . strtolower( $properties[ 'hover-deco' ] ) . ';' : '' );
        $links_css .= '}' . $nl;
      }

      if ( ! empty( $properties[ 'active' ] ) || ( ! empty( $properties[ 'active-deco' ] ) && strtolower( $properties[ 'active-deco' ] ) !== 'default' ) ) {
        $links_css .= $classes . ' a:active {';
        $links_css .= ( ! empty( $properties[ 'active' ] ) ? 'color:' . $properties[ 'active' ] . ';' : '' );
        $links_css .= ( strtolower( $properties[ 'active-deco' ] ) !== 'default' ? 'text-decoration:' . strtolower( $properties[ 'active-deco' ] ) . ';' : '' );
        $links_css .= '}' . $nl;
      }

      if ( ! empty( $properties[ 'visited' ] ) || ( ! empty( $properties[ 'visited-deco' ] ) && strtolower( $properties[ 'visited-deco' ] ) !== 'default' ) ) {
        $links_css .= $classes . ' a:visited {';
        $links_css .= ( ! empty( $properties[ 'visited' ] ) ? 'color:' . $properties[ 'visited' ] . ';' : '' );
        $links_css .= ( strtolower( $properties[ 'visited-deco' ] ) !== 'default' ? 'text-decoration:' . strtolower( $properties[ 'visited-deco' ] ) . ';' : '' );
        $links_css .= '}' . $nl;
      }

      return $links_css;
    } else {
      return null;
    }
  }

  function pzarc_process_background( $classes, $properties ) {
    // Currently not used
    $pzarc_bg_css = '';
    // Could come from one of two methods
    if ( ! empty( $properties ) ) {
      if ( ! empty( $properties[ 'color' ] ) ) {
        $pzarc_bg_css .= $classes . ' {background-color:' . $properties[ 'color' ] . ';}';
      }
      if ( ! empty( $properties[ 'background-color' ] ) ) {
        $pzarc_bg_css .= $classes . ' {background-color:' . $properties[ 'background-color' ] . ';}';
      }

      return $pzarc_bg_css;
    } else {
      return null;
    }
  }


  /**
   * @param $properties
   * @param $exclude
   *
   * @return bool
   */
  function pzarc_is_empty_vals( $properties, $exclude ) {

    $is_empty = true;
    if ( is_array( $properties ) ) {
      foreach ( $properties as $key => $value ) {
//    var_dump(!in_array($key,$exclude) , !empty($value));
        if ( ! in_array( $key, $exclude ) && strlen( $value ) > 0 ) {
          $is_empty = false;
          break;
        }
      }
    }

    return $is_empty;
  }

  // TODO: If this was all a class it could be extensible!
  /**
   * @param $source
   * @param $keys
   * @param $value
   * @param $parentClass
   *
   * @return mixed|string
   */
  function pzarc_get_styling( $source, $keys, $value, $parentClass ) {

    // generate correct whosit
    $pzarc_func = 'pzarc_style_' . $keys[ 'style' ];
    $pzarc_css  = '';
    foreach ( $keys[ 'classes' ] as $class ) {
      $pzarc_css .= ( function_exists( $pzarc_func ) ? call_user_func( $pzarc_func, $parentClass . ' ' . $class, $value ) : '' );
      if ( $pzarc_func == 'pzarc_style_padding' ) {
        //     var_dump($pzarc_css);
      }
      if ( ! function_exists( $pzarc_func ) ) {
        //print 'Missing function ' . $pzarc_func;
        pzdb( $pzarc_func );
      }
    }

    return $pzarc_css;
  }

  function pzarc_style_background( $class, $value ) {

    return ( ! empty( $value[ 'color' ] ) ? $class . ' {background-color:' . $value[ 'color' ] . ';}' . "\n" : null );

  }

  function pzarc_style_padding( $class, $value ) {
//    var_Dump(pzarc_is_empty_vals($value, array('units')),$value);
    //   var_dump($class,$value);
    return ( ! pzarc_is_empty_vals( $value, array( 'units' ) ) ? $class . ' {' . pzarc_process_spacing( $value ) . ';}' . "\n" : null );
  }

  function pzarc_style_margin( $class, $value ) {
    return ( ! pzarc_is_empty_vals( $value, array( 'units' ) ) ? $class . ' {' . pzarc_process_spacing( $value ) . ';}' . "\n" : null );
  }

  // *cough!* Hack. TODO: Fix it!
  function pzarc_style_margins( $class, $value ) {
    return ( ! pzarc_is_empty_vals( $value, array( 'units' ) ) ? $class . ' {' . pzarc_process_spacing( $value ) . ';}' . "\n" : null );
  }

  function pzarc_style_borders( $class, $value ) {
    return pzarc_process_borders( $class, $value ) . "\n";
  }

  function pzarc_style_borderradius( $class, $value ) {
    return pzarc_process_border_radius( $class, $value ) . "\n";
  }

  function pzarc_style_links( $class, $value ) {
    return pzarc_process_links( $class, $value, "\n" ) . "\n";
  }

  function pzarc_style_font( $class, $value ) {
    return pzarc_process_fonts( $class, $value ) . "\n";
  }

  function pzarc_style_css( $class, $value ) {
    // PHP doesn't like trim used inline
    $value = trim( $value );

    return ( ! empty( $value ) ? $class . $value : null );
  }

  if ( ! function_exists( 'pzifempty' ) ) {

    /**
     *
     * Checks value if empty which includes exists, and returns null or return value. Otherwise returns the value
     *
     * @param      $var
     * @param null $return
     *
     * @return null
     */
// Actually this doesn't work!!!! Coz PHP won't pass a var unchecked. So still get a warnign if not set.
    function pzifempty( $var, $return = null ) {
      return ( empty( $var ) ? $return : $var );
    }

  }

  function pzarc_term_title( $appendage, $terms ) {
    $term_list = '';
    foreach ( $terms->queries as $term_query ) {
      foreach ( $term_query[ 'terms' ] as $term_tag ) {
        $term_object = get_terms( $term_query[ 'taxonomy' ], array( 'slug' => $term_tag ) );
        $term_list .= ', ' . $term_object[ 0 ]->name;
      }
    }

    return $appendage . substr( $term_list, 2 );
  }

  function pzarc_check_googlefont( $properties ) {
    $redux_standard_fonts =
      array(
        "Arial, Helvetica, sans-serif"                         => "Arial, Helvetica, sans-serif",
        "'Arial Black', Gadget, sans-serif"                    => "'Arial Black', Gadget, sans-serif",
        "'Bookman Old Style', serif"                           => "'Bookman Old Style', serif",
        "'Comic Sans MS', cursive"                             => "'Comic Sans MS', cursive",
        "Courier, monospace"                                   => "Courier, monospace",
        "Garamond, serif"                                      => "Garamond, serif",
        "Georgia, serif"                                       => "Georgia, serif",
        "Impact, Charcoal, sans-serif"                         => "Impact, Charcoal, sans-serif",
        "'Lucida Console', Monaco, monospace"                  => "'Lucida Console', Monaco, monospace",
        "'Lucida Sans Unicode', 'Lucida Grande', sans-serif"   => "'Lucida Sans Unicode', 'Lucida Grande', sans-serif",
        "'MS Sans Serif', Geneva, sans-serif"                  => "'MS Sans Serif', Geneva, sans-serif",
        "'MS Serif', 'New York', sans-serif"                   => "'MS Serif', 'New York', sans-serif",
        "'Palatino Linotype', 'Book Antiqua', Palatino, serif" => "'Palatino Linotype', 'Book Antiqua', Palatino, serif",
        "Tahoma,Geneva, sans-serif"                            => "Tahoma, Geneva, sans-serif",
        "'Times New Roman', Times,serif"                       => "'Times New Roman', Times, serif",
        "'Trebuchet MS', Helvetica, sans-serif"                => "'Trebuchet MS', Helvetica, sans-serif",
        "Verdana, Geneva, sans-serif"                          => "Verdana, Geneva, sans-serif",
      );

    $return_val = '';
    if ( ! empty( $properties[ 'font-family' ] ) && ! in_array( $properties[ 'font-family' ], $redux_standard_fonts ) ) {
      $return_val = '@import url(//fonts.googleapis.com/css?family=' . str_replace( ' ', '+', $properties[ 'font-family' ] ) . ');';
    }

    return $return_val;
  }


  /**
   * @param      $arc_preset_data : json_decode($file_contents, true)
   * @param      $preset_name
   * @param      $process_type : styled or unstyled
   * @param null $alt_slug : Specify for codetically added blueprints
   * @param null $alt_title : Specify for codetically added blueprints
   */
  function pzarc_create_blueprint( $arc_preset_data, $preset_name, $process_type, $alt_slug = null, $alt_title = null ) {
    global $wpdb;

    /*
     * if you don't want current user to be the new post author,
     * then change next couple of lines to this: $new_post_author = $post->post_author;
     */
    $current_user    = wp_get_current_user();
    $new_post_author = $current_user->ID;

    /*
     * if post data exists, create the post duplicate
     */
    if ( ! empty( $preset_name ) ) {


      // Get the next slug name
      $args                  = array(
        'post_status'    => array( 'publish', 'draft' ),
        'post_type'      => 'arc-blueprints',
        'posts_per_page' => 1
      );
      $last_blueprint        = get_posts( $args );
      $next_id               = ( isset( $last_blueprint[ 0 ]->ID ) ? $last_blueprint[ 0 ]->ID + 1 : '1' );
      $preset[ 'post' ]      = json_decode( $arc_preset_data[ 'post' ] );
      $preset[ 'post_meta' ] = json_decode( $arc_preset_data[ 'meta' ], true );
      $new_slug              = sanitize_title( $preset[ 'post' ]->post_title ) . '-' . ( $next_id );

      /*
       * new post data array
       */
      $args = array(
        'comment_status' => $preset[ 'post' ]->comment_status,
        'ping_status'    => $preset[ 'post' ]->ping_status,
        'post_author'    => $new_post_author,
        'post_content'   => $preset[ 'post' ]->post_content,
        'post_excerpt'   => $preset[ 'post' ]->post_excerpt,
        'post_name'      => $alt_slug ? $alt_slug : $new_slug,
        'post_parent'    => $preset[ 'post' ]->post_parent,
        'post_password'  => $preset[ 'post' ]->post_password,
        'post_status'    => $alt_slug ? 'publish' : 'draft',
        'post_title'     => $alt_title ? $alt_title : 'A new ' . $preset[ 'bptype' ] . ' Blueprint using preset : ' . $preset[ 'post' ]->post_title,
        'post_type'      => $preset[ 'post' ]->post_type,
        'to_ping'        => $preset[ 'post' ]->to_ping,
        'menu_order'     => $preset[ 'post' ]->menu_order
      );

      /*
       * insert the post by wp_insert_post() function
       */
      $new_post_id = wp_insert_post( $args );

      /*
       * get all current post terms ad set them to the new post draft
       */
//      $taxonomies = get_object_taxonomies($pre->post_type); // returns array of taxonomy names for post type, ex array("category", "post_tag");
//      foreach ($taxonomies as $taxonomy) {
//        $post_terms = wp_get_object_terms($preset_name, $taxonomy, array('fields' => 'slugs'));
//        wp_set_object_terms($new_post_id, $post_terms, $taxonomy, false);
//      }

      /*
       * duplicate all post meta
       */
      if ( count( $preset[ 'post_meta' ] ) != 0 ) {
        $sql_query     = "INSERT INTO $wpdb->postmeta (post_id, meta_key, meta_value) ";
        $sql_query_sel = array();
        foreach ( $preset[ 'post_meta' ] as $meta_key => $value ) {
          if ( $meta_key === '_blueprints_short-name' ) {
            $meta_value = $value[ 0 ] . '-' . $new_post_id;
          } else {
            if ( $process_type === 'unstyled' ) {
              // Just done it this way for speed.
              if ( strpos( $meta_key, '_styling_' ) === false ) {
                $meta_value = addslashes( $value[ 0 ] );
              }

            } else {
              $meta_value = addslashes( $value[ 0 ] );
            }
          }

          $sql_query_sel[ ] = "SELECT $new_post_id, '$meta_key', '$meta_value'";
        }
        $sql_query .= implode( " UNION ALL ", $sql_query_sel );
        $wpdb->query( $sql_query );
      }


      /*
       * finally, redirect to the edit post screen for the new draft
       */
      if ( ! $alt_slug ) {
        wp_redirect( admin_url( 'post.php?action=edit&post=' . $new_post_id ) );
        exit;
      }
//      } else {
//        wp_redirect(admin_url('edit.php?post_type=' . $preset[ 'post' ]->post_type));
//      }

    } else {
      wp_die( 'Post creation failed, could not find original post: ' . $preset_name );
    }

  }

  function example_import() {

    //myfile.txt is a txt file containing the data generated from the Blueprint export
    $bpexpfile = 'http://mysite.com/path/to/myfile.txt';

    $ch = curl_init( $bpexpfile );
    curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
    $file_contents = curl_exec( $ch );
    curl_close( $ch );

    $arc_preset_data = json_decode( $file_contents, true );

    $preset_name  = 'anything'; // This is not relevant for code generated blueprints; however, it cannot be blank
    $process_type = 'styled'; //This can be styled or unstyled
    $alt_slug     = 'SLUGMNAME FOR NEW BLUEPRINT';
    $alt_title    = 'TITLE FOR NEW BLUEPRINT';

    pzarc_create_blueprint( $arc_preset_data, $preset_name, $process_type, $alt_slug, $alt_title );

  }