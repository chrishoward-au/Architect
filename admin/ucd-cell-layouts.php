<?php

//Cells may need to include Content because of custom fields!!! OOps! But content has to be over-arching! So I guess, cells will need to know the content type and its fields. So lets make Content Criteria again. :S

/**
 * Class pzucd_Cell_Layouts
 */
class pzucd_Cell_Layouts
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
      require_once PZUCD_PLUGIN_PATH . 'includes/pzucd-custom-field-types.php';

      //	add_action('admin_init', 'pzucd_preview_meta');
      //   add_action('add_meta_boxes', array($this, 'layouts_meta'));
      add_action('admin_head', array($this, 'cell_layouts_admin_head'));
      add_action('admin_enqueue_scripts', array($this, 'cell_layouts_admin_enqueue'));
//      add_filter('manage_ucd-layouts_posts_columns', array($this, 'add_cell_layout_columns'));
//      add_action('manage_ucd-layouts_posts_custom_column', array($this, 'add_cell_layout_column_content'), 10, 2);

      // check screen ucd-layouts. ugh. doesn't work for save and edit
//			if ( $_REQUEST[ 'post_type' ] == 'ucd-layouts' )
//			{
//			}
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
    if ('ucd-layouts' == $screen->id)
    {
      //  var_dump($screen->id);

      wp_enqueue_script('jquery-ui-draggable');
      wp_enqueue_script('jquery-ui-droppable');
      wp_enqueue_script('jquery-ui-sortable');
      wp_enqueue_script('jquery-ui-resizable');

      wp_enqueue_style('pzucd-admin-cells-css', PZUCD_PLUGIN_URL . 'admin/css/ucd-admin-cells.css');

      wp_enqueue_script('jquery-pzucd-metaboxes-cells', PZUCD_PLUGIN_URL . 'admin/js/ucd-metaboxes-cells.js', array('jquery'));
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
   * [add_cell_layout_columns description]
   * @param [type] $columns [description]
   */
  public function add_cell_layout_columns($columns)
  {
    unset($columns[ 'thumbnail' ]);
    $pzucd_front  = array_slice($columns, 0, 2);
    $pzucd_back   = array_slice($columns, 2);
    $pzucd_insert =
            array
            (
                    'pzucd_set_name'   => __('Set name', 'pzsp'),
                    'pzucd_short_name' => __('Screen size', 'pzsp'),
            );

    return array_merge($pzucd_front, $pzucd_insert, $pzucd_back);
  }

  /**
   * [add_cell_layout_column_content description]
   * @param [type] $column  [description]
   * @param [type] $post_id [description]
   */
  public function add_cell_layout_column_content($column, $post_id)
  {
    switch ($column)
    {
      case 'pzucd_short_name':
        echo get_post_meta($post_id, 'pzucd_layout-screen-size', true);
        break;
      case 'pzucd_set_name':
        echo get_post_meta($post_id, 'pzucd_layout-set-name', true);
        break;
    }
  }

  /**
   * [create_layouts_post_type description]
   * @return [type] [description]
   */
  public function create_layouts_post_type()
  {
    $labels = array(
            'name'               => _x('Cell designs', 'post type general name'),
            'singular_name'      => _x('Cell design', 'post type singular name'),
            'add_new'            => __('Add New Cell design'),
            'add_new_item'       => __('Add New Cell design'),
            'edit_item'          => __('Edit Cell design'),
            'new_item'           => __('New Cell design'),
            'view_item'          => __('View Cell design'),
            'search_items'       => __('Search Cell designs'),
            'not_found'          => __('No cell designs found'),
            'not_found_in_trash' => __('No cell designs found in Trash'),
            'parent_item_colon'  => '',
            'menu_name'          => _x('<span class="dashicons-icon icon-cells"></span>Cells', 'pzucd-cell-designer'),
    );

    $args = array(
            'labels'              => $labels,
            'description'         => __('Ultimate Content Display cells are used to create reusable cell designs for use in your UCD blocks, widgets, shortcodes and WP template tags'),
            'public'              => false,
            'publicly_queryable'  => false,
            'show_ui'             => true,
            'show_in_menu'        => 'pzucd',
            'show_in_nav_menus'   => false,
            'query_var'           => true,
            'rewrite'             => true,
            'capability_type'     => 'post',
            'has_archive'         => false,
            'hierarchical'        => false,
            'menu_position'       => 10,
            'supports'            => array('title', 'revisions'),
            'exclude_from_search' => true,
            //			'register_meta_box_cb' => array($this, 'layouts_meta')
    );

    register_post_type('ucd-layouts', $args);
  }


}

