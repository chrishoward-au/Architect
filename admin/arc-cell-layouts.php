<?php

//Cells may need to include Content because of custom fields!!! OOps! But content has to be over-arching! So I guess, cells will need to know the content type and its fields. So lets make Content Criteria again. :S

/**
 * Class pzarc_Cell_Layouts
 */
class pzarc_Cell_Layouts
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
      require_once PZARC_PLUGIN_PATH . 'includes/pzarc-custom-field-types.php';

      //	add_action('admin_init', 'pzarc_preview_meta');
      //   add_action('add_meta_boxes', array($this, 'layouts_meta'));
      add_action('admin_head', array($this, 'cell_layouts_admin_head'));
      add_action('admin_enqueue_scripts', array($this, 'cell_layouts_admin_enqueue'));
//      add_filter('manage_arc-layouts_posts_columns', array($this, 'add_cell_layout_columns'));
//      add_action('manage_arc-layouts_posts_custom_column', array($this, 'add_cell_layout_column_content'), 10, 2);

      // check screen arc-layouts. ugh. doesn't work for save and edit
//			if ( $_REQUEST[ 'post_type' ] == 'arc-layouts' )
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
    if ('arc-layouts' == $screen->id)
    {
      //  var_dump($screen->id);

      wp_enqueue_script('jquery-ui-draggable');
      wp_enqueue_script('jquery-ui-droppable');
      wp_enqueue_script('jquery-ui-sortable');
      wp_enqueue_script('jquery-ui-resizable');

      wp_enqueue_style('pzarc-admin-cells-css', PZARC_PLUGIN_URL . 'admin/css/arc-admin-cells.css');

      wp_enqueue_script('jquery-pzarc-metaboxes-cells', PZARC_PLUGIN_URL . 'admin/js/arc-metaboxes-cells.js', array('jquery'));
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
    $pzarc_front  = array_slice($columns, 0, 2);
    $pzarc_back   = array_slice($columns, 2);
    $pzarc_insert =
            array
            (
                    'pzarc_set_name'   => __('Set name', 'pzsp'),
                    'pzarc_short_name' => __('Screen size', 'pzsp'),
            );

    return array_merge($pzarc_front, $pzarc_insert, $pzarc_back);
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
      case 'pzarc_short_name':
        echo get_post_meta($post_id, 'pzarc_layout-screen-size', true);
        break;
      case 'pzarc_set_name':
        echo get_post_meta($post_id, 'pzarc_layout-set-name', true);
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
            'menu_name'          => _x('<span class="dashicons dashicons-id-alt"></span>Cells', 'pzarc-cell-designer'),
    );

    $args = array(
            'labels'              => $labels,
            'description'         => __('Architect cells are used to create reusable cell designs for use in your Architect blocks, widgets, shortcodes and WP template tags'),
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
            //      'register_meta_box_cb' => array($this, 'layouts_meta')
    );

    register_post_type('arc-layouts', $args);
  }


}

// EOC


/* why not use a WP like methodology!

register_cells('name',$args)'
register_criteria('name',$args);
register_layout('name',$args);

*/


//add_filter('cmb_meta_boxes', 'pzarc_cells_wizard_metabox');
//function pzarc_cells_wizard_metabox($meta_boxes = array())
//{
//  $prefix        = '_pzarc_';
//  $fields        = array(
//          array(
//                  'name'    => 'Select what style of cell you want to make',
//                  'id'      => $prefix . 'cell-wizard',
//                  'type'    => 'radio',
//                  'default' => 'custom',
//                  'desc'    => 'Select one to quickly enable relevant settings, or custom to build your own from scratch with all settings and defaults. Minimize this metabox once you are happy with your selection.',
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
//  $meta_boxes[ ] = array(
//          'title'    => 'What do you want to do?',
//          'pages'    => 'arc-layouts',
//          'context'  => 'normal',
//          'priority' => 'high',
//          'fields'   => $fields // An array of fields.
//  );
//
//  return $meta_boxes;
//}

