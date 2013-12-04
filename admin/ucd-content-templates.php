<?php

class pzucd_Content_templates extends pzucdForm
{

  private $mb_fields;

  /**
   * [__construct description]
   */
  function __construct()
  {
    add_action('init', array($this, 'create_templates_post_type'));
    // This overrides the one in the parent class

    if (is_admin())
    {

      if (!class_exists('CMB_Meta_Box'))
      {
        require_once PZUCD_PLUGIN_PATH . 'external/HM-Custom-Meta-Boxes/custom-meta-boxes.php';
      }

      //	add_action('admin_init', 'pzucd_preview_meta');
      add_action('add_meta_boxes', array($this, 'templates_meta'));
      add_action('admin_head', array($this, 'content_templates_admin_head'));
      add_action('admin_enqueue_scripts', array($this, 'content_templates_admin_enqueue'));
//			add_filter('manage_ucd-templates_posts_columns', array($this, 'add_template_columns'));
//			add_action('manage_ucd-templates_posts_custom_column', array($this, 'add_template_column_content'), 10, 2);

      // check screen ucd-templates. ugh. doesn't work for save and edit
//			if ( $_REQUEST[ 'post_type' ] == 'ucd-templates' )
//			{
//			}
    }

  }

  /**
   * [content_templates_admin_enqueue description]
   * @param  [type] $hook [description]
   * @return [type]       [description]
   */
  public function content_templates_admin_enqueue($hook)
  {
    $screen = get_current_screen();
    if ('ucd-templates' == $screen->id)
    {


      wp_enqueue_style('pzucd-admin-templates-css', PZUCD_PLUGIN_URL . 'admin/css/ucd-admin-templates.css');

      wp_enqueue_script('jquery-pzucd-metaboxes-templates', PZUCD_PLUGIN_URL . 'admin/js/ucd-metaboxes-templates.js', array('jquery'));
      wp_enqueue_script('jquery-isotope', PZUCD_PLUGIN_URL . 'external/jquery.isotope.min.js', array('jquery'));
      wp_enqueue_script('jquery-isotope', PZUCD_PLUGIN_URL . 'external/masonry.pkgd.min.js', array('jquery'));
    }
  }

  /**
   * [content_templates_admin_head description]
   * @return [type] [description]
   */
  public function content_templates_admin_head()
  {

  }

  /**
   * [add_template_columns description]
   * @param [type] $columns [description]
   */
  public function add_template_columns($columns)
  {
    unset($columns[ 'thumbnail' ]);
    $pzucd_front  = array_slice($columns, 0, 2);
    $pzucd_back   = array_slice($columns, 2);
    $pzucd_insert =
            array
            (
              'pzucd_template_short_name' => __('Template short name', 'pzsp'),
            );

    return array_merge($pzucd_front, $pzucd_insert, $pzucd_back);
  }

  /**
   * [add_template_column_content description]
   * @param [type] $column  [description]
   * @param [type] $post_id [description]
   */
  public function add_template_column_content($column, $post_id)
  {
    switch ($column)
    {
      case 'pzucd_short_name':
        echo get_post_meta($post_id, 'pzucd_template_short-name', true);
        break;
    }
  }

