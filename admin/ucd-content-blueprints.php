<?php

class pzucd_Content_blueprints extends pzucdForm
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

      //	add_action('admin_init', 'pzucd_preview_meta');
//      add_action('add_meta_boxes', array($this, 'blueprints_meta'));
//      add_action('add_meta_boxes', 'blueprints_meta');
      add_action('admin_head', array($this, 'content_blueprints_admin_head'));
      add_action('admin_enqueue_scripts', array($this, 'content_blueprints_admin_enqueue'));
			add_filter('manage_ucd-blueprints_posts_columns', array($this, 'add_blueprint_columns'));
			add_action('manage_ucd-blueprints_posts_custom_column', array($this, 'add_blueprint_column_content'), 10, 2);

      // check screen ucd-blueprints. ugh. doesn't work for save and edit
//			if ( $_REQUEST[ 'post_type' ] == 'ucd-blueprints' )
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
    if ('ucd-blueprints' == $screen->id)
    {


      wp_enqueue_style('pzucd-admin-blueprints-css', PZUCD_PLUGIN_URL . 'admin/css/ucd-admin-blueprints.css');

      wp_enqueue_script('jquery-pzucd-metaboxes-blueprints', PZUCD_PLUGIN_URL . 'admin/js/ucd-metaboxes-blueprints.js', array('jquery'));
      wp_enqueue_script('js-isotope-v2');
     // wp_enqueue_script('jquery-masonary', PZUCD_PLUGIN_URL . 'external/masonry.pkgd.min.js', array('jquery'));
     // wp_enqueue_script('jquery-lorem', PZUCD_PLUGIN_URL . 'external/jquery.lorem.js', array('jquery'));
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
    $pzucd_front  = array_slice($columns, 0, 2);
    $pzucd_back   = array_slice($columns, 2);
    $pzucd_insert =
            array
            (
              '_pzucd_blueprint-short-name' => __('Blueprint short name', 'pzsp'),
            );

    return array_merge($pzucd_front, $pzucd_insert, $pzucd_back);
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
      case '_pzucd_blueprint-short-name':
        echo get_post_meta($post_id, '_pzucd_blueprint-short-name', true);
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
      'menu_name'          => _x('<span class="dashicons dashicons-screenoptions"></span>Blueprints', 'pzucd-blueprint-designer'),
    );

    $args = array(
      'labels'              => $labels,
      'description'         => __('Architect Blueprints are used to create reusable layout Blueprints for use in your Architect blocks, widgets, shortcodes and WP template tags. These are made up of cells, sections, criteria and navigation'),
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
      'menu_position'       => 30,
      'supports'            => array('title', 'revisions'),
      'exclude_from_search' => true,
    );

    register_post_type('ucd-blueprints', $args);
  }

} // EOC


