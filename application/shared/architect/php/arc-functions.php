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

  function pzarc_redux_font($id, $selectors, $defaults = null)
  {

    // TODO: Change font size to a range and use flowtype.js
    return array(
        'title'           => __('Font', 'pzarc'),
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
        'default'         => $defaults,
    );
  }


  function pzarc_redux_bg($id, $selectors = null, $defaults = null)
  {
    return array(
        'title'                 => __('Background', 'pzarc'),
        'id'                    => $id,
        'output'                => $selectors,
        'compiler'              => $selectors,
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

  function pzarc_redux_padding($id, $selectors, $defaults = null)
  {
    return array(
        'title'   => __('Padding', 'pzarc'),
        'id'      => $id,
        'output'  => $selectors,
        'mode'    => 'padding',
        'type'    => 'spacing',
        'units'   => array('px', '%'),
        'default' => $defaults,
    );

  }

  function pzarc_redux_margin($id, $selectors, $defaults = null)
  {
    return array(
        'title'   => __('Margins', 'pzarc'),
        'id'      => $id,
        'output'  => $selectors,
        'mode'    => 'margin',
        'type'    => 'spacing',
        'units'   => array('px', '%'),
        'default' => $defaults,
    );

  }

  function pzarc_redux_links($id, $selectors, $defaults = null)
  {
    return
        array(
            'title'   => __('Links', 'pzarc'),
            'id'      => $id,
            'type'    => 'links',
            'output'  => $selectors,
            'default' => $defaults,
        );

  }

//  function pzarc_redux_links_dec($id, $selectors, $defaults = null)
//  {
//    return array(
//        'title'   => __('Links underline', 'pzarc'),
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

  function pzarc_redux_borders($id, $selectors, $defaults = null)
  {

    return array(
        'title'   => __('Border', 'pzarc'),
        'id'      => $id,
        'type'    => 'border',
        'all'     => false,
        'output'  => $selectors,
        'default' => $defaults
    );
  }

  function pzarc_set_defaults()
  {
    // TODO: Do we really need to call this on the front end??!!

    // TODO: Remove this once Dovy fixes MB defaults... or maybe not...
    // Actually, $pzarchitect doesn't populate if it's not here
    require_once PZARC_PLUGIN_APP_PATH . '/admin/php/class_arc_Panels_Layouts.php';
    require_once PZARC_PLUGIN_APP_PATH . '/admin/php/class_arc_Blueprints_Layouts.php';

    global $pzarchitect;
    global $_architect_options;

    // BLUEPRINTS
    $pzarchitect[ 'defaults' ][ 'blueprints' ]                                 = (!isset($pzarchitect[ 'defaults' ][ 'blueprints' ]) ? array() : $pzarchitect[ 'defaults' ][ 'blueprints' ]);
    $blueprint_layout_general                                                  = pzarc_blueprint_layout_general($pzarchitect[ 'defaults' ][ 'blueprints' ]);
    $pzarc_blueprint_content_general                                           = pzarc_blueprint_content_general($pzarchitect[ 'defaults' ][ 'blueprints' ]);
    $pzarc_blueprint_layout                                                    = pzarc_blueprint_layout($pzarchitect[ 'defaults' ][ 'blueprints' ]);
    $pzarc_contents_metabox                                                    = pzarc_contents_metabox($pzarchitect[ 'defaults' ][ 'blueprints' ]);
    $pzarchitect[ 'defaults' ][ 'blueprints' ][ '_blueprint_layout_general' ]  = $blueprint_layout_general[ 0 ][ 'sections' ];
    $pzarchitect[ 'defaults' ][ 'blueprints' ][ '_blueprint_content_general' ] = $pzarc_blueprint_content_general[ 0 ][ 'sections' ];
    $pzarchitect[ 'defaults' ][ 'blueprints' ][ '_blueprint_layout' ]          = $pzarc_blueprint_layout[ 0 ][ 'sections' ];
    $pzarchitect[ 'defaults' ][ 'blueprints' ][ '_contents_metabox' ]          = $pzarc_contents_metabox[ 0 ][ 'sections' ];

    // PANELS
    $pzarchitect[ 'defaults' ][ 'panels' ]                              = (!isset($pzarchitect[ 'defaults' ][ 'panels' ]) ? array() : $pzarchitect[ 'defaults' ][ 'panels' ]);
    $pzarc_panel_general_settings                                       = pzarc_panel_general_settings($pzarchitect[ 'defaults' ][ 'panels' ]);
    $pzarc_panels_design                                                = pzarc_panels_design($pzarchitect[ 'defaults' ][ 'panels' ]);
    $pzarc_panels_styling                                               = pzarc_panels_styling($pzarchitect[ 'defaults' ][ 'panels' ]);
    $pzarchitect[ 'defaults' ][ 'panels' ][ '_panel_general_settings' ] = $pzarc_panel_general_settings[ 0 ][ 'sections' ];
    $pzarchitect[ 'defaults' ][ 'panels' ][ '_panels_design' ]          = $pzarc_panels_design[ 0 ][ 'sections' ];

    if (!empty($_architect_options[ 'architect_enable_styling' ])) {
      $pzarchitect[ 'defaults' ][ 'panels' ][ '_panels_styling' ] = $pzarc_panels_styling[ 0 ][ 'sections' ];
    }
    foreach ($pzarchitect[ 'defaults' ][ 'blueprints' ] as $key1 => $value1) {
      foreach ($value1 as $key2 => $value2) {
        foreach ($value2 as $key3 => $fields) {
          if (is_array($fields)) {
            foreach ($fields as $key4 => $field) {
              if (isset($field[ 'id' ])) {
                $pzarchitect[ 'defaults' ][ '_blueprints' ][ $field[ 'id' ] ] = (empty($field[ 'default' ]) ? '' : $field[ 'default' ]);
              }
            }
          }
        }
      }
    }

    unset($pzarchitect[ 'defaults' ][ 'blueprints' ]);

    foreach ($pzarchitect[ 'defaults' ][ 'panels' ] as $key1 => $value1) {
      foreach ($value1 as $key2 => $value2) {
        foreach ($value2 as $key3 => $fields) {
          if (is_array($fields)) {
            foreach ($fields as $key4 => $field) {
              if (isset($field[ 'id' ])) {
                $pzarchitect[ 'defaults' ][ '_panels' ][ $field[ 'id' ] ] = (empty($field[ 'default' ]) ? '' : $field[ 'default' ]);
              }
            }
          }
        }
      }
    }
    unset($pzarchitect[ 'defaults' ][ 'panels' ]);

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

  function pzarc_get_authors($inc_all = true, $min_level = 2)
  {
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

  function pzarc_get_blueprints()
  {
    $args           = array(
        'posts_per_page'   => -1,
        'orderby'          => 'post_title',
        'order'            => 'ASC',
        'post_type'        => 'arc-blueprints',
        'post_status'      => 'publish',
        'suppress_filters' => true);
    $blueprints_obj = get_posts($args);
    $blueprint_list = array();
    foreach ($blueprints_obj as $blueprint_obj) {
      $blueprint_list[ $blueprint_obj->post_name ] = $blueprint_obj->post_title;
    }

    return $blueprint_list;
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
    $pzep_cf_list = $wpdb->get_results(
        "SELECT DISTINCT meta_key FROM $wpdb->postmeta HAVING (meta_key NOT LIKE '\_%' AND meta_key NOT LIKE 'pz%' AND meta_key NOT LIKE 'field_%') ORDER BY meta_key"
    );
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

  function pzarc_get_styling($source, $keys)
  {

    if ('blueprint' === $source) {
      switch ($keys[ 'id' ]) {
        case 'blueprint':
          $keys[ 'class' ] = '.pzarc-blueprint';
          break;
        case  'blueprint-custom':
          $keys[ 'class' ] = '.';
          break;
        case  'sections':
          $keys[ 'class' ] = '.pzarc-section';
          break;
        case  'pzarc-section_1':
          $keys[ 'class' ] = '.pzarc-section_1';
          break;
        case  'pzarc-section_2':
          $keys[ 'class' ] = '.pzarc-section_2';
          break;
        case  'pzarc-section_3':
          $keys[ 'class' ] = '.pzarc-section_3';
          break;
        case  'pzarc-navigator':
          $keys[ 'class' ] = '.pzarc-navigator';
          break;
        case  'pzarc-navigator-items':
          $keys[ 'class' ] = '.arc-slider-slide-nav-item'; // not always!! ugh!
          break;

      }
    }

    // generate correct whosit
    $pzf = 'pzarc_style_' . $keys[ 'style' ];
    $def = call_user_func($pzf, $keys[ 'class' ]);

    var_dump($def);

    return $def;
  }

  function pzarc_style_background($class)
  {
    return $class;
  }

  function pzarc_style_padding($class)
  {
    $padding = pzarc_process_spacing($value);
    return $class;
  }

  function pzarc_style_margins($class)
  {
    return $class;
  }

  function pzarc_style_borders($class)
  {
    return $class;
  }

  function pzarc_style_links($class)
  {
    return $class;
  }

  function pzarc_style_font($class)
  {
    return $class;
  }

  function pzarc_style_css($class)
  {
    return $class;
  }
