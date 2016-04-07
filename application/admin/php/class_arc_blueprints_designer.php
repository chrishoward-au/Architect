<?php

  class arc_Blueprints_Designer {

    public $content_types;
    public $redux_opt_name = '_architect';
    public $defaults = false;
    public $custom_fields = array();
    public $postmeta = null;
    private $post_info = array();
    private $component_types;
    private $component_defaults = array( 'title', 'excerpt', 'meta1', 'image' );
    private $preview_default = array(
      'title'   => array( 'width' => 100, 'show' => true ),
      'meta1'   => array( 'width' => 100, 'show' => true ),
      'image'   => array( 'width' => 25, 'show' => true ),
      'excerpt' => array( 'width' => 75, 'show' => true ),
      'content' => array( 'width' => 100, 'show' => false ),
      'meta2'   => array( 'width' => 100, 'show' => false ),
      'meta3'   => array( 'width' => 100, 'show' => false ),
      'custom1' => array( 'width' => 100, 'show' => false ),
      'custom2' => array( 'width' => 100, 'show' => false ),
      'custom3' => array( 'width' => 100, 'show' => false )
    );
    private $layout_editor_fields = '{"title","meta1","meta2","meta3","body","excerpt","feature","custom1","custom2","custom3"}';


    /**
     * [__construct description]
     */
    function __construct( $defaults = false ) {


      $this->defaults = $defaults;

      // load extra stuffs
      if ( is_admin() ) {

        add_action( 'admin_head', array( $this, 'content_blueprints_admin_head' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'content_blueprints_admin_enqueue' ) );
        add_filter( 'manage_arc-blueprints_posts_columns', array( $this, 'add_blueprint_columns' ) );
        add_action( 'manage_arc-blueprints_posts_custom_column', array(
          $this,
          'add_blueprint_column_content'
        ), 10, 2 );

        add_action( "redux/metaboxes/$this->redux_opt_name/boxes", array( $this, 'pzarc_mb_blueprint_tabs' ), 10, 1 );
//      add_action("redux/metaboxes/$this->redux_opt_name/boxes", array($this,
//                                                                      'pzarc_mb_blueprint_presets'), 10, 1);
        add_action( "redux/metaboxes/$this->redux_opt_name/boxes", array(
          $this,
          'pzarc_mb_blueprint_general_settings'
        ), 10, 1 );
        add_action( "redux/metaboxes/$this->redux_opt_name/boxes", array( $this, 'pzarc_mb_blueprint_design' ), 10, 1 );
        add_action( "redux/metaboxes/$this->redux_opt_name/boxes", array( $this, 'pzarc_mb_blueprint_types' ), 10, 1 );
        add_action( "redux/metaboxes/$this->redux_opt_name/boxes", array( $this, 'pzarc_mb_panels_layout' ), 10, 1 );
        add_action( "redux/metaboxes/$this->redux_opt_name/boxes", array(
          $this,
          'pzarc_mb_blueprint_content_selection'
        ), 10, 1 );

        add_filter( 'views_edit-arc-blueprints', array( $this, 'blueprints_description' ) );

        //       add_action('admin_init',array($this,'admin_init'));
        // Grab the extra slider types from the registry

        $registry     = arc_Registry::getInstance();
        $slider_types = (array) $registry->get( 'slider_types' );
        foreach ( $slider_types as $st ) {

          require_once( $st[ 'admin' ] );

        }

        // 1.8.1 Attempting to reduce calls to get custom fields. Generally this should work, but may occasionally miss some fields.
        $this->custom_fields = get_option( 'architect_custom_fields' );
        if ( empty( $this->custom_fields ) || ( ( isset( $_GET[ 'post_type' ] ) && $_GET[ 'post_type' ] === 'arc-blueprints' ) ) ) {
          $this->custom_fields = pzarc_get_custom_fields();
          update_option( 'architect_custom_fields', $this->custom_fields );
//          var_dump('Custom fields updated');
        }

        if ( array_key_exists( 'post', $_GET ) ) {
          $this->post_info[ 'id' ]     = $_GET[ 'post' ];
          $this->postmeta              = get_post_meta( $_GET[ 'post' ] );
          $this->post_info[ 'source' ] = isset($this->postmeta[ '_blueprints_content-source' ][ 0 ])?$this->postmeta[ '_blueprints_content-source' ][ 0 ]:'defaults';
          $this->post_info[ 'source' ] .= in_array($this->post_info[ 'source' ],array('post','page'))?'s':''; // A bit of hacking.
        } else {
          $this->post_info[ 'id' ]     = - 1;
          $this->post_info[ 'source' ] = 'defaults';
        }
        // These can be overridden by plugins
        // This is here coz it has functions in it
        $this->component_types    = array(
          'title'   => __( 'Title', 'pzarchitect' ),
          'excerpt' => __( 'Excerpt', 'pzarchitect' ),
          'content' => __( 'Body', 'pzarchitect' ),
          'image'   => __( 'Feature', 'pzarchitect' ),
          'meta1'   => __( 'Meta1', 'pzarchitect' ),
          'meta2'   => __( 'Meta2', 'pzarchitect' ),
          'meta3'   => __( 'Meta3', 'pzarchitect' ),
          'custom1' => __( 'Custom 1', 'pzarchitect' ),
          'custom2' => __( 'Custom 2', 'pzarchitect' ),
          'custom3' => __( 'Custom 3', 'pzarchitect' ),
        );
      }
    }

    public function admin_init() {
//      add_filter('_arc_add_tax_titles','pzarc_get_tax_titles',10,1);
    }

    /**
     * [content_blueprints_admin_enqueue description]
     *
     * @param  [type] $hook [description]
     */
    public function content_blueprints_admin_enqueue( $hook ) {
      $screen = get_current_screen();
      if ( 'arc-blueprints' == $screen->id ) {
        //TODO: Update this for 1.8!
        //     require_once( PZARC_DOCUMENTATION_PATH . PZARC_LANGUAGE . '/blueprints-pageguide.php' );

        wp_enqueue_script( 'jquery-ui-draggable' );
        wp_enqueue_script( 'jquery-ui-droppable' );
        wp_enqueue_script( 'jquery-ui-sortable' );
        wp_enqueue_script( 'jquery-ui-resizable' );

        wp_enqueue_style( 'pzarc-admin-panels-css', PZARC_PLUGIN_APP_URL . '/admin/css/arc-admin-panels.css' );

        wp_enqueue_script( 'jquery-pzarc-metaboxes-panels', PZARC_PLUGIN_APP_URL . '/admin/js/arc-metaboxes-panels.js', array( 'jquery' ), null, true );

        wp_enqueue_style( 'pzarc-admin-blueprints-css', PZARC_PLUGIN_APP_URL . '/admin/css/arc-admin-blueprints.css' );

        wp_enqueue_script( 'jquery-pzarc-metaboxes-blueprints', PZARC_PLUGIN_APP_URL . '/admin/js/arc-metaboxes-blueprints.js', array( 'jquery' ), true );


      } elseif ( 'edit-arc-blueprints' === $screen->id ) {
//        require_once( PZARC_DOCUMENTATION_PATH.PZARC_LANGUAGE . '/blueprints-listing-pageguide.php');
        require_once PZARC_PLUGIN_PATH . 'presets/presets.php';
        wp_enqueue_script( 'jquery-pzarc-blueprints-presets', PZARC_PLUGIN_APP_URL . '/admin/js/arc-blueprints-presets.js', array(
          'jquery',
          'jquery-ui-draggable',
        ), true );
//        wp_enqueue_script('jquery-echo-js', PZARC_PLUGIN_APP_URL . '/admin/js/echo.js', array('jquery'), true);
        wp_enqueue_script( 'jquery-lazy', PZARC_PLUGIN_APP_URL . '/admin/js/jquery.lazy.min.js', array( 'jquery' ), true );

      }
    }

    /**
     * [content_blueprints_admin_head description]
     */
    public function content_blueprints_admin_head() {

    }

    function blueprints_description( $content ) {
      // todo: MAKE SURE ALL PRESETS USE DUMMY CONTENT AND NO FILTERS

      // TODO: How can we make this not load until we want it too?

      global $_architect_options;
      $arc_styling              = ! empty( $_architect_options[ 'architect_enable_styling' ] ) ? 'arc-styling-on' : 'arc-styling-off';
      $presets                  = new arcPresetsLoader();
      $presets_array            = $presets->render();
      $presets_html             = $presets_array[ 'html' ];
      $content[ 'arc-message' ] = '
      <div class="after-title-help postbox">

        <div class="inside">
<div class="pzarc-help-section">
                        <a class="pzarc-button-help" href="http://architect4wp.com/codex-listings/" target="_blank">
                        <span class="dashicons dashicons-book"></span>
                        Documentation</a>&nbsp;
                        <a class="pzarc-button-help" href="https://pizazzwp.freshdesk.com/support/tickets/new" target="_blank">
                        <span class="dashicons dashicons-admin-tools"></span>
                        Tech support</a>
                        <a class="pzarc-button-help" href="https://shop.pizazzwp.com/checkout/customer-dashboard/" target="_blank">
                        <span class="dashicons dashicons-admin-users"></span>
                        Customer dashboard</a>
                        <a class="pzarc-button-help" href="https://shop.pizazzwp.com/affiliate-area/" target="_blank">
                        <span class="dashicons" style="font-size:1.3em">$</span>
                        Affiliates</a>
                        </div>
          ' .

                                  self::arc_has_export_data()

                                  .
                                  '
          <!-- Presets selector -->
          <!-- Display none to be sure -->
          <div class="arc-presets-selector closed">
           <h2 class="heading closed">Architect Blueprints: Preset selector</h2>
           <div class="tabby tabs">
                <button class="tabby-sliders first active" data-tab="#sliders">' . __( 'Sliders', 'pzarchitect' ) . '</button>
                <button class="tabby-grids" data-tab="#grids" >' . __( 'Grids', 'pzarchitect' ) . '</button>
                <button class="tabby-tabbed" data-tab="#tabbed">' . __( 'Tabbed', 'pzarchitect' ) . '</button>
                <button class="tabby-masonry" data-tab="#masonry">' . __( 'Masonry', 'pzarchitect' ) . '</button>
                <button class="tabby-accordion" data-tab="#accordion">' . __( 'Accordion', 'pzarchitect' ) . '</button>
                <button class="tabby-tabular" data-tab="#tabular">' . __( 'Tabular', 'pzarchitect' ) . '</button>
                <button class="tabby-custom" data-tab="#import">' . __( 'Import', 'pzarchitect' ) . '</button>
            </div>

           <div class="tabby tabs-content container">
           <div class="tabs-pane active" id="sliders">' . $presets_html[ 'slider' ] . '</div>
           <div class="tabs-pane" id="grids">' . $presets_html[ 'basic' ] . '</div>
           <div class="tabs-pane" id="tabbed">' . $presets_html[ 'tabbed' ] . '</div>
           <div class="tabs-pane" id="masonry">' . $presets_html[ 'masonry' ] . '</div>
           <div class="tabs-pane" id="accordion">' . $presets_html[ 'accordion' ] . '</div>
           <div class="tabs-pane" id="tabular">' . $presets_html[ 'table' ] . '</div>
           <div class="tabs-pane" id="import">
                If you have Presets to import, do so in the Architect > Tools page
           </div>
           </div>
           <div class="footer">
           <p>• All Presets use Dummy Content by default. Please change to the content of your choice after loading the chosen Preset.</p>
           <p class="' . $arc_styling . '"><em>Use Architect styling</em> is turned off in <em>Architect</em> > <em>Options</em>, therefore styling will not render.</p>
           </div>
           <div class="buttons">
            <a class="arc-button-presets styled disabled" href="javascript:void(0);">Use styled</a>
            <a class="arc-button-presets unstyled disabled" href="javascript:void(0);">Use unstyled</a>
          </div>
          </div>
        </div>
        <!-- .inside -->
      </div><!-- .postbox -->';

      return $content;

    }


    /**
     * [add_blueprint_columns description]
     *
     * @param [type] $columns [description]
     */
    public function add_blueprint_columns( $columns ) {
      unset( $columns[ 'thumbnail' ] );
      $pzarc_front  = array_slice( $columns, 0, 2 );
      $pzarc_back   = array_slice( $columns, 2 );
      $pzarc_insert = array(
        '_blueprints_short-name'     => __( 'Shortname', 'pzarchitect' ),
        'layout-type'                => __( 'Type', 'pzarchitect' ),
        '_blueprints_content-source' => __( 'Content source', 'pzarchitect' ),
        '_blueprints_description'    => __( 'Description', 'pzarchitect' ),
        //          'id'                         => __('ID', 'pzarchitect'),
      );

      return array_merge( $pzarc_front, $pzarc_insert, $pzarc_back );
    }

    /**
     * [add_blueprint_column_content description]
     *
     * @param [type] $column  [description]
     * @param [type] $post_id [description]
     */
    public function add_blueprint_column_content( $column, $post_id ) {

      $post_meta = get_post_meta( $post_id );


      switch ( $column ) {
        case 'id':
          if ( isset( $post_meta[ $column ] ) ) {
            echo $post_meta[ $column ][ 0 ];
          }
          break;

        case '_blueprints_short-name':
          if ( isset( $post_meta[ $column ] ) ) {
            echo $post_meta[ $column ][ 0 ];
          }
          break;

        case '_blueprints_description':
          if ( isset( $post_meta[ $column ] ) ) {
            echo $post_meta[ $column ][ 0 ];
          }
          break;

        case '_blueprints_content-source':

          $content_source = ucwords( empty( $post_meta[ $column ][ 0 ] ) ? 'default' : $post_meta[ $column ][ 0 ] );
          $content_source = ( $content_source === 'Cpt' ? 'Custom Post Type' : $content_source );
          echo $content_source;
          break;

        case 'layout-type':
          if ( ! empty( $post_meta[ '_blueprints_section-0-layout-mode' ][ 0 ] ) ) {
            echo ucfirst( $post_meta[ '_blueprints_section-0-layout-mode' ][ 0 ] );
          } else {
            echo 'Grid';
          }
//          if (!empty($post_meta[ '_blueprints_section-0-layout-mode' ][ 0 ]) && !empty($post_meta[ '_blueprints_section-1-layout-mode' ][ 0 ])) {
//            echo '<br>' . '2: ' . ucfirst($post_meta[ '_blueprints_section-1-layout-mode' ][ 0 ]);
//          }
//          if ((!empty($post_meta[ '_blueprints_section-0-layout-mode' ][ 0 ]) || !empty($post_meta[ '_blueprints_section-1-layout-mode' ][ 0 ])) && !empty($post_meta[ '_blueprints_section-2-layout-mode' ])) {
//            echo '<br>' . '3: ' . ucfirst($post_meta[ '_blueprints_section-2-layout-mode' ][ 0 ]);
//          }
          break;
      }
    }


    /**
     * [create_blueprints_post_type description]
     * @return [type] [description]
     */


    function pzarc_mb_blueprint_tabs( $metaboxes, $defaults_only = false ) {
      $prefix   = '_blueprint_tabs_'; // declare prefix
      $sections = array();
      global $_architect_options;
      if ( empty( $_architect_options ) ) {
        $_architect_options = get_option( '_architect_options' );
      }
      $fields = array();
//      if (!empty($_architect_options[ 'architect_enable_styling' ])) {

      $registry     = arc_Registry::getInstance();
      $content_info = $registry->get( 'content_info' );
      $fields       = array(
        array(
          'id'      => $prefix . 'tabs',
          'type'    => 'tabbed',
          'default' => 'layout',
          'options' => array_merge( array(
                                      'layout'  => '<span class="pzarc-tab-title">' . __( 'Blueprint', 'pzarchitect' ) . '</span>',
                                      'type'    => '<span class="pzarc-tab-title pzarc-blueprint-type">' . __( '', 'pzarchitect' ) . '</span>',
                                      'content' => '<span class="pzarc-tab-title">' . __( 'Source', 'pzarchitect' ) . '</span>',
                                      'panels'  => '<span class="pzarc-tab-title">' . __( 'Panels', 'pzarchitect' ) . '</span>',
                                    ), $content_info[ $this->post_info[ 'source' ] ][ 'options' ] ),
          'targets' => array_merge( array(
                                      'layout'  => array( 'layout-settings' ),
                                      'type'    => array( 'type-settings' ),
                                      'content' => array( 'content-selections' ),
                                      'panels'  => array( 'panels-design' ),
                                    ), $content_info[ $this->post_info[ 'source' ] ][ 'targets' ] )
        ),
      );

//      } else {
//        $fields = array(
//            array(
//                'id'      => $prefix . 'tabs',
//                'type'    => 'tabbed',
//                'options' => array(
//                    'layout'  => '<span><span class="stepno">1</span> <span class="pzarc-tab-title">Blueprint Design</span></span>',
//                    'content' => '<span><span class="stepno">2</span> <span class="pzarc-tab-title">Content Selection</span></span>',
//                    'panels'  => '<span><span class="stepno">3</span> <span class="pzarc-tab-title">Content Layout</span></span>',
//                ),
//                'targets' => array(
//                    'layout'  => array('layout-settings'),
//                    'content' => array('content-selections'),
//                    'panels'  => array('panels-design'),
//                )
//            ),
//        );
//      }

      $fields = apply_filters( 'arc_editor_tabs', $fields );

      $sections[ 'settings-chooser' ] = array(
        //          'title'      => __('General Settings', 'pzarchitect'),
        'show_title' => true,
        'icon_class' => 'icon-large',
        'icon'       => 'el-icon-home',
        'fields'     => $fields
      );
      $metaboxes[]                    = array(
        'id'         => $prefix . 'blueprints',
        //'title'      => __('Sections:', 'pzarchitect'),
        'post_types' => array( 'arc-blueprints' ),
        'sections'   => $sections,
        'position'   => 'normal',
        'priority'   => 'high',
        'sidebar'    => false

      );

      return $metaboxes;
    }

//    function pzarc_mb_blueprint_presets($metaboxes, $defaults_only = false)
//    {
//      $fields = array(
//          array(
//              'id'      => '_presets_choose',
//              'title'   => 'Choose a preset (optional)',
//              'type'    => 'image_select',
//              'default' => 'none',
//              'height'  => '200px',
//              'options' => array(
//                  'none'                    => array(
//                      'alt' => 'Build your own',
//                      'img' => PZARC_PLUGIN_APP_URL . 'shared/assets/images/metaboxes/presets-none.png'
//                  ),
//                  'featured-posts-slider'   => array(
//                      'alt' => 'Featured Posts Slider',
//                      'img' => PZARC_PLUGIN_APP_URL . 'shared/assets/images/metaboxes/preset-featured-posts-slider.jpg'
//                  ),
//                  'gallery-carousel'        => array(
//                      'alt' => 'Gallery carousel',
//                      'img' => PZARC_PLUGIN_APP_URL . 'shared/assets/images/metaboxes/preset-gallery-carousel.jpg'
//                  ),
//                  'full-width-image-slider' => array(
//                      'alt' => 'Full width image slider',
//                      'img' => PZARC_PLUGIN_APP_URL . 'shared/assets/images/metaboxes/presets-full-width-image-slider.png'
//                  ),
//                  'featured-video'          => array(
//                      'alt' => 'Featured videos',
//                      'img' => PZARC_PLUGIN_APP_URL . 'shared/assets/images/metaboxes/presets-featured-video.png'
//                  ),
//              ),
//          ),
//      );
//
//      $sections[]  = array(
//        //          'title'      => __('General Settings', 'pzarchitect'),
//        'show_title' => true,
//        'icon_class' => 'icon-large',
//        'icon'       => 'el-icon-home',
//        'fields'     => $fields
//      );
//      $metaboxes[] = array(
//          'id'         => 'presets',
//          'title'      => __('Presets', 'pzarchitect'),
//          'post_types' => array('arc-blueprints'),
//          'sections'   => $sections,
//          'position'   => 'normal',
//          'priority'   => 'low',
//          'sidebar'    => false
//
//      );
//
//      return $metaboxes;
//    }


    // TODO: ADD FILTER OPTION FOR RELATED POSTS

    /**
     * pzarc_blueprint_layout_general
     *
     * @param array $metaboxes
     *
     * @return array
     */
    function pzarc_mb_blueprint_general_settings( $metaboxes, $defaults_only = false ) {
      $prefix = '_blueprints_'; // declare prefix
      global $_architect_options;
      $cfwarn          = false;
      $animation_state = ! empty( $_architect_options[ 'architect_animation-enable' ] ) ? $_architect_options[ 'architect_animation-enable' ] : false;
      if ( is_admin() && ! empty( $_GET[ 'post' ] ) ) {
        $cfcount        = ( ! empty( $this->postmeta[ '_panels_design_custom-fields-count' ][ 0 ] ) ? $this->postmeta[ '_panels_design_custom-fields-count' ][ 0 ] : 0 );
        $max_input_vars = ini_get( 'max_input_vars' );
        $cfwarn         = ( ! empty( $max_input_vars ) && $max_input_vars <= 1000 && ( $cfcount > 0 || $animation_state ) );

      }
//      $registry = arc_Registry::getInstance();
//
//      $content_types      = array();
//      $content_post_types = (array) $registry->get( 'post_types' );
//      foreach ( $content_post_types as $key => $value ) {
//        if ( isset( $value[ 'blueprint-content' ] ) ) {
//          $content_types[ $value[ 'blueprint-content' ][ 'type' ] ] = $value[ 'blueprint-content' ][ 'name' ];
//        }
//      }

//      $blueprint_content_common = $registry->get( 'blueprint-content-common' );
      $sections[ '_general_bp' ] = array(
        'fields' => array(
          //          array(
          //            'title'    => __( 'Content source', 'pzarchitect' ),
          //            'id'       => '_blueprints_content-source',
          //            'type'     => 'select',
          ////            'select2'  => array( 'allowClear' => false ),
          //            'default'  => 'defaults',
          //            'options'  => $content_types,
          //            'desc'     => __( 'If you want a Blueprint to display the content of the specific post/page/cpt it is to be displayed on, use the Default content source.<br><strong>Select <em>Defaults</em> if this Blueprint will display blog posts on the blog page</strong>', 'pzarchitect' ),
          //            'subtitle' => ( array_key_exists( 'snippets', $content_types ) ? '' : 'Several more content sources supported in Architect Pro version, including Pages, Snippets, Galleries, Custom Post Types and a special Dummy option to display dummy content' )
          //          ),

          array(
            'id'       => $prefix . 'short-name',
            'title'    => __( 'Blueprint Short Name', 'pzarchitect' ) . '<span class="pzarc-required el-icon-star" title="Required"></span>',
            'type'     => 'text',
            'subtitle' => __( 'Letters, numbers, dashes only. ', 'pzarchitect' ) . '</strong>' . __( 'Use this in shortcodes, template tags, and CSS classes', 'pzarchitect' ),
            'hint'     => array(
              'title'   => 'Blueprint Short Name',
              'content' => '<strong>' . __( 'Letters, numbers, dashes only. ', 'pzarchitect' ) . '</strong>' . __( 'Use this in shortcodes, template tags, and CSS classes', 'pzarchitect' )
            ),
            //TODO: Write  acomprehensive little help dialog here
            'validate' => 'not_empty',
            'default'  => '',
          ),
          array(
            'id'    => $prefix . 'description',
            'title' => __( 'Description', 'pzarchitect' ),
            'type'  => 'textarea',
            'rows'  => 2,
            'hint'  => array( 'content' => __( 'A short description to help you or others know what this Blueprint is for', 'pzarchitect' ) ),
          ),
        )
      );

      if ( $cfwarn ) {
        $sections[ '_general_bp' ][ 'fields' ][] = array(
          'id'       => $prefix . 'input-vars-message',
          'title'    => __( 'Custom fields', 'pzarchitect' ),
          'type'     => 'info',
          'style'    => ( $cfwarn ? 'critical' : 'normal' ),
          'required' => array( '_panels_design_components-to-show', 'contains', 'custom' ),
          'desc'     => __( 'If you add custom fields to a Blueprint it adds many more fields to the form. <strong>This can cause some fields not to save</strong>. Please read this post by Woo Themes for solutions:', 'pzarchitect' ) . '<br><a href="http://docs.woothemes.com/document/problems-with-large-amounts-of-data-not-saving-variations-rates-etc/" target=_blank>Problems with large amounts of data not saving</a><br>Your max_input_vars setting is: ' . ini_get( 'max_input_vars' ),

        );

      }

      $current_theme = wp_get_theme();
      $is_hw         = ( ( $current_theme->get( 'Name' ) === 'Headway Base' || $current_theme->get( 'Template' ) == 'headway' ) );

      if ( ! $_architect_options[ 'architect_enable_styling' ] ) {
        $sections[ '_general_bp' ][ 'fields' ][] = array(
          'id'    => $prefix . 'headway-styling-message',
          'title' => __( 'Architect Styling', 'pzarchitect' ),
          'type'  => 'info',
          'desc'  => __( 'Architect Styling is turned off. You can still style Blueprints using custom CSS, or in the Headway Visual Editor if that is your theme. You can re-enable it in <em>Architect</em> > <em>Options</em> > <em>Use Architect Styling</em>. Note: Architect styling will take precedence.', 'pzarchitect' ),
        );
      }
      if ( ! $animation_state ) {
        $sections[ '_general_bp' ][ 'fields' ][] = array(
          'id'    => $prefix . 'animation-message',
          'title' => __( 'Animation', 'pzarchitect' ),
          'type'  => 'info',
          'desc'  => __( 'To use Animation settings, first enable Animation in <em>Architect</em> > <em>Options</em> > <em>Animation</em>.', 'pzarchitect' ),
        );
      }

      $sections[ '_general_bp' ][ 'fields' ][] =
        array(
          'title'    => __( 'Intended Device', 'pzarchitect' ),
          'id'       => '_blueprint_device',
          'type'     => 'button_set',
          'subtitle' => __( 'Choose the device you intend to display this Blueprint on. This is currently for information purposes only. That is, so anyone else working with this Blueprint is aware of why it is configured the way it is.', 'pzarchitect' ),
          'default'  => '',
          'options'  => array(
            ''       => __( 'Any', 'pzarchitect' ),
            'tablet' => __( 'Tablet', 'pzarchitect' ),
            'phone'  => __( 'Phone', 'pzarchitect' ),
          ),
          'hint'     => array(
            'title'   => __( 'Device', 'pzarchitect' ),
            'content' => __( 'Choose the device you intend to display this Blueprint on. This is currently for information purposes only. That is, co anyone else working with this Blueprint is aware.', 'pzarchitect' )
          )
        );
      $sections[ '_general_bp' ][ 'fields' ][] = array(
        'title'   => 'Getting help',
        'id'      => $prefix . 'help-info',
        'type'    => 'raw',
        'indent'  => false,
        'content' => '<div class="pzarc-help-section">
                        <a class="pzarc-button-help" href="http://architect4wp.com/codex-listings/" target="_blank">
                        <span class="dashicons dashicons-book"></span>
                        Documentation</a><br>
                        <a class="pzarc-button-help" href="https://pizazzwp.freshdesk.com/support/tickets/new" target="_blank">
                        <span class="dashicons dashicons-admin-tools"></span>
                        Tech support</a>
                        <a class="pzarc-button-help" href="https://shop.pizazzwp.com/checkout/customer-dashboard/" target="_blank">
                        <span class="dashicons dashicons-admin-users"></span>
                        Customer dashboard</a>
                        </div>
                        <p style="font-size:0.8em;">Architect v' . PZARC_VERSION . '</p>
                        </div>',
      );


      $metaboxes[] = array(
        'id'         => $prefix . 'layout-general-settings',
        'title'      => 'General Settings ',
        'post_types' => array( 'arc-blueprints' ),
        'sections'   => $sections,
        'position'   => 'side',
        'priority'   => 'default',
        'sidebar'    => false

      );


      return $metaboxes;

    }


    /**
     * LAYOUT
     */
    function pzarc_mb_blueprint_design( $metaboxes, $defaults_only = false ) {
      $prefix   = '_blueprints_'; // declare prefix
      $sections = array();
      global $_architect_options;
      if ( empty( $_architect_options ) ) {
        $_architect_options = get_option( '_architect_options' );
      }
      if ( empty( $_architect ) ) {
        $_architect = get_option( '_architect' );
      }


      /** SECTIONS */
      $icons       = array( 0 => 'el-icon-th-large', 1 => 'el-icon-th', 2 => 'el-icon-th-list' );
      $modes[ 0 ]  = array(
        'basic'     => __( 'Basic', 'pzarchitect' ),
        'slider'    => __( 'Slider', 'pzarchitect' ),
        'tabbed'    => __( 'Tabbed', 'pzarchitect' ),
        'masonry'   => __( 'Masonry', 'pzarchitect' ),
        'table'     => __( 'Tabular', 'pzarchitect' ),
        'accordion' => __( 'Accordion', 'pzarchitect' ),
        //          'fitRows'         => 'Fit rows',
        //          'fitColumns'      => 'Fit columns',
        //          'masonryVertical' => 'Masonry Vertical',
        //          'panelsByRow'      => 'Panels by row',
        //          'panelsByColumn'   => 'Panels by column',
        //          'vertical'    => 'Vertical',
        //          'horizontal'  => 'Horizontal',
      );
      $modes[ 1 ]  = array(
        'basic'     => __( 'Grid/Single', 'pzarchitect' ),
        'masonry'   => __( 'Masonry', 'pzarchitect' ),
        'table'     => __( 'Tabular', 'pzarchitect' ),
        'accordion' => __( 'Accordion', 'pzarchitect' ),
        //          'fitRows'         => 'Fit rows',
        //          'fitColumns'      => 'Fit columns',
        //          'masonryVertical' => 'Masonry Vertical',
        //          'panelsByRow'      => 'Panels by row',
        //          'panelsByColumn'   => 'Panels by column',
        //          'vertical'    => 'Vertical',
        //          'horizontal'  => 'Horizontal',
      );
      $modesx[ 0 ] = array(
        'basic'     => array(
          'alt' => 'Grid/Single',
          'img' => PZARC_PLUGIN_APP_URL . 'shared/assets/images/metaboxes/layouts-grid.png'
        ),
        'slider'    => array(
          'alt' => 'Slider',
          'img' => PZARC_PLUGIN_APP_URL . 'shared/assets/images/metaboxes/layouts-slider.png'
        ),
        'tabbed'    => array(
          'alt' => 'Tabbed',
          'img' => PZARC_PLUGIN_APP_URL . 'shared/assets/images/metaboxes/layouts-tabbed.png'
        ),
        'masonry'   => array(
          'alt' => 'Masonry',
          'img' => PZARC_PLUGIN_APP_URL . 'shared/assets/images/metaboxes/layouts-masonry.png'
        ),
        'table'     => array(
          'alt' => 'Tabular',
          'img' => PZARC_PLUGIN_APP_URL . 'shared/assets/images/metaboxes/layouts-tabular.png'
        ),
        'accordion' => array(
          'alt' => 'Accordion',
          'img' => PZARC_PLUGIN_APP_URL . 'shared/assets/images/metaboxes/layouts-accordion.png'
        ),
      );
      $modesx[ 1 ] = array(
        'basic'     => array(
          'alt' => 'Grid/Single',
          'img' => PZARC_PLUGIN_APP_URL . 'shared/assets/images/metaboxes/layouts-grid.png'
        ),
        'masonry'   => array(
          'alt' => 'Masonry',
          'img' => PZARC_PLUGIN_APP_URL . 'shared/assets/images/metaboxes/layouts-masonry.png'
        ),
        'table'     => array(
          'alt' => 'Tabular',
          'img' => PZARC_PLUGIN_APP_URL . 'shared/assets/images/metaboxes/layouts-tabular.png'
        ),
        'accordion' => array(
          'alt' => 'Accordion',
          'img' => PZARC_PLUGIN_APP_URL . 'shared/assets/images/metaboxes/layouts-accordion.png'
        ),
      );
      $desc[ 0 ]   = 'Grid/Single, Slider, Tabbed, Masonry, Tabular, Accordion';
      $desc[ 1 ]   = 'Grid/Single, Masonry, Tabular, Accordion';
      for ( $i = 0; $i < 1; $i ++ ) {
        $sections[ '_section' . ( $i + 1 ) ] = array(
          'title'      => __( 'Design', 'pzarchitect' ),
          'show_title' => true,
          'desc'       => '<p class="arc-important-admin-message">' . __( 'Each post in WordPress is - in a coding context - displayed in its own box. In Architect we call that box a <strong>Panel</strong>. So when you see the term Panel, it means an individual post layout.
            <br>Panels can be laid out beside each other as in grids, masonry and tabular; or layered as in sliders, tabbed and accordions
            <br>Note: The term Posts is used but refers generically to any WordPress post type or custom post type.', 'pzarchitect' ) . '</p>',
          'icon_class' => 'icon-large',
          'icon'       => $icons[ $i ],
          'fields'     => array(
            array(
              'title'    => __( 'Layout type', 'pzarchitect' ),
              'id'       => $prefix . 'section-' . $i . '-layout-mode',
              'type'     => 'image_select',
              'default'  => 'basic',
              'subtitle' => $desc[ (int) ( $i > 0 ) ],
              'height'   => 80,
              'options'  => $modesx[ (int) ( $i > 0 ) ],
              'hint'     => array(
                'title'   => 'Layout types',
                'content' => __( '<strong>Grid</strong> is for flat designs like single posts, blog excerpts and magazine grids.<br>
<br><strong>Masonry</strong> is like Basic but formats for a Pinterest-like design.<br>
<br><strong>Slider</strong> for making sliders like featured posts, image slideshows etc.<br>
<br><strong>Tabbed</strong> for tabbed designs.<br>
<br><strong>Tabular</strong> displays the content in a table, and applies extra controls.<br>
<br><em>Note: To use Tabular, make sure your Content Layout is in a table-like design - that is, all fields on a single row</em><br>
<br><strong>Accordion</strong> for a vertical Accordion design. i.e. posts in a single column.<br>
',
                                 'pzarchitect' )
              ),
            ),
            array(
              'id'     => $prefix . 'section-' . $i . '-panels-heading',
              'title'  => __( 'General panels settings', 'pzarchitect' ),
              'type'   => 'section',
              'indent' => true,
            ),
            array(
              'title'       => __( 'Limit posts', 'pzarchitect' ),
              'id'          => $prefix . 'section-' . $i . '-panels-limited',
              'type'        => 'switch',
              'on'          => __( 'Yes', 'pzarchitect' ),
              'off'         => __( 'No', 'pzarchitect' ),
              'default'     => true,
              'subtitle'    => __( 'Each panel displays a single post from the selected content type.', 'pzarchitect' ),
              'description' => ( ! defined( 'PZARC_PRO' ) ? '<br><strong>' . __( 'Architect Lite is always limited to a maximum of 15 posts/pages', 'pzarchitect' ) . '</strong>' : '' )
            ),
            array(
              'title'    => __( 'Limit number of posts to show to', 'pzarchitect' ),
              'id'       => $prefix . 'section-' . $i . '-panels-per-view',
              'type'     => 'spinner',
              'default'  => 6,
              'min'      => 1,
              'max'      => 99,
              'subtitle' => __( 'This is how many posts will show if Limit enabled above', 'pzarchitect' ),
              'required' => array( $prefix . 'section-' . $i . '-panels-limited', 'equals', true )
            ),
            array(
              'title'   => __( 'Fixed width panels', 'pzarchitect' ),
              'id'      => $prefix . 'section-' . $i . '-panels-fixed-width',
              'type'    => 'switch',
              'on'      => __( 'Yes', 'pzarchitect' ),
              'off'     => __( 'No', 'pzarchitect' ),
              'default' => false,
            ),
            array(
              'id'       => $prefix . 'section-' . $i . '-columns-heading',
              'title'    => __( 'Panels across', 'pzarchitect' ),
              'type'     => 'section',
              'indent'   => true,
              'required' => array( $prefix . 'section-' . $i . '-panels-fixed-width', 'equals', false )
            ),
            array(
              'title'         => __( 'Wide screen', 'pzarchitect' ),
              'subtitle'      => $_architect_options[ 'architect_breakpoint_1' ][ 'width' ] . ' and above',
              'id'            => $prefix . 'section-' . $i . '-columns-breakpoint-1',
              'hint'          => array(
                'title'   => __( 'Wide screen', 'pzarchitect' ),
                'content' => __( 'Number of panels across on a wide screen as set in the breakpoints options. <br><br>In sliders, this would usually be one.', 'pzarchitect' )
              ),
              'type'          => 'slider',
              'default'       => 1,
              'min'           => 1,
              'max'           => 10,
              'display_value' => 'label'
            ),
            array(
              'title'         => __( 'Medium screen', 'pzarchitect' ),
              'subtitle'      => $_architect_options[ 'architect_breakpoint_2' ][ 'width' ] . ' to ' . $_architect_options[ 'architect_breakpoint_1' ][ 'width' ],
              'id'            => $prefix . 'section-' . $i . '-columns-breakpoint-2',
              'hint'          => array(
                'title'   => __( 'Medium screen', 'pzarchitect' ),
                'content' => __( 'Number of panels across on a medium screen as set in the breakpoints options', 'pzarchitect' )
              ),
              'type'          => 'slider',
              'default'       => 1,
              'min'           => 1,
              'max'           => 10,
              'display_value' => 'label'
            ),
            array(
              'title'         => __( 'Narrow screen', 'pzarchitect' ),
              'subtitle'      => $_architect_options[ 'architect_breakpoint_2' ][ 'width' ] . ' and below',
              'id'            => $prefix . 'section-' . $i . '-columns-breakpoint-3',
              'hint'          => array(
                'title'   => __( 'Narrow screen', 'pzarchitect' ),
                'content' => __( 'Number of panels across on a narrow screen as set in the breakpoints options', 'pzarchitect' )
              ),
              'type'          => 'slider',
              'default'       => 1,
              'min'           => 1,
              'max'           => 10,
              'display_value' => 'label'
            ),
            array(
              'id'       => $prefix . 'section-' . $i . '-panel-width-heading',
              'title'    => __( 'Panel width (px)', 'pzarchitect' ),
              'type'     => 'section',
              'indent'   => true,
              'required' => array( $prefix . 'section-' . $i . '-panels-fixed-width', 'equals', true )
            ),
            array(
              'title'         => __( 'Wide screen', 'pzarchitect' ),
              'subtitle'      => $_architect_options[ 'architect_breakpoint_1' ][ 'width' ] . ' and above',
              'id'            => $prefix . 'section-' . $i . '-panel-width-breakpoint-1',
              'hint'          => array(
                'title'   => __( 'Wide screen', 'pzarchitect' ),
                'content' => __( 'Width of the panels on wide screens', 'pzarchitect' )
              ),
              'type'          => 'spinner',
              'default'       => 250,
              'min'           => 1,
              'step'          => 1,
              'max'           => 99999,
              'display_value' => 'label'
            ),
            array(
              'title'         => __( 'Medium screen', 'pzarchitect' ),
              'subtitle'      => $_architect_options[ 'architect_breakpoint_2' ][ 'width' ] . ' to ' . $_architect_options[ 'architect_breakpoint_1' ][ 'width' ],
              'id'            => $prefix . 'section-' . $i . '-panel-width-breakpoint-2',
              'hint'          => array(
                'title'   => __( 'Medium screen', 'pzarchitect' ),
                'content' => __( 'Width of the panels on mediium screens', 'pzarchitect' )
              ),
              'type'          => 'spinner',
              'default'       => 350,
              'min'           => 1,
              'step'          => 1,
              'max'           => 99999,
              'display_value' => 'label'
            ),
            array(
              'title'         => __( 'Narrow screen', 'pzarchitect' ),
              'subtitle'      => $_architect_options[ 'architect_breakpoint_2' ][ 'width' ] . ' and below',
              'id'            => $prefix . 'section-' . $i . '-panel-width-breakpoint-3',
              'hint'          => array(
                'title'   => __( 'Narrow screen', 'pzarchitect' ),
                'content' => __( 'Panel width narrow screen', 'pzarchitect' )
              ),
              'type'          => 'spinner',
              'default'       => 320,
              'min'           => 1,
              'max'           => 99999,
              'step'          => 1,
              'display_value' => 'label'
            ),
            array(
              'title'    => __( 'Justify panels', 'pzarchitect' ),
              'id'       => $prefix . 'section-' . $i . '-panels-fixed-width-justify',
              'type'     => 'button_set',
              'options'  => array(
                'justify-content: flex-start;'    => 'Start',
                'justify-content: flex-end;'      => 'End',
                'justify-content: center;'        => 'Centre',
                'justify-content: space-between;' => 'Space between',
                'justify-content: space-around;'  => 'Space around',
              ),
              'default'  => 'justify-content: space-between;',
              'required' => array(
                array( $prefix . 'section-' . $i . '-panels-fixed-width', 'equals', true ),
                array( $prefix . 'section-0-layout-mode', '=', 'basic' )
              ),
              'subtitle' => __( 'These are the standard Flexbox justification options', 'pzarchitect' )
            ),
            array(
              'title'    => __( 'Stretch panels to fill', 'pzarchitect' ),
              'id'       => $prefix . 'section-' . $i . '-panels-fixed-width-fill',
              'type'     => 'switch',
              'on'       => __( 'Yes', 'pzarchitect' ),
              'off'      => __( 'No', 'pzarchitect' ),
              'subtitle' => __( 'Stretches panels to fill all available space per row, except margins.', 'pzarchitect' ),
              'default'  => false,
              'required' => array(
                array( $prefix . 'section-' . $i . '-panels-fixed-width', 'equals', true ),
                array( $prefix . 'section-0-layout-mode', '=', 'basic' )
              ),
            ),
            array(
              'id'     => $prefix . 'section-' . $i . '-panels-settings-heading',
              'title'  => __( 'Panel dimensions', 'pzarchitect' ),
              'type'   => 'section',
              'indent' => true,
            ),
            array(
              'title'   => __( 'Minimum panel width', 'pzarchitect' ),
              'id'      => $prefix . 'section-' . $i . '-min-panel-width',
              'type'    => 'dimensions',
              'height'  => false,
              'units'   => 'px',
              'default' => array( 'width' => '0' ),
              //      'hint'  => array('content' => __('Set the minimum width for panels in this section. This helps with responsive layout', 'pzarchitect'))
            ),
            array(
              'title'   => __( 'Panel Height Type', 'pzarchitect' ),
              'id'      => '_panels_settings_' . 'panel-height-type',
              'type'    => 'select',
              'default' => 'none',
              //                  'class'=> 'arc-field-advanced' ,
              //'required' => array('show_advanced', 'equals', true),
              'select2' => array( 'allowClear' => false ),
              'options' => array(
                'none'       => __( 'Fluid', 'pzarchitect' ),
                'height'     => __( 'Exact', 'pzarchitect' ),
                'max-height' => __( 'Max', 'pzarchitect' ),
                'min-height' => __( 'Min', 'pzarchitect' )
              ),
              'hint'    => array(
                'title'   => __( 'Height type', 'pzarchitect' ),
                'content' => __( 'Choose if you want an exact height or not for the panels. If you want totally fluid, choose Min, and set a height of 0.', 'pzarchitect' )
              )
            ),
            // Hmm? How's this gunna sit with the min-height in templates?
            // We will want to use this for image height cropping when behind.

            array(
              'title'    => __( 'Panel Height px', 'pzarchitect' ),
              'id'       => '_panels_settings_' . 'panel-height',
              'type'     => 'dimensions',
              //          'class'=> 'arc-field-advanced' ,
              'required' => array(
                array( '_panels_settings_' . 'panel-height-type', '!=', 'none' ),
                //                      array('show_advanced', 'equals', true),,
              ),
              'width'    => false,
              'units'    => 'px',
              'default'  => array( 'height' => '0' ),
              'hint'     => array(
                'title'   => __( 'Height', 'pzarchitect' ),
                'content' => __( 'Set a height in pixels for the panel according to the height type you chose.', 'pzarchitect' )
              ),

            ),
            array(
              'title'   => __( 'Panel margins', 'pzarchitect' ),
              'id'      => $prefix . 'section-' . $i . '-panels-margins',
              'type'    => 'spacing',
              'units'   => array( '%', 'px', 'em' ),
              'mode'    => 'margin',
              'default' => array(
                'margin-right'  => '0',
                'margin-bottom' => '0',
                'margin-left'   => '0',
                'margin-top'    => '0'
              ),
              //'subtitle' => __('Right, bottom', 'pzarchitect')
              //    'hint'  => array('content' => __('Set the vertical gutter width as a percentage of the section width. The gutter is the gap between adjoining elements', 'pzarchitect'))
            ),
            array(
              'id'       => $prefix . 'section-' . $i . '-panels-margins-guttered',
              'type'     => 'switch',
              'on'       => __( 'Yes', 'pzarchitect' ),
              'off'      => __( 'No', 'pzarchitect' ),
              'default'  => true,
              'title'    => __( 'Exclude top/left/right margins on outer panels', 'pzarchitect' ),
              'required' => array( $prefix . 'section-' . $i . '-layout-mode', '=', 'basic' ),
            ),
            array(
              'id'     => $prefix . 'section-' . $i . '-blueprint-settings-heading',
              'title'  => __( 'Blueprint settings', 'pzarchitect' ),
              'type'   => 'section',
              'indent' => true,
            ),
            array(
              'id'       => $prefix . 'blueprint-width',
              'type'     => 'dimensions',
              //               'mode'    => array('width' => true, 'height' => false),
              'units'    => array( '%', 'px' ),
              'width'    => true,
              'height'   => false,
              'title'    => __( 'Blueprint max width', 'pzarchitect' ),
              'default'  => array( 'width' => '100', 'units' => '%' ),
              'subtitle' => __( 'Set a max width to stop spillage when the container is larger than you want the Blueprint to be.', 'pzarchitect' )
            ),
            array(
              'id'      => $prefix . 'blueprint-align',
              'type'    => 'button_set',
              'select2' => array( 'allowClear' => false ),
              'options' => array(
                'left'   => __( 'Left', 'pzarchitect' ),
                'center' => __( 'Centre', 'pzarchitect' ),
                'right'  => __( 'Right', 'pzarchitect' )
              ),
              'title'   => __( 'Blueprint align', 'pzarchitect' ),
              'default' => 'center',
            ),
            array(
              'title'    => 'Page title',
              'id'       => $prefix . 'page-title',
              'type'     => 'switch',
              'subtitle' => __( 'Show page title on single and archive pages', 'pzarchitect' ),
              'on'       => 'Yes',
              'off'      => 'No',
              'default'  => false
            ),
            array(
              'title'    => 'Hide archive title prefix',
              'id'       => $prefix . 'hide-archive-title-prefix',
              'type'     => 'switch',
              'subtitle' => __( 'When on an Archive page, hide the archive prefix from Architect > Options > Language', 'pzarchitect' ),
              'on'       => 'Yes',
              'off'      => 'No',
              'default'  => false,
              'required' => array( $prefix . 'page-title', '=', true )
            ),
            array(
              'title'    => 'Blueprint display title',
              'id'       => $prefix . 'blueprint-title',
              'type'     => 'text',
              'subtitle' => __( 'Enter a title to display above the Blueprint', 'pzarchitect' ),
            ),
            array(
              'title'    => 'Blueprint footer text',
              'id'       => $prefix . 'footer-text-link',
              'type'     => 'text',
              'subtitle' => __( 'Enter text to show at the foot of the Blueprint. You can also enter a shortcode here.', 'pzarchitect' ),
            ),
          )

        );
      }

      /**
       * Styling
       */

      if ( ! empty( $_architect_options[ 'architect_enable_styling' ] ) ) {
        $defaults = get_option( '_architect' );
        $prefix   = '_blueprints_styling_'; // declare prefix
// TODO: need to get styling to take
        $font         = '-font';
        $link         = '-links';
        $padding      = '-padding';
        $margin       = '-margins';
        $background   = '-background';
        $border       = '-borders';
        $borderradius = '-borderradius';

        $stylingSections = array();
        $optprefix       = 'architect_config_';

        $thisSection = 'blueprint';

        $sections[ '_styling_general' ] = array(
          'title'      => 'Blueprint styling',
          'show_title' => false,
          'icon_class' => 'icon-large',
          'icon'       => 'el-icon-brush',
          'fields'     => pzarc_fields(
//                array(
//                    'title'   => __('Load style'),
//                    'id'      => $prefix . 'blueprints-load-style',
//                    'type'    => 'select',
//                    'desc'    => 'Sorry to tease, but this isn\'t implemented yet.',
//                    'options' => array('none', 'dark', 'light'),
//                    'default' => 'none'
//                ),
            array(
              'title'    => __( 'Blueprint', 'pzarchitect' ),
              'id'       => $prefix . 'blueprint-section-blueprint',
              'type'     => 'section',
              'indent'   => true,
              'class'    => 'heading',
              'subtitle' => 'Class: .pzarc-blueprint_{shortname}',
            ),

            // TODO: Add shadows! Or maybe not!
            pzarc_redux_bg( $prefix . $thisSection . $background, $this->defaults ? '' : $_architect[ 'architect_config_' . $thisSection . '-selectors' ], $defaults[ $optprefix . $thisSection . $background ] ),
            pzarc_redux_padding( $prefix . $thisSection . $padding, $this->defaults ? '' : $_architect[ 'architect_config_' . $thisSection . '-selectors' ], $defaults[ $optprefix . $thisSection . $padding ] ),
            pzarc_redux_margin( $prefix . $thisSection . $margin, $this->defaults ? '' : $_architect[ 'architect_config_' . $thisSection . '-selectors' ], $defaults[ $optprefix . $thisSection . $margin ] ),
            pzarc_redux_borders( $prefix . $thisSection . $border, $this->defaults ? '' : $_architect[ 'architect_config_' . $thisSection . '-selectors' ], $defaults[ $optprefix . $thisSection . $border ] ),
            pzarc_redux_links( $prefix . $thisSection . $link, $this->defaults ? '' : $_architect[ 'architect_config_' . $thisSection . '-selectors' ], $defaults[ $optprefix . $thisSection . $link ] ),
            array(
              'id'     => $prefix . 'blueprint-end-section-blueprint',
              'type'   => 'section',
              'indent' => false,
            ),
            array(
              'title'    => __( 'Blueprint title', 'pzarchitect' ),
              'id'       => $prefix . 'blueprint-section-blueprint-title-css',
              'type'     => 'section',
              'indent'   => true,
              'subtitle' => 'Class: .pzarc-blueprint-title',
            ),
            pzarc_redux_font( $prefix . $thisSection . '_blueprint-title' . $font, $this->defaults ? '' : $_architect[ 'architect_config_' . $thisSection . '_blueprint-title' . '-selectors' ], $defaults[ $optprefix . $thisSection . '_blueprint-title' . $font ] ),
            pzarc_redux_bg( $prefix . $thisSection . '_blueprint-title' . $background, $this->defaults ? '' : $_architect[ 'architect_config_' . $thisSection . '_blueprint-title' . '-selectors' ], $defaults[ $optprefix . $thisSection . '_blueprint-title' . $background ] ),
            pzarc_redux_padding( $prefix . $thisSection . '_blueprint-title' . $padding, $this->defaults ? '' : $_architect[ 'architect_config_' . $thisSection . '_blueprint-title' . '-selectors' ], $defaults[ $optprefix . $thisSection . '_blueprint-title' . $padding ] ),
            pzarc_redux_margin( $prefix . $thisSection . '_blueprint-title' . $margin, $this->defaults ? '' : $_architect[ 'architect_config_' . $thisSection . '_blueprint-title' . '-selectors' ], $defaults[ $optprefix . $thisSection . '_blueprint-title' . $margin ] ),
            pzarc_redux_borders( $prefix . $thisSection . '_blueprint-title' . $border, $this->defaults ? '' : $_architect[ 'architect_config_' . $thisSection . '_blueprint-title' . '-selectors' ], $defaults[ $optprefix . $thisSection . '_blueprint-title' . $border ] ),
            array(
              'id'     => $prefix . 'blueprint-end-section-blueprint-title',
              'type'   => 'section',
              'indent' => false,
            ),
            array(
              'title'    => __( 'Blueprint footer', 'pzarchitect' ),
              'id'       => $prefix . 'blueprint-section-blueprint-footer-css',
              'type'     => 'section',
              'indent'   => true,
              'subtitle' => 'Class: .pzarc-blueprint-footer',
            ),
            pzarc_redux_font( $prefix . $thisSection . '_blueprint-footer' . $font, $this->defaults ? '' : $_architect[ 'architect_config_' . $thisSection . '_blueprint-footer' . '-selectors' ], $defaults[ $optprefix . $thisSection . '_blueprint-footer' . $font ] ),
            pzarc_redux_bg( $prefix . $thisSection . '_blueprint-footer' . $background, $this->defaults ? '' : $_architect[ 'architect_config_' . $thisSection . '_blueprint-footer' . '-selectors' ], $defaults[ $optprefix . $thisSection . '_blueprint-footer' . $background ] ),
            pzarc_redux_padding( $prefix . $thisSection . '_blueprint-footer' . $padding, $this->defaults ? '' : $_architect[ 'architect_config_' . $thisSection . '_blueprint-footer' . '-selectors' ], $defaults[ $optprefix . $thisSection . '_blueprint-footer' . $padding ] ),
            pzarc_redux_margin( $prefix . $thisSection . '_blueprint-footer' . $margin, $this->defaults ? '' : $_architect[ 'architect_config_' . $thisSection . '_blueprint-footer' . '-selectors' ], $defaults[ $optprefix . $thisSection . '_blueprint-footer' . $margin ] ),
            pzarc_redux_borders( $prefix . $thisSection . '_blueprint-footer' . $border, $this->defaults ? '' : $_architect[ 'architect_config_' . $thisSection . '_blueprint-footer' . '-selectors' ], $defaults[ $optprefix . $thisSection . '_blueprint-footer' . $border ] ),
            pzarc_redux_links( $prefix . $thisSection . '_blueprint-footer' . $link, $this->defaults ? '' : $_architect[ 'architect_config_' . $thisSection . '_blueprint-footer' . '-selectors' ], $defaults[ $optprefix . $thisSection . '_blueprint-footer' . $link ] ),
            array(
              'id'     => $prefix . 'blueprint-end-section-blueprint-footer',
              'type'   => 'section',
              'indent' => false,
            )
          )
        );

        $thisSection                 = 'page';
        $sections[ '_styling_page' ] = array(
          'title'      => 'Page styling',
          'show_title' => false,
          'icon_class' => 'icon-large',
          'icon'       => 'el-icon-file',
          'fields'     => pzarc_fields(
            array(
              'title'    => __( 'Page title', 'pzarchitect' ),
              'id'       => $prefix . 'blueprint-section-page-title',
              'type'     => 'section',
              'indent'   => true,
              'class'    => 'heading',
              'subtitle' => 'Class: .pzarc-page-title',
            ),
            pzarc_redux_font( $prefix . $thisSection . '_page-title' . $font, $this->defaults ? '' : $_architect[ 'architect_config_' . $thisSection . '_page-title' . '-selectors' ], $defaults[ $optprefix . $thisSection . '_page-title' . $font ] ),
            pzarc_redux_bg( $prefix . $thisSection . '_page-title' . $background, $this->defaults ? '' : $_architect[ 'architect_config_' . $thisSection . '_page-title' . '-selectors' ], $defaults[ $optprefix . $thisSection . '_page-title' . $background ] ),
            pzarc_redux_padding( $prefix . $thisSection . '_page-title' . $padding, $this->defaults ? '' : $_architect[ 'architect_config_' . $thisSection . '_page-title' . '-selectors' ], $defaults[ $optprefix . $thisSection . '_page-title' . $padding ] ),
            pzarc_redux_margin( $prefix . $thisSection . '_page-title' . $margin, $this->defaults ? '' : $_architect[ 'architect_config_' . $thisSection . '_page-title' . '-selectors' ], $defaults[ $optprefix . $thisSection . '_page-title' . $margin ] ),
            pzarc_redux_borders( $prefix . $thisSection . '_page-title' . $border, $this->defaults ? '' : $_architect[ 'architect_config_' . $thisSection . '_page-title' . '-selectors' ], $defaults[ $optprefix . $thisSection . '_page-title' . $border ] ),
            array(
              'id'     => $prefix . 'blueprint-end-section-page-title',
              'type'   => 'section',
              'indent' => false,
            )
          )
        );


        $thisSection                             = 'sections';
        $sections[ '_styling_sections_wrapper' ] = array(
          'title'      => 'Panels wrapper styling',
          'show_title' => false,
          'icon_class' => 'icon-large',
          'icon'       => 'el-icon-check-empty',
          'desc'       => 'Class: .pzarc-sections_{shortname}',
          'fields'     => pzarc_fields(

          // TODO: Add shadows
          //$prefix . 'hentry' . $background, $_architect[ 'architect_config_hentry-selectors' ], $defaults[ $optprefix . 'hentry' . $background ]
            pzarc_redux_bg( $prefix . $thisSection . $background, $this->defaults ? '' : $_architect[ 'architect_config_' . $thisSection . '-selectors' ], $defaults[ $optprefix . $thisSection . $background ] ),
            pzarc_redux_padding( $prefix . $thisSection . $padding, $this->defaults ? '' : $_architect[ 'architect_config_' . $thisSection . '-selectors' ], $defaults[ $optprefix . $thisSection . $padding ] ),
            pzarc_redux_margin( $prefix . $thisSection . $margin, $this->defaults ? '' : $_architect[ 'architect_config_' . $thisSection . '-selectors' ], $defaults[ $optprefix . $thisSection . $margin ] ),
            pzarc_redux_borders( $prefix . $thisSection . $border, $this->defaults ? '' : $_architect[ 'architect_config_' . $thisSection . '-selectors' ], $defaults[ $optprefix . $thisSection . $border ] )
          )
        );

        /**
         * CUSTOM CSS
         */
        $sections[ '_bp_custom_css' ] = array(
          'id'         => 'bp-custom-css',
          'title'      => __( 'Custom CSS', 'pzarchitect' ),
          'icon_class' => 'icon-large',
          'icon'       => 'el-icon-wrench',
          'fields'     => array(
            array(
              'id'       => $prefix . 'blueprint-custom-css',
              'type'     => 'ace_editor',
              'title'    => __( 'Custom CSS', 'pzarchitect' ),
              'mode'     => 'css',
              'options'  => array( 'minLines' => 25 ),
              'subtitle' => __( 'As a shorthand, you can prefix your CSS class with MYBLUEPRINT and Architect will substitute the correct class for this Blueprint. e.g. MYBLUEPRINT {border-radius:5px;}', 'pzarchitect' )
            ),
          )
        );
      }
//      $file_contents          = file_get_contents(PZARC_DOCUMENTATION_PATH . PZARC_LANGUAGE . '/using-blueprints.md');
      $file_contents = '';
      if ( is_admin() ) {
        $ch = curl_init( PZARC_DOCUMENTATION_URL . PZARC_LANGUAGE . '/using-blueprints.md' );
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
        $file_contents = curl_exec( $ch );
        curl_close( $ch );
      }
      $sections[ '_help' ] = array(
        'title'      => 'Help',
        'icon_class' => 'icon-large',
        'icon'       => 'el-icon-question-sign',
        'fields'     => array(
          array(
            'title'    => __( 'Enabling styling tabs', 'pzarchitect' ),
            'id'       => $prefix . 'help-usingbp-styling-tabs',
            'type'     => 'raw',
            'markdown' => false,
            'content'  => __( 'If you are using <strong>Headway</strong>, the Architect styling tabs are off by default. They can be enabled in Architect > Options > Use Architect Styling. Styling applied in the Headway Visual Editor will still be used, but the Architect styling will take precedence.', 'pzarchitect' )
          ),
          array(
            'title'    => __( 'Enabling animation tab', 'pzarchitect' ),
            'id'       => $prefix . 'help-usingbp-animation-tabs',
            'type'     => 'raw',
            'markdown' => false,
            'content'  => __( 'Animation is off by default. It can be enabled in Architect > Animation > Enable Animation', 'pzarchitect' )
          ),

          array(
            'title'    => __( 'Displaying Blueprints', 'pzarchitect' ),
            'id'       => $prefix . 'help-blueprints',
            'type'     => 'raw',
            'class'    => 'plain',
            'markdown' => true,
            'content'  => ( $defaults_only ? '' : $file_contents ),
            'pzarchitect'
          ),
          array(
            'title'    => __( 'Blueprint styling', 'pzarchitect' ),
            'id'       => $prefix . 'help',
            'type'     => 'raw',
            'markdown' => false,
            //  'class' => 'plain',
            'content'  => '<h3>Adding underlines to hover links</h3>
                            <p>In the Custom CSS field, enter the following CSS</p>
                            <p>.pzarc-blueprint_SHORTNAME a:hover {text-decoration:underline;}</p>
                            <p>SHORTNAME = the short name you entered for this blueprint</p>
                            <h3>Make pager appear outside of panels</h3>
                            <p>If you want the pager to appear outside of the panels instead of over them, set a the sections width less than 100%.</p>
                            '
          ),
          array(
            'title'    => __( 'Online documentation', 'pzarchitect' ),
            'id'       => $prefix . 'help-content-online-docs',
            'type'     => 'raw',
            'markdown' => false,
            'content'  => '<a href="http://architect4wp.com/codex-listings/" target=_blank>' . __( 'Architect Online Documentation', 'pzarchitect' ) . '</a><br>' . __( 'This is a growing resource. Please check back regularly.', 'pzarchitect' )

          ),
        )
      );

      $metaboxes[] = array(
        'id'         => 'layout-settings',
        'title'      => 'Blueprint Design and stylings: Choose and setup the overall design and stylings',
        'post_types' => array( 'arc-blueprints' ),
        'sections'   => $sections,
        'position'   => 'normal',
        'priority'   => 'low',
        'sidebar'    => false

      );

      return $metaboxes;
    }


    function pzarc_mb_blueprint_types( $metaboxes, $defaults_only = false ) {
      global $_architect;
      global $_architect_options;
      if ( empty( $_architect_options ) ) {
        $_architect_options = get_option( '_architect_options' );
      }

      if ( empty( $_architect ) ) {
        $_architect = get_option( '_architect' );
      }

      $prefix                   = '_blueprints_'; // declare prefix
      $sections                 = array();
      $sections[ '_tabular' ]   = array(
        'title'      => __( 'Tabular settings', 'pzarchitect' ),
        'show_title' => true,
        'icon_class' => 'icon-large',
        'icon'       => 'el-icon-adjust-alt',
        //          'desc'       => 'When the navigation type is set to navigator, presentation will always be in a slider form. You can have multiple navigators on a page, thus multiple sliders.',
        'fields'     => array(
          array(
            'id'         => $prefix . 'section-0-table-column-titles',
            'title'      => __( 'Table column titles', 'pzarchitect' ),
            'type'       => 'multi_text',
            'show_empty' => false,
            'add_text'   => 'Add a title',
            'default'    => array(),
          )
        )
      );
      $sections[ '_accordion' ] = array(
        'title'      => __( 'Accordion settings', 'pzarchitect' ),
        'show_title' => true,
        'icon_class' => 'icon-large',
        'icon'       => 'el-icon-adjust-alt',
        'fields'     => array(
          array(
            'title'    => __( 'Accordion open', 'pzarchitect' ),
            'id'       => $prefix . 'accordion-closed',
            'type'     => 'switch',
            'on'       => __( 'Yes', 'pzarchitect' ),
            'off'      => __( 'No', 'pzarchitect' ),
            'default'  => false,
            'subtitle' => __( 'Turn off to have accordion closed on startup.', 'pzarchitect' )
          ),
          array(
            'id'         => $prefix . 'section-0-accordion-titles',
            'title'      => __( 'Accordion titles', 'pzarchitect' ),
            'type'       => 'multi_text',
            'show_empty' => false,
            'add_text'   => 'Add a title',
            'subtitle'   => 'Optional. Leave as none to use post titles',
            'default'    => array(),
          ),
        )
      );
      /**
       *
       * SLIDERS & TABBED
       */
      $tabbed = array(
        'tabbed' => array(
          'alt' => 'Titles',
          'img' => PZARC_PLUGIN_APP_URL . 'shared/assets/images/metaboxes/nav-type-tabbed.png'
        ),
        'labels' => array(
          'alt' => 'Labels',
          'img' => PZARC_PLUGIN_APP_URL . 'shared/assets/images/metaboxes/nav-type-labels.png'
        ),
      );
      $slider = array(
        'bullets' => array(
          'alt' => 'Bullets',
          'img' => PZARC_PLUGIN_APP_URL . 'shared/assets/images/metaboxes/nav-type-bullets.png'
        ),
        'tabbed'  => array(
          'alt' => 'Titles',
          'img' => PZARC_PLUGIN_APP_URL . 'shared/assets/images/metaboxes/nav-type-tabbed.png'
        ),
        'labels'  => array(
          'alt' => 'Labels',
          'img' => PZARC_PLUGIN_APP_URL . 'shared/assets/images/metaboxes/nav-type-labels.png'
        ),
        'numbers' => array(
          'alt' => 'Numbers',
          'img' => PZARC_PLUGIN_APP_URL . 'shared/assets/images/metaboxes/nav-type-numeric.png'
        ),
        'thumbs'  => array(
          'alt' => 'Thumbs',
          'img' => PZARC_PLUGIN_APP_URL . 'shared/assets/images/metaboxes/nav-type-thumbs.png'
        ),
        'none'    => array(
          'alt' => 'None',
          'img' => PZARC_PLUGIN_APP_URL . 'shared/assets/images/metaboxes/nav-type-none.png'
        ),
      );

      $sections[ '_slidertabbed' ] = array(
        'title'      => __( 'Sliders & Tabbed settings', 'pzarchitect' ),
        'show_title' => true,
        'icon_class' => 'icon-large',
        'icon'       => 'el-icon-adjust-alt',
        //          'desc'       => 'When the navigation type is set to navigator, presentation will always be in a slider form. You can have multiple navigators on a page, thus multiple sliders.',
        'fields'     => array(
          array(
            'id'       => $prefix . 'slider-engine',
            'title'    => __( 'Slider engine', 'pzarchitect' ),
            'type'     => 'button_set',
            'default'  => 'slick',
            'options'  => apply_filters( 'arc-slider-engine', array( 'slick' => 'Slick' ) ),
            'required' => array(
              array( $prefix . 'section-0-layout-mode', '!=', 'basic' ),
              array( $prefix . 'section-0-layout-mode', '!=', 'masonry' ),
              array( $prefix . 'section-0-layout-mode', '!=', 'accordion' ),
              array( $prefix . 'section-0-layout-mode', '!=', 'table' ),
            ),
            'hint'     => array(
              'title'   => __( 'Slider engine', 'pzarchitect' ),
              'content' => __( 'Sliders and Tabbed are controlled by a slider engine. Developers can add their own', 'pzarchitect' )
            )
          ),
          array(
            'id'      => $prefix . 'navigator',
            'title'   => __( 'Type', 'pzarchitect' ),
            'type'    => 'image_select',
            'default' => 'bullets',
            'hint'    => array( 'content' => __( 'Bullets,Titles, Labels, Numbers, Thumbnails or none', 'pzarchitect' ) ),
            'options' => $slider
          ),
          array(
            'title'    => __( 'Titles & Labels', 'pzarchitect' ),
            'id'       => $prefix . 'section-navtabs-heading',
            'type'     => 'section',
            'indent'   => true,
            'required' => array(
              array( $prefix . 'navigator', '!=', 'buttons' ),
              array( $prefix . 'navigator', '!=', 'numbers' ),
              array( $prefix . 'navigator', '!=', 'bullets' ),
              array( $prefix . 'navigator', '!=', 'thumbs' ),
              array( $prefix . 'navigator', '!=', 'none' ),
              //                      array($prefix . 'navigator-position', '!=', 'left'),
              //                      array($prefix . 'navigator-position', '!=', 'right')
            ),
          ),
          array(
            'title'      => 'Labels',
            'id'         => $prefix . 'navigator-labels',
            'type'       => 'multi_text',
            'show_empty' => false,
            'add_text'   => 'Add a label',
            'default'    => 'Label name',
            'required'   => array(
              array( $prefix . 'navigator', 'equals', 'labels' ),
            ),
            'subtitle'   => 'One label per panel. Labels only work for a fixed number of panels'
          ),
          array(
            'title'    => 'Width type',
            'id'       => $prefix . 'navtabs-width-type',
            'type'     => 'button_set',
            'default'  => 'fluid',
            'options'  => array(
              'fluid' => 'Fluid',
              'even'  => 'Even',
              'fixed' => 'Fixed'
            ),
            'required' => array(
              array( $prefix . 'navigator-position', '!=', 'left' ),
              array( $prefix . 'navigator-position', '!=', 'right' )
            ),
            'hint'     => array(
              'title'   => __( 'Tab width type', 'pzarchitect' ),
              'content' => __( 'Fluid: Adjusts to the width of the content<br>Even: Distributes evenly across the width of the blueprint<br>Fixed: Set a specific width', 'pzarchitect' )
            )
          ),
          array(
            'id'       => $prefix . 'navtabs-margins-compensation',
            'type'     => 'dimensions',
            'units'    => array( '%', 'px', 'em' ),
            'width'    => true,
            'height'   => false,
            'title'    => __( 'Margins compensation', 'pzarchitect' ),
            'default'  => array( 'width' => '0', 'units' => '%' ),
            'subtitle' => __( 'If you set margins for the tabs anywhere else, enter the sum of the left and right margins and units of a single tab. e.g. if margins are 5px, enter 10px', 'pzarchitect' ),
            'required' => array(
              array( $prefix . 'navtabs-width-type', 'contains', 'even' ),
            ),
          ),
          array(
            'id'       => $prefix . 'navtabs-width',
            'type'     => 'dimensions',
            'units'    => array( '%', 'px', 'em' ),
            'width'    => true,
            'height'   => false,
            'title'    => __( 'Fixed width', 'pzarchitect' ),
            'default'  => array( 'width' => '10', 'units' => '%' ),
            'required' => array(
              array( $prefix . 'navtabs-width-type', 'contains', 'fixed' ),
            ),
          ),
          array(
            'id'       => $prefix . 'navtabs-maxlen',
            'type'     => 'spinner',
            'title'    => __( 'Max length (characters)', 'pzarchitect' ),
            'default'  => 0,
            'min'      => 0,
            'max'      => 1000,
            'subtitle' => __( '0 for no max', 'pzarchitect' )
          ),
          array(
            'title'   => 'Text wrap',
            'id'      => $prefix . 'navtabs-textwrap',
            'type'    => 'button_set',
            'default' => '',
            'options' => array(
              ''           => 'Default',
              'wraplines'  => 'Wrap lines',
              'break-word' => 'Break word',
              'nowrap'     => 'No wrap'
            ),
          ),
          array(
            'title'    => 'Text overflow',
            'id'       => $prefix . 'navtabs-textoverflow',
            'type'     => 'button_set',
            'default'  => '',
            'required' => array( $prefix . 'navtabs-textwrap', '!=', 'break-word' ),
            'options'  => array(
              ''                => 'Default',
              'visible'         => 'Visible',
              'hidden-ellipses' => 'Hidden with ellipses',
              'hidden-clip'     => 'Hidden with clipping'
            ),
          ),
          array(
            'id'       => $prefix . 'section-navtabs-end',
            'type'     => 'section',
            'indent'   => false,
            'required' => array(
              array( $prefix . 'section-0-layout-mode', '=', 'tabbed' ),
            ),
          ),
          array(
            'title'  => __( 'Layout', 'pzarchitect' ),
            'id'     => $prefix . 'section-navlayout-heading',
            'type'   => 'section',
            'indent' => true,
          ),
          array(
            'title'    => 'Position',
            'id'       => $prefix . 'navigator-position',
            'type'     => 'image_select',
            'default'  => 'bottom',
            'hint'     => array( 'content' => __( 'Bottom, top,left, right', 'pzarchitect' ) ),
            'height'   => 75,
            'options'  => array(
              'bottom' => array(
                'alt' => 'Bottom',
                'img' => PZARC_PLUGIN_APP_URL . 'shared/assets/images/metaboxes/nav-pos-bottom.png'
              ),
              'top'    => array(
                'alt' => 'Top',
                'img' => PZARC_PLUGIN_APP_URL . 'shared/assets/images/metaboxes/nav-pos-top.png'
              ),
              'left'   => array(
                'alt' => 'Left',
                'img' => PZARC_PLUGIN_APP_URL . 'shared/assets/images/metaboxes/nav-pos-left.png'
              ),
              'right'  => array(
                'alt' => 'Right',
                'img' => PZARC_PLUGIN_APP_URL . 'shared/assets/images/metaboxes/nav-pos-right.png'
              ),
            ),
            'required' => array(
              array( $prefix . 'navigator', '!=', 'thumbs' ),
              array( $prefix . 'navigator', '!=', 'none' ),
            )
          ),
          array(
            'title'    => 'Position',
            'id'       => $prefix . 'navigator-thumbs-position',
            'type'     => 'image_select',
            'default'  => 'bottom',
            'hint'     => array( 'content' => __( 'Bottom, top', 'pzarchitect' ) ),
            'height'   => 75,
            'options'  => array(
              'bottom' => array(
                'alt' => 'Bottom',
                'img' => PZARC_PLUGIN_APP_URL . 'shared/assets/images/metaboxes/nav-pos-bottom.png'
              ),
              'top'    => array(
                'alt' => 'Top',
                'img' => PZARC_PLUGIN_APP_URL . 'shared/assets/images/metaboxes/nav-pos-top.png'
              ),
            ),
            'required' => array(
              array( $prefix . 'navigator', '=', 'thumbs' ),
            )
          ),
          array(
            'title'    => 'Location',
            'id'       => $prefix . 'navigator-location',
            'hint'     => array( 'content' => __( 'Select whether navigator should appear over the content area, or outside of it', 'pzarchitect' ) ),
            'type'     => 'image_select',
            'default'  => 'outside',
            'height'   => 75,
            'options'  => array(
              'inside'  => array(
                'alt' => 'Inside',
                'img' => PZARC_PLUGIN_APP_URL . 'shared/assets/images/metaboxes/nav-loc-inside.png'
              ),
              'outside' => array(
                'alt' => 'Outside',
                'img' => PZARC_PLUGIN_APP_URL . 'shared/assets/images/metaboxes/nav-loc-outside.png'
              ),
            ),
            'required' => array(
              array( $prefix . 'navigator', '!=', 'accordion' ),
              array( $prefix . 'navigator', '!=', 'none' ),
              array( $prefix . 'navigator', '!=', 'tabbed' ),
              array( $prefix . 'navigator', '!=', 'thumbs' ),
              array( $prefix . 'navigator-position', '!=', 'left' ),
              array( $prefix . 'navigator-position', '!=', 'right' )
            )

          ),
          array(
            'title'    => 'Horizontal alignment',
            'id'       => $prefix . 'navigator-align',
            'type'     => 'button_set',
            'default'  => 'center',
            'options'  => array(
              'left'    => 'Left',
              'center'  => 'Centre',
              'right'   => 'Right',
              'justify' => 'Justified'
            ),
            'required' => array(
              array( $prefix . 'navigator', '!=', 'accordion' ),
              array( $prefix . 'navigator', '!=', 'buttons' ),
              array( $prefix . 'navigator', '!=', 'thumbs' ),
              array( $prefix . 'navigator', '!=', 'none' ),
              array( $prefix . 'navigator-position', '!=', 'left' ),
              array( $prefix . 'navigator-position', '!=', 'right' )
            )
          ),
          array(
            'title'    => 'Vertical width',
            'id'       => $prefix . 'navigator-vertical-width',
            'type'     => 'dimensions',
            'default'  => array( 'width' => '15%' ),
            'height'   => false,
            'units'    => '%',
            'subtitle' => __( 'Note: Set left and right padding on the navigator to zero.', 'pzarchitect' ),
            'required' => array(
              array( $prefix . 'navigator', '!=', 'thumbs' ),
              array( $prefix . 'navigator', '!=', 'accordion' ),
              array( $prefix . 'navigator', '!=', 'none' ),
              array( $prefix . 'navigator-position', '!=', 'top' ),
              array( $prefix . 'navigator-position', '!=', 'bottom' )
            )
          ),
          array(
            'title'    => 'Bullet shape',
            'id'       => $prefix . 'navigator-bullet-shape',
            'type'     => 'button_set',
            'default'  => 'circle',
            'options'  => array(
              'circle' => 'Circle',
              'square' => 'Square',
            ),
            'required' => array(
              array( $prefix . 'navigator', 'equals', 'bullets' ),
            )
          ),
          array(
            'title'    => 'Sizing',
            'id'       => $prefix . 'navigator-sizing',
            'type'     => 'button_set',
            'default'  => 'small',
            'options'  => array(
              'small'  => 'Small',
              'medium' => 'Medium',
              'large'  => 'Large'
            ),
            'required' => array(
              array( $prefix . 'navigator', '!=', 'none' ),
              array( $prefix . 'navigator', '!=', 'thumbs' ),
              array( $prefix . 'navigator', '!=', 'tabbed' ),
              array( $prefix . 'navigator', '!=', 'labels' ),
            )
          ),
          array(
            'title'    => 'Thumb dimensions',
            'id'       => $prefix . 'navigator-thumb-dimensions',
            'type'     => 'dimensions',
            'default'  => array( 'width' => '50px', 'height' => '50px' ),
            'units'    => 'px',
            'required' => array(
              array( $prefix . 'navigator', '=', 'thumbs' ),
            )
          ),
          array(
            'title'    => 'Slide Pager',
            'id'       => $prefix . 'navigator-pager',
            'type'     => 'button_set',
            'default'  => 'hover',
            'options'  => array(
              'none'  => 'None',
              'hover' => 'Hover',
              /// TODO: Add inline pager to nav
              //                      'inline' => 'Inline with navigator',
              //                      'both'   => 'Both'
            ),
            'required' => array( $prefix . 'section-0-layout-mode', '=', 'slider' ),
          ),
          //                            array(
          //                                'title'    => 'Skip left icon',
          //                                'id'       => $prefix . 'navigator-skip-left',
          //                                'type'     => 'button_set',
          //                                'default'  => 'backward',
          //                                'options'  => array(
          //                                    'backward'      => 'Backward',
          //                                    'step-backward' => 'Step Backward',
          //                                ),
          //                                'required' => array(
          //                                    array($prefix . 'navigator', '=', 'thumbs'),
          //                                )
          //                            ),
          //                            array(
          //                                'title'    => 'Skip right icon',
          //                                'id'       => $prefix . 'navigator-skip-right',
          //                                'type'     => 'button_set',
          //                                'default'  => 'forward',
          //                                'options'  => array(
          //                                    'forward'      => 'Forward',
          //                                    'step-forward' => 'Step Forward',
          //                                ),
          //                                'required' => array(
          //                                    array($prefix . 'navigator', '=', 'thumbs'),
          //                                )
          //          ),
          array(
            'title'    => 'Navigation skip button',
            'id'       => $prefix . 'navigator-skip-button',
            'type'     => 'button_set',
            'default'  => 'circle',
            'options'  => array(
              'none'   => __( 'None', 'pzarchitect' ),
              'circle' => __( 'Circle', 'pzarchitect' ),
              'square' => __( 'Square', 'pzarchitect' ),
            ),
            'required' => array(
              array( $prefix . 'section-0-layout-mode', '=', 'slider' ),
              array( $prefix . 'navigator', '=', 'thumbs' ),
            )
          ),
          array(
            'id'       => $prefix . 'navigator-skip-thumbs',
            'title'    => __( 'Number of thumbs visible', 'pzarchitect' ),
            'type'     => 'spinner',
            'default'  => 5,
            'min'      => 1,
            'max'      => 100,
            'hint'     => array(
              'title'   => __( 'Number of thumbs visible', 'pzarchitect' ),
              'content' => __( 'Number of thumbs to fully show at once in the navigator. If Continuous is enabled, partial thumbs will additionally be shown left and right of the full thumbs.', 'pzarchitect' )
            ),
            'required' => array(
              array( $prefix . 'section-0-layout-mode', '=', 'slider' ),
              array( $prefix . 'navigator', '=', 'thumbs' ),
            )
          ),
          array(
            'title'    => 'Continuous thumbs',
            'id'       => $prefix . 'navigator-continuous',
            'type'     => 'button_set',
            'default'  => 'continuous',
            'options'  => array(
              'off'        => __( 'No', 'pzarchitect' ),
              'continuous' => __( 'Yes', 'pzarchitect' ),
            ),
            'required' => array(
              array( $prefix . 'section-0-layout-mode', '=', 'slider' ),
              array( $prefix . 'navigator', '=', 'thumbs' ),
            )
          ),
          array(
            'id'     => $prefix . 'section-navlayout-end',
            'type'   => 'section',
            'indent' => false,
          ),
          /** TRANSITIONS
           ******************/

          array(
            'title'    => __( 'Transitions timing', 'pzarchitect' ),
            'id'       => $prefix . 'section-transitions-heading',
            'type'     => 'section',
            'indent'   => true,
            'required' => array(
              array( $prefix . 'section-0-layout-mode', '!=', 'basic' ),
              array( $prefix . 'section-0-layout-mode', '!=', 'masonry' ),
              array( $prefix . 'section-0-layout-mode', '!=', 'tabular' ),
              array( $prefix . 'section-0-layout-mode', '!=', 'accordion' ),
            ),
          ),
          array(
            'title'         => 'Duration (seconds)',
            'id'            => $prefix . 'transitions-duration',
            'type'          => 'slider',
            'min'           => 0,
            'max'           => 5,
            'resolution'    => 0.1,
            'step'          => 0.5,
            'hint'          => array( 'content' => __( 'Time taken for the transition to display', 'pzarchitect' ) ),
            'default'       => 2,
            'display_value' => 'label',
            'required'      => array(
              array( $prefix . 'section-0-layout-mode', '!=', 'basic' ),
              array( $prefix . 'section-0-layout-mode', '!=', 'masonry' ),
              array( $prefix . 'section-0-layout-mode', '!=', 'tabular' ),
              array( $prefix . 'section-0-layout-mode', '!=', 'accordion' ),
            ),
          ),
          array(
            'title'         => 'Interval (seconds)',
            'id'            => $prefix . 'transitions-interval',
            'type'          => 'slider',
            'resolution'    => 0.1,
            'step'          => 0.5,
            'default'       => 0,
            'min'           => 0,
            'max'           => 60,
            'display_value' => 'label',
            'desc'          => __( 'Set to zero to disable autoplay', 'pzarchitect' ),
            'hint'          => array( 'content' => __( 'Time slide is shown with no transitions active. Set to zero to disable autoplay', 'pzarchitect' ) ),
            'required'      => array( $prefix . 'section-0-layout-mode', '=', 'slider' ),
          ),
        ),
      );

      $sections[ '_slidertabbed' ] = apply_filters( 'arc-extend-slider-settings', $sections[ '_slidertabbed' ] );

      /**
       * MASONRY
       */
      $sort_fields = apply_filters( 'arc_masonry_sorting', array_merge( array(
                                                                          'Standard' => array(
                                                                            'random'       => __( 'Random', 'pzarchitect' ),
                                                                            '.entry-title' => __( 'Title', 'pzarchitect' ),
                                                                            '[data-order]' => __( 'Date', 'pzarchitect' ),
                                                                            '.author'      => __( 'Author', 'pzarchitect' )
                                                                          )
                                                                        ),
                                                                        array( 'Custom Fields' => $this->custom_fields )
                                                         )
      );
//      global $pzarc_masonry_filter_taxes;
      if ( is_admin() && ! empty( $_GET[ 'post' ] ) && ! empty( $this->postmeta[ $prefix . 'masonry-filtering' ] ) ) {
        $pzarc_masonry_filter_taxes = apply_filters( '_arc_add_tax_titles', maybe_unserialize( $this->postmeta[ $prefix . 'masonry-filtering' ][ 0 ] ) );
      } else {
        $pzarc_masonry_filter_taxes = array();
      }
      $sections[ '_masonry' ] = array(
        'title'      => __( 'Masonry settings', 'pzarchitect' ),
        'icon_class' => 'icon-large',
        'icon'       => 'el-icon-adjust-alt',
        'fields'     => array(
          array(
            'title'   => __( 'Features', 'pzarchitect' ),
            'id'      => '_blueprints_masonry-features',
            'type'    => 'button_set',
            'multi'   => true,
            'options' => array(
              // Infinite scroll requires a method to load next set, so would would best leveraging off pagination -maybe... And that is a lot harder!
              // Waypoints provides infinite scroll support.
              //                      'infinite-scroll' => __('Infinite scroll', 'pzarchitect'),
              'filtering'     => __( 'Filtering', 'pzarchitect' ),
              'sorting'       => __( 'Sorting', 'pzarchitect' ),
              'bidirectional' => __( 'Packed masonry', 'pzarchitect' ),
            ),
            'default' => array(),
            'desc'    => __( '', 'pzarchitect' )
          ),
          // Infinite scroll options: Show progress
          // Sorting: Choose what on (title, date, cat, tag) and order
          array(
            'title'    => __( 'Sorting', 'pzarchitect' ),
            'id'       => $prefix . 'masonry-sorting-section-open',
            'type'     => 'section',
            'indent'   => true,
            'required' => array( $prefix . 'masonry-features', 'contains', 'sorting' ),
          ),
          array(
            'title'    => __( 'Sort fields', 'pzarchitect' ),
            'id'       => $prefix . 'masonry-sort-fields',
            'type'     => 'select',
            'multi'    => true,
            'default'  => array(),
            //                  'sortable' => true, //when enabled, it breaks coz $sort_Fields is a multilevel array
            'options'  => $sort_fields,
            'required' => array( $prefix . 'masonry-features', 'contains', 'sorting' ),
            'subtitle' => __( 'Some plugins, such as Woo Commerce, you need to use the field beginning with an underscore', 'pzarchtiect' ),
            'desc'     => __( 'It is up to YOU to ensure the sort fields are available in the Blueprint', 'pzarchitect' )
          ),
          array(
            'title'    => __( 'Numeric sort fields', 'pzarchitect' ),
            'id'       => $prefix . 'masonry-sort-fields-numeric',
            'type'     => 'select',
            'default'  => array(),
            'multi'    => true,
            'options'  => $this->custom_fields,
            'required' => array( $prefix . 'masonry-features', 'contains', 'sorting' ),
            'subtitle' => __( 'Select which of the above custom fields are numeric', 'pzarchitect' )
          ),
          array(
            'title'    => __( 'Date sort fields', 'pzarchitect' ),
            'id'       => $prefix . 'masonry-sort-fields-date',
            'type'     => 'select',
            'default'  => array(),
            'multi'    => true,
            'options'  => $this->custom_fields,
            'required' => array( $prefix . 'masonry-features', 'contains', 'sorting' ),
            'subtitle' => __( 'Select which of the above custom fields contain dates', 'pzarchitect' )
          ),
          array(
            'id'     => $prefix . 'masonry-sorting-section-close',
            'type'   => 'section',
            'indent' => false,
          ),
          //filtering
          array(
            'title'    => __( 'Filtering', 'pzarchitect' ),
            'id'       => $prefix . 'masonry-filtering-section-open',
            'type'     => 'section',
            'required' => array( $prefix . 'masonry-features', 'contains', 'filtering' ),
            'indent'   => true,
          ),
          array(
            'title'    => __( 'Taxonomies', 'pzarchitect' ),
            'id'       => $prefix . 'masonry-filtering',
            'type'     => 'select',
            'default'  => array(),
            'multi'    => true,
            'sortable' => true,
            'data'     => 'callback',
            'args'     => array( 'pzarc_get_taxonomies_ctb' ),
            'required' => array( $prefix . 'masonry-features', 'contains', 'filtering' ),
            'desc'     => __( 'It is up to YOU to ensure the taxonomies match the content', 'pzarchitect' ),
            'subtitle' => __( 'Note: You will have to publish/update to show the Taxonomy filters below', 'pzarchitect' ),
          ),
          array(
            'title'    => __( 'Allow multiple filter terms', 'pzarchitect' ),
            'id'       => '_blueprints_masonry-filtering-allow-multiple',
            'type'     => 'button_set',
            'default'  => 'multiple',
            'options'  => array(
              'multiple' => __( 'Yes', 'pzarchitect' ),
              'single'   => __( 'No', 'pzarchitect' )
            ),
            'required' => array( $prefix . 'masonry-features', 'contains', 'filtering' ),
            'subtitle' => __( 'Allow multiple filters to be selected by site users. Note: Multiple selected filters narrow the selection shown. Therefore, ensure content is well tagged for best results.', 'pzarchitect' )
          ),
          //bidirection
          array(
            'title'    => __( 'Packed masonry', 'pzarchitect' ),
            'id'       => $prefix . 'masonry-full-section-open',
            'type'     => 'section',
            'required' => array( $prefix . 'masonry-features', 'contains', 'bidirectional' ),
            'indent'   => true,
            'subtitle' => __( 'In Packed Masonry mode, Panels Across and Fixed Width Panels are ignored. The width and height of each panel is determined by its the image\'s dimensions. Therefore, it is important you choose the Shrink to fit width and height limit option for Image Cropping to get the effect. Packed Masonry has no settings.', 'pzarchitect' )
          ),
        )
      );

      foreach ( $pzarc_masonry_filter_taxes as $pzarc_tax ) {
        $sections[ '_masonry' ][ 'fields' ][] = array(
          'title'    => __( 'Filter on ', 'pzarchitect' ) . ucwords( str_replace( array(
                                                                                    '_',
                                                                                    '-'
                                                                                  ), ' ', $pzarc_tax ) ),
          'id'       => $prefix . 'masonry-filtering-section-open-' . $pzarc_tax,
          'type'     => 'section',
          'required' => array( $prefix . 'masonry-features', 'contains', 'filtering' ),
          'indent'   => true,
        );
        $sections[ '_masonry' ][ 'fields' ][] = array(
          'title'    => __( 'Limit ', 'pzarchitect' ),
          'id'       => '_blueprints_masonry-filtering-limit-' . $pzarc_tax,
          'type'     => 'button_set',
          'default'  => 'none',
          'options'  => array(
            'none'    => __( 'None', 'pzarchitect' ),
            'include' => __( 'Include', 'pzarchitect' ),
            'exclude' => __( 'Exclude', 'pzarchitect' )
          ),
          'required' => array( $prefix . 'masonry-features', 'contains', 'filtering' ),
          'subtitle' => __( 'Control what terms are shown.', 'pzarchitect' ),
        );
        $sections[ '_masonry' ][ 'fields' ][] = array(
          'title'    => __( 'Inclusions/Exclusions', 'pzarchitect' ),
          'id'       => '_blueprints_masonry-filtering-incexc-' . $pzarc_tax,
          'type'     => 'select',
          'multi'    => true,
          'data'     => 'terms',
          'default'  => '',
          'required' => array( $prefix . 'masonry-filtering-limit-' . $pzarc_tax, '!=', 'none' ),
          'args'     => array( 'taxonomies' => $pzarc_tax, 'hide_empty' => false )
        );
        $sections[ '_masonry' ][ 'fields' ][] = $sections[ '_masonry' ][ 'fields' ][] = array(
          'id'     => $prefix . 'masonry-filtering-section-close-' . $pzarc_tax,
          'type'   => 'section',
          'indent' => false,
        );

      }
      $sections[ '_masonry' ][ 'fields' ][] = array(
        'id'     => $prefix . 'masonry-filtering-section-close',
        'type'   => 'section',
        'indent' => false,
      );

      $sections[ '_masonry' ] = apply_filters( 'arc-extend-masonry-settings', $sections[ '_masonry' ] );

      /** PAGINATION  */
      $sections[ '_pagination' ] = array(
        'title'      => 'Pagination',
        'icon_class' => 'icon-large',
        'icon'       => 'el-icon-chevron-right',
        'fields'     => array(
          array(
            'title'   => 'Pagination',
            'id'      => '_blueprints_pagination',
            'type'    => 'switch',
            'on'      => 'Yes',
            'off'     => 'No',
            'default' => false,
            'desc'    => __( 'If this Blueprint is displaying blog posts on the blog page (thus Defaults is the Content Source) and you choose to enable overrides, pagination will likely mess up.', 'pzarchitect' )
          ),
          array(
            'title'    => __( 'Settings', 'pzarchitect' ),
            'id'       => '_blueprint_pagination-settings-section',
            'type'     => 'section',
            'indent'   => true,
            'required' => array( '_blueprints_pagination', 'equals', true ),
          ),
          array(
            'title'    => __( 'Posts per page', 'pzarchitect' ),
            'id'       => '_blueprints_pagination-per-page',
            'type'     => 'spinner',
            'default'  => get_option( 'posts_per_page' ),
            'min'      => 1,
            'max'      => 99,
            'required' => array( '_blueprints_pagination', 'equals', true ),
            'desc'     => __( 'If this Blueprint is displaying blog posts on the blog page (thus Defaults is the Content Source), change WP <em>Settings > Reading > Blog pages show at most</em> to control posts per page', 'pzarchitect' )
          ),
          array(
            'id'      => '_blueprints_pager-location',
            'title'   => __( 'Pagination location', 'pzarchitect' ),
            'type'    => 'select',
            'select2' => array( 'allowClear' => false ),
            'default' => 'bottom',
            'options' => array(
              'bottom' => 'Bottom',
              'top'    => 'Top',
              'both'   => 'Both'
            ),
          ),
          array(
            'id'      => '_blueprints_pager',
            'title'   => __( 'Blog Pagination', 'pzarchitect' ),
            'type'    => 'select',
            'select2' => array( 'allowClear' => false ),
            'default' => 'prevnext',
            'options' => array(
              //                    'none'     => 'None',
              'prevnext' => 'Previous/Next',
              'names'    => 'Post names',
              'pagenavi' => 'PageNavi',
            ),
          ),
          array(
            'id'      => '_blueprints_pager-single',
            'title'   => __( 'Single Post Pagination', 'pzarchitect' ),
            'type'    => 'select',
            'select2' => array( 'allowClear' => false ),
            'default' => 'prevnext',
            'options' => array(
              //                    'none'     => 'None',
              'prevnext' => 'Previous/Next',
              'names'    => 'Post names',
              'pagenavi' => 'PageNavi',
            ),
          ),
          array(
            'id'      => '_blueprints_pager-archives',
            'title'   => __( 'Archives Pagination', 'pzarchitect' ),
            'type'    => 'select',
            'select2' => array( 'allowClear' => false ),
            'default' => 'prevnext',
            'options' => array(
              //                    'none'     => 'None',
              'prevnext' => 'Previous/Next',
              'names'    => 'Post names',
              'pagenavi' => 'PageNavi',
            ),
          ),
          array(
            'id'      => '_blueprints_pager-custom-prev',
            'title'   => __( 'Custom text for Previous', 'pzarchitect' ),
            'type'    => 'text',
            'default' => '',
          ),
          array(
            'id'      => '_blueprints_pager-custom-next',
            'title'   => __( 'Custom text for Next', 'pzarchitect' ),
            'type'    => 'text',
            'default' => '',
          ),
          array(
            'id'       => '_blueprint_pagination-settings-section-end',
            'type'     => 'section',
            'indent'   => false,
            'required' => array( '_blueprints_pagination', 'equals', true ),
          ),
        )
      );

      //  Styling
      if ( ! empty( $_architect_options[ 'architect_enable_styling' ] ) ) {
        $defaults = get_option( '_architect' );
        $prefix   = '_blueprints_styling_'; // declare prefix
// TODO: need to get styling to take
        $font         = '-font';
        $link         = '-links';
        $padding      = '-padding';
        $margin       = '-margins';
        $background   = '-background';
        $border       = '-borders';
        $borderradius = '-borderradius';

        $stylingSections                     = array();
        $optprefix                           = 'architect_config_';
        $thisSection                         = 'blueprint';
        $thisSection                         = 'navigator';
        $sections[ '_styling_slidertabbed' ] = array(
          'title'      => 'Sliders & Tabbed styling',
          'show_title' => false,
          'icon_class' => 'icon-large',
          'icon'       => 'el-icon-brush',
          'fields'     => array(

            array(
              'title'    => __( 'Navigator container', 'pzarchitect' ),
              'id'       => $prefix . 'blueprint-nav-container-css-heading',
              'type'     => 'section',
              'indent'   => true,
              'subtitle' => 'Classes: ' . implode( ', ', array( '.arc-slider-nav', '.pzarc-navigator' ) ),

            ),
            pzarc_redux_bg( $prefix . $thisSection . $background, $_architect[ 'architect_config_' . $thisSection . '-selectors' ], $defaults[ $optprefix . $thisSection . $background ] ),
            pzarc_redux_padding( $prefix . $thisSection . $padding, $_architect[ 'architect_config_' . $thisSection . '-selectors' ], $defaults[ $optprefix . $thisSection . $padding ] ),
            pzarc_redux_margin( $prefix . $thisSection . $margin, $_architect[ 'architect_config_' . $thisSection . '-selectors' ], $defaults[ $optprefix . $thisSection . $margin ] ),
            pzarc_redux_borders( $prefix . $thisSection . $border, $_architect[ 'architect_config_' . $thisSection . '-selectors' ], $defaults[ $optprefix . $thisSection . $border ] ),
            array(
              'title'    => __( 'Navigator items', 'pzarchitect' ),
              'id'       => $prefix . 'blueprint-nav-items-css-heading',
              'type'     => 'section',
              'indent'   => true,
              'subtitle' => 'Class: ' . implode( ', ', array( '.pzarc-navigator .arc-slider-slide-nav-item' ) ),

            ),
            pzarc_redux_font( $prefix . $thisSection . '-items' . $font, $_architect[ 'architect_config_' . $thisSection . '-selectors' ], $defaults[ $optprefix . $thisSection . '-items' . $font ] ),
            pzarc_redux_bg( $prefix . $thisSection . '-items' . $background, $_architect[ 'architect_config_' . $thisSection . '-selectors' ], $defaults[ $optprefix . $thisSection . '-items' . $background ] ),
            pzarc_redux_padding( $prefix . $thisSection . '-items' . $padding, $_architect[ 'architect_config_' . $thisSection . '-selectors' ], $defaults[ $optprefix . $thisSection . '-items' . $padding ] ),
            pzarc_redux_margin( $prefix . $thisSection . '-items' . $margin, $_architect[ 'architect_config_' . $thisSection . '-selectors' ], $defaults[ $optprefix . $thisSection . '-items' . $margin ] ),
            pzarc_redux_borders( $prefix . $thisSection . '-items' . $border, $_architect[ 'architect_config_' . $thisSection . '-selectors' ], $defaults[ $optprefix . $thisSection . '-items' . $border ] ),
            pzarc_redux_border_radius( $prefix . $thisSection . '-items' . $borderradius, $_architect[ 'architect_config_' . $thisSection . '-selectors' ], $defaults[ $optprefix . $thisSection . '-items' . $borderradius ] ),
            array(
              'title'    => __( 'Navigator item hover', 'pzarchitect' ),
              'id'       => $prefix . 'blueprint-nav-hover-item-css-heading',
              'type'     => 'section',
              'indent'   => true,
              'subtitle' => 'Class: ' . implode( ', ', array( '.pzarc-navigator .arc-slider-slide-nav-item:hover' ) ),

            ),
            pzarc_redux_font( $prefix . $thisSection . '-items-hover' . $font, $_architect[ 'architect_config_' . $thisSection . '-selectors' ], $defaults[ $optprefix . $thisSection . '-items-hover' . $font ], array(
              'letter-spacing',
              'font-variant',
              'text-transform',
              'font-family',
              'font-style',
              'text-align',
              'line-height',
              'word-spacing'
            ) ),
            pzarc_redux_bg( $prefix . $thisSection . '-items-hover' . $background, $_architect[ 'architect_config_' . $thisSection . '-selectors' ], $defaults[ $optprefix . $thisSection . '-items-hover' . $background ] ),
            pzarc_redux_borders( $prefix . $thisSection . '-items-hover' . $border, $_architect[ 'architect_config_' . $thisSection . '-selectors' ], $defaults[ $optprefix . $thisSection . '-items-hover' . $border ] ),
            array(
              'title'    => __( 'Navigator active item', 'pzarchitect' ),
              'id'       => $prefix . 'blueprint-nav-active-item-css-heading',
              'type'     => 'section',
              'indent'   => true,
              'subtitle' => 'Class: ' . implode( ', ', array(
                  '.pzarc-navigator .arc-slider-slide-nav-item.active',
                  '.pzarc-navigator .arc-slider-slide-nav-item.current'
                ) ),

            ),
            pzarc_redux_font( $prefix . $thisSection . '-items-active' . $font, $_architect[ 'architect_config_' . $thisSection . '-selectors' ], $defaults[ $optprefix . $thisSection . '-items-active' . $font ], array(
              'letter-spacing',
              'font-variant',
              'text-transform',
              'font-family',
              'font-style',
              'text-align',
              'line-height',
              'word-spacing'
            ) ),
            pzarc_redux_bg( $prefix . $thisSection . '-items-active' . $background, $_architect[ 'architect_config_' . $thisSection . '-selectors' ], $defaults[ $optprefix . $thisSection . '-items-active' . $background ] ),
            pzarc_redux_borders( $prefix . $thisSection . '-items-active' . $border, $_architect[ 'architect_config_' . $thisSection . '-selectors' ], $defaults[ $optprefix . $thisSection . '-items-active' . $border ] )
          ),
        );

        $thisSection                    = 'masonry';
        $sections[ '_styling_masonry' ] = array(
          'id'         => 'masonry-css',
          'title'      => 'Masonry styling',
          'icon_class' => 'icon-large',
          'icon'       => 'el-icon-brush',
          'fields'     => array(
            array(
              'title'    => __( 'Filtering and sorting section', 'pzarchitect' ),
              'id'       => $prefix . 'blueprint-masonry-css-heading',
              'type'     => 'section',
              'indent'   => true,
              'subtitle' => 'Class: ' . $_architect[ 'architect_config_' . $thisSection . '-selectors' ]

            ),
            pzarc_redux_font( $prefix . $thisSection . $font, $this->defaults ? '' : $_architect[ 'architect_config_' . $thisSection . '-selectors' ], $defaults[ $optprefix . $thisSection . $font ] ),
            pzarc_redux_bg( $prefix . $thisSection . $background, $this->defaults ? '' : $_architect[ 'architect_config_' . $thisSection . '-selectors' ], $defaults[ $optprefix . $thisSection . $background ] ),
            pzarc_redux_padding( $prefix . $thisSection . $padding, $this->defaults ? '' : $_architect[ 'architect_config_' . $thisSection . '-selectors' ], $defaults[ $optprefix . $thisSection . $padding ] ),
            pzarc_redux_margin( $prefix . $thisSection . $margin, $this->defaults ? '' : $_architect[ 'architect_config_' . $thisSection . '-selectors' ], $defaults[ $optprefix . $thisSection . $margin ] ),
            pzarc_redux_borders( $prefix . $thisSection . $border, $this->defaults ? '' : $_architect[ 'architect_config_' . $thisSection . '-selectors' ], $defaults[ $optprefix . $thisSection . $border ] ),
            array(
              'title'    => __( 'Buttons', 'pzarchitect' ),
              'id'       => $prefix . 'blueprint-masonry-buttons-css-heading',
              'type'     => 'section',
              'indent'   => true,
              'subtitle' => 'Class: ' . $_architect[ 'architect_config_' . $thisSection . '-buttons-selectors' ]

            ),
            pzarc_redux_font( $prefix . $thisSection . '-buttons' . $font, $this->defaults ? '' : $_architect[ 'architect_config_' . $thisSection . '-buttons-selectors' ], $defaults[ $optprefix . $thisSection . '-buttons' . $font ] ),
            pzarc_redux_bg( $prefix . $thisSection . '-buttons' . $background, $this->defaults ? '' : $_architect[ 'architect_config_' . $thisSection . '-buttons-selectors' ], $defaults[ $optprefix . $thisSection . '-buttons' . $background ] ),
            pzarc_redux_padding( $prefix . $thisSection . '-buttons' . $padding, $this->defaults ? '' : $_architect[ 'architect_config_' . $thisSection . '-buttons-selectors' ], $defaults[ $optprefix . $thisSection . '-buttons' . $padding ] ),
            pzarc_redux_margin( $prefix . $thisSection . '-buttons' . $margin, $this->defaults ? '' : $_architect[ 'architect_config_' . $thisSection . '-buttons-selectors' ], $defaults[ $optprefix . $thisSection . '-buttons' . $margin ] ),
            pzarc_redux_borders( $prefix . $thisSection . '-buttons' . $border, $this->defaults ? '' : $_architect[ 'architect_config_' . $thisSection . '-buttons-selectors' ], $defaults[ $optprefix . $thisSection . '-buttons' . $border ] ),
            array(
              'title'    => __( 'Selected', 'pzarchitect' ),
              'id'       => $prefix . 'blueprint-masonry-selected-css-heading',
              'type'     => 'section',
              'indent'   => true,
              'subtitle' => 'Class: ' . $_architect[ 'architect_config_' . $thisSection . '-selected-selectors' ]
            ),
            pzarc_redux_font( $prefix . $thisSection . '-selected' . $font, $this->defaults ? '' : $_architect[ 'architect_config_' . $thisSection . '-selected-selectors' ], $defaults[ $optprefix . $thisSection . '-selected' . $font ] ),
            pzarc_redux_bg( $prefix . $thisSection . '-selected' . $background, $this->defaults ? '' : $_architect[ 'architect_config_' . $thisSection . '-selected-selectors' ], $defaults[ $optprefix . $thisSection . '-selected' . $background ] ),
            pzarc_redux_borders( $prefix . $thisSection . '-selected' . $border, $this->defaults ? '' : $_architect[ 'architect_config_' . $thisSection . '-selected-selectors' ], $defaults[ $optprefix . $thisSection . '-selected' . $border ] ),
            array(
              'title'    => __( 'Hover', 'pzarchitect' ),
              'id'       => $prefix . 'blueprint-hover-css-heading',
              'type'     => 'section',
              'indent'   => true,
              'subtitle' => 'Class: ' . $_architect[ 'architect_config_' . $thisSection . '-hover-selectors' ]
            ),
            pzarc_redux_font( $prefix . $thisSection . '-hover' . $font, $this->defaults ? '' : $_architect[ 'architect_config_' . $thisSection . '-clear-selectors' ], $defaults[ $optprefix . $thisSection . '-hover' . $font ] ),
            pzarc_redux_bg( $prefix . $thisSection . '-hover' . $background, $this->defaults ? '' : $_architect[ 'architect_config_' . $thisSection . '-clear-selectors' ], $defaults[ $optprefix . $thisSection . '-hover' . $background ] ),
            pzarc_redux_borders( $prefix . $thisSection . '-hover' . $border, $this->defaults ? '' : $_architect[ 'architect_config_' . $thisSection . '-clear-selectors' ], $defaults[ $optprefix . $thisSection . '-hover' . $border ] ),
            array(
              'title'    => __( 'Clear button', 'pzarchitect' ),
              'id'       => $prefix . 'blueprint-clear-css-heading',
              'type'     => 'section',
              'indent'   => true,
              'subtitle' => 'Class: ' . $_architect[ 'architect_config_' . $thisSection . '-clear-selectors' ]
            ),
            pzarc_redux_font( $prefix . $thisSection . '-clear' . $font, $this->defaults ? '' : $_architect[ 'architect_config_' . $thisSection . '-clear-selectors' ], $defaults[ $optprefix . $thisSection . '-clear' . $font ] ),
            pzarc_redux_bg( $prefix . $thisSection . '-clear' . $background, $this->defaults ? '' : $_architect[ 'architect_config_' . $thisSection . '-clear-selectors' ], $defaults[ $optprefix . $thisSection . '-clear' . $background ] ),
            pzarc_redux_borders( $prefix . $thisSection . '-clear' . $border, $this->defaults ? '' : $_architect[ 'architect_config_' . $thisSection . '-clear-selectors' ], $defaults[ $optprefix . $thisSection . '-clear' . $border ] ),
          ),
        );

        $thisSection                      = 'accordion-titles';
        $sections[ '_styling_accordion' ] = array(
          'id'         => 'accordion-css',
          'title'      => 'Accordion styling',
          'icon_class' => 'icon-large',
          'icon'       => 'el-icon-brush',
          'fields'     => array(
            array(
              'title'    => __( 'Titles', 'pzarchitect' ),
              'id'       => $prefix . 'blueprint-accordion-css-heading',
              'type'     => 'section',
              'indent'   => true,
              'subtitle' => 'Class: ' . $_architect[ 'architect_config_' . $thisSection . '-selectors' ]

            ),
            pzarc_redux_font( $prefix . $thisSection . $font, $this->defaults ? '' : $_architect[ 'architect_config_' . $thisSection . '-selectors' ], $defaults[ $optprefix . $thisSection . $font ] ),
            pzarc_redux_bg( $prefix . $thisSection . $background, $this->defaults ? '' : $_architect[ 'architect_config_' . $thisSection . '-selectors' ], $defaults[ $optprefix . $thisSection . $background ] ),
            pzarc_redux_padding( $prefix . $thisSection . $padding, $this->defaults ? '' : $_architect[ 'architect_config_' . $thisSection . '-selectors' ], $defaults[ $optprefix . $thisSection . $padding ] ),
            pzarc_redux_margin( $prefix . $thisSection . $margin, $this->defaults ? '' : $_architect[ 'architect_config_' . $thisSection . '-selectors' ], $defaults[ $optprefix . $thisSection . $margin ] ),
            pzarc_redux_borders( $prefix . $thisSection . $border, $this->defaults ? '' : $_architect[ 'architect_config_' . $thisSection . '-selectors' ], $defaults[ $optprefix . $thisSection . $border ] ),
            array(
              'title'    => __( 'Open', 'pzarchitect' ),
              'id'       => $prefix . 'blueprint-accordion-open-css-heading',
              'type'     => 'section',
              'indent'   => true,
              'subtitle' => 'Class: ' . $_architect[ 'architect_config_' . $thisSection . '-open-selectors' ]
            ),
            pzarc_redux_font( $prefix . $thisSection . '-open' . $font, $this->defaults ? '' : $_architect[ 'architect_config_' . $thisSection . '-open-selectors' ], $defaults[ $optprefix . $thisSection . '-open' . $font ] ),
            pzarc_redux_bg( $prefix . $thisSection . '-open' . $background, $this->defaults ? '' : $_architect[ 'architect_config_' . $thisSection . '-open-selectors' ], $defaults[ $optprefix . $thisSection . '-open' . $background ] ),
            pzarc_redux_borders( $prefix . $thisSection . '-open' . $border, $this->defaults ? '' : $_architect[ 'architect_config_' . $thisSection . '-open-selectors' ], $defaults[ $optprefix . $thisSection . '-open' . $border ] ),
            array(
              'title'    => __( 'Hover', 'pzarchitect' ),
              'id'       => $prefix . 'blueprint-accordion-hover-css-heading',
              'type'     => 'section',
              'indent'   => true,
              'subtitle' => 'Class: ' . $_architect[ 'architect_config_' . $thisSection . '-hover-selectors' ]
            ),
            pzarc_redux_font( $prefix . $thisSection . '-hover' . $font, $this->defaults ? '' : $_architect[ 'architect_config_' . $thisSection . '-hover-selectors' ], $defaults[ $optprefix . $thisSection . '-hover' . $font ] ),
            pzarc_redux_bg( $prefix . $thisSection . '-hover' . $background, $this->defaults ? '' : $_architect[ 'architect_config_' . $thisSection . '-hover-selectors' ], $defaults[ $optprefix . $thisSection . '-hover' . $background ] ),
            pzarc_redux_borders( $prefix . $thisSection . '-hover' . $border, $this->defaults ? '' : $_architect[ 'architect_config_' . $thisSection . '-hover-selectors' ], $defaults[ $optprefix . $thisSection . '-hover' . $border ] ),
          ),
        );
        $thisSection                      = 'tabular';
        $sections[ '_styling_tabular' ]   = array(
          'id'         => 'tabular-css',
          'title'      => 'Tabular styling',
          'icon_class' => 'icon-large',
          'icon'       => 'el-icon-brush',
          'fields'     => array(
            array(
              'title'    => __( 'Headings', 'pzarchitect' ),
              'id'       => $prefix . 'blueprint-tabular-css-heading',
              'type'     => 'section',
              'subtitle' => 'Class: ' . $this->defaults ? '' : $_architect[ 'architect_config_' . $thisSection . '-selectors' ],
              'indent'   => true,

            ),
            pzarc_redux_font( $prefix . $thisSection . '-headings' . $font, $this->defaults ? '' : $_architect[ 'architect_config_' . $thisSection . '-headings-selectors' ], $defaults[ $optprefix . $thisSection . '-headings' . $font ] ),
            pzarc_redux_bg( $prefix . $thisSection . '-headings' . $background, $this->defaults ? '' : $_architect[ 'architect_config_' . $thisSection . '-headings-selectors' ], $defaults[ $optprefix . $thisSection . '-headings' . $background ] ),
            pzarc_redux_padding( $prefix . $thisSection . '-headings' . $padding, $this->defaults ? '' : $_architect[ 'architect_config_' . $thisSection . '-headings-selectors' ], $defaults[ $optprefix . $thisSection . '-headings' . $padding ] ),
            pzarc_redux_margin( $prefix . $thisSection . '-headings' . $margin, $this->defaults ? '' : $_architect[ 'architect_config_' . $thisSection . '-headings-selectors' ], $defaults[ $optprefix . $thisSection . '-headings' . $margin ] ),
            pzarc_redux_borders( $prefix . $thisSection . '-headings' . $border, $this->defaults ? '' : $_architect[ 'architect_config_' . $thisSection . '-headings-selectors' ], $defaults[ $optprefix . $thisSection . '-headings' . $border ] ),
            array(
              'title'    => __( 'Odd rows', 'pzarchitect' ),
              'id'       => $prefix . 'blueprint-tabular-odd-rows-css-heading',
              'type'     => 'section',
              'subtitle' => 'Class: ' . $this->defaults ? '' : $_architect[ 'architect_config_' . $thisSection . '-odd-rows-selectors' ],
              'indent'   => true,

            ),
            pzarc_redux_font( $prefix . $thisSection . '-odd-rows' . $font, $this->defaults ? '' : $_architect[ 'architect_config_' . $thisSection . '-odd-rows-selectors' ], $defaults[ $optprefix . $thisSection . '-odd-rows' . $font ] ),
            pzarc_redux_bg( $prefix . $thisSection . '-odd-rows' . $background, $this->defaults ? '' : $_architect[ 'architect_config_' . $thisSection . '-odd-rows-selectors' ], $defaults[ $optprefix . $thisSection . '-odd-rows' . $background ] ),
            pzarc_redux_borders( $prefix . $thisSection . '-odd-rows' . $border, $this->defaults ? '' : $_architect[ 'architect_config_' . $thisSection . '-odd-rows-selectors' ], $defaults[ $optprefix . $thisSection . '-odd-rows' . $border ] ),
            array(
              'title'    => __( 'Even rows', 'pzarchitect' ),
              'id'       => $prefix . 'blueprint-tabular-even-rows-css-heading',
              'type'     => 'section',
              'subtitle' => 'Class: ' . $this->defaults ? '' : $_architect[ 'architect_config_' . $thisSection . '-even-rows-selectors' ],
              'indent'   => true,

            ),
            pzarc_redux_font( $prefix . $thisSection . '-even-rows' . $font, $this->defaults ? '' : $_architect[ 'architect_config_' . $thisSection . '-even-rows-selectors' ], $defaults[ $optprefix . $thisSection . '-even-rows' . $font ] ),
            pzarc_redux_bg( $prefix . $thisSection . '-even-rows' . $background, $this->defaults ? '' : $_architect[ 'architect_config_' . $thisSection . '-even-rows-selectors' ], $defaults[ $optprefix . $thisSection . '-even-rows' . $background ] ),
            pzarc_redux_borders( $prefix . $thisSection . '-even-rows' . $border, $this->defaults ? '' : $_architect[ 'architect_config_' . $thisSection . '-even-rows-selectors' ], $defaults[ $optprefix . $thisSection . '-even-rows' . $border ] ),
          )
        );
      }
      $metaboxes[] = array(
        'id'         => 'type-settings',
        'title'      => 'Additional settings for chosen Blueprint layout',
        'post_types' => array( 'arc-blueprints' ),
        'sections'   => $sections,
        'position'   => 'normal',
        'priority'   => 'low',
        'sidebar'    => false

      );

      return $metaboxes;

    }


    /**
     *
     * CONTENT
     *
     * @param array $metaboxes
     *
     * @return array
     */
    function pzarc_mb_blueprint_content_selection( $metaboxes, $defaults_only = false ) {

      // TODO: Setup a loop that reads the object containing content type info as appened by the content type classes. Will need a means of letting js know tho.
      $prefix   = '_content_general_'; // declare prefix
      $sections = array();
      /** DISPLAY ALL THE CONTENT TYPES FORMS */
      $registry           = arc_Registry::getInstance();
      $content_types      = array();
      $content_post_types = (array) $registry->get( 'post_types' );
      foreach ( $content_post_types as $key => $value ) {
        if ( isset( $value[ 'blueprint-content' ] ) ) {
          $content_types[ $value[ 'blueprint-content' ][ 'type' ] ] = $value[ 'blueprint-content' ][ 'name' ];
        }
      }
      /** GENERAL  Settings*/
      $blueprint_content_common = $registry->get( 'blueprint-content-common' );

      // If you add/remove a content type, you have to add/remove it's side tab too
      $prefix                 = '_content_general_'; // declare prefix
      $sections[ '_general' ] = array(
        'title'      => 'Source',
        'icon_class' => 'icon-large',
        'icon'       => 'el-icon-file',
        'fields'     => array(
          array(
            'title'    => __( 'Content source', 'pzarchitect' ),
            'id'       => '_blueprints_content-source',
            'type'     => 'button_set',
            //                  'select2'  => array('allowClear' => false),
            'default'  => 'defaults',
            'options'  => $content_types,
            'desc'     => __( 'If you want a Blueprint to display the content of the specific post/page/cpt it is to be displayed on, use the Default content source.<br><strong>Select <em>Defaults</em> if this Blueprint will display blog posts on the blog page</strong>', 'pzarchitect' ),
            'subtitle' => ( array_key_exists( 'snippets', $content_types ) ? '' : 'Several more content sources supported in Architect Pro version, including Pages, Snippets, Galleries, Custom Post Types and a special Dummy option to display dummy content' )
          ),
        )
      );


      /** DISPLAY ALL THE CONTENT TYPES FORMS */
      foreach ( $content_post_types as $key => $value ) {
        if ( isset( $value[ 'blueprint-content' ] ) ) {
          foreach ( $value[ 'blueprint-content' ][ 'sections' ][ 'fields' ] as $k => $v ) {
            $v[ 'required' ][]                    = array(
              '_blueprints_content-source',
              'equals',
              $value[ 'blueprint-content' ][ 'type' ]
            );
            $sections[ '_general' ][ 'fields' ][] = $v;
          }
        }
      }
      /** FILTERS */
      $sections[ '_settings' ] = $blueprint_content_common[ 0 ][ 'settings' ][ 'sections' ];
      $sections[ '_filters' ]  = $blueprint_content_common[ 0 ][ 'filters' ][ 'sections' ];

      $sections[ '_settings' ][ 'fields' ][] =
        array(
          'title'    => __( 'Custom field sort', 'pzarchitect' ),
          'id'       => $prefix . 'sort-section',
          'type'     => 'section',
          'indent'   => true,
          'required' => array( '_content_general_orderby', 'equals', 'custom' )
        );
      $sections[ '_settings' ][ 'fields' ][] =
        array(
          'type'    => 'select',
          'title'   => 'Custom sort field',
          'default' => '',
          'options' => $this->custom_fields,
          'id'      => $prefix . 'custom-sort-key',
        );
      $sections[ '_settings' ][ 'fields' ][] =
        array(
          'type'    => 'select',
          'title'   => 'Custom field type',
          'default' => 'CHAR',
          'options' => array(
            'NUMERIC'     => 'Number',
            'BINARY'      => 'True/false',
            'CHAR'        => 'Text',
            'NUMERICDATE' => 'Numerical date',
            'DATE'        => 'Date',
            'DATETIME'    => 'Date time',
            'TIME'        => 'Time',
          ),
          'id'      => $prefix . 'custom-sort-key-type',
          'desc'    => __( 'Most plugins, such as WooCommerce, will store dates numerically, even though it will appear as a normal date.', 'pzarchitect' )
        );

      // Custom fields filtering and sorting
      $prefix = '_content_customfields_'; // declare prefix


      $sections[ '_content_customfields' ] = array(
        'title'      => 'Custom field filtering',
        'icon_class' => 'icon-large',
        'icon'       => 'el-icon-wrench',
        'fields'     => array(
          array(
            'title' => 'Developer information',
            'id'    => '_blueprints_content-fields-filter-info',
            'type'  => 'info',
            'desc'  => 'A WP install can have dozens and dozens of custom fields, so it\'s up to you to know the custom field name you require.<br><strong style="color:tomato;">It\'s up to you to ensure the chosen field is available to the post type being displayed</strong>.<br> See <a href="https://codex.wordpress.org/Class_Reference/WP_Query#Custom_Field_Parameters" target=_blank>WordPress Codex, Class Reference WP_Query, Custom Field Parameters</a> for detailed usage information. <br>Note: You can do not need to set all three fields. You may use just one, two or all three.',
          ),
          array(
            'title'   => __( 'Filter match requirement', 'pzarchitect' ),
            'id'      => '_blueprints_content-fields-filter-jointype',
            'type'    => 'button_set',
            'default' => 'AND',
            'options' => array( 'AND' => 'Must match ALL', 'OR' => 'May match ANY' ),
            'desc'    => __( 'Each filter is optional. Leave the field empty to ignore it.', 'pzarchitect' )
          )
        )
      );
      for ( $cfi = 1; $cfi <= 3; $cfi ++ ) {
        $sections[ '_content_customfields' ][ 'fields' ][] =
          array(
            'title'  => __( 'Filter field ' . $cfi, 'pzarchitect' ),
            'id'     => '_blueprints_content-fields-filter-section' . $cfi,
            'type'   => 'section',
            'indent' => true,
          );
        $sections[ '_content_customfields' ][ 'fields' ][] =
          array(
            'type'     => 'select',
            'title'    => 'Field',
            'subtitle' => __( 'For some plugins, like WooCommerce, the field you need to use is the one beginning with an underscore.', 'pzarchitect' ),
            'default'  => '',
            //            'options'  => array_merge( array( 'title' => 'TITLE', 'date' => 'DATE' ), $this->custom_fields ),
            'options'  => $this->custom_fields,
            'id'       => '_blueprints_content-fields-filter-key' . $cfi,
          );
        $sections[ '_content_customfields' ][ 'fields' ][] =

          array(
            'type'     => 'select',
            'title'    => 'Field type',
            'subtitle' => 'You need to know how the data is stored. For example, the Types plugin stores dates in the numeric Unix timestamp format. Therefore, you would select Numeric here, and Timestamp for the field value format.',
            'default'  => 'CHAR',
            'required' => array( '_blueprints_content-fields-filter-key' . $cfi, '!=', '' ),
            'options'  => array(
              'NUMERIC'  => 'NUMERIC',
              'BINARY'   => 'BINARY',
              'CHAR'     => 'CHAR',
              'DATE'     => 'DATE',
              'DATETIME' => 'DATETIME',
              'DECIMAL'  => 'DECIMAL',
              'SIGNED'   => 'SIGNED',
              'TIME'     => 'TIME',
              'UNSIGNED' => 'UNSIGNED'
            ),
            'id'       => '_blueprints_content-fields-filter-type' . $cfi,
          );
        $sections[ '_content_customfields' ][ 'fields' ][] =

          array(
            'type'     => 'text',
            'title'    => 'Filter value',
            'default'  => '',
            'id'       => '_blueprints_content-fields-filter-value' . $cfi,
            'required' => array( '_blueprints_content-fields-filter-key' . $cfi, '!=', '' ),
          );
        $sections[ '_content_customfields' ][ 'fields' ][] =

          array(
            'type'     => 'select',
            'title'    => 'Filter value type',
            'subtitle' => 'Set this to ensure correct matching with the field data type. E.g. For Types the plugin date fields set this to Timestamp, which will convert your Filter value to a timestamp value.',
            'default'  => 'string',
            'required' => array( '_blueprints_content-fields-filter-key' . $cfi, '!=', '' ),
            'options'  => array(
              'numeric'   => 'Numeric',
              'binary'    => 'True/False',
              'string'    => 'String',
              'date'      => 'Date',
              'datetime'  => 'DateTime',
              'time'      => 'Time',
              'timestamp' => 'Timestamp'
            ),
            'id'       => '_blueprints_content-fields-filter-value-type' . $cfi,
          );
        $sections[ '_content_customfields' ][ 'fields' ][] =

          array(
            'type'     => 'select',
            'title'    => 'Field compare',
            'default'  => '=',
            'options'  => array(
              '='           => '=',
              '!='          => '!=',
              '>'           => '>',
              '>='          => '>=',
              '<'           => '<',
              '<='          => '<=',
              'LIKE'        => 'LIKE',
              'NOT LIKE'    => 'NOT LIKE',
              'IN'          => 'IN',
              'NOT IN'      => 'NOT IN',
              'BETWEEN'     => 'BETWEEN',
              'NOT BETWEEN' => 'NOT BETWEEN',
              'EXISTS'      => 'EXISTS',
              'NOT EXISTS'  => 'NOT EXISTS'
            ),
            'required' => array( '_blueprints_content-fields-filter-key' . $cfi, '!=', '' ),
            'id'       => '_blueprints_content-fields-filter-compare' . $cfi,
          );
        $sections[ '_content_customfields' ][ 'fields' ][] =

          array(
            'id'     => '_blueprints_content-fields-filter-section-end' . $cfi,
            'type'   => 'section',
            'indent' => false,
          );
      }

      // Help
      $prefix                      = '_content_help_'; // declare prefix
      $sections[ '_content_help' ] = array(
        'title'      => 'Help',
        'icon_class' => 'icon-large',
        'icon'       => 'el-icon-question-sign',
        'fields'     => array(

          array(
            'title'    => __( 'Snippets', 'pzarchitect' ),
            'id'       => $prefix . 'help-content-selection',
            'type'     => 'raw',
            'markdown' => true,
            'content'  => __( 'With Architect Pro you can enable an extra content type called *Snippets*.
  These give you a third method of creating content that doesn\'t fit into the post or page types.
It came about with my own need to create grids of product features. I didn\'t want to fill up pages or posts, so created Snippets for these small content bites.
You can use them however you like though, e.g Testimonials, FAQs, Features, Contacts, etc.
                ', 'pzarchitect' )

          ),
          array(
            'title'    => __( 'Online documentation', 'pzarchitect' ),
            'id'       => $prefix . 'help-content-online-docs',
            'type'     => 'raw',
            'markdown' => false,
            'content'  => '<a href="http://architect4wp.com/codex-listings/" target=_blank>' . __( 'Architect Online Documentation', 'pzarchitect' ) . '</a><br>' . __( 'This is a growing resource. Please check back regularly.', 'pzarchitect' )

          ),

        )
      );

      $metaboxes[] = array(
        'id'         => 'content-selections',
        'title'      => 'Content Source selection: Choose which posts, pages or other content to display',
        'post_types' => array( 'arc-blueprints' ),
        'sections'   => $sections,
        'position'   => 'normal',
        'priority'   => 'low',
        'sidebar'    => false

      );

      return $metaboxes;

    }

    function pzarc_mb_panels_layout( $metaboxes, $defaults_only = false ) {
      global $_architect;
      global $_architect_options;
      if ( empty( $_architect_options ) ) {
        $_architect_options = get_option( '_architect_options' );
      }

      if ( empty( $_architect ) ) {
        $_architect = get_option( '_architect' );
      }
      if ( $this->post_info[ 'source' ] !== 'defaults' ) {
        $registry     = arc_Registry::getInstance();
        $content_info = $registry->get( 'content_info' );
        if ( array_key_exists( 'component_types', $content_info[ $this->post_info[ 'source' ] ] ) ) {
          $this->component_types      = $content_info[ $this->post_info[ 'source' ] ][ 'component_types' ];
          $this->component_defaults   = $content_info[ $this->post_info[ 'source' ] ][ 'component_defaults' ];
          $this->preview_default   = $content_info[ $this->post_info[ 'source' ] ][ 'preview_default' ];
          $this->layout_editor_fields = array_key_exists( 'layout_editor_fields', $content_info[ $this->post_info[ 'source' ] ] ) ? $content_info[ $this->post_info[ 'source' ] ] [ 'layout_editor_fields' ] : $this->layout_editor_fields;
//        d($this->layout_editor);
//          die();
        }

      }
      $prefix     = '_panels_design_'; // declare prefix
      $sections   = array();
      $sections[] = array(
        'title'      => __( 'Content Panels Layout ', 'pzarchitect' ),
        'show_title' => false,
        'icon_class' => 'icon-large',
        'icon'       => 'el-icon-adjust-alt',
        'fields'     => array(
          array(
            'title'    => __( 'Components to show', 'pzarchitect' ),
            'id'       => $prefix . 'components-to-show',
            'type'     => 'button_set',
            'multi'    => true,
            'subtitle' => __( 'Feature can be either the Featured Image of the post, or the Featured Video (added by Architect).', 'pzarchitect' ),
            'default'  => $this->component_defaults,
            'options'  => $this->component_types,
            'hint'     => array(
              'title'   => __( 'Components to show', 'pzarchitect' ),
              'content' => __( 'Select which base components to include in this post\'s layout.', 'pzarchitect' )
            )
          ),
          array(
            'title'        => __( 'Layout', 'pzarchitect' ),
            'id'           => $prefix . 'preview',
            'type'         => 'code',
            'readonly'     => false, // Readonly fields can't be written to by code! Weird
            'code'         => draw_panel_layout( apply_filters( 'arc-layout-fields', $this->layout_editor_fields ) ),
            'default_show' => false,
            'subtitle'     => __( 'Drag and drop to reposition and resize components', 'pzarchitect' ),
            'default'      => json_encode($this->preview_default),
            'hint'         => array(
              'title'   => __( 'Post layout', 'pzarchitect' ),
              'content' => __( 'Drag and drop to sort the order of your elements. <strong>Heights are fluid, so not indicative of how it will look on the page</strong>.', 'pzarchitect' )
            )
          ),
          array(
            'title'    => __( 'Feature location', 'pzarchitect' ),
            'id'       => $prefix . 'feature-location',
            'width'    => '100%',
            'type'     => 'button_set',
            'default'  => 'components',
            'required' => array( $prefix . 'components-to-show', 'contains', 'image' ),
            'subtitle' => 'Use Background when you need the image to fill the post layout.',
            'options'  => array(
              'components'    => __( 'In Components Group', 'pzarchitect' ),
              'float'         => __( 'Outside components', 'pzarchitect' ),
              'content-left'  => __( 'In body/excerpt left', 'pzarchitect' ),
              'content-right' => __( 'In body/excerpt right', 'pzarchitect' ),
              'fill'          => __( 'Background', 'pzarchitect' ),
            ),
            'hint'     => array(
              'title'   => __( 'Feature location', 'pzarchitect' ),
              'content' => __( 'Select where within the post layout you want to display the Feature.', 'pzarchitect' )
            )
          ),
          array(
            'title'    => __( 'Feature align', 'pzarchitect' ),
            'id'       => $prefix . 'feature-float',
            'type'     => 'button_set',
            'default'  => 'default',
            'required' => array(
              array( $prefix . 'components-to-show', 'contains', 'image' ),
              array( $prefix . 'feature-location', '=', 'components' ),
            ),
            'subtitle' => __( 'Float the feature left or right to help close gaps between images and other components.', 'pzarchitect' ),
            'options'  => array(
              'default' => __( 'Default', 'pzarchitect' ),
              'left'    => __( 'Left', 'pzarchitect' ),
              'right'   => __( 'Right', 'pzarchitect' ),
            ),
          ),
          array(
            'title'    => __( 'Alternate features position', 'pzarchitect' ),
            'id'       => $prefix . 'alternate-feature-position',
            'type'     => 'button_set',
            'default'  => 'off',
            'required' => array(
              array( $prefix . 'feature-location', '!=', 'fill' ),
              array( $prefix . 'feature-location', '!=', 'components' ),
              array( $prefix . 'components-position', '!=', 'top' ),
              array( $prefix . 'components-position', '!=', 'bottom' ),
            ),
            'options'  => array(
              'off' => __( 'No', 'pzarchitect' ),
              'on'  => __( 'Yes', 'pzarchitect' ),
            ),
            'hint'     => array(
              'title'   => __( 'Alternate features position', 'pzarchitect' ),
              'content' => __( 'Alternate the features position left/right for each post.', 'pzarchitect' )
            ),
          ),
          array(
            'title'    => __( 'Feature in', 'pzarchitect' ),
            'id'       => $prefix . 'feature-in',
            'type'     => 'button_set',
            'multi'    => true,
            //                  'class'=> 'arc-field-advanced' ,
            'default'  => array( 'excerpt', 'content' ),
            'required' => array(
              array( $prefix . 'feature-location', '!=', 'components' ),
              array( $prefix . 'feature-location', '!=', 'float' ),
              array( $prefix . 'feature-location', '!=', 'fill' ),
              //                      array('show_advanced', 'equals', true),
            ),
            'options'  => array(
              'excerpt' => __( 'Excerpt', 'pzarchitect' ),
              'content' => __( 'Body', 'pzarchitect' ),
            ),
            'hint'     => array(
              'title'   => __( 'Feature in', 'pzarchitect' ),
              'content' => __( 'Set whether to display the Feature in the Excerpt, the Body or both. The default is both.<br><br> If you are using the Excerpt in full layouts as an introduction paragraph, this is one example of when you would turn off the Feature for the Excerpt.', 'pzarchitect' )
            )
          ),
          array(
            'title'   => __( 'Components area position', 'pzarchitect' ),
            'id'      => $prefix . 'components-position',
            'type'    => 'button_set',
            'width'   => '100%',
            'default' => 'top',
            'options' => array(
              'top'    => __( 'Top', 'pzarchitect' ),
              'bottom' => __( 'Bottom', 'pzarchitect' ),
              'left'   => __( 'Left', 'pzarchitect' ),
              'right'  => __( 'Right', 'pzarchitect' ),
            ),
            'hint'    => array(
              'title'   => __( 'Components area position', 'pzarchitect' ),
              'content' => __( 'Position for all the components as a group.', 'pzarchitect' )
            ),
            'desc'    => __( 'Left/right will only take affect when components area width is less than 100%', 'pzarchitect' )
          ),
          array(
            'title'         => __( 'Components area width %', 'pzarchitect' ),
            'id'            => $prefix . 'components-widths',
            'type'          => 'slider',
            'default'       => 100,
            'min'           => 1,
            'max'           => 100,
            'step'          => 1,
            'class'         => ' percent',
            'display_value' => 'label',
            'hint'          => array(
              'title'   => __( 'Components area width', 'pzarchitect' ),
              'content' => __( 'Set the overall width for the components area. Necessary for left or right positioning of sections', 'pzarchitect' )
            ),
          ),
          array(
            'title'         => __( 'Nudge components area up/down %', 'pzarchitect' ),
            'id'            => $prefix . 'components-nudge-y',
            'type'          => 'slider',
            'default'       => 0,
            'min'           => 0,
            'max'           => 100,
            'step'          => 1,
            'class'         => ' percent',
            'required'      => array( $prefix . 'feature-location', '=', 'fill' ),
            'display_value' => 'label',
            'hint'          => array(
              'title'   => __( 'Nudge components are up/down', 'pzarchitect' ),
              'content' => __( 'Enter percent to move the components area up/down. </br<br>NOTE: These measurements are percentage of the post layout.', 'pzarchitect' )
            )
          ),
          array(
            'title'         => __( 'Nudge components area left/right %', 'pzarchitect' ),
            'id'            => $prefix . 'components-nudge-x',
            'type'          => 'slider',
            'default'       => 0,
            'min'           => 0,
            'max'           => 100,
            'step'          => 1,
            'class'         => ' percent',
            'required'      => array( $prefix . 'feature-location', '=', 'fill' ),
            'display_value' => 'label',
            'hint'          => array(
              'title'   => __( 'Nudge components are left/right', 'pzarchitect' ),
              'content' => __( 'Enter percent to move the components area left/right. </br><br>NOTE: These measurements are percentage of the post layout.', 'pzarchitect' )
            )
          ),
          array(
            'title'         => __( 'Feature as thumbnail width %', 'pzarchitect' ),
            'id'            => $prefix . 'thumb-width',
            'type'          => 'slider',
            'default'       => 15,
            'min'           => 0,
            'max'           => 100,
            'step'          => 1,
            'class'         => ' percent',
            'required'      => array(
              array( $prefix . 'feature-location', '!=', 'fill' ),
              array( $prefix . 'feature-location', '!=', 'float' ),
              array( $prefix . 'feature-location', '!=', 'components' ),
            ),
            'display_value' => 'label',
            'subtitle'      => __( 'Set to zero to use image at actual size.', 'pzarchitect' ),
            'hint'          => array(
              'title'   => __( 'Feature as thumbnail width', 'pzarchitect' ),
              'content' => __( 'When you have set the featured image to appear in the body/excerpt, this determines its width.', 'pzarchitect' )
            )
          ),
          array(
            'title'   => __( 'Make header and footer', 'pzarchitect' ),
            'id'      => $prefix . 'components-headers-footers',
            'type'    => 'switch',
            'on'      => __( 'Yes', 'pzarchitect' ),
            'off'     => __( 'No', 'pzarchitect' ),
            //              'class'=> 'arc-field-advanced' ,
            //'required' => array('show_advanced', 'equals', true),
            'default' => true,
            'hint'    => array(
              'title'   => __( 'Make header and footer', 'pzarchitect' ),
              'content' => __( 'When enabled, Architect will automatically wrap the header and footer components of the post layout in header and footer tags to maintain compatibility with current WP layout trends.<br><br>However, some layouts, such as tabular, are not suited to using the headers and footers.', 'pzarchitect' )
            )
          ),
          array(
            'title'   => __( 'Link whole panel', 'pzarchitect' ),
            'id'      => $prefix . 'link-panel',
            //            'cols'    => 4,
            'type'    => 'switch',
            'on'      => __( 'Yes', 'pzarchitect' ),
            'off'     => __( 'No', 'pzarchitect' ),
            'default' => false,
            'hint'    => array(
              'title'   => 'Link whole panel',
              'content' => __( 'If enabled, clicking anywhere on the panel will take the viewer to the post. Note: No other links within the panel will work.', 'pzarchitect' )
            ),

            /// can't set defaults on checkboxes!
          ),
        )
      );

      if ( ! empty( $_architect_options[ 'architect_enable_styling' ] ) ) {
        $defaults = get_option( '_architect' );
        $prefix   = '_panels_styling_'; // declare prefix

        $font       = '-font';
        $link       = '-links';
        $padding    = '-padding';
        $margin     = '-margin';
        $background = '-background';
        $border     = '-borders';

        $stylingSections = array();
        $optprefix       = 'architect_config_';

        $sections[] = array(
          'title'      => __( 'Content Panels Styling', 'pzarchitect' ),
          'show_title' => false,
          'icon_class' => 'icon-large',
          'icon'       => 'el-icon-brush',
          'fields'     => pzarc_fields(
//                array(
//                    'title'    => __('Load style'),
//                    'id'       => $prefix . 'panels-load-style',
//                    'type'     => 'select',
//                    'subtitle' => 'Sorry to tease, but this isn\'t implemented yet.',
//                    'options'  => array('none', 'dark', 'light'),
//                    'default'  => 'none'
//                ),
            array(
              'title'    => __( 'Panels', 'pzarchitect' ),
              'id'       => $prefix . 'panels-section',
              'type'     => 'section',
              'indent'   => true,
              'class'    => 'heading',
              'subtitle' => 'Class: .pzarc-panel',
              'hint'     => array( 'content' => 'Class: .pzarc-panel' ),
            ),
            pzarc_redux_bg( $prefix . 'panels' . $background, '', $defaults[ $optprefix . 'panels' . $background ] ),
            pzarc_redux_padding( $prefix . 'panels' . $padding, '', $defaults[ $optprefix . 'panels' . $padding ] ),
            pzarc_redux_borders( $prefix . 'panels' . $border, '', $defaults[ $optprefix . 'panels' . $border ] ),
            array(
              'title'    => __( 'Components group', 'pzarchitect' ),
              'id'       => $prefix . 'components-section',
              'type'     => 'section',
              'indent'   => true,
              'class'    => 'heading',
              'hint'     => array( 'content' => 'Class: .pzarc_components' ),
              'subtitle' => 'Class: .pzarc_components',
            ),
            pzarc_redux_bg( $prefix . 'components' . $background, array( '.pzarc_components' ), $defaults[ $optprefix . 'components' . $background ] ),
            pzarc_redux_padding( $prefix . 'components' . $padding, array( '.pzarc_components' ), $defaults[ $optprefix . 'components' . $padding ] ),
            pzarc_redux_borders( $prefix . 'components' . $border, array( '.pzarc_components' ), $defaults[ $optprefix . 'components' . $border ] ),
            array(
              'title'    => __( 'Entry', 'pzarchitect' ),
              'id'       => $prefix . 'hentry-section',
              'type'     => 'section',
              'indent'   => true,
              'class'    => 'heading',
              'hint'     => array( 'content' => 'Class: .hentry' ),
              'subtitle' => ! $this->defaults ? ( is_array( $_architect[ 'architect_config_hentry-selectors' ] ) ? 'Classes: ' . implode( ', ', $_architect[ 'architect_config_hentry-selectors' ] ) : 'Class: ' . $_architect[ 'architect_config_hentry-selectors' ] ) : ''
            ),
            // id,selectors,defaults
            // need to grab selectors from options
            // e.g. $_architect['architect_config_hentry-selectors']
            // Then we need to get them back later
            pzarc_redux_bg( $prefix . 'hentry' . $background, ! $this->defaults ? $_architect[ 'architect_config_hentry-selectors' ] : '', $defaults[ $optprefix . 'hentry' . $background ] ),
            pzarc_redux_padding( $prefix . 'hentry' . $padding, ! $this->defaults ? $_architect[ 'architect_config_hentry-selectors' ] : '', $defaults[ $optprefix . 'hentry' . $padding ] ),
            pzarc_redux_margin( $prefix . 'hentry' . $margin, ! $this->defaults ? $_architect[ 'architect_config_hentry-selectors' ] : '', $defaults[ $optprefix . 'hentry' . $margin ] ),
            pzarc_redux_borders( $prefix . 'hentry' . $border, ! $this->defaults ? $_architect[ 'architect_config_hentry-selectors' ] : '', $defaults[ $optprefix . 'hentry' . $border ] )
          )
        );

        $sections[] = array(
          'id'         => 'custom-css',
          'title'      => __( 'Custom CSS', 'pzarchitect' ),
          'icon_class' => 'icon-large',
          'icon'       => 'el-icon-wrench',
          'fields'     => array(
            array(
              'id'       => $prefix . 'custom-css',
              'type'     => 'ace_editor',
              'title'    => __( 'Custom CSS', 'pzarchitect' ),
              'mode'     => 'css',
              'options'  => array( 'minLines' => 25 ),
              'default'  => $defaults[ $optprefix . 'custom-css' ],
              'subtitle' => __( 'As a shorthand, you can prefix your CSS class with MYBLUEPRINT or MYPANELS and Architect will substitute the correct class for this Blueprint. e.g. MYPANELS .entry-content{border-radius:5px;}', 'pzarchitect' )
            ),
          )
        );
      }


      // Create the metaboxes


      $metaboxes[] = array(
        'id'         => 'panels-design',
        'title'      => __( 'Content Panels Layout and stylings: Setup the layout of the content itself and panel stylings.', 'pzarchitect' ),
        'post_types' => array( 'arc-blueprints' ),
        'sections'   => $sections,
        'position'   => 'normal',
        'priority'   => 'low',
        'sidebar'    => false

      );

      return $metaboxes;

    }


    private
    static function arc_has_export_data() {
      $export_data = get_option( 'arc-export-to-preset' );
      if ( ! empty( $export_data ) ) {
        $title = $export_data[ 'title' ];
        delete_option( 'arc-export-to-preset' );

// TODO Tutorials on saving exports and creating Presets.
        return '<h4>Export data for Blueprint:' . $title . '</h4><p>Copy and paste the export data to its own file with a txt extension. You can then import it on another site in the Architect > Tools page. Or you can use it as the basis of a Preset that you may give away or sell.</p><form><pre class="arc-export-data"><textarea rows="10" cols="70">' . json_encode( $export_data ) . '</textarea></pre></form>';
      } else {
        return '';
      }
    }
  } // EOC


//  function pzarc_draw_sections_preview() {
//    // Put in a hidden field with the plugin url for use in js
//    $return_html
//      = '
//          <div id="pzarc-blueprint-wireframe">
//            <div id="pzarc-sections-preview-0" class="pzarc-sections pzarc-section-0"></div>
//            <div id="pzarc-sections-preview-1" class="pzarc-sections pzarc-section-1"></div>
//            <div id="pzarc-sections-preview-2" class="pzarc-sections pzarc-section-2"></div>
//            <div id="pzarc-navigator-preview">
//              <div class="pzarc-sections pzarc-section-navigator"><span class="icon-large el-icon-backward"></span> <span class="icon-large el-icon-caret-left"></span> <span class="icon-xlarge el-icon-stop"></span> <span class="icon-xlarge el-icon-stop"></span> <span class="icon-xlarge el-icon-stop"></span> <span class="icon-large el-icon-caret-right"></span> <span class="icon-large el-icon-forward"></span></div>
//              <div class="pzarc-sections pzarc-section-pagination">First Prev 1 2 3 4 ... 15 Next Last</div>
//            </div>
//          </div>
//          <div class="plugin_url" style="display:none;">' . PZARC_PLUGIN_APP_URL . '</div>
//        ';
//
//    return $return_html;
//  }

//  function show_meta() {
//    $return_html = '2301';
//    $meta        = get_post_meta( 2301 );
//    if ( $meta ) {
//
//      foreach ( $meta as $key => $value ) {
//        $return_html .= '<p>' . $key . ' : ' . $value[ 0 ] . '</p>';
//      }
//    }
//
//    return $return_html;
//  }

  /**
   * [draw_panel_layout description]
   * @return [type] [description]
   */
  function draw_panel_layout( $layout_fields = '' ) {
    //var_dump($layout_fields);
    // Put in a hidden field with the plugin url for use in js
    $return_html
      = '
  <div id="pzarc-custom-pzarc_layout" class="pzarc-custom">
    <div id="pzarc-dropzone-pzarc_layout" class="pzarc-dropzone">
      <div class="pzgp-cell-image-behind"></div>
      <div class="pzarc-content-area sortable">
        <span class="pzarc-draggable pzarc-draggable-title" title="Post title" data-idcode=title ><span>This is the title</span></span>
        <span class="pzarc-draggable pzarc-draggable-meta1 pzarc-draggable-meta" title="Meta info 1" data-idcode=meta1 ><span>Jan 1 2013</span></span>
        <span class="pzarc-draggable pzarc-draggable-excerpt" title="Excerpt with featured image" data-idcode=excerpt ><span><img src="' . PZARC_PLUGIN_APP_URL . '/shared/assets/images/sample-image.jpg" style="max-width:20%;padding:2px;" class="pzarc-align none">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam quis justo erat. Cras semper sem hendre...[more]</span></span>
        <span class="pzarc-draggable pzarc-draggable-content" title="Full post content" data-idcode=content ><span><img src="' . PZARC_PLUGIN_APP_URL . '/shared/assets/images/sample-image.jpg" style="max-width:20%;padding:2px;" class="pzarc-align none"><img src="' . PZARC_PLUGIN_APP_URL . '/shared/assets/images/sample-in-content.jpg" style="max-width:30%;float:left;padding:5px;">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam quis justo erat. <ul><li>&nbsp;•&nbsp;Cras semper sem hendrerit</li><li>&nbsp;•&nbsp;Tortor porta at auctor</li></ul></span></span>
        <span class="pzarc-draggable pzarc-draggable-image" title="Featured image" data-idcode=image style="max-height: 100px; overflow: hidden;"><span><img src="' . PZARC_PLUGIN_APP_URL . '/shared/assets/images/sample-image.jpg" style="max-width:100%;"></span></span>
        <span class="pzarc-draggable pzarc-draggable-meta2 pzarc-draggable-meta" title="Meta info 2" data-idcode=meta2 ><span>Categories - News, Sport</span></span>
        <span class="pzarc-draggable pzarc-draggable-meta3 pzarc-draggable-meta" title="Meta info 3" data-idcode=meta3 ><span>Comments: 27</span></span>
        <span class="pzarc-draggable pzarc-draggable-custom1 pzarc-draggable-meta" title="Custom field 1" data-idcode=custom1 ><span>Custom content group 1</span></span>
        <span class="pzarc-draggable pzarc-draggable-custom2 pzarc-draggable-meta" title="Custom field 2" data-idcode=custom2 ><span>Custom content group 2</span></span>
        <span class="pzarc-draggable pzarc-draggable-custom3 pzarc-draggable-meta" title="Custom field 3" data-idcode=custom3 ><span>Custom content group 3</span></span>
      </div>
    </div>
    <p class="pzarc-states ">Loading</p>
    <p class="howto "><strong style="color:#d00;">' . __( 'This is an example only and thus only a <span style="border-bottom: 3px double;">general guide</span> to how the panels will look.', 'pzarchitect' ) . '</strong></p>
  </div>
  <div class="plugin_url" style="display:none;">' . PZARC_PLUGIN_APP_URL . '</div>
  ';

    return $return_html;
  }


  add_action( 'admin_action_pzarc_new_from_preset', 'pzarc_new_from_preset' );
