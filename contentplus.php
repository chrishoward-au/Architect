<?php
/*
Plugin Name: Pizazz ContentPlus Block for Headway
Plugin URI: http://pizazzwp.com
Description: Example block for Headway 3.0.
Version: 0.1
Author: Chris Howard
Author URI: http://pizazzwp.com
License: GNU GPL v2
*/

define('CPLUS_VERSION','0.1');

define('CPLUS_PLUGIN_URL', substr(WP_PLUGIN_URL.'/'.str_replace(basename(__FILE__),'', plugin_basename(__FILE__)), 0, -1));
define('CPLUS_PLUGIN_PATH', substr(WP_PLUGIN_DIR.'/'.str_replace(basename(__FILE__),'', plugin_basename(__FILE__)), 0, -1));
define('CPLUS_CACHE', '/splus/');
define('CPLUS_CACHE_URL', WP_CONTENT_URL.'/uploads/cache/pizazzwp/cplus');
define('CPLUS_CACHE_PATH', WP_CONTENT_DIR.'/uploads/cache/pizazzwp/cplus');
define('CPLUS_DEBUG',0);

if (!function_exists('pizazzwp_head')) {
	include_once CPLUS_PLUGIN_PATH.'/libs/PizazzWP.php';
}

add_action('after_setup_theme', 'register_contentplus_block');
function register_contentplus_block() {
	
	require_once 'contentplus-display.php';
	require_once 'contentplus-block-options.php';
	require_once 'contentplus-admin.php';

	return headway_register_block('HeadwayContentPlusBlock', substr(WP_PLUGIN_URL . '/' . str_replace(basename(__FILE__), '', plugin_basename(__FILE__)), 0, -1));

}