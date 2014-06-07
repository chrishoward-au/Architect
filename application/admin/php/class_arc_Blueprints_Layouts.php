<?php
  $redux_opt_name = '_architect';

  class arc_Blueprints_Layouts
  {


    /**
     * [__construct description]
     */
    function __construct()
    {
      add_action('init', array($this, 'create_blueprints_post_type'));
      // This overrides the one in the parent class

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
            $pzarc_panels_array[ $pzarc_cell->ID ] = (empty($pzarc_cell->post_title) ? 'No title' : $pzarc_cell->post_title);
          }
        } else {
          $pzarc_panels_array = array(0 => 'No cell layouts. Create some.');
        }

        add_action('admin_head', array($this, 'content_blueprints_admin_head'));
        add_action('admin_enqueue_scripts', array($this, 'content_blueprints_admin_enqueue'));
        add_filter('manage_arc-blueprints_posts_columns', array($this, 'add_blueprint_columns'));
        add_action('manage_arc-blueprints_posts_custom_column', array($this, 'add_blueprint_column_content'), 10, 2);

      }

    }

    /**
     * [content_blueprints_admin_enqueue description]
     * @param  [type] $hook [description]
     * @return [type]       [description]
     */
    public function content_blueprints_admin_enqueue($hook)
    {
      $screen = get_current_screen();
      if ('arc-blueprints' == $screen->id) {


        wp_enqueue_style('pzarc-admin-blueprints-css', PZARC_PLUGIN_APP_URL . '/admin/css/arc-admin-blueprints.css');

        wp_enqueue_script('jquery-pzarc-metaboxes-blueprints', PZARC_PLUGIN_APP_URL . '/admin/js/arc-metaboxes-blueprints.js', array('jquery'));
        wp_enqueue_script('js-isotope-v2');
        // wp_enqueue_script('jquery-masonary', PZARC_PLUGIN_URL . 'includes/masonry.pkgd.min.js', array('jquery'));
        // wp_enqueue_script('jquery-lorem', PZARC_PLUGIN_URL . 'includes/jquery.lorem.js', array('jquery'));
      }
    }

    /**
     * [content_blueprints_admin_head description]
     * @return [type] [description]
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
          'panels'                     => __('Panels', 'pzarchitect'),
          '_blueprints_content-source' => __('Content source', 'pzarchitect'),
          'navigation'                 => __('Navigation', 'pzarchitect'),
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

      $post_meta = get_post_meta($post_id, '_architect', true);
      switch ($column) {
        case '_blueprints_short-name':
          echo $post_meta[ $column ];
          break;
        case '_blueprints_content-source':
          echo ucwords(empty($post_meta[ $column ]) ? 'default' : $post_meta[ $column ]);
          break;
        case 'navigation':
          switch (true) {
            case empty($post_meta[ '_blueprints_navigation' ]):
            case $post_meta[ '_blueprints_navigation' ] === 'none':
              echo 'None';
              break;
            case $post_meta[ '_blueprints_navigation' ] === 'pagination':
              echo 'Pagination : ' . ucwords(empty($post_meta[ '_blueprints_pager' ]) ? 'Prev/Next' : $post_meta[ '_blueprints_pager' ]);
              break;
            case $post_meta[ '_blueprints_navigation' ] === 'navigator':
              echo "Navigator : " . ucwords(empty($post_meta[ '_blueprints_navigator' ]) ? 'Tabbed' : $post_meta[ '_blueprints_navigator' ]);
              break;
          }
          break;
        case 'panels':
          global $pzarc_panels_array;
          echo $pzarc_panels_array[ $post_meta[ '_blueprints_section-0-panel-layout' ] ];
          if (!empty($post_meta[ '_blueprints_section-1-panel-layout' ])) {
            echo '<br>' . $pzarc_panels_array[ $post_meta[ '_blueprints_section-1-panel-layout' ] ];
          }
          if (!empty($post_meta[ '_blueprints_section-2-panel-layout' ])) {
            echo '<br>' . $pzarc_panels_array[ $post_meta[ '_blueprints_section-2-panel-layout' ] ];
          }
      }
    }

    /**
     * [create_blueprints_post_type description]
     * @return [type] [description]
     */
    public function create_blueprints_post_type()
    {
      $labels = array(
          'name'               => _x('Blueprints', 'post type general name'),
          'singular_name'      => _x('Blueprint', 'post type singular name'),
          'add_new'            => __('Add New Blueprint'),
          'add_new_item'       => __('Add New Blueprint'),
          'edit_item'          => __('Edit Blueprint'),
          'new_item'           => __('New Blueprint'),
          'view_item'          => __('View Blueprint'),
          'search_items'       => __('Search Blueprints'),
          'not_found'          => __('No Blueprints found'),
          'not_found_in_trash' => __('No Blueprints found in Trash'),
          'parent_item_colon'  => '',
          'menu_name'          => _x('<span class="dashicons dashicons-welcome-widgets-menus"></span>Blueprints', 'pzarc-blueprint-designer'),
      );

      $args = array(
          'labels'              => $labels,
          'description'         => __('Architect Blueprints are used to create reusable layout Blueprints for use in your Architect blocks, widgets, shortcodes and WP template tags. These are made up of panels, sections, criteria and navigation'),
          'public'              => false,
          'publicly_queryable'  => false,
          'show_ui'             => true,
          'show_in_menu'        => 'pzarc',
          'show_in_nav_menus'   => false,
          'query_var'           => true,
          'rewrite'             => true,
          'capability_type'     => 'post',
          'has_archive'         => false,
          'hierarchical'        => false,
          'menu_position'       => 30,
          'supports'            => array('title', 'revisions'),
          'exclude_from_search' => true,
      );

      register_post_type('arc-blueprints', $args);
    }

  } // EOC