// EOC


/* why not use a WP like methodology!

register_cells('name',$args)'
register_criteria('name',$args);
register_layout('name',$args);

*/


add_filter('cmb_meta_boxes', 'pzucd_cells_wizard_metabox');
function pzucd_cells_wizard_metabox($meta_boxes = array())
{
  $prefix        = '_pzucd_';
  $fields        = array(
          array(
                  'name'    => 'Select what style of cell you want to make',
                  'id'      => $prefix . 'cell-wizard',
                  'type'    => 'radio',
                  'default' => 'custom',
                  'desc'    => 'Select one to quickly enable relevant settings, or custom to build your own from scratch with all settings and defaults. Minimize this metabox once you are happy with your selection.',
                  'options' => array(
                          'custom'   => 'Custom',
                          'article'  => 'Article',
                          'blog'     => 'Blog Excerpt',
                          'feature'  => 'Featured Content',
                          'gallery'  => 'Gallery',
                          'magazine' => 'Magazine Excerpt',
                  )
          )
  );
  $meta_boxes[ ] = array(
          'title'    => 'What do you want to do?',
          'pages'    => 'ucd-layouts',
          'context'  => 'normal',
          'priority' => 'high',
          'fields'   => $fields // An array of fields.
  );

  return $meta_boxes;
}


add_filter('cmb_meta_boxes', 'pzucd_cell_designer_meta');
function pzucd_cell_designer_meta($meta_boxes = array())
{
  $prefix        = '_pzucd_';
  $fields        = array(
          array(
                  'name'     => 'Cell preview',
                  'id'       => $prefix . 'layout-cell-preview',
                  'cols'     => 12,
                  'type'     => 'pzlayout',
                  'readonly' => false, // Readonly fields can't be written to by code! Weird
                  'code'     => draw_cell_layout(),
                  'default'  => json_encode(array(
                                                    'title'   => array('width' => 100, 'show' => true),
                                                    'meta1'   => array('width' => 100, 'show' => true),
                                                    'image'   => array('width' => 25, 'show' => true),
                                                    'excerpt' => array('width' => 75, 'show' => true),
                                                    'content' => array('width' => 100, 'show' => false),
                                                    'meta2'   => array('width' => 100, 'show' => false),
                                                    'meta3'   => array('width' => 100, 'show' => false),
                                                    'custom1' => array('width' => 100, 'show' => false),
                                                    'custom2' => array('width' => 100, 'show' => false),
                                                    'custom3' => array('width' => 100, 'show' => false))),
                  'desc'     => __('Drag and drop to sort the order of your elements. Heights are fluid in cells, so not indicative of how it will look on the page.', 'pzucd')
          ),
  );
  $meta_boxes[ ] = array(
          'title'   => 'Cell designer',
          'pages'   => 'ucd-layouts',
          'fields'  => $fields,
          'context' => 'normal',
  );

  return $meta_boxes;
}

