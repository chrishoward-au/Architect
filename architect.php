<?php

/*
  Plugin Name: Architect - an all-in-one content display framework
  Plugin URI: http://pizazzwp.com
  Description: Display your content in grids, tabs, sliders, galleries with sources like posts, pages, galleries, widgets, custom code, Headway blocks and custom content types
  Version: 0.4.7 beta
  Author: Chris Howard
  Author URI: http://pizazzwp.com
  License: GNU GPL v2
  Shoutouts: Plugin structure based on WP Plugin Boilerplate by Tom McPharlin http://tommcfarlin.com/
 */

// What's the essential difference between E+. G+, S+ and T+? Their navigation. E+ has pagination, G+ thumbs, S+ tabs, T+ tabs.

/* BLUEPRINTS: Overall layouts bricks
 * VIEWS: End user view Nav brick(s)+Blueprint+Cell Brick
 * CRITERIA:
 * CELL BRICKS
 * NAV BRICKS
 */



/* why not use a WP like methodology!
================================================================================
register_cell_layout('name',$args)'
register_criteria('name',$args);
register_blueprint_layout('name',$args);
================================================================================
*/


class pz_Architect
{

	function __construct()
	{

		define( 'PZARC_VERSION', '0.4.7' );
		define( 'PZARC_NAME', 'pzarc' );
		define( 'PZARC_FOLDER', '/pizazzwp-architect' );

		define( 'PZARC_PLUGIN_URL', trailingslashit(plugin_dir_url( __FILE__ ) ));
		define( 'PZARC_PLUGIN_PATH', trailingslashit(plugin_dir_path( __FILE__ )) );
		define( 'PZARC_CACHE', '/pzarc/' );

		$upload_dir = wp_upload_dir();
		define( 'PZARC_CACHE_URL', trailingslashit($upload_dir[ 'baseurl' ] . '/uploads/cache/pizazzwp/pzarc' ));
		define( 'PZARC_CACHE_PATH', trailingslashit($upload_dir[ 'basedir' ] . '/cache/pizazzwp/pzarc' ));
		define( 'PZARC_DEBUG', 0 );

		// Load plugin text domain
		add_action( 'init', array( $this, 'plugin_textdomain' ) );

		if ( !function_exists( 'pizazzwp_head' ) )
		{
//			include_once PZARC_PLUGIN_PATH . '/libs/PizazzWP.php';
		}
		require_once PZARC_PLUGIN_PATH . 'includes/arc-functions.php';

		// Register admin styles and scripts

		if ( is_admin() )
		{
			require_once PZARC_PLUGIN_PATH . '/admin/arc-admin.php';
			//	require_once PZARC_PLUGIN_PATH . '/external/Custom-Metaboxes-and-Fields/example-functions.php';
			//require_once(PZARC_PLUGIN_PATH .'/admin/admin-page-class/admin-page-class.php');
			add_action( 'admin_print_styles', array( $this, 'register_admin_styles' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'register_admin_scripts' ) );
			//		add_action( 'init', array( $this, 'admin_initialize' ) );
		}


		// Front end libs, Register site styles and scripts
		if ( !is_admin() )
		{
			add_action( 'wp_enqueue_scripts', array( $this, 'register_plugin_styles' ) );
			add_action( 'wp_enqueue_scripts', array( $this, 'register_plugin_scripts' ) );

			require_once PZARC_PLUGIN_PATH . '/frontend/arc-display.php';

		}


		// Register hooks that are fired when the plugin is activated, deactivated, and uninstalled, respectively.
		register_activation_hook( __FILE__, array( $this, 'activate' ) );
		register_deactivation_hook( __FILE__, array( $this, 'deactivate' ) );
		//	register_uninstall_hook( __FILE__, array( $this, 'uninstall' ) );

		add_action( 'after_setup_theme', array( $this, 'register_architect_block' ) );


		/*
		 * TODO:
		 * Define the custom functionality for your plugin. The first parameter of the
		 * add_action/add_filter calls are the hooks into which your code should fire.
		 *
		 * The second parameter is the function name located within this class. See the stubs
		 * later in the file.
		 *
		 * For more information:
		 * http://codex.wordpress.org/Plugin_API#Hooks.2C_Actions_and_Filters
		 */
		// add_action( 'wp_action_name', array( $this, 'my_action_method_name' ) );
		// add_filter( 'wp_filter_name', array( $this, 'my_filter_method_name' ) );
	}

// end constructor


	public function register_architect_block()
	{

		if ( class_exists( 'HeadwayDisplay' ) )
		{
			require('includes/headway/arc-block-display.php');
			require('includes/headway/arc-block-options.php');

			return headway_register_block( 'HeadwayArchitectBlock', PZARC_PLUGIN_URL . '/includes/headway' );
		}
	}