  /**
   * [create_templates_post_type description]
   * @return [type] [description]
   */
  public function create_templates_post_type()
  {
    $labels = array(
      'name'               => _x('Templates', 'post type general name'),
      'singular_name'      => _x('Template', 'post type singular name'),
      'add_new'            => __('Add New Template'),
      'add_new_item'       => __('Add New Template'),
      'edit_item'          => __('Edit Template'),
      'new_item'           => __('New Template'),
      'view_item'          => __('View Template'),
      'search_items'       => __('Search Templates'),
      'not_found'          => __('No Templates found'),
      'not_found_in_trash' => __('No Templates found in Trash'),
      'parent_item_colon'  => '',
      'menu_name'          => _x('Templates', 'pzucd-template-designer'),
    );

    $args = array(
      'labels'              => $labels,
      'description'         => __('Ultimate Content Display templates are used to create reusable layout templates for use in your UCD blocks, widgets, shortcodes and WP template tags. These are made up of cells, sections, criteria and navigation'),
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

    register_post_type('ucd-templates', $args);
  }

} // EOC

add_filter('cmb_meta_boxes', 'pzucd_template_preview_metabox');
function pzucd_template_preview_metabox($meta_boxes = array())
{
  $prefix        = '_pzucd_';
  $fields        = array(
    array(
      'id'       => $prefix . '0-sections-preview',
      'cols'     => 12,
      'type'     => 'pzlayout',
      'readonly' => false, // Readonly fields can't be written to by code! Weird
      'code'     => draw_sections_preview(0),
      'default'  => 'May not need anything',
      'desc'     => __('', 'pzucd')
    ),
    array(
      'id'       => $prefix .'1-sections-preview',
      'cols'     => 12,
      'type'     => 'pzlayout',
      'readonly' => false, // Readonly fields can't be written to by code! Weird
      'code'     => draw_sections_preview(1),
      'default'  => 'May not need anything',
      'desc'     => __('', 'pzucd')
    ),
    array(
      'id'       => $prefix . '2-sections-preview',
      'cols'     => 12,
      'type'     => 'pzlayout',
      'readonly' => false, // Readonly fields can't be written to by code! Weird
      'code'     => draw_sections_preview(2),
      'default'  => 'May not need anything',
      'desc'     => __('', 'pzucd')
    ),
  );
  $meta_boxes[ ] = array(
    'title'    => 'Template Preview',
    'pages'    => 'ucd-templates',
    'fields'   => $fields,
    'context'  => 'normal',
    'priority' => 'high'

  );

  return $meta_boxes;
}



add_filter('cmb_meta_boxes', 'pzucd_sections_preview_meta');
function pzucd_sections_preview_meta($meta_boxes = array())
{
  $prefix        = '_pzucd_';

  $args = array(
    'posts_per_page'   => -1,
    'orderby'          => 'title',
    'order'            => 'ASC',
    'post_type'        => 'ucd-layouts',
    'suppress_filters' => true );

  $pzucd_cells = get_posts($args);
  $pzucd_cells_array = array();
  if (!empty($pzucd_cells)) {
    foreach($pzucd_cells as $pzucd_cell){
      $pzucd_cells_array[$pzucd_cell->ID] = (empty($pzucd_cell->post_title)?'No title':$pzucd_cell->post_title);
    }
  } else {
    $pzucd_cells_array = array(0=>'No cell layouts. Create some.');
  }
  // ID,post_title
  for ($i = 0; $i < 3; $i++)
  {
    $fields = array(

      array(
        'id'      => $prefix . $i . '-template-section-title',
        'name'    => __('Section title', 'pzucd'),
        'type'    => 'text',
        'cols'    => 12,
        'desc' => 'Section title is optional'
      ),
      array(
        'name'    => __('Cells per section', 'pzucd'),
        'id'      => $prefix . $i . '-template-cells-per-view',
        'type'    => 'pzspinner',
        'default' => '9',
        'min'     => 1,
        'max'     => 999,
        'cols'    => 2,
      ),
      array(
        'name'    => __('Cells across', 'pzucd'),
        'id'      => $prefix . $i . '-template-cells-across',
        'type'    => 'pzspinner',
        'default' => '3',
        'min'     => 1,
        'cols'    => 2,
        'max'     => 999,
      ),
      array(
        'name'    => __('Minimum cell width', 'pzucd'),
        'id'      => $prefix . $i . '-template-min-cell-width',
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
        'id'      => $prefix . $i . '-template-cells-vert-margin',
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
        'id'      => $prefix . $i . '-template-cells-horiz-margin',
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
        'id'      => $prefix . $i . '-template-section-cell-layout',
        'name'    => __('Cells layout', 'pzucd'),
        'type'    => 'pzselect',
        'cols'    => 9,
        'default' => '',
        'options' => $pzucd_cells_array
      ),
      array(
        'name'    => __('Layout mode', 'pzucd'),
        'id'      => $prefix . $i . '-template-layout-mode',
        'type'    => 'pzselect',
        'default' => 'fitRows',
        'cols'    => 3,
        'options' => array(
          'fitRows'         => 'Fit rows',
          'fitColumns'      => 'Fit columns',
          'masonry'         => 'Masonry (Pinterest-like)',
          'masonryVertical' => 'Masonry Vertical',
          'cellsByRow'      => 'Cells by row',
          'cellsByColumn'   => 'Cells by column',
          'straightDown'    => 'Straight down',
          'straightAcross'  => 'Straight across',
        ),
 //       'desc'    => __('Choose how you want the cells to display. With evenly sized cells, you\'ll see little difference. Please visit <a href="http://isotope.metafizzy.co/demos/layout-modes.html" target=_blank>Isotope Layout Modes</a> for demonstrations of these layouts', 'pzucd')
      ),
    );
    $meta_boxes[ ] = array(
      'title'    => 'Template Section '.($i+1),
      'pages'    => 'ucd-templates',
      'context'  => 'normal',
      'priority' => 'high',
      'fields'         => $fields // An array of fields.
    );
  }

  return $meta_boxes;
}


add_filter('cmb_meta_boxes', 'pzucd_template_settings_metabox');
function pzucd_template_settings_metabox($meta_boxes = array())
{
  $args = array(
    'posts_per_page'   => -1,
    'orderby'          => 'title',
    'order'            => 'ASC',
    'post_type'        => 'ucd-criterias',
    'suppress_filters' => true );


  $pzucd_criterias = get_posts($args);
  $pzucd_criterias_array = array();
  if (!empty($pzucd_criterias)) {
    foreach($pzucd_criterias as $pzucd_criteria){
      $pzucd_criterias_array[$pzucd_criteria->ID] = (empty($pzucd_criteria->post_title)?'No title':$pzucd_criteria->post_title);
    }
  } else {
    $pzucd_criterias_array = array(0=>'No criterias defined. Create some.');
  }
  $prefix        = '_pzucd_';
  $fields        = array(
    array(
      'id' => $prefix.'template-short-name',
      'name' => __('Template Short Name','pzucd'),
      'type' => 'text',
      'cols' => 12,
      'default' => '',
      'desc' => __('Alphanumeric only','pzucd')
    ),
    array(
      'id'      => $prefix . 'template-criteria',
      'name'    => __('Criteria', 'pzucd'),
      'type'    => 'pzselect',
      'cols'    => 12,
      'default' => '',
      'options' => $pzucd_criterias_array
    ),
    array(
      'id'      => $prefix . 'template-controls',
      'name'    => __('Navigation', 'pzucd'),
      'type'    => 'pzselect',
      'cols'    => 12,
      'default' => 'pagination',
      'options' => array(
        'wpagination' => 'WP pagination',
        'pagenavi'    => 'PageNavi',
        'player'      => 'Media Player',
        'titles'      => 'Titles',
        'bullets'     => 'Bullets',
        'numbers'     => 'Numbers',
        'thumbs'      => 'Thumbnails'
      )
    ),
    array(
      'name' => 'Enable section 1',
      'id' => $prefix . '0-template-section-enable',
      'type' => 'checkbox',
      'cols'=> 12,
      'default' => true,
    ),
    array(
      'name' => 'Enable section 2',
      'id' => $prefix . '1-template-section-enable',
      'type' => 'checkbox',
      'cols'=> 12,
      'default' => false
    ),
    array(
      'name' => 'Enable section 3',
      'id' => $prefix . '2-template-section-enable',
      'type' => 'checkbox',
      'cols'=> 12,
      'default' => false
    ),

    array(
      'id'      => $prefix . 'layout-set-save',
      'type'    => 'pzsubmit',
      'default' => 'Save'
    ),
  );
  $meta_boxes[ ] = array(
    'title'    => 'Template settings',
    'pages'    => 'ucd-templates',
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
  <div id="pzucd-sections-preview-'.$section_number.'" class="pzucd-sections pzucd-section-'.$section_number.'">';
  $return_html .= '</div>
	</div>
	';

  return $return_html;
}
