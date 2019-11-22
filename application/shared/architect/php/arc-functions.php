<?php

  if ( ! function_exists( 'd' ) ) {
    function d() {
      $vars = func_get_args();
      foreach ( $vars as $var ) {
        var_dump( $var );
      }
    }
  }
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
      $line = $btr[0]['line'];
      $file = basename( $btr[0]['file'] );
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
    $list = '';
    if ( is_array( $tax ) ) {  // 1.17.0 Sometimes $tax is not an array causing warning. Need to find out when
      $count = count( $tax );
      $i     = 1;
      if ( is_array( $tax ) ) {
        foreach ( $tax as $key => $value ) {
          $list .= $prefix . $value->slug . $suffix . ( $i ++ == $count ? '' : $separator );
        }
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
      $return_array[ $key ] = $array[ $key ][0];
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

    return $result[0];

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
    $units                = isset( $_architect_options['architect_typography_units'] ) ? $_architect_options['architect_typography_units'] : 'px';
    $extra_fonts          = file_exists( content_url( 'extra-fonts.css' ) ) ? content_url( 'extra-fonts.css' ) : NULL;
    $disable_font_family  = isset( $_architect_options['architect_disable_fonts'] ) ? $_architect_options['architect_disable_fonts'] : FALSE;
    $disable_google_fonts = isset( $_architect_options['architect_disable_google_fonts'] ) ? $_architect_options['architect_disable_google_fonts'] : FALSE;

    $return_array = array(
        'title'           => __( 'Typography', 'pzarchitect' ),
        'id'              => $id,
        'subtitle'        => __( 'You can change the typography units to px,em or rem in Architect > Options', 'pzarchitect' ),
        'desc'            => __( 'Tip: If you set the typography line height to less than 3, Architect will use it as a multiplier of the font size.e.g. line-height:1.5.', 'pzarchitect' ),
        //       'output'          => $selectors,
        'type'            => 'typography',
        'text-decoration' => TRUE,
        'font-variant'    => TRUE,
        'text-transform'  => TRUE,
        'font-family'     => ! $disable_font_family,
        'font-size'       => TRUE,
        'font-weight'     => TRUE,
        'font-style'      => TRUE,
        'font-backup'     => ! $disable_font_family,
        'google'          => ! $disable_font_family && ! $disable_google_fonts,
        'subsets'         => FALSE,
        'custom_fonts'    => FALSE,
        'text-align'      => TRUE,
        //'text-shadow'       => false, // false
        'color'           => TRUE,
        'preview'         => TRUE,
        'line-height'     => TRUE,
        'word-spacing'    => TRUE,
        'letter-spacing'  => TRUE,
        'units'           => $units,
        'default'         => $defaults,
        'ext-font-css'    => $extra_fonts,
    );
    foreach ( $return_array as $k => $v ) {
      if ( in_array( $k, $exclude ) ) {
        $return_array[ $k ] = FALSE;
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
  function pzarc_redux_bg( $id, $selectors = NULL, $defaults = array( 'color' => '' ) ) {
    return array(
        'title'                 => __( 'Background', 'pzarchitect' ),
        'id'                    => $id,
        //        'output'                => $selectors,
        //        'compiler'              => $selectors,
        'type'                  => 'spectrum',
        'mode'                  => 'background-color',
        'background-image'      => FALSE,
        'background-repeat'     => FALSE,
        'background-size'       => FALSE,
        'background-attachment' => FALSE,
        'background-position'   => FALSE,
        'preview'               => FALSE,
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
        'units'   => array(
            '%',
            'px',
            'em',
        ),
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
    //   var_dump($id);
    return array(
        'title'   => __( 'Margins', 'pzarchitect' ),
        'id'      => $id,
        //        'output'  => $selectors,
        'mode'    => 'margin',
        'type'    => 'spacing',
        'units'   => array(
            '%',
            'px',
            'em',
        ),
        'default' => $defaults,
        'top'     => ( strpos( $limits, 't' ) !== FALSE ),
        'bottom'  => ( strpos( $limits, 'b' ) !== FALSE ),
        'left'    => ( strpos( $limits, 'l' ) !== FALSE ),
        'right'   => ( strpos( $limits, 'r' ) !== FALSE ),
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
    return array(
        'title'   => __( 'Links', 'pzarchitect' ),
        'id'      => $id,
        'type'    => 'links',
        //            'output'  => $selectors,
        'default' => $defaults,
    );

  }

  /**
   * @param        $id
   * @param null   $selectors
   * @param array  $defaults
   * @param string $subtitle
   *
   * @return array
   */
  function pzarc_redux_custom_css( $id, $selectors = NULL, $defaults = array(), $subtitle = '' ) {
    return array(
        'id'       => $id,
        'type'     => 'ace_editor',
        'title'    => __( 'Custom CSS', 'pzarchitect' ),
        'mode'     => 'css',
        'options'  => array( 'minLines' => 25 ),
        'default'  => $defaults,
        'subtitle' => __( $subtitle, 'pzarchitect' ),
    );
  }

//  function pzarc_get_taxterms($pzarc_taxes = null) {
//    global $pzarc_masonry_filter_taxes,$tax_slug;
//    var_dump($pzarc_masonry_filter_taxes,$tax_slug);
//    $return_terms =  array();
////    $pzarc_taxes = $pzarc_taxes?$pzarc_taxes:$pzarc_masonry_filter_taxes;
////    if ($pzarc_taxes) {
////      foreach ($pzarc_taxes as $pzarc_tax) {
//        $pzarc_terms = get_terms($tax_slug);
//        if (!empty($pzarc_terms)) {
//          foreach ($pzarc_terms as $k => $v) {
//            $return_terms[ ucwords(str_replace(array('-','_'),' ',$tax_slug)) ][ $v->term_id ] = $v->name;
//          }
//        }
////      }
////    }
//    return $return_terms;
//  }

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
        'all'     => FALSE,
        //      'output'  => $selectors,
        'default' => $defaults,
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
        'all'      => FALSE,
        'style'    => FALSE,
        'color'    => FALSE,
        //       'output'  => $selectors,
        'default'  => $defaults,
    );
  }

  /**
   * pzarc_set_defaults
   *
   * This doesn't need to return anything because it is populating the global variable
   *
   */
  function pzarc_set_defaults( $a = FALSE, $b = FALSE ) {

    pzdb( 'top get defaults' );
    // gah! Why don't we jsut save this in a options var?!
    // TODO: Do we really need to call this on the front end??!!

    // TODO: Remove this once Dovy fixes MB defaults... or maybe not...
    // Actually, $_architect doesn't populate if it's not here
    require_once PZARC_PLUGIN_APP_PATH . '/admin/php/class_arc_blueprints_designer.php';
    $blueprints = new arc_Blueprints_Designer( 'defaults' );

//    $panels     = new arc_Panels_Layouts('defaults');

    global $_architect;
    global $_architect_options;
    if ( empty( $_architect_options ) ) {
      $_architect_options = get_option( '_architect_options' );
    }

    if ( empty( $_architect ) ) {
      $_architect = get_option( '_architect' );
    }

    if ( ! isset( $_architect['defaults'] ) ) {
      /**
       * BLUEPRINTS
       *
       */
      pzdb( 'pre get blueprints defaults' );
//    if ($use_cache) {
//
//    } else {

      $_architect['defaults']['blueprints'] = ( ! isset( $_architect['defaults']['blueprints'] ) ? array() : $_architect['defaults']['blueprints'] );
      $bpd                                  = array();

      $bpd['layout_general']                                             = $blueprints->mb_general( $_architect['defaults']['blueprints'], TRUE );
      $_architect['defaults']['blueprints']['_blueprint_layout_general'] = $bpd['layout_general'][0]['sections'];

      $bpd['design']                                             = $blueprints->mb_blueprint_design( $_architect['defaults']['blueprints'], TRUE );
      $_architect['defaults']['blueprints']['_blueprint_design'] = $bpd['design'][0]['sections'];

      $bpd['field_types']                                       = $blueprints->mb_layouts( $_architect['defaults']['blueprints'], TRUE );
      $_architect['defaults']['blueprints']['_blueprint_types'] = $bpd['field_types'][0]['sections'];

      $bpd['source']                                             = $blueprints->mb_sources( $_architect['defaults']['blueprints'], TRUE );
      $_architect['defaults']['blueprints']['_contents_metabox'] = $bpd['source'][0]['sections'];

      // Apply the defaults
      foreach ( $_architect['defaults']['blueprints'] as $key1 => $value1 ) {
        if ( ! empty( $value1 ) ) {
          foreach ( $value1 as $key2 => $value2 ) {
            foreach ( $value2 as $key3 => $fields ) {
              if ( is_array( $fields ) ) {
                foreach ( $fields as $key4 => $field ) {
                  if ( isset( $field['id'] ) ) {
                    $_architect['defaults']['_blueprints'][ $field['id'] ] = ( empty( $field['default'] ) ? '' : $field['default'] );
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
      $_architect['defaults']['panels'] = ( ! isset( $_architect['defaults']['panels'] ) ? array() : $_architect['defaults']['panels'] );
//var_dump($_architect);
//      $pzarc_panel_general_settings = $blueprints->pzarc_panel_general_settings($_architect[ 'defaults' ][ 'panels' ], true);
      $pand             = array();
      $pand['panels']   = $blueprints->mb_panels( $_architect['defaults']['panels'], TRUE );
      $pand['titles']   = $blueprints->mb_titles( $_architect['defaults']['panels'], TRUE );
      $pand['meta']     = $blueprints->mb_meta( $_architect['defaults']['panels'], TRUE );
      $pand['features'] = $blueprints->mb_features( $_architect['defaults']['panels'], TRUE );
      $pand['body']     = $blueprints->mb_body_excerpt( $_architect['defaults']['panels'], TRUE );
      if ( ( function_exists( 'arc_fs' ) && arc_fs()->is__premium_only() ) || defined( 'PZARC_PRO' ) ) {
        $pand['customfields'] = $blueprints->mb_customfields__premium_only( $_architect['defaults']['panels'], TRUE );
      } else {
        $pand['customfields'][0]['sectons'] = array();
      }
      //     $_architect[ 'defaults' ][ 'panels' ][ '_panel_general_settings' ] = $pzarc_panel_general_settings[ 0 ][ 'sections' ];
      $_architect['defaults']['panels']['_panels_design']       = $pand['panels'][0]['sections'];
      $_architect['defaults']['panels']['_panels_titles']       = $pand['titles'][0]['sections'];
      $_architect['defaults']['panels']['_panels_meta']         = $pand['meta'][0]['sections'];
      $_architect['defaults']['panels']['_panels_features']     = $pand['features'][0]['sections'];
      $_architect['defaults']['panels']['_panels_body']         = $pand['body'][0]['sections'];
      $_architect['defaults']['panels']['_panels_customfields'] = $pand['customfields'][0]['sections'];

      foreach ( $_architect['defaults']['panels'] as $key1 => $value1 ) {
        if ( ! empty( $value1 ) ) {
          foreach ( $value1 as $key2 => $value2 ) {
            foreach ( $value2 as $key3 => $fields ) {
              if ( is_array( $fields ) ) {
                foreach ( $fields as $key4 => $field ) {
                  if ( isset( $field['id'] ) ) {
                    $_architect['defaults']['_blueprints'][ $field['id'] ] = ( empty( $field['default'] ) ? '' : $field['default'] );
                  }
                }
              }
            }
          }
        }
      }
      pzdb( 'bottom get defaults' );

      delete_option( '_architect_defaults' );
      add_option( '_architect_defaults', maybe_serialize( $_architect['defaults']['_blueprints'] ) );
      //     var_dump(get_option('_architect_defaults'));
      //  Unset the temporary blueprints field
      // ???
      unset( $_architect['defaults']['blueprints'] );
//      //  Unset the temporary panels field
      unset( $_architect['defaults']['panels'] );
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
      if ( isset( $settings[0] ) ) {
        foreach ( $v as $k2 => $v2 ) {
          $returna[] = $v2;
        }
      } else {
        $returna[] = $v;
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
   * This is explicit as it's used in a callback which doesn't allow parameters
   *
   * @return array|null
   */
  function pzarc_get_gp_galleries() {
    $post_types = get_post_types();
    if ( ! isset( $post_types['gp_gallery'] ) ) {
      return NULL;
    }
    // Don't need to check for GPlus class coz we add the post type
    // Get GalleryPlus galleries
    $args    = array(
        'post_type'   => 'gp_gallery',
        'numberposts' => - 1,
        'post_status' => NULL,
        'parent_ID'   => NULL,
    );
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
    $results = array();

    $args   = array(
        'post_type'   => array(
            'post',
            'page',
        ),
        'numberposts' => - 1,
        'post_status' => 'publish',
        'parent_ID'   => NULL,
    );
    $albums = get_posts( $args );
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
  function pzarc_get_authors( $inc_all = TRUE, $min_level = 1 ) {
    // user_level 1 = contributor
// Get authors
    $userslist = get_users();
    $authors   = array();
    if ( $inc_all ) {
      $authors[0] = 'All';
    }
    foreach ( $userslist as $author ) {
      if ( get_the_author_meta( 'user_level', $author->ID ) >= $min_level ) {
        $authors[ $author->ID ] = $author->display_name;
      }
    }

    return $authors;
  }

  function pzarc_get_post_types() {
    $all_post_types = array(
        'post' => 'Posts',
        'page' => 'Pages',
    );
    $post_types     = pzarc_get_custom_post_types();

    return array_merge( $all_post_types, $post_types );
  }

  function pzarc_get_custom_post_types() {
    $pzarc_cpts = ( get_post_types( array(
        '_builtin' => FALSE,
        'public'   => TRUE,
    ), 'objects' ) );
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
  function pzarc_get_posts_in_post_type( $pzarc_post_type = 'arc-blueprints', $use_shortname = FALSE, $override_admin = FALSE, $append_post_type = TRUE, $append_bp_type = TRUE ) {
//    // No point doing this if not on a screen that can use it.
// Except it didn't work!
//    if (!function_exists('get_current_screen')) {
//      return array();
//    }
//    // No point doing this if not on a screen that can use it.
//    var_Dump($override_admin,!$override_admin, !is_admin(),$pzarc_post_type);
    if ( ! is_admin() && ! $override_admin ) {
      return array();
    }
    $args                 = array(
        'posts_per_page'   => - 1,
        'orderby'          => 'post_title',
        'order'            => 'ASC',
        'post_type'        => $pzarc_post_type,
        'post_status'      => 'publish',
        'suppress_filters' => TRUE,
    );
    $pzarc_post_types_obj = get_posts( $args );
    $pzarc_post_type_list = array();
    // d( $pzarc_post_type );

    foreach ( $pzarc_post_types_obj as $pzarc_post_type_obj ) {
      if ( $use_shortname === TRUE ) {

        if ( $pzarc_post_type === 'arc-blueprints' ) {
          $use_key = get_post_meta( $pzarc_post_type_obj->ID, '_blueprints_short-name', TRUE );
        } elseif ( $pzarc_post_type === 'arc-panels' ) {
          $use_key = get_post_meta( $pzarc_post_type_obj->ID, '_panels_settings_short-name', TRUE );
        } else {
          $use_key = $pzarc_post_type_obj->post_name;
        }

      } elseif ( $use_shortname === 'id-slug' ) {
        $use_key = $pzarc_post_type_obj->ID . ':' . $pzarc_post_type_obj->post_name;
      } elseif ( $use_shortname === 'id' ) {
        $use_key = $pzarc_post_type_obj->ID;
      } else {
        $use_key = $pzarc_post_type_obj->post_name;
      }

      $pz_post_type = ucwords( get_post_meta( $pzarc_post_type_obj->ID, '_blueprints_content-source', TRUE ) );
      $pz_post_type = $pz_post_type === 'Cpt' ? 'Custom' : $pz_post_type;

      $pz_bp_type = ucwords( get_post_meta( $pzarc_post_type_obj->ID, '_blueprints_section-0-layout-mode', TRUE ) );
      $pz_bp_type = ( $pz_bp_type === 'Basic' ? 'Grid' : $pz_bp_type );

      $pzarc_post_type_list[ $use_key ] = ( $append_bp_type ? ( ! empty( $pz_bp_type ) ? $pz_bp_type : 'Grid' ) . ': ' : '' );
      $pzarc_post_type_list[ $use_key ] .= $pzarc_post_type_obj->post_title;
      $pzarc_post_type_list[ $use_key ] .= ( $append_post_type ? '  [' . ( $pz_post_type ? $pz_post_type : 'Defaults' ) . ']' : '' );

      asort( $pzarc_post_type_list );
    }

    return $pzarc_post_type_list;
  }

// TODO: This is a sorta duplicate of pzarc_get_posts_in_type. Fix it one day.
  /**
   * @param bool $inc_post_id
   *
   * @return array
   */
  function pzarc_get_blueprints( $inc_post_id = FALSE ) {
    $query_options    = array(
        'post_type'      => 'arc-blueprints',
        'meta_key'       => '_blueprints_short-name',
        'posts_per_page' => '-1',
    );
    $blueprints_query = new WP_Query( $query_options );
    $pzarc_blueprints = array();
    while ( $blueprints_query->have_posts() ) {
      $blueprints_query->next_post();
      $the_panel_meta = get_post_meta( $blueprints_query->post->ID );
      $bpid           = $the_panel_meta['_blueprints_short-name'][0] . ( $inc_post_id ? '##' . $blueprints_query->post->ID : '' );
      // This caused an error with the WooCommerce 2.3
      //     $pzarc_return[ $bpid ] = get_the_title($blueprints_query->post->ID);
      $pzarc_blueprints[ $bpid ] = $blueprints_query->post->post_title;
    };
    asort( $pzarc_blueprints );
    wp_reset_postdata();

    return $pzarc_blueprints;
  }

  /**
   * @param $source_arr
   * @param $selected
   */
  function pzarc_array_to_options_list( $source_arr, $selected ) {
    foreach ( $source_arr as $key => $value ) {
      echo '<option value="' . esc_attr( $key ) . '" ' . ( $selected == $key ? 'selected' : NULL ) . '>' . esc_attr( $value ) . '</option>';
    }
  }


  function pzarc_starts_with( $needles = array(), $haystack = '', $position = 0 ) {
    $success = FALSE;
    $needles = ! is_array( $needles ) ? array( $needles ) : $needles;
    foreach ( $needles as $needle ) {
      if ( strpos( $haystack, $needle ) === $position ) {
        $success = TRUE;
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

  function pzarc_flatten_wpinfo( $array_in, $strip = NULL ) {
    $array_out = array();
    if ( ! empty( $array_in ) ) {
      foreach ( $array_in as $key => $value ) {
        if ( $key == '_edit_lock' || $key == '_edit_last' || strpos( $key, $strip ) !== FALSE ) {
          continue;
        }
        if ( is_array( $value ) ) {
          $array_out[ $key ] = $value;
        }
        $array_out[ $key ] = maybe_unserialize( $value[0] );
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
      if ( taxonomy_exists( $matches[2][ $i - 1 ] ) ) {
        $post_tax_terms[] = array( $matches[2][ $i - 1 ] => get_the_term_list( $post_id, $matches[2][ $i - 1 ], NULL, ', ', NULL ) );
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
    // We don't want transients used for admins since they may be testing new pzarc_settings - which won't take!
    if ( ! empty( $_architect_options['architect_enable_query_cache'] ) && ( ! current_user_can( 'manage_options' ) || ! current_user_can( 'edit_others_pages' ) ) && FALSE === ( $post_id = get_transient( 'pzarc_post_name_to_id_' . $post_name ) ) ) {
      // It wasn't there, so regenerate the data and save the transient
      $post_id = $wpdb->get_var( "SELECT ID FROM $wpdb->posts WHERE post_name = '" . $post_name . "'" );
      set_transient( 'pzarc_post_name_to_id_' . $post_name, $post_id, PZARC_TRANSIENTS_KEEP );
    } elseif ( current_user_can( 'edit_others_pages' ) || empty( $_architect_options['architect_enable_query_cache'] ) ) {
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

    switch ( TRUE ) {
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
    $minify = str_replace( array(
        "\r\n",
        "\r",
        "\n",
        "\t",
    ), '', $minify );
    $minify = str_replace( array(
        '  ',
        '    ',
        '    ',
    ), ' ', $minify );

    return $minify;
  }

  add_shortcode( 'pztestsc', 'pzarc_test_shortcode' );
  /**
   * @param $atts
   *
   * @return string
   */
  function pzarc_test_shortcode( $atts = '' ) {
    global $pzarc_post_id;

    return 'Shortcode test : ' . str_replace( '%id%', $pzarc_post_id, ( is_array( $atts ) ? implode( ',', $atts ) : $atts ) );
  }

//  /**
//   * @param $values
//   *
//   * @return array
//   */
//  function pzarc_maths_sum( $values ) {
//    $result = 0;
//    $vtype  = '';
//    switch ( true ) {
//      case strpos( $values[ 1 ], 'px' ):
//        $vtype = 'px';
//        break;
//      case strpos( $values[ 1 ], 'rem' ):
//        $vtype = 'rem';
//        break;
//      case strpos( $values[ 1 ], '%' ):
//        $vtype = '%';
//        break;
//      case strpos( $values[ 1 ], 'em' ):
//        $vtype = 'em';
//        break;
//    }
//    foreach ( $values as $v ) {
//      $vclean = str_replace( array( '%', 'px', 'em', 'rem' ), '', $v );
//      $result += $vclean;
//    }
//
//    return array( 'result' => $result, 'type' => $vtype );
//  }

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
    if ( isset( $atts[0] ) ) {
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
              if ( strpos( $font, ' ' ) > 0 && strpos( $font, '\'' ) === FALSE ) {
                $fonts .= '"' . $font . '"';
              } else {
                $fonts .= $font;
              }

              if ( $key != count( $ff ) - 1 ) {
                $fonts .= ', ';
              }
            }
            $font_backup = ( ! empty( $properties['font-backup'] ) ? ', ' . str_replace( "'", '"', $properties['font-backup'] ) : '' );
            $filler      .= $k . ':' . $fonts . $font_backup . ';';
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

    return ( ! empty( $filler ) ? $classes . '{' . $filler . '}' : NULL );
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
          $iszero      = ( $value === 0 || $value === '0' );
          $isnotset    = $value === '';
          $propval     = $key . ':' . $value;
          $propzero    = $key . ':0;';
          $spacing_css .= ( $iszero ? $propzero : ( $isnotset ? NULL : $propval . ';' ) );
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
        'border-color'  => '',
    );

    if ( ! empty( $properties ) && $properties != $dodgy_values ) {

      $borders_css .= ( ! empty( $properties['border-top'] ) ? 'border-top:' . $properties['border-top'] : '' );
      $borders_css .= ( ! empty( $properties['border-top'] ) && $properties['border-top'] !== '0' ? ' ' . $properties['border-style'] . ' ' . $properties['border-color'] . ';' : ';' );
      $borders_css .= ( ! empty( $properties['border-right'] ) ? 'border-right:' . $properties['border-right'] : '' );
      $borders_css .= ( ! empty( $properties['border-right'] ) && $properties['border-right'] !== '0' ? ' ' . $properties['border-style'] . ' ' . $properties['border-color'] . ';' : ';' );
      $borders_css .= ( ! empty( $properties['border-bottom'] ) ? 'border-bottom:' . $properties['border-bottom'] : '' );
      $borders_css .= ( ! empty( $properties['border-bottom'] ) && $properties['border-bottom'] !== '0' ? ' ' . $properties['border-style'] . ' ' . $properties['border-color'] . ';' : ';' );
      $borders_css .= ( ! empty( $properties['border-left'] ) ? 'border-left:' . $properties['border-left'] : '' );
      $borders_css .= ( ! empty( $properties['border-left'] ) && $properties['border-left'] !== '0' ? ' ' . $properties['border-style'] . ' ' . $properties['border-color'] . ';' : ';' );

      return $classes . '{' . $borders_css . '}';
    } else {
      return NULL;
    }
  }

  function pzarc_process_border_radius( $classes, $properties ) {
    $borders_css = '';
    if ( ! empty( $properties ) ) {

      $borders_css .= ( ! empty( $properties['border-top'] ) ? 'border-top-left-radius:' . $properties['border-top'] . ';' : '' );
      $borders_css .= ( ! empty( $properties['border-right'] ) ? 'border-top-right-radius:' . $properties['border-right'] . ';' : '' );
      $borders_css .= ( ! empty( $properties['border-bottom'] ) ? 'border-bottom-left-radius:' . $properties['border-bottom'] . ';' : '' );
      $borders_css .= ( ! empty( $properties['border-left'] ) ? 'border-bottom-right-radius:' . $properties['border-left'] . ';' : '' );

      return $classes . '{' . $borders_css . '}';
    } else {
      return NULL;
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
      if ( ! empty( $properties['regular'] ) || ( ! empty( $properties['regular-deco'] ) && strtolower( $properties['regular-deco'] ) !== 'default' ) ) {
        $links_css .= $classes . ' a {';
        $links_css .= ( ! empty( $properties['regular'] ) ? 'color:' . $properties['regular'] . ';' : '' );
        $links_css .= ( strtolower( $properties['regular-deco'] ) !== 'default' ? 'text-decoration:' . strtolower( $properties['regular-deco'] ) . ';' : '' );
        $links_css .= '}' . $nl;
      }

      if ( ! empty( $properties['hover'] ) || ( ! empty( $properties['hover-deco'] ) && strtolower( $properties['hover-deco'] ) !== 'default' ) ) {
        $links_css .= $classes . ' a:hover {';
        $links_css .= ( ! empty( $properties['hover'] ) ? 'color:' . $properties['hover'] . ';' : '' );
        $links_css .= ( strtolower( $properties['hover-deco'] ) !== 'default' ? 'text-decoration:' . strtolower( $properties['hover-deco'] ) . ';' : '' );
        $links_css .= '}' . $nl;
      }

      if ( ! empty( $properties['active'] ) || ( ! empty( $properties['active-deco'] ) && strtolower( $properties['active-deco'] ) !== 'default' ) ) {
        $links_css .= $classes . ' a:active {';
        $links_css .= ( ! empty( $properties['active'] ) ? 'color:' . $properties['active'] . ';' : '' );
        $links_css .= ( strtolower( $properties['active-deco'] ) !== 'default' ? 'text-decoration:' . strtolower( $properties['active-deco'] ) . ';' : '' );
        $links_css .= '}' . $nl;
      }

      if ( ! empty( $properties['visited'] ) || ( ! empty( $properties['visited-deco'] ) && strtolower( $properties['visited-deco'] ) !== 'default' ) ) {
        $links_css .= $classes . ' a:visited {';
        $links_css .= ( ! empty( $properties['visited'] ) ? 'color:' . $properties['visited'] . ';' : '' );
        $links_css .= ( strtolower( $properties['visited-deco'] ) !== 'default' ? 'text-decoration:' . strtolower( $properties['visited-deco'] ) . ';' : '' );
        $links_css .= '}' . $nl;
      }

      return $links_css;
    } else {
      return NULL;
    }
  }

  function pzarc_process_background( $classes, $properties ) {
    // Currently not used
    $pzarc_bg_css = '';
    // Could come from one of two methods
    if ( ! empty( $properties ) ) {
      if ( ! empty( $properties['color'] ) ) {
        $pzarc_bg_css .= $classes . ' {background-color:' . $properties['color'] . ';}';
      }
      if ( ! empty( $properties['background-color'] ) ) {
        $pzarc_bg_css .= $classes . ' {background-color:' . $properties['background-color'] . ';}';
      }

      return $pzarc_bg_css;
    } else {
      return NULL;
    }
  }


  /**
   * @param $properties
   * @param $exclude
   *
   * @return bool
   */
  function pzarc_is_empty_vals( $properties, $exclude ) {

    $is_empty = TRUE;
    if ( is_array( $properties ) ) {
      foreach ( $properties as $key => $value ) {
//    var_dump(!in_array($key,$exclude) , !empty($value));
        if ( ! in_array( $key, $exclude ) && strlen( $value ) > 0 ) {
          $is_empty = FALSE;
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
   * @param $parent_class
   *
   * @return mixed|string
   */
  function pzarc_get_styling( $source, $keys, $value, $parent_class, $pkeys_classes = '' ) {

    // generate correct whosit
    $pzarc_func = 'pzarc_style_' . $keys['style'];
    $pzarc_css  = '';
    foreach ( $keys['classes'] as $class_str ) {
      $class_arr = explode( ',', $class_str );
      foreach ( $class_arr as $class ) {
        $pzarc_css .= ( function_exists( $pzarc_func ) ? call_user_func( $pzarc_func, $parent_class . ' ' . $class, $value ) : '' );
        if ( $pzarc_func == 'pzarc_style_padding' ) {
          //     var_dump($pzarc_css);
        }
        if ( ! function_exists( $pzarc_func ) ) {
          //print 'Missing function ' . $pzarc_func;
          pzdb( $pzarc_func );
        }
      }
    }

    return $pzarc_css;
  }

  function pzarc_style_background( $class, $value ) {

    return ( ! empty( $value['color'] ) ? $class . ' {background-color:' . $value['color'] . ';}' . "\n" : NULL );

  }

  function pzarc_style_padding( $class, $value ) {
//    var_Dump(pzarc_is_empty_vals($value, array('units')),$value);
    //   var_dump($class,$value);
    return ( ! pzarc_is_empty_vals( $value, array( 'units' ) ) ? $class . ' {' . pzarc_process_spacing( $value ) . ';}' . "\n" : NULL );
  }

  function pzarc_style_margin( $class, $value ) {
    return ( ! pzarc_is_empty_vals( $value, array( 'units' ) ) ? $class . ' {' . pzarc_process_spacing( $value ) . ';}' . "\n" : NULL );
  }

// *cough!* Hack. TODO: Fix it!
  function pzarc_style_margins( $class, $value ) {
    return ( ! pzarc_is_empty_vals( $value, array( 'units' ) ) ? $class . ' {' . pzarc_process_spacing( $value ) . ';}' . "\n" : NULL );
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

    return ( ! empty( $value ) ? $class . $value : NULL );
  }

  /**
   * @param $arc_needle
   * @param $arc_replace
   * @param $arc_haystack
   *
   * @return mixed
   */
  function pzarc_style_custom_css( $arc_needle, $arc_replace, $arc_haystack ) {
    return str_replace( $arc_needle, $arc_replace, $arc_haystack );
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
    function pzifempty( $var, $return = NULL ) {
      return ( empty( $var ) ? $return : $var );
    }

  }

  function pzarc_term_title( $appendage, $terms ) {
    $term_list = '';
    foreach ( $terms->queries as $term_query ) {
      foreach ( $term_query['terms'] as $term_tag ) {
        $term_object = get_terms( $term_query['taxonomy'], array( 'slug' => $term_tag ) );
        $term_list   .= ', ' . $term_object[0]->name;
      }
    }

    return $appendage . substr( $term_list, 2 );
  }

  function pzarc_check_googlefont( $properties ) {
    $redux_standard_fonts = array(
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
    if ( ! empty( $properties['font-family'] ) && ! in_array( $properties['font-family'], $redux_standard_fonts ) ) {
      $return_val = '@import url(//fonts.googleapis.com/css?family=' . str_replace( ' ', '+', $properties['font-family'] ) . ');';
    }

    return $return_val;
  }


  /**
   * @param      $arc_preset_data : json_decode($file_contents, true)
   * @param      $preset_name
   * @param      $process_type    : styled or unstyled
   * @param null $alt_slug        : Specify for codetically added blueprints
   * @param null $alt_title       : Specify for codetically added blueprints
   */
  function pzarc_create_blueprint( $arc_preset_data, $preset_name, $process_type, $alt_slug = NULL, $alt_title = NULL, $unique_shortname = TRUE ) {
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
      $args                = array(
          'post_status'    => array(
              'publish',
              'draft',
          ),
          'post_type'      => 'arc-blueprints',
          'posts_per_page' => 1,
      );
      $last_blueprint      = get_posts( $args );
      $next_id             = ( isset( $last_blueprint[0]->ID ) ? $last_blueprint[0]->ID + 1 : '1' );
      $preset['post']      = json_decode( $arc_preset_data['post'] );
      $preset['post_meta'] = json_decode( $arc_preset_data['meta'], TRUE );
      $new_slug            = sanitize_title( $preset['post']->post_title ) . '-' . ( $next_id );

      /*
         * new post data array
         */
      $args = array(
          'comment_status' => $preset['post']->comment_status,
          'ping_status'    => $preset['post']->ping_status,
          'post_author'    => $new_post_author,
          'post_content'   => $preset['post']->post_content,
          'post_excerpt'   => $preset['post']->post_excerpt,
          'post_name'      => $alt_slug ? $alt_slug : $new_slug,
          'parent_ID'      => $preset['post']->post_parent,
          'post_password'  => $preset['post']->post_password,
          'post_status'    => $alt_slug ? 'publish' : 'draft',
          'post_title'     => $alt_title ? $alt_title : '(New) ' . $preset['post']->post_title,
          'post_type'      => $preset['post']->post_type,
          'to_ping'        => $preset['post']->to_ping,
          'menu_order'     => $preset['post']->menu_order,
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
      if ( count( $preset['post_meta'] ) != 0 ) {
        $sql_query     = "INSERT INTO $wpdb->postmeta (post_id, meta_key, meta_value) ";
        $sql_query_sel = array();
        foreach ( $preset['post_meta'] as $meta_key => $value ) {
          if ( $meta_key === '_blueprints_short-name' ) {
            $meta_value = $value[0] . ( $unique_shortname ? '-' . $new_post_id : '' );
          } else {
            if ( $process_type === 'unstyled' ) {
              // Just done it this way for speed.
              if ( strpos( $meta_key, '_styling_' ) === FALSE ) {
                $meta_value = addslashes( $value[0] );
              }

            } else {
              $meta_value = addslashes( $value[0] );
            }
          }

          $sql_query_sel[] = "SELECT $new_post_id, '$meta_key', '$meta_value'";
        }
        $sql_query .= implode( " UNION ALL ", $sql_query_sel );
        $wpdb->query( $sql_query );
      }


      /*
       * finally, redirect to the edit post screen for the new draft
       */
      if ( ! $alt_slug ) {
        $pazrc_screen_id = NULL;
        if ( function_exists( 'get_current_screen' ) ) {
          $pzarc_current_screen = get_current_screen();
          $pazrc_screen_id      = $pzarc_current_screen->base;
        }
        if ( $pazrc_screen_id === 'architect_page_pzarc_tools' ) {
          echo '<div id="message" class="updated notice notice-success"><p>Edit new Blueprint: <a href="' . admin_url( 'post.php?action=edit&post=' . $new_post_id ) . '">' . $args['post_title'] . '</a></p></div>';
        } else {
          wp_safe_redirect( admin_url( 'post.php?action=edit&post=' . $new_post_id ) );
        }
        exit;
      }

    } else {
      wp_die( 'Post creation failed, could not find original post: ' . $preset_name );
    }

  }

  function example_import() {

    //myfile.txt is a txt file containing the data generated from the Blueprint export
    $bpexpfile = 'http://mysite.com/path/to/myfile.txt';

    $ch = curl_init( $bpexpfile );
    curl_setopt( $ch, CURLOPT_RETURNTRANSFER, TRUE );
    $file_contents = curl_exec( $ch );
    curl_close( $ch );

    $arc_preset_data = json_decode( $file_contents, TRUE );

    $preset_name  = 'anything'; // This is not relevant for code generated blueprints; however, it cannot be blank
    $process_type = 'styled'; //This can be styled or unstyled
    $alt_slug     = 'SLUGMNAME FOR NEW BLUEPRINT';
    $alt_title    = 'TITLE FOR NEW BLUEPRINT';

    pzarc_create_blueprint( $arc_preset_data, $preset_name, $process_type, $alt_slug, $alt_title );

  }

  function pzarc_rebuild() {
    if ( ! is_admin() ) {
      return;
    }
    // This doesn't seem to work properly when upgrading, so might pull it for now, since it's probably better to use what is already there
    /** Build CSS cache */
    $pzarc_cssblueprint_cache = maybe_unserialize( get_option( 'pzarc_css' ) );

    if ( ! $pzarc_cssblueprint_cache ) {
      add_option( 'pzarc_css', maybe_serialize( array(
          'blueprints' => array(),
          'panels'     => array(),
      ) ), NULL, 'no' );
    }
    require_once( PZARC_PLUGIN_APP_PATH . '/admin/php/arc-save-process.php' );

    save_arc_layouts( 'all', NULL, TRUE );
    update_option( 'pzarc-run-rebuild', FALSE );
    delete_option( 'architect_custom_fields' );
    delete_option( 'arc-blueprint-usage' );
    update_option( 'arc_blueprints', pzarc_get_blueprints( FALSE ) );


  }

  function pzarc_get_tags() {
    $return_arr = array();
    if ( ! empty( $_GET['post'] ) ) {
      $ctname       = '';
      $thispostmeta = get_post_meta( $_GET['post'] );
      $ctname       = ( ! empty( $thispostmeta['_content_general_other-tax'][0] ) ? $thispostmeta['_content_general_other-tax'][0] : NULL );
      if ( $ctname ) {
        $args       = array( 'hide_empty' => FALSE );
        $custom_tax = get_terms( $ctname, $args );
        foreach ( $custom_tax as $ct ) {
          $return_arr[ $ct->slug ] = $ct->name;
        }
      }
    }

    return $return_arr;
  }

  if ( ! function_exists( 'array_replace_recursive' ) ) {
    function array_replace_recursive( $array, $array1 ) {
      // handle the arguments, merge one by one
      $args  = func_get_args();
      $array = $args[0];
      if ( ! is_array( $array ) ) {
        return $array;
      }
      for ( $i = 1; $i < count( $args ); $i ++ ) {
        if ( is_array( $args[ $i ] ) ) {
          $array = recurse( $array, $args[ $i ] );
        }
      }

      return $array;
    }
  }
  if ( ! function_exists( 'recurse' ) ) {
    function recurse( $array, $array1 ) {
      foreach ( $array1 as $key => $value ) {
        // create new key in $array, if it is empty or not an array
        if ( ! isset( $array[ $key ] ) || ( isset( $array[ $key ] ) && ! is_array( $array[ $key ] ) ) ) {
          $array[ $key ] = array();
        }

        // overwrite the value in the base array
        if ( is_array( $value ) ) {
          $value = recurse( $array[ $key ], $value );
        }
        $array[ $key ] = $value;
      }

      return $array;
    }
  }

  /**
   * @param $pzarc_dir
   *
   * @return mixed
   */
  function pzarc_tidy_dir( $pzarc_dir ) {
    foreach ( $pzarc_dir as $k => $v ) {
      if ( substr( $v, 0, 1 ) === '.' ) {
        unset( $pzarc_dir[ $k ] );
      }
    }

    return $pzarc_dir;
  }

  /**
   * @param null $pzarc_file
   * @param null $pzarc_upload_type
   */
  function pzarc_upload_file( $pzarc_file = NULL, $pzarc_upload_type = NULL, $pzarc_newbpname = NULL ) {
    if ( in_array( $pzarc_upload_type, array(
            'blueprint',
            'preset',
        ) ) && ! empty( $pzarc_file ) ) {

      if ( ! function_exists( 'wp_handle_upload' ) ) {
        require_once( ABSPATH . 'wp-admin/includes/file.php' );
      }

      // TODO Skip if the folder already exists but then we'll need a way to replace old ones
      $uploadedfile     = $pzarc_file;
      $upload_overrides = array(
          'test_form' => FALSE,
          'mimes'     => array(
              'zip' => 'application/zip',
              'txt' => 'text/plain',
          ),
      );

      switch ( $pzarc_upload_type ) {
        case 'preset':
          add_filter( 'upload_dir', 'pzarc_presets_upload_dir' );
          $movefile = wp_handle_upload( $uploadedfile, $upload_overrides );
          remove_filter( 'upload_dir', 'pzarc_presets_upload_dir' );

          if ( $movefile && ! isset( $movefile['error'] ) ) {
            $pzarc_uploads = wp_upload_dir();
            $result        = unzip_file( $movefile['file'], $pzarc_uploads['basedir'] . '/pizazzwp/architect/presets' );
            if ( $result === TRUE ) {
              echo '<div id="message" class="updated"><p>' . __( 'Preset installed! Go to the Blueprints Preset Selector to use it.', 'pzarchitect' ) . '</p></div>';
            } else {
              echo '<div id="message" class="error"><p>' . __( 'Preset uploaded but failed to unzip. Please check your folder permissions.', 'pzarchitect' ) . '</p></div>';
            }
          } else {
            /**
             * Error generated by _wp_handle_upload()
             * @see _wp_handle_upload() in wp-admin/includes/file.php
             */
            echo $movefile['error'];
          }
          break;

        case 'blueprint':
          add_filter( 'upload_dir', 'pzarc_blueprints_upload_dir' );
          $movefile = wp_handle_upload( $uploadedfile, $upload_overrides );
          remove_filter( 'upload_dir', 'pzarc_blueprints_upload_dir' );

          if ( $movefile && ! isset( $movefile['error'] ) ) {
            pzarc_import_blueprint( $movefile['url'], $alt_slug = NULL, $pzarc_newbpname, $process_type = 'styled' );
          } else {
            /**
             * Error generated by _wp_handle_upload()
             * @see _wp_handle_upload() in wp-admin/includes/file.php
             */
            echo $movefile['error'];
          }
          break;

      }
    }
  }

  /**
   * @param $dir
   *
   * @return array
   */
  function pzarc_presets_upload_dir( $dir ) {
    return array(
               'path'   => $dir['basedir'] . '/pizazzwp/architect/presets',
               'url'    => $dir['baseurl'] . '/pizazzwp/architect/presets',
               'subdir' => '/pizazzwp/architect/presets',
           ) + $dir;
  }

  /**
   * @param $dir
   *
   * @return array
   */
  function pzarc_blueprints_upload_dir( $dir ) {
    return array(
               'path'   => PZARC_CACHE_PATH,
               'url'    => PZARC_CACHE_URL,
               'subdir' => '',
           ) + $dir;
  }

  /**
   * Function creates post duplicate as a draft and redirects then to the edit post screen
   *
   */
  function pzarc_new_from_preset() {
    // How do we add some security?
    if ( ! ( isset( $_GET['name'] ) || isset( $_POST['name'] ) || ( isset( $_REQUEST['action'] ) && 'pzarc_new_from_preset' == $_REQUEST['action'] ) ) ) {
      wp_die();
    }

    /*
       * get the original post name
       */
    $preset_name  = ( isset( $_GET['name'] ) ? $_GET['name'] : $_POST['name'] );
    $process_type = ( isset( $_GET['type'] ) ? $_GET['type'] : $_POST['type'] );
    /*
       * and all the original post data then
       */

    require_once PZARC_PLUGIN_PATH . 'presets/class_arcPresetsLoader.php';
    $presets         = new arcPresetsLoader();
    $presets_array   = $presets->render();
    $arc_preset_data = $presets_array['data'][ $preset_name ];

    pzarc_create_blueprint( $arc_preset_data, $preset_name, $process_type, NULL, NULL );
  }

  /**
   * @param null   $bpexpfile
   * @param null   $alt_slug
   * @param null   $alt_title
   * @param string $process_type
   */
  function pzarc_import_blueprint( $bpexpfile = NULL, $alt_slug = NULL, $alt_title = NULL, $process_type = 'styled' ) {

    if ( ! empty( $bpexpfile ) ) {


      $ch = curl_init( $bpexpfile );
      curl_setopt( $ch, CURLOPT_RETURNTRANSFER, TRUE );
      $file_contents = curl_exec( $ch );
      curl_close( $ch );

      $arc_preset_data = json_decode( $file_contents, TRUE );

      $preset_name = 'anything'; // This is not relevant for code generated blueprints; however, it cannot be blank
      $alt_title   = $alt_title ? $alt_title : '(New) Unnamed Blueprint ' . $alt_slug;
      pzarc_create_blueprint( $arc_preset_data, $preset_name, $process_type, $alt_slug, $alt_title );
    } else {
      // todo: pop an error msg
    }
  }

  /**
   * @param string $pzarc_tax
   * Unused until can find a way to make this load before Blueprints editor
   */
  function pzarc_set_tax_titles( $pzarc_tax = '' ) {
    global $pzarc_taxonomy_list;
    $pzarc_taxonomy_list = array();
    $pzarc_taxes         = get_taxonomies( array( 'public' => TRUE ) );
    foreach ( $pzarc_taxes as $k => $v ) {
      $pzarc_tax                 = get_taxonomy( $k );
      $pzarc_taxonomy_list[ $k ] = $pzarc_tax->labels->name;
    }
  }

  /**
   * @return array
   * Because Redux passing arguments doesn'ty  seem to be working now
   */
  function pzarc_get_taxonomies_ctb() {
    return pzarc_get_taxonomies( TRUE, FALSE );
  }

  /**
   * @param bool $catstags
   * @param bool $has_blank
   *
   * @return array
   */
  function pzarc_get_taxonomies( $catstags = TRUE, $has_blank = TRUE ) {
    $taxonomy_list = get_taxonomies( array(
        'public'   => TRUE,
        '_builtin' => FALSE,
    ) );
    foreach ( $taxonomy_list as $k => $v ) {
      $tax_obj             = get_taxonomy( $k );
      $taxonomy_list[ $k ] = $tax_obj->labels->name;
    }
    // Add the None option if required
    $extras        = $has_blank ? array(
        0          => '',
        'category' => 'Categories',
        'post_tag' => 'Tags',
    ) : array(
        'category' => 'Categories',
        'post_tag' => 'Tags',
    );
    $taxonomy_list = $catstags ? $extras + $taxonomy_list : $taxonomy_list;

    return $taxonomy_list;
  }

  /**
   * @param string $taxonomy
   * @param array  $args
   * @param bool   $array
   *
   * @return array|int|null|WP_Error
   */
  function pzarc_get_terms( $taxonomy = '', $args = array(), $array = TRUE, $use_id = FALSE ) {

    $terms = get_terms( $taxonomy, $args );
    if ( isset( $terms->errors ) ) {
      return NULL;
    } else {
      if ( $array && ! empty( $terms ) ) {
        $term_list = array();
        foreach ( $terms as $k => $v ) {
          $term_list[ ( $use_id ? $v->term_id : $v->slug ) ] = $v->name;
        }
        $terms = $term_list;
      }

      return $terms;
    }


  }

  /**
   * display Archives page_title
   *
   * @param $display_title
   * @param $title_override
   *
   * @return $return string
   */
  function pzarc_display_page_title( &$blueprint, &$arcoptions, $tag = 'h1' ) {
    global $wp_the_query;
    $return              = '';
    $display_title       = $blueprint['_blueprints_page-title'];
    $display_description = ( ! isset( $blueprint['_blueprints_show-archive-description'] ) || ! empty( $blueprint['_blueprints_show-archive-description'] ) );
    $desc                = '';
    $title_override      = array(
        'category' => $arcoptions['architect_language-categories-archive-pages-title'],
        'tag'      => $arcoptions['architect_language-tags-archive-pages-title'],
        'month'    => $arcoptions['architect_language-tags-archive-pages-title'],
        'custom'   => $arcoptions['architect_language-custom-archive-pages-title'],
    );

    pzdb( 'page title' );
    if ( ! empty( $display_title ) || ! empty( $blueprint['additional_overrides']['pzarc-overrides-page-title'] ) ) {
      $title      = '';
      $inc_prefix = empty( $blueprint['_blueprints_hide-archive-title-prefix'] );

      /**
       * Get the original page query global
       */

      switch ( TRUE ) {
        case is_category():
          $title = single_cat_title( __( $inc_prefix ? $title_override['category'] : '', 'pzarchitect' ), FALSE );
          break;
        case is_tag() :
          $title = single_tag_title( __( $inc_prefix ? $title_override['tag'] : '', 'pzarchitect' ), FALSE );
          break;
        case is_month() :
          $title = single_month_title( __( $inc_prefix ? $title_override['month'] : '', 'pzarchitect' ), FALSE );
          break;
        case is_tax() :
          $title = single_term_title( __( $inc_prefix ? $title_override['custom'] : '', 'pzarchitect' ), FALSE );
          break;
        case $wp_the_query->is_category:
          $title = pzarc_term_title( __( $inc_prefix ? $title_override['category'] : '', 'pzarchitect' ), $wp_the_query->tax_query );
          break;
        case $wp_the_query->is_tag :
          $title = pzarc_term_title( __( $inc_prefix ? $title_override['tag'] : '', 'pzarchitect' ), $wp_the_query->tax_query );
          break;
        case $wp_the_query->is_month :
          $title = pzarc_term_title( __( $inc_prefix ? $title_override['month'] : '', 'pzarchitect' ), $wp_the_query->tax_query );
          break;
        case $wp_the_query->is_tax :
          $title = pzarc_term_title( __( $inc_prefix ? $title_override['custom'] : '', 'pzarchitect' ), $wp_the_query->tax_query );
          break;
        case is_single() || $wp_the_query->is_single:
        case is_singular() || $wp_the_query->is_singular || $wp_the_query->is_page:
          $title = single_post_title( NULL, FALSE );
          $title = ! $title ? $wp_the_query->post->post_title : $title;
          break;
      }
      if ( $title ) {
        $return .= '<' . $tag . ' class="pzarc-page-title">' . esc_attr( $title ) . '</' . $tag . '>';
      }
    }

    return $return;
  }

  /**
   * @param $blueprint
   *
   * @return string
   */
  function pzarc_display_archive_description( &$blueprint ) {
    global $wp_the_query;
    $return              = '';
    $display_description = ! empty( $blueprint['_blueprints_show-archive-description'] );
    if ( $display_description ) {
      if ( is_category() || is_tag() || is_tax() || $wp_the_query->is_category || $wp_the_query->is_tag || $wp_the_query->is_tax ) {
        $desc   = ! empty( $wp_the_query->queried_object->description ) ? $wp_the_query->queried_object->description : get_the_archive_description();
        $return .= '<div class="archive-desc">' . esc_attr( $desc ) . '</div>';
      }
    }

    return $return;
  }


  /**
   *
   */
  function bfi_flush_image_cache() {
    $upload_info = wp_upload_dir();
    $upload_dir  = $upload_info['basedir'];
    if ( defined( 'BFITHUMB_UPLOAD_DIR' ) ) {
      $upload_dir .= "/" . BFITHUMB_UPLOAD_DIR;
    } else {
      $upload_dir .= "/bfi_thumb";
    }
    if ( ! is_dir( $upload_dir ) ) {
      if ( ! wp_mkdir_p( $upload_dir ) ) {
        //     die('Failed to create folders...');
      }
    }
    $cache_files = scandir( $upload_dir );
    foreach ( $cache_files as $cache_file ) {
      if ( ! is_dir( $upload_dir . '/' . $cache_file ) ) {
        @unlink( $upload_dir . '/' . $cache_file );
      }
    }
  }

  /**
   * @return array
   */
  function return_taxonomies() {
    $args       = array(
        'public'   => TRUE,
        '_builtin' => FALSE,
    );
    $output     = 'names';
    $taxonomies = get_taxonomies( $args, $output );

    return $taxonomies;
  }

  /**
   * @param $pzarc_settings
   *
   * @return string
   * Use in Loop
   */
  function pzarc_make_excerpt_more( $pzarc_settings, $pzarc_post = NULL ) {

    if ( is_array( $pzarc_post ) ) {
      $pzarc_permalink = isset( $pzarc_post['permalink'] ) ? $pzarc_post['permalink'] : NULL;
    } elseif ( is_object( $pzarc_post ) ) {
      $pzarc_permalink = get_permalink( $pzarc_post->ID );
    } elseif ( is_numeric( $pzarc_post ) ) {
      $pzarc_permalink = get_permalink( $pzarc_post );
    } else {
      $pzarc_permalink = '';
    }
    $new_more = $pzarc_settings['_panels_design_readmore-truncation-indicator'];
    $new_more .= ( $pzarc_settings['_panels_design_readmore-text'] ? '<a href="' . $pzarc_permalink . '" class="readmore moretag">' . $pzarc_settings['_panels_design_readmore-text'] . '</a>' : NULL );

    return $new_more;

  }

  function pzarc_get_page_id() {
    // Use the original wp query global
    global $wp_the_query;
    $return = get_the_ID();
    if ( isset( $wp_the_query->queried_object->ID ) ) {
      $return = $wp_the_query->queried_object->ID;
    } else {
      switch ( TRUE ) {
        case ( isset( $wp_the_the_query->query_vars['p'] ) && $wp_the_query->query_vars['p'] === 0 ) :
          $return = $wp_query->query_vars['page_id'];
          break;
        case ( isset( $wp_the_query->query_vars['page_id'] ) && $wp_the_query->query_vars['page_id'] === 0 ) :
          $return = $wp_the_query->query_vars['p'];
          break;
        case ( isset( $wp_the_query->query_vars['p'] ) ):
          $return = $wp_the_query->query_vars['p'];
          break;
      }
    }

    return $return;

  }

  function pzarc_get_post_type() {

    if ( is_admin() ) {

      if ( function_exists( 'get_current_screen' ) ) {
        $screen = get_current_screen();
        if ( isset( $screen->post_type ) ) {
          return $screen->post_type;
        }
      }

      if ( isset( $_GET['post_type'] ) ) {
        return $_GET['post_type'];
      }

      if ( isset( $_GET['post'] ) ) {
        $pzarc_post = get_post( $_GET['post'] );
        if ( isset( $pzarc_post->post_type ) ) {
          return $pzarc_post->post_type;
        }
      }
    }

    return NULL;
  }

  /**
   * @param null $pzarc_source
   * @param null $pzarc_settings
   *
   * @return array
   */
  // TODO: Something!
  function pzarc_generate_additional_overrides( $pzarc_source = NULL, $pzarc_settings = NULL ) {
    $pzarc_overrides = array();
    switch ( $pzarc_source ) {
      case 'beaver':
        $pzarc_overrides['_blueprints_blueprint-title'] = ! empty( $pzarc_settings->blueprint_title ) ? $pzarc_settings->blueprint_title : NULL;
        break;
    }

    return $pzarc_overrides;
  }

  /**
   * @param $categories
   * @param $cats_to_hide
   *
   * @return string
   */
  function pzarc_hide_categories( $categories, $cats_to_hide ) {
    $cat_list = explode( ',', $categories );
    foreach ( $cats_to_hide as $cat_to_hide ) {
      foreach ( $cat_list as $cat_key => $cat_val ) {
        $arc_cat_id = get_the_category_by_ID( $cat_to_hide );
        if ( ! empty( $arc_cat_id ) && strpos( $cat_val, $arc_cat_id ) ) {
          unset( $cat_list[ $cat_key ] );
        }
      }
    }

    return implode( ',', $cat_list );
  }

  /**
   * @param        $pzarc_option
   * @param string $pz_default
   *
   * @return string
   */
  function pzarc_get_option( $pzarc_option, $pz_default = '' ) {
    if ( ! isset( $GLOBALS['_architect_options'] ) ) {
      $GLOBALS['_architect_options'] = get_option( '_architect_options', array() );
    }
    global $_architect_options;
    $pz_value = empty( $_architect_options[ $pzarc_option ] ) ? $pz_default : $_architect_options[ $pzarc_option ];

    return $pz_value;
  }

  /**
   * @param $post_id
   *
   * @return mixed|void
   */
  function pzarc_has_media( $post_id ) {
    $pzarc_media = array();
    $pzarc_post  = get_post( $post_id );

    // Check for galleries
    if ( has_shortcode( $pzarc_post->post_content, 'gallery' ) ) {
      $pzarc_media[] = 'gallery';
    }

    // Check for video
    if ( pzarc_has_video( $post_id, $pzarc_post->post_content ) ) {
      $pzarc_media[] = 'video';
    }

    // Check for audio
    if ( pzarc_has_audio( $post_id, $pzarc_post->post_content ) ) {
      $pzarc_media[] = 'audio';
    }


    if ( pzarc_has_document( $post_id, $pzarc_post->post_content ) ) {
      $pzarc_media[] = 'document';
    }

    return apply_filters( 'pzarc_has_media', $pzarc_media );

  }


  /**
   * @param $post_id
   * @param $pzarc_content
   *
   * @return bool
   */
  function pzarc_has_video( $post_id, $pzarc_content ) {
    $pzarc_opt_featured_video = get_post_meta( $post_id, 'pzarc_features-video', TRUE );
    $pzarc_video              = ! empty( $pzarc_opt_featured_video );
    if ( ! $pzarc_video && has_shortcode( $pzarc_content, 'playlist' ) ) {

      $pattern = get_shortcode_regex();
      preg_match_all( '/' . $pattern . '/s', $pzarc_content, $matches );

      foreach ( $matches[3] as $k => $v ) {
        if ( $matches[2][ $k ] == 'playlist' && strpos( 'x' . $v, 'type="video"' ) ) {
          $pzarc_video = TRUE;
          break;
        }
      }

    }

    return apply_filters( 'pzarc_has_video', $pzarc_video );
  }

  /**
   * @param $post_id
   * @param $pzarc_content
   *
   * @return bool
   */
  function pzarc_has_audio( $post_id, $pzarc_content ) {
    $pzarc_audio = FALSE;
    if ( has_shortcode( $pzarc_content, 'playlist' ) ) {

      $pattern = get_shortcode_regex();
      preg_match_all( '/' . $pattern . '/s', $pzarc_content, $matches );

      foreach ( $matches[3] as $k => $v ) {
        if ( $matches[2][ $k ] == 'playlist' && ( strpos( 'x' . $v, 'type="audio"' ) || ! strpos( 'x' . $v, 'type="video"' ) ) ) {
          $pzarc_audio = TRUE;
          break;
        }
      }

    }

    return apply_filters( 'pzarc_has_audio', $pzarc_audio );
  }

  /**
   * @param $post_id
   */
  function pzarc_has_document( $post_id, $pzarc_content ) {
    $pzarc_documents = FALSE;
    $pzarc_documents = ( $pzarc_documents || preg_match( '/:\\/\\/(.)*(\\.pdf)/uiUm', $pzarc_content ) );
    $pzarc_documents = ( $pzarc_documents || preg_match( '/:\\/\\/(.)*(\\.doc)/uiUm', $pzarc_content ) );
    $pzarc_documents = ( $pzarc_documents || preg_match( '/:\\/\\/(.)*(\\.docx)/uiUm', $pzarc_content ) );
    $pzarc_documents = ( $pzarc_documents || preg_match( '/:\\/\\/(.)*(\\.txt)/uiUm', $pzarc_content ) );
    $pzarc_documents = ( $pzarc_documents || preg_match( '/:\\/\\/(.)*(\\.xls)/uiUm', $pzarc_content ) );
    $pzarc_documents = ( $pzarc_documents || preg_match( '/:\\/\\/(.)*(\\.xlsx)/uiUm', $pzarc_content ) );
    $pzarc_documents = ( $pzarc_documents || preg_match( '/:\\/\\/(.)*(\\.ppt)/uiUm', $pzarc_content ) );
    $pzarc_documents = ( $pzarc_documents || preg_match( '/:\\/\\/(.)*(\\.pptx)/uiUm', $pzarc_content ) );

    return apply_filters( 'pzarc_has_docs', $pzarc_documents );
  }

  /**
   * Add fields to custom fields
   *
   * @param $arc_fields
   * @param $arc_add
   *
   * @return array
   */
  function arc_add_to_custom_fields( $arc_fields, $arc_add ) {
    return array_merge( $arc_fields, $arc_add );
  }

  /**
   * @param $arc_overrides
   * @param $arc_blueprint
   */

  function arc_process_overrides( $arc_overrides, &$arc_blueprint ) {

    if ( ! empty( $arc_overrides ) && is_array( $arc_overrides ) ) {

      foreach ( $arc_overrides as $k => $v ) {

        if ( key_exists( $k, $arc_blueprint ) ) {
          // TODO: Will need to handle arrays in the future
          $arc_blueprint[ $k ] = ! empty( $v ) ? $v : $arc_blueprint[ $k ];
        }
      }
      // Capture any others manually
      switch ( TRUE ) {
      }
    }

    return $arc_overrides;
  }

  /**
   * @return mixed|string|void
   */
  function pzarc_status() {
    if ( function_exists( 'arc_fs' ) ) {
      switch ( TRUE ) {
        case arc_fs()->is_premium() && arc_fs()->is_plan( 'is_free_localhost' ) && defined( 'WP_FS__IS_LOCALHOST_FOR_SERVER' ) && WP_FS__IS_LOCALHOST_FOR_SERVER:
          $pzarc_status = 'valid';
          break;
        case arc_fs()->is_free_plan():
          $pzarc_status = '';
          break;
        case arc_fs()->is_plan( 'architectpro' ):
          $pzarc_status = 'valid';
          break;
        default:
          $pzarc_status = '';

      }
    } else {
      $pzarc_status = get_option( 'edd_architect_license_status' );
    }

    return $pzarc_status;
  }

  function pzarc_truncate( $pzval, $pzprecision ) {

    return floor( $pzval * pow( 10, $pzprecision ) + ( 1 / pow( 10, ( $pzprecision + 1 ) ) ) ) / pow( 10, $pzprecision );

  }

  /**
   * @param $value
   * @param $key
   * Used in conjunction with array_filter
   *
   * @return bool
   */
  function pzarc_strip_empty_lines( $value, $key ) {
    $pzarc_tags = trim( strip_tags( $value ) );
    if ( empty( $pzarc_tags ) ) {
      return FALSE;
    } else {
      return TRUE;
    }
  }


  // What was the point of this? Trying to do cropping without bfi?
  //	if (!function_exists('bfi_thumb')) {
  //		function bfi_thumb($arc_image_url=null,$arc_image_dimensions=array()){
  //		  // Was rthis here just for testing?!
  ////			remove_filter( 'wp_image_editors', 'bfi_wp_image_editor' );
  ////			remove_filter( 'image_resize_dimensions', 'bfi_image_resize_dimensions', 10, 6 );
  ////			remove_filter( 'image_downsize', 'bfi_image_downsize', 1, 3 );
  //			$image = wp_get_image_editor( $arc_image_url );
  //			if ( ! is_wp_error( $image ) ) {
  //				$image->resize( $arc_image_dimensions['width'], $arc_image_dimensions['height'], false );
  //				$fn= random_int(1,1000).'.jpg';
  //				$image->save( PZARC_CACHE_PATH.$fn );
  //				return PZARC_CACHE_URL.$fn;
  //			} else {
  //				return null;
  //			}
  //		}
  //
  //}

  class ArcFun {

    static function get_blueprint_id( $post ) {
      return $post->ID;
    }

    static function get_blueprint_title( $post ) {
      return $post->post_title;
    }

    static function get_tables( $limit = NULL ) {
      global $wpdb;
      // Get all tables for current site
      $results    = $wpdb->get_results( "SHOW TABLES LIKE '{$wpdb->prefix}%'" );
      $tableset   = array();
      $exclusions = array(
          $wpdb->prefix . 'hw_',
          $wpdb->prefix . 'bt_',
          $wpdb->prefix . 'cpk_',
          $wpdb->prefix . 'wc_',
          $wpdb->prefix . 'postmeta',
          $wpdb->prefix . 'options',
          $wpdb->prefix . 'term',
      );
      foreach ( $results as $index => $value ) {
        foreach ( $value as $tablename ) {
          $toexc = FALSE;
          foreach ( $exclusions as $exclusion ) {
            $len   = strlen( $exclusion );
            $toexc = ( substr( $tablename, 0, $len ) === $exclusion );
            if ( $toexc ) {
              break;
            }
          }
          if ( ! $toexc ) {
            $tableset[ $tablename ] = $tablename;
          }
        }
      }

      return $tableset;
    }

    /**
     * @param      $table
     * @param bool $inc_table_in_value
     *
     * @return array
     */
    static function get_table_fields( $table, $inc_table_in_value = FALSE ) {
      global $wpdb;
      $fields = $wpdb->get_col( "DESC {$table}", 0 );
//      trigger_error("TABLE FIELDS", E_USER_WARNING);
      $fieldskv = array();
      foreach ( $fields as $v ) {
        $fieldskv[ $table . '/' . $v ] = ( $inc_table_in_value ? $table . '/' : '' ) . $v;
      }
      unset( $fields );

      return $fieldskv;
    }

    /**
     * @return array
     */
    static function get_all_table_fields_flat( $in_customfields = TRUE ) {
      $tableset     = ArcFun::get_tables();
      $tablesfields = array( '' => '' );
      foreach ( $tableset as $table ) {
        $tablesfields = array_merge( $tablesfields, ArcFun::get_table_fields( $table, TRUE ) );
      }

      if ( $in_customfields ) {
        $tablesfields = array_merge( $tablesfields, ArcFun::get_custom_fields( NULL, TRUE ) );
      }

      return $tablesfields;
    }

    static function extract_table_field( $tablefield ) {
      $array = explode( '/', $tablefield );
      if ( isset( $array[1] ) ) {
        return array( 'table' => $array[0], 'field' => $array[1] );
      } else {
        return array();
      }
    }

    /**
     * @param $settings
     *
     * @return mixed
     */
    static function render_any_field( $settings ) {
      $panel_def_cfield = '<{{cfieldwrapper}} class="arcaf-anyfield arcaf-anyfield-{{cfieldname}} {{cfieldname}}">{{cfieldcontent}}</{{cfieldwrapper}}>';
      $content          = '';
//      $field_val        = is_array( $settings['field'] ) ? $settings['field'] : ( $settings['field'] );
//      $field_val        = $settings['field'];
      if ( empty( $settings['fieldset'][0]->arc_fieldname ) ) {
        return NULL;
      }
      foreach ( $settings['fieldset'] as $field_info ) {
        $content   .= ( isset( $field_info->arc_fieldbefore ) ? ArcFun::strip_tags( $field_info->arc_fieldbefore, '<br><p><strong><em><ul><ol><li><h1><h2><h3><h4><h5><h6>' ) : '' );
        $field_val = arc_get_table_field_value( ArcFun::extract_table_field( $field_info->arc_fieldname ) );
        switch ( $field_info->field_type ) {

          // TODO: Add escaping?
          case 'image':
            if ( function_exists( 'bfi_thumb' ) ) {

              $content .= '<img src="' . esc_url( bfi_thumb( $field_val ) ) . '">';
            } else {
              $content .= '<img src="' . esc_url( $field_val ) . '">';
            }
            break;

          case 'embed':
//          var_dump($field_val);
            $dimensions = array();
            if ( ! empty( $field_info->embed_width ) ) {
              $dimensions['width'] = $field_info->embed_width;
            }
            if ( ! empty( $field_info->embed_height ) ) {
              $dimensions['height'] = $field_info->embed_height;
            }

            $content .= wp_oembed_get( esc_url( $field_val ), $dimensions );
            break;

          case 'date':
            if ( is_numeric( $field_val ) ) {
              $contentn = date( $field_info->date_format, $field_val );
            } else {
              $contentn = $field_val;
            }
            $content .= '<time datetime="' . $contentn . '">' . $contentn . '</time>';
            break;

          case 'number':
            $content .= @number_format( $field_val, $field_info->number_decimals, $field_info->number_decimal_char, $field_info->number_thousands_sep );
            break;

          case 'group': // Multi select? Multi check? Or a group of fields?
            if ( is_array( maybe_unserialize( $field_val ) ) ) {

              switch ( $field_info->field_creator ) {
                case 'toolsettypes' :
                  $ts_vals = array();
                  foreach ( $field_val as $k => $v ) {
                    $ts_vals[] = $v[0];
                  }
                  $field_val = $ts_vals;
                  break;
                case 'acf':
                  break;
                case 'unknown':
                  break;

              }
              switch ( TRUE ) {
                case empty( $field_info->group_joiner ):
                case $field_info->group_joiner === 'linebreak';
                  $content .= '<p>' . implode( '</p><p>', $field_val ) . '</p>';
                  break;
                case $field_info->group_joiner === 'comma':
                  $content .= '<span>' . implode( '</span>, <span>', $field_val ) . '</span>';
                  break;
                case $field_info->group_joiner === 'ulist':

                  $content .= '<ul><li>' . implode( '</li><li>', $field_val ) . '</li></ul>';
                  break;
              }
            } else {
              $content .= $field_val;
            }
            break;

          case
          'acf_repeater':
            $content .= $field_val;

            break;

          case 'text':
          default:
            $contentt = ( ! empty( $field_info->text_paras ) && $field_info->text_paras === 'yes' ) ? wpautop( $field_val ) : $field_val;
            if ( empty( $field_info->process_shortcodes ) || $field_info->process_shortcodes === 'process' ) {
              $content .= do_shortcode( $contentt );
            } else {
              $content .= strip_shortcodes( $contentt );
            }

            break;


        }
        $content .= ( isset( $field_info->arc_fieldafter ) ? ArcFun::strip_tags( $field_info->arc_fieldafter, '<br><p><strong><em><ul><ol><li><h1><h2><h3><h4><h5><h6>' ) : '' );

      }

      $prefix_image = '';
      $suffix_image = '';
      if ( ! empty( $settings['prefix-image'] ) ) {
        $prefix_image = '<img src="' . esc_url( $settings['prefix-image_src'] ) . '" class="arcaf-presuff-image prefix-image" width=' . esc_attr( $settings['ps-images-width'] ) . ' height=' . esc_attr( $settings['ps-images-height'] ) . ' >';
      }

      if ( ! empty( $settings['suffix-image'] ) ) {
        $suffix_image = '<img src="' . esc_url( $settings['suffix-image_src'] ) . '" class="arcaf-presuff-image suffix-image" width=' . esc_attr( $settings['ps-images-width'] ) . ' height=' . esc_attr( $settings['ps-images-height'] ) . ' >';
      }


      $prefix_text = ! empty( $settings['prefix-text'] ) ? '<span class="arcaf-prefix-text">' . $settings['prefix-text'] . '</span>' : '';
      $suffix_text = ! empty( $settings['suffix-text'] ) ? '<span class="arcaf-suffix-text">' . $settings['suffix-text'] . '</span>' : '';
      $content     = $prefix_image . $prefix_text . $content . $suffix_text . $suffix_image;
      if ( ! empty( $settings['link-field'] ) ) {
        $content = '<a href="' . ( $settings['link-behaviour'] === 'email' ? 'mailto:' : '' ) . esc_url( arc_get_table_field_value( ArcFun::extract_table_field( $settings['link-field'] ) ) ) . '" target="' . esc_attr( $settings['link-behaviour'] === 'email' ? '_self' : $settings['link-behaviour'] ) . '" rel="noopener">' . $content . '</a>';
      }


      // TODO: Should apply filters here?
      $panel_def_cfield = str_replace( '{{cfieldwrapper}}', $settings['html-tag'], $panel_def_cfield );
      $panel_def_cfield = str_replace( '{{cfieldcontent}}', $content, $panel_def_cfield );
      $panel_def_cfield = str_replace( '{{cfieldname}}', sanitize_title_with_dashes( $settings['name'] ), $panel_def_cfield );

      return $panel_def_cfield;
    }

    /**
     * @return bool
     */
    static function is_bb_active() {
      return ( class_exists( 'FLBuilderModel' ) && ( FLBuilderModel::is_builder_active() || isset( $_GET['fl_builder'] ) ) );
    }

    static function clear_arc_cache() {
      // deletes any remaining files in the pzarc cache
      $cache_files = scandir( PZARC_CACHE_PATH );
      foreach ( $cache_files as $cache_file ) {
        if ( ! is_dir( PZARC_CACHE_PATH . '/' . $cache_file ) ) {
          @unlink( PZARC_CACHE_PATH . '/' . $cache_file );
        }
      }

    }

    /**
     * @param        $string
     * @param string $tags
     *
     * @return string
     */
    static function strip_tags( $string, $tags = '<br><p><a><strong><em><ul><ol><li><pre><code><blockquote><h1><h2><h3><h4><h5><h6>' ) {
      return strip_tags( $string, $tags );
    }

    /**
     * @param $array
     * @param $key
     *
     * @return bool
     */
    static function is_last( $array, $key ) {
      return ( $array[ $key ] == end( $array ) );
    }

    /**
     * @param $date
     *
     * @return mixed
     *
     *  Purpose: Fix problems with / and , in dates for strtotime
     */
    static function fix_date( $date, $format ) {
      $checks = array( 'd/m/', 'dd/mm/', 'd/mm/', 'dd/m/' );
      $found  = 0;
      $return = $date;
      foreach ( $checks as $check ) {
        $found += (int) strpos( $check, str_replace( 'y', '', strtolower( $format ) ) );
      }

      if ( $found && strpos( $date, '/' ) ) {
        $return = str_replace( '/', '-', str_replace( ',', ' ', $date ) );
      } else {
        $return = str_replace( ',', ' ', $date );
      }

      return $return;
    }

    static function enqueue_scripts( $layout_type = 'basic', $blueprint = 'none' ) {
      switch ( $layout_type ) {
        case 'basic':
          break;
        case 'masonry':
          wp_enqueue_script( 'js-imagesloaded' );
          wp_enqueue_script( 'js-isotope' );
          wp_enqueue_script( 'js-isotope-packery' );
          wp_enqueue_script( 'js-front-isotope' );
          break;
        case 'accordion':
          wp_enqueue_script( 'js-jquery-collapse' );
          break;
        case 'table':
          wp_enqueue_script( 'js-datatables' );
          wp_enqueue_style( 'css-datatables' );
          break;


      }
    }

    /**
     * @param $blueprint_name
     *
     * @return array
     */
    static function get_blueprint_query_args( $blueprint_name ) {
      if ( is_numeric( $blueprint_name ) ) {
        return array(
            'post_type' => 'arc-blueprints',
            'include'   => $blueprint_name,
        );
      } else {
        return array(
            'post_type'    => 'arc-blueprints',
            'meta_key'     => '_blueprints_short-name',
            'meta_value'   => $blueprint_name,
            'meta_compare' => '=',
        );
      }

    }

    /**
     * @param string $field
     * @param array  $field_types
     * @param array  $exclude
     * @param string $comparison
     *
     * @return array
     *
     * For
     *
     */
    static function redux_required( $compare_field = '', $all_values = array(), $exclude_values = array(), $comparison = '!=', $inc_empty = TRUE ) {
      /*
       * When $comparison is != this effectively works as an OR for the $exclude_values
       */
      if ( empty( $compare_field ) || empty( $all_values ) ) {
        $return = array();
      } else {
        if ( $inc_empty ) {
          $return = array( array( $compare_field, $comparison, '' ) );
          $i      = 1;
        } else {
          $return = array();
          $i      = 0;
        }
        foreach ( $all_values as $k => $v ) {
          if ( ! in_array( $k, $exclude_values ) ) {
            $return[ $i ++ ] = array( $compare_field, $comparison, $k );
          }
        }
      }

      return $return;
    }

    static function field_types( $return_path = FALSE ) {
      //PZARC_PLUGIN_APP_PATH . '/shared/architect/php/field_types/'
      $field_types = array(
          'text'            => array( 'description' => __( 'Text', 'pzarchitect' ), 'path' => PZARC_PLUGIN_APP_PATH . '/shared/architect/php/field_types/' ),
          'text-with-paras' => array( 'description' => __( 'Text with paragraph breaks', 'pzarchitect' ), 'path' => PZARC_PLUGIN_APP_PATH . '/shared/architect/php/field_types/' ),
          'wysiwyg'         => array( 'description' => __( 'Formatted text', 'pzarchitect' ), 'path' => PZARC_PLUGIN_APP_PATH . '/shared/architect/php/field_types/' ),
          'image'           => array( 'description' => __( 'Image', 'pzarchitect' ), 'path' => PZARC_PLUGIN_APP_PATH . '/shared/architect/php/field_types/' ),
          'gallery'         => array( 'description' => __( 'Gallery', 'pzarchitect' ), 'path' => PZARC_PLUGIN_APP_PATH . '/shared/architect/php/field_types/' ),
          'date'            => array( 'description' => __( 'Date', 'pzarchitect' ), 'path' => PZARC_PLUGIN_APP_PATH . '/shared/architect/php/field_types/' ),
          'number'          => array( 'description' => __( 'Number', 'pzarchitect' ), 'path' => PZARC_PLUGIN_APP_PATH . '/shared/architect/php/field_types/' ),
          'link'            => array( 'description' => __( 'Link (URL)', 'pzarchitect' ), 'path' => PZARC_PLUGIN_APP_PATH . '/shared/architect/php/field_types/' ),
          'email'           => array( 'description' => __( 'Email', 'pzarchitect' ), 'path' => PZARC_PLUGIN_APP_PATH . '/shared/architect/php/field_types/' ),
          'embed'           => array( 'description' => __( 'Embed URL', 'pzarchitect' ), 'path' => PZARC_PLUGIN_APP_PATH . '/shared/architect/php/field_types/' ),
          // 'array'           => __( 'Array', 'pzarchitect' ), // Gargh! This makes fields in fields we need to prompt to format them.
          'file'            => array( 'description' => __( 'File', 'pzarchitect' ), 'path' => PZARC_PLUGIN_APP_PATH . '/shared/architect/php/field_types/' ),
          'map'             => array( 'description' => __( 'Map', 'pzarchitect' ), 'path' => PZARC_PLUGIN_APP_PATH . '/shared/architect/php/field_types/' ),
          'multi'           => array( 'description' => __( 'Multi value (Taxonomies, multiselect, checkboxes, radio)', 'pzarchitect' ), 'path' => PZARC_PLUGIN_APP_PATH . '/shared/architect/php/field_types/' ),
          'boolean'         => array( 'description' => __( 'Boolean', 'pzarchitect' ), 'path' => PZARC_PLUGIN_APP_PATH . '/shared/architect/php/field_types/' ),
          'repeater'        => array( 'description' => __( 'Repeater', 'pzarchitect' ), 'path' => PZARC_PLUGIN_APP_PATH . '/shared/architect/php/field_types/' ),
          'group'           => array( 'description' => __( 'Group', 'pzarchitect' ), 'path' => PZARC_PLUGIN_APP_PATH . '/shared/architect/php/field_types/' ),// WTF is a group Is it the aCF group?'path
      );
      foreach ( $field_types as $ftk => $ftv ) {
        $field_types[ $ftk ] = $return_path ? $ftv['path'] : $ftv['description'];
      }

      return apply_filters( 'arc_cfield_types', $field_types );
    }

    static function get_focal_point( $thumb_id ) {

//      switch ($type) {
//        case ( 'post' ):
      global $post;
      $focal_point = get_post_meta( $thumb_id, 'pzgp_focal_point', TRUE );
      if ( $post->post_type === 'attachment' ) {
        $thumb_id = $post->ID;
      }
      if ( empty( $focal_point ) ) {
        $focal_point = get_post_meta( get_the_id(), 'pzgp_focal_point', TRUE );
      }
      $focal_point = ( empty( $focal_point ) ? explode( ',', pzarc_get_option( 'architect_focal_point_default', '50,10' ) ) : explode( ',', $focal_point ) );
//          break;
//        case 'image':
//          break;
//      }
      return ( array( 'thumb_id' => $thumb_id, 'focal_point' => $focal_point ) );
    }

    /**
     * @param $i
     * @param $section
     * @param $data
     *
     * @return string
     */
    static function get_number_field( &$i, &$section, $data ) {
      // TODO: Process ACF settings e.g. prepend text
      // Numeric settings
      $meta['prefix'] = '';
      $meta['suffix'] = '';
      if ( $data['field-source'] == 'acf' ) {
        $field_object   = get_field_object( $data['name'] );
        $meta['prefix'] = $field_object['prepend'];
        $meta['suffix'] = $field_object['append'];
      }
      $data ['decimals']      = $section[ '_panels_design_cfield-' . $i . '-number-decimals' ];
      $data ['decimal-char']  = $section[ '_panels_design_cfield-' . $i . '-number-decimal-char' ];
      $data ['thousands-sep'] = $section[ '_panels_design_cfield-' . $i . '-number-thousands-separator' ];

      if ( $section[ '_panels_design_cfield-' . $i . '-field-type' ] === 'number' ) {
        //    $cfnumeric           = @number_format( $data ['value'], $data ['decimals'], '', '' );
        $cfnumeric     = @number_format( $data ['value'], $data ['decimals'], '', '' );
        $cfnumeric     = empty( $cfnumeric ) ? '0000' : $cfnumeric;
        $data ['data'] = "data-sort-numeric='{$cfnumeric}'";
      }

      $content = '';
      if ( ! empty( $data['value'] ) ) {
        $content = $meta['prefix'] . @number_format( $data['value'], $data['decimals'], $data['decimal-char'], $data['thousands-sep'] ) . $meta['suffix'];
      }

      return $content;
    }

    /**
     * @param $type
     *
     * @return string
     */
    static function get_field_type( $type ) {
      $return = $type;
      switch ( $type ) {
        case 'date_picker':
          $return = 'date';
      }

      return $return;
    }

    /**
     * Function: get_acf_fields
     * Desc: This gets all ACF fields, whether populated or not. It returns both a field dictionary (by group) and a field list.
     *
     * @param string $list_dictionary
     *
     * @return array/string
     */
    static function get_acf_fields( $list_dictionary = 'both' ) {
      // TODO: Add option to return jsut fields, not groups
      /*
       * Populate the field groups to the dictionary
       */
      $acf_fields_group_array = get_posts( array( 'post_type' => 'acf-field-group', 'numberposts' => - 1 ) );
      $acf_dictionary         = array();
      $acf_field_group_ids    = array();
      foreach ( $acf_fields_group_array as $kg => $vg ) {
        $acf_dictionary[ $vg->ID ]      = array(
            'group_name'     => $vg->post_excerpt,
            'group_key'      => $vg->post_name,
            'group_ID'       => $vg->ID,
            'group_title'    => $vg->post_title,
            'group_settings' => maybe_unserialize( $vg->post_content ),
        );
        $acf_field_group_ids[ $vg->ID ] = $vg->post_name;
      }


      /*
       * Populate the fields arrays
       */
      $acf_fields_array = get_posts( array( 'post_type' => 'acf-field', 'numberposts' => - 1 ) );
      //  var_dump($acf_fields_array);
      $acf_fields      = array();
      $acf_fields_list = array();
      foreach ( $acf_fields_array as $kf => $vf ) {
        $fsettings = maybe_unserialize( $vf->post_content );
        if ( isset( $acf_field_group_ids[ $vf->post_parent ] ) ) {
          $acf_parent      = 'field_group';
          $acf_parent_name = $acf_dictionary[ $vf->post_parent ]['group_name'];
        } else {
          $acf_parent_field          = get_post( $vf->post_parent );
          $acf_parent_field_settings = maybe_unserialize( $acf_parent_field->post_content );
          $acf_parent                = $acf_parent_field_settings['type'];
          $acf_parent_name           = $acf_parent_field->post_excerpt;
          $acf_parent_desc           = $acf_parent_field->post_title;
        }

        // TODO: Populate child fields here
        $acf_fields[ $vf->ID ] = array(
            'field_name'     => $vf->post_excerpt,
            'field_key'      => $vf->post_name,
            'field_type'     => $fsettings['type'],
            'field_ID'       => $vf->ID,
            'field_title'    => $vf->post_title,
            'field_settings' => $fsettings,
            'parent_ID'      => $vf->post_parent,
            'parent_type'    => $acf_parent,
            'parent_name'    => $acf_parent_name,
        );

        if ( $acf_parent != 'field_group' ) {
          $acf_parent_type                                               = strtoupper(substr( $acf_parent, 0, 1 )) . ' sub: ';
          $acf_fields_list[ $acf_parent_name . ':' . $vf->post_excerpt ] = $acf_parent_desc . ' : ' . $vf->post_title . ' (' . $acf_parent_type . str_replace( '_', ' ', $fsettings['type'] ) . ')';
        } else {

          $acf_fields_list[ $vf->post_excerpt ] = $vf->post_title . ' (' . str_replace( '_', ' ', $fsettings['type'] ) . ')';

        }
      }

      /*
       * Add fields to the  ACF dictionary
       */

      foreach ( $acf_dictionary as $k => $v ) {
        $acf_dictionary[ $k ]['fields'] = array();
      }
      foreach ( $acf_fields as $kd => $vd ) {
        $field_id  = $kd;
        $parent_id = $vd['parent_ID'];
        $level     = ( $vd['parent_type'] == 'field_group' ) ? 'toplevelfield' : 'childfield';
        $group_id  = ( $level == 'toplevelfield' ) ? $parent_id : $acf_fields[ $parent_id ]['parent_ID'];
        switch ( TRUE ) {
          // Field is top level  and field has not been added to the dictionary
          case $level == 'toplevelfield' && ! isset( $acf_dictionary[ $group_id ]['fields'][ $field_id ] ):
            $acf_dictionary[ $group_id ]['fields'][ $field_id ] = $acf_fields[ $field_id ];

            break;
          // Field is top level and field has children
          case $level == 'toplevelfield' && isset( $acf_dictionary[ $group_id ]['fields'][ $field_id ]['children'] ):
            $children                                                       = $acf_dictionary[ $group_id ]['fields'][ $field_id ]['children'];
            $acf_dictionary[ $group_id ]['fields'][ $field_id ]             = $acf_fields[ $field_id ];
            $acf_dictionary[ $group_id ]['fields'][ $field_id ]['children'] = $children;
            break;
          // Field is a child field
          case $level == 'childfield':
            $acf_dictionary[ $group_id ]['fields'][ $parent_id ]['children'][ $field_id ] = $acf_fields[ $field_id ];
            break;
          default:
            var_dump( 'Error missed: ' . $field_id );
        }

      }
      switch ( $list_dictionary ) {
        case 'list':
          return $acf_fields_list;
          break;
        case 'dictionary':
          return $acf_dictionary;
          break;
        case 'both':
        default:
          return array( 'dictionary' => $acf_dictionary, 'list' => $acf_fields_list );
          break;
      }

      // TODO: This is all well and good for toplevel fields.... but ACF concatenates repeater fields like repeater_0_field, repeater_1_field. So if you were trying to access the child fields...
      // TODO... hmmm?
    }

    /**
     * @return array
     */
    static function get_custom_fields( $pzarc_custom_fields = array(), $inc_custom_prefix = FALSE ) {
      global $wpdb;
      global $_architect_options;
      // WARNING: This may cause problems with missing custom fields
      // And it did!! FFS!!
//    if ( false === ( $pzarc_cf_list = get_transient( 'pzarc_cf_list' ) ) ) {
      // It wasn't there, so regenerate the data and save the transient

      // TODO: Need to replace ACF custom fields with all ACF custom fields and in a friendly structure
      if ( ! empty( $_architect_options['architect_exclude_hidden_custom'] ) ) {
        $pzarc_cf_list = $wpdb->get_results(//        "SELECT DISTINCT meta_key FROM $wpdb->postmeta HAVING meta_key NOT LIKE '\_%' ORDER BY meta_key"
            "SELECT DISTINCT meta_key FROM $wpdb->postmeta  HAVING (
          meta_key NOT LIKE '\_%' 
          ) ORDER BY meta_key" );

      } else {
        //Get custom fields
        // This is only able to get custom fields that have been used! ugh!
//    if ( false === ( $pzep_cf_list = get_transient( 'pzarc_custom_fields' ) ) ) {
        // It wasn't there, so regenerate the data and save the transient
        $pzarc_cf_list = $wpdb->get_results(//        "SELECT DISTINCT meta_key FROM $wpdb->postmeta ORDER BY meta_key"
            "SELECT DISTINCT meta_key FROM $wpdb->postmeta  HAVING (
          meta_key NOT LIKE '\_blueprints_%' AND
          meta_key NOT LIKE '\_panels_%' AND
          meta_key NOT LIKE '\_content_%' AND
          meta_key NOT LIKE '\_hw%'
          ) ORDER BY meta_key" );
      }
      //  set_transient( 'pzarc_cf_list', $pzarc_cf_list );
      //  }
      //    set_transient( 'pzarc_custom_fields', $pzep_cf_list, 0*PZARC_TRANSIENTS_KEEP );
      // }

//    $pzep_cf_list = $wpdb->get_results(
      ///// NEver select from wp_* as site might not use wp_ prefix
//        "SELECT meta_key,post_id,wp_posts.post_type FROM wp_postmeta,wp_posts GROUP BY meta_key HAVING ((meta_key NOT LIKE '\_%' AND meta_key NOT LIKE 'pz%' AND meta_key NOT LIKE 'enclosure%') AND (wp_posts.post_type NOT LIKE 'attachment' AND wp_posts.post_type NOT LIKE 'revision' AND wp_posts.post_type NOT LIKE 'acf' AND wp_posts.post_type NOT LIKE 'arc-%' AND wp_posts.post_type NOT LIKE 'nav_menu_item' AND wp_posts.post_type NOT LIKE 'wp-field_types%')) ORDER BY meta_key"
//    );
//    var_dump($pzarc_cf_list);
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
          'parent_ID',
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
          'sharing_disabled',
          'hide_on_screen',
          'hidden_from_ui',
      );
      $exclude_prefixes = array(
          '_blueprints',
          '_panels',
          'panels_',
          '_animation',
          '_pzarc_pagebuilder',
          '_pz',
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
          '_slick',
          'pzgp',
          'pzsp',
          'field_',
          'metaboxhidden',
          'ignore_redux_blast',
          'closedpostboxes',


      );
      foreach ( $pzarc_cf_list as $pzarc_cf ) {
        if ( in_array( $pzarc_cf->meta_key, $exclude_fields ) === FALSE && ! pzarc_starts_with( $exclude_prefixes, $pzarc_cf->meta_key ) ) {

          $pzarc_custom_fields[ ( $inc_custom_prefix ? 'customfield/' : '' ) . $pzarc_cf->meta_key ] = ( $inc_custom_prefix ? 'customfield/' : '' ) . $pzarc_cf->meta_key;
        }

      }

      ksort( $pzarc_custom_fields );

      $acf_fields = ArcFun::get_acf_fields( 'list' );
      ksort( $acf_fields );

      if ( ! empty( $acf_fields ) ) {
        foreach ( $acf_fields as $k => $v ) {
          switch ( TRUE ) {
            case array_key_exists( $k, $pzarc_custom_fields ):
              unset( $pzarc_custom_fields[ $k ] );
              break;
            case strpos( $k, ':' ):
              $field = explode( ':', $k );
              foreach ( $pzarc_custom_fields as $kc => $vc ) {

                if ( strpos( $kc, $field[0] ) === 0 && strpos( $kc, $field[1] ) > strlen( $field[0] ) + 2 ) {
                  unset( $pzarc_custom_fields[ $kc ] );
                }
              }
              break;
          }
        }

        $custom_fields = array( 'Advanced Custom Fields' => $acf_fields, 'Other Custom Fields' => $pzarc_custom_fields, );
      } else {
        $custom_fields = array( 'Other Custom Fields' => $pzarc_custom_fields, );

      }

      return $custom_fields;
    }

  }
