<?php
  require_once 'designer_metaboxes/class_arc_mbGeneral.php';
  require_once 'designer_metaboxes/class_arc_mbTabs.php';
  require_once 'designer_metaboxes/class_arc_mbTitles.php';
  require_once 'designer_metaboxes/class_arc_mbBlueprintDesign.php';
  require_once 'designer_metaboxes/class_arc_mbBodyExcerpt.php';
  require_once 'designer_metaboxes/class_arc_mbCustomFields.php';
  require_once 'designer_metaboxes/class_arc_mbFeatures.php';
  require_once 'designer_metaboxes/class_arc_mbLayouts.php';
  require_once 'designer_metaboxes/class_arc_mbMeta.php';
  require_once 'designer_metaboxes/class_arc_mbPanels.php';
  require_once 'designer_metaboxes/class_arc_mbSource.php';


  class arc_Blueprints_Designer {

    public $content_types;
    public $redux_opt_name = '_architect';
    public $defaults = FALSE;
    public $custom_fields = array();
    public $postmeta = NULL;
    public $source = 'defaults';
    public $tableset = array();
    public $tablesfields = array();


    /**
     * [__construct description]
     */
    function __construct( $defaults = FALSE ) {
//global $wp_meta_boxes;
//      var_dump($wp_meta_boxes);
      $this->defaults = $defaults;
      pzdb( 'bp_Designer_start' );
      // load extra stuffs
      if ( is_admin() ) {
        add_action( 'admin_head', array( $this, 'content_blueprints_admin_head', ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'content_blueprints_admin_enqueue', ) );
        add_filter( 'manage_arc-blueprints_posts_columns', array( $this, 'add_blueprint_columns', ) );
        add_action( 'manage_arc-blueprints_posts_custom_column', array( $this, 'add_blueprint_column_content', ), 10, 2 );
        add_filter( 'views_edit-arc-blueprints', array( $this, 'blueprints_description', ) );


        //       add_action('admin_init',array($this,'admin_init'));
        // Grab the extra slider types from the registry

        $registry     = arc_Registry::getInstance();
        $slider_types = (array) $registry->get( 'slider_types' );
        //   var_dump($slider_types);
        foreach ( $slider_types as $st ) {

          require_once( $st['admin'] );

        }

        // 1.8.1 Attempting to reduce calls to get custom fields. Generally this should work, but may occasionally miss some fields.
        $this->custom_fields = get_option( 'architect_custom_fields' );
        if ( empty( $this->custom_fields ) || ( ( isset( $_GET['post_type'] ) && $_GET['post_type'] === 'arc-blueprints' ) ) ) {
          $this->custom_fields = pzarc_get_custom_fields();
          update_option( 'architect_custom_fields', $this->custom_fields );
//          var_dump('Custom fields updated');
        }
//        $this->custom_fields = apply_filters('arc_custom_field_list',$this->custom_fields, $this->source);
        if ( ! empty( $_GET['post'] ) ) {
          $this->postmeta = get_post_meta( $_GET['post'] );
          if ( isset( $this->postmeta['_blueprints_content-source'] ) ) {
            $this->source = $this->postmeta['_blueprints_content-source'][0];
          }
        }
        $this->tableset = ArcFun::get_tables();
        foreach ( $this->tableset as $table ) {
          $this->tablesfields[ $table ] = ArcFun::get_table_fields( $table );
        }

      }
      pzdb( 'end: ' . __CLASS__ . '\\' . __FUNCTION__ );
    }

    /**
     *
     */
    public function admin_init() {
      //      add_filter('_arc_add_tax_titles','pzarc_get_tax_titles',10,1);
    }

    /**
     * [content_blueprints_admin_enqueue description]
     *
     * @param  [type] $hook [description]
     */
    public function content_blueprints_admin_enqueue( $hook ) {
      pzdb( __FUNCTION__ );
      $screen = get_current_screen();
      if ( 'arc-blueprints' == $screen->id ) {
        //TODO: Update this for 1.8!
        //     require_once( PZARC_DOCUMENTATION_PATH . PZARC_LANGUAGE . '/blueprints-pageguide.php' );

        wp_enqueue_script( 'jquery-ui-draggable' );
        wp_enqueue_script( 'jquery-ui-droppable' );
        wp_enqueue_script( 'jquery-ui-sortable' );
        wp_enqueue_script( 'jquery-ui-resizable' );

        wp_enqueue_style( 'pzarc-admin-panels-css', PZARC_PLUGIN_APP_URL . '/admin/css/arc-admin-panels.css' );

        wp_enqueue_script( 'jquery-pzarc-metaboxes-panels', PZARC_PLUGIN_APP_URL . '/admin/js/arc-metaboxes-panels.js', array( 'jquery' ), PZARC_VERSION, TRUE );

        wp_enqueue_style( 'pzarc-admin-blueprints-css', PZARC_PLUGIN_APP_URL . '/admin/css/arc-admin-blueprints.css' );

        wp_enqueue_script( 'jquery-pzarc-metaboxes-blueprints', PZARC_PLUGIN_APP_URL . '/admin/js/arc-metaboxes-blueprints.js', array( 'jquery' ), PZARC_VERSION, TRUE );


      } elseif ( 'edit-arc-blueprints' === $screen->id ) {
//        require_once( PZARC_DOCUMENTATION_PATH.PZARC_LANGUAGE . '/blueprints-listing-pageguide.php');
        require_once PZARC_PLUGIN_PATH . 'presets/presets.php';
        wp_enqueue_script( 'jquery-pzarc-blueprints-presets', PZARC_PLUGIN_APP_URL . '/admin/js/arc-blueprints-presets.js', array( 'jquery', 'jquery-ui-draggable', ), PZARC_VERSION, TRUE );
//        wp_enqueue_script('jquery-echo-js', PZARC_PLUGIN_APP_URL . '/admin/js/echo.js', array('jquery'), true);
        wp_enqueue_script( 'jquery-lazy', PZARC_PLUGIN_APP_URL . '/admin/js/jquery.lazy.min.js', array( 'jquery' ), PZARC_VERSION, TRUE );

      }
    }

    /**
     * [content_blueprints_admin_head description]
     */
    public function content_blueprints_admin_head() {
      $this->screen = get_current_screen();
    }

    /**
     * @param $content
     *
     * @return mixed
     */
    function blueprints_description( $content ) {
      pzdb( __FUNCTION__ );
      // todo: MAKE SURE ALL PRESETS USE DUMMY CONTENT AND NO FILTERS

      // TODO: How can we make this not load until we want it too?

      global $_architect_options;
      $arc_styling            = ! empty( $_architect_options['architect_enable_styling'] ) ? 'arc-styling-on' : 'arc-styling-off';
      $presets                = new arcPresetsLoader();
      $presets_array          = $presets->render();
      $presets_html           = $presets_array['html'];
      $content['arc-message'] = '
      <div class="after-title-help postbox">

        <div class="inside">
<div class="pzarc-help-section">
                        <a class="pzarc-button-help" href="http://architect4wp.com/codex-listings/" target="_blank">
                        <span class="dashicons dashicons-book"></span>
                        Documentation</a>&nbsp;
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
          ' .

                                self::arc_has_export_data()

                                . '
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
           <div class="tabs-pane active" id="sliders">' . $presets_html['slider'] . '</div>
           <div class="tabs-pane" id="grids">' . $presets_html['basic'] . '</div>
           <div class="tabs-pane" id="tabbed">' . $presets_html['tabbed'] . '</div>
           <div class="tabs-pane" id="masonry">' . $presets_html['masonry'] . '</div>
           <div class="tabs-pane" id="accordion">' . $presets_html['accordion'] . '</div>
           <div class="tabs-pane" id="tabular">' . $presets_html['table'] . '</div>
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
      pzdb( __FUNCTION__ );
      unset( $columns['thumbnail'] );
      $pzarc_checkbox    = array_slice( $columns, 0, 1 );
      $pzarc_front       = array_slice( $columns, 1, 1 );
      $pzarc_back        = array_slice( $columns, 2 );
      $pzarc_insert      = array(
        '_blueprints_short-name'     => __( 'Shortname', 'pzarchitect' ),
        '_blueprints_content-source' => __( 'Content source', 'pzarchitect' ),
        '_blueprints_description'    => __( 'Description', 'pzarchitect' ),
        'used-on'                    => __( 'Used on ', 'pzarchitect' ) . '<span style="color:#fff;font-size:0.8em;background:#999;border-radius:50px;padding:0 5px;cursor:help;" title="Note: Resets when Architect cache is cleared and rebuilt as pages are displayed.">?</span>',
        //          'id'                         => __('ID', 'pzarchitect'),
      );
      $pzarc_layout_type = array( 'layout-type' => __( 'Type', 'pzarchitect' ), );

      return array_merge( $pzarc_checkbox, $pzarc_layout_type, $pzarc_front, $pzarc_insert, $pzarc_back );
    }

    /**
     * [add_blueprint_column_content description]
     *
     * @param [type] $column  [description]
     * @param [type] $post_id [description]
     */
    public function add_blueprint_column_content( $column, $post_id ) {
      pzdb( __FUNCTION__ );

      $post_meta = get_post_meta( $post_id );
      $bp_uses   = maybe_unserialize( get_option( 'arc-blueprint-usage' ) );

      switch ( $column ) {
        case 'id':
          if ( isset( $post_meta[ $column ] ) ) {
            echo $post_meta[ $column ][0];
          }
          break;

        case '_blueprints_short-name':
          if ( isset( $post_meta[ $column ] ) ) {
            echo $post_meta[ $column ][0];
          }
          break;

        case '_blueprints_description':
          if ( isset( $post_meta[ $column ] ) ) {
            echo $post_meta[ $column ][0];
          }
          break;

        case '_blueprints_content-source':

          $content_source = ucwords( empty( $post_meta[ $column ][0] ) ? 'default' : $post_meta[ $column ][0] );
          $content_source = ( $content_source === 'Cpt' ? 'Custom Post Type' : $content_source );
          echo $content_source;
          break;

        case 'used-on':
          if ( is_array( $bp_uses ) ) {
            $i = 1;
            echo '<div class="arc-used-on" >';
            foreach ( $bp_uses as $k => $v ) {
              if ( ! empty( $post_meta['_blueprints_short-name'][0] ) && $v['bp'] === $post_meta['_blueprints_short-name'][0] ) {
                $uo_post = get_post( $v['id'] );
                if ( ! in_array( $uo_post->post_type, array( 'arc-blueprints', 'revision', 'attachment' ) ) ) {
                  $odd_even = $i % 2 == 0 ? 'even' : 'odd';
                  echo '<p class="rows ' . $odd_even . '">' . ucwords( $uo_post->post_type ) . ' : ' . $uo_post->post_title . '</p>';
                  $i ++;
                }
              }
              if ( $i > 15 ) {
                if ( $i < count( $bp_uses ) ) {
                  echo __( '... and more', 'pzarchitect' );
                }
                break;
              }
            }
            echo '</div>';
          }
          break;
        case 'layout-type':
          $layout_imgs = array(
            'basic'     => array( 'img' => PZARC_PLUGIN_APP_URL . 'shared/assets/images/metaboxes/layouts-grid.svg' ),
            'slider'    => array( 'img' => PZARC_PLUGIN_APP_URL . 'shared/assets/images/metaboxes/layouts-slider.svg' ),
            'tabbed'    => array( 'img' => PZARC_PLUGIN_APP_URL . 'shared/assets/images/metaboxes/layouts-tabbed.svg' ),
            'masonry'   => array( 'img' => PZARC_PLUGIN_APP_URL . 'shared/assets/images/metaboxes/layouts-masonry.svg' ),
            'table'     => array( 'img' => PZARC_PLUGIN_APP_URL . 'shared/assets/images/metaboxes/layouts-tabular.svg' ),
            'accordion' => array( 'img' => PZARC_PLUGIN_APP_URL . 'shared/assets/images/metaboxes/layouts-accordion.svg' ),
          );

          if ( ! empty( $post_meta['_blueprints_section-0-layout-mode'][0] ) ) {
            $layout_type = ( $post_meta['_blueprints_section-0-layout-mode'][0] );
          } else {
            $layout_type = 'basic';
          }
          $layout_image = $layout_imgs[ $layout_type ]['img'];
          echo '<div class="pzarc-layout-type-column"><img src="' . $layout_image . '" width="48"></div>';
          break;
      }
    }




    private static function arc_has_export_data() {
      /**
       * NOT USED ANYMORE
       */
      return NULL;


      pzdb( __FUNCTION__ );
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
          return '<p class="arc-export-save">Ready for download: <a href="' . $filename_url . '" target=_blank download>' . basename( $filename ) . '</a></p>';
        }
        // TODO Tutorials on saving exports and creating Presets.
      } else {
        return '';
      }
    }


  } // EOC


  function pzarc_draw_sections_preview() {
    pzdb( __FUNCTION__ );
    // Put in a hidden field with the plugin url for use in js
    $return_html = '
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
    pzdb( __FUNCTION__ );
    $return_html = '2301';
    $meta        = get_post_meta( 2301 );
    if ( $meta ) {

      foreach ( $meta as $key => $value ) {
        $return_html .= '<p>' . $key . ' : ' . $value[0] . '</p>';
      }
    }

    return $return_html;
  }

  /**
   * [draw_panel_layout description]
   * @return [type] [description]
   */
  function draw_panel_layout() {
    pzdb( __FUNCTION__ );
    $return_html = '';

    // Put in a hidden field with the plugin url for use in js
    $return_html = '
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
