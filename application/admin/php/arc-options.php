<?php

  /** OPTIONS */

  if ( ! ( class_exists( 'ReduxFramework' ) || class_exists( 'ReduxFrameworkPlugin' ) ) ) {
    return;
  }

  if ( ! class_exists( "Redux_Framework_Architect_Options" ) ) {
    class Redux_Framework_Architect_Options {

      public $args = array();
      public $sections = array();
      public $theme;
      public $ReduxFramework;

//      public function __construct() {
//        if ( ! class_exists( 'ReduxFramework' ) ) {
//          return;
//        }
//        // This is needed. Bah WordPress bugs.  ;)
//        if ( true == Redux_Helpers::isTheme( __FILE__ ) ) {
//          pzdebug();
//          $this->initSettings();
//        } else {
//          pzdebug();
//          add_action( 'plugins_loaded', array( $this, 'initSettings' ), 10 );
//        }
//      }
//      public function initSettings() {
//        // Just for demo purposes. Not needed per say.
//        $this->theme = wp_get_theme();
//        // Set the default arguments
//        $this->setArguments();
//        // Set a few help tabs so you can see how it's done
//        $this->setHelpTabs();
//        pzdebug();
//        // Create the sections and fields
//        $this->setSections();
//        if ( ! isset( $this->args['opt_name'] ) ) { // No errors please
//          return;
//        }
//        // If Redux is running as a plugin, this will remove the demo notice and links
//        add_action( 'redux/loaded', array( $this, 'remove_demo' ) );
//        // Function to test the compiler hook and demo CSS output.
//        // Above 10 is a priority, but 2 in necessary to include the dynamically generated CSS to be sent to the function.
//        //add_filter('redux/options/'.$this->args['opt_name'].'/compiler', array( $this, 'compiler_action' ), 10, 3);
//        // Change the arguments after they've been declared, but before the panel is created
//        //add_filter('redux/options/'.$this->args['opt_name'].'/args', array( $this, 'change_arguments' ) );
//        // Change the default value of a field after it's been set, but before it's been useds
//        //add_filter('redux/options/'.$this->args['opt_name'].'/defaults', array( $this,'change_defaults' ) );
//        // Dynamically add a section. Can be also used to modify sections/fields
//        //add_filter('redux/options/' . $this->args['opt_name'] . '/sections', array($this, 'dynamic_section'));
//        $this->ReduxFramework = new ReduxFramework( $this->sections, $this->args );
//      }


      public function __construct() {

        // Set the default arguments
        $this->setArguments();

        // Set a few help tabs so you can see how it's done
        $this->setHelpTabs();

        // Create the sections and fields
        $this->setSections();

        if ( ! isset( $this->args[ 'opt_name' ] ) ) { // No errors please
          return;
        }

        // If Redux is running as a plugin, this will remove the demo notice and links
        //add_action( 'redux/plugin/hooks', array( $this, 'remove_demo' ) );

        // Function to test the compiler hook and demo CSS output.
        add_filter( 'redux/options/' . $this->args[ 'opt_name' ] . '/compiler', array(
          $this,
          'compiler_action'
        ), 10, 2 );
        // Above 10 is a priority, but 2 in necessary to include the dynamically generated CSS to be sent to the function.

        // Change the arguments after they've been declared, but before the panel is created
        //add_filter('redux/options/'.$this->args['opt_name'].'/args', array( $this, 'change_arguments' ) );

        // Change the default value of a field after it's been set, but before it's been used
        //add_filter('redux/options/'.$this->args['opt_name'].'/defaults', array( $this,'change_defaults' ) );

        // Dynamically add a section. Can be also used to modify sections/fields
        // add_filter('redux/options/' . $this->args[ 'opt_name' ] . '/sections', array($this, 'dynamic_section'));
//        global $wp_filter;
//                pzdebug((array_keys($wp_filter)));
        $this->ReduxFramework = new ReduxFramework( $this->sections, $this->args );

      }


      /**
       *
       * This is a test function that will let you see when the compiler hook occurs.
       * It only runs if a field  set with compiler=>true is changed.
       **/

      function compiler_action( $options, $css ) {
//        echo "<h1>The compiler hook has run!";
//        var_dump($options); //Option values
//
//        var_dump($css); // Compiler selector CSS values  compiler => array( CSS SELECTORS )

        // Demo of how to use the dynamic CSS and write your own static CSS file
        // error here. file.php not found!

        $filename = PZARC_CACHE_PATH . '/arc-dynamic-styles' . '.css';
        //  var_dump($filename);
        global $wp_filesystem;
        if ( empty( $wp_filesystem ) ) {
          require_once( ABSPATH . '/wp-admin/includes/file.php' );
          WP_Filesystem();
        }

        if ( $wp_filesystem ) {
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

      function change_arguments( $args ) {
        //$args['dev_mode'] = true;

        return $args;
      }


      /**
       *
       * Filter hook for filtering the default value of any given field. Very useful in development mode.
       **/

      function change_defaults( $defaults ) {
        $defaults[ 'str_replace' ] = "Testing filter hook!";

        return $defaults;
      }


      public function setSections() {

        /**
         * Used within different fields. Simply examples. Search for ACTUAL DECLARATION for field examples
         **/

        // Background Patterns Reader
        $sample_patterns_path = ReduxFramework::$_dir . '../sample/patterns/';
        $sample_patterns_url  = ReduxFramework::$_url . '../sample/patterns/';
        $sample_patterns      = array();

        if ( is_dir( $sample_patterns_path ) ) :

          if ( $sample_patterns_dir = opendir( $sample_patterns_path ) ) :
            $sample_patterns = array();

            while ( ( $sample_patterns_file = readdir( $sample_patterns_dir ) ) !== false ) {

              if ( stristr( $sample_patterns_file, '.png' ) !== false || stristr( $sample_patterns_file, '.jpg' ) !== false ) {
                $name               = explode( ".", $sample_patterns_file );
                $name               = str_replace( '.' . end( $name ), '', $sample_patterns_file );
                $sample_patterns[ ] = array( 'alt' => $name, 'img' => $sample_patterns_url . $sample_patterns_file );
              }
            }
          endif;
        endif;

        ob_start();

        $ct          = wp_get_theme();
        $this->theme = $ct;
        $item_name   = $this->theme->get( 'Name' );
        $tags        = $this->theme->Tags;
        $screenshot  = $this->theme->get_screenshot();
        $class       = $screenshot ? 'has-screenshot' : '';

        $customize_title
          = sprintf( __( 'Customize &#8220;%s&#8221;', 'pzarchitect' ), $this->theme->display( 'Name' ) );

        ?>
        <div id="current-theme" class="<?php echo esc_attr( $class ); ?>">
          <?php if ( $screenshot ) : ?>
            <?php if ( current_user_can( 'edit_theme_options' ) ) : ?>
              <a href="<?php echo wp_customize_url(); ?>" class="load-customize hide-if-no-customize"
                 title="<?php echo esc_attr( $customize_title ); ?>">
                <img src="<?php echo esc_url( $screenshot ); ?>" alt="<?php esc_attr_e( 'Current theme preview' ); ?>"/>
              </a>
            <?php endif; ?>
            <img class="hide-if-customize" src="<?php echo esc_url( $screenshot ); ?>"
                 alt="<?php esc_attr_e( 'Current theme preview' ); ?>"/>
          <?php endif; ?>

          <h4>
            <?php echo $this->theme->display( 'Name' ); ?>
          </h4>

          <div>
            <ul class="theme-info">
              <li><?php printf( __( 'By %s', 'pzarchitect' ), $this->theme->display( 'Author' ) ); ?></li>
              <li><?php printf( __( 'Version %s', 'pzarchitect' ), $this->theme->display( 'Version' ) ); ?></li>
              <li><?php echo '<strong>' . __( 'Tags', 'pzarchitect' ) . ':</strong> '; ?><?php printf( $this->theme->display( 'Tags' ) ); ?></li>
            </ul>
            <p class="theme-description"><?php echo $this->theme->display( 'Description' ); ?></p>
            <?php if ( $this->theme->parent() ) {
              printf( ' <p class="howto">' . __( 'This <a href="%1$s">child theme</a> requires its parent theme, %2$s.' ) . '</p>',
                      __( 'http://codex.wordpress.org/Child_Themes', 'pzarchitect' ),
                      $this->theme->parent()->display( 'Name' ) );
            } ?>

          </div>

        </div>

        <?php
        $item_info = ob_get_contents();

        ob_end_clean();

        $sampleHTML = '';
        if ( file_exists( dirname( __FILE__ ) . '/info-html.html' ) ) {
          /** @global WP_Filesystem_Direct $wp_filesystem */
          global $wp_filesystem;
          if ( empty( $wp_filesystem ) ) {
            require_once( ABSPATH . '/wp-admin/includes/file.php' );
            WP_Filesystem();
          }
          $sampleHTML = $wp_filesystem->get_contents( dirname( __FILE__ ) . '/info-html.html' );
        }


        // ACTUAL DECLARATION OF SECTIONS
        $current_theme = wp_get_theme();
        global $pzarc_blueprints_list;
        if ( empty( $pzarc_blueprints_list ) ) {
          $pzarc_blueprints_list = pzarc_get_posts_in_post_type( 'arc-blueprints', true );

        }


        $is_hw = ( ($current_theme->get('Name') === 'Headway Base' || $current_theme->get('Template')=='headway') ) ;
        $this->sections[ '_general'] = array(
          'title'      => __( 'General ', 'pzarchitect' ),
          'show_title' => true,
          'icon'       => 'el-icon-wrench',
          'fields'     => array(
            array(
              'title'  => __( 'Styling', 'pzarchitect' ),
              'id'     => 'architect_stylings_section',
              'type'   => 'section',
              'indent' => true,
            ),
            array(
              'title'    => __( 'Use Architect styling', 'pzarchitect' ),
              'id'       => 'architect_enable_styling',
              'type'     => 'switch',
              'subtitle' => __( 'Turn this off if you want to manage styling from your own CSS stylesheets or only from the Headway Visual Editor Design Mode.', 'pzarchitect' ),
              'default'  => !$is_hw
            ),
            array(
              'title'   => __( 'Typography units', 'pzarchitect' ),
              'id'      => 'architect_typography_units',
              'type'    => 'button_set',
              'options' => array(
                'px'  => 'px',
                'em'  => 'em',
                'rem' => 'rem',
              ),
              'default' => 'px'
            ),
            ( $is_hw ? array(
              'title'    => __( 'Add Headway Content Block class', 'pzarchitect' ),
              'id'       => 'architect_hw-content-class',
              'type'     => 'switch',
              'on'       => __( 'Yes', 'pzarchitect' ),
              'off'      => __( 'No', 'pzarchitect' ),
              'default'  => false,
              'subtitle' => __( 'This will add the class <strong>block-type-content</strong> to the panels, which enables them to inherit the stylings for the Content block. However, this can make styling in the Visual Editor Design Mode a little confusing, as hovering over an element will show it as a Content Block element', 'pzarchitect' )

            ) : null ),
            ( $is_hw ? array(
              'title'    => __( 'Use Architect Headway CSS from Design Mode', 'pzarchitect' ),
              'id'       => 'architect_use-hw-css',
              'type'     => 'switch',
              'on'       => __( 'Yes', 'pzarchitect' ),
              'off'      => __( 'No', 'pzarchitect' ),
              'default'  => $is_hw,
              'subtitle' => __( 'Use the stylings you configure for Architect in the Headway Visual Editor Design Mode.', 'pzarchitect' )

            ) : null ),
            array(
              'id'     => 'architect_stylings_end-section',
              'type'   => 'section',
              'indent' => false,
            ),
            array(
              'title'  => __( 'Appearance', 'pzarchitect' ),
              'id'     => 'architect_appearance_section',
              'type'   => 'section',
              'indent' => true,
            ),
            array(
              'title'   => __( 'Hide page guides and tutorials', 'pzarchitect' ),
              'id'      => 'architect_hide_guides',
              'type'    => 'switch',
              //                    'subtitle' => __('Displays a background image on the Architect admin pages', 'pzarchitect'),
              'default' => false,
              'on'      => __( 'Yes', 'pzarchitect' ),
              'off'     => __( 'No', 'pzarchitect' )
            ),
            array(
              'title'    => __( 'Enable admin background image', 'pzarchitect' ),
              'id'       => 'architect_enable_bgimage',
              'type'     => 'switch',
              'subtitle' => __( 'Displays a background image on the Architect admin pages', 'pzarchitect' ),
              'default'  => false,
              'on'       => __( 'Yes', 'pzarchitect' ),
              'off'      => __( 'No', 'pzarchitect' )
            ),
            array(
              'title'    => __( 'Choose background image', 'pzarchitect' ),
              'id'       => 'architect_bgimage',
              'type'     => 'button_set',
              'required' => array( 'architect_enable_bgimage', 'equals', true ),
              'options'  => array(
                'green'      => __( 'Green', 'pzarchitect' ),
                'ocean-blue' => __( 'Ocean/Blue', 'pzarchitect' ),
                'pink'       => __( 'Pink', 'pzarchitect' ),
              ),
              'default'  => 'ocean-blue'
            ),
            array(
              'id'     => 'architect_appearance_end-section',
              'type'   => 'section',
              'indent' => false,
            ),
            array(
              'title'  => __( 'Shortcodes', 'pzarchitect' ),
              'id'     => 'architect_shortcodes_section',
              'type'   => 'section',
              'indent' => true,
            ),
            array(
              'title'    => __( 'Default shortcode blueprint', 'pzarchitect' ),
              'id'       => 'architect_default_shortcode_blueprint',
              'type'     => 'select',
              'options'  => $pzarc_blueprints_list,
              'subtitle' => __( 'If you omit the blueprint name from a shortcode, it will use the one selected here. Useful for quick conversion of WP galleries by simply renaming gallery to architect in the shortcode.', 'pzarchitect' ),
            ),
            array(
              'title'    => __( 'Replace WP Galleries with Blueprint', 'pzarchitect' ),
              'id'       => 'architect_replace_wpgalleries',
              'type'     => 'select',
              'options'  => $pzarc_blueprints_list,
              'subtitle' => __( 'Select a Blueprint to use for <strong>all</strong> WP gallery shortcodes.', 'pzarchitect' ),
              'desc'     => __( 'Make sure this Blueprint is using Galleries as its Content Source!', 'pzarchitect' )
            ),
            array(
              'id'     => 'architect_shortcodes_end-section',
              'type'   => 'section',
              'indent' => false,
            ),
            array(
              'title'  => __( 'Enhancements', 'pzarchitect' ),
              'id'     => 'architect_mods_section',
              'type'   => 'section',
              'indent' => true,
            ),
//            array(
//                'title'    => __( 'Use Focal Point', 'pzarchitect' ),
//                'id'       => 'architect_use-focal-point',
//                'type'     => 'switch',
//                'on'       => __( 'Yes', 'pzarchitect' ),
//                'off'      => __( 'No', 'pzarchitect' ),
//                'default'  => true,
//                'subtitle' => __( 'If you do not need the focal point feature, you can turn it off.', 'pzarchitect' )
//
//            ),
             array(
                'title'    => __( 'Activate Architect Builder on Pages editor', 'pzarchitect' ),
                'id'       => 'architect_use-builder',
                'type'     => 'switch',
                'on'       => __( 'Yes', 'pzarchitect' ),
                'off'      => __( 'No', 'pzarchitect' ),
                'default'  => !$is_hw,
                'subtitle' => __( '', 'pzarchitect' )

            ),
            array(
              'title'   => __( 'Additional content types', 'pzarchitect' ),
              'id'      => 'architect_add-content-types',
              'type'    => 'checkbox',
              //              'subtitle' => __( 'Add a video field to content types to optionally use as the Feature.', 'pzarchitect' ),
              'options' => array(
                'pz_snippets'     => __( 'Snippets', 'pzarchitect' ),
                'pz_testimonials' => __( 'Testimonials', 'pzarchitect' )
              ),
              'default' => array( 'pz_testimonials' => 1, 'pz_snippets' => 1 )
            ),
            array(
              'title'    => __( 'Feature Video field', 'pzarchitect' ),
              'id'       => 'architect_mod-video-fields',
              'type'     => 'checkbox',
              'subtitle' => __( 'Add a video field to content types to optionally use as the Feature.', 'pzarchitect' ),
              'options'  => array(
                'post'        => __( 'Posts', 'pzarchitect' ),
                'page'        => __( 'Pages', 'pzarchitect' ),
                'pz_snippets' => __( 'Snippets', 'pzarchitect' )
              ),
              'default'  => array( 'post' => 0, 'page' => 0, 'pz_snippets' => 1 )
            ),
            //            array(
            //              'title'    => __( 'Query caching', 'pzarchitect' ),
            //              'id'       => 'architect_enable_query_cache',
            //              'type'     => 'switch',
            //              'subtitle' => __( 'Turn this off if you find your Architect Blueprints don\'t show correct posts. This can be caused by other caching plugins or services.', 'pzarchitect' ),
            //              'default'  => true
            //            ),
            array(
              'id'     => 'architect_mods_end-section',
              'type'   => 'section',
              'indent' => false,
            ),
            array(
              'title'  => __( 'Other', 'pzarchitect' ),
              'id'     => 'architect_other_section',
              'type'   => 'section',
              'indent' => true
            ),
            array(
              'title'    => __( 'Exclude hidden custom fields', 'pzarchitect' ),
              'id'       => 'architect_exclude_hidden_custom',
              'type'     => 'switch',
              'desc' => __( 'Many plugins have custom fields that are hidden because you don\'t need to access them. Some however, like WooCommerce, make their fields hidden anyway. If you want to shorten the custom field drop downs and are not using a plugin that you need hidden fields from, then enable this.', 'pzarchitect' ),
              'default'  => false,
              'on'       => __( 'Yes', 'pzarchitect' ),
              'off'      => __( 'No', 'pzarchitect' )
            ),
//            array(
//              'title'    => __( 'Remove Architect Support button', 'pzarchitect' ),
//              'id'       => 'architect_remove_support_button',
//              'type'     => 'switch',
//              'desc' => __( 'If you don\'t want the Architect Support button appearing on every screen (it can slow down loading), then enable this. You can still access the support form in Architect> Help & Support > Support.', 'pzarchitect' ),
//              'default'  => false,
//              'on'       => __( 'Yes', 'pzarchitect' ),
//              'off'      => __( 'No', 'pzarchitect' )
//            ),
            array(
              'title'    => __( 'Enable beta features', 'pzarchitect' ),
              'id'       => 'architect_enable_beta',
              'type'     => 'switch',
              'desc' => __( 'This will enable features that are working but not fully complete. Use at your own risk!', 'pzarchitect' ),
              'default'  => false,
              'on'       => __( 'Yes', 'pzarchitect' ),
              'off'      => __( 'No', 'pzarchitect' )
            ),
            array(
              'title'    => __( 'Beta features', 'pzarchitect' ),
              'id'       => 'architect_beta_features',
              'type'     => 'info',
              'required' => array( 'architect_enable_beta', 'equals', true ),
              'icon'  => 'el-icon-info-sign',
              'style'    => 'critical',
              'subtitle' => 'No beta features currently available.'
            ),
            //                array(
            //                    'title'    => __('Custom post def path', 'pzarchitect'),
            //                    'id'       => 'architect_custom_post_def_path',
            //                    'type'     => 'text',
            //                    'validate' => 'url',
            //                    'default'  => '',
            //                ),
            array(
              'id'     => 'architect_other_section_end',
              'type'   => 'section',
              'indent' => false
            ),
          )
        );
        $this->sections[ '_responsive'] = array(
          'title'      => 'Responsive ',
          'show_title' => true,
          'icon'       => 'el-icon-laptop',
          'desc'       => __( 'Architect lets you set some arbitrary breakpoints for responsive design. Responsive design, however, is a lot more complicated than a handful of breakpoints! It is affected by devices, content, containers and so on. To provide support for all of that would severely overwhelm Architect\'s settings. For example, for every font styling, it would need to be set for every scenario. The breakpoints are therefore used on a limited range of options. If you want to get serious with responsive design, you will have to write a lot of custom css', 'pzarchitect' ),
          'fields'     => array(
            array(
              'title'  => __( 'Breakpoints', 'pzarchitect' ),
              'id'     => 'architect_breakpoint_section',
              'type'   => 'section',
              'indent' => true,
            ),
            array(
              'title'   => __( 'Wide screen breakpoint', 'pzarchitect' ),
              'id'      => 'architect_breakpoint_1',
              'type'    => 'dimensions',
              'height'  => false,
              'units'   => 'px',
              'default' => array( 'width' => '960' ),
            ),
            array(
              'title'   => __( 'Medium screen breakpoint', 'pzarchitect' ),
              'id'      => 'architect_breakpoint_2',
              'type'    => 'dimensions',
              'height'  => false,
              'units'   => 'px',
              'default' => array( 'width' => '640' ),
            ),
            array(
              'id'     => 'architect_responsive-end-section',
              'type'   => 'section',
              'indent' => false,
            ),
            //            array(
            //              'title'    => __( 'Phone Breakpoints', 'pzarchitect' ),
            //              'id'       => 'architect_breakpoint_section_phone',
            //              'type'     => 'section',
            //              'indent'   => true,
            //            ),
            //            array(
            //              'title'   => __( 'Wide screen breakpoint', 'pzarchitect' ),
            //              'id'      => 'architect_breakpoint_1_phone',
            //              'type'    => 'dimensions',
            //              'height'  => false,
            //              'units'   => 'px',
            //              'default' => array( 'width' => '480' ),
            //            ),
            //            array(
            //              'title'   => __( 'Medium screen breakpoint', 'pzarchitect' ),
            //              'id'      => 'architect_breakpoint_2_phone',
            //              'type'    => 'dimensions',
            //              'height'  => false,
            //              'units'   => 'px',
            //              'default' => array( 'width' => '320' ),
            //            ),
            //            array(
            //              'id'     => 'architect_responsive-end-section_phone',
            //              'type'   => 'section',
            //              'indent' => false,
            //            ),
            //
            //            array(
            //              'title'    => __( 'Tablet Breakpoints', 'pzarchitect' ),
            //              'id'       => 'architect_breakpoint_section_tablet',
            //              'type'     => 'section',
            //              'indent'   => true,
            //            ),
            //            array(
            //              'title'   => __( 'Wide screen breakpoint', 'pzarchitect' ),
            //              'id'      => 'architect_breakpoint_1_tablet',
            //              'type'    => 'dimensions',
            //              'height'  => false,
            //              'units'   => 'px',
            //              'default' => array( 'width' => '1024' ),
            //            ),
            //            array(
            //              'title'   => __( 'Medium screen breakpoint', 'pzarchitect' ),
            //              'id'      => 'architect_breakpoint_2_tablet',
            //              'type'    => 'dimensions',
            //              'height'  => false,
            //              'units'   => 'px',
            //              'default' => array( 'width' => '768' ),
            //            ),
            //            array(
            //              'id'     => 'architect_responsive-end-section_tablet',
            //              'type'   => 'section',
            //              'indent' => false,
            //            ),


            array(
              'title'  => __( 'Images', 'pzarchitect' ),
              'id'     => 'architect_responsive-images_section',
              'type'   => 'section',
              'indent' => true,
            ),
            array(
              'title'    => __( 'Create and use retina images', 'pzarchitect' ),
              'id'       => 'architect_enable-retina-images',
              'type'     => 'switch',
              'subtitle' => __( 'If enabled, when images are created, a second high version to display on retina screens will also be created and then displayed as required. This can be turned off for specific Panels. NOTE: This will make your site load slower on retina devices.', 'pzarchitect' ),
              'default'  => false,
              'on'       => __( 'Yes', 'pzarchitect' ),
              'off'      => __( 'No', 'pzarchitect' )
            ),
            array(
              'id'     => 'architect_responsive-images_end-section',
              'type'   => 'section',
              'indent' => false,
            ),
          )
        );
        $this->sections[ '_language'] = array(
          'title'      => 'Language ',
          'show_title' => true,
          'icon'       => 'el-icon-globe',
          'fields'     => array(
            array(
              'title'    => __( 'Categories archive pages title', 'pzarchitect' ),
              'id'       => 'architect_language-categories-archive-pages-title',
              'type'     => 'text',
              'subtitle' => __( 'Enter a title to appear at the top of Categories archives pages', 'pzarchitect' ),
              'default'  => 'Posts in Category: '
            ),
            array(
              'title'    => __( 'Tags archive pages title', 'pzarchitect' ),
              'id'       => 'architect_language-tags-archive-pages-title',
              'type'     => 'text',
              'subtitle' => __( 'Enter a title to appear at the top of Tags archives pages', 'pzarchitect' ),
              'default'  => 'Posts in Tag: '
            ),
            array(
              'title'    => __( 'Months archive pages title', 'pzarchitect' ),
              'id'       => 'architect_language-months-archive-pages-title',
              'type'     => 'text',
              'subtitle' => __( 'Enter a title to appear at the top of Months archives pages', 'pzarchitect' ),
              'default'  => 'Posts in Month: '
            ),
            array(
              'title'    => __( 'Custom taxonomies archive pages title', 'pzarchitect' ),
              'id'       => 'architect_language-custom-archive-pages-title',
              'type'     => 'text',
              'subtitle' => __( 'Enter a title to appear at the top of Custom taxonomies archives pages', 'pzarchitect' ),
              'default'  => 'Posts in: '
            ),
          )
        );
//        $this->sections[ ] = array(
//            'title'      => 'Custom CSS',
//            'show_title' => true,
//            'icon'       => 'el-icon-globe',
//            'fields'     => array(
//                array(
//                    'title'    => __('Custom CSS', 'pzarchitect'),
//                    'id'       => 'architect_custom-css',
//                    'type'     => 'ace_editor',
//                    'mode'=>'css',
//                    'subtitle' => 'This can be any CSS',
//                    'default'  => ''
//                )
//            )
//        );
        $this->sections = apply_filters('pzarc-extend-options',$this->sections);

      }

      public function setHelpTabs() {

        // Custom page help tabs, displayed using the help API. Tabs are shown in order of definition.
        $this->args[ 'help_tabs' ][ ] = array(
          'id'      => 'redux-opts-1',
          'title'   => __( 'Theme Information 1', 'pzarchitect' ),
          'content' => __( '<p>This is the tab content, HTML is allowed.</p>', 'pzarchitect' )
        );

        $this->args[ 'help_tabs' ][ ] = array(
          'id'      => 'redux-opts-2',
          'title'   => __( 'Theme Information 2', 'pzarchitect' ),
          'content' => __( '<p>This is the tab content, HTML is allowed.</p>', 'pzarchitect' )
        );

        // Set the help sidebar
        $this->args[ 'help_sidebar' ]
          = __( '<p>This is the sidebar content, HTML is allowed.</p>', 'pzarchitect' );

      }


      /**
       *
       * All the possible arguments for Redux.
       * For full documentation on arguments, please refer to: https://github.com/ReduxFramework/ReduxFramework/wiki/Arguments
       **/
      public function setArguments() {

        $theme = wp_get_theme(); // For use with some settings. Not necessary.

        $this->args = array(

          // TYPICAL -> Change these values as you need/desire
          'opt_name'           => '_architect_options',
          // This is where your data is stored in the database and also becomes your global variable name.
          'display_name'       => __( 'Architect Options', 'pzarchitect' ),
          // Name that appears at the top of your panel
          'display_version'    => 'Architect v' . PZARC_VERSION,
          // Version that appears at the top of your panel
          'menu_type'          => 'submenu',
          //Specify if the admin menu should appear or not. Options: menu or submenu (Under appearance only)
          'allow_sub_menu'     => false,
          // Show the sections below the admin menu item or not
          'menu_title'         => __( '<span class="dashicons dashicons-admin-settings"></span>Options', 'pzarchitect' ),
          'page'               => __( 'Architect Options', 'pzarchitect' ),
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
          'page_slug'          => '_architect_options',
          // Page slug used to denote the panel
          'save_defaults'      => true,
          // On load save the defaults to DB before user clicks save or not
          'default_show'       => false,
          // If true, shows the default value next to each field that is not the default value.
          'default_mark'       => '',
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
            'icon'          => 'el el-question-sign',
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
          'icon'  => 'el el-twitter'
        );


        // Panel Intro text -> before the form
        if ( ! isset( $this->args[ 'global_variable' ] ) || $this->args[ 'global_variable' ] !== false ) {
          if ( ! empty( $this->args[ 'global_variable' ] ) ) {
            $v = $this->args[ 'global_variable' ];
          } else {
            $v = str_replace( "-", "_", $this->args[ 'opt_name' ] );
          }
//          $this->args[ 'intro_text' ]
//              = sprintf(__('<p>On this page you can setup specific blueprints to display at specific points in your page\'s display. This is done using WordPress action hooks. Although WordPress provides many, the ones that work best for content display will be those included in the theme you are using. Review your theme and/or its documentation.</p>', 'pzarchitect'), $v);
        } else {
//          $this->args[ 'intro_text' ]
//              = __('<p>This text is displayed above the options panel. It isn\'t required, but more info is always better! The intro_text field accepts all HTML.</p>', 'pzarchitect');
        }

        // Add content after the form.
//        $this->args['footer_text'] = sprintf( __('<p><strong>$%1$s</strong></p>', 'pzarc' ), $v );
      }
    }

    new Redux_Framework_Architect_Options();


    /**
     *
     * Custom function for the callback validation referenced above
     **/
    if ( ! function_exists( 'redux_validate_callback_function' ) ):
      function redux_validate_callback_function( $field, $value, $existing_value ) {
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
        if ( $error == true ) {
          $return[ 'error' ] = $field;
        }

        return $return;
      }
    endif;

    // Redux tracking
    if ( ! function_exists( 'pzarc_redux_tracking' ) ) {
      function pzarc_redux_tracking( $options ) {
        $opt                                                       = array();
        $options[ 'DqDE7uzWFMdHsJsRIjveviQBVuE3Q75C03YLUt7rhVw=' ] = true;

        // var_dump($options);
        return $options;
      }

      add_filter( 'redux/tracking/developer', 'pzarc_redux_tracking' );

    }
  }