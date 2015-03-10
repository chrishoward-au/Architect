<?php

  if (!function_exists('pzdebug')) {

    //---------------------------------------------------------------------------------------------------
    // Debug
    //---------------------------------------------------------------------------------------------------
    /**
     * [pzdebug description]
     * @param  string $value ='' [description]
     * @return [type]           [description]
     */
    function pzdebug($value = '')
    {
      $btr  = debug_backtrace();
      $line = $btr[ 0 ][ 'line' ];
      $file = basename($btr[ 0 ][ 'file' ]);
      print"<pre>$file:$line</pre>\n";
      if (is_array($value)) {
        print"<pre>";
        print_r($value);
        print"</pre>\n";
      } elseif (is_object($value)) {
        var_dump($value);
      } else {
        print("<p>&gt;${value}&lt;</p>");
      }
    }

  }

  /**
   * @param $tax
   * @param $prefix
   * @param $suffix
   * @param $separator
   * @return string
   */
  function pzarc_tax_string_list($tax, $prefix, $suffix, $separator)
  {
    $list  = '';
    $count = count($tax);
    $i     = 1;
    if (is_array($tax)) {
      foreach ($tax as $key => $value) {
        $list .= $prefix . $value->slug . $suffix . ($i++ == $count ? '' : $separator);
      }
    }

    return $list;
  }

  /**
   * @param $array
   * @return array
   */
  function pzarc_squish($array)
  {
    $return_array = array();
    foreach ($array as $key => $value) {
      $return_array[ $key ] = $array[ $key ][ 0 ];
    }

    return $return_array;
  }

  /**
   * @param $start
   * @param $end
   * @param $source
   * @return mixed
   */
  function pzarc_get_string($start, $end, $source)
  {

    preg_match("/(?<=" . $start . ").+?(?=" . $end . ")/uim", $source, $result);

    return $result[ 0 ];

  }

  /**
   * @param        $id
   * @param        $selectors
   * @param string $defaults
   * @param array  $exclude
   * @return array
   */
  function pzarc_redux_font($id, $selectors, $defaults = '', $exclude = array())
  {
//var_dump($exclude,in_array('font-family',$exclude),in_array('color',$exclude),!array_search('font-family',$exclude));
    // TODO: Change font size to a range and use flowtype.js
//'architect_typography_units'
    global $_architect_options;
    $units        = isset($_architect_options[ 'architect_typography_units' ]) ? $_architect_options[ 'architect_typography_units' ] : 'px';
    $return_array = array(
        'title'           => __('Font', 'pzarchitect'),
        'id'              => $id,
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
    foreach ($return_array as $k => $v) {
      if (in_array($k, $exclude)) {
        $return_array[ $k ] = false;
      }
    }

    return $return_array;
  }


  /**
   * @param       $id
   * @param null  $selectors
   * @param array $defaults
   * @return array
   */
  function pzarc_redux_bg($id, $selectors = null, $defaults = array('color' => ''))
  {
    return array(
        'title'                 => __('Background', 'pzarchitect'),
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
   * @return array
   */
  function pzarc_redux_padding($id, $selectors, $defaults = array('units' => '%'))
  {
//    var_dump($id, $defaults);
    return array(
        'title'   => __('Padding', 'pzarchitect'),
        'id'      => $id,
        //      'output'  => $selectors,
        'mode'    => 'padding',
        'type'    => 'spacing',
        'units'   => array('%', 'px', 'em'),
        'default' => $defaults,
    );

  }

  /**
   * @param        $id
   * @param        $selectors
   * @param array  $defaults
   * @param string $limits
   * @return array
   */
  function pzarc_redux_margin($id, $selectors, $defaults = array('units' => '%'), $limits = 'tblr')
  {
    return array(
        'title'   => __('Margins', 'pzarchitect'),
        'id'      => $id,
        //        'output'  => $selectors,
        'mode'    => 'margin',
        'type'    => 'spacing',
        'units'   => array('%', 'px', 'em'),
        'default' => $defaults,
        'top'     => (strpos($limits, 't') !== false),
        'bottom'  => (strpos($limits, 'b') !== false),
        'left'    => (strpos($limits, 'l') !== false),
        'right'   => (strpos($limits, 'r') !== false)
    );

  }

  /**
   * @param       $id
   * @param       $selectors
   * @param array $defaults
   * @return array
   */
  function pzarc_redux_links($id, $selectors, $defaults = array())
  {
    return
        array(
            'title'   => __('Links', 'pzarchitect'),
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
   * @return array
   */
  function pzarc_redux_borders($id, $selectors, $defaults = '')
  {

    return array(
        'title'   => __('Border', 'pzarchitect'),
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
   * @return array
   */
  function pzarc_redux_border_radius($id, $selectors, $defaults = '')
  {

    return array(
        'title'    => __('Border Radius', 'pzarchitect'),
        'subtitle' => __('TopLeft, TopRight, BottomLeft, BottomRight', 'pzarchitect'),
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
   */
  function pzarc_get_defaults($use_cache = false)
  {
    pzdb('top get defaults');
    // TODO: Do we really need to call this on the front end??!!

    // TODO: Remove this once Dovy fixes MB defaults... or maybe not...
    // Actually, $_architect doesn't populate if it's not here
//    require_once PZARC_PLUGIN_APP_PATH . '/admin/php/class_arc_panels_layouts.php';
    require_once PZARC_PLUGIN_APP_PATH . '/admin/php/class_arc_blueprints_designer.php';
    $blueprints = new arc_Blueprints_Designer('defaults');
//    $panels     = new arc_Panels_Layouts('defaults');

    global $_architect;
    global $_architect_options;
    if (!isset($_architect[ 'defaults' ])) {
      /**
       * BLUEPRINTS
       *
       */
      pzdb('pre get blueprints defaults');
//    if ($use_cache) {
//
//    } else {

      $_architect[ 'defaults' ][ 'blueprints' ] = (!isset($_architect[ 'defaults' ][ 'blueprints' ]) ? array() : $_architect[ 'defaults' ][ 'blueprints' ]);

      $blueprint_layout_general                 = $blueprints->pzarc_blueprint_layout_general_mb($_architect[ 'defaults' ][ 'blueprints' ], true);
      $blueprint_styling                        = empty($_architect_options[ 'architect_enable_styling' ]) ? '' : $blueprints->pzarc_blueprint_layout_styling_mb($_architect[ 'defaults' ][ 'blueprints' ], true);
      $pzarc_blueprint_layout                   = $blueprints->pzarc_blueprint_layout_mb($_architect[ 'defaults' ][ 'blueprints' ], true);
      $pzarc_contents_metabox                   = $blueprints->pzarc_blueprint_contents_mb($_architect[ 'defaults' ][ 'blueprints' ], true);

      $_architect[ 'defaults' ][ 'blueprints' ][ '_blueprint_layout_general' ] = $blueprint_layout_general[ 0 ][ 'sections' ];
      $_architect[ 'defaults' ][ 'blueprints' ][ '_blueprint_stylings' ]       = empty($blueprint_styling) ? '' : $blueprint_styling[ 0 ][ 'sections' ];
      $_architect[ 'defaults' ][ 'blueprints' ][ '_blueprint_layout' ]         = $pzarc_blueprint_layout[ 0 ][ 'sections' ];
      $_architect[ 'defaults' ][ 'blueprints' ][ '_contents_metabox' ]         = $pzarc_contents_metabox[ 0 ][ 'sections' ];

      // Apply the defaults
      foreach ($_architect[ 'defaults' ][ 'blueprints' ] as $key1 => $value1) {
        if (!empty($value1)) {
          foreach ($value1 as $key2 => $value2) {
            foreach ($value2 as $key3 => $fields) {
              if (is_array($fields)) {
                foreach ($fields as $key4 => $field) {
                  if (isset($field[ 'id' ])) {
                    $_architect[ 'defaults' ][ '_blueprints' ][ $field[ 'id' ] ] = (empty($field[ 'default' ]) ? '' : $field[ 'default' ]);
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
      pzdb('pre get panels defaults');
      $_architect[ 'defaults' ][ 'panels' ] = (!isset($_architect[ 'defaults' ][ 'panels' ]) ? array() : $_architect[ 'defaults' ][ 'panels' ]);

//      $pzarc_panel_general_settings = $blueprints->pzarc_panel_general_settings($_architect[ 'defaults' ][ 'panels' ], true);
      $pzarc_panels_design          = $blueprints->pzarc_panels_design($_architect[ 'defaults' ][ 'panels' ], true);
      $pzarc_panels_styling         = empty($_architect_options[ 'architect_enable_styling' ]) ? '' : $blueprints->pzarc_panels_styling($_architect[ 'defaults' ][ 'panels' ], true);

 //     $_architect[ 'defaults' ][ 'panels' ][ '_panel_general_settings' ] = $pzarc_panel_general_settings[ 0 ][ 'sections' ];
      $_architect[ 'defaults' ][ 'panels' ][ '_panels_design' ]          = $pzarc_panels_design[ 0 ][ 'sections' ];

      if (!empty($_architect_options[ 'architect_enable_styling' ])) {
        $_architect[ 'defaults' ][ 'panels' ][ '_panels_styling' ] = $pzarc_panels_styling[ 0 ][ 'sections' ];
      }

      foreach ($_architect[ 'defaults' ][ 'panels' ] as $key1 => $value1) {
        if (!empty($value1)) {
          foreach ($value1 as $key2 => $value2) {
            foreach ($value2 as $key3 => $fields) {
              if (is_array($fields)) {
                foreach ($fields as $key4 => $field) {
                  if (isset($field[ 'id' ])) {
                    $_architect[ 'defaults' ][ '_blueprints' ][ $field[ 'id' ] ] = (empty($field[ 'default' ]) ? '' : $field[ 'default' ]);
                  }
                }
              }
            }
          }
        }
      }
      pzdb('bottom get defaults');

      //  Unset the temporary blueprints field
      unset($_architect[ 'defaults' ][ 'blueprints' ]);
      //  Unset the temporary panels field
      unset($_architect[ 'defaults' ][ 'panels' ]);
    }
  }

  /**
   * @param $defaultvs
   * @param $setvals
   * @return mixed
   */
  function pzarc_merge_defaults($defaultvs, $setvals)
  {

    foreach ($defaultvs as $key => $value) {
      if (!isset($setvals[ $key ])) {
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
  function pzarc_action_test($component, $panelno, $postid)
  {
    echo '<h2>Action run:' . current_action() . '</h2>';
    var_dump($component, $panelno, $postid);
  }

  // For testing filters
//add_filter('arc_filter_excerpt','pzarc_filter_test',10,2);
  /**
   * @param $stuff
   * @param $postid
   * @return string
   */
  function pzarc_filter_test($stuff, $postid)
  {
    return $stuff . '--more stuff added by filter--' . $postid;
  }

  //add_filter('arc_filter_shortcode', 'pzarc_scf_test', 10, 3);
  /**
   * @param $content
   * @param $blueprint
   * @param $overrides
   * @return string
   */
  function pzarc_scf_test($content, $blueprint, $overrides)
  {
    return '<div class="pzarc-shortcode-debug" style="background:#fff4f4;border:solid 1px #c99;box-sizing: border-box;"><h3>Start shortcode blueprint ' . $blueprint . ' with ' . count($overrides) . ' overrides</h3>' . $content . '<h3>End blueprint ' . $blueprint . '</h3>';
  }

  /**
   * Convert any variable to an array
   *
   * @param $delimiter
   * @param $var
   * @return array
   */
  function pzarc_to_array($delimiter, $var)
  {
    return (is_array($var) ? $var : explode($delimiter, (string)$var));
  }

  /**
   * @return array
   */
  function pzarc_fields()
  {
    $arg_list = func_get_args();
    $returna  = array();
    foreach ($arg_list as $k => $v) {
      if (isset($v[ 0 ])) {
        foreach ($v as $k2 => $v2) {
          $returna[ ] = $v2;
        }
      } else {
        $returna[ ] = $v;
      }
    }

    return $returna;
  }

  add_action('xloop_start', 'pzarc_top_of_loop', 10, 1);
  /**
   * @param $the_query
   */
  function pzarc_top_of_loop(&$the_query)
  {

    if (is_main_query()) {
      echo '<h1 style="font-size:24px;font-weight:bold;color:red;">Loop starts here</h1>';
//      var_dump($the_query);
    }
  }

  add_action('xloop_end', 'pzarc_bottom_of_loop');
  /**
   *
   */
  function pzarc_bottom_of_loop()
  {
    if (is_main_query()) {
      echo '<h1 style="font-size:24px;font-weight:bold;color:red;">Loop ends here</h1>';
    }
  }

  /**
   * @return array|null
   */
  function pzarc_get_gp_galleries()
  {
    $post_types = get_post_types();
    if (!isset($post_types[ 'gp_gallery' ])) {
      return null;
    }
    // Don't need to check for GPlus class coz we add the post type
    // Get GalleryPlus galleries
    $args    = array('post_type' => 'gp_gallery', 'numberposts' => -1, 'post_status' => null, 'post_parent' => null);
    $albums  = get_posts($args);
    $results = array();
    if ($albums) {
      foreach ($albums as $post) {
        setup_postdata($post);
        $results[ $post->ID ] = get_the_title($post->ID);
      }
    }

    return $results;
  }

  /**
   * @return array
   */
  function pzarc_get_wp_galleries()
  {

    // Get galleries in posts and pages
    $args    = array('post_type'   => array('post', 'page'),
                     'numberposts' => -1,
                     'post_status' => null,
                     'post_parent' => null);
    $albums  = get_posts($args);
    $results = array();
    if ($albums) {
      foreach ($albums as $post) {
        setup_postdata($post);
        if (get_post_gallery($post->ID)) {
          $results[ $post->ID ] = substr(get_the_title($post->ID), 0, 60);
        }
      }
    }

    return $results;


  }

  /**
   * @return array
   */
  function pzarc_get_wp_post_images()
  {
    $results = array('todo' => 'TODO!!!');

    return $results;
  }

  /**
   * @param bool $inc_all
   * @param int  $min_level
   * @return array
   */
  function pzarc_get_authors($inc_all = true, $min_level = 1)
  {
    // user_level 1 = contributor
// Get authors
    $userslist = get_users();
    $authors   = array();
    if ($inc_all) {
      $authors[ 0 ] = 'All';
    }
    foreach ($userslist as $author) {
      if (get_the_author_meta('user_level', $author->ID) >= $min_level) {
        $authors[ $author->ID ] = $author->display_name;
      }
    }

    return $authors;
  }

  function pzarc_get_custom_post_types()
  {
    $pzarc_cpts = (get_post_types(array('_builtin' => false, 'public' => true), 'objects'));
    $return     = array();
    foreach ($pzarc_cpts as $key => $value) {
      $return[ $key ] = $value->labels->name;
    }

    return $return;
  }

  /**
   * @param string $pzarc_post_type
   * @param bool   $use_shortname
   * @return array
   */
  function pzarc_get_posts_in_post_type($pzarc_post_type = 'arc-blueprints', $use_shortname = false)
  {
//    // No point doing this if not on a screen that can use it.
// Except it didn't work!
//    if (!function_exists('get_current_screen')) {
//      return array();
//    }
//    // No point doing this if not on a screen that can use it.
    if (!is_admin()) {
      return array();
    }
    $args                 = array(
        'posts_per_page'   => -1,
        'orderby'          => 'post_title',
        'order'            => 'ASC',
        'post_type'        => $pzarc_post_type,
        'post_status'      => 'publish',
        'suppress_filters' => true);
    $pzarc_post_types_obj = get_posts($args);
    $pzarc_post_type_list = array();

    foreach ($pzarc_post_types_obj as $pzarc_post_type_obj) {

      if ($use_shortname) {

        if ($pzarc_post_type === 'arc-blueprints') {
          $use_key = get_post_meta($pzarc_post_type_obj->ID, '_blueprints_short-name', true);
        } elseif ($pzarc_post_type === 'arc-panels') {
          $use_key = get_post_meta($pzarc_post_type_obj->ID, '_panels_settings_short-name', true);
        } else {
          $use_key = $pzarc_post_type_obj->post_name;
        }

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
   * @return array
   */
  function pzarc_get_blueprints($inc_post_id = false)
  {
    $query_options    = array(
        'post_type'      => 'arc-blueprints',
        'meta_key'       => '_blueprints_short-name',
        'posts_per_page' => '-1'
    );
    $blueprints_query = new WP_Query($query_options);
    $pzarc_return     = array();
    while ($blueprints_query->have_posts()) {
      $blueprints_query->next_post();
      $the_panel_meta = get_post_meta($blueprints_query->post->ID);
      $bpid           = $the_panel_meta[ '_blueprints_short-name' ][ 0 ] . ($inc_post_id ? '##' . $blueprints_query->post->ID : '');
      // This caused an error with the WooCommerce 2.3
      //     $pzarc_return[ $bpid ] = get_the_title($blueprints_query->post->ID);
      $pzarc_return[ $bpid ] = $blueprints_query->post->post_title;
    };
    asort($pzarc_return);
    wp_reset_postdata();

    return $pzarc_return;
  }

  /**
   * @param $source_arr
   * @param $selected
   */
  function pzarc_array_to_options_list($source_arr, $selected)
  {
    foreach ($source_arr as $key => $value) {
      echo '<option value="' . esc_attr($key) . '" ' . ($selected == $key ? 'selected' : null) . '>' . esc_attr($value) . '</option>';
    }
  }

  /**
   * @return array
   */
  function pzarc_get_custom_fields()
  {
    global $wpdb;

    //Get custom fields
    // This is only able to get custom fields that have been used! ugh!
//    if ( false === ( $pzep_cf_list = get_transient( 'pzarc_custom_fields' ) ) ) {
    // It wasn't there, so regenerate the data and save the transient
    $pzep_cf_list = $wpdb->get_results(
        "SELECT DISTINCT meta_key FROM $wpdb->postmeta HAVING (meta_key NOT LIKE '\_blueprints%' AND meta_key NOT LIKE '_panels%' AND meta_key NOT LIKE '_hw%'  AND meta_key NOT LIKE '_wp_%' AND meta_key NOT LIKE '_format%' AND meta_key NOT LIKE '_edit%' AND meta_key NOT LIKE '_content%' AND meta_key NOT LIKE '_attachment%' AND meta_key NOT LIKE '_menu%' AND meta_key NOT LIKE '_oembed%' AND meta_key NOT LIKE '_publicize%' AND meta_key NOT LIKE '_thumbnail%' AND meta_key NOT LIKE 'pz%' AND meta_key NOT LIKE 'field_%') ORDER BY meta_key"
    );
    //    set_transient( 'pzarc_custom_fields', $pzep_cf_list, 0*PZARC_TRANSIENTS_KEEP );
    // }

//    $pzep_cf_list = $wpdb->get_results(
//        "SELECT meta_key,post_id,wp_posts.post_type FROM wp_postmeta,wp_posts GROUP BY meta_key HAVING ((meta_key NOT LIKE '\_%' AND meta_key NOT LIKE 'pz%' AND meta_key NOT LIKE 'enclosure%') AND (wp_posts.post_type NOT LIKE 'attachment' AND wp_posts.post_type NOT LIKE 'revision' AND wp_posts.post_type NOT LIKE 'acf' AND wp_posts.post_type NOT LIKE 'arc-%' AND wp_posts.post_type NOT LIKE 'nav_menu_item' AND wp_posts.post_type NOT LIKE 'wp-types%')) ORDER BY meta_key"
//    );
    //   var_dump($pzep_cf_list);
    $exclude_fields = array(
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

    $pzep_custom_fields = array();
    foreach ($pzep_cf_list as $pzep_cf) {
      if (in_array($pzep_cf->meta_key, $exclude_fields) === false) {
        $pzep_custom_fields[ $pzep_cf->meta_key ] = $pzep_cf->meta_key;
      }
    }

    return $pzep_custom_fields;
  }

  /**
   * @param $text
   * @param $type
   */
  function pzarc_msg($text, $type)
  {
    echo '<div class="message-' . $type . '">' . $text . '</div>';
  }


  /***********************
   *
   * Flatten wp arrays if necessary
   *
   ***********************/

  function pzarc_flatten_wpinfo($array_in, $strip = null)
  {
    $array_out = array();
    foreach ($array_in as $key => $value) {
      if ($key == '_edit_lock' || $key == '_edit_last' || strpos($key, $strip) !== false) {
        continue;
      }
      if (is_array($value)) {
        $array_out[ $key ] = $value;
      }
      $array_out[ $key ] = maybe_unserialize($value[ 0 ]);
    }

    return $array_out;
  }

  /**
   * @param $post_id
   * @param $meta_string
   * @return array
   */
  function pzarc_get_post_terms($post_id, $meta_string)
  {
    $post_tax_terms = array();
    $meta_custom    = substr_count($meta_string, '%ct:');
    preg_match_all("/(?<=\\%)(ct\\:)(.*)(?=\\%)/uiUmx", $meta_string, $matches);
    for ($i = 1; $i <= $meta_custom; $i++) {
      if (taxonomy_exists($matches[ 2 ][ $i - 1 ])) {
        $post_tax_terms = array($matches[ 2 ][ $i - 1 ] => get_the_term_list($post_id, $matches[ 2 ][ $i - 1 ]));
      }
    }

    return $post_tax_terms;
  }

  /**
   * @param $post_name
   * @return mixed
   *
   * Convert slug name to post ID
   *
   * We need this because we can't save post IDs else things aren't transportable
   */
  function pzarc_convert_name_to_id($post_name)
  {
    global $wpdb, $_architect_options;
    // We don't want transients used for admins since they may be testing new settings - which won't take!
    if (!empty($_architect_options[ 'architect_enable_query_cache' ]) && !current_user_can('manage_options') && false === ($post_id = get_transient('pzarc_post_name_to_id_' . $post_name))) {
      // It wasn't there, so regenerate the data and save the transient
      $post_id = $wpdb->get_var("SELECT ID FROM $wpdb->posts WHERE post_name = '" . $post_name . "'");
      set_transient('pzarc_post_name_to_id_' . $post_name, $post_id, PZARC_TRANSIENTS_KEEP);
    } elseif (current_user_can('manage_options') || empty($_architect_options[ 'architect_enable_query_cache' ])) {
      $post_id = $wpdb->get_var("SELECT ID FROM $wpdb->posts WHERE post_name = '" . $post_name . "'");
    }


    return $post_id;
  }

  /**
   * @param $vcode
   * @return false|string
   */
  function pzarc_process_video($vcode)
  {

    $vcode_type      = 'unknown';
    $vcode_processed = '';

    switch (true) {
      case (strpos(strtolower($vcode), 'http') === 0):
        if (strpos($vcode, home_url()) === 0) {
          $vcode      = '[video src="' . $vcode . '"]';
          $vcode_type = 'shortcode';
        } else {
          $vcode_type = 'url';
        }
        break;
      case (strpos(strtolower($vcode), '<iframe ') === 0):
        $vcode_type = 'embed';
        break;
      case (strpos(strtolower($vcode), '[') === 0):
        $vcode_type = 'shortcode';
        break;
    }

    switch ($vcode_type) {
      case 'url':
        // This also throws securing the url back to wp!
        $vcode_processed = wp_oembed_get($vcode);
        break;
      case 'embed':
        $vcode_processed = $vcode;
        break;
      case 'shortcode':
        $vcode_processed = do_shortcode($vcode);
        break;
    }

    return $vcode_processed;
  }

  /**
   * A simple minifier for CSS from https://ikreativ.com/combine-minify-css-with-php/
   * @param  [type] $minify [description]
   * @return [type]         [description]
   */
  function pzarc_compress($minify)
  {
    /* remove comments */
    $minify = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $minify);

    /* remove tabs, spaces, newlines, etc. */
    $minify = str_replace(array("\r\n", "\r", "\n", "\t"), '', $minify);
    $minify = str_replace(array('  ', '    ', '    '), ' ', $minify);

    return $minify;
  }

  add_shortcode('pztestsc', 'pzarc_test_shortcode');
  /**
   * @param $atts
   * @return string
   */
  function pzarc_test_shortcode($atts)
  {
    return 'Shortcode test';
  }

  /**
   * @param $values
   * @return array
   */
  function pzarc_maths_sum($values)
  {
    $result = 0;
    $vtype  = '';
    switch (true) {
      case strpos($values[ 1 ], 'px'):
        $vtype = 'px';
        break;
      case strpos($values[ 1 ], 'rem'):
        $vtype = 'rem';
        break;
      case strpos($values[ 1 ], '%'):
        $vtype = '%';
        break;
      case strpos($values[ 1 ], 'em'):
        $vtype = 'em';
        break;
    }
    foreach ($values as $v) {
      $vclean = str_replace(array('%', 'px', 'em', 'rem'), '', $v);
      $result += $vclean;
    }

    return array('result' => $result, 'type' => $vtype);
  }

  /**
   * @param $atts
   * @param $rawemail
   * @param $tag
   * @return string
   */
  function pzarc_mail_encode($atts, $rawemail, $tag)
  {
    $s_email     = sanitize_email($rawemail);
    $encodedmail = '';
    for ($i = 0; $i < strlen($s_email); $i++) {
      $encodedmail .= "&#" . ord($s_email[ $i ]) . ';';
    }
    if (isset($atts[ 0 ])) {
      return '<a href="mailto:' . $encodedmail . '">' . $encodedmail . '</a>';
    } else {
      return $encodedmail;
    }
  }

  if (!shortcode_exists('mailto')) {
    add_shortcode('mailto', 'pzarc_mail_encode');
  }
  add_shortcode('pzmailto', 'pzarc_mail_encode');

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