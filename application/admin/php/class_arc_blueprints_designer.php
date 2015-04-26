<?php

  class arc_Blueprints_Designer {

    public $content_types;
    public $redux_opt_name = '_architect';
    public $defaults = false;


    /**
     * [__construct description]
     */
    function __construct( $defaults = false ) {


      $this->defaults = $defaults;


      add_action( 'admin_head', array( $this, 'content_blueprints_admin_head' ) );
      add_action( 'admin_enqueue_scripts', array( $this, 'content_blueprints_admin_enqueue' ) );
      add_filter( 'manage_arc-blueprints_posts_columns', array( $this, 'add_blueprint_columns' ) );
      add_action( 'manage_arc-blueprints_posts_custom_column', array( $this, 'add_blueprint_column_content' ), 10, 2 );

      add_action( "redux/metaboxes/$this->redux_opt_name/boxes", array( $this, 'pzarc_mb_blueprint_tabs' ), 10, 1 );
//      add_action("redux/metaboxes/$this->redux_opt_name/boxes", array($this,
//                                                                      'pzarc_mb_blueprint_presets'), 10, 1);
      add_action( "redux/metaboxes/$this->redux_opt_name/boxes", array(
        $this,
        'pzarc_mb_blueprint_general_settings'
      ), 10, 1 );
      add_action( "redux/metaboxes/$this->redux_opt_name/boxes", array( $this, 'pzarc_mb_blueprint_design' ), 10, 1 );
      add_action( "redux/metaboxes/$this->redux_opt_name/boxes", array( $this, 'pzarc_mb_panels_layout' ), 10, 1 );
      add_action( "redux/metaboxes/$this->redux_opt_name/boxes", array(
        $this,
        'pzarc_mb_blueprint_content_selection'
      ), 10, 1 );
      add_action( "redux/metaboxes/$this->redux_opt_name/boxes", array(
        $this,
        'pzarc_mb_blueprint_styling'
      ), 10, 1 );
      add_action( "redux/metaboxes/$this->redux_opt_name/boxes", array(
        $this,
        'pzarc_mb_panels_styling'
      ), 10, 1 );
      add_filter( 'views_edit-arc-blueprints', array( $this, 'blueprints_description' ) );


    }

    /**
     * [content_blueprints_admin_enqueue description]
     *
     * @param  [type] $hook [description]
     */
    public function content_blueprints_admin_enqueue( $hook ) {
      $screen = get_current_screen();
      if ( 'arc-blueprints' == $screen->id ) {
        require_once( PZARC_DOCUMENTATION_PATH . PZARC_LANGUAGE . '/blueprints-pageguide.php' );

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

      $presets                  = new arcPresetsLoader();
      $presets_array            = $presets->render();
      $presets_html             = $presets_array[ 'html' ];
      $content[ 'arc-message' ] = '
      <div class="after-title-help postbox">

        <div class="inside">
          <p class="howto">' . __( 'Documentation can be found throughout Architect or online at the', 'pzarchitect' ) . ' <a
                href="http://architect4wp.com/codex-listings" target="_blank">Architect Codex</a></p>

          <p class="howto"><a class="arc-presets-link" href="javascript:void(0);">' . __( 'Create a new Blueprint from a Preset design', 'pzarchitect' ) . '</a></p>
          ' .

                                  self::arc_has_export_data()

                                  .
                                  '
          <!-- Presets selector -->
          <!-- Display none to be sure -->
          <div class="arc-presets-selector" style="display:none;">
           <h2 class="heading">Architect Blueprints: Preset selector</h2>
           <div class="tabby tabs">
                <button class="tabby-sliders first active" data-tab="#sliders">' . __( 'Sliders', 'pzarchitect' ) . '</button>
                <button class="tabby-grids" data-tab="#grids" >' . __( 'Grids', 'pzarchitect' ) . '</button>
                <button class="tabby-tabbed" data-tab="#tabbed">' . __( 'Tabbed', 'pzarchitect' ) . '</button>
                <button class="tabby-masonry" data-tab="#masonry">' . __( 'Masonry', 'pzarchitect' ) . '</button>
                <button class="tabby-accordion" data-tab="#accordion">' . __( 'Accordion', 'pzarchitect' ) . '</button>
                <button class="tabby-tabular" data-tab="#tabular">' . __( 'Tabular', 'pzarchitect' ) . '</button>
            </div>

           <div class="tabby tabs-content container">
           <div class="tabs-pane active" id="sliders">' .

                                  $presets_html[ 'slider' ]

                                  . '</div>
           <div class="tabs-pane" id="grids">' .

                                  $presets_html[ 'basic' ]

                                  . '</div>
           <div class="tabs-pane" id="tabbed">' .

                                  $presets_html[ 'tabbed' ]

                                  . '</div>
           <div class="tabs-pane" id="masonry">' .

                                  $presets_html[ 'masonry' ]

                                  . '</div>
           <div class="tabs-pane" id="accordion">' .

                                  $presets_html[ 'accordion' ]

                                  . '</div>
           <div class="tabs-pane" id="tabular">' .

                                  $presets_html[ 'table' ]

                                  . '</div>
           </div>
           <p class="footer">All Presets use Dummy Content by default. Please change to the content of your choice after loading the chosen Preset.</p>
           <div class="buttons">
            <a class="arc-button-presets styled disabled" href="javascript:void(0);">Use styled</a>
            <a class="arc-button-presets unstyled disabled" href="javascript:void(0);">Use unstyled</a>
            <a class="arc-button-presets close" href="javascript:void(0);">Cancel</a>
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
        '_blueprints_description'    => __( 'Description', 'pzarchitect' ),
        '_blueprints_content-source' => __( 'Content source', 'pzarchitect' ),
        'layout'                     => __( 'Type', 'pzarchitect' ),
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

        case 'layout':
          if ( ! empty( $post_meta[ '_blueprints_section-0-layout-mode' ][ 0 ] ) ) {
            echo '1: ' . ucfirst( $post_meta[ '_blueprints_section-0-layout-mode' ][ 0 ] );
          } else {
            echo '1: Grid';
          }
          if ( ! empty( $post_meta[ '_blueprints_section-0-layout-mode' ][ 0 ] ) && ! empty( $post_meta[ '_blueprints_section-1-layout-mode' ][ 0 ] ) ) {
            echo '<br>' . '2: ' . ucfirst( $post_meta[ '_blueprints_section-1-layout-mode' ][ 0 ] );
          }
          if ( ( ! empty( $post_meta[ '_blueprints_section-0-layout-mode' ][ 0 ] ) || ! empty( $post_meta[ '_blueprints_section-1-layout-mode' ][ 0 ] ) ) && ! empty( $post_meta[ '_blueprints_section-2-layout-mode' ] ) ) {
            echo '<br>' . '3: ' . ucfirst( $post_meta[ '_blueprints_section-2-layout-mode' ][ 0 ] );
          }
          break;
      }
    }


    /**
     * [create_blueprints_post_type description]
     * @return [type] [description]
     */


    function pzarc_mb_blueprint_tabs( $metaboxes, $defaults_only = false ) {
      $prefix   = '_blueprint_tabs_';
      $sections = array();
      global $_architect_options;
      if ( empty( $_architect_options ) ) {
        $_architect_options = get_option( '_architect_options' );
      }
      $fields = array();
      if ( ! empty( $_architect_options[ 'architect_enable_styling' ] ) ) {
        $fields = array(
          array(
            'id'      => $prefix . 'tabs',
            'type'    => 'tabbed',
            'options' => array(
              'layout'          => '<span><span class="stepno">1</span> <i class="el el-th"></i> Blueprint Design</span>',
              'content'         => '<span><span class="stepno">2</span> <i class="el el-filter"></i> Content Selection</span>',
              'panels'          => '<span><span class="stepno">3</span> <i class="el el-website"></i> Content Layout</span>',
              'content_styling' => '<span><span class="stepno">4</span> <i class="el el-brush"></i> Content Styling</span>',
              'styling'         => '<span><span class="stepno">5</span> <i class="el el-brush"></i> Blueprint Styling</span>',
            ),
            'targets' => array(
              'layout'          => array( 'layout-settings' ),
              'content'         => array( 'content-selections' ),
              'panels'          => array( 'panels-design' ),
              'content_styling' => array( 'panels-styling' ),
              'styling'         => array( 'blueprint-stylings' ),
              //                    'presets'         => array('presets'),
            )
          ),
        );

      } else {
        $fields = array(
          array(
            'id'      => $prefix . 'tabs',
            'type'    => 'tabbed',
            'options' => array(
              'layout'  => '<span><span class="stepno">1</span> Blueprint Design</span>',
              'content' => '<span><span class="stepno">2</span> Content Selection</span>',
              'panels'  => '<span><span class="stepno">3</span> Content Design</span>',
            ),
            'targets' => array(
              'layout'  => array( 'layout-settings' ),
              'content' => array( 'content-selections' ),
              'panels'  => array( 'panels-design' ),
            )
          ),
        );


      }

      $fields = apply_filters( 'pzarc_editor_tabs', $fields );

      $sections[ ]  = array(
        //          'title'      => __('General Settings', 'pzarchitect'),
        'show_title' => true,
        'icon_class' => 'icon-large',
        'icon'       => 'el-icon-home',
        'fields'     => $fields
      );
      $metaboxes[ ] = array(
        'id'         => $prefix . 'blueprints',
        'title'      => __( 'Steps:', 'pzarchitect' ),
        'post_types' => array( 'arc-blueprints' ),
        'sections'   => $sections,
        'position'   => 'normal',
        'priority'   => 'high',
        'sidebar'    => false

      );

      return $metaboxes;
    }

    function pzarc_mb_blueprint_presets( $metaboxes, $defaults_only = false ) {
      $fields = array(
        array(
          'id'      => '_presets_choose',
          'title'   => 'Choose a preset (optional)',
          'type'    => 'image_select',
          'default' => 'none',
          'height'  => '200px',
          'options' => array(
            'none'                    => array(
              'alt' => 'Build your own',
              'img' => PZARC_PLUGIN_APP_URL . 'shared/assets/images/metaboxes/presets-none.png'
            ),
            'featured-posts-slider'   => array(
              'alt' => 'Featured Posts Slider',
              'img' => PZARC_PLUGIN_APP_URL . 'shared/assets/images/metaboxes/preset-featured-posts-slider.jpg'
            ),
            'gallery-carousel'        => array(
              'alt' => 'Gallery carousel',
              'img' => PZARC_PLUGIN_APP_URL . 'shared/assets/images/metaboxes/preset-gallery-carousel.jpg'
            ),
            'full-width-image-slider' => array(
              'alt' => 'Full width image slider',
              'img' => PZARC_PLUGIN_APP_URL . 'shared/assets/images/metaboxes/presets-full-width-image-slider.png'
            ),
            'featured-video'          => array(
              'alt' => 'Featured videos',
              'img' => PZARC_PLUGIN_APP_URL . 'shared/assets/images/metaboxes/presets-featured-video.png'
            ),
          ),
        ),
      );

      $sections[ ]  = array(
        //          'title'      => __('General Settings', 'pzarchitect'),
        'show_title' => true,
        'icon_class' => 'icon-large',
        'icon'       => 'el-icon-home',
        'fields'     => $fields
      );
      $metaboxes[ ] = array(
        'id'         => 'presets',
        'title'      => __( 'Presets', 'pzarchitect' ),
        'post_types' => array( 'arc-blueprints' ),
        'sections'   => $sections,
        'position'   => 'normal',
        'priority'   => 'low',
        'sidebar'    => false

      );

      return $metaboxes;
    }


    // TODO: ADD FILTER OPTION FOR RELATED POSTS

    /**
     * pzarc_blueprint_layout_general
     *
     * @param array $metaboxes
     *
     * @return array
     */
    function pzarc_mb_blueprint_general_settings( $metaboxes, $defaults_only = false ) {
      $prefix = '_blueprints_';
      global $_architect_options;
      $cfwarn=false;
      if ( ! empty( $_GET[ 'post' ] ) ) {
        $thispostmeta = get_post_meta( $_GET[ 'post' ] );
        $cfcount      = ( ! empty( $thispostmeta[ '_panels_design_custom-fields-count' ][ 0 ] ) ? $thispostmeta[ '_panels_design_custom-fields-count' ][ 0 ] : 0 );
        $cfwarn = (ini_get('max_input_vars')<=1000 && $cfcount>0);

      }
      $sections[ ]  = array(
        'fields' => array(
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
            'validate' => 'not_empty'
          ),
          array(
            'id'    => $prefix . 'description',
            'title' => __( 'Description', 'pzarchitect' ),
            'type'  => 'textarea',
            'rows'  => 2,
            'hint'  => array( 'content' => __( 'A short description to help you or others know what this Blueprint is for', 'pzarchitect' ) ),
          ),
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
          ),
          array(
            'id'    => $prefix . 'input-vars-message',
            'title' => __( 'Custom fields', 'pzarchitect' ),
            'type'  => 'info',
            'style'=> ($cfwarn?'critical':'normal'),
            'required'=>array('_panels_design_components-to-show','contains','custom'),
            'desc'=>__('If you add custom fields to a Blueprint it adds many more fields to the form. <strong>This can cause some fields not to save</strong>. Please read this post by Woo Themes for solutions:','pzarchitect').'<br><a href="http://docs.woothemes.com/document/problems-with-large-amounts-of-data-not-saving-variations-rates-etc/" target=_blank>Problems with large amounts of data not saving</a><br>Your max_input_vars setting is: '.ini_get('max_input_vars'),

          ),
        )
      );
      $metaboxes[ ] = array(
        'id'         => $prefix . 'layout-general-settings',
        'title'      => 'General Settings',
        'post_types' => array( 'arc-blueprints' ),
        'sections'   => $sections,
        'position'   => 'side',
        'priority'   => 'low',
        'sidebar'    => false

      );


      return $metaboxes;

    }


    /**
     * LAYOUT
     */
    function pzarc_mb_blueprint_design( $metaboxes, $defaults_only = false ) {
      $prefix   = '_blueprints_';
      $sections = array();
      global $_architect_options;
      if ( empty( $_architect_options ) ) {
        $_architect_options = get_option( '_architect_options' );
      }


      /** SECTIONS */
      $icons       = array( 0 => 'el-icon-align-left', 1 => 'el-icon-th', 2 => 'el-icon-th-list' );
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
              'title'   => 'Type',
              'id'      => $prefix . 'section-' . $i . '-layout-mode',
              'type'    => 'image_select',
              'default' => 'basic',
              'desc'    => $desc[ (int) ( $i > 0 ) ],
              'height'  => 64,
              'options' => $modesx[ (int) ( $i > 0 ) ],
              'hint'    => array(
                'title'   => 'Types',
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
              'id'       => $prefix . 'section-' . $i . '-tabular-title',
              'title'    => __( 'Tabular', 'pzarchitect' ),
              'type'     => 'section',
              'indent'   => true,
              'required' => array( $prefix . 'section-' . $i . '-layout-mode', '=', 'table' ),
            ),
            array(
              'id'         => $prefix . 'section-' . $i . '-table-column-titles',
              'title'      => __( 'Table column titles', 'pzarchitect' ),
              'type'       => 'multi_text',
              'show_empty' => false,
              'add_text'   => 'Add a title',
              'required'   => array( $prefix . 'section-' . $i . '-layout-mode', '=', 'table' ),
            ),
            array(
              'id'       => $prefix . 'section-' . $i . '-accordion-title',
              'title'    => __( 'Accordion', 'pzarchitect' ),
              'type'     => 'section',
              'indent'   => true,
              'required' => array( $prefix . 'section-' . $i . '-layout-mode', '=', 'accordion' ),
            ),
            array(
              'id'         => $prefix . 'section-' . $i . '-accordion-titles',
              'title'      => __( 'Accordion titles', 'pzarchitect' ),
              'type'       => 'multi_text',
              'show_empty' => false,
              'add_text'   => 'Add a title',
              'required'   => array( $prefix . 'section-' . $i . '-layout-mode', '=', 'accordion' ),
              'subtitle'   => 'Optional. Leave as none to use post titles'
            ),
            array(
              'id'     => $prefix . 'section-' . $i . '-panels-heading',
              'title'  => __( 'Number to show', 'pzarchitect' ),
              'type'   => 'section',
              'indent' => true,
            ),
            array(
              'title'    => __( 'Limit posts', 'pzarchitect' ),
              'id'       => $prefix . 'section-' . $i . '-panels-limited',
              'type'     => 'switch',
              'on'       => __( 'Yes', 'pzarchitect' ),
              'off'      => __( 'No', 'pzarchitect' ),
              'default'  => true,
              'subtitle' => 'Each panel displays a single post from the selected content type.'
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
              'id'     => $prefix . 'section-' . $i . '-columns-heading',
              'title'  => __( 'Panels across', 'pzarchitect' ),
              'type'   => 'section',
              'indent' => true,
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
            //            (PZARC_TESTER?array(
            //              'title'    => __( 'Enable horizontal scrolling', 'pzarchitect' ),
            //              'id'       => $prefix . 'section-' . $i . '-columns-breakpoint-2-scroll',
            //              'type'     => 'switch',
            //              'on'       => __( 'Yes', 'pzarchitect' ),
            //              'off'      => __( 'No', 'pzarchitect' ),
            //              'required'   => array( $prefix . 'section-' . $i . '-layout-mode', '=', 'basic' ),
            //              'default'  => true,
            //              'subtitle' => __('Turn this grid into a horizontal scroller at '.$_architect_options[ 'architect_breakpoint_2' ][ 'width' ] . ' to ' . $_architect_options[ 'architect_breakpoint_1' ][ 'width' ],'pzarchitect'),
            //            ):null),
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
            //            (PZARC_TESTER?array(
            //              'title'    => __( 'Enable horizontal scrolling', 'pzarchitect' ),
            //              'id'       => $prefix . 'section-' . $i . '-columns-breakpoint-3-scroll',
            //              'type'     => 'switch',
            //              'on'       => __( 'Yes', 'pzarchitect' ),
            //              'off'      => __( 'No', 'pzarchitect' ),
            //              'required'   => array( $prefix . 'section-' . $i . '-layout-mode', '=', 'basic' ),
            //              'default'  => true,
            //              'subtitle' => __('Turn this grid into a horizontal scroller at '.$_architect_options[ 'architect_breakpoint_2' ][ 'width' ] . ' and below','pzarchitect'),
            //            ):null),

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
              'default' => array( 'margin-right'  => '0',
                                  'margin-bottom' => '0',
                                  'margin-left'   => '0',
                                  'margin-top'    => '0'
              ),
              //'subtitle' => __('Right, bottom', 'pzarchitect')
              //    'hint'  => array('content' => __('Set the vertical gutter width as a percentage of the section width. The gutter is the gap between adjoining elements', 'pzarchitect'))
            ),
            // For the time being the container is not used and its values are defaulted.
            /// It's kept  here for possible future use
            //                array(
            //                    'id'     => $prefix . 'section-' . $i . '-sections-heading',
            //                    'title'  => __('Panels wrapper', 'pzarchitect'),
            //                    'type'   => 'section',
            //                    'indent' => true,
            //                ),
            //                array(
            //                    'id'      => $prefix . 'sections-width' . $i,
            //                    'type'    => 'dimensions',
            //                    //               'mode'    => array('width' => true, 'height' => false),
            //                    'units'   => array('%', 'px'),
            //                    'width'   => true,
            //                    'height'  => false,
            //                    'title'   => __('Width', 'pzarchitect'),
            //                    'default' => array('width' => '100', 'units' => '%'),
            //                ),
            //                array(
            //                    'id'      => $prefix . 'sections-align' . $i,
            //                    'type'    => 'button_set',
            //                    'select2' => array('allowClear' => false),
            //                    'options' => array('left'   => __('Left', 'pzarchitect'),
            //                                       'center' => __('Centre', 'pzarchitect'),
            //                                       'right'  => __('Right', 'pzarchitect')),
            //                    'title'   => __('Align', 'pzarchitect'),
            //                    'default' => 'center',
            //                ),
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
            //              array(
            //                  'title'   => __('Show advanced settings', 'pzarchitect'),
            //                  'id'      => 'show_advanced',
            //                  'type'    => 'switch',
            //                  //                    'subtitle' => __('Displays a background image on the Architect admin pages', 'pzarchitect'),
            //                  'default' => $_architect_options[ 'architect_show_advanced' ],
            //                  'on'      => __('Yes', 'pzarchitect'),
            //                  'off'     => __('No', 'pzarchitect')
            //              ),
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
              'title'    => 'Blueprint display title',
              'id'       => $prefix . 'blueprint-title',
              'type'     => 'text',
              'subtitle' => __( 'Enter a title to display above the Blueprint', 'pzarchitect' ),
            ),
            //                array(
            //                    'id'       => $prefix . 'section-' . $i . '-panel-layout',
            //                    'title'    => __('Panels layout (discontinued)', 'pzarchitect'),
            //                    'type'     => 'text',
            //                    'readonly' => true,
            //                ),

          )

        );
      }


      /** SLIDER  */
      $tabbed                      = array(
        'tabbed' => array(
          'alt' => 'Titles',
          'img' => PZARC_PLUGIN_APP_URL . 'shared/assets/images/metaboxes/nav-type-tabbed.png'
        ),
        'labels' => array(
          'alt' => 'Labels',
          'img' => PZARC_PLUGIN_APP_URL . 'shared/assets/images/metaboxes/nav-type-labels.png'
        ),
      );
      $slider                      = array(
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
        'title'      => __( 'Sliders & Tabbed', 'pzarchitect' ),
        'show_title' => true,
        'icon_class' => 'icon-large',
        'icon'       => 'el-icon-website',
        //          'desc'       => 'When the navigation type is set to navigator, presentation will always be in a slider form. You can have multiple navigators on a page, thus multiple sliders.',
        'fields'     => array(
          array(
            'id'      => $prefix . 'navigator',
            'title'   => __( 'Type', 'pzarchitect' ),
            'type'    => 'image_select',
            'default' => 'bullets',
            'hint'    => array( 'content' => __( 'Bullets,Titles, Labels, Numbers, Thumbnails or none', 'pzarchitect' ) ),
            'height'  => 75,
            'options' => $slider
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
              'left'   => 'Left',
              'center' => 'Centre',
              'right'  => 'Right'
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
            'title'    => 'Pager',
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
            'title'    => 'Thumb skip button',
            'id'       => $prefix . 'navigator-skip-button',
            'type'     => 'button_set',
            'default'  => 'circle',
            'options'  => array(
              'circle' => __( 'Circle', 'pzarchitect' ),
              'square' => __( 'Square', 'pzarchitect' ),
            ),
            'required' => array(
              array( $prefix . 'navigator', '=', 'thumbs' ),
            )
          ),
          array(
            'id'       => $prefix . 'navigator-skip-thumbs',
            'title'    => __( 'Number of thumbnails', 'pzarchitect' ),
            'type'     => 'spinner',
            'default'  => 5,
            'min'      => 1,
            'max'      => 100,
            'subtitle' => __( 'Number of thumbnails to show at once in the navigator. This is also the number of thumbs skipped by by the navigator forward and back buttons', 'pzarchitect' ),
            'required' => array(
              array( $prefix . 'navigator', 'equals', 'thumbs' ),
            )
          ),
          /** TRANSITIONS
           ******************/

          array(
            'title'    => __( 'Transitions', 'pzarchitect' ),
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
            'title'    => 'Type',
            'id'       => $prefix . 'transitions-type',
            'type'     => 'button_set',
            'default'  => 'fade',
            //              'select2' => array('allowClear' => false),
            'required' => array( $prefix . 'section-0-layout-mode', '=', 'slider' ),
            'options'  => array(
              'fade'  => 'Fade',
              'slide' => 'Slide',
              //                  'swipe'  => 'Swipe',
              //                  'rotate' => 'Rotate',
              //                  'flip'   => 'Flip'
            )
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
          //              array(
          //                  'title'   => __('Go to after last slide', 'pzarchitect'),
          //                  'id'      => $prefix . 'transitions-infinite',
          //                  'type'    => 'button_set',
          //                  'options' => array(
          //                      'infinite' => 'First',
          //                      'reverse'  => 'Previous'),
          //                  'default' => 'infinite',
          //                  'hint'    => array('content' => __('Loop back to the first slide after reaching the last one or reverse direction to previous slide', 'pzarchitect')),
          //              ),

        ),
        //            array(
        //                'title'   => 'Pause on hover',
        //                'id'      => $prefix . 'transitions-pause-on-hover',
        //                'type'    => 'switch',
        //                'default' => true,
        //            ),
        //)
      );

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
            'desc'    => __( 'If your content type is Defaults and you choose to enable overrides, pagination will likely mess up if this Blueprint is displayed on the standard WP blog page.', 'pzarchitect' )
          ),
          array(
            'title'  => __( 'Settings', 'pzarchitect' ),
            'id'     => '_blueprint_pagination-settings-section',
            'type'   => 'section',
            'indent' => true,
            'required' => array( '_blueprints_pagination', 'equals', true ),
          ),
          array(
            'title'    => __( 'Posts per page', 'pzarchitect' ),
            'id'       => '_blueprints_pagination-per-page',
            'type'     => 'spinner',
            'default'  => 1,
            'min'      => 1,
            'max'      => 99,
            'required' => array( '_blueprints_pagination', 'equals', true ),
          ),
          array(
            'id'       => '_blueprints_pager-location',
            'title'    => __( 'Pagination location', 'pzarchitect' ),
            'type'     => 'select',
            'select2'  => array( 'allowClear' => false ),
            'default'  => 'bottom',
            'options'  => array(
              'bottom' => 'Bottom',
              'top'    => 'Top',
              'both'   => 'Both'
            ),
          ),
          array(
            'id'       => '_blueprints_pager',
            'title'    => __( 'Blog Pagination', 'pzarchitect' ),
            'type'     => 'select',
            'select2'  => array( 'allowClear' => false ),
            'default'  => 'prevnext',
            'options'  => array(
              //                    'none'     => 'None',
              'prevnext' => 'Previous/Next',
              'names'    => 'Post names',
              'pagenavi' => 'PageNavi',
            ),
          ),
          array(
            'id'       => '_blueprints_pager-single',
            'title'    => __( 'Single Post Pagination', 'pzarchitect' ),
            'type'     => 'select',
            'select2'  => array( 'allowClear' => false ),
            'default'  => 'prevnext',
            'options'  => array(
              //                    'none'     => 'None',
              'prevnext' => 'Previous/Next',
              'names'    => 'Post names',
              'pagenavi' => 'PageNavi',
            ),
          ),
          array(
            'id'       => '_blueprints_pager-archives',
            'title'    => __( 'Archives Pagination', 'pzarchitect' ),
            'type'     => 'select',
            'select2'  => array( 'allowClear' => false ),
            'default'  => 'prevnext',
            'options'  => array(
              //                    'none'     => 'None',
              'prevnext' => 'Previous/Next',
              'names'    => 'Post names',
              'pagenavi' => 'PageNavi',
            ),
          ),
          array(
            'id'       => '_blueprints_pager-custom-prev',
            'title'    => __( 'Custom text for Previous', 'pzarchitect' ),
            'type'     => 'text',
            'default'  => null,
          ),
          array(
            'id'       => '_blueprints_pager-custom-next',
            'title'    => __( 'Custom text for Next', 'pzarchitect' ),
            'type'     => 'text',
            'default'  => null,
          ),
          array(
            'id'     => '_blueprint_pagination-settings-section-end',
            'type'   => 'section',
            'indent' => false,
            'required' => array( '_blueprints_pagination', 'equals', true ),
          ),
        )
      );

