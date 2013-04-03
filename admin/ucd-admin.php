<?php

class pzucdAdmin
{

	function __construct()
	{
		/*
		 * Create the layouts custom post type 
		 */


		if ( is_admin() )
		{
			//	add_action('admin_init', 'pzucd_preview_meta');
			add_action( 'admin_head', array( $this, 'admin_head' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue' ) );

			require_once PZUCD_PLUGIN_PATH . '/includes/classForm.php';
			require_once PZUCD_PLUGIN_PATH . '/admin/ucd-cell-layouts.php';

			self::do_it();
			//add_action( 'pzucd_do_it', array( $this, 'do_it' ) );
		}
	}

	function admin_enqueue( $hook )
	{
		$screen = get_current_screen();
		if ( 'ucd-layouts' == $screen->id )
		{
			
		}
	}

	function admin_head()
	{
		
	}

	function do_it()
	{
		$cell_layout = new pzucd_Cell_Layouts;
	}

	// add_action('admin_head','pzucd_layouts_add_help_tab');
	// function pzucd_layouts_add_help_tab () {
	//     $screen = get_current_screen();
	// 		$prefix = 'pzucd_';
	// 		switch ($screen->id) {
	// 			case 'edit-ucd-layouts':
	// 				$screen->add_help_tab( array(
	// 						'id'	=> $prefix.'view_help_about',
	// 						'title'	=> __('About ContentPlus Layouts'),
	// 						'content'	=> '<h3>About</h3><p>' . __( 'ContentPlus layouts are used in the ContentPlus Block' ) . '</p>'
	// 				) );
	// 				$screen->add_help_tab( array(
	//             'title' => __('Support','pzucd'),
	//             'id' => $prefix . 'view_help_support',
	//             'content' => '<h3>Support</h3><p>'.__('Headway users can get support for ContentPlus on the <a href="http://support.headwaythemes.com/" target=_blank>Headway forums</a>').'</p>'
	//         )	);
	// 				break;
	// 			default:
	// 				return;
	// 				break;
	// 		}
	// }
	// Make this only load once - probably loads all the time at the moment
}

// end pzucdAdmin

new pzucdAdmin();

class pzucdPreview
{
	
}

