<?php
  /**
   * Created by PhpStorm.
   * User: chrishoward
   * Date: 23/8/17
   * Time: 3:35 PM
   */

  class pzArchitect {

    function __construct() {

      define( 'PZARC_PLUGIN_URL', trailingslashit( plugin_dir_url( __FILE__ ) ) );
      define( 'PZARC_PLUGIN_PATH', trailingslashit( plugin_dir_path( __FILE__ ) ) );
      define( 'PZARC_PLUGIN_FILE', __FILE__ );

      require_once PZARC_PLUGIN_PATH . '/application/shared/architect/php/arc-constants.php';

      pzdb( 'after dependency check' );

      if ( is_admin() ) {
        // Before we go anywhere, make sure dependent plugins are loaded and active.
        require_once PZARC_PLUGIN_APP_PATH . '/shared/architect/php/arc-check-dependencies.php';
        wp_mkdir_p( PZARC_CACHE_PATH );
        // Why the heck doesn't this work when it's in initialise?
        pzdb( 'after dependency check' );
      }

      // Need this one to create the Architect widget
      require_once PZARC_PLUGIN_APP_PATH . '/shared/architect/php/class_arc_widget.php';

      // Load plugin text domain
      add_action( 'init', array( $this, 'pzarc_text_domain' ) );

      require_once PZARC_PLUGIN_APP_PATH . '/shared/architect/php/arc-functions.php';

      // Register admin styles and scripts

      if ( is_admin() ) {
        require_once PZARC_PLUGIN_APP_PATH . '/arc-admin.php';
        pzdb( 'after admin load' );
      } else {
        require_once PZARC_PLUGIN_APP_PATH . '/arc-public.php';
        pzdb( 'after public  load' );
      }

      // Register hooks that are fired when the plugin is activated, deactivated, and uninstalled, respectively.
      register_activation_hook( __FILE__, array( $this, 'activate' ) );
      register_deactivation_hook( __FILE__, array( $this, 'deactivate' ) );
      //	register_uninstall_hook( __FILE__, array( $this, 'uninstall' ) );

      add_action( 'init', array( $this, 'init' ) );
      add_action( 'after_setup_theme', array( $this, 'register_architect_headway_block' ) );
      add_action( 'after_setup_theme', array( $this, 'register_architect_blox_block' ) );

      add_action( "redux/options/_architect/saved", 'pzarc_set_defaults', 20, 2 );

      require_once PZARC_PLUGIN_APP_PATH . '/shared/architect/php/class_arc_registry.php';

      if ( version_compare( PHP_VERSION, '5.3.0' ) >= 0 ) {
        // include PHP5.3+ code here
        require_once PZARC_PLUGIN_APP_PATH . '/shared/architect/php/class_arc_set_data.php';
      } else {
        // load legacy code here
        require_once PZARC_PLUGIN_APP_PATH . '/shared/architect/php/class_arc_set_data-legacy.php';
      }
      require_once PZARC_PLUGIN_APP_PATH . '/shared/architect/php/class_arc_blueprint_data.php';

      // Load custom custom types
      if ( is_admin() ) {
        require_once PZARC_PLUGIN_APP_PATH . '/shared/architect/php/arc-cpt-panels.php';

        self::update();

      }
      require_once PZARC_PLUGIN_APP_PATH . '/shared/architect/php/arc-cpt-blueprints.php';

      // Load all the builtin post types
      pzdb( 'Load builtin post types' );
      require_once PZARC_PLUGIN_APP_PATH . '/shared/architect/php/content-types/defaults/class_arc_content_defaults.php';
      require_once PZARC_PLUGIN_APP_PATH . '/shared/architect/php/content-types/post/class_arc_content_posts.php';
      require_once PZARC_PLUGIN_APP_PATH . '/shared/architect/php/content-types/page/class_arc_content_pages.php';
      require_once PZARC_PLUGIN_APP_PATH . '/shared/architect/php/content-types/dummy/class_arc_content_dummy.php';


      require_once PZARC_PLUGIN_PATH . '/extensions-inc/beaver-builder/fl-custom-module-architect.php';


      // Pro
      pzdb( 'before architect pro' );
      $this->check_licencing();


      pzdb( 'after architect pro' );


      // Extensions can hook in here
      do_action( 'arc_load_extensions' );


      require_once( PZARC_PLUGIN_PATH . '/extensions-inc/sliders/slick15/arc-slick15-init.php' );


      // Rebuiild cache if instructed

      if ( get_option( 'pzarc-run-rebuild' ) ) {

        add_action( 'admin_init', 'pzarc_rebuild' );

      }
    }

    function __destruct() {
      pzdb( 'the end' );
    }

    public function init() {

      // TODO: Is this still used??
      if ( defined( 'PZARC_PRO' ) && PZARC_PRO ) {
        @include PZARC_PLUGIN_PATH . '/extensions/architect-pro-cpt.php';
      }
      if ( ! is_admin() && isset( $_GET['arcbp'] ) ) {
        // This needs to be an action/filter after header...
        // or redirect to a custom template
        pzarchitect( $_GET['arcbp'] );
      }


    }


    public function register_architect_headway_block() {

      if ( class_exists( 'HeadwayDisplay' ) ) {
        require( 'extensions-inc/headway/public/arc-headway-block-display.php' );
        require( 'extensions-inc/headway/admin/arc-headway-block-options.php' );

        return headway_register_block( 'HeadwayArchitectBlock', PZARC_PLUGIN_URL . '/extensions-inc/headway/admin' );
      }
    }

    public function register_architect_blox_block() {

      if ( class_exists( 'BloxDisplay' ) ) {
        require( 'extensions-inc/blox/public/arc-blox-block-display.php' );
        require( 'extensions-inc/blox/admin/arc-blox-block-options.php' );

        return blox_register_block( 'BloxArchitectBlock', PZARC_PLUGIN_URL . '/extensions-inc/blox/admin' );
      }
    }

    /**
     * Fired when the plugin is activated.
     *
     * @param  boolean $network_wide True if WPMU superadmin uses "Network Activate" action, false if WPMU is disabled or plugin is activated on an individual blog
     */
    public function activate( $network_wide ) {
      /** Check for correct version of Pizazz Libs  */
      if ( defined( 'PIZAZZ_VERSION' ) ) {
        if ( version_compare( PIZAZZ_VERSION, '1.6.3', '<' ) ) {
          die( __( 'Cannot activate Architect because an out of date version of PizazzWP Libs is active. It needs to be at least version 1.6.3. Deactivate or upgrade it, and try again. Here is a manual link if you need it: <a href="https://s3.amazonaws.com/341public/LATEST/pizazzwp-libs.zip">Latest Pizazz Libs</a>', 'pzarchitect' ) );

          return;
        }

      }
      if ( ! class_exists( 'TGM_Plugin_Activation' ) ) {
        require_once PZARC_PLUGIN_APP_PATH . '/shared/architect/php/arc-check-dependencies.php';
      }
      pzarc_set_defaults(); //Run here in case we've added any new fields or changed their defaults.
      update_option( 'pzarc-run-rebuild', TRUE );

    }

    public function admin_initialize() {
    }

// end activate

    /**
     * Fired when the plugin is deactivated.
     *
     * @param  boolean $network_wide True if WPMU superadmin uses "Network Activate" action, false if WPMU is disabled or plugin is activated on an individual blog
     */
    public function deactivate( $network_wide ) {
      // TODO:	Define deactivation functionality here
    }

// end deactivate

    /**
     * Fired when the plugin is uninstalled.
     *
     * @param  boolean $network_wide True if WPMU superadmin uses "Network Activate" action, false if WPMU is disabled or plugin is activated on an individual blog
     */
    public function uninstall( $network_wide ) {
      // TODO:	Define uninstall functionality here
    }

// end uninstall

    /**
     * Loads the plugin text domain for translation
     */
    public function pzarc_text_domain() {

      $domain = PZARC_NAME;
      $locale = apply_filters( 'plugin_locale', get_locale(), $domain );
      load_textdomain( $domain, WP_LANG_DIR . '/' . $domain . '/' . $domain . '-' . $locale . '.mo' );
      //  var_dump(WP_LANG_DIR . '/' . $domain . '/' . $domain . '-' . $locale . '.mo',dirname( plugin_basename( __FILE__ ) ) . '/application/languages/');
      load_plugin_textdomain( $domain, FALSE, dirname( plugin_basename( __FILE__ ) ) . '/application/languages/' );
    }

// end plugin_textdomain

    /**
     * Registers and enqueues admin-specific styles.
     */
    public function register_admin_styles() {

      wp_enqueue_style( 'pzarc-admin-styles', PZARC_PLUGIN_APP_URL . '/admin/css/arc-admin.css', FALSE, time() );
      wp_register_style( 'pzarc-jqueryui-css', PZARC_PLUGIN_APP_URL . '/shared/thirdparty/jquery-ui-1.10.2.custom/css/pz_architect/jquery-ui-1.10.2.custom.min.css', FALSE, PZARC_VERSION );
//wp_enqueue_style('fontawesome','//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css');
    }

// end register_admin_styles

    /**
     * Registers and enqueues admin-specific JavaScript.
     */
    public function register_admin_scripts() {

    }

// end register_admin_scripts

    /**
     * Registers and enqueues plugin-specific styles.
     */
    public function register_plugin_styles() {

      /** Check Blueprint uses registry to see if we need to load css for blueprints on this page. */
      $bp_uses = maybe_unserialize( get_option( 'arc-blueprint-usage' ) );
      if ( is_array( $bp_uses ) ) {
        $page_id = pzarc_get_page_id();
        foreach ( $bp_uses as $k => $v ) {
          if ( $v['id'] === $page_id ) {
            $filename      = PZARC_CACHE_URL . '/pzarc_blueprint_' . $v['bp'] . '.css';
            $filename_path = PZARC_CACHE_PATH . '/pzarc_blueprint_' . $v['bp'] . '.css';
            if ( file_exists( $filename_path ) ) {
              wp_enqueue_style( 'pzarc_css_blueprint_' . $v['bp'], $filename, FALSE, filemtime( $filename_path ) );
            } else {
              //how do we tell the developer without an horrid message on the front end?
            }
          }
        }
      }
      wp_enqueue_style( PZARC_NAME . '-styles', PZARC_PLUGIN_APP_URL . '/public/css/architect-styles.css', FALSE, PZARC_VERSION );
      wp_register_style( PZARC_NAME . '-plugin-styles', PZARC_PLUGIN_APP_URL . '/public/css/arc-front.css', FALSE, PZARC_VERSION );
      // Need this for custom CSS in styling options
      if ( file_exists( PZARC_CACHE_PATH . 'arc-dynamic-styles.css' ) ) {
        wp_register_style( PZARC_NAME . '-dynamic-styles', PZARC_CACHE_URL . 'arc-dynamic-styles.css', FALSE, PZARC_VERSION );
      }
    }

// end register_plugin_styles

    /**
     * Registers and enqueues plugin-specific scripts.
     */
    public function register_plugin_scripts() {

      wp_enqueue_script( 'jquery' );
      wp_register_script( 'js-imagesloaded', PZARC_PLUGIN_APP_URL . '/public/js/imagesloaded.pkgd.min.js', array( 'jquery' ), PZARC_VERSION, TRUE );

      wp_register_script( 'js-isotope', PZARC_PLUGIN_APP_URL . '/public/js/isotope.pkgd.min.js', array( 'jquery' ), PZARC_VERSION, TRUE );
      wp_register_script( 'js-isotope-packery', PZARC_PLUGIN_APP_URL . '/public/js/packery-mode.pkgd.js', array( 'jquery' ), PZARC_VERSION, TRUE );

//        wp_register_script( 'js-isotope', PZARC_PLUGIN_APP_URL . '/public/js/isotope.pkgd.min.js', array( 'jquery' ), PZARC_VERSION, true );
//        wp_register_script( 'js-isotope-packery', PZARC_PLUGIN_APP_URL . '/public/js/packery-mode.pkgd.min.js', array( 'jquery' ), PZARC_VERSION, true );

      wp_register_script( 'js-front', PZARC_PLUGIN_APP_URL . '/public/js/arc-front.js', array( 'jquery' ), PZARC_VERSION, TRUE );
      wp_register_script( 'js-front-isotope', PZARC_PLUGIN_APP_URL . '/public/js/arc-front-isotope.js', array( 'jquery' ), PZARC_VERSION, TRUE );


    }

// end register_plugin_scripts

    private static function update() {
      $current_db_version = get_option( 'architect_db_version' );
      $db_updates         = array(
        '1.1.0.0' => 'updates/architect-1100.php',
        '1.2.0.0' => 'updates/architect-1200.php',
        '1.3.0.0' => 'updates/architect-1300.php',
        '1.4.0.0' => 'updates/architect-1400.php',
        '1.5.0.0' => 'updates/architect-1500.php',
        '1.6.0.0' => 'updates/architect-1600.php',
        '1.8.0.0' => 'updates/architect-1800.php',
      );

      foreach ( $db_updates as $version => $updater ) {
        if ( version_compare( $current_db_version, $version, '<' ) ) {
          include( $updater );
          update_option( 'architect_db_version', $version );
        }
      }

      update_option( 'architect_db_version', PZARC_VERSION );
    }

    public function check_licencing() {
      // This is a shorthand way of doing an if. When pro isn't present, it's the lite version.
      $pzarc_current_theme = wp_get_theme();

      $hw_opts      = NULL;
      $pzarc_status = get_option( 'edd_architect_license_status' );

      // There is no Blox licencing so don't need to check for that
      if ( ( $pzarc_current_theme->get( 'Name' ) === 'Headway Base' || $pzarc_current_theme->get( 'Template' ) == 'headway' ) && ( $pzarc_status == FALSE || $pzarc_status !== 'valid' ) ) {
        if ( is_multisite() ) {
          $hw_opts = get_blog_option( 1, 'headway_option_group_general' );

        } else {
          $hw_opts = get_option( 'headway_option_group_general' );
        }


      }

      if ( arc_fs()->is__premium_only() ) {
        $pzarc_status = 'valid';
      }

      if ( ( ! empty( $hw_opts['license-status-architect'] ) && $hw_opts['license-status-architect'] == 'valid' ) || ( $pzarc_status !== FALSE && $pzarc_status == 'valid' ) ) {
        define( 'PZARC_PRO', TRUE );
        @include PZARC_PLUGIN_PATH . '/extensions/architect-pro-layout.php';
      }

      switch ( TRUE ) {
        case ( ! empty( $hw_opts['license-status-architect'] ) && $hw_opts['license-status-architect'] == 'valid' ) && ( $pzarc_status === FALSE || $pzarc_status !== 'valid' ) :
          define( 'PZARC_SHOP', '(H)' );
          break;
        case ( $pzarc_status !== FALSE && $pzarc_status === 'valid' ) :
          define( 'PZARC_SHOP', '(P)' );
          break;
        default:
          define( 'PZARC_SHOP', '(L)' );

      }
    }


  }

  // end class