////add_filter('cmb_meta_boxes', 'pzarc_blueprint_wizard_metabox');
//function pzarc_blueprint_wizard_metabox($meta_boxes = array())
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
//  $meta_boxes[ ] = array(
//          'title'    => 'What do you want to do?',
//          'pages'    => 'arc-blueprints',
//          'context'  => 'normal',
//          'priority' => 'high',
//          'fields'   => $fields // An array of fields.
//  );
//
//  return $meta_boxes;
//}

  add_action("redux/metaboxes/{$redux_opt_name}/boxes", 'pzarc_blueprint_tabs');
  function pzarc_blueprint_tabs($metaboxes)
  {
    $prefix       = '_blueprint_tabs_';
    $sections     = array();
    $sections[ ]  = array(
      //          'title'      => __('General Settings', 'pzarchitect'),
      'show_title' => true,
      'icon_class' => 'icon-large',
      'icon'       => 'el-icon-home',
      'fields'     => array(
          array(
              'id'      => $prefix . 'tabs',
              'type'    => 'tabbed',
              'options' => array(
                  'layout'  => '<span><span class="icon-large el-icon-website"></span> Layout</span>',
                  'content' => '<span><span class="icon-large el-icon-align-left"></span> Content</span>',
                  'styling' => '<span><span class="icon-large el-icon-brush"></span> Styling</span>'
              ),
              'targets' => array('layout'  => array('layout-settings', '_blueprints_layout-general-settings'),
                                 'content' => array('content-selections', '_blueprints_content-general-settings'),
                                 'styling' => array('blueprint-stylings', '_blueprints_styling-general-settings')
              )
          ),
      )
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
//  function pzarc_blueprint_general($meta_boxes = array())
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
//    $meta_boxes[ ] = array(
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
//    return $meta_boxes;
//
//  }
  add_action("redux/metaboxes/{$redux_opt_name}/boxes", 'pzarc_blueprint_layout_general');
  function pzarc_blueprint_layout_general($meta_boxes = array())
  {
    $prefix = '_blueprints_';

    $sections[ ]   = array(
        'fields' => array(
            array(
                'id'       => $prefix . 'short-name',
                'title'    => __('Blueprint Short Name', 'pzarchitect') . '<span class="pzarc-required el-icon-star" title="Required"></span>',
                'type'     => 'text',
                'width'    => 'auto',
                'subtitle' => __('Alphanumeric only. ', 'pzarchitect') . __('Use the shortcode <strong class="pzarc-usage-info">[pzarc "<span class="pzarc-shortname"></span>"]</strong> or the template tag <strong class="pzarc-usage-info">pzarc(\'<span class="pzarc-shortname"></span>\');</strong>', 'pzarchitect'),
                'validate' => 'not_empty'
            ),
            array(
                'id'      => $prefix . 'blueprint-width',
                'type'    => 'dimensions',
                //               'mode'    => array('width' => true, 'height' => false),
                'units'   => array('%', 'px'),
                'width'   => true,
                'height'  => false,
                'title'   => __('Blueprint width', 'pzarchitect'),
                'default' => array('width' => '100', 'units' => '%'),
            ),
            array(
                'id'      => $prefix . 'sections-width',
                'type'    => 'dimensions',
                //               'mode'    => array('width' => true, 'height' => false),
                'units'   => array('%', 'px'),
                'width'   => true,
                'height'  => false,
                'title'   => __('Sections width', 'pzarchitect'),
                'default' => array('width' => '100', 'units' => '%'),
            ),
            array(
                'id'      => $prefix . 'sections-align',
                'type'    => 'button_set',
                'select2' => array('allowClear' => false),
                'options' => array('left'   => __('Left', 'pzarchitect'),
                                   'center' => __('Centre', 'pzarchitect'),
                                   'right'  => __('Right', 'pzarchitect')),
                'title'   => __('Sections align', 'pzarchitect'),
                'default' => 'center',
            ),
            array(
                'title'    => 'Navigation',
                'id'       => $prefix . 'navigation',
                'type'     => 'button_set',
                'default'  => 'none',
                'subtitle' => __('Note: Navigator will only function when one section selected. Pagination effects all sections.', 'pzarchitect'),
                'options'  => array(
                    'none'       => 'None',
                    'pagination' => 'Pagination',
                    'navigator'  => 'Navigator'
                )
            ),
            array(
                'title'    => 'Section 2',
                'id'       => $prefix . 'section-1-enable', //this number is the increment number
                'type'     => 'switch',
                'on'       => 'Yes',
                'off'      => 'No',
                'width'    => 'auto',
                'required' => array($prefix . 'navigation', 'not', 'navigator'),
                'default'  => false
            ),
            array(
                'title'    => 'Section 3',
                'id'       => $prefix . 'section-2-enable',
                'type'     => 'switch',
                'on'       => 'Yes',
                'off'      => 'No',
                'width'    => 'auto',
                'required' => array($prefix . 'navigation', 'not', 'navigator'),
                'default'  => false
            ),

        )
    );
    $meta_boxes[ ] = array(
        'id'         => $prefix . 'layout-general-settings',
        'title'      => 'General Settings',
        'post_types' => array('arc-blueprints'),
        'sections'   => $sections,
        'position'   => 'side',
        'priority'   => 'high',
        'sidebar'    => false

    );


    return $meta_boxes;

  }

  add_action("redux/metaboxes/{$redux_opt_name}/boxes", 'pzarc_blueprint_content_general');
  function pzarc_blueprint_content_general($meta_boxes = array())
  {
    $prefix = '_blueprints_';

    $sections[ ] = array(
        'fields' => array(
            array(
                'title'    => __('Content source', 'pzarchitect'),
                'id'       => $prefix . 'content-source',
                'type'     => 'select',
                'select2'  => array('allowClear' => false),
                'default'  => 'defaults',
                'options'  => array(
                  // need to make adjustments when adding new ones so help still displays
                  'defaults' => 'Defaults',
                  'post'     => 'Posts',
                  'page'     => 'Pages',
                  'snippets' => 'Snippets',
                  'gallery'  => 'Galleries',
                  'slides'   => 'Slides',
                  //                          'images'      => 'Specific Images',
                  //                          'wpgallery'   => 'WP Gallery from post',
                  //                          'galleryplus' => 'GalleryPlus',
                  //                          'nggallery'   => 'NextGen',
                  //                        'widgets' => 'Widgets',
                  //                          'custom-code' => 'Custom code',
                  //                        'rss'     => 'RSS Feed',
                  'cpt'      => 'Custom Post Types'
                ),
                'subtitle' => 'todo: code all the js to show hide relevant sections'
            ),
            array(
                'title'   => __('Order by', 'pzarchitect'),
                'id'      => $prefix . 'orderby',
                'type'    => 'button_set',
                'default' => 'date',
                'cols'    => 6,
                'options' => array(
                    'date'  => 'Date',
                    'title' => 'Title',
                ),
            ),
            array(
                'title'   => __('Order direction', 'pzarchitect'),
                'id'      => $prefix . 'orderdir',
                'type'    => 'button_set',
                'default' => 'desc',
                'cols'    => 6,
                'options' => array(
                    'ASC'  => 'Ascending',
                    'DESC' => 'Descending',
                ),
            ),
            array(
                'title'   => __('Skip N posts', 'pzarchitect'),
                'id'      => $prefix . 'skip',
                'type'    => 'spinner',
                'min'     => 0,
                'max'     => 9999,
                'step'    => 1,
                'default' => 0,
                'desc'    => __('Note: Skipping breaks pagination. This is a known WordPress bug.', 'pzarchitect'),
            ),
            array(
                'title'   => __('Sticky posts first', 'pzarchitect'),
                'id'      => $prefix . 'sticky',
                'type'    => 'switch',
                'on'      => 'Yes',
                'off'     => 'No',
                'default' => false,
            ),
        )
    );

    $meta_boxes[ ] = array(
        'id'         => $prefix . 'content-general-settings',
        'title'      => 'Content Settings',
        'post_types' => array('arc-blueprints'),
        'sections'   => $sections,
        'position'   => 'side',
        'priority'   => 'high',
        'sidebar'    => false

    );


    return $meta_boxes;

  }


  add_action("redux/metaboxes/{$redux_opt_name}/boxes", 'pzarc_blueprint_layout');
  function pzarc_blueprint_layout($meta_boxes = array())
  {
    $prefix   = '_blueprints_';
    $sections = array();
    global $_architect_options;
    global $pzarc_panels_array;
    if (empty($pzarc_panels_array)) {
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
          $pzarc_panels_array[ $pzarc_cell->ID ] = (empty($pzarc_cell->post_title) ? 'No title' : $pzarc_cell->post_title);
        }
      } else {
        $pzarc_panels_array = array(0 => 'No cell layouts. Create some.');
      }
    }

    // ID,post_title
    $icons = array(0 => 'el-icon-align-left', 1 => 'el-icon-th', 2 => 'el-icon-th-list');
    for ($i = 0; $i < 3; $i++) {
      $sections[ ] = array(
          'title'      => __('Section ' . ($i + 1), 'pzarchitect'),
          'show_title' => true,
          'icon_class' => 'icon-large',
          'icon'       => $icons[ $i ],
          'fields'     => array(

              array(
                  'id'    => $prefix . 'section-' . $i . '-title',
                  'title' => __('Section ' . ($i + 1) . ' title (optional)', 'pzarchitect'),
                  'type'  => 'text',
                  'cols'  => 12,
              ),
              array(
                  'id'      => $prefix . 'section-width',
                  'type'    => 'dimensions',
                  //               'mode'    => array('width' => true, 'height' => false),
                  'units'   => array('%', 'px'),
                  'width'   => true,
                  'height'  => false,
                  'title'   => __('Section width', 'pzarchitect'),
                  'default' => array('width' => '100', 'units' => '%'),
              ),
              array(
                  'id'       => $prefix . 'section-' . $i . '-panel-layout',
                  'title'    => __('Panels layout', 'pzarchitect'),
                  'type'     => 'select',
                  'validate' => ($i == 0 ? 'not_empty' : null),
                  'select2'  => array('allowClear' => false),
                  'options'  => $pzarc_panels_array
              ),
              array(
                  'title'   => __('Layout mode', 'pzarchitect'),
                  'id'      => $prefix . 'section-' . $i . '-layout-mode',
                  'type'    => 'select',
                  'default' => 'basic',
                  'select2' => array('allowClear' => false),
                  'cols'    => 6,
                  'options' => array(
                      'basic'   => 'Basic (CSS only)',
                      //          'fitRows'         => 'Fit rows',
                      //          'fitColumns'      => 'Fit columns',
                      'masonry' => 'Masonry (Pinterest-like)',
                      //          'masonryVertical' => 'Masonry Vertical',
                      //          'panelsByRow'      => 'Panels by row',
                      //          'panelsByColumn'   => 'Panels by column',
                      //          'vertical'    => 'Vertical',
                      //          'horizontal'  => 'Horizontal',
                  ),
                  //       'subtitle'    => __('Choose how you want the panels to display. With evenly sized panels, you\'ll see little difference. Please visit <a href="http://isotope.metafizzy.co/demos/layout-modes.html" target=_blank>Isotope Layout Modes</a> for demonstrations of these layouts', 'pzarchitect')
              ),
              array(
                  'title'    => __('Limit panels', 'pzarchitect'),
                  'id'       => $prefix . 'section-' . $i . '-panels-limited',
                  'type'     => 'switch',
                  'on'       => 'Yes',
                  'off'      => 'No',
                  'default'  => true,
                  'subtitle' => 'Each panel displays content from the selected content type. Use unlimited with care.'
              ),
              array(
                  'title'    => __('Panels to show', 'pzarchitect'),
                  'id'       => $prefix . 'section-' . $i . '-panels-per-view',
                  'type'     => 'spinner',
                  'default'  => 1,
                  'min'      => 1,
                  'max'      => 99,
                  'subtitle' => __('If using pagination, this will be the number per page.'),
                  'required' => array($prefix . 'section-' . $i . '-panels-limited', '=', true)
              ),
              array(
                  'title'         => __('Columns wide screen', 'pzarchitect') . ' (' . $_architect_options[ 'architect_breakpoint_1' ][ 'width' ] . ')',
                  'id'            => $prefix . 'section-' . $i . '-columns-breakpoint-1',
                  'hint'          => array('content' => __('Number of columns or panels across on a wide screen as set in the breakpoints options', 'pzarchitect')),
                  'type'          => 'slider',
                  'default'       => 3,
                  'min'           => 1,
                  'max'           => 10,
                  'display_value' => 'label'
              ),
              array(
                  'title'         => __('Columns medium screen', 'pzarchitect') . ' (' . $_architect_options[ 'architect_breakpoint_2' ][ 'width' ] . ')',
                  'id'            => $prefix . 'section-' . $i . '-columns-breakpoint-2',
                  'hint'          => array('content' => __('Number of columns or panels across on a medium screen as set in the breakpoints options', 'pzarchitect')),
                  'type'          => 'slider',
                  'default'       => 2,
                  'min'           => 1,
                  'max'           => 10,
                  'display_value' => 'label'
              ),
              array(
                  'title'         => __('Columns narrow screen', 'pzarchitect') . ' (' . $_architect_options[ 'architect_breakpoint_3' ][ 'width' ] . ')',
                  'id'            => $prefix . 'section-' . $i . '-columns-breakpoint-3',
                  'hint'          => array('content' => __('Number of columns or panels across on a narrow screen as set in the breakpoints options', 'pzarchitect')),
                  'type'          => 'slider',
                  'default'       => 1,
                  'min'           => 1,
                  'max'           => 10,
                  'display_value' => 'label'
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
                  'title'   => __('Panels vertical margin', 'pzarchitect'),
                  'id'      => $prefix . 'section-' . $i . '-panels-vert-margin',
                  'type'    => 'dimensions',
                  'height'  => false,
                  'units'   => false,
                  'default' => array('width' => '1'),
                  //    'hint'  => array('content' => __('Set the vertical gutter width as a percentage of the section width. The gutter is the gap between adjoining elements', 'pzarchitect'))
              ),
              array(
                  'title'   => __('Panels horizontal margin', 'pzarchitect'),
                  'id'      => $prefix . 'section-' . $i . '-panels-horiz-margin',
                  'type'    => 'dimensions',
                  'width'   => false,
                  'units'   => false,
                  'default' => array('height' => '1'),
                  //      'hint'  => array('content' => __('Set the horizontal gutter width as a percentage of the section width. The gutter is the gap between adjoining elements', 'pzarchitect'))
              ),

          )

      );
    }
    $sections[ ] = array(
        'title'      => __('Pagination', 'pzarchitect'),
        'show_title' => true,
        'icon_class' => 'icon-large',
        'icon'       => 'el-icon-chevron-right',
        'required'   => array('blueprints_navigation', 'equals', 'pagination'),
        'fields'     => array(

            array(
                'title'    => __('Warning', 'pzarchitect'),
                'id'       => $prefix . 'pager-info',
                'type'     => 'info',
                'subtitle' => __('You should never display more than one pagination element on a page as pagination reloads the page and will therefore affect all paginated content.', 'pzarchitect')
            ),
            array(
                'id'      => $prefix . 'pager',
                'title'   => __('Pagination', 'pzarchitect'),
                'type'    => 'button_set',
                'default' => 'prevnext',
                'options' => array(
//                    'none'     => 'None',
'prevnext' => 'Previous/Next',
'names'    => 'Post names',
'pagenavi' => 'PageNavi',
                )
            ),
            array(
                'id'      => $prefix . 'pager-location',
                'title'   => __('Pagination location', 'pzarchitect'),
                'type'    => 'button_set',
                'default' => 'bottom',
                'options' => array(
                    'bottom' => 'Bottom',
                    'top'    => 'Top',
                    'both'   => 'Both'
                )
            ),
            array(
                'id'      => $prefix . 'posts-per-page',
                'title'   => __('Posts per page', 'pzarchitect'),
                'type'    => 'text',
                'default' => 'Do we really need this? Can\'t we just use the total panels per section?',
            ),
        )
    );
    $sections[ ] = array(
        'title'      => __('Navigator', 'pzarchitect'),
        'show_title' => true,
        'icon_class' => 'icon-large',
        'icon'       => 'el-icon-play-circle',
        'required'   => array('blueprints_navigation', 'equals', 'navigator'),
        'desc'       => 'When the navigation type is set to navigator, presentation will always be in a slider form. You can have multiple navigators on a page, thus multiple sliders.',
        'fields'     => array(
          //            array(
          //                'title'   => 'Slider engine',
          //                'id'      => $prefix . 'navigator-slider-engine',
          //                'type'    => 'button_set',
          //                'default' => 'swiper',
          //                'options' => array(
          //                    'swiper'   => 'Swiper',
          //                    'bxslider' => 'bxSlider'
          //                )
          //            ),
          array(
              'id'      => $prefix . 'navigator',
              'title'   => __('Type', 'pzarchitect'),
              'type'    => 'image_select',
              'default' => 'tabbed',
              'hint'    => array('content' => __('Tabbed, accordion, buttons, bullets, numbers, thumbnails', 'pzarchitect')),
              'height'  => 75,
              'options' => array(
                  'tabbed'  => array(
                      'alt' => 'Tabbed',
                      'img' => PZARC_PLUGIN_APP_URL . 'shared/assets/images/metaboxes/nav-type-tabbed.png'
                  ),
                  // TODO: Add these beta2
                  //                    'accordion' => array(
                  //                        'alt' => 'Accordion',
                  //                        'img' => PZARC_PLUGIN_APP_URL . 'shared/assets/images/metaboxes/nav-type-accordion.png'
                  //                    ),
                  //                    'buttons'   => array(
                  //                        'alt' => 'Buttons',
                  //                        'img' => PZARC_PLUGIN_APP_URL . 'shared/assets/images/metaboxes/nav-type-buttons.png'
                  //                    ),
                  'bullets' => array(
                      'alt' => 'Bullets',
                      'img' => PZARC_PLUGIN_APP_URL . 'shared/assets/images/metaboxes/nav-type-bullets.png'
                  ),
                  'numbers' => array(
                      'alt' => 'Numbers',
                      'img' => PZARC_PLUGIN_APP_URL . 'shared/assets/images/metaboxes/nav-type-numeric.png'
                  ),
                  'thumbs'  => array(
                      'alt' => 'Thumbs',
                      'img' => PZARC_PLUGIN_APP_URL . 'shared/assets/images/metaboxes/nav-type-thumbs.png'
                  ),
              )
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
                  // TODO: Add these beta2
                  //                    'left'   => array(
                  //                        'alt' => 'Left',
                  //                        'img' => PZARC_PLUGIN_APP_URL . 'shared/assets/images/metaboxes/nav-pos-left.png'
                  //                    ),
                  //                    'right'  => array(
                  //                        'alt' => 'Right',
                  //                        'img' => PZARC_PLUGIN_APP_URL . 'shared/assets/images/metaboxes/nav-pos-right.png'
                  //                    ),
              ),
              'required' => array(
                  array($prefix . 'navigator', '!=', 'accordion'),
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
                  array($prefix . 'navigator', '!=', 'tabbed'),
                  array($prefix . 'navigator', '!=', 'thumbs'),
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
                  array($prefix . 'navigator-position', '!=', 'left'),
                  array($prefix . 'navigator-position', '!=', 'right')
              )
          ),
          // TODO: Add these beta2
          //            array(
          //                'title'    => 'Vertical width',
          //                'id'       => $prefix . 'navigator-vertical-width',
          //                'type'     => 'dimensions',
          //                'default'  => array('width' => '10%'),
          //                'height'   => false,
          //                'units'    => '%',
          //                'required' => array(
          //                    array($prefix . 'navigator', '!=', 'accordion'),
          //                    array($prefix . 'navigator-position', '!=', 'top'),
          //                    array($prefix . 'navigator-position', '!=', 'bottom')
          //                )
          //            ),
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
              'title'   => 'Sizing',
              'id'      => $prefix . 'navigator-sizing',
              'type'    => 'button_set',
              'default' => 'small',
              'options' => array(
                  'small'  => 'Small',
                  'medium' => 'Medium',
                  'large'  => 'Large'
              ),
          ),
          array(
              'title'    => 'Pager',
              'id'       => $prefix . 'navigator-pager',
              'type'     => 'button_set',
              'cols'     => 6,
              'default'  => 'none',
              'options'  => array(
                  'none'   => 'None',
                  'hover'  => 'Hover over panels',
                  'inline' => 'Inline with navigator',
                  'both'   => 'Both'
              ),
              'required' => array(
                  array($prefix . 'navigator', '!=', 'accordion'),
              )
          ),
          // TODO: Notsure this is necessary
          //            array(
          //                'id'       => $prefix . 'navigator-items-visible',
          //                'title'    => __('Navigator items visible', 'pzarchitect'),
          //                'type'     => 'spinner',
          //                'default'  => 5,
          //                'subtitle' => 'If zero, it will use the "Panels to show" value. This is the number of items visible in the navigator bar. NOTE: This is also the number of items skipped by multi-skip pager element of the inline pager.'
          //            ),
          array(
              'title' => __('Transitions', 'pzarchitect'),
              'id'    => $prefix . 'section-transitions-heading',
              'type'  => 'section',
              'class' => ' heading',
          ),
          //TODO: Get the fader working!
          //            array(
          //                'title'   => 'Type',
          //                'id'      => $prefix . 'transitions-type',
          //                'type'    => 'select',
          //                'default' => 'none',
          //                'select2' => array('allowClear' => false),
          //                'options' => array(
          //                    'none'  => 'None',
          //                    'fade'  => 'Fade',
          //                    'slide' => 'Slide',
          //                )
          //            ),
          array(
              'title'         => 'Duration (seconds)',
              'id'            => $prefix . 'transitions-duration',
              'type'          => 'slider',
              'min'           => 0.5,
              'max'           => 5,
              'resolution'    => 0.1,
              'step'          => 0.5,
              'hint'          => array('content' => __('Time taken for the transition to display', 'pzarchitect')),
              'default'       => 2,
              'display_value' => 'label'
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
          ),
          //            array(
          //                'title'   => 'Auto start',
          //                'id'      => $prefix . 'transitions-autostart',
          //                'type'    => 'switch',
          //                'default' => false,
          //            ),
          //            array(
          //                'title'   => 'Pause on hover',
          //                'id'      => $prefix . 'transitions-pause-on-hover',
          //                'type'    => 'switch',
          //                'default' => true,
          //            ),
        )
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

    $sections[ ] = array(
        'title'      => 'Using blueprints',
        'icon_class' => 'icon-large',
        'icon'       => 'el-icon-info-sign',
        'fields'     => array(

            array(
                'title' => __('How to use blueprints', 'pzarchitect'),
                'id'    => $prefix . 'help-blueprints',
                'type'  => 'info',
                'class' => 'plain',
                'desc'  => '<p>There are five methods to displaying a blueprint. These are, in order of necessary technical know-how:</p>
                <ul>
                <li><strong>Widget</strong> : Select on widgets screen</li>
                <li><strong>Shortcode</strong> : Add <em>[pzarc "blueprint" "overrides"]</em> your content at the point you want the blueprint to appear.</li>
                <li><strong>Actions editor</strong> Enter the action name, blueprint and pages to appear on</li>
                <li><strong>Action call</strong> :Add </em>new showBlueprint(\'action\',\'blueprint\',\'pageids\');</em> to your functions.php</li>
                <li><strong>Template tag</strong> : Add <em>pzarchitect(\'blueprint\');</em> to your template at the point you want the blueprint to appear</li>
                </ul>
                <p><em>blueprint</em> = the shortname of the blueprint to display.</p>
                <p><em>overrides</em> = a comma separated list of the media ids to display instead. Very useful for easily converting a WordPress gallery shortcode. eg [gallery ids="1,2,3,5"] becomes [pzarc "mygallery", "1,2,3,4,5"]</p>
                <p><em>action</em> = the name of the action hook where you want the blueprint to appear</p>
                 <p><em>pageids</em> = a comma separated list of names or numeric ids of the pages to display the blueprint. </p>

            ')
        )
    );
    $sections[ ] = array(
        'title'      => 'Help',
        'icon_class' => 'icon-large',
        'icon'       => 'el-icon-question-sign',
        'fields'     => array(

            array(
                'title' => __('Layout', 'pzarchitect'),
                'id'    => $prefix . 'help-layout',
                'type'  => 'info',
                'class' => 'plain',
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
                              </p>

            ')
        )
    );

    $meta_boxes[ ] = array(
        'id'         => 'layout-settings',
        'title'      => 'Layout Settings',
        'post_types' => array('arc-blueprints'),
        'sections'   => $sections,
        'position'   => 'normal',
        'priority'   => 'high',
        'sidebar'    => false

    );

    return $meta_boxes;
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

  add_action("redux/metaboxes/{$redux_opt_name}/boxes", 'pzarc_contents_metabox');
  function pzarc_contents_metabox($meta_boxes = array())
  {
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
    $prefix      = '_content_defaults_';
    $sections[ ] = array(
        'title'      => 'Defaults',
        'icon_class' => 'icon-large',
        'icon'       => 'el-icon-check',
        'fields'     => array(
            array(
                'title'    => __('Defaults', 'pzarchitect'),
                'id'       => $prefix . 'defaults-heading',
                'type'     => 'info',
                'style'    => 'success',
                'subtitle' => 'When Default is selected, Architect will use whatever the default content for the page. e.g. the home page, category archives, search results etc'
            )
        )
    );
    $prefix      = '_content_posts_';
    $sections[ ] = array(
        'title'      => 'Posts',
        'icon_class' => 'icon-large',
        'icon'       => 'el-icon-list',
        'fields'     => array(
            array(
                'title' => __('Posts', 'pzarchitect'),
                'id'    => $prefix . 'section-start-posts',
                'type'  => 'section',
                'class' => ' heading',
            ),
            array(
                'title'  => __('Categories', 'pzarchitect'),
                'id'     => $prefix . 'categories-heading-start',
                'type'   => 'section',
                'class'  => ' heading',
                'indent' => true
            ),
            array(
                'title'   => __('Include categories', 'pzarchitect'),
                'id'      => $prefix . 'inc-cats',
                'type'    => 'select',
                'select2' => array('allowClear' => true),
                'default' => '',
                'wpqv'    => 'category__in',
                'data'    => 'category',
                'multi'   => true
            ),
            array(
                'title'   => __('In ANY or ALL categories', 'pzarchitect'),
                'id'      => $prefix . 'all-cats',
                'type'    => 'button_set',
                'options' => array('any' => 'Any', 'all' => 'All'),
                'wpqv'    => 'category__and',
                'default' => 'any',
            ),
            array(
                'title'   => __('Exclude categories', 'pzarchitect'),
                'id'      => $prefix . 'exc-cats',
                'type'    => 'select',
                'default' => '',
                'select2' => array('allowClear' => true),
                'wpqv'    => 'category__not_in',
                'data'    => 'category',
                'multi'   => true
            ),
            array(
                'title'   => __('Include sub-categories on archives', 'pzarchitect'),
                'id'      => $prefix . 'sub-cat-archives',
                'type'    => 'switch',
                'on'      => 'Yes',
                'off'     => 'No',
                'default' => true,
            ),
            array(
                'id'     => $prefix . 'categories-section-end',
                'type'   => 'section',
                'indent' => false
            ),
            array(
                'title'  => __('Tags', 'pzarchitect'),
                'id'     => $prefix . 'tags-section-end',
                'type'   => 'section',
                'class'  => ' heading',
                'indent' => true
            ),
            array(
                'title'   => __('Tags', 'pzarchitect'),
                'id'      => $prefix . 'inc-tags',
                'type'    => 'select',
                'select2' => array('allowClear' => true),
                'default' => '',
                'cols'    => 4,
                'data'    => 'tags',
                'multi'   => true
            ),
            array(
                'title'   => __('Exclude tags', 'pzarchitect'),
                'id'      => $prefix . 'exc-tags',
                'type'    => 'select',
                'default' => '',
                'select2' => array('allowClear' => true),
                'cols'    => 4,
                'data'    => 'tags',
                'multi'   => true
            ),
            array(
                'id'     => $prefix . 'tags-section-end',
                'type'   => 'section',
                'indent' => false
            ),
            array(
                'title'  => __('Custom taxonomies', 'pzarchitect'),
                'id'     => $prefix . 'custom-taxonomies-section-start',
                'type'   => 'section',
                'class'  => ' heading',
                'indent' => true
            ),
            // TODO: Add a loop to display custom taxonomies
            // foreach($taxonomies as $taxonomy ){}
            array(
                'title' => __('Other taxonomies', 'pzarchitect'),
                'id'    => $prefix . 'other-tax',
                'type'  => 'select',
                'data'  => 'taxonomies',
                'args'  => array('_builtin' => false)
            ),
            array(
                'title'    => __('Other taxonomy tags', 'pzarchitect'),
                'id'       => $prefix . 'other-tax-tags',
                'type'     => 'text',
                'subtitle' => 'Enter a comma separated list of tags to filter by in the chosen custom taxonomy'
            ),
            array(
                'title'   => __('Taxonomies operator', 'pzarchitect'),
                'id'      => $prefix . 'tax-op',
                'type'    => 'button_set',
                'options' => array('all' => 'All', 'any' => 'Any'),
                'default' => 'any',
                'hint'    => array('content' => __('Choose whether posts contain all or any of the taxonomies', 'pzarchitect')),
            ),
            //    array(
            //      'title' => __('Days to show', 'pzarchitect'),
            //      'id' => $prefix . 'days',
            //      'type' => 'text',
            //      'cols'=>6,
            //              //      'default' => 'All',
            //    ),
            //    array(
            //      'title' => __('Days to show until', 'pzarchitect'),
            //      'id' => $prefix . 'days-until',
            //      'type' => 'text',
            //      'cols'=>6,
            //
            //    ),
            array(
                'id'     => $prefix . 'custom-taxonomies-section-end',
                'type'   => 'section',
                'indent' => false
            ),
            array(
                'title'  => __('Others', 'pzarchitect'),
                'id'     => $prefix . 'other-section-start',
                'type'   => 'section',
                'class'  => ' heading',
                'indent' => true
            ),
            array(
                'title'   => __('Authors', 'pzarchitect'),
                'id'      => $prefix . 'authors',
                'type'    => 'select',
                'data'    => 'callback',
                'args'    => array('pzarc_get_authors'),
                'default' => 'all',
            ),
            array(
                'id'     => $prefix . 'other-section-end',
                'type'   => 'section',
                'indent' => false
            ),
        )
    );
    //Pages
    $prefix      = '_content_pages_';
    $sections[ ] = array(
        'title'      => 'Pages',
        'icon_class' => 'icon-large',
        'icon'       => 'el-icon-align-justify',
        'fields'     => array(
            array(
                'title' => __('Pages', 'pzarchitect'),
                'id'    => $prefix . 'pages-heading',
                'type'  => 'section',
                'class' => ' heading',
            )
        )
    );
    //Snippets
    $prefix      = '_content_snippets_';
    $sections[ ] = array(
        'title'      => 'Snippets',
        'icon_class' => 'icon-large',
        'icon'       => 'el-icon-align-justify',
        'fields'     => array(
            array(
                'title' => __('Snippets', 'pzarchitect'),
                'id'    => $prefix . 'snippets-heading',
                'type'  => 'section',
                'class' => ' heading',
            )
        )
    );

    // Gallery
    $prefix      = '_content_galleries_';
    $sections[ ] = array(
        'title'      => 'Galleries',
        'icon_class' => 'icon-large',
        'icon'       => 'el-icon-picture',
        'fields'     => array(
            array(
                'title'   => __('Gallery source', 'pzarchitect'),
                'id'      => $prefix . 'gallery-source',
                'type'    => 'button_set',
                'default' => 'images',
                'hint'    => array('content' => __('Can be overriden by shortcode e.g. [pzarc blueprint="mytemplate" ids="1,2,3,4,5"]', 'pzarchitect')),
                'options' => array(
                    'images'      => 'Image Gallery',
                    'ids'         => 'Specific IDs',
                    'wpgallery'   => 'WP Galleries',
                    'postimages'  => 'Post images',
                    'galleryplus' => 'GalleryPlus',
                    'nggallery'   => 'NextGen',
                )
            ),
            array(
                'title'    => __('Image Gallery', 'pzarchitect'),
                'id'       => $prefix . 'specific-images',
                'type'     => 'gallery',
                'required' => array($prefix . 'gallery-source', 'equals', 'images'),
            ),
            array(
                'title'    => __('Specific IDs', 'pzarchitect'),
                'id'       => $prefix . 'specific-ids',
                'type'     => 'text',
                'subtitle' => 'Enter a comma separated list of image ids',
                'required' => array($prefix . 'gallery-source', 'equals', 'ids')
            ),
            array(
                'title'    => __('WP Gallery', 'pzarchitect'),
                'id'       => $prefix . 'wp-post-gallery',
                'type'     => 'select',
                'data'     => 'callback',
                'args'     => array('pzarc_get_wp_galleries'),
                'subtitle' => 'Select a post with a gallery',
                'required' => array($prefix . 'gallery-source', 'equals', 'wpgallery')
            ),
            array(
                'title'    => __('Post iamges', 'pzarchitect'),
                'id'       => $prefix . 'wp-post-images',
                'type'     => 'select',
                'data'     => 'callback',
                'args'     => array('pzarc_get_wp_galleries'),
                'subtitle' => 'Select a post with images',
                'required' => array($prefix . 'gallery-source', 'equals', 'wpgallery')
            ),
            array(
                'title'    => __('GalleryPlus', 'pzarchitect'),
                'id'       => $prefix . 'galleryplus',
                'type'     => 'select',
                'data'     => 'callback',
                'args'     => array('pzarc_get_gp_galleries'),
                'subtitle' => 'Select a GalleryPlus gallery',
                'required' => array($prefix . 'gallery-source', 'equals', 'galleryplus')
            ),
            array(
                'title'    => __('NextGen Gallery', 'pzarchitect'),
                'id'       => $prefix . 'nggallery',
                'type'     => 'select',
                'data'     => 'callback',
                'args'     => array('pzarc_get_ng_galleries'),
                'subtitle' => (class_exists('P_Photocrati_NextGen') ? 'Enter NGG gallery name to use'
                        : 'NextGen is not running on this site'),
                'required' => array($prefix . 'gallery-source', 'equals', 'nggallery')
            ),
        )
    );

    // Slides
    $prefix      = '_content_slides_';
    $sections[ ] = array(
        'title'      => 'Slides',
        'icon_class' => 'icon-large',
        'icon'       => 'el-icon-video',
        'fields'     => array(
            array(
                'title' => __('Slides', 'pzarchitect'),
                'id'    => $prefix . 'slides-heading',
                'type'  => 'section',
                'class' => ' heading',
            )
        )
    );
    //Custom post types
    $sections[ ] = array(
        'title'      => 'Custom Post Types',
        'icon_class' => 'icon-large',
        'icon'       => 'el-icon-wrench',
        'fields'     => array(
            array(
                'title' => __('Custom Post Types', 'pzarchitect'),
                'id'    => $prefix . 'cpt-heading',
                'type'  => 'section',
                'class' => ' heading',
            )
        )
    );
    $prefix      = '_content_help_';
    $sections[ ] = array(
        'title'      => 'Help',
        'icon_class' => 'icon-large',
        'icon'       => 'el-icon-question-sign',
        'fields'     => array(

            array(
                'title' => __('Snippets', 'pzarchitect'),
                'id'    => $prefix . 'help-content-selection',
                'type'  => 'info',
                'class' => 'plain',
                'desc'  => '
<p>With Architect you can enable an extra content type called Snippets.
  These give you a third method of creating content that doesn\'t fit into the post or page types.
It came about with my own need to create grids of product features. I didn\'t want to fill up pages or posts, so created Snippets for these small content bites.
You can use them however you like though.</p>
<p>
                              </p>
                '

            )
        )
    );

    $meta_boxes[ ] = array(
        'id'         => 'content-selections',
        'title'      => 'Content selections',
        'post_types' => array('arc-blueprints'),
        'sections'   => $sections,
        'position'   => 'normal',
        'priority'   => 'high',
        'sidebar'    => false

    );


    return $meta_boxes;

  }

  add_action("redux/metaboxes/{$redux_opt_name}/boxes", 'pzarc_blueprint_layout_styling');
  function pzarc_blueprint_layout_styling($metaboxes)
  {

//  $screen = get_current_screen();
//  if ($screen->ID != 'xx') {return;}
    $defaults = get_option('_architect');

    $prefix = '_blueprints_styling_';

    $sections  = array();
    $optprefix = 'architect_config_';

    $sections[ ] = array(
        'title'      => 'Blueprint',
        'show_title' => false,
        'icon_class' => 'icon-large',
        'icon'       => 'el-icon-website',
        'desc'       => 'Class: .pzarc-blueprint_{shortname}',
        'fields'     => pzarc_fields(
        // TODO: Get correct $defaults
        // TODO: Add shadows
            pzarc_redux_bg($prefix . 'blueprint-background', array('.pzarc-blueprint')),
            pzarc_redux_padding($prefix . 'blueprint-padding', array('.pzarc-blueprint')),
            pzarc_redux_margin($prefix . 'blueprint-margins', array('.pzarc-blueprint')),
            pzarc_redux_borders($prefix . 'blueprint-borders', array('.pzarc-blueprint')),
            pzarc_redux_links($prefix . 'blueprint-links', array('.pzarc-blueprint')),
            array(
                'title' => __('Custom CSS', 'pzarchitect'),
                'id'    => $prefix . 'blueprint-custom-css',
                'type'  => 'ace_editor',
                'mode'  => 'css',
                'hint'  => array('content' => __('This is can be any CSS you\'d like to add to a page this blueprint is displayed on', 'pzarchitect')),
            )
        )
    );
    $sections[ ] = array(
        'title'      => 'Sections wrapper',
        'show_title' => false,
        'icon_class' => 'icon-large',
        'icon'       => 'el-icon-check-empty',
        'desc'       => 'Class: .pzarc-sections_{shortname}',
        'fields'     => pzarc_fields(

        // TODO: Get correct $defaults
        // TODO: Add shadows
            pzarc_redux_bg($prefix . 'sections-background', array('.pzarc-sections')),
            pzarc_redux_padding($prefix . 'sections-padding', array('.pzarc-sections')),
            pzarc_redux_margin($prefix . 'sections-margins', array('.pzarc-sections')),
            pzarc_redux_borders($prefix . 'sections-borders', array('.pzarc-sections'))
        )
    );
    $icons       = array(1 => 'el-icon-align-left', 2 => 'el-icon-th', 3 => 'el-icon-th-list');

    $sections[ ] = array(
        'title'      => 'Section 1',
        'show_title' => false,
        'icon_class' => 'icon-large',
        'icon'       => $icons[ 1 ],
        'desc'       => 'Class: .pzarc-section_1',
        'fields'     => array(
            pzarc_redux_bg($prefix . 'pzarc-section_1-background', array('.pzarc-section_1')),
            pzarc_redux_padding($prefix . 'pzarc-section_1-padding', array('.pzarc-section_1')),
            pzarc_redux_margin($prefix . 'pzarc-section_1-margins', array('.pzarc-section_1')),
            pzarc_redux_borders($prefix . 'pzarc-section_1-borders', array('.pzarc-section_1')),
        ),
    );
    $sections[ ] = array(
        'title'      => 'Section 2',
        'show_title' => false,
        'icon_class' => 'icon-large',
        'icon'       => $icons[ 2 ],
        'desc'       => 'Class: .pzarc-section_2',
        'fields'     => array(
            pzarc_redux_bg($prefix . 'pzarc-section_2-background', array('.pzarc-section_2')),
            pzarc_redux_padding($prefix . 'pzarc-section_2-padding', array('.pzarc-section_2')),
            pzarc_redux_margin($prefix . 'pzarc-section_2-margins', array('.pzarc-section_2')),
            pzarc_redux_borders($prefix . 'pzarc-section_2-borders', array('.pzarc-section_2')),
        ),
    );
    $sections[ ] = array(
        'title'      => 'Section 3',
        'show_title' => false,
        'icon_class' => 'icon-large',
        'icon'       => $icons[ 3 ],
        'desc'       => 'Class: .pzarc-section_3',
        'fields'     => array(
            pzarc_redux_bg($prefix . 'pzarc-section_3-background', array('.pzarc-section_3')),
            pzarc_redux_padding($prefix . 'pzarc-section_3-padding', array('.pzarc-section_3')),
            pzarc_redux_margin($prefix . 'pzarc-section_3-margins', array('.pzarc-section_3')),
            pzarc_redux_borders($prefix . 'pzarc-section_3-borders', array('.pzarc-section_3')),
        ),
    );

    $sections[ ]  = array(
        'id'         => 'blueprint-styling-help',
        'title'      => 'Help',
        'icon_class' => 'icon-large',
        'icon'       => 'el-icon-question-sign',
        'fields'     => array(

            array(
                'title' => __('Blueprint styling', 'pzarchitect'),
                'id'    => $prefix . 'help',
                'type'  => 'info',
                //  'class' => 'plain',
                'desc'  => '<h3>Adding underlines to hover links</h3>
                            <p>In the Custom CSS field, enter the following CSS</p>
                            <p>.pzarc-blueprint_SHORTNAME a:hover {text-decoration:underline;}</p>
                            <p>SHORTNAME = the short name you entered for this blueprint</p>
                            <h3>Make pager appear outside of panels</h3>
                            <p>If you want the pager to appear outside of the panels instead of over them, set a deep left and right padding on the blueprint.</p>
                            '

            )
        )
    );
    $metaboxes[ ] = array(
        'id'         => 'blueprint-stylings',
        'title'      => 'Blueprint Styling',
        'post_types' => array('arc-blueprints'),
        'sections'   => $sections,
        'position'   => 'normal',
        'priority'   => 'low',
        'sidebar'    => true

    );

    //pzdebug($metaboxes);


    return $metaboxes;
  }


