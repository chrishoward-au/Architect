<?php

  /** ACTIONS EDITOR */

  if (!(class_exists('ReduxFramework') || class_exists('ReduxFrameworkPlugin'))) {
    return;
  }

  if (!class_exists("Redux_Framework_Architect_Options_Actions_Editor")) {
    class Redux_Framework_Architect_Options_Actions_Editor
    {

      public $args = array();
      public $sections = array();
      public $theme;
      public $ReduxFramework;

      public function __construct()
      {

        // Set the default arguments
        $this->setArguments();

        // Set a few help tabs so you can see how it's done
        $this->setHelpTabs();

        // Create the sections and fields
        $this->setSections();

        if (!isset($this->args[ 'opt_name' ])) { // No errors please
          return;
        }

        // If Redux is running as a plugin, this will remove the demo notice and links
        //add_action( 'redux/plugin/hooks', array( $this, 'remove_demo' ) );

        // Function to test the compiler hook and demo CSS output.
        add_filter('redux/options/' . $this->args[ 'opt_name' ] . '/compiler', array($this, 'compiler_action'), 10, 2);
        // Above 10 is a priority, but 2 in necessary to include the dynamically generated CSS to be sent to the function.

        // Change the arguments after they've been declared, but before the panel is created
        //add_filter('redux/options/'.$this->args['opt_name'].'/args', array( $this, 'change_arguments' ) );

        // Change the default value of a field after it's been set, but before it's been used
        //add_filter('redux/options/'.$this->args['opt_name'].'/defaults', array( $this,'change_defaults' ) );

        // Dynamically add a section. Can be also used to modify sections/fields
        // add_filter('redux/options/' . $this->args[ 'opt_name' ] . '/sections', array($this, 'dynamic_section'));
//        global $wp_filter;
//                pzdebug((array_keys($wp_filter)));
        $this->ReduxFramework = new ReduxFramework($this->sections, $this->args);

      }


      /**
       *
       * This is a test function that will let you see when the compiler hook occurs.
       * It only runs if a field  set with compiler=>true is changed.
       **/

      function compiler_action($options, $css)
      {
//        echo "<h1>The compiler hook has run!";
//        var_dump($options); //Option values
//
//        var_dump($css); // Compiler selector CSS values  compiler => array( CSS SELECTORS )

        // Demo of how to use the dynamic CSS and write your own static CSS file
        $filename = PZARC_CACHE_PATH . '/arc-dynamic-styles' . '.css';
        //  var_dump($filename);
        global $wp_filesystem;
        if (empty($wp_filesystem)) {
          require_once(ABSPATH . '/wp-admin/includes/file.php');
          WP_Filesystem();
        }

        if ($wp_filesystem) {
          $wp_filesystem->put_contents(
              $filename,
              $css,
              FS_CHMOD_FILE // predefined mode settings for WP files
          );
        }

      }


      /**
       *
       * Custom function for filtering the sections array. Good for child themes to override or add to the sections.
       * Simply include this function in the child themes functions.php file.
       *
       * NOTE: the defined constants for URLs, and directories will NOT be available at this point in a child theme,
       * so you must use get_template_directory_uri() if you want to use any of the built in icons
       **/

//      function dynamic_section($sections)
//      {
//        //$sections = array();
//        $sections[ ] = array(
//            'title'  => __('Section via hook', 'pzarchitect'),
//            'desc'   => __('<p class="description">This is a section created by adding a filter to the sections array. Can be used by child themes to add/remove sections from the options.</p>', 'pzarchitect'),
//            'icon'   => 'el-icon-paper-clip',
//            // Leave this as a blank section, no options just some intro text set above.
//            'fields' => array()
//        );
//
//        return $sections;
//      }


      /**
       *
       * Filter hook for filtering the args. Good for child themes to override or add to the args array. Can also be used in other functions.
       **/

      function change_arguments($args)
      {
        //$args['dev_mode'] = true;

        return $args;
      }


      /**
       *
       * Filter hook for filtering the default value of any given field. Very useful in development mode.
       **/

      function change_defaults($defaults)
      {
        $defaults[ 'str_replace' ] = "Testing filter hook!";

        return $defaults;
      }


      public function setSections()
      {

        /**
         * Used within different fields. Simply examples. Search for ACTUAL DECLARATION for field examples
         **/


        // Background Patterns Reader
        $sample_patterns_path = ReduxFramework::$_dir . '../sample/patterns/';
        $sample_patterns_url  = ReduxFramework::$_url . '../sample/patterns/';
        $sample_patterns      = array();

        if (is_dir($sample_patterns_path)) :

          if ($sample_patterns_dir = opendir($sample_patterns_path)) :
            $sample_patterns = array();

            while (($sample_patterns_file = readdir($sample_patterns_dir)) !== false) {

              if (stristr($sample_patterns_file, '.png') !== false || stristr($sample_patterns_file, '.jpg') !== false) {
                $name               = explode(".", $sample_patterns_file);
                $name               = str_replace('.' . end($name), '', $sample_patterns_file);
                $sample_patterns[ ] = array('alt' => $name, 'img' => $sample_patterns_url . $sample_patterns_file);
              }
            }
          endif;
        endif;

        ob_start();

        $ct          = wp_get_theme();
        $this->theme = $ct;
        $item_name   = $this->theme->get('Name');
        $tags        = $this->theme->Tags;
        $screenshot  = $this->theme->get_screenshot();
        $class       = $screenshot ? 'has-screenshot' : '';

        $customize_title
            = sprintf(__('Customize &#8220;%s&#8221;', 'pzarchitect'), $this->theme->display('Name'));

        ?>
        <div id="current-theme" class="<?php echo esc_attr($class); ?>">
          <?php if ($screenshot) : ?>
            <?php if (current_user_can('edit_theme_options')) : ?>
              <a href="<?php echo wp_customize_url(); ?>" class="load-customize hide-if-no-customize"
                 title="<?php echo esc_attr($customize_title); ?>">
                <img src="<?php echo esc_url($screenshot); ?>" alt="<?php esc_attr_e('Current theme preview'); ?>"/>
              </a>
            <?php endif; ?>
            <img class="hide-if-customize" src="<?php echo esc_url($screenshot); ?>"
                 alt="<?php esc_attr_e('Current theme preview'); ?>"/>
          <?php endif; ?>

          <h4>
            <?php echo $this->theme->display('Name'); ?>
          </h4>

          <div>
            <ul class="theme-info">
              <li><?php printf(__('By %s', 'pzarchitect'), $this->theme->display('Author')); ?></li>
              <li><?php printf(__('Version %s', 'pzarchitect'), $this->theme->display('Version')); ?></li>
<!--              <li>--><?php //echo '<strong>' . __('Tags', 'pzarchitect') . ':</strong> '; ?><!----><?php //printf($this->theme->display('Tags')); ?><!--</li>-->
            </ul>
            <p class="theme-description"><?php echo $this->theme->display('Description'); ?></p>
            <?php if ($this->theme->parent()) {
              printf(' <p class="howto">' . __('This <a href="%1$s">child theme</a> requires its parent theme, %2$s.') . '</p>',
                     __('http://codex.wordpress.org/Child_Themes', 'pzarchitect'),
                     $this->theme->parent()->display('Name'));
            } ?>

          </div>

        </div>

        <?php
        $item_info = ob_get_contents();

        ob_end_clean();

        $sampleHTML = '';
        if (file_exists(dirname(__FILE__) . '/info-html.html')) {
          /** @global WP_Filesystem_Direct $wp_filesystem */
          global $wp_filesystem;
          if (empty($wp_filesystem)) {
            require_once(ABSPATH . '/wp-admin/includes/file.php');
            WP_Filesystem();
          }
          $sampleHTML = $wp_filesystem->get_contents(dirname(__FILE__) . '/info-html.html');
        }


        // ACTUAL DECLARATION OF SECTIONS
        $this->sections[ ] = array(
            'title'      => 'General ',
            'show_title' => true,
            'icon'       => 'el-icon-wrench',
            'fields'     => array(
                array(
                    'title'   => __('Number of Actions', 'pzarc'),
                    'id'      => 'architect_actions_number-of',
                    'type'    => 'spinner',
                    'default' => 1,
                    'min'     => '1',
                    'max'     => '100',
                    'step'    => '1',
                ),
            )
        );
        global $pzarc_blueprints_list;
        if (empty($pzarc_blueprints_list)) {
          $pzarc_blueprints_list   = pzarc_get_posts_in_post_type('arc-blueprints',true);

        }

        $actions_options   = get_option('_architect_actions');
        for ($i = 1; $i <= $actions_options[ 'architect_actions_number-of' ]; $i++) {
          $action_title      = (empty($actions_options[ 'architect_actions_' . $i . '_action-name' ]) ? 'Action ' . $i : $actions_options[ 'architect_actions_' . $i . '_action-name' ] . ' ' . $actions_options[ 'architect_actions_' . $i . '_blueprint' ]);
          $prefix            = 'architect_actions_' . $i . '_';
          $this->sections[ ] = array(
              'title'      => $action_title,
              'show_title' => true,
              'icon'       => 'el-icon-cogs',
              'fields'     => array(
                  array(
                      'title'   => __('Action Name', 'pzarc'),
                      'id'      => $prefix . 'action-name',
                      'type'    => 'text',
                      'default' => '',
                  ),
//                  array(
//                      'title'   => __('Order', 'pzarc'),
//                      'id'      => $prefix . 'order',
//                      'type'    => 'text',
//                      'default' => 10,
//                  ),
                  array(
                      'title'   => __('Blueprint shortname', 'pzarc'),
                      'id'      => $prefix . 'blueprint',
                      'type'    => 'select',
//                      'options' => pzarc_get_blueprints(false),
                      'options' => $pzarc_blueprints_list,
                      'default' => '',
                  ),
                  array(
                      'title'   => __('Page field_types', 'pzarc'),
                      'id'      => $prefix . 'pageids',
                      'type'    => 'select',
                      'multi'   => true,
                      'default' => '',
                      'options' => array(
                          'all'         => __('All','pzarchitect'),
                          'home'        => __('Home','pzarchitect'),
                          'specific'    => __('Specific IDs','pzarchitect'),
                          'single-post' => __('Single Post','pzarchitect'),
                          'single-page' => __('Single page','pzarchitect'),
                          'blog'        => __('Blog page','pzarchitect'),
                          'categories' => __('Category archives','pzarchitect'),
                          'tags'        => __('Tag archives','pzarchitect'),
                          'tax'        => __('Custom taxonomy archives','pzarchitect'),
                          'dates'       => __('Date archives','pzarchitect'),
                          'authors'     => __('Author archives','pzarchitect'),
                          'search'      => __('Search','pzarchitect'),
                          '404'         => __('404','pzarchitect')
                      )
                  ),
                  array(
                      'title'   => __('Specific page IDs', 'pzarc'),
                      'id'      => $prefix . 'specificids',
                      'type'    => 'text',
                      'default' => '',
                      'required'=> array($prefix . 'pageids','contains','specific')
                  ),
                  array(
                      'title'   => __('Custom taxonomy slugs', 'pzarc'),
                      'id'      => $prefix . 'tax-slugs',
                      'type'    => 'text',
                      'default' => '',
                      'required'=> array($prefix . 'pageids','contains','tax')
                  ),

              )
          );
        }

      }

      public function setHelpTabs()
      {

        // Custom page help tabs, displayed using the help API. Tabs are shown in order of definition.
        $this->args[ 'help_tabs' ][ ] = array(
            'id'      => 'redux-opts-1',
            'title'   => __('Theme Information 1', 'pzarchitect'),
            'content' => __('<p>This is the tab content, HTML is allowed.</p>', 'pzarchitect')
        );

        $this->args[ 'help_tabs' ][ ] = array(
            'id'      => 'redux-opts-2',
            'title'   => __('Theme Information 2', 'pzarchitect'),
            'content' => __('<p>This is the tab content, HTML is allowed.</p>', 'pzarchitect')
        );

        // Set the help sidebar
        $this->args[ 'help_sidebar' ]
            = __('<p>This is the sidebar content, HTML is allowed.</p>', 'pzarchitect');

      }


      /**
       *
       * All the possible arguments for Redux.
       * For full documentation on arguments, please refer to: https://github.com/ReduxFramework/ReduxFramework/wiki/Arguments
       **/
      public function setArguments()
      {

        $theme = wp_get_theme(); // For use with some settings. Not necessary.

        $this->args = array(

          // TYPICAL -> Change these values as you need/desire
          'opt_name'           => '_architect_actions',
          // This is where your data is stored in the database and also becomes your global variable name.
          'display_name'       => __('Actions Editor','pzarchitect'),
          // Name that appears at the top of your panel
          'display_version'    => 'Architect v' . PZARC_VERSION,
          // Version that appears at the top of your panel
          'menu_type'          => 'submenu',
          //Specify if the admin menu should appear or not. Options: menu or submenu (Under appearance only)
          'allow_sub_menu'     => false,
          // Show the sections below the admin menu item or not
          'menu_title'         => __('<span class="dashicons dashicons-migrate"></span>Actions Editor', 'pzarchitect'),
          'page'               => __('Actions Editor', 'pzarchitect'),
          'google_api_key'     => 'Xq9o3CdQFHKr+47vQr6eO4EUYLtlEyTe',
          // Must be defined to add google fonts to the typography module
          'global_variable'    => '',
          // Set a different name for your global variable other than the opt_name
          'dev_mode'           => false,
          // Show the time the page took to load, etc
          'customizer'         => false,
          // Enable basic customizer support

          // OPTIONAL -> Give you extra features
          'page_priority'      => null,
          // Order where the menu appears in the admin area. If there is any conflict, something will not show. Warning.
          'page_parent'        => 'pzarc',
          // For a full list of options, visit: http://codex.wordpress.org/Function_Reference/add_submenu_page#Parameters
          'page_permissions'   => 'manage_options',
          // Permissions needed to access the options panel.
          'menu_icon'          => '',
          // Specify a custom URL to an icon
          'last_tab'           => '',
          // Force your panel to always open to a specific tab (by id)
          'page_icon'          => 'icon-themes',
          // Icon displayed in the admin panel next to your menu_title
          'page_slug'          => '_architect_actions_editor',
          // Page slug used to denote the panel
          'save_defaults'      => true,
          // On load save the defaults to DB before user clicks save or not
          'default_show'       => false,
          // If true, shows the default value next to each field that is not the default value.
          'default_mark'       => '*',
          // What to print by the field's title if the value shown is default. Suggested: *


          // CAREFUL -> These options are for advanced use only
          'transient_time'     => 60 * MINUTE_IN_SECONDS,
          'output'             => false,
          // Global shut-off for dynamic CSS output by the framework. Will also disable google fonts output
          'output_tag'         => false,
          // Allows dynamic CSS to be generated for customizer and google fonts, but stops the dynamic CSS from going to the head
          //'domain'             	=> 'redux-framework', // Translation domain key. Don't change this unless you want to retranslate all of Redux.
          //'footer_credit'      	=> '', // Disable the footer credit of Redux. Please leave if you can help it.


          // FUTURE -> Not in use yet, but reserved or partially implemented. Use at your own risk.
          'database'           => '',
          // possible: options, theme_mods, theme_mods_expanded, transient. Not fully functional, warning!


          'show_import_export' => true,
          // REMOVE
          'system_info'        => false,
          // REMOVE

          'help_tabs'          => array(),
          'help_sidebar'       => '',
          'hints'              => array(
              'icon'          => 'icon-question-sign',
              'icon_position' => 'right',
              'icon_color'    => 'lightgray',
              'icon_size'     => 'normal',
              'tip_style'     => array(
                  'color'   => 'yellow',
                  'shadow'  => true,
                  'rounded' => false,
                  'style'   => '',
              ),
              'tip_position'  => array(
                  'my' => 'top right',
                  'at' => 'bottom left',
              ),
              'tip_effect'    => array(
                  'show' => array(
                      'effect'   => 'show',
                      'duration' => '300',
                      'event'    => 'mouseover',
                  ),
                  'hide' => array(
                      'effect'   => 'show',
                      'duration' => '300',
                      'event'    => 'click mouseleave',
                  ),
              ),
          )
          // __( '', $this->args['domain'] );
        );


        // SOCIAL ICONS -> Setup custom links in the footer for quick links in your panel footer icons.
        $this->args[ 'share_icons' ][ ] = array(
            'url'   => 'http://twitter.com/pizazzwp',
            'title' => 'Follow us on Twitter',
            'icon'  => 'el-icon-twitter'
        );


        // Panel Intro text -> before the form
        if (!isset($this->args[ 'global_variable' ]) || $this->args[ 'global_variable' ] !== false) {
          if (!empty($this->args[ 'global_variable' ])) {
            $v = $this->args[ 'global_variable' ];
          } else {
            $v = str_replace("-", "_", $this->args[ 'opt_name' ]);
          }
          $this->args[ 'intro_text' ]
              = sprintf(__('<p>On this page you can setup specific blueprints to display at specific points in your page\'s display. This is done using WordPress action hooks. Although WordPress provides many, the ones that work best for content display will be those included in the theme you are using. Review your theme and/or its documentation.</p>', 'pzarchitect'), $v);
        } else {
//          $this->args[ 'intro_text' ]
//              = __('<p>This text is displayed above the options panel. It isn\'t required, but more info is always better! The intro_text field accepts all HTML.</p>', 'pzarchitect');
        }

        // Add content after the form.
//        $this->args['footer_text'] = sprintf( __('<p><strong>$%1$s</strong></p>', 'pzarc' ), $v );
      }
    }

    new Redux_Framework_Architect_Options_Actions_Editor();
  }


   /**
   *
   * Custom function for the callback validation referenced above
   **/
  if (!function_exists('redux_validate_callback_function')):
    function redux_validate_callback_function($field, $value, $existing_value)
    {
      $error = false;
      $value = 'just testing';
      /*
      do your validation

      if(something) {
          $value = $value;
      } elseif(something else) {
          $error = true;
          $value = $existing_value;
          $field['msg'] = 'your custom error message';
      }
      */

      $return[ 'value' ] = $value;
      if ($error == true) {
        $return[ 'error' ] = $field;
      }

      return $return;
    }
  endif;