add_filter('cmb_meta_boxes', 'pzucd_cell_designer_settings_meta');
function pzucd_cell_designer_settings_meta($meta_boxes = array())
{
  $prefix = '_pzucd_';

  $fields        = array(
          array(
                  'name'       => __('Short name', 'pzucd'),
                  'id'         => $prefix . 'layout-short-name',
                  'type'       => 'text',
                  'cols'       => 12,
                  'tooltip'    => __('A short name for this cell layout. This enables you to create sets of layouts for different parent dimensions. That is, when the dimensions of the parent change, the layout will change accordingly. Traditional responsive design is based on the width of your device\'s screen,; however this fails if you place the object in a narrow column on a large screen,', 'pzucd'),
                  'help'       => __('Create sets of layouts with each layout in a set for different parent dimensions'),
          ),

          //    array(
          //      'name'    => __('Content', 'pzucd'),
          //      'id'      => $prefix . 'layout-cells-content',
          //      'type'    => 'pzselect',
          //      'default' => 'post',
          //      'cols'    => 12,
          //      'tooltip' => __('Select the content type to display in these cells.', 'pzucd'),
          //      'options' => array(
          //        'post'       => 'Posts',
          //        'page'       => 'Pages',
          //        'attachment' => 'Attachments'
          //      )
          //    ),

          // OMG!! The multiselect is working and I don't know why!!

          array(
                  'name'     => __('Components to show', 'pzucd'),
                  'id'       => $prefix . 'layout-show',
                  'type'     => 'select',
                  'multiple' => true,
                  'cols'     => 12,
                  'default'  => array('title', 'excerpt', 'meta1', 'image'),
                  'options'  => array(
                          'title'   => 'Title',
                          'excerpt' => 'Excerpt',
                          'content' => 'Content',
                          'image'   => 'Image',
                          'meta1'   => 'Meta1',
                          'meta2'   => 'Meta2',
                          'meta3'   => 'Meta3',
                          'custom1' => 'Custom Field 1',
                          'custom2' => 'Custom Field 2',
                          'custom3' => 'Custom Field 3',
                          //        'custom4' => 'Custom Field 4',
                  ),
                  'tooltip'  => __('Select which base components to include in this cell layout.', 'pzucd')
          ),
          //    array(
          //      'name'     => __('Components to show', 'pzucd'),
          //      'id'       => $prefix . 'layout-show',
          //      'type' => 'group',
          //      'fields' => array(
          //        array('name'=>'Title','type'=>'checkbox','id'=>$prefix.'show-title','default'=>true),
          //        array('name'=>'Excerpt','type'=>'checkbox','id'=>$prefix.'show-excerpt','default'=>true),
          //        array('name'=>'Content','type'=>'checkbox','id'=>$prefix.'show-content','default'=>false)
          //      ),
          //    ),


          array(
                  'name'    => __('Components area position', 'pzucd'),
                  'id'      => $prefix . 'layout-sections-position',
                  'type'    => 'pzselect',
                  'cols'    => 12,
                  'default' => 'top',
                  'options' => array(
                          'top'    => 'Top of cell',
                          'bottom' => 'Bottom of cell',
                          'left'   => 'Left of cell',
                          'right'  => 'Right of cell',
                  ),
                  //'desc'		 => __('Position for all the components as a group', 'pzucd')
          ),
          array(
                  'name'    => __('Nudge components area up/down', 'pzucd'),
                  'id'      => $prefix . 'layout-nudge-section-y',
                  'cols'    => 12,
                  'type'    => 'pzrange',
                  'default' => '0',
                  'min'     => '0',
                  'max'     => '100',
                  'step'    => '1',
                  'suffix'  => '%',
                  'tooltip' => __('Enter percent to move the components area up/down. Note: These measurements are percentage of the cell.', 'pzucd')
          ),
          array(
                  'name'    => __('Nudge components area left/right', 'pzucd'),
                  'id'      => $prefix . 'layout-nudge-section-x',
                  'type'    => 'pzrange',
                  'cols'    => 12,
                  'default' => '0',
                  'min'     => '0',
                  'max'     => '100',
                  'step'    => '1',
                  'suffix'  => '%',
                  'tooltip' => __('Enter percent to move the components area left/right. Note: These measurements are percentage of the cell.', 'pzucd')
          ),
          array(
                  'name'    => __('Components area width', 'pzucd'),
                  'id'      => $prefix . 'layout-sections-widths',
                  'type'    => 'pzrange',
                  'cols'    => 12,
                  'default' => '100',
                  'alt'     => 'zones',
                  'min'     => '0',
                  'max'     => '100',
                  'step'    => '1',
                  'suffix'  => '%',
                  'tooltip' => __('Set the overall width for the components area. Necessary for left or right positioning of sections', 'pzucd'),
                  //      'help'    => __('Note:The sum of the width and the left/right nudge should equal 100', 'pzucd')
          ),
          array(
                  'name'    => __('Excerpt image', 'pzucd'),
                  'id'      => $prefix . 'layout-excerpt-thumb',
                  'cols'    => 12,
                  'type'    => 'pzselect',
                  'default' => 'none',
                  'options' => array(
                          'none'  => 'No image',
                          'left'  => 'Image left',
                          'right' => 'Image right',
                  ),
                  'tooltip' => __('Set the alignment of the image when it is in the excerpt. This will use the image settings', 'pzucd')
          ),
          array(
                  'name'    => __('Feature Image/Video', 'pzucd'),
                  'id'      => $prefix . 'layout-background-image',
                  'cols'    => 12,
                  'type'    => 'pzselect',
                  'default' => 'none',
                  'options' => array(
                          'none'  => 'No image',
                          'fill'  => 'Fill the cell',
                          'align' => 'Align with components area',
                  ),
                  'tooltip' => __('Select how to display the featured image or video as the background.', 'pzucd')
          ),
          array(
                  'name'    => __('Cell Height Type', 'pzucd'),
                  'id'      => $prefix . 'layout-cell-height-type',
                  'cols'    => 12,
                  'type'    => 'pzselect',
                  'default' => 'fluid',
                  'options' => array(
                          'fluid' => 'Fluid',
                          'fixed' => 'Fixed',
                  ),
                  'tooltip' => __('Choose whether to set the height of the cells (fixed), or allow them to adjust to the content height (fluid).', 'pzucd')
          ),
          // Hmm? How's this gunna sit with the min-height in templates?
          // We will want to use this for image height cropping when behind.
          array(
                  'name'    => __('Cell Height', 'pzucd'),
                  'id'      => $prefix . 'layout-cell-height',
                  'type'    => 'pzspinner',
                  'cols'    => 6,
                  'default' => '350',
                  'min'     => '0',
                  'max'     => '9999',
                  'step'    => '1',
                  'suffix'  => 'px',
                  'tooltip' => __('If using fixed height, set height for the cell.', 'pzucd'),
          ),
          array(
                  'name'    => __('Components Height', 'pzucd'),
                  'id'      => $prefix . 'layout-components-height',
                  'type'    => 'pzspinner',
                  'cols'    => 6,
                  'default' => '100',
                  'min'     => '0',
                  'max'     => '9999',
                  'step'    => '1',
                  'suffix'  => 'px',
                  'tooltip' => __('If using fixed height, set height for the components area.', 'pzucd'),
          ),

          array(
                  'name'    => __('Save cell layout', 'pzucd'),
                  'id'      => $prefix . 'layout-set-save',
                  'type'    => 'pzsubmit',
                  'default' => 'Save'
          ),

  );
  $meta_boxes[ ] = array(
          'title'    => 'Cell designer settings',
          'pages'    => 'ucd-layouts',
          'fields'   => $fields,
          'context'  => 'side',
          'priority' => 'high'

  );

  return $meta_boxes;
}

