<?php

  /*
    Plugin Name: Architect
    Plugin URI: http://architect4wp.com
    Description: Architect is an all-in-one content layout framework to extend your theme. Go beyond the limitations of the theme you use to easily build any content layouts for it. Build your own grids, tabs, sliders, galleries and more with sources such ass posts, pages, galleries, and custom content types. Display using shorcodes, widgets, Headway blocks, WP action hooks and template tags, and WP Gallery shortcode. Change themes without needing to rebuild your layouts! The Lite version provides only content types of Posts and Defaults.
    Version: 0.9.1
    Author: Chris Howard
    Author URI: http://pizazzwp.com
    License: GNU GPL v2
    General Support: support@pizazzwp.com
    Beta support: http://discourse.pizazzwp.com
   */


  define('PZDEBUG', false);

  class pzArchitect
  {

    function __construct()
    {

      define('PZARC_VERSION', '0.9.1');
      define('PZARC_NAME', 'pzarchitect'); // This is also same as the locale
      define('PZARC_FOLDER', '/pizazzwp-architect');
      define('PZARC_CODEX','http://architect4wp.com/codex');

      define('PZARC_PLUGIN_URL', trailingslashit(plugin_dir_url(__FILE__)));
      define('PZARC_PLUGIN_PATH', trailingslashit(plugin_dir_path(__FILE__)));
      define('PZARC_PLUGIN_APP_URL', PZARC_PLUGIN_URL . 'application/');
      define('PZARC_PLUGIN_APP_PATH', PZARC_PLUGIN_PATH . 'application/');
      define('PZARC_DOCUMENTATION_URL', PZARC_PLUGIN_URL . 'documentation/');
      define('PZARC_DOCUMENTATION_PATH', PZARC_PLUGIN_PATH . 'documentation/');
      define('PZARC_PLUGIN_ASSETS_URL', PZARC_PLUGIN_APP_URL . 'shared/assets/');
      define('PZARC_CACHE', '/arc/');

      define('PZARC_TRANSIENTS_KEEP', 12 * HOUR_IN_SECONDS);
      $upload_dir = wp_upload_dir();
      // TODO: why isn't this using myfiles folder?
      define('PZARC_CACHE_URL', trailingslashit($upload_dir[ 'baseurl' ] . '/cache/pizazzwp/arc'));
      define('PZARC_CACHE_PATH', trailingslashit($upload_dir[ 'basedir' ] . '/cache/pizazzwp/arc'));

      // Before we go anywhere, make sure dependent plugins are loaded and active.
      require_once PZARC_PLUGIN_APP_PATH . '/shared/architect/php/arc-check-dependencies.php';
      // Need this one to create the Architect widget
      require_once PZARC_PLUGIN_APP_PATH . '/shared/architect/php/class_arc_Widget.php';

      wp_mkdir_p(PZARC_CACHE_PATH);

      // Load plugin text domain
      add_action('init', array($this, 'pzarc_text_domain'));

      if (!function_exists('pizazzwp_head')) {
        // The TGM dependency loader needs to run first
//			include_once PZARC_PLUGIN_PATH . '/includes/PizazzWP.php';
      }
      require_once PZARC_PLUGIN_APP_PATH . '/shared/architect/php/arc-functions.php';

      // Register admin styles and scripts

      if (is_admin()) {
        add_action('admin_print_styles', array($this, 'register_admin_styles'));
        add_action('admin_enqueue_scripts', array($this, 'register_admin_scripts'));
        //		add_action( 'init', array( $this, 'admin_initialize' ) );
        require_once PZARC_PLUGIN_APP_PATH . '/arc-admin.php';

      } else {
        // Front end includes, Register site styles and scripts
        add_action('wp_enqueue_scripts', array($this, 'register_plugin_styles'));
        add_action('wp_enqueue_scripts', array($this, 'register_plugin_scripts'));

        require_once PZARC_PLUGIN_APP_PATH . '/arc-public.php';

      }

      // Register hooks that are fired when the plugin is activated, deactivated, and uninstalled, respectively.
      register_activation_hook(__FILE__, array($this, 'activate'));
      register_deactivation_hook(__FILE__, array($this, 'deactivate'));
      //	register_uninstall_hook( __FILE__, array( $this, 'uninstall' ) );

      add_action('init', array($this, 'init'));
      add_action('after_setup_theme', array($this, 'register_architect_block'));


      require_once PZARC_PLUGIN_APP_PATH . '/shared/architect/php/class_arc_Registry.php';

      require_once PZARC_PLUGIN_APP_PATH . '/shared/architect/php/class_arc_set_data.php';
      require_once PZARC_PLUGIN_APP_PATH . '/shared/architect/php/class_arc_Blueprint_Data.php';

      // Load custom custom types
      require_once PZARC_PLUGIN_APP_PATH . '/shared/architect/php/arc-cpt-panels.php';
      require_once PZARC_PLUGIN_APP_PATH . '/shared/architect/php/arc-cpt-blueprints.php';

      // Load all the builtin post types
      require_once PZARC_PLUGIN_APP_PATH . '/shared/architect/php/content-types/defaults/class_arc_content_defaults.php';
      require_once PZARC_PLUGIN_APP_PATH . '/shared/architect/php/content-types/post/class_arc_content_posts.php';

      // This is a shorthand way of doing an if. When pro isn't present, it's the lite version.
      @include PZARC_PLUGIN_PATH .'/extensions/architect-pro.php';

      // Extensions hook in here
      do_action('arc_load_extensions');

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
      // TODO:	Define activation functionality here
      TGM_Plugin_Activation::get_instance()->update_dismiss();

      // TODO: Inisitalize and save all default options
      // TODO: Inisitalize and save all default options
      // TODO: Inisitalize and save all default options
      // TODO: Inisitalize and save all default options
      // TODO: Inisitalize and save all default options


      /** Build CSS cache */

      $pzarc_cssblueprint_cache = maybe_unserialize(get_option('pzarc_css'));

      if (!$pzarc_cssblueprint_cache) {
        add_option('pzarc_css', maybe_serialize(array('blueprints' => array(), 'panels' => array())), null, 'no');
      }
      require_once(PZARC_PLUGIN_APP_PATH . '/admin/php/arc-save-process.php');
      save_arc_layouts('all', null, true);
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

      // TODO: replace "plugin-name-locale" with a unique value for your plugin
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
      //  wp_register_style('pzarc-font-awesome', PZARC_PLUGIN_APP_URL . '/shared/includes/font-awesome/css/font-awesome.min.css');
      wp_register_style('pzarc-jqueryui-css', PZARC_PLUGIN_APP_URL . '/shared/includes/jquery-ui-1.10.2.custom/css/pz_architect/jquery-ui-1.10.2.custom.min.css');

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
      // wp_enqueue_script( PZARC_NAME.'-plugin-script', plugins_url( PZARC_FOLDER.'/frontend/js/display.js' ) );
      //wp_register_script('jquery-isotope', plugins_url(PZARC_FOLDER . '/public/js/jquery.isotope.min.js'));
      wp_register_script('js-isotope-v2', PZARC_PLUGIN_APP_URL . '/public/js/isotope.pkgd.min.js');

      // TODO: bug in this, so removed for now
//      wp_enqueue_script('js-useragent', plugins_url(PZARC_FOLDER) . '/shared/architect/js/architect.js');
    }

// end register_plugin_scripts


    /* --------------------------------------------*
     * Core Functions
     * --------------------------------------------- */

    /**
     * NOTE:  Actions are points in the execution of a page or process
     *        lifecycle that WordPress fires.
     *
     *    WordPress Actions: http://codex.wordpress.org/Plugin_API#Actions
     *    Action Reference:  http://codex.wordpress.org/Plugin_API/Action_Reference
     *
     */
    function action_method_name()
    {
      // TODO:	Define your action method here
    }

// end action_method_name

    /**
     * NOTE:  Filters are points of execution in which WordPress modifies data
     *        before saving it or sending it to the browser.
     *
     *    WordPress Filters: http://codex.wordpress.org/Plugin_API#Filters
     *    Filter Reference:  http://codex.wordpress.org/Plugin_API/Filter_Reference
     *
     */
    function filter_method_name()
    {
      // TODO:	Define your filter method here
    }

// end filter_method_name
  }

