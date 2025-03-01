<?php
/*
Plugin Name: Padma Architect Support
Plugin URI: https://www.padmaunlimited.com/plugins/#
Description: Architect compatibility plugin
Version: 0.0.1
Author: Padma Unlimited team
Author URI: https://www.padmaunlimited.com
License: GNU GPL v2
*/

/**
 *
 * Register block
 *
 */

add_action( 'after_setup_theme', function() {

    if ( !class_exists( 'Padma' ) )
		return;

	if ( !class_exists( 'PadmaBlockAPI' ) )
		return;
	
	if ( !class_exists( 'PadmaDisplay' ) )
		return;

	if ( !class_exists( 'pzArchitect' ) )
		return;

	/**
	 *
	 * Register elements as blocks
	 *
	 */
	
	$block_type_url = substr(WP_PLUGIN_URL . '/' . str_replace(basename(__FILE__), '', plugin_basename(__FILE__)), 0, -1) . '/admin';
	$class_file = __DIR__ . '/public/arc-padma-block-display.php';
	$icons = __DIR__ . '/admin';

	padma_register_block(
		'PadmaArchitectBlock',
		$block_type_url,
		$class_file,
		$icons
	);

	include_once __DIR__ . '/admin/arc-padma-block-options.php';	
	

	/**
	 *
	 * Check if there is the Padma Loader
	 *
	 */				
	if ( version_compare(PADMA_VERSION, '1.1.70', '<=') ){			
		include_once $class_file;
	}

});