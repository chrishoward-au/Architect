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
        add_action('admin_notices', array($this, 'missing_redux_admin_notice'));

        // TODO: Add an alternativeArchitect Admin screen.
        add_action('admin_menu', array($this, 'admin_menu_no_redux'));
        require_once(PZARC_PLUGIN_APP_PATH . '/shared/includes/php/BFI-thumb-forked/BFI_Thumb.php');
        require_once(PZARC_PLUGIN_APP_PATH . '/shared/includes/php/pzwp-focal-point/pzwp-focal-point.php');

        return;
      } else {
        add_action('admin_head', array($this, 'admin_head'));
        add_action('admin_menu', array($this, 'admin_menu'));
        add_action('admin_enqueue_scripts', array($this, 'admin_enqueue'));
        add_filter('admin_body_class', array(&$this, 'add_admin_body_class'));


        // TODO: Make up some easily editable panel defs - prob have to be a custom content type
        //       require_once PZARC_PLUGIN_PATH . '/admin/php/arc-options-def-editor.php';

        //@TODO: need a bit of screen dependency on this?

        require_once PZARC_PLUGIN_APP_PATH . '/admin/php/class_arc_panels_layouts.php';
        require_once PZARC_PLUGIN_APP_PATH . '/admin/php/class_arc_blueprints_layouts.php';
        require_once PZARC_PLUGIN_APP_PATH . '/admin/php/arc-save-process.php';


        //TODO:     require_once PZARC_PLUGIN_PATH . '/admin/arc-widget.php';

        // This one is really only needed on posts, pages and snippets, so could conditionalise its load
        require_once PZARC_PLUGIN_APP_PATH . '/admin/php/class_arc_misc_metaboxes.php';
        require_once PZARC_PLUGIN_APP_PATH . '/shared/includes/php/redux-custom-fields/loader.php';
        require_once PZARC_PLUGIN_APP_PATH . '/shared/includes/php/redux-extensions/loader.php';

        $misc_metaboxes    = new arc_Misc_metaboxes();
        $panel_layout      = new arc_Panels_Layouts();
        $content_blueprint = new arc_Blueprints_Layouts();

        require_once PZARC_PLUGIN_APP_PATH . '/admin/php/arc-options.php';
        require_once PZARC_PLUGIN_APP_PATH . '/admin/php/arc-options-styling.php';
        require_once PZARC_PLUGIN_APP_PATH . '/admin/php/arc-options-actions.php';

        // TODO: this needs to be dumberized so can work on dev defined panels and content. But why is these here anyway??
//      require_once PZARC_PLUGIN_APP_PATH . '/shared/architect/php/content-types/generic/class_arc_panel_generic.php';


        require_once(PZARC_PLUGIN_APP_PATH . '/shared/includes/php/BFI-thumb-forked/BFI_Thumb.php');
        require_once(PZARC_PLUGIN_APP_PATH . '/shared/includes/php/pzwp-focal-point/pzwp-focal-point.php');

      }
    }

    function missing_redux_admin_notice()
    {
      echo '<div id="message" class="error"><h3>' . __('Architect requires Redux Framework', 'pzarchitect') . '</h3><p><strong>' . __('One final step in installing Architect.', 'pzarchitect') . '</strong><br>' . __('It cannot function without the Redux Framework plugin. You need to install and/or activate Redux.', 'pzarchitect') . '<br>' . __('Redux is the backbone of Architect, providing all the necessary code libraries for Architect\'s fields and options.', 'pzarchitect') . '<br>' . __('There should be another message with a link to make installing and activating Redux easy. If you can\'t find it, contact PizazzWP support.', 'pzarchitect') . '</p></div>';
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

        wp_enqueue_script('jquery-pzarc-metaboxes', PZARC_PLUGIN_APP_URL . '/admin/js/arc-metaboxes.js', array('jquery'), false);


        // We shouldn't need this anymore

//        wp_enqueue_script('pzarc-validation-engine-js-lang', PZARC_PLUGIN_APP_URL . '/shared/includes/js/jQuery-Validation-Engine/js/languages/jquery.validationEngine-en.js', array('jquery'));
//        wp_enqueue_script('pzarc-validation-engine-js', PZARC_PLUGIN_APP_URL . '/shared/includes/js/jQuery-Validation-Engine/js/jquery.validationEngine.js', array('jquery'));
//        wp_enqueue_style('pzarc-validation-engine-css', PZARC_PLUGIN_APP_URL . '/shared/includes/js/jQuery-Validation-Engine/css/validationEngine.jquery.css');
        add_filter('post_row_actions', 'pzarc_duplicate_post_link', 10, 2);
        add_filter('page_row_actions', 'pzarc_duplicate_post_link', 10, 2);
      }
      if ('architect_page_pzarc_support' === $screen->id) {
        wp_enqueue_script('js-classlist', PZARC_PLUGIN_APP_URL . '/shared/includes/js/tabby/dist/js/classList.min.js', array('jquery'));
        wp_enqueue_script('js-tabby', PZARC_PLUGIN_APP_URL . '/shared/includes/js/tabby/dist/js/tabby.min.js', array('jquery'));
        wp_enqueue_style('css-tabby', PZARC_PLUGIN_APP_URL . '/shared/includes/js/tabby/dist/css/tabby.min.css');
      }

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

          wp_enqueue_script('js-freshdesk', 'http://assets.freshdesk.com/widget/freshwidget.js', false, true);
          wp_enqueue_script('js-freshdesk-support', PZARC_PLUGIN_APP_URL . '/admin/js/freshdesk-support.js', false, true);
          break;
      }


    }

    function admin_menu()
    {
      global $pzarc_menu, $pizazzwp_updates;
      if (!$pzarc_menu) {
        //add_menu_page( $page_title,  $menu_title, $capability,   $menu_slug, $function,    $icon_url, $position );
        $pzarc_menu = add_menu_page(__('Getting started', 'pzarchitect'), 'Architect', 'edit_posts', 'pzarc', 'pzarc_about', PZARC_PLUGIN_APP_URL . 'wp-icon.png');
        // add_submenu_page( $parent_slug, $page_title, $menu_title, $capability, $menu_slug, $function );

        // Don't need this as it's carried in the layouts already
//			add_submenu_page(
//				'pzarc', 'Styling', 'Styling', 'manage_options', 'pzarc_styling', array( $this, 'pzarc_styling' )
//			);
        add_submenu_page(
            'pzarc', __('Tools', 'pzarchitect'), '<span class="dashicons dashicons-hammer size-small"></span>' . __('Tools', 'pzarchitect'), 'manage_options', 'pzarc_tools', array($this,
                                                                                                                                                                                    'pzarc_tools')
        );
        add_submenu_page(
            'pzarc', __('Help & Support', 'pzarchitect'), '<span class="dashicons dashicons-editor-help size-small"></span>' . __('Help & Support', 'pzarchitect'), 'manage_options', 'pzarc_support', array($this,
                                                                                                                                                                                                             'pzarc_support')
        );

        global $submenu;
        // Shift those last  to the top
        array_unshift($submenu[ 'pzarc' ], array_pop($submenu[ 'pzarc' ]));
      }

    }

    function admin_menu_no_redux()
    {
//      global $pzarc_menu, $pizazzwp_updates;
//      if (!$pzarc_menu) {
      //add_menu_page( $page_title,  $menu_title, $capability,   $menu_slug, $function,    $icon_url, $position );
      $pzarc_menu = add_menu_page('About Architect', 'Architect', 'edit_posts', 'pzarc', 'pzarc_about', PZARC_PLUGIN_APP_URL . 'wp-icon.png');
//        add_submenu_page(
//            'pzarc', 'Help & Support', '<span class="dashicons dashicons-editor-help size-small"></span>Help & Support', 'manage_options', 'pzarc_support', array($this,
//                                                                                                                                                                  'pzarc_support')
//        );
//
//        global $submenu;
//        // Shift those last  to the top
//        array_unshift($submenu[ 'pzarc' ], array_pop($submenu[ 'pzarc' ]));
//      }

    }

    function admin_head()
    {

    }


    function pzarc_tools()
    {
      global $title;

      echo '<div class = "wrap">

			<!--Display Plugin Icon, Header, and Description-->
			<div class = "icon32" id = "icon-users"><br></div>
        <div class="pzarc-about-box" style="background:#f9f9f9;padding:20px;border:1px solid #ddd;">

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
						<h3>' . __('Rebuild Architect CSS cache', 'pzarchitect') . '</h3>
						<p>' . __('Sometimes the CSS cache file may not exist or may even become scrambled and layouts will not look right. If so, simply click the Rebuild button and it will be recreated. If the problem persists, contact Pizazz Support at <strong>support@pizazzwp.com</strong>.', 'pzarchitect') . '</p>
						<form action="admin.php?page=pzarc_tools" method="post">';
      wp_nonce_field('rebuild-architect-css-cache');
      echo '<button class="button-primary" style="min-width:100px;" type="submit" name="rebuildarchitectcss" value="' . __('Rebuild Architect CSS Cache') . '">' . __('Rebuild') . '  <span class="dashicons dashicons-admin-appearance" style="margin-left:1%;color:inherit;font-size:22px;vertical-align:text-bottom"></span></button>
        </form>';

      if (isset($_POST[ 'rebuildarchitectcss' ]) && check_admin_referer('rebuild-architect-css-cache')) {
        require_once(PZARC_PLUGIN_APP_PATH . '/admin/php/arc-save-process.php');
        save_arc_layouts('all', null, true);
        echo '<br><div id="message" class="updated"><p>' . __('Architect CSS cache has been rebuilt. Your site should look awesome again!', 'pzarchitect') . '</p>
        <p>' . __('If your site is using a cache plugin or service, clear that cache too.', 'pzarchitect') . '</p></div>';
      }

      echo '<hr style="margin-top:20px;border-color:#eee;border-style:solid;"/>';
      if (function_exists('bfi_flush_image_cache')) {
        echo '<h3>' . __('Clear Architect images cache', 'pzarchitect') . '</h3>

    <p>' . __('If you update or change images in any posts,sometimes the image cache may get out-of-sync. In that case, you can refresh the thumbs image cache to ensure your site visitors are seeing the correct images.', 'pzarchitect') . '</p>

    <p>' . __('Please note: Refreshing the cache causes no problems other than the next person who visits your site may have to wait a little longer as the cache images get recreated.', 'pzarchitect') . ' <strong>' . __('No images in any post will be affected', 'pzarchitect') . '</strong>. </p>

    <form action="admin.php?page=pzarc_tools" method="post">';
        wp_nonce_field('flush-thumb-cache');
        echo '<button class="button-primary"  style="min-width:100px;" type="submit" name="flushbficache" value="' . __('Empty Architect image cache', 'pzarchitect') . '">' . __('Clear') . '  <span class="dashicons dashicons-images-alt2" style="margin-left:1%;color:inherit;font-size:22px;vertical-align:text-bottom"></span></button>
    </form>
    <hr style="margin-top:20px;border-color:#eee;border-style:solid;"/>';
        if (isset($_POST[ 'flushbficache' ]) && check_admin_referer('flush-thumb-cache')) {
          bfi_flush_image_cache();
          echo '<div id="message" class="updated"><p>' . __('Architect image cache cleared. It will be recreated next time someone vists your site.', 'pzarchitect') . '</p></div>';
        }

      }
      echo '</div><!--end table-->
			</div>
      ';
    }

    function pzarc_support()
    {
      global $title;

      echo '<div class = "wrap">
     <script>
          tabby.init();
      </script>

  			<!--Display Plugin Icon, Header, and Description-->
        <div class="icon32" id="icon-users">
            <br>
        </div>
        <div class="pzarc-about-box" style="background:#f9f9f9;padding:20px;border:1px solid #ddd;">

            <h2>' . $title . '</h2>

            <div class="tabby tabs">
                <button data-tab="#quick" class="first active">' . __('Quick start', 'pzarchitect') . '</button>
                <button data-tab="#what" >' . __('What is Architect', 'pzarchitect') . '</button>
                <button data-tab="#how">' . __('Usage', 'pzarchitect') . '</button>
                <button data-tab="#help">' . __('Support', 'pzarchitect') . '</button>
                <button data-tab="#shout">' . __('Shoutouts', 'pzarchitect') . '</button>
                <button data-tab="#presets">' . __('Presets', 'pzarchitect') . '</button>
            </div>
            <div class="tabby tabs-content">
                <div class="tabs-pane active" id="quick">
                    <h2>' . __('Quick start') . '</h2>
                    <div style="background:#f2f2f2;border:1px solid #e2e2e2;padding:10px;border-radius:3px;max-width:800px;font-size:14px;">
                    <ol>
                    <li><strong>' . __('Create a Panel', 'pzarchitect') . '</strong></li>
                    <ol style="list-style-type:lower-roman"><li>' . __('Go to <em>Architect > Panels</em> and create a basic Panel. Make sure to give it a title and a short name.', 'pzarchitect') . '</li>
                        <li>' . __('Leave all the other defaults for now. <em>Publish/Update</em> that.', 'pzarchitect') . '</li></ol>
                    <li><strong>' . __('Create a Blueprint', 'pzarchitect') . '</strong></li>
                    <ol style="list-style-type:lower-roman">
                        <li>' . __('Go to <em>Architect > Blueprints</em> and create a Blueprint. Give it a <em>Title</em> and <em>Short Name</em> too, and in <em>Section 1</em> tab, under <em>Panels Layout</em>, select your Panel you just created', 'pzarchitect') . '</li>
                        <li>' . __('Change <em>Limit panels (content)</em> to no so we get a lot of posts', 'pzarchitect') . '</li>
                        <li>' . __('Click the <em>Panels Content</em> button and for the <em>Settings, Content Source</em>, choose <em>Posts</em>', 'pzarchitect') . '</li>
                        <li>' . __('Click <em>Publish/Update</em>.', 'pzarchitect') . '</li>
                        </ol>
                    <li><strong>' . __('Display the Blueprint', 'pzarchitect') . '</strong></li>
                    <ol style="list-style-type:lower-roman">
                        <li>' . __('If you are using <strong>Headway</strong>, then go to the Headway Visual Editor, select a layout to show and draw an Architect block on it and select the Blueprint and Save.', 'pzarchitect') . '<br>
                        ' . __('For <strong>other themes</strong>, the quickest way to test is insert an Architect shortcode on a page.', 'pzarchitect') . '<br>' . __('The form is <strong>[architect <em>blueprint-shortname</em>]</strong> where <em>blueprint-shortname</em> is the Short Name of the Blueprint to show', 'pzarchitect') . '
                        </li>
                        <li>' . __('Load the page and you should see a 3x grid of posts.', 'pzarchitect') . '</li></ol>
                        </ol>
                        </div>
                        <h3>' . __('Video version') . '</h3>
                        <p><a href="//fast.wistia.net/embed/iframe/46fxmn8h0l?popover=true" class="wistia-popover[height=405,playerColor=7b796a,width=720]"><img src="' . PZARC_DOCUMENTATION_URL . '/assets/images/quick-start.jpg' . '" alt="' . __('Building and Displaying Your First Architect Project', 'pzarchitect') . '"></a>
<script charset="ISO-8859-1" src="//fast.wistia.com/assets/external/popover-v1.js"></script></p>
<p>' . __('Style wise, it may not look that great yet. To tidy it up, start exploring the Styling settings for Panels and Blueprints', 'pzarchitect') . '</p>
                        <p>' . __('There are a lot of settings in Architect that have all sorts of affects on your layouts and designs. Explore, experiment and have fun!', 'pzarchitect') . '</p>
                        <p>' . __('For more detailed help, visit', 'pzarchitect') . ' <a href="http://architect4wp.com/codex-listings" target="_blank">' . __('documentation at architect4wp.com', 'pzarchitect') . '</a></p>
                </div>
                <div class="tabs-pane" id="what">
                    <h2>' . __('What is Architect', 'pzarchitect') . '</h2>
                    <p>' . __('Is it a slider? Is it a gallery? Is it a grid layout? Yes! It\'s all these and more.', 'pzarchitect') . '</p>
                    <p>' . __('Fed up with a plethora of plugins that all seem to do the same thing, but in different ways? Me too. That\'s why I created Architect. I was guilty too. I had four plugins: ExcerptsPlus, GalleryPlus, SliderPlus and TabsPlus providing four different ways to display your content.', 'pzarchitect') . '</p>
                    <p>' . __('Architect enables you to easily design complex content layouts, such as magazine layouts, sliders, galleries and tabbed content.', 'pzarchitect') . '</p>
                    <p>' . __('And probably the most amazing thing... with Architect, your layouts are transportable. Change your theme without losing your content layouts. And they\'ll even pick up a lot of the formatting of your new theme if it uses standard WordPress classes although, you may need to tweak the styling a little for different themes.', 'pzarchitect') . '</p>

                    <p>' . __('At first it might be a little confusing about what to setup in Panels and what to do in Blueprints. Here\'s an overview:', 'pzarchitect') . '</p>
                    <p><img src="' . PZARC_PLUGIN_URL . '/documentation/assets/images/how-architect-works.jpg" style="display:block;max-width:100%;" />
                    </p>

                    <h3>' . __('Panels') . '</h3>
                    <ul>
                        <li>' . __('Panels define the layout of the individual content which can be displayed one or many times in a layout. Panels can also be re-used in multiple Blueprints', 'pzarchitect') . '</li>
                    </ul>
                    <ul>
                        <li>' . __('Individual content layout - titles, text, images, meta info', 'pzarchitect') . '</li>
                        <li>' . __('Content styling', 'pzarchitect') . '</li>
                    </ul>
                    <h3>' . __('Blueprints', 'pzarchitect') . '</h3>
                    <ul>
                        <li>' . __('A Blueprint encompasses the overall content selection, design, layout and navigation. It can contain up to three Sections, each section displaying a Panel layout one or multiple times. This allows you to easily create a layout that, for example, might show a single post followed by a grid of excerpts. Within the Blueprint you can also include navigation, which can be pagination type, or a navigator type.', 'pzarchitect') . '</li>
                    </ul>
                    <ul>
                        <li>' . __('Overall layout', 'pzarchitect') . '</li>
                        <li>' . __('content source', 'pzarchitect') . '</li>
                        <li>' . __('Navigation', 'pzarchitect') . '</li>
                    </ul>
                    <p>' . __('Below is a wireframe example of how a Blueprint is structured', 'pzarchitect') . '</p>
                    <p><img src="' . PZARC_PLUGIN_APP_URL . '/shared/assets/images/help/arc-layout.jpg" style="display:block;max-width:100%" />
                    </p>
                    <p><strong>' . __('Note: A Blueprint cannot have multiple content selections', 'pzarchitect') . '</strong></p>
                </div>
                <div class="tabs-pane " id="how">
                    <h2>' . __('Usage') . '</h2>

                    <h3>' . __('Shortcode', 'pzarchitect') . '</h3>
                    <p>' . __('For example, using shortcodes, youuse any of the following formats:', 'pzarchitect') . '</p>
                    <p><strong>[architect ' . __('blog-page-layout') . ']</strong></p>
                    <p><strong>[architect blueprint="' . __('blog-page-layout') . '"]</strong></p>
                    <p><strong>[architect blueprint="' . __('thumb-gallery') . '" ids="321,456,987,123,654,789"]</strong></p>
                    <p>' . __('ids are the specific post, page etc IDs and are used to override the defined selection for the Blueprint', 'pzarchitect') . '</p>

                    <h3>' . __('Template tag', 'pzarchitect') . '</h3>
                    <p>' . __('Template tags are inserted in your page templates and the fiurst parameter is the Blueprint short name, and the optional second one is a list of IDs to override the defaults.', 'pzarchitect') . '</p>
                    <p><strong>pzarchitect(\'' . __('blog-page-layout') . '\')</strong></p>
                    <p><strong>pzarchitect(\'' . __('thumb-gallery') . '\', \'321,456,987,123,654,789\')</strong></p>
                    <h3>' . __('Widget', 'pzarchitect') . '</h3>
                    Add the Architect widgets through the WP > Appearance > Widgets screen
                    <h3>' . __('Headway Block', 'pzarchitect') . '</h3>
                    Add the Architect Headway blocks in the Headway Visual Editor
                    <h3>' . __('Action Hooks', 'pzarchitect') . '</h3>
                    <p>If your them had action hooks, you can hook specific Blueprints to them in your functions.php</p>
                        <p>To use, add this code to your functions.php:</p>
                        <pre><code>new showBlueprint(’action’, ’blueprint’, ’pageids’);</code></pre>
    <p>action = Action hook to hook into</p>
    <p>blueprint = Blueprint short name to display</p>
    <p>pageids = Override IDs</p>

                    <h3>' . __('Actions Editor', 'pzarchitect') . '</h3>

                    <h3>' . __('Page builder', 'pzarchitect') . '</h3>
                    <h3>' . __('WP Gallery Shortcode Override', 'pzarchitect') . '</h3>
                </div>
                <div class="tabs-pane " id="help">
                    <h2>' . __('Support') . '</h2>
                    <h4>' . __('Currently installed version') . ': ' . PZARC_VERSION . '</h4>
          <script type="text/javascript" src="http://assets.freshdesk.com/widget/freshwidget.js"></script>
            <style type="text/css" media="screen, projection">
        @import url(http://assets.freshdesk.com/widget/freshwidget.css);
            </style>
            <iframe class="freshwidget-embedded-form" id="freshwidget-embedded-form" src="https://pizazzwp.freshdesk.com/widgets/feedback_widget/new?&widgetType=embedded&formTitle=Submit+a+help+request&screenshot=no&searchArea=no" scrolling="no" height="850px" width="90%" frameborder="0"  style="margin:20px 10px 10px 40px;background:#eee;overflow-y: auto;">
            </iframe>

                </div>
                <div class="tabs-pane " id="shout">
                    <h2>' . __('Shoutouts', 'pzarchitect') . '</h2>
                    <p>' . __('A lot of the magic in Architect is powered by third-party code libraries who deserve much credit for the awesomeness they bring to Architect:', 'pzarchitect') . '</p>
                    <ul class="shoutout">
                        <li><a href="http://reduxframework.com" target=_blank alt="Redux Options Framework">Redux Options Framework</a>
                        </li>
                        <li><a href="https://github.com/tommcfarlin/WordPress-Plugin-Boilerplate" target="_blank">Tom McPharlin\'s WP Plugin Boilerplate</a>
                        </li>
                        <li><a href="http://kenwheeler.github.io/slick/" target="_blank">Slick JS</a>
                        </li>
                        <li><a href="http://bgrins.github.io/spectrum/" target="_blank">Spectrum JS</a>
                        </li>
                        <li><a href="http://www.datatables.net/" target="_blank">DataTables JS</a>
                        </li>
                        <li><a href="http://isotope.metafizzy.co/" target="_blank">Isotope JS</a>
                        </li>
                        <li><a href="http://dimsemenov.com/plugins/magnific-popup/" target="_blank">Magnific JS</a>
                        </li>
                        <li><a href="http://jqueryui.com/" target="_blank">jQueryUI</a>
                        </li>
                        <li><a href="http://webcloud.se/jQuery-Collapse/" target="_blank">jQuery Collapse</a>
                        </li>
                        <li><a href="https://github.com/fzaninotto/Faker" target="_blank">PHP Faker</a>
                        </li>
                        <li><a href="http://tinsology.net/scripts/php-lorem-ipsum-generator/" target="_blank">PHP Lorem Ipsum</a>
                        </li>
                        <li><a href="https://github.com/bfintal/bfi_thumb" target="_blank">BFI Thumbs (modded)</a>
                        </li>
                    </ul>
                </div>
                <div class="tabs-pane " id="presets">
                    <h2>' . __('Presets') . '</h2>
                    <p>' . __('Right click and save to download preset ', 'pzarchitect') . '<a href="' . PZARC_PLUGIN_PRESETS_URL . 'architectexamples.xml">' . __('Panels and Blueprints', 'pzarchitect') . '</a>. ' . __('And then add them using <em>WP > Tools > Import</em> to add them to your site.', 'pzarchitect') . '</p>
                </div>

            </div>


        </div>


      </div>';
    }


  }

// end pzarcAdmin


  $pzarcadmin = new ArchitectAdmin();


  /*
   * Function creates post duplicate as a draft and redirects then to the edit post screen
   */
  function pzarc_duplicate_post_as_draft()
  {
    global $wpdb;
    if (!(isset($_GET[ 'post' ]) || isset($_POST[ 'post' ]) || (isset($_REQUEST[ 'action' ]) && 'pzarc_duplicate_post_as_draft' == $_REQUEST[ 'action' ]))) {
      wp_die(__('No post to duplicate has been supplied!', 'pzarchitect'));
    }

    /*
     * get the original post id
     */
    $post_id = (isset($_GET[ 'post' ]) ? $_GET[ 'post' ] : $_POST[ 'post' ]);
    /*
     * and all the original post data then
     */
    $post = get_post($post_id);

    /*
     * if you don't want current user to be the new post author,
     * then change next couple of lines to this: $new_post_author = $post->post_author;
     */
    $current_user    = wp_get_current_user();
    $new_post_author = $current_user->ID;

    /*
     * if post data exists, create the post duplicate
     */
    if (isset($post) && $post != null) {


      // Get the next slug name
      $post_exists = array();
      $args        = array(
          'post_status' => array('publish', 'draft'),
          'post_type'   => $post->post_type,
      );
      $i           = 1;
      do {
        $new_slug       = $post->post_name . '-' . $i++;
        $args[ 'name' ] = $new_slug;
        $post_exists    = get_posts($args);
      } while (!empty($post_exists));


      /*
       * new post data array
       */
      $args = array(
          'comment_status' => $post->comment_status,
          'ping_status'    => $post->ping_status,
          'post_author'    => $new_post_author,
          'post_content'   => $post->post_content,
          'post_excerpt'   => $post->post_excerpt,
          'post_name'      => $new_slug,
          'post_parent'    => $post->post_parent,
          'post_password'  => $post->post_password,
          'post_status'    => 'draft',
          'post_title'     => __('(DUPLICATE) ', 'pzarchitect') . $post->post_title,
          'post_type'      => $post->post_type,
          'to_ping'        => $post->to_ping,
          'menu_order'     => $post->menu_order
      );

      /*
       * insert the post by wp_insert_post() function
       */
      $new_post_id = wp_insert_post($args);

      /*
       * get all current post terms ad set them to the new post draft
       */
      $taxonomies = get_object_taxonomies($post->post_type); // returns array of taxonomy names for post type, ex array("category", "post_tag");
      foreach ($taxonomies as $taxonomy) {
        $post_terms = wp_get_object_terms($post_id, $taxonomy, array('fields' => 'slugs'));
        wp_set_object_terms($new_post_id, $post_terms, $taxonomy, false);
      }

      /*
       * duplicate all post meta
       */
      $post_meta_infos = $wpdb->get_results("SELECT meta_key, meta_value FROM $wpdb->postmeta WHERE post_id=$post_id");
      if (count($post_meta_infos) != 0) {
        $sql_query = "INSERT INTO $wpdb->postmeta (post_id, meta_key, meta_value) ";
        foreach ($post_meta_infos as $meta_info) {
          $meta_key = $meta_info->meta_key;
          if ($meta_key === '_panels_settings_short-name' || $meta_key === '_blueprints_short-name') {
            $meta_value = '';
          } else {
            $meta_value = addslashes($meta_info->meta_value);
          }

          $sql_query_sel[ ] = "SELECT $new_post_id, '$meta_key', '$meta_value'";
        }
        $sql_query .= implode(" UNION ALL ", $sql_query_sel);
        $wpdb->query($sql_query);
      }


      /*
       * finally, redirect to the edit post screen for the new draft
       */
//      wp_redirect( admin_url( 'post.php?action=edit&post=' . $new_post_id ) );
      wp_redirect(admin_url('edit.php?post_type=' . $post->post_type));

      exit;
    } else {
      wp_die('Post creation failed, could not find original post: ' . $post_id);
    }
  }

  add_action('admin_action_pzarc_duplicate_post_as_draft', 'pzarc_duplicate_post_as_draft');

  /*
   * Add the duplicate link to action list for post_row_actions
   */
  function pzarc_duplicate_post_link($actions, $post)
  {
    if (current_user_can('edit_posts')) {
      $actions[ 'duplicate' ] = '<a href="admin.php?action=pzarc_duplicate_post_as_draft&amp;post=' . $post->ID . '" title="' . __('Duplicate this item', 'pzarchitect') . '" rel="permalink">' . __('Duplicate', 'pzarchitect') . '</a>';
    }

    return $actions;
  }

  //  add_filter( 'arc-panels_row_actions', 'pzarc_duplicate_post_link', 10, 2 );
  //  add_filter( 'arc-blueprints_row_actions', 'pzarc_duplicate_post_link', 10, 2 );
  //  add_filter( 'pz_snippets_row_actions', 'pzarc_duplicate_post_link', 10, 2 );

  function pzarc_about()
  {
    global $title;

    echo '<div class = "wrap">

			<!--Display Plugin Icon, Header, and Description-->
			<div class = "icon32" id = "icon-users"><br></div>
        <div class="pzarc-about-box" style="background:#f9f9f9;padding:20px;border:1px solid #ddd;">

			<h2>' . $title . '</h2>
			<h3>Architect is installed but not usable accessible WP Admin until Redux is installed/activated.</h3>
        <script type="text/javascript" src="http://assets.freshdesk.com/widget/freshwidget.js"></script>
            <style type="text/css" media="screen, projection">
      @import url(http://assets.freshdesk.com/widget/freshwidget.css);
            </style>
            <iframe class="freshwidget-embedded-form" id="freshwidget-embedded-form" src="https://pizazzwp.freshdesk.com/widgets/feedback_widget/new?&widgetType=embedded&formTitle=Submit+a+help+request&screenshot=no&searchArea=no" scrolling="no" height="850px" width="90%" frameborder="0"  style="margin:20px 10px 10px 40px;background:#eee;overflow-y: auto;">
            </iframe>

			</div></div>';

  }
