<?php
  /**
   * Created by JetBrains PhpStorm.
   * User: chrishoward
   * Date: 13/08/13
   * Time: 8:32 PM
   * To change this blueprint use File | Settings | File Blueprints.
   */


  // TODO: Move these til needed
  require_once PZARC_PLUGIN_PATH . '/public/php/class_Architect.php';
  require_once(PZARC_PLUGIN_PATH . '/shared/libs/php/jo-image-resizer/jo_image_resizer.php');


  add_action('init', 'pzarc_display_init');
  function pzarc_display_init()
  {
    wp_register_script('js-arc-frontjs', PZARC_PLUGIN_URL . '/public/js/arc-front.js', array('jquery'));
    wp_register_script('js-swiperjs', PZARC_PLUGIN_URL . '/shared/libs/js/swiper/idangerous.swiper.js');
    wp_register_style('css-swiperjs', PZARC_PLUGIN_URL . '/shared/libs/js/swiper/idangerous.swiper.css');

    wp_enqueue_script('js-arc-frontjs');
    wp_enqueue_script('js-swiperjs');
    wp_enqueue_style('css-swiperjs');

    if (!(class_exists('ReduxFramework') || class_exists('ReduxFrameworkPlugin')))
    {
      return;
    }
    require_once PZARC_PLUGIN_PATH . '/admin/php/arc-options.php';
  }


  /***********************
   *
   * Shortcode
   *
   ***********************/
  add_shortcode('pzarc', 'pzarc_shortcode');
  function pzarc_shortcode($atts, $content = null, $tag)
  {

    $pzarc_blueprint = '';
    if (!empty($atts[ 'blueprint' ]))
    {
      $pzarc_blueprint = $atts[ 'blueprint' ];
    }
    elseif (!empty($atts[ 0 ]))
    {
      $pzarc_blueprint = $atts[ 0 ];
    }

    return 'This isn\'t quite right yet. It goes into a recursive spin';

//    return pzarc($pzarc_blueprint, (!empty($atts[ 'ids' ]) ? $atts[ 'ids' ] : null), true);


  }

  /***********************
   *
   * Template tag
   *
   ***********************/
  function pzarchitect($pzarc_blueprint = null, $pzarc_overrides = null)
  {
    pzarc($pzarc_blueprint, $pzarc_overrides, false);
  }

  /***********************
   *
   * Blueprint main display function
   * Overrides is a list of ids
   *
   ******************************/
  function pzarc($blueprint = null, $overrides = null, $is_shortcode = false)
  {
    if (empty($blueprint))
    {
      // make this use a set of defaults. prob an excerpt grid
      echo 'You need to set a blueprint';

    }
    else
    {

      $architect = new Architect($blueprint, $is_shortcode);
      if (empty($architect->build->blueprint[ 'err_msg' ]))
      {
        global $wp_query;
        $original_query = $wp_query;

        $architect->build($overrides);

        //restore original query
        $wp_query = $original_query;

        /* These lines from ExcerptsPlus */
        // removed after null prob fixed. may need to be reinstated one day
        // Reinstated after conflict with breadcrumbs and related posts plugin
        wp_reset_postdata();
        //Added 11/8/13 so can display multiple blocks on single post page with single post's content
        rewind_posts();

      }

//      new pzarc_Display($pzarc_blueprint, $pzarc_blueprint_object, $pzarc_overrides, $pzarc_is_shortcode);

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
    if (!in_array('pzarchitect', $classes))
    {
      $classes[ ] = 'pzarchitect';
    }

    // return the $classes array
    return $classes;
  }
