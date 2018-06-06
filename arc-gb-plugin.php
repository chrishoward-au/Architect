<?php
/**
 * Plugin Name: Architect Gutenberg block
 * Description: pizazzwp-arc-guten — is a Gutenberg plugin created via create-guten-block.
 * Author: chrishoward, mrahmadawais, maedahbatool
 * Version: 1.0.0
 * License: GPL2+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 *
 * @package CGB
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Block Initializer.
 */
require_once plugin_dir_path( __FILE__ ) . 'gutenberg/architect-generic/src/init.php';