add_filter('cmb_meta_boxes', 'pzarc_cell_designer_tabbed');
function pzarc_cell_designer_tabbed($meta_boxes = array())
{
  $prefix        = '_pzarc_';
  $fields        = array(
          array(
                  'name'     => __('Tabs', 'pzarc'),
                  'id'       => $prefix . 'layout-cell-tabs',
                  'type'     => 'pztabs',
                  'defaults' => array('#cell-designer' => 'Cell Designer',
                                      '#styling'       => 'Styling',
                                      '#settings'=>'Settings'
                                      //                                      '#posts-filters'=>'Posts',
                                      //                                      '#pages-filters'=>'Pages',
                                      //                                      '#galleries-filters'=>'Galleries',
                                      //                                      '#custom-post-types-filters'=>'Custom Content',
                  ),
          ),

  );
  $meta_boxes[ ] = array(
          'title'    => 'Cells',
          'pages'    => 'arc-layouts',
          'context'  => 'normal',
          'priority' => 'high',
          'fields'   => $fields // An array of fields.
  );

  return $meta_boxes;
}

add_filter('cmb_meta_boxes', 'pzarc_cell_designer_meta');
function pzarc_cell_designer_meta($meta_boxes = array())
{
  $prefix        = '_pzarc_';
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
                                                    'caption' => array('width' => 100, 'show' => false),
                                                    'content' => array('width' => 100, 'show' => false),
                                                    'meta2'   => array('width' => 100, 'show' => false),
                                                    'meta3'   => array('width' => 100, 'show' => false),
                                                    'custom1' => array('width' => 100, 'show' => false),
                                                    'custom2' => array('width' => 100, 'show' => false),
                                                    'custom3' => array('width' => 100, 'show' => false))),
                  'desc'     => __('Drag and drop to sort the order of your elements. Heights are fluid in cells, so not indicative of how it will look on the page.', 'pzarc')
          ),
          // OMG!! The multiselect is working and I don't know why!!

          array(
                  'name'     => __('Components to show', 'pzarc'),
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
                          'caption' => 'Image Caption',
                          'meta1'   => 'Meta1',
                          'meta2'   => 'Meta2',
                          'meta3'   => 'Meta3',
                          'custom1' => 'Custom Field 1',
                          'custom2' => 'Custom Field 2',
                          'custom3' => 'Custom Field 3',
                          //        'custom4' => 'Custom Field 4',
                  ),
                  'tooltip'  => __('Select which base components to include in this cell layout.', 'pzarc')
          ),
  );
  $meta_boxes[ ] = array(
          'title'   => 'Cell designer',
          'pages'   => 'arc-layouts',
          'fields'  => $fields,
          'context' => 'normal',
  );

  return $meta_boxes;
}

add_filter('cmb_meta_boxes', 'pzarc_cell_general_Settings');
function pzarc_cell_general_settings($meta_boxes = array())
{
  $prefix        = '_pzarc_';
  $fields        = array(

          array(
                  'name'    => __('Short name', 'pzarc'),
                  'id'      => $prefix . 'layout-short-name',
                  'type'    => 'text',
                  'cols'    => 12,
                  'tooltip' => __('A short name for this cell layout to identify it.', 'pzarc'),
          ),

          //    array(
          //      'name'    => __('Content', 'pzarc'),
          //      'id'      => $prefix . 'layout-cells-content',
          //      'type'    => 'pzselect',
          //      'default' => 'post',
          //      'cols'    => 12,
          //      'tooltip' => __('Select the content type to display in these cells.', 'pzarc'),
          //      'options' => array(
          //        'post'       => 'Posts',
          //        'page'       => 'Pages',
          //        'attachment' => 'Attachments'
          //      )
          //    ),
          array(
                  'name'    => __('Save cell layout', 'pzarc'),
                  'id'      => $prefix . 'layout-set-save',
                  'type'    => 'pzsubmit',
                  'default' => 'Save'
          ),

  );
  $meta_boxes[ ] = array(
          'title'    => 'General Settings',
          'pages'    => 'arc-layouts',
          'fields'   => $fields,
          'context'  => 'side',
          'priority' => 'high'

  );

  return $meta_boxes;
}