	/**
	 * Fired when the plugin is activated.
	 *
	 * @param	boolean	$network_wide	True if WPMU superadmin uses "Network Activate" action, false if WPMU is disabled or plugin is activated on an individual blog
	 */
	public function activate( $network_wide )
	{
		// TODO:	Define activation functionality here
	}

	public function admin_initialize()
	{
	//	require_once PZARC_PLUGIN_PATH . '/includes/external/Custom-Metaboxes-and-Fields/init.php';
	}

// end activate

	/**
	 * Fired when the plugin is deactivated.
	 *
	 * @param	boolean	$network_wide	True if WPMU superadmin uses "Network Activate" action, false if WPMU is disabled or plugin is activated on an individual blog
	 */
	public function deactivate( $network_wide )
	{
		// TODO:	Define deactivation functionality here
	}

// end deactivate

	/**
	 * Fired when the plugin is uninstalled.
	 *
	 * @param	boolean	$network_wide	True if WPMU superadmin uses "Network Activate" action, false if WPMU is disabled or plugin is activated on an individual blog
	 */
	public function uninstall( $network_wide )
	{
		// TODO:	Define uninstall functionality here
	}

// end uninstall

	/**
	 * Loads the plugin text domain for translation
	 */
	public function plugin_textdomain()
	{

		// TODO: replace "plugin-name-locale" with a unique value for your plugin
		$domain	 = PZARC_NAME;
		$locale	 = apply_filters( 'plugin_locale', get_locale(), $domain );
		load_textdomain( $domain, WP_LANG_DIR . '/' . $domain . '/' . $domain . '-' . $locale . '.mo' );
		load_plugin_textdomain( $domain, FALSE, dirname( plugin_basename( __FILE__ ) ) . '/lang/' );
	}

// end plugin_textdomain

	/**
	 * Registers and enqueues admin-specific styles.
	 */
	public function register_admin_styles()
	{

		wp_enqueue_style( PZARC_NAME . '-admin-styles', plugins_url( PZARC_FOLDER . '/admin/css/arc-admin.css' ) );
		wp_enqueue_style( PZARC_NAME . '-font-awesome', plugins_url( PZARC_FOLDER . '/external/font-awesome/css/font-awesome.min.css' ) );

		// Be nice to use bootstrap, but it's just not compatible with WP as it uses common non-specific element names.
		//wp_enqueue_style( 'bootstrap-admin-styles', plugins_url( PZARC_FOLDER . '/external/bootstrap/css/bootstrap.min.css' ) );
	}

// end register_admin_styles

	/**
	 * Registers and enqueues admin-specific JavaScript.
	 */
	public function register_admin_scripts()
	{

		wp_enqueue_script( 'jquery' );

		// wp_enqueue_script( PZARC_NAME.'-admin-script', plugins_url( PZARC_FOLDER.'/admin/js/admin.js' ) );
		//wp_enqueue_script(PZARC_NAME . '-metaboxes-script', plugins_url(PZARC_FOLDER . '/admin/js/arc-metaboxes.js'));
	}

// end register_admin_scripts

	/**
	 * Registers and enqueues plugin-specific styles.
	 */
	public function register_plugin_styles()
	{

		wp_enqueue_style( PZARC_NAME . '-plugin-styles', plugins_url( PZARC_FOLDER . '/frontend/css/arc-front.css' ) );
	}

// end register_plugin_styles

	/**
	 * Registers and enqueues plugin-specific scripts.
	 */
	public function register_plugin_scripts()
	{

		wp_enqueue_script( 'jquery' );
		// wp_enqueue_script( PZARC_NAME.'-plugin-script', plugins_url( PZARC_FOLDER.'/frontend/js/display.js' ) );
    wp_register_script( 'jquery-isotope', plugins_url( PZARC_FOLDER . '/external/js/jquery.isotope.min.js' ) );
    wp_register_script( 'js-isotope-v2', plugins_url( PZARC_FOLDER . '/external/js/isotope.pkgd.min.js' ) );
	}

// end register_plugin_scripts


	/* --------------------------------------------*
	 * Core Functions
	 * --------------------------------------------- */

	/**
	 * NOTE:  Actions are points in the execution of a page or process
	 *        lifecycle that WordPress fires.
	 *
	 * 	  WordPress Actions: http://codex.wordpress.org/Plugin_API#Actions
	 * 	  Action Reference:  http://codex.wordpress.org/Plugin_API/Action_Reference
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
	 * 	  WordPress Filters: http://codex.wordpress.org/Plugin_API#Filters
	 * 	  Filter Reference:  http://codex.wordpress.org/Plugin_API/Filter_Reference
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
$pzarc = new Ultimate_Content_Display();






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
if (is_admin())
{
  add_action('admin_init', 'pzarc_initiate_updater');

  function pzarc_initiate_updater() {
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
      $ep_update = new WPUpdatesPluginUpdater_259( 'http://wp-updates.com/api/2/plugin', plugin_basename(__FILE__));
//    }
  }

}


