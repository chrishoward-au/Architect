<?php

  class arc_Blueprints_Layouts
  {

    public $content_types;
    public $redux_opt_name = '_architect';
    public $defaults = false;


    /**
     * [__construct description]
     */
    function __construct($defaults = false)
    {


      $this->defaults = $defaults;
      if (is_admin()) {
        global $pzarc_panels_array;
        $args = array(
            'posts_per_page'   => -1,
            'orderby'          => 'title',
            'order'            => 'ASC',
            'post_type'        => 'arc-panels',
            'suppress_filters' => true);

        $pzarc_panels       = get_posts($args);
        $pzarc_panels_array = array();
        if (!empty($pzarc_panels)) {
          foreach ($pzarc_panels as $pzarc_cell) {
            $pzarc_panels_array[ $pzarc_cell->post_name ] = (empty($pzarc_cell->post_title) ? 'No title' : $pzarc_cell->post_title);
          }
        } else {
          $pzarc_panels_array = array(0 => 'No cell layouts. Create some.');
        }


        add_action('admin_head', array($this, 'content_blueprints_admin_head'));
        add_action('admin_enqueue_scripts', array($this, 'content_blueprints_admin_enqueue'));
        add_filter('manage_arc-blueprints_posts_columns', array($this, 'add_blueprint_columns'));
        add_action('manage_arc-blueprints_posts_custom_column', array($this, 'add_blueprint_column_content'), 10, 2);

        add_action("redux/metaboxes/$this->redux_opt_name/boxes", array($this, 'pzarc_blueprint_tabs_mb'), 10, 1);
        add_action("redux/metaboxes/$this->redux_opt_name/boxes", array($this,
                                                                        'pzarc_blueprint_layout_general_mb'), 10, 1);
        add_action("redux/metaboxes/$this->redux_opt_name/boxes", array($this,
                                                                        'pzarc_blueprint_layout_mb'), 10, 1);
        add_action("redux/metaboxes/$this->redux_opt_name/boxes", array($this, 'pzarc_blueprint_contents_mb'), 10, 1);
        add_action("redux/metaboxes/$this->redux_opt_name/boxes", array($this,
                                                                        'pzarc_blueprint_layout_styling_mb'), 10, 1);
 //       add_filter('views_edit-arc-blueprints', array($this, 'blueprints_description'));


      }

    }

    /**
     * [content_blueprints_admin_enqueue description]
     * @param  [type] $hook [description]
     */
    public function content_blueprints_admin_enqueue($hook)
    {
      $screen = get_current_screen();
      if ('arc-blueprints' == $screen->id) {
        require_once( PZARC_DOCUMENTATION_PATH.PZARC_LANGUAGE . '/blueprints-pageguide.php');


        wp_enqueue_style('pzarc-admin-blueprints-css', PZARC_PLUGIN_APP_URL . '/admin/css/arc-admin-blueprints.css');

        wp_enqueue_script('jquery-pzarc-metaboxes-blueprints', PZARC_PLUGIN_APP_URL . '/admin/js/arc-metaboxes-blueprints.js', array('jquery'), true);

        // TODO: We don't need this here??
        // wp_enqueue_script('js-isotope-v2');

        // wp_enqueue_script('js-magnific');
//        wp_enqueue_script('jquery-pageguide', PZARC_PLUGIN_APP_URL . 'shared/thirdparty/js/pageguide/pageguide.min.js', array('jquery'),true);
//        wp_enqueue_style('css-pageguide', PZARC_PLUGIN_APP_URL . 'shared/thirdparty/js/pageguide/css/pageguide.css');

//        wp_enqueue_style('css-tourist-bootstrap','//netdna.bootstrapcdn.com/bootstrap/3.0.3/css/bootstrap.min.css');
//        wp_enqueue_style('css-tourist-fa','//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.min.css');
//
//        wp_enqueue_script('jquery-tourist', PZARC_PLUGIN_APP_URL . 'shared/thirdparty/js/tourist/tourist.js', array('jquery','backbone'),false);
//        wp_enqueue_script('jquery-tourist-blueprint', PZARC_PLUGIN_APP_URL . 'admin/js/arc-tourist-blueprint.js',null,false);
//        wp_enqueue_style('css-tourist', PZARC_PLUGIN_APP_URL . 'shared/thirdparty/js/tourist/tourist.css');

//                wp_enqueue_script('jquery-introjs', PZARC_PLUGIN_APP_URL . 'shared/thirdparty/js/intro.js/intro.js', array('jquery'),true);
//        wp_enqueue_script('jquery-introjs-blueprint', PZARC_PLUGIN_APP_URL . 'admin/js/arc-introjs-blueprint.js',null,true);
//        wp_enqueue_style('css-introjs', PZARC_PLUGIN_APP_URL . 'shared/thirdparty/js/intro.js/introjs.css');

        // wp_enqueue_script('jquery-masonry', PZARC_PLUGIN_URL . 'includes/masonry.pkgd.min.js', array('jquery'));

        // wp_enqueue_script('jquery-lorem', PZARC_PLUGIN_URL . 'includes/jquery.lorem.js', array('jquery'));
      } elseif ('edit-arc-blueprints'===$screen->id) {
//        require_once( PZARC_DOCUMENTATION_PATH.PZARC_LANGUAGE . '/blueprints-listing-pageguide.php');

      }
//      global $pzcustom_post_types;
//      $pzcustom_post_types = (get_post_types(array('_builtin' => false, 'public' => true), 'names'));
    }

    /**
     * [content_blueprints_admin_head description]
     */
    public function content_blueprints_admin_head()
    {

    }

    /**
     * [add_blueprint_columns description]
     * @param [type] $columns [description]
     */
    public function add_blueprint_columns($columns)
    {
      unset($columns[ 'thumbnail' ]);
      $pzarc_front  = array_slice($columns, 0, 2);
      $pzarc_back   = array_slice($columns, 2);
      $pzarc_insert = array(
          '_blueprints_short-name'     => __('Blueprint short name', 'pzarchitect'),
          '_blueprints_description'    => __('Description', 'pzarchitect'),
          '_blueprints_content-source' => __('Content source', 'pzarchitect'),
          'layout'                     => __('Layout type', 'pzarchitect'),
          'panels'                     => __('Section Panel', 'pzarchitect'),
          'id'                         => __('ID', 'pzarchitect'),
      );

      return array_merge($pzarc_front, $pzarc_insert, $pzarc_back);
    }

    /**
     * [add_blueprint_column_content description]
     * @param [type] $column  [description]
     * @param [type] $post_id [description]
     */
    public function add_blueprint_column_content($column, $post_id)
    {

      $post_meta = get_post_meta($post_id);


      switch ($column) {
        case 'id':
          if (isset($post_meta[ $column ])) {
            echo $post_meta[ $column ][ 0 ];
          }
          break;

        case '_blueprints_short-name':
          if (isset($post_meta[ $column ])) {
            echo $post_meta[ $column ][ 0 ];
          }
          break;

        case '_blueprints_description':
          if (isset($post_meta[ $column ])) {
            echo $post_meta[ $column ][ 0 ];
          }
          break;

        case '_blueprints_content-source':

          $content_source = ucwords(empty($post_meta[ $column ][ 0 ]) ? 'default' : $post_meta[ $column ][ 0 ]);
          $content_source = ($content_source === 'Cpt' ? 'Custom Post Type' : $content_source);
          echo $content_source;
          break;

        case 'layout':
          if (!empty($post_meta[ '_blueprints_section-0-layout-mode' ][ 0 ])) {
            echo '1: ' . ucfirst($post_meta[ '_blueprints_section-0-layout-mode' ][ 0 ]);
          } else {
            echo '1: Basic';
          }
          if (!empty($post_meta[ '_blueprints_section-0-layout-mode' ][ 0 ]) && !empty($post_meta[ '_blueprints_section-1-layout-mode' ][ 0 ])) {
            echo '<br>' . '2: ' . ucfirst($post_meta[ '_blueprints_section-1-layout-mode' ][ 0 ]);
          }
          if ((!empty($post_meta[ '_blueprints_section-0-layout-mode' ][ 0 ]) || !empty($post_meta[ '_blueprints_section-1-layout-mode' ][ 0 ])) && !empty($post_meta[ '_blueprints_section-2-layout-mode' ])) {
            echo '<br>' . '3: ' . ucfirst($post_meta[ '_blueprints_section-2-layout-mode' ][ 0 ]);
          }
          break;

        case 'panels':
          global $pzarc_panels_array;
          if (!empty($post_meta[ '_blueprints_section-0-panel-layout' ][ 0 ])) {
            echo '1: ' . $pzarc_panels_array[ $post_meta[ '_blueprints_section-0-panel-layout' ][ 0 ] ];
          } else {
            echo 'NO PANEL LAYOUT SELECTED';
          }
          if (!empty($post_meta[ '_blueprints_section-1-panel-layout' ][ 0 ])) {
            echo '<br>' . '2: ' . $pzarc_panels_array[ $post_meta[ '_blueprints_section-1-panel-layout' ][ 0 ] ];
          }
          if (!empty($post_meta[ '_blueprints_section-2-panel-layout' ][ 0 ])) {
            echo '<br>' . '3: ' . $pzarc_panels_array[ $post_meta[ '_blueprints_section-2-panel-layout' ][ 0 ] ];
          }
      }
    }


    function blueprints_description($content)
    {

      $content[ 'arc-message' ] = '
      <div class="after-title-help postbox">
        <div class="inside">
          <h4>About Blueprints</h4>

          <p class="howto">
            ' . __('Architect Blueprints are where you build the overall layouts to display. In Blueprints, you select a Panel, what content will appear in the panels, and how you want to lay them out.', 'pzarchitect') . '</p>

          <p class="howto">Documentation can be found throughout Architect or online at the <a
                href="http://architect4wp.com/codex-listings" target="_blank">Architect Codex</a></p>

        </div>
        <!-- .inside -->
      </div><!-- .postbox -->';

      return $content;
    }


    /**
     * [create_blueprints_post_type description]
     * @return [type] [description]
     */


////add_filter('cmb_meta_boxes', 'pzarc_blueprint_wizard_metabox');
//function pzarc_blueprint_wizard_metabox($metaboxes = array())
//{
//  $prefix        = '_pzarc_';
//  $fields        = array(
//          array(
//                  'title'    => 'Select what type of blueprint you want to make',
//                  'id'      => $prefix . 'wizard',
//                  'type'    => 'radio',
//                  'default' => 'custom',
//                  'subtitle'    => 'Select one to quickly enable relevant settings, or custom to build your own from scratch with all settings and defaults. Minimize this metabox once you are happy with your selection.',
//                  'options' => array(
//                          'custom'       => 'Custom',
//                          'fullplusgrid' => 'Full + grid',
//                          'gallery'      => 'Gallery',
//                          'grid'         => 'Grid',
//                          'slider'       => 'Content slider',
//                          'tabbed'       => 'Tabbed',
//                  )
//          )
//  );
//  $metaboxes[ ] = array(
//          'title'    => 'What do you want to do?',
//          'pages'    => 'arc-blueprints',
//          'context'  => 'normal',
//          'priority' => 'high',
//          'fields'   => $fields // An array of fields.
//  );
//
//  return $metaboxes;
//}


    function pzarc_blueprint_tabs_mb($metaboxes,$defaults_only = false)
    {
      $prefix   = '_blueprint_tabs_';
      $sections = array();

      global $_architect_options;
      if (empty($_architect_options)) {
        $_architect_options = get_option('_architect_options');
      }
      $fields = array();
      if (!empty($_architect_options[ 'architect_enable_styling' ])) {
        $fields = array(array(
                            'id'      => $prefix . 'tabs',
                            'type'    => 'tabbed',
                            'desc'  =>'
                <p style="color:#ff5500;">' . __('For guided help, click the orange <em>Blueprints Guide</em> button at the top right. You can keep it open and still interact with this page.', 'pzarchitect') . '</p>',
//                '<p>&bull;&nbsp;' . __('<strong style="color:#0074A2;"><em>Blueprint Layout</em></strong> is where you choose which Panel design to use for posts or page, and how you want to lay out those Panels.', 'pzarchitect') . '</p>
//                 <p>&bull;&nbsp;' . __('<strong style="color:#0074A2;"><em>Panels Content</em></strong> is where you select the specific posts or pages to display within this Blueprint\'s Panels. <strong>The one content selection is spread across all sections</strong>', 'pzarchitect') . '</p>
//                 <p>&bull;&nbsp;' . __('Blueprints by default have limited styling. Use <strong style="color:#0074A2;"><em>Blueprint Styling</em></strong> to refine the styling of the Blueprint to match your theme.', 'pzarchitect') . '</p>

                            'options' => array(
                                'layout'  => '<span><span class="icon-large el-icon-website"></span> Blueprint Layout</span>',
                                'content' => '<span><span class="icon-large el-icon-align-left"></span> Panel Content</span>',
                                'styling' => '<span><span class="icon-large el-icon-brush"></span> Blueprint Styling</span>'
                            ),
                            'targets' => array(
                                'layout'  => array('layout-settings'),
                                'content' => array('content-selections'),
                                'styling' => array('blueprint-stylings'),
                            )
                        ),
        );
      } else {
        $fields = array(array(
                            'id'      => $prefix . 'tabs',
                            'type'    => 'tabbed',
                            'options' => array(
                                'layout'  => '<span><span class="icon-large el-icon-website"></span> Blueprint Layout</span>',
                                'content' => '<span><span class="icon-large el-icon-align-left"></span> Panel Content</span>',
                            ),
                            'targets' => array(
                                'layout'  => array('layout-settings'),
                                'content' => array('content-selections'),
                            )
                        ),
        );


      }
      $sections[ ]  = array(
        //          'title'      => __('General Settings', 'pzarchitect'),
        'show_title' => true,
        'icon_class' => 'icon-large',
        'icon'       => 'el-icon-home',
        'fields'     => $fields
      );
      $metaboxes[ ] = array(
          'id'         => $prefix . 'blueprints',
          'title'      => 'Show Blueprints settings for:',
          'post_types' => array('arc-blueprints'),
          'sections'   => $sections,
          'position'   => 'normal',
          'priority'   => 'high',
          'sidebar'    => false

      );

      return $metaboxes;
    }


//  add_action("redux/metaboxes/{$redux_opt_name}/boxes", 'pzarc_blueprint_general');
//  function pzarc_blueprint_general($metaboxes)
//  {
//    $prefix      = '_blueprints_';
//    $sections    = array();
//    $sections[ ] = array(
//        'fields' => array(
////            array(
////                'id'      => $prefix . 'tabs',
////                'type'    => 'tabbed',
////                'title' => 'Show settings panel for:',
////        'options' => array(
////                      'layout-settings' => '<span class="icon-large el-icon-th-large"></span> Layout',
////                      'content-selections' => '<span class="icon-large el-icon-pencil"></span> Content'),
////            ),
//        )
//    );
//
//    $metaboxes[ ] = array(
//        'id'         => 'blueprint-general-settings',
//        'title'      => 'General Settings',
//        'post_types' => array('arc-blueprints'),
//        'sections'   => $sections,
//        'position'   => 'side',
//        'priority'   => 'high',
//        'sidebar'    => false
//
//    );
//
//
//    return $metaboxes;
//
//  }

    // TODO: ADD FILTER OPTION FOR RELATED POSTS

    /**
     * pzarc_blueprint_layout_general
     * @param array $metaboxes
     * @return array
     */
    function pzarc_blueprint_layout_general_mb($metaboxes,$defaults_only = false)
    {
      $prefix = '_blueprints_';

      $sections[ ]  = array(
          'fields' => array(
              array(
                  'id'       => $prefix . 'short-name',
                  'title'    => __('Blueprint Short Name', 'pzarchitect') . '<span class="pzarc-required el-icon-star" title="Required"></span>',
                  'type'     => 'text',
                  'hint'     => array('title'   => 'Blueprint Short Name',
                                      'content' => '<strong>' . __('Letters, numbers, dashes only. ', 'pzarchitect') . '</strong>' . __('Use this in shortcodes and template tags', 'pzarchitect')),
                  //TODO: Write acomprehensive little help dialog here
                  'validate' => 'not_empty'
              ),
              array(
                  'id'    => $prefix . 'description',
                  'title' => __('Description', 'pzarchitect'),
                  'type'  => 'textarea',
                  'rows'  => 2,
                  'hint'  => array('content' => __('A short description to help you or others know what this Blueprint is for', 'pzarchitect')),
              ),
              array(
                  'id'       => $prefix . 'blueprint-width',
                  'type'     => 'dimensions',
                  //               'mode'    => array('width' => true, 'height' => false),
                  'units'    => array('%', 'px'),
                  'width'    => true,
                  'height'   => false,
                  'title'    => __('Blueprint max width', 'pzarchitect'),
                  'default'  => array('width' => '100', 'units' => '%'),
                  'subtitle' => __('Set a max width to stop spillage when the container is larger than you want the Blueprint to be.', 'pzarchitect')
              ),
              array(
                  'id'      => $prefix . 'blueprint-align',
                  'type'    => 'button_set',
                  'select2' => array('allowClear' => false),
                  'options' => array('left'   => __('Left', 'pzarchitect'),
                                     'center' => __('Centre', 'pzarchitect'),
                                     'right'  => __('Right', 'pzarchitect')),
                  'title'   => __('Blueprint align', 'pzarchitect'),
                  'default' => 'center',
              ),
              array(
                  'title'   => 'Page title',
                  'id'      => $prefix . 'page-title',
                  'type'    => 'switch',
                  'on'      => 'Yes',
                  'off'     => 'No',
                  'default' => false
              ),
              array(
                  'title'   => 'Section 2',
                  'id'      => $prefix . 'section-1-enable', //this number is the increment number
                  'type'    => 'switch',
                  'on'      => 'Yes',
                  'off'     => 'No',
                  'width'   => 'auto',
                  //                  'required' => array($prefix . 'navigation', 'not', 'navigator'),
                  'default' => false
              ),
              array(
                  'title'   => 'Section 3',
                  'id'      => $prefix . 'section-2-enable',
                  'type'    => 'switch',
                  'on'      => 'Yes',
                  'off'     => 'No',
                  //                  'required' => array($prefix . 'navigation', 'not', 'navigator'),
                  'default' => false
              ),

          )
      );
      $metaboxes[ ] = array(
          'id'         => $prefix . 'layout-general-settings',
          'title'      => 'General Settings',
          'post_types' => array('arc-blueprints'),
          'sections'   => $sections,
          'position'   => 'side',
          'priority'   => 'high',
          'sidebar'    => false

      );


      return $metaboxes;

    }

//    // add_action("redux/metaboxes/{$redux_opt_name}/boxes", 'pzarc_blueprint_content_general');
//    /**
//     * pzarc_blueprint_content_general
//     * @param array $metaboxes
//     * @return array
//     */
//    function pzarc_blueprint_content_general_mb($metaboxes)
//    {
//
//      $prefix = '_blueprints_';
//
//      $sections[ ] = array(
//          'fields' => array()
//      );
//
//      $metaboxes[ ] = array(
//          'id'         => $prefix . 'content-general-settings',
//          'title'      => 'Content Settings',
//          'post_types' => array('arc-blueprints'),
//          'sections'   => $sections,
//          'position'   => 'side',
//          'priority'   => 'high',
//          'sidebar'    => false
//
//      );
//
//
//      return $metaboxes;
//
//    }


    /**
     * LAYOUT
     */
    function pzarc_blueprint_layout_mb($metaboxes,$defaults_only = false)
    {
      $prefix   = '_blueprints_';
      $sections = array();
      global $_architect_options;
      if (empty($_architect_options)) {
        $_architect_options = get_option('_architect_options');
      }
      global $pzarc_panels_array;
      if (empty($pzarc_panels_array)) {
        $args = array(
            'posts_per_page'   => -1,
            'orderby'          => 'title',
            'order'            => 'ASC',
            'post_type'        => 'arc-panels',
            'suppress_filters' => true,
            'post_status'      => 'publish');

        $pzarc_panels       = get_posts($args);
        $pzarc_panels_array = array();
        if (!empty($pzarc_panels)) {
          foreach ($pzarc_panels as $pzarc_cell) {
            $pzarc_panels_array[ $pzarc_cell->post_name ] = (empty($pzarc_cell->post_title) ? 'No title' : $pzarc_cell->post_title);
          }
        } else {
          $pzarc_panels_array = array(0 => 'No cell layouts. Create some.');
        }
      }

//      $sections[ ] = array(
//          'title'      => __('General settings', 'pzarchitect'),
//          'show_title' => true,
//          'icon_class' => 'icon-large',
//          'icon'       => 'el-icon-adjust-alt',
//          'fields'     => array(
//          )
//      );


      /** SECTIONS */
      $icons       = array(0 => 'el-icon-align-left', 1 => 'el-icon-th', 2 => 'el-icon-th-list');
      $modes[ 0 ]  = array(
          'basic'     => __('Basic', 'pzarchitect'),
          'slider'    => __('Slider', 'pzarchitect'),
          'tabbed'    => __('Tabbed', 'pzarchitect'),
          'masonry'   => __('Masonry', 'pzarchitect'),
          'table'     => __('Tabular', 'pzarchitect'),
          'accordion' => __('Accordion', 'pzarchitect'),
          //          'fitRows'         => 'Fit rows',
          //          'fitColumns'      => 'Fit columns',
          //          'masonryVertical' => 'Masonry Vertical',
          //          'panelsByRow'      => 'Panels by row',
          //          'panelsByColumn'   => 'Panels by column',
          //          'vertical'    => 'Vertical',
          //          'horizontal'  => 'Horizontal',
      );
      $modes[ 1 ]  = array(
          'basic'     => __('Grid/Single', 'pzarchitect'),
          'masonry'   => __('Masonry', 'pzarchitect'),
          'table'     => __('Tabular', 'pzarchitect'),
          'accordion' => __('Accordion', 'pzarchitect'),
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
      for ($i = 0; $i < 3; $i++) {
        $sections[ '_section' . ($i + 1) ] = array(
            'title'      => __('Section ' . ($i + 1), 'pzarchitect'),
            'show_title' => true,
            'icon_class' => 'icon-large',
            'icon'       => $icons[ $i ],
            'desc'       =>
                __('Blueprints can display up to three sections. Only section 1 can be used for Sliders or Tabbed. If it is, the other sections are not available.') . ' <a href="' . PZARC_CODEX . '/" target=_blank class="pzarc-codex" title="View tutorials"><span class="dashicons dashicons-welcome-learn-more size-small"></span></a>',
            'fields'     => array(
                array(
                    'id'       => $prefix . 'section-' . $i . '-panel-layout',
                    'subtitle'=> __('Choose which Panel design to display the content for this Bleuprint.'),
                    'title'    => __('Panels layout', 'pzarchitect'),
                    'type'     => 'select',
                    'validate' => ($i == 0 ? 'not_empty' : null),
                    'select2'  => array('allowClear' => false),
                    'options'  => $pzarc_panels_array
                ),
                array(
                    'title'   => 'Layout type',
                    'id'      => $prefix . 'section-' . $i . '-layout-mode',
                    'type'    => 'image_select',
                    'default' => 'basic',
                    'desc'    => $desc[ (int)($i > 0) ],
                    'height'  => 64,
                    'required'   => array($prefix . 'section-' . $i . '-panel-layout', 'not_empty_and',false),
                    'options' => $modesx[ (int)($i > 0) ],
                    //                ),
                    //                // Layout modes affect section. Navigator types apply to the blueprint
                    //                array(
                    //                    'title'   => __('Layout type', 'pzarchitect'),
                    //                    'id'      => $prefix . 'section-' . $i . '-layout-mode',
                    //                    'type'    => 'button_set',
                    //                    'default' => 'basic',
                    //                    'select2' => array('allowClear' => false),
                    //                    'options' => $modes[ (int)($i > 0) ],
                    //                  'subtitle'    => __('Choose how you want the panels to display. With evenly sized panels, you\'ll see little difference. Please visit <a href="http://isotope.metafizzy.co/demos/layout-modes.html" target=_blank>Isotope Layout Modes</a> for demonstrations of these layouts', 'pzarchitect'),
                    'hint'    => array('title'   => 'Layout types',
                                       'content' => __('<strong>Basic</strong> is for flat layouts like single posts, blog excerpts and magazine grids.<br>
<br><strong>Masonry</strong> is like Basic but formats for a Pinterest-like layout.<br>
<br><strong>Slider</strong> for making sliders like featured posts, image slideshows etc.<br>
<br><strong>Tabbed</strong> for tabbed layouts.<br>
<br><strong>Tabular</strong> displays the content in a table, and applies extra controls.<br>
<br><em>Note: To use Tabular, make sure your Panel is in a table-like layout - that is, all fields on a single row</em><br>
<br><strong>Accordion</strong> for a vertical Accordion layout. i.e. panels in a single column.<br>
',
                                                       'pzarchitect')),
                ),
                array(
                    'id'       => $prefix . 'section-' . $i . '-tabular-title',
                    'title'    => __('Section ' . ($i + 1) . ' Tabular', 'pzarchitect'),
                    'type'     => 'section',
                    'required'   => array($prefix . 'section-' . $i . '-panel-layout', 'not_empty_and',false),
                    'indent'   => true,
                    'required' => array($prefix . 'section-' . $i . '-layout-mode', '=', 'table'),
                ),
                array(
                    'id'         => $prefix . 'section-' . $i . '-table-column-titles',
                    'title'      => __('Table column titles', 'pzarchitect'),
                    'type'       => 'multi_text',
                    'show_empty' => false,
                    'add_text'   => 'Add a title',
                    'required'   => array($prefix . 'section-' . $i . '-layout-mode', '=', 'table'),
                ),
                array(
                    'id'       => $prefix . 'section-' . $i . '-accordion-title',
                    'title'    => __('Section ' . ($i + 1) . ' Accordion', 'pzarchitect'),
                    'type'     => 'section',
                    'indent'   => true,
                    'required' => array($prefix . 'section-' . $i . '-layout-mode', '=', 'accordion'),
                ),
                array(
                    'id'         => $prefix . 'section-' . $i . '-accordion-titles',
                    'title'      => __('Accordion titles', 'pzarchitect'),
                    'type'       => 'multi_text',
                    'show_empty' => false,
                    'add_text'   => 'Add a title',
                    'required'   => array($prefix . 'section-' . $i . '-layout-mode', '=', 'accordion'),
                    'subtitle'   => 'None to use post titles'
                ),
                array(
                    'id'     => $prefix . 'section-' . $i . '-panels-heading',
                    'title'  => __('Section ' . ($i + 1) . ' Panels configuration', 'pzarchitect'),
                    'type'   => 'section',
                    'required'   => array($prefix . 'section-' . $i . '-panel-layout', 'not_empty_and',false),
                    'indent' => true,
                ),
                array(
                    'title'    => __('Limit panels', 'pzarchitect'),
                    'id'       => $prefix . 'section-' . $i . '-panels-limited',
                    'type'     => 'switch',
                    'on'       => __('Yes', 'pzarchitect'),
                    'off'      => __('No', 'pzarchitect'),
                    'default'  => true,
                    'subtitle' => 'Each panel displays a single post from the selected content type.'
                ),
                array(
                    'title'    => __('Panels to show', 'pzarchitect'),
                    'id'       => $prefix . 'section-' . $i . '-panels-per-view',
                    'type'     => 'spinner',
                    'default'  => 1,
                    'min'      => 1,
                    'max'      => 99,
                    'required' => array($prefix . 'section-' . $i . '-panels-limited', '=', true),
                    'subtitle' => __('This is how many posts will show if Limit enabled above', 'pzarchitect'),
                ),
                array(
                    'id'     => $prefix . 'section-' . $i . '-columns-heading',
                    'title'  => __('Section ' . ($i + 1) . ' Columns', 'pzarchitect'),
                    'type'   => 'section',
                    'required'   => array($prefix . 'section-' . $i . '-panel-layout', 'not_empty_and',false),
                    'indent' => true,
                ),
                array(
                    'title'         => __('Columns wide screen', 'pzarchitect'),
                    'subtitle'      => $_architect_options[ 'architect_breakpoint_1' ][ 'width' ] . ' and above',
                    'id'            => $prefix . 'section-' . $i . '-columns-breakpoint-1',
                    'hint'          => array('title'   => __('Columns wide screen', 'pzarchitect'),
                                             'content' => __('Number of columns or panels across on a wide screen as set in the breakpoints options. <br><br>In sliders, this will be the number across.', 'pzarchitect')),
                    'type'          => 'slider',
                    'default'       => 3,
                    'min'           => 1,
                    'max'           => 10,
                    'display_value' => 'label'
                ),
                array(
                    'title'         => __('Columns medium screen', 'pzarchitect'),
                    'subtitle'      => $_architect_options[ 'architect_breakpoint_2' ][ 'width' ] . ' to ' . $_architect_options[ 'architect_breakpoint_1' ][ 'width' ],
                    'id'            => $prefix . 'section-' . $i . '-columns-breakpoint-2',
                    'hint'          => array('title'   => __('Columns medium screen', 'pzarchitect'),
                                             'content' => __('Number of columns or panels across on a medium screen as set in the breakpoints options', 'pzarchitect')),
                    'type'          => 'slider',
                    'default'       => 2,
                    'min'           => 1,
                    'max'           => 10,
                    'display_value' => 'label'
                ),
                array(
                    'title'         => __('Columns narrow screen', 'pzarchitect'),
                    'subtitle'      => $_architect_options[ 'architect_breakpoint_2' ][ 'width' ] . ' and below',
                    'id'            => $prefix . 'section-' . $i . '-columns-breakpoint-3',
                    'hint'          => array('title'   => __('Columns narrow screen', 'pzarchitect'),
                                             'content' => __('Number of columns or panels across on a narrow screen as set in the breakpoints options', 'pzarchitect')),
                    'type'          => 'slider',
                    'default'       => 1,
                    'min'           => 1,
                    'max'           => 10,
                    'display_value' => 'label'
                ),
                array(
                    'id'     => $prefix . 'section-' . $i . '-panels-settings-heading',
                    'title'  => __('Section ' . ($i + 1) . ' Panels design extras', 'pzarchitect'),
                    'type'   => 'section',
                    'required'   => array($prefix . 'section-' . $i . '-panel-layout', 'not_empty_and',false),
                    'indent' => true,
                ),
                array(
                    'title'   => __('Minimum panel width', 'pzarchitect'),
                    'id'      => $prefix . 'section-' . $i . '-min-panel-width',
                    'type'    => 'dimensions',
                    'height'  => false,
                    'units'   => 'px',
                    'default' => array('width' => '0'),
                    //      'hint'  => array('content' => __('Set the minimum width for panels in this section. This helps with responsive layout', 'pzarchitect'))
                ),
                array(
                    'title'   => __('Panels margins', 'pzarchitect'),
                    'id'      => $prefix . 'section-' . $i . '-panels-margins',
                    'type'    => 'spacing',
                    'units'   => array('%', 'px', 'em'),
                    'mode'    => 'margin',
                    'default' => array('right' => '0', 'bottom' => '0', 'left' => '0', 'top' => '0'),
                    //'subtitle' => __('Right, bottom', 'pzarchitect')
                    //    'hint'  => array('content' => __('Set the vertical gutter width as a percentage of the section width. The gutter is the gap between adjoining elements', 'pzarchitect'))
                ),
                array(
                    'id'     => $prefix . 'section-' . $i . '-sections-heading',
                    'title'  => __('Section ' . ($i + 1) . ' Section configuration', 'pzarchitect'),
                    'type'   => 'section',
                    'required'   => array($prefix . 'section-' . $i . '-panel-layout', 'not_empty_and',false),
                    'indent' => true,
                ),
                array(
                    'id'    => $prefix . 'section-' . $i . '-title',
                    'title' => __('Section ' . ($i + 1) . ' title (optional)', 'pzarchitect'),
                    'type'  => 'text',
                ),
                array(
                    'id'      => $prefix . 'sections-width' . $i,
                    'type'    => 'dimensions',
                    //               'mode'    => array('width' => true, 'height' => false),
                    'units'   => array('%', 'px'),
                    'width'   => true,
                    'height'  => false,
                    'title'   => __('Sections width', 'pzarchitect'),
                    'default' => array('width' => '100', 'units' => '%'),
                ),
                array(
                    'id'      => $prefix . 'sections-align' . $i,
                    'type'    => 'button_set',
                    'select2' => array('allowClear' => false),
                    'options' => array('left'   => __('Left', 'pzarchitect'),
                                       'center' => __('Centre', 'pzarchitect'),
                                       'right'  => __('Right', 'pzarchitect')),
                    'title'   => __('Sections align', 'pzarchitect'),
                    'default' => 'center',
                ),

            )

        );
      }

      /** PAGINATION  */
//      $sections[ '_pagination' ] = array(
//          'title'      => __('Pagination', 'pzarchitect'),
//          'show_title' => true,
//          'icon_class' => 'icon-large',
//          'icon'       => 'el-icon-chevron-right',
//          'required'   => array('blueprints_navigation', 'equals', 'pagination'),
//          'fields'     => array(
//
//              array(
//                  'title'    => __('Note', 'pzarchitect'),
//                  'id'       => $prefix . 'pager-info',
//                  'type'     => 'info',
//                  'subtitle' => '<p>' . __('<strong>Pagination will only show on WordPress pages that support it.</strong> These are the blog list page and single posts and pages - but not the front page if set as a static page.', 'pzarchitect') . '</p>' .
//                      __('Also, you should never display more than one pagination element on a page as pagination reloads the page and will therefore affect all paginated content.', 'pzarchitect')
//              ),
//              //            array(
//              //                'id'      => $prefix . 'posts-per-page',
//              //                'title'   => __('Posts per page', 'pzarchitect'),
//              //                'type'    => 'text',
//              //                'default' => 'Do we really need this? Can\'t we just use the total panels per section?',
//              //            ),
//          )
//      );

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
          'title'      => __('Sliders & Tabbed', 'pzarchitect'),
          'show_title' => true,
          'icon_class' => 'icon-large',
          'icon'       => 'el-icon-website',
          //          'desc'       => 'When the navigation type is set to navigator, presentation will always be in a slider form. You can have multiple navigators on a page, thus multiple sliders.',
          'fields'     => array(
              array(
                  'id'      => $prefix . 'navigator',
                  'title'   => __('Type', 'pzarchitect'),
                  'type'    => 'image_select',
                  'default' => 'bullets',
                  'hint'    => array('content' => __('Bullets,Titles, Labels, Numbers, Thumbnails or none', 'pzarchitect')),
                  'height'  => 75,
                  'options' => $slider
              ),
              array(
                  'title'    => 'Position',
                  'id'       => $prefix . 'navigator-position',
                  'type'     => 'image_select',
                  'default'  => 'bottom',
                  'hint'     => array('content' => __('Bottom, top,left, right', 'pzarchitect')),
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
                      array($prefix . 'navigator', '!=', 'thumbs'),
                      array($prefix . 'navigator', '!=', 'none'),
                  )
              ),
              array(
                  'title'    => 'Position',
                  'id'       => $prefix . 'navigator-thumbs-position',
                  'type'     => 'image_select',
                  'default'  => 'bottom',
                  'hint'     => array('content' => __('Bottom, top', 'pzarchitect')),
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
                      array($prefix . 'navigator', '=', 'thumbs'),
                  )
              ),
              array(
                  'title'    => 'Location',
                  'id'       => $prefix . 'navigator-location',
                  'hint'     => array('content' => __('Select whether navigator should appear over the content area, or outside of it', 'pzarchitect')),
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
                      array($prefix . 'navigator', '!=', 'accordion'),
                      array($prefix . 'navigator', '!=', 'none'),
                      array($prefix . 'navigator', '!=', 'tabbed'),
                      array($prefix . 'navigator', '!=', 'thumbs'),
                      array($prefix . 'navigator-position', '!=', 'left'),
                      array($prefix . 'navigator-position', '!=', 'right')
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
                      array($prefix . 'navigator', '!=', 'accordion'),
                      array($prefix . 'navigator', '!=', 'buttons'),
                      array($prefix . 'navigator', '!=', 'thumbs'),
                      array($prefix . 'navigator', '!=', 'none'),
                      array($prefix . 'navigator-position', '!=', 'left'),
                      array($prefix . 'navigator-position', '!=', 'right')
                  )
              ),
              array(
                  'title'    => 'Vertical width',
                  'id'       => $prefix . 'navigator-vertical-width',
                  'type'     => 'dimensions',
                  'default'  => array('width' => '15%'),
                  'height'   => false,
                  'units'    => '%',
                  'required' => array(
                      array($prefix . 'navigator', '!=', 'thumbs'),
                      array($prefix . 'navigator', '!=', 'accordion'),
                      array($prefix . 'navigator', '!=', 'none'),
                      array($prefix . 'navigator-position', '!=', 'top'),
                      array($prefix . 'navigator-position', '!=', 'bottom')
                  )
              ),
              array(
                  'title'      => 'Labels',
                  'id'         => $prefix . 'navigator-labels',
                  'type'       => 'multi_text',
                  'show_empty' => false,
                  'add_text'   => 'Add a label',
                  'default'    => 'Label name',
                  'required'   => array(
                      array($prefix . 'navigator', 'equals', 'labels'),
                  ),
                  'subtitle'   => 'One label per post etc.'
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
                      array($prefix . 'navigator', 'equals', 'bullets'),
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
                      array($prefix . 'navigator', '!=', 'none'),
                      array($prefix . 'navigator', '!=', 'thumbs'),
                  )
              ),
              array(
                  'title'    => 'Thumb dimensions',
                  'id'       => $prefix . 'navigator-thumb-dimensions',
                  'type'     => 'dimensions',
                  'default'  => array('width' => '50px', 'height' => '50px'),
                  'units'    => 'px',
                  'required' => array(
                      array($prefix . 'navigator', '=', 'thumbs'),
                  )
              ),
              array(
                  'title'    => 'Pager',
                  'id'       => $prefix . 'navigator-pager',
                  'type'     => 'button_set',
                  'default'  => 'hover',
                  'options'  => array(
                      'none'  => 'None',
                      'hover' => 'Hover over panels',
                      /// TODO: Add inline pager to nav
                      //                      'inline' => 'Inline with navigator',
                      //                      'both'   => 'Both'
                  ),
                  'required' => array($prefix . 'section-0-layout-mode', '=', 'slider'),
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
                      'circle' => __('Circle', 'pzarchitect'),
                      'square' => __('Square', 'pzarchitect'),
                  ),
                  'required' => array(
                      array($prefix . 'navigator', '=', 'thumbs'),
                  )
              ),
              array(
                  'id'       => $prefix . 'navigator-skip-thumbs',
                  'title'    => __('Number of thumbnails', 'pzarchitect'),
                  'type'     => 'spinner',
                  'default'  => 5,
                  'min'      => 1,
                  'max'      => 100,
                  'subtitle' => __('Number of thumbnails to show at once in the navigator. This is also the number of thumbs skipped by by the navigator forward and back buttons', 'pzarchitect'),
                  'required' => array(
                      array($prefix . 'navigator', 'equals', 'thumbs'),
                  )
              ),
              /** TRANSITIONS
               ******************/

              array(
                  'title'    => __('Transitions', 'pzarchitect'),
                  'id'       => $prefix . 'section-transitions-heading',
                  'type'     => 'section',
                  'indent'   => true,
                  'required' => array($prefix . 'section-0-layout-mode', '=', 'slider'),
              ),
              array(
                  'title'    => 'Type',
                  'id'       => $prefix . 'transitions-type',
                  'type'     => 'button_set',
                  'default'  => 'slide',
                  //              'select2' => array('allowClear' => false),
                  'required' => array($prefix . 'section-0-layout-mode', '=', 'slider'),
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
                  'hint'          => array('content' => __('Time taken for the transition to display', 'pzarchitect')),
                  'default'       => 2,
                  'display_value' => 'label',
                  'required'      => array($prefix . 'section-0-layout-mode', '=', 'slider'),
              ),
              array(
                  'title'         => 'Interval (seconds)',
                  'id'            => $prefix . 'transitions-interval',
                  'type'          => 'slider',
                  'resolution'    => 0.1,
                  'step'          => 0.5,
                  'default'       => 5,
                  'min'           => 0,
                  'max'           => 60,
                  'display_value' => 'label',
                  'desc'          => __('Set to zero to disable autoplay', 'pzarchitect'),
                  'hint'          => array('content' => __('Time slide is shown with no transitions active. Set to zero to disable autoplay', 'pzarchitect')),
                  'required'      => array($prefix . 'section-0-layout-mode', '=', 'slider'),
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


//    $sections[ ] = array(
//        'title'      => __('Wireframe Preview', 'pzarchitect'),
//        'show_title' => true,
//        'icon_class' => 'icon-large',
//        'icon'       => 'el-icon-th-large',
//        'fields'     => array(
//
//            array(
//                'title'    => 'Sections',
//                'id'       => $prefix . 'sections-preview',
//                'type'     => 'raw',
//                'readonly' => false, // Readonly fields can't be written to by code! Weird
//                'content'  => pzarc_draw_sections_preview(),
//            ),
//        )
//    );

      $sections[ '_usingbp' ] = array(
          'title'      => 'Using Blueprints',
          'icon_class' => 'icon-large',
          'icon'       => 'el-icon-info-sign',
          'fields'     => array(

              array(
                  'title'    => __('Displaying Blueprints', 'pzarchitect'),
                  'id'       => $prefix . 'help-blueprints',
                  'type'     => 'raw',
                  'class'    => 'plain',
                  'markdown' => true,
                  'content'  => ($defaults_only?'':file_get_contents(PZARC_DOCUMENTATION_PATH . PZARC_LANGUAGE . '/using-blueprints.md')),
                  'pzarchitect'),
              array(
                  'title'    => __('Online documentation', 'pzarchitect'),
                  'id'       => $prefix . 'help-usingbp-online-docs',
                  'type'     => 'raw',
                  'markdown' => false,
                  'content'  => '<a href="http://architect4wp.com/codex-listings/" target=_blank>'.__('Architect Online Documentation','pzarchitect').'</a><br>'. __('This is a growing resource. Please check back regularly.', 'pzarchitect')

              ),
          )
      );
      $sections[ '_help' ]    = array(
          'title'      => 'Help',
          'icon_class' => 'icon-large',
          'icon'       => 'el-icon-question-sign',
          'fields'     => array(

              array(
                  'title'    => __('Blueprints videos', 'pzarchitect'),
                  'id'       => $prefix . 'help-blueprints-videos',
                  'subtitle'=> __('Internet connection required'),
                  'type'     => 'raw',
                  'class'    => 'plain',
                  'markdown' => false,
                  'content'=>($defaults_only?'':@file_get_contents('https://s3.amazonaws.com/341public/architect/blueprints-videos.html'))
              ),
              array(
                  'title'    => __('Online documentation', 'pzarchitect'),
                  'id'       => $prefix . 'help-layout-online-docs',
                  'type'     => 'raw',
                  'markdown' => false,
                  'content'  => '<a href="http://architect4wp.com/codex-listings/" target=_blank>'.__('Architect Online Documentation','pzarchitect').'</a><br>'. __('This is a growing resource. Please check back regularly.', 'pzarchitect')

              ),

          )
      );

      $metaboxes[ ] = array(
          'id'         => 'layout-settings',
          'title'      => 'Blueprint Layout',
          'post_types' => array('arc-blueprints'),
          'sections'   => $sections,
          'position'   => 'normal',
          'priority'   => 'high',
          'sidebar'    => false

      );

      return $metaboxes;
    }


    function pzarc_draw_sections_preview()
    {
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

    /**
     *
     * CONTENT
     *
     * @param array $metaboxes
     * @return array
     */
    function pzarc_blueprint_contents_mb($metaboxes,$defaults_only = false)
    {

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

      $content_post_types = (array)$registry->get('post_types');
      $content_types      = array();
      foreach ($content_post_types as $key => $value) {
        if (isset($value[ 'blueprint-content' ])) {
          $content_types[ $value[ 'blueprint-content' ][ 'type' ] ] = $value[ 'blueprint-content' ][ 'name' ];
        }
      }
      /** GENERAL  Settings*/
      $blueprint_content_common = $registry->get('blueprint-content-common');

      // If you add/remove a content type, you have to add/remove it's side tab too
      $prefix                 = '_content_general_';
      $sections[ '_general' ] = array(
          'title'      => 'Source',
          'icon_class' => 'icon-large',
          'icon'       => 'el-icon-folder',
          'fields'     => array(
              array(
                  'title'    => __('Content source', 'pzarchitect'),
                  'id'       => '_blueprints_content-source',
                  'type'     => 'button_set',
                  //                  'select2'  => array('allowClear' => false),
                  'default'  => 'defaults',
                  'options'  => $content_types,
                  'subtitle' => (array_key_exists('snippets', $content_types) ? '' : 'Several more content sources supported in Architect Pro version, including Pages, Snippets, Galleries, Custom Post Types and a special Dummy option to display dummy content')
              ),
          )
      );


      /** DISPLAY ALL THE CONTENT TYPES FORMS */
      foreach ($content_post_types as $key => $value) {
        if (isset($value[ 'blueprint-content' ])) {
          foreach ($value[ 'blueprint-content' ][ 'sections' ][ 'fields' ] as $k => $v) {
            $v[ 'required' ][ ]                    = array('_blueprints_content-source',
                                                           'equals',
                                                           $value[ 'blueprint-content' ][ 'type' ]);
            $sections[ '_general' ][ 'fields' ][ ] = $v;
          }
        }
      }
      /** FILTERS */
      $sections[ '_settings' ] = $blueprint_content_common[ 0 ][ 'settings' ][ 'sections' ];
      $sections[ '_filters' ]  = $blueprint_content_common[ 0 ][ 'filters' ][ 'sections' ];


      $prefix                      = '_content_help_';
      $sections[ '_content_help' ] = array(
          'title'      => 'Help',
          'icon_class' => 'icon-large',
          'icon'       => 'el-icon-question-sign',
          'fields'     => array(

              array(
                  'title'    => __('Snippets', 'pzarchitect'),
                  'id'       => $prefix . 'help-content-selection',
                  'type'     => 'raw',
                  'markdown' => true,
                  'content'  => __('With Architect Pro you can enable an extra content type called *Snippets*.
  These give you a third method of creating content that doesn\'t fit into the post or page types.
It came about with my own need to create grids of product features. I didn\'t want to fill up pages or posts, so created Snippets for these small content bites.
You can use them however you like though, e.g Testimonials, FAQs, Features, Contacts, etc.
                ', 'pzarchitect')

              ),
              array(
                  'title'    => __('Blueprints videos', 'pzarchitect'),
                  'subtitle'=> __('Internet connection required'),
                  'id'       => $prefix . 'help-blueprints-videos',
                  'type'     => 'raw',
                  'class'    => 'plain',
                  'markdown' => false,
                  'content'=>($defaults_only?'':@file_get_contents('https://s3.amazonaws.com/341public/architect/blueprints-videos.html'))
              ),
              array(
                  'title'    => __('Online documentation', 'pzarchitect'),
                  'id'       => $prefix . 'help-content-online-docs',
                  'type'     => 'raw',
                  'markdown' => false,
                  'content'  => '<a href="http://architect4wp.com/codex-listings/" target=_blank>'.__('Architect Online Documentation','pzarchitect').'</a><br>'. __('This is a growing resource. Please check back regularly.', 'pzarchitect')

              ),

          )
      );

      $metaboxes[ ] = array(
          'id'         => 'content-selections',
          'title'      => 'Panel Content',
          'post_types' => array('arc-blueprints'),
          'sections'   => $sections,
          'position'   => 'normal',
          'priority'   => 'high',
          'sidebar'    => false

      );

      return $metaboxes;

    }

    /**
     * pzarc_blueprint_layout_styling
     * @param $metaboxes
     * @return array
     */
    function pzarc_blueprint_layout_styling_mb($metaboxes,$defaults_only = false)
    {
      global $_architect_options;
      if (empty($_architect_options)) {
        $_architect_options = get_option('_architect_options');
      }
      if (empty($_architect)) {
        $_architect = get_option('_architect');
      }
      if (!empty($_architect_options[ 'architect_enable_styling' ])) {
        $defaults = get_option('_architect');
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

        $sections[ '_styling_general' ]          = array(
            'title'      => 'General',
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
                    'title'    => __('Blueprint', 'pzarchitect'),
                    'id'       => $prefix . 'blueprint-section-blueprint',
                    'type'     => 'section',
                    'indent'   => true,
                    'class'    => 'heading',
                    'subtitle' => 'Class: .pzarc-blueprint_{shortname}',
                ),

                // TODO: Add shadows
                pzarc_redux_bg($prefix . $thisSection . $background, $this->defaults ? '' : $_architect[ 'architect_config_' . $thisSection . '-selectors' ], $defaults[ $optprefix . $thisSection . $background ]),
                pzarc_redux_padding($prefix . $thisSection . $padding, $this->defaults ? '' : $_architect[ 'architect_config_' . $thisSection . '-selectors' ], $defaults[ $optprefix . $thisSection . $background ]),
                pzarc_redux_margin($prefix . $thisSection . $margin, $this->defaults ? '' : $_architect[ 'architect_config_' . $thisSection . '-selectors' ], $defaults[ $optprefix . $thisSection . $background ]),
                pzarc_redux_borders($prefix . $thisSection . $border, $this->defaults ? '' : $_architect[ 'architect_config_' . $thisSection . '-selectors' ], $defaults[ $optprefix . $thisSection . $background ]),
                pzarc_redux_links($prefix . $thisSection . $link, $this->defaults ? '' : $_architect[ 'architect_config_' . $thisSection . '-selectors' ], $defaults[ $optprefix . $thisSection . $background ]),
                array(
                    'title'    => __('Custom CSS', 'pzarchitect'),
                    'id'       => $prefix . 'blueprint-custom-css',
                    'type'     => 'ace_editor',
                    'mode'     => 'css',
                    'subtitle' => __('This can be any CSS you\'d like to add to a page this blueprint is displayed on. It will ONLY load on the pages this blueprint is shown on, so will only impact those pages. However, if you have multiple blueprints on a page, this CSS could affect or be overriden by ther blueprints\' custom CSS.', 'pzarchitect'),
                    //                'hint'  => array('content' => __('This is can be any CSS you\'d like to add to a page this blueprint is displayed on. It will ONLY load on the pages this blueprint is shown on, so will only impact those pages. However, if you have multiple blueprints on a page, this CSS could affect or be overriden by ther blueprints\' custom CSS.', 'pzarchitect')),
                )
            )
        );
        $thisSection                             = 'sections';
        $sections[ '_styling_sections_wrapper' ] = array(
            'title'      => 'Sections wrapper',
            'show_title' => false,
            'icon_class' => 'icon-large',
            'icon'       => 'el-icon-check-empty',
            'desc'       => 'Class: .pzarc-sections_{shortname}',
            'fields'     => pzarc_fields(

            // TODO: Add shadows
            //$prefix . 'hentry' . $background, $_architect[ 'architect_config_hentry-selectors' ], $defaults[ $optprefix . 'hentry' . $background ]
                pzarc_redux_bg($prefix . $thisSection . $background, $this->defaults ? '' : $_architect[ 'architect_config_' . $thisSection . '-selectors' ], $defaults[ $optprefix . $thisSection . $background ]),
                pzarc_redux_padding($prefix . $thisSection . $padding, $this->defaults ? '' : $_architect[ 'architect_config_' . $thisSection . '-selectors' ], $defaults[ $optprefix . $thisSection . $padding ]),
                pzarc_redux_margin($prefix . $thisSection . $margin, $this->defaults ? '' : $_architect[ 'architect_config_' . $thisSection . '-selectors' ], $defaults[ $optprefix . $thisSection . $margin ]),
                pzarc_redux_borders($prefix . $thisSection . $border, $this->defaults ? '' : $_architect[ 'architect_config_' . $thisSection . '-selectors' ], $defaults[ $optprefix . $thisSection . $border ])
            )
        );
        $icons                                   = array(1 => 'el-icon-align-left',
                                                         2 => 'el-icon-th',
                                                         3 => 'el-icon-th-list');

        $thisSection                         = 'section_1';
        $sections[ '_styling_section_1' ]    = array(
            'title'      => 'Section 1',
            'show_title' => false,
            'icon_class' => 'icon-large',
            'icon'       => $icons[ 1 ],
            'desc'       => 'Class: ' . $this->defaults ? '' : $_architect[ 'architect_config_' . $thisSection . '-selectors' ],
            'fields'     => array(
                pzarc_redux_bg($prefix . $thisSection . $background, $this->defaults ? '' : $_architect[ 'architect_config_' . $thisSection . '-selectors' ], $defaults[ $optprefix . $thisSection . $background ]),
                pzarc_redux_padding($prefix . $thisSection . $padding, $this->defaults ? '' : $_architect[ 'architect_config_' . $thisSection . '-selectors' ], $defaults[ $optprefix . $thisSection . $padding ]),
                pzarc_redux_margin($prefix . $thisSection . $margin, $this->defaults ? '' : $_architect[ 'architect_config_' . $thisSection . '-selectors' ], $defaults[ $optprefix . $thisSection . $margin ]),
                pzarc_redux_borders($prefix . $thisSection . $border, $this->defaults ? '' : $_architect[ 'architect_config_' . $thisSection . '-selectors' ], $defaults[ $optprefix . $thisSection . $border ])
            ),
        );
        $thisSection                         = 'section_2';
        $sections[ '_styling_section_2' ]    = array(
            'title'      => 'Section 2',
            'show_title' => false,
            'icon_class' => 'icon-large',
            'icon'       => $icons[ 2 ],
            'desc'       => 'Class: ' . $this->defaults ? '' : $_architect[ 'architect_config_' . $thisSection . '-selectors' ],
            'fields'     => array(
                pzarc_redux_bg($prefix . $thisSection . $background, $this->defaults ? '' : $_architect[ 'architect_config_' . $thisSection . '-selectors' ], $defaults[ $optprefix . $thisSection . $background ]),
                pzarc_redux_padding($prefix . $thisSection . $padding, $this->defaults ? '' : $_architect[ 'architect_config_' . $thisSection . '-selectors' ], $defaults[ $optprefix . $thisSection . $padding ]),
                pzarc_redux_margin($prefix . $thisSection . $margin, $this->defaults ? '' : $_architect[ 'architect_config_' . $thisSection . '-selectors' ], $defaults[ $optprefix . $thisSection . $margin ]),
                pzarc_redux_borders($prefix . $thisSection . $border, $this->defaults ? '' : $_architect[ 'architect_config_' . $thisSection . '-selectors' ], $defaults[ $optprefix . $thisSection . $border ])
            ),
        );
        $thisSection                         = 'section_3';
        $sections[ '_styling_section_3' ]    = array(
            'title'      => 'Section 3',
            'show_title' => false,
            'icon_class' => 'icon-large',
            'icon'       => $icons[ 3 ],
            'desc'       => 'Class: ' . $this->defaults ? '' : $_architect[ 'architect_config_' . $thisSection . '-selectors' ],
            'fields'     => array(
                pzarc_redux_bg($prefix . $thisSection . $background, $this->defaults ? '' : $_architect[ 'architect_config_' . $thisSection . '-selectors' ], $defaults[ $optprefix . $thisSection . $background ]),
                pzarc_redux_padding($prefix . $thisSection . $padding, $this->defaults ? '' : $_architect[ 'architect_config_' . $thisSection . '-selectors' ], $defaults[ $optprefix . $thisSection . $padding ]),
                pzarc_redux_margin($prefix . $thisSection . $margin, $this->defaults ? '' : $_architect[ 'architect_config_' . $thisSection . '-selectors' ], $defaults[ $optprefix . $thisSection . $margin ]),
                pzarc_redux_borders($prefix . $thisSection . $border, $this->defaults ? '' : $_architect[ 'architect_config_' . $thisSection . '-selectors' ], $defaults[ $optprefix . $thisSection . $border ])
            ),
        );
        $thisSection                         = 'navigator';
        $sections[ '_styling_slidertabbed' ] = array(
            'title'      => 'Sliders & Tabbed',
            'show_title' => false,
            'icon_class' => 'icon-large',
            'icon'       => 'el-icon-website',
            'fields'     => array(

                array(
                    'title'    => __('Navigator container', 'pzarchitect'),
                    'id'       => $prefix . 'blueprint-nav-container-css-heading',
                    'type'     => 'section',
                    'indent'   => true,
                    'subtitle' => 'Classes: ' . implode(', ', array('.arc-slider-nav', '.pzarc-navigator')),

                ),
                pzarc_redux_bg($prefix . $thisSection . $background, $_architect[ 'architect_config_' . $thisSection . '-selectors' ], $defaults[ $optprefix . $thisSection . $background ]),
                pzarc_redux_padding($prefix . $thisSection . $padding, $_architect[ 'architect_config_' . $thisSection . '-selectors' ], $defaults[ $optprefix . $thisSection . $padding ]),
                pzarc_redux_margin($prefix . $thisSection . $margin, $_architect[ 'architect_config_' . $thisSection . '-selectors' ], $defaults[ $optprefix . $thisSection . $margin ]),
                pzarc_redux_borders($prefix . $thisSection . $border, $_architect[ 'architect_config_' . $thisSection . '-selectors' ], $defaults[ $optprefix . $thisSection . $border ]),
                array(
                    'title'    => __('Navigator items', 'pzarchitect'),
                    'id'       => $prefix . 'blueprint-nav-items-css-heading',
                    'type'     => 'section',
                    'indent'   => true,
                    'subtitle' => 'Class: ' . implode(', ', array('.pzarc-navigator .arc-slider-slide-nav-item')),

                ),
                pzarc_redux_font($prefix . $thisSection . '-items' . $font, $_architect[ 'architect_config_' . $thisSection . '-selectors' ], $defaults[ $optprefix . $thisSection . '-items' . $font ]),
                pzarc_redux_bg($prefix . $thisSection . '-items' . $background, $_architect[ 'architect_config_' . $thisSection . '-selectors' ], $defaults[ $optprefix . $thisSection . '-items' . $background ]),
                pzarc_redux_padding($prefix . $thisSection . '-items' . $padding, $_architect[ 'architect_config_' . $thisSection . '-selectors' ], $defaults[ $optprefix . $thisSection . '-items' . $padding ]),
                pzarc_redux_margin($prefix . $thisSection . '-items' . $margin, $_architect[ 'architect_config_' . $thisSection . '-selectors' ], $defaults[ $optprefix . $thisSection . '-items' . $margin ]),
                pzarc_redux_borders($prefix . $thisSection . '-items' . $border, $_architect[ 'architect_config_' . $thisSection . '-selectors' ], $defaults[ $optprefix . $thisSection . '-items' . $border ]),
                pzarc_redux_border_radius($prefix . $thisSection . '-items' . $borderradius, $_architect[ 'architect_config_' . $thisSection . '-selectors' ], $defaults[ $optprefix . $thisSection . '-items' . $borderradius ]),
                array(
                    'title'    => __('Navigator item hover', 'pzarchitect'),
                    'id'       => $prefix . 'blueprint-nav-hover-item-css-heading',
                    'type'     => 'section',
                    'indent'   => true,
                    'subtitle' => 'Class: ' . implode(', ', array('.pzarc-navigator .arc-slider-slide-nav-item:hover')),

                ),
                pzarc_redux_font($prefix . $thisSection . '-items-hover' . $font, $_architect[ 'architect_config_' . $thisSection . '-selectors' ], $defaults[ $optprefix . $thisSection . '-items-hover' . $font ], array('letter-spacing',
                                                                                                                                                                                                                           'font-variant',
                                                                                                                                                                                                                           'text-transform',
                                                                                                                                                                                                                           'font-family',
                                                                                                                                                                                                                           'font-style',
                                                                                                                                                                                                                           'text-align',
                                                                                                                                                                                                                           'line-height',
                                                                                                                                                                                                                           'word-spacing')),
                pzarc_redux_bg($prefix . $thisSection . '-items-hover' . $background, $_architect[ 'architect_config_' . $thisSection . '-selectors' ], $defaults[ $optprefix . $thisSection . '-items-hover' . $background ]),
                pzarc_redux_borders($prefix . $thisSection . '-items-hover' . $border, $_architect[ 'architect_config_' . $thisSection . '-selectors' ], $defaults[ $optprefix . $thisSection . '-items-hover' . $border ]),
                array(
                    'title'    => __('Navigator active item', 'pzarchitect'),
                    'id'       => $prefix . 'blueprint-nav-active-item-css-heading',
                    'type'     => 'section',
                    'indent'   => true,
                    'subtitle' => 'Class: ' . implode(', ', array('.pzarc-navigator .arc-slider-slide-nav-item.active')),

                ),
                pzarc_redux_font($prefix . $thisSection . '-items-active' . $font, $_architect[ 'architect_config_' . $thisSection . '-selectors' ], $defaults[ $optprefix . $thisSection . '-items-active' . $font ], array('letter-spacing',
                                                                                                                                                                                                                             'font-variant',
                                                                                                                                                                                                                             'text-transform',
                                                                                                                                                                                                                             'font-family',
                                                                                                                                                                                                                             'font-style',
                                                                                                                                                                                                                             'text-align',
                                                                                                                                                                                                                             'line-height',
                                                                                                                                                                                                                             'word-spacing')),
                pzarc_redux_bg($prefix . $thisSection . '-items-active' . $background, $_architect[ 'architect_config_' . $thisSection . '-selectors' ], $defaults[ $optprefix . $thisSection . '-items-active' . $background ]),
                pzarc_redux_borders($prefix . $thisSection . '-items-active' . $border, $_architect[ 'architect_config_' . $thisSection . '-selectors' ], $defaults[ $optprefix . $thisSection . '-items-active' . $border ])
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
                    'title'    => __('Titles', 'pzarchitect'),
                    'id'       => $prefix . 'blueprint-accordion-css-heading',
                    'type'     => 'section',
                    'indent'   => true,
                    'subtitle' => 'Class: ' . $_architect[ 'architect_config_' . $thisSection . '-selectors' ]

                ),
                pzarc_redux_font($prefix . $thisSection . $font, $this->defaults ? '' : $_architect[ 'architect_config_' . $thisSection . '-selectors' ], $defaults[ $optprefix . $thisSection . $font ]),
                pzarc_redux_bg($prefix . $thisSection . $background, $this->defaults ? '' : $_architect[ 'architect_config_' . $thisSection . '-selectors' ], $defaults[ $optprefix . $thisSection . $background ]),
                pzarc_redux_padding($prefix . $thisSection . $padding, $this->defaults ? '' : $_architect[ 'architect_config_' . $thisSection . '-selectors' ], $defaults[ $optprefix . $thisSection . $padding ]),
                pzarc_redux_margin($prefix . $thisSection . $margin, $this->defaults ? '' : $_architect[ 'architect_config_' . $thisSection . '-selectors' ], $defaults[ $optprefix . $thisSection . $margin ]),
                pzarc_redux_borders($prefix . $thisSection . $border, $this->defaults ? '' : $_architect[ 'architect_config_' . $thisSection . '-selectors' ], $defaults[ $optprefix . $thisSection . $border ]),
                array(
                    'title'    => __('Open', 'pzarchitect'),
                    'id'       => $prefix . 'blueprint-accordion-open-css-heading',
                    'type'     => 'section',
                    'indent'   => true,
                    'subtitle' => 'Class: ' . $_architect[ 'architect_config_' . $thisSection . '-open-selectors' ]
                ),
                pzarc_redux_font($prefix . $thisSection . '-open' . $font, $this->defaults ? '' : $_architect[ 'architect_config_' . $thisSection . '-open-selectors' ], $defaults[ $optprefix . $thisSection . '-open' . $font ]),
                pzarc_redux_bg($prefix . $thisSection . '-open' . $background, $this->defaults ? '' : $_architect[ 'architect_config_' . $thisSection . '-open-selectors' ], $defaults[ $optprefix . $thisSection . '-open' . $background ]),
                pzarc_redux_borders($prefix . $thisSection . '-open' . $border, $this->defaults ? '' : $_architect[ 'architect_config_' . $thisSection . '-open-selectors' ], $defaults[ $optprefix . $thisSection . '-open' . $border ]),
                array(
                    'title'    => __('Hover', 'pzarchitect'),
                    'id'       => $prefix . 'blueprint-accordion-hover-css-heading',
                    'type'     => 'section',
                    'indent'   => true,
                    'subtitle' => 'Class: ' . $_architect[ 'architect_config_' . $thisSection . '-hover-selectors' ]
                ),
                pzarc_redux_font($prefix . $thisSection . '-hover' . $font, $this->defaults ? '' : $_architect[ 'architect_config_' . $thisSection . '-hover-selectors' ], $defaults[ $optprefix . $thisSection . '-hover' . $font ]),
                pzarc_redux_bg($prefix . $thisSection . '-hover' . $background, $this->defaults ? '' : $_architect[ 'architect_config_' . $thisSection . '-hover-selectors' ], $defaults[ $optprefix . $thisSection . '-hover' . $background ]),
                pzarc_redux_borders($prefix . $thisSection . '-hover' . $border, $this->defaults ? '' : $_architect[ 'architect_config_' . $thisSection . '-hover-selectors' ], $defaults[ $optprefix . $thisSection . '-hover' . $border ]),
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
                    'title'    => __('Headings', 'pzarchitect'),
                    'id'       => $prefix . 'blueprint-tabular-css-heading',
                    'type'     => 'section',
                    'subtitle' => 'Class: ' . $this->defaults ? '' : $_architect[ 'architect_config_' . $thisSection . '-selectors' ],
                    'indent'   => true,

                ),
                pzarc_redux_font($prefix . $thisSection . '-headings' . $font, $this->defaults ? '' : $_architect[ 'architect_config_' . $thisSection . '-headings-selectors' ], $defaults[ $optprefix . $thisSection . '-headings' . $font ]),
                pzarc_redux_bg($prefix . $thisSection . '-headings' . $background, $this->defaults ? '' : $_architect[ 'architect_config_' . $thisSection . '-headings-selectors' ], $defaults[ $optprefix . $thisSection . '-headings' . $background ]),
                pzarc_redux_padding($prefix . $thisSection . '-headings' . $padding, $this->defaults ? '' : $_architect[ 'architect_config_' . $thisSection . '-headings-selectors' ], $defaults[ $optprefix . $thisSection . '-headings' . $padding ]),
                pzarc_redux_margin($prefix . $thisSection . '-headings' . $margin, $this->defaults ? '' : $_architect[ 'architect_config_' . $thisSection . '-headings-selectors' ], $defaults[ $optprefix . $thisSection . '-headings' . $margin ]),
                pzarc_redux_borders($prefix . $thisSection . '-headings' . $border, $this->defaults ? '' : $_architect[ 'architect_config_' . $thisSection . '-headings-selectors' ], $defaults[ $optprefix . $thisSection . '-headings' . $border ]),
                array(
                    'title'    => __('Odd rows', 'pzarchitect'),
                    'id'       => $prefix . 'blueprint-tabular-odd-rows-css-heading',
                    'type'     => 'section',
                    'subtitle' => 'Class: ' . $this->defaults ? '' : $_architect[ 'architect_config_' . $thisSection . '-odd-rows-selectors' ],
                    'indent'   => true,

                ),
                pzarc_redux_font($prefix . $thisSection . '-odd-rows' . $font, $this->defaults ? '' : $_architect[ 'architect_config_' . $thisSection . '-odd-rows-selectors' ], $defaults[ $optprefix . $thisSection . '-odd-rows' . $font ]),
                pzarc_redux_bg($prefix . $thisSection . '-odd-rows' . $background, $this->defaults ? '' : $_architect[ 'architect_config_' . $thisSection . '-odd-rows-selectors' ], $defaults[ $optprefix . $thisSection . '-odd-rows' . $background ]),
                pzarc_redux_borders($prefix . $thisSection . '-odd-rows' . $border, $this->defaults ? '' : $_architect[ 'architect_config_' . $thisSection . '-odd-rows-selectors' ], $defaults[ $optprefix . $thisSection . '-odd-rows' . $border ]),
                array(
                    'title'    => __('Even rows', 'pzarchitect'),
                    'id'       => $prefix . 'blueprint-tabular-even-rows-css-heading',
                    'type'     => 'section',
                    'subtitle' => 'Class: ' . $this->defaults ? '' : $_architect[ 'architect_config_' . $thisSection . '-even-rows-selectors' ],
                    'indent'   => true,

                ),
                pzarc_redux_font($prefix . $thisSection . '-even-rows' . $font, $this->defaults ? '' : $_architect[ 'architect_config_' . $thisSection . '-even-rows-selectors' ], $defaults[ $optprefix . $thisSection . '-even-rows' . $font ]),
                pzarc_redux_bg($prefix . $thisSection . '-even-rows' . $background, $this->defaults ? '' : $_architect[ 'architect_config_' . $thisSection . '-even-rows-selectors' ], $defaults[ $optprefix . $thisSection . '-even-rows' . $background ]),
                pzarc_redux_borders($prefix . $thisSection . '-even-rows' . $border, $this->defaults ? '' : $_architect[ 'architect_config_' . $thisSection . '-even-rows-selectors' ], $defaults[ $optprefix . $thisSection . '-even-rows' . $border ]),
            )
        );


        $sections[ '_styling_help' ] = array(
            'id'         => 'blueprint-styling-help',
            'title'      => 'Help',
            'icon_class' => 'icon-large',
            'icon'       => 'el-icon-question-sign',
            'fields'     => array(

                array(
                    'title'    => __('Blueprint styling', 'pzarchitect'),
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
                    'title'    => __('Online documentation', 'pzarchitect'),
                    'id'       => $prefix . 'help-content-online-docs',
                    'type'     => 'raw',
                    'markdown' => false,
                    'content'  => '<a href="http://architect4wp.com/codex-listings/" target=_blank>'.__('Architect Online Documentation','pzarchitect').'</a><br>'. __('This is a growing resource. Please check back regularly.', 'pzarchitect')

                ),
            )
        );
        $metaboxes[ ]                = array(
            'id'         => 'blueprint-stylings',
            'title'      => 'Blueprint Styling',
            'post_types' => array('arc-blueprints'),
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

  } // EOC