add_filter('cmb_meta_boxes', 'pzarc_cell_designer_settings_meta');
function pzarc_cell_designer_settings_meta($meta_boxes = array())
{
  $prefix = '_pzarc_';

  $fields        = array(


    //    array(
    //      'name'     => __('Components to show', 'pzarc'),
    //      'id'       => $prefix . 'layout-show',
    //      'type' => 'group',
    //      'fields' => array(
    //        array('name'=>'Title','type'=>'checkbox','id'=>$prefix.'show-title','default'=>true),
    //        array('name'=>'Excerpt','type'=>'checkbox','id'=>$prefix.'show-excerpt','default'=>true),
    //        array('name'=>'Content','type'=>'checkbox','id'=>$prefix.'show-content','default'=>false)
    //      ),
    //    ),
    array(
            'name'    => __('Excerpt/Content featured image', 'pzarc'),
            'id'      => $prefix . 'layout-excerpt-thumb',
            'cols'    => 12,
            'type'    => 'pzselect',
            'default' => 'none',
            'options' => array(
                    'none'  => 'No image',
                    'left'  => 'Image left',
                    'right' => 'Image right',
            ),
            'tooltip' => __('Set the alignment of the image to show it in the excerpt or the content. This will use the image settings', 'pzarc')
    ),
    array(
            'name'    => __('Background Feature Image/Video', 'pzarc'),
            'id'      => $prefix . 'layout-background-image',
            'cols'    => 12,
            'type'    => 'pzselect',
            'default' => 'none',
            'options' => array(
                    'none'  => 'No image',
                    'fill'  => 'Fill the cell',
                    'align' => 'Align with components area',
            ),
            'tooltip' => __('Select how to display the featured image or video as the background.', 'pzarc')
    ),


    array(
            'name'    => __('Components area position', 'pzarc'),
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
            //'desc'     => __('Position for all the components as a group', 'pzarc')
    ),
    array(
            'name'    => __('Nudge components area up/down', 'pzarc'),
            'id'      => $prefix . 'layout-sections-nudge-y',
            'cols'    => 12,
            'type'    => 'pzrange',
            'default'=>'0',
            'min'     => '0',
            'max'     => '100',
            'step'    => '1',
            'suffix'  => '%',
            'tooltip' => __('Enter percent to move the components area up/down. Note: These measurements are percentage of the cell.', 'pzarc')
    ),
    array(
            'name'    => __('Nudge components area left/right', 'pzarc'),
            'id'      => $prefix . 'layout-sections-nudge-x',
            'type'    => 'pzrange',
            'default'=>'0',
            'cols'    => 12,
            'default' => '0',
            'min'     => '0',
            'max'     => '100',
            'step'    => '1',
            'suffix'  => '%',
            'tooltip' => __('Enter percent to move the components area left/right. Note: These measurements are percentage of the cell.', 'pzarc')
    ),
    array(
            'name'    => __('Components area width', 'pzarc'),
            'id'      => $prefix . 'layout-sections-widths',
            'type'    => 'pzrange',
            'cols'    => 12,
            'default' => '100',
            'alt'     => 'zones',
            'min'     => '0',
            'max'     => '100',
            'step'    => '1',
            'suffix'  => '%',
            'tooltip' => __('Set the overall width for the components area. Necessary for left or right positioning of sections', 'pzarc'),
            //      'help'    => __('Note:The sum of the width and the left/right nudge should equal 100', 'pzarc')
    ),
    array(
            'name'    => __('Cell Height Type', 'pzarc'),
            'id'      => $prefix . 'layout-cell-height-type',
            'cols'    => 12,
            'type'    => 'pzselect',
            'default' => 'fluid',
            'options' => array(
                    'fluid' => 'Fluid',
                    'fixed' => 'Fixed',
            ),
            'tooltip' => __('Choose whether to set the height of the cells (fixed), or allow them to adjust to the content height (fluid).', 'pzarc')
    ),
    // Hmm? How's this gunna sit with the min-height in templates?
    // We will want to use this for image height cropping when behind.
    array(
            'name'    => __('Cell Height', 'pzarc'),
            'id'      => $prefix . 'layout-cell-height',
            'type'    => 'pzspinner',
            'cols'    => 6,
            'default' => '350',
            'min'     => '0',
            'max'     => '9999',
            'step'    => '1',
            'suffix'  => 'px',
            'tooltip' => __('If using fixed height, set height for the cell.', 'pzarc'),
    ),
    array(
            'name'    => __('Components Height', 'pzarc'),
            'id'      => $prefix . 'layout-components-height',
            'type'    => 'pzspinner',
            'cols'    => 6,
            'default' => '100',
            'min'     => '0',
            'max'     => '9999',
            'step'    => '1',
            'suffix'  => 'px',
            'tooltip' => __('If using fixed height, set height for the components area.', 'pzarc'),
    ),


  );
  $meta_boxes[ ] = array(
          'title'    => 'Cell Designer settings',
          'pages'    => 'arc-layouts',
          'fields'   => $fields,
          'context'  => 'side',
          'priority' => 'default'

  );

  return $meta_boxes;
}


