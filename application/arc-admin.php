<?php
  function pzarc_removeReduxDemoModeLink()
  { // Be sure to rename this function to something more unique
    if (class_exists('ReduxFrameworkPlugin')) {
      remove_filter('plugin_row_meta', array(ReduxFrameworkPlugin::get_instance(), 'plugin_metalinks'), null, 2);
    }
    if (class_exists('ReduxFrameworkPlugin')) {
      remove_action('admin_notices', array(ReduxFrameworkPlugin::get_instance(), 'admin_notices'));
    }
  }

  add_action('init', 'pzarc_removeReduxDemoModeLink');

  class pzarcAdmin
  {

    function __construct()
    {
      /*
       * Create the layouts custom post type
       */

      add_action('plugins_loaded', array($this, 'init'));
    }

    function init()
    {

      // @TODO: verify this blocks non admins!
      if (is_admin() && current_user_can('edit_theme_options')) {


        if (!(class_exists('ReduxFramework') || class_exists('ReduxFrameworkPlugin'))) {
          return;
        }

//	add_action('admin_init', 'pzarc_preview_meta');
        add_action('admin_head', array($this, 'admin_head'));
        add_action('admin_menu', array($this, 'admin_menu'));
        add_action('admin_enqueue_scripts', array($this, 'admin_enqueue'));

        //@TODO: need a bit of screen dependency on this?
//      require_once PZARC_PLUGIN_PATH . '/resources/class_pzarcForm.php';
        require_once PZARC_PLUGIN_PATH . '/admin/php/class_arc_Panels_Layouts.php';
        require_once PZARC_PLUGIN_PATH . '/admin/php/class_arc_Blueprints_Layouts.php';
        require_once PZARC_PLUGIN_PATH . '/admin/php/arc-galleries.php';
        require_once PZARC_PLUGIN_PATH . '/admin/php/arc-slides.php';

        //TODO:     require_once PZARC_PLUGIN_PATH . '/admin/arc-widget.php';

//			require_once PZARC_PLUGIN_PATH . '/admin/ucd-controls.php';


        require_once PZARC_PLUGIN_PATH . '/resources/libs/php/redux-custom-fields/loader.php';
        require_once PZARC_PLUGIN_PATH . '/resources/libs/php/redux-extensions/loader.php';
        require_once PZARC_PLUGIN_PATH . '/admin/php/arc-options.php';

        // @TODO Should these really be objects?
        // Initialise objects for data and setup menu items
        $panel_layout = new arc_Panels_Layouts;
        $content_blueprint = new arc_Blueprints_Layouts;
        $galleries = new pzarc_Galleries;
        $slides = new pzarc_Slides;


//add_action( 'pzarc_do_it', array( $this, 'do_it' ) );
      }

    }

    function admin_enqueue($hook)
    {
      wp_enqueue_style('pzarc-admin-styles');
//    wp_enqueue_style('pzarc-jqueryui-css');

      $screen = get_current_screen();
      if (strpos(('X' . $screen->id), 'arc-') > 0) {
//			wp_enqueue_script( 'jquery-ui-tabs' );
//			wp_enqueue_script( 'jquery-ui-button' );

//      wp_enqueue_script('jquerui');
        wp_enqueue_style('dashicons');

//      wp_enqueue_style('pzarc-block-css', PZARC_PLUGIN_URL . '/admin/css/arc-admin.css');
        wp_enqueue_style('pzarc-jqueryui-css', PZARC_PLUGIN_URL . '/resources/libs/js/jquery-ui-1.10.2.custom/css/pz_architect/jquery-ui-1.10.2.custom.min.css');

        wp_enqueue_script('jquery-pzarc-metaboxes', PZARC_PLUGIN_URL . '/admin/js/arc-metaboxes.js', array('jquery'));


        wp_enqueue_script('pzarc-validation-engine-js-lang', PZARC_PLUGIN_URL . '/resources/libs/js/jQuery-Validation-Engine/js/languages/jquery.validationEngine-en.js', array('jquery'));
        wp_enqueue_script('pzarc-validation-engine-js', PZARC_PLUGIN_URL . '/resources/libs/js/jQuery-Validation-Engine/js/jquery.validationEngine.js', array('jquery'));
        wp_enqueue_style('pzarc-validation-engine-css', PZARC_PLUGIN_URL . '/resources/libs/js/jQuery-Validation-Engine/css/validationEngine.jquery.css');
      }
    }

    function admin_menu()
    {
      global $pzarc_menu, $pizazzwp_updates;
      if (!$pzarc_menu) {
        //add_menu_page( $page_title,  $menu_title, $capability,   $menu_slug, $function,    $icon_url, $position );
        $pzarc_menu = add_menu_page('About', 'Architect', 'edit_posts', 'pzarc', 'pzarc_about', PZARC_PLUGIN_URL . 'wp-icon.png', 93);
        // add_submenu_page( $parent_slug, $page_title, $menu_title, $capability, $menu_slug, $function );

        // Don't need this as it's carried in the layouts already
//			add_submenu_page(
//				'pzarc', 'Styling', 'Styling', 'manage_options', 'pzarc_styling', array( $this, 'pzarc_styling' )
//			);
        add_submenu_page(
            'pzarc', 'Developer Tools', '<span class="dashicons dashicons-hammer size-small"></span>Tools', 'manage_options', 'pzarc_tools', array($this,
                                                                                                                                                   'pzarc_tools')
        );
        add_submenu_page(
            'pzarc', 'About Architect Content Display Framework', '<span class="dashicons dashicons-info size-small"></span>About', 'manage_options', 'pzarc_about', array($this,
                                                                                                                                                                           'pzarc_about'), 99
        );

        global $submenu;
        // This is reliant on About being the last menu item
        array_unshift($submenu[ 'pzarc' ], array_pop($submenu[ 'pzarc' ]));
      }
    }

    function admin_head()
    {

    }

    // Admin main page
    function pzarc_about()
    {
      global $title;

      echo '<div class = "wrap clearfix">

      <!--Display Plugin Icon, Header, and Description-->
      <div class = "icon32" id = "icon-users"><br></div>

      <h2>' . $title . '</h2>
      <h4>Version ' . PZARC_VERSION . '</h4>
      <p>Is it a slider? Is it a gallery? Is it a grid layout? Yes! It\'s all these and more.</p>
      <p>Fed up with a plethora of plugins that all seem to do the same thing, but in different ways? Me too. That\'s why I created Architect. I was guilty too. I had four plugins: ExcerptsPlus, GalleryPlus, SliderPlus and TabsPlus providing four different ways to display your content.</p>
      <p>Architect enables you to easily design complex content layouts, such as magazine layouts, sliders, galleries and tabbed content. A layout is made up of two components:</p>
      <p> And probably the most amazing thing... with Architect,  your layouts are transportable. Change your theme without losing your content layouts. And they\'ll even pick up a lot of the fomatting of your new theme if it uses standard WordPress classes</p>
      <ul><li>Criteria define  what content is selected to display</li></ul>
      <h3>Panels</h3>
      <ul><li>Panels define the layout of the individual content which can be displayed one or many times in a layout. Panels can also be re-used in multiple Blueprints</li></ul>
      <h3>Blueprints</h3>
      <ul><li>A Blueprint encompasses the overall content selection, design, layout and navigation. It can contain up to three Sections, each section displaying a Panel layout one or multiple times. This allows you to easily create a layout that, for example, might show a single post followed by a grid of excerpts. Within the Blueprint you can also include navigation, which can be pagination type, or a navigator type.</li></ul>
      <p>Below is a wireframe example</p>
      <p><img src="' . PZARC_PLUGIN_URL . '/assets/images/help/arc-layout.jpg" style="display:block"/></p>

      <h2>Usage</h2>

      <p>For example, using shortcodes, you might have:</p>
      <p style="font-weight:bold">[pzarchitect blueprint="blog-page-layout"]</p>
      <p style="font-weight:bold">[pzarchitect blueprint="thumb-gallery" ids="321,456,987,123,654,789"]</p>

      <p>Or a template tag</p>
      <p style="font-weight:bold">pzarchitect(\'blog-page-layout\')</p>
      <p style="font-weight:bold">pzarchitect(\'thumb-gallery\', \'321,456,987,123,654,789\')</p>

      </div><!--end table-->
      </div>
      <div class="clear"></div>';
    }


    function pzarc_tools()
    {
      global $title;

      echo '<div class = "wrap">

			<!--Display Plugin Icon, Header, and Description-->
			<div class = "icon32" id = "icon-users"><br></div>

			<h2>' . $title . '</h2>
			<h3>Builder</h3>
				<p>Generate WordPress page template code for inserting in your templates</p>
				<p>Can I use Redux or similar??</p>
				<textarea name="textarea" rows="10" cols="50">Code will appear here upon generating</textarea>
						<h3>Export</h3>
						<p>Export single or multiple blueprints and panels</p>
						<h3>Import</h3>
						<p>Import single or multiple blueprints and panels</p>
						<h3>Duplicate</h3>
						<p>Duplicate single or multiple blueprints and panels</p>
			</div><!--end table-->
			</div>
      ';
    }

    // Make this only load once - probably loads all the time at the moment
  }

// end pzarcAdmin

  new pzarcAdmin();


  class pzarc_Options
  {

    /* Using the WP setting sframework. TODO: Intergrate into the above */
    private $plugin_path;
    private $plugin_url;
    private $l10n;
    private $wpsf;

    function __construct()
    {
      $this->plugin_path = PZARC_PLUGIN_PATH;
      $this->plugin_url  = PZARC_PLUGIN_URL;
      $this->l10n        = 'wp-settings-framework';
      add_action('admin_menu', array(&$this, 'admin_menu'), 99);

      // Include and create a new WordPressSettingsFramework
      //TODO: Remove this
      require_once($this->plugin_path . '/resources/libs/php/wp-settings-framework.php');
      $this->wpsf = new WordPressSettingsFramework($this->plugin_path . '/admin/php/settings/options.php', 'architect-defaults');
      // Add an optional settings validation filter (recommended)
      add_filter($this->wpsf->get_option_group() . '_settings_validate', array(&$this, 'validate_settings'));
    }

    function admin_menu()
    {
      add_submenu_page(
          'pzarc', 'Options', '<span class="dashicons dashicons-admin-settings"></span>Options', 'manage_options', 'pzarc_options', array(&$this,
                                                                                                                                          'pzarc_options')
      );
    }

    function pzarc_options()
    {
      global $title;

      echo '<div class = "wrap">

			<!--Display Plugin Icon, Header, and Description-->
			<div class = "icon32" id = "icon-options-general"><br></div>

			<h2>' . $title . '</h2>
<p>List of common classes as used in TwentyXX themes and the means to override them and their default values i.e. .hentry, .entry-title, .entry-content, etc. This way people can change them if their theme uses different ones. Architect will add these to appropriate parent classes of the panel ID</p>
    ';
      $this->wpsf->settings();
      echo '</div>';
    }

    function validate_settings($input)
    {
      // Do your settings validation here
      // Same as $sanitize_callback from http://codex.wordpress.org/Function_Reference/register_setting
      return $input;
    }


  }

  new pzarc_Options();
