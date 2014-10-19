<?php


  if (!(class_exists('ReduxFramework') || class_exists('ReduxFrameworkPlugin'))) {
    return;
  }

  if (!class_exists("Redux_Framework_Architect_Options_Styling")) {
    class Redux_Framework_Architect_Options_Styling
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
          require_once(ABSPATH . '/wp-admin/includes/file.php');
          WP_Filesystem();
        }

        // TODO Remove once bug of ACE not being set to $css
        $css .= $options[ 'architect_config_custom-css' ];
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
//            'title'  => __('Section via hook', 'redux-framework-demo'),
//            'desc'   => __('<p class="description">This is a section created by adding a filter to the sections array. Can be used by child themes to add/remove sections from the options.</p>', 'redux-framework-demo'),
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

        if (is_dir($sample_patterns_path)) : {
          if ($sample_patterns_dir = opendir($sample_patterns_path)) : {
            $sample_patterns = array();

            while (($sample_patterns_file = readdir($sample_patterns_dir)) !== false) {

              if (stristr($sample_patterns_file, '.png') !== false || stristr($sample_patterns_file, '.jpg') !== false) {
                $name               = explode(".", $sample_patterns_file);
                $name               = str_replace('.' . end($name), '', $sample_patterns_file);
                $sample_patterns[ ] = array('alt' => $name, 'img' => $sample_patterns_url . $sample_patterns_file);
              }
            }
          }
          endif;
        }
        endif;

        ob_start();

        $ct          = wp_get_theme();
        $this->theme = $ct;
        $item_name   = $this->theme->get('Name');
        $tags        = $this->theme->Tags;
        $screenshot  = $this->theme->get_screenshot();
        $class       = $screenshot ? 'has-screenshot' : '';

        $customize_title
            = sprintf(__('Customize &#8220;%s&#8221;', 'redux-framework-demo'), $this->theme->display('Name'));

        ?>
        <div id="current-theme" class="<?php echo esc_attr($class); ?>">
          <?php if ($screenshot) : { ?>
            <?php if (current_user_can('edit_theme_options')) : { ?>
              <a href="<?php echo wp_customize_url(); ?>" class="load-customize hide-if-no-customize"
                 title="<?php echo esc_attr($customize_title); ?>">
                <img src="<?php echo esc_url($screenshot); ?>" alt="<?php esc_attr_e('Current theme preview'); ?>"/>
              </a>
            <?php } endif; ?>
            <img class="hide-if-customize" src="<?php echo esc_url($screenshot); ?>"
                 alt="<?php esc_attr_e('Current theme preview'); ?>"/>
          <?php } endif; ?>

          <h4>
            <?php echo $this->theme->display('Name'); ?>
          </h4>

          <div>
            <ul class="theme-info">
              <li><?php printf(__('By %s', 'redux-framework-demo'), $this->theme->display('Author')); ?></li>
              <li><?php printf(__('Version %s', 'redux-framework-demo'), $this->theme->display('Version')); ?></li>
              <li><?php echo '<strong>' . __('Tags', 'redux-framework-demo') . ':</strong> '; ?><?php printf($this->theme->display('Tags')); ?></li>
            </ul>
            <p class="theme-description"><?php echo $this->theme->display('Description'); ?></p>
            <?php if ($this->theme->parent()) {
              printf(' <p class="howto">' . __('This <a href="%1$s">child theme</a> requires its parent theme, %2$s.') . '</p>',
                     __('http://codex.wordpress.org/Child_Themes', 'redux-framework-demo'),
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

        $prefix = 'architect_config_';

        // ACTUAL DECLARATION OF SECTIONS
        $this->sections[ ] = array(
            'title'      => 'General',
            'show_title' => false,
            'icon_class' => 'icon-large',
            'icon'       => 'el-icon-brush',
            'fields'     => array(

                array(
                    'title'    => __('Panels', 'pzarc'),
                    'id'       => $prefix . 'panels',
                    'type'     => 'section',
                    'class'    => 'heading',
                    'subtitle' => 'Class: .pzarc-panel',
                ),
                array(
                    'title'    => __('CSS selectors', 'pzarc'),
                    'id'       => $prefix . 'panels-selectors',
                    'type'     => 'text',
                    'default'  => '.pzarc-panel',
                    'readonly' => true
                ),
                pzarc_redux_bg($prefix . 'panels-background', array('.pzarc-panel')),
                pzarc_redux_padding($prefix . 'panels-padding', array('.pzarc-panel')),
                pzarc_redux_borders($prefix . 'panels-borders', array('.pzarc-panel')),
                array(
                    'title'    => __('Components group', 'pzarc'),
                    'id'       => $prefix . 'components-group-section-start',
                    'type'     => 'section',
                    'class'    => 'heading',
                    'subtitle' => 'Class: .pzarc-components',
                    'indent'   => false
                ),
                array(
                    'title'    => __('CSS selectors', 'pzarc'),
                    'id'       => $prefix . 'components-group-selectors',
                    'type'     => 'text',
                    'default'  => '.pzarc-components',
                    'readonly' => true
                ),
                pzarc_redux_bg($prefix . 'components-background', array('.pzarc_components')),
                pzarc_redux_padding($prefix . 'components-padding', array('.pzarc_components')),
                pzarc_redux_borders($prefix . 'components-borders', array('.pzarc_components')),
                array(
                    'id'     => $prefix . 'components-group-section-end',
                    'type'   => 'section',
                    'indent' => false,
                ),
                array(
                    'title' => __('Entry', 'pzarc'),
                    'id'    => $prefix . 'entry',
                    'type'  => 'section',
                    'class' => 'heading',
                ),
                array(
                    'title'    => __('CSS selectors', 'pzarc'),
                    'id'       => $prefix . 'hentry-selectors',
                    'type'     => 'multi_text',
                    'default'  => array('.hentry'),
                    'subtitle' => 'One per row',
                    'hint'     => array(
                        'title'   => 'Hint Title',
                        'content' => 'This is the content of the tool-tip'
                    )
                ),
                pzarc_redux_bg($prefix . 'hentry-background', array('.hentry')),
                pzarc_redux_padding($prefix . 'hentry-padding', array('.hentry')),
                pzarc_redux_margin($prefix . 'hentry-margin', array('.hentry')),
                pzarc_redux_borders($prefix . 'hentry-borders', array('.hentry')),
            )
        );
        $this->sections[ ] = array(
            'title'      => 'Titles',
            'show_title' => false,
            'icon_class' => 'icon-large',
            'icon'       => 'el-icon-font',
            'desc'       => 'Class: .pzarc_entry-title',
            'fields'     => array(
                pzarc_redux_font($prefix . 'entry-title-font', array('.entry-title'), array('line_height'     => 1.2,
                                                                                            'text_decoration' => 'none')),
                pzarc_redux_bg($prefix . 'entry-title-font-background', array('.entry-title')),
                pzarc_redux_padding($prefix . 'entry-title-font-padding', array('.entry-title')),
                pzarc_redux_margin($prefix . 'entry-title-font-margin', array('.entry-title')),
                pzarc_redux_links($prefix . 'entry-title-font-links', array('.entry-title a')),
                pzarc_redux_borders($prefix . 'entry-title-borders', array('.entry-title')),
            ),
        );

        $this->sections[ ] = array(
            'title'      => 'Meta',
            'show_title' => false,
            'icon_class' => 'icon-large',
            'icon'       => 'el-icon-calendar',
            'desc'       => 'Class: .pzarc_entry_meta',
            'fields'     => array(
                pzarc_redux_font($prefix . 'entry-meta-font', array('.entry-meta')),
                pzarc_redux_bg($prefix . 'entry-meta-font-background', array('.entry-meta')),
                pzarc_redux_padding($prefix . 'entry-meta-font-padding', array('.entry-meta')),
                pzarc_redux_links($prefix . 'entry-meta-font-links', array('.entry-meta a')),
            )
        );
        $this->sections[ ] = array(
            'title'      => 'Content',
            'show_title' => false,
            'icon_class' => 'icon-large',
            'icon'       => 'el-icon-align-left',
            'fields'     => array(
                pzarc_redux_font($prefix . 'entry-content-font', array('.entry-content')),
                pzarc_redux_bg($prefix . 'entry-content-font-background', array('.entry-content')),
                pzarc_redux_padding($prefix . 'entry-content-font-padding', array('.entry-content')),
                pzarc_redux_links($prefix . 'entry-content-font-links', array('.entry-content a')),
                array(
                    'title'    => __('Read more', 'pzarc'),
                    'id'       => $prefix . 'entry-readmore',
                    'type'     => 'section',
                    'class'    => 'heading',
                    //        'default' => $defaults[ $optprefix . 'content_defaults_entry-readmore-defaults' ],
                    'subtitle' => 'Class: a.pzarc_readmore',
                ),
                pzarc_redux_font($prefix . 'entry-readmore-font', array('.readmore')),
                pzarc_redux_bg($prefix . 'entry-readmore-font-background', array('.readmore')),
                pzarc_redux_padding($prefix . 'entry-readmore-font-padding', array('.readmore')),
                pzarc_redux_links($prefix . 'entry-readmore-font-links', array('a.readmore')),
            )
        );
        $this->sections[ ] = array(
            'title'      => 'Entry Featured image',
            'show_title' => false,
            'icon_class' => 'icon-large',
            'icon'       => 'el-icon-picture',
            'fields'     => array(
                array(
                    'title'    => __('Image', 'pzarc'),
                    'id'       => $prefix . 'entry-image',
                    'type'     => 'section',
                    'class'    => 'heading',
                    //          'default' => $defaults[ $optprefix . 'image_defaults_entry-image-defaults' ],
                    'subtitle' => 'Class: .pzarc_entry_featured_image',
                    //     'subtitle'    => __('Format the entry featured image', 'pzarc')
                ),
                pzarc_redux_bg($prefix . 'entry-image-background', array('.entry-title')),
                pzarc_redux_padding($prefix . 'entry-image-padding', array('.entry-title')),
                pzarc_redux_borders($prefix . 'entry-image-borders', array('.entry-title')),
                array(
                    'title' => __('Caption', 'pzarc'),
                    'id'    => $prefix . 'entry-image-caption',
                    'type'  => 'section',
                    'class' => 'heading',
                    //    'default' => $defaults[ $optprefix . 'image_defaults_entry-image-caption-defaults' ],
                ),
                pzarc_redux_font($prefix . 'entry-image-caption-font', array('.entry-thumbnail caption')),
                pzarc_redux_bg($prefix . 'entry-image-caption-font-background', array('.entry-thumbnail caption')),
                pzarc_redux_padding($prefix . 'entry-image-caption-font-padding', array('.entry-thumbnail caption')),
            )


        );
        $this->sections[ ] = array(
            'id'         => 'custom-css',
            'title'      => 'Custom CSS',
            'icon_class' => 'icon-large',
            'icon'       => 'el-icon-wrench',
            'fields'     => array(
                array(
                    'id'       => $prefix . 'custom-css',
                    'type'     => 'ace_editor',
                    'title'    => __('Custom CSS', 'pzarc'),
                    'subtitle' => __('Enter any custom CSS at all here and it will be loaded with each page. Use wisely!'),
                    'mode'     => 'css',
                    'theme'    => 'chrome',
                    'compiler' => true,
                    'default'  => '.pzarchitect p {word-break:normal;}'
                ),
            )
        );
        $this->sections[ ] = array(
            'id'         => 'styling-help',
            'title'      => 'Help',
            'icon_class' => 'icon-large',
            'icon'       => 'el-icon-info-sign',
            'fields'     => array(

                array(
                    'title' => __('Design', 'pzarc'),
                    'id'    => $prefix . 'panels-help-design',
                    'type'  => 'section',
                    //  'class' => 'plain',
                    'desc'  => '<p>
                              Fiant nulla claritatem processus vulputate quarta. Anteposuerit eodem habent parum id et. Notare mutationem facilisi nulla ut facer.
                              </p>

                              <p>
                              Nam minim quis est typi nostrud. Et nunc in legere dignissim decima. Feugiat facilisi nulla lectores quod esse.
                              </p>

                              <p>
                              Nostrud ipsum usus nam ut magna. Zzril nobis qui est nonummy in. Nonummy seacula dolore amet ipsum decima.
                              </p>

                              <p>
                              Nibh cum lorem iriure laoreet ut. Nihil in vel diam sit iusto. Eorum tempor ea zzril dynamicus consuetudium.
                              </p>

                              <p>
                              Ut at consectetuer blandit nibh in.
                              </p>'

                )
            )
        );


      }

      public function setHelpTabs()
      {

        // Custom page help tabs, displayed using the help API. Tabs are shown in order of definition.
        $this->args[ 'help_tabs' ][ ] = array(
            'id'      => 'redux-opts-1',
            'title'   => __('Theme Information 1', 'redux-framework-demo'),
            'content' => __('<p>This is the tab content, HTML is allowed.</p>', 'redux-framework-demo')
        );

        $this->args[ 'help_tabs' ][ ] = array(
            'id'      => 'redux-opts-2',
            'title'   => __('Theme Information 2', 'redux-framework-demo'),
            'content' => __('<p>This is the tab content, HTML is allowed.</p>', 'redux-framework-demo')
        );

        // Set the help sidebar
        $this->args[ 'help_sidebar' ]
            = __('<p>This is the sidebar content, HTML is allowed.</p>', 'redux-framework-demo');

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
          'opt_name'           => '_architect',
          // This is where your data is stored in the database and also becomes your global variable name.
          'display_name'       => 'Architect Styling Defaults',
          // Name that appears at the top of your panel
          'display_version'    => 'Architect v' . PZARC_VERSION,
          // Version that appears at the top of your panel
          'menu_type'          => 'submenu',
          //Specify if the admin menu should appear or not. Options: menu or submenu (Under appearance only)
          'allow_sub_menu'     => false,
          // Show the sections below the admin menu item or not
          'menu_title'         => __('<span class="dashicons dashicons-art"></span>Styling Defaults', 'pzarc'),
          'page'               => __('Architect Styling', 'pzarc'),
          'google_api_key'     => 'Xq9o3CdQFHKr+47vQr6eO4EUYLtlEyTe',
          // Must be defined to add google fonts to the typography module
//          'global_variable'    => 'pzarchitect',
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
          'page_slug'          => '_architect_styling',
          // Page slug used to denote the panel
          'save_defaults'      => true,
          // On load save the defaults to DB before user clicks save or not
          'default_show'       => false,
          // If true, shows the default value next to each field that is not the default value.
          'default_mark'       => '',
          // What to print by the field's title if the value shown is default. Suggested: *


          // CAREFUL -> These options are for advanced use only
          'transient_time'     => 60 * MINUTE_IN_SECONDS,
          'output'             => true,
          // Global shut-off for dynamic CSS output by the framework. Will also disable google fonts output
          'output_tag'         => true,
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
              = sprintf(__('<p>On this page you can configure default CSS styling as well as indicating the classes it applies to.</p>', 'redux-framework-demo'), $v);
        } else {
//          $this->args[ 'intro_text' ]
//              = __('<p>This text is displayed above the options panel. It isn\'t required, but more info is always better! The intro_text field accepts all HTML.</p>', 'redux-framework-demo');
        }

        // Add content after the form.
//        $this->args['footer_text'] = sprintf( __('<p><strong>$%1$s</strong></p>', 'pzarc' ), $v );
      }
    }

    new Redux_Framework_Architect_Options_Styling();
  }


  /**
   *
   * Custom function for the callback referenced above

   */
  if (!function_exists('redux_my_custom_field')): {
    function redux_my_custom_field($field, $value)
    {
      print_r($field);
      print_r($value);
    }
  }
  endif;

  /**
   *
   * Custom function for the callback validation referenced above
   **/
  if (!function_exists('redux_validate_callback_function')): {
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
  }
  endif;
  // Redux tracking
  if (!function_exists('pzarc_redux_tracking')) {
    function pzarc_redux_tracking($options)
    {
      $opt                                                       = array();
      $options[ 'DqDE7uzWFMdHsJsRIjveviQBVuE3Q75C03YLUt7rhVw=' ] = true;

      // var_dump($options);
      return $options;
    }

    add_filter('redux/tracking/developer', 'pzarc_redux_tracking');

  }
