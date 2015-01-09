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

    function missing_redux_admin_notice()
    {
      echo '<div id="message" class="error"><p><strong>'.__('One final step in installing ARCHITECT.').'</strong><br>'.__('It cannot function without the Redux Framework plugin. You need to install and/or activate Redux.').'<br>'.__('Redux is the backbone of Architect, providing all the necessary code libraries for Architect\'s fields and options.').'<br>'.__('There should be another message with a link to make installing and activating Redux easy. If you can\'t find it, contact PizazzWP support.').'</p></div>';
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

        wp_enqueue_script('jquery-pzarc-metaboxes', PZARC_PLUGIN_APP_URL . '/admin/js/arc-metaboxes.js', array('jquery'),false);


        // We shouldn't need this anymore

//        wp_enqueue_script('pzarc-validation-engine-js-lang', PZARC_PLUGIN_APP_URL . '/shared/includes/js/jQuery-Validation-Engine/js/languages/jquery.validationEngine-en.js', array('jquery'));
//        wp_enqueue_script('pzarc-validation-engine-js', PZARC_PLUGIN_APP_URL . '/shared/includes/js/jQuery-Validation-Engine/js/jquery.validationEngine.js', array('jquery'));
//        wp_enqueue_style('pzarc-validation-engine-css', PZARC_PLUGIN_APP_URL . '/shared/includes/js/jQuery-Validation-Engine/css/validationEngine.jquery.css');
      }
      if ('architect_page_pzarc_support'===$screen->id) {
        wp_enqueue_script('js-classlist', PZARC_PLUGIN_APP_URL . '/shared/includes/js/tabby/dist/js/classList.min.js', array('jquery'));
        wp_enqueue_script('js-tabby', PZARC_PLUGIN_APP_URL . '/shared/includes/js/tabby/dist/js/tabby.min.js', array('jquery'));
        wp_enqueue_style('css-tabby', PZARC_PLUGIN_APP_URL . '/shared/includes/js/tabby/dist/css/tabby.min.css');
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
            'pzarc', 'Help & Support', '<span class="dashicons dashicons-editor-help size-small"></span>Help & Support', 'manage_options', 'pzarc_support', array($this,
                                                                                                                                                                  'pzarc_support')
        );

        global $submenu;
        // Shift those last  to the top
        array_unshift($submenu[ 'pzarc' ], array_pop($submenu[ 'pzarc' ]));
      }

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
						<h3>'.__('Rebuild Architect CSS cache').'</h3>
						<p>'.__('Sometimes the CSS cache file may not exist or may even become scrambled and layouts will not look right. If so, simply click the Rebuild button and it will be recreated. If the problem persists, contact Pizazz Support at support@pizazzwp.com.').'</p>
						<form action="admin.php?page=pzarc_tools" method="post">';
      wp_nonce_field('rebuild-architect-css-cache');
      echo '<button class="button-primary" type="submit" name="rebuildarchitectcss" value="'.__('Rebuild Architect CSS Cache').'"><span class="dashicons dashicons-admin-appearance" style="color:white;font-size:22px;vertical-align:text-bottom"></span>'.__('Rebuild Architect CSS Cache').'</button>
        </form>';

      if (isset($_POST[ 'rebuildarchitectcss' ]) && check_admin_referer('rebuild-architect-css-cache')) {
        require_once(PZARC_PLUGIN_APP_PATH . '/admin/php/arc-save-process.php');
        save_arc_layouts('all', null, true);
        echo '<br><div id="message" class="updated"><p>'.__('Architect CSS cache has been rebuilt. Your site should look awesome again!').'</p>
        <p>'.__('If your site is using a cache plugin or service, clear that cache too.').'</p></div>';
      }

      echo '<hr style="margin-top:20px;border-color:#eee;border-style:solid;"/>';
      if (function_exists('bfi_flush_image_cache')) {
        echo '<h3>'.__('Clear Architect images cache').'</h3>

    <p>'.__('If you update or change images in any posts,sometimes the image cache may get out-of-sync. In that case, you can refresh the thumbs image cache to ensure your site visitors are seeing the correct images.').'</p>

    <p>'.__('Please note: Refreshing the cache causes no problems other than the next person who visits your site may have to wait a little longer as the cache images get recreated.').' <strong>'.__('No images in any post will be affected').'</strong>. </p>

    <form action="admin.php?page=pzarc_tools" method="post">';
        wp_nonce_field('flush-thumb-cache');
        echo '<button class="button-primary" type="submit" name="flushbficache" value="'.__('Empty Architect image cache').'"><span class="dashicons dashicons-images-alt2" style="color:white;font-size:22px;vertical-align:text-bottom"></span>'.__('Empty Architect image cache').'</button>
    </form>
    <hr style="margin-top:20px;border-color:#eee;border-style:solid;"/>';
        if (isset($_POST[ 'flushbficache' ]) && check_admin_referer('flush-thumb-cache')) {
          bfi_flush_image_cache();
          echo '<div id="message" class="updated"><p>'.__('Architect image cache cleared. It will be recreated next time someone vists your site.').'</p></div>';
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
                <button data-tab="#quick" class="first active">'.__('Quick start').'</button>
                <button data-tab="#what" >'.__('What is Architect').'</button>
                <button data-tab="#how">'.__('Usage').'</button>
                <button data-tab="#help">'.__('Support').'</button>
                <button data-tab="#shout">'.__(''.__('Shoutouts').'').'</button>
                <button data-tab="#presets">'.__('Presets').'</button>
            </div>
            <div class="tabby tabs-content">
                <div class="tabs-pane active" id="quick">
                    <h2>'.__('Quick start').'</h2>
                    <div style="background:#f2f2f2;border:1px solid #e2e2e2;padding:10px;border-radius:3px;max-width:800px;font-size:14px;">
                    <ol>
                    <li><strong>'.__('Create a Panel').'</strong></li>
                    <ol style="list-style-type:lower-roman"><li>'.__('Go to <em>Architect > Panels</em> and create a basic Panel. Make sure to give it a title and a short name.').'</li>
                        <li>'.__('Leave all the other defaults for now. <em>Publish/Update</em> that.').'</li></ol>
                    <li><strong>'.__('Create a Blueprint').'</strong></li>
                    <ol style="list-style-type:lower-roman">
                        <li>'.__('Go to <em>Architect > Blueprints</em> and create a Blueprint. Give it a <em>Title</em> and <em>Short Name</em> too, and in <em>Section 1</em> tab, under <em>Panels Layout</em>, select your Panel you just created').'</li>
                        <li>'.__('Change <em>Limit panels (content)</em> to no so we get a lot of posts').'</li>
                        <li>'.__('Click the <em>Panels Content</em> button and for the <em>Settings, Content Source</em>, choose <em>Posts</em>').'</li>
                        <li>'.__('Click <em>Publish/Update</em>.').'</li>
                        </ol>
                    <li><strong>'.__('Display the Blueprint').'</strong></li>
                    <ol style="list-style-type:lower-roman">
                        <li>'.__('If you are using <strong>Headway</strong>, then go to the Headway Visual Editor, select a layout to show and draw an Architect block on it and select the Blueprint and Save.').'<br>
                        '.__('For <strong>other themes</strong>, the quickest way to test is insert an Architect shortcode on a page.').'<br>'.__('The form is <strong>[architect <em>blueprint-shortname</em>]</strong> where <em>blueprint-shortname</em> is the Short Name of the Blueprint to show').'
                        </li>
                        <li>'.__('Load the page and you should see a 3x grid of posts.').'</li></ol>
                        </ol>
                        </div>
                        <h3>'.__('Video version').'</h3>
                        <div style="max-width:800px;"><iframe src="//fast.wistia.net/embed/iframe/46fxmn8h0l?videoFoam=true" allowtransparency="true" frameborder="0" scrolling="no" class="wistia_embed" name="wistia_embed" allowfullscreen mozallowfullscreen webkitallowfullscreen oallowfullscreen msallowfullscreen width="720" height="405"></iframe><script src="//fast.wistia.net/assets/external/iframe-api-v1.js"></script></div>
                        <p>'.__('Style wise, it may not look that great yet. To tidy it up, start exploring the Styling settings for Panels and Blueprints').'</p>
                        <p>'.__('To make a <strong>slideshow</strong>, set the <em>Navigation</em> type to <em>Navigator</em>').'</p>
                        <p>'.__('There are a lot of settings in Architect that have all sorts of affects on your layouts and designs. Explore, experiment and have fun!').'</p>
                        <p>'.__('For more detailed help, visit').' <a href="http://architect4wp.com/codex-listings" target="_blank">'.__('documentation at architect4wp.com').'</a></p>
                </div>
                <div class="tabs-pane" id="what">
                    <h2>'.__('What is Architect').'</h2>
                    <p>'.__('Is it a slider? Is it a gallery? Is it a grid layout? Yes! It\'s all these and more.').'</p>
                    <p>'.__('Fed up with a plethora of plugins that all seem to do the same thing, but in different ways? Me too. That\'s why I created Architect. I was guilty too. I had four plugins: ExcerptsPlus, GalleryPlus, SliderPlus and TabsPlus providing four different ways to display your content.').'</p>
                    <p>'.__('Architect enables you to easily design complex content layouts, such as magazine layouts, sliders, galleries and tabbed content.').'</p>
                    <p>'.__('And probably the most amazing thing... with Architect, your layouts are transportable. Change your theme without losing your content layouts. And they\'ll even pick up a lot of the formatting of your new theme if it uses standard WordPress classes although, you may need to tweak the styling a little for different themes.').'</p>

                    <p>'.__('At first it might be a little confusing about what to setup in Panels and what to do in Blueprints. Here\'s an overview:').'</p>
                    <p><img src="' . PZARC_PLUGIN_URL . '/documentation/assets/images/how-architect-works.jpg" style="display:block;max-width:100%;" />
                    </p>

                    <h3>'.__('Panels').'</h3>
                    <ul>
                        <li>'.__('Panels define the layout of the individual content which can be displayed one or many times in a layout. Panels can also be re-used in multiple Blueprints').'</li>
                    </ul>
                    <ul>
                        <li>'.__('Individual content layout - titles, text, images, meta info').'</li>
                        <li>'.__('Content styling').'</li>
                    </ul>
                    <h3>'.__('Blueprints').'</h3>
                    <ul>
                        <li>'.__('A Blueprint encompasses the overall content selection, design, layout and navigation. It can contain up to three Sections, each section displaying a Panel layout one or multiple times. This allows you to easily create a layout that, for example, might show a single post followed by a grid of excerpts. Within the Blueprint you can also include navigation, which can be pagination type, or a navigator type.').'</li>
                    </ul>
                    <ul>
                        <li>'.__('Overall layout').'</li>
                        <li>'.__('content source').'</li>
                        <li>'.__('Navigation').'</li>
                    </ul>
                    <p>'.__('Below is a wireframe example of how a Blueprint is structured').'</p>
                    <p><img src="' . PZARC_PLUGIN_APP_URL . '/shared/assets/images/help/arc-layout.jpg" style="display:block;max-width:100%" />
                    </p>
                </div>
                <div class="tabs-pane " id="how">
                    <h2>'.__('Usage').'</h2>

                    <p>'.__('For example, using shortcodes, you might have:').'</p>
                    <p style="font-weight:bold">[architect blueprint="'.__('blog-page-layout').'"]</p>
                    <p style="font-weight:bold">[architect blueprint="'.__('thumb-gallery').'" ids="321,456,987,123,654,789"]</p>

                    <p>Or a template tag</p>
                    <p style="font-weight:bold">pzarchitect(\''.__('blog-page-layout').'\')</p>
                    <p style="font-weight:bold">pzarchitect(\''.__('thumb-gallery').'\', \'321,456,987,123,654,789\')</p>
                </div>
                <div class="tabs-pane " id="help">
                    <h2>'.__('Support').'</h2>
                    <h4>'.__('Currently installed version').': ' . PZARC_VERSION . '</h4>
                </div>
                <div class="tabs-pane " id="shout">
                    <h2>'.__('Shoutouts').'</h2>
                    <p>'.__('A lot of the magic in Architect is powered by third-party code libraries who deserve much credit for the awesomeness they bring to Architect:').'</p>
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
                    <h2>'.__('Presets').'</h2>
                    This is still to to!
                    <p>Download preset Panels and Blueprints. <a href="#">Link</a></p>
                </div>

            </div>


        </div>


      </div>';
    }


  }

// end pzarcAdmin


  $pzarcadmin = new ArchitectAdmin();

