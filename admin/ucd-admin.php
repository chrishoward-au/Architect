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
			add_action( 'admin_menu', array( $this, 'admin_menu' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue' ) );

			require_once PZUCD_PLUGIN_PATH . '/includes/classForm.php';
			require_once PZUCD_PLUGIN_PATH . '/admin/ucd-cell-layouts.php';

			self::do_it();
//add_action( 'pzucd_do_it', array( $this, 'do_it' ) );
		}
	}

	function admin_enqueue( $hook )
	{
		wp_enqueue_style( 'pzucd-block-css', PZUCD_PLUGIN_URL . '/admin/css/ucd-admin.css' );
		wp_enqueue_style( 'pzucd-jqueryui-css', PZUCD_PLUGIN_URL . '/external/jquery-ui-1.10.2.custom/css/pz_ultimate_content_display/jquery-ui-1.10.2.custom.min.css' );
		$screen = get_current_screen();
		if ( 'ucd-layouts' == $screen->id )
		{
			wp_enqueue_script( 'pzucd-validation-engine-js-lang', PZUCD_PLUGIN_URL . '/external/jQuery-Validation-Engine/js/languages/jquery.validationEngine-en.js', array( 'jquery' ) );
			wp_enqueue_script( 'pzucd-validation-engine-js', PZUCD_PLUGIN_URL . '/external/jQuery-Validation-Engine/js/jquery.validationEngine.js', array( 'jquery' ) );
			wp_enqueue_style( 'pzucd-validation-engine-css', PZUCD_PLUGIN_URL . '/external/jQuery-Validation-Engine/css/validationEngine.jquery.css' );
		}
	}

	function admin_menu()
	{
		global $pzucd_menu, $pizazzwp_updates;
		if ( !$pzucd_menu )
		{
			//add_menu_page( $page_title, $menu_title, $capability, $menu_slug, $function, $icon_url, $position );
			$pzucd_menu = add_menu_page( 'About UCD', 'Ultimate Content Display', 'edit_posts', 'pzucd', 'pzucd_about' );
			// add_submenu_page( $parent_slug, $page_title, $menu_title, $capability, $menu_slug, $function ); 
			add_submenu_page(
							'pzucd', 'About Ultimate Content Display', 'About', 'manage_options', 'pzucd_about', array( $this, 'pzucd_about' )
			);
		}
	}

	function admin_head()
	{
		
	}

	// Admin main page
	function pzucd_about()
	{
		global $title;

		echo '<div class = "wrap">

<!--Display Plugin Icon, Header, and Description-->
<div class = "icon32" id = "icon-users"><br></div>

<h2>' . $title . '</h2>

</div><!--end table-->
</div>
<div style = "clear:both"></div>';
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
	//             'content' => '<h3>Support</h3><p>'.__('Headway users can get support for ContentPlus on the <a href = "http://support.headwaythemes.com/" target = _blank>Headway forums</a>').'</p>'
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