add_filter('cmb_meta_boxes', 'pzucd_blueprint_wizard_metabox');
function pzucd_blueprint_wizard_metabox($meta_boxes = array())
{
  $prefix        = '_pzucd_';
  $fields        = array(
    array(
      'name'    => 'Select what type of blueprint do you want to make',
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
    'pages'    => 'ucd-blueprints',
    'context'  => 'normal',
    'priority' => 'high',
    'fields'   => $fields // An array of fields.
  );

  return $meta_boxes;
}

add_filter('cmb_meta_boxes', 'pzucd_blueprint_preview_metabox');
function pzucd_blueprint_preview_metabox($meta_boxes = array())
{
  // Need to redesign this into one layout that includes navigation positions
  $prefix = '_pzucd_';
  $fields = array(

//    array(
//      'id'       => $prefix . 'sections-preview-desc',
//      'type' => 'pzinfo',
//      'desc' => __('The preview gives you a feel for how you layout might look only.','pzucd')
//    ),
    array(
      'name' => 'Section 1',
      'id'       => $prefix . '0-sections-preview',
      'cols'     => 12,
      'type'     => 'pzlayout',
      'readonly' => false, // Readonly fields can't be written to by code! Weird
      'code'     => draw_sections_preview(0),
      'desc'     => __('', 'pzucd')
    ),
    array(
      'name' => 'Section 2',
      'id'       => $prefix . '1-sections-preview',
      'cols'     => 12,
      'type'     => 'pzlayout',
      'readonly' => false, // Readonly fields can't be written to by code! Weird
      'code'     => draw_sections_preview(1),
      'desc'     => __('', 'pzucd')
    ),
    array(
      'name' => 'Section 3',
      'id'       => $prefix . '2-sections-preview',
      'cols'     => 12,
      'type'     => 'pzlayout',
      'readonly' => false, // Readonly fields can't be written to by code! Weird
      'code'     => draw_sections_preview(2),
      'desc'     => __('', 'pzucd')
    ),
  );

  $meta_boxes[ ] = array(
    'title'    => 'Blueprint Preview',
    'pages'    => 'ucd-blueprints',
    'fields'   => $fields,
    'context'  => 'side',
    'priority' => 'default'

  );

  return $meta_boxes;
}


add_filter('cmb_meta_boxes', 'pzucd_sections_preview_meta');
function pzucd_sections_preview_meta($meta_boxes = array())
{
  $prefix = '_pzucd_';

  $args = array(
    'posts_per_page'   => -1,
    'orderby'          => 'title',
    'order'            => 'ASC',
    'post_type'        => 'ucd-layouts',
    'suppress_filters' => true);

  $pzucd_cells       = get_posts($args);
  $pzucd_cells_array = array();
  if (!empty($pzucd_cells))
  {
    foreach ($pzucd_cells as $pzucd_cell)
    {
      $pzucd_cells_array[ $pzucd_cell->ID ] = (empty($pzucd_cell->post_title) ? 'No title' : $pzucd_cell->post_title);
    }
  }
  else
  {
    $pzucd_cells_array = array(0 => 'No cell layouts. Create some.');
  }
  // ID,post_title
  for ($i = 0; $i < 3; $i++)
  {
    $fields        = array(

            array(
                    'id'   => $prefix . $i. 'section-layout-header',
                    'name' => 'Section '.($i+1).' Layout',
                    'type' => 'title',
                    'cols'=>12,
            ),
      array(
        'id'   => $prefix . $i . '-blueprint-section-title',
        'name' => __('Section '.($i+1).' title (optional)', 'pzucd'),
        'type' => 'text',
        'cols' => 12,
      ),
      array(
        'name'    => __('Cells per section', 'pzucd'),
        'id'      => $prefix . $i . '-blueprint-cells-per-view',
        'type'    => 'pzspinner',
        'default' => 0,
        'min'     => 0,
        'max'     => 99,
        'cols'    => 2,
        'desc' => '0 for unlimited'
      ),
      array(
        'name'    => __('Cells across', 'pzucd'),
        'id'      => $prefix . $i . '-blueprint-cells-across',
        'type'    => 'pzspinner',
        'default' => 3,
        'min'     => 1,
        'cols'    => 2,
        'max'     => 999,
      ),
      array(
        'name'    => __('Minimum cell width', 'pzucd'),
        'id'      => $prefix . $i . '-blueprint-min-cell-width',
        'type'    => 'pzspinner',
        'alt'     => 'mincellw',
        'default' => 0,
        'min'     => '0',
        'max'     => 9999,
        'cols'    => 2,
        'step'    => '1',
        'suffix'  => 'px',
//      'desc'    => __('Set the minimum width for sells in this section. This helps with responsive layout', 'pzucd')
      ),

      array(
        'name'    => __('Cells vertical margin', 'pzucd'),
        'id'      => $prefix . $i . '-blueprint-cells-vert-margin',
        'type'    => 'pzspinner',
        'alt'     => 'gutterv',
        'default' => '1',
        'min'     => '0',
        'max'     => '100',
        'cols'    => 3,
        'step'    => '1',
        'suffix'  => '%',
        //    'desc'    => __('Set the vertical gutter width as a percentage of the section width. The gutter is the gap between adjoining elements', 'pzucd')
      ),
      array(
        'name'    => __('Cells horizontal margin', 'pzucd'),
        'id'      => $prefix . $i . '-blueprint-cells-horiz-margin',
        'type'    => 'pzspinner',
        'alt'     => 'gutterh',
        'default' => '1',
        'min'     => '0',
        'max'     => '100',
        'cols'    => 3,
        'step'    => '1',
        'suffix'  => '%',
//      'desc'    => __('Set the horizontal gutter width as a percentage of the section width. The gutter is the gap between adjoining elements', 'pzucd')
      ),
      array(
        'id'      => $prefix . $i . '-blueprint-section-navigation',
        'name'    => __('Navigation', 'pzucd'),
        'type'    => 'pzselect',
        'cols'    => 4,
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
        'name'    => 'Navigation Position',
        'id'      => $prefix . $i . '-blueprint-section-nav-pos',
        'type'    => 'radio',
        'cols'    => 3,
        'default' => 'bottom',
        'options' => array(
          'left'   => 'Left',
          'right'  => 'Right',
          'top'    => 'Top',
          'bottom' => 'Bottom',
        )
      ),
      array(
        'name'    => 'Navigation Location',
        'id'      => $prefix . $i . '-blueprint-section-nav-loc',
        'type'    => 'radio',
        'cols'    => 3,
        'default' => 'outside',
        'options' => array(
          'inside'  => 'Inside',
          'outside' => 'Outside',
        )
      ),

      array(
        'id'      => $prefix . $i . '-blueprint-section-cell-layout',
        'name'    => __('Cells layout', 'pzucd'),
        'type'    => 'pzselect',
        'cols'    => 6,
        
        'options' => $pzucd_cells_array
      ),
      array(
        'name'    => __('Layout mode', 'pzucd'),
        'id'      => $prefix . $i . '-blueprint-layout-mode',
        'type'    => 'pzselect',
        'default' => 'fitRows',
        'cols'    => 3,
        'options' => array(
          'basic'=> 'Basic (CSS only)',
//          'fitRows'         => 'Fit rows',
//          'fitColumns'      => 'Fit columns',
          'masonry'         => 'Masonry (Pinterest-like)',
//          'masonryVertical' => 'Masonry Vertical',
//          'cellsByRow'      => 'Cells by row',
//          'cellsByColumn'   => 'Cells by column',
//          'vertical'    => 'Vertical',
//          'horizontal'  => 'Horizontal',
        ),
        //       'desc'    => __('Choose how you want the cells to display. With evenly sized cells, you\'ll see little difference. Please visit <a href="http://isotope.metafizzy.co/demos/layout-modes.html" target=_blank>Isotope Layout Modes</a> for demonstrations of these layouts', 'pzucd')
      ),



    );
    $meta_boxes[ ] = array(
      'title'    => 'Blueprint Section ' . ($i + 1),
      'pages'    => 'ucd-blueprints',
      'context'  => 'normal',
      'priority' => 'high',
      'fields'   => $fields // An array of fields.
    );
  }

  return $meta_boxes;
}


add_filter('cmb_meta_boxes', 'pzucd_blueprint_settings_metabox');
function pzucd_blueprint_settings_metabox($meta_boxes = array())
{
  $args = array(
    'posts_per_page'   => -1,
    'orderby'          => 'title',
    'order'            => 'ASC',
    'post_type'        => 'ucd-criterias',
    'suppress_filters' => true);


  $pzucd_criterias       = get_posts($args);
  $pzucd_criterias_array['default'] = 'Use default content for the displayed page';
  if (!empty($pzucd_criterias))
  {
    foreach ($pzucd_criterias as $pzucd_criteria)
    {
      $pzucd_criterias_array[ $pzucd_criteria->ID ] = (empty($pzucd_criteria->post_title) ? 'No title' : $pzucd_criteria->post_title);
    }
  }
  $prefix = '_pzucd_';
  $fields = array(
    array(
      'id'      => $prefix . 'blueprint-short-name',
      'name'    => __('Blueprint Short Name', 'pzucd'),
      'type'    => 'text',
      'cols'    => 12,
      
      'desc'    => __('Alphanumeric only. <br/>Use the shortcode <strong class="pzucd-usage-info">[pzucd <span class="pzucd-shortname"></span>]</strong> or the blueprint tag <strong class="pzucd-usage-info">pzucd(\'<span class="pzucd-shortname"></span>\');</strong>', 'pzucd')
    ),
    array(
      'id'      => $prefix . 'blueprint-criteria',
      'name'    => __('Criteria', 'pzucd'),
      'type'    => 'pzselect',
      'cols'    => 12,
      'default' => 'default',
      'options' => $pzucd_criterias_array
    ),
    array(
      'id'      => $prefix . 'blueprint-pager',
      'name'    => __('Pagination', 'pzucd'),
      'type'    => 'pzselect',
      'cols'    => 12,
      'default' => 'wppagination',
      'options' => array(
        'none'        => 'None',
        'wordpress' => 'WP pagination',
        'wppagenavi'    => 'PageNavi',
        'hover'       => 'Hover buttons'
      )
    ),
    array(
      'id'      => $prefix . 'blueprint-posts-per-page',
      'name'    => __('Posts per page', 'pzucd'),
      'type'    => 'text',
      'cols'    => 12,
      'default' => 10,
    ),
    array(
      'name'    => 'Section 1',
      'id'      => $prefix . '0-blueprint-section-enable',
      'type'    => 'checkbox',
      'cols'    => 4,
      'default' => true,
    ),
    array(
      'name'    => 'Section 2',
      'id'      => $prefix . '1-blueprint-section-enable',
      'type'    => 'checkbox',
      'cols'    => 4,
      'default' => false
    ),
    array(
      'name'    => 'Section 3',
      'id'      => $prefix . '2-blueprint-section-enable',
      'type'    => 'checkbox',
      'cols'    => 4,
      'default' => false
    ),

    array(
      'name' => 'Save blueprint',
      'id'      => $prefix . 'layout-set-save',
      'type'    => 'pzsubmit',
      'default' => 'Save'
    ),
  );

  $meta_boxes[ ] = array(
    'title'    => 'Blueprint settings',
    'pages'    => 'ucd-blueprints',
    'fields'   => $fields,
    'context'  => 'side',
    'priority' => 'high'

  );

  return $meta_boxes;
}


function draw_sections_preview($section_number)
{
  $return_html = '';

  // Put in a hidden field with the plugin url for use in js
  $return_html = '
  <div id="pzucd-sections-preview-' . $section_number . '" class="pzucd-sections pzucd-section-' . $section_number . '">
	</div>
		<div class="plugin_url" style="display:none;">' . PZUCD_PLUGIN_URL . '</div>
	';

  return $return_html;
}