add_filter('cmb_meta_boxes', 'pzucd_cell_settings_meta');
function pzucd_cell_settings_meta($meta_boxes = array())
{
  $prefix = '_pzucd_';
  $fields = array(
          array(
                  'id'   => $prefix . 'cell-settings-title',
                  'name' => 'Title',
                  'type' => 'title'
          ),

          array(
                  'id'   => $prefix . 'cell-settings-meta1',
                  'name' => 'Meta1',
                  'type' => 'title',
          ),
          array(
                  'name'     => __('Meta1 config', 'pzucd'),
                  'id'       => $prefix . 'cell-settings-meta1-config',
                  'type'     => 'select',
                  'multiple' => true,
                  'cols'     => 4,
                  'default'  => array('date', 'author', 'editlink'),
                  'options'  => array('date' => 'Date', 'author' => 'Author', 'editlink' => 'Edit link', 'categories' => 'Categories', 'tags' => 'Tags', 'commentcount' => 'Comment count'),
          ),
          array(
                  'name'     => __('Meta2 config', 'pzucd'),
                  'id'       => $prefix . 'cell-settings-meta2-config',
                  'type'     => 'select',
                  'multiple' => true,
                  'cols'     => 4,
                  'default'  => array('categories', 'tags'),
                  'options'  => array('date' => 'Date', 'author' => 'Author', 'editlink' => 'Edit link', 'categories' => 'Categories', 'tags' => 'Tags', 'commentcount' => 'Comment count'),
          ),
          array(
                  'name'     => __('Meta3 config', 'pzucd'),
                  'id'       => $prefix . 'cell-settings-meta3-config',
                  'type'     => 'select',
                  'multiple' => true,
                  'cols'     => 4,
                  'default'  => array('commentcount'),
                  'options'  => array('date' => 'Date', 'author' => 'Author', 'editlink' => 'Edit link', 'categories' => 'Categories', 'tags' => 'Tags', 'commentcount' => 'Comment count'),
          ),
          array(
                  'id'   => $prefix . 'cell-settings-excerpt',
                  'name' => 'Excerpt',
                  'type' => 'title'
          ),
          array(
                  'id'   => $prefix . 'cell-settings-image',
                  'name' => 'Image',
                  'type' => 'title'
          ),
          array(
                  'id'      => $prefix . 'cell-settings-image-max-width',
                  'name'    => 'Image max width',
                  'type'    => 'text_small',
                  'default' => '300',
                  'cols'=> 6
          ),
          array(
                  'id'      => $prefix . 'cell-settings-image-max-height',
                  'name'    => 'Image max height',
                  'type'    => 'text_small',
                  'default' => '250',
                  'cols'=> 6
          ),
          array(
                  'id'   => $prefix . 'cell-settings-content',
                  'name' => 'Full Content',
                  'type' => 'title'
          ),
          array(
                  'id'   => $prefix . 'cell-settings-custom1',
                  'name' => 'Custom fields 1',
                  'type' => 'title'
          ),
          array(
                  'id'   => $prefix . 'cell-settings-custom2',
                  'name' => 'Custom fields 2',
                  'type' => 'title'
          ),
          array(
                  'id'   => $prefix . 'cell-settings-custom3',
                  'name' => 'Custom fields 3',
                  'type' => 'title'
          ),
  );

  $meta_boxes[ ] = array(
          'title'   => 'Settings',
          'pages'   => 'ucd-layouts',
          'context' => 'normal',
          'fields'  => $fields
  );

  return $meta_boxes;
}

