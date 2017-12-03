<?php
/**
 * Plugin Name: Beaver Builder Custom Modules
 * Plugin URI: http://www.wpbeaverbuilder.com
 * Description: An example plugin for creating custom builder modules.
 * Version: 2.0
 * Author: The Beaver Builder Team
 * Author URI: http://www.wpbeaverbuilder.com
 */
define( 'FL_ARCHITECT_BB2_MODULE_DIR', plugin_dir_path( __FILE__ ) );
define( 'FL_ARCHITECT_BB2_MODULE_URL', plugins_url( '/', __FILE__ ) );

require_once FL_ARCHITECT_BB2_MODULE_DIR . 'classes/class-fl-architect-modules-loader.php';