//      $file_contents          = file_get_contents(PZARC_DOCUMENTATION_PATH . PZARC_LANGUAGE . '/using-blueprints.md');
      $ch = curl_init( PZARC_DOCUMENTATION_URL . PZARC_LANGUAGE . '/using-blueprints.md' );
      curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
      $file_contents = curl_exec( $ch );
      curl_close( $ch );

//      $sections[ '_usingbp' ] = array(
//        'title'      => '.eprints',
//        'icon_class' => 'icon-large',
//        'icon'       => 'el-icon-info-sign',
//        'fields'     => array(
      $sections[ '_help' ] = array(
        'title'      => 'Help',
        'icon_class' => 'icon-large',
        'icon'       => 'el-icon-question-sign',
        'fields'     => array(

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
            'title'    => __( 'Online documentation', 'pzarchitect' ),
            'id'       => $prefix . 'help-usingbp-online-docs',
            'type'     => 'raw',
            'markdown' => false,
            'content'  => '<a href="http://architect4wp.com/codex-listings/" target=_blank>' . __( 'Architect Online Documentation', 'pzarchitect' ) . '</a><br>' . __( 'This is a growing resource. Please check back regularly.', 'pzarchitect' )

          ),
        )
      );
//      $sections[ '_help' ]    = array(
//        'title'      => 'Help',
//        'icon_class' => 'icon-large',
//        'icon'       => 'el-icon-question-sign',
//        'fields'     => array(