add_filter('cmb_meta_boxes', 'pzucd_cell_formats_meta');
function pzucd_cell_formats_meta($meta_boxes = array())
{
  $prefix        = '_pzucd_';
  $fields        = array(
          array(
                  'name'    => __('CSS instructions', 'pzucd'),
                  'id'      => $prefix . 'layout-format-instructions',
                  'type'    => 'pzinfo',
                  
                  'desc'    => __('Enter CSS declarations, such as: background:#123; color:#abc; font-size:1.6em; padding:1%;', 'pzucd') . '<br/>' . __('As much as possible, use fluid units (%,em) if you want to ensure maximum responsiveness.', 'pzucd') . '<br/>' .
                          __('The base font size is 10px. So, for example, to get a font size of 14px, use 1.4em. Even better is using relative ems i.e. rem.')
          ),
          array(
                  'name'    => __('Cells', 'pzucd'),
                  'id'      => $prefix . 'layout-format-cells',
                  'type'    => 'textarea',
                  'rows'    => 1,
                  'cols'    => 6,
                  
                  'class'   => '.pzucd_cells',
                  'help'    => 'Declarations only for class: .pzucd_cells',
                  //     'desc'    => __('Format the cells', 'pzucd')
          ),
          array(
                  'name'    => __('Cells Classes', 'pzucd'),
                  'id'      => $prefix . 'layout-format-cells-classes',
                  'type'    => 'text',
                  'rows'    => 1,
                  'cols'    => 6,
                  'default' => '.pzucd-cells',
                  //      'desc'    => __('Format the entry title', 'pzucd')
          ),
          array(
                  'name'    => __('Components group', 'pzucd'),
                  'id'      => $prefix . 'layout-format-components-group',
                  'type'    => 'textarea',
                  'rows'    => 1,
                  'cols'    => 6,
                  
                  'help'    => 'Declarations only for class: .pzucd_components',
                  //     'desc'    => __('Format the components group', 'pzucd')
          ),
          array(
                  'name'    => __('Components Group Classes', 'pzucd'),
                  'id'      => $prefix . 'layout-format-components-group-classes',
                  'type'    => 'text',
                  'rows'    => 1,
                  'cols'    => 6,
                  'default' => '.pzucd-components',
          ),
          array(
                  'name'    => __('Entry title', 'pzucd'),
                  'id'      => $prefix . 'layout-format-entry-title',
                  'type'    => 'textarea',
                  'rows'    => 1,
                  'cols'    => 6,
                  
                  'help'    => 'Declarations only for class: .pzucd_entry_title and .pzucd_entry_title a',
                  //      'desc'    => __('Format the entry title', 'pzucd')
          ),
          array(
                  'name'    => __('Entry Title Classes', 'pzucd'),
                  'id'      => $prefix . 'layout-format-entry-title-classes',
                  'type'    => 'text',
                  'rows'    => 1,
                  'cols'    => 6,
                  'default' => '.entry-title, .entry-title a',
          ),
          array(
                  'name'    => __('Entry title hover', 'pzucd'),
                  'id'      => $prefix . 'layout-format-entry-title-hover',
                  'type'    => 'textarea',
                  'rows'    => 1,
                  
                  'help'    => 'Declarations only for class: .pzucd_entry_title a:hover',
                  //      'desc'    => __('Format the entry title link hover', 'pzucd')
          ),
          array(
                  'name'    => __('Entry meta', 'pzucd'),
                  'id'      => $prefix . 'layout-format-entry-meta',
                  'type'    => 'textarea',
                  'rows'    => 1,
                  
                  'help'    => 'Declarations only for class: .pzucd_entry_meta',
                  //     'desc'    => __('Format the entry meta', 'pzucd')
          ),
          array(
                  'name'    => __('Entry meta links', 'pzucd'),
                  'id'      => $prefix . 'layout-format-entry-meta-link',
                  'type'    => 'textarea',
                  'rows'    => 1,
                  
                  'help'    => 'Declarations only for class: .pzucd_entry_meta a',
                  //     'desc'    => __('Format the entry meta link', 'pzucd')
          ),
          array(
                  'name'    => __('Entry meta link hover', 'pzucd'),
                  'id'      => $prefix . 'layout-format-entry-meta-link-hover',
                  'type'    => 'textarea',
                  'rows'    => 1,
                  
                  'help'    => 'Declarations only for class: .pzucd_entry_meta a:hover',
                  //     'desc'    => __('Format the entry meta link hover', 'pzucd')
          ),
          array(
                  'name'    => __('Entry content', 'pzucd'),
                  'id'      => $prefix . 'layout-format-entry-content',
                  'type'    => 'textarea',
                  'rows'    => 1,
                  
                  'help'    => 'Declarations only for class: .pzucd_entry_content',
                  //     'desc'    => __('Format the entry content', 'pzucd')
          ),
          array(
                  'name'    => __('Entry content links', 'pzucd'),
                  'id'      => $prefix . 'layout-format-entry-content-links',
                  'type'    => 'textarea',
                  'rows'    => 1,
                  
                  'help'    => 'Declarations only for class: .pzucd_entry_content a',
                  //     'desc'    => __('Format the entry content', 'pzucd')
          ),
          array(
                  'name'    => __('Entry content link hover', 'pzucd'),
                  'id'      => $prefix . 'layout-format-entry-content-link-hover',
                  'type'    => 'textarea',
                  'rows'    => 1,
                  
                  'help'    => 'Declarations only for class: .pzucd_entry_content a:hover',
                  //     'desc'    => __('Format the entry content link hover', 'pzucd')
          ),
          array(
                  'name'    => __('Entry featured image', 'pzucd'),
                  'id'      => $prefix . 'layout-format-entry-fimage',
                  'type'    => 'textarea',
                  'rows'    => 1,
                  
                  'help'    => 'Declarations only for class: .pzucd_entry_featured_image',
                  //     'desc'    => __('Format the entry featured image', 'pzucd')
          ),
          array(
                  'name'    => __('Read more', 'pzucd'),
                  'id'      => $prefix . 'layout-format-entry-readmore',
                  'type'    => 'textarea',
                  'rows'    => 1,
                  
                  'help'    => 'Declarations only for class: a.pzucd_readmore',
                  //     'desc'    => __('Format the content "Read more" link', 'pzucd')
          ),
          array(
                  'name'    => __('Read more hover', 'pzucd'),
                  'id'      => $prefix . 'layout-format-entry-readmore-hover',
                  'type'    => 'textarea',
                  'rows'    => 1,
                  
                  'help'    => 'Declarations only for class: a.pzucd_readmore:hover',
                  //     'desc'    => __('Format the content "Read more" link hover', 'pzucd')
          ),
  );
  $meta_boxes[ ] = array(
          'title'  => 'Styling',
          'pages'  => 'ucd-layouts',
          'fields' => $fields
  );

  return $meta_boxes;
}

