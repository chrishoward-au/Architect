<?php
  /**
   * Created by JetBrains PhpStorm.
   * User: chrishoward
   * Date: 13/08/13
   * Time: 8:32 PM
   */


  // How do we do this only on pages needing it?
  add_action('init', 'pzarc_display_init');
  /**
   *
   * pzarc_display_init
   *
   */
  function pzarc_display_init()
  {
    if (is_admin()) {
      return;
    }
    $pzarc_css_cache = maybe_unserialize(get_option('pzarc_css'));
    // No point in proceeding if no blueprints or no panels
    if (empty($pzarc_css_cache[ 'blueprints' ])) {
      return;
    }

    require_once(PZARC_PLUGIN_APP_PATH.'public/php/class_arcBuilder.php');
    new arcBuilder;

    wp_register_style('css-hw-float-fix', PZARC_PLUGIN_APP_URL . '/public/css/arc-hw-fix.css');

    // TODO: These seem to be loading late so loading in footer - even the CSS!
    // Retina Js
    // Using hacked version which only supports data-at2x attribute
    wp_register_script('js-retinajs', PZARC_PLUGIN_APP_URL . '/public/js/retinajs/retina.js');


    // Slick
    wp_register_script('js-arc-front-slickjs', PZARC_PLUGIN_APP_URL . '/public/js/min/arc-front-slick-min.js', array('jquery'), null, true);
    wp_register_script('js-slickjs', PZARC_PLUGIN_APP_URL . '/public/js/slick/slick/slick.min.js', array('jquery'), null, true);
    wp_register_style('css-slickjs', PZARC_PLUGIN_APP_URL . '/public/js/slick/slick/slick.css');

    // Magnific
    wp_register_script('js-magnific-arc', PZARC_PLUGIN_APP_URL . '/public/js/arc-front-magnific.js', array('jquery'), null, true);
    wp_register_script('js-magnific', PZARC_PLUGIN_APP_URL . '/public/js/Magnific-Popup/jquery.magnific-popup.min.js', array('jquery'), null, true);
    wp_register_style('css-magnific', PZARC_PLUGIN_APP_URL . '/public/js/Magnific-Popup/magnific-popup.css');

    //icomoon
    wp_register_style('css-icomoon-arrows', PZARC_PLUGIN_APP_URL . '/shared/assets/fonts/icomoon/im-style.css');

    //AnimateCSS
    wp_register_style('css-animate', PZARC_PLUGIN_APP_URL . '/public/css/animate.min.css');

    // DataTables
    wp_register_script('js-datatables', PZARC_PLUGIN_APP_URL . '/public/js/DataTables/media/js/jquery.dataTables.min.js', array('jquery'), null, true);
    wp_register_style('css-datatables', PZARC_PLUGIN_APP_URL . '/public/js/DataTables/media/css/jquery.dataTables.min.css');

    // jQuery Collapse
    wp_register_script('js-jquery-collapse', PZARC_PLUGIN_APP_URL . '/public/js/jQuery-Collapse/src/jquery.collapse.js', array('jquery'), null, true);

    $actions_options = get_option('_architect_actions');
    $actions         = array();
    $i               = 1;
    foreach ($actions_options as $k => $v) {
      if ($k != 'last_tab') {
        $actions[ $i ][ $k ] = $v;
      }
      if ($k == 'architect_actions_' . $i . '_pageids') {
        $i++;
      }
    }
    require_once PZARC_PLUGIN_APP_PATH . '/public/php/class_showblueprint.php';
    foreach ($actions as $k => $v) {
      if (isset($v[ 'architect_actions_' . $k . '_action-name' ]) && isset($v[ 'architect_actions_' . $k . '_blueprint' ])) {
        new showBlueprint($v[ 'architect_actions_' . $k . '_action-name' ], $v[ 'architect_actions_' . $k . '_blueprint' ], 'home');
      }
    }

    // Override WP Gallery if necessary
    global $_architect_options;

    // Just incase that didn't work... A problem from days of past
    if (!isset($GLOBALS[ '_architect_options' ])) {
      $GLOBALS[ '_architect_options' ] = get_option('_architect_options', array());
    }
    if (!empty($_architect_options[ 'architect_replace_wpgalleries' ])) {
      remove_shortcode('gallery');
      add_shortcode('gallery', 'pzarc_shortcode');

    }
  }

  /***********************
   *
   * Shortcode
   *
   ***********************/
  function pzarc_shortcode($atts, $content = null, $tag)
  {
    $pzarc_caller    = 'shortcode';
    $pzarc_blueprint = '';
    $pzarc_overrides = null;

    if (!empty($atts[ 'blueprint' ])) {

      $pzarc_blueprint = $atts[ 'blueprint' ];
      $pzarc_overrides = $atts;
      array_shift($pzarc_overrides);

    } elseif (!empty($atts[ 0 ])) {
      $pzarc_blueprint = $atts[ 0 ];
      $pzarc_overrides = $atts;
      array_shift($pzarc_overrides);
    }


    // Need to capture the output so we can get it to appear where the shortcode actually is
    ob_start();


    do_action("arc_before_shortcode", $pzarc_blueprint, $pzarc_overrides, $pzarc_caller, $tag);


    // The caller is shortcode, and not variable here. It just uses a variable for consistency and documentation
    do_action("arc_do_shortcode", $pzarc_blueprint, $pzarc_overrides, $pzarc_caller, $tag);

    do_action("arc_after_shortcode", $pzarc_blueprint, $pzarc_overrides, $pzarc_caller, $tag);

    $pzout = ob_get_contents();
    ob_end_clean();

    // Putting thru a filter so devs can do stuff with it
    return apply_filters('arc_filter_shortcode', $pzout, $pzarc_blueprint, $pzarc_overrides, $tag);

  }

  add_shortcode('architect', 'pzarc_shortcode');
  add_shortcode('pzarc', 'pzarc_shortcode'); // Old version
  add_shortcode('pzarchitect', 'pzarc_shortcode'); // alternate version
  // I still don't understand why this works!! One day, maybe I will
  add_action('arc_do_shortcode', 'pzarc', 10, 4);

  /***********************
   *
   * Template tag
   *
   ***********************/
  function pzarchitect($pzarc_blueprint = null, $pzarc_overrides = null)
  {
    $pzarc_caller = 'template_tag';
    do_action("arc_before_template_tag", $pzarc_blueprint, $pzarc_overrides, $pzarc_caller);
    do_action("arc_do_template_tag", $pzarc_blueprint, $pzarc_overrides, $pzarc_caller);
    do_action("arc_after_template_tag", $pzarc_blueprint, $pzarc_overrides, $pzarc_caller);
  }

  add_action('arc_do_template_tag', 'pzarc', 10, 3);

  /***********************
   *
   * Page builder
   *
   ***********************/
  function pzarc_pagebuilder($pzarc_blueprint = null, $pzarc_overrides = null)
  {
    $pzarc_caller = 'pagebuilder';
//    do_action("arc_before_pagebuilder", $pzarc_blueprint, $pzarc_overrides, $pzarc_caller);
    do_action("arc_do_pagebuilder", $pzarc_blueprint, $pzarc_overrides, $pzarc_caller);
//    do_action("arc_after_pagebuilder", $pzarc_blueprint, $pzarc_overrides, $pzarc_caller);
  }

  add_action('arc_do_pagebuilder', 'pzarc', 10, 3);

  /***********************
   *
   * Blueprint main display function
   * Overrides is a list of ids
   *
   ******************************/
  function pzarc($blueprint = null, $overrides = null, $caller, $tag = null, $additional_overrides = null)
  {
    pzdb('start pzarc');
    $filename      = PZARC_CACHE_URL . '/pzarc_blueprint_' . $blueprint . '.css';
    $filename_path = PZARC_CACHE_PATH . '/pzarc_blueprint_' . $blueprint . '.css';
    wp_enqueue_style('pzarc_css_blueprint_' . $blueprint, $filename, false, filemtime($filename_path));


    global $_architect_options;
    global $in_arc;
    $in_arc = 'yes';
    // Just incase that didn't work... A problem from days of past
    if (!isset($GLOBALS[ '_architect_options' ])) {
      $GLOBALS[ '_architect_options' ] = get_option('_architect_options', array());
    }

    wp_enqueue_script('js-magnific');
    wp_enqueue_script('js-magnific-arc');
    wp_enqueue_style('css-magnific');


    wp_enqueue_style(PZARC_NAME . '-plugin-styles');
    wp_enqueue_style(PZARC_NAME . '-dynamic-styles');
//    wp_enqueue_style('pzarc_css');


    $is_shortcode = ($caller == 'shortcode');


    if (empty($blueprint) && ($is_shortcode && (empty($_architect_options[ 'architect_default_shortcode_blueprint' ])) && empty($_architect_options[ 'architect_replace_wpgalleries' ]))) {

      // TODO: Should we make this use a set of defaults. prob an excerpt grid
      echo '<p class="message-warning">You need to set a blueprint</p>';

    } else {

      if (empty($blueprint) && $is_shortcode) {

        if ($tag === 'gallery') {
          $blueprint = ($_architect_options[ 'architect_replace_wpgalleries' ] ? $_architect_options[ 'architect_replace_wpgalleries' ] : $_architect_options[ 'architect_default_shortcode_blueprint' ]);
        } else {
          $blueprint = $_architect_options[ 'architect_default_shortcode_blueprint' ];
        }
      }

      require_once PZARC_PLUGIN_APP_PATH . '/public/php/class_architect_public.php';
      require_once(PZARC_PLUGIN_APP_PATH . '/shared/thirdparty/php/BFI-thumb-forked/BFI_Thumb.php');

      $architect = new ArchitectPublic($blueprint, $is_shortcode);
//var_dump($architect);
      // If no errors, let's go!
      if (empty($architect->build->blueprint[ 'err_msg' ])) {

        /** This is it! **/
        $architect->build_blueprint($overrides, $caller, $additional_overrides);

        // might need this... don't know
        if (is_main_query() || in_the_loop() || $caller === 'shortcode') {
        }
      }

      // Cleanup
      // If Blueprint is none, shortname is not set
      if (isset($architect->build->blueprint[ '_blueprints_short-name' ])) {
        remove_all_actions('arc_top_left_navigation_' . $architect->build->blueprint[ '_blueprints_short-name' ]);
        remove_all_actions('arc_bottom_right_navigation_' . $architect->build->blueprint[ '_blueprints_short-name' ]);
      }

      unset ($architect);
    }

    // Tell WP to resume using the main query just in case we might have accidentally left another query active. (0.9.0.2 This might be our saviour!)
    wp_reset_postdata();
    pzdb('end pzarc');

    $in_arc = 'no';
  }


  // TODO: Shouldn't need this if we're going to do outputting
  /********************************************
   *
   * Capture and append the comments display
   *
   ********************************************/
  //add_filter('pzarc_comments', 'pzarc_get_comments');
  function pzarc_get_comments($pzarc_content)
  {
    ob_start();
    comments_template(null, null);
    $pzarc_comments = ob_get_contents();
    ob_end_flush();

    return $pzarc_content . $pzarc_comments;
  }

  /********************************************
   *
   * Add body class by filter
   *
   ********************************************/
  add_filter('body_class', 'add_pzarc_class');
  function add_pzarc_class($classes)
  {
    // add 'class-name' to the $classes array
    if (!in_array('pzarchitect', $classes)) {
      $classes[ ] = 'pzarchitect';
    }

    $classes[] = 'theme-'.get_stylesheet();
    // return the $classes array
    return $classes;
  }



  /**
   * Display Page Builder after the post
   */
  //  function pzarc_add_pagebuilder_after($query_object)
  //  {
  //    if (!is_admin()) {
  //
  //      $page_build = get_post_meta(get_the_id(), '_pzarc_pagebuilder', true);
  //      if (isset($page_build[ 'enabled' ])) {
  //        $show_content = array_key_exists('original', $page_build[ 'enabled' ]);
  //        if ($show_content) {
  //          $past_content = false;
  //          foreach ($page_build[ 'enabled' ] as $bpsn => $v) {
  //            if ($past_content) {
  //              pzarc_pagebuilder($bpsn);
  //            }
  //            $past_content = ($bpsn === 'original');
  //          }
  //        }
  //      }
  //    }
  //  }
  //
  //  /**
  //   * Display Page Builder before the post
  //   */
  //  function xpzarc_add_pagebuilder_before($query_object)
  //  {
  //    if (!is_admin()) {
  //      // We only want this to run once. There's probably a more correct way.
  //      static $before_state = false;
  //      // This is coz .hentry is floated which breaks page builder
  //      if (!$before_state) {
  //        global $original_post;
  //        $original_post = get_the_id();
  //      }
  //      wp_enqueue_style('css-hw-float-fix');
  //      global $in_arc, $post;
  //      var_Dump($in_arc);
  //      if ($in_arc === 'no' || !$in_arc) {
  //        if (is_singular() && !$before_state) {
  //          $page_build = get_post_meta(get_the_id(), '_pzarc_pagebuilder', true);
  //          var_dump($page_build);
  //          if (isset($page_build[ 'enabled' ])) {
  //            $show_content = array_key_exists('original', $page_build[ 'enabled' ]);
  //            foreach ($page_build[ 'enabled' ] as $bpsn => $v) {
  //              if ($bpsn !== 'placebo' && $bpsn !== 'original') {
  //                pzarc_pagebuilder($bpsn);
  //              }
  //              if ($bpsn === 'original') {
  //                break;
  //              }
  //            }
  //            if (!$show_content) {
  //              echo '<span class="hide-content"></span>';
  //            }
  //          }
  //        }
  //        $before_state = true;
  //        remove_action('pzarc_template_before_content', 'pzarc_add_pagebuilder_before');
  //      }
  //    }
  //  }
  //
  //  /**
  //   * Display Page Builder after the post
  //   */
  //  function xpzarc_add_pagebuilder_after($query_object)
  //  {
  //
  //    if (!is_admin()) {
  //      global $in_arc;
  //      if ($in_arc === 'no') {
  //        global $original_post;
  //        if (get_the_id() === $original_post) {
  //          if (is_singular()) {
  //            $page_build = get_post_meta(get_the_id(), '_pzarc_pagebuilder', true);
  //            if (isset($page_build[ 'enabled' ])) {
  //              $skip = array_key_exists('original', $page_build[ 'enabled' ]);
  //              // If not skip, then we would have already done it
  //              if ($skip) {
  //                foreach ($page_build[ 'enabled' ] as $bpsn => $v) {
  //                  // Skip until after the Original
  //                  if (!$skip && $bpsn !== 'placebo' && $bpsn !== 'original') {
  //                    pzarc_pagebuilder($bpsn);
  //                  } elseif ($bpsn === 'original') {
  //                    $skip = false;
  //                  }
  //                }
  //              }
  //            }
  //          }
  //        }
  //        $original_post = null;
  //        remove_action('pzarc_template_after_content', 'pzarc_add_pagebuilder_after');
  //      }
  //    }
  //  }
