<?php

/*
  Plugin Name: PizazzWP Ultimate Content Display - a content display framework
  Plugin URI: http://pizazzwp.com
  Description: Display your content in grids, tabs, sliders, galleries with sources like posts, pages, galleries, widgets, custom code, Headway blocks and custom content types
  Version: 0.2
  Author: Chris Howard
  Author URI: http://pizazzwp.com
  License: GNU GPL v2
  Shoutouts: Plugin structure based on WP Plugin Boilerplate by Tom McPharlin http://tommcfarlin.com/
 */

// What's the essential difference between E+. G+, S+ and T+? Their navigation. E+ has pagination, G+ thumbs, S+ tabs, T+ tabs.

/* TEMPLATES: Overall layouts bricks
 * VIEWS: End user view Nav brick(s)+Template+Cell Brick
 * CRITERIA:
 * CELL BRICKS
 * NAV BRICKS
 */

class Ultimate_Content_Display
{

	function __construct()
	{

		define( 'PZUCD_VERSION', '0.2' );
		define( 'PZUCD_NAME', 'pzucd' );
		define( 'PZUCD_FOLDER', '/pizazzwp-ultimatecontentdisplay' );

		define( 'PZUCD_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
		define( 'PZUCD_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
		define( 'PZUCD_CACHE', '/pzucd/' );

		$upload_dir = wp_upload_dir();
		define( 'PZUCD_CACHE_URL', $upload_dir[ 'baseurl' ] . '/uploads/cache/pizazzwp/pzucd' );
		define( 'PZUCD_CACHE_PATH', $upload_dir[ 'basedir' ] . '/cache/pizazzwp/pzucd' );
		define( 'PZUCD_DEBUG', 0 );

		// Load plugin text domain
		add_action( 'init', array( $this, 'plugin_textdomain' ) );

		if ( !function_exists( 'pizazzwp_head' ) )
		{
//			include_once PZUCD_PLUGIN_PATH . '/libs/PizazzWP.php';
		}
		require_once PZUCD_PLUGIN_PATH . '/includes/ucd-functions.php';

		// Register admin styles and scripts

		if ( is_admin() )
		{
			require_once PZUCD_PLUGIN_PATH . '/includes/admin/ucd-admin.php';
			//	require_once PZUCD_PLUGIN_PATH . '/external/Custom-Metaboxes-and-Fields/example-functions.php';
			//require_once(PZUCD_PLUGIN_PATH .'/admin/admin-page-class/admin-page-class.php');
			add_action( 'admin_print_styles', array( $this, 'register_admin_styles' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'register_admin_scripts' ) );
			//		add_action( 'init', array( $this, 'admin_initialize' ) );
		}


		// Front end libs, Register site styles and scripts
		if ( !is_admin() )
		{
			add_action( 'wp_enqueue_scripts', array( $this, 'register_plugin_styles' ) );
			add_action( 'wp_enqueue_scripts', array( $this, 'register_plugin_scripts' ) );

			require_once PZUCD_PLUGIN_PATH . '/includes/frontend/ucd-display.php';

		}


		// Register hooks that are fired when the plugin is activated, deactivated, and uninstalled, respectively.
		register_activation_hook( __FILE__, array( $this, 'activate' ) );
		register_deactivation_hook( __FILE__, array( $this, 'deactivate' ) );
		//	register_uninstall_hook( __FILE__, array( $this, 'uninstall' ) );

		add_action( 'after_setup_theme', array( $this, 'register_ultimatecontentdisplay_block' ) );


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


	public function register_ultimatecontentdisplay_block()
	{

		if ( class_exists( 'HeadwayDisplay' ) )
		{
			require('includes/headway/ucd-block-display.php');
			require('includes/headway/ucd-block-options.php');
			require('includes/frontend/classUcd_Display.php');

			return headway_register_block( 'HeadwayUltimateContentDisplayBlock', PZUCD_PLUGIN_URL . '/includes/headway' );
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
		require_once PZUCD_PLUGIN_PATH . '/includes/external/Custom-Metaboxes-and-Fields/init.php';
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
		$domain	 = PZUCD_NAME;
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

		wp_enqueue_style( PZUCD_NAME . '-admin-styles', plugins_url( PZUCD_FOLDER . '/includes/admin/css/ucd-admin.css' ) );
		wp_enqueue_style( PZUCD_NAME . '-font-awesome', plugins_url( PZUCD_FOLDER . '/includes/external/font-awesome/css/font-awesome.min.css' ) );

		// Be nice to use bootstrap, but it's just not compatible with WP as it uses common non-specific element names.
		//wp_enqueue_style( 'bootstrap-admin-styles', plugins_url( PZUCD_FOLDER . '/external/bootstrap/css/bootstrap.min.css' ) );
	}

// end register_admin_styles

	/**
	 * Registers and enqueues admin-specific JavaScript.
	 */
	public function register_admin_scripts()
	{

		wp_enqueue_script( 'jquery' );

		// wp_enqueue_script( PZUCD_NAME.'-admin-script', plugins_url( PZUCD_FOLDER.'/admin/js/admin.js' ) );
		//wp_enqueue_script(PZUCD_NAME . '-metaboxes-script', plugins_url(PZUCD_FOLDER . '/admin/js/ucd-metaboxes.js'));
	}

// end register_admin_scripts

	/**
	 * Registers and enqueues plugin-specific styles.
	 */
	public function register_plugin_styles()
	{

		wp_enqueue_style( PZUCD_NAME . '-plugin-styles', plugins_url( PZUCD_FOLDER . '/includes/frontend/css/ucd-front.css' ) );
	}

// end register_plugin_styles

	/**
	 * Registers and enqueues plugin-specific scripts.
	 */
	public function register_plugin_scripts()
	{

		wp_enqueue_script( 'jquery' );
		// wp_enqueue_script( PZUCD_NAME.'-plugin-script', plugins_url( PZUCD_FOLDER.'/frontend/js/display.js' ) );
		wp_enqueue_script( PZUCD_NAME . '-isotope', plugins_url( PZUCD_FOLDER . '/includes/frontend/js/jquery.isotope.min.js' ) );
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
$pzucd = new Ultimate_Content_Display();






// Use this for debuggging
//add_action( 'all', create_function( '', 'var_dump( current_filter() );' ));



/* Display method Widget */
// Create an uber widget with all the layout params

/* Display method Template Tag */
// Create template tag with all the layout params

/* Display method shortcode */
// We might need a shortcode for gallery displaying


/* Display method Headway */
// Provide method to display using a Headway block
