<?php
/*
Plugin Name: PizazzWP Ultimate Content Display - a content framework
Plugin URI: http://pizazzwp.com
Description: Display your content in grids, tabs, sliders, galleries with sources like posts, pages, galleries, widgets, custom code, Headway blocks and custom content types
Version: 0.2
Author: Chris Howard
Author URI: http://pizazzwp.com
License: GNU GPL v2
*/

define('PZUCD_VERSION','0.2');

define('PZUCD_PLUGIN_URL', plugin_dir_url(__FILE__));
define('PZUCD_PLUGIN_PATH', plugin_dir_path(__FILE__));
define('PZUCD_CACHE', '/pzucd/');
$upload_dir = wp_upload_dir();
define('PZUCD_CACHE_URL', $upload_dir['baseurl'].'/uploads/cache/pizazzwp/pzucd');
define('PZUCD_CACHE_PATH', $upload_dir['basedir'].'/cache/pizazzwp/pzucd');
define('PZUCD_DEBUG',0);

if (!function_exists('pizazzwp_head')) {
	include_once PZUCD_PLUGIN_PATH.'/libs/PizazzWP.php';
}


// Use this for debuggging
//add_action( 'all', create_function( '', 'var_dump( current_filter() );' ));


if (is_admin()) {
	require_once PZUCD_PLUGIN_PATH.'/ucd-admin.php';
}

/* Display method Widget */
// Create an uber widget with all the layout params

/* Display method Template Tag */
// Create template tag with all the layout params

/* Display method shortcode */
// We might need a shortcode for gallery displaying


/* Display method Headway */
// Provide method to display using a Headway block
if (class_exists('HeadwayDisplay')) {
	add_action('after_setup_theme', 'register_ultimatecontentdisplay_block');
	function register_ultimatecontentdisplay_block() {
		
		require_once 'ucd-display.php';
		require_once 'ucd-block-options.php';

		return headway_register_block('HeadwayUltimateContentDisplayBlock', PZUCD_PLUGIN_URL);

	}
}
