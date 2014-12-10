<?php

  $redux_opt_name = '_architect';

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

  class ArchitectAdmin
  {

    function __construct()
    {
      /*
       * Create the layouts custom post type
       */

//      add_action('plugins_loaded', array($this, 'init'));
      add_action('plugins_loaded', array($this, 'init'));
    }

    function init()
    {
      // @TODO: verify this blocks non admins!
      if (!is_admin() || !current_user_can('edit_theme_options')) {
        return;
      }
      if (!(class_exists('ReduxFramework') || class_exists('ReduxFrameworkPlugin'))) {
        add_action( 'admin_notices', array($this,'missing_redux_admin_notice' ));
        return;
      }
      add_action('admin_head', array($this, 'admin_head'));
      add_action('admin_menu', array($this, 'admin_menu'));
      add_action('admin_enqueue_scripts', array($this, 'admin_enqueue'));
      add_filter('admin_body_class', array(&$this, 'add_admin_body_class'));


      // TODO: Make up some easily editable panel defs - prob have to be a custom content type
      //       require_once PZARC_PLUGIN_PATH . '/admin/php/arc-options-def-editor.php';

      //@TODO: need a bit of screen dependency on this?

      require_once PZARC_PLUGIN_APP_PATH . '/admin/php/class_arc_Panels_Layouts.php';
      require_once PZARC_PLUGIN_APP_PATH . '/admin/php/class_arc_Blueprints_Layouts.php';
      require_once PZARC_PLUGIN_APP_PATH . '/admin/php/arc-save-process.php';


      //TODO:     require_once PZARC_PLUGIN_PATH . '/admin/arc-widget.php';

      // This one is really only needed on posts, pages and snippets, so could conditionalise its load
      require_once PZARC_PLUGIN_APP_PATH . '/admin/php/class_arc_Misc_Metaboxes.php';
      require_once PZARC_PLUGIN_APP_PATH . '/shared/includes/php/redux-custom-fields/loader.php';
      require_once PZARC_PLUGIN_APP_PATH . '/shared/includes/php/redux-extensions/loader.php';

      $misc_metaboxes    = new arc_Misc_metaboxes();
      $panel_layout      = new arc_Panels_Layouts();
      $content_blueprint = new arc_Blueprints_Layouts();

      require_once PZARC_PLUGIN_APP_PATH . '/admin/php/arc-options.php';
      require_once PZARC_PLUGIN_APP_PATH . '/admin/php/arc-options-styling.php';
      require_once PZARC_PLUGIN_APP_PATH . '/admin/php/arc-options-actions.php';

      // TODO: this needs to be dumberized so can work on dev defined panels and content. But why is these here anyway??
//      require_once PZARC_PLUGIN_APP_PATH . '/shared/architect/php/content-types/generic/class_arc_Panel_Generic.php';


      require_once(PZARC_PLUGIN_APP_PATH . '/shared/includes/php/BFI-thumb-forked/BFI_Thumb.php');
      require_once(PZARC_PLUGIN_APP_PATH . '/shared/includes/php/pzwp-focal-point/pzwp-focal-point.php');


    }

    function missing_redux_admin_notice() {
      echo '<div id="message" class="error"><p><strong>One final step in installing ARCHITECT.</strong><br>It cannot function without the Redux Framework plugin. You need to install and/or activate Redux.<br>Redux is the backbone of Architect, providing all the necessary code libraries for Architect\'s fields and options.<br>There should be another message with a link to make installing and activating Redux easy. If you can\'t find it, contact PizazzWP support.</p></div>';
    }


    function add_admin_body_class($classes)
    {
      $screen = get_current_screen();

      switch ($screen->id) {
        case 'architect_page__architect_options':
        case 'architect_page__architect_styling':
        case 'architect_page__architect_actions_editor':
        case 'edit-arc-panels':
        case 'edit-arc-blueprints':
        case 'arc-panels':
        case 'arc-blueprints':
        case 'architect_page_pzarc_tools':
        case 'architect_page_pzarc_about':
          global $_architect_options;
          if ($_architect_options[ 'architect_enable_bgimage' ]) {
            $arc_bg = $_architect_options[ 'architect_bgimage' ];
            $classes .= ' arc-bgimage arc-bg-' . $arc_bg;
          }
          $classes .= ' ' . $screen->post_type;
          break;
      }

      return $classes;
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

        // We shouldn't need this anymore
//        wp_enqueue_style('pzarc-jqueryui-css', PZARC_PLUGIN_APP_URL . '/shared/includes/js/jquery-ui-1.10.2.custom/css/pz_architect/jquery-ui-1.10.2.custom.min.css');

        wp_enqueue_script('jquery-pzarc-metaboxes', PZARC_PLUGIN_APP_URL . '/admin/js/arc-metaboxes.js', array('jquery'));

        // We shouldn't need this anymore

//        wp_enqueue_script('pzarc-validation-engine-js-lang', PZARC_PLUGIN_APP_URL . '/shared/includes/js/jQuery-Validation-Engine/js/languages/jquery.validationEngine-en.js', array('jquery'));
//        wp_enqueue_script('pzarc-validation-engine-js', PZARC_PLUGIN_APP_URL . '/shared/includes/js/jQuery-Validation-Engine/js/jquery.validationEngine.js', array('jquery'));
//        wp_enqueue_style('pzarc-validation-engine-css', PZARC_PLUGIN_APP_URL . '/shared/includes/js/jQuery-Validation-Engine/css/validationEngine.jquery.css');
      }
    }

    function admin_menu()
    {
      global $pzarc_menu, $pizazzwp_updates;
      if (!$pzarc_menu) {
        //add_menu_page( $page_title,  $menu_title, $capability,   $menu_slug, $function,    $icon_url, $position );
        $pzarc_menu = add_menu_page('Getting started', 'Architect', 'edit_posts', 'pzarc', 'pzarc_about', PZARC_PLUGIN_APP_URL . 'wp-icon.png');
        // add_submenu_page( $parent_slug, $page_title, $menu_title, $capability, $menu_slug, $function );

        // Don't need this as it's carried in the layouts already
//			add_submenu_page(
//				'pzarc', 'Styling', 'Styling', 'manage_options', 'pzarc_styling', array( $this, 'pzarc_styling' )
//			);
        add_submenu_page(
            'pzarc', 'Tools', '<span class="dashicons dashicons-hammer size-small"></span>Tools', 'manage_options', 'pzarc_tools', array($this,
                                                                                                                                         'pzarc_tools')
        );
        add_submenu_page(
            'pzarc', 'Getting started with Architect Content Layout Framework', '<span class="dashicons dashicons-info size-small"></span>Getting started', 'manage_options', 'pzarc_about', array($this,
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
      echo '<div class = "wrap">

      <!--Display Plugin Icon, Header, and Description-->
        <div class = "icon32" id = "icon-users"><br></div>
        <div class="pzarc-about-box" style="background:#f9f9f9;padding:20px;border:1px solid #ddd;">
        <h2>' . $title . '</h2>
        <h4>Currently installed version: ' . PZARC_VERSION .'</h4>
        <h2>Quick start</h2>
        <div style="background:#f2f2f2;border:1px solid #e2e2e2;padding:10px;border-radius:3px;max-width:800px;font-size:14px;">
        <ol>
        <li><strong>Create a Panel</strong></li>
        <ol style="list-style-type:lower-roman"><li>Go to <em>Architect > Panels</em> and create a basic Panel. Make sure to give it a title and a short name.</li>
            <li>Leave all the other defaults for now. <em>Publish/Update</em> that.</li></ol>
        <li><strong>Create a Blueprint</strong></li>
        <ol style="list-style-type:lower-roman">
            <li>Go to <em>Architect > Blueprints</em> and create a Blueprint. Give it a <em>Title</em> and <em>Short Name</em> too, and in <em>Section 1</em> tab, under <em>Panels Layout</em>, select your Panel you just created</li>
            <li>Change <em>Limit panels (content)</em> to no so we get a lot of posts</li>
            <li>Click the <em>Panels Content</em> button and for the <em>Settings, Content Source</em>, choose <em>Posts</em></li>
            <li>Click <em>Publish/Update</em>.</li>
            </ol>
        <li><strong>Display the Blueprint</strong></li>
        <ol style="list-style-type:lower-roman">
            <li>If you are using <strong>Headway</strong>, then go to the Headway Visual Editor, select a layout to show and draw an Architect block on it and select the Blueprint and Save.<br>
            For <strong>other themes</strong>, the quickest way to test is insert an Architect shortcode on a page.<br>The form is <strong>[architect <em>blueprint-shortname</em>]</strong> where <em>blueprint-shortname</em> is the Short Name of the Blueprint to show
            </li>
            <li>Load the page and you should see a 3x grid of posts.</li></ol>
            </ol>
            </div>
            <h3>Video version</h3>
            <div style="max-width:800px;"><iframe src="//fast.wistia.net/embed/iframe/46fxmn8h0l?videoFoam=true" allowtransparency="true" frameborder="0" scrolling="no" class="wistia_embed" name="wistia_embed" allowfullscreen mozallowfullscreen webkitallowfullscreen oallowfullscreen msallowfullscreen width="720" height="405"></iframe><script src="//fast.wistia.net/assets/external/iframe-api-v1.js"></script></div>
            <p>Style wise, it may not look that great yet. To tidy it up, start exploring the Styling settings for Panels and Blueprints</p>
            <p>To make a <strong>slideshow</strong>, set the <em>Navigation</em> type to <em>Navigator</em></p>
            <p>There are a lot of settings in Architect that have all sorts of affects on your layouts and designs. Explore, experiment and have fun!</p>
            <p>For more detailed help, keep an eye on our growing <a href="http://architect4wp.com/codex-listings" target="_blank">documentation at architect4wp.com</a></p>
        <h2>What is Architect</h2>
        <p>Is it a slider? Is it a gallery? Is it a grid layout? Yes! It\'s all these and more.</p>
        <p>Fed up with a plethora of plugins that all seem to do the same thing, but in different ways? Me too. That\'s why I created Architect. I was guilty too. I had four plugins: ExcerptsPlus, GalleryPlus, SliderPlus and TabsPlus providing four different ways to display your content.</p>
        <p>Architect enables you to easily design complex content layouts, such as magazine layouts, sliders, galleries and tabbed content.</p>
        <p> And probably the most amazing thing... with Architect,  your layouts are transportable. Change your theme without losing your content layouts. And they\'ll even pick up a lot of the formatting of your new theme if it uses standard WordPress classes although, you may need to tweak the styling a little for different themes.</p>

        <p>At first it might be a little confusing about what to setup in Panels and what to do in Blueprints. Here\'s an overview:</p>
        <p><img src="' . PZARC_PLUGIN_URL . '/documentation/assets/images/how-architect-works.jpg" style="display:block;max-width:100%;"/></p>

        <h3>Panels</h3>
        <ul><li>Panels define the layout of the individual content which can be displayed one or many times in a layout. Panels can also be re-used in multiple Blueprints</li></ul>
        <ul><li>Individual content layout - titles, text, images, meta info</li>
        <li>Content styling</li>
        </ul>
        <h3>Blueprints</h3>
        <ul><li>A Blueprint encompasses the overall content selection, design, layout and navigation. It can contain up to three Sections, each section displaying a Panel layout one or multiple times. This allows you to easily create a layout that, for example, might show a single post followed by a grid of excerpts. Within the Blueprint you can also include navigation, which can be pagination type, or a navigator type.</li></ul>
        <ul><li>Overall layout</li>
        <li>content source</li>
        <li>Navigation</li>
        </ul>
        <p>Below is a wireframe example of how a Blueprint is structured</p>
        <p><img src="' . PZARC_PLUGIN_APP_URL . '/shared/assets/images/help/arc-layout.jpg" style="display:block;max-width:100%"/></p>

        <h2>Usage</h2>

        <p>For example, using shortcodes, you might have:</p>
        <p style="font-weight:bold">[architect blueprint="blog-page-layout"]</p>
        <p style="font-weight:bold">[architect blueprint="thumb-gallery" ids="321,456,987,123,654,789"]</p>

        <p>Or a template tag</p>
        <p style="font-weight:bold">pzarchitect(\'blog-page-layout\')</p>
        <p style="font-weight:bold">pzarchitect(\'thumb-gallery\', \'321,456,987,123,654,789\')</p>
        </div>

        <h2>Shout outs</h2>
        <p>Architect is powered by the following awesome code libraries, plugins and add-ons:</p>
        <ul><li><a href="http://reduxframework.com" target=_blank alt="Redux Options Framework"><img src="' . PZARC_PLUGIN_APP_URL . '/shared/assets/images/help/redux-logo.png" style="display:block"/> Redux Options Framework</a></li>
          <li><a href="http://kenwheeler.github.io/slick/" target="_blank">Slick JS</a></li>
          <li><a href="http://isotope.metafizzy.co/" target="_blank">Isotope JS</a></li>
          <li><a href="https://github.com/tommcfarlin/WordPress-Plugin-Boilerplate" target="_blank">Tom McPharlin\'s WP Plugin Boilerplate</a></li>
        </ul>
      </div>';
    }


    function pzarc_tools()
    {
      global $title;

      echo '<div class = "wrap">

			<!--Display Plugin Icon, Header, and Description-->
			<div class = "icon32" id = "icon-users"><br></div>

			<h2>' . $title . '</h2>
			<!-- This is definitely too ambitious for v1!<h3>Builder</h3>
				<p>Generate WordPress page template code for inserting in your templates</p>
				<p>Can I use Redux or similar??</p>
				<textarea name="textarea" rows="10" cols="50">Code will appear here upon generating</textarea>
						<h3>Export</h3>
						<p>Export single or multiple blueprints and panels</p>
						<h3>Import</h3>
						<p>Import single or multiple blueprints and panels</p>
						<h3>Duplicate</h3>
						<p>Duplicate single or multiple blueprints and panels</p>-->
						<h3>Rebuild Architect CSS cache</h3>
						<p>Sometimes the CSS cache file may not exist or may even become scrambled and layouts won\'t look right. If so, simply click the Rebuild button and it will be recreated. If the problem persists, contact Pizazz Support at support@pizazzwp.com.</p>
						<form action="admin.php?page=pzarc_tools" method="post">';
      wp_nonce_field('rebuild-architect-css-cache');
      echo '<input class="button-primary" type="submit" name="rebuildarchitectcss" value="Rebuild Architect CSS Cache">
        </form>';

      if (isset($_POST[ 'rebuildarchitectcss' ]) && check_admin_referer('rebuild-architect-css-cache')) {
        require_once(PZARC_PLUGIN_APP_PATH . '/admin/php/arc-save-process.php');
        save_arc_layouts('all', null, true);
        echo '<br><div id="message" class="updated"><p>Architect CSS cache has been rebuilt. Your site should look awesome again!</p>
        <p>If your site is using a cache plugin or service, clear that cache too.</p></div>';
      }

      echo '<hr style="margin-top:20px;border-color:#eee;border-style:solid;"/>

			</div><!--end table-->
			</div>
      ';
    }

    // Make this only load once - probably loads all the time at the moment
  }

// end pzarcAdmin


  $pzarcadmin = new ArchitectAdmin();

