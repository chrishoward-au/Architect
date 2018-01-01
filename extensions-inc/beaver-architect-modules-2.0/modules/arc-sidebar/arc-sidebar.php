<?php

/**
 * @class FLSidebarModule
 */
class ArcSidebarModule extends FLBuilderModule {

	/**
	 * @method __construct
	 */
	public function __construct() {
		parent::__construct(array(
			'name'          	=> __( 'Adaptive Sidebar', 'fl-builder' ),
			'description'   	=> __( 'Display a WordPress sidebar that has been registered by the current theme.', 'fl-builder' ),
			'category'      	=> __( 'Architect Modules', 'fl-builder' ),
			'editor_export' 	=> false,
			'partial_refresh'	=> true,
			'icon'				=> 'layout.svg',
		));
	}
}

/**
 * Register the module and its form settings.
 */
FLBuilder::register_module('ArcSidebarModule', array(
	'general'       => array( // Tab
		'title'         => __( 'General', 'fl-builder' ), // Tab title
		'file'          => FL_ARCHITECT_BB2_MODULE_DIR . 'modules/arc-sidebar/includes/settings-general.php',

	),
));
