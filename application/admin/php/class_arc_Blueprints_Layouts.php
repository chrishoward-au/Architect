<?php

  class arc_Blueprints_Layouts
  {

    public $content_types;
    public $redux_opt_name = '_architect';

    /**
     * [__construct description]
     */
    function __construct()
    {

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
            $pzarc_panels_array[ $pzarc_cell->post_name ] = (empty($pzarc_cell->post_title) ? 'No title' : $pzarc_cell->post_title);
          }
        } else {
          $pzarc_panels_array = array(0 => 'No cell layouts. Create some.');
        }

        add_action('admin_head', array($this, 'content_blueprints_admin_head'));
        add_action('admin_enqueue_scripts', array($this, 'content_blueprints_admin_enqueue'));
        add_filter('manage_arc-blueprints_posts_columns', array($this, 'add_blueprint_columns'));
        add_action('manage_arc-blueprints_posts_custom_column', array($this, 'add_blueprint_column_content'), 10, 2);

        add_action("redux/metaboxes/$this->redux_opt_name/boxes", array($this, 'pzarc_blueprint_tabs'), 10, 1);
        add_action("redux/metaboxes/$this->redux_opt_name/boxes", array($this,
                                                                        'pzarc_blueprint_layout_general'), 10, 1);
        add_action("redux/metaboxes/$this->redux_opt_name/boxes", array($this, 'pzarc_blueprint_layout'), 10, 1);
        add_action("redux/metaboxes/$this->redux_opt_name/boxes", array($this, 'pzarc_contents_metabox'), 10, 1);
        add_action("redux/metaboxes/$this->redux_opt_name/boxes", array($this,
                                                                        'pzarc_blueprint_layout_styling'), 10, 1);
        add_action('views_edit-arc-blueprints', array($this,'blueprints_description'));


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


        wp_enqueue_style('pzarc-admin-blueprints-css', PZARC_PLUGIN_APP_URL . '/admin/css/arc-admin-blueprints.css');

        wp_enqueue_script('jquery-pzarc-metaboxes-blueprints', PZARC_PLUGIN_APP_URL . '/admin/js/arc-metaboxes-blueprints.js', array('jquery'));

        wp_enqueue_script('js-isotope-v2');

        wp_enqueue_script('js-magnific');


        // wp_enqueue_script('jquery-masonary', PZARC_PLUGIN_URL . 'includes/masonry.pkgd.min.js', array('jquery'));

        // wp_enqueue_script('jquery-lorem', PZARC_PLUGIN_URL . 'includes/jquery.lorem.js', array('jquery'));
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
          'panels'                     => __('Section Panels', 'pzarchitect'),
          '_blueprints_content-source' => __('Content source', 'pzarchitect'),
          'navigation'                 => __('Navigation', 'pzarchitect'),
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

      global $post;
      $slug      = $post->post_name;
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

        case 'navigation':
          switch (true) {
            case empty($post_meta[ '_blueprints_navigation' ][ 0 ]):
            case $post_meta[ '_blueprints_navigation' ][ 0 ] === 'none':
              echo 'None';
              break;
            case $post_meta[ '_blueprints_navigation' ][ 0 ] === 'pagination':
              echo 'Pagination : ' . ucwords(empty($post_meta[ '_blueprints_pager' ][ 0 ]) ? 'Prev/Next' : $post_meta[ '_blueprints_pager' ][ 0 ]);
              break;
            case $post_meta[ '_blueprints_navigation' ][ 0 ] === 'navigator':
              echo "Navigator : " . ucwords(empty($post_meta[ '_blueprints_navigator' ][ 0 ]) ? 'Tabbed' : $post_meta[ '_blueprints_navigator' ][ 0 ]);
              break;
          }
          break;

        case 'panels':
          global $pzarc_panels_array;
          echo '1: ' . $pzarc_panels_array[ $post_meta[ '_blueprints_section-0-panel-layout' ][ 0 ] ];
          if (!empty($post_meta[ '_blueprints_section-1-panel-layout' ][ 0 ])) {
            echo '<br>' . '2: ' . $pzarc_panels_array[ $post_meta[ '_blueprints_section-1-panel-layout' ][ 0 ] ];
          }
          if (!empty($post_meta[ '_blueprints_section-2-panel-layout' ][ 0 ])) {
            echo '<br>' . '3: ' . $pzarc_panels_array[ $post_meta[ '_blueprints_section-2-panel-layout' ][ 0 ] ];
          }
      }
    }


    function blueprints_description($post)
    {

      ?>
      <div class="after-title-help postbox">
        <div class="inside">
          <h4>About Blueprints</h4>
          <p class="howto">
            <?php echo __('Architect Blueprints are where you build the overall layouts to display. In Blueprints, you select a Panel, what content will appear in the panels, and how you want to lay them out.', 'pzarchitect'); ?></p>
          <p class="howto">Documentation can be found throughout Architect or online at the <a href="http://architect4wp.com/codex-listings" target="_blank">Architect Codex</a></p>

        </div>
        <!-- .inside -->
      </div><!-- .postbox -->
    <?php

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


    function pzarc_blueprint_tabs($metaboxes)
    {
      $prefix   = '_blueprint_tabs_';
      $sections = array();

      global $_architect_options;
      $fields = array();
      if (!empty($_architect_options[ 'architect_enable_styling' ])) {
        $fields = array(array(
                            'id'      => $prefix . 'tabs',
                            'type'    => 'tabbed',
                            'options' => array(
                                'layout'  => '<span><span class="icon-large el-icon-website"></span> Blueprint Layout</span>',
                                'content' => '<span><span class="icon-large el-icon-align-left"></span> Panel Content</span>',
                                'styling' => '<span><span class="icon-large el-icon-brush"></span> Blueprint Styling</span>'
                            ),
                            'targets' => array('layout'  => array('layout-settings',
                                                                  '_blueprints_layout-general-settings'),
                                               'content' => array('content-selections',
                                                                  '_blueprints_content-general-settings'),
                                               'styling' => array('blueprint-stylings',
                                                                  '_blueprints_styling-general-settings')
                            )
                        ),
        );
      } else {
        $fields = array(array(
                            'id'      => $prefix . 'tabs',
                            'type'    => 'tabbed',
                            'options' => array(
                                'layout'  => '<span><span class="icon-large el-icon-website"></span> Layout</span>',
                                'content' => '<span><span class="icon-large el-icon-align-left"></span> Content</span>',
                            ),
                            'targets' => array('layout'  => array('layout-settings',
                                                                  '_blueprints_layout-general-settings'),
                                               'content' => array('content-selections',
                                                                  '_blueprints_content-general-settings'),
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
    function pzarc_blueprint_layout_general($metaboxes)
    {
      $prefix = '_blueprints_';

      $sections[ ]  = array(
          'fields' => array(
              array(
                  'id'       => $prefix . 'short-name',
                  'title'    => __('Blueprint Short Name', 'pzarchitect') . '<span class="pzarc-required el-icon-star" title="Required"></span>',
                  'type'     => 'text',
                  'subtitle' => '<strong>' . __('Letters, numbers, dashes only. ', 'pzarchitect') . '</strong><br>' . __('Use the shortcode <strong class="pzarc-usage-info">[architect "<span class="pzarc-shortname"></span>"]</strong> <br>or the template tag <strong class="pzarc-usage-info">pzarchitect(\'<span class="pzarc-shortname"></span>\');</strong>', 'pzarchitect'),
                  //TODO: Write acomprehensive little help dialog here
                  'validate' => 'not_empty'
              ),
              array(
                  'id'    => $prefix . 'description',
                  'title' => __('Description', 'pzarchitect'),
                  'type'  => 'textarea',
                  'rows'  => 2,
                  'hint'  => __('A short description to help you or others know what this Blueprint is for', 'pzarchitect'),
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
                  'subtitle' => 'Set a max width to stop spillage when the container is larger than you want the Blueprint to be.'
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
                  'title'    => 'Navigation',
                  'id'       => $prefix . 'navigation',
                  'type'     => 'button_set',
                  'default'  => 'none',
                  'subtitle' => __('Note: Navigator will only function when one section selected. Pagination effects all sections.<br> <strong>Use Navigator for sliders and tabbed layouts</strong>', 'pzarchitect'),
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

    // add_action("redux/metaboxes/{$redux_opt_name}/boxes", 'pzarc_blueprint_content_general');
    /**
     * pzarc_blueprint_content_general
     * @param array $metaboxes
     * @return array
     */
    function pzarc_blueprint_content_general($metaboxes)
    {

      $prefix = '_blueprints_';

      $sections[ ] = array(
          'fields' => array()
      );

      $metaboxes[ ] = array(
          'id'         => $prefix . 'content-general-settings',
          'title'      => 'Content Settings',
          'post_types' => array('arc-blueprints'),
          'sections'   => $sections,
          'position'   => 'side',
          'priority'   => 'high',
          'sidebar'    => false

      );


      return $metaboxes;

    }


    /**
     * LAYOUT
     */
    function pzarc_blueprint_layout($metaboxes)
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
      $icons = array(0 => 'el-icon-align-left', 1 => 'el-icon-th', 2 => 'el-icon-th-list');
      for ($i = 0; $i < 3; $i++) {
        $sections[ '_section' . ($i + 1) ] = array(
            'title'      => __('Section ' . ($i + 1), 'pzarchitect'),
            'show_title' => true,
            'icon_class' => 'icon-large',
            'icon'       => $icons[ $i ],
            'fields'     => array(

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
                    'title'    => __('Limit panels (content)', 'pzarchitect'),
                    'id'       => $prefix . 'section-' . $i . '-panels-limited',
                    'type'     => 'switch',
                    'on'       => 'Yes',
                    'off'      => 'No',
                    'default'  => true,
                    'subtitle' => 'Each panel displays content from the selected content type.'
                ),
                array(
                    'title'    => __('Panels to show', 'pzarchitect'),
                    'id'       => $prefix . 'section-' . $i . '-panels-per-view',
                    'type'     => 'spinner',
                    'default'  => 1,
                    'min'      => 1,
                    'max'      => 99,
                    'subtitle' => __('This is how many posts will show if Limit enabled above', 'pzarchitect'),
                    'desc'     => __('If using pagination, this will be the number per page.', 'pzarchitect'),
                    //                  'required' => array(
                    //                      array( $prefix . 'section-' . $i . '-panels-limited', '=', true ),
                    //                      array( $prefix . 'navigation', '!=', 'pagination' ),
                    //                  )
                ),
                array(
                    'title'         => __('Columns wide screen', 'pzarchitect'),
                    'subtitle'      => $_architect_options[ 'architect_breakpoint_1' ][ 'width' ] . ' and above',
                    'id'            => $prefix . 'section-' . $i . '-columns-breakpoint-1',
                    'hint'          => array('content' => __('Number of columns or panels across on a wide screen as set in the breakpoints options. In sliders, this will be the number across.', 'pzarchitect')),
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
                    'hint'          => array('content' => __('Number of columns or panels across on a medium screen as set in the breakpoints options', 'pzarchitect')),
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
                    'title'   => __('Panels margins (%)', 'pzarchitect'),
                    'id'      => $prefix . 'section-' . $i . '-panels-margins',
                    'type'    => 'spacing',
                    'units'   => '%',
                    'mode'    => 'margin',
                    'default' => array('right' => '0', 'bottom' => '0', 'left' => '0', 'top' => '0'),
                    //'subtitle' => __('Right, bottom', 'pzarchitect')
                    //    'hint'  => array('content' => __('Set the vertical gutter width as a percentage of the section width. The gutter is the gap between adjoining elements', 'pzarchitect'))
                ),

            )

        );
      }

      /** PAGINATION  */
      $sections[ '_pagination' ] = array(
          'title'      => __('Pagination', 'pzarchitect'),
          'show_title' => true,
          'icon_class' => 'icon-large',
          'icon'       => 'el-icon-chevron-right',
          'required'   => array('blueprints_navigation', 'equals', 'pagination'),
          'fields'     => array(

              array(
                  'title'    => __('Note', 'pzarchitect'),
                  'id'       => $prefix . 'pager-info',
                  'type'     => 'info',
                  'subtitle' => '<p>' . __('<strong>Pagination will only show on WordPress pages that support it.</strong> These are the blog list page and single posts and pages - but not the front page if set as a static page.', 'pzarchitect') . '</p>' .
                      __('Also, you should never display more than one pagination element on a page as pagination reloads the page and will therefore affect all paginated content.', 'pzarchitect')
              ),
              array(
                  'id'      => $prefix . 'pager',
                  'title'   => __('Blog Pagination', 'pzarchitect'),
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
                  'id'      => $prefix . 'pager-single',
                  'title'   => __('Single Post Pagination', 'pzarchitect'),
                  'type'    => 'button_set',
                  'default' => 'prevnext',
                  'options' => array(
                    //                    'none'     => 'None',
                    'prevnext' => 'Previous/Next',
                    'names'    => 'Post names',
                  )
              ),
              array(
                  'id'      => $prefix . 'pager-archives',
                  'title'   => __('Archives Pagination', 'pzarchitect'),
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
              //            array(
              //                'id'      => $prefix . 'posts-per-page',
              //                'title'   => __('Posts per page', 'pzarchitect'),
              //                'type'    => 'text',
              //                'default' => 'Do we really need this? Can\'t we just use the total panels per section?',
              //            ),
          )
      );

      /** NAVIGATOR  */
      $sections[ '_navigator' ] = array(
          'title'      => __('Navigator', 'pzarchitect'),
          'show_title' => true,
          'icon_class' => 'icon-large',
          'icon'       => 'el-icon-play-circle',
          'required'   => array('blueprints_navigation', 'equals', 'navigator'),
          'desc'       => 'When the navigation type is set to navigator, presentation will always be in a slider form. You can have multiple navigators on a page, thus multiple sliders.',
          'fields'     => array(
              array(
                  'id'      => $prefix . 'navigator',
                  'title'   => __('Type', 'pzarchitect'),
                  'type'    => 'image_select',
                  'default' => 'tabbed',
                  'hint'    => array('content' => __('Tabbed, accordion, buttons, bullets, numbers, thumbnails or none', 'pzarchitect')),
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
                      'none'    => array(
                          'alt' => 'None',
                          'img' => PZARC_PLUGIN_APP_URL . 'shared/assets/images/metaboxes/nav-type-none.png'
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
                      //TODO: beta 2
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
                      array($prefix . 'navigator', '!=', 'accordion'),
                      array($prefix . 'navigator', '!=', 'none'),
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
                      array($prefix . 'navigator', '!=', 'accordion'),
                      array($prefix . 'navigator', '!=', 'none'),
                      array($prefix . 'navigator-position', '!=', 'top'),
                      array($prefix . 'navigator-position', '!=', 'bottom')
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
                  'required' => array(
                      array($prefix . 'navigator', '!=', 'accordion'),
                  )
              ),
//              array(
//                  'title'    => 'Skip left icon',
//                  'id'       => $prefix . 'navigator-skip-left',
//                  'type'     => 'button_set',
//                  'default'  => 'backward',
//                  'options'  => array(
//                      'backward'      => 'Backward',
//                      'step-backward' => 'Step Backward',
//                  ),
//                  'required' => array(
//                      array($prefix . 'navigator', '!=', 'accordion'),
//                  )
//              ),
//              array(
//                  'title'    => 'Skip right icon',
//                  'id'       => $prefix . 'navigator-skip-right',
//                  'type'     => 'button_set',
//                  'default'  => 'forward',
//                  'options'  => array(
//                      'forward'      => 'Forward',
//                      'step-forward' => 'Step Forward',
//                  ),
//                  'required' => array(
//                      array($prefix . 'navigator', '!=', 'accordion'),
//                  )
//              ),
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
                  'title' => __('Transitions', 'pzarchitect'),
                  'id'    => $prefix . 'section-transitions-heading',
                  'type'  => 'section',
                  'class' => ' heading',
              ),
              array(
                  'title'   => 'Type',
                  'id'      => $prefix . 'transitions-type',
                  'type'    => 'button_set',
                  'default' => 'slide',
                  //              'select2' => array('allowClear' => false),
                  'options' => array(
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
                  'content'  => file_get_contents(PZARC_DOCUMENTATION_PATH . '/using-blueprints.md'),
                  'pzarchitect')
          )
      );
      $sections[ '_help' ]    = array(
          'title'      => 'Help',
          'icon_class' => 'icon-large',
          'icon'       => 'el-icon-question-sign',
          'fields'     => array(

              array(
                  'title' => __('Help designing Blueprints', 'pzarchitect'),
                  'id'    => $prefix . 'help-layout',
                  'type'  => 'info',
                  'class' => 'plain',
                  'desc'  => '<p>For more help, visit the <a href="http://architect4wp.com/codex-listings" target=_blank>Architect documentation</a></p>
                  <h3>How do I make a slider?</h3>
                  <p>In the Navigation option, enable Navigator and then in the Navigator tab, select a Type of bullets, numbers or thumbs. Also, set all Columns to 1 in Section 1</p>
                  <h3>How do I make tabbed layout?</h3>
                  <p>In the Navigation option, enable Navigator and then in the Navigator tab, select a Type of titles. Also, set all Columns to 1 in Section 1</p>
            ')
          )
      );

      $metaboxes[ ] = array(
          'id'         => 'layout-settings',
          'title'      => 'Layout Settings',
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
    function pzarc_contents_metabox($metaboxes)
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

      $content_post_types = $registry->get('post_types');
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
          'title'      => 'Settings',
          'icon_class' => 'icon-large',
          'icon'       => 'el-icon-adjust-alt',
          'fields'     => array(
              array(
                  'title'    => __('Content source', 'pzarchitect'),
                  'id'       => '_blueprints_content-source',
                  'type'     => 'select',
                  'select2'  => array('allowClear' => false),
                  'default'  => 'defaults',
                  //                                    'options'  => array(
                  //                                        'defaults' => 'Defaults',
                  //                                        'post'     => 'Posts',
                  //                                        'page'     => 'Pages',
                  //                                        'snippets' => 'Snippets',
                  //                                        'gallery'  => 'Galleries',
                  //                                        'slides'   => 'Slides',
                  //                                        'dummy'    => 'Dummy content',
                  //                                        //                          'images'      => 'Specific Images',
                  //                                        //                          'wpgallery'   => 'WP Gallery from post',
                  //                                        //                          'galleryplus' => 'GalleryPlus',
                  //                                        //                          'nggallery'   => 'NextGen',
                  //                                        //                        'widgets' => 'Widgets',  // This one may not be workable if user can't control where sidebars appear
                  //                                        //                          'custom-code' => 'Custom code',
                  //                                        //                        'rss'     => 'RSS Feed',
                  //                                        'cpt'      => 'Custom Post Types'
                  //                                    ),
                  'options'  => $content_types,
                  'subtitle' => 'todo: code all the js to show hide relevant sections'
              ),
          )
      );

      // Add the rest off general content settings
      foreach ($blueprint_content_common[ 0 ][ 'general' ][ 'sections' ][ 'fields' ] as $value) {
        $sections[ '_general' ][ 'fields' ][ ] = $value;
      }


      /** FILTERS */
      $sections[ '_filters' ] = $blueprint_content_common[ 0 ][ 'filters' ][ 'sections' ];

      /** DISPLAY ALL THE CONTENT TYPES FORMS */
      foreach ($content_post_types as $key => $value) {
        if (isset($value[ 'blueprint-content' ])) {
          $sections[ '_' . $value[ 'blueprint-content' ][ 'type' ] ] = $value[ 'blueprint-content' ][ 'sections' ];
        }
      }

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
                  'content'  => 'With Architect you can enable an extra content type called *Snippets*.
  These give you a third method of creating content that doesn\'t fit into the post or page types.
It came about with my own need to create grids of product features. I didn\'t want to fill up pages or posts, so created Snippets for these small content bites.
You can use them however you like though.
                '

              )
          )
      );

      $metaboxes[ ] = array(
          'id'         => 'content-selections',
          'title'      => 'Content selections',
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
    function pzarc_blueprint_layout_styling($metaboxes)
    {
      global $_architect_options;
      if (!empty($_architect_options[ 'architect_enable_styling' ])) {

//  $screen = get_current_screen();
//  if ($screen->ID != 'xx') {return;}
        $defaults = get_option('_architect');

        $prefix = '_blueprints_styling_';

        $sections  = array();
        $optprefix = 'architect_config_';

//        $xsections[ ] = array(
//            'title'      => 'Styling',
//            'show_title' => false,
//            'icon_class' => 'icon-large',
//            'icon'       => 'el-icon-info-sign',
//            'fields'     => array(
//                array(
//                    'title'    => __('Styling Blueprints', 'pzarchitect'),
//                    'id'       => $prefix . 'styling-blueprints',
//                    'type'     => 'info',
//                    'subtitle' => __('To style blueprints...', 'pzarchitect'),
//                    //                'hint'  => array('content' => __('This is can be any CSS you\'d like to add to a page this blueprint is displayed on. It will ONLY load on the pages this blueprint is shown on, so will only impact those pages. However, if you have multiple blueprints on a page, this CSS could affect or be overriden by ther blueprints\' custom CSS.', 'pzarchitect')),
//                )
//
//            )
//        );


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
                    'title'    => __('Custom CSS', 'pzarchitect'),
                    'id'       => $prefix . 'blueprint-custom-css',
                    'type'     => 'ace_editor',
                    'mode'     => 'css',
                    'subtitle' => __('This can be any CSS you\'d like to add to a page this blueprint is displayed on. It will ONLY load on the pages this blueprint is shown on, so will only impact those pages. However, if you have multiple blueprints on a page, this CSS could affect or be overriden by ther blueprints\' custom CSS.', 'pzarchitect'),
                    //                'hint'  => array('content' => __('This is can be any CSS you\'d like to add to a page this blueprint is displayed on. It will ONLY load on the pages this blueprint is shown on, so will only impact those pages. However, if you have multiple blueprints on a page, this CSS could affect or be overriden by ther blueprints\' custom CSS.', 'pzarchitect')),
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

        $sections[ ] = array(
            'title'      => 'Navigator',
            'show_title' => false,
            'icon_class' => 'icon-large',
            'icon'       => 'el-icon-play-circle',
            'fields'     => array(

                array(
                    'title'    => __('Navigator container', 'pzarchitect'),
                    'id'       => $prefix . 'blueprint-nav-container-css-heading',
                    'type'     => 'section',
                    'subtitle' => 'Class: .pzarc-navigator',

                ),
                pzarc_redux_bg($prefix . 'pzarc-navigator-background', array('.arc-slider-nav','.pzarc-navigator')),
                pzarc_redux_padding($prefix . 'pzarc-navigator-padding', array('.arc-slider-nav','.pzarc-navigator')),
                pzarc_redux_margin($prefix . 'pzarc-navigator-margins', array('.arc-slider-nav','.pzarc-navigator')),
                pzarc_redux_borders($prefix . 'pzarc-navigator-borders', array('.arc-slider-nav','.pzarc-navigator')),

                array(
                    'title'    => __('Navigator items', 'pzarchitect'),
                    'id'       => $prefix . 'blueprint-nav-items-css-heading',
                    'type'     => 'section',
                    'subtitle' => 'Class: .pzarc-navigator .arc-slider-slide-nav-item span',

                ),
                pzarc_redux_font($prefix . 'pzarc-navigator-items-font', array('.pzarc-navigator .arc-slider-slide-nav-item span'),null),
                pzarc_redux_bg($prefix . 'pzarc-navigator-items-background', array('.pzarc-navigator .arc-slider-slide-nav-item span')),
                pzarc_redux_padding($prefix . 'pzarc-navigator-items-padding', array('.pzarc-navigator .arc-slider-slide-nav-item span')),
                pzarc_redux_margin($prefix . 'pzarc-navigator-items-margins', array('.pzarc-navigator .arc-slider-slide-nav-item span')),
                pzarc_redux_borders($prefix . 'pzarc-navigator-items-borders', array('.pzarc-navigator .arc-slider-slide-nav-item span')),
                pzarc_redux_border_radius($prefix . 'pzarc-navigator-items-borderradius', array('.pzarc-navigator .arc-slider-slide-nav-item span')),

                array(
                    'title'    => __('Navigator active item', 'pzarchitect'),
                    'id'       => $prefix . 'blueprint-nav-active-item-css-heading',
                    'type'     => 'section',
                    'subtitle' => 'Class: .pzarc-navigator .arc-slider-slide-nav-item.active span',

                ),
                pzarc_redux_font($prefix . 'pzarc-navigator-itemactive-font', array('.pzarc-navigator .arc-slider-slide-nav-item.active span'),null,array('letter-spacing','font-variant','text-transform','font-family','font-style','text-align','line-height','word-spacing')),
                pzarc_redux_bg($prefix . 'pzarc-navigator-itemactive-background', array('.pzarc-navigator .arc-slider-slide-nav-item.active span')),
                pzarc_redux_borders($prefix . 'pzarc-navigator-itemactive-borders', array('.pzarc-navigator .arc-slider-slide-nav-item.active span')),
            ),
        );

        $sections[ ]  = array(
            'id'         => 'blueprint-styling-help',
            'title'      => 'Help',
            'icon_class' => 'icon-large',
            'icon'       => 'el-icon-question-sign',
            'fields'     => array(

                array(
                    'title'    => __('Blueprint styling', 'pzarchitect'),
                    'id'       => $prefix . 'help',
                    'type'     => 'raw',
                    'markdown' => true,
                    //  'class' => 'plain',
                    'content'  => '<h3>Adding underlines to hover links</h3>
                            <p>In the Custom CSS field, enter the following CSS</p>
                            <p>.pzarc-blueprint_SHORTNAME a:hover {text-decoration:underline;}</p>
                            <p>SHORTNAME = the short name you entered for this blueprint</p>
                            <h3>Make pager appear outside of panels</h3>
                            <p>If you want the pager to appear outside of the panels instead of over them, set a the sections width less than 100%.</p>
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


      }

      // Still need to return this, even if we did nothing.
      return $metaboxes;
    }

  } // EOC

