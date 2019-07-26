<?php
  /**
   * Created by JetBrains PhpStorm.
   * User: chrishoward
   * Date: 13/08/13
   * Time: 8:32 PM
   */

  // Front end includes, Register site styles and scripts

  add_action( 'wp_enqueue_scripts', array( $this, 'register_plugin_styles' ), ( time() % 10000 ) ); // v.1.11.1 Forcing late loading of Blueprint CSS
  add_action( 'wp_enqueue_scripts', array( $this, 'register_plugin_scripts' ) );
  add_action( 'wp_print_footer_scripts', 'pzarc_footer_css' );

  add_action( 'wp_head', 'pzarc_display_init' );

  add_shortcode( 'architect', 'pzarc_shortcode' );
  add_shortcode( 'architectsc', 'pzarc_shortcode' ); // Shortcake version coz shortcake doesn't support positional attributes
  add_shortcode( 'pzarc', 'pzarc_shortcode' ); // Old version
  add_shortcode( 'pzarchitect', 'pzarc_shortcode' ); // alternate version
  // I still don't understand why this works!! One day, maybe I will
  add_action( 'arc_do_shortcode', 'pzarc', 10, 7 );
  add_filter( 'body_class', 'add_pzarc_class' );

  add_action( 'arc_do_pagebuilder', 'pzarc', 10, 7 );
  add_action( 'arc_do_template_tag', 'pzarc', 10, 7 );

  add_theme_support( 'infinite-scroll', array(
      'type'           => 'scroll',
      'footer_widgets' => FALSE,
      'container'      => 'pzarc-sections_blog-grid',
      'wrapper'        => FALSE,
      'render'         => 'pzarc',
      'posts_per_page' => FALSE,
  ) );

  // How do we do this only on pages needing it?
  /**
   *
   * pzarc_display_init
   *
   */
  function pzarc_display_init() {
    if ( is_admin() ) {
      return;
    }
    $pzarc_css_cache = maybe_unserialize( get_option( 'pzarc_css' ) );
    // No point in proceeding if no blueprints or no panels
    if ( empty( $pzarc_css_cache['blueprints'] ) ) {
      return;
    }
    // Register all the scripts in case it solves the late loading!
    // It didn't
//    foreach ( $pzarc_css_cache[ 'blueprints' ] as $blueprint => $v ) {
//      $filename      = PZARC_CACHE_URL . '/pzarc_blueprint_' . $blueprint . '.css';
//      $filename_path = PZARC_CACHE_PATH . '/pzarc_blueprint_' . $blueprint . '.css';
//      // Keep the timestamp or caching get messed up.
//      wp_register_style('pzarc_css_blueprint_' . $blueprint, $filename, false, filemtime($filename_path));
//    }


    wp_register_style( 'css-hw-float-fix', PZARC_PLUGIN_APP_URL . '/public/css/arc-hw-fix.css', FALSE, PZARC_VERSION );

    // TODO: These seem to be loading late so loading in footer - even the CSS!
    // Retina Js
    // Using hacked version which only supports data-at2x attribute
    wp_register_script( 'js-retinajs', PZARC_PLUGIN_APP_URL . '/public/js/retinajs/retina.js' );

    // Disable saving images
    wp_register_script( 'js-disableimagesaving', PZARC_PLUGIN_APP_URL . '/public/js/arc-disable-image-saving.js' );


    // Waypoints
    // Infinite scroll requires a method to load next set, so would would best leveraging off pagination -maybe... And that is a lot harder!
    // Waypoints provides infinite scroll support.
//    wp_register_script('js-waypoints', PZARC_PLUGIN_APP_URL . '/public/js/waypoints/jquery.waypoints.min.js');

    // Magnific
    wp_register_script( 'js-magnific-arc', PZARC_PLUGIN_APP_URL . '/public/js/arc-front-magnific.js', array( 'jquery' ), PZARC_VERSION, TRUE );
    wp_register_script( 'js-magnific', PZARC_PLUGIN_APP_URL . '/public/js/Magnific-Popup/jquery.magnific-popup.min.js', array( 'jquery' ), PZARC_VERSION, TRUE );
    wp_register_style( 'css-magnific', PZARC_PLUGIN_APP_URL . '/public/js/Magnific-Popup/magnific-popup.css', FALSE, PZARC_VERSION );

    //icomoon
    wp_register_style( 'css-icomoon-arrows', PZARC_PLUGIN_APP_URL . '/shared/assets/fonts/icomoon/im-style.css', FALSE, PZARC_VERSION );

// Percentify Margins
    //  wp_enqueue_script( 'js-arc-percentifymargins', PZARC_PLUGIN_APP_URL . '/public/js/marginsPercentify.js', array( 'jquery' ), null, true );

    // DataTables
    wp_register_script( 'js-datatables', PZARC_PLUGIN_APP_URL . '/public/js/DataTables/media/js/jquery.dataTables.min.js', array( 'jquery' ), PZARC_VERSION, TRUE );
    wp_register_style( 'css-datatables', PZARC_PLUGIN_APP_URL . '/public/js/DataTables/media/css/jquery.dataTables.min.css', FALSE, PZARC_VERSION );

    // jQuery Collapse
    wp_register_script( 'js-jquery-collapse', PZARC_PLUGIN_APP_URL . '/public/js/jQuery-Collapse/src/jquery.collapse.js', array( 'jquery' ), PZARC_VERSION, TRUE );

    // hints.css
    wp_register_style( 'css-hints', 'https://cdnjs.cloudflare.com/ajax/libs/hint.css/2.5.0/hint.base.min.css', FALSE, PZARC_VERSION );
    if ( isset( $_GET['demo'] ) || ( isset( $_GET['debug'] ) && is_user_logged_in() ) ) {
      wp_enqueue_style( 'css-hints' );
    }

    $actions_options = get_option( '_architect_actions' );
    if ( ! empty( $actions_options ) ) { // v.1.15.0
      $actions = array();
      $i       = 1;
      foreach ( $actions_options as $k => $v ) {
        if ( $k !== 'last_tab' && $k !== 'architect_actions_number-of' ) {
          $actions[ $i ][ $k ] = $v;
        }
        if ( $k === 'architect_actions_' . $i . '_tax-slugs' ) { // TODO: Need a better way!
          $i ++;
        }
      }
      require_once PZARC_PLUGIN_APP_PATH . '/public/php/class_showblueprint.php';
      $bpactions = array();
      foreach ( $actions as $k => $v ) {
        if ( ! empty( $v[ 'architect_actions_' . $k . '_action-name' ] ) && ! empty( $v[ 'architect_actions_' . $k . '_blueprint' ] ) ) {
          $bpactions[ $k ] = new showBlueprint( $k, $v ); // We could pass this in all at once...
        }
      }

      unset( $bpactions );
    }
    // Override WP Gallery shortcode if necessary
    //
    global $_architect_options;

    // Just incase that didn't work... A problem from days of past
    if ( ! isset( $GLOBALS['_architect_options'] ) ) {
      $GLOBALS['_architect_options'] = get_option( '_architect_options', array() );
    }
    if ( ! empty( $_architect_options['architect_replace_wpgalleries'] ) ) {
      remove_shortcode( 'gallery' );
      add_shortcode( 'gallery', 'pzarc_shortcode' );

    }


  }

  /***********************
   *
   * Shortcode
   *
   ***********************/
  function pzarc_shortcode( $atts, $content = NULL, $tag = NULL ) {
//    if (is_admin()){
//      return '<img src="'.PZARC_PLUGIN_URL.'/assets/architect-logo-final-logo-only.svg" width=32 height=32>';
//    }
    $pzarc_caller    = 'shortcode';
    $pzarc_blueprint = '';
    $pzarc_overrides = NULL;

    if ( ! empty( $atts['blueprint'] ) ) {

      $pzarc_blueprint = $atts['blueprint'];

    } elseif ( ! empty( $atts[0] ) ) {
      $pzarc_blueprint = $atts[0];
    } elseif ( $tag === 'gallery' ) {
      global $_architect_options;
      $pzarc_blueprint = $_architect_options['architect_replace_wpgalleries'];
    }

    $pzarc_overrides = array();
//    if ( isset( $atts['ids'] ) ) {
//      $pzarc_overrides['ids'] = $atts['ids'];
//    }
//    if ( isset( $atts['tax'] ) ) {
//      $pzarc_overrides['tax'] = $atts['tax'];
//    }
//    if ( isset( $atts['terms'] ) ) {
//      $pzarc_overrides['terms'] = $atts['terms'];
//    }


    if ( is_array( $atts ) && ! empty( $atts ) ) {
      foreach ( $atts as $key => $value ) {
        $pzarc_overrides[ $key ] = $atts[ $key ];
      }
    }

    $tablet_bp            = isset( $atts['tablet'] ) ? $atts['tablet'] : NULL;
    $phone_bp             = isset( $atts['phone'] ) ? $atts['phone'] : NULL;
    $tag                  = NULL;
    $additional_overrides = NULL;

//    $pzarc_overrides = array( 'ids' => $pzarc_ids, 'tax' => $pzarc_tax, 'terms' => $pzarc_terms );

    // Need to capture the output so we can get it to appear where the shortcode actually is
    ob_start();


    do_action( 'arc_before_shortcode', $pzarc_blueprint, $pzarc_overrides, $pzarc_caller, $tag );


    // The caller is shortcode, and not variable here. It just uses a variable for consistency and documentation
    do_action( 'arc_do_shortcode', $pzarc_blueprint, $pzarc_overrides, $pzarc_caller, $tag, $additional_overrides, $tablet_bp, $phone_bp );

    do_action( 'arc_after_shortcode', $pzarc_blueprint, $pzarc_overrides, $pzarc_caller, $tag );

    $pzout = ob_get_contents();
    ob_end_clean();

    // Putting thru a filter so devs can do stuff with it
    return apply_filters( 'arc_filter_shortcode', $pzout, $pzarc_blueprint, $pzarc_overrides, $tag );

  }


  /**
   * Template tag
   *
   * @param null $pzarc_blueprint
   * @param null $pzarc_overrides
   * @param null $tablet_bp
   * @param null $phone_bp
   */
  function pzarchitect( $pzarc_blueprint = NULL, $pzarc_overrides = NULL, $tablet_bp = NULL, $phone_bp = NULL, $additional_overrides = NULL, $pzarc_caller = 'template_tag' ) {
    $tag = NULL;
    do_action( 'arc_before_template_tag', $pzarc_blueprint, $pzarc_overrides, $pzarc_caller );
    do_action( 'arc_do_template_tag', $pzarc_blueprint, $pzarc_overrides, $pzarc_caller, $tag, $additional_overrides, $tablet_bp, $phone_bp );
    do_action( 'arc_after_template_tag', $pzarc_blueprint, $pzarc_overrides, $pzarc_caller );
  }


  /***********************
   *
   * Page builder
   *
   * No longer used. Use Beaver
   *
   ***********************/
  function pzarc_pagebuilder( $pzarc_blueprint = NULL ) {
    $pzarc_caller         = 'pagebuilder';
    $tag                  = NULL;
    $additional_overrides = NULL;
    $tablet_bp            = NULL;
    $phone_bp             = NULL;
//TODO: Need to fix this up so it uses these right
//    do_action('arc_before_pagebuilder', $pzarc_blueprint, $pzarc_overrides, $pzarc_caller);
    do_action( 'arc_do_pagebuilder', $pzarc_blueprint, NULL, $pzarc_caller, $tag, $additional_overrides, $tablet_bp, $phone_bp );
//    do_action('arc_after_pagebuilder', $pzarc_blueprint, $pzarc_overrides, $pzarc_caller);
  }


  /***********************
   *
   * Blueprint main display function
   * Overrides is a list of ids
   *
   * @param null $blueprint
   * @param null $overrides - IDs
   * @param null $caller
   * @param null $tag
   * @param null $additional_overrides
   * @param null $tablet_bp
   * @param null $phone_bp
   */
  function pzarc( $blueprint = NULL, $overrides = NULL, $caller = NULL, $tag = NULL, $additional_overrides = NULL, $tablet_bp = NULL, $phone_bp = NULL ) {
    pzdb( 'start pzarc' );
    $pzarc_start_time = microtime( TRUE );

//global $wp_the_query, $wp_query;
//var_dump($wp_the_query->queried_object_id,$wp_query->queried_object_id);
    global $arc_blueprint_stack;
//    var_dump($arc_blueprint);

    // v.1.11.1: Protect against blueprints calling themselves
    if ( is_array( $arc_blueprint_stack ) && in_array( $blueprint, $arc_blueprint_stack ) ) {
      return;
    }

    $arc_blueprint_stack[] = $blueprint;


    //  var_dump(func_get_args());

    if ( ! class_exists( 'Mobile_Detect' ) ) {
      require_once( PZARC_PLUGIN_APP_PATH . '/shared/thirdparty/php/Mobile-Detect/Mobile_Detect.php' );
    }
    $detect = new Mobile_Detect;
    $device = 'desktop';

    // Switch Blueprint if available.
    switch ( TRUE ) {
      case ( $detect->isMobile() && ! $detect->isTablet() ):
        // Phone
        $blueprint = ( ! empty( $phone_bp ) && $phone_bp !== 'none' ) ? $phone_bp : $blueprint;
        $device    = 'phone';
        break;
      case ( $detect->isTablet() ):
        // Tablet
        $blueprint = ( ! empty( $tablet_bp ) && $tablet_bp !== 'none' ) ? $tablet_bp : $blueprint;
        $device    = 'tablet';
        break;
      default:
        // Desktop or other weird thing
        $blueprint = $blueprint; // WTF?
        $device    = 'desktop';
        break;
    }


    if ( 'show-none' === $blueprint ) {
      return;
    }

    // Register Blueprint usage on this page if necessary
    $bp_uses       = maybe_unserialize( get_option( 'arc-blueprint-usage' ) );
    $pzarc_page_id = pzarc_get_page_id();

    if (!( $bp_uses && array_key_exists( $pzarc_page_id . $blueprint, $bp_uses ) )) {
      $bp_uses[ $pzarc_page_id . $blueprint ] = array(
          'id' => $pzarc_page_id,
          'bp' => $blueprint,
      );
      update_option( 'arc-blueprint-usage', maybe_serialize( $bp_uses ) );
    }

    /* v1.17.0 Switched to using wp options for CSS to get away from filesystem API*/
    global $arc_blueprint_css;
    $arc_blueprint_css = $arc_blueprint_css . '/* '.$blueprint.' */' . get_option( '_arc-css_' . $blueprint );

    global $_architect_options;
    global $in_arc;
    $in_arc = 'yes';
    // Just incase that didn't work... A problem from days of past
    if ( ! isset( $GLOBALS['_architect_options'] ) ) {
      $GLOBALS['_architect_options'] = get_option( '_architect_options', array() );
    }

    wp_enqueue_style( PZARC_NAME . '-plugin-styles' );
    wp_enqueue_style( PZARC_NAME . '-dynamic-styles' );
//    wp_enqueue_style('pzarc_css');


    $is_shortcode = ( $caller == 'shortcode' );


    if ( empty( $blueprint ) && ( $is_shortcode && ( empty( $_architect_options['architect_default_shortcode_blueprint'] ) ) && empty( $_architect_options['architect_replace_wpgalleries'] ) ) ) {

      // TODO: Should we make this use a set of defaults. prob an excerpt grid
      echo '<p class="message-warning">Architect shortcode has no Blueprint specified. Usage [architect blueprint="blueprint short name"]</p>';

    } else {

      if ( empty( $blueprint ) && $is_shortcode ) {

        if ( $tag === 'gallery' ) {
          $blueprint = ( $_architect_options['architect_replace_wpgalleries'] ? $_architect_options['architect_replace_wpgalleries'] : $_architect_options['architect_default_shortcode_blueprint'] );
        } else {
          $blueprint = $_architect_options['architect_default_shortcode_blueprint'];
        }
      }

      require_once PZARC_PLUGIN_APP_PATH . '/public/php/class_architect_public.php';
     // require_once( PZARC_PLUGIN_APP_PATH . '/shared/thirdparty/php/BFI-thumb-forked/BFI_Thumb.php' );
      if (function_exists('bfi_thumb')) {
        add_filter( 'wp_image_editors', 'bfi_wp_image_editor' );
        add_filter( 'image_resize_dimensions', 'bfi_image_resize_dimensions', 10, 6 );
        add_filter( 'image_downsize', 'bfi_image_downsize', 1, 3 );
      }
      $architect = new Architect_Public( $blueprint, $is_shortcode, $device );

      // If no errors, let's go!
      if ( empty( $architect->build->blueprint['err_msg'] ) ) {

        if ( $architect->build->blueprint['_blueprints_section-0-layout-mode'] === 'slider' || $architect->build->blueprint['_blueprints_section-0-layout-mode'] === 'tabbed' ) {

          $slider_engine = empty( $architect->build->blueprint['_blueprints_slider-engine'] ) || $architect->build->blueprint['_blueprints_slider-engine'] === 'slick15' ? 'slick' : $architect->build->blueprint['_blueprints_slider-engine'];

          $registry     = arc_Registry::getInstance();
          $slider_types = (array) $registry->get( 'slider_types' );
          foreach ( $slider_types as $st ) {

            if ( $st['name'] === $slider_engine ) {
              require_once( $st['public'] );
              break;
            }
          }
        }
        /** This is it! **/
        $architect->build_blueprint( $overrides, $caller, $additional_overrides );

        // might need this... don't know
        //        if (is_main_query() || in_the_loop() || $caller === 'shortcode') {
        //        }
      }

      // Cleanup
      // If Blueprint is none, shortname is not set
      if ( isset( $architect->build->blueprint['_blueprints_short-name'] ) ) {
        remove_all_actions( 'arc_top_left_navigation_' . $architect->build->blueprint['_blueprints_short-name'] );
        remove_all_actions( 'arc_bottom_right_navigation_' . $architect->build->blueprint['_blueprints_short-name'] );
      }
      remove_filter( 'wp_image_editors', 'bfi_wp_image_editor' );

      unset ( $architect );
    }

    // Tell WP to resume using the main query just in case we might have accidentally left another query active. (0.9.0.2 This might be our saviour!)
    // Thois is at the end of the build, so shouldnt' be needed again! v1.6.1
    //    wp_reset_postdata();
    //    pzdebug('wp_reset_postdata');

    pzdb( 'end pzarc' );
    //remove_filter( 'wp_image_editors', 'bfi_wp_image_editor' );// Maybe problem with?
    remove_filter( 'image_resize_dimensions', 'bfi_image_resize_dimensions' );
    remove_filter( 'image_downsize', 'bfi_image_downsize' );

    $in_arc = 'no';
    // v.1.11.1: Remove the last blueprint in the stack else we can't use same Blueprint multiple times on a page.
    array_pop( $arc_blueprint_stack );

    $pzarc_end_time  = microtime( TRUE );
    $pzarc_this_time = ( $pzarc_end_time - $pzarc_start_time );

// TODO: We only want to run this when needed!!
    if ( $pzarc_this_time > 0 ) {
      $pzarc_architect_timers               = get_option( 'architect_timers', array( 'runs' => 0, 'total_time' => '0', 'avg_time' => 0 ) );
      $pzarc_architect_timers['runs']       = $pzarc_architect_timers['runs'] + 1;
      $pzarc_architect_timers['total_time'] = $pzarc_architect_timers['total_time'] + $pzarc_this_time;
      $pzarc_architect_timers['avg_time']   = $pzarc_architect_timers['total_time'] / $pzarc_architect_timers['runs'];
      update_option( 'architect_timers', $pzarc_architect_timers );
      //delete_option( 'architect_timers' );
    }
//    var_dump($pzarc_this_time);
//    var_dump($pzarc_architect_timers);

  }


  // TODO: Shouldn't need this if we're going to do outputting
  /********************************************
   *
   * Capture and append the comments display
   *
   ********************************************/
  //add_filter('arc_comments', 'pzarc_get_comments');
  function pzarc_get_comments( $pzarc_content ) {
    ob_start();
    comments_template( NULL, NULL );
    $pzarc_comments = ob_get_contents();
    ob_end_flush();

    return $pzarc_content . $pzarc_comments;
  }

  /********************************************
   *
   * Add body class by filter
   *
   ********************************************/

  function add_pzarc_class( $classes ) {
    // add 'class-name' to the $classes array
    if ( ! in_array( 'pzarchitect', $classes ) ) {
      $classes[] = 'pzarchitect';
    }
    if ( isset( $_GET['demo'] ) || ( isset( $_GET['debug'] ) && is_user_logged_in() ) ) {
      $classes[] = 'architect-demo-mode';
    }
    $classes[] = 'theme-' . get_stylesheet();

    // return the $classes array
    return $classes;
  }

  /**
   * pzarc_footer_css
   * Add Blueprints' CSS to the footer
   */
  function pzarc_footer_css(){
    global $arc_blueprint_css;
    echo '<!-- Architect Blueprints CSS -->';
    echo '<style type="text/css">';
    echo $arc_blueprint_css;
    echo '</style>';
  }


