<?php
  $redux_opt_name = '_architect';

  class pzarc_Blueprints
  {


    /**
     * [__construct description]
     */
    function __construct()
    {
      add_action('init', array($this, 'create_blueprints_post_type'));
      // This overrides the one in the parent class

      if (is_admin())
      {

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
      if ('arc-blueprints' == $screen->id)
      {


        wp_enqueue_style('pzarc-admin-blueprints-css', PZARC_PLUGIN_URL . '/data/admin/css/arc-admin-blueprints.css');

        wp_enqueue_script('jquery-pzarc-metaboxes-blueprints', PZARC_PLUGIN_URL . '/data/admin/js/arc-metaboxes-blueprints.js', array('jquery'));
        wp_enqueue_script('js-isotope-v2');
        // wp_enqueue_script('jquery-masonary', PZARC_PLUGIN_URL . 'external/masonry.pkgd.min.js', array('jquery'));
        // wp_enqueue_script('jquery-lorem', PZARC_PLUGIN_URL . 'external/jquery.lorem.js', array('jquery'));
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
      $pzarc_front = array_slice($columns, 0, 2);
      $pzarc_back  = array_slice($columns, 2);
      $pzarc_insert
                   = array
      (
          '_blueprints_short-name' => __('Blueprint short name', 'pzsp'),
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
//      switch ($column)
//      {
//        case '_blueprints_short-name':
      echo $post_meta[ $column ];
//          break;
//      }
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
      //          'title'      => __('General Settings', 'pzarc'),
      'show_title' => true,
      'icon_class' => 'icon-large',
      'icon'       => 'el-icon-home',
      'fields'     => array(
          array(
              'id'      => $prefix . 'tabs',
              'type'    => 'tabbed',
              'options' => array(
                  'layout'  => '<span><span class="icon-large el-icon-website"></span> Layout</span>',
                  'content' => '<span><span class="icon-large el-icon-align-left"></span> Content</span>'),
              'targets' => array('layout'  => array('layout-settings', '_blueprints_layout-general-settings'),
                                 'content' => array('content-selections', '_blueprints_content-general-settings'))
          ),
      )
    );
    $metaboxes[ ] = array(
        'id'         => $prefix . 'blueprints',
        'title'      => 'Show settings for:',
        'post_types' => array('arc-blueprints'),
        'sections'   => $sections,
        'position'   => 'side',
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
                'title'    => __('Blueprint Short Name', 'pzarc'),
                'type'     => 'text',
                'width'    => 'auto',
                'subtitle' => __('Alphanumeric only. <br/>Use the shortcode <strong class="pzarc-usage-info">[pzarc "<span class="pzarc-shortname"></span>"]</strong> or the template tag <strong class="pzarc-usage-info">pzarc(\'<span class="pzarc-shortname"></span>\');</strong>', 'pzarc'),
                'validate' => 'not_empty'
            ),
            array(
                'title'    => 'Select what type of navigation to use',
                'id'       => $prefix . 'navigation',
                'type'     => 'button_set',
                'width'    => 'auto',
                'default'  => 'none',
                'subtitle' => 'Note: Navigator will only function when one section',
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
                'title'    => __('Content source', 'pzarc'),
                'id'       => $prefix . 'content-source',
                'type'     => 'select',
                'select2'  => array('allowClear' => false),
                'default'  => 'defaults',
                'options'  => array(
                    'defaults' => 'Defaults',
                    'post'     => 'Posts',
                    'page'     => 'Pages',
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
                'title'   => __('Order by', 'pzarc'),
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
                'title'   => __('Order direction', 'pzarc'),
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
                'title'   => __('Skip N posts', 'pzarc'),
                'id'      => $prefix . 'skip',
                'type'    => 'spinner',
                'min'     => 0,
                'max'     => 9999,
                'step'    => 1,
                'default' => 0,
                'desc'    => __('Note: Skipping breaks pagination. This is a known WordPress bug.', 'pzarc'),
            ),
            array(
                'title'   => __('Sticky posts first', 'pzarc'),
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


    $args = array(
        'posts_per_page'   => -1,
        'orderby'          => 'title',
        'order'            => 'ASC',
        'post_type'        => 'arc-panels',
        'suppress_filters' => true);

    $pzarc_panels       = get_posts($args);
    $pzarc_panels_array = array();
    if (!empty($pzarc_panels))
    {
      foreach ($pzarc_panels as $pzarc_cell)
      {
        $pzarc_panels_array[ $pzarc_cell->ID ] = (empty($pzarc_cell->post_title) ? 'No title' : $pzarc_cell->post_title);
      }
    }
    else
    {
      $pzarc_panels_array = array(0 => 'No cell layouts. Create some.');
    }
    // ID,post_title
    $icons = array(0 => 'el-icon-align-left', 1 => 'el-icon-th', 2 => 'el-icon-th-list');
    for ($i = 0; $i < 3; $i++)
    {
      $sections[ ] = array(
          'title'      => __('Section ' . ($i + 1), 'pzarc'),
          'show_title' => true,
          'icon_class' => 'icon-large',
          'icon'       => $icons[ $i ],
          'fields'     => array(

              array(
                  'id'    => $prefix . 'section-' . $i . '-title',
                  'title' => __('Section ' . ($i + 1) . ' title (optional)', 'pzarc'),
                  'type'  => 'text',
                  'cols'  => 12,
              ),
              array(
                  'id'      => $prefix . 'section-' . $i . '-panel-layout',
                  'title'   => __('Panels layout', 'pzarc'),
                  'type'    => 'select',
                  'cols'    => 6,
                  'select2' => array('allowClear' => false),
                  'options' => $pzarc_panels_array
              ),
              array(
                  'title'   => __('Layout mode', 'pzarc'),
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
                  //       'subtitle'    => __('Choose how you want the panels to display. With evenly sized panels, you\'ll see little difference. Please visit <a href="http://isotope.metafizzy.co/demos/layout-modes.html" target=_blank>Isotope Layout Modes</a> for demonstrations of these layouts', 'pzarc')
              ),
              array(
                  'title'   => __('Panels to show', 'pzarc'),
                  'id'      => $prefix . 'section-' . $i . '-panels-per-view',
                  'type'    => 'spinner',
                  'default' => 0,
                  'min'     => 0,
                  'max'     => 99,
                  'cols'    => 2,
                  'desc'    => '0 for unlimited'
              ),
              array(
                  'title'    => __('Columns', 'pzarc'),
                  'id'       => $prefix . 'section-' . $i . '-columns',
                  'subtitle' => 'Number of columns or panels across. Number of rows is calculated automatically.',
                  'type'     => 'spinner',
                  'default'  => 3,
                  'min'      => 1,
                  'cols'     => 2,
                  'max'      => 999,
              ),
              array(
                  'title'   => __('Minimum panel width', 'pzarc'),
                  'id'      => $prefix . 'section-' . $i . '-min-panel-width',
                  'type'    => 'spinner',
                  'alt'     => 'minpanelw',
                  'default' => 0,
                  'min'     => '0',
                  'max'     => 9999,
                  'cols'    => 2,
                  'step'    => '1',
                  'suffix'  => 'px',
                  //      'subtitle'    => __('Set the minimum width for sells in this section. This helps with responsive layout', 'pzarc')
              ),
              array(
                  'title'   => __('Panels vertical margin', 'pzarc'),
                  'id'      => $prefix . 'section-' . $i . '-panels-vert-margin',
                  'type'    => 'spinner',
                  'alt'     => 'gutterv',
                  'default' => '1',
                  'min'     => '0',
                  'max'     => '100',
                  'cols'    => 3,
                  'step'    => '1',
                  'suffix'  => '%',
                  //    'subtitle'    => __('Set the vertical gutter width as a percentage of the section width. The gutter is the gap between adjoining elements', 'pzarc')
              ),
              array(
                  'title'   => __('Panels horizontal margin', 'pzarc'),
                  'id'      => $prefix . 'section-' . $i . '-panels-horiz-margin',
                  'type'    => 'spinner',
                  'alt'     => 'gutterh',
                  'default' => '1',
                  'min'     => '0',
                  'max'     => '100',
                  'cols'    => 3,
                  'step'    => '1',
                  'suffix'  => '%',
                  //      'subtitle'    => __('Set the horizontal gutter width as a percentage of the section width. The gutter is the gap between adjoining elements', 'pzarc')
              ),
          )

      );
    }
    $sections[ ] = array(
        'title'      => __('Pagination', 'pzarc'),
        'show_title' => true,
        'icon_class' => 'icon-large',
        'icon'       => 'el-icon-chevron-right',
        'required'   => array('blueprints_navigation', 'equals', 'pagination'),
        'fields'     => array(

            array(
                'id'      => $prefix . 'pager',
                'title'   => __('Pagination', 'pzarc'),
                'type'    => 'button_set',
                'cols'    => 12,
                'default' => 'wppagination',
                'options' => array(
                    'none'       => 'None',
                    'names'      => 'Post names',
                    'prevnext'   => 'Previous/Next',
                    'wppagenavi' => 'PageNavi',
                )
            ),
            array(
                'id'      => $prefix . 'pager-location',
                'title'   => __('Pagination location', 'pzarc'),
                'type'    => 'button_set',
                'cols'    => 12,
                'default' => 'bottom',
                'options' => array(
                    'bottom' => 'Bottom',
                    'top'    => 'Top',
                    'both'   => 'Both'
                )
            ),
            array(
                'id'      => $prefix . 'posts-per-page',
                'title'   => __('Posts per page', 'pzarc'),
                'type'    => 'text',
                'cols'    => 12,
                'default' => 'Do we really need this? Can\'t we just use the total panels per section?',
            ),
        )
    );
    $sections[ ] = array(
        'title'      => __('Navigator', 'pzarc'),
        'show_title' => true,
        'icon_class' => 'icon-large',
        'icon'       => 'el-icon-play-circle',
        'required'   => array('blueprints_navigation', 'equals', 'navigator'),
        'desc'       => 'When the navigation type is set to navigator, presentation will always be in a slider form.',
        'fields'     => array(
            array(
                'title' => __('General', 'pzarc'),
                'id'    => $prefix . 'navigator-heading',
                'type'  => 'section',
                'class' => ' heading',
            ),
            array(
                'id'      => $prefix . 'navigator',
                'title'   => __('Navigator Type', 'pzarc'),
                'type'    => 'image_select',
                'default' => 'tabbed',
                'height'   => 75,
                'options' => array(
                    'tabbed' => array(
                        'alt' => 'Tabbed',
                        'img' => PZARC_PLUGIN_URL . 'assets/images/metaboxes/nav-type-tabbed.png'
                    ),
                    'accordion' => array(
                        'alt' => 'Accordion',
                        'img' => PZARC_PLUGIN_URL . 'assets/images/metaboxes/nav-type-accordion.png'
                    ),
                    'buttons' => array(
                        'alt' => 'Buttons',
                        'img' => PZARC_PLUGIN_URL . 'assets/images/metaboxes/nav-type-buttons.png'
                    ),
                    'bullets' => array(
                        'alt' => 'Tabbed',
                        'img' => PZARC_PLUGIN_URL . 'assets/images/metaboxes/nav-type-bullets.png'
                    ),
                    'numbers' => array(
                        'alt' => 'Numbers',
                        'img' => PZARC_PLUGIN_URL . 'assets/images/metaboxes/nav-type-numeric.png'
                    ),
                    'thumbs' => array(
                        'alt' => 'Thumbs',
                        'img' => PZARC_PLUGIN_URL . 'assets/images/metaboxes/nav-type-thumbs.png'
                    ),
                )
            ),
            array(
                'title'   => 'Navigator Position',
                'id'      => $prefix . 'navigator-position',
                'type'    => 'image_select',
                'default' => 'bottom',
                'height'   => 75,
                'options' => array(
                    'bottom' => array(
                        'alt' => 'Bottom',
                        'img' => PZARC_PLUGIN_URL . 'assets/images/metaboxes/nav-pos-bottom.png'
                    ),
                    'top'    => array(
                        'alt' => 'Top',
                        'img' => PZARC_PLUGIN_URL . 'assets/images/metaboxes/nav-pos-top.png'
                    ),
                    'left'   => array(
                        'alt' => 'Left',
                        'img' => PZARC_PLUGIN_URL . 'assets/images/metaboxes/nav-pos-left.png'
                    ),
                    'right'  => array(
                        'alt' => 'Right',
                        'img' => PZARC_PLUGIN_URL . 'assets/images/metaboxes/nav-pos-right.png'
                    ),
                )
            ),
            array(
                'title'   => 'Navigator Location',
                'id'      => $prefix . 'navigator-location',
                'subtitle' => 'Select whether navigator should appear over the content are, or outside of it',
                'type'    => 'image_select',
                'default' => 'outside',
                'height'   => 75,
                'options' => array(
                    'inside'  => array(
                        'alt' => 'Inside',
                        'img' => PZARC_PLUGIN_URL . 'assets/images/metaboxes/nav-loc-inside.png'
                    ),
                    'outside' => array(
                        'alt' => 'Outside',
                        'img' => PZARC_PLUGIN_URL . 'assets/images/metaboxes/nav-loc-outside.png'
                    ),
                )
            ),
            array(
                'title'   => 'Navigator Pager',
                'id'      => $prefix . 'navigator-pager',
                'type'    => 'button_Set',
                'cols'    => 6,
                'default' => 'none',
                'options' => array(
                    'none'   => 'None',
                    'hover'  => 'Hover over panels',
                    'inline' => 'Inline with navigator'
                )
            ),
            array(
                'id'       => $prefix . 'navigator-items-visible',
                'title'    => __('Navigator items visible', 'pzarc'),
                'type'     => 'spinner',
                'default'  => 0,
                'subtitle' => 'If zero, it will use the "Panels to show" value. This is the number of items visible in the navigator bar.'
            ),
            array(
                'title' => __('Transition', 'pzarc'),
                'id'    => $prefix . 'transition-heading',
                'type'  => 'section',
                'class' => ' heading',
            ),
            array(
                'title'   => 'Type',
                'id'      => $prefix . 'transitions-type',
                'type'    => 'select',
                'default' => 'none',
                'select2' => array('allowClear' => false),
                'options' => array(
                    'none'  => 'None',
                    'fade'  => 'Fade',
                    'slide' => 'Slide',
                )
            ),
            array(
                'title'    => 'Duration',
                'id'       => $prefix . 'transitions-duration',
                'type'     => 'spinner',
                'subtitle' => 'Time taken for the transition to display',
                'default'  => 2,
            ),
            array(
                'title'    => 'Interval',
                'id'       => $prefix . 'transitions-interval',
                'type'     => 'spinner',
                'default'  => 5,
                'subtitle' => 'Time between transitions',
            ),
            array(
                'title'   => 'Auto start',
                'id'      => $prefix . 'transitions-autostart',
                'type'    => 'switch',
                'default' => false,
            ),
            array(
                'title'   => 'Pause on hover',
                'id'      => $prefix . 'transitions-pause-on-hover',
                'type'    => 'switch',
                'default' => true,
            ),

        )
    );
    $sections[ ] = array(
        'title'      => __('Wireframe Preview', 'pzarc'),
        'show_title' => true,
        'icon_class' => 'icon-large',
        'icon'       => 'el-icon-th-large',
        'fields'     => array(

            array(
                'title'    => 'Sections',
                'id'       => $prefix . 'sections-preview',
                'cols'     => 12,
                'type'     => 'raw',
                'readonly' => false, // Readonly fields can't be written to by code! Weird
                'content'  => pzarc_draw_sections_preview(),
            ),
        )
    );

    $sections[ ] = array(
        'title'      => 'Help',
        'icon_class' => 'icon-large',
        'icon'       => 'el-icon-info-sign',
        'fields'     => array(

            array(
                'title' => __('Layout', 'pzarc'),
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
                              </p>'

            )
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
          <div class="plugin_url" style="display:none;">' . PZARC_PLUGIN_URL . '</div>
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
                'title'    => __('Defaults', 'pzarc'),
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
                'title' => __('Posts', 'pzarc'),
                'id'    => $prefix . 'section-start-posts',
                'type'  => 'section',
                'class' => ' heading',
            ),
            array(
                'id'     => $prefix . 'section-end-posts',
                'type'   => 'section',
                'indent' => false
            ),
            array(
                'title'  => __('Categories', 'pzarc'),
                'id'     => $prefix . 'categories-heading-start',
                'type'   => 'section',
                'class'  => ' heading',
                'indent' => true
            ),
            array(
                'title'   => __('Include categories', 'pzarc'),
                'id'      => $prefix . 'inc-cats',
                'type'    => 'select',
                'select2' => array('allowClear' => true),
                'default' => '',
                'cols'    => 3,
                'data'    => 'category',
                'multi'   => true
            ),
            array(
                'title'   => __('In ANY or ALL categories', 'pzarc'),
                'id'      => $prefix . 'all-cats',
                'type'    => 'button_set',
                'options' => array('any' => 'Any', 'all' => 'All'),
                'default' => 'any',
            ),
            array(
                'title'   => __('Exclude categories', 'pzarc'),
                'id'      => $prefix . 'exc-cats',
                'type'    => 'select',
                'default' => '',
                'select2' => array('allowClear' => true),
                'cols'    => 3,
                'data'    => 'category',
                'multi'   => true
            ),
            array(
                'title'   => __('Include sub-categories on archives', 'pzarc'),
                'id'      => $prefix . 'sub-cat-archives',
                'type'    => 'switch',
                'on'      => 'Yes',
                'off'     => 'No',
                'default' => true,
                'cols'    => 3,
            ),
            array(
                'id'     => $prefix . 'categories-section-end',
                'type'   => 'section',
                'indent' => false
            ),
            array(
                'title'  => __('Tags', 'pzarc'),
                'id'     => $prefix . 'tags-section-end',
                'type'   => 'section',
                'class'  => ' heading',
                'indent' => true
            ),
            array(
                'title'   => __('Tags', 'pzarc'),
                'id'      => $prefix . 'inc-tags',
                'type'    => 'select',
                'select2' => array('allowClear' => true),
                'default' => '',
                'cols'    => 4,
                'data'    => 'tags',
                'multi'   => true
            ),
            array(
                'title'   => __('Exclude tags', 'pzarc'),
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
                'title'  => __('Custom taxonomies', 'pzarc'),
                'id'     => $prefix . 'custom-taxonomies-section-start',
                'type'   => 'section',
                'class'  => ' heading',
                'indent' => true
            ),
            array(
                'title' => __('Other taxonomies', 'pzarc'),
                'id'    => $prefix . 'other-tax',
                'type'  => 'select',
                'data'  => 'taxonomies',
                'args'  => array('_builtin' => false)
            ),
            array(
                'title'    => __('Other taxonomy tags', 'pzarc'),
                'id'       => $prefix . 'other-tax-tags',
                'type'     => 'text',
                'subtitle' => 'Enter a comma separated list of tags to filter by in the chosen custom taxonomy'
            ),
            array(
                'title'    => __('Taxonomies operator', 'pzarc'),
                'id'       => $prefix . 'tax-op',
                'type'     => 'button_set',
                'options'  => array('all' => 'All', 'any' => 'Any'),
                'subtitle' => 'Choose whether posts contain all or any of the taxonomies'
            ),
            //    array(
            //      'title' => __('Days to show', 'pzarc'),
            //      'id' => $prefix . 'days',
            //      'type' => 'text',
            //      'cols'=>6,
            //              //      'default' => 'All',
            //    ),
            //    array(
            //      'title' => __('Days to show until', 'pzarc'),
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
                'title'  => __('Others', 'pzarc'),
                'id'     => $prefix . 'other-section-start',
                'type'   => 'section',
                'class'  => ' heading',
                'indent' => true
            ),
            array(
                'title'   => __('Authors', 'pzarc'),
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
    $prefix = '_content_pages_';

    $sections[ ] = array(
        'title'      => 'Pages',
        'icon_class' => 'icon-large',
        'icon'       => 'el-icon-align-justify',
        'fields'     => array(
            array(
                'title' => __('Pages', 'pzarc'),
                'id'    => $prefix . 'pages-heading',
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
                'title'    => __('Gallery source', 'pzarc'),
                'id'       => $prefix . 'gallery-source',
                'type'     => 'button_set',
                'default'  => 'images',
                'subtitle' => __('Can be overriden by shortcode e.g. [pzarc blueprint="mytemplate" ids="1,2,3,4,5"]', 'pzarc'),
                'options'  => array(
                    'images'      => 'Image Gallery',
                    'ids'         => 'Specific IDs',
                    'wpgallery'   => 'WP Galleries',
                    'postimages'  => 'Post images',
                    'galleryplus' => 'GalleryPlus',
                    'nggallery'   => 'NextGen',
                )
            ),
            array(
                'title'    => __('Image Gallery', 'pzarc'),
                'id'       => $prefix . 'specific-images',
                'type'     => 'gallery',
                'required' => array($prefix . 'gallery-source', 'equals', 'images'),
            ),
            array(
                'title'    => __('Specific IDs', 'pzarc'),
                'id'       => $prefix . 'specific-ids',
                'type'     => 'text',
                'subtitle' => 'Enter a comma separated list of image ids',
                'required' => array($prefix . 'gallery-source', 'equals', 'ids')
            ),
            array(
                'title'    => __('WP Gallery', 'pzarc'),
                'id'       => $prefix . 'wp-post-gallery',
                'type'     => 'select',
                'data'     => 'callback',
                'args'     => array('pzarc_get_wp_galleries'),
                'subtitle' => 'Select a post with a gallery',
                'required' => array($prefix . 'gallery-source', 'equals', 'wpgallery')
            ),
            array(
                'title'    => __('Post iamges', 'pzarc'),
                'id'       => $prefix . 'wp-post-images',
                'type'     => 'select',
                'data'     => 'callback',
                'args'     => array('pzarc_get_wp_galleries'),
                'subtitle' => 'Select a post with images',
                'required' => array($prefix . 'gallery-source', 'equals', 'wpgallery')
            ),
            array(
                'title'    => __('GalleryPlus', 'pzarc'),
                'id'       => $prefix . 'galleryplus',
                'type'     => 'select',
                'data'     => 'callback',
                'args'     => array('pzarc_get_gp_galleries'),
                'subtitle' => 'Select a GalleryPlus gallery',
                'required' => array($prefix . 'gallery-source', 'equals', 'galleryplus')
            ),
            array(
                'title'    => __('NextGen Gallery', 'pzarc'),
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
                'title' => __('Slides', 'pzarc'),
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
                'title' => __('Custom Post Types', 'pzarc'),
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
        'icon'       => 'el-icon-info-sign',
        'fields'     => array(

            array(
                'title' => __('Content Selection', 'pzarc'),
                'id'    => $prefix . 'help-content-selection',
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
                              </p>'

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


  function pzarc_get_ng_galleries()
  {
    if (!class_exists('P_Photocrati_NextGen'))
    {
      return null;
    }
    global $ngg, $nggdb;
    $results = array();


    $ng_galleries = $nggdb->find_all_galleries('gid', 'asc', true, 0, 0, false);

    if ($ng_galleries)
    {
      foreach ($ng_galleries as $gallery)
      {
        $results[ $gallery->gid ] = $gallery->title;
      }
    }

    return $results;
  }

  function pzarc_get_gp_galleries()
  {
    $post_types = get_post_types();
    if (!isset($post_types[ 'gp_gallery' ]))
    {
      return null;
    }
    // Don't need to check for GPlus class coz we add the post type
    // Get GalleryPlus galleries
    $args    = array('post_type' => 'gp_gallery', 'numberposts' => -1, 'post_status' => null, 'post_parent' => null);
    $albums  = get_posts($args);
    $results = array();
    if ($albums)
    {
      foreach ($albums as $post)
      {
        setup_postdata($post);
        $results[ $post->ID ] = get_the_title($post->ID);
      }
    }

    return $results;
  }

  function pzarc_get_wp_galleries()
  {

    // Get galleries in posts and pages
    $args    = array('post_type'   => array('post', 'page'),
                     'numberposts' => -1,
                     'post_status' => null,
                     'post_parent' => null);
    $albums  = get_posts($args);
    $results = array();
    if ($albums)
    {
      foreach ($albums as $post)
      {
        setup_postdata($post);
        if (get_post_gallery($post->ID))
        {
          $results[ $post->ID ] = substr(get_the_title($post->ID), 0, 60);
        }
      }
    }

    return $results;


  }

  function pzarc_get_authors()
  {
// Get authors
    $userslist    = get_users();
    $authors[ 0 ] = 'All';
    foreach ($userslist as $author)
    {
      if (get_the_author_meta('user_level', $author->ID) >= 2)
      {
        $authors[ $author->ID ] = $author->display_name;
      }
    }

    return $authors;
  }