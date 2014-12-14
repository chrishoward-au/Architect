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

  function pzarc_squish($array)
  {
    $return_array = array();
    foreach ($array as $key => $value) {
      $return_array[ $key ] = $array[ $key ][ 0 ];
    }

    return $return_array;
  }


  function pzarc_get_string($start, $end, $source)
  {

    preg_match("/(?<=" . $start . ").+?(?=" . $end . ")/uim", $source, $result);

    return $result[ 0 ];

  }

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
        'output'          => $selectors,
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


  function pzarc_redux_bg($id, $selectors = null, $defaults = array('color' => ''))
  {
    return array(
        'title'                 => __('Background', 'pzarchitect'),
        'id'                    => $id,
        //        'output'                => $selectors,
        //        'compiler'              => $selectors,
        'type'                  => 'spectrum',
        'mode'                  => 'backgound-color',
        'background-image'      => false,
        'background-repeat'     => false,
        'background-size'       => false,
        'background-attachment' => false,
        'background-position'   => false,
        'preview'               => false,
        'default'               => $defaults,
    );
  }

  function pzarc_redux_padding($id, $selectors, $defaults = '')
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

  function pzarc_redux_margin($id, $selectors, $defaults = '')
  {
    return array(
        'title'   => __('Margins', 'pzarchitect'),
        'id'      => $id,
        //        'output'  => $selectors,
        'mode'    => 'margin',
        'type'    => 'spacing',
        'units'   => array('%', 'px', 'em'),
        'default' => $defaults,
    );

  }

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

  function pzarc_set_defaults()
  {
    // TODO: Do we really need to call this on the front end??!!

    // TODO: Remove this once Dovy fixes MB defaults... or maybe not...
    // Actually, $_architect doesn't populate if it's not here
    require_once PZARC_PLUGIN_APP_PATH . '/admin/php/class_arc_Panels_Layouts.php';
    require_once PZARC_PLUGIN_APP_PATH . '/admin/php/class_arc_Blueprints_Layouts.php';
    $blueprints = new arc_Blueprints_Layouts('defaults');
    $panels     = new arc_Panels_Layouts('defaults');

    global $_architect;
    global $_architect_options;

    // BLUEPRINTS
    $_architect[ 'defaults' ][ 'blueprints' ] = (!isset($_architect[ 'defaults' ][ 'blueprints' ]) ? array() : $_architect[ 'defaults' ][ 'blueprints' ]);
    $blueprint_layout_general                 = $blueprints->pzarc_blueprint_layout_general_mb($_architect[ 'defaults' ][ 'blueprints' ]);
    //  $pzarc_blueprint_content_general                                          = $blueprints->pzarc_blueprint_content_general_mb($_architect[ 'defaults' ][ 'blueprints' ]);
    $pzarc_blueprint_layout                                                  = $blueprints->pzarc_blueprint_layout_mb($_architect[ 'defaults' ][ 'blueprints' ]);
    $pzarc_contents_metabox                                                  = $blueprints->pzarc_blueprint_contents_mb($_architect[ 'defaults' ][ 'blueprints' ]);
    $_architect[ 'defaults' ][ 'blueprints' ][ '_blueprint_layout_general' ] = $blueprint_layout_general[ 0 ][ 'sections' ];
    //   $_architect[ 'defaults' ][ 'blueprints' ][ '_blueprint_content_general' ] = $pzarc_blueprint_content_general[ 0 ][ 'sections' ];
    $_architect[ 'defaults' ][ 'blueprints' ][ '_blueprint_layout' ] = $pzarc_blueprint_layout[ 0 ][ 'sections' ];
    $_architect[ 'defaults' ][ 'blueprints' ][ '_contents_metabox' ] = $pzarc_contents_metabox[ 0 ][ 'sections' ];

    // PANELS
    $_architect[ 'defaults' ][ 'panels' ]                              = (!isset($_architect[ 'defaults' ][ 'panels' ]) ? array() : $_architect[ 'defaults' ][ 'panels' ]);
    $pzarc_panel_general_settings                                      = $panels->pzarc_panel_general_settings($_architect[ 'defaults' ][ 'panels' ]);
    $pzarc_panels_design                                               = $panels->pzarc_panels_design($_architect[ 'defaults' ][ 'panels' ]);
    $pzarc_panels_styling                                              = $panels->pzarc_panels_styling($_architect[ 'defaults' ][ 'panels' ]);
    $_architect[ 'defaults' ][ 'panels' ][ '_panel_general_settings' ] = $pzarc_panel_general_settings[ 0 ][ 'sections' ];
    $_architect[ 'defaults' ][ 'panels' ][ '_panels_design' ]          = $pzarc_panels_design[ 0 ][ 'sections' ];

    if (!empty($_architect_options[ 'architect_enable_styling' ])) {
      $_architect[ 'defaults' ][ 'panels' ][ '_panels_styling' ] = $pzarc_panels_styling[ 0 ][ 'sections' ];
    }
    foreach ($_architect[ 'defaults' ][ 'blueprints' ] as $key1 => $value1) {
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

    unset($_architect[ 'defaults' ][ 'blueprints' ]);

    foreach ($_architect[ 'defaults' ][ 'panels' ] as $key1 => $value1) {
      foreach ($value1 as $key2 => $value2) {
        foreach ($value2 as $key3 => $fields) {
          if (is_array($fields)) {
            foreach ($fields as $key4 => $field) {
              if (isset($field[ 'id' ])) {
                $_architect[ 'defaults' ][ '_panels' ][ $field[ 'id' ] ] = (empty($field[ 'default' ]) ? '' : $field[ 'default' ]);
              }
            }
          }
        }
      }
    }
    unset($_architect[ 'defaults' ][ 'panels' ]);

  }

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
  function pzarc_action_test($component, $panelno, $postid)
  {
    echo '<h2>Action run:' . current_action() . '</h2>';
    var_dump($component, $panelno, $postid);
  }

  // For testing filters
//add_filter('arc_filter_excerpt','pzarc_filter_test',10,2);
  function pzarc_filter_test($stuff, $postid)
  {
    return $stuff . '--more stuff added by filter--' . $postid;
  }

  //add_filter('arc_filter_shortcode', 'pzarc_scf_test', 10, 3);
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
  function pzarc_top_of_loop(&$the_query)
  {

    if (is_main_query()) {
      echo '<h1 style="font-size:24px;font-weight:bold;color:red;">Loop starts here</h1>';
//      var_dump($the_query);
    }
  }

  add_action('xloop_end', 'pzarc_bottom_of_loop');
  function pzarc_bottom_of_loop()
  {
    if (is_main_query()) {
      echo '<h1 style="font-size:24px;font-weight:bold;color:red;">Loop ends here</h1>';
    }
  }

  function pzarc_get_ng_galleries()
  {
    if (!class_exists('P_Photocrati_NextGen')) {
      return null;
    }
    global $ngg, $nggdb;
    $results = array();


    $ng_galleries = $nggdb->find_all_galleries('gid', 'asc', true, 0, 0, false);

    if ($ng_galleries) {
      foreach ($ng_galleries as $gallery) {
        $results[ $gallery->gid ] = $gallery->title;
      }
    }

    return $results;
  }

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

  function pzarc_get_posts_in_post_type($pzarc_post_type = 'arc-blueprints')
  {
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
      $pzarc_post_type_list[ $pzarc_post_type_obj->post_name ] = $pzarc_post_type_obj->post_title;
    }

    return $pzarc_post_type_list;
  }

  function pzarc_array_to_options_list($source_arr, $selected)
  {
    foreach ($source_arr as $key => $value) {
      echo '<option value="' . esc_attr($key) . '" ' . ($selected == $key ? 'selected' : null) . '>' . esc_attr($value) . '</option>';
    }
  }

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


  function pzarc_msg($text, $type)
  {
    echo '<div class="message-' . $type . '">' . $text . '</div>';
  }

  /**
   * Class showBlueprint
   *
   * Provides an easy method for users to inject blueprints at any action hook
   */
  class showBlueprint
  {

    // Pass $data var to class
    function __construct($action, $blueprint, $pageid = 'home', $overrides = null, $caller = 'template_tag')
    {
      $this->blueprint = $blueprint; // Get data in var
      $this->action    = $action;
      $this->overrides = $overrides;
      $this->caller    = $caller;
      $this->pageid    = $pageid;
      add_action($action, array(&$this, 'render'));
    }

    public function render()
    {
      //TODO: Get the page conditionals working
      //     switch (true) {
//        case ('home' === $this->pageid && (is_home() || is_front_page())) :
      pzarchitect($this->blueprint, $this->overrides, $this->caller);
      //        break;
      //   }
    }
  } // EOC showBlueprint

  /***********************
   *
   * Flatten wp arrays if necessary
   *
   ***********************/

  function pzarc_flatten_wpinfo($array_in)
  {
    $array_out = array();
    foreach ($array_in as $key => $value) {
      if ($key == '_edit_lock' || $key == '_edit_last') {
        continue;
      }
      if (is_array($value)) {
        $array_out[ $key ] = $value;
      }
      $array_out[ $key ] = maybe_unserialize($value[ 0 ]);
    }

    return $array_out;
  }

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
    global $wpdb;
    // We don't want transients used for admins since they may be testing new settings - which won't take!
    if (!current_user_can('manage_options') && false === ($post_id = get_transient('pzarc_post_name_to_id_' . $post_name))) {
      // It wasn't there, so regenerate the data and save the transient
      $post_id = $wpdb->get_var("SELECT ID FROM $wpdb->posts WHERE post_name = '" . $post_name . "'");
      set_transient('pzarc_post_name_to_id_' . $post_name, $post_id, PZARC_TRANSIENTS_KEEP);
    } elseif (current_user_can('manage_options')) {
      $post_id = $wpdb->get_var("SELECT ID FROM $wpdb->posts WHERE post_name = '" . $post_name . "'");
    }


    return $post_id;
  }

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
  function pzarc_test_shortcode($atts)
  {
    return 'Shortcode test';
  }

  // TODO: This is a sorta duplicate of pzarc_get_posts_in_type. Fix it one day.
  function pzarc_get_blueprints($inc_post_id=false)
  {
    $query_options = array(
        'post_type'      => 'arc-blueprints',
        'meta_key'       => '_blueprints_short-name',
        'posts_per_page' => '-1'
    );
    $blueprints_query = new WP_Query($query_options);
    $pzarc_return     = array();
    while ($blueprints_query->have_posts()) {
      $blueprints_query->next_post();
      $the_panel_meta        = get_post_meta($blueprints_query->post->ID);
      $bpid                  = $the_panel_meta[ '_blueprints_short-name' ][ 0 ] . ($inc_post_id?'##' . $blueprints_query->post->ID:'');
      $pzarc_return[ $bpid ] = get_the_title($blueprints_query->post->ID);
    };
    asort($pzarc_return);
    wp_reset_postdata();
    return $pzarc_return;
  }

  // Testing function.
//  add_filter( 'the_content', 'cv_display_random_imgs_home' );
//  function cv_display_random_imgs_home( $content ) {
//    $custom_content = '1WTF'.$content;
//    // Theoretically, this shouldn't run in most instances coz Architect is not the main loop. Template tags or a specific action could be the exception.
//    if ( is_main_query() ) {
//      $custom_content .= '2WTF';
//    }
//    return $custom_content;
//  }