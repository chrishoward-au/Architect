<?php
/**
 * Beaver Builder Architect Modules
 *  http://www.wpbeaverbuilder.com
 * Description: An architect plugin for creating custom builder modules.
 * Version: 1.0
 * Author: The Beaver Builder Team
 * Author URI: http://www.wpbeaverbuilder.com
 */
define( 'FL_MODULE_ARCHITECT_DIR', plugin_dir_path( __FILE__ ) );
define( 'FL_MODULE_ARCHITECT_URL', plugins_url( '/', __FILE__ ) );

/**
 * Custom modules
 */
function fl_load_module_architects() {
	if ( class_exists( 'FLBuilder' ) ) {

	  // Load custom fields
    require_once 'fields/pz-box-styling.php';
	    require_once 'basic-architect/basic-architect-module.php';
	    require_once 'includes/functions.php';
	    require_once 'includes/function-generate-beaver-css.php';
	    require_once 'includes/form-styles-editor.php';
//	    require_once 'includes/form-styles-blueprints.php'; //no longer used
	   // require_once 'architect/architect-module.php'; //This will be the full version - somehow!
	   // require_once 'example/example.php'; // Custom fields
	}
}
add_action( 'init', 'fl_load_module_architects' );

/**
 * Custom fields
 */
function fl_my_custom_field( $name, $value, $field ) {
    echo '<input type="text" class="text text-full" name="' . $name . '" value="' . $value . '" />';
}
add_action( 'fl_builder_control_my-custom-field', 'fl_my_custom_field', 1, 3 );

/**
 * Custom field styles and scripts
 */
function fl_my_custom_field_assets() {
    if ( class_exists( 'FLBuilderModel' ) && FLBuilderModel::is_builder_active() ) {
        wp_enqueue_style( 'my-custom-fields', FL_MODULE_ARCHITECT_URL . 'assets/css/fields.css', array(), '' );
        wp_enqueue_script( 'my-custom-fields', FL_MODULE_ARCHITECT_URL . 'assets/js/fields.js', array(), '', true );
    }
}
add_action( 'wp_enqueue_scripts', 'fl_my_custom_field_assets' );