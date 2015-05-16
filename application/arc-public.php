<?php
  /**
   * Created by JetBrains PhpStorm.
   * User: chrishoward
   * Date: 13/08/13
   * Time: 8:32 PM
   */


  add_action( 'wp_head', 'pzarc_display_init' );

  add_shortcode( 'architect', 'pzarc_shortcode' );
  add_shortcode( 'pzarc', 'pzarc_shortcode' ); // Old version
  add_shortcode( 'pzarchitect', 'pzarc_shortcode' ); // alternate version
  // I still don't understand why this works!! One day, maybe I will
  add_action( 'arc_do_shortcode', 'pzarc', 10, 7 );
  add_filter( 'body_class', 'add_pzarc_class' );

  add_action( 'arc_do_pagebuilder', 'pzarc', 10, 7 );
  add_action( 'arc_do_template_tag', 'pzarc', 10, 7 );

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
    if ( empty( $pzarc_css_cache[ 'blueprints' ] ) ) {
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


    wp_register_style( 'css-hw-float-fix', PZARC_PLUGIN_APP_URL . '/public/css/arc-hw-fix.css' );

    // TODO: These seem to be loading late so loading in footer - even the CSS!
    // Retina Js
    // Using hacked version which only supports data-at2x attribute
    wp_register_script( 'js-retinajs', PZARC_PLUGIN_APP_URL . '/public/js/retinajs/retina.js' );


    // Magnific
    wp_register_script( 'js-magnific-arc', PZARC_PLUGIN_APP_URL . '/public/js/arc-front-magnific.js', array( 'jquery' ), null, true );
    wp_register_script( 'js-magnific', PZARC_PLUGIN_APP_URL . '/public/js/Magnific-Popup/jquery.magnific-popup.min.js', array( 'jquery' ), null, true );
    wp_register_style( 'css-magnific', PZARC_PLUGIN_APP_URL . '/public/js/Magnific-Popup/magnific-popup.css' );

    //icomoon
    wp_register_style( 'css-icomoon-arrows', PZARC_PLUGIN_APP_URL . '/shared/assets/fonts/icomoon/im-style.css' );


    // DataTables
    wp_register_script( 'js-datatables', PZARC_PLUGIN_APP_URL . '/public/js/DataTables/media/js/jquery.dataTables.min.js', array( 'jquery' ), null, true );
    wp_register_style( 'css-datatables', PZARC_PLUGIN_APP_URL . '/public/js/DataTables/media/css/jquery.dataTables.min.css' );

    // jQuery Collapse
    wp_register_script( 'js-jquery-collapse', PZARC_PLUGIN_APP_URL . '/public/js/jQuery-Collapse/src/jquery.collapse.js', array( 'jquery' ), null, true );


    $actions_options = get_option( '_architect_actions' );
    $actions         = array();
    $i               = 1;
    foreach ( $actions_options as $k => $v ) {
      if ( $k != 'last_tab' ) {
        $actions[ $i ][ $k ] = $v;
      }
      if ( $k == 'architect_actions_' . $i . '_pageids' ) {
        $i ++;
      }
    }
    require_once PZARC_PLUGIN_APP_PATH . '/public/php/class_showblueprint.php';
    foreach ( $actions as $k => $v ) {
      if ( isset( $v[ 'architect_actions_' . $k . '_action-name' ] ) && isset( $v[ 'architect_actions_' . $k . '_blueprint' ] ) ) {
        new showBlueprint( $v[ 'architect_actions_' . $k . '_action-name' ], $v[ 'architect_actions_' . $k . '_blueprint' ], 'home' );
      }
    }

    // Override WP Gallery if necessary
    global $_architect_options;

    // Just incase that didn't work... A problem from days of past
    if ( ! isset( $GLOBALS[ '_architect_options' ] ) ) {
      $GLOBALS[ '_architect_options' ] = get_option( '_architect_options', array() );
    }
    if ( ! empty( $_architect_options[ 'architect_replace_wpgalleries' ] ) ) {
      remove_shortcode( 'gallery' );
      add_shortcode( 'gallery', 'pzarc_shortcode' );

    }
  }

  /***********************
   *
   * Shortcode
   *
   ***********************/
  function pzarc_shortcode( $atts, $content = null, $tag ) {
    $pzarc_caller    = 'shortcode';
    $pzarc_blueprint = '';
    $pzarc_overrides = null;

    if ( ! empty( $atts[ 'blueprint' ] ) ) {

      $pzarc_blueprint = $atts[ 'blueprint' ];

    } elseif ( ! empty( $atts[ 0 ] ) ) {
      $pzarc_blueprint = $atts[ 0 ];
    }

    $pzarc_overrides      = isset( $atts[ 'ids' ] ) ? array( 'ids' => $atts[ 'ids' ] ) : null;
    $tablet_bp            = isset( $atts[ 'tablet' ] ) ? $atts[ 'tablet' ] : null;
    $phone_bp             = isset( $atts[ 'phone' ] ) ? $atts[ 'phone' ] : null;
    $tag                  = null;
    $additional_overrides = null;

    // Need to capture the output so we can get it to appear where the shortcode actually is
    ob_start();


    do_action( "arc_before_shortcode", $pzarc_blueprint, $pzarc_overrides, $pzarc_caller, $tag );


    // The caller is shortcode, and not variable here. It just uses a variable for consistency and documentation
    do_action( "arc_do_shortcode", $pzarc_blueprint, $pzarc_overrides, $pzarc_caller, $tag, $additional_overrides, $tablet_bp, $phone_bp );

    do_action( "arc_after_shortcode", $pzarc_blueprint, $pzarc_overrides, $pzarc_caller, $tag );

    $pzout = ob_get_contents();
    ob_end_clean();

    // Putting thru a filter so devs can do stuff with it
    return apply_filters( 'arc_filter_shortcode', $pzout, $pzarc_blueprint, $pzarc_overrides, $tag );

  }


  /***********************
   *
   * Template tag
   *
   ***********************/
  function pzarchitect( $pzarc_blueprint = null, $pzarc_overrides = null, $tablet_bp = null, $phone_bp = null ) {
    $pzarc_caller         = 'template_tag';
    $tag                  = null;
    $additional_overrides = null;
    do_action( "arc_before_template_tag", $pzarc_blueprint, $pzarc_overrides, $pzarc_caller );
    do_action( "arc_do_template_tag", $pzarc_blueprint, $pzarc_overrides, $pzarc_caller, $tag, $additional_overrides, $tablet_bp, $phone_bp );
    do_action( "arc_after_template_tag", $pzarc_blueprint, $pzarc_overrides, $pzarc_caller );
  }


  /***********************
   *
   * Page builder
   *
   ***********************/
  function pzarc_pagebuilder( $pzarc_blueprint = null ) {
    $pzarc_caller         = 'pagebuilder';
    $tag                  = null;
    $additional_overrides = null;
    $tablet_bp            = null;
    $phone_bp             = null;
//TODO: Need to fix this up so it uses these right
//    do_action("arc_before_pagebuilder", $pzarc_blueprint, $pzarc_overrides, $pzarc_caller);
    do_action( "arc_do_pagebuilder", $pzarc_blueprint, null, $pzarc_caller, $tag, $additional_overrides, $tablet_bp, $phone_bp );
//    do_action("arc_after_pagebuilder", $pzarc_blueprint, $pzarc_overrides, $pzarc_caller);
  }


  /***********************
   *
   * Blueprint main display function
   * Overrides is a list of ids
   *
   ******************************/
  function pzarc( $blueprint = null, $overrides = null, $caller, $tag = null, $additional_overrides = null, $tablet_bp = null, $phone_bp = null ) {
    pzdb( 'start pzarc' );

    require_once( PZARC_PLUGIN_APP_PATH . '/shared/thirdparty/php/Mobile-Detect/Mobile_Detect.php' );
    $detect = new Mobile_Detect;
    $device = 'not set';

    switch ( true ) {
      case ( $detect->isMobile() && ! $detect->isTablet() ):
        // Phone
        $blueprint = ! empty( $phone_bp ) ? $phone_bp : $blueprint;
        $device    = 'phone';
        break;
      case ( $detect->isTablet() ):
        // Tablet
        $blueprint = ! empty( $tablet_bp ) ? $tablet_bp : $blueprint;
        $device    = 'tablet';
        break;
      default:
        // Desktop or other weird thing
        $blueprint = $blueprint;
        $device    = 'desktop';
        break;
    }

    if ( 'show-none' === $blueprint ) {
      return;
    }
    // Shortcodes will load these late. TODO Should search for shortcode in page
    $filename      = PZARC_CACHE_URL . '/pzarc_blueprint_' . $blueprint . '.css';
    $filename_path = PZARC_CACHE_PATH . '/pzarc_blueprint_' . $blueprint . '.css';
    if ( file_exists( $filename_path ) ) {
      wp_enqueue_style( 'pzarc_css_blueprint_' . $blueprint, $filename, false, filemtime( $filename_path ) );
    } else {
      //how do we tell the developer without an horrid message on the front end?
    }

    global $_architect_options;
    global $in_arc;
    $in_arc = 'yes';
    // Just incase that didn't work... A problem from days of past
    if ( ! isset( $GLOBALS[ '_architect_options' ] ) ) {
      $GLOBALS[ '_architect_options' ] = get_option( '_architect_options', array() );
    }


    wp_enqueue_style( PZARC_NAME . '-plugin-styles' );
    wp_enqueue_style( PZARC_NAME . '-dynamic-styles' );
//    wp_enqueue_style('pzarc_css');


    $is_shortcode = ( $caller == 'shortcode' );


    if ( empty( $blueprint ) && ( $is_shortcode && ( empty( $_architect_options[ 'architect_default_shortcode_blueprint' ] ) ) && empty( $_architect_options[ 'architect_replace_wpgalleries' ] ) ) ) {

      // TODO: Should we make this use a set of defaults. prob an excerpt grid
      echo '<p class="message-warning">You need to set a blueprint</p>';

    } else {

      if ( empty( $blueprint ) && $is_shortcode ) {

        if ( $tag === 'gallery' ) {
          $blueprint = ( $_architect_options[ 'architect_replace_wpgalleries' ] ? $_architect_options[ 'architect_replace_wpgalleries' ] : $_architect_options[ 'architect_default_shortcode_blueprint' ] );
        } else {
          $blueprint = $_architect_options[ 'architect_default_shortcode_blueprint' ];
        }
      }

      require_once PZARC_PLUGIN_APP_PATH . '/public/php/class_architect_public.php';
      require_once( PZARC_PLUGIN_APP_PATH . '/shared/thirdparty/php/BFI-thumb-forked/BFI_Thumb.php' );

      $architect = new ArchitectPublic( $blueprint, $is_shortcode );
//var_dump($architect);
      // If no errors, let's go!
      if ( empty( $architect->build->blueprint[ 'err_msg' ] ) ) {

        if ( $architect->build->blueprint[ '_blueprints_section-0-layout-mode' ] === 'slider' ) {
          if (!empty($architect->build->blueprint[ '_blueprints_slider-engine' ])) {
            $registry     = arc_Registry::getInstance();
            $slider_types = (array) $registry->get( 'slider_types' );
            foreach ( $slider_types as $st ) {

              if ( $st[ 'name' ] === $architect->build->blueprint[ '_blueprints_slider-engine' ] ) {
                require_once( $st[ 'public' ] );
                break;
              }
            }
          } else {
            require_once(PZARC_PLUGIN_PATH. '/extensions/sliders/slick/arc-slick-public.php');
          }
        }
        /** This is it! **/
        $architect->build_blueprint( $overrides, $caller, $additional_overrides );

        // might need this... don't know
        if ( is_main_query() || in_the_loop() || $caller === 'shortcode' ) {
        }
      }

      // Cleanup
      // If Blueprint is none, shortname is not set
      if ( isset( $architect->build->blueprint[ '_blueprints_short-name' ] ) ) {
        remove_all_actions( 'arc_top_left_navigation_' . $architect->build->blueprint[ '_blueprints_short-name' ] );
        remove_all_actions( 'arc_bottom_right_navigation_' . $architect->build->blueprint[ '_blueprints_short-name' ] );
      }

      unset ( $architect );
    }

    // Tell WP to resume using the main query just in case we might have accidentally left another query active. (0.9.0.2 This might be our saviour!)
    wp_reset_postdata();
    pzdb( 'end pzarc' );

    $in_arc = 'no';
  }


  // TODO: Shouldn't need this if we're going to do outputting
  /********************************************
   *
   * Capture and append the comments display
   *
   ********************************************/
  //add_filter('pzarc_comments', 'pzarc_get_comments');
  function pzarc_get_comments( $pzarc_content ) {
    ob_start();
    comments_template( null, null );
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
      $classes[ ] = 'pzarchitect';
    }

    $classes[ ] = 'theme-' . get_stylesheet();

    // return the $classes array
    return $classes;
  }