add_filter('cmb_meta_boxes', 'pzarc_cell_formats_meta');
function pzarc_cell_formats_meta($meta_boxes = array())
{
  $prefix        = '_pzarc_';
  $fields        = array(
          array(
                  'id'   => $prefix . 'layout-styling-header',
                  'name' => 'Styling',
                  'type' => 'title',
                  'cols' => 12,
                  'desc' => __('Architect uses standard WordPress class names as much as possible, so your Architect Blueprints will inherit styling from your theme if it uses these. Below you can add your own styling and classes. Enter CSS declarations, such as: background:#123; color:#abc; font-size:1.6em; padding:1%;', 'pzarc') . '<br/>' . __('As much as possible, use fluid units (%,em) if you want to ensure maximum responsiveness.', 'pzarc') . '<br/>' .
                          __('The base font size is 10px. So, for example, to get a font size of 14px, use 1.4em. Even better is using relative ems i.e. rem.')
          ),
          array(
                  'name' => __('Cells', 'pzarc'),
                  'id'   => $prefix . 'layout-format-cells',
                  'type' => 'textarea',
                  'rows' => 1,
                  'cols' => 6,
                  'help' => 'Declarations only for class: .pzarc_cells',
          ),
          array(
                  'name'    => __('Cells Classes', 'pzarc'),
                  'id'      => $prefix . 'layout-format-cells-classes',
                  'type'    => 'text',
                  'cols'    => 6,
                  'default' => '.pzarc-cells',
          ),
          array(
                  'name' => __('Entry section', 'pzarc'),
                  'id'   => $prefix . 'layout-format-entry',
                  'type' => 'textarea',
                  'rows' => 1,
                  'cols' => 6,
                  'help' => 'Declarations only for class: .hentry',
          ),
          array(
                  'name'    => __('Entry Classes', 'pzarc'),
                  'id'      => $prefix . 'layout-format-entry-classes',
                  'type'    => 'text',
                  'cols'    => 6,
                  'default' => '.hentry',
          ),
          array(
                  'name' => __('Components group', 'pzarc'),
                  'id'   => $prefix . 'layout-format-components-group',
                  'type' => 'textarea',
                  'rows' => 1,
                  'cols' => 6,
                  'help' => 'Declarations only for class: .pzarc_components',
          ),
          array(
                  'name'    => __('Components Group Classes', 'pzarc'),
                  'id'      => $prefix . 'layout-format-components-group-classes',
                  'type'    => 'text',
                  'rows'    => 1,
                  'cols'    => 6,
                  'default' => '.pzarc-components',
          ),
          array(
                  'name' => __('Entry title', 'pzarc'),
                  'id'   => $prefix . 'layout-format-entry-title',
                  'type' => 'textarea',
                  'rows' => 1,
                  'cols' => 6,
                  'help' => 'Declarations only for class: .pzarc_entry_title and .pzarc_entry_title a',
                  //      'desc'    => __('Format the entry title', 'pzarc')
          ),
          array(
                  'name'    => __('Entry Title Classes', 'pzarc'),
                  'id'      => $prefix . 'layout-format-entry-title-classes',
                  'type'    => 'text',
                  'cols'    => 6,
                  'default' => '.entry-title, .entry-title a',
          ),
          array(
                  'name' => __('Entry title hover', 'pzarc'),
                  'id'   => $prefix . 'layout-format-entry-title-hover',
                  'type' => 'textarea',
                  'rows' => 1,
                  'cols'    => 6,

                  'help' => 'Declarations only for class: .pzarc_entry_title a:hover',
                  //      'desc'    => __('Format the entry title link hover', 'pzarc')
          ),
         array(
                  'name' => __('Entry title hover classes', 'pzarc'),
                  'id'   => $prefix . 'layout-format-entry-title-hover-classes',
                  'type' => 'textarea',
                  'rows' => 1,
                  'cols'    => 6,
          ),
          array(
                  'name' => __('Entry meta', 'pzarc'),
                  'id'      => $prefix . 'layout-format-entry-meta',
                  'type' => 'textarea',
                  'rows' => 1,
                  'cols' => 6,
                  'help' => 'Declarations only for class: .pzarc_entry_meta',
                  //     'desc'    => __('Format the entry meta', 'pzarc')
          ),
          array(
                  'name'    => __('Entry Meta Classes', 'pzarc'),
                  'id'      => $prefix . 'layout-format-entry-meta-classes',
                  'type'    => 'text',
                  'rows'    => 1,
                  'cols'    => 6,
                  'default' => ' .entry-meta',
          ),
          array(
                  'name' => __('Entry meta links', 'pzarc'),
                  'id'   => $prefix . 'layout-format-entry-meta-link',
                  'type' => 'textarea',
                  'rows' => 1,
                  'cols' => 6,

                  'help' => 'Declarations only for class: .pzarc_entry_meta a',
                  //     'desc'    => __('Format the entry meta link', 'pzarc')
          ),
          array(
                  'name' => __('Entry meta links classes', 'pzarc'),
                  'id'   => $prefix . 'layout-format-entry-meta-link-classes',
                  'type' => 'textarea',
                  'rows' => 1,
                  'cols' => 6,

          ),
          array(
                  'name' => __('Entry meta link hover', 'pzarc'),
                  'id'   => $prefix . 'layout-format-entry-meta-link-hover',
                  'type' => 'textarea',
                  'rows' => 1,
                  'cols' => 6,

                  'help' => 'Declarations only for class: .pzarc_entry_meta a:hover',
                  //     'desc'    => __('Format the entry meta link hover', 'pzarc')
          ),
          array(
                  'name' => __('Entry meta link hover classes', 'pzarc'),
                  'id'   => $prefix . 'layout-format-entry-meta-link-hover-classes',
                  'type' => 'textarea',
                  'rows' => 1,
                  'cols' => 6,
          ),
          array(
                  'name' => __('Entry content', 'pzarc'),
                  'id'   => $prefix . 'layout-format-entry-content',
                  'type' => 'textarea',
                  'rows' => 1,
                  'cols' => 6,

                  'help' => 'Declarations only for class: .pzarc_entry_content',
                  //     'desc'    => __('Format the entry content', 'pzarc')
          ),
          array(
                  'name' => __('Entry content classes', 'pzarc'),
                  'id'   => $prefix . 'layout-format-entry-content-classes',
                  'type' => 'textarea',
                  'rows' => 1,
                  'cols' => 6,

          ),
          array(
                  'name' => __('Entry content links', 'pzarc'),
                  'id'   => $prefix . 'layout-format-entry-content-links',
                  'type' => 'textarea',
                  'rows' => 1,
                  'cols' => 6,

                  'help' => 'Declarations only for class: .pzarc_entry_content a',
                  //     'desc'    => __('Format the entry content', 'pzarc')
          ),
          array(
                  'name' => __('Entry content links classes', 'pzarc'),
                  'id'   => $prefix . 'layout-format-entry-content-links-classes',
                  'type' => 'textarea',
                  'rows' => 1,
                  'cols' => 6,

          ),
          array(
                  'name' => __('Entry content link hover', 'pzarc'),
                  'id'   => $prefix . 'layout-format-entry-content-link-hover',
                  'type' => 'textarea',
                  'rows' => 1,
                  'cols' => 6,

                  'help' => 'Declarations only for class: .pzarc_entry_content a:hover',
                  //     'desc'    => __('Format the entry content link hover', 'pzarc')
          ),
          array(
                  'name' => __('Entry content link hover classes', 'pzarc'),
                  'id'   => $prefix . 'layout-format-entry-content-link-hover-classes',
                  'type' => 'textarea',
                  'rows' => 1,
                  'cols' => 6,

          ),
          array(
                  'name' => __('Entry featured image', 'pzarc'),
                  'id'   => $prefix . 'layout-format-entry-fimage',
                  'type' => 'textarea',
                  'rows' => 1,
                  'cols' => 6,

                  'help' => 'Declarations only for class: .pzarc_entry_featured_image',
                  //     'desc'    => __('Format the entry featured image', 'pzarc')
          ),
          array(
                  'name' => __('Entry featured image classes', 'pzarc'),
                  'id'   => $prefix . 'layout-format-entry-fimage-classes',
                  'type' => 'textarea',
                  'rows' => 1,
                  'cols' => 6,

          ),
          array(
                  'name' => __('Read more', 'pzarc'),
                  'id'   => $prefix . 'layout-format-entry-readmore',
                  'type' => 'textarea',
                  'rows' => 1,
                  'cols' => 6,

                  'help' => 'Declarations only for class: a.pzarc_readmore',
                  //     'desc'    => __('Format the content "Read more" link', 'pzarc')
          ),
          array(
                  'name' => __('Read more classes', 'pzarc'),
                  'id'   => $prefix . 'layout-format-entry-readmore-classes',
                  'type' => 'textarea',
                  'rows' => 1,
                  'cols' => 6,

          ),
          array(
                  'name' => __('Read more hover', 'pzarc'),
                  'id'   => $prefix . 'layout-format-entry-readmore-hover',
                  'type' => 'textarea',
                  'rows' => 1,
                  'cols' => 6,

                  'help' => 'Declarations only for class: a.pzarc_readmore:hover',
                  //     'desc'    => __('Format the content "Read more" link hover', 'pzarc')
          ),
          array(
                  'name' => __('Read more hover classes', 'pzarc'),
                  'id'   => $prefix . 'layout-format-entry-readmore-hover-classes',
                  'type' => 'textarea',
                  'rows' => 1,
                  'cols' => 6,
          ),
          array(
                  'name' => __('Image caption', 'pzarc'),
                  'id'   => $prefix . 'layout-format-image-caption',
                  'type' => 'textarea',
                  'rows' => 1,
                  'cols' => 6,
          ),
          array(
                  'name' => __('Image caption classes', 'pzarc'),
                  'id'   => $prefix . 'layout-format-image-caption-classes',
                  'type' => 'textarea',
                  'rows' => 1,
                  'cols' => 6,
                  'default' => 'figcaption.caption'
          ),


  );
  $meta_boxes[ ] = array(
          'title'  => 'Styling',
          'pages'  => 'arc-layouts',
          'fields' => $fields
  );

  return $meta_boxes;
}

