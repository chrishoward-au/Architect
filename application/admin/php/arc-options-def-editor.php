<?php

  /** PANELS DEFINITION EDITOR */

  if (!(class_exists('ReduxFramework') || class_exists('ReduxFrameworkPlugin'))) {
    return;
  }

  if (!class_exists("Redux_Framework_Architect_Def_Editor_config")) {
    class Redux_Framework_Architect_Def_Editor_config
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
        // var_dump($filename);
        global $wp_filesystem;
        if (empty($wp_filesystem)) {
          require_once(ABSPATH . '/wp-admin/shared/file.php');
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

        $prefix = 'architect_panels_def_';

        // ACTUAL DECLARATION OF SECTIONS
        $this->sections[ ] = array(
            'title'      => 'Panel Definitions Editor',
            'show_title' => false,
            'icon_class' => 'icon-large',
            'icon'       => 'el-icon-pencil',
            'fields'     => array(
//                $panel_def[ 'panel-open' ]   = '{{bgimagetl}}<article id="post-{{postid}}" class="block-type-content post-{{postid}} post type-{{posttype}} status-{{poststatus}} format-{{postformat}} hentry {{categories}} {{tags}} {{pzclasses}}">';
//        $panel_def[ 'panel-close' ]  = '</article>{{bgimagebr}}';
//        $panel_def[ 'postlink' ]     = '<a href="{{permalink}}" title="{{title}}">';
//        $panel_def[ 'header' ]       = '<header class="entry-header">{{headerinnards}}</header><!-- .entry-header -->';
//        $panel_def[ 'title' ]        = '<h1 class="entry-title">{{postlink}}{{title}}{{closepostlink}}</h1>';
//        $panel_def[ 'meta1' ]        = '<div class="entry-meta entry-meta1">{{meta1innards}}</div><!-- .entry-meta 1 -->';
//        $panel_def[ 'meta2' ]        = '<div class="entry-meta entry-meta2">{{meta2innards}}</div><!-- .entry-meta 2 -->';
//        $panel_def[ 'meta3' ]        = '<div class="entry-meta entry-meta3">{{meta3innards}}</div><!-- .entry-meta 3 -->';
//        $panel_def[ 'datetime' ]     = '<span class="date"><time class="entry-date" datetime="{{datetime}}">{{fdatetime}}</time></span>';
//        $panel_def[ 'categories' ]   = '<span class="categories-links">{{categorieslinks}}</span>';
//        $panel_def[ 'tags' ]         = '<span class="tags-links">{{tagslinks}}</span>';
//        $panel_def[ 'author' ]       = '<span class="author vcard"><a class="url fn n" href="{{authorlink}}" title="View all posts by {{authorname}}" rel="author">{{authorname}}</a></span>';
//        $panel_def[ 'image' ]        = '<figure class="entry-thumbnail {{incontent}}">{{postlink}}<img width="{{width}}" src="{{imgsrc}}" class="attachment-post-thumbnail wp-post-image" alt="{{alttext}}">{{closepostlink}}{{captioncode}}</figure>';
//        $panel_def[ 'caption' ]      = '<figcaption class="caption">{{caption}}</figcaption>';
//        $panel_def[ 'content' ]      = ' <div class="entry-content {{nothumb}}">{{image-in-content}}{{content}}</div><!-- .entry-content -->';
//        $panel_def[ 'custom1' ]      = '<div class="entry-customfield entry-customfield-1">{{custom1innards}}</div><!-- .entry-custom 1 -->';
//        $panel_def[ 'custom2' ]      = '<div class="entry-customfield entry-customfield-2">custom2innards}}</div><!-- .entry-custom 2 -->';
//        $panel_def[ 'custom3' ]      = '<div class="entry-customfield entry-customfield-3">{{custom3innards}}</div><!-- .entry-custom 3 -->';
//        $panel_def[ 'footer' ]       = '<footer class="entry-footer">{{footerinnards}}</footer><!-- .entry-meta -->';
//        $panel_def[ 'excerpt' ]      = ' <div class="entry-excerpt {{nothumb}}">{{image-in-content}}{{excerpt}}</div><!-- .entry-excerpt -->';
//        $panel_def[ 'image' ]        = '{{postlink}}{{image}}{{closelink}}';
//        $panel_def[ 'feature' ]      = '{{feature}}';

array(
    'title'   => __('Definition Name', 'pzarc'),
    'id'      => $prefix . 'definition-name',
    'type'    => 'text',
    'default' => '',
    'validate' => 'not_empty'
),
array(
    'title'   => __('Panel open', 'pzarc'),
    'id'      => $prefix . 'panels-open',
    'type'    => 'textarea',
    'rows'    => 2,
    'default' => '',
),
array(
    'title'   => __('Header', 'pzarc'),
    'id'      => $prefix . 'header',
    'type'    => 'textarea',
    'rows'    => 2,
    'default' => '',
),
array(
    'title'   => __('Title', 'pzarc'),
    'id'      => $prefix . 'title',
    'type'    => 'textarea',
    'rows'    => 2,
    'default' => '',
),
array(
    'title'   => __('Meta area 1', 'pzarc'),
    'id'      => $prefix . 'meta1',
    'type'    => 'textarea',
    'rows'    => 2,
    'default' => '',
),
array(
    'title'   => __('Date/Time', 'pzarc'),
    'id'      => $prefix . 'datetime',
    'type'    => 'textarea',
    'rows'    => 2,
    'default' => '',
),
array(
    'title'   => __('Categories', 'pzarc'),
    'id'      => $prefix . 'categories',
    'type'    => 'textarea',
    'rows'    => 2,
    'default' => '',
),
array(
    'title'   => __('Tags', 'pzarc'),
    'id'      => $prefix . 'tags',
    'type'    => 'textarea',
    'rows'    => 2,
    'default' => '',
),
array(
    'title'   => __('Author', 'pzarc'),
    'id'      => $prefix . 'author',
    'type'    => 'textarea',
    'rows'    => 2,
    'default' => '',
),
array(
    'title'   => __('Image', 'pzarc'),
    'id'      => $prefix . 'image',
    'type'    => 'textarea',
    'rows'    => 2,
    'default' => '',
),
array(
    'title'   => __('Content', 'pzarc'),
    'id'      => $prefix . 'content',
    'type'    => 'textarea',
    'rows'    => 2,
    'default' => '',
),
array(
    'title'   => __('Excerpt', 'pzarc'),
    'id'      => $prefix . 'excerpt',
    'type'    => 'textarea',
    'rows'    => 2,
    'default' => '',
),
array(
    'title'   => __('feature', 'pzarc'),
    'id'      => $prefix . 'footer',
    'type'    => 'textarea',
    'rows'    => 2,
    'default' => '',
),
array(
    'title'   => __('footer', 'pzarc'),
    'id'      => $prefix . 'footer',
    'type'    => 'textarea',
    'rows'    => 2,
    'default' => '',
),
array(
    'title'   => __('Panel close', 'pzarc'),
    'id'      => $prefix . 'panels-close',
    'type'    => 'textarea',
    'rows'    => 2,
    'default' => '',
),
            )
        );
        $this->sections[ ] = array(
            'id'         => 'def-editor-help',
            'title'      => 'Help',
            'icon_class' => 'icon-large',
            'icon'       => 'el-icon-info-sign',
            'fields'     => array(

                array(
                    'title' => __('Panel Definitions Editor', 'pzarc'),
                    'id'    => $prefix . 'panels-help-def-editor',
                    'type'  => 'info',
                    //  'class' => 'plain',
                    'desc'  => '<h3>Tags</h3>
<p>{{title}}</p>
<p>{{content}}</p>
<p>{{excerpt}}</p>
<p>{{date}}</p>
<p>{{}}</p>
<p>{{}}</p>
<p>{{}}</p>
<p>{{}}</p>
<p>{{}}</p>
<p>{{}}</p>

'

                )
            )
        );


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
          'opt_name'           => '_architect_def',
          // This is where your data is stored in the database and also becomes your global variable name.
          'display_name'       => 'Panel Definitions Editor',
          // Name that appears at the top of your panel
          'display_version'    => 'Architect v' . PZARC_VERSION,
          // Version that appears at the top of your panel
          'menu_type'          => 'submenu',
          //Specify if the admin menu should appear or not. Options: menu or submenu (Under appearance only)
          'allow_sub_menu'     => false,
          // Show the sections below the admin menu item or not
          'menu_title'         => __('<span class="dashicons dashicons-edit"></span>Panel Definitions', 'pzarc'),
          'page'               => __('Panel Definitions Editor', 'pzarc'),
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
          'page_slug'          => '_architect_def_editor',
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
              = sprintf(__('<p>On this page you can configure default CSS styling as well as indicating the classes it applies to.</p>', 'pzarchitect'), $v);
        } else {
//          $this->args[ 'intro_text' ]
//              = __('<p>This text is displayed above the options panel. It isn\'t required, but more info is always better! The intro_text field accepts all HTML.</p>', 'pzarchitect');
        }

        // Add content after the form.
//        $this->args['footer_text'] = sprintf( __('<p><strong>$%1$s</strong></p>', 'pzarc' ), $v );
      }
    }

    new Redux_Framework_Architect_Def_Editor_config();
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
