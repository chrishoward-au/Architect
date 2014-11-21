<?php
  /**
   * Created by JetBrains PhpStorm.
   * User: chrishoward
   * Date: 13/08/13
   * Time: 8:32 PM
   */




  // How do we do this only on pages needing it?
  add_action('init', 'pzarc_display_init');
  function pzarc_display_init()
  {
    if (!is_admin() && file_exists(PZARC_CACHE_PATH . 'pzarc_css_cache.css')) {

      wp_register_style('pzarc_css', PZARC_CACHE_URL . 'pzarc_css_cache.css');

    } else {

      echo '<p class="message-warning">Oops! Could not find Architect CSS cache file. Please go to WP Admin Architect > Tools and rebuild it.</p>';

    }

    // TODO: These seem to be loading late so loading in footer - even the CSS!
    // Retina Js
    // Using hacked version which only supports data-at2x attribute
    wp_register_script('js-retinajs', PZARC_PLUGIN_APP_URL . '/public/js/retinajs/retina.js');


    // Slick
    wp_register_script('js-arc-front-slickjs', PZARC_PLUGIN_APP_URL . '/public/js/min/arc-front-slick-min.js', array('jquery'),null,true);
    wp_register_script('js-slickjs', PZARC_PLUGIN_APP_URL . '/public/js/slick/slick/slick.min.js', array('jquery'), null, true);
    wp_register_style('css-slickjs', PZARC_PLUGIN_APP_URL . '/public/js/slick/slick/slick.css');

    // Magnific
    wp_register_script('js-magnific-arc', PZARC_PLUGIN_APP_URL . '/public/js/arc-front-magnific.js', array('jquery'), null, true);
    wp_register_script('js-magnific', PZARC_PLUGIN_APP_URL . '/public/js/Magnific-Popup/jquery.magnific-popup.min.js', array('jquery'), null, true);
    wp_register_style('css-magnific', PZARC_PLUGIN_APP_URL . '/public/js/Magnific-Popup/magnific-popup.css');

    //icomoon
    wp_register_style('css-icomoon-arrows', PZARC_PLUGIN_APP_URL . '/shared/assets/fonts/icomoon/im-style.css');


    // ResponCSS
//    wp_register_script('js-responcss', PZARC_PLUGIN_APP_URL . '/shared/includes/css/ResponCSS/js/responcss.js');
//    wp_register_style('css-responcss', PZARC_PLUGIN_APP_URL . '/shared/includes/css/ResponCSS/css/responcss.css');
//
//    wp_enqueue_script('js-responcss');
//    wp_enqueue_style('css-responcss');


    if (!(class_exists('ReduxFramework') || class_exists('ReduxFrameworkPlugin'))) {
      return;
    }
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
    foreach ($actions as $k => $v) {
      if (isset($v[ 'architect_actions_' . $k . '_action-name' ]) && isset($v[ 'architect_actions_' . $k . '_blueprint' ])) {
        new showBlueprint($v[ 'architect_actions_' . $k . '_action-name' ], $v[ 'architect_actions_' . $k . '_blueprint' ], 'home');
      }
    }
    //   require_once PZARC_PLUGIN_PATH . '/admin/php/arc-options.php';

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

    if (!empty($atts[ 'blueprint' ])) {

      $pzarc_blueprint = $atts[ 'blueprint' ];

    } elseif (!empty($atts[ 0 ])) {

      $pzarc_blueprint = $atts[ 0 ];

    }

    // Need to capture the output so we can get it to appear where the shortcode actually is
    ob_start();

    $pzarc_overrides = !empty($atts[ 'ids' ]) ? $atts[ 'ids' ] : null;
    // UGH! This is a bit of a mess. Need a better solution
    // echo '<div class="clearfix"></div>'; // Just need to stop some overlapping when images are bigger than content in post

    do_action("arc_before_{$pzarc_caller}", $pzarc_blueprint, $pzarc_overrides, $pzarc_caller, $tag);

//    var_dump($pzarc_blueprint, $pzarc_overrides, $pzarc_caller);

    // The caller is shortcode, and not variable here. It just uses a variable for consistency and documentation
    do_action("arc_do_{$pzarc_caller}", $pzarc_blueprint, $pzarc_overrides, $pzarc_caller, $tag);

    do_action("arc_after_{$pzarc_caller}", $pzarc_blueprint, $pzarc_overrides, $pzarc_caller, $tag);

    $pzout = ob_get_contents();
    ob_end_clean();

//    $pzout = '<div class="pzarc-shortcode pzarc-shortcode-' . $pzarc_blueprint . '">' . $pzout . '</div>';

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
    do_action("arc_before_{$pzarc_caller}", $pzarc_blueprint, $pzarc_overrides, $pzarc_caller);
    do_action("arc_do_{$pzarc_caller}", $pzarc_blueprint, $pzarc_overrides, $pzarc_caller);
    do_action("arc_after_{$pzarc_caller}", $pzarc_blueprint, $pzarc_overrides, $pzarc_caller);
  }

  add_action('arc_do_template_tag', 'pzarc', 10, 3);

  /***********************
   *
   * Blueprint main display function
   * Overrides is a list of ids
   *
   ******************************/
  function pzarc($blueprint = null, $overrides = null, $caller, $tag = null, $additional_overrides = null)
  {

    global $_architect_options;

    // Just incase that didn't work... A problem from days of past
    if (!isset($GLOBALS[ '_architect_options' ])) {
      $GLOBALS[ '_architect_options' ] = get_option('_architect_options', array());
    }

    // Enqueue registered scripts and styles
    // make optional
    wp_enqueue_script('js-arc-front-slickjs');
    wp_enqueue_script('js-slickjs');
    wp_enqueue_style('css-slickjs');
    wp_enqueue_style('css-icomoon-arrows');
    // make optional
    if (!empty($_architect_options['architect_enable-retina-images'])) {
      wp_enqueue_script('js-retinajs');
    }
    wp_enqueue_script('js-magnific');
    wp_enqueue_script('js-magnific-arc');
    wp_enqueue_style('css-magnific');

    wp_enqueue_style(PZARC_NAME . '-plugin-styles');
    wp_enqueue_style(PZARC_NAME . '-dynamic-styles');
    wp_enqueue_style('pzarc_css');


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
      // Ok - how do we id the shortcode?

//      require_once PZARC_PLUGIN_APP_PATH . '/admin/php/arc-options-styling.php';
//      require_once PZARC_PLUGIN_APP_PATH . '/shared/includes/php/redux-extensions/extensions/metaboxes/extension_metaboxes.php';

      require_once PZARC_PLUGIN_APP_PATH . '/public/php/class_ArchitectPublic.php';
//      require_once(PZARC_PLUGIN_APP_PATH . '/shared/includes/php/jo-image-resizer/jo_image_resizer.php');
      require_once(PZARC_PLUGIN_APP_PATH . '/shared/includes/php/BFI-thumb-forked/BFI_Thumb.php');


      $architect = new ArchitectPublic($blueprint, $is_shortcode);
      if (empty($architect->build->blueprint[ 'err_msg' ])) {


        $architect->build_blueprint($overrides, $caller, $additional_overrides);


        /* These lines from ExcerptsPlus */
        // removed after null prob fixed. may need to be reinstated one day
        // Reinstated after conflict with breadcrumbs and related posts plugin
        //Added 11/8/13 so can display multiple blocks on single post page with single post's content

        // TODO: We mighthave to check these!!
        //  public 'is_single' => boolean false
        //  public 'is_preview' => boolean false
        //  public 'is_page' => boolean true
        //  public 'is_archive' => boolean false
        //  public 'is_date' => boolean false
        //  public 'is_year' => boolean false
        //  public 'is_month' => boolean false
        //  public 'is_day' => boolean false
        //  public 'is_time' => boolean false
        //  public 'is_author' => boolean false
        //  public 'is_category' => boolean false
        //  public 'is_tag' => boolean false
        //  public 'is_tax' => boolean false
        //  public 'is_search' => boolean false
        //  public 'is_feed' => boolean false
        //  public 'is_comment_feed' => boolean false
        //  public 'is_trackback' => boolean false
        //  public 'is_home' => boolean false
        //  public 'is_404' => boolean false
        //  public 'is_comments_popup' => boolean false
        //  public 'is_paged' => boolean false
        //  public 'is_admin' => boolean false
        //  public 'is_attachment' => boolean false
        //  public 'is_singular' => boolean true
        //  public 'is_robots' => boolean false
        //  public 'is_posts_page' => boolean false
        //  public 'is_post_type_archive' => boolean false

        // might need this... don't know
        if (is_main_query() || in_the_loop() || $caller === 'shortcode') {
        }
        unset ($architect);
      }
    }

    // Tell WP to resume using the main query just in case we might have accidentally left another query active.
    wp_reset_postdata();
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
//  pzdebug(get_the_id());
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

    // return the $classes array
    return $classes;
  }