/*
*
* SETTINGS
* 
 */
add_filter('cmb_meta_boxes', 'pzarc_cell_settings_meta');
function pzarc_cell_settings_meta($meta_boxes = array())
{
  $prefix = '_pzarc_';
  $fields = array(
          /// TITLES
          array(
                  'id'   => $prefix . 'cell-settings-title',
                  'name' => 'Title',
                  'type' => 'title'
          ),
          array(
                  'name'    => __('Title prefix', 'pzarc'),
                  'id'      => $prefix . 'cell-settings-title-prefix',
                  'type'    => 'select',
                  'cols'    => 2,
                  'default' => 'none',
                  'options' => array('none' => 'None', 'bullet' => 'Bullet', 'thumb' => 'Thumbnail'),
          ),
          array(
                  'name'    => __('Link titles', 'pzarc'),
                  'id'      => $prefix . 'cell-settings-link-titles',
                  'type'    => 'checkbox',
                  'cols'    => 4,
                  /// can't set defaults on checkboxes!
          ),

          // ======================================
          // META
          // ======================================
          array(
                  'id'   => $prefix . 'cell-settings-meta1',
                  'name' => 'Meta',
                  'type' => 'title',
                  'desc'  => 'Include any text, field tags, and simple HTML tags. Available field tags: %date%, %author%, %editlink%, %categories%, %tags%, %commentcount%',
          ),
          array(
                  'name'     => __('Meta1 config', 'pzarc'),
                  'id'       => $prefix . 'cell-settings-meta1-config',
                  'type'     => 'textarea',
                  'cols'     => 4,
                  'rows'    => 2,
                  'default'  => '%date%, %author%',
          ),
          array(
                  'name'     => __('Meta2 config', 'pzarc'),
                  'id'       => $prefix . 'cell-settings-meta2-config',
                  'type'     => 'textarea',
                  'cols'     => 4,
                  'rows'    => 2,
                  'default'  => '%categories%, %tags%',
          ),
          array(
                  'name'     => __('Meta3 config', 'pzarc'),
                  'id'       => $prefix . 'cell-settings-meta3-config',
                  'type'     => 'textarea',
                  'cols'     => 4,
                  'rows'    => 2,
                  'default'  => '%commentcount%   %editlink%',
          ),
          array(
                  'id'      => $prefix . 'cell-settings-meta-date-format',
                  'name'    => 'Date format',
                  'type'    => 'text',
                  'default' => 'l, F j, Y g:i a',
                  'cols'    => 4,
                  'desc' => __('See here for information on <a href="http://codex.wordpress.org/Formatting_Date_and_Time" target=_blank>formatting date and time</a>','pzarc')
          ),
          // EXCERPTS
          array(
                  'id'   => $prefix . 'cell-settings-excerpt',
                  'name' => 'Excerpt',
                  'type' => 'title'
          ),
          array(
                  'id'      => $prefix . 'cell-settings-excerpts-word-count',
                  'name'    => 'Excerpt length (words)',
                  'type'    => 'text_small',
                  'default' => 55,
                  'cols'    => 3
          ),
          array(
                  'name'    => __('Truncation indicator', 'pzarc'),
                  'id'      => $prefix . 'cell-settings-excerpts-morestr',
                  'type'    => 'text_small',
                  'cols'    => 3,
                  'default' => '[...]',
          ),
          array(
                  'name'    => __('Read More', 'pzarc'),
                  'id'      => $prefix . 'cell-settings-excerpts-linkmore',
                  'type'    => 'text',
                  'cols'    => 3,
                  'default' => 'Read more',
          ),

          // IMAGE
          array(
                  'id'   => $prefix . 'cell-settings-image',
                  'name' => 'Image',
                  'type' => 'title',
                  'desc'=> 'Left and right margins are allowed for in the image width. e.g if Image width is 25% and right margin is 3%, image width will be adjusted to 22%'
          ),
          array(
                  'name' => __('Link image', 'pzarc'),
                  'id'   => $prefix . 'cell-settings-link-image',
                  'type' => 'checkbox',
                  'cols' => 3,
          ),
          array(
                  'name' => __('Image Captions', 'pzarc'),
                  'id'   => $prefix . 'cell-settings-image-captions',
                  'type' => 'checkbox',
                  'cols' => 3,
          ),
          array(
                  'id'      => $prefix . 'cell-settings-image-max-width',
                  'name'    => 'Image max width',
                  'type'    => 'text_small',
                  'default' => '300',
                  'cols'    =>3
          ),
          array(
                  'id'      => $prefix . 'cell-settings-image-max-height',
                  'name'    => 'Image max height',
                  'type'    => 'text_small',
                  'default' => '250',
                  'cols'    => 3
          ),
          array(
                  'id'      => $prefix . 'cell-settings-image-margin-top',
                  'name'    => 'Margin top %',
                  'type'    => 'text_small',
                  'default' => '',
                  'cols'    => 3
          ),
          array(
                  'id'      => $prefix . 'cell-settings-image-margin-bottom',
                  'name'    => 'Margin bottom %',
                  'type'    => 'text_small',
                  'default' => '',
                  'cols'    => 3
          ),
          array(
                  'id'      => $prefix . 'cell-settings-image-margin-left',
                  'name'    => 'Margin left %',
                  'type'    => 'text_small',
                  'default' => '',
                  'cols'    => 3
          ),
          array(
                  'id'      => $prefix . 'cell-settings-image-margin-right',
                  'name'    => 'Margin right %',
                  'type'    => 'text_small',
                  'default' => '',
                  'cols'    => 3
          ),

          // CONTENT
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
          'pages'   => 'arc-layouts',
          'context' => 'normal',
          'fields'  => $fields
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
	  <p class="pzarc-states pzcentred">Loading</p>
	  <p class="howto pzcentred"><strong style="color:#d00;">This is an example only and thus only a <span style="border-bottom: 3px double;">general guide</span> to how the cells will look.</strong></p>
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
add_action('save_post', 'save_arc_layouts',99);
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
        [id] => arc-layouts
        [*in_admin] => site
        [is_network] =>
        [is_user] =>
        [parent_base] =>
        [parent_file] =>
        [post_type] => arc-layouts
        [taxonomy] =>
        [WP_Screen_help_tabs] => Array()
        [WP_Screen_help_sidebar] =>
        [WP_Screen_options] => Array()
        [WP_Screen_show_screen_options] =>
        [WP_Screen_screen_settings] =>
    )
   */
  if ($screen->id == 'arc-layouts')
  {
    // save the CSS too
    // new wp_filesystem
    // create file named with id e.g. pzarc-cell-layout-123.css
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
    $filename   = trailingslashit($upload_dir[ 'basedir' ]) . '/cache/pizazzwp/arc/pzarc-cell-layout-' . $postid . '.css';
    wp_mkdir_p(trailingslashit($upload_dir[ 'basedir' ]) . '/cache/pizazzwp/arc/');

    // Need to create the file contents
 
///pzdebug($filename);
   $pzarc_contents = pzarc_create_css($postid,'cells');

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