// end class
// TODO:	Update the instantiation call of your plugin to the name given at the class definition

  $pzarc = new pzArchitect();


// Use this for debuggging
//add_action( 'all', create_function( '', 'var_dump( current_filter() );' ));


  /* Display method Widget */
// Create an uber widget with all the layout params

  /* Display method Blueprint Tag */
// Create blueprint tag with all the layout params

  /* Display method shortcode */
// We might need a shortcode for gallery displaying


  /* Display method Headway */
// Provide method to display using a Headway block
  if (is_admin()) {
    add_action('admin_init', 'pzarc_initiate_updater');

    function pzarc_initiate_updater()
    {
//    $opt_val = get_option('pizazz_options');
//    if (class_exists('HeadwayUpdaterAPI') && empty($opt_val['val_update_method']))
//    {
//
//      $updater = new HeadwayUpdaterAPI(array(
//                                            'slug'						 => 'excerptsplus',
//                                            'path'						 => plugin_basename(__FILE__),
//                                            'name'						 => 'ExcerptsPlus',
//                                            'type'						 => 'block',
//                                            'current_version'	 => EPVERSION
//                                       ));
//    }
//    else
//    {
      require_once('wp-updates-plugin.php');
      new WPUpdatesPluginUpdater_625('http://wp-updates.com/api/2/plugin', plugin_basename(__FILE__)); //    }
    }

    // TODO: check if older Redux is installed and use ours instead (if possible), but give warning too.

    add_action('plugins_loaded', 'pzarc_check_redux');

    function pzarc_check_redux()
    {
      if (!is_admin()) {
        return;
      }
      if (class_exists('ReduxFramework') || class_exists('ReduxFrameworkPlugin')) {
        // do a version check somehow... might need to hard code redux version using a constant
        //    echo '<div id="message" class="updated"><p>The plugin or theme at address: <strong>',ReduxFramework::$_url,'</strong> has loaded an old and probably incompatible version (<strong>',ReduxFramework::$_version,'</strong>) of the Redux library that Architect is dependent upon.<br>Please ask the developer of the other plugin/theme to upgrade their version of Redux.</p></div>';
      }
    }
  }

  /** Special notices */
  /* Display a notice that can be dismissed */

  add_action('admin_notices', 'pzarc_admin_notice');
  function pzarc_admin_notice()
  {
    if (current_user_can('install_plugins')) {

      global $current_user;
      $user_id = $current_user->ID;
      /* Check that the user hasn't already clicked to ignore the message */
      if (!get_user_meta($user_id, 'pzarc_ignore_notice_v091')) {
        echo '<div class="message error highlight"><p>';
        printf(__('<h3>Architect beta 0.9.1</h3><p><strong>Architect v0.9.1 changes significantly how the navigator is set.</strong> You will need to recreate any Architect blueprints using navigation. To do so:</p>


 <p><a href="http://discourse.pizazzwp.com/t/architect-beta-v0-9-1" target="_blank">Full change log</a> | <a href="http://discourse.pizazzwp.com" target="_blank">Support</a> |<a href="%1$s">Hide Notice</a>','pzarchitect'), '?pzarc_nag_ignore_v091=0');

        echo "</p>
</div>";
        echo '<div class="message updated highlight"><p>';
        printf(__('<h3>Architect beta 0.9.0</h3><p><strong>Architect v0.9.0 changes how CSS is created.</strong> You will need to recreate Architect CSS. To do so:</p>
<ol><li>Go to Architect > Styling Defaults and click <em>Reset All</em>. Apologies if you are using the Defaults options and need to re-enter them.</li>
<li>Go to Architect > Tools and click <em>Rebuild Architect CSS Cache</em>.</li>
 <li>If your site has a a caching plugin or service, you will need to clear that as well</li></ol>
 <p>If your Panels or Blueprints still look scrambled on the front end, go to their list in admin, select them all, select Edit from the dropdown and click Apply.</p>
<h4>New features in 0.9.0</h4>
<ul>
<li>&bull; Tabular layout. Select in Blueprints > Blueprint Layout > Layout mode. Many thanks to Matt Davis for this idea.</li>
<li>&bull; Accordion layout. Select in Blueprints > Blueprint Layout > Layout mode.</li>
<li>&bull; Navigation type Labels. Let you set specific labels for navigation items.</li>
<li>&bull; In Meta fields, Accordion titles and Navigation Labels, you can now include shortcodes. This is the best and most secure way to include and execute custom code.</li>
</ul> ','pzarchitect'));

        echo "</p>
</div>";
      }
    }
  }

  add_action('admin_init', 'pzarc_nag_ignore');

  function pzarc_nag_ignore()
  {
    global $current_user;
    $user_id = $current_user->ID;
    /* If user clicks to ignore the notice, add that to their user meta */
    if (isset($_GET[ 'pzarc_nag_ignore_v091' ]) && '0' == $_GET[ 'pzarc_nag_ignore_v091' ]) {
      add_user_meta($user_id, 'pzarc_ignore_notice_v091', 'true', true);
    }
  }


  if (PZDEBUG) {
    global $pzstart_time;
    $pzstart_time = microtime(true);
    pzdb('start');
  }

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

//  if ( ! function_exists('pzfaqs') ) {
//
//// Register Custom Post Type
//    function pzfaqs() {
//
//      $labels = array(
//          'name'                => _x( 'FAQs', 'Post Type General Name', 'pzarchitect' ),
//          'singular_name'       => _x( 'FAQ', 'Post Type Singular Name', 'pzarchitect' ),
//          'menu_name'           => __( 'FAQs', 'pzarchitect' ),
//          'parent_item_colon'   => __( 'Parent FAQ:', 'pzarchitect' ),
//          'all_items'           => __( 'All FAQs', 'pzarchitect' ),
//          'view_item'           => __( 'View FAQQ', 'pzarchitect' ),
//          'add_new_item'        => __( 'Add New FAQ', 'pzarchitect' ),
//          'add_new'             => __( 'Add New FAQ', 'pzarchitect' ),
//          'edit_item'           => __( 'Edit FAQ', 'pzarchitect' ),
//          'update_item'         => __( 'Update FAQ', 'pzarchitect' ),
//          'search_items'        => __( 'Search FAQ', 'pzarchitect' ),
//          'not_found'           => __( 'Not found', 'pzarchitect' ),
//          'not_found_in_trash'  => __( 'Not found in Trash', 'pzarchitect' ),
//      );
//      $args = array(
//          'label'               => __( 'pzfaqs', 'pzarchitect' ),
//          'description'         => __( 'Frequently Asked Questions', 'pzarchitect' ),
//          'labels'              => $labels,
//          'supports'            => array( 'title', 'editor', 'excerpt', 'author', 'thumbnail', 'comments', 'revisions', 'custom-fields', 'page-attributes', 'post-formats', ),
//          'taxonomies'          => array( 'category', 'post_tag' ),
//          'hierarchical'        => true,
//          'public'              => true,
//          'show_ui'             => true,
//          'show_in_menu'        => true,
//          'show_in_nav_menus'   => true,
//          'show_in_admin_bar'   => true,
//          'menu_position'       => 25,
//          'menu_icon'           => PZARC_PLUGIN_ASSETS_URL.'images/faqs-icon.png',
//          'can_export'          => true,
//          'has_archive'         => true,
//          'exclude_from_search' => false,
//          'publicly_queryable'  => true,
//          'capability_type'     => 'page',
//      );
//      register_post_type( 'pzfaqs', $args );
//
//    }
//
//// Hook into the 'init' action
//    add_action( 'init', 'pzfaqs', 0 );
//
//  }