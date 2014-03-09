<?php

//Panels may need to include Content because of custom fields!!! OOps! But content has to be over-arching! So I guess, cells will need to know the content type and its fields. So lets make Content Criteria again. :S

  /**
   * Class pzarc_Panel_Layouts
   */
  $redux_opt_name = '_architect';
  $show_hints = true;

  class pzarc_Panels_Layouts
  {


    /**
     * [__construct description]
     */
    function __construct()
    {
      add_action('init', array($this, 'create_layouts_post_type'));
      // This overrides the one in the parent class

      if (is_admin())
      {
        //     require_once PZARC_PLUGIN_PATH . 'includes/pzarc-custom-field-types.php';

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
      if ('arc-panels' == $screen->id)
      {
        //  var_dump($screen->id);

        wp_enqueue_script('jquery-ui-draggable');
        wp_enqueue_script('jquery-ui-droppable');
        wp_enqueue_script('jquery-ui-sortable');
        wp_enqueue_script('jquery-ui-resizable');

        wp_enqueue_style('pzarc-admin-panels-css', PZARC_PLUGIN_URL . 'admin/css/arc-admin-panels.css');

        wp_enqueue_script('jquery-pzarc-metaboxes-panels', PZARC_PLUGIN_URL . 'admin/js/arc-metaboxes-panels.js', array('jquery'));
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
//                    'pzarc_set_name'   => __('Set name', 'pzsp'),
'pzarc_short_name' => __('Short name', 'pzsp'),
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
      // thiswont work coz
      switch ($column)
      {
        case 'pzarc_short_name':
          $metaboxes = get_post_meta($post_id, '_architect', true);
          echo $metaboxes[ 'panels_settings_short-name' ];
          break;
//      case 'pzarc_set_name':
//        echo get_post_meta($post_id, 'pzarc_layout-set-name', true);
//        break;
      }
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


  add_action("redux/metaboxes/{$redux_opt_name}/boxes", 'pzarc_panel_tabs');
  function pzarc_panel_tabs($metaboxes)
  {
    $prefix        = 'panels_tabs_';
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
                    'panels-design' => '<span class="icon-large el-icon-website"></span> Design',
                    'panels-styling' => '<span class="icon-large el-icon-brush"></span> Styling'),
            ),
        )
    );
    $metaboxes[ ]  = array(
        'id'         => $prefix . 'panels',
        'title'      => 'Show settings for:',
        'post_types' => array('arc-panels'),
        'sections'   => $sections,
        'position'   => 'normal',
        'priority'   => 'high',
        'sidebar'    => false

    );

    return $metaboxes;
  }

  add_action("redux/metaboxes/{$redux_opt_name}/boxes", 'pzarc_panel_general_settings');
  function pzarc_panel_general_settings($metaboxes)
  {
    $prefix       = 'panels_settings_';
    $sections     = array();
    $sections[0 ]  = array(
        'show_title' => true,
        'icon_class' => 'icon-large',
        'icon'       => 'el-icon-home',
        'fields'     => array(
            array(
                'id'       => $prefix . 'short-name',
                'title'    => __('Short name', 'pzarc'),
                'hint' => array('content'=> __('A short name for this panel layout to identify it.', 'pzarc')),
                'type'     => 'text',
            ),
            array(
                'title'    => __('Panel Height Type', 'pzarc'),
                'id'       => $prefix . 'panel-height-type',
                'cols'     => 6,
                'type'     => 'button_set',
                'default'  => 'fluid',
                'options'  => array(
                    'fluid' => 'Fluid',
                    'fixed' => 'Fixed',
                ),
                'hint' => array('content'=>__('Choose whether to set the height of the panels (fixed), or allow them to adjust to the content height (fluid).', 'pzarc'))
            ),
            // Hmm? How's this gunna sit with the min-height in templates?
            // We will want to use this for image height cropping when behind.

            array(
                'title'    => __('Panel Height px', 'pzarc'),
                'id'       => $prefix . 'panel-height',
                'type'     => 'spinner',
                'default'  => '350',
                'min'      => '0',
                'max'      => '9999',
                'step'     => '1',
                'class'    => ' pixels',
                'hint' => array('content'=>__('If using fixed height, set height for the panel.', 'pzarc')),
                'required' => array($prefix . 'panel-height-type', 'equals', 'fixed')
            ),
            array(
                'title'    => __('Components Height px', 'pzarc'),
                'id'       => $prefix . 'components-height',
                'type'     => 'spinner',
                'width'    => '100%',
                'default'  => '100',
                'min'      => '0',
                'max'      => '100',
                'step'     => '1',
                'class'    => ' pixels',
                'hint' => array('content'=>__('If using fixed height, set height for the components area.', 'pzarc')),
                'required' => array($prefix . 'panel-height-type', 'equals', 'fixed')
            ),
        )
    );
    $metaboxes[ ] = array(
        'id'         => $prefix . 'general-settings',
        'title'      => 'General Settings',
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
  add_action("redux/metaboxes/{$redux_opt_name}/boxes", 'pzarc_panels_design');
  function pzarc_panels_design($metaboxes)
  {
    $prefix = 'panels_design_';

    $sections    = array();
    $sections[ ] = array(
        'title'      => __('Designer', 'pzarc'),
        'show_title' => false,
        'icon_class' => 'icon-large',
        'icon'       => 'el-icon-website',
        'fields'     => array(
            array(
                'title'    => 'Panel preview',
                'id'       => $prefix . 'preview',
                'width'    => 'auto',
                'type'     => 'code',
                'readonly' => false, // Readonly fields can't be written to by code! Weird
                'code'     => draw_panel_layout(),
                'default_show' => false,
                'default'  => json_encode(array(
                                              'title'   => array('width' => 100, 'show' => true),
                                              'meta1'   => array('width' => 100, 'show' => true),
                                              'image'   => array('width' => 25, 'show' => true),
                                              'excerpt' => array('width' => 75, 'show' => true),
                                              'caption' => array('width' => 100, 'show' => false),
                                              'content' => array('width' => 100, 'show' => false),
                                              'meta2'   => array('width' => 100, 'show' => false),
                                              'meta3'   => array('width' => 100, 'show' => false),
                                              'custom1' => array('width' => 100, 'show' => false),
                                              'custom2' => array('width' => 100, 'show' => false),
                                              'custom3' => array('width' => 100, 'show' => false))),
                'hint' => array('title'=>'','content'=>__('Drag and drop to sort the order of your elements. <strong>Heights are fluid in panels, so not indicative of how it will look on the page</strong>.', 'pzarc'))
            ),
            // OMG!! The multiselect is working and I don't know why!!
            array(
                'title'    => __('Components to show', 'pzarc'),
                'id'       => $prefix . 'components-to-show',
                'type'     => 'select',
                'multi'    => true,
                'width'    => '100%',
                'default'  => array('title', 'excerpt', 'meta1', 'image'),
                'options'  => array(
                    'title'   => 'Title',
                    'excerpt' => 'Excerpt',
                    'content' => 'Content',
                    'image'   => 'Image',
                    'caption' => 'Image Caption',
                    'meta1'   => 'Meta1',
                    'meta2'   => 'Meta2',
                    'meta3'   => 'Meta3',
                    'custom1' => 'Custom Field 1',
                    'custom2' => 'Custom Field 2',
                    'custom3' => 'Custom Field 3',
                    //        'custom4' => 'Custom Field 4',
                ),
                'hint' => array('content'=>__('Select which base components to include in this panel layout.', 'pzarc'))
            ),
            array(
                'title'    => __('Excerpt/Content featured image', 'pzarc'),
                'id'       => $prefix . 'thumb-position',
                'width'    => '100%',
                'type'     => 'button_set',
                'default'  => 'none',
                'options'  => array(
                    'none'  => 'No image',
                    'left'  => 'Left',
                    'right' => 'Right',
                ),
                'hint' => array('content'=>__('Set the alignment of the image to show it in the excerpt or the content. This will use the image settings', 'pzarc'))
            ),
            array(
                'title'    => __('Background Feature Image/Video', 'pzarc'),
                'id'       => $prefix . 'background-position',
                'width'    => '100%',
                'type'     => 'button_set',
                'default'  => 'none',
                'options'  => array(
                    'none'  => 'No image',
                    'fill'  => 'Fill the panel',
                    'align' => 'Align with components area',
                ),
                'hint' => array('content'=>__('Select how to display the featured image or video as the background.', 'pzarc'))
            ),
            array(
                'title'    => __('Components area position', 'pzarc'),
                'id'       => $prefix . 'components-position',
                'type'     => 'button_set',
                'width'    => '100%',
                'default'  => 'top',
                'options'  => array(
                    'top'    => 'Top',
                    'bottom' => 'Bottom',
                    'left'   => 'Left',
                    'right'  => 'Right',
                ),
                'hint' => array('content'=>__('Position for all the components as a group. </br>NOTE: If feature is set to align, then components will be below the feature, but not at the bottom of the panel. ', 'pzarc')),
                'desc'     => __('Left/right will only take affect when components area width is less than 100%', 'pzarc')
            ),
            array(
                'title'    => __('Nudge components area up/down %', 'pzarc'),
                'id'       => $prefix . 'components-nudge-y',
                'type'     => 'slider',
                'default'  => '0',
                'min'      => '0',
                'max'      => '100',
                'step'     => '1',
                'class'    => ' percent',
                'display_value' => 'label',
                'hint' => array('content'=>__('Enter percent to move the components area up/down. </br>NOTE: These measurements are percentage of the panel.', 'pzarc'))
            ),
            array(
                'title'    => __('Nudge components area left/right %', 'pzarc'),
                'id'       => $prefix . 'components-nudge-x',
                'type'     => 'slider',
                'default'  => '0',
                'min'      => '0',
                'max'      => '100',
                'step'     => '1',
                'class'    => ' percent',
                'display_value' => 'label',
                'hint' => array('content'=>__('Enter percent to move the components area left/right. </br>NOTE: These measurements are percentage of the panel.', 'pzarc'))
            ),
            array(
                'title'    => __('Components area width %', 'pzarc'),
                'id'       => $prefix . 'components-widths',
                'type'     => 'slider',
                'default'  => '100',
                'alt'      => 'zones',
                'min'      => '0',
                'max'      => '100',
                'step'     => '1',
                'class'    => ' percent',
                'display_value' => 'label',
                'hint' => array('content'=>__('Set the overall width for the components area. Necessary for left or right positioning of sections', 'pzarc')),
            ),

        )
    );


    $sections[2 ] = array(
        'title'      => 'Titles',
        'show_title' => false,
        'icon_class' => 'icon-large',
        'icon'       => 'el-icon-font',
        'fields'     => array(
            array(
                'title'   => __('Title prefix', 'pzarc'),
                'id'      => $prefix . 'title-prefix',
                'type'    => 'button_set',
                //              'width'    => 'auto',
                'default' => 'none',
                'class'   => ' horizontal',
                'options' => array('none' => 'None', 'bullet' => 'Bullet', 'thumb' => 'Thumbnail'),
            ),
            array(
                'title'   => __('Link titles', 'pzarc'),
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
    $sections[ ] = array(
        'title'      => 'Meta',
        'show_title' => false,
        'icon_class' => 'icon-large',
        'icon'       => 'el-icon-calendar',
        'fields'     => array(
          // ======================================
          // META
          // ======================================
          array(
              'title'   => __('Meta1 config', 'pzarc'),
              'id'      => $prefix . 'meta1-config',
              'type'    => 'textarea',
              'cols'    => 4,
              'rows'    => 2,
              'default' => '%date%, %author%',
          ),
          array(
              'title'   => __('Meta2 config', 'pzarc'),
              'id'      => $prefix . 'meta2-config',
              'type'    => 'textarea',
              'cols'    => 4,
              'rows'    => 2,
              'default' => '%categories%, %tags%',
          ),
          array(
              'title'   => __('Meta3 config', 'pzarc'),
              'id'      => $prefix . 'meta3-config',
              'type'    => 'textarea',
              'cols'    => 4,
              'rows'    => 2,
              'default' => '%commentcount%   %editlink%',
          ),
          array(
              'id'       => $prefix . 'meta-date-format',
              'title'    => 'Date format',
              'type'     => 'text',
              'default'  => 'l, F j, Y g:i a',
              'cols'     => 4,
              'hint' => array('title'=>'','content'=>'See here for information on <a href="http://codex.wordpress.org/Formatting_Date_and_Time" target=_blank>formatting date and time</a>')
          ),
        )
    );


    /**********
     * Excerpts
     *********/
    // EXCERPTS
    $sections[ ] = array(
        'title'      => 'Content',
        'icon_class' => 'icon-large',
        'icon'       => 'el-icon-align-left',
        'fields'     => array(

            array(
                'id'      => $prefix . 'responsive-hide-content',
                'title'   => 'Hide Content on screens less than',
                'type'    => 'text',
                'default' => 0,
                'cols'    => 3
            ),
            array(
                'id'      => $prefix . 'excerpts-word-count',
                'title'   => 'Excerpt length (words)',
                'type'    => 'text',
                'default' => 55,
                'cols'    => 3
            ),
            array(
                'title'   => __('Truncation indicator', 'pzarc'),
                'id'      => $prefix . 'readmore-truncation-indicator',
                'type'    => 'text',
                'cols'    => 3,
                'default' => '[...]',
            ),
            array(
                'title'   => __('Read More', 'pzarc'),
                'id'      => $prefix . 'readmore-text',
                'type'    => 'text',
                'cols'    => 3,
                'default' => 'Read more',
            ),
        )


    );
    $sections[ ] = array(
        'title'      => 'Images',
        'icon_class' => 'icon-large',
        'icon'       => 'el-icon-picture',
        'fields'     => array(

            array(
                'id'       => $prefix . 'image-responsive-heading',
                'title'    => 'Responsive',
                'type'     => 'section',
                'class'    => 'heading',
            ),
            array(
                'title'   => __('Background images: Effect on resize', 'pzarc'),
                'id'      => $prefix . 'background-image-resize',
                'type'    => 'button_set',
                'options' => array('scale' => 'Scale Vertically & Horizontally',
                                   'crop'  => 'Crop horizontally, retain height'),
                'default' => 'scale',
                'cols'    => 3,
            ),
            ///
            // IMAGE
            ///

            array(
                'id'       => $prefix . 'featured-image-heading',
                'title'    => 'Content Featured Image',
                'type'     => 'section',
                'class'    => 'heading',
                'hint' => array('content'=>'Left and right margins are included in the image width in the designer. e.g if Image width is 25% and right margin is 3%, image width will be adjusted to 22%')
            ),
            array(
                'id'      => $prefix . 'image-margin-top',
                'title'   => 'Margin top %',
                'type'    => 'slider',
                'width'   => '100%',
                'default' => '1',
                'alt'     => 'zones',
                'min'     => '0',
                'max'     => '100',
                'step'    => '1',
                'units'   => '%',
                'display_value' => 'label'
            ),
            array(
                'id'      => $prefix . 'image-margin-bottom',
                'title'   => 'Margin bottom %',
                'type'    => 'slider',
                'width'   => '100%',
                'default' => '1',
                'alt'     => 'zones',
                'min'     => '0',
                'max'     => '100',
                'step'    => '1',
                'units'   => '%',
                'display_value' => 'label'
            ),
            array(
                'id'      => $prefix . 'image-margin-left',
                'title'   => 'Margin left %',
                'type'    => 'slider',
                'width'   => '100%',
                'default' => 1,
                'alt'     => 'zones',
                'min'     => '0',
                'max'     => '100',
                'step'    => '1',
                'units'   => '%',
                'display_value' => 'label'
            ),
            array(
                'id'      => $prefix . 'image-margin-right',
                'title'   => 'Margin right %',
                'type'    => 'slider',
                'width'   => '100%',
                'default' => '1',
                'alt'     => 'zones',
                'min'     => '0',
                'max'     => '100',
                'step'    => '1',
                'units'   => '%',
                'display_value' => 'label'
            ),
            array(
                'title'   => __('Link image', 'pzarc'),
                'id'      => $prefix . 'link-image',
                'type'    => 'switch',
                'on'      => 'Yes',
                'off'     => 'No',
                'default' => true,
            ),
            array(
                'title'   => __('Image Captions', 'pzarc'),
                'id'      => $prefix . 'image-captions',
                'type'    => 'switch',
                'on'      => 'Yes',
                'off'     => 'No',
                'default' => false,
            ),
            array(
                'id'    => $prefix . 'image-sizing-heading',
                'title' => 'Image Sizing',
                'type'  => 'section',
                'class' => 'heading',
            ),
            array(
                'title'    => __('Convert to JPEG', 'pzarc'),
                'id'       => $prefix . 'image-to-jpeg',
                'type'     => 'switch',
                'default'  => false,
                'cols'     => 3,
                'hint' => array('content'=>'Convert all resized images to JPEG format - usually will be smaller.')
            ),
            array(
                'title'      => __('Image resizing method', 'pzarc'),
                'id'         => $prefix . 'image-resizing',
                'type'       => 'select',
                'allow_none' => false,
                'cols'       => 3,
                'default'    => 'crop',
                'options'    => array(
                    'crop'          => 'Crop width and height to fit',
                    'exact'         => 'Stretch to width and height (Warning: Can distort image)',
                    'portrait'      => 'Crop width, match height',
                    'landscape'     => 'Match width, crop height',
                    'auto'          => 'Fit within width and height',
                    'scaletowidth'  => 'Scale to resize width',
                    'scaletoheight' => 'Scale to resize height',
                    'none'          => 'No cropping, use original. (Use with care!)'
                ),
                'hint'   => array('content'=>'Choose how you want the image resized in respect of its original width and height to the Image Max Height and Width.'),
            ),
            array(
                'id'      => $prefix . 'image-max-width',
                'title'   => 'Image max width',
                'type'    => 'spinner',
                'min'     => '0',
                'max'     => '9999',
                'step'    => '1',
                'default' => '300',
                'cols'    => 3,
            ),
            array(
                'id'      => $prefix . 'image-max-height',
                'title'   => 'Image max height',
                'type'    => 'spinner',
                'min'     => '0',
                'max'     => '9999',
                'step'    => '1',
                'default' => '250',
                'cols'    => 3,

            ),
            array(
                'id'       => $prefix . 'image-bgcolour',
                'title'    => 'Image background colour',
                'type'     => 'color',
                'default'  => '#ffffff',
                'hint' => array('content'=>'If the cropped image  doesn\'t fill the resize area, fill the space with this colour.')
            ),
            array(
                'id'       => $prefix . 'image-quality',
                'title'    => 'Image quality',
                'type'     => 'spinner',
                'default'  => '75',
                'min'      => '20',
                'max'      => '100',
                'step'     => '1',
                'units'    => '%',
                'cols'     => 3,
                'hint' => array('content'=>'Quality to use when processing images')

            ),
        )
    );

    $sections[ ] = array(
        'title'      => 'Custom Fields',
        'icon_class' => 'icon-large',
        'icon'       => 'el-icon-wrench',
        'fields'     => array(
            array(
                'id'    => $prefix . 'settings-custom1',
                'title' => 'Custom fields 1',
                'type'  => 'section',
                'class' => 'heading',

            ),
            array(
                'id'    => $prefix . 'settings-custom2',
                'title' => 'Custom fields 2',
                'type'  => 'section',
                'class' => 'heading',

            ),
            array(
                'id'    => $prefix . 'settings-custom3',
                'title' => 'Custom fields 3',
                'type'  => 'section',
                'class' => 'heading',

            ),
        )
    );


    $sections[ ] = array(
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

  add_action("redux/metaboxes/{$redux_opt_name}/boxes", 'pzarc_panels_styling');
  function pzarc_panels_styling($metaboxes)
  {

//  $screen = get_current_screen();
//  if ($screen->ID != 'xx') {return;}
    $defaults = get_option( '_architect' );
    $prefix          = 'panels_styling_';
    $stylingSections = array();
    $sections        = array();
    $optprefix       = 'architect_config_';
//  $stylingSections[ ] = array(
//          'title'      => 'Styling',
//          'icon_class' => 'icon-large',
//          'icon'       => 'el-icon-brush',
//          'hint' => __('Architect uses standard WordPress class names as much as possible, so your Architect Blueprints will inherit styling from your theme if it uses these. Below you can add your own styling and classes. Enter CSS declarations, such as: background:#123; color:#abc; font-size:1.6em; padding:1%;', 'pzarc') . '<br/>' . __('As much as possible, use fluid units (%,em) if you want to ensure maximum responsiveness.', 'pzarc') . '<br/>' .
//                  __('The base font size is 10px. So, for example, to get a font size of 14px, use 1.4em. Even better is using relative ems i.e. rem.'),
//
//          'fields'     => array(

    $sections[ ] = array(
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
                'hint' => array('content'=>'Class: .pzarc-panel'),
            ),
            pzarc_redux_bg($prefix . 'panels-bg', array('.pzarc-panel'), $defaults[ $optprefix.'panels-bg' ]),
            pzarc_redux_padding($prefix . 'panels-padding', array('.pzarc-panel'), $defaults[ $optprefix.'panels-padding' ]),
            pzarc_redux_borders($prefix . 'panels-borders', array('.pzarc-panel'), $defaults[ $optprefix.'panels-borders' ]),
            array(
                'title'    => __('Components group', 'pzarc'),
                'id'       => $prefix . 'components-group',
                'type'     => 'section',
                'class'    => 'heading',
                'hint' => array('content'=>'Class: .pzarc_components'),
            ),
            pzarc_redux_bg($prefix . 'components-bg', array('.pzarc_components')),
            pzarc_redux_padding($prefix . 'components-padding', array('.pzarc_components')),
            pzarc_redux_borders($prefix . 'components-borders', array('.pzarc_components')),
            array(
                'title'    => __('Entry', 'pzarc'),
                'id'       => $prefix . 'entry',
                'type'     => 'section',
                'class'    => 'heading',
                'hint' => array('content'=>'Class: .hentry'),
            ),
            pzarc_redux_bg($prefix . 'hentry-bg', array('.hentry')),
            pzarc_redux_padding($prefix . 'hentry-padding', array('.hentry')),
            pzarc_redux_borders($prefix . 'hentry-borders', array('.hentry')),
        )
    );
    $sections[ ] = array(
        'title'      => 'Titles',
        'show_title' => false,
        'icon_class' => 'icon-large',
        'icon'       => 'el-icon-font',
        'desc'       => 'Class: .pzarc_entry-title',
        'fields'     => array(
            pzarc_redux_font($prefix . 'entry-title-font', array('.entry-title')),
            pzarc_redux_bg($prefix . 'entry-title-font-bg', array('.entry-title')),
            pzarc_redux_padding($prefix . 'entry-title-font-padding', array('.entry-title')),
            pzarc_redux_links($prefix . 'entry-title-font-links', array('.entry-title a')),
        ),
    );

    $sections[ ]  = array(
        'title'      => 'Meta',
        'show_title' => false,
        'icon_class' => 'icon-large',
        'icon'       => 'el-icon-calendar',
        'desc'       => 'Class: .pzarc_entry_meta',
        'fields'     => array(
            pzarc_redux_font($prefix . 'entry-meta-font', array('.entry-meta')),
            pzarc_redux_bg($prefix . 'entry-meta-font-bg', array('.entry-meta')),
            pzarc_redux_padding($prefix . 'entry-meta-font-padding', array('.entry-meta')),
            pzarc_redux_links($prefix . 'entry-meta-font-links', array('.entry-meta a')),
        )
    );
    $sections[ ]  = array(
        'title'      => 'Content',
        'show_title' => false,
        'icon_class' => 'icon-large',
        'icon'       => 'el-icon-align-left',
        'fields'     => array(
            pzarc_redux_font($prefix . 'entry-content-font', array('.entry-content')),
            pzarc_redux_bg($prefix . 'entry-content-font-bg', array('.entry-content')),
            pzarc_redux_padding($prefix . 'entry-content-font-padding', array('.entry-content')),
            pzarc_redux_links($prefix . 'entry-content-font-links', array('.entry-content a')),
            array(
                'title'    => __('Read more', 'pzarc'),
                'id'       => $prefix . 'entry-readmore',
                'type'     => 'section',
                'class'    => 'heading',
                'hint' => array('content'=>'Class: a.pzarc_readmore'),
            ),
            pzarc_redux_font($prefix . 'entry-readmore-font', array('.readmore')),
            pzarc_redux_bg($prefix . 'entry-readmore-font-bg', array('.readmore')),
            pzarc_redux_padding($prefix . 'entry-readmore-font-padding', array('.readmore')),
            pzarc_redux_links($prefix . 'entry-readmore-font-links', array('a.readmore')),
        )
    );
    $sections[ ]  = array(
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
                'hint' => array('content'=>'Class: .pzarc_entry_featured_image'),
                //     'hint'    => __('Format the entry featured image', 'pzarc')
            ),
            array(
                'title'                 => __('Background', 'pzarc'),
                'id'                    => $prefix . 'entry-image-background',
                'type'                  => 'background',
                'background-image'      => false,
                'background-repeat'     => false,
                'background-size'       => false,
                'background-attachment' => false,
                'background-position'   => false,
                'preview'               => false,
                //    'default' => $defaults[ $optprefix . 'image_defaults_entry-image-caption-defaults' ],
            ),
            array(
                'title' => __('Border', 'pzarc'),
                'id'    => $prefix . 'entry-image-background',
                'type'  => 'border',
                'all'   => false,

                //    'default' => $defaults[ $optprefix . 'image_defaults_entry-image-caption-defaults' ],
            ),
            array(
                'title' => __('Padding', 'pzarc'),
                'id'    => $prefix . 'entry-image-padding',
                'mode'  => 'padding',
                'type'  => 'spacing',
                'units' => array('px', '%')
                //    'default' => $defaults[ $optprefix . 'image_defaults_entry-image-caption-defaults' ],
            ),
            array(
                'title' => __('Caption', 'pzarc'),
                'id'    => $prefix . 'entry-image-caption',
                'type'  => 'section',
                'class' => 'heading',
                //    'default' => $defaults[ $optprefix . 'image_defaults_entry-image-caption-defaults' ],
            ),
            pzarc_redux_font($prefix . 'entry-mage-caption-font', array('.')),
            pzarc_redux_bg($prefix . 'entry-mage-caption-font-bg', array('.')),
            pzarc_redux_padding($prefix . 'entry-readmore-font-padding', array('.')),
        )


    );
    $sections[ ]  = array(
        'id'         => 'custom-css',
        'title'      => 'Custom CSS',
        'icon_class' => 'icon-large',
        'icon'       => 'el-icon-wrench',
        'fields'     => array(
            array(
                'id'    => $prefix . 'custom-css',
                'type'  => 'ace_editor',
                'title' => __('Custom CSS', 'pzarc'),
                'mode'  => 'css',
                'theme' => 'chrome',
            ),
        )
    );
    $sections[ ]  = array(
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
        <span class="pzarc-draggable pzarc-draggable-excerpt" title="Excerpt with featured image" data-idcode=excerpt ><span><img src="' . PZARC_PLUGIN_URL . '/assets/images/sample-image.jpg" style="max-width:20%;padding:2px;" class="pzarc-align none">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam quis justo erat. Cras semper sem hendre...[more]</span></span>
        <span class="pzarc-draggable pzarc-draggable-content" title="Full post content" data-idcode=content ><span><img src="' . PZARC_PLUGIN_URL . '/assets/images/sample-image.jpg" style="max-width:20%;padding:2px;" class="pzarc-align none"><img src="' . PZARC_PLUGIN_URL . '/assets/images/fireworks.jpg" style="max-width:30%;float:left;padding:5px;">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam quis justo erat. <ul><li>&nbsp;•&nbsp;Cras semper sem hendrerit</li><li>&nbsp;•&nbsp;Tortor porta at auctor</li></ul><strong>Lacus consequat</strong><p>Pellentesque pulvinar iaculis tellus in blandit. Suspendisse rhoncus, magna vel eleifend cursus, turpis odio molestie urna, quis posuere eros risus quis neque. </p></span></span>
        <span class="pzarc-draggable pzarc-draggable-image" title="Featured image" data-idcode=image style="max-height: 100px; overflow: hidden;"><span><img src="' . PZARC_PLUGIN_URL . '/assets/images/sample-image.jpg" style="max-width:100%;"></span></span>
        <span class="pzarc-draggable pzarc-draggable-caption pzarc-draggable-caption" title="Image caption" data-idcode=caption ><span>Featured image caption</span></span>
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
	<div class="plugin_url" style="display:none;">' . PZARC_PLUGIN_URL . '</div>
	';

    return $return_html;
  }

  /*
   * save_arc_layouts
   *
   * Creates CSS file
   *
   */
  add_action('save_post', 'save_arc_layouts', 99);
  function save_arc_layouts($postid)
  {
    $screen = get_current_screen();
    /*
     * $screen:
     * Array
      (
          [action] =>
          [base] => post
          [WP_Screencolumns] => 0
          [id] => arc-panels
          [*in_admin] => site
          [is_network] =>
          [is_user] =>
          [parent_base] =>
          [parent_file] =>
          [post_type] => arc-panels
          [taxonomy] =>
          [WP_Screen_help_tabs] => Array()
          [WP_Screen_help_sidebar] =>
          [WP_Screen_options] => Array()
          [WP_Screen_show_screen_options] =>
          [WP_Screen_screen_settings] =>
      )
     */
    if ($screen->id == 'arc-panels')
    {
      // save the CSS too
      // new wp_filesystem
      // create file named with id e.g. pzarc-cell-layout-123.css
      // Or should we connect this to the template? Potentially there'll be less panel layouts than templates tho

      $url = wp_nonce_url('post.php?post=' . $postid . '&action=edit', basename(__FILE__));
      if (false === ($creds = request_filesystem_credentials($url, '', false, false, null)))
      {
        return; // stop processing here
      }
      if (!WP_Filesystem($creds))
      {
        request_filesystem_credentials($url, '', true, false, null);

        return;
      }

//    WP_Filesystem(true);
// get the upload directory and make a test.txt file
      $upload_dir = wp_upload_dir();
      $filename
                  = trailingslashit($upload_dir[ 'basedir' ]) . '/cache/pizazzwp/arc/pzarc-cell-layout-' . $postid . '.css';
      wp_mkdir_p(trailingslashit($upload_dir[ 'basedir' ]) . '/cache/pizazzwp/arc/');

      // Need to create the file contents

///pzdebug($filename);
      $pzarc_contents = pzarc_create_css($postid, 'panels');

// by this point, the $wp_filesystem global should be working, so let's use it to create a file
      global $wp_filesystem;
      if (!$wp_filesystem->put_contents(
          $filename,
          $pzarc_contents,
          FS_CHMOD_FILE // predefined mode settings for WP files
      )
      )
      {
        echo 'error saving file!';
      }
    }
  }