/**
 * [draw_cell_layout description]
 * @return [type] [description]
 */
function draw_cell_layout()
{
  $return_html = '';

  // Put in a hidden field with the plugin url for use in js
  $return_html = '
  <div id="pzucd-custom-pzucd_layout" class="pzucd-custom">
    <div id="pzucd-dropzone-pzucd_layout" class="pzucd-dropzone">
      <div class="pzgp-cell-image-behind"></div>
      <div class="pzucd-content-area sortable">
        <span class="pzucd-draggable pzucd-draggable-title" title="Post title" data-idcode=title ><span>This is the title</span></span>
        <span class="pzucd-draggable pzucd-draggable-meta1 pzucd-draggable-meta" title="Meta info 1" data-idcode=meta1 ><span>Jan 1 2013</span></span>
        <span class="pzucd-draggable pzucd-draggable-excerpt" title="Excerpt with featured image" data-idcode=excerpt ><span><img src="' . PZUCD_PLUGIN_URL . '/assets/images/sample-image.jpg" style="max-width:20%;padding:2px;" class="pzucd-align-none">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam quis justo erat. Cras semper sem hendre...[more]</span></span>
        <span class="pzucd-draggable pzucd-draggable-content" title="Full post content" data-idcode=content ><span><img src="' . PZUCD_PLUGIN_URL . '/assets/images/sample-image.jpg" style="max-width:30%;float:left;padding:5px;">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam quis justo erat. <ul><li>&nbsp;•&nbsp;Cras semper sem hendrerit</li><li>&nbsp;•&nbsp;Tortor porta at auctor</li></ul><strong>Lacus consequat</strong><p>Pellentesque pulvinar iaculis tellus in blandit. Suspendisse rhoncus, magna vel eleifend cursus, turpis odio molestie urna, quis posuere eros risus quis neque. </p></span></span>
        <span class="pzucd-draggable pzucd-draggable-image" title="Featured image" data-idcode=image style="max-height: 100px; overflow: hidden;"><span><img src="' . PZUCD_PLUGIN_URL . '/assets/images/sample-image.jpg" style="max-width:100%;"></span></span>
        <span class="pzucd-draggable pzucd-draggable-meta2 pzucd-draggable-meta" title="Meta info 2" data-idcode=meta2 ><span>Categories - News, Sport</span></span>
        <span class="pzucd-draggable pzucd-draggable-meta3 pzucd-draggable-meta" title="Meta info 3" data-idcode=meta3 ><span>Comments: 27</span></span>
        <span class="pzucd-draggable pzucd-draggable-custom1 pzucd-draggable-meta" title="Custom field 1" data-idcode=custom1 ><span>Custom content 1</span></span>
        <span class="pzucd-draggable pzucd-draggable-custom2 pzucd-draggable-meta" title="Custom field 2" data-idcode=custom2 ><span>Custom content 2</span></span>
        <span class="pzucd-draggable pzucd-draggable-custom3 pzucd-draggable-meta" title="Custom field 3" data-idcode=custom3 ><span>Custom content 3</span></span>
      </div>
	  </div>
	  <p class="pzucd-states pzcentred">Loading</p>
	  <p class="howto pzcentred"><strong style="color:#d00;">This is an example only and thus only a <span style="border-bottom: 3px double;">general guide</span> to how the cells will look.</strong></p>
	</div>
	<div class="plugin_url" style="display:none;">' . PZUCD_PLUGIN_URL . '</div>
	';

  return $return_html;
}

