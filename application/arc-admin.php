<?php


  /*********************************************
   * Class ArchitectAdmin
   */
  class ArchitectAdmin {

    /*********************************************
     *
     */
    function __construct() {
      /*
       * Create the layouts custom post type
       */
      global $arc_presets_data;
//      add_action('plugins_loaded', array($this, 'init'));
      add_action( 'plugins_loaded', array( $this, 'init' ) );

      if ( ( function_exists( 'arc_fs' ) && ! arc_fs()->is__premium_only() ) || ! function_exists( 'arc_fs' ) ) {
        add_action( 'after_setup_theme', 'pzarc_initiate_updater' );
      }

    }


    /*********************************************
     *
     */
    function init() {
      if ( ! is_admin()  ) { // 1.11.0 Block only not admin
        return;
      }

      // 1.11.0: Moved so all users can use it!
      require_once( PZARC_PLUGIN_APP_PATH . '/shared/thirdparty/php/pzwp-focal-point/pzwp-focal-point.php' );


      if ( ! class_exists( 'SysInfo' ) ) {
        require_once( PZARC_PLUGIN_APP_PATH . '/shared/thirdparty/php/WordPress-SysInfo/sysinfo.php' );
      }

      if ( ! ( class_exists( 'ReduxFramework' ) || class_exists( 'ReduxFrameworkPlugin' ) ) ) {
        add_action( 'admin_notices', array( $this, 'missing_redux_admin_notice' ) );

        // TODO: Add an alternativeArchitect Admin screen.
        add_action( 'admin_menu', array( $this, 'admin_menu_no_redux' ) );

        return;

      } else {

        if ( current_user_can( 'manage_options' ) ) { // 1.11.0 So featured vid shows in post editor for all users

          add_action( 'admin_head', array( $this, 'admin_head' ) );
          add_action( 'admin_menu', array( $this, 'admin_menu' ) );

          add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue' ) );
          add_filter( 'admin_body_class', array( &$this, 'add_admin_body_class' ) );


          // TODO: Make up some easily editable panel defs - prob have to be a custom content type
          //       require_once PZARC_PLUGIN_PATH . '/admin/php/arc-options-def-editor.php';

          require_once PZARC_PLUGIN_APP_PATH . 'admin/php/class_arc_blueprints_designer.php';
          require_once PZARC_PLUGIN_APP_PATH . 'admin/php/arc-save-process.php';

        }

        //TODO:     require_once PZARC_PLUGIN_PATH . '/admin/arc-widget.php';
        // This one is really only needed on posts, pages and snippets, so could conditionalise its load
        require_once PZARC_PLUGIN_APP_PATH . 'admin/php/class_arc_misc_metaboxes.php';
        require_once PZARC_PLUGIN_APP_PATH . 'shared/thirdparty/php/redux-custom-fields/loader.php';
        require_once PZARC_PLUGIN_APP_PATH . 'shared/thirdparty/php/redux-extensions/loader.php';

        $misc_metaboxes = new arc_Misc_metaboxes();
        if ( current_user_can( 'manage_options' ) ) { // 1.11.0 So featured vid shows in post editor for all users
          $content_blueprint = new arc_Blueprints_Designer();

          if ( ( function_exists( 'arc_fs' ) && arc_fs()->is__premium_only() ) || defined( 'PZARC_PRO' ) ) {
            require_once PZARC_PLUGIN_APP_PATH . 'admin/php/arc-options-actions.php';
          }
        }
        require_once PZARC_PLUGIN_APP_PATH . 'admin/php/arc-options-styling.php';
        require_once PZARC_PLUGIN_APP_PATH . 'admin/php/arc-options.php';
      }
    }

    /*********************************************
     *
     */
    function missing_redux_admin_notice() {
      echo '<div id="message" class="error"><h3>' . __( 'Architect requires Redux Framework', 'pzarchitect' ) . '</h3><p><strong>' . __( 'One final step in installing Architect.', 'pzarchitect' ) . '</strong><br>' . __( 'It cannot function without the Redux Framework plugin. You need to install and/or activate Redux.', 'pzarchitect' ) . '<br>' . __( 'Redux is the backbone of Architect, providing all the necessary code libraries for Architect\'s fields and options.', 'pzarchitect' ) . '<br>' . __( 'There should be another message with a link to make installing and activating Redux easy. If you can\'t find it, contact PizazzWP support.', 'pzarchitect' ) . '</p></div>';
    }


    /*********************************************
     * @param $classes
     *
     * @return string
     */
    function add_admin_body_class( $classes ) {
      $screen = get_current_screen();

      switch ( $screen->id ) {
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
          if ( $_architect_options['architect_enable_bgimage'] ) {
            $arc_bg  = $_architect_options['architect_bgimage'];
            $classes .= ' arc-bgimage arc-bg-' . $arc_bg;
          }
          $classes .= ' ' . $screen->post_type;
          break;
      }

      global $_architect_options;
      if ( ! empty( $_architect_options['architect_show_advanced'] ) ) {
        $classes .= ' arc-advanced';
      } else {
        $classes .= ' arc-beginner';
      }

      return $classes;
    }


    /*********************************************
     * @param $hook
     */
    function admin_enqueue( $hook ) {
      wp_enqueue_style( 'pzarc-admin-styles' );

      wp_register_script( 'jquery-cookie', PZARC_PLUGIN_APP_URL . 'shared/thirdparty/js/jquery-cookie/jquery.cookie.js', array( 'jquery' ), PZARC_VERSION, TRUE );
      wp_register_script( 'jquery-pageguide', PZARC_PLUGIN_APP_URL . 'shared/thirdparty/js/pageguide/pageguide.min.js', array( 'jquery' ), PZARC_VERSION, TRUE );
      wp_register_style( 'css-pageguide', PZARC_PLUGIN_APP_URL . 'shared/thirdparty/js/pageguide/css/pageguide.css', FALSE, PZARC_VERSION );

      $screen = get_current_screen();
      if ( strpos( ( 'X' . $screen->id ), 'arc-' ) > 0 ) {
        wp_enqueue_style( 'dashicons' );
        wp_enqueue_script( 'jquery-pzarc-metaboxes', PZARC_PLUGIN_APP_URL . '/admin/js/arc-metaboxes.js', array( 'jquery' ), PZARC_VERSION, TRUE );
        wp_enqueue_script( 'jquery-cookie' );

        add_filter( 'post_row_actions', 'pzarc_duplicate_post_link', 10, 2 );
        add_filter( 'page_row_actions', 'pzarc_duplicate_post_link', 10, 2 );
        add_filter( 'post_row_actions', 'pzarc_export_preset_link', 10, 3 );
        add_filter( 'page_row_actions', 'pzarc_export_preset_link', 10, 3 );
      }
      if ( 'architect-lite_page_pzarc_support' === $screen->id || 'architect_page_pzarc_support' === $screen->id || 'edit-arc-blueprints' === $screen->id ) {
        wp_enqueue_script( 'js-classlist', PZARC_PLUGIN_APP_URL . '/shared/thirdparty/js/tabby/dist/js/classList.min.js', array( 'jquery' ), PZARC_VERSION, TRUE );
        wp_enqueue_script( 'js-tabby', PZARC_PLUGIN_APP_URL . '/shared/thirdparty/js/tabby/dist/js/tabby.min.js', array( 'jquery' ), PZARC_VERSION, TRUE );
        wp_enqueue_script( 'js-tabby-arc', PZARC_PLUGIN_APP_URL . '/admin/js/arc-tabby.js', array( 'jquery' ), PZARC_VERSION, TRUE );
        wp_enqueue_style( 'css-tabby', PZARC_PLUGIN_APP_URL . '/shared/thirdparty/js/tabby/dist/css/tabby.min.css' );
      }

      switch ( $screen->id ) {
        case 'architect_page__architect_options':
        case 'architect_page__architect_styling':
        case 'architect_page__architect_actions_editor':
        case 'edit-arc-blueprints':
        case 'arc-blueprints':
        case 'architect_page_pzarc_tools':
        case 'architect_page_pzarc_about':

          global $_architect_options;
          if ( ! isset( $GLOBALS['_architect_options'] ) ) {
            $GLOBALS['_architect_options'] = get_option( '_architect_options', array() );
          }
          break;
      }

      if ( $screen->id === 'edit-arc-blueprints' ) {
//        wp_enqueue_script( 'jquery-cookie' );
//        require_once( PZARC_DOCUMENTATION_PATH . PZARC_LANGUAGE . '/blueprints-listings-pageguide.php' );
      }

//      if ($screen post ot page editor)
//      require_once (PZARC_PLUGIN_APP_PATH.'admin/php/arcMCEButtons.php');
    }

    /*********************************************
     *
     */
    function admin_menu() {
      global $pzarc_menu, $pizazzwp_updates;
      global $submenu;
      if ( ! $pzarc_menu ) {
        //add_menu_page( $page_title,  $menu_title, $capability,   $menu_slug, $function,    $icon_url, $position );
        $pzarc_current_theme = wp_get_theme();
        if ( ( $pzarc_current_theme->get( 'Name' ) === 'Headway' || $pzarc_current_theme->get( 'Name' ) === 'Headway Base' || $pzarc_current_theme->get( 'Template' ) == 'headway' ) ) {

          if ( is_multisite() ) {
            $hw_opts = get_blog_option( 1, 'headway_option_group_general' );
          } else {
            $hw_opts = get_option( 'headway_option_group_general' );
          }
        }

        $pzarc_status = pzarc_status();

        $vers       = ( ( ! empty( $hw_opts['license-status-architect'] ) && $hw_opts['license-status-architect'] == 'valid' ) || $pzarc_status !== FALSE && $pzarc_status == 'valid' ) ? '' : 'Lite';
        $pzarc_menu = add_menu_page( __( 'Getting started', 'pzarchitect' ), 'Architect ' . $vers, 'edit_posts', 'pzarc', array( $this, 'pzarc_support', ), PZARC_PLUGIN_APP_URL . 'wp-icon.png' );
        // add_submenu_page( $parent_slug, $page_title, $menu_title, $capability, $menu_slug, $function );

        add_submenu_page( 'pzarc', __( 'Tools', 'pzarchitect' ), '<span class="dashicons dashicons-hammer size-small"></span>' . __( 'Tools', 'pzarchitect' ), 'edit_others_pages', 'pzarc_tools', array( $this, 'pzarc_tools', ) );
        add_submenu_page( 'pzarc', __( 'Help & Support', 'pzarchitect' ), '<span class="dashicons dashicons-editor-help size-small"></span>' . __( 'Help & Support', 'pzarchitect' ), 'edit_others_pages', 'pzarc_support', array(
            $this,
            'pzarc_support',
        ) );

        // Shift Help to the top
        array_unshift( $submenu['pzarc'], array_pop( $submenu['pzarc'] ) );
      }

      // This should ensure the support menu is available before Freemius licence activation
      if ( isset( $submenu['pzarc'] ) ) {
        $missing_support = TRUE;
        foreach ( $submenu['pzarc'] as $v ) {
          if ( in_array( 'pzarc_support', $v ) ) {
            $missing_support = FALSE;
            break;
          }
        }
        if ( $missing_support ) {
          add_submenu_page( 'pzarc', __( 'Help & Support', 'pzarchitect' ), '<span class="dashicons dashicons-editor-help size-small"></span>' . __( 'Help & Support', 'pzarchitect' ), 'edit_others_pages', 'pzarc_support', array(
              $this,
              'pzarc_support',
          ) );
        }
      }

    }

    /**
     *
     */
    function admin_menu_no_redux() {
      global $pzarc_menu, $pizazzwp_updates;
      if ( ! $pzarc_menu ) {
        $pzarc_menu = add_menu_page( 'About Architect', 'Architect', 'edit_posts', 'pzarc', 'pzarc_about', PZARC_PLUGIN_APP_URL . 'wp-icon.png' );
        add_submenu_page( 'pzarc', 'Help & Support', '<span class="dashicons dashicons-editor-help size-small"></span>Help & Support', 'manage_options', 'pzarc_support', array(
                $this,
                'pzarc_support',
            ) );

        global $submenu;
        // Shift those last  to the top
        array_unshift( $submenu['pzarc'], array_pop( $submenu['pzarc'] ) );
      }

    }

    /*********************************************
     *
     */
    function admin_head() {

    }


    /**
     * pzarc_tools
     */
    function pzarc_tools() {
      global $title;

      echo '<div class = "wrap">

			<!--Display Plugin Icon, Header, and Description-->
			<div class = "icon32" id = "icon-users"><br></div>
        <div class="pzarc-about-box">

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
						<h3>' . __( 'Clear Architect caches', 'pzarchitect' ) . '</h3>
						<p>' . __( 'Sometimes the CSS cache file may not exist or may even become scrambled and layouts will not look right. If so, simply click the Rebuild button and it will be recreated. This also rebuilds the default settings cache. If the problem persists, contact Pizazz Support at <strong>support@pizazzwp.com</strong>.', 'pzarchitect' ) . '</p>
    <p>' . __( 'If you update or change images in any posts,sometimes the image cache may get out-of-sync. In that case, you can refresh the thumbs image cache to ensure your site visitors are seeing the correct images.', 'pzarchitect' ) . '</p>

    <p>' . __( 'Please note: Refreshing the cache causes no problems other than the next person who visits your site may have to wait a little longer as the cache images get recreated.', 'pzarchitect' ) . ' <strong>' . __( 'No images in any post will be affected', 'pzarchitect' ) . '</strong>. </p>
						<form action="admin.php?page=pzarc_tools" method="post">';
      wp_nonce_field( 'rebuild-architect-css-cache' );
      echo '<button class="button-primary" style="min-width:100px;" type="submit" name="rebuildarchitectcss" value="' . __( 'Clear Architect Caches' ) . '">' . __( 'Clear' ) . '  <span class="dashicons dashicons-admin-appearance" style="margin-left:1%;color:inherit;font-size:22px;vertical-align:text-bottom"></span></button>
        </form>';

      if ( isset( $_POST['rebuildarchitectcss'] ) && check_admin_referer( 'rebuild-architect-css-cache' ) ) {
        require_once( PZARC_PLUGIN_APP_PATH . '/admin/php/arc-save-process.php' );
        global $pzarc_css_success;
        $pzarc_css_success = TRUE;
        save_arc_layouts( 'all', NULL, TRUE );
        pzarc_set_defaults();
        bfi_flush_image_cache();
        delete_option( 'architect_custom_fields' );
        // Clear the registry of Blueprint usages
        delete_option( 'arc-blueprint-usage' );
        if ( $pzarc_css_success ) {
          echo '<br><div id="message" class="updated"><p>' . __( 'Architect caches have been cleared. Your site should look awesome again!', 'pzarchitect' ) . '</p>

        <p>' . __( 'If your site is using a cache plugin or service, clear that cache too.', 'pzarchitect' ) . '</p></div>';
        } else {
          // add a message
        }
      }

      if ( ( function_exists( 'arc_fs' ) && arc_fs()->is__premium_only() ) || defined( 'PZARC_PRO' ) ) {

        /**
         * Import presets
         */
        if ( defined( 'PZARC_PRO' ) && PZARC_PRO == TRUE && wp_mkdir_p( PZARC_PRESETS_PATH ) ) {
          $pzarc_custom_presets = pzarc_tidy_dir( scandir( PZARC_PRESETS_PATH ) );
          // TODO: add existing presets with option to delete (custom) or hide (builtin)
          echo '<h3>' . __( 'Import Blueprint or Preset', 'pzarchitect' ) . '</h3>';
          if ( count( $pzarc_custom_presets ) > 0 ) {
            echo '<div class="arc-installed-presets"><h4>Currently installed additional Presets</h4>';
            echo '<ul>';
            foreach ( $pzarc_custom_presets as $f ) {
              if ( is_dir( PZARC_PRESETS_PATH . '/' . $f ) ) {
                echo '<li>' . ucwords( $f ) . '</li>';
              }
            }
            echo '</ul></div>';
          }
        }

        echo '<p>Tutorial: <a href="http://architect4wp.com/codex/architect-importing-blueprints-and-presets/" target=_blank>Importing Blueprints and Presets</a></p>';
        echo '<p>' . __( '<strong>Blueprints</strong>: If you have a <strong>Blueprint</strong> in a .txt format, you may import it by uploading it here. The new Blueprint will then be opened ready for editing. Blueprint export files can be created from context menu on the Blueprint listing.' ) . '</p>';
        echo '<p>' . __( '<strong>Presets</strong>: If you have a <strong>Preset</strong> in a .zip format, you may import it by uploading it here. It will then appear in the Blueprints, Preset Selector' ) . '</p>
        <div class="pzarc-upload-preset">
             <form method="post" enctype="multipart/form-data" class="pzarc-preset-upload-form" action="">';
        wp_nonce_field( 'arc-preset-upload' );
        echo '<p><label  for="txtorzip">' . __( 'Select Blueprint .txt file or Preset zip file' ) . '</label>
                  <input type="file" id="txtorzip" name="txtorzip" /></p>';
        echo '<p><label for="newbpname">' . __( 'Name for new Blueprint' ) . '</label>
                  <input type="text" size=40 id="newbpname" name="newbpname" placeholder="(New) Unnamed Blueprint"/></p>';
        echo '<p><button class="button-primary"  style="min-width:100px;" type="submit" name="install-blueprint-or-preset-submit" value="' . __( 'Import', 'pzarchitect' ) . '">' . __( 'Import' ) . '  <span class="dashicons dashicons-id-alt" style="margin-left:1%;color:inherit;font-size:22px;vertical-align:text-bottom"></span></button></p>';
        echo '</form>
        </div>';

        if ( isset( $_POST['install-blueprint-or-preset-submit'] ) && check_admin_referer( 'arc-preset-upload' ) ) {

          switch ( TRUE ) {
            case ( ( substr( $_FILES['txtorzip']['name'], - 4, 4 ) === '.zip' ) && file_exists( PZARC_PRESETS_PATH . '/' . str_replace( '.zip', '', $_FILES['txtorzip']['name'] ) ) ):
              echo '<div id="message" class="error"><p>' . __( 'A Preset with that name already exists.', 'pzarchitect' ) . '</p></div>';
              break;

            case ( ! empty( $_FILES['txtorzip']['name'] ) && ( substr( $_FILES['txtorzip']['name'], - 4, 4 ) === '.zip' ) ):
              pzarc_upload_file( $_FILES['txtorzip'], 'preset' );
              break;

            case ( ! empty( $_FILES['txtorzip']['name'] ) && ( substr( $_FILES['txtorzip']['name'], - 4, 4 ) === '.txt' ) ):
              pzarc_upload_file( $_FILES['txtorzip'], 'blueprint', $_POST['newbpname'] );
              // todo: add method of styled or unstyled
              break;

            case empty( $_FILES['txtorzip']['name'] ):
              echo '<div id="message" class="error"><p>' . __( 'No file specified.', 'pzarchitect' ) . '</p></div>';
              break;

            case ( substr( $_FILES['txtorzip']['name'], - 4, 4 ) !== '.zip' && substr( $_FILES['txtorzip']['name'], - 4, 4 ) !== '.txt' ):
              echo '<div id="message" class="error"><p>' . __( 'File must be a txt or zip file.', 'pzarchitect' ) . '</p></div>';
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
    function pzarc_support() {
      global $title;
//      require_once(PZARC_DOCUMENTATION_PATH . PZARC_LANGUAGE . '/architect-pageguide.php');

      echo '<div class = "wrap">
     <script>
      //    tabby.init();
      </script>

  			<!--Display Plugin Icon, Header, and Description-->
        <div class="icon32" id="icon-users">
            
        </div>
        <h1 style="font-size:2.8rem;margin-bottom: 20px;font-weight:300"><img src="' . PZARC_PLUGIN_APP_URL . 'admin/assets/images/architect-logo.png" width="96" height="96" style="vertical-align:middle;">Architect content block builder</h1>
        ';

      if ( class_exists( 'HeadwayLayoutOptions' ) ) {
        echo '<div class="pzarc-about-box alert">
      <h2>Crossgrade Headway licences</h2>
      <p>Anyone who purchased Architect from the Headway Extend store, please contact <a href="mailto:support@pizazzwp.com">support@pizazzwp.com</a> to arrange a discounted crosgrade licence from the Pizazz store.</p>
      </div>
';
      }
      echo '                             
        <div class="pzarc-about-box" >
            <div class="pzarc-help-section">
            <h2>' . $title . '</h2>
                        <a class="pzarc-button-help" href="http://architect4wp.com/codex-listings/" target="_blank">
                        <span class="dashicons dashicons-book"></span>
                        Documentation</a>&nbsp;
                        <!-- <a class="pzarc-button-help" href="https://pizazzwp.freshdesk.com/support/discussions" target="_blank">
                        <span class="dashicons dashicons-groups"></span>
                        Community support</a>&nbsp; -->
                        <a class="pzarc-button-help" href="mailto:support@pizazzwp.com?subject=Architect%20help" target="_blank">
                        <span class="dashicons dashicons-admin-tools"></span>
                        Tech support</a>
                        <a class="pzarc-button-help" href="https://shop.pizazzwp.com/checkout/customer-dashboard/" target="_blank">
                        <span class="dashicons dashicons-admin-users"></span>
                        Customer dashboard</a>
                        <a class="pzarc-button-help" href="https://shop.pizazzwp.com/affiliate-area/" target="_blank">
                        <span class="dashicons" style="font-size:1.3em">$</span>
                        Affiliates</a>
                        </div>

            ';

      $pzarc_status = pzarc_status();

      $pzarc_current_theme = wp_get_theme();
      if ( ( $pzarc_current_theme->get( 'Name' ) === 'Headway' || $pzarc_current_theme->get( 'Name' ) === 'Headway Base' || $pzarc_current_theme->get( 'Template' ) == 'headway' ) ) {
        if ( is_multisite() ) {
          $hw_opts = get_blog_option( 1, 'headway_option_group_general' );
        } else {
          $hw_opts = get_option( 'headway_option_group_general' );
        }
      }

      $lite = ( ( ! empty( $hw_opts['license-status-architect'] ) && $hw_opts['license-status-architect'] == 'valid' ) || $pzarc_status !== FALSE && $pzarc_status == 'valid' ) ? FALSE : TRUE;

      if ( $lite ) {
        echo ' <div class="arc-info-boxes">
                    <div class="arc-info col1 arc-is-lite">';
        echo '<h3 style="color:#0074A2">Architect Lite</h3>
        <p class="arc-important" style="font-weight:bold;">You are running Architect without activating a licence, therefore it is in Lite mode. Cool features you are missing out on are: Animations and access to all content types, including Galleries, Snippets, NextGen, Testimonials and custom post types</p>
        <p style="font-weight:bold;">To get all the extra goodness of Architect, you can purchase it from the <a href="http://shop.pizazzwp.com/downloads/architect/" target="_blank">PizazzWP Shop</a> or just click the <a href="./admin.php?page=pzarc-pricing">Upgrade link</a> in the Architect menu</p>
        </div>
        </div>';
      }
      echo ' <div class="tabby tabs">
                <button class="tabby-quick first active" data-tab="#quick">' . __( 'Getting started', 'pzarchitect' ) . '</button>
                <button class="tabby-how" data-tab="#how">' . __( 'Usage', 'pzarchitect' ) . '</button>
                <button class="tabby-latest" data-tab="#latest">' . __( 'Latest news', 'pzarchitect' ) . '</button>
                <button class="tabby-changes" data-tab="#changes">' . __( 'Changelog', 'pzarchitect' ) . '</button>
                <button class="tabby-shout" data-tab="#shout">' . __( 'Shoutouts', 'pzarchitect' ) . '</button>
                <button class="tabby-features" data-tab="#features">' . __( 'Features', 'pzarchitect' ) . '</button>
                <button class="tabby-help" data-tab="#help">' . __( 'Support', 'pzarchitect' ) . '</button>
            </div>';
echo '	<div class="tabby tabs-content">';
      include_once( 'admin/parts/admin-tabs-pane-about.php' );
      include_once( 'admin/parts/admin-tabs-pane-usage.php' );
      include_once( 'admin/parts/admin-tabs-pane-news.php' );
      include_once( 'admin/parts/admin-tabs-pane-changes.php' );
      include_once( 'admin/parts/admin-tabs-pane-shoutout.php' );
      include_once( 'admin/parts/admin-tabs-pane-features.php' );
      include_once( 'admin/parts/admin-tabs-pane-support.php' );

      echo '
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
  function pzarc_duplicate_post_as_draft() {
    global $wpdb;
    if ( ! ( isset( $_GET['post'] ) || isset( $_POST['post'] ) || ( isset( $_REQUEST['action'] ) && 'pzarc_duplicate_post_as_draft' == $_REQUEST['action'] ) ) ) {
      wp_die( __( 'No post to duplicate has been supplied!', 'pzarchitect' ) );
    }

    /*
     * get the original post id
     */
    $post_id = ( isset( $_GET['post'] ) ? $_GET['post'] : $_POST['post'] );
    /*
     * and all the original post data then
     */
    $post = get_post( $post_id );

    /*
     * if you don't want current user to be the new post author,
     * then change next couple of lines to this: $new_post_author = $post->post_author;
     */
    $current_user    = wp_get_current_user();
    $new_post_author = $current_user->ID;

    /*
     * if post data exists, create the post duplicate
     */
    if ( isset( $post ) && $post != NULL ) {


      // Get the next slug name
      $post_exists = array();
      $args        = array(
          'post_status' => array( 'publish', 'draft' ),
          'post_type'   => $post->post_type,
      );
      $i           = 1;
      do {
        $new_slug     = $post->post_name . '-' . $i ++;
        $args['name'] = $new_slug;
        $post_exists  = get_posts( $args );
      } while ( ! empty( $post_exists ) );


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
          'post_title'     => __( '(DUPLICATE) ', 'pzarchitect' ) . $post->post_title,
          'post_type'      => $post->post_type,
          'to_ping'        => $post->to_ping,
          'menu_order'     => $post->menu_order,
      );

      /*
       * insert the post by wp_insert_post() function
       */
      $new_post_id = wp_insert_post( $args );

      /*
       * get all current post terms ad set them to the new post draft
       */
      $taxonomies = get_object_taxonomies( $post->post_type ); // returns array of taxonomy names for post type, ex array("category", "post_tag");
      foreach ( $taxonomies as $taxonomy ) {
        $post_terms = wp_get_object_terms( $post_id, $taxonomy, array( 'fields' => 'slugs' ) );
        wp_set_object_terms( $new_post_id, $post_terms, $taxonomy, FALSE );
      }

      /*
       * duplicate all post meta
       */
      $post_meta_infos = $wpdb->get_results( "SELECT meta_key, meta_value FROM $wpdb->postmeta WHERE post_id=$post_id" );
      if ( count( $post_meta_infos ) != 0 ) {
        $sql_query = "INSERT INTO $wpdb->postmeta (post_id, meta_key, meta_value) ";
        foreach ( $post_meta_infos as $meta_info ) {
          $meta_key = $meta_info->meta_key;
          if ( $meta_key === '_panels_settings_short-name' || $meta_key === '_blueprints_short-name' ) {
            $meta_value = '';
          } else {
            $meta_value = addslashes( $meta_info->meta_value );
          }

          $sql_query_sel[] = "SELECT $new_post_id, '$meta_key', '$meta_value'";
        }
        $sql_query .= implode( " UNION ALL ", $sql_query_sel );
        $wpdb->query( $sql_query );
      }


      /*
       * finally, redirect to the edit post screen for the new draft
       */
//      wp_redirect( admin_url( 'post.php?action=edit&post=' . $new_post_id ) );
      wp_redirect( admin_url( 'edit.php?post_type=' . $post->post_type ) );

      exit;
    } else {
      wp_die( 'Post creation failed, could not find original post: ' . $post_id );
    }
  }

  add_action( 'admin_action_pzarc_duplicate_post_as_draft', 'pzarc_duplicate_post_as_draft' );

  /*********************************************
   * Add the duplicate link to action list for post_row_actions
   */
  function pzarc_duplicate_post_link( $actions, $post ) {
    $screen = get_current_screen();
    if ( current_user_can( 'edit_posts' ) && $screen->id === 'edit-arc-blueprints' ) {
      $actions['duplicate'] = '<a href="admin.php?action=pzarc_duplicate_post_as_draft&amp;post=' . $post->ID . '" title="' . __( 'Duplicate this item', 'pzarchitect' ) . '" rel="permalink">' . __( 'Duplicate', 'pzarchitect' ) . '</a>';
    }

    return $actions;
  }

  /*********************************************
   * Functions for exporting a preset
   */
  add_action( 'admin_action_pzarc_export_blueprint', 'pzarc_export_blueprint' );

  /*********************************************
   * pzarc_export_blueprint
   */
  function pzarc_export_blueprint() {
    global $wpdb;
    if ( ! ( isset( $_GET['post'] ) || isset( $_POST['post'] ) || ( isset( $_REQUEST['action'] ) && 'pzarc_export_blueprint' == $_REQUEST['action'] ) ) ) {
      wp_die( __( 'No post to export has been supplied!', 'pzarchitect' ) );
    }

    /*
     * get the original post id
     */
    $post_id = ( isset( $_GET['post'] ) ? $_GET['post'] : $_POST['post'] );
    /*
     * and all the original post data then
     */
    $post = get_post( $post_id );

    /*
     * if you don't want current user to be the new post author,
     * then change next couple of lines to this: $new_post_author = $post->post_author;
     */
    $current_user    = wp_get_current_user();
    $new_post_author = $current_user->ID;

    /*
     * if post data exists, create the post duplicate
     */
    if ( isset( $post ) && $post != NULL ) {
      $arc_exp_post['post']   = json_encode( $post );
      $arc_post_meta          = get_post_meta( $post->ID );
      $arc_exp_post['meta']   = json_encode( $arc_post_meta );
      $arc_exp_post['title']  = $post->post_title;
      $arc_exp_post['bptype'] = ( empty( $arc_post_meta['_blueprints_section-0-layout-mode'] ) ? 'basic' : $arc_post_meta['_blueprints_section-0-layout-mode'][0] );
      update_option( 'arc-export-to-preset', $arc_exp_post );
      $export_data = get_option( 'arc-export-to-preset' );
      if ( ! empty( $export_data ) ) {
        $title = $export_data['title'];
        delete_option( 'arc-export-to-preset' );

        // create file
        $url = wp_nonce_url( 'edit.php?post_type=arc-blueprints', basename( __FILE__ ) );

        if ( FALSE === ( $creds = request_filesystem_credentials( $url, '', FALSE, FALSE, NULL ) ) ) {
          return ''; // stop processing here
        }

        if ( ! WP_Filesystem( $creds ) ) {
          request_filesystem_credentials( $url, '', TRUE, FALSE, NULL );

          return '';
        }

        // create URL to file

        wp_mkdir_p( trailingslashit( PZARC_CACHE_PATH ) ); // Just in case
        $filename     = PZARC_CACHE_PATH . 'blueprint-' . sanitize_title( $title ) . '.txt';
        $filename_url = PZARC_CACHE_URL . 'blueprint-' . sanitize_title( $title ) . '.txt';

        // Create file
        global $wp_filesystem;
        $wp_filesystem->put_contents( $filename, json_encode( $export_data ), FS_CHMOD_FILE // predefined mode settings for WP files
        );
        if ( file_exists( $filename ) ) {
          header( 'Content-Description: File Transfer' );
          header( 'Content-Type: text/plain' );
          header( 'Content-Disposition: attachment; filename="' . basename( $filename ) . '"' );
          header( 'Expires: 0' );
          header( 'Cache-Control: must-revalidate' );
          header( 'Pragma: public' );
          header( 'Content-Length: ' . filesize( $filename ) );
          ob_clean();
          flush();
          readfile( $filename );
          exit;
        }        // TODO Tutorials on saving exports and creating Presets.
      }

      wp_redirect( admin_url( 'edit.php?post_type=' . $post->post_type ) );
      exit;
    }
  }

  /*********************************************
   * @param $actions
   * @param $post
   *
   * @return mixed
   */
  function pzarc_export_preset_link( $actions, $post ) {
    $screen = get_current_screen();
    if ( current_user_can( 'edit_posts' ) && $screen->id === 'edit-arc-blueprints' ) {
      $actions['export'] = '<a href="admin.php?action=pzarc_export_blueprint&amp;post=' . $post->ID . '" title="' . __( 'Export', 'pzarchitect' ) . '" rel="permalink">' . __( 'Export', 'pzarchitect' ) . '</a>';
    }

    return $actions;
  }

  //  add_filter( 'arc-panels_row_actions', 'pzarc_duplicate_post_link', 10, 2 );
  //  add_filter( 'arc-blueprints_row_actions', 'pzarc_duplicate_post_link', 10, 2 );
  //  add_filter( 'pz_snippets_row_actions', 'pzarc_duplicate_post_link', 10, 2 );

  function pzarc_about() {
    global $title;

    echo '<div class = "wrap">

			<!--Display Plugin Icon, Header, and Description-->
			<div class = "icon32" id = "icon-users"><br></div>
        <div class="pzarc-about-box" >
      <h1>Architect content block builder</h1>
			
			<h3>Architect successfully installed and activated.</h3>
			</div></div>';

  }

  /*********************************************
   * Sort posts in wp_list_table by column in ascending or descending order.
   */
  function pzarc_blueprints_order( $query ) {
    /*
        Set post types.
        _builtin => true returns WordPress default post types.
        _builtin => false returns custom registered post types.
    */
    $post_types = get_post_types( array(), 'names' );
    /* The current post type. */
    $post_type = $query->get( 'post_type' );
    /* Check post types. */
    if ( in_array( $post_type, $post_types ) && $post_type === 'arc-blueprints' ) {
      /* Post Column: e.g. title */
      if ( $query->get( 'orderby' ) == '' ) {
        $query->set( 'orderby', 'title' );
      }
      /* Post Order: ASC / DESC */
      if ( $query->get( 'order' ) == '' ) {
        $query->set( 'order', 'ASC' );
      }
    }
  }

  if ( is_admin() ) {
    add_action( 'pre_get_posts', 'pzarc_blueprints_order' );
  }


  //http://wpsnipp.com/index.php/functions-php/update-automatically-create-media_buttons-for-shortcode-selection/
  add_action( 'media_buttons', 'pzarc_add_sc_select', 11 );

  /*********************************************
   *
   */
  function pzarc_add_sc_select() {

    $screen   = get_current_screen();
    $user_can = current_user_can( 'edit_others_posts' );
    if ( $user_can && ( $screen->post_type === 'page' || $screen->post_type === 'post' ) ) {
      $blueprint_list = pzarc_get_posts_in_post_type( 'arc-blueprints', TRUE, FALSE, TRUE );
      echo '&nbsp;<select id="arc-select" class="arc-dropdown" style="font-size:small;"><option>Insert Architect Blueprint</option>';
      $shortcodes_list = '';
      foreach ( $blueprint_list as $key => $val ) {
        $shortcodes_list .= '<option value="' . $key . '">' . $val . '</option>';
      }

      echo $shortcodes_list;
      echo '</select>';
    }
  }

  add_action( 'admin_head', 'pzarc_button_js' );
  /*********************************************
   *
   */
  function pzarc_button_js() {
    echo "<script type='text/javascript'>
        jQuery(document).ready(function(){
           jQuery('#arc-select').change(function() {
           selected=jQuery('#arc-select :selected').val();
           if (selected !=='Insert Architect Blueprint'){
           var shortcode = '[architect blueprint=\"'+selected+'\"]';
            send_to_editor(shortcode);
            }
            jQuery('#arc-select>option:eq(0)').prop('selected', true);
           return false;
           });
        });
        </script>";
  }


  function pzarc_initiate_updater() {
    // TODO: Try to not run this too mcuh
    // Check on Headway if enabled since it was probably bought there
    $pzarc_status = pzarc_status();

    // Checks for HW and that we havem't already activated a Pizazz licence
    if ( class_exists( 'HeadwayUpdaterAPI' ) && ! ( $pzarc_status !== FALSE && $pzarc_status == 'valid' ) ) {

      $updater = new HeadwayUpdaterAPI( array(
          'slug'            => 'architect',
          'path'            => plugin_basename( __FILE__ ),
          'name'            => 'Architect',
          'type'            => 'block',
          'current_version' => PZARC_VERSION,
      ) );
    }

    require_once( PZARC_PLUGIN_APP_PATH . 'admin/php/edd-architect-plugin.php' );


    /**
     * Display update notices
     */
//    @include_once PZARC_DOCUMENTATION_PATH . 'updates/1000.php';

  }

  function pzarc_set_messages( $messages ) {
    global $post, $post_ID;
    $post_type = get_post_type( $post_ID );

    $obj      = get_post_type_object( $post_type );
    $singular = $obj->labels->singular_name;

    $messages[ $post_type ] = array(
        0  => '', // Unused. Messages start at index 1.
        1  => ( ! in_array( $post_type, array(
            'arc-blueprints',
            'pz_testimonials',
            'pz_snippets',
        ) ) ? sprintf( __( $singular . ' updated. <a href="%s">View ' . strtolower( $singular ) . '</a>', 'pzarchitect' ), esc_url( get_permalink( $post_ID ) ) ) : __( $singular . ' updated', 'pzarchitect' ) ),
        2  => __( 'Custom field updated.' ),
        3  => __( 'Custom field deleted.' ),
        4  => __( $singular . ' updated.' ),
        5  => isset( $_GET['revision'] ) ? sprintf( __( $singular . ' restored to revision from %s' ), wp_post_revision_title( (int) $_GET['revision'], FALSE ) ) : FALSE,
        6  => ( ! in_array( $post_type, array(
            'arc-blueprints',
            'pz_testimonials',
            'pz_snippets',
        ) ) ? sprintf( __( $singular . ' published. <a href="%s">View ' . strtolower( $singular ) . '</a>' ), esc_url( get_permalink( $post_ID ) ) ) : __( $singular . ' published', 'pzarchitect' ) ),
        7  => __( 'Page saved.' ),
        8  => sprintf( __( $singular . ' submitted. <a target="_blank" href="%s">Preview ' . strtolower( $singular ) . '</a>' ), esc_url( add_query_arg( 'preview', 'true', get_permalink( $post_ID ) ) ) ),
        9  => sprintf( __( $singular . ' scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview ' . strtolower( $singular ) . '</a>' ), date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ), esc_url( get_permalink( $post_ID ) ) ),
        10 => ( ! in_array( $post_type, array(
            'arc-blueprints',
            'pz_testimonials',
            'pz_snippets',
        ) ) ? sprintf( __( $singular . ' draft updated. <a target="_blank" href="%s">Preview ' . strtolower( $singular ) . '</a>' ), esc_url( add_query_arg( 'preview', 'true', get_permalink( $post_ID ) ) ) ) : __( $singular . ' draft updated', 'pzarchitect' ) ),
    );

    return $messages;
  }

  add_filter( 'post_updated_messages', 'pzarc_set_messages' );


  // SHORTCAKE
  add_action( 'register_shortcode_ui', 'pzarc_register_shortcake_shortcode' );
  function pzarc_register_shortcake_shortcode() {

    if ( ! function_exists( 'shortcode_ui_register_for_shortcode' ) ) {
//      add_action('admin_notices', function () {
//        if (current_user_can('activate_plugins')) {
//          echo '<div class="error message"><p>Shortcode UI plugin must be active for Shortcode UI Example plugin to function.</p></div>';
//        }
//      });

      return;
    }

    shortcode_ui_register_for_shortcode( 'architectsc', array(
        'label' => 'Architect',
    ) );


    /**
     * Register a UI for the Shortcode.
     * Pass the shortcode tag (string)
     * and an array or args.
     */
    $pzarc_blueprints = array_merge( array( 'none' => 'Select Blueprint' ), pzarc_get_blueprints(), array( 'show-none' => 'DO NOT SHOW ANY BLUEPRINT' ) );
    shortcode_ui_register_for_shortcode( 'architectsc', array(

      // Display label. String. Required.
      'label'         => __( 'Architect Blueprint', 'pzarchitect' ),
      // Icon/attachment for shortcode. Optional. src or dashicons-$icon. Defaults to carrot.
      'listItemImage' => '<img src="' . PZARC_PLUGIN_URL . '/assets/architect-logo-final-logo-only.svg">',
      // Visibility
      //         'post_type'     => array( 'post','page' ),

      // Available shortcode attributes and default values. Required. Array.
      // Attribute model expects 'attr', 'type' and 'label'
      // Supported field types: text, checkbox, textarea, radio, select, email, url, number, and date.
      'attrs'         => array(

          array(
              'label'   => __( 'Blueprint - any device', 'pzarchitect' ),
              'attr'    => 'blueprint',
              'type'    => 'select',
              'options' => $pzarc_blueprints,
          ),
          array(
              'label'   => __( 'Blueprint - tablet (optional)', 'pzarchitect' ),
              'attr'    => 'tablet',
              'type'    => 'select',
              'options' => $pzarc_blueprints,
          ),
          array(
              'label'   => __( 'Blueprint - phone (optional)', 'pzarchitect' ),
              'attr'    => 'phone',
              'type'    => 'select',
              'options' => $pzarc_blueprints,
          ),
          array(
              'label'       => __( 'Specific IDs (optional)', 'pzarchitect' ),
              'attr'        => 'ids',
              'type'        => 'text',
              'description' => __( 'Comma separated post, page, snippets, etc ids', 'pzarchitect' ),
          ),
          array(
              'label'   => __( 'Taxonomy (optional)', 'pzarchitect' ),
              'attr'    => 'tax',
              'type'    => 'select',
              'options' => pzarc_get_taxonomies( TRUE ),
          ),
          array(
              'label'       => __( 'Term IDs (optional)', 'pzarchitect' ),
              'attr'        => 'terms',
              'type'        => 'text',
              'description' => __( 'Comma separated term ids from the chosen taxonomy', 'pzarchitect' ),
          ),
      ),

    ) );

  }

  // Redux 3.6 solves this! - or not :(
  $max_input_vars     = (int) ini_get( 'max_input_vars' );
  $max_su_input_vars  = (int) ini_get( 'suhosin.post.max_vars' );
  $max_sur_input_vars = (int) ini_get( 'suhosin.request.max_vars' );
  global $_architect_options;
  if ( ! isset( $GLOBALS['_architect_options'] ) ) {
    $GLOBALS['_architect_options'] = get_option( '_architect_options', array() );
  }
  $arc_styling = ! empty( $_architect_options['architect_enable_styling'] ) ? 'arc-styling-on' : 'arc-styling-off';
  if ( is_admin() && $arc_styling === 'arc-styling-on' && ( ( $max_input_vars > 0 && (int) $max_input_vars < 2000 ) || ( $max_su_input_vars > 0 && (int) $max_su_input_vars < 2000 ) || ( $max_sur_input_vars > 0 && (int) $max_sur_input_vars < 2000 ) ) ) {

    /**
     * Display a message if mx_input_vars value is too low.
     */
    function pz_arc_update_max_input_vars() {
      if ( function_exists( 'get_current_screen' ) ) {
        $screen = get_current_screen();
        if ( $screen->post_type === 'arc-blueprints' ) {
          $max_input_vars     = (int) ini_get( 'max_input_vars' );
          $max_su_input_vars  = (int) ini_get( 'suhosin.post.max_vars' );
          $max_sur_input_vars = (int) ini_get( 'suhosin.request.max_vars' );

          global $current_user;
          $user_id = $current_user->ID;
          /* Check that the user hasn't already clicked to ignore the message */
          if ( ! get_user_meta( $user_id, 'pzarc_ignore_notice_max_inputs' ) ) {

            ?>

            <div class="notice notice-error is-dismissible" style="background:#fee;">
              <p><?php echo __( 'To use Architect with all features enabled, you will need to increase PHP\'s default limit on input variables.  Recommended is 2000.<br>', 'pzarchitect' );
                  if ( ( $max_su_input_vars > 0 && (int) $max_su_input_vars < 2000 ) || ( $max_sur_input_vars > 0 && (int) $max_sur_input_vars < 2000 ) ) {
                    _e( 'Your current settings are:<br>', 'pzarchitect' );
                    echo __( 'PHP Max Input Vars: ', 'pzarchitect' ) . $max_input_vars . '<br>';
                    echo __( 'Suhosin Max Input Vars: ', 'pzarchitect' ) . $max_su_input_vars . "<br>";
                    echo __( 'Suhosin Request Max Input Vars: ', 'pzarchitect' ) . $max_sur_input_vars . "<br>";
                    _e( 'Please follow WooCommerce\'s instructions here.', 'pzarchitect' );
                    echo ' <a href="https://docs.woothemes.com/document/problems-with-large-amounts-of-data-not-saving-variations-rates-etc//" target="_blank">Fields not saving</a><br>';
                  } else {
                    _e( 'Your current setting is:<br>', 'pzarchitect' );
                    echo __( 'PHP Max Input Vars: ', 'pzarchitect' ) . $max_input_vars . "<br>";
                    _e( 'Please follow the instructions here.', 'pzarchitect' );
                    echo ' <a href="http://architect4wp.com/codex/fields-not-saving/" target="_blank">Fields not saving</a><br>';
                  }
                  _e( '<strong>If you do not do this, some fields may not save.</strong><br>
                                Apologies for the inconvenience', 'pzarchitect' ); ?></p>
            </div>
            <?php
          }
        }
      }
    }

    add_action( 'admin_notices', 'pz_arc_update_max_input_vars' );
  }


  if ( ! function_exists( 'version_compare' ) || version_compare( PHP_VERSION, '5.4.0', '<' ) ) {
    function pz_arc_update_php() {
      if ( function_exists( 'get_current_screen' ) ) {
        $screen = get_current_screen();
        if ( in_array( $screen->id, array( 'edit-arc-blueprints', 'architect_page_pzarc_support' ) ) ) {
          echo '<div class="notice notice-error" >
                 <p><strong>Architect community service announcement:</strong> Your site is running PHP ' . PHP_VERSION . ', a <a href="https://www.wikiwand.com/en/PHP#/Release_history" target="_blank">potentially insecure version of PHP</a>. Please ask your host to upgrade it to at least PHP 5.4 but ideally 5.6.</p>
                </div>';
        }
      }
    }

    add_action( 'admin_notices', 'pz_arc_update_php' );
  }

  if ( ! function_exists( 'curl_init' ) ) {
    function pz_arc_curl_reqd() {
      if ( function_exists( 'get_current_screen' ) ) {
        $screen = get_current_screen();
//        d($screen);
        if ( in_array( $screen->id, array( 'edit-arc-blueprints', 'architect_page_pzarc_support', 'arc-blueprints' ) ) ) {
          echo '<div class="notice notice-error" >
                 <p>Architect requires the PHP cURL extension. Some features will not work without it. Please contact your host and request it to be enabled.</p>
                </div>';
        }
      }
    }

    add_action( 'admin_notices', 'pz_arc_curl_reqd' );
  }

  // Message re Headway licence transfer
  // If hw and not arc licence
  $current_theme = wp_get_theme();
  $is_hw         = ( ( $current_theme->get( 'Name' ) == 'Headway' || $current_theme->get( 'Name' ) == 'Headway Base' || $current_theme->get( 'Template' ) == 'headway' ) );
  $pzarc_status  = get_option( 'edd_architect_license_status' );

  if ( $is_hw && ! $pzarc_status && $pzarc_status !== 'valid' ) {
    function pz_hw_to_pizazz_licence() {
      if ( function_exists( 'get_current_screen' ) ) {
        $screen = get_current_screen();
        if ( in_array( $screen->id, array( 'edit-arc-blueprints' ) ) ) {
          echo '<div class="notice notice-error is-dismissible" style="background:tomato;color:#fff;margin-top:20px;">
                 <p><strong>Please crossgrade your Architect licence purchased from Headway to ensure you have the latest version.</strong>. <a style="color:#fff;" href="http://pizazzwp.com/cross-grade-your-architect-licence-from-the-headway-extend-store/" target="_blank" >Click here for more information.</a></p>
                </div>';
        }
      }
    }

    add_action( 'admin_notices', 'pz_hw_to_pizazz_licence' );

  }

