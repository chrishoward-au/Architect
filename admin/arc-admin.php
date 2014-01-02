<?php

class pzucdAdmin
{

	function __construct()
	{
		/*
		 * Create the layouts custom post type 
		 */

    add_action('plugins_loaded',array($this,'init'));
	}

  function init() {

    // @TODO: verify this blocks non admins!
    if ( is_admin() && current_user_can('edit_theme_options'))
    {

      if (!class_exists('CMB_Meta_Box'))
      {
        require_once PZUCD_PLUGIN_PATH . 'external/Custom-Meta-Boxes/custom-meta-boxes.php';
      }

//	add_action('admin_init', 'pzucd_preview_meta');
      add_action( 'admin_head', array( $this, 'admin_head' ) );
      add_action( 'admin_menu', array( $this, 'admin_menu' ) );
      add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue' ) );

      //@TODO: need a bit of screen dependency on this?
      require_once PZUCD_PLUGIN_PATH . '/includes/class_pzucdForm.php';
      require_once PZUCD_PLUGIN_PATH . '/admin/ucd-cell-layouts.php';
      require_once PZUCD_PLUGIN_PATH . '/admin/ucd-data-selection.php';
      require_once PZUCD_PLUGIN_PATH . '/admin/ucd-content-blueprints.php';
//			require_once PZUCD_PLUGIN_PATH . '/admin/ucd-controls.php';


      // @TODO Should these really be objects?
      // Initialise objects for data
      $data_selection		 = new pzucd_Criteria;
      $cell_layout		 = new pzucd_Cell_Layouts;
      $content_blueprint	 = new pzucd_Content_Blueprints;
//			$content_blueprint	 = new pzucd_Controls;

//add_action( 'pzucd_do_it', array( $this, 'do_it' ) );
    }

  }

	function admin_enqueue( $hook )
	{
		wp_enqueue_style( 'pzucd-block-css', PZUCD_PLUGIN_URL . '/admin/css/ucd-admin.css' );
		wp_enqueue_style( 'pzucd-jqueryui-css', PZUCD_PLUGIN_URL . '/external/jquery-ui-1.10.2.custom/css/pz_ultimate_content_display/jquery-ui-1.10.2.custom.min.css' );
		$screen = get_current_screen();
		if ( strpos( ('X' . $screen->id ), 'ucd-' ) > 0 )
		{
//			wp_enqueue_script( 'jquery-ui-tabs' );
//			wp_enqueue_script( 'jquery-ui-button' );

//      wp_enqueue_script('jquerui');
      wp_enqueue_style( 'dashicons' );

			wp_enqueue_style( 'pzucd-block-css', PZUCD_PLUGIN_URL . '/admin/css/ucd-admin.css' );
			wp_enqueue_style( 'pzucd-jqueryui-css', PZUCD_PLUGIN_URL . '/external/jquery-ui-1.10.2.custom/css/pz_ultimate_content_display/jquery-ui-1.10.2.custom.min.css' );

			wp_enqueue_script( 'jquery-pzucd-metaboxes', PZUCD_PLUGIN_URL . '/admin/js/ucd-metaboxes.js', array( 'jquery' ) );

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
			//add_menu_page(             $page_title,  $menu_title,               $capability,   $menu_slug, $function,    $icon_url, $position );
			$pzucd_menu = add_menu_page( 'About', 'Architect', 'edit_posts', 'pzucd',     'pzucd_about',PZUCD_PLUGIN_URL.'wp-icon.png' );
			// add_submenu_page( $parent_slug, $page_title, $menu_title, $capability, $menu_slug, $function ); 

			// Don't need this as it's carried in the layouts already
//			add_submenu_page(
//				'pzucd', 'Styling', 'Styling', 'manage_options', 'pzucd_styling', array( $this, 'pzucd_styling' )
//			);
			add_submenu_page(
				'pzucd', 'Developer Tools', '<span class="dashicons dashicons-hammer"></span>Tools', 'manage_options', 'pzucd_generator', array( $this, 'pzucd_generator' )
			);
			add_submenu_page(
							'pzucd', 'Options', '<span class="dashicons dashicons-admin-settings"></span>Options', 'manage_options', 'pzucd_options', array( $this, 'pzucd_options' )
			);
			add_submenu_page(
				'pzucd', 'About Architect Content Framework', '<span class="dashicons dashicons-info"></span>About', 'manage_options', 'pzucd_about', array( $this, 'pzucd_about' )
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
<p>Is it a slider? Is it a gallery? Is it a grid layout? Yes! It\'s all these and more.</p>
<p>Fed up with a plethora of plugins that all seem to do the same thing, but in different ways? Me too. That\'s why I created UCD. I was guilty to. I had four plugins: ExcerptsPlus, GalleryPlus, SliderPlus and TabsPlus providing four different ways to display your content.</p>
<p>Ultimate Content Display enables you to easily build complex content layouts, such as magazine layouts, sliders, galleries and tabbed content. A layout is made up of four components:</p>
<h3>Criteria</h3>
<ul><li>Criteria define  what content is selected to display</li></ul>
<h3>Cells</h3>
<ul><li>Cells define the layout of the content for each cell</li></ul>
<h3>Sections</h3>
<ul><li>Sections define the layout of the cells. Multiple sections can be used e.g. first a full post, then a grid of post excerpts</li></ul>
<h3>Controls</h3>
<ul><li>Controls define the layout of the navigation controllers</li></ul>

<p>These four are combined to produce the final layout</p>
<p><img src="'.PZUCD_PLUGIN_URL.'/documentation/ucd-layout.jpg" /></p>

<p>For example, using shortcodes, you might have:</p>
<p style="font-weight:bold">[pzucd cells="myfirstcelldesign" criteria="latestposts" blueprints="one-up,six-up" controls="myfirstnav"]</p>

<p>Or a template tag</p>
<p style="font-weight:bold">pzucd_layout(\'myfirstcelldesign\', \'latestposts\', \'one-up,six-up\', \'myfirstnav\');</p>

<p>You can use one to three blueprints. The latter ones will continue display of posts from where the previous left off. This, for example, would allow you to make a layout that shows the first post in full, and then excerpts for the next six.</p>

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
<p>add a list of common classes as used in TwentyXX themes and the means to override (and reset) them i.e. .hentry, .entry-title, .entry-content, etc. This way people can change them if their theme uses different ones. UCD will add these appropriately to classes</p>
      <p><label><em>.entry-title</em> alternatives: </label><input type="text" value".excerpt-title"></p>
      <p><label><em>.entry-content</em> alternatives: </label><input type="text" value".excerpt-content"></p>
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
			<h3>Generator</h3>
				<p>Generate WordPress page template code for inserting in your templates</p>
				<p>Can I use Redux or similar??</p>
				<textarea name="textarea" rows="10" cols="50">Code will appear here upon generating</textarea>
						<h3>Export</h3>
						<h3>Import</h3>
			</div><!--end table-->
			</div>
			<div style = "clear:both"></div>
';
	}

	// Make this only load once - probably loads all the time at the moment
}

// end pzucdAdmin

new pzucdAdmin();



