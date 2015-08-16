<?php

  $redux_opt_name = '_architect';
  function pzarc_removeReduxDemoModeLink()
  { // Be sure to rename this function to something  unique
    if (class_exists('ReduxFrameworkPlugin')) {
      remove_filter('plugin_row_meta', array(ReduxFrameworkPlugin::get_instance(), 'plugin_metalinks'), null, 2);
    }
    if (class_exists('ReduxFrameworkPlugin')) {
      remove_action('admin_notices', array(ReduxFrameworkPlugin::get_instance(), 'admin_notices'));
    }
  }

  add_action('init', 'pzarc_removeReduxDemoModeLink');


  /*********************************************
   * Class ArchitectAdmin
   */
  class ArchitectAdmin
  {

    /*********************************************
     *
     */
    function __construct()
    {
      /*
       * Create the layouts custom post type
       */
      global $arc_presets_data;
//      add_action('plugins_loaded', array($this, 'init'));
      add_action('plugins_loaded', array($this, 'init'));

      add_action('after_setup_theme', 'pzarc_initiate_updater');


    }

    /*********************************************
     *
     */
    function init()
    {
      // @TODO: verify this blocks non admins!
      if (!is_admin() || !current_user_can('edit_others_pages')) {
        return;
      }
      if (!class_exists('SysInfo')) {
        require_once(PZARC_PLUGIN_APP_PATH . '/shared/thirdparty/php/WordPress-SysInfo/sysinfo.php');
      }
      if (!(class_exists('ReduxFramework') || class_exists('ReduxFrameworkPlugin'))) {
        add_action('admin_notices', array($this, 'missing_redux_admin_notice'));

        // TODO: Add an alternativeArchitect Admin screen.
        add_action('admin_menu', array($this, 'admin_menu_no_redux'));
        require_once(PZARC_PLUGIN_APP_PATH . '/shared/thirdparty/php/BFI-thumb-forked/BFI_Thumb.php');
        require_once(PZARC_PLUGIN_APP_PATH . '/shared/thirdparty/php/pzwp-focal-point/pzwp-focal-point.php');

        return;
      } else {
        add_action('admin_head', array($this, 'admin_head'));
        add_action('admin_menu', array($this, 'admin_menu'));

        add_action('admin_enqueue_scripts', array($this, 'admin_enqueue'));
        add_filter('admin_body_class', array(&$this, 'add_admin_body_class'));


        // TODO: Make up some easily editable panel defs - prob have to be a custom content type
        //       require_once PZARC_PLUGIN_PATH . '/admin/php/arc-options-def-editor.php';

        //@TODO: need a bit of screen dependency on this?
        require_once(PZARC_PLUGIN_APP_PATH . 'admin/php/class_arcBuilderAdmin.php');

        require_once PZARC_PLUGIN_APP_PATH . 'admin/php/class_arc_blueprints_designer.php';
        require_once PZARC_PLUGIN_APP_PATH . 'admin/php/arc-save-process.php';


        //TODO:     require_once PZARC_PLUGIN_PATH . '/admin/arc-widget.php';

        // This one is really only needed on posts, pages and snippets, so could conditionalise its load
        require_once PZARC_PLUGIN_APP_PATH . 'admin/php/class_arc_misc_metaboxes.php';
        require_once PZARC_PLUGIN_APP_PATH . 'shared/thirdparty/php/redux-custom-fields/loader.php';
        require_once PZARC_PLUGIN_APP_PATH . 'shared/thirdparty/php/redux-extensions/loader.php';

        $misc_metaboxes = new arc_Misc_metaboxes();
//        $panel_layout      = new arc_Panels_Layouts();
        $content_blueprint = new arc_Blueprints_Designer();

        require_once PZARC_PLUGIN_APP_PATH . 'admin/php/arc-options.php';
        require_once PZARC_PLUGIN_APP_PATH . 'admin/php/arc-options-styling.php';
        require_once PZARC_PLUGIN_APP_PATH . 'admin/php/arc-options-actions.php';

        // TODO: this needs to be dumberized so can work on dev defined panels and content. But why is these here anyway??
//      require_once PZARC_PLUGIN_APP_PATH . '/shared/architect/php/content-types/generic/class_arc_panel_generic.php';


        require_once(PZARC_PLUGIN_APP_PATH . '/shared/thirdparty/php/BFI-thumb-forked/BFI_Thumb.php');
        require_once(PZARC_PLUGIN_APP_PATH . '/shared/thirdparty/php/pzwp-focal-point/pzwp-focal-point.php');

      }

    }

    /*********************************************
     *
     */
    function missing_redux_admin_notice()
    {
      echo '<div id="message" class="error"><h3>' . __('Architect requires Redux Framework', 'pzarchitect') . '</h3><p><strong>' . __('One final step in installing Architect.', 'pzarchitect') . '</strong><br>' . __('It cannot function without the Redux Framework plugin. You need to install and/or activate Redux.', 'pzarchitect') . '<br>' . __('Redux is the backbone of Architect, providing all the necessary code libraries for Architect\'s fields and options.', 'pzarchitect') . '<br>' . __('There should be another message with a link to make installing and activating Redux easy. If you can\'t find it, contact PizazzWP support.', 'pzarchitect') . '</p></div>';
    }


    /*********************************************
     * @param $classes
     *
     * @return string
     */
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

      global $_architect_options;
      if (!empty($_architect_options[ 'architect_show_advanced' ])) {
        $classes .= ' arc-advanced';
      } else {
        $classes .= ' arc-beginner';
      }

      return $classes;
    }


    /*********************************************
     * @param $hook
     */
    function admin_enqueue($hook)
    {
      wp_enqueue_style('pzarc-admin-styles');
//    wp_enqueue_style('pzarc-jqueryui-css');

      wp_register_script('jquery-cookie', PZARC_PLUGIN_APP_URL . 'shared/thirdparty/js/jquery-cookie/jquery.cookie.js', array('jquery'), true);
      wp_register_script('jquery-pageguide', PZARC_PLUGIN_APP_URL . 'shared/thirdparty/js/pageguide/pageguide.min.js', array('jquery'), true);
      wp_register_style('css-pageguide', PZARC_PLUGIN_APP_URL . 'shared/thirdparty/js/pageguide/css/pageguide.css');

      $screen = get_current_screen();
      if (strpos(('X' . $screen->id), 'arc-') > 0) {
//			wp_enqueue_script( 'jquery-ui-tabs' );
//			wp_enqueue_script( 'jquery-ui-button' );

//      wp_enqueue_script('jquerui');
        wp_enqueue_style('dashicons');

//      wp_enqueue_style('pzarc-block-css', PZARC_PLUGIN_URL . '/admin/css/arc-admin.css');

        // We shouldn't need this anymore
//        wp_enqueue_style('pzarc-jqueryui-css', PZARC_PLUGIN_APP_URL . '/shared/thirdparty/js/jquery-ui-1.10.2.custom/css/pz_architect/jquery-ui-1.10.2.custom.min.css');

        wp_enqueue_script('jquery-pzarc-metaboxes', PZARC_PLUGIN_APP_URL . '/admin/js/arc-metaboxes.js', array('jquery'), true);
//        wp_enqueue_script( 'js-validator-arc', PZARC_PLUGIN_APP_URL . '/shared/thirdparty/js/jQuery-Form-Validator/form-validator/jquery.form-validator.min.js', array( 'jquery' ), true );


        // We shouldn't need this anymore

//        wp_enqueue_script('pzarc-validation-engine-js-lang', PZARC_PLUGIN_APP_URL . '/shared/thirdparty/js/jQuery-Validation-Engine/js/languages/jquery.validationEngine-en.js', array('jquery'));
//        wp_enqueue_script('pzarc-validation-engine-js', PZARC_PLUGIN_APP_URL . '/shared/thirdparty/js/jQuery-Validation-Engine/js/jquery.validationEngine.js', array('jquery'));
//        wp_enqueue_style('pzarc-validation-engine-css', PZARC_PLUGIN_APP_URL . '/shared/thirdparty/js/jQuery-Validation-Engine/css/validationEngine.jquery.css');
        add_filter('post_row_actions', 'pzarc_duplicate_post_link', 10, 2);
        add_filter('page_row_actions', 'pzarc_duplicate_post_link', 10, 2);
        add_filter('post_row_actions', 'pzarc_export_preset_link', 10, 3);
        add_filter('page_row_actions', 'pzarc_export_preset_link', 10, 3);
      }
      if ('architect-lite_page_pzarc_support' === $screen->id || 'architect_page_pzarc_support' === $screen->id || 'edit-arc-blueprints' === $screen->id) {
        wp_enqueue_script('js-classlist', PZARC_PLUGIN_APP_URL . '/shared/thirdparty/js/tabby/dist/js/classList.min.js', array('jquery'), true);
        wp_enqueue_script('js-tabby', PZARC_PLUGIN_APP_URL . '/shared/thirdparty/js/tabby/dist/js/tabby.min.js', array('jquery'), true);
        wp_enqueue_script('js-tabby-arc', PZARC_PLUGIN_APP_URL . '/admin/js/arc-tabby.js', array('jquery'), true);
        wp_enqueue_style('css-tabby', PZARC_PLUGIN_APP_URL . '/shared/thirdparty/js/tabby/dist/css/tabby.min.css');
      }

      switch ($screen->id) {
        case 'architect_page__architect_options':
        case 'architect_page__architect_styling':
        case 'architect_page__architect_actions_editor':
        case 'edit-arc-blueprints':
        case 'arc-blueprints':
        case 'architect_page_pzarc_tools':
        case 'architect_page_pzarc_about':

          global $_architect_options;
          if (!isset($GLOBALS[ '_architect_options' ])) {
            $GLOBALS[ '_architect_options' ] = get_option('_architect_options', array());
          }
//          if ( empty( $_architect_options[ 'architect_remove_support_button' ] ) ) {
//            wp_enqueue_script( 'js-freshdesk', 'http://assets.freshdesk.com/widget/freshwidget.js', false, true );
//            wp_enqueue_script( 'js-freshdesk-support', PZARC_PLUGIN_APP_URL . '/admin/js/freshdesk-support.js', false, true );
//
//          }
          break;
      }

      if ($screen->id === 'edit-arc-blueprints') {
        require_once(PZARC_DOCUMENTATION_PATH . PZARC_LANGUAGE . '/blueprints-listings-pageguide.php');
      }

//      if ($screen post ot page editor)
//      require_once (PZARC_PLUGIN_APP_PATH.'admin/php/arcMCEButtons.php');
    }

    /*********************************************
     *
     */
    function admin_menu()
    {
      global $pzarc_menu, $pizazzwp_updates;
      if (!$pzarc_menu) {
        //add_menu_page( $page_title,  $menu_title, $capability,   $menu_slug, $function,    $icon_url, $position );
        $pzarc_status        = get_option('edd_architect_license_status');
        $pzarc_current_theme = wp_get_theme();
        if (($pzarc_current_theme->get('Name') === 'Headway Base' || $pzarc_current_theme->get('Template') == 'headway')) {

          if (is_multisite()) {
            $hw_opts = get_blog_option(1, 'headway_option_group_general');
          } else {
            $hw_opts = get_option('headway_option_group_general');
          }
        }
        $vers       = ((!empty($hw_opts[ 'license-status-architect' ]) && $hw_opts[ 'license-status-architect' ] == 'valid') || $pzarc_status !== false && $pzarc_status == 'valid') ? '' : 'Lite';
        $pzarc_menu = add_menu_page(__('Getting started', 'pzarchitect'), 'Architect ' . $vers, 'edit_posts', 'pzarc', 'pzarc_about', PZARC_PLUGIN_APP_URL . 'wp-icon.png');
        // add_submenu_page( $parent_slug, $page_title, $menu_title, $capability, $menu_slug, $function );

        // Don't need this as it's carried in the layouts already
//			add_submenu_page(
//				'pzarc', 'Styling', 'Styling', 'manage_options', 'pzarc_styling', array( $this, 'pzarc_styling' )
//			);
        add_submenu_page(
            'pzarc', __('Tools', 'pzarchitect'), '<span class="dashicons dashicons-hammer size-small"></span>' . __('Tools', 'pzarchitect'), 'edit_others_pages', 'pzarc_tools', array(
                       $this,
                       'pzarc_tools'
                   )
        );
        add_submenu_page(
            'pzarc', __('Help & Support', 'pzarchitect'), '<span class="dashicons dashicons-editor-help size-small"></span>' . __('Help & Support', 'pzarchitect'), 'edit_others_pages', 'pzarc_support', array(
                       $this,
                       'pzarc_support'
                   )
        );

        global $submenu;
        // Shift those last  to the top
        array_unshift($submenu[ 'pzarc' ], array_pop($submenu[ 'pzarc' ]));
      }

    }

    /*********************************************
     *
     */
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

    /*********************************************
     *
     */
    function admin_head()
    {

    }


    /*********************************************
     *
     */
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
						';
      /**
       * Rebuild CSS
       */
      echo '
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

      /**
       * Clear image cache
       */
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
      /**
       * Import presets
       */
      if (defined('PZARC_PRO') && PZARC_PRO == true && wp_mkdir_p(PZARC_PRESETS_PATH)) {
        $pzarc_custom_presets = pzarc_tidy_dir(scandir(PZARC_PRESETS_PATH));
        // TODO: add existing presets with option to delete (custom) or hide (builtin)
        echo '<h3>' . __('Import Blueprint or Preset', 'pzarchitect') . '</h3>';
        if (count($pzarc_custom_presets)>0) {
          echo '<div class="arc-installed-presets"><h4>Currently installed additional Presets</h4>';
          echo '<ul>';
          foreach ($pzarc_custom_presets as $f) {
            if (is_dir(PZARC_PRESETS_PATH . '/' . $f)) {
              echo '<li>' . ucwords($f) . '</li>';
            }
          }
          echo '</ul></div>';
        }
        echo '<p>' . __('<strong>Blueprints</strong>: If you have a <strong>Blueprint</strong> in a .txt format, you may import it by uploading it here. The new Blueprint will then be opened ready for editing. Blueprint export files can be created from context menu on the Blueprint listing.') . '</p>';
        echo '<p>' . __('<strong>Presets</strong>: If you have a <strong>Preset</strong> in a .zip format, you may import it by uploading it here. It will then appear in the Blueprints, Preset Selector') . '</p>
      <div class="pzarc-upload-preset">
                        <form method="post" enctype="multipart/form-data" class="pzarc-preset-upload-form" action="">';
        wp_nonce_field('arc-preset-upload');
        echo '<label class="screen-reader-text" for="txtorzip">' . __('Select Blueprint .txt file or Preset zip file') . '</label>
                          <input type="file" id="txtorzip" name="txtorzip" />
                          ';
//      submit_button(__('Install Now'), 'button', 'install-blueprint-or-preset-submit', true);
        echo '<p><button class="button-primary"  style="min-width:100px;" type="submit" name="install-blueprint-or-preset-submit" value="' . __('Import', 'pzarchitect') . '">' . __('Import') . '  <span class="dashicons dashicons-id-alt" style="margin-left:1%;color:inherit;font-size:22px;vertical-align:text-bottom"></span></button></p>';
        echo '</form>
                      </div>';
        if (isset($_POST[ 'install-blueprint-or-preset-submit' ]) && check_admin_referer('arc-preset-upload')) {
          switch (true) {
            case ((substr($_FILES[ 'txtorzip' ][ 'name' ], -4, 4) === '.zip') && file_exists(PZARC_PRESETS_PATH . '/' . str_replace('.zip', '', $_FILES[ 'txtorzip' ][ 'name' ]))):
              echo '<div id="message" class="error"><p>' . __('A Preset with that name already exists.', 'pzarchitect') . '</p></div>';
              break;
            case (!empty($_FILES[ 'txtorzip' ][ 'name' ]) && (substr($_FILES[ 'txtorzip' ][ 'name' ], -4, 4) === '.zip')):
              pzarc_upload_file($_FILES[ 'txtorzip' ], 'preset');
              break;
            case (!empty($_FILES[ 'txtorzip' ][ 'name' ]) && (substr($_FILES[ 'txtorzip' ][ 'name' ], -4, 4) === '.txt')):
              var_dump($_FILES[ 'txtorzip' ][ 'name' ]);
              pzarc_upload_file($_FILES[ 'txtorzip' ], 'blueprint');
              // todo: add method of styled or unstyled
              break;
            case empty($_FILES[ 'txtorzip' ][ 'name' ]):
              echo '<div id="message" class="error"><p>' . __('No file specified.', 'pzarchitect') . '</p></div>';
              break;
            case (substr($_FILES[ 'txtorzip' ][ 'name' ], -4, 4) !== '.zip' && substr($_FILES[ 'txtorzip' ][ 'name' ], -4, 4) !== '.txt'):
              echo '<div id="message" class="error"><p>' . __('File must be a txt or zip file.', 'pzarchitect') . '</p></div>';
              break;
          }
        }
      }
      echo '</div><!--end table-->
			</div>
      ';
    }

    /*********************************************
     *
     */
    function pzarc_support()
    {
      global $title;
//      require_once(PZARC_DOCUMENTATION_PATH . PZARC_LANGUAGE . '/architect-pageguide.php');

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
            <div class="pzarc-help-section">
                        <a class="pzarc-button-help" href="http://architect4wp.com/codex-listings/" target="_blank">
                        <span class="dashicons dashicons-book"></span>
                        Documentation</a>&nbsp;
                        <a class="pzarc-button-help" href="https://pizazzwp.freshdesk.com/support/discussions" target="_blank">
                        <span class="dashicons dashicons-groups"></span>
                        Community support</a>&nbsp;
                        <a class="pzarc-button-help" href="https://pizazzwp.freshdesk.com/support/tickets/new" target="_blank">
                        <span class="dashicons dashicons-admin-tools"></span>
                        Tech support</a>
                        </div>

            ';

      $pzarc_status        = get_option('edd_architect_license_status');
      $pzarc_current_theme = wp_get_theme();
      if (($pzarc_current_theme->get('Name') === 'Headway Base' || $pzarc_current_theme->get('Template') == 'headway')) {

        if (is_multisite()) {
          $hw_opts = get_blog_option(1, 'headway_option_group_general');
        } else {
          $hw_opts = get_option('headway_option_group_general');
        }
      }

      $lite = ((!empty($hw_opts[ 'license-status-architect' ]) && $hw_opts[ 'license-status-architect' ] == 'valid') || $pzarc_status !== false && $pzarc_status == 'valid') ? false : true;

      if ($lite) {
        echo ' <div class="arc-info-boxes">
                    <div class="arc-info col1">';
        echo '<h3 style="color:#0074A2">Architect Lite</h3>
        <p class="arc-important" style="font-weight:bold;">You are running Architect without activating a licence, therefore it is in Lite mode. Cool features you are missing out on are: Animations and access to all content types, including Galleries, Snippets, NextGen, Testimonials and custom post types</p>
        <p style="font-weight:bold;">To get all the extra goodness of Architect, you can purchase it from the <a href="http://shop.pizazzwp.com/downloads/architect/" target="_blank">PizazzWP Shop</a></p>
</div></div>';
      }
      echo ' <div class="tabby tabs">
                <button class="tabby-quick first active" data-tab="#quick">' . __('Getting started', 'pzarchitect') . '</button>
                <button class="tabby-how" data-tab="#how">' . __('Usage', 'pzarchitect') . '</button>
                <button class="tabby-latest" data-tab="#latest">' . __('Latest news', 'pzarchitect') . '</button>
                <button class="tabby-help" data-tab="#help">' . __('Support', 'pzarchitect') . '</button>
                <button class="tabby-shout" data-tab="#shout">' . __('Shoutouts', 'pzarchitect') . '</button>
            </div>
            <div class="tabby tabs-content">

                <div class="tabs-pane active" id="quick">
                <div class="arc-info-boxes">
                <h2>Quick start</h2>
                <div class="arc-info col1">

                    <ol style="list-style-type:lower-roman">
                        <li>' . __('From the <em>Architect</em> > <em>Blueprints</em> listing, click the button that says <em>Create a new Blueprint from a Preset design</em>', 'pzarchitect') . '</li>
                        <li>' . __('Browse the various Presets and select one to use', 'pzarchitect') . '</li>
                        <li>' . __('To create a new Blueprint with the Preset\'s inbuilt styles, click <em>Use styled</em>', 'pzarchitect') . '</li>
                        <li>' . __('To create a new Blueprint without any inbuilt styles, click <em>Use unstyled</em>. Note, the Blueprint when dispalyed will inherit some styling from your theme.', 'pzarchitect') . '</li>
                        <li>' . __('Change the <em>Title</em> and <em>Blueprint Short name</em> to whatever is suitable', 'pzarchitect') . '</li>
                        <li>' . __('Click <em>Update</em> to save.', 'pzarchitect') . '</li>
                        <li>' . __('Within a WordPres page or post, add the shortcode <strong>[architect yourblueprint]</strong> where <em>yourblueprint</em> is the <em>Shortname</em> you gave the Blueprint.', 'pzarchitect') . '</li>
                        <li>' . __('Click <em>Update</em> to save and visit that page on your site to see your awesome Architect Blueprint displayed.', 'pzarchitect') . '</li>
                    </ol>
                    </div>
                  </div>
                    <h2>' . __('Overview') . '</h2>
                    <div class="arc-info-boxes">
                    <div class="arc-info col2"><h3><span class="dashicons dashicons-editor-help"></span>Help & Support</h3>
                    <p>This page! Provides a brief Quick start guide, an overview of the Architect menus and:<ul>
                    <li>Usage instructions for displaying your Blueprints</li>
                    <li>A form for submitting a tech support request</li>
                    <li>and most importantly, a shout out to all the third party code that make Architect great.</li></p>
                    </div>
                    <div class="arc-info col2"><h3><span class="dashicons dashicons-welcome-widgets-menus"></span>Blueprints</h3>
                    <p>Blueprints is where you create and manage the content layouts.</p>
                    <p> These can take any of the six basic forms: Grid, Slider, Tabbed, Tabular, Masonry and Accordion.</p>
                    <p>Blueprints can be displayed using Widgets; Shortcodes; Headway Blocks; Template tags; Action Hooks; or the Architect Builder</p>
                    </div>
                    <div class="arc-info col2"><h3><span class="dashicons dashicons-hammer"></span>Tools</h3>
                    <p>The Tools menu provides methods for clearing the Architect CSS cache and the Architect image cache.</p>
                    <p>If you change the Focal Point of an image, you will need to clear the Architect image cache.</p>
                    <p>If you have a caching plugin installed and clear either of these caches, you will still need to clear it too.</p>
                    </div>
                    <div class="arc-info col2"><h3><span class="dashicons dashicons-admin-settings"></span>Options</h3>
                    <p>Options contains a lot of useful settings for controlling the behaviour of Architect.</p>
                    <p> This includes, setting Responsive breakpoints, default for shortcodes and other modifications to Architect\'s behaviour.</p>
                    </div>
                    <div class="arc-info col2"><h3><span class="dashicons dashicons-admin-appearance"></span>Styling Defaults</h3>
                    <p>Styling Defaults are very useful. Set these before you get started making Blueprints from scratch to save time setting styling for every Blueprint. </p>
                    </div>
                    <div class="arc-info col2"><h3><span class="dashicons dashicons-migrate"></span>Actions Editor</h3>
                    <p>If you know the names of the action hooks in your theme, the Actions Editor allows you to hook into them without any coding.</p>
                    </div>
                    </div>
                </div>


                <div class="tabs-pane " id="how">
                    <h2>' . __('Usage') . '</h2>
                    <div class="arc-info-boxes">
                    <div class="arc-info col1">
                      <h3>' . __('Shortcode', 'pzarchitect') . '</h3>
                      <p>' . __('For example, using shortcodes, you can use any of the following formats:', 'pzarchitect') . '</p>
                      <p><strong>[architect ' . __('blog-page-layout') . ']</strong></p>
                      <p><strong>[architect blueprint="' . __('blog-page-layout') . '"]</strong></p>
                      <p><strong>[architect blueprint="' . __('thumb-gallery') . '" ids="321,456,987,123,654,789"]</strong></p>
                      <p>Since version 1.2, you can now specify Blueprints to show on phones and/or tablets. For eaxmple:</p>
                      <p><strong>[architect' . __('blog-page-layout') . '  phone="' . __('blog-page-layout-phone') . '"  tablet="' . __('blog-page-layout-tablet') . '" ]</strong></p>

                      <p>' . __('<em>ids</em> are the specific post, page etc IDs and are used to override the defined selection for the Blueprint', 'pzarchitect') . '</p>
                    </div>


                    <div class="arc-info col1">
                    <h3>' . __('Template tag', 'pzarchitect') . '</h3>
                    <p>' . __('Template tags are inserted in your page templates and the first parameter is the Blueprint short name, and the optional second one is a list of IDs to override the defaults.', 'pzarchitect') . '</p>
                    <p><strong>pzarchitect(\'' . __('blog-page-layout') . '\')</strong></p>
                    <p><strong>pzarchitect(\'' . __('thumb-gallery') . '\', \'321,456,987,123,654,789\')</strong></p>
                    </div>


                    <div class="arc-info col1">
                    <h3>' . __('Widget', 'pzarchitect') . '</h3>
                    Add the Architect widgets through the <em>WP</em> > <em>Appearance</em> > <em>Widgets</em> screen
                    </div>


                    <div class="arc-info col1">
                    <h3>' . __('Headway Block', 'pzarchitect') . '</h3>
                    Add the Architect Headway blocks in the <em>Headway Visual Editor</em>
                    </div>


                    <div class="arc-info col1">
                    <h3>' . __('Action Hooks', 'pzarchitect') . '</h3>
                    <p>If your theme had action hooks, you can hook specific Blueprints to them in your functions.php with the following base code:</p>
                        <pre>new showBlueprint(’action’, ’blueprint’, ’pageids’);</pre>
    <p><em>action</em> = Action hook to hook into</p>
    <p><em>blueprint</em> = Blueprint short name to display</p>
    <p><em>pageids</em> = Override IDs</p>
                        <p>Here is a a more extensive example that would work with Genesis (if you had those named Blueprints):</p>
<pre>function gs_init(){
  if (class_exists(\'showBlueprint\')) {
    new showBlueprint(\'genesis_before_header\',\'featured-posts-2x4\',\'home\');
    new showBlueprint(\'genesis_after_header\',\'basic-grid-2x3\',\'home\');
  }
}
add_action(\'init\',\'gs_init\');
</pre>

                    </div>


                    <div class="arc-info col1">
                    <h3>' . __('Actions Editor', 'pzarchitect') . '</h3>
                    <p>The Actions Editor is in the <em>Architect</em> > <em>Actions Editor</em> menu and is a non-coding way to do the same thing as the Action Hooks do.</p>
                    </div>


                    <div class="arc-info col1">
                    <h3>' . __('WP Gallery Shortcode Override', 'pzarchitect') . '</h3>
                    <p>An option in <em>Architect</em> > <em>Options</em> lets you set an override for all usages of the WP Gallery shortcode with a Blueprint of your own design. The only condition is the Blueprint must be set to use <em>Galleries</em> as the content source.</p>
                    <p>If you want to change individual <em>WP Gallery</em> shortcodes, switch to Text mode in the post editor, and replace the the word <em>gallery</em> in the short code with <em>architect</em> followed by the Blueprint short name. Keep the IDs.</p>
                    <p>e.g. <strong>[gallery ids="11,222,33,44,555"]</strong> you would change to <strong>[architect myblueprint ids="11,222,33,44,555"]</strong> where <em>myblueprint</em> is the <em>Shortname</em> of you Blueprint.</em></p>
                    </div>


                    <div class="arc-info col1">
                    <h3>' . __('Architect Builder', 'pzarchitect') . '</h3>
                      <p>The Architect Builder is available on the <em>WP Pages editor</em> screen.</p>
                      <p>It provides a drag &  drop interface for arranging Blueprints to display on that page.</p>
                      <p>NOTE: To use the Architect Builder, you must set the page\'s <em>Page Template</em> to <em>Architect Builder</em>.</p>
                    </div>
                </div>
                </div>


                <div class="tabs-pane " id="latest">
                  <h2>' . __('Latest News') . '</h2>
                  <div class="arc-info-boxes">
                    <div class="arc-info col1">';
      include_once(ABSPATH . WPINC . '/feed.php');

      //      add_filter( 'wp_feed_cache_transient_lifetime' , 'return_10' );
      $rss = fetch_feed('http://pizazzwp.com/category/architect/feed');
      //      remove_filter( 'wp_feed_cache_transient_lifetime' , 'return_10' );
      //      var_dump($rss);
      if (!is_wp_error($rss))  // Checks that the object is created correctly
        // Figure out how many total items there are, but limit it to 5.
      {
        $maxitems = $rss->get_item_quantity(5);

        // Build an array of all the items, starting with element 0 (first element).
        $rss_items = $rss->get_items(0, $maxitems);


        echo '<div class="postbox pzwp_blog" style="width:68%;float:left;">
                                      <h3 class="handle" style="line-height:30px;padding-left:10px;">Latest Architect News</h3>
                                      <ul class="inside">';
        if ($maxitems == 0) {
          echo '<li>No items.</li>';
        } else // Loop through each feed item and display each item as a hyperlink.
        {
          foreach ($rss_items as $item) :
            echo '<li>
                                  <h4 style="font-size:15px;"><a href=' . esc_url($item->get_permalink()) . '
                                                                 title=' . esc_html($item->get_title()) . '
                                                                 target=_blank>
                                      ' . esc_html($item->get_title()) . '</a></h4>

                                  <p style="line-height:0;font-style:italic">' . $item->get_date('j F Y') . '</p>

                                  <p>' . $item->get_description() . '<a
                                      href="' . esc_url($item->get_permalink()) . '" target=_blank>
                                      Continue reading</a></p>
                                </li>';
          endforeach;
        }

        echo '     </ul>
                      </div>';
      } else {
        echo "There was a problem accessing the news feed. As WP caches feeds for 12 hours, you won't be able to check again for a while.";
      }

      echo '</div>
                  </div>
                </div>


                <div class="tabs-pane " id="help">
                    <h2>' . __('Support') . '</h2>
                    <div class="arc-info-boxes">
                    <div class="arc-info col1">
                    <h4>' . __('Currently installed version') . ': ' . PZARC_VERSION . '</h4>';
      if (!$lite) {
        echo '<p>You can download this version anytime directly from: <a href="https://s3.amazonaws.com/341public/LATEST/Architect/pizazzwp-architect-' . str_replace('.', '', PZARC_VERSION) . '.zip">Version ' . PZARC_VERSION . '</a></p>';
      }
                echo '<p>' . __('For more detailed help, visit', 'pzarchitect') . ' <a href="http://architect4wp.com/codex-listings" target="_blank" class="arc-codex">' . __('Architect documentation at architect4wp.com', 'pzarchitect') . '</a></p>
                        <p>' . __('For <strong>technical support</strong>, either fill out the form below or email', 'pzarchitect') . ' <a href="mailto://support@pizazzwp.com" target="_blank" class="arc-codex">' . __('support@pizazzwp.com', 'pzarchitect') . '</a></p>
                        <p>' . __('For <strong>community and peer-to-peer support</strong>, visit the', 'pzarchitect') . ' <a href="https://pizazzwp.freshdesk.com/support/discussions" target="_blank" class="arc-codex">' . __('Architect Community', 'pzarchitect') . '</a></p>
                    <h3>' . __('Things to try first', 'pzarchitect') . '</h3>
                    <ul><li>' . __('If Blueprints are not displaying as expected, please try emptying your WP cache if you are using one and then the Architect cache (under <em>Architect</em> > <em>Tools</em>)', 'pzarchitect') . '</li>
                    <li>' . __('If things just aren\'t working, e.g. nothing displays, the page is broken - then try deactivating all other plugins. If that fixes things, reactivate one at a time until you identify the conflict, then let us know what the plugin is.', 'pzarchitect') . '</li>
                    </ul>
                    </div>
                    </div>';
      if (!$lite) {
        echo '      <h2>Submit a help request directly</h2>
                                  <div class="arc-info-boxes">
                    <div class="arc-info col1">

          <script type="text/javascript" src="http://assets.freshdesk.com/widget/freshwidget.js"></script>
            <style type="text/css" media="screen, projection">
        @import url(http://assets.freshdesk.com/widget/freshwidget.css);
            </style>
            <iframe class="freshwidget-embedded-form" id="freshwidget-embedded-form" src="https://pizazzwp.freshdesk.com/widgets/feedback_widget/new?&widgetType=embedded&formTitle=&screenshot=no&searchArea=no" scrolling="no" height="850px" width="90%" frameborder="0"  style="margin:20px 10px 10px 40px;background:#eee;overflow-y: auto;">
            </iframe>
                </div>
           </div>';
      }
echo '                </div>

                <div class="tabs-pane " id="shout">
                    <h2>' . __('Shoutouts', 'pzarchitect') . '</h2>
                                        <div class="arc-info-boxes">
                    <div class="arc-info col1">
                    <h3>Code</h3>
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
                        <li><a href="http://imagesloaded.desandro.com/" target="_blank">imagesLoaded</a>
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
                        <li><a href="http://tracelytics.github.io/pageguide/" target="_blank">PageGuide</a>
                        </li>
                        <li><a href="https://github.com/carhartl/jquery-cookie" target="_blank">jQuery Cookie</a>
                        </li>
                        <li><a href="http://www.wpexplorer.com/author/harri/" target="_blank">Page template tutorial on WP Explorer by Harri Bell-Thomas which made the Builder possible.</a>
                        </li>
                        <li><a href="http://jquery.eisbehr.de/lazy/" target="_blank">Lazy</a> This is used to lazy load images in the Preset Selector.
                        </li>
                        <li><a href="http://mobiledetect.net/" target="_blank">Mobile Detect</a>
                        </li>
                        <li><a href="https://github.com/davedonaldson/WordPress-SysInfo" target="_blank">WordPress SysInfo</a>
                        </li>

                    </ul>
                    <!--
                    <h3>Tireless beta testers</h3>
                    <p>Thank you to all the beta testers. Some went way above and beyond so deserve special mention:</p>
                    <ul class=""shoutout">
                    <li>Jon Mather</li>
                    <li>Frank Gomez</li>
                    <li>Clay Griffiths</li>
                    <li>Gerard Godin</li>
                    </ul
                    -->
                </div>
                </div>
                </div>

            </div>


        </div>


      </div>';
    }


  }

// end pzarcAdmin


  $pzarcadmin = new ArchitectAdmin();


  /*********************************************
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

          $sql_query_sel[] = "SELECT $new_post_id, '$meta_key', '$meta_value'";
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

  /*********************************************
   * Add the duplicate link to action list for post_row_actions
   */
  function pzarc_duplicate_post_link($actions, $post)
  {
    $screen = get_current_screen();
    if (current_user_can('edit_posts') && $screen->id === 'edit-arc-blueprints') {
      $actions[ 'duplicate' ] = '<a href="admin.php?action=pzarc_duplicate_post_as_draft&amp;post=' . $post->ID . '" title="' . __('Duplicate this item', 'pzarchitect') . '" rel="permalink">' . __('Duplicate', 'pzarchitect') . '</a>';
    }

    return $actions;
  }

  /*********************************************
   * Functions for exporting a preset
   */
  add_action('admin_action_pzarc_export_blueprint', 'pzarc_export_blueprint');

  /*********************************************
   * pzarc_export_blueprint
   */
  function pzarc_export_blueprint()
  {
    global $wpdb;
    if (!(isset($_GET[ 'post' ]) || isset($_POST[ 'post' ]) || (isset($_REQUEST[ 'action' ]) && 'pzarc_export_blueprint' == $_REQUEST[ 'action' ]))) {
      wp_die(__('No post to export has been supplied!', 'pzarchitect'));
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
      $arc_exp_post[ 'post' ]   = json_encode($post);
      $arc_post_meta            = get_post_meta($post->ID);
      $arc_exp_post[ 'meta' ]   = json_encode($arc_post_meta);
      $arc_exp_post[ 'title' ]  = $post->post_title;
      $arc_exp_post[ 'bptype' ] = (empty($arc_post_meta[ '_blueprints_section-0-layout-mode' ]) ? 'basic' : $arc_post_meta[ '_blueprints_section-0-layout-mode' ][ 0 ]);
      update_option('arc-export-to-preset', $arc_exp_post);
      wp_redirect(admin_url('edit.php?post_type=' . $post->post_type));
      exit;
    }
  }

  /*********************************************
   * @param $actions
   * @param $post
   *
   * @return mixed
   */
  function pzarc_export_preset_link($actions, $post)
  {
    $screen = get_current_screen();
    if (current_user_can('edit_posts') && $screen->id === 'edit-arc-blueprints') {
      $actions[ 'export' ] = '<a href="admin.php?action=pzarc_export_blueprint&amp;post=' . $post->ID . '" title="' . __('Export', 'pzarchitect') . '" rel="permalink">' . __('Export', 'pzarchitect') . '</a>';
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

  /*********************************************
   * Sort posts in wp_list_table by column in ascending or descending order.
   */
  function pzarc_blueprints_order($query)
  {
    /*
        Set post types.
        _builtin => true returns WordPress default post types.
        _builtin => false returns custom registered post types.
    */
    $post_types = get_post_types(array(), 'names');
    /* The current post type. */
    $post_type = $query->get('post_type');
    /* Check post types. */
    if (in_array($post_type, $post_types) && $post_type === 'arc-blueprints') {
      /* Post Column: e.g. title */
      if ($query->get('orderby') == '') {
        $query->set('orderby', 'title');
      }
      /* Post Order: ASC / DESC */
      if ($query->get('order') == '') {
        $query->set('order', 'ASC');
      }
    }
  }

  if (is_admin()) {
    add_action('pre_get_posts', 'pzarc_blueprints_order');
  }


  //http://wpsnipp.com/index.php/functions-php/update-automatically-create-media_buttons-for-shortcode-selection/
  add_action('media_buttons', 'pzarc_add_sc_select', 11);

  /*********************************************
   *
   */
  function pzarc_add_sc_select()
  {

    $screen   = get_current_screen();
    $user_can = current_user_can('edit_others_posts');
    if ($user_can && ($screen->post_type === 'page' || $screen->post_type === 'post')) {
      $blueprint_list = pzarc_get_posts_in_post_type('arc-blueprints', true);

      echo '&nbsp;<select id="arc-select" class="arc-dropdown" style="font-size:small;"><option>Insert Architect Blueprint</option>';
      $shortcodes_list = '';
      foreach ($blueprint_list as $key => $val) {
        $shortcodes_list .= '<option value="[architect ' . $key . ']">' . $val . '</option>';
      }

      echo $shortcodes_list;
      echo '</select>';
    }
  }

  add_action('admin_head', 'pzarc_button_js');
  /*********************************************
   *
   */
  function pzarc_button_js()
  {
    echo '<script type="text/javascript">
        jQuery(document).ready(function(){
           jQuery("#arc-select").change(function() {
           $selected=jQuery("#arc-select :selected").val();
           if ($selected !=="Insert Architect Blueprint"){
            send_to_editor($selected);
            }
            jQuery("#arc-select>option:eq(0)").prop("selected", true);
           return false;
           });
        });
        </script>';
  }

  function pzarc_initiate_updater()
  {
    // TODO: Try to not run this too mcuh
    // Check on Headway if enabled since it was probably bought there
    $pzarc_status = get_option('edd_architect_license_status');

    // Checks for HW and that we havem't already activated a Pizazz licence
    if (class_exists('HeadwayUpdaterAPI') && !($pzarc_status !== false && $pzarc_status == 'valid')) {

      $updater = new HeadwayUpdaterAPI(array(
                                           'slug'            => 'architect',
                                           'path'            => plugin_basename(__FILE__),
                                           'name'            => 'Architect',
                                           'type'            => 'block',
                                           'current_version' => PZARC_VERSION
                                       ));
    }

    require_once(PZARC_PLUGIN_APP_PATH . 'admin/php/edd-architect-plugin.php');


    /**
     * Display update notices
     */
//    @include_once PZARC_DOCUMENTATION_PATH . 'updates/1000.php';

  }

  function pzarc_set_messages($messages) {
    global $post, $post_ID;
    $post_type = get_post_type( $post_ID );

    $obj = get_post_type_object($post_type);
    $singular = $obj->labels->singular_name;

    $messages[$post_type] = array(
        0 => '', // Unused. Messages start at index 1.
        1 => (!in_array($post_type, array('arc-blueprints','pz_testimonials','pz_snippets'))?sprintf( __($singular.' updated. <a href="%s">View '.strtolower($singular).'</a>','pzarchitect'), esc_url( get_permalink($post_ID) ) ):__($singular .' updated','pzarchitect')),
        2 => __('Custom field updated.'),
        3 => __('Custom field deleted.'),
        4 => __($singular.' updated.'),
        5 => isset($_GET['revision']) ? sprintf( __($singular.' restored to revision from %s'), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
        6 => (!in_array($post_type, array('arc-blueprints','pz_testimonials','pz_snippets'))?sprintf( __($singular.' published. <a href="%s">View '.strtolower($singular).'</a>'), esc_url( get_permalink($post_ID) ) ):__($singular .' published','pzarchitect')),
        7 => __('Page saved.'),
        8 => sprintf( __($singular.' submitted. <a target="_blank" href="%s">Preview '.strtolower($singular).'</a>'), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
        9 => sprintf( __($singular.' scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview '.strtolower($singular).'</a>'), date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ), esc_url( get_permalink($post_ID) ) ),
        10 => (!in_array($post_type, array('arc-blueprints','pz_testimonials','pz_snippets'))?sprintf( __($singular.' draft updated. <a target="_blank" href="%s">Preview '.strtolower($singular).'</a>'), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ):__($singular .' draft updated','pzarchitect')),
    );
    return $messages;
  }

  add_filter('post_updated_messages', 'pzarc_set_messages' );