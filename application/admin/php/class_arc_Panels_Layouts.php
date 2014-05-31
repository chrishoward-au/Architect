<?php

  //Panels may need to include Content because of custom fields!!! OOps! But content has to be over-arching! So I guess, cells will need to know the content type and its fields. So lets make Content Criteria again. :S

  /**
   * Class pzarc_Panel_Layouts
   */
  $redux_opt_name = '_architect';
  $show_hints     = true;

  //  new arc_Panels_Layouts;

  class arc_Panels_Layouts
  {


    /**
     * [__construct description]
     */
    function __construct()
    {
      add_action('init', array($this, 'create_layouts_post_type'));
      // This overrides the one in the parent class

      if (is_admin()) {
        //     require_once PZARC_PLUGIN_PATH . 'shared/pzarc-custom-field-types.php';

        //	add_action('admin_init', 'pzarc_preview_meta');
        //   add_action('add_meta_boxes', array($this, 'layouts_meta'));
        add_action('admin_head', array($this, 'cell_layouts_admin_head'));
        add_action('admin_enqueue_scripts', array($this, 'cell_layouts_admin_enqueue'));
        add_filter('manage_arc-panels_posts_columns', array($this, 'add_panel_layout_columns'));
        add_action('manage_arc-panels_posts_custom_column', array($this, 'add_panel_layout_column_content'), 10, 2);


        //        add_action('add_meta_boxes', array($this, 'add_panels_tabbed_metabox'));
        // check screen arc-panels. ugh. doesn't work for save and edit
        //			if ( $_REQUEST[ 'post_type' ] == 'arc-panels' )
        //			{
        //			}

        // Create the metaboxes redux style!

        // NOTE, YOU MUST load this before the code you do your new ReduxFramework declaration. IE,
        // put this at the top of your functions.php so it will load properly.

        // The loader will load all of the extensions automatically.
        // Alternatively you can run the include/init statements below.
        //    add_action('redux/metaboxes/architect/boxes', 'pzarc_add_panels_metaboxes',10);

      }

    }


    /**
     * [cell_layouts_admin_enqueue description]
     * @param  [type] $hook [description]
     * @return [type]       [description]
     */
    public function cell_layouts_admin_enqueue($hook)
    {
      $screen = get_current_screen();
      if ('arc-panels' == $screen->id) {
        //  var_dump($screen->id);

        wp_enqueue_script('jquery-ui-draggable');
        wp_enqueue_script('jquery-ui-droppable');
        wp_enqueue_script('jquery-ui-sortable');
        wp_enqueue_script('jquery-ui-resizable');

        wp_enqueue_style('pzarc-admin-panels-css', PZARC_PLUGIN_APP_URL . '/admin/css/arc-admin-panels.css');

        wp_enqueue_script('jquery-pzarc-metaboxes-panels', PZARC_PLUGIN_APP_URL . '/admin/js/arc-metaboxes-panels.js', array('jquery'));
      }
    }

    /**
     * [cell_layouts_admin_head description]
     * @return [type] [description]
     */
    public function cell_layouts_admin_head()
    {

    }

    /**
     * [add_panel_layout_columns description]
     * @param [type] $columns [description]
     */
    public function add_panel_layout_columns($columns)
    {
      unset($columns[ 'thumbnail' ]);
      $pzarc_front  = array_slice($columns, 0, 2);
      $pzarc_back   = array_slice($columns, 2);
      $pzarc_insert = array
      (
          '_panels_settings_short-name' => __('Short name', 'pzsp'),
      );

      return array_merge($pzarc_front, $pzarc_insert, $pzarc_back);
    }

    /**
     * [add_panel_layout_column_content description]
     * @param [type] $column  [description]
     * @param [type] $post_id [description]
     */
    public function add_panel_layout_column_content($column, $post_id)
    {
      $post_meta = get_post_meta($post_id, '_architect', true);
      //      var_dump($post_meta);
      //      switch ($column)
      //      {
      //        case '_blueprints_short-name':
      echo $post_meta[ $column ];
      //          break;
      //      }
      //      // thiswont work coz
      //      switch ($column)
      //      {
      //        case '_panels_settings_short-name':
      //          $metaboxes = get_post_meta($post_id, '_architect', true);
      //          echo $metaboxes[ '_panels_settings_short-name' ];
      //          break;
      //      case 'pzarc_set_name':
      //        echo get_post_meta($post_id, 'pzarc_layout-set-name', true);
      //        break;
      //     }
    }

    /**
     * [create_layouts_post_type description]
     * @return [type] [description]
     */
    public function create_layouts_post_type()
    {
      $labels = array(
          'name'               => _x('Panel designs', 'post type general name'),
          'singular_name'      => _x('Panel design', 'post type singular name'),
          'add_new'            => __('Add New Panel design'),
          'add_new_item'       => __('Add New Panel design'),
          'edit_item'          => __('Edit Panel design'),
          'new_item'           => __('New Panel design'),
          'view_item'          => __('View Panel design'),
          'search_items'       => __('Search Panel designs'),
          'not_found'          => __('No Panel designs found'),
          'not_found_in_trash' => __('No Panel designs found in Trash'),
          'parent_item_colon'  => '',
          'menu_name'          => _x('<span class="dashicons dashicons-id-alt"></span>Panels', 'pzarc-cell-designer'),
      );

      $args = array(
          'labels'              => $labels,
          'description'         => __('Architect Panels are used to create reusable panel designs for use in your Architect blocks, widgets, shortcodes and WP template tags'),
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
          'menu_position'       => 10,
          'supports'            => array('title', 'revisions'),
          'exclude_from_search' => true,
          //          'register_meta_box_cb' => 'redux/metaboxes/architect/boxes'
      );

      register_post_type('arc-panels', $args);
    }


  }

  // EOC


  /* why not use a WP like methodology!

  register_panels('title',$args)'
  register_criteria('title',$args);
  register_layout('title',$args);

  */


  //add_action("redux/metaboxes/{$redux_opt_name}/boxes", 'pzarc_panels_wizard_metabox');
  //function pzarc_panels_wizard_metabox($metaboxes)
  //{
  //  $prefix        = '_pzarc_';
  //  $fields        = array(
  //          array(
  //                  'title'    => 'Select what style of panel you want to make',
  //                  'id'      => $prefix . 'wizard',
  //                  'type'    => 'radio',
  //                  'default' => 'custom',
  //                  'hint'    => 'Select one to quickly enable relevant settings, or custom to build your own from scratch with all settings and defaults. Minimize this metabox once you are happy with your selection.',
  //                  'options' => array(
  //                          'custom'   => 'Custom',
  //                          'article'  => 'Article',
  //                          'blog'     => 'Blog Excerpt',
  //                          'feature'  => 'Featured Content',
  //                          'gallery'  => 'Gallery',
  //                          'magazine' => 'Magazine Excerpt',
  //                  )
  //          )
  //  );
  //  $metaboxes[ ] = array(
  //          'title'    => 'What do you want to do?',
  //          'pages'    => 'arc-panels',
  //          'context'  => 'normal',
  //          'priority' => 'high',
  //          'fields'   => $fields // An array of fields.
  //  );
  //
  //  return $metaboxes;
  //}

  // Load up the metaboxes. Do it all in one hit to easily manage ordering (since order can affect js working)

  add_action("redux/metaboxes/{$redux_opt_name}/boxes", 'pzarc_panel_tabs');
  add_action("redux/metaboxes/{$redux_opt_name}/boxes", 'pzarc_panel_general_settings');
  add_action("redux/metaboxes/{$redux_opt_name}/boxes", 'pzarc_panels_styling');
  add_action("redux/metaboxes/{$redux_opt_name}/boxes", 'pzarc_panels_design');


  function pzarc_panel_tabs($metaboxes)
  {
    $prefix        = '_panels_tabs_';
    $sections      = array();
    $sections[ 0 ] = array(
        'show_title' => true,
        'icon_class' => 'icon-large',
        'icon'       => 'el-icon-home',
        'fields'     => array(
            array(
                'id'      => $prefix . 'tabs',
                'type'    => 'tabbed',
                'options' => array(
                    'design'  => '<span class="icon-large el-icon-website"></span> Design',
                    'styling' => '<span class="icon-large el-icon-brush"></span> Styling'
                ),
                'targets' => array('design'  => array('panels-design', '_panels_settings_general-settings'),
                                   'styling' => array('panels-styling'))
            ),
        )
    );
    $metaboxes[ ]  = array(
        'id'         => $prefix . 'panel',
        'title'      => 'Show Panels settings for:',
        'post_types' => array('arc-panels'),
        'sections'   => $sections,
        'position'   => 'normal',
        'priority'   => 'high',
        'sidebar'    => false

    );

    return $metaboxes;
  }

  function pzarc_panel_general_settings($metaboxes)
  {
    $prefix        = '_panels_settings_';
    $sections      = array();
    $sections[ 0 ] = array(
        'show_title' => true,
        'icon_class' => 'icon-large',
        'icon'       => 'el-icon-home',
        'fields'     => array(
//TODO: Make validation check illegal characters
array(
    'id'       => $prefix . 'short-name',
    'title'    => __('Short name', 'pzarchitect') . '<span class="pzarc-required el-icon-star" title="Required"></span>',
    'hint'     => array('content' => __('A short name for this panel layout to identify it.', 'pzarchitect')),
    'type'     => 'text',
    'validate' => 'not_empty'
),
array(
    'title'   => __('Panel Height Type', 'pzarchitect'),
    'id'      => $prefix . 'panel-height-type',
    'cols'    => 6,
    'type'    => 'button_set',
    'default' => 'fluid',
    'options' => array(
        'fluid' => 'Fluid',
        'fixed' => 'Fixed',
    ),
    'hint'    => array('content' => __('Choose whether to set the height of the panels (fixed), or allow them to adjust to the content height (fluid).', 'pzarchitect'))
),
// Hmm? How's this gunna sit with the min-height in templates?
// We will want to use this for image height cropping when behind.

array(
    'title'    => __('Panel Height px', 'pzarchitect'),
    'id'       => $prefix . 'panel-height',
    'type'     => 'dimensions',
    'width'    => false,
    'units'    => 'px',
    'default'  => array('height' => '350'),
    'hint'     => array('content' => __('If using fixed height, set height for the panel.', 'pzarchitect')),
    'required' => array($prefix . 'panel-height-type', 'equals', 'fixed')
),
array(
    'title'    => __('Components Height px', 'pzarchitect'),
    'id'       => $prefix . 'components-height',
    'type'     => 'dimensions',
    'width'    => false,
    'units'    => 'px',
    'default'  => array('height' => '100'),
    'hint'     => array('content' => __('If using fixed height, set height for the components area.', 'pzarchitect')),
    'required' => array($prefix . 'panel-height-type', 'equals', 'fixed')
),
        )
    );
    $metaboxes[ ]  = array(
        'id'         => $prefix . 'general-settings',
        'title'      => 'Panel Settings',
        'post_types' => array('arc-panels'),
        'sections'   => $sections,
        'position'   => 'side',
        'priority'   => 'high',
        'sidebar'    => false

    );

    return $metaboxes;
  }


  /**********
   * Panels Design and Styling
   *********/
  function pzarc_panels_design($metaboxes)
  {
    global $_architect_options;
    $prefix = '_panels_design_';

    $sections    = array();
    $sections[ ] = array(
        'title'      => __('Designer', 'pzarchitect'),
        'show_title' => false,
        'icon_class' => 'icon-large',
        'icon'       => 'el-icon-website',
        'fields'     => array(
            array(
                'title'   => __('Components to show', 'pzarchitect'),
                'id'      => $prefix . 'components-to-show',
                'type'    => 'button_set',
                'multi'   => true,
                'width'   => '100%',
                'default' => array('title', 'excerpt', 'meta1', 'image'),
                'options' => array(
                    'title'   => 'Title',
                    'excerpt' => 'Excerpt',
                    'content' => 'Content',
                    'image'   => 'Image',
                    //                    'caption' => 'Caption',
                    'meta1'   => 'Meta1',
                    'meta2'   => 'Meta2',
                    'meta3'   => 'Meta3',
                    'custom1' => 'Custom 1',
                    'custom2' => 'Custom 2',
                    'custom3' => 'Custom 3',
                    //        'custom4' => 'Custom Field 4',
                ),
                'hint'    => array('content' => __('Select which base components to include in this panel layout.', 'pzarchitect'))
            ),
            array(
                'title'        => 'Panel preview',
                'id'           => $prefix . 'preview',
                'type'         => 'code',
                'readonly'     => false, // Readonly fields can't be written to by code! Weird
                'code'         => draw_panel_layout(),
                'default_show' => false,
                'subtitle'     => 'Drag and drop to reposition and resize components',
                'default'      => json_encode(array(
                                                  'title'   => array('width' => 100, 'show' => true),
                                                  'meta1'   => array('width' => 100, 'show' => true),
                                                  'image'   => array('width' => 25, 'show' => true),
                                                  'excerpt' => array('width' => 75, 'show' => true),
                                                  //                                            'caption' => array('width' => 100, 'show' => false),
                                                  'content' => array('width' => 100, 'show' => false),
                                                  'meta2'   => array('width' => 100, 'show' => false),
                                                  'meta3'   => array('width' => 100, 'show' => false),
                                                  'custom1' => array('width' => 100, 'show' => false),
                                                  'custom2' => array('width' => 100, 'show' => false),
                                                  'custom3' => array('width' => 100, 'show' => false))),
                'hint'         => array('title'   => '',
                                        'content' => __('Drag and drop to sort the order of your elements. <strong>Heights are fluid in panels, so not indicative of how it will look on the page</strong>.', 'pzarchitect'))
            ),
            array(
                'title'   => __('Components area position', 'pzarchitect'),
                'id'      => $prefix . 'components-position',
                'type'    => 'button_set',
                'width'   => '100%',
                'default' => 'top',
                'options' => array(
                    'top'    => 'Top',
                    'bottom' => 'Bottom',
                    'left'   => 'Left',
                    'right'  => 'Right',
                ),
                'hint'    => array('content' => __('Position for all the components as a group. </br>NOTE: If feature is set to align, then components will be below the feature, but not at the bottom of the panel. ', 'pzarchitect')),
                'desc'    => __('Left/right will only take affect when components area width is less than 100%', 'pzarchitect')
            ),
            array(
                'title'         => __('Components area width %', 'pzarchitect'),
                'id'            => $prefix . 'components-widths',
                'type'          => 'slider',
                'default'       => '100',
                'min'           => '0',
                'max'           => '100',
                'step'          => '1',
                'class'         => ' percent',
                'display_value' => 'label',
                'hint'          => array('content' => __('Set the overall width for the components area. Necessary for left or right positioning of sections', 'pzarchitect')),
            ),
            array(
                'title'         => __('Nudge components area up/down %', 'pzarchitect'),
                'id'            => $prefix . 'components-nudge-y',
                'type'          => 'slider',
                'default'       => '0',
                'min'           => '0',
                'max'           => '100',
                'step'          => '1',
                'class'         => ' percent',
                'subtitle'      => 'Not sure this is right - as disappears at 80%. Prob something to do with height here. need fixed?',
                'display_value' => 'label',
                'hint'          => array('content' => __('Enter percent to move the components area up/down. </br>NOTE: These measurements are percentage of the panel.', 'pzarchitect'))
            ),
            array(
                'title'         => __('Nudge components area left/right %', 'pzarchitect'),
                'id'            => $prefix . 'components-nudge-x',
                'type'          => 'slider',
                'default'       => '0',
                'min'           => '0',
                'max'           => '100',
                'step'          => '1',
                'class'         => ' percent',
                'display_value' => 'label',
                'hint'          => array('content' => __('Enter percent to move the components area left/right. </br>NOTE: These measurements are percentage of the panel.', 'pzarchitect'))
            ),
            array(
                'title'   => __('Background Feature Image/Video', 'pzarchitect'),
                'id'      => $prefix . 'background-position',
                'width'   => '100%',
                'type'    => 'button_set',
                'default' => 'none',
                'options' => array(
                    'none'  => 'No image',
                    'fill'  => 'Fill the panel',
                    'align' => 'Align with components area',
                ),
                'hint'    => array('content' => __('Select how to display the featured image or video as the background.', 'pzarchitect'))
            ),
            array(
                'title'   => __('Excerpt/Content thumbnail', 'pzarchitect'),
                'id'      => $prefix . 'thumb-position',
                'width'   => '100%',
                'type'    => 'button_set',
                'default' => 'none',
                'options' => array(
                    'none'  => 'No thumb',
                    'left'  => 'Left',
                    'right' => 'Right',
                ),
                'hint'    => array('content' => __('Set the alignment of the image to show it in the excerpt or the content.', 'pzarchitect'))
            ),
            array(
                'title'         => __('Thumbnail width %', 'pzarchitect'),
                'id'            => $prefix . 'thumb-width',
                'type'          => 'slider',
                'default'       => '25',
                'min'           => '10',
                'max'           => '90',
                'step'          => '1',
                'class'         => ' percent',
                'required'      => array($prefix . 'thumb-position', '!=', 'none'),
                'display_value' => 'label',
                'hint'          => array('content' => __('When you have set the featured image to appear in the content/excerpt, this determines its width.', 'pzarchitect'))
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
                'title'   => __('Title prefix', 'pzarchitect'),
                'id'      => $prefix . 'title-prefix',
                'type'    => 'select',
                'select2' => array('allowClear' => false),
                'default' => 'none',
                'class'   => ' horizontal',
                'options' => array('none'                 => 'None',
                                   'disc'                 => 'Disc',
                                   'circle'               => 'Circle',
                                   'square'               => 'Square',
                                   'thumb'                => 'Thumbnail',
                                   'decimal'              => 'Number',
                                   'decimal-leading-zero' => 'Number with leading zero',
                                   'lower-alpha'          => 'Alpha lower',
                                   'upper-alpha'          => 'Alpha upper',
                                   'lower-roman'          => 'Roman lower',
                                   'upper-roman'          => 'Roman upper',
                                   'lower-greek'          => 'Greek lower',
                                   'upper-greek'          => 'Greek upper',
                                   'lower-latin'          => 'Latin lower',
                                   'upper-latin'          => 'Latin upper',
                                   'armenian'             => 'Armenian',
                                   'georgian'             => 'Georgian',
                ),
            ),
            array(
                'title'         => __('Title thumbnail width', 'pzarchitect'),
                'id'            => $prefix . 'title-thumb-width',
                'type'          => 'spinner',
                'default'       => '32',
                'min'           => '8',
                'max'           => '1000',
                'step'          => '1',
                'display_value' => 'label',
                'required'      => array('_panels_design_title-prefix', '=', 'thumb'),
                'hint'          => array('content' => __('', 'pzarchitect')),
            ),
            array(
                'title'    => __('Bullet separator', 'pzarchitect'),
                'id'       => $prefix . 'title-bullet-separator',
                'type'     => 'text',
                'class'    => 'textbox-small',
                'default'  => '. ',
                'required' => array('_panels_design_title-prefix', '!=', 'thumb'),
            ),
            array(
                'title'   => __('Link titles', 'pzarchitect'),
                'id'      => $prefix . 'link-titles',
                //            'cols'    => 4,
                'type'    => 'switch',
                'on'      => 'Yes',
                'off'     => 'No',
                'default' => true

                /// can't set defaults on checkboxes!
            ),
        )
    );

    /**
     * META
     */
    $sections[ ] = array(
        'title'      => 'Meta',
        'show_title' => false,
        'icon_class' => 'icon-large',
        'icon'       => 'el-icon-calendar',
        'desc'       => __('Available tags are %author%, %email%,%date%,%categories%,%tags, %commentslink%,%editlink%.', 'pzarchitect') . '<br>' .
            __('Note: Enclose any author related text in // to hide it when using excluded authors.', 'pzarchitect') . '<br>' .
            __('Note: The email address will be encoded to prevent automated harvesting by spammers.', 'pzarchitect'),
        'fields'     => array(
          // ======================================
          // META
          // ======================================
          array(
              'title'   => __('Meta1 config', 'pzarchitect'),
              'id'      => $prefix . 'meta1-config',
              'type'    => 'textarea',
              'cols'    => 4,
              'rows'    => 2,
              'default' => '%date% //by// %author%',
          ),
          array(
              'title'   => __('Meta2 config', 'pzarchitect'),
              'id'      => $prefix . 'meta2-config',
              'type'    => 'textarea',
              'rows'    => 2,
              'default' => 'Categories: %categories%   Tags: %tags%',
          ),
          array(
              'title'   => __('Meta3 config', 'pzarchitect'),
              'id'      => $prefix . 'meta3-config',
              'type'    => 'textarea',
              'rows'    => 2,
              'default' => '%commentslink%   %editlink%',
          ),
          array(
              'id'      => $prefix . 'meta-date-format',
              'title'   => 'Date format',
              'type'    => 'text',
              'default' => 'l, F j, Y g:i a',
              'desc'    => __('See here for information on <a href="http://codex.wordpress.org/Formatting_Date_and_Time" target=_blank>formatting date and time</a>', 'pzarchitect'),
              'hint'    => array('title'   => '',
                                 'content' => 'See here for information on <a href="http://codex.wordpress.org/Formatting_Date_and_Time" target=_blank>formatting date and time</a>')
          ),
          array(
              'title'    => __('Excluded authors', 'pzarchitect'),
              'id'       => $prefix . 'excluded-authors',
              'type'     => 'select',
              'multi'    => true,
              'data'     => 'callback',
              //TODO: Findout how to pass parameters
              'args'     => array('pzarc_get_authors', array(false, 0)),
              'default'  => '',
              'subtitle' => __('Select any authors here you want to exclude from showing when the %author% or %email% tag is used.', 'pzarchitect')
          ),
        )
    );


    /**********
     * Content
     *********/
    // EXCERPTS
    $sections[ ] = array(
        'title'      => __('Content/Excerpts', 'pzarchitect'),
        'icon_class' => 'icon-large',
        'icon'       => 'el-icon-align-left',
        'fields'     => array(

            array(
                'id'    => $prefix . 'content-responsive-heading',
                'title' => __('Responsive', 'pzarchitect'),
                'type'  => 'section',
                'class' => 'heading',
            ),
            array(
                'id'       => $prefix . 'responsive-hide-content',
                'title'    => __('Hide Content at breakpoint', 'pzarchitect'),
                'type'     => 'select',
                'options'  => array(
                    'none' => 'None',
                    '3'    => 'Small screen ' . $_architect_options[ 'architect_breakpoint_3' ][ 'width' ],
                    '2'    => 'Medium screen ' . $_architect_options[ 'architect_breakpoint_2' ][ 'width' ],
                    '1'    => 'Wide screen ' . $_architect_options[ 'architect_breakpoint_1' ][ 'width' ]
                ),
                'default'  => 'none',
                'subtitle' => __('Breakpoints can be changed in Architect Options', 'pzachitect')
            ),
            array(
                'id'    => $prefix . 'excerpt-heading',
                'title' => __('Excerpts', 'pzarchitect'),
                'type'  => 'section',
                'class' => 'heading',
            ),
            array(
                'id'      => $prefix . 'excerpts-word-count',
                'title'   => __('Excerpt length (words)', 'pzarchitect'),
                'type'    => 'spinner',
                'default' => 55,
                'min'     => '1',
                'max'     => '9999',
                'step'    => '1',
            ),
            array(
                'title'   => __('Truncation indicator', 'pzarchitect'),
                'id'      => $prefix . 'readmore-truncation-indicator',
                'type'    => 'text',
                'class'   => 'textbox-small',
                'default' => '[...]',
            ),
            array(
                'title'   => __('Read More', 'pzarchitect'),
                'id'      => $prefix . 'readmore-text',
                'type'    => 'text',
                'class'   => 'textbox-medium',
                'default' => __('Read more', 'pzarchitect'),
            ),
            array(
                'title'    => __('Maximize content', 'pzarchitect'),
                'id'       => $prefix . 'maximize-content',
                'type'     => 'switch',
                'on'       => __('Yes', 'pzarchitect'),
                'off'      => __('No', 'pzarchitect'),
                'default'  => true,
                'subtitle' => __('Make excerpt or content 100% width if no featured image', 'pzarchitect')
            ),
        )


    );
    /**
     * IMAGES
     */
    $sections[ ] = array(
        'title'      => 'Images',
        'icon_class' => 'icon-large',
        'icon'       => 'el-icon-picture',
        'fields'     => array(

          ///
          // IMAGE
          ///

          array(
              'id'    => $prefix . 'featured-image-heading',
              'title' => 'Featured Image',
              'type'  => 'section',
              'class' => 'heading',
              'hint'  => array('content' => 'Left and right margins are included in the image width in the designer. e.g if Image width is 25% and right margin is 3%, image width will be adjusted to 22%')
          ),
          array(
              'id'             => $prefix . 'image-spacing',
              'type'           => 'spacing',
              //                'output'         => array('.site-header'),
              'mode'           => 'margin',
              'units'          => '%',
              'units_extended' => 'false',
              'title'          => __('Margins (%)', 'pzarchitect'),
              'default'        => array(
                  'margin-top'    => '1%',
                  'margin-right'  => '1%',
                  'margin-bottom' => '1%',
                  'margin-left'   => '0',
                  'units'         => '%',
              )
          ),
          array(
              'title'    => __('Centre image', 'pzarchitect'),
              'id'       => $prefix . 'centre-image',
              'type'     => 'switch',
              'on'       => 'Yes',
              'off'      => 'No',
              'default'  => false,
              'subtitle' => 'Centres the image horizontally. It is best to display it on its own row, and the content to be 100% wide.'
          ),
          array(
              'title'    => __('Link image', 'pzarchitect'),
              'id'       => $prefix . 'link-image',
              'type'     => 'switch',
              'on'       => 'Yes',
              'off'      => 'No',
              'default'  => true,
              'subtitle' => 'Makes the image link to the post/page'
          ),
          array(
              'title'   => __('Image Captions', 'pzarchitect'),
              'id'      => $prefix . 'image-captions',
              'type'    => 'switch',
              'on'      => 'Yes',
              'off'     => 'No',
              'default' => false,
          ),
          array(
              'id'      => $prefix . 'image-max-dimensions',
              'title'   => 'Maximum image dimensions',
              'type'    => 'dimensions',
              'units'   => 'px',
              'default' => array('width' => '300', 'height' => '350'),
          ),
          array(
              'id'    => $prefix . 'image-processing-heading',
              'title' => __('Image processing', 'pzarchitect'),
              'type'  => 'section',
              'class' => 'heading',
          ),
          array(
              'title'    => __('Convert to JPEG', 'pzarchitect'),
              'id'       => $prefix . 'image-to-jpeg',
              'type'     => 'switch',
              'default'  => false,
              'cols'     => 3,
              'subtitle' => 'Convert all resized images to JPEG format.',
              'hint'     => array('content' => 'Convert all resized images to JPEG format - usually will be smaller.')
          ),
          array(
              'title'   => __('Image resizing method', 'pzarchitect'),
              'id'      => $prefix . 'image-resizing',
              'type'    => 'select',
              'select2' => array('allowClear' => false),
              'default' => 'crop',
              'options' => array(
                  'crop'          => 'Crop width and height to fit',
                  'exact'         => 'Stretch to width and height (Warning: Can distort image)',
                  'portrait'      => 'Crop width, match height',
                  'landscape'     => 'Match width, crop height',
                  'auto'          => 'Fit within width and height',
                  'scaletowidth'  => 'Scale to resize width',
                  'scaletoheight' => 'Scale to resize height',
                  'none'          => 'No cropping, use original. (Use with care!)'
              ),
              'hint'    => array('content' => 'Choose how you want the image resized in respect of its original width and height to the Image Max Height and Width.'),
          ),
          array(
              'id'      => $prefix . 'image-bgcolour',
              'title'   => 'Image background colour',
              'type'    => 'spectrum',
              'default' => '#ffffff',
              'hint'    => array('content' => 'If the cropped image  doesn\'t fill the resize area, fill the space with this colour.')
          ),
          array(
              'id'            => $prefix . 'image-quality',
              'title'         => 'Image quality',
              'type'          => 'slider',
              'display_value' => 'label',
              'default'       => '75',
              'min'           => '20',
              'max'           => '100',
              'step'          => '1',
              'units'         => '%',
              'hint'          => array('content' => 'Quality to use when processing images')

          ),
          array(
              'id'       => $prefix . 'image-bgimage-heading',
              'title'    => 'Background images',
              'type'     => 'section',
              'class'    => 'heading',
              'subtitle' => 'Background images can be a bit trickier to work with responsively as they might not fill the background on all devices. Consider the probable aspect ratio of the panel at each breakpoint when setting the image dimensions. What may be landscape on a desktop, could be portrait on a smartphone.'
          ),
          array(
              'title'    => __('Maximum dimensions BP1 : ', 'pzarchitect') . $_architect_options[ 'architect_breakpoint_1' ][ 'width' ],
              'id'       => $prefix . 'background-image-max',
              'type'     => 'dimensions',
              'units'    => 'px',
              'default'  => array('width' => '300', 'height' => '350'),
              'required' => array($prefix . 'background-position', '!=', 'none'),
              'subtitle' => __('This should be larger than the expected maximum viewing size at this breakpoint to ensure best responsive behaviour', 'pzarchitect')
          ),
          array(
              'title'    => __('Maximum dimensions BP2 : ', 'pzarchitect') . $_architect_options[ 'architect_breakpoint_2' ][ 'width' ],
              'id'       => $prefix . 'background-image-max-bp2',
              'type'     => 'dimensions',
              'units'    => 'px',
              'default'  => array('width' => '600', 'height' => '400'),
              'required' => array($prefix . 'background-position', '!=', 'none'),
          ),
          array(
              'title'    => __('Maximum dimensions BP3 : ', 'pzarchitect') . $_architect_options[ 'architect_breakpoint_3' ][ 'width' ],
              'id'       => $prefix . 'background-image-max-bp3',
              'type'     => 'dimensions',
              'units'    => 'px',
              'default'  => array('width' => '200', 'height' => '300'),
              'required' => array($prefix . 'background-position', '!=', 'none'),
          ),
          array(
              'title'   => __('Effect on resize', 'pzarchitect'),
              'id'      => $prefix . 'background-image-resize',
              'type'    => 'button_set',
              'options' => array(
                  'trim'  => 'Trim horizontally, retain height',
                  'scale' => 'Scale Vertically & Horizontally'
              ),
              'default' => 'trim',
          ),
        )
    );

    for ($i = 1; $i <= 3; $i++) {
      $sections[ ] = array(
          'title'      => 'Custom Field '.$i,
          'icon_class' => 'icon-large',
          'icon'       => 'el-icon-wrench',
          'desc'=>'Note: Only fields with content will show.',
          'fields'     => array(
              array(
                  'title'   => __('Field name', 'pzarchitect'),
                  'id'      => $prefix . 'cfield-'.$i,
                  'type'    => 'select',
                  'data'     => 'callback',
                  'args'    => array('pzarc_get_custom_fields'),

              ),
          )
      );
    }
    $sections[ ] = array(
        'title'      => 'Using panels',
        'icon_class' => 'icon-large',
        'icon'       => 'el-icon-info-sign',
        'fields'     => array(

            array(
                'title' => __('How to use panels', 'pzarchitect'),
                'id'    => $prefix . 'help-panels-use',
                'type'  => 'info',
                'class' => 'plain',
                'desc'  => '<p>To use a panel, you need to select it to be used by a Blueprint section</p>
                <img src="' . PZARC_PLUGIN_URL . '/documentation/assets/images/arc-using-panels.jpg" style="max-width:100%;">
                <p>The great thing then is, any panel can be re-used as often as you like.</p>

            ')
        )
    );

    $sections[ ] = array(
        'title'      => 'Help',
        'icon_class' => 'icon-large',
        'icon'       => 'el-icon-question-sign',
        'fields'     => array(
            array(
                'title' => __('Design', 'pzarchitect'),
                'id'    => $prefix . 'panels-help-design',
                'type'  => 'info',
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

    // Create the metaboxes


    $metaboxes[ ] = array(
        'id'         => 'panels-design',
        'title'      => 'Panels Design',
        'post_types' => array('arc-panels'),
        'sections'   => $sections,
        'position'   => 'normal',
        'priority'   => 'high',
        'sidebar'    => false

    );

    return $metaboxes;

  }


  function pzarc_panels_styling($metaboxes)
  {

//  $screen = get_current_screen();
//  if ($screen->ID != 'xx') {return;}
    $defaults = get_option('_architect');
//var_dump($defaults);
    $prefix = '_panels_styling_';

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

    $sections[ ] = array(
        'title'      => 'Overall',
        'show_title' => false,
        'icon_class' => 'icon-large',
        'icon'       => 'el-icon-brush',
        'fields'     => pzarc_fields(

            array(
                'title'    => __('Panels', 'pzarchitect'),
                'id'       => $prefix . 'panels-section',
                'type'     => 'section',
                'class'    => 'heading',
                'subtitle' => 'Class: .pzarc-panel',
                'hint'     => array('content' => 'Class: .pzarc-panel'),
            ),
            pzarc_redux_bg($prefix . 'panels-background', pzarc_to_array('%%%', $defaults[ $optprefix . 'panels-selectors' ]), $defaults[ $optprefix . 'panels-background' ]),
            pzarc_redux_padding($prefix . 'panels-padding', pzarc_to_array('%%%', $defaults[ $optprefix . 'panels-selectors' ]), $defaults[ $optprefix . 'panels-padding' ]),
            pzarc_redux_borders($prefix . 'panels-borders', pzarc_to_array('%%%', $defaults[ $optprefix . 'panels-selectors' ]), $defaults[ $optprefix . 'panels-borders' ]),
            array(
                'title'    => __('Components group', 'pzarchitect'),
                'id'       => $prefix . 'components-section',
                'type'     => 'section',
                'class'    => 'heading',
                'hint'     => array('content' => 'Class: .pzarc_components'),
                'subtitle' => 'Class: .pzarc_components',
            ),
            pzarc_redux_bg($prefix . 'components-background', array('.pzarc_components'), $defaults[ $optprefix . 'components-background' ]),
            pzarc_redux_padding($prefix . 'components-padding', array('.pzarc_components'), $defaults[ $optprefix . 'components-padding' ]),
            pzarc_redux_borders($prefix . 'components-borders', array('.pzarc_components'), $defaults[ $optprefix . 'components-borders' ]),
            array(
                'title'    => __('Entry', 'pzarchitect'),
                'id'       => $prefix . 'hentry-section',
                'type'     => 'section',
                'class'    => 'heading',
                'hint'     => array('content' => 'Class: .hentry'),
                'subtitle' => 'Class: .hentry'
            ),
            pzarc_redux_bg($prefix . 'hentry-background', array('.hentry'), $defaults[ $optprefix . 'hentry-background' ]),
            pzarc_redux_padding($prefix . 'hentry-padding', array('.hentry'), $defaults[ $optprefix . 'hentry-padding' ]),
            pzarc_redux_margin($prefix . 'hentry-margin', array('.hentry'), $defaults[ $optprefix . 'hentry-margin' ]),
            pzarc_redux_borders($prefix . 'hentry-borders', array('.hentry'), $defaults[ $optprefix . 'hentry-borders' ])
        )
    );
//    $sections[ ] = array(
//        'title'      => 'Test',
//        'show_title' => false,
//        'icon_class' => 'icon-large',
//        'icon'       => 'el-icon-idea',
//        'fields'     => array(
//            array(
//                'title'        => 'Title',
//                'id'           => $prefix . 'entry-title-css',
//                'type'         => 'code',
//                'readonly'     => false,
//                // Readonly fields can't be written to by code! Weird
//                'code'         => draw_css_styles_editor('.entry-title'),
//                'default_show' => false,
//                //                'default'      => json_encode(array(
//                //                                                  'title'   => array('width' => 100, 'show' => true),
//                //                                                  'meta1'   => array('width' => 100, 'show' => true),
//                //                                                  'image'   => array('width' => 25, 'show' => true),
//                //                                                  'excerpt' => array('width' => 75, 'show' => true),
//                //                                                  //                                            'caption' => array('width' => 100, 'show' => false),
//                //                                                  'content' => array('width' => 100, 'show' => false),
//                //                                                  'meta2'   => array('width' => 100, 'show' => false),
//                //                                                  'meta3'   => array('width' => 100, 'show' => false),
//                //                                                  'custom1' => array('width' => 100, 'show' => false),
//                //                                                  'custom2' => array('width' => 100, 'show' => false),
//                //                                                  'custom3' => array('width' => 100, 'show' => false))),
//            ),
//
//        )
//    );

    $sections[ ] = array(
        'title'      => 'Titles',
        'show_title' => false,
        'icon_class' => 'icon-large',
        'icon'       => 'el-icon-font',
        'desc'       => 'Class: .pzarc_entry-title',
        'fields'     => pzarc_fields(
            pzarc_redux_font($prefix . 'entry-title-font', array('.entry-title'), $defaults[ $optprefix . 'entry-title-font' ]),
            pzarc_redux_bg($prefix . 'entry-title-font-background', array('.entry-title'), $defaults[ $optprefix . 'entry-title-font-background' ]),
            pzarc_redux_padding($prefix . 'entry-title-font-padding', array('.entry-title'), $defaults[ $optprefix . 'entry-title-font-padding' ]),
            pzarc_redux_margin($prefix . 'entry-title-font-margin', array('.entry-title'), $defaults[ $optprefix . 'entry-title-font-margin' ]),
            pzarc_redux_borders($prefix . 'entry-title-borders', array('.entry-title'), $defaults[ $optprefix . 'entry-title-borders' ]),
            pzarc_redux_links($prefix . 'entry-title-font-links', array('.entry-title a'), $defaults[ $optprefix . 'entry-title-font-links' ])
//            array(
//                'title'   => __('Other declarations', 'pzarchitect'),
//                'id'      => $prefix . 'text-other-css',
//                'type'    => 'ace_editor',
//                'default' => ".entry-title {\n\n}",
//                'mode'    => 'css',
//                'hint'    => array('content' => 'Class: .entry-title'),
//            )
        ),
    );

    $sections[ ]  = array(
        'title'      => 'Meta',
        'show_title' => false,
        'icon_class' => 'icon-large',
        'icon'       => 'el-icon-calendar',
        'desc'       => 'Class: .pzarc_entry_meta',
        'fields'     => pzarc_fields(
            pzarc_redux_font($prefix . 'entry-meta-font', array('.entry-meta'), $defaults[ $optprefix . 'entry-meta-font' ]),
            pzarc_redux_bg($prefix . 'entry-meta-font-background', array('.entry-meta'), $defaults[ $optprefix . 'entry-meta-font-background' ]),
            pzarc_redux_padding($prefix . 'entry-meta-font-padding', array('.entry-meta'), $defaults[ $optprefix . 'entry-meta-font-padding' ]),
            pzarc_redux_links($prefix . 'entry-meta-font-links', array('.entry-meta a'), $defaults[ $optprefix . 'entry-meta-font-links' ])
        )
    );
    $sections[ ]  = array(
        'title'      => 'Content',
        'show_title' => false,
        'icon_class' => 'icon-large',
        'icon'       => 'el-icon-align-left',
        'fields'     => pzarc_fields(
            pzarc_redux_font($prefix . 'entry-content-font', array('.entry-content'), $defaults[ $optprefix . 'entry-content-font' ]),
            pzarc_redux_bg($prefix . 'entry-content-font-background', array('.entry-content'), $defaults[ $optprefix . 'entry-content-font-background' ]),
            pzarc_redux_padding($prefix . 'entry-content-font-padding', array('.entry-content'), $defaults[ $optprefix . 'entry-content-font-padding' ]),
            pzarc_redux_links($prefix . 'entry-content-font-links', array('.entry-content a'), $defaults[ $optprefix . 'entry-content-font-links' ]),
            array(
                'title' => __('Read more', 'pzarchitect'),
                'id'    => $prefix . 'entry-readmore',
                'type'  => 'section',
                'class' => 'heading',
                'hint'  => array('content' => 'Class: a.pzarc_readmore'),
            ),
            pzarc_redux_font($prefix . 'entry-readmore-font', array('.readmore'), $defaults[ $optprefix . 'entry-readmore-font' ]),
            pzarc_redux_bg($prefix . 'entry-readmore-font-background', array('.readmore'), $defaults[ $optprefix . 'entry-readmore-font-background' ]),
            pzarc_redux_padding($prefix . 'entry-readmore-font-padding', array('.readmore'), $defaults[ $optprefix . 'entry-readmore-font-padding' ]),
            pzarc_redux_links($prefix . 'entry-readmore-font-links', array('a.readmore'), $defaults[ $optprefix . 'entry-readmore-font-links' ])
        )
    );
    $sections[ ]  = array(
        'title'      => 'Entry Featured image',
        'show_title' => false,
        'icon_class' => 'icon-large',
        'icon'       => 'el-icon-picture',
        'fields'     => pzarc_fields(
            array(
                'title' => __('Image', 'pzarchitect'),
                'id'    => $prefix . 'entry-image',
                'type'  => 'section',
                'class' => 'heading',
                //          'default' => $defaults[ $optprefix . 'image_defaults_entry-image-defaults' ],
                'hint'  => array('content' => 'Class: figure.entry-thumbnail'),
                //     'hint'    => __('Format the entry featured image', 'pzarchitect')
            ),
            array(
                'title'                 => __('Background', 'pzarchitect'),
                'id'                    => $prefix . 'entry-image-background',
                'type'                  => 'spectrum',
                'background-image'      => false,
                'background-repeat'     => false,
                'background-size'       => false,
                'background-attachment' => false,
                'background-position'   => false,
                'preview'               => false,
                'output'                => array('.pzarc_entry_featured_image'),
                'default'               => $defaults[ $optprefix . 'entry-image-background' ]

                //    'default' => $defaults[ $optprefix . 'image_defaults_entry-image-caption-defaults' ],
            ),
            array(
                'title'   => __('Border', 'pzarchitect'),
                'id'      => $prefix . 'entry-image-borders',
                'type'    => 'border',
                'all'     => false,
                'output'  => array('.pzarc_entry_featured_image'),
                'default' => $defaults[ $optprefix . 'entry-image-borders' ]

                //    'default' => $defaults[ $optprefix . 'image_defaults_entry-image-caption-defaults' ],
            ),
            array(
                'title'   => __('Padding', 'pzarchitect'),
                'id'      => $prefix . 'entry-image-padding',
                'mode'    => 'padding',
                'type'    => 'spacing',
                'units'   => array('px', '%'),
                'default' => $defaults[ $optprefix . 'entry-image-padding' ]
                //    'default' => $defaults[ $optprefix . 'image_defaults_entry-image-caption-defaults' ],
            ),
            array(
                'title' => __('Caption', 'pzarchitect'),
                'id'    => $prefix . 'entry-image-caption',
                'type'  => 'section',
                'class' => 'heading',
                //    'default' => $defaults[ $optprefix . 'image_defaults_entry-image-caption-defaults' ],
            ),
            pzarc_redux_font($prefix . 'entry-image-caption-font', array('figure.entry-thumbnail span.caption'), $defaults[ $optprefix . 'entry-image-caption-font' ]),
            pzarc_redux_bg($prefix . 'entry-image-caption-font-background', array('figure.entry-thumbnail span.caption'), $defaults[ $optprefix . 'entry-image-caption-font-background' ]),
//            pzarc_redux_margin($prefix . 'entry-image-caption-font-margin', array('figure.entry-thumbnail span.caption'), $defaults[ $optprefix . 'entry-image-caption-font-margin' ]),
            pzarc_redux_padding($prefix . 'entry-image-caption-font-padding', array('figure.entry-thumbnail span.caption'), $defaults[ $optprefix . 'entry-image-caption-font-margin' ])
        )


    );
    $sections[ ]  = array(
        'id'         => 'custom-css',
        'title'      => 'Custom CSS',
        'icon_class' => 'icon-large',
        'icon'       => 'el-icon-wrench',
        'fields'     => array(
            array(
                'id'       => $prefix . 'custom-css',
                'type'     => 'ace_editor',
                'title'    => __('Custom CSS', 'pzarchitect'),
                'mode'     => 'css',
                'default'  => $defaults[ $optprefix . 'custom-css' ],
                'subtitle' => 'To apply to this panel design specifically, prepend with class .pzarc-panel_shortname where shortname is the shortname of this panel. Note: underscore before shortname.'
            ),
        )
    );
    $sections[ ]  = array(
        'id'         => 'styling-help',
        'title'      => 'Help',
        'icon_class' => 'icon-large',
        'icon'       => 'el-icon-question-sign',
        'fields'     => array(

            array(
                'title' => __('Styling', 'pzarchitect'),
                'id'    => $prefix . 'panels-help-styling',
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
    $metaboxes[ ] = array(
        'id'         => 'panels-styling',
        'title'      => 'Panels Styling',
        'post_types' => array('arc-panels'),
        'sections'   => $sections,
        'position'   => 'normal',
        'priority'   => 'default',
        'sidebar'    => false

    );

    //pzdebug($metaboxes);


    return $metaboxes;
  }

  /**
   * [draw_panel_layout description]
   * @return [type] [description]
   */
  function draw_panel_layout()
  {
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
        <span class="pzarc-draggable pzarc-draggable-content" title="Full post content" data-idcode=content ><span><img src="' . PZARC_PLUGIN_APP_URL . '/shared/assets/images/sample-image.jpg" style="max-width:20%;padding:2px;" class="pzarc-align none"><img src="' . PZARC_PLUGIN_APP_URL . '/shared/assets/images/fireworks.jpg" style="max-width:30%;float:left;padding:5px;">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam quis justo erat. <ul><li>&nbsp;•&nbsp;Cras semper sem hendrerit</li><li>&nbsp;•&nbsp;Tortor porta at auctor</li></ul><strong>Lacus consequat</strong><p>Pellentesque pulvinar iaculis tellus in blandit. Suspendisse rhoncus, magna vel eleifend cursus, turpis odio molestie urna, quis posuere eros risus quis neque. </p></span></span>
        <span class="pzarc-draggable pzarc-draggable-image" title="Featured image" data-idcode=image style="max-height: 100px; overflow: hidden;"><span><img src="' . PZARC_PLUGIN_APP_URL . '/shared/assets/images/sample-image.jpg" style="max-width:100%;"></span></span>
        <span class="pzarc-draggable pzarc-draggable-meta2 pzarc-draggable-meta" title="Meta info 2" data-idcode=meta2 ><span>Categories - News, Sport</span></span>
        <span class="pzarc-draggable pzarc-draggable-meta3 pzarc-draggable-meta" title="Meta info 3" data-idcode=meta3 ><span>Comments: 27</span></span>
        <span class="pzarc-draggable pzarc-draggable-custom1 pzarc-draggable-meta" title="Custom field 1" data-idcode=custom1 ><span>Custom content 1</span></span>
        <span class="pzarc-draggable pzarc-draggable-custom2 pzarc-draggable-meta" title="Custom field 2" data-idcode=custom2 ><span>Custom content 2</span></span>
        <span class="pzarc-draggable pzarc-draggable-custom3 pzarc-draggable-meta" title="Custom field 3" data-idcode=custom3 ><span>Custom content 3</span></span>
      </div>
	  </div>
	  <p class="pzarc-states ">Loading</p>
	  <p class="howto "><strong style="color:#d00;">This is an example only and thus only a <span style="border-bottom: 3px double;">general guide</span> to how the panels will look.</strong></p>
	</div>
	<div class="plugin_url" style="display:none;">' . PZARC_PLUGIN_APP_URL . '</div>
	';

    return $return_html;
  }

//
  function draw_css_styles_editor($class)
  {
    $return_html = '
    <div class="pzarc-css-styles-editor">
    <p>Font  Weight  Size  Decoration  Styles  Color</p>
    </div>
    ';

    // if you pass a class, how you gunna make it differentiate different panel?!


    return $return_html;
  }