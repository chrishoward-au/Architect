<?php
  /**
   * Created by JetBrains PhpStorm.
   * User: chrishoward
   * Date: 13/08/13
   * Time: 8:32 PM
   * To change this blueprint use File | Settings | File Blueprints.
   */


  // TODO: Move these til needed


  add_action('init', 'pzarc_display_init');
  function pzarc_display_init()
  {
    wp_register_script('js-arc-frontjs', PZARC_PLUGIN_APP_URL . '/public/js/arc-front.js', array('jquery'));
    wp_register_script('js-swiperjs', PZARC_PLUGIN_APP_URL . '/shared/libraries/js/swiper/idangerous.swiper.min.js');
    wp_register_script('js-swiper-progressjs', PZARC_PLUGIN_APP_URL . '/shared/libraries/js/swiper/idangerous.swiper.progress.min.js');
    wp_register_style('css-swiperjs', PZARC_PLUGIN_APP_URL . '/shared/libraries/js/swiper/idangerous.swiper.css');

    wp_enqueue_script('js-arc-frontjs');
    wp_enqueue_script('js-swiperjs');
    wp_enqueue_style('css-swiperjs');

    if (!(class_exists('ReduxFramework') || class_exists('ReduxFrameworkPlugin'))) {
      return;
    }
    $actions_options = get_option('_architect_actions');
    $actions = array();
    $i = 1;
    foreach ($actions_options as $k => $v) {
      if ($k != 'last_tab'){
        $actions[$i][$k]=$v;
      }
      if ($k == 'architect_actions_'.$i.'_pageids'){
        $i++;
      }
    }
    foreach ($actions as $k => $v) {
      new showBlueprint($v['architect_actions_'.$k.'_action-name'],$v['architect_actions_'.$k.'_blueprint'],'home');
    }
    //   require_once PZARC_PLUGIN_PATH . '/admin/php/arc-options.php';
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

    do_action("arc_before_{$pzarc_caller}", $pzarc_blueprint, $pzarc_overrides, $pzarc_caller);

    // The caller is shortcode, and not variable here. It just uses a variable for consistency and documentation
    do_action("arc_do_{$pzarc_caller}", $pzarc_blueprint, $pzarc_overrides, $pzarc_caller);

    do_action("arc_after_{$pzarc_caller}", $pzarc_blueprint, $pzarc_overrides, $pzarc_caller);

    $pzout = ob_get_contents();
    ob_end_clean();

//    $pzout = '<div class="pzarc-shortcode pzarc-shortcode-' . $pzarc_blueprint . '">' . $pzout . '</div>';

    // Putting thru a filter so devs can do stuff with it
    return apply_filters('arc_filter_shortcode', $pzout, $pzarc_blueprint, $pzarc_overrides);
  }

  add_shortcode('pzarc', 'pzarc_shortcode');
  // I still don't understand why this works!! One day, maybe I will
  add_action('arc_do_shortcode', 'pzarc', 10, 3);

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
  function pzarc($blueprint = null, $overrides = null, $caller)
  {
    global $wp_query;
    $original_query = $wp_query;
    $is_shortcode   = ($caller == 'shortcode');
    if ($is_shortcode) {
      // This was just in testing!!
      // remove_shortcode('pzarc');
    }
    if (empty($blueprint)) {
      // TODO: Should we make this use a set of defaults. prob an excerpt grid
      echo '<p class="warning-msg">You need to set a blueprint</p>';
    } else {
      require_once PZARC_PLUGIN_APP_PATH . '/public/php/class_Architect.php';
      require_once(PZARC_PLUGIN_APP_PATH . '/shared/libraries/php/jo-image-resizer/jo_image_resizer.php');
      require_once(PZARC_PLUGIN_APP_PATH . '/shared/libraries/php/BFI_Thumb.php');

      $architect = new Architect($blueprint, $is_shortcode);
      if (empty($architect->build->blueprint[ 'err_msg' ])) {

        $architect->build($overrides, $caller);

        wp_reset_postdata(); // Pretty sure this goes here... Not after the query reassignment

        //restore original query
        $wp_query = $original_query;

        /* These lines from ExcerptsPlus */
        // removed after null prob fixed. may need to be reinstated one day
        // Reinstated after conflict with breadcrumbs and related posts plugin
        //Added 11/8/13 so can display multiple blocks on single post page with single post's content
        // TODO: rewind_posts causes recursion if in main loop so need to determine when it is needed. Be aware it still might cause problems used here
        if (!is_main_query()) {
          // i.e. when is this necessary???????
          ////       rewind_posts();
        } else {
          // Trying to break out of the main loop!
          if ($wp_query->current_post == -1) {
            //WTF? Shouldn't pointer have moved?
//            $wp_query->next_post();
            // Doing this nudges things, believe it or not!
            // Solves a few problems: 1) Recursion (but only if in this if), 2) template tag not resetting main loop
            $wp_query->have_posts();

            // TODO: Be interesting to see what other havoc this is going to cause!
            // eg when multiple posts have shortcodes and are showing full content
          }

        }

      }

//      new pzarc_Display($pzarc_blueprint, $pzarc_blueprint_object, $pzarc_overrides, $pzarc_is_shortcode);
      unset ($architect);
//    return $pzarc->output;
    }
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
