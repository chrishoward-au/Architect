<?php

  /*
    Plugin Name: Architect
    Plugin URI: http://architect4wp.com
    Description: Architect is an all-in-one content layout framework to extend your theme. Go beyond the limitations of the theme you use to easily build any content layouts for it. Build your own grids, tabs, sliders, galleries and more with sources such as posts, pages, galleries, and custom content types. Display using shortcodes, widgets, Headway blocks, WP action hooks and template tags, and WP Gallery shortcode. Change themes without needing to rebuild your layouts!
    Version: 1.0.6
    Author: Chris Howard
    Author URI: http://pizazzwp.com
    License: GNU GPL v2
    Support: support@pizazzwp.com
   */

  if (!defined('ABSPATH')) {
    exit;
  } // Exit if accessed directly

  define('PZDEBUG', false);
  if (PZDEBUG) {
    global $pzstart_time;
    $pzstart_time = microtime(true);
    pzdb('start');
  }

  class pzArchitect
  {

    function __construct()
    {

      define('PZARC_VERSION', '1.0.6');
      define('PZARC_NAME', 'pzarchitect'); // This is also same as the locale
      define('PZARC_FOLDER', '/pizazzwp-architect');
      define('PZARC_CODEX', 'http://architect4wp.com/codex-listings');
      define('PZARC_HWREL', true);

      define('PZARC_PLUGIN_URL', trailingslashit(plugin_dir_url(__FILE__)));
      define('PZARC_PLUGIN_PATH', trailingslashit(plugin_dir_path(__FILE__)));
      define('PZARC_PLUGIN_APP_URL', PZARC_PLUGIN_URL . 'application/');
      define('PZARC_PLUGIN_APP_PATH', PZARC_PLUGIN_PATH . 'application/');
      define('PZARC_DOCUMENTATION_URL', PZARC_PLUGIN_URL . 'documentation/');
      define('PZARC_DOCUMENTATION_PATH', PZARC_PLUGIN_PATH . 'documentation/');
      define('PZARC_PLUGIN_ASSETS_URL', PZARC_PLUGIN_APP_URL . 'shared/assets/');
      define('PZARC_PLUGIN_PRESETS_URL', PZARC_PLUGIN_URL . 'presets/');
      define('PZARC_CACHE', '/arc/');
      // TODO: Setup an option for changing the language
      $language = substr(get_locale(), 0, 2);

      define('PZARC_LANGUAGE', 'en');

      define('PZARC_TRANSIENTS_KEEP', 12 * HOUR_IN_SECONDS);
      $upload_dir = wp_upload_dir();

      // TODO: why isn't this using myfiles folder?
      define('PZARC_CACHE_URL', trailingslashit($upload_dir[ 'baseurl' ] . '/cache/pizazzwp/arc'));
      define('PZARC_CACHE_PATH', trailingslashit($upload_dir[ 'basedir' ] . '/cache/pizazzwp/arc'));


      pzdb('after dependency check');
      if (is_admin()) {
        // Before we go anywhere, make sure dependent plugins are loaded and active.
        require_once PZARC_PLUGIN_APP_PATH . '/shared/architect/php/arc-check-dependencies.php';
        wp_mkdir_p(PZARC_CACHE_PATH);
        pzdb('after dependency check');
      }

      // Need this one to create the Architect widget
      require_once PZARC_PLUGIN_APP_PATH . '/shared/architect/php/class_arc_widget.php';

      // Load plugin text domain
      add_action('init', array($this, 'pzarc_text_domain'));

      require_once PZARC_PLUGIN_APP_PATH . '/shared/architect/php/arc-functions.php';

      // Register admin styles and scripts

      if (is_admin()) {
        add_action('admin_print_styles', array($this, 'register_admin_styles'));
        add_action('admin_enqueue_scripts', array($this, 'register_admin_scripts'));
        //		add_action( 'init', array( $this, 'admin_initialize' ) );
        require_once PZARC_PLUGIN_APP_PATH . '/arc-admin.php';
        pzdb('after admin load');

      } else {
        // Front end includes, Register site styles and scripts
        add_action('wp_enqueue_scripts', array($this, 'register_plugin_styles'));
        add_action('wp_enqueue_scripts', array($this, 'register_plugin_scripts'));


        require_once PZARC_PLUGIN_APP_PATH . '/arc-public.php';
        pzdb('after public  load');

      }

      // Register hooks that are fired when the plugin is activated, deactivated, and uninstalled, respectively.
      register_activation_hook(__FILE__, array($this, 'activate'));
      register_deactivation_hook(__FILE__, array($this, 'deactivate'));
      //	register_uninstall_hook( __FILE__, array( $this, 'uninstall' ) );

      add_action('init', array($this, 'init'));
      add_action('after_setup_theme', array($this, 'register_architect_block'));


      require_once PZARC_PLUGIN_APP_PATH . '/shared/architect/php/class_arc_registry.php';

      if (version_compare(PHP_VERSION, '5.3.0') >= 0) {
        // include PHP5.3+ code here
        require_once PZARC_PLUGIN_APP_PATH . '/shared/architect/php/class_arc_set_data.php';
      } else {
        // load legacy code here
        require_once PZARC_PLUGIN_APP_PATH . '/shared/architect/php/class_arc_set_data-legacy.php';
      }
      require_once PZARC_PLUGIN_APP_PATH . '/shared/architect/php/class_arc_blueprint_data.php';

      // Load custom custom types
      require_once PZARC_PLUGIN_APP_PATH . '/shared/architect/php/arc-cpt-panels.php';
      require_once PZARC_PLUGIN_APP_PATH . '/shared/architect/php/arc-cpt-blueprints.php';

      // Load all the builtin post types
      require_once PZARC_PLUGIN_APP_PATH . '/shared/architect/php/content-types/defaults/class_arc_content_defaults.php';
      require_once PZARC_PLUGIN_APP_PATH . '/shared/architect/php/content-types/post/class_arc_content_posts.php';

      pzdb('before architect pro');

      // This is a shorthand way of doing an if. When pro isn't present, it's the lite version.
      @include PZARC_PLUGIN_PATH . '/extensions/architect-pro.php';
      pzdb('after architect pro');

      // Extensions hook in here
      do_action('arc_load_extensions');


    }

    function __destruct()
    {
      pzdb('the end');
    }

    public function init()
    {

    }


    public function register_architect_block()
    {

      if (class_exists('HeadwayDisplay')) {
        require('application/public/php/headway/arc-headway-block-display.php');
        require('application/admin/php/headway/arc-headway-block-options.php');

        return headway_register_block('HeadwayArchitectBlock', PZARC_PLUGIN_APP_URL . '/admin/php/headway');
      }
    }

    /**
     * Fired when the plugin is activated.
     *
     * @param  boolean $network_wide True if WPMU superadmin uses "Network Activate" action, false if WPMU is disabled or plugin is activated on an individual blog
     */
    public function activate($network_wide)
    {
      /** Check for correct version of Pizazz Libs  */
      if (defined('PIZAZZ_VERSION')) {
        if (version_compare(PIZAZZ_VERSION, '1.6.3', '<')) {
          die(__('Cannot activate Architect because an out of date version of PizazzWP Libs is active. It needs to be at least version 1.6.3. Deactivate or upgrade it, and try again. Here is a manual link if you need it: <a href="https://s3.amazonaws.com/341public/LATEST/pizazzwp-libs.zip">Latest Pizazz Libs</a>', 'pzarchitect'));

          return;
        }

      }

      TGM_Plugin_Activation::get_instance()->update_dismiss();


      // This doesn't seem to work properly when upgrading, so might pull it for now, since it's probably better to use what is already there
//      /** Build CSS cache */
//      $pzarc_cssblueprint_cache = maybe_unserialize(get_option('pzarc_css'));
//
//      if (!$pzarc_cssblueprint_cache) {
//        add_option('pzarc_css', maybe_serialize(array('blueprints' => array(), 'panels' => array())), null, 'no');
//      }
//      require_once(PZARC_PLUGIN_APP_PATH . '/admin/php/arc-save-process.php');
//
//      save_arc_layouts('all', null, true);
    }

    public function admin_initialize()
    {
    }

// end activate

    /**
     * Fired when the plugin is deactivated.
     *
     * @param  boolean $network_wide True if WPMU superadmin uses "Network Activate" action, false if WPMU is disabled or plugin is activated on an individual blog
     */
    public function deactivate($network_wide)
    {
      // TODO:	Define deactivation functionality here
    }

// end deactivate

    /**
     * Fired when the plugin is uninstalled.
     *
     * @param  boolean $network_wide True if WPMU superadmin uses "Network Activate" action, false if WPMU is disabled or plugin is activated on an individual blog
     */
    public function uninstall($network_wide)
    {
      // TODO:	Define uninstall functionality here
    }

// end uninstall

    /**
     * Loads the plugin text domain for translation
     */
    public function pzarc_text_domain()
    {

      $domain = PZARC_NAME;
      $locale = apply_filters('plugin_locale', get_locale(), $domain);
      load_textdomain($domain, WP_LANG_DIR . '/' . $domain . '/' . $domain . '-' . $locale . '.mo');
      load_plugin_textdomain($domain, false, dirname(plugin_basename(__FILE__)) . '/languages/');
    }

// end plugin_textdomain

    /**
     * Registers and enqueues admin-specific styles.
     */
    public function register_admin_styles()
    {

      wp_enqueue_style('pzarc-admin-styles', PZARC_PLUGIN_APP_URL . '/admin/css/arc-admin.css');
      wp_register_style('pzarc-jqueryui-css', PZARC_PLUGIN_APP_URL . '/shared/thirdparty/jquery-ui-1.10.2.custom/css/pz_architect/jquery-ui-1.10.2.custom.min.css');

    }

// end register_admin_styles

    /**
     * Registers and enqueues admin-specific JavaScript.
     */
    public function register_admin_scripts()
    {

    }

// end register_admin_scripts

    /**
     * Registers and enqueues plugin-specific styles.
     */
    public function register_plugin_styles()
    {

      wp_register_style(PZARC_NAME . '-plugin-styles', PZARC_PLUGIN_APP_URL . '/public/css/arc-front.css');
      // Need this for custom CSS in styling options
      if (file_exists(PZARC_CACHE_PATH . 'arc-dynamic-styles.css')) {
        wp_register_style(PZARC_NAME . '-dynamic-styles', PZARC_CACHE_URL . 'arc-dynamic-styles.css');
      }
    }

// end register_plugin_styles

    /**
     * Registers and enqueues plugin-specific scripts.
     */
    public function register_plugin_scripts()
    {

      wp_enqueue_script('jquery');
      wp_register_script('js-isotope-v2', PZARC_PLUGIN_APP_URL . '/public/js/isotope.pkgd.min.js', array('jquery'), 2, true);
      wp_register_script('js-imagesloaded', PZARC_PLUGIN_APP_URL . '/public/js/imagesloaded.pkgd.min.js', array('jquery'), 2, true);
      wp_register_script('js-front-isotope', PZARC_PLUGIN_APP_URL . '/public/js/arc-front-isotope.js', array('jquery'), 2, true);


    }

// end register_plugin_scripts


  }

// end class

  $pzarc = new pzArchitect();


  function pzdb($pre = null, $var = 'dorkus')
  {
    if (PZDEBUG) {
      static $oldtime;
      $oldtime = empty($oldtime) ? microtime(true) : $oldtime;
      $btr     = debug_backtrace();
      $line    = $btr[ 0 ][ 'line' ];
      $file    = basename($btr[ 0 ][ 'file' ]);
      global $pzstart_time;
      var_dump(strtoupper($pre) . ': ' . $file . ':' . $line . ': ' . round((microtime(true) - $pzstart_time), 5) . 's. Time since last: ' . round(microtime(true) - $oldtime, 5) . 's');
      $oldtime = microtime(true);
      if ($var !== 'dorkus') {
        var_dump($var);
      }
    }
  }

  pzdb('bottom');