//              array(
//                  'title'    => __('Blueprints videos', 'pzarchitect'),
//                  'id'       => $prefix . 'help-blueprints-videos',
//                  'subtitle' => __('Internet connection required'),
//                  'type'     => 'raw',
//                  'class'    => 'plain',
//                  'markdown' => false,
//                  'content'  => ($defaults_only ? '' : @file_get_contents('https://s3.amazonaws.com/341public/architect/blueprints-videos.html'))
//              ),
//array(
//  'title'    => __( 'Online documentation', 'pzarchitect' ),
//  'id'       => $prefix . 'help-layout-online-docs',
//  'type'     => 'raw',
//  'markdown' => false,
//  'content'  => '<a href="http://architect4wp.com/codex-listings/" target=_blank>' . __( 'Architect Online Documentation', 'pzarchitect' ) . '</a><br>' . __( 'This is a growing resource. Please check back regularly.', 'pzarchitect' )
//
//),
//              array(
//                  'title' => __('Data', 'pzarchitect'),
//                  'id'    => $prefix . 'help-data',
//                  'type'  => 'code',
//                  'code'  => show_meta()
//
//              ),

////        )
//      );

      $metaboxes[ ] = array(
        'id'         => 'layout-settings',
        'title'      => 'Blueprint Design: Choose and setup the overall design ',
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
      $prefix   = '_content_general_';
      $sections = array();
//    $sections[ ] = array(
//        'title'      => 'Settings',
//        'icon_class' => 'icon-large',
//        'icon'       => 'el-icon-adjust-alt',
//        'fields'     => array(
//
//        )
//    );
//
      /** DISPLAY ALL THE CONTENT TYPES FORMS */
      $registry = arc_Registry::getInstance();

      $content_post_types = (array) $registry->get( 'post_types' );
      $content_types      = array();
      foreach ( $content_post_types as $key => $value ) {
        if ( isset( $value[ 'blueprint-content' ] ) ) {
          $content_types[ $value[ 'blueprint-content' ][ 'type' ] ] = $value[ 'blueprint-content' ][ 'name' ];
        }
      }
      /** GENERAL  Settings*/
      $blueprint_content_common = $registry->get( 'blueprint-content-common' );

      // If you add/remove a content type, you have to add/remove it's side tab too
      $prefix                 = '_content_general_';
      $sections[ '_general' ] = array(
        'title'      => 'Source',
        'icon_class' => 'icon-large',
        'icon'       => 'el-icon-folder',
        'fields'     => array(
          array(
            'title'    => __( 'Content source', 'pzarchitect' ),
            'id'       => '_blueprints_content-source',
            'type'     => 'button_set',
            //                  'select2'  => array('allowClear' => false),
            'default'  => 'defaults',
            'options'  => $content_types,
            'subtitle' => ( array_key_exists( 'snippets', $content_types ) ? '' : 'Several more content sources supported in Architect Pro version, including Pages, Snippets, Galleries, Custom Post Types and a special Dummy option to display dummy content' )
          ),
        )
      );


      /** DISPLAY ALL THE CONTENT TYPES FORMS */
      foreach ( $content_post_types as $key => $value ) {
        if ( isset( $value[ 'blueprint-content' ] ) ) {
          foreach ( $value[ 'blueprint-content' ][ 'sections' ][ 'fields' ] as $k => $v ) {
            $v[ 'required' ][ ]                    = array(
              '_blueprints_content-source',
              'equals',
              $value[ 'blueprint-content' ][ 'type' ]
            );
            $sections[ '_general' ][ 'fields' ][ ] = $v;
          }
        }
      }
      /** FILTERS */
      $sections[ '_settings' ] = $blueprint_content_common[ 0 ][ 'settings' ][ 'sections' ];
//      $sections[ '_pagination' ] = $blueprint_content_common[ 0 ][ 'pagination' ][ 'sections' ];
      $sections[ '_filters' ] = $blueprint_content_common[ 0 ][ 'filters' ][ 'sections' ];


      $prefix                      = '_content_help_';
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
          //              array(
          //                  'title'    => __('Blueprints videos', 'pzarchitect'),
          //                  'subtitle' => __('Internet connection required'),
          //                  'id'       => $prefix . 'help-blueprints-videos',
          //                  'type'     => 'raw',
          //                  'class'    => 'plain',
          //                  'markdown' => false,
          //                  'content'  => ($defaults_only ? '' : @file_get_contents('https://s3.amazonaws.com/341public/architect/blueprints-videos.html'))
          //              ),
          array(
            'title'    => __( 'Online documentation', 'pzarchitect' ),
            'id'       => $prefix . 'help-content-online-docs',
            'type'     => 'raw',
            'markdown' => false,
            'content'  => '<a href="http://architect4wp.com/codex-listings/" target=_blank>' . __( 'Architect Online Documentation', 'pzarchitect' ) . '</a><br>' . __( 'This is a growing resource. Please check back regularly.', 'pzarchitect' )

          ),

        )
      );

      $metaboxes[ ] = array(
        'id'         => 'content-selections',
        'title'      => 'Content Selection: Choose which posts, pages or other content to display',
        'post_types' => array( 'arc-blueprints' ),
        'sections'   => $sections,
        'position'   => 'normal',
        'priority'   => 'low',
        'sidebar'    => false

      );

      return $metaboxes;

    }

    /**
     * pzarc_blueprint_layout_styling
     *
     * @param $metaboxes
     *
     * @return array
     */
    function pzarc_mb_blueprint_styling( $metaboxes, $defaults_only = false ) {
      global $_architect_options;
      if ( empty( $_architect_options ) ) {
        $_architect_options = get_option( '_architect_options' );
      }
      if ( empty( $_architect ) ) {
        $_architect = get_option( '_architect' );
      }
      if ( ! empty( $_architect_options[ 'architect_enable_styling' ] ) ) {
        $defaults = get_option( '_architect' );
        $prefix   = '_blueprints_styling_';
// TODO: need to get styling to take
        $font         = '-font';
        $link         = '-links';
        $padding      = '-padding';
        $margin       = '-margins';
        $background   = '-background';
        $border       = '-borders';
        $borderradius = '-borderradius';

        $stylingSections = array();
        $sections        = array();
        $optprefix       = 'architect_config_';

        $thisSection = 'blueprint';

        $sections[ '_styling_general' ] = array(
          'title'      => 'Blueprint General Styling',
          'show_title' => false,
          'icon_class' => 'icon-large',
          'icon'       => 'el-icon-th-large',
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

            // TODO: Add shadows
            pzarc_redux_bg( $prefix . $thisSection . $background, $this->defaults ? '' : $_architect[ 'architect_config_' . $thisSection . '-selectors' ], $defaults[ $optprefix . $thisSection . $background ] ),
            pzarc_redux_padding( $prefix . $thisSection . $padding, $this->defaults ? '' : $_architect[ 'architect_config_' . $thisSection . '-selectors' ], $defaults[ $optprefix . $thisSection . $background ] ),
            pzarc_redux_margin( $prefix . $thisSection . $margin, $this->defaults ? '' : $_architect[ 'architect_config_' . $thisSection . '-selectors' ], $defaults[ $optprefix . $thisSection . $background ] ),
            pzarc_redux_borders( $prefix . $thisSection . $border, $this->defaults ? '' : $_architect[ 'architect_config_' . $thisSection . '-selectors' ], $defaults[ $optprefix . $thisSection . $background ] ),
            pzarc_redux_links( $prefix . $thisSection . $link, $this->defaults ? '' : $_architect[ 'architect_config_' . $thisSection . '-selectors' ], $defaults[ $optprefix . $thisSection . $background ] ),
            array(
              'id'     => $prefix . 'blueprint-end-section-blueprint',
              'type'   => 'section',
              'indent' => false,
            )
//            array(
//              'title'  => __( 'Custom CSS', 'pzarchitect' ),
//              'id'     => $prefix . 'blueprint-section-blueprint-custom-css',
//              'type'   => 'section',
//              'indent' => true,
//            )
//            array(
//              'title'    => __( 'Custom CSS', 'pzarchitect' ),
//              'id'       => $prefix . 'blueprint-custom-css',
//              'options'	=>	array('minLines'=> 25),
//              'type'     => 'ace_editor',
//              'mode'     => 'css',
//              'subtitle' => __( 'As a shorthand, you can prefix your CSS class with MYBLUEPRINT and Architect will substitute the correct class for this Blueprint. e.g. MYBLUEPRINT {border-radius:5px;}', 'pzarchitect' )
//              //              'subtitle' => __( 'This can be any CSS you\'d like to add to a page this blueprint is displayed on. It will ONLY load on the pages this blueprint is shown on, so will only impact those pages. However, if you have multiple blueprints on a page, this CSS could affect or be overriden by ther blueprints\' custom CSS.', 'pzarchitect' ),
//              //                'hint'  => array('content' => __('This is can be any CSS you\'d like to add to a page this blueprint is displayed on. It will ONLY load on the pages this blueprint is shown on, so will only impact those pages. However, if you have multiple blueprints on a page, this CSS could affect or be overriden by ther blueprints\' custom CSS.', 'pzarchitect')),
//            )
          )
        );

        $thisSection                 = 'page';
        $sections[ '_styling_page' ] = array(
          'title'      => 'Page',
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
            pzarc_redux_padding( $prefix . $thisSection . '_page-title' . $padding, $this->defaults ? '' : $_architect[ 'architect_config_' . $thisSection . '_page-title' . '-selectors' ], $defaults[ $optprefix . $thisSection . '_page-title' . $background ] ),
            pzarc_redux_margin( $prefix . $thisSection . '_page-title' . $margin, $this->defaults ? '' : $_architect[ 'architect_config_' . $thisSection . '_page-title' . '-selectors' ], $defaults[ $optprefix . $thisSection . '_page-title' . $background ] ),
            pzarc_redux_borders( $prefix . $thisSection . '_page-title' . $border, $this->defaults ? '' : $_architect[ 'architect_config_' . $thisSection . '_page-title' . '-selectors' ], $defaults[ $optprefix . $thisSection . '_page-title' . $background ] ),
            pzarc_redux_links( $prefix . $thisSection . '_page-title' . $link, $this->defaults ? '' : $_architect[ 'architect_config_' . $thisSection . '_page-title' . '-selectors' ], $defaults[ $optprefix . $thisSection . '_page-title' . $background ] ),
            array(
              'id'     => $prefix . 'blueprint-end-section-page-title',
              'type'   => 'section',
              'indent' => false,
            )
          )
        );


        $thisSection                             = 'sections';
        $sections[ '_styling_sections_wrapper' ] = array(
          'title'      => 'Panels wrapper',
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
//        $icons                                   = array(1 => 'el-icon-align-left',
//                                                         2 => 'el-icon-th',
//                                                         3 => 'el-icon-th-list');
//
//        $thisSection                         = 'section_1';
//        $sections[ '_styling_section_1' ]    = array(
//            'title'      => 'Section 1',
//            'show_title' => false,
//            'icon_class' => 'icon-large',
//            'icon'       => $icons[ 1 ],
//            'desc'       => 'Class: ' . $this->defaults ? '' : $_architect[ 'architect_config_' . $thisSection . '-selectors' ],
//            'fields'     => array(
//                pzarc_redux_bg($prefix . $thisSection . $background, $this->defaults ? '' : $_architect[ 'architect_config_' . $thisSection . '-selectors' ], $defaults[ $optprefix . $thisSection . $background ]),
//                pzarc_redux_padding($prefix . $thisSection . $padding, $this->defaults ? '' : $_architect[ 'architect_config_' . $thisSection . '-selectors' ], $defaults[ $optprefix . $thisSection . $padding ]),
//                pzarc_redux_margin($prefix . $thisSection . $margin, $this->defaults ? '' : $_architect[ 'architect_config_' . $thisSection . '-selectors' ], $defaults[ $optprefix . $thisSection . $margin ]),
//                pzarc_redux_borders($prefix . $thisSection . $border, $this->defaults ? '' : $_architect[ 'architect_config_' . $thisSection . '-selectors' ], $defaults[ $optprefix . $thisSection . $border ])
//            ),
//        );
//        $thisSection                         = 'section_2';
//        $sections[ '_styling_section_2' ]    = array(
//            'title'      => 'Section 2',
//            'show_title' => false,
//            'icon_class' => 'icon-large',
//            'icon'       => $icons[ 2 ],
//            'desc'       => 'Class: ' . $this->defaults ? '' : $_architect[ 'architect_config_' . $thisSection . '-selectors' ],
//            'fields'     => array(
//                pzarc_redux_bg($prefix . $thisSection . $background, $this->defaults ? '' : $_architect[ 'architect_config_' . $thisSection . '-selectors' ], $defaults[ $optprefix . $thisSection . $background ]),
//                pzarc_redux_padding($prefix . $thisSection . $padding, $this->defaults ? '' : $_architect[ 'architect_config_' . $thisSection . '-selectors' ], $defaults[ $optprefix . $thisSection . $padding ]),
//                pzarc_redux_margin($prefix . $thisSection . $margin, $this->defaults ? '' : $_architect[ 'architect_config_' . $thisSection . '-selectors' ], $defaults[ $optprefix . $thisSection . $margin ]),
//                pzarc_redux_borders($prefix . $thisSection . $border, $this->defaults ? '' : $_architect[ 'architect_config_' . $thisSection . '-selectors' ], $defaults[ $optprefix . $thisSection . $border ])
//            ),
//        );
//        $thisSection                         = 'section_3';
//        $sections[ '_styling_section_3' ]    = array(
//            'title'      => 'Section 3',
//            'show_title' => false,
//            'icon_class' => 'icon-large',
//            'icon'       => $icons[ 3 ],
//            'desc'       => 'Class: ' . $this->defaults ? '' : $_architect[ 'architect_config_' . $thisSection . '-selectors' ],
//            'fields'     => array(
//                pzarc_redux_bg($prefix . $thisSection . $background, $this->defaults ? '' : $_architect[ 'architect_config_' . $thisSection . '-selectors' ], $defaults[ $optprefix . $thisSection . $background ]),
//                pzarc_redux_padding($prefix . $thisSection . $padding, $this->defaults ? '' : $_architect[ 'architect_config_' . $thisSection . '-selectors' ], $defaults[ $optprefix . $thisSection . $padding ]),
//                pzarc_redux_margin($prefix . $thisSection . $margin, $this->defaults ? '' : $_architect[ 'architect_config_' . $thisSection . '-selectors' ], $defaults[ $optprefix . $thisSection . $margin ]),
//                pzarc_redux_borders($prefix . $thisSection . $border, $this->defaults ? '' : $_architect[ 'architect_config_' . $thisSection . '-selectors' ], $defaults[ $optprefix . $thisSection . $border ])
//            ),
//        );
        $thisSection                         = 'navigator';
        $sections[ '_styling_slidertabbed' ] = array(
          'title'      => 'Sliders & Tabbed',
          'show_title' => false,
          'icon_class' => 'icon-large',
          'icon'       => 'el-icon-website',
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
              'subtitle' => 'Class: ' . implode( ', ', array( '.pzarc-navigator .arc-slider-slide-nav-item.active' ) ),

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

        $thisSection                      = 'accordion-titles';
        $sections[ '_styling_accordion' ] = array(
          'id'         => 'accordion-css',
          'title'      => 'Accordion',
          'icon_class' => 'icon-large',
          'icon'       => 'el-icon-lines',
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
          'title'      => 'Tabular',
          'icon_class' => 'icon-large',
          'icon'       => 'el-icon-th-list',
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


        $sections[ '_styling_help' ] = array(
          'id'         => 'blueprint-styling-help',
          'title'      => 'Help',
          'icon_class' => 'icon-large',
          'icon'       => 'el-icon-question-sign',
          'fields'     => array(

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
        $metaboxes[ ]                = array(
          'id'         => 'blueprint-stylings',
          'title'      => 'Blueprint Styling',
          'post_types' => array( 'arc-blueprints' ),
          'sections'   => $sections,
          'position'   => 'normal',
          'priority'   => 'low',
          'sidebar'    => true

        );

        //pzdebug($metaboxes);


      }

      // Still need to return this, even if we did nothing.
      return $metaboxes;
    }

    function pzarc_mb_panels_layout( $metaboxes, $defaults_only = false ) {
      global $_architect_options;
      if ( empty( $_architect_options ) ) {
        $_architect_options = get_option( '_architect_options' );
      }

      $prefix      = '_panels_design_';
      $sections    = array();
      $sections[ ] = array(
        'title'      => __( 'Content Layout ', 'pzarchitect' ),
        'show_title' => false,
        'icon_class' => 'icon-large',
        'icon'       => 'el-icon-website',
        'fields'     => array(
          array(
            'title'    => __( 'Components to show', 'pzarchitect' ),
            'id'       => $prefix . 'components-to-show',
            'type'     => 'button_set',
            'multi'    => true,
            'subtitle' => __( 'Feature can be either the Featured Image of the post, or the Featured Video (added by Architect).', 'pzarchitect' ),
            'default'  => array( 'title', 'excerpt', 'meta1', 'image' ),
            'options'  => array(
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
            ),
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
            'code'         => draw_panel_layout(),
            'default_show' => false,
            'subtitle'     => __( 'Drag and drop to reposition and resize components', 'pzarchitect' ),
            'default'      => json_encode( array(
                                             'title'   => array( 'width' => 100, 'show' => true ),
                                             'meta1'   => array( 'width' => 100, 'show' => true ),
                                             'image'   => array( 'width' => 25, 'show' => true ),
                                             'excerpt' => array( 'width' => 75, 'show' => true ),
                                             //                                            'caption' => array('width' => 100, 'show' => false),
                                             'content' => array( 'width' => 100, 'show' => false ),
                                             'meta2'   => array( 'width' => 100, 'show' => false ),
                                             'meta3'   => array( 'width' => 100, 'show' => false ),
                                             'custom1' => array( 'width' => 100, 'show' => false ),
                                             'custom2' => array( 'width' => 100, 'show' => false ),
                                             'custom3' => array( 'width' => 100, 'show' => false )
                                           ) ),
            'hint'         => array(
              'title'   => __( 'Post layout', 'pzarchitect' ),
              'content' => __( 'Drag and drop to sort the order of your elements. <strong>Heights are fluid, so not indicative of how it will look on the page</strong>.', 'pzarchitect' )
            )
          ),
          array(
            'title'    => __( 'Feature type', 'pzarchitect' ),
            'id'       => '_panels_settings_feature-type',
            'type'     => 'button_set',
            'default'  => 'image',
            'options'  => array( 'image' => __( 'Images', 'pzarchitect' ), 'video' => __( 'Videos', 'pzarchitect' ) ),
            'subtitle' => __( 'Choose whether Feature is images or videos.', 'pzarchitect' ),
            'required' => array( $prefix . 'components-to-show', 'contains', 'image' ),
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
            'title'         => __( 'Number of custom fields', 'pzarchitect' ),
            'id'            => $prefix . 'custom-fields-count',
            'type'          => 'spinner',
            'default'       => 0,
            'min'           => '0',
            'max'           => '999',
            'step'          => '1',
            'display_value' => 'label',
            'required'      => array( $prefix . 'components-to-show', 'contains', 'custom' ),
            'subtitle'      => __( 'Each of the three Custom groups can have multiple custom fields. Enter the <strong>total</strong> number of custom fields, click Save/Update', 'pzarchitect' ),
            'hint'          => array(
              'title'   => __( 'Number of custom fields', 'pzarchitect' ),
              'content' => __( 'After selecting upto three custom field groups, you now need to set the total number of custom fields you will be displaying so Architect can create the settings tabs for each one.<br><br><strong>You will need to Publish/Update to see those new tabs.</strong>', 'pzarchitect' )
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
            'default'       => '100',
            'min'           => '1',
            'max'           => '100',
            'step'          => '1',
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
            'default'       => '0',
            'min'           => '0',
            'max'           => '100',
            'step'          => '1',
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
            'default'       => '0',
            'min'           => '0',
            'max'           => '100',
            'step'          => '1',
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
            'default'       => '15',
            'min'           => '5',
            'max'           => '100',
            'step'          => '1',
            'class'         => ' percent',
            'required'      => array(
              array( $prefix . 'feature-location', '!=', 'fill' ),
              array( $prefix . 'feature-location', '!=', 'float' ),
              array( $prefix . 'feature-location', '!=', 'components' ),
            ),
            'display_value' => 'label',
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
        )
      );

      /**
       * TITLES
       */

      $sections[ ] = array(
        'title'      => 'Titles',
        'show_title' => false,
        'icon_class' => 'icon-large',
        'icon'       => 'el-icon-font',
        'fields'     => array(
          array(
            'title'   => __( 'Title prefix', 'pzarchitect' ),
            'id'      => $prefix . 'title-prefix',
            'type'    => 'select',
            'select2' => array( 'allowClear' => false ),
            'default' => 'none',
            'class'   => ' horizontal',
            //'required' => array('show_advanced', 'equals', true),
            'options' => array(
              'none'                 => __( 'None', 'pzarchitect' ),
              'disc'                 => __( 'Disc', 'pzarchitect' ),
              'circle'               => __( 'Circle', 'pzarchitect' ),
              'square'               => __( 'Square', 'pzarchitect' ),
              'thumb'                => __( 'Thumbnail', 'pzarchitect' ),
              'decimal'              => __( 'Number', 'pzarchitect' ),
              'decimal-leading-zero' => __( 'Number with leading zero', 'pzarchitect' ),
              'lower-alpha'          => __( 'Alpha lower', 'pzarchitect' ),
              'upper-alpha'          => __( 'Alpha upper', 'pzarchitect' ),
              'lower-roman'          => __( 'Roman lower', 'pzarchitect' ),
              'upper-roman'          => __( 'Roman upper', 'pzarchitect' ),
              'lower-greek'          => __( 'Greek lower', 'pzarchitect' ),
              'upper-greek'          => __( 'Greek upper', 'pzarchitect' ),
              'lower-latin'          => __( 'Latin lower', 'pzarchitect' ),
              'upper-latin'          => __( 'Latin upper', 'pzarchitect' ),
              'armenian'             => __( 'Armenian', 'pzarchitect' ),
              'georgian'             => __( 'Georgian', 'pzarchitect' ),
            ),
          ),
          array(
            'id'             => $prefix . 'title-margins',
            'type'           => 'spacing',
            'mode'           => 'margin',
            'units'          => 'px',
            'units_extended' => 'false',
            'title'          => __( 'Title margins', 'pzarchitect' ),
            'desc'           => __( 'You must set a left margin on titles for bullets to show. This is in pixels', 'pzarchitect' ),
            'default'        => array(
              'margin-right' => '0',
              'margin-left'  => '20',
              'units'        => 'px',
            ),
            'top'            => false,
            'bottom'         => false,
            'left'           => true,
            'right'          => true,
            'required'       => array( '_panels_design_title-prefix', '!=', 'none' ),
          ),
          array(
            'title'         => __( 'Title thumbnail width', 'pzarchitect' ),
            'id'            => $prefix . 'title-thumb-width',
            'type'          => 'spinner',
            'default'       => '32',
            'min'           => '8',
            'max'           => '1000',
            'step'          => '1',
            'display_value' => 'label',
            'required'      => array( '_panels_design_title-prefix', '=', 'thumb' ),
            'hint'          => array( 'content' => __( '', 'pzarchitect' ) ),
          ),
          array(
            'title'    => __( 'Prefix separator', 'pzarchitect' ),
            'id'       => $prefix . 'title-bullet-separator',
            'type'     => 'text',
            'class'    => 'textbox-small',
            'default'  => '. ',
            'required' => array(
              array( '_panels_design_title-prefix', '!=', 'none' ),
              array( '_panels_design_title-prefix', '!=', 'disc' ),
              array( '_panels_design_title-prefix', '!=', 'circle' ),
              array( '_panels_design_title-prefix', '!=', 'square' ),
              array( '_panels_design_title-prefix', '!=', 'thumb' )
            )
          ),
          array(
            'title'   => __( 'Link titles', 'pzarchitect' ),
            'id'      => $prefix . 'link-titles',
            //            'cols'    => 4,
            'type'    => 'switch',
            'on'      => __( 'Yes', 'pzarchitect' ),
            'off'     => __( 'No', 'pzarchitect' ),
            'default' => true,
            'hint'    => array( 'content' => __( 'If enabled, clicking on the Title will take the viewer to the post.', 'pzarchitect' ) ),

            /// can't set defaults on checkboxes!
          ),
        )
      );

      /**
       * META
       */
      $sections[ ] = array(
        'title'      => __( 'Meta', 'pzarchitect' ),
        'show_title' => false,
        'icon_class' => 'icon-large',
        'icon'       => 'el-icon-calendar',
        'desc'       => __( 'Available tags are <span class="pzarc-text-highlight">%author%,   %email%,   %date%,   %categories%,   %tags,   %commentslink%,   %editlink%,   %id%</span>. For custom taxonomies, prefix with ct:. e.g. To display the Woo Testimonials category, you would use %ct:testimonial-category%.', 'pzarchitect' ) . '<br>' .
                        __( 'Allowed HTML tags:', 'pzarchitect' ) . ' p, br, span, strong & em<br><br>' .
                        __( 'Use shortcodes to add custom functions to meta. e.g. [add_to_cart id="%id%"]', 'pzarchitect' ) . '<br>' .
                        __( 'Note: Enclose any author related text in <span class="pzarc-text-highlight">//</span> to hide it when using excluded authors.', 'pzarchitect' ) . '<br>' .
                        __( 'Note: The email address will be encoded to prevent automated harvesting by spammers.', 'pzarchitect' ),
        'fields'     => array(
          // ======================================
          // META
          // ======================================
          array(
            'title'   => __( 'Meta1 config', 'pzarchitect' ),
            'id'      => $prefix . 'meta1-config',
            'type'    => 'textarea',
            'cols'    => 4,
            'rows'    => 2,
            'default' => '%date% //by// %author%',
          ),
          array(
            'title'   => __( 'Meta2 config', 'pzarchitect' ),
            'id'      => $prefix . 'meta2-config',
            'type'    => 'textarea',
            'rows'    => 2,
            'default' => __( 'Categories', 'pzarchitect' ) . ': %categories%   ' . __( 'Tags', 'pzarchitect' ) . ': %tags%',
          ),
          array(
            'title'   => __( 'Meta3 config', 'pzarchitect' ),
            'id'      => $prefix . 'meta3-config',
            'type'    => 'textarea',
            'rows'    => 2,
            'default' => '%commentslink%   %editlink%',
          ),
          array(
            'id'      => $prefix . 'meta-date-format',
            'title'   => __( 'Date format', 'pzarchitect' ),
            'type'    => 'text',
            'default' => 'l, F j, Y g:i a',
            'desc'    => __( 'See here for information on <a href="http://codex.wordpress.org/Formatting_Date_and_Time" target=_blank>formatting date and time</a>', 'pzarchitect' ),
          ),
          array(
            'title'    => __( 'Excluded authors', 'pzarchitect' ),
            'id'       => $prefix . 'excluded-authors',
            'type'     => 'select',
            'multi'    => true,
            'data'     => 'callback',
            //'required' => array('show_advanced', 'equals', true),
            //TODO: Findout how to pass parameters. currently that is doing nothing!
            'args'     => array( 'pzarc_get_authors', array( false, 0 ) ),
            'subtitle' => __( 'Select any authors here you want to exclude from showing when the %author% or %email% tag is used.', 'pzarchitect' )
          ),
        )
      );

      /**********
       * Content
       *********/
      // EXCERPTS
      $sections[ ] = array(
        'title'      => __( 'Body/excerpt', 'pzarchitect' ),
        'icon_class' => 'icon-large',
        'icon'       => 'el-icon-align-left',
        'fields'     => array(
          array(
            'title'    => __( 'Maximize body', 'pzarchitect' ),
            'id'       => $prefix . 'maximize-content',
            'type'     => 'switch',
            'on'       => __( 'Yes', 'pzarchitect' ),
            'off'      => __( 'No', 'pzarchitect' ),
            'default'  => true,
            //'required' => array('show_advanced', 'equals', true),
            'subtitle' => __( 'Make excerpt or body 100% width if no featured image.', 'pzarchitect' )
          ),
          array(
            'id'     => $prefix . 'excerpt-heading',
            'title'  => __( 'Excerpts', 'pzarchitect' ),
            'type'   => 'section',
            'indent' => true,
            'class'  => 'heading',
          ),
          array(
            'id'      => $prefix . 'excerpts-word-count',
            'title'   => __( 'Excerpt length (words)', 'pzarchitect' ),
            'type'    => 'spinner',
            'default' => 55,
            'min'     => '1',
            'max'     => '9999',
            'step'    => '1',
          ),
          array(
            'title'   => __( 'Truncation indicator', 'pzarchitect' ),
            'id'      => $prefix . 'readmore-truncation-indicator',
            'type'    => 'text',
            'class'   => 'textbox-small',
            'default' => '[...]',
          ),
          array(
            'title'    => __( 'Display entered excerpts only', 'pzarchitect' ),
            'id'       => $prefix . 'manual-excerpts',
            'type'     => 'switch',
            'on'       => __( 'Yes', 'pzarchitect' ),
            'off'      => __( 'No', 'pzarchitect' ),
            'default'  => false,
            //'required' => array('show_advanced', 'equals', true),
            'subtitle' => __( 'Only display excerpts that are actually entered in the Excerpt field of the post editor', 'pzarchitect' )
          ),
          array(
            'title'   => __( 'Read More', 'pzarchitect' ),
            'id'      => $prefix . 'readmore-text',
            'type'    => 'text',
            'class'   => 'textbox-medium',
            'default' => __( 'Read more', 'pzarchitect' ),
          ),
          array(
            'id'     => $prefix . 'content-responsive-heading',
            'title'  => __( 'Responsive', 'pzarchitect' ),
            'type'   => 'section',
            'indent' => true,
            //'required' => array('show_advanced', 'equals', true),
            //                  'class'    => 'heading',
          ),
          array(
            'id'       => $prefix . 'responsive-hide-content',
            'title'    => __( 'Hide Body at breakpoint', 'pzarchitect' ),
            'type'     => 'select',
            //'required' => array('show_advanced', 'equals', true),
            'options'  => array(
              'none' => __( 'None', 'pzarchitect' ),
              '2'    => __( 'Small screen ', 'pzarchitect' ) . $_architect_options[ 'architect_breakpoint_2' ][ 'width' ],
              '1'    => __( 'Medium screen ', 'pzarchitect' ) . $_architect_options[ 'architect_breakpoint_1' ][ 'width' ]
            ),
            'default'  => 'none',
            'subtitle' => __( 'Breakpoints can be changed in Architect Options', 'pzachitect' )
          ),
          array(
            'title'    => __( 'Use responsive font sizes', 'pzarchitect' ),
            'id'       => $prefix . 'use-responsive-font-size',
            'type'     => 'switch',
            'default'  => false,
            //'required' => array('show_advanced', 'equals', true),
            'subtitle' => __( 'Enabling this will override all other CSS for body/excerpt text', 'pzarchitect' )
          ),
          array(
            'id'              => $prefix . 'content-font-size-bp1',
            'title'           => __( 'Font size - large screen ', 'pzarchitect' ),
            'subtitle'        => $_architect_options[ 'architect_breakpoint_1' ][ 'width' ] . __( ' and above', 'pzarchitect' ),
            'required'        => array( $prefix . 'use-responsive-font-size', 'equals', true ),
            'type'            => 'typography',
            'text-decoration' => false,
            'font-variant'    => false,
            'text-transform'  => false,
            'font-family'     => false,
            'font-size'       => true,
            'font-weight'     => false,
            'font-style'      => false,
            'font-backup'     => false,
            'google'          => false,
            'subsets'         => false,
            'custom_fonts'    => false,
            'text-align'      => false,
            //'text-shadow'       => false, // false
            'color'           => false,
            'preview'         => false,
            'line-height'     => true,
            'word-spacing'    => false,
            'letter-spacing'  => false,
          ),
          array(
            'id'              => $prefix . 'content-font-size-bp2',
            'title'           => __( 'Font size - medium screen ', 'pzarchitect' ),
            'subtitle'        => $_architect_options[ 'architect_breakpoint_2' ][ 'width' ] . ' to ' . $_architect_options[ 'architect_breakpoint_1' ][ 'width' ],
            'required'        => array( $prefix . 'use-responsive-font-size', 'equals', true ),
            'type'            => 'typography',
            'text-decoration' => false,
            'font-variant'    => false,
            'text-transform'  => false,
            'font-family'     => false,
            'font-size'       => true,
            'font-weight'     => false,
            'font-style'      => false,
            'font-backup'     => false,
            'google'          => false,
            'subsets'         => false,
            'custom_fonts'    => false,
            'text-align'      => false,
            //'text-shadow'       => false, // false
            'color'           => false,
            'preview'         => false,
            'line-height'     => true,
            'word-spacing'    => false,
            'letter-spacing'  => false,
          ),
          array(
            'id'              => $prefix . 'content-font-size-bp3',
            'title'           => __( 'Font size - small screen ', 'pzarchitect' ),
            'subtitle'        => $_architect_options[ 'architect_breakpoint_2' ][ 'width' ] . ' and below',
            'required'        => array( $prefix . 'use-responsive-font-size', 'equals', true ),
            'type'            => 'typography',
            'text-decoration' => false,
            'font-variant'    => false,
            'text-transform'  => false,
            'font-family'     => false,
            'font-size'       => true,
            'font-weight'     => false,
            'font-style'      => false,
            'font-backup'     => false,
            'google'          => false,
            'subsets'         => false,
            'custom_fonts'    => false,
            'text-align'      => false,
            //'text-shadow'       => false, // false
            'color'           => false,
            'preview'         => false,
            'line-height'     => true,
            'word-spacing'    => false,
            'letter-spacing'  => false,
          ),
          //            array(
          //                'id'      => $prefix . 'content-font-size-range',
          //                'title'   => __('Font size range', 'pzarchitect'),
          //                'type'    => 'slider',
          //                'min'     => 5,
          //                'max'     => 24,
          //                'step'    => 1,
          //                'handles' => 2,
          //                'default' => array(1=>10,2=>16)
          //            )
        )


      );
      /***************************
       * FEATURE IMAGES
       ***************************/
      $sections[ ] = array(
        'title'      => 'Featured Images/Videos',
        'icon_class' => 'icon-large',
        'icon'       => 'el-icon-picture',
        'subtitle'   => __( 'Left and right margins are included in the image width in the designer. e.g if Feature width is 25% and right margin is 3%, Feature width will be adjusted to 22%', 'pzarchitect' ),
        'fields'     => array(
          array(
            'title'    => __( 'Image cropping', 'pzarchitect' ),
            'id'       => '_panels_settings_image-focal-point',
            'type'     => 'select',
            'default'  => 'respect',
            'required' => array( '_panels_settings_feature-type', '=', 'image' ),
            'options'  => array(
              'respect' => __( 'Respect focal point', 'pzarchitect' ),
              'centre'  => __( 'Centre focal point', 'pzarchitect' ),
              'none'    => __( 'Crop to centre', 'pzarchitect' ),
              'scale'   => __( 'Preserve aspect, fit width', 'pzarchitect' ),
              //                      'shrink'  => __('Shrink', 'pzarchitect')
            )
          ),
          array(
            'title'    => __( 'Use embedded images', 'pzarchitect' ),
            'id'       => '_panels_settings_use-embedded-images',
            'type'     => 'switch',
            'on'       => __( 'Yes', 'pzarchitect' ),
            'off'      => __( 'No', 'pzarchitect' ),
            'default'  => false,
            'required' => array(
              //array('show_advanced', 'equals', true),
              array( '_panels_settings_feature-type', '=', 'image' ),
            ),
            'subtitle' => __( 'Enable this to use the first found attached image in the body of the post if no featured image is set.', 'pzarchitect' )
          ),
          array(
            'title'    => __( 'Use retina images', 'pzarchitect' ),
            'id'       => '_panels_settings_use-retina-images',
            'type'     => 'switch',
            'on'       => __( 'Yes', 'pzarchitect' ),
            'off'      => __( 'No', 'pzarchitect' ),
            'default'  => true,
            'required' => array(
              //array('show_advanced', 'equals', true),
              array( '_panels_settings_feature-type', '=', 'image' ),
            ),
            'hint'     => array(
              'title'   => __( 'Use retina images', 'pzarchitect' ),
              'content' => __( 'If enabled, a retina version of the featured image will be created and displayed. <strong>Ensure the global setting in Architect Options is on as well</strong>. NOTE: This will make your site load slower on retina devices, so you may only want consider which panels you have it enabled on.', 'pzarchitect' )
            )
          ),
          array(
            'title'    => __( 'Filler image', 'pzarchitect' ),
            'id'       => $prefix . 'use-filler-image-source',
            'type'     => 'select',
            'default'  => 'none',
            'subtitle' => __( 'Use a filler image from lorempixel if post has no image.', 'pzarchitect' ),
            'options'  => array(
              'none'       => 'None',
              'lorempixel' => __( 'Random Picture', 'pzarchitect' ),
              'abstract'   => ucfirst( 'abstract' ),
              'animals'    => ucfirst( 'animals' ),
              'business'   => ucfirst( 'business' ),
              'cats'       => ucfirst( 'cats' ),
              'city'       => ucfirst( 'city' ),
              'food'       => ucfirst( 'food' ),
              'nightlife'  => ucfirst( 'nightlife' ),
              'fashion'    => ucfirst( 'fashion' ),
              'people'     => ucfirst( 'people' ),
              'nature'     => ucfirst( 'nature' ),
              'sports'     => ucfirst( 'sports' ),
              'technics'   => ucfirst( 'transport' ),
            ),
            'required' => array(
              array( '_panels_settings_feature-type', '=', 'image' ),
            ),
          ),
          // TODO: This will be for proper masonry galleries
          //              array(
          //                  'id'       => $prefix . 'image-shrinkage',
          //                  'title'    => __('Shrink images', 'pzarchitect'),
          //                  'type'     => 'slider',
          //                  'display_value' => 'label',
          //                  'default'       => '100',
          //                  'min'           => '0',
          //                  'max'           => '100',
          //                  'step'          => '5',
          //                  'units'         => '%',
          //                  'required' => array(
          //                      array('_panels_settings_image-focal-point', '=', 'shrink'),
          //                      array('_panels_settings_feature-type', '=', 'image')
          //                  ),
          //              ),
          array(
            'id'       => $prefix . 'image-max-dimensions',
            'title'    => __( 'Maximum dimensions', 'pzarchitect' ),
            'type'     => 'dimensions',
            'desc'     => __( 'The displayed width of the image is determined by it\'s size in the Content Layout designer. This setting is used limit the size of the image used.', 'pzarchitect' ),
            'units'    => 'px',
            'default'  => array( 'width' => '400', 'height' => '300' ),
            'required' => array(
              array( '_panels_settings_feature-type', '=', 'image' )
            ),
          ),
          array(
            'title'    => __( 'Effect on screen resize', 'pzarchitect' ),
            'id'       => $prefix . 'background-image-resize',
            'type'     => 'button_set',
            'subtitle' => __( 'Scale Vertically & Horizontally ', 'pzarchitect' ) . '<br>' . __( 'Trim horizontally, fill height', 'pzarchitect' ) . '<br>' . __( 'None uses default image sizing settings', 'pzarchitect' ),
            'options'  => array(
              'scale'            => __( 'Scale', 'pzarchitect' ),
              'trim'             => __( 'Trim', 'pzarchitect' ),
              'no-resize-effect' => __( 'None', 'pzarchitect' )
            ),
            'required' => array(
              //array('show_advanced', 'equals', true),
              array( '_panels_settings_feature-type', '=', 'image' ),
              array( '_panels_design_feature-location', '=', 'fill' ),
            ),
            'default'  => 'scale',
          ),
          array(
            'id'             => $prefix . 'image-spacing',
            'type'           => 'spacing',
            'mode'           => 'margin',
            'units'          => '%',
            'units_extended' => 'false',
            'title'          => __( 'Margins', 'pzarchitect' ),
            'default'        => array(
              'margin-top'    => '0',
              'margin-right'  => '0',
              'margin-bottom' => '0',
              'margin-left'   => '0',
              'units'         => '%',
            )
          ),
          array(
            'title'    => __( 'Link to', 'pzarchitect' ),
            'id'       => $prefix . 'link-image',
            'type'     => 'button_set',
            'options'  => array(
              'none'     => __( 'None', 'pzarchitect' ),
              'page'     => __( 'Post', 'pzarchitect' ),
              'image'    => __( 'Attachment page', 'pzarchitect' ),
              'original' => __( 'Lightbox', 'pzarchitect' ),
              'url'      => __( 'Specific URL', 'pzarchitect' ),
              // 'destination-url'=> __('Destination URL','pzarchtiect')
            ),
            'default'  => 'page',
            'required' => array( '_panels_settings_feature-type', '=', 'image' ),
            'subtitle' => __( 'Set what happens when a viewer clicks on the image', 'pzazrchitect' )
          ),
          array(
            'title'    => __( 'Specific URL', 'pzarchitect' ),
            'id'       => $prefix . 'link-image-url',
            'type'     => 'text',
            'required' => array(
              array( $prefix . 'link-image', 'equals', 'url' ),
              array( '_panels_settings_feature-type', '=', 'image' )
            ),
            'validate' => 'url',
            'subtitle' => __( 'Enter the URL that all images will link to', 'pzazrchitect' )
          ),
          array(
            'title'    => __( 'Specific URL tooltip', 'pzarchitect' ),
            'id'       => $prefix . 'link-image-url-tooltip',
            'type'     => 'text',
            'required' => array(
              array( $prefix . 'link-image', 'equals', 'url' ),
              array( '_panels_settings_feature-type', '=', 'image' )
            ),
            'subtitle' => __( 'Enter the text that appears when the user hovers over the link', 'pzazrchitect' )
          ),
          array(
            'title'    => __( 'Image Captions', 'pzarchitect' ),
            'id'       => $prefix . 'image-captions',
            'type'     => 'switch',
            'on'       => __( 'Yes', 'pzarchitect' ),
            'off'      => __( 'No', 'pzarchitect' ),
            'default'  => false,
            'required' => array( '_panels_settings_feature-type', '=', 'image' ),
          ),
          array(
            'title'    => __( 'Centre feature', 'pzarchitect' ),
            'id'       => $prefix . 'centre-image',
            'type'     => 'switch',
            'on'       => __( 'Yes', 'pzarchitect' ),
            'off'      => __( 'No', 'pzarchitect' ),
            'default'  => false,
            'required' => array(
              //array('show_advanced', 'equals', true),
              array( '_panels_settings_feature-type', '=', 'image' ),
            ),
            'subtitle' => __( 'Centres the image horizontally. It is best to display it on its own row, and the other components to be 100% wide.', 'pzarchitect' )
          ),
          //              array(
          //                  'id'            => $prefix . 'image-quality',
          //                  'title'         => __('Image quality', 'pzarchitect'),
          //                  'type'          => 'slider',
          //                  'display_value' => 'label',
          //                  'default'       => '75',
          //                  'min'           => '20',
          //                  'max'           => '100',
          //                  'step'          => '1',
          //                  'units'         => '%',
          //                  'hint'          => array('content' => 'Quality to use when processing images'),
          //                  'required'      => array('_panels_settings_feature-type', '=', 'image'),
          //
          //              ),
        )
      );


      /**
       * CUSTOM FIELDS
       * Why are these here even though they are somewhat content related. They're not choosing the content itself. Yes they do limit the usablity of the panel. Partly this came about because of the way WPdoesn't bind custom fields to specific content types.
       */
      if ( ! empty( $_GET[ 'post' ] ) ) {
        $thispostmeta = get_post_meta( $_GET[ 'post' ] );
        $cfcount      = ( ! empty( $thispostmeta[ '_panels_design_custom-fields-count' ][ 0 ] ) ? $thispostmeta[ '_panels_design_custom-fields-count' ][ 0 ] : 0 );

        $pzarc_custom_fields = pzarc_get_custom_fields();

        for ( $i = 1; $i <= $cfcount; $i ++ ) {
          $cfname      = 'Custom field ' . $i . ( ! empty( $thispostmeta[ '_panels_design_cfield-' . $i . '-name' ][ 0 ] ) ? ': <br>' . $thispostmeta[ '_panels_design_cfield-' . $i . '-name' ][ 0 ] : '' );
          $sections[ ] = array(
            'title'      => $cfname,
            'icon_class' => 'icon-large',
            'icon'       => 'el-icon-wrench',
            'desc'       => __( 'Note: Only fields with content will show on the Blueprint.', 'pzarchitect' ),
            'fields'     => array(
              array(
                'title'   => __( 'Show in custom field group', 'pzarchitect' ),
                'id'      => $prefix . 'cfield-' . $i . '-group',
                'type'    => 'button_set',
                'default' => 'custom1',
                'options' => array(
                  'custom1' => __( 'Custom 1', 'pzarchitect' ),
                  'custom2' => __( 'Custom 2', 'pzarchitect' ),
                  'custom3' => __( 'Custom 3', 'pzarchitect' )
                )
              ),
              array(
                'title'    => __( 'Field name', 'pzarchitect' ),
                'id'       => $prefix . 'cfield-' . $i . '-name',
                'type'     => 'select',
                //                'data'     => 'callback',
                //                'args'     => array( 'pzarc_get_custom_fields' ),
                'options'  => $pzarc_custom_fields,
                'subtitle' => __( 'If a custom field is not shown in the dropdown, it is because it has no data yet.', 'pzarchitect' )

              ),
              array(
                'title'   => __( 'Field type', 'pzarchitect' ),
                'id'      => $prefix . 'cfield-' . $i . '-field-type',
                'type'    => 'button_set',
                'default' => 'text',
                'options' => array( 'text' => 'Text', 'image' => 'Image', 'date' => 'Date', 'number' => 'Number' )

              ),
              array(
                'id'       => $prefix . 'cfield-' . $i . '-date-format',
                'title'    => __( 'Date format', 'pzarchitect' ),
                'type'     => 'text',
                'default'  => 'l, F j, Y g:i a',
                'desc'     => __( 'Visit here for information on <a href="http://codex.wordpress.org/Formatting_Date_and_Time" target=_blank>formatting date and time</a>', 'pzarchitect' ),
                'required' => array( $prefix . 'cfield-' . $i . '-field-type', '=', 'date' ),
              ),
              array(
                'id'            => $prefix . 'cfield-' . $i . '-number-decimals',
                'title'         => __( 'Decimals', 'pzarchitect' ),
                'type'          => 'spinner',
                'default'       => 0,
                'min'           => '0',
                'max'           => '100',
                'step'          => '1',
                'display_value' => 'label',
                'subtitle'      => __( 'Number of decimal places.', 'pzarchitect' ),
                'required'      => array( $prefix . 'cfield-' . $i . '-field-type', '=', 'number' ),
              ),
              array(
                'id'       => $prefix . 'cfield-' . $i . '-number-decimal-char',
                'title'    => __( 'Decimal point character', 'pzarchitect' ),
                'type'     => 'text',
                'default'  => '.',
                'required' => array( $prefix . 'cfield-' . $i . '-field-type', '=', 'number' ),
              ),
              array(
                'id'       => $prefix . 'cfield-' . $i . '-number-thousands-separator',
                'title'    => __( 'Thousands separator', 'pzarchitect' ),
                'type'     => 'text',
                'default'  => ',',
                'required' => array( $prefix . 'cfield-' . $i . '-field-type', '=', 'number' ),
              ),
              array(
                'title'    => __( 'Wrapper tag', 'pzarchitect' ),
                'id'       => $prefix . 'cfield-' . $i . '-wrapper-tag',
                'type'     => 'select',
                'default'  => 'div',
                'options'  => array(
                  'div'  => 'div',
                  'p'    => 'p',
                  'span' => 'span',
                  'h1'   => 'h1',
                  'h2'   => 'h2',
                  'h3'   => 'h3',
                  'h4'   => 'h4',
                  'h5'   => 'h5',
                  'h6'   => 'h6',
                ),
                'subtitle' => __( 'Select the wrapper element for this custom field', 'pzarchitect' )

              ),
              // THis wasn't being added, plus we know the name of the field
              //              array(
              //                'id'    => $prefix . 'cfield-' . $i . '-class-name',
              //                'title' => __( 'Add class name', 'pzarchitect' ),
              //                'type'  => 'text',
              //              ),
              array(
                'title'    => __( 'Link field', 'pzarchitect' ),
                'id'       => $prefix . 'cfield-' . $i . '-link-field',
                'type'     => 'select',
                //                'data'     => 'callback',
                //                'args'     => array( 'pzarc_get_custom_fields' ),
                'options'  => $pzarc_custom_fields,
                'subtitle' => 'Select a custom field that contains URLs you want to use as the link',
              ),
              array(
                'title' => __( 'Prefix text', 'pzarchitect' ),
                'id'    => $prefix . 'cfield-' . $i . '-prefix-text',
                'type'  => 'text',
              ),
              array(
                'title' => __( 'Prefix image', 'pzarchitect' ),
                'id'    => $prefix . 'cfield-' . $i . '-prefix-image',
                'type'  => 'media',
              ),
              array(
                'title' => __( 'Suffix text', 'pzarchitect' ),
                'id'    => $prefix . 'cfield-' . $i . '-suffix-text',
                'type'  => 'text',
              ),
              array(
                'title' => __( 'Suffix image', 'pzarchitect' ),
                'id'    => $prefix . 'cfield-' . $i . '-suffix-image',
                'type'  => 'media',
              ),
              array(
                'title'   => __( 'Prefix/suffix images width', 'pzarchitect' ),
                'id'      => $prefix . 'cfield-' . $i . '-ps-images-width',
                'type'    => 'dimensions',
                'height'  => false,
                'default' => array( 'width' => '32px' ),
                'units'   => 'px'
              ),
              array(
                'title'   => __( 'Prefix/suffix images height', 'pzarchitect' ),
                'id'      => $prefix . 'cfield-' . $i . '-ps-images-height',
                'type'    => 'dimensions',
                'width'   => false,
                'default' => array( 'height' => '32px' ),
                'units'   => 'px'
              ),
            )
          );
        }
      }

      $sections[ ] = array(
        'title'      => __( 'Help', 'pzarchitect' ),
        'icon_class' => 'icon-large',
        'icon'       => 'el-icon-question-sign',
        'fields'     => array(
//              array(
//                  'title'    => __('Panels videos', 'pzarchitect'),
//                  'id'       => $prefix . 'help-panels-videos',
//                  'subtitle' => __('Internet connection required'),
//                  'type'     => 'raw',
//                  'class'    => 'plain',
//                  'markdown' => false,
//                  'content'  => ($defaults_only ? '' : @file_get_contents('https://s3.amazonaws.com/341public/architect/panels-videos.html')),
//              ),
array(
  'title'    => __( 'Online documentation', 'pzarchitect' ),
  'id'       => $prefix . 'help-panels-online-docs',
  'type'     => 'raw',
  'markdown' => false,
  'content'  => '<a href="http://architect4wp.com/codex-listings/" target=_blank>' . __( 'Architect Online Documentation', 'pzarchitect' ) . '</a><br>' . __( 'This is a growing resource. Please check back regularly.', 'pzarchitect' )

),
        )
      );

      // Create the metaboxes


      $metaboxes[ ] = array(
        'id'         => 'panels-design',
        'title'      => __( 'Content Layout: Setup the layout of the content itself.', 'pzarchitect' ),
        'post_types' => array( 'arc-blueprints' ),
        'sections'   => $sections,
        'position'   => 'normal',
        'priority'   => 'low',
        'sidebar'    => false

      );

      return $metaboxes;

    }

    /**
     * STYLING
     *
     * @param $metaboxes
     *
     * @return array
     */
    function pzarc_mb_panels_styling( $metaboxes, $defaults_only = false ) {

      global $_architect;
      global $_architect_options;
      if ( empty( $_architect_options ) ) {
        $_architect_options = get_option( '_architect_options' );
      }

      if ( empty( $_architect ) ) {
        $_architect = get_option( '_architect' );
      }
      if ( ! empty( $_architect_options[ 'architect_enable_styling' ] ) ) {
        $defaults = get_option( '_architect' );
        $prefix   = '_panels_styling_';

        $font       = '-font';
        $link       = '-links';
        $padding    = '-padding';
        $margin     = '-margin';
        $background = '-background';
        $border     = '-borders';

        $stylingSections = array();
        $sections        = array();
        $optprefix       = 'architect_config_';
//  $stylingSections[ ] = array(
//          'title'      => 'Styling',
//          'icon_class' => 'icon-large',
//          'icon'       => 'el-icon-brush',
//          'hint' => __('Architect uses standard WordPress class names as much as possible, so your Architect Blueprints will inherit styling from your theme if it uses these. Below you can add your own styling and classes. Enter CSS declarations, such as: background:#123; color:#abc; font-size:1.6em; padding:1%;', 'pzarchitect') . '<br/>' . __('As much as possible, use fluid units (%,em) if you want to ensure maximum responsiveness.', 'pzarchitect') . '<br/>' .
//                  __('The base font size is 10px. So, for example, to get a font size of 14px, use 1.4em. Even better is using relative ems i.e. rem.'),
//
//          'fields'     => array(
        $xsections[ ] = array(
          'title'      => __( 'Styling', 'pzarchitect' ),
          'show_title' => false,
          'icon_class' => 'icon-large',
          'icon'       => 'el-icon-info-sign',
          'fields'     => array(
            array(
              'title'    => __( 'Styling Panels', 'pzarchitect' ),
              'id'       => $prefix . 'styling-panels',
              'type'     => 'info',
              'subtitle' => __( 'To style panels...', 'pzarchitect' ),
            )

          )
        );

        /**
         * GENERAL
         */
        $sections[ ] = array(
          'title'      => __( 'Content General Styling', 'pzarchitect' ),
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

        /**
         * TITLES
         */
        // architect_config_entry-title-font-margin

        $sections[ ] = array(
          'title'      => __( 'Titles', 'pzarchitect' ),
          'show_title' => false,
          'icon_class' => 'icon-large',
          'icon'       => 'el-icon' . $font,
          'desc'       => 'Class: .entry-title',
          'fields'     => pzarc_fields(
            pzarc_redux_font( $prefix . 'entry-title' . $font, array( '.entry-title' ), $defaults[ $optprefix . 'entry-title' . $font ] ),
            pzarc_redux_bg( $prefix . 'entry-title' . $font . $background, array( '.entry-title' ), $defaults[ $optprefix . 'entry-title' . $font . $background ] ),
            pzarc_redux_padding( $prefix . 'entry-title' . $font . $padding, array( '.entry-title' ), $defaults[ $optprefix . 'entry-title' . $font . $padding ] ),
            pzarc_redux_margin( $prefix . 'entry-title' . $font . $margin, array( '.entry-title' ), $defaults[ $optprefix . 'entry-title' . $font . $margin ], 'tb' ),
            pzarc_redux_borders( $prefix . 'entry-title' . $border, array( '.entry-title' ), $defaults[ $optprefix . 'entry-title' . $border ] ),
            pzarc_redux_links( $prefix . 'entry-title' . $font . $link, array( '.entry-title a' ), $defaults[ $optprefix . 'entry-title' . $font . $link ] )
          ),
        );

        /**
         * META
         */
        $sections[ ] = array(
          'title'      => __( 'Meta', 'pzarchitect' ),
          'show_title' => false,
          'icon_class' => 'icon-large',
          'icon'       => 'el-icon-calendar',
          'desc'       => 'Class: .entry_meta',
          'fields'     => pzarc_fields(
            pzarc_redux_font( $prefix . 'entry-meta' . $font, array( '.entry-meta' ), $defaults[ $optprefix . 'entry-meta' . $font ] ),
            pzarc_redux_bg( $prefix . 'entry-meta' . $font . $background, array( '.entry-meta' ), $defaults[ $optprefix . 'entry-meta' . $font . $background ] ),
            pzarc_redux_padding( $prefix . 'entry-meta' . $font . $padding, array( '.entry-meta' ), $defaults[ $optprefix . 'entry-meta' . $font . $padding ] ),
            pzarc_redux_margin( $prefix . 'entry-meta' . $font . $margin, array( '.entry-meta' ), $defaults[ $optprefix . 'entry-meta' . $font . $margin ], 'tb' ),
            pzarc_redux_links( $prefix . 'entry-meta' . $font . $link, array( '.entry-meta a' ), $defaults[ $optprefix . 'entry-meta' . $font . $link ] )
          )
        );

        /**
         * CONTENT
         */
        $sections[ ] = array(
          'title'      => __( 'Body content', 'pzarchitect' ),
          'show_title' => false,
          'icon_class' => 'icon-large',
          'icon'       => 'el-icon-align-left',
          'fields'     => pzarc_fields(
            array(
              'title'  => __( 'Full content', 'pzarc' ),
              'id'     => $prefix . 'entry-content',
              'type'   => 'section',
              'indent' => true,
              'class'  => 'heading',
            ),
            pzarc_redux_font( $prefix . 'entry-content' . $font, array( '.entry-content' ), $defaults[ $optprefix . 'entry-content' . $font ] ),
            pzarc_redux_bg( $prefix . 'entry-content' . $font . $background, array( '.entry-content' ), $defaults[ $optprefix . 'entry-content' . $font . $background ] ),
            pzarc_redux_padding( $prefix . 'entry-content' . $font . $padding, array( '.entry-content' ), $defaults[ $optprefix . 'entry-content' . $font . $padding ] ),
            pzarc_redux_margin( $prefix . 'entry-content' . $font . $margin, array( '.entry-content' ), $defaults[ $optprefix . 'entry-content' . $font . $margin ], 'tb' ),
            pzarc_redux_borders( $prefix . 'entry-content' . $border, array( '.entry-content' ), $defaults[ $optprefix . 'entry-content' . $border ] ),
            pzarc_redux_links( $prefix . 'entry-content' . $font . $link, array( '.entry-content a' ), $defaults[ $optprefix . 'entry-content' . $font . $link ] ),
            array(
              'title'  => __( 'Content paragraphs', 'pzarc' ),
              'id'     => $prefix . 'entry-content-p',
              'desc'   => 'Class: .entry-content p',
              'type'   => 'section',
              'indent' => true,
              'class'  => 'heading',
            ),
            pzarc_redux_font( $prefix . 'entry-contentp' . $font, array( '.entry-content' ), $defaults[ $optprefix . 'entry-content' . $font ] ),
            pzarc_redux_padding( $prefix . 'entry-contentp' . $font . $padding, array( '.entry-content p' ), $defaults[ $optprefix . 'entry-contentp' . $font . $padding ] ),
            pzarc_redux_margin( $prefix . 'entry-contentp' . $font . $margin, array( '.entry-content p' ), $defaults[ $optprefix . 'entry-contentp' . $font . $margin ] )
          )
        );

        $sections[ ] = array(
          'title'      => __( 'Excerpts', 'pzarchitect' ),
          'show_title' => false,
          'icon_class' => 'icon-large',
          'icon'       => 'el-icon-align-left',
          'fields'     => pzarc_fields(
            array(
              'title'  => __( 'Excerpt', 'pzarchitect' ),
              'id'     => $prefix . 'entry-excerpt',
              'type'   => 'section',
              'indent' => true,
              'class'  => 'heading',
            ),
            pzarc_redux_font( $prefix . 'entry-excerpt' . $font, array( '.entry-excerpt' ), $defaults[ $optprefix . 'entry-excerpt' . $font ] ),
            pzarc_redux_bg( $prefix . 'entry-excerpt' . $font . $background, array( '.entry-excerpt' ), $defaults[ $optprefix . 'entry-excerpt' . $font . $background ] ),
            pzarc_redux_padding( $prefix . 'entry-excerpt' . $font . $padding, array( '.entry-excerpt' ), $defaults[ $optprefix . 'entry-excerpt' . $font . $padding ] ),
            pzarc_redux_margin( $prefix . 'entry-excerpt' . $font . $margin, array( '.entry-excerpt' ), $defaults[ $optprefix . 'entry-excerpt' . $font . $margin ], 'tb' ),
            pzarc_redux_borders( $prefix . 'entry-excerpt' . $border, array( '.entry-excerpt' ), $defaults[ $optprefix . 'entry-excerpt' . $border ] ),
            pzarc_redux_links( $prefix . 'entry-excerpt' . $font . $link, array( '.entry-excerpt a' ), $defaults[ $optprefix . 'entry-excerpt' . $font . $link ] ),
            array(
              'title'  => __( 'Excerpt paragraphs', 'pzarc' ),
              'id'     => $prefix . 'entry-excerptp',
              'desc'   => 'Class: .entry-excerpt p',
              'type'   => 'section',
              'indent' => true,
              'class'  => 'heading',
            ),
            pzarc_redux_font( $prefix . 'entry-excerptp' . $font, array( '.entry-excerpt p' ), $defaults[ $optprefix . 'entry-excerptp' . $font ] ),
            pzarc_redux_padding( $prefix . 'entry-excerptp' . $font . $padding, array( '.entry-excerpt p' ), $defaults[ $optprefix . 'entry-excerptp' . $font . $padding ] ),
            pzarc_redux_margin( $prefix . 'entry-excerptp' . $font . $margin, array( '.entry-excerpt p' ), $defaults[ $optprefix . 'entry-excerptp' . $font . $margin ] ),
            array(
              'title'  => __( 'Read more', 'pzarchitect' ),
              'id'     => $prefix . 'entry-readmore',
              'type'   => 'section',
              'indent' => true,
              'class'  => 'heading',
              'hint'   => array( 'content' => 'Class: a.pzarc_readmore' ),
            ),
            pzarc_redux_font( $prefix . 'entry-readmore' . $font, array( '.readmore' ), $defaults[ $optprefix . 'entry-readmore' . $font ] ),
            pzarc_redux_bg( $prefix . 'entry-readmore' . $font . $background, array( '.readmore' ), $defaults[ $optprefix . 'entry-readmore' . $font . $background ] ),
            pzarc_redux_padding( $prefix . 'entry-readmore' . $font . $padding, array( '.readmore' ), $defaults[ $optprefix . 'entry-readmore' . $font . $padding ] ),
            pzarc_redux_links( $prefix . 'entry-readmore' . $font . $link, array( 'a.readmore' ), $defaults[ $optprefix . 'entry-readmore' . $font . $link ] )
          )
        );
        /**
         * FEATURED IMAGE
         */
        $sections[ ] = array(
          'title'      => __( 'Featured image', 'pzarchitect' ),
          'show_title' => false,
          'icon_class' => 'icon-large',
          'icon'       => 'el-icon-picture',
          'fields'     => pzarc_fields(
            array(
              'title'  => __( 'Image', 'pzarchitect' ),
              'id'     => $prefix . 'entry-image',
              'type'   => 'section',
              'indent' => true,
              'class'  => 'heading',
              'hint'   => array( 'content' => 'Class: figure.entry-thumbnail' ),
              //     'hint'    => __('Format the entry featured image', 'pzarchitect')
            ),
            array(
              'title'                 => __( 'Background', 'pzarchitect' ),
              'id'                    => $prefix . 'entry-image' . $background,
              'type'                  => 'spectrum',
              'background-image'      => false,
              'background-repeat'     => false,
              'background-size'       => false,
              'background-attachment' => false,
              'background-position'   => false,
              'preview'               => false,
              'output'                => array( '.pzarc_entry_featured_image' ),
              'default'               => $defaults[ $optprefix . 'entry-image' . $background ]
            ),
            array(
              'title'   => __( 'Border', 'pzarchitect' ),
              'id'      => $prefix . 'entry-image' . $border,
              'type'    => 'border',
              'all'     => false,
              'output'  => array( '.pzarc_entry_featured_image' ),
              'default' => $defaults[ $optprefix . 'entry-image' . $border ]
            ),
            array(
              'title'   => __( 'Padding', 'pzarchitect' ),
              'id'      => $prefix . 'entry-image' . $padding,
              'mode'    => 'padding',
              'type'    => 'spacing',
              'units'   => array( 'px', '%' ),
              'default' => $defaults[ $optprefix . 'entry-image' . $padding ]
            ),
            array(
              'title'   => __( 'Margin', 'pzarchitect' ),
              'id'      => $prefix . 'entry-image' . $margin,
              'mode'    => 'margin',
              'type'    => 'spacing',
              'units'   => array( 'px', '%' ),
              'default' => $defaults[ $optprefix . 'entry-image' . $margin ],
              'top'     => true,
              'bottom'  => true,
              'left'    => false,
              'right'   => false
            ),
            array(
              'title'  => __( 'Caption', 'pzarchitect' ),
              'id'     => $prefix . 'entry-image-caption',
              'type'   => 'section',
              'indent' => true,
              'class'  => 'heading',
            ),
            pzarc_redux_font( $prefix . 'entry-image-caption' . $font, array( 'figure.entry-thumbnail .caption' ), $defaults[ $optprefix . 'entry-image-caption' . $font ] ),
            pzarc_redux_bg( $prefix . 'entry-image-caption' . $font . $background, array( 'figure.entry-thumbnail .caption' ), $defaults[ $optprefix . 'entry-image-caption' . $font . $background ] ),
            pzarc_redux_padding( $prefix . 'entry-image-caption' . $font . $padding, array( 'figure.entry-thumbnail .caption' ), $defaults[ $optprefix . 'entry-image-caption' . $font . $padding ] )
          )
        );

        /**
         * CUSTOM FIELDS
         */
        if ( ! empty( $_GET[ 'post' ] ) ) {
          $thispostmeta = get_post_meta( $_GET[ 'post' ] );
          $cfcount      = ( ! empty( $thispostmeta[ '_panels_design_custom-fields-count' ][ 0 ] ) ? $thispostmeta[ '_panels_design_custom-fields-count' ][ 0 ] : 0 );
          for ( $i = 1; $i <= $cfcount; $i ++ ) {
            $cfname      = 'Custom field ' . $i . ( ! empty( $thispostmeta[ '_panels_design_cfield-' . $i . '-name' ][ 0 ] ) ? ': <br>' . $thispostmeta[ '_panels_design_cfield-' . $i . '-name' ][ 0 ] : '' );
            $sections[ ] = array(
              'title'      => $cfname,
              'show_title' => false,
              'icon_class' => 'icon-large',
              'icon'       => 'el-icon-wrench-alt',
              'desc'       => 'Class: .entry-customfield-' . $i,
              'fields'     => pzarc_fields(
                pzarc_redux_font( $prefix . 'entry-customfield-' . $i . '' . $font, array( '.entry-customfield-' . $i . '' ) ),
                pzarc_redux_bg( $prefix . 'entry-customfield-' . $i . '' . $font . $background, array( '.entry-customfield-' . $i . '' ) ),
                pzarc_redux_padding( $prefix . 'entry-customfield-' . $i . '' . $font . $padding, array( '.entry-customfield-' . $i . '' ) ),
                pzarc_redux_margin( $prefix . 'entry-customfield-' . $i . '' . $font . $margin, array( '.entry-customfield-' . $i . '' ), null, 'tb' ),
                pzarc_redux_borders( $prefix . 'entry-customfield-' . $i . '' . $border, array( '.entry-customfield-' . $i . '' ) ),
                pzarc_redux_links( $prefix . 'entry-customfield-' . $i . '' . $font . $link, array( '.entry-customfield-' . $i . ' a' ) )
              ),
            );
          }
        }
        /**
         * CUSTOM CSS
         */
        $sections[ ] = array(
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

        /**
         * HELP
         */
        $sections[ ]  = array(
          'id'         => 'styling-help',
          'title'      => __( 'Help', 'pzarchitect' ),
          'icon_class' => 'icon-large',
          'icon'       => 'el-icon-question-sign',
          'fields'     => array(
//                array(
//                    'title'    => __('Panels videos', 'pzarchitect'),
//                    'id'       => $prefix . 'help-styling-videos',
//                    'subtitle' => __('Internet connection required'),
//                    'type'     => 'raw',
//                    'class'    => 'plain',
//                    'markdown' => false,
//                    'content'  => ($defaults_only ? '' : @file_get_contents('https://s3.amazonaws.com/341public/architect/panels-videos.html')),
//                ),
array(
  'title'    => __( 'Online documentation', 'pzarchitect' ),
  'id'       => $prefix . 'help-styling-online-docs',
  'type'     => 'raw',
  'markdown' => false,
  'content'  => '<a href="http://architect4wp.com/codex-listings/" target=_blank>' . __( 'Architect Online Documentation', 'pzarchitect' ) . '</a><br>' . __( 'This is a growing resource. Please check back regularly.', 'pzarchitect' )

),

          )
        );
        $metaboxes[ ] = array(
          'id'         => 'panels-styling',
          'title'      => 'Content Styling',
          'post_types' => array( 'arc-blueprints' ),
          'sections'   => $sections,
          'position'   => 'normal',
          'priority'   => 'low',
          'sidebar'    => false

        );

        //pzdebug($metaboxes);

//        foreach ($metaboxes as $k => $v) {
//          var_dump($v['id'],$v['post_types']);
//        }


      }

      return $metaboxes;
    }


    private static function arc_has_export_data() {
      $export_data = get_option( 'arc-export-to-preset' );
      if ( ! empty( $export_data ) ) {
        $title = $export_data[ 'title' ];
        delete_option( 'arc-export-to-preset' );

        return '<h4>Export data for Blueprint:' . $title . '</h4><p>Copy and paste the export data to its own file.</p><form><pre class="arc-export-data"><textarea rows="10" cols="70">' . json_encode( $export_data ) . '</textarea></pre></form>';
      } else {
        return '';
      }
    }
  } // EOC


  function pzarc_draw_sections_preview() {
    // Put in a hidden field with the plugin url for use in js
    $return_html
      = '
          <div id="pzarc-blueprint-wireframe">
            <div id="pzarc-sections-preview-0" class="pzarc-sections pzarc-section-0"></div>
            <div id="pzarc-sections-preview-1" class="pzarc-sections pzarc-section-1"></div>
            <div id="pzarc-sections-preview-2" class="pzarc-sections pzarc-section-2"></div>
            <div id="pzarc-navigator-preview">
              <div class="pzarc-sections pzarc-section-navigator"><span class="icon-large el-icon-backward"></span> <span class="icon-large el-icon-caret-left"></span> <span class="icon-xlarge el-icon-stop"></span> <span class="icon-xlarge el-icon-stop"></span> <span class="icon-xlarge el-icon-stop"></span> <span class="icon-large el-icon-caret-right"></span> <span class="icon-large el-icon-forward"></span></div>
              <div class="pzarc-sections pzarc-section-pagination">First Prev 1 2 3 4 ... 15 Next Last</div>
            </div>
          </div>
          <div class="plugin_url" style="display:none;">' . PZARC_PLUGIN_APP_URL . '</div>
        ';

    return $return_html;
  }

  function show_meta() {
    $return_html = '2301';
    $meta        = get_post_meta( 2301 );
    if ( $meta ) {

      foreach ( $meta as $key => $value ) {
        $return_html .= '<p>' . $key . ' : ' . $value[ 0 ] . '</p>';
      }
    }

    return $return_html;
  }

  /**
   * [draw_panel_layout description]
   * @return [type] [description]
   */
  function draw_panel_layout() {
    $return_html = '';

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


  /*
   * Function creates post duplicate as a draft and redirects then to the edit post screen
   */
  function pzarc_new_from_preset() {
    global $wpdb;
    // How do we add some security?
    if ( ! ( isset( $_GET[ 'name' ] ) || isset( $_POST[ 'name' ] ) || ( isset( $_REQUEST[ 'action' ] ) && 'pzarc_new_from_preset' == $_REQUEST[ 'action' ] ) ) ) {
      wp_die();
    }

    /*
     * get the original post name
     */
    $preset_name  = ( isset( $_GET[ 'name' ] ) ? $_GET[ 'name' ] : $_POST[ 'name' ] );
    $process_type = ( isset( $_GET[ 'type' ] ) ? $_GET[ 'type' ] : $_POST[ 'type' ] );
    /*
     * and all the original post data then
     */

    require_once PZARC_PLUGIN_PATH . 'presets/presets.php';
    $presets         = new arcPresetsLoader();
    $presets_array   = $presets->render();
    $arc_preset_data = $presets_array[ 'data' ][ $preset_name ];

    /*
     * if you don't want current user to be the new post author,
     * then change next couple of lines to this: $new_post_author = $post->post_author;
     */
    $current_user    = wp_get_current_user();
    $new_post_author = $current_user->ID;

    /*
     * if post data exists, create the post duplicate
     */
    if ( ! empty( $preset_name ) ) {


      // Get the next slug name
      $args                  = array(
        'post_status'    => array( 'publish', 'draft' ),
        'post_type'      => 'arc-blueprints',
        'posts_per_page' => 1
      );
      $last_blueprint        = get_posts( $args );
      $next_id               = ( isset( $last_blueprint[ 0 ]->ID ) ? $last_blueprint[ 0 ]->ID + 1 : '1' );
      $preset[ 'post' ]      = json_decode( $arc_preset_data[ 'post' ] );
      $preset[ 'post_meta' ] = json_decode( $arc_preset_data[ 'meta' ], true );
      $new_slug              = sanitize_title( $preset[ 'post' ]->post_title ) . '-' . ( $next_id );

      /*
       * new post data array
       */
      $args = array(
        'comment_status' => $preset[ 'post' ]->comment_status,
        'ping_status'    => $preset[ 'post' ]->ping_status,
        'post_author'    => $new_post_author,
        'post_content'   => $preset[ 'post' ]->post_content,
        'post_excerpt'   => $preset[ 'post' ]->post_excerpt,
        'post_name'      => $new_slug,
        'post_parent'    => $preset[ 'post' ]->post_parent,
        'post_password'  => $preset[ 'post' ]->post_password,
        'post_status'    => 'draft',
        'post_title'     => 'A new ' . $process_type . ' Blueprint using preset : ' . $preset[ 'post' ]->post_title,
        'post_type'      => $preset[ 'post' ]->post_type,
        'to_ping'        => $preset[ 'post' ]->to_ping,
        'menu_order'     => $preset[ 'post' ]->menu_order
      );

      /*
       * insert the post by wp_insert_post() function
       */
      $new_post_id = wp_insert_post( $args );

      /*
       * get all current post terms ad set them to the new post draft
       */
//      $taxonomies = get_object_taxonomies($pre->post_type); // returns array of taxonomy names for post type, ex array("category", "post_tag");
//      foreach ($taxonomies as $taxonomy) {
//        $post_terms = wp_get_object_terms($preset_name, $taxonomy, array('fields' => 'slugs'));
//        wp_set_object_terms($new_post_id, $post_terms, $taxonomy, false);
//      }

      /*
       * duplicate all post meta
       */
      if ( count( $preset[ 'post_meta' ] ) != 0 ) {
        $sql_query     = "INSERT INTO $wpdb->postmeta (post_id, meta_key, meta_value) ";
        $sql_query_sel = array();
        foreach ( $preset[ 'post_meta' ] as $meta_key => $value ) {
          if ( $meta_key === '_blueprints_short-name' ) {
            $meta_value = $value[ 0 ] . '-' . $new_post_id;
          } else {
            if ( $process_type === 'unstyled' ) {
              // Just done it this way for speed.
              if ( strpos( $meta_key, '_styling_' ) === false ) {
                $meta_value = addslashes( $value[ 0 ] );
              }

            } else {
              $meta_value = addslashes( $value[ 0 ] );
            }
          }

          $sql_query_sel[ ] = "SELECT $new_post_id, '$meta_key', '$meta_value'";
        }
        $sql_query .= implode( " UNION ALL ", $sql_query_sel );
        $wpdb->query( $sql_query );
      }


      /*
       * finally, redirect to the edit post screen for the new draft
       */
      wp_redirect( admin_url( 'post.php?action=edit&post=' . $new_post_id ) );
//      wp_redirect(admin_url('edit.php?post_type=' . $preset[ 'post' ]->post_type));

      exit;
    } else {
      wp_die( 'Post creation failed, could not find original post: ' . $preset_name );
    }
  }

  add_action( 'admin_action_pzarc_new_from_preset', 'pzarc_new_from_preset' );