add_action('pre_post_update', 'save_ucd_layouts');
function save_ucd_layouts($postid)
{
  $screen = get_current_screen();
  /*
   * $screen:
   * Array
    (
        [action] =>
        [base] => post
        [WP_Screencolumns] => 0
        [id] => ucd-layouts
        [*in_admin] => site
        [is_network] =>
        [is_user] =>
        [parent_base] =>
        [parent_file] =>
        [post_type] => ucd-layouts
        [taxonomy] =>
        [WP_Screen_help_tabs] => Array()
        [WP_Screen_help_sidebar] =>
        [WP_Screen_options] => Array()
        [WP_Screen_show_screen_options] =>
        [WP_Screen_screen_settings] =>
    )
   */

  if ($screen->id == 'ucd-layouts')
  {
    // save the CSS too
    // new wp_filesystem
    // create file named with id e.g. pzucd-cell-layout-123.css
    // Or should we connect this to the template? Potentially there'll be less cell layouts than templates tho

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
    $filename   = trailingslashit($upload_dir[ 'basedir' ]) . '/cache/pizazzwp/ucd/pzucd-cell-layout-' . $postid . '.css';
    wp_mkdir_p(trailingslashit($upload_dir[ 'basedir' ]) . '/cache/pizazzwp/ucd/');

    // Need to create the file contents
    // For each field in stylings, create css
    $pzucd_cells    = get_post_meta($postid);
    $pzucd_contents = "/* This is the css for cell $postid */\n";
    foreach ($pzucd_cells as $key => $value)
    {
      if (strpos($key, '-format-') && !empty($value[ 0 ]) && !empty($pzucd_cells[ $key . '-classes' ][ 0 ]))
      {
        $pzucd_classes = '.pzucd-' . $postid  . ' ' . str_replace(',', ', .pzucd-' . $postid  . ' ', $pzucd_cells[ $key . '-classes' ][ 0 ]);
        $pzucd_contents .= $pzucd_classes . ' {' . $value[ 0 ] . '}' . "\n";
      }
    }
    $pzucd_tb = ($pzucd_cells[ '_pzucd_layout-sections-position' ][ 0 ] == 'left' || $pzucd_cells[ '_pzucd_layout-sections-position' ][ 0 ] == 'right' ? 'top' : $pzucd_cells[ '_pzucd_layout-sections-position' ][ 0 ]);
    $pzucd_lr = ($pzucd_cells[ '_pzucd_layout-sections-position' ][ 0 ] == 'top' || $pzucd_cells[ '_pzucd_layout-sections-position' ][ 0 ] == 'bottom' ? 'left' : $pzucd_cells[ '_pzucd_layout-sections-position' ][ 0 ]);
    $pzucd_contents .= '.pzucd-' . $postid  . ' .pzucd-components {' . $pzucd_tb . ':' . $pzucd_cells[ '_pzucd_layout-nudge-section-y' ][ 0 ] . '%;' . $pzucd_lr . ':' . $pzucd_cells[ '_pzucd_layout-nudge-section-x' ][ 0 ] . '%;width:' . $pzucd_cells[ '_pzucd_layout-sections-widths' ][ 0 ] . '%;}';


// by this point, the $wp_filesystem global should be working, so let's use it to create a file
    global $wp_filesystem;
    if (!$wp_filesystem->put_contents(
            $filename,
            $pzucd_contents,
            FS_CHMOD_FILE // predefined mode settings for WP files
    )
    )
    {
      echo 'error saving file!';
    }
  }
}