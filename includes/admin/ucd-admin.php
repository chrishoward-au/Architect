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

			require_once PZUCD_PLUGIN_PATH . '/includes//classForm.php';
			require_once PZUCD_PLUGIN_PATH . '/includes/admin/ucd-cell-layouts.php';
			require_once PZUCD_PLUGIN_PATH . '/includes/admin/ucd-data-selection.php';
			require_once PZUCD_PLUGIN_PATH . '/includes/admin/ucd-content-templates.php';

			$cell_layout			 = new pzucd_Cell_Layouts;
			$data_selection		 = new pzucd_Criteria;
			$content_template	 = new pzucd_Content_Templates;
//add_action( 'pzucd_do_it', array( $this, 'do_it' ) );
		}
	}

	function admin_enqueue( $hook )
	{
		wp_enqueue_style( 'pzucd-block-css', PZUCD_PLUGIN_URL . '/includes/admin/css/ucd-admin.css' );
		wp_enqueue_style( 'pzucd-jqueryui-css', PZUCD_PLUGIN_URL . '/includes/external/jquery-ui-1.10.2.custom/css/pz_ultimate_content_display/jquery-ui-1.10.2.custom.min.css' );
		$screen = get_current_screen();
		if ( strpos( ('X' . $screen->id ), 'ucd-' ) > 0 )
		{
			wp_enqueue_script( 'jquery-ui-tabs' );
			wp_enqueue_script( 'jquery-ui-button' );

			wp_enqueue_style( 'pzucd-block-css', PZUCD_PLUGIN_URL . '/includes/admin/css/ucd-admin.css' );
			wp_enqueue_style( 'pzucd-jqueryui-css', PZUCD_PLUGIN_URL . '/includes/external/jquery-ui-1.10.2.custom/css/pz_ultimate_content_display/jquery-ui-1.10.2.custom.min.css' );

			wp_enqueue_script( 'jquery-pzucd-metaboxes', PZUCD_PLUGIN_URL . '/includes/admin/js/ucd-metaboxes.js', array( 'jquery' ) );

			wp_enqueue_script( 'pzucd-validation-engine-js-lang', PZUCD_PLUGIN_URL . '/includes/external/jQuery-Validation-Engine/js/languages/jquery.validationEngine-en.js', array( 'jquery' ) );
			wp_enqueue_script( 'pzucd-validation-engine-js', PZUCD_PLUGIN_URL . '/includes/external/jQuery-Validation-Engine/js/jquery.validationEngine.js', array( 'jquery' ) );
			wp_enqueue_style( 'pzucd-validation-engine-css', PZUCD_PLUGIN_URL . '/includes/external/jQuery-Validation-Engine/css/validationEngine.jquery.css' );
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

			// Don't need this as it's carried in the layouts already
//			add_submenu_page(
//				'pzucd', 'Styling', 'Styling', 'manage_options', 'pzucd_styling', array( $this, 'pzucd_styling' )
//			);
			add_submenu_page(
				'pzucd', 'Generate code', 'Generator', 'manage_options', 'pzucd_generator', array( $this, 'pzucd_generator' )
			);
			add_submenu_page(
							'pzucd', 'UCD Options', 'Options', 'manage_options', 'pzucd_options', array( $this, 'pzucd_options' )
			);
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
<p>Ultimate Content Display enables you to easily build complex content layouts. A layout is made up of four components:</p>
<ul style="margin-left:10px;">
<li>&bull; Cells</li>
<li>&bull; Criteria</li>
<li>&bull; Templates</li>
<li>&bull; Controls</li>
</ul>
<p>These four are combined to produce the final layout</p>

<p>For example, using shortcodes, you might have:</p>
<p style="font-weight:bold">[pzucd cells="myfirstcelldesign" criteria="latestposts" templates="one-up,six-up" controls="myfirstnav"]</p>

<p>Or a template tag</p>
<p style="font-weight:bold">pzucd_layout(\'myfirstcelldesign\', \'latestposts\', \'one-up,six-up\', \'myfirstnav\');</p>

<p>You can use one or two templates. The second one will continue display of posts from where the first left off. This, for example, would allow you to make a layout that shows the first post in full, and then excerpts for the next six.</p>

</div><!--end table-->
</div>
<div style = "clear:both"></div>';
	}

	function pzucd_options()
	{
		global $title;

		echo '<div class = "wrap">

			<!--Display Plugin Icon, Header, and Description-->
			<div class = "icon32" id = "icon-users"><br></div>

			<h2>' . $title . '</h2>
add a list of common classes as used in TentyX themes and the means to override (and reset) them i.e. .hentry, .entry-title, .entry-content, etc. This way peopel canchange them if their thme uses different ones.
			</div><!--end table-->
			</div>
			<div style = "clear:both"></div>';
	}

	function pzucd_generator()
	{
		global $title;

		echo '<div class = "wrap">

			<!--Display Plugin Icon, Header, and Description-->
			<div class = "icon32" id = "icon-users"><br></div>

			<h2>' . $title . '</h2>
				Bring it all together in the generator. Take a cell layout, one or more tempaltes, a controller and a criteria, and the generator will generator all the CSS, JS and PHP files you need.
			</div><!--end table-->
			</div>
			<div style = "clear:both"></div>';
	}

	// Make this only load once - probably loads all the time at the moment
}

// end pzucdAdmin

new pzucdAdmin();

class pzucdPreview
{
	
}

