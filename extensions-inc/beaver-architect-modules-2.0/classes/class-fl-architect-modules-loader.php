<?php
	
/**
 * A class that handles loading custom modules and custom
 * fields if the builder is installed and activated.
 */
class FL_Architect_Modules_Loader {
	
	/**
	 * Initializes the class once all plugins have loaded.
	 */
	static public function init() {
		add_action( 'plugins_loaded', __CLASS__ . '::setup_hooks' );
	}
	
	/**
	 * Setup hooks if the builder is installed and activated.
	 */
	static public function setup_hooks() {
		if ( ! class_exists( 'FLBuilder' ) ) {
			return;	
		}
		
		// Load custom modules.
		add_action( 'init', __CLASS__ . '::load_modules' );
		
		// Register custom fields.
		add_filter( 'fl_builder_custom_fields', __CLASS__ . '::register_fields' );
		
		// Enqueue custom field assets.
		add_action( 'init', __CLASS__ . '::enqueue_field_assets' );
	}
	
	/**
	 * Loads our custom modules.
	 */
	static public function load_modules() {
		require_once FL_ARCHITECT_BB2_MODULE_DIR . 'modules/architect-any-fields/architect-any-fields.php';
	//	require_once FL_ARCHITECT_BB2_MODULE_DIR . 'modules/example/example.php';
		require_once FL_ARCHITECT_BB2_MODULE_DIR . 'modules/arc-map/arc-map.php';
		require_once FL_ARCHITECT_BB2_MODULE_DIR . 'modules/arc-sidebar/arc-sidebar.php';
	}
	
	/**
	 * Registers our custom fields.
	 */
	static public function register_fields( $fields ) {
		$fields['my-custom-field'] = FL_ARCHITECT_BB2_MODULE_DIR . 'fields/my-custom-field.php';
		return $fields;
	}
	
	/**
	 * Enqueues our custom field assets only if the builder UI is active.
	 */
	static public function enqueue_field_assets() {
		if ( ! FLBuilderModel::is_builder_active() ) {
			return;
		}
		
		wp_enqueue_style( 'my-custom-fields', FL_ARCHITECT_BB2_MODULE_URL . 'assets/css/fields.css', array(), '' );
		wp_enqueue_script( 'my-custom-fields', FL_ARCHITECT_BB2_MODULE_URL . 'assets/js/fields.js', array(), '', true );
	}
}

FL_Architect_Modules_Loader::init();