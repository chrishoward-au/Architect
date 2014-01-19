<?php

class pzarc_Blueprints
{

  private $mb_fields;

  /**
   * [__construct description]
   */
  function __construct()
  {
    add_action('init', array($this, 'create_blueprints_post_type'));
    // This overrides the one in the parent class

    if (is_admin())
    {

      //	add_action('admin_init', 'pzarc_preview_meta');
//      add_action('add_meta_boxes', array($this, 'blueprints_meta'));
//      add_action('add_meta_boxes', 'blueprints_meta');
      add_action('admin_head', array($this, 'content_blueprints_admin_head'));
      add_action('admin_enqueue_scripts', array($this, 'content_blueprints_admin_enqueue'));
      add_filter('manage_arc-blueprints_posts_columns', array($this, 'add_blueprint_columns'));
      add_action('manage_arc-blueprints_posts_custom_column', array($this, 'add_blueprint_column_content'), 10, 2);

      // check screen arc-blueprints. ugh. doesn't work for save and edit
//			if ( $_REQUEST[ 'post_type' ] == 'arc-blueprints' )
//			{
//			}
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


      wp_enqueue_style('pzarc-admin-blueprints-css', PZARC_PLUGIN_URL . 'admin/css/arc-admin-blueprints.css');

      wp_enqueue_script('jquery-pzarc-metaboxes-blueprints', PZARC_PLUGIN_URL . 'admin/js/arc-metaboxes-blueprints.js', array('jquery'));
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
    $pzarc_front  = array_slice($columns, 0, 2);
    $pzarc_back   = array_slice($columns, 2);
    $pzarc_insert =
            array
            (
                    '_pzarc_blueprint-short-name' => __('Blueprint short name', 'pzsp'),
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
    switch ($column)
    {
      case '_pzarc_blueprint-short-name':
        echo get_post_meta($post_id, '_pzarc_blueprint-short-name', true);
        break;
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


//add_filter('cmb_meta_boxes', 'pzarc_blueprint_wizard_metabox');
function pzarc_blueprint_wizard_metabox($meta_boxes = array())
{
  $prefix        = '_pzarc_';
  $fields        = array(
          array(
                  'name'    => 'Select what type of blueprint you want to make',
                  'id'      => $prefix . 'blueprint-wizard',
                  'type'    => 'radio',
                  'default' => 'custom',
                  'desc'    => 'Select one to quickly enable relevant settings, or custom to build your own from scratch with all settings and defaults. Minimize this metabox once you are happy with your selection.',
                  'options' => array(
                          'custom'       => 'Custom',
                          'fullplusgrid' => 'Full + grid',
                          'gallery'      => 'Gallery',
                          'grid'         => 'Grid',
                          'slider'       => 'Content slider',
                          'tabbed'       => 'Tabbed',
                  )
          )
  );
  $meta_boxes[ ] = array(
          'title'    => 'What do you want to do?',
          'pages'    => 'arc-blueprints',
          'context'  => 'normal',
          'priority' => 'high',
          'fields'   => $fields // An array of fields.
  );

  return $meta_boxes;
}

add_filter('cmb_meta_boxes', 'pzarc_blueprint_sections_tabbed');
function pzarc_blueprint_sections_tabbed($meta_boxes = array())
{
  $prefix        = '_pzarc_';
  $fields        = array(
          array(
                  'name'     => __('Tabs', 'pzarc'),
                  'id'       => $prefix . 'layout-blueprint-sections-tabs',
                  'type'     => 'pztabs',
                  'defaults' => array('#blueprint-section-1' => 'Blueprint Section 1', '#blueprint-section-2' => 'Blueprint Section 2', '#blueprint-section-3' => 'Blueprint Section 3','#blueprint-wireframe-preview' => 'Wireframe Preview'),
          ),

  );
  $meta_boxes[ ] = array(
          'title'    => 'Blueprint Sections',
          'pages'    => 'arc-blueprints',
          'context'  => 'normal',
          'priority' => 'high',
          'fields'   => $fields // An array of fields.
  );

  return $meta_boxes;

}

add_filter('cmb_meta_boxes', 'pzarc_blueprint_main');
function pzarc_blueprint_main($meta_boxes = array())
{
  $prefix        = '_pzarc_';
  $fields        = array(
          array(
                  'id'   => $prefix . 'blueprint-short-name',
                  'name' => __('Blueprint Short Name', 'pzarc'),
                  'type' => 'text',

                  'desc' => __('Alphanumeric only. <br/>Use the shortcode <strong class="pzarc-usage-info">[pzarc "<span class="pzarc-shortname"></span>"]</strong> or the template tag <strong class="pzarc-usage-info">pzarc(\'<span class="pzarc-shortname"></span>\');</strong>', 'pzarc')
          ),
          array(
                  'name'    => 'Save blueprint',
                  'id'      => $prefix . 'layout-set-save',
                  'type'    => 'pzsubmit',
                  'default' => 'Save'
          ),

  );
  $meta_boxes[ ] = array(
          'title'    => 'Blueprint',
          'pages'    => 'arc-blueprints',
          'context'  => 'side',
          'priority' => 'high',
          'fields'   => $fields // An array of fields.
  );

  return $meta_boxes;

}
add_filter('cmb_meta_boxes', 'pzarc_blueprint_parts_switcher');
function pzarc_blueprint_parts_switcher($meta_boxes = array())
{
  $prefix        = '_pzarc_';
  $fields        = array(
          array(
                  'name'     => __('Switcher', 'pzarc'),
                  'id'       => $prefix . 'layout-blueprint-parts-switch',
                  'type'     => 'pztabs',
                  'defaults' => array('#contents-selection-settings' => 'Content', '#blueprint-settings' => 'Sections'),
          ),

  );
  $meta_boxes[ ] = array(
          'title'    => 'Show settings for',
          'pages'    => 'arc-blueprints',
          'context'  => 'side',
          'priority' => 'high',
          'fields'   => $fields // An array of fields.
  );

  return $meta_boxes;

}


add_filter('cmb_meta_boxes', 'pzarc_blueprint_preview_metabox');
function pzarc_blueprint_preview_metabox($meta_boxes = array())
{
  // Need to redesign this into one layout that includes navigation positions
  $prefix = '_pzarc_';
  $fields = array(

//    array(
//      'id'       => $prefix . 'sections-preview-desc',
//      'type' => 'pzinfo',
//      'desc' => __('The preview gives you a feel for how you layout might look only.','pzarc')
//    ),
array(
        'name'     => 'Sections',
        'id'       => $prefix . 'sections-preview',
        'cols'     => 12,
        'type'     => 'pzlayout',
        'readonly' => false, // Readonly fields can't be written to by code! Weird
        'code'     => draw_sections_preview(),
),
  );

  $meta_boxes[ ] = array(
          'title'    => 'Blueprint Wireframe Preview',
          'pages'    => 'arc-blueprints',
          'fields'   => $fields,
          'context'  => 'normal',
          'priority' => 'default'

  );

  return $meta_boxes;
}


add_filter('cmb_meta_boxes', 'pzarc_sections_preview_meta');
function pzarc_sections_preview_meta($meta_boxes = array())
{
  $prefix = '_pzarc_';

  $args = array(
          'posts_per_page'   => -1,
          'orderby'          => 'title',
          'order'            => 'ASC',
          'post_type'        => 'arc-layouts',
          'suppress_filters' => true);

  $pzarc_cells       = get_posts($args);
  $pzarc_cells_array = array();
  if (!empty($pzarc_cells))
  {
    foreach ($pzarc_cells as $pzarc_cell)
    {
      $pzarc_cells_array[ $pzarc_cell->ID ] = (empty($pzarc_cell->post_title) ? 'No title' : $pzarc_cell->post_title);
    }
  }
  else
  {
    $pzarc_cells_array = array(0 => 'No cell layouts. Create some.');
  }
  // ID,post_title
  for ($i = 0; $i < 3; $i++)
  {
    $fields        = array(

            array(
                    'id'   => $prefix . $i . '-blueprint-section-title',
                    'name' => __('Section ' . ($i + 1) . ' title (optional)', 'pzarc'),
                    'type' => 'text',
                    'cols' => 12,
            ),
            array(
                    'id'      => $prefix . $i . '-blueprint-section-cell-layout',
                    'name'    => __('Panels layout', 'pzarc'),
                    'type'    => 'pzselect',
                    'cols'    => 6,

                    'options' => $pzarc_cells_array
            ),
            array(
                    'name'    => __('Layout mode', 'pzarc'),
                    'id'      => $prefix . $i . '-blueprint-layout-mode',
                    'type'    => 'pzselect',
                    'default' => 'fitRows',
                    'cols'    => 6,
                    'options' => array(
                            'basic'   => 'Basic (CSS only)',
                            //          'fitRows'         => 'Fit rows',
                            //          'fitColumns'      => 'Fit columns',
                            'masonry' => 'Masonry (Pinterest-like)',
                            //          'masonryVertical' => 'Masonry Vertical',
                            //          'cellsByRow'      => 'Cells by row',
                            //          'cellsByColumn'   => 'Cells by column',
                            //          'vertical'    => 'Vertical',
                            //          'horizontal'  => 'Horizontal',
                    ),
                    //       'desc'    => __('Choose how you want the cells to display. With evenly sized cells, you\'ll see little difference. Please visit <a href="http://isotope.metafizzy.co/demos/layout-modes.html" target=_blank>Isotope Layout Modes</a> for demonstrations of these layouts', 'pzarc')
            ),
            array(
                    'name'    => __('Panels to show', 'pzarc'),
                    'id'      => $prefix . $i . '-blueprint-cells-per-view',
                    'type'    => 'pzspinner',
                    'default' => 0,
                    'min'     => 0,
                    'max'     => 99,
                    'cols'    => 2,
                    'desc'    => '0 for unlimited'
            ),
            array(
                    'name'    => __('Panels across', 'pzarc'),
                    'id'      => $prefix . $i . '-blueprint-cells-across',
                    'type'    => 'pzspinner',
                    'default' => 3,
                    'min'     => 1,
                    'cols'    => 2,
                    'max'     => 999,
            ),
            array(
                    'name'    => __('Minimum cell width', 'pzarc'),
                    'id'      => $prefix . $i . '-blueprint-min-cell-width',
                    'type'    => 'pzspinner',
                    'alt'     => 'mincellw',
                    'default' => 0,
                    'min'     => '0',
                    'max'     => 9999,
                    'cols'    => 2,
                    'step'    => '1',
                    'suffix'  => 'px',
                    //      'desc'    => __('Set the minimum width for sells in this section. This helps with responsive layout', 'pzarc')
            ),

            array(
                    'name'    => __('Panels vertical margin', 'pzarc'),
                    'id'      => $prefix . $i . '-blueprint-cells-vert-margin',
                    'type'    => 'pzspinner',
                    'alt'     => 'gutterv',
                    'default' => '1',
                    'min'     => '0',
                    'max'     => '100',
                    'cols'    => 3,
                    'step'    => '1',
                    'suffix'  => '%',
                    //    'desc'    => __('Set the vertical gutter width as a percentage of the section width. The gutter is the gap between adjoining elements', 'pzarc')
            ),
            array(
                    'name'    => __('Panels horizontal margin', 'pzarc'),
                    'id'      => $prefix . $i . '-blueprint-cells-horiz-margin',
                    'type'    => 'pzspinner',
                    'alt'     => 'gutterh',
                    'default' => '1',
                    'min'     => '0',
                    'max'     => '100',
                    'cols'    => 3,
                    'step'    => '1',
                    'suffix'  => '%',
                    //      'desc'    => __('Set the horizontal gutter width as a percentage of the section width. The gutter is the gap between adjoining elements', 'pzarc')
            ),


    );
    $meta_boxes[ ] = array(
            'title'    => 'Blueprint Section ' . ($i + 1),
            'pages'    => 'arc-blueprints',
            'context'  => 'normal',
            'priority' => 'high',
            'fields'   => $fields // An array of fields.
    );
  }

  return $meta_boxes;
}

add_filter('cmb_meta_boxes', 'pzarc_blueprint_pagination_metabox');
function pzarc_blueprint_pagination_metabox($meta_boxes = array())
{
  // Need to redesign this into one layout that includes navigation positions
  $prefix        = '_pzarc_';
  $fields        = array(
          array(
                  'id'      => $prefix . 'blueprint-pager',
                  'name'    => __('Pagination', 'pzarc'),
                  'type'    => 'pzselect',
                  'cols'    => 12,
                  'default' => 'wppagination',
                  'options' => array(
                          'none'       => 'None',
                          'names'  => 'Post names',
                          'prevnext'=>'Previous/Next',
                          'wppagenavi' => 'PageNavi',
                  )
          ),
          array(
                  'id'      => $prefix . 'blueprint-pager-location',
                  'name'    => __('Pagination location', 'pzarc'),
                  'type'    => 'pzselect',
                  'cols'    => 12,
                  'default' => 'bottom',
                  'options' => array(
                          'bottom'       => 'Bottom',
                          'top'  => 'Top',
                          'both'=>'Both'
                  )
          ),
          array(
                  'id'      => $prefix . 'blueprint-posts-per-page',
                  'name'    => __('Posts per page', 'pzarc'),
                  'type'    => 'text',
                  'cols'    => 12,
                  'default' => 'Do we really need this? Can\'t we just use the total panels per section?',
          ),
  );
  $meta_boxes[ ] = array(
          'title'    => 'Blueprint Pagination',
          'pages'    => 'arc-blueprints',
          'context'  => 'side',
          'priority' => 'default',
          'fields'   => $fields // An array of fields.
  );

  return $meta_boxes;

}

add_filter('cmb_meta_boxes', 'pzarc_blueprint_navigator_metabox');
function pzarc_blueprint_navigator_metabox($meta_boxes = array())
{
  // Need to redesign this into one layout that includes navigation positions
  $prefix        = '_pzarc_';
  $fields        = array(

          array(
                  'id'      => $prefix . '-blueprint-navigation',
                  'name'    => __('Navigator Type', 'pzarc'),
                  'type'    => 'pzselect',
                  'cols'    => 12,
                  'default' => 'none',
                  'options' => array(
                          'none'          => 'None',
                          'player'        => 'Media Player buttons',
                          'titles'        => 'Titles (accordion)',
                          'titles'        => 'Titles (tabbed)',
                          'bullets'       => 'Bullets',
                          'numbers'       => 'Numbers',
                          'thumbs'        => 'Thumbnails',
                          'thumbsbuttons' => 'Thumbnails + buttons'
                  )
          ),
          array(
                  'name'    => 'Navigator Position',
                  'id'      => $prefix . '-blueprint-nav-pos',
                  'type'    => 'pzselect',
                  'cols'    => 6,
                  'default' => 'bottom',
                  'options' => array(
                          'left'   => 'Left',
                          'right'  => 'Right',
                          'top'    => 'Top',
                          'bottom' => 'Bottom',
                  )
          ),
          array(
                  'name'    => 'Navigator Location',
                  'id'      => $prefix . '-blueprint-nav-loc',
                  'type'    => 'pzselect',
                  'cols'    => 6,
                  'default' => 'outside',
                  'options' => array(
                          'inside'  => 'Inside',
                          'outside' => 'Outside',
                  )
          ),
          array(
                  'name'    => 'Navigator Pager',
                  'id'      => $prefix . '-blueprint-nav-loc',
                  'type'    => 'pzselect',
                  'cols'    => 6,
                  'default' => 'none',
                  'options' => array(
                          'none'   => 'None',
                          'hover'  => 'Hover over panels',
                          'inline' => 'Inline with navigator'
                  )
          ),
          array(
                  'id'      => $prefix . 'blueprint-nav-items-visible',
                  'name'    => __('Navigator items visible', 'pzarc'),
                  'type'    => 'text',
                  'cols'    => 6,
                  'default' => 0,
                  'desc'    => 'If zero, it will use the "Panels to show" value'
          ),

  );
  $meta_boxes[ ] = array(
          'title'    => 'Blueprint Navigator',
          'pages'    => 'arc-blueprints',
          'context'  => 'side',
          'priority' => 'default',
          'fields'   => $fields // An array of fields.
  );

  return $meta_boxes;

}


add_filter('cmb_meta_boxes', 'pzarc_blueprint_settings_metabox');
function pzarc_blueprint_settings_metabox($meta_boxes = array())
{
  $args = array(
          'posts_per_page'   => -1,
          'orderby'          => 'title',
          'order'            => 'ASC',
          'post_type'        => 'arc-contents',
          'suppress_filters' => true);


  $pzarc_criterias                    = get_posts($args);
  $pzarc_criterias_array[ 'default' ] = 'Page default';
  if (!empty($pzarc_criterias))
  {
    foreach ($pzarc_criterias as $pzarc_criteria)
    {
      $pzarc_criterias_array[ $pzarc_criteria->ID ] = (empty($pzarc_criteria->post_title) ? 'No title' : $pzarc_criteria->post_title);
    }
  }
  $prefix = '_pzarc_';
  $fields = array(
          // array(
          //         'id'      => $prefix . 'blueprint-criteria',
          //         'name'    => __('Criteria', 'pzarc'),
          //         'type'    => 'pzselect',
          //         'cols'    => 12,
          //         'default' => 'default',
          //         'options' => $pzarc_criterias_array
          // ),

          array(
                  'name'    => 'Section 2',
                  'id'      => $prefix . '1-blueprint-section-enable',
                  'type'    => 'checkbox',
                  'cols'    => 6,
                  'default' => false
          ),
          array(
                  'name'    => 'Section 3',
                  'id'      => $prefix . '2-blueprint-section-enable',
                  'type'    => 'checkbox',
                  'cols'    => 6,
                  'default' => false
          ),
          array(
                  'name'    => 'Select what type of navigation to use',
                  'id'      => $prefix . 'blueprint-navigation',
                  'type'    => 'pzselect',
                  'default' => 'none',
                  'desc'    => 'Note: Navigator will only function when one section',
                  'options' => array(
                          'none'      => 'None',
                          'pager'     => 'Pagination',
                          'navigator' => 'Navigator'
                  )
          ),
          array(
                  'name'    => __('Order by', 'pzarc'),
                  'id'      => $prefix . 'contents-orderby',
                  'type'    => 'pzselect',
                  'default' => 'date',
                  'cols'    => 6,
                  'options' => array(
                          'date'  => 'Date',
                          'title' => 'Title',
                  ),
          ),
          array(
                  'name'    => __('Order direction', 'pzarc'),
                  'id'      => $prefix . 'contents-orderdir',
                  'type'    => 'pzselect',
                  'default' => 'DESC',
                  'cols'    => 6,
                  'options' => array(
                          'ASC'  => 'Ascending',
                          'DESC' => 'Descending',
                  ),
          ),
          array(
                  'name' => __('Skip N posts', 'pzarc'),
                  'id'   => $prefix . 'contents-skip',
                  'type' => 'pzspinner',
                  'min'  => 0,
                  'max'  => 9999,
                  'step' => 1,
                  'default'=>0,
                  'desc' => __('Note: Skipping breaks pagination. This is a known WordPress bug.', 'pzarc'),
          ),
          array(
                  'name'    => __('Sticky posts first', 'pzarc'),
                  'id'      => $prefix . 'contents-sticky',
                  'type'    => 'radio',
                  'options' => array('yes'=>'Yes','no'=>'No'),
                  'default' => 'no',
          ),

  );

  $meta_boxes[ ] = array(
          'title'    => 'Blueprint settings',
          'pages'    => 'arc-blueprints',
          'fields'   => $fields,
          'context'  => 'side',
          'priority' => 'high'

  );

  return $meta_boxes;
}


function draw_sections_preview()
{
  // Put in a hidden field with the plugin url for use in js
  $return_html = '
  <div id="pzarc-blueprint-wireframe">
  <div id="pzarc-sections-preview-0" class="pzarc-sections pzarc-section-0"></div>
  <div id="pzarc-sections-preview-1" class="pzarc-sections pzarc-section-1"></div>
  <div id="pzarc-sections-preview-2" class="pzarc-sections pzarc-section-2"></div>
  <div id="pzarc-navigator-preview" class="pzarc-sections pzarc-section-navigator"><span class="dashicons dashicons-arrow-left"></span><span class="dashicons dashicons-editor-justify"></span><span class="dashicons dashicons-editor-justify"></span><span class="dashicons dashicons-editor-justify"></span><span class="dashicons dashicons-arrow-right"></span></div>
	</div>
	<div class="plugin_url" style="display:none;">' . PZARC_PLUGIN_URL . '</div>
	';

  return $return_html;
